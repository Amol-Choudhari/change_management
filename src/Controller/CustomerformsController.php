<?php
//session_start();
namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;

class CustomerformsController extends AppController{
		
	var $name = 'Customerforms';		
	//var $layout = 'customer_forms_layout';		
	
    public function initialize(): void
    {
        parent::initialize();
		   
		// public $helpers = array('Form','Html','Time');
		$this->viewBuilder()->setHelpers(['Form','Html']);
    }
	
	public function beforeFilter(EventInterface $event) {
		parent::beforeFilter($event);
	
		if ($this->Session->read('username') == null) {
					
			echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->getRequest()->getAttribute('webroot');?>">Please Login</a><?php
			exit();
					
		} else {//to allow only logged in Applicant
		
			$this->loadComponent('Customfunctions');
			$customer_last_login = $this->Customfunctions->customerLastLogin();
			$this->set('customer_last_login',$customer_last_login);
			
			if (!$this->request->isAjax())//not restrict the ajax request
			{
				$action = $this->request->getParam('action');
				if ($action=='addFirm' || $action=='addedFirms' || $action=='fetchFirmId' || 
					$action=='deleteFirmId' || $action=='deleteFirm')//these actions used by primary Applicant
				{
					//checking applicant id pattern ex.102/2017
					if (preg_match("/^[0-9]+\/[0-9]+$/", $this->Session->read('username'),$matches)==1)
					{						
						//Give Permission
					} else {
						echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->getRequest()->getAttribute('webroot');?>">Please Login</a><?php
						exit();
					}

				//for secondary Applicant	
				} else {
					//checking applicant id pattern ex.102/1/PUN/006
					if (preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $this->Session->read('username'),$matches)==1)
					{	
						//to check the application is new, not old //on 17/10/2017
						$check_applicant_is_new = $this->DmiFirms->find('first',array('conditions'=>array('customer_id IS'=>$this->Session->read('username'),'is_already_granted'=>'no')));
						if (!empty($check_applicant_is_new)) {
							//Give Permission
						} else {
							echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->getRequest()->getAttribute('webroot');?>">Please Login</a><?php
							exit();
						}
						
					} else {
						echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->getRequest()->getAttribute('webroot');?>">Please Login</a><?php
						exit();
					}
					
					// To check valid CA applicant (by Pravin 24-07-2017)
					$valid_applicant_type = explode('/',$this->Session->read('username'));
					 
					if ($valid_applicant_type[1]!=1) {
					 
					 echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->getRequest()->getAttribute('webroot');?>">Please Login</a><?php
					 exit();
					 
					}
				}
			}
		}

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 	check to switch payment btn 																														//
		/*	$check_final_submit_entry = $this->Dmi_final_submit->find('first',array('conditions'=>array('customer_id'=>$this->Session->read('username')))); 	//	
		//																																						//
		//	if (!empty($check_final_submit_entry)) 																												//
		//	{																																					//
		//																																						//
		//	$switch_payment_btn = 'yes';																														//		
		//																																						//
		//	}else{																																				//			
		//																																						//
		//		$switch_payment_btn = 'no';																														//
		//	}																																					//
		//																																						//	
		//	$this->set('switch_payment_btn',$switch_payment_btn);																								//
		//																																						//
		//																																						//
		//	check application have export unit 																													//
		//	$export_unit_status = $this->check_applicant_export_unit($this->Session->read('username'));															//	
		//	$this->set('export_unit_status',$export_unit_status);																								//
		*/																																						//
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	}

	//Before Filter function End
		
	public function fetchFirmId($id){
		
		$this->Session->write('firm_table_id',$id);
		$this->Redirect(array('controller'=>'customerforms','action'=>'added_firms'));
		
	}
		

	public function addedFirms(){
		
        // SET MENU NAME FOR CURRENT ACTIVE MENU IN SIDEBAR
        $this->set('current_menu', 'menu_firm');

    	$this->viewBuilder()->setLayout('corporate_customer');
		
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
		$this->loadModel('DmiSponsoredPrintingFirms');
		
		$firm_table_id = $this->Session->read('firm_table_id');
		
		$firm_id_result = $this->DmiFirms->find('all',array('fields'=>'customer_id', 'conditions'=>array('id IS'=>$firm_table_id)))->first();
		
		$firm_id = $firm_id_result['customer_id'];

		$this->Session->write('customer_id',$firm_id);
		
		$added_firms = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$firm_id)))->toArray();					
		$added_firm_field = $added_firms[0];
		
		//get personal details masked by custom function to show in secure mode
		//applied on 12-10-2017 by Amol
		$added_firms[0]['mobile_no'] = $this->Customfunctions->getMaskedValue(base64_decode($added_firms[0]['mobile_no']),'mobile'); //This is addded on 27-04-2021 for base64decoding by AKASH
		$added_firms[0]['email'] = $this->Customfunctions->getMaskedValue(base64_decode($added_firms[0]['email']),'email');
		$this->set('added_firms',$added_firms);



		// get list of sponsored CA , Done by Pravin Bhakare 18-10-2020
		$sponsored_ca_name = $this->DmiSponsoredPrintingFirms->find('all',array('fields'=>array('sponsored_ca'),'conditions'=>array('customer_id IS'=>$firm_id)))->first();

		if (!empty($sponsored_ca_name)) {

			$sponsored_ca_list = $this->DmiFirms->find();
			//$concatCaName = $sponsored_ca_list->func()->concat(['firm_name'=>'identifier','(','customer_id'=>'identifier',')']); 
			$sponsored_ca_list->select(['customer_id','firm_name'=>$sponsored_ca_list->func()->concat(['firm_name'=>'identifier','(','customer_id'=>'identifier',')'])]);
			$sponsored_ca_list->where(['delete_status IS NULL','certification_type IS'=>'1','customer_id'=>$sponsored_ca_name['sponsored_ca']]);
			$sponsored_cas = $sponsored_ca_list->all()->combine('customer_id', 'firm_name')->toArray();
			
		} else {
			$sponsored_cas = array();
		}

		$this->set('sponsored_cas',$sponsored_cas);


		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	added on 01-06-2017 by Amol 																			//	
		//	to decrypt aadhar number before showing on frontend 													//
		//																											//
		//	commented  on 23-03-2018 to avoid mandatory for aadhar 													//		
		/*	$decrypted_aadhar = $this->decrypt($added_firm_field['once_card_no']); 									//	
		//																											//
		//	$decrypted_aadhar = $this->get_masked_value($decrypted_aadhar,'aadhar');//applied on 12-10-2017 by Amol //
		//	$this->set('decrypted_aadhar',$decrypted_aadhar); 														//	
		*/////////////////////////////////////////////////////////////////////////////////////////////////////////////

		
		//taking id of multiple sub commodities	to show names in list	
		
		$sub_comm_id = explode(',',$added_firm_field['sub_commodity']);
	
		$sub_commodity_value = $this->MCommodity->find('list',array('valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toList();
			
		$this->set('sub_commodity_value',$sub_commodity_value);
			
			
		//taking id of multiple Packaging Materials types to show names in list	
		
		$packaging_type_id = explode(',',$added_firm_field['packaging_materials']);
		
		$packaging_materials_value = $this->DmiPackingTypes->find('list',array('valueField'=>'packing_type', 'conditions'=>array('id IN'=>$packaging_type_id)))->toList();
			
		$this->set('packaging_materials_value',$packaging_materials_value);

		//taking select values from id

		$certification_type_value = $this->DmiCertificateTypes->find('all',array('fields'=>'certificate_type', 'conditions'=>array('id IS'=>$added_firm_field['certification_type'])))->first();
		$this->set('certification_type_value',$certification_type_value);
		
		$commodity_value = $this->MCommodityCategory->find('all',array('fields'=>'category_name', 'conditions'=>array('category_code IS'=>$added_firm_field['commodity'],'display'=>'Y')))->first();
		$this->set('commodity_value',$commodity_value);
		
		//$sub_commodity_value = $this->Dmi_sub_commodity->find('first',array('fields'=>'sub_comm_name', 'conditions'=>array('id'=>$added_firm_field['sub_commodity'])));
		//$this->set('sub_commodity_value',$sub_commodity_value);
		
		$state_value = $this->DmiStates->find('all',array('fields'=>'state_name', 'conditions'=>array('id IS'=>$added_firm_field['state'],'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->first();
		$this->set('state_value',$state_value);
		
		$district_value = $this->DmiDistricts->find('all',array('fields'=>'district_name', 'conditions'=>array('id IS'=>$added_firm_field['district'],'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->first();
		$this->set('district_value',$district_value);
		
		$is_already_granted = $added_firm_field['is_already_granted'];
		$this->set('is_already_granted',$is_already_granted);
		
		$certificate_no = null;
		$date_of_grant = null;
		$old_certificate_details = $this->DmiOldApplicationCertificateDetails->find('all', array('conditions'=>array('customer_id IS'=>$firm_id)))->first();
		
		if (!empty($old_certificate_details))
		{	
			$certificate_no = $old_certificate_details['certificate_no'];
			$date_of_grant = $old_certificate_details['date_of_grant'];
		}
		
		$this->set('certificate_no',$certificate_no);
		$this->set('date_of_grant',$date_of_grant);
		
		$old_app_renewal_dates = $this->DmiOldApplicationRenewalDates->find('all', array('conditions'=>array('customer_id IS'=>$firm_id)))->toArray();
		$this->set('old_app_renewal_dates',$old_app_renewal_dates);
		
		// set variables to show popup messages from view file
		$message_theme = '';
		$message = '';
		$redirect_to = '';
		
		if (null !== $this->request->getData('ok')) {
			
			$this->redirect(array('controller'=>'customers','action'=>'primary_home'));

		} elseif (null !== $this->request->getData('update')) {
			
			//this check added on 19-06-2018 to avoid duplicate email id.
			$Checkemailexist =  $this->DmiFirms->find('all', array('fields' => 'email', 'conditions' => array('email IS' => $this->request->getData('email'),'id !='=>$firm_table_id)))->first();
			if ($Checkemailexist == null) {
			
				//commented  on 23-03-2018 to avoid mandatory for aadhar
				$htmlencoded_email = base64_encode(htmlentities($this->request->getData('email'), ENT_QUOTES));//for email encoding
				$htmlencoded_phone_no = htmlentities($this->request->getData('fax_no'), ENT_QUOTES);
				
				///////////////////////////////////////////////////////////////
				//added on 01-06-2017 by Amol 								 //		
				//to encrypt aadhar number before storing to DB and Session  //
				//commented  on 23-03-2018 to avoid mandatory for aadhar     //
				//$encrypted_aadhar = $this->encrypt($htmlencoded_aadhar_no);// 
				///////////////////////////////////////////////////////////////	

				$db_email_before_update = $this->DmiFirms->find('all', array('fields'=>'email', 'conditions'=>array('id IS'=>$firm_table_id)))->first();
			
				//below query & conditions added on 12-10-2017 by Amol
				//To check if mobile,aadhar & email post in proper format, if not then save old value itself from DB
				$added_firms = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$firm_id)))->first();
				
				if (preg_match("/^[X-X]{6}[0-9]{4}$/i", $this->request->getData('mobile_no'),$matches)==1) {						
					$htmlencoded_mobile_no = $added_firms['mobile_no'];
				}

				
				/////////////////////////////////////////////////////////////////////////////////////////////////
				/* //commented  on 23-03-2018 to avoid mandatory for aadhar 								   //			
				// if (preg_match("/^[X-X]{8}[0-9]{4}$/i", $this->request->data['once_card_no'],$matches)==1)  //
				// {																						   //
				//	$encrypted_aadhar = $added_firms['once_card_no'];										   //		
				// }*/																						   //
				/////////////////////////////////////////////////////////////////////////////////////////////////


				//for email encoding
				$email_masked_value = $this->Customfunctions->getEmailMasked(base64_decode($added_firms['email']));//called custom function
				if ($email_masked_value == $this->request->getData('email')) {
					
					$htmlencoded_email = $added_firms['email'];
				}
				
				//added on 06-05-2021 for profile pic
				if ($this->request->getData('profile_pic')->getClientFilename() != null) {

					$attachment = $this->request->getData('profile_pic');
					$file_name = $attachment->getClientFilename();
					$file_size = $attachment->getSize();
					$file_type = $attachment->getClientMediaType();
					$file_local_path = $attachment->getStream()->getMetadata('uri');

					$profile_pic = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

				} else {
					$profile_pic = $added_firms['profile_pic'];
				}
							
							
					
				$DmiFirmsEntitny = $this->DmiFirms->newEntity(array($this->request->getData(),
				
					'id'=>$firm_table_id,
				//	'once_card_no'=>$encrypted_aadhar, //commented  on 23-03-2018 to avoid mandatory for aadhar
					'email'=>$htmlencoded_email,
				//	'mobile_no'=>$htmlencoded_mobile_no,
					'fax_no'=>base64_encode($htmlencoded_phone_no), //This is added on 27-04-2021 for base64encoding by AKASH
					'modified'=>date('Y-m-d H:i:s'),
					'profile_pic'=>$profile_pic

				));
				
				if ($this->DmiFirms->save($DmiFirmsEntitny)) {
					
					//Save the firm profile update logs history (Done by pravin 13/2/2018)
					$DmiFirmsHistoryLogsEntitny = $this->DmiFirmHistoryLogs->newEntity(array(											
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
						'fax_no'=>base64_encode($htmlencoded_phone_no), //This is added on 27-04-2021 for base64encoding by AKASH
						'export_unit'=>$added_firms['export_unit'],
						'total_charges'=>$added_firms['total_charges'],
						'is_already_granted'=>$added_firms['is_already_granted'],
						'created'=>date('Y-m-d H:i:s'),
						'modified'=>date('Y-m-d H:i:s'),
						'profile_pic'=>$profile_pic
					));
					
					$this->DmiFirmHistoryLogs->save($DmiFirmsHistoryLogsEntitny);
					
					$db_email_after_update = $this->DmiFirms->find('all', array('fields'=>'email', 'conditions'=>array('id IS'=>$firm_table_id)))->first();
					
					if ($db_email_before_update['email'] != $db_email_after_update['email']) {
			
						$message = '& your Email Id is Changed. New password reset link sent on new email id.';
					}
					
					//added on 22-08-2017 by Pravin to send SMS/Email
					//call custom function from Model with message id
					//$this->DmiSmsEmailTemplates->sendMessage(4,$firm_id);
				
					//Added this call to save the user action log on 01-03-2022
					$this->Authentication->userActionPerformLog('Update Firm Details','Success');
					$message = 'Firm details are updated '. $message;
					$message_theme = 'success';
					$redirect_to = 'added_firms';
					
				} else {
					//Added this call to save the user action log on 01-03-2022
					$this->Authentication->userActionPerformLog('Update Firm Details','Failed');
					$message = 'Sorry... Firm details are not updated';
					$message_theme = 'failed';
					$redirect_to = 'added_firms';
				}
					
			} else {
				//Added this call to save the user action log on 01-03-2022
				$this->Authentication->userActionPerformLog('Update Firm Details','Failed');
				$message = 'This email id is already exist. Please provide another email id to update. Thankyou.';
				$message_theme = 'failed';
				$redirect_to = 'added_firms';
			}
		}
		// set variables to show popup messages from view file
		$this->set('message_theme',$message_theme);
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);
		
		if ($message != null) {
			$this->render('/element/message_boxes');
		}
	}
		
	
	public function addFirm(){
		
        // SET MENU NAME FOR CURRENT ACTIVE MENU IN SIDEBAR
        $this->set('current_menu', 'menu_firm');
		//Set the Layout				
        $this->viewBuilder()->setLayout('corporate_customer');
		//Load Models
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
		
		//Load Component
		$this->loadComponent('Customfunctions');
		
		//$commodities = $this->Dmi_commodity->find('list',array('fields'=>'commodity_name','conditions'=>array('display'=>'Y')));
		//$this->set('commodities',$commodities);
		
		$commodity_categories = $this->MCommodityCategory->find('list',array('valueField'=>'category_name','conditions'=>array('display'=>'Y')))->toArray();
		$this->set('commodity_categories',$commodity_categories);
		
		//$sub_commodities = $this->Dmi_sub_commodity->find('list',array('fields'=>'sub_comm_name', 'conditions'=>array('commodity_id'=>1)));
		
		//$this->set('sub_commodities',$sub_commodities);
		
		$certificate_type = $this->DmiCertificateTypes->find('list',array('valueField'=>'certificate_type','conditions'=>array()))->toArray();
		$this->set('certificate_type',$certificate_type);
		
		$packaging_materials = $this->DmiPackingTypes->find('list',array('valueField'=>'packing_type','conditions'=>array('OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->toArray();
		$this->set('packaging_materials',$packaging_materials);
		
		// Apply "Order by" clause to get state list by order wise (Done By Pravin 10-01-2018)
		$states = $this->DmiStates->find('list', array('valueField'=>'state_name','conditions'=>array('OR'=>array('delete_status IS NULL','delete_status'=>'no')),'order'=>array('state_name')))->toArray();
		$this->set('states',$states);
	
		$districts = $this->DmiDistricts->find('list', array('valueField'=>'district_name', 'conditions'=>array('state_id'=>1,'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->toArray();
		$this->set('districts',$districts);
		
		// get list of sponsored CA , Done by Pravin Bhakare 18-10-2020
		$sponsored_ca_list = $this->DmiFirms->find();
		$sponsored_ca_list->select(['customer_id','firm_name'=>$sponsored_ca_list->func()->concat(['firm_name'=>'identifier','(','customer_id'=>'identifier',')'])]);
		$sponsored_ca_list->where(['delete_status IS NULL','certification_type'=>'1']);
		$sponsored_cas = $sponsored_ca_list->all()->combine('customer_id', 'firm_name')->toArray();
		$this->set('sponsored_cas',$sponsored_cas);
		
		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';
			

		if (null !== $this->request->getData('save')){
			
			//this check added on 19-06-2018 to avoid duplicate email id.
			$Checkemailexist =  $this->DmiFirms->find('all', array('fields' => 'email', 'conditions' => array('email IS' => $this->request->getData('email'))))->toArray();
			
			if ($Checkemailexist == null) {
				
				//applied condition to check all post data for !empty validation on server side on 24/10/2017 by Amol
				if (!empty($this->request->getData('certification_type')) && !empty($this->request->getData('is_already_granted')) && !empty($this->request->getData('firm_name')) &&
					/*!empty($this->request->data['once_card_no']) && $this->request->data['aadhar_auth_check']==1 &&*/ !empty($this->request->getData('email')) && !empty($this->request->getData('mobile_no')) &&
					!empty($this->request->getData('total_charge')) && !empty($this->request->getData('street_address')) && !empty($this->request->getData('state')) &&
					!empty($this->request->getData('district')) && !empty($this->request->getData('postal_code'))) {
					
					//this conditions are for empty commodity & packaging_materials check//on 28-11-2017
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
					
					if (!$this->Customfunctions->validateUniquePostData($this->request->getData('email'),'email')== 1){
						
						$this->set('return_error_msg','Please enter proper Email id');
						return null;
						exit;
					}


					//////////////////////////////////////////////////////////////////////////////////////////////////
					//commented  on 23-03-2018 to avoid mandatory for aadhar 										//					
					/*if (!$this->validate_unique_post_data($this->request->data['once_card_no'],'aadhar')== 1){ 	//
					//	echo "<script>alert('Please enter proper Aadhar Card no.')</script>";						//
					//	return false;																				//
					//	exit;																						//
					//}*/																							//	
					//////////////////////////////////////////////////////////////////////////////////////////////////


					if ($this->Session->read('username')!= '') {
					
						//Creating Customer secondary id by fetching primary id.		
			
						$customer_primary_id 		= 	$_SESSION['username'];
						
						$split_primary_id			= 	explode('/',$customer_primary_id);	
						
						$splited_primary_id_value	= 	$split_primary_id[0];
						
						$certificate_type_id 		= 	$this->request->getData('certification_type');
						
						$district_short_name_query	= 	$this->DmiDistricts->find('all',array('conditions'=>array('id IS'=>$this->request->getData('district'),'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->first(); 

						//new condition added on 21-04-2020 by Amol
						if (!empty($district_short_name_query['so_id'])) {
							$district_office = $district_short_name_query['so_id'];
						} else {
							$district_office = $district_short_name_query['ro_id'];
						}
						
						$district_ro_id 			= 	$district_office;
						
						$ro_short_code_query		=	$this->DmiRoOffices->find('all',array('conditions'=>array('id IS'=>$district_ro_id,'OR'=>array('delete_status IS NULL','delete_status'=>'no'))))->first(); 

						$district_short_name		=	$ro_short_code_query['short_code'];

						
						$max_id = $this->DmiFirms->find('list', array('valueFields'=>'id', 'conditions'=>array('customer_primary_id IS'=>$customer_primary_id)))->toArray();
						
						if (!empty($max_id)) {
							
							$max_customer_id = $this->DmiFirms->find('all', array('conditions'=>array('id'=>max($max_id))))->first();
														
							//$fetch_last_secondary_id_query 	= 	$this->DmiFirms->find('first', array('fields'=>'MAX(customer_id) as customer_id', 'conditions'=>array('customer_primary_id'=>$customer_primary_id)));
							
							$fetch_last_secondary_id = $max_customer_id['customer_id'];
									
							$split_secondary_id	= explode('/',$fetch_last_secondary_id);
							
							$splited_secondary_id_value	= $split_secondary_id[3];
						
						} else {
							
							$splited_secondary_id_value = 0;
						}
						
						$new_secondary_id_value	=	sprintf('%03d', $splited_secondary_id_value + 1);
						
						$customer_secondary_id 	= 	$splited_primary_id_value .'/'.$certificate_type_id.'/'.$district_short_name.'/'.$new_secondary_id_value;
						
			
						//if certification type is printing press the no commodity	
						
						$split_new_generated_id = explode('/',$customer_secondary_id);
							
						if ($split_new_generated_id[1] != 2) {

							// Calculate total charges for selected sub commodities
						
							$selected_commodity = $this->request->getData('selected_commodity');
							
							//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
							//	/* $i=0; 																																		//
							//	$total_charges = 0;																																//
							//	foreach($selected_commodity as $sub_commodity_id){																								//
							//																																					//
							//		$sub_commodity_charge = $this->Dmi_sub_commodity->find('first',array('fields'=>'charge','conditions'=>array('id'=>$sub_commodity_id)));		//
							//																																					//	
							//		$total_charges = $total_charges + $sub_commodity_charge['Dmi_sub_commodity']['charge'];														//
							//																																					//
							//		$i=$i+1;																																	//	
							//	} */																																			//		
							//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

							//$total_charges = '10000'; // currently default 10000 for all.
							
							$commodity_value = $this->request->getData('commodity');
							$sub_commodities_values = implode(',',$selected_commodity);
							$packaging_materials_values = null;
				
						} else {
							
							//$commodity_value = $this->request->data['commodity'];	
							$commodity_value = 1;	
							$sub_commodities_values = $this->request->getData('selected_commodity');
							$packaging_materials = $this->request->getData('packaging_materials');									
							$packaging_materials_values = implode(',',$packaging_materials);
							
							//$total_charges = '10000'; // currently default 10000 for all.
						}
							
						//to check to string contain first character ',', then remove that ','
						//added on 22-11-2017 by Amol									
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
						//$htmlencoded_aadhar_no = htmlentities($this->request->data['once_card_no'], ENT_QUOTES); //commented  on 23-03-2018 to avoid mandatory for aadhar
						$htmlencoded_email = base64_encode(htmlentities($this->request->getData('email'), ENT_QUOTES));//for email encoding
						$htmlencoded_mobile_no = htmlentities($this->request->getData('mobile_no'), ENT_QUOTES);
						$htmlencoded_fax_no = htmlentities($this->request->getData('fax_no'), ENT_QUOTES);
						$htmlencoded_other_packaging_details = htmlentities($this->request->getData('other_packaging_details'), ENT_QUOTES);
							
						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						//taking charges from application charges table 																												//			
						//added on 13-06-2017																																			//
						//$get_charges = $this->Dmi_application_charge->find('first',array('conditions'=>array('certificate_type_id'=>$this->request->data['certification_type'])));	//
						//$total_charges = $get_charges['Dmi_application_charge']['charge'];																							//
						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


						//added on 09-08-2017 by Amol
						$total_charges = htmlentities($this->request->getData('total_charge'), ENT_QUOTES);

						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						//added on 01-06-2017 by Amol 																							//
						//to encrypt aadhar number before storing to DB and Session 															//
						//$encrypted_aadhar = $this->encrypt($htmlencoded_aadhar_no); //commented  on 23-03-2018 to avoid mandatory for aadhar  //
						//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


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
						//changed condition on 31-08-2017 by amol(export unit only for CA & Lab)
						if ($this->request->getData('certification_type')==1 || $this->request->getData('certification_type')==3) {
							$post_input_request = $this->request->getData('export_unit');				
							$export_unit = $this->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
							if ($export_unit == null){ return false;}
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
								if (empty($sponsored_ca)){
									$this->set('return_error_msg','Sponsored CA not selected');	
									return null;
									exit;
								}else{
									$valid_ca = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$sponsored_ca)))->first();
									if (empty($valid_ca)){
										$this->set('return_error_msg','Invalid sponsored CA selected');	
										return null;
										exit;
									}else{
										$press_is_sponsored = "yes";
									}
								}									
							}
						}

							
						//this value is set on 06/05/2017 by Amol
						//$export_unit = 'no';//default set to no, currently not required.	

						// start , Get Details of old grant application
						// Donem By pravin 26-09-2017
						
						$post_input_request = $this->request->getData('is_already_granted');				
						$is_already_granted = $this->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
						if ($is_already_granted == null){ return null;}
						
						if ($is_already_granted == 'yes') {
							
							$old_certificate_no = htmlentities($this->request->getData('old_certificate_no'), ENT_QUOTES);
							
							// Start Apply check for to find duplicate cerification number (Done by pravin 14-07-2018)
							$duplicate_certification_no = $this->DmiOldApplicationCertificateDetails->find('all',array('conditions'=>array('certificate_no IS'=>$old_certificate_no)))->first();
							
							if (empty($duplicate_certification_no)) {
								
								$grant_date = $this->request->getData('grant_date');	
								// $grant_date = $this->Customfunctions->dateFormatCheck($grant_date); // commented on 29-01-2021 as it returns date format in ('d/m/Y') and it's not saving in DB prior to the PHP 8
								$grant_date = $this->Customfunctions->changeDateFormat($grant_date);
								$renewal_date_details = $this->request->getData('renewal_dates');
								
								$i=0;
								$valid_renewal_date = array();
								foreach($renewal_date_details as $renewal_year)
								{
									if (!empty($renewal_year)){
										if ($certification_type == 1)
										{	$update_renewal_date = '01/04/'.trim($renewal_year);
											$valid_renewal_date[$i] = $this->Customfunctions->dateFormatCheck($update_renewal_date);
											
										}elseif ($certification_type == 2)
										{	
											$update_renewal_date = '01/01/'.trim($renewal_year);
											$valid_renewal_date[$i] = $this->Customfunctions->dateFormatCheck($update_renewal_date);
											
										}elseif ($certification_type == 3)
										{	
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
							// End  duplicate cerification number
						}
							// end 26-09-2017
							
							
						//added on 06-05-2021 for profile pic
						if ($this->request->getData('profile_pic')->getClientFilename() != null) {

							$attachment = $this->request->getData('profile_pic');
							$file_name = $attachment->getClientFilename();
							$file_size = $attachment->getSize();
							$file_type = $attachment->getClientMediaType();
							$file_local_path = $attachment->getStream()->getMetadata('uri');

							$profile_pic = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

						} else {
							$profile_pic = '';
						}
							
						$DmiFirmsEntitny = $this->DmiFirms->newEntity(array($this->request->getData(),
						
							'customer_primary_id'=>$customer_primary_id,
							//'customer_primary_once_no'=>$this->Session->read('once_card_no'), //commented on 23-03-2018 to avoid mandatory for aadhar
							'customer_id'=>$customer_secondary_id,
						//	'password'=>'91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc',//Agmark123@
							'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2',//123
							'certification_type'=>$certification_type,
							'firm_name'=>$htmlencoded_firm_name,
							//'once_card_no'=>$encrypted_aadhar, //commented on 23-03-2018 to avoid mandatory for aadhar
							'commodity'=>$commodity_value,
							'sub_commodity'=>$sub_commodities_values,
							'packaging_materials'=>$packaging_materials_values,
							'other_packaging_details'=>$htmlencoded_other_packaging_details,
							'street_address'=>$htmlencoded_street_address,
							'state'=>$state,
							'district'=>$district,
							'postal_code'=>$htmlencoded_postal_code,
							'email'=>$htmlencoded_email,
							'mobile_no'=>base64_encode($htmlencoded_mobile_no),//This is added on 27-04-2021 for base64encoding by AKASH
							'fax_no'=>base64_encode($htmlencoded_fax_no),//This is added on 27-04-2021 for base64encoding by AKASH
							'export_unit'=>$export_unit,
							'total_charges'=>$total_charges,
							
							// Start Save flag status for old application Done by pravin 26-09-2017
							'is_already_granted'=>$is_already_granted,
							// end 26-09-2017
							
							'created'=>date('Y-m-d H:i:s'),
							'modified'=>date('Y-m-d H:i:s'),
							'profile_pic'=>$profile_pic
		
						));
							
			
						if ($this->DmiFirms->save($DmiFirmsEntitny)){
							
							//Save the firm profile update logs history (Done by pravin 13/2/2018)
							$DmiFirmHistoryLogsEntity = $this->DmiFirmHistoryLogs->newEntity(array(											
								'customer_primary_id'=>$customer_primary_id,
								//'customer_primary_once_no'=>$this->Session->read('once_card_no'), //commented on 23-03-2018 to avoid mandatory for aadhar
								'customer_id'=>$customer_secondary_id,
							//	'password'=>'91c8559eb34ab5e1ab86f9e80d9753c59b7da0d0e025ec8e7785f19e7852ca428587cdb4f02b5c67d1220ca5bb440b5592cd76b1c13878d7f10a1e568014f4dc', //Agmark123@
								'password'=>'3c9909afec25354d551dae21590bb26e38d53f2173b8d3dc3eee4c047e7ab1c1eb8b85103e3be7ba613b31bb5c9c36214dc9f14a42fd7a2fdb84856bca5c44c2',//123
								'certification_type'=>$certification_type,
								'firm_name'=>$htmlencoded_firm_name,
								//'once_card_no'=>$encrypted_aadhar, //commented on 23-03-2018 to avoid mandatory for aadhar
								'commodity'=>$commodity_value,
								'sub_commodity'=>$sub_commodities_values,
								'packaging_materials'=>$packaging_materials_values,
								'other_packaging_details'=>$htmlencoded_other_packaging_details,
								'street_address'=>$htmlencoded_street_address,
								'state'=>$state,
								'district'=>$district,
								'postal_code'=>$htmlencoded_postal_code,
								'email'=>$htmlencoded_email,
								'mobile_no'=>base64_encode($htmlencoded_mobile_no),//This is added on 27-04-2021 for base64encoding by AKASH
								'fax_no'=>base64_encode($htmlencoded_fax_no),//This is added on 27-04-2021 for base64encoding by AKASH
								'export_unit'=>$export_unit,
								'total_charges'=>$total_charges,
								'is_already_granted'=>$is_already_granted,
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s'),
								'profile_pic'=>$profile_pic
							));
								
							$this->DmiFirmHistoryLogs->save($DmiFirmHistoryLogsEntity);
								
							// Start, Save Old Grant Application Details
							// Done By pravin 26-09-2017
							if ($is_already_granted == 'yes') {
								
								$DmiOldApplicationCertificateDetailsEntitny = $this->DmiOldApplicationCertificateDetails->newEntity(array($this->request->getData(),
									'customer_id'=>$customer_secondary_id,
									'certificate_no'=>$old_certificate_no,
									'date_of_grant'=>$grant_date,
									'created'=>date('Y-m-d H:i:s'),
									'modified'=>date('Y-m-d H:i:s')
								));
									
								if ($this->DmiOldApplicationCertificateDetails->save($DmiOldApplicationCertificateDetailsEntitny)){
									
									if (!empty($valid_renewal_date)) {
										
										foreach($valid_renewal_date as $renewal_date) {
											
											$DmiOldApplicationRenewalDatesEntitny = $this->DmiOldApplicationRenewalDates->newEntity(array($this->request->getData(),
												'customer_id'=>$customer_secondary_id,
												'renewal_date'=>$renewal_date,
												'created'=>date('Y-m-d H:i:s')
											));
											
											//$this->DmiOldApplicationRenewalDates->create();
											if ($this->DmiOldApplicationRenewalDates->save($DmiOldApplicationRenewalDatesEntitny)){}
										}
									}
								}
							}
							// end 26-09-2017

							$this->loadModel('DmiApplWithRoMappings');
							$this->loadModel('DmiApplWithRoMappingLogs');

							// New section is added for getting the printer sponsored ca Done by Pravin  Bhakare 18-10-2021	
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
								
							//called function to send link for reset password on registered email on 13-02-2018 by Amol
							$this->loadComponent('Authentication');
							$this->Authentication->forgotPasswordLib('DmiFirms',$htmlencoded_email);
							
							$secondary_registered = 'done';
							$email = $this->request->getData('email');
							
							$this->set('secondary_registered',$secondary_registered);															
							$this->set('customer_secondary_id',$customer_secondary_id);															
							$this->set('email',$email);
							
							//This function is used to save Application with RO mapping record while new firm added.
							$this->DmiApplWithRoMappings->saveRecord($customer_secondary_id,$district_ro_id);
							
							//added on 22-08-2017 by Pravin to send SMS/Email
							//call custom function from Model with message id
							//$this->DmiSmsEmailTemplates->sendMessage(3,$customer_secondary_id);
							
							//Added this call to save the user action log on 01-03-2022
							$this->Authentication->userActionPerformLog('Add Firm','Success');
							$this->set('toastTheme', 'success');
							$this->set('toastMsg', 'Successfully added new firm !');
								
						} else {
							
							//Added this call to save the user action log on 01-03-2022
							$this->Authentication->userActionPerformLog('Add Firm','Failed');
							$message = 'Sorry... New firm Not created please try again';
							$redirect_to = 'add_firm';	
						}
			
					} else {
						
						$message = 'You are not logged in... Please login first';
						$redirect_to = '/';
					}

				} else {
					
					$this->set('return_error_msg','Please check some fields are not entered');					
					return null;
					exit;
				}
				
			} else {
				
				//Added this call to save the user action log on 01-03-2022
				$this->Authentication->userActionPerformLog('Add Firm','Failed');
				$message = 'This email id is already registered with us. Please create firm with another email id. Thankyou.';
				$redirect_to = 'add_firm';		
			}
		
		}
		
		// set variables to show popup messages from view file
		$this->set('message_theme',$message_theme);
		$this->set('return_error_msg',null);
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);

	}
				
		
	//created on 05/05/2017 by Amol	
	//To delete firms which was created but still not final submitted the application

	public function deleteFirmId($id) {

		$this->Session->write('firm_id',$id);

		// removed alert script as delete confirmaiton window already show to the user
		// @by Aniket Ganvir dated 11th DEC
		$this->redirect(array('controller'=>'customerforms', 'action'=>'delete_firm'));

	}
				
				
	public function deleteFirm(){
	
		$this->autoRender = false; 
		// $this->layout = 'corporate_customer';
		$this->viewBuilder()->setLayout('corporate_customer');
	
		// set variables to show popup messages from view file
		$message_theme = '';
		$message = '';
		$redirect_to = '';
		$this->loadModel('DmiFirms');

		$firm_id = $this->Session->read('firm_id');
	
		$DmiFirmsEntity = $this->DmiFirms->newEntity(array(
		
			'id'=>$firm_id,
			'delete_status'=>'yes',
			'modified'=>date('Y-m-d H:i:s')
		
		));

		if ($this->DmiFirms->save($DmiFirmsEntity)){
				
			$message_theme = 'success';
			$message = 'You have deleted the Firm Successfully.';
			$redirect_to = '../customers/primary_home';
			// $this->view = '/Element/message_boxes';
			
		}
		
		// set variables to show popup messages from view file
		$this->set('message_theme',$message_theme);
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);		

		// PRIOR TO THE CAKEPHP 4, "$this->view" IS NOT WORKING,
		// SO ADDED "render" PROPERTY TO POP UP FORM RELATED MESSAGES
		// by Aniket Ganvir dated 29th JAN 2021
		if ($message != null)
			$this->render('/element/message_boxes');		
	
	}
		
	
}

?>