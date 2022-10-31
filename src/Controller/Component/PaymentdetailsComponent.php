<?php	
	namespace app\Controller\Component;
	use Cake\Controller\Controller;
	use Cake\Controller\Component;	
	use Cake\Controller\ComponentRegistry;
	use Cake\ORM\Table;
	use Cake\ORM\TableRegistry;
	use Cake\Datasource\EntityInterface;

	class PaymentdetailsComponent extends Component {
	
		
		public $components= array('Session','Customfunctions');
		public $controller = null;
		public $session = null;
		public function initialize(array $config): void{
			parent::initialize($config);
			$this->Controller = $this->_registry->getController();
			$this->Session = $this->getController()->getRequest()->getSession();
			
		}
		
		//Below methods added for Directors details add/edit/delete ajax functions by Amol on 07-08-2017
		//These are used in all 3 CA/printing/lab inspections reports

			public function saveApplicantPaymentDetails($data,$form_table){
				
				$customer_id = $this->Session->read('username');
				$application_type = $this->Session->read('application_type');
				
				$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
				
				$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
				$Dmi_final_submit_tb = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
			
				$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');//initialize model in component
				$DmiApplicantPaymentDetails = TableRegistry::getTableLocator()->get($form_table);//initialize model in component
				$DmiDistricts = TableRegistry::getTableLocator()->get('DmiDistricts');
				$DmiPaoDetails = TableRegistry::getTableLocator()->get('DmiPaoDetails');
				$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
				$all_applications_current_position = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['appl_current_pos']);				
				$DmiSmsEmailTemplates = TableRegistry::getTableLocator()->get('DmiSmsEmailTemplates');//added on 23-07-2018 by Amol
								
				$once_card_no = $this->Session->read('once_card_no');
				$payment_conirmation_status = '';
				$payment_receipt_docs = '';
				
			
				$list_applicant_payment_id = $DmiApplicantPaymentDetails->find('list', array('fields'=>'id','conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->toArray();
				
				if(!empty($list_applicant_payment_id)){	
				
					$payment_confirmation_query = $DmiApplicantPaymentDetails->find('all', array('conditions'=>array('id'=>max($list_applicant_payment_id))))->first();
					$payment_conirmation_status = $payment_confirmation_query['payment_confirmation'];
					$payment_receipt_docs = $payment_confirmation_query['payment_receipt_docs'];
				}
				
				$firm_details = $Dmi_firm->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
				$firm_details_fields = $firm_details[0];

				$district_id = $firm_details_fields['district'];
				$pao_id_details = $DmiDistricts->find('all',array('fields'=>'pao_id','conditions'=>array('id IS'=>$district_id)))->first();	
				$pao_id = $pao_id_details['pao_id'];
				
				if(empty($data['payment_amount']) && empty($data['payment_transaction_id']) && empty($data['bharatkosh_payment_done'])
					&& empty($data['payment_trasaction_date'])){
						return false;
				}
				
				if(empty($payment_receipt_docs)){
					if(empty($data['payment_receipt_document']->getClientFilename())){
						return false;
					}
				}
				
				$payment_amount = htmlentities($data['payment_amount'], ENT_QUOTES);
				
				$payment_transaction_id = htmlentities($data['payment_transaction_id'], ENT_QUOTES);
				
				$post_input_request = $data['bharatkosh_payment_done'];
				$bharatkosh_payment_done = $this->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
				if($bharatkosh_payment_done == null){ return false;}				
					
				
				
				if(!empty($data['payment_receipt_document']->getClientFilename())){
					
					$file_name = $data['payment_receipt_document']->getClientFilename();
					$file_size = $data['payment_receipt_document']->getSize();
					$file_type = $data['payment_receipt_document']->getClientMediaType();
					$file_local_path = $data['payment_receipt_document']->getStream()->getMetadata('uri');
				
					$payment_receipt_docs = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
				}
				
				$payment_trasaction_date = $this->Customfunctions->changeDateFormat($data['payment_trasaction_date']);
				
				if($payment_conirmation_status == 'not_confirmed'){
					
					
					//find PAO email id (Done By pravin 4/11/2017)
				//	$pao = $DmiApplicantPaymentDetails->find('all', array('fields'=>'pao_id', 'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();//order by added on 04-04-2022 by Amol
				//	$pao_user_id = $DmiPaoDetails->find('all',array('fields'=>'pao_user_id', 'conditions'=>array('id IS'=>$pao['pao_id'])))->first();
				
				//commented above lines now taking pao id variable directly from above query result.
				//to take latest pao/ddo user for that district applications, if user transfer occured
				//on 04-04-2022 by Amol
					$pao_user_id = $DmiPaoDetails->find('all',array('fields'=>'pao_user_id', 'conditions'=>array('id IS'=>$pao_id)))->first();
					$pao_user_email_id = $DmiUsers->find('all',array('fields'=>'email', 'conditions'=>array('id IS'=>$pao_user_id['pao_user_id'])))->first();
				
					$DmiApplicantPaymentDetailsEntity = $DmiApplicantPaymentDetails->newEntity(array(
							'customer_id'=>$customer_id,
							'once_no'=>$once_card_no,
							'certificate_type'=>$firm_details_fields['certification_type'],
							'amount_paid'=>$payment_amount,
							'transaction_id'=>$payment_transaction_id,											
							'transaction_date'=>$payment_trasaction_date,
							'payment_receipt_docs'=>$payment_receipt_docs,
							'bharatkosh_payment_done'=>$bharatkosh_payment_done,
							'reason_option_comment'=>$payment_confirmation_query['reason_option_comment'],
							'reason_comment'=>$payment_confirmation_query['reason_comment'],
							'district_id'=>$district_id,  // Save District id to find list District wise (Updated Date : 02/05/2018 Pravin)
							'payment_confirmation'=>'replied',
							'pao_id'=>$pao_id,
							'created'=>date('Y-m-d H:i:s'),
							'modified'=>date('Y-m-d H:i:s')
					 ));
								
					if($DmiApplicantPaymentDetails->save($DmiApplicantPaymentDetailsEntity)){
						 
						//Entry in all applications current position table (Done By pravin 4/11/2017)
						$user_email_id = $pao_user_email_id['email'];
						$current_level = 'pao';
						$all_applications_current_position->currentUserUpdate($customer_id,$user_email_id,$current_level);//call to custom function from model
						
						if($form_table == 'DmiAdvPaymentDetails'){
							#SMS: Advance Payment referred back replied
							$DmiSmsEmailTemplates->sendMessage(64,$customer_id); #DDO	
						}else{
							#SMS: Applicant Replied to DDO
							$DmiSmsEmailTemplates->sendMessage(50,$customer_id);
						}
							
						return true;	
					}
					
				}else{
					
					$DmiApplicantPaymentDetailsEntity = $DmiApplicantPaymentDetails->newEntity(array(
							'customer_id'=>$customer_id,
							'once_no'=>$once_card_no,
							'certificate_type'=>$firm_details_fields['certification_type'],
							'amount_paid'=>$payment_amount,
							'transaction_id'=>$payment_transaction_id,											
							'transaction_date'=>$payment_trasaction_date,
							'payment_receipt_docs'=>$payment_receipt_docs,
							'bharatkosh_payment_done'=>$bharatkosh_payment_done,
							'payment_confirmation'=>'saved',
							'district_id'=>$district_id,  // Save District id to find list District wise (Updated Date : 02/05/2018 Pravin)
							'pao_id'=>$pao_id,
							'created'=>date('Y-m-d H:i:s'),	
							'modified'=>date('Y-m-d H:i:s')
					 ));
						
					if($DmiApplicantPaymentDetails->save($DmiApplicantPaymentDetailsEntity)){
						
							return true;	
					}
					
				}
				
			}
			
			
			public function applicantPaymentDetails($customer_id,$district_id,$form_table){
				
				$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
				
				$DmiPaoDetails = TableRegistry::getTableLocator()->get('DmiPaoDetails');//initialize model in component
				$DmiDistricts = TableRegistry::getTableLocator()->get('DmiDistricts');//initialize model in component
				$DmiApplicantPaymentDetails = TableRegistry::getTableLocator()->get($form_table);//initialize model in component
				
				
				
				$process_query = 'insert';
				
				$bharatkosh_payment_done = '';
				$payment_amount = '';
				$payment_transaction_id = '';
				$selected_pao_alias_name = '';
				$payment_trasaction_date[0] = '';
				$payment_receipt_docs = '';
				$reason_list_comment = '';
				$reason_comment = '';
				
				$this->Controller->set('bharatkosh_payment_done',$bharatkosh_payment_done);
				$this->Controller->set('payment_amount',$payment_amount);
				$this->Controller->set('payment_transaction_id',$payment_transaction_id);
				$this->Controller->set('selected_pao_alias_name',$selected_pao_alias_name);
				$this->Controller->set('payment_trasaction_date',$payment_trasaction_date);
				$this->Controller->set('payment_receipt_docs',$payment_receipt_docs);
				$this->Controller->set('reason_list_comment',$reason_list_comment);
				$this->Controller->set('reason_comment',$reason_comment);
				
				$pao_alias_name = $DmiPaoDetails->find('list',array('valueField'=>'pao_alias_name'))->toArray();
				$this->Controller->set('pao_alias_name',$pao_alias_name);
				
				$pao_id = $DmiDistricts->find('all',array('fields'=>'pao_id','conditions'=>array('id IS'=>$district_id)))->first();
				if(!empty($pao_id['pao_id'])){
					$pao_to_whom_payment = $pao_alias_name[$pao_id['pao_id']];
				}else{
					$pao_to_whom_payment = null;
				}
				
				
				$list_applicant_payment_id = $DmiApplicantPaymentDetails->find('list', array('fields'=>'id','conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
						
				if(!empty($list_applicant_payment_id)){
					
					$process_query = 'update';
					
					$payment_confirmation_query = $DmiApplicantPaymentDetails->find('all', array('conditions'=>array('id'=>max($list_applicant_payment_id))))->first();
					$payment_confirmation = $payment_confirmation_query;
					$this->Controller->set('payment_confirmation_query',$payment_confirmation_query);
					
					$payment_confirmation_status = $payment_confirmation['payment_confirmation'];
					$bharatkosh_payment_done = $payment_confirmation['bharatkosh_payment_done'];
					$payment_amount = $payment_confirmation['amount_paid'];
					$payment_transaction_id = $payment_confirmation['transaction_id'];
					$payment_trasaction_date = explode(' ',$payment_confirmation['transaction_date']);
					$payment_receipt_docs = $payment_confirmation['payment_receipt_docs'];
					$reason_list_comment = $payment_confirmation['reason_option_comment'];
					$reason_comment = $payment_confirmation['reason_comment'];
					$pao_to_whom_payment = $pao_alias_name[$payment_confirmation['pao_id']];
					
					$selected_pao = $DmiPaoDetails->find('all',array('fields'=>'pao_alias_name','conditions'=>array('id IS'=>$payment_confirmation['pao_id'])))->first();
					$selected_pao_alias_name = $selected_pao['pao_alias_name'];
					$this->Controller->set('bharatkosh_payment_done',$bharatkosh_payment_done);
					$this->Controller->set('payment_amount',$payment_amount);
					$this->Controller->set('payment_transaction_id',$payment_transaction_id);
					$this->Controller->set('selected_pao_alias_name',$selected_pao_alias_name);
					$this->Controller->set('payment_trasaction_date',$payment_trasaction_date);
					$this->Controller->set('payment_receipt_docs',$payment_receipt_docs);
					$this->Controller->set('reason_list_comment',$reason_list_comment);
					$this->Controller->set('reason_comment',$reason_comment);
					$this->Controller->set('payment_confirmation_status',$payment_confirmation_status);
					
				}else{
					
					$payment_confirmation_status = 'payment_not_submit';
					$this->Controller->set('payment_confirmation_status',$payment_confirmation_status);
				}
				
				$fetch_pao_referred_back = array();
				$fetch_pao_referred_back = $DmiApplicantPaymentDetails->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,'payment_confirmation'=>'not_confirmed',$grantDateCondition)))->toArray();
				$this->Controller->set('fetch_pao_referred_back',$fetch_pao_referred_back);	
				$this->Controller->set('pao_to_whom_payment',$pao_to_whom_payment);
				
			}
			
			
			public function renewalFormFinalSubmit($form_table){
				
				$customer_id = $this->Session->read('username');
				
				$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
				
				$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');//initialize model in component
				$Dmi_renewal_final_submit = TableRegistry::getTableLocator()->get('DmiRenewalFinalSubmits');//initialize model in component
				$DmiApplicantPaymentDetails = TableRegistry::getTableLocator()->get('DmiRenewalApplicantPaymentDetails');//initialize model in component
				$Dmi_ro_office = TableRegistry::getTableLocator()->get('Dmi_ro_office');//initialize model in component
				$Dmi_renewal_all_current_position = TableRegistry::getTableLocator()->get('DmiRenewalAllCurrentPositions');//initialize model in component
				$DmiSmsEmailTemplates = TableRegistry::getTableLocator()->get('DmiSmsEmailTemplates');//initialize model in component
				$Dmi_renewal_form_table = TableRegistry::getTableLocator()->get($form_table);//initialize model in component
				$DmiPaoDetails = TableRegistry::getTableLocator()->get('DmiPaoDetails');//initialize model in component
				$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');//initialize model in component
								
				//taking id list to take out max id	
						
					$list_firm_id = $Dmi_renewal_form_table->find('list', array('fields'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
					$list_payment_id = $DmiApplicantPaymentDetails->find('list', array('fields'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->toArray();
							
					$payment_form_status = array('saved','confirmed');
					
				if(!empty($list_firm_id) && !empty($list_payment_id)){

				
					//joining tables to check form status

					$tablejoins = $Dmi_firm->find('all', array('joins' => array(
						
								array(
									'table' => lcfirst($form_table).'s',
									'alias' => $form_table,
									'type' => 'inner',
									'foreignKey' => false,
									'conditions'=> array($form_table.'.form_status' => 'saved',
														 $form_table.'.id'=>MAX($list_firm_id),
														 $form_table.'.customer_id'=>$customer_id)
								),
								array(
									'table' => 'dmi_renewal_applicant_payment_details',
									'alias' => 'Dmi_renewal_applicant_payment_detail',
									'type' => 'inner',
									'foreignKey' => false,
									'conditions'=> array('Dmi_renewal_applicant_payment_detail.payment_confirmation' => $payment_form_status, 
														'Dmi_renewal_applicant_payment_detail.id'=>MAX($list_payment_id),
														'Dmi_renewal_applicant_payment_detail.customer_id'=>$customer_id)
								),
								
							),
								'conditions'=>array('Dmi_firm.customer_id IS'=>$customer_id)
						)
					); 		
					
						
					if(!empty($tablejoins)){
						
						$final_submit_entry_id = $Dmi_renewal_final_submit->find('list', array('fields'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)));
						
						//find RO to which this applicant belongs
						$split_customer_id = split('/',$customer_id);						
						$district_ro_code = $split_customer_id[2];						
						$find_ro_email_id = $Dmi_ro_office->find('first',array('fields'=>'ro_email_id','conditions'=>array('short_code IS'=>$district_ro_code)));						
						$ro_email_id	=	$find_ro_email_id['Dmi_ro_office']['ro_email_id'];
						
						//find PAO email id (Done By pravin 28/10/2017)
						$pao_id = $DmiApplicantPaymentDetails->find('first', array('fields'=>'pao_id', 'conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)));
						$pao_user_id = $DmiPaoDetails->find('first',array('fields'=>'pao_user_id', 'conditions'=>array('id IS'=>$pao_id['Dmi_renewal_applicant_payment_detail']['pao_id'])));
						$pao_user_email_id = $DmiUsers->find('first',array('fields'=>'email', 'conditions'=>array('id IS'=>$pao_user_id['DmiPaoDetails']['pao_user_id'])));
						
						if(!empty($final_submit_entry_id)){
							
							if($Dmi_renewal_final_submit->save(array(
					
								'customer_id'=>$customer_id,
								'status'=>'replied',
								'created'=>date('Y-m-d H:i:s'),
								'current_level'=>'level_3'
												
							))){
												
								$user_email_id = $ro_email_id;
								$current_level = 'level_3';
								$Dmi_renewal_all_current_position->current_user_update($customer_id,$user_email_id,$current_level);//call to custom function from model
										
								//added on 22-08-2017 by Pravin to send SMS/Email
								//call custom function from Model with message id
								$DmiSmsEmailTemplates->send_message(34,$customer_id);
								
								
							}
						}else{
							
							if($Dmi_renewal_final_submit->save(array(
					
							'customer_id'=>$customer_id,
							'status'=>'pending',
							'created'=>date('Y-m-d H:i:s'),
							'current_level'=>'level_1'
								
							))){
								
								if($DmiApplicantPaymentDetails->save(array(
									'id'=>MAX($list_payment_id),
									'payment_confirmation'=>'pending',
									'modified'=>date('Y-m-d H:i:s')
								))){ }


								//Entry in all renewal applications current position table
								//applied on 17-05-2017 by amol							
								$user_email_id = $pao_user_email_id['DmiUsers']['email'];
								$current_level = 'pao';
								$Dmi_renewal_all_current_position->current_user_entry($customer_id,$user_email_id,$current_level);//call to custom function from model
								
								//added on 22-08-2017 by Pravin to send SMS/Email
								//call custom function from Model with message id
								$DmiSmsEmailTemplates->send_message(32,$customer_id);		
								$DmiSmsEmailTemplates->send_message(48,$customer_id);//added on 23-07-2018 by Amol
							
						
							}
						}
						
						
						return true;
						
					}else{
						
						return false;
					}
				
				}
				
				
				
			}
			
			public function all_renewal_form_status($form_table){
				
				$Dmi_firm = TableRegistry::getTableLocator()->get('Dmi_firm');//initialize model in component
				
				$DmiApplicantPaymentDetails = TableRegistry::getTableLocator()->get('Dmi_renewal_applicant_payment_detail');//initialize model in component
				$Dmi_renewal_form_table = TableRegistry::getTableLocator()->get($form_table);//initialize model in component
				
				$customer_id = $this->Session->read('username');
				
				
				//taking id list to take out max id	
						
					$list_firm_id = $Dmi_renewal_form_table->find('list', array('fields'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)));
					$list_payment_id = $DmiApplicantPaymentDetails->find('list', array('fields'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)));
							
					$payment_form_status = array('saved','confirmed');
					
				if(!empty($list_firm_id) && !empty($list_payment_id)){

				
					//joining tables to check form status

					$tablejoins = $Dmi_firm->find('first', array('joins' => array(
						
								array(
									'table' => lcfirst($form_table).'s',
									'alias' => $form_table,
									'type' => 'inner',
									'foreignKey' => false,
									'conditions'=> array($form_table.'.form_status' => 'saved',
														 $form_table.'.id'=>MAX($list_firm_id),
														 $form_table.'.customer_id'=>$customer_id)
								),
								array(
									'table' => 'dmi_renewal_applicant_payment_details',
									'alias' => 'Dmi_renewal_applicant_payment_detail',
									'type' => 'inner',
									'foreignKey' => false,
									'conditions'=> array('Dmi_renewal_applicant_payment_detail.payment_confirmation' => $payment_form_status, 
														'Dmi_renewal_applicant_payment_detail.id'=>MAX($list_payment_id),
														'Dmi_renewal_applicant_payment_detail.customer_id'=>$customer_id)
								),
								
							),
								'conditions'=>array('Dmi_firm.customer_id'=>$customer_id)
						)
					); 		
					
						
					if(!empty($tablejoins)){
												
						return true;
						
					}else{
						
						return false;
					}
				
				}
				
				
				
			}
			

	}
		
?>