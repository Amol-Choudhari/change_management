<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

class DmiPrintingSiteinspectionReportsTable extends Table{
	
	var $name = "DmiPrintingSiteinspectionReports";
	
	public $validate = array(
	
		'customer_id'=>array(
					'rule'=>array('maxLength',50),				
					),							
		'io_reply_once_no'=>array(
					'rule'=>array('maxLength',200),				
					),
		'user_email_id'=>array(
					'rule'=>array('maxLength',200),				
					),
		'user_once_no'=>array(
					'rule'=>array('maxLength',200),				
					),
		'referred_back_comment'=>array(
					'rule'=>array('maxLength',200),				
					),
		'io_reply'=>array(
					'rule'=>array('maxLength',200),				
					),
		'form_status'=>array(
					'rule'=>array('maxLength',50),				
					),
		'is_assessed_for'=>array(
					'rule'=>array('maxLength',10),				
					),
		'earlier_permitted'=>array(
					'rule'=>array('maxLength',10),				
					),
		'machines_requisite'=>array(
					'rule'=>array('maxLength',10),				
					),
		'machines_requisite_docs'=>array(
					'rule'=>array('maxLength',200),				
					),
		'in_house_storage_facility'=>array(
					'rule'=>array('maxLength',10),				
					),
		'account_maintained'=>array(
					'rule'=>array('maxLength',10),				
					),
		'fabrication_facility'=>array(
					'rule'=>array('maxLength',10),				
					),
		'fabrication_facility_docs'=>array(
					'rule'=>array('maxLength',200),				
					),
		'declaration_given'=>array(
					'rule'=>array('maxLength',10),				
					),
		'is_press_sponsored'=>array(
					'rule'=>array('maxLength',10),				
					),				
		'press_sponsored_docs'=>array(
					'rule'=>array('maxLength',200),				
					),
		'referred_back_by_email'=>array(
					'rule'=>array('maxLength',200),				
					),	
		'referred_back_by_once'=>array(
					'rule'=>array('maxLength',25),				
					),	
		'current_level'=>array(
					'rule'=>array('maxLength',30),				
					),
		'ink_declaration_docs'=>array(
					'rule'=>array('maxLength',200),				
					),
		'is_press_authorised'=>array(
					'rule'=>array('maxLength',10),				
					),			
	);
			
	// Fetch form section all details
	public function sectionFormDetails($customer_id)
	{
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
				
		if($latest_id != null){
			$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
			$form_fields_details = $form_fields;
			
		}else{
			
			$form_fields_details = Array ( 'id' =>'', 'customer_id' =>'', 'io_reply_once_no' =>'', 'user_email_id' =>'', 'user_once_no' =>'', 'referred_back_comment' =>'', 'referred_back_date' =>'', 'io_reply' =>'', 
											'io_reply_date' =>'', 'form_status' =>'', 'is_assessed_for' =>'', 'assessed_for_pan_no' =>'', 'assessed_for_tax_no' =>'', 'earlier_permitted' =>'', 'reason_of_withdrawal' =>'',
											'machines_requisite' =>'no', 'machines_requisite_docs' =>'', 'in_house_storage_facility' =>'no', 'account_maintained' =>'no', 'fabrication_facility' =>'no', 'fabrication_facility_docs' =>'', 
											'declaration_given' =>'no', 'is_press_sponsored' =>'no', 'press_sponsored_docs' =>'', 'any_other_point' =>'', 'recommendations' =>'', 'approved_date' =>'', 'referred_back_by_email' =>'',
											'referred_back_by_once' =>'', 'current_level' =>'', 'machines_requisite_details' =>'', 'ink_declaration_docs' =>'', 'is_press_authorised' =>'no', 'delete_mo_comment' =>'', 
											'delete_ro_referred_back' =>'', 'delete_ro_reply' =>'', 'delete_customer_reply' =>''); 
			
		}
		
		$Dmi_printing_premises_profile = TableRegistry::getTableLocator()->get('DmiPrintingPremisesProfiles');
		$firm_premises_details = $Dmi_printing_premises_profile->sectionFormDetails($customer_id);
		
		$Dmi_printing_unit_details = TableRegistry::getTableLocator()->get('DmiPrintingUnitDetails');
		$firm_unit_details = $Dmi_printing_unit_details->sectionFormDetails($customer_id);
		
		return array($form_fields_details,$firm_premises_details,$firm_unit_details);			
	}

	public function saveFormDetails($customer_id,$forms_data){

		$CustomersController = new CustomersController;
		
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'inspection_report');
		
		$Dmi_siteinspection_final_report = TableRegistry::getTableLocator()->get($final_submit_table);
		$report_final_status = $Dmi_siteinspection_final_report->siteinspectionFinalReportStatus($customer_id);
		
		
		$id = ''; $message_id = 1;
		$current_level = $_SESSION['current_level'];					

		$section_form_details = $this->sectionFormDetails($customer_id);
		if(!empty($section_form_details[0]['id'])){	
		
			$message_id = 2;																	
			$id = $section_form_details[0]['id'];
			
			if(isset($forms_data['io_reply'])){$io_reply = $forms_data['io_reply']; }else{ $io_reply = null; }
						if(isset($report_final_status['status'])){$reportFinalStatusValue = $report_final_status['status']; }else{ $reportFinalStatusValue = null; }
						
			if($current_level == 'level_2' && empty($io_reply) && $reportFinalStatusValue == 'referred_back'){
				
				return 4;  //error "comment required"
			}
			
			if($current_level == 'level_2' && !empty($io_reply) && $reportFinalStatusValue == 'referred_back'){
				
				$message_id = 3;							
			}
		
		}
		
							
		$htmlencoded_reason_of_withdrawal = htmlentities($forms_data['reason_of_withdrawal'], ENT_QUOTES);
		$htmlencoded_any_other_point = htmlentities($forms_data['any_other_point'], ENT_QUOTES);
		$htmlencoded_recommendations = htmlentities($forms_data['recommendations'], ENT_QUOTES);
		
		/*Code start By Pravin 18/3/2017*/
		$htmlencoded_machines_requisite_details = htmlentities($forms_data['machines_requisite_details'], ENT_QUOTES);
		/*Code end By Pravin 18/3/2017*/
		
		
		//checking radio buttons input					
		$post_input_request = $forms_data['machines_requisite'];				
		$machines_requisite = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($machines_requisite == null){ return false;}
		
		
		$post_input_request = $forms_data['in_house_storage_facility'];				
		$in_house_storage_facility = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($in_house_storage_facility == null){ return false;}
		
		
		$post_input_request = $forms_data['account_maintained'];				
		$account_maintained = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($account_maintained == null){ return false;}
		
		
		$post_input_request = $forms_data['fabrication_facility'];				
		$fabrication_facility = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($fabrication_facility == null){ return false;}
		
		
		$post_input_request = $forms_data['declaration_given'];				
		$declaration_given = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($declaration_given == null){ return false;}
		
		
		$post_input_request = $forms_data['is_press_sponsored'];				
		$is_press_sponsored = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($is_press_sponsored == null){ return false;}
		
		/*Code start by Pravin 18/3/2017*/
	
		$post_input_request = $forms_data['is_press_authorised'];					
		$is_press_authorised = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($is_press_authorised == null){ return false;}
	
		/*Code End by Pravin 18/3/2017*/
	
		//file uploading
		if(!empty($forms_data['machines_requisite_docs']->getClientFilename())){			
			
			$file_name = $forms_data['machines_requisite_docs']->getClientFilename();
			$file_size = $forms_data['machines_requisite_docs']->getSize();
			$file_type = $forms_data['machines_requisite_docs']->getClientMediaType();
			$file_local_path = $forms_data['machines_requisite_docs']->getStream()->getMetadata('uri');
		
			$machines_requisite_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{ $machines_requisite_docs = ''; }
				
		if(!empty($forms_data['fabrication_facility_docs']->getClientFilename())){			
			
			$file_name = $forms_data['fabrication_facility_docs']->getClientFilename();
			$file_size = $forms_data['fabrication_facility_docs']->getSize();
			$file_type = $forms_data['fabrication_facility_docs']->getClientMediaType();
			$file_local_path = $forms_data['fabrication_facility_docs']->getStream()->getMetadata('uri');		
		
			$fabrication_facility_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{ $fabrication_facility_docs = ''; }
		
		
		/*code start by pravin 18/3/2017*/		
		if(!empty($forms_data['ink_declaration_docs']->getClientFilename())){			
			
			$file_name = $forms_data['ink_declaration_docs']->getClientFilename();
			$file_size = $forms_data['ink_declaration_docs']->getSize();
			$file_type = $forms_data['ink_declaration_docs']->getClientMediaType();
			$file_local_path = $forms_data['ink_declaration_docs']->getStream()->getMetadata('uri');
			
			$ink_declaration_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{ $ink_declaration_docs = ''; }
		
		if(!empty($forms_data['press_sponsored_docs']->getClientFilename())){			
			
			$file_name = $forms_data['press_sponsored_docs']->getClientFilename();
			$file_size = $forms_data['press_sponsored_docs']->getSize();
			$file_type = $forms_data['press_sponsored_docs']->getClientMediaType();
			$file_local_path = $forms_data['press_sponsored_docs']->getStream()->getMetadata('uri');
		
			$press_sponsored_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{ $press_sponsored_docs = ''; }
		
		
		if(!empty($section_form_details[0]['id'])){
			if(empty($machines_requisite_docs)){
		
				$machines_requisite_docs = $section_form_details[0]['machines_requisite_docs'];
			}
			if(empty($fabrication_facility_docs)){
				
				$fabrication_facility_docs = $section_form_details[0]['fabrication_facility_docs'];
			}
			if(empty($press_sponsored_docs)){
				
				$press_sponsored_docs = $section_form_details[0]['press_sponsored_docs'];
			}
			if(empty($ink_declaration_docs)){
				
				$ink_declaration_docs = $section_form_details[0]['ink_declaration_docs'];
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
							'id'=>$id,
							'io_reply_once_no'=>$_SESSION['once_card_no'],
							'io_reply_date'=>date('Y-m-d H:i:s'),
							'io_reply'=>$htmlencoded_io_reply,
							'ir_comment_ul'=>$ir_comment_ul,
							'current_level'=>'level_3',								
							));
							
							$this->save($ioReplyEntity);
						}
					}	
		
			$formSavedEntity = $this->newEntity(array(						
	
				'id'=>$id,
				'customer_id'=>$customer_id,
				'user_email_id'=>$_SESSION['username'],
				'user_once_no'=>$_SESSION['once_card_no'],	
				'reason_of_withdrawal'=>$htmlencoded_reason_of_withdrawal,
				'machines_requisite'=>$machines_requisite,							
				'machines_requisite_details'=>$htmlencoded_machines_requisite_details,
				'machines_requisite_docs'=>$machines_requisite_docs,
				'in_house_storage_facility'=>$in_house_storage_facility,
				'account_maintained'=>$account_maintained,
				'fabrication_facility'=>$fabrication_facility,
				'fabrication_facility_docs'=>$fabrication_facility_docs,
				'declaration_given'=>$declaration_given,
				'ink_declaration_docs'=>$ink_declaration_docs,
				'is_press_sponsored'=>$is_press_sponsored,
				'is_press_authorised'=>$is_press_authorised,
				'press_sponsored_docs'=>$press_sponsored_docs,
				'any_other_point'=>$htmlencoded_any_other_point,
				'recommendations'=>$htmlencoded_recommendations,								
				'form_status'=>'saved'
			
			));
			
			if($this->save($formSavedEntity)){ return $message_id; }else{ $message_id = ""; return $message_id; }  		
	}

	public function saveReferredBackComment($customer_id,$report_details,$reffered_back_comment,$rb_comment_ul){
			
			
			
			$formSavedEntity = $this->newEntity(array(	
			
				'customer_id'=>$customer_id,
				'user_email_id'=>$_SESSION['username'],
				'user_once_no'=>$_SESSION['once_card_no'],	
				'reason_of_withdrawal'=>$report_details['reason_of_withdrawal'],
				'machines_requisite'=>$report_details['machines_requisite'],							
				'machines_requisite_details'=>$report_details['machines_requisite_details'],
				'machines_requisite_docs'=>$report_details['machines_requisite_docs'],
				'in_house_storage_facility'=>$report_details['in_house_storage_facility'],
				'account_maintained'=>$report_details['account_maintained'],
				'fabrication_facility'=>$report_details['fabrication_facility'],
				'fabrication_facility_docs'=>$report_details['fabrication_facility_docs'],
				'declaration_given'=>$report_details['declaration_given'],
				'ink_declaration_docs'=>$report_details['ink_declaration_docs'],
				'is_press_sponsored'=>$report_details['is_press_sponsored'],
				'is_press_authorised'=>$report_details['is_press_authorised'],
				'press_sponsored_docs'=>$report_details['press_sponsored_docs'],
				'any_other_point'=>$report_details['any_other_point'],
				'recommendations'=>$report_details['recommendations'],
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