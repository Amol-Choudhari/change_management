<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;
	

class DmiFirmActionLogsTable extends Table{

	var $name = "DmiFirmActionLogs";


	public function getActionLogs(){
		return $this->find('all', array('conditions' => array('customer_id IS' => $_SESSION['username'],'action_perform IS NOT NULL'), 'order' => array('id desc'), 'limit' => '100'))->toArray();
	}

	public function saveActionLogs($userAction,$status,$username){

		$current_ip = $_SERVER['REMOTE_ADDR'];
		
		if($username==null){
			$username = $_SESSION['username'];
		}

		if ($current_ip == '::1') { $current_ip = '127.0.0.1'; }

		$entity = $this->newEntity(['customer_id'=>$username,
									'action_perform'=>$userAction,
									'ipaddress'=>$current_ip,
									'status'=>$status,
									'created'=>date('Y-m-d H:i:s')]);

		$this->save($entity);
	}


}

?>