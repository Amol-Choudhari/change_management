<?php
    namespace app\Model\Table;
    use Cake\ORM\Table;
    use App\Model\Model;
class DmiCrushingRefiningPeriodsTable extends Table{	

	var $name = "DmiCrushingRefiningPeriods";
	
	public $validate = array(
	
		'crushing_refining_periods'=>array(
					'rule'=>array('maxLength',50),	
					'allowEmpty'=>false,	
				),
		'delete_status'=>array(
					'rule'=>array('maxLength',20),				
				),
		'user_email_id'=>array(
					'rule'=>array('maxLength',100),				
				),				
	
	);

} ?>