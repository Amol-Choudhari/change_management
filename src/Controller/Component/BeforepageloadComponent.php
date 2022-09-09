<?php
//Note: All $this are converted to $this->Controller in this component. on 11-07-2017 by Amol
//To access the properties of main controller used initialize function.
namespace app\Controller\Component;
use Cake\Controller\Controller;
use Cake\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;
use Cake\Http\ServerRequest;

class BeforepageloadComponent extends Component {


	public $components= array('Session','Authentication');
	public $controller = null;
	public $session = null;

	// The other component your component uses
	public function initialize(array $config): void{

		parent::initialize($config);
		$this->Controller = $this->_registry->getController();
		$this->Session = $this->getController()->getRequest()->getSession();
		//public $components = ['Customfunctions'];
	}


	//This method is used to update logout time in dmi_customer_logs or dmi_user_logs table on every request for current logged in person.
	public function setLogoutTime() {
		
		//Load Models in Component
		$DmiUserLogs = TableRegistry::getTableLocator()->get('DmiUserLogs');
		$DmiCustomerLogs = TableRegistry::getTableLocator()->get('DmiCustomerLogs');
		
		if ($this->Session->read('username') != null) {

			$username_id = $this->Session->read('username');

			//$proper_email = Validation::email($username_id);// cake email validation

			if (filter_var($username_id, FILTER_VALIDATE_EMAIL)) {

				// update user logs table
				$find_id_list = $DmiUserLogs->find('list', array('valueField'=>'id','conditions'=>array('email_id IS'=>$username_id)))->toList();

				if (!empty($find_id_list)) {
					
					$find_max_id = $DmiUserLogs->find('all', array('fields'=>'id','conditions'=>array('id'=>max($find_id_list))))->first();
					$max_id = $find_max_id['id'];
					$DmiUserLogsEntity = $DmiUserLogs->newEntity(array('id'=>$max_id,'time_out'=>date('H:i:s')));
					$DmiUserLogs->save($DmiUserLogsEntity);
				}

			} else {

				// update customer logs table
				$find_id_list = $DmiCustomerLogs->find('list', array('valueField'=>'id','conditions'=>array('customer_id IS'=>$username_id)))->toList();

				if (!empty($find_id_list)) {

					$find_max_id = $DmiCustomerLogs->find('all', array('fields'=>'id','conditions'=>array('id'=>max($find_id_list))))->first();
					$max_id = $find_max_id['id'];
					$DmiCustomerLogsEntity = $DmiCustomerLogs->newEntity(array('id'=>$max_id,'time_out'=>date('H:i:s')));
					$DmiCustomerLogs->save($DmiCustomerLogsEntity);

				}
			}

		}

	}


	
	//This method is used to set and display site menus from database
	public function set_site_menus() {

		//Load Models in Component
		$Dmi_menu = TableRegistry::getTableLocator()->get('DmiMenus');
		$Dmi_page = TableRegistry::getTableLocator()->get('DmiPages');

		// top menu
		$menus = $Dmi_menu->find('all', array('order'=>array('order_id'=>'Asc'), 'conditions'=>array('position'=>'top','delete_status IS NULL')))->toArray();
		$this->Controller->set('menus', $menus);

		$submenus = $Dmi_menu->find('all', array('order'=>array('order_id'=>'Asc'), 'conditions'=>array('position'=>'top', 'NOT' =>array('parent'=> 0))))->toArray();
		$this->Controller->set('submenus', $submenus);

		// Side menu
		$current_date = date('Y-m-d');

		$publish_pages_id = $Dmi_page->find('list',array('fields'=>'id', 'conditions'=>array('AND'=>array('publish_date <='=>$current_date,'archive_date >='=>$current_date),'status'=>'publish')))->toArray();

		$page_condition = array('link_id'=>$publish_pages_id,'position'=>'side','OR'=>array('delete_status'=>null,'delete_status ='=>'no'));

		$external_condition = array( );

		//getQuery updated on 09-10-2017 by Amol
		$sidemenus = $Dmi_menu->find('all', array('order'=>array('order_id'=>'Asc'), 'conditions'=>array('OR'=>array('link_id'=>$publish_pages_id, 'link_type'=>'external'),'position'=>'side','OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->toArray();
		$this->Controller->set('sidemenus', $sidemenus);

		// bottom menu
		//getQuery updated on 09-10-2017 by Amol
		$bottommenus = $Dmi_menu->find('all', array('order'=>array('order_id'=>'Asc'), 'conditions'=>array('OR'=>array('link_id'=>$publish_pages_id, 'link_type'=>'external'),'position'=>'bottom', 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->toArray();
		$this->Controller->set('bottommenus', $bottommenus);


		// Display Single page from database
		if ($this->getController()->getRequest()->getParam('controller')=='Pages' && $this->getController()->getRequest()->getAttribute("here") != $this->getController()->getRequest()->getAttribute("webroot")) {
			
			if ($this->getController()->getRequest()->getQuery('$type') == 'page') {

				$pagetype = $this->getController()->getRequest()->getQuery('$type');
				$pageid = $this->getController()->getRequest()->getQuery('$page');
				$checkpageid = $Dmi_page->find('all',array('fields'=>'id', 'conditions'=> array('id IS' => $pageid)))->first();
			
				if ($checkpageid['id'] != '') {

					$pagecontents = $Dmi_page->find('all', array('conditions'=>array('id IS'=>$pageid)))->first();
					$this->Controller->set('pagecontents', $pagecontents);

					$meta_keyword = $pagecontents['meta_keyword'];
					$meta_description = $pagecontents['meta_description'];

					$pagetitle = $pagecontents['title'];
					$pagedata = $pagecontents['content'];

					$this->Controller->set('meta_keyword', $meta_keyword);
					$this->Controller->set('meta_description', $meta_description);
					$this->Controller->set('pagetitle', $pagetitle);
					$this->Controller->set('pagedata', $pagedata);
				
				} else {
					$this->Flash->error('Requested page not found');
				}

			} elseif ($this->getController()->getRequest()->getQuery('$type') == 'file') {

				$pagetype = $this->getController()->getRequest()->getQuery('$type');
				$pageid = $this->getController()->getRequest()->getQuery('$page');
				$checkpageid = $this->Controller->File->find('all',array('fields'=>'id', 'conditions'=> array('id IS' => $pageid)))->first();

				if ($checkpageid['File']['id'] != '') {
					echo "this will open file";
				} else {
					$this->Flash->error('Requested page not found');
				}
			
			} elseif ($this->getController()->getRequest()->getQuery('$type') == 'external') {

					$pagetype = $this->getController()->getRequest()->getQuery('$type');
					$menu_id = $this->getController()->getRequest()->getQuery('$menu');
					$ext_link_menu = $Dmi_menu->find('all', array('fields'=>'external_link', 'conditions'=>array('id IS'=>$menu_id)))->first();
					$this->Controller->set('ext_link_menu', $ext_link_menu);
					//	$ext_link_result = $ext_link_menu['Dmi_menu'];
					$ext_link = $ext_link_menu['external_link'];
					$this->Controller->redirect($ext_link);
					//exit();
			} elseif ($this->getController()->getRequest()->getQuery('$type') == 'home') {

				$this->Controller->redirect(array('controller'=>'pages', 'action'=>'home'));
				//exit();
			}

		}

	}



	// Display Footer content from database
	public function get_footer_content() {

		//initialize model in component
		$Dmi_page = TableRegistry::getTableLocator()->get('DmiPages');
		$footer_content = $Dmi_page->find('all',array('fields'=>'content', 'conditions'=>array('id'=>17)))->first()->toArray();
		$this->Controller->set('footer_content',$footer_content['content']);
	}


	//method to send alert for renewal if any application enters in valid renewal period. //added on 03-02-2018 by Amol
	public function send_renewal_alert() {

		//initialize model in component
		$DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');
		$DmiSmsEmailTemplates = TableRegistry::getTableLocator()->get('DmiSmsEmailTemplates');

		//get all granted applications details
		$all_granted_applications = $DmiGrantCertificatesPdfs->find('all',array('fields'=>'customer_id','group'=>'customer_id'))->toArray();

		foreach ($all_granted_applications as $each) {
			
			$customer_id = $each['customer_id'];
			$each_application_list = $DmiGrantCertificatesPdfs->find('list',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
			
			if (!empty($each_application_list)) {

				$last_grant_details = $DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('id'=>max($each_application_list))))->first();
				$last_grant_date = $last_grant_details['date'];

				//get certificate valid upto date
				$certificate_valid_upto = $this->Controller->getCertificateValidUpToDate($customer_id,$last_grant_date);

				//check application is in valid renewal period now
				$valid_for_renewal = $this->Controller->checkApplicantValidForRenewal($customer_id);

				if ($valid_for_renewal == 'yes') {
					//send renewal alert
					//$DmiSmsEmailTemplates->sendMessage(31, $customer_id);
				}
			}
		}
	}


	// Check the user login time //  Done by Pravin 24/3/2018
	public function current_session_status() {

		$username = $this->Session->read('username');
		$login_time = $this->Controller->Session->read('last_login_time_value');


		// compare user current session id, Done by Pravin Bhakare 12-11-2020
		$username = $this->Controller->Session->read('username');
		$countspecialchar = substr_count($username,"/");
		if($countspecialchar == 1){ $userType = 'dp'; $logTable = 'DmiCustomerLogs'; $conditions = array('customer_id IS' => $username);}
		if($countspecialchar == 2){ $userType = 'ch'; $logTable = 'DmiChemistLogs'; $conditions = array('customer_id IS' => $username);}
		if($countspecialchar == 3){ $userType = 'df'; $logTable = 'DmiCustomerLogs'; $conditions = array('customer_id IS' => $username);}
		if($countspecialchar == 0){ $userType = 'du'; $logTable = 'DmiUserLogs'; $conditions = array('email_id IS' => $username);}
		
		$logTable = TableRegistry::getTableLocator()->get($logTable);

		$Dmi_login_status = TableRegistry::getTableLocator()->get('DmiLoginStatuses'); //initialize model in component
		
		$currLoggedin = $Dmi_login_status->find('all',array('conditions'=>array('user_id IS'=>$username,'user_type'=>$userType),'order'=>'id'))->first();

		$dateMod = date('H:i:s');
		
		if(!empty($currLoggedin)){
			$browser_session_d = $currLoggedin['sessionid'];
			if($this->Controller->Session->read('browser_session_d') !=''){
				if($browser_session_d != $this->Controller->Session->read('browser_session_d')){
					
					//get last id to update //added to logout on session expires
					//on 29-03-2022 by Amol
					$getId = $logTable->find('all',array('fields'=>'id','conditions'=>$conditions,'order'=>'id desc'))->first();
					$logTable->updateAll(array('time_out' => "$dateMod"),array('id' => $getId['id']));
					
					$this->Authentication->browserLoginStatus($username,null);
					$this->Controller->Session->destroy();	
				}
			}
		}

		if (!empty($login_time)) {

			if (time() - $login_time > 1200) {

				//$this->Controller->Authentication->browserLoginStatus($username,null);
				
				//get last id to update //added to logout on session expires
				//on 29-03-2022 by Amol
				$getId = $logTable->find('all',array('fields'=>'id','conditions'=>$conditions,'order'=>'id desc'))->first();
				$logTable->updateAll(array('time_out' => "$dateMod"),array('id' => $getId['id']));
				$this->Authentication->browserLoginStatus($username,null);
					
				$this->Controller->Session->destroy();
				echo "Your session has timed out due to inactivity";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php "Again";
				exit;

			} else {

				$current_time = time();
				$this->Controller->Session->write('last_login_time_value',$current_time);
			}

		}

	}


	
	// Function for "Total visitor" and "Today visitor" count// Done By Pravin 26/04/2018
	public function fetch_visitor_count() {

		//initialize model in component
		$Dmi_visitors = TableRegistry::getTableLocator()->get('DmiVisitorCounts');

		$current_date = date('d-m-Y');

		//$fetch_count_ids = $Dmi_visitors->find('list',array('fields'=>'id'))->toArray();
		$fetch_count = $Dmi_visitors->find('all',array('order'=>'id desc'))->first();	
		
		if (!empty($fetch_count)) {
			
			$convert_current_date = strtotime($current_date);
			$convert_table_date  = explode(' ',$fetch_count['created']);
			$explode_table_date = strtotime(str_replace('/','-',$convert_table_date[0]));
		
		} else {

			$fetch_count = null;
		}

		$this->Controller->set('fetch_count',$fetch_count);

		if (!empty($fetch_count)) {
			
			if (!isset($_SESSION['views'])) {

				// Compare current date with result date of getQuery, 
				//If result date is less than current date then insert new entry into table
				// Otherwise update entery into table
				if ($convert_current_date == $explode_table_date) {

					//if session is not set means if user comes first time or if session expires then.
					$_SESSION['views'] = 1;
					$pre_visitor=$fetch_count['visitor'];
					$total_visitor=$_SESSION['views']+$pre_visitor;
					$today_visitor = $fetch_count['t_visitor'];
					$total_today_visitor = $_SESSION['views']+$today_visitor;

					//Save the data
					$Dmi_visitor = $Dmi_visitors->newEntity(['id'=>$fetch_count['id'],'visitor'=>$total_visitor,'t_visitor'=>$total_today_visitor]);
					$Dmi_visitors->save($Dmi_visitor);

				} elseif ($convert_current_date > $explode_table_date) {

					$_SESSION['views'] = 1;
					$pre_visitor=$fetch_count['visitor'];
					$total_visitor=$_SESSION['views']+$pre_visitor;

					//save the data
					$Dmi_visitor = $Dmi_visitors->newEntity(['visitor' => $total_visitor,'t_visitor' => $_SESSION['views'],'created'=>date('Y-m-d H:i:s')]);
					$Dmi_visitors->save($Dmi_visitor);
				}
			}

		} else {
			$Dmi_visitor = $Dmi_visitors->newEntity(['visitor' =>1]);
			$Dmi_visitors->save($Dmi_visitor);

		}
	
	}


	//Added to get Home page contents // Created by pravin 28/4/2018
	public function home_page_content() {

		//initialize model in component
		$Dmi_home_page_content = TableRegistry::getTableLocator()->get('DmiHomePageContents');
		$fetch_home_page_content = $Dmi_home_page_content->find('all',array('order'=>'id'))->toArray();
		$this->Controller->set('home_page_content',$fetch_home_page_content);
	}


	//created this function on 31-05-2018 by Amol to to concent msg from DB.
	public function get_all_concent_messages() {

		//initialize model in component
		$Dmi_declaration_concent_message = TableRegistry::getTableLocator()->get('DmiDeclarationConcentMessages');
		$get_concent_msg = $Dmi_declaration_concent_message->find('all')->toArray();

		$esign_msg =null;
		$aadhar_auth_msg =null;

		foreach ($get_concent_msg as $each_msg) {
			
			if ($each_msg['concent_for']=='esign') {
				
				$esign_msg = $each_msg['message'];
			}
			
			if ($each_msg['concent_for']=='aadhar') {
				
				$aadhar_auth_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='ca_new') {

				$ca_new_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='ca_renewal') {

				$ca_renewal_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='ca_bevo_new') {

				$ca_bevo_new_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='ca_bevo_renewal') {

				$ca_bevo_renewal_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='printing_new') {

				$printing_new_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='printing_renewal') {

				$printing_renewal_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='lab_new') {

				$lab_new_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='lab_renewal') {

				$lab_renewal_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='io_report') {

				$io_report_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='cert_grant') {

				$cert_grant_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='cert_renewal_grant') {

				$cert_renewal_grant_msg = $each_msg['message'];
			}

			if ($each_msg['concent_for']=='esign_please_wait') {

				$esign_please_wait = $each_msg['message'];//added on 01/10/2018
			}

			if ($each_msg['concent_for']=='without_esign') {//added on 01/10/2018

				$without_esign = $each_msg['message'];
			}
		}

		//set this variable to use while dispalying the declaration concent on final submit/aadhar authentication/esigning
		$this->Controller->set(compact('esign_msg','aadhar_auth_msg','ca_new_msg','ca_renewal_msg','ca_bevo_new_msg',
									'ca_bevo_renewal_msg','printing_new_msg','printing_renewal_msg','lab_new_msg',
									'lab_renewal_msg','io_report_msg','cert_grant_msg','cert_renewal_grant_msg',
									'esign_please_wait','without_esign'));
	}


	//Common Function for the  Show Button
	public function showButtonOnSecondaryHome() {

		//Load model
		$DmiFinalSubmits = TableRegistry::getTableLocator()->get('DmiFinalSubmits');
		$DmiRenewalFinalSubmits = TableRegistry::getTableLocator()->get('DmiRenewalFinalSubmits');
		//Get Customer ID
		$customer_id = $this->Session->read('username');
		$final_submit_id = $DmiFinalSubmits->find('all', array('conditions' => array('customer_id IS' => $customer_id),'order'=>'id desc'))->first();
		$IsApproved='';
        
		if (!empty($final_submit_id)) {
            
			$show_button = 'Application Status';
			//get grant status		
			if ($final_submit_id['status']=='approved' && $final_submit_id['current_level']=='level_3') {
				$IsApproved='yes';
			}
		
        } else {
            $show_button = 'New Certification';
        }	
		
		//created and called function to check applicant is valid for renewal or not 
		$show_renewal_btn = $this->Controller->Customfunctions->checkApplicantValidForRenewal($customer_id);
		$show_renewal_button = '';
		if($show_renewal_btn=='yes'){
			$grantDateCondition = $this->Controller->Customfunctions->returnGrantDateCondition($customer_id);

			$list_renewal_final_submit_id = $DmiRenewalFinalSubmits->find('list', array('valueField' => 'id', 'conditions' => array('customer_id IS' => $customer_id, $grantDateCondition)))->toArray();

			if (!empty($list_renewal_final_submit_id)) {
				$show_renewal_button = 'Renewal Status';
			} else {
				$show_renewal_button = 'Renewal';
			}
		}
		
		$this->Controller->set('IsApproved',$IsApproved);
		$this->Controller->set('show_renewal_btn',$show_renewal_btn);
		$this->Controller->set('show_button', $show_button);
		$this->Controller->set('show_renewal_button', $show_renewal_button);

	}
	
	
	public function checkValidRequest(){
				
		//commented on 02-04-2021 as esign services was blocked on response
		
		/*	$validHostName = array('agmarkonline.dmi.gov.in','esignservice.cdac.in');
			$hostName = $_SERVER['HTTP_HOST'];
			if(!in_array($hostName,$validHostName)){
				$this->Controller->Session->destroy();
				echo "Something went wrong. ";?><a href="<?php echo $this->webroot;?>"> Please Login</a><?php "Again";					
				exit;
			}else{
				
				//new condition added on 27-03-2021 by Amol to bypass esign on response
				if(isset($_SERVER['HTTP_REFERER']) &&
					($_SERVER['HTTP_REFERER'] == 'https://esignservice.cdac.in/esign2.1' || 
					$_SERVER['HTTP_REFERER'] == 'https://esignservice.cdac.in/esign2.1/OTP')){
						
						//do nothing
				}else{
					// validated referere, Done by Pravin Bhakare 10-02-2021
					if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'],$hostName) == null){
						$this->Controller->Session->destroy();
						echo "Something went wrong. ";?><a href="<?php echo $this->webroot;?>"> Please Login</a><?php "Again";					
						exit;
					}
					
				}
				
			}*/
			
		}


	//To show notifications on applicant dashboard, on 02-12-2021
	public function showNotificationToApplicant(){ 
	
		if(preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $this->Session->read('username'), $matches) == 1){
			
			$customer_id = $this->Session->read('username');
			//for notificaion module on applicant dashboard
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();

			$appl_notifications = array();
			$i = 0;
			foreach($flow_wise_tables as $eachflow){
				
				//get final status of each type of flow as per applicant
				$finalSubmitTable = TableRegistry::getTableLocator()->get($eachflow['application_form']);
				
				$lastStatus = $finalSubmitTable->find('all',array('fields'=>array('status','current_level','modified'),'conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
				
				if(!empty($lastStatus)){
					$forStatus =null;
					if($lastStatus['status']=='approved' && $lastStatus['current_level']=='level_3'){					
						$forStatus = 'Granted';
					}elseif($lastStatus['status']=='referred_back' && $lastStatus['current_level']=='level_3'){
						$forStatus = 'Referred Back';							
					}
					
					if(!empty($forStatus)){
						
						$appl_notifications[$i]['on_date']=$lastStatus['modified'];
						$appl_notifications[$i]['link']='../application/applicationType/'.$eachflow['application_type'];
						
						$DmiApplicationTypes = TableRegistry::getTableLocator()->get('DmiApplicationTypes');
						$getApplTypeLabel = $DmiApplicationTypes->find('all',array('fields'=>'application_type','conditions'=>array('id'=>$eachflow['application_type'],'delete_status IS NULL')))->first();
						$applTypeLabel = $getApplTypeLabel['application_type'];
						$appl_notifications[$i]['message']=$applTypeLabel.' Certificate is '.$forStatus;
						
						$i = $i+1;
					}
				}
			}
			
			//get alloted replica/15 digit/e-code status for notifications
			//check allotment pdf table for each
			$DmiECodeAllotmentPdfs = TableRegistry::getTableLocator()->get('DmiECodeAllotmentPdfs');
			$checkpdf = $DmiECodeAllotmentPdfs->find('all',array('fields'=>array('id','modified'),'conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
			if(!empty($checkpdf)){
				$methodLink = 'alloted_e_code_list';
				$message = 'Requested Replica E-code is Alloted';
			}else{
				$Dmi15DigitAllotmentPdfs = TableRegistry::getTableLocator()->get('Dmi15DigitAllotmentPdfs');
				$checkpdf = $Dmi15DigitAllotmentPdfs->find('all',array('fields'=>array('id','modified'),'conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
				if(!empty($checkpdf)){
					$methodLink = 'alloted15_digit_list';
					$message = 'Requested Replica 15 Digit Code is Alloted';
				}else{
					$DmiReplicaAllotmentPdfs = TableRegistry::getTableLocator()->get('DmiReplicaAllotmentPdfs');
					$checkpdf = $DmiReplicaAllotmentPdfs->find('all',array('fields'=>array('id','modified'),'conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
					if(!empty($checkpdf)){
						$methodLink = 'replica_alloted_list';
						$message = 'Requested Replica Series is Alloted';
					}
				}
			}
			if(!empty($methodLink)){
				$appl_notifications[$i]['on_date']=$checkpdf['modified'];
				$appl_notifications[$i]['link']='../customers/'.$methodLink;
				$appl_notifications[$i]['message'] = $message;
			}
			
			$this->Controller->set('appl_notifications',$appl_notifications);
			
		}
	
	}


}


?>
