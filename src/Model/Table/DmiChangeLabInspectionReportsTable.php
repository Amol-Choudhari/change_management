<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiChangeLabInspectionReportsTable extends Table{
		
		var $name = "DmiChangeLabInspectionReports";
		
		public $validate = array(
		
				'customer_id'=>array(
					'rule'=>array('maxLength',20),				
				),
				'io_reply_once_no'=>array(
					'rule'=>array('maxLength',200),				
				),
				'user_email_id'=>array(
					'rule'=>array('maxLength',50),				
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
					'rule'=>array('maxLength',20),				
				),
				'referred_back_by_email'=>array(
					'rule'=>array('maxLength',50),				
				),
				'referred_back_by_once'=>array(
					'rule'=>array('maxLength',50),				
				),
				'current_level'=>array(
					'rule'=>array('maxLength',20),				
				),
				'laboratory_site_plan_no'=>array(
					'rule'=>array('maxLength',50),				
				),
				'laboratory_site_plan_docs'=>array(
					'rule'=>array('maxLength',200),				
				),
				'lab_surrounding_details'=>array(
					'rule'=>array('maxLength',20),				
				),
				'lab_environment_details'=>array(
					'rule'=>array('maxLength',20),				
				),
				'is_lab_fully_equipped'=>array(
					'rule'=>array('maxLength',20),				
				),
				'is_lab_fully_equipped_doc'=>array(
					'rule'=>array('maxLength',100),				
				),
				'laboretory_safety_records'=>array(
					'rule'=>array('maxLength',20),				
				),
				'chemists_employed_docs'=>array(
					'rule'=>array('maxLength',100),				
				),
				'delete_ro_referred_back'=>array(
					'rule'=>array('maxLength',10),				
				),			
		
		);
		
		/* fetch printing firm details (Done by pravin 29/09/2018)*/
		public function sectionFormDetails($customer_id){
			
			$CustomersController = new CustomersController;	
			$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
			
			$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
			if(!empty($latest_id)){
				$report_fields = $this->find('all',array('conditions'=>array('id' => MAX($latest_id))))->first();
				$form_fields_details = $report_fields;
			}else{ 
					$form_fields_details = Array ( 'id' => "", 'customer_id' => "", 'io_reply_once_no' => "", 'user_email_id' => "", 'user_once_no' => "", 'referred_back_comment' => "", 'referred_back_date' => "", 'io_reply' => "",
											'io_reply_date' => "", 'form_status' => "", 'referred_back_by_email'=>"", 'referred_back_by_once' => "", 'current_level' => "", 'inspection_date' => "", 'laboratory_site_plan_no' => "", 'laboratory_site_plan_docs' => "",
											'lab_surrounding_details' => "no", 'lab_environment_details' => "no", 'is_lab_fully_equipped' => "no", 'is_lab_fully_equipped_doc' => "", 'laboretory_safety_records' => "no", 'chemists_employed_docs' => "", 'recommendations' => "",
											'created' => "", 'modified' => "", 'delete_ro_referred_back' => "");  
			}
			
			$DmiLaboratoryChemistsDetails = TableRegistry::getTableLocator()->get('DmiChangeLabChemistsDetails');
			$laboratory_chemist_details = $DmiLaboratoryChemistsDetails->laboratoryChemistDetails($customer_id);
			$chemist_details = $laboratory_chemist_details[0];
			$chemist_commodity_value = $laboratory_chemist_details[1];	
			return array($form_fields_details,$chemist_details,$chemist_commodity_value);
				
	   }	   
	   
	   public function saveFormDetails($customer_id,$forms_data){
		
					$CustomersController = new CustomersController;
					
					$ca_bevo_applicant =$CustomersController->Customfunctions->checkCaBevo($customer_id); 
					
					$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists'); 
					$final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'inspection_report');
					
					$Dmi_siteinspection_final_report = TableRegistry::getTableLocator()->get($final_submit_table);
					$report_final_status = $Dmi_siteinspection_final_report->siteinspectionFinalReportStatus($customer_id);
					
					
					$id = '';  $message_id = 1;
					$current_level = $_SESSION['current_level'];					
		
					$report_details = $this->sectionFormDetails($customer_id);		
		
					if(!empty($report_details[0]['created'])){
						$id = $report_details[0]['id'];
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
					$htmlencoded_inspection_date = $CustomersController->Customfunctions->dateFormatCheck(htmlentities($forms_data['inspection_date'], ENT_QUOTES));	
					$htmlencoded_laboratory_site_plan_no = htmlentities($forms_data['laboratory_site_plan_no'], ENT_QUOTES);
					$htmlencoded_recommendations = htmlentities($forms_data['recommendations'], ENT_QUOTES);
					
					
					//checking radio buttons inputs
					$post_input_request = $forms_data['lab_surrounding_details'];				
					$lab_surrounding_details = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($lab_surrounding_details == null){ return false;}

					$post_input_request = $forms_data['lab_environment_details'];				
					$lab_environment_details = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($lab_environment_details == null){ return false;}
					
					$post_input_request = $forms_data['is_lab_fully_equipped'];				
					$is_lab_fully_equipped = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($is_lab_fully_equipped == null){ return false;}
					
					$post_input_request = $forms_data['laboretory_safety_records'];				
					$laboretory_safety_records = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($laboretory_safety_records == null){ return false;}
					
					
					//file uploads
					if(!empty($forms_data['laboratory_site_plan_docs']->getClientFilename())){

							$file_name = $forms_data['laboratory_site_plan_docs']->getClientFilename();
							$file_size = $forms_data['laboratory_site_plan_docs']->getSize();
							$file_type = $forms_data['laboratory_site_plan_docs']->getClientMediaType();
							$file_local_path = $forms_data['laboratory_site_plan_docs']->getStream()->getMetadata('uri');

							$laboratory_site_plan_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
						
						}else{						
							$laboratory_site_plan_docs = '';
						}
						
						
						if(!empty($forms_data['is_lab_fully_equipped_doc']->getClientFilename())){

							$file_name = $forms_data['is_lab_fully_equipped_doc']->getClientFilename();
							$file_size = $forms_data['is_lab_fully_equipped_doc']->getSize();
							$file_type = $forms_data['is_lab_fully_equipped_doc']->getClientMediaType();
							$file_local_path = $forms_data['is_lab_fully_equipped_doc']->getStream()->getMetadata('uri');

							$is_lab_fully_equipped_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
						
						}else{							
							$is_lab_fully_equipped_doc = '';
						}
						

						if(!empty($forms_data['chemists_employed_docs']->getClientFilename())){

							$file_name = $forms_data['chemists_employed_docs']->getClientFilename();
							$file_size = $forms_data['chemists_employed_docs']->getSize();
							$file_type = $forms_data['chemists_employed_docs']->getClientMediaType();
							$file_local_path = $forms_data['chemists_employed_docs']->getStream()->getMetadata('uri');

							$chemists_employed_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
						
						}else{			
							$chemists_employed_docs = '';
						}
					
					
					
					
				//check if new file is selected	while reply if not save file path from db
					if(!empty($report_details[0]['created'])){
						
						if(empty($laboratory_site_plan_docs)){
							
							$laboratory_site_plan_docs = $report_details[0]['laboratory_site_plan_docs'];
						}
						if(empty($is_lab_fully_equipped_doc)){
							
							$is_lab_fully_equipped_doc = $report_details[0]['is_lab_fully_equipped_doc'];
						}
						if(empty($chemists_employed_docs)){
							
							$chemists_employed_docs = $report_details[0]['chemists_employed_docs'];
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
						
					if(empty($report_details[0]['created'])){  $created = date('Y-m-d H:i:s'); }
					else{ $created = $CustomersController->Customfunctions->dateFormatCheck($report_details[0]['created']); }
			
					$formSavedEntity = $this->newEntity(array(						
			
						'id'=>$id,
						'customer_id'=>$customer_id,
						'user_email_id'=>$_SESSION['username'],
						'user_once_no'=>$_SESSION['once_card_no'],
						'inspection_date'=>$htmlencoded_inspection_date,
						'laboratory_site_plan_no'=>$htmlencoded_laboratory_site_plan_no,
						'laboratory_site_plan_docs'=>$laboratory_site_plan_docs,
						'lab_surrounding_details'=>$lab_surrounding_details,
						'lab_environment_details'=>$lab_environment_details,
						'is_lab_fully_equipped'=>$is_lab_fully_equipped,
						'is_lab_fully_equipped_doc'=>$is_lab_fully_equipped_doc,
						'laboretory_safety_records'=>$laboretory_safety_records,
						'chemists_employed_docs'=>$chemists_employed_docs,
						'recommendations'=>$htmlencoded_recommendations,
						'form_status'=>'saved',
						'created'=>$created,
						'modified'=>date('Y-m-d H:i:s')		
					
					));	
					
					if($this->save($formSavedEntity)){ return $message_id; }else{ $message_id = ""; return $message_id; }  		
			}
			
			
			public function saveReferredBackComment($customer_id,$report_details,$reffered_back_comment,$rb_comment_ul){
				
						$CustomersController = new CustomersController;
						$inspection_date = $CustomersController->Customfunctions->dateFormatCheck($report_details['inspection_date']);
					
					$formSavedEntity = $this->newEntity(array(
						
						'customer_id'=>$customer_id,
						'user_email_id'=>$report_details['user_email_id'],
						'user_once_no'=>$report_details['user_once_no'],
						'inspection_date'=>$inspection_date,
						'laboratory_site_plan_no'=>$report_details['laboratory_site_plan_no'],
						'laboratory_site_plan_docs'=>$report_details['laboratory_site_plan_docs'],
						'lab_surrounding_details'=>$report_details['lab_surrounding_details'],
						'lab_environment_details'=>$report_details['lab_environment_details'],
						'is_lab_fully_equipped'=>$report_details['is_lab_fully_equipped'],
						'is_lab_fully_equipped_doc'=>$report_details['is_lab_fully_equipped_doc'],
						'laboretory_safety_records'=>$report_details['laboretory_safety_records'],
						'chemists_employed_docs'=>$report_details['chemists_employed_docs'],
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