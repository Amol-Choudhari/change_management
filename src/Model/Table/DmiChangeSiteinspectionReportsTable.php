<?php

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;
	
class DmiChangeSiteinspectionReportsTable extends Table{
	
	var $name = "DmiChangeSiteinspectionReports";
	
	public function sectionFormDetails($customer_id)
	{
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
				
		if($latest_id != null){
			$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
			$form_fields_details = $form_fields;
			
		}else{
			
			$form_fields_details = Array ( 'id' =>'', 'customer_id' =>'', 'user_email_id' =>'', 'referred_back_comment' =>'', 'referred_back_date' =>'', 'io_reply' =>'', 
											'io_reply_date' =>'', 'form_status' =>'', 'remark_on_report' =>'', 'report_docs' =>'', 'approved_date' =>'', 'referred_back_by_email' =>'',
											'current_level' =>'','ir_comment_ul' =>'', 'rb_comment_ul' =>'','created'=>'','modified'=>''); 
			
		}
		
		return array($form_fields_details);			
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
		
							
		$htmlencoded_firm_renewal_remark = htmlentities($forms_data['remark_on_report'], ENT_QUOTES);		
	
		/*Code End by Pravin 18/3/2017*/
	
		//file uploading
		if(!empty($forms_data['report_docs']->getClientFilename())){			
			
			$file_name = $forms_data['report_docs']->getClientFilename();
			$file_size = $forms_data['report_docs']->getSize();
			$file_type = $forms_data['report_docs']->getClientMediaType();
			$file_local_path = $forms_data['report_docs']->getStream()->getMetadata('uri');
		
			$report_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{ $report_docs = ''; }			
		
		if(!empty($section_form_details[0]['id'])){
			if(empty($report_docs)){		
				$report_docs = $section_form_details[0]['report_docs'];
			}			
		}
		
		if(empty($section_form_details[0]['created'])){  $created = date('Y-m-d H:i:s'); }
		else{ $created = $CustomersController->Customfunctions->changeDateFormat($section_form_details[0]['created']); }
		
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
					'current_level'=>$_SESSION['current_level'],	
					'modified'=>date('Y-m-d H:i:s')		
				));
				
				$this->save($ioReplyEntity);
			}
		}	
		
			$formSavedEntity = $this->newEntity(array(		
				'id'=>$id,
				'customer_id'=>$customer_id,
				'user_email_id'=>$_SESSION['username'],				
				'remark_on_report'=>$htmlencoded_firm_renewal_remark,
				'report_docs'=>$report_docs,				
				'form_status'=>'saved',
				'current_level'=>$_SESSION['current_level'],
				'created'=>$created,
				'modified'=>date('Y-m-d H:i:s')	
			));
			
			if($this->save($formSavedEntity)){ return $message_id; }else{ $message_id = ""; return $message_id; }  		
	}

	public function saveReferredBackComment($customer_id,$report_details,$reffered_back_comment,$rb_comment_ul){
			
		$formSavedEntity = $this->newEntity(array(	
		
			'customer_id'=>$customer_id,
			'user_email_id'=>$_SESSION['username'],				
			'remark_on_report'=>$report_details['remark_on_report'],
			'report_docs'=>$report_details['report_docs'],
			'referred_back_comment'=>$reffered_back_comment,
			'rb_comment_ul'=>$rb_comment_ul,
			'referred_back_date'=>date('Y-m-d H:i:s'),
			'referred_back_by_email'=>$_SESSION['username'],
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