<?php
namespace App\Controller;
use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;

class ScrutinyController extends AppController{

	var $name = 'Scrutiny';

	public function beforeFilter($event) {

		parent::beforeFilter($event);

		$this->loadComponent('Romoioapplicantcommunicationactions');
		$this->loadComponent('Communication');
		$this->loadComponent('Customfunctions');
		$this->loadComponent('Mastertablecontent');
		$this->loadComponent('Progressbar');
		$this->loadComponent('Paymentdetails');
		$this->loadComponent('Flowbuttons');
		$this->loadComponent('Randomfunctions');

		$this->viewBuilder()->setHelpers(['Form','Html']);

		$this->loadModel('DmiUserRoles');

		if($this->Session->read('username') == null){

			$this->customAlertPage("Sorry You are not authorized to view this page..");
			exit;
		}else{

			//check if user have inspection/scrutiny role
			$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$this->Session->read('username'))))->first();

			if($user_access['super_admin'] == 'yes' || 
				$user_access['dy_ama'] == 'yes' || 
				$user_access['jt_ama'] == 'yes' || 
				$user_access['ama'] == 'yes' || 
				$user_access['form_verification_home'] == 'yes'){
				
				//proceed
				}else{

					$this->customAlertPage("Sorry You are not authorized to view this page..");
					exit;
				}

			$this->set('user_access',$user_access);
		}

	}
	//end of beforeFilter function

	public function home(){

		$this->Session->delete('form_section_id');
		$this->Session->delete('report_application_subtype');
		$this->Session->delete('application_view_mode');
		$this->Session->delete('current_sequence');
		$this->Session->delete('form_type');
		//$this->Session->write('application_type','new');//for new applications home
	}

	/* Start common functionality for form scrutiny */

	public function formScrutinyFetchId($id,$mode,$application_type,$fromHoLevel=null){

		$this->loadModel('DmiFirms');

		//added conditions for chemist flow, get details from chemist registration table
		//on 30-09-2021 by Amol
		if($application_type==4){
			$this->loadModel('DmiChemistRegistrations');
			$customer_id_result = $this->DmiChemistRegistrations->find('all',array('fields'=>'chemist_id', 'conditions'=>array('id IS'=>$id)))->first();
			$customer_id = $customer_id_result['chemist_id'];
		}else{
			$customer_id_result = $this->DmiFirms->find('all',array('fields'=>'customer_id', 'conditions'=>array('id IS'=>$id)))->first();
			$customer_id = $customer_id_result['customer_id'];
		}
		
		$this->Session->write('customer_id',$customer_id);
		$this->Session->write('application_mode',$mode);
		$this->Session->write('fromHoLevel',$fromHoLevel);
		$this->Session->write('application_type',$application_type);
		$this->Session->delete('section_id');

		$this->redirect('/scrutiny/form-scrutiny');
	}

	public function section($id){

		$this->Session->write('section_id',$id);
		$this->redirect('/scrutiny/form-scrutiny');
	}

	public function formScrutiny(){

		$message = "";
		$message_theme = "";
		$redirect_to = "";

		$this->set('cname','scrutiny');
		$this->set('fromHoLevel',$this->Session->read('fromHoLevel'));
		$this->loadModel('DmiAllocations');

		$customer_id = $this->Customfunctions->checkCustomerIdAvailable($this->Session->read('customer_id'));
		$this->set('customer_id',$customer_id);

		$oldapplication = $this->Customfunctions->isOldApplication($customer_id);
		$this->set('oldapplication',$oldapplication);

		// Find out the officer present in SO office, Done by Pravin Bhakare 12-10-2021
		$officerPresentInOff = $this->Customfunctions->findOfficerCountInoffice($this->Session->read('username'));
		$this->set('officer_present_in_off',$officerPresentInOff);

		$authRegFirm = $this->Mastertablecontent->authFirmRegistration($customer_id);
		$this->set('authRegFirm',$authRegFirm);

		$application_type = $this->Session->read('application_type');
		$this->set('application_type',$application_type);

		$document_lists = $this->Mastertablecontent->allDocumentsList();
		$this->set('document_lists',$document_lists);
		
		//added conditions for chemist flow,
		//on 30-09-2021 by Amol
		if($application_type != 4){
			$this->Customfunctions->showOldCertDetailsPopup($customer_id);
		}

		$changefields = array();
		if($application_type == 3){

			$this->changeApplication();
			$changefields = $this->Session->read('changefield');
		}
		$this->set('changefields',json_encode($changefields));

		$selectedSections = array();
		if($application_type == 3){
			$this->loadModel('DmiChangeSelectedFields');
			$selectedfields = $this->DmiChangeSelectedFields->selectedChangeFields();
			$selectedSections = $selectedfields[2];
		}
		$this->set('selectedSections',$selectedSections);

		if($oldapplication == 'yes' && $application_type == 1){

			$this->viewBuilder()->setLayout('old_app_scrutiny_layout');
		}else{

			$this->viewBuilder()->setLayout('form_scrutiny_layout');
		}

		$this->loadModel('DmiFirms');
		$this->loadModel('DmiUserRoles');
		$this->loadModel('DmiSmsEmailTemplates');
		$this->loadModel('DmiCommonScrutinyFlowDetails');
		$this->loadModel('DmiAllDirectorsDetails');

		$user_email_id = $this->Session->read('username');
		$user_as = $this->Session->read('user_as');
		//$this->Session->write('current_level','level_3');
		$current_level = $this->Session->read('current_level');
		$this->set('current_level',$current_level);

		$application_mode = $this->Session->read('application_mode');
		$this->set('application_mode',$application_mode);


		$this->Session->write('application_type',$application_type);

		if($current_level){

			$back_to_inspection_level = $current_level;
		}else{
			$back_to_inspection_level = 'level_3';
		}
		$this->set('back_to_inspection_level',$back_to_inspection_level);

		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

		//Added on 01-09-2017 check lab export unit
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
		$this->set('export_unit_status',$export_unit_status);

		$added_directors_details = $this->DmiAllDirectorsDetails->allDirectorsDetail($customer_id);
		$this->set('added_directors_details',$added_directors_details);

		// This function find out the lastest version of Application pdf (By pravin 08-08-2017)
		$download_application_pdf = $this->Customfunctions->findLatestApplicationPdf($customer_id);
		$this->set('download_application_pdf',$download_application_pdf);

		$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
		$this->set('office_type',$office_type);

		$firm_type = $this->Customfunctions->firmType($customer_id);
		$this->set('firm_type',$firm_type);

		$firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
		$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);

		$final_submit_details = $this->Customfunctions->finalSubmitDetails($customer_id,'application_form');
		$this->set('final_submit_details',$final_submit_details);

		$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
		$this->set('ca_bevo_applicant',$ca_bevo_applicant);

		$applicant_type = $this->Customfunctions->checkFatSpreadOrBevo($customer_id);//call fucntion to check bevo or fat spread
		$this->set('applicant_type',$applicant_type);

		if($form_type=='F' && $ca_bevo_applicant=='yes'){
			$form_type='E';
		}

		$this->set('form_type',$form_type);

		// check current form section value
		if(isset($_SESSION['section_id'])){
			$section_id = $this->Session->read('section_id');
		}else{
			$section_id = 1;
			$this->Session->write('section_id',$section_id);
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
		$firm_details = $this->DmiFirms->firmDetails($customer_id);


		$this->set('firm_details',$firm_details);

		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();

		$Dmi_allocation_table_name = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'allocation');
		$Dmi_allocation_table = TableRegistry::getTableLocator()->get($Dmi_allocation_table_name);

		$appl_current_pos_table_name = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'appl_current_pos');
		$Dmi_appl_current_pos_table = TableRegistry::getTableLocator()->get($appl_current_pos_table_name);

		$final_submit_table_name = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'application_form');
		$Dmi_final_submit_table = TableRegistry::getTableLocator()->get($final_submit_table_name);

		// get current section all details
		$section_details = $this->DmiCommonScrutinyFlowDetails->currentSectionDetails($application_type,$office_type,$firm_type,$form_type,$section_id);

		// get all section all details
		$allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);

		$section_model = $section_details['section_model'];
		$section = $section_details['section_name'];
		$this->set('section',$section);

		// get section details
		$this->loadModel($section_model);
		$section_form_details = $this->$section_model->sectionFormDetails($customer_id);

		// if return value 1 (all forms saved), return value 2 (all forms approved), return value 0 (all forms not saved or approved)
		$all_section_status = $this->Customfunctions->formStatusValue($allSectionDetails,$customer_id);

		// get previous and next button id
		$nextPreviousBtn =	$this->Customfunctions->getNextPreSec($allSectionDetails);
		$this->set('previousbtnid',$nextPreviousBtn[0]);
		$this->set('nextbtnid',$nextPreviousBtn[1]);


		if(!empty($final_submit_details)){
			$final_submit_status = $final_submit_details['status'];
		}else{
			$final_submit_status = 'no_final_submit';
		}
		$this->set('final_submit_status',$final_submit_status);

		$progress_bar_status = $this->Progressbar->formsProgressBarStatus($allSectionDetails,$customer_id);
		$this->set('progress_bar_status',$progress_bar_status);

		$formScrutinyStatus = $Dmi_final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'status'=>'approved','current_level'=>'level_1', $grantDateCondition)))->first();

		// fetch all allocation details
		$allocation_deatils = $Dmi_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();
		$this->set('allocation_deatils',$allocation_deatils);

		//to check if report filed, then hide comm. with MO box, and show button for back to report
		//allow RO to referred back to applicant from report inspection window.
		$check_if_report_filed_id_object = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['inspection_report']);

		$check_if_report_filed_id = $check_if_report_filed_id_object->siteinspectionFinalReportStatus($customer_id);
		if(!empty($check_if_report_filed_id)){
			$check_if_report_filed= $check_if_report_filed_id;
		}else{
			$check_if_report_filed = null;
		}

		// This condition work for optional siteinspection for renewal flow.
		if(!empty($formScrutinyStatus) && empty($allocation_deatils['level_2'])){
			$check_if_report_filed = 'yes';
		}

		$this->set('check_if_report_filed',$check_if_report_filed);

		// Get chemist comment history details. Done by Aakash Thakare 30-09-2021
		$this->Session->write('application_dashboard','ro');
		$this->Communication->singleWindowCommentHistory($customer_id);


		//to check ro current comment to // added on 26-05-2017 by amol
		$level3_current_comment_to = $this->Customfunctions->checkLevel3CurrentCommentTo($allSectionDetails,$customer_id);
		$this->set('level3_current_comment_to',$level3_current_comment_to);

		$fetch_comment_reply = $this->Customfunctions->getCommentReply($section_model,$customer_id);
		$this->set('fetch_comment_reply',$fetch_comment_reply);

		$fetch_applicant_communication = $this->Customfunctions->getApplicantCommentReply($section_model,$customer_id);
		$this->set('fetch_applicant_communication',$fetch_applicant_communication);

		//get forward btn and grant btn display status value
		$forward_to_btn = $this->Flowbuttons->ShowNodalLevelForwardBtnAfterScru($customer_id,$application_type,$section_details,$allSectionDetails);
		$this->set('forward_to_btn',$forward_to_btn);
		$final_granted_btn = $this->Flowbuttons->ShowNodalLevelGrantBtnAfterScru($customer_id,$application_type,$section_details,$allSectionDetails);
		$this->set('final_granted_btn',$final_granted_btn);

		$this->set('tablename',$section_model);
		$this->set('current_form_data',$section_form_details[0]);
		$last_record_with_delete_null = $this->Communication->lastRecordWithDeleteNull($section_model,$customer_id);

		$this->Communication->editDeleteOptionForMoRoCommunication($section_model,$customer_id,$current_level,$last_record_with_delete_null);

		//For MO comment
		if (null!==($this->request->getData('save_edited_mo_comment'))){

			$htmlencoded_comment_by_mo = $this->request->getData('edited_mo_comment');

			if(!empty($this->request->getData('mo_comment_ul')->getClientFilename())){

				$file_name = $this->request->getData('mo_comment_ul')->getClientFilename();
				$file_size = $this->request->getData('mo_comment_ul')->getSize();
				$file_type = $this->request->getData('mo_comment_ul')->getClientMediaType();
				$file_local_path = $this->request->getData('mo_comment_ul')->getStream()->getMetadata('uri');

				$mo_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

			}else{ $mo_comment_ul = $section_form_details[0]['mo_comment_ul']; }

			$redirect_location = '/scrutiny/form-scrutiny';
			$this->Communication->saveEditedMoComment($section_model,$htmlencoded_comment_by_mo,$mo_comment_ul,$redirect_location);
		}

		//For RO reply
		if (null!==($this->request->getData('save_edited_ro_reply'))){

			$htmlencoded_reply_by_ro = $this->request->getData('edited_ro_reply');

			if(!empty($this->request->getData('rr_comment_ul')->getClientFilename())){

				$file_name = $this->request->getData('rr_comment_ul')->getClientFilename();
				$file_size = $this->request->getData('rr_comment_ul')->getSize();
				$file_type = $this->request->getData('rr_comment_ul')->getClientMediaType();
				$file_local_path = $this->request->getData('rr_comment_ul')->getStream()->getMetadata('uri');

				$rr_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

			}else{ $rr_comment_ul = $section_form_details[0]['rr_comment_ul']; }

			$redirect_location = '/scrutiny/form-scrutiny';
			$this->Communication->saveEditedRoReply($section_model,$htmlencoded_reply_by_ro,$rr_comment_ul,$redirect_location);
		}


		//Start code for Edit/Delete options For RO on Communication with Applicant.
		if($current_level == 'level_3'){

			$reffered_back_date = $section_form_details[0]['reffered_back_date'];
			$this->Communication->editDeleteOptionForRoApplicantCommunication($customer_id,$reffered_back_date);

			if (null!==($this->request->getData('save_edited_referred_back'))){

				$htmlencoded_edited_referred_back = $this->request->getData('edited_referred_back');

				if(!empty($this->request->getData('rb_comment_ul')->getClientFilename())){

					$file_name = $this->request->getData('rb_comment_ul')->getClientFilename();
					$file_size = $this->request->getData('rb_comment_ul')->getSize();
					$file_type = $this->request->getData('rb_comment_ul')->getClientMediaType();
					$file_local_path = $this->request->getData('rb_comment_ul')->getStream()->getMetadata('uri');

					$rb_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

				}else{ $rb_comment_ul = $section_form_details[0]['rb_comment_ul']; }

				$redirect_location = '/scrutiny/form-scrutiny';
				$this->Communication->saveEditedReferredBack($section_model,$htmlencoded_edited_referred_back,$rb_comment_ul,$redirect_location);
			}
		}
		//End code for Edit/Delete options For RO on Communication with Applicant.

		// Saved referredback comments in chemist flow, Done Aakash Thakare 30-09-2021
		if(null!==($this->request->getData('che_ro_referred_back'))){

			$result = $this->Communication->singleWindowReferredback($this->request->getData(),$allSectionDetails);
			if($result == 1)
			{	
				$this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Success'); #Action
				$message =$firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Reffered back comments to applicant saved successfully";
				$message_theme = "success";
				$redirect_to = '../scrutiny/form-scrutiny';

			}elseif($result == 2){
				$this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Failed'); #Action
				$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Sorry you can not save blank Reffered back";
				$message_theme = "failed";
				$redirect_to = '../scrutiny/form-scrutiny';
			};

		}elseif (null!==($this->request->getData('accepted'))){

			//commented code removed on 14-04-2017


		//referred back by RO to Applicant
		}elseif(null!==($this->request->getData('ro_referred_back'))) {


			//called this component to optimized code on 02-04-2018
			$result = $this->Romoioapplicantcommunicationactions->referredBackTo($customer_id,$section_model,$section_form_details[0],$this->request->getData('reffered_back_comment'),$this->request->getData('rb_comment_ul'),'Level3ToApplicant');

			if($result == 1)
			{	
				$this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Success'); #Action
				$message =$firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Reffered back comments to applicant saved successfully";
				$message_theme = "success";
				$redirect_to = '../scrutiny/form-scrutiny';

			}elseif($result == 2){

				$this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Failed'); #Action
				$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Sorry you can not save blank Reffered back";
				$message_theme = "warning";
				$redirect_to = '../scrutiny/form-scrutiny';
			};


		// Referred Back by MO to RO
		}elseif (null!==($this->request->getData('mo_referred_back'))) {

			//called this component to optimized code on 02-04-2018
			$result = $this->Romoioapplicantcommunicationactions->referredBackTo($customer_id,$section_model,$section_form_details[0],$this->request->getData('comment_by_mo'),$this->request->getData('mo_comment_ul'),'Level1ToLevel3');

			if($result == 1){

				$this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Success'); #Action
				$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Reffered back comments to $office_type saved successfully ";
				$message_theme = "success";
				$redirect_to = "../scrutiny/form-scrutiny";

			}elseif($result == 2){

				$this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Failed'); #Action
				$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Sorry you can not save blank Reffered back";
				$message_theme = "warning";
				$redirect_to = "../scrutiny/form-scrutiny";
			}

		//Ro Reply to MO
		}elseif (null!==($this->request->getData('ro_reply'))) {

			//called this component to optimized code on 02-04-2018
			$result = $this->Romoioapplicantcommunicationactions->RO2MOcommunication($customer_id,$section_model,$section_form_details[0],$this->request->getData('ro_reply_comment'),$this->request->getData('rr_comment_ul'),'Level3ToLevel1');

			if($result == 1 || $result == 3){

				$this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Success'); #Action
				$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, reply to Scrutinizer saved successfully";
				$message_theme = "success";
				$redirect_to = "../scrutiny/form-scrutiny";

			}elseif($result == 2){

				$this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Failed'); #Action
				$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name']))." Section, Sorry you can not save blank Reply";
				$message_theme = "warning";
				$redirect_to = "../scrutiny/form-scrutiny";

			}elseif($result == 4){

				$this->Customfunctions->saveActionPoint('Reffered Back Comment Saved', 'Failed'); #Action
				$message = "Scrutinizer is not allocated for this Application yet";
				$message_theme = "failed";
				$redirect_to = "../scrutiny/form-scrutiny";
			}
			//For Form Scrutiny by RO only, not by MO now

		}elseif (null!==($this->request->getData('sent_to_applicant'))){

			//called this component to optimized code on 02-04-2018
			$result = $this->Romoioapplicantcommunicationactions->RO2ApplicantCommentFinalSubmit($customer_id,$section_model,$level3_current_comment_to,$current_level,$allSectionDetails);
			if($result == 1){

				//This below action call is added t save the action log for the user by AKASH on 18-08-2022
				$this->Customfunctions->saveActionPoint('Application Reffered Back to Applicant', 'Success');
				$message = 'All reffered back comments to applicant sent successfully';
				$message_theme = "success";
				$redirect_to = "../dashboard/home";

			}elseif($result == 2){
				
				//This below action call is added t save the action log for the user by AKASH on 18-08-2022
				$this->Customfunctions->saveActionPoint('Application Reffered Back to Applicant', 'Failed');
				$message = $firm_type_text." - Reffered back are not saved, Please save Referred back on Atleast one Section and then Final Submit";
				$message_theme = "warning";
				$redirect_to = "../scrutiny/form-scrutiny";
			}

		}elseif (null!== ($this->request->getData('sent_to'))){

			//called this component to optimized code on 02-04-2018
			$result = $this->Romoioapplicantcommunicationactions->RO2MOandMO2ROCommentFinalSubmit($customer_id,$level3_current_comment_to,$office_type,$allSectionDetails);
			if($result[0] == 1){

				//This below action call is added t save the action log for the user by AKASH on 19-08-2022
				$this->Customfunctions->saveActionPoint('Application Sent to Scrutinizer', 'Success');
				$message = $firm_type_text." application sent to $result[1] successfully";
				$message_theme = "success";
				$redirect_to ="../dashboard/home";

			}elseif($result[0] == 2){

				//This below action call is added t save the action log for the user by AKASH on 19-08-2022
				$this->Customfunctions->saveActionPoint('Application Sent to Scrutinizer', 'Failed');
				$message = $firm_type_text." - Replies/Comments are not saved, Please save Comments on Atleast one Section and then Final Submit";
				$message_theme = "failed";
				$redirect_to = "../scrutiny/form-scrutiny";

			}elseif($result[0] == 3){

				//This below action call is added t save the action log for the user by AKASH on 19-08-2022
				$this->Customfunctions->saveActionPoint('Application Sent to Scrutinizer', 'Failed');
				$message = "Scrutinizer is not allocated for this application yet";
				$message_theme = "failed";
				$redirect_to ="../scrutiny/form-scrutiny";
			}

		}elseif (null!== ($this->request->getData('mo_verified'))) { //only done by RO but recorded as level 1 entry

			//called this component to optimized code on 02-04-2018

			$result = $this->Romoioapplicantcommunicationactions->ROScrutinizedATMOLevel($customer_id,$section_model,$section_form_details[0],$allSectionDetails);

			if($result == 1 && $oldapplication=='yes' && ($application_type==1 || $application_type==6)){//added appl type 6 cond. on 23-11-2021

				$this->Romoioapplicantcommunicationactions->ROScrutinizedOldApplication($customer_id);

				//This below action call is added t save the action log for the user by AKASH on 19-08-2022
				$this->Customfunctions->saveActionPoint('All Section Scrutinized', 'Success');
				$message = $firm_type_text." - All sections scrutinized and backlog data verified successfully";
				$message_theme = "success";
				$redirect_to =  '../dashboard/home';

			}elseif($result == 1){

				//as per new order by 01-04-2021 from DMI
				//if lab is NABL accreditated then no site inspection will be done, forwarded to HO
				//applied on 29-09-2021 by Amol
				$NablDate = $this->Randomfunctions->checkIfLabNablAccreditated($customer_id);

				//applied this condition for lab export, on 01-09-2017 by Amol
				//if all Sections scrutinized then stay on same page and show forward to HO btn directly
				if((($export_unit_status == 'yes' || $NablDate != null) && $firm_type == 3 ) 
				 	|| ($firm_type == 3 && $export_unit_status == 'yes' && $application_type == 8)){  // # If Block Statement Updated for the Application Type 8 (ADP Flow)- Shankhpal [17/11/2022] #Reason : 

					//this HO level allocation applied for lab export application
					//on 02-09-2017 by Amol
					$find_dy_ama_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('dy_ama'=>'yes','OR'=>array('super_admin IS NULL','super_admin'=>'no'))))->first();
					$dy_ama_email_id = $find_dy_ama_user['user_email_id'];

					$find_jt_ama_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('jt_ama'=>'yes','OR'=>array('super_admin IS NULL','super_admin'=>'no'))))->first();;
					$jt_ama_email_id = $find_jt_ama_user['user_email_id'];


					$find_ama_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('ama'=>'yes','OR'=>array('super_admin IS NULL','super_admin'=>'no'))))->first();;
					$ama_email_id = $find_ama_user['user_email_id'];

					$Dmi_ho_allocation = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['ho_level_allocation']);
					$Dmi_all_applications_current_position = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['appl_current_pos']);

					$Dmi_ho_allocation_entity = $Dmi_ho_allocation->newEntity(array(

						'customer_id'=>$customer_id,
						'dy_ama'=>$dy_ama_email_id,
						'jt_ama'=>$jt_ama_email_id,
						'ama'=>$ama_email_id,
						'current_level'=>$dy_ama_email_id,
						'created'=>date('Y-m-d H:i:s'),
						'modified'=>date('Y-m-d H:i:s')

					));
					
					if($Dmi_ho_allocation->save($Dmi_ho_allocation_entity)){

						//Update record in all applications current position table
						//created and applied on 02-09-2017 by amol
						$customer_id = $customer_id;
						$user_email_id = $dy_ama_email_id;
						$current_level = 'level_4';
						$Dmi_all_applications_current_position->currentUserUpdate($customer_id,$user_email_id,$current_level);//call to custom function from model

						#SMS: RO forwarded to HO
						$this->DmiSmsEmailTemplates->sendMessage(20,$customer_id);
					}

					$this->Customfunctions->saveActionPoint('All Section Scrutinized', 'Success'); #Action
					$message = $firm_type_text." - All sections scrutinized and forwarded to HO successfully";
					$message_theme = "success";
					$redirect_to = "../dashboard/home";

				}else{

					$this->Customfunctions->saveActionPoint('All Section Scrutinized', 'Success'); #Action
					$message = $firm_type_text." - All sections scrutinized successfully";
					$message_theme = "success";
					$redirect_to =  '../dashboard/home';
				}

			}elseif($result == 2){

				$this->Customfunctions->saveActionPoint('Section Scrutinized', 'Success'); #Action
				$message = $firm_type_text." - ".ucwords(str_replace('_',' ',$section_details['section_name']))." Section Scrutinized successfully";
				$message_theme = "success";
				$redirect_to = "../scrutiny/form-scrutiny";

			}elseif($result == 3){

				$this->Customfunctions->saveActionPoint('Section Scrutinized', 'Failed'); #Action
				$message = $firm_type_text." - ".ucwords(str_replace('_',' ',$section_details['section_name']))." Section already Scrutinized";
				$redirect_to = "../scrutiny/form-scrutiny";

			}elseif($result == 4){

				$this->Customfunctions->saveActionPoint('Section Scrutinized', 'Failed'); #Action
				$message = "Please verify old dates entered before scrutinizing the section, The button is given above to edit/view the old dates.'";
				$message_theme = "failed";
				$redirect_to =  '../scrutiny/form-scrutiny';
			}
			//This button is used to Send from Mo to Ro & Ro to MO both

		}elseif(null!== ($this->request->getData('accepted_forward'))){

			if($forward_to_btn == 'RO'){

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
				$Dmi_appl_current_pos_table->currentUserUpdate($customer_id,$user_email_id,$current_level);

				#SMS: RO forwarded to HO
				$this->DmiSmsEmailTemplates->sendMessage(20,$customer_id);
				
				$this->Customfunctions->saveActionPoint('Application Forwarded', 'Success'); #Action
				$message = $firm_type_text.' Forwarded to '.$forward_to_btn.' successfully';
				$message_theme = "success";
				$redirect_to =  '../dashboard/home';
			}

		}

		$this->set('all_section_status',$all_section_status);
		$this->set('section_details',$section_details);
		$this->set('allSectionDetails',$allSectionDetails);
		$this->set('section_form_details',$section_form_details);

		$this->set('message',$message);
		$this->set('message_theme',$message_theme);
		$this->set('redirect_to',$redirect_to);
	}




	public function payment(){

		$customer_id = $this->Session->read('customer_id');
		if(!empty($customer_id)){

			// set variables to show popup messages from view file
			$message = '';
			$redirect_to = '';

			$this->viewBuilder()->setLayout('form_scrutiny_layout');

			$this->loadModel('DmiCommonScrutinyFlowDetails');
			$this->loadModel('DmiFlowWiseTablesLists');
			$this->loadModel('DmiFirms');
			$this->loadModel('DmiApplicationCharges');
			$this->loadModel('MCommodity');
			$this->loadModel('MCommodityCategory');
			$this->loadModel('DmiPackingTypes');
			$this->loadModel('DmiChangeFirms');

			if($this->Session->read('current_level')){

				$back_to_inspection_level = $this->Session->read('current_level');
			}else{
				$back_to_inspection_level = 'level_3';
			}
			$this->set('back_to_inspection_level',$back_to_inspection_level);

			$this->set('current_level',$this->Session->read('current_level'));


			// This function find out the lastest version of Application pdf (By pravin 08-08-2017)
			$download_application_pdf = $this->Customfunctions->findLatestApplicationPdf($customer_id);
			$this->set('download_application_pdf',$download_application_pdf);

			//check CA BEVO Applicant
			$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
			$this->set('ca_bevo_applicant',$ca_bevo_applicant);

			$application_type = $this->Session->read('application_type');
			$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);

			$firm_type = $this->Customfunctions->firmType($customer_id);
			$this->set('firm_type',$firm_type);

			// Find out the officer present in SO office, Done by Pravin Bhakare 12-10-2021
			$officerPresentInOff = $this->Customfunctions->findOfficerCountInoffice($this->Session->read('username'));
			$this->set('officer_present_in_off',$officerPresentInOff);

			$firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
			$this->set('form_type',$form_type);
			$section_details = $this->DmiCommonScrutinyFlowDetails->currentSectionDetails($application_type,$office_type,$firm_type,$form_type,1);

			$allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);

			// get previous and next button id
			$previousBtn =	$this->Customfunctions->getNextPreSec($allSectionDetails);
			$previous_button_url = 'scrutiny/section/'.$previousBtn[2];

			// For change flow
			$selectedSections = array();
			if($application_type == 3){
				$this->loadModel('DmiChangeSelectedFields');
				$selectedfields = $this->DmiChangeSelectedFields->selectedChangeFields();
				$selectedSections = $selectedfields[2];
			}
			$this->set('selectedSections',$selectedSections);


			$level3_current_comment_to = $this->Customfunctions->checkLevel3CurrentCommentTo($allSectionDetails,$customer_id);
			$this->set('level3_current_comment_to',$level3_current_comment_to);

			$progress_bar_status = $this->Progressbar->formsProgressBarStatus($allSectionDetails,$customer_id);
			$this->set('progress_bar_status',$progress_bar_status);

			$oldapplication = $this->Customfunctions->isOldApplication($customer_id);

			$this->set('oldapplication',$oldapplication);

			// if return value 1 (all forms saved), return value 2 (all forms approved), return value 0 (all forms not saved or approved)
			$all_section_status = $this->Customfunctions->formStatusValue($allSectionDetails,$customer_id);

			$payment_table = $this->DmiFlowWiseTablesLists->getFlowWiseTableDetails($application_type,'payment');

			$final_submit_details = $this->Customfunctions->finalSubmitDetails($customer_id,'application_form');
			$this->set('final_submit_details',$final_submit_details);

			$firm_detail = $this->DmiChangeFirms->sectionFormDetails($customer_id);
			$firm_details = $firm_detail[0];
			$this->set('firm_details',$firm_details);

			// Fetch submitted Payment Details and show // Done By pravin 13/10/2017
			$this->Paymentdetails->applicantPaymentDetails($customer_id,$firm_details['district'],$payment_table);

			$application_charge = $this->Customfunctions->applicationCharges($application_type,$firm_type);
			$this->set('application_charge',$application_charge);

			$this->loadModel($payment_table);
			$list_applicant_payment_id = $this->$payment_table->find('list', array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
			if(!empty($list_applicant_payment_id)){ $process_query = 'Updated'; }else{ $process_query = 'Saved'; }

			$sub_commodity_array = explode(',',$firm_details['sub_commodity']);

			if(!empty($firm_details['sub_commodity'])){
				$i=0;
				foreach($sub_commodity_array as $sub_commodity_id)
				{
					$fetch_commodity_id = $this->MCommodity->find('all',array('conditions'=>array('commodity_code IN'=>$sub_commodity_id)))->first();
					$commodity_id[$i] = $fetch_commodity_id['category_code'];

					$sub_commodity_data[$i] =  $fetch_commodity_id;

					$i=$i+1;
				}

				$unique_commodity_id = array_unique($commodity_id);

				$commodity_name_list = $this->MCommodityCategory->find('all',array('conditions'=>array('category_code IN'=>$unique_commodity_id, 'display'=>'Y')))->toArray();

				$this->set('commodity_name_list',$commodity_name_list);

				$this->set('sub_commodity_data',$sub_commodity_data);
			}

			if(!empty($firm_details['packaging_materials'])){

				$packaging_materials = explode(',',$firm_details['packaging_materials']);
				$packaging_type = $this->DmiPackingTypes->find('list', array('keyField'=>'id','valueField'=>'packing_type', 'conditions'=>array('id IN'=>$packaging_materials)))->toArray();
				$this->set('packaging_type',$packaging_type);
			}

			if(!empty($final_submit_details)){
				$final_submit_status = $final_submit_details['status'];
			}else{
				$final_submit_status = 'no_final_submit';
			}
			$this->set('final_submit_status',$final_submit_status);

			// set variables to show popup messages from view file
			$this->set('previous_button_url',$previous_button_url);
			$this->set('allSectionDetails',$allSectionDetails);
			$this->set('all_section_status',$all_section_status);
			$this->set('section_details',$section_details);
		}

	}

	//form RO to Applicant referred back
	public function editReferredBack(){
		$this->autoRender = false;
		$id = $_POST['referred_back_max_id'];
		$this->Session->write('edit_referred_back_id',$id);
	}

	public function deleteReferredBack(){
		$this->autoRender = false;
		$id = $_POST['referred_back_max_id'];
		$model_name = $_POST['model_name'];
		$this->loadModel($model_name);
		$this->$model_name->delete(array('id'=>$id));
	}

	//MO and Level3 user comments edit and delete methods
	public function editMoComment(){
		$this->autoRender = false;
		$id = $_POST['mo_comment_max_id'];
		$this->Session->write('edit_mo_comment_id',$id);
	}

	public function deleteMoComment(){
		$this->autoRender = false;
		$id = $_POST['mo_comment_max_id'];
		$model_name = $_POST['model_name'];
		$this->loadModel($model_name);
		$this->$model_name->delete(array('id'=>$id));
	}

	public function editRoReply(){
		$this->autoRender = false;
		$id = $_POST['ro_reply_max_id'];
		$this->Session->write('edit_ro_reply_id',$id);
	}

	public function deleteRoReply(){

		$this->autoRender = false;
		$id = $_POST['ro_reply_max_id'];
		$model_name = $_POST['model_name'];
		$this->loadModel($model_name);

		//to check if ro commented first & created new row then delete entire row
		$check_row_comment_by = $this->$model_name->find('all',array('conditions'=>array('id IS'=>$id)))->first();
		if($check_row_comment_by['mo_comment_date'] == null)
		{
			$this->$model_name->delete(array('id'=>$id));
		}
		else{
			$model_name_entity = $this->$model_name->newEntity(array(
				'id'=>$id,
				'ro_reply_comment'=>null,
				'rr_comment_ul'=>null,
				'ro_current_comment_to'=>'both'
			));
			//only update the row with ro_reply null
			$this->$model_name->save($model_name_entity);
		}
	}

	public function deleteComment($id,$section_id)
	{
		$this->autoRender = false;
		$customer_id = $this->Customfunctions->sessionCustomerID();
		$application_type = $this->Session->read('application_type');
		//print_r($customer_id);  exit;
		$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
		$firm_type = $this->Customfunctions->firmType($customer_id);
		$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);

		$this->loadModel('DmiChemistComments');
		$this->loadModel('DmiCommonScrutinyFlowDetails');

		$allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);
		//print_r($allSectionDetails); exit;
		$section_id = $section_id - 1;
		$section_model = $allSectionDetails[$section_id]['section_model'];
		$this->loadModel($section_model);
		
		$this->DmiChemistComments->id = $id;
		$entity = $this->DmiChemistComments->get($id);
		$this->DmiChemistComments->delete($entity);

		$this->$section_model->updateAll(
			array('form_status' => "saved",'ro_current_comment_to'=>'both'),
			array('customer_id'=>$customer_id,'is_latest'=>'1')
		);						

		$this->redirect('/scrutiny/form-scrutiny');				
	}


	public function changeApplication(){

		$this->loadModel('DmiChangeSelectedFields');
		$this->loadModel('DmiChangeFieldLists');

		$selectedValues = $this->DmiChangeSelectedFields->selectedChangeFields();

		$fieldResult = $this->DmiChangeFieldLists->changeFieldList($selectedValues[0]);

		$this->Session->write('changefield',$fieldResult[0]);
		$this->Session->write('paymentforchange',$fieldResult[1]);

	}

	/* End common functionality for form scrutiny */


}
?>
