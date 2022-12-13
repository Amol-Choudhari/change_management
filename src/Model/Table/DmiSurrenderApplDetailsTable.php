<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	
	class DmiSurrenderApplDetailsTable extends Table{
	
	var $name = "DmiSurrenderApplDetails";
	

	// Fetch form section all details
		public function sectionFormDetails($customer_id)
		{
			$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
					
			if($latest_id != null){
				$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
				$form_fields_details = $form_fields;
				
			}else{
				$form_fields_details = Array ( 'id'=>"", 'created' => "", 'modified' =>"", 'customer_id' => "", 'business_years' => "", 				'reffered_back_comment' => "",'reffered_back_date' => "", 'once_card_no' =>"", 'form_status' =>"", 'customer_reply' =>"", 'customer_reply_date' =>"", 'approved_date' => "",'user_email_id' => "", 'user_once_no' => "", 'current_level' => "",
											   'mo_comment' =>"", 'mo_comment_date' => "", 'ro_reply_comment' =>"", 'ro_reply_comment_date' =>"", 'delete_mo_comment' =>"", 'delete_ro_reply' => "",
											   'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "", 
											   'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"",'auto_packing_lines'=>"",
											   'separate_sections_unit'=>"",'is_all_commo_graded'=>"",'all_commo_graded_doc'=>"",'is_commo_stored_in_room'=>"",'is_reg_stored_in_room'=>"",'relevant_doc'=>""); 
				
			}
			
			$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
			$firm_details = $DmiFirms->firmDetails($customer_id);
			
			$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
			$sub_comm_id = explode(',',$firm_details['sub_commodity']);	
			$sub_commodity_value = $MCommodity->find('list',array('valueField'=>'commodity_name','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toArray();
			
			return array($form_fields_details,$sub_commodity_value);
				
		}
		
		
	// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if($dataValidatation == 1 ){
				
				$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
				
				$CustomersController = new CustomersController;
				$section_form_details = $this->sectionFormDetails($customer_id);
				$firm_details = $DmiFirms->firmDetails($customer_id);
				
				//checking radio buttons input
				$post_input_request = $forms_data['auto_packing_lines'];				
				$auto_packing_lines = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($auto_packing_lines == null){ return false;}
				
				$post_input_request = $forms_data['separate_sections_unit'];				
				$separate_sections_unit = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($separate_sections_unit == null){ return false;}
				
				$post_input_request = $forms_data['is_all_commo_graded'];				
				$is_all_commo_graded = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($is_all_commo_graded == null){ return false;}
				
				$post_input_request = $forms_data['is_commo_stored_in_room'];				
				$is_commo_stored_in_room = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($is_commo_stored_in_room == null){ return false;}
				
				$post_input_request = $forms_data['is_reg_stored_in_room'];				
				$is_reg_stored_in_room = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($is_reg_stored_in_room == null){ return false;}
				
				
				//file uploads
				if(!empty($forms_data['all_commo_graded_doc']->getClientFilename())){				
					
					$file_name = $forms_data['all_commo_graded_doc']->getClientFilename();
					$file_size = $forms_data['all_commo_graded_doc']->getSize();
					$file_type = $forms_data['all_commo_graded_doc']->getClientMediaType();
					$file_local_path = $forms_data['all_commo_graded_doc']->getStream()->getMetadata('uri');			
				
					$all_commo_graded_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function				
				
				}else{ $all_commo_graded_doc = $section_form_details[0]['all_commo_graded_doc']; }
				
				if(!empty($forms_data['relevant_doc']->getClientFilename())){				
					
					$file_name = $forms_data['relevant_doc']->getClientFilename();
					$file_size = $forms_data['relevant_doc']->getSize();
					$file_type = $forms_data['relevant_doc']->getClientMediaType();
					$file_local_path = $forms_data['relevant_doc']->getStream()->getMetadata('uri');			
				
					$relevant_doc = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function				
				
				}else{ $relevant_doc = $section_form_details[0]['relevant_doc']; }

				
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
					'auto_packing_lines'=>$forms_data['auto_packing_lines'],
					'separate_sections_unit'=>$forms_data['separate_sections_unit'],
					'is_all_commo_graded'=>$forms_data['is_all_commo_graded'],
					'all_commo_graded_doc'=>$all_commo_graded_doc,
					'is_commo_stored_in_room'=>$forms_data['is_commo_stored_in_room'],
					'is_reg_stored_in_room'=>$forms_data['is_reg_stored_in_room'],
					'relevant_doc'=>$relevant_doc,
					'form_status'=>'saved',
					'customer_reply'=>$htmlencoded_reply,
					'customer_reply_date'=>$customer_reply_date,
					'cr_comment_ul'=>$cr_comment_ul,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s')));
				
				if ($this->save($newEntity)){ 			
					
					return 1;
					
				};
				
			}else{	return false; }	
			
					
		}
		
		
		// To save 	RO/SO referred back  and MO reply comment
		public function saveReferredBackComment ($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to)
		{			
			// Import another model in this model	
			
			$logged_in_user = $_SESSION['username'];
			$current_level = $_SESSION['current_level'];

			$CustomersController = new CustomersController;
			
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
				'auto_packing_lines'=>$forms_data['auto_packing_lines'],
				'separate_sections_unit'=>$forms_data['separate_sections_unit'],
				'is_all_commo_graded'=>$forms_data['is_all_commo_graded'],
				'all_commo_graded_doc'=>$forms_data['all_commo_graded_doc'],
				'is_commo_stored_in_room'=>$forms_data['is_commo_stored_in_room'],
				'is_reg_stored_in_room'=>$forms_data['is_reg_stored_in_room'],
				'relevant_doc'=>$forms_data['relevant_doc'],
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
				'rr_comment_ul'=>$rr_comment_ul
				
			));
			
			if($this->save($newEntity)){ 
			
				return true; 
			}

		}
		
		
		public function postDataValidation($customer_id,$forms_data){
		//	print_r($forms_data); exit;
			$returnValue = true;
			$section_form_details = $this->sectionFormDetails($customer_id);
			$CustomersController = new CustomersController;


			if(empty($section_form_details[0]['id'])){

				if(empty($forms_data['all_commo_graded_doc']->getClientFilename())){ $returnValue = null ; }
				if(empty($forms_data['relevant_doc']->getClientFilename())){ $returnValue = null ; }
			}

			if(empty($forms_data['auto_packing_lines'])){ $returnValue = null ; }
			if(empty($forms_data['separate_sections_unit'])){ $returnValue = null ; }
			if(empty($forms_data['is_all_commo_graded'])){ $returnValue = null ; }
			if(empty($forms_data['is_commo_stored_in_room'])){ $returnValue = null ; }
			if(empty($forms_data['is_reg_stored_in_room'])){ $returnValue = null ; }
			
			return $returnValue;
			
		}
			
}

?>