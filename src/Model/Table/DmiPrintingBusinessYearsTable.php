<?php 
        namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
class DmiPrintingBusinessYearsTable extends Table{	

	var $name = "DmiPrintingBusinessYears";
	
	public $validate = array(
	
		'business_years'=>array(
					'rule'=>array('maxLength',20),	
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