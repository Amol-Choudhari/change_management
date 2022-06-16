<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiCaDomesticRenewalDetailsTable extends Table{

		var $name = "DmiCaDomesticRenewalDetails";
		
		public $validate = array(
		
			'fullfill_minimum_quantity'=>array(
				'rule'=>array('maxLength',10),
				'allowEmpty'=>false,
			),			
		);
		
		
		
		// Fetch form section all details
		public function sectionFormDetails($customer_id)
		{
			$CustomersController = new CustomersController;	
			$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
		
			$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition )))->toArray();
					
			if($latest_id != null){
				$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
				$form_fields_details = $form_fields;
				
			}else{
				$form_fields_details = Array ( 'id'=>"",'customer_id' =>"", 'customer_once_no' => "", 'user_email_id' => "", 'user_once_no' => "", 'referred_back_comment' => "",
											   'referred_back_date' => "", 'customer_reply' => "", 'customer_reply_date' => "", 'form_status' => "", 'approved_date' =>"", 'current_level' =>"",
											   'fullfill_minimum_quantity' =>"yes", 'renewed_upto_date' =>"", 'delete_mo_comment' =>"", 'delete_ro_referred_back' =>"", 'mo_comment' =>"", 'mo_comment_date' =>"",
											   'ro_reply_comment' =>"", 'ro_reply_comment_date' =>"", 'created' => "", 'modified' =>"", 'ro_current_comment_to' => "",'rb_comment_ul'=>"",'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>""); 
				
			}
			
			$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
			$firm_details = $Dmi_firm->firmDetails($customer_id);
			
			$M_commodity = TableRegistry::getTableLocator()->get('MCommodity');
			$sub_comm_id = explode(',',$firm_details['sub_commodity']);	
			$sub_commodity_value = $M_commodity->find('list',array('valueField'=>'commodity_name','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toArray();
			
			$DmiCustomerLaboratoryDetails = TableRegistry::getTableLocator()->get('DmiCustomerLaboratoryDetails');
			$LaboratoryDetails = $DmiCustomerLaboratoryDetails->sectionFormDetails($customer_id);
			
			$financialYears = $this->financialYear($customer_id, $sub_commodity_value, $form_fields_details);
			$get_cat_min_value = $this->MinCategoryValue($sub_commodity_value);		
				
			return array($form_fields_details,$sub_commodity_value,$LaboratoryDetails,$financialYears,$get_cat_min_value);
				
		}		
		
		
		// save or update form data and comment reply by applicant
		public function saveFormDetails($customer_id,$forms_data){
			
			$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
			$DmiCaRenewalCommodityDetails = TableRegistry::getTableLocator()->get('DmiCaRenewalCommodityDetails');
			$form_details = $this->sectionFormDetails($customer_id);
			$firm_details = $Dmi_firm->firmDetails($customer_id);
			
			$quantityGradedTotal = $this->quantityGradedTotal($form_details[4][0],$form_details[1],$form_details[3][0],$forms_data);
			
			if($quantityGradedTotal[1]=='no'){				
				return $quantityGradedTotal;
				exit;				
			}			

			$CustomersController = new CustomersController;			
			$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id); 
			
			//checking radio buttons input
			$post_input_request = $forms_data['fullfill_minimum_quantity'];				
			$fullfill_minimum_quantity = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
			if($fullfill_minimum_quantity == null){ return false;}
			
			$i=0;
			foreach($form_details[1] as $each_commodity)
			{
				$commodity_name[$i] = htmlentities($forms_data['commodity_name'.$i], ENT_QUOTES);
				
				$y=0;
				foreach($form_details[3][0] as $each_year)
				{
					$year_value[$i.$y] = htmlentities($forms_data['year'.$i.$y], ENT_QUOTES);
					$quantity_graded_value[$i.$y] = htmlentities($forms_data['quantity_graded'.$i.$y], ENT_QUOTES);
					$y=$y+1;
				}
				
				$i=$i+1;
			}
			
			//fetch last records of current applicant in table and set is_latest to 'no'
			$last_grading_records = $DmiCaRenewalCommodityDetails->find('list',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
			
			if(!empty($last_grading_records))
			{          
				foreach($last_grading_records as $each_id)
				{
					$record_id[] = array(						
						'id'=>$each_id,
						'is_latest'=>'no',
						'modified'=>date('Y-m-d H:i:s')		
					);
				}
				$entities = $DmiCaRenewalCommodityDetails->newEntities($record_id);
				foreach ($entities as $entity) {
					// Save entity
					$DmiCaRenewalCommodityDetails->save($entity);
				}
			}
			
			//save fetched data loop wise in renewal commodity details table
			$i=0;
			foreach($form_details[1] as $each_commodity)
			{
				$y=0;
				foreach($form_details[3][0] as $each_year)
				{
					$detailsValue[] = array(						
						'customer_id'=>$customer_id,
						'customer_once_no'=>$_SESSION['once_card_no'],
						'commodity_name'=>$commodity_name[$i],
						'year'=>$year_value[$i.$y],
						'quantity_graded'=>$quantity_graded_value[$i.$y],
						'is_latest'=>'yes',
						'created'=>date('Y-m-d H:i:s')
					);
					$y=$y+1;
				}				
			$i=$i+1;
			}
			
			$detailsValueentities = $DmiCaRenewalCommodityDetails->newEntities($detailsValue);
			foreach ($detailsValueentities as $entity) {
				// Save entity
				$DmiCaRenewalCommodityDetails->save($entity);
			}
										
			// If applicant have referred back on give section				
			if($form_details[0]['form_status'] == 'referred_back'){
				
				$max_id = $form_details[0]['id'];
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

			if(empty($form_details[0]['created'])){  $created = date('Y-m-d H:i:s'); }
			//added date function on 31-05-2021 by Amol to convert date format, as saving null
			else{ $created = $CustomersController->Customfunctions->changeDateFormat($form_details[0]['created']); }
			
			$newEntity = $this->newEntity(array(
			
				'id'=>$max_id,
				'customer_id'=>$customer_id,
				'once_card_no'=>$_SESSION['once_card_no'],
				'fullfill_minimum_quantity'=>$fullfill_minimum_quantity,					
				'form_status'=>'saved',
				'customer_reply'=>$htmlencoded_reply,
				'customer_reply_date'=>$customer_reply_date,
				'cr_comment_ul'=>$cr_comment_ul,
				'created'=>$created,
				'modified'=>date('Y-m-d H:i:s')));
			
			unset($_SESSION['show_not_fullfilled_msg']);
			
			if ($this->save($newEntity)){ return 1; }					
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
				'once_card_no'=>$forms_data['once_card_no'],
				'fullfill_minimum_quantity'=>$forms_data['fullfill_minimum_quantity'],
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

		public function financialYear($customer_id,$commodities,$form_fields_details){
			
			//print_r($form_fields_details); exit;

			$DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');
		
			$get_last_grant_list = $DmiGrantCertificatesPdfs->find('list',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();

			$get_last_grant_date = $DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('id'=>max($get_last_grant_list))))->first();


			$last_grant_date = $get_last_grant_date['date'];
			
			//added on 24-08-2017 by Amol//to get last 5 years from valid upto date
			$CustomersController = new CustomersController;
			$certificate_valid_upto = $CustomersController->Customfunctions->getCertificateValidUptoDate($customer_id,$last_grant_date);
			$certificate_valid_upto = strtotime($certificate_valid_upto);				
			//for each commodity from selected
			
			if($form_fields_details['id']==""){
				// find last 5 years	
				$i = 5;
				$j = 1;	
				while($i >= 1)
				{
					$year_count = '-'.$j;
					$incremented_year_count = '-'.$j+1;//added on 23-08-2017 by Amol
					$incremented_year[$i] = date("y", strtotime($incremented_year_count." years",$certificate_valid_upto));//added on 23-08-2017 by Amol
					$year[$i] = date("Y", strtotime($year_count." years",$certificate_valid_upto)).'-'.$incremented_year[$i];
						
				$i = $i-1;
				$j = $j+1;
				}
				
				$i = 0;
				$quantity_graded = array();//this line added on 25-03-2019 by Amol
				foreach($commodities as $each_commodity)
				{
					foreach($year as $each_grading)
					{
						$quantity_graded[$i] = null;				
					
					$i = $i+1;
					}
				}
				
			}else{
					$DmiCaRenewalCommodityDetails = TableRegistry::getTableLocator()->get('DmiCaRenewalCommodityDetails');
					
					//last records from renewal commodity details table
					$last_commodity_gradings = $DmiCaRenewalCommodityDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'is_latest'=>'yes')))->toArray();
					
					$i=0;
					foreach($last_commodity_gradings as $each_grading)
					{
						$last_gradings_years = $DmiCaRenewalCommodityDetails->find('all',array('fields'=>'year','conditions'=>array('customer_id IS'=>$customer_id,'is_latest'=>'yes'),'group'=>'year','order'=>'year'))->toArray();
						
						$p=1;
						foreach($last_gradings_years as $each_year)
						{
							$year[$p]= $each_year['year'];
							
						$p=$p+1;
						}
						
						$quantity_graded[$i] = $each_grading['quantity_graded'];
					
					$i=$i+1;	
					}
			}
			return array($year,$quantity_graded);
			
		}	
		
		
		public function MinCategoryValue($commodities){
			
			$category_code=array();
			$j=0;
			
			$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
			$MCommodityCategory = TableRegistry::getTableLocator()->get('MCommodityCategory');
			
			foreach($commodities as $each_commodity)
			{					
				$get_category_code = $MCommodity->find('all',array('fields'=>'category_code','conditions'=>array('commodity_name IS'=>$each_commodity,'display'=>'Y')))->first();
				
				if($j>0)
				{
					if($category_code[$j-1] != $get_category_code['category_code'])
					{
						$category_code[$j] = $get_category_code['category_code'];						
						$j=$j+1;
					}
				}else{
					$category_code[$j] = $get_category_code['category_code'];					
					$j=$j+1;
					
				}				
			}
			
			//to get minimum quantity for taken main categories from table field min_quanity.
			$get_cat_min_value = $MCommodityCategory->find('all',array('conditions'=>array('category_code IN'=>$category_code)))->toArray();
			return array($category_code,$get_cat_min_value);
		}
		
		
		public function quantityGradedTotal($category_code,$commodities,$year,$formData){
			
			$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
			$MCommodityCategory = TableRegistry::getTableLocator()->get('MCommodityCategory');
			
			//to get the total quantity for each main category, filled by Applicant in from's sub commodities
			$quantity_graded_total=array();
			$sum_value[-1] =0;
			$cid=0;
			foreach($category_code as $cat_code)
			{
				$i=0;
				$x=0;
				foreach($commodities as $each_commodity)
				{
					$commodity_name[$i] = htmlentities($formData['commodity_name'.$i], ENT_QUOTES);
					
					$get_category_code = $MCommodity->find('all',array('fields'=>'category_code','conditions'=>array('commodity_name IS'=>$commodity_name[$i],'display'=>'Y')))->first();
					
					if($cat_code == $get_category_code['category_code'])
					{			
						$y=0;
						foreach($year as $each_year)
						{
							$quantity_graded_total[$cid] = $sum_value[$x-1] + htmlentities($formData['quantity_graded'.$i.$y], ENT_QUOTES);
							$sum_value[$x]= $quantity_graded_total[$cid];
							$y=$y+1;
							$x=$x+1;
						}							
					}
					
					$i=$i+1;
				}
				
			$cid=$cid+1;
			}
			
			
			
			
			//to check if the total of each category fullfilled the minimum quantity of that category
			//and get the categories names which not fullfulled.
			$not_fulfilled_cat_id=array();
			$quantity_fulfilled = 'yes';
			$not_fullfilled_msg = '';
			$quantity_graded=array();
			$i=0;
			foreach($category_code as $cat_code)
			{
				$get_min_val = $MCommodityCategory->find('all',array('fields'=>'min_quantity','conditions'=>array('category_code IS'=>$cat_code)))->first();
				$min_quantity = $get_min_val['min_quantity'];
				
				if($min_quantity > $quantity_graded_total[$i])
				{
					$quantity_fulfilled = 'no';
					$not_fulfilled_cat_id[$i]=$cat_code;
					
					$get_cat_name = $MCommodityCategory->find('list',array('keyField'=>'category_code','valueField'=>'category_name','conditions'=>array('category_code IN'=>$not_fulfilled_cat_id)))->toArray();
					
				}
				
				$i=$i+1;	
			}
			
			
			//this condition added on 11-08-2017 by Amol to check quantity one time only on save
			if(!isset($_SESSION['show_not_fullfilled_msg']))
			{				
				//if any of the total not fullfilled the minimum quantity, show message with minimum and total quanity table.
				if($quantity_fulfilled == 'no')
				{
					$cat_name = implode(',',$get_cat_name);

					$not_fullfilled_msg = "Warning: Your Commodity categories ($cat_name) did not fulfilled the minimum grading quantity criteria in 5 years.";
					
					//added session variable to maintain when to check the quantity camparision
					$_SESSION['show_not_fullfilled_msg'] = 'yes';					
					
					// to take the filled values from table and show as it is when return false. 					
				}
			}else{ $quantity_fulfilled = 'yes'; }
			
			$i=0;
			$x=0;
			foreach($commodities as $each_commodity)
			{						
				$y=0;
				foreach($year as $each_year)
				{
					$quantity_graded[$x] = htmlentities($formData['quantity_graded'.$i.$y], ENT_QUOTES);
					$y=$y+1;
					$x=$x+1;
				}
				$i=$i+1;	
			}
					
			return array($quantity_graded_total,$quantity_fulfilled,$not_fullfilled_msg,$quantity_graded);
			
		}

} ?>