<?php

	namespace App\Controller;

	use Cake\Event\Event;
	use App\Network\Request\Request;
	use App\Network\Response\Response;
	use Cake\Datasource\ConnectionManager;

	class CommercialController extends AppController{

		var $name = 'Commercial';

		public function initialize(): void {
			parent::initialize();

			$this->loadComponent('RequestHandler');
			$this->loadComponent('Createcaptcha');
			$this->loadComponent('Paymentdetails');
			$this->loadComponent('Customfunctions');
			$this->loadComponent('Beforepageload');

			$this->viewBuilder()->setHelpers(['Form','Html','Time']);
			$this->viewBuilder()->setLayout('admin_dashboard');

			$this->loadModel('SampleInward');

		}

  		//Before Filter Method
  	  public function beforeFilter($event) {
  		parent::beforeFilter($event);

     		$this->loadModel('DmiUserRoles');
			$paouser =  $this->DmiUserRoles->find('all',array('conditions'=>array('pao'=>'yes','user_email_id IS'=>$this->Session->read('username'))))->first();

			if (empty($paouser)) {

				echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot');?>">Please Login</a><?php
				exit();
			}
		}



		// Commercial PAYMENT VERFICATION
		// @AUTHOR : PRAVIN BHAKARE
		// @CONTRIBUTER : AKASH THAKRE
		// DATE : ------

		public function commercialVerfication() {

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
      		$this->loadModel('LimsSamplePaymentDetails');

			$plist = $this->LimsSamplePaymentDetails->find('list',array('keyField'=>'id','valueField'=>array('sample_code'),'conditions'=>array('pao_id IS'=> $paoid['id'])))->toArray();

			$pendinglist = array_unique($plist);

			foreach ($pendinglist as $key => $sample_code) {

				if ($sample_code != null ) {

					$payStatus =  $this->LimsSamplePaymentDetails->find('all',array('conditions'=>array('sample_code IS'=> $sample_code),'order'=>'id desc'))->first();

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


			//pr($paymemtReplied); exit;
			$this->set('payment_pendingList',$paymentPendingList);
			$this->set('payment_notconfirmed',$paymentNotconfirmed);
			$this->set('paymemt_replied',$paymemtReplied);
			$this->set('payment_confirmed',$paymentConfirmed);
		}



		// commercial_payment_inspection
		// AUTHOR : Akash THAKRE
		// Date : 25-06-2022
		public function commercialPaymentInspection($id){

			$message = '';
			$message_theme = '';
			$redirect_to = '';


			$this->loadModel('LimsSamplePaymentDetails');
			$sample_id_result = $this->LimsSamplePaymentDetails->find('all',array('fields'=>'sample_code', 'conditions'=>array('id IS'=>$id)))->first();

			if (!empty($sample_id_result)) {

				$sample_code = $sample_id_result['sample_code'];
				$this->Session->write('sample_code',$sample_code);
			}


			if (!empty($sample_code)) {


				$office_table = 'DmiRoOffices';
				$field_name = 'ro_email_id';


				$this->loadModel($office_table);

				$this->loadModel('SampleInward');
				$this->loadModel('MCommodityCategory');
				$this->loadModel('MCommodity');
				$this->loadmodel('Workflow');

				$sample_information = $this->SampleInward->find('all', array('conditions' => array('org_sample_code IS'=>$sample_code)))->first();
				$this->set('sample_information',$sample_information);

				$category = $this->MCommodityCategory->getCategoryName($sample_information['category_code']);
				$this->set('category',$category['category_name']);

				//commodity
				$commodity = $this->MCommodity->getCommodityName($sample_information['commodity_code']);
				$this->set('commodity',$commodity['commodity_name']);

				$payment_confirmation_query = $this->LimsSamplePaymentDetails->find('all', array('conditions'=>array('sample_code IS'=>$sample_code),'order'=>'id DESC'))->first();

				$verification_action_value = $payment_confirmation_query['payment_confirmation'];

				//added new code to check unique transaction id, if already used.
				//on 15-10-2019 by Amol, called function custom function.
				if ($verification_action_value != 'confirmed') {

					//get existed application details if transaction id already used.
					$trans_id = $payment_confirmation_query['transaction_id'];
					$existed_appl_details = $this->checkUniqueTransIdForPao($trans_id,$sample_code);
					$this->set('existed_appl_details',$existed_appl_details);
				}

				$payment_trasaction_date = explode(' ',$payment_confirmation_query['transaction_date']);

				$action_value =null;

				if($verification_action_value == 'replied' || $verification_action_value == 'not_confirmed') {

					$action_value = 1;

				} elseif ($verification_action_value == 'confirmed') {

					$action_value = 0;
				}




				$this->loadModel('DmiPaoDetails');
				$this->loadModel('DmiUsers');

				$selected_pao_alias_name = $this->DmiPaoDetails->find('all',array('fields'=>'pao_alias_name','conditions'=>array('id IS'=>$payment_confirmation_query['pao_id'])))->first();

				$this->set('selected_pao_alias_name',$selected_pao_alias_name);
				$this->set('payment_confirmation_query',$payment_confirmation_query);
				$this->set('action_value',$action_value);
				$this->set('payment_trasaction_date',$payment_trasaction_date);

				// Fetch all referred back commment data
				$fetch_pao_referred_back = array();
				$fetch_pao_referred_back = $this->LimsSamplePaymentDetails->find('all', array('conditions'=>array('sample_code IS'=>$sample_code,'payment_confirmation'=>'not_confirmed')))->toArray();
				$this->set('fetch_pao_referred_back',$fetch_pao_referred_back);

				//find PAO email id
				$pao_id = $this->LimsSamplePaymentDetails->find('all', array('fields'=>'pao_id', 'conditions'=>array('sample_code IS'=>$sample_code)))->first();
				$pao_user_id = $this->DmiPaoDetails->find('all',array('fields'=>'pao_user_id', 'conditions'=>array('id IS'=>$pao_id['pao_id'])))->first();
				$pao_user_email_id = $this->DmiUsers->find('all',array('fields'=>'email', 'conditions'=>array('id IS'=>$pao_user_id['pao_user_id'])))->first();

				//Get User details
				$workflowQuery =	$this->Workflow->find('all')->where(['org_sample_code IS'=>$sample_code,'stage_smpl_flag'=>'SI'])->first();

				$src_loc_id = $this->DmiUsers->getPostedOffId($_SESSION['username']);
				$src_usr_cd = $this->DmiUsers->getUserTableId($_SESSION['username']);
				$dst_loc_id = $workflowQuery['src_loc_id'];
				$dst_usr_cd = $workflowQuery['src_usr_cd'];

				// Save payment details by applicant
				if (null!==($this->request->getData('payment_verificatin_action'))) {

					$payment_verification_action = $this->request->getData('action');
					$reason_option_comment = $this->request->getData('reasone_list_comment');
					$reasone_comment = htmlentities($this->request->getData('reasone_comment'), ENT_QUOTES);
					$transaction_date = $this->Customfunctions->dateFormatCheck($payment_confirmation_query['transaction_date']);
					$created = $this->Customfunctions->dateFormatCheck($payment_confirmation_query['created']);


					if ($payment_verification_action == 1) {

						$paymentEntity = $this->LimsSamplePaymentDetails->newEntity(array(

							'sample_code'=>$payment_confirmation_query['sample_code'],
							'sample_type'=>$payment_confirmation_query['sample_type'],
							'amount_paid'=>$payment_confirmation_query['amount_paid'],
							'transaction_id'=>$payment_confirmation_query['transaction_id'],
							'transaction_date'=>$transaction_date,
							'payment_receipt_docs'=>$payment_confirmation_query['payment_receipt_docs'],
							'payment_confirmation'=>'not_confirmed',
							'pao_id'=>$payment_confirmation_query['pao_id'],
							'district_id'=>$payment_confirmation_query['district_id'],  // Save District id to find list District wise
							'bharatkosh_payment_done'=>$payment_confirmation_query['bharatkosh_payment_done'],
							'reason_option_comment'=>$reason_option_comment,
							'reason_comment'=>$reasone_comment,
							'created'=>$created,
							'modified'=>date('Y-m-d H:i:s')
						));

						if ($this->LimsSamplePaymentDetails->save($paymentEntity)) {



							//Save the Workflow entry
							$workflow_data = $this->Workflow->newEntity(array(

								'org_sample_code'=>$sample_code,
								'src_loc_id'=>$src_loc_id,
								'src_usr_cd'=>$src_usr_cd,
								'dst_loc_id'=>$dst_loc_id,
								'dst_usr_cd'=>$dst_usr_cd,
								'user_code'=>$src_usr_cd,
								'stage_smpl_cd'=>$sample_code,
								'tran_date'=>date('Y-m-d'),
								'stage'=>'3',
								'stage_smpl_flag'=>'PR' // Added this flag for "Payment Referred"
							));

							if ($this->Workflow->save($workflow_data)) {

								//added the "acc_rej_flg" => {PR} flag to show in the confirmed sample list on 05-07-2022
								$this->SampleInward->updateAll(array('status_flag'=>'S','acc_rej_flg'=>'PR'),array('org_sample_code'=>$sample_code));

								//Entry in all applications current position table
								$user_email_id = $pao_user_email_id['email'];

								#SMS: Payment Not Confirmed
								//$this->DmiSmsEmailTemplates->sendMessage(49,$customer_id);
								//$this->DmiSmsEmailTemplates->sendMessage(49,$customer_id);

								$this->Customfunctions->saveActionPoint('Payment Referred Back', 'Success');

								$message = 'Payment not confirmed and Referred Back to Applicant';
								$message_theme = 'success';
								$redirect_to = '../commercial_verfication';
							}

						}

					} elseif ($payment_verification_action == 0) {

						$paymentEntity = $this->LimsSamplePaymentDetails->newEntity(array(

							'sample_code'=>$payment_confirmation_query['sample_code'],
							'sample_type'=>$payment_confirmation_query['sample_type'],
							'amount_paid'=>$payment_confirmation_query['amount_paid'],
							'transaction_id'=>$payment_confirmation_query['transaction_id'],
							'transaction_date'=>$transaction_date,
							'payment_receipt_docs'=>$payment_confirmation_query['payment_receipt_docs'],
							'payment_confirmation'=>'confirmed',
							'pao_id'=>$payment_confirmation_query['pao_id'],
							'district_id'=>$payment_confirmation_query['district_id'],  // Save District id to find list District wise
							'bharatkosh_payment_done'=>$payment_confirmation_query['bharatkosh_payment_done'],
							'reason_option_comment'=>$reason_option_comment,
							'reason_comment'=>$reasone_comment,
							'created'=>$created,
							'modified'=>date('Y-m-d H:i:s')
						));


						//added the "acc_rej_flg" => A flag to show in the confirmed sample list on 05-07-2022
						$this->SampleInward->updateAll(array('status_flag'=>'S','acc_rej_flg'=>'A'),array('org_sample_code'=>$sample_code));



						if ($this->LimsSamplePaymentDetails->save($paymentEntity)) {

							//Save the Workflow entry
							$workflow_data = $this->Workflow->newEntity(array(

								'org_sample_code'=>$sample_code,
								'src_loc_id'=>$src_loc_id,
								'src_usr_cd'=>$src_usr_cd,
								'dst_loc_id'=>$dst_loc_id,
								'dst_usr_cd'=>$dst_usr_cd,
								'user_code'=>$src_usr_cd,
								'stage_smpl_cd'=>$sample_code,
								'tran_date'=>date('Y-m-d'),
								'stage'=>'3',
								'stage_smpl_flag'=>'PC' // Added this flag for "Payment Confirmed"
							));

							if ($this->Workflow->save($workflow_data)) {

								$this->loadModel('DmiSmsEmailTemplates');

								#SMS: Payment Not Confirmed
								//$this->DmiSmsEmailTemplates->sendMessage(49,$customer_id);
								//$this->DmiSmsEmailTemplates->sendMessage(49,$customer_id);
								//$this->DmiSmsEmailTemplates->sendMessage(49,$customer_id);

								$this->Customfunctions->saveActionPoint('Payment Confirmed', 'Success'); #Action

								$message = 'Payment Confirmed Successfully';
								$message_theme = 'success';
								$redirect_to = '../commercial_verfication';
							}
						}
					}
				}

			} else {

				$message = '';
				$redirect_to = '../commercial_verfication';
			}

			// set variables to show popup messages from view file
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);

		}




		public function checkUniqueTransIdForPao($trans_id,$sample_code){

			$allow_id = 'yes';
			$existed_appl_details = null;

			$this->loadModel('LimsSamplePaymentDetails');

			//check new app if trans id already exist
			$check_trans_id = $this->LimsSamplePaymentDetails->find('all',array('conditions'=>array('transaction_id IS'=>$trans_id,'sample_code !='=>$sample_code),'order'=>'id desc'))->first();
				//for new
				if(!empty($check_trans_id)){
					$existed_customer_id = $check_trans_id['customer_id'];//applicant which already used this trans id.
					//old existed application details
					$existed_appl_details = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$existed_customer_id)))->first();
				}

			return $existed_appl_details;
		}



	}



?>
