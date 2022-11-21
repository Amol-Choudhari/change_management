<?php

namespace app\Model\Table;

use Cake\ORM\Table;
use App\Model\Model;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

class MCommodityCategoryTable extends Table{

	var $name = "MCommodityCategory";
	var $useTable = "MCommodityCategory";


		public function	getCategoryName($id){
			return $detail = $this->find('all')->select(['category_name'])->where(['category_code IS' => $id])->first();
		}

	// getCategory
    // Description : This function will return the category name by id.
    // Author : Akash Thakre
    // Date : 03-06-2022

    public function getCategory($id) {
		
		if (!empty($id)) {
			$getCategory = $this->find('all')->select(['category_name'])->where(['category_code' => $id])->first();
			$detail = $getCategory['category_name'];
		} else {
			$detail = '';
		}

        return $detail;
    }


}

?>
