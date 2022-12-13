<?php

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Utility\Hash;
use Cake\Datasource\ConnectionManager;

class CustomersController extends AppController {

    var $name = 'Customers';

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


    //added on 04-08-2017 by Amol to set session variable according to id and used in view file to show heading
    public function loginCustomerRedirect($id) {

        if ($id == 1) {

            $this->getRequest()->getSession()->write('login_to', 'ca');
            $this->redirect('/customers/login-customer');

        } elseif ($id == 2) {

            $this->getRequest()->getSession()->write('login_to', 'printing');
            $this->redirect('/customers/login-customer');

        } elseif ($id == 3) {

            $this->getRequest()->getSession()->write('login_to', 'lab');
            $this->redirect('/customers/login-customer');
        }
    }


    //Login Customer function start
    public function loginCustomer() {

        //Set the Layout
        $this->viewBuilder()->setLayout('form_layout');
        //load model
        $this->loadModel('DmiCustomers');
        //this condition added on 05-08-2017 by Amol to redirect to home
        if ($this->getRequest()->getSession()->read('login_to') == null) {
            $this->redirect('/');
        }

        // set variables to show popup messages from view file
        $message = '';
        $message_theme = '';
        $redirect_to = '';
        $already_loggedin_msg = 'no';

        if ($this->request->is('post')) {

            //check login lockout status, applied on 24-04-2018 by Amol
            $lockout_status = $this->Customfunctions->checkLoginLockout('DmiCustomerLogs', $this->request->getData('customer_id'));

            if ($lockout_status == 'yes') {

                $message = 'Sorry... Your account is disabled for today, on account of 3 login failure.';
                $redirect_to = $this->request->getAttribute('webroot');

            } else {


                if (substr_count($this->request->getData('customer_id'), "/") != 0) {

                    $split_customer_id = explode('/',(string) $this->request->getData('customer_id')); #For Deprecations

                    //added below conditions on 15-02-2018 by Amol
                    if ($split_customer_id[1] == 1 && $this->getRequest()->getSession()->read('login_to') != 'ca') {

                        $message = 'Please Login from Certificate Of Authorisation Window. Thankyou';
                        $redirect_to = $this->request->getAttribute('webroot');

                    } elseif ($split_customer_id[1] == 2 && $this->getRequest()->getSession()->read('login_to') != 'printing') {

                        $message = 'Please Login from Certificate of Printing Permission Window. Thankyou';
                        $redirect_to = $this->request->getAttribute('webroot');

                    } elseif ($split_customer_id[1] == 3 && $this->getRequest()->getSession()->read('login_to') != 'lab') {

                        $message = 'Please Login from Certificate of Approval of Laboratory Window. Thankyou';
                        $redirect_to = $this->request->getAttribute('webroot');

                    } else {

                        $randsalt = $this->getRequest()->getSession()->read('randSalt');
                        $captchacode1 = $this->getRequest()->getSession()->read('code');
                        $logindata = $this->request->getData();
                        $username = $this->request->getData('customer_id');
                        $password = $this->request->getData('password');
                        $captcharequest = $this->request->getData('captcha');

                        $countspecialchar = substr_count($username, "/");

                        if ($countspecialchar == 1) {

                            $table = 'DmiCustomers';
                            // calling login library function
                            $login_result = $this->Authentication->customerLoginLib($table, $username, $password, $randsalt);
                            // show user login failed messgae (by pravin 27/05/2017)
                            if ($login_result == 1) {
                                //this custom functionn is called on 08-04-2021, to show remaining login attempts
                                $remng_attempts_msg = $this->showRemainingLoginAttempts('DmiCustomerLogs', $this->request->getData('customer_id'));
                                $message = 'Username or password do not match. <br>' . $remng_attempts_msg;
                                $message_theme = 'failed';
                                $redirect_to = 'login_customer';

                            } elseif ($login_result == 2) {

                                //this custom functionn is called on 08-04-2021, to show remaining login attempts
                                $remng_attempts_msg = $this->showRemainingLoginAttempts('DmiCustomerLogs', $this->request->getData('customer_id'));
                                $message = 'Username or password do not match. <br>' . $remng_attempts_msg;
                                $message_theme = 'failed';
                                $redirect_to = 'login_customer';

                            } elseif ($login_result == 3) {

                                $captcha_error_msg = 'Sorry... Wrong Code Entered';
                                $message_theme = 'failed';
                                $this->set('captcha_error_msg', $captcha_error_msg);
                                $this->set('already_loggedin_msg',$already_loggedin_msg);
                                return null;
                                exit;

                            } elseif ($login_result == 4) {

                                //get applicant email id and apply masking before showing in message by Amol on 25-02-2021
                                $get_email_id = $this->DmiCustomers->find('all', array('fields' => 'email', 'conditions' => array('customer_id' => $username)))->first();
                                $email_id = $this->Customfunctions->getMaskedValue($get_email_id['email'], 'email');
                                $message = 'Your password has been expired, The link to reset password is sent on email id ' . $email_id . ', Please contact the concerned office.';
                                $redirect_to = 'login_customer';

                            //created/updated/added on 25-06-2021 for multiple logged in check security updates, by Amol
                            }elseif($login_result == 5){
                                
                                $already_loggedin_msg = 'yes';
                            }

                        } elseif ($countspecialchar == 3) {

                            $table = 'DmiFirms';
                            // calling login library function
                            $login_result = $this->Authentication->customerLoginLib($table, $username, $password, $randsalt);

                            // show user login failed messgae (by pravin 27/05/2017)
                            if ($login_result == 1) {

                                $remng_attempts_msg = $this->showRemainingLoginAttempts('DmiCustomerLogs', $this->request->getData('customer_id'));
                                $message = 'Username or password do not match. <br>' . $remng_attempts_msg;
                                $message_theme = 'failed';
                                $redirect_to = 'login_customer';

                            } elseif ($login_result == 2) {

                                $remng_attempts_msg = $this->showRemainingLoginAttempts('DmiCustomerLogs', $this->request->getData('customer_id'));
                                $message = 'Username or password do not match. <br>' . $remng_attempts_msg;
                                $message_theme = 'failed';
                                $redirect_to = 'login_customer';

                            } elseif ($login_result == 3) {

                                $message = 'Sorry...Wrong Captcha Code Entered';
                                $message_theme = 'failed';
                                $redirect_to = 'login_customer';

                            } elseif ($login_result == 4) {
                                //get applicant email id and apply masking before showing in message by Amol on 25-02-2021
                                $get_email_id = $this->$table->find('all', array('fields' => 'email', 'conditions' => array('customer_id' => $username)))->first();
                                $email_id = $this->Customfunctions->getMaskedValue($get_email_id['email'], 'email');
                                $message = 'Your password has been expired, The link to reset password is sent on email id ' . $email_id . ', Please contact the concerned office.';
                                $redirect_to = 'login_customer';

                            //created/updated/added on 25-06-2021 for multiple logged in check security updates, by Amol
                            }elseif($login_result == 5){
                                
                                $already_loggedin_msg = 'yes';
                            }

                        } else {

                            $message = 'Username or password do not match or your account is freezed';
                            $message_theme = 'failed';
                            $redirect_to = 'login_customer';
                        }

                    }

                } else {

                    $message = 'User Id entered is not valid';
                    $message_theme = 'failed';
                    $redirect_to = 'login_customer';
                }

            }
        }

        // set variables to show popup messages from view file
        $this->set('already_loggedin_msg',$already_loggedin_msg);
        $this->set('message', $message);
        $this->set('message_theme', $message_theme);
        $this->set('redirect_to', $redirect_to);
    }



    //Register Customer Fucntion starts
    public function registerCustomer() {

        //Load Models
        $this->loadModel('DmiStates');
        $this->loadModel('DmiDistricts');
        $this->loadModel('DmiDocumentLists');
        $this->loadModel('DmiCustomers');
        $this->loadModel('DmiCustomersHistoryLogs');
    
        //Set the Layout
        $this->viewBuilder()->setLayout('form_layout');

        // Apply "Order by" clause to get state list by order wise (Done By Pravin 10-01-2018)
        $states = $this->DmiStates->find('list', array('valueField' => 'state_name', 'conditions' => array('OR' => array('delete_status IS NULL', 'delete_status =' => 'no')), 'order' => array('state_name')))->toArray();
        $this->set('states', $states);

        $districts = $this->DmiDistricts->find('list', array('valueField' => 'district_name', 'conditions' => array('state_id' => 1, 'OR' => array('delete_status IS NULL', 'delete_status =' => 'no'))))->toArray();
        $this->set('districts', $districts);

        $document_lists = $this->DmiDocumentLists->find('list', array('valueField' => 'document_name', 'conditions' => array('delete_status IS NULL')))->toArray(); // Changed the query on the 09-08-2022 by Akash
        $this->set('document_lists', $document_lists);

        // set variables to show popup messages from view file
        $message = '';
        $message_theme = '';
        $redirect_to = '';


        if ($this->request->is('post')) {

            //this condition is added on 22-03-2018 by Amol //aadhar authentication will only done when concent is checked
            if (!empty($this->request->getData('once_card_no')) && $this->request->getData('aadhar_auth_check') == 1) {

                //below function is called to authenticate Aadhar no. before registering applicant.//added on 20-11-2017 by Amol
                $esign = new EsignController();
                $aadhar_authentication_result = $esign->requestAadharAuthentication($this->request->getData('once_card_no'), $this->request->getData('aadhar_otp'));//added new parameter $aadhar_otp on 25-08-2018

                if ($aadhar_authentication_result == true) {
                    //Process
                } else {
                    $this->set('return_error_msg','Sorry.. Aadhar Authentication Failed, Please try again.');
                    return null;
                    exit;
                }
            }

            if (!$this->Customfunctions->validateUniquePostData($this->request->getData('mobile'), 'mobile') == 1) {

                $this->set('return_error_msg','Please enter proper Mobile no.');
                return null;
                exit;
            }

            if (!$this->Customfunctions->validateUniquePostData($this->request->getData('email'), 'email') == 1) {

                $this->set('return_error_msg','Please enter proper Email id');
                return null;
                exit;
            }

            //this condition is added on 22-03-2018 by Amol //only validate when aadhar & concent provided
            if (!empty($this->request->getData('once_card_no')) && $this->request->getData('aadhar_auth_check') == 1) {

                if (!$this->Customfunctions->validateUniquePostData($this->request->getData('once_card_no'), 'aadhar') == 1) {

                    $this->set('return_error_msg','Please enter proper Aadhar Card no.');
                    return null;
                    exit;
                }
            }

            $randsalt = $this->getRequest()->getSession()->read('randSalt');
            $captchacode1 = $this->getRequest()->getSession()->read('code');
            $usersData = $this->request->getData();
            $Checkemailexist = $this->DmiCustomers->find('all', array('fields' => 'email', 'conditions' => array('email IS' => $usersData['email'])))->first();

            // verifying captcha code
            if ($this->request->getData('captcha') != "" && $captchacode1 == $this->request->getData('captcha')) {

                if ($Checkemailexist == null) {

                    $last_record_id_query = $this->DmiCustomers->find('all', array('fields' => 'id', 'order' => array('id desc')))->first();
                    $last_record_id = $last_record_id_query['id'];
                    $last_customer_id_query = $this->DmiCustomers->find('all', array('fields' => 'customer_id', 'conditions' => array('id' => $last_record_id)))->first();

                    if (!empty($last_customer_id_query)) {

                        $last_customer_id = $last_customer_id_query['customer_id'];
                        $split = explode('/', $last_customer_id);
                        $splited_id = $split[0];
                    } else {
                        $splited_id = 100;
                    }

                    $new_customer_id = ($splited_id + 1) . '/' . date('Y');

                    $htmlencodedfname = htmlentities($this->request->getData('f_name'), ENT_QUOTES);
                    $htmlencodedmname = htmlentities($this->request->getData('m_name'), ENT_QUOTES);
                    $htmlencodedlname = htmlentities($this->request->getData('l_name'), ENT_QUOTES);
                    $htmlencodedstreetaddress = htmlentities($this->request->getData('street_address'), ENT_QUOTES);
                    $htmlencodedpostalcode = htmlentities($this->request->getData('postal_code'), ENT_QUOTES);
                    $htmlencodedemail = htmlentities($this->request->getData('email'), ENT_QUOTES);
                    $htmlencodedmobile = htmlentities($this->request->getData('mobile'), ENT_QUOTES);
                    $htmlencodedlandline = htmlentities($this->request->getData('landline'), ENT_QUOTES);
                    $htmlencodedphoto_id_no = htmlentities($this->request->getData('photo_id_no'), ENT_QUOTES);

                    $table = 'DmiDocumentLists';
                    $post_input_request = $this->request->getData('document');
                    $document = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request);//calling library function


                    //for state
                    $table = 'DmiStates';
                    $post_input_request = $this->request->getData('state');
                    $state = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request);//calling library function

                    //for district
                    $table = 'DmiDistricts';
                    $post_input_request = $this->request->getData('district');
                    $district = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request);//calling library function

                    $htmlencodedemail = base64_encode($htmlencodedemail);//for email encoding

                    $DmiCustomersEntity = $this->DmiCustomers->newEntity(array(
                        'customer_id' => $new_customer_id,
                        'f_name' => $htmlencodedfname,
                        'm_name' => $htmlencodedmname,
                        'l_name' => $htmlencodedlname,
                        'street_address' => $htmlencodedstreetaddress,
                        'state' => $state,
                        'district' => $district,
                        'postal_code' => $htmlencodedpostalcode,
                        'email' => $htmlencodedemail,
                        'password' => '91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
						//'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2',//123
                        'mobile' => base64_encode($htmlencodedmobile), //This is added on 27-04-2021 for base64encoding by AKASH
                        'landline' => base64_encode($htmlencodedlandline),//This is added on 27-04-2021 for base64encoding by AKASH
                        //'once_card_no'=>$encrypted_aadhar, //commented on 22-03-2018
                        'document' => $document,
                        'photo_id_no' => $htmlencodedphoto_id_no,
                        //'file'=>$uploadedfile,
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s')
                    ));

                    if ($this->DmiCustomers->save($DmiCustomersEntity)) {

                        $DmiCustomersHistoryLogsEntity = $this->DmiCustomersHistoryLogs->newEntity(array(
                            'customer_id' => $new_customer_id,
                            'f_name' => $htmlencodedfname,
                            'm_name' => $htmlencodedmname,
                            'l_name' => $htmlencodedlname,
                            'street_address' => $htmlencodedstreetaddress,
                            'state' => $state,
                            'district' => $district,
                            'postal_code' => $htmlencodedpostalcode,
                            'email' => $htmlencodedemail,
                            'password' => '91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
							//'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2',//123
                            'mobile' => base64_encode($htmlencodedmobile), //This is added on 27-04-2021 for base64encoding by AKASH
                            'landline' => base64_encode($htmlencodedlandline), //This is added on 27-04-2021 for base64encoding by AKASH
                            //'once_card_no'=>$encrypted_aadhar, //commented on 22-03-2018
                            'document' => $document,
                            'photo_id_no' => $htmlencodedphoto_id_no,
                            //'file'=>$uploadedfile,
                            'created' => date('Y-m-d H:i:s'),
                            'modified' => date('Y-m-d H:i:s')
                        ));

                        $this->DmiCustomersHistoryLogs->save($DmiCustomersHistoryLogsEntity);

                        //called function to send link for reset password on registered email //on 13-02-2018 by Amol
                        $this->Authentication->forgotPasswordLib('DmiCustomers', $htmlencodedemail, $new_customer_id);

                        $primary_registered = 'done';
                        $this->set('primary_registered', $primary_registered);
                        $this->set('new_customer_id', $new_customer_id);
                        $this->set('htmlencodedemail', $htmlencodedemail);

                        #SMS: New Customer Registered
                        $this->DmiSmsEmailTemplates->sendMessage(1, $new_customer_id);

                        //saving log for passes attempts of aadhar authentication //added on 16-06-2020 by Amol, on suggestion from Tarun Sir to take logs
                        if (!empty($this->request->getData('once_card_no')) && $this->request->getData('aadhar_auth_check') == 1) {

                            $aadhar_txn_id=null;
                            $this->loadModel('DmiOnceTokenDetails');
                            $onceTokenEntity = $this->DmiOnceTokenDetails->newEntity(array(
                                'user_id' => $new_customer_id,
                                'once_token_id' => null,
                                'created' => date('Y-m-d H:i:s'),
                                'status' => 'Passed',
                                'txn_id' => $aadhar_txn_id
                            ));

                            $this->DmiOnceTokenDetails->save($onceTokenEntity);
                        }

                    } else {

                        $message = 'Your details are not saved please check again';
                        $message_theme = 'warning';
                        $redirect_to = 'register_customer';

                    }

                } else {

                    //above code commented & below code added on 13-07-2018 by pravin
                    $used_email_error_msg = 'This email id is already registered with us. Please register with another email id.';
                    $this->set('used_email_error_msg', $used_email_error_msg);
                    return null;
                    exit;
                }

            } else {

                $captcha_error_msg = 'Sorry... Wrong Code Entered';
                $this->set('captcha_error_msg', $captcha_error_msg);
                return null;
                exit;
            }
        }

        // set variables to show popup messages from view file
        $this->set('message', $message);
        $this->set('message_theme',$message_theme);
        $this->set('return_error_msg',null);
        $this->set('redirect_to', $redirect_to);

    }


      //Primary HOME Method
      public function primaryHome() {

        //Set the Layout
        $this->viewBuilder()->setLayout('corporate_customer');
        //Load Models
        $this->loadModel('DmiDistricts');
        $this->loadModel('DmiFirms');
        $this->loadModel('DmiFinalSubmits');
        $this->loadModel('DmiCertificateTypes');
        $this->loadModel('DmiStates');
        $this->loadModel('MCommodityCategory');
        $this->loadModel('DmiDistricts');

        if ($this->getRequest()->getSession()->read('username') == null) {

			$this->customAlertPage("Sorry You are not authorized to view this page..");
            exit;

        } else {//this else portion added on 10-07-2017 by Amol to allow only logged in Applicant

            //checking applicant id pattern ex.102/1/PUN/006
            if (preg_match("/^[0-9]+\/[0-9]+$/", $this->getRequest()->getSession()->read('username'), $matches) == 1) {
                //Give Permission
            } else {

                $this->customAlertPage("Sorry You are not authorized to view this page..");
				exit;
            }
        }

        if (null !== ($this->request->getData('search_firms'))) {

            $firm_name = strtolower($this->request->getData('firm_name'));
            $firm_district = strtolower($this->request->getData('firm_district'));

            $id_not_empty = array();
            $name_not_empty = array();
            $district_not_empty = array();

            if ($this->request->getData('firm_id') != null) {

                $id_not_empty = array('customer_primary_id' => $this->getRequest()->getSession()->read('username'), 'customer_id' => $this->request->getData('firm_id'));
            }

            if ($this->request->getData('firm_name') != null) {

                $name_not_empty = array('customer_primary_id' => $this->getRequest()->getSession()->read('username'), 'LOWER(firm_name) LIKE' => '%' . $firm_name . '%');

            }


            if ($this->request->getData('firm_district') != null) {

                $find_district_id = $this->DmiDistricts->find('all', array('fields' => 'id', 'conditions' => array('LOWER(district_name) Like' => '%' . $firm_district . '%')))->first();

                if (!empty($find_district_id)) {

                    $district_id = $find_district_id['id'];

                } else {

                    $district_id = null;
                }

                $district_not_empty = array('customer_primary_id' => $this->getRequest()->getSession()->read('username'), 'district IS' => $district_id);

            }

            $this->paginate = array('conditions' => Hash::merge($id_not_empty, $name_not_empty, $district_not_empty, array('delete_status' => null)), 'limit' => 6, 'order' => array('id' => 'desc'));

            //this condition added on 05-05-2017 by Amol(to solve search in pagination error)
            if ($this->request->getData('firm_id') == '' && $this->request->getData('firm_name') == '' && $this->request->getData('firm_district') == '') {
                //for pagination
                $firms_details = $this->paginate('Dmi_firm');
            } else {
                // For normal search, show result without pagination
                $firms_details = $this->DmiFirms->find('all', array('order' => array('id' => 'asc'), 'conditions' => Hash::merge($id_not_empty, $name_not_empty, $district_not_empty, array('delete_status IS NULL'))))->toArray();
            }

        } else {

            $this->paginate = array('conditions' => array('customer_primary_id IS' => $this->getRequest()->getSession()->read('username'), array('delete_status IS NULL')),'order' => array('id' => 'desc'));
            //for pagination
            $firms_details = $this->paginate('DmiFirms');
            $firms_details = $firms_details->toArray();

        }

        //displaced from above loop to outside and make it single for both scenario.//on 05/5/2017 by Amol
        if (!empty($firms_details)) {

            $i = 0;
            $application_status = array();//added on 11-10-2017 by Amol

            foreach ($firms_details as $firms_detail) {

                $customer_id = $firms_detail['customer_id'];
                $split_customer_id = explode('/',(string) $customer_id); #For Deprecations

                //added on 05/05/2017 by Amol(to show delete firm button) //check this applicant id is final submitted or not to show dlete firm button
                $final_submit_done[$i] = $this->DmiFinalSubmits->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->first();

                //fetch id from array and display values from table in list
                $certification_type = $this->DmiCertificateTypes->find('all', array('fields' => 'certificate_type', 'conditions' => array('id IS' => $firms_detail['certification_type'])))->first();
                $firms_details[$i]['certification_type'] = $certification_type['certificate_type'];

                $commodity = $this->MCommodityCategory->find('all', array('fields' => 'category_name', 'conditions' => array('category_code IS' => $firms_detail['commodity'])))->first();
                $firms_details[$i]['commodity'] = $commodity['category_name'];

                $state = $this->DmiStates->find('all', array('fields' => 'state_name', 'conditions' => array('id IS' => $firms_detail['state'])))->first();
                $firms_details[$i]['state'] = $state['state_name'];

                $district = $this->DmiDistricts->find('all', array('fields' => 'district_name', 'conditions' => array('id IS' => $firms_detail['district'])))->first();
                $firms_details[$i]['district'] = $district['district_name'];

                //added on 11-10-2017 by Amol
                $application_status[$i] = $this->Customfunctions->getApplicationCurrentStatus($customer_id);

                $i = $i + 1;
            }

            $this->set('firms_details', $firms_details);
            $this->set('final_submit_done', $final_submit_done);
            $this->set('application_status', $application_status);//added on 11-10-2017 by Amol

        } else {

            $this->set('firms_details', $firms_details = array());
        }

    }


    //Reset Password function Starts - Updated with new changes on 28-04-2021 By Akash.
    public function resetPassword() {

        // set variables to show popup messages from view file
        $message = '';
        $message_theme = '';
        $redirect_to = '';
        //Set the layout
        $this->viewBuilder()->setLayout('form_layout');

        if (empty($_GET['$key']) || empty($_GET['$id'])) {

			$this->customAlertPage("Sorry You are not authorized to view this page..");
            exit;

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

                $this->customAlertPage("Sorry You are not authorized to view this page..");
				exit;
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
                    $username = $this->request->getData('customer_id');
                    $countspecialchar = substr_count($username, "/");
                    $postdata = $this->request->getData();

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
					
                    // calling reset password library function
                    $reset_pass_result = $this->Authentication->resetPasswordLib($table, $username, $newpassdata, $randsalt,$postdata);
					
                    if ($reset_pass_result == 1) {

						$this->Customfunctions->saveActionPoint('Reset Password (Email Not Matched)','Failed',$user_id); #Action
                        $email_id_not_matched_msg = 'Email id & User Id not Matched.';
                        $this->set('email_id_not_matched_msg', $email_id_not_matched_msg);
                        return null;
                        exit;

                    } elseif ($reset_pass_result == 2) {

						$this->Customfunctions->saveActionPoint('Reset Password (Incorrect Captcha)','Failed',$user_id); #Action
                        $incorrect_captcha_msg = 'Incorrect Captcha code entered.';
                        $this->set('incorrect_captcha_msg', $incorrect_captcha_msg);
                        return null;
                        exit;

                    } elseif ($reset_pass_result == 3) {

						$this->Customfunctions->saveActionPoint('Reset Password (Password Not Macthed)','Failed',$user_id); #Action
                        $comfirm_pass_msg = 'Confirm password not matched';
                        $this->set('comfirm_pass_msg', $comfirm_pass_msg);
                        return null;
                        exit;

                    } elseif ($reset_pass_result == 4) {

						$this->Customfunctions->saveActionPoint('Reset Password (Password is Same as Last)','Failed',$user_id); #Action
                        // SHOW ERROR MESSAGE IF NEW PASSWORD FOUND UNDER LAST THREE PASSWORDS OF USER // By Aniket Ganvir dated 16th NOV 2020
                        $comfirm_pass_msg = 'This password matched with your last three passwords, Please enter different password';
                        $this->set('comfirm_pass_msg', $comfirm_pass_msg);
                        return null;
                        exit;

                    } else {
                        
						$this->Customfunctions->saveActionPoint('Reset Password','Success',$user_id); #Action
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


    //Forgot Password method starts
    public function forgotPassword() {

        // set variables to show popup messages from view file
        $message = '';
        $message_theme = '';
        $redirect_to = '';
        //set the Layout
        $this->viewBuilder()->setLayout('form_layout');

        if ($this->request->is('post')) {

            if ($this->request->getData('captcha') != "" && $this->Session->read('code') == $this->request->getData('captcha')) {

                $username = $this->request->getData('customer_id');

                $countspecialchar = substr_count($username, "/");

                if ($countspecialchar == 1) {
                    $table = 'DmiCustomers';
                } elseif ($countspecialchar == 3) {
                    $table = 'DmiFirms';
                } elseif ($countspecialchar == 2) {
                    $table = 'DmiChemistRegistrations';
                } else {
                    $message = 'Sorry...User Id entered is not valid';
                    $message_theme = 'failed';
                    $redirect_to = 'forgot_password';
                }

                $this->loadModel($table);

                $emailforrecovery = $this->request->getData('email');

                // For chemist module, Done by Pravin Bhakare 4/08/2021
                //check if Customer ID & Email Match in record.
                if($countspecialchar == 2){

                    $check_valid_record = $this->$table->find('all', array('conditions' => array('email IS' => base64_encode($emailforrecovery), 'chemist_id IS' => $username)))->first();
                
                } else {
                        
                    $check_valid_record = $this->$table->find('all', array('conditions' => array('email IS' => base64_encode($emailforrecovery), 'customer_id IS' => $username)))->first();
                }

                if (empty($check_valid_record)) {

                    $message = 'Sorry... Provided Applicant ID & Email Id does not Matched.';
                    $message_theme = 'failed';
                    $redirect_to = 'forgot_password';

                } else {

                    $forgot_password_result = $this->Authentication->forgotPasswordLib($table, $emailforrecovery, $username);//added new parameter username on 25-10-2018

                    // show forgot password failed messgae (by pravin 27/05/2017)
                    if ($forgot_password_result == 1) {

                        $message = 'Sorry Restricted... This email is not authorized';
                        $message_theme = 'failed';
                        $redirect_to = 'forgot_password';

                    } elseif ($forgot_password_result == 2) {

                        //get applicant email id and apply masking before showing in message by Amol on 25-02-2021
                        $emailforrecovery = $this->Customfunctions->getMaskedValue($emailforrecovery, 'email');
                        $message = 'Change password link sent on ' . $emailforrecovery;
                        $message_theme = 'success';
                        $redirect_to = 'forgot_password';

                    }

                }

            } else {

                $message = 'Sorry...Wrong Captcha Code Entered';
                $message_theme = 'failed';
                $redirect_to = 'forgot_password';
            }
        }
        // set variables to show popup messages from view file
        $this->set('message', $message);
        $this->set('message_theme', $message_theme);
        $this->set('redirect_to', $redirect_to);

    }
    //Forgot Password method ends


 
    // Method to view and update primary customer profile
    public function customerProfile() {

        // error_reporting('0'); // needtoremove
        // SET MENU NAME FOR CURRENT ACTIVE MENU IN SIDEBAR
        $this->set('current_menu', 'menu_profile');

        $this->loadModel('DmiPermittedOnceUpdations');
        $this->loadModel('DmiCustomers');
        $this->loadModel('DmiStates');
        $this->loadModel('DmiDistricts');
        $this->loadModel('DmiDocumentLists');
        $this->loadModel('DmiCustomersHistoryLogs');

        if ($this->getRequest()->getSession()->read('username') == null) {

			$this->customAlertPage("Sorry You are not authorized to view this page..");
            exit;

        } else {//this else portion added on 10-07-2017 by Amol to allow only logged in Applicant

            //checking applicant id pattern ex.102/2016
            if (preg_match("/^[0-9]+\/[0-9]+$/", $this->getRequest()->getSession()->read('username'), $matches) == 1) {
                //Give Permission
            } else {

                $this->customAlertPage("Sorry You are not authorized to view this page..");
                exit;
            }

        }


        // set variables to show popup messages from view file
        $message = '';
        $message_theme = '';
        $redirect_to = '';

        $this->viewBuilder()->setLayout('corporate_customer');
        $customer_id = $this->getRequest()->getSession()->read('username');

        //get details from aadhar updation permission table to make aadhar & mobile field available to change //applied on 03-02-2018 by Amol
        $once_update_permission = $this->DmiPermittedOnceUpdations->find('list', array('valueField' => 'id', 'conditions' => array('primary_applicant_id IS' => $this->getRequest()->getSession()->read('username'))))->toArray();
        $aadhar_change_status = null;

        if (!empty($once_update_permission)) {

            $get_last_change_status = $this->DmiPermittedOnceUpdations->find('all', array('conditions' => array('id' => max($once_update_permission))))->first();
            $aadhar_change_status = $get_last_change_status['change_status'];
        }

        $this->set('aadhar_change_status', $aadhar_change_status);


        $customer_data = $this->DmiCustomers->find('all', array('conditions' => array('customer_id IS' => $this->getRequest()->getSession()->read('username'))))->first();

        //get personal details masked by custom function to show in secure mode //applied on 12-10-2017 by Amol
        $customer_data['mobile'] = $this->Customfunctions->getMaskedValue(base64_decode($customer_data['mobile']), 'mobile'); //This is addded on 27-04-2021 for base64decoding by AKASH
        $customer_data['email'] = $this->Customfunctions->getMaskedValue(base64_decode($customer_data['email']), 'email');
		


        $this->set('customer_data', $customer_data);

        // Apply "Order by" clause to get state list by order wise (Done By Pravin 10-01-2018)
        $states = $this->DmiStates->find('list', array('valueField' => 'state_name', 'conditions' => array('OR' => array('delete_status IS NULL', 'delete_status =' => 'no')), 'order' => array('state_name')))->toArray();
        $this->set('states', $states);

        /*error solve on district value by pravin 15/4/2017*/
        $districts = $this->DmiDistricts->find('list', array('valueField' => 'district_name', 'conditions' => array('state_id IS' => $customer_data['state'])))->toArray();
        $this->set('districts', $districts);

        $document_lists = $this->DmiDocumentLists->find('list', array('valueField' => 'document_name', 'conditions' => array('delete_status IS NULL')))->toArray(); // Changed the query on the 09-08-2022 by Akash
        $this->set('document_lists', $document_lists);


        $selected_states_query = $this->DmiStates->find('all', array('fields' => 'id', 'conditions' => array('id IS' => $customer_data['state'])))->first();
        $selected_states_value = $selected_states_query['id'];
        $this->set('selected_states_value', $selected_states_value);


        $selected_districts_query = $this->DmiDistricts->find('all', array('fields' => 'id', 'conditions' => array('id IS' => $customer_data['district'])))->first();
        $selected_districts_value = $selected_districts_query['id'];
        $this->set('selected_districts_value', $selected_districts_value);


        $selected_document_lists_query = $this->DmiDocumentLists->find('all', array('fields' => 'id', 'conditions' => array('id IS' => $customer_data['document'])))->first();
        $selected_document_lists_value = $selected_document_lists_query['id'];
        $this->set('selected_document_lists_value', $selected_document_lists_value);


        if (null !== $this->request->getData('back')) {

            $this->redirect(array('controller' => 'customers', 'action' => 'primary_home'));

        } elseif (null !== $this->request->getData('update')) {

            //check email already exist to avoid duplicates //applied on 19-06-2018
            $Checkemailexist = $this->DmiCustomers->find('all', array('fields' => 'email', 'conditions' => array('email IS' => $this->request->getData('email'), 'customer_id !=' => $customer_id)))->first();

            if ($Checkemailexist == null) {

                //applied condition to check all post data for !empty validation on server side
                //on 21/10/2017 by Amol
                if (!empty($this->request->getData('f_name')) && !empty($this->request->getData('l_name')) && !empty($this->request->getData('street_address')) &&
                    !empty($this->request->getData('state')) && !empty($this->request->getData('district')) && !empty($this->request->getData('postal_code')) &&
                    !empty($this->request->getData('email')) && !empty($this->request->getData('mobile')) &&
                    !empty($this->request->getData('document')) && !empty($this->request->getData('photo_id_no'))) {

                    if (!$this->Customfunctions->validateUniquePostData($this->request->getData('mobile'), 'mobile') == 1) {

                       $this->set('return_error_msg','Please enter proper Mobile no.');
                        return false;
                        exit;
                    }
                    if (!$this->Customfunctions->validateUniquePostData($this->request->getData('email'), 'email') == 1) {

                       $this->set('return_error_msg','Please enter proper Email id');
                        return false;
                        exit;
                    }

                    if ($this->request->getData('file')->getClientFilename() != null) {

                        $attachment = $this->request->getData('file');
                        $file_name = $attachment->getClientFilename();
                        $file_size = $attachment->getSize();
                        $file_type = $attachment->getClientMediaType();
                        $file_local_path = $attachment->getStream()->getMetadata('uri');
                        // calling file uploading function
                        $uploadedfile = $this->Customfunctions->fileUploadLib($file_name, $file_size, $file_type, $file_local_path);

                    } else {

                        $uploadedfile = $customer_data['file'];
                    }

                    //html encoding start
                    $htmlencodedfname = htmlentities($this->request->getData('f_name'), ENT_QUOTES);
                    $htmlencodedmname = htmlentities($this->request->getData('m_name'), ENT_QUOTES);
                    $htmlencodedlname = htmlentities($this->request->getData('l_name'), ENT_QUOTES);
                    $htmlencodedstreetaddress = htmlentities($this->request->getData('street_address'), ENT_QUOTES);
                    $htmlencodedpostalcode = htmlentities($this->request->getData('postal_code'), ENT_QUOTES);
                    $htmlencodedemail = base64_encode(htmlentities($this->request->getData('email'), ENT_QUOTES));//for email encoding
                    $htmlencodedmobile = htmlentities(base64_encode($this->request->getData('mobile')), ENT_QUOTES); //This is addded on 27-04-2021 for base64encoding by AKASH
                    $htmlencodedlandline = htmlentities(base64_encode($this->request->getData('landline')), ENT_QUOTES);//This is addded on 27-04-2021 for base64encoding by AKASH
                    $htmlencodedphoto_id_no = htmlentities($this->request->getData('photo_id_no'), ENT_QUOTES);

                    //for business_type
                    $table = 'DmiDocumentLists';
                    $post_input_request = $this->request->getData('document');
                    //calling library function
                    $document = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request);


                    //for state
                    $table = 'DmiStates';
                    $post_input_request = $this->request->getData('state');
                    //calling library function
                    $state = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request);


                    //for district
                    $table = 'DmiDistricts';
                    $post_input_request = $this->request->getData('district');
                    //calling library function
                    $district = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request);

                    $fetch_id_query = $this->DmiCustomers->find('all', array('conditions' => array('customer_id IS' => $this->getRequest()->getSession()->read('username'))))->first();
                    $fetch_id = $fetch_id_query['id'];

                    if (preg_match("/^[X-X]{6}[0-9]{4}$/i", $this->request->getData('mobile'), $matches) == 1) {
                        $htmlencodedmobile = $fetch_id_query['mobile'];
                    }

                    //called custom function
                    $email_masked_value = $this->Customfunctions->getEmailMasked(base64_decode($fetch_id_query['email']));//for email encoding
                    if ($email_masked_value == $this->request->getData('email')) {

                        $htmlencodedemail = $fetch_id_query['email'];
                    }

                    //added on 06-05-2021 for profile pic
                    if ($this->request->getData('profile_pic')->getClientFilename() != null) {

                        $attachment = $this->request->getData('profile_pic');
                        $file_name = $attachment->getClientFilename();
                        $file_size = $attachment->getSize();
                        $file_type = $attachment->getClientMediaType();
                        $file_local_path = $attachment->getStream()->getMetadata('uri');
                        // calling file uploading function
                        $profile_pic = $this->Customfunctions->fileUploadLib($file_name, $file_size, $file_type, $file_local_path);

                    } else {
                        $profile_pic = $fetch_id_query['profile_pic'];
                    }

                    $DmiCustomersEntity = $this->DmiCustomers->newEntity(array(
                        'id' => $fetch_id,
                        'f_name' => $htmlencodedfname,
                        'm_name' => $htmlencodedmname,
                        'l_name' => $htmlencodedlname,
                        'street_address' => $htmlencodedstreetaddress,
                        'state' => $state,
                        'district' => $district,
                        'postal_code' => $htmlencodedpostalcode,
                        'email' => $htmlencodedemail,
                        'mobile' => $htmlencodedmobile,
                        'landline' => $htmlencodedlandline,
                        //'once_card_no'=>$encrypted_aadhar, //commented on 23-03-2018 to avoid mandatory for aadhar
                        'document' => $document,
                        'photo_id_no' => $htmlencodedphoto_id_no,
                        'file' => $uploadedfile,
                        'modified' => date('Y-m-d H:i:s'),
                        'profile_pic' => $profile_pic
                    ));

                    if ($this->DmiCustomers->save($DmiCustomersEntity)) {

                        $DmiCustomersHistoryLogsEntity = $this->DmiCustomersHistoryLogs->newEntity(array(
                            'customer_id' => $customer_id,
                            'f_name' => $htmlencodedfname,
                            'm_name' => $htmlencodedmname,
                            'l_name' => $htmlencodedlname,
                            'street_address' => $htmlencodedstreetaddress,
                            'state' => $state,
                            'district' => $district,
                            'postal_code' => $htmlencodedpostalcode,
                            'email' => $htmlencodedemail,
                            'password' => $customer_data['password'],
                            'mobile' => $htmlencodedmobile,
                            'landline' => $htmlencodedlandline,
                            //'once_card_no'=>$encrypted_aadhar, //commented on 23-03-2018 to avoid mandatory for aadhar
                            'document' => $document,
                            'photo_id_no' => $htmlencodedphoto_id_no,
                            'file' => $uploadedfile,
                            'created' => date('Y-m-d H:i:s'),
                            'modified' => date('Y-m-d H:i:s'),
                            'profile_pic' => $profile_pic
                        ));

                        $this->DmiCustomersHistoryLogs->save($DmiCustomersHistoryLogsEntity);

                        //update aadhar permission table to done from 'in_progree' in entry exist
                        if (!empty($once_update_permission)) {

                            if ($aadhar_change_status == 'in_progress') {

                                $DmiPermittedOnceUpdationsEntity = $this->DmiPermittedOnceUpdations->newEntity(array(
                                    'id' => $get_last_change_status['id'],
                                    'change_status' => 'done',
                                    'modified' => date('Y-m-d H:i:s')
                                ));

                                $this->DmiPermittedOnceUpdations->save($DmiPermittedOnceUpdationsEntity);
                            }
                        }


                        #SMS: Customer Profile Update
                        $this->DmiSmsEmailTemplates->sendMessage(2,$customer_id);
					
						//Added this call to save the user action log on 01-03-2022
						$this->Customfunctions->saveActionPoint('Profile Update','Success');
                        $message = 'Your details are updated successfully';
                        $message_theme = 'success';
                        $redirect_to = 'customer_profile';

                    } else {
						
						//Added this call to save the user action log on 01-03-2022
						$this->Customfunctions->saveActionPoint('Profile Update','Failed');
                        $message = 'Your details are not saved please check again';
                        $message_theme = 'failed';
                        $redirect_to = 'customer_profile';
                    }

                } else {
					
					//Added this call to save the user action log on 01-03-2022
					$this->Customfunctions->saveActionPoint('Profile Update','Failed');
                    $this->set('return_error_msg','Please check some fields are not entered');
                    return null;
                    exit;
                }

            } else {
				
				//Added this call to save the user action log on 01-03-2022
				$this->Customfunctions->saveActionPoint('Profile Update','Failed');
                $message = 'This email id is already exist. Please provide another email id to update. Thankyou.';
                $message_theme = 'failed';
                $redirect_to = 'customer_profile';
            }
        }

        // set variables to show popup messages from view file
        $this->set('message', $message);
        $this->set('message_theme', $message_theme);
        $this->set('return_error_msg',null);
        $this->set('redirect_to', $redirect_to);

        // PRIOR TO THE CAKEPHP 4, "$this->view" IS DEPRECATED,
        // SO ADDED "render" PROPERTY TO POP UP FORM RELATED MESSAGES
        // by Aniket Ganvir dated 29th JAN 2021
        if ($message != null) {
            $this->render('/element/message_boxes');
        }

    }



    //Secondary Home Method
    public function secondaryHome() {

        $customer_id = $this->Session->read('username');

        if ($customer_id == null) {

			$this->customAlertPage("Sorry You are not authorized to view this page..");
            exit;

        } else {//this else portion added on 10-07-2017 by Amol to allow only logged in Applicant

            //checking applicant id pattern ex.102/1/PUN/006
            if (preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $customer_id, $matches) == 1) {
                //Give Permission
            } else {

                $this->customAlertPage("Sorry You are not authorized to view this page..");
                exit;
            }
        }

        //Set the Layout
        $this->viewBuilder()->setLayout('secondary_customer');
        //Load Models
        $this->loadModel('DmiApplicationPdfRecords');
        $this->loadModel('DmiRenewalApplicationPdfRecords');
        $this->loadModel('DmiFinalSubmits');
        $this->loadModel('DmiRenewalFinalSubmits');
        $this->loadModel('DmiGrantCertificatesPdfs');
        $this->loadModel('DmiFirms');
        $this->loadModel('DmiDistricts');

        // to get export unit added by shankhpal shende on 08/11/2022 
        $export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
		$this->set('export_unit_status',$export_unit_status);

        //Set Granr Date Condition
        $grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

        //to show application pdfs list
        $application_pdfs = $this->DmiApplicationPdfRecords->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->toArray();
        $this->set('application_pdfs', $application_pdfs);

        //to show Renewal application pdfs list
        $renewal_application_pdfs = $this->DmiRenewalApplicationPdfRecords->find('all', array('conditions' => array('customer_id IS' => $customer_id, $grantDateCondition)))->toArray();
        $this->set('renewal_application_pdfs', $renewal_application_pdfs);


        //check final submit status to show final grant certificate on home
        $list_final_submit_id = $this->DmiFinalSubmits->find('list', array('valueField' => 'id', 'conditions' => array('customer_id IS' => $customer_id)))->toArray();

        if (!empty($list_final_submit_id)) {

            $final_submit_query = $this->DmiFinalSubmits->find('all', array('fields' => array('status', 'current_level'), 'conditions' => array('id' => max($list_final_submit_id))))->first();
            $final_submit_status = $final_submit_query['status'];
            $final_submit_level = $final_submit_query['current_level'];
            $this->set('final_submit_status', $final_submit_status);
            $this->set('final_submit_level', $final_submit_level);

        } else {

            $final_submit_status = 'no_final_submit';
            $this->set('final_submit_status', $final_submit_status);
        }


        //applied query to get renewal final submit details //on 29-09-2017 by Amol
        $renewal_final_submit_details = $this->DmiRenewalFinalSubmits->find('list', array('valueField' => 'id', 'conditions' => array('customer_id IS' => $customer_id)))->toArray();
        $this->set('renewal_final_submit_details', $renewal_final_submit_details);

        if (!empty($renewal_final_submit_details)) {

            $renewal_final_submit_query = $this->DmiRenewalFinalSubmits->find('all', array('fields' => array('status', 'current_level'), 'conditions' => array('id' => max($renewal_final_submit_details))))->first();
            $renewal_final_submit_status = $renewal_final_submit_query['status'];
            $renewal_final_submit_level = $renewal_final_submit_query['current_level'];
            $this->set('renewal_final_submit_status', $renewal_final_submit_status);
            $this->set('renewal_final_submit_level', $renewal_final_submit_level);

        } else {

            $renewal_final_submit_status = 'no_final_submit';
            $this->set('renewal_final_submit_status', $renewal_final_submit_status);
        }


        $grant_certificate_pdf = null;
        $valid_upto_date = null;
        $fetch_max_pdf_id = $this->DmiGrantCertificatesPdfs->find('all', array('fields' => 'id', 'conditions' => array('customer_id IS' => $customer_id), 'order' => array('id desc')))->first();

        if (empty($fetch_max_pdf_id)) {
            $fetch_max_pdf_id = '';
        } else {
            $fetch_max_pdf_id = $fetch_max_pdf_id['id'];
        }

        $grant_certificate_pdf = $this->DmiGrantCertificatesPdfs->find('all', array('conditions' => array('id IS' => $fetch_max_pdf_id)))->toArray();

        if (!empty($grant_certificate_pdf)) {
            //below logic added on 04-06-2019 by Amol, to show valid upto date to applicant
            $grant_date = chop($grant_certificate_pdf[0]['date'], "00:00:00");
            $valid_upto_date = str_replace('-', '/', $this->Customfunctions->getCertificateValidUptoDate($customer_id, $grant_date));
        }

        $this->set('valid_upto_date', $valid_upto_date);
        $this->set('grant_certificate_pdf', $grant_certificate_pdf);


        //check condition to show "Applied to" popup on home page // on 17-08-2018
        $get_dist_id = $this->DmiFirms->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->first();
        $dist_id = $get_dist_id['district'];

        $district_details = $this->DmiDistricts->find('all', array('conditions' => array('id IS' => $dist_id)))->first();
        $smd_id = $district_details['smd_id'];

        $show_applied_to_popup = null;

        if (!empty($smd_id) && empty($final_submit_status)) {

            $show_applied_to_popup = 'yes';
        }

        $this->set('show_applied_to_popup', $show_applied_to_popup);

        //added on 17-08-2018 to update dmi_firm table with login id record, set applied_to field
        if (null !== $this->request->getData('applying_to')) {
            $applied_to = $this->request->getData('applied_to');
            $this->DmiFirms->updateAll(array('applied_to' => "$applied_to"), array('customer_id' => $customer_id));
        }

        //to show application and certificate pdf for 15 digit code approval
        //on 11-11-2021 by Amol
        $this->loadModel('Dmi15DigitPdfRecords');
        $appl_15_digit_pdfs = $this->Dmi15DigitPdfRecords->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->toArray();
        $this->set('appl_15_digit_pdfs', $appl_15_digit_pdfs);

        //added on 17-11-2021 for 15 digit code
        $this->loadModel('Dmi15DigitGrantCertificatePdfs');
        $cert_15_digit_pdfs = $this->Dmi15DigitGrantCertificatePdfs->find('all', array('conditions' => array('customer_id IS' => $customer_id), 'order' => array('id desc')))->toArray();
        $this->set('cert_15_digit_pdfs', $cert_15_digit_pdfs);

        //to show application and certificate pdf for E-code approval
        //on 18-11-2021 by Amol
        $this->loadModel('DmiECodePdfRecords');
        $appl_e_code_pdfs = $this->DmiECodePdfRecords->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->toArray();
        $this->set('appl_e_code_pdfs', $appl_e_code_pdfs);

        //added on 18-11-2021 for E-code
        $this->loadModel('DmiECodeGrantCertificatePdfs');
        $cert_e_code_pdfs = $this->DmiECodeGrantCertificatePdfs->find('all', array('conditions' => array('customer_id IS' => $customer_id), 'order' => array('id desc')))->toArray();
        $this->set('cert_e_code_pdfs', $cert_e_code_pdfs);

        //To check if the application is rejected or junked and set the custom message - Akash[14-11-2022]
        $is_appl_rejected =  $this->Customfunctions->isApplicationRejected($customer_id);
        $this->set('is_appl_rejected',$is_appl_rejected);

                
        //to show application certificate pdf for Approval of Designated persons
        //on 18-11-2022 by Shankhpal Shende
        $this->loadModel('DmiAdpPdfRecords');
        $appl_adp_pdfs_records = $this->DmiAdpPdfRecords->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->toArray();
        $this->set('appl_adp_pdfs_records', $appl_adp_pdfs_records);

        //to show Grant certificate pdf for Approval of Designated persons
        //on 18-11-2022 by Shankhpal Shende
        $this->loadModel('DmiAdpGrantCertificatePdfs');
        $appl_adp_grant_pdfs = $this->DmiAdpGrantCertificatePdfs->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->toArray();
        $this->set('appl_adp_grant_pdfs', $appl_adp_grant_pdfs);
		
		
		//to show application pdf for change/modification 
        //on 13-12-2022 by Amol
        $this->loadModel('DmiChangePdfRecords');
        $appl_change_records = $this->DmiChangePdfRecords->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->toArray();
        $this->set('appl_change_records', $appl_change_records);

        //to show Certificate pdf for change/modification 
        //on 13-12-2022 by Amol
        $this->loadModel('DmiChangeGrantCertificatesPdfs');
        $appl_change_grant_pdfs = $this->DmiChangeGrantCertificatesPdfs->find('all', array('conditions' => array('customer_id IS' => $customer_id)))->toArray();
        $this->set('appl_change_grant_pdfs', $appl_change_grant_pdfs);


		
    }


    //To Commonly Show Replica Status
    public function replicaAllotedList() {

        //Set the Layout
        $this->viewBuilder()->setLayout('secondary_customer');
        //Calling the replica alloted list
        $this->Customfunctions->replicaAllotedListCall('replica');

    }

	//to show alloted 15 digit codes list, on 26-11-2021
	public function alloted15DigitList() {

        $this->viewBuilder()->setLayout('secondary_customer');
        $this->Customfunctions->replicaAllotedListCall('15Digit');

    }

	//to show alloted E-Code codes list, on 26-11-2021
	public function allotedECodeList() {

        $this->viewBuilder()->setLayout('secondary_customer');
        $this->Customfunctions->replicaAllotedListCall('ECode');

    }




    //To Show the Registered Chemist List
    public function getAllChemistList() {

        //Set the Layout
        $this->viewBuilder()->setLayout('secondary_customer');
        $this->Session->delete('unset_incharge_id');
        $this->Session->delete('set_incharge_id');
        $this->Customfunctions->chemistList();
    }


    //To Fetch the Chemist ID for editing purpose
    public function fetchChemistId($chemist_id) {

        $this->Session->write('alloc_chemist_id',$chemist_id);
        $this->redirect(array('controller'=>'customers','action'=>'allotChemist'));

    }



    //Set Chemist Incharge Method
    public function setChemistIncharge($chemist_id) {

        $this->Session->write('set_incharge_id',$chemist_id);
        $this->redirect(array('controller'=>'customers','action'=>'setIncharge'));

    }


    //Unset the Incharge Method
    public function unsetChemistIncharge($chemist_id) {

        $this->Session->write('unset_incharge_id',$chemist_id);
        $this->redirect(array('controller'=>'customers','action'=>'unsetIncharge'));

    }


    //Set Inchagre Method
    public function setIncharge() {

	$this->viewBuilder()->setLayout('secondary_customer');
        //Set the blank variables for the Displaying messages
        $message = '';
        $message_theme = '';
        $redirect_to = '';

        $set_incharge = $this->Session->read('set_incharge_id');

        //Create the data entity for saving in the 'DmiChemistAllotments'
		$this->loadModel('DmiChemistAllotments');
        $DmiChemistAllotmentsEntity = $this->DmiChemistAllotments->newEntity(array(

            'id'=>$set_incharge,
            'incharge'=>'yes',
            'modified'=>date('Y-m-d H:i:s')

        ));

        //Save the data entity
        if ($this->DmiChemistAllotments->save($DmiChemistAllotmentsEntity)) {

            $message = 'You have successfully set the chemist incharge';
            $message_theme = 'success';
            $redirect_to = 'get_all_chemist_list';

        }

        //Set the variables for the mesasges
        $this->set('message',$message);
        $this->set('message_theme',$message_theme);
        $this->set('redirect_to',$redirect_to);

		if ($message != null) {
            $this->render('/element/message_boxes');
        }

    }



    //Unset Incharge Method
    public function unsetIncharge() {

		$this->viewBuilder()->setLayout('secondary_customer');
        $unset_incharge = $this->Session->read('unset_incharge_id');

        //Set the blank variables for the Displaying messages
        $message = '';
        $message_theme = '';
        $redirect_to = '';

        //Create the data entity for saving in the 'DmiChemistAllotments'
		$this->loadModel('DmiChemistAllotments');
        $DmiChemistAllotmentsEntity = $this->DmiChemistAllotments->newEntity(array(

            'id'=>$unset_incharge,
            'incharge'=>'no',
            'modified'=>date('Y-m-d H:i:s')

        ));

        //Save the data entity
        if ($this->DmiChemistAllotments->save($DmiChemistAllotmentsEntity)) {

            $message = 'You have successfully unset the chemist incharge';
            $message_theme = 'success';
            $redirect_to = 'get_all_chemist_list';
        }


        //Set the variables for the mesasges
        $this->set('message',$message);
        $this->set('message_theme',$message_theme);
        $this->set('redirect_to',$redirect_to);

		if ($message != null) {
            $this->render('/element/message_boxes');
        }

    }


    //Allot Chemist Method
    public function allotChemist() {

		$this->viewBuilder()->setLayout('secondary_customer');

        $chemist_id = $this->Session->read('alloc_chemist_id');
        $customer_id = $this->Session->read('username');

        //Set the blank variables for the Displaying messages
        $message = '';
        $message_theme = '';
        $redirect_to = '';

        //check if this table id is having entry or not
		$this->loadModel('DmiChemistRegistrations');
        $check_id = $this->DmiChemistRegistrations->find('all',array('conditions'=>array('id IS'=>$chemist_id)))->first();


		$this->loadModel('DmiChemistAllotments');
        //Create the data entity for saving in the 'DmiChemistAllotments'
        $DmiChemistAllotmentsEntity = $this->DmiChemistAllotments->newEntity(array(

            'chemist_id'=>$check_id['chemist_id'],
            'customer_id'=>$customer_id,
            'status'=>'1',
            'incharge'=>'no',
            'created'=>$check_id['created'],
            'modified'=>date('Y-m-d H:i:s'),
            'created_by'=>$check_id['created_by'],
            'usertype'=>$check_id['usertype']

        ));

        //Save the data entity
        if ($this->DmiChemistAllotments->save($DmiChemistAllotmentsEntity)) {

            $message = 'You have alloted the laboratory chemist yourself successfully';
            $message_theme = 'success';
            $redirect_to = 'get_all_chemist_list';
        }

        //Set the variables for the mesasges
        $this->set('message',$message);
        $this->set('message_theme',$message_theme);
        $this->set('redirect_to',$redirect_to);

		if ($message != null) {
            $this->render('/element/message_boxes');
        }

    }
	
	
//below functions are for testing purpose for email encoding process

    //function to check the string is already base64 encoded
    public function is_base64_encoded($data){
        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
           return TRUE;
        } else {
           return FALSE;
        }
    }
    //function to check is the string is proper email id
    public function is_email_valid($email_id){
        if (filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //function to encode email id columns for flow wise tables and speficied tables
/*    public function updateEmailWithId(){			
			
        $this->autoRender=false;

        $this->loadModel('DmiUsers');
        $this->loadModel('DmiFirms');

        //get users table ids list
    //    $userIds = $this->DmiUsers->find('list',array('keyField'=>'email','valueField'=>'id','order'=>'id ASC'))->toArray();

        //get users table ids list
    //    $firmIds = $this->DmiFirms->find('list',array('keyField'=>'email','valueField'=>'id','order'=>'id ASC'))->toArray();

        //for flow wise common tables
        $this->loadModel('DmiFlowWiseTablesLists');
        $flowWisetables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('id IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();
        
        foreach($flowWisetables as $eachFlow){

            $hoAllocationTable = $eachFlow['ho_level_allocation'];
            $amaApprovedTable = $eachFlow['ama_approved_application'];
            $hoCommentsTable = $eachFlow['ho_comment_reply'];
            $allocationTable = $eachFlow['allocation'];
            $moCommentsTable = $eachFlow['commenting_with_mo'];
            $currentPositionTable = $eachFlow['appl_current_pos'];
            $rosocommentTable = $eachFlow['ro_so_comments'];
            $grantTable = $eachFlow['grant_pdf'];
            $level4ROApproved = $eachFlow['level_4_ro_approved'];

            //for allocation table
            $this->loadModel($allocationTable);
            $getRecords = $this->$allocationTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $level_1 = $eachRecord['level_1'];
                    $level_2 = $eachRecord['level_2'];
                    $level_3 = $eachRecord['level_3'];
                    $current_level = $eachRecord['current_level'];
                    $level_4_ro = $eachRecord['level_4_ro'];
                    $level_4_mo = $eachRecord['level_4_mo'];
                       
                 //   $UpdateValueArray = array('level_1'=>"$level_1",'level_2'=>"$level_2",'level_3'=>"$level_3",'current_level'=>"$current_level",'level_4_ro'=>"$level_4_ro",'level_4_mo'=>"$level_4_mo");
                    
                    if($this->is_base64_encoded($level_1)==false && $this->is_email_valid($level_1)==true && !empty($level_1)){
                        $level_1 = base64_encode($level_1);
                        $this->$allocationTable->updateAll(array('level_1'=>"$level_1"),array('id'=>$record_id));
                    
                    }if($this->is_base64_encoded($level_2)==false && $this->is_email_valid($level_2)==true && !empty($level_2)){
                        $level_2 = base64_encode($level_2);
                        $this->$allocationTable->updateAll(array('level_2'=>"$level_2"),array('id'=>$record_id));
                    
                    }if($this->is_base64_encoded($level_3)==false && $this->is_email_valid($level_3)==true && !empty($level_3)){
                        $level_3 = base64_encode($level_3);
                        $this->$allocationTable->updateAll(array('level_3'=>"$level_3"),array('id'=>$record_id));
                    
                    }if($this->is_base64_encoded($current_level)==false && $this->is_email_valid($current_level)==true && !empty($current_level)){
                        $current_level = base64_encode($current_level);
                        $this->$allocationTable->updateAll(array('current_level'=>"$current_level"),array('id'=>$record_id));
                    
                    }if($this->is_base64_encoded($level_4_ro)==false && $this->is_email_valid($level_4_ro)==true && !empty($level_4_ro)){
                        $level_4_ro = base64_encode($level_4_ro);
                        $this->$allocationTable->updateAll(array('level_4_ro'=>"$level_4_ro"),array('id'=>$record_id));
                    
                    }if($this->is_base64_encoded($level_4_mo)==false && $this->is_email_valid($level_4_mo)==true && !empty($level_4_mo)){
                        $level_4_mo = base64_encode($level_4_mo);
                        $this->$allocationTable->updateAll(array('level_4_mo'=>"$level_4_mo"),array('id'=>$record_id));
                    }
                    
            }

            //for current position table
            $this->loadModel($currentPositionTable);
            $getRecords = $this->$currentPositionTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $current_user_email_id = $eachRecord['current_user_email_id'];
                    
                    

                    if($this->is_base64_encoded($current_user_email_id)==false && $this->is_email_valid($current_user_email_id)==true && !empty($current_user_email_id)){
                        $current_user_email_id = base64_encode($current_user_email_id);
                        $UpdateValueArray = array('current_user_email_id'=>"$current_user_email_id");
                        $this->$currentPositionTable->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
            }

            //for AMA approved table
            $this->loadModel($amaApprovedTable);
            $getRecords = $this->$amaApprovedTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $user_email_id = $eachRecord['user_email_id'];
                    

                    if($this->is_base64_encoded($user_email_id)==false && $this->is_email_valid($user_email_id)==true && !empty($user_email_id)){
                        $user_email_id = base64_encode($user_email_id);
                        $UpdateValueArray = array('user_email_id'=>"$user_email_id");
                        $this->$amaApprovedTable->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
            }

            //for grant table
            $this->loadModel($grantTable);
            $getRecords = $this->$grantTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $user_email_id = $eachRecord['user_email_id'];
                    
																				 

                    if($this->is_base64_encoded($user_email_id)==false && $this->is_email_valid($user_email_id)==true && !empty($user_email_id)){
                        $user_email_id = base64_encode($user_email_id);
						$UpdateValueArray = array('user_email_id'=>"$user_email_id");
                        $this->$grantTable->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
            }

            //for HO allocation table
            $this->loadModel($hoAllocationTable);
            $getRecords = $this->$hoAllocationTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $dy_ama = $eachRecord['dy_ama'];
                    $ho_mo_smo = $eachRecord['ho_mo_smo'];
                    $jt_ama = $eachRecord['jt_ama'];
                    $ama = $eachRecord['ama'];
                    $current_level = $eachRecord['current_level'];
                    
                //    $UpdateValueArray = array('dy_ama'=>"$dy_ama",'ho_mo_smo'=>"$ho_mo_smo",'jt_ama'=>"$jt_ama",'ama'=>"$ama",'current_level'=>"$current_level");
                    
                    if($this->is_base64_encoded($dy_ama)==false && $this->is_email_valid($dy_ama)==true && !empty($dy_ama)){
                        $dy_ama = base64_encode($dy_ama);
                        $this->$hoAllocationTable->updateAll(array('dy_ama'=>"$dy_ama"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($ho_mo_smo)==false && $this->is_email_valid($ho_mo_smo)==true && !empty($ho_mo_smo)){
                        $ho_mo_smo = base64_encode($ho_mo_smo);
                        $this->$hoAllocationTable->updateAll(array('ho_mo_smo'=>"$ho_mo_smo"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($jt_ama)==false && $this->is_email_valid($jt_ama)==true && !empty($jt_ama)){
                        $jt_ama = base64_encode($jt_ama);
                        $this->$hoAllocationTable->updateAll(array('jt_ama'=>"$jt_ama"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($ama)==false && $this->is_email_valid($ama)==true && !empty($ama)){
                        $ama = base64_encode($ama);
                        $this->$hoAllocationTable->updateAll(array('ama'=>"$ama"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($current_level)==false && $this->is_email_valid($current_level)==true && !empty($current_level)){
                        $current_level = base64_encode($current_level);
                        $this->$hoAllocationTable->updateAll(array('current_level'=>"$current_level"),array('id'=>$record_id));
                    }
            }

            //for HO comments table
            $this->loadModel($hoCommentsTable);
            $getRecords = $this->$hoCommentsTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $comment_by = $eachRecord['comment_by'];
                    $comment_to = $eachRecord['comment_to'];
                    
                //    $UpdateValueArray = array('comment_by'=>"$comment_by",'comment_to'=>"$comment_to");

                    if($this->is_base64_encoded($comment_by)==false && $this->is_email_valid($comment_by)==true && !empty($comment_by)){
                        $comment_by = base64_encode($comment_by);
                        $this->$hoCommentsTable->updateAll(array('comment_by'=>"$comment_by"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($comment_to)==false && $this->is_email_valid($comment_to)==true && !empty($comment_to)){
                        $comment_to = base64_encode($comment_to);
                        $this->$hoCommentsTable->updateAll(array('comment_to'=>"$comment_to"),array('id'=>$record_id));
                    }
            }

            //for level 4 RO approved table
            $this->loadModel($level4ROApproved);
            $getRecords = $this->$level4ROApproved->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $user_email_id = $eachRecord['user_email_id'];
                    
                    

                    if($this->is_base64_encoded($user_email_id)==false && $this->is_email_valid($user_email_id)==true && !empty($user_email_id)){
                        $user_email_id = base64_encode($user_email_id);
                        $UpdateValueArray = array('user_email_id'=>"$user_email_id");
                        $this->$level4ROApproved->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
            }

            //for mo ro comments table
            $this->loadModel($moCommentsTable);
            $getRecords = $this->$moCommentsTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $comment_by = $eachRecord['comment_by'];
                    $comment_to = $eachRecord['comment_to'];
                    
                //    $UpdateValueArray = array('comment_by'=>"$comment_by",'comment_to'=>"$comment_to");
                    if($this->is_base64_encoded($comment_by)==false && $this->is_email_valid($comment_by)==true && !empty($comment_by)){
                        $comment_by = base64_encode($comment_by);
                        $this->$moCommentsTable->updateAll(array('comment_by'=>"$comment_by"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($comment_to)==false && $this->is_email_valid($comment_to)==true && !empty($comment_to)){
                        $comment_to = base64_encode($comment_to);
                        $this->$moCommentsTable->updateAll(array('comment_to'=>"$comment_to"),array('id'=>$record_id));
                    }
            }

            //for ro so comments table
            $this->loadModel($rosocommentTable);
            $getRecords = $this->$rosocommentTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $comment_by = $eachRecord['comment_by'];
                    $comment_to = $eachRecord['comment_to'];
                    
                //    $UpdateValueArray = array('comment_by'=>"$comment_by",'comment_to'=>"$comment_to");
                if($this->is_base64_encoded($comment_by)==false && $this->is_email_valid($comment_by)==true && !empty($comment_by)){
                    $comment_by = base64_encode($comment_by);
                    $this->$rosocommentTable->updateAll(array('comment_by'=>"$comment_by"),array('id'=>$record_id));
                }if($this->is_base64_encoded($comment_to)==false && $this->is_email_valid($comment_to)==true && !empty($comment_to)){
                    $comment_to = base64_encode($comment_to);
                    $this->$rosocommentTable->updateAll(array('comment_to'=>"$comment_to"),array('id'=>$record_id));
                }
            }

        }


        //for other random tables having user email id
        $modelArray = array('Dmi15DigitSiteinspectionReports','Dmi15DigitApplDetails','DmiAllConstituentOilsDetails','DmiAllDirectorsDetails',
                            'DmiAllTanksDetails','DmiApplicationCharges','DmiApplTransferLogs','DmiAuthFirmRegistrations',
                            'DmiAuthPrimaryRegistrations','DmiBusinessTypes','DmiCaBusinessYears','DmiCaDomesticRenewalDetails',
                        'DmiCaExportSiteinspectionReports','DmiChangeDirectorsDetails','DmiChangeFirms','DmiChangeLabChemistsDetails','DmiChangeLabFirmDetails',
                        'DmiChangeLabInspectionReports','DmiChangeLabOtherDetails','DmiChemistComments','DmiChemistEducationDetails','DmiChemistExperienceDetails',
                        'DmiChemistFinalReports','DmiChemistOtherDetails','DmiChemistProfileDetails','DmiChemistTrainingDetails','DmiCrushingRefiningPeriods','DmiCustomerFirmProfiles',
                        'DmiCustomerLaboratoryDetails','DmiCustomerMachineryProfiles','DmiCustomerPackingDetails','DmiCustomerPremisesProfiles','DmiCustomerTblDetails',
                        'DmiDatesExtensionsLogs','DmiDistricts','DmiDivisionGrades','DmiEducationTypes','DmiEsignRequestResponseLogs','DmiECodeApplDetails','DmiECodeSiteinspectionReports',
                        'DmiGrantProvCertificateLogs','DmiIoAllocationLogs','DmiLaboratoryChemistsDetails','DmiLaboratoryFirmDetails','DmiLaboratoryOtherDetails',
                        'DmiLaboratoryRenewalOtherDetails','DmiLaboratorySiteinspectionReports','DmiLaboratoryTypes','DmiLoginStatuses','DmiMachineTypes','DmiMenus','DmiMoAllocationLogs',
                        'DmiOldCertDateUpdateLogs','DmiPackingTypes','DmiPages','DmiPaoDetails','DmiPrintingBusinessYears','DmiPrintingFirmProfiles','DmiPrintingPremisesProfiles',
                        'DmiPrintingRenewalDetails','DmiPrintingSiteinspectionReports','DmiPrintingUnitDetails','DmiRejectedApplLogs','DmiRenewalSiteinspectionReports','DmiReplicaChargesDetails',
                        'DmiReplicaChargesDetailsLogs','DmiReEsignGrantLogs','DmiRoAllocationLogs','DmiRoOffices','DmiSentEmailLogs','DmiSiteinspectionLaboratoryDetails',
                        'DmiSiteinspectionOtherDetails','DmiSiteinspectionPremisesDetails','DmiSiteinspectionPremisesProfiles','DmiSmsEmailTemplates','DmiStates','DmiTankShapes',
                        'DmiTempEsignStatuses','DmiUsers','DmiUsersResetpassKeys','DmiUserActionLogs','DmiUserFileUploads','DmiUserHistoryLogs','DmiUserLogs','DmiUserRoles',
                        'DmiUserRolesManagmentLogs','DmiWorkTransferHoPermissions','DmiWorkTransferLogs','LimsSampleCharges');
                            
        foreach($modelArray as $eachModel){
            
            $this->loadModel($eachModel);
            $getRecords = $this->$eachModel->find('all',array('order'=>'id ASC'))->toArray();
            
            if($eachModel=='Dmi15DigitApplDetails' || $eachModel=='DmiAllConstituentOilsDetails' || $eachModel=='DmiAllDirectorsDetails' || $eachModel=='DmiAllTanksDetails'
                || $eachModel=='DmiApplicationCharges' || $eachModel=='DmiAuthFirmRegistrations' || $eachModel=='DmiAuthPrimaryRegistrations' || $eachModel=='DmiBusinessTypes'
                || $eachModel=='DmiCaBusinessYears' || $eachModel=='DmiCaDomesticRenewalDetails' || $eachModel=='DmiChangeDirectorsDetails' || $eachModel=='DmiChangeLabChemistsDetails'
                || $eachModel=='DmiChangeLabFirmDetails' || $eachModel=='DmiChangeLabOtherDetails' || $eachModel=='DmiChemistEducationDetails' || $eachModel=='DmiChemistExperienceDetails'
                || $eachModel=='DmiChemistOtherDetails' || $eachModel=='DmiChemistTrainingDetails' || $eachModel=='DmiCrushingRefiningPeriods' || $eachModel=='DmiCustomerLaboratoryDetails'
                || $eachModel=='DmiCustomerMachineryProfiles' || $eachModel=='DmiCustomerPackingDetails' || $eachModel=='DmiCustomerPremisesProfiles' || $eachModel=='DmiCustomerTblDetails'
                || $eachModel=='DmiDistricts' || $eachModel=='DmiECodeApplDetails' || $eachModel=='DmiGrantProvCertificateLogs' || $eachModel=='DmiLaboratoryChemistsDetails'
                || $eachModel=='DmiLaboratoryFirmDetails' || $eachModel=='DmiLaboratoryOtherDetails' || $eachModel=='DmiLaboratoryRenewalOtherDetails' || $eachModel=='DmiLaboratoryTypes'
                || $eachModel=='DmiMachineTypes' || $eachModel=='DmiMenus' || $eachModel=='DmiPackingTypes' || $eachModel=='DmiPages' || $eachModel=='DmiPaoDetails' || $eachModel=='DmiPrintingBusinessYears'
                || $eachModel=='DmiPrintingPremisesProfiles' || $eachModel=='DmiPrintingRenewalDetails' || $eachModel=='DmiPrintingUnitDetails' || $eachModel=='DmiSmsEmailTemplates'
                || $eachModel=='DmiStates' || $eachModel=='DmiTankShapes' || $eachModel=='DmiUserFileUploads' || $eachModel=='DmiUserRoles' || $eachModel=='LimsSampleCharges'){
                                    
                    foreach($getRecords as $eachRecord){
                    
                        $record_id = $eachRecord['id'];
                        $user_email_id = $eachRecord['user_email_id'];
                        
                        

                        if($this->is_base64_encoded($user_email_id)==false && $this->is_email_valid($user_email_id)==true && !empty($user_email_id)){
                            $user_email_id = base64_encode($user_email_id);
                            $UpdateValueArray = array('user_email_id'=>"$user_email_id");
                            $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                        }
                    }

            }elseif($eachModel=='Dmi15DigitSiteinspectionReports' || $eachModel=='DmiCaExportSiteinspectionReports' || $eachModel=='DmiChangeLabInspectionReports' || $eachModel=='DmiChemistFinalReports'
                || $eachModel=='DmiECodeSiteinspectionReports' || $eachModel=='DmiLaboratorySiteinspectionReports' || $eachModel=='DmiPrintingSiteinspectionReports' || $eachModel=='DmiRenewalSiteinspectionReports'
                || $eachModel=='DmiSiteinspectionLaboratoryDetails' || $eachModel=='DmiSiteinspectionOtherDetails' || $eachModel=='DmiSiteinspectionPremisesDetails' || $eachModel=='DmiSiteinspectionPremisesProfiles'){

                    foreach($getRecords as $eachRecord){
                
                        $record_id = $eachRecord['id'];
                        $user_email_id = $eachRecord['user_email_id'];
                        $referred_back_by_email = $eachRecord['referred_back_by_email'];
                        
                    //    $UpdateValueArray = array('user_email_id'=>"$user_email_id",'referred_back_by_email'=>"$referred_back_by_email");
                        
                        if($this->is_base64_encoded($user_email_id)==false && $this->is_email_valid($user_email_id)==true && !empty($user_email_id)){
                            $user_email_id = base64_encode($user_email_id);
                            $this->$eachModel->updateAll(array('user_email_id'=>"$user_email_id"),array('id'=>$record_id));
                        }if($this->is_base64_encoded($referred_back_by_email)==false && $this->is_email_valid($referred_back_by_email)==true && !empty($referred_back_by_email)){
                            $referred_back_by_email = base64_encode($referred_back_by_email);
                            $this->$eachModel->updateAll(array('referred_back_by_email'=>"$referred_back_by_email"),array('id'=>$record_id));
                        }
                    }
            
            }elseif($eachModel=='DmiApplTransferLogs' || $eachModel=='DmiWorkTransferLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $by_user = $eachRecord['by_user'];
                    $from_user = $eachRecord['from_user'];
                    $to_user = $eachRecord['to_user'];
                    
                //    $UpdateValueArray = array('by_user'=>"$by_user",'from_user'=>"$from_user",'to_user'=>"$to_user");

                    if($this->is_base64_encoded($by_user)==false && $this->is_email_valid($by_user)==true && !empty($by_user)){
                        $by_user = base64_encode($by_user);
                        $this->$eachModel->updateAll(array('by_user'=>"$by_user"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($from_user)==false && $this->is_email_valid($from_user)==true && !empty($from_user)){
                        $from_user = base64_encode($from_user);
                        $this->$eachModel->updateAll(array('from_user'=>"$from_user"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($to_user)==false && $this->is_email_valid($to_user)==true && !empty($to_user)){
                        $to_user = base64_encode($to_user);
                        $this->$eachModel->updateAll(array('to_user'=>"$to_user"),array('id'=>$record_id));
                    }
                }

            }elseif($eachModel=='DmiChangeFirms' || $eachModel=='DmiChemistProfileDetails'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $email = $eachRecord['email'];
                    
                   

                    if($this->is_base64_encoded($email)==false && $this->is_email_valid($email)==true && !empty($email)){
                        $email = base64_encode($email);
                        $UpdateValueArray = array('email'=>"$email");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            
            }elseif($eachModel=='DmiCustomerFirmProfiles' || $eachModel=='DmiPrintingFirmProfiles'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $user_email_id = $eachRecord['user_email_id'];
                    $firm_email_id = $eachRecord['firm_email_id'];
                    
                //    $UpdateValueArray = array('user_email_id'=>"$user_email_id",'firm_email_id'=>"$firm_email_id");

                    if($this->is_base64_encoded($user_email_id)==false && $this->is_email_valid($user_email_id)==true && !empty($user_email_id)){
                        $user_email_id = base64_encode($user_email_id);
                        $this->$eachModel->updateAll(array('user_email_id'=>"$user_email_id"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($firm_email_id)==false && $this->is_email_valid($firm_email_id)==true && !empty($firm_email_id)){
                        $firm_email_id = base64_encode($firm_email_id);
                        $this->$eachModel->updateAll(array('firm_email_id'=>"$firm_email_id"),array('id'=>$record_id));
                    }
                }
            
            }elseif($eachModel=='DmiDatesExtensionsLogs' || $eachModel=='DmiDivisionGrades' || $eachModel=='DmiEducationTypes' || $eachModel=='DmiRejectedApplLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $by_user = $eachRecord['by_user'];
                    
                    

                    if($this->is_base64_encoded($by_user)==false && $this->is_email_valid($by_user)==true && !empty($by_user)){
                        $by_user = base64_encode($by_user);
                        $UpdateValueArray = array('by_user'=>"$by_user");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            
            }elseif($eachModel=='DmiLoginStatuses' || $eachModel=='DmiUsersResetpassKeys' || $eachModel=='DmiUserActionLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $user_id = $eachRecord['user_id'];
                    
                    if($this->is_base64_encoded($user_id)==false && $this->is_email_valid($user_id)==true && !empty($user_id)){ 
                        $user_id = base64_encode($user_id);
                        $UpdateValueArray = array('user_id'=>"$user_id");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            
            }elseif($eachModel=='DmiReplicaChargesDetails' || $eachModel=='DmiReplicaChargesDetailsLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $user = $eachRecord['user'];
                    
                    if($this->is_base64_encoded($user)==false && $this->is_email_valid($user)==true && !empty($user)){ 
                        $user = base64_encode($user);
                        $UpdateValueArray = array('user'=>"$user");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            
            }elseif($eachModel=='DmiUsers' || $eachModel=='DmiUserHistoryLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $created_by_user = $eachRecord['created_by_user'];
                    
                    

                    if($this->is_base64_encoded($created_by_user)==false && $this->is_email_valid($created_by_user)==true && !empty($created_by_user)){
                        $created_by_user = base64_encode($created_by_user); 
                        $UpdateValueArray = array('created_by_user'=>"$created_by_user");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            
            }elseif($eachModel=='DmiChemistComments'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $comment_by = $eachRecord['comment_by'];
                    $comment_to = $eachRecord['comment_to'];
                    $reply_by = $eachRecord['reply_by'];
                    $reply_to = $eachRecord['reply_to'];
                    
                //    $UpdateValueArray = array('comment_by'=>"$comment_by",'comment_to'=>"$comment_to",'reply_by'=>"$reply_by",'reply_to'=>"$reply_to");
                    
                    if($this->is_base64_encoded($comment_by)==false && $this->is_email_valid($comment_by)==true && !empty($comment_by)){
                        $comment_by = base64_encode($comment_by);
                        $this->$eachModel->updateAll(array('comment_by'=>"$comment_by"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($comment_to)==false && $this->is_email_valid($comment_to)==true && !empty($comment_to)){
                        $comment_to = base64_encode($comment_to);
                        $this->$eachModel->updateAll(array('comment_to'=>"$comment_to"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($reply_by)==false && $this->is_email_valid($reply_by)==true && !empty($reply_by)){
                        $reply_by = base64_encode($reply_by);
                        $this->$eachModel->updateAll(array('reply_by'=>"$reply_by"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($reply_to)==false && $this->is_email_valid($reply_to)==true && !empty($reply_to)){
                        $reply_to = base64_encode($reply_to);
                        $this->$eachModel->updateAll(array('reply_to'=>"$reply_to"),array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiEsignRequestResponseLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $request_by_user_id = $eachRecord['request_by_user_id'];
                    
                    if($this->is_base64_encoded($request_by_user_id)==false && $this->is_email_valid($request_by_user_id)==true && !empty($request_by_user_id)){ 
                        $request_by_user_id = base64_encode($request_by_user_id);
                        $UpdateValueArray = array('request_by_user_id'=>"$request_by_user_id");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiIoAllocationLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $io_email_id = $eachRecord['io_email_id'];
                    
                    

                    if($this->is_base64_encoded($io_email_id)==false && $this->is_email_valid($io_email_id)==true && !empty($io_email_id)){
                        $io_email_id = base64_encode($io_email_id);
                        $UpdateValueArray = array('io_email_id'=>"$io_email_id");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiMoAllocationLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $mo_email_id = $eachRecord['mo_email_id'];
                    
                    

                    if($this->is_base64_encoded($mo_email_id)==false && $this->is_email_valid($mo_email_id)==true && !empty($mo_email_id)){
                        $mo_email_id = base64_encode($mo_email_id);
                        $UpdateValueArray = array('mo_email_id'=>"$mo_email_id");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiOldCertDateUpdateLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $updated_by = $eachRecord['updated_by'];
                    
                    

                    if($this->is_base64_encoded($updated_by)==false && $this->is_email_valid($updated_by)==true && !empty($updated_by)){
                        $updated_by = base64_encode($updated_by);
                        $UpdateValueArray = array('updated_by'=>"$updated_by");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiReEsignGrantLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $re_esigned_by = $eachRecord['re_esigned_by'];
                    
                    

                    if($this->is_base64_encoded($re_esigned_by)==false && $this->is_email_valid($re_esigned_by)==true && !empty($re_esigned_by)){
                        $re_esigned_by = base64_encode($re_esigned_by);
                        $UpdateValueArray = array('re_esigned_by'=>"$re_esigned_by");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiRoAllocationLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $ro_incharge_id = $eachRecord['ro_incharge_id'];
                    
                    

                    if($this->is_base64_encoded($ro_incharge_id)==false && $this->is_email_valid($ro_incharge_id)==true && !empty($ro_incharge_id)){
                        $ro_incharge_id = base64_encode($ro_incharge_id);
                        $UpdateValueArray = array('ro_incharge_id'=>"$ro_incharge_id");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiRoOffices'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $ro_email_id = $eachRecord['ro_email_id'];
                    $user_email_id = $eachRecord['user_email_id'];
                    
                //    $UpdateValueArray = array('ro_email_id'=>"$ro_email_id",'user_email_id'=>"$user_email_id");

                    if($this->is_base64_encoded($ro_email_id)==false && $this->is_email_valid($ro_email_id)==true && !empty($ro_email_id)){
                        $ro_email_id = base64_encode($ro_email_id);
                        $this->$eachModel->updateAll(array('ro_email_id'=>"$ro_email_id"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($user_email_id)==false && $this->is_email_valid($user_email_id)==true && !empty($user_email_id)){
                        $user_email_id = base64_encode($user_email_id);
                        $this->$eachModel->updateAll(array('user_email_id'=>"$user_email_id"),array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiSentEmailLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $splitEmails = explode(',',$eachRecord['destination_list']);
                    
                    $destination_list = '';
                    foreach($splitEmails as $eachEmail){
                        if($this->is_base64_encoded($eachEmail)==false && $this->is_email_valid($eachEmail)==true){
                            $eachEmail = base64_encode($eachEmail);
                            $destination_list .= $eachEmail.',';
                        }
                    }

                    $UpdateValueArray = array('destination_list'=>"$destination_list");

                    if(!empty($destination_list)){                       
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                    
                }
            }elseif($eachModel=='DmiTempEsignStatuses'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $esigning_user = $eachRecord['esigning_user'];
                    
                    if($this->is_base64_encoded($esigning_user)==false && $this->is_email_valid($esigning_user)==true && !empty($esigning_user)){
                        $esigning_user = base64_encode($esigning_user);
                        $UpdateValueArray = array('esigning_user'=>"$esigning_user");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiUserLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $email_id = $eachRecord['email_id'];
                    
																	   
                    

                    if($this->is_base64_encoded($email_id)==false && $this->is_email_valid($email_id)==true && !empty($email_id)){
                        $email_id = base64_encode($email_id);
						$UpdateValueArray = array('email_id'=>"$email_id");
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiUserRolesManagmentLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $by_user = $eachRecord['by_user'];
                    $to_user = $eachRecord['to_user'];
                    
                //    $UpdateValueArray = array('by_user'=>"$by_user",'to_user'=>"$to_user");

                    if($this->is_base64_encoded($by_user)==false && $this->is_email_valid($by_user)==true && !empty($by_user)){
                        $by_user = base64_encode($by_user);
                        $this->$eachModel->updateAll(array('by_user'=>"$by_user"),array('id'=>$record_id));
                    }if($this->is_base64_encoded($to_user)==false && $this->is_email_valid($to_user)==true && !empty($to_user)){
                        $to_user = base64_encode($to_user);
                        $this->$eachModel->updateAll(array('to_user'=>"$to_user"),array('id'=>$record_id));
                    }
                }
            }elseif($eachModel=='DmiWorkTransferHoPermissions'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $req_by_user = $eachRecord['req_by_user'];
                    $req_for_user = $eachRecord['req_for_user'];
                    
                //    $UpdateValueArray = array('req_by_user'=>"$req_by_user",'req_for_user'=>"$req_for_user");

                    if($this->is_base64_encoded($req_by_user)==false && $this->is_email_valid($req_by_user)==true && !empty($req_by_user)){
                        $req_by_user = base64_encode($req_by_user);
                        $this->$eachModel->updateAll(array('req_by_user'=>"$req_by_user"),array('id'=>$record_id));
                    
                    }if($this->is_base64_encoded($req_for_user)==false && $this->is_email_valid($req_for_user)==true && !empty($req_for_user)){
                        $req_for_user = base64_encode($req_for_user);
                        $this->$eachModel->updateAll(array('req_for_user'=>"$req_for_user"),array('id'=>$record_id));
                    }
                }
            }
        }

    }*/
	
	/*public function tempForLabRecords(){
		
		$this->loadModel('DmiCustomerLaboratoryDetails');
		$getRecords = $this->DmiCustomerLaboratoryDetails->tempConn();
		
		
	} */
	
	//function to manage the double encoded values from any table
	/*public function checkIfDoubleEncoded($tableName=null){
		
		$tableName = 'DmiCustomerLaboratoryDetails';
		$this->autoRender=false;
		$this->loadModel($tableName);
		$getRecords = $this->$tableName->find('all',array('order'=>'id ASC'))->toArray();

		foreach($getRecords as $eachRecord){
                
			if (!empty($eachRecord) && !empty($eachRecord['user_email_id'])) {
				
				//$lab_email_id = $eachRecord['lab_email_id'];
				$user_email_id = base64_decode($eachRecord['user_email_id']);
				$record_id = $eachRecord['id'];
				
				//check if getting proper email value after decoding, else rotate till last value is proper email
				if (str_contains($user_email_id, '@')== false) {
					
					while (str_contains($user_email_id, '@')== false) {
					
						$lastWithEncoded = $user_email_id;//take last value before decoding to email id.
						$user_email_id = base64_decode($user_email_id);
					}

					//$this->$tableName->updateAll(array('user_email_id'=>"$lastWithEncoded"),array('id'=>$record_id));
					
				}
			}	
		}
	}*/
    //function to encode the email id for some standard tables
 /*   public function encodeAllEmailIds(){			
			
        $this->autoRender=false;

        $this->loadModel('DmiUsers');
        $this->loadModel('DmiFirms');

        $modelArray = array('DmiCustomers','DmiCustomersHistoryLogs','DmiFirms','DmiFirmHistoryLogs','DmiUsers','DmiUserHistoryLogs','DmiChemistRegistrations','DmiFeedbacks','DmiCustomerLaboratoryDetails');

        foreach($modelArray as $eachModel){
            
            $this->loadModel($eachModel);
            $getRecords = $this->$eachModel->find('all',array('order'=>'id ASC'))->toArray();

            if($eachModel=='DmiCustomerLaboratoryDetails'){
                                    
                foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $lab_email_id = $eachRecord['lab_email_id'];
					$user_email_id = $eachRecord['user_email_id'];

					if($this->is_base64_encoded($lab_email_id)==false && $this->is_email_valid($lab_email_id)==true && !empty($lab_email_id)){
                        $lab_email_id = base64_encode($lab_email_id);
                        $this->$eachModel->updateAll(array('lab_email_id'=>"$lab_email_id"),array('id'=>$record_id));
                    }
					
					if($this->is_base64_encoded($user_email_id)==false && $this->is_email_valid($user_email_id)==true && !empty($user_email_id)){
                        $user_email_id = base64_encode($user_email_id);
                        $this->$eachModel->updateAll(array('user_email_id'=>"$user_email_id"),array('id'=>$record_id));
                    }

                }

            }else{

                foreach($getRecords as $eachRecord){
                    
                    $record_id = $eachRecord['id'];
                    $email = $eachRecord['email'];
					
					if($this->is_base64_encoded($email)==false && $this->is_email_valid($email)==true && !empty($email)){
                        $email = base64_encode($email);
                        $this->$eachModel->updateAll(array('email'=>"$email"),array('id'=>$record_id));
                    }

                }

            }
        }
    }*/

//below functions are not in used and created for temp use to decode the encoded email id if required.
/*    public function abcx(){

        $testArr = array('139'=>'buddhi.yadav@gov.in','140'=>'buddhi.yadav@gov.in','141'=>'jawahar.malawath@gov.in','142'=>'skkoul.dmi@gov.in','143'=>'jawahar.malawath@gov.in','144'=>'old_application','145'=>'old_application','146'=>'dinesh.kumar70@gov.in','147'=>'old_application','148'=>'old_application','149'=>'bc.mouli@nic.in','150'=>'old_application','151'=>'bc.mouli@nic.in','152'=>'dm.govindareddy@gov.in','153'=>'old_application','154'=>'k.anupam@gov.in','155'=>'bc.mouli@nic.in','156'=>'dinesh.kumar70@gov.in','157'=>'old_application','158'=>'santosh.rangrao@gov.in','159'=>'old_application','160'=>'dinesh.kumar70@gov.in','161'=>'laxmi.gotru@gov.in','162'=>'old_application','163'=>'old_application','164'=>'old_application','165'=>'ak.upadhyay71@gov.in','166'=>'old_application','167'=>'editordmi-agri@nic.in','168'=>'old_application','169'=>'old_application','170'=>'old_application','171'=>'old_application','172'=>'old_application','173'=>'old_application','174'=>'old_application','175'=>'old_application','176'=>'old_application','177'=>'old_application','178'=>'dinesh.kumar70@gov.in','179'=>'old_application','180'=>'old_application','181'=>'old_application','182'=>'old_application','183'=>'old_application','184'=>'old_application','185'=>'old_application','186'=>'old_application','187'=>'old_application','188'=>'old_application','189'=>'laxmi.gotru@gov.in','190'=>'old_application','191'=>'old_application','192'=>'pb.babbanwar@gov.in','193'=>'old_application','194'=>'old_application','195'=>'bc.mouli@nic.in','196'=>'old_application','197'=>'old_application','198'=>'old_application','199'=>'old_application','200'=>'old_application','201'=>'old_application','202'=>'old_application','203'=>'old_application','204'=>'dinesh.kumar70@gov.in','205'=>'old_application','206'=>'old_application','207'=>'old_application','208'=>'old_application','209'=>'old_application','210'=>'old_application','211'=>'old_application','212'=>'old_application','213'=>'old_application','214'=>'old_application','215'=>'old_application','216'=>'old_application','217'=>'old_application','218'=>'old_application','219'=>'old_application','220'=>'old_application','221'=>'old_application','222'=>'old_application','223'=>'old_application','224'=>'old_application','225'=>'old_application','226'=>'old_application','227'=>'old_application','228'=>'old_application','229'=>'old_application','230'=>'old_application','231'=>'old_application','232'=>'old_application','233'=>'old_application','234'=>'old_application','235'=>'old_application','236'=>'old_application','237'=>'old_application','238'=>'dm.govindareddy@gov.in','239'=>'old_application','240'=>'old_application','241'=>'old_application','242'=>'jawahar.malawath@gov.in','243'=>'old_application','244'=>'old_application','245'=>'old_application','246'=>'old_application','247'=>'dm.govindareddy@gov.in','248'=>'old_application','249'=>'old_application','250'=>'old_application','251'=>'old_application','252'=>'old_application','253'=>'old_application','254'=>'old_application','255'=>'old_application','256'=>'old_application','257'=>'old_application','258'=>'old_application','259'=>'old_application','260'=>'old_application','261'=>'old_application','262'=>'manojkumar.92@gov.in','263'=>'old_application','264'=>'old_application','265'=>'old_application','266'=>'old_application','267'=>'old_application','268'=>'old_application','269'=>'dm.govindareddy@gov.in','270'=>'dinesh.kumar70@gov.in','271'=>'dinesh.kumar70@gov.in','272'=>'old_application','273'=>'dinesh.kumar70@gov.in','274'=>'old_application','275'=>'old_application','276'=>'old_application','277'=>'old_application','278'=>'dinesh.kumar70@gov.in','279'=>'old_application','280'=>'dinesh.kumar70@gov.in','281'=>'old_application','282'=>'old_application','283'=>'melvinroy.p@gov.in','284'=>'laxmi.gotru@gov.in','285'=>'laxmi.gotru@gov.in','286'=>'old_application','287'=>'old_application','288'=>'dm.govindareddy@gov.in','289'=>'dm.govindareddy@gov.in','290'=>'old_application','291'=>'bc.mouli@nic.in','292'=>'melvinroy.p@gov.in','293'=>'old_application','294'=>'old_application','295'=>'old_application','296'=>'old_application','297'=>'bc.mouli@nic.in','298'=>'bc.mouli@nic.in','299'=>'bc.mouli@nic.in','300'=>'santosh.rangrao@gov.in','301'=>'bc.mouli@nic.in','302'=>'bc.mouli@nic.in','303'=>'bc.mouli@nic.in','304'=>'bc.mouli@nic.in','305'=>'bc.mouli@nic.in','306'=>'bc.mouli@nic.in','307'=>'bc.mouli@nic.in','308'=>'bc.mouli@nic.in','309'=>'bc.mouli@nic.in','310'=>'bc.mouli@nic.in','311'=>'bc.mouli@nic.in','312'=>'bc.mouli@nic.in','313'=>'bc.mouli@nic.in','314'=>'jawahar.malawath@gov.in','315'=>'jawahar.malawath@gov.in','316'=>'old_application','317'=>'singh.np29@gov.in','318'=>'dm.govindareddy@gov.in','319'=>'old_application','320'=>'bc.mouli@nic.in','321'=>'bc.mouli@nic.in','322'=>'editordmi-agri@nic.in','323'=>'old_application','324'=>'old_application','325'=>'old_application','326'=>'old_application','327'=>'old_application','328'=>'old_application','329'=>'old_application','330'=>'old_application','331'=>'old_application','332'=>'old_application','333'=>'old_application','334'=>'old_application','335'=>'old_application','336'=>'old_application','337'=>'old_application','338'=>'melvinroy.p@gov.in','339'=>'mehra.sanjay@gov.in','340'=>'old_application','341'=>'old_application','342'=>'mehra.sanjay@gov.in','343'=>'old_application','344'=>'old_application','345'=>'old_application','346'=>'dm.govindareddy@gov.in','347'=>'dm.govindareddy@gov.in','348'=>'old_application','349'=>'old_application','350'=>'melvinroy.p@gov.in','351'=>'dinesh.kumar70@gov.in','352'=>'editordmi-agri@nic.in','353'=>'old_application','354'=>'old_application','355'=>'old_application','356'=>'old_application','357'=>'old_application','358'=>'old_application','359'=>'old_application','360'=>'old_application','361'=>'old_application','362'=>'old_application','363'=>'old_application','364'=>'old_application','365'=>'old_application','366'=>'old_application','367'=>'old_application','368'=>'old_application','369'=>'old_application','370'=>'old_application','371'=>'old_application','372'=>'old_application','373'=>'old_application','374'=>'old_application','375'=>'old_application','376'=>'old_application','377'=>'old_application','378'=>'old_application','379'=>'melvinroy.p@gov.in','380'=>'old_application','381'=>'melvinroy.p@gov.in','382'=>'ak.singh59@nic.in','383'=>'melvinroy.p@gov.in','384'=>'ak.upadhyay71@gov.in','385'=>'old_application','386'=>'ak.upadhyay71@gov.in','387'=>'old_application','388'=>'editordmi-agri@nic.in','389'=>'old_application','390'=>'old_application','391'=>'old_application','392'=>'old_application','393'=>'old_application','394'=>'old_application','395'=>'melvinroy.p@gov.in','396'=>'melvinroy.p@gov.in','397'=>'melvinroy.p@gov.in','398'=>'melvinroy.p@gov.in','399'=>'dinesh.kumar70@gov.in','400'=>'old_application','401'=>'old_application','402'=>'melvinroy.p@gov.in','403'=>'melvinroy.p@gov.in','404'=>'melvinroy.p@gov.in','405'=>'old_application','406'=>'old_application','407'=>'old_application','408'=>'old_application','409'=>'old_application','410'=>'melvinroy.p@gov.in','411'=>'melvinroy.p@gov.in','412'=>'melvinroy.p@gov.in','413'=>'old_application','414'=>'old_application','415'=>'melvinroy.p@gov.in','416'=>'old_application','417'=>'old_application','418'=>'old_application','419'=>'old_application','420'=>'old_application','421'=>'old_application','422'=>'old_application','423'=>'melvinroy.p@gov.in','424'=>'melvinroy.p@gov.in','425'=>'melvinroy.p@gov.in','426'=>'melvinroy.p@gov.in','427'=>'bc.mouli@nic.in','428'=>'melvinroy.p@gov.in','429'=>'melvinroy.p@gov.in','430'=>'old_application','431'=>'old_application','432'=>'old_application','433'=>'melvinroy.p@gov.in','434'=>'old_application','435'=>'melvinroy.p@gov.in','436'=>'old_application','437'=>'old_application','438'=>'melvinroy.p@gov.in','439'=>'melvinroy.p@gov.in','440'=>'melvinroy.p@gov.in','441'=>'old_application','442'=>'old_application','443'=>'old_application','444'=>'old_application','445'=>'old_application','446'=>'old_application','447'=>'old_application','448'=>'old_application','449'=>'old_application','450'=>'old_application','451'=>'old_application','452'=>'old_application','453'=>'old_application','454'=>'old_application','455'=>'ak.upadhyay71@gov.in','456'=>'old_application','457'=>'melvinroy.p@gov.in','458'=>'manojkumar.92@gov.in','459'=>'melvinroy.p@gov.in','460'=>'old_application','461'=>'old_application','462'=>'old_application','463'=>'old_application','464'=>'old_application','465'=>'bc.mouli@nic.in','466'=>'melvinroy.p@gov.in','467'=>'old_application','468'=>'old_application','469'=>'old_application','470'=>'old_application','471'=>'old_application','472'=>'dinesh.kumar70@gov.in','473'=>'singh.np29@gov.in','474'=>'dinesh.kumar70@gov.in','475'=>'old_application','476'=>'old_application','477'=>'old_application','478'=>'dinesh.kumar70@gov.in','479'=>'dinesh.kumar70@gov.in','480'=>'dinesh.kumar70@gov.in','481'=>'dinesh.kumar70@gov.in','482'=>'dinesh.kumar70@gov.in','483'=>'bc.mouli@nic.in','484'=>'old_application','485'=>'old_application','486'=>'old_application','487'=>'old_application','488'=>'old_application','489'=>'old_application','490'=>'old_application','491'=>'laxmi.gotru@gov.in','492'=>'dinesh.kumar70@gov.in','493'=>'dinesh.kumar70@gov.in','494'=>'old_application','495'=>'melvinroy.p@gov.in','496'=>'melvinroy.p@gov.in','497'=>'melvinroy.p@gov.in','498'=>'melvinroy.p@gov.in','499'=>'dinesh.kumar70@gov.in','500'=>'dinesh.kumar70@gov.in','501'=>'dinesh.kumar70@gov.in','502'=>'dinesh.kumar70@gov.in','503'=>'melvinroy.p@gov.in','504'=>'dinesh.kumar70@gov.in','505'=>'old_application','506'=>'bc.mouli@nic.in','507'=>'jawahar.malawath@gov.in','508'=>'jawahar.malawath@gov.in','509'=>'old_application','510'=>'melvinroy.p@gov.in','511'=>'melvinroy.p@gov.in','512'=>'old_application','513'=>'dinesh.kumar70@gov.in','514'=>'dinesh.kumar70@gov.in','515'=>'dinesh.kumar70@gov.in',
        '516'=>'dinesh.kumar70@gov.in','517'=>'dinesh.kumar70@gov.in','518'=>'bc.mouli@nic.in','519'=>'bc.mouli@nic.in','520'=>'bc.mouli@nic.in','521'=>'dinesh.kumar70@gov.in','522'=>'melvinroy.p@gov.in','523'=>'buddhi.yadav@gov.in','524'=>'old_application','525'=>'melvinroy.p@gov.in','526'=>'old_application','527'=>'old_application','528'=>'dinesh.kumar70@gov.in','529'=>'dinesh.kumar70@gov.in','530'=>'santosh.rangrao@gov.in','531'=>'old_application','532'=>'old_application','533'=>'old_application','534'=>'old_application','535'=>'old_application','536'=>'old_application','537'=>'old_application','538'=>'old_application','539'=>'old_application','540'=>'old_application','541'=>'bc.mouli@nic.in','542'=>'bc.mouli@nic.in','543'=>'bc.mouli@nic.in','544'=>'old_application','545'=>'bc.mouli@nic.in','546'=>'old_application','547'=>'old_application','548'=>'santosh.rangrao@gov.in','549'=>'dinesh.kumar70@gov.in','550'=>'dinesh.kumar70@gov.in','551'=>'dinesh.kumar70@gov.in','552'=>'old_application','553'=>'old_application','554'=>'dinesh.kumar70@gov.in','555'=>'dinesh.kumar70@gov.in','556'=>'dinesh.kumar70@gov.in','557'=>'melvinroy.p@gov.in','558'=>'dinesh.kumar70@gov.in','559'=>'dinesh.kumar70@gov.in','560'=>'dinesh.kumar70@gov.in','561'=>'old_application','562'=>'old_application','563'=>'bc.mouli@nic.in','564'=>'bc.mouli@nic.in','565'=>'bc.mouli@nic.in','566'=>'bc.mouli@nic.in','567'=>'bc.mouli@nic.in','568'=>'old_application','569'=>'old_application','570'=>'old_application','571'=>'old_application','572'=>'old_application','573'=>'old_application','574'=>'old_application','575'=>'jawahar.malawath@gov.in','576'=>'old_application','577'=>'jawahar.malawath@gov.in','578'=>'old_application','579'=>'k.anupam@gov.in','580'=>'melvinroy.p@gov.in','581'=>'pb.babbanwar@gov.in','582'=>'old_application','583'=>'old_application','584'=>'old_application','585'=>'melvinroy.p@gov.in','586'=>'old_application','587'=>'old_application','588'=>'old_application','589'=>'old_application','590'=>'jawahar.malawath@gov.in','591'=>'old_application','592'=>'jawahar.malawath@gov.in','593'=>'melvinroy.p@gov.in','594'=>'melvinroy.p@gov.in','595'=>'old_application','596'=>'old_application','597'=>'old_application','598'=>'jawahar.malawath@gov.in','599'=>'old_application','600'=>'old_application','601'=>'old_application','602'=>'old_application','603'=>'old_application','604'=>'melvinroy.p@gov.in','605'=>'jawahar.malawath@gov.in','606'=>'old_application','607'=>'santosh.rangrao@gov.in','608'=>'old_application','609'=>'old_application','610'=>'old_application','611'=>'old_application','612'=>'old_application','613'=>'bc.mouli@nic.in','614'=>'bc.mouli@nic.in','615'=>'old_application','616'=>'old_application','617'=>'old_application','618'=>'melvinroy.p@gov.in','619'=>'melvinroy.p@gov.in','620'=>'jawahar.malawath@gov.in','621'=>'jawahar.malawath@gov.in','622'=>'old_application','623'=>'old_application','624'=>'old_application','625'=>'old_application','626'=>'old_application','627'=>'old_application','628'=>'dm.govindareddy@gov.in','629'=>'old_application','630'=>'skkoul.dmi@gov.in','631'=>'skkoul.dmi@gov.in','632'=>'skkoul.dmi@gov.in','633'=>'old_application','634'=>'old_application','635'=>'jawahar.malawath@gov.in','636'=>'jawahar.malawath@gov.in','637'=>'ak.upadhyay71@gov.in','638'=>'dinesh.kumar70@gov.in','639'=>'ak.upadhyay71@gov.in','640'=>'old_application','641'=>'jawahar.malawath@gov.in','642'=>'skkoul.dmi@gov.in','643'=>'skkoul.dmi@gov.in','644'=>'old_application','645'=>'old_application','646'=>'old_application','647'=>'pb.babbanwar@gov.in','648'=>'old_application','649'=>'old_application','650'=>'old_application','651'=>'old_application','652'=>'old_application','653'=>'old_application','654'=>'pb.babbanwar@gov.in','655'=>'laxmi.gotru@gov.in','656'=>'old_application','657'=>'bc.mouli@nic.in','658'=>'laxmi.gotru@gov.in','659'=>'bc.mouli@nic.in','660'=>'bc.mouli@nic.in','661'=>'bc.mouli@nic.in','662'=>'old_application','663'=>'old_application','664'=>'skkoul.dmi@gov.in','665'=>'laxmi.gotru@gov.in','666'=>'laxmi.gotru@gov.in','667'=>'old_application','668'=>'old_application','669'=>'old_application','670'=>'old_application','671'=>'old_application','672'=>'old_application','673'=>'melvinroy.p@gov.in','674'=>'bc.mouli@nic.in','675'=>'bc.mouli@nic.in','676'=>'bc.mouli@nic.in','677'=>'skkoul.dmi@gov.in','678'=>'old_application','679'=>'melvinroy.p@gov.in','680'=>'skkoul.dmi@gov.in','681'=>'old_application','682'=>'melvinroy.p@gov.in','683'=>'old_application','684'=>'old_application','685'=>'old_application','686'=>'melvinroy.p@gov.in','687'=>'old_application','688'=>'santosh.rangrao@gov.in','689'=>'melvinroy.p@gov.in','690'=>'santosh.rangrao@gov.in','691'=>'old_application','692'=>'melvinroy.p@gov.in','693'=>'old_application','694'=>'old_application','695'=>'old_application','696'=>'old_application','697'=>'old_application','698'=>'old_application','699'=>'old_application','700'=>'old_application','701'=>'melvinroy.p@gov.in','702'=>'bc.mouli@nic.in','703'=>'bc.mouli@nic.in','704'=>'old_application','705'=>'anilkumar.pillai@gov.in','706'=>'old_application','707'=>'old_application','708'=>'old_application','709'=>'old_application','710'=>'laxmi.gotru@gov.in','711'=>'laxmi.gotru@gov.in','712'=>'laxmi.gotru@gov.in','713'=>'laxmi.gotru@gov.in','714'=>'old_application','715'=>'old_application','716'=>'jawahar.malawath@gov.in','717'=>'laxmi.gotru@gov.in','718'=>'editordmi-agri@nic.in','719'=>'laxmi.gotru@gov.in','720'=>'laxmi.gotru@gov.in','721'=>'dinesh.kumar70@gov.in','722'=>'ak.singh59@nic.in','723'=>'ak.upadhyay71@gov.in','724'=>'melvinroy.p@gov.in','725'=>'laxmi.gotru@gov.in','726'=>'laxmi.gotru@gov.in','727'=>'ak.upadhyay71@gov.in','728'=>'old_application','729'=>'old_application','730'=>'old_application','731'=>'old_application','732'=>'laxmi.gotru@gov.in','733'=>'old_application','734'=>'laxmi.gotru@gov.in','735'=>'laxmi.gotru@gov.in','736'=>'laxmi.gotru@gov.in','737'=>'ak.singh59@nic.in','738'=>'old_application','739'=>'old_application','740'=>'melvinroy.p@gov.in','741'=>'laxmi.gotru@gov.in','742'=>'laxmi.gotru@gov.in','743'=>'laxmi.gotru@gov.in','744'=>'old_application','745'=>'laxmi.gotru@gov.in','746'=>'old_application','747'=>'old_application','748'=>'old_application','749'=>'dm.govindareddy@gov.in','750'=>'jaiveer.singh@gov.in','751'=>'laxmi.gotru@gov.in','752'=>'laxmi.gotru@gov.in','753'=>'laxmi.gotru@gov.in','754'=>'laxmi.gotru@gov.in','755'=>'laxmi.gotru@gov.in','756'=>'laxmi.gotru@gov.in','757'=>'melvinroy.p@gov.in','758'=>'laxmi.gotru@gov.in','759'=>'laxmi.gotru@gov.in','760'=>'laxmi.gotru@gov.in','761'=>'jawahar.malawath@gov.in','762'=>'ak.upadhyay71@gov.in','763'=>'dinesh.kumar70@gov.in','764'=>'old_application','765'=>'ak.upadhyay71@gov.in','766'=>'anilkumar.pillai@gov.in','767'=>'anilkumar.pillai@gov.in','768'=>'anilkumar.pillai@gov.in','769'=>'anilkumar.pillai@gov.in','770'=>'anilkumar.pillai@gov.in','771'=>'anilkumar.pillai@gov.in','772'=>'anilkumar.pillai@gov.in','773'=>'anilkumar.pillai@gov.in','774'=>'laxmi.gotru@gov.in','775'=>'laxmi.gotru@gov.in','776'=>'old_application','777'=>'old_application','778'=>'ak.upadhyay71@gov.in','779'=>'pb.babbanwar@gov.in','780'=>'anilkumar.pillai@gov.in','781'=>'laxmi.gotru@gov.in','782'=>'anilkumar.pillai@gov.in','783'=>'anilkumar.pillai@gov.in','784'=>'dm.govindareddy@gov.in','785'=>'anilkumar.pillai@gov.in','786'=>'old_application','787'=>'editordmi-agri@nic.in','788'=>'old_application','789'=>'old_application','790'=>'mehra.sanjay@gov.in','791'=>'mehra.sanjay@gov.in','792'=>'old_application','793'=>'old_application','794'=>'old_application','795'=>'old_application','796'=>'old_application','797'=>'old_application','798'=>'old_application','799'=>'old_application','800'=>'old_application','801'=>'old_application','802'=>'old_application','803'=>'old_application','804'=>'santosh.rangrao@gov.in','805'=>'buddhi.yadav@gov.in','806'=>'editordmi-agri@nic.in','807'=>'dinesh.kumar70@gov.in','808'=>'bc.mouli@nic.in','809'=>'bc.mouli@nic.in','810'=>'bc.mouli@nic.in','811'=>'bc.mouli@nic.in','812'=>'dinesh.kumar70@gov.in','813'=>'bc.mouli@nic.in','814'=>'bc.mouli@nic.in','815'=>'bc.mouli@nic.in','816'=>'bc.mouli@nic.in','817'=>'bc.mouli@nic.in','818'=>'bc.mouli@nic.in','819'=>'melvinroy.p@gov.in','820'=>'old_application','821'=>'brajesh.tiwari@gov.in','822'=>'brajesh.tiwari@gov.in','823'=>'old_application','824'=>'old_application','825'=>'melvinroy.p@gov.in','826'=>'melvinroy.p@gov.in','827'=>'old_application','828'=>'old_application','829'=>'singh.np29@gov.in','830'=>'old_application','831'=>'bc.mouli@nic.in','832'=>'bc.mouli@nic.in','833'=>'brajesh.tiwari@gov.in','834'=>'brajesh.tiwari@gov.in','835'=>'old_application','836'=>'old_application','837'=>'old_application','838'=>'old_application','839'=>'old_application','840'=>'bc.mouli@nic.in','841'=>'dinesh.kumar70@gov.in','842'=>'old_application','843'=>'old_application','844'=>'jaiveer.singh@gov.in','845'=>'jaiveer.singh@gov.in','846'=>'jaiveer.singh@gov.in','847'=>'old_application','848'=>'prasad.chakraborty@nic.in','849'=>'old_application','850'=>'old_application','851'=>'editordmi-agri@nic.in','852'=>'prasad.chakraborty@nic.in','853'=>'prasad.chakraborty@nic.in','854'=>'prasad.chakraborty@nic.in','855'=>'prasad.chakraborty@nic.in','856'=>'prasad.chakraborty@nic.in','857'=>'prasad.chakraborty@nic.in','858'=>'prasad.chakraborty@nic.in','859'=>'prasad.chakraborty@nic.in','860'=>'prasad.chakraborty@nic.in','861'=>'bc.mouli@nic.in','862'=>'old_application','863'=>'old_application','864'=>'old_application','865'=>'melvinroy.p@gov.in','866'=>'dinesh.kumar70@gov.in','867'=>'editordmi-agri@nic.in','868'=>'editordmi-agri@nic.in','869'=>'manojkumar.92@gov.in','870'=>'bc.mouli@nic.in','871'=>'bc.mouli@nic.in','872'=>'bc.mouli@nic.in','873'=>'old_application','874'=>'old_application','875'=>'old_application','876'=>'bc.mouli@nic.in','877'=>'mehra.sanjay@gov.in','878'=>'pankaj.girdhar62@gov.in','879'=>'satbirsingh.saghu@gov.in',
        '880'=>'brajesh.tiwari@gov.in','881'=>'dm.govindareddy@gov.in','882'=>'brajesh.tiwari@gov.in','883'=>'dinesh.kumar70@gov.in','884'=>'old_application','885'=>'brajesh.tiwari@gov.in','886'=>'brajesh.tiwari@gov.in','887'=>'pankaj.girdhar62@gov.in','888'=>'manojkumar.92@gov.in','889'=>'prasad.chakraborty@nic.in','890'=>'prasad.chakraborty@nic.in','891'=>'melvinroy.p@gov.in','892'=>'melvinroy.p@gov.in','893'=>'melvinroy.p@gov.in','894'=>'pankaj.girdhar62@gov.in','895'=>'bc.mouli@nic.in','896'=>'satbirsingh.saghu@gov.in','897'=>'melvinroy.p@gov.in','898'=>'anilkumar.pillai@gov.in','899'=>'anilkumar.pillai@gov.in','900'=>'melvinroy.p@gov.in','901'=>'melvinroy.p@gov.in','902'=>'melvinroy.p@gov.in','903'=>'anilkumar.pillai@gov.in','904'=>'dinesh.kumar70@gov.in','905'=>'pankaj.girdhar62@gov.in','906'=>'editordmi-agri@nic.in','907'=>'bc.mouli@nic.in','908'=>'melvinroy.p@gov.in','909'=>'melvinroy.p@gov.in','910'=>'melvinroy.p@gov.in','911'=>'melvinroy.p@gov.in','912'=>'melvinroy.p@gov.in','913'=>'manojkumar.92@gov.in','914'=>'shivnandan.65@gov.in','915'=>'bc.mouli@nic.in','916'=>'old_application','917'=>'sonali.bagade@gov.in','918'=>'pankaj.girdhar62@gov.in','919'=>'pankaj.girdhar62@gov.in','920'=>'brajesh.tiwari@gov.in','921'=>'melvinroy.p@gov.in','922'=>'melvinroy.p@gov.in','923'=>'bc.mouli@nic.in','924'=>'melvinroy.p@gov.in','925'=>'melvinroy.p@gov.in','926'=>'bc.mouli@nic.in','927'=>'dm.govindareddy@gov.in','928'=>'bc.mouli@nic.in','929'=>'bc.mouli@nic.in','930'=>'bc.mouli@nic.in','931'=>'melvinroy.p@gov.in','932'=>'bc.mouli@nic.in','933'=>'bc.mouli@nic.in','934'=>'bc.mouli@nic.in','935'=>'bc.mouli@nic.in','936'=>'melvinroy.p@gov.in','937'=>'melvinroy.p@gov.in','938'=>'prasad.chakraborty@nic.in','939'=>'melvinroy.p@gov.in','940'=>'prasad.chakraborty@nic.in','941'=>'prasad.chakraborty@nic.in','942'=>'melvinroy.p@gov.in','943'=>'editordmi-agri@nic.in','944'=>'prasad.chakraborty@nic.in','945'=>'santosh.rangrao@gov.in','946'=>'dm.govindareddy@gov.in','947'=>'anilkumar.pillai@gov.in','948'=>'melvinroy.p@gov.in','949'=>'old_application','950'=>'jawahar.malawath@gov.in','951'=>'old_application','952'=>'jawahar.malawath@gov.in','953'=>'old_application','954'=>'old_application','955'=>'old_application','956'=>'old_application','957'=>'old_application','958'=>'old_application','959'=>'melvinroy.p@gov.in','960'=>'melvinroy.p@gov.in','961'=>'shivnandan.65@gov.in','962'=>'editordmi-agri@nic.in','963'=>'old_application','964'=>'old_application','965'=>'prasad.chakraborty@nic.in','966'=>'old_application','967'=>'old_application','968'=>'old_application','969'=>'brajesh.tiwari@gov.in','970'=>'melvinroy.p@gov.in','971'=>'old_application','972'=>'manojkumar.92@gov.in','973'=>'old_application','974'=>'old_application','975'=>'old_application','976'=>'dinesh.kumar70@gov.in','977'=>'old_application','978'=>'melvinroy.p@gov.in','979'=>'old_application','980'=>'old_application','981'=>'raghavendra.murgod@gov.in','982'=>'melvinroy.p@gov.in','983'=>'melvinroy.p@gov.in','984'=>'old_application','985'=>'old_application','986'=>'old_application','987'=>'old_application','988'=>'old_application','989'=>'old_application','990'=>'old_application','991'=>'old_application','992'=>'old_application','993'=>'old_application','994'=>'old_application','995'=>'raghavendra.murgod@gov.in','996'=>'old_application','997'=>'raghavendra.murgod@gov.in','998'=>'old_application','999'=>'old_application','1000'=>'old_application','1001'=>'old_application','1002'=>'old_application','1003'=>'melvinroy.p@gov.in','1004'=>'brajesh.tiwari@gov.in','1005'=>'jawahar.malawath@gov.in','1006'=>'melvinroy.p@gov.in','1007'=>'melvinroy.p@gov.in','1008'=>'old_application','1009'=>'old_application','1010'=>'old_application','1011'=>'old_application','1012'=>'old_application','1013'=>'melvinroy.p@gov.in','1014'=>'old_application','1015'=>'gaurav.keshari@gov.in','1016'=>'old_application','1017'=>'old_application','1018'=>'old_application','1019'=>'old_application','1020'=>'old_application','1021'=>'old_application','1022'=>'old_application','1023'=>'old_application','1024'=>'old_application','1025'=>'old_application','1026'=>'old_application','1027'=>'old_application','1028'=>'prasad.chakraborty@nic.in','1029'=>'old_application','1030'=>'old_application','1031'=>'old_application','1032'=>'old_application','1033'=>'old_application','1034'=>'old_application','1035'=>'old_application','1036'=>'editordmi-agri@nic.in','1037'=>'old_application','1038'=>'editordmi-agri@nic.in','1039'=>'dinesh.kumar70@gov.in','1040'=>'old_application','1041'=>'old_application','1042'=>'old_application','1043'=>'old_application','1044'=>'old_application','1045'=>'old_application','1046'=>'old_application','1047'=>'old_application','1048'=>'old_application','1049'=>'old_application','1050'=>'old_application','1051'=>'old_application','1052'=>'old_application','1053'=>'old_application','1054'=>'old_application','1055'=>'old_application','1056'=>'old_application','1057'=>'old_application','1058'=>'old_application','1059'=>'old_application','1060'=>'old_application','1061'=>'old_application','1062'=>'old_application','1063'=>'old_application','1064'=>'old_application','1065'=>'melvinroy.p@gov.in','1066'=>'melvinroy.p@gov.in','1067'=>'old_application','1068'=>'old_application','1069'=>'old_application','1070'=>'old_application','1071'=>'dinesh.kumar70@gov.in','1072'=>'old_application','1073'=>'old_application','1074'=>'old_application','1075'=>'old_application','1076'=>'old_application','1077'=>'old_application','1078'=>'manojkumar.92@gov.in','1079'=>'old_application','1080'=>'old_application','1081'=>'old_application','1082'=>'old_application','1083'=>'old_application','1084'=>'old_application','1085'=>'old_application','1086'=>'old_application','1087'=>'old_application','1088'=>'old_application','1089'=>'old_application','1090'=>'old_application','1091'=>'old_application','1092'=>'old_application','1093'=>'old_application','1094'=>'old_application','1095'=>'old_application','1096'=>'old_application','1097'=>'old_application','1098'=>'old_application','1099'=>'old_application','1100'=>'old_application','1101'=>'old_application','1102'=>'old_application','1103'=>'old_application','1104'=>'old_application','1105'=>'old_application','1106'=>'old_application','1107'=>'old_application','1108'=>'old_application','1109'=>'dinesh.kumar70@gov.in','1110'=>'old_application','1111'=>'dinesh.kumar70@gov.in','1112'=>'dinesh.kumar70@gov.in','1113'=>'dinesh.kumar70@gov.in','1114'=>'dinesh.kumar70@gov.in','1115'=>'dinesh.kumar70@gov.in','1116'=>'dinesh.kumar70@gov.in','1117'=>'dinesh.kumar70@gov.in','1118'=>'old_application','1119'=>'dinesh.kumar70@gov.in','1120'=>'dinesh.kumar70@gov.in','1121'=>'anilkumar.pillai@gov.in','1122'=>'old_application','1123'=>'old_application','1124'=>'old_application','1125'=>'old_application','1126'=>'old_application','1127'=>'dinesh.kumar70@gov.in','1128'=>'jaiveer.singh@gov.in','1129'=>'dinesh.kumar70@gov.in','1130'=>'jaiveer.singh@gov.in','1131'=>'jaiveer.singh@gov.in','1132'=>'satbirsingh.saghu@gov.in','1133'=>'old_application','1134'=>'old_application','1135'=>'dinesh.kumar70@gov.in','1136'=>'dinesh.kumar70@gov.in','1137'=>'old_application','1138'=>'dinesh.kumar70@gov.in','1139'=>'old_application','1140'=>'old_application','1141'=>'old_application','1142'=>'dinesh.kumar70@gov.in','1143'=>'old_application','1144'=>'old_application','1145'=>'sonali.bagade@gov.in','1146'=>'old_application','1147'=>'old_application','1148'=>'old_application','1149'=>'old_application','1150'=>'old_application','1151'=>'old_application','1152'=>'old_application','1153'=>'old_application','1154'=>'old_application','1155'=>'old_application','1156'=>'old_application','1157'=>'old_application','1158'=>'manojkumar.92@gov.in','1159'=>'old_application','1160'=>'old_application','1161'=>'dinesh.kumar70@gov.in','1162'=>'dinesh.kumar70@gov.in','1163'=>'old_application','1164'=>'dinesh.kumar70@gov.in','1165'=>'dinesh.kumar70@gov.in','1166'=>'old_application','1167'=>'dinesh.kumar70@gov.in','1168'=>'old_application','1169'=>'old_application','1170'=>'prasad.chakraborty@nic.in','1171'=>'prasad.chakraborty@nic.in','1172'=>'brajesh.tiwari@gov.in','1173'=>'old_application','1174'=>'brajesh.tiwari@gov.in','1175'=>'brajesh.tiwari@gov.in','1176'=>'brajesh.tiwari@gov.in','1177'=>'dinesh.kumar70@gov.in','1178'=>'dinesh.kumar70@gov.in','1179'=>'prasad.chakraborty@nic.in','1180'=>'dm.govindareddy@gov.in','1181'=>'old_application','1182'=>'jaiveer.singh@gov.in','1183'=>'old_application','1184'=>'old_application','1185'=>'skkoul.dmi@gov.in','1186'=>'skkoul.dmi@gov.in','1187'=>'skkoul.dmi@gov.in','1188'=>'skkoul.dmi@gov.in','1189'=>'old_application','1190'=>'old_application','1191'=>'jaiveer.singh@gov.in','1192'=>'old_application','1193'=>'old_application','1194'=>'jawahar.malawath@gov.in','1195'=>'skkoul.dmi@gov.in','1196'=>'skkoul.dmi@gov.in','1197'=>'old_application','1198'=>'old_application','1199'=>'old_application','1200'=>'old_application','1201'=>'pankaj.girdhar62@gov.in','1202'=>'pankaj.girdhar62@gov.in','1203'=>'pankaj.girdhar62@gov.in','1204'=>'old_application','1205'=>'old_application','1206'=>'old_application','1207'=>'old_application','1208'=>'santosh.rangrao@gov.in','1209'=>'old_application','1210'=>'old_application','1211'=>'old_application','1212'=>'old_application','1213'=>'old_application','1214'=>'old_application','1215'=>'pankaj.girdhar62@gov.in','1216'=>'dinesh.kumar70@gov.in','1217'=>'old_application','1218'=>'old_application','1219'=>'old_application','1220'=>'old_application','1221'=>'melvinroy.p@gov.in','1222'=>'melvinroy.p@gov.in','1223'=>'pankaj.girdhar62@gov.in','1224'=>'melvinroy.p@gov.in','1225'=>'old_application','1226'=>'old_application','1227'=>'old_application','1228'=>'old_application','1229'=>'old_application','1230'=>'old_application','1231'=>'mehra.sanjay@gov.in','1232'=>'editordmi-agri@nic.in','1233'=>'old_application','1234'=>'old_application','1235'=>'old_application',
        '1236'=>'pankaj.girdhar62@gov.in','1237'=>'old_application','1238'=>'old_application','1239'=>'old_application','1240'=>'old_application','1241'=>'old_application','1242'=>'pankaj.girdhar62@gov.in','1243'=>'sonali.bagade@gov.in','1244'=>'sonali.bagade@gov.in','1245'=>'sonali.bagade@gov.in','1246'=>'old_application','1247'=>'old_application','1248'=>'melvinroy.p@gov.in','1249'=>'dm.govindareddy@gov.in','1250'=>'dm.govindareddy@gov.in','1251'=>'old_application','1252'=>'melvinroy.p@gov.in','1253'=>'melvinroy.p@gov.in','1254'=>'melvinroy.p@gov.in','1255'=>'melvinroy.p@gov.in','1256'=>'melvinroy.p@gov.in','1257'=>'melvinroy.p@gov.in','1258'=>'old_application','1259'=>'old_application','1260'=>'old_application','1261'=>'old_application','1262'=>'satbirsingh.saghu@gov.in','1263'=>'dm.govindareddy@gov.in','1264'=>'satbirsingh.saghu@gov.in','1265'=>'dm.govindareddy@gov.in','1266'=>'melvinroy.p@gov.in','1267'=>'old_application','1268'=>'dm.govindareddy@gov.in','1269'=>'dm.govindareddy@gov.in','1270'=>'dm.govindareddy@gov.in','1271'=>'dm.govindareddy@gov.in','1272'=>'satbirsingh.saghu@gov.in','1273'=>'old_application','1274'=>'dm.govindareddy@gov.in','1275'=>'old_application','1276'=>'dm.govindareddy@gov.in','1277'=>'melvinroy.p@gov.in','1278'=>'dm.govindareddy@gov.in','1279'=>'melvinroy.p@gov.in','1280'=>'dm.govindareddy@gov.in','1281'=>'melvinroy.p@gov.in','1282'=>'melvinroy.p@gov.in','1283'=>'old_application','1284'=>'old_application','1285'=>'old_application','1286'=>'old_application','1287'=>'old_application','1288'=>'editordmi-agri@nic.in','1289'=>'melvinroy.p@gov.in','1290'=>'old_application','1291'=>'old_application','1292'=>'melvinroy.p@gov.in','1293'=>'old_application','1294'=>'old_application','1295'=>'satbirsingh.saghu@gov.in','1296'=>'satbirsingh.saghu@gov.in','1297'=>'brajesh.tiwari@gov.in','1298'=>'santosh.rangrao@gov.in','1299'=>'santosh.rangrao@gov.in','1300'=>'santosh.rangrao@gov.in','1301'=>'santosh.rangrao@gov.in','1302'=>'manojkumar.92@gov.in','1303'=>'old_application','1304'=>'old_application','1305'=>'old_application','1306'=>'melvinroy.p@gov.in','1307'=>'manojkumar.92@gov.in','1308'=>'manojkumar.92@gov.in','1309'=>'manojkumar.92@gov.in','1310'=>'manojkumar.92@gov.in','1311'=>'manojkumar.92@gov.in','1312'=>'manojkumar.92@gov.in','1313'=>'old_application','1314'=>'old_application','1315'=>'old_application','1316'=>'old_application','1317'=>'old_application','1318'=>'melvinroy.p@gov.in','1319'=>'skkoul.dmi@gov.in','1320'=>'pankaj.girdhar62@gov.in','1321'=>'old_application','1322'=>'old_application','1323'=>'pankaj.girdhar62@gov.in','1324'=>'pankaj.girdhar62@gov.in','1325'=>'old_application','1326'=>'pankaj.girdhar62@gov.in','1327'=>'pankaj.girdhar62@gov.in','1328'=>'pankaj.girdhar62@gov.in','1329'=>'pankaj.girdhar62@gov.in','1330'=>'pankaj.girdhar62@gov.in','1331'=>'melvinroy.p@gov.in','1332'=>'melvinroy.p@gov.in','1333'=>'melvinroy.p@gov.in','1334'=>'manojkumar.92@gov.in','1335'=>'old_application','1336'=>'melvinroy.p@gov.in','1337'=>'old_application','1338'=>'old_application','1339'=>'dinesh.kumar70@gov.in','1340'=>'old_application','1341'=>'jawahar.malawath@gov.in','1342'=>'jawahar.malawath@gov.in','1343'=>'jawahar.malawath@gov.in','1344'=>'jawahar.malawath@gov.in','1345'=>'jawahar.malawath@gov.in','1346'=>'old_application','1347'=>'jawahar.malawath@gov.in','1348'=>'old_application','1349'=>'raghavendra.murgod@gov.in','1350'=>'brajesh.tiwari@gov.in','1351'=>'raghavendra.murgod@gov.in','1352'=>'old_application','1353'=>'old_application','1354'=>'jawahar.malawath@gov.in','1355'=>'jawahar.malawath@gov.in','1356'=>'jawahar.malawath@gov.in','1357'=>'jawahar.malawath@gov.in','1358'=>'jawahar.malawath@gov.in','1359'=>'old_application','1360'=>'old_application','1361'=>'manojkumar.92@gov.in','1362'=>'manojkumar.92@gov.in','1363'=>'manojkumar.92@gov.in','1364'=>'manojkumar.92@gov.in','1365'=>'old_application','1366'=>'manojkumar.92@gov.in','1367'=>'manojkumar.92@gov.in','1368'=>'manojkumar.92@gov.in','1369'=>'manojkumar.92@gov.in','1370'=>'old_application','1371'=>'melvinroy.p@gov.in','1372'=>'pankaj.girdhar62@gov.in','1373'=>'old_application','1374'=>'pankaj.girdhar62@gov.in','1375'=>'pankaj.girdhar62@gov.in','1376'=>'anilkumar.pillai@gov.in','1377'=>'pankaj.girdhar62@gov.in','1378'=>'pankaj.girdhar62@gov.in','1379'=>'brajesh.tiwari@gov.in','1380'=>'raghavendra.murgod@gov.in','1381'=>'raghavendra.murgod@gov.in','1382'=>'old_application','1383'=>'old_application','1384'=>'pankaj.girdhar62@gov.in','1385'=>'pankaj.girdhar62@gov.in','1386'=>'dinesh.kumar70@gov.in','1387'=>'melvinroy.p@gov.in','1388'=>'melvinroy.p@gov.in','1389'=>'melvinroy.p@gov.in','1390'=>'prasad.chakraborty@nic.in','1391'=>'prasad.chakraborty@nic.in','1392'=>'pankaj.girdhar62@gov.in','1393'=>'old_application','1394'=>'pankaj.girdhar62@gov.in','1395'=>'pankaj.girdhar62@gov.in','1396'=>'anilkumar.pillai@gov.in','1397'=>'old_application','1398'=>'anilkumar.pillai@gov.in','1399'=>'pankaj.girdhar62@gov.in','1400'=>'pankaj.girdhar62@gov.in','1401'=>'anilkumar.pillai@gov.in','1402'=>'anilkumar.pillai@gov.in','1403'=>'manojkumar.92@gov.in','1404'=>'editordmi-agri@nic.in','1405'=>'pankaj.girdhar62@gov.in','1406'=>'dinesh.kumar70@gov.in','1407'=>'melvinroy.p@gov.in','1408'=>'dinesh.kumar70@gov.in','1409'=>'melvinroy.p@gov.in','1410'=>'melvinroy.p@gov.in','1411'=>'editordmi-agri@nic.in','1412'=>'editordmi-agri@nic.in','1413'=>'editordmi-agri@nic.in','1414'=>'editordmi-agri@nic.in','1415'=>'editordmi-agri@nic.in','1416'=>'editordmi-agri@nic.in','1417'=>'editordmi-agri@nic.in','1418'=>'editordmi-agri@nic.in','1419'=>'editordmi-agri@nic.in','1420'=>'melvinroy.p@gov.in','1421'=>'melvinroy.p@gov.in','1422'=>'old_application','1423'=>'old_application','1424'=>'old_application','1425'=>'anilkumar.pillai@gov.in','1426'=>'anilkumar.pillai@gov.in','1427'=>'editordmi-agri@nic.in','1428'=>'brajesh.tiwari@gov.in','1429'=>'melvinroy.p@gov.in','1430'=>'dm.govindareddy@gov.in','1431'=>'jaiveer.singh@gov.in','1432'=>'old_application','1433'=>'melvinroy.p@gov.in','1434'=>'editordmi-agri@nic.in','1435'=>'editordmi-agri@nic.in','1436'=>'editordmi-agri@nic.in','1437'=>'editordmi-agri@nic.in','1438'=>'editordmi-agri@nic.in','1439'=>'melvinroy.p@gov.in','1440'=>'santosh.rangrao@gov.in','1441'=>'old_application','1442'=>'old_application','1443'=>'old_application','1444'=>'old_application','1445'=>'old_application','1446'=>'pankaj.girdhar62@gov.in','1447'=>'dm.govindareddy@gov.in','1448'=>'melvinroy.p@gov.in','1449'=>'k.anupam@gov.in','1450'=>'pankaj.girdhar62@gov.in','1451'=>'anilkumar.pillai@gov.in','1452'=>'raghavendra.murgod@gov.in','1453'=>'melvinroy.p@gov.in','1454'=>'manojkumar.92@gov.in','1455'=>'melvinroy.p@gov.in','1456'=>'melvinroy.p@gov.in','1457'=>'dm.govindareddy@gov.in','1458'=>'melvinroy.p@gov.in','1459'=>'manojkumar.92@gov.in','1460'=>'editordmi-agri@nic.in','1461'=>'editordmi-agri@nic.in','1462'=>'old_application','1463'=>'melvinroy.p@gov.in','1464'=>'old_application','1465'=>'old_application','1466'=>'manojkumar.92@gov.in','1467'=>'old_application','1468'=>'old_application','1469'=>'old_application','1470'=>'old_application','1471'=>'old_application','1472'=>'old_application','1473'=>'old_application','1474'=>'editordmi-agri@nic.in','1475'=>'editordmi-agri@nic.in','1476'=>'editordmi-agri@nic.in','1477'=>'raghavendra.murgod@gov.in','1478'=>'old_application','1479'=>'editordmi-agri@nic.in','1480'=>'brajesh.tiwari@gov.in','1481'=>'old_application','1482'=>'old_application','1483'=>'old_application','1484'=>'old_application','1485'=>'anilkumar.pillai@gov.in','1486'=>'jawahar.malawath@gov.in','1487'=>'brajesh.tiwari@gov.in','1488'=>'prasad.chakraborty@nic.in','1489'=>'shivnandan.65@gov.in','1490'=>'old_application','1491'=>'old_application','1492'=>'old_application','1493'=>'prasad.chakraborty@nic.in','1494'=>'santosh.rangrao@gov.in','1495'=>'dm.govindareddy@gov.in','1496'=>'old_application','1497'=>'old_application','1498'=>'old_application','1499'=>'old_application','1500'=>'old_application','1501'=>'old_application','1502'=>'old_application','1503'=>'melvinroy.p@gov.in','1504'=>'old_application','1505'=>'old_application','1506'=>'old_application','1507'=>'old_application','1508'=>'old_application','1509'=>'old_application','1510'=>'old_application','1511'=>'prasad.chakraborty@nic.in','1512'=>'old_application','1513'=>'prasad.chakraborty@nic.in','1514'=>'old_application','1515'=>'old_application','1516'=>'melvinroy.p@gov.in','1517'=>'old_application','1518'=>'old_application','1519'=>'old_application','1520'=>'old_application','1521'=>'old_application','1522'=>'old_application','1523'=>'old_application','1524'=>'old_application','1525'=>'old_application','1526'=>'old_application','1527'=>'old_application','1528'=>'old_application','1529'=>'old_application','1530'=>'old_application','1531'=>'sonali.bagade@gov.in','1532'=>'melvinroy.p@gov.in','1533'=>'melvinroy.p@gov.in','1534'=>'melvinroy.p@gov.in','1535'=>'old_application','1536'=>'old_application','1537'=>'old_application','1538'=>'old_application','1539'=>'old_application','1540'=>'old_application','1541'=>'old_application','1542'=>'old_application','1543'=>'old_application','1544'=>'old_application','1545'=>'old_application','1546'=>'old_application','1547'=>'old_application','1548'=>'old_application','1549'=>'old_application','1550'=>'old_application','1551'=>'old_application','1552'=>'old_application','1553'=>'old_application','1554'=>'old_application','1555'=>'old_application','1556'=>'pankaj.girdhar62@gov.in','1557'=>'old_application','1558'=>'old_application','1559'=>'old_application','1560'=>'old_application','1561'=>'old_application','1562'=>'old_application','1563'=>'old_application','1564'=>'old_application','1565'=>'old_application','1566'=>'old_application','1567'=>'prasad.chakraborty@nic.in','1568'=>'old_application','1569'=>'old_application','1570'=>'old_application','1571'=>'old_application','1572'=>'raghavendra.murgod@gov.in','1573'=>'old_application','1574'=>'old_application','1575'=>'old_application','1576'=>'old_application','1577'=>'old_application','1578'=>'old_application','1579'=>'old_application','1580'=>'old_application','1581'=>'old_application','1582'=>'old_application','1583'=>'old_application','1584'=>'old_application','1585'=>'old_application','1586'=>'old_application','1587'=>'old_application','1588'=>'old_application','1589'=>'old_application','1590'=>'old_application','1591'=>'old_application','1592'=>'old_application','1593'=>'old_application','1594'=>'old_application','1595'=>'old_application','1596'=>'old_application','1597'=>'old_application','1598'=>'old_application','1599'=>'old_application','1600'=>'old_application','1601'=>'old_application','1602'=>'old_application','1603'=>'old_application','1604'=>'old_application','1605'=>'old_application','1606'=>'old_application','1607'=>'old_application','1608'=>'old_application','1609'=>'old_application','1610'=>'old_application','1611'=>'old_application','1612'=>'old_application','1613'=>'old_application','1614'=>'old_application','1615'=>'old_application','1616'=>'old_application','1617'=>'old_application','1618'=>'old_application','1619'=>'old_application','1620'=>'melvinroy.p@gov.in','1621'=>'melvinroy.p@gov.in','1622'=>'melvinroy.p@gov.in','1623'=>'melvinroy.p@gov.in','1624'=>'melvinroy.p@gov.in','1625'=>'melvinroy.p@gov.in','1626'=>'melvinroy.p@gov.in','1627'=>'melvinroy.p@gov.in','1628'=>'melvinroy.p@gov.in','1629'=>'melvinroy.p@gov.in','1630'=>'melvinroy.p@gov.in','1631'=>'old_application','1632'=>'old_application','1633'=>'old_application','1634'=>'old_application','1635'=>'old_application','1636'=>'old_application','1637'=>'old_application','1638'=>'old_application','1639'=>'old_application','1640'=>'old_application','1641'=>'old_application','1642'=>'melvinroy.p@gov.in','1643'=>'melvinroy.p@gov.in','1644'=>'dinesh.kumar70@gov.in','1645'=>'dinesh.kumar70@gov.in','1646'=>'dinesh.kumar70@gov.in','1647'=>'dinesh.kumar70@gov.in','1648'=>'dinesh.kumar70@gov.in','1649'=>'dinesh.kumar70@gov.in','1650'=>'melvinroy.p@gov.in','1651'=>'melvinroy.p@gov.in','1652'=>'old_application','1653'=>'old_application','1654'=>'old_application','1655'=>'melvinroy.p@gov.in','1656'=>'old_application','1657'=>'old_application','1658'=>'old_application','1659'=>'old_application','1660'=>'old_application','1661'=>'old_application','1662'=>'old_application','1663'=>'old_application','1664'=>'old_application','1665'=>'melvinroy.p@gov.in','1666'=>'melvinroy.p@gov.in','1667'=>'old_application','1668'=>'melvinroy.p@gov.in','1669'=>'jawahar.malawath@gov.in','1670'=>'jawahar.malawath@gov.in','1671'=>'jawahar.malawath@gov.in','1672'=>'jawahar.malawath@gov.in','1673'=>'jawahar.malawath@gov.in','1674'=>'jawahar.malawath@gov.in','1675'=>'jawahar.malawath@gov.in','1676'=>'dinesh.kumar70@gov.in','1677'=>'dinesh.kumar70@gov.in','1678'=>'dinesh.kumar70@gov.in','1679'=>'dinesh.kumar70@gov.in','1680'=>'dinesh.kumar70@gov.in','1681'=>'dinesh.kumar70@gov.in','1682'=>'dinesh.kumar70@gov.in','1683'=>'dinesh.kumar70@gov.in','1684'=>'old_application','1685'=>'old_application','1686'=>'dinesh.kumar70@gov.in','1687'=>'dinesh.kumar70@gov.in','1688'=>'dinesh.kumar70@gov.in','1689'=>'dinesh.kumar70@gov.in','1690'=>'dinesh.kumar70@gov.in','1691'=>'dinesh.kumar70@gov.in','1692'=>'old_application','1693'=>'old_application','1694'=>'old_application','1695'=>'old_application','1696'=>'dinesh.kumar70@gov.in','1697'=>'dinesh.kumar70@gov.in','1698'=>'dinesh.kumar70@gov.in','1699'=>'dinesh.kumar70@gov.in','1700'=>'dinesh.kumar70@gov.in','1701'=>'dinesh.kumar70@gov.in','1702'=>'old_application','1703'=>'dinesh.kumar70@gov.in','1704'=>'dinesh.kumar70@gov.in','1705'=>'gaurav.keshari@gov.in','1706'=>'old_application','1707'=>'shivnandan.65@gov.in','1708'=>'shivnandan.65@gov.in','1709'=>'shivnandan.65@gov.in','1710'=>'old_application','1711'=>'old_application','1712'=>'old_application','1713'=>'old_application','1714'=>'old_application','1715'=>'old_application','1716'=>'old_application','1717'=>'old_application','1718'=>'old_application','1719'=>'old_application','1720'=>'old_application','1721'=>'raghavendra.murgod@gov.in','1722'=>'raghavendra.murgod@gov.in','1723'=>'raghavendra.murgod@gov.in','1724'=>'raghavendra.murgod@gov.in','1725'=>'raghavendra.murgod@gov.in','1726'=>'raghavendra.murgod@gov.in','1727'=>'old_application','1728'=>'old_application','1729'=>'raghavendra.murgod@gov.in','1730'=>'old_application','1731'=>'jawahar.malawath@gov.in','1732'=>'jawahar.malawath@gov.in','1733'=>'jawahar.malawath@gov.in','1734'=>'old_application','1735'=>'old_application','1736'=>'old_application','1737'=>'old_application','1738'=>'old_application','1739'=>'prasad.chakraborty@nic.in','1740'=>'melvinroy.p@gov.in','1741'=>'prasad.chakraborty@nic.in','1742'=>'prasad.chakraborty@nic.in','1743'=>'prasad.chakraborty@nic.in','1744'=>'old_application','1745'=>'raghavendra.murgod@gov.in','1746'=>'raghavendra.murgod@gov.in','1747'=>'dinesh.kumar70@gov.in','1748'=>'dinesh.kumar70@gov.in','1749'=>'dinesh.kumar70@gov.in','1750'=>'dinesh.kumar70@gov.in','1751'=>'dinesh.kumar70@gov.in','1752'=>'dinesh.kumar70@gov.in','1753'=>'dinesh.kumar70@gov.in','1754'=>'sonali.bagade@gov.in','1755'=>'sonali.bagade@gov.in','1756'=>'sonali.bagade@gov.in','1757'=>'sonali.bagade@gov.in','1758'=>'sonali.bagade@gov.in','1759'=>'sonali.bagade@gov.in','1760'=>'sonali.bagade@gov.in','1761'=>'sonali.bagade@gov.in','1762'=>'old_application','1763'=>'raghavendra.murgod@gov.in','1764'=>'raghavendra.murgod@gov.in','1765'=>'old_application','1766'=>'jawahar.malawath@gov.in','1767'=>'jawahar.malawath@gov.in','1768'=>'old_application','1769'=>'jawahar.malawath@gov.in','1770'=>'old_application','1771'=>'prasad.chakraborty@nic.in','1772'=>'raghavendra.murgod@gov.in','1773'=>'raghavendra.murgod@gov.in','1774'=>'raghavendra.murgod@gov.in','1775'=>'dinesh.kumar70@gov.in','1776'=>'old_application','1777'=>'old_application','1778'=>'old_application','1779'=>'old_application','1780'=>'old_application','1781'=>'jawahar.malawath@gov.in','1782'=>'old_application','1783'=>'old_application','1784'=>'old_application','1785'=>'old_application','1786'=>'jawahar.malawath@gov.in','1787'=>'jawahar.malawath@gov.in','1788'=>'melvinroy.p@gov.in','1789'=>'old_application','1790'=>'old_application','1791'=>'old_application','1792'=>'old_application','1793'=>'jawahar.malawath@gov.in','1794'=>'jawahar.malawath@gov.in','1795'=>'old_application','1796'=>'melvinroy.p@gov.in','1797'=>'old_application','1798'=>'old_application','1799'=>'old_application','1800'=>'old_application','1801'=>'sonali.bagade@gov.in','1802'=>'old_application','1803'=>'old_application','1804'=>'old_application','1805'=>'old_application','1806'=>'old_application','1807'=>'raghavendra.murgod@gov.in','1808'=>'old_application','1809'=>'raghavendra.murgod@gov.in','1810'=>'raghavendra.murgod@gov.in','1811'=>'raghavendra.murgod@gov.in','1812'=>'old_application','1813'=>'raghavendra.murgod@gov.in','1814'=>'raghavendra.murgod@gov.in','1815'=>'raghavendra.murgod@gov.in','1816'=>'raghavendra.murgod@gov.in','1817'=>'raghavendra.murgod@gov.in','1818'=>'old_application','1819'=>'old_application','1820'=>'old_application','1821'=>'old_application','1822'=>'old_application','1823'=>'old_application','1824'=>'old_application','1825'=>'old_application','1826'=>'old_application','1827'=>'old_application','1828'=>'old_application','1829'=>'old_application','1830'=>'old_application','1831'=>'old_application','1832'=>'old_application','1833'=>'old_application','1834'=>'jawahar.malawath@gov.in','1835'=>'jawahar.malawath@gov.in','1836'=>'melvinroy.p@gov.in','1837'=>'melvinroy.p@gov.in','1838'=>'old_application','1839'=>'old_application','1840'=>'jawahar.malawath@gov.in','1841'=>'old_application','1842'=>'old_application','1843'=>'old_application','1844'=>'pb.babbanwar@gov.in','1845'=>'old_application','1846'=>'pb.babbanwar@gov.in','1847'=>'old_application','1848'=>'old_application','1849'=>'old_application','1850'=>'old_application','1851'=>'old_application','1852'=>'old_application','1853'=>'old_application','1854'=>'old_application','1855'=>'old_application','1856'=>'old_application','1857'=>'old_application','1858'=>'old_application','1859'=>'old_application','1860'=>'melvinroy.p@gov.in','1861'=>'melvinroy.p@gov.in','1862'=>'melvinroy.p@gov.in','1863'=>'old_application','1864'=>'old_application','1865'=>'old_application','1866'=>'old_application','1867'=>'old_application','1868'=>'old_application','1869'=>'dm.govindareddy@gov.in','1870'=>'raghavendra.murgod@gov.in','1871'=>'raghavendra.murgod@gov.in','1872'=>'old_application','1873'=>'dm.govindareddy@gov.in','1874'=>'old_application','1875'=>'old_application','1876'=>'editordmi-agri@nic.in','1877'=>'dinesh.kumar70@gov.in','1878'=>'editordmi-agri@nic.in','1879'=>'old_application','1880'=>'editordmi-agri@nic.in','1881'=>'dinesh.kumar70@gov.in','1882'=>'editordmi-agri@nic.in','1883'=>'old_application','1884'=>'satbirsingh.saghu@gov.in','1885'=>'old_application','1886'=>'old_application','1887'=>'old_application','1888'=>'raghavendra.murgod@gov.in','1889'=>'manojkumar.92@gov.in','1890'=>'raghavendra.murgod@gov.in','1891'=>'manojkumar.92@gov.in','1892'=>'prasad.chakraborty@nic.in','1893'=>'manojkumar.92@gov.in','1894'=>'prasad.chakraborty@nic.in','1895'=>'manojkumar.92@gov.in','1896'=>'prasad.chakraborty@nic.in','1897'=>'manojkumar.92@gov.in','1898'=>'manojkumar.92@gov.in','1899'=>'editordmi-agri@nic.in','1900'=>'manojkumar.92@gov.in','1901'=>'manojkumar.92@gov.in','1902'=>'editordmi-agri@nic.in','1903'=>'manojkumar.92@gov.in','1904'=>'manojkumar.92@gov.in','1905'=>'manojkumar.92@gov.in','1906'=>'manojkumar.92@gov.in','1907'=>'manojkumar.92@gov.in','1908'=>'old_application','1909'=>'manojkumar.92@gov.in','1910'=>'shiva.s67@gov.in','1911'=>'manojkumar.92@gov.in','1912'=>'shiva.s67@gov.in','1913'=>'manojkumar.92@gov.in','1914'=>'manojkumar.92@gov.in','1915'=>'old_application','1916'=>'melvinroy.p@gov.in','1917'=>'melvinroy.p@gov.in','1918'=>'old_application','1919'=>'melvinroy.p@gov.in','1920'=>'old_application','1921'=>'melvinroy.p@gov.in','1922'=>'old_application','1923'=>'manojkumar.92@gov.in','1924'=>'manojkumar.92@gov.in','1925'=>'manojkumar.92@gov.in','1926'=>'old_application','1927'=>'manojkumar.92@gov.in','1928'=>'old_application','1929'=>'manojkumar.92@gov.in','1930'=>'old_application','1931'=>'melvinroy.p@gov.in','1932'=>'manojkumar.92@gov.in','1933'=>'melvinroy.p@gov.in','1934'=>'manojkumar.92@gov.in','1935'=>'old_application','1936'=>'melvinroy.p@gov.in','1937'=>'old_application','1938'=>'raghavendra.murgod@gov.in','1939'=>'raghavendra.murgod@gov.in','1940'=>'skkoul.dmi@gov.in','1941'=>'raghavendra.murgod@gov.in','1942'=>'skkoul.dmi@gov.in','1943'=>'skkoul.dmi@gov.in','1944'=>'editordmi-agri@nic.in','1945'=>'skkoul.dmi@gov.in','1946'=>'skkoul.dmi@gov.in','1947'=>'melvinroy.p@gov.in','1948'=>'old_application','1949'=>'melvinroy.p@gov.in','1950'=>'old_application','1951'=>'editordmi-agri@nic.in','1952'=>'melvinroy.p@gov.in','1953'=>'editordmi-agri@nic.in','1954'=>'editordmi-agri@nic.in','1955'=>'raghavendra.murgod@gov.in','1956'=>'raghavendra.murgod@gov.in','1957'=>'raghavendra.murgod@gov.in','1958'=>'old_application','1959'=>'melvinroy.p@gov.in','1960'=>'melvinroy.p@gov.in','1961'=>'old_application','1962'=>'old_application','1963'=>'old_application','1964'=>'raghavendra.murgod@gov.in','1965'=>'raghavendra.murgod@gov.in','1966'=>'melvinroy.p@gov.in','1967'=>'melvinroy.p@gov.in','1968'=>'melvinroy.p@gov.in','1969'=>'melvinroy.p@gov.in','1970'=>'melvinroy.p@gov.in','1971'=>'melvinroy.p@gov.in','1972'=>'melvinroy.p@gov.in','1973'=>'old_application','1974'=>'old_application','1975'=>'melvinroy.p@gov.in','1976'=>'editordmi-agri@nic.in','1977'=>'editordmi-agri@nic.in','1978'=>'old_application','1979'=>'manojkumar.92@gov.in','1980'=>'editordmi-agri@nic.in','1981'=>'manojkumar.92@gov.in','1982'=>'manojkumar.92@gov.in','1983'=>'manojkumar.92@gov.in','1984'=>'editordmi-agri@nic.in','1985'=>'old_application','1986'=>'skkoul.dmi@gov.in','1987'=>'manojkumar.92@gov.in','1988'=>'melvinroy.p@gov.in','1989'=>'satbirsingh.saghu@gov.in','1990'=>'satbirsingh.saghu@gov.in','1991'=>'sonali.bagade@gov.in','1992'=>'jawahar.malawath@gov.in','1993'=>'jawahar.malawath@gov.in','1994'=>'jawahar.malawath@gov.in','1995'=>'old_application','1996'=>'dinesh.kumar70@gov.in','1997'=>'dinesh.kumar70@gov.in','1998'=>'old_application','1999'=>'melvinroy.p@gov.in','2000'=>'dm.govindareddy@gov.in','2001'=>'dinesh.kumar70@gov.in','2002'=>'melvinroy.p@gov.in','2003'=>'pankaj.girdhar62@gov.in','2004'=>'editordmi-agri@nic.in','2005'=>'anilkumar.pillai@gov.in','2006'=>'anilkumar.pillai@gov.in','2007'=>'anilkumar.pillai@gov.in','2008'=>'anilkumar.pillai@gov.in','2009'=>'anilkumar.pillai@gov.in','2010'=>'anilkumar.pillai@gov.in','2011'=>'anilkumar.pillai@gov.in','2012'=>'anilkumar.pillai@gov.in','2013'=>'anilkumar.pillai@gov.in','2014'=>'old_application','2015'=>'old_application','2016'=>'jawahar.malawath@gov.in','2017'=>'jawahar.malawath@gov.in','2018'=>'dm.govindareddy@gov.in','2019'=>'satbirsingh.saghu@gov.in',);
    
        foreach($testArr as $key=>$value){

            $this->loadModel('DmiGrantCertificatesPdfs');
            $this->DmiGrantCertificatesPdfs->updateAll(array('user_email_id'=>"$value"),array('id IS'=>$key));
        }
    }*/

    
/*    public function updateEmailWithEncoded(){			
			
        $this->autoRender=false;

        $this->loadModel('DmiUsers');
        $this->loadModel('DmiFirms');

        //get users table ids list
        $userIds = $this->DmiUsers->find('list',array('keyField'=>'id','valueField'=>'email','order'=>'id ASC'))->toArray();

        //get users table ids list
        $firmIds = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'email','order'=>'id ASC'))->toArray();

        //for flow wise common tables
        $this->loadModel('DmiFlowWiseTablesLists');
        $flowWisetables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('id IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();
        
        foreach($flowWisetables as $eachFlow){

            $hoAllocationTable = $eachFlow['ho_level_allocation'];
            $amaApprovedTable = $eachFlow['ama_approved_application'];
            $hoCommentsTable = $eachFlow['ho_comment_reply'];
            $allocationTable = $eachFlow['allocation'];
            $moCommentsTable = $eachFlow['commenting_with_mo'];
            $currentPositionTable = $eachFlow['appl_current_pos'];
            $rosocommentTable = $eachFlow['ro_so_comments'];
            $grantTable = $eachFlow['grant_pdf'];
            $level4ROApproved = $eachFlow['level_4_ro_approved'];

            //for allocation table
            $this->loadModel($allocationTable);
            $getRecords = $this->$allocationTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $level_1 = base64_decode($eachRecord['level_1']);
                    $level_2 = base64_decode($eachRecord['level_2']);
                    $level_3 = base64_decode($eachRecord['level_3']);
                    $current_level = base64_decode($eachRecord['current_level']);
                    $level_4_ro = base64_decode($eachRecord['level_4_ro']);
                    $level_4_mo = base64_decode($eachRecord['level_4_mo']);
                       
                 //   $UpdateValueArray = array('level_1'=>"$level_1",'level_2'=>"$level_2",'level_3'=>"$level_3",'current_level'=>"$current_level",'level_4_ro'=>"$level_4_ro",'level_4_mo'=>"$level_4_mo");
                    
                    if(!empty($level_1)){
                        $this->$allocationTable->updateAll(array('level_1'=>"$level_1"),array('id'=>$record_id));
                    }if(!empty($level_2)){
                        $this->$allocationTable->updateAll(array('level_2'=>"$level_2"),array('id'=>$record_id));
                    }if(!empty($level_3)){
                        $this->$allocationTable->updateAll(array('level_3'=>"$level_3"),array('id'=>$record_id));
                    }if(!empty($current_level)){
                        $this->$allocationTable->updateAll(array('current_level'=>"$current_level"),array('id'=>$record_id));
                    }if(!empty($level_4_ro)){
                        $this->$allocationTable->updateAll(array('level_4_ro'=>"$level_4_ro"),array('id'=>$record_id));
                    }if(!empty($level_4_mo)){
                        $this->$allocationTable->updateAll(array('level_4_mo'=>"$level_4_mo"),array('id'=>$record_id));
                    }
                    
            }

            //for current position table
            $this->loadModel($currentPositionTable);
            $getRecords = $this->$currentPositionTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $current_user_email_id = base64_decode($eachRecord['current_user_email_id']);
                    
                    $UpdateValueArray = array('current_user_email_id'=>"$current_user_email_id");

                    if(!empty($current_user_email_id)){
                        $this->$currentPositionTable->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
            }

            //for AMA approved table
            $this->loadModel($amaApprovedTable);
            $getRecords = $this->$amaApprovedTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $user_email_id = base64_decode($eachRecord['user_email_id']);
                    
                    $UpdateValueArray = array('user_email_id'=>"$user_email_id");

                    if(!empty($user_email_id)){
                        $this->$amaApprovedTable->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
            }

            //for grant table
            $this->loadModel($grantTable);
            $getRecords = $this->$grantTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                $record_id = $eachRecord['id'];
                if(base64_decode($eachRecord['user_email_id'],true)==true){
                    $user_email_id = base64_decode($eachRecord['user_email_id']);
                    
                    $UpdateValueArray = array('user_email_id'=>"$user_email_id");

                    if(!empty($user_email_id)){
                        $this->$grantTable->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                }
            }

            //for HO allocation table
            $this->loadModel($hoAllocationTable);
            $getRecords = $this->$hoAllocationTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $dy_ama = base64_decode($eachRecord['dy_ama']);
                    $ho_mo_smo = base64_decode($eachRecord['ho_mo_smo']);
                    $jt_ama = base64_decode($eachRecord['jt_ama']);
                    $ama = base64_decode($eachRecord['ama']);
                    $current_level = base64_decode($eachRecord['current_level']);
                    
                //    $UpdateValueArray = array('dy_ama'=>"$dy_ama",'ho_mo_smo'=>"$ho_mo_smo",'jt_ama'=>"$jt_ama",'ama'=>"$ama",'current_level'=>"$current_level");
                    
                    if(!empty($dy_ama)){
                        $this->$hoAllocationTable->updateAll(array('dy_ama'=>"$dy_ama"),array('id'=>$record_id));
                    }if(!empty($ho_mo_smo)){
                        $this->$hoAllocationTable->updateAll(array('ho_mo_smo'=>"$ho_mo_smo"),array('id'=>$record_id));
                    }if(!empty($jt_ama)){
                        $this->$hoAllocationTable->updateAll(array('jt_ama'=>"$jt_ama"),array('id'=>$record_id));
                    }if(!empty($ama)){
                        $this->$hoAllocationTable->updateAll(array('ama'=>"$ama"),array('id'=>$record_id));
                    }if(!empty($current_level)){
                        $this->$hoAllocationTable->updateAll(array('current_level'=>"$current_level"),array('id'=>$record_id));
                    }
            }

            //for HO comments table
            $this->loadModel($hoCommentsTable);
            $getRecords = $this->$hoCommentsTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $comment_by=null;
                    $comment_to=null;
                    if(base64_decode($eachRecord['comment_by'],true)==true){
                        $comment_by = base64_decode($eachRecord['comment_by']);
                    }
                    if(base64_decode($eachRecord['comment_to'],true)==true){
                        $comment_to = base64_decode($eachRecord['comment_to']);
                    }
                    
                //    $UpdateValueArray = array('comment_by'=>"$comment_by",'comment_to'=>"$comment_to");

                    if(!empty($comment_by)){
                        $this->$hoCommentsTable->updateAll(array('comment_by'=>"$comment_by"),array('id'=>$record_id));
                    }if(!empty($comment_to)){
                        $this->$hoCommentsTable->updateAll(array('comment_to'=>"$comment_to"),array('id'=>$record_id));
                    }
            }

            //for level 4 RO approved table
            $this->loadModel($level4ROApproved);
            $getRecords = $this->$level4ROApproved->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $user_email_id = base64_decode($eachRecord['user_email_id']);
                    
                    $UpdateValueArray = array('user_email_id'=>"$user_email_id");

                    if(!empty($user_email_id)){
                        $this->$level4ROApproved->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
            }

            //for mo ro comments table
            $this->loadModel($moCommentsTable);
            $getRecords = $this->$moCommentsTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $comment_by=null;
                    $comment_to=null;
                    if(base64_decode($eachRecord['comment_by'],true)==true){
                        $comment_by = base64_decode($eachRecord['comment_by']);
                    }
                    if(base64_decode($eachRecord['comment_to'],true)==true){
                        $comment_to = base64_decode($eachRecord['comment_to']);
                    }
                    
                //    $UpdateValueArray = array('comment_by'=>"$comment_by",'comment_to'=>"$comment_to");
                    if(!empty($comment_by)){
                        $this->$moCommentsTable->updateAll(array('comment_by'=>"$comment_by"),array('id'=>$record_id));
                    }if(!empty($comment_to)){
                        $this->$moCommentsTable->updateAll(array('comment_to'=>"$comment_to"),array('id'=>$record_id));
                    }
            }

            //for ro so comments table
            $this->loadModel($rosocommentTable);
            $getRecords = $this->$rosocommentTable->find('all',array('order'=>'id ASC'))->toArray();
            foreach($getRecords as $eachRecord){
                
                    $record_id = $eachRecord['id'];
                    $comment_by=null;
                    $comment_to=null;
                    if(base64_decode($eachRecord['comment_by'],true)==true){
                        $comment_by = base64_decode($eachRecord['comment_by']);
                    }
                    if(base64_decode($eachRecord['comment_to'],true)==true){
                        $comment_to = base64_decode($eachRecord['comment_to']);
                    }
                    
                //    $UpdateValueArray = array('comment_by'=>"$comment_by",'comment_to'=>"$comment_to");
                    if(!empty($comment_by)){
                        $this->$rosocommentTable->updateAll(array('comment_by'=>"$comment_by"),array('id'=>$record_id));
                    }if(!empty($comment_to)){
                        $this->$rosocommentTable->updateAll(array('comment_to'=>"$comment_to"),array('id'=>$record_id));
                    }
            }

        }


        //for other random tables having user email id
        $modelArray = array('Dmi15DigitSiteinspectionReports','Dmi15DigitApplDetails','DmiAllConstituentOilsDetails','DmiAllDirectorsDetails',
                            'DmiAllTanksDetails','DmiApplicationCharges','DmiApplTransferLogs','DmiAuthFirmRegistrations',
                            'DmiAuthPrimaryRegistrations','DmiBusinessTypes','DmiCaBusinessYears','DmiCaDomesticRenewalDetails',
                        'DmiCaExportSiteinspectionReports','DmiChangeDirectorsDetails','DmiChangeFirms','DmiChangeLabChemistsDetails','DmiChangeLabFirmDetails',
                        'DmiChangeLabInspectionReports','DmiChangeLabOtherDetails','DmiChemistComments','DmiChemistEducationDetails','DmiChemistExperienceDetails',
                        'DmiChemistFinalReports','DmiChemistOtherDetails','DmiChemistProfileDetails','DmiChemistTrainingDetails','DmiCrushingRefiningPeriods','DmiCustomerFirmProfiles',
                        'DmiCustomerLaboratoryDetails','DmiCustomerMachineryProfiles','DmiCustomerPackingDetails','DmiCustomerPremisesProfiles','DmiCustomerTblDetails',
                        'DmiDatesExtensionsLogs','DmiDistricts','DmiDivisionGrades','DmiEducationTypes','DmiEsignRequestResponseLogs','DmiECodeApplDetails','DmiECodeSiteinspectionReports',
                        'DmiGrantProvCertificateLogs','DmiIoAllocationLogs','DmiLaboratoryChemistsDetails','DmiLaboratoryFirmDetails','DmiLaboratoryOtherDetails',
                        'DmiLaboratoryRenewalOtherDetails','DmiLaboratorySiteinspectionReports','DmiLaboratoryTypes','DmiLoginStatuses','DmiMachineTypes','DmiMenus','DmiMoAllocationLogs',
                        'DmiOldCertDateUpdateLogs','DmiPackingTypes','DmiPages','DmiPaoDetails','DmiPrintingBusinessYears','DmiPrintingFirmProfiles','DmiPrintingPremisesProfiles',
                        'DmiPrintingRenewalDetails','DmiPrintingSiteinspectionReports','DmiPrintingUnitDetails','DmiRejectedApplLogs','DmiRenewalSiteinspectionReports','DmiReplicaChargesDetails',
                        'DmiReplicaChargesDetailsLogs','DmiReEsignGrantLogs','DmiRoAllocationLogs','DmiRoOffices','DmiSentEmailLogs','DmiSiteinspectionLaboratoryDetails',
                        'DmiSiteinspectionOtherDetails','DmiSiteinspectionPremisesDetails','DmiSiteinspectionPremisesProfiles','DmiSmsEmailTemplates','DmiStates','DmiTankShapes',
                        'DmiTempEsignStatuses','DmiUsers','DmiUsersResetpassKeys','DmiUserActionLogs','DmiUserFileUploads','DmiUserHistoryLogs','DmiUserLogs','DmiUserRoles',
    'DmiUserRolesManagmentLogs','DmiWorkTransferHoPermissions','DmiWorkTransferLogs','LimsSampleCharges');
                            
        foreach($modelArray as $eachModel){
            
            $this->loadModel($eachModel);
            $getRecords = $this->$eachModel->find('all',array('order'=>'id ASC'))->toArray();
            
            if($eachModel=='Dmi15DigitApplDetails' || $eachModel=='DmiAllConstituentOilsDetails' || $eachModel=='DmiAllDirectorsDetails' || $eachModel=='DmiAllTanksDetails'
                || $eachModel=='DmiApplicationCharges' || $eachModel=='DmiAuthFirmRegistrations' || $eachModel=='DmiAuthPrimaryRegistrations' || $eachModel=='DmiBusinessTypes'
                || $eachModel=='DmiCaBusinessYears' || $eachModel=='DmiCaDomesticRenewalDetails' || $eachModel=='DmiChangeDirectorsDetails' || $eachModel=='DmiChangeLabChemistsDetails'
                || $eachModel=='DmiChangeLabFirmDetails' || $eachModel=='DmiChangeLabOtherDetails' || $eachModel=='DmiChemistEducationDetails' || $eachModel=='DmiChemistExperienceDetails'
                || $eachModel=='DmiChemistOtherDetails' || $eachModel=='DmiChemistTrainingDetails' || $eachModel=='DmiCrushingRefiningPeriods' || $eachModel=='DmiCustomerLaboratoryDetails'
                || $eachModel=='DmiCustomerMachineryProfiles' || $eachModel=='DmiCustomerPackingDetails' || $eachModel=='DmiCustomerPremisesProfiles' || $eachModel=='DmiCustomerTblDetails'
                || $eachModel=='DmiDistricts' || $eachModel=='DmiECodeApplDetails' || $eachModel=='DmiGrantProvCertificateLogs' || $eachModel=='DmiLaboratoryChemistsDetails'
                || $eachModel=='DmiLaboratoryFirmDetails' || $eachModel=='DmiLaboratoryOtherDetails' || $eachModel=='DmiLaboratoryRenewalOtherDetails' || $eachModel=='DmiLaboratoryTypes'
                || $eachModel=='DmiMachineTypes' || $eachModel=='DmiMenus' || $eachModel=='DmiPackingTypes' || $eachModel=='DmiPages' || $eachModel=='DmiPaoDetails' || $eachModel=='DmiPrintingBusinessYears'
                || $eachModel=='DmiPrintingPremisesProfiles' || $eachModel=='DmiPrintingRenewalDetails' || $eachModel=='DmiPrintingUnitDetails' || $eachModel=='DmiSmsEmailTemplates'
                || $eachModel=='DmiStates' || $eachModel=='DmiTankShapes' || $eachModel=='DmiUserFileUploads' || $eachModel=='DmiUserRoles' || $eachModel=='LimsSampleCharges'){
                                    
                    foreach($getRecords as $eachRecord){
                    
                        $record_id = $eachRecord['id'];
                        if(base64_decode($eachRecord['user_email_id'],true)==true){
                            $user_email_id = base64_decode($eachRecord['user_email_id']);
                            
                            $UpdateValueArray = array('user_email_id'=>"$user_email_id");

                            if(!empty($user_email_id)){
                                $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                            }
                        }
                    }

            }elseif($eachModel=='Dmi15DigitSiteinspectionReports' || $eachModel=='DmiCaExportSiteinspectionReports' || $eachModel=='DmiChangeLabInspectionReports' || $eachModel=='DmiChemistFinalReports'
                || $eachModel=='DmiECodeSiteinspectionReports' || $eachModel=='DmiLaboratorySiteinspectionReports' || $eachModel=='DmiPrintingSiteinspectionReports' || $eachModel=='DmiRenewalSiteinspectionReports'
                || $eachModel=='DmiSiteinspectionLaboratoryDetails' || $eachModel=='DmiSiteinspectionOtherDetails' || $eachModel=='DmiSiteinspectionPremisesDetails' || $eachModel=='DmiSiteinspectionPremisesProfiles'){

                    foreach($getRecords as $eachRecord){
                
                        $record_id = $eachRecord['id'];
                        if(base64_decode($eachRecord['user_email_id'],true)==true){
                            $user_email_id = base64_decode($eachRecord['user_email_id']);
                            if(!empty($user_email_id)){
                                $this->$eachModel->updateAll(array('user_email_id'=>"$user_email_id"),array('id'=>$record_id));
                            }
                        }

                        if(base64_decode($eachRecord['referred_back_by_email'],true)==true){
                            $referred_back_by_email = base64_decode($eachRecord['referred_back_by_email']);
                            if(!empty($referred_back_by_email)){
                                $this->$eachModel->updateAll(array('referred_back_by_email'=>"$referred_back_by_email"),array('id'=>$record_id));
                            }
                        }
                            
                    }
            
            }elseif($eachModel=='DmiApplTransferLogs' || $eachModel=='DmiWorkTransferLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    if(base64_decode($eachRecord['by_user'],true)==true){
                        $by_user = base64_decode($eachRecord['by_user']);
                        if(!empty($by_user)){
                            $this->$eachModel->updateAll(array('by_user'=>"$by_user"),array('id'=>$record_id));
                        }
                    }
                    if(base64_decode($eachRecord['from_user'],true)==true){
                        $from_user = base64_decode($eachRecord['from_user']);
                        if(!empty($from_user)){
                            $this->$eachModel->updateAll(array('from_user'=>"$from_user"),array('id'=>$record_id));
                        }
                    }
                    if(base64_decode($eachRecord['to_user'],true)==true){
                        $to_user = base64_decode($eachRecord['to_user']);
                        if(!empty($to_user)){
                            $this->$eachModel->updateAll(array('to_user'=>"$to_user"),array('id'=>$record_id));
                        }
                    }

                }

            }elseif($eachModel=='DmiChangeFirms' || $eachModel=='DmiChemistProfileDetails'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['email'],true)==true){
                        $email = base64_decode($eachRecord['email']);
                        if(!empty($email)){
                            $this->$eachModel->updateAll(array('email'=>"$email"),array('id'=>$record_id));
                        }
                    }
   
                }
            
            }elseif($eachModel=='DmiCustomerFirmProfiles' || $eachModel=='DmiPrintingFirmProfiles'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['user_email_id'],true)==true){
                        $user_email_id = base64_decode($eachRecord['user_email_id']);
                        if(!empty($user_email_id)){
                            $this->$eachModel->updateAll(array('user_email_id'=>"$user_email_id"),array('id'=>$record_id));
                        }
                    }
                    if(base64_decode($eachRecord['firm_email_id'],true)==true){
                        $firm_email_id = base64_decode($eachRecord['firm_email_id']);
                        if(!empty($firm_email_id)){
                            $this->$eachModel->updateAll(array('firm_email_id'=>"$firm_email_id"),array('id'=>$record_id));
                        }
                    }

                }
            
            }elseif($eachModel=='DmiDatesExtensionsLogs' || $eachModel=='DmiDivisionGrades' || $eachModel=='DmiEducationTypes' || $eachModel=='DmiRejectedApplLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['by_user'],true)==true){
                        $by_user = base64_decode($eachRecord['by_user']);
                        if(!empty($by_user)){
                            $this->$eachModel->updateAll(array('by_user'=>"$by_user"),array('id'=>$record_id));
                        }
                    }

                }
            
            }elseif($eachModel=='DmiLoginStatuses' || $eachModel=='DmiUsersResetpassKeys' || $eachModel=='DmiUserActionLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    if(base64_decode($eachRecord['user_id'],true)==true){
                        $user_id = base64_decode($eachRecord['user_id']);
                        if(!empty($user_id)){
                            $this->$eachModel->updateAll(array('user_id'=>"$user_id"),array('id'=>$record_id));
                        }
                    }

                }
            
            }elseif($eachModel=='DmiReplicaChargesDetails' || $eachModel=='DmiReplicaChargesDetailsLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    if(base64_decode($eachRecord['user'],true)==true){
                        $user = base64_decode($eachRecord['user']);
                        if(!empty($user)){
                            $this->$eachModel->updateAll(array('user'=>"$user"),array('id'=>$record_id));
                        }
                    }
                }
            
            }elseif($eachModel=='DmiUsers' || $eachModel=='DmiUserHistoryLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['created_by_user'],true)==true){
                        $created_by_user = base64_decode($eachRecord['created_by_user']);
                        if(!empty($created_by_user)){
                            $this->$eachModel->updateAll(array('created_by_user'=>"$created_by_user"),array('id'=>$record_id));
                        }
                    }

                }
            
            }elseif($eachModel=='DmiChemistComments'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['comment_by'],true)==true){
                        $comment_by = base64_decode($eachRecord['comment_by']);
                        if(!empty($comment_by)){
                            $this->$eachModel->updateAll(array('comment_by'=>"$comment_by"),array('id'=>$record_id));
                        }
                    }
                    if(base64_decode($eachRecord['comment_to'],true)==true){
                        $comment_to = base64_decode($eachRecord['comment_to']);
                        if(!empty($comment_to)){
                            $this->$eachModel->updateAll(array('comment_to'=>"$comment_to"),array('id'=>$record_id));
                        }
                    }
                    if(base64_decode($eachRecord['reply_by'],true)==true){
                        $reply_by = base64_decode($eachRecord['reply_by']);
                        if(!empty($reply_by)){
                            $this->$eachModel->updateAll(array('reply_by'=>"$reply_by"),array('id'=>$record_id));
                        }
                    }
                    if(base64_decode($eachRecord['reply_to'],true)==true){
                        $reply_to = base64_decode($eachRecord['reply_to']);
                        if(!empty($reply_to)){
                            $this->$eachModel->updateAll(array('reply_to'=>"$reply_to"),array('id'=>$record_id));
                        }
                    }

                }
            }elseif($eachModel=='DmiEsignRequestResponseLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['request_by_user_id'],true)==true){
                        $request_by_user_id = base64_decode($eachRecord['request_by_user_id']);
                        if(!empty($request_by_user_id)){
                            $this->$eachModel->updateAll(array('request_by_user_id'=>"$request_by_user_id"),array('id'=>$record_id));
                        }
                    }

                }
            }elseif($eachModel=='DmiIoAllocationLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['io_email_id'],true)==true){
                        $io_email_id = base64_decode($eachRecord['io_email_id']);
                        if(!empty($io_email_id)){
                            $this->$eachModel->updateAll(array('io_email_id'=>"$io_email_id"),array('id'=>$record_id));
                        }
                    }

                }
            }elseif($eachModel=='DmiMoAllocationLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['mo_email_id'],true)==true){
                        $mo_email_id = base64_decode($eachRecord['mo_email_id']);
                        if(!empty($mo_email_id)){
                            $this->$eachModel->updateAll(array('mo_email_id'=>"$mo_email_id"),array('id'=>$record_id));
                        }
                    }
                }
            }elseif($eachModel=='DmiOldCertDateUpdateLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['updated_by'],true)==true){
                        $updated_by = base64_decode($eachRecord['updated_by']);
                        if(!empty($updated_by)){
                            $this->$eachModel->updateAll(array('updated_by'=>"$updated_by"),array('id'=>$record_id));
                        }
                    }

                }
            }elseif($eachModel=='DmiReEsignGrantLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['re_esigned_by'],true)==true){
                        $re_esigned_by = base64_decode($eachRecord['re_esigned_by']);
                        if(!empty($re_esigned_by)){
                            $this->$eachModel->updateAll(array('re_esigned_by'=>"$re_esigned_by"),array('id'=>$record_id));
                        }
                    }

                }
            }elseif($eachModel=='DmiRoAllocationLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['ro_incharge_id'],true)==true){
                        $ro_incharge_id = base64_decode($eachRecord['ro_incharge_id']);
                        if(!empty($ro_incharge_id)){
                            $this->$eachModel->updateAll(array('ro_incharge_id'=>"$ro_incharge_id"),array('id'=>$record_id));
                        }
                    }

                }
            }elseif($eachModel=='DmiRoOffices'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['ro_email_id'],true)==true){
                        $ro_email_id = base64_decode($eachRecord['ro_email_id']);
                        if(!empty($ro_email_id)){
                            $this->$eachModel->updateAll(array('ro_email_id'=>"$ro_email_id"),array('id'=>$record_id));
                        }
                    }

                    if(base64_decode($eachRecord['user_email_id'],true)==true){
                        $user_email_id = base64_decode($eachRecord['user_email_id']);
                        if(!empty($user_email_id)){
                            $this->$eachModel->updateAll(array('user_email_id'=>"$user_email_id"),array('id'=>$record_id));
                        }
                    }

                }
            }elseif($eachModel=='DmiSentEmailLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];
                    $splitEmails = explode(',',$eachRecord['destination_list']);
                    
                    $destination_list = '';
                    foreach($splitEmails as $eachEmail){

                        if(base64_decode($eachEmail,true)==true){
                            $idVar = base64_decode($eachEmail);
                            $destination_list .= $idVar.',';
                        }

                    }

                    $UpdateValueArray = array('destination_list'=>"$destination_list");

                    if(!empty($destination_list)){
                        $this->$eachModel->updateAll($UpdateValueArray,array('id'=>$record_id));
                    }
                    
                }
            }elseif($eachModel=='DmiTempEsignStatuses'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['esigning_user'],true)==true){
                        $esigning_user = base64_decode($eachRecord['esigning_user']);
                        if(!empty($esigning_user)){
                            $this->$eachModel->updateAll(array('esigning_user'=>"$esigning_user"),array('id'=>$record_id));
                        }
                    }

                }
            }elseif($eachModel=='DmiUserLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['email_id'],true)==true){
                        $email_id = base64_decode($eachRecord['email_id']);
                        if(!empty($email_id)){
                            $this->$eachModel->updateAll(array('email_id'=>"$email_id"),array('id'=>$record_id));
                        }
                    }

                }
            }elseif($eachModel=='DmiUserRolesManagmentLogs'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['by_user'],true)==true){
                        $by_user = base64_decode($eachRecord['by_user']);
                        if(!empty($by_user)){
                            $this->$eachModel->updateAll(array('by_user'=>"$by_user"),array('id'=>$record_id));
                        }
                    }

                    if(base64_decode($eachRecord['to_user'],true)==true){
                        $to_user = base64_decode($eachRecord['to_user']);
                        if(!empty($to_user)){
                            $this->$eachModel->updateAll(array('to_user'=>"$to_user"),array('id'=>$record_id));
                        }
                    }

                }
            }elseif($eachModel=='DmiWorkTransferHoPermissions'){

                foreach($getRecords as $eachRecord){
            
                    $record_id = $eachRecord['id'];

                    if(base64_decode($eachRecord['req_by_user'],true)==true){
                        $req_by_user = base64_decode($eachRecord['req_by_user']);
                        if(!empty($req_by_user)){
                            $this->$eachModel->updateAll(array('req_by_user'=>"$req_by_user"),array('id'=>$record_id));
                        }
                    }

                    if(base64_decode($eachRecord['req_for_user'],true)==true){
                        $req_for_user = base64_decode($eachRecord['req_for_user']);
                        if(!empty($req_for_user)){
                            $this->$eachModel->updateAll(array('req_for_user'=>"$req_for_user"),array('id'=>$record_id));
                        }
                    }

                }
            }
        }

    }*/
	
	//for temp to update lg code of states and districts
	/*public function updateLgCode(){
		
		$this->viewBuilder()->setLayout('form_layout');
		
		if ($this->request->is('post')) {
			
			$a = '';//string was '1@13,2@33' first is id and second is lg_code
			$spilt_a = explode(',',$a);
				
				$this->loadModel('DmiDistricts');
				foreach($spilt_a as $each){
					
					$split_lg = explode('@',$each);
					$id = $split_lg[0];
					$lg_code = $split_lg[1];
					//$this->DmiDistricts->updateAll(array('lg_code'=>"$lg_code"),array('id'=>$id));
				}
		}
		
	}*/
	
	
	//for temp use to apply indexing to tables for better performance
	/*public function tableIndexingzz(){
		
		$this->autoRender = false;		
		$this->loadModel('DmiFlowWiseTablesLists');
		$conn = ConnectionManager::get('default');
		
		//For DMI tables
		$flowWisetables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('id IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();
        $i=0;
        foreach($flowWisetables as $eachFlow){

			$finalSubmitTable = $eachFlow['application_form'];
			$finalReportTable = $eachFlow['inspection_report'];
            $hoAllocationTable = $eachFlow['ho_level_allocation'];
            $amaApprovedTable = $eachFlow['ama_approved_application'];
            $hoCommentsTable = $eachFlow['ho_comment_reply'];
            $allocationTable = $eachFlow['allocation'];
            $moCommentsTable = $eachFlow['commenting_with_mo'];
			$esignStatusTable = $eachFlow['esign_status'];
			$paymentStatusTable = $eachFlow['payment'];
            $currentPositionTable = $eachFlow['appl_current_pos'];
			$applPdfTable = $eachFlow['app_pdf_record'];
			$reportPdfTable = $eachFlow['inspection_pdf_record'];
            $rosocommentTable = $eachFlow['ro_so_comments'];
            $grantTable = $eachFlow['grant_pdf'];
            $level4ROApproved = $eachFlow['level_4_ro_approved'];
			
			

				
				$finalSubmitTable = $this->getTableLocator()->get($finalSubmitTable)->getTable();//to get table name from model name
				if(!empty($finalSubmitTable)){
					
					$conn->execute("CREATE INDEX ind_finsub_customer_id_$i ON $finalSubmitTable (customer_id);");
					$conn->execute("CREATE INDEX ind_finsub_status_$i ON $finalSubmitTable (status);");
					$conn->execute("CREATE INDEX ind_finsub_current_level_$i ON $finalSubmitTable (current_level);");
				}
			
			
				$finalReportTable = $this->getTableLocator()->get($finalReportTable)->getTable();//to get table name from model name
				if(!empty($finalReportTable)){
					
					$conn->execute("CREATE INDEX ind_finrep_customer_id_$i ON $finalReportTable (customer_id);");
					$conn->execute("CREATE INDEX ind_finrep_status_$i ON $finalReportTable (status);");
					$conn->execute("CREATE INDEX ind_finrep_current_level_$i ON $finalReportTable (current_level);");
				}
			
			
				$hoAllocationTable = $this->getTableLocator()->get($hoAllocationTable)->getTable();//to get table name from model name
				if(!empty($hoAllocationTable)){
					
					$conn->execute("CREATE INDEX ind_hoAlloc_customer_id_$i ON $hoAllocationTable (customer_id);");
					$conn->execute("CREATE INDEX ind_hoAlloc_dyama_$i ON $hoAllocationTable (dy_ama);");
					$conn->execute("CREATE INDEX ind_hoAlloc_jtama_$i ON $hoAllocationTable (jt_ama);");
					$conn->execute("CREATE INDEX ind_hoAlloc_ama_$i ON $hoAllocationTable (ama);");
					$conn->execute("CREATE INDEX ind_hoAlloc_current_level_$i ON $hoAllocationTable (current_level);");
				}
				
				$amaApprovedTable = $this->getTableLocator()->get($amaApprovedTable)->getTable();//to get table name from model name
				if(!empty($amaApprovedTable)){
					
					$conn->execute("CREATE INDEX ind_amaAppr_customer_id_$i ON $amaApprovedTable (customer_id);");
					
					if($i != 3){
						$conn->execute("CREATE INDEX ind_amaAppr_user_email_id_$i ON $amaApprovedTable (user_email_id);");
					}

				}
				
				
				$hoCommentsTable = $this->getTableLocator()->get($hoCommentsTable)->getTable();//to get table name from model name
				if(!empty($hoCommentsTable)){
					
					$conn->execute("CREATE INDEX ind_hoComm_customer_id_$i ON $hoCommentsTable (customer_id);");
					$conn->execute("CREATE INDEX ind_hoComm_from_user_$i ON $hoCommentsTable (from_user);");
					$conn->execute("CREATE INDEX ind_hoComm_to_user_$i ON $hoCommentsTable (to_user);");

				}
				
				$allocationTable = $this->getTableLocator()->get($allocationTable)->getTable();//to get table name from model name
				if(!empty($allocationTable)){
					
					$conn->execute("CREATE INDEX ind_alloc_customer_id_$i ON $allocationTable (customer_id);");
					$conn->execute("CREATE INDEX ind_alloc_level_1_$i ON $allocationTable (level_1);");
					$conn->execute("CREATE INDEX ind_alloc_level_2_$i ON $allocationTable (level_2);");
					$conn->execute("CREATE INDEX ind_alloc_level_3_$i ON $allocationTable (level_3);");
					$conn->execute("CREATE INDEX ind_alloc_current_level_$i ON $allocationTable (current_level);");

				}
				
				$moCommentsTable = $this->getTableLocator()->get($moCommentsTable)->getTable();//to get table name from model name
				if(!empty($moCommentsTable)){
					
					$conn->execute("CREATE INDEX ind_moComm_customer_id_$i ON $moCommentsTable (customer_id);");
					$conn->execute("CREATE INDEX ind_moComm_comment_by_$i ON $moCommentsTable (comment_by);");
					$conn->execute("CREATE INDEX ind_moComm_comment_to_$i ON $moCommentsTable (comment_to);");

				}
				
				$esignStatusTable = $this->getTableLocator()->get($esignStatusTable)->getTable();//to get table name from model name
				if(!empty($esignStatusTable)){
					
					$conn->execute("CREATE INDEX ind_esignSta_customer_id_$i ON $esignStatusTable (customer_id);");

				}
				
				$paymentStatusTable = $this->getTableLocator()->get($paymentStatusTable)->getTable();//to get table name from model name
				if(!empty($paymentStatusTable)){
					
					$conn->execute("CREATE INDEX ind_paysta_customer_id_$i ON $paymentStatusTable (customer_id);");
					$conn->execute("CREATE INDEX ind_paysta_confirm_$i ON $paymentStatusTable (payment_confirmation);");

				}
				
				$currentPositionTable = $this->getTableLocator()->get($currentPositionTable)->getTable();//to get table name from model name
				if(!empty($currentPositionTable)){
					
					$conn->execute("CREATE INDEX ind_curpos_customer_id_$i ON $currentPositionTable (customer_id);");
					$conn->execute("CREATE INDEX ind_curpos_current_level_$i ON $currentPositionTable (current_level);");
					$conn->execute("CREATE INDEX ind_curpos_user_email_id_$i ON $currentPositionTable (current_user_email_id);");

				}
				
				$applPdfTable = $this->getTableLocator()->get($applPdfTable)->getTable();//to get table name from model name
				if(!empty($applPdfTable)){
					
					$conn->execute("CREATE INDEX ind_apppdf_customer_id_$i ON $applPdfTable (customer_id);");
					$conn->execute("CREATE INDEX ind_apppdf_$i ON $applPdfTable (pdf_version);");

				}

				$reportPdfTable = $this->getTableLocator()->get($reportPdfTable)->getTable();//to get table name from model name
				if(!empty($reportPdfTable)){
					
					$conn->execute("CREATE INDEX ind_reppdf_customer_id_$i ON $reportPdfTable (customer_id);");
					
					if($i != 1) {
						$conn->execute("CREATE INDEX ind_reppdf_$i ON $reportPdfTable (pdf_version);");
					}

				}
				
				
				$rosocommentTable = $this->getTableLocator()->get($rosocommentTable)->getTable();//to get table name from model name
				if(!empty($rosocommentTable)){
					
					$conn->execute("CREATE INDEX ind_roso_customer_id_$i ON $rosocommentTable (customer_id);");
					$conn->execute("CREATE INDEX ind_roso_from_user_$i ON $rosocommentTable (from_user);");
					$conn->execute("CREATE INDEX ind_roso_to_user_$i ON $rosocommentTable (to_user);");

				}
				
				$grantTable = $this->getTableLocator()->get($grantTable)->getTable();//to get table name from model name
				if(!empty($grantTable)){
					
					$conn->execute("CREATE INDEX ind_grant_customer_id_$i ON $grantTable (customer_id);");
					$conn->execute("CREATE INDEX ind_grant_user_email_id_$i ON $grantTable (user_email_id);");

				}
				
				$level4ROApproved = $this->getTableLocator()->get($level4ROApproved)->getTable();//to get table name from model name
				if(!empty($level4ROApproved)){
					
					$conn->execute("CREATE INDEX ind_roappr_customer_id_$i ON $level4ROApproved (customer_id);");
					$conn->execute("CREATE INDEX ind_roappr_user_email_id_$i ON $level4ROApproved (user_email_id);");

				}
			
			$i=$i+1;
		}
		
		$firmsTable = $this->getTableLocator()->get('DmiFirms')->getTable();//to get table name from model name
		if(!empty($firmsTable)){
			
			$conn->execute("CREATE INDEX ind_firm_id ON $firmsTable (customer_id);");
			$conn->execute("CREATE INDEX ind_cert_type ON $firmsTable (certification_type);");
			$conn->execute("CREATE INDEX ind_already_granted ON $firmsTable (is_already_granted);");

		}
		
		//For LIMS tables
			
			$conn->execute("CREATE INDEX ind_org_sample_code ON actual_test_data (org_sample_code);");
			$conn->execute("CREATE INDEX ind_sample_code ON actual_test_data (sample_code);");
			$conn->execute("CREATE INDEX ind_chemist_code ON actual_test_data (chemist_code);");
			$conn->execute("CREATE INDEX ind_lab_code ON actual_test_data (lab_code);");
			
			$conn->execute("CREATE INDEX ind_org_sample_code_1 ON code_decode (org_sample_code);");
			$conn->execute("CREATE INDEX ind_sample_code_1 ON code_decode (sample_code);");
			$conn->execute("CREATE INDEX ind_chemist_code_1 ON code_decode (chemist_code);");
			$conn->execute("CREATE INDEX ind_lab_code_1 ON code_decode (lab_code);");
			
		//	$conn->execute("CREATE INDEX ind_commodity_code ON commodity_test (commodity_code);"); //already has primary key index
		//	$conn->execute("CREATE INDEX ind_test_code ON commodity_test (test_code);"); //already has primary key index
			
			$conn->execute("CREATE INDEX ind_category_code ON comm_grade (category_code);");
			$conn->execute("CREATE INDEX ind_commodity_code ON comm_grade (commodity_code);");
			$conn->execute("CREATE INDEX ind_test_code ON comm_grade (test_code);");
			$conn->execute("CREATE INDEX ind_method_code ON comm_grade (method_code);");
			$conn->execute("CREATE INDEX ind_grade_code ON comm_grade (grade_code);");
			
			$conn->execute("CREATE INDEX ind_category_code_1 ON final_test_result (category_code);");
			$conn->execute("CREATE INDEX ind_commodity_code_1 ON final_test_result (commodity_code);");
			$conn->execute("CREATE INDEX ind_test_code_1 ON final_test_result (test_code);");
			$conn->execute("CREATE INDEX ind_sample_code_2 ON final_test_result (sample_code);");
			$conn->execute("CREATE INDEX ind_org_sample_code_2 ON final_test_result (org_sample_code);");
			
			$conn->execute("CREATE INDEX ind_lab_id ON lims_lab_nabl_comm_test_details (lab_id);");
			$conn->execute("CREATE INDEX ind_commodity ON lims_lab_nabl_comm_test_details (commodity);");
			
			$conn->execute("CREATE INDEX ind_sample_code_3 ON lims_sample_payment_details (sample_code);");
			$conn->execute("CREATE INDEX ind_payment_confirmation ON lims_sample_payment_details (payment_confirmation);");
			
			//$conn->execute("CREATE INDEX ind_category_code ON m_commodity (category_code);"); //already has primary key index
			//$conn->execute("CREATE INDEX ind_commodity_code ON m_commodity (commodity_code);"); //already has primary key index
			
			$conn->execute("CREATE INDEX ind_m_sample_obs_code ON m_commodity_obs (m_sample_obs_code);");
			$conn->execute("CREATE INDEX ind_commodity_code_2 ON m_commodity_obs (commodity_code);");
			$conn->execute("CREATE INDEX ind_category_code_2 ON m_commodity_obs (category_code);");
			
			$conn->execute("CREATE INDEX ind_lab_code_2 ON m_sample_allocate (lab_code);");
			$conn->execute("CREATE INDEX ind_commodity_code_3 ON m_sample_allocate (commodity_code);");
			$conn->execute("CREATE INDEX ind_category_code_3 ON m_sample_allocate (category_code);");
			$conn->execute("CREATE INDEX ind_sample_code_4 ON m_sample_allocate (sample_code);");
			$conn->execute("CREATE INDEX ind_org_sample_code_3 ON m_sample_allocate (org_sample_code);");
			$conn->execute("CREATE INDEX ind_chemist_code_2 ON m_sample_allocate (chemist_code);");
			
			$conn->execute("CREATE INDEX ind_stage_sample_code ON m_sample_reg_obs (stage_sample_code);");
			$conn->execute("CREATE INDEX ind_commodity_code_4 ON m_sample_reg_obs (commodity_code);");
			$conn->execute("CREATE INDEX ind_category_code_4 ON m_sample_reg_obs (category_code);");
			$conn->execute("CREATE INDEX ind_m_sample_obs_code_1 ON m_sample_reg_obs (m_sample_obs_code);");
			$conn->execute("CREATE INDEX ind_m_sample_obs_type_code ON m_sample_reg_obs (m_sample_obs_type_code);");

		//	$conn->execute("CREATE INDEX ind_stage_sample_code ON sample_inward (stage_sample_code);"); //already has primary key index
			$conn->execute("CREATE INDEX ind_commodity_code_5 ON sample_inward (commodity_code);");
			$conn->execute("CREATE INDEX ind_category_code_5 ON sample_inward (category_code);");
		//	$conn->execute("CREATE INDEX ind_loc_id ON sample_inward (loc_id);"); //already has primary key index
			$conn->execute("CREATE INDEX ind_org_sample_code_4 ON sample_inward (org_sample_code);");

		//	$conn->execute("CREATE INDEX ind_inward_id ON sample_inward_details (inward_id);"); //already has primary key index
		//	$conn->execute("CREATE INDEX ind_loc_id ON sample_inward_details (loc_id);"); //already has primary key index
		//	$conn->execute("CREATE INDEX ind_org_sample_code_5 ON sample_inward_details (org_sample_code);"); //already has primary key index
			
			$conn->execute("CREATE INDEX ind_test_code_2 ON test_fields (test_code);");
			$conn->execute("CREATE INDEX ind_field_code ON test_fields (field_code);");
			$conn->execute("CREATE INDEX ind_field_value ON test_fields (field_value);");
			
			$conn->execute("CREATE INDEX ind_test_code_3 ON test_formula (test_code);");
			$conn->execute("CREATE INDEX ind_method_code_1 ON test_formula (method_code);");
			
			$conn->execute("CREATE INDEX ind_org_sample_code_6 ON workflow (org_sample_code);");
			$conn->execute("CREATE INDEX ind_src_loc_id ON workflow (src_loc_id);");
			$conn->execute("CREATE INDEX ind_src_usr_cd ON workflow (src_usr_cd);");
			$conn->execute("CREATE INDEX ind_dst_loc_id ON workflow (dst_loc_id);");
			$conn->execute("CREATE INDEX ind_dst_usr_cd ON workflow (dst_usr_cd);");
			$conn->execute("CREATE INDEX ind_stage_smpl_cd ON workflow (stage_smpl_cd);");


	}*/
	
	
	
	/*public function tableIndexingSecond(){
		
		$this->autoRender = false;		
		$conn = ConnectionManager::get('default');
		
		$conn->execute("CREATE INDEX ind_cust_id_11 ON dmi15_digit_allotment_details (customer_id);");
		$conn->execute("CREATE INDEX ind_ca_unique_no_11 ON dmi15_digit_allotment_details (ca_unique_no);");
		$conn->execute("CREATE INDEX ind_commodity_11 ON dmi15_digit_allotment_details (commodity);");
		
		$conn->execute("CREATE INDEX ind_customer_id_12 ON dmi15_digit_siteinspection_reports (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_12 ON dmi15_digit_siteinspection_reports (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_12 ON dmi15_digit_siteinspection_reports (form_status);");
		
		$conn->execute("CREATE INDEX ind_customer_id_13 ON dmi_adv_payment_details (customer_id);");
		$conn->execute("CREATE INDEX ind_pay_conf_13 ON dmi_adv_payment_details (payment_confirmation);");
		
		$conn->execute("CREATE INDEX ind_customer_id_14 ON dmi_adv_payment_transactions (customer_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_15 ON dmi_all_directors_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_15 ON dmi_all_directors_details (user_email_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_16 ON dmi_all_machines_details (customer_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_17 ON dmi_all_tanks_details (customer_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_18 ON dmi_all_tbls_details (customer_id);");
		
		$conn->execute("CREATE INDEX ind_applicant_id_19 ON dmi_applicants_resetpass_keys (applicant_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_20 ON dmi_appl_with_ro_mappings (customer_id);");
		$conn->execute("CREATE INDEX ind_office_id_20 ON dmi_appl_with_ro_mappings (office_id);");
		
		$conn->execute("CREATE INDEX ind_primary_id_21 ON dmi_auth_firm_registrations (primary_id);");
		$conn->execute("CREATE INDEX ind_firm_id_21 ON dmi_auth_firm_registrations (firm_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_21 ON dmi_auth_firm_registrations (user_email_id);");
		
		$conn->execute("CREATE INDEX ind_primary_id_22 ON dmi_auth_primary_registrations (primary_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_22 ON dmi_auth_primary_registrations (user_email_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_23 ON dmi_ca_domestic_renewal_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_23 ON dmi_ca_domestic_renewal_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_23 ON dmi_ca_domestic_renewal_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_23 ON dmi_ca_domestic_renewal_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_24 ON dmi_ca_export_siteinspection_reports (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_24 ON dmi_ca_export_siteinspection_reports (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_24 ON dmi_ca_export_siteinspection_reports (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_24 ON dmi_ca_export_siteinspection_reports (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_25 ON dmi_ca_renewal_commodity_details (customer_id);");
		$conn->execute("CREATE INDEX ind_commodity_name_25 ON dmi_ca_renewal_commodity_details (commodity_name);");
		$conn->execute("CREATE INDEX ind_is_latest_25 ON dmi_ca_renewal_commodity_details (is_latest);");
		
		$conn->execute("CREATE INDEX ind_customer_id_26 ON dmi_change_directors_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_26 ON dmi_change_directors_details (user_email_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_27 ON dmi_change_firms (customer_id);");
		$conn->execute("CREATE INDEX ind_customer_primary_id_27 ON dmi_change_firms (customer_primary_id);");
		$conn->execute("CREATE INDEX ind_certification_type_27 ON dmi_change_firms (certification_type);");
		$conn->execute("CREATE INDEX ind_commodity_27 ON dmi_change_firms (commodity);");
		
		$conn->execute("CREATE INDEX ind_customer_id_28 ON dmi_change_lab_chemists_details (customer_id);");
		$conn->execute("CREATE INDEX ind_commodity_28 ON dmi_change_lab_chemists_details (commodity);");
		$conn->execute("CREATE INDEX ind_user_email_id_28 ON dmi_change_lab_chemists_details (user_email_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_29 ON dmi_change_lab_firm_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_29 ON dmi_change_lab_firm_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_29 ON dmi_change_lab_firm_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_29 ON dmi_change_lab_firm_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_30 ON dmi_change_lab_inspection_reports (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_30 ON dmi_change_lab_inspection_reports (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_30 ON dmi_change_lab_inspection_reports (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_30 ON dmi_change_lab_inspection_reports (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_31 ON dmi_change_lab_other_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_31 ON dmi_change_lab_other_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_31 ON dmi_change_lab_other_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_31 ON dmi_change_lab_other_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_32 ON dmi_change_selected_fields (customer_id);");
		$conn->execute("CREATE INDEX ind_payment_32 ON dmi_change_selected_fields (payment);");
		
		$conn->execute("CREATE INDEX ind_user_id_33 ON dmi_chemists_resetpass_keys (user_id);");
		$conn->execute("CREATE INDEX ind_status_33 ON dmi_chemists_resetpass_keys (status);");
		
		$conn->execute("CREATE INDEX ind_chemist_id_34 ON dmi_chemist_allotments (chemist_id);");
		$conn->execute("CREATE INDEX ind_customer_id_34 ON dmi_chemist_allotments (customer_id);");
		$conn->execute("CREATE INDEX ind_created_by_34 ON dmi_chemist_allotments (created_by);");
		
		$conn->execute("CREATE INDEX ind_form_status_35 ON dmi_chemist_education_details (form_status);");
		$conn->execute("CREATE INDEX ind_customer_id_35 ON dmi_chemist_education_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_35 ON dmi_chemist_education_details (user_email_id);");
		
		$conn->execute("CREATE INDEX ind_form_status_36 ON dmi_chemist_experience_details (form_status);");
		$conn->execute("CREATE INDEX ind_customer_id_36 ON dmi_chemist_experience_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_36 ON dmi_chemist_experience_details (user_email_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_37 ON dmi_chemist_logs (customer_id);");
		$conn->execute("CREATE INDEX ind_ip_address_37 ON dmi_chemist_logs (ip_address);");
		
		$conn->execute("CREATE INDEX ind_form_status_40 ON dmi_chemist_other_details (form_status);");
		$conn->execute("CREATE INDEX ind_customer_id_40 ON dmi_chemist_other_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_40 ON dmi_chemist_other_details (user_email_id);");
		
		$conn->execute("CREATE INDEX ind_form_status_41 ON dmi_chemist_profile_details (form_status);");
		$conn->execute("CREATE INDEX ind_customer_id_41 ON dmi_chemist_profile_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_41 ON dmi_chemist_profile_details (user_email_id);");
		
		$conn->execute("CREATE INDEX ind_chemist_id_42 ON dmi_chemist_registrations (chemist_id);");
		$conn->execute("CREATE INDEX ind_created_by_42 ON dmi_chemist_registrations (created_by);");
		
		$conn->execute("CREATE INDEX ind_form_status_43 ON dmi_chemist_training_details (form_status);");
		$conn->execute("CREATE INDEX ind_customer_id_43 ON dmi_chemist_training_details (customer_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_44 ON dmi_commodity_packtype_update_logs (customer_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_45 ON dmi_customers (customer_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_46 ON dmi_customer_firm_profiles (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_46 ON dmi_customer_firm_profiles (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_46 ON dmi_customer_firm_profiles (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_46 ON dmi_customer_firm_profiles (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_47 ON dmi_customer_laboratory_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_47 ON dmi_customer_laboratory_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_47 ON dmi_customer_laboratory_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_47 ON dmi_customer_laboratory_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_48 ON dmi_customer_logs (customer_id);");
		$conn->execute("CREATE INDEX ind_ip_address_48 ON dmi_customer_logs (ip_address);");
		
		$conn->execute("CREATE INDEX ind_customer_id_49 ON dmi_customer_machinery_profiles (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_49 ON dmi_customer_machinery_profiles (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_49 ON dmi_customer_machinery_profiles (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_49 ON dmi_customer_machinery_profiles (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_50 ON dmi_customer_packing_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_50 ON dmi_customer_packing_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_50 ON dmi_customer_packing_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_50 ON dmi_customer_packing_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_51 ON dmi_customer_premises_profiles (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_51 ON dmi_customer_premises_profiles (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_51 ON dmi_customer_premises_profiles (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_51 ON dmi_customer_premises_profiles (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_52 ON dmi_customer_tbl_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_52 ON dmi_customer_tbl_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_52 ON dmi_customer_tbl_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_52 ON dmi_customer_tbl_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_district_name_53 ON dmi_districts (district_name);");
		$conn->execute("CREATE INDEX ind_state_id_53 ON dmi_districts (state_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_54 ON dmi_e_code_allotment_details (customer_id);");
		$conn->execute("CREATE INDEX ind_commodity_54 ON dmi_e_code_allotment_details (commodity);");
		
		$conn->execute("CREATE INDEX ind_customer_id_56 ON dmi_e_code_allotment_pdfs (customer_id);");
		$conn->execute("CREATE INDEX ind_chemist_id_56 ON dmi_e_code_allotment_pdfs (chemist_id);");
		$conn->execute("CREATE INDEX ind_pdf_version_56 ON dmi_e_code_allotment_pdfs (pdf_version);");
		
		$conn->execute("CREATE INDEX ind_customer_id_57 ON dmi15_digit_allotment_pdfs (customer_id);");
		$conn->execute("CREATE INDEX ind_chemist_id_57 ON dmi15_digit_allotment_pdfs (chemist_id);");
		$conn->execute("CREATE INDEX ind_pdf_version_57 ON dmi15_digit_allotment_pdfs (pdf_version);");
		
		$conn->execute("CREATE INDEX ind_customer_id_58 ON dmi_e_code_appl_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_58 ON dmi_e_code_appl_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_58 ON dmi_e_code_appl_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_58 ON dmi_e_code_appl_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_59 ON dmi_e_code_siteinspection_reports (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_59 ON dmi_e_code_siteinspection_reports (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_59 ON dmi_e_code_siteinspection_reports (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_59 ON dmi_e_code_siteinspection_reports (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_60 ON dmi_laboratory_chemists_details (customer_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_61 ON dmi_laboratory_firm_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_61 ON dmi_laboratory_firm_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_61 ON dmi_laboratory_firm_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_61 ON dmi_laboratory_firm_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_62 ON dmi_laboratory_other_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_62 ON dmi_laboratory_other_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_62 ON dmi_laboratory_other_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_62 ON dmi_laboratory_other_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_63 ON dmi_laboratory_renewal_other_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_63 ON dmi_laboratory_renewal_other_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_63 ON dmi_laboratory_renewal_other_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_63 ON dmi_laboratory_renewal_other_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_64 ON dmi_laboratory_siteinspection_reports (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_64 ON dmi_laboratory_siteinspection_reports (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_64 ON dmi_laboratory_siteinspection_reports (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_64 ON dmi_laboratory_siteinspection_reports (current_level);");
		
		$conn->execute("CREATE INDEX ind_user_id_65 ON dmi_login_statuses (user_id);");
		$conn->execute("CREATE INDEX ind_user_type_65 ON dmi_login_statuses (user_type);");
		$conn->execute("CREATE INDEX ind_curr_loggedin_65 ON dmi_login_statuses (curr_loggedin);");
		$conn->execute("CREATE INDEX ind_ipaddress_65 ON dmi_login_statuses (ipaddress);");
		
		$conn->execute("CREATE INDEX ind_customer_id_66 ON dmi_old_application_certificate_details (customer_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_67 ON dmi_old_application_renewal_dates (customer_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_68 ON dmi_old_cert_date_update_logs (customer_id);");
		$conn->execute("CREATE INDEX ind_updated_by_68 ON dmi_old_cert_date_update_logs (updated_by);");
		
		$conn->execute("CREATE INDEX ind_customer_id_69 ON dmi_printing_firm_profiles (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_69 ON dmi_printing_firm_profiles (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_69 ON dmi_printing_firm_profiles (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_69 ON dmi_printing_firm_profiles (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_70 ON dmi_printing_premises_profiles (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_70 ON dmi_printing_premises_profiles (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_70 ON dmi_printing_premises_profiles (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_70 ON dmi_printing_premises_profiles (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_71 ON dmi_printing_renewal_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_71 ON dmi_printing_renewal_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_71 ON dmi_printing_renewal_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_71 ON dmi_printing_renewal_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_72 ON dmi_printing_siteinspection_reports (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_72 ON dmi_printing_siteinspection_reports (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_72 ON dmi_printing_siteinspection_reports (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_72 ON dmi_printing_siteinspection_reports (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_73 ON dmi_printing_unit_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_73 ON dmi_printing_unit_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_73 ON dmi_printing_unit_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_73 ON dmi_printing_unit_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_74 ON dmi_rejected_appl_logs (customer_id);");
		
		$conn->execute("CREATE INDEX ind_customer_id_75 ON dmi_renewal_packer_details (customer_id);");
		$conn->execute("CREATE INDEX ind_packer_type_75 ON dmi_renewal_packer_details (packer_type);");
		
		$conn->execute("CREATE INDEX ind_customer_id_76 ON dmi_renewal_submission_logs (customer_id);");
		$conn->execute("CREATE INDEX ind_form_type_76 ON dmi_renewal_submission_logs (form_type);");
		
		$conn->execute("CREATE INDEX ind_customer_id_77 ON dmi_replica_allotment_details (customer_id);");
		$conn->execute("CREATE INDEX ind_commodity_77 ON dmi_replica_allotment_details (commodity);");
		
		$conn->execute("CREATE INDEX ind_customer_id_78 ON dmi_replica_allotment_pdfs (customer_id);");
		$conn->execute("CREATE INDEX ind_chemist_id_78 ON dmi_replica_allotment_pdfs (chemist_id);");
		$conn->execute("CREATE INDEX ind_pdf_version_78 ON dmi_replica_allotment_pdfs (pdf_version);");
		
		$conn->execute("CREATE INDEX ind_category_code_79 ON dmi_replica_charges_details (category_code);");
		$conn->execute("CREATE INDEX ind_commodity_code_79 ON dmi_replica_charges_details (commodity_code);");
		
		$conn->execute("CREATE INDEX ind_customer_id_80 ON dmi_re_esign_grant_logs (customer_id);");
		$conn->execute("CREATE INDEX ind_re_esigned_by_80 ON dmi_re_esign_grant_logs (re_esigned_by);");
		
		$conn->execute("CREATE INDEX ind_customer_id_81 ON dmi_siteinspection_laboratory_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_81 ON dmi_siteinspection_laboratory_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_81 ON dmi_siteinspection_laboratory_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_81 ON dmi_siteinspection_laboratory_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_82 ON dmi_siteinspection_other_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_82 ON dmi_siteinspection_other_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_82 ON dmi_siteinspection_other_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_82 ON dmi_siteinspection_other_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_83 ON dmi_siteinspection_premises_details (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_83 ON dmi_siteinspection_premises_details (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_83 ON dmi_siteinspection_premises_details (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_83 ON dmi_siteinspection_premises_details (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_84 ON dmi_siteinspection_premises_profiles (customer_id);");
		$conn->execute("CREATE INDEX ind_user_email_id_84 ON dmi_siteinspection_premises_profiles (user_email_id);");
		$conn->execute("CREATE INDEX ind_form_status_84 ON dmi_siteinspection_premises_profiles (form_status);");
		$conn->execute("CREATE INDEX ind_current_level_84 ON dmi_siteinspection_premises_profiles (current_level);");
		
		$conn->execute("CREATE INDEX ind_customer_id_85 ON dmi_sponsored_printing_firms (customer_id);");
		$conn->execute("CREATE INDEX ind_sponsored_ca_85 ON dmi_sponsored_printing_firms (sponsored_ca);");
		
		$conn->execute("CREATE INDEX ind_customer_id_86 ON dmi_update_firm_details (customer_id);");
		$conn->execute("CREATE INDEX ind_sponsored_ca_86 ON dmi_update_firm_details (update_by);");
		
		$conn->execute("CREATE INDEX ind_email_87 ON dmi_users (email);");
		$conn->execute("CREATE INDEX ind_posted_ro_office_87 ON dmi_users (posted_ro_office);");
		
		$conn->execute("CREATE INDEX ind_user_id_88 ON dmi_users_resetpass_keys (user_id);");
		$conn->execute("CREATE INDEX ind_status_88 ON dmi_users_resetpass_keys (status);");
		
		$conn->execute("CREATE INDEX ind_email_id_89 ON dmi_user_logs (email_id);");
		$conn->execute("CREATE INDEX ind_ip_address_89 ON dmi_user_logs (ip_address);");
		
		$conn->execute("CREATE INDEX ind_user_email_id_90 ON dmi_user_roles (user_email_id);");	
		
		$conn->execute("CREATE INDEX ind_customer_id_91 ON dmi_work_transfer_logs (customer_id);");
	
	}*/
	
	
	//temp function to update districts under SO offices, to set ro_id and so_id
	/*public function updateDistrictsForSO(){
		
		$this->autoRender = false;
		$this->loadModel('DmiRoOffices');
		//get SO offices list
		$getSoOffices = $this->DmiRoOffices->find('all',array('fields'=>array('id','ro_id_for_so'),'conditions'=>array('office_type'=>'SO','OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order'=>'id'))->toArray();
		
		foreach($getSoOffices as $eachSo){
			
			$office_id = $eachSo['id'];
			$ro_id = $eachSo['ro_id_for_so'];
			
			//check districts comes under SO offices
			$this->loadModel('DmiDistricts');
			$getDistricts = $this->DmiDistricts->find('all',array('fields'=>'id','conditions'=>array('ro_id'=>$office_id,'delete_status IS NULL')))->toArray();
		
			//update each district
			foreach($getDistricts as $eachDistrict){
				
				$distId = $eachDistrict['id'];//print($office_id.'@'.$ro_id.' ');
				$this->DmiDistricts->updateAll(array('ro_id'=>"$ro_id",'so_id'=>"$office_id"),array('id'=>$distId));
			
			}
		}
	
	}*/


    //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>    
    // created by shankhpal shende for list of documents list on 09/08/2022
    public function documentCheckList()
    {
        $this->viewBuilder()->setLayout('document_check_list_layout');
        $this->loadModel('DmiDocCheckLists');
        
        $form_type = ['CA Non Bevo','CA Bevo','Printing Press','Laboratory','15 Digit Code','E code'];
        $this->set('form_type', $form_type);
        $comm_array = [];
        $i = 0;
        foreach($form_type as $eachformtype){ 
            $get_doc =  $this->DmiDocCheckLists->find('all')->select(['releted_document'])->where(['form_type'=>$eachformtype])->order('releted_document ASC')->toArray();
            $doc_array[$i] = $get_doc;
            $i++;
        }
        $this->set('doc_array', $doc_array);
       
    }

    public function showCommodities()
    {
        $this->viewBuilder()->setLayout('document_check_list_layout');
        $this->loadModel('MCommodityCategory');
        $this->loadModel('MCommodity');
        $conn = ConnectionManager::get('default');
        
         
        
        $commodity_cat =  $this->MCommodityCategory->find('all')->select(['category_code','category_name'])->where(['display' => 'Y'])->order('category_name ASC')->toArray();
        $this->set('commodity_cat', $commodity_cat);
        
        $comm_array = [];
        $i = 0;
        foreach($commodity_cat as $eachcat){
             $get_comm =  $this->MCommodity->find('all')->select(['commodity_name'])->where(['category_code'=>$eachcat['category_code'],'display' => 'Y'])->order('commodity_name ASC')->toArray();
             $comm_array[$i] = $get_comm;
             $i++;
        }
        
        $this->set('comm_array', $comm_array);
		
	}



    // This function added by Shankhpal shende 
    // on date 24/08/2022
    // for Attach PP/LAB

    public function attachePpLab() {

       //load modal 
        $this->loadModel('DmiFirms');
        $this->loadModel('DmiReplicaAllotmentDetails');
        $this->loadModel('DmiCaPpLabMapings');
        $this->loadModel('DmiGrantCertificatesPdfs');
        //Set the blank variables for the Displaying messages
        $message = '';
        $message_theme = '';
        $redirect_to = '';

        $this->viewBuilder()->setLayout('secondary_customer');
        $customer_id = $this->Session->read('username');
    
        //list of authorized laboratory commented by shankhpal shende on 29/08/2022
		//$lab_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/3/'.'%','delete_status IS Null'),'order'=>'firm_name asc'))->toArray();

       //list of authorized laboratory
        $lab_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','joins'=>array(array('table' => 'dmi_grant_certificates_pdfs','alias' => 'dmigcp','type' => 'INNER','conditions' => array('dmigcp.customer_id = DmiFirms.customer_id'))),
		'conditions'=>array('Dmifirms.customer_id like'=>'%'.'/3/'.'%','delete_status IS NULL'),'order'=>array('Dmifirms.firm_name asc')))->toArray();

        $this->set('lab_list',$lab_list);
        
        //list of authorized printers commented by shankhpal shende on 29/08/2022
        //$printers_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/2/'.'%','delete_status IS Null'),'order'=>'firm_name asc'))->toArray();
       
        
        $printers_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','joins'=>array(array('table' => 'dmi_grant_certificates_pdfs','alias' => 'dmigcp','type' => 'INNER','conditions' => array('dmigcp.customer_id = DmiFirms.customer_id'))),
		'conditions'=>array('Dmifirms.customer_id like'=>'%'.'/2/'.'%','delete_status IS NULL'),'order'=>array('Dmifirms.firm_name asc')))->toArray();
        $this->set('printers_list',$printers_list);

        $attached_list =  $this->DmiCaPpLabMapings->find('all')->select(['customer_id','pp_id','lab_id','map_type'])->where(array('customer_id IS' => $customer_id))->toArray();
        
        $this->set('attached_list',$attached_list);

      
        $resultArr =  $this->DmiCaPpLabMapings->find('list')->where(array('customer_id IS' => $customer_id))->toList();
        $this->set('resultArr',$resultArr);
       
        //this array is used for display printing press and laboratory on view
        $result = [];
        $i = 0;
        foreach($attached_list as $eachlist){
        
            $result[$i]['type'] = $eachlist['map_type'];
            if(!empty($eachlist['pp_id'])){
                $result[$i]['p_name'] = $printers_list[$eachlist['pp_id']];
            }
            if(!empty($eachlist['lab_id'])){
                $result[$i]['l_name'] = $lab_list[$eachlist['lab_id']];
            }
            
            $i++;
        }
               
        $this->set('result',$result);

        //fetch last reocrds from table, if empty set default value
        $dataArray = $this->DmiReplicaAllotmentDetails->getSectionData($customer_id);
			
        //to show selected lab in list
        if (!empty($dataArray)) {
            
            $selected_lab = $dataArray[0]['grading_lab'];
            $selected_PP = $dataArray[0]['authorized_printer'];
            
        } else {
            $selected_lab = '';
        }

        $this->set('selected_lab',$selected_lab);
        $this->set('selected_PP',$selected_PP);
        $this->set('dataArray',$dataArray);
        
        //to save post data
        if (null!==($this->request->getData('save'))) {
            
            $postData = $this->request->getData();
            
            $customer_id = $this->Session->read('username');
        
            $pp_id = $this->request->getData('pp_id');
            $lab_id = $this->request->getData('lab_id');
            $maptype = $this->request->getData('maptype');
        
            $get_record_pp =  $this->DmiCaPpLabMapings->find('all')->where(array('customer_id IS' => $customer_id,'pp_id IS' => $pp_id))->first();
        
            $get_record_lab =  $this->DmiCaPpLabMapings->find('all')->where(array('customer_id IS' => $customer_id,'map_type IN' => $maptype))->first();
                
            // for validation to insert data if maptype is pp or lab
            if(!empty($maptype)) {
                    
                // to check insert data with selected pp_id if pp_id is exists then record not add
                if(isset($get_record_pp['pp_id'])){
                    
                    $message = 'Printing Press alredy Attached with you';
                    $message_theme = 'failed';
                    $redirect_to = 'attache_pp_lab';
                
                } elseif(!empty($get_record_lab && $get_record_pp )) { //if lab is already exists then this condition stop adding new lab
               
                    $message = 'Packer can attach only one laboratory.';
                    $message_theme = 'failed';
                    $redirect_to = 'attache_pp_lab';

                } else {
                
                    $DmiCaPpLabMapings = $this->DmiCaPpLabMapings->newEntity(array(

                        'customer_id'=>$customer_id,
                        'pp_id'=>$pp_id,
                        'lab_id'=>$lab_id,
                        'map_type'=> $maptype,
                        'created'=>date('Y-m-d H:i:s'),
                        'modified'=>date('Y-m-d H:i:s'),
                    ));
                        
                    if ($this->DmiCaPpLabMapings->save($DmiCaPpLabMapings) && $maptype == 'pp' ) {
                        
                        $message = 'Printing Press Attached successfully';
                        $message_theme = 'success';
                        $redirect_to = 'attache_pp_lab';
                    }

                    if ($this->DmiCaPpLabMapings->save($DmiCaPpLabMapings) && $maptype == 'lab' ) {
                        
                        $message = 'Laboratory Attached successfully';
                        $message_theme = 'success';
                        $redirect_to = 'attache_pp_lab';
                    }
                }
        
            } else{

                $message = 'Please Select Atleast one Laboratory/Printing Press!';
                $message_theme = 'failed';
                $redirect_to = 'attache_pp_lab';
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



	// customer_information function added by Laxmi Bhadade on 18-11-22
    public function customerInformation(){
       $result = '';
        $commodity = '';
        $this->viewBuilder()->setLayout('document_check_list_layout');
         $this->loadModel('DmiFirms');
         $this->loadModel('DmiCustomers');
        $this->loadModel('DmiStates');
        $this->loadModel('DmiDistricts');
        $this->loadModel('MCommodityCategory');
        $this->loadModel('DmiGrantCertificatesPdfs');
        $this->loadModel('DmiPackingTypes');
       

        $conn = ConnectionManager::get('default');
         if( $this->request->is('post') ) {
            if($this->request->getdata('name') == 'primary'){
                $customer_id = $this->request->getdata('id');
                $primary_data = $this->DmiCustomers->find('all')->where(array('customer_id IS' => $customer_id))->first();
                if (!empty($primary_data->district )) {
                $dist = $this->DmiDistricts->find('all',array('fields'=>array('district_name','state_id')))->where(array('id IS'=>$primary_data->district , 'state_id IS'=> $primary_data->state))->first();
                $state = $this->DmiStates->find('all',array('fields'=>array('state_name')))->where(array('id IS'=> $primary_data->state , 'id IS'=> $dist->state_id))->first();
                
              }
               if ($primary_data  !=null) {
            $result= "<table class= table table-bordered >
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>District</th>
                                <th>State</th>
                              </tr>
                            </thead>
                              <tr id= custmer_data >
                                <td id= id>".$customer_id."</td>
                                <td id= name>".$primary_data->f_name." &nbsp; ".$primary_data->l_name."</td>
                                <td id= district>".$dist->district_name."</td>
                                <td id= state>".$state->state_name."</td>
                                
                              </tr>
                             
                              
                            </tbody>
                          </table>
                        ";
                   
                        echo $result;
                    }else{

                     echo $result = "<p class = 'text-danger' id = 'danger-id'>This Customer Id is not valid</p>";
                
                
                    }
                     
                 
                exit;
            }elseif($this->request->getdata('name') == 'firm'){
                 $customer_id = $this->request->getdata('id');

                  
                $firm_data = $this->DmiFirms->find('all')->where(array('customer_id IS' => $customer_id))->first();
                //if commodity is one then select packaging type
               
                 if(!empty($firm_data->commodity) && $firm_data->commodity > 1) {
                    
                      $commodity = $this->MCommodityCategory->find('all',array('fields'=>array('category_name')))->where(array('category_code IS'=> $firm_data->commodity))->first();

                            if(!empty ($commodity->category_name)) { 
                               $commodity = $commodity->category_name;
                           } else { 
                               $commodity = "Not found ";
                          }
                }else{
                    if(!empty($firm_data->packaging_materials) && $firm_data->commodity == 1){
                        $packaging =  explode(',', $firm_data->packaging_materials);
                            foreach($packaging as $packtype){
                               $pacging_type = $this->DmiPackingTypes->find('all',array('fields'=>array('packing_type')))->where(array('id IS'=> $packtype))->first();
                                  
                                
                              if(!empty($pacging_type->packing_type)){
                                  $commodity .= $pacging_type->packing_type. ",";
                              }
                              else{
                                $commodity = "Not found ";
                              }
                            } 
                         
                      }
                }

                   
                
                
                   $grant_date = $this->DmiGrantCertificatesPdfs->find('all',array('fields'=>array('date')))->where(array('customer_id IS'=> $customer_id))->last();
                       if(!empty($grant_date->date)){
                         $grant_date = $grant_date->date;
                          $uptoDate = $this->Customfunctions->getCertificateValidUptoDate($customer_id, $grant_date);
                           $date =  date('d-m-Y', strtotime("$uptoDate +1 Months"));
                     }
                   
                    if(!empty($date)){
                    $current_date = date("d-m-Y");
                    if($current_date <  $date ){
                       $status = "Inactive";
                    }else{
                        $status = "Active valid on &nbsp;" .$grant_date;
                    }
                }else{
                     $status = "Not Found";
                }
                 
                  if ($firm_data  !=null) {
              
                    $result .= "<tr><td><b>Name:</b></td><td>".$firm_data->firm_name."</td></tr>";
                    $result .= "<tr><td><b>Commodity:</b></td><td>".$commodity."</td></tr>";
                    $result .= "<tr><td><b>Status:</b></td><td>".$status."</td></tr>";
                     
                        echo $result;
                    }else{

                     echo $result = "<tr><td></td><td>Sorry, This Customer Id you have searched is not valid</td></tr>";
                
                
                    }
                exit;

            }else{
               
                $customer_id_not_valid_msg = 'Sorry, This Customer Id is not valid';

                       // $this->set('customer_id_not_valid_msg', $customer_id_not_valid_msg);
                       echo $result = "<tr><td></td><td>This Customer Id you have searched is not valid</td></tr>";
                
                          echo $result;
                        exit;
            }
      }
   }
   
   
}
?>
