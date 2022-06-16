<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
	class DmiRenewalAllocationsTable extends Table{
		
		var $name = "DmiRenewalAllocations";
		
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
		
		
	}

?>