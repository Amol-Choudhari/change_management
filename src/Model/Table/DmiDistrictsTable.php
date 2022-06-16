<?php

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;

class DmiDistrictsTable extends Table{
	
	
	public $validate = array(
		
			'district_name'=>array(
					'rule'=>array('maxLength',100),		
					'allowEmpty'=>true,
				),
			'state_id'=>array(
					'rule'=>'Numeric',	
					'allowEmpty'=>true,
				),
			'ro_id'=>array(
					'rule'=>'Numeric',	
					'allowEmpty'=>true,
				),
			'pao_id'=>array(
					'rule'=>'Numeric',
					'allowEmpty'=>true,
				),
		);


	// get ro and so id from district code by akash on 02-06-2022
	public function getRoIdFromDistrictId($district_id){

		$details = $this->find()->where(['id IS' => $district_id,'delete_status IS NULL'])->first();
		if (!empty($details)) {
			return array('ro_id' => $details['ro_id'], 'so_id' => $details['so_id']);
		}
		
	}
}

?>