<?php

namespace App\Controller;
use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\ORM\TableRegistry;
use PHPUnit\TextUI\XmlConfiguration\Loader;
use Twig\Profiler\Dumper\HtmlDumper;

class InspectionsController extends AppController{
	
	
	var $name = 'Inspections';		

	public function beforeFilter($event) {
		parent::beforeFilter($event);
		
								
		$this->loadComponent('Customfunctions');
			$this->loadComponent('Mastertablecontent');
			$this->loadComponent('Progressbar');
			$this->loadComponent('Communication');
			$this->loadComponent('Flowbuttons');
			$this->viewBuilder()->setLayout('admin_dashboard');
			$this->viewBuilder()->setHelpers(['Form','Html']);

		if($this->Session->read('username') == null){
					
			$this->customAlertPage("Sorry You are not authorized to view this page..");
			exit();						
		}
		else{
			
			//check if user have siteinspection role
			$this->loadModel('DmiUserRoles');	
			$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('OR'=>array('io_inspection'=>'yes','form_verification_home'=>'yes','super_admin'=>'yes','dy_ama'=>'yes','jt_ama'=>'yes','ama'=>'yes'),'user_email_id'=>$this->Session->read('username'))))->first();
			
			if(empty($user_access)){					
				echo "Sorry.. You don't have permission to view this page";
				exit();
			}
		}		
	}
			
	public function inspectionReportFetchId($id,$mode,$application_type,$fromHoLevel=null){
		
		$this->loadModel('DmiFirms');
		$customer_id_result = $this->DmiFirms->find('all',array('fields'=>'customer_id', 'conditions'=>array('id IS'=>$id)))->first();
		$customer_id = $customer_id_result['customer_id'];
		$this->Session->write('customer_id',$customer_id);
		$this->Session->write('application_mode',$mode);
		$this->Session->write('fromHoLevel',$fromHoLevel);
		$this->Session->write('application_type',$application_type);
		$this->Session->delete('section_id');
		$this->Session->delete('edit_directors_details_id');

		$this->redirect('/inspections/inspection-report');
		
	}

	public function section($id){
		
		$this->Session->write('section_id',$id);
		$this->redirect('/inspections/inspection-report');
	}	
	
	//Change on 06/09/2018, This is the main function that manages the common IO report functionality like fill report, update report, commenting on report from both side, view report - By Pravin Bhakare		
	public function inspectionReport(){
		
		// set variables to show popup messages from view file
		
		$message = '';
		$message_theme = '';
		$redirect_to = '';
		
		$this->viewBuilder()->setLayout('form_siteinspection_layout');
		$this->tablesEditDetailSessionDelete();
		$this->set('fromHoLevel',$this->Session->read('fromHoLevel'));
		
		$this->loadModel('DmiAllDirectorsDetails');	
		$this->loadModel('DmiCommonSiteinspectionFlowDetails');
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiUserRoles');			
		$this->loadModel('DmiSmsEmailTemplates');
		$this->loadModel('DmiChangeFirms');

		$customer_id = $this->Customfunctions->checkCustomerIdAvailable($this->Session->read('customer_id'));
		$this->set('customer_id',$customer_id);
		
		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
					//print_r($grantDateCondition); exit;

		$form_final_submit_details = $this->Customfunctions->finalSubmitDetails($customer_id,'application_form');
		
		//Below the Not Empty condition is added to remove the offset error - Akash [26-10-2022]
		if(!empty($form_final_submit_details['status']) != 'approved'){
			$this->Session->write('application_mode','view');
		}
		
		$user_email_id = $this->Session->read('username');
		$user_as = $this->Session->read('user_as');
		
		//$this->Session->write('current_level','level_2');
		$current_level = $this->Session->read('current_level');	
		$this->set('current_level',$current_level);
		
		$application_mode = $this->Session->read('application_mode');
		$this->set('application_mode',$application_mode);
		
		$application_type = $this->Session->read('application_type');
		$this->set('application_type',$application_type);			
				
		if(empty($application_mode) || empty($application_type) || empty($customer_id)){
			
			$this->customAlertPage("Something went wrong activity ");
			exit();
		}			
					
		if($current_level){
						
			$back_to_inspection_level = $current_level;
		}else{							
			$back_to_inspection_level = 'level_3';
		}
		$this->set('back_to_inspection_level',$back_to_inspection_level);
		
		
		// This function find out the lastest version of Application pdf (By pravin 08-08-2017)
		$download_report_pdf = $this->Customfunctions->findLatestReportPdf($customer_id);
		$this->set('download_report_pdf',$download_report_pdf);
		
		//Added on 01-09-2017 check lab export unit 
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);								
		$this->set('export_unit_status',$export_unit_status);
		
		$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
		$this->set('office_type',$office_type);	
		
		$firm_type = $this->Customfunctions->firmType($customer_id);		
		$firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
		$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
		$this->set('form_type',$form_type);	
		
		$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
		$this->set('ca_bevo_applicant',$ca_bevo_applicant);
		
		$applicant_type = $this->Customfunctions->checkFatSpreadOrBevo($customer_id);//call fucntion to check bevo or fat spread
		$this->set('applicant_type',$applicant_type);
		
		$forward_to = $this->Customfunctions->forwardToApplicationAtLevel4($customer_id,$office_type,$application_type);
		$this->set('forward_to',$forward_to);
		
		// check current form section value
		if(isset($_SESSION['section_id'])){
			$section_id = $this->Session->read('section_id');	
		}else{
			$section_id = 1; 
		}
		
		
		
		// Predefine value
		$business_type = $this->Mastertablecontent->allBusinessType();
		$this->set('business_type',$business_type);
		$state_list = $this->Mastertablecontent->allStateValue();
		asort($state_list);
		$this->set('state_list',$state_list);
		$distict_list = $this->Mastertablecontent->allDistrictValue();
		$this->set('distict_list',$distict_list);		
		$all_ca_business_year = $this->Mastertablecontent->allCaBusinessYear();
		$this->set('all_ca_business_year',$all_ca_business_year);	
		$all_printing_business_year = $this->Mastertablecontent->allPrintingBusinessYear();
		$this->set('all_printing_business_year',$all_printing_business_year);
		$rushing_refining_period = $this->Mastertablecontent->allCrushingRefiningValue();
		$this->set('rushing_refining_period',$rushing_refining_period);
		
		
		$firm_detail = $this->DmiChangeFirms->sectionFormDetails($customer_id);
		$firm_details = $firm_detail[0];
		$this->set('firm_details',$firm_details);
		
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');		
		
		$report_final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'inspection_report');
		$Dmi_report_final_submit_table = TableRegistry::getTableLocator()->get($report_final_submit_table);
		
		$Dmi_allocation_table_name = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'allocation');
		$Dmi_allocation_table = TableRegistry::getTableLocator()->get($Dmi_allocation_table_name);
		
		$ho_allocation_table_name = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'ho_level_allocation');
		$Dmi_ho_allocation_table = TableRegistry::getTableLocator()->get($ho_allocation_table_name);	

		$appl_current_pos_table_name = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'appl_current_pos');
		$Dmi_appl_current_pos_table = TableRegistry::getTableLocator()->get($appl_current_pos_table_name);

		$form_final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'application_form');
		
		// get current section all details			
		$section_details = $this->DmiCommonSiteinspectionFlowDetails->currentSectionDetails($application_type,$office_type,$firm_type,$form_type,$section_id);
		
		// get all section all details
		$allSectionDetails = $this->DmiCommonSiteinspectionFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);
		$section_model_name = $section_details['section_model'];
		$this->set('section_model_name',$section_model_name);
		
		$section_model = TableRegistry::getTableLocator()->get($section_model_name);
		$section = $section_details['section_name'];
			
		//addded by akash thakre to display section details
			$str = str_replace('_', ' ', $section);
			$section_name =  ucwords($str); 

			$this->set('section_name',$section_name);
			$this->set('section',$section);

		// get section details
		$section_form_details = $section_model->sectionFormDetails($customer_id);	
		
		$added_directors_details = $this->DmiAllDirectorsDetails->allDirectorsDetail($customer_id);	
		$this->set('added_directors_details',$added_directors_details);
					
		$final_submit_status = $this->Customfunctions->finalSubmitDetails($customer_id,'inspection_report');			
		$this->set('final_submit_status',$final_submit_status);
		
		//added on 13-05-2021 by Amol, to be used in view side
		if(!empty($final_submit_status)){
			$this->set('section_status',$final_submit_status['status']);
		}else{
			$this->set('section_status','');
		}
		
		
		/* $show_accept_forward_btn = $this->showReportBtnsStatus($customer_id,$application_type,$section_details,$allSectionDetails);			
		$this->set('show_accept_btn',$show_accept_forward_btn[0]);	
		$this->set('show_forward_to_ho_btn',$show_accept_forward_btn[1]);	
		$this->set('show_final_granted_btn',$show_accept_forward_btn[2]); */
		
		//Get current report section status value
		$all_report_status = $this->DmiCommonSiteinspectionFlowDetails->reportSectionStatus($customer_id,$allSectionDetails);			
		if($all_report_status == 'true') {  $show_final_report_btn = 'yes'; }else{ $show_final_report_btn = 'no'; }
		$this->set('show_final_report_btn',$show_final_report_btn);	
		
		//Get referred back status value
		$all_report_referred_back_status = $this->DmiCommonSiteinspectionFlowDetails->reportReferredBackStatus($customer_id,$allSectionDetails);	
		$this->set('report_referred_back_status',$all_report_referred_back_status);
		
		//Get progress bar status value
		$progress_bar_status = $this->Progressbar->inspectionProgressBarStatus($allSectionDetails,$customer_id);
		$this->set('progress_bar_status',$progress_bar_status);
		
		//get forward btn, accept btn and grant btn display status value
		$accept_btn = $this->Flowbuttons->ShowNodalLevelAcceptBtnAfterInsp($customer_id,$application_type,$section_details,$allSectionDetails);
		$this->set('accept_btn',$accept_btn);
		$forward_to_btn = $this->Flowbuttons->ShowNodalLevelForwardBtnAfterInsp($customer_id,$application_type,$section_details,$allSectionDetails);
		$this->set('forward_to_btn',$forward_to_btn);
		$final_granted_btn = $this->Flowbuttons->ShowNodalLevelGrantBtnAfterInsp($customer_id,$application_type,$section_details,$allSectionDetails);
		$this->set('final_granted_btn',$final_granted_btn);
		
		
		if($section_details['comment_section']=='yes'){
			
			$this->Communication->ioCommentHistory($section_model_name,$customer_id,$section_form_details,$application_type);
		}
		
		$show_message = '';
		$redirect_url = '../inspections/inspection-report';			
		
		//For edit referred_back						
		if(null!== ($this->request->getData('save_edited_reply'))){
			
			$id = $this->Session->read('edit_reply_to_ro_id');
			$htmlencoded_edited_reply = htmlentities($this->request->getData('edited_reply'), ENT_QUOTES);
			
			if(!empty($this->request->getData('ir_comment_ul')->getClientFilename())){				
			
				$file_name = $this->request->getData('ir_comment_ul')->getClientFilename();
				$file_size = $this->request->getData('ir_comment_ul')->getSize();
				$file_type = $this->request->getData('ir_comment_ul')->getClientMediaType();
				$file_local_path = $this->request->getData('ir_comment_ul')->getStream()->getMetadata('uri');
				
				$ir_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
	
			}else{ $ir_comment_ul = $section_form_details[0]['ir_comment_ul']; }
			
			$section_model_Entity = $section_model->newEntity(array(				
				'id'=>$id,
				'io_reply'=>$htmlencoded_edited_reply,
				'io_reply_date'=>date('Y-m-d H:i:s'),
				'ir_comment_ul'=>$ir_comment_ul,
			)); 
			
			if($section_model->save($section_model_Entity)){							
				$this->Session->delete('edit_reply_to_ro_id');
				$this->redirect('/inspections/inspection-report');	
			}
			
		//For edit referred_back
		}elseif(null!==($this->request->getData('save_edited_referred_back'))){
						
			$id = $this->Session->read('edit_referred_back_to_io_id');
			$htmlencoded_edited_referred_back = htmlentities($this->request->getData('edited_referred_back'), ENT_QUOTES);
			
			if(!empty($this->request->getData('rb_comment_ul')->getClientFilename())){				
			
				$file_name = $this->request->getData('rb_comment_ul')->getClientFilename();
				$file_size = $this->request->getData('rb_comment_ul')->getSize();
				$file_type = $this->request->getData('rb_comment_ul')->getClientMediaType();
				$file_local_path = $this->request->getData('rb_comment_ul')->getStream()->getMetadata('uri');
				
				$rb_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
	
			}else{ $rb_comment_ul = $section_form_details[0]['rb_comment_ul']; }
			
			$section_model_Entity = $section_model->newEntity(array(				
				'id'=>$id,
				'referred_back_comment'=>$htmlencoded_edited_referred_back,
				'referred_back_date'=>date('Y-m-d H:i:s'),
				'rb_comment_ul'=>$rb_comment_ul,				
			));
			
			if($section_model->save($section_model_Entity)){					
				$this->Session->delete('edit_referred_back_to_io_id');
				$this->redirect('/inspections/inspection-report');
			}
			
		}elseif(null!==($this->request->getData('save'))){
					
			$result = $section_model->saveFormDetails($customer_id,$this->request->getData()); 
			$result_message = $this->reportPopupMessage('save',$result,$section_details,$firm_type_text,$office_type);	
			$show_message = 'yes';
			if(empty($section_form_details[0]['created']) && count($allSectionDetails)!=$section_id){					
				$this->Session->write('section_id',$section_id+1);
			}
			$redirect_url = '../inspections/inspection-report';	
			
		}elseif(null!==($this->request->getData('final_submit'))){
			
			$result = $this->Customfunctions->commonReportFinalSubmitCall();
			$result_message = $this->reportPopupMessage('final_report',$result,$section_details,$firm_type_text,$office_type);
			$show_message = 'yes';
			$redirect_url = '../dashboard/home'; 
			
		}elseif(null!==($this->request->getData('sent_to'))){
			
			$result = $this->Customfunctions->commonReportFinalSubmitCall();
			$result_message = $this->reportPopupMessage('sent_to',$result,$section_details,$firm_type_text,$office_type);
			$show_message = 'yes';
			$redirect_url = '../dashboard/home';
			
			/* $Dmi_report_final_Entity = $Dmi_report_final_submit_table->newEntity(array(
				'customer_id'=>$customer_id,
				'status'=>'replied',
				'current_level'=>'level_3',
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s')
			)); 
			
			if($Dmi_report_final_submit_table->save($Dmi_report_final_Entity)){ $result_message = $this->reportPopupMessage('sent_to',1,$section_details,$firm_type_text,$office_type); }
			else{ $result_message = $this->reportPopupMessage('sent_to',0,$section_details,$firm_type_text,$office_type); }
			
			$show_message = 'yes';
			$redirect_url = '../scrutiny/home'; */
			
		}elseif(null!==($this->request->getData('send_to_io'))) {				
						
				//to check if any comment saved before final submit to IO
				if($all_report_referred_back_status == 'referred_back')
				{	
					$Dmi_report_final_Entity = $Dmi_report_final_submit_table->newEntity(array(							
						'customer_id'=>$customer_id,
						'status'=>'referred_back',
						'current_level'=>$current_level,
						'created'=>date('Y-m-d H:i:s'),
						'modified'=>date('Y-m-d H:i:s')								
					));
					if($Dmi_report_final_submit_table->save($Dmi_report_final_Entity)){ 
					
						$allocationDetails = $Dmi_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();
						$user_email_id = $allocationDetails['level_2'];
						$current_level = 'level_2';
						
						// Send to current application postion entry to all_applications_current_position Table	
						$Dmi_appl_current_pos_table->currentUserUpdate($customer_id,$user_email_id,$current_level); //call to custom function from model			
									
						#SMS: RO referred back to IO
						$this->DmiSmsEmailTemplates->sendMessage(18,$customer_id);
						$result_message = $this->reportPopupMessage('send_to_io',1,$section_details,$firm_type_text,$office_type);
					
					}	
					
				}else{ $result_message = $this->reportPopupMessage('send_to_io',0,$section_details,$firm_type_text,$office_type); }
				
				$show_message = 'yes';
				$redirect_url = '../dashboard/home';
				
		}elseif(null!==($this->request->getData('referred_back'))){
			
			$reffered_back_comment = htmlentities($this->request->getData('referred_back_comment'), ENT_QUOTES);
			
			if(!empty($this->request->getData('rb_comment_ul')->getClientFilename())){				
			
				$file_name = $this->request->getData('rb_comment_ul')->getClientFilename();
				$file_size = $this->request->getData('rb_comment_ul')->getSize();
				$file_type = $this->request->getData('rb_comment_ul')->getClientMediaType();
				$file_local_path = $this->request->getData('rb_comment_ul')->getStream()->getMetadata('uri');

				$rb_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
	
			}else{ $rb_comment_ul = $section_form_details[0]['rb_comment_ul']; }
			
			$result = $section_model->saveReferredBackComment($customer_id,$section_form_details[0],$reffered_back_comment,$rb_comment_ul);
			$result_message = $this->reportPopupMessage('referred_back',$result,$section_details,$firm_type_text,$office_type);				
			$show_message = 'yes';
			
		}elseif(null!==($this->request->getData('accepted'))) {
			
			if($section_form_details[0]['form_status'] != 'approved')
			{					
				
				$check_applicant_form_ref_back_entry = $this->Customfunctions->checkOnRoinspectionFormsApproved($customer_id,$form_final_submit_table);
				
				if($check_applicant_form_ref_back_entry == 1){
					
					if($section_details['comment_section']=='yes'){
						
						if($section_form_details[0]['referred_back_by_once'] != '' || $section_form_details[0]['referred_back_by_once'] != null){
						
							$email_array = array($section_form_details[0]['referred_back_by_email'],$this->Session->read('username'));
							$aadhar_no_array = array($section_form_details[0]['referred_back_by_once'],$this->Session->read('once_card_no'));				
						
							$referred_back_by_email = implode(',',$email_array);
							$referred_back_by_once = implode(',',$aadhar_no_array);
						
						}else{ 
								$referred_back_by_email = $this->Session->read('username');
								$referred_back_by_once = $this->Session->read('once_card_no');
						}
							
						$data_saving_array = array('id'=>$section_form_details[0]['id'],'referred_back_by_email'=>$referred_back_by_email,'referred_back_by_once'=>$referred_back_by_once,
													'approved_date'=>date('Y-m-d H:i:s'),'current_level'=>$current_level,'form_status'=>'approved','modified'=>date('Y-m-d H:i:s'));		
					
					}else{
						
						$data_saving_array = array('id'=>$section_form_details[0]['id'],'approved_date'=>date('Y-m-d H:i:s'),'current_level'=>$current_level,'form_status'=>'approved','modified'=>date('Y-m-d H:i:s'));
					}
					
					$section_model_Entity = $section_model->newEntity($data_saving_array); 
					
					if($section_model->save($section_model_Entity)){  
						$result_message = $this->reportPopupMessage('accepted',1,$section_details,$firm_type_text,$office_type);
						$show_message = 'yes'; 
					}	
					if(empty($section_form_details[0]['created']) && count($allSectionDetails)!=$section_id){						
						$this->Session->write('section_id',$section_id+1);
					}		
					$redirect_url = '../inspections/inspection-report';	

				}else{  
						$result_message = $this->reportPopupMessage('accepted',2,$section_details,$firm_type_text,$office_type);	
						$show_message = 'yes'; 
						} 	
							
			}else{ 
					$result_message = $this->reportPopupMessage('accepted',3,$section_details,$firm_type_text,$office_type);	
					$show_message = 'yes'; 
				}				
			
		}elseif(null!==($this->request->getData('accepted_forward'))){
							
			$find_dy_ama_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('dy_ama'=>'yes',/*'super_admin'=>null*/)))->first();
			$dy_ama_email_id = $find_dy_ama_user['user_email_id'];												
											
			$find_jt_ama_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('jt_ama'=>'yes',/*'super_admin'=>null*/)))->first();
			$jt_ama_email_id = $find_jt_ama_user['user_email_id'];												
											
			//added condition on 16-09-2019 by Amol
			//to restrict BEVO application till JTAMA only
			if($ca_bevo_applicant == 'yes')
			{
				$ama_email_id = 'Not Assigned';
			}else{
				$find_ama_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('ama'=>'yes',/*'super_admin'=>null*/)))->first();
				$ama_email_id = $find_ama_user['user_email_id'];
			}			
			
			if($forward_to == 'RO'){
				
				$ro_email_id = $this->Customfunctions->getApplRegOfficeId($customer_id,$application_type);
				$allocation_id = $Dmi_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();					
				
				$Dmi_allocation_Entity = $Dmi_allocation_table->newEntity(array(
					'id'=>$allocation_id['id'],
					'customer_id'=>$customer_id,
					'level_4_ro'=>$ro_email_id,
					'current_level'=>$ro_email_id,
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')			
				)); 
				
				if($Dmi_allocation_table->save($Dmi_allocation_Entity));
				
				$final_report_status = 'level_4_ro';
				$user_email_id = $ro_email_id;
				$current_level = 'level_4_ro';					
				
			}elseif($forward_to == 'HO'){
							
				$Dmi_ho_allocation_Entity = $Dmi_ho_allocation_table->newEntity(array(								
					'customer_id'=>$customer_id,
					'dy_ama'=>$dy_ama_email_id,
					'jt_ama'=>$jt_ama_email_id,
					'ama'=>$ama_email_id,
					'current_level'=>$dy_ama_email_id,
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')
				)); 
				
				$Dmi_ho_allocation_table->save($Dmi_ho_allocation_Entity);	
			
				$final_report_status = 'ho_allocated';
				$user_email_id = $dy_ama_email_id;
				$current_level = 'level_4';
			}
			
			$Dmi_report_final_submit_Entity = $Dmi_report_final_submit_table->newEntity(array(
				'customer_id'=>$customer_id,
				'status'=>$final_report_status,
				'current_level'=>'level_3',
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s')				
			)); 
			
			if($Dmi_report_final_submit_table->save($Dmi_report_final_submit_Entity)){									
				
				// Send to current application postion entry to all_applications_current_position Table	
				$Dmi_appl_current_pos_table->currentUserUpdate($customer_id,$user_email_id,$current_level); //call to custom function from model			
				
				#SMS: RO forwarded to HO
				$this->DmiSmsEmailTemplates->sendMessage(20,$customer_id);				
				$result_message = $this->reportPopupMessage('accepted_forward',1,$section_details,$firm_type_text,$office_type);
				$show_message = 'yes'; 
				$redirect_url = '../dashboard/home';
			}
			
		}elseif(null!==($this->request->getData('final_granted'))){
			
			
			
		}elseif(null!==($this->request->getData('view_application'))) {				
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
			$this->redirect(array('controller'=>'scrutiny','action'=>'form_scrutiny_fetch_id',$firm_details['id'],'edit',$application_type));
		}
		
		if(!empty($show_message)){			
			$message = $result_message;
			$redirect_to = $redirect_url;
			//$this->view = '/Element/message_boxes';
		}
		
		
		//below code added on 20-05-2021 by amol to show note msg to RO conditionally
		//when applicant revert back from HO-QC, and want to send again to HO-QC
		$show_note_msg = null;
		if($this->Session->read('level_3_for') == 'ro'){

			$Dmi_ama_approval_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'ama_approved_application');
			$Dmi_ama_approval_table = TableRegistry::getTableLocator()->get($Dmi_ama_approval_table);
			
			$ho_allocation = $this->$Dmi_ho_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();
			$ama_approval = $this->$Dmi_ama_approval_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();
			
			if(empty($ama_approval) && ($form_final_submit_details['status']=='replied' 
				|| $final_submit_status['status']=='replied') && !empty($ho_allocation)){
				
				$show_note_msg = 'yes';
			}
		}			
		$this->set('show_note_msg',$show_note_msg);	
		
				
		$this->set('section_details',$section_details);
		$this->set('allSectionDetails',$allSectionDetails);
		$this->set('section_form_details',$section_form_details);
		
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);	 
	
		//exit;
	}
	
	// Call this function after esign of final submitted report.
	public function reportFinalSubmit(){
		
		$message = '';
		$redirect_to = '';
		
		$customer_id = $this->Session->read('customer_id');
		$firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
		$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
		$firm_type = $this->Customfunctions->firmType($customer_id);
		$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
		$application_type = $this->Session->read('application_type');
		
		$DmiCommonSiteinspectionFlowDetails = TableRegistry::getTableLocator()->get('DmiCommonSiteinspectionFlowDetails');
		$allSectionDetails = $DmiCommonSiteinspectionFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);
		
		$result = $this->Customfunctions->commonReportFinalSubmitCall();
		
		if($result==1){
			$message = $firm_type_text.' - '.'Final submitted successfully';
			$redirect_to = '../applicationformspdfs/'.$allSectionDetails[0]['report_pdf'];
			//$this->view = '/Element/message_boxes';
		}elseif($result==2){
			$message = $firm_type_text.' - '.'All Sections, Reply to '.$office_type.' send successfully';
			$redirect_to = '../applicationformspdfs/'.$allSectionDetails[0]['report_pdf'];
			//$this->view = '/Element/message_boxes';	
			
		}elseif($result==0){
			$message = $firm_type_text.' - '.' Please check and fill all reports  and then Final Submit';
			$redirect_to = '../dashboard/home';
			//$this->view = '/Element/message_boxes';	
		}
		
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to); //exit;
	}
			
	//Change on 12/09/2018, Through this function manage the display messages after execute the save, final report, forward , referred back, accepted report functionality - By Pravin Bhakare		
	public function reportPopupMessage($action_event,$result_value,$section_details,$firm_type_text,$office_type){
					
		switch ($action_event) {
		case "save":
					if($result_value == 1){					
						$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name'])).' saved successfully';				
					}
					if($result_value == 2){				
						$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name'])).' Updated successfully';				
					}
					if($result_value == 3){				
						$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name'])).' Reply to '.$office_type.' saved successfully';				
					}
					if($result_value == 4){				
						$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name'])).' Sorry you can not save blank Reply';				
					}
					if($result_value == ""){					
						$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name'])).'Please check and fill all reports properly';				
					}
					return $message;
					break;
					
		case "final_report":
					if($result_value == 1){					
						$message = $firm_type_text.' - '.'Final submitted successfully';				
					}
					if($result_value == 0){					
						$message = $firm_type_text.' - '.' Please check and fill all reports  and then Final Submit';				
					}
					return $message;
					break;
					
		case "send_to_io":
					if($result_value == 1){					
						$message = $firm_type_text.' - '.'Referred Back Comment to IO send successfully';				
					}
					if($result_value == 0){					
						$message = $firm_type_text.' - '.'Referred Back Comment is not saved, Please save Referred Back and then Final Submit';				
					}
					return $message;
					break;
		case "sent_to":
					if($result_value == 2){					
						$message = $firm_type_text.' - '.'All Sections, Reply to '.$office_type.' send successfully';				
					}
					return $message;
					break;
		case "referred_back":
					if($result_value == 1){					
						$message = $firm_type_text.' - '.'Referred Back Comment to IO saved successfully';				
					}else{
						$message = $firm_type_text.' - '.'Referred Back Comment to IO not saved';					
					}
					return $message;
					break;	
		case "accepted":
					if($result_value == 1){					
						$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name'])).' Approved successfully';				
					}
					if($result_value == 2){					
						$message = 'Sorry... Please approved applicant forms first which you have referred back';				
					}
					if($result_value == 3){					
						$message = $firm_type_text.' - '.'is already Accepted';				
					}
					return $message;
		case "accepted_forward":
					if($result_value == 1){		

						if($office_type == 'SO'){ $forwarded_to = 'RO'; }elseif($office_type == 'RO'){ $forwarded_to = 'HO'; }
						$message = $firm_type_text.' - '.' Approved and Forwarded to '.$forwarded_to.' successfully';				
					}						
					return $message;			
					break;				
		}
	
	}		
	
	
	//Change on 04/10/2018, Through this function find the latest created siteinspection report filling pdf - By Pravin Bhakare
	public function findLatestSiteinspectionReportPdf($customer_id,$report_section_details){
	
		$report_pdf_model = $report_section_details['report_pdf'];
		
		if(!empty($report_pdf_model)){
			
			$this->loadModel($report_pdf_model);
			
			$report_pdf_list_id = $this->$report_pdf_model->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
				
				if(!empty($report_pdf_list_id)){
					
					$report_pdf_path = $this->$report_pdf_model->find('all',array('conditions'=>array('id'=>max($report_pdf_list_id))))->first(); 
					$download_siteinspection_report_pdf = $report_pdf_path['pdf_file'];
					return $download_siteinspection_report_pdf;
					
				}else{
					
					$download_siteinspection_report_pdf = "";
					return $download_siteinspection_report_pdf;
				}
		}else{
				$download_siteinspection_report_pdf = "";
				return $download_siteinspection_report_pdf;
			}		
	}


	//Function to call final grant
	public function finalGrantCall(){	
			
		$message='';
		$redirect_to='';
		
		$customer_id = $this->Session->read('customer_id');
		$firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
		$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
		$firm_type = $this->Customfunctions->firmType($customer_id);
		$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
		$application_type = $this->Session->read('application_type');
		
		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
		
		$DmiCommonSiteinspectionFlowDetails = TableRegistry::getTableLocator()->get('DmiCommonSiteinspectionFlowDetails');
		$allSectionDetails = $DmiCommonSiteinspectionFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);
					
		$this->loadModel('DmiTempEsignStatuses');			
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_flow_wise_tables = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		$Dmi_final_submit_tb = TableRegistry::getTableLocator()->get($Dmi_flow_wise_tables['application_form']);
		$Dmi_inspection_final_submit_tb = TableRegistry::getTableLocator()->get($Dmi_flow_wise_tables['inspection_report']);	
		$Dmi_allocation_table_tb = TableRegistry::getTableLocator()->get($Dmi_flow_wise_tables['allocation']);


		//below condition is added to provide Grant table name for some applications, as there is conditional siteinspection for it.
		//it was throwing issue after esigning the doc and storing grant record.
		//on 22-11-2022 by Amol
		if(empty($allSectionDetails)){
			
			$DmiCommonScrutinyFlowDetails = TableRegistry::getTableLocator()->get('DmiCommonScrutinyFlowDetails');
			$allSectionDetails = $DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);
		}
		
		$customer_level_3_approved = $Dmi_final_submit_tb->find('all',
												array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,
														'status'=>'approved', 'current_level'=>'level_3')))->first();														
		
		$inspection_allocated = $Dmi_allocation_table_tb->find('all', array(array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,
														'level_2 IS NOT NULL'))))->first();

		if(empty($customer_level_3_approved)){	
				
				$Dmi_final_submit_tb_entity = $Dmi_final_submit_tb->newEntity(array(
												'customer_id'=>$customer_id,
												'status'=>'approved',
												'current_level'=>'level_3',
												'created'=>date('Y-m-d H:i:s'),
												'modified'=>date('Y-m-d H:i:s')
												));
				
				// approved entry for level 3
				$Dmi_final_submit_tb->save($Dmi_final_submit_tb_entity);
			
				if(!empty($inspection_allocated)){
					$Dmi_inspection_final_submit_entity = $Dmi_inspection_final_submit_tb->newEntity(array(
															'customer_id'=>$customer_id,
															'status'=>'approved',
															'current_level'=>'level_3',
															'created'=>date('Y-m-d H:i:s'),
															'modified'=>date('Y-m-d H:i:s')
															));
					// approved entry for level 3
					$Dmi_inspection_final_submit_tb->save($Dmi_inspection_final_submit_entity);	
				}
				//added on 23-08-2017 by Pravin to send SMS/Email
				//call custom function from Model with message id
				//$this->Dmi_sms_email_template->send_message(30,$customer_id);
				
				//deleting record from temp esign status table, to clear that esign process reached till end succesfully.
				//added on 01-10-2018 by Amol
				$this->DmiTempEsignStatuses->deleteTempEsignRecord($customer_id);													 
				$message = $firm_type_text.' - '.'Final Granted Successfully';
				$redirect_to = '../applicationformspdfs/'.$allSectionDetails[0]['grant_pdf'];	
				//$this->view = '/Element/message_boxes';		
				
				
		}elseif(!empty($customer_level_3_approved)){
				
				if(!empty($inspection_allocated)){
					$Dmi_inspection_final_submit_entity = $Dmi_inspection_final_submit_tb->newEntity(array(
															'customer_id'=>$customer_id,
															'status'=>'approved',
															'current_level'=>'level_3',
															'created'=>date('Y-m-d H:i:s'),
															'modified'=>date('Y-m-d H:i:s')
															));
					// approved entry for level 3
					$Dmi_inspection_final_submit_tb->save($Dmi_inspection_final_submit_entity);	
				}
				//added on 23-08-2017 by Pravin to send SMS/Email
				//call custom function from Model with message id
				//$this->Dmi_sms_email_template->send_message(30,$customer_id);
				
				//deleting record from temp esign status table, to clear that esign process reached till end succesfully.
				//added on 01-10-2018 by Amol 
				$this->DmiTempEsignStatuses->deleteTempEsignRecord($customer_id);
				$message = $firm_type_text.' - '.'Final Granted Successfully';
				$redirect_to = '../applicationformspdfs/'.$allSectionDetails[0]['grant_pdf'];	
				//$this->view = '/Element/message_boxes';		
		}			
		
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);			
	}	

//Ajax function For Constituent Oil Mill details tables end ***********************************************************************************************	
		
	public function tablesEditDetailSessionDelete(){
		
		$this->Session->delete('edit_const_oil_mill_id');
		$this->Session->delete('edit_bevo_oils_tank_id');	
		$this->Session->delete('edit_const_oils_tank_id');
		$this->Session->delete('edit_storage_tank_id');	
	}

}
?>