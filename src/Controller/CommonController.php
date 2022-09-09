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

			//Find the value of "is_already_granted" flag status to redirect the application on appropriate new application or old application controller
			//Done by pravin 27-09-2017
			$this->loadModel('DmiFirms');
			$is_already_granted = null;
			$get_is_already_granted = $this->DmiFirms->find('all', array('fields' => 'is_already_granted', 'conditions' => array('customer_id IS' => $this->Session->read('username'))))->first();

			if (!empty($get_is_already_granted)) {

				$is_already_granted = $get_is_already_granted['is_already_granted'];
			}

			$this->set('is_already_granted', $is_already_granted);

		}




		public function authenticateUser(){

			if ($this->Session->read('username') == null) {
				echo "Sorry You are not authorized to view this page..'<a href='../'>'Please login'</a>'";
				exit();
			} else {//this else portion added on 10-07-2017 by Amol to allow only logged in Applicant

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
	
	
	
		//Change Password method start
		public function changePassword() {

			// set variables to show popup messages from view file
			$this->authenticateUser();
			$message = '';
			$message_theme = '';
			$redirect_to = '';

			if ($this->request->is('post')) {

				$randsalt = $this->Session->read('randSalt');
				$changepassdata = $this->request->getData();
				$username = $this->Session->read('username');

				// If the user having email id
				$username = $this->Session->read('username');
				if (strpos(base64_decode($username),'@')) {
					$table = 'DmiUsers';
				} else {
					$countspecialchar = substr_count($username, "/");

					if ($countspecialchar == 1) {
						$table = 'DmiCustomers';
					} elseif ($countspecialchar == 2) {
						$table = 'DmiChemistRegistrations';
					} elseif ($countspecialchar == 3) {
						$table = 'DmiFirms';
					} else {
						$message_theme = 'failed';
						$message = 'Sorry...User Id entered is not valid';
						$redirect_to = 'change_password';
					}
				}
			  
				$oldpassdata = $this->request->getData('old_password');
				$newpassdata = $this->request->getData('new_password');
				$confpassdata = $this->request->getData('confirm_password');
		
				$change_pass_result = $this->Authentication->changePasswordLib($table, $username, $oldpassdata, $newpassdata, $confpassdata, $randsalt); // calling change password library function

				if ($change_pass_result == 1) {

					$message = 'Sorry...username not matched to save new password';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} elseif ($change_pass_result == 2) {
					
					$message = 'Sorry...Please Check old password again';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} elseif ($change_pass_result == 3) {

					$message = 'Sorry...please Check. Confirm password not matched';
					$message_theme = 'failed';
					$redirect_to = 'change_password';

				} elseif ($change_pass_result == 4) {

					// By Aniket Ganvir dated 16th NOV 2020
					$message = 'This password matched with your last three passwords, Please enter different password';
					$redirect_to = 'change_password';

				} else {
					$this->Customfunctions->saveActionPoint('Change Password','Success');
					$message = 'Password Changed Successfully';
					$message_theme = 'success';
					$redirect_to = 'change_password';
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
		// @AUTHOR : PRAVIN BHAKARE
		// #Contributer : Akash Thakre (Common/Migration/Upatation)
		// DATE : 19-04-2022
		
		public function userActionHistory() {

			$this->authenticateUser();
			$userId = $this->Session->read('username');
			$this->loadModel('DmiUserActionLogs');
			$this->loadModel('DmiFirms');
			$userType = $this->userType($userId);
	
			$get_user_actions = $this->DmiUserActionLogs->find('all', array('conditions' => array('user_id IS' => $userId,'action_perform IS NOT NULL'), 'order' => array('id desc'), 'limit' => '100'))->toArray();

			$is_already_granted = $this->DmiFirms->find('all', array('fields' => 'is_already_granted', 'conditions' => array('customer_id' => $this->Session->read('username'))))->first();

			$show_renewal_btn = $this->Customfunctions->checkApplicantValidForRenewal($this->Session->read('username'));

			$this->set('show_renewal_btn', $show_renewal_btn);
			$this->set('is_already_granted', $is_already_granted);
			$this->set('get_user_actions', $get_user_actions);
			$this->set('userType', $userType);
		
		}





        // USER LOGS
        // Description : 
        // @Author : Amol Choudhari
        // #Contributer : Akash Thakre (Common/Migration/Upatation)
        // Date : 19-04-2022

		public function currentUserLogs() {

            $this->authenticateUser();

            $username = $this->getRequest()->getSession()->read('username');
            //check the user type
            $userType = $this->userType($username);
            
            if ($userType == 'User') {
                $logsTable = 'DmiUserLogs';         
                $condition = array('email_id IS'=>$this->Session->read('username'));

            } elseif ($userType == 'Secondary') {
                $logsTable = 'DmiCustomerLogs';
                $condition = array('customer_id IS'=>$this->Session->read('username'));

            } elseif ($userType == 'Chemist') {
                $logsTable = 'DmiChemistLogs';
                $condition = array('customer_id IS'=>$this->Session->read('username'));

            } elseif ($userType == 'Primary') {
                $logsTable = 'DmiCustomerLogs';
                $condition = array('customer_id IS'=>$this->Session->read('username'));
            }

            $this->loadModel($logsTable);
			$currentLogs = $this-> $logsTable->find('all', array('conditions'=> $condition,'order' => 'id DESC'))->toArray();
				
			//to hide current session logout time.
			$currentLogs[0]['time_out'] = null;
			$this->set('currentLogs',$currentLogs);
            $this->set('userType',$userType);   

		}




		// Logout 
		// Description : This common logout function is created for the user,chemist and customer customer
		// @Author : Amol Choudhari
		// #Contributer : Akash Thakre (Common/Migration/Upatation)
		// Date : 19-04-2022

		public function logout() {
		   
			$this->authenticateUser();
			$username = $this->getRequest()->getSession()->read('username');
			//check the user type
			$userType = $this->userType($username);
		   
			if ($userType == 'User') {
				$logsTable = 'DmiUserLogs';         
				$condition = array('email_id IS'=>$username);  
			} elseif ($userType == 'Secondary' || $userType == 'Primary') {
				$logsTable = 'DmiCustomerLogs';
				$condition = array('customer_id IS'=>$username);
			} elseif ($userType == 'Chemist') {
				$logsTable = 'DmiChemistLogs';
				$condition = array('customer_id IS'=>$username);
			}

			$this->loadModel($logsTable);
		   
			if (!empty($username)) {
				
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

 



		// for checking the encoded or not
		public function isBase64Encoded($data){
			if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
			return TRUE;
			} else {
			return FALSE;
			}
		}

		// for checking user type
		public function userType($username){

			// If the user having email id
			$username = $this->Session->read('username');
			if (strpos(base64_decode($username),'@')) {
				$userType = 'User';
			} else {
				$countspecialchar = substr_count($username, "/");

				if ($countspecialchar == 1) {
					$userType = 'Primary';
				} elseif ($countspecialchar == 2) {
					$userType = 'Chemist';
				} elseif ($countspecialchar == 3) {
					$userType = 'Secondary';
				}
			}

			return $userType;
		}







		//Reset Password function Starts - Updated with new changes on 28-04-2021 By Akash.
		public function resetPassword() {

			$this->authenticateUser();

			// set variables to show popup messages from view file
			$message = '';
			$message_theme = '';
			$redirect_to = '';
			//Set the layout
			$this->viewBuilder()->setLayout('form_layout');

			if (empty($_GET['$key']) || empty($_GET['$id'])) {

				echo "Sorry You are not authorized to view this page..'<a href='../'>'Please login'</a>'";
				exit();

			} else {

				$key_id = $_GET['$key'];
				// Added the urldecode funtion to fix the issue of +,<,# etc issue in gettin through get parameter // added on 26/11/2018
				$user_id = $this->Authentication->decrypt($_GET['$id']);
				$this->set('user_id', $user_id);
				//check Applicant type (primary/secondary)
				$countspecialchar = substr_count($user_id, "/");

				if ($countspecialchar == 1) {

					$table = 'DmiCustomers';

				} elseif ($countspecialchar == 3) {

					$table = 'DmiFirms';

				} else {

					echo "Sorry You are not authorized to view this page..'<a href='../'>'Please login'</a>'";
					exit();
				}

				$this->loadModel($table);
				$this->loadModel('DmiApplicantsResetpassKeys');

				//fetch applicant details
				$get_record_details = $this->$table->find('all', array('conditions' => array('customer_id IS' => $user_id)))->first();
				$record_id = $get_record_details['id'];

				//call function to check valid key
				$valid_key_result = $this->DmiApplicantsResetpassKeys->checkValidKey($user_id, $key_id);

				if ($valid_key_result == 1) {

					if ($this->request->is('post')) {

						$randsalt = $this->Session->read('randSalt');
						$captchacode1 = $this->Session->read('code');
						$changepassdata = $this->request->getData();
						$username = $this->request->getData('customer_id');
						$countspecialchar = substr_count($username, "/");

						if ($countspecialchar == 1) {
							$table = 'DmiCustomers';
						} elseif ($countspecialchar == 3) {
							$table = 'DmiFirms';
						} else {
							$user_id_not_valid_msg = 'This User Id is not valid';
							$this->set('user_id_not_valid_msg', $user_id_not_valid_msg);
							return null;
							exit;
						}

						$newpassdata = $this->request->getData('new_password');
						$confpassdata = $this->request->getData('confirm_password');

						// calling reset password library function
						$reset_pass_result = $this->Authentication->resetPasswordLib($table, $username, $newpassdata, $randsalt);

						if ($reset_pass_result == 1) {

							$email_id_not_matched_msg = 'Email id & User Id not Matched.';
							$this->set('email_id_not_matched_msg', $email_id_not_matched_msg);
							return null;
							exit;

						} elseif ($reset_pass_result == 2) {

							$incorrect_captcha_msg = 'Incorrect Captcha code entered.';
							$this->set('incorrect_captcha_msg', $incorrect_captcha_msg);
							return null;
							exit;

						} elseif ($reset_pass_result == 3) {

							$comfirm_pass_msg = 'Confirm password not matched';
							$this->set('comfirm_pass_msg', $comfirm_pass_msg);
							return false;
							exit;

						} elseif ($reset_pass_result == 4) {

							// SHOW ERROR MESSAGE IF NEW PASSWORD FOUND UNDER LAST THREE PASSWORDS OF USER // By Aniket Ganvir dated 16th NOV 2020
							$comfirm_pass_msg = 'This password matched with your last three passwords, Please enter different password';
							$this->set('comfirm_pass_msg', $comfirm_pass_msg);
							return false;
							exit;

						} else {
							//update link key table status to 1 for successfully
							$this->DmiApplicantsResetpassKeys->updateKeySuccess($user_id, $key_id);
							$message = 'Password Changed Successfully';
							$message_theme = 'success';
							$redirect_to = '../../customers/login_customer';

						}


					}

				} elseif ($valid_key_result == 2) {

					$message = 'Sorry.. This link to reset password is already used or expired. Please proceed through "Forgot Password" again.';
					$message_theme = 'failed';
					$redirect_to = '../forgot_password';

				}

			}


			// set variables to show popup messages from view file
			$this->set('message', $message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to', $redirect_to);

		}



    

	}
?>
