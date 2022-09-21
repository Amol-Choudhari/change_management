<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;
	

class DmiPasswordLogsTable extends Table{

	var $name = "DmiPasswordLogs";
	var $useTable = 'dmi_password_logs';
	
	public function savePasswordLogs($username, $table, $password){

		date_default_timezone_set('Asia/Kolkata');
		
		if ($table=='DmiChemistRegistrations') {
			$user_type = 'Chemist';
		} elseif ($table=='DmiCustomers') {
			$user_type = 'Customer';
		} elseif ($table=='DmiFirms') {
			$user_type = 'Firm';
		} elseif ($table=='DmiUsers') {
			$user_type = 'User';
		}

		$newEntity = $this->newEntity(array(

			'username'=>$username,
			'user_type'=>$user_type,
			'password'=>$password,
			'created'=>date('Y-m-d H:i:s')
		));

		if ($this->save($newEntity)){ return true;  }

	}

	public function checkPastThreePassword($username, $table, $password) {
		
		$result = "empty";

		if ($table=='DmiChemistRegistrations') {
			$user_type = 'Chemist';
		} elseif ($table=='DmiCustomers') {
			$user_type = 'Customer';
		} elseif ($table=='DmiFirms') {
			$user_type = 'Firm';
		} elseif ($table=='DmiUsers') {
			$user_type = 'User';
		}
	
		$lastThreePassword = $this->find('all', array('conditions' => array('username'=>$username, 'user_type'=>$user_type), 'order' => 'id DESC', 'limit' => '3'))->toArray();
		
		foreach ($lastThreePassword as $passwordLog) {
				
			$passwordInDb = $passwordLog['password'];
				
			if ($password == $passwordInDb) {
				
				$result = 'found';
			}
		}
			
		return $result;
		exit;
	}


}

?>