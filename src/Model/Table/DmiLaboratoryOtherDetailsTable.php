<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

	class DmiLaboratoryOtherDetailsTable extends Table {
		
		var $name = "DmiLaboratoryOtherDetails";
		
		public $validate = array(
		
			'customer_id'=>array(
				'rule'=>array('maxLength',20),				
			),
			'customer_once_card_no'=>array(
				'rule'=>array('maxLength',200),				
			),
			'user_email_id'=>array(
				'rule'=>array('maxLength',100),				
			),
			'user_once_no'=>array(
				'rule'=>array('maxLength',200),				
			),
			'form_status'=>array(
				'rule'=>array('maxLength',50),				
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
			'premises_belongs_to'=>array(
				'rule'=>array('maxLength',100),				
			),
			'owner_name'=>array(
				'rule'=>array('maxLength',100),				
			),
			'premises_belongs_to_docs'=>array(
				'rule'=>array('maxLength',100),				
			),
			'total_area_covered'=>array(
				'rule'=>array('maxLength',100),				
			),
			'total_area_covered_docs'=>array(
				'rule'=>array('maxLength',100),				
			),
			'is_accreditated'=>array(
				'rule'=>array('maxLength',20),				
			),
			'accreditation_no'=>array(
				'rule'=>array('maxLength',50),				
			),
			'accreditation_docs'=>array(
				'rule'=>array('maxLength',100),				
			),
			'is_laboretory_equipped'=>array(
				'rule'=>array('maxLength',20),				
			),
			'is_laboretory_equipped_docs'=>array(
				'rule'=>array('maxLength',100),				
			),
			'chemists_employed_docs'=>array(
				'rule'=>array('maxLength',100),				
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
			'apeda_docs'=>array(
				'rule'=>array('maxLength',200),				
			),
			'lab_ceo_name'=>array(
				'rule'=>array('maxLength',50),				
			),
		
		);
		

		// Fetch form section all details
		public function sectionFormDetails($customer_id) {
			
			$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
					
			if ($latest_id != null) {
				$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
				$form_fields_details = $form_fields;				
			} else {
				
				$form_fields_details = Array ( 	 'id' => '', 'customer_id' => '', 'customer_once_card_no' => '', 'user_email_id' => '', 'user_once_no' => '', 'form_status' => '', 'approved_date' => '', 'current_level' => '',
												 'reffered_back_comment' => '', 'reffered_back_date' => '', 'customer_reply' => '', 'customer_reply_date' => '', 'mo_comment' => '', 'mo_comment_date' => '', 'ro_reply_comment' => '', 'other_information' => '',
												 'ro_reply_comment_date' => '', 'created' => '', 'modified' => '', 'premises_belongs_to' => 'no', 'owner_name' => '', 'premises_belongs_to_docs' => '', 'total_area_covered' => '',  'chemists_employed_docs' => '',
												 'total_area_covered_docs' => '', 'is_accreditated' => 'no', 'accreditation_no' => '', 'accreditation_scope' => '', 'accreditation_docs' => '', 'is_laboretory_equipped' => 'no', 'is_laboretory_equipped_docs' => '', 
												 'delete_mo_comment' => '', 'delete_ro_reply' => '', 'delete_ro_referred_back' => '', 'delete_customer_reply' => '', 'ro_current_comment_to' => '', 'apeda_docs' => '', 'lab_ceo_name' => '',
												 'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"",'nabl_accreditated_upto'=>""); 
				
			}

			$Dmi_laboratory_chemists_detail = TableRegistry::getTableLocator()->get('DmiLaboratoryChemistsDetails');
			$chemist_details = $Dmi_laboratory_chemists_detail->laboratoryChemistDetails();			
			//print_r($chemist_details); exit;
			return array($form_fields_details,$chemist_details);
				
		}		
		
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data) {
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if ($dataValidatation == 1 ) {
				
				$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
				
				$CustomersController = new CustomersController;
				$section_form_details = $this->sectionFormDetails($customer_id);
				$firm_details = $Dmi_firm->firmDetails($customer_id);
							
				$export_unit_status = $CustomersController->Customfunctions->checkApplicantExportUnit($customer_id);
							
				$htmlencoded_owner_name = htmlentities($forms_data['owner_name'], ENT_QUOTES);
				$htmlencoded_total_area_covered = htmlentities($forms_data['total_area_covered'], ENT_QUOTES);
				$htmlencoded_accreditation_no = htmlentities($forms_data['accreditation_no'], ENT_QUOTES);
				$htmlencoded_accreditation_scope = htmlentities($forms_data['accreditation_scope'], ENT_QUOTES);
				$htmlencoded_other_information = htmlentities($forms_data['other_information'], ENT_QUOTES);
				
				//new field added on 28-09-2021 by Amol
				$nabl_accreditated_upto = htmlentities($forms_data['nabl_accreditated_upto'], ENT_QUOTES);
				$nabl_accreditated_upto = $CustomersController->Customfunctions->dateFormatCheck($nabl_accreditated_upto);

				if ($export_unit_status == 'yes')
				{			
					$htmlencoded_lab_ceo_name = htmlentities($forms_data['lab_ceo_name'], ENT_QUOTES);
					
					//APEDA cert. upload
					if (!empty($forms_data['apeda_docs']->getClientFilename())) {
						
						$file_name = $forms_data['apeda_docs']->getClientFilename();
						$file_size = $forms_data['apeda_docs']->getSize();
						$file_type = $forms_data['apeda_docs']->getClientMediaType();
						$file_local_path = $forms_data['apeda_docs']->getStream()->getMetadata('uri');
					
						$apeda_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
						
					} else { $apeda_docs = $section_form_details[0]['apeda_docs']; }
					
				} else {
					
					$htmlencoded_lab_ceo_name = null;
					$apeda_docs = null;
				}
					
							
				$post_input_request = $forms_data['premises_belongs_to'];								
				$premises_belongs_to = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if ($premises_belongs_to == null) { return false;}

				$post_input_request = $forms_data['is_accreditated'];							
				$is_accreditated = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if ($is_accreditated == null) { return false;}
					
				$post_input_request = $forms_data['is_laboretory_equipped'];							
				$is_laboretory_equipped = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if ($is_laboretory_equipped == null) { return false;}
				
				//file uploading					
				if (!empty($forms_data['premises_belongs_to_docs']->getClientFilename())) {				
					
					$file_name = $forms_data['premises_belongs_to_docs']->getClientFilename();
					$file_size = $forms_data['premises_belongs_to_docs']->getSize();
					$file_type = $forms_data['premises_belongs_to_docs']->getClientMediaType();
					$file_local_path = $forms_data['premises_belongs_to_docs']->getStream()->getMetadata('uri');
					
					$premises_belongs_to_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				} else { $premises_belongs_to_docs = $section_form_details[0]['premises_belongs_to_docs']; }
				
				if (!empty($forms_data['total_area_covered_docs']->getClientFilename())) {				
					
					$file_name = $forms_data['total_area_covered_docs']->getClientFilename();
					$file_size = $forms_data['total_area_covered_docs']->getSize();
					$file_type = $forms_data['total_area_covered_docs']->getClientMediaType();
					$file_local_path = $forms_data['total_area_covered_docs']->getStream()->getMetadata('uri');
					
					$total_area_covered_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				} else { $total_area_covered_docs = $section_form_details[0]['total_area_covered_docs']; }
				
				if (!empty($forms_data['accreditation_docs']->getClientFilename())) {				
					
					$file_name = $forms_data['accreditation_docs']->getClientFilename();
					$file_size = $forms_data['accreditation_docs']->getSize();
					$file_type = $forms_data['accreditation_docs']->getClientMediaType();
					$file_local_path = $forms_data['accreditation_docs']->getStream()->getMetadata('uri');
					
					$accreditation_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				} else { $accreditation_docs = $section_form_details[0]['accreditation_docs']; }
							
							
				if (!empty($forms_data['is_laboretory_equipped_docs']->getClientFilename())) {				
					
					$file_name = $forms_data['is_laboretory_equipped_docs']->getClientFilename();
					$file_size = $forms_data['is_laboretory_equipped_docs']->getSize();
					$file_type = $forms_data['is_laboretory_equipped_docs']->getClientMediaType();
					$file_local_path = $forms_data['is_laboretory_equipped_docs']->getStream()->getMetadata('uri');
					
					$is_laboretory_equipped_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				} else { $is_laboretory_equipped_docs = $section_form_details[0]['is_laboretory_equipped_docs']; }	


				if (!empty($forms_data['chemists_employed_docs']->getClientFilename())) {				
					
					$file_name = $forms_data['chemists_employed_docs']->getClientFilename();
					$file_size = $forms_data['chemists_employed_docs']->getSize();
					$file_type = $forms_data['chemists_employed_docs']->getClientMediaType();
					$file_local_path = $forms_data['chemists_employed_docs']->getStream()->getMetadata('uri');
					
					$chemists_employed_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				} else { $chemists_employed_docs = $section_form_details[0]['chemists_employed_docs']; }

					
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
					'once_card_no'=>$_SESSION['once_card_no'],				
					'premises_belongs_to'=>$premises_belongs_to,
					'owner_name'=>$htmlencoded_owner_name,
					'premises_belongs_to_docs'=>$premises_belongs_to_docs,											
					'total_area_covered'=>$htmlencoded_total_area_covered,
					'total_area_covered_docs'=>$total_area_covered_docs,
					'is_accreditated'=>$is_accreditated,
					'accreditation_no'=>$htmlencoded_accreditation_no,
					'accreditation_scope'=>$htmlencoded_accreditation_scope,
					'accreditation_docs'=>$accreditation_docs,
					'is_laboretory_equipped'=>$is_laboretory_equipped,
					'is_laboretory_equipped_docs'=>$is_laboretory_equipped_docs,
					'chemists_employed_docs'=>$chemists_employed_docs,
					'other_information'=>$htmlencoded_other_information,										
					'apeda_docs'=>$apeda_docs,// new field for lab-export on 31-08-2017
					'lab_ceo_name'=>$htmlencoded_lab_ceo_name,// new field for lab-export on 31-08-2017				
					'form_status'=>'saved',
					'customer_reply'=>$htmlencoded_reply,
					'customer_reply_date'=>$customer_reply_date,
					'cr_comment_ul'=>$cr_comment_ul,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s'),
					'nabl_accreditated_upto'=>chop($nabl_accreditated_upto,' 00:00:00'))); //new field added on 28-09-2021 by Amol
				
				if ($this->save($newEntity)) { return 1; };	
				
			} else { return false; }		
		}
		
		
		// To save 	RO/SO referred back  and MO reply comment
		public function saveReferredBackComment($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to)
		{			
			// Import another model in this model	
			
			$logged_in_user = $_SESSION['username'];
			$current_level = $_SESSION['current_level'];
			
			//added date function on 31-05-2021 by Amol to convert date format, as saving null
			$CustomersController = new CustomersController;	
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
				
			} elseif ($reffered_back_to = 'Level3ToLevel') {
				
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

			$nabl_accreditated_upto = htmlentities($forms_data['nabl_accreditated_upto'], ENT_QUOTES);
			$nabl_accreditated_upto = $CustomersController->Customfunctions->dateFormatCheck($nabl_accreditated_upto);
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'customer_once_card_no'=>$forms_data['customer_once_card_no'],
				
				'premises_belongs_to'=>$forms_data['premises_belongs_to'],
				'owner_name'=>$forms_data['owner_name'],
				'premises_belongs_to_docs'=>$forms_data['premises_belongs_to_docs'],
				'total_area_covered'=>$forms_data['total_area_covered'],
				'total_area_covered_docs'=>$forms_data['total_area_covered_docs'],
				'is_accreditated'=>$forms_data['is_accreditated'],
				'accreditation_no'=>$forms_data['accreditation_no'],
				'accreditation_scope'=>$forms_data['accreditation_scope'],
				'accreditation_docs'=>$forms_data['accreditation_docs'],
				'apeda_docs'=>$forms_data['apeda_docs'],//added on 01-09-2017 by Amol for lab-export
				'lab_ceo_name'=>$forms_data['lab_ceo_name'],//added on 01-09-2017 by Amol for lab-export
				'is_laboretory_equipped'=>$forms_data['is_laboretory_equipped'],
				'is_laboretory_equipped_docs'=>$forms_data['is_laboretory_equipped_docs'],
				'other_information'=>$forms_data['other_information'],
				'chemists_employed_docs'=>$forms_data['chemists_employed_docs'],
				'nabl_accreditated_upto'=>chop($nabl_accreditated_upto,' 00:00:00'),//new field added on 28-09-2021 by Amol
				
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
			
			if ($this->save($newEntity)) { return true; }

		}
		
		
		public function postDataValidation($customer_id,$forms_data) {
			
			$returnValue = true;
			$section_form_details = $this->sectionFormDetails($customer_id);			
			
			if (empty($section_form_details[0]['id'])) {
				if (empty($forms_data['premises_belongs_to_docs']->getClientFilename())) { $returnValue = null ; }
				if (empty($forms_data['total_area_covered_docs']->getClientFilename())) { $returnValue = null ; }
				if ($forms_data['is_accreditated'] == 'yes') {
					if (empty($forms_data['accreditation_docs']->getClientFilename())) { $returnValue = null ; }
				}
				if ($forms_data['is_laboretory_equipped'] == 'yes') {
					if (empty($forms_data['is_laboretory_equipped_docs']->getClientFilename())) { $returnValue = null ; }
				}
			} else {
				if ($forms_data['is_accreditated'] == 'yes' && $section_form_details[0]['accreditation_docs'] == "") {
					if (empty($forms_data['accreditation_docs']->getClientFilename())) { $returnValue = null ; }
				}
				if ($forms_data['is_laboretory_equipped'] == 'yes' && $section_form_details[0]['is_laboretory_equipped_docs'] == "") {
					if (empty($forms_data['is_laboretory_equipped_docs']->getClientFilename())) { $returnValue = null ; }
				}
			}
			
			if (empty($section_form_details[1][0])) { $returnValue = null ; }
			if (empty($forms_data['premises_belongs_to'])) { $returnValue = null ; }
			if ($forms_data['premises_belongs_to'] == 'no') { 
				if (empty($forms_data['owner_name'])) { $returnValue = null ; } 
			}
			if (empty($forms_data['total_area_covered'])) { $returnValue = null ; }		
			if (empty($forms_data['is_accreditated'])) { $returnValue = null ; }
			if ($forms_data['is_accreditated'] == 'yes') { 
				if (empty($forms_data['accreditation_no'])) { $returnValue = null ; } 
				if (empty($forms_data['accreditation_scope'])) { $returnValue = null ; }
				if (empty($forms_data['nabl_accreditated_upto'])) { $returnValue = null ; }//new field added on 28-09-2021 by Amol
			}
			if (empty($forms_data['is_laboretory_equipped'])) { $returnValue = null ; } 
			if (empty($forms_data['other_information'])) { $returnValue = null ; }
			//print_r($returnValue); exit;
			return $returnValue;
			
		}
		
		
		
	}

?>