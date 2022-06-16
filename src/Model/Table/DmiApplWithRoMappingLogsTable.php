<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
class DmiApplWithRoMappingLogsTable extends Table{
	
	var $name = "DmiApplWithRoMappingLogs";
	
	public $validate = array(
		
			'customer_id'=>array(
					'rule'=>array('maxLength',50),				
				),											
			'ro_id'=>array(
					'rule'=>'Numeric',
				),
			
	);
}

?>