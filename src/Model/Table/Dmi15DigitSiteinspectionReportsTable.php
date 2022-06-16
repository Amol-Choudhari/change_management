<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
class Dmi15DigitSiteinspectionReportsTable extends Table{
	
	var $name = "Dmi15DigitSiteinspectionReports";
	
	public function sectionFormDetails($customer_id)
	{
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
				
		if($latest_id != null){
			$report_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
			$form_fields_details = $report_fields;
			
		}else{
			$form_fields_details = Array (  'id' =>"", 'customer_id' =>"", 'io_reply_once_no' =>"", 'user_email_id' =>"", 'user_once_no' =>"", 'referred_back_comment' =>"", 'referred_back_date' =>"", 'io_reply' =>"", 
											 'io_reply_date' =>"", 'form_status' =>"", 'recommendations' =>"", 'approved_date' =>"", 'referred_back_by_email' =>"", 'referred_back_by_once' =>"",
											 'current_level' =>"", 'delete_mo_comment' =>"", 'delete_ro_referred_back' =>"", 'delete_ro_reply' =>"", 'delete_customer_reply' =>"", 'rb_comment_ul' =>"", 'ir_comment_ul' =>"",
											 'is_automatic_system' =>"", 'automatic_system_docs' =>"", 'is_separate_records' =>"", 'separate_records_docs' =>"", 'is_copy_of_orders' =>"", 'copy_of_orders_docs' =>"",
											 'is_copy_of_printing' =>"", 'copy_of_printing_docs' =>"", 'reg_of_empty_container' =>"", 'empty_container_docs' =>"", 'issue_of_empty_container' =>"", 'issue_of_empty_container_docs' =>"",
											 'reg_of_raw_materials' =>"",'reg_daily_production' =>"",'reg_daily_account_qty' =>"",'reg_damaged_container' =>"",'reg_showing_daily_stock' =>"",'reg_sale_invoice' =>"",'reg_sale_invoice_docs' =>"",
											 'graded_min_quantity' =>"",'graded_min_qty_docs' =>"",'grade_100_per_prod'=>""); 
			
		}
		
		$Dmi_all_tanks_detail = TableRegistry::getTableLocator()->get('DmiAllTanksDetails');
		
		return array($form_fields_details);			
	}
	
	
	public function saveFormDetails($customer_id,$forms_data){
		
		$CustomersController = new CustomersController;			
		
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
		
		//html encoding post data before saving
		$recommendations = htmlentities($forms_data['recommendations'], ENT_QUOTES);
		
		//checking radio buttons input
		$post_input_request = $forms_data['is_automatic_system'];				
		$is_automatic_system = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($is_automatic_system == null){ return false;}
		
		$post_input_request = $forms_data['is_separate_records'];				
		$is_separate_records = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($is_separate_records == null){ return false;}
		
		$post_input_request = $forms_data['is_copy_of_orders'];				
		$is_copy_of_orders = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($is_copy_of_orders == null){ return false;}
		
		$post_input_request = $forms_data['is_copy_of_printing'];				
		$is_copy_of_printing = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($is_copy_of_printing == null){ return false;}
		
		$post_input_request = $forms_data['reg_of_empty_container'];				
		$reg_of_empty_container = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($reg_of_empty_container == null){ return false;}
		
		$post_input_request = $forms_data['issue_of_empty_container'];				
		$issue_of_empty_container = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($issue_of_empty_container == null){ return false;}
		
		$post_input_request = $forms_data['reg_of_raw_materials'];				
		$reg_of_raw_materials = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($reg_of_raw_materials == null){ return false;}
		
		$post_input_request = $forms_data['reg_daily_production'];				
		$reg_daily_production = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($reg_daily_production == null){ return false;}
		
		$post_input_request = $forms_data['reg_daily_account_qty'];				
		$reg_daily_account_qty = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($reg_daily_account_qty == null){ return false;}
		
		$post_input_request = $forms_data['reg_damaged_container'];				
		$reg_damaged_container = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($reg_damaged_container == null){ return false;}
		
		$post_input_request = $forms_data['reg_showing_daily_stock'];				
		$reg_showing_daily_stock = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($reg_showing_daily_stock == null){ return false;}
		
		$post_input_request = $forms_data['reg_sale_invoice'];				
		$reg_sale_invoice = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($reg_sale_invoice == null){ return false;}
		
		$post_input_request = $forms_data['graded_min_quantity'];				
		$graded_min_quantity = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($graded_min_quantity == null){ return false;}
		
		$post_input_request = $forms_data['grade_100_per_prod'];				
		$grade_100_per_prod = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($grade_100_per_prod == null){ return false;}
		
		
		//file uploading
		if(!empty($forms_data['automatic_system_docs']->getClientFilename())){

			$file_name = $forms_data['automatic_system_docs']->getClientFilename();
			$file_size = $forms_data['automatic_system_docs']->getSize();
			$file_type = $forms_data['automatic_system_docs']->getClientMediaType();
			$file_local_path = $forms_data['automatic_system_docs']->getStream()->getMetadata('uri');

			$automatic_system_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{	
			$automatic_system_docs = '';
		}
		
		if(!empty($forms_data['separate_records_docs']->getClientFilename())){

			$file_name = $forms_data['separate_records_docs']->getClientFilename();
			$file_size = $forms_data['separate_records_docs']->getSize();
			$file_type = $forms_data['separate_records_docs']->getClientMediaType();
			$file_local_path = $forms_data['separate_records_docs']->getStream()->getMetadata('uri');

			$separate_records_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{	
			$separate_records_docs = '';
		}
		
		if(!empty($forms_data['copy_of_orders_docs']->getClientFilename())){

			$file_name = $forms_data['copy_of_orders_docs']->getClientFilename();
			$file_size = $forms_data['copy_of_orders_docs']->getSize();
			$file_type = $forms_data['copy_of_orders_docs']->getClientMediaType();
			$file_local_path = $forms_data['copy_of_orders_docs']->getStream()->getMetadata('uri');

			$copy_of_orders_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{	
			$copy_of_orders_docs = '';
		}
		
		
		if(!empty($forms_data['copy_of_printing_docs']->getClientFilename())){

			$file_name = $forms_data['copy_of_printing_docs']->getClientFilename();
			$file_size = $forms_data['copy_of_printing_docs']->getSize();
			$file_type = $forms_data['copy_of_printing_docs']->getClientMediaType();
			$file_local_path = $forms_data['copy_of_printing_docs']->getStream()->getMetadata('uri');

			$copy_of_printing_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{	
			$copy_of_printing_docs = '';
		}
		
		if(!empty($forms_data['empty_container_docs']->getClientFilename())){

			$file_name = $forms_data['empty_container_docs']->getClientFilename();
			$file_size = $forms_data['empty_container_docs']->getSize();
			$file_type = $forms_data['empty_container_docs']->getClientMediaType();
			$file_local_path = $forms_data['empty_container_docs']->getStream()->getMetadata('uri');

			$empty_container_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{	
			$empty_container_docs = '';
		}
		
		
		if(!empty($forms_data['issue_of_empty_container_docs']->getClientFilename())){

			$file_name = $forms_data['issue_of_empty_container_docs']->getClientFilename();
			$file_size = $forms_data['issue_of_empty_container_docs']->getSize();
			$file_type = $forms_data['issue_of_empty_container_docs']->getClientMediaType();
			$file_local_path = $forms_data['issue_of_empty_container_docs']->getStream()->getMetadata('uri');

			$issue_of_empty_container_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{	
			$issue_of_empty_container_docs = '';
		}
		
		
		if(!empty($forms_data['reg_sale_invoice_docs']->getClientFilename())){

			$file_name = $forms_data['reg_sale_invoice_docs']->getClientFilename();
			$file_size = $forms_data['reg_sale_invoice_docs']->getSize();
			$file_type = $forms_data['reg_sale_invoice_docs']->getClientMediaType();
			$file_local_path = $forms_data['reg_sale_invoice_docs']->getStream()->getMetadata('uri');

			$reg_sale_invoice_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{	
			$reg_sale_invoice_docs = '';
		}
		
		if(!empty($forms_data['graded_min_qty_docs']->getClientFilename())){

			$file_name = $forms_data['graded_min_qty_docs']->getClientFilename();
			$file_size = $forms_data['graded_min_qty_docs']->getSize();
			$file_type = $forms_data['graded_min_qty_docs']->getClientMediaType();
			$file_local_path = $forms_data['graded_min_qty_docs']->getStream()->getMetadata('uri');

			$graded_min_qty_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
		}else{	
			$graded_min_qty_docs = '';
		}


		
			//check if new file is selected	while reply if not save file path from db
		if(!empty($section_form_details[0]['id'])){
			if(empty($automatic_system_docs)){
				
				$automatic_system_docs = $section_form_details[0]['automatic_system_docs'];
			}
			if(empty($separate_records_docs)){
				
				$separate_records_docs = $section_form_details[0]['separate_records_docs'];
			}
			if(empty($copy_of_orders_docs)){
				
				$copy_of_orders_docs = $section_form_details[0]['copy_of_orders_docs'];
			}
			if(empty($copy_of_printing_docs)){
				
				$copy_of_printing_docs = $section_form_details[0]['copy_of_printing_docs'];
			}
			if(empty($empty_container_docs)){
				
				$empty_container_docs = $section_form_details[0]['empty_container_docs'];
			}
			if(empty($issue_of_empty_container_docs)){
				
				$issue_of_empty_container_docs = $section_form_details[0]['issue_of_empty_container_docs'];
			}
			if(empty($reg_sale_invoice_docs)){
				
				$reg_sale_invoice_docs = $section_form_details[0]['reg_sale_invoice_docs'];
			}
			if(empty($graded_min_qty_docs)){
				
				$graded_min_qty_docs = $section_form_details[0]['graded_min_qty_docs'];
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
			'is_automatic_system'=>$is_automatic_system,
			'automatic_system_docs'=>$automatic_system_docs,
			'is_separate_records'=>$is_separate_records,
			'separate_records_docs'=>$separate_records_docs,
			'is_copy_of_orders'=>$is_copy_of_orders,
			'copy_of_orders_docs'=>$copy_of_orders_docs,
			'is_copy_of_printing'=>$is_copy_of_printing,
			'copy_of_printing_docs'=>$copy_of_printing_docs,
			'reg_of_empty_container'=>$reg_of_empty_container,
			'empty_container_docs'=>$empty_container_docs,
			'issue_of_empty_container'=>$issue_of_empty_container,
			'issue_of_empty_container_docs'=>$issue_of_empty_container_docs,
			'reg_of_raw_materials'=>$reg_of_raw_materials,
			'reg_daily_production'=>$reg_daily_production,
			'reg_daily_account_qty'=>$reg_daily_account_qty,
			'reg_damaged_container'=>$reg_damaged_container,
			'reg_showing_daily_stock'=>$reg_showing_daily_stock,
			'reg_sale_invoice'=>$reg_sale_invoice,
			'reg_sale_invoice_docs'=>$reg_sale_invoice_docs,
			'graded_min_quantity'=>$graded_min_quantity,
			'graded_min_qty_docs'=>$graded_min_qty_docs,
			'grade_100_per_prod'=>$grade_100_per_prod,
			'recommendations'=>$recommendations,
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
			'is_automatic_system'=>$report_details['is_automatic_system'],
			'automatic_system_docs'=>$report_details['automatic_system_docs'],
			'is_separate_records'=>$report_details['is_separate_records'],
			'separate_records_docs'=>$report_details['separate_records_docs'],
			'is_copy_of_orders'=>$report_details['is_copy_of_orders'],
			'copy_of_orders_docs'=>$report_details['copy_of_orders_docs'],
			'is_copy_of_printing'=>$report_details['is_copy_of_printing'],
			'copy_of_printing_docs'=>$report_details['copy_of_printing_docs'],
			'reg_of_empty_container'=>$report_details['reg_of_empty_container'],
			'empty_container_docs'=>$report_details['empty_container_docs'],
			'issue_of_empty_container'=>$report_details['issue_of_empty_container'],
			'issue_of_empty_container_docs'=>$report_details['issue_of_empty_container_docs'],
			'reg_of_raw_materials'=>$report_details['reg_of_raw_materials'],
			'reg_daily_production'=>$report_details['reg_daily_production'],
			'reg_daily_account_qty'=>$report_details['reg_daily_account_qty'],
			'reg_damaged_container'=>$report_details['reg_damaged_container'],
			'reg_showing_daily_stock'=>$report_details['reg_showing_daily_stock'],
			'reg_sale_invoice'=>$report_details['reg_sale_invoice'],
			'reg_sale_invoice_docs'=>$report_details['reg_sale_invoice_docs'],
			'graded_min_quantity'=>$report_details['graded_min_quantity'],
			'graded_min_qty_docs'=>$report_details['graded_min_qty_docs'],
			'grade_100_per_prod'=>$report_details['grade_100_per_prod'],
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