<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	
	class DmiPagesTable extends Table{
		
		var $name = "Dmi_page";
		
		public $validate = array(
		
			'title'=>array(
						'rule'=>array('maxLength',100),
						'allowEmpty'=>false,	
					),
			'content'=>array(
						'rule' => 'notBlank',	
					),
			'user_email_id'=>array(
						'rule'=>array('maxLength',200),
					),
			'status'=>array(
						'rule'=>array('maxLength',50),
					),
			'meta_keyword'=>array(
						'rule'=>array('maxLength',200),
					),
			'archive_date'=>array(
						'rule'=>array('date','dmy'),
						'allowEmpty'=>false,
					),
			'publish_date'=>array(
						'rule'=>array('date','dmy'),
						'allowEmpty'=>false,
					),		
		);
	}

?>