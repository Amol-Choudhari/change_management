<?php
    namespace app\Model\Table;
    use Cake\ORM\Table;
    use App\Model\Model;
    use App\Controller\AppController;
    use App\Controller\CustomersController;
    use Cake\ORM\TableRegistry;
	

class DmiTankShapesTable extends Table{
	
	var $name = "DmiTankShapes";
	
/*/	public $validate = array(
	
		'tank_shapes'=>array(
					'rule'=>array('maxLength',5),	
					'allowEmpty'=>false,	
				),		
		'user_email_id'=>array(
					'rule'=>array('maxLength',100),				
				),
		'delete_status'=>array(
					'rule'=>array('maxLength',20),				
				),				
	
	);*/
}

?>