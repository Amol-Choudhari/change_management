<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

class DmiRenewalEsignedStatusesTable extends Table{
	
	var $name = "DmiRenewalEsignedStatuses"; 
	
	
	public function getEsignedStatus($customer_id,$current_level){
		
		//create other model objects		
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		
		$CustomersController = new CustomersController;		
		$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
		
		$application_final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'application_form');
		$Dmi_renewal_final_submit = TableRegistry::getTableLocator()->get($application_final_submit_table);
		
		$inspection_report_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'inspection_report');
		$Dmi_siteinspection_final_report = TableRegistry::getTableLocator()->get($inspection_report_table);
		
		$status = null;
		if($current_level == 'applicant'){
			//get application status from final submit table
			$get_ids = $Dmi_renewal_final_submit->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
			if(!empty($get_ids)){
				$get_status = $Dmi_renewal_final_submit->find('all',array('conditions'=>array('id'=>max($get_ids))))->first();
				$status = $get_status['status'];
			}else{
				$status = 'pending';
			}
			
		}elseif($current_level == 'level_2'){
			//get application status from final submit table
		/*	$get_ids = $Dmi_siteinspection_final_report->find('list',array('conditions'=>array('customer_id'=>$customer_id)));
			if(!empty($get_ids)){
				$get_status = $Dmi_siteinspection_final_report->find('first',array('conditions'=>array('id'=>max($get_ids))));
				$status = $get_status['Dmi_siteinspection_final_report']['status'];
			}else{
				$status = 'pending';
			}
			*/
		}
		
		//taking condition for query conditionally
		if($current_level == 'applicant'){
			$query_conditions = array('customer_id'=>$customer_id, $grantDateCondition, 'application_status'=>$status);
		}else{
			$query_conditions = array('customer_id'=>$customer_id, $grantDateCondition );
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
		$current_level = $_SESSION['current_level'];
		
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');	
		
		$application_final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'application_form');
		$Dmi_renewal_final_submit = TableRegistry::getTableLocator()->get($application_final_submit_table);
		
		$inspection_report_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($_SESSION['application_type'],'inspection_report');
		$Dmi_siteinspection_final_report = TableRegistry::getTableLocator()->get($inspection_report_table);
		$application_status = 'pending';
			
		$CustomersController = new CustomersController;
		$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
		
		//check record in esign status table
		$check_esigned_record = $this->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();
		
		//if record exist then update that record
		if(!empty($check_esigned_record)){
			
			$application_status = null;
			if($current_level == 'applicant'){
				//get application status from final submit table
				$get_ids = $Dmi_renewal_final_submit->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
				if(!empty($get_ids)){
					$get_status = $Dmi_renewal_final_submit->find('all',array('conditions'=>array('id'=>max($get_ids))))->first();
					$application_status = $get_status['status'];
				}else{
					$application_status = 'pending';
				}
				
				$esiging_level = 'application_esigned';
				
			}elseif($current_level == 'level_2'){
				//get application status from final submit table
			/*	$get_ids = $Dmi_siteinspection_final_report->find('list',array('conditions'=>array('customer_id'=>$customer_id)));
				if(!empty($get_ids)){
					$get_status = $Dmi_siteinspection_final_report->find('first',array('conditions'=>array('id'=>max($get_ids))));
					$application_status = $get_status['Dmi_siteinspection_final_report']['status'];
				}else{
					$application_status = 'pending';
				}
				*/
				$application_status = 'pending';
				$esiging_level = 'report_esigned';
			}
			elseif($current_level == 'level_3' || $current_level == 'level_4'){
				
				$esiging_level = 'certificate_esigned';
				$application_status = 'Granted';
			}
			
			$entity = $this->newEntity(array(		
				'id'=>$check_esigned_record['id'],
				$esiging_level=>'yes',
				'application_status'=>$application_status,
				'modified'=>date('Y-m-d H:i:s'),
			
			));
			
			if($this->save($entity)){

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
		
			$entity = $this->newEntity(array(		
				'customer_id'=>$customer_id,
				$esiging_level=>'yes',
				'application_status'=>$application_status,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s')	
			));
			
			if($this->save($entity)){
				return true;
			}
			
		}
		
		
		
	}
}

?>