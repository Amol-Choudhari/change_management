<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiCustomerFirmProfilesTable extends Table{

		var $name = "DmiCustomerFirmProfiles";
		
		public $validate = array(
		
			'firm_name'=>array(
					'rule'=>array('maxLength',200),				
				),											
			'street_address'=>array(
					'rule'=>array('maxLength',200),
				),
			'state'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
			'district'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
			'postal_code'=>array(
					'rule'=>array('maxLength',20),
				),
			'business_type'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
			'business_type_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'have_reg_no'=>array(
					'rule'=>array('maxLength',10),
				),
			'fssai_reg_no'=>array(
					'rule'=>array('maxLength',100),
				),
			'fssai_reg_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'vopa_certificate_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'business_years'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'last'=>false),
					'numeric' => array(
						'rule' => 'numeric',
						'allowEmpty' => true,
					),
				),
			'reffered_back_comment'=>array(
					'rule'=>array('maxLength',200),
				),
			'customer_reply'=>array(
					'rule'=>array('maxLength',200),
				),
			'constituents_oils_docs_attached'=>array(
					'rule'=>array('maxLength',10),
				),
			'constituents_oils_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'firm_email_id'=>array(
					'rule'=>array('maxLength',200),
				),
			'firm_mobile_no'=>array(
					'rule'=>array('maxLength',20),
				),
			'firm_fax_no'=>array(
					'rule'=>array('maxLength',30),
				),
			'authorised_for_bevo'=>array(
					'rule'=>array('maxLength',10),
				),
			'authorised_bevo_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'quantity_per_month'=>array(
					'rule'=>array('maxLength',100),
				),
			'oil_manu_affidavit_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'bank_references_docs'=>array(
					'rule'=>array('maxLength',200),
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
				$form_fields_details = Array ( 'id'=>"", 'firm_name' =>"", 'street_address' => "", 'district' => "", 'postal_code' => "", 'business_type' => "",
											   'business_type_docs' => "", 'have_reg_no' => "yes", 'fssai_reg_no' => "", 'fssai_reg_docs' => "", 'have_reg_cert_no' =>"", 'vopa_certificate' =>"",
											   'vopa_certificate_docs' =>"", 'have_vat_cst_no' =>"", 'vat_cst_no' =>"", 'vat_cst_docs' =>"", 'have_storage_licence' =>"", 'storage_licence_no' =>"",
											   'storage_licence_docs' =>"", 'no_of_storage' =>"", 'created' => "", 'modified' =>"", 'customer_id' => "", 'business_years' => "", 'reffered_back_comment' => "",
											   'reffered_back_date' => "", 'once_card_no' =>"", 'form_status' =>"", 'customer_reply' =>"", 'customer_reply_date' =>"", 'approved_date' => "",
											   'user_email_id' => "", 'user_once_no' => "", 'current_level' => "", 'constituents_oils_docs_attached' =>"", 'constituents_oils_docs' =>"", 'firm_email_id' =>"",
											   'firm_mobile_no' => "", 'firm_fax_no' => "", 'authorised_for_bevo' => "no", 'authorised_bevo_docs' => "", 'bank_references' => "", 'quantity_per_month' =>"", 
											   'mo_comment' =>"", 'mo_comment_date' => "", 'ro_reply_comment' =>"", 'ro_reply_comment_date' =>"", 'delete_mo_comment' =>"", 'delete_ro_reply' => "",
											   'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "", 'oil_manu_affidavit_docs' => "", 'bank_references_docs' =>"", 'state' => "",
											   'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>""); 
				
			}
			
			$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
			$firm_details = $DmiFirms->firmDetails($customer_id);
			
			$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
			$sub_comm_id = explode(',',$firm_details['sub_commodity']);	
			$sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toArray();
			
			$DmiAllConstituentOilsDetails = TableRegistry::getTableLocator()->get('DmiAllConstituentOilsDetails');
			$added_const_oils_details = $DmiAllConstituentOilsDetails->constituentOilsMillDetails('Application');
			
			$DmiOldApplicationCertificateDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');
			$OldApplicationCertificateDetails = $DmiOldApplicationCertificateDetails->oldApplicationCertificationDetails($customer_id);	
			
			return array($form_fields_details,$added_const_oils_details,$sub_commodity_value,$OldApplicationCertificateDetails);
				
		}		
		
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if($dataValidatation == 1 ){
				
				$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
				$DmiOldApplicationDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');
				
				$CustomersController = new CustomersController;
				
				$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
				$oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);
				$section_form_details = $this->sectionFormDetails($customer_id);
				$firm_details = $DmiFirms->firmDetails($customer_id);
				
				$firm_name_main = $firm_details['firm_name'];
				$firm_street_address = $firm_details['street_address'];
				$firm_state_id = $firm_details['state'];
				$firm_district_id = $firm_details['district'];
				$firm_postal_code = $firm_details['postal_code'];
				$firm_email_id = $firm_details['email'];
				$firm_mobile_no = $firm_details['mobile_no'];
				$firm_fax_no = $firm_details['fax_no'];	
				
				if($ca_bevo_applicant == 'no')
				{
					//html encoding post data before saving 
					$htmlencoded_fssai_reg_no = htmlentities($forms_data['fssai_reg_no'], ENT_QUOTES);				
					$business_years = $forms_data['business_years'];
				
					//checking radio buttons input
					$post_input_request = $forms_data['have_reg_no'];				
					$have_reg_no = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($have_reg_no == null){ return false;}
					
					//file uploads
					if(!empty($forms_data['fssai_reg_docs']->getClientFilename())){				
						
						$file_name = $forms_data['fssai_reg_docs']->getClientFilename();
						$file_size = $forms_data['fssai_reg_docs']->getSize();
						$file_type = $forms_data['fssai_reg_docs']->getClientMediaType();
						$file_local_path = $forms_data['fssai_reg_docs']->getStream()->getMetadata('uri');			
					
						$fssai_reg_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function				
					
					}else{ $fssai_reg_docs = $section_form_details[0]['fssai_reg_docs']; }
					
					
					//Set all other values to null, not required in CA Form A
					$authorised_for_bevo = null;
					$authorised_bevo_docs = null;
					$html_encoded_bank_references = null;
					$html_encoded_quantity_per_month = null;
					$bank_references_docs = null;
					$oil_manu_affidavit_docs = null;
					$vopa_certificate_docs = null;
					
				}
				elseif($ca_bevo_applicant == 'yes')	
				{
					//htmlencoding post data
					$html_encoded_bank_references = htmlentities($forms_data['bank_references'], ENT_QUOTES);
					$html_encoded_quantity_per_month = htmlentities($forms_data['quantity_per_month'], ENT_QUOTES);
					
					//checking radio buttons input
					$post_input_request = $forms_data['authorised_for_bevo'];				
					$authorised_for_bevo = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($authorised_for_bevo == null){ return false;}
				
					//file upload
					if(!empty($forms_data['authorised_bevo_docs']->getClientFilename())){
						
						
						$file_name = $forms_data['authorised_bevo_docs']->getClientFilename();
						$file_size = $forms_data['authorised_bevo_docs']->getSize();
						$file_type = $forms_data['authorised_bevo_docs']->getClientMediaType();
						$file_local_path = $forms_data['authorised_bevo_docs']->getStream()->getMetadata('uri');
					
					
						$authorised_bevo_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
					
					}else{ $authorised_bevo_docs = $section_form_details[0]['authorised_bevo_docs']; }
					
					
					// Add New Field Affidavit/Undertaking From Oil Manufacturer by Pravin 22/07/2017
					//file upload
					if(!empty($forms_data['oil_manu_affidavit_docs']->getClientFilename())){
						
						$file_name = $forms_data['oil_manu_affidavit_docs']->getClientFilename();
						$file_size = $forms_data['oil_manu_affidavit_docs']->getSize();
						$file_type = $forms_data['oil_manu_affidavit_docs']->getClientMediaType();
						$file_local_path = $forms_data['oil_manu_affidavit_docs']->getStream()->getMetadata('uri');
					
						$oil_manu_affidavit_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
					
					}else{ $oil_manu_affidavit_docs = $section_form_details[0]['oil_manu_affidavit_docs']; }
					
					// Add New Field FSSAI Registration Details by Pravin 22/07/2017
					//file upload
					if(!empty($forms_data['fssai_reg_docs']->getClientFilename())){
						
						$file_name = $forms_data['fssai_reg_docs']->getClientFilename();
						$file_size = $forms_data['fssai_reg_docs']->getSize();
						$file_type = $forms_data['fssai_reg_docs']->getClientMediaType();
						$file_local_path = $forms_data['fssai_reg_docs']->getStream()->getMetadata('uri');
					
						$fssai_reg_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
					
					}else{ $fssai_reg_docs = $section_form_details[0]['fssai_reg_docs']; }
					
					// Add New Field vopa_certificate_docs by Pravin 22/07/2017
					//file upload
					if(!empty($forms_data['vopa_certificate_docs']->getClientFilename())){
						
						$file_name = $forms_data['vopa_certificate_docs']->getClientFilename();
						$file_size = $forms_data['vopa_certificate_docs']->getSize();
						$file_type = $forms_data['vopa_certificate_docs']->getClientMediaType();
						$file_local_path = $forms_data['vopa_certificate_docs']->getStream()->getMetadata('uri');
					
						$vopa_certificate_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
					
					}else{ $vopa_certificate_docs = $section_form_details[0]['vopa_certificate_docs']; }
					
					// Add New Field bank_references_docs by Amol 04-08-2017
					//file upload
					if(!empty($forms_data['bank_references_docs']->getClientFilename())){					
						
						$file_name = $forms_data['bank_references_docs']->getClientFilename();
						$file_size = $forms_data['bank_references_docs']->getSize();
						$file_type = $forms_data['bank_references_docs']->getClientMediaType();
						$file_local_path = $forms_data['bank_references_docs']->getStream()->getMetadata('uri');
					
						$bank_references_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
					
					}else{ $bank_references_docs = $section_form_details[0]['bank_references_docs']; }
					
					
					//Set all other values to null, not required in CA BEVO
					$htmlencoded_fssai_reg_no = null;
					$business_years = null;
					$have_reg_no = null;
					//$fssai_reg_docs = null;
				
				}
				
				
				$table = 'DmiBusinessTypes';
				$post_input_request = $forms_data['business_type'];
				$business_type = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function

				
				//file uploading					
				if(!empty($forms_data['business_type_docs']->getClientFilename())){				
					
					$file_name = $forms_data['business_type_docs']->getClientFilename();
					$file_size = $forms_data['business_type_docs']->getSize();
					$file_type = $forms_data['business_type_docs']->getClientMediaType();
					$file_local_path = $forms_data['business_type_docs']->getStream()->getMetadata('uri');
					
					$business_type_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				}else{ $business_type_docs = $section_form_details[0]['business_type_docs']; }
								
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
					'firm_name'=>$firm_name_main,
					'street_address'=>$firm_street_address,
					'state'=>$firm_state_id,
					'district'=>$firm_district_id,
					'postal_code'=>$firm_postal_code,
					'firm_email_id'=>base64_encode($firm_email_id),//for email encoding
					'firm_mobile_no'=>$firm_mobile_no,
					'firm_fax_no'=>$firm_fax_no,
					'business_type'=>$business_type,
					'business_type_docs'=>$business_type_docs,
					'business_years'=>$business_years,
					'have_reg_no'=>$have_reg_no,
					'fssai_reg_no'=>$htmlencoded_fssai_reg_no,
					'fssai_reg_docs'=>$fssai_reg_docs,
					//fields for BEVO starts
					'authorised_for_bevo'=>$authorised_for_bevo,
					'authorised_bevo_docs'=>$authorised_bevo_docs,				
					'oil_manu_affidavit_docs'=>$oil_manu_affidavit_docs, //Add By pravin 22/07/2017
					'vopa_certificate_docs'=>$vopa_certificate_docs, //Add By pravin 22/07/2017					
					'quantity_per_month'=>$html_encoded_quantity_per_month,
					'bank_references'=>$html_encoded_bank_references,
					'bank_references_docs'=>$bank_references_docs,
					//fields for BEVO end	
					'form_status'=>'saved',
					'customer_reply'=>$htmlencoded_reply,
					'customer_reply_date'=>$customer_reply_date,
					'cr_comment_ul'=>$cr_comment_ul,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s')));
				
				if ($this->save($newEntity)){ 			
					
					if($oldapplication == 'yes'){
						
											$old_certificate_details = $DmiOldApplicationDetails->oldApplicationCertificationDetails($customer_id);
											
						if(!empty($forms_data['old_certification_pdf']->getClientFilename())){
									
							$file_name = $forms_data['old_certification_pdf']->getClientFilename();
							$file_size = $forms_data['old_certification_pdf']->getSize();
							$file_type = $forms_data['old_certification_pdf']->getClientMediaType();
							$file_local_path = $forms_data['old_certification_pdf']->getStream()->getMetadata('uri');

							$old_certification_pdf = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
						
						}else{ $old_certification_pdf = $old_certificate_details['old_certificate_pdf']; }
						
						
						if(!empty($forms_data['old_application_docs']->getClientFilename())){
							
							$file_name = $forms_data['old_application_docs']->getClientFilename();
							$file_size = $forms_data['old_application_docs']->getSize();
							$file_type = $forms_data['old_application_docs']->getClientMediaType();
							$file_local_path = $forms_data['old_application_docs']->getStream()->getMetadata('uri');

							$old_application_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
						
						}else{ $old_application_docs = $old_certificate_details['old_application_docs']; }
						
						$DmiOldApplicationDetailsEntity = $DmiOldApplicationDetails->newEntity(array(											
							'id'=>$old_certificate_details['id'],
							'old_certificate_pdf'=>$old_certification_pdf,
							'old_application_docs'=>$old_application_docs,
						));
						
						if($DmiOldApplicationDetails->save($DmiOldApplicationDetailsEntity)){ return 1;  }
					
					}else{ return 1;  }
					
				};
				
			}else{	return false; }	
			
					
		}
				
		
		
		// To save 	RO/SO referred back  and MO reply comment
		public function saveReferredBackComment ($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to)
		{			
			// Import another model in this model	
			
			$logged_in_user = $_SESSION['username'];
			$current_level = $_SESSION['current_level'];
			
			$DmiOldApplicationDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');
			
			$CustomersController = new CustomersController;
			$oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);
			
			//added date function on 31-05-2021 by Amol to convert date format, as saving null
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
				
			}elseif($reffered_back_to == 'Level3ToLevel'){
				
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
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'once_card_no'=>$forms_data['once_card_no'],
				'firm_name'=>$forms_data['firm_name'],
				'street_address'=>$forms_data['street_address'],
				'state'=>$forms_data['state'],
				'district'=>$forms_data['district'],
				'postal_code'=>$forms_data['postal_code'],
				'firm_email_id'=>base64_encode($forms_data['firm_email_id']),//for email encoding
				'firm_mobile_no'=>$forms_data['firm_mobile_no'],
				'firm_fax_no'=>$forms_data['firm_fax_no'],
				'business_type'=>$forms_data['business_type'],
				'business_type_docs'=>$forms_data['business_type_docs'],
				'business_years'=>$forms_data['business_years'],
				'have_reg_no'=>$forms_data['have_reg_no'],
				'fssai_reg_no'=>$forms_data['fssai_reg_no'],
				'fssai_reg_docs'=>$forms_data['fssai_reg_docs'],
				'authorised_for_bevo'=>$forms_data['authorised_for_bevo'],
				'authorised_bevo_docs'=>$forms_data['authorised_bevo_docs'],
				
				// Add new Fields oil_manu_affidavit_docs and vopa_certificate_docs by pravin 22/07/2017
				'oil_manu_affidavit_docs'=>$forms_data['oil_manu_affidavit_docs'],
				'vopa_certificate_docs'=>$forms_data['vopa_certificate_docs'],
				
				'quantity_per_month'=>$forms_data['quantity_per_month'],
				'bank_references'=>$forms_data['bank_references'],
				'bank_references_docs'=>$forms_data['bank_references_docs'],
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
			
			if($this->save($newEntity)){ 
			
				if($oldapplication == 'yes'){
                                    
                                        $old_certificate_details = $DmiOldApplicationDetails->oldApplicationCertificationDetails($customer_id);
                                        
					$DmiOldApplicationDetailsEntity = $DmiOldApplicationDetails->newEntity(array(											
                                            'id'=>$old_certificate_details['id'],
                                            'old_certificate_pdf'=>$old_certificate_details['old_certificate_pdf'],
                                            'old_application_docs'=>$old_certificate_details['old_application_docs'],
					));
						
					if($DmiOldApplicationDetails->save($DmiOldApplicationDetailsEntity)){ return true;  } 
					
				}else{ return true; } 
			}

		}


		public function postDataValidation($customer_id,$forms_data){
		//	print_r($forms_data); exit;
			$returnValue = true;
			$section_form_details = $this->sectionFormDetails($customer_id);
			$CustomersController = new CustomersController;
			
			$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiAllDirectorsDetails');			
			$added_directors_details = $DmiAllDirectorsDetails->allDirectorsDetail($customer_id);
			$oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);
			$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id);
			
			if($ca_bevo_applicant=='yes'){
				if(empty($section_form_details[0]['id'])){
					
					if($forms_data['authorised_for_bevo'] == 'yes'){
						if(empty($forms_data['authorised_bevo_docs']->getClientFilename())){ $returnValue = null ; }
					}
					if(empty($forms_data['oil_manu_affidavit_docs']->getClientFilename())){ $returnValue = null ; }
					if(empty($forms_data['fssai_reg_docs']->getClientFilename())){ $returnValue = null ; }
					if(empty($forms_data['vopa_certificate_docs']->getClientFilename())){ $returnValue = null ; }
					if(empty($forms_data['bank_references_docs']->getClientFilename())){ $returnValue = null ; }
				}else{
					if($forms_data['authorised_for_bevo'] == 'yes' && $section_form_details[0]['authorised_bevo_docs'] == ""){
						if(empty($forms_data['authorised_bevo_docs']->getClientFilename())){ $returnValue = null ; }
					}
				}
				
				if(empty($section_form_details[1])){ $returnValue = null ; }
				if(empty($forms_data['authorised_for_bevo'])){ $returnValue = null ; }
				if(empty($forms_data['quantity_per_month'])){ $returnValue = null ; }
				if(empty($forms_data['bank_references'])){ $returnValue = null ; }
					
			}else{				
			
				if(empty($section_form_details[0]['id'])){
					
					if(empty($forms_data['fssai_reg_docs']->getClientFilename())){ $returnValue = null ; }
				}
				if(empty($forms_data['fssai_reg_no'])){ $returnValue = null ; }				
				if(!filter_var($forms_data['business_years'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
			}
			if(empty($section_form_details[0]['id'])){
				if(empty($forms_data['business_type_docs']->getClientFilename())){ $returnValue = null ; }
			}
			if(!filter_var($forms_data['business_type'], FILTER_VALIDATE_INT)){ $returnValue = null ; }			
				
			if($oldapplication == 'yes'){
				if(empty($section_form_details[0]['id'])){
					if(empty($forms_data['old_certification_pdf']->getClientFilename())){ $returnValue = null ; }
					if(empty($forms_data['old_application_docs']->getClientFilename())){ $returnValue = null ; }					
				}
				if(empty($added_directors_details)){ $returnValue = null ; }	
			}
			
			return $returnValue;
			
		}

} ?>