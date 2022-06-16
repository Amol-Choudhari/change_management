<?php 

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
class DmiBusinessTypesTable extends Table{	

	var $name = "DmiBusinessTypes";
	
	public $validate = array(
	
		'business_type'=>array(
					'rule'=>array('maxLength',100),	
					'allowEmpty'=>false,	
				),
		'delete_status'=>array(
					'rule'=>array('maxLength',10),				
				),
		'user_email_id'=>array(
					'rule'=>array('maxLength',200),				
				),			
	
	);

} ?>