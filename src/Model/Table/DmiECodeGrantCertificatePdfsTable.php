<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
	class DmiECodeGrantCertificatePdfsTable extends Table{
	
	var $name = "DmiECodeGrantCertificatePdfs";
	
	
	public function renewalDueReportConditions($renewal_year,$state,$district,$application_type) {

		$final_customer_id = array();
	
		return $final_customer_id;	
	}
	
	// Get certificate pdfs, Pravin Bhakare, 03-07-2020
	public function getcertificate($customer_id){
		
		// $result = $this->find('all',array('fields'=>array('pdf_file'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
		$result = $this->find('all')->select(['pdf_file'])->where(['customer_id IS'=>$customer_id])->order(['id'=>'DESC'])->first();

		if(!empty($result)) {
			$filePath = $result['pdf_file'];
		}
		else {
			$filePath = null;
		}
		return $filePath;
	}
		
}

?>