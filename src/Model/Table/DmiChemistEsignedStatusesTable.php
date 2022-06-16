<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\CustomersController;

class DmiChemistEsignedStatusesTable extends Table{

	var $name = "DmiChemistEsignedStatuses";
	var $useTable = 'dmi_chemist_esigned_statuses';
}
?>
