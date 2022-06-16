<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;
use App\Controller\CustomersController;

class DmiGrantCertificatesPdfsTable extends Table{
	
	var $name = "DmiGrantCertificatesPdfs";
	
	// Method to find renewal due application list (Done By pravin 08/11/2017)
	public function renewalDueReportConditions($renewal_year,$state,$district,$application_type) {
	
		$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
		$CustomersController = new CustomersController;
		
		$final_customer_id = array();
		
		$granted_application_ids = $this->find('all')->select(['customer_id'])->distinct(['customer_id'])->extract('customer_id')->toArray(); // updated by Ankur
		
		if(!empty($granted_application_ids)) {				
			$i=0;
			foreach($granted_application_ids as $application_id) { 
				
				$application_id_list = $this->find('all')->select(['id'])->where(['customer_id IS' => $application_id])->combine('id', 'id')->toArray(); // updated by Ankur
				
				$granted_application_details = $this->find('all')->select(['customer_id', 'date'])->where(['id' => max($application_id_list)])->first(); // updated by Ankur
				
				$firm_details = $Dmi_firm->find('all')->where(['customer_id IS' => $application_id])->first();  // updated by Ankur
				
				$application_renewal_date = $CustomersController->Customfunctions->getCertificateValidUptoDate($granted_application_details['customer_id'],
																					$granted_application_details['date']);	
			
				
				$renewal_date = new \DateTime(date($application_renewal_date));
				$renewal_date_year = $renewal_date->format('Y');
				
				if($state != '' && $district != '' && $application_type == '') {
					if($renewal_date_year == $renewal_year) {
						if($state == $firm_details['state'] && $district == $firm_details['district']) {
							$final_customer_id[$i] = $application_id;
							$i=$i+1;
						}
					}					
				} elseif($state != '' && $district == '' && $application_type == '') {
					if($renewal_date_year == $renewal_year) {
						if($state == $firm_details['state']) {
							$final_customer_id[$i] = $application_id;
							$i=$i+1;
						}
					}					
				} elseif($state != '' && $district != '' && $application_type != '') {
					if($renewal_date_year == $renewal_year){
						if($state == $firm_details['state'] && $district == $firm_details['district'] && $application_type == $firm_details['certification_type']) {
							$final_customer_id[$i] = $application_id;
							$i=$i+1;
						}
					}					
				} elseif($state != '' && $district == '' && $application_type != '') {
					if($renewal_date_year == $renewal_year) {
						if($state == $firm_details['state'] && $application_type == $firm_details['certification_type']){
							$final_customer_id[$i] = $application_id;
							$i=$i+1;
						}
					}					
				} elseif($state == '' && $district == '' && $application_type != '') {
					if($renewal_date_year == $renewal_year) {
						if($application_type == $firm_details['certification_type']) {
							$final_customer_id[$i] = $application_id;
							$i=$i+1;
						}
					}					
				} else {
					if($renewal_date_year == $renewal_year) {
						$final_customer_id[$i] = $application_id;
						$i=$i+1;
					}
				}				
			}				
		}	
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