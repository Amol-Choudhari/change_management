<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

class DmiSiteinspectionLaboratoryDetailsTable extends Table{
	
	var $name = "DmiSiteinspectionLaboratoryDetails";
	
	public $validate = array(
		
		'referred_back_comment'=>array(
				'rule'=>array('maxLength',200),				
			),											
		'io_reply'=>array(
				'rule'=>array('maxLength',200),
			),
		'laboratory_equipped'=>array(
				'rule'=>array('maxLength',10),
			),
		'lab_doc_ref_no'=>array(
				'rule'=>array('maxLength',100),
			),
		'laboratory_equipped_docs'=>array(
				'rule'=>array('maxLength',200),
			),
		'extra_load_handled'=>array(
				'rule'=>array('maxLength',10),
			),
		'consent_letter_ref_no'=>array(
				'rule'=>array('maxLength',100),
			),
		'extra_load_docs'=>array(
				'rule'=>array('maxLength',200),
			),				
	);
	
	
	public function sectionFormDetails($customer_id)
	{
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
				
		if($latest_id != null){
			$report_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
			$form_fields_details = $report_fields;
			
		}else{
			$form_fields_details = Array (  'id' => "", 'customer_id' => "", 'io_reply_once_no' => "", 'user_email_id' => "", 'user_once_no' => "", 'referred_back_comment' => "", 'referred_back_date' => "", 'io_reply' => "",
											'io_reply_date' => "", 'form_status' => "", 'laboratory_equipped' => "no", 'lab_doc_ref_no' => "", 'laboratory_equipped_docs' => "", 'extra_load_handled' => "", 'consent_letter_ref_no' => "",
											'extra_load_docs' => "", 'approved_date' => "", 'referred_back_by_email' => "", 'referred_back_by_once' => "", 'current_level' => "", 'lab_shortcomings' => "", 'delete_ro_referred_back' => ""); 
			
		}
		
		$Dmi_customer_laboratory_detail = TableRegistry::getTableLocator()->get('DmiCustomerLaboratoryDetails');
		$customer_laboratory_detail = $Dmi_customer_laboratory_detail->sectionFormDetails($customer_id);
		
		return array($form_fields_details,$customer_laboratory_detail);
			
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

		$htmlencoded_lab_shortcomings = htmlentities($forms_data['lab_shortcomings'], ENT_QUOTES);
				
			//if non bevo application
			if($ca_bevo_applicant == 'no')
			{
				//html encoding post data before saving				
				$htmlencoded_lab_doc_ref_no = htmlentities($forms_data['lab_doc_ref_no'], ENT_QUOTES);	
			
			//if bevo application
			}elseif($ca_bevo_applicant == 'yes')
			{											
				//set null to fields which are not required in BEVO
				$htmlencoded_lab_doc_ref_no = null;
				
			}								

			//checking radio buttons input								
			$post_input_request = $forms_data['laboratory_equipped'];				
			$laboratory_equipped = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
			if($laboratory_equipped == null){ return false;}	
			
			//file uploading							
			if(!empty($forms_data['laboratory_equipped_docs']->getClientFilename())){

				$file_name = $forms_data['laboratory_equipped_docs']->getClientFilename();
				$file_size = $forms_data['laboratory_equipped_docs']->getSize();
				$file_type = $forms_data['laboratory_equipped_docs']->getClientMediaType();
				$file_local_path = $forms_data['laboratory_equipped_docs']->getStream()->getMetadata('uri');					
				
				$laboratory_equipped_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
			}else{										
				$laboratory_equipped_docs = '';
			}
				
				
				
					
			//check if new file is selected	while reply if not save file path from db
			if(!empty($section_form_details[0]['id'])){
				if(empty($laboratory_equipped_docs)){
			
					$laboratory_equipped_docs = $section_form_details[0]['laboratory_equipped_docs'];
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
			'laboratory_equipped'=>$laboratory_equipped,
			'lab_doc_ref_no'=>$htmlencoded_lab_doc_ref_no,
			'laboratory_equipped_docs'=>$laboratory_equipped_docs,
			'lab_shortcomings'=>$htmlencoded_lab_shortcomings,
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
				'laboratory_equipped'=>$report_details['laboratory_equipped'],
				'lab_doc_ref_no'=>$report_details['lab_doc_ref_no'],
				'laboratory_equipped_docs'=>$report_details['laboratory_equipped_docs'],
				'lab_shortcomings'=>$report_details['lab_shortcomings'],
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