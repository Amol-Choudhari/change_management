<?php
//session_start();
namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Utility\Hash;

	class RosocommentsController extends AppController{
		
		
		var $name = 'Rosocomments';
		
		public function beforeFilter($event) {
			parent::beforeFilter($event);
		
				$this->loadComponent('Customfunctions');
				$this->loadComponent('Mastertablecontent');
				$this->loadComponent('Flowbuttons');
			
				$this->viewBuilder()->setHelpers(['Form','Html']);
				$this->viewBuilder()->setLayout('admin_dashboard');
				
			if($this->Session->read('username') == null){
						
				$this->customAlertPage("Sorry You are not authorized to view this page..");
				exit();
						
			}
			else{
				$this->loadModel('DmiUserRoles');
				$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('OR'=>array('ro_inspection'=>'yes','so_inspection'=>'yes','mo_smo_inspection'=>'yes','user_email_id'=>$this->Session->read('username')))))->first();
				
				if(empty($user_access)){
					
					echo "Sorry.. You don't have permission to view this page";
					exit();
	
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
		
			$this->redirect(array('controller'=>'rosocomments', 'action'=>'ro-so-mo-comments'));	
		}
				
				
		public function roSoMoComments(){

			$this->loadModel('DmiFirms');
			$this->loadModel('DmiFlowWiseTablesLists');
			$this->loadModel('DmiUserRoles'); 
			$this->loadModel('DmiSmsEmailTemplates'); 
			
			$customer_id = $this->Session->read('customer_id');

			//updated on 25-05-2021 by Amol from HoinspectionController
			$show_check_msg_popup = 'no';
			$approval_on = 'None';
			$check_result = (new HoinspectionsController())->checkFormsAndReportsAllApproved($customer_id);//called from HOinspectionController
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
				if((new HoinspectionsController())->updateFormsAndReportsAllApproved($customer_id)==false){//called from HOinspectionController
					return false;
				}
			}
			if (null!==($this->request->getData('cancel_check'))){
				
				$this->Redirect(array('controller'=>'dashboard','action'=>'home'));
			}
			//till here
			
			if(isset($_SESSION['level_3_for'])){ 
			
				$commentWindow =  strtolower($this->Session->read('level_3_for'));
			}else{
				$commentWindow = 'mo';
			}
			$this->set('commentWindow',$commentWindow);
			$from_user = $commentWindow;
			
			
			$level_3_for = $_SESSION['level_3_for'];
			$application_type = $this->Session->read('application_type');
			$username = $this->Session->read('username');
			
			$firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
			$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
			$firm_type = $this->Customfunctions->firmType($customer_id);
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
			$this->set('form_type',$form_type);
			$this->set('office_type',$office_type);
			
			// Show final grant butto to SO user, if he have power to grant the application
			// For printing and CA Non Bevo application
			// Done by Pravin Bhakare 14-10-2021
			$so_power_to_grant_appl = $this->soAuthorisedToGrantApp($customer_id);
			$this->set('so_power_to_grant_appl',$so_power_to_grant_appl);


			// get current section all details	
			$this->loadModel('DmiCommonScrutinyFlowDetails');	
			$section_details = $this->DmiCommonScrutinyFlowDetails->currentSectionDetails($application_type,$office_type,$firm_type,$form_type,1);
			$this->set('section_details',$section_details);
			
			// Fetch grant date conditions get latest records.
			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
			
			// Get flow related tables
			$flow_wise_table = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
			
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
			
			$ho_comment_reply_table = $flow_wise_table['ho_comment_reply'];
			$this->loadModel($ho_comment_reply_table);
			
			$DmiLevel4RoApprovedAppl = $flow_wise_table['level_4_ro_approved'];
			$this->loadModel($DmiLevel4RoApprovedAppl);
			
			$firm_details = $this->DmiFirms->firmDetails($customer_id);		
			$this->set('firm_name',$firm_details['firm_name']);
			
			// fetch comments history
			$ro_so_mo_comments = $this->$ro_so_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'OR'=>array('comment_by IS'=>$username,'comment_to IS'=>$username),$grantDateCondition),'order'=>'id'))->toArray();	
			$ho_comments = $this->$ho_comment_reply_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'OR'=>array('comment_by IS'=>$username,'comment_to IS'=>$username),$grantDateCondition),'order'=>'id'))->toArray();	
			$comments_result = array_merge($ro_so_mo_comments,$ho_comments);			
			$comments_result = Hash::sort($comments_result, '{n}.created', 'desc');	
			$this->set('ro_so_mo_comments',$comments_result);
			
			// fetch all allocation details
			$allocation_deatils = $this->$allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();
			$this->set('allocation_deatils',$allocation_deatils);
			
			// fetch application pdf record	
			$application_pdf_path = $this->$appl_pdf_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first(); 
			$download_application_pdf = null;
			if(!empty($application_pdf_path)){
					
				$download_application_pdf = $application_pdf_path['pdf_file'];
			}			
			$this->set('download_application_pdf',$download_application_pdf);
			
			// fetch inspection report pdf record
			$download_report_pdf_file = null;
			$report_pdf_path = null;
			
			//condition added on 08-03-2023 for CA export report and pdf links
			$checkExport = $this->Customfunctions->checkApplicantExportUnit($customer_id);
			if ($application_type==1 && $checkExport=='yes') {			
				$report_pdf_table = 'DmiCaExportSiteinspectionReports';
				$this->loadModel($report_pdf_table);				
			}
			
			//added applicationtype==3 condition on 19-04-2023, to get change report table
			elseif($application_type==3){
				$report_pdf_table = 'DmiChangeSiteinspectionReports';
				$this->loadModel($report_pdf_table);				
			}
			
			if(!empty($report_pdf_table)){
				
				$report_pdf_path = $this->$report_pdf_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
				if(!empty($report_pdf_path)){
		
					//condition added on 08-03-2023 for CA export report and pdf links
					if(($application_type==1 && $checkExport=='yes') || $application_type==3){//added applicationtype==3 condition on 19-04-2023
						$download_report_pdf_file = $report_pdf_path['report_docs'];
					}else{
						$download_report_pdf_file = $report_pdf_path['pdf_file'];
					}
				}
			}
			$this->set('download_report_pdf',$download_report_pdf_file);
			$this->set('report_pdf_path',$report_pdf_path);
			
			// Check Ho inspection reqired for current application	
			$HoInspectionExist = $this->Flowbuttons->HoInspectionExist($customer_id);
			$this->set('HoInspectionExist',$HoInspectionExist);
			
			//only if HO level verification exists in the flow
			$amaapproved='';
			if ($HoInspectionExist == 'yes') {				
				// Check current application ama approved or not	
				$amaapproved = $this->$ama_approved_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();				
				if(!empty($amaapproved)){
					$amaapproved = 'yes';
				}
				
			}
			$this->set('amaapproved',$amaapproved);

			//SO can grant only CA non Bevo appl, and with RO approval, on 23-09-2021 by Amol
			// Show final grant butto to SO user, if he have power to grant the application
			// For printing and CA None Bevo application
			// Done by Pravin Bhakare 14-10-2021
			if($so_power_to_grant_appl == 'yes'){
				
				// Check current application ama approved or not	
				$roApproved = $this->$DmiLevel4RoApprovedAppl->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();						
				$this->set('roApproved',$roApproved);
			}	
			
			
			// Check current user roles
			$check_user_role = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$username)))->first();
			$this->set('check_user_role',$check_user_role);					
					
			$ho_allocation_details = $this->$ho_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();
			$this->set('ho_allocation_details',$ho_allocation_details);
			
			// set variables to show popup messages from view file
			$message = '';
			$message_theme = '';
			$redirect_to = '';
			$comment_to_email_id = '';			
			
			//to view forms					
			if (null!== ($this->request->getData('view_applicant_forms'))){					
				
				$appl_view_link = '/scrutiny/form_scrutiny_fetch_id/'.$firm_details['id'].'/view/'.$application_type.'/yes';
				$this->redirect($appl_view_link);			
				
			//to view Report	
			}elseif (null!==($this->request->getData('view_inspection_reports'))){						
				
				$report_link = '/inspections/inspection_report_fetch_id/'.$firm_details['id'].'/view/'.$application_type.'/yes';
				$this->redirect($report_link);
				
			//to send and save comment			
			}elseif (null!==($this->request->getData('send_comment'))){
				
				//html encoding post data before saving					
				$htmlencoded_comment = htmlentities($this->request->getData('comment'), ENT_QUOTES);
				$comment_to = $this->request->getData('comment_to');
				
				if(!empty($comment_to) && !empty($htmlencoded_comment))
				{	
					if($comment_to == 'ro')
						
					{	$comment_to_email_id = $allocation_deatils['level_4_ro'];
						$comment_to_level = 'level_4_ro';						
					}
					elseif($comment_to == 'so')
					{	
						$comment_to_email_id = $allocation_deatils['level_3'];
						$comment_to_level = 'level_3';						
					}
					elseif($comment_to == 'mo')
					{			
						if(!empty($allocation_deatils['level_4_mo']))
						{
							$comment_to_email_id = $allocation_deatils['level_4_mo'];
							$comment_to_level = 'level_4_mo';	
							
						}else{							
							$message = 'Sorry... Please allocate this application to MO/SMO first';
							$message_theme = 'warning';
							$redirect_to = '../rosocomments/ro-so-mo-comments';
							//$this->view = '/Element/message_boxes';		
						}						
					}elseif($comment_to == 'dy_ama'){		
														
						$comment_to_email_id = $ho_allocation_details['dy_ama'];
						$ro_so_comments_table = $ho_comment_reply_table;	
						$comment_to_level = 'level_4';		
					}					
			
					//$sms_id = $this->Customfunctions->checkRoSOSmsId($check_user_role,$comment_to);
					
					if(!empty($comment_to_email_id))
					{
						$ro_so_comments_entity = $this->$ro_so_comments_table->newEntity(array(
						
							'customer_id'=>$customer_id,
							'comment_by'=>$username,
							'comment_to'=>$comment_to_email_id,
							'comment_date'=>date('Y-m-d H:i:s'),
							'comment'=>$htmlencoded_comment,
							'created'=>date('Y-m-d H:i:s'),
							'modified'=>date('Y-m-d H:i:s'),
							'from_user'=>$from_user,
							'to_user'=>$comment_to						
						));
							
						if($this->$ro_so_comments_table->save($ro_so_comments_entity)){
							
							//update allocation current level
							$this->$allocation_table->updateAll(array('current_level' => "$comment_to_email_id"),array('customer_id IS' => $customer_id, $grantDateCondition));
							
							//Update record in all applications current position table
							$user_email_id = $comment_to_email_id;										
							$this->$appl_current_pos_table->currentUserUpdate($customer_id,$user_email_id,$comment_to_level);//call to custom function from model
							
							//call custom function from Model with message id
							//$this->DmiSmsEmailTemplates->sendMessage($sms_id,$customer_id);
							
							$this->Session->write('application_mode','view');
							
							$message = 'Your Comment is successfully sent';
							$message_theme = 'success';
							$redirect_to = '../rosocomments/ro_so_mo_comments';
												
						}
					}				
				
				}
				else{
					
					$message = 'Sorry.. User not selected or Comment box is blank';
					$message_theme = 'failed';
					$redirect_to = '../rosocomments/ro_so_mo_comments';
				
				}
				
			// To Comment to Applicant from RO comment window
			}elseif(null!== ($this->request->getData('comment_to_applicant'))){

				$this->Session->write('ro_with','applicant');				
				$appl_view_link = '/scrutiny/form_scrutiny_fetch_id/'.$firm_details['id'].'/edit/'.$application_type;
				$this->redirect($appl_view_link);		
				
			// To Comment to IO
			}elseif(null!== ($this->request->getData('comment_to_io'))){						
				
				$report_link = '/inspections/inspection_report_fetch_id/'.$firm_details['id'].'/edit/'.$application_type;
				$this->redirect($report_link);
				
			// To Forward the application to Ho level inspection	
			}elseif(null!== ($this->request->getData('forward_to_ho'))){
				
				$find_dy_ama_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('dy_ama'=>'yes',/*'super_admin'=>null*/)))->first();
				$dy_ama_email_id = $find_dy_ama_user['user_email_id'];												
												
				$find_jt_ama_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('jt_ama'=>'yes',/*'super_admin'=>null*/)))->first();
				$jt_ama_email_id = $find_jt_ama_user['user_email_id'];												
												
				$find_ama_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('ama'=>'yes',/*'super_admin'=>null*/)))->first();
				$ama_email_id = $find_ama_user['user_email_id'];
				
				$Dmi_ho_allocation_Entity = $this->$ho_allocation_table->newEntity(array(								
					'customer_id'=>$customer_id,
					'dy_ama'=>$dy_ama_email_id,
					'jt_ama'=>$jt_ama_email_id,
					'ama'=>$ama_email_id,
					'current_level'=>$dy_ama_email_id,
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')
				)); 
				
				$this->$ho_allocation_table->save($Dmi_ho_allocation_Entity);	
				
				$final_report_status = 'ho_allocated';
				$user_email_id = $dy_ama_email_id;		
				$current_level = 'level_4';
				
				$Dmi_report_final_submit_Entity = $this->$final_report_table->newEntity(array(
					'customer_id'=>$customer_id,
					'status'=>$final_report_status,
					'current_level'=>'level_3',
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')				
				));
			
				if($this->$final_report_table->save($Dmi_report_final_submit_Entity)){
					
					// Send to current application postion entry to all_applications_current_position Table	
					$this->$appl_current_pos_table->currentUserUpdate($customer_id,$user_email_id,$current_level); //call to custom function from model	

					#SMS: RO forwarded to HO
					$this->DmiSmsEmailTemplates->sendMessage(20,$customer_id);
					
					$this->Session->write('application_mode','view');
					
					$message = $firm_type_text.' - '.' Approved and Forwarded to HO successfully';
					$message_theme = 'success';
					$redirect_to = '../rosocomments/ro_so_mo_comments';
					
				}
				
			// To grant the current application		
			}elseif(null!==($this->request->getData('final_grant'))){
				
				
			

			//new Approve button functionality added on 18-05-2021 by Amol
			}elseif(null!==($this->request->getData('ro_approve'))){
								
				$approval_comment = htmlentities($this->request->getData('comment'), ENT_QUOTES);
				$ro_approved = $this->$DmiLevel4RoApprovedAppl->saveRoApproved($approval_comment,$application_type);

				if($ro_approved == 1){

					$this->Session->write('application_mode','view');
					
					$sent_ur = 'SO Officer';

					$message = 'Application Approved and sent to '.$sent_ur;
					$redirect_to = '../rosocomments/ro_so_mo_comments';

				}
			}
			
			// set variables to show popup messages from view file
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);
			$this->set('level_3_for',$level_3_for); 
			
			//to set the application visibility mode 			
			$application_mode = $this->Session->read('application_mode');
			$this->set('application_mode',$application_mode); 

		}

		// checked if SO have power to grant the CA Non Bevo or printing application
		// Done by Pravin Bhakare 14-10-2021
		public function soAuthorisedToGrantApp($customer_id){

			$this->loadModel("DmiUserRoles");
			$this->loadModel("DmiApplWithRoMappings");
			$application_type = 1; //intentionally set to 1, to get form type like A,B,C,D,E,F and not like FDC,EC,SOC etc. on 06-12-2022
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id,$application_type);
			$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);	
					
			$find_ro_email_id = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
			$nodalOfficerId = $find_ro_email_id['ro_email_id'];

			$soPowerToGrantApp = 'no';
			
			$soGrantPP = $this->DmiUserRoles->find('all',array('conditions'=>array('so_grant_pp'=>'yes','user_email_id IS'=>$nodalOfficerId)))->first();
			
			if( $form_type == 'B' && !empty($soGrantPP) && $office_type=='SO')
			{
				$soPowerToGrantApp = 'yes';
			}
			elseif(($form_type=='A' || $form_type=='F') && $office_type=='SO')
			{
				$soPowerToGrantApp = 'yes';
			}

			return $soPowerToGrantApp;
		}		
		
	
	}
?>