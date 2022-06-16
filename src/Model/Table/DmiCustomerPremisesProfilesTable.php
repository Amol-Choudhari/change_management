<?php 

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiCustomerPremisesProfilesTable extends Table{

		var $name = "DmiCustomerPremisesProfiles";
		
		public $validate = array(
											
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
			'reffered_back_comment'=>array(
					'rule'=>array('maxLength',200),
				),
			'once_card_no'=>array(
					'rule'=>array('maxLength',50),
				),
			'customer_reply'=>array(
					'rule'=>array('maxLength',200),
				),
			'separate_tanks_used'=>array(
					'rule'=>array('maxLength',10),
				),
			'separate_tanks_used'=>array(
					'rule'=>array('maxLength',10),
				),
			'locking_for_storage_tanks'=>array(
					'rule'=>array('maxLength',10),
				),
			'bevo_mills_address_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'separate_tanks_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'separate_tanks_docs'=>array(
					'rule'=>array('maxLength',200),
				),
				
		);
		
		
		// Fetch form section all details
		public function sectionFormDetails($customer_id)
		{
			$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id'=>$customer_id)))->toArray();
					
			if($latest_id != null){
				$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
				$form_fields_details = $form_fields;
			
			}else{
				$form_fields_details = Array (  'id'=>"", 'street_address' => "", 'state' => "", 'district' => "", 'postal_code' => "", 'belongs_to_applicant' => "",
												'premises_owner_name' => "",'premises_docs' => "",'have_reg_no' => "",'vopa_certificate' => "",'vopa_certificate_docs' => "",
												'have_vat_cst_no' =>"", 'vat_cst_no' =>"", 'vat_cst_docs' =>"", 'created' => "", 'modified' => "", 'customer_id' => "", 
												'reffered_back_comment' => "",'reffered_back_date' => "",'once_card_no' => "",'form_status' => "", 'customer_reply' => "",
												'customer_reply_date' =>"", 'approved_date' => "", 'first_rep_f_name' =>"", 'first_rep_m_name' =>"", 'first_rep_l_name' =>"", 
												'first_rep_mobile' => "", 'first_rep_signature' => "",'second_rep_f_name' => "", 'second_rep_m_name' =>"", 'second_rep_l_name' => "", 
												'second_rep_mobile' => "", 'second_rep_signature' => "", 'user_email_id' => "", 'user_once_no' => "", 'current_level' => "", 
												'separate_tanks_used' => "yes", 'locking_for_storage_tanks' => "yes", 'bevo_mills_address_docs' => "",'separate_tanks_docs' => "", 
												'mo_comment' =>"", 'mo_comment_date' =>"", 'ro_reply_comment' => "", 'ro_reply_comment_date' => "", 'delete_mo_comment' => "",
												'delete_ro_reply' => "",'delete_ro_referred_back' => "",'delete_customer_reply' => "",'ro_current_comment_to' => "",
												'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"");  
			}
			$Dmi_all_tanks_detail = TableRegistry::getTableLocator()->get('DmiAllTanksDetails');
			$oil_type = null; $user_email_id = null;
			$added_tanks_details = $Dmi_all_tanks_detail->tanksDetails($user_email_id,$oil_type,'Application');
			
			$Dmi_district = TableRegistry::getTableLocator()->get('DmiDistricts');
			$districts_list = $Dmi_district->find('list', array('valueField'=>'district_name', 'conditions'=>array('state_id'=>$form_fields_details['state'],'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->toArray();
			asort($districts_list);
			
			return array($form_fields_details,$added_tanks_details,$districts_list);
		
		
		}
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
		
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if($dataValidatation == 1 ){
				
				$CustomersController = new CustomersController;
				$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
				$section_form_details = $this->sectionFormDetails($customer_id);
				
				
				$htmlencoded_street_address = htmlentities($forms_data['street_address'], ENT_QUOTES);
				$htmlencoded_postal_code = htmlentities($forms_data['postal_code'], ENT_QUOTES);
				
				$table = 'DmiStates';
				$post_input_request = $forms_data['state'];
				$state = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
				
				$table = 'DmiDistricts';
				$post_input_request = $forms_data['district'];
				$district = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
				
				if($ca_bevo_applicant == 'no')		
				{
					$separate_tanks_used = null;
					$locking_for_storage_tanks = null;
					$bevo_mills_address_docs = null;
					$separate_tanks_docs = null;
					
				}elseif($ca_bevo_applicant == 'yes'){
					
					//checking radio buttons input
					$post_input_request = $forms_data['separate_tanks_used'];				
					$separate_tanks_used = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($separate_tanks_used == null){ return false;}
					
					$post_input_request = $forms_data['locking_for_storage_tanks'];				
					$locking_for_storage_tanks = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
					if($locking_for_storage_tanks == null){ return false;}
					
					
					//uploading files											
					if(!empty($forms_data['bevo_mills_address_docs']->getClientFilename())){

						$file_name = $forms_data['bevo_mills_address_docs']->getClientFilename();
						$file_size = $forms_data['bevo_mills_address_docs']->getSize();
						$file_type = $forms_data['bevo_mills_address_docs']->getClientMediaType();
						$file_local_path = $forms_data['bevo_mills_address_docs']->getStream()->getMetadata('uri');

						$bevo_mills_address_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
						
					}else{ $bevo_mills_address_docs = $section_form_details[0]['bevo_mills_address_docs']; }
					
					if(!empty($forms_data['separate_tanks_docs']->getClientFilename())){

						$file_name = $forms_data['separate_tanks_docs']->getClientFilename();
						$file_size = $forms_data['separate_tanks_docs']->getSize();
						$file_type = $forms_data['separate_tanks_docs']->getClientMediaType();
						$file_local_path = $forms_data['separate_tanks_docs']->getStream()->getMetadata('uri');

						$separate_tanks_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function					
					
					}else{ $separate_tanks_docs = $section_form_details[0]['separate_tanks_docs']; }
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
					'street_address'=>$htmlencoded_street_address,
					'state'=>$state,
					'district'=>$district,
					'postal_code'=>$htmlencoded_postal_code,
					//fields for BEVO starts
					'separate_tanks_used'=>$separate_tanks_used,
					'separate_tanks_docs'=>$separate_tanks_docs,
					'bevo_mills_address_docs'=>$bevo_mills_address_docs,
					'locking_for_storage_tanks'=>$locking_for_storage_tanks,
					'form_status'=>'saved',
					'customer_reply'=>$htmlencoded_reply,
					'customer_reply_date'=>$customer_reply_date,
					'cr_comment_ul'=>$cr_comment_ul,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s')				
				));
				
				if ($this->save($newEntity)){ return 1; };
				
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
				'bevo_mills_address_docs'=>$forms_data['bevo_mills_address_docs'],
				'separate_tanks_used'=>$forms_data['separate_tanks_used'],
				'separate_tanks_docs'=>$forms_data['separate_tanks_docs'],
				'locking_for_storage_tanks'=>$forms_data['locking_for_storage_tanks'],
				'street_address'=>$forms_data['street_address'],
				'state'=>$forms_data['state'],
				'district'=>$forms_data['district'],
				'postal_code'=>$forms_data['postal_code'],
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
			
			if($this->save($newEntity)){  return true; }
		}
		
		
		public function postDataValidation($customer_id,$forms_data){
			
			$returnValue = true;
			$section_form_details = $this->sectionFormDetails($customer_id);
			$CustomersController = new CustomersController;
			$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id);
			
			if($ca_bevo_applicant=='yes'){
				if(empty($section_form_details[0]['id'])){
					if($forms_data['separate_tanks_used'] == 'yes'){
						if(empty($forms_data['separate_tanks_docs']->getClientFilename())){ $returnValue = null ; }
					}
					if(empty($forms_data['bevo_mills_address_docs']->getClientFilename())){ $returnValue = null ; }
				}else{
					if($forms_data['separate_tanks_used'] == 'yes' && $section_form_details[0]['separate_tanks_docs']==""){
						if(empty($forms_data['separate_tanks_docs']->getClientFilename())){ $returnValue = null ; }
					}
				}			
				if(empty($section_form_details[1][2])){ $returnValue = null ; }			
				if(empty($forms_data['separate_tanks_used'])){ $returnValue = null ; }	
				if(empty($forms_data['locking_for_storage_tanks'])){ $returnValue = null ; }
			}
			
			if(empty($forms_data['street_address'])){ $returnValue = null ; }
			if(!filter_var($forms_data['postal_code'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
			if(!filter_var($forms_data['state'], FILTER_VALIDATE_INT)){ $returnValue = null ; }else{
				if(!filter_var($forms_data['district'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
			}
			
			return $returnValue;
		}
		

} ?>