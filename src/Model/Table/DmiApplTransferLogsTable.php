<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
class DmiApplTransferLogsTable extends Table{
	
	var $name = "DmiApplTransferLogs";
	
	public $validate = array(
		
			'customer_id'=>array(
					'rule'=>array('maxLength',50),				
				),											
			'from_office'=>array(
					'rule'=>array('maxLength',10),
				),
			'from_user'=>array(
					'rule'=>array('maxLength',200),
				),
			'to_office'=>array(
					'rule'=>array('maxLength',10),
				),
			'to_user'=>array(
					'rule'=>array('maxLength',200),
				),
			'by_user'=>array(
					'rule'=>array('maxLength',200),
				),
			'flow_type'=>array(
					'rule'=>array('maxLength',50),
				)
			
	);
}

?>