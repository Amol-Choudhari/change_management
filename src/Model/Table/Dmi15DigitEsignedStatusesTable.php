<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\CustomersController;
	
	class Dmi15DigitEsignedStatusesTable extends Table{
	
	var $name = "Dmi15DigitEsignedStatuses";
	
	public function getEsignedStatus($customer_id,$current_level){
		
		$CustomersController = new CustomersController;
		$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
		
		//create other model objects
		$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');	
		
		$application_final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'application_form');
		$Dmi_final_submit = TableRegistry::getTableLocator()->get($application_final_submit_table);
		
		$inspection_report_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'inspection_report');
		$Dmi_siteinspection_final_report = TableRegistry::getTableLocator()->get($inspection_report_table);
				
		//check application type new/old 
		$get_type = $Dmi_firm->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		if($get_type['is_already_granted']=='yes'){			
			$type = 'old';
		}else{
			$type = 'new';
		}
		
		$status = null;
		if($current_level == 'applicant'){
			//get application status from final submit table
			$get_ids = $Dmi_final_submit->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
			if(!empty($get_ids)){
				$get_status = $Dmi_final_submit->find('all',array('conditions'=>array('id'=>max($get_ids))))->first();
				$status = $get_status['status'];
			}else{
				$status = 'pending';
			}
			
		}elseif($current_level == 'level_2'){
			//get application status from final submit table
			$get_ids = $Dmi_siteinspection_final_report->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
			if(!empty($get_ids)){
				$get_status = $Dmi_siteinspection_final_report->find('all',array('conditions'=>array('id'=>max($get_ids))))->first();
				$status = $get_status['status'];
			}else{
				$status = 'pending';
			}
			
		}
		
		//taking condition for query conditionally
		if($current_level == 'applicant' || $current_level == 'level_2'){
			$query_conditions = array('customer_id'=>$customer_id, 'application_type'=>$type, 'application_status'=>$status ,$grantDateCondition);
		}else{
			$query_conditions = array('customer_id'=>$customer_id, 'application_type'=>$type, $grantDateCondition);
		}
		
		$get_esign_details = $this->find('all',array('conditions'=>$query_conditions))->first();		
		$esign_status = 'no';
		
		if(!empty($get_esign_details)){
		
			if($current_level == 'applicant'){
				
				if($get_esign_details['application_esigned']=='yes'){
					
					$esign_status = 'yes';
				}
			}
			elseif($current_level == 'level_2'){
				
				if($get_esign_details['report_esigned']=='yes'){
					
					$esign_status = 'yes';
				}
			}
			elseif($current_level == 'level_3' || $current_level == 'level_4'){
				
				if($get_esign_details['certificate_esigned']=='yes'){
					
					$esign_status = 'yes';
				}
			}
		
		}
		
		return $esign_status;
	}
	

	public function saveEsignStatus(){
		
		if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
			$customer_id = $_SESSION['customer_id'];
		}else{
			$customer_id = $_SESSION['username'];
		}
		
		$CustomersController = new CustomersController;
		$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
		
		$current_level = $_SESSION['current_level'];
		
		$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
		
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');	
		
		$application_final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'application_form');
		$Dmi_final_submit = TableRegistry::getTableLocator()->get($application_final_submit_table);
		
		$inspection_report_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'inspection_report');
		$Dmi_siteinspection_final_report = TableRegistry::getTableLocator()->get($inspection_report_table);
		$application_status = 'pending';
		
		
		//check application type old/new
		$get_type = $Dmi_firm->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		if($get_type['is_already_granted']=='yes'){			
			$application_type = 'old';
		}else{
			$application_type = 'new';
		}
				
		//check record in esign status table
		$check_esigned_record = $this->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();
		
		//if record exist then update that record
		if(!empty($check_esigned_record)){
			
			$application_status = null;
			if($current_level == 'applicant'){
				//get application status from final submit table
				$get_ids = $Dmi_final_submit->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
				if(!empty($get_ids)){
					$get_status = $Dmi_final_submit->find('all',array('conditions'=>array('id'=>max($get_ids))))->first();
					$application_status = $get_status['status'];
				}else{
					$application_status = 'pending';
				}
				
				$esiging_level = 'application_esigned';
				
			}elseif($current_level == 'level_2'){
				//get application status from final submit table
				$get_ids = $Dmi_siteinspection_final_report->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
				if(!empty($get_ids)){
					$get_status = $Dmi_siteinspection_final_report->find('all',array('conditions'=>array('id'=>max($get_ids))))->first();
					$application_status = $get_status['status'];
				}else{
					$application_status = 'pending';
				}
				
				$esiging_level = 'report_esigned';
			}
			elseif($current_level == 'level_3' || $current_level == 'level_4'){
				
				$esiging_level = 'certificate_esigned';
				$application_status = 'Granted';
			}
			
			$newEntity = $this->newEntity(array(		
				'id'=>$check_esigned_record['id'],
				$esiging_level=>'yes',
				'application_status'=>$application_status,
				'modified'=>date('Y-m-d H:i:s'),			
			));
			
			if($this->save($newEntity)){
				return true;
			}
		}
		else{//else create new entry in table

		
		//start - below logic added on 21-04-2018 by Amol, after applied optional esign for applicant
			if($current_level == 'applicant'){
				
				$esiging_level = 'application_esigned';
				$application_status = 'pending';
				
			}elseif($current_level == 'level_2'){
				
				//get application status from final submit table
				$get_ids = $Dmi_siteinspection_final_report->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
				if(!empty($get_ids)){
					$get_status = $Dmi_siteinspection_final_report->find('all',array('conditions'=>array('id'=>max($get_ids))))->first();
					$application_status = $get_status['status'];
				}else{
					$application_status = 'pending';
				}
				
				$esiging_level = 'report_esigned';
				
			}elseif($current_level == 'level_3' || $current_level == 'level_4'){
				
				$esiging_level = 'certificate_esigned';
				$application_status = 'Granted';
			}
		//end - above logic added on 21-04-2018 by Amol, after applied optional esign for applicant
		
			$newEntity = $this->newEntity(array(		
				'customer_id'=>$customer_id,
				$esiging_level=>'yes',
				'application_type'=>$application_type,
				'application_status'=>$application_status,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s'),
			));
			
			if($this->save($newEntity)){
				return true;
			}
			
		}
		
		
		
	}
		
}

?>