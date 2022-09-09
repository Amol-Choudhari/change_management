<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;
	

class DmiUserActionLogsTable extends Table{

	var $name = "DmiUserActionLogs";


	public function getActionLogs(){

	}

	public function saveActionLogs($userAction,$status){

		$user_id = $_SESSION['username'];

		$current_ip = $_SERVER['REMOTE_ADDR'];

		if ($current_ip == '::1') { $current_ip = '127.0.0.1'; }

		$action_entity = $this->newEntity(['user_id'=>$user_id,
										   'action_perform'=>$userAction,
										   'ipaddress'=>$current_ip,
										   'status'=>$status,
										   'created'=>date('Y-m-d H:i:s')]);

		$this->save($action_entity);
	}


}

?>