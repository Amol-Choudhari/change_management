<?php
namespace App\Controller;

use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;

	class HoinspectionsController extends AppController{

		var $name = 'Hoinspections';

		public function initialize(): void
		{
			parent::initialize();
			
			$this->loadComponent('Customfunctions');
			$this->loadComponent('Mastertablecontent');
			$this->loadComponent('Randomfunctions');
			$this->loadComponent('Flowbuttons');
			$this->viewBuilder()->setLayout('admin_dashboard');
			$this->viewBuilder()->setHelpers(['Form','Html']);
			$this->Session = $this->getRequest()->getSession();
			
		}
			
		public function beforeFilter($event) {

			parent::beforeFilter($event);
			
			if($this->Session->read('username') == null){
				$this->customAlertPage("Sorry You are not authorized to view this page..");
				exit();
			}
			else{
				//checkif user have HO level roles
				$this->loadModel('DmiUserRoles');
				$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('OR'=>array('ro_inspection'=>'yes','so_inspection'=>'yes','dy_ama'=>'yes','ho_mo_smo'=>'yes','jt_ama'=>'yes',
																								'ama'=>'yes','super_admin'=>'yes','pao'=>'yes'),'user_email_id'=>$this->Session->read('username'))))->first();
				if(empty($user_access)){
					$this->customAlertPage("Sorry You are not authorized to view this page..");
					exit;
				}
			}

		}

		public function fetchRecordId($id,$mode,$application_type){

			$this->loadModel('DmiFirms');
			$customer_id_result = $this->DmiFirms->find('all',array('fields'=>'customer_id', 'conditions'=>array('id IS'=>$id)))->first();
			$customer_id = $customer_id_result['customer_id'];
			$this->Session->write('customer_id',$customer_id);
			$this->Session->write('application_mode',$mode);
			$this->Session->write('application_type',$application_type);

			$this->redirect(array('controller'=>'hoinspections', 'action'=>'ho-inspection'));
		}


		public function hoInspection(){

			$this->loadModel('DmiFirms');
			$this->loadModel('DmiFlowWiseTablesLists');
			$this->loadModel('DmiUserRoles');
			
			$customer_id = $this->Session->read('customer_id');

			//updated on 20-05-2021 by Amol from live current version code
			$show_check_msg_popup = 'no';
			$approval_on = 'None';
			$check_result = $this->checkFormsAndReportsAllApproved($customer_id);
			if($check_result==1){			
				$show_check_msg_popup = 'yes';
				$approval_on = 'Application Form Sections';
			}elseif($check_result==2){
				$show_check_msg_popup = 'yes';
				$approval_on = 'Inspection Report Sections';
			}elseif($check_result==3){
				$show_check_msg_popup = 'yes';
				$approval_on = 'Application Form Sections and Inspection Report Sections';
			}
			$this->set(compact('show_check_msg_popup','approval_on'));
			
			if (null!==($this->request->getData('approve_check'))){
				//call the function to update records
				if($this->updateFormsAndReportsAllApproved($customer_id)==false){
					return false;
				}
			}
			if (null!==($this->request->getData('cancel_check'))){
				
				$this->Redirect(array('controller'=>'dashboard','action'=>'home'));
			}
			//till here
			
			$application_type = $this->Session->read('application_type');
			$username = $this->Session->read('username');

			//as per new order by 01-04-2021 from DMI
			//if lab is NABL accreditated then manage grant button for RO and HO
			//applied on 30-09-2021 by Amol
			$NablDate = $this->Randomfunctions->checkIfLabNablAccreditated($customer_id);
			$this->set('NablDate',$NablDate);

			$firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
			$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
			$this->set('office_type',$office_type);

			// Fetch grant date conditions get latest records.
			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

			$flow_wise_table = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();

			$ho_comments_table = $flow_wise_table['ho_comment_reply'];
			$this->loadModel($ho_comments_table);

			$ro_so_comments_table = $flow_wise_table['ro_so_comments'];
			$this->loadModel($ro_so_comments_table);

			$appl_current_pos_table = $flow_wise_table['appl_current_pos'];
			$this->loadModel($appl_current_pos_table);

			$appl_pdf_table = $flow_wise_table['app_pdf_record'];
			$this->loadModel($appl_pdf_table);

			$report_pdf_table = $flow_wise_table['inspection_pdf_record'];
			$this->loadModel($report_pdf_table);

			$allocation_table = $flow_wise_table['allocation'];
			$this->loadModel($allocation_table);

			$final_report_table = $flow_wise_table['inspection_report'];
			$this->loadModel($final_report_table);

			$ama_approved_table = $flow_wise_table['ama_approved_application'];
			$this->loadModel($ama_approved_table);

			$ho_allocation_table = $flow_wise_table['ho_level_allocation'];
			$this->loadModel($ho_allocation_table);

			$firm_details = $this->DmiFirms->firmDetails($customer_id);
			$this->set('firm_name',$firm_details['firm_name']);

			// fetch comments history
			$ho_comment_details = $this->$ho_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'OR'=>array('comment_by IS'=>$username,'comment_to'=>$username),$grantDateCondition),'order'=>'id'))->toArray();
			$this->set('ho_comment_details',$ho_comment_details);

			//check current allocated user
			$current_allocation = $this->$ho_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();
			$this->set('current_allocated_to',$current_allocation['current_level']);

			// fetch application pdf record
			$application_pdf_path = $this->$appl_pdf_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
			$this->set('download_application_pdf',$application_pdf_path['pdf_file']);

			//condition added on 08-03-2023 for CA export report and pdf links
			$checkExport = $this->Customfunctions->checkApplicantExportUnit($customer_id);
			if ($application_type==1 && $checkExport=='yes') {			
				$report_pdf_table = 'DmiCaExportSiteinspectionReports';
				$this->loadModel($report_pdf_table);
				
			}
			//added applicationtype==3 condition on 05-04-2023, to get change report table
			elseif($application_type==3){
				$report_pdf_table = 'DmiChangeSiteinspectionReports';
				$this->loadModel($report_pdf_table);				
			}

			// fetch inspection report pdf record
			$report_pdf_path = $this->$report_pdf_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
			if(empty($report_pdf_path)){
				$this->set('download_report_pdf',null);
				$this->set('report_pdf_path',null);

			}else{
				//condition added on 08-03-2023 for CA export report and pdf links
				if(($application_type==1 && $checkExport=='yes') || $application_type==3){//added applicationtype==3 condition on 05-04-2023
					$this->set('download_report_pdf',$report_pdf_path['report_docs']);
				}else{
					$this->set('download_report_pdf',$report_pdf_path['pdf_file']);
				}
				
				$this->set('report_pdf_path',$report_pdf_path);
			}
			
			

			// Check current user roles
			$check_user_role = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$username)))->first();
			$this->set('check_user_role',$check_user_role);

			$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
			$this->set('split_customer_id',$split_customer_id);

			//check CA BEVO Applicant
			$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
			$this->set('ca_bevo_applicant',$ca_bevo_applicant);

			//Added on 19-09-2017 check lab export unit
			$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
			$this->set('export_unit_status',$export_unit_status);

			//added below condition for lab export/domestic grant pdf file name
			//on 20-09-2021
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
			$section_details = array();
			$section_details['grant_pdf'] = '';
			if (($application_type==1 || $application_type==2) && ($form_type=='C' || $form_type=='D')) {

				$section_details['grant_pdf']='grantLaboratoryCertificatePdf';

			//Else If Block Statement is  added for export lab for Application Type -> 8 (ADP Flow) grant pdf by shankhpal shende on 17/11/2022
			}elseif($application_type==8 && $form_type=='ADP'){ 
				$section_details['grant_pdf']='grantAdpCertificate';

			}
			$this->set('section_details',$section_details);


			//to view forms
			if (null!==($this->request->getData('view_applicant_forms'))){

				$appl_view_link = '/scrutiny/form_scrutiny_fetch_id/'.$firm_details['id'].'/view/'.$application_type.'/yes';
				$this->redirect($appl_view_link);

			}

			//to view reports
			if (null!==($this->request->getData('view_inspection_reports'))){

				$report_link = '/inspections/inspection_report_fetch_id/'.$firm_details['id'].'/view/'.$application_type.'/yes';
				$this->redirect($report_link);

			}

			//below query added on 18-09-2017 by Amol to show JAT Report link
		//	$check_jat_report_filed = $this->Dmi_jat_uploaded_report->find('first',array('conditions'=>array('customer_id'=>$customer_id, 'finalized_report !='=>null)));
		//	$this->set('check_jat_report_filed',$check_jat_report_filed);

			// set variables to show popup messages from view file
			$message = '';
			$message_theme = '';
			$redirect_to = '';



			//to send and save comment

			if (null!==($this->request->getData('send_comment'))){

				//get user Position from whome comment sent //added on 07-03-2018 by Amol
				if($this->Session->read('current_level')=='level_3'){

					$from_user = 'ro';

				}elseif($this->Session->read('current_level')=='level_4'){

					//check user is DYAMA/JTAMA/AMA
					if($check_user_role['ho_mo_smo']=='yes'){

						$from_user = 'ho_mo_smo';

					}elseif($check_user_role['dy_ama']=='yes'){

						$from_user = 'dy_ama';

					}elseif($check_user_role['jt_ama']=='yes'){

						$from_user = 'jt_ama';

					}elseif($check_user_role['ama']=='yes'){

						$from_user = 'ama';
					}
				}elseif($this->Session->read('current_level')=='level_1'){

					if($check_user_role['ho_mo_smo']=='yes'){

						$from_user = 'ho_mo_smo';

					}
				}

				//html encoding post data before saving
				$htmlencoded_comment = htmlentities($this->request->getData('comment'), ENT_QUOTES);
				$comment_to = $this->request->getData('comment_to');


				if(!empty($comment_to) && !empty($htmlencoded_comment))//condition added on 10-04-2017 by Amol
				{
					$find_allocation = $this->$allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();

					if($comment_to == 'ro')
					{
						if($office_type == 'RO'){
							$comment_to_email_id = $find_allocation['level_3'];
						}elseif($office_type == 'SO'){
							$comment_to_email_id = $find_allocation['level_4_ro'];
						}

						$to_user = 'ro';

					}
					elseif($comment_to == 'so')
					{
						$comment_to_email_id = $find_allocation['level_3'];
						$comment_to_level = 'level_3';
						$ho_comments_table = $ro_so_comments_table;
						$ho_allocation_table = $allocation_table;
						$to_user = 'so';
					}
					elseif($comment_to == 'dy_ama')
					{
						$find_ho_allocation = $this->$ho_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();
						$comment_to_email_id = $find_ho_allocation['dy_ama'];
						$to_user = 'dy_ama';

					}
					elseif($comment_to == 'ho_mo_smo')
					{
						$find_ho_allocation = $this->$ho_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,  $grantDateCondition)))->first();

						if(!empty($find_ho_allocation['ho_mo_smo']))
						{

							$comment_to_email_id = $find_ho_allocation['ho_mo_smo'];
							$to_user = 'ho_mo_smo';

						}else{
							$message = 'Sorry... Please allocate this application to MO/SMO first';
							$message_theme = 'warning';
							$redirect_to = '../hoinspections/ho-inspection';
						}

					}
					elseif($comment_to == 'jt_ama')
					{
						$find_ho_allocation = $this->$ho_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,  $grantDateCondition)))->first();

						if(!empty($find_ho_allocation['jt_ama']))
						{
							$comment_to_email_id = $find_ho_allocation['jt_ama'];

							$to_user = 'jt_ama';

						}else{

							$message = 'Sorry... Please allocate this application to JT AMA first';
							$message_theme = 'warning';
							$redirect_to = '../hoinspections/ho-inspection';
						}


					}
					elseif($comment_to == 'ama')
					{
						$find_ho_allocation = $this->$ho_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,  $grantDateCondition)))->first();

						if(!empty($find_ho_allocation['ama']))
						{
							$comment_to_email_id = $find_ho_allocation['ama'];
							$to_user = 'ama';

						}else{
							$message = 'Sorry... Please allocate this application to AMA';
							$message_theme = 'warning';
							$redirect_to = '../hoinspections/ho-inspection';
						}
					}

					//function return the value of sms_id on comment_by to comment_to on HO level communication(Like Dy_ama to Jt.Ama, Jt_ama to Ama)
					// Created on 23-08-2017 by pravin
					$sms_id = $this->Customfunctions->checkHoLevelSmsId($check_user_role,$comment_to);


					if(!empty($comment_to_email_id))//Condition added on 10-04-2017 by Amol
					{
						$ho_comments_entity = $this->$ho_comments_table->newEntity(array(

								'customer_id'=>$customer_id,
								'comment_by'=>$username,
								'comment_to'=>$comment_to_email_id,
								'comment_date'=>date('Y-m-d H:i:s'),
								'comment'=>$htmlencoded_comment,
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s'),
								'from_user'=>$from_user,
								'to_user'=>$to_user
						));

						if($this->$ho_comments_table->save($ho_comments_entity)){

								//update ho_allocation current level // added on 07-11-2017 by Amol
								$this->$ho_allocation_table->updateAll(array('current_level' => "$comment_to_email_id"),array('customer_id IS' => $customer_id,  $grantDateCondition));
								
								$check_if_report_fileds = $this->Customfunctions->finalSubmitDetails($customer_id,'inspection_report',$application_type);
								
								//when there is no site inspection report exists, added on 28-09-2021 by Amol
								if(empty($check_if_report_fileds)){
									$check_if_report_fileds = array();//if empty set blank array as default
									$check_if_report_fileds['id']='0';
								}
								/* Check the send_to radio button option, if it is "dy_ama" then update status value "siteinspection_final_report" table.
								   to hide the all buttons on application forms of communication with applicant box(replied window) on RO dashboard */

								if($comment_to == 'dy_ama'){

									$ho_status = 'ho_allocated';
									$this->$final_report_table->updateAll(array('status' => "$ho_status"),array('id IS' => $check_if_report_fileds['id']));
								}

								if($comment_to == 'ro')
								{
									/* Check the send_to radio button option, if it is "ro" then update status value "siteinspection_final_report" table.
									   to show the all buttons on application forms of communication with applicant box(replied window) on RO dashboard
									*/
									$check_ama_approval = $this->$ama_approved_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'status'=>'approved',  $grantDateCondition)))->first();

									if(empty($check_ama_approval)){

										$ho_status = 'replied';
										$this->$final_report_table->updateAll(array('status' => "$ho_status"),array('id IS' => $check_if_report_fileds['id']));
									}

									if($office_type == 'RO'){
										$current_level = 'level_3';
									}elseif($office_type == 'SO'){
										$current_level = 'level_4_ro';
									}

								}elseif($comment_to == 'so'){

									$current_level = 'level_3';

								}else{
									$current_level = 'level_4';
								}


								//Update record in all applications current position table
								$user_email_id = $comment_to_email_id;
								$this->$appl_current_pos_table->currentUserUpdate($customer_id,$user_email_id,$current_level);//call to custom function from model

								$this->Session->write('application_mode','view');

								#SMS: Ho Level All SMS
								//$this->DmiSmsEmailTemplates->sendMessage($sms_id,$customer_id);

								if($comment_to == 'so'){

									$redirect_to_ul = '../dashboard/home';
								}else{
									$redirect_to_ul = '../hoinspections/ho-inspection';
								}

								$message = 'Your Comment is successfully sent';
								$message_theme = 'success';
								$redirect_to = $redirect_to_ul;
						}
					}

				}
				else{

					$message = 'Sorry.. User not selected or Comment box is blank';
					$message_theme = 'failed';
					$redirect_to = '../hoinspections/ho-inspection';
				}

			}

			//This button will only seen by AMA to send Final approval to RO
			if (null!==($this->request->getData('approved_by_ama'))){

				//added this line on 05-05-2020 by Amol, to add commenting on Approval
				$approval_comment = htmlentities($this->request->getData('approval_comment'), ENT_QUOTES);
				$ama_approved = $this->$ama_approved_table->saveAmaApproved($approval_comment,$application_type);

				if($ama_approved == 1){

					$this->Session->write('application_mode','view');
					
					$this->loadModel('DmiSmsEmailTemplates');
					//call custom function from Model with message id
					//added this condition on 16-09-2019, if appln is CA BEVO
					//then approved by JTAMA and send to DYAMA
					if($ca_bevo_applicant == 'yes' && ($application_type==1 || $application_type==3)) { //added cond. on 22-11-2021 for appl. type = 1

						#SMS: JTAMA approved application
						//$this->DmiSmsEmailTemplates->sendMessage(53,$customer_id);
						$sent_ur = 'Dy. AMA';

					}else{

						#SMS: AMA approved application
						$this->DmiSmsEmailTemplates->sendMessage(26,$customer_id);
						$sent_ur = 'Jt. AMA';
					}

					$message = 'Application Approved and sent to '.$sent_ur;
					$redirect_to = '../hoinspections/ho-inspection';
					
				}

			}

			//Below isset is added for Grant btn to Dyama for lab exp application only
			//These applications will granted by DYAMA
			// added on 18-09-2017 by Amol
			if(null!==($this->request->getData('final_granted'))){

				//calling common function for esigning//applied on 04-11-2017 by Amol
				//$this->processToEsign($customer_id);

			}

			//check AMA approval for the application to show grant btn to dyama
			//added on 18-09-2017 by Amol
			$check_ama_approval = $this->$ama_approved_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'status'=>'approved',   $grantDateCondition)))->first();
			$this->set('check_ama_approval',$check_ama_approval);

			//To get RO office email id
			if($office_type=='RO'){ $level_field = 'level_3'; }elseif($office_type=='SO'){ $level_field = 'level_4_ro'; }

			//check RO allocation for current application //added on 16-01-2018 by Amol
			$check_valid_ro = $this->$allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$level_field=>$username,   $grantDateCondition)))->first();
			$this->set('check_valid_ro',$check_valid_ro);

			//below code added on 16-01-2018 by Amol
			//To provide buttons to RO on HO comment window for communication with Applicant & IO

			// To Comment to Applicant from HO comment window
			if (null!==($this->request->getData('comment_to_applicant'))){

				$this->Session->write('ro_with','applicant');

				$appl_view_link = '/scrutiny/form_scrutiny_fetch_id/'.$firm_details['id'].'/edit/'.$application_type;
				$this->redirect($appl_view_link);

			}


			// To Comment to IO, or Grant from HO comment window
			if (null!==($this->request->getData('comment_to_io')) || null!==($this->request->getData('proceed_to_grant'))){

				$report_link = '/inspections/inspection_report_fetch_id/'.$firm_details['id'].'/edit/'.$application_type;
				$this->redirect($report_link);
			}


			// set variables to show popup messages from view file
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);
			//exit;
		}


//Removed on 05-05-2021 by Amol, view part is also managed from 'hoInspection' function commonly.

		
	//to redirect on granted applications window	
		public function redirectGrantedApplications($appl_type_id){
			
			$this->Session->write('ap_id',$appl_type_id);
			$this->redirect(array('controller'=>'hoinspections', 'action'=>'grantCertificatesList'));
		}


		//added on 18-09-2017 by Amol
		//method to show All DYAMA grant certificates list for lab export applications

		public function grantCertificatesList(){
			
			
			$appl_type_id = $this->Session->read('ap_id');
			if(empty($appl_type_id)){
				$appl_type_id = $this->Session->read('application_type');
			}
			$username = $this->Session->read('username');
			
			//get flow wise application tables
			$this->loadModel('DmiFlowWiseTablesLists');
			$flow_wise_table = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('id IS'=>$appl_type_id)))->first();
			$grant_cert_table = $flow_wise_table['grant_pdf'];
			$appl_pdf_table = $flow_wise_table['app_pdf_record'];
			$report_pdf_table = $flow_wise_table['inspection_pdf_record'];
			
			//get application type
			$this->loadModel('DmiApplicationTypes');
			$get_appl_type = $this->DmiApplicationTypes->find('all',array('conditions'=>array('id IS'=>$appl_type_id)))->first();
			$appl_type = $get_appl_type['application_type'];
			
			$this->loadModel($grant_cert_table);
			$this->loadModel($appl_pdf_table);
			$this->loadModel($report_pdf_table);
			
			$this->Session->write('current_level',null);
			
			//by default
			$to_dt = date('Y-m-d');
			$from_dt = date('Y-m-d', strtotime('-1 month'));

			if ($this->request->is('post')) {

				//on search
				$to_dt = $this->request->getData('to_dt');
				$from_dt = $this->request->getData('from_dt');


				if (empty($from_dt) || empty($to_dt)) {

					$this->set('return_error_msg','Please  Proper Dates.');
					return null;
				}
				
				$this->set(compact('to_dt', 'from_dt'));
			}
			
			
			//set common values conditionally
			if($appl_type_id == '1'){//for new
				
				$condition = array('pdf_version'=>'1','user_email_id !='=>'old_application','date(created) >=' => $from_dt, 'date(created) <=' => $to_dt);
				$report_pdf_field = 'pdf_file';
			
			}elseif($appl_type_id == '2'){//for renewal

				$condition = array('pdf_version >'=>'1','user_email_id !='=>'old_application','date(created) >=' => $from_dt, 'date(created) <=' => $to_dt);
				$report_pdf_field = 'firm_renewal_docs';
				
			}else{
				
				$condition = array('user_email_id !='=>'old_application','date(created) >=' => $from_dt, 'date(created) <=' => $to_dt);
				$report_pdf_field = 'pdf_file';
			}
			
			//get user roles
			$this->loadModel('DmiUserRoles');
			$roles = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id'=>$username)))->first();
				
			//get records from grant certificate table
			$fetch_all_granted_pdf = $this->$grant_cert_table->find('all',array('fields'=>array('customer_id','id','pdf_file','created','user_email_id'),'group'=>'customer_id,id,pdf_file,created','conditions'=>$condition,'order'=>'id DESC'))->toArray();
			
			$i=0;
			$appl_array = array();
			foreach($fetch_all_granted_pdf as $each_record)
			{
				$customer_id = $each_record['customer_id'];
				
				if($roles['super_admin']=='yes' || $roles['dy_ama']=='yes' || $roles['jt_ama']=='yes' || $roles['ama']=='yes'){					
					//no conditions
					$office_email_id = $username;
					$ro_incharge = '';
					
				}elseif($roles['ro_inspection']=='yes' || $roles['so_inspection']=='yes'){
					
					//check application wise RO/SO office
					$this->loadModel('DmiApplWithRoMappings');
					$find_office_id = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
					$office_email_id = $find_office_id['ro_email_id'];

					//get RO in-charge id to display all SO applications to RO
					$ro_incharge = $this->Customfunctions->getApplRegOfficeId($customer_id,$appl_type_id);
					
				}
				
				if($office_email_id==$username || $ro_incharge==$username){
					//check form type
					$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
					
					// application is old
					/*if($each_record[$grant_cert_table]['user_email_id']=='old_application'){
						
						$appl_type = 'BackLog';
					}*/
					
					//get firm details table id
					$this->loadModel('DmiFirms');
					$get_firm_id = $this->DmiFirms->find('all',array('fields'=>'id','conditions'=>array('customer_id'=>$customer_id)))->first();
					$f_id = $get_firm_id['id'];
					
					//get application form link
					$appl_form = '../scrutiny/form_scrutiny_fetch_id/'.$f_id.'/view/'.$appl_type_id;
					$report_form = '../inspections/inspection_report_fetch_id/'.$f_id.'/view/'.$appl_type_id;
					
					//get certificate type
					$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
					if($split_customer_id[1] == 1){					
						$cert_type = 'CA';
						
					}elseif($split_customer_id[1] == 2){					
						$cert_type = 'Printing Press';
						
					}elseif($split_customer_id[1] == 3){					
						$cert_type = 'Laboratory';
						
					}
					
					
					//get application pdf links
					
					$appl_pdf = $appl_type_id;//set default to reload page if blank
					$get_appl_pdf = $this->$appl_pdf_table->find('all',array('conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();				
					if(!empty($get_appl_pdf)){
						$appl_pdf = $get_appl_pdf['pdf_file'];
					}
					
					//get report pdf links
					
					
					//condition added on 01-05-2023 for CA export report and pdf links by Amol
					$checkExport = $this->Customfunctions->checkApplicantExportUnit($customer_id);
					if ($appl_type_id==1 && $checkExport=='yes') {			
						$report_pdf_table = 'DmiCaExportSiteinspectionReports';
						$this->loadModel($report_pdf_table);				
				
					}
					//added applicationtype==3 condition on 01-05-2023, to get change report table by Amol
					elseif($appl_type_id==3){
						$report_pdf_table = 'DmiChangeSiteinspectionReports';
						$this->loadModel($report_pdf_table);				
					}
			
					$report_pdf = $appl_type_id;//set default to reload page if blank
					$get_report_pdf = $this->$report_pdf_table->find('all',array('conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
					if(!empty($get_report_pdf)){
	  
						//condition added on 01-05-2023 for CA export report and pdf links
						//and also for change request report pdf
						if(($appl_type_id==1 && $checkExport=='yes') || $appl_type_id==3){
							$report_pdf = $get_report_pdf['report_docs'];
						}else{
							$report_pdf = $get_report_pdf[$report_pdf_field];
						}
					}
					
					
					
					//set values to array to show in list with pdf links				
					$appl_array[$i]['cert_type'] = $cert_type.' - '.$form_type;
					$appl_array[$i]['customer_id'] = $customer_id;
					$appl_array[$i]['cert_pdf'] = $each_record['pdf_file'];
					$appl_array[$i]['appl_pdf'] = $appl_pdf;
					$appl_array[$i]['report_pdf'] = $report_pdf;
					$appl_array[$i]['grant_date'] = $each_record['created'];
					$appl_array[$i]['appl_form'] = $appl_form;
					$appl_array[$i]['report_form'] = $report_form;

					$appl_array[$i]['show_esign_btn'] = 'no';
					if($each_record['user_email_id']==$office_email_id || $each_record['user_email_id']==$ro_incharge){

						$appl_array[$i]['show_esign_btn'] = 'no';
					}
					//else part commented on 16-01-2023 to show esign button if status is null in prov grant table
					//else{
						$this->loadModel('DmiGrantProvCertificateLogs');
						$getStatus = $this->DmiGrantProvCertificateLogs->find('all',array('fields'=>array('id','status'),'conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
						if(!empty($getStatus) && $getStatus['status']==null){							
							$appl_array[$i]['show_esign_btn'] = 'yes';
						}						
					//}
					
					
					$i=$i+1;
					
				}
				
			}
			
			//$this->Session->delete('ap_id');
			//$this->Session->delete('application_type');
			
			$this->set(compact('appl_array','fetch_all_granted_pdf','appl_type'));
		}


//Removed renewal functions here, now using common function "hoInspection" and view for all type of applications conditionally with $appl_type
//on 05-05-2021 by Amol


	//updated nere on 20-05-2021 by Amol
		//on 14-02-2020 by Amol as issue occured on live 
		//new function to apply check and get approval by RO
		//when application send back from HO to RO and RO referred_back on forms Or On report
		//update last records of referred_back sections to "approved", as it was referred back again once approved.
		//If such cond. matched then forced RO to Approve that changes with Yes/No option, and update all internally.
		//If NO then restrict RO user to proceed on HO inspection window.
		public function checkFormsAndReportsAllApproved($customer_id){
			
			$current_level = $this->Session->read('current_level');
			$level_3_for = $this->Session->read('level_3_for');
			
			
			//only when current user is RO
			if($current_level=='level_3' && $level_3_for=='ro'){
				
				$application_type = $this->Session->read('application_type');			
				$this->loadModel('DmiFlowWiseTablesLists');		
				
				// Fetch grant date conditions get latest records.
				$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
				
				$form_value = 0;
				$report_value = 0;
				
				//for forms status check
				$get_final_submit_status = $this->Customfunctions->finalSubmitDetails($customer_id,'application_form');
				$final_submit_status = $get_final_submit_status['status'];

				if($final_submit_status != 'approved'){ $form_value = 1; }
				
				//for reports status check
				//get application type
				$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
				$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
				$firm_type = $this->Customfunctions->firmType($customer_id);
				
				$this->loadModel('DmiCommonSiteinspectionFlowDetails');
				
				//flow wise all reports sections details
				$allSectionDetails = $this->DmiCommonSiteinspectionFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);
				
				//check report sections status
				$i = 0;
				foreach($allSectionDetails as $each){
					
					if($i != 0){//to bypass the first section of firm details, no ststus
					
						//load model
						$section_model = $each['section_model'];
						$this->loadModel($section_model);
						//get last status
						$get_last_status = $this->$section_model->find('all',array('field'=>'form_status','conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition),'order'=>'id DESC'))->first();
						
						if($get_last_status['form_status'] != 'approved'){
							
							$report_value = 1;
						}
					}
					
					$i=$i+1;
				}
				
				if($form_value==0 && $report_value==0){//both no needed		
					return 0;
					
				}elseif($form_value==1 && $report_value==0){//form section needed
					return 1;
					
				}elseif($form_value==0 && $report_value==1){//report section needed
					return 2;
					
				}elseif($form_value==1 && $report_value==1){//both needed
					return 3;
					
				}
				
			}
			
			
		}
		
		
		//to update the table records for forms/reports approval status. as above check function
		public function updateFormsAndReportsAllApproved($customer_id){
			
			$current_level = $this->Session->read('current_level');
			$level_3_for = $this->Session->read('level_3_for');
			
			//only when current user is RO
			if($current_level=='level_3' && $level_3_for=='ro'){
				
				$application_type = $this->Session->read('application_type');			
				$this->loadModel('DmiFlowWiseTablesLists');			
				$Dmi_final_submit = $this->DmiFlowWiseTablesLists->getFlowWiseTableDetails($application_type,'application_form');
				$this->loadModel($Dmi_final_submit);
				
				//get application type
				$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
				$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
				$firm_type = $this->Customfunctions->firmType($customer_id);
				
				// Fetch grant date conditions get latest records.
				$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
				
			//for forms status check
				$get_final_submit_status = $this->Customfunctions->finalSubmitDetails($customer_id,'application_form');
				$final_submit_status = $get_final_submit_status['status'];
				
				if($final_submit_status != 'referred_back'){
				
					//add record in final submit table
					if($final_submit_status != 'approved'){

						$Dmi_final_submit_entity = $this->$Dmi_final_submit->newEntity(array(

							'customer_id'=>$customer_id,
							'status'=>'approved',
							'created'=>date('Y-m-d H:i:s'),
							'modified'=>date('Y-m-d H:i:s'),
							'current_level'=>'level_2'
						));

						$this->$Dmi_final_submit->save($Dmi_final_submit_entity);
					
					}
					
					//get application type
					//flow wise all forms sections details
					$this->loadModel('DmiCommonScrutinyFlowDetailsTable');
					$allSectionDetails = $this->DmiCommonScrutinyFlowDetailsTable->allSectionList($application_type,$office_type,$firm_type,$form_type);
					
					//update form sections status
					foreach($allSectionDetails as $each){
						
						//load model
						$section_model = $each['section_model'];
						$this->loadModel($section_model);
						
						$date1 = date('Y-m-d H:i:s');
						//get last status
						$get_last_status = $this->$section_model->find('all',array('field'=>'form_status','conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition),'order'=>'id DESC'))->first();
						
						if($get_last_status['form_status'] != 'approved'){
							
							$this->$section_model->updateAll(array('form_status'=>"approved",'approved_date'=>"$date1",'modified'=>"$date1"),array('id'=>$get_last_status['id']));
						}
						
					}
				
				}else{
					
					return false;
				}
				
				
				
			//for reports status check
			
				//for forms status check
				$get_final_report_status = $this->Customfunctions->finalSubmitDetails($customer_id,'inspection_report');
				$final_report_status = $get_final_report_status['status'];
				
				if($final_report_status != 'referred_back'){
					
					$this->loadModel('DmiCommonSiteinspectionFlowDetails');
					
					//flow wise all reports sections details
					$allSectionDetails = $this->DmiCommonSiteinspectionFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);
					
					$i=0;
					//update form sections status
					foreach($allSectionDetails as $each){
						
						if($i != 0){//to bypass the first section of firm details, no ststus
						
							//load model
							$section_model = $each['section_model'];
							$this->loadModel($section_model);
							
							$date1 = date('Y-m-d H:i:s');
							//get last status
							$get_last_status = $this->$section_model->find('all',array('field'=>'form_status','conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition),'order'=>'id DESC'))->first();
							
							if($get_last_status['form_status'] != 'approved'){
								
								$this->$section_model->updateAll(array('form_status'=>"approved"/*,'approved_date'=>"$date1",'modified'=>"$date1"*/),array('id'=>$get_last_status['id']));
							}
							
							$i= $i+1;
						}
						
					}
					
				}else{
						
					return false;
				}
					
			}
		}
		
		
		public function rejectedApplList(){
			
			$this->loadModel('DmiRejectedApplLogs');
			$this->loadModel('DmiApplicationTypes');
			$this->loadModel('DmiFirms');
			$username = $this->Session->read('username');
			$this->loadModel('DmiUserRoles');
			$roles = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id'=>$username)))->first();
			
			//get last rejected records from each appl type from reject log table
			$get_rejected = $this->DmiRejectedApplLogs->find('all',array('order'=>array('id DESC')))->toArray();

			$appl_array = array();
			$i=0;
			foreach($get_rejected as $each){	
					
				$customer_id = $each['customer_id'];
				$appl_type_id = $each['appl_type'];
				
				
				$office_email_id = '';
				$ro_incharge = '';
				$paoEmailId = '';
				
				if($roles['super_admin']=='yes' || $roles['dy_ama']=='yes' || $roles['jt_ama']=='yes' || $roles['ama']=='yes'){					
					//no conditions
					$office_email_id = $username;
					
				}elseif($roles['ro_inspection']=='yes' || $roles['so_inspection']=='yes'){
					
					//check application wise RO/SO office
					$this->loadModel('DmiApplWithRoMappings');
					$find_office_id = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
					$office_email_id = $find_office_id['ro_email_id'];

					//get RO in-charge id to display all SO applications to RO
					$ro_incharge = $this->Customfunctions->getApplRegOfficeId($customer_id,$appl_type_id);
					
				} elseif ($roles['pao']=='yes') {
					
					//get flow wise payment details table
					$this->loadModel('DmiFlowWiseTablesLists');
					$paymentDetailsTable = $this->DmiFlowWiseTablesLists->getFlowWiseTableDetails($appl_type_id,'payment');
										
					//get pao id from payment details 
					$this->loadModel($paymentDetailsTable);
					$checkpaoId = $this->$paymentDetailsTable->find('all',array('fields'=>'pao_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
				
					if (!empty($checkpaoId)) {
						//get user id from PAO details table
						$this->loadModel('DmiPaoDetails');
						$checkuserId = $this->DmiPaoDetails->find('all',array('fields'=>'pao_user_id','conditions'=>array('id IS'=>$checkpaoId['pao_id'])))->first();
						
						if (!empty($checkuserId)) {
							//get user email id from user details table
							$this->loadModel('DmiUsers');
							$checkuserEmail = $this->DmiUsers->find('all',array('fields'=>'email','conditions'=>array('id IS'=>$checkuserId['pao_user_id'])))->first();
						
							$paoEmailId = $checkuserEmail['email'];
						}
					}
					
				}
				
				if($office_email_id==$username || $ro_incharge==$username || $paoEmailId==$username){
					
					$by_user = base64_decode($each['by_user']);			
					$get_appl_type = $this->DmiApplicationTypes->find('all',array('conditions'=>array('id IS'=>$appl_type_id)))->first();
					$appl_type = $get_appl_type['application_type'];
					
					$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
					//get firm details
					$firm_details = $this->DmiFirms->firmDetails($customer_id);
					$firm_name = $firm_details['firm_name'];					
					$firm_table_id = $firm_details['id'];
		
					$appl_view_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;
					$appl_edit_link = '';

					$appl_array[$i]['appl_type'] = $appl_type;
					$appl_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
					$appl_array[$i]['firm_name'] = $firm_name;
					$appl_array[$i]['by_user'] = $by_user;
					$appl_array[$i]['on_date'] = $each['created'];
					$appl_array[$i]['appl_view_link'] = $appl_view_link;
					
					$i=$i+1;
				}
			}
			
			$this->set('appl_array',$appl_array);
		}


	}
?>
