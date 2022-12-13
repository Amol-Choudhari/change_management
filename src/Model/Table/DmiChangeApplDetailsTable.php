<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiChangeApplDetailsTable extends Table{

		var $name = "DmiChangeApplDetails";
		
		public $validate = array();
		
		
		
		// Fetch form section all details
		public function sectionFormDetails($customer_id)
		{
			$form_fields = $this->find('all', array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
					
			if($form_fields != null){		
				$form_fields_details = $form_fields;
				$DmiDistricts = TableRegistry::getTableLocator()->get('DmiDistricts');
				$DistList = $DmiDistricts->find('list',array('keyField'=>'id','valueField'=>'district_name','conditions'=>array('state_id'=>$form_fields['premise_state'],'delete_status IS NULL'),'order'=>'district_name asc'),)->toArray();
				$form_fields_details['dist_list'] = $DistList;
				
			}else{
				$form_fields_details = Array ( 'id'=>"", 'firm_name' =>"",'premise_state'=>"", 'premise_street' => "", 'premise_city' => "", 'premise_pin' => "", 'const_of_firm' => "",
											   'mobile_no' => "", 'email_id' => "", 'phone_no' => "", 'comm_category' => "", 'commodity' =>"", 'lab_type' =>"",
											   'lab_name' =>"", 'lab_consent_docs' =>"", 'lab_equipped_docs' =>"", 'chemist_details_docs' =>"", 'packing_types' =>"", 'created' => "", 'modified' =>"", 'customer_id' => "", 'reffered_back_comment' => "",
											   'reffered_back_date' => "", 'form_status' =>"", 'customer_reply' =>"", 'customer_reply_date' =>"", 'approved_date' => "",
											   'user_email_id' => "", 'current_level' => "",'mo_comment' =>"", 'mo_comment_date' => "", 'ro_reply_comment' =>"", 'ro_reply_comment_date' =>"", 'delete_mo_comment' =>"", 'delete_ro_reply' => "",
											   'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "",
											   'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>"",'dist_list'=>""); 
				
			}
			
			$CustomersController = new CustomersController;
			
			$firm_type = $CustomersController->Customfunctions->firmType($customer_id);
			
			$commOrPackingTypeResult = $this->getChangeCommodityDetails($form_fields_details,$firm_type);
			$form_fields_details['packing_types'] = $commOrPackingTypeResult[2];
			$form_fields_details['commodity'] = $commOrPackingTypeResult[1];
			$form_fields_details['comm_category_list'] = $commOrPackingTypeResult[0];
			$form_fields_details['commodity_list'] = $commOrPackingTypeResult[3];
			
			
			//for firm details
			$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
			$firm_details = $DmiFirms->firmDetails($customer_id);

			//premises details
			$DmiCustomerPremisesProfiles = TableRegistry::getTableLocator()->get('DmiCustomerPremisesProfiles');
			$premises_details = $DmiCustomerPremisesProfiles->find('all',array('fields'=>array('street_address','state','district','postal_code'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			
			//tbl details
			$DmiChangeAllTblsDetails = TableRegistry::getTableLocator()->get('DmiChangeAllTblsDetails');
			$checkChangeTbl = $DmiChangeAllTblsDetails->find('all',array('fields'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->first();
			
			//for first time only
			if(empty($checkChangeTbl)){
				//fetch last details
				$DmiAllTblsDetails = TableRegistry::getTableLocator()->get('DmiAllTblsDetails');
				$getLastTbls = $DmiAllTblsDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id asc'))->toArray();
				$dataArr = array();
				foreach($getLastTbls as $each){
					$dataArr[] = array(
						'customer_id'=>$customer_id,
						'tbl_name'=>$each['tbl_name'],
						'tbl_registered'=>$each['tbl_registered'],
						'tbl_registered_no'=>$each['tbl_registered_no'],
						'tbl_registration_docs'=>$each['tbl_registration_docs'],
						'created'=>$CustomersController->Customfunctions->changeDateFormat($each['created']),
						'modified'=>$CustomersController->Customfunctions->changeDateFormat($each['modified'])
					);
				}
				//save last details in change tbl table
				$ChangeTblsEntity = $DmiChangeAllTblsDetails->newEntities($dataArr);
				foreach($ChangeTblsEntity as $each){
					$DmiChangeAllTblsDetails->save($each);
				}
			}
			$added_tbls_details = $DmiChangeAllTblsDetails->tblsDetails();
			
			//for director details
			$DmiChangeDirectorsDetails = TableRegistry::getTableLocator()->get('DmiChangeDirectorsDetails');
			$checkChangeDirector = $DmiChangeDirectorsDetails->find('all',array('fields'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->first();
			
			//for first time only
			if(empty($checkChangeDirector)){
				//fetch last details
				$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiAllDirectorsDetails');
				$getLastDirector = $DmiAllDirectorsDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id asc'))->toArray();
				$dataArr = array();
				foreach($getLastDirector as $each){
					$dataArr[] = array(
						'customer_id'=>$customer_id,
						'user_email_id'=>$each['user_email_id'],
						'd_name'=>$each['d_name'],
						'd_address'=>$each['d_address'],
						'created'=>$CustomersController->Customfunctions->changeDateFormat($each['created']),
						'modified'=>$CustomersController->Customfunctions->changeDateFormat($each['modified'])
					);
				}
				//save last details in change tbl table
				$ChangeDirectorEntity = $DmiChangeDirectorsDetails->newEntities($dataArr);
				foreach($ChangeDirectorEntity as $each){
					$DmiChangeDirectorsDetails->save($each);
				}
			}
			$added_directors_details = $DmiChangeDirectorsDetails->allDirectorsDetail($customer_id);			
					
			//loboratory details
			$DmiCustomerLaboratoryDetails = TableRegistry::getTableLocator()->get('DmiCustomerLaboratoryDetails');
			$laboratory_types = $CustomersController->Mastertablecontent->allLaboratoryType();
			$fetchlabDetails = $DmiCustomerLaboratoryDetails->find('all',array('fields'=>array('laboratory_name','laboratory_type','consent_letter_docs','chemist_detail_docs','lab_equipped_docs'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			$labDetails = array($laboratory_types,$fetchlabDetails);		
					
			return array($form_fields_details,$firm_details,$premises_details,$added_tbls_details,$added_directors_details,$labDetails);
				
		}		
		
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
			
			$dataValidatation = $this->postDataValidation($customer_id,$forms_data);
			
			if($dataValidatation == 1 ){
				
				$CustomersController = new CustomersController;
	
				$section_form_details = $this->sectionFormDetails($customer_id);
				
				$DmiChangeSelectedFields = TableRegistry::getTableLocator()->get('DmiChangeSelectedFields');
				$selectedfields = $DmiChangeSelectedFields->selectedChangeFields();
				$selectedValues = $selectedfields[0];
				
				$firm_type = $CustomersController->Customfunctions->firmType($customer_id);

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
				
				
				
				$dataArray = array();
				//data array for selected fields only
				if(in_array(1,$selectedValues)){
					$dataArray = array_merge($dataArray,array('firm_name'=>htmlentities($forms_data['firm_name'], ENT_QUOTES)));
				}
				if(in_array(2,$selectedValues)){
					$dataArray = array_merge($dataArray,array(
						'mobile_no'=>htmlentities($forms_data['mobile_no'], ENT_QUOTES),
						'email_id'=>htmlentities($forms_data['email_id'], ENT_QUOTES),
						'phone_no'=>htmlentities($forms_data['phone_no'], ENT_QUOTES),
					));
				}
				if(in_array(5,$selectedValues)){
					$dataArray = array_merge($dataArray,array(
						'premise_street'=>htmlentities($forms_data['premise_street'], ENT_QUOTES),
						'premise_state'=>htmlentities($forms_data['premise_state'], ENT_QUOTES),
						'premise_city'=>htmlentities($forms_data['premise_city'], ENT_QUOTES),
						'premise_pin'=>htmlentities($forms_data['premise_pin'], ENT_QUOTES),
					));
				}
				if(in_array(6,$selectedValues)){
					
					if(!empty($forms_data['chemist_details_docs']->getClientFilename())){

						$file_name = $forms_data['chemist_details_docs']->getClientFilename();
						$file_size = $forms_data['chemist_details_docs']->getSize();
						$file_type = $forms_data['chemist_details_docs']->getClientMediaType();
						$file_local_path = $forms_data['chemist_details_docs']->getStream()->getMetadata('uri');
													
						$chemist_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $chemist_details_docs = $section_form_details[0]['chemist_details_docs']; }
					
					if(!empty($forms_data['lab_equipped_docs']->getClientFilename())){

						$file_name = $forms_data['lab_equipped_docs']->getClientFilename();
						$file_size = $forms_data['lab_equipped_docs']->getSize();
						$file_type = $forms_data['lab_equipped_docs']->getClientMediaType();
						$file_local_path = $forms_data['lab_equipped_docs']->getStream()->getMetadata('uri');
													
						$lab_equipped_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $lab_equipped_docs = $section_form_details[0]['lab_equipped_docs']; }
					
					if(!empty($forms_data['lab_consent_docs']->getClientFilename())){

						$file_name = $forms_data['lab_consent_docs']->getClientFilename();
						$file_size = $forms_data['lab_consent_docs']->getSize();
						$file_type = $forms_data['lab_consent_docs']->getClientMediaType();
						$file_local_path = $forms_data['lab_consent_docs']->getStream()->getMetadata('uri');
													
						$lab_consent_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
					
					}else{ $lab_consent_docs = $section_form_details[0]['lab_consent_docs']; }
					
					$dataArray = array_merge($dataArray,array(
						'lab_name'=>htmlentities($forms_data['lab_name'], ENT_QUOTES),
						'lab_type'=>htmlentities($forms_data['lab_type'], ENT_QUOTES),
						'chemist_details_docs'=>$chemist_details_docs,
						'lab_equipped_docs'=>$lab_equipped_docs,
						'lab_consent_docs'=>$lab_consent_docs,
					));
				}
				if(in_array(7,$selectedValues)){
				
					if ($firm_type==1 || $firm_type==3) {
						
						$selected_commodity = implode(',',$forms_data['selected_commodity']);
						$dataArray = array_merge($dataArray,array(
							'comm_category'=>htmlentities($forms_data['comm_category'], ENT_QUOTES),
							'commodity'=>htmlentities($selected_commodity, ENT_QUOTES),
						));
						
					} elseif ($firm_type==2) {
						
						$selected_packing_types = implode(',',$forms_data['selected_packing_types']);
						$dataArray = array_merge($dataArray,array(
							'packing_types'=>htmlentities($selected_packing_types, ENT_QUOTES),
						));
						
					}
				}
				
				//common required fields
				$commonArr = array(				
					'id'=>$max_id,
					'customer_id'=>$customer_id,
					'form_status'=>'saved',
					'customer_reply'=>$htmlencoded_reply,
					'customer_reply_date'=>$customer_reply_date,
					'cr_comment_ul'=>$cr_comment_ul,
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s')
				);
				
				$dataArray = array_merge($dataArray,$commonArr);
				
				$newEntity = $this->newEntity($dataArray);
				
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
			
			$firm_type = $CustomersController->Customfunctions->firmType($customer_id);
			
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
			
			$dataArray = array();
			//data array for selected fields only
			if(in_array(1,$selectedValues)){
				$dataArray = array_merge($dataArray,array('firm_name'=>htmlentities($forms_data['firm_name'], ENT_QUOTES)));
			}
			if(in_array(2,$selectedValues)){
				$dataArray = array_merge($dataArray,array(
					'mobile_no'=>htmlentities($forms_data['mobile_no'], ENT_QUOTES),
					'email_id'=>htmlentities($forms_data['email_id'], ENT_QUOTES),
					'phone_no'=>htmlentities($forms_data['phone_no'], ENT_QUOTES),
				));
			}
			if(in_array(5,$selectedValues)){
				$dataArray = array_merge($dataArray,array(
					'premise_street'=>htmlentities($forms_data['premise_street'], ENT_QUOTES),
					'premise_state'=>htmlentities($forms_data['premise_state'], ENT_QUOTES),
					'premise_city'=>htmlentities($forms_data['premise_city'], ENT_QUOTES),
					'premise_pin'=>htmlentities($forms_data['premise_pin'], ENT_QUOTES),
				));
			}
			if(in_array(6,$selectedValues)){
				
				if(!empty($forms_data['chemist_details_docs']->getClientFilename())){

					$file_name = $forms_data['chemist_details_docs']->getClientFilename();
					$file_size = $forms_data['chemist_details_docs']->getSize();
					$file_type = $forms_data['chemist_details_docs']->getClientMediaType();
					$file_local_path = $forms_data['chemist_details_docs']->getStream()->getMetadata('uri');
												
					$chemist_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
				
				}else{ $chemist_details_docs = $section_form_details[0]['chemist_details_docs']; }
				
				if(!empty($forms_data['lab_equipped_docs']->getClientFilename())){

					$file_name = $forms_data['lab_equipped_docs']->getClientFilename();
					$file_size = $forms_data['lab_equipped_docs']->getSize();
					$file_type = $forms_data['lab_equipped_docs']->getClientMediaType();
					$file_local_path = $forms_data['lab_equipped_docs']->getStream()->getMetadata('uri');
												
					$lab_equipped_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
				
				}else{ $lab_equipped_docs = $section_form_details[0]['lab_equipped_docs']; }
				
				if(!empty($forms_data['lab_consent_docs']->getClientFilename())){

					$file_name = $forms_data['lab_consent_docs']->getClientFilename();
					$file_size = $forms_data['lab_consent_docs']->getSize();
					$file_type = $forms_data['lab_consent_docs']->getClientMediaType();
					$file_local_path = $forms_data['lab_consent_docs']->getStream()->getMetadata('uri');
												
					$lab_consent_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function		
				
				}else{ $lab_consent_docs = $section_form_details[0]['lab_consent_docs']; }
				
				$dataArray = array_merge($dataArray,array(
					'lab_name'=>htmlentities($forms_data['lab_name'], ENT_QUOTES),
					'lab_type'=>htmlentities($forms_data['lab_type'], ENT_QUOTES),
					'chemist_details_docs'=>$chemist_details_docs,
					'lab_equipped_docs'=>$lab_equipped_docs,
					'lab_consent_docs'=>$lab_consent_docs,
				));
			}
			if(in_array(7,$selectedValues)){
			
				if ($firm_type==1 || $firm_type==3) {
					$dataArray = array_merge($dataArray,array(
						'comm_category'=>htmlentities($forms_data['comm_category'], ENT_QUOTES),
						'commodity'=>htmlentities($forms_data['selected_commodity'], ENT_QUOTES),
					));
					
				} elseif ($firm_type==2) {
					$dataArray = array_merge($dataArray,array(
						'packing_types'=>htmlentities($forms_data['selected_packing_types'], ENT_QUOTES),
					));
					
				}
			}
			
			//common required fields
			$commonArr = array(				

				'customer_id'=>$customer_id,
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
			);
			
			$dataArray = array_merge($dataArray,$commonArr);
			
			$newEntity = $this->newEntity($dataArray);
			
			if($this->save($newEntity)){ 
			
				return true; 
			}

		}


		public function postDataValidation($customer_id,$forms_data){
		//	print_r($forms_data); exit;
			$returnValue = true;
			$DmiChangeSelectedFields = TableRegistry::getTableLocator()->get('DmiChangeSelectedFields');
			$selectedfields = $DmiChangeSelectedFields->selectedChangeFields();
			$selectedValues = $selectedfields[0];
			
			$CustomersController = new CustomersController;
			$firm_type = $CustomersController->Customfunctions->firmType($customer_id);
						
			if(in_array(1,$selectedValues) && empty($forms_data['firm_name'])){ $returnValue = null ; }
			
			if(in_array(2,$selectedValues)){ 
			
				if(empty($forms_data['mobile_no']) || empty($forms_data['email_id']) || empty($forms_data['phone_no'])){
					$returnValue = null ; 
				}
			
			}	
			if(in_array(5,$selectedValues)){ 
			
				if(empty($forms_data['premise_street']) || empty($forms_data['premise_state']) || empty($forms_data['premise_city']) || empty($forms_data['premise_pin'])){
					$returnValue = null ; 
				}
			
			}	
			if(in_array(6,$selectedValues)){ 
			
				if(empty($forms_data['lab_name']) || empty($forms_data['lab_type'])){
					
					if($forms_data['lab_type']==1){
						if(empty($forms_data['lab_equipped_docs']->getClientFilename())){ $returnValue = null ; }
						if(empty($forms_data['chemist_details_docs']->getClientFilename())){ $returnValue = null ; }
					}else{
						if(empty($forms_data['lab_consent_docs']->getClientFilename())){ $returnValue = null ; }				
					}

				}
			
			}
			if(in_array(7,$selectedValues)){ 
			
				if ($firm_type==1 || $firm_type==3) {
					if(empty($forms_data['comm_category']) || empty($forms_data['selected_commodity'])){
						$returnValue = null ; 
					}
				}
				if ($firm_type==2) {
					if(empty($forms_data['selected_packing_types'])){
						$returnValue = null ; 
					}
					
				}
				
			
			}
			
			return $returnValue;
			
		}
		
		
		//thie is created to display firm change commodity details,
		//on 02-07-2021 by Amol
		public function getChangeCommodityDetails($firm_details,$firm_type){

			//load models
			$categoryTable = TableRegistry::getTableLocator()->get('MCommodityCategory');
			$commodityTable = TableRegistry::getTableLocator()->get('MCommodity');
			$packingTypeTable = TableRegistry::getTableLocator()->get('DmiPackingTypes');

			$category_list = array();
			$selected_commodities = array();
			$selected_packing_types = array();
			$selected_category_commodities = array();
			
			if($firm_type==1){

				//in CA to show only already selected category list, to avoid payment amount conflict
				$commodity_array = explode(',',$firm_details['commodity']);

				$i=0;
				foreach($commodity_array as $commodity_id)
				{
					$fetch_commodity_id = $commodityTable->find('all',array('fields'=>'category_code','conditions'=>array('commodity_code IS'=>(int) $commodity_id)))->first();
					$category_id[$i] = $fetch_commodity_id['category_code'];
					$sub_commodity_data[$i] =  $fetch_commodity_id;
					$i=$i+1;
				}

				$category_id_list = array_unique($category_id);

				$category_list = $categoryTable->find('list',array('keyField'=>'category_code','valueField'=>'category_name','conditions'=>array('category_code IN'=>$category_id_list)))->toArray();

				$sub_comm_id = explode(',',$firm_details['commodity']);
				
				if(!empty($sub_comm_id)){
					
					$selected_commodities = $commodityTable->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=> $sub_comm_id)))->toArray();
				}

				
			}elseif($firm_type==2){

				$packing_types = $packingTypeTable->find('list',array('keyField'=>'id','valueField'=>'packing_type','conditions'=>array('delete_status IS Null')))->toArray();

				$packaging_type_id = explode(',',$firm_details['packing_types']);

				if(!empty($packaging_type_id)){
					
					$selected_packing_types = $packingTypeTable->find('list',array('keyField'=>'id','valueField'=>'packing_type', 'conditions'=>array('id IN'=> $packaging_type_id)))->toArray();
				}
				
			}elseif($firm_type==3){

				$category_list = $categoryTable->find('list',array('keyField'=>'category_code','valueField'=>'category_name','conditions'=>array('display'=>'Y')))->toArray();

				$sub_comm_id = explode(',',$firm_details['commodity']);

				if(!empty($sub_comm_id)){
					
					$selected_commodities = $commodityTable->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>(int) $sub_comm_id)))->toArray();
				}
				

			}
			
			if(!empty($category_id_list)){
				
				$selected_category_commodities = $commodityTable->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name','conditions'=>array('category_code IN'=>$category_id_list)))->toArray();
			}
			
			return array($category_list,$selected_commodities,$selected_packing_types,$selected_category_commodities);
		}

} ?>