<?php
	namespace app\Controller\Component;
	use Cake\Controller\Controller;
	use Cake\Controller\Component;
	use Cake\Controller\ComponentRegistry;
	use Cake\ORM\Table;
	use Cake\ORM\TableRegistry;
	use Cake\Datasource\EntityInterface;

class ReportstatisticsComponent extends Component {
	
	public $components= array('Session');
	public $controller = null;
	public $session = null;

	public function initialize(array $config): void{
		parent::initialize($config);
		$this->Controller = $this->_registry->getController();
		$this->Session = $this->getController()->getRequest()->getSession();
	}

	public function backlog_app_processed($searchConditions){
		
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');//initialize model in component
		$final_submitT	= 'Dmi_final_submit';
		
		$conditions = array();
		$date_conditions = array();
		
		if(array_key_exists("OR",$searchConditions)){
			$conditions = array('OR'=>$searchConditions['OR']);
		}
		if(array_key_exists("date(modified) BETWEEN ? AND ?",$searchConditions)){
			$date_conditions = $searchConditions['date(modified) BETWEEN ? AND ?'];
		}		
		
		// $firm_result = $DmiFirms->find('list',array('fields'=>'customer_id','conditions'=>am($conditions,array('is_already_granted'=>'yes'))));
		// below query updated by Ankur Jangid as per cakePHP 4.2
		$firm_result = $DmiFirms->find('all')->where($conditions)->where(['is_already_granted IS' => 'yes'])->combine('id', 'customer_id')->toArray();
		
		$final_result = $this->inprocess_applications($firm_result,$final_submitT,$date_conditions);
		return $final_result;
	}
	
	public function new_app_processed($searchConditions){
		
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');//initialize model in component
		$final_submitT	= 'Dmi_final_submit';	
		
		$conditions = array();
		$date_conditions = array();
		
		if(array_key_exists("OR",$searchConditions)){
			// $conditions = array('OR'=>$searchConditions['OR']); 
			$conditions = ['OR'=>$searchConditions['OR']]; 
		}
		if(array_key_exists("date(modified) BETWEEN ? AND ?",$searchConditions)){
			$date_conditions = $searchConditions['date(modified) BETWEEN ? AND ?']; print_r('2 - ReportstatisticsComponent'); exit;
		}
		
		// $firm_result = $DmiFirms->find('list',array('fields'=>'customer_id','conditions'=>am($conditions,array('is_already_granted !='=>'yes'))));
		// below query updated by Ankur Jangid as per cakePHP 4.2
		$firm_result = $DmiFirms->find('all')->where($conditions)->where(['is_already_granted IS NOT' => 'yes'])->combine('id', 'customer_id')->toArray();
	
		$final_result = $this->inprocess_applications($firm_result,$final_submitT,$date_conditions);
		
		return $final_result;
	}
	
	public function renewal_app_processed($searchConditions){
		
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');//initialize model in component
		$final_submitT	= 'Dmi_renewal_final_submit';	
		$conditions = array();
		$date_conditions = array();
		
		if(array_key_exists("OR",$searchConditions)){
			$conditions = array('OR'=>$searchConditions['OR']);
		}
		if(array_key_exists("date(modified) BETWEEN ? AND ?",$searchConditions)){
			$date_conditions = $searchConditions['date(modified) BETWEEN ? AND ?'];
		}
		
		$firm_result = $DmiFirms->find('list',array('fields'=>'customer_id','conditions'=>$conditions));
		$final_result = $this->inprocess_applications($firm_result,$final_submitT,$date_conditions);
		return $final_result;
	}
	
	public function inprocess_applications($firm_result,$final_submitT,$date_conditions){
		
		if(!empty($date_conditions)){
			$date = array('date(modified) BETWEEN ? AND ?' => $date_conditions);
		}else{
			$date = array();
		}
		
		// $Dmi_final_submit = ClassRegistry::init($final_submitT);
		$DmiFinalSubmits = TableRegistry::getTableLocator()->get('DmiFinalSubmits'); // initialize model in component

		// $final_submit_result = $Dmi_final_submit->find('all',array('fields'=>array('DISTINCT customer_id'),'conditions'=>array('customer_id'=>$firm_result)));
		// below query updated by Ankur Jangid as per cakePHP 4.2
		$final_submit_result = $DmiFinalSubmits->find('all')->select(['customer_id'])->distinct(['customer_id'])->where(['customer_id IN' => $firm_result])->toArray();
		
		
		
		$tot = 0; $ca = 0; $pp = 0; $la = 0;
		$grant_tot = 0; $grant_ca = 0; $grant_pp = 0; $grant_la = 0; 	
		$application_inprocess_status = array();
		foreach($final_submit_result as $each_result){ 
			// $customer_id = $each_result[$final_submitT]['customer_id'];
			$customer_id = $each_result['customer_id']; 
			$explode = explode('/',$customer_id); 
			$application_type = $explode[1]; 
			
			// $grant_result = $Dmi_final_submit->find('first',array('fields'=>array('customer_id'),'conditions'=>am($date,array('customer_id'=>$customer_id,'current_level'=>'level_3','status'=>'approved'))));
			// below query updated by Ankur Jangid as per cakePHP 4.2
			$grant_result = $DmiFinalSubmits->find('all')->select(['customer_id'])->where($date)
				->where(['customer_id IS' => $customer_id, 'current_level' => 'level_3', 'status' => 'approved'])->first();
			
			if(empty($grant_result)){
				
				if($application_type == 1){
					$ca = $ca +1;
				}elseif($application_type == 2){
					$pp = $pp +1;
				}elseif($application_type == 3){
					$la = $la +1;
				}
				$tot = $tot + 1;
			}else{
				
				if($application_type == 1){
					$grant_ca = $grant_ca +1;
				}elseif($application_type == 2){
					$grant_pp = $grant_pp +1;
				}elseif($application_type == 3){
					$grant_la = $grant_la +1;
				}
				$grant_tot = $grant_tot + 1;
			}
		}
		//exit;
		$inprogress_application = array($ca,$pp,$la,$tot);
		$grant_application = array($grant_ca,$grant_pp,$grant_la,$grant_tot);
		return array($inprogress_application,$grant_application);		
	}
	
}
?>