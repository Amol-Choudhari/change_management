<?php	
	namespace app\Controller\Component;
	use Cake\Controller\Controller;
	use Cake\Controller\Component;	
	use Cake\Controller\ComponentRegistry;
	use Cake\ORM\Table;
	use Cake\ORM\TableRegistry;
	use Cake\Datasource\EntityInterface;

	class CommoncountsfunctionsComponent extends Component {
	
		
		public $components= array('Session');
		public $controller = null;
		public $session = null;

		public function initialize(array $config): void{
			parent::initialize($config);
			$this->Controller = $this->_registry->getController();
			$this->Session = $this->getController()->getRequest()->getSession();
		}
		
		
		public function getlevelwiseCountTable($for_level,$user_type=null){
			
			//for PAO
			if($for_level == 'pao'){				
				$count_table = 'DmiCommonPaoCounts';	
				
			}elseif($for_level == 'level_1'){				
				$count_table = 'DmiCommonMoCounts';	
				
			}elseif($for_level == 'level_2'){
				$count_table = 'DmiCommonIoCounts';
				
			}elseif($for_level == 'level_3'){
				
				if($user_type == 'RO'){
					$count_table = 'DmiCommonRoCounts';
				
				}elseif($user_type == 'SO'){
					$count_table = 'DmiCommonSoCounts';
				}
				
			}elseif($for_level == 'level_4'){				
					$count_table = 'DmiCommonHoCounts';

			}
			
			return $count_table;

		}
		
	//to insert user record in count table first time with all 0 values.	
		public function insertFirstCountRecord($username){
			
			$all_count_tables = array('DmiCommonPaoCounts','DmiCommonMoCounts','DmiCommonIoCounts','DmiCommonRoCounts','DmiCommonSoCounts','DmiCommonHoCounts','DmiCommonMainCounts');
								
			foreach($all_count_tables as $each){
				$model_name = TableRegistry::getTableLocator()->get($each);
				
				//check if record exist
				$check_record = $model_name->find('all',array('conditions'=>array('user_id IS'=>$username)))->first();
				
				if(empty($check_record)){
					
					if($model_name == 'DmiCommonIoCounts'){
						$modelEntity = $model_name->newEntity(array(
							'user_id'=>$username,
							'pending'=>0,
							'reports_filed'=>0,
							'ref_back'=>0,
							'replied'=>0,
							'approved'=>0,
							'rejected'=>0,
							'created'=>date('Y-m-d H:i:s')				
						));
					}else{
						$modelEntity = $model_name->newEntity(array(
							'user_id'=>$username,
							'pending'=>0,
							'ref_back'=>0,
							'replied'=>0,
							'approved'=>0,
							'rejected'=>0,
							'created'=>date('Y-m-d H:i:s')				
						));
						
					}
					
					$model_name->save($modelEntity);
					
				}
			}	
			
		}
		
		
		public function insertAllUsersInCountTable(){
			
			$users_table = TableRegistry::getTableLocator()->get('DmiUsers');
			
			//get all active users from users table
			$all_users = $users_table->find('all',array('conditions'=>array('status'=>'active')))->toArray();
			
			foreach($all_users as $each){
				
				$this->insertFirstCountRecord($each['email']);
			}
			
		}
		
		
		
		//to fetch user dashboard counts when click on role wise top button
		public function fetchUserCountRecord($username,$for_level,$user_type=null){
		
			$get_model_name = $this->getlevelwiseCountTable($for_level,$user_type);
			$model_name = TableRegistry::getTableLocator()->get($get_model_name);
			
			//get counts
			$get_count = array();
			if(empty($user_type)){
				$get_count = $model_name->find('all',array('conditions'=>array('user_id IS'=>$username)))->first();
			}else{
				//$get_count = $model_name->find('all',array('conditions'=>array('user_id IS'=>$username,'user_type'=>$user_type)))->first();
			}
			
			if(empty($get_count)){
				$get_count['pending']=0;
				$get_count['ref_back']=0;
				$get_count['replied']=0;
				$get_count['approved']=0;
				$get_count['rejected']=0;
			}
			
			if($for_level=='level_2'){
				$get_count['reports_filed']=0;
			}
			
			return $get_count;			
			
		}
		
		
		//to fetch counts from main counts table, called when user logged in
		public function fetchUserMainCounts($username,$user_type=null){
			
			$model_name = TableRegistry::getTableLocator()->get('DmiCommonMainCounts');
			//get counts
			$get_count = array();
			if(empty($user_type)){
				$get_count = $model_name->find('all',array('conditions'=>array('user_id IS'=>$username)))->first();
			}else{
				//$get_count = $model_name->find('all',array('conditions'=>array('user_id IS'=>$username,'user_type IS'=>$user_type)))->first();
			}
			
			if(empty($get_count)){
				$get_count['pending']=0;
				$get_count['ref_back']=0;
				$get_count['replied']=0;
				$get_count['approved']=0;
				$get_count['rejected']=0;
			}
			
			$this->Session->write('first_visit',true);
			
			return $get_count;
			
		}
		
		
		//to fetch counts from main counts table, called when user logged in
		public function updateUserCounts($customer_id,$from_level,$to_level,$for_status,$current_status=null,$from_user_type=null,$to_user_type=null){
			
			$from_user = $this->Session->read('username');
			$appl_type = $this->Session->read('application_type');
			
			$modify_date = date('Y-m-d H:i:s');
			
			//get application wise table
			$flow_wise_table = TableRegistry::getTableLocator()->get('DmiFlowWiseTableslists');
			$appl_tables = $flow_wise_table->find('all',array('conditions'=>array('application_type IS'=>$appl_type)))->first();
			$get_allocation_table = $appl_tables['allocation'];
			
			if($to_level=='applicant'){
				$to_user = $customer_id;
				
			}elseif($to_level=='pao'){
				//get pao user id
				$this->Controller->loadComponent('MasterTableContent');
				$pao_user_id = $this->Controller->MasterTableContent->getPaoUserId($customer_id,$appl_type);
				$to_user = $pao_user_id;
				
			}elseif($to_level=='level_1' || $to_level=='level_2' || $to_level=='level_3'){
				//get mo user
				$allocation_table = TableRegistry::getTableLocator()->get($get_allocation_table);
				$get_to_user = $allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
				
				$to_user = $get_to_user[$to_level];
	
			}elseif($to_level=='level_4'){
				$ho_level_allocation  = TableRegistry::getTableLocator()->get($appl_tables['ho_level_allocation']);
				$get_to_user = $ho_level_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
				
				$to_user = $get_to_user[$to_user_type];
				
			}
			
			//load model
			$pao_count_table = TableRegistry::getTableLocator()->get('DmiCommonPaoCounts');
			$mo_count_table = TableRegistry::getTableLocator()->get('DmiCommonMoCounts');
			$io_count_table = TableRegistry::getTableLocator()->get('DmiCommonIoCounts');
			
			//conditions level 3 officer RO/SO
			if($from_user_type=='RO' || $to_user_type=='RO'){
				$level_3_count_table = TableRegistry::getTableLocator()->get('DmiCommonRoCounts');		
			}elseif($from_user_type=='SO' || $to_user_type=='SO'){
				$level_3_count_table = TableRegistry::getTableLocator()->get('DmiCommonSoCounts');
			}			
			
			$ho_count_table = TableRegistry::getTableLocator()->get('DmiCommonhoCounts');
			$main_count_table = TableRegistry::getTableLocator()->get('DmiCommonMainCounts');
			
			//get last counts from main count table
			//from main user
			$get_from_main_count = $main_count_table->find('all',array('conditions'=>array('user_id IS'=>$from_user)))->first();
			$from_main_pending = $get_from_main_count['pending'];
			$from_main_reports_filed = $get_from_main_count['reports_filed'];
			$from_main_ref_back = $get_from_main_count['ref_back'];
			$from_main_replied = $get_from_main_count['replied'];
			$from_main_approved = $get_from_main_count['approved'];
			
			//to main user
			$get_to_main_count = $main_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user)))->first();
			$to_main_pending = $get_to_main_count['pending'];
			$to_main_reports_filed = $get_to_main_count['reports_filed'];
			$to_main_ref_back = $get_to_main_count['ref_back'];
			$to_main_replied = $get_to_main_count['replied'];
			$to_main_approved = $get_to_main_count['approved'];
			
			
			//form Applicant
			if($from_level == 'applicant'){ 				
				if($to_level == 'pao'){	//applicant with PAO/DDO

					//get last counts
					$get_pao_count = $pao_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user)))->first();
					$pending = $get_pao_count['pending'];
					$ref_back = $get_pao_count['ref_back'];
					$replied = $get_pao_count['replied'];
					
					if($for_status=='final_submit'){//on first final submit					
						$pao_count_table->updateAll(array('pending'=>"$pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('pending'=>"$to_main_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
					}elseif($for_status=='replied'){ //on replied to PAO
						$pao_count_table->updateAll(array('replied'=>"$replied"+1,'ref_back'=>"$ref_back"-1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('replied'=>"$to_main_replied"+1,'ref_back'=>"$to_main_ref_back"-1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
					}
					
				}elseif($to_level == 'level_3'){ //applicant with RO/SO				
					
					//get last counts
					$level_3_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user)))->first();
					$level_3_ref_back = $level_3_count_table['ref_back'];
					$level_3_replied = $level_3_count_table['replied'];
					
					if($for_status=='replied'){ //on replied to RO
						$level_3_count_table->updateAll(array('replied'=>"$level_3_replied"+1,'ref_back'=>"$level_3_ref_back"-1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('replied'=>"$to_main_replied"+1,'ref_back'=>"$to_main_ref_back"-1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
					}
										
				}
				
			}
			//form PAO/DDO
			elseif($from_level == 'pao'){
				//get PAO last counts
				$get_pao_count = $pao_count_table->find('all',array('conditions'=>array('user_id IS'=>$from_user)))->first();
				$pao_pending = $get_pao_count['pending'];
				$pao_ref_back = $get_pao_count['ref_back'];
				$pao_replied = $get_pao_count['replied'];
				$pao_approved = $get_pao_count['approved'];
				
				if($to_level == 'applicant'){	//PAO with Applicant
					if($for_status=='ref_back' && $current_status=='pending'){
						$pao_count_table->updateAll(array('ref_back'=>"$pao_ref_back"+1,'pending'=>"$pao_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'pending'=>"$from_main_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
					}elseif($for_status=='ref_back' && $current_status=='replied'){
						$pao_count_table->updateAll(array('ref_back'=>"$pao_ref_back"+1,'replied'=>"$pao_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'replied'=>"$from_main_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
					}
					
				}elseif($to_level == 'level_3'){ //PAO with RO/SO				
					
					if($for_status=='approved' && $current_status=='pending'){
						$pao_count_table->updateAll(array('approved'=>"$pao_approved"+1,'pending'=>"$pao_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('approved'=>"$from_main_approved"+1,'pending'=>"$from_main_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
					}elseif($for_status=='approved' && $current_status=='replied'){
						$pao_count_table->updateAll(array('approved'=>"$pao_approved"+1,'replied'=>"$pao_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('approved'=>"$from_main_approved"+1,'replied'=>"$from_main_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
					}					
					
					//get last counts
					$level_3_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user)))->first();
					$level_3_pending = $level_3_count_table['pending'];
					
					$level_3_count_table->updateAll(array('pending'=>"$level_3_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
					
					$main_count_table->updateAll(array('pending'=>"$to_main_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
											
				}
				
			
			}//form MO scrutiny officer
			elseif($from_level == 'level_1'){
				
				//get MO last counts
				$get_mo_count = $mo_count_table->find('all',array('conditions'=>array('user_id IS'=>$from_user)))->first();
				$mo_pending = $get_mo_count['pending'];
				$mo_ref_back = $get_mo_count['ref_back'];
				$mo_replied = $get_mo_count['replied'];
				$mo_approved = $get_mo_count['approved'];
				
				if($to_level == 'level_3'){ //PAO with RO/SO			
					
					if($for_status=='ref_back' && $current_status=='pending'){
						$mo_count_table->updateAll(array('ref_back'=>"$mo_ref_back"+1,'pending'=>"$mo_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'pending'=>"$from_main_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
					}elseif($for_status=='ref_back' && $current_status=='replied'){
						$mo_count_table->updateAll(array('ref_back'=>"$mo_ref_back"+1,'replied'=>"$mo_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'replied'=>"$from_main_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
					}
						
					//get last counts
					$level_3_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user)))->first();
					$level_3_replied = $level_3_count_table['replied'];
					
					$level_3_count_table->updateAll(array('replied'=>"$level_3_replied"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
					
					$main_count_table->updateAll(array('replied'=>"$to_main_replied"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
				}
			
			
			}//form IO officer
			elseif($from_level == 'level_2'){
				
				//get IO last counts
				$get_io_count = $io_count_table->find('all',array('conditions'=>array('user_id IS'=>$from_user)))->first();
				$io_pending = $get_io_count['pending'];
				$io_reports_filed = $get_io_count['reports_filed'];
				$io_ref_back = $get_io_count['ref_back'];
				$io_replied = $get_io_count['replied'];
				$io_approved = $get_io_count['approved'];
				
				if($to_level == 'level_3'){ //PAO with RO/SO			
					
					if($for_status=='reports_filed' && $current_status=='pending'){
						$io_count_table->updateAll(array('reports_filed'=>"$io_reports_filed"+1,'pending'=>"$io_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('reports_filed'=>"$from_main_reports_filed"+1,'pending'=>"$from_main_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));

						//get last counts
						$level_3_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user)))->first();
						$level_3_pending = $level_3_count_table['pending'];
						
						$level_3_count_table->updateAll(array('pending'=>"$level_3_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('pending'=>"$to_main_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));

					}elseif($for_status=='replied' && $current_status=='ref_back'){
						$io_count_table->updateAll(array('replied'=>"$io_replied"+1,'ref_back'=>"$io_ref_back"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('replied'=>"$from_main_replied"+1,'ref_back'=>"$from_main_ref_back"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						//get last counts
						$level_3_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user)))->first();
						$level_3_pending = $level_3_count_table['pending'];
						
						$level_3_count_table->updateAll(array('pending'=>"$level_3_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('pending'=>"$to_main_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
					}					
					
				}			
			
			
			}//form RO/SO officer
			elseif($from_level == 'level_3'){
				
				//get level 3 last counts
				$get_level_3_count = $level_3_count_table->find('all',array('conditions'=>array('user_id IS'=>$from_user)))->first();
				$level_3_pending = $get_level_3_count['pending'];
				$level_3_ref_back = $get_level_3_count['ref_back'];
				$level_3_replied = $get_level_3_count['replied'];
				$level_3_approved = $get_level_3_count['approved'];
				$level_3_rejected = $get_level_3_count['rejected'];
				
				if($to_level == 'applicant'){ //RO/SO with applicant
					
					if($for_status=='ref_back' && $current_status=='pending'){
						
						$level_3_count_table->updateAll(array('ref_back'=>"$level_3_ref_back"+1,'pending'=>"$level_3_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'pending'=>"$from_main_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
					
					}elseif($for_status=='ref_back' && $current_status=='replied'){
						
						$level_3_count_table->updateAll(array('ref_back'=>"$level_3_ref_back"+1,'replied'=>"$level_3_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'replied'=>"$from_main_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
					}
				
				}elseif($to_level == 'level_1'){ //RO/SO with MO/SMO
				
					//get MO last counts
					$get_mo_count = $mo_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user)))->first();
					$mo_pending = $get_mo_count['pending'];
					$mo_ref_back = $get_mo_count['ref_back'];
					$mo_replied = $get_mo_count['replied'];
					$mo_approved = $get_mo_count['approved'];
				
					if($for_status=='allocate' && $current_status=='pending'){
						
						$level_3_count_table->updateAll(array('pending'=>"$level_3_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('pending'=>"$from_main_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$mo_count_table->updateAll(array('pending'=>"$mo_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('pending'=>"$to_main_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
					
					}elseif($for_status=='ref_back' && $current_status=='replied'){
						
						$level_3_count_table->updateAll(array('ref_back'=>"$level_3_ref_back"+1,'replied'=>"$level_3_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'replied'=>"$from_main_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$mo_count_table->updateAll(array('ref_back'=>"$mo_ref_back"-1,'replied'=>"$mo_replied"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$to_main_ref_back"-1,'replied'=>"$to_main_replied"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
					}
				
					
				}elseif($to_level == 'level_2'){ //RO/SO with MO/SMO
				
					//get IO last counts
					$get_io_count = $io_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user)))->first();
					$io_pending = $get_io_count['pending'];
					$io_reports_filed = $get_io_count['reports_filed'];
					$io_ref_back = $get_io_count['ref_back'];
					$io_replied = $get_io_count['replied'];
					$io_approved = $get_io_count['approved'];
				
					if($for_status=='allocate' && $current_status=='pending'){
						
						$level_3_count_table->updateAll(array('pending'=>"$level_3_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('pending'=>"$from_main_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$io_count_table->updateAll(array('pending'=>"$io_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('pending'=>"$to_main_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
					
					}elseif($for_status=='ref_back' && $current_status=='pending'){
						
						$level_3_count_table->updateAll(array('ref_back'=>"$level_3_ref_back"+1,'pending'=>"$level_3_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'pending'=>"$from_main_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$io_count_table->updateAll(array('$reports_filed'=>"$io_reports_filed"-1,'ref_back'=>"$io_ref_back"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('$reports_filed'=>"$to_main_reports_filed"-1,'ref_back'=>"$to_main_ref_back"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
					}elseif($for_status=='ref_back' && $current_status=='replied'){
						
						$level_3_count_table->updateAll(array('ref_back'=>"$level_3_ref_back"+1,'replied'=>"$level_3_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'replied'=>"$from_main_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
						
						$io_count_table->updateAll(array('ref_back'=>"$io_ref_back"+1,'replied'=>"$io_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('ref_back'=>"$to_main_ref_back"+1,'replied'=>"$to_main_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$to_user));
					}
				
				}elseif($to_level == 'level_4'){ //RO/SO with HO officer
				
					//get HO last counts
					$get_ho_count = $ho_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user,'user_type'=>$to_user_type)))->first();
					$ho_pending = $get_ho_count['pending'];
					$ho_ref_back = $get_ho_count['ref_back'];
					$ho_replied = $get_ho_count['replied'];
					$ho_approved = $get_ho_count['approved'];
				
					if($from_user_type == 'RO'){//only RO communicate with HO, either from level3 or level4
						
						if($to_user_type == 'dy_ama'){
							if($for_status=='pending' && $current_status=='pending'){//from io report pending to HO pending
							
								$level_3_count_table->updateAll(array('pending'=>"$level_3_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
								
								$main_count_table->updateAll(array('pending'=>"$from_main_pending"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
								
								$ho_count_table->updateAll(array('pending'=>"$ho_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
								
								$main_count_table->updateAll(array('pending'=>"$to_main_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
								
							}elseif($for_status=='pending' && $current_status=='replied'){//from io report replied to HO pending
							
								$level_3_count_table->updateAll(array('replied'=>"$level_3_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
								
								$main_count_table->updateAll(array('replied'=>"$from_main_replied"-1,'modified'=>"$modify_date"),array('user_id'=>$from_user));
								
								$ho_count_table->updateAll(array('pending'=>"$ho_pending"+1,'modified'=>"$modify_date"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
								
								$main_count_table->updateAll(array('pending'=>"$to_main_pending"+1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
						
							}elseif($for_status=='ref_back' && $current_status=='replied'){//from HO replied replied to HO ref_back
							
								$level_3_count_table->updateAll(array('ref_back'=>"$level_3_ref_back"+1,'replied'=>"$level_3_replied"-1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user));
								
								$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'replied'=>"$from_main_replied"-1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user));
								
								$ho_count_table->updateAll(array('replied'=>"$ho_replied"+1,'ref_back'=>"$ho_ref_back"-1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
						
								$main_count_table->updateAll(array('replied'=>"$to_main_replied"+1,'ref_back'=>"$to_main_ref_back"-1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
							}
							
						}elseif($to_user_type == 'SO'){
							
							// No SO office if RO office is on level 3
					
						}
						
					}elseif($from_user_type == 'SO'){//from SO to RO office comm.
						
						if($to_user_type == 'RO'){
							if($for_status=='pending' && $current_status=='pending'){//from io report pending to HO-RO pending
							
								$level_3_count_table->updateAll(array('pending'=>"$level_3_pending"-1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user));
								
								$main_count_table->updateAll(array('pending'=>"$from_main_pending"-1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user));
								
								$ho_count_table->updateAll(array('pending'=>"$ho_pending"+1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
								
								$main_count_table->updateAll(array('pending'=>"$to_main_pending"+1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
								
							}elseif($for_status=='pending' && $current_status=='replied'){//from io report replied to HO-RO pending
							
								$level_3_count_table->updateAll(array('replied'=>"$level_3_replied"-1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user));
								
								$main_count_table->updateAll(array('replied'=>"$from_main_replied"-1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user));
								
								$ho_count_table->updateAll(array('pending'=>"$ho_pending"+1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
								
								$main_count_table->updateAll(array('pending'=>"$to_main_pending"+1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
						
							}elseif($for_status=='ref_back' && $current_status=='replied'){//from HO replied to HO-RO ref_back
							
								$level_3_count_table->updateAll(array('ref_back'=>"$level_3_ref_back"+1,'replied'=>"$level_3_replied"-1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user));
								
								$main_count_table->updateAll(array('ref_back'=>"$from_main_ref_back"+1,'replied'=>"$from_main_replied"-1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user));
								
								$ho_count_table->updateAll(array('replied'=>"$ho_replied"+1,'ref_back'=>"$ho_ref_back"-1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
								
								$main_count_table->updateAll(array('replied'=>"$to_main_replied"+1,'ref_back'=>"$to_main_ref_back"-1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
						
							}
							
						}

					}					
				
				}
				
			}//form HO level
			elseif($from_level == 'level_4'){
				
				//get HO last counts
				$get_ho_count = $ho_count_table->find('all',array('conditions'=>array('user_id IS'=>$from_user,'user_type'=>$from_user_type)))->first();
				$ho_pending_from = $get_ho_count['pending'];
				$ho_ref_back_from = $get_ho_count['ref_back'];
				$ho_replied_from = $get_ho_count['replied'];
				$ho_approved_from = $get_ho_count['approved'];
				
				if($to_level == 'level_3'){
					
					if($for_status=='ref_back' && $current_status=='pending'){
						
						$ho_count_table->updateAll(array('pending'=>"$ho_pending"-1,'ref_back'=>"$ho_ref_back"+1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user,'user_type'=>$from_user_type));
						
						$main_count_table->updateAll(array('pending'=>"$from_main_pending"-1,'ref_back'=>"$from_main_ref_back"+1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user,'user_type'=>$from_user_type));
						
						$level_3_count_table->updateAll(array('replied'=>"$level_3_replied"+1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user));
						
						$main_count_table->updateAll(array('replied'=>"$to_main_replied"+1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user));
						
					}
				
				}elseif($to_level == 'level_4'){
					
				/*	$get_ho_count = $ho_count_table->find('all',array('conditions'=>array('user_id IS'=>$to_user,'user_type'=>$to_user_type)))->first();
					$ho_pending_to = $get_ho_count['pending'];
					$ho_ref_back_to = $get_ho_count['ref_back'];
					$ho_replied_to = $get_ho_count['replied'];
					$ho_approved_to = $get_ho_count['approved'];
					
					if($for_status=='ref_back' && $current_status=='pending'){
						
						$ho_count_table->updateAll(array('pending'=>"$ho_pending"-1,'ref_back'=>"$ho_ref_back"+1,'modified'=>"'$modify_date'"),array('user_id'=>$from_user,'user_type'=>$from_user_type));
						
						$ho_count_table->updateAll(array('pending'=>"$ho_pending"+1,'modified'=>"'$modify_date'"),array('user_id'=>$to_user,'user_type'=>$to_user_type));
					}
					*/
				}
										
				
			}
			
			
		}

			
	}
