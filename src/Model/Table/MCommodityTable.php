<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;

class MCommodityTable extends Table{

	var $name = "MCommodity";
	var $useTable = "m_commodity";

	public function	getCommodityName($id){
		return $this->find('all')->select(['commodity_name'])->where(['commodity_code IS' => $id])->first();
	}

}

?>
