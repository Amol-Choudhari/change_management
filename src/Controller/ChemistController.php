<?php
	namespace App\Controller;

	use Cake\Event\Event;
	use Cake\Network\Session\DatabaseSession;
	use App\Network\Email\Email;
	use App\Network\Request\Request;
	use App\Network\Response\Response;
	use Cake\Datasource\ConnectionManager;
	use phpDocumentor\Reflection\Types\This;
	use Cake\Http\Response\withHeader;

	class ChemistController extends AppController {

		var $name = 'Chemist';

		//to initialize our custom requirements
		public function initialize(): void {

			parent::initialize();

			$this->loadComponent('RequestHandler');
			$this->loadComponent('Createcaptcha');
			$this->loadComponent('Authentication');
			$this->loadComponent('Customfunctions');

			$this->viewBuilder()->setHelpers(['Form','Html','Time']);

			//$this->viewBuilder()->setLayout('admin_dashboard');
			$this->Session = $this->getRequest()->getSession();

			//Load Models
			$this->loadModel('DmiChemistRegistrations');
			$this->loadModel('DmiFirms');
			$this->loadModel('DmiChemistsResetpassKeys');
			$this->loadModel('DmiChemistLogs');
			$this->loadModel('DmiChemistProfileDetails');
			$this->loadModel('DmiChemistExperienceDetails');
			$this->loadModel('DmiChemistEducationDetails');
			$this->loadModel('DmiChemistTrainingDetails');
			$this->loadModel('DmiChemistFinalSubmits');
			$this->loadModel('DmiStates');
			$this->loadModel('DmiDistricts');
			$this->loadModel('DmiEducationTypes');
			$this->loadModel('DmiDivisionGrades');
			$this->loadModel('DmiChemistAllocations');
			$this->loadModel('DmiRoOffices');
			$this->loadModel('DmiChemistComments');
			$this->loadModel('DmiChemistAllotments');
			$this->loadModel('DmiChemistOtherDetails');
			$this->loadModel('DmiSmsEmailTemplates');


		}


		// BEFORE FILTER
		public function beforeFilter($event) {

			parent::beforeFilter($event);
			//To Find Customer Last Login
			$customer_last_login = $this->Customfunctions->customerLastLogin();
			$this->set('customer_last_login', $customer_last_login);
			// Change layout for Ajax requests
			if ($this->request->is('ajax')) {
				$this->layout = 'ajax';
			}

			// Checked final submit status, moved here on 10-08-2021 by Amol, for common use
			$chemist_id = $this->Session->read('username');
			$final_submit_record = $this->DmiChemistFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id),'order'=>'id desc'))->first();
			$final_status = '';

			if (!empty($final_submit_record)) {
				$final_status = $final_submit_record['status'];
			}

			$this->set('final_submit_status',$final_status);
		}

		 //To create captcha code, called from component on 14-07-2017 by Amol
		 public function createCaptcha() {

			$this->autoRender = false;
			$this->Createcaptcha->createCaptcha();
		}
	
	
		//To Refresh Captcha Code
		public function refreshCaptchaCode() {
	
			$this->autoRender = false;
			$this->Createcaptcha->refreshCaptchaCode();
			exit;
		}
	



		// VALID USER
		// @AUTHOR : PRAVIN BHAKARE
		// #CONTRIBUTER : AKASH THAKRE (Migration)
		
		public function validUser() {

			$application_dashboard = $this->Session->read('application_dashboard');

			if ($this->Session->read('username') == null) {
				
				$this->customAlertPage("Sorry You are not authorized to view this page..");
				exit;
			} else {

				if ($application_dashboard == 'packer') {
					//checking applicant id pattern ex.102/1/PUN/006
					if (preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $this->Session->read('username'),$matches) !=1) {

						$this->customAlertPage("Sorry You are not authorized to view this page..");
						exit;
					}
				}
			}


			if ($application_dashboard == 'packer') {

				$show_renewal_btn = $this->Customfunctions->checkApplicantValidForRenewal($this->Session->read('username'));
				$this->set('show_renewal_btn',$show_renewal_btn);

				//Find the value of "is_already_granted" flag status to redirect the application on appropriate new application or old application controller
				//Done by pravin 27-09-2017
				$is_already_granted = null;
				$get_is_already_granted = $this->DmiFirms->find('all', array('fields'=>'is_already_granted','conditions'=>array('customer_id IS'=>$this->Session->read('username'))))->first();

				if (!empty($get_is_already_granted)) {

					$is_already_granted = $get_is_already_granted['is_already_granted'];
				}

				$this->set('is_already_granted',$is_already_granted);
			}

		}



		// CHEMIST LOGIN
		// @AUTHOR : PRAVIN BHAKARE
		// #CONTRIBUTER : AKASH THAKRE (Migration)
		// DATE : 25-06-2021 
		
		public function chemistLogin() {

			// set variables to show popup messages from view file
			$message = '';
			$message_theme = '';
			$redirect_to = '';
			$login_result = '';
			$already_loggedin_msg = 'no';

			//Set Layout
			$this->viewBuilder()->setLayout('form_layout');

			if ($this->request->is('post')) {

				//check login lockout status, applied on 24-04-2018 by Amol
				$lockout_status = $this->Customfunctions->checkLoginLockout('DmiChemistLogs',$this->request->getData('chemist_id'));
				
				if ($lockout_status == 'yes') {

					$message = 'Sorry... Your account is disabled for today, on account of 3 login failure.';
					$message_theme = 'failed';
					$redirect_to = $this->getRequest()->getAttribute('webroot');

				} else {


					$countspecialchar = substr_count($this->request->getData('chemist_id') ,"/");

					if ($countspecialchar == 2) {

						if (substr_count($this->request->getData('chemist_id') ,"/")!=0) {

							$split_customer_id = explode('/',(string) $this->request->getData('chemist_id')); #For Deprecations

							$randsalt = $this->Session->read('randSalt');
							$captchacode1 = $this->Session->read('code');
							$logindata = $this->request->getData();
							$username = $this->request->getData('chemist_id');
							$password = $this->request->getData('password');
							$captcharequest = $this->request->getData('captcha');
							$current_ip = $this->getRequest()->clientIp();

							if ($current_ip == '::1') {

								$current_ip = '127.0.0.1';
							}

							if ($this->request->getData('captcha') !="" && $this->Session->read('code') == $this->request->getData('captcha')) {

								$PassFromdb = $this->DmiChemistRegistrations->find('all', array('conditions'=> array('chemist_id IS' => $username,'delete_status IS NULL')))->first();

								$userType = 'chem';

								if ($PassFromdb != null && $PassFromdb != '') {

									$passarray1 = $PassFromdb;
									$passarray2 = $passarray1['password'];
									$emailforrecovery = $passarray1['email'];
									$PassFromdbsalted = $randsalt . $passarray2; //adding random salt
									$DbpasssaltedSHA512 = hash('sha512',$PassFromdbsalted); // Encripting

									// check password to db password
									if ($password == $DbpasssaltedSHA512 ) {
										
										$checkLog = $this->Authentication->alreadyLoggedInCheck($username);
										if ($checkLog == 'norecord') {
												
											//the logic from here is transffered to the function and called here
											//on 25-06-2021 by Amol
											$this->chemistLoginProced($username);

										} else {
											
											$_SESSION['username'] = $username;
											$_SESSION['userloggedin'] = 'no';
											$already_loggedin_msg = 'yes';
										}

									} else {

										//Save Chemist Logs Failed Status
										$DmiChemistLogsEntity = $this->DmiChemistLogs->newEntity(
											['customer_id'=>$username,
											'ip_address'=>$current_ip,
											'date'=>date('Y-m-d'),
											'time_in'=>date('H:i:s'),
											'remark'=>'Failed']
										);

										$this->DmiChemistLogs->save($DmiChemistLogsEntity);

										$login_result = 1;
									}

								} else {

									$login_result = 2;
								}

							} else {

								$login_result = 3;
							}

							// show user login failed messgae (by pravin 27/05/2017)
							if ($login_result == 1) {

								//this custom functionn is called on 08-04-2021, to show remaining login attempts
								$remng_attempts_msg = $this->showRemainingLoginAttempts('DmiChemistLogs',$this->request->getData('chemist_id'));
								$message = 'Username or password do not match. <br>'.$remng_attempts_msg;
								$message_theme = 'failed';
								$redirect_to = 'chemist_login';

							} elseif ($login_result == 2) {

								//this custom functionn is called on 08-04-2021, to show remaining login attempts
								$remng_attempts_msg = $this->showRemainingLoginAttempts('DmiChemistLogs',$this->request->getData('chemist_id'));
								$message = 'Username or password do not match. <br>'.$remng_attempts_msg;
								$message_theme = 'info';
								$redirect_to = 'chemist_login';

							} elseif ($login_result == 3) {

								$message = 'Sorry...Wrong Captcha Code Entered';
								$message_theme = 'failed';
								$redirect_to = 'chemist_login';
							}

						} else {

							$message = 'Username or password do not match or your account is freezed';
							$message_theme = 'failed';
							$redirect_to = 'chemist_login';
						}

					} else {

						$message = 'Username or password do not match or your account is freezed';
						$message_theme = 'failed';
						$redirect_to = 'chemist_login';
					}
				}
			}

			// set variables to show popup messages from view file
			$this->set('already_loggedin_msg',$already_loggedin_msg);
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);

			if ($message != null) {
				$this->render('/element/message_boxes');
			}

		}



		// CHEMIST REGISTRATION
		// @AUTHOR : PRAVIN BHAKARE
		// #CONTRIBUTER : AKASH THAKRE (Migration)
		// DATE : 25-06-2021 
		
		public function chemistRegistration() {

			$this->Session->write('application_dashboard','packer');
			$this->validUser();
			//Set Varibles For Display
			$message = '';
			$message_theme = '';
			$redirect_to = '';
			//Show button on Side menu
			$this->Beforepageload->showButtonOnSecondaryHome();

			//$url = $this->request->getParam('here');
			//$new = explode('/',$url);
			//$new_method = $new[3];
			//Set the Layout
			$this->viewBuilder()->setLayout('secondary_customer');

			$already_present = 'no';
			$present_email  = '';
			$present_mobile = '';

			$username = $this->Session->read('username');

			if ($this->request->is('post')) {

				//applied condition to check all post data for !empty validation on server side //on 21/10/2017 by Amol
				if (!empty($this->request->getData('email')) && !empty($this->request->getData('mobile')) && !empty($this->request->getData('dob'))) {

					$usersData = $this->request->getData();

					$checkEmailExist =  $this->DmiChemistRegistrations->find('all', array('fields' => 'email', 'conditions' => array('email IS' => base64_encode($usersData['email']))))->first();
					$checkMobileExist =  $this->DmiChemistRegistrations->find('all', array('fields' => 'mobile', 'conditions' => array('mobile IS' => $usersData['mobile'])))->first();

					if ($this->request->getData('chemist_fname') !="" && $this->request->getData('chemist_lname') !="" && $this->request->getData('email') !="" && $this->request->getData('mobile') !="" && $this->request->getData('dob') !="") {

						if ($checkEmailExist == null) {

							if ($checkMobileExist == null) {

								$last_registered_record	= $this->DmiChemistRegistrations->find('all', array('fields'=>'chemist_id','order'=>'id desc'))->first();

								if ($last_registered_record != null) {

									$last_registered_id = substr($last_registered_record['chemist_id'],-4) + 1;
									$chemist_id = 'CHM/'.date('y').'/'.$last_registered_id;

								} else {

									$chemist_id = 'CHM/'.date('y').'/1001';
								}


								$htmlEncoded_dob= htmlentities($this->Customfunctions->changeDateFormat($this->request->getData('dob')), ENT_QUOTES);
								$htmlEncoded_email = htmlentities(base64_encode($this->request->getData('email')), ENT_QUOTES); //for email encoding
								$htmlEncoded_mobile = htmlentities($this->request->getData('mobile'), ENT_QUOTES);
								$htmlEncoded_chemistFirstname = htmlentities($this->request->getData('chemist_fname'), ENT_QUOTES);
								$htmlEncoded_chemistLastname = htmlentities($this->request->getData('chemist_lname'), ENT_QUOTES);

								$certificationType = explode('/',(string) $username); #For Deprecations

								$DmiChemistRegistrationsEntity = $this->DmiChemistRegistrations->newEntity(array(

									'chemist_fname'=>$htmlEncoded_chemistFirstname,
									'chemist_lname'=>$htmlEncoded_chemistLastname,
									'chemist_id'=>$chemist_id,
									'email'=>$htmlEncoded_email,
									'password'=>'91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
								//	'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', // 123
									'mobile'=>base64_encode($htmlEncoded_mobile),
									'dob'=>$htmlEncoded_dob,
									'created_by'=>$username,
									'usertype'=>$certificationType[1],
									'created'=>date('Y-m-d H:i:s'),
									'modified'=>date('Y-m-d H:i:s')
								));


								if ($this->DmiChemistRegistrations->save($DmiChemistRegistrationsEntity)) {

									//Save Chemist Logs
									$DmiChemistLogsEntity = $this->DmiChemistLogs->newEntity(array(

										'chemist_fname'=>$htmlEncoded_chemistFirstname,
										'chemist_lname'=>$htmlEncoded_chemistLastname,
										'chemist_id'=>$chemist_id,
										'email'=>$htmlEncoded_email,
										'password'=>'91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
									//	'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2', // 123
										'mobile'=>base64_encode($htmlEncoded_mobile),
										'dob'=>$htmlEncoded_dob,
										'created_by'=>$username,
										'usertype'=>$certificationType[1],
										'created'=>date('Y-m-d H:i:s'),
										'modified'=>date('Y-m-d H:i:s')
									));

									$this->DmiChemistLogs->save($DmiChemistLogsEntity);

									$this->set('new_customer_id',$chemist_id);
									$this->set('htmlencodedemail',base64_decode($htmlEncoded_email)); //for email encoding
									$this->set('htmlencodedchemist_fname',$htmlEncoded_chemistFirstname);
									$this->set('htmlencodedchemist_lname',$htmlEncoded_chemistLastname);

									//called function to send link for reset password on registered email//on 13-02-2018 by Amol
									$this->Authentication->forgotPasswordLib('DmiChemistRegistrations', $htmlEncoded_email);

									//Save Chemist Allotment Entry
									$DmiChemistAllotmentsEntity = $this->DmiChemistAllotments->newEntity(array(

										'chemist_id'=>$chemist_id,
										'customer_id'=>$username,
										'created_by'=>$username,
										'usertype'=>$certificationType[1],
										'status'=>1,
										'incharge'=>'no',
										'created'=> date('Y-m-d H:i:s'),
										'modified'=>date('Y-m-d H:i:s')
									));

									$this->DmiChemistAllotments->save($DmiChemistAllotmentsEntity);

									#SMS: Chemist Registration
									$this->DmiSmsEmailTemplates->sendMessage(66,$chemist_id); #Packer
									$this->DmiSmsEmailTemplates->sendMessage(67,$chemist_id); #Chemist

									$message = 'You have registered Chemist <strong>"'.$htmlEncoded_chemistFirstname.' '.$htmlEncoded_chemistLastname.'"</strong> with chemist ID is <strong>"'.$chemist_id.'"</strong> .<br>An email has been sent to you and your chemist to set your login password. <br> <strong>Now chemist need to login and complete profile verification.';
									$message_theme = 'success';
									$redirect_to = '../customers/secondary_home';

								} else {

									$message = 'Your chemist details are not saved please check again';
									$message_theme = 'warning';
									$redirect_to = 'chemist_registration';
								}

							} else {

								$present_mobile = 'mobile number';
								$already_present = 'yes';
							}

						} else {

							$present_email = 'email id';
							$already_present = 'yes';
						}

						if ($already_present == 'yes') {

							$message = 'This '.$present_email.' '.$present_mobile.' is already registered with us.';
							$message_theme = 'failed';
							$redirect_to = '../customers/secondary_home';
						}

					} else {

						$message = 'Please enter all details. Do not leave any field empty!!';
						$message_theme = 'warning';
						$redirect_to = 'chemist_registration';
					}

				} else {

					$message = 'Please enter all details. Do not leave any field empty!!';
					$message_theme = 'warning';
					$redirect_to = 'chemist_registration';
				}
			}

			// set variables to show popup messages from view file
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);

			if ($message != null) {
				$this->render('/element/message_boxes');

			}
		
		}



		// RESET PASSWORD
		// @AUTHOR : PRAVIN BHAKARE
		// #CONTRIBUTER : AKASH THAKRE (Migration)
		// DATE : 25-06-2021 
		
		public function resetPassword() {

			// set variables to show popup messages from view file
			$message = '';
			$message_theme = '';
			$redirect_to = '';

			$this->viewBuilder()->setLayout('form_layout');

			if (empty($_GET['$key']) || empty($_GET['$id'])) {

				$this->customAlertPage("Sorry You are not authorized to view this page..");
				exit;

			} else {

				$key_id = $_GET['$key'];
				// Added the urldecode funtion to fix the issue of +,<,# etc issue in gettin through get parameter
				// added on 26/11/2018

				$user_id = $this->Authentication->decrypt($_GET['$id']);
				$this->set('user_id',$user_id);

				$countspecialchar = substr_count($user_id ,"/");

				if ($countspecialchar != 2) {

					$this->customAlertPage("Sorry You are not authorized to view this page..");
					exit;
				}

				//fetch applicant details
				$get_record_details = $this->DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id'=>$user_id)))->first();
				$record_id = $get_record_details['id'];

				//call function to check valid key
				$valid_key_result = $this->DmiChemistsResetpassKeys->checkValidKey($user_id,$key_id);

				if ($valid_key_result == 1) {

					if ($this->request->is('post')) {

						$randsalt = $this->Session->read('randSalt');
						$captchacode1 = $this->Session->read('code');
						$postData = $this->request->getData();
						$username = $this->request->getData('chemist_id');
						$countspecialchar = substr_count($username ,"/");

						if ($countspecialchar != 2) {

							$user_id_not_valid_msg = 'This User Id is not valid';
							$this->set('user_id_not_valid_msg',$user_id_not_valid_msg);
							return null;
							exit;
						}

						$newpassdata = $this->request->getData('new_password');
						$confpassdata = $this->request->getData('confirm_password');

						$reset_pass_result = $this->Authentication->resetPasswordLib('DmiChemistRegistrations',$username,$newpassdata,$randsalt,$postData);

						if ($reset_pass_result == 1) {

							$this->Customfunctions->saveActionPoint('Reset Password (Email Not Matched)','Failed',$user_id); #Action
							$email_id_not_matched_msg = 'Email id & User Id not Matched.';
							$this->set('email_id_not_matched_msg',$email_id_not_matched_msg);
							return null;
							exit;

						} elseif ($reset_pass_result == 2) {

							$this->Customfunctions->saveActionPoint('Reset Password (Incorrect Captcha)','Failed',$user_id); #Action
							$incorrect_captcha_msg = 'Incorrect Captcha code entered.';
							$this->set('incorrect_captcha_msg',$incorrect_captcha_msg);
							return null;
							exit;

						} elseif ($reset_pass_result == 3) {

							$this->Customfunctions->saveActionPoint('Reset Password (Password Not Macthed)','Failed',$user_id); #Action
							$comfirm_pass_msg = 'Confirm password not matched';
							$this->set('comfirm_pass_msg',$comfirm_pass_msg);
							return null;
							exit;

						} elseif ($reset_pass_result == 4) {

							$this->Customfunctions->saveActionPoint('Reset Password (Password is Same as Last)','Failed',$user_id); #Action
							$comfirm_pass_msg = 'This password matched with your last three passwords, Please enter different password';
							$this->set('comfirm_pass_msg',$comfirm_pass_msg);
							return null;
							exit;

						} else {

							$this->Customfunctions->saveActionPoint('Reset Password','Success',$user_id); #Action
							//update link key table status to 1 for successfully
							$this->DmiChemistsResetpassKeys->updateKeySuccess($user_id,$key_id);
							$message = 'Password Changed Successfully';
							$message_theme = 'success';
							$redirect_to = '../../chemist/chemist_login';
						}
					}
				
				} elseif ($valid_key_result == 2) {

					$message = 'Sorry.. This link to reset password is already used or expired. Please proceed through "Forgot Password" again.';
					$message_theme = 'failed';
					$redirect_to = '../../customers/forgot_password';
				}
			}

			// set variables to show popup messages from view file
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);
		}



		// HOME
		// @AUTHOR : PRAVIN BHAKARE
		// DATE : 25-06-2021 
				
		public function home() {

			$this->viewBuilder()->setLayout('chemist_home_layout');
			$this->Session->write('application_dashboard','chemist');
		}



	

		// REPLICA ALLOTED LIST
		// @AUTHOR : AKASH THAKRE
		// DATE : 25-06-2021 
		
		public function replicaAllotedList() {

			$this->viewBuilder()->setLayout('chemist_home_layout');
			$this->Customfunctions->replicaAllotedListCall('replica');
		}


		// ALLOTED 15 DIGIT LIST
		// @AUTHOR : AKASH THAKRE
		// DATE : 25-06-2021 
		
		public function alloted15DigitList() {

			$this->viewBuilder()->setLayout('chemist_home_layout');
			$this->Customfunctions->replicaAllotedListCall('15Digit');

		}


		// ALLOTED E CODE LIST
		// @AUTHOR : AKASH THAKRE
		// DATE : 25-06-2021 
		
		
		public function allotedECodeList() {

			$this->viewBuilder()->setLayout('chemist_home_layout');
			$this->Customfunctions->replicaAllotedListCall('ECode');

		}



		// LOGOUT
		// @AUTHOR : AKASH THAKRE
		// DATE : 28-04-2021
		
		public function logout() {
			//LOAD MODEL
			$this->loadModel('DmiChemistLogs');
			$list_id = $this->DmiChemistLogs->find('list', array('valueField' => 'id', 'conditions' => array('customer_id IS' => $this->Session->read('username'))))->toArray();

			if (!empty($list_id)) {

				$fetch_last_id_query = $this->DmiChemistLogs->find('all', array('fields' => 'id', 'conditions' => array('id' => max($list_id), 'remark' => 'Success')))->first();
				$fetch_last_id = $fetch_last_id_query['id'];
				$DmiChemistLogsEntity = $this->DmiChemistLogs->newEntity(array('id' => $fetch_last_id,'time_out' => date('H:i:s')));
				$this->DmiChemistLogs->save($DmiChemistLogsEntity);

				// Update status of browser login history, Done By Pravin Bhakare 12-11-2020 & Added on 28-04-2021 by Akash
				$this->Authentication->browserLoginStatus($this->Session->read('username'),null);
				$this->Session->destroy();
				$this->redirect('/');

			} else {

				$this->customAlertPage("Sorry You are not authorized to view this page..");
				exit;
			}

		}


		
		
		// CHEMIST LOGIN PROCED
		// @AUTHOR : PRAVIN BHAKARE
		// DATE : 28-04-2021
		
		public function chemistLoginProced($username) {

			

			$this->Session->destroy();// destroy old session data
			session_start();

			$current_ip = $this->getRequest()->clientIp();
			if ($current_ip == '::1')
			{
				$current_ip = '127.0.0.1';
			}
											
			$this->Authentication->browserLoginStatus($username,'yes');

			//updating customer successful logs
			$DmiChemistLogsEntity = $this->DmiChemistLogs->newEntity(
				['customer_id'=>$username,
				'ip_address'=>$current_ip,
				'date'=>date('Y-m-d'),
				'time_in'=>date('H:i:s'),
				'remark'=>'Success']
			);

			$this->DmiChemistLogs->save($DmiChemistLogsEntity);

			$customer_data_query = $this->DmiChemistRegistrations->find('all', array('conditions'=> array('chemist_id IS' => $username)))->first();

			$this->Session->write('username',$username);
			$this->Session->write('last_login_time_value',time()); // Store the "login time" into session for checking user activity time (Done by pravin 24/4/2018)
			$this->Session->write('ip_address',$this->getRequest()->clientIp());


			$customer_f_name = $customer_data_query['chemist_fname'];
			$this->Session->write('f_name',$customer_f_name);

			$customer_l_name = $customer_data_query['chemist_lname'];
			$this->Session->write('l_name',$customer_l_name);

			$this->redirect(array('controller'=>'chemist', 'action'=>'home'));


		}




	}

?>
