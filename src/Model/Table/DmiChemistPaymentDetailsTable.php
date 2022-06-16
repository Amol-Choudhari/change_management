<?php

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;

class DmiChemistPaymentDetailsTable extends Table{

	var $name = "DmiChemistPaymentDetails";
	var $useTable = 'dmi_chemist_payment_details';
}

?>
