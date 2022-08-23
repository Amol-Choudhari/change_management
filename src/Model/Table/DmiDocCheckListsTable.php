<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiDocCheckListsTable extends Table{
    
    var $name = "DmiDocCheckLists";
    var $useTable = 'dmi_doc_check_lists';
       
    }
    ?>
    