<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\CustomersController;


class DmiChemistProfileDetailsTable extends Table{

	var $name = "DmiChemistProfileDetails";
	var $userTable = 'dmi_chemist_profile_details';

	public function sectionFormDetails($chemist_id) {

		$result = array();
		

		$latest_id = $this->find('list',array('conditions'=>array('customer_id IS'=>$chemist_id)))->toArray();
		if($latest_id != null){
			$result = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->toArray();			
		}

		 $DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
		 $get_registered_details = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$chemist_id)))->first();
		 $registered_details = $get_registered_details;

		
 

		 if(empty($result)){
				$result = array();
				$result[0]['id'] = '';
				$result[0]['customer_id'] = '';
				$result[0]['first_name'] = $registered_details['chemist_fname'];
				$result[0]['last_name'] = $registered_details['chemist_lname'];
				$result[0]['state'] = '';
				$result[0]['district'] = '';
				$result[0]['pin_code'] = '';
				$result[0]['email'] = $registered_details['email'];
				$result[0]['mobile_no'] = $registered_details['mobile'];
				$result[0]['dob'] = $registered_details['dob'];
				$result[0]['gender'] = '';
				#$result[0]['pan_no'] = '';
				$result[0]['address'] = '';
				$result[0]['address_1'] = '';
				$result[0]['profile_photo'] = '';
				$result[0]['signature_photo'] = '';
				$result[0]['form_status'] = '';
				$result[0]['created'] = '';
				$result[0]['modified'] = '';
				$result[0]['customer_reply'] = '';
				$result[0]['current_level'] = '';
				$result[0]['mo_comment'] = '';
				$result[0]['ro_reply_comment'] = '';
				$result[0]['delete_mo_comment'] = '';
				$result[0]['customer_reply_date'] = '';
				$result[0]['rb_comment_ul'] = '';
				$result[0]['mo_comment_ul'] = '';
				$result[0]['rr_comment_ul'] = '';
				$result[0]['cr_comment_ul'] = '';
				$result[0]['document'] = '';
				$result[0]['document_id_no'] = '';
		}else{

			$section_id = $_SESSION['section_id'];
			$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');
			$commentDetails = $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id,'section_id IS'=>$section_id,'is_latest'=>1)))->first();
			
			if(!empty($commentDetails)){
				$reffered_back_comment = $commentDetails['comments'];
				$reffered_back_date = $commentDetails['comment_dt'];
			}else{
				$reffered_back_comment = '';
				$reffered_back_date = '';
			}
			$result[0]['reffered_back_comment'] = $reffered_back_comment;
			$result[0]['reffered_back_date'] = $reffered_back_date;
			
		}
		 //$result['count'] = $count;
		//print_r($result); exit;
		 return array($result[0]);
	}

	public function saveFormDetails($chemist_id,$forms_data) {


		$result = false;
		$dataValidatation = $this->postDataValidation($forms_data);
		$date = date('Y-m-d H:i:s');

		$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
		$get_registered_details = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$chemist_id)))->first();
		$registered_details = $get_registered_details;

		if($dataValidatation == 1 ){

			$section_form_details = $this->sectionFormDetails($chemist_id);

			$id = $section_form_details[0]['id'];
			$status = 'saved';
			$created = date('Y-m-d H:i:s');
			$CustomersController = new CustomersController;

			$table = 'DmiStates';
			$post_input_request = $forms_data['state'];
			$state = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function

			$table = 'DmiDistricts';
			$post_input_request = $forms_data['district'];
			$district = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function

			$pin_code = htmlentities($forms_data['pin_code'], ENT_QUOTES);

			//$email = htmlentities($forms_data['email'), ENT_QUOTES);
			//$mobile_no = htmlentities($forms_data['mobile_no'), ENT_QUOTES);
			//$dob = $this->->Customfunctions->dateFormatCheck($forms_data['dob'));

			$gender = $CustomersController->Customfunctions->radioButtonInputCheck($forms_data['gender']);//calling librabry function]

			if ($gender == null) {

				return false;
			}

			#$pan_no = htmlentities($forms_data['pan_no'], ENT_QUOTES);
			$address = htmlentities($forms_data['address'], ENT_QUOTES);
			$address_1 = htmlentities($forms_data['address_1'], ENT_QUOTES);
			$document = htmlentities($forms_data['document'], ENT_QUOTES); #This is added by Akash on the 09-08-2022 
			$document_id_no = htmlentities($forms_data['document_id_no'], ENT_QUOTES); #This is added by Akash on the 09-08-2022

			if (!empty($forms_data['profile_photo']->getClientFilename())) {

				$attchment = $forms_data['profile_photo'];
				$file_name = $attchment->getClientFilename();
				$file_size = $attchment->getSize();
				$file_type = $attchment->getClientMediaType();
				$file_local_path = $attchment->getStream()->getMetadata('uri');
				// calling file uploading function
				$profile_photo = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path);

			} else {

				$profile_photo = $section_form_details[0]['profile_photo'];
			}

			if (!empty($forms_data['signature_photo']->getClientFilename())) {

				$attchment = $forms_data['signature_photo'];
				$file_name = $attchment->getClientFilename();
				$file_size = $attchment->getSize();
				$file_type = $attchment->getClientMediaType();
				$file_local_path = $attchment->getStream()->getMetadata('uri');
				// calling file uploading function
				$signature_photo = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path);

			} else {

				$signature_photo = $section_form_details[0]['signature_photo'];
			}

			
			// If applicant have referred back on give section
			
				
			$section_id = $_SESSION['section_id'];
			$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');

			$currCommentRecord =  $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id'=>$chemist_id,'section_id'=>$section_id,'is_latest'=>'1')))->first();
			//print_r($currCommentRecord); exit;
			if(!empty($currCommentRecord)){
				$commentid = $currCommentRecord['id'];
				$reply_to = $currCommentRecord['comment_by'];
			}else{
				$commentid ='';
				$reply_to = '';
			}
			
			if(!empty($reply_to))
			{
				$comment = htmlentities($forms_data['reffered_back_comment'], ENT_QUOTES);
				
				$newEntity = $Dmi_chemist_comment->newEntity(array(
					'id'=>$commentid,
					'reply_by'=>$chemist_id,
					'reply_to'=>$reply_to,
					'reply_comment'=>$comment,
					'reply_dt'=>date('Y-m-d H:i:s')			
				));
				$Dmi_chemist_comment->save($newEntity);
			}	

			if(empty($section_form_details[0]['created'])){  $created = date('Y-m-d H:i:s'); }
			//added date function on 31-05-2021 by Amol to convert date format, as saving null
			else{ $created = $CustomersController->Customfunctions->changeDateFormat($section_form_details[0]['created']); }
			
			$DmiChemistProfileDetailsEntity = $this->newEntity(array(

				'id'=>$id,
				'customer_id'=>$chemist_id,
				'first_name'=>$registered_details['chemist_fname'],
				'last_name'=>$registered_details['chemist_lname'],
				'state'=>$state,
				'district'=>$district,
				'pin_code'=>$pin_code,
				'email'=>$registered_details['email'],
				'mobile_no'=>$registered_details['mobile'],
				'dob'=>$registered_details['dob'],
				'gender'=>$gender,
				#'pan_no'=>$pan_no,
				'address'=>$address,
				'address_1'=>$address_1,
				'profile_photo'=>$profile_photo,
				'signature_photo'=>$signature_photo,
				'form_status'=>$status,
				'created'=>$created,
				'modified'=>date('Y-m-d H:i:s'),
				'is_latest'=>1,
				'document'=>$document, #This is added by Akash on the 09-08-2022 
				'document_id_no'=>$document_id_no #This is added by Akash on the 09-08-2022 
			));

			if($this->save($DmiChemistProfileDetailsEntity)) {
				return true;
			}

		} else {
			return false;
		}



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
			'first_name'=>$forms_data['first_name'],
			'last_name'=>$forms_data['last_name'],
			'state'=>$forms_data['state'],
			'district'=>$forms_data['district'],
			'pin_code'=>$forms_data['pin_code'],
			'email'=>base64_encode($forms_data['email']),//for email encoding
			'mobile_no'=>$forms_data['mobile_no'],
			'address_1'=>$forms_data['address_1'],
			'dob'=>$forms_data['dob'],
			'gender'=>$forms_data['gender'],
			#'pan_no'=>$forms_data['pan_no'],
			'address'=>$forms_data['address'],
			'profile_photo'=>$forms_data['profile_photo'],
			'signature_photo'=>$forms_data['signature_photo'],
			'form_status'=>$forms_data['form_status'],
			'is_latest'=>$forms_data['is_latest'],
			'created'=>$created_date,
			'modified'=>date('Y-m-d H:i:s'),
			'form_status'=>$form_status,
			'reffered_back_comment'=>$reffered_back_comment,
			'reffered_back_date'=>$reffered_back_date,
			'rb_comment_ul'=>$rb_comment_ul,
			'user_email_id'=>$_SESSION['username'],			
			'current_level'=>$current_level,
			'ro_current_comment_to'=>$ro_current_comment_to,	
			'mo_comment'=>$mo_comment,
			'mo_comment_date'=>$mo_comment_date,
			'mo_comment_ul'=>$mo_comment_ul,
			'ro_reply_comment'=>$ro_reply_comment,
			'ro_reply_comment_date'=>$ro_reply_comment_date,
			'rr_comment_ul'=>$rr_comment_ul,
			'document'=>$document, #This is added by Akash on the 09-08-2022 
			'document_id_no'=>$document_id_no #This is added by Akash on the 09-08-2022 

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


	public function postDataValidation($forms_data){

			return true;

	}

} ?>
