<?php

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;

class DmiChemistFinalReportsTable extends Table
{

    var $name = "DmiChemistFinalReports";

    public function siteinspectionFinalReportStatus($customer_id){

        $CustomersController = new CustomersController;
        $grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);

        $final_status = array();
        //print_r($customer_id);exit;
        $final_status_id_list = $this->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->toArray();

        if($final_status_id_list){
            $final_reply_status = $this->find('all',array('conditions'=>array('id'=>max($final_status_id_list))))->first();
            $final_status = $final_reply_status;
        }
        return $final_status;
    }
}
?>
