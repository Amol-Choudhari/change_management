<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;

class DmiApplicationChargesTable extends Table{
	
	var $name = "DmiApplicationCharges";
	
	public $validate = array(
	
		'application_type'=>array(
					'rule'=>array('maxLength',200),	
					'allowEmpty'=>false,	
				),
		'charge'=>array(
					'rule'=>array('maxLength',200),
					'allowEmpty'=>false,	
				),
		'user_email_id'=>array(
					'rule'=>array('maxLength',200),				
				),
		'certificate_type_id'=>array(
					'rule'=>array('maxLength',10),				
				),				
	
	);
}

?>