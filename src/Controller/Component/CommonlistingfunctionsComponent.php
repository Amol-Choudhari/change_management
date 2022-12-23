<?php
	namespace app\Controller\Component;
	use Cake\Controller\Controller;
	use Cake\Controller\Component;	
	use Cake\Controller\ComponentRegistry;
	use Cake\ORM\Table;
	use Cake\ORM\TableRegistry;
	use Cake\Datasource\EntityInterface;
	use Cake\Datasource\ConnectionManager;
	use Cake\Utility\Hash;
	
	class CommonlistingfunctionsComponent extends Component {
	
		
		public $components= array('Session','Customfunctions','Randomfunctions');
		public $controller = null;
		public $session = null;

		public function initialize(array $config): void {
			parent::initialize($config);
			$this->Controller = $this->_registry->getController();
			$this->Session = $this->getController()->getRequest()->getSession();
		}
		
		
		//Get MO With Nodal Create Array Status Method
		public function getMOWithNodalCreateArrayStatus($for_status,$customer_id,$final_submit_table,$DmiMoRoCommentsDetails,$each_alloc) {
			
			$DmiMoRoCommentsDetails = TableRegistry::getTableLocator()->get($DmiMoRoCommentsDetails);
			$final_submit_table = TableRegistry::getTableLocator()->get($final_submit_table);
			$creat_array = null;
			$username = $this->Session->read('username');
			
			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
			
			//check final submit status for level 1 and approved for each allocated id
			$level1_approved_status = $final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'status'=>'approved','current_level'=>'level_1'),'order'=>'id DESC'))->first();							
			//check MO RO SO comments table status for each id
			$mo_with_level3_comm = $DmiMoRoCommentsDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition),'order'=>'id DESC'))->first();	
			
			//for pending applications
			if ($for_status == 'pending') {
				
				$this->Session->write('ro_with','mo');
				
				if (empty($mo_with_level3_comm) && empty($level1_approved_status)) {
					$creat_array = $each_alloc['modified'];
				}

			//for referred back applications
			} elseif ($for_status == 'ref_back') {
				
				$this->Session->write('ro_with','mo');
				
				if (!empty($mo_with_level3_comm)) {
					
					if ($mo_with_level3_comm['available_to']=='ro' && empty($level1_approved_status)) {
						$creat_array = $mo_with_level3_comm['modified'];
					}
				}
			
			//for replied applications
			} elseif ($for_status == 'replied') {
				
				$this->Session->write('ro_with','mo');
				
				if (!empty($mo_with_level3_comm)) {

					if ($mo_with_level3_comm['available_to']=='mo' && empty($level1_approved_status)) {
						$creat_array = $mo_with_level3_comm['modified'];
					}
				}
			
			//for approved applications	
			} elseif ($for_status == 'approved') {

				if (!empty($level1_approved_status)) {
					$creat_array = $level1_approved_status['modified'];
				}
			}
			
			return $creat_array;
		}
		
		
		//Get MO With RO Create Array Status Method
		public function getMOWithROCreateArrayStatus($appl_type_id,$for_status,$customer_id,$ro_so_comments_table,$appl_current_pos_table) {
			
			$ro_so_comments_table = TableRegistry::getTableLocator()->get($ro_so_comments_table);
			$creat_array = null;
			$username = $this->Session->read('username');
			$final_submit_last_record = $this->Controller->Customfunctions->finalSubmitDetails($customer_id,'application_form',$appl_type_id);
			//get comments details from ro so comments table
			$get_comments_details = $ro_so_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
			
			//get current position
			$appl_current_pos = $this->Controller->Customfunctions->getApplCurrentPos($appl_current_pos_table,$customer_id);
		
			if (!empty($final_submit_last_record)) {

				if ($for_status == 'pending') {

					$get_comments_from_mo = $ro_so_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'from_user'=>'mo')))->first();

					if (empty($get_comments_from_mo) && ($appl_current_pos['current_level']=='level_4_mo' && $appl_current_pos['current_user_email_id']==$username)) {

						$creat_array = $appl_current_pos['modified'];
					}

				} elseif ($for_status == 'ref_back') {

					if (!($final_submit_last_record['status']=='approved' && $final_submit_last_record['current_level']=='level_3')) {

						if (!empty($get_comments_details) && $get_comments_details['from_user']=='mo' && $get_comments_details['to_user']=='ro') {

							$creat_array = $get_comments_details['modified'];
						}
					}

				} elseif ($for_status == 'replied') {

					if (!($final_submit_last_record['status']=='approved' && $final_submit_last_record['current_level']=='level_3')) {

						if (!empty($get_comments_details) && 
						   ($get_comments_details['from_user']=='ro' && $get_comments_details['to_user']=='mo') &&
						   ($appl_current_pos['current_level']=='level_4_mo' && $appl_current_pos['current_user_email_id']==$username)) {

								$creat_array = $get_comments_details['modified'];
						}
					}

				}
			
				if ($for_status == 'approved') {
					
					if($final_submit_last_record['status']=='approved' && $final_submit_last_record['current_level']=='level_3') {
						$creat_array = $final_submit_last_record['modified'];									
					}
				}
			}
			
			return $creat_array;
		}
		
		
		//Get MO With HO Create Array Status Method
		public function getMOWithHOCreateArrayStatus($for_status,$customer_id,$final_submit_table,$ho_comments_table,$each_alloc) {
			
			$final_submit_table = TableRegistry::getTableLocator()->get($final_submit_table);
			$ho_comments_table = TableRegistry::getTableLocator()->get($ho_comments_table);
			$creat_array = null;
			$username = $this->Session->read('username');
			$current_user = 'ho_mo_smo';
			//check lab export unit 
			$export_unit_status = $this->Controller->Customfunctions->checkApplicantExportUnit($customer_id);
			
			$check_last_status = $final_submit_table->find('all', array('conditions' => array('customer_id IS'=>$customer_id,'status'=>'approved', 'current_level'=>'level_3')))->first();

			if (empty($check_last_status)) {
				
				if ($for_status == 'pending') {
					
					$check_if_commented = $ho_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'OR'=>array('from_user'=>$current_user,'to_user'=>$current_user))))->first();
		
					if (empty($check_if_commented) && $each_alloc['current_level']==$each_alloc['ho_mo_smo']) {

							$creat_array = $each_alloc['modified'];
					}
				
				} elseif ($for_status == 'ref_back') {
					
					$check_if_commented = $ho_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'from_user'=>$current_user),'order'=>'id DESC'))->first();
		
					if (!empty($check_if_commented)) {

						$check_comment_to_status = 	$ho_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'to_user'=>$current_user),'order'=>'id DESC'))->first();
						
						if (!empty($check_comment_to_status)) {
							
							if ($check_if_commented['id'] > $check_comment_to_status['id']) {

								$creat_array = $check_comment_to_status['modified'];
							}
						} else {
							$creat_array = $check_if_commented['modified']; 
						}
					}

				} elseif ($for_status == 'replied') {

					$check_if_commented = $ho_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'from_user'=>$current_user),'order'=>'id DESC'))->first();
		
					if (!empty($check_if_commented)) {

						$check_comment_to_status = 	$ho_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'to_user'=>$current_user),'order'=>'id DESC'))->first();
						
						if (!empty($check_comment_to_status)) {
							
							if($check_if_commented['id'] < $check_comment_to_status['id']) {
								$creat_array = $check_comment_to_status['modified'];
							}
						}
					}
				}
			}
			
			if ($for_status == 'approved') {
				
				if (!empty($check_last_status)) {
					
					$creat_array = $check_last_status['modified'];
				}
			}
			
			return $creat_array;
		
		}
		
		
		//Get IO Create Array Status Method
		public function getIOCreateArrayStatus($appl_type_id,$for_status,$customer_id,$final_submit_table,$final_report_table,$each_alloc) {
			
			$final_submit_table = TableRegistry::getTableLocator()->get($final_submit_table);
			$final_report_table = TableRegistry::getTableLocator()->get($final_report_table);
			$creat_array = null;
			$username = $this->Session->read('username');
			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
			
			//for pending reports
			if($for_status == 'pending') {
				$check_final_reported = $final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'status'=>'approved','OR'=>array('current_level IN'=>array('level_2','level_3')),$grantDateCondition)))->first();		
				
				if (empty($check_final_reported)) {
					$creat_array = $each_alloc['modified'];
				}
			}

			$check_last_status = $this->Controller->Customfunctions->finalSubmitDetails($customer_id,'inspection_report',$appl_type_id);
			
			if (!empty($check_last_status)) {
				
				//for reports filed
				if ($for_status == 'reports_filed') {	
				
					if ($check_last_status['status'] == 'pending' && $check_last_status['current_level'] == 'level_2') {
						$creat_array = $check_last_status['modified'];
					}
				
				//for referred back reports
				} elseif ($for_status == 'ref_back') {
				
					if ($check_last_status['status'] == 'referred_back' && $check_last_status['current_level'] == 'level_3') {
						$creat_array = $check_last_status['modified'];
					}
				
				//for replied reports
				} elseif ($for_status == 'replied') {

					if($check_last_status['status'] == 'replied' && $check_last_status['current_level'] == 'level_3') {
						$creat_array = $check_last_status['modified'];
					}
				
				//for approved reports
				} elseif ($for_status == 'approved') {
				
					$final_approved_record = $final_report_table->find('all', array('conditions' => array('customer_id IS'=>$customer_id,'status'=>'approved','current_level'=>'level_3',$grantDateCondition)))->first();
					
					if (!empty($final_approved_record)) {
						$creat_array = $final_approved_record['modified'];
					}
				}

			}

			return $creat_array;
		}
		

		//Get Level 3 With Application Create Array Status Method
		public function getLevel3WithApplCreateArrayStatus($appl_type_id,$for_status) {
			
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$flow_wise_table = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type_id)))->first();
			$allocation_table = $flow_wise_table['allocation'];	
			$final_submit_table = $flow_wise_table['application_form'];
			$final_report_table = $flow_wise_table['inspection_report'];						
			$appl_current_pos_table = $flow_wise_table['appl_current_pos'];
			
			$allocationTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$allocation_table))));
			$finalReportTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$final_report_table))));
			$finalSubmitTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$final_submit_table))));
			$applCurrentPosTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$appl_current_pos_table))));
			
			$creat_array = false;
			$username = $this->Session->read('username');
			$conditions = null;
	 
			if ($for_status == 'pending') {

				$conditions = "cp.current_level != 'level_1' and al.level_3 = '$username' and al.level_1 IS NULL and al.level_2 IS NULL AND fsr.status = 'pending' AND fsr.current_level = 'level_1'";
			
			} elseif ($for_status == 'ref_back') {

				$conditions = "cp.current_level != 'level_1' AND al.level_3 = '$username' AND fsr.status = 'referred_back' AND fsr.current_level = 'level_3'";
			
			} elseif ($for_status == 'replied') {

				$conditions = "cp.current_level != 'level_1' AND al.level_3 = '$username' AND fsr.status = 'replied' AND fsr.current_level = 'level_3'";
			}

		
			$conn = ConnectionManager::get('default');
			
			if (!empty($conditions)) {

				$stmt = $conn->execute("select al.*,fsr.modified as tradate from $allocationTable as al 
									    inner join (select fss.customer_id, fss.status , fss.current_level, fss.modified from $finalSubmitTable as fss
										inner join (select max(id) id, customer_id from $finalSubmitTable group by customer_id) as fs on fs.customer_id = fss.customer_id and fs.id = fss.id) as fsr on fsr.customer_id = al.customer_id
										inner join (select max(id) id, customer_id from $allocationTable group by customer_id) as maxall on maxall.customer_id = al.customer_id and maxall.id = al.id
										inner join (select maxcpt.* from $applCurrentPosTable as maxcpt
										inner join (select max(id) id, customer_id from $applCurrentPosTable group by customer_id) as mxcpt on mxcpt.customer_id = maxcpt.customer_id and mxcpt.id = maxcpt.id) as cp on cp.customer_id = al.customer_id
										where $conditions");
								
			}/* elseif($for_status == 'approved'){
				$stmt = $conn->execute("select al.* from $allocationTable as al 
								inner join $finalSubmitTable as fss on fss.customer_id = al.customer_id and fss.status = 'approved' and fss.current_level = 'level_1'
								inner join $applCurrentPosTable as cp on cp.customer_id = al.customer_id
								where al.level_3 = '$username';");	 
			} */
			
			$results = array();
			if (!empty($stmt)) {	
				$results = $stmt ->fetchAll('assoc');
			}	
			return $results;
		}
		
		

		//Get Level 3 With MO Create Array Status Method
		public function getLevel3WithMOCreateArrayStatus($appl_type_id,$for_status) {
			
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$flow_wise_table = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type_id)))->first();
			$allocation_table = $flow_wise_table['allocation'];	
			$final_submit_table = $flow_wise_table['application_form'];
			$DmiMoRoCommentsDetails = $flow_wise_table['commenting_with_mo'];
			$appl_current_pos_table = $flow_wise_table['appl_current_pos'];
			
			$allocationTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$allocation_table))));
			$moRoCommentsDetailsTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$DmiMoRoCommentsDetails))));
			$finalSubmitTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$final_submit_table))));
			$applCurrentPosTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$appl_current_pos_table))));
			
			$creat_array = false;
			$username = $this->Session->read('username');
			$conditions = null;

			if ($for_status == 'ref_back') {

				$conditions = "cp.current_level != 'applicant' and al.level_3 = '$username' and al.level_1 IS NOT NULL and al.current_level = al.level_1 AND 
							  ((fsr.status != 'approved' AND fsr.current_level != 'level_1') OR (fsr.status != 'approved' AND fsr.current_level != 'level_3')) AND
							  cmt.available_to = 'mo'";
			} elseif ($for_status == 'replied') {

				$conditions = "cp.current_level != 'applicant' and cp.current_user_email_id='$username' and al.level_3 = '$username' and al.level_1 IS NOT NULL and al.current_level = al.level_1 AND 
							  ((fsr.status != 'approved' AND fsr.current_level != 'level_1') OR (fsr.status != 'approved' AND fsr.current_level != 'level_3')) AND
							  cmt.available_to = 'ro'";
			}
			
			$conn = ConnectionManager::get('default');
			
			if ($for_status == 'pending') {
				
				$stmt = $conn->execute("select al.*,cp.modified as tradate from $allocationTable as al 
										inner join (select fss.customer_id, fss.status , fss.current_level from $finalSubmitTable as fss
										inner join (select max(id) id, customer_id from $finalSubmitTable group by customer_id) as fs on fs.customer_id = fss.customer_id and fs.id = fss.id) as fsr on fsr.customer_id = al.customer_id
										LEFT  join (select mrc.customer_id,mrc.available_to from $moRoCommentsDetailsTable as mrc
										inner join (select max(id) id,customer_id from $moRoCommentsDetailsTable group by customer_id) as mrcc on mrcc.customer_id = mrc.customer_id and mrcc.id = mrc.id) as cmt on cmt.customer_id = al.customer_id
										inner join (select max(id) id, customer_id from $allocationTable group by customer_id) as maxall on maxall.customer_id = al.customer_id and maxall.id = al.id
										inner join (select maxcpt.* from $applCurrentPosTable as maxcpt
										inner join (select max(id) id, customer_id from $applCurrentPosTable group by customer_id) as mxcpt on mxcpt.customer_id = maxcpt.customer_id and mxcpt.id = maxcpt.id) as cp on cp.customer_id = al.customer_id
										where cp.current_level != 'applicant' and al.level_3 = '$username' and al.level_1 IS NOT NULL and al.current_level = al.level_1 AND ((fsr.status != 'approved' AND fsr.current_level != 'level_1') OR (fsr.status != 'approved' AND fsr.current_level != 'level_3')) AND
										cmt.customer_id IS NULL;");
										
			} elseif ($for_status == 'ref_back' || $for_status == 'replied') {
				
				$stmt = $conn->execute("select al.*,cmt.modified as tradate from $allocationTable as al 
										inner join (select fss.customer_id, fss.status , fss.current_level from $finalSubmitTable as fss
										inner join (select max(id) id, customer_id from $finalSubmitTable group by customer_id) as fs on fs.customer_id = fss.customer_id and fs.id = fss.id) as fsr on fsr.customer_id = al.customer_id
										inner join (select mrc.customer_id,mrc.available_to,mrc.modified from $moRoCommentsDetailsTable as mrc
										inner join (select max(id) id,customer_id from $moRoCommentsDetailsTable group by customer_id) as mrcc on mrcc.customer_id = mrc.customer_id and mrcc.id = mrc.id) as cmt on cmt.customer_id = al.customer_id
										inner join (select max(id) id, customer_id from $allocationTable group by customer_id) as maxall on maxall.customer_id = al.customer_id and maxall.id = al.id
										inner join (select maxcpt.* from $applCurrentPosTable as maxcpt
										inner join (select max(id) id, customer_id from $applCurrentPosTable group by customer_id) as mxcpt on mxcpt.customer_id = maxcpt.customer_id and mxcpt.id = maxcpt.id) as cp on cp.customer_id = al.customer_id
										where $conditions");
								
			} elseif ($for_status == 'approved') {
				
				if ($appl_type_id == 1) {
					$innerJoin = "LEFT join dmi_renewal_final_submits as drfs on drfs.customer_id = al.customer_id";
					$approvedConditions = "al.level_3 = '$username' and drfs.customer_id IS NULL";
				} else {
					$innerJoin = '';
					$approvedConditions = "al.level_3 = '$username'";
				}
				
				//print_r($appl_type_id);
				$stmt = $conn->execute("select al.*,fsr.modified as tradate from $allocationTable as al
										inner join (select fss.customer_id, fss.status , fss.current_level, fss.modified from $finalSubmitTable as fss
										inner join (select max(id) id, customer_id from $finalSubmitTable group by customer_id) as fs on fs.customer_id = fss.customer_id and fs.id = fss.id) as fsr on fsr.customer_id = al.customer_id and fsr.status = 'approved'
										inner join (select max(id) id, customer_id from $allocationTable group by customer_id) as maxall on maxall.customer_id = al.customer_id and maxall.id = al.id
										inner join ( select maxcpt.* from $applCurrentPosTable as maxcpt
										inner join (select max(id) id, customer_id from $applCurrentPosTable group by customer_id) as mxcpt on mxcpt.customer_id = maxcpt.customer_id and mxcpt.id = maxcpt.id) as cp on cp.customer_id = al.customer_id 
										$innerJoin
										where $approvedConditions order by al.id desc;");
			}
			
			$results = array();

			if (!empty($stmt)){

				$results = $stmt ->fetchAll('assoc');
			}
			
			return $results;
		}
		
		
		
		public function getLevel3WithIOCreateArrayStatus($appl_type_id,$for_status) {
			
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$flow_wise_table = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type_id)))->first();
			$allocation_table = $flow_wise_table['allocation'];	
			$final_submit_table = $flow_wise_table['application_form'];
			$final_report_table = $flow_wise_table['inspection_report'];
			$appl_current_pos_table = $flow_wise_table['appl_current_pos'];
			
			$allocationTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$allocation_table))));
			$finalSubmitTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$final_submit_table))));	
			$finalReportTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$final_report_table))));			
			$applCurrentPosTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$appl_current_pos_table))));
			
			$creat_array = false;
			$username = $this->Session->read('username');
			$conditions = null;
	
			if ($for_status == 'pending') {

				$conditions = "al.level_3 = '$username' and cp.current_user_email_id = '$username' and al.level_2 IS NOT NULL AND fsr.status = 'pending' AND fsr.current_level = 'level_2' and ffsa.status='approved'";		  
			
			} elseif ($for_status == 'ref_back') {
	 
				$conditions = "al.level_3 = '$username' and al.level_2 IS NOT NULL AND fsr.status = 'referred_back' AND fsr.current_level = 'level_3'";												  
			
			} elseif ($for_status == 'replied'){
				
				$conditions = "al.level_3 = '$username' and cp.current_user_email_id = '$username' and al.level_2 IS NOT NULL AND fsr.status = 'replied' AND fsr.current_level = 'level_3' and ffsa.status='approved' ";
			}
			
			$conn = ConnectionManager::get('default');

			if (!empty($conditions)) {

				$stmt = $conn->execute("select al.*,fsr.modified as tradate from $allocationTable as al 
										inner join (select dfsa.customer_id, dfsa.status , dfsa.current_level from $finalSubmitTable as dfsa
										inner join (select max(id) id, customer_id from $finalSubmitTable group by customer_id) as fsa on fsa.customer_id = dfsa.customer_id and fsa.id = dfsa.id) as ffsa on ffsa.customer_id = al.customer_id
										inner join (select fss.customer_id, fss.status , fss.current_level, fss.modified from $finalReportTable as fss
										inner join (select max(id) id, customer_id from $finalReportTable group by customer_id) as fs on fs.customer_id = fss.customer_id and fs.id = fss.id) as fsr on fsr.customer_id = al.customer_id
										inner join (select max(id) id, customer_id from $allocationTable group by customer_id) as maxall on maxall.customer_id = al.customer_id and maxall.id = al.id
										inner join (select maxcpt.customer_id, maxcpt.current_level, maxcpt.current_user_email_id from $applCurrentPosTable as maxcpt
										inner join (select max(id) id, customer_id from $applCurrentPosTable group by customer_id) as mxcpt on mxcpt.customer_id = maxcpt.customer_id and mxcpt.id = maxcpt.id) as cp on cp.customer_id = al.customer_id
										where $conditions");
								
			} elseif ($for_status == 'approved') {

				$stmt = $conn->execute("select al.*,fsr.modified as tradate from $allocationTable as al
										inner join (select fss.customer_id, fss.status , fss.current_level, fss.modified from $finalReportTable as fss
										inner join (select max(id) id, customer_id from $finalReportTable group by customer_id) as fs on fs.customer_id = fss.customer_id and fs.id = fss.id) as fsr on fsr.customer_id = al.customer_id and fsr.status = 'approved' and fsr.current_level = 'level_3'
										inner join (select max(id) id, customer_id from $allocationTable group by customer_id) as maxall on maxall.customer_id = al.customer_id and maxall.id = al.id
										inner join (select maxcpt.customer_id, maxcpt.current_level from $applCurrentPosTable as maxcpt
										inner join (select max(id) id, customer_id from $applCurrentPosTable group by customer_id) as mxcpt on mxcpt.customer_id = maxcpt.customer_id and mxcpt.id = maxcpt.id) as cp on cp.customer_id = al.customer_id
										where al.level_3 = '$username'");
			}
			
			$results = array();
			
			if (!empty($stmt)) {
				$results = $stmt ->fetchAll('assoc');
			}
			
			return $results;		
			
		}
			
		
		//Get RO With SO Create Array Status Method
		public function getROwithSOCreateArrayStatus($appl_type_id,$for_status,$allocation_table,$final_submit_table,$ho_comment_reply_details_table,$ro_so_comments_table,$appl_current_pos_table) {
			
			$allocationTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$allocation_table))));
			$roSoCommentsTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$ro_so_comments_table))));			
			$finalSubmitTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$final_submit_table))));
			$hoCommentReplyDetailsTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$ho_comment_reply_details_table))));
			$applCurrentPosTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$appl_current_pos_table))));
			$creat_array = false;
			$username = $this->Session->read('username');
			
			if ($appl_type_id == 2 || $appl_type_id == 3) {//added temp for all change flow to avoid level 2 check, on 21-12-2022
				$level2 = null;			
			} else {
				$level2 = "and al.level_2 IS NOT NULL";
			}			
			
			$conditions = null;
			if ($for_status == 'pending') {

				$conditions = "al.level_4_ro = '$username' and cp.current_user_email_id = '$username' $level2 AND hlct.customer_id IS NULL and rsct.customer_id IS NULL";
				$tradate = "cp.modified as tradate";
			
			} elseif ($for_status == 'ref_back') {

				$conditions = "al.level_4_ro = '$username' $level2 AND rsct.from_user ='ro' and rsct.to_user ='so'";
				$tradate = "rsct.modified as tradate";
			
			} elseif ($for_status == 'replied') {

				$conditions = "al.level_4_ro = '$username' and cp.current_user_email_id = '$username' $level2 AND (fsr.status !='approved' OR fsr.current_level !='level_3') and (rsct.from_user ='so' OR rsct.from_user ='mo' ) and rsct.to_user ='ro'";
				$tradate = "rsct.modified as tradate";
				
			} elseif ($for_status == 'approved') {

				$conditions = "al.level_4_ro = '$username' $level2 and fsr.status ='approved' and fsr.current_level ='level_3'";
				$tradate = "fsr.modified as tradate";
			}
			
			
			$conn = ConnectionManager::get('default');
			
			if (!empty($conditions)) {
				
				$stmt = $conn->execute("select al.*,$tradate from $allocationTable as al 
										inner join(
											select fss.customer_id, fss.status , fss.current_level, fss.modified
											from $finalSubmitTable as fss
											inner join (
												select max(id) id, customer_id
												from $finalSubmitTable
												group by customer_id
											) as fs on fs.customer_id = fss.customer_id and fs.id = fss.id
										) as fsr on fsr.customer_id = al.customer_id										
										LEFT join(
											select rsc.customer_id,rsc.to_user,rsc.from_user,rsc.modified
											from $roSoCommentsTable as rsc
											inner join (
												select max(id) id,customer_id
												from $roSoCommentsTable
												group by customer_id
											) as rscc on rscc.customer_id = rsc.customer_id and rscc.id = rsc.id
										) as rsct on rsct.customer_id = al.customer_id										
										LEFT join(
											select hlc.customer_id
											from $hoCommentReplyDetailsTable as hlc
											inner join (
												select max(id) id,customer_id
												from $hoCommentReplyDetailsTable
												group by customer_id
											) as hlcc on hlcc.customer_id = hlc.customer_id and hlcc.id = hlc.id
										) as hlct on hlct.customer_id = al.customer_id										
										inner join (
												select max(id) id, customer_id
												from $allocationTable
												group by customer_id
										) as maxall on maxall.customer_id = al.customer_id and maxall.id = al.id
										inner join ( 
											select maxcpt.customer_id, maxcpt.current_level, maxcpt.current_user_email_id,maxcpt.modified
											from $applCurrentPosTable as maxcpt
											inner join (
												select max(id) id, customer_id
												from $applCurrentPosTable
												group by customer_id
											) as mxcpt on mxcpt.customer_id = maxcpt.customer_id and mxcpt.id = maxcpt.id								
										) as cp on cp.customer_id = al.customer_id										
										where $conditions");
								
			}
			
					   
			
			$results = array();
			if(!empty($stmt)){	
				$results = $stmt ->fetchAll('assoc');			
			}	
			return $results;			
		}
		
		
		public function getSOwithROCreateArrayStatus($appl_type_id,$for_status){
			
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$flow_wise_table = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type_id)))->first();
			$allocation_table = $flow_wise_table['allocation'];	
			$final_submit_table = $flow_wise_table['application_form'];	
			$ro_so_comments_table = $flow_wise_table['ro_so_comments'];
			$ho_comments_table = $flow_wise_table['ho_comment_reply'];
			$appl_current_pos_table = $flow_wise_table['appl_current_pos'];
			
			$allocationTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$allocation_table))));
			$roSoCommentsTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$ro_so_comments_table))));			
			$finalSubmitTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$final_submit_table))));
			$hoCommentReplyDetailsTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$ho_comments_table))));
			$applCurrentPosTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$appl_current_pos_table))));
			
			$creat_array = false;
			$username = $this->Session->read('username');
			
			$conditions = null;
			if($for_status == 'pending'){
				$conditions = "al.level_3 = '$username' and cp.current_user_email_id = '$username' and al.level_4_ro IS NOT NULL AND 			  
								hlct.customer_id IS NOT NULL and rsct.customer_id IS NULL and fsr.status ='approved' ";
				$tradate = "cp.modified as tradate";
			}
			elseif($for_status == 'ref_back'){	
				$conditions = "al.level_3 = '$username' and al.level_4_ro IS NOT NULL and rsct.from_user ='so' and 
							  rsct.to_user ='ro' and fsr.current_level !='level_3'";	
				$tradate = "rsct.modified as tradate";				
			}
			elseif($for_status == 'replied'){						
				$conditions = "al.level_3 = '$username' and cp.current_user_email_id = '$username' and al.level_4_ro IS NOT NULL AND rsct.from_user ='ro' and 
							  rsct.to_user ='so' and fsr.status ='approved' and cp.current_level ='level_3'";
				$tradate = "rsct.modified as tradate";
				
			}elseif($for_status == 'approved'){
				$conditions = "al.level_3 = '$username' and al.level_4_ro IS NOT NULL and 			  
							   fsr.status ='approved' and fsr.current_level ='level_3'";
				$tradate = "fsr.modified as tradate";			   
			}
			
						 
									
			
			$conn = ConnectionManager::get('default');
			
			if(!empty($conditions)){
				$stmt = $conn->execute("select al.*,$tradate from $allocationTable as al 		
										inner join(
											select fss.customer_id, fss.status , fss.current_level, fss.modified
											from $finalSubmitTable as fss
											inner join (
												select max(id) id, customer_id
												from $finalSubmitTable
												group by customer_id
											) as fs on fs.customer_id = fss.customer_id and fs.id = fss.id
										) as fsr on fsr.customer_id = al.customer_id										
										LEFT join(
											select rsc.customer_id, rsc.to_user,rsc.from_user,rsc.modified
											from $roSoCommentsTable as rsc
											inner join (
												select max(id) id,customer_id
												from $roSoCommentsTable
												group by customer_id
											) as rscc on rscc.customer_id = rsc.customer_id and rscc.id = rsc.id
										) as rsct on rsct.customer_id = al.customer_id										
										LEFT join(
											select hlc.customer_id
											from $hoCommentReplyDetailsTable as hlc
											inner join (
												select max(id) id,customer_id
												from $hoCommentReplyDetailsTable
												group by customer_id
											) as hlcc on hlcc.customer_id = hlc.customer_id and hlcc.id = hlc.id
										) as hlct on hlct.customer_id = al.customer_id										
										inner join (
												select max(id) id, customer_id
												from $allocationTable
												group by customer_id
										) as maxall on maxall.customer_id = al.customer_id and maxall.id = al.id
										inner join ( 
											select maxcpt.customer_id, maxcpt.current_level, maxcpt.current_user_email_id,maxcpt.modified
											from $applCurrentPosTable as maxcpt
											inner join (
												select max(id) id, customer_id
												from $applCurrentPosTable
												group by customer_id
											) as mxcpt on mxcpt.customer_id = maxcpt.customer_id and mxcpt.id = maxcpt.id								
										) as cp on cp.customer_id = al.customer_id										
										where $conditions");
								
			}
			
			
			$results = array();
			if(!empty($stmt)){	
				$results = $stmt ->fetchAll('assoc');			
			}
			return $results;
			
		}
		
		
		
		public function getRoWithHoCreateArrayStatus($appl_type_id,$customer_id,$for_status,$ho_comments_table,$each_alloc){
			
			//model initialize
			$ho_comments_table = TableRegistry::getTableLocator()->get($ho_comments_table);
			$creat_array = null;
			$username = $this->Session->read('username');
			
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type_id)))->first();
			$appl_current_pos_table = TableRegistry::getTableLocator()->get($flow_wise_tables['appl_current_pos']);
			
			$form_type = $this->Controller->Customfunctions->checkApplicantFormType($customer_id,$appl_type_id);//added appl type param on 22-11-2021
			$office_type = $this->Controller->Customfunctions->getApplDistrictOffice($customer_id,$appl_type_id);
			$firm_type = $this->Controller->Customfunctions->firmType($customer_id);
			
			$DmiCommonSiteinspectionFlowDetails = TableRegistry::getTableLocator()->get('DmiCommonSiteinspectionFlowDetails');
			$allSectionDetails = $DmiCommonSiteinspectionFlowDetails->allSectionList($appl_type_id,$office_type,$firm_type,$form_type);
			$all_report_status = $DmiCommonSiteinspectionFlowDetails->reportSectionApproveStatus($customer_id,$allSectionDetails);
			
			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
			
			//check application is for export
			$export_unit_status = $this->Controller->Customfunctions->checkApplicantExportUnit($customer_id);														
			
			//check CA BEVO Applicant	
			$ca_bevo_applicant = $this->Controller->Customfunctions->checkCaBevo($customer_id);						
			
			$office_email_id = $this->Controller->Customfunctions->getApplRegOfficeId($customer_id,$appl_type_id);
			$split_customer_id = explode('/',$customer_id);

			//as per new order by 01-04-2021 from DMI
			//if lab is NABL accreditated then no site inspection will be done, forwarded to HO
			//applied on 30-09-2021 by Amol
			$NablDate = $this->Randomfunctions->checkIfLabNablAccreditated($customer_id);

			//added condition for lab export, as there will be no siteinspection, so default set to true
			//29-09-2021 by Amol
			if($split_customer_id[1]==3 && ($export_unit_status == 'yes' || $NablDate != null)){//updated on 30-09-2021

				$all_report_status = 'true';
			}

			if($this->Session->read('username') == $office_email_id)
			{
				$find_id_list = $this->Controller->Customfunctions->finalSubmitDetails($customer_id,'inspection_report',$appl_type_id);	
				
				$app_current_pending = $appl_current_pos_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'current_user_email_id'=>$username,$grantDateCondition),'order'=>array('id DESC')))->first();
				
				if($for_status == 'pending'){
					
					if(!empty($app_current_pending)){
						
						$this->Session->write('ho_comments_readonly','yes');
						
						//to get list of lab export appln allocated to HO without Report.					
						if($split_customer_id[1]==3 && empty($find_id_list) && ($export_unit_status == 'yes' || $NablDate != null))
						{											
							$check_if_commented = $ho_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'to_user'=>'ro')))->first();

							if(empty($check_if_commented))
							{
								$creat_array = $each_alloc['modified'];															
							}											
						}
						else{
					
							$find_max_id_status = $this->Controller->Customfunctions->finalSubmitDetails($customer_id,'inspection_report',$appl_type_id);
								
							//now getting applications by "from_user" & "to_user" either "comment_by/comment_to"
							$check_if_commented = $ho_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'to_user'=>'ro')))->first();

							if(empty($check_if_commented))
							{
								if(($find_max_id_status['status'] == 'pending' && $find_max_id_status['current_level'] == 'level_2') ||
									($find_max_id_status['status'] == 'referred_back' || $find_max_id_status['status'] == 'replied' ||
									$find_max_id_status['status'] == 'ho_allocated' && $find_max_id_status['current_level'] == 'level_3'))
								{
									$creat_array = $find_max_id_status['modified'];												
								}	
							}					
						}
					}	
				}
				elseif($for_status == 'ref_back'){
					
					$this->Session->write('ho_comments_readonly','yes');
					//to get list of lab export appln allocated to HO without Report.					
					if($split_customer_id[1]==3 && empty($find_id_list) && ($export_unit_status == 'yes' || $NablDate != null))
					{
						$check_if_commented = $ho_comments_table->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,'to_user'=>'ro')))->toList();
						
						if(!empty($check_if_commented))
						{
							//now getting applications by "from_user" & "to_user" either "comment_by/comment_to"
							$check_if_commented = $ho_comments_table->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,'from_user'=>'ro')))->toList();
																					
							if(!empty($check_if_commented))
							{
								//now getting applications by "from_user" & "to_user" either "comment_by/comment_to"
								$check_comment_to_status = $ho_comments_table->find('all',array('conditions'=>array('id >'=>max($check_if_commented),
																										'customer_id IS'=>$customer_id,'to_user'=>'ro')))->first();																										
								
								$comment_date = $ho_comments_table->find('all',array('conditions'=>array('id >'=>max($check_if_commented))))->first();
																										
								if(empty($check_comment_to_status))
								{
									$creat_array = $comment_date['modified'];
								}								
							}						
						}
							
					}else{
						
						$find_max_id_status = $this->Controller->Customfunctions->finalSubmitDetails($customer_id,'inspection_report',$appl_type_id);
						
						//now getting applications by "from_user" & "to_user" either "comment_by/comment_to"
						$check_if_commented = $ho_comments_table->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,'from_user'=>'ro')))->toList();
																					
						if(!empty($check_if_commented))
						{
							//now getting applications by "from_user" & "to_user" either "comment_by/comment_to"
							$check_comment_to_status = 	$ho_comments_table->find('all',array('conditions'=>array('id >'=>max($check_if_commented),
																									'customer_id IS'=>$customer_id, 'to_user'=>'ro')))->first();
							
							$comment_date = $ho_comments_table->find('all',array('conditions'=>array('id >'=>max($check_if_commented))))->first();
																									
							if(empty($check_comment_to_status))
							{
								if(($find_max_id_status['status'] == 'pending' && $find_max_id_status['current_level'] == 'level_2') ||
								($find_max_id_status['status'] == 'referred_back' || $find_max_id_status['status'] == 'replied' ||
								$find_max_id_status['status'] == 'ho_allocated' && $find_max_id_status['current_level'] == 'level_3'))			
								{
									$creat_array = $comment_date['modified'];
								}								
							}
						}
					}				
				}
				elseif($for_status == 'replied'){
					
					$this->Session->write('ho_comments_readonly',null);	
					
					if(!empty($app_current_pending) && $all_report_status=='true'){
						
						//to get list of lab export appln allocated to HO without Report.
						if($split_customer_id[1]==3 && empty($find_id_list) && ($export_unit_status == 'yes' || $NablDate != null))
						{
							$check_if_commented = $ho_comments_table->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,'to_user'=>'ro')))->toList();
							
								if(!empty($check_if_commented))
								{
									//now getting applications by "from_user" & "to_user" either "comment_by/comment_to"
									$check_comment_to_status = $ho_comments_table->find('all',array('conditions'=>array('id >'=>max($check_if_commented),
																											'customer_id IS'=>$customer_id,'from_user'=>'ro')))->first();
									
									$comment_date = $ho_comments_table->find('all',array('conditions'=>array('id'=>max($check_if_commented))))->first();
																											
									if(empty($check_comment_to_status)){
										
										$creat_array = $comment_date['modified'];
									}
								}
							
						}else{
							
							$find_max_id_status = $this->Controller->Customfunctions->finalSubmitDetails($customer_id,'inspection_report',$appl_type_id);
							$find_app_max_status = $this->Controller->Customfunctions->finalSubmitDetails($customer_id,'application_form',$appl_type_id);
							
							//now getting applications by "from_user" & "to_user" either "comment_by/comment_to"
							$check_if_commented = $ho_comments_table->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,'to_user'=>'ro')))->toList();
						
							if(!empty($check_if_commented) && $find_app_max_status['status']=='approved')
							{	
								//now getting applications by "from_user" & "to_user" either "comment_by/comment_to"
								$check_comment_to_status = 	$ho_comments_table->find('all',array('conditions'=>array('id >'=>max($check_if_commented),
																														'customer_id IS'=>$customer_id,'from_user'=>'ro')))->first();
							
								$comment_date = $ho_comments_table->find('all',array('conditions'=>array('id'=>max($check_if_commented))))->first();
								
								if(empty($check_comment_to_status))
								{						
									if(($find_max_id_status['status'] == 'pending' && $find_max_id_status['current_level'] == 'level_2') ||
									($find_max_id_status['status'] == 'referred_back' || $find_max_id_status['status'] == 'replied' ||
									$find_max_id_status['status'] == 'ho_allocated' && $find_max_id_status['current_level'] == 'level_3'))						
									{										
										$creat_array = $comment_date['modified'];
									}
								}					
							}					
						}
					}
				}					
				
			}
			if($for_status == 'approved'){
				
				$this->Session->write('ho_comments_readonly','yes');
				
				$final_submit_last_record = $this->Controller->Customfunctions->finalSubmitDetails($customer_id,'application_form',$appl_type_id);
				
				if(!empty($final_submit_last_record) && ($final_submit_last_record['status']=='approved' && 
					($final_submit_last_record['current_level']=='level_3' || $final_submit_last_record['current_level']=='level_4')))
				{																
					$creat_array = $final_submit_last_record['modified'];									
				}
			}
			
			return $creat_array;
		}
		
		
		
		public function getHoLevelCreateArrayStatus($appl_type_id,$customer_id,$for_status,$sub_tab,$list_for_field,$ho_comments_table){
			
			//model initialize
			$ho_comments_table = TableRegistry::getTableLocator()->get($ho_comments_table);
			$creat_array = null;
			$username = $this->Session->read('username');
			
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type_id)))->first();
			$appl_current_pos_table = TableRegistry::getTableLocator()->get($flow_wise_tables['appl_current_pos']);
		
			$final_submit_status = $this->Controller->Customfunctions->finalSubmitDetails($customer_id,'application_form',$appl_type_id);
			
			$grantDateCondition = $this->Controller->Customfunctions->returnGrantDateCondition($customer_id);
				
			$app_current_pending = $appl_current_pos_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'current_user_email_id'=>$username,$grantDateCondition),'order'=>array('id DESC')))->first();
			
			if(!empty($final_submit_status)){
				if(!($final_submit_status['status']=='approved' && 
					($final_submit_status['current_level']=='level_3' || $final_submit_status['current_level']=='level_4')))
				{
					if($for_status == 'pending'){
						
						if(!empty($app_current_pending)){
							
							if($sub_tab=='for_ho_scrutiny' || $sub_tab=='for_dy_ama'){
								
								$check_if_commented = $ho_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,
																					'OR'=>array('from_user IS'=>$list_for_field,'to_user IS'=>$list_for_field))))->first();									
								$comment_date = $app_current_pending['modified'];
							}else{
								
								$check_if_commented =null;
								$check_comment_to = $ho_comments_table->find('all',array('conditions'=>array($grantDateCondition,'customer_id IS'=>$customer_id,'to_user IS'=>$list_for_field)))->first();
							
								if(!empty($check_comment_to))
								{
									$comment_date = $check_comment_to['modified'];
									$check_if_commented = $ho_comments_table->find('all',array('conditions'=>array($grantDateCondition,'id >'=>$check_comment_to['id'],
																							'customer_id IS'=>$customer_id,'from_user IS'=>$list_for_field)))->first();																			
								}
							}
							
							if((!empty($check_comment_to) && empty($check_if_commented))||
								(empty($check_if_commented) && ($sub_tab=='for_ho_scrutiny' || $sub_tab=='for_dy_ama')))
							{
								
								$creat_array = $comment_date;
								
							}
						}
					}
					elseif($for_status == 'ref_back'){
						
						$check_if_commented = $ho_comments_table->find('all',array('conditions'=>array($grantDateCondition,'customer_id IS'=>$customer_id,
																			'from_user IS'=>$list_for_field),'order'=>'id DESC'))->first();
																			
						$check_last_to_status = $ho_comments_table->find('all',array('conditions'=>array($grantDateCondition,'customer_id IS'=>$customer_id,
																			'to_user IS'=>$list_for_field),'order'=>'id DESC'))->first();
						
						if(!empty($check_if_commented))
						{
															
							if(empty($check_last_to_status) || ($check_if_commented['id'] >= $check_last_to_status['id']))
							{
								$creat_array = $check_if_commented['modified'];
								
							}
								
						}
					}
					elseif($for_status == 'replied'){
						
						if(!empty($app_current_pending)){
							
							$check_if_commented = $ho_comments_table->find('all',array('conditions'=>array($grantDateCondition,'customer_id IS'=>$customer_id,
																				'from_user IS'=>$list_for_field),'order'=>'id DESC'))->first();
																				
							$check_comment_to_status = null;
							if(!empty($check_if_commented))
							{
								$check_comment_to_status = 	$ho_comments_table->find('all',array('conditions'=>array($grantDateCondition,'customer_id IS'=>$customer_id,
																							'to_user IS'=>$list_for_field),'order'=>'id DESC'))->first();																													
							}
							
							elseif(empty($check_if_commented))
							{
								if($sub_tab=='for_dy_ama')
								{
									$check_comment_to_status = 	$ho_comments_table->find('all',array('conditions'=>array($grantDateCondition,'customer_id IS'=>$customer_id,'to_user IS'=>$list_for_field)))->first();			
								}
								
								$check_if_commented['id'] = null;
							}
							
							if(!empty($check_comment_to_status) && ($check_if_commented['id'] <= $check_comment_to_status['id']))
							{
								$creat_array = $app_current_pending['modified'];
							}
							
						}
						
					}
				}
				elseif($for_status == 'approved'){
																					
					$creat_array = $final_submit_status['modified'];									

				}		
			}
			
			return $creat_array;
		}
		
		
		
		public function getCommonListingVariables($i,$each_alloc,$for_level,$for_status,$appl_type_id,$appl_type,$list_for_field=null,$sub_tab=null){
			
			$creat_array = null;
			$customer_id = $each_alloc['customer_id'];							
			//model initialize
			$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
			$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');

			$flow_wise_table = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type_id)))->first();
			$allocation_table = $flow_wise_table['allocation'];	
			$final_submit_table = $flow_wise_table['application_form'];			
			$final_report_table = $flow_wise_table['inspection_report'];
			$ro_so_comments_table = $flow_wise_table['ro_so_comments'];
			$ho_comments_table = $flow_wise_table['ho_comment_reply'];
			$appl_current_pos_table = $flow_wise_table['appl_current_pos'];
			$DmiMoRoCommentsDetails = $flow_wise_table['commenting_with_mo'];

			//get_form_type
			$form_type = $this->Controller->Customfunctions->checkApplicantFormType($customer_id);
			$office_type = $this->Controller->Customfunctions->getApplDistrictOffice($customer_id,$appl_type_id);
			//get firm details
			$firm_details = $DmiFirms->firmDetails($customer_id);
			$firm_name = $firm_details['firm_name'];
			$firm_table_id = $firm_details['id'];
			
			//common link
			$appl_view_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;
			$appl_edit_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/edit/'.$appl_type_id;
			$comm_with_email = null;
	
			//extra conditional stuff
			if($for_level=='pao'){
				$comm_with = 'Applicant';
				$appl_view_link = '../paymentverifications/inspect_payment_fetch_id/'.$each_alloc['id'].'/'.$appl_type_id;
				$appl_edit_link = '../paymentverifications/inspect_payment_fetch_id/'.$each_alloc['id'].'/'.$appl_type_id;
				
				if($for_status == 'pending'){$creat_array = true;}
				elseif($for_status == 'not_confirmed'){$creat_array = true;}
				elseif($for_status == 'replied'){$creat_array = true;}
				elseif($for_status == 'confirmed'){$creat_array = true;}
							
			}else{
				
				if($for_level=='level_1'){
					if($sub_tab=='scrutiny_with_nodal_office'){
						$creat_array = $this->getMOWithNodalCreateArrayStatus($for_status,$customer_id,$final_submit_table,$DmiMoRoCommentsDetails,$each_alloc);
						$comm_with_email = $each_alloc['level_3'];
					}
					elseif($sub_tab=='scrutiny_with_reg_office'){
						$creat_array = $this->getMOWithROCreateArrayStatus($appl_type_id,$for_status,$customer_id,$ro_so_comments_table,$appl_current_pos_table);
						$comm_with_email = $each_alloc['level_4_ro'];
						$appl_view_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/view/'.$appl_type_id;
						$appl_edit_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/edit/'.$appl_type_id;
					}
					elseif($sub_tab=='scrutiny_with_ho_office'){
						$creat_array = $this->getMOWithHOCreateArrayStatus($for_status,$customer_id,$final_submit_table,$ho_comments_table,$each_alloc);
						$comm_with_email = $each_alloc['dy_ama'];
						$appl_view_link = '../hoinspections/fetch_record_id/'.$firm_table_id.'/view/'.$appl_type_id;
						$appl_edit_link = '../hoinspections/fetch_record_id/'.$firm_table_id.'/edit/'.$appl_type_id;
					}
				}
				elseif($for_level == 'level_2'){
					
					$creat_array = $this->getIOCreateArrayStatus($appl_type_id,$for_status,$customer_id,$final_submit_table,$final_report_table,$each_alloc);
					if($for_status=='pending'||$for_status=='ref_back'){//for IO the pending/ref back reports will open in edit mode.
						//in edit mode
						$report_link = '../inspections/inspection_report_fetch_id/'.$firm_table_id.'/edit/'.$appl_type_id;
					}else{
						//in view mode
						$report_link = '../inspections/inspection_report_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;
					}
					$appl_edit_link = '';
					$comm_with_email = $each_alloc['level_3'];
					$io_scheduled_date = $each_alloc['io_scheduled_date'];
					$io_sched_date_comment = $each_alloc['io_sched_date_comment'];//added on 12-05-2021 by Amol
				
				}
				elseif($for_level == 'level_3'){
					/* if($sub_tab=='with_applicant'){
						$creat_array = $this->getLevel3WithApplCreateArrayStatus($i,$appl_type_id,$for_status,$customer_id,$allocation_table,$appl_current_pos_table,$final_submit_table,$final_report_table);
						$comm_with_email = 'Applicant';
					}
					elseif($sub_tab=='scrutiny'){
						$creat_array = $this->getLevel3WithMOCreateArrayStatus($appl_type_id,$for_status,$customer_id,$DmiMoRoCommentsDetails,$appl_current_pos_table,$final_submit_table);
						$comm_with_email = $each_alloc['level_1'];
					}
					elseif($sub_tab=='reports'){
						$creat_array = $this->getLevel3WithIOCreateArrayStatus($appl_type_id,$for_status,$customer_id,$final_report_table);
						$appl_view_link = '../inspections/inspection_report_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;
						$appl_edit_link = '../inspections/inspection_report_fetch_id/'.$firm_table_id.'/edit/'.$appl_type_id;
						$comm_with_email = $each_alloc['level_2'];
					}
					elseif($sub_tab=='with_sub_office'){
						$creat_array = $this->getROwithSOCreateArrayStatus($appl_type_id,$for_status,$customer_id,$ro_so_comments_table,$appl_current_pos_table);
						$appl_view_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/view/'.$appl_type_id;
						$appl_edit_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/edit/'.$appl_type_id;
						$comm_with_email = $each_alloc['level_3'];
					}
					elseif($sub_tab=='with_reg_office'){
						$creat_array = $this->getSOwithROCreateArrayStatus($appl_type_id,$for_status,$customer_id,$ro_so_comments_table,$appl_current_pos_table);
						$appl_view_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/view/'.$appl_type_id;
						$appl_edit_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/edit/'.$appl_type_id;
						$comm_with_email = $each_alloc['level_4_ro'];
					}
					else */if($sub_tab=='with_ho_office'){
						$creat_array = $this->getRoWithHoCreateArrayStatus($appl_type_id,$customer_id,$for_status,$ho_comments_table,$each_alloc);
						
						if($office_type == 'RO'){						
							$appl_view_link = '../hoinspections/fetch_record_id/'.$firm_table_id.'/view/'.$appl_type_id;
							$appl_edit_link = '../hoinspections/fetch_record_id/'.$firm_table_id.'/edit/'.$appl_type_id;
						
						}else{
							$appl_view_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/view/'.$appl_type_id;
							$appl_edit_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/edit/'.$appl_type_id;
						
						}
						$comm_with_email = $each_alloc['dy_ama'];
					}
					
				}
				elseif($for_level == 'level_4'){
					$creat_array = $this->getHoLevelCreateArrayStatus($appl_type_id,$customer_id,$for_status,$sub_tab,$list_for_field,$ho_comments_table);
					
					$appl_view_link = '../hoinspections/fetch_record_id/'.$firm_table_id.'/view/'.$appl_type_id;
					$appl_edit_link = '../hoinspections/fetch_record_id/'.$firm_table_id.'/edit/'.$appl_type_id;
					
					if($for_status == 'pending' || $for_status == 'replied'){
						$check_comm_with = 'from_user';
					}elseif($for_status == 'ref_back' || $for_status == 'approved'){
						$check_comm_with = 'to_user';
					}
					$ho_comments_table = TableRegistry::getTableLocator()->get($flow_wise_table['ho_comment_reply']);//load only for below use
					$ho_comment_status = $ho_comments_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
					
					if(empty($ho_comment_status) || $ho_comment_status[$check_comm_with]=='ro'){						
						$comm_with_email = $this->Controller->Customfunctions->getApplRegOfficeId($customer_id,$appl_type_id);
					}elseif(!empty($ho_comment_status[$check_comm_with])){
						$comm_with_email = $each_alloc[$ho_comment_status[$check_comm_with]];
					}
				}

				//get User Name from email id
				$mo_user_details = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$comm_with_email)))->first();
				if(empty(!$mo_user_details)){
					$comm_with = $mo_user_details['f_name'].' '.$mo_user_details['l_name'];
				}else{
					$comm_with='Not Allocated';
				}
			}
			
			$list_array = array();
			//creating array to list records with respect to above conditions
			if(!empty($creat_array)){
					
				$list_array['appl_type'] = $appl_type;
				$list_array['customer_id'] = $customer_id.'-'.$form_type;
				$list_array['firm_name'] = $firm_name;
				$list_array['comm_with'] = $comm_with;
				$list_array['appl_view_link'] = $appl_view_link; 
				$list_array['on_date'] = $creat_array; 
				
				if($for_level == 'level_2'){
					if($for_status=='pending'){
						$list_array['io_scheduled_date'] = $io_scheduled_date;
						$list_array['io_sched_date_comment'] = $io_sched_date_comment;//added on 12-05-2021 by Amol
					}else{
						$list_array['comm_with'] = $comm_with;
					}
					$list_array['report_link'] = $report_link;
				
				}else{
					if($for_status=='pending'||$for_status=='replied'){
						$list_array['appl_edit_link'] = $appl_edit_link;
					}else{
						$list_array['appl_edit_link'] = '';
					}
				}
			}
			
			return $list_array;
		}
		
		
		
	//fetching records form DB current user level wise and requested status box clicked(Pending, Replied etc..)
		public function fetchRecords($for_level,$for_status,$sub_tab=null){
			
			$conn = ConnectionManager::get('default');
			$applTypeArray = $this->Session->read('applTypeArray');
			
			//this cond. ia only for PAO/DDO user dashboard.
			if($for_level=='pao'){
				if($for_status=='ref_back'){
					$for_status='not_confirmed';
				}elseif($for_status=='approved'){
					$for_status='confirmed';
				}
			}else{
				//Index 1, Now Renewal application will not list except DDO dashboard, any where in list. on 20-10-2022
				unset($applTypeArray['1']);
			}
			
			$username = $this->Session->read('username');
				
			//get flow wise tables
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$applTypeArray/*$this->Session->read('applTypeArray')*/),'order'=>'id ASC'))->toArray();
		
			$i=0;
			$appl_list_array = array();
		//first loop for each application/flow type
			foreach($flow_wise_tables as $each_flow){
				
				//get flow/application type
				$DmiApplicationTypes = TableRegistry::getTableLocator()->get('DmiApplicationTypes');
				$get_appl_type = $DmiApplicationTypes->find('all',array('conditions'=>array('id IS'=>$each_flow['application_type'])))->first();
				$appl_type = $get_appl_type['application_type'];				
				$payment_details_table = TableRegistry::getTableLocator()->get($each_flow['payment']);
				$allocation_table = TableRegistry::getTableLocator()->get($each_flow['allocation']);
				$allocationTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$each_flow['allocation']))));																								 
				$ho_allocation_table = TableRegistry::getTableLocator()->get($each_flow['ho_level_allocation']);	
				$appl_current_pos_table = TableRegistry::getTableLocator()->get($each_flow['appl_current_pos']);	
				$appl_type_id = $each_flow['application_type'];
				
				$alloc_table_fields = array('id'=>'MAX(id)','customer_id','level_1','level_2','level_3','level_4_ro','level_4_mo','current_level','ro_scheduled_date','io_scheduled_date','io_sched_date_comment');
				$ho_alloc_table_fields = array('id','customer_id','ho_mo_smo','dy_ama','jt_ama','ama','current_level');

				//get rejected appl cond.
				$rej_appl_cond = $this->Randomfunctions->get_rej_cond($each_flow);
				//get rejected appl array.
				$rej_appl_array = $this->Randomfunctions->get_rejected_appl($each_flow);
				
				
				//initialize common variable with null value on 09-02-2021 by Amol
				$customer_id = null;
				$form_type = null;
				$firm_details = null;
				$firm_name = null;
				$firm_table_id = null;
				$comm_with = null;
				$appl_edit_link = null;
				$grantCertificate = null;
				$comm_with_email = null;
				
				//for payment verification dashboard
				if($for_level == 'pao'){

					$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
					$DmiPaoDetails = TableRegistry::getTableLocator()->get('DmiPaoDetails');
					$DmiDistricts = TableRegistry::getTableLocator()->get('DmiDistricts');
					
					$user_id = $DmiUsers->find('all', array('fields'=>'id', 'conditions' => array('email IS'=>$username)))->first();
					$pao_id = $DmiPaoDetails->find('all', array('fields'=>'id', 'conditions' => array('pao_user_id IS'=>$user_id['id'])))->first();
					
					// Find aaplication list District wise (Updated Date : 02/05/2018 Pravin)
					$district_id_list = $DmiDistricts->find('list', array('valueField'=>'id', 'conditions' => array('pao_id IS'=>$pao_id['id'])))->toList();
					
					$check_payment_submitted_listxy = array();
					if(!empty($district_id_list)){
						$check_payment_submitted_listxy = $payment_details_table->find('all', array('fields'=>array('customer_id'),'conditions' => array('district_id IN'=>$district_id_list),'group'=>array('customer_id')))->toArray();	
					}
					$list_customer_id = array();			
					if(!empty($check_payment_submitted_listxy)){				
						foreach($check_payment_submitted_listxy as $customer_id){			
											
							$check_payment_submitted_listxxxxx = $payment_details_table->find('all', array('fields'=>array('id'),'conditions' => array('customer_id IS'=>$customer_id['customer_id']),'order'=>array('id desc')))->first();
							$list_customer_id[] = $check_payment_submitted_listxxxxx['id'];	
						}				
					}
					
					$check_payment_submitted_list = array();
					if(!empty($list_customer_id)){
						$check_payment_submitted_list = $payment_details_table->find('all', array('fields'=>array('id','customer_id','modified'),'conditions' => array($rej_appl_cond,'id IN'=>$list_customer_id,'payment_confirmation IS'=>$for_status)))->toArray();
					}
					
					foreach($check_payment_submitted_list as $customer_is_list){
						
						$list_array = $this->getCommonListingVariables($i,$customer_is_list,$for_level,$for_status,$appl_type_id,$appl_type,null,null);
					
						if(!empty($list_array)){
							$appl_list_array[$i]['appl_type'] = $list_array['appl_type'];
							$appl_list_array[$i]['customer_id'] = $list_array['customer_id'];
							$appl_list_array[$i]['firm_name'] = $list_array['firm_name'];
							$appl_list_array[$i]['appl_view_link'] = $list_array['appl_view_link'];
							$appl_list_array[$i]['comm_with'] = $list_array['comm_with'];
							$appl_list_array[$i]['appl_edit_link'] = $list_array['appl_edit_link'];
							$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$customer_is_list['modified']));
						}

						$i=$i+1;
					}


				//for level 1 (MO/SMO dashbaord)
				}elseif($for_level == 'level_1'){
					
					//Applications for scrutiny with Nodal offcer					
					if($sub_tab=='scrutiny_with_nodal_office'){
							
						$resultIds = $allocation_table->find('list',array('fields'=>array('customer_id','id'=>'max(id)'),'order'=>array('id DESC'),'group'=>'customer_id'))->toArray();	
						if(!empty($resultIds)){
							$get_allocations = $allocation_table->find('all',array('conditions' => array($rej_appl_cond,'id IN'=>$resultIds, 'level_1 = current_level','level_1 IS'=>$username)))->toArray();
						}else{ $get_allocations = array(); }		  
						foreach($get_allocations as $each_alloc){	

							$list_array = $this->getCommonListingVariables($i,$each_alloc,$for_level,$for_status,$appl_type_id,$appl_type,null,$sub_tab);
						
							if(!empty($list_array)){
								$appl_list_array[$i]['appl_type'] = $list_array['appl_type'];
								$appl_list_array[$i]['customer_id'] = $list_array['customer_id'];
								$appl_list_array[$i]['firm_name'] = $list_array['firm_name'];
								$appl_list_array[$i]['appl_view_link'] = $list_array['appl_view_link'];
								$appl_list_array[$i]['comm_with'] = $list_array['comm_with'];
								$appl_list_array[$i]['appl_edit_link'] = $list_array['appl_edit_link'];
								$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$list_array['on_date']));
							}

						$i=$i+1;
						}
					
					//Applications for scrutiny with Reg. Office
					}elseif($sub_tab=='scrutiny_with_reg_office'){
						
						$resultIds = $allocation_table->find('list',array('fields'=>array('customer_id','id'=>'max(id)'),'order'=>array('id DESC'),'group'=>'customer_id'))->toArray();	
						if(!empty($resultIds)){
							$get_allocations = $allocation_table->find('all', array('conditions' => array($rej_appl_cond,'id IN'=>$resultIds, 'level_4_ro IS NOT'=>null,'level_4_mo IS'=>$username)))->toArray();
						}else{ $get_allocations = array(); }			  
						
						foreach($get_allocations as $each_alloc){
							
							$list_array = $this->getCommonListingVariables($i,$each_alloc,$for_level,$for_status,$appl_type_id,$appl_type,null,$sub_tab);
						
							if(!empty($list_array)){
								$appl_list_array[$i]['appl_type'] = $list_array['appl_type'];
								$appl_list_array[$i]['customer_id'] = $list_array['customer_id'];
								$appl_list_array[$i]['firm_name'] = $list_array['firm_name'];
								$appl_list_array[$i]['appl_view_link'] = $list_array['appl_view_link'];
								$appl_list_array[$i]['comm_with'] = $list_array['comm_with'];
								$appl_list_array[$i]['appl_edit_link'] = $list_array['appl_edit_link'];
								$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$list_array['on_date']));
							}
							
						$i=$i+1;								
						}
						
						
					}elseif($sub_tab=='scrutiny_with_ho_office'){
						
						$resultIds = $ho_allocation_table->find('list',array('fields'=>array('customer_id','id'=>'max(id)'),'order'=>array('id DESC'),'group'=>'customer_id'))->toArray();	
						
						if(!empty($resultIds)){
							$get_allocations = $ho_allocation_table->find('all', array('conditions' => array($rej_appl_cond,'id IN'=>$resultIds,'dy_ama IS NOT'=>null,'ho_mo_smo IS'=>$username,'current_level IS NOT'=>null)))->toArray();
						}else{ $get_allocations = array(); }				  
						
						foreach($get_allocations as $each_alloc){
							
							$list_array = $this->getCommonListingVariables($i,$each_alloc,$for_level,$for_status,$appl_type_id,$appl_type,null,$sub_tab);
						
							if(!empty($list_array)){
								$appl_list_array[$i]['appl_type'] = $list_array['appl_type'];
								$appl_list_array[$i]['customer_id'] = $list_array['customer_id'];
								$appl_list_array[$i]['firm_name'] = $list_array['firm_name'];
								$appl_list_array[$i]['appl_view_link'] = $list_array['appl_view_link'];
								$appl_list_array[$i]['comm_with'] = $list_array['comm_with'];
								$appl_list_array[$i]['appl_edit_link'] = $list_array['appl_edit_link'];
								$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$list_array['on_date']));
							}
							
						$i=$i+1;								
						}	
					}
				
				}
				//for level 2 (IO dashbaord)
				elseif($for_level == 'level_2'){
					
					if($for_status == 'pending'){	
					
						$resultIds = $allocation_table->find('list',array('fields'=>array('customer_id','id'=>'max(id)'),'order'=>array('id DESC'),'group'=>'customer_id'))->toArray();
						if(!empty($resultIds)){
							$get_allocations = $allocation_table->find('all',array('conditions'=>array($rej_appl_cond,'id IN'=>$resultIds, 'level_3 IS NOT'=>null, 
																	'level_2 IS'=>$username,'current_level = level_2')))->toArray();				
						}else{ $get_allocations = array(); }
						//$conditions = "al.level_2='$username' and al.level_3 IS NOT NULL and al.current_level = al.level_2";
					}else{		
						
						$resultIds = $allocation_table->find('list',array('fields'=>array('customer_id','id'=>'max(id)'),'order'=>array('id DESC'),'group'=>'customer_id'))->toArray();
						if(!empty($resultIds)){
							$get_allocations = $allocation_table->find('all',array('conditions'=>array($rej_appl_cond,'id IN'=>$resultIds, 'level_3 IS NOT'=>null, 
																	'level_2 IS'=>$username)))->toArray();
						}else{ $get_allocations = array(); }	
						//$conditions = "al.level_2='$username' and al.level_3 IS NOT NULL";
					}
					
					//Second loop to check application/flow wise allocations	
					foreach($get_allocations as $each_alloc){
						
						$list_array = $this->getCommonListingVariables($i,$each_alloc,$for_level,$for_status,$appl_type_id,$appl_type,null,null);
							
						if(!empty($list_array)){
							$appl_list_array[$i]['appl_type'] = $list_array['appl_type'];
							$appl_list_array[$i]['customer_id'] = $list_array['customer_id'];
							$appl_list_array[$i]['firm_name'] = $list_array['firm_name'];
							$appl_list_array[$i]['appl_view_link'] = $list_array['appl_view_link'];
							//$appl_list_array[$i]['appl_edit_link'] = $list_array['appl_edit_link'];							
							if($for_status=='pending'){
								$appl_list_array[$i]['io_scheduled_date'] = $list_array['io_scheduled_date'];
								$appl_list_array[$i]['io_sched_date_comment'] = $list_array['io_sched_date_comment'];//added on 12-05-2021 by Amol
							}else{
								$appl_list_array[$i]['comm_with'] = $list_array['comm_with'];
							}
							$appl_list_array[$i]['report_link'] = $list_array['report_link'];
							$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$list_array['on_date']));
						}

					$i=$i+1;
					}
					
				}
				//for level 3 (RO/SO dashbaord)
				elseif($for_level == 'level_3'){
					
					$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
					$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
					$DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');		
					$DmiAuthFirmRegistrations = TableRegistry::getTableLocator()->get('DmiAuthFirmRegistrations');
					$DmiRejectedApplLogs = TableRegistry::getTableLocator()->get('DmiRejectedApplLogs');	

					if($for_status == 'rejected'){
					/* //commented below code on 07-09-2022 for rejected option, now available on left menu																		
						
						//get last rejected records from each appl type from reject log table
						$resultIds = $DmiRejectedApplLogs->find('list',array('fields'=>array('customer_id','id'=>'max(id)'),
															'conditions'=>array('by_user IS'=>$username,'appl_type IS'=>$appl_type_id),
															'order'=>array('id DESC'),'group'=>'customer_id'))->toArray();
						$get_rejected = array();
						if(!empty($resultIds)){
							$get_rejected = $DmiRejectedApplLogs->find('all', array('conditions' => array('id IN'=>$resultIds)))->toArray();
						}
						
						foreach($get_rejected as $each){	
								
							$customer_id = $each['customer_id'];
							$form_type = $this->Controller->Customfunctions->checkApplicantFormType($customer_id);
							//get firm details
							$firm_details = $DmiFirms->firmDetails($customer_id);
							$firm_name = $firm_details['firm_name'];					
							$firm_table_id = $firm_details['id'];
					
							$appl_view_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;
							$appl_edit_link = '';
							$comm_with_email = 'Applicant';
							
							$comm_with='Applicant';

							$appl_list_array[$i]['appl_type'] = $appl_type;
							$appl_list_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
							$appl_list_array[$i]['firm_name'] = $firm_name;
							$appl_list_array[$i]['appl_view_link'] = $appl_view_link;
							$appl_list_array[$i]['comm_with'] = $comm_with;
							$appl_list_array[$i]['appl_edit_link'] = $appl_edit_link;
							$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$each['created']));

							
						$i=$i+1;
						}
						*/
					}else{
					
						//applications from/to applicant
						if($sub_tab=='with_applicant'){
							
							$result = $this->getLevel3WithApplCreateArrayStatus($appl_type_id,$for_status);	
							
							foreach($result as $each_alloc){

								//check application not in rejected array
								if(!in_array($each_alloc['customer_id'],$rej_appl_array)){
								
									$customer_id = $each_alloc['customer_id'];
									$form_type = $this->Controller->Customfunctions->checkApplicantFormType($customer_id);
									//get firm details
									$firm_details = $DmiFirms->firmDetails($customer_id);
									$firm_name = $firm_details['firm_name'];					
									$firm_table_id = $firm_details['id'];
							
									$appl_view_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;
									$appl_edit_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/edit/'.$appl_type_id;
									$comm_with_email = 'Applicant';
									
									$comm_with='Applicant';
									
																
									if($for_status=='pending'||$for_status=='replied'){
										$appl_edit_link = $appl_edit_link;
									}else{
										$appl_edit_link = '';
									}
									
									//checked is firm registed by dmi authorised person.
									$authRegFirm = $DmiAuthFirmRegistrations->find('all',array('conditions'=>array('firm_id IS'=>$customer_id)))->first();
									if(!empty($authRegFirm) && $for_status=='pending' && $appl_type_id == 1){
										$appl_edit_link = '../application/fill_form_fetch_id/'.$firm_table_id.'/'.$appl_type_id.'/edit/authregfirm';
									}
									
									// Get certificate pdfs, Pravin Bhakare, 03-07-2020
									$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id,$appl_type_id);
									if($appl_type_id == 2 && $office_type=='SO'){
										$grantCertificate = $DmiGrantCertificatesPdfs->getcertificate($customer_id);
									}else{
										$grantCertificate = null;
									}
									
									
									//$list_array = $this->getCommonListingVariables($i,$each_alloc,$for_level,$for_status,$appl_type_id,$appl_type,null,$sub_tab);
								
									//if(!empty($list_array)){
										$appl_list_array[$i]['appl_type'] = $appl_type;
										$appl_list_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
										$appl_list_array[$i]['firm_name'] = $firm_name;
										$appl_list_array[$i]['appl_view_link'] = $appl_view_link;
										$appl_list_array[$i]['comm_with'] = $comm_with;
										$appl_list_array[$i]['appl_edit_link'] = $appl_edit_link;
										$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$each_alloc['tradate']));
										$appl_list_array[$i]['grant_certificate'] = $grantCertificate;
									//}
								}
								
							$i=$i+1;
							}
						
						}
						//applications from/to Scrutiny officer (MO/SMO)
						elseif($sub_tab=='scrutiny'){
						
							$result = $this->getLevel3WithMOCreateArrayStatus($appl_type_id,$for_status);
						
							foreach($result as $each_alloc){
								
								//check application not in rejected array
								if(!in_array($each_alloc['customer_id'],$rej_appl_array)){
								
									$customer_id = $each_alloc['customer_id'];
									$form_type = $this->Controller->Customfunctions->checkApplicantFormType($customer_id);
									//get firm details
									$firm_details = $DmiFirms->firmDetails($customer_id);
									$firm_name = $firm_details['firm_name'];					
									$firm_table_id = $firm_details['id'];
							
									$appl_view_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;
									$appl_edit_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/edit/'.$appl_type_id;
									$comm_with_email = $each_alloc['level_1'];
									
									$mo_user_details = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$comm_with_email)))->first();
									if(empty(!$mo_user_details)){
										$comm_with = $mo_user_details['f_name'].' '.$mo_user_details['l_name'];
									}else{
										$comm_with='Not Allocated';
									}
																
									if($for_status=='pending'||$for_status=='replied'){
										$appl_edit_link = $appl_edit_link;
									}else{
										$appl_edit_link = '';
									}
									
									// Get certificate pdfs, Pravin Bhakare, 03-07-2020
									$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id,$appl_type_id);
									if($appl_type_id == 2 && $office_type=='SO'){
										$grantCertificate = $DmiGrantCertificatesPdfs->getcertificate($customer_id);
									}else{
										$grantCertificate = null;
									}
									
									//if(!empty($list_array)){
										$appl_list_array[$i]['appl_type'] = $appl_type;
										$appl_list_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
										$appl_list_array[$i]['firm_name'] = $firm_name;
										$appl_list_array[$i]['appl_view_link'] = $appl_view_link;
										$appl_list_array[$i]['comm_with'] = $comm_with;
										$appl_list_array[$i]['appl_edit_link'] = $appl_edit_link;
										$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$each_alloc['tradate']));
										$appl_list_array[$i]['grant_certificate'] = $grantCertificate;
									//}
								}
								
							$i=$i+1;
							}
		
						}
						//Reports from/to Site Inspection officer (IO)
						elseif($sub_tab=='reports'){
							
							$result = $this->getLevel3WithIOCreateArrayStatus($appl_type_id,$for_status);
							
							foreach($result as $each_alloc){
								
								//check application not in rejected array
								if(!in_array($each_alloc['customer_id'],$rej_appl_array)){
								
									$customer_id = $each_alloc['customer_id'];
									$form_type = $this->Controller->Customfunctions->checkApplicantFormType($customer_id);
									//get firm details
									$firm_details = $DmiFirms->firmDetails($customer_id);
									$firm_name = $firm_details['firm_name'];					
									$firm_table_id = $firm_details['id'];
									
									$appl_view_link = '../inspections/inspection_report_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;
									$appl_edit_link = '../inspections/inspection_report_fetch_id/'.$firm_table_id.'/edit/'.$appl_type_id;
									$comm_with_email = $each_alloc['level_2'];
								
									if($for_status=='pending'||$for_status=='replied'){
										$appl_edit_link = $appl_edit_link;
									}else{
										$appl_edit_link = '';
									}
									
									$mo_user_details = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$comm_with_email)))->first();
									if(empty(!$mo_user_details)){
										$comm_with = $mo_user_details['f_name'].' '.$mo_user_details['l_name'];
									}else{
																					   
										$comm_with='Not Allocated';
																					   
									}
									
									// Get certificate pdfs, Pravin Bhakare, 03-07-2020
									$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id,$appl_type_id);
									if($appl_type_id == 2 && $office_type=='SO'){
										$grantCertificate = $DmiGrantCertificatesPdfs->getcertificate($customer_id);
									}else{
										$grantCertificate = null;
									}
									
									//$list_array = $this->getCommonListingVariables($i,$each_alloc,$for_level,$for_status,$appl_type_id,$appl_type,null,$sub_tab);
								
									//if(!empty($list_array)){
										$appl_list_array[$i]['appl_type'] = $appl_type;
										$appl_list_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
										$appl_list_array[$i]['firm_name'] = $firm_name;
										$appl_list_array[$i]['appl_view_link'] = $appl_view_link;
										$appl_list_array[$i]['comm_with'] = $comm_with;
										$appl_list_array[$i]['appl_edit_link'] = $appl_edit_link;
										$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$each_alloc['tradate']));
										$appl_list_array[$i]['grant_certificate'] = $grantCertificate;
									//}
								}
								
							$i=$i+1;
								
							}

						}//Communication with Sub office from Reg. office
						elseif($sub_tab=='with_sub_office'){
							
							$result = $this->getROwithSOCreateArrayStatus($appl_type_id,$for_status,$each_flow['allocation'],$each_flow['application_form'],$each_flow['ho_comment_reply'],
															$each_flow['ro_so_comments'],$each_flow['appl_current_pos']);
															
							foreach($result as $each_alloc){
								
								//check application not in rejected array
								if(!in_array($each_alloc['customer_id'],$rej_appl_array)){
									$customer_id = $each_alloc['customer_id'];
									$form_type = $this->Controller->Customfunctions->checkApplicantFormType($customer_id);
									//get firm details
									$firm_details = $DmiFirms->firmDetails($customer_id);
									$firm_name = $firm_details['firm_name'];					
									$firm_table_id = $firm_details['id'];
									
									$appl_view_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/view/'.$appl_type_id;
									$appl_edit_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/edit/'.$appl_type_id;
									$comm_with_email = $each_alloc['level_3'];
								
									if($for_status=='pending'||$for_status=='replied'){
										$appl_edit_link = $appl_edit_link;
									}else{
										$appl_edit_link = '';
									}
									
									$mo_user_details = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$comm_with_email)))->first();
									if(empty(!$mo_user_details)){
										$comm_with = $mo_user_details['f_name'].' '.$mo_user_details['l_name'];
									}else{
																					   
										$comm_with='Not Allocated';
																					   
									}
									
									// Get certificate pdfs, Pravin Bhakare, 03-07-2020
									$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id,$appl_type_id);
									if($appl_type_id == 2 && $office_type=='SO'){
										$grantCertificate = $DmiGrantCertificatesPdfs->getcertificate($customer_id);
									}else{
										$grantCertificate = null;
									}
									
									//$list_array = $this->getCommonListingVariables($i,$each_alloc,$for_level,$for_status,$appl_type_id,$appl_type,null,$sub_tab);
								
									//if(!empty($list_array)){
										$appl_list_array[$i]['appl_type'] = $appl_type;
										$appl_list_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
										$appl_list_array[$i]['firm_name'] = $firm_name;
										$appl_list_array[$i]['appl_view_link'] = $appl_view_link;
										$appl_list_array[$i]['comm_with'] = $comm_with;
										$appl_list_array[$i]['appl_edit_link'] = $appl_edit_link;
										$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$each_alloc['tradate']));
										$appl_list_array[$i]['grant_certificate'] = $grantCertificate;
									//}
								}
								
							$i=$i+1;								
							}

						}//communication with Reg. Office from Sun Office
						elseif($sub_tab=='with_reg_office'){
							
							$result = $this->getSOwithROCreateArrayStatus($appl_type_id,$for_status);
															
							foreach($result as $each_alloc){
								
								//check application not in rejected array
								if(!in_array($each_alloc['customer_id'],$rej_appl_array)){
								
									$customer_id = $each_alloc['customer_id'];
									$form_type = $this->Controller->Customfunctions->checkApplicantFormType($customer_id,$appl_type_id);
									$office_type = $this->Controller->Customfunctions->getApplDistrictOffice($customer_id,$appl_type_id);
									$firm_type = $this->Controller->Customfunctions->firmType($customer_id);
									
									//get firm details
									$firm_details = $DmiFirms->firmDetails($customer_id);
									$firm_name = $firm_details['firm_name'];					
									$firm_table_id = $firm_details['id'];
									
									$appl_view_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/view/'.$appl_type_id;
									$appl_edit_link = '../rosocomments/fetch_record_id/'.$firm_table_id.'/edit/'.$appl_type_id;
									$comm_with_email = $each_alloc['level_4_ro'];
									
									if($for_status=='pending'||$for_status=='replied'){
										$appl_edit_link = $appl_edit_link;
									}else{
										$appl_edit_link = '';
									}
									
									$mo_user_details = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$comm_with_email)))->first();
									if(empty(!$mo_user_details)){
										$comm_with = $mo_user_details['f_name'].' '.$mo_user_details['l_name'];
									}else{
										$comm_with='Not Allocated';
									}		
									
									// Get certificate pdfs, Pravin Bhakare, 03-07-2020									
									if($appl_type_id == 2 && $office_type=='SO'){
										$grantCertificate = $DmiGrantCertificatesPdfs->getcertificate($customer_id);
									}else{
										$grantCertificate = null;
									}
									
									
									$DmiCommonSiteinspectionFlowDetails = TableRegistry::getTableLocator()->get('DmiCommonSiteinspectionFlowDetails');
									$allSectionDetails = $DmiCommonSiteinspectionFlowDetails->allSectionList($appl_type_id,$office_type,$firm_type,$form_type);
									$all_report_status = $DmiCommonSiteinspectionFlowDetails->reportSectionApproveStatus($customer_id,$allSectionDetails);
									if(empty($each_alloc['level_2'])){
										$all_report_status = 'true';
									}									
									if($all_report_status == 'true'){
										
										$appl_list_array[$i]['appl_type'] = $appl_type;
										$appl_list_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
										$appl_list_array[$i]['firm_name'] = $firm_name;
										$appl_list_array[$i]['appl_view_link'] = $appl_view_link;
										$appl_list_array[$i]['comm_with'] = $comm_with;
										$appl_list_array[$i]['appl_edit_link'] = $appl_edit_link;
										$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$each_alloc['tradate']));
										$appl_list_array[$i]['grant_certificate'] = $grantCertificate;
									}
								}
								
							$i=$i+1;								
							}
				
						}//communication with HO office from Reg. Office
						elseif($sub_tab=='with_ho_office'){
							
							$resultIds = $ho_allocation_table->find('list',array('fields'=>array('customer_id','id'=>'max(id)'),'order'=>array('id DESC'),'group'=>'customer_id'))->toArray();
							if(!empty($resultIds)){
								$get_allocations = $ho_allocation_table->find('all', array('conditions' => array($rej_appl_cond,'id IN'=>$resultIds,'dy_ama IS NOT'=>null)))->toArray();
							}else{ $get_allocations = array(); }
							
							foreach($get_allocations as $each_alloc){
									
								$list_array = $this->getCommonListingVariables($i,$each_alloc,$for_level,$for_status,$appl_type_id,$appl_type,null,$sub_tab);
						
								if(!empty($list_array)){
									$appl_list_array[$i]['appl_type'] = $list_array['appl_type'];
									$appl_list_array[$i]['customer_id'] = $list_array['customer_id'];
									$appl_list_array[$i]['firm_name'] = $list_array['firm_name'];
									$appl_list_array[$i]['appl_view_link'] = $list_array['appl_view_link'];
									$appl_list_array[$i]['comm_with'] = $list_array['comm_with'];
									$appl_list_array[$i]['appl_edit_link'] = $list_array['appl_edit_link'];
									$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$list_array['on_date']));
								}
								
								$i=$i+1;										
							}
														
						}
					
					
					}//else closed
					
				}
				//for level 4 (HO dashbaord)
				elseif($for_level == 'level_4'){
					
					if($sub_tab=='for_ho_scrutiny'){ $list_for_field = 'ho_mo_smo'; }
					elseif($sub_tab=='for_dy_ama'){ $list_for_field = 'dy_ama'; }
					elseif($sub_tab=='for_jt_ama'){ $list_for_field = 'jt_ama'; }
					elseif($sub_tab=='for_ama'){ $list_for_field = 'ama'; }			
					
					$resultIds = $ho_allocation_table->find('list',array('fields'=>array('customer_id','id'=>'max(id)'),'order'=>array('id DESC'),'group'=>'customer_id'))->toArray();
					if(!empty($resultIds)){
						$get_allocations = $ho_allocation_table->find('all', array('conditions' => array($rej_appl_cond,'id IN'=>$resultIds,$list_for_field=>$username)))->toArray();
					}else{ $get_allocations = array(); }
					
					foreach($get_allocations as $each_alloc){
						
						$list_array = $this->getCommonListingVariables($i,$each_alloc,$for_level,$for_status,$appl_type_id,$appl_type,$list_for_field,$sub_tab);
						
						if(!empty($list_array)){
							$appl_list_array[$i]['appl_type'] = $list_array['appl_type'];
							$appl_list_array[$i]['customer_id'] = $list_array['customer_id'];
							$appl_list_array[$i]['firm_name'] = $list_array['firm_name'];
							$appl_list_array[$i]['appl_view_link'] = $list_array['appl_view_link'];
							$appl_list_array[$i]['comm_with'] = $list_array['comm_with'];
							$appl_list_array[$i]['appl_edit_link'] = $list_array['appl_edit_link'];
							$appl_list_array[$i]['on_date'] = strtotime( str_replace('/', '-',$list_array['on_date']));
						}
							
					$i=$i+1;	
					}
						
				}

				//applied condition to check if appl is for backlog data entry, then change text 'New' to 'Old Appl'
				//added on 23-09-2021 by Amol
				if($appl_type_id==1){

					foreach($appl_list_array as $key => $each){
						$cId = explode('-',$each['customer_id']);
						$checkIfOld = $this->Controller->Customfunctions->isOldApplication($cId[0],$appl_type_id);
						if($checkIfOld=='yes'){
							$appl_list_array[$key]['appl_type']='Old Appl';
						}
					}

				}
				
				
			}

			
			
			
			$appl_sort_list_array = Hash::sort($appl_list_array, '{n}.on_date', 'desc');
			
			return $appl_sort_list_array;
				
		}
		
		
		
	}
	
	
	
?>