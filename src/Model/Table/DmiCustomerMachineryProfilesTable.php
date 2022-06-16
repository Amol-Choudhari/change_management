<?php 
	
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiCustomerMachineryProfilesTable extends Table{
		
		var $name = "DmiCustomerMachineryProfiles";
		
		public $validate = array(
		
			'have_details'=>array(
					'rule'=>array('maxLength',10),				
				),											
			'detail_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'owned_by_applicant'=>array(
					'rule'=>array('maxLength',10),
				),
			'unit_related_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'provision_for_storage'=>array(
					'rule'=>array('maxLength',10),
				),
			'storage_provision_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'reffered_back_comment'=>array(
					'rule'=>array('maxLength',200),
				),
			'once_card_no'=>array(
					'rule'=>array('maxLength',50),
				),
			'customer_reply'=>array(
					'rule'=>array('maxLength',200),
				),
			'quantity_of_oilseeds'=>array(
					'rule'=>array('maxLength',100),
				),
			'bevo_machinery_details_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'fat_spread_facility_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'stored_crushed_separately'=>array(
					'rule'=>array('maxLength',10),
				),
			'stored_crushed_separately_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'crushed_refined_seeds'=>array(
					'rule'=>array('maxLength',150),
				),
			'mill_business_period'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'last'=>false),
					'numeric' => array(
						'rule' => 'numeric',
						'allowEmpty' => true,
					),
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
				$form_fields_details = Array (  'id'=>"",'customer_id' => "", 'have_details' => "yes", 'detail_docs' => "", 'owned_by_applicant' => "no", 'unit_name_address' => "", 
												'unit_related_docs' => "", 'provision_for_storage' => "", 'storage_provision_docs' => "", 'created' => "", 'modified' => "", 
												'reffered_back_comment' => "", 'reffered_back_date' => "", 'once_card_no' => "", 'form_status' => "", 'customer_reply' => "", 
												'customer_reply_date' => "", 'approved_date' => "", 'user_email_id' => "", 'user_once_no' => "", 'current_level' => "", 
												'quantity_of_oilseeds' => "", 'bevo_machinery_details_docs' => "", 'fat_spread_facility_docs' => "", 'stored_crushed_separately' => "yes", 
												'stored_crushed_separately_docs' => "", 'precautions_taken' => "", 'mo_comment' => "", 'mo_comment_date' => "", 'ro_reply_comment' => "", 
												'ro_reply_comment_date' => "", 'delete_mo_comment' => "", 'delete_ro_reply' => "", 'delete_ro_referred_back' => "", 'delete_customer_reply' => "",
												'crushed_refined_seeds' => "", 'mill_business_period' => "", 'ro_current_comment_to' => "",'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>""); 
			}
			
			$Dmi_all_machines_detail = TableRegistry::getTableLocator()->get('DmiAllMachinesDetails');
			$added_machines_detail = $Dmi_all_machines_detail->machineDetails(1);
                        
			return array($form_fields_details,$added_machines_detail);
			
		}
		
		
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){ 
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
				if($dataValidatation == 1 ){
					
				$CustomersController = new CustomersController;
				$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
				$section_form_details = $this->sectionFormDetails($customer_id);
				$applicant_type = $CustomersController->Customfunctions->checkFatSpreadOrBevo($customer_id);//call fucntion to check bevo or fat spread		
				
				if($ca_bevo_applicant == 'no')
				{							
					//html encoding post data before saving
					$htmlencoded_unit_name_address = htmlentities($forms_data['unit_name_address'], ENT_QUOTES);	
					
					//checking radio buttons input
					$post_input_request = $forms_data['have_details'];				
					$have_details = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($have_details == null){ return false;}
												
					$post_input_request = $forms_data['owned_by_applicant'];				
					$owned_by_applicant = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($owned_by_applicant == null){ return false;}
					

					//uploading files					
					if(!empty($forms_data['detail_docs']->getClientFilename())){								
						
						$file_name = $forms_data['detail_docs']->getClientFilename();
						$file_size = $forms_data['detail_docs']->getSize();
						$file_type = $forms_data['detail_docs']->getClientMediaType();
						$file_local_path = $forms_data['detail_docs']->getStream()->getMetadata('uri');
												
						$detail_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function							
					
					}else{ $detail_docs = $section_form_details[0]['detail_docs']; }

					
					if(!empty($forms_data['unit_related_docs']->getClientFilename())){
													
						$file_name = $forms_data['unit_related_docs']->getClientFilename();
						$file_size = $forms_data['unit_related_docs']->getSize();
						$file_type = $forms_data['unit_related_docs']->getClientMediaType();
						$file_local_path = $forms_data['unit_related_docs']->getStream()->getMetadata('uri');
												
						$unit_related_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function							
					
					}else{ $unit_related_docs = $section_form_details[0]['unit_related_docs']; }

					
					//Set all other values to null, not required in CA Form A
					$htmlencoded_quantity_of_oilseeds = null;
					$htmlencoded_precautions_taken = null;
					$htmlencoded_crushed_refined_seeds = null;
					$htmlencoded_mill_business_period = null;
					$stored_crushed_separately = null;
					$bevo_machinery_details_docs = null;
					$fat_spread_facility_docs = null;
					$stored_crushed_separately_docs = null;
					
					
					
				}elseif($ca_bevo_applicant == 'yes')
				{
					//html encoding post data before saving
					$htmlencoded_quantity_of_oilseeds = htmlentities($forms_data['quantity_of_oilseeds'], ENT_QUOTES);	
					$htmlencoded_precautions_taken = htmlentities($forms_data['precautions_taken'], ENT_QUOTES);
					$htmlencoded_crushed_refined_seeds = htmlentities($forms_data['crushed_refined_seeds'], ENT_QUOTES);	
					$htmlencoded_mill_business_period = htmlentities($forms_data['mill_business_period'], ENT_QUOTES);
					
					//checking radio buttons input
					$post_input_request = $forms_data['stored_crushed_separately'];				
					$stored_crushed_separately = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($stored_crushed_separately == null){ return false;};
					
					//uploading files	
					
					if($applicant_type == 'bevo')
					{	
						if(!empty($forms_data['bevo_machinery_details_docs']->getClientFilename())){								
							
							$file_name = $forms_data['bevo_machinery_details_docs']->getClientFilename();
							$file_size = $forms_data['bevo_machinery_details_docs']->getSize();
							$file_type = $forms_data['bevo_machinery_details_docs']->getClientMediaType();
							$file_local_path = $forms_data['bevo_machinery_details_docs']->getStream()->getMetadata('uri');
													
							$bevo_machinery_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function							
						
						}else{ $bevo_machinery_details_docs = $section_form_details[0]['bevo_machinery_details_docs']; }
						
						//if bevo
						$fat_spread_facility_docs = null;
					}
					if($applicant_type == 'fat_spread')
					{
						if(!empty($forms_data['fat_spread_facility_docs']->getClientFilename())){								
							
							$file_name = $forms_data['fat_spread_facility_docs']->getClientFilename();
							$file_size = $forms_data['fat_spread_facility_docs']->getSize();
							$file_type = $forms_data['fat_spread_facility_docs']->getClientMediaType();
							$file_local_path = $forms_data['fat_spread_facility_docs']->getStream()->getMetadata('uri');
													
							$fat_spread_facility_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function							
						
						}else{ $fat_spread_facility_docs = $section_form_details[0]['fat_spread_facility_docs']; }
						
						//if fat spread
						$bevo_machinery_details_docs = null;
					}
					
					if(!empty($forms_data['stored_crushed_separately_docs']->getClientFilename())){					
							
						
						$file_name = $forms_data['stored_crushed_separately_docs']->getClientFilename();
						$file_size = $forms_data['stored_crushed_separately_docs']->getSize();
						$file_type = $forms_data['stored_crushed_separately_docs']->getClientMediaType();
						$file_local_path = $forms_data['stored_crushed_separately_docs']->getStream()->getMetadata('uri');
												
						$stored_crushed_separately_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function							
					
					}else{ $stored_crushed_separately_docs = $section_form_details[0]['stored_crushed_separately_docs']; }
					
					
					//Set all other values to null, not required in CA Form A
					$htmlencoded_unit_name_address = null;
					$have_details = null;
					$owned_by_applicant = null;
					$detail_docs = null;
					$unit_related_docs = null;				
				}
				
				// If applicant have referred back on give section				
				if($section_form_details[0]['form_status'] == 'referred_back'){
					
					$max_id = $section_form_details[0]['id'];
					$htmlencoded_reply = htmlentities($forms_data['customer_reply'], ENT_QUOTES);
					$customer_reply_date = date('Y-m-d H:i:s');
					
					if(!empty($forms_data['cr_comment_ul']->getClientFilename())){				
						
						$file_name = $forms_data['cr_comment_ul']->getClientFilename();
						$file_size = $forms_data['cr_comment_ul']->getSize();
						$file_type = $forms_data['cr_comment_ul']->getClientMediaType();
						$file_local_path = $forms_data['cr_comment_ul']->getStream()->getMetadata('uri');
						
						$cr_comment_ul = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
					}else{ $cr_comment_ul = null; }
						
				}else{ 			
						$htmlencoded_reply = ''; 
						$max_id = ''; 
						$customer_reply_date = '';
						$cr_comment_ul = null;	
				}

				if(empty($section_form_details[0]['created'])){  $created = date('Y-m-d H:i:s'); }
				//added date function on 31-05-2021 by Amol to convert date format, as saving null
				else{ $created = $CustomersController->Customfunctions->changeDateFormat($section_form_details[0]['created']); }

				$newEntity = $this->newEntity(array(
					'id'=>$max_id,
					'customer_id'=>$customer_id,
					'once_card_no'=>$_SESSION['once_card_no'],
					'have_details'=>$have_details,
					'detail_docs'=>$detail_docs,
					'owned_by_applicant'=>$owned_by_applicant,
					'unit_name_address'=>$htmlencoded_unit_name_address,
					'unit_related_docs'=>$unit_related_docs,			
					//fields for BEVO starts
					'quantity_of_oilseeds'=>$htmlencoded_quantity_of_oilseeds,
					'bevo_machinery_details_docs'=>$bevo_machinery_details_docs,
					'fat_spread_facility_docs'=>$fat_spread_facility_docs,
					'stored_crushed_separately'=>$stored_crushed_separately,
					'stored_crushed_separately_docs'=>$stored_crushed_separately_docs,
					'precautions_taken'=>$htmlencoded_precautions_taken,
					'crushed_refined_seeds'=>$htmlencoded_crushed_refined_seeds,
					'mill_business_period'=>$htmlencoded_mill_business_period,
					//fields for BEVO end
					'form_status'=>'saved',
					'customer_reply'=>$htmlencoded_reply,
					'customer_reply_date'=>$customer_reply_date,
					'cr_comment_ul'=>$cr_comment_ul,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s')										
				));
				
				if ($this->save($newEntity)){ return 1; }	
				
			}else{	return false; }
		}
		
				
		// To save 	RO/SO referred back  and MO reply comment
		public function saveReferredBackComment($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to)
		{
			$logged_in_user = $_SESSION['username'];
			$current_level = $_SESSION['current_level'];
			
			//added date function on 31-05-2021 by Amol to convert date format, as saving null
			$CustomersController = new CustomersController;
			$created_date = $CustomersController->Customfunctions->changeDateFormat($forms_data['created']);
			
			if($reffered_back_to == 'Level3ToApplicant'){
				
				$form_status = 'referred_back';
				$reffered_back_comment = $comment;
				$reffered_back_date = date('Y-m-d H:i:s');
				$rb_comment_ul = $comment_upload;
				$ro_current_comment_to = 'applicant';
				$mo_comment = null;
				$mo_comment_date = null;
				$mo_comment_ul = null;
				$ro_reply_comment = null;
				$ro_reply_comment_date = null;
				$rr_comment_ul = null;
				
			}elseif($reffered_back_to == 'Level1ToLevel3'){
				
				$form_status = $forms_data['form_status'];
				$reffered_back_comment = null;
				$reffered_back_date = null;
				$rb_comment_ul = null;
				$ro_current_comment_to = null;
				$mo_comment = $comment;
				$mo_comment_date = date('Y-m-d H:i:s');
				$mo_comment_ul = $comment_upload;
				$ro_reply_comment = null;
				$ro_reply_comment_date = null;
				$rr_comment_ul = null;
				
			}elseif($reffered_back_to = 'Level3ToLevel'){
				
				$form_status = $forms_data['form_status'];
				$reffered_back_comment = $forms_data['reffered_back_comment'];
				$reffered_back_date = $forms_data['reffered_back_date'];
				$rb_comment_ul = $forms_data['rb_comment_ul'];
				$ro_current_comment_to = 'mo';
				$mo_comment = null;
				$mo_comment_date = null;
				$mo_comment_ul = null;
				$ro_reply_comment = $comment;
				$ro_reply_comment_date = date('Y-m-d H:i:s');
				$rr_comment_ul = $comment_upload;
				
			}	
			//print_r($form_status); exit;
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'once_card_no'=>$forms_data['once_card_no'],
				'have_details'=>$forms_data['have_details'],
				'detail_docs'=>$forms_data['detail_docs'],
				'owned_by_applicant'=>$forms_data['owned_by_applicant'],
				'unit_name_address'=>$forms_data['unit_name_address'],
				'unit_related_docs'=>$forms_data['unit_related_docs'],
				'quantity_of_oilseeds'=>$forms_data['quantity_of_oilseeds'],
				'bevo_machinery_details_docs'=>$forms_data['bevo_machinery_details_docs'],
				'fat_spread_facility_docs'=>$forms_data['fat_spread_facility_docs'],
				'stored_crushed_separately'=>$forms_data['stored_crushed_separately'],
				'stored_crushed_separately_docs'=>$forms_data['stored_crushed_separately_docs'],
				'precautions_taken'=>$forms_data['precautions_taken'],
				'crushed_refined_seeds'=>$forms_data['crushed_refined_seeds'],
				'mill_business_period'=>$forms_data['mill_business_period'],
				'created'=>$created_date,
				'modified'=>date('Y-m-d H:i:s'),
				'form_status'=>$form_status,
				'reffered_back_comment'=>$reffered_back_comment,
				'reffered_back_date'=>$reffered_back_date,
				'rb_comment_ul'=>$rb_comment_ul,
				'user_email_id'=>$_SESSION['username'],
				'user_once_no'=>$_SESSION['once_card_no'],
				'current_level'=>$current_level,
				'ro_current_comment_to'=>$ro_current_comment_to,	
				'mo_comment'=>$mo_comment,
				'mo_comment_date'=>$mo_comment_date,
				'mo_comment_ul'=>$mo_comment_ul,
				'ro_reply_comment'=>$ro_reply_comment,
				'ro_reply_comment_date'=>$ro_reply_comment_date,
				'rr_comment_ul'=>$rr_comment_ul	
				
				));
			
			if($this->save($newEntity)){ return true; }
		}
		
		public function postDataValidation($customer_id,$forms_data){
			
			$returnValue = true;
			$section_form_details = $this->sectionFormDetails($customer_id);
			$CustomersController = new CustomersController;
			$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id);
			$bevo_type = $CustomersController->Customfunctions->checkFatSpreadOrBevo($customer_id);
			//print_r($forms_data['stored_crushed_separately_docs']); exit;
			if($ca_bevo_applicant=='yes'){
				if(empty($section_form_details[0]['id'])){
					if($forms_data['stored_crushed_separately'] == 'yes'){

						if(empty($forms_data['stored_crushed_separately_docs']->getClientFilename())){ $returnValue = null ; }
					}
					//check bevo type added by amol on 23-02-2021
					if($bevo_type == 'bevo'){
						
						if(empty($forms_data['bevo_machinery_details_docs']->getClientFilename())){ $returnValue = null ; }
						
					}elseif($bevo_type == 'fat_spread'){
						
						if(empty($forms_data['fat_spread_facility_docs']->getClientFilename())){ $returnValue = null ; }
					}
										
				
				}else{
					if($forms_data['stored_crushed_separately'] == 'yes' && $section_form_details[0]['stored_crushed_separately_docs'] == ""){
						if(empty($forms_data['stored_crushed_separately_docs']->getClientFilename())){ $returnValue = null ; }
					}				
				}
				
				if(empty($forms_data['crushed_refined_seeds'])){ $returnValue = null ; }
				if(!filter_var($forms_data['mill_business_period'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
				if(empty($forms_data['quantity_of_oilseeds'])){ $returnValue = null ; }
				if(empty($forms_data['stored_crushed_separately'])){ $returnValue = null ; }
				if(empty($forms_data['precautions_taken'])){ $returnValue = null ; }
				
			}else{
				if(empty($section_form_details[0]['id'])){
					if($forms_data['have_details'] == 'yes'){						
						if(empty($forms_data['detail_docs']->getClientFilename())){ $returnValue = null ; }
					}
					if($forms_data['owned_by_applicant'] == 'no'){
						if(empty($forms_data['unit_related_docs'])){ $returnValue = null ; }				
					}										
				}else{
					if($forms_data['have_details'] == 'yes' && $section_form_details[0]['detail_docs'] == ""){						
						if(empty($forms_data['detail_docs']->getClientFilename())){ $returnValue = null ; }
					}
					if($forms_data['owned_by_applicant'] == 'no' && $section_form_details[0]['unit_related_docs'] == ""){
						if(empty($forms_data['unit_related_docs'])){ $returnValue = null ; }				
					}
				}
				if(empty($forms_data['have_details'])){ $returnValue = null ; }
				if(empty($forms_data['owned_by_applicant'])){ $returnValue = null ; }
				if($forms_data['have_details'] == 'yes'){
					if(empty($section_form_details[1][2])){ $returnValue = null ; }					
				}
				if($forms_data['owned_by_applicant'] == 'no'){
					if(empty($forms_data['unit_name_address'])){ $returnValue = null ; }				
				}
			}
			
			return $returnValue;
		}
		
		
} ?>