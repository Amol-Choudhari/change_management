<?php 

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiCustomerPackingDetailsTable extends Table{

		var $name = "DmiCustomerPackingDetails";
		
		public $validate = array(
		
			'proposed_to_repack'=>array(
					'rule'=>array('maxLength',10),				
				),											
			'reffered_back_comment'=>array(
					'rule'=>array('maxLength',200),
				),
			'proposed_place'=>array(
					'rule'=>array('maxLength',200),
				),
			'once_card_no'=>array(
					'rule'=>array('maxLength',50),
				),
			'customer_reply'=>array(
					'rule'=>array('maxLength',200),
				),
			'have_grading_other_info'=>array(
					'rule'=>array('maxLength',10),
				),
			'repacking_docs'=>array(
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
				$form_fields_details = Array ( 'id'=>"",'type_of_packing' => "", 'other_packing' => "", 'food_grade_attached' => "", 'undertaking_docs' => "", 'proposed_to_repack' => "no", 'street_address' => "", 'state' => "",
												'district' => "", 'postal_code' => "", 'created' => "", 'modified' => "", 'customer_id' => "",
												'reffered_back_comment' => "", 'reffered_back_date' => "", 'proposed_place' => "", 'once_card_no' => "", 'form_status' => "", 'customer_reply' => "",
												'customer_reply_date' => "", 'approved_date' => "", 'user_email_id' => "", 'user_once_no' => "",
												'current_level' => "", 'have_grading_other_info' => "no", 'grading_other_info' => "", 'repacking_docs' => "", 'mo_comment' => "", 'mo_comment_date' => "",
												'ro_reply_comment' => "", 'ro_reply_comment_date' => "", 'delete_mo_comment' => "", 'delete_ro_reply' => "",
												'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "",'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"" );
			}
		
			return array($form_fields_details);
				
		}
		
		
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if($dataValidatation == 1 ){
				
				$CustomersController = new CustomersController;
				$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
				$section_form_details = $this->sectionFormDetails($customer_id);
				
				$htmlencoded_proposed_place = htmlentities($forms_data['proposed_place'], ENT_QUOTES);
				$htmlencoded_grading_other_info = htmlentities($forms_data['grading_other_info'], ENT_QUOTES);
				
				$post_input_request = $forms_data['proposed_to_repack'];				
				$proposed_to_repack = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($proposed_to_repack == null){ return false;}
				
				$post_input_request = $forms_data['have_grading_other_info'];				
				$have_grading_other_info = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($have_grading_other_info == null){ return false;}
				
				if(!empty($forms_data['repacking_docs']->getClientFilename())){
					
					$file_name = $forms_data['repacking_docs']->getClientFilename();
					$file_size = $forms_data['repacking_docs']->getSize();
					$file_type = $forms_data['repacking_docs']->getClientMediaType();
					$file_local_path = $forms_data['repacking_docs']->getStream()->getMetadata('uri');
					
					$repacking_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function			
				
				}else{ $repacking_docs = $section_form_details[0]['repacking_docs']; }
				
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
					'proposed_to_repack'=>$proposed_to_repack,
					'proposed_place'=>$htmlencoded_proposed_place,
					'repacking_docs'=>$repacking_docs,			
					'have_grading_other_info'=>$have_grading_other_info,
					'grading_other_info'=>$htmlencoded_grading_other_info,
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
				'proposed_to_repack'=>$forms_data['proposed_to_repack'],
				'proposed_place'=>$forms_data['proposed_place'],
				'repacking_docs'=>$forms_data['repacking_docs'],
				'have_grading_other_info'=>$forms_data['have_grading_other_info'],
				'grading_other_info'=>$forms_data['grading_other_info'],
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
			
			if(empty($section_form_details[0]['id'])){
				if($forms_data['proposed_to_repack'] == 'yes'){
					if(empty($forms_data['repacking_docs']->getClientFilename())){ $returnValue = null ; }
				}
			}
			
			if(empty($forms_data['proposed_to_repack'])){ $returnValue = null ; }
			if(empty($forms_data['have_grading_other_info'])){ $returnValue = null ; }
			if($forms_data['proposed_to_repack'] == 'yes'){
				if(empty($forms_data['proposed_place'])){ $returnValue = null ; }
			}
			if($forms_data['have_grading_other_info'] == 'yes'){
				if(empty($forms_data['grading_other_info'])){ $returnValue = null ; }
			}
			
			return $returnValue;
		}
	
} ?>