<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use Cake\Utility\Hash;
	use App\Controller\CustomersController;
	
class DmiFirmsTable extends Table{
	
	var $name = "DmiFirms";
	
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
			
	//validation ends		
			
			
	
	
	//find the newly added firms whose not final submiitted the application yet ( Done By pravin 1/11/2017) 
	public function newlyAddedFirmListReportConditions($application_type,$company_id,$state,$district,$search_from_date,$search_to_date,$search_flag) {		
		
		$Dmi_final_submit = TableRegistry::getTableLocator()->get('DmiFinalSubmits');
		$Dmi_district = TableRegistry::getTableLocator()->get('DmiDistricts');
		$Dmi_state = TableRegistry::getTableLocator()->get('DmiStates');		
		$CustomersController = new CustomersController;
		
		$application_type_array = array('A'=>'CA (Form-A)','C'=>'Laboratory (Form-C)','E'=>'CA (Form-E)','B'=>'Printing Press (Form-B)',
									'D'=>'Laboratory (Form-D)','F'=>'CA (Form-F)');
		$final_customer_id_list = array();   
		$firms_details = null;
		
		$company_id_not_empty = array(); 
		$application_type_not_empty = array(); 
		$state_not_empty = array(); 
		$district_not_empty = array(); 
		$date_not_empty = array();
		
		if($company_id != '') {			
			$company_id_not_empty = ['customer_primary_id LIKE'=> $company_id.'%']; // updated '%'.$company_id.'%' to $company_id.'%' by Ankur
		}
		
		if($application_type != '') {			
			$i=0;
			foreach($application_type as $certification_type) {				
				$application_type_id = $application_type_array[$certification_type];
				
				if($application_type_id == 'CA (Form-A)' || $application_type_id == 'CA (Form-F)' || $application_type_id == 'CA (Form-E)') {
					$certification_id[$i] = 1;
				}
				elseif($application_type_id == 'Laboratory (Form-D)' || $application_type_id == 'Laboratory (Form-C)') {
					$certification_id[$i] = 3;
				}
				elseif($application_type_id == 'Printing Press (Form-B)') {
					$certification_id[$i] = 2;
				}
				$i=$i+1;
			}			
			$application_type_not_empty = ['certification_type IN' => $certification_id];			
		}
		
		if($state != '') {			
			$state_not_empty = ['state' => $state]; 
		}
		
		if($district != '') {			
			$district_not_empty = ['district' => $district];
		}
		
		if($search_from_date != '' && $search_to_date != '') {			
			$date_not_empty = ['date(created) BETWEEN :start AND :end']; // updated by Ankur
		}		
		
		if($company_id == '' && $application_type == '' && $state == '' && $district == '' && $search_from_date == '' && $search_to_date == '' ) {			
			// $firms_details = $this->find('all',array('group'=>'id,customer_primary_id','order' => array('customer_primary_id'=>'desc')))->toArray();
			if ($search_flag == 'on') {
				$firms_details = $this->find('all')->select(['id', 'customer_primary_id', 'created', 'customer_id'])->group(['id', 'customer_primary_id'])
					->order(['created'=>'DESC'])->toArray(); // updated by Ankur
			}
			else {
				$firms_details = $this->find('all')->select(['id', 'customer_primary_id', 'created', 'customer_id'])->group(['id', 'customer_primary_id'])
					->order(['created'=>'DESC'])->limit(['200'])->toArray(); // updated by Ankur
			}
		}
		else {
			if($search_from_date != '' && $search_to_date != '') {
				$firms_details = $this->find('all')->select(['id', 'customer_primary_id', 'created', 'customer_id'])
				->where(Hash::merge($company_id_not_empty,$application_type_not_empty,$state_not_empty,$district_not_empty,$date_not_empty))
				->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->group(['id', 'customer_primary_id'])->order(['created'=>'DESC'])->toArray(); // updated by Ankur
			}	
			else {
				$firms_details = $this->find('all')->select(['id', 'customer_primary_id', 'created', 'customer_id'])
				->where(Hash::merge($company_id_not_empty,$application_type_not_empty,$state_not_empty,$district_not_empty,$date_not_empty))
				->group(['id', 'customer_primary_id'])->order(['created'=>'DESC'])->toArray(); // updated by Ankur
			}									
		}
		
		if(!empty($firms_details)) {
			$i=0;
			foreach($firms_details as $firms_data) {				
				// $firm_not_final_submitted = $Dmi_final_submit->find('all')->where(['customer_id IS'=>$firms_data['customer_id']])->first(); // updated by Ankur 
				$firm_not_final_submitted = $Dmi_final_submit->find('all')->select(['id'])->where(['customer_id IS'=>$firms_data['customer_id']])->extract('id')->first(); // updated by Ankur    
				
				if(empty($firm_not_final_submitted)) {					
					if(!empty($application_type)) {						
						$application_form_type = $CustomersController->Customfunctions->checkApplicantFormType($firms_data['customer_id']);
						
						if(in_array($application_form_type,$application_type) != '') {							
							$final_customer_id_list[$i] = $firms_data['customer_id'];
							$i=$i+1;
						}					
					} else {						
						$final_customer_id_list[$i] = $firms_data['customer_id']; 
						$i=$i+1;
					}
				} 
			}							
		}		
		return $final_customer_id_list;
	}
	
	/* fetch firm details (Done by pravin 11/09/2018) */
	public function firmDetails($customer_id){
		
		//added conditions for chemist flow, get details from chemist registration table
		//on 30-09-2021 by Amol
		if(preg_match("/^[A-Z]+\/[0-9]+\/[0-9]+$/", $customer_id,$matches)==1){
			
			$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
			$getDetails = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$customer_id)))->first();
			$getDetails['firm_name'] = $getDetails['chemist_fname'].' '.$getDetails['chemist_lname'];
			
			$firm_detail = $getDetails;
			
		}else{
			$firm_details = $this->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
			$firm_detail = $firm_details;
		}
		
		
		return $firm_detail;		
	}


	public function renewalFirmDetails($customer_id){
			
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		$firm_renewal_details = $this->find('all',array('conditions'=>array('id' => MAX($latest_id))))->first();	
		$firm_details_latest = $firm_renewal_details;
		return $firm_details_latest;		
	}
	
	public function checkCaBevo($customer_id){
					
		$split_customer_id = explode('/',$customer_id);

		if($split_customer_id[1] == 1)
		{
			$check_application_type = $this->find('all',array('fields'=>'commodity,export_unit','conditions'=>array('customer_id IS'=>$customer_id)))->first();
																	
			if($check_application_type['commodity'] == 106)
			{
				$applicant_bevo = 'yes';
			}
			else{
				
				$applicant_bevo = 'no';
			}
			
			return $applicant_bevo;
									
		}

	}
	
	//method to differentiate bet. Fat Spread and BEVO
	//below logic created on 27-03-2017 by Amol (by new flow)
	
	public function checkFatSpreadOrBevo($customer_id){
		
		if($this->checkCaBevo($customer_id) == 'yes')
		{
			$check_sub_commodities = $this->find('all',array('fields'=>'sub_commodity','conditions'=>array('customer_id IS'=>$customer_id)))->first();
			
			$sub_commodities_values = $check_sub_commodities['sub_commodity'];
			$sub_commodities_array = explode(',',$sub_commodities_values);
			
			$bevo = 'no';
			$fat_spread = 'no';
			foreach($sub_commodities_array as $each_sub_commodity)
			{
				if($each_sub_commodity == '172')
				{
					$bevo = 'yes';
				}
				if($each_sub_commodity == '173')
				{
					$fat_spread = 'yes';
				}				
			}
			
		//if($bevo == 'yes' && $fat_spread == 'yes')
			//{
				$applicant_type = 'both';
			//}
			if($bevo == 'yes')
			{							
				$applicant_type = 'bevo';
			}
			elseif($fat_spread == 'yes')
			{							
				$applicant_type = 'fat_spread';
			}
						
			return $applicant_type;
			
		}
		
	}	
	

} ?>