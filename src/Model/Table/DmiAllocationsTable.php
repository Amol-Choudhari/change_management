<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
class DmiAllocationsTable extends Table{
	
	var $name = "DmiAllocations";
	
	public $validate = array(
		
			'customer_id'=>array(
					'rule'=>array('maxLength',100),				
				),											
			'level_1'=>array(
					'rule'=>array('maxLength',200),
				),
			'level_2'=>array(
					'rule'=>array('maxLength',200),
				),
			'level_3'=>array(
					'rule'=>array('maxLength',200),
				),
			'current_level'=>array(
					'rule'=>array('maxLength',200),
				),
			'ro_scheduled_date'=>array(
					'rule'=>array('date','dmy'),
				),
			'io_scheduled_date'=>array(
					'rule'=>array('date','dmy'),
				),
	);


	//get Allocated Users by Customer Id
	public function getAllocatedScrutinizer($customer_id) {
		
		$users = $this->find()->where(['customer_id IS' => $customer_id])->first();
		if (!empty($users)) {
			return $users['level_1'];
		}
	}


	//get Allocated Users by Customer Id
	public function getAllocatedIo($customer_id) {

		$users = $this->find()->where(['customer_id IS' => $customer_id])->first();
		if (!empty($users)) {
			return $users['level_2'];
		}
	}


}

?>