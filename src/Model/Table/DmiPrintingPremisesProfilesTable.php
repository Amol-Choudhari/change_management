<?php 

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

class DmiPrintingPremisesProfilesTable extends Table{
	
	var $name = "DmiPrintingPremisesProfiles";
	
	
	public $validate = array(
	
		'street_address'=>array(
			'rule'=>array('maxLength',200),				
			),
		
		'state'=>array(
			'rule1'=>array(
					'rule'=>array('maxLength',100),
					'allowEmpty'=>false,
					'last'=>false,
				),
			'rule2'=>array(
					'rule'=>'Numeric',
				)					
			),

		'district'=>array(
				'rule1'=>array(
						'rule'=>array('maxLength',100),
						'allowEmpty'=>false,
						'last'=>false,
					),
				'rule2'=>array(
						'rule'=>'Numeric',
					)					
			),

		'postal_code'=>array(
				'rule'=>array('maxLength',20),				
			),
		
		'have_vat_cst_no'=>array(
			'rule'=>array('maxLength',10),				
		),
		
		'vat_cst_no'=>array(
			'rule'=>array('maxLength',50),				
		),
		
		'vat_cst_docs'=>array(
			'rule'=>array('maxLength',200),				
		),
		
		'customer_id'=>array(
			'rule'=>array('maxLength',50),				
		),
		
		'reffered_back_comment'=>array(
			'rule'=>array('maxLength',200),				
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
		
		'first_rep_f_name'=>array(
			'rule'=>array('maxLength',100),				
		),
		
		'first_rep_m_name'=>array(
			'rule'=>array('maxLength',100),				
		),
		
		'first_rep_l_name'=>array(
			'rule'=>array('maxLength',100),				
		),
		
		'first_rep_mobile'=>array(
			'rule'=>array('maxLength',15),				
		),
		
		'first_rep_signature'=>array(
			'rule'=>array('maxLength',200),				
		),
		
		'second_rep_f_name'=>array(
			'rule'=>array('maxLength',100),				
		),
		
		'second_rep_m_name'=>array(
			'rule'=>array('maxLength',100),				
		),
		
		'second_rep_l_name'=>array(
			'rule'=>array('maxLength',100),				
		),
		
		'second_rep_mobile'=>array(
			'rule'=>array('maxLength',15),				
		),
		
		'second_rep_signature'=>array(
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
		
		'layout_plan_attached'=>array(
			'rule'=>array('maxLength',50),				
		),
		
		'layout_plan_docs'=>array(
			'rule'=>array('maxLength',200),				
		),
		
		'gst_no'=>array(
			'rule'=>array('maxLength',100),				
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
			
			$form_fields_details = Array ( 	'id'=>"", 'street_address' => "", 'state' => "", 'district' => "", 'postal_code' => "", 'belongs_to_applicant' => "",'premises_owner_name' => "",'premises_docs' => "",
											'have_vat_cst_no' => "no", 'vat_cst_no' => "",'vat_cst_docs' => "", 'created' => "", 'modified' => "", 'customer_id' => "", 'reffered_back_comment' => "",'reffered_back_date' => "",
											'once_card_no' => "",'form_status' => "", 'customer_reply' => "",'customer_reply_date' => "",'approved_date' => "",'first_rep_f_name' => "", 'first_rep_m_name' => "", 
											'first_rep_l_name' => "", 'first_rep_mobile' => "", 'first_rep_signature' => "", 'second_rep_f_name' => "", 'second_rep_m_name' => "", 'second_rep_l_name' => "", 'second_rep_mobile' => "",
											'second_rep_signature' => "", 'user_email_id' => "",'user_once_no' => "",'current_level' => "",'layout_plan_attached' => "no", 'layout_plan_docs' => "", 'structure_is_permanent' => "",
											'adequate_arrangement' => "",'facility_for_security' => "",'proper_documentation_system' => "",'gst_no' => "", 'mo_comment_date' => "",'mo_comment' => "",'ro_reply_comment_date' => "",
											'ro_reply_comment' => "",'delete_mo_comment' => "",'delete_ro_reply' => "",'delete_ro_referred_back' => "",'delete_customer_reply' => "",'ro_current_comment_to' => "",
											'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>""); 
			
		}
		$Dmi_district = TableRegistry::getTableLocator()->get('DmiDistricts');
		$districts_list = $Dmi_district->find('list', array('valueField'=>'district_name', 'conditions'=>array('state_id'=>$form_fields_details['state'],'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->toArray();
		asort($districts_list);
		return array($form_fields_details,$districts_list);			
	}
	
	
	// save or update form data and comment reply by applicant
	public function saveFormDetails($customer_id,$forms_data){
		
		$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
		
		if($dataValidatation == 1 ){	
		
			$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
			
			$CustomersController = new CustomersController;		
			$section_form_details = $this->sectionFormDetails($customer_id);
			$firm_details = $Dmi_firm->firmDetails($customer_id);
					
			$htmlencoded_street_address = htmlentities($forms_data['street_address'], ENT_QUOTES);
			$htmlencoded_postal_code = htmlentities($forms_data['postal_code'], ENT_QUOTES);
			$htmlencoded_gst_no = htmlentities($forms_data['gst_no'], ENT_QUOTES);
			$htmlencoded_first_rep_f_name = htmlentities($forms_data['first_rep_f_name'], ENT_QUOTES);
			$htmlencoded_first_rep_m_name = htmlentities($forms_data['first_rep_m_name'], ENT_QUOTES);
			$htmlencoded_first_rep_l_name = htmlentities($forms_data['first_rep_l_name'], ENT_QUOTES);
			$htmlencoded_first_rep_mobile = htmlentities($forms_data['first_rep_mobile'], ENT_QUOTES);		
			$htmlencoded_second_rep_f_name = htmlentities($forms_data['second_rep_f_name'], ENT_QUOTES);
			$htmlencoded_second_rep_m_name = htmlentities($forms_data['second_rep_m_name'], ENT_QUOTES);
			$htmlencoded_second_rep_l_name = htmlentities($forms_data['second_rep_l_name'], ENT_QUOTES);
			$htmlencoded_second_rep_mobile = htmlentities($forms_data['second_rep_mobile'], ENT_QUOTES);

			$post_input_request = $forms_data['layout_plan_attached'];				
			$layout_plan_attached = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
			if($layout_plan_attached == null){ return false;}	
				
				
			$post_input_request = $forms_data['have_vat_cst_no'];				
			$have_vat_cst_no = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function		
			if($have_vat_cst_no == null){ return false;}
			
			$table = 'DmiStates';
			$post_input_request = $forms_data['state'];
			$state = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
				
				
			//for district
			$table = 'DmiDistricts';
			$post_input_request = $forms_data['district'];
			$district = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
			
			
			//file uploading					
			if(!empty($forms_data['layout_plan_docs']->getClientFilename())){				
				
				$file_name = $forms_data['layout_plan_docs']->getClientFilename();
				$file_size = $forms_data['layout_plan_docs']->getSize();
				$file_type = $forms_data['layout_plan_docs']->getClientMediaType();
				$file_local_path = $forms_data['layout_plan_docs']->getStream()->getMetadata('uri');
				
				$layout_plan_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{ $layout_plan_docs = $section_form_details[0]['layout_plan_docs']; }
			
			
			if(!empty($forms_data['vat_cst_docs']->getClientFilename())){				
				
				$file_name = $forms_data['vat_cst_docs']->getClientFilename();
				$file_size = $forms_data['vat_cst_docs']->getSize();
				$file_type = $forms_data['vat_cst_docs']->getClientMediaType();
				$file_local_path = $forms_data['vat_cst_docs']->getStream()->getMetadata('uri');
				
				$vat_cst_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{ $vat_cst_docs = $section_form_details[0]['vat_cst_docs']; }
			
			
			if(!empty($forms_data['first_rep_signature']->getClientFilename())){				
				
				$file_name = $forms_data['first_rep_signature']->getClientFilename();
				$file_size = $forms_data['first_rep_signature']->getSize();
				$file_type = $forms_data['first_rep_signature']->getClientMediaType();
				$file_local_path = $forms_data['first_rep_signature']->getStream()->getMetadata('uri');
				
				$first_rep_signature = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{ $first_rep_signature = $section_form_details[0]['first_rep_signature']; }
			
			
			if(!empty($forms_data['second_rep_signature']->getClientFilename())){				
				
				$file_name = $forms_data['second_rep_signature']->getClientFilename();
				$file_size = $forms_data['second_rep_signature']->getSize();
				$file_type = $forms_data['second_rep_signature']->getClientMediaType();
				$file_local_path = $forms_data['second_rep_signature']->getStream()->getMetadata('uri');
				
				$second_rep_signature = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
			
			}else{ $second_rep_signature = $section_form_details[0]['second_rep_signature']; }
			
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
				'layout_plan_attached'=>$layout_plan_attached,
				'layout_plan_docs'=>$layout_plan_docs,
				'have_vat_cst_no'=>$have_vat_cst_no,
				'gst_no'=>$htmlencoded_gst_no,
				'vat_cst_docs'=>$vat_cst_docs,
				'first_rep_f_name'=>$htmlencoded_first_rep_f_name,
				'first_rep_m_name'=>$htmlencoded_first_rep_m_name,
				'first_rep_l_name'=>$htmlencoded_first_rep_l_name,
				'first_rep_mobile'=>base64_encode($htmlencoded_first_rep_mobile),//This is added on 27-04-2021 for base64encoding by AKASH
				'first_rep_signature'=>$first_rep_signature,
				'second_rep_f_name'=>$htmlencoded_second_rep_f_name,
				'second_rep_m_name'=>$htmlencoded_second_rep_m_name,
				'second_rep_l_name'=>$htmlencoded_second_rep_l_name,
				'second_rep_mobile'=>base64_encode($htmlencoded_second_rep_mobile),//This is added on 27-04-2021 for base64encoding by AKASH
				'second_rep_signature'=>$second_rep_signature,	
				//fields for BEVO end	
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
			'street_address'=>$forms_data['street_address'],
			'state'=>$forms_data['state'],
			'district'=>$forms_data['district'],
			'postal_code'=>$forms_data['postal_code'],
			'have_vat_cst_no'=>$forms_data['have_vat_cst_no'],							
			'gst_no'=>$forms_data['gst_no'],
			'vat_cst_docs'=>$forms_data['vat_cst_docs'],
			'layout_plan_attached'=>$forms_data['layout_plan_attached'],
			'layout_plan_docs'=>$forms_data['layout_plan_docs'],
			'first_rep_f_name'=>$forms_data['first_rep_f_name'],
			'first_rep_m_name'=>$forms_data['first_rep_m_name'],
			'first_rep_l_name'=>$forms_data['first_rep_l_name'],
			'first_rep_mobile'=>$forms_data['first_rep_mobile'],
			'first_rep_signature'=>$forms_data['first_rep_signature'],
			'second_rep_f_name'=>$forms_data['second_rep_f_name'],
			'second_rep_m_name'=>$forms_data['second_rep_m_name'],
			'second_rep_l_name'=>$forms_data['second_rep_l_name'],
			'second_rep_mobile'=>$forms_data['second_rep_mobile'],
			'second_rep_signature'=>$forms_data['second_rep_signature'],
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
			if($forms_data['have_vat_cst_no'] == 'yes'){ 
				if(empty($forms_data['vat_cst_docs']->getClientFilename())){ $returnValue = null ; }
			}
			if($forms_data['layout_plan_attached'] == 'yes'){ 
				if(empty($forms_data['layout_plan_docs']->getClientFilename())){ $returnValue = null ; }
			}
			if(empty($forms_data['first_rep_signature']->getClientFilename())){ $returnValue = null ; }
			if(empty($forms_data['second_rep_signature']->getClientFilename())){ $returnValue = null ; }
		}else{
			if($forms_data['layout_plan_attached'] == 'yes' && $section_form_details[0]['layout_plan_docs'] == ""){
				if(empty($forms_data['layout_plan_docs']->getClientFilename())){ $returnValue = null ; }
			}
			if($forms_data['have_vat_cst_no'] == 'yes' && $section_form_details[0]['vat_cst_docs'] == ""){
				if(empty($forms_data['vat_cst_docs']->getClientFilename())){ $returnValue = null ; }
			}
		}
		
		if(!filter_var($forms_data['state'], FILTER_VALIDATE_INT)){ $returnValue = null ; }else{
			if(!filter_var($forms_data['district'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
		}
		if(empty($forms_data['street_address'])){ $returnValue = null ; }
		if(empty($forms_data['postal_code'])){ $returnValue = null ; }
		if(empty($forms_data['have_vat_cst_no'])){ $returnValue = null ; } 
		if($forms_data['have_vat_cst_no'] == 'yes'){ 
			if(empty($forms_data['gst_no'])){ $returnValue = null ; }
		}
		if(empty($forms_data['layout_plan_attached'])){ $returnValue = null ; }
		if(empty($forms_data['first_rep_f_name'])){ $returnValue = null ; }
		//if(empty($forms_data['first_rep_m_name'])){ $returnValue = null ; } //not mandatory
		if(empty($forms_data['first_rep_l_name'])){ $returnValue = null ; }
		
		if(empty($forms_data['second_rep_f_name'])){ $returnValue = null ; }
		//if(empty($forms_data['second_rep_m_name'])){ $returnValue = null ; } //not mandatory
		if(empty($forms_data['second_rep_l_name'])){ $returnValue = null ; }
		
		return $returnValue;
			
	}	

	
} ?>