<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
class DmiRenewalSiteinspectionFinalReportsTable extends Table{
	
	var $name = "DmiRenewalSiteinspectionFinalReports";
	
	 
	public function siteinspectionFinalReportStatus($customer_id){
		
		$CustomersController = new CustomersController;	
		$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
		
		$final_status = null;
		$final_status_id_list = $this->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
		if($final_status_id_list){
			$final_reply_status = $this->find('all',array('conditions'=>array('id'=>max($final_status_id_list))))->first();
			$final_status = $final_reply_status;
		}
		return $final_status;		
	}
}

?>