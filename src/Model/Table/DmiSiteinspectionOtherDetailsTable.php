<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

class DmiSiteinspectionOtherDetailsTable extends Table{
	
	var $name = "DmiSiteinspectionOtherDetails";
	
	public $validate = array(
		
		'referred_back_comment'=>array(
				'rule'=>array('maxLength',200),				
			),											
		'io_reply'=>array(
				'rule'=>array('maxLength',200),
			),
		'commodity_quantity'=>array(
				'rule'=>array('maxLength',50),
			),
		'packing_size'=>array(
				'rule'=>array('maxLength',50),
			),
		'analysis_facility_details'=>array(
				'rule'=>array('maxLength',200),
			),
		'own_machinery'=>array(
				'rule'=>array('maxLength',10),
			),
		'processing_done_by'=>array(
				'rule'=>array('maxLength',200),
			),
		'machinery_processing_docs'=>array(
				'rule'=>array('maxLength',200),
			),
		'tbl_stamp_paper_docs'=>array(
				'rule'=>array('maxLength',200),
			),
		'tbl_name'=>array(
				'rule'=>array('maxLength',200),
			),
		'tbl_in_order'=>array(
				'rule'=>array('maxLength',10),
			),
		'tbl_order_docs'=>array(
				'rule'=>array('maxLength',200),
			),
		'bevo_machinery_details_docs'=>array(
				'rule'=>array('maxLength',200),
			),
		'fat_spread_facilitities'=>array(
				'rule'=>array('maxLength',10),
			),
		'bevo_quantity_per_month'=>array(
				'rule'=>array('maxLength',100),
			),
		'constituent_oil_suppliers_docs'=>array(
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
			$form_fields_details = Array (  'id' =>"", 'customer_id' =>"", 'io_reply_once_no' =>"", 'user_email_id' =>"", 'user_once_no' =>"", 'referred_back_comment' =>"", 'referred_back_date' =>"", 'io_reply' =>"", 
											'io_reply_date' =>"", 'form_status' =>"", 'commodity_quantity' =>"", 'packing_size' =>"", 'analysis_facility_details' =>"", 'own_machinery' =>"no", 'processing_done_by' =>"", 
											'machinery_processing_docs' =>"", 'tbl_stamp_paper_docs' =>"", 'tbl_name' =>"", 'tbl_in_order' =>"", 'tbl_order_docs' =>"", 'approved_date' =>"", 'referred_back_by_email' =>"",
											'referred_back_by_once' =>"", 'current_level' =>"", 'other_points' =>"", 'recommendations' =>"", 'bevo_machinery_details_docs' =>"", 'fat_spread_facilitities' =>"no",
											'bevo_quantity_per_month' =>"", 'constituent_oil_suppliers_docs' =>"", 'graded_bevo_marketed_places' =>"", 'delete_ro_referred_back' =>""); 
			
		}
		
		$Dmi_customer_laboratory_detail = TableRegistry::getTableLocator()->get('DmiCustomerLaboratoryDetails');
		$customer_laboratory_detail = $Dmi_customer_laboratory_detail->sectionFormDetails($customer_id);
		
		$Dmi_all_tbls_detail = TableRegistry::getTableLocator()->get('DmiAllTblsDetails');
		$added_tbls_details = $Dmi_all_tbls_detail->tblsDetails();
		
		$CustomersController = new CustomersController;
		$laboratory_types = $CustomersController->Mastertablecontent->allLaboratoryType();
		
		$Dmi_all_constituent_oils_detail = TableRegistry::getTableLocator()->get('DmiAllConstituentOilsDetails');
		$added_const_oils_details = $Dmi_all_constituent_oils_detail->constituentOilsMillDetails('report');
		
		return array($form_fields_details,$customer_laboratory_detail,$added_tbls_details,$laboratory_types,$added_const_oils_details);
			
	}
	
	public function saveFormDetails($customer_id,$forms_data){

		$CustomersController = new CustomersController;
							
		$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
		$bevoOrFatSpread = $CustomersController->Customfunctions->checkFatSpreadOrBevo($customer_id);
		
		$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
		$firm_details = $Dmi_firm->firmDetails($customer_id);
		$firm_sub_commodity = explode(',',$firm_details['sub_commodity']);	
		
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
			$htmlencoded_commodity_quantity = htmlentities($forms_data['commodity_quantity'], ENT_QUOTES);
			$htmlencoded_processing_done_by = htmlentities($forms_data['processing_done_by'], ENT_QUOTES);
			
			//these null is set on 31-05-2017 
			//these fields are not in use currently
			$htmlencoded_packing_size = null;
			$htmlencoded_analysis_facility_details = null;
			$htmlencoded_tbl_name = null;
			$tbl_in_order = null;
											
			//checking radio buttons input								
			$post_input_request = $forms_data['own_machinery'];				
			$own_machinery = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
			if($own_machinery == null){ return false;}
			
			
			//file uploading							
			if(!empty($forms_data['machinery_processing_docs']->getClientFilename())){

				$file_name = $forms_data['machinery_processing_docs']->getClientFilename();
				$file_size = $forms_data['machinery_processing_docs']->getSize();
				$file_type = $forms_data['machinery_processing_docs']->getClientMediaType();
				$file_local_path = $forms_data['machinery_processing_docs']->getStream()->getMetadata('uri');
		
				$machinery_processing_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{											
				$machinery_processing_docs = '';
			}

						
			//set null to fields which are not required in BEVO
			$htmlencoded_bevo_quantity_per_month = null;
			$htmlencoded_bevo_graded_bevo_marketed_places = null;
			$fat_spread_facilitities = null;
			$bevo_machinery_details_docs = null;
			$constituent_oil_suppliers_docs = null;
					
		}
		elseif($ca_bevo_applicant == 'yes')
		{
			//html encoding post data before saving							
			$htmlencoded_bevo_quantity_per_month = htmlentities($forms_data['bevo_quantity_per_month'], ENT_QUOTES);
			$htmlencoded_bevo_graded_bevo_marketed_places = htmlentities($forms_data['graded_bevo_marketed_places'], ENT_QUOTES);


			//Hide and show the "Machinery details" and "Minimum Infrastructure/Facilities" box on selected sub commodity wise Done by pravin 10-01-2018
			if($bevoOrFatSpread=='fat_spread'){ //$bevoOrFatSpread added on 05-09-2022 for Fat Spread updates after UAT
			
				$post_input_request = $forms_data['fat_spread_facilitities'];				
				$fat_spread_facilitities = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($fat_spread_facilitities == null){ return false;}
				
			}else{$fat_spread_facilitities = null;}
			
			
			//file uploading

			//Hide and show the "Machinery details" and "Minimum Infrastructure/Facilities" box on selected sub commodity wise Done by pravin 10-01-2018
			if($bevoOrFatSpread=='bevo'){ //$bevoOrFatSpread added on 05-09-2022 for Fat Spread updates after UAT
				
				if(!empty($forms_data['bevo_machinery_details_docs']->getClientFilename())){

					$file_name = $forms_data['bevo_machinery_details_docs']->getClientFilename();
					$file_size = $forms_data['bevo_machinery_details_docs']->getSize();
					$file_type = $forms_data['bevo_machinery_details_docs']->getClientMediaType();
					$file_local_path = $forms_data['bevo_machinery_details_docs']->getStream()->getMetadata('uri');
			
					$bevo_machinery_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				}else{											
					$bevo_machinery_details_docs = '';
				}
			}else{$bevo_machinery_details_docs = '';}
			
			
			if(!empty($forms_data['constituent_oil_suppliers_docs']->getClientFilename())){

				$file_name = $forms_data['constituent_oil_suppliers_docs']->getClientFilename();
				$file_size = $forms_data['constituent_oil_suppliers_docs']->getSize();
				$file_type = $forms_data['constituent_oil_suppliers_docs']->getClientMediaType();
				$file_local_path = $forms_data['constituent_oil_suppliers_docs']->getStream()->getMetadata('uri');
		
				$constituent_oil_suppliers_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{											
				$constituent_oil_suppliers_docs = '';
			}
			
			
			//set null to fields which are not required in BEVO
			$htmlencoded_commodity_quantity = null;
			$htmlencoded_packing_size = null;
			$htmlencoded_analysis_facility_details = null;
			$htmlencoded_processing_done_by = null;
			$htmlencoded_tbl_name = null;
			$own_machinery = null;
			$tbl_in_order = null;
			$machinery_processing_docs = null;
			$tbl_stamp_paper_docs = null;
			$tbl_order_docs = null;					
		}
				
		//other common fields for both BEVo and Non bevo				
		$htmlencoded_other_points = htmlentities($forms_data['other_points'], ENT_QUOTES);
		$htmlencoded_recommendations = htmlentities($forms_data['recommendations'], ENT_QUOTES);	
					
		//check if new file is selected	while reply if not save file path from db
		if(!empty($section_form_details[0]['id'])){
			if(empty($machinery_processing_docs)){
		
				$machinery_processing_docs = $section_form_details[0]['machinery_processing_docs'];
			}
						
			if(empty($bevo_machinery_details_docs)){
				
				$bevo_machinery_details_docs = $section_form_details[0]['bevo_machinery_details_docs'];
			}
			if(empty($constituent_oil_suppliers_docs)){
				
				$constituent_oil_suppliers_docs = $section_form_details[0]['constituent_oil_suppliers_docs'];
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
			
			'commodity_quantity'=>$htmlencoded_commodity_quantity,						
			'own_machinery'=>$own_machinery,
			'processing_done_by'=>$htmlencoded_processing_done_by,
			'machinery_processing_docs'=>$machinery_processing_docs,						
			'graded_bevo_marketed_places'=>$htmlencoded_bevo_graded_bevo_marketed_places,
			'fat_spread_facilitities'=>$fat_spread_facilitities,
			'bevo_quantity_per_month'=>$htmlencoded_bevo_quantity_per_month,
			'bevo_machinery_details_docs'=>$bevo_machinery_details_docs,
			'constituent_oil_suppliers_docs'=>$constituent_oil_suppliers_docs,
			'other_points'=>$htmlencoded_other_points,
			'recommendations'=>$htmlencoded_recommendations,
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
				'commodity_quantity'=>$report_details['commodity_quantity'],						
				'own_machinery'=>$report_details['own_machinery'],
				'processing_done_by'=>$report_details['processing_done_by'],
				'machinery_processing_docs'=>$report_details['machinery_processing_docs'],						
				'graded_bevo_marketed_places'=>$report_details['graded_bevo_marketed_places'],
				'fat_spread_facilitities'=>$report_details['fat_spread_facilitities'],
				'bevo_quantity_per_month'=>$report_details['bevo_quantity_per_month'],
				'bevo_machinery_details_docs'=>$report_details['bevo_machinery_details_docs'],
				'constituent_oil_suppliers_docs'=>$report_details['constituent_oil_suppliers_docs'],
				'other_points'=>$report_details['other_points'],
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