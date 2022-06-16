<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;

class DmiChemistsResetpassKeysTable extends Table{

	var $name = "DmiChemistsResetpassKeys";
	var $useTable = 'dmi_chemists_resetpass_keys';

	public function save_key_details($user_id,$key_id){

		$this->save(array(
			'user_id'=>$user_id,
			'key'=>$key_id,
			'created_on'=>date('Y-m-d H:i:s')
		));

	}



	public function check_valid_key($user_id,$key_id){

		//check record is available
		$get_record = $this->find('all',array('conditions'=>array('user_id IS'=>$user_id,'key'=>$key_id,'status'=>null)))->first();

		if(!empty($get_record)){
			//check key created on
			$created_on = $get_record['created_on'];
			$current_timestamp = date('d-m-Y H:i:s');

			$created_on = strtotime(str_replace('/','-',$created_on));
			$current_timestamp = strtotime($current_timestamp);

			$diff_in_seconds = $current_timestamp - $created_on;
			$diff_in_hours = ($diff_in_seconds/60)/60;//converted in hours

			if($diff_in_hours < 24){

				return 1;
			}else{

				//update status to 2, link expired
				$this->save(array(

					'id'=>$get_record['id'],
					'status'=>'2'

				));

				return 2;
			}

		}else{
			return 2;
		}
	}





	public function update_key_success($user_id,$key_id){

		//check record is available
		$get_record = $this->find('all',array('conditions'=>array('user_id IS'=>$user_id,'key'=>$key_id,'status'=>null)))->first();

		if(!empty($get_record)){

			//update status to 1, link successfully used
			$this->save(array(

				'id'=>$get_record['id'],
				'status'=>'1'

			));
		}

	}

}

?>
