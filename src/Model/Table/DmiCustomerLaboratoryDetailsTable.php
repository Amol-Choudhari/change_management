<?php 

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiCustomerLaboratoryDetailsTable extends Table{
		

		var $name = "DmiCustomerLaboratoryDetails";
		
		public $validate = array(
		
			'laboratory_name'=>array(
					'rule'=>array('maxLength',200),				
				),											
			'laboratory_type'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'last'=>false),
					'numeric' => array(
						'rule' => 'numeric',
						'allowEmpty' => true,
					),
				),
			'consent_letter_attached'=>array(
					'rule'=>array('maxLength',10),				
				),
			'consent_letter_docs'=>array(
					'rule'=>array('maxLength',200),				
				),
			'street_address'=>array(
					'rule'=>array('maxLength',200),				
				),
			'state'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'last'=>false),
					'numeric' => array(
						'rule' => 'numeric',
						'allowEmpty' => true,
					),
				),
			'district'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'last'=>false),
					'numeric' => array(
						'rule' => 'numeric',
						'allowEmpty' => true,
					),
				),
			'postal_code'=>array(
					'rule'=>array('maxLength',20),				
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
			'lab_email_id'=>array(
					'rule'=>array('maxLength',200),				
				),
			'lab_mobile_no'=>array(
					'rule'=>array('maxLength',20),				
				),
			'lab_fax_no'=>array(
					'rule'=>array('maxLength',20),				
				),
			'is_lab_equipped'=>array(
					'rule'=>array('maxLength',10),				
				),
			'lab_equipped_docs'=>array(
					'rule'=>array('maxLength',200),				
				),
			'chemist_detail_docs'=>array(
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
				$form_fields_details =	Array ( 'id' => "", 'laboratory_name' => "", 'laboratory_type' => "", 'consent_letter_attached' => "", 'consent_letter_docs' => "",
												'street_address' => "", 'state' => "", 'district' => "", 'postal_code' => "", 'created' => "", 'modified' => "", 'customer_id' => "",
												'reffered_back_comment' => "", 'reffered_back_date' => "", 'once_card_no' => "", 'form_status' => "", 'customer_reply' => "", 
												'customer_reply_date' => "", 'approved_date' => "", 'user_email_id' => "", 'user_once_no' => "", 'current_level' => "", 'lab_email_id' => "",
												'lab_mobile_no' => "", 'lab_fax_no' => "", 'is_lab_equipped' => "yes", 'lab_equipped_docs' => "", 'mo_comment' => "", 'mo_comment_date' => "", 'ro_reply_comment' => "",
												'ro_reply_comment_date' => "", 'delete_mo_comment' => "", 'delete_ro_reply' => "", 'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 
												'ro_current_comment_to' => "", 'chemist_detail_docs' => "",'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"" );
			}
			
                        $CustomersController = new CustomersController;
			$Dmi_laboratory_type = TableRegistry::getTableLocator()->get('DmiLaboratoryTypes');
			$laboratory_types = $CustomersController->Mastertablecontent->allLaboratoryType();
			
			$Dmi_district = TableRegistry::getTableLocator()->get('DmiDistricts');
			$districts_list = $Dmi_district->find('list', array('valueField'=>'district_name', 'conditions'=>array('state_id IS'=>$form_fields_details['state'],'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->toArray();
			asort($districts_list);
			
			return array($form_fields_details,$laboratory_types,$districts_list);
				
		}
		
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if($dataValidatation == 1 ){
				
				$CustomersController = new CustomersController;
				$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
				$section_form_details = $this->sectionFormDetails($customer_id);
				
				if($ca_bevo_applicant == 'no')
				{
					//html encoding post data before saving
					$htmlencoded_laboratory_name = htmlentities($forms_data['laboratory_name'], ENT_QUOTES);			
					$htmlencoded_street_address = htmlentities($forms_data['street_address'], ENT_QUOTES);
					$htmlencoded_postal_code = htmlentities($forms_data['postal_code'], ENT_QUOTES);
					$htmlencoded_lab_email_id = htmlentities(base64_encode($forms_data['lab_email_id']), ENT_QUOTES);//for email encoding
					$htmlencoded_lab_mobile_no = htmlentities(base64_encode($forms_data['lab_mobile_no']), ENT_QUOTES);	//This is added on 27-04-2021 for base64encoding by AKASH
					$htmlencoded_lab_fax_no = htmlentities(base64_encode($forms_data['lab_fax_no']), ENT_QUOTES);//This is added on 27-04-2021 for base64encoding by AKASH

					//checking dropdown input
					//for state
					$table = 'DmiStates';
					$post_input_request = $forms_data['state'];
					$state = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
							
							
					//for district
					$table = 'DmiDistricts';
					$post_input_request = $forms_data['district'];
					$district = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
						
					//for laboratory type
					$table = 'DmiLaboratoryTypes';
					$post_input_request = $forms_data['laboratory_type'];
					$laboratory_type = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
						
											
					//uploading files					
					if(!empty($forms_data['consent_letter_docs']->getClientFilename())){

						$file_name = $forms_data['consent_letter_docs']->getClientFilename();
						$file_size = $forms_data['consent_letter_docs']->getSize();
						$file_type = $forms_data['consent_letter_docs']->getClientMediaType();
						$file_local_path = $forms_data['consent_letter_docs']->getStream()->getMetadata('uri');
													
						$consent_letter_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $consent_letter_docs = $section_form_details[0]['consent_letter_docs']; }
					
					
					// Add new field by pravin 22-07-2017
					if(!empty($forms_data['chemist_detail_docs']->getClientFilename())){

						$file_name = $forms_data['chemist_detail_docs']->getClientFilename();
						$file_size = $forms_data['chemist_detail_docs']->getSize();
						$file_type = $forms_data['chemist_detail_docs']->getClientMediaType();
						$file_local_path = $forms_data['chemist_detail_docs']->getStream()->getMetadata('uri');
													
						$chemist_detail_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $chemist_detail_docs = $section_form_details[0]['chemist_detail_docs']; }
					
					//uploading files					
					if(!empty($forms_data['lab_equipped_docs']->getClientFilename())){

						$file_name = $forms_data['lab_equipped_docs']->getClientFilename();
						$file_size = $forms_data['lab_equipped_docs']->getSize();
						$file_type = $forms_data['lab_equipped_docs']->getClientMediaType();
						$file_local_path = $forms_data['lab_equipped_docs']->getStream()->getMetadata('uri');
													
						$lab_equipped_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $lab_equipped_docs = $section_form_details[0]['lab_equipped_docs']; }
					
					//Set all other values to null, not required in CA Form A
					$is_lab_equipped = null;
					
					
				}
				elseif($ca_bevo_applicant == 'yes')
				{
					
					//checking radio buttons input						
					$post_input_request = $forms_data['is_lab_equipped'];				
					$is_lab_equipped = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($is_lab_equipped == null){ return false;}
					
					
					//uploading files					
					if(!empty($forms_data['lab_equipped_docs']->getClientFilename())){

						$file_name = $forms_data['lab_equipped_docs']->getClientFilename();
						$file_size = $forms_data['lab_equipped_docs']->getSize();
						$file_type = $forms_data['lab_equipped_docs']->getClientMediaType();
						$file_local_path = $forms_data['lab_equipped_docs']->getStream()->getMetadata('uri');
													
						$lab_equipped_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $lab_equipped_docs = $section_form_details[0]['lab_equipped_docs']; }
					
					
					
					// Add new field by pravin 22-07-2017
					if(!empty($forms_data['chemist_detail_docs']->getClientFilename())){

						$file_name = $forms_data['chemist_detail_docs']->getClientFilename();
						$file_size = $forms_data['chemist_detail_docs']->getSize();
						$file_type = $forms_data['chemist_detail_docs']->getClientMediaType();
						$file_local_path = $forms_data['chemist_detail_docs']->getStream()->getMetadata('uri');
													
						$chemist_detail_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $chemist_detail_docs = $section_form_details[0]['chemist_detail_docs']; }
					
					
					//Set all other values to null, not required in CA BEVO
					$htmlencoded_laboratory_name = null;
					$htmlencoded_street_address = null;
					$htmlencoded_postal_code = null;
					$htmlencoded_lab_email_id = null;
					$htmlencoded_lab_mobile_no = null;
					$htmlencoded_lab_fax_no = null;
					$state = null;
					$district = null;
					$laboratory_type = null;
					$consent_letter_docs = null;
					
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
					'laboratory_name'=>$htmlencoded_laboratory_name,
					'laboratory_type'=>$laboratory_type,			
					'consent_letter_docs'=>$consent_letter_docs,				
					'chemist_detail_docs'=>$chemist_detail_docs,
					'street_address'=>$htmlencoded_street_address,
					'state'=>$state,
					'district'=>$district,
					'postal_code'=>$htmlencoded_postal_code,
					'lab_email_id'=>$htmlencoded_lab_email_id,
					'lab_mobile_no'=>$htmlencoded_lab_mobile_no,
					'lab_fax_no'=>$htmlencoded_lab_fax_no,			
					'is_lab_equipped'=>$is_lab_equipped,
					'lab_equipped_docs'=>$lab_equipped_docs,
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
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'once_card_no'=>$forms_data['once_card_no'],
				'laboratory_name'=>$forms_data['laboratory_name'],
				'laboratory_type'=>$forms_data['laboratory_type'],
				'consent_letter_docs'=>$forms_data['consent_letter_docs'],
				'street_address'=>$forms_data['street_address'],
				'state'=>$forms_data['state'],
				'district'=>$forms_data['district'],
				'postal_code'=>$forms_data['postal_code'],
				'lab_email_id'=>base64_encode($forms_data['lab_email_id']),//for email encoding
				'lab_mobile_no'=>$forms_data['lab_mobile_no'],
				'lab_fax_no'=>$forms_data['lab_fax_no'],
				'is_lab_equipped'=>$forms_data['is_lab_equipped'],
				'lab_equipped_docs'=>$forms_data['lab_equipped_docs'],
				
				// Add New chemist_detail_docs Fields By pravin 22-07-2017
				'chemist_detail_docs'=>$forms_data['chemist_detail_docs'],
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
			
			if($ca_bevo_applicant=='yes'){				
				if(empty($forms_data['is_lab_equipped'])){ $returnValue = null ; }			
			}else{
				
				if(empty($forms_data['laboratory_name'])){ $returnValue = null ; } 
				if(empty($forms_data['street_address'])){ $returnValue = null ; }
				if(empty($forms_data['lab_email_id'])){ $returnValue = null ; }
				if(!filter_var($forms_data['laboratory_type'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
				if(!filter_var($forms_data['state'], FILTER_VALIDATE_INT)){ $returnValue = null ; }else{
					if(!filter_var($forms_data['district'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
				}
				if(!filter_var($forms_data['postal_code'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
				if(empty($forms_data['lab_mobile_no'])){ $returnValue = null ; }
				if(empty($forms_data['lab_fax_no'])){ $returnValue = null ; }
			}
			
			if(empty($section_form_details[0]['id'])){
				
				if($ca_bevo_applicant=='yes' || $forms_data['laboratory_type']==1){
					if(empty($forms_data['lab_equipped_docs']->getClientFilename())){ $returnValue = null ; }
					if(empty($forms_data['chemist_detail_docs']->getClientFilename())){ $returnValue = null ; }
				}else{
					if(empty($forms_data['consent_letter_docs']->getClientFilename())){ $returnValue = null ; }				
				}
			}
			
			return $returnValue;
			
		}
		
} ?>