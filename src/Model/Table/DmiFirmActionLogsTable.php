<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;
	

class DmiFirmActionLogsTable extends Table{

	var $name = "DmiFirmActionLogs";


	public function getActionLogs(){

	}

	public function saveActionLogs($userAction,$status){

		$current_ip = $_SERVER['REMOTE_ADDR'];

		if ($current_ip == '::1') { $current_ip = '127.0.0.1'; }

		$action_entity = $this->newEntity(['customer_id'=>$_SESSION['username'],
										   'action_perform'=>$userAction,
										   'ip_address'=>$current_ip,
										   'status'=>$status,
										   'created'=>date('Y-m-d H:i:s')]);

		$this->save($action_entity);
	}


}

?>