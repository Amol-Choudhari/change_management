<?php

	namespace App\Controller;

	use Cake\Event\Event;
	use Cake\Network\Session\DatabaseSession;
	use App\Network\Email\Email;
	use App\Network\Request\Request;
	use App\Network\Response\Response;
	use Cake\Datasource\ConnectionManager;
	use phpDocumentor\Reflection\Types\This;

	class MastersController extends AppController {

		var $name = 'Masters';

		// Initialized common variables
		private $message = '';
		private $message_theme = '';
		private $redirect_to = '';
		private $masterTable = '';
		private $masterListTitle = '';
		private $masterListHeader = '';
		private $masterAddTitle = '';
		private $masterEditTitle = '';

		// BEFORE FILTER
		public function beforeFilter($event) {

			parent::beforeFilter($event);

			$this->loadComponent('Commonlistingfunctions');
			$this->loadComponent('Customfunctions');
			$this->loadComponent('Mastertablecontent');
			$this->loadComponent('Randomfunctions');
			$this->loadComponent('Randomfunctions');

			$this->viewBuilder()->setHelpers(['Form','Html','Time']);
			$this->viewBuilder()->setLayout('admin_dashboard');

			$username = $this->Session->read('username');

			$this->loadModel('DmiStates');
			$this->loadModel('DmiUserRoles');
			$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('masters'=>'yes','user_email_id IS'=>$username)))->first();

			//check masters role given
			if (empty($user_access)) {

				echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->getRequest()->getAttribute('webroot');?>users/login_user">Please Login</a><?php
				exit();
			}
		}


		// MASTER HOME
		public function mastersHome() {
			//only view part
		}



		//Fetch the ID for Redirect Master
		public function fetchAndRedirect($id) {

			$this->autoRender = false;
			$this->Session->write('master_id',$id);
			$this->Redirect('/masters/list-master-records');
		}


		// SET MASTER DETAILS (function to set some common used variables values)
		public function setMasterDeatils($masterId) {

			//no. are given to each master in link, and used for identification here

			//[ 1 For State Master ]
			if ($masterId=='1') {

				$this->masterTable = 'DmiStates';
				$this->masterListTitle = 'List of All States';
				$this->masterListHeader = 'State Records';
				$this->masterAddTitle = 'Add New State';
				$this->masterEditTitle = 'Edit State';
				$this->fieldNameForCheck = 'state';

			//[ 2 For District Master ]
			} elseif($masterId=='2') {

				$this->masterTable = 'DmiDistricts';
				$this->masterListTitle = 'List of All Districts';
				$this->masterListHeader = 'District Records';
				$this->masterAddTitle = 'Add New District';
				$this->masterEditTitle = 'Edit District';
				$this->fieldNameForCheck = 'district';

			//[ 3 For Business Type Master ]
			} elseif ($masterId=='3') {

				$this->masterTable = 'DmiBusinessTypes';
				$this->masterListTitle = 'List of All Business Types';
				$this->masterListHeader ='Buisness Type';
				$this->masterAddTitle = 'Add Business Type';
				$this->masterEditTitle = 'Edit Business Type';
				$this->fieldNameForCheck = 'business_type';

			//[ 4 For Packing Type Master ]
			} elseif ($masterId=='4') {

				$this->masterTable = 'DmiPackingTypes';
				$this->masterListTitle = 'List of All Packing Types';
				$this->masterListHeader = 'Packing Type';
				$this->masterAddTitle = 'Add Packing Type';
				$this->masterEditTitle = 'Edit Packing Type';
				$this->fieldNameForCheck = 'packing_type';

			//[ 5 For Laboratory Type Master ]
			} elseif ($masterId=='5') {
				$this->masterTable = 'DmiLaboratoryTypes';
				$this->masterListTitle = 'List of All Laboratory Types';
				$this->masterListHeader = 'Laboratory Types';
				$this->masterAddTitle = 'Add Laboratory Type';
				$this->masterEditTitle = 'Edit Laboratory Type';
				$this->fieldNameForCheck = 'laboratory_type';

			//[ 6 For Machine Type Master ]
			} elseif ($masterId=='6') {
				$this->masterTable = 'DmiMachineTypes';
				$this->masterListTitle = 'List of All Machine Types';
				$this->masterListHeader = 'Machine Types';
				$this->masterAddTitle = 'Add Machine Type';
				$this->masterEditTitle = 'Edit Machine Type';
				$this->fieldNameForCheck = 'machine_type';

			//[ 7 For Tank Shape Master ]
			} elseif ($masterId=='7') {
				$this->masterTable = 'DmiTankShapes';
				$this->masterListTitle = 'List of All Tank Shapes';
				$this->masterListHeader = 'Shape Types';
				$this->masterAddTitle = 'Add Tank Shape';
				$this->masterEditTitle = 'Edit Tank Shape';
				$this->fieldNameForCheck = 'tank_shape';

			//[ 8 For Application Charges Master ]
			} elseif ($masterId=='8') {
				$this->masterTable = 'DmiApplicationCharges';
				$this->masterListTitle = 'List of All Applications Charges';
				$this->masterListHeader = 'Application Charges';
				$this->masterAddTitle = 'Add Application Charge';
				$this->masterEditTitle = 'Edit Application Charge';
				$this->fieldNameForCheck = 'appl_charges';

			//[ 9 For Business Years Master ]
			} elseif ($masterId=='9') {

				/*NOTE: 3 tables used, so mastertable variables not set here*/
				$this->masterAddTitle = 'Add Business year';
				$this->masterEditTitle = 'Edit Business Year';
				$this->masterListHeader = 'Buisness Years';
				$this->masterTable = 'DmiBusinessTypes';
				$this->masterListTitle = 'List of All Buisness Years';
				$this->fieldNameForCheck = 'business_years';

			//[ 10 For RO Offices Master ]
			} elseif ($masterId=='10') {
				$this->masterTable = 'DmiRoOffices';
				$this->masterListTitle = 'List of All Offices';
				$this->masterListHeader = 'DMI RO Offices';
				$this->masterAddTitle = 'Add New Office';
				$this->masterEditTitle = 'Edit Office Details';
				$this->fieldNameForCheck = 'office';

			//[ 11 For SMS/Email Templates Master ]
			} elseif ($masterId=='11') {
				$this->masterTable = 'DmiSmsEmailTemplates';
				$this->masterListTitle = 'List of All SMS/Email Templates';
				$this->masterListHeader = 'DMI SMS EMAIL Templates';
				$this->masterAddTitle = 'Add New Template';
				$this->masterEditTitle = 'Edit Template';
				$this->fieldNameForCheck = 'template';

			//[ 12 For PAO/DDO Master ]
			} elseif ($masterId=='12') {

				/*NOTE: mastertable variables not set here*/
				$this->masterListTitle = 'List of All PAO/DDO';
				$this->masterAddTitle = 'Set New PAO/DDO';
				$this->masterEditTitle = 'Edit PAO/DDO';
				$this->masterListHeader = 'PAO/DDP';
				$this->masterTable = '';
				$this->fieldNameForCheck = 'pao-ddo';

			//[ 15 For Feedback Types Master ]
			} elseif ($masterId=='15') {

				$this->masterTable = 'DmiFeedbackTypes';
				$this->masterListTitle = 'List of All Feedback Types';
				$this->masterAddTitle = 'Set New Type';
				$this->masterEditTitle = 'Edit Type';
				$this->masterListHeader = 'Other';
				$this->fieldNameForCheck = 'feedback_type';

			//[ 16 For the Replica Charges Master ]
			} elseif ($masterId=='16') {
				/*NOTE: mastertable variables not set here*/
				$this->masterTable = 'DmiReplicaChargesDetails';
				$this->masterListTitle = 'List of Replica Charges';
				$this->masterAddTitle = 'Add Replica Charge';
				$this->masterEditTitle = 'Edit Replica Charge';
				$this->masterListHeader = 'Replica';
				$this->fieldNameForCheck = 'replica_charges';

			//17 For the Education Type Master
			} elseif ($masterId=='17') {
				//for education, added on 24-08-2021 by Akash
				$this->masterTable = 'DmiEducationTypes';
				$this->masterListTitle = 'List of All Education Types';
				$this->masterListHeader = 'Education Type';
				$this->masterAddTitle = 'Add Education Type';
				$this->masterEditTitle = 'Edit Education Type';
				$this->fieldNameForCheck = 'education_types';

			//18 For the Division Type Master
			} elseif ($masterId=='18') {
				//for division, added on 24-08-2021 by Akash
				$this->masterTable = 'DmiDivisionGrades';
				$this->masterListTitle = 'List of All Division Grade';
				$this->masterListHeader = 'Division Grade';
				$this->masterAddTitle = 'Add Division Grade';
				$this->masterEditTitle = 'Edit Division Grade';
				$this->fieldNameForCheck = 'division_grade';
			
			//19 For the Documents List Master
			} elseif ($masterId=='19') {
				//for division, added on 09-08-2022 by Akash
				$this->masterTable = 'DmiDocumentLists';
				$this->masterListTitle = 'List of All Documents';
				$this->masterListHeader = 'Documents List';
				$this->masterAddTitle = 'Add Documents Type';
				$this->masterEditTitle = 'Edit Documents Type';
				$this->fieldNameForCheck = 'document_type';
			}
		
		}



		// LIST MASTER RECORDS (common function to list master recrods)
		public function listMasterRecords() {
			
			
			$masterId = $this->Session->read('master_id');//masterId will be read from the session example 1 - state, 9-business years etc
			$this->setMasterDeatils($masterId);//set masters common variables
			$masterTable = $this->masterTable;
			$masterListTitle = $this->masterListTitle;
			$masterListHeader = $this->masterListHeader;

			//search and pagination code removed as we are using datatables script with search and pagination option on view side.

			if (!empty($masterTable)) {

				$this->loadModel($masterTable);
				$all_records = $this->$masterTable->find('all',array('order' => array('id' => 'asc'),'conditions'=>array('OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->toArray();
				
			} else {

				$all_records=array();
			}


			//For District
			if ($masterId=='2') {

				$this->Mastertablecontent->listStateForDistrictMaster($all_records);

			//For All Business Years
			} elseif ($masterId=='9') {

				$this->loadModel('DmiCaBusinessYears');
				$this->loadModel('DmiPrintingBusinessYears');
				$this->loadModel('DmiCrushingRefiningPeriods');
				$ca_business_years = $this->DmiCaBusinessYears->find('all',array('order' => array('id' => 'asc'),'conditions'=>array('delete_status IS NULL')))->toArray();
				$pp_business_years = $this->DmiPrintingBusinessYears->find('all',array('order' => array('id' => 'asc'),'conditions'=>array('delete_status IS NULL')))->toArray();
				$crush_refine_years = $this->DmiCrushingRefiningPeriods->find('all',array('order' => array('id' => 'asc'),'conditions'=>array('delete_status IS NULL')))->toArray();

				$this->set(compact('ca_business_years','masterId','pp_business_years','crush_refine_years'));

			//For PAO/DDO
			} elseif ($masterId=='12') {

				$pao_name_list = array();
				$this->loadModel('DmiPaoDetails');
				$this->loadModel('DmiUsers');
				$this->loadModel('DmiUserRoles');
				$this->$masterTable = 'DmiPaoDetails';

				// check activated user condition to make pao user list
				$pao_user_id_list = $this->DmiPaoDetails->find('all',array('joins'=>array(
					array('table' => 'dmi_users','alias' => 'users','type' => 'INNER','conditions' => array( 'Dmi_pao_detail.pao_user_id::integer = users.id','users.status !='=>'disactive')),
					array('table' => 'dmi_user_roles','alias' => 'u_roles','type' => 'INNER','conditions' => array( 'users.email = u_roles.user_email_id', 'u_roles.pao'=>'yes'))),
					'fields'=>array('id','pao_user_id'),'order'=>'id asc','conditions'=>array()))->toArray();

				if (!empty($pao_user_id_list)) {

					$i=0; $j=0;

					foreach ($pao_user_id_list as $pao_user_id) {

						$user_details = $this->DmiUsers->find('all',array('conditions'=>array('id IS'=>$pao_user_id['pao_user_id'],'status'=>'active')))->toArray();//added status cond on 05-01-2022

						//Check user id
						if (!empty($user_details)) {

							$pao_id_list[$j] = $pao_user_id['id'];

							foreach ($user_details as $user_detail) {

								$user_full_name = $user_detail['f_name'].' '.$user_detail['l_name'];
								$pao_name_list[$i] = $user_full_name.'('.base64_decode($user_detail['email']).')'; //for email encoding
								$i=$i+1;
							}

							$j=$j+1;
						}
					}
				}

				$this->set(compact('pao_id_list','pao_name_list'));

			// For Re-Esign
			} elseif ($masterId=='13') {

				//provide Applications for resign
				$this->redirect(array('controller'=>'masters','action'=>'add-appl-for-re-esign'));

			// For Extend Dates
			} elseif ($masterId=='14') {

				$this->redirect(array('controller'=>'masters','action'=>'extend-dates'));

			// For Replica Charges
			} elseif ($masterId=='16') {

				//For Replica Module - Below function is added for Listing all replica Charges, added on 24-08-2021 By Akash.

				//Load Model
				$this->loadModel('DmiReplicaChargesDetails');
				$this->loadModel('MCommodityCategory');
				$this->loadModel('MCommodity');

				//Get All Replica Charges
				$all_replica_charges = $this->DmiReplicaChargesDetails->find('all',array('order' => array('id' => 'asc')))->toArray();
				$this->set('all_replica_charges',$all_replica_charges);
				
				if (!empty($all_replica_charges)) {

					$i=0;

					foreach ($all_replica_charges as $replica_details) {

						//get category name
						$get_category = $this->MCommodityCategory->find('all',array('fields'=>'category_name','conditions'=>array('category_code IS'=>$replica_details['category_code'],'display'=>'Y')))->first();
						//get commodity
						$get_commodity = $this->MCommodity->find('all',array('fields'=>'commodity_name','conditions'=>array('commodity_code IS'=>$replica_details['commodity_code'],'display'=>'Y')))->first();

						if (!empty($get_category) && !empty($get_commodity)) {

							$category[$i] = $get_category['category_name'];
							$commodity[$i] = $get_commodity['commodity_name'];
						}

						$i=$i+1;

						$this->set('category',$category);
						$this->set('commodity',$commodity);
						$this->set('charge',$replica_details['charges']);
						$this->set('unit',$replica_details['unit']);
						$this->set('min_qty',$replica_details['min_qty']);
					}
				}
			}

			// Set all the Values
			$this->set(compact('all_records','masterId','masterListTitle','masterTable','masterListHeader'));

		}


		// ADD MASTER RECORD (common function to add master record)
		public function addMasterRecord() {

			$masterId = $this->Session->read('master_id');
			$this->setMasterDeatils($masterId);//set masters common variables
			$masterTable = $this->masterTable;
			$masterAddTitle = $this->masterAddTitle;
			$masterListHeader = $this->masterListHeader;

			//Load Models
			$this->loadModel('DmiStates');
			$this->loadModel('DmiRoOffices');
			$this->loadModel('DmiUserRoles');
			$this->loadModel('DmiUsers');
			$this->loadModel('DmiPaoDetails');
			$this->loadModel('DmiUsers');
			$this->loadModel('DmiUserRoles');
			$this->loadModel('DmiStates');
			$this->loadModel('DmiDistricts');
			$form_id = '';

			if (!empty($masterTable)) {

				$this->loadModel($masterTable);
			}

			//on default page load

			//For District Master
			if ($masterId=='2') {

				// to show states list
				$state_list = $this->DmiStates->find('list',array('keyField'=>'id','valueField'=>'state_name','order'=>'state_name','conditions'=>array('delete_status IS NULL')))->toArray();
				// to show RO Offices list
				$ro_offices_list = $this->DmiRoOffices->find('list',array('keyField'=>'id','valueField'=>'ro_office','conditions'=>array('office_type'=>'RO','delete_status IS NULL'),'order'=>'ro_office'))->toArray();
				// to show SO Offices list
				$so_offices_list = $this->DmiRoOffices->find('list',array('keyField'=>'id','valueField'=>'ro_office','conditions'=>array('office_type'=>'SO','delete_status IS NULL'),'order'=>'ro_office'))->toArray();
				$this->set(compact('state_list','ro_offices_list','so_offices_list','masterListHeader'));
			
			// For Office Master 
			} elseif ($masterId=='8') {
				
				//get the application type for the dropdown from selection of application type and firm type added on the 09-04-2022 by Akash
				$this->loadModel('DmiApplicationTypes');
				$this->loadModel('DmiCertificateTypes');

				$applicationTypes = $this->DmiApplicationTypes->find()->select(['id','application_type'])->where(['delete_status IS NULL'])->order(['id'=>'ASC'])->combine('id','application_type')->toArray();
				$firmType = $this->DmiCertificateTypes->find('list',array('keyField'=>'id','valueField'=>'certificate_type','order'=>'id ASC'))->toArray();
				$this->set(compact('applicationTypes','firmType'));

			// For Office Master
			} elseif ($masterId=='10') {
				
				$form_id = 'add_new_office'; //added for form_id for add master of office added by Akash on 02-12-2021
				//to get RO incharge users list
				$all_ro_list = $this->DmiUserRoles->find('list',array('keyField'=>'user_email_id','valueField'=>'user_email_id','conditions'=>array('ro_inspection'=>'yes')))->toArray();
				//to show LMIS or Both user list to add RAL office
				$all_ral_list = $this->DmiUsers->find('list',array('keyField'=>'email','valueField'=>'email','conditions'=>array('division IN'=>array('LMIS','BOTH'),'status'=>'active')))->toArray();
				//to get SO incharge users list
				$all_so_list = $this->DmiUserRoles->find('list',array('keyField'=>'user_email_id','valueField'=>'user_email_id','conditions'=>array('so_inspection'=>'yes')))->toArray();
				//to fetch ro offices list
				$ro_office_list = $this->DmiRoOffices->find('list',array('keyField'=>'id','valueField'=>'ro_office','conditions'=>array('office_type'=>'RO','delete_status IS NULL'),'order'=>'ro_office ASC'))->toArray();

				//added below loop //for email encoding
				$newArray = array();
				foreach ($all_ro_list as $key => $emailId) {

					$newArray[$key] = base64_decode($emailId);
				}
				
				$all_ro_list = $newArray;

				$newArray = array();
				foreach ($all_ral_list as $key => $emailId) {

					$newArray[$key] = base64_decode($emailId);
				}
				
				$all_ral_list = $newArray;

				$newArray = array();
				foreach ($all_so_list as $key => $emailId) {

					$newArray[$key] = base64_decode($emailId);
				}
				$all_so_list = $newArray;
				//till here

				$this->set(compact('all_ro_list','all_ral_list','all_so_list','ro_office_list','masterListHeader'));

			//For PAO/DDO
			} elseif ($masterId=='11') {

				$form_id = 'add_sms_template';

			} elseif ($masterId=='12') {

				$form_id = 'set_pao';
				$already_added_pao_user_id = $this->DmiPaoDetails->find('list',array('keyField'=>'id','valueField'=>'pao_user_id'))->toList();

				// get only active userlist.
				$user_email_id_list = array();

				if (!empty($already_added_pao_user_id)) {
					$user_email_id_list = $this->DmiUsers->find('list',array('keyField'=>'id','valueField'=>'email','conditions'=>array('id IN'=>$already_added_pao_user_id,'OR'=>array('status IS NULL','status'=>'active'))))->toArray();
				}

				// Delete pao record id from session.
				$this->Session->delete('record_id');

				// to show pao email id list
				$pao_email_id_list = null;
				$all_pao_email_id_list = $this->DmiUserRoles->find('list',array('keyField'=>'id','valueField'=>'user_email_id','conditions'=>array('pao'=>'yes')))->toList();

				$user_id_details = array();

				if (!empty($all_pao_email_id_list)) {
					$user_id_details = $this->DmiUsers->find('list',array('keyField'=>'id','valueField'=>'email','conditions'=>array('email IN'=>$all_pao_email_id_list)))->toArray();
				}

				$pao_email_id_list = array_diff($user_id_details,$user_email_id_list);

				//added below loop //for email encoding
				$newArray = array();
				foreach ($pao_email_id_list as $key => $emailId) {

					$newArray[$key] = base64_decode($emailId);
				}
				$pao_email_id_list = $newArray;
				//till here

				$all_states = $this->DmiStates->find('list', array('keyField'=>'id','valueField'=>'state_name','conditions'=>array('delete_status IS NULL'),'order'=>'state_name'))->toArray();

				// to show district list
				$district_name_list = null;
				$district_name_list = $this->DmiDistricts->find('list',array('keyField'=>'id','valueField'=>'district_name','conditions'=>array('state_id'=>1),'order'=>'id'))->toArray();
				$this->set(compact('district_name_list','all_states','pao_email_id_list','masterListHeader'));

			//For Replica
			} elseif ($masterId=='16') {

				//Load Models
				$this->loadModel('DmiReplicaUnitDetails');
				$this->loadModel('MCommodityCategory');
				$this->loadModel('MCommodity');

				// to show category list
				$commodity_categories = $this->MCommodityCategory->find('list',array('keyField'=>'category_code','valueField'=>'category_name','conditions'=>array('display'=>'Y')))->toArray();
				//to show the defined unit
				$unit = $this->DmiReplicaUnitDetails->find('list',array('keyField'=>'id','valueField'=>'sub_unit','conditions'=>array('delete_status IS NULL')))->toArray();
				//Set the All Varibles
				$this->set(compact('commodity_categories','unit'));
			}


			//when POST data sent from Form
			if ($this->request->is('post')) {

				$postData = $this->request->getData();

				//For Duplicate State Name check the state name is exist on 04-12-2021 by AKASH
				if ($masterId == '1') {
					
					$this->loadModel('DmiStates');
					$check_if_exist = $this->DmiStates->find()->select(['state_name'])->where(['state_name' => $postData['state_name'],'delete_status IS NULL'])->first();
					if (!empty($check_if_exist)) {

						$duplicate_state_name = 'yes';
						$this->set(compact('duplicate_state_name','masterId','masterAddTitle','masterTable','masterListHeader','form_id'));
						$this->set('duplicate_state_name','The State Name you entered is Already Exists! Please Enter Another.');
						return null;
						exit;
					}
				}


				//For District Name check the state name is exist on 04-12-2021 by AKASH
				if ($masterId == '2') {
					
					$this->loadModel('DmiDistricts');
					$check_if_exist = $this->DmiDistricts->find()->select(['district_name'])->where(['district_name' => $postData['district_name'],'delete_status IS NULL'])->first();
					if (!empty($check_if_exist)) {

						$duplicate_district_name = 'yes';
						$this->set(compact('duplicate_district_name','masterId','masterAddTitle','masterTable','masterListHeader','form_id'));
						$this->set('duplicate_district_name','The District Name you entered is Already Exists! Please Enter Another.');
						return null;
						exit;
					}
				}

				//for Office Master to avoid duplicate District Short code on 03-12-2021 by AKASH
				if ($masterId=='10') {
					
					$form_id = 'add_office';
					//check duplicate short code in table to avoid
					
					//check only when adding RO, there is no short code for RAL
					if ($postData['office_type']=='RO' || $postData['office_type']=='SO') {
						
						//check if replica code is exists in database or not added on 03-12-2021 by AKASH 	
						$check_replica_code = $this->DmiRoOffices->find('all')->select(['replica_code'])->where(['OR' => [['delete_status IS NULL'] ,['delete_status ='=>'no']]])->where(['replica_code' => $postData['replica_code']])->first();

						$check_short_code = $this->DmiRoOffices->find('all',array('conditions'=>array('short_code IS'=>$postData['short_code'],'delete_status IS NULL')))->first();
						
						//Added below validations to check the District Short Code & Office Code for 15 digit code is already exists and set the message on 03-12-2021 By AKASH
						if (!empty($check_short_code || $check_replica_code)) {

							$duplicate_short_code = 'yes';
							$this->set(compact('duplicate_short_code','masterId','masterAddTitle','masterTable','masterListHeader','form_id'));
								
							if (!empty($check_short_code)) {
								$this->set('duplicate_code_msg','The  Office District Code you entered is Already Exists! Please Enter Another.');
							} else {
								$this->set('duplicate_code_msg','The Office Code for 15-Digit Code you is Already Exists! Please Enter Another.');
							}
						
							return null;
							exit;
						}
					}
				}

				//call to common Add/edit calling function
				$record_id = null;//for Add Master
				$this->callAddEditCommonFunctions($masterId,$postData,$record_id);

				$this->redirect_to = 'list-master-records';
				$this->set('message',$this->message);
				$this->set('message_theme',$this->message_theme);
				$this->set('redirect_to',$this->redirect_to);
			
			}

			$this->set(compact('masterId','masterAddTitle','masterTable','form_id','masterListHeader'));
		}



		// EDIT FETCH AND REDIRECT (To Fetch Record Id Redirect To Edit Common Function)
		public function editfetchAndRedirect($record_id,$optional_param=null) {

			$this->autoRender = false;
			$this->Session->write('record_id',$record_id);

			//used optionaly if required extra parameter
			$this->Session->write('edit_optional_param',$optional_param);
			$this->Redirect('/masters/edit-master-record');
		}



		// EDIT MASTER RECORD
		public function editMasterRecord() {

			$masterId = $this->Session->read('master_id');
			$record_id = $this->Session->read('record_id');
			$this->setMasterDeatils($masterId);//set masters common variables
			$masterTable = $this->masterTable;
			$masterEditTitle = $this->masterEditTitle;
			$masterListHeader = $this->masterListHeader;

			//Load Models
			$this->loadModel('DmiStates');
			$this->loadModel('DmiRoOffices');
			$this->loadModel('DmiUsers');
			$this->loadModel('DmiUserRoles');
			$this->loadModel('DmiPaoDetails');
			$this->loadModel('DmiDistricts');
			$form_id = '';

			//get record details with common query
			if (!empty($masterTable)) {
				$this->loadModel($masterTable);
				$record_details = $this->$masterTable->find('all',array('conditions'=>array('id IS'=>$record_id)))->first();
			} else {
				$record_details = array();
			}


			//on default page load
			if ($masterId=='1') {
				
				//no extra executions//

			// For District Master
			} elseif ($masterId=='2') {

				$entered_district = $record_details['district_name'];
				$selected_state_id = $record_details['state_id'];
				$selected_ro_office_id = $record_details['ro_id'];
				$selected_so_office_id = $record_details['so_id'];
				$this->set(compact('entered_district','selected_state_id','selected_ro_office_id','selected_so_office_id','masterEditTitle','masterListHeader'));

				// to show states list
				$state_list = $this->DmiStates->find('list',array('keyField'=>'id','valueField'=>'state_name','order'=>'state_name','conditions'=>array('delete_status IS NULL')))->toArray();
				// to show RO Offices list
				$ro_offices_list = $this->DmiRoOffices->find('list',array('keyField'=>'id','valueField'=>'ro_office','conditions'=>array('office_type'=>'RO','delete_status IS NULL'),'order'=>'ro_office'))->toArray();
				// to show SO Offices list
				$so_offices_list = $this->DmiRoOffices->find('list',array('keyField'=>'id','valueField'=>'ro_office','conditions'=>array('office_type'=>'SO','delete_status IS NULL'),'order'=>'ro_office'))->toArray();
				//Set the Values
				$this->set(compact('state_list','ro_offices_list','so_offices_list'));
			
			// For All Charges
			} elseif ($masterId=='8') {

				//Modified the charges edit function of All charges to add the values of Applucation Type and Firm Type on 09-04-2022 by Akash
				$selectedApplicationType = $record_details['application_type_id'];
				$selectedFirmType = $record_details['firm_type'];
				$this->set(compact('selectedApplicationType','selectedFirmType'));

				//get the application type for the dropdown from selection of application type and firm type added on the 09-04-2022 by Akash
				$this->loadModel('DmiApplicationTypes');
				$this->loadModel('DmiCertificateTypes');

				$applicationTypes = $this->DmiApplicationTypes->find()->select(['id','application_type'])->where(['delete_status IS NULL'])->order(['id'=>'ASC'])->combine('id','application_type')->toArray();
				$firmType = $this->DmiCertificateTypes->find('list',array('keyField'=>'id','valueField'=>'certificate_type','order'=>'id ASC'))->toArray();
				$this->set(compact('applicationTypes','firmType'));

			// For Business Years Master
			} elseif ($masterId=='9') {

				$years_for = $this->Session->read('edit_optional_param');

				//for CA
				if ($years_for=='0') {

					$this->loadModel('DmiCaBusinessYears');
					$record_details = $this->DmiCaBusinessYears->find('all',array('conditions'=>array('id IS'=>$record_id)))->first();

				//for Printing
				} elseif ($years_for=='1') {

					$this->loadModel('DmiPrintingBusinessYears');
					$record_details = $this->DmiPrintingBusinessYears->find('all',array('conditions'=>array('id IS'=>$record_id)))->first();

				//for Crushing Refining
				} elseif ($years_for=='2') {

					$this->loadModel('DmiCrushingRefiningPeriods');
					$record_details = $this->DmiCrushingRefiningPeriods->find('all',array('conditions'=>array('id IS'=>$record_id)))->first();
				}


			// For Offices Master
			} elseif ($masterId=='10') {

				//Fetch current allocated Ro incharge details and list of Ro incharge user rols name list
				$user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$record_details['ro_email_id'])))->first();
				//print_r($record_details); exit;
				$ro_incharge_name = $user_details['f_name'].' '.$user_details['l_name'];
				$ro_incharge_mobile_no = $user_details['phone'];

				//get incharge listing
				$ro_incharge_name_list = $this->getInchargeNameList($record_details['office_type']);

				//to show LMIS or Both user list to add RAL office
				$all_ral_list = $this->DmiUsers->find('list',array('keyField'=>'email','valueField'=>'email','conditions'=>array('division IN'=>array('LMIS','BOTH'))))->toArray();
				
				$newArray = array();
				foreach ($all_ral_list as $key => $emailId) {
					
					$newArray[$key] = base64_decode($emailId);
				}

				$all_ral_list = $newArray;
				
				//to fetch ro offices list
				$ro_office_list = $this->DmiRoOffices->find('list',array('keyField'=>'id','valueField'=>'ro_office','conditions'=>array('office_type'=>'RO','delete_status IS NULL'),'order'=>'ro_office ASC'))->toArray();

				$this->set(compact('ro_incharge_name','ro_incharge_mobile_no','ro_incharge_name_list','all_ral_list','ro_office_list','masterEditTitle','masterListHeader'));


			// For SMS/Email Templates
			} elseif ($masterId=='11') {

				//added on NOV16 for edit template form ID
				$form_id = 'edit_sms_template';
				//Existed values from table
				$existed_destination_values = $record_details['destination'];
			
				if ($existed_destination_values == null) {
					$existed_destination_array = array();
				} else {
					$existed_destination_array = explode(',',$existed_destination_values);
				}

				$this->set(compact('existed_destination_array'));

			// For PAO/DDO
			} elseif ($masterId=='12') {
				
				$form_id = 'edit_pao';
				$pao_id = $this->Session->read('record_id');
		
				$all_pao_details = $this->DmiPaoDetails->find('all',array('conditions'=>array('id IS'=>$pao_id)))->first();
			
				$pao_user_id_list =	$all_pao_details['pao_user_id'];
				$pao_alias_name =	$all_pao_details['pao_alias_name'];

				$selected_pao_email_id = $this->DmiUsers->find('all',array('fields'=>'email','conditions'=>array('id IS'=>$pao_user_id_list)))->first();
				$selected_district_list = $this->DmiDistricts->find('list',array('valueField'=>'id','conditions'=>array('pao_id IS'=>$pao_id),'order'=>'id'))->toArray();
			
				// Find selected state list (Done By pravin 22/11/2017)
				$selected_state_list = $this->DmiDistricts->find('list',array('keyField'=>'id','valueField'=>'state_id','conditions'=>array('pao_id IS'=>$pao_id),'order'=>'id','group'=>array('id','state_id')))->toList();
				// find all state list (Done By pravin 22/11/2017)
				$all_states = $this->DmiStates->find('list', array('keyField'=>'id','valueField'=>'state_name','conditions'=>array('OR'=>array('delete_status IS NULL','delete_status ='=>'no')),'order'=>'state_name'))->toArray();
				
				// Get only those  district name list which are not allocated to another user. (update on 08-05-2018 by pravin)
				// to show district list
				$district_name_list = array();
			
				if (!empty($selected_state_list)) {
					$district_name_list = $this->DmiDistricts->find('list',array('keyField'=>'id','valueField'=>'district_name','conditions'=>array('state_id IN'=>$selected_state_list,'OR'=>array(array('pao_id'=>1), array('pao_id IS'=>$pao_id),array('pao_id IS NULL'))),'order'=>'id'))->toArray();
				}

				$this->set(compact('district_name_list','all_states','selected_pao_email_id','pao_alias_name','selected_district_list','selected_state_list','masterEditTitle','masterListHeader'));


			// For the Replica Charges Edit Function
			} elseif ($masterId=='16') {

				//Load Models
				$this->loadModel('DmiReplicaChargesDetails');
				$this->loadModel('MCommodityCategory');
				$this->loadModel('MCommodity');

				//Get the Replica Charge Details
				$replica_charge_details = $this->DmiReplicaChargesDetails->find('all',array('conditions'=>array('id'=>$record_id)))->first();

				//Set the Selected & Entered Replica Charge Details like Charge,Category,Commodity,Quantity,Unit
				$entered_charge = $replica_charge_details['charges'];
				$selected_category = $replica_charge_details['category_code'];
				$selected_commodity = $replica_charge_details['commodity_code'];
				$entered_qty = $replica_charge_details['min_qty'];
				$selected_unit = $replica_charge_details['unit'];
				$replica_code = $replica_charge_details['replica_code'];	
				//To Get the Commonodity Categories
				$commodity_categories = $this->MCommodityCategory->find('list',array('keyField'=>'category_code','valueField'=>'category_name','conditions'=>array('display'=>'Y')))->toArray();
				//Get the Commodities Entered
				$getEneteredCommodity = $this->MCommodity->find('all',array('fields'=>'commodity_name','conditions'=>array('commodity_code'=>$selected_commodity)))->first();
				$entered_commodity = $getEneteredCommodity['commodity_name'];

				//Set the All Varibles
				$this->set(compact('commodity_categories','entered_commodity','entered_charge','entered_qty','selected_unit','replica_code','masterEditTitle','masterListHeader'));
			}


			//when POST data sent from Form
			if ($this->request->is('post')) {

				$postData = $this->request->getData();
				//call to common Add/edit calling function
			
				$this->callAddEditCommonFunctions($masterId,$postData,$record_id);
			
				$this->redirect_to = 'edit-master-record';

				$this->set('message',$this->message);
				$this->set('message_theme',$this->message_theme);
				$this->set('redirect_to',$this->redirect_to);
			}

			$this->set(compact('masterId','masterEditTitle','masterTable','record_details','form_id','masterEditTitle','masterListHeader'));
		}



		// DELETE MASTER RECORD (common function to Delete master Record)
		public function deleteMasterRecord($record_id) {

			$this->autoRender = false;
			$this->Session->write('del_rec_id',$record_id);
			$this->redirect('/masters/deleteMasterRecordCall');
		}


		//DELETE MASTER RECORD CALL
		public function deleteMasterRecordCall() {

			$record_id = $this->Session->read('del_rec_id');
			$masterId = $this->Session->read('master_id');
			$this->setMasterDeatils($masterId);//set masters common variables
			$masterTable = $this->masterTable;
			$this->loadModel($masterTable);

			$checkFieldName = $this->fieldNameForCheck;

			//custom function to save master actions logs
			$this->saveMasterActionLogs($masterId,null,$record_id);

			if ($this->Randomfunctions->checkIfMasterValueUsed($record_id,$checkFieldName)==1) {

				$masterTableEntity = $this->$masterTable->newEntity(array(
					'id'=>$record_id,
					'delete_status'=>'yes',
					'modified'=>date('Y-m-d H:i:s')));

				if ($this->$masterTable->save($masterTableEntity)) {
					
					//Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint($checkFieldName.' Master (Delete)', 'Success');
					$this->message = 'The selected record has been deleted successfully.';
					$this->message_theme = 'success';
				}

			} else {

				//Added this call to save the user action log on 21-02-2022 by Akash
				$this->Customfunctions->saveActionPoint($checkFieldName.' Master (Delete)', 'Failed');
				$this->message = 'Unable to edit/delete this record, as it is currently exist with some applications';
				$this->message_theme = 'failed';

			}

			$this->redirect_to = 'list-master-records';
			$this->set('message',$this->message);
			$this->set('message_theme',$this->message_theme);
			$this->set('redirect_to',$this->redirect_to);

		}


		
		// CALL ADD EDIT COMMON FUNCTIONS (function to common call to Add/Edit functions from components called in Add master and Edit Master functions above , record id will be null in Add master call)
		public function callAddEditCommonFunctions($masterId,$postData,$record_id) {
			
			if ($record_id == null) {
				$action_var = 'Added';
				$forActionLog = 'Add';
			} else {
				$action_var = 'Edited';
				$forActionLog = 'Edit';
			}
			$checkFieldName = $this->fieldNameForCheck;
			
			//custom function to save master actions logs
			$this->saveMasterActionLogs($masterId,$postData,$record_id);
			
			// For State
			if ($masterId=='1') {

				if ($this->Randomfunctions->checkIfMasterValueUsed($record_id,$checkFieldName)==1) {

					if ($this->Mastertablecontent->addEditStateMaster($postData,$record_id)) {

						//Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('State Master '."($forActionLog)", 'Success');
						$this->message = 'You have '.$action_var.' State Successfully';
						$this->message_theme = 'success';
					}

				} else {

					//Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('State Master '."($forActionLog)", 'Failed');
					$this->message = 'Unable to edit/delete this record, as it is currently exist with some applications';
					$this->message_theme = 'failed';
				}

			// For District
			} elseif ($masterId=='2') {

				//custom function to check district id in use
				if ($this->checkIfDistrictInUse($record_id)==true) {

					if ($this->Mastertablecontent->addEditDistrictMaster($postData,$record_id)) {

						//Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('District Master '."($forActionLog)", 'Success');
						$this->message = 'You have '.$action_var.' District Successfully';
						$this->message_theme = 'success';
					}

				} else {

					//Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('District Master '."($forActionLog)", 'Failed');
					$this->message = 'Unable to edit/delete this record, as it is currently exist with some applications';
					$this->message_theme = 'failed';
				}

			// For Buisness Type
			} elseif ($masterId=='3') {

				if ($this->Randomfunctions->checkIfMasterValueUsed($record_id,$checkFieldName)==1) {

					if ($this->Mastertablecontent->addEditBusinessTypeMaster($postData,$record_id)) {

						//Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('Buisness Master '."($forActionLog)", 'Success');
						$this->message = 'You have '.$action_var.' Business Type Successfully';
						$this->message_theme = 'success';
					}

				} else {

					//Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Buisness Master '."($forActionLog)", 'Failed');
					$this->message = 'Unable to edit/delete this record, as it is currently exist with some applications';
					$this->message_theme = 'failed';
				}

			// For Packeging Material
			} elseif ($masterId=='4') {

				//query to get packaging materials id from dmi_firms table fro printing applications
				//on 19-08-2017 by Amol
				$this->loadModel('DmiFirms');
				$get_packing_type_list = $this->DmiFirms->find('all',array('conditions'=>array('packaging_materials != NULL')))->toArray();

				$packing_type_found = 'no';
				foreach ($get_packing_type_list as $each_record) {

					$packing_type_list = $each_record['packaging_materials'];
					$packing_type_array = explode(',',$packing_type_list);

					if (in_array($record_id, $packing_type_array)) {

						$packing_type_found = 'yes';
					}
				}

				if ($packing_type_found == 'no') {

					if ($this->Mastertablecontent->addEditPackingTypeMaster($postData,$record_id)) {
						//Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('Packing Master '."($forActionLog)", 'Success');
						$this->message = 'You have '.$action_var.' Packing Type Successfully';
						$this->message_theme = 'success';
					}

				} else {

					//Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Packing Master '."($forActionLog)", 'Failed');
					$this->message = 'Unable to edit/delete this record, as it is currently exist with some applications';
					$this->message_theme = 'failed';
				}

			// For Laboratory Type
			} elseif ($masterId=='5') {

				if ($this->Randomfunctions->checkIfMasterValueUsed($record_id,$checkFieldName)==1) {

					if ($this->Mastertablecontent->addEditLaboratoryTypeMaster($postData,$record_id)) {

						//Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('Laboratory Master '."($forActionLog)", 'Success');
						$this->message = 'You have '.$action_var.' Laboratory Type Successfully';
						$this->message_theme = 'success';
					}

				} else {

					//Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Laboratory Master '."($forActionLog)", 'Failed');
					$this->message = 'Unable to edit/delete this record, as it is currently exist with some applications';
					$this->message_theme = 'failed';
				}

			// For Machine Type
			} elseif ($masterId=='6') {

				if ($this->Randomfunctions->checkIfMasterValueUsed($record_id,$checkFieldName)==1) {

					if ($this->Mastertablecontent->addEditMachineTypeMaster($postData,$record_id)) {

						//Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('Machine Master '."($forActionLog)", 'Success');
						$this->message = 'You have '.$action_var.' Machine Type Successfully';
						$this->message_theme = 'success';
					}

				} else {

					//Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Machine Master '."($forActionLog)", 'Failed');
					$this->message = 'Unable to edit/delete this record, as it is currently exist with some applications';
					$this->message_theme = 'failed';
				}

			// For Tank Shapes
			} elseif ($masterId=='7') {

				if ($this->Randomfunctions->checkIfMasterValueUsed($record_id,$checkFieldName)==1) {

					if ($this->Mastertablecontent->addEditTankShapesMaster($postData,$record_id)){

						//Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('Tank Master '."($forActionLog)", 'Success');
						$this->message = 'You have '.$action_var.' Tank Shape Successfully';
						$this->message_theme = 'success';

					}

				} else {

					//Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Tank Master '."($forActionLog)", 'Failed');
					$this->message = 'Unable to edit/delete this record, as it is currently exist with some applications';
					$this->message_theme = 'failed';
				}

			// For Charge
			} elseif ($masterId=='8') {

				//if($this->Randomfunctions->checkIfMasterValueUsed($record_id,$checkFieldName)==1){
				if ($this->Mastertablecontent->addEditapplChargeMaster($postData,$record_id)) {
					
					///Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Application Charges Master '."($forActionLog)", 'Success');
					$this->message = 'You have '.$action_var.' Application Charge Successfully';
					$this->message_theme = 'success';
				}
				//}else{
				//	$this->message = 'Unable to edit/delete this record, as it is currently exist with some applications';
				//}

			// For Buisness Years
			} elseif ($masterId=='9') {

				if ($record_id == null) {//for Add

					if ($this->Mastertablecontent->addEditbusinessYearsMaster($postData,$record_id)) {

						///Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('Buisness Years Master '."($forActionLog)", 'Success');
						$this->message = 'You have '.$action_var.' Business Year Successfully';
						$this->message_theme = 'success';
					}

				} else {// For Edit
					$years_for = $this->Session->read('edit_optional_param');

					if ($postData['business_years_for']==$years_for) {
						//if not change the dropdown value in edit
						if ($this->Randomfunctions->checkIfMasterValueUsed($record_id,$checkFieldName)==1) {

							if ($this->Mastertablecontent->addEditbusinessYearsMaster($postData,$record_id)) {

								///Added this call to save the user action log on 21-02-2022 by Akash
								$this->Customfunctions->saveActionPoint('Buisness Years Master '."($forActionLog)", 'Success');
								$this->message = 'You have '.$action_var.' Business Year Successfully';
								$this->message_theme = 'success';
							}

						} else {

							///Added this call to save the user action log on 21-02-2022 by Akash
							$this->Customfunctions->saveActionPoint('Buisness Years Master '."($forActionLog)", 'Failed');
							$this->message = 'Unable to edit/delete this record, as it is currently exist with some applications';
							$this->message_theme = 'failed';
						}

					} else {

						$this->message = 'Please Check and submit again.';
						$this->message_theme = 'warning';
					}
				}

			// For Office Details
			} elseif ($masterId=='10') {
				
			
				if (!empty($postData['edit_ro_office'])) {

					//if($this->Randomfunctions->checkIfMasterValueUsed($record_id,$checkFieldName)==1){
					if ($this->Mastertablecontent->addEditOfficeMaster($postData,$record_id)) {

							///Added this call to save the user action log on 21-02-2022 by Akash
							$this->Customfunctions->saveActionPoint('Office Details Master '."($forActionLog)", 'Success');
							$this->message = 'You have <b>'.$action_var.'</b> Office Successfully. Please <i>Set/Update</i> Jurisdictions for '.$action_var.' Office from <b>District Master.</b>';
							$this->message_theme = 'success';
					}
		
				} elseif (!empty($postData['ro_reallocate'])) { //to reallocate Office In-Charge

					//In-charge Reallocate function call
					if ($this->reallocateOfficeIncharge($postData,$record_id)) {

						///Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('User Reallocate', 'Success');
						$this->message = 'You have Reallocated Office In-charge Successfully';
						$this->message_theme = 'success';
					}
				
				} else {
					
					if ($this->Mastertablecontent->addEditOfficeMaster($postData,$record_id)) {

						///Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('Office Details Master '."($forActionLog)", 'Success');
						$this->message = 'You have <b>'.$action_var.'</b> Office Successfully. Please <i>Set/Update</i> Jurisdictions for '.$action_var.' Office from <b>District Master.</b>';
						$this->message_theme = 'success';
					}
				}

			// For SMS/Email Templates
			} elseif ($masterId=='11') {

				if ($this->Mastertablecontent->addEditMessageTemplateMaster($postData,$record_id)) {

					///Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('SMS-Email Master '."($forActionLog)", 'Success');
					$this->message = 'You have '.$action_var.' SMS/Email Template Successfully';
					$this->message_theme = 'success';
				}

			// For Pao / DDO
			} elseif ($masterId=='12') {
				
				if (!empty($postData['pao_email_id']) && !empty($postData['pao_alias_name']) && (!empty($postData['district_list']) || $record_id != null)) {

					if ($this->Mastertablecontent->addEditPaoMaster($postData,$record_id)) {

						///Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('PAO/DDO Master '."($forActionLog)", 'Success');
						$this->message = 'You have '.$action_var.' PAO/DDO successfully';
						$this->message_theme = 'success';
					}
					
				} else {

					$this->message = 'Plaase Check some fields are not provided';
					$this->message_theme = 'warning';
				}

			// For Feedback
			} elseif ($masterId=='15') {

				if ($this->Mastertablecontent->addEditFeedbackTypeMaster($postData,$record_id)){

					///Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Feedback Master '."($forActionLog)", 'Success');
					$this->message = 'You have '.$action_var.' Feedback Type Successfully';
					$this->message_theme = 'success';
				}

			// For Replica Charges
			} elseif ($masterId=='16') {

				if ($this->Mastertablecontent->addEditReplicaChargesMaster($postData,$record_id)) {

					///Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Replica Master '."($forActionLog)", 'Success');
					$this->message = 'You have '.$action_var.' Replica Charge Successfully.';
					$this->message_theme = 'success';
				}


			// For Education Type
			} elseif ($masterId=='17') {

				if ($this->Mastertablecontent->addEditEducationTypeMaster($postData,$record_id)) {

					///Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Education Master '."($forActionLog)", 'Success');
					$this->message = 'You have '.$action_var.' Education Type Successfully.';
					$this->message_theme = 'success';
				}

			// For Division Type
			} elseif ($masterId=='18') {

				if ($this->Mastertablecontent->addEditDivisionGradeMaster($postData,$record_id)) {

					///Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Division Master '."($forActionLog)", 'Success');
					$this->message = 'You have '.$action_var.' Division Grade Successfully.';
					$this->message_theme = 'success';
				}

			// For Documents Type  # Added on the 09-08-2022 by Akash
			} elseif ($masterId=='19') {

				if ($this->Mastertablecontent->addEditDocumentsMaster($postData,$record_id)) {

					///Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Documents Master '."($forActionLog)", 'Success');
					$this->message = 'You have '.$action_var.' Documents Type Successfully.';
					$this->message_theme = 'success';
				}
			}

			if ($this->message == '') {

				///Added this call to save the user action log on 21-02-2022 by Akash
				$this->Customfunctions->saveActionPoint('Master '."($forActionLog)", 'Failed');
				if ($masterId=='16') {
					$this->message = 'Replica Charges for selected commodity is already exist.';
				}else{
					$this->message = 'Record not '.$action_var.', Please Try Again.';
				}
				
				$this->message_theme = 'failed';
			}

		}



		//function to reallocate Office In-charge
		public function reallocateOfficeIncharge($postData,$record_id) {

			$this->loadModel('DmiUsers');
			$this->loadModel('DmiRoOffices');
			$this->loadModel('DmiRoAllocationLogs');
			$this->loadModel('DmiFlowWiseTablesLists');

			//get incharge listing
			$ro_incharge_name_list = $this->getInchargeNameList($postData['office_type']);

			$incharge_name = $postData['ro_name_list'];

			if (!empty($ro_incharge_name_list[$incharge_name])) {

				$incharge_name = $ro_incharge_name_list[$incharge_name];
				$incharge_name = explode('(',$incharge_name); //spliting name string(name+email)
			
				$incharge_email = rtrim($incharge_name[1],')');//get email from name string

				$incharge_email = base64_encode($incharge_email); //for email encoding

			} else {
				$this->redirect('/');
				$this->Session->destroy();
			}

			//to get reallocating user id.
			$reallocate_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$incharge_email)))->first();
		
			$DmiRoOfficesEntity = $this->DmiRoOffices->newEntity(array(

				'id'=>$record_id,
				'ro_email_id'=>$incharge_email,
				'modified'=>date('Y-m-d H:i:s')

			));
			
			if ($this->DmiRoOffices->save($DmiRoOfficesEntity)) {

				//Using common function for incharge rellocaiion with flow wise argument
				//allocation table model name, incharge name & current position table  model name

				//get flow wise tables
				$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();

				foreach ($flow_wise_tables as $each_flow) {

					//call common functionality to update tables
					$this->flowWiseInchargeReallocation($each_flow['allocation'],$postData['short_code'],$incharge_email,$each_flow['appl_current_pos'],$postData['office_type'],$each_flow['application_form']);

				}

				//Save Entry in Ro allocation table to maintain the record of RO incharge allocation history
				$DmiRoAllocationLogsEntity = $this->DmiRoAllocationLogs->newEntity(array(
					'ro_incharge_id'=>$incharge_email,
					'ro_office'=>$postData['ro_office'],
					'created'=>date('Y-m-d H:i:s'),
					'user_id'=>$reallocate_user_details['id'],
					'once_card_no'=>$reallocate_user_details['once_card_no']
				));

				if ($this->DmiRoAllocationLogs->save($DmiRoAllocationLogsEntity)) {
					
					///Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Re-allocate Office Incharge', 'Success');
					return true;
				}
			}
		
		}


		//to get conditional incharge list office type wise
		public function getInchargeNameList($office_type) {

			$this->LoadModel('DmiUserRoles');
			$this->LoadModel('DmiUsers');

			if ($office_type=='RO') {
				$incharge_list = $this->DmiUserRoles->find('list',array('keyField'=>'user_email_id','valueField'=>'user_email_id','conditions'=>array('ro_inspection'=>'yes')))->toArray();
			} elseif ($office_type=='SO') {
				$incharge_list = $this->DmiUserRoles->find('list',array('keyField'=>'user_email_id','valueField'=>'user_email_id','conditions'=>array('so_inspection'=>'yes')))->toArray();
			} else {
				$incharge_list = array();//no reallocation for RAL, this user belongs to LIMS
			}

			$i = 0;
			$ro_incharge_name_list = null;
			foreach ($incharge_list as $incharge_details) {
				
				$user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$incharge_details)))->first();
				$ro_incharge_name = $user_details['f_name'].' '.$user_details['l_name'];
				$ro_incharge_name_list[$i] = $ro_incharge_name.' ('.base64_decode($incharge_details).')';//for email encoding
				$i = $i+1;
			}

			return $ro_incharge_name_list;
		}	



		//to updates table flow wise on incharge reallocations
		public function flowWiseInchargeReallocation($allocation_model,$short_code,$incharge_email,$current_position_model,$office_type,$final_submit_model) {

			$this->loadModel($allocation_model);
			$this->loadModel($current_position_model);

			$allocation_list = $this->$allocation_model->find('all',array('conditions'=>array('customer_id LIKE'=>'%/'.$short_code.'/%'),'order'=>'id ASC'))->toArray();

			if (!empty($allocation_list)) {

				foreach ($allocation_list as $each_appl) {

					//getting office short code from appl.
					$appl_short_code = explode('/',$each_appl['customer_id']);
					$appl_short_code = $appl_short_code[2];

					if ($appl_short_code == $short_code) {

						$customer_id = $each_appl['customer_id'];

						//check if application is granted, then don't reallocate
						//added on 05-11-2021
						$this->loadModel($final_submit_model);
						$finalStatus = $this->$final_submit_model->find('all',array('conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
						
						if(!($finalStatus['status']=='approved' && $finalStatus['current_level']=='level_3')){

							if ($each_appl['level_3'] == $each_appl['current_level']) {//if application currently is with incharge

								$data_array = array(
									'id'=>$each_appl['id'],
									'level_3'=>$incharge_email,
									'current_level'=>$incharge_email,
									'modified'=>date('Y-m-d H:i:s')
								);

							} else {

								$data_array = array(
									'id'=>$each_appl['id'],
									'level_3'=>$incharge_email,
									'modified'=>date('Y-m-d H:i:s')
								);
							}

							$allocation_modelEntity = $this->$allocation_model->newEntity($data_array);

							if ($this->$allocation_model->save($allocation_modelEntity)) {

								//update RO ids in current position table, with RO Officer change
								$this->$current_position_model->updateAll(array('current_user_email_id'=>"$incharge_email"),
								array('customer_id'=>$customer_id,'current_level'=>'level_3','current_user_email_id'=>$each_appl['level_3']));

								//update RO on level_4_ro column for SO flow, only when RO office Updated
								if ($office_type=='RO') {

									$this->$allocation_model->updateAll(array('level_4_ro'=>"$incharge_email"),
									array('customer_id'=>$customer_id,'level_4_ro'=>$each_appl['level_3']));

									//to update position on level 4 Ro
									$this->$current_position_model->updateAll(array('current_user_email_id'=>"$incharge_email"),
									array('customer_id'=>$customer_id,'current_level'=>'level_4_ro','current_user_email_id'=>$each_appl['level_4_ro']));
								}
							}
						}
					}
				}
			}
		}


		//get records id and redirct to change status
		public function changeTemplateStatusRedirect($id) {

			$this->Session->write('record_id',$id);
			$this->redirect(array('controller'=>'masters','action'=>'change_status_sms_template'));

		}


		//to change the status of SMS/Email templates
		public function changeStatusSmsTemplate() {

			$this->loadModel('DmiSmsEmailTemplates');
			$sms_template_id = $this->Session->read('record_id');
			$sms_template_values = $this->DmiSmsEmailTemplates->find('all',array('conditions'=>array('id IS'=>$sms_template_id)))->first();

			if ($sms_template_values['status'] == 'active') {
				$status = 'disactive';
				///Added this call to save the user action log on 21-02-2022 by Akash
				$this->Customfunctions->saveActionPoint('SMS/Email Master (Template Deactivate)','Success');
				$this->message = 'You have <b>Deactivated</b> this SMS/Email Template';
				$this->message_theme = 'success';
			} else {
				///Added this call to save the user action log on 21-02-2022 by Akash
				$this->Customfunctions->saveActionPoint('SMS/Email Master (Template Activate)','Success');
				$status = 'active';
				$this->message = 'You have <b>Activated</b> this SMS/Email Template';
				$this->message_theme = 'success';
			}

			$DmiSmsEmailTemplateEntity = $this->DmiSmsEmailTemplates->newEntity(array(
				'id'=>$sms_template_id,
				'status'=>$status,
				'modified'=>date('Y-m-d H:i:s')
			));

			if ($this->DmiSmsEmailTemplates->save($DmiSmsEmailTemplateEntity)) {

				$this->redirect_to = 'list-master-records';
				$this->set('message',$this->message);
				$this->set('message_theme',$this->message_theme);
				$this->set('redirect_to',$this->redirect_to);
				$this->render('/element/message_boxes');

			}

		}



		// Menthod to create district "li" options list for PAO/DDO master table
		public function paoDistrictDropdown() {

			$this->autoRender = false;
			$this->loadModel('DmiDistricts');

			$state_id = $_POST['state_id'];
			$split_state_id = explode(',',$state_id);

			// Get only those  district name list which are not allocated to another user.
			$pao_id = $this->Session->read('record_id');

			if (empty($pao_id)) {

				$current_pao_id = array();
			} else {
				$current_pao_id = array('pao_id'=>$pao_id);
			}

			// find districts list if "pao_id= null or 1"
			$districts = $this->DmiDistricts->find('all', array('fields'=>array('id','district_name','pao_id'), 'conditions'=>array('state_id IN'=>$split_state_id, 'OR'=>array(array('pao_id'=>1),  $current_pao_id, array('pao_id IS NULL')),'delete_status IS NULL'),'order'=>'district_name'))->toArray();
			
			?> <ul class="ligap"> <?php

			$i=1;

				foreach ($districts as $district) { ?>

					<li class="">
						<label for="ms-opt-<?php //echo $i; ?>" class="">																															<!-- Show already select district in district dropdown in edit pao, Change on 14-12-2018, By Pravin Bhakare -->
							<input value="<?php echo $district['id']; ?>" title="<?php echo $district['district_name']; ?>" id="ms-opt-<?php echo $i; ?>" type="checkbox" <?php  if($district['pao_id'] == $pao_id && !empty($pao_id)){ echo 'checked'; }  ?>>  <?php echo $district['district_name']; ?>
						</label>
					</li>

				<?php	$i=$i+1; }

			?> </ul> <?php

			exit;
		
		}


		// Menthod to create district "<options>" list for PAO/DDO master table
		public function	paoDistrictOption() {

			$this->autoRender = false;
			$this->loadModel('DmiDistricts');

			$state_id = $_POST['state_id'];
			$split_state_id = explode(',',$state_id);

			// Get only those  district name list which are not allocated to another user.
			$pao_id = $this->Session->read('record_id');
			
			if ($pao_id != '') {
				$current_pao_id = array('pao_id'=>$pao_id);
			} else {
				$current_pao_id = array();
			}


			// find districts list if "pao_id= null or 1" (Updated Date : 02/05/2018 Pravin)
			$districts = $this->DmiDistricts->find('all', array('fields'=>array('id','district_name'), 'conditions'=>array('state_id IN'=>$split_state_id,'OR'=>array(array('pao_id'=>1), $current_pao_id, array('pao_id IS NULL')),'delete_status IS NULL'),'order'=>'district_name'))->toList();

			foreach ($districts as $district) { ?>

				<option value="<?php echo $district['id']; ?>">  <?php echo $district['district_name']; ?></option>

			<?php	}

			exit;
		}


		//to provide applications for re-esign
		public function addApplForReEsign() {

			$this->LoadModel('DmiApplAddedForReEsigns');
			$this->LoadModel('DmiGrantCertificatesPdfs');

			$added_appl = $this->DmiApplAddedForReEsigns->find('all')->toArray();
			$this->set('added_appl',$added_appl);

		//	if (null !== ($this->request->getData('add_appl'))) {
			if ($this->request->is('post')) {//changed on 08-04-2022 to 'post'

				//check if already added for re-esigned
				//query updated on 15-09-2021 by Amol, for new order 01-04-2021
				$check = $this->DmiApplAddedForReEsigns->find('all',array('conditions'=>array('customer_id IS'=>$this->request->getData('customer_id'),'re_esign_status'=>'Pending')))->first();

				if (empty($check)) {
					
					//check if theapplicatio renewal granted or not
					//updated logic as per new order on 01-04-2021, 5 years validity for PP and Laboratory
					//as the module is to reesign renewal certificate only, So now need to re-esign the first grant also, if granted with 2 years of validity
					//but not the old first grant record
					//on 15-09-2021 by Amol
					//$grant_details = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$this->request->getData('customer_id'),'pdf_version'=>'2')))->first();

					$grant_details = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$this->request->getData('customer_id'),'user_email_id !='=>'old_application'),'order'=>'id desc'))->first();

					if (!empty($grant_details)) {

						$DmiApplAddedEntity = $this->DmiApplAddedForReEsigns->newEntity(array(
							'customer_id'=>$this->request->getData('customer_id'),
							're_esign_status'=>'Pending',
							'action_status'=>'active',
							'created'=>date('Y-m-d H:i:s'),
							'modified'=>date('Y-m-d H:i:s')
						));

						if ($this->DmiApplAddedForReEsigns->save($DmiApplAddedEntity)) {

							///Added this call to save the user action log on 21-02-2022 by Akash
							$this->Customfunctions->saveActionPoint('Re-Esign Request', 'Success');
							$this->message = 'New Application Added for Re_esign the Renewal Certificate.';
							$this->message_theme = 'success';
						}

					} else {

						///Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('Re-Esign Request', 'Failed');
						$this->message = 'Sorry... The renewal is not granted yet, for the entered application id.';
						$this->message_theme = 'failed';
					}

				} else {

					///Added this call to save the user action log on 21-02-2022 by Akash
					$this->Customfunctions->saveActionPoint('Re-Esign Request', 'Failed');
					$this->message = 'Sorry..This Application id is already added for Re-esign.';
					$this->message_theme = 'failed';
				}

				$this->redirect_to = 'add_appl_for_re_esign';
			}

			$this->set('message',$this->message);
			$this->set('message_theme',$this->message_theme);
			$this->set('redirect_to',$this->redirect_to);
		}


		//Deactivate Application Re-Esign ID
		public function deactvtApplReEsignId($id) {

			$this->LoadModel('DmiApplAddedForReEsigns');

			$DmiApplAddedEntity = $this->DmiApplAddedForReEsigns->newEntity(array(
				'id'=>$id,
				'action_status'=>'deactivated',
				'modified'=>date('Y-m-d H:i:s')
			));

			if ($this->DmiApplAddedForReEsigns->save($DmiApplAddedEntity)) {
				///Added this call to save the user action log on 21-02-2022 by Akash
				$this->Customfunctions->saveActionPoint('Re-Esign Request Deactivate', 'Success');
				$this->redirect('/masters/add_appl_for_re_esign');
			}
		}
		
		
		//function is called through ajax to check last grant office and current in-charge same or not
		//on 08-04-2022 by Amol
		public function checkInchargeToReesign(){
			
			$this->autoRender = false;
			$customer_id = $_POST['customer_id'];
			
			//get In-charge user id of the user who granted last time
			$this->loadModel('DmiGrantCertificatesPdfs');
			$lastGrantBy = $this->DmiGrantCertificatesPdfs->find('all',array('fields'=>'user_email_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			
			//get current In-charge
			$this->loadModel('DmiRoOffices');
			$splitId = explode('/',$customer_id);
			$curIncharge = $this->DmiRoOffices->find('all',array('fields'=>'ro_email_id','conditions'=>array('short_code IS'=>$splitId[2])))->first();

			if($curIncharge['ro_email_id'] != $lastGrantBy['user_email_id']){					
				echo '~'.base64_decode($curIncharge['ro_email_id']).'~';
			}else{				
				echo '~1~';
			}
			exit;
		}



		//below function is created to update/extend dates from masters
		//currently for renewal due upto date
		public function extendDates() {

			$this->LoadModel('DmiCertificateTypes');
			$this->LoadModel('DmiDatesExtensions');
			$this->LoadModel('DmiDatesExtensionsLogs');

			//get certificate_type
			$cert_type_list = $this->DmiCertificateTypes->find('list',array('keyField'=>'id','valueField'=>'certificate_type','order'=>'id ASC'))->toArray();
			$mod_date = date('Y-m-d H:i:s');
			$by_user = $this->Session->read('username');

			$message='';
			$message_theme = '';
			$redirect_to = '';

			if (null !== ($this->request->getData('update'))) {

				$cert_type = $this->request->getData('cert_type');
				$ren_ext_dt = $this->request->getData('ren_ext_dt');
				$remark = $this->request->getData('remark');

				if ($this->DmiDatesExtensions->updateAll(
					array('renewal_extension_dt'=>"$ren_ext_dt",'modified'=>"$mod_date"),
					array('cert_type'=>$cert_type)
				)) {

					$DmiDatesExtensionsEntity = $this->DmiDatesExtensionsLogs->newEntity(array(
						'appl_type'=>null,
						'cert_type'=>$cert_type,
						'renewal_extension_dt'=>$ren_ext_dt,
						'by_user'=>$this->Session->read('username'),
						'remark'=>$remark,
						'created'=>date('Y-m-d H:i:s')

					));

					if ($this->DmiDatesExtensionsLogs->save($DmiDatesExtensionsEntity)) {

						///Added this call to save the user action log on 21-02-2022 by Akash
						$this->Customfunctions->saveActionPoint('Extend-Dates', 'Success');
						$message = 'You have successfully updated the date. Thank you';
						$message_theme = 'success';
						$redirect_to = 'extend_dates';
					}
				}
			}

			$this->set(compact('message','redirect_to','cert_type_list'));
		
		}



		//to fetch ext. dates through ajax on cert type click
		public function fetchExtDate(){

			$this->autoRender = false;
			$cert_type = $this->request->getData('cert_type');

			$this->loadModel('DmiDatesExtensions');
			$fetch_details = $this->DmiDatesExtensions->find('all',array('fields'=>'renewal_extension_dt','conditions'=>array('cert_type IS'=>$cert_type)))->first();
			$ren_ext_dt = $fetch_details['renewal_extension_dt'];

			echo '~'.$ren_ext_dt.'~';
			exit;
		}



		//below function is created on 30-01-2019 by Amol
		//To check id given district id is in used with any firm
		//to enable/disable updation of that district
		public function checkIfDistrictInUse($district_id) {

			$this->loadModel('DmiFirms');
			$check_firms = $this->DmiFirms->find('all',array('conditions'=>array('district IS'=>$district_id,'delete_status IS NULL')))->first();

			if (!empty($check_firms)) {
				return false;
			} else {
				return true;
			}
		}



		//to save master action logs for all masters commonly
		//on 30-06-2021 by Amol
		public function saveMasterActionLogs($masterId,$postData=null,$record_id) {
		
			//to save log if any master added or edited
			
			//To give the master table for PAO/DDO table for Save action logs on 03-12-2021 by AKASH
			if ($masterId == '12') {
				$masterModel = $this->masterTable = 'DmiPaoDetails';
			} else {
				$masterModel = $this->masterTable;
			}
			$this->loadModel($masterModel);
			$master_name = $this->fieldNameForCheck;
			
			if ($record_id == null) {
				$action = 'Add';
				$prev_data = '';

			} else {
				$action = 'Edit';
				$get_record = $this->$masterModel->find('all',array('conditions'=>array('id'=>$record_id)))->first();
				$prev_data = (json_encode($get_record));//convert the array into json
			}

			if (empty($postData)) {
				$action = 'Delete';
			}

			$new_data = json_encode($postData);//convert the array into json
			
			//get user id
			$get_user_id = $this->DmiUsers->find('all',array('fields'=>'id','conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
			$user_id = $get_user_id['id'];

			//create data array
			$data_array = array(
				'master_no'=>$masterId,
				'master_name'=>$master_name,
				'table_name'=>$masterModel,
				'record_id'=>$record_id,
				'action'=>$action,
				'by_user'=>$user_id,
				'prev_data'=>$prev_data,
				'new_data'=>$new_data,
				'created'=>date('Y-m-d H:i:s')
			);
			
			//save entry in log table
			$this->loadModel('DmiMastersActionLogs');
			$DmiMasterLogsEntity = $this->DmiMastersActionLogs->newEntity($data_array);

			$this->DmiMastersActionLogs->save($DmiMasterLogsEntity);

		}
	
	
	}



?>
