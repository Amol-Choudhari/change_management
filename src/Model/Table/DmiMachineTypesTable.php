<?php 
    namespace app\Model\Table;
    use Cake\ORM\Table;
    use App\Model\Model;
    use App\Controller\AppController;
    use App\Controller\CustomersController;
    use Cake\ORM\TableRegistry;
    
class DmiMachineTypesTable extends Table{	

	var $name = "DmiMachineTypes";
	
	public $validate = array(
	
		'machine_types'=>array(
					'rule'=>array('maxLength',50),	
					'allowEmpty'=>false,	
				),
		'delete_status'=>array(
					'rule'=>array('maxLength',20),				
				),
		'user_email_id'=>array(
					'rule'=>array('maxLength',100),				
				),
		'application_type'=>array(
					'rule'=>array('maxLength',10),
					'allowEmpty'=>false,	
				),			
	
	);

} ?>