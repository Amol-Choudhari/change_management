<?php
namespace app\Controller\Component;
use Cake\Controller\Controller;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;

class ProgressbarComponent extends Component {
    public $components= array('Session','Customfunctions');
    public $controller = null;
    public $session = null;

    public function initialize(array $config): void{
            parent::initialize($config);
            $this->Controller = $this->_registry->getController();
            $this->Session = $this->getController()->getRequest()->getSession();
    }

    public function formsProgressBarStatus($sections,$customer_id){

        $progressBarSections = array();

        foreach($sections as $each_section){

            $model_name = $each_section['section_model'];
            $section_id = $each_section['section_id'];
            $section_model = TableRegistry::getTableLocator()->get($model_name);
            $section_form_details = $section_model->sectionFormDetails($customer_id);
           
            $section_form_status = $section_form_details[0]['form_status'];
            $section_customer_reply = $section_form_details[0]['customer_reply'];
            $section_current_level = $section_form_details[0]['current_level'];
            $section_mo_comment = $section_form_details[0]['mo_comment'];
            $section_ro_reply_comment = $section_form_details[0]['ro_reply_comment'];
            $section_delete_mo_comment = $section_form_details[0]['delete_mo_comment'];

            $progressBarSections[] = array($section_id,$section_form_status,$section_customer_reply,$section_current_level,
										   $section_mo_comment,$section_ro_reply_comment,$section_delete_mo_comment);
        }

        $payment_table = $sections[0]['payment_section'];

		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
        if($payment_table != ""){
            $payment = TableRegistry::getTableLocator()->get($payment_table);
            $payment_status = $payment->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition),'order'=>'id DESC'))->first();

            if(empty($payment_status)){  $payment_status['payment_confirmation'] = ''; }
            $progressBarSections[] = array('payment',$payment_status['payment_confirmation'],null,null,null,null,null);
        }
		//print_r($progressBarSections); exit;
        return $progressBarSections;
    }

	public function inspectionProgressBarStatus($sections,$customer_id){

		$progressBarSections = array();
		foreach($sections as $each_section){

			$model_name = $each_section['section_model'];
            $section_id = $each_section['section_id'];
			$section_model = TableRegistry::getTableLocator()->get($model_name);
            $section_form_details = $section_model->sectionFormDetails($customer_id);
			$section_form_status = $section_form_details[0]['form_status'];
			$section_referred_back = "";
			$section_reply = "";

			if(isset($section_form_details[0]['referred_back_comment'])){
				$section_referred_back = $section_form_details[0]['referred_back_comment'];
			}
			if(isset($section_form_details[0]['io_reply'])){
				$section_reply = $section_form_details[0]['io_reply'];
			}

			$progressBarSections[] = array($section_id,$section_form_status,$section_referred_back,$section_reply);

		}

		return $progressBarSections;
	}
}
