<?php
	
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiCustomerTblDetailsTable extends Table{
	
		var $name = "DmiCustomerTblDetails";
		
		public $validate = array(
		
			'tbl_belongs_to_applicant'=>array(
					'rule'=>array('maxLength',10),				
				),											
			'tbl_belongs_docs'=>array(
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
			'tbl_consent_letter_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			'tbl_proposed_firm'=>array(
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
				$form_fields_details = Array ( 'id' => "",  'tbl_name' => "", 'tbl_registered' => "", 'trade_mark_registration' => "", 'tbl_registration_docs' => "", 'tbl_belongs_to_applicant' => "no", 
												'tbl_belongs_docs' => "", 'street_address' => "", 'state' => "", 'district' => "", 'postal_code' => "", 'created' => "", 'modified' => "", 
												'customer_id' => "", 'reffered_back_comment' => "", 'reffered_back_date' => "", 'once_card_no' => "", 'form_status' => "", 'customer_reply' => "", 
												'customer_reply_date' => "", 'approved_date' => "", 'user_email_id' => "", 'user_once_no' => "", 'current_level' => "", 
												'tbl_consent_letter_docs' => "", 'mo_comment' => "", 'mo_comment_date' => "", 'ro_reply_comment' => "", 'ro_reply_comment_date' => "", 
												'tbl_proposed_firm' => "", 'delete_mo_comment' => "", 'delete_ro_reply' => "", 'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 
												'ro_current_comment_to' => "",'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"");
			}
			
			$Dmi_all_tbls_detail = TableRegistry::getTableLocator()->get('DmiAllTblsDetails');
			$added_tbls_details = $Dmi_all_tbls_detail->tblsDetails();
			return array($form_fields_details,$added_tbls_details);
				
		}
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if($dataValidatation == 1 ){
				
				$CustomersController = new CustomersController;
				$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
				$section_form_details = $this->sectionFormDetails($customer_id);
				
				$htmlencoded_tbl_proposed_firm = htmlentities($forms_data['tbl_proposed_firm'], ENT_QUOTES);
				
				$post_input_request = $forms_data['tbl_belongs_to_applicant'];				
				$tbl_belongs_to_applicant = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($tbl_belongs_to_applicant == null){ return false;}
				
				if(!empty($forms_data['tbl_belongs_docs']->getClientFilename())){				
					
					$file_name = $forms_data['tbl_belongs_docs']->getClientFilename();
					$file_size = $forms_data['tbl_belongs_docs']->getSize();
					$file_type = $forms_data['tbl_belongs_docs']->getClientMediaType();
					$file_local_path = $forms_data['tbl_belongs_docs']->getStream()->getMetadata('uri');			
				
					$tbl_belongs_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

				}else{ $tbl_belongs_docs = $section_form_details[0]['tbl_belongs_docs']; }
				
				if(!empty($forms_data['tbl_consent_letter_docs']->getClientFilename())){
									
					$file_name = $forms_data['tbl_consent_letter_docs']->getClientFilename();
					$file_size = $forms_data['tbl_consent_letter_docs']->getSize();
					$file_type = $forms_data['tbl_consent_letter_docs']->getClientMediaType();
					$file_local_path = $forms_data['tbl_consent_letter_docs']->getStream()->getMetadata('uri');
							
					$tbl_consent_letter_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
				}else{ $tbl_consent_letter_docs = $section_form_details[0]['tbl_consent_letter_docs']; }
				
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
					'tbl_belongs_to_applicant'=>$tbl_belongs_to_applicant,
					'tbl_belongs_docs'=>$tbl_belongs_docs,
					'tbl_consent_letter_docs'=>$tbl_consent_letter_docs,
					'tbl_proposed_firm'=>$htmlencoded_tbl_proposed_firm,			
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
				'tbl_belongs_to_applicant'=>$forms_data['tbl_belongs_to_applicant'],
				'tbl_belongs_docs'=>$forms_data['tbl_belongs_docs'],
				'tbl_proposed_firm'=>$forms_data['tbl_proposed_firm'],
				'tbl_consent_letter_docs'=>$forms_data['tbl_consent_letter_docs'],
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
			
			if(empty($section_form_details[0]['id'])){
				
				if($forms_data['tbl_belongs_to_applicant'] == 'yes'){				
					if(empty($forms_data['tbl_belongs_docs']->getClientFilename())){ $returnValue = null ; }
				}
				if($forms_data['tbl_belongs_to_applicant'] == 'no'){					
					if(empty($forms_data['tbl_consent_letter_docs']->getClientFilename())){ $returnValue = null ; }
				}
				
			}
			
			if(empty($section_form_details[1][0])){ $returnValue = null ; }
			if(empty($forms_data['tbl_belongs_to_applicant'])){ $returnValue = null ; }

			if($forms_data['tbl_belongs_to_applicant'] == 'no'){
				if(empty($forms_data['tbl_proposed_firm'])){ $returnValue = null ; }
			}
			
			return $returnValue;
		}	
					
}

?>