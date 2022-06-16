<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	
	class DmiCommonSiteinspectionFlowDetailsTable extends Table{
		
		var $name = "DmiCommonSiteinspectionFlowDetails";

		//Get current section details of current application type	
		public function currentSectionDetails($application_type,$office_type,$firm_type,$form_type,$section_id){
			
			$sectionDetails = $this->find('all',array('conditions'=>array('application_type IS'=>$application_type,'office_type IS'=>$office_type,'firm_type IS'=>$firm_type,
														  'form_type IS'=>$form_type,'section_id IS'=>$section_id)))->first();		
			return $sectionDetails;
		}
		
		//Get all section details of current application type
		public function allSectionList($application_type,$office_type,$firm_type,$form_type){
			
			$allSectionDetails = $this->find('all',array('conditions'=>array('application_type IS'=>$application_type,'office_type IS'=>$office_type,'firm_type IS'=>$firm_type,
														  'form_type IS'=>$form_type),'order'=>'section_id'))->toArray();
			
			return $allSectionDetails;
		}
		
		// To check, all report sections are "saved" or not
		public function reportSectionStatus($customer_id,$allSectionDetails){
			
			$CustomersController = new CustomersController;
			$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
			
			$return_value = '';	
			
			foreach($allSectionDetails as $each_section){
					
				$model_name = $each_section['section_model'];
				
				if(!empty($model_name)){				
					
					$model = TableRegistry::getTableLocator()->get($model_name);
					
					$list_report_id = $model->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->toArray();
						 
					if(!empty($list_report_id))
					{						
						$find_last_status = $model->find('all', array('fields'=>'form_status', 'conditions'=>array('id'=>max($list_report_id))))->first();
						$report_status = $find_last_status['form_status'];	
						
						if($report_status != 'saved' && $report_status != 'approved')
						{ 				
							$return_value= 'false';
							break;
							
						}else{ $return_value= 'true'; }
					
					}else{					
						$return_value= 'false';
						break;
					}
				}
			}
			
			return $return_value;
		}
		
		// To check, all report sections are "approved" or not
		public function reportSectionApproveStatus($customer_id,$allSectionDetails){
			
			$CustomersController = new CustomersController;
			$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
			
			$return_value = '';
			
			foreach($allSectionDetails as $each_section){
				
				$model_name = $each_section['section_model'];
				if(!empty($model_name)){
								
				$model = TableRegistry::getTableLocator()->get($model_name);
				
				$list_report_id = $model->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->toArray();
						
				if(!empty($list_report_id))
				{
					
					$find_last_status = $model->find('all', array('fields'=>'form_status', 'conditions'=>array('id'=>max($list_report_id))))->first();
					$report_status = $find_last_status['form_status'];	
				
					if($report_status != 'approved')
					{   
						$return_value= 'false';
						break;
						
					}else{ $return_value= 'true'; 	}
				}
			  }
			}
			
			return $return_value;
		}
		
		// To check, at least one sections is saved with referred back comment or not
		public function reportReferredBackStatus($customer_id,$allSectionDetails){
			
			$CustomersController = new CustomersController;
			$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
			
			$return_value = '';			
			foreach($allSectionDetails as $each_section){
					
				$model_name = $each_section['section_model'];
				$comment_section = $each_section['comment_section'];
				
				if(!empty($model_name) && !empty($comment_section)){
									
					$model = TableRegistry::getTableLocator()->get($model_name);
					
					$list_report_id = $model->find('all', array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition),'order'=>'id DESC'))->first();					 
					
					if(!empty($list_report_id['referred_back_comment']) &&
					   empty($list_report_id['io_reply']))
					{	
						$return_value= 'referred_back';
						break;
					}
				}
			}
			 
			return $return_value;
		}
	}

?>