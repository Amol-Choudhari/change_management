<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
class DmiFlowWiseTablesListsTable extends Table{
	
	var $name = "DmiFlowWiseTablesLists";
	
	public function getFlowWiseTableDetails($application_type,$colomn_name){//removed "$application_type" parameter and added variable from session value below
		
		$tables_details = $this->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		$table_name = $tables_details[$colomn_name];		
		return $table_name;
	}
}

?>