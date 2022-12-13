<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
	class DmiSurrenderAllCurrentPositionsTable extends Table{
	
	var $name = "DmiSurrenderAllCurrentPositions";
	
	public function applicationCurrentUsers($customer_id)
	{
		$fetch_data = $this->find('all',array('fields'=>'current_user_email_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id DESC')))->first();		
		
		return $fetch_data;
	}
	
	
	
	public function userCurrentApplications($user_email_id)
	{
		$fetch_data = $this->find('all',array('conditions'=>array('current_user_email_id IS'=>$user_email_id)))->toArray();
		
		return $fetch_data;
	}
	
	
	public function currentUserEntry($customer_id,$user_email_id,$current_level)
	{
		$Entity = $this->newEntity(array(
			'customer_id'=>$customer_id,
			'current_level'=>$current_level,
			'current_user_email_id'=>$user_email_id,
			'created'=>date('Y-m-d H:i:s')		 
		 ));
		 $this->save($Entity);
		
	}
	
	
	public function currentUserUpdate($customer_id,$user_email_id,$current_level)
	{
		
		$find_row_id = $this->find('all',array('fields'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id DESC')))->first();
		$row_id = $find_row_id['id'];
		
		$newEntity = $this->newEntity(array(
			'id'=>$row_id,
			'current_level'=>$current_level,
			'current_user_email_id'=>$user_email_id,
			'modified'=>date('Y-m-d H:i:s')		 
		 ));
		
		 $this->save($newEntity);
		return true;
	}
		
}

?>