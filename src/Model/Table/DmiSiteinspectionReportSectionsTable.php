<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
class DmiSiteinspectionReportSectionsTable extends Table{
	
	var $name = "DmiSiteinspectionReportSectionsTable";
	
	public function sectionUrl($customer_id,$form_section_id,$application_type,$report_application_subtype){
		
		if(empty($form_section_id)){   $form_section_id = 1;  }
		$explode_array = explode('/',$customer_id);
		$firm_type = $explode_array[1];		
		$path = $this->find('all',array('conditions'=>array('firm_type IS'=>$firm_type,'section IS'=>$form_section_id,'application_type IS'=>$application_type,'application_subtype IS'=>$report_application_subtype)))->first();
		$section_path = $path['path'];	
		return $section_path;
	}
	
	
	public function reportSectionStatus($customer_id,$application_type,$report_application_subtype){
		
		$explode_array = explode('/',$customer_id);
		$firm_type = $explode_array[1];
		$report_section_details = $this->find('all',array('conditions'=>array('firm_type IS'=>$firm_type,'application_type IS'=>$application_type,'application_subtype IS'=>$report_application_subtype)))->toArray();
		$return_value = '';
		
		foreach($report_section_details as $each_section){
			
			$model_name = $each_section['model'];
			
			if(!empty($model_name)){
			
			
			$model = TableRegistry::getTableLocator()->get($model_name);
			
			$list_report_id = $model->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
					
			if(!empty($list_report_id))
			{
					
				$find_last_status = $model->find('all', array('fields'=>'form_status', 'conditions'=>array('id'=>max($list_report_id))))->first();
				$report_status = $find_last_status['form_status'];	
				
				if($report_status != 'saved')
				{ 
				
					$return_value= 'false';
					break;
				
				}else{
					
					$return_value= 'true';
				}
			
			}else{
				
				$return_value= 'false';
				break;
			}
		  }
		}
		
		return $return_value;
	}
	
	public function getCurrentSectionDetail($customer_id,$form_section_id,$application_type,$report_application_subtype){
		
		if(empty($form_section_id)){   $form_section_id = 1;  }
		$explode_array = explode('/',$customer_id);
		$firm_type = $explode_array[1];
		$section_details = $this->find('all',array('conditions'=>array('firm_type IS'=>$firm_type,'section IS'=>$form_section_id,'application_type IS'=>$application_type,'application_subtype IS'=>$report_application_subtype)))->first();
		$section_detail = $section_details;			
		return $section_detail;
		
	}
	
	
	public function reportSectionApproveStatus($customer_id,$application_type,$report_application_subtype){
		
		$explode_array = explode('/',$customer_id);
		$firm_type = $explode_array[1];
		$report_section_details = $this->find('all',array('conditions'=>array('firm_type IS'=>$firm_type,'application_type IS'=>$application_type,'application_subtype IS'=>$report_application_subtype)))->first();
		$return_value = '';
		
		foreach($report_section_details as $each_section){
			
			$model_name = $each_section['model'];
			if(!empty($model_name)){
						
			$model = TableRegistry::getTableLocator()->get($model_name);
			
			$list_report_id = $model->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
					
			if(!empty($list_report_id))
			{
				
				$find_last_status = $model->find('all', array('fields'=>'form_status', 'conditions'=>array('id'=>max($list_report_id))))->first();
				$report_status = $find_last_status['form_status'];	
			
				if($report_status != 'approved')
				{
					$return_value= 'false';
					break;
				
				}else{
					
					$return_value= 'true';
				}
			}
		  }
		}
		
		return $return_value;
	}
	
	
	
}

?>