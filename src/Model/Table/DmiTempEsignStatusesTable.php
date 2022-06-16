<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use Cake\Filesystem\File;
	
class DmiTempEsignStatusesTable extends Table{
	
	var $name = "DmiTempEsignStatuses";
	

	//this function is created to save temp esign record, when redirected from CDAC domain to DMI domain
	//to just get that esigning is called, this record will be deleted if esiging done properly till final submit.
	public function saveTempEsignRecord($customer_id,$pdf_file_name,$esigning_level,$flow_type){
		
		$Entity = $this->newEntity(array(
			'customer_id'=>$customer_id,
			'pdf_file_name'=>$pdf_file_name,
			'esigning_level'=>$esigning_level,
			'esigning_user'=>$_SESSION['username'],
			'flow_type'=>$flow_type,
			'created'=>date('Y-m-d H:i:s')
		));
		$this->save($Entity);
	}
	
	
	//this function created to delete the temp esign record. if final submit done properly after esign called.
	//to just ensure that esign completed, not halfly done.
	public function DeleteTempEsignRecord($customer_id){
		
		$get_record = $this->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		
		if(!empty($get_record)){
			$this->deleteAll(array('customer_id'=>$customer_id));
		}
	}
	
	
	//this function created to check that if there any record exist which was not deleted.
	//if yes then then the esign process is not completed till final submit properly.
	//then delete created PDF file from DMI/temp folder and also update/delete the record from temp table.
	public function checkTempEsignRecordExist($customer_id,$esigning_level){
		
		$get_record = $this->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'esigning_level'=>$esigning_level)))->first();
		
		if(!empty($get_record)){
			
			$pdf_file_name = $get_record['pdf_file_name'];
			$flow_type = $get_record['flow_type'];
			
			//delete pdf file from temp folder
			
			$file = new File($_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/'.$pdf_file_name, false, 0777);
			$file->delete();

			//delete temp esign record. from temp table
			$this->deleteAll(array('customer_id'=>$customer_id));
			
			
			//take flow wise esign status model to fire query on.
			$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$Dmi_flow_wise_tables = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('id IS'=>$flow_type)))->first();
			$Dmi_esign_status_model = TableRegistry::getTableLocator()->get($Dmi_flow_wise_tables['esign_status']);
			
			//check esign record exist
			$get_esign_reoord = $Dmi_esign_status_model->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
			$application_status = null;
			if(!empty($get_esign_reoord)){
				
				$application_status = $get_esign_reoord['application_status'];
				
				//update/delete record from main esign status table conditionally.
				if($esigning_level=='applicant'){
					//if($application_status!='referred_back' && $application_status!='replied' && $application_status!='approved') {
						$Dmi_esign_status_model->updateAll(array('application_esigned' => null),array('customer_id'=>$customer_id));//to update record
					//}
				}elseif($esigning_level=='level_2'){
					//if($application_status!='referred_back' && $application_status!='replied' && $application_status!='approved') {
						$Dmi_esign_status_model->updateAll(array('report_esigned' => null),array('customer_id'=>$customer_id));//to update record
					//}
					
				}elseif($esigning_level=='level_3'){
					$Dmi_esign_status_model->updateAll(array('certificate_esigned' => null),array('customer_id'=>$customer_id));//to update record
					
				}elseif($esigning_level=='level_4'){
					$Dmi_esign_status_model->updateAll(array('certificate_esigned' => null),array('customer_id'=>$customer_id));//to update record
					
				}
			}
			
			
		}		
		
	}
}

?>