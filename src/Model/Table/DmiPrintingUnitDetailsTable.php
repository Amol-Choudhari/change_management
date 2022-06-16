<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
class DmiPrintingUnitDetailsTable extends Table{

		var $name = "DmiPrintingUnitDetails";
		
		public $validate = array(
		
					'machine_details_docs'=>array(
								'rule'=>array('maxLength',200),				
							),
					'other_required_machine_docs'=>array(
								'rule'=>array('maxLength',200),				
							),
					'earlier_approved'=>array(
								'rule'=>array('maxLength',10),				
							),
					'customer_id'=>array(
								'rule'=>array('maxLength',50),				
							),
					'reffered_back_comment'=>array(
								'rule'=>array('maxLength',200),				
							),
					'in_house_machinery'=>array(
								'rule'=>array('maxLength',10),				
							),
					'once_card_no'=>array(
								'rule'=>array('maxLength',200),				
							),
					'form_status'=>array(
								'rule'=>array('maxLength',20),				
							),
					'customer_reply'=>array(
								'rule'=>array('maxLength',200),				
							),
					'user_email_id'=>array(
								'rule'=>array('maxLength',200),				
							),
					'user_once_no'=>array(
								'rule'=>array('maxLength',200),				
							),
					'current_level'=>array(
								'rule'=>array('maxLength',50),				
							),	
					'proper_fabrication'=>array(
								'rule'=>array('maxLength',10),				
							),	
					'fabrication_docs'=>array(
								'rule'=>array('maxLength',200),				
							),							
					'delete_ro_reply'=>array(
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
				$form_fields_details = Array ( 	'id'=>"", 'type_of_packing' => "", 'other_packing' => "", 'have_machine_details' => "", 'no_of_machine' => "", 'capacity_of_machine' => "", 'machine_details_docs' => "", 
												'other_required_machine_docs' => "",  'earlier_approved' => "no", 'created' => "", 'modified' => "", 'customer_id' => "", 'reffered_back_comment' => "", 'reffered_back_date' => "",
												'in_house_machinery' => "no", 'once_card_no' => "", 'form_status' => "", 'customer_reply' => "", 'customer_reply_date' => "", 'approved_date' => "", 'user_email_id' => "",
												'user_once_no' => "", 'current_level' => "", 'proper_fabrication' => "no", 'name_address_fabrication_unit' => "", 'fabrication_docs' => "", 'is_container_of_food_grade' => "", 
												'container_docs' => "", 'right_quality_ink' => "", 'press_proposed_date' => "", 'ro_reply_comment_date' => "", 'ro_reply_comment' => "", 'mo_comment_date' => "", 'mo_comment' => "",
												'delete_mo_comment' => "", 'delete_ro_reply' => "", 'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "", 'earlier_expiry_date' => "",
												'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>""); 
				
			}
			
			$Dmi_all_machines_detail = TableRegistry::getTableLocator()->get('DmiAllMachinesDetails');
			$added_machines_detail = $Dmi_all_machines_detail->machineDetails(2);
			return array($form_fields_details,$added_machines_detail);
				
		}
		
		
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if($dataValidatation == 1 ){
				
				$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
				
				$CustomersController = new CustomersController;	
				$section_form_details = $this->sectionFormDetails($customer_id);
				$firm_details = $Dmi_firm->firmDetails($customer_id);
						
				$htmlencoded_earlier_expiry_date = htmlentities($forms_data['earlier_expiry_date'], ENT_QUOTES);
				$htmlencoded_earlier_expiry_date = $CustomersController->Customfunctions->dateFormatCheck($htmlencoded_earlier_expiry_date);
				$htmlencoded_name_address_fabrication_unit = htmlentities($forms_data['name_address_fabrication_unit'], ENT_QUOTES);
				$htmlencoded_press_proposed_date = htmlentities($forms_data['press_proposed_date'], ENT_QUOTES);
				$htmlencoded_press_proposed_date = $CustomersController->Customfunctions->dateFormatCheck($htmlencoded_press_proposed_date);

				$post_input_request = $forms_data['earlier_approved'];				
				$earlier_approved = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($earlier_approved == null){ return false;}	
					
					
				$post_input_request = $forms_data['in_house_machinery'];				
				$in_house_machinery = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function		
				if($in_house_machinery == null){ return false;}
				
				$post_input_request = $forms_data['proper_fabrication'];				
				$proper_fabrication = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function		
				if($proper_fabrication == null){ return false;}
						
			
				
				//file uploading					
		//	if(!empty($forms_data['machine_details_docs']->getClientFilename())){				
			
				//	$file_name = $forms_data['machine_details_docs']->getClientFilename();
				//	$file_size = $forms_data['machine_details_docs']->getSize();
				//	$file_type = $forms_data['machine_details_docs']->getClientMediaType();
			//		$file_local_path = $forms_data['machine_details_docs']->getStream()->getMetadata('uri');
					
				//	$machine_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,//$file_type,$file_local_path); // calling file uploading function
				
		// 		}else{ $machine_details_docs = $section_form_details[0]['machine_details_docs']; }
				
				
				if(!empty($forms_data['other_required_machine_docs']->getClientFilename())){				
					
					$file_name = $forms_data['other_required_machine_docs']->getClientFilename();
					$file_size = $forms_data['other_required_machine_docs']->getSize();
					$file_type = $forms_data['other_required_machine_docs']->getClientMediaType();
					$file_local_path = $forms_data['other_required_machine_docs']->getStream()->getMetadata('uri');
					
					$other_required_machine_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				}else{ $other_required_machine_docs = $section_form_details[0]['other_required_machine_docs']; }
				
				
				if(!empty($forms_data['fabrication_docs']->getClientFilename())){				
					
					$file_name = $forms_data['fabrication_docs']->getClientFilename();
					$file_size = $forms_data['fabrication_docs']->getSize();
					$file_type = $forms_data['fabrication_docs']->getClientMediaType();
					$file_local_path = $forms_data['fabrication_docs']->getStream()->getMetadata('uri');
					
					$fabrication_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				
				}else{ $fabrication_docs = $section_form_details[0]['fabrication_docs']; }
						
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
					//'machine_details_docs'=>$machine_details_docs,
					'other_required_machine_docs'=>$other_required_machine_docs,
					'earlier_approved'=>$earlier_approved,
					'earlier_expiry_date'=>$htmlencoded_earlier_expiry_date,
					'in_house_machinery'=>$in_house_machinery,
					'proper_fabrication'=>$proper_fabrication,
					'name_address_fabrication_unit'=>$htmlencoded_name_address_fabrication_unit,
					'fabrication_docs'=>$fabrication_docs,
					'press_proposed_date'=>$htmlencoded_press_proposed_date,
					
					'form_status'=>'saved',
					'customer_reply'=>$htmlencoded_reply,
					'customer_reply_date'=>$customer_reply_date,
					'cr_comment_ul'=>$cr_comment_ul,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s'))); 
				
				if ($this->save($newEntity)){ return 1; };	
				
			}else{	return false; }			
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
			$earlier_expiry_date = $CustomersController->Customfunctions->changeDateFormat($forms_data['earlier_expiry_date']);
			$press_proposed_date = $CustomersController->Customfunctions->changeDateFormat($forms_data['press_proposed_date']);
				
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
				'other_required_machine_docs'=>$forms_data['other_required_machine_docs'],
				'earlier_approved'=>$forms_data['earlier_approved'],
				'earlier_expiry_date'=>$earlier_expiry_date,
				'in_house_machinery'=>$forms_data['in_house_machinery'],
				'proper_fabrication'=>$forms_data['proper_fabrication'],
				'name_address_fabrication_unit'=>$forms_data['name_address_fabrication_unit'],
				'fabrication_docs'=>$forms_data['fabrication_docs'],
				'press_proposed_date'=>$press_proposed_date,
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
			//print_r($forms_data); exit;
			$returnValue = true;
			$section_form_details = $this->sectionFormDetails($customer_id);	
			
			if(empty($section_form_details[0]['id'])){
				if(empty($forms_data['other_required_machine_docs']->getClientFilename())){ $returnValue = null ; }
				if($forms_data['proper_fabrication'] == 'no' || $forms_data['proper_fabrication'] == 'yes'){ 
					if(empty($forms_data['fabrication_docs']->getClientFilename())){ $returnValue = null ; } 
				}
			}else{
				
				if(($forms_data['proper_fabrication'] == 'no' || $forms_data['proper_fabrication'] == 'yes')
					&& $section_form_details[0]['fabrication_docs'] == ""){
					if(empty($forms_data['fabrication_docs']->getClientFilename())){  $returnValue = null ; } 
				}
				
			}
			
			if(empty($section_form_details[1][2])){ $returnValue = null ; }
			if(empty($forms_data['earlier_approved'])){ $returnValue = null ; }
			if($forms_data['earlier_approved'] == 'yes'){ 
				if(empty($forms_data['earlier_expiry_date'])){ $returnValue = null ; } 
			}
			if(empty($forms_data['in_house_machinery'])){ $returnValue = null ; }
			if(empty($forms_data['proper_fabrication'])){ $returnValue = null ; }
			if($forms_data['proper_fabrication'] == 'no'){ 
				if(empty($forms_data['name_address_fabrication_unit'])){ $returnValue = null ; } 
			}
			if(empty($forms_data['press_proposed_date'])){ $returnValue = null ; }
			
			return $returnValue;
			
		}
		

} ?>