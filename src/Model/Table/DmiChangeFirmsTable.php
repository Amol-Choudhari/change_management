<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	use Cake\Validation\Validator;
	use Cake\Utility\Hash;
	
class DmiChangeFirmsTable extends Table{
	
	var $name = "DmiChangeFirms";
	
	public $validate = array(
			'firm_name'=>array(
					'rule'=>array('maxLength',200),
					'allowEmpty' => false,				
				),											
			'certification_type'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
			'commodity'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'last'=>false),
					'numeric' => array(
						'rule' => 'numeric',
						'allowEmpty' => true,
					),
				),
			'sub_commodity'=>array(
					'rule'=>array('maxLength',50),	
				),
			'street_address'=>array(
					'rule'=>array('maxLength',200),
					'allowEmpty' => false,
				),
			'state'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
			'district'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
			'postal_code'=>array(
					'rule'=>array('maxLength',20),
					'allowEmpty' => false,
				),
			'email'=>array(
					'rule'=>array('maxLength',100),
					'allowEmpty' => false,
				),
			'total_charges'=>array(
					'rule'=>array('maxLength',10),
					'allowEmpty' => false,
				),
			'mobile_no'=>array(
					'rule'=>array('maxLength',10),
					'allowEmpty' => false,
				),
			'export_unit'=>array(
					'rule'=>array('maxLength',10),
				),
			'fax_no'=>array(
					'rule'=>array('maxLength',10),
				),
			'packaging_materials'=>array(					
						'rule'=>array('maxLength',50),					
				),
			'is_already_granted'=>array(
					'rule'=>array('maxLength',10),
				),
				
			);
	
	public function sectionFormDetails($customer_id){
		
		$CustomersController = new CustomersController;	
		$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);	
		$DmiFirm = TableRegistry::getTableLocator()->get('DmiFirms');
		$application_type = $_SESSION['application_type'];
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();	
		
		if($latest_id != null){
			$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
			$form_fields['c_record'] = 'yes';
			$form_fields_details = $form_fields;
			
		}else{
			
			$latestId = $DmiFirm->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
			$form_fields = $DmiFirm->find('all', array('conditions'=>array('id'=>MAX($latestId))))->first();		
			$form_fields['form_status'] = '';
			$form_fields['created'] = '';
			
			$form_fields['c_record'] = null;
			$form_fields_details = $form_fields;								
		}
				
		$commodities_detail = array();
		$sub_commodities_detail = array();

		$categoryList = array();
		if($form_fields_details['certification_type'] == 1 
			|| $form_fields_details['certification_type'] == 3){

			$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
			$commodities_detail = $MCommodity->find('list', array('valueField'=>'commodity_name','keyField'=>'commodity_code', 'conditions'=>array('category_code'=>$form_fields_details['commodity'])))->toArray();
			
			$sub_commodities_details = explode(',',$form_fields_details['sub_commodity']);
			$sub_commodities_detail = $MCommodity->find('list', array('valueField'=>'commodity_name','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$sub_commodities_details)))->toArray();
				
			$categoryList = $this->getCommodityCategoryList($form_fields_details);
		}	

		if($form_fields_details['certification_type'] == 1 && $application_type == 3){
			$this->getPaymentForCommodity($customer_id,$form_fields_details);
		}	

		return array($form_fields_details,$commodities_detail,$sub_commodities_detail,$categoryList);
	}
	
	
	public function saveFormDetails($customer_id,$forms_data){
		
		$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
		
		if($dataValidatation == 1 ){
			
			$CustomersController = new CustomersController;	
			$section_form_details = $this->sectionFormDetails($customer_id);
			
			$firm_name = htmlentities($forms_data['firm_name'], ENT_QUOTES);
			$mobile_no = htmlentities($forms_data['mobile_no'], ENT_QUOTES);
			$email = htmlentities($forms_data['email'], ENT_QUOTES);
			$fax_no = htmlentities($forms_data['fax_no'], ENT_QUOTES);
			
			$street_address = htmlentities($forms_data['street_address'], ENT_QUOTES);
			$postal_code = htmlentities($forms_data['postal_code'], ENT_QUOTES);
			
			if($section_form_details[0]['certification_type'] == 2){
				
				$packaging_materials =  implode(',',$forms_data['selected_packaging_materials']);
				$other_packaging_details =  htmlentities($forms_data['other_packaging_details'], ENT_QUOTES);
				$commodity = null;
				$sub_commodity = null;
			}else{
				$packaging_materials = null;
				$other_packaging_details = null;
				$commodity = htmlentities($forms_data['commodity'], ENT_QUOTES);
				$sub_commodity = implode(',',$forms_data['selected_commodity']);
			}
			
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
			else{ $created = $CustomersController->Customfunctions->dateFormatCheck($section_form_details[0]['created']); }
			
			$newEntity = $this->newEntity(array(			
				'id'=>$max_id,
				'customer_primary_id'=>$section_form_details[0]['customer_primary_id'],
				'customer_id'=>$customer_id,
				'certification_type'=>$section_form_details[0]['certification_type'],
				'firm_name'=>$firm_name,
				'commodity'=>$commodity,
				'sub_commodity'=>$sub_commodity,
				'packaging_materials'=>$packaging_materials,
				'other_packaging_details'=>$other_packaging_details,
				'street_address'=>$street_address,
				'state'=>$section_form_details[0]['state'],
				'district'=>$section_form_details[0]['district'],
				'postal_code'=>$postal_code,
				'email'=>base64_encode($email),//for email encoding
				'mobile_no'=>$mobile_no,
				'fax_no'=>$fax_no,
									
				'form_status'=>'saved',
				'customer_reply'=>$htmlencoded_reply,
				'customer_reply_date'=>$customer_reply_date,
				'cr_comment_ul'=>$cr_comment_ul,
				'created'=>$created,
				'modified'=>date('Y-m-d H:i:s')));
			
			if ($this->save($newEntity)){

				if($section_form_details[0]['certification_type'] == 1){
					$form_details = $this->sectionFormDetails($customer_id);
					
					$this->getPaymentForCommodity($customer_id,$form_details[0],$section_form_details[0]['sub_commodity'],'delPay');
				}	

				return 1; 
			}
			
				
		}else{	return false; }
	}
	
	
	// To save 	RO/SO referred back  and MO reply comment
	public function saveReferredBackComment($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to)
	{	
		$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
		
		if($dataValidatation == 1 && !empty($comment) ){
			
			$logged_in_user = $_SESSION['username'];
			$current_level = $_SESSION['current_level'];
				
			$CustomersController = new CustomersController;	
			$created = $CustomersController->Customfunctions->dateFormatCheck($forms_data['created']);
			$section_form_details = $this->sectionFormDetails($customer_id);
			
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
					'customer_primary_id'=>$section_form_details[0]['customer_primary_id'],
					'customer_id'=>$customer_id,
					'certification_type'=>$section_form_details[0]['certification_type'],
					'firm_name'=>$section_form_details[0]['firm_name'],
					'commodity'=>$section_form_details[0]['commodity'],
					'sub_commodity'=>$section_form_details[0]['sub_commodity'],
					'packaging_materials'=>$section_form_details[0]['packaging_materials'],
					'other_packaging_details'=>$section_form_details[0]['other_packaging_details'],
					'street_address'=>$section_form_details[0]['street_address'],
					'state'=>$section_form_details[0]['state'],
					'district'=>$section_form_details[0]['district'],
					'postal_code'=>$section_form_details[0]['postal_code'],
					'email'=>$section_form_details[0]['email'],
					'mobile_no'=>$section_form_details[0]['mobile_no'],
					'fax_no'=>$section_form_details[0]['fax_no'],
				
					'created'=>$created,
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
				return true;
			}
				
		}else{ return false; }
	
	}
	
	
	
	public function postDataValidation($customer_id,$forms_data){
		
		$returnValue = true;
		
		$section_form_details = $this->sectionFormDetails($customer_id);
		
		if(empty($forms_data['firm_name'])){ $returnValue = null ; }	
		if(!preg_match('/^[0-9]{10}+$/',$forms_data['mobile_no'])){ $returnValue = null ; }
		if(!filter_var($forms_data['email'], FILTER_VALIDATE_EMAIL)){ $returnValue = null ; }
		
		if(!empty($forms_data['fax_no'])){  
			if(!filter_var($forms_data['fax_no'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
		}
		
		if($section_form_details[0]['certification_type'] == 2){
		
			foreach($forms_data['selected_packaging_materials'] as $value){
				
				if(!filter_var($value, FILTER_VALIDATE_INT)){ $returnValue = null ; }
			}	
			
		}else{
			
			if(!filter_var($forms_data['commodity'], FILTER_VALIDATE_INT)){ $returnValue = null ; }
		
			if(array_key_exists('selected_commodity',$forms_data->toArray())){
				foreach($forms_data['selected_commodity'] as $value){
				
					if(!filter_var($value, FILTER_VALIDATE_INT)){ $returnValue = null ; }
				}
			}	
				
		}
		
		if(empty($forms_data['street_address'])){ $returnValue = null ; }	
		if(!preg_match('/^[0-9]{6}+$/',$forms_data['postal_code'])){ $returnValue = null ; }
				
		return $returnValue;		
			
	}
	
	
	public function getPaymentForCommodity($customer_id,$form_fields_details,$preData=null,$delPay=null){
		
		$DmiFirm = TableRegistry::getTableLocator()->get('DmiFirms');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
		
		$oldFirmDetails = $DmiFirm->firmDetails($customer_id);
		$sub_commodities_details = explode(',',$oldFirmDetails['sub_commodity']);
		$pre_sub_commodities = 	explode(',',$preData);	
		$sub_commodities = explode(',',$form_fields_details['sub_commodity']);
		
		$oldCategories = $MCommodity->find('list', array('valueField'=>'category_code','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$sub_commodities_details)))->toArray();
		$preCategories = $MCommodity->find('list', array('valueField'=>'category_code','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$pre_sub_commodities)))->toArray();
		$categories = $MCommodity->find('list', array('valueField'=>'category_code','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$sub_commodities)))->toArray();
				
		$results = array_diff(array_unique($categories),array_unique($oldCategories));
		$results1 = array_diff(array_unique($categories),array_unique($preCategories));

		$result = count($results);
		//print_r($result); exit;	
		if($result == 0){ 
			$_SESSION['paymentforchange'] = 'NA';
		}else{

			$_SESSION['paymentforchange'] = 'available';	
			
			if($preData !=null && count($results1) != 0){
				print_r($results1);
				print_r(array_unique($categories));
				print_r(array_unique($preCategories));
				if($delPay){
					$CustomersController = new CustomersController;	
					$DmiChangePaymentDetails = TableRegistry::getTableLocator()->get('DmiChangePaymentDetails');
					$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
					$DmiChangePaymentDetails->deleteAll(['customer_id'=>$customer_id,$grantDateCondition]);
				}

			}
			
		}

	}


	public function getCommodityCategoryList($formData)
	{
		$MCommodityCategory = TableRegistry::getTableLocator()->get('MCommodityCategory');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
		$CustomersController = new CustomersController;	
			
		if($formData['certification_type'] == 1 && $formData['form_status'] == 'referred_back'){
			
			$sub_commodities = explode(',',$formData['sub_commodity']);
			$categories = $MCommodity->find('list', array('valueField'=>'category_code','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$sub_commodities)))->toArray();
			$categoriesList = array_unique($categories);
			$commodityCategory = $MCommodityCategory->find('list',array('valueField'=>array('category_name'),'keyField'=>array('category_code'),'conditions'=>array('category_code IN'=>$categoriesList, 'display'=>'Y'),'order'=>array('category_code')))->toArray();

		}else{
			$commodityCategory = $CustomersController->Mastertablecontent->allCommodityCategories();
		}

		return $commodityCategory;

	}

} ?>