<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Model\Table\DmiFirmsTable;

class DmiCustomersTable extends Table{
	
	var $name = "Dmi_customer";
	
	public $validate = array(
			'customer_id'=>array(
				'rule1'=>array(
						'rule'=>array('maxLength',20),
						'allowEmpty' => false,
						'message'=>'Company/Premises Id is Not Valid',
						'last' => false
					),
			/*	'rule2'=>array(
						'rule'=>'alphaNumeric',	
						'message'=>'Company/Premises Id is Not Valid'
					),		*/						
				),					
						
			'password'=>array(
					'rule'=>array('maxLength',100),
					'allowEmpty' => false,
					'message'=>'Password is Not Valid',
				),
					
			'f_name'=>array(
					'rule'=>array('maxLength',50),
					'allowEmpty' => false,
					'message'=>'First Name is Not Valid',
				),
				
			'm_name'=>array(
					'rule'=>array('maxLength',50),
					'message'=>'Middle Name is Not Valid',
				),
				
			'l_name'=>array(
					'rule'=>array('maxLength',50),
					'allowEmpty' => false,
					'message'=>'Last Name is Not Valid',
				),
				
			'street_address'=>array(
					'rule'=>array('maxLength',200),
					'allowEmpty' => false,
					'message'=>'Address is Not Valid',
				),
				
			'district'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',20),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
				
			'state'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',20),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
				
			'postal_code'=>array(
					'rule'=>array('maxLength',20),
					'allowEmpty' => false,
					'message'=>'Postal Code is Not Valid',
				),
				
			'mobile'=>array(
					'rule'=>array('maxLength',12),
					'allowEmpty' => false,
					'message'=>'Mobile No. is Not Valid',
				),
			
			'landline'=>array(
					'rule'=>array('maxLength',15),
					'message'=>'Landline No. is Not Valid',
				),
			'file'=>array(
					'rule'=>array('maxLength',100),
					'message'=>'File is Not Valid',
				),
				
			'email'=>array(
					'rule'=>array('maxLength',200),
					'allowEmpty' => false,
					'message'=>'Email is Not Valid',
				),
			'document'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',20),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
			'once_card_no'=>array(
					'rule'=>array('maxLength',20),
					'message'=>'Aadhar is Not Valid',
				),
			'photo_id_no'=>array(
					'rule'=>array('maxLength',100),
					'allowEmpty' => false,
					'message'=>'Id is Not Valid',
				),
					
		
		);
		
		
		
		
		
	
	// Method to create primary user details report 06/06/2017
	public function primaryUserDetailsReportConditions($state,$district,$search_from_date,$search_to_date,$search_flag) {
		
		$Dmi_firm = new DmiFirmsTable();
		$state_not_empty = null; 
		$district_not_empty = null; 
		$date_not_empty = null;
		
		if($state != '') {			
			$state_not_empty = ['state' => $state];
		}
		
		if($district != '') {			
			$district_not_empty = ['district' => $district];
		}
		
		if($search_from_date != '' && $search_to_date != '') {			
			// $date_not_empty = array('date(created) BETWEEN ? AND ?' => array($search_from_date,$search_to_date));
			$date_not_empty = ['date(created) BETWEEN :start AND :end'];
		}		
		
		if($state == '' && $district == '' && $search_from_date == '' && $search_to_date == '' ) { 			
			// $primary_user_details = $this->find('all',array('fields'=>'customer_id'));
			// below query updated by Ankur Jangid
			if($search_flag == 'on') {
				$primary_user_details = $this->find('all')->select(['customer_id'])->order(['created'=>'DESC'])->extract('customer_id')->toArray();	// updated by Ankur
			}
			else {
				$primary_user_details = $this->find('all')->select(['customer_id'])->order(['created'=>'DESC'])->limit(['100'])->extract('customer_id')->toArray();	// updated by Ankur
			}					
		}
		else { 			
			// $primary_user_details = $this->find('all',array('fields'=>'customer_id','conditions'=>am($state_not_empty,$district_not_empty,$date_not_empty)));
			// below query updated by Ankur Jangid
			if($search_from_date != '' && $search_to_date != '') {
				$primary_user_details = $this->find('all')->select(['customer_id'])->where($state_not_empty)->where($district_not_empty)->where($date_not_empty)
					->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->extract('customer_id')->toArray(); // updated by Ankur
			}
			else {
				$primary_user_details = $this->find('all')->select(['customer_id'])->where($state_not_empty)->where($district_not_empty)->extract('customer_id')->toArray(); // updated by Ankur
			}					
		}
		
		return $primary_user_details; 		
	}		
}

?>