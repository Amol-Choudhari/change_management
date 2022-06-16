<?php
	
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	use Cake\Utility\Hash;
	
	class DmiSentEmailLogsTable extends Table{

		var $name = "DmiSentEmailLogs";
	}
