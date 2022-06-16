<?php
    namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	use Cake\Validation\Validator;

	class DmiLaboratoryFirmDetailsTable extends Table{

		var $name = "DmiLaboratoryFirmDetails";

		public $validate = array(

				'customer_id'=>array(
					'rule'=>array('maxLength',30),
				),
				'customer_once_card_no'=>array(
					'rule'=>array('maxLength',200),
				),
				'user_email_id'=>array(
					'rule'=>array('maxLength',50),
				),
				'user_once_no'=>array(
					'rule'=>array('maxLength',200),
				),
				'form_status'=>array(
					'rule'=>array('maxLength',20),
				),
				'current_level'=>array(
					'rule'=>array('maxLength',50),
				),
				'reffered_back_comment'=>array(
					'rule'=>array('maxLength',200),
				),
				'customer_reply'=>array(
					'rule'=>array('maxLength',200),
				),
				'mo_comment'=>array(
					'rule'=>array('maxLength',200),
				),
				'ro_reply_comment'=>array(
					'rule'=>array('maxLength',200),
				),
				'ro_reply_comment'=>array(
					'rule'=>array('maxLength',200),
				),
				'laboratory_type'=>array(
					'rule1'=>array(
							'rule'=>array('maxLength',100),
							'allowEmpty'=>false,
							'last'=>false,
						),
					'rule2'=>array(
							'rule'=>'Numeric',
						)
				),
				'business_type'=>array(
					'rule1'=>array(
							'rule'=>array('maxLength',100),
							'allowEmpty'=>false,
							'last'=>false,
						),
					'rule2'=>array(
							'rule'=>'Numeric',
						)
				),
				'business_type_docs'=>array(
					'rule'=>array('maxLength',200),
				),
				'delete_mo_comment'=>array(
					'rule'=>array('maxLength',10),
				),
				'delete_ro_reply'=>array(
					'rule'=>array('maxLength',10),
				),
				'delete_ro_referred_back'=>array(
					'rule'=>array('maxLength',10),
				),
				'delete_customer_reply'=>array(
					'rule'=>array('maxLength',10),
				),
				'ro_current_comment_to'=>array(
					'rule'=>array('maxLength',100),
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

				$form_fields_details = Array ( 	'id' =>"", 'customer_id' =>"", 'customer_once_card_no' =>"", 'user_email_id' =>"", 'user_once_no' =>"", 'form_status' =>"", 'approved_date' =>"", 'current_level' =>"",
												'reffered_back_comment' =>"", 'reffered_back_date' =>"", 'customer_reply' =>"", 'customer_reply_date' =>"", 'mo_comment' =>"", 'mo_comment_date' =>"", 'ro_reply_comment' =>"",
												'ro_reply_comment_date' =>"", 'created' =>"", 'modified' =>"", 'laboratory_type' =>"", 'business_type' =>"", 'business_type_docs' =>"", 'establishment_date' =>"",
												'delete_mo_comment' =>"", 'delete_ro_reply' =>"", 'delete_ro_referred_back' =>"", 'delete_customer_reply' =>"", 'ro_current_comment_to' =>"",
												'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"");

			}

			$Dmi_laboratory_type = TableRegistry::getTableLocator()->get('DmiLaboratoryTypes');
			$laboratory_types = $Dmi_laboratory_type->find('list', array('valueField'=>'laboratory_type', 'conditions'=>array('delete_status IS NULL')))->toArray();

			$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
			$firm_detail = $Dmi_firm->firmDetails($customer_id);

			$M_commodity = TableRegistry::getTableLocator()->get('MCommodity');
			$sub_commodities_details = explode(',',$firm_detail['sub_commodity']);
			$sub_commodities_detail = $M_commodity->find('list', array('valueField'=>'commodity_name','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$sub_commodities_details)))->toArray();

			$DmiOldApplicationCertificateDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');
			$OldApplicationCertificateDetails = $DmiOldApplicationCertificateDetails->oldApplicationCertificationDetails($customer_id);

			return array($form_fields_details,$laboratory_types,$sub_commodities_detail,$OldApplicationCertificateDetails);

		}

		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){

			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);

			if($dataValidatation == 1 ){

				$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
				$DmiOldApplicationDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');

				$CustomersController = new CustomersController;
				$section_form_details = $this->sectionFormDetails($customer_id);

				$oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);

				$htmlencoded_establishment_date = htmlentities($forms_data['establishment_date'], ENT_QUOTES);
				$htmlencoded_establishment_date = $CustomersController->Customfunctions->dateFormatCheck($htmlencoded_establishment_date);

				$table = 'DmiLaboratoryTypes';
				$post_input_request = $forms_data['laboratory_type'];
				$laboratory_type = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function

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
					'customer_once_card_no'=>$_SESSION['once_card_no'],

					'laboratory_type'=>$laboratory_type,
					'business_type'=>$business_type,
					'business_type_docs'=>$business_type_docs,
					'establishment_date'=>$htmlencoded_establishment_date,

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
		public function saveReferredBackComment($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to)
		{
			// Import another model in this model

			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);

			if($dataValidatation == 1 && !empty($comment) ){

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
					'customer_once_card_no'=>$forms_data['customer_once_card_no'],
					'laboratory_type'=>$forms_data['laboratory_type'],
					'business_type'=>$forms_data['business_type'],
					'business_type_docs'=>$forms_data['business_type_docs'],
					'establishment_date'=>$forms_data['establishment_date'],
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

			}else{ return false; }
		}

		public function postDataValidation($customer_id,$forms_data){

			$returnValue = true;
			$CustomersController = new CustomersController;

			$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiAllDirectorsDetails');
			$added_directors_details = $DmiAllDirectorsDetails->allDirectorsDetail($customer_id);
			$oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);

			$section_form_details = $this->sectionFormDetails($customer_id);

			if(!filter_var($forms_data['business_type'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
			if(!filter_var($forms_data['laboratory_type'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
			if(empty($section_form_details[0]['id'])){
				if($forms_data['business_type'] != 1){
					if(empty($forms_data['business_type_docs']->getClientFilename())){ $returnValue = null ; }
				}
			}
			if(empty($forms_data['establishment_date'])){ $returnValue = null ; }

			if($oldapplication == 'yes'){
				if(empty($section_form_details[0]['id'])){
					if(empty($forms_data['old_certification_pdf']->getClientFilename())){ $returnValue = null ; }
					if(empty($forms_data['old_application_docs']->getClientFilename())){ $returnValue = null ; }
				}
				if(empty($added_directors_details)){ $returnValue = null ; }
			}

			return $returnValue;
		}


	}

?>
