<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
class DmiOldApplicationCertificateDetailsTable extends Table{
	
	var $name = "DmiOldApplicationCertificateDetails";
	
	public function oldApplicationCertificationDetails($customer_id){
		
		$old_certificate_details = $this->find('all', array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
					
		if(empty($old_certificate_details)){			
			$old_certificate_details = array('old_certificate_pdf' =>"", 'old_application_docs' => "");	
		}
		
		return $old_certificate_details;
	}
}

?>