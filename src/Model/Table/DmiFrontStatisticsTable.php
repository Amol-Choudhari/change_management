<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
class DmiFrontStatisticsTable extends Table {
	
	var $name = "DmiFrontStatistics";
	 var $useTable = 'dmi_front_statistics';
	  public $primaryKey='id';
	  
}
?>