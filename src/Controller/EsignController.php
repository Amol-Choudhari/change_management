<?php

namespace App\Controller;
use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\ORM\TableRegistry;
use App\Network\Http\HttpSocket;
use Cake\Utility\Xml;
use FR3D;
use Applicationformspdfs;//importing another controller class here
use TCPDF;
/**
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class EsignController extends AppController {

	var $name = 'Esign';
	
	public function initialize(): void
	{
		parent::initialize();
		$this->loadComponent('Customfunctions');
		$this->loadComponent('Mastertablecontent');
		$this->viewBuilder()->setHelpers(['Form','Html','Time']);
	}

	//This is for new applications esign call
	public function requestEsignOtp() {
		
		//taking current level
		$current_level = $this->Session->read('current_level');
		$this->Session->write('once_no',$_POST['once_no']);
		$once_card_no = $this->Session->read('once_no');
		echo $_SESSION['_Token']['key'];
		exit;
	}
		
	
	//This is for new applications esign call
	public function requestEsign() { 

		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');
		
		//if applicant proceed for esign, use username as customer id
		//added on 12-05-2021 by Amol
		if(empty($customer_id)){

			$customer_id = $this->Session->read('username');
			$this->Session->write('customer_id',$customer_id);//store as customer id in session
		}
		
		$flow_type = $this->Session->read('application_type');
		
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_flow_wise_tables = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('id IS'=>$flow_type)))->first();
		$Dmi_esign_status_tb = TableRegistry::getTableLocator()->get($Dmi_flow_wise_tables['esign_status']);
		
		$current_level = $this->Session->read('current_level');
		if(empty($current_level)){

			$current_level = 'applicant';
			$this->Session->write('current_level',$current_level);
		}

		$pdf_file_name = $this->Session->read('pdf_file_name');	

		//if response from ESP for esign request
		if($this->request->is('post')){
			
			//to get FORM base method response POST and convert into associative array
			////updated on 31-05-2021 for Form Based Esign method by Amol
		/*	$eSignResponse = simplexml_load_string($this->request->data['eSignResponse']);
			$getRespInJson = json_encode($eSignResponse);
			$getRespAssoArray = json_decode($getRespInJson,TRUE);
			
			//added on 01-10-2018 by Amol
			//save entry in temp esign table, which will deleted after this process done properly.
			$this->loadModel('DmiTempEsignStatuses');
			$this->DmiTempEsignStatuses->saveTempEsignRecord($customer_id,$pdf_file_name,$current_level,$flow_type);																			  
			//calling to set response signature on existing pdf.
			$esign_status = $this->sign_the_doc($getRespAssoArray,$pdf_file_name);
		*/
			$esign_status = 1;
			if ($esign_status == 1) {

				$this->Session->delete('pdf_file_name');//added to clear pdf file name from session, after esign					
					
				//calling final submit process now after signature appended in pdf.

				$url_to_redirect = 	null;
									
					if ($Dmi_esign_status_tb->saveEsignStatus()==1) { 
						
						$split_customer_id = explode('/',$customer_id);
						
						if ($current_level == 'applicant') {
							
							$this->redirect(['controller' => 'application', 'action' => 'application_final_submit']);
						
						} elseif ($current_level == 'level_2') {
							
							$this->redirect(['controller' => 'inspections', 'action' => 'report_final_submit']);
						
						} elseif ($current_level == 'level_3') {						
							
							$this->redirect(['controller' => 'inspections', 'action' => 'final_grant_call']);							
						
						} elseif ($current_level == 'level_4') {	

							$this->redirect(['controller' => 'inspections', 'action' => 'final_grant_call']);
						}
						
					} else {

						//else proceed
					}
					
				//this echo is used to redirect from CDAC to our Agarmark url.
				//after successfull OTP on CDAC				
				$this->redirect($url_to_redirect);//updated on 31-05-2021 for Form Based Esign method by Amol
				
			//added this else part on 11-06-2019 by Amol to show esign failed message	
			} else {
				
				$this->redirect('https://10.153.72.52/DMI/esign/esign_issue');//updated on 31-05-2021 for Form Based Esign method by Amol
			}
			
		}
		
	}
	
	
	//This is for Lab export JAT report esign call//on 11-11-2017
	/*public function jatReportRequestEsign() {
		//$this->autoRender = false;
		$message = '';
		$redirect_to = '';
		
		$customer_id = $this->Session->read('customer_id');
		$pdf_file_name = $this->Session->read('pdf_file_name');
		$once_card_no = $this->Session->read('once_card_no');
		$esign_otp = $this->Session->read('esign_otp');
		
		if ($esign_otp == 1234) { //temp. set 1234 as default OTP
		
	
			//This below code is to get OTP and request for esign on given document
		
			/*	$path = $_SERVER['DOCUMENT_ROOT']."/Esign_files/Esign/esignpdf.jar";//full path for jar file
			$basepath = $_SERVER['DOCUMENT_ROOT']."/Esign_files/Esign/";//base path of jks file

			if (file_exists($path)) {
					$aadhaar = $once_card_no;
					$ci = "abcdefghi";
					$filename = $pdf_file_name;
					$folder = $_SERVER['DOCUMENT_ROOT']."/writereaddata/DMI/temp/";
					$aspid = "NICP-900";
					$transactionid="1235abcd";
					$esignRequesturl="https://196.1.113.253/esignlevel1/1.0/signdoc";
					$cert_alisealise_name="ngdrsrsanew16";
					$jksCert_password="ngdrs1";
					$cdaccert_path = $_SERVER['DOCUMENT_ROOT']."/Esign_files/Esign/cdaccert.txt";
					$jks_path = $_SERVER['DOCUMENT_ROOT']."/Esign_files/Esign/jks.txt";

					$message = exec('java -jar ' . $path . ' ' . $aadhaar . ' ' . $userid . ' ' . $esign_otp . ' ' . $ci . ' ' . $filename . ' ' . $basepath . ' ' . $folder.' '. $aspid.' '. $transactionid.' '.$esignRequesturl.' '.$cert_alisealise_name.' '.$jksCert_password.' '.$cdaccert_path.' '.$jks_path, $result);
		
		
			}*/
			
	/*		$this->Session->delete('esign_otp');//added to clear OTP from session, after esign
			$this->Session->delete('pdf_file_name');//added to clear pdf file name from session, after esign
			
			$esign_result = 'yes';//temp. assigned to yes
			if($esign_result == 'yes'){
				
				$this->loadModel('DmiApplicationEsignedStatuses');
				//check record in esign status table
				$check_esigned_record = $this->DmiApplicationEsignedStatuses->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();

				$esignStatusEntity = $this->DmiApplicationEsignedStatuses->newEntity(array(
			
					'id'=>$check_esigned_record['id'],
					'report_esigned'=>'yes',
					'application_status'=>'pending',
					'modified'=>date('Y-m-d H:i:s'),
				
				));
				
				if($this->DmiApplicationEsignedStatuses->save($esignStatusEntity)){
					
					$this->redirect(array('controller'=>'app','action'=>'jat_report_final_submit_call'));
				}
				
			
			}else{
				
				$message = 'Sorry.. Esign Authentication Failed, Please try again.';
				$redirect_to = $_SERVER['HTTP_REFERER'];
				//$this->view = '/Element/message_boxes';
			}
			
			
		}else{
			
			$message = 'Sorry.. Esign Authentication Failed, Please try again.';
			$redirect_to = $_SERVER['HTTP_REFERER'];
			//$this->view = '/Element/message_boxes';			
			
		}
		
		$this->set('message',$message);
		//$this->set('redirect_to',$redirect_to);
		
	}*/
	
	
	
	//to create session variable with esign OTP value
	public function setEsignOtpSession(){
		
		$this->autoRender = false; 
		$this->Session->write('esign_otp',$_POST['esign_otp']);
		//echo $_SESSION['_Token']['key'];
		//exit;
	}
	
	
	
	
	

//below methods are used to Authenticate Aadhar of user/Applicant.	
	
	//This is to request OTP for aadhar authentication //on 20-11-2017
	public function requestAadharOtp(){
	
		$once_card_no = $_POST['once_no'];
		
		//webservice to request OTP
		$atservices_wsdl = "https://dbtdacfw.gov.in/UIDAuthService.asmx?WSDL";
		$atservices_client = new SoapClient($atservices_wsdl);
		//$genotp = $atservices_client->GenOTP(array('uid'=>$once_card_no));//commented on 24-05-2018,added 2 new param. in function below.
		$genotp = $atservices_client->GenOTP(array('uid'=>$once_card_no,'schemecode'=>'Nagpur','key'=>'660662C5C301099ACB02633BDDD3E775'));
		
		//call function with response parameter to generate array
		$genotp_array = $this->objectToArray($genotp);		
		$this->Session->write('aadhar_txn_id',$genotp_array['rand']);
		
	
		echo $_SESSION['_Token']['key'];//echo token key to update on current form
		exit;
	}
	
	
	
	//To create session variable with aadhar OTP value //on 20-11-2017
	public function setAadharOtpSession(){
		
		$this->autoRender = false; 
		$this->Session->write('aadhar_otp',$_POST['aadhar_otp']);
		//echo $_SESSION['_Token']['key'];
		//exit;
	}	
	
	
	
	//This is to authenticate aadhar no //on 20-11-2017
	public function requestAadharAuthentication($once_card_no,$aadhar_otp){//added new parameter $aadhar_otp on 25-08-2018
		
		$this->Session = $this->Components->load('Session');//load Session component.
		//$aadhar_otp = $this->Session->read('aadhar_otp');
		$aadhar_txn_id = $this->Session->read('aadhar_txn_id');

	//	if($aadhar_otp == 1234){//temp. set 1234 as default OTP
		
			//webservice to request Aadhar authentication
			$atservices_wsdl = "https://dbtdacfw.gov.in/UIDAuthService.asmx?WSDL";
			$atservices_client = new SoapClient($atservices_wsdl);
			$auth_call = $atservices_client->OTPAuth(array('uid'=>$once_card_no,'OTP'=>$aadhar_otp,'txn'=>$aadhar_txn_id));
			
			//call function with response parameter to generate array
			$auth_result = $this->objectToArray($auth_call);

			$aadhar_authenticated = null;
			//if authentication successfull
			
			//this is old logic to check response, not working in current state
			/*if($auth_result['OTPAuthResult'] == 'Not Authenticated.'){
				
				$aadhar_authenticated = 'no';
				
			}elseif(str_replace(' ','',$auth_result['OTPAuthResult'])=='Result:Y,AuthenticationSucessfull.'){//trim spaces from output
				
				$aadhar_authenticated = 'yes';
			}*/
			
			//added on 09-10-2019 by Amol
			//new logic after response is updated from aadhar authentication
			//checking the first letter from response is 'y' or not
			if(substr($auth_result['OTPAuthResult'],0,1) == 'y'){
				
				$aadhar_authenticated = 'yes';
				
			}else{
				
				$aadhar_authenticated = 'no';
			}

			$this->Session->delete('aadhar_otp');//added to clear OTP from session, after authentication done

			if($aadhar_authenticated == 'yes'){

				/*		//if aadhar authenticated properly, get Aadhar token from 'info' field from response, show to user for future used
						//and store in DB for further logs.
						$once_token_id = $auth_result['info'];
						echo "<script>alert('Note: Your Aadhar/VID Token Id is:$once_token_id , Please Copy/Save this Token Id for future use. This Token Id can use on place of Aadhar/VID')</script>";
					
						//save token to DB
						$this->Dmi_once_token_detail->save(array(
							'user_id'=>$this->Session->read('username'),
							'once_token_id'=>$once_token_id,
							'created'=>date('Y-m-d H:i:s')));
				*/			
			
				return true;		
			}else{
				
				//saving log for failed attempts
				//added on 16-06-2020 by Amol, on suggestion from Tarun Sir to take logs
				$this->loadModel('DmiOnceTokenDetails');
				
				$onceTokenEntity = $this->DmiOnceTokenDetails->newEntity(array(
					'user_id'=>'Id not created',
					'once_token_id'=>null,
					'created'=>date('Y-m-d H:i:s'),
					'status'=>'Failed',
					'txn_id'=>$aadhar_txn_id
				));
				$this->DmiOnceTokenDetails->save($onceTokenEntity);
				
				return false;			
			}
			
	//	}else{

	//		return false;
	//	}
	
	}
	
	
	
	
	
	//to set session variable of esig or not when concent check box click on popup modal
	public function setEsignOrNot(){
		
		$this->autoRender = false; 
		$this->Session->write('with_esign',$_POST['with_esign']);
		
		//this condition added on 28-12-2021 by Amol, to create customer id session while applicant is esign
		if($_POST['with_esign']=='yes'){			
			$this->Session->write('customer_id',$_SESSION['username']);
		}
		exit;
	}
	
	
	
	
	
	


	//function to generate array from webservice response object
	//added on 10-04-2018 by amol
	public function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}		
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(array($this,__FUNCTION__), $d); //this is pattern to call function recursive in cakephp
		}
		else {
			// Return array
			return $d;
		}
	}
	
	

	
	//this function is created to create XML with signature to request esign OTP, called through ajax
	//if ajax call of this function properly responded with OTP on mobile, 
	//then it will redirect to CDAC server with CORS(Cross-Origin-Resourse-Sharing) functionality to validate OTP.
	//if OTP is successfull, then CDAC will redirected to our provided URL with proper session by CORS.
	public function createEsignXmlAjax(){
		
		$this->autoRender = false;
		$message = '';
		$redirect_to = '';

		$current_level = $this->Session->read('current_level');
		$pdf_file_name = $this->Session->read('pdf_file_name');
		//$once_card_no = $this->Session->read('once_card_no');
		//$esign_otp = $this->Session->read('esign_otp');
		
		//get aadhar no from session variable
		$once_card_no = $this->Session->read('once_no');//added on 26-03-2018 


		//removed tcpdf code from here to create pdf using imagik images, on 24-01-2020
		//Now created common TCPDF function 'call_tcpdf' in Appcontroller and replaced with Mpdf code
		//Now implementing signature content at the time of first pdf creation, fetch that pdf here to create hash for Xml.
		
		//get generated pdf to create hash
		$doc_path = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/'.$pdf_file_name;	
		
		//added new condition for replica allotment esign
		//on 30-07-2021 by Amol
		$ca_unique_no = $this->Session->read('ca_unique_no');
		$ren_esign_process = $this->Session->read('ren_esign_process');
		if(!empty($ca_unique_no)){			
			$response_action = 'replica_allot_esign';//for replica allotment letter
		}elseif(!empty($ren_esign_process)){
			$response_action = 'ren_grant_esign';//for renewal
		}else{
			$response_action = 'request_esign';//for new
		}
		
		$get_date = date('Y-m-d');
		$get_time = date('H:i:s'.'.000');
		$time_stamp = $get_date.'T'.$get_time; //formatting timestamp as required
		$txn_id = rand().time();
		$asp_id = 'DMIC-001';
		$document_hashed = hash_file('sha256',$doc_path);//create pdf hash		
		$response_url = 'https://10.153.72.52/DMI/esign/'.$response_action;

		if($current_level == 'level_2'){
			$doc_info = 'Report Final Submit';
			
		}elseif($current_level == 'level_3' || $current_level == 'level_4'){
			$doc_info = 'Application Final Grant';			
		}else{			
			$doc_info = 'Application Final Submit';			
		}

		require_once(ROOT . DS . 'vendor' . DS . 'xmldsign' . DS . 'src' . DS . 'Adapter' . DS . 'XmlseclibsAdapter.php');

		// "Create" the document.
		$xml = new \DOMDocument( "1.0", "ISO-8859-15" );

		// Create some elements.
		$xml_esign = $xml->createElement( "Esign" );
		$xml_docs = $xml->createElement( "Docs" );
		$xml_docs_input = $xml->createElement( "InputHash", $document_hashed );
		
		// Set the attributes for Esign tag
		$xml_esign->setAttribute( "ver", "2.1" );
		$xml_esign->setAttribute( "sc", "Y" );
		$xml_esign->setAttribute( "ts", $time_stamp );
		$xml_esign->setAttribute( "txn", $txn_id );
		//$xml_esign->setAttribute( "ekycMode", "U" );
		$xml_esign->setAttribute( "ekycIdType", "A" );
		$xml_esign->setAttribute( "ekycId", "" );
		$xml_esign->setAttribute( "aspId", $asp_id );
		$xml_esign->setAttribute( "AuthMode", "1" );				
		$xml_esign->setAttribute( "responseSigType", "pkcs7" );
		//$xml_esign->setAttribute( "preVerified", "n" );
		//$xml_esign->setAttribute( "organizationFlag", "n" );
		$xml_esign->setAttribute( "responseUrl", $response_url ); 
		
		// Set the attributes for InputHash tag
		$xml_docs_input->setAttribute( "id", "1" );
		$xml_docs_input->setAttribute( "hashAlgorithm", "SHA256" );
		$xml_docs_input->setAttribute( "docInfo", $doc_info );
		
		
		// Append the whole bunch inside
		$xml_docs->appendChild( $xml_docs_input );
		$xml_esign->appendChild( $xml_docs );

		$xml->appendChild( $xml_esign );

		$xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
		$xmlTool->setPrivateKey(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/agmarkonline.key'));
		$xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);

		$xmlTool->sign($xml);	
		$xml_string = $xml->saveXML(); 
		
		//save details in logs table
		$this->saveRequestLog(null,$this->Session->read('username'),$pdf_file_name,$current_level,$time_stamp,$txn_id,$asp_id,
											$document_hashed,$response_url,null,null);
		
		//updated on 31-05-2021 for Form Based Esign method
		$result_arr = array('xml'=>$xml_string,'txnid'=>$txn_id);
		
		echo json_encode($result_arr);
		exit;
	}
	
	
	
	//This function is created to append response signature on existing pdf doc.
	//created on 28-06-2018 by Amol
	public function signTheDoc($resp_array,$pdf_file_name){

		$resp_status = $resp_array['@attributes']['status'];//updated on 31-05-2021 for Form Based Esign method

		if($resp_status == 1){
			//Set signature on pdf process Starts here....				
			//file path to get existing pdf, signed it and write on the same place
			$pdf_path = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/'.$pdf_file_name;
			$cer_value = $resp_array['UserX509Certificate'];//updated on 31-05-2021 for Form Based Esign method
			$pkcs7_value = $resp_array['Signatures']['DocSignature'];//updated on 31-05-2021 for Form Based Esign method
			
			//to verify response called custom function
			//created & added on 11-06-2019 by Amol
			$verify_cdac_response = $this->verifyCdacResponse($resp_array);
			if($verify_cdac_response == true) {

				require_once(ROOT . DS .'vendor' . DS . 'tcpdf' . DS . 'tcpdf.php');
				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				
				$pdf->my_output($pdf_path,'F',$pdf_path,$cer_value,$pkcs7_value,true);
				
			}else{
				
				$resp_status = false;
			}
				
		}

		//update this response array to DB for log
		$last_insert_id = $this->Session->read('log_last_insert_id');									
		$this->updateResponseLog($last_insert_id,null,$resp_array);
		
		return $resp_status;
	}
	
	
	//check application granted or not
	//to set response url is for New or Renewal
	public function checkApplGranted(){
		
		//get customer id
		$customer_id = $this->Session->read('customer_id');
		if(empty($customer_id)){
			$customer_id = $this->Session->read('username');
			$this->Session->write('customer_id',$customer_id);
		}
		
		$this->loadModel('DmiGrantCertificatesPdfs');
		$check_record = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		if(!empty($check_record)){			
			return 1;
		}else{
			return 0;
		}
	}
	
	
	
	//this function is created to save request log in db
	//created on 03-07-2018
	public function saveRequestLog($id,$applicant_id,$pdf_file_name,$current_level,$ts,$txn_id,$asp_id,
											$doc_hash,$response_url,$response_one,$response_two){		
			
		$this->loadModel('DmiEsignRequestResponseLogs');
		
			if(empty($id)){
				$dataArray = array('request_by_user_id'=>$applicant_id,'pdf_file_name'=>$pdf_file_name,
					'current_level'=>$current_level,'time_stamp'=>$ts,'txn_id'=>$txn_id,'asp_id'=>$asp_id,
					'doc_hash_value'=>$doc_hash,'response_url'=>$response_url,'response_one'=>$response_one,
					'response_two'=>$response_two,'created'=>date('Y-m-d H:i:s'));
					
			}else{				
				$dataArray = array('id'=>$id,'request_by_user_id'=>$applicant_id,'pdf_file_name'=>$pdf_file_name,
					'current_level'=>$current_level,'time_stamp'=>$ts,'txn_id'=>$txn_id,'asp_id'=>$asp_id,
					'doc_hash_value'=>$doc_hash,'response_url'=>$response_url,'response_one'=>$response_one,
					'response_two'=>$response_two,'created'=>date('Y-m-d H:i:s'));
			}
		
			$dataEntity = $this->DmiEsignRequestResponseLogs->newEntity($dataArray); 
			
			$this->DmiEsignRequestResponseLogs->save($dataEntity);
			
			//get last id from table to update
			$getId = $this->DmiEsignRequestResponseLogs->find('all',array('fields'=>'id','conditions'=>array('pdf_file_name IS'=>$pdf_file_name),'order'=>'id desc'))->first();

		$log_last_insert_id = $getId['id'];
		$this->Session->write('log_last_insert_id',$log_last_insert_id);
		
	}
	
	//this function is created to update response(first & second) log in db
	//created on 03-07-2018
	public function updateResponseLog($id,$response_one,$response_two){
		
		$this->loadModel('DmiEsignRequestResponseLogs');
					
		if($response_one != null){
			
			//string representation of array
			$response_one = json_encode($response_one);
			
			$responseEntity = $this->DmiEsignRequestResponseLogs->newEntity(array(		
				'id'=>$id,'response_one'=>$response_one,'modified'=>date('Y-m-d H:i:s')
			));
			$this->DmiEsignRequestResponseLogs->save($responseEntity);
			
		}elseif($response_two != null){
			
			//string representation of array
			$response_two = json_encode($response_two); 
			
			$responseEntity = $this->DmiEsignRequestResponseLogs->newEntity(array(		
				'id'=>$id,'response_two'=>$response_two,'modified'=>date('Y-m-d H:i:s')
			));
			$this->DmiEsignRequestResponseLogs->save($responseEntity);
			
			$this->Session->delete('log_last_insert_id');
		}
											
	}

//this function is cretted on 04-05-2018
//to fetch first response from ajax post data and update in DB record
	public function update1stReponseAjax(){
		$this->autoRender = false;
		$resp_arr = $_POST['resp1_arr'];		
		$uid_token = $resp_arr['info'];
		
		//update response one in DB
		$last_insert_id = $this->Session->read('log_last_insert_id');									
		$this->updateResponseLog($last_insert_id,$resp_arr,null);
		
		//save UID token for user further use.
	/*	$this->Dmi_once_token_detail->save(array(
			'user_id'=>$this->Session->read('username'),
			'once_token_id'=>$uid_token		
		));
		*/
	}
	
	
	//created this function to fetch cdac response array and verify the signature
//on 11-06-2019 by Amol
	public function verifyCdacResponse($resp_array){
		
		//certificate file provided by CDAC
		$get_cdac_cert = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cdac_ssl_cert.pem');
		$split_string = explode('-----',$get_cdac_cert);//split string and get cert key string from it
		$cert_key_string = $split_string[2];
		 
		//signature attached with response 
		$resp_cdac_signature = $resp_array['Signature']['SignatureValue']; //updated on 31-05-2021 for Form Based Esign method
		//Certificate details attached with response
		$resp_cdac_cert = $resp_array['EsignResp']['Signature']['KeyInfo']['X509Data']['X509Certificate']; //no such value in form based response now 31-05-2021
		
		//remove white spaces and compare in condition
		if(preg_replace('/\s+/', '', $cert_key_string) == preg_replace('/\s+/', '', $resp_cdac_cert)){			
			return true;
		}else{
			return false;
		}
		return true;

	}

//created this function to show esign failed message redirect to home page
//on 11-06-2019 by Amol	
	public function esignIssue(){
		$message = '';
		$redirect_to = '';
		
		$message = 'Sorry.. Esign Failed, Please login again and try.';
		$redirect_to = '/';
		$this->view = '/Elements/message_boxes';
		
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);
	}
	
	
//below function is created on 08-06-2019 by Amol
//to create pdf xml for re esign the granted renewal with wrong vaild upto date
	public function createReEsignXmlAjax(){
		
		$this->Session->delete('re_esigning');//deleting here because its work is over.
		$this->autoRender = false;
		$message = '';
		$redirect_to = '';

		$current_level = $this->Session->read('current_level');
		$pdf_file_name = $this->Session->read('pdf_file_name');
		//$once_card_no = $this->Session->read('once_card_no');
		//$esign_otp = $this->Session->read('esign_otp');
		
		//get aadhar no from session variable
		$once_card_no = $this->Session->read('once_no');//added on 26-03-2018 

		
		//removed tcpdf code from here to create pdf using imagik images, on 24-01-2020
		//Now created common TCPDF function 'call_tcpdf' in Appcontroller and replaced with Mpdf code
		//Now implementing signature content at the time of first pdf creation, fetch that pdf here to create hash for Xml.
		
		
		//get generated pdf to create hash
		$doc_path = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/'.$pdf_file_name;

		$response_action = 'renewal_request_re_esign';//for renewal

		$get_date = date('Y-m-d');
		$get_time = date('H:i:s'.'.000');
		$time_stamp = $get_date.'T'.$get_time; //formatting timestamp as required
		$txn_id = rand().time();
		$asp_id = 'DMIC-001';
		$document_hashed = hash_file('sha256',$doc_path);//create pdf hash		
		$response_url = 'https://10.153.72.52/DMI/esign/'.$response_action;

		if($current_level == 'level_2'){
			$doc_info = 'Report Final Submit';
			
		}elseif($current_level == 'level_3' || $current_level == 'level_4'){
			$doc_info = 'Application Final Grant';
			
		}else{			
			$doc_info = 'Application Final Submit';
			
		}

		//included path to library function class
		require_once(ROOT . DS . 'vendor' . DS . 'xmldsign' . DS . 'src' . DS . 'Adapter' . DS . 'XmlseclibsAdapter.php');

		// "Create" the document.
		$xml = new \DOMDocument( "1.0", "ISO-8859-15" );

		// Create some elements.
		$xml_esign = $xml->createElement( "Esign" );
		$xml_docs = $xml->createElement( "Docs" );
		$xml_docs_input = $xml->createElement( "InputHash", $document_hashed );
		
		// Set the attributes for Esign tag
		$xml_esign->setAttribute( "ver", "2.1" );
		$xml_esign->setAttribute( "sc", "Y" );
		$xml_esign->setAttribute( "ts", $time_stamp );
		$xml_esign->setAttribute( "txn", $txn_id );
		//$xml_esign->setAttribute( "ekycMode", "U" );
		$xml_esign->setAttribute( "ekycIdType", "A" );
		$xml_esign->setAttribute( "ekycId", "" );
		$xml_esign->setAttribute( "aspId", $asp_id );
		$xml_esign->setAttribute( "AuthMode", "1" );				
		$xml_esign->setAttribute( "responseSigType", "pkcs7" );
		//$xml_esign->setAttribute( "preVerified", "n" );
		//$xml_esign->setAttribute( "organizationFlag", "n" );
		$xml_esign->setAttribute( "responseUrl", $response_url ); 
		
		// Set the attributes for InputHash tag
		$xml_docs_input->setAttribute( "id", "1" );
		$xml_docs_input->setAttribute( "hashAlgorithm", "SHA256" );
		$xml_docs_input->setAttribute( "docInfo", $doc_info );
		
		
		// Append the whole bunch inside
		$xml_docs->appendChild( $xml_docs_input );
		$xml_esign->appendChild( $xml_docs );

		$xml->appendChild( $xml_esign );

		$xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
		$xmlTool->setPrivateKey(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/agmarkonline.key'));
		$xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);

		$xmlTool->sign($xml);	
		$xml_string = $xml->saveXML(); 
		
		//updated on 31-05-2021 for Form Based Esign method
		$result_arr = array('xml'=>$xml_string,'txnid'=>$txn_id);
		
		echo json_encode($result_arr);
		exit;
	}	
	
	
	
	
//below function is created on 08-06-2019 by Amol
//To apply esign on pdf while esigning the renewal grant and move temp to main folder
public function renewalRequestReEsign(){ 

		$this->autoRender = false;
		$pdf_file_name = $this->Session->read('pdf_file_name');	

		//if response from ESP for esign request
		if($this->request->is('post')){
			
			//to get FORM base method response POST and convert into associative array
			//updated on 31-05-2021 for Form Based Esign method by Amol
		/*	$eSignResponse = simplexml_load_string($this->request->data['eSignResponse']);
			$getRespInJson = json_encode($eSignResponse);
			$getRespAssoArray = json_decode($getRespInJson,TRUE);
																					  
			//calling to set response signature on existing pdf.
			$esign_status = $this->signTheDoc($getRespAssoArray,$pdf_file_name);
		*/
			$esign_status = 1;	
			//enter re-esign log in log table
			$this->loadModel('DmiReEsignGrantLogs');
			$customer_id = $this->Session->read('customer_id');
			
			//as per distributed folder structure, get folder name as per application to store pdf
			//on 04-10-2021 by Amol
			$folderName = $this->Customfunctions->getFolderName($customer_id);
			
			$resignLogsEntity = $this->DmiReEsignGrantLogs->newEntity(array(
				'customer_id'=>$customer_id,
				're_esigned_by'=>$this->Session->read('username'),
				'reason_to_re_esign'=>$this->Session->read('reason_to_re_esign'),
				'created'=>date('Y-m-d H:i:s')
			
			));
			
			$this->DmiReEsignGrantLogs->save($resignLogsEntity);
			
			//update master table
			$this->LoadModel('DmiApplAddedForReEsigns');
			$date1 = date('Y-m-d H:i:s');
			$this->DmiApplAddedForReEsigns->updateAll(array('re_esign_status' => "Re_Esigned",'modified'=>"$date1"),array('customer_id IS' => $customer_id));
			
			
			$main_domain_url = 'https://10.153.72.52/DMI/';
			$url_to_redirect = 	$main_domain_url.'hoinspections/grant_certificates_list';
			$this->Session->delete('pdf_file_name');
			$this->Session->delete('re_esigning');
			$this->Session->delete('re_esign_grant_date');
			$this->Session->delete('application_type');
			
			$source = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/';
			$destination = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/certificates/'.$folderName.'/';
			
			//Renaming the existing grant pdf file for backup, bcoz after moving it will be repalced
			rename($destination.$pdf_file_name,$destination.'Old-'.$pdf_file_name);
			
			$objMoveFile = new ApplicationformspdfsController();//creating object for class of another controller
			$objMoveFile->moveFile($pdf_file_name,$source,$destination);

			$this->redirect($url_to_redirect);//updated on 31-05-2021 for Form Based Esign method by Amol

		}
			
	}


	//This will be used when Chemist approve the replica allotment and esign the letter
	//created on 30-07-2021 by Amol
	public function replicaAllotEsign(){ 

		$this->autoRender = false;

		$current_level = 'Chemist';
		$this->Session->write('current_level',$current_level);

		$pdf_file_name = $this->Session->read('pdf_file_name');	

		//if response from ESP for esign request
		if($this->request->is('post')){

			$postData = $this->request->getData();
			
			//to get FORM base method response POST and convert into associative array
			////Form Based Esign method by Amol
		/*	$eSignResponse = simplexml_load_string($postData['eSignResponse']);
			$getRespInJson = json_encode($eSignResponse);
			$getRespAssoArray = json_decode($getRespInJson,TRUE);
																					  
			//calling to set response signature on existing pdf.			
			$esign_status = $this->signTheDoc($getRespAssoArray,$pdf_file_name);
		*/
			$esign_status = 1;
			if($esign_status == 1){

				//$this->Session->delete('pdf_file_name');//added to clear pdf file name from session, after esign					
					
				//calling final submit process now after signature appended in pdf.

				$main_domain_url = 'https://10.153.72.52/DMI/';
				$url_to_redirect =	$main_domain_url.$_SESSION['replica_for'].'/after_replica_allotment_esigned';				
					
				//this echo is used to redirect from CDAC to our Agarmark url.
				//after successfull OTP on CDAC				
				$this->redirect($url_to_redirect);//for Form Based Esign method by Amol
			
			//by Amol to show esign failed message	
			}else{
				
				$this->redirect('https://10.153.72.52/DMI/esign/esign_issue');//for Form Based Esign method by Amol
			}
			
		}
		
	}
	
	
	//This function created to esign renewal certificate, which was already grant on DDO approval
	//to avoid all other process, just esign the document, and move to main folder
	//added on 12-10-2021 by Amol
	public function renGrantEsign() { 

		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');

		//change the renewal final submit last status to approved and level_3 from pending level_1 again 
		//the status was change to generate the grant pdf propery and reverted here, as DDO already granted renewals now.
		//and reverted created date with modified date, as need to be before grant record date
	/*	$this->loadModel('DmiRenewalFinalSubmits');
		$getLastId = $this->DmiRenewalFinalSubmits->find('all',array('fields'=>array('id','modified'),'conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
		$this->DmiRenewalFinalSubmits->updateAll(array('status'=>'approved','current_level'=>'level_3','created'=>$getLastId['modified']),array('id'=>$getLastId['id']));
		*/

		$flow_type = 2;
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_flow_wise_tables = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('id IS'=>$flow_type)))->first();
		$Dmi_esign_status_tb = TableRegistry::getTableLocator()->get($Dmi_flow_wise_tables['esign_status']);
		$grant_pdf = TableRegistry::getTableLocator()->get($Dmi_flow_wise_tables['grant_pdf']);

		$current_level = $this->Session->read('current_level');
		$pdf_file_name = $this->Session->read('pdf_file_name');	

		//if response from ESP for esign request
		if($this->request->is('post')){
			
			//to get FORM base method response POST and convert into associative array
			////updated on 31-05-2021 for Form Based Esign method by Amol
		/*	$eSignResponse = simplexml_load_string($this->request->data['eSignResponse']);
			$getRespInJson = json_encode($eSignResponse);
			$getRespAssoArray = json_decode($getRespInJson,TRUE);
			
			//added on 01-10-2018 by Amol
			//save entry in temp esign table, which will deleted after this process done properly.
			$this->loadModel('DmiTempEsignStatuses');
			$this->DmiTempEsignStatuses->saveTempEsignRecord($customer_id,$pdf_file_name,$current_level,$flow_type);																			  
			//calling to set response signature on existing pdf.
			$esign_status = $this->sign_the_doc($getRespAssoArray,$pdf_file_name);
		*/
			$esign_status = 1;
			if ($esign_status == 1) {				
							
				if ($Dmi_esign_status_tb->saveEsignStatus()==1) { 
					
					//after renewal certificate esigned move file to main folder
					//move esigned file from temp folder to files folder
					$file_name = $pdf_file_name;
					$source = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/';

					//as per distributed folder structure, get folder name as per application to store pdf
					$folderName = $this->Customfunctions->getFolderName($customer_id);

					$destination = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/certificates/'.$folderName.'/';
					
					//calling custome function to move file
					$applPdfObj = new ApplicationformspdfsController();
					if($applPdfObj->moveFile($file_name,$source,$destination)==1){

						//get max id to update record grant record
						$getGrantId = $grant_pdf->find('all',array('fields'=>'id','conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
						$grantTableId = $getGrantId['id'];
						$filePath = '/writereaddata/DMI/certificates/'.$folderName.'/'.$file_name;

						//updated record in grant table
						$grant_pdf->updateAll(array('user_email_id'=>$this->Session->read('username'),'pdf_file'=>$filePath,'modified'=>date('Y-m-d H:i:s')),array('id'=>$grantTableId));
						
						//updated the record from table to maintain the DDO/PAO renewal provisional grant logs
						//added on 18-10-2021 by Amol
						$DmiProvGrantLogs = TableRegistry::getTableLocator()->get('DmiGrantProvCertificateLogs');
						$getProvGrantId = $DmiProvGrantLogs->find('all',array('fields'=>'id','conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
						$provGrantTableId = $getProvGrantId['id'];

						//updated record in provisional grant table
						$DmiProvGrantLogs->updateAll(array('status'=>'esigned','modified'=>date('Y-m-d H:i:s')),array('id'=>$provGrantTableId));

						//clear record from temp esign table
						$this->DmiTempEsignStatuses->DeleteTempEsignRecord($customer_id);

						//delete sessions created in this process
						$this->Session->delete('customer_id');
						$this->Session->delete('pdf_file_name');
						$this->Session->delete('current_level');
						$this->Session->delete('ren_esign_process');

						$this->redirect('/hoinspections/redirectGrantedApplications/2');
					}
					
				}
				
			//added this else part on 11-06-2019 by Amol to show esign failed message	
			} else {

				//delete sessions created in this process
				$this->Session->delete('customer_id');
				$this->Session->delete('pdf_file_name');
				$this->Session->delete('current_level');
				$this->Session->delete('ren_esign_process');
				
				$this->redirect('https://10.153.72.52/DMI/esign/esign_issue');//updated on 31-05-2021 for Form Based Esign method by Amol
			}
			
		}
		
	}
	
}
