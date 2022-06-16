<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
class DmiSiteinspectionPremisesDetailsTable extends Table{
	
	var $name = "DmiSiteinspectionPremisesDetails";
	
	public $validate = array(
		
			'referred_back_comment'=>array(
					'rule'=>array('maxLength',200),				
				),											
			'io_reply'=>array(
					'rule'=>array('maxLength',200),
				),
			'premises_inspected'=>array(
					'rule'=>array('maxLength',10),
				),
			'premises_inspected_status'=>array(
					'rule'=>array('maxLength',200),
				),
			'premises_inspected_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'room_site_plan_no'=>array(
					'rule'=>array('maxLength',100),
				),
			'room_details_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'storage_site_plan_no'=>array(
					'rule'=>array('maxLength',100),
				),
			'storage_details_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'locking_adequate'=>array(
					'rule'=>array('maxLength',10),
				),
			'locking_details_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'lighted_ventilated'=>array(
					'rule'=>array('maxLength',10),
				),
			'ventilation_details_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'conditions_fulfilled'=>array(
					'rule'=>array('maxLength',10),
				),
			'condition_details_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'constituent_oil_mill_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'separate_pipe_lines'=>array(
					'rule'=>array('maxLength',10),
				),
				
	);
	
	
	
	public function sectionFormDetails($customer_id)
	{
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
				
		if($latest_id != null){
			$report_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
			$form_fields_details = $report_fields;
			
		}else{
			$form_fields_details = Array (  'id' =>"", 'customer_id' =>"", 'io_reply_once_no' =>"", 'user_email_id' =>"", 'user_once_no' =>"", 'referred_back_comment' =>"", 'referred_back_date' =>"", 'io_reply' =>"", 
											 'io_reply_date' =>"", 'form_status' =>"", 'premises_inspected' =>"", 'premises_inspected_status' =>"", 'premises_inspected_docs' =>"", 'room_site_plan_no' =>"",
											 'room_details_docs' =>"", 'storage_site_plan_no' =>"", 'storage_details_docs' =>"", 'locking_adequate' =>"no", 'locking_details' =>"", 'locking_details_docs' =>"", 'lighted_ventilated' =>"no",
											 'ventilation_details' =>"", 'ventilation_details_docs' =>"", 'conditions_fulfilled' =>"no", 'condition_details' =>"", 'condition_details_docs' =>"", 'approved_date' =>"",
											 'referred_back_by_email' =>"", 'referred_back_by_once' =>"", 'current_level' =>"", 'constituent_oil_mill_docs' =>"", 'separate_pipe_lines' =>"no", 'delete_ro_referred_back' =>""); 
			
		}
		
		$Dmi_all_tanks_detail = TableRegistry::getTableLocator()->get('DmiAllTanksDetails');
		$user_email_id = $_SESSION['username'];
		$added_storage_tanks_details = $Dmi_all_tanks_detail->tanksDetails($user_email_id,null,null);		
		$added_const_tanks_details = $Dmi_all_tanks_detail->tanksDetails($user_email_id,'constituent',null);
		$added_bevo_tanks_details = $Dmi_all_tanks_detail->tanksDetails($user_email_id,'bevo',null);
		
		return array($form_fields_details,$added_storage_tanks_details,$added_const_tanks_details,$added_bevo_tanks_details);			
	}
	
	
	public function saveFormDetails($customer_id,$forms_data){
		
		$CustomersController = new CustomersController;			
		
		$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
		
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'inspection_report');
		
		$Dmi_siteinspection_final_report = TableRegistry::getTableLocator()->get($final_submit_table);
		$report_final_status = $Dmi_siteinspection_final_report->siteinspectionFinalReportStatus($customer_id);
		
		
		$message_id = 1;
		$current_level = $_SESSION['current_level'];						

		$section_form_details = $this->sectionFormDetails($customer_id);
		
		if(!empty($section_form_details[0]['id'])){
			
			$message_id = 2;																		
			
			if(isset($forms_data['io_reply'])){$io_reply = $forms_data['io_reply']; }else{ $io_reply = null; }
			if(isset($report_final_status['status'])){$reportFinalStatusValue = $report_final_status['status']; }else{ $reportFinalStatusValue = null; }
			
			if($current_level == 'level_2' && empty($io_reply) && $reportFinalStatusValue == 'referred_back'){
				
				return 4;  //error "comment required"
			}
			
			if($current_level == 'level_2' && !empty($io_reply) && $reportFinalStatusValue == 'referred_back'){
				
				$message_id = 3;							
			}
		
		}

		if($ca_bevo_applicant == 'no')
		{
				
			//html encoding post data before saving
			$htmlencoded_storage_site_plan_no = htmlentities($forms_data['storage_site_plan_no'], ENT_QUOTES);
			$htmlencoded_condition_details = htmlentities($forms_data['condition_details'], ENT_QUOTES);	

			//checking radio buttons input
			$post_input_request = $forms_data['conditions_fulfilled'];				
			$conditions_fulfilled = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
			if($conditions_fulfilled == null){ return false;}
		
			//file uploading
			if(!empty($forms_data['storage_details_docs']->getClientFilename())){

				$file_name = $forms_data['storage_details_docs']->getClientFilename();
				$file_size = $forms_data['storage_details_docs']->getSize();
				$file_type = $forms_data['storage_details_docs']->getClientMediaType();
				$file_local_path = $forms_data['storage_details_docs']->getStream()->getMetadata('uri');

				$storage_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{	
				$storage_details_docs = '';
			}


			if(!empty($forms_data['condition_details_docs']->getClientFilename())){

				$file_name = $forms_data['condition_details_docs']->getClientFilename();
				$file_size = $forms_data['condition_details_docs']->getSize();
				$file_type = $forms_data['condition_details_docs']->getClientMediaType();
				$file_local_path = $forms_data['condition_details_docs']->getStream()->getMetadata('uri');

				$condition_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{											
				$condition_details_docs = '';
			}
			
			//Set all other values to null, not required in CA Form A
			$separate_pipe_lines = null;
			$constituent_oil_mill_docs = null;
				
				//if BEVO applicant
		}elseif($ca_bevo_applicant == 'yes')
		{	
			
			//checking radio buttons inputs
			$post_input_request = $forms_data['separate_pipe_lines'];				
			$separate_pipe_lines = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
			if($separate_pipe_lines == null){ return false;}
		
		
			//file uploading
			if(!empty($forms_data['constituent_oil_mill_docs']->getClientFilename())){

				$file_name = $forms_data['constituent_oil_mill_docs']->getClientFilename();
				$file_size = $forms_data['constituent_oil_mill_docs']->getSize();
				$file_type = $forms_data['constituent_oil_mill_docs']->getClientMediaType();
				$file_local_path = $forms_data['constituent_oil_mill_docs']->getStream()->getMetadata('uri');

				$constituent_oil_mill_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{							
				$constituent_oil_mill_docs = '';
			}
			
			
			//Set all other values to null, not required in CA BEVO
			$htmlencoded_premises_inspected_status = null;
			$htmlencoded_storage_site_plan_no = null;
			$htmlencoded_condition_details = null;
			$premises_inspected = null;
			$conditions_fulfilled = null;
			$premises_inspected_docs = null;
			$storage_details_docs = null;
			$condition_details_docs = null;

		}

				
		//html encoding post data before saving
		$htmlencoded_room_site_plan_no = htmlentities($forms_data['room_site_plan_no'], ENT_QUOTES);	
		$htmlencoded_locking_details = htmlentities($forms_data['locking_details'], ENT_QUOTES);
		$htmlencoded_ventilation_details = htmlentities($forms_data['ventilation_details'], ENT_QUOTES);	
		
		
		//checking radio buttons inputs
		$post_input_request = $forms_data['locking_adequate'];				
		$locking_adequate = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($locking_adequate == null){ return false;}

		$post_input_request = $forms_data['lighted_ventilated'];				
		$lighted_ventilated = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($lighted_ventilated == null){ return false;}
		
		//file uploads
		if(!empty($forms_data['room_details_docs']->getClientFilename())){

			$file_name = $forms_data['room_details_docs']->getClientFilename();
			$file_size = $forms_data['room_details_docs']->getSize();
			$file_type = $forms_data['room_details_docs']->getClientMediaType();
			$file_local_path = $forms_data['room_details_docs']->getStream()->getMetadata('uri');

			$room_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{						
			$room_details_docs = '';
		}
					
					
		if(!empty($forms_data['locking_details_docs']->getClientFilename())){

			$file_name = $forms_data['locking_details_docs']->getClientFilename();
			$file_size = $forms_data['locking_details_docs']->getSize();
			$file_type = $forms_data['locking_details_docs']->getClientMediaType();
			$file_local_path = $forms_data['locking_details_docs']->getStream()->getMetadata('uri');

			$locking_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{							
			$locking_details_docs = '';
		}
					

		if(!empty($forms_data['ventilation_details_docs']->getClientFilename())){

			$file_name = $forms_data['ventilation_details_docs']->getClientFilename();
			$file_size = $forms_data['ventilation_details_docs']->getSize();
			$file_type = $forms_data['ventilation_details_docs']->getClientMediaType();
			$file_local_path = $forms_data['ventilation_details_docs']->getStream()->getMetadata('uri');

			$ventilation_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{			
			$ventilation_details_docs = '';
		}
			
				
			//check if new file is selected	while reply if not save file path from db
		if(!empty($section_form_details[0]['id'])){
			if(empty($room_details_docs)){
				
				$room_details_docs = $section_form_details[0]['room_details_docs'];
			}
			if(empty($storage_details_docs)){
				
				$storage_details_docs = $section_form_details[0]['storage_details_docs'];
			}
			if(empty($locking_details_docs)){
				
				$locking_details_docs = $section_form_details[0]['locking_details_docs'];
			}
			if(empty($ventilation_details_docs)){
				
				$ventilation_details_docs = $section_form_details[0]['ventilation_details_docs'];
			}
			if(empty($condition_details_docs)){
				
				$condition_details_docs = $section_form_details[0]['condition_details_docs'];
			}
			if(empty($constituent_oil_mill_docs)){
				
				$constituent_oil_mill_docs = $section_form_details[0]['constituent_oil_mill_docs'];
			}	
		}
		
		if(!empty($report_final_status)){
			if($report_final_status['status'] == 'referred_back' && !empty($forms_data['io_reply'])){
				
				$htmlencoded_io_reply = htmlentities($forms_data['io_reply'], ENT_QUOTES);
				
				if(!empty($forms_data['ir_comment_ul']->getClientFilename())){				
					
					$file_name = $forms_data['ir_comment_ul']->getClientFilename();
					$file_size = $forms_data['ir_comment_ul']->getSize();
					$file_type = $forms_data['ir_comment_ul']->getClientMediaType();
					$file_local_path = $forms_data['ir_comment_ul']->getStream()->getMetadata('uri');
					
					$ir_comment_ul = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
				}else{ $ir_comment_ul = null; }
				
				$ioReplyEntity = $this->newEntity(array(
					'id'=>$section_form_details[0]['id'],
					'io_reply_once_no'=>$_SESSION['once_card_no'],
					'io_reply_date'=>date('Y-m-d H:i:s'),
					'io_reply'=>$htmlencoded_io_reply,
					'ir_comment_ul'=>$ir_comment_ul,
					'current_level'=>'level_3',
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')
				)); 
				
				$this->save($ioReplyEntity);
			}
		}						
			
		$formSavedEntity = $this->newEntity(array(	
			'id'=>$section_form_details[0]['id'],
			'customer_id'=>$customer_id,
			'user_email_id'=>$_SESSION['username'],
			'user_once_no'=>$_SESSION['once_card_no'],
			'room_site_plan_no'=>$htmlencoded_room_site_plan_no,
			'room_details_docs'=>$room_details_docs,
			'storage_site_plan_no'=>$htmlencoded_storage_site_plan_no,
			'storage_details_docs'=>$storage_details_docs,
			'locking_adequate'=>$locking_adequate,
			'locking_details'=>$htmlencoded_locking_details,
			'locking_details_docs'=>$locking_details_docs,
			'lighted_ventilated'=>$lighted_ventilated,
			'ventilation_details'=>$htmlencoded_ventilation_details,
			'ventilation_details_docs'=>$ventilation_details_docs,
			'conditions_fulfilled'=>$conditions_fulfilled,
			'condition_details'=>$htmlencoded_condition_details,
			'condition_details_docs'=>$condition_details_docs,
			'constituent_oil_mill_docs'=>$constituent_oil_mill_docs,
			'separate_pipe_lines'=>$separate_pipe_lines,
			'form_status'=>'saved',
			'created'=>date('Y-m-d H:i:s'),
			'modified'=>date('Y-m-d H:i:s')
		));	
		if($this->save($formSavedEntity)){ return $message_id; }else{ $message_id = ""; return $message_id; }  		
	}


	public function saveReferredBackComment($customer_id,$report_details,$reffered_back_comment,$rb_comment_ul){
		
		$formSavedEntity = $this->newEntity(array(			
			'customer_id'=>$customer_id,
			'user_email_id'=>$report_details['user_email_id'],
			'user_once_no'=>$report_details['user_once_no'],
			'room_site_plan_no'=>$report_details['room_site_plan_no'],
			'room_details_docs'=>$report_details['room_details_docs'],
			'storage_site_plan_no'=>$report_details['storage_site_plan_no'],
			'storage_details_docs'=>$report_details['storage_details_docs'],
			'locking_adequate'=>$report_details['locking_adequate'],
			'locking_details'=>$report_details['locking_details'],
			'locking_details_docs'=>$report_details['locking_details_docs'],
			'lighted_ventilated'=>$report_details['lighted_ventilated'],
			'ventilation_details'=>$report_details['ventilation_details'],
			'ventilation_details_docs'=>$report_details['ventilation_details_docs'],
			'conditions_fulfilled'=>$report_details['conditions_fulfilled'],
			'condition_details'=>$report_details['condition_details'],
			'condition_details_docs'=>$report_details['condition_details_docs'],
			'constituent_oil_mill_docs'=>$report_details['constituent_oil_mill_docs'],
			'separate_pipe_lines'=>$report_details['separate_pipe_lines'],
			'referred_back_comment'=>$reffered_back_comment,
			'rb_comment_ul'=>$rb_comment_ul,
			'referred_back_date'=>date('Y-m-d H:i:s'),
			'referred_back_by_email'=>$_SESSION['username'],
			'referred_back_by_once'=>$_SESSION['once_card_no'],
			'form_status'=>'referred_back',
			'current_level'=>$_SESSION['current_level'],
			'created'=>date('Y-m-d H:i:s'),
			'modified'=>date('Y-m-d H:i:s')				
		));	
		if($this->save($formSavedEntity)){
			
			return 1;
		}else{
			
			return 0;
		}	
		
	}
			
}

?>