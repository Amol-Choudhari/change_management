<?php
	
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	use Cake\Utility\Hash;
	
	class DmiSentSmsLogsTable extends Table{

		var $name = "DmiSentSmsLogsTable";
	}
