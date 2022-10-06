<?php
namespace app\Controller\Component;
use Cake\Controller\Controller;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;
use QRcode;

class CustomfunctionsComponent extends Component {

	public $components= array('Session','Randomfunctions');
	public $controller = null;
	public $session = null;

	var $returnFalseMessage = "Please Check all the Fields Before Proceding";

	public function initialize(array $config): void {
		parent::initialize($config);
		$this->Controller = $this->_registry->getController();
		$this->Session = $this->getController()->getRequest()->getSession();
	}



	//Check Customer Available Method
	public function checkCustomerIdAvailable($customer_id) {

		if ( $customer_id == null ) {
			echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>">Please Login</a><?php
			exit();
		} else {
			return $customer_id;
		}
	}



	//Check Application Old New Method
	public function checkApplicationOldNew($customer_id) {

		//Load Model
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$get_firm_details = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$application_type = null;

		if ($get_firm_details['is_already_granted']=='yes') {
			$application_type = 'old';
		} else {
			$application_type = 'new';
		}

		return $application_type;
	}



	//If HO Level Role Assigned Method
	public function ifHoLevelRoleAssigned($user_email_id,$dy_ama,$jt_ama,$ama) {

		//Load Models
		$Dmi_user_role = TableRegistry::getTableLocator()->get('DmiUserRoles');
		$Dmi_ho_allocation = TableRegistry::getTableLocator()->get('DmiHoAllocation');
		//check Selected user roles
		$selected_user_roles = $Dmi_user_role->find('all',array('conditions'=>array('user_email_id IS'=>$user_email_id)))->first();

		//get flow wise tables
		$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$flow_wise_tables = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();

		foreach ($flow_wise_tables as $each_flow) {
			//initialize model in component
			$Dmi_ho_allocation = TableRegistry::getTableLocator()->get($each_flow['ho_level_allocation']);

			//check current HO users
			$get_current_ho_users = $Dmi_ho_allocation->find('all',array('order'=>array('id'=>'DESC')))->first();
			$current_dyama = null;
			$current_jtama = null;
			$current_ama = null;

			if (!empty($get_current_ho_users)) {

				$current_dyama = $get_current_ho_users['dy_ama'];
				$current_jtama = $get_current_ho_users['jt_ama'];
				$current_ama = $get_current_ho_users['ama'];
			}

			//for DY AMA
			if ($dy_ama=='yes') {

				//if DY AMA role option checked
				$proceed_to_assign = null;

				if (!empty($selected_user_roles)) {

					if ($selected_user_roles['dy_ama']=='yes') {
						//do nothing
					} else {

						if ($selected_user_roles['jt_ama']=='yes' || $selected_user_roles['ama']=='yes') {
							//user already have a role from HO level
							return 0;
						} else {
							//now the DY AMA role can be assigned/change to new user
							$proceed_to_assign = 'yes';
						}
					}

				} else {
					//now the DY AMA role can be assigned/change to new user
					$proceed_to_assign = 'yes';
				}

				if ($proceed_to_assign == 'yes') {

					//update new DY AMA id in HO allocation table for all application
					$Dmi_ho_allocation->updateAll(array('dy_ama' => "$user_email_id"),array('1=1'));
					$Dmi_ho_allocation->updateAll(array('current_level' => "$user_email_id"),array('current_level' => $current_dyama));
				}
			}
			
			//For JT AMA
			if ($jt_ama=='yes') {

				//if JT AMA role option checked
				$proceed_to_assign = null;

				if (!empty($selected_user_roles)) {

					if ($selected_user_roles['jt_ama']=='yes') {
						//do nothing
					} else {

						if ($selected_user_roles['dy_ama']=='yes' || $selected_user_roles['ama']=='yes') {
							//user already have a role from HO level
							return 0;
						} else {
							//now the JT AMA role can be assigned/change to new user
							$proceed_to_assign = 'yes';
						}
					}

				} else {
					//now the JT AMA role can be assigned/change to new user
					$proceed_to_assign = 'yes';
				}

				if ($proceed_to_assign == 'yes') {

					//update new JT AMA id in HO allocation table for all application
					$Dmi_ho_allocation->updateAll(array('jt_ama' => "$user_email_id"),array('1=1'));
					$Dmi_ho_allocation->updateAll(array('current_level' => "$user_email_id"),array('current_level' => $current_jtama));
				}
			}
			
			//For AMA
			if ($ama=='yes') {
				//if AMA role option checked
				$proceed_to_assign = null;

				if (!empty($selected_user_roles)) {

					if ($selected_user_roles['ama']=='yes') {
						//do nothing
					} else {

						if ($selected_user_roles['dy_ama']=='yes' || $selected_user_roles['jt_ama']=='yes') {
							//user already have a role from HO level
							return 0;
						} else {
							//now the AMA role can be assigned/change to new user
							$proceed_to_assign = 'yes';
						}
					}

				} else {
					//now the AMA role can be assigned/change to new user
					$proceed_to_assign = 'yes';
				}

				if ($proceed_to_assign == 'yes') {

					//update new AMA id in HO allocation table for all application
					$Dmi_ho_allocation->updateAll(array('ama' => "$user_email_id"),array('1=1'));
					$Dmi_ho_allocation->updateAll(array('current_level' => "$user_email_id"),array('current_level' => $current_ama));
				}
			}
		}

		return true;
	}



	// Check entry of dy_ama, jt_ama, ama into user_roles tabels for duplicate set roles for dy_ama, jt_ama, ama into user_roles Done by pravin 30-08-2017
	public function alreadySetDyamaJtamaAma() {

		//initialize model in component
		$Dmi_user_role = TableRegistry::getTableLocator()->get('DmiUserRoles');

		$already_dyama_roles_set = $Dmi_user_role->find('all',array('conditions'=>array('dy_ama'=>'yes')))->first();

		if (!empty($already_dyama_roles_set) ) {

			$dyama_set_roles_email = $already_dyama_roles_set['user_email_id'];

		} else {

			$dyama_set_roles_email = null;
		}


		$already_jt_ama_roles_set = $Dmi_user_role->find('all',array('conditions'=>array('jt_ama'=>'yes')))->first();

		if (!empty($already_jt_ama_roles_set)) {

			$jtama_set_roles_email = $already_jt_ama_roles_set['user_email_id'];

		} else {

			$jtama_set_roles_email = null;
		}

		$already_ama_roles_set = $Dmi_user_role->find('all',array('conditions'=>array('ama'=>'yes')))->first();

		if (!empty($already_ama_roles_set)) {

			$ama_set_roles_email = $already_ama_roles_set['user_email_id'];

		} else {

			$ama_set_roles_email = null;
		}

		return array($dyama_set_roles_email,$jtama_set_roles_email,$ama_set_roles_email);

	}


	//on 17-08-2018 by Amol //to get application district wise office
	public function getApplDistrictOffice($customer_id,$appl_type=null) {

		if (empty($appl_type)) {

			$appl_type = $this->Session->read('application_type');
		}

		//added conditions for chemist flow, get details from chemist registration table on 30-09-2021 by Amol
		if (preg_match("/^[A-Z]+\/[0-9]+\/[0-9]+$/", $customer_id,$matches)==1) {

			$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
			$getDetails = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$customer_id)))->first();
			$customer_id = $getDetails['created_by'];
		}

		//Load Models
		$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
		$Dmi_district = TableRegistry::getTableLocator()->get('DmiDistricts');
		$DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$DmichemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
		$DmiApplTransferLogs = TableRegistry::getTableLocator()->get('DmiApplTransferLogs');

		$flow_wise_table = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type)))->first();
		$allocation_table = $flow_wise_table['allocation'];

		$Dmi_allocation = TableRegistry::getTableLocator()->get($allocation_table);
		$grantDateCondition = $this->returnGrantDateCondition($customer_id);
		$secondPhaseLaunchDate = '07/06/2020';
		$phaseOneApplication = $Dmi_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'date(created) <'=>$secondPhaseLaunchDate,$grantDateCondition)))->first();

		//get district id from firm table
		$get_dist_id = $Dmi_firm->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$dist_id = $get_dist_id['district'];
		$applied_to = $get_dist_id['applied_to'];

		//now check RO/SO/SMD id in district table to set office for application
		//$this->loadModel('Dmi_district');
		$district_details = $Dmi_district->find('all',array('conditions'=>array('id IS'=>$dist_id)))->first();
		$ro_id = $district_details['ro_id'];
		$so_id = $district_details['so_id'];
		$smd_id = $district_details['smd_id'];

		$to_office = null;
		$tranferApp = $DmiApplTransferLogs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'appl_type IS'=>$appl_type,$grantDateCondition),'order'=>array('id desc')))->first();
		if (!empty($tranferApp)) {
			$to_office = $tranferApp['to_office'];
		}

		$district_office = null;
		if ($applied_to == 'DMI' || $applied_to == null) {

			/*if (!empty($ro_id) && empty($so_id)) {

				$district_office = 'RO';

			} elseif (!empty($so_id) && empty($ro_id)) {

				$district_office = 'SO';
			}*/
			//above commented and below updated on 21-04-2020 by Amol

			if (empty($phaseOneApplication)) {

				// Checked, It is transfered application
				// if yes then set the District Office equal to transfer office
				// Done by Pravin Bhakare 12-10-2021
				if (!empty($to_office)) {

					$roDetails = $DmiRoOffices->find('all',array('fields'=>array('office_type'),'conditions'=>array('id IS'=>$to_office)))->first();
					$district_office = $roDetails['office_type'];
					
				} elseif (!empty($so_id)) {

					$district_office = 'SO';

					//if appl is for lab (domestic/export), No 'SO' involded as per new scenario applied on 21-09-2021 by Amol
					$firm_type = $this->firmType($customer_id);
					if ($firm_type==3) {
						//set district officce to RO by default
						$district_office = 'RO';
					}

				} else {

					$district_office = 'RO';
				}

			} else {

				$district_office = 'RO';
			}

		} elseif ($applied_to == 'SMD') {

			if (!empty($smd_id)) {

				$district_office = 'SMD';
			}
		}

		return $district_office;
	}


	// Get firm certification type
	public function firmType($customer_id) {

		$split_customer_id = explode('/',$customer_id);

		if ($split_customer_id[1] == 1) {
			return 1;
		} elseif ($split_customer_id[1] == 2) {
			return 2;
		} elseif ($split_customer_id[1] == 3) {
			return 3;
		} elseif ($split_customer_id[0] == 'CHM') {
			return 1;
		}
	}


	// Get firm Heading
	public function firmTypeText($customer_id) {

		$split_customer_id = explode('/',$customer_id);

		if ($split_customer_id[1] == 1) {
			return "CA firm";
		} elseif ($split_customer_id[1] == 2) {
			return "Printing firm";
		} elseif ($split_customer_id[1] == 3) {
			return "Laboratory firm";
		} elseif ($split_customer_id[0] == 'CHM') {
			return "Chemist";
		}
	}


	// Get firm Heading
	public function getFolderName($customer_id) {

		$split_customer_id = explode('/',$customer_id);

		if ($split_customer_id[1] == 1) {
			return "CA";
		} elseif ($split_customer_id[1] == 2) {
			return "PP";
		} elseif ($split_customer_id[1] == 3) {
			return "LAB";
		} elseif ($split_customer_id[0] == 'CHM') {
			return "CHM";
		}
	}


	// Get application form type
	public function checkApplicantFormType($customer_id,$appl_type=null) {

		$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');

		$split_customer_id = explode('/',$customer_id);

		//ADDED ON 03-06-2021 BY ANKUR
		if ($customer_id == null) {
			$split_customer_id[1] = null;
			$form_type = null;
		}


		if ($split_customer_id[1] == 1) {

			if ($this->checkApplicantExportUnit($customer_id) == 'yes') {

				$form_type = 'F';

			} else {

				$check_application_type = $Dmi_firm->find('all',array('fields'=>array('commodity','export_unit'),'conditions'=>array('customer_id IS'=>$customer_id)))->first();
				
				//added id '11' on 05-09-2022 for Fat Spread updates after UAT
				if ($check_application_type['commodity'] == 106 || $check_application_type['commodity'] == 11) {

					$form_type = 'E';

				} else {

					$form_type = 'A';
				}
			}

		} elseif ($split_customer_id[1] == 2) {

			$form_type = 'B';

		} elseif ($split_customer_id[1] == 3) {

			//added this condition to check laboratory form type(export/Non export) //on 31-08-2017 by Amol
			if ($this->checkApplicantExportUnit($customer_id) == 'yes') {

				$form_type = 'C';

			} else {

				$form_type = 'D';
			}
		//pravin bhakare 30-09-2021
		} elseif ($split_customer_id[0] == 'CHM') {

			$form_type = 'CHM';
		}

		//check application type for other type of forms
		//added on 15-11-2021 for other modules applications form type
		if (empty($appl_type)) {
			$appl_type = $this->Session->read('application_type');
		}
		
		if ($appl_type==5) {

			$form_type = 'FDC';

		} elseif ($appl_type==6) {
			
			$form_type = 'EC';
		}

		return $form_type;
	}


	// Method to split customer id and check CA BEVO Applicant
	//below logic created on 27-03-2017 by Amol (by new flow)
	public function checkCaBevo($customer_id) {

		$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');

		$split_customer_id = explode('/',$customer_id);

		if ($split_customer_id[1] == 1) {

			$check_application_type = $Dmi_firm->find('all',array('fields'=>array('commodity','export_unit'),'conditions'=>array('customer_id IS'=>$customer_id)))->first();

			//added id '11' on 05-09-2022 for Fat Spread updates after UAT
			if ($check_application_type['commodity'] == 106 || $check_application_type['commodity'] == 11) {
				$applicant_bevo = 'yes';
			} else {
				$applicant_bevo = 'no';
			}

			return $applicant_bevo;
		}
	}


	// Check all sections status value is approved not not
	public function formStatusValue($sections,$customer_id) {

		$grantDateCondition = $this->returnGrantDateCondition($customer_id);

		$application_type = $this->Session->read('application_type');

		$return_value = 1; // if all section "saved"
		$form_save_count = 0;
		$form_approve_count = 0;


		foreach ($sections as $each_section) {

			$model_name = $each_section['section_model'];
			$section_model = TableRegistry::getTableLocator()->get($model_name);
			$section_form_details = $section_model->sectionFormDetails($customer_id);
			$section_form_status = $section_form_details[0]['form_status'];



			if ($section_form_status != 'saved' && $section_form_status != 'approved') {

				$return_value = 0;
				break;
			} elseif ($section_form_status == 'approved') {
				$form_approve_count = $form_approve_count+1;
			}
		}
		// exit;
		$payment_table = $sections[0]['payment_section'];
		$oldapplication = $this->isOldApplication($customer_id);

		if ($payment_table != "" and ($oldapplication=='no' || $application_type != 1)) {

			$payment = TableRegistry::getTableLocator()->get($payment_table);
			$payment_status = $payment->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'payment_confirmation IN'=>array('saved','confirmed')),'order'=>'id DESC'))->first();

			if (empty($payment_status)) {
				$return_value = 0;
			}
		}

		if ($form_approve_count == count($sections)) { $return_value = 2; }

		return $return_value;
	}


	// Get application final submit details
	public function finalSubmitDetails($customer_id,$field_name,$application_type=null) {

		$grantDateCondition = $this->returnGrantDateCondition($customer_id);

		if ($application_type==null) {

			$application_type = $this->Session->read('application_type');
		}

		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		$Dmi_final_submit = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb[$field_name]);
		$final_submit_deatil = $Dmi_final_submit->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition),'order'=>'id DESC'))->first();

		if (!empty($final_submit_deatil)) {
			$final_submit_deatils = $final_submit_deatil;
		} else {
			$final_submit_deatils = "";
		}

		return $final_submit_deatils;
	}


	// This function call after final submitted the application by applicant
	public function applicationFinalSubmitCall($customer_id,$all_section_status) {

		$grantDateCondition = $this->returnGrantDateCondition($customer_id);
		$application_type = $this->Session->read('application_type');
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		$Dmi_final_submit = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['application_form']);
		$Dmi_esign_status = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['esign_status']);
		$final_submitted_done = $this->applicationFinalSubmitted($customer_id,$all_section_status);

		$get_ids = $Dmi_final_submit->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition),'order'=>'id DESC'))->first();

		if (!empty($get_ids)) {
			$application_status = $get_ids['status'];
		} else {
			$application_status = 'pending';
		}


		//this condition added on 28-03-2018 by Amol
		if ($this->Session->read('with_esign')=='yes') {
			//update status of esign status table//added on 04-11-2017
			$Dmi_esign_status->updateAll(array('application_status' => "$application_status"),array('customer_id' => $customer_id));
		} else {
			//proceed
		}

		if ($final_submitted_done == 1 ) {

			$Dmi_temp_esign_status = TableRegistry::getTableLocator()->get('DmiTempEsignStatuses');
			$Dmi_temp_esign_status->DeleteTempEsignRecord($customer_id);

			return true;
		}

	}


	// This function call after validated esign status to enter final submitted value in final submit table
	public function applicationFinalSubmitted($customer_id,$all_section_status) {

		$grantDateCondition = $this->returnGrantDateCondition($customer_id);

		if ($all_section_status == 1) {

			$application_type = $this->Session->read('application_type');
			$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
			$Dmi_final_submit = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['application_form']);
			$Dmi_payment_tb = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['payment']);
			$Dmi_appl_current_pos = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['appl_current_pos']);
			$Dmi_sms_email_template = TableRegistry::getTableLocator()->get('DmiSmsEmailTemplates');
			$Dmi_pao_detail = TableRegistry::getTableLocator()->get('DmiPaoDetails');
			$Dmi_ro_office = TableRegistry::getTableLocator()->get('DmiRoOffices');
			$Dmi_user = TableRegistry::getTableLocator()->get('DmiUsers');
			$Dmi_allocation = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['allocation']);

			//below whole code changed on 07-07-2017 by Amol make pending/final reply on same final submit btn
			$final_submit_entry_id = $Dmi_final_submit->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->toArray();

			//find RO by district ro id in customer id:
			$split_customer_id = explode('/',$customer_id);
			$district_ro_code = $split_customer_id[2];

			//updated and added code to get Office table details from appl mapping Model
			$DmiApplWithRoMappings = TableRegistry::getTableLocator()->get('DmiApplWithRoMappings');

			/* [CHEMIST APPLICATION MODIFICATION] by Akash on 29-09-2021
			//	In Order to get the Office Details From the DMI APPLICATION WITH RO MAPPING table,
			//	The Packer ID is needed, So it being replaced the Customer ID (i.e Now Chemist ID),
			//	With Packer ID from Session .
			*/
			//Getting {packer_id} from Session. and replacing the packer id with chemist id
			if ($application_type == 4) {
				$packer_id = $this->Session->read('packer_id');
				$find_ro_email_id = $DmiApplWithRoMappings->getOfficeDetails($packer_id);
			} else {
				$find_ro_email_id = $DmiApplWithRoMappings->getOfficeDetails($customer_id);
			}

			$ro_email_id = $find_ro_email_id['ro_email_id'];


			//find PAO email id (Done By pravin 28/10/2017)
			$pao_id = $Dmi_payment_tb->find('all', array('fields'=>'pao_id', 'conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();

			if (!empty($pao_id)) {

				$pao_user_id = $Dmi_pao_detail->find('all',array('fields'=>'pao_user_id', 'conditions'=>array('id IS'=>$pao_id['pao_id'])))->first();
				$pao_user_email_id = $Dmi_user->find('all',array('fields'=>'email', 'conditions'=>array('id IS'=>$pao_user_id['pao_user_id'])))->first();
			}

			$list_payment_id = $Dmi_payment_tb->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->toArray();

			if (!empty($final_submit_entry_id)) {

				$Dmi_final_submit_Entity = $Dmi_final_submit->newEntity(array('customer_id'=>$customer_id,
																				'status'=>'replied',
																				'current_level'=>'level_3',
																				'created'=>date('Y-m-d H:i:s'),
																				'modified'=>date('Y-m-d H:i:s')));

				if ($Dmi_final_submit->save($Dmi_final_submit_Entity)) {

					$ro_email_id = $Dmi_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition)))->first();
					//Entry in all applications current position table//applied on 15-05-2017 by amol

					$user_email_id = $ro_email_id['level_3'];

					$current_level = 'level_3';
					//call to custom function from model
					$Dmi_appl_current_pos->currentUserUpdate($customer_id,$user_email_id,$current_level);
					//added on 22-08-2017 by Pravin to send SMS/Email
					//call custom function from Model with message id
					//$Dmi_sms_email_template->sendMessage(8,$customer_id);

					$this->singleWindowReplySubmit();

					return true;
				}
				
			} else {

				// Enter the record in final submit table
				$Dmi_final_submit_Entity = $Dmi_final_submit->newEntity(array('customer_id'=>$customer_id,
																				'status'=>'pending',
																				'current_level'=>'level_1',
																				'created'=>date('Y-m-d H:i:s'),
																				'modified'=>date('Y-m-d H:i:s')));

				if ($Dmi_final_submit->save($Dmi_final_submit_Entity)) {

					$oldapplication = $this->isOldApplication($customer_id);
					$paymentSection = $this->Session->read('paymentSection');

					if ($paymentSection == 'available') {

						$payment_id = MAX($list_payment_id);
						$Dmi_payment_tb_Entity = $Dmi_payment_tb->newEntity(array('id'=>$payment_id,'payment_confirmation'=>'pending','modified'=>date('Y-m-d H:i:s')));

						if ($Dmi_payment_tb->save($Dmi_payment_tb_Entity)) {

							$user_email_id = $pao_user_email_id['email'];
							$current_level = 'pao';
							//call to custom function from model
							$Dmi_appl_current_pos->currentUserEntry($customer_id,$user_email_id,$current_level);
						}

						//added on 22-08-2017 by Pravin to send SMS/Email
						//call custom function from Model with message id
						//$this->Dmi_sms_email_template->send_message(6,$customer_id);//commented on 23-07-2018 by Amol
						//$Dmi_sms_email_template->sendMessage(47,$customer_id);//added on 23-07-2018 by Amol
						//$Dmi_sms_email_template->sendMessage(48,$customer_id);//added on 23-07-2018 by Amol

					} else {

						//if appl is for lab (domestic/export), No 'SO' involded as per new scenario
						//to check appl type and get RO in-charge id to allocate
						//applied on 21-09-2021 by Amol
						$firm_type = $this->firmType($customer_id);
						if ($firm_type==3) {
							//get RO incharge id as per appln
							$ro_email_id = $this->getApplRegOfficeId($customer_id,$application_type);

						}

						$Dmi_allocation_Entity = $Dmi_allocation->newEntity(array('customer_id'=>$customer_id,
																					'level_3'=>$ro_email_id,
																					'current_level'=>$ro_email_id,
																					'created'=>date('Y-m-d H:i:s'),
																					'modified'=>date('Y-m-d H:i:s')));
						$Dmi_allocation->save($Dmi_allocation_Entity);

						$user_email_id = $ro_email_id;
						$current_level = 'level_3';
						//call to custom function from model
						$Dmi_appl_current_pos->currentUserEntry($customer_id,$user_email_id,$current_level);

						//added on 22-08-2017 by Pravin to send SMS/Email
						//call custom function from Model with message id
						//exit;
						//$Dmi_sms_email_template->sendMessage(8,$customer_id);
					}

					return true;
				}
			}
		}
	}



	// Method to Entry for referred back for applicant in final submit table
	public function sentToApplicant($customer_id,$current_level,$final_submit_table) {

		$Dmi_final_submit = TableRegistry::getTableLocator()->get($final_submit_table);

			$Dmi_final_submit_Entity = $Dmi_final_submit->newEntity(array('customer_id'=>$customer_id,
																			'status'=>'referred_back',
																			'current_level'=>$current_level,
																			'created'=>date('Y-m-d H:i:s'),
																			'modified'=>date('Y-m-d H:i:s')));

		if ($Dmi_final_submit->save($Dmi_final_submit_Entity)) { return true;}
	}


	// Method to save RO/SO reply
	public function updateRoReply($latest_id, $ro_reply,$rr_comment_ul,$customer_id,$formtable) {

		$Dmi_formtable = TableRegistry::getTableLocator()->get($formtable);

		$Dmi_formtable_Entity = $Dmi_formtable->newEntity(array('id'=>$latest_id,
																'ro_reply_comment'=>$ro_reply,
																'ro_reply_comment_date'=>date('Y-m-d H:i:s'),
																'rr_comment_ul'=>$rr_comment_ul,
																'ro_current_comment_to'=>'mo',
																'modified'=>date('Y-m-d H:i:s')));

		if ($Dmi_formtable->save($Dmi_formtable_Entity)) { return true; }
	}


	// Method to scrutinized forms section by RO/SO
	public function formScrutinized($customer_id,$current_level,$last_user_email_id,$last_user_aadhar_no,$last_record_id,$tablename) {

		$Dmi_tablename = TableRegistry::getTableLocator()->get($tablename);

		if ($last_user_aadhar_no == $_SESSION['once_card_no']) {

			$user_email_id = $_SESSION['username'];
			$user_once_no = $_SESSION['once_card_no'];

		} else {

			if ($last_user_aadhar_no == '') {

				$user_email_id = $_SESSION['username'];
				$user_once_no = $_SESSION['once_card_no'];

			} else {

				$email_array = array($last_user_email_id,$_SESSION['username']);
				$aadhar_no_array = array($last_user_aadhar_no,$_SESSION['once_card_no']);
				$user_email_id = implode(',',$email_array);
				$user_once_no = implode(',',$aadhar_no_array);
			}
		}

		$Dmi_tablename_Entity = $Dmi_tablename->newEntity(array('id'=>$last_record_id,
																'form_status'=>'approved',
																'current_level'=>$current_level,
																'approved_date'=>date('Y-m-d H:i:s'),
																'user_email_id'=>$user_email_id,
																'user_once_no'=>$user_once_no,
																'ro_current_comment_to'=>'both'));

		if ($Dmi_tablename->save($Dmi_tablename_Entity)) { return true; }

	}


	// This function checked, Who is RO/SO talking with?
	public function checkLevel3CurrentCommentTo($sections,$customer_id) {
		$latest_modified__date_id = array();

		foreach ($sections as $each_section) {

			$each_table = $each_section['section_model'];
			$section_model = TableRegistry::getTableLocator()->get($each_table);

			// find the max modified date by pravin 30//05/2017
			$latest_modified__date_id = $section_model->find('all', array('fields'=>'modified', 'conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
			if (isset($latest_modified__date_id['modified'])) {
				$latest_modified__date = $latest_modified__date_id['modified'];
			}

			$get_record_list = $section_model->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,'modified IS'=>$latest_modified__date, 'delete_mo_comment IS NULL', 'delete_ro_referred_back IS NULL')))->toArray();

			if (!empty($get_record_list)) {

				$get_last_record = $section_model->find('all',array('conditions'=>array('id'=>max($get_record_list))))->first();
				$ro_current_comment_to = $get_last_record['ro_current_comment_to'];

				if ($ro_current_comment_to == 'applicant' || $ro_current_comment_to == 'mo') {
					return $ro_current_comment_to;
				}

			} else { $ro_current_comment_to = 'both'; }
		}

		if (empty($ro_current_comment_to) || $ro_current_comment_to == 'both') {
			$ro_current_comment_to = 'both';
		}

		return $ro_current_comment_to;
	}


	// This function updated the status of RO/SO talking with
	public function updateLevel3CurrentCommentTo($sections,$customer_id) {

		foreach ($sections as $each_section) {

			$each_table = $each_section['section_model'];
			$section_model = TableRegistry::getTableLocator()->get($each_table);
			$get_record_list = $section_model->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,'delete_mo_comment IS NULL', 'delete_ro_referred_back IS NULL')))->toArray();

			if (!empty($get_record_list)) {

				$section_model_Entity = $section_model->newEntity(array('id'=>max($get_record_list),'ro_current_comment_to'=>'both'));
				$section_model->save($section_model_Entity);
			}
		}
	}


	// This function check, is application have export unit?
	public function checkApplicantExportUnit($customer_id) {

		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');

		$check_application_type = $DmiFirms->find('all',array('fields'=>'export_unit','conditions'=>array('customer_id IS'=>$customer_id)))->first();

		$export_unit = 'no';

		if (!empty($check_application_type)) {

			if ($check_application_type['export_unit'] == 'yes') {

				$export_unit = 'yes';

			} else {

				$export_unit = 'no';
			}
		}

		return $export_unit;
	}


	//Get all comment history between RO/SO and MO on particular application
	public function getCommentReply($section_model,$customer_id) {

		// Get last grant date to get latest record of the application. Done by Pravin Bhakare
		$grantDateCondition = $this->returnGrantDateCondition($customer_id);
		$model = TableRegistry::getTableLocator()->get($section_model);
		$fetch_comment_reply = $model->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'delete_mo_comment IS NULL', 'delete_ro_referred_back IS NULL'), 'order'=>'id'))->toArray();
		return $fetch_comment_reply;
	}


	//Get all comment history between RO/SO and Applicant on particular application
	public function getApplicantCommentReply($section_model,$customer_id) {

		// Get last grant date to get latest record of the application. Done by Pravin Bhakare
		$grantDateCondition = $this->returnGrantDateCondition($customer_id);
		$model = TableRegistry::getTableLocator()->get($section_model);
		$fetch_applicant_communication = $model->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'delete_mo_comment IS NULL', 'delete_ro_referred_back IS NULL'), 'order'=>'id'))->toArray();
		return 	$fetch_applicant_communication;
	}


	// This function find out the lastest version of Application pdf (By pravin 08-08-2017)
	public function findLatestApplicationPdf($customer_id) {

		$application_type = $_SESSION['application_type'];
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		$Dmi_application_pdf_record = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['app_pdf_record']);
		$application_pdf_list_id = $Dmi_application_pdf_record->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();

		if (!empty($application_pdf_list_id)) {

			$application_pdf_path = $Dmi_application_pdf_record->find('all',array('conditions'=>array('id'=>max($application_pdf_list_id))))->first();
			$download_application_pdf = $application_pdf_path['pdf_file'];
			return $download_application_pdf;

		} else {

			$download_application_pdf = "";
			return $download_application_pdf;
		}
	}


	// This function find out the lastest version of sitinspection report pdf (By pravin 08-08-2017)
	public function findLatestReportPdf($customer_id) {

		$grantDateCondition = $this->returnGrantDateCondition($customer_id);

		$application_type = $_SESSION['application_type'];
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		$Dmi_siteinspection_report_pdf_record = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['inspection_pdf_record']);

		$report_pdf_list_id = null;

		if ($application_type !=2) {
			$report_pdf_list_id = $Dmi_siteinspection_report_pdf_record->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
		}

		if (!empty($report_pdf_list_id)) {

			$report_pdf_path = $Dmi_siteinspection_report_pdf_record->find('all',array('conditions'=>array('id'=>max($report_pdf_list_id))))->first();
			$download_report_pdf = $report_pdf_path['pdf_file'];
			return $download_report_pdf;

		} else {

			$download_report_pdf = "";
			return $download_report_pdf;
		}
	}


	// to get allication district office email id (for level 3 user)
	public function getApplDistOfficeId($customer_id) {

		$office_type = $this->getApplDistrictOffice($customer_id);

		if ($office_type == 'RO' || $office_type == 'SO') {

			$office_model_name = 'DmiRoOffices';
			$office_email_id_field = 'ro_email_id';

		} elseif ($office_type == 'SMD') {

			$office_model_name = 'DmiSmdOffices';
			$office_email_id_field = 'smd_email_id';
		}


		$split_customer_id = explode('/',$customer_id);
		$district_code = $split_customer_id[2];

		$office_model_name = TableRegistry::getTableLocator()->get($office_model_name);
		//updated and added code to get Office table details from appl mapping Model
		$DmiApplWithRoMappings = TableRegistry::getTableLocator()->get('DmiApplWithRoMappings');
		$find_email_id = $DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$office_email_id = $find_email_id['ro_email_id'];

		return $office_email_id;

	}


	//to get application reg. office email id
	public function getApplRegOfficeId($customer_id,$application_type) {

		$office_type = $this->getApplDistrictOffice($customer_id,$application_type);

		$split_customer_id = explode('/',$customer_id);
		$district_code = $split_customer_id[2];

		//get office email id
		$office_model_name = TableRegistry::getTableLocator()->get('DmiRoOffices');

		//updated and added code to get Office table details from appl mapping Model
		$DmiApplWithRoMappings = TableRegistry::getTableLocator()->get('DmiApplWithRoMappings');
		$get_office_record = $DmiApplWithRoMappings->getOfficeDetails($customer_id);

		//if appl is for lab (domestic/export), No 'SO' involded as per new scenario
		//applied on 21-09-2021 by Amol
		$firm_type = $this->firmType($customer_id);


		// if appl is printing and only 1 officer available in respective SO office
		// then appl directory transfer to respective RO office for approval.
		// as per new printing scenario
		// Done by Pravin Bhakare 05-10-2021
		/*if ($firm_type==2 && $get_office_record['office_type']=='SO')
		{
			$officerPresent = $this->findOfficerCountInoffice($user);

			if ( $officerPresent < 2) {

				$field_name = 'ro_id_for_so';
			}
		}
		else*/

		if ($firm_type==3 && $get_office_record['office_type']=='SO') {
			//as updated function "getApplDistrictOffice" for lab appl, it will give 'RO' for 'SO' office type also
			//so get ro id for SO office
			$field_name = 'ro_id_for_so';
			
		} else {
			if ($office_type == 'RO') {
				$field_name = 'id';
			} elseif ($office_type == 'SO') {
				$field_name = 'ro_id_for_so';
			}
		}

		$get_email_id = $office_model_name->find('all',array('conditions'=>array('id IS'=>$get_office_record[$field_name])))->first();

		if (!empty($get_email_id)) {
			return $get_email_id['ro_email_id'];
		} else {
			return '';
		}
	}


	//Common function to submit final sitinspection report or reply. (By pravin 15-09-2018)
	public function commonReportFinalSubmitCall() {

		$customer_id = $this->Session->read('customer_id');
		$grantDateCondition = $this->returnGrantDateCondition($customer_id);

		$firm_type_text = $this->firmTypeText($customer_id);
		$office_type = $this->getApplDistrictOffice($customer_id);
		$firm_type = $this->firmType($customer_id);
		$form_type = $this->checkApplicantFormType($customer_id);
		$application_type = $this->Session->read('application_type');

		$DmiCommonSiteinspectionFlowDetails = TableRegistry::getTableLocator()->get('DmiCommonSiteinspectionFlowDetails');
		$allSectionDetails = $DmiCommonSiteinspectionFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);

		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'inspection_report');
		$appl_current_pos_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'appl_current_pos');
		$allocation_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'allocation');
		$esign_status_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'esign_status');

		$Dmi_all_applications_current_position = TableRegistry::getTableLocator()->get($appl_current_pos_table);
		$Dmi_report_final_submit_table = TableRegistry::getTableLocator()->get($final_submit_table);
		$Dmi_sms_email_template = TableRegistry::getTableLocator()->get('DmiSmsEmailTemplates');
		$Dmi_ro_office = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$Dmi_application_esigned_status = TableRegistry::getTableLocator()->get($esign_status_table);
		$Dmi_allocation = TableRegistry::getTableLocator()->get($allocation_table);

		$allocation_details = $Dmi_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();

		//check final report status
		$final_report_list_ids = $Dmi_report_final_submit_table->find('list', array('valueField'=>array('id'),'conditions' => array('customer_id IS'=>$customer_id, $grantDateCondition)))->toArray();

		if (empty($final_report_list_ids)) {

			if ($this->commonSiteinspectionFormsFinalReport($customer_id,$allSectionDetails) == 1) {
				//added on 23-08-2017 by Pravin to send SMS/Email
				//call custom function from Model with message id
				//$Dmi_sms_email_template->sendMessage(17,$customer_id);
				$Dmi_temp_esign_status = TableRegistry::getTableLocator()->get('DmiTempEsignStatuses');
				$Dmi_temp_esign_status->DeleteTempEsignRecord($customer_id);
				return 1;

			} else {
				return 0;
			}

		} else {

			$Dmi_report_final_submit_table_Entity = $Dmi_report_final_submit_table->newEntity(array('customer_id'=>$customer_id,
																									'status'=>'replied',
																									'current_level'=>'level_3',
																									'created'=>date('Y-m-d H:i:s'),
																									'modified'=>date('Y-m-d H:i:s')));

			if ($Dmi_report_final_submit_table->save($Dmi_report_final_submit_table_Entity)) {

				//deleting record from temp esign status table, to clear that esign process reached till end succesfully.
				$Dmi_temp_esign_status = TableRegistry::getTableLocator()->get('DmiTempEsignStatuses');
				$Dmi_temp_esign_status->DeleteTempEsignRecord($customer_id);

				//update status of esign status table//added on 04-11-2017
				$Dmi_application_esigned_status->updateAll(array('application_status' => "replied"),array('customer_id IS' => $customer_id));

				//find RO by district ro id in customer id:
				$split_customer_id = explode('/',$customer_id);
				$district_ro_code = $split_customer_id[2];

				$ro_email_id = $allocation_details['level_3'];

				//Update record in all applications current position table
				//created and applied on 17-05-2017 by amol

				$user_email_id = $ro_email_id;
				$current_level = 'level_3';
				$Dmi_all_applications_current_position->currentUserUpdate($customer_id,$user_email_id,$current_level);//call to custom function from model

				//added on 23-08-2017 by Pravin to send SMS/Email
				//call custom function from Model with message id
				//$Dmi_sms_email_template->sendMessage(19,$customer_id);

				return 2;

			}
		}

	}


	// Common function to save final report status after submit final report or reply. (By pravin 15-09-2018)
	public function commonSiteinspectionFormsFinalReport($customer_id,$allSectionDetails) {

		$grantDateCondition = $this->returnGrantDateCondition($customer_id);

		$application_type = $this->Session->read('application_type');

		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$application_final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'application_form');
		$report_final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'inspection_report');
		$appl_current_pos_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'appl_current_pos');
		$allocation_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'allocation');

		$Dmi_final_submit_table = TableRegistry::getTableLocator()->get($application_final_submit_table);
		$Dmi_common_siteinspection_flow_detail = TableRegistry::getTableLocator()->get('DmiCommonSiteinspectionFlowDetails');
		$Dmi_report_final_submit_table = TableRegistry::getTableLocator()->get($report_final_submit_table);
		$Dmi_ro_office = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$Dmi_all_applications_current_position = TableRegistry::getTableLocator()->get($appl_current_pos_table);
		$Dmi_allocation = TableRegistry::getTableLocator()->get($allocation_table);

		// get allocation details
		$allocation_details = $Dmi_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();

		// check any entry of level_2 in final submit table
		$final_submit_level_2_entry = $Dmi_final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition,'status'=>'referred_back', 'current_level'=>'level_2')))->first();

		// check level_2 approved in final submit table
		$customer_level_2_approved = $Dmi_final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition,'status'=>'approved', 'current_level'=>'level_2')))->first();

		//check site inspection all forms status
		$siteinspection_forms_status = $Dmi_common_siteinspection_flow_detail->reportSectionStatus($customer_id,$allSectionDetails);

		// if any entry by level 2 found(referred back)
		if (!empty($final_submit_level_2_entry)) {

			if (!empty($customer_level_2_approved) && $siteinspection_forms_status == 'true') {

				// make entry in siteinspection final report table with level 2
				$Dmi_report_final_submit_table_Entity = $Dmi_report_final_submit_table->newEntity(array('customer_id'=>$customer_id,
																										'status'=>'pending',
																										'current_level'=>'level_2',
																										'created'=>date('Y-m-d H:i:s'),
																										'modified'=>date('Y-m-d H:i:s')));

				if ($Dmi_report_final_submit_table->save($Dmi_report_final_submit_table_Entity)) {

					//find RO by district ro id in customer id:
					$split_customer_id = explode('/',$customer_id);
					$district_ro_code = $split_customer_id[2];
					//Update record in all applications current position table
					//created and applied on 03-04-2017 by amol
					$user_email_id = $allocation_details['level_3'];
					$current_level = 'level_3';
					$Dmi_all_applications_current_position->currentUserUpdate($customer_id,$user_email_id,$current_level);

					return true;

				} else {

					return false;
				}


			} elseif (empty($customer_level_2_approved) && $siteinspection_forms_status == 'true') {
				
				//this below is changed from script alert to the  returning variables - Akash [08-09-2022]
				$messagevariable = "Sorry... You have referred back to the applicant and not approved the forms";
				return $messagevariable;

			} else {
				
				//this below is changed from script alert to the  returning variables - Akash [08-09-2022]
				$messagevariable ="Sorry...All forms and reports should be properly saved to final report";
				return $messagevariable;
			}

		} else {

			// if any entry by level 2 found(no referred back)
			if ($siteinspection_forms_status == 'true') {

				// make entry in final submit table with level 2
				$Dmi_final_submit_table_Entity = $Dmi_final_submit_table->newEntity(array('customer_id'=>$customer_id,
																							'status'=>'approved',
																							'current_level'=>'level_2',
																							'created'=>date('Y-m-d H:i:s'),
																							'modified'=>date('Y-m-d H:i:s')));

				if ($Dmi_final_submit_table->save($Dmi_final_submit_table_Entity)) {

					// make entry in siteinspection final report table with level 2
					$Dmi_report_final_submit_table_Entity = $Dmi_report_final_submit_table->newEntity(array('customer_id'=>$customer_id,
																											'status'=>'pending',
																											'current_level'=>'level_2',
																											'created'=>date('Y-m-d H:i:s'),
																											'modified'=>date('Y-m-d H:i:s')));

					if ($Dmi_report_final_submit_table->save($Dmi_report_final_submit_table_Entity)) {

						//Update record in all applications current position table //created and applied on 03-04-2017 by amol
						$user_email_id = $allocation_details['level_3'];
						$current_level = 'level_3';
						//call to custom function from model
						$Dmi_all_applications_current_position->currentUserUpdate($customer_id,$user_email_id,$current_level);

						return true;
					}
				}

			} else {

				return false;
			}
		}
	}


	// To check on report accept, If referred back by level 3 on applicant forms then make approved entry
	public function checkOnRoinspectionFormsApproved($customer_id,$form_final_submit_table) {

		$grantDateCondition = $this->returnGrantDateCondition($customer_id);

		$Dmi_final_submit = TableRegistry::getTableLocator()->get($form_final_submit_table);

		// check any entry of level_3 in final submit table
		$final_submit_level_3_entry = $Dmi_final_submit->find('all',
												array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition,
														'status'=>'referred_back', 'current_level'=>'level_3')))->first();


		//below logic changed & Applied on 29-03-2017 by Amol (by new flow)
		//changed as level_1 bcoz scrutiny is only done by RO now (Not by MO/IO)
		// check level_3 approved in final submit table
		$customer_level_3_approved = $Dmi_final_submit->find('all',
												array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition,
														'status'=>'approved', 'current_level'=>'level_1')))->first(); //it was level_3 before, but changed to level_1 as new flow logic. //changed on 29-03-2017 by Amol


		// if any entry by level 3 found(referred back)
		if (!empty($final_submit_level_3_entry)) {

			if (!empty($customer_level_3_approved)) {
					return true;
			} else {
				//this below is changed from script alert to the  returning variables - Akash [08-09-2022]
				$messagevariable = "Sorry... Please approved applicant forms first which you have referred back.";
				return $messagevariable;
			}

		} else {
			return true;
		}

	}



	// To check, The application will be forwarded by RO/SO to whom
	public function forwardToApplicationAtLevel4($customer_id,$office_type,$application_type) {

		$grantDateCondition = $this->returnGrantDateCondition($customer_id);

		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_allocation_table_name = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'allocation');
		$Dmi_allocation_table = TableRegistry::getTableLocator()->get($Dmi_allocation_table_name);
		$allocation_details = $Dmi_allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition),'order'=>'id DESC'))->first();

		if ($office_type == 'SO') {
			if (empty($allocation_details['level_4_ro'])) {
				$forward_to = 'RO';
			} else {
				$forward_to = 'HO';
			}
		}elseif ($office_type == 'RO') {
				$forward_to = 'HO';
		}
		return $forward_to;
	}



	//check if applicant did 3 failed attempts, lockout him for current day to login.
	//on 24-04-2018 by Amol
	public function checkLoginLockout($table,$user_id) {

		$Dmitable = TableRegistry::getTableLocator()->get($table);
		//check in DB logs table
		if ($table == 'DmiUserLogs') {
			$get_logs_records = $Dmitable->find('all',array('fields'=>array('id'),'conditions'=>array('email_id IS'=>$user_id),'order'=>'id Desc'))->toArray();

		}elseif ($table == 'DmiCustomerLogs') {
			$get_logs_records = $Dmitable->find('all',array('fields'=>array('id'),'conditions'=>array('customer_id IS'=>$user_id),'order'=>'id Desc'))->toArray();

		}elseif ($table == 'DmiChemistLogs') {
			$get_logs_records = $Dmitable->find('all',array('fields'=>array('id'),'conditions'=>array('customer_id IS'=>$user_id),'order'=>'id Desc'))->toArray();
		}

		$i = 0;
		foreach($get_logs_records as $each) {

			$each_log_details = $Dmitable->find('all',array('conditions'=>array('id IS'=>$each['id'])))->first();
			$remark[$i] = $each_log_details['remark'];
			$date[$i] = $each_log_details['date'];

			$i = $i+1;
		}

		$current_date = strtotime(date('d-m-Y'));


		$j = 0;
		$failed_count = 0;
		$lockout_status = null;
		while($j <= 2) {

			if (!empty($remark[$j])) {

				if ($remark[$j] == 'Failed') {
					$log_date = strtotime(str_replace('/','-',$date[$j]));

					if ($current_date == $log_date) {

						$lockout_status = 'yes';
					} else {
						$lockout_status = 'no';
					}

					$failed_count = $failed_count+1;
				}
			}

			$j = $j+1;
		}

		if ($failed_count == 3 && $lockout_status == 'yes')
		{
			return 'yes';
		} else {
			return 'no';
		}
	}
	
	
	
	public function getApplicationCurrentStatus($customer_id,$appl_type=null) {

		$DmiRenewalFinalSubmits = TableRegistry::getTableLocator()->get('DmiRenewalFinalSubmits');
		$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');

		if ($appl_type==null) {
			$DmiFinalSubmits = TableRegistry::getTableLocator()->get('DmiFinalSubmits');
		} else {
			//get flow wise final submit table
			$getfinalSubmiModel = $DmiFlowWiseTablesLists->getFlowWiseTableDetails($appl_type,'application_form');
			$DmiFinalSubmits = TableRegistry::getTableLocator()->get($getfinalSubmiModel);
		}

		$application_status = null;
		//check final submit table status
		$final_submit_ids = $DmiFinalSubmits->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		if (!empty($final_submit_ids)) {
			
			$final_submit_last_details = $DmiFinalSubmits->find('all', array('conditions'=>array('id'=>max($final_submit_ids))))->first();
			$final_submit_status = $final_submit_last_details['status'];
			$final_submit_level = $final_submit_last_details['current_level'];

			if ($final_submit_status=='pending' || $final_submit_status=='replied') {

				$application_status = 'In Progress';

			} elseif ($final_submit_status=='referred_back') {

				$application_status = 'Referred Back';

			} elseif ($final_submit_status=='approved' && ($final_submit_level=='level_1' || $final_submit_level=='level_2')) {

				$application_status = 'In Progress';

			} else {//if approved status

				//check if renewal due
				$check_renewal_due = $this->checkApplicantValidForRenewal($customer_id);
				
				if ($check_renewal_due == 'yes') {

					//check Renewal final submit table status
					$renewal_final_submit_ids = $DmiRenewalFinalSubmits->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
					
					if (!empty($renewal_final_submit_ids)) {

						$renewal_final_submit_last_details = $DmiRenewalFinalSubmits->find('all', array('conditions'=>array('id'=>max($renewal_final_submit_ids))))->first();
						$renewal_final_submit_status = $renewal_final_submit_last_details['status'];

						if ($renewal_final_submit_status=='pending' || $renewal_final_submit_status=='replied') {

							$application_status = 'Renewal In Progress';

						} elseif ($renewal_final_submit_status=='referred_back') {

							$application_status = 'Renewal Referred Back';

						} else {

							$application_status = 'Renewal Granted';
						}
						
					} else {

						$application_status = 'Renewal Due';
					}
					
				} else {

					$application_status = 'Granted';
				}
			}

		} else {

				$application_status = 'Not Applied yet';
		}

		return $application_status;

	}



	//created function to check if applicant is valid for renewal ore not
	//accordingly show renewal btn, from last validity year till 1 month after expiry
	//on 10-08-2017 by Amol
	public function checkApplicantValidForRenewal($customer_id) {

		$DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');
		$DmiRenewalFinalSubmits = TableRegistry::getTableLocator()->get('DmiRenewalFinalSubmits');
		$list_id = $DmiGrantCertificatesPdfs->find('list', array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();

		if (!empty($list_id)) {

			$fetch_last_grant_data = $DmiGrantCertificatesPdfs->find('all', array('conditions'=>array('id'=>max($list_id))))->first();
			$grant_date = $fetch_last_grant_data['date'];

			$certificate_validity_date = $this->getCertificateValidUptoDate($customer_id,$grant_date);
			//$certificate_validity_date = '31-12-2018';
			//$get_validity_last_year = date('Y',strtotime($certificate_validity_date));
			//$get_current_year = date('Y');

			$split_customer_id = explode('/',$customer_id);

			/* //old logic for 13 months renewal period
			$validity_last_year = date('d-m-Y',strtotime("-1 year", strtotime($certificate_validity_date)));
			*/
			//new logic for 4 months renewal period //updated on 03-02-2018 by Amol
			$validity_last_year = date('d-m-Y',strtotime("-3 months", strtotime($certificate_validity_date)));

			$renewal_start_from = strtotime(str_replace('/','-',date('01-m-Y',strtotime($validity_last_year))));
			//$renewal_start_from = strtotime(str_replace('/','-',date('01-m-Y',strtotime("+1 month", strtotime($validity_last_year)))));
			//$one_month_later_date = strtotime(str_replace('/','-',date('t-m-Y', strtotime("+1 month", strtotime($certificate_validity_date)))));
			$get_current_date = strtotime(str_replace('/','-',date('d-m-Y')));

			// from here

			//get ext date from DB(maintained from master)
			$DmiDatesExtensions = TableRegistry::getTableLocator()->get('DmiDatesExtensions');
			$fetch_ext_date = $DmiDatesExtensions->find('all',array('fields'=>'renewal_extension_dt','conditions'=>array('cert_type'=>$split_customer_id[1])))->first();
			$ext_dt = $fetch_ext_date['renewal_extension_dt'];
			
			//for CA Application
			if ($split_customer_id[1] == 1) {
				$one_month_later_date = strtotime(str_replace('/','-',date($ext_dt.'/Y', strtotime("+1 month", strtotime($certificate_validity_date)))));
			//for PRINTING Application
			} elseif ($split_customer_id[1] == 2) {
				$one_month_later_date = strtotime(str_replace('/','-',date($ext_dt.'/Y', strtotime("+1 month", strtotime($certificate_validity_date)))));
			//for LAB Application
			} elseif ($split_customer_id[1] == 3) {
				$one_month_later_date = strtotime(str_replace('/','-',date($ext_dt.'/Y', strtotime("+1 month", strtotime($certificate_validity_date)))));
			}
			//till here


			//check renewal application submitted
			//added this new query & condition on 04-06-2019 by Amol
			$renewal_final_submit = $DmiRenewalFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
			$ren_status=null;
			$ren_current_level=null;
			
			if (!empty($renewal_final_submit)) {
				$ren_status = $renewal_final_submit['status'];
				$ren_current_level = $renewal_final_submit['current_level'];
			}

			if (($renewal_start_from <= $get_current_date && $get_current_date <= $one_month_later_date) || (!empty($renewal_final_submit) && !($ren_status=='approved' && $ren_current_level=='level_3')))//added this new condition on 04-06-2019 by Amol
			{
				$show_renewal_btn = 'yes';
				
			} else {

				$show_renewal_btn = 'no';
			}

		} else {

			$show_renewal_btn = 'no';
		}

		return $show_renewal_btn;
	}



	public function isOldApplication($customer_id,$appl_type=null) { //added new parameter on 23-09-2021 by Amol

		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$application_type = $this->Session->read('application_type');
		
		if (empty($application_type)) {
			$application_type = $appl_type;
		}

		$appl_is_old='no';
		
		if (!empty($customer_id)) {
			
			if ($application_type == 1) {
				
				$check_appl_is_old = $DmiFirms->find('all',array('fields'=>array('is_already_granted'),'conditions'=>array('customer_id IS'=>$customer_id)))->first();
				if (!empty($check_appl_is_old)) {
					$appl_is_old = $check_appl_is_old['is_already_granted'];
				}
				
			} elseif ($application_type == 6) {//for old e-code appl, added on 23-11-2021
			
				$DmiECodeApplDetails = TableRegistry::getTableLocator()->get('DmiECodeApplDetails');
				$check_appl_is_old = $DmiECodeApplDetails->find('all',array('fields'=>array('already_granted'),'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
				if (!empty($check_appl_is_old)) {
					$appl_is_old = $check_appl_is_old['already_granted'];
				}
				
			} else {
				$appl_is_old = 'no';
			}
		}
		
		$this->Session->write('oldapplication',$appl_is_old);
		return $appl_is_old;
	}



	//this function is created on 21-07-2017 by Amol to Applicant wise certificate validity date
	public function getCertificateValidUptoDate($customer_id,$cert_grant_date) {

		$DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$DmiOldApplicationRenewalDates = TableRegistry::getTableLocator()->get('DmiOldApplicationRenewalDates');
		$DmiRenewalFinalSubmits = TableRegistry::getTableLocator()->get('DmiRenewalFinalSubmits');
		$DmiFinalSubmits = TableRegistry::getTableLocator()->get('DmiFinalSubmits');

		//as per new order on 01-04-2021, the validity for renewal of lab & PP will be for 5 years
		//update for order on 01-04-2021 for renewal
		//for the certificate which was already granted before this order, on online system
		//this will work as per old validity dates, for example: to show renewal button for already granted
		//updated on 15-09-2021 by Amol

		$grant_date_pattern = str_replace('/','-',$cert_grant_date);
		$new_order_date_pattern = str_replace('/','-','01/04/2021');

		$split_grant_date = explode(' ',$grant_date_pattern);//as it will come with 00:00:00 some time
		$g_date = strtotime($split_grant_date[0]);
		$n_date = strtotime($new_order_date_pattern);

		if ($g_date < $n_date) { //if provided grant date is less than new order date '01-04-2021'

			$split_customer_id = explode('/',$customer_id);

			//check entry in grant table if yes, then change grant date in fixed format for further use
			//applied on 17-05-2019 by Amol

			//check grant date with max id on 13-07-2019
			$check_grant_entry =  $DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();

			//check application is old or new on 13-07-2019
			$check_appl_is_old = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'is_already_granted'=>'yes')))->first();

			// Check renewal date are presents for old application. on 13-07-2019
			$renewa_dates_present = '';
			
			if (!empty($check_grant_entry)) {
				
				if ($check_grant_entry['pdf_version']==1 && !empty($check_appl_is_old)) {
					
					$renewa_dates_present = $DmiOldApplicationRenewalDates->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();

					//currently for CA and lab, if required then apply for PP on 30-03-2022
					//applied condition if old appl and grant of first renewal, no renewal before
					//then check month of last grant to decide whether to add 4yrs or 5 yrs for validity in below logic
					if(empty($renewa_dates_present) && ($split_customer_id[1] == 1 || $split_customer_id[1] == 3)){
						
						$cert_grant_date = str_replace('/','-',$cert_grant_date);
						$get_grant_year = date('Y',strtotime($cert_grant_date));
						$get_grant_month = date('m',strtotime($cert_grant_date));
						
						if($get_grant_year != date('Y')) {//grant year is not same as current year, for first time
						
							if($split_customer_id[1] == 1 && $get_grant_month > 3){
								$renewa_dates_present = 'yes';
							}elseif($split_customer_id[1] == 3 && $get_grant_month > 6){
								$renewa_dates_present = 'yes';
							}
							
						}else{//grant year is same as current year, for next time
							$renewa_dates_present = 'yes';
						}
					}
					
					//if old application and come for renewal under oct,nov or dec. added on 12-01-2021 by Amol
					//added logic from below to here
					if ($split_customer_id[1] == 2) {//for Printing Application
					
						$cert_grant_date = str_replace('/','-',$cert_grant_date);
						$get_grant_year = date('Y',strtotime($cert_grant_date));
						$get_grant_month = date('m',strtotime($cert_grant_date));
						
						if ($get_grant_month > 9) {//if grant in oct,nov or dec then grant from 1st of next year
							$get_grant_year = $get_grant_year + 1;
						}
						$cert_grant_date = '01-01-'.$get_grant_year;
						
					}
					
				} elseif ($check_grant_entry['pdf_version']==1 && empty($check_appl_is_old)) {

					//when new application granted and applying for renewal, on 04-12-2019 by Amol
					//check if renewal application submitted
					$if_renew_applied = $DmiRenewalFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
					
					if (!empty($if_renew_applied)) {
						//check status is approved or not, when RO preview for Renewal grant, before grant
						//to get validity date with renewal logic
						if ($if_renew_applied['status']=='approved' && $if_renew_applied['current_level']=='level_3') {//added this condition on 31-12-2021 by Amol, as PP renewal conflicts only for 'approved', required level 3 also
							$renewa_dates_present ='yes';

						} else {
							$renewa_dates_present ='';
						}
						
					} else {
						$renewa_dates_present ='';
					}


				} elseif ($check_grant_entry['pdf_version']>=2) {
					
					//added below condition on 04-01-2022 for PP renewal dates adjusment
					if ($split_customer_id[1] == 2) {//for Printing Application
					
						$cert_grant_date = str_replace('/','-',$cert_grant_date);
						$get_grant_year = date('Y',strtotime($cert_grant_date));
						$get_grant_month = date('m',strtotime($cert_grant_date));
						
						if ($get_grant_month > 9) {//if grant in oct,nov or dec then grant from 1st of next year
							$renewa_dates_present ='';
						} else {
							$renewa_dates_present ='yes';
						}
						
					} else {
						//check if getting date for first grant entry// to avoid setting renewal date variable for first entry
						//added on 06-04-2022 by Amol
						$checkfirstrecord = $DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
						$firstGrantDate = $checkfirstrecord['date'];
						
						$grant_date = str_replace('-','/',$cert_grant_date);
						//check grant date from table with grant date from argument is equal and for first grant
						if($firstGrantDate == $grant_date){
							$renewa_dates_present ='';
						}else{
							$renewa_dates_present ='yes';
						}
					}
				}
			}


			//for renewal application date issues, logic updated on 17-05-2019 by Amol
			if (!empty($check_grant_entry) && !empty($renewa_dates_present))//added new condition on 13-07-2019
			{
				$cert_grant_date = str_replace('/','-',$cert_grant_date);
				$get_grant_year = date('Y',strtotime($cert_grant_date));
				$get_grant_month = date('m',strtotime($cert_grant_date));
				
				//for CA Application
				if ($split_customer_id[1] == 1) {
					$cert_grant_date = '01-04-'.$get_grant_year;
					
				//for Printing Application
				} elseif ($split_customer_id[1] == 2) {
					
					//if grant in oct,nov or dec then grant from 1st of next year
					if ($get_grant_month > 9) {
						$get_grant_year = $get_grant_year + 1;
					}
					
					$cert_grant_date = '01-01-'.$get_grant_year;
					
				//for Laboratory Application
				} elseif ($split_customer_id[1] == 3) {
					$cert_grant_date = '01-07-'.$get_grant_year;
				}
			}


			//if application is not grant yet, then logic start from here as it is
			$cert_grant_date = str_replace('/','-',$cert_grant_date);
			$get_grant_year = date('Y',strtotime($cert_grant_date));
			$get_grant_month = date('m',strtotime($cert_grant_date));

			//for CA Application
			if ($split_customer_id[1] == 1) {
				
				if ($get_grant_month <= 3) {
					$valid_upto_year = $get_grant_year + 4;
				} else {
					$valid_upto_year = $get_grant_year + 5;
				}
				
				$valid_upto_date = '31-03-'.$valid_upto_year;
				
			//for Printing Application
			} elseif ($split_customer_id[1] == 2) {
				
				$valid_upto_year = $get_grant_year + 1;
				$valid_upto_date = '31-12-'.$valid_upto_year;
				
			//for Laboratory Application
			} elseif ($split_customer_id[1] == 3) {
				
				if ($get_grant_month <= 6) {
					$valid_upto_year = $get_grant_year + 1;
				} else {
					$valid_upto_year = $get_grant_year + 2;
				}
				
				$valid_upto_date = '30-06-'.$valid_upto_year;
			}

		//this else part is for new updates, 5 years for validity for PP and Lab
		//also if lab is NABL accreditated then valid upto accreditation
		} else {

			$split_customer_id = explode('/',$customer_id);

			//check entry in grant table if yes, then change grant date in fixed format for further use
			//applied on 17-05-2019 by Amol
			//check grant date with max id on 13-07-2019
			$check_grant_entry = $DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();

			//check application is old or new on 13-07-2019
			$check_appl_is_old = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'is_already_granted'=>'yes')))->first();

			// Check renewal date are presents for old application. on 13-07-2019
			$renewa_dates_present = '';
			if (!empty($check_grant_entry)) {
				
				if ($check_grant_entry['pdf_version']==1 && !empty($check_appl_is_old)) {
					
					$renewa_dates_present = $DmiOldApplicationRenewalDates->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();

					//currently for CA and lab, if required then apply for PP on 30-03-2022
					//applied condition if old appl and grant of first renewal, no renewal before
					//then check month of last grant to decide whether to add 4yrs or 5 yrs for validity in below logic
					if(empty($renewa_dates_present) && ($split_customer_id[1] == 1 || $split_customer_id[1] == 3)){
						
						$cert_grant_date = str_replace('/','-',$cert_grant_date);
						$get_grant_year = date('Y',strtotime($cert_grant_date));
						$get_grant_month = date('m',strtotime($cert_grant_date));
						
						if($get_grant_year != date('Y')) {//grant year is not same as current year, for first time
						
							if($split_customer_id[1] == 1 && $get_grant_month > 3){
								$renewa_dates_present = 'yes';
							}elseif($split_customer_id[1] == 3 && $get_grant_month > 6){
								$renewa_dates_present = 'yes';
							}
							
						}else{//grant year is same as current year, for next time
							$renewa_dates_present = 'yes';
						}
					}
					
					//if old application and come for renewal under oct,nov or dec. added on 12-01-2021 by Amol
					//added logic from below to here
					if ($split_customer_id[1] == 2) {//for Printing Application

						$cert_grant_date = str_replace('/','-',$cert_grant_date);
						$get_grant_year = date('Y',strtotime($cert_grant_date));
						$get_grant_month = date('m',strtotime($cert_grant_date));

						if ($get_grant_month > 9) {//if grant in oct,nov or dec then grant from 1st of next year
							$get_grant_year = $get_grant_year + 1;
						}
						$cert_grant_date = '01-01-'.$get_grant_year;

					}

				} elseif ($check_grant_entry['pdf_version']==1 && empty($check_appl_is_old)) {

					//when new application granted and applying for renewal, on 04-12-2019 by Amol
					//check if renewal application submitted
					$if_renew_applied = $DmiRenewalFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
					if (!empty($if_renew_applied)) {
						//check status is approved or not, when RO preview for Renewal grant, before grant
						//to get validity date with renewal logic
						if ($if_renew_applied['status']=='approved' && $if_renew_applied['current_level']=='level_3') { //added this condition on 31-12-2021 by Amol, as PP renewal conflicts only for 'approved', required level 3 also
							$renewa_dates_present ='yes';

						} else {
							$renewa_dates_present ='';
						}
					} else {
						$renewa_dates_present ='';
					}

				} elseif ($check_grant_entry['pdf_version']>=2) {
					
					//added below condition on 04-01-2022 for PP renewal dates adjusment
					if ($split_customer_id[1] == 2) {//for Printing Application
					
						$cert_grant_date = str_replace('/','-',$cert_grant_date);
						$get_grant_year = date('Y',strtotime($cert_grant_date));
						$get_grant_month = date('m',strtotime($cert_grant_date));
						
						if ($get_grant_month > 9) {//if grant in oct,nov or dec then grant from 1st of next year
							$renewa_dates_present ='';
						} else {
							$renewa_dates_present ='yes';
						}
						
					} else {
						//check if getting date for first grant entry// to avoid setting renewal date variable for first entry
						//added on 06-04-2022 by Amol
						$checkfirstrecord = $DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
						$firstGrantDate = $checkfirstrecord['date'];
						
						$grant_date = str_replace('-','/',$cert_grant_date);
						//check grant date from table with grant date from argument is equal and for first grant
						if($firstGrantDate == $grant_date){
							$renewa_dates_present ='';
						}else{
							$renewa_dates_present ='yes';
						}
					}
				}
			}

			//for renewal application date issues, logic updated on 17-05-2019 by Amol
			if (!empty($check_grant_entry) && !empty($renewa_dates_present))//added new condition on 13-07-2019
			{
				$cert_grant_date = str_replace('/','-',$cert_grant_date);
				$get_grant_year = date('Y',strtotime($cert_grant_date));
				$get_grant_month = date('m',strtotime($cert_grant_date));

				if ($split_customer_id[1] == 1) {//for CA Application
				
					$cert_grant_date = '01-04-'.$get_grant_year;

				} elseif ($split_customer_id[1] == 2) {//for Printing Application
				
					if ($get_grant_month > 9) {//if grant in oct,nov or dec then grant from 1st of next year
						$get_grant_year = $get_grant_year + 1;
					}
					
					$cert_grant_date = '01-01-'.$get_grant_year;

				} elseif ($split_customer_id[1] == 3) {//for Laboratory Application
					$cert_grant_date = '01-07-'.$get_grant_year;

				}
			}

			//if application is not grant yet, then logic start from here as it is

			$cert_grant_date = str_replace('/','-',$cert_grant_date);
			$get_grant_year = date('Y',strtotime($cert_grant_date));
			$get_grant_month = date('m',strtotime($cert_grant_date));
			
			//query applied on 31-01-2022 by Amol
			//applied only in this else section for grant after "01-04-2021" new order, not required in above section
			//to resolve the issue if appl is CA or Lab, applied in one year and grant in next year
			//so take last application submission year for deciding valid up to year.
			if ($split_customer_id[1] == 3)//currently only for Lab appl, if required then CA will be included
			{
				$check_app_subm = $DmiRenewalFinalSubmits->find('all',array('conditions'=>array('status'=>'pending','customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
				if (!empty($check_app_subm)) {
					$app_sub_date = $check_app_subm['created'];
					$app_sub_date = str_replace('/','-',$app_sub_date);
					$get_grant_year = date('Y',strtotime($app_sub_date));
					
				} else {
					$check_app_subm = $DmiFinalSubmits->find('all',array('conditions'=>array('status'=>'pending','customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
					
					if(!empty($check_app_subm)){ // this line added by shankhpal shende on 12/09/2022
						$app_sub_date = $check_app_subm['created'];
						$app_sub_date = str_replace('/','-',$app_sub_date);
						$get_grant_year = date('Y',strtotime($app_sub_date));
					}
					
				}
			}

			if ($split_customer_id[1] == 1)//for CA Application
			{
				if ($get_grant_month <= 3)
				{
					$valid_upto_year = $get_grant_year + 4;
				} else {
					$valid_upto_year = $get_grant_year + 5;
				}
				$valid_upto_date = '31-03-'.$valid_upto_year;

			} elseif ($split_customer_id[1] == 2) { //for Printing Application

				$valid_upto_year = $get_grant_year + 4; //now validity for 5 years as per new order

				$valid_upto_date = '31-12-'.$valid_upto_year;
				
			} elseif ($split_customer_id[1] == 3) {//for Laboratory Application

				if ($get_grant_month <= 6)
				{
					$valid_upto_year = $get_grant_year + 4; //now validity for 5 years as per new order
				} else {
					$valid_upto_year = $get_grant_year + 5; //now validity for 5 years as per new order
				}
				$valid_upto_date = '30-06-'.$valid_upto_year;

				//as per new order by 01-04-2021 from DMI
				//if lab is NABL accreditated then valid upto the NABL accreditation date
				//applied on 29-09-2021 by Amol
				$NablDate = $this->Randomfunctions->checkIfLabNablAccreditated($customer_id);
				if ($NablDate != null) {
					$NablDate = str_replace('/','-',$NablDate);//replaced / with -
					$valid_upto_date = $NablDate;
				}
			}
		}

		return $valid_upto_date;
	}



	public function customerLastLogin() {

		$userType = $this->Session->read('username');
		//check User Type
		$explodeValue = explode('/',$userType);

		if ($explodeValue[0] == 'CHM') {

			$getLastLogs = TableRegistry::getTableLocator()->get('DmiChemistLogs');

		} else {

			$getLastLogs = TableRegistry::getTableLocator()->get('DmiCustomerLogs');

		}

		$list_id = $getLastLogs->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$userType, 'remark'=>'Success'),'order'=>'id'))->toArray();

		if (!empty($list_id)) {

			$i=0;
			foreach ($list_id as $id) {

				$list_id[$i]= $id;
				$i=$i+1;

			}

			if ($i != 1) {

				$last_login_id = $list_id[$i-2];

			} else {

				$last_login = 'First login';
				return $last_login;
			}


			$last_login = $getLastLogs->find('all', array('fields'=>array('date','time_in'), 'conditions'=>array('id IS'=>$last_login_id)))->first();

			return $last_login;

		} else {

			$last_login = 'First login';
			return $last_login;
		}
	}



	//this method is created to get masked value for email id.
	//on 13-10-2017 by Amol
	public function getEmailMasked($email_id) {

		$em   = explode("@",$email_id);
		// $name = implode(array_slice($em, 0, count($em)-1), '@');
		$name = implode('@', array_slice($em, 0, count($em)-1));
		$len  = floor(strlen($name)/2);

		$split_name = str_split($name, 1);

		$i=0;
		$j=0;
		foreach($split_name as $each) {

				if ($i % 2 == 0) {
						$masked_value_array[$j] = str_replace($split_name[$i],'X', $each);

				} else {

						$masked_value_array[$j] = $each;
				}
				$i=$i+1;
				$j=$j+1;
		}

		$masked_value = implode('',$masked_value_array) . "@" . end($em);

		return $masked_value;

	}


	//This function is created to validate all POST data on server side
	//On 21/10/2017 by Amol
	public function validateUniquePostData($value,$type) {

		if ($type == 'mobile') {

			if (preg_match("/^(?=.*[0-9])[0-9]{10}$/", $value,$matches)==1 || preg_match("/^[X-X]{6}[0-9]{4}$/i", $value,$matches)==1) {
				return true;
			} else {
				return false;
			}
		}

		if ($type == 'email') {

			if (preg_match("/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/", $value,$matches)==1) {
				return true;
			} else {
				return false;
			}
		}


		if ($type == 'aadhar') {

			if (preg_match("/^(?=.*[0-9])[0-9]{12}$/", $value,$matches)==1 || preg_match("/^[X-X]{8}[0-9]{4}$/i", $value,$matches)==1) {
				return true;
			} else {
				return false;
			}
		}

	}



	//this method is created to create mask on personal identification details. as aadhar acts
	//on 12-10-2017 by Amol
	public function getMaskedValue($value,$type) {
		
		$masked_value = null;
		
		if ($type=='mobile') {
			$masked_value = substr_replace($value, str_repeat("X", 6), 0, 6);
		} elseif ($type=='email') {
			//calling custom email masking function
			$masked_value = $this->getEmailMasked($value);
		} elseif ($type=='aadhar') {
			$masked_value = substr_replace($value, str_repeat("X", 8), 0, 8);
		}
		
		return $masked_value;
	}
	
	//dropdown select server side validation
	public function dropdownSelectInputCheck($table,$post_input_request) {

		$table = TableRegistry::getTableLocator()->get($table);
		$db_table_id_list = $table->find('list',array('valueField'=>'id'))->toArray();
		$min_id_from_list = min($db_table_id_list);
		$max_id_from_list = max($db_table_id_list);

		if (filter_var($post_input_request, FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min_id_from_list, "max_range"=>$max_id_from_list))) === false) {


			$this->Session->destroy();
			echo "One of selected drop down value is not proper"; ?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
			exit;
		} else {
			return $post_input_request;
		}
	}



	//to upload file on server
	public function fileUploadLib($file_name,$file_size,$file_type,$file_local_path) {

		$valid_extension_file = array('jpeg','pdf','jpg');
		$get_extension_value = explode('.',$file_name);

		if (count($get_extension_value) != 2 ) {

			$this->Session->destroy();
			echo "Invalid file type.";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
			exit;

		} else {

			$extension_name = strtolower($get_extension_value[1]);

			if (in_array($extension_name,$valid_extension_file)) {} else {

				$this->Session->destroy();
				echo "Invalid file type.";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
				exit;
			}
		}

		if (($file_size > 2097152)) {

			$this->Session->destroy();
			echo "File too large. File must be less than 2 megabytes.";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
			exit;

		} elseif (($file_type != "application/pdf") && ($file_type != "image/jpeg")) {

			$this->Session->destroy();
			echo "Invalid file type. Only PDF, JPG types are accepted.";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
			exit;

		} else {

			// For PDF files
			if ($file_type == "application/pdf" ) {

				if ($f = fopen($file_local_path, 'rb')) {

					$header = fread($f, 4);
					fclose($f);

					// Signature = PDF
					if (strncmp($header, "\x25\x50\x44\x46", 4)==0 && strlen ($header)==4) {

						// CHECK IF PDF CONTENT HAVING MALICIOUS CHARACTERS OR NOT
						$pdf_content = file_get_contents($file_local_path);

						$cleaned_pdf_content = $this->fileClean($pdf_content);

						if ($cleaned_pdf_content=='invalid') {

							$this->Session->destroy();
							echo "File seems to be corrupted !";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
							exit;
						}

					} else {

						$this->Session->destroy();
						echo "Sorry....modified PDF file";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
						exit;
					}

				} else {

					echo "Not getting file path";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
					exit;
				}

			} elseif ($file_type == "image/jpeg" ) {

				if ($f = fopen($file_local_path, 'rb')) {

					$header = fread($f, 3);
					fclose($f);

					// Signature = JPEG
					if (strncmp($header, "\xFF\xD8\xFF", 3)==0 && strlen ($header)==3) {

						// CHECK FOR CORRUPTED (MODIFIED) FILE
						$img_content = file_get_contents($file_local_path);
						$im = imagecreatefromstring($img_content);
						if ($im !== false) {
							// original file
						} else {

							$this->Session->destroy();
							echo "File seems to be corrupted !";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
							exit;
						}

						// CHECK IF IMAGE CONTENTS HAVING MALICIOUS CHARACTERS OR NOT
						$img_content = file_get_contents($file_local_path);
						$cleaned_img_content = $this->fileClean($img_content);

						if ($cleaned_img_content=='invalid') {

							$this->Session->destroy();
							echo "File seems to be corrupted !";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
							exit;
						}

					} else {

						$this->Session->destroy();
						echo "Sorry....modified JPG file";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
						exit;
					}

				} else {

					echo "Not getting file path";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
					exit;
				}

			} elseif ($file_type == "image/jpg") {

				if ($f = fopen($file_local_path, 'rb')) {

					$header = fread($f, 3);
					fclose($f);

					// Signature = JPEG
					if (strncmp($header, "\xFF\xD8\xFF", 3)==0 && strlen ($header)==3) {

						// CHECK FOR CORRUPTED (MODIFIED) FILE
						$img_content = file_get_contents($file_local_path);
						$im = imagecreatefromstring($img_content);
						if ($im !== false) {
							// original file
						} else {

							$this->Session->destroy();
							echo "File seems to be corrupted !";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
							exit;
						}

						// CHECK IF IMAGE CONTENTS HAVING MALICIOUS CHARACTERS OR NOT
						$img_content = file_get_contents($file_local_path);
						$cleaned_img_content = $this->fileClean($img_content);

						if ($cleaned_img_content=='invalid') {

							$this->Session->destroy();
							echo "File seems to be corrupted !";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
							exit;
						}

					} else {

						$this->Session->destroy();
						echo "Sorry....modified JPG file";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
						exit;
					}

				} else {

					echo "Not getting file path";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
					exit;

				}

			}

			// File Uploading code start
			$filecodedName = time().uniqid().$file_name;
			$uploadPath = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/uploaded_docs/';
			$uploadFile = $uploadPath.$filecodedName;
			$uploadData = '';

			if (move_uploaded_file($file_local_path,$uploadFile)) {

				$uploadData = '/writereaddata/DMI/uploaded_docs/'.$filecodedName;

			} else {

				echo "File not uploaded please select proper file";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login</a><?php
				exit;
			}

		}

		if (!empty($uploadData)) {

			return $uploadData;
		}

	}


	//below function is for checking the uploadded files have malicious chracter or not! added on 29-04-2021 by Akash
	public function fileClean($str) {

		$BlacklistCharacters = TableRegistry::getTableLocator()->get('BlacklistCharacters');
		// $blacklists = array of blacklist characters from database
		$blacklists = $BlacklistCharacters->find('all');

		$malicious_found = '0';
		foreach($blacklists as $b_list) {
			// Change by Pravin Bhakare 13-10-2020
			$charac = $b_list['charac'];
			$posValue = strpos($str,$charac);
			if (!empty($posValue)) {
				$malicious_found = 1;
				break;
			}

		}

		if ($malicious_found > 0)
		{
			return 'invalid';
		}

		return $str;

	}



	//DMI user Last login time stamp function
	public function userLastLogins() {

		$DmiUserLogs = TableRegistry::getTableLocator()->get('DmiUserLogs');

		$list_id = $DmiUserLogs->find('list', array('fields'=>'id', 'conditions'=>array('email_id IS'=>$this->Session->read('username'), 'remark'=>'Success'),'order'=>'id'))->toArray();

		if (!empty($list_id)) {

			$i=0;
			foreach($list_id as $id) {

			$list_id[$i]= $id;

			$i=$i+1;

			}

			if ($i != 1) {
				$last_login_id = $list_id[$i-2];

			} else {

				$last_login = 'First login';
				return $last_login;
			}

			$last_login = $DmiUserLogs->find('all', array('fields'=>array('date','time_in'), 'conditions'=>array('id IS'=>$last_login_id)))->first();

			return $last_login;

		} else {

			$last_login = 'First login';
			return $last_login;
		}
	}


	//radio button server side validation
	public function radioButtonInputCheck($post_input_request) {

		if ($post_input_request == 'yes' 	  || $post_input_request == 'no'  || $post_input_request == 'page' ||
			$post_input_request == 'external' || $post_input_request == 'top' || $post_input_request == 'side' ||
			$post_input_request == 'bottom'   || $post_input_request == 'DMI' || $post_input_request == 'LMIS' ||
			$post_input_request == 'BOTH'     || $post_input_request == 'n/a' || $post_input_request == 'RO'   ||
			$post_input_request == 'SO'       || $post_input_request == 'RAL' || $post_input_request == 'CAL'  ||
			$post_input_request == 'HO'       || $post_input_request == 'male'|| $post_input_request == 'female')
		{

				return $post_input_request;

		} else {
			return null;
		}
	}



	//method to differentiate bet. Fat Spread and BEVO //below logic created on 27-03-2017 by Amol (by new flow)
	public function checkFatSpreadOrBevo($customer_id) {

		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');

		if ($this->checkCaBevo($customer_id) == 'yes') {
			
			$check_sub_commodities = $DmiFirms->find('all',array('fields'=>'sub_commodity','conditions'=>array('customer_id IS'=>$customer_id)))->first();
			$sub_commodities_values = $check_sub_commodities['sub_commodity'];
			$sub_commodities_array = explode(',',$sub_commodities_values);

			$bevo = 'no';
			$fat_spread = 'no';
			
			foreach ($sub_commodities_array as $each_sub_commodity) {
				
				if ($each_sub_commodity == '173')//changed fron 172 to 173 on 11-07-2019 by Amol
				{
					$bevo = 'yes';
				}
				//id 79,80,81 added on 05-09-2022 for Fat Spread updates after UAT
				if ($each_sub_commodity == '172' || $each_sub_commodity == '79' || $each_sub_commodity == '80' || $each_sub_commodity == '81')//changed fron 173 to 172 on 11-07-2019 by Amol
				{
					$fat_spread = 'yes';
				}

			}

		//  if ($bevo == 'yes' && $fat_spread == 'yes')
		//  {
				$applicant_type = 'both';
		//  }
		
			if ($bevo == 'yes')
			{
					$applicant_type = 'bevo';
			} elseif ($fat_spread == 'yes') {
					$applicant_type = 'fat_spread';
			}

			return $applicant_type;

		}

	}



	//Only no. input server side validation
	public function integerInputCheck($post_input_request) {

		$min = 1;

		if (!filter_var($post_input_request, FILTER_VALIDATE_INT, array("options" => array("min_range"=>$min))) === false) {

			return $post_input_request;

		} else {

			return null;
		}
	}



	//Function check the valid date format and valid date value
	public function dateFormatCheck($date) {
		
		if (!empty($date)) {
			
			$input_date = explode('/',$date);
			$removeTime	= explode(' ',$input_date[2]);
			$year = $removeTime[0];

			if (count($input_date) == 3) {

				$zero_int_value = array('01','02','03','04','05','06','07','08','09');

				if (in_array($input_date[0],$zero_int_value, true))
				{
					$day_value = str_replace('0','',$input_date[0]);

				} else {

					$day_value = $input_date[0];
				}
				$day_value = $this->integerInputCheck($day_value);

				if (in_array($input_date[1],$zero_int_value, true))
				{
					$month_value = str_replace('0','',$input_date[1]);

				} else {

					$month_value = $input_date[1];
				}
				$month_value = $this->integerInputCheck($month_value);

				if (in_array($year,$zero_int_value, true))
				{
					$year_value = str_replace('0','',$$year);

				} else {

					$year_value = $year;
				}
				
				$year_value = $this->integerInputCheck($year_value);
				$valid = checkdate(trim($month_value), trim($day_value), trim((int)$year_value));

				if ($valid == 1) {
					return $this->changeDateFormat($date);
				} else {
					echo"Sorry.. Something wrong happened. ";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login again</a><?php
					exit;
				}

			} else {
				echo"Sorry.. Something wrong happened. ";?><a href="<?php echo $this->getController()->getRequest()->getAttribute('webroot');?>"> Please Login again</a><?php
				exit;
			}

		} else {
			return $this->changeDateFormat($date);
		}
	}



	// To get session customer id
	public function sessionCustomerID() {

		if (strpos(base64_decode($this->Session->read('username')), '@') !== false) {//for email encoding

				$customer_id = $_SESSION['customer_id'];
		} else {
				$customer_id = $_SESSION['username'];
		}

		return $customer_id;
	}



	//This function is created on 29-03-2019 by Amol
	//this is called in oldinspection controller in all edit firm profile function (CA,P.P,Lab)
	//To fetch old cert details from added firm, and show in a popup box on firm profile section while DMI user scrutiny
	public function showOldCertDetailsPopup($customer_id) {

		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');//initialize model in component
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
		$DmiPackingTypes = TableRegistry::getTableLocator()->get('DmiPackingTypes');
		$DmiOldApplicationCertificateDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');
		$DmiOldApplicationRenewalDates = TableRegistry::getTableLocator()->get('DmiOldApplicationRenewalDates');


		$added_firm_field = array();
		$added_firms = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		$added_firm_field = $added_firms[0];

		//taking id of multiple sub commodities	to show names in list
		$sub_comm_id = explode(',',$added_firm_field['sub_commodity']);
		$sub_commodity_value = $MCommodity->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_comm_id)))->toArray();
		$this->Controller->set('sub_commodity_value',$sub_commodity_value);

		//taking id of multiple Packaging Materials types to show names in list
		$packaging_type_id = explode(',',$added_firm_field['packaging_materials']);
		$packaging_materials_value = $DmiPackingTypes->find('list',array('keyField'=>'id','valueField'=>'packing_type','conditions'=>array('id IN'=>$packaging_type_id)))->toArray();
		$this->Controller->set('packaging_materials_value',$packaging_materials_value);

		$certificate_no = null;
		$date_of_grant = null;
		$old_certificate_details = $DmiOldApplicationCertificateDetails->find('all', array('conditions'=>array('customer_id IS'=>$customer_id)))->first();

		if (!empty($old_certificate_details)) {
			$certificate_no = $old_certificate_details['certificate_no'];
			$date_of_grant = $old_certificate_details['date_of_grant'];
		}

		$this->Controller->set('certificate_no',$certificate_no);
		$this->Controller->set('date_of_grant',$date_of_grant);

		$old_app_renewal_dates = $DmiOldApplicationRenewalDates->find('all', array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		$this->Controller->set('old_app_renewal_dates',$old_app_renewal_dates);


		//get valid upto date to show in flash msg on each section
		//added on 09-01-2020 by Amol
		if (!empty($old_app_renewal_dates)) {

			foreach ($old_app_renewal_dates as $renewal_date) {
				$last_ren_date = chop($renewal_date['renewal_date'],'00:00:00');
			}

			$valid_upto_date = str_replace('-','/',$this->getCertificateValidUptoDate($customer_id,$last_ren_date));
			$this->Controller->set('last_ren_date',$last_ren_date);
		} else {
			$valid_upto_date = str_replace('-','/',$this->getCertificateValidUptoDate($customer_id,$date_of_grant));
		}

		$this->Controller->set('valid_upto_date',$valid_upto_date);
	}



	//function to check comment_by to comment_to on HO level communication(Like Dy_ama to Jt.Ama, Jt_ama to Ama)
	//to set the sms/email templete id for sending the sms/email
	public function checkHoLevelSmsId($comment_by,$comment_to) {


		if ($comment_by['dy_ama'] == 'yes' && $comment_to == 'jt_ama') {

			$sms_id = 24;
			return $sms_id;

		} elseif ($comment_by['jt_ama'] == 'yes' && $comment_to == 'ama') {

			$sms_id = 25;
			return $sms_id;

		} elseif ($comment_by['ama'] == 'yes' && $comment_to == 'jt_ama') {

			$sms_id = 27;
			return $sms_id;

		} elseif ($comment_by['jt_ama'] == 'yes' && $comment_to == 'dy_ama') {

			$sms_id = 28;
			return $sms_id;

		} elseif ($comment_by['dy_ama'] == 'yes' && $comment_to == 'ro') {

			$sms_id = 29;
			return $sms_id;

		} elseif ($comment_by['dy_ama'] == 'yes' && $comment_to == 'ho_mo_smo') {

			$sms_id = 46;
			return $sms_id;
		} elseif ($comment_by['ho_mo_smo'] == 'yes' && $comment_to == 'dy_ama') {

			$sms_id = 23;
			return $sms_id;

		} elseif ($comment_by['ro_inspection'] == 'yes' && $comment_to == 'dy_ama') {

			$sms_id = 45;
			return $sms_id;
		}
	}


	//to get application flow wise current position
	public function getApplCurrentPos($position_table,$customer_id) {

		$grantDateCondition = $this->returnGrantDateCondition($customer_id);
		$position_table = TableRegistry::getTableLocator()->get($position_table);
		$get_details = $position_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition),'order'=>'id DESC'))->first();

		return $get_details;
	}


	// Return Grant Date Condition
	public function returnGrantDateCondition($customer_id) {

		$application_type = $this->Session->read('application_type');
		$advancepayment = $this->Session->read('advancepayment');


		$DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');
		$DmiAdvPaymentDetails = TableRegistry::getTableLocator()->get('DmiAdvPaymentDetails');

		$grantDate = $DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'pdf_version >'=>'1'),'order'=>'id DESC'))->first();

		if ($application_type == 3) {

			$DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get('DmiChangeGrantCertificatesPdfs');
			$grantDate = $DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
		}

		if ($advancepayment == 'yes') {

			$grantDate = $DmiAdvPaymentDetails->find('all', array('fields'=>array('created'),'conditions'=>array('customer_id IS'=>$customer_id,'payment_confirmation'=>'confirmed'),'order'=>'id desc'))->first();

			$table_name = 'Dmi_adv_payment_detail';
		}

		if (!empty($grantDate)) {

			if ($advancepayment == 'yes') {

				return array('created >'=>$grantDate['created']);

			} else {
				return array('date(created) >'=>$grantDate['created']);
			}

		} else {
			return array();
		}
	}


	// GET DATE FORMAT AS IN "Y-m-d"
	// by Aniket Ganvir dated 29th JAN 2021
	public function changeDateFormat($date) {

		if (!empty($date)) {

			$result	= explode(' ',trim($date));

			if (count($result) == 2) {

				$date1 = $result[0];
				$time = $result[1];
				$date = date_create_from_format("d/m/Y" , trim($date1))->format("Y-m-d").' '.$time;//added ' ' on 01-10-2021 by Amol

			} else {

				$date1 = $result[0];
				$date = date_create_from_format("d/m/Y" , trim($date1))->format("Y-m-d")." 00:00:00";
			}

		} else {
			$date;
		}

		return $date;
	}


	public function deleteChangeRequestEntry($customer_id) {

		$firm_type = $this->firmType($customer_id);
		$grantDateCondition = $this->returnGrantDateCondition($customer_id);

		if ($firm_type == 1) {

			$Tables = array('DmiChangeFirms','DmiChangePaymentDetails');

		} elseif ($firm_type == 2) {

			$Tables = array();

		} elseif ($firm_type == 3) {

			//$Tables = array('DmiChangeFirms','DmiChangeLabFirmDetails','DmiChangeLabOtherDetails','DmiChangeLabChemistsDetails');
			$Tables = array('DmiChangeFirms','DmiChangeLabFirmDetails','DmiChangeLabOtherDetails','DmiChangePaymentDetails');
		}

		foreach ($Tables as $table) {

			$DmiTable = TableRegistry::getTableLocator()->get($table);
			$DmiTable->deleteAll(['customer_id'=>$customer_id,$grantDateCondition]);
		}
	}


	// Get the next and previous button id of selected section.
	public function getNextPreSec($allSectionDetails) {

		$customer_id = $this->sessionCustomerID();
		$oldapplication = $this->isOldApplication($customer_id);

		$section_id = $this->Session->read('section_id');
		$application_type = $this->Session->read('application_type');
		$sections = array();

		if ($application_type == 3 ) {

			$DmiChangeSelectedFields = TableRegistry::getTableLocator()->get('DmiChangeSelectedFields');
			$selectedfields = $DmiChangeSelectedFields->selectedChangeFields();
			$sections = $selectedfields[2];

		} else {

			foreach ($allSectionDetails as $section) {

				$sections[] =  $section['section_id'];
			}
		}

		sort($sections);

		if ($section_id == null) {

			$section_id = $sections[0];
		}

		$key = array_keys($sections,$section_id);

		if (array_key_exists($key[0]+1,$sections)) {
			$nextSectionid = $sections[$key[0]+1];
		} else {
			$nextSectionid = null;
		}

		if (array_key_exists($key[0]-1,$sections)) {
			$preSectionid = $sections[$key[0]-1];
		} else {
			$preSectionid = null;
		}

		$paymentPreSection =  end($sections);

		return array($preSectionid,$nextSectionid,$paymentPreSection);
	}


	// Get the status of inspection for change application
	public function inspRequiredForChangeApp($customer_id,$appl_type) {

		$inspection = 'no';
		$DmiChangeSelectedFields = TableRegistry::getTableLocator()->get('DmiChangeSelectedFields');
		$DmiChangeFieldLists = TableRegistry::getTableLocator()->get('DmiChangeFieldLists');

		if ($appl_type == 3) {

			$form_type = $this->checkApplicantFormType($customer_id);
			$grantDateCondition = $this->returnGrantDateCondition($customer_id);
			$selectedfields = $DmiChangeSelectedFields->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->first();

			if ($selectedfields != null) {
				$selectedValues = explode(',',$selectedfields['changefields']);
			}

			foreach ($selectedValues as $data) {

				$changeField = $DmiChangeFieldLists->find('all',array('valueField'=>array('inspection'),'conditions'=>array('field_id IS'=>$data, 'form_type IS'=>$form_type)))->first();
				
				if (!empty($changeField) && $changeField['inspection'] == 'yes') {
					$inspection = 'yes';
				}
			}
		}

		return $inspection;
	}


	public function applicationCharges($applicationType,$firmType) {

		$DmiApplicationCharges = TableRegistry::getTableLocator()->get('DmiApplicationCharges');
		$DmiChangeFirms = TableRegistry::getTableLocator()->get('DmiChangeFirms');
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');

		$DmiChangeSelectedFields = TableRegistry::getTableLocator()->get('DmiChangeSelectedFields');
		$customer_id = $this->sessionCustomerID();

		$firmDetails = $DmiFirms->find('all',array('valueField'=>array('sub_commodity'),'conditions'=>array('customer_id IS'=>$customer_id)))->order('id desc')->first();
		$totalCharges = null;
		$charges = $DmiApplicationCharges->find('all',array('valueField'=>'charge','conditions'=>array('application_type_id IS'=>$applicationType)))->first();
		
		if (!empty($charges)) {
			$totalCharges = $charges['charge'];
		}

		//if application for change and firm is CA
		if ($applicationType == 3 &&  $firmType == 1) {

			$selectedValues = $DmiChangeSelectedFields->selectedChangeFields();

			if (in_array(3,$selectedValues[0])) {

				$changeFirmDetails = $DmiChangeFirms->find('all',array('valueField'=>array('sub_commodity'),'conditions'=>array('customer_id IS'=>$customer_id)))->order('id desc')->first();
				$changeCommoditiesDetails = explode(',',$changeFirmDetails['sub_commodity']);
				$commoditiesDetails = explode(',',$firmDetails['sub_commodity']);
				$changeCategories = $MCommodity->find('list', array('valueField'=>'category_code','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$changeCommoditiesDetails)))->toArray();
				$categories = $MCommodity->find('list', array('valueField'=>'category_code','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$commoditiesDetails)))->toArray();

				$results = array_diff(array_unique($changeCategories),array_unique($categories));

				$totalCategories = count(array_unique($results));
				$totalCharges = $totalCategories * $totalCharges;
			}
		}

		//if application for New and firm is CA
		if ($applicationType ==1 &&  $firmType == 1) {

			$commoditiesDetails = explode(',',$firmDetails['sub_commodity']);
			$categories = $MCommodity->find('list', array('valueField'=>'category_code','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$commoditiesDetails)))->toArray();
			$totalCategories = count(array_unique($categories));
			$totalCharges = $totalCategories * $totalCharges;
		}

		return $totalCharges;
	}



	//this function is created to show commodity names in reports listing on User dashboard. on 27-05-2020 by pravin
	public function showCommdityInApplList($sub_commodities) {

		$sub_commodity_array = explode(',',$sub_commodities);
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
		$MCommodityCategory = TableRegistry::getTableLocator()->get('MCommodityCategory');
		$category_names = '';
		$category_id = array();

		$i=0; $commdity_names = '';
		foreach ($sub_commodity_array as $sub_commodity_id) {

			$fetch_commodity_id = $MCommodity->find('all')->where(['commodity_code' => $sub_commodity_id])->first(); // updated by Ankur

			if (!empty($commdity_names)) {
				$commdity_names = $commdity_names.','.$fetch_commodity_id['commodity_name'];
			} else {
				if (!empty($fetch_commodity_id)) {
					$commdity_names = $fetch_commodity_id['commodity_name'];
				}
			}
			$i=$i+1;
		}

		return $commdity_names;
	}


	//to get and print Old application verifing user email id. on 28-05-2020 by pravin
	function old_app_approved_by($customer_id) {

		$explode = explode("/",$customer_id);
		$applicationType = $explode[1];

		if ($applicationType == 1 ) {
			$table_name = 'DmiCustomerMachineryProfiles';
		} elseif ($applicationType == 2) {
			$table_name = 'DmiPrintingUnitDetails';
		} elseif ($applicationType == 3) {
			$table_name = 'DmiLaboratoryOtherDetails';
		}

		$table = TableRegistry::getTableLocator()->get($table_name);

		// below query updated by Ankur Jangid
		$user_email = $table->find('all')->select(['user_email_id'])->where(['customer_id IS'=>$customer_id,'form_status'=>'approved'])->order(['id' => 'DESC'])->first();

		return $user_email['user_email_id'];
	}



	/////[FOR REPLICA - Replica Alloted List Call Method]////
	
	//to show alloted replica list, on 31-07-2021 by Amol
	public function replicaAllotedListCall($show_list_for) {

		//load models
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$DmiChemistAllotments = TableRegistry::getTableLocator()->get('DmiChemistAllotments');
		$DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
		
		//conditional model added on 26-11-2021, to get list for specific alloted codes
		if ($show_list_for=='replica') {
			$DmiReplicaAllotmentDetails = TableRegistry::getTableLocator()->get('DmiReplicaAllotmentDetails');
			$this->Session->write('alloted_list_for','replica');
			
		} elseif ($show_list_for=='15Digit') {
			$DmiReplicaAllotmentDetails = TableRegistry::getTableLocator()->get('Dmi15DigitAllotmentDetails');
			$this->Session->write('alloted_list_for','15Digit');
			
		} elseif ($show_list_for=='ECode') {
			$DmiReplicaAllotmentDetails = TableRegistry::getTableLocator()->get('DmiECodeAllotmentDetails');
			$this->Session->write('alloted_list_for','ECode');
		}
		
		$pdf_link = array();
		$commodity = array();

		//for customer
		$username = $this->Session->read('username');

		//Diplay Stastus To DMI USERS
		if (strpos(base64_decode($username),'@')) {//for email encoding

			//for dmi user
			$userEmail = $this->Session->read('username');

			//get RO/SO Incharge details
			$get_ro_details = $DmiRoOffices->find('all',array('fields'=>'short_code','conditions'=>array('ro_email_id IS'=>$userEmail)))->toArray();

			//get RO/SO office wise list
			$i=0;
			$get_list = array();
			$replica_stats = array();
			foreach ($get_ro_details as $eachCode) {

				$short_code = $eachCode['short_code'];

				$condition = array('customer_id like'=>'%/'.$short_code.'/%','allot_status'=>'1','delete_status IS Null');

				//Get Replica Status //added 'id' in query field on 25-08-2022
				$get_list[$i] = $DmiReplicaAllotmentDetails->find('all',array('fields'=>array('id','customer_id','ca_unique_no','commodity','grading_lab','allot_status','modified','version'),'conditions'=>$condition,'order'=>array('id desc')))->toArray();
				
				$replica_stats = array_merge($replica_stats,$get_list[$i]);

				$i=$i+1;
			}

			//Display Statuses To Applicant Side Users
		} else {

			$explodeValue = explode('/',$username);

			//For chemist dashboard
			if ($explodeValue[0] == 'CHM') {

				$get_alloted_ca = $DmiChemistAllotments->find('list',array('keyField'=>'id','valueField'=>'customer_id','conditions'=>array('chemist_id IS'=>$username,'status'=>'1')))->toArray();
				$condition = array('customer_id IN'=>$get_alloted_ca,'allot_status'=>'1','delete_status IS Null');

				//Diplay Status For CA
			} elseif ($explodeValue[1] == 1) {

				$condition = array('customer_id IS'=>$username,'allot_status'=>'1','delete_status IS Null');

				//Display Status For PP
			} elseif ($explodeValue[1] == 2) {

				//get the firm details and id
				$get_firm_details = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$username)))->first();
				$firm_id = $get_firm_details['id'];
				$condition = array('authorized_printer IS'=>$firm_id,'allot_status'=>'1','delete_status IS Null');

				//Display Status For LAB
			} elseif ($explodeValue[1] == 3) {

				//get the firm details and id
				$get_firm_details = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$username)))->first();
				$firm_id = $get_firm_details['id'];
				$condition = array('grading_lab IS'=>$firm_id,'allot_status'=>'1','delete_status IS Null');
			}

			//Get Replica Status //added 'id' in query field on 25-08-2022
			$replica_stats = $DmiReplicaAllotmentDetails->find('all',array('fields'=>array('id','customer_id','ca_unique_no','commodity','grading_lab','allot_status','modified','version'),'conditions'=>$condition,'order'=>array('id desc')))->toArray();

		}

		$pdf_version = array();
		
		if (!empty($replica_stats)) {

			$i=0;
			foreach ($replica_stats as $each_status) {

				//Get Commomdity
				$get_commodity = $MCommodity->find('all',array('fields'=>'commodity_name','conditions'=>array('commodity_code IS'=>$each_status['commodity'])))->first();
				$commodity[$i] = $get_commodity['commodity_name'];

				//get pdf record as per date
				$DmiReplicaAllotmentPdfs = TableRegistry::getTableLocator()->get('DmiReplicaAllotmentPdfs');

				//mapping pdf record with allotment date, get first records greater than allotment date
				$get_pdf = $DmiReplicaAllotmentPdfs->find('all',array('fields'=>array('pdf_file','pdf_version'),'conditions'=> array('customer_id IS'=>$each_status['customer_id'],'pdf_version IS'=>$each_status['version']),'order'=>'id asc'))->first();
				
				if (!empty($get_pdf)) {

					$pdf_link[$i] = $get_pdf['pdf_file'];
					$pdf_version[$i] = $get_pdf['pdf_version'];

					$i=$i+1;
				}
			}
		}
		
		//Set Variables
		$this->Controller->set('pdf_version',$pdf_version);
		$this->Controller->set('pdf_link',$pdf_link);
		$this->Controller->set('commodity',$commodity);
		$this->Controller->set('replica_stats',$replica_stats);
		
	}



	////[FOR REPLICA - CHEMIST LIST]////
	
	//To Show The Registered Chemists
	public function chemistList() {

		//Self Registred Chemist List
		$this->selfRegisteredChemistList();
		//Lab Registred Chemist List
		$this->labRegisteredChemistList();
		//Allocated Chemist List
		$this->allocatedChemistList();
	}



	//Get self Registered Chemist List For chemistList Method
	public function selfRegisteredChemistList() {

		//load models
		$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
		$DmiChemistAllotments = TableRegistry::getTableLocator()->get('DmiChemistAllotments');
		$DmiChemistFinalSubmits = TableRegistry::getTableLocator()->get('DmiChemistFinalSubmits');
		//Session Value
		$customer_id = $this->Session->read('username');
		//Self Registered Chemist
		$self_registered_chemist = $DmiChemistRegistrations->find('all',array('conditions'=>array('created_by IS' => $customer_id)))->toArray();
		$this->Controller->set('self_registered_chemist',$self_registered_chemist);

	}



	//Get Lab Registered Chemist List For chemistList Method
	public function labRegisteredChemistList() {

		//load models
		$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
		$DmiChemistAllotments = TableRegistry::getTableLocator()->get('DmiChemistAllotments');
		$DmiChemistFinalSubmits =  TableRegistry::getTableLocator()->get('DmiChemistFinalSubmits');
		//Session Value
		$customer_id = $this->Session->read('username');

		//Lab Registered Chemist
		$lab_registered_chemist_up = array();
		$chemist_name = array();
		$chemist_email = array();

		$lab_registered_chemist = $DmiChemistRegistrations->find('all',array('conditions'=>array('usertype'=>'3')))->toArray();

		if (!empty($lab_registered_chemist)) {

			$i=0;

			foreach ($lab_registered_chemist as $lab_chemist) {

				$alreadyallocate = $DmiChemistAllotments->find('all',array('conditions'=>array('usertype'=>'3','customer_id IS'=>$customer_id,'chemist_id IS'=>$lab_chemist['chemist_id'])))->first();

				if (empty($alreadyallocate)) {

					$isChemistApproved	= $DmiChemistFinalSubmits->find('all',array('fields'=>'status','conditions'=>array('customer_id IS'=>$lab_chemist['chemist_id'],'status'=>'approved'),'order'=>array('id'=>'desc')))->first();

					if (!empty($isChemistApproved)) {

						//fetch chemist list
						$chemist_list = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$lab_chemist['chemist_id'])))->toArray();
						//fetch chemist name
						$chemist_name[$i] = $chemist_list[0]['chemist_fname']." ".$chemist_list[0]['chemist_lname'];
						//chemist_email
						$chemist_email[$i] = $chemist_list[0]['email'];

						$lab_registered_chemist_up[$i] = $lab_chemist;
						$i=$i+1;
					}
				}
			}
		}

		//Set the Lab registered chemists
		$this->Controller->set('chemist_name',$chemist_name);
		$this->Controller->set('chemist_email',$chemist_email);
		$this->Controller->set('lab_registered_chemist',$lab_registered_chemist_up);

	}



	//Get Allocated Chemist List For chemistList Method
	public function allocatedChemistList() {

		//load models
		$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
		$DmiChemistAllotments = TableRegistry::getTableLocator()->get('DmiChemistAllotments');
		$DmiChemistFinalSubmits =  TableRegistry::getTableLocator()->get('DmiChemistFinalSubmits');
		//Session Value
		$customer_id = $this->Session->read('username');

		//Allocated Chemist
		$alloc_chemist_name = array();
		$alloc_chemist_email = array();
		$alloc_allocated_chemists = array();
		$chemist_incharge_id = null;

		//get allocated chemist list
		$alloc_allocated_chemists = $DmiChemistAllotments->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		$chemist_incharge = $DmiChemistAllotments->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'incharge'=>'yes')))->first();

		if (!empty($chemist_incharge)) {

			$chemist_incharge_id = $chemist_incharge['id'];
		}

		$approvedChemistList = array();

		if (!empty($alloc_allocated_chemists)) {

			$i=0;

			foreach ($alloc_allocated_chemists as $allocated_chemist) {

				$chemist_id = $allocated_chemist['chemist_id'];

				$isChemistApproved	= $DmiChemistFinalSubmits->find('all',array('fields'=>'status','conditions'=>array('customer_id IS'=>$chemist_id,'status'=>'approved'),'order'=>array('id'=>'desc')))->first();

				if (!empty($isChemistApproved)) {

					$chemist_list = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$allocated_chemist['chemist_id'])))->toArray();
					//fetch chemist name
					$alloc_chemist_name[$i] = $chemist_list[0]['chemist_fname']." ".$chemist_list[0]['chemist_lname'];
					//chemist_email
					$alloc_chemist_email[$i] = $chemist_list[0]['email'];
					$approvedChemistList[$i] = $allocated_chemist;
					$i=$i+1;
				}
			}
		}

		//set the allocated chemist list
		$this->Controller->set('chemist_incharge_id',$chemist_incharge_id);
		$this->Controller->set('alloc_chemist_name',$alloc_chemist_name);
		$this->Controller->set('alloc_chemist_email',$alloc_chemist_email);
		$this->Controller->set('alloc_allocated_chemists',$approvedChemistList);

	}


	//Check If The Replica Charges Is Exist Or Not
	public function checkIfCommodityReplicaIsExists($commodity_code) {

		//Load Model
		$DmiReplicaChargesDetails = TableRegistry::getTableLocator()->get('DmiReplicaChargesDetails');
		$checkIfExist = $DmiReplicaChargesDetails->find()->select(['id'])->where(['commodity_code IS'=>$commodity_code])->first();
		
		if (empty($checkIfExist)) {
			return true;
		} else {
			return false;
		}
	}


	// Update the common communication table is_latest value on reply
	// Done by Pravin Bhakare
	public function singleWindowReplySubmit() {
		
		$customer_id = $this->sessionCustomerID();
		$application_type = $this->Session->read('application_type');
		$office_type = $this->getApplDistrictOffice($customer_id);
		$firm_type = $this->firmType($customer_id);
		$form_type = $this->checkApplicantFormType($customer_id);

		$DmiChemistComments = TableRegistry::getTableLocator()->get('DmiChemistComments');
		$DmiCommonScrutinyFlowDetails = TableRegistry::getTableLocator()->get('DmiCommonScrutinyFlowDetails');


		$allSectionDetails = $DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);

		foreach($allSectionDetails as $each) {

			$currCommentRecord =  $DmiChemistComments->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'section_id IS'=>$each['section_id'],'is_latest'=>'1')))->first();
			if (!empty($currCommentRecord)) {
				$commentid = $currCommentRecord['id'];

				$newEntity = $DmiChemistComments->newEntity(array(
					'id'=>$commentid,
					'is_latest'=>0
					));
				if ($DmiChemistComments->save($newEntity));
			}
		}
	}



	// get the count of officer available in particular RO and SO office,
	// done by Pravin Bhakare, 05-10-2021
	public function findOfficerCountInoffice($user) {

		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');

		$officerPresent = 0;

		if (strrpos(base64_decode($user),'@')) {//for email encoding
			$userOffice = $DmiUsers->find('all',array('fields'=>array('posted_ro_office'),'conditions'=>array('email IS'=>$user)))->first();
			$officeid = $userOffice['posted_ro_office'];
		} else {
			$explodevalue = explode('/',$user);
			$roOffice = $DmiRoOffices->find('all',array('fields'=>array('id'),'conditions'=>array('short_code IS'=>$explodevalue[2])))->first();
			$officeid = $roOffice['id'];
		}

		$result = $DmiUsers->find('list',array('conditions'=>array('posted_ro_office'=>$officeid,'status'=>'active')))->toArray();

		if ( !empty($result))
		{
			$officerPresent = count($result);
		}

		return $officerPresent;
	}


	//User Action Performed Action for Logs
	public function saveActionPoint($action,$status) {
		
		//get user type
		$user_type = $this->getuserType($_SESSION['username']);
		
		if ($user_type == 'User') {
			#load model to save action 
			$action_model = 'DmiUserActionLogs';
		} elseif ($user_type == 'Primary') {
			#load model to save action 
			$action_model = 'DmiCustomerActionLogs';
		} elseif ($user_type == 'Secondary') {
			#load model to save action 
			$action_model = 'DmiFirmActionLogs';
		} else {
			$action_model = 'DmiChemistActionLogs';
		}

		$model = TableRegistry::getTableLocator()->get($action_model);
		
		//calling the model save function
		$model->saveActionLogs($action,$status);
	}
	
	
	
	// getShortCode
	// Author : Akash Thakre
	// Description : This will return the Short Code by Customer id
	// Date : 31-05-2022

	public function getShortCode($customer_id) {

		$explodevalue = explode('/',$customer_id);
		$short_code = $explodevalue[2];
		return $short_code;
	}



	// Get QR Code
	// Author : Shankhpal Shende
	// Description : This will return QR code
	// Date : 12/08/2022
	
	public function getQrCode($result,$type=null){
		
		$customer_id = $this->Session->read('customer_id');
		
		#this condition is added if there is no customer_id in the session. - Akash [08-09-2022]
		if (empty($customer_id)) {
			$customer_id = $this->Session->read('username');
		}

		if ($type =='CHM') {
			
			if(!empty($result[0])){

				$tableRowData = $result[0];
				$i=0;
				$data1 = '';
				$data2 = '';
				$data3 = '';
				foreach($tableRowData as $each){ 
					$data1 .=  $each['commodity_name'].",";
					$data2 .= $each['tbl_name'].",";
					$data3 .= $each['printer_name'].",";
				} 
			}
		}

		$DmiCertQrCodes = TableRegistry::getTableLocator()->get('DmiCertQrCodes'); //initialize model in component
		
		$resultdata = $DmiCertQrCodes->find('all',array('conditions'=>array('customer_id'=>$customer_id)))->toArray();
		
		require_once(ROOT . DS .'vendor' . DS . 'phpqrcode' . DS . 'qrlib.php');
		
		if ($type == 'CHM') {
			$data = "CA Id :".$result[2]."##"."Chemist Name :".$result[1]."##"."commodity_name :".$data1."##"."TBL Name: ".$data2."##"."Printer Name: ".$data3;
		}elseif ($type=='FDC') {
			$data = "Applicant Id :".$result."##"."This is the Certificate of Fifteen Digit Code".";";
		}elseif($type=='ECode'){
			$data = "Applicant Id :".$result[0]."##"."ECode :".$result[1].";";
		}else{
			$data = "MECARD:N:".'Certificate No:'.$result[0].'Grant Date:'.$result[1]." Valid up to date:".$result[2][0].";";
		}

		$qrimgname = rand();
		
		$server_imagpath = '/writereaddata/DMI/certificates/QRCodes/'.$qrimgname.".png";
		
		$file_path = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/certificates/QRCodes/'.$qrimgname.".png";
		
		$file_name = $file_path;
		
		QRcode::png($data,$file_name);
		
		
		$date = date('Y-m-d H:i:s');	
		
		$DmiCertificateQrAdd = $DmiCertQrCodes->newEntity(['customer_id'=>$customer_id,
														   'qr_code_path'=>$server_imagpath,
														   'created'=>$date,
														   'modified'=>$date]);

		$DmiCertQrCodes->save($DmiCertificateQrAdd);

		$qrimage = $DmiCertQrCodes->find('all',array('field'=>'qr_code_path','conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
		
		return $qrimage;
		
	}


	// Get QR Code for Replica
	// Author : Shankhpal Shende
	// Description : This will return QR code for Esigned of Chemist
	// Date : 19/08/2022

	public function getQrCodeEsignedChemist($tableRowData,$chemist_name,$firm_details){

		if(!empty($tableRowData)){
			
			$i=0;
			$data1 = '';
			$data2 = '';
			$data3 = '';
			foreach($tableRowData as $each){ 
				$data1 .=  $each['commodity_name'].",";
				$data2 .= $each['tbl_name'].",";
				$data3 .= $each['printer_name'].",";
			} 
		}
		
		$customer_id = $this->Session->read('username');
		$DmiCertQrCodes = TableRegistry::getTableLocator()->get('DmiCertQrCodes'); //initialize model in component
		$resultdata = $DmiCertQrCodes->find('all',array('conditions'=>array('customer_id'=>$customer_id)))->toArray();
		
		//if(count($resultdata) == 0){
			
			require_once(ROOT . DS .'vendor' . DS . 'phpqrcode' . DS . 'qrlib.php');
	
			//$data = "MECARD:N:".'Esigned By:'.$result[0].'(Chemist In-charge)'.";EMAIL:".$result[1]['firm_name']." ;";
			$data = "CA Id :".$firm_details['customer_id']."##"."Chemist Name :".$chemist_name."##"."commodity_name :".$data1."##"."TBL Name: ".$data2."##"."Printer Name: ".$data3;
			
			$qrimgname = rand();
			
			$server_imagpath = '/writereaddata/DMI/certificates/QRCodes/'.$qrimgname.".png";
			
			$file_path = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/certificates/QRCodes/'.$qrimgname.".png";
			
			$file_name = $file_path;
			
			QRcode::png($data,$file_name);
			
			
			$date = date('Y-m-d H:i:s');	
			
			$DmiCertificateQrAdd = $DmiCertQrCodes->newEntity(
													['customer_id'=>$customer_id,
													'qr_code_path'=>$server_imagpath,
													'created'=>$date,
													'modified'=>$date
													]);
			
			$DmiCertQrCodes->save($DmiCertificateQrAdd);
			
		//}
		
		$qrimage = $DmiCertQrCodes->find('all',array('field'=>'qr_code_path','conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
		
		return $qrimage;

	}





	//User Type
	//Description: Returns the user text or type against the username.
	//@Author : Akash Thakre
	//Date : 14-09-2022

	public function getUserType($username){

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






}
?>
