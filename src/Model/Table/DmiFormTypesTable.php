<?php
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use Cake\ORM\TableRegistry;
	
class DmiFormTypesTable extends Table{
	
	var $name = "DmiFormTypes";

    public function getFormDesc($ftype){
       $det = $this->find()->select(['description'])->where(['form_type' => trim($ftype)])->first();
       return $det['description'];
    }

}

?>