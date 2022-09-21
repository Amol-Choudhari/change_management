<?php

	namespace App\Controller;

	use Cake\Event\Event;
	use App\Network\Request\Request;
	use App\Network\Response\Response;
	use Cake\Datasource\ConnectionManager;

	class AdvancepaymentController extends AppController{

		var $name = 'Advancepayment';

		public function initialize(): void {
			parent::initialize();

			$this->loadComponent('RequestHandler');
			$this->loadComponent('Createcaptcha');
			$this->loadComponent('Paymentdetails');
			$this->loadComponent('Customfunctions');
			$this->loadComponent('Beforepageload');

			$this->viewBuilder()->setHelpers(['Form','Html','Time']);
			$this->viewBuilder()->setLayout('secondary_customer');

		}
		
		//Before Filter Method
		public function beforeFilter($event) {
			parent::beforeFilter($event);

			//Customer Last Login Added By AKASH on 06-09-2021
			$customer_last_login = $this->Customfunctions->customerLastLogin();
			$this->set('customer_last_login', $customer_last_login);

			$customer_id = $this->Session->read('username');
			$this->loadModel('DmiFirms');
			$this->loadModel('DmiUserRoles');

			 //Show button on Side menu
			$this->Beforepageload->showButtonOnSecondaryHome();

			if ($customer_id == null) {

				echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot'); ?>"> Please Login</a><?php
				exit;

			} else { //this else portion added on 10-07-2017 by Amol to allow only logged in Applicant

				//checking applicant id pattern ex.102/1/PUN/006
				if (preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $this->Session->read('username'),$matches)==1) {
					//Give Permission
				} else if (strpos(base64_decode($customer_id),'@')) {//for email encoding

					$paouser =  $this->DmiUserRoles->find('all',array('conditions'=>array('pao'=>'yes','user_email_id IS'=>$this->Session->read('username'))))->first();

					if (empty($paouser)) {

						echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot'); ?>"> Please Login</a><?php
						exit;
					}

				}else{
					echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot'); ?>"> Please Login</a><?php
					exit;
				}
			}

			//created and called function to check applicant is valid for renewal or not
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



		// TRANSACTIONS
		// @AUTHOR : PRAVIN BHAKARE
		// @CONTRIBUTER : AKASH THAKRE
		// DATE : 06-09-2021 (U)
		
		public function transactions(){

			//Set the Layout
			$this->viewBuilder()->setLayout('secondary_customer');
			//Load Models
			$this->loadModel('DmiAdvPaymentTransactions');
			$this->loadModel('DmiAdvPaymentDetails');
			//Session Varible
			$customer_id = $this->Session->read('username');

			$currentBalance = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>'balance_amount','conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
					   
			$currentBalanceAmount = 0;

			if (!empty($currentBalance)) {

				$currentBalanceAmount = $currentBalance['balance_amount'];
			} 
			$this->set('currentBalance',$currentBalanceAmount);

			$transactionsHistory = $this->DmiAdvPaymentTransactions->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->toArray();



			$unconfirmedBalance = $this->DmiAdvPaymentDetails->find('all', array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();

			$unconfirmedBalanceAmount = 0;

			//Added empty condition on 06-09-2021
			if (!empty($unconfirmedBalance)) {

				$status = $unconfirmedBalance['payment_confirmation'];

				if ($status != 'confirmed') {

					$unconfirmedBalanceAmount = $unconfirmedBalance['amount_paid'];
				}
			}

			$this->set('currentBalanceAmount',$currentBalanceAmount);
			$this->set('transactionsHistory',$transactionsHistory);
			$this->set('unconfirmedBalanceAmount',$unconfirmedBalanceAmount);

		}


		// ADD PAYMENT
		// @AUTHOR : PRAVIN BHAKARE
		// @CONTRIBUTER : AKASH THAKRE
		// DATE : 09-09-2022 (U)
		
		public function addPayment(){
			
			//Set the variables
			$message = '';
			$message_theme = '';
			$redirect_to = '';
			//set the session variable
			$this->Session->write('advancepayment','yes');
			$this->Session->write('application_type',1);

			$application_type = $this->Session->read('application_type');

			//Load Models
			$this->loadModel('DmiFirms');
			$this->loadModel('DmiAdvPaymentDetails');
			$this->loadModel('DmiSmsEmailTemplates');

			//Set the layout
			$this->viewBuilder()->setLayout('secondary_customer');

			$customer_id = $this->Session->read('username');
			$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
			$firm_details_fields = $firm_details[0];
			$this->set('firm_details',$firm_details);
			$this->set('firm_details_fields',$firm_details_fields);

			$firm_type = $this->Customfunctions->firmType($customer_id);
			$application_charge = $this->Customfunctions->applicationCharges($application_type,$firm_type);
			$this->set('application_charge',$application_charge);

			$lastAdvancePaymentDate = $this->Customfunctions->returnGrantDateCondition($customer_id);

			$this->Paymentdetails->applicantPaymentDetails($customer_id,$firm_details_fields['district'],'DmiAdvPaymentDetails');

			$recordId = $this->DmiAdvPaymentDetails->find('all', array('fields'=>array('id','payment_confirmation'),'conditions'=>array('customer_id IS'=>$customer_id,$lastAdvancePaymentDate),'order'=>'id desc'))->first();

			$status = null;

			if (!empty($recordId)) {
			   
				$status = $recordId['payment_confirmation'];
			}

			if (null!== ($this->request->getData('final_submit'))) {

				$payID = $recordId['id'];

				//Create the data entity for "DmiAdvPaymentDetails" top save the data
				$DmiAdvPaymentDetailsEntity = $this->DmiAdvPaymentDetails->newEntity(array(

					'id'=>$payID,
					'payment_confirmation'=>'pending',
					'modified'=>date('Y-m-d H:i:s')
				));

				if ($this->DmiAdvPaymentDetails->save($DmiAdvPaymentDetailsEntity)) {

					//SMS/EMAIL - Adavance Payment Final Submit
					//$this->DmiSmsEmailTemplates->sendMessage(108,$customer_id,'DmiChemistFinalSubmits'); #PACKER
					//$this->DmiSmsEmailTemplates->sendMessage(109,$customer_id,'DmiChemistFinalSubmits'); #DDO
					
					///Added this call to save the user action log on 09-09-2022 by Akash
					$this->Customfunctions->saveActionPoint('Advance Payment(Save)', 'Success');
					$message = 'Advance payment saved. After verification of payment details, the amount will be creadited on your account.';
					$message_theme = 'success';
					$redirect_to = 'add_payment';
				}

			} elseif (null!== ($this->request->getData('save'))) {

				$get_payment_details = $this->Paymentdetails->saveApplicantPaymentDetails($this->request->getData(),'DmiAdvPaymentDetails');
				
				if ($get_payment_details == 1) {
					///Added this call to save the user action log on 09-09-2022 by Akash
					$this->Customfunctions->saveActionPoint('Advance Payment Final Submit', 'Success');
					$message = 'Advance Payment, Saved successfully';
					$message_theme = 'success';
					$redirect_to = 'add_payment';
				} else {
					///Added this call to save the user action log on 09-09-2022 by Akash
					$this->Customfunctions->saveActionPoint('Advance Payment Final Submit', 'Failed');
					$message = 'Advance Payment Not, Saved successfully';
					$message_theme = 'failed';
					$redirect_to = 'add_payment';
				}
			}

			$this->set('status',$status);
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);

			if ($message != null){

				$this->render('/element/message_boxes');

			}
		}





		// ADV PAYMENT VERFICATION
		// @AUTHOR : PRAVIN BHAKARE
		// @CONTRIBUTER : AKASH THAKRE
		// DATE : ------
		
		public function advPaymentVerification() {

			$this->viewBuilder()->setLayout('admin_dashboard');

			$this->loadModel('DmiUsers');
			$this->loadModel('DmiPaoDetails');
			$this->loadModel('DmiAdvPaymentDetails');

			$paymemtReplied = array();
			$paymentConfirmed = array();
			$paymentNotconfirmed = array();
			$paymentPendingList = array();

			$username = $this->Session->read('username');

			$userid = $this->DmiUsers->find('all',array('fields'=>array('id'),'conditions'=>array('email IS'=> $username)))->first();
			$paoid = $this->DmiPaoDetails->find('all',array('fields'=>array('id'),'conditions'=>array('pao_user_id IS'=> $userid['id'])))->first();

			// Pending Listing
			$plist = $this->DmiAdvPaymentDetails->find('list',array('keyField'=>'id','valueField'=>array('customer_id'),'conditions'=>array('pao_id IS'=> $paoid['id'])))->toArray();
			$pendinglist = array_unique($plist);
		   
			foreach ($pendinglist as $key => $customerid) {
				   
				if ($customerid != null ) {

					$payStatus =  $this->DmiAdvPaymentDetails->find('all',array('conditions'=>array('customer_id IS'=> $customerid),'order'=>'id desc'))->first();
	
					if ($payStatus['payment_confirmation'] == 'pending') {

						$paymentPendingList[] = $payStatus;
					}

					if ($payStatus['payment_confirmation'] == 'not_confirmed') {

						$paymentNotconfirmed[] = $payStatus;
					}

					if ($payStatus['payment_confirmation'] == 'replied') {

						$paymemtReplied[] = $payStatus;
					}

					if ($payStatus['payment_confirmation'] == 'confirmed') {

						$paymentConfirmed[] = $payStatus;
					}
				}
			}

			$this->set('payment_pendingList',$paymentPendingList);
			$this->set('payment_notconfirmed',$paymentNotconfirmed);
			$this->set('paymemt_replied',$paymemtReplied);
			$this->set('payment_confirmed',$paymentConfirmed);
		}

	
	}



?>
