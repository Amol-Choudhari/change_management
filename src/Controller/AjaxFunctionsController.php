<?php


namespace App\Controller;
use Cake\Network\Session\DatabaseSession;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\ORM\TableRegistry;
use Controller\Dashboard;

class AjaxFunctionsController extends AppController{
	
	var $name = 'AjaxFunctions';

	public function initialize(): void {
		parent::initialize();

		$this->loadComponent('Customfunctions');
		$this->loadComponent('Mastertablecontent');
		$this->loadComponent('Communication');
	}



	// SHOW COMMODITY DROPDOWN
	// DESCRIPTION : --
	// @AUTHOR : ---
	// DATE : ------
	
	public function showCommodityDropdown() {

		$this->autoRender = false;
		$this->loadModel('MCommodity');
		$category_id = $_POST['commodity'];
		// changes by shankhpal shende for display commodities in asc order on 02/09/2022
		$commodities = $this->MCommodity->find('all', array('fields'=>array('commodity_code','commodity_name'), 'conditions'=>array('category_code IS'=>$category_id,'display'=>'Y'),'order'=>array('commodity_name asc')))->toArray();
		?>
				<option value=""><?php echo "Select Commodity";?></option>
		<?php foreach ($commodities as $commodity) { ?>
				<option value="<?php echo $commodity['commodity_code'];?>"><?php echo $commodity['commodity_name'];?></option>
		<?php }
		exit;
	}



	// SHOW DISTRICT DROPDOWN
	// DESCRIPTION : --
	// @AUTHOR : ---
	// DATE : ------
	
	public function showDistrictDropdown() {

		$this->autoRender = false;
		$this->loadModel('DmiDistricts');
		$state_id = $_POST['state'];
		// Apply "Order by" clause to get state list by order wise (Done By Pravin 10-01-2018)
		$districts = $this->DmiDistricts->find('all', array('fields'=>array('id','district_name'), 'conditions'=>array('state_id IS'=>$state_id, 'delete_status IS NULL'),'order'=>array('district_name')))->toArray();

		foreach ($districts as $district) { ?>
				<option value="<?php echo $district['id']?>"><?php echo $district['district_name']?></option>
		<?php	}
		exit;
	}



	// SHOW CHARGE
	// DESCRIPTION : --
	// @AUTHOR : ---
	// DATE : ------
	
	public function showCharge() {

		$this->autoRender = false;
		$this->loadModel('DmiApplicationCharges');
		$get_charges = $this->DmiApplicationCharges->find('all',array('conditions'=>array('certificate_type_id IS'=>$this->request->getData('certification_type'))))->first();
		$total_charges = $get_charges['charge'];

		?><input type="text" id="total_charge" name="total_charge" class="form-control" value="<?php echo $total_charges; ?>" readonly /><?php
		exit;
	}



	// CALCULATE CATEGORY WISE CHARGE
	// DESCRIPTION : --
	// @AUTHOR : ---
	// DATE : ------
	
	public function calculateCategoryWiseCharge() {

			$this->autoRender = false;
			$this->loadModel('MCommodity');
			$this->loadModel('DmiApplicationCharges');
			$selected_commodity_ids = explode(',',(string) $this->request->getData('selected_sub_commodities')); #For Deprecations

			$get_category_ids = $this->MCommodity->find('list',array('valueField'=>'category_code','conditions'=>array('commodity_code IN'=>$selected_commodity_ids)))->toList();

			$get_charges = $this->DmiApplicationCharges->find('all',array('conditions'=>array('certificate_type_id IS'=>$this->request->getData('certification_type'))))->first();
			$default_charges = $get_charges['charge'];

			$total_charges = $default_charges * count(array_unique($get_category_ids));//added array_unique()

			?><input type="text" id="total_charge" name="total_charge" class="form-control" value="<?php echo $total_charges; ?>" readonly /><?php
		exit;
	}



	// EDIT MACHINE ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
		
	public function editMachineId() {

		$this->autoRender = false;
		$this->loadModel('DmiAllMachinesDetails');

		$customer_id = $this->Customfunctions->sessionCustomerID();
		$firm_type = $this->Customfunctions->firmType($customer_id);

		if ($this->Session->read('edit_machine_id')==null) {

			$edit_machine_id = $_POST['edit_machine_id'];
			$this->Session->write('edit_machine_id',$edit_machine_id);

		} elseif ($_POST['edit_machine_id'] != $this->Session->read('edit_machine_id')) {

			if ($_POST['edit_machine_id'] == '') {

				$save_machine_id = $_POST['save_machine_id'];

			} else {

				$edit_machine_id = $_POST['edit_machine_id'];
				$this->Session->write('edit_machine_id',$edit_machine_id);
			}
		}

		if ($this->Session->read('edit_machine_id') != null) {

			if (!empty($edit_machine_id)) {

				$find_machines_details = $this->DmiAllMachinesDetails->find('all',array('conditions'=>array('id IS'=>$edit_machine_id)))->first();
				$this->set('find_machines_details',$find_machines_details);

				$machine_type_value_edit = $find_machines_details['machine_type'];
				$this->Set('machine_type_value_edit',$machine_type_value_edit);
			}
		}

		if (!empty($save_machine_id)) {

			$record_id = $this->Session->read('edit_machine_id');
			$machine_name = htmlentities($_POST['machine_name'], ENT_QUOTES);
			$machine_type = htmlentities($_POST['machine_type'], ENT_QUOTES);
			$machine_no = htmlentities($_POST['machine_no'], ENT_QUOTES);
			$machine_capacity = htmlentities($_POST['machine_capacity'], ENT_QUOTES);
			$save_details_result = $this->DmiAllMachinesDetails->editMachineDetails($record_id,$machine_name,$machine_type,$machine_no,$machine_capacity);// call custome method from model
			$this->Session->delete('edit_machine_id');
		}

		$added_machines_details[1] = $this->DmiAllMachinesDetails->machineDetails($firm_type);
		$this->Set('section_form_details',$added_machines_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/machine_details_table_view');
		
	}



	// ADD MACHINE DETAILS
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function addMachineDetails() {
		
		$this->autoRender = false;
		$this->loadModel('DmiAllMachinesDetails');

		$customer_id = $this->Customfunctions->sessionCustomerID();
		$firm_type = $this->Customfunctions->firmType($customer_id);

		$machine_name = htmlentities($_POST['machine_name'], ENT_QUOTES);
		$machine_type = htmlentities($_POST['machine_type'], ENT_QUOTES);
		$machine_no = htmlentities($_POST['machine_no'], ENT_QUOTES);
		$machine_capacity = htmlentities($_POST['machine_capacity'], ENT_QUOTES);

		$save_details_result = $this->DmiAllMachinesDetails->saveMachineDetails($machine_name,$machine_type,$machine_no,$machine_capacity);// call custome method from model
		$added_machines_details[1] = $this->DmiAllMachinesDetails->machineDetails($firm_type);
		$this->Set('section_form_details',$added_machines_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/machine_details_table_view');
	}




	// DELETE MACHINE ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function deleteMachineId() {
		
		$this->Session->delete('edit_machine_id');
		$this->loadModel('DmiAllMachinesDetails');

		$customer_id = $this->Customfunctions->sessionCustomerID();
		$firm_type = $this->Customfunctions->firmType($customer_id);

		//$record_id = $id;
		$record_id = $_POST['delete_machine_id'];
		$machine_delete_result = $this->DmiAllMachinesDetails->deleteMachineDetails($record_id);// call to custome function from model

		$added_machines_details[1] = $this->DmiAllMachinesDetails->machineDetails($firm_type);
		$this->Set('section_form_details',$added_machines_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/machine_details_table_view');
		

	}


	// ADD TANK DETAILS
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function addTankDetails() {

		$this->autoRender = false;
		$customer_id = $this->Customfunctions->sessionCustomerID();
		$this->loadModel('DmiAllTanksDetails');

		$tank_no = htmlentities($_POST['tank_no'], ENT_QUOTES);
		$tank_shape = htmlentities($_POST['tank_shape'], ENT_QUOTES);
		$tank_size = htmlentities($_POST['tank_size'], ENT_QUOTES);
		$tank_capacity = htmlentities($_POST['tank_capacity'], ENT_QUOTES);

		$save_details_result = $this->DmiAllTanksDetails->saveCustomerTankDetails($customer_id,$tank_no,$tank_shape,$tank_size,$tank_capacity);// call custome method from model

		//to show added tank table
		$oil_type = null;	$user_email_id = null;
		$cname = htmlentities($_POST['cname'], ENT_QUOTES);
		$added_tanks_details[1] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,$cname);
		$this->set('section_form_details',$added_tanks_details);
		$this->set('cname',$cname);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/tank_details_table_view');

	}


	// EDIT TANK ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function editTankId() {

		$this->autoRender = false;
		$customer_id = $this->Customfunctions->sessionCustomerID();
		$this->loadModel('DmiAllTanksDetails');

		if ($this->Session->read('edit_tank_id')==null) {

				$edit_tank_id = $_POST['edit_tank_id'];
				$this->Session->write('edit_tank_id',$edit_tank_id);

		} elseif ($_POST['edit_tank_id'] != $this->Session->read('edit_tank_id')) {

			if ($_POST['edit_tank_id'] == '') {

				$save_tank_id = $_POST['save_tank_id'];

			} else {

				$edit_tank_id = $_POST['edit_tank_id'];
				$this->Session->write('edit_tank_id',$edit_tank_id);
			}
		}


		if ($this->Session->read('edit_tank_id') != null) {

			if (!empty($edit_tank_id)) {

				$edit_tank_id = $this->Session->read('edit_tank_id');

				$find_tanks_details = $this->DmiAllTanksDetails->find('all',array('conditions'=>array('id IS'=>$edit_tank_id)))->first();
				$this->set('find_tanks_details',$find_tanks_details);

				$tank_shape_value_edit = $find_tanks_details['tank_shape'];
				$this->Set('tank_shape_value_edit',$tank_shape_value_edit);
			}
		}

		if (!empty($save_tank_id)) {

			$record_id = $this->Session->read('edit_tank_id');
			$tank_no = htmlentities($_POST['tank_no'], ENT_QUOTES);
			$tank_shape = htmlentities($_POST['tank_shape'], ENT_QUOTES);
			$tank_size = htmlentities($_POST['tank_size'], ENT_QUOTES);
			$tank_capacity = htmlentities($_POST['tank_capacity'], ENT_QUOTES);

			$save_details_result = $this->DmiAllTanksDetails->editCustomerTankDetails($record_id,$tank_no,$tank_shape,$tank_size,$tank_capacity);// call custome method from model
			$this->Session->delete('edit_tank_id');
		}

		$oil_type = null; $user_email_id = null;
		$cname = htmlentities($_POST['cname'], ENT_QUOTES);
		$added_tanks_details[1] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,$cname);
		$this->set('section_form_details',$added_tanks_details);
		$this->set('cname',$cname);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/tank_details_table_view');
	}




	// DELETE TANK ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function deleteTankId() {

		$this->Session->delete('edit_tank_id');
		$this->loadModel('DmiAllTanksDetails');

		$record_id = $_POST['delete_tank_id'];
		$tank_delete_result = $this->DmiAllTanksDetails->deleteCustomerTankDetails($record_id);// call to custome function from model

		$oil_type = null;	$user_email_id = null;
		$cname = htmlentities($_POST['cname'], ENT_QUOTES);
		$added_tanks_details[1] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,$cname);
		$this->set('section_form_details',$added_tanks_details);
		$this->set('cname',$cname);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/tank_details_table_view');

	}


	// ADD CONST OILS DETAILS
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function addConstOilsDetails() {

		$this->autoRender = false;
		$customer_id = $this->Customfunctions->sessionCustomerID();

		$oil_name = htmlentities($_POST['oil_name'], ENT_QUOTES);
		$mill_name_address = htmlentities($_POST['mill_name_address'], ENT_QUOTES);
		$quantity_procured = htmlentities($_POST['quantity_procured'], ENT_QUOTES);
		$cname = htmlentities($_POST['cname'], ENT_QUOTES);

		$this->loadModel('DmiAllConstituentOilsDetails');
		$save_details_result = $this->DmiAllConstituentOilsDetails->saveConstOilsDetails($oil_name,$mill_name_address,$quantity_procured);// call custome method from model

		//to show added mills table
		$hide_edit_id = '';
		$added_const_oils_details[1] = $this->DmiAllConstituentOilsDetails->constituentOilsMillDetails($cname);
		$this->set('section_form_details',$added_const_oils_details);
		$this->set('cname',$cname);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/const_oils_details_table_view');

	}



	// EDIT CONST OILS ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function editConstOilsId() {

		$this->autoRender = false;
		$customer_id = $this->Customfunctions->sessionCustomerID();
		$this->loadModel('DmiAllConstituentOilsDetails');

		if ($this->Session->read('edit_const_oils_id')==null) {

			$edit_const_oils_id = $_POST['edit_const_oils_id'];
			$this->Session->write('edit_const_oils_id',$edit_const_oils_id);

		} elseif ($_POST['edit_const_oils_id'] != $this->Session->read('edit_const_oils_id')) {

			if ($_POST['edit_const_oils_id'] == '') {

				$save_const_oils_id = $_POST['save_const_oils_id'];

			} else {

				$edit_const_oils_id = $_POST['edit_const_oils_id'];
				$this->Session->write('edit_const_oils_id',$edit_const_oils_id);
			}
		}


		if ($this->Session->read('edit_const_oils_id') != null) {

			if (!empty($edit_const_oils_id)) {

				$edit_const_oils_id = $this->Session->read('edit_const_oils_id');
				$find_const_oils_details = $this->DmiAllConstituentOilsDetails->find('all',array('conditions'=>array('id IS'=>$edit_const_oils_id)))->first();
				$this->set('find_const_oils_details',$find_const_oils_details);
			}
		}

		if (!empty($save_const_oils_id)) {

			$record_id = $this->Session->read('edit_const_oils_id');
			$oil_name = htmlentities($_POST['oil_name'], ENT_QUOTES);
			$mill_name_address = htmlentities($_POST['mill_name_address'], ENT_QUOTES);
			$quantity_procured = htmlentities($_POST['quantity_procured'], ENT_QUOTES);

			$save_details_result = $this->DmiAllConstituentOilsDetails->editConstOilsDetails($record_id,$oil_name,$mill_name_address,$quantity_procured);// call custome method from model

			$this->Session->delete('edit_const_oils_id');
		}

		$cname = htmlentities($_POST['cname'], ENT_QUOTES);
		//to show added mills table
		$hide_edit_id = $this->Session->read('edit_const_oils_id');
		$added_const_oils_details[1] = $this->DmiAllConstituentOilsDetails->constituentOilsMillDetails($cname);

		$this->set('section_form_details',$added_const_oils_details);
		$this->set('cname',$cname);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/const_oils_details_table_view');

	}



	// DELETE CONST OILS ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function deleteConstOilsId() {

		$this->Session->delete('edit_const_oils_id');
		$this->loadModel('DmiAllConstituentOilsDetails');

		//$record_id = $id;
		$record_id = $_POST['delete_const_oils_id'];
		$const_oils_delete_result = $this->DmiAllConstituentOilsDetails->deleteConstOilsDetails($record_id);// call to custome function from model

		$customer_id = $this->Customfunctions->sessionCustomerID();
		$hide_edit_id = '';
		$cname = htmlentities($_POST['cname'], ENT_QUOTES);

		//to show added mills table
		$added_const_oils_details[1] = $this->DmiAllConstituentOilsDetails->constituentOilsMillDetails($cname);
		$this->set('section_form_details',$added_const_oils_details);
		$this->set('cname',$cname);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/const_oils_details_table_view');
	}




	// DELETE DIRECTORS DETAILS ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function deleteDirectorsDetailsId() {

		//$this->loadModel('DmiAllDirectorsDetails');
		$applicationType = $this->Session->read('application_type');
		if ($applicationType == 3) {
			$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiChangeDirectorsDetails');
		} else {
			$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiAllDirectorsDetails');
		}

		$this->Session->delete('edit_directors_details_id');

		$customer_id = $this->Customfunctions->sessionCustomerID();

		//$record_id = $id;
		$record_id = $_POST['delete_directors_details_id'];
		$directors_details_delete_result = $DmiAllDirectorsDetails->deleteDirectorsDetails($record_id);// call to custome function from model

		//to show added directors table
		$hide_edit_id = '';
		$added_directors_details = $DmiAllDirectorsDetails->allDirectorsDetail($customer_id);
		$this->set('added_directors_details',$added_directors_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/old_applications_elements/old_app_directors_details_table_view');
	}



	// ADD DIRECTORS DETAILS
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function addDirectorsDetails() {

		$applicationType = $this->Session->read('application_type');
		if ($applicationType == 3) {
			$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiChangeDirectorsDetails');
		} else {
			$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiAllDirectorsDetails');
		}
		//$this->loadModel('DmiAllDirectorsDetails');
		$this->autoRender = false;

		$d_name = htmlentities($_POST['d_name'], ENT_QUOTES);
		$d_address = htmlentities($_POST['d_address'], ENT_QUOTES);

		$customer_id = $this->Customfunctions->sessionCustomerID();
		$save_details_result = $DmiAllDirectorsDetails->saveDirectorsDetails($d_name,$d_address);// call custome method from model

		//to show added directors table
		$added_directors_details = $DmiAllDirectorsDetails->allDirectorsDetail($customer_id);

		$this->set('added_directors_details',$added_directors_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/old_applications_elements/old_app_directors_details_table_view');
		
	}



	// EDIT DIRECTORS DETAILS ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function editDirectorsDetailsId() {

		$applicationType = $this->Session->read('application_type');
		if ($applicationType == 3) {
			$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiChangeDirectorsDetails');
		} else {
			$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiAllDirectorsDetails');
		}

		//$this->loadModel('DmiAllDirectorsDetails');
		//$this->loadModel('DmiChangeDirectorsDetails');

		$this->autoRender = false;

		if ($this->Session->read('edit_directors_details_id')==null) {

			$edit_directors_details_id = $_POST['edit_directors_details_id'];
			$this->Session->write('edit_directors_details_id',$edit_directors_details_id);

		} elseif ($_POST['edit_directors_details_id'] != $this->Session->read('edit_directors_details_id')) {

			if ($_POST['edit_directors_details_id'] == '') {

				$save_directors_details_id = $_POST['save_directors_details_id'];

			} else {

				$edit_directors_details_id = $_POST['edit_directors_details_id'];
				$this->Session->write('edit_directors_details_id',$edit_directors_details_id);
			}
		}


		if ($this->Session->read('edit_directors_details_id') != null) {

			if (!empty($edit_directors_details_id)) {

				$edit_directors_details_id = $this->Session->read('edit_directors_details_id');
				$find_directors_details = $DmiAllDirectorsDetails->find('all',array('conditions'=>array('id IS'=>$edit_directors_details_id)))->first();
				$this->set('find_directors_details',$find_directors_details);
			}
		}


		if (!empty($save_directors_details_id)) {

			$record_id = $this->Session->read('edit_directors_details_id');
			$d_name = htmlentities($_POST['d_name'], ENT_QUOTES);
			$d_address = htmlentities($_POST['d_address'], ENT_QUOTES);
			$save_details_result = $DmiAllDirectorsDetails->editDirectorsDetails($record_id,$d_name,$d_address);
			// call custome method from model
			$this->Session->delete('edit_directors_details_id');
		}

		//to show added directors table
		$hide_edit_id = $this->Session->read('edit_directors_details_id');
		$customer_id = $this->Customfunctions->sessionCustomerID();
		$added_directors_details = $DmiAllDirectorsDetails->allDirectorsDetail($customer_id);
		$this->set('added_directors_details',$added_directors_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/old_applications_elements/old_app_directors_details_table_view');
	}


	// ADD STORAGE TANK DETAILS
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function addStorageTankDetails() {

		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');
		$oil_type = null;	$user_email_id = $this->Session->read('username');

		$tank_no = htmlentities($_POST['tank_no'], ENT_QUOTES);
		$tank_shape = htmlentities($_POST['tank_shape'], ENT_QUOTES);
		$tank_size = htmlentities($_POST['tank_size'], ENT_QUOTES);
		$tank_capacity = htmlentities($_POST['tank_capacity'], ENT_QUOTES);

		$this->loadModel('DmiAllTanksDetails');
		$save_details_result = $this->DmiAllTanksDetails->saveUserTankDetails($customer_id,$tank_no,$tank_shape,$tank_size,$tank_capacity,$oil_type);// call custome method from model

		//to show added tank table
		$added_tanks_details[1] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,null);

		$this->set('section_form_details',$added_tanks_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/storage_tank_details_table_view');

	}



	// EDIT STOARGE TANK ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function editStorageTankId() {

		$this->loadModel('DmiAllTanksDetails');
		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');
		$oil_type = null;	$user_email_id = $this->Session->read('username');

		if ($this->Session->read('edit_storage_tank_id')==null) {

			$edit_storage_tank_id = $_POST['edit_storage_tank_id'];
			$this->Session->write('edit_storage_tank_id',$edit_storage_tank_id);

		} elseif ($_POST['edit_storage_tank_id'] != $this->Session->read('edit_storage_tank_id')) {

			if ($_POST['edit_storage_tank_id'] == '') {

				$save_storage_tank_id = $_POST['save_storage_tank_id'];

			} else {

				$edit_storage_tank_id = $_POST['edit_storage_tank_id'];
				$this->Session->write('edit_storage_tank_id',$edit_storage_tank_id);
			}
		}



		if ($this->Session->read('edit_storage_tank_id') != null) {

			if (!empty($edit_storage_tank_id)) {

				$edit_storage_tank_id = $this->Session->read('edit_storage_tank_id');
				$find_tanks_details = $this->DmiAllTanksDetails->find('all',array('conditions'=>array('id IS'=>$edit_storage_tank_id)))->first();
				$this->set('find_tanks_details',$find_tanks_details);

				$tank_shape_value_edit = $find_tanks_details['tank_shape'];
				$this->Set('tank_shape_value_edit',$tank_shape_value_edit);
			}
		}


		if (!empty($save_storage_tank_id)) {

			$record_id = $this->Session->read('edit_storage_tank_id');
			$tank_no = htmlentities($_POST['tank_no'], ENT_QUOTES);
			$tank_shape = htmlentities($_POST['tank_shape'], ENT_QUOTES);
			$tank_size = htmlentities($_POST['tank_size'], ENT_QUOTES);
			$tank_capacity = htmlentities($_POST['tank_capacity'], ENT_QUOTES);
			$save_details_result = $this->DmiAllTanksDetails->editUserTankDetails($record_id,$tank_no,$tank_shape,$tank_size,$tank_capacity,$oil_type);// call custome method from model
			$this->Session->delete('edit_storage_tank_id');
		}

		$added_tanks_details[1] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,null);
		$this->set('section_form_details',$added_tanks_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/storage_tank_details_table_view');

	}



	// DELETE STORAGE TANK ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function deleteStorageTankId() {

		$this->loadModel('DmiAllTanksDetails');
		$this->Session->delete('edit_storage_tank_id');
		$oil_type = null;	$user_email_id = $this->Session->read('username');

		$record_id = $_POST['delete_storage_tank_id'];
		$tank_delete_result = $this->DmiAllTanksDetails->deleteUserTankDetails($record_id);// call to custome function from model

		$added_tanks_details[1] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,null);
		$this->set('section_form_details',$added_tanks_details);

		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/storage_tank_details_table_view');

	}


	
	// ADD CONST OILS TANK DETAILS
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function addConstOilsTankDetails() {

		$this->loadModel('DmiAllTanksDetails');
		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');
		$oil_type = 'constituent';	$user_email_id = $this->Session->read('username');

		$tank_no = htmlentities($_POST['tank_no'], ENT_QUOTES);
		$tank_shape = htmlentities($_POST['tank_shape'], ENT_QUOTES);
		$tank_size = htmlentities($_POST['tank_size'], ENT_QUOTES);
		$tank_capacity = htmlentities($_POST['tank_capacity'], ENT_QUOTES);

		$save_details_result = $this->DmiAllTanksDetails->saveUserTankDetails($customer_id,$tank_no,$tank_shape,$tank_size,$tank_capacity,$oil_type);// call custome method from model

		$added_tanks_details[2] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,null);
		$this->set('section_form_details',$added_tanks_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/const_oil_tank_details_table_view');

	}

	
	// EDIT CONST OILS DETAILS
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function editConstOilsTankId() {

		$this->loadModel('DmiAllTanksDetails');
		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');
		$oil_type = 'constituent';	$user_email_id = $this->Session->read('username');

		if ($this->Session->read('edit_const_oils_tank_id')==null) {

			$edit_const_oils_tank_id = $_POST['edit_const_oils_tank_id'];
			$this->Session->write('edit_const_oils_tank_id',$edit_const_oils_tank_id);

		} elseif ($_POST['edit_const_oils_tank_id'] != $this->Session->read('edit_const_oils_tank_id')) {

			if ($_POST['edit_const_oils_tank_id'] == '') {

				$save_const_oils_tank_id = $_POST['save_const_oils_tank_id'];

			} else {

				$edit_const_oils_tank_id = $_POST['edit_const_oils_tank_id'];
				$this->Session->write('edit_const_oils_tank_id',$edit_const_oils_tank_id);
			}
		}

		if ($this->Session->read('edit_const_oils_tank_id') != null) {

			if (!empty($edit_const_oils_tank_id)) {

				$edit_const_oils_tank_id = $this->Session->read('edit_const_oils_tank_id');
				$find_const_oils_tanks_details = $this->DmiAllTanksDetails->find('all',array('conditions'=>array('id IS'=>$edit_const_oils_tank_id)))->first();
				$this->set('find_const_oils_tanks_details',$find_const_oils_tanks_details);

				$const_oils_tank_shape_value_edit = $find_const_oils_tanks_details['tank_shape'];
				$this->Set('const_oils_tank_shape_value_edit',$const_oils_tank_shape_value_edit);
			}
		}

		if (!empty($save_const_oils_tank_id)) {

			$record_id = $this->Session->read('edit_const_oils_tank_id');
			$tank_no = htmlentities($_POST['tank_no'], ENT_QUOTES);
			$tank_shape = htmlentities($_POST['tank_shape'], ENT_QUOTES);
			$tank_size = htmlentities($_POST['tank_size'], ENT_QUOTES);
			$tank_capacity = htmlentities($_POST['tank_capacity'], ENT_QUOTES);

			$save_details_result = $this->DmiAllTanksDetails->editUserTankDetails($record_id,$tank_no,$tank_shape,$tank_size,$tank_capacity,$oil_type);// call custome method from model
			$this->Session->delete('edit_const_oils_tank_id');

		}

		$added_tanks_details[2] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,null);
		$this->set('section_form_details',$added_tanks_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/const_oil_tank_details_table_view');
	}


	// DELETE CONST OILS TANK ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function deleteConstOilsTankId() {

		$this->loadModel('DmiAllTanksDetails');
		$this->Session->delete('edit_const_oils_tank_id');
		$oil_type = 'constituent';	$user_email_id = $this->Session->read('username');

		$record_id = $_POST['delete_const_oils_tank_id'];
		$const_oils_tank_delete_result = $this->DmiAllTanksDetails->deleteUserTankDetails($record_id);// call to custome function from model

		$added_tanks_details[2] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,null);
		$this->set('section_form_details',$added_tanks_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/const_oil_tank_details_table_view');
	}



	// ADD BEVO OILS TANK DETAILS
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function addBevoOilsTankDetails() {

		$this->loadModel('DmiAllTanksDetails');
		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');
		$oil_type = 'bevo';	$user_email_id = $this->Session->read('username');

		$tank_no = htmlentities($_POST['tank_no'], ENT_QUOTES);
		$tank_shape = htmlentities($_POST['tank_shape'], ENT_QUOTES);
		$tank_size = htmlentities($_POST['tank_size'], ENT_QUOTES);
		$tank_capacity = htmlentities($_POST['tank_capacity'], ENT_QUOTES);

		$save_details_result = $this->DmiAllTanksDetails->saveUserTankDetails($customer_id,$tank_no,$tank_shape,$tank_size,$tank_capacity,$oil_type);// call custome method from model

		$added_tanks_details[3] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,null);
		$this->set('section_form_details',$added_tanks_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/bevo_oil_tank_details_table_view');
	}



	// EDIT BEVO OILS TANK ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function editBevoOilsTankId() {

		$this->loadModel('DmiAllTanksDetails');
		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');
		$oil_type = 'bevo';	$user_email_id = $this->Session->read('username');

		if ($this->Session->read('edit_bevo_oils_tank_id')==null) {

			$edit_bevo_oils_tank_id = $_POST['edit_bevo_oils_tank_id'];
			$this->Session->write('edit_bevo_oils_tank_id',$edit_bevo_oils_tank_id);

		} elseif ($_POST['edit_bevo_oils_tank_id'] != $this->Session->read('edit_bevo_oils_tank_id')) {

			if ($_POST['edit_bevo_oils_tank_id'] == '') {

				$save_bevo_oils_tank_id = $_POST['save_bevo_oils_tank_id'];

			} else {

				$edit_bevo_oils_tank_id = $_POST['edit_bevo_oils_tank_id'];
				$this->Session->write('edit_bevo_oils_tank_id',$edit_bevo_oils_tank_id);
			}
		}


		if ($this->Session->read('edit_bevo_oils_tank_id') != null) {

			if (!empty($edit_bevo_oils_tank_id)) {

				$edit_bevo_oils_tank_id = $this->Session->read('edit_bevo_oils_tank_id');
				$find_bevo_oils_tanks_details = $this->DmiAllTanksDetails->find('all',array('conditions'=>array('id IS'=>$edit_bevo_oils_tank_id)))->first();
				$this->set('find_bevo_oils_tanks_details',$find_bevo_oils_tanks_details);

				$bevo_oils_tank_shape_value_edit = $find_bevo_oils_tanks_details['tank_shape'];
				$this->Set('bevo_oils_tank_shape_value_edit',$bevo_oils_tank_shape_value_edit);
			}
		}


		if (!empty($save_bevo_oils_tank_id)) {

			$record_id = $this->Session->read('edit_bevo_oils_tank_id');
			$tank_no = htmlentities($_POST['tank_no'], ENT_QUOTES);
			$tank_shape = htmlentities($_POST['tank_shape'], ENT_QUOTES);
			$tank_size = htmlentities($_POST['tank_size'], ENT_QUOTES);
			$tank_capacity = htmlentities($_POST['tank_capacity'], ENT_QUOTES);

			$save_details_result = $this->DmiAllTanksDetails->editUserTankDetails($record_id,$tank_no,$tank_shape,$tank_size,$tank_capacity,$oil_type);// call custome method from model
			$this->Session->delete('edit_bevo_oils_tank_id');
		}

		$added_tanks_details[3] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,null);
		$this->set('section_form_details',$added_tanks_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/bevo_oil_tank_details_table_view');
	}



	// DELETE BEVO OILS TANK ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function deleteBevoOilsTankId() {

		$this->loadModel('DmiAllTanksDetails');
		$this->Session->delete('edit_bevo_oils_tank_id');
		$oil_type = 'bevo';	$user_email_id = $this->Session->read('username');

		//$record_id = $id;
		$record_id = $_POST['delete_bevo_oils_tank_id'];
		$bevo_oils_tank_delete_result = $this->DmiAllTanksDetails->deleteUserTankDetails($record_id);// call to custome function from model

		$added_tanks_details[3] = $this->DmiAllTanksDetails->tanksDetails($user_email_id,$oil_type,null);
		$this->set('section_form_details',$added_tanks_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/bevo_oil_tank_details_table_view');
	}




	// ADD CONST OIL MILL DETAILS
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function addConstOilMillDetails() {

		$this->loadModel('DmiAllConstituentOilsDetails');
		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');

		$oil_name = htmlentities($_POST['oil_name'], ENT_QUOTES);
		$mill_name_address = htmlentities($_POST['mill_name_address'], ENT_QUOTES);
		$quantity_procured = htmlentities($_POST['quantity_procured'], ENT_QUOTES);

		$save_details_result = $this->DmiAllConstituentOilsDetails->saveUserConstOilMillDetails($customer_id,$oil_name,$mill_name_address,$quantity_procured);// call custome method from model

		$added_const_oils_details[4] = $this->DmiAllConstituentOilsDetails->constituentOilsMillDetails('report');
		$this->set('section_form_details',$added_const_oils_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/const_oil_mill_details_table_view');
	}
	
	
	// EDIT CONST OIL MILL ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function editConstOilMillId() {

		$this->loadModel('DmiAllConstituentOilsDetails');
		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');

		if ($this->Session->read('edit_const_oil_mill_id')==null) {

			$edit_const_oil_mill_id = $_POST['edit_const_oil_mill_id'];
			$this->Session->write('edit_const_oil_mill_id',$edit_const_oil_mill_id);

		} elseif ($_POST['edit_const_oil_mill_id'] != $this->Session->read('edit_const_oil_mill_id')) {

			if ($_POST['edit_const_oil_mill_id'] == '') {
				$save_const_oil_mill_id = $_POST['save_const_oil_mill_id'];
			} else {
				$edit_const_oil_mill_id = $_POST['edit_const_oil_mill_id'];
				$this->Session->write('edit_const_oil_mill_id',$edit_const_oil_mill_id);
			}
		}

		if ($this->Session->read('edit_const_oil_mill_id') != null)
		{
			if (!empty($edit_const_oil_mill_id)) {

				$edit_const_oil_mill_id = $this->Session->read('edit_const_oil_mill_id');
				$find_const_oil_mill_details = $this->DmiAllConstituentOilsDetails->find('all',array('conditions'=>array('id IS'=>$edit_const_oil_mill_id)))->first();
				$this->set('find_const_oil_mill_details',$find_const_oil_mill_details);
			}
		}

		if (!empty($save_const_oil_mill_id)) {

			$record_id = $this->Session->read('edit_const_oil_mill_id');
			$oil_name = htmlentities($_POST['oil_name'], ENT_QUOTES);
			$mill_name_address = htmlentities($_POST['mill_name_address'], ENT_QUOTES);
			$quantity_procured = htmlentities($_POST['quantity_procured'], ENT_QUOTES);

			$save_details_result = $this->DmiAllConstituentOilsDetails->editUserConstOilMillDetails($record_id,$oil_name,$mill_name_address,$quantity_procured);// call custome method from model
			$this->Session->delete('edit_const_oil_mill_id');
		}

		$added_const_oils_details[4] = $this->DmiAllConstituentOilsDetails->constituentOilsMillDetails('report');
		$this->set('section_form_details',$added_const_oils_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/const_oil_mill_details_table_view');

	}
	
	
	
	// DELETE CONST OIL MILL ID
	// @AUTHOR : AMOL CHOUDHARI
	// #M - AKASH THAKRE 
	// DATE : 14/06/2017 (C), 23-03-2022 (M)
	
	public function deleteConstOilMillId() {

		$this->loadModel('DmiAllConstituentOilsDetails');
		$this->Session->delete('edit_const_oil_mill_id');

		//$record_id = $id;
		$record_id = $_POST['delete_const_oil_mill_id'];
		$const_oil_mill_delete_result = $this->DmiAllConstituentOilsDetails->deleteUserConstOilMillDetails($record_id);// call to custome function from model

		$added_const_oils_details[4] = $this->DmiAllConstituentOilsDetails->constituentOilsMillDetails('report');
		$this->set('section_form_details',$added_const_oils_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/ca_other_tables_elements/const_oil_mill_details_table_view');
	}

	
	
	// ADD PACKER DETAILS
	// DESCRIPTION : Method for to Add printing packer details.
	// @AUTHOR : PRAVIN BHAKARE
	// #M - AKASH THAKRE 
	// DATE : 10/06/2017 (C) , 23-03-2022 (M)
	
	public function addPackerDetails() {

		$this->autoRender = false;
		$customer_id = $this->Session->read('username');

		$this->loadModel('DmiRenewalPackerDetails');

		$packer_name = htmlentities($_POST['packer_name'], ENT_QUOTES);
		$packer_type = htmlentities($_POST['packer_type'], ENT_QUOTES);
		$quantity_printed = htmlentities($_POST['quantity_printed'], ENT_QUOTES);

		$save_details_result = $this->DmiRenewalPackerDetails->savePackerDetails($packer_name,$packer_type,$quantity_printed);// call custome method from model

		$added_packer_details[3] =	$this->DmiRenewalPackerDetails->packerDetatils($customer_id);
		$this->set('section_form_details',$added_packer_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/application_forms/renewal/printing/printing_renewal_packer_details');
	}




	// EDIT PACKER ID
	// DESCRIPTION : Method for to edit printing packer details.
	// @AUTHOR : PRAVIN BHAKARE
	// #M - AKASH THAKRE 
	// DATE : 10/06/2017 , 23-03-2022 (M)
	
	public function editPackerId() {

		$this->autoRender = false;
		$customer_id = $this->Session->read('username');

		$this->loadModel('DmiRenewalPackerDetails');

		if ($this->Session->read('edit_packer_id')==null) {

			$edit_packer_id = $_POST['edit_packer_id'];
			$this->Session->write('edit_packer_id',$edit_packer_id);

		} elseif (!($_POST['edit_packer_id']==$this->Session->read('edit_packer_id'))) {

			if ($_POST['edit_packer_id'] == '') {
				$save_packer_id = $_POST['save_packer_id'];
			} else {
				$edit_packer_id = $_POST['edit_packer_id'];
				$this->Session->write('edit_packer_id',$edit_packer_id);
			}
		}

		if ($this->Session->read('edit_packer_id') != null)
		{
			if (!empty($edit_packer_id)) {

				$find_packers_details = $this->DmiRenewalPackerDetails->find('all',array('conditions'=>array('id IS'=>$edit_packer_id)))->first();
				$this->set('find_packers_details',$find_packers_details);

				$packer_type_value_edit = $find_packers_details['packer_type'];
				$this->Set('packer_type_value_edit',$packer_type_value_edit);

				$packer_name_value_edit = $find_packers_details['packer_name'];
				$this->Set('packer_name_value_edit',$packer_name_value_edit);
			}
		}

		if (!empty($save_packer_id)) {

			$record_id = $this->Session->read('edit_packer_id');
			$packer_name = htmlentities($_POST['packer_name'], ENT_QUOTES);
			$packer_type = htmlentities($_POST['packer_type'], ENT_QUOTES);
			$quantity_printed = htmlentities($_POST['quantity_printed'], ENT_QUOTES);
			$save_details_result = $this->DmiRenewalPackerDetails->editPackerDetails($record_id, $packer_name,$packer_type,$quantity_printed);// call custome method from model
			$this->Session->delete('edit_packer_id');
		}

		$added_packer_details[3] = $this->DmiRenewalPackerDetails->packerDetatils($customer_id);
		$this->set('section_form_details',$added_packer_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/application_forms/renewal/printing/printing_renewal_packer_details');

	}
	
	
	
	// DELETE PACKER ID
	// DESCRIPTION : Method for to Delete printing packer details.
	// @AUTHOR : PRAVIN BHAKARE
	// #M - AKASH THAKRE 
	// DATE : 10/06/2017 , 23-03-2022 (M)

	public function deletePackerId() {

		$this->loadModel('DmiRenewalPackerDetails');
		$customer_id = $this->Session->read('username');

		$this->Session->delete('edit_packer_id');
		$record_id = $_POST['delete_packer_id'];
		$packer_delete_result = $this->DmiRenewalPackerDetails->deletePackerDetails($record_id);// call to custome function from model

		$added_packer_details[3] = $this->DmiRenewalPackerDetails->packerDetatils($customer_id);
		$this->set('section_form_details',$added_packer_details);
		
		//this below call is modified "Element" changed to "element" as it declining the path on server by AKASH THAKRE on 23-03-2022
		$this->render('/element/application_forms/renewal/printing/printing_renewal_packer_details');

	}



	// EDIT REFERRED BACK
	// DESCRIPTION : form RO to Applicant referred back
	// @AUTHOR : ------
	// DATE : -----
	
	public function editReferredBack() {
		$this->autoRender = false;
		$id = $_POST['referred_back_max_id'];
		$this->Session->write('edit_referred_back_id',$id);
	}
	
	
	
	// DELETE REFERRED BACK
	// DESCRIPTION : -----
	// @AUTHOR : ------
	// DATE : -----
	
	public function deleteReferredBack() {
		$this->autoRender = false;
		$id = $_POST['referred_back_max_id'];
		$model_name = $_POST['model_name'];
		$this->loadModel($model_name);
		$entity = $this->$model_name->get($id);
		$this->$model_name->delete($entity);
	}
	
	
	
	
	// EDIT MO COMMENT
	// DESCRIPTION : MO and Level3 user comments edit and delete methods.
	// @AUTHOR : ------
	// DATE : -----
	
	public function editMoComment() {
		$this->autoRender = false;
		$id = $_POST['mo_comment_max_id'];
		$this->Session->write('edit_mo_comment_id',$id);
	}
	
	
	
	// DELETE MO COMMENT
	public function deleteMoComment() {
		$this->autoRender = false;
		$id = $_POST['mo_comment_max_id'];
		$model_name = $_POST['model_name'];
		$this->loadModel($model_name);
		$entity = $this->$model_name->get($id);
		$this->$model_name->delete($entity);
	}
	
	
	
	// EDIT RO REPLY
	public function editRoReply() {
		$this->autoRender = false;
		$id = $_POST['ro_reply_max_id'];
		$this->Session->write('edit_ro_reply_id',$id);
	}
	
	
	
	// DELETE RO REPLY
	public function deleteRoReply() {

		$this->autoRender = false;
		$id = $_POST['ro_reply_max_id'];
		$model_name = $_POST['model_name'];
		$this->loadModel($model_name);

		//to check if ro commented first & created new row then delete entire row
		$check_row_comment_by = $this->$model_name->find('all',array('conditions'=>array('id IS'=>$id)))->first();
		if ($check_row_comment_by['mo_comment_date'] == null) {
			$entity = $this->$model_name->get($id);
			$this->$model_name->delete($entity);
		} else {
			
			$model_name_entity = $this->$model_name->newEntity(array(
				'id'=>$id,
				'ro_reply_comment'=>null,
				'rr_comment_ul'=>null,
				'ro_current_comment_to'=>'both'
			));
			//only update the row with ro_reply null
			$this->$model_name->save($model_name_entity);
		}
	}
	
	
	
	

	// EDIT REFERRED TO IO BACK
	// DESCRIPTION : form RO/SO to IO referred_back.
	// @AUTHOR : ------
	// DATE : -----
	
	public function editReferredToIoBack() {
		$this->autoRender = false;
		$id = $_POST['referred_back_max_id'];
		$this->Session->write('edit_referred_back_to_io_id',$id);
	}


	// DELETE REFERRED TO IO BACK
	public function deleteReferredToIoBack() {
		$this->autoRender = false;
		$id = $_POST['referred_back_max_id'];
		$model_name = $_POST['model_name'];

		$this->loadModel($model_name);
		$entity = $this->$model_name->get($id);
		$this->$model_name->delete($entity);
	}



	// EDIT IO REPLY
	public function editIoReply() {

		$this->autoRender = false;
		$id = $_POST['reply_max_id'];
		$this->Session->write('edit_reply_to_ro_id',$id);
	}


	// DELETE IO REPLY
	public function deleteIoReply() {

		$this->autoRender = false;
		$id = $_POST['reply_max_id'];
		$model_name = $_POST['model_name'];
		$this->loadModel($model_name);

		$entity = $this->$model_name->newEntity(array(
			'id'=>$id,
			'io_reply'=>null,
			'io_reply_date'=>null,
			'ir_comment_ul'=>null,
			'form_status'=>'referred_back'
		));

		$this->$model_name->save($entity);
	}



	// UPDATE OLD CERT DATES
	// DESCRIPTION : To update old certificate date by DMI user, with logs in new table.
	// @AUTHOR : AMOL CHOUDHARI
	// DATE : 28-05-2019 
	
	public function updateOldCertDates() {

		$this->autoRender = false;
		$customer_id = $this->Session->read('customer_id');
		$grant_date = trim($_POST['grant_date']);//trim added on 20-12-19
		$last_ren_date = trim($_POST['last_ren_date']);//trim added on 20-12-19
		$reason_to_update = $_POST['reason_to_update'];
		$valid_upto_date = $_POST['valid_upto_date'];

		$this->loadModel('DmiOldApplicationCertificateDetails');

		//get old application certificate details
		$get_old_certificate_details = $this->DmiOldApplicationCertificateDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$previous_grant_date = $get_old_certificate_details['date_of_grant'];


		$previous_last_ren_date = null;
		if (!empty($last_ren_date)) {

			//get old application renewal details
			$get_old_renewal_details = $this->DmiOldApplicationCertificateDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
			$previous_last_ren_date = $get_old_renewal_details['renewal_date'];

			$last_ren_date = $this->Customfunctions->dateFormatCheck($last_ren_date);
			$this->loadModel('DmiOldApplicationRenewalDates');

			//to update last renewal grant date
			$this->DmiOldApplicationRenewalDates->updateAll(array('renewal_date' => "$last_ren_date"),array('customer_id' => $customer_id), array('order'=>'id DESC'));
		}

		$grant_date = $this->Customfunctions->dateFormatCheck($grant_date);
		//to update grant date
		$this->DmiOldApplicationCertificateDetails->updateAll(array('date_of_grant' => "$grant_date"),array('customer_id' => $customer_id), array('order'=>'id DESC'));


		//maintain date updation logs
		$this->loadModel('DmiOldCertDateUpdateLogs');
		$DmiOldCertDateUpdateLogsEntity = $this->DmiOldCertDateUpdateLogs->newEntity(array(

			'customer_id'=>$customer_id,
			'updated_by'=>$this->Session->read('username'),
			'pre_grant_date'=>$previous_grant_date,
			'pre_last_renewal_date'=>$previous_last_ren_date,
			'new_grant_date'=>$grant_date,
			'new_last_renewal_date'=>$last_ren_date,
			'reason_to_update'=>$reason_to_update,
			'valid_upto_date'=>$valid_upto_date,
			//'created'=>date('Y-m-d H:i:s')

		));
		$this->DmiOldCertDateUpdateLogs->save($DmiOldCertDateUpdateLogsEntity);

		echo '~done~';
		exit;
	}



	// CHECK UNIQUE TRANS ID FOR APPL
	// DESCRIPTION : to check unique payment trsnsaction id , For applicant side on payment section while saving payment details.
	// @AUTHOR : AMOL CHOUDHARI
	// DATE : 14-10-2019 
	
	public function checkUniqueTransIdForAppl() {

		//initialize model in component
		$this->loadModel('DmiGrantCertificatesPdfs');
		$this->loadModel('DmiFlowWiseTablesLists');
		$this->loadModel('DmiFirms');
		$trans_id = $_POST['trans_id'];

		$new_customer_id = $this->Session->read('username');//currently applying applicant
		$allow_id = 'yes';

		//temp static array, will be replaced by query result in phase 2
		//$payment_tables_array = array('Dmi_applicant_payment_detail','Dmi_renewal_applicant_payment_detail');

		//get all payment tables flow wise
		$payment_tables_array = $this->DmiFlowWiseTablesLists->find('all',array('fields'=>'payment','conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray'),'payment IS NOT'=>null)))->toArray();

		foreach ($payment_tables_array as $each_table) {

			$each_table = $each_table['payment'];
			$this->loadModel($each_table);

			//check new app if trans id already exist
			$check_trans_id = $this->$each_table->find('all',array('conditions'=>array('transaction_id IS'=>$trans_id),'order'=>'id desc'))->first();

			//for new
			if (!empty($check_trans_id)) {

				$old_customer_id = $check_trans_id['customer_id'];//applicant which already used this trans id.

				//check if appl with that trans id is granted already
				$check_grant = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('OR'=>array('customer_id IS'=>$old_customer_id)),'order'=>'id desc'))->first();

				if (!empty($check_grant)) {

					$allow_id = 'no';
					break;

				} else {

					//if not grant then check primary id, district and firm type of the applicant
					// The old applicant and current applicant should match the above 3 details.
					//old details
					$get_old_details = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$old_customer_id)))->first();
					$old_primary_id = $get_old_details['customer_primary_id'];
					$old_firm_type = $get_old_details['certification_type'];
					$old_district_id = $get_old_details['district'];

					//new details
					$get_new_details = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$new_customer_id)))->first();
					$new_primary_id = $get_new_details['customer_primary_id'];
					$new_firm_type = $get_new_details['certification_type'];
					$new_district_id = $get_new_details['district'];

					if (($old_primary_id==$new_primary_id) && ($old_firm_type==$new_firm_type) && ($old_district_id==$new_district_id)) {

						$allow_id = 'yes';

					} else {

						$allow_id = 'no';
						break;
					}
				}
			}
		}

		echo '~'.$allow_id.'~';
		exit;
	}



	// UPDATE COMMODITY CALL
	// DESCRIPTION : to update the commodity in process.
	// @AUTHOR : AMOL CHOUDHARI
	// DATE : 03-07-2021
	
	public function updateCommodityCall() {

		$this->autoRender = false;
		$selected_commodity = $_POST['selected_commodity'];
		$remark = $_POST['remark'];

		//get customer_id
		if ($this->Session->read('customer_id')==null) {
			$customer_id = $this->Session->read('username');
		} else {
			$customer_id = $this->Session->read('customer_id');
		}

		$selected_commodity = implode(',',$selected_commodity);
		//to check to string contain first character ',', then remove that ','
		if (substr($selected_commodity, 0, 1) === ',') {

			$selected_commodity = ltrim($selected_commodity, ',');
		}

		$this->loadModel('DmiFirms');
		$this->loadModel('DmiCommodityPacktypeUpdateLogs');

		//get firm details
		$firm_details = $this->DmiFirms->firmDetails($customer_id);

		$mod_dt = date('Y-m-d H:i:s');

		//add record in log table
		$logArray = array(

			'customer_id'=>$customer_id,
			'prev_values'=>$firm_details['sub_commodity'],
			'new_values'=>$selected_commodity,
			'update_by'=>$this->Session->read('username'),
			'remark'=>$remark,
			'created'=>$mod_dt
		);

		$logTableEntity = $this->DmiCommodityPacktypeUpdateLogs->newEntity($logArray);
		$this->DmiCommodityPacktypeUpdateLogs->save($logTableEntity);

		//update firm table
		$this->DmiFirms->updateAll(array('sub_commodity'=>"$selected_commodity",'modified'=>"$mod_dt"),array('customer_id IS'=>$customer_id));

		echo '~done~';

	}



	// UPDATE PACKING TYPE CALL
	// DESCRIPTION : to update the packing in process.
	// @AUTHOR : AMOL CHOUDHARI
	// DATE : 03-07-2021
	
	public function updatePackingTypeCall() {

		$this->autoRender = false;
		$selected_packing_types = $_POST['selected_packing_types'];
		$remark = $_POST['remark'];

		//get customer_id
		if ($this->Session->read('customer_id')==null) {
			$customer_id = $this->Session->read('username');
		} else {
			$customer_id = $this->Session->read('customer_id');
		}

		$selected_packing_types = implode(',',$selected_packing_types);
		//to check to string contain first character ',', then remove that ','
		if (substr($selected_packing_types, 0, 1) === ',') {

			$selected_packing_types = ltrim($selected_packing_types, ',');
		}

		$this->loadModel('DmiFirms');
		$this->loadModel('DmiCommodityPacktypeUpdateLogs');

		//get firm details
		$firm_details = $this->DmiFirms->firmDetails($customer_id);

		$mod_dt = date('Y-m-d H:i:s');

		//add record in log table
		$logArray = array(

			'customer_id'=>$customer_id,
			'prev_values'=>$firm_details['packaging_materials'],
			'new_values'=>$selected_packing_types,
			'update_by'=>$this->Session->read('username'),
			'remark'=>$remark,
			'created'=>$mod_dt
		);

		$logTableEntity = $this->DmiCommodityPacktypeUpdateLogs->newEntity($logArray);
		$this->DmiCommodityPacktypeUpdateLogs->save($logTableEntity);

		//update firm table
		$this->DmiFirms->updateAll(array('packaging_materials'=>"$selected_packing_types",'modified'=>"$mod_dt"),array('customer_id IS'=>$customer_id));

		echo '~done~';
	}


	
	// TRANSFER APP RO OFFICE
	// DESCRIPTION : Transfer application from so office to Ro office
	// @AUTHOR : PRAVIN BHAKARE
	// DATE : 11-10-2021
	
	public function transferAppToROOffice() {

		$this->autoRender = false;

		$username = $this->Session->read('username');
		$customer_id = $this->Session->read('customer_id');
		$application_type = $this->Session->read('application_type');

		$this->loadModel('DmiApplWithRoMappings');
		$this->loadModel('DmiUsers');

		$dashboardCon = new DashboardController();

		$from_office = $this->DmiUsers->getPostedOffId($username);

		$get_office_record = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$to_office = $get_office_record['ro_id_for_so'];
		$remark = "single officer available in SO office";

		$result = $dashboardCon->transferAppFormTo($application_type,$customer_id,$from_office,$to_office,$remark);
	
		echo $result;
		exit;
	}



	// SET SESSION FOR RENEWAL ESIGN
	// DESCRIPTION : to set the session, when RO/SO esign renewal certificate which is already granted on DDO payment approval
	// @AUTHOR : AMOL CHOUDHARI
	// DATE : 12-10-2021
	
	public function setSessionForRenewalEsign() {

		$customer_id = $_POST['customer_id'];
		$pdf_name = $_POST['pdf_name'];

		//set session
		$this->Session->write('customer_id',$customer_id);
		$this->Session->write('pdf_file_name',$pdf_name);
		$this->Session->write('current_level','level_3');
		$this->Session->write('ren_esign_process','yes');
		$this->Session->write('application_type',2);

		//change the renewal final submit last status to pending and level_1 from approved level_3 temparary 
		//to generate proper grant certificate pdf again when RO/SO tries to esign the certificate.
		//This status will again reverted to approved level_3 once grant pdf is generated and ready to esign.
		//also update created date
		/*	$this->loadModel('DmiRenewalFinalSubmits');
		$getLastId = $this->DmiRenewalFinalSubmits->find('all',array('fields'=>'id','conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
		if (!empty($getLastId)) {
			$this->DmiRenewalFinalSubmits->updateAll(array('status'=>'pending','current_level'=>'level_1','created'=>date('Y-m-d H:i:s')),array('id'=>$getLastId['id']));
		}*/
		exit;
	}



	// SEARCH APPLICATION
	// DESCRIPTION : user dashboard search application ajax function.
	// @AUTHOR : AMOL CHOUDHARI
	// DATE : 25-10-2021
	
	public function searchApplication() {

		$username = $this->Session->read('username');
		$customer_id = $_POST['applicant_id'];
		$this->loadModel('DmiUserRoles');
		$check_user_role = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$username)))->first();
		$this->loadComponent('Randomfunctions');
		$resultArray = $this->Randomfunctions->dashboardApplicationSearch($customer_id,$check_user_role);


		if ($resultArray['no_result']==null) {

			echo "<table class='table'>
				<thead>
					<tr>
						<th>Application Id</th>
						<th>District</th>
						<th>Position</th>
						<th>Process</th>
						<th>Available With</th>

					</tr>
				</thead>
				<tbody>
					<tr>
						<td>".$customer_id."</td>
						<td>".$resultArray['firm_data']['district']."</td>
						<td>".$resultArray['current_position']."</td>
						<td>".$resultArray['process']."</td>
						<td>".$resultArray['currentPositionUser']." <br>( ".$resultArray['getEmailCurrent']." )"."</td>
					</tr>
				</tbody>
			</table>";

		}else{
			echo $resultArray['no_result'];
		}
		
		exit;
	}

	
	
	
	// CHECK IF REPLICA CODE IS EXIST
	// DESCRIPTION : FOR CHECKING THE REPLICA CODE IS AVAILABLE OR NOT IN THE DATABASE.
	// @AUTHOR : AKASH THAKRE
	// DATE : 02-12-2021
	
	public function checkIfReplicaCodeIsExist() {

		$this->autoRender = false;
		//Load Models
		$this->loadModel('DmiRoOffices');
		$replica_code = $_POST['replica_code'];
		
		$check_If_exist = $this->DmiRoOffices->find('all')->select(['replica_code'])->where(['OR' => [['delete_status IS NULL'] ,['delete_status ='=>'no']]])->where(['replica_code' => $replica_code])->first();
		
		if (!empty($check_If_exist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
		
		exit;
	}



	// CHECK IF OFFICE SHORT CODE IS EXIST
	// DESCRIPTION : FOR CHECKING THE RO/SO OFFICE SHORT CODE IS AVAILABLE OR NOT IN THE DATABASE.
	// @AUTHOR : AKASH THAKRE
	// DATE : 02-12-2021
	
	public function checkIfOfficeShortCodeIsExist() {

		$this->autoRender = false;
		//Load Models
		$this->loadModel('DmiRoOffices');
		$short_code = $_POST['short_code'];
		
		$check_If_exist = $this->DmiRoOffices->find('all')->select(['short_code'])->where(['OR' => [['delete_status IS NULL'] ,['delete_status ='=>'no']]])->where(['short_code' => $short_code])->first();
		
		if (!empty($check_If_exist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
		
		exit;
	}

	
	
	// CHECK IF STATE ALREADY EXIST
	// DESCRIPTION : FOR CHECKING THE STATE NAME IS ALREADY EXISTS OR NOT IN THE DATABASE.
	// @AUTHOR : AKASH THAKRE
	// DATE : 02-12-2021
	
	public function checkIfStateAlreadyExist() {	
	
		$this->autoRender = false;
		//Load Models
		$this->loadModel('DmiStates');
		$state_name = $_POST['state_name'];
		$check_if_exist = $this->DmiStates->find()->select(['state_name'])->where(['state_name' => $state_name,'delete_status IS NULL'])->first();
		
		if (!empty($check_if_exist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
		exit;
	}
	
	
	
	
	// CHECK IF DISTRICT ALREADY EXIST
	// DESCRIPTION : FOR CHECKING THE DISTRICT NAME IS ALREADY EXISTS OR NOT IN THE DATABASE.
	// @AUTHOR : AKASH THAKRE
	// DATE : 04-12-2021
	
	public function checkIfDistrictAlreadyExist() {
	
		$this->autoRender = false;
		//Load Models
		$this->loadModel('DmiDistricts');
		$district_name = $_POST['district_name'];
		$check_if_exist = $this->DmiDistricts->find()->select(['district_name'])->where(['district_name' => $district_name,'delete_status IS NULL'])->first();
		
		if (!empty($check_if_exist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
		exit;
	}


	
	// CHECK MOBILE NUMBER EXIST IN CHEMIST TABLE
	// DESCRIPTION : FOR CHECKING THE MOBILE NO. ALREADY EXIST OR NOT IN THE DATABASE FOR CHEMIST.
	// @AUTHOR : AKASH THAKRE
	// DATE : 04-12-2021
	
	public function checkMobileNumberExistInChemistTable() {

		$this->autoRender = false;
		$this->loadModel('DmiChemistRegistrations');
		$mobile = base64_encode($_POST['mobile']); //for email encoding
	
		$check_if_exist = $this->DmiChemistRegistrations->find()->select(['mobile'])->where(['mobile IS' => $mobile])->first();

		if (!empty($check_if_exist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
		exit;
	}


	// CHECK MOBILE NUMBER EXIST IN CHEMIST TABLE
	// DESCRIPTION : FOR CHECKING THE MOBILE NO. ALREADY EXIST OR NOT IN THE DATABASE FOR CHEMIST.
	// @AUTHOR : AKASH THAKRE
	// DATE : 04-12-2021
	
	public function checkEmailNumberExistInChemistTable() {

		$this->autoRender = false;
		$this->loadModel('DmiChemistRegistrations');
		$mobile = base64_encode($_POST['email']); //for email encoding
	
		$check_if_exist = $this->DmiChemistRegistrations->find()->select(['email'])->where(['email IS' => $mobile])->first();

		if (!empty($check_if_exist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
		exit;
	}
	

	// CHECK EMAIL ID EXIST IN CUSTOMERS TABLE
	// DESCRIPTION : FOR CHECKING THE EMAIL ID. IS ALREADY EXIST OR NOT IN DATABASE FOR CUSTOMERS
	// @AUTHOR : AKASH THAKRE
	// DATE : 25-12-2021
	
	public function checkEmailExistInCustomerTable() {

		$this->autoRender = false;
		$this->loadModel('DmiCustomers');
		$email = base64_encode($_POST['email']); //for email encoding
		$check_if_exist = $this->DmiCustomers->find()->select(['email'])->where(['email IS' => $email])->first();

		if (!empty($check_if_exist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
		exit;
	}



	
	// CHECK MOBILE NUMBER EXIST IN CUSTOMERS TABLE
	// DESCRIPTION : FOR CHECKING THE MOBILE NO. ALREADY EXIST OR NOT IN DATABASE FOR CUSTOMERS
	// @AUTHOR : AKASH THAKRE
	// DATE : 25-12-2021
	
	public function checkMobileNumberExistInCustomersTable() {

		$this->autoRender = false;
		$this->loadModel('DmiCustomers');
		$mobile = base64_encode($_POST['mobile']); //for email encoding
	
		$check_if_exist = $this->DmiCustomers->find()->select(['mobile'])->where(['mobile IS' => $mobile])->first();

		if (!empty($check_if_exist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
		exit;
	}



	// CHECK OLD PASSWORD
	// DESCRIPTION : FOR CHECKING THE OLD PASSWORD
	// @AUTHOR : AKASH THAKRE
	// DATE : 03-12-2021
	
	public function checkOldPassword()
	{
		$this->autoRender = false;
		$username = $this->Session->read('username');
		$explodeValue = count(explode('/',(string) $username)); #For Deprecations
	
		if ($explodeValue == 4) {
			$model = 'DmiFirms';
			$condition = array('customer_id IS'=>$username);
		} elseif ($explodeValue == 2) {
			$model = 'DmiCustomers';
			$condition = array('customer_id IS'=>$username);
		} elseif ($explodeValue == 3) {
			$model = 'DmiChemistRegistrations';
			$condition = array('chemist_id IS'=>$username);
		} else {
			$model = 'DmiUsers';
			$condition = array('email IS'=>$username);
		}
		
		$this->loadModel($model);
		$get_password = $_POST['Oldpassword'];
		$oldPassword = hash('sha512',$get_password);
		
		$checkDatabasePassword = $this->$model->find('all',array('fields'=>array('password'),'conditions'=>$condition))->first();

		if (!empty($checkDatabasePassword)) {

			$existedPassword = $checkDatabasePassword['password'];

			if ($oldPassword != $existedPassword) {
				echo 'yes';
			} else {
				echo 'no';
			}
		}

		exit;

	}


	
	// CHECK MOBILE NUMBER EXIST IN USERS TABLE
	// DESCRIPTION : FOR CHECKING THE MOBILE NO. ALREADY EXIST OR NOT IN DATABASE FOR USERS
	// @AUTHOR : AKASH THAKRE
	// DATE : 21-02-2022
	
	public function checkMobileNumberExistInUsersTable() {

		$this->autoRender = false;
		$this->loadModel('DmiUsers');
		$mobile = base64_encode($_POST['phone']); //for email encoding
	
		$check_if_exist = $this->DmiUsers->find()->select(['phone'])->where(['phone IS' => $mobile])->first();

		if (!empty($check_if_exist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
		exit;
	}




	// CHECK EMAIL EXIST IN USERS TABLE
	// DESCRIPTION : FOR CHECKING THE EMAIL ID. ALREADY EXIST OR NOT IN DATABASE FOR USERS.
	// @AUTHOR : AKASH THAKRE
	// DATE : 21-02-2022
	
	
	public function checkEmailExistInUsersTable() {

		$this->autoRender = false;
		$this->loadModel('DmiUsers');
		$email = base64_encode($_POST['email']); //for email encoding
		$check_if_exist = $this->DmiUsers->find()->select(['email'])->where(['email IS' => $email])->first();

		if (!empty($check_if_exist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
		exit;
	}


	// check If Commodity Replica Is Exists
	// DESCRIPTION : FOR CHECKING THE REPLICA COMMODIY , RETURN THE YES OR NO
	// @AUTHOR : AKASH THAKRE
	// DATE : 08-09-2022

	public function checkIfCommodityReplicaIsExists() {

		$this->autoRender = false;
		$commodity_code = trim($_POST['commodity']);
		//Load Model
		$this->loadModel('DmiReplicaChargesDetails');
		$checkIfExist = $this->DmiReplicaChargesDetails->find()->select(['id'])->where(['commodity_code IS'=>$commodity_code])->first();
		
		if (empty($checkIfExist)) {
			echo 'yes';
		} else {
			echo 'no';
		}
	}
	// check If Session Is Exists
	// DESCRIPTION : FOR CHECKING THE SESSION IF SESSION IS YES , RETURN THE YES OR NO
	// @AUTHOR : SHANKHPAL SHENDE
	// DATE : 24-11-2022

	public function checkIfSesionIsExists() {

		//$this->autoRender = false;
		// taking editmode data in Session variables
	    $this->Session->write('adpupdatemode','yes');
	    echo 'yes';exit;
			
	}


}
?>