<?php	

//Note: All $this are converted to $this->Controller in this component. created on 14-07-2017 by Amol
//To access the properties of main controller used initialize function.

namespace app\Controller\Component;
	use Cake\Controller\Controller;
	use Cake\Controller\Component;
	
	use Cake\Controller\ComponentRegistry;
	use Cake\ORM\Table;
	use Cake\ORM\TableRegistry;
	use Cake\Datasource\EntityInterface;
	use Cake\Utility\Security;

	class DashboardchartsComponent extends Component {
	
		
		public $components= array('Session');
		public $controller = null;
		public $session = null;

		public function initialize(array $config): void{
			parent::initialize($config);
			$this->Controller = $this->_registry->getController();
			$this->Session = $this->getController()->getRequest()->getSession();
		}
		
		
		//created common function and called in below function with required parameters
		//on 04-05-2021 by Amol
		public function getRoleWiseAllocatedCounts($role_arr,$role,$allo_level,$username,$date_from,$date_to){
			
			$allocated_count = 0;
			if($role_arr[$role] == 'yes'){
				
				//get flow wise tables data
				$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
				$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();
				
				$i=0;
				foreach($flow_wise_tables as $each_flow){
					
					$allocationTable = TableRegistry::getTableLocator()->get($each_flow['allocation']);
					
					//for HO users
					if($role=='dy_ama' || $role=='jt_ama' || $role=='ama' || $role=='ho_mo_smo'){
						$allocationTable = TableRegistry::getTableLocator()->get($each_flow['ho_level_allocation']);
					}
					$finalSubmitTable = TableRegistry::getTableLocator()->get($each_flow['application_form']);

					$allocated_applications = $allocationTable->find('all',array('conditions'=>array($allo_level.' IS'=>$username,'date(created) >='=>$date_from, 'date(created) <'=>$date_to)))->toArray();
					$total_applications[$i] = count($allocated_applications);
					
					$allocated_count = $allocated_count + $total_applications[$i];

					$i=$i+1;
				}				
			}
			
			return $allocated_count;
		}
		
		
		//created common function and called in below function with required parameters
		//on 04-05-2021 by Amol
		public function getRoleWiseAcceptedCounts($role_arr,$role,$allo_level,$username,$current_level,$date_from,$date_to){
			
			$accepted_count = 0;
			if($role_arr[$role] == 'yes'){
				
				//get flow wise tables data
				$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
				$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();
				
				$i=0;
				foreach($flow_wise_tables as $each_flow){
					
					$allocationTable = TableRegistry::getTableLocator()->get($each_flow['allocation']);
					//for HO users
					if($role=='dy_ama' || $role=='jt_ama' || $role=='ama' || $role=='ho_mo_smo'){
						$allocationTable = TableRegistry::getTableLocator()->get($each_flow['ho_level_allocation']);
					}
					$finalSubmitTable = TableRegistry::getTableLocator()->get($each_flow['application_form']);
					
					$allocated_applications = $allocationTable->find('list',array('valueField'=>'customer_id', 'conditions'=>array($allo_level.' IS'=>$username)))->toList();

					if(!empty($allocated_applications)){
						$accepted_applications = $finalSubmitTable->find('all',array('fields'=>'customer_id','conditions'=>array('status'=>'approved','current_level'=>$current_level,'customer_id IN'=>$allocated_applications,'date(created) >='=>$date_from, 'date(created) <'=>$date_to),'group'=>'customer_id'))->toArray();
					}else{
						$accepted_applications = array();
					}

					$total_accepted[$i] = count($accepted_applications);
					
					$accepted_count = $accepted_count + $total_accepted[$i];
					
					$i=$i+1;
				}				
			}
			
			return $accepted_count;
		}
		
		
		//created common function and called in below function with required parameters
		//on 04-05-2021 by Amol
		public function getPaoUserCounts($role_arr,$role,$username,$date_from,$date_to,$count_for){
			
			$pao_count = 0;
			if($role_arr[$role] == 'yes'){
				
				//get user id from table
				$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
				$user_details = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$username)))->first();
				$user_id = $user_details['id'];

				//get pao id from pao table for this user id
				$DmiPaoDetails = TableRegistry::getTableLocator()->get('DmiPaoDetails');
				$pao_details = $DmiPaoDetails->find('all',array('conditions'=>array('pao_user_id IS'=>$user_id)))->first();
				$pao_id = $pao_details['id'];


				//get flow wise tables data
				$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
				$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();
				
				$i=0;
				foreach($flow_wise_tables as $each_flow){
					
					$paymentDetailsTable = TableRegistry::getTableLocator()->get($each_flow['payment']);
					
					$find_pao_applications = $paymentDetailsTable->find('all',array('conditions'=>array('pao_id IS'=>$pao_id,'payment_confirmation'=>$count_for,'date(created) >='=>$date_from, 'date(created) <'=>$date_to)))->toArray();
					
					$pao_count = $pao_count + count($find_pao_applications);
					
					$i=$i+1;
				}
				
			}
			
			return $pao_count;
			
		}
		

		
		// custome function for line chart graph

		public function lineChartGraph($username,$type){
			
			//initialize model in component
			$Dmi_user_role = TableRegistry::getTableLocator()->get('DmiUserRoles');

				$check_user_role = $Dmi_user_role->find('all',array('conditions'=>array('user_email_id IS'=>$username)))->first();	
				
				if(!empty($check_user_role))//this condition added on 30-03-2017 by Amol(if user roles empty, no dashboard graphs)
				{
					$user_role = $check_user_role;

					
					$for_months = 5; 
					// find last 6 months name	
					$i=$for_months;
					while($i>=0)
					{
						$month_count = '-'.$i;
						$month_name[$i] = date("F", strtotime($month_count." months"));

						
					$i = $i-1;
					}
		
					$this->Controller->set('month_name',$month_name);

					// For Super Admin User show over all applications status				
					if($user_role['super_admin'] == 'yes')
					{

						$i=$for_months;
						while($i>=0)
						{
							
							$month_count = '-'.$i;
							$search_month_date_from = date("Y-m-01", strtotime($month_count." months"));
							
							$search_month_date_to = date("Y-m-01", strtotime(($month_count+1)." months")); // + and - will be -,so decrement
							
							
							$split_date = explode('-',$search_month_date_from); 
							
							$search_year = $split_date[0];
							$search_month = $split_date[1];
							
							//get flow wise tables data
							$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
							$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();
							
							$allocated_count = 0;
							$accepted_count = 0;
							$j=0;
							foreach($flow_wise_tables as $each_flow){
								
								$finalSubmitTable = TableRegistry::getTableLocator()->get($each_flow['application_form']);
								
								//for overall allocated applications			
								$find_total_applications = $finalSubmitTable->find('all',array('fields'=>'customer_id','conditions'=>array('status'=>'pending','date(created) >='=>$search_month_date_from, 'date(created) <'=>$search_month_date_to),'group'=>'customer_id'))->toArray();																					
								$applications_allocated[$j] = count($find_total_applications);
								$allocated_count = $allocated_count + $applications_allocated[$j];
								
								//for overall accepted applications
								$find_total_approved = $finalSubmitTable->find('all',array('fields'=>'customer_id','conditions'=>array('status'=>'approved','current_level'=>'level_3',
																				'date(created) >='=>$search_month_date_from, 'date(created) <'=>$search_month_date_to),
																				'group'=>'customer_id'))->toArray();

								$applications_accepted[$j] = count($find_total_approved);									
								$accepted_count = $accepted_count + $applications_accepted[$j];
								
								$j=$j+1;
							}
							
							$total_applications_allocated[$i] = $allocated_count;																
							$total_applications_accepted[$i] = $accepted_count;
						
							$i=$i-1;																
						}			
	
					}
					else{
					// For other users show allocated applications status	
						
						
						$i=$for_months;
						while($i>=0)
						{
							$month_count = '-'.$i;
							$search_month_date_from = date("Y-m-01", strtotime($month_count." months"));
							
							$search_month_date_to = date("Y-m-01", strtotime(($month_count+1)." months")); // + and - will be -,so decrement
							
							$split_date = explode('-',$search_month_date_from); 
							
							$search_year = $split_date[0];
							$search_month = $split_date[1];
							

							//for Scrutiny Officer count
							$total_mo_applications[$i] = $this->getRoleWiseAllocatedCounts($user_role,'mo_smo_inspection','level_1',$username,$search_month_date_from,$search_month_date_to);
							
							//for Inspection Officer count
							$total_io_applications[$i] = $this->getRoleWiseAllocatedCounts($user_role,'io_inspection','level_2',$username,$search_month_date_from,$search_month_date_to);
							
							//for Nodal Officer count
							$total_ro_applications[$i] = $this->getRoleWiseAllocatedCounts($user_role,'ro_inspection','level_3',$username,$search_month_date_from,$search_month_date_to);
			
							//for PAO/DDO Officer count
							$total_pao_applications[$i] = $this->getPaoUserCounts($user_role,'pao',$username,$search_month_date_from,$search_month_date_to,'pending');					
							
							//for Dy AMA Officer count
							$total_dyama_applications[$i] = $this->getRoleWiseAllocatedCounts($user_role,'dy_ama','dy_ama',$username,$search_month_date_from,$search_month_date_to);
	
							//for Scrutiny Officer (HO QC) count
							$total_ho_mo_applications[$i] = $this->getRoleWiseAllocatedCounts($user_role,'ho_mo_smo','ho_mo_smo',$username,$search_month_date_from,$search_month_date_to);
	
							//for Jt AMA Officer count
							$total_jtama_applications[$i] = $this->getRoleWiseAllocatedCounts($user_role,'jt_ama','jt_ama',$username,$search_month_date_from,$search_month_date_to);
	
							//for AMA Officer count
							$total_ama_applications[$i] = $this->getRoleWiseAllocatedCounts($user_role,'ama','ama',$username,$search_month_date_from,$search_month_date_to);
	
							//taking sum of all allocated applications count	
							$total_applications_allocated[$i] = $total_mo_applications[$i] + $total_io_applications[$i] + $total_ro_applications[$i] + $total_dyama_applications[$i] + $total_pao_applications[$i] + $total_ho_mo_applications[$i] + $total_ama_applications[$i];
							
							$i = $i-1;
						}

				//calculation for other user accepted applications		

						$i=$for_months;
						while($i>=0)
						{
							$month_count = '-'.$i;
							$search_month_date_from = date("Y-m-01", strtotime($month_count." months"));
							
							$search_month_date_to = date("Y-m-01", strtotime(($month_count+1)." months")); // + and - will be -,so decrement
							
							$split_date = explode('-',$search_month_date_from); 
							
							$search_year = $split_date[0];
							$search_month = $split_date[1];
							
							//for Scrutiny Officer count
							$total_mo_accepted[$i] = $this->getRoleWiseAcceptedCounts($user_role,'mo_smo_inspection','level_1',$username,'level_1',$search_month_date_from,$search_month_date_to);

							//for Inspection Officer count
							$total_io_accepted[$i] = $this->getRoleWiseAcceptedCounts($user_role,'io_inspection','level_2',$username,'level_2',$search_month_date_from,$search_month_date_to);

							//for Nodal (RO/SO) Officer count
							$total_ro_accepted[$i] = $this->getRoleWiseAcceptedCounts($user_role,'ro_inspection','level_3',$username,'level_3',$search_month_date_from,$search_month_date_to);

							//for PAO/DDO Officer count
							$total_pao_accepted[$i] = $this->getPaoUserCounts($user_role,'pao',$username,$search_month_date_from,$search_month_date_to,'confirmed');
							
							//for Dy AMA Officer count
							$total_dyama_accepted[$i] = $this->getRoleWiseAcceptedCounts($user_role,'dy_ama','dy_ama',$username,'level_3',$search_month_date_from,$search_month_date_to);

							//for Scrutiny Office (HO QC) Officer count
							$total_ho_mo_accepted[$i] = $this->getRoleWiseAcceptedCounts($user_role,'ho_mo_smo','ho_mo_smo',$username,'level_3',$search_month_date_from,$search_month_date_to);

							//for Jt AMA Office (HO QC) Officer count
							$total_jtama_accepted[$i] = $this->getRoleWiseAcceptedCounts($user_role,'jt_ama','jt_ama',$username,'level_3',$search_month_date_from,$search_month_date_to);

							//for AMA Office (HO QC) Officer count
							$total_ama_accepted[$i] = $this->getRoleWiseAcceptedCounts($user_role,'ama','ama',$username,'level_3',$search_month_date_from,$search_month_date_to);							
				
							//taking sum of all accepted/approved application count
							$total_applications_accepted[$i] = $total_mo_accepted[$i] + $total_io_accepted[$i] + $total_ro_accepted[$i] + $total_pao_accepted[$i] + $total_dyama_accepted[$i] + $total_ho_mo_accepted[$i] + $total_jtama_accepted[$i] + $total_ama_accepted[$i];

							$i = $i-1;
						}

					}
	
					
					// setting variables array for above calculations for line chart
					// for total pending applications from last 12 months

					$i=$for_months;
					while($i>=0)
					{								
						$month_allocated_data[$i] = $total_applications_allocated[$i];					

						$i=$i-1;
					}					
					$this->Controller->set('month_allocated_data',$month_allocated_data);

							
					// for total approved applications from last 12 months							
					
					$i=$for_months;
					while($i>=0)
					{								
						$month_approved_data[$i] = $total_applications_accepted[$i];					

						$i=$i-1;
					}				
					$this->Controller->set('month_approved_data',$month_approved_data);

				}//end of first if condition on not empty check

		}
		
		

		
// custome function for Pie chart data
	

	//created common function to get application counts with percentage for pie chart data
	//on 04-05-2021 by Amol
	public function getRoleWisePieChartCount($role,$allo_level,$username,$check_level){
		
		//get flow wise tables data
		$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();
		
		$i=0;
		$result_array = array();
		$inspection_count = 0;
		$ca_count = 0;
		$printing_count = 0;
		$lab_count = 0;
		$total_allocation = 0;
		foreach($flow_wise_tables as $each_flow){
			
			$allocationTable = TableRegistry::getTableLocator()->get($each_flow['allocation']);
			
			//for HO users
			if($role=='dy_ama' || $role=='jt_ama' || $role=='ama' || $role=='ho_mo_smo'){
				$allocationTable = TableRegistry::getTableLocator()->get($each_flow['ho_level_allocation']);
			}
			$finalSubmitTable = TableRegistry::getTableLocator()->get($each_flow['application_form']);
			
			$find_allocated = $allocationTable->find('all',array('conditions'=>array($allo_level.' IS'=>$username)))->toArray();
									
			foreach($find_allocated as $each_id)
			{
				if($each_id['customer_id']!=null){
					//for site inspection count
					$find_approved = $finalSubmitTable->find('all',array('conditions'=>array('customer_id IS'=>$each_id['customer_id'],
										'status'=>'approved','current_level'=>$check_level)))->first();
																									
					if(!empty($find_approved)){
						$inspection_count = $inspection_count+1;					
					}

					//for application type count
					$split_id = explode('/',$each_id['customer_id']);
					
					if($split_id[1] == 1){					
						$ca_count = $ca_count+1;					
					}
					elseif($split_id[1] == 2){					
						$printing_count = $printing_count+1;					
					}
					elseif($split_id[1] == 3){					
						$lab_count = $lab_count+1;					
					}
				}

			}
			
			$total_allocation = $total_allocation + count($find_allocated);
		}
		$result_array['allo_arr'] = $total_allocation;
		$result_array['inspection'] = $inspection_count;
		$result_array['ca'] = $ca_count;
		$result_array['pp'] = $printing_count;
		$result_array['lab'] = $lab_count;
			

		return $result_array;
	}	
		

	public function pieChartData($username){
						
		//initialize model in component
		$Dmi_user_role = TableRegistry::getTableLocator()->get('DmiUserRoles');
		$Dmi_user = TableRegistry::getTableLocator()->get('DmiUsers');//added on 22-03-2019 by Amol, it was not added when code upadsted.
		$Dmi_pao_detail = TableRegistry::getTableLocator()->get('DmiPaoDetails');//added on 22-03-2019 by Amol, it was not added when code upadsted.

		$site_inspection_count = 0;
		
		$ca_applications_count =0;
		$printing_applications_count = 0;
		$lab_applications_count = 0;
			
		$total_allocated_applications = 0;

		$check_user_role = $Dmi_user_role->find('all',array('conditions'=>array('user_email_id IS'=>$username)))->first();	
		
		if(!empty($check_user_role))//this condition added on 30-03-2017 by Amol(if user roles empty, no dashboard graphs)
		{
			$user_role = $check_user_role;

			// For Super Admin User show over all applications status	
	
			if($user_role['super_admin'] == 'yes')
			{
				
				//get flow wise tables data
				$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
				$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();
				
				$inspection_count = 0;
				$allocated_count = 0;
				foreach($flow_wise_tables as $each_flow){
					
					$finalSubmitTable = TableRegistry::getTableLocator()->get($each_flow['application_form']);
					
					//to get total site inspection done
					$find_total_site_inspected = $finalSubmitTable->find('all',array('fields'=>'customer_id',
										'conditions'=>array('status'=>'approved','current_level'=>'level_2'),'group'=>'customer_id'))->toArray();	

					$inspection_count = $inspection_count + count($find_total_site_inspected);

					//to get total allocated applications
					$total_applications = $finalSubmitTable->find('all',array('fields'=>'customer_id','conditions'=>array('status'=>'pending'),'group'=>'customer_id'))->toArray();
					
					$allocated_count = $allocated_count + count($total_applications);
					
					foreach($total_applications as $each_type)
					{
						if($each_type['customer_id']!=null){
							//for application type count
							$split_id = explode('/',$each_type['customer_id']);
							
							if($split_id[1] == 1)
							{									
								$ca_applications_count = $ca_applications_count+1;											
							}
							elseif($split_id[1] == 2){									
								$printing_applications_count = $printing_applications_count+1;											
							}
							elseif($split_id[1] == 3){									
								$lab_applications_count = $lab_applications_count+1;											
							}
						}						
					}					
				}
		
				$site_inspection_count = $inspection_count;										
				$total_allocated_applications = $allocated_count;															

			}
			else{
			// for other users

				// MO allocated user		
				if($user_role['mo_smo_inspection'] == 'yes')
				{									
					$result_array = $this->getRoleWisePieChartCount('mo_smo_inspection','level_1',$username,'level_2');
					$site_inspection_count = $site_inspection_count + $result_array['inspection'];
					$ca_applications_count = $ca_applications_count + $result_array['ca'];
					$printing_applications_count = $printing_applications_count + $result_array['pp'];
					$lab_applications_count = $lab_applications_count + $result_array['lab'];
					$total_allocated_applications = $total_allocated_applications + $result_array['allo_arr'];
				}
				
				
				// IO allocated user		
				if($user_role['io_inspection'] == 'yes')
				{									
					$result_array = $this->getRoleWisePieChartCount('io_inspection','level_2',$username,'level_2');
					$site_inspection_count = $site_inspection_count + $result_array['inspection'];
					$ca_applications_count = $ca_applications_count + $result_array['ca'];
					$printing_applications_count = $printing_applications_count + $result_array['pp'];
					$lab_applications_count = $lab_applications_count + $result_array['lab'];
					$total_allocated_applications = $total_allocated_applications + $result_array['allo_arr'];

				}

					
				//added new logic to show application status to PAO user
				if($user_role['pao'] == 'yes')								
				{
					//get user id from table
					$user_details = $Dmi_user->find('all',array('conditions'=>array('email IS'=>$username)))->first();
					$user_id = $user_details['id'];
					
					//get pao id from pao table for this user id
					$pao_details = $Dmi_pao_detail->find('all',array('conditions'=>array('pao_user_id IS'=>$user_id)))->first();
					$pao_id = $pao_details['id'];
					
					//get flow wise tables data
					$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
					$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();

					$pao_allocation = 0;
					foreach($flow_wise_tables as $each_flow){
						
						$paymentDetailsTable = TableRegistry::getTableLocator()->get($each_flow['payment']);
						$find_pao_allocated = $paymentDetailsTable->find('all',array('conditions'=>array('pao_id IS'=>$pao_id,'payment_confirmation'=>'confirmed')))->toArray();
					
						foreach($find_pao_allocated as $each_id)
						{
							if($each_id['customer_id']!=null){
								//for application type count
								$split_id = explode('/',$each_id['customer_id']);
								
								if($split_id[1] == 1){								
									$ca_applications_count = $ca_applications_count+1;								
								}
								elseif($split_id[1] == 2){								
									$printing_applications_count = $printing_applications_count+1;							
								}
								elseif($split_id[1] == 3){								
									$lab_applications_count = $lab_applications_count+1;								
								}
							}
						}
						
						$pao_allocation = $pao_allocation + count($find_pao_allocated);
						
					}
					$total_allocated_applications = $total_allocated_applications + $pao_allocation;
										
				}
	
					
				// Nodal allocated user		
				if($user_role['ro_inspection'] == 'yes')
				{									
					$result_array = $this->getRoleWisePieChartCount('ro_inspection','level_3',$username,'level_2');
					$site_inspection_count = $site_inspection_count + $result_array['inspection'];
					$ca_applications_count = $ca_applications_count + $result_array['ca'];
					$printing_applications_count = $printing_applications_count + $result_array['pp'];
					$lab_applications_count = $lab_applications_count + $result_array['lab'];
					$total_allocated_applications = $total_allocated_applications + $result_array['allo_arr'];

				}
				
				
				// Dy AMA allocated user		
				if($user_role['dy_ama'] == 'yes')
				{									
					$result_array = $this->getRoleWisePieChartCount('dy_ama','dy_ama',$username,'level_2');
					$site_inspection_count = $site_inspection_count + $result_array['inspection'];
					$ca_applications_count = $ca_applications_count + $result_array['ca'];
					$printing_applications_count = $printing_applications_count + $result_array['pp'];
					$lab_applications_count = $lab_applications_count + $result_array['lab'];
					$total_allocated_applications = $total_allocated_applications + $result_array['allo_arr'];

				}
				
				// HO MO allocated user		
				if($user_role['ho_mo_smo'] == 'yes')
				{									
					$result_array = $this->getRoleWisePieChartCount('ho_mo_smo','ho_mo_smo',$username,'level_2');
					$site_inspection_count = $site_inspection_count + $result_array['inspection'];
					$ca_applications_count = $ca_applications_count + $result_array['ca'];
					$printing_applications_count = $printing_applications_count + $result_array['pp'];
					$lab_applications_count = $lab_applications_count + $result_array['lab'];
					$total_allocated_applications = $total_allocated_applications + $result_array['allo_arr'];

				}

				// Jt AMA allocated user		
				if($user_role['jt_ama'] == 'yes')
				{									
					$result_array = $this->getRoleWisePieChartCount('jt_ama','jt_ama',$username,'level_2');
					$site_inspection_count = $site_inspection_count + $result_array['inspection'];
					$ca_applications_count = $ca_applications_count + $result_array['ca'];
					$printing_applications_count = $printing_applications_count + $result_array['pp'];
					$lab_applications_count = $lab_applications_count + $result_array['lab'];
					$total_allocated_applications = $total_allocated_applications + $result_array['allo_arr'];

				}
				
				
				// AMA allocated user		
				if($user_role['ama'] == 'yes')
				{									
					$result_array = $this->getRoleWisePieChartCount('ama','ama',$username,'level_2');
					$site_inspection_count = $site_inspection_count + $result_array['inspection'];
					$ca_applications_count = $ca_applications_count + $result_array['ca'];
					$printing_applications_count = $printing_applications_count + $result_array['pp'];
					$lab_applications_count = $lab_applications_count + $result_array['lab'];
					$total_allocated_applications = $total_allocated_applications + $result_array['allo_arr'];

				}

			}
	
			// calculate the values at last							
			if($total_allocated_applications != 0)
			{
				$siteinspection_percentage = ($site_inspection_count*100)/$total_allocated_applications;
				$ca_percentage = ($ca_applications_count*100)/$total_allocated_applications;
				$printing_percentage = ($printing_applications_count*100)/$total_allocated_applications;
				$lab_percentage = ($lab_applications_count*100)/$total_allocated_applications;
				
			}
			else{
				
				$siteinspection_percentage =0;
				$ca_percentage =0;
				$printing_percentage =0;
				$lab_percentage =0;
				
			}

			//set percentage variables
			$this->Controller->set('siteinspection_percentage',$siteinspection_percentage);
			$this->Controller->set('ca_percentage',$ca_percentage);
			$this->Controller->set('printing_percentage',$printing_percentage);
			$this->Controller->set('lab_percentage',$lab_percentage);
			//set count variables
			$this->Controller->set('site_inspection_count',$site_inspection_count);
			$this->Controller->set('ca_applications_count',$ca_applications_count);
			$this->Controller->set('printing_applications_count',$printing_applications_count);
			$this->Controller->set('lab_applications_count',$lab_applications_count);
			
			$this->Controller->set('total_allocated_applications',$total_allocated_applications);

		}//end of first if condition on not empty check
		
	}
		
	
}
	
?>