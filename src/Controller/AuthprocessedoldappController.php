<?php
	namespace App\Controller;

	use Cake\Event\Event;
	use Cake\Network\Session\DatabaseSession;
	use App\Network\Email\Email;
	use App\Network\Request\Request;
	use App\Network\Response\Response;
	use Cake\ORM\TableRegistry;

	class AuthprocessedoldappController extends AppController{


		var $name = 'Authprocessedoldapp';
		
		public function initialize(): void
		{
			parent::initialize();

		}
		
		
		
		// BEFORE FILTER
		// DESCRIPTION : ----
		// @AUTHOR : ----
		// DATE : ----
		
		public function beforeFilter($event) {
			
			parent::beforeFilter($event);

			$this->viewBuilder()->setLayout('admin_dashboard');
			$this->viewBuilder()->setHelpers(['Form','Html']);

			if ($this->Session->read('username') == null) {
				
				echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot');?>users/login_user">Please Login</a><?php
				exit();
			}

			// Change layout for Ajax requests
			if ($this->request->is('ajax')) {
				$this->viewBuilder()->setLayout('ajax');
			}
		}
		
		
		
		
		// HOME
		// DESCRIPTION : To Display the Content of Primary and Secondary Firm List / Create / Edit or Delete. 
		// @AUTHOR : AMOL CHOUDHARI / PRAVIN BHAKARE
		// #CONTRIBUTER : AKASH THAKRE (M)
		// DATE : ------
		
		public function home() {

			$this->loadModel('DmiUserRoles');
			$this->loadModel('DmiAuthPrimaryRegistrations');
			$this->loadModel('DmiAuthFirmRegistrations');
			$this->loadModel('DmiCustomers');
			$this->loadModel('DmiFirms');
			$this->loadModel('DmiFinalSubmits');
			$this->loadModel('DmiCertificateTypes');
			$this->loadModel('DmiDistricts');
			
			$this->Session->write('fromauth','yes');
			//check if user have role
			$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('old_appln_data_entry'=>'yes','user_email_id IS'=>$this->Session->read('username'))))->first();
			
			if (empty($user_access)) {
				echo "Sorry.. You don't have permission to view this page";
				exit();
			}
			
			$primary_reg_list = array();
			$firms_list = array();
			
			//get all primary registration done by current Auth user
			$auth_primary_ids = $this->DmiAuthPrimaryRegistrations->find('list',array('valueField'=>'primary_id','conditions'=>array('user_email_id IS'=>$this->Session->read('username'))))->toList();
			
			if (!empty($auth_primary_ids)) {

				$primary_reg_list = $this->DmiCustomers->find('all',array('conditions'=>array('customer_id IN'=>$auth_primary_ids),'order'=>'id Desc'))->toArray();
				$authfirms_list = $this->DmiAuthFirmRegistrations->find('list',array('valueField'=>array('firm_id'),'keyField'=>array('id'),'conditions'=>array('primary_id IN'=>$auth_primary_ids)))->toArray();
				
				if (!empty($authfirms_list)) {
					$firms_list = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IN'=>$authfirms_list),'order'=>'id Desc'))->toArray();
				}
			}

			//to check if the firm if final submit
			$i=0;
			$firm_final_submited = array();
			
			foreach ($firms_list as $each) {

				$firm_final_submited[$i] = $this->DmiFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$each['customer_id'])))->first();
				$i=$i+1;
			}

			$certificate_type = $this->DmiCertificateTypes->find('list',array('valueField'=>'certificate_type','conditions'=>array()))->toArray();
			$district_list = $this->DmiDistricts->find('list',array('valueField'=>'district_name','conditions'=>array('delete_status IS NULL')))->toArray();
			
			$this->set(compact('primary_reg_list','firms_list','district_list','certificate_type','firm_final_submited'));

		}
	
	

		// REGISTER CUSTOMER
		// DESCRIPTION : To Create the primary firm for the Auth Old Processed Application.
		// @AUTHOR : AMOL CHOUDHARI / PRAVIN BHAKARE
		// #CONTRIBUTER : AKASH THAKRE (M)
		// DATE : ------
		
		public function registerCustomer() {

			$this->loadModel('DmiStates');
			$this->loadModel('DmiUserRoles');
			$this->loadModel('DmiDistricts');
			$this->loadModel('DmiDocumentLists');
			$this->loadModel('DmiCustomers');
			$this->loadModel('DmiCustomersHistoryLogs');
			$this->loadModel('DmiSmsEmailTemplates');
			$this->loadModel('DmiAuthPrimaryRegistrations');


			//check if user have role
			$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('old_appln_data_entry'=>'yes','user_email_id IS'=>$this->Session->read('username'))))->first();
			if (empty($user_access)) {
				echo "Sorry.. You don't have permission to view this page";
				exit();
			}


			$states = $this->DmiStates->find('list', array('valueField'=>'state_name','conditions'=>array('OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order'=>array('state_name')))->toArray();
			$this->set('states',$states);

			$districts = $this->DmiDistricts->find('list', array('valueField'=>'district_name', 'conditions'=>array('state_id'=>1, 'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->toArray();
			$this->set('districts',$districts);

			$document_lists = $this->DmiDocumentLists->find('list',array('valueField'=>'document_name', 'conditions'=>array('delete_status IS NULL')))->toArray(); //change the query on the 09-08-2022 by Akash
			$this->set('document_lists',$document_lists);

			// set variables to show popup messages from view file
			$message = '';
			$message_theme = '';
			$redirect_to = '';

			if ($this->request->is('post')) {

				//applied condition to check all post data for !empty validation on server side
				if (!empty($this->request->getData('f_name')) && !empty($this->request->getData('l_name')) && !empty($this->request->getData('street_address')) &&
					!empty($this->request->getData('state')) && !empty($this->request->getData('district')) && !empty($this->request->getData('postal_code')) &&
					!empty($this->request->getData('email')) && !empty($this->request->getData('mobile')) /*&& !empty($this->request->getData('landline'))*/ &&
					!empty($this->request->getData('document')) && !empty($this->request->getData('file')->getClientFilename())) 
				{

					if (!$this->Customfunctions->validateUniquePostData($this->request->getData('mobile'),'mobile')== 1) {
						$this->set('return_error_msg','Please enter proper Mobile no.');
						return false;
						exit;
					}

					if (!$this->Customfunctions->validateUniquePostData($this->request->getData('email'),'email')== 1) {
						$this->set('return_error_msg','Please enter proper Email ID.');
						return false;
						exit;
					}


					$usersData = $this->request->getData();
					
					$Checkemailexist = $this->DmiCustomers->find('all', array('fields' => 'email', 'conditions' => array('email IS' => base64_encode($usersData['email']))))->first();


					if ($Checkemailexist == null) {

						$last_record_id_query  = $this->DmiCustomers->find('all', array('fields'=>'id','order'=>array('id desc')))->first();

						$last_record_id = $last_record_id_query['id'];

						$last_customer_id_query = $this->DmiCustomers->find('all', array('fields'=>'customer_id', 'conditions'=>array('id IS'=>$last_record_id)))->first();

						if (!empty($last_customer_id_query )) {

							$last_customer_id = $last_customer_id_query['customer_id'];

							$split = explode('/',$last_customer_id);

							$splited_id = $split[0];

						} else {

							$splited_id = 100;
						}


						$new_customer_id = ($splited_id + 1).'/'.date('Y');


						if (!empty($this->request->getData('file')->getClientFilename())) {

							$file_name = $this->request->getData('file')->getClientFilename();;
							$file_size = $this->request->getData('file')->getSize();
							$file_type = $this->request->getData('file')->getClientMediaType();
							$file_local_path = $this->request->getData('file')->getStream()->getMetadata('uri');

							$uploadedfile = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

							if (!empty($uploadedfile)) {

								//html encoding start
								$htmlencodedfname = htmlentities($this->request->getData('f_name'), ENT_QUOTES);
								$htmlencodedmname = htmlentities($this->request->getData('m_name'), ENT_QUOTES);
								$htmlencodedlname = htmlentities($this->request->getData('l_name'), ENT_QUOTES);
								$htmlencodedstreetaddress = htmlentities($this->request->getData('street_address'), ENT_QUOTES);
								$htmlencodedpostalcode = htmlentities($this->request->getData('postal_code'), ENT_QUOTES);
								$htmlencodedemail = base64_encode(htmlentities($this->request->getData('email'), ENT_QUOTES));//for email encoding
								$htmlencodedmobile = htmlentities($this->request->getData('mobile'), ENT_QUOTES);
								$htmlencodedlandline = htmlentities($this->request->getData('landline'), ENT_QUOTES);


								//Proper dropdown option selected checking start for document
								$table = 'DmiDocumentLists';
								$post_input_request = $this->request->getData('document');
								$document = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function


								//for state
								$table = 'DmiStates';
								$post_input_request = $this->request->getData('state');
								$state = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function


								//for district
								$table = 'DmiDistricts';
								$post_input_request = $this->request->getData('district');
								$district = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function

								//SAVING THE DATA
								$DmiCustomersEntity = $this->DmiCustomers->newEntity(array(

									'customer_id'=>$new_customer_id,
									'f_name'=>$htmlencodedfname,
									'm_name'=>$htmlencodedmname,
									'l_name'=>$htmlencodedlname,
									'street_address'=>$htmlencodedstreetaddress,
									'state'=>$state,
									'district'=>$district,
									'postal_code'=>$htmlencodedpostalcode,
									'email'=>$htmlencodedemail,
									'password'=>'91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc',//Agmark123@
								//	'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2',//123
									'mobile'=>base64_encode($htmlencodedmobile),
									'landline'=>base64_encode($htmlencodedlandline),
									'document'=>$document,
									'file'=>$uploadedfile,
									'created'=>date('Y-m-d H:i:s'),
									'modified'=>date('Y-m-d H:i:s')
								));

								if ($this->DmiCustomers->save($DmiCustomersEntity)) {

									// Save the primary customer profile update logs history (Done by pravin 13/02/2018)
									$DmiCustomersHistoryLogsEntity = $this->DmiCustomersHistoryLogs->newEntity(array(

										'customer_id'=>$new_customer_id,
										'f_name'=>$htmlencodedfname,
										'm_name'=>$htmlencodedmname,
										'l_name'=>$htmlencodedlname,
										'street_address'=>$htmlencodedstreetaddress,
										'state'=>$state,
										'district'=>$district,
										'postal_code'=>$htmlencodedpostalcode,
										'email'=>$htmlencodedemail,
										'password'=>'91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc',//Agmark123@
									//	'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2',//123
										'mobile'=>base64_encode($htmlencodedmobile),
										'landline'=>base64_encode($htmlencodedlandline),
										'document'=>$document,
										'file'=>$uploadedfile,
										'created'=>date('Y-m-d H:i:s'),
										'modified'=>date('Y-m-d H:i:s')
									));

									$this->DmiCustomersHistoryLogs->save($DmiCustomersHistoryLogsEntity);

									//save record in Auth primary registration table
									$DmiAuthPrimaryRegistrationsEntity = $this->DmiAuthPrimaryRegistrations->newEntity(array(

										'primary_id'=>$new_customer_id,
										'user_email_id'=>$this->Session->read('username'),
										'user_once_no'=>$this->Session->read('once_card_no'),
										'created'=>date('Y-m-d H:i:s'),
										'modified'=>date('Y-m-d H:i:s')
									));

									$this->DmiAuthPrimaryRegistrations->save($DmiAuthPrimaryRegistrationsEntity);
									
									//$this->DmiSmsEmailTemplates->sendMessage(1, $new_customer_id);
									
									$message = 'You have Successfully Created New Primary Id.';
									$message_theme = 'success';
									$redirect_to = 'home';
									
								} else {

									$message = 'Your details are not saved please check again';
									$message_theme = 'failed';
									$redirect_to = 'register_customer';
								}
							}
						
						} else {

							$this->set('return_error_msg','File Not Selected !');	
							return null;
							exit;
						}

					} else {

						/*$message = 'This email id is already registered with us. Please register with another email id. Thankyou.';
						$redirect_to = 'register_customer';
						$this->view = '/Elements/message_boxes';*/
						//above code commented & below code added on 14-07-2018 by pravin
						$return_error_msg = 'This email id is already registered with us. Please register with another email id.';
						$this->set('return_error_msg',$return_error_msg );
						return null;
						exit;
					}

				} else {
					
					$this->set('return_error_msg','Please check some fields are not entered !');	
					return null;
					exit;
				}
			}

			// set variables to show popup messages from view file
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('return_error_msg',null);
			$this->set('redirect_to',$redirect_to);

		}


	
		// FETCH PRIMARY ID
		// DESCRIPTION : To fetch  the primary firm id (table) for edit , delete and view the form of that perticular ID.
		// @AUTHOR : AMOL CHOUDHARI / PRAVIN BHAKARE
		// #CONTRIBUTER : ----
		// DATE : ------
		
		public function fetchPrimaryId($id) {
			
			$this->loadModel('DmiCustomers');
			$primary_details = $this->DmiCustomers->find('all',array('conditions'=>array('id IS'=>$id)))->first();
			$this->Session->write('primary_id',$primary_details['customer_id']);
			$this->redirect('/authprocessedoldapp/primary_profile');
		}

		
		

		// PRIMARY PROFILE
		// DESCRIPTION : this method is used to create/edit the primary firm for backlog data entry / old application.
		// @AUTHOR : AMOL CHOUDHARI
		// @CONTRIBUTER : PRAVIN BHAKARE , AKASH THAKRE
		// DATE : 09-03-2022
		
		public function primaryProfile() {

			$this->loadModel('DmiPermittedOnceUpdations');
			$this->loadModel('DmiCustomers');
			$this->loadModel('DmiStates');
			$this->loadModel('DmiDistricts');
			$this->loadModel('DmiDocumentLists');
			$this->loadModel('DmiCustomersHistoryLogs');
			$this->loadModel('DmiSmsEmailTemplates');

			$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('old_appln_data_entry'=>'yes','user_email_id'=>$this->Session->read('username'))))->first();
			if (empty($user_access)) {
				echo "Sorry.. You don't have permission to view this page";
				exit();
			}


			// set variables to show popup messages from view file
			$message = '';
			$message_theme = '';
			$redirect_to = '';

			$customer_id = $this->Session->read('primary_id');

			//get details from aadhar updation permission table to make aadhar & mobile field available to change applied on 03-02-2018 by Amol
			$once_update_permission = $this->DmiPermittedOnceUpdations->find('all',array('conditions'=>array('primary_applicant_id IS'=>$customer_id),'order'=>'id desc'))->first();

			$aadhar_change_status = null;
			if (!empty($once_update_permission)) {

				//$get_last_change_status = $this->DmiPermittedOnceUpdations->find('all',array('conditions'=>array('id'=>max($once_update_permission))))->first();
				$aadhar_change_status = $once_update_permission['change_status'];
			}
			
			$this->set('aadhar_change_status',$aadhar_change_status);

			$customer_data = $this->DmiCustomers->find('all', array('conditions'=>array('customer_id IS'=>$customer_id)))->first();

			//get personal details masked by custom function to show in secure mode applied on 12-10-2017 by Amol
			$customer_data['mobile'] = $this->Customfunctions->getMaskedValue(base64_decode($customer_data['mobile']),'mobile'); //This is added on 27-04-2021 for base64 encoding by AKASH
			$customer_data['email'] = $this->Customfunctions->getMaskedValue(base64_decode($customer_data['email']),'email');

			$this->set('customer_data',$customer_data);


			//added on 01-06-2017 by Amol to decrypt aadhar number before showing on frontend
			$this->loadComponent('Authentication');
			$decrypted_aadhar = $this->Authentication->decrypt($customer_data['once_card_no']);

			$decrypted_aadhar = $this->Customfunctions->getMaskedValue($decrypted_aadhar,'aadhar');//applied on 12-10-2017 by Amol
			$this->set('decrypted_aadhar',$decrypted_aadhar);

			// Apply "Order by" clause to get state list by order wise (Done By Pravin 10-01-2018)
			$states = $this->DmiStates->find('list', array('valueField'=>'state_name','conditions'=>array('OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order'=>array('state_name')))->toArray();
			$this->set('states',$states);

			/*error solve on district value by pravin 15/4/2017*/
			$districts = $this->DmiDistricts->find('list', array('valueField'=>'district_name', 'conditions'=>array('state_id IS'=>$customer_data['state'])))->toArray();
			$this->set('districts',$districts);

			$document_lists = $this->DmiDocumentLists->find('list',array('valueField'=>'document_name', 'conditions'=>array('delete_status IS NULL')))->toArray(); // Changed the query on the 09-08-2022 by Akash
			$this->set('document_lists',$document_lists);


			$selected_states_query = $this->DmiStates->find('all', array('fields'=>'id', 'conditions'=>array('id IS'=>$customer_data['state'])))->first();
			$selected_states_value = $selected_states_query['id'];
			$this->set('selected_states_value',$selected_states_value);


			$selected_districts_query = $this->DmiDistricts->find('all', array('fields'=>'id', 'conditions'=>array('id IS'=>$customer_data['district'])))->first();
			$selected_districts_value = $selected_districts_query['id'];
			$this->set('selected_districts_value',$selected_districts_value);


			$selected_document_lists_query = $this->DmiDocumentLists->find('all', array('fields'=>'id', 'conditions'=>array('id IS'=>$customer_data['document'])))->first();
			$selected_document_lists_value = $selected_document_lists_query['id'];
			$this->set('selected_document_lists_value',$selected_document_lists_value);



			if (null!== ($this->request->getData('back'))) {
				$this->redirect(array('controller'=>'customers','action'=>'primary_home'));
			} elseif (null!== ($this->request->getData('update'))) {

				//check email already exist to avoid duplicates //applied on 19-06-2018
				$Checkemailexist =  $this->DmiCustomers->find('all', array('fields' => 'email', 'conditions' => array('email IS' => $this->request->getData('email'),'customer_id !='=>$customer_id )))->first();
					
				if ($Checkemailexist == null) {

					//applied condition to check all post data for !empty validation on server side on 21/10/2017 by Amol
					if (!empty($this->request->getData('f_name')) && !empty($this->request->getData('l_name')) && !empty($this->request->getData('street_address')) &&
						!empty($this->request->getData('state')) && !empty($this->request->getData('district')) && !empty($this->request->getData('postal_code')) &&
						!empty($this->request->getData('email')) && !empty($this->request->getData('mobile')) /*&& !empty($this->request->getData('landline'))*/ &&
						!empty($this->request->getData('document')))
					{

						if (!$this->Customfunctions->validateUniquePostData($this->request->getData('mobile'),'mobile')== 1) {
							
							$this->set('return_error_msg','Please enter proper Mobile no');	
							return null;
							exit;
						}
							
						if (!$this->Customfunctions->validateUniquePostData($this->request->getData('email'),'email')== 1) {
							
							$this->set('return_error_msg','Please enter proper Email id');
							return null;
							exit;
						}


						if ($this->request->getData('file')->getClientFilename() != null) {
							
							$file_name = $this->request->getData('file')->getClientFilename();
							$file_size = $this->request->getData('file')->getSize();
							$file_type = $this->request->getData('file')->getClientMediaType();
							$file_local_path = $this->request->getData('file')->getStream()->getMetadata('uri');
							$uploadedfile = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

						} else {
					
							$uploadedfile = $customer_data['file'];
						}

						//html encoding start
						$htmlencodedfname = htmlentities($this->request->getData('f_name'), ENT_QUOTES);
						$htmlencodedmname = htmlentities($this->request->getData('m_name'), ENT_QUOTES);
						$htmlencodedlname = htmlentities($this->request->getData('l_name'), ENT_QUOTES);
						$htmlencodedstreetaddress = htmlentities($this->request->getData('street_address'), ENT_QUOTES);
						$htmlencodedpostalcode = htmlentities($this->request->getData('postal_code'), ENT_QUOTES);
						$htmlencodedemail = base64_encode(htmlentities($this->request->getData('email'), ENT_QUOTES)); //for email encoding
						$htmlencodedmobile = htmlentities(base64_encode($this->request->getData('mobile')), ENT_QUOTES);
						$htmlencodedlandline = htmlentities(base64_encode($this->request->getData('landline')), ENT_QUOTES);
						//$htmlencodedaadhar = htmlentities($this->request->getData('once_card_no'), ENT_QUOTES);
						
						///////////////////////////////////////////////////////////////
						//html encoding end											 //		
						//added on 01-06-2017 by Amol								 //
						//to encrypt aadhar number before storing to DB and Session  //
						//$encrypted_aadhar = $this->encrypt($htmlencodedaadhar);	 //		
						///////////////////////////////////////////////////////////////
						
						//Proper dropdown option selected checking start//

						//for business_type
						$table = 'DmiDocumentLists';
						$post_input_request = $this->request->getData('document');
						$document = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function


						//for state
						$table = 'DmiStates';
						$post_input_request = $this->request->getData('state');
						$state = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function


						//for district
						$table = 'DmiDistricts';
						$post_input_request = $this->request->getData('district');
						$district = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function

						//Proper dropdown option selected checking end//
						//fetching id to update//

						$fetch_id_query = $this->DmiCustomers->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
						$fetch_id = $fetch_id_query['id'];



						//below query & conditions added on 12-10-2017 by Amol
						//To check if mobile,aadhar & email post in proper format, if not then save old value itself from DB

						if (preg_match("/^[X-X]{6}[0-9]{4}$/i", $this->request->getData('mobile'),$matches)==1) {
							
							$htmlencodedmobile = $fetch_id_query['mobile'];
						}
						
						//for email encoding
						$email_masked_value = $this->Customfunctions->getEmailMasked(base64_decode($fetch_id_query['email']));//called custom function
						
						if ($email_masked_value == $this->request->getData('email')) {

							$htmlencodedemail = $fetch_id_query['email'];
						}


						// updating data to start
						$DmiCustomersEntity = $this->DmiCustomers->newEntity(array(

							'id'=>$fetch_id,
							'f_name'=>$htmlencodedfname,
							'm_name'=>$htmlencodedmname,
							'l_name'=>$htmlencodedlname,
							'street_address'=>$htmlencodedstreetaddress,
							'state'=>$state,
							'district'=>$district,
							'postal_code'=>$htmlencodedpostalcode,
							'email'=>$htmlencodedemail,
							'mobile'=>$htmlencodedmobile,
							'landline'=>$htmlencodedlandline,
							//'once_card_no'=>$encrypted_aadhar,
							'document'=>$document,
							'file'=>$uploadedfile,
							'modified'=>date('Y-m-d H:i:s')
							
							// Saving data to end

						));
						
						if ($this->DmiCustomers->save($DmiCustomersEntity)) {

							// Save the primary customer profile update logs history (Done by pravin 13/02/2018)
							$DmiCustomersHistoryLogsEntity = $this->DmiCustomersHistoryLogs->newEntity(array(
								'customer_id'=>$customer_id,
								'f_name'=>$htmlencodedfname,
								'm_name'=>$htmlencodedmname,
								'l_name'=>$htmlencodedlname,
								'street_address'=>$htmlencodedstreetaddress,
								'state'=>$state,
								'district'=>$district,
								'postal_code'=>$htmlencodedpostalcode,
								'email'=>$htmlencodedemail,
								'password'=>$customer_data['password'],
								'mobile'=>$htmlencodedmobile,
								'landline'=>$htmlencodedlandline,
								'document'=>$document,
								'file'=>$uploadedfile,
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s')
							));

							$this->DmiCustomersHistoryLogs->save($DmiCustomersHistoryLogsEntity);

							//update aadhar permission table to done from 'in_progress' in entry exist
							if (!empty($once_update_permission)) {

								if ($aadhar_change_status=='in_progress') {
									
									$DmiPermittedOnceUpdationsEntity = $this->DmiPermittedOnceUpdations->newEntity(array(

										'id'=>$get_last_change_status['id'],
										'change_status'=>'done',
										'modified'=>date('Y-m-d H:i:s')
									));
									
									$this->DmiPermittedOnceUpdations->save($DmiPermittedOnceUpdationsEntity);
								}
							}



							//added on 22-08-2017 by Pravin to send SMS/Email
							//call custom function from Model with message id
							$this->loadModel('DmiSmsEmailTemplates');
							//$this->DmiSmsEmailTemplates->sendMessage(2,$customer_id);
						
							//Added this call to save the user action log on 09-03-2022
							$this->Customfunctions->saveActionPoint('Firm Edit (Auth)','Success');
							$message = 'Your details are updated successfully';
							$message_theme = "success";
							$redirect_to = 'primary_profile';
							
						} else {
							
							//Added this call to save the user action log on 09-03-2022
							$this->Customfunctions->saveActionPoint('Firm Edit (Auth)','Failed');
							$message = 'Your details are not saved please check again';
							$message_theme = 'failed';
							$redirect_to = 'customer_profile';
						}
					
					} else {
						
						$this->set('return_error_msg','Please check some fields are not entered');
						return null;
						exit;
					}

				} else {

					//Added this call to save the user action log on 09-03-2022
					$this->Customfunctions->saveActionPoint('Firm Edit (Auth)','Failed');
					$message = 'This email id is already exist. Please provide another email id to update. Thankyou.';
					$message_theme = 'failed';
					$redirect_to = 'primary_profile';
				}
			}

			// set variables to show popup messages from view file
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('return_error_msg',null);
			$this->set('redirect_to',$redirect_to);

		}



		// ADD FIRM
		// DESCRIPTION : this method is used to Add Firm from the primary firm for backlog data entry / old application.
		// @AUTHOR : ---
		// @CONTRIBUTER : ---
		// DATE : ---
		
		public function addFirm() {

			$this->loadModel('MCommodityCategory');
			$this->loadModel('DmiCertificateTypes');
			$this->loadModel('DmiPackingTypes');
			$this->loadModel('DmiStates');
			$this->loadModel('DmiDistricts');
			$this->loadModel('DmiFirms');
			$this->loadModel('DmiRoOffices');
			$this->loadModel('DmiUserRoles');
			$this->loadModel('DmiOldApplicationCertificateDetails');
			$this->loadModel('DmiOldApplicationRenewalDates');
			$this->loadModel('DmiSmsEmailTemplates');
			$this->loadModel('DmiFirmHistoryLogs');
			$this->loadModel('DmiAuthPrimaryRegistrations');
			$this->loadModel('DmiAuthFirmRegistrations');

			$this->loadComponent('Customfunctions');

			$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('old_appln_data_entry'=>'yes','user_email_id IS'=>$this->Session->read('username'))))->first();
			
			if (empty($user_access)) {
				echo "Sorry.. You don't have permission to view this page";
				exit();
			}

			$primary_id_list = $this->DmiAuthPrimaryRegistrations->find('list',array('keyField'=>'primary_id','valueField'=>'primary_id','conditions'=>array('user_email_id IS'=>$this->Session->read('username'))))->toArray();

			$commodity_categories = $this->MCommodityCategory->find('list',array('valueField'=>'category_name','conditions'=>array('display'=>'Y')))->toArray();

			$certificate_type = $this->DmiCertificateTypes->find('list',array('valueField'=>'certificate_type','conditions'=>array()))->toArray();

			$packaging_materials = $this->DmiPackingTypes->find('list',array('valueField'=>'packing_type','conditions'=>array('OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->toArray();

			$states = $this->DmiStates->find('list', array('valueField'=>'state_name','conditions'=>array('OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order'=>array('state_name')))->toArray();

			$districts = $this->DmiDistricts->find('list', array('valueField'=>'district_name', 'conditions'=>array('state_id'=>1,'OR'=>array('delete_status'=>null,'delete_status'=>'no'))));

			//set all above values
			$this->set(compact('primary_id_list','commodity_categories','certificate_type','packaging_materials','states','districts'));



			// get list of sponsored CA , Done by Pravin Bhakare 18-10-2020
			$sponsored_ca_list = $this->DmiFirms->find();
			//$concatCaName = $sponsored_ca_list->func()->concat(['firm_name'=>'identifier','(','customer_id'=>'identifier',')']); 
			$sponsored_ca_list->select(['customer_id','firm_name'=>$sponsored_ca_list->func()->concat(['firm_name'=>'identifier','(','customer_id'=>'identifier',')'])]);
			$sponsored_ca_list->where(['delete_status IS NULL','certification_type'=>'1']);
			$sponsored_cas = $sponsored_ca_list->all()->combine('customer_id', 'firm_name')->toArray();
			$this->set('sponsored_cas',$sponsored_cas);


			// set variables to show popup messages from view file
			$message = '';
			$message_theme = '';
			$redirect_to = '';


			if (null!== ($this->request->getData('save'))) {
				
				//this check added on 19-06-2018 to avoid duplicate email id.
				$Checkemailexist =  $this->DmiFirms->find('all', array('fields' => 'email', 'conditions' => array('email IS' => $this->request->getData('email'))))->first();
				
				if ($Checkemailexist == null) {

					//applied condition to check all post data for !empty validation on server side
					if (!empty($this->request->getData('primary_id')) &&!empty($this->request->getData('certification_type')) && !empty($this->request->getData('is_already_granted')) &&
						!empty($this->request->getData('firm_name')) && !empty($this->request->getData('email')) && !empty($this->request->getData('mobile_no')) &&
						!empty($this->request->getData('total_charge')) && !empty($this->request->getData('street_address')) && !empty($this->request->getData('state')) &&
						!empty($this->request->getData('district')) && !empty($this->request->getData('postal_code'))) 
					{

						//this conditions are for empty commodity & packaging_materials check
						if ($this->request->getData('certification_type')==1 || $this->request->getData('certification_type')==3) {

							if (!empty($this->request->getData('commodity')) && !empty($this->request->getData('selected_commodity'))) {
								//do nothing
							} else {

								$this->set('return_error_msg','No Commodities Selected, Please select min. 1 commodity');	
								return null;
								exit;
							}

						} elseif ($this->request->getData('certification_type')==2) {

							if (empty($this->request->getData('packaging_materials'))) {

								$this->set('return_error_msg','No Packaging Material Selected, Please select min. 1 commodity');
								return null;
								exit;
							}
						}

						if (!$this->Customfunctions->validateUniquePostData($this->request->getData('mobile_no'),'mobile')== 1) {

							$this->set('return_error_msg','Please enter proper Mobile no.');
							return null;
							exit;
						}

						if (!$this->Customfunctions->validateUniquePostData($this->request->getData('email'),'email')== 1) {
							
							$this->set('return_error_msg','Please enter proper Email id');	
							return null;
							exit;
						}



						if ($this->Session->read('username')!= '') {

							//Creating Customer secondary id by fetching primary id.

							$customer_primary_id 		= 	$this->request->getData('primary_id');

							$split_primary_id			= 	explode('/',$customer_primary_id);

							$splited_primary_id_value	= 	$split_primary_id[0];

							$certificate_type_id 		= 	$this->request->getData('certification_type');

							$district_short_name_query	= 	$this->DmiDistricts->find('all',array('conditions'=>array('id IS'=>$this->request->getData('district'),'OR'=>array('delete_status iS NULL','delete_status'=>'no'))))->first();

							//new condition added on 21-04-2020 by Amol
							if (!empty($district_short_name_query['so_id'])) {

								$district_office = $district_short_name_query['so_id'];

							} else {

								$district_office = $district_short_name_query['ro_id'];
							}

							$district_ro_id 			= 	$district_office;

							$ro_short_code_query		=	$this->DmiRoOffices->find('all',array('fields'=>'short_code', 'conditions'=>array('id IS'=>$district_ro_id,'OR'=>array('delete_status iS NULL','delete_status'=>'no'))))->first();

							$district_short_name		=	$ro_short_code_query['short_code'];



							$max_id = $this->DmiFirms->find('list', array('valueField'=>'id', 'conditions'=>array('customer_primary_id IS'=>$customer_primary_id)))->toList();

							if (!empty($max_id)) {
								
								$max_customer_id = $this->DmiFirms->find('all', array('fields'=>'customer_id', 'conditions'=>array('id'=>max($max_id))))->first();

								//$fetch_last_secondary_id_query 	= 	$this->Dmi_firm->find('first', array('fields'=>'MAX(customer_id) as customer_id', 'conditions'=>array('customer_primary_id'=>$customer_primary_id)));

								$fetch_last_secondary_id 		=	$max_customer_id['customer_id'];

								$split_secondary_id				= 	explode('/',$fetch_last_secondary_id);

								$splited_secondary_id_value		= 	$split_secondary_id[3];
							
							} else {

								$splited_secondary_id_value = 0;
							}

							$new_secondary_id_value		=	sprintf('%03d', $splited_secondary_id_value + 1);

							$customer_secondary_id 		= 	$splited_primary_id_value .'/'.$certificate_type_id.'/'.$district_short_name.'/'.$new_secondary_id_value;




							//if certification type is printing press the no commodity

							$split_new_generated_id = explode('/',$customer_secondary_id);

							if ($split_new_generated_id[1] != 2) {

								// Calculate total charges for selected sub commodities
								$selected_commodity = $this->request->getData('selected_commodity');
								$commodity_value = $this->request->getData('commodity');
								$sub_commodities_values = implode(',',$selected_commodity);
								$packaging_materials_values = null;

							} else {

								//$commodity_value = $this->request->getData('commodity');
								$commodity_value = 1;
								$sub_commodities_values = $this->request->getData('selected_commodity');
								$packaging_materials = $this->request->getData('packaging_materials');
								$packaging_materials_values = implode(',',$packaging_materials);

								//$total_charges = '10000'; // currently default 10000 for all.

							}


							//to check to string contain first character ',', then remove that ',' added on 22-11-2017 by Amol
							if (substr($sub_commodities_values, 0, 1) === ',') {

								$sub_commodities_values = ltrim($sub_commodities_values, ',');
							}

							if (substr($packaging_materials_values, 0, 1) === ',') {

								$packaging_materials_values = ltrim($packaging_materials_values, ',');
							}


							//html encoding
							$htmlencoded_firm_name = htmlentities($this->request->getData('firm_name'), ENT_QUOTES);
							$htmlencoded_street_address = htmlentities($this->request->getData('street_address'), ENT_QUOTES);
							$htmlencoded_postal_code = htmlentities($this->request->getData('postal_code'), ENT_QUOTES);
							//$htmlencoded_aadhar_no = htmlentities($this->request->getData('once_card_no'), ENT_QUOTES); //commented  on 23-03-2018 to avoid mandatory for aadhar
							$htmlencoded_email = base64_encode(htmlentities($this->request->getData('email'), ENT_QUOTES));
							$htmlencoded_mobile_no = htmlentities($this->request->getData('mobile_no'), ENT_QUOTES);
							$htmlencoded_fax_no = htmlentities($this->request->getData('fax_no'), ENT_QUOTES);
							$htmlencoded_other_packaging_details = htmlentities($this->request->getData('other_packaging_details'), ENT_QUOTES);

							$total_charges = htmlentities($this->request->getData('total_charge'), ENT_QUOTES);

							//to encrypt aadhar number before storing to DB and Session 
							//commented  on 23-03-2018 to avoid mandatory for aadhar
							//$encrypted_aadhar = $this->encrypt($htmlencoded_aadhar_no);

							//check drop down values
							$table = 'DmiCertificateTypes';
							$post_input_request = $this->request->getData('certification_type');
							$certification_type = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function

							$table = 'DmiStates';
							$post_input_request = $this->request->getData('state');
							$state = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function

							$table = 'DmiDistricts';
							$post_input_request = $this->request->getData('district');
							$district = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function


							//checking radio buttons input
							//changed condition (export unit only for CA & Lab)
							if ($this->request->getData('certification_type')==1 /*|| $this->request->getData('certification_type')==3*/)//AND portion commented on 09-10-2017 by amol temp. to hide lab export.
							{
								$post_input_request = $this->request->getData('export_unit');
								$export_unit = $this->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
								if ($export_unit == null) { return null;}
							} else {

								$export_unit = '';
							}


							// New section is added for getting the printer sponsored ca
							// Done by Pravin  Bhakare 18-10-2021
							$press_is_sponsored = "no";

							if ($certificate_type_id == 2) {

								$post_input_request = $this->request->getData('is_sponsored_press');				
								$is_sponsored_press = $this->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
								
								if ($is_sponsored_press == null) { 
									$this->set('return_error_msg','Is press sponsored by CA option not selected');	
									return null;	
									exit;					
								}
							
								if ($is_sponsored_press == 'yes') {

									$sponsored_ca = htmlentities($this->request->getData('sponsored_ca'), ENT_QUOTES);
									
									if (empty($sponsored_ca)) {
										$this->set('return_error_msg','Sponsored CA not selected');	
										return null;
										exit;
										
									} else {
										
										$valid_ca = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$sponsored_ca)))->first();
										
										if (empty($valid_ca)) {
											
											$this->set('return_error_msg','Invalid sponsored CA selected');	
											return null;
											exit;
											
										} else {
											
											$press_is_sponsored = "yes";
										}
									}									
								}
							}


							//$export_unit = 'no';//default set to no, currently not required.
							//start , Get Details of old grant application

							$post_input_request = $this->request->getData('is_already_granted');
							$is_already_granted = $this->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
							
							if ($is_already_granted == null) { return null;}

							if ($is_already_granted == 'yes') {

								$old_certificate_no = htmlentities($this->request->getData('old_certificate_no'), ENT_QUOTES);

								// Start Apply check for to find duplicate cerification number (Done by pravin 14-07-2018)
								$duplicate_certification_no = $this->DmiOldApplicationCertificateDetails->find('all',array('conditions'=>array('certificate_no'=>$old_certificate_no)))->first();

								if (empty($duplicate_certification_no)) {

									$grant_date = $this->request->getData('grant_date');
									$grant_date = $this->Customfunctions->dateFormatCheck($grant_date);
									$renewal_date_details = $this->request->getData('renewal_dates');


									$i=0;
									$valid_renewal_date = array();
									foreach($renewal_date_details as $renewal_year) {

										if (!empty($renewal_year)) {
											
											if ($certification_type == 1) {	
												
												$update_renewal_date = '01/04/'.trim($renewal_year);
												$valid_renewal_date[$i] = $this->Customfunctions->dateFormatCheck($update_renewal_date);

											} elseif ($certification_type == 2) {
											
												$update_renewal_date = '01/01/'.trim($renewal_year);
												$valid_renewal_date[$i] = $this->Customfunctions->dateFormatCheck($update_renewal_date);

											} elseif ($certification_type == 3) {

												$update_renewal_date = '01/07/'.trim($renewal_year);
												$valid_renewal_date[$i] = $this->Customfunctions->dateFormatCheck($update_renewal_date);
											}

											$i=$i+1;
										}
									}

								} else {

									$this->set('duplicate_certification_no_msg','This cerificate no. already exist');
									return null;
									exit;
								}
							}
						

							$DmiFirmsEntitny = $this->DmiFirms->newEntity(array($this->request->getData(),

								'customer_primary_id'=>$customer_primary_id,
								'customer_primary_once_no'=>null,
								'customer_id'=>$customer_secondary_id,
								'password'=>'91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc',//Agmark123@
							//	'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2',//123
								'certification_type'=>$certification_type,
								'firm_name'=>$htmlencoded_firm_name,
								//'once_card_no'=>$encrypted_aadhar, //commented  on 23-03-2018 to avoid mandatory for aadhar
								'commodity'=>$commodity_value,
								'sub_commodity'=>$sub_commodities_values,
								'packaging_materials'=>$packaging_materials_values,
								'other_packaging_details'=>$htmlencoded_other_packaging_details,
								'street_address'=>$htmlencoded_street_address,
								'state'=>$state,
								'district'=>$district,
								'postal_code'=>$htmlencoded_postal_code,
								'email'=>$htmlencoded_email,
								'mobile_no'=>base64_encode($htmlencoded_mobile_no),
								'fax_no'=>base64_encode($htmlencoded_fax_no),
								'export_unit'=>$export_unit,
								'total_charges'=>$total_charges,

								// Start Save flag status for old application
								'is_already_granted'=>$is_already_granted,
								// end

								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s')

							));

							if ($this->DmiFirms->save($DmiFirmsEntitny)) {

								//Save the firm profile update logs history (Done by pravin 13/2/2018)
								$DmiFirmHistoryLogsEntity = $this->DmiFirmHistoryLogs->newEntity(array(
									'customer_primary_id'=>$customer_primary_id,
									'customer_primary_once_no'=>null,
									'customer_id'=>$customer_secondary_id,
									'password'=>'91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
								//	'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2',//123
									'certification_type'=>$certification_type,
									'firm_name'=>$htmlencoded_firm_name,
									//'once_card_no'=>$encrypted_aadhar, //commented  on 23-03-2018 to avoid mandatory for aadhar
									'commodity'=>$commodity_value,
									'sub_commodity'=>$sub_commodities_values,
									'packaging_materials'=>$packaging_materials_values,
									'other_packaging_details'=>$htmlencoded_other_packaging_details,
									'street_address'=>$htmlencoded_street_address,
									'state'=>$state,
									'district'=>$district,
									'postal_code'=>$htmlencoded_postal_code,
									'email'=>$htmlencoded_email,
									'mobile_no'=>base64_encode($htmlencoded_mobile_no),
									'fax_no'=>base64_encode($htmlencoded_fax_no),
									'export_unit'=>$export_unit,
									'total_charges'=>$total_charges,
									'is_already_granted'=>$is_already_granted,
									'created'=>date('Y-m-d H:i:s'),
									'modified'=>date('Y-m-d H:i:s')
								));

								$this->DmiFirmHistoryLogs->save($DmiFirmHistoryLogsEntity);

								// Save Old Grant Application Details
								if ($is_already_granted == 'yes') {

									$DmiOldApplicationCertificateDetailsEntitny = $this->DmiOldApplicationCertificateDetails->newEntity(array($this->request->getData(),
										'customer_id'=>$customer_secondary_id,
										'certificate_no'=>$old_certificate_no,
										'date_of_grant'=>$grant_date,
										'created'=>date('Y-m-d H:i:s'),
										'modified'=>date('Y-m-d H:i:s')
									));

									if ($this->DmiOldApplicationCertificateDetails->save($DmiOldApplicationCertificateDetailsEntitny)) {

										if (!empty($valid_renewal_date)) {

											foreach($valid_renewal_date as $renewal_date) {

												$DmiOldApplicationRenewalDatesEntitny = $this->DmiOldApplicationRenewalDates->newEntity(array($this->request->getData(),
													'customer_id'=>$customer_secondary_id,
													'renewal_date'=>$renewal_date,
													'created'=>date('Y-m-d H:i:s')
												));
												
												if ($this->DmiOldApplicationRenewalDates->save($DmiOldApplicationRenewalDatesEntitny)) {
													// save record
												}
											}
										}
									}
								}
								

								//save record in Auth Firm registration table
								$DmiAuthFirmRegistrationsEntity = $this->DmiAuthFirmRegistrations->newEntity(array(

									'primary_id'=>$customer_primary_id,
									'firm_id'=>$customer_secondary_id,
									'user_email_id'=>$this->Session->read('username'),
									'user_once_no'=>$this->Session->read('once_card_no'),
									'created'=>date('Y-m-d H:i:s'),
									'modified'=>date('Y-m-d H:i:s')

								));
								
								$this->DmiAuthFirmRegistrations->save($DmiAuthFirmRegistrationsEntity);

								//This function is used to save Application with RO mapping record while new firm added.
								$this->loadModel('DmiApplWithRoMappings');
								$this->loadModel('DmiApplWithRoMappingLogs');
								$this->DmiApplWithRoMappings->saveRecord($customer_secondary_id,$district_ro_id);


								// New section is added for getting the printer sponsored ca
								// Done by Pravin  Bhakare 18-10-2021	
								if ($press_is_sponsored == 'yes') {

									$this->loadModel('DmiSponsoredPrintingFirms');

									$sponsored_entity = $this->DmiFirms->newEntity(array(
										'customer_id'=>$customer_secondary_id,
										'sponsored_ca'=>$sponsored_ca,
										'created'=>date('Y-m-d H:i:s'),
										'modified'=>date('Y-m-d H:i:s')));

									$this->DmiSponsoredPrintingFirms->save($sponsored_entity);
									
									//entry in application tranfer logs table to manage flow as per these appl.	
									//applied on 20-05-2022 by Amol, required entry in this table to manage PP flow btns
									$this->loadModel('DmiApplTransferLogs');
									
									//get office of sponserer CA
									$sponsored_ca_office_id  = $this->DmiApplWithRoMappings->find('all',array('fields'=>array('office_id'),'conditions'=>array('customer_id IS'=>$sponsored_ca),'order'=>'id desc'))->first();
									$district_ro_id = $sponsored_ca_office_id['office_id'];
									$getSponsOffsemail = $this->DmiRoOffices->find('all',array('fields'=>'ro_email_id','conditions'=>array('id'=>$district_ro_id)))->first();
									$SponsOffsemail = $getSponsOffsemail['ro_email_id'];

									$DmiApplTransferLogs_entity = $this->DmiApplTransferLogs->newEntity(array(
										'customer_id'=>$customer_secondary_id,
										'from_office'=>$district_office,
										'from_user'=>$ro_short_code_query['ro_email_id'],
										'to_office'=>$district_ro_id,
										'to_user'=>$SponsOffsemail,
										'by_user'=>$ro_short_code_query['ro_email_id'],
										'appl_type'=>1,
										'created'=>date('Y-m-d H:i:s')));

									$this->DmiApplTransferLogs->save($DmiApplTransferLogs_entity);
								}	

								$message = 'Your have Successfully Created New Firm.';
								$message_theme = 'success';
								$redirect_to = 'home';
								
								//added on 22-08-2017 by Pravin to send SMS/Email
								//call custom function from Model with message id
								//$this->DmiSmsEmailTemplates->sendMessage(3,$customer_secondary_id);

							} else {

								$message = 'Sorry... New firm Not created please try again';
								$message_theme = 'failed';
								$redirect_to = 'add_firm';
							}
							
						} else {

							$message = 'You are not logged in... Please login first';
							$message_theme = 'failed';
							$redirect_to = '/';
						}

					} else {

						$this->set('return_error_msg','Please check some fields are not entered');
						return null;
						exit;
					}

				} else {

					$message = 'This email id is already registered with us. Please create firm with another email id. Thankyou.';
					$message_theme = 'failed';
					$redirect_to = 'add_firm';
				}

			}

			// set variables to show popup messages from view file
			$this->set('message',$message);
			$this->set('return_error_msg',null);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);

		}


		// EDIT FIRM FETCH ID
		public function editFirmFetchId($id) {

			$this->Session->write('firm_table_id',$id);
			$this->redirect('/authprocessedoldapp/edit_firm');
		}

		
		
		// EDIT FIRM
		public function editFirm() {

			$this->loadComponent('Customfunctions');
			$this->loadModel('MCommodityCategory');
			$this->loadModel('DmiCertificateTypes');
			$this->loadModel('DmiPackingTypes');
			$this->loadModel('DmiStates');
			$this->loadModel('DmiDistricts');
			$this->loadModel('DmiFirms');
			$this->loadModel('DmiRoOffices');
			$this->loadModel('DmiOldApplicationCertificateDetails');
			$this->loadModel('DmiOldApplicationRenewalDates');
			$this->loadModel('DmiSmsEmailTemplates');
			$this->loadModel('DmiFirmHistoryLogs');
			$this->loadModel('MCommodity');
			$this->loadModel('DmiUserRoles');

			$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('old_appln_data_entry'=>'yes','user_email_id IS'=>$this->Session->read('username'))))->first();
			
			if (empty($user_access)) {
				echo "Sorry.. You don't have permission to view this page";
				exit();
			}

			$firm_table_id = $this->Session->read('firm_table_id');
			$firm_id_result = $this->DmiFirms->find('all',array('fields'=>'customer_id', 'conditions'=>array('id IS'=>$firm_table_id)))->first();
			$firm_id = $firm_id_result['customer_id'];
			$this->Session->write('customer_id',$firm_id);

			$added_firms = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$firm_id)))->toArray();
			$added_firm_field = $added_firms[0];

			//get personal details masked by custom function to show in secure mode applied on 12-10-2017 by Amol
			$added_firms[0]['mobile_no'] = $this->Customfunctions->getMaskedValue(base64_decode($added_firms[0]['mobile_no']),'mobile'); //This is added on 27-04-2021 for base64decoding by AKASH
			$added_firms[0]['email'] = $this->Customfunctions->getMaskedValue(base64_decode($added_firms[0]['email']),'email');
	
			$this->set('added_firms',$added_firms);

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//added on 01-06-2017 by Amol																						//
			//to decrypt aadhar number before showing on frontend																//
			//																													//					
			//commented  on 23-03-2018 to avoid mandatory for aadhar															//
			//	/*	$decrypted_aadhar = $this->decrypt($added_firm_field['once_card_no']);										//
			//																													//
			//			$decrypted_aadhar = $this->get_masked_value($decrypted_aadhar,'aadhar');//applied on 12-10-2017 by Amol	//
			//	$this->set('decrypted_aadhar',$decrypted_aadhar);																//				
			//	*/																												//
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			//taking id of multiple sub commodities	to show names in list
			$sub_comm_id = explode(',',$added_firm_field['sub_commodity']);
			$sub_commodity_value = $this->MCommodity->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toArray();
			$this->set('sub_commodity_value',$sub_commodity_value);


			//taking id of multiple Packaging Materials types to show names in list
			$packaging_type_id = explode(',',$added_firm_field['packaging_materials']);
			$packaging_materials_value = $this->DmiPackingTypes->find('list',array('valueField'=>'packing_type', 'conditions'=>array('id IN'=>$packaging_type_id)))->toArray();
			$this->set('packaging_materials_value',$packaging_materials_value);


			//taking select values from id
			$certification_type_value = $this->DmiCertificateTypes->find('all',array('fields'=>'certificate_type', 'conditions'=>array('id IS'=>$added_firm_field['certification_type'])))->first();
			$this->set('certification_type_value',$certification_type_value);
			$commodity_value = $this->MCommodityCategory->find('all',array('fields'=>'category_name', 'conditions'=>array('category_code IS'=>$added_firm_field['commodity'],'display'=>'Y')))->first();
			$this->set('commodity_value',$commodity_value);
			
			
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//$sub_commodity_value = $this->Dmi_sub_commodity->find('first',array('fields'=>'sub_comm_name', 'conditions'=>array('id'=>$added_firm_field['sub_commodity'])));	//
			//$this->set('sub_commodity_value',$sub_commodity_value);																											//
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			
			$state_value = $this->DmiStates->find('all',array('fields'=>'state_name', 'conditions'=>array('id IS'=>$added_firm_field['state'],'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->first();
			$this->set('state_value',$state_value);

			$district_value = $this->DmiDistricts->find('all',array('fields'=>'district_name', 'conditions'=>array('id IS'=>$added_firm_field['district'],'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->first();
			$this->set('district_value',$district_value);

			$is_already_granted = $added_firm_field['is_already_granted'];
			$this->set('is_already_granted',$is_already_granted);

			$certificate_no = null;
			$date_of_grant = null;
			$old_certificate_details = $this->DmiOldApplicationCertificateDetails->find('all', array('conditions'=>array('customer_id IS'=>$firm_id)))->first();

			if (!empty($old_certificate_details)) {
				
				$certificate_no = $old_certificate_details['certificate_no'];
				$date_of_grant = $old_certificate_details['date_of_grant'];
			}

			$this->set('certificate_no',$certificate_no);
			$this->set('date_of_grant',$date_of_grant);

			$old_app_renewal_dates = $this->DmiOldApplicationRenewalDates->find('all', array('conditions'=>array('customer_id IS'=>$firm_id)))->toArray();
			$this->set('old_app_renewal_dates',$old_app_renewal_dates);

			// set variables to show popup messages from view file
			$message = '';
			$message_theme = '';
			$redirect_to = '';


			if (null!==($this->request->getData('ok'))) {

				$this->redirect('home');
			} elseif (null !==($this->request->getData('update'))) {

				//this check added on 19-06-2018 to avoid duplicate email id.
				$Checkemailexist =  $this->DmiFirms->find('all', array('fields' => 'email', 'conditions' => array('email IS' => $this->request->getData('email'),'id !='=>$firm_table_id)))->first();
					
				if ($Checkemailexist == null) {

					//applied condition to check all post data for !empty validation on server side
					if (!empty($this->request->getData('email'))) {

						//$htmlencoded_aadhar_no = htmlentities($this->request->getData('once_card_no'), ENT_QUOTES);
						$htmlencoded_email = base64_encode(htmlentities($this->request->getData('email'), ENT_QUOTES));
						//$htmlencoded_mobile_no = htmlentities($this->request->getData('mobile_no'), ENT_QUOTES);
						$htmlencoded_phone_no = htmlentities($this->request->getData('fax_no'), ENT_QUOTES);
						
						
						///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						//added on 01-06-2017 by Amol																							 //	
						//to encrypt aadhar number before storing to DB and Session																 //	
						//$encrypted_aadhar = $this->encrypt($htmlencoded_aadhar_no); //commented  on 23-03-2018 to avoid mandatory for aadhar	 //
						///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

						
						$db_email_before_update = $this->DmiFirms->find('all', array('fields'=>'email', 'conditions'=>array('id IS'=>$firm_table_id)))->first();

						//below query & conditions added on 12-10-2017 by Amol
						//To check if mobile,aadhar & email post in proper format, if not then save old value itself from DB
						$added_firms = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$firm_id)))->first();

						if (preg_match("/^[X-X]{6}[0-9]{4}$/i", $this->request->getData('mobile_no'),$matches)==1) {
							
							$htmlencoded_mobile_no = $added_firms['mobile_no'];
						}
						
						///////////////////////////////////////////////////////////////////////////////////////////////////
						//commented  on 23-03-2018 to avoid mandatory for aadhar										 //
						/*if (preg_match("/^[X-X]{8}[0-9]{4}$/i", $this->request->getData('once_card_no'),$matches)==1)  //
						//{																								 //	
						//	$encrypted_aadhar = $added_firms['once_card_no']; 											 //
						//}*/																							 //	
						///////////////////////////////////////////////////////////////////////////////////////////////////
						
						
						$email_masked_value = $this->Customfunctions->getEmailMasked(base64_decode($added_firms['email']));//called custom function
						if ($email_masked_value == $this->request->getData('email')) {

							$htmlencoded_email = $added_firms['email'];
						}


						$DmiFirmsEntity = $this->DmiFirms->newEntity(array($this->request->getData(),

							'id'=>$firm_table_id,
							//	'once_card_no'=>$encrypted_aadhar, //commented on 23-03-2018 to avoid mandatory for aadhar
							'email'=>$htmlencoded_email,
							//	'mobile_no'=>$htmlencoded_mobile_no,
							'fax_no'=>base64_encode($htmlencoded_phone_no), //This is added on 27-04-2021 for base64encoding by AKASH
							'modified'=>date('Y-m-d H:i:s')

						));
						
						if ($this->DmiFirms->save($DmiFirmsEntity)) {

							//Save the firm profile update logs history (Done by pravin 13/2/2018)
							$DmiFirmHistoryLogsEntity = $this->DmiFirmHistoryLogs->newEntity(array(
								'customer_primary_id'=>$added_firms['customer_primary_id'],
								'customer_primary_once_no'=>$added_firms['customer_primary_once_no'],
								'customer_id'=>$added_firms['customer_id'],
								'password'=>$added_firms['password'],
								'certification_type'=>$added_firms['certification_type'],
								'firm_name'=>$added_firms['firm_name'],
								//'once_card_no'=>$added_firms['once_card_no'], //commented  on 23-03-2018 to avoid mandatory for aadhar
								'commodity'=>$added_firms['commodity'],
								'sub_commodity'=>$added_firms['sub_commodity'],
								'packaging_materials'=>$added_firms['packaging_materials'],
								'other_packaging_details'=>$added_firms['other_packaging_details'],
								'street_address'=>$added_firms['street_address'],
								'state'=>$added_firms['state'],
								'district'=>$added_firms['district'],
								'postal_code'=>$added_firms['postal_code'],
								'email'=>$htmlencoded_email,
								'mobile_no'=>$added_firms['mobile_no'],
								'fax_no'=>base64_encode($htmlencoded_phone_no),
								'export_unit'=>$added_firms['export_unit'],
								'total_charges'=>$added_firms['total_charges'],
								'is_already_granted'=>$added_firms['is_already_granted'],
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s')
							));

							$this->DmiFirmHistoryLogs->save($DmiFirmHistoryLogsEntity);

							$db_email_after_update = $this->DmiFirms->find('all', array('fields'=>'email', 'conditions'=>array('id IS'=>$firm_table_id)))->first();

							if ($db_email_before_update['email'] != $db_email_after_update['email']) {

								$message = '& your Email Id is Changed. New password reset link sent on new email id.';
								
							}

							//added on 22-08-2017 by Pravin to send SMS/Email
							//call custom function from Model with message id
							//	$this->DmiSmsEmailTemplate->send_message(4,$firm_id);

							$message = 'Firm details are updated '. $message;
							$message_theme = 'success';
							$redirect_to = 'edit_firm';

						} else {
							
							$message = 'Sorry... Firm details are not updated';
							$message_theme = 'failed';
							$redirect_to = 'edit_firm';
						}
					
					} else {
						
						$this->set('return_error_msg','Please check some fields are not entered');
						return false;
						exit;
					}

				} else {

					$message = 'This email id is already exist. Please provide another email id to update. Thankyou.';
					$message_theme = 'failed';
					$redirect_to = 'edit_firm';
				}
			}
				
			// set variables to show popup messages from view file
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('return_error_msg',null);
			$this->set('redirect_to',$redirect_to);

		}



		// Show primary user name (Done by pravin 16-07-2018)
		public function primaryUserDetail() {

			$primary_id = $_POST['primary_id'];
			$this->loadModel('DmiCustomers');
			$primary_user_details = $this->DmiCustomers->find('all',array('conditions'=>array('customer_id IS'=>$primary_id)))->first();
			if (!empty($primary_user_details)) {
			$name = $primary_user_details['f_name'].' '.$primary_user_details['l_name'];
			} else {$name = null; }
			echo $name; exit;
		}


	}
?>
