<?php

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Utility\Hash;
use Cake\Datasource\ConnectionManager;

class NswsController extends AppController {

    var $name = 'Nsws';

    public function initialize(): void {

        parent::initialize();
        //Load Components
        $this->loadComponent('Createcaptcha');
        $this->loadComponent('Customfunctions');
        $this->loadComponent('Authentication');
        //Set Helpers
        $this->viewBuilder()->setHelpers(['Form', 'Html', 'Time']);
    }


    //Before Filter
    public function beforeFilter($event) {

        parent::beforeFilter($event);
	}
	
	public function primApplRegViaNsws(){
			
		$this->layout = false;
		$this->autoRender = false;
		
		if($this->request->is('post')){

			$reqdata = $this->request->getData();
			if(!empty($reqdata['InvestorSWSId']) && !empty($reqdata['f_name'])
				&& !empty($reqdata['l_name']) && !empty($reqdata['email'])
				&& !empty($reqdata['mobile'])){

				$investorId = $reqdata['InvestorSWSId'];
				
				//check if investor id is already present, if not register new primary id
				$this->loadModel('DmiNswsApplMappings');
				$getRecord = $this->DmiNswsApplMappings->find('all',array('fields'=>'id','conditions'=>array('investor_id'=>$investorId)))->first();
				if(empty($getRecord)){
					
					//create a primary applicant id
					
				}
				
				$response = array('200'=>'Success');
			}else{
				$response = array('401'=>'Unauthorized');
			}
			
			echo json_encode($response);
		}
		
		
	}

}

?>
