<?php

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;

class MCommodityCategoryTable extends Table{

	var $name = "MCommodityCategory";
	var $useTable = "MCommodityCategory";


		public function	getCategoryName($id){
			return $detail = $this->find('all')->select(['category_name'])->where(['category_code IS' => $id])->first();
		}

}

?>
