<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiLaboratoryRenewalOtherDetailsTable extends Table{
		
		var $name = "DmiLaboratoryRenewalOtherDetails";
		
		public $validate = array(
		
			'customer_id'=>array(
					'rule'=>array('maxLength',50),				
				),
			'customer_once_no'=>array(
					'rule'=>array('maxLength',200),				
				),
			'user_email_id'=>array(
					'rule'=>array('maxLength',200),				
				),
			'user_once_no'=>array(
					'rule'=>array('maxLength',200),				
				),
			'current_level'=>array(
					'rule'=>array('maxLength',20),				
				),
			'form_status'=>array(
					'rule'=>array('maxLength',20),				
				),	
			'chemist_detail_docs'=>array(
					'rule'=>array('maxLength',100),				
				),
			'authorized_packers_docs'=>array(
					'rule'=>array('maxLength',100),				
				),
			'lots_graded_docs'=>array(
					'rule'=>array('maxLength',100),				
				),
			'quantity_graded_docs'=>array(
					'rule'=>array('maxLength',100),				
				),
			'check_Sample_docs'=>array(
					'rule'=>array('maxLength',100),				
				),
			'is_warning_issued'=>array(
					'rule'=>array('maxLength',20),				
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
		
		public function sectionFormDetails($customer_id)
		{
			$CustomersController = new CustomersController;	
			$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
			
			$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
					
			if($latest_id != null){
				$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
				$form_fields_details = $form_fields;				
			}else{
				
				$form_fields_details = Array ( 	 'id' => '', 'customer_id' => '', 'customer_once_no' => '', 'user_email_id' => '', 'user_once_no' => '', 'referred_back_comment' => '', 'referred_back_date' => '', 'customer_reply' => '',
												 'customer_reply_date' => '', 'ro_reply_comment' => '', 'ro_reply_comment_date' => '', 'mo_comment' => '', 'mo_comment_date' => '', 'current_level' => '', 'form_status' => '', 'created' => '',
												 'modified' => '', 'chemist_detail_docs' => '', 'authorized_packers_docs' => '', 'lots_graded_docs' => '', 'quantity_graded_docs' => '', 'check_Sample_docs' => '', 'is_warning_issued' => 'yes',  'warning_details' => '',
												 'delete_mo_comment' => '', 'delete_ro_reply' => '', 'delete_ro_referred_back' => '', 'delete_customer_reply' => '', 'ro_current_comment_to' => '', 'delete_mo_comment' => '', 'delete_ro_reply' => '', 
												 'delete_ro_referred_back' => '', 'delete_customer_reply' => '', 'ro_current_comment_to' => '','rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>""); 
				
			}
			
			$Dmi_laboratory_chemists_detail = TableRegistry::getTableLocator()->get('DmiLaboratoryChemistsDetails');
			$chemist_details = $Dmi_laboratory_chemists_detail->renewalChemistDetails($customer_id);
			return array($form_fields_details,$chemist_details);		
		}
		// To save 	MO Referred Back comment	  (code by pravin 7/4/2017)
		
		
		public function saveFormDetails($customer_id,$forms_data){
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if($dataValidatation == 1 ){
				
				$CustomersController = new CustomersController;	
				$section_form_details = $this->sectionFormDetails($customer_id);
				
				$htmlencoded_warning_details = htmlentities($forms_data['warning_details'], ENT_QUOTES);
				
				$post_input_request = $forms_data['is_warning_issued'];							
				$is_warning_issued = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($is_warning_issued == null){ return false;}
				
				if(!empty($forms_data['chemist_detail_docs']->getClientFilename())){								
									
					$file_name = $forms_data['chemist_detail_docs']->getClientFilename();
					$file_size = $forms_data['chemist_detail_docs']->getSize();
					$file_type = $forms_data['chemist_detail_docs']->getClientMediaType();
					$file_local_path = $forms_data['chemist_detail_docs']->getStream()->getMetadata('uri');
				
					$chemist_detail_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				}else{ $chemist_detail_docs = $section_form_details[0]['chemist_detail_docs']; }
				
				if(!empty($forms_data['authorized_packers_docs']->getClientFilename())){
					
					$file_name = $forms_data['authorized_packers_docs']->getClientFilename();
					$file_size = $forms_data['authorized_packers_docs']->getSize();
					$file_type = $forms_data['authorized_packers_docs']->getClientMediaType();
					$file_local_path = $forms_data['authorized_packers_docs']->getStream()->getMetadata('uri');
				
					$authorized_packers_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				}else{ $authorized_packers_docs = $section_form_details[0]['authorized_packers_docs']; }
				
				if(!empty($forms_data['lots_graded_docs']->getClientFilename())){								
									
					$file_name = $forms_data['lots_graded_docs']->getClientFilename();
					$file_size = $forms_data['lots_graded_docs']->getSize();
					$file_type = $forms_data['lots_graded_docs']->getClientMediaType();
					$file_local_path = $forms_data['lots_graded_docs']->getStream()->getMetadata('uri');
				
					$lots_graded_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				}else{ $lots_graded_docs = $section_form_details[0]['lots_graded_docs']; }
				
				if(!empty($forms_data['quantity_graded_docs']->getClientFilename())){
																	
					$file_name = $forms_data['quantity_graded_docs']->getClientFilename();
					$file_size = $forms_data['quantity_graded_docs']->getSize();
					$file_type = $forms_data['quantity_graded_docs']->getClientMediaType();
					$file_local_path = $forms_data['quantity_graded_docs']->getStream()->getMetadata('uri');
				
					$quantity_graded_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				}else{ $quantity_graded_docs = $section_form_details[0]['quantity_graded_docs']; }
				
				if(!empty($forms_data['check_Sample_docs']->getClientFilename())){
																	
					$file_name = $forms_data['check_Sample_docs']->getClientFilename();
					$file_size = $forms_data['check_Sample_docs']->getSize();
					$file_type = $forms_data['check_Sample_docs']->getClientMediaType();
					$file_local_path = $forms_data['check_Sample_docs']->getStream()->getMetadata('uri');
				
					$check_Sample_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				}else{ $check_Sample_docs = $section_form_details[0]['check_Sample_docs']; }
				
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
					'chemist_detail_docs'=>$chemist_detail_docs,
					'authorized_packers_docs'=>$authorized_packers_docs,
					'lots_graded_docs'=>$lots_graded_docs,
					'quantity_graded_docs'=>$quantity_graded_docs,
					'check_Sample_docs'=>$check_Sample_docs,
					'is_warning_issued'=>$is_warning_issued,
					'warning_details'=>$htmlencoded_warning_details,
					'form_status'=>'saved',
					'customer_reply'=>$htmlencoded_reply,
					'customer_reply_date'=>$customer_reply_date,
					'cr_comment_ul'=>$cr_comment_ul,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s')
				));
				if ($this->save($newEntity)){ return 1;  }
				
			}else{	return false; }			
			
		}
		
		public function saveReferredBackComment($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to)
		{			
			// Import another model in this model	
			
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
				'chemist_detail_docs'=>$forms_data['chemist_detail_docs'],
				'authorized_packers_docs'=>$forms_data['authorized_packers_docs'],
				'lots_graded_docs'=>$forms_data['lots_graded_docs'],
				'quantity_graded_docs'=>$forms_data['quantity_graded_docs'],
				'check_Sample_docs'=>$forms_data['check_Sample_docs'],
				'is_warning_issued'=>$forms_data['is_warning_issued'],
				'warning_details'=>$forms_data['warning_details'],
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
			
			if(empty($section_form_details[0]['id'])){
				if(empty($forms_data['chemist_detail_docs']->getClientFilename())){ $returnValue = null ; }
				if(empty($forms_data['authorized_packers_docs']->getClientFilename())){ $returnValue = null ; }
				if(empty($forms_data['lots_graded_docs']->getClientFilename())){ $returnValue = null ; }
				if(empty($forms_data['quantity_graded_docs']->getClientFilename())){ $returnValue = null ; }
				if(empty($forms_data['check_Sample_docs']->getClientFilename())){ $returnValue = null ; }
				if(empty($forms_data['quantity_graded_docs']->getClientFilename())){ $returnValue = null ; }
			}
			
			if(empty($section_form_details[1][0])){ $returnValue = null ; }
			if(empty($forms_data['is_warning_issued'])){ $returnValue = null ; }
			if($forms_data['is_warning_issued'] == 'yes'){ 
				if(empty($forms_data['warning_details'])){ $returnValue = null ; } 
			}
			
			return $returnValue;
		}
		
	}

?>