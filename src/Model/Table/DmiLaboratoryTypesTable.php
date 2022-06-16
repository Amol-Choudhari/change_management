<?php

    namespace app\Model\Table;
    use Cake\ORM\Table;
    use App\Model\Model;
    use App\Controller\AppController;
    use App\Controller\CustomersController;
    use Cake\ORM\TableRegistry;
    
class DmiLaboratoryTypesTable extends Table{
	
	var $name = "DmiLaboratoryTypes";
	
	public $validate = array(
	
		'laboratory_type'=>array(
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
	
}

?>