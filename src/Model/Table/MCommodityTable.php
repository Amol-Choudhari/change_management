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
	
    //getCommodity
    //Description : This is function will return the commodity name by id.
    //Author : Akash Thakre
    //Date : 03-06-2022

    public function getCommodity($id) {
        if (!empty($id)) {
            $getData = $this->find('all')->select(['commodity_name'])->where(['commodity_code' => $id])->first();
            $detail = $getData['commodity_name'];
        } else {
            $detail = '';
        }
        return $detail;
    }
}

?>
