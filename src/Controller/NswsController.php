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

			$response = array('401'=>'Unauthorized');
			$reqdata = $this->request->getData();
			if(!empty($reqdata['InvestorSWSId']) && !empty($reqdata['f_name'])
				&& !empty($reqdata['l_name']) && !empty($reqdata['email'])
				&& !empty($reqdata['mobile'])){

				$investorId = $reqdata['InvestorSWSId'];
				
				//check if investor id is already present, if not register new primary id
				$this->loadModel('DmiNswsApplMappings');
				$getRecord = $this->DmiNswsApplMappings->find('all',array('fields'=>'id','conditions'=>array('investor_id'=>$investorId)))->first();
				if(empty($getRecord)){

					$this->loadModel('DmiCustomers');
					$Checkemailexist = $this->DmiCustomers->find('all', array('fields' => 'email', 'conditions' => array('email IS' => $reqdata['email'])))->first();
					if ($Checkemailexist == null) {
						
						$last_customer_id_query = $this->DmiCustomers->find('all', array('fields' => 'customer_id', 'order' => array('id desc')))->first();
						$last_customer_id = $last_customer_id_query['customer_id'];
                        $split = explode('/', $last_customer_id);
                        $splited_id = $split[0];
						
						$new_customer_id = ($splited_id + 1) . '/' . date('Y');
						
						$f_name = htmlentities($this->request->getData('f_name'), ENT_QUOTES);
						$l_name = htmlentities($this->request->getData('l_name'), ENT_QUOTES);
						$email = htmlentities($this->request->getData('email'), ENT_QUOTES);
						$mobile = htmlentities($this->request->getData('mobile'), ENT_QUOTES);
						$investor_id = htmlentities($this->request->getData('InvestorSWSId'), ENT_QUOTES);
						
						$email = base64_encode($email);//for email encoding

						$DmiCustomersEntity = $this->DmiCustomers->newEntity(array(
							'customer_id' => $new_customer_id,
							'f_name' => $f_name,
							'l_name' => $l_name,
							'email' => $email,
							'password' => '91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
							'mobile' => base64_encode($mobile),
							'created' => date('Y-m-d H:i:s'),
							'modified' => date('Y-m-d H:i:s')
						));

						if ($this->DmiCustomers->save($DmiCustomersEntity)) {

							$this->loadModel('DmiCustomersHistoryLogs');
							$DmiCustomersHistoryLogsEntity = $this->DmiCustomersHistoryLogs->newEntity(array(
								'customer_id' => $new_customer_id,
								'f_name' => $f_name,
								'l_name' => $l_name,
								'email' => $email,
								'password' => '91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
								'mobile' => base64_encode($mobile),
								'created' => date('Y-m-d H:i:s'),
								'modified' => date('Y-m-d H:i:s')
							));

							$this->DmiCustomersHistoryLogs->save($DmiCustomersHistoryLogsEntity);						
														
							//entry in NSWS appl mapping table
							$DmiNswsApplMappingsEntity = $this->DmiNswsApplMappings->newEntity(array(
							
								'investor_id'=>$investor_id,
								'primary_id'=>$new_customer_id,
								'created' => date('Y-m-d H:i:s'),
								'modified' => date('Y-m-d H:i:s')
							
							));
							$this->DmiNswsApplMappings->save($DmiNswsApplMappingsEntity);
							
						//	$this->Authentication->forgotPasswordLib('DmiCustomers', $email, $new_customer_id);
						//	$this->DmiSmsEmailTemplates->sendMessage(1, $new_customer_id);
						
							$response = array('200'=>'Success');
							
						}
					}
				}			
			}	
		//	echo json_encode($response);
			
			//Function to call API to get security token before calling CRF API
		//	if(!empty($response['200'])){
				$access_token = $this->getNswsSecurityToken();
				
				$this->fetchCrfAndRedirectUrl($access_token,$investorId);
		//	}
			
		}	
	}
	
	public function getNswsSecurityToken(){
		
		//make a CURL request with defined parameters
		//to get access security token, for further procedure
		$grant_type = 'password';
		$client_id = 'sws_state';
		$username = 'AQCMS';
		$password = 'AQCMS@123';
		$client_secret = '643790eb-2b2a-4187-8c43-54a663b840eb';
		
		$URL="https://sso-uat-nsws.investindia.gov.in/auth/realms/madhyam/protocol/openid-connect/token";
		// Create and initialize a new cURL resource
		$ch = curl_init();
		// Set URL to URL variable
		curl_setopt($ch, CURLOPT_URL,$URL);
		// Set URL HTTPS post to 1
		curl_setopt($ch, CURLOPT_POST, true);
		// Set URL HTTPS post field values
		$data = '{"grant_type":$grant_type,"client_id":$client_id,"username":$username,"password":$password,"client_secret":$client_secret}';
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		// Set URL return value to True to return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//set header
		$headers = array("Content-Type: application/x-www-form-urlencoded","Accept: application/json");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// The URL session is executed and passed to the browser
		$curl_output =curl_exec($ch);
		curl_close($ch);
		//var_dump($curl_output);
		
		$token = json_decode($curl_output);
		$token = $token['access_token'];
		
		return $token;
		
	}
	
	
	public function fetchCrfAndRedirectUrl($access_token,$swsId){
		
	//API call to fetch CRF/SRF	
	//using GET
		$URL="https://uat-nsws.investindia.gov.in/gateway/form-builder/caf/public/$swsId";
		// Create and initialize a new cURL resource
		$ch = curl_init();
		// Set URL to URL variable
		curl_setopt($ch, CURLOPT_URL,$URL);
		// Set URL return value to True to return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//set header
		$headers = array("Content-Type: application/json","Authorization: Bearer ".$access_token);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// The URL session is executed and passed to the browser
		$curl_output =curl_exec($ch);
		curl_close($ch);
		
		
	//API call to post redirect URL to Nsws
	//Using POST
		$departmentId = "D001";
		$licenseId = "M001_D001_A016";
		$ministryId = "M001";
		$redirectionUrl = "https://10.153.72.52/DMI/Nsws/invstrDashRedirect";
	
		$URL="https://uat-nsws.investindia.gov.in/gateway/form-builder/caf/redirection";
		// Create and initialize a new cURL resource
		$ch = curl_init();
		// Set URL to URL variable
		curl_setopt($ch, CURLOPT_URL,$URL);
		// Set URL HTTPS post to 1
		curl_setopt($ch, CURLOPT_POST, true);
		// Set URL HTTPS post field values
		$data = '{"departmentId":$departmentId,"licenseId":$licenseId,"ministryId":$ministryId,"redirectionUrl":$redirectionUrl,"stateId":$stateId,"swsId":$swsId}';
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		// Set URL return value to True to return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//set header
		$headers = array("Content-Type: application/json","Authorization: Bearer ".$access_token);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		// The URL session is executed and passed to the browser
		$curl_output =curl_exec($ch);
		curl_close($ch);
	}
	
	
	public function invstrDashRedirect(){
		
	}

}

?>
