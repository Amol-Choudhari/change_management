<?php 

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;

class DmiChangeSelectedFieldsTable extends Table{
	
	var $name = "DmiChangeSelectedFields";

	public function selectedChangeFields(){
				
		$CustomersController = new CustomersController;
		$customer_id = $CustomersController->Customfunctions->sessionCustomerID(); 
		$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
		
		$selectedValues = [];			
		$recordid = '';
		$sections = [];
		
		$selectedfields = $this->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();
		if($selectedfields != null){
			$recordid = $selectedfields['id'];
			$selectedValues = explode(',',$selectedfields['changefields']);
			$sections = explode(',',$selectedfields['changesections']);
		}
		
		return array($selectedValues,$recordid,$sections);
			
	}
	
	public function saveData($fields){
		
		$CustomersController = new CustomersController;
		$customer_id = $CustomersController->Customfunctions->sessionCustomerID();
		$form_type = $CustomersController->Customfunctions->checkApplicantFormType($customer_id);
		
		$result = $this->selectedChangeFields();
		$id = $result[1];
		
		$selectedFields = explode(',',$fields);
		$DmiChangeFieldList = TableRegistry::getTableLocator()->get('DmiChangeFieldLists'); 
		
		$sections = $DmiChangeFieldList->find('list',array('valueField'=>'sectionid','conditions'=>array('field_id IN'=>$selectedFields, 'form_type IS'=>$form_type)))->toArray();
		$sectionids = implode(',',array_unique($sections)); 
		
		$newEntity = $this->newEntity(array(
			'id'=>$id,
			'customer_id'=>$customer_id,
			'changefields'=>$fields,
			'changesections'=>$sectionids,
			'created'=>date('Y-m-d H:i:s'),
			'modified'=>date('Y-m-d H:i:s')
		));
		
		$this->save($newEntity);
	}

} 



?>