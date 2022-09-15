<?php

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Utility\Hash;
use Cake\Datasource\ConnectionManager;

class CommonController extends AppController {

	var $name = 'Common';

	public function initialize(): void {

		parent::initialize();
		//Load Components
		$this->loadComponent('Createcaptcha');
		$this->loadComponent('Customfunctions');
		$this->loadComponent('Authentication');
		$this->loadComponent('Mastertablecontent');
		$this->loadComponent('Randomfunctions');
		//Set Helpers
		$this->viewBuilder()->setHelpers(['Form', 'Html', 'Time']);
	}


	//Before Filter
	public function beforeFilter($event) {

		parent::beforeFilter($event);
		$customer_last_login = $this->Customfunctions->customerLastLogin();
		$this->set('customer_last_login', $customer_last_login);

			//Show button on Side menu
		$this->Beforepageload->showButtonOnSecondaryHome();

		//created and called function to check applicant is valid for renewal or not
		$show_renewal_btn = $this->Customfunctions->checkApplicantValidForRenewal($this->Session->read('username'));
		$this->set('show_renewal_btn', $show_renewal_btn);

		$this->loadModel('DmiFirms');
		$is_already_granted = null;
		$get_is_already_granted = $this->DmiFirms->find('all', array('fields' => 'is_already_granted', 'conditions' => array('customer_id IS' => $this->Session->read('username'))))->first();

		if (!empty($get_is_already_granted)) {

			$is_already_granted = $get_is_already_granted['is_already_granted'];
		}

		$this->set('is_already_granted', $is_already_granted);

	}



	// Authenticate User
	// Description : This is method will set the Layout for the specific user type , if user is not authorized it will show the message.
	// #Contributer : Akash Thakre
	// DATE : 19-04-2022

	public function authenticateUser(){

		if ($this->Session->read('username') == null) {
			echo "Sorry You are not authorized to view this page..'<a href='../'>'Please login'</a>'";
			exit();

		} else {

			//checking primary applicant id pattern ex.102/2016
			if (preg_match("/^[0-9]+\/[0-9]+$/", $this->Session->read('username'), $matches) == 1) {

				$this->viewBuilder()->setLayout('corporate_customer');

			//checking secondary applicant id pattern ex.102/1/PUN/006
			} elseif (preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $this->Session->read('username'), $matches) == 1) {

				$this->viewBuilder()->setLayout('secondary_customer');

			//checking chemist user id pattern ex. CHM/21/1003    
			} elseif (preg_match("/^[CHM]+\/[0-9]+\/[0-9]+$/", $this->Session->read('username'), $matches) == 1) {

				$this->viewBuilder()->setLayout('chemist_home_layout');

			// checking the if Email User 
			} elseif ($this->isBase64Encoded($this->Session->read('username'))==true) {

				$this->viewBuilder()->setLayout('admin_dashboard');

			} else {

				echo "Sorry You are not authorized to view this page.."; ?><a
				href="<?php echo $this->request->getAttribute('webroot'); ?>">Please Login</a><?php
				exit();
			}
		}
	}
	
	
	
	// Change Password
	// Description : This is the common function to change the password for all users. This has the common template.
	// #Contributer : Akash Thakre
	// DATE : 19-04-2022

	public function changePassword() {

		// set variables to show popup messages from view file
		$this->authenticateUser();

		$message = '';
		$message_theme = '';
		$redirect_to = '';

		if ($this->request->is('post')) {

			#Random Salt 
			$randsalt = $this->Session->read('randSalt');

			#change passsword data
			$changepassdata = $this->request->getData();

			#username 
			$username = $this->Session->read('username');

			//get user type
			$user_type = $this->Customfunctions->getUserType($username);

			
			if (empty($user_type)) {

				$message_theme = 'failed';
				$message = 'Sorry...User Id entered is not valid';
				$redirect_to = 'change_password';

			} else {

				$oldpassdata = $this->request->getData('old_password');
				$newpassdata = $this->request->getData('new_password');
				$confpassdata = $this->request->getData('confirm_password');
				
				#get table
				$getTable = $this->getSpecificTable();
				$table = $getTable['password_table'];

				#calling library function 
				$change_pass_result = $this->Authentication->changePasswordLib($table, $username, $oldpassdata, $newpassdata, $confpassdata, $randsalt);

				if ($change_pass_result == 1) {

					$this->Customfunctions->saveActionPoint('Change Password','Failed'); #save action log
					$message = 'Sorry...username not matched to save new password';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} elseif ($change_pass_result == 2) {

					$this->Customfunctions->saveActionPoint('Change Password','Failed'); #save action log
					$message = 'Sorry...Please Check old password again';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} elseif ($change_pass_result == 3) {

					$this->Customfunctions->saveActionPoint('Change Password','Failed'); #save action log
					$message = 'Sorry...please Check. Confirm password not matched';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} elseif ($change_pass_result == 4) {

					$this->Customfunctions->saveActionPoint('Change Password','Failed'); #save action log
					$message = 'This password matched with your last three passwords, Please enter different password';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} else {

					$this->Customfunctions->saveActionPoint('Change Password','Success'); #save action log
					$message = 'Password Changed Successfully';
					$message_theme = 'success';
					$redirect_to = 'change_password';
				}

			}
			
			

			// set variables to show popup messages from view file
			$this->set('message_theme', $message_theme);
			$this->set('message', $message);
			$this->set('redirect_to', $redirect_to);


			if ($message != null) {
				$this->render('/element/message_boxes');
			}
		}
	}



    
	// USER ACTION HISTORY
	// Description : This is the common function to display the user action logs for all users. This has the common template.
	// @AUTHOR : PRAVIN BHAKARE
	// #Contributer : Akash Thakre
	// DATE : 19-04-2022
	
	public function userActionHistory() {

		$this->authenticateUser();
		$username = $this->Session->read('username');

		//check the user type
		$userType = $this->Customfunctions->getUserType($username);

		#get the specific action log table
		$getTable = $this->getSpecificTable();
		$actionLogTable = $getTable['action_log_table'];
		#load the model
		$this->loadModel($actionLogTable);

		$get_user_actions = $this->$actionLogTable->getActionLogs();

		//this is if the result is not found on the new tables created for saving the action log , then look into the old Action Table
		if (empty($get_user_actions)) {
			$this->loadModel('DmiUserActionLogs');
			$get_user_actions = $this->DmiUserActionLogs->find('all', array('conditions' => array('user_id IS' => $username,'action_perform IS NOT NULL'),'order' => array('id desc'), 'limit' => '100'))->toArray();
		}

		$this->set('get_user_actions', $get_user_actions);
		$this->set('userType', $userType);

	}





	// User Logs
	// Description : This is the common function to display the user logs for all users. This has the common template.
	// @Author : Amol Choudhari
	// #Contributer : Akash Thakre
	// Date : 19-04-2022

	public function currentUserLogs() {

		$this->authenticateUser();

		$username = $this->getRequest()->getSession()->read('username');

		//check the user type
		$userType = $this->Customfunctions->getUserType($username);
		
		#get the specific log table
		$getTable = $this->getSpecificTable();
		$logsTable = $getTable['log_table'];
		#load the model
		$this->loadModel($logsTable);

		#condition for user
		if ($userType == 'User') {
			$condition = array('email_id IS'=>$this->Session->read('username'));
		} else {
			$condition = array('customer_id IS'=>$this->Session->read('username'));
		}

		$currentLogs = $this-> $logsTable->find('all', array('conditions'=> $condition,'order' => 'id DESC'))->toArray();
			
		//to hide current session logout time.
		$currentLogs[0]['time_out'] = null;
		$this->set('currentLogs',$currentLogs);
		$this->set('userType',$userType);

	}



	// Logout 
	// Description : This common logout function is created for the user,chemist and customer customer
	// @Author : Amol Choudhari
	// #Contributer : Akash Thakre
	// Date : 19-04-2022

	public function logout() {
		
		$this->authenticateUser();
		$username = $this->getRequest()->getSession()->read('username');

		if (!empty($username)) {

			//check the user type
			$userType = $this->Customfunctions->getUserType($username);

			#get the specific log table
			$getTable = $this->getSpecificTable();
			$logsTable = $getTable['log_table'];
			#load the model
			$this->loadModel($logsTable);
			
			if ($userType == 'User') {
				$condition = array('email_id IS'=>$username);
			} else {
				$condition = array('customer_id IS'=>$username);
			}
			
			$list_id = $this->$logsTable->find('list', array('valueField' => 'id', 'conditions' => $condition))->toList();

			if (!empty($list_id)) {

				$fetch_last_id_query = $this->$logsTable->find('all', array('fields' => 'id', 'conditions' => array('id' => max($list_id), 'remark' => 'Success')))->first();
				$fetch_last_id = $fetch_last_id_query['id'];

				$UserLogsEntity = $this->$logsTable->newEntity(array('id' => $fetch_last_id,'time_out' => date('H:i:s')));
				$this->$logsTable->save($UserLogsEntity);
				$this->Authentication->browserLoginStatus($username,null);
				$this->Session->destroy();
				$this->redirect('/');

			} else {
				echo "Sorry You are not authorized to view this page..'<a href='login_user'>'Please login'</a>'";
				exit();
			}
			
		} else {
			$this->redirect('/');
		}
	}
		



	//Get Specific Table
	//Description: Returns the table needed for all the methods those are common flow.
	//@Author : Akash Thakre
	//Date : 14-09-2022

	public function getSpecificTable(){

		//get user type
		$userType = $this->Customfunctions->userType($this->Session->read('username'));

		if ($userType == 'Primary') {

			$log_table = 'DmiCustomerLogs';					#user log table
			$action_log_table = 'DmiCustomerActionLogs';	#action log table
			$password_table = 'DmiCustomers';				#password saved table

		} elseif ($userType == 'Chemist') {

			$log_table = 'DmiChemistLogs';					#user log table
			$action_log_table = 'DmiChemistActionLogs';		#action log table
			$password_table = 'DmiChemistRegistrations';	#password saved table

		} elseif ($userType == 'Secondary') {

			$log_table = 'DmiCustomerLogs';						#user log table
			$action_log_table = 'DmiFirmActionLogs'; 		#action log table
			$password_table = 'DmiFirms';					#password saved table

		} elseif ($userType == 'User') {

			$log_table = 'DmiUserLogs';						#user log table
			$action_log_table = 'DmiUserActionLogs'; 		#action log table
			$password_table = 'DmiUsers';					#password saved table
		}

		return $table = array('log_table' => $log_table, 'action_log_table' => $action_log_table,'password_table' => $password_table);
	}



    //Is Base 64 Encoded
	//Description: Returns the true or false based on the email encoded or not.
	//@Author : Amol Choudhari
	//Date : 14-09-2022

	public function isBase64Encoded($data){
		if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
		return TRUE;
		} else {
		return FALSE;
		}
	}

}
?>
