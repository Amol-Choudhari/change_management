<?php	//component for masters related functions .
// Define functions for all master tables value.
namespace app\Controller\Component;
use Cake\Controller\Controller;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\EntityInterface;

class MastertablecontentComponent extends Component {

	public $controller = null;
	public $session = null;

	public function initialize(array $config): void {
		parent::initialize($config);
		$this->Controller = $this->_registry->getController();
		$this->Session = $this->getController()->getRequest()->getSession();

	}


	public function allStateValue(){

		$DmiStates = TableRegistry::getTableLocator()->get('DmiStates');
		$state_list = $DmiStates->find('list',array('valueField'=>array('state_name'),'keyField'=>array('id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $state_list;
	}


	public function stateValueById($id){

		$DmiStates = TableRegistry::getTableLocator()->get('DmiStates');
		$state_field = $DmiStates->find('all',array('fields'=>array('state_name'),'conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$state_value = $state_field['state_name'];
		return $state_value;
	}

	public function allDistrictValue(){

		$DmiDistricts = TableRegistry::getTableLocator()->get('DmiDistricts');
		$district_list = $DmiDistricts->find('list',array('valueField'=>array('district_name'),'keyField'=>array('id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $district_list;
	}


	public function districtValueById($id){

		$DmiDistricts = TableRegistry::getTableLocator()->get('DmiDistricts');
		$district_field = $DmiDistricts->find('all',array('conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$district_value = $district_field;
		return $district_value;
	}


	public function allCommodityCategories(){

		$MCommodityCategory = TableRegistry::getTableLocator()->get('MCommodityCategory');
		$categories_list = $MCommodityCategory->find('list',array('valueField'=>array('category_name'),'keyField'=>array('category_code'),'conditions'=>array('display'=>'Y'),'order'=>array('category_code')))->toArray();
		return $categories_list;
	}


	public function allBusinessType(){

		$DmiBusinessTypes = TableRegistry::getTableLocator()->get('DmiBusinessTypes');
		$business_type_list = $DmiBusinessTypes->find('list',array('valueField'=>array('business_type'),'keyField'=>array('id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $business_type_list;
	}


	public function businessTypeById($id){

		$DmiBusinessTypes = TableRegistry::getTableLocator()->get('DmiBusinessTypes');
		$business_type_field = $DmiBusinessTypes->find('all',array('fields'=>array('business_type'),'conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$business_type_value = $business_type_field['business_type'];
		return $business_type_value;
	}


	public function allCaBusinessYear(){

		$DmiCaBusinessYears = TableRegistry::getTableLocator()->get('DmiCaBusinessYears');
		$ca_business_year_list = $DmiCaBusinessYears->find('list',array('valueField'=>array('business_years'),'keyField'=>array('id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $ca_business_year_list;
	}


	public function caBusinessYearById($id){

		$DmiCaBusinessYears = TableRegistry::getTableLocator()->get('DmiCaBusinessYears');
		$ca_business_year_field = $DmiCaBusinessYears->find('all',array('fields'=>array('business_years'),'conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$ca_business_year_value = $ca_business_year_field['business_years'];
		return $ca_business_year_value;
	}


	public function allPrintingBusinessYear(){

		$DmiPrintingBusinessYears = TableRegistry::getTableLocator()->get('DmiPrintingBusinessYears');
		$printing_business_year_list = $DmiPrintingBusinessYears->find('list',array('valueField'=>array('business_years'),'keyField'=>array('id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $printing_business_year_list;
	}


	public function printingBusinessYearById($id){

		$DmiPrintingBusinessYears = TableRegistry::getTableLocator()->get('DmiPrintingBusinessYears');
		$printing_business_year_field = $DmiPrintingBusinessYears->find('all',array('fields'=>array('business_years'),'conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$printing_business_year_value = $printing_business_year_field['business_years'];
		return $printing_business_year_value;
	}


	public function allCrushingRefiningValue(){

		$DmiCrushingRefiningPeriods = TableRegistry::getTableLocator()->get('DmiCrushingRefiningPeriods');
		$crushing_refining_period_list = $DmiCrushingRefiningPeriods->find('list',array('valueField'=>array('crushing_refining_periods'),'keyField'=>array('id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $crushing_refining_period_list;
	}


	public function crushingRefiningValueById($id){

		$DmiCrushingRefiningPeriods = TableRegistry::getTableLocator()->get('DmiCrushingRefiningPeriods');
		$crushing_refining_period_field = $DmiCrushingRefiningPeriods->find('all',array('fields'=>array('crushing_refining_periods'),'conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$crushing_refining_period_value = $crushing_refining_period_field['crushing_refining_periods'];
		return $crushing_refining_period_value;
	}


	public function allPackingType(){

		$DmiPackingTypes = TableRegistry::getTableLocator()->get('DmiPackingTypes');
		$packing_type_list = $DmiPackingTypes->find('list',array('valueField'=>array('packing_type'),'keyField'=>array('id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $packing_type_list;
	}


	public function packingTypeById($id){

		$DmiPackingTypes = TableRegistry::getTableLocator()->get('DmiPackingTypes');
		$packing_type_field = $DmiPackingTypes->find('all',array('fields'=>array('packing_type'),'conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$packing_type_value = $packing_type_field['packing_type'];
		return $packing_type_value;

	}


	public function allApplicationCharges(){

		$DmiApplicationCharges = TableRegistry::getTableLocator()->get('DmiApplicationCharges');
		$application_charge_list = $DmiApplicationCharges->find('list',array('valueField'=>array('charge'),'keyField'=>array('certificate_type_id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('certificate_type_id')))->toArray();
		return $application_charge_list;
	}


	public function applicationChargesById($id){

		$DmiApplicationCharges = TableRegistry::getTableLocator()->get('DmiApplicationCharges');
		$application_charge_field = $DmiApplicationCharges->find('all',array('conditions'=>array('certificate_type_id IS'=>$id, 'delete_status IS NULL')))->first();
		$application_charge_value = $application_charge_field;
		return $application_charge_value;
	}


	public function allMachineType(){

		$DmiMachineTypes = TableRegistry::getTableLocator()->get('DmiMachineTypes');
		$machine_type_list = $DmiMachineTypes->find('list',array('valueField'=>array('machine_types'),'keyField'=>array('id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $machine_type_list;
	}


	public function machineTypeById($id){

		$DmiMachineTypes = TableRegistry::getTableLocator()->get('DmiMachineTypes');
		$machine_type_field = $DmiMachineTypes->find('all',array('fields'=>array('machine_types'),'conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$machine_type_value = $machine_type_field['machine_types'];
		return $machine_type_value;
	}


	public function allTankShapes(){

		$DmiTankShapes = TableRegistry::getTableLocator()->get('DmiTankShapes');
		$tank_shape_list = $DmiTankShapes->find('list',array('valueField'=>array('tank_shapes'),'keyField'=>array('id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $tank_shape_list;
	}


	public function tankShapeById($id){

		$DmiTankShapes = TableRegistry::getTableLocator()->get('DmiTankShapes');
		$tank_shape_field = $DmiTankShapes->find('all',array('fields'=>array('tank_shapes'),'conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$tank_shape_value = $tank_shape_field['tank_shapes'];
		return $tank_shape_value;
	}

	public function applicationType(){

		$DmiApplicationTypes = TableRegistry::getTableLocator()->get('DmiApplicationTypes');
		$application_type_list = $DmiApplicationTypes->find('list',array('valueField'=>array('application_type'),'keyField'=>array('id'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $application_type_list;
	}

	public function applicationTypeById($id){

		$DmiApplicationTypes = TableRegistry::getTableLocator()->get('DmiApplicationTypes');
		$application_type_field = $DmiApplicationTypes->find('all',array('fields'=>array('application_type'),'conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$application_type_value = $application_type_field['application_type'];
		return $application_type_value;

	}

	public function allLaboratoryType(){

		$DmiLaboratoryTypes = TableRegistry::getTableLocator()->get('DmiLaboratoryTypes');
		$laboratory_type_list = $DmiLaboratoryTypes->find('list',array('valueField'=>array('laboratory_type'),'conditions'=>array('delete_status IS NULL'),'order'=>array('id')))->toArray();
		return $laboratory_type_list;
	}

	public function laboratoryTypeById($id){

		$DmiLaboratoryTypes = TableRegistry::getTableLocator()->get('DmiLaboratoryTypes');
		$laboratory_type_field = $DmiLaboratoryTypes->find('all',array('fields'=>array('laboratory_type'),'conditions'=>array('id IS'=>$id, 'delete_status IS NULL')))->first();
		$laboratory_type_value = $laboratory_type_field['laboratory_type'];
		return $laboratory_type_value;
	}

	public function allCertificateType(){

		$DmiCertificateTypes = TableRegistry::getTableLocator()->get('DmiCertificateTypes');
		$Certificate_type_list = $DmiCertificateTypes->find('list',array('valueField'=>array('certificate_type'),'order'=>array('id')))->toArray();
		return $Certificate_type_list;
	}

	public function CertificateTypeId($id){

		$DmiCertificateTypes = TableRegistry::getTableLocator()->get('DmiCertificateTypes');
		$Certificate_type_field = $DmiCertificateTypes->find('all',array('fields'=>array('certificate_type'),'conditions'=>array('id IS'=>$id)))->first();
		$Certificate_type_value = $Certificate_type_field['certificate_type'];
		return $Certificate_type_value;
	}


	//For Education Type Master used for Chemist
	public function allEducationType(){

		$DmiEducationTypes = TableRegistry::getTableLocator()->get('DmiEducationTypes');
		$all_education_types = $DmiEducationTypes->find('all',array('conditions'=>array('delete_status'=>'no')))->toArray();
		return $all_education_types;

	}

	//For Division Grades Master used for Chemist
	public function allDivisionGrade(){

		$DmiDivisionGrades = TableRegistry::getTableLocator()->get('DmiDivisionGrades');
		$all_division_types = $DmiDivisionGrades->find('all',array('conditions'=>array('delete_status'=>'no'),'order' => array('id' => 'asc')))->toArray();
		return $all_division_types;
	}

	//For Dmi Document Lists # Added on the 09-08-2022 by Akash
	public function allDocumentsList(){

		$DmiDocumentLists = TableRegistry::getTableLocator()->get('DmiDocumentLists');
		$all_documents_list = $DmiDocumentLists->find('list', array('valueField' => 'document_name', 'conditions' => array()))->toArray();
		return $all_documents_list;
	}

	//--------------------------------------------//

	//Get User Pao ID
	public function getPaoUserId($customer_id,$appl_type){

		$flow_wise_table = TableRegistry::getTableLocator()->get('DmiFlowWiseTableslists');
		$appl_table = $flow_wise_table->find('all',array('conditions'=>array('application_type IS'=>$appl_type)))->first();
		$get_payemt_table = $appl_table['payment'];

		$payemt_table = TableRegistry::getTableLocator()->get($get_payemt_table);
		//get user id

		$payment_details = $payemt_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
		$pao_id = $payment_details['pao_id'];

		//get pao details
		$pao_table = TableRegistry::getTableLocator()->get('DmiPaoDetails');
		$pao_details = $pao_table->find('all',array('conditions'=>array('id IS'=>$pao_id)))->first();
		$user_id = $pao_details['pao_user_id'];

		//get use details
		$user_table = TableRegistry::getTableLocator()->get('DmiUsers');
		$user_details = $user_table->find('all',array('conditions'=>array('id IS'=>$user_id)))->first();
		$pao_email_id = $user_details['email'];

		return $pao_email_id;
	}


	public function authFirmRegistration($customer_id){

		$DmiAuthFirmRegistrations = TableRegistry::getTableLocator()->get('DmiAuthFirmRegistrations');

		$application_type = $this->Session->read('application_type');

		$result = $DmiAuthFirmRegistrations->find('all',array('fields'=>array('firm_id'),'conditions'=>array('firm_id IS'=>$customer_id, 'delete_status IS NULL')))->first();

		if (!empty($result ) && $application_type == 1) {
			 $value ='yes';
		} else {
			$value='no';
		}
		return $value;
	}


	//function to get some records conditionaly for each master.
	//by Amol from 16-06-2020

	//to get state name for each district
	public function listStateForDistrictMaster($all_records){

		// to show district state names
		$DmiStates = TableRegistry::getTableLocator()->get('DmiStates');
		$state_name = array();
		$i=0;
		foreach ($all_records as $each_state) {

			$find_state_name = $DmiStates->find('all',array('conditions'=>array('id IS'=>$each_state['state_id'])))->first();
			$state_name[$i] = $find_state_name['state_name'];
			$i=$i+1;

			$this->Controller->set('state_name',$state_name);
		}

		return $state_name;
	}


	//to add/edit state
	public function addEditStateMaster($postData,$record_id=null){

		$DmiStates = TableRegistry::getTableLocator()->get('DmiStates');
		//html encoding
		$state_name = htmlentities($postData['state_name'], ENT_QUOTES);

		//save array
		$data_array = array('state_name'=>$state_name,
			'user_email_id'=>$this->Session->read('username'),
			'created'=>date('Y-m-d H:i:s'));

		//edit array
		if ($record_id != null) {

			$data_array = array('id'=>$record_id,
								'state_name'=>$state_name,
								'user_email_id'=>$this->Session->read('username'),
								'modified'=>date('Y-m-d H:i:s'));
		}

		$DmiStatesEntity = $DmiStates->newEntity($data_array);

		if ($DmiStates->save($DmiStatesEntity)) {

			return true;
		}

	}



	//to add/Edit district
	public function addEditDistrictMaster($postData,$record_id=null){

		$DmiDistricts = TableRegistry::getTableLocator()->get('DmiDistricts');
		$DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');

		//check dropdown //for state list
		$post_input_request = $postData['state_list'];
		$state_id = $this->Controller->Customfunctions->dropdownSelectInputCheck('DmiStates',$post_input_request);

		//For RO office List
		if (!empty($postData['ro_offices_list'])) {

			$post_input_request = $postData['ro_offices_list'];
			$ro_office_id = $this->Controller->Customfunctions->dropdownSelectInputCheck('DmiRoOffices',$post_input_request);

		} else {

			$ro_office_id = null;
		}

		//for SO Offices list (this will be optional for district)
		if ($postData['dist_office_type'] == 'SO' && !empty($postData['so_offices_list'])){

			$post_input_request = $postData['so_offices_list'];
			$so_office_id = $this->Controller->Customfunctions->dropdownSelectInputCheck('DmiRoOffices',$post_input_request);

		} else {

			$so_office_id = null;
		}

		// html encoding
		$district_name = htmlentities($postData['district_name'], ENT_QUOTES);

		//save array
		$data_array = array('district_name'=>$district_name,
							'state_id'=>$state_id,
							'ro_id'=>$ro_office_id,
							'so_id'=>$so_office_id,
							'smd_id'=>null,
							'user_email_id'=>$this->Session->read('username'),
							'created'=>date('Y-m-d H:i:s'));

		//edit array
		if ($record_id != null) {

			$data_array = array('id'=>$record_id,
								'district_name'=>$district_name,
								'state_id'=>$state_id,
								'ro_id'=>$ro_office_id,
								'so_id'=>$so_office_id,
								'smd_id'=>null,
								'user_email_id'=>$this->Session->read('username'),
								'modified'=>date('Y-m-d H:i:s'));
		}

		$DmiDistrictsEntity = $DmiDistricts->newEntity($data_array);

		if ($DmiDistricts->save($DmiDistrictsEntity)) {

			return true;
		}

	}


	//to add/Edit business type
	public function addEditBusinessTypeMaster($postData,$record_id=null){

		$DmiBusinessTypes = TableRegistry::getTableLocator()->get('DmiBusinessTypes');
		//html encoding
		$business_type = htmlentities($postData['business_type'], ENT_QUOTES);

		//save array
		$data_array = array('business_type'=>$business_type,'user_email_id'=>$this->Session->read('username'),'created'=>date('Y-m-d H:i:s'));

		//edit array
		if ($record_id != null) {

			$data_array = array('id'=>$record_id,'business_type'=>$business_type,'user_email_id'=>$this->Session->read('username'),'modified'=>date('Y-m-d H:i:s'));
		}

		$DmiBusinessTypesEntity = $DmiBusinessTypes->newEntity($data_array);

		if ($DmiBusinessTypes->save($DmiBusinessTypesEntity)) {

			return true;
		}

	}


	//to add/Edit Packing type
	public function addEditPackingTypeMaster($postData,$record_id=null){

		$DmiPackingTypes = TableRegistry::getTableLocator()->get('DmiPackingTypes');
		//html encoding
		$packing_type = htmlentities($postData['packing_type'], ENT_QUOTES);

		//save array
		$data_array = array('packing_type'=>$packing_type,
							'user_email_id'=>$this->Session->read('username'),
							'created'=>date('Y-m-d H:i:s'));

		//edit array
		if ($record_id != null) {

			$data_array = array('id'=>$record_id,
				'packing_type'=>$packing_type,
				'user_email_id'=>$this->Session->read('username'),
				'modified'=>date('Y-m-d H:i:s'));
		}

		$DmiPackingTypesEntity = $DmiPackingTypes->newEntity($data_array);

		if ($DmiPackingTypes->save($DmiPackingTypesEntity)) {

			return true;
		}

	}



	//to add/Edit Laboratory type
	public function addEditLaboratoryTypeMaster($postData,$record_id=null){

		$DmiLaboratoryTypes = TableRegistry::getTableLocator()->get('DmiLaboratoryTypes');
		//html encoding
		$laboratory_type = htmlentities($postData['laboratory_type'], ENT_QUOTES);

		//save array
		$data_array = array('laboratory_type'=>$laboratory_type,
							'user_email_id'=>$this->Session->read('username'),
							'created'=>date('Y-m-d H:i:s'));

		//edit array
		if ($record_id != null) {

			$data_array = array('id'=>$record_id,
								'laboratory_type'=>$laboratory_type,
								'user_email_id'=>$this->Session->read('username'),
								'modified'=>date('Y-m-d H:i:s'));
		}

		$DmiLaboratoryTypesEntity = $DmiLaboratoryTypes->newEntity($data_array);

		if ($DmiLaboratoryTypes->save($DmiLaboratoryTypesEntity)) {

			return true;
		}

	}



	//to add/Edit Machine type
	public function addEditMachineTypeMaster($postData,$record_id=null){

		$DmiMachineTypes = TableRegistry::getTableLocator()->get('DmiMachineTypes');
		//html encoding
		$machine_types = htmlentities($postData['machine_types'], ENT_QUOTES);

		//save array
		$data_array = array('machine_types'=>$machine_types,
							'application_type'=>$postData['application_type'],
							'user_email_id'=>$this->Session->read('username'),
							'created'=>date('Y-m-d H:i:s'));

		//edit array
		if ($record_id != null) {

			$data_array = array('id'=>$record_id,
								'machine_types'=>$machine_types,
								'application_type'=>$postData['application_type'],
								'user_email_id'=>$this->Session->read('username'),
								'modified'=>date('Y-m-d H:i:s'));
		}

		$DmiMachineTypesEntity = $DmiMachineTypes->newEntity($data_array);

		if ($DmiMachineTypes->save($DmiMachineTypesEntity)) {

			return true;
		}

	}



	//to add/Edit Tank Shapes
	public function addEditTankShapesMaster($postData,$record_id=null){

		$DmiTankShapes = TableRegistry::getTableLocator()->get('DmiTankShapes');
		//html encoding
		$tank_shapes = htmlentities($postData['tank_shapes'], ENT_QUOTES);

		//save array
		$data_array = array('tank_shapes'=>$tank_shapes,
							'user_email_id'=>$this->Session->read('username'),
							'created'=>date('Y-m-d H:i:s'));

		//edit array
		if ($record_id != null) {

			$data_array = array('id'=>$record_id,
								'tank_shapes'=>$tank_shapes,
								'user_email_id'=>$this->Session->read('username'),
								'modified'=>date('Y-m-d H:i:s'));
		}

		$DmiTankShapesEntity = $DmiTankShapes->newEntity($data_array);

		if ($DmiTankShapes->save($DmiTankShapesEntity)) {

			return true;
		}

	}


	//to edit Application Charges
	public function addEditapplChargeMaster($postData,$record_id=null){

		$DmiApplicationCharges = TableRegistry::getTableLocator()->get('DmiApplicationCharges');
		$charge = htmlentities($postData['charge'], ENT_QUOTES);
		$application_type = htmlentities($postData['application_type'], ENT_QUOTES);

		//Modified the charges edit function of All charges to add the values of Applucation Type and Firm Type on 09-04-2022 by Akash
		$application_type_id = htmlentities($postData['application_type_id'],ENT_QUOTES);
		$firm_type = htmlentities($postData['firm_type'], ENT_QUOTES);

		//edit array
		if ($record_id != null) {

			$data_array = array(
				'id'=>$record_id,
				'charge'=>$charge,
				'user_email_id'=>$this->Session->read('username'),
				'modified'=>date('Y-m-d H:i:s'),
				//'application_type_id'=>$application_type_id, //values of Applucation Type on 09-04-2022 by Akash
				//'firm_type'=>$firm_type //the values of  Firm Type on 09-04-2022 by Akash
			);

		//add array
		} else {

			$data_array = array(
				'application_type'=>$application_type,
				'charge'=>$charge,
				'user_email_id'=>$this->Session->read('username'),
				'created'=>date('Y-m-d H:i:s'),
				'application_type_id'=>$application_type_id, //values of Applucation Type on 09-04-2022 by Akash
				'firm_type'=>$firm_type //the values of  Firm Type on 09-04-2022 by Akash
			);
		}

		$DmiApplicationChargesEntity = $DmiApplicationCharges->newEntity($data_array);

		if ($DmiApplicationCharges->save($DmiApplicationChargesEntity)) {

			return true;
		}

	}


	//to add/edit business years
	public function addEditbusinessYearsMaster($postData,$record_id=null){

		$years_for = $postData['business_years_for'];
		$post_field = htmlentities($postData['business_years'], ENT_QUOTES);

		if ($years_for=='0') {

			$masterTable = TableRegistry::getTableLocator()->get('DmiCaBusinessYears');
			$column_name = 'business_years';

		} elseif ($years_for=='1') {

			$masterTable = TableRegistry::getTableLocator()->get('DmiPrintingBusinessYears');
			$column_name = 'business_years';

		} elseif ($years_for=='2') {

			$masterTable = TableRegistry::getTableLocator()->get('DmiCrushingRefiningPeriods');
			$column_name = 'crushing_refining_periods';
		}

		//save array
		$data_array = array($column_name=>$post_field,
							'user_email_id'=>$this->Session->read('username'),
							'created'=>date('Y-m-d H:i:s'));

		//edit array
		if ($record_id != null) {

			$data_array = array('id'=>$record_id,
								$column_name=>$post_field,
								'user_email_id'=>$this->Session->read('username'),
								'modified'=>date('Y-m-d H:i:s'));
		}

		$masterTableEntity = $masterTable->newEntity($data_array);

		if ($masterTable->save($masterTableEntity)) {

			return true;
		}

	}



	//add/edit Office Master
	public function addEditOfficeMaster($postData,$record_id=null){

		//load model
		$DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$DmiRoAllocationLogs = TableRegistry::getTableLocator()->get('DmiRoAllocationLogs');
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		// html encoding
		$ro_office = htmlentities($postData['ro_office'], ENT_QUOTES);
		$ro_office_address = htmlentities($postData['ro_office_address'], ENT_QUOTES);
		$ro_office_phone = htmlentities($postData['ro_office_phone'], ENT_QUOTES);
		
		//save array
		if ($record_id == null) {

			//to take email id & short code conditionally
			if ($postData['office_type'] == 'RO') {

				$ro_email_id = htmlentities($postData['ro_email_id'], ENT_QUOTES);
				$short_code = htmlentities($postData['short_code'], ENT_QUOTES);
				$ro_id_for_so = null;
				$replica_code = htmlentities($postData['replica_code'], ENT_QUOTES);

			//For RAL Office
			} elseif ($postData['office_type'] == 'RAL') {

				$ro_email_id = htmlentities($postData['ral_email_id'], ENT_QUOTES);
				$short_code = 'no-code';
				$ro_id_for_so=null;
				$replica_code = null;

			//for SO Office
			} elseif ($postData['office_type'] == 'SO') {

				$ro_id_for_so = $postData['ro_office_id'];
				//store SO email id in same column
				$ro_email_id = htmlentities($postData['so_email_id'], ENT_QUOTES);
				$short_code = htmlentities($postData['short_code'], ENT_QUOTES);
				$replica_code = htmlentities($postData['replica_code'], ENT_QUOTES);

			}

			$data_array = array('ro_office'=>$ro_office,
								'ro_office_address'=>$ro_office_address,
								'short_code'=>strtoupper($short_code),
								'ro_office_phone'=>$ro_office_phone,
								'ro_email_id'=>$ro_email_id, //same column used for RO/RAL/SO email id
								'user_email_id'=>$this->Session->read('username'),
								'created'=>date('Y-m-d H:i:s'),
								'office_type'=>$postData['office_type'],//flag for RO/RAL/SO
								'ro_id_for_so'=>$ro_id_for_so,//RO table id for SO office
								'replica_code'=>$replica_code
			);

		//edit array
		} elseif ($record_id != null) {

			/*[ To Take Email ID Conditionaly ]*/

			//For RO
			if ($postData['office_type']=='RO') {

				//value from DB as it is.
				$ro_email_id = base64_encode(htmlentities($postData['ro_email_id'], ENT_QUOTES));//for email encoding
				$ro_id_for_so = null;
				$replica_code = htmlentities($postData['replica_code'], ENT_QUOTES);


			//For RAL
			} elseif ($postData['office_type']=='RAL') {

				//value from form field
				$ro_email_id = htmlentities($postData['ral_email_id'], ENT_QUOTES);
				$ro_id_for_so = null;
				$replica_code = null;

			//For SO
			} elseif ($postData['office_type']=='SO') {

				//value from DB as it is. same coloumn used for RO/SO email id
				$ro_email_id = base64_encode(htmlentities($postData['ro_email_id'], ENT_QUOTES));//for email encoding
				//RO table id for SO office
				$ro_id_for_so = $postData['ro_office_id'];
				$replica_code = htmlentities($postData['replica_code'], ENT_QUOTES);

			}


			$data_array = array('id'=>$record_id,
								'ro_office'=>$ro_office,
								'ro_office_address'=>$ro_office_address,
								'ro_office_phone'=>$ro_office_phone,
								'ro_email_id'=>$ro_email_id,
								'user_email_id'=>$this->Session->read('username'),
								'modified'=>date('Y-m-d H:i:s'),
								'ro_id_for_so'=>$ro_id_for_so,//RO table id for SO office
								'office_type'=>$postData['office_type'], //added on 11-05-2021 to update office type RO/SO
								'replica_code'=>$replica_code	
			);
		}

		//to get reallocating user id.
		$get_user_id = $DmiUsers->find('all',array('fields'=>'id','conditions'=>array('email IS'=>$ro_email_id)))->first();

		$DmiRoOfficesEntity = $DmiRoOffices->newEntity($data_array);

		if ($DmiRoOffices->save($DmiRoOfficesEntity)) {

			//Save Entry in Ro allocation table to maintain the record of RO incharge allocation history
			$DmiRoAllocationLogsEntity = $DmiRoAllocationLogs->newEntity(array(
				'ro_incharge_id'=>$ro_email_id,
				'ro_office'=>$ro_office,
				'created'=>date('Y-m-d H:i:s'),
				'user_id'=>$get_user_id['id'],
			));

			if ($DmiRoAllocationLogs->save($DmiRoAllocationLogsEntity)) {

				return true;
			}
		}
	}



	//add/edit SMS/Email Templates Master
	public function addEditMessageTemplateMaster($postData,$record_id=null){

		$DmiSmsEmailTemplates = TableRegistry::getTableLocator()->get('DmiSmsEmailTemplates');

		// html encoding
		$sms_message = htmlentities($postData['sms_message'], ENT_QUOTES);
		$email_message = htmlentities($postData['email_message'], ENT_QUOTES);
		$email_subject = htmlentities($postData['email_subject'], ENT_QUOTES);
		$description = htmlentities($postData['description'], ENT_QUOTES);

		$i=0;
		//Current selected values from edit page for DMI

		$destination = array();

		if ($postData['applicant'] == 1) { $destination[$i] = 0; $i=$i+1; }

		if ($postData['mo_smo'] == 1) { $destination[$i] = 1; $i=$i+1; }

		if ($postData['io'] == 1) { $destination[$i] = 2; $i=$i+1; }

		if ($postData['ro_so'] == 1) { $destination[$i] = 3; $i=$i+1; }

		if ($postData['dy_ama'] == 1) { $destination[$i] = 4; $i=$i+1; }

		if ($postData['jt_ama'] == 1) { $destination[$i] = 5; $i=$i+1; }

		if ($postData['ho_mo_smo'] == 1) { $destination[$i] = 6; $i=$i+1; }

		if ($postData['ama'] == 1) { $destination[$i] = 7; $i=$i+1; }

		if ($postData['accounts'] == 1) { $destination[$i] = 8; $i=$i+1; }

		if ($postData['ro_incharge'] == 1) { $destination[$i] = 9; $i=$i+1; }

		if ($postData['chemist_user'] == 1) { $destination[$i] = 10; $i=$i+1; }


		//Current selected values from edit page for LMIS
		if ($postData['inward_officer'] == 1) { $destination[$i] = 101; $i=$i+1; }

		if ($postData['ral_cal_oic'] == 1) { $destination[$i] = 102; $i=$i+1; }

		if ($postData['chemist'] == 1) { $destination[$i] = 103; $i=$i+1; }

		if ($postData['chief_chemist'] == 1) { $destination[$i] = 104; $i=$i+1; }

		if ($postData['lab_incharge'] == 1) { $destination[$i] = 105; $i=$i+1; }

		if ($postData['dol'] == 1) { $destination[$i] = 106; $i=$i+1; }

		if ($postData['inward_clerk'] == 1) { $destination[$i] = 107; $i=$i+1; }

		if ($postData['outward_clerk'] == 1) { $destination[$i] = 108; $i=$i+1; }

		if ($postData['ro_so_officer'] == 1) { $destination[$i] = 109; $i=$i+1; }

		if ($postData['ro_so_oic'] == 1) { $destination[$i] = 110; $i=$i+1; }

		if ($postData['accounts'] == 1) { $destination[$i] = 111; $i=$i+1; }

		if ($postData['head_office'] == 1) { $destination[$i] = 112; $i=$i+1; }


		$destination_values = implode(',',$destination);

		//save array
		$data_array = array('sms_message'=>$sms_message,
							'email_message'=>$email_message,
							'email_subject'=>$email_subject,
							'destination'=>$destination_values,
							'user_email_id'=>$this->Session->read('username'),
							'user_once_no'=>$this->Session->read('once_card_no'),
							'status'=>'active',
							'description'=>$description,
							'template_for'=>$postData['template_for'],
							'created'=>date('Y-m-d H:i:s'));

		//edit array
		if ($record_id != null) {

			$data_array = array('id'=>$record_id,
								'sms_message'=>$sms_message,
								'email_message'=>$email_message,
								'email_subject'=>$email_subject,
								'destination'=>$destination_values,
								'user_email_id'=>$this->Session->read('username'),
								'user_once_no'=>$this->Session->read('once_card_no'),
								'description'=>$description,
								'template_for'=>$postData['template_for'],
								'modified'=>date('Y-m-d H:i:s'));
		}

		$DmiSmsEmailTemplatesEntity = $DmiSmsEmailTemplates->newEntity($data_array);

		if ($DmiSmsEmailTemplates->save($DmiSmsEmailTemplatesEntity)) {

			return true;
		}

	}



	//to Add/Set PAO/DDO
	public function addEditPaoMaster($postData,$record_id=null){

		$DmiPaoDetails = TableRegistry::getTableLocator()->get('DmiPaoDetails');
		$DmiDistricts = TableRegistry::getTableLocator()->get('DmiDistricts');

		$post_input_request = $postData['district_list'];

		if (!empty($post_input_request)) {

			$i=0;
			foreach ($post_input_request as $input_value) {
				$distric_id_list[$i] = $this->Controller->Customfunctions->dropdownSelectInputCheck('DmiDistricts',$input_value);//calling library function
				$i=$i+1;
			}

		} else {

			$distric_id_list = array();
		}

		$pao_alias_name = htmlentities($postData['pao_alias_name'], ENT_QUOTES);


		//for saving record
		if ($record_id == null) {

			//check dropdown
			$pao_user_id = $this->Controller->Customfunctions->dropdownSelectInputCheck('DmiUsers',$postData['pao_email_id']);

			$DmiPaoDetailsEntity = 	$DmiPaoDetails->newEntity(array(

				'pao_user_id'=>$pao_user_id,
				'pao_alias_name'=>$pao_alias_name,
				'user_email_id'=>$this->Session->read('username'),
				'user_once_no'=>$this->Session->read('once_card_no'),
				'created'=>date('Y-m-d H:i:s')

			));

			if ($DmiPaoDetails->save($DmiPaoDetailsEntity)) {

				$pao_id = $DmiPaoDetails->find('all',array('fields'=>'id','conditions'=>array('pao_user_id IS'=>$pao_user_id)))->first();

				$i=0;
				$dataValues = array();
				foreach ($distric_id_list as $district_id) {
					$dataValues[] =  array('id'=>$district_id,'pao_id'=>$pao_id['id'],'modified'=>date('Y-m-d H:i:s'));
				}

				//creating entities for array
				$DmiDistrictsEntity = $DmiDistricts->newEntities($dataValues);

				//saving data in loop
				foreach ($DmiDistrictsEntity as $each) {
					$DmiDistricts->save($each);
				}

				return true;
			}

		//for updating record
		} else {

			$selected_district_list = $DmiDistricts->find('list',array('valueField'=>'id','conditions'=>array('pao_id IS'=>$record_id),'order'=>'id'))->toArray();

			//find uncheck district list
			$uncheck_distric_id_list = array_diff($selected_district_list,$distric_id_list);

			$DmiPaoDetailsEntity = $DmiPaoDetails->newEntity(array(
				'id'=>$record_id,
				'pao_alias_name'=>$pao_alias_name,
				'modified'=>date('Y-m-d H:i:s')
			));

			if ($DmiPaoDetails->save($DmiPaoDetailsEntity)) {

				//Set 'null' value to uncheck district list
				if (!empty($uncheck_distric_id_list)) {

					foreach ($uncheck_distric_id_list as $district_id) {

						$dataValues[] = array('id'=>$district_id,
											  'pao_id'=>1,// Save Defualt PAO id
											  'modified'=>date('Y-m-d H:i:s'));
					}

					//creating entities for array
					$DmiDistrictsEntity = $DmiDistricts->newEntities($dataValues);

					//saving data in loop
					foreach ($DmiDistrictsEntity as $each) {
						$DmiDistricts->save($each);
					}

				}

				//if district not selected
				if (!empty($distric_id_list)) {

					foreach ($distric_id_list as $district_id) {

						$dataValues1[] = array('id'=>$district_id,
											   'pao_id'=>$record_id,
											   'modified'=>date('Y-m-d H:i:s'));
					}

					//creating entities for array
					$DmiDistrictsEntity1 = $DmiDistricts->newEntities($dataValues1);

					//saving data in loop
					foreach ($DmiDistrictsEntity1 as $each) {
						$DmiDistricts->save($each);
					}
				}

				return true;
			}
		}
	}


	//to add/edit Feedback Types
	public function addEditFeedbackTypeMaster($postData,$record_id=null) {

		$title = htmlentities($postData['title'], ENT_QUOTES);
		$masterTable = TableRegistry::getTableLocator()->get('DmiFeedbackTypes');

		//save array
		$data_array = array('title'=>$title,
							'user_email_id'=>$this->Session->read('username'),
							'created'=>date('Y-m-d H:i:s'));

		//edit array
		if ($record_id != null) {

			$data_array = array('id'=>$record_id,
								'title'=>$title,
								'user_email_id'=>$this->Session->read('username'),
								'modified'=>date('Y-m-d H:i:s'));
		}

		$masterTableEntity = $masterTable->newEntity($data_array);

		if ($masterTable->save($masterTableEntity)) {

			return true;
		}

	}


	//for Dashboard CMS
	//add/edit pages
	public function addEditCmsPages($postData,$record_id=null){

		//load Model
		$DmiPages = TableRegistry::getTableLocator()->get('DmiPages');

		// white listed tags
		$tags_white_list = array('strong','italic','p','a','img','s','ul','li','ol','h1','h2','h3','h4','h5','h6','h7',
							'div','pre','table','tr','td','th','thead','tbody','hr','blockquote','em','big',
							'/strong','/italic','/p','/a','/img','/s','/ul','/li','/ol','/h1','/h2','/h3','/h4','/h5','/h6','/h7',
							'/div','/pre','/table','/tr','/td','/th','/thead','/tbody','/hr','/blockquote','/em','/big');

		// check content tags are proper if not make it htmlencoded
		$page_content = $postData['content'];
		$total_character = strlen($page_content);
		$find_tag = preg_match('/</', $page_content, $matches);

		if (!empty($find_tag)) {

			$i=0;
			while ($i <= $total_character-1) {

				$tag_start_pos = strpos($page_content,'<',$i);

				if ($tag_start_pos === false) {

					$i = $total_character;

				} else {

					$i = $tag_start_pos+1;

					$char = $i;
					$tag_name = '';
					$tag_array = array();
					$tag_array_counter =0;

					while (!($page_content[$char] == '>' || $page_content[$char] == ' ')) {

						$tag_array[$tag_array_counter] = $page_content[$char];

						$tag_array_counter = $tag_array_counter+1;
						$char = $char + 1;
					}

					$tag_name = implode('',$tag_array);
					$x=0;
					$tag_match = '';

					//count of white list tags
					while ($x <= 54) {

						if ($tag_name == $tags_white_list[$x]) {

							$tag_match = 'yes';
							$x = 55;

						} else {

							$tag_match = 'no';
							$x = $x+1;

						}

					}

					if ($tag_match == 'no') {

						$full_tag = array();
						$y = $tag_start_pos;
						$k = 0;

						while ($y <= $char-1) {

							$full_tag[$k] = $page_content[$y];
							$y = $y+1;
							$k = $k+1;
						}

						$htmlencoded_tag = htmlentities((implode('',$full_tag)), ENT_QUOTES);

						$tag_length = strlen($htmlencoded_tag);

						$full_tag_string = implode('',$full_tag);

						$page_content = str_replace($full_tag_string,$htmlencoded_tag,$page_content);

						$i=$char;

						$total_character = strlen($page_content);

					} else {

						$i=$i+1;
					}

				}	// if position empty

			}

		}

		// html encoding
		$title = htmlentities($postData['title'], ENT_QUOTES);
		$publish_date = htmlentities($postData['publish_date'], ENT_QUOTES);
		$publish_date = $this->Controller->Customfunctions->dateFormatCheck($publish_date);
		$archive_date = htmlentities($postData['archive_date'], ENT_QUOTES);
		$archive_date = $this->Controller->Customfunctions->dateFormatCheck($archive_date);

		/*if ($this->dateComparison($publish_date,$archive_date)==false) {
			
			return false;
		}
*/
		$status = htmlentities($postData['status'], ENT_QUOTES);
		$meta_keyword = htmlentities($postData['meta_keyword'], ENT_QUOTES);
		$meta_description = htmlentities($postData['meta_description'], ENT_QUOTES);

		//set dataArray
		//for add
		if ($record_id == null) {

			$dataArray = array('title'=>$title,
							   'content'=>$page_content,
							   'user_email_id'=>$this->Session->read('username'),
							   'status'=>$status,
							   'publish_date'=>$publish_date,
							   'archive_date'=>$archive_date,
							   'meta_keyword'=>$meta_keyword,
							   'meta_description'=>$meta_description,
							   'created'=>date('Y-m-d H:i:s'));

		//for edit
		} else {

			$dataArray = array('id'=>$record_id,
							   'title'=>$title,
							   'content'=>$page_content,
							   'user_email_id'=>$this->Session->read('username'),
							   'status'=>$status,
							   'publish_date'=>$publish_date,
							   'archive_date'=>$archive_date,
							   'meta_keyword'=>$meta_keyword,
							   'meta_description'=>$meta_description,
							   'modified'=>date('Y-m-d H:i:s'));
		}

		$DmiPagesEntity = $DmiPages->newEntity($dataArray);

		if ($DmiPages->save($DmiPagesEntity)) {

				return true;
		}

	}


	// Function to check comparison between from date and To date
	public function dateComparison($from_date,$to_date) {

		$from_date = strtotime(str_replace('/','-',$from_date));
		$to_date  = strtotime(str_replace('/','-',$to_date));

		if ($from_date <= $to_date) {
			return true;
		} else {
			return false;
		}

	}


	//add/edit Menus
	public function addEditCmsMenus($postData,$record_id=null){

		//load Model
		$DmiMenus = TableRegistry::getTableLocator()->get('DmiMenus');

		// html encoding
		$title = htmlentities($postData['title'], ENT_QUOTES);
		$external_link = htmlentities($postData['external_link'], ENT_QUOTES);

		//checking radio buttons input
		$link_type = $this->Controller->Customfunctions->radioButtonInputCheck($postData['link_type']);
		$position = $this->Controller->Customfunctions->radioButtonInputCheck($postData['position']);

		//checking dropdown input
		//for page id
		if ($postData['link_type']=='page') {
			$link_id = $this->Controller->Customfunctions->dropdownSelectInputCheck('DmiPages',$postData['link_id']);
		} else {
			$link_id = null;
		}

		//checking number input
		$order = $this->Controller->Customfunctions->integerInputCheck($postData['order_id']);
		if ($order == 0) {
			return false;
		}

		//for add
		if ($record_id == null) {

			$dataArray = array('title'=>$title,
							    'external_link'=>$external_link,
							    'user_email_id'=>$this->Session->read('username'),
							    'link_type'=>$link_type,
							    'position'=>$position,
							    'link_id'=>$link_id,
							    'order_id'=>$order,
							    'created'=>date('Y-m-d H:i:s'));

		//for edit
		} else {

			$dataArray = array('id'=>$record_id,
							   'title'=>$title,
							   'external_link'=>$external_link,
							   'user_email_id'=>$this->Session->read('username'),
							   'link_type'=>$link_type,
							   'position'=>$position,
							   'link_id'=>$link_id,
							   'order_id'=>$order,
							   'modified'=>date('Y-m-d H:i:s'));
		}


		$DmiMenusEntity = $DmiMenus->newEntity($dataArray);

		if ($DmiMenus->save($DmiMenusEntity)) {

			return true;
		}

	}


	//For Replica Module - Below function is added for Listing all replica Charges, added on 24-08-2021 By Akash.
	public function addEditReplicaChargesMaster($postData,$record_id=null) {

		$username = $this->Session->read('username');
		//Load Model 
		$DmiReplicaChargesDetails = TableRegistry::getTableLocator()->get('DmiReplicaChargesDetails');
		// html encoding
		$htmlencoded_charges = htmlentities($postData['replica_charges'], ENT_QUOTES);
		$htmlencoded_quantity = htmlentities($postData['minimum_quantity'], ENT_QUOTES);
		$htmlencoded_replica_code = htmlentities($postData['replica_code'], ENT_QUOTES);
		
		//for add
		if ($record_id == null) {
			
			$selected_commodity = $postData['sub_commodity'];
			$selected_category = $postData['commodity'];
			$selected_unit = htmlentities($postData['unit'], ENT_QUOTES);

			if ($this->Controller->Customfunctions->checkIfCommodityReplicaIsExists($selected_commodity) == true) {

					$dataArray = array('category_code'=>$selected_category,
									   'commodity_code'=>$selected_commodity,
									   'min_qty'=>$htmlencoded_quantity,
									   'charges'=>$htmlencoded_charges,
									   'unit'=>$selected_unit,
									   'by_user'=>$username,
									   'replica_code'=>strtoupper($htmlencoded_replica_code),
									   'created'=>date('Y-m-d H:i:s'),
									   'modified'=>date('Y-m-d H:i:s'));
			}

		//for edit
		} else {
			$dataArray = array('id'=>$record_id,
							   'charges'=>$htmlencoded_charges,
							   'by_user'=>$username,
							   'modified'=>date('Y-m-d H:i:s'),
							   'min_qty'=>$htmlencoded_quantity,
							   'replica_code'=>strtoupper($htmlencoded_replica_code));

		}

		if (!empty($dataArray)) {
			//Save The Data
			$DmiReplicaChargesDetailsEntity = $DmiReplicaChargesDetails->newEntity($dataArray);

			if ($DmiReplicaChargesDetails->save($DmiReplicaChargesDetailsEntity)) {

				return true;
			}
		}
		
	}


	//For Adding and Editing the Education Type Master
	public function addEditEducationTypeMaster($postData,$record_id=null){

		$username = $this->Session->read('username');
		$DmiEducationTypes = TableRegistry::getTableLocator()->get('DmiEducationTypes');
		//html encoding
		$htmlencoded_education_type = htmlentities($postData['education_type'], ENT_QUOTES);

		//edit array
		if ($record_id == null) {

			$data_array = array('edu_type'=>$htmlencoded_education_type,
								'delete_status'=>'no',
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s'),
								'by_user'=>$username);
		} else {

			$data_array = array('id'=>$record_id,
								'edu_type'=>$htmlencoded_education_type,
								'delete_status'=>'no',
								'modified'=>date('Y-m-d H:i:s'),
								'by_user'=>$username);

		}

		$DmiEducationTypesEntity = $DmiEducationTypes->newEntity($data_array);

		if ($DmiEducationTypes->save($DmiEducationTypesEntity)) {

			return true;
		}

	}


	//For Adding and Editing the Division Type Master
	public function addEditDivisionGradeMaster($postData,$record_id=null){

		$username = $this->Session->read('username');
		$DmiDivisionGrades = TableRegistry::getTableLocator()->get('DmiDivisionGrades');
		//html encoding
		$htmlencoded_division_type = htmlentities($postData['division_type'], ENT_QUOTES);

		//edit array
		if ($record_id == null) {

			$data_array = array('division'=>$htmlencoded_division_type,
								'delete_status'=>'no',
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s'),
								'by_user'=>$username);
		} else {

			$data_array = array('id'=>$record_id,
								'division'=>$htmlencoded_division_type,
								'delete_status'=>'no',
								'modified'=>date('Y-m-d H:i:s'),
								'by_user'=>$username);
		}

		$DmiDivisionGradesEntity = $DmiDivisionGrades->newEntity($data_array);

		if ($DmiDivisionGrades->save($DmiDivisionGradesEntity)) {

			return true;
		}

	}


	//For Adding and Editing the Documents Type Master # Added on the 09-08-2022 By Akash
	public function addEditDocumentsMaster($postData,$record_id=null){

		$username = $this->Session->read('username');
		$DmiDocumentLists = TableRegistry::getTableLocator()->get('DmiDocumentLists');
		//html encoding
		$htmlencoded_value = htmlentities($postData['document_name'], ENT_QUOTES);

		//edit array
		if ($record_id == null) {

			$data_array = array('document_name'=>$htmlencoded_value,
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s'),
								'by_user'=>$username);
		} else {

			$data_array = array('id'=>$record_id,
								'document_name'=>$htmlencoded_value,
								'modified'=>date('Y-m-d H:i:s'),
								'by_user'=>$username);
		}

		$entity = $DmiDocumentLists->newEntity($data_array);

		if ($DmiDocumentLists->save($entity)) {

			return true;
		}

	}

}


?>
