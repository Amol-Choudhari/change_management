<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;

class DmiApplicantsResetpassKeysTable extends Table{
	
	var $name = "DmiApplicantsResetpassKeys";
	
	
	public function saveKeyDetails($applicant_id,$key_id){
		$saveDataEntity = $this->newEntity(array(
			'applicant_id'=>$applicant_id,
			'key'=>$key_id,
			'created_on'=>date('Y-m-d H:i:s')		
		)); 
		$this->save($saveDataEntity);
	
	}
	
	
	
	public function checkValidKey($user_id,$key_id){
		
		//check record is available
		$get_record = $this->find('all',array('conditions'=>array('applicant_id IS'=>$user_id,'key IS'=>$key_id,'status IS NULL')))->first();
		
		if(!empty($get_record)){
			//check key created on
			$created_on = $get_record['created_on'];
			$current_timestamp = date('d/m/Y H:i:s');
		
			$created_on = strtotime($created_on);
			$current_timestamp = strtotime($current_timestamp);
			
			$diff_in_seconds = $current_timestamp - $created_on;
			$diff_in_hours = ($diff_in_seconds/60)/60;//converted in hours
			
			if($diff_in_hours < 24){
				
				return 1;
			}else{
				
				//update status to 2, link expired
				$saveDataEntity = $this->newEntity(array(				
					'id'=>$get_record['id'],
					'status'=>'2'				
				)); 
				
				$this->save($saveDataEntity);
				
				return 2;
			}
			
		}else{
			return 2;
		}
	}	
	
	public function updateKeySuccess($user_id,$key_id){
		
		//check record is available
		$get_record = $this->find('all',array('conditions'=>array('applicant_id IS'=>$user_id,'key IS'=>$key_id,'status IS NULL'),'order'=>'id desc'))->first();		
		if(!empty($get_record)){
			
			//update status to 1, link successfully used
			$saveDataEntity = $this->newEntity(array(			
				'id'=>$get_record['id'],
				'status'=>'1'			
			)); 
			$this->save($saveDataEntity);
		}

	}
	
	
}

?>