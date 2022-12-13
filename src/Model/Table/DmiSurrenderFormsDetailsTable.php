<?php 
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;
	
class DmiSurrenderFormsDetailsTable extends Table{

	var $name = "DmiSurrenderFormsDetails";

	// Fetch form section all details
	public function sectionFormDetails($customer_id){
		
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		
		if($latest_id != null){
			$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
			
			$form_fields_details = $form_fields;
			
		}else{
			
			$form_fields_details = Array ( 'id'=>"", 'reason' =>"", 'document_for' => "", 
											'created' => "", 'modified' =>"", 'customer_id' => "", 'reffered_back_comment' => "",
											'reffered_back_date' => "", 'form_status' =>"", 'customer_reply' =>"", 'customer_reply_date' =>"", 'approved_date' => "",
											'current_level' => "",'mo_comment' =>"", 'mo_comment_date' => "", 'ro_reply_comment' =>"", 'ro_reply_comment_date' =>"", 'delete_mo_comment' =>"", 'delete_ro_reply' => "",
											'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "",
											'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>""); 
			
		}
		
		$Dmi_person_detail = TableRegistry::getTableLocator()->get('DmiAdpPersonDetails');
	
		$person_details = $Dmi_person_detail->laboratoryPersonDetails();	

		return array($form_fields_details,$person_details);
			
	}		
	
	
	// save or update form data and comment reply by applicant
	public function saveFormDetails($customer_id,$forms_data){

		
		$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
		
		if ($dataValidatation == 1 ) {
			
			$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
			$CustomersController = new CustomersController;
			$section_form_details = $this->sectionFormDetails($customer_id);
			$firm_details = $Dmi_firm->firmDetails($customer_id);
			$reason = htmlentities($forms_data['reason'], ENT_QUOTES);
			
			//file uploading					
			if(!empty($forms_data['document_for']->getClientFilename())){
					
				$file_name = $forms_data['document_for']->getClientFilename();
				$file_size = $forms_data['document_for']->getSize();
				$file_type = $forms_data['document_for']->getClientMediaType();
				$file_local_path = $forms_data['document_for']->getStream()->getMetadata('uri');
				$document_for = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
							
			} else { $document_for = $section_form_details[0]['document_for'];}
			
			// If applicant have referred back on give section				
			if ($section_form_details[0]['form_status'] == 'referred_back') {
				
				$max_id = $section_form_details[0]['id'];
				$htmlencoded_reply = htmlentities($forms_data['customer_reply'], ENT_QUOTES);
				$customer_reply_date = date('Y-m-d H:i:s');
				
				if (!empty($forms_data['cr_comment_ul']->getClientFilename())) {
					
					$file_name = $forms_data['cr_comment_ul']->getClientFilename();
					$file_size = $forms_data['cr_comment_ul']->getSize();
					$file_type = $forms_data['cr_comment_ul']->getClientMediaType();
					$file_local_path = $forms_data['cr_comment_ul']->getStream()->getMetadata('uri');
					
					$cr_comment_ul = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
				} else { $cr_comment_ul = null; }
					
			} else {
			
				$htmlencoded_reply = ''; 
				$max_id = ''; 
				$customer_reply_date = '';
				$cr_comment_ul = null;	
			}

			if (empty($section_form_details[0]['created'])) {
				$created = date('Y-m-d H:i:s'); 
			} else {
				//added date function on 31-05-2021 by Amol to convert date format, as saving null
				$created = $CustomersController->Customfunctions->changeDateFormat($section_form_details[0]['created']);
			}
			
			$newEntity = $this->newEntity(array(

				'id'=>$max_id,
				'customer_id'=>$customer_id,
				'any_other_info'=>$reason,
				'any_other_upload'=>$document_for,
				'form_status'=>'saved',
				'customer_reply'=>$htmlencoded_reply,
				'customer_reply_date'=>$customer_reply_date,
				'cr_comment_ul'=>$cr_comment_ul,
				'created'=>$created,
				'modified'=>date('Y-m-d H:i:s'),
			));
			
			if ($this->save($newEntity)) { return 1; };	
			
		} else { return false; }	
		
	}
	
	
	// To save 	RO/SO referred back  and MO reply comment
	public function saveReferredBackComment ($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to) {
		// Import another model in this model	
		
		$logged_in_user = $_SESSION['username'];
		$current_level = $_SESSION['current_level'];
		
		$DmiOldApplicationDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');
		
		$CustomersController = new CustomersController;
		$oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);
		
		//added date function on 31-05-2021 by Amol to convert date format, as saving null
		$created_date = $CustomersController->Customfunctions->changeDateFormat($forms_data['created']);
		
		if ($reffered_back_to == 'Level3ToApplicant') {
			
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
			
		} elseif ($reffered_back_to == 'Level1ToLevel3') {
			
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
			
		} elseif ($reffered_back_to == 'Level3ToLevel1') { // this '1' is added to 'level' as it was not there for RO - MO communication on AKASH [19-08-2022]
			
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
			'rr_comment_ul'=>$rr_comment_ul,
			
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
		
		$returnValue = true;
		$section_form_details = $this->sectionFormDetails($customer_id);
		if(empty($section_form_details[0]['id'])){
			
			if(empty($forms_data['document_for']->getClientFilename())){ $returnValue = null ; }
		}
	
		
		return $returnValue;
		
	}

} ?>