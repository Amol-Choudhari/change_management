<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;

class DashboardController extends AppController{

		var $name = 'Dashboard';
		//to initialize our custom requirements
		public function initialize(): void
		{
			parent::initialize();

				$this->loadComponent('Commonlistingfunctions');
				$this->loadComponent('Customfunctions');
				$this->loadComponent('Randomfunctions');

				$this->viewBuilder()->setHelpers(['Form','Html','Time']);

			$this->viewBuilder()->setLayout('admin_dashboard');
			$this->Session = $this->getRequest()->getSession();
		}

// Admin user methods start

		public function beforeFilter($event) {
		parent::beforeFilter($event);

			//$username = $this->Session->read('username');
			$username = $this->getRequest()->getSession()->read('username');

			if($username == null){
				$this->customAlertPage("Sorry You are not authorized to view this page..");
				exit();
			}
			else{
				$this->loadModel('DmiUsers');
				//check if user entry in Dmi_users table for valid user
				$check_user = $this->DmiUsers->find('all',array('conditions'=>array('email'=>$this->Session->read('username'))))->first();

				if(empty($check_user)){
					$this->customAlertPage("Sorry You are not authorized to view this page..");
					exit();
				}
			}
		}

//phase 2 new code from here

		public function home(){

				$this->viewBuilder()->setLayout('admin_dashboard');
				$this->loadComponent('Dashboardcharts');
				$this->loadComponent('Commoncountsfunctions');
				
				$this->loadModel('DmiUsers');
				$this->loadModel('DmiUserRoles');
				$this->loadModel('DmiFirms');

				$username = $this->getRequest()->getSession()->read('username');
				$this->set('username',$username);

				$check_user_role = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$username)))->first();
				$this->set('check_user_role',$check_user_role);

				//when 'Scrutiny tab clicked', this will be for MO/SMO user dashboard
				if($this->request->getData('scrutiny') != null)
				{
					$this->Session->write('current_level','level_1');
					//set which count boxes should not be visible
					$status_title = 'Status of Applications Scrutiny';

				}

				//when 'Inspection tab clicked', this will be for IO user dashboard
				elseif($this->request->getData('inspection') != null)
				{
					$this->Session->write('current_level','level_2');
					$status_title = 'Status of Site Inspection Reports';

				}

				//when 'Regional Office tab clicked', this will be for RO user dashboard
				elseif($this->request->getData('regional_office') != null)
				{
					$this->Session->write('current_level','level_3');
					$this->Session->write('level_3_for','RO');
					$status_title = 'Status of Applications in Regional Office';
				}

				//when 'Sub Office tab clicked', this will be for SO user dashboard
				elseif($this->request->getData('sub_office') != null)
				{
					$this->Session->write('current_level','level_3');
					$this->Session->write('level_3_for','SO');
					$status_title = 'Status of Applications in Sub Office';
				}

				//when 'HO Quality Control tab clicked', this will be for HO user dashboard
				elseif($this->request->getData('ho_quality_control') != null)
				{
					$this->Session->write('current_level','level_4');
					$status_title = 'Status of Applications in HO Quality Control';
				}

				//when 'PAO/DDO Office', this will be for PAO/DDO user dashboard
				elseif($this->request->getData('pao_ddo_office') != null)
				{
					$this->Session->write('current_level','pao');
					$status_title = 'Status of Applications Payments';

				}
				elseif($this->request->getData('get_pending_work') != null)
				{
					$main_count_array = array();
					$status_title = null;
					$this->Session->write('current_level','pending_status');
					//to show current user pending work statistic in popup window on home page
					$main_count_array = $this->dashboardpendingWorkCount();
					$this->set('main_count_array',$main_count_array);

				}else{

					$count_array = array();				

					$type = 1;

					if(!empty($check_user_role))//this condition added on 30-03-2017 by Amol(if user roles empty, no dashboard graphs)
					{
						$this->Dashboardcharts->lineChartGraph($username,$type); //call to custome function for line chart graph

						$this->Dashboardcharts->pieChartData($username); //call to custome function for pie chart data

					}

					//status tile variable to show on listing table top
					$status_title = null;

					//set current level to null as default
					$this->Session->write('current_level',null);
					$this->Session->write('level_3_for',null);

					$this->set('count_array',$count_array);

					//get flow wise tables
					$this->loadModel('DmiFlowWiseTablesLists');
					$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();

					//dates between to fetch records
					$from_date = date("Y-m-d H:i:s",strtotime("-6 month"));
					$to_date = date('Y-m-d H:i:s');//str_replace('/','-',$to_date);


					$i=0;
					$dashboard_comments = array();
					foreach($flow_wise_tables as $each_flow){

						$mo_ro_comments_tb = $each_flow['commenting_with_mo'];
						$so_ro_comments_tb = $each_flow['ro_so_comments'];
						$ho_comments_tb = $each_flow['ho_comment_reply'];

						$tables_array = array($mo_ro_comments_tb,$so_ro_comments_tb,$ho_comments_tb);

						//get comments from all tables to current user between specified date
						foreach($tables_array as $each_table){

							//load model
							$this->loadModel($each_table);
							//get records
							$get_comments = $this->$each_table->find('all',array('conditions'=>array('comment_to'=>$username,'and'=>array('date(created) >=' => $from_date, 'date(created) <=' =>$to_date)),'order'=>'id desc'))->toArray();

							foreach($get_comments as $each_comment){

								//get commenting user name
								$user_details = $this->DmiUsers->find('all', array('fields'=>array('f_name','l_name','profile_pic'), 'conditions' => array('email IS'=>$each_comment['comment_by'])))->first();
								$dashboard_comments[$i]['username'] = $user_details['f_name'].' '.$user_details['l_name'];

								$dashboard_comments[$i]['date'] = $each_comment['created'];
								$dashboard_comments[$i]['profile_pic'] = $user_details['profile_pic'];
								$dashboard_comments[$i]['customer_id'] = $each_comment['customer_id'];
								$dashboard_comments[$i]['comment'] = $each_comment['comment'];

							$i=$i+1;
							}

						}

					}

					//now get applicant side comments, so need to take each application table in the list
					$tables_array = array('DmiCustomerFirmProfiles','DmiCustomerPremisesProfiles','DmiCustomerMachineryProfiles','DmiCustomerPackingDetails','DmiCustomerLaboratoryDetails','DmiCustomerTblDetails',
					'DmiPrintingFirmProfiles','DmiPrintingPremisesProfiles','DmiPrintingUnitDetails',
					'DmiLaboratoryFirmDetails','DmiLaboratoryOtherDetails');

					//get comments from all tables to current user between specified date
					foreach($tables_array as $each_table){

						//load model
						$this->loadModel($each_table);
						//get records
						$get_reply = $this->$each_table->find('all',array('conditions'=>array('reffered_back_comment IS NOT NULL','customer_reply IS NOT NULL','user_email_id IS'=>$username,'and'=>array('date(created) >=' => $from_date, 'date(created) <=' =>$to_date)),'order'=>'id desc'))->toArray();

						foreach($get_reply as $each_reply){

							//check no records after this comment
							$checklast = $this->$each_table->find('all',array('conditions'=>array('id >'=>$each_reply['id'])))->first();

							if(empty($checklast)){

								$customer_id = $each_reply['customer_id'];
								//get commenting Firm details
								$firm_details = $this->DmiFirms->find('all', array('fields'=>array('firm_name','profile_pic'), 'conditions' => array('customer_id IS'=>$customer_id)))->first();
								$dashboard_comments[$i]['username'] = $firm_details['firm_name'];

								$dashboard_comments[$i]['date'] = $each_reply['created'];
								$dashboard_comments[$i]['profile_pic'] = $firm_details['profile_pic'];
								$dashboard_comments[$i]['customer_id'] = $customer_id;
								$dashboard_comments[$i]['comment'] = $each_reply['customer_reply'];

								$i=$i+1;
							}
						}

					}

					//$dashboard_comments = usort($dashboard_comments, 'date');

					$this->set('dashboard_comments',$dashboard_comments);

				}
				//to set variables commomly for all button
				$this->set('status_title',$status_title);



		}



	//below are ajax called function on count box clicked.

		//common code for main status tabs onclick calls
		public function commonMainTabscall(){

			$current_level = $this->Session->read('current_level');
			$show_list_for = $this->Session->read('show_list_for');

			if($current_level == 'level_3' && $show_list_for != 'rejected'){
				$this->render('/element/common_counts_and_list_elements/ro_so_common_elements/ro_so_common_dashboard_tabs');
			}elseif($current_level == 'level_4'){
				$this->render('/element/common_counts_and_list_elements/ho_common_elements/ho_common_dashboard_tabs');
			}elseif($current_level == 'level_1'){
				//for scrutiny officer
				$this->render('/element/common_counts_and_list_elements/scrutiny_common_elements/scrutiny_common_dashboard_tabs');
			}else{
				//create dynamic arrays of values to show list of applications in tables
				//also with conditional links for actions for each row of application
				$appl_list_array = $this->Commonlistingfunctions->fetchRecords($current_level,$show_list_for);
				$this->set('appl_list_array',$appl_list_array);
				$this->render('/element/common_counts_and_list_elements/common_app_list_element');
			}
			
		}

		public function pendingApplications(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$this->Session->write('show_list_for','pending');
			$this->commonMainTabscall();
			
		}

		public function reportsFiled(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$this->Session->write('show_list_for','reports_filed');
			$this->commonMainTabscall();
		}

		public function refBackApplications(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$this->Session->write('show_list_for','ref_back');
			$this->commonMainTabscall();
			
		}

		public function repliedApplications(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$this->Session->write('show_list_for','replied');
			$this->commonMainTabscall();
		}

		public function approvedApplications(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$this->Session->write('show_list_for','approved');
			$this->commonMainTabscall();
		}

		public function rejectedApplications(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$this->Session->write('show_list_for','rejected');
			$this->commonMainTabscall();
		}


	/*	public function allApplications(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call

			$this->Session->write('show_list_for','all');

			$current_level = $this->Session->read('current_level');
			if($current_level == 'level_3' || $current_level == 'level_4'){
				$this->render('/element/common_counts_and_list_elements/ro_so_common_elements/ro_so_common_dashboard_tabs');

			}else{

				$appl_list_array = $this->fetchRecords($current_level,'all');
				$this->set('appl_list_array',$appl_list_array);

				$this->render('/element/common_counts_and_list_elements/all_app_list_element');
			}

		}
	*/




	//below are ajax called functions for level 3 tabs

		//common code for Level 3 sub tabs onclick calls
		public function commonLevel3SubTabscall($sub_tab){

			$show_list_for = $this->Session->read('show_list_for');
			$current_level = $this->Session->read('current_level');
			$appl_list_array = $this->Commonlistingfunctions->fetchRecords($current_level,$show_list_for,$sub_tab);
			$this->set('appl_list_array',$appl_list_array);

			if($show_list_for != 'all'){
				$this->render('/element/common_counts_and_list_elements/common_app_list_element');
			}else{
				$this->render('/element/common_counts_and_list_elements/all_app_list_element');
			}
		}

		public function withApplicantTab(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'with_applicant';
			//set session variable to hide & show progress bar to RO
			$this->Session->write('ro_with','applicant');
			$this->Session->write('for_sub_tab',$sub_tab);
			$this->commonLevel3SubTabscall($sub_tab);
		}

		public function scrutinyTab(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			//set session variable to hide & show progress bar to RO
			$this->Session->write('ro_with','mo');
			$sub_tab = 'scrutiny';
			$this->Session->write('for_sub_tab',$sub_tab);
			$this->commonLevel3SubTabscall($sub_tab);
		}

		public function reportsTab(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'reports';
			$this->Session->write('for_sub_tab',$sub_tab);
			$this->commonLevel3SubTabscall($sub_tab);
		}

		public function withSubOffsTab(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'with_sub_office';
			$this->Session->write('for_sub_tab',$sub_tab);
			$this->commonLevel3SubTabscall($sub_tab);
		}

		public function withRegOffsTab(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'with_reg_office';
			$this->Session->write('for_sub_tab',$sub_tab);
			$this->commonLevel3SubTabscall($sub_tab);
		}

		public function withHoOffsTab(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'with_ho_office';
			$this->Session->write('for_sub_tab',$sub_tab);
			$this->commonLevel3SubTabscall($sub_tab);
		}
	//till here for level3 tabs for nodal officer


	//below ajax functions are for scrutiny officer tabs

		//common code for scrutiny sub tabs onclick calls
		public function commonScrutinySubTabscall($sub_tab){

			$show_list_for = $this->Session->read('show_list_for');
			$current_level = $this->Session->read('current_level');
			$appl_list_array = $this->Commonlistingfunctions->fetchRecords($current_level,$show_list_for,$sub_tab);
			$this->set('appl_list_array',$appl_list_array);

			if($show_list_for != 'all'){
				$this->render('/element/common_counts_and_list_elements/common_app_list_element');
			}else{
				$this->render('/element/common_counts_and_list_elements/all_app_list_element');
			}
		}

		public function scrutinyWithNodalOfficeTab(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'scrutiny_with_nodal_office';
			$this->Session->write('for_sub_tab',$sub_tab);
			$this->commonScrutinySubTabscall($sub_tab);
		}


		public function scrutinyWithRegOfficeTab(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'scrutiny_with_reg_office';
			$this->Session->write('for_sub_tab',$sub_tab);
			$this->commonScrutinySubTabscall($sub_tab);
		}


		public function scrutinyWithHoOfficeTab(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'scrutiny_with_ho_office';
			$this->Session->write('for_sub_tab',$sub_tab);
			$this->commonScrutinySubTabscall($sub_tab);
		}

	//till here for scrutiny officers tabs


	//for HO level listing
		public function fetchHoLevelLists(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call

			$list_for = $_POST['list_for'];
			if($list_for == 'ho_scrutiny'){
				$sub_tab = 'for_ho_scrutiny';
			}elseif($list_for == 'dy_ama'){
				$sub_tab = 'for_dy_ama';
			}elseif($list_for == 'jt_ama'){
				$sub_tab = 'for_jt_ama';
			}elseif($list_for == 'ama'){
				$sub_tab = 'for_ama';
			}

			$this->Session->write('for_sub_tab',$sub_tab);

			$show_list_for = $this->Session->read('show_list_for');

			$current_level = $this->Session->read('current_level');
			$appl_list_array = $this->Commonlistingfunctions->fetchRecords($current_level,$show_list_for,$sub_tab);
			$this->set('appl_list_array',$appl_list_array);

			$this->render('/element/common_counts_and_list_elements/common_app_list_element');


		}

	//till here



//allocations starts

		public function allocationsMainTab(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call

			$this->Session->write('show_list_for','allocation');
			$current_level = $this->Session->read('current_level');

			$this->render('/element/common_counts_and_list_elements/allocation_common_elements/allocation_common_dashboard_tabs');
		}

		//common allocations sub tabs onclick calls
		public function commonAllocationsSubTabsCall($sub_tab){

			$show_list_for = $this->Session->read('show_list_for');
			$current_level = $this->Session->read('current_level');
			$appl_list_array = $this->fetchAllocationRecords($current_level,$sub_tab);
			$this->set('appl_list_array',$appl_list_array);
			$this->render('/element/common_counts_and_list_elements/common_app_list_element');

		}

		public function allocationForScrutinyTab(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'scrutiny_allocation_tab';
			//only for allocation by level 4 Ro listing
			$this->Session->write('allocation_by',null);
			$this->commonAllocationsSubTabsCall($sub_tab);

		}


		public function allocationForInspectionTab(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'inspection_allocation_tab';
			//only for allocation by level 4 Ro listing
			$this->Session->write('allocation_by',null);
			$this->commonAllocationsSubTabsCall($sub_tab);

		}

		public function allocationForScrutinyByLevel4RoTab(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
			$sub_tab = 'scrutiny_allocation_by_level4ro_tab';
			//only for allocation by level 4 Ro listing
			$this->Session->write('allocation_by','level_4_ro');
			$this->commonAllocationsSubTabsCall($sub_tab);

		}


		public function openScrutinyAllocationPopup(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call

			$appl_type = $_POST['appl_type'];
			$comm_with = $_POST['comm_with'];
			$get_customer_id = explode('-',(string) $_POST['customer_id']); #For Deprecations
			$customer_id = $get_customer_id[0];

			$form_type = $get_customer_id[1];
			$status = null;

			$current_level = $this->Session->read('current_level');
			if($current_level=='level_4'){
				$mo_field_name = 'ho_mo_smo';
			}else{
				$mo_field_name = 'mo_smo_inspection';
			}

			$this->loadModel('DmiUserRoles');
			$mo_users_list = $this->DmiUserRoles->find('list',array('keyField'=>'user_email_id','valueField'=>'user_email_id','conditions'=>array($mo_field_name=>'yes')))->toArray();

			//function to get first & last name wise list
			$mo_users_list = $this->userNameList($mo_users_list);

			$this->set(compact('appl_type','customer_id','form_type','mo_users_list','comm_with'));
			$this->render('/element/common_counts_and_list_elements/allocation_popup_models/scrutiny_allocation_popup');
		}


		public function openInspectionAllocationPopup(){
			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call

			$appl_type = $_POST['appl_type'];
			$comm_with = $_POST['comm_with'];
			$get_customer_id = explode('-',(string) $_POST['customer_id']); #For Deprecations
			$customer_id = $get_customer_id[0];
		
			$form_type = $get_customer_id[1];
			$status = null;

			$this->loadModel('DmiUserRoles');
			$this->loadModel('DmiRoOffices');
			$username = $this->Session->read('username');

			$find_ro_id = $this->DmiRoOffices->find('list',array('valueField'=>'id','conditions'=>array('ro_email_id IS'=>$username)))->toList();

			$io_users_list = array();
			if(!empty($find_ro_id))
			{
				$ro_id = $find_ro_id;

				$find_user_belongs = $this->DmiUsers->find('list',array('keyField'=>'id', 'valueField'=>'email','conditions'=>array('posted_ro_office IN'=>$ro_id,'status'=>'active')))->toList();

				$io_users_list = $this->DmiUserRoles->find('list',array('keyField'=>'user_email_id','valueField'=>'user_email_id','conditions'=>array('user_email_id IN'=>$find_user_belongs,'io_inspection'=>'yes')))->toArray();

			}

			//for printing applications show IO all around the offices with role 'inspection_pp'
			//condition updated on 16-11-2021 for PP appl, only inspected by the user with special role.
			if($form_type=='B'){

				$io_users_list = $this->DmiUserRoles->find('list',array('keyField'=>'user_email_id','valueField'=>'user_email_id','conditions'=>array('inspection_pp'=>'yes')))->toArray();
			}

			//for other types of applications 15 digit, ecode etc
			//added new query and condition on 16-11-2021
			$this->loadModel('DmiApplicationTypes');
			$appl_type_id = $this->DmiApplicationTypes->find('all',array('conditions'=>array('LOWER(application_type) IS'=>strtolower($appl_type))))->first();
			if($appl_type_id['id']==5 || $appl_type_id['id']==6){
				//show only RO in-charge user id for site inspection allocation drop down
				$RoEmailId = $this->Customfunctions->getApplRegOfficeId($customer_id,$appl_type_id['id']);
				$io_users_list = array($RoEmailId=>$RoEmailId);
			}


			//function to get first & last name wise list
			$io_users_list = $this->userNameList($io_users_list);

			$this->set(compact('appl_type','customer_id','form_type','io_users_list','comm_with'));
			$this->render('/element/common_counts_and_list_elements/allocation_popup_models/inspection_allocation_popup');
		}



		public function fetchAllocationRecords($for_level,$sub_tab){

			$conn = ConnectionManager::get('default');

			$username = $this->Session->read('username');
			$this->loadModel('DmiFirms');
			$this->loadComponent('Customfunctions');

			$alloc_window = 'yes';
			$this->set('alloc_window',$alloc_window);

				//get flow wise tables
				$this->loadModel('DmiFlowWiseTablesLists');
				$applTypeArray = $this->Session->read('applTypeArray');
				unset($applTypeArray['1']);//index 1, Now Renewal application will not list except DDO dashboard, any where in list. on 20-10-2022
				unset($applTypeArray['3']);//chemist flow type id is removed, as no need for allocation (scrutiny/inspection), on 28-04-2022
				$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$applTypeArray),'order'=>'id ASC'))->toArray();

				$i=0;
				$appl_list_array = array();
			//first loop for each application/flow type
				foreach($flow_wise_tables as $each_flow){

					//get flow/application type
					$this->loadModel('DmiApplicationTypes');
					$get_appl_type = $this->DmiApplicationTypes->find('all',array('conditions'=>array('id IS'=>$each_flow['application_type'])))->first();
					$appl_type = $get_appl_type['application_type'];

					$allocation_table = $each_flow['allocation'];
					$allocationTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$allocation_table))));
					$this->loadModel($allocation_table);

					$ho_allocation_table = $each_flow['ho_level_allocation'];
					$this->loadModel($ho_allocation_table);

					$final_submit_table = $each_flow['application_form'];
					$this->loadModel($final_submit_table);

					$final_report_table = $each_flow['inspection_report'];
					$this->loadModel($final_report_table);

					$ro_so_comments_table = $each_flow['ro_so_comments'];
					$this->loadModel($ro_so_comments_table);

					$ho_comments_table = $each_flow['ho_comment_reply'];
					$this->loadModel($ho_comments_table);

					$appl_current_pos_table = $each_flow['appl_current_pos'];
					$this->loadModel($appl_current_pos_table);

					if($for_level=='level_3'){

						if($sub_tab=='scrutiny_allocation_tab'){

							$stmt = $conn->execute("select al.* from $allocationTable as al
								inner join (
										select max(id) id, customer_id
										from $allocationTable
										group by customer_id
								) as maxall on maxall.customer_id = al.customer_id and maxall.id = al.id
								where al.level_3='$username'");
							$get_allocations = $stmt ->fetchAll('assoc');
							foreach($get_allocations as $each_alloc){

								$creat_array='';//clear variable each time
								$customer_id = $each_alloc['customer_id'];

								$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
								//get_form_type
								$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
								//get firm details
								$firm_details = $this->DmiFirms->firmDetails($customer_id);
								$firm_name = $firm_details['firm_name'];
								$firm_table_id = $firm_details['id'];
								$appl_type_id = $each_flow['application_type'];
								$appl_view_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;

								//get Nodal officer details
								$this->loadModel('DmiUsers');
								$mo_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$each_alloc['level_1'])))->first();
								if(empty(!$mo_user_details)){
									$comm_with = $mo_user_details['f_name'].' '.$mo_user_details['l_name'];
								}else{
									$comm_with='Not Allocated';
								}

								//check final submit status for level 1,2&3 and approved for each allocated id
								$approved_status = $this->$final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'status'=>'approved','OR'=>array('current_level IN'=>array('level_1','level_2','level_3')),$grantDateCondition)))->first();
								//get if application is old
								$is_appl_old = $this->Customfunctions->isOldApplication($customer_id);
								//get appl current position user
								$current_pos = $this->Customfunctions->getApplCurrentPos($appl_current_pos_table,$customer_id);

								if(empty($approved_status) && !($is_appl_old == 'yes' && $appl_type_id=='1') && !empty($current_pos)){

									//commented below condition to show allocated appls also in allocation window, on 10-08-2022
									//if($current_pos['current_level']=='level_3' && $current_pos['current_user_email_id']==$username){

										//application must not be with applicant while allocation
										//added on 03-02-2023 by Amol
										$finalSubmitStatus = $this->$final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
										if($finalSubmitStatus['status'] != 'referred_back'){
											$creat_array = true;
										}
										
									//}

								}

								//creating array to list records with respect to above conditions
								if($creat_array==true){

									$appl_list_array[$i]['appl_type'] = $appl_type;
									$appl_list_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
									$appl_list_array[$i]['firm_name'] = $firm_name;
									$appl_list_array[$i]['comm_with'] = $comm_with;
									$appl_list_array[$i]['appl_view_link'] = $appl_view_link;
									$appl_list_array[$i]['appl_edit_link'] = '';
									$appl_list_array[$i]['alloc_sub_tab']='scrutiny_allocation_tab';

								}

							$i=$i+1;
							}
						}
						elseif($sub_tab=='inspection_allocation_tab'){

							//$get_allocations = $this->$allocation_table->find('all',array('conditions' => array('level_3'=>$username)))->toArray();

							$stmt = $conn->execute("select al.* from $allocationTable as al
								inner join (
										select max(id) id, customer_id
										from $allocationTable
										group by customer_id
								) as maxall on maxall.customer_id = al.customer_id and maxall.id = al.id
								where al.level_3='$username'");
							$get_allocations = $stmt ->fetchAll('assoc');

							foreach($get_allocations as $each_alloc){

								$creat_array='';//clear variable each time
								$customer_id = $each_alloc['customer_id'];

								$inspection = 'yes'; //by default

								//check site inspection exist or not for Change flow, else move forward
								//on 12-05-2021 by Amol
								if($each_flow['application_type']=='3'){
									$inspection = $this->Customfunctions->inspRequiredForChangeApp($customer_id,$each_flow['application_type']);
								
								}elseif($each_flow['application_type']=='1' || $each_flow['application_type']=='2'){

									//as per new order by 01-04-2021 from DMI
									//if lab is NABL accreditated then ther will be no site inspection
									//applied on 01-10-2021 by Amol
									$NablDate = $this->Randomfunctions->checkIfLabNablAccreditated($customer_id);
									if($NablDate != null){
										$inspection = 'no';
									}
								}

								

								if($inspection == 'yes'){

									
									//get_form_type
									$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
									//get firm details
									$firm_details = $this->DmiFirms->firmDetails($customer_id);
									$firm_name = $firm_details['firm_name'];
									$firm_table_id = $firm_details['id'];
									$appl_type_id = $each_flow['application_type'];
									$appl_view_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;
									$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id,$appl_type_id);//added new parameter in call "$appl_type_id" on 20-03-2023

									//get Nodal officer details
									$this->loadModel('DmiUsers');
									$mo_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$each_alloc['level_2'])))->first();
									if(empty(!$mo_user_details)){
										$comm_with = $mo_user_details['f_name'].' '.$mo_user_details['l_name'];
									}else{
										$comm_with='Not Allocated';
									}

									//check final submit status for level 2 & 3 and approved for each allocated id
									$approved_status = $this->$final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'status'=>'approved','OR'=>array('current_level IN'=>array('level_2','level_3')))))->first();
									//check scrutiny status
									$scrutiny_status = $this->$final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'status'=>'approved','current_level'=>'level_1')))->first();
									//get if application is old
									$is_appl_old = $this->Customfunctions->isOldApplication($customer_id);
									//get appl current position user
									$current_pos = $this->Customfunctions->getApplCurrentPos($appl_current_pos_table,$customer_id);

									if(!empty($scrutiny_status)){

										if(empty($approved_status) && !($is_appl_old == 'yes' && $appl_type_id=='1' && !empty($current_pos))){

											if(!($form_type == 'C' && ($appl_type_id=='1' || $appl_type_id=='2'))){//don't list lab export appl. with flow new & renewal
												
												//commented below condition to show allocated appls also in allocation window, on 10-08-2022
												//if($current_pos['current_level']=='level_3' && $current_pos['current_user_email_id']==$username){

												//application must not be with applicant while allocation
													//added on 03-02-2023 by Amol
													$finalSubmitStatus = $this->$final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
													if($finalSubmitStatus['status'] != 'referred_back'){
														$creat_array = true;
													}

												//}

											}
										}
									}

									//creating array to list records with respect to above conditions
									if($creat_array==true){

										$appl_list_array[$i]['appl_type'] = $appl_type;
										$appl_list_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
										$appl_list_array[$i]['firm_name'] = $firm_name;
										$appl_list_array[$i]['comm_with'] = $comm_with;
										$appl_list_array[$i]['appl_view_link'] = $appl_view_link;
										$appl_list_array[$i]['appl_edit_link'] = '';
										$appl_list_array[$i]['alloc_sub_tab']='inspection_allocation_tab';

									}

									$i=$i+1;
								}
							}
						}
						elseif($sub_tab=='scrutiny_allocation_by_level4ro_tab'){

							$get_allocations = $this->$allocation_table->find('all',array('conditions' => array('level_4_ro IS'=>$username)))->toArray();

							foreach($get_allocations as $each_alloc){

								$creat_array='';//clear variable each time
								$customer_id = $each_alloc['customer_id'];

								$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

								//get_form_type
								$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
								//get appl current position user
								$current_pos = $this->Customfunctions->getApplCurrentPos($appl_current_pos_table,$customer_id);
								//get firm details
								$firm_details = $this->DmiFirms->firmDetails($customer_id);
								$firm_name = $firm_details['firm_name'];
								$firm_table_id = $firm_details['id'];
								$appl_type_id = $each_flow['application_type'];
								$appl_view_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;

								//get Nodal officer details
								$this->loadModel('DmiUsers');
								$mo_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$each_alloc['level_4_mo'])))->first();
								if(empty(!$mo_user_details)){
									$comm_with = $mo_user_details['f_name'].' '.$mo_user_details['l_name'];
								}else{
									$comm_with='Not Allocated';
								}

								//check reports final submitted
								$reports_submitted_status = $this->$final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'status'=>'approved','current_level'=>'level_2')))->first();
								//check final submit status for level 3 & 4 and approved for each allocated id
								$approved_status = $this->$final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'status'=>'approved','OR'=>array('current_level IN'=>array('level_3','level_4')))))->first();

								if(!empty($reports_submitted_status) && empty($approved_status)){

									//commented below condition to show allocated appls also in allocation window, on 10-08-2022
									//if($current_pos['current_level']=='level_4_ro' && $current_pos['current_user_email_id']==$username){

										//application must not be with applicant while allocation
										//added on 03-02-2023 by Amol
										$finalSubmitStatus = $this->$final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
										if($finalSubmitStatus['status'] != 'referred_back'){
											$creat_array = true;
										}

									//}
								}

								//creating array to list records with respect to above conditions
								if($creat_array==true){

									$appl_list_array[$i]['appl_type'] = $appl_type;
									$appl_list_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
									$appl_list_array[$i]['firm_name'] = $firm_name;
									$appl_list_array[$i]['comm_with'] = $comm_with;
									$appl_list_array[$i]['appl_view_link'] = $appl_view_link;
									$appl_list_array[$i]['appl_edit_link'] = '';
									$appl_list_array[$i]['alloc_sub_tab']='scrutiny_allocation_tab';

								}

							$i=$i+1;
							}

						}

					}

					//for HO level scrutiny allocations
					elseif($for_level=='level_4'){

						if($sub_tab=='scrutiny_allocation_tab'){

							$get_allocations = $this->$ho_allocation_table->find('all',array('conditions' => array('dy_ama IS'=>$username)))->toArray();

							foreach($get_allocations as $each_alloc){

								$creat_array='';//clear variable each time

								$customer_id = $each_alloc['customer_id'];

								$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
								//get_form_type
								$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);

								//get firm details
								$firm_details = $this->DmiFirms->firmDetails($customer_id);
								$firm_name = $firm_details['firm_name'];
								$firm_table_id = $firm_details['id'];

								$appl_type_id = $each_flow['application_type'];
								$appl_view_link = '../scrutiny/form_scrutiny_fetch_id/'.$firm_table_id.'/view/'.$appl_type_id;

								//get Nodal officer details
								$this->loadModel('DmiUsers');
								$mo_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$each_alloc['ho_mo_smo'])))->first();
								if(empty(!$mo_user_details)){
									$comm_with = $mo_user_details['f_name'].' '.$mo_user_details['l_name'];
								}else{
									$comm_with='Not Allocated';
								}

								//check final submit status for level 1 and approved for each allocated id
								$level1_approved_status = $this->$final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'status'=>'approved','OR'=>array('current_level IN'=>array('level_3','level_4')))))->first();

								//get appl current position user
								$current_pos = $this->Customfunctions->getApplCurrentPos($appl_current_pos_table,$customer_id);

								if(empty($level1_approved_status) && !empty($current_pos)){

									//commented below condition to show allocated appls also in allocation window, on 10-08-2022
									//if($current_pos['current_user_email_id']==$username && $current_pos['current_level']=='level_4'){

										//application must not be with applicant while allocation
										//added on 03-02-2023 by Amol
										$finalSubmitStatus = $this->$final_submit_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
										if($finalSubmitStatus['status'] != 'referred_back'){
											$creat_array = true;
										}

									//}

								}

								//creating array to list records with respect to above conditions
								if($creat_array==true){

									$appl_list_array[$i]['appl_type'] = $appl_type;
									$appl_list_array[$i]['customer_id'] = $customer_id.'-'.$form_type;
									$appl_list_array[$i]['firm_name'] = $firm_name;
									$appl_list_array[$i]['comm_with'] = $comm_with;
									$appl_list_array[$i]['appl_view_link'] = $appl_view_link;
									$appl_list_array[$i]['appl_edit_link'] = '';
									$appl_list_array[$i]['alloc_sub_tab']='scrutiny_allocation_tab';

								}

								$i=$i+1;
							}
						}

					}

					//applied condition to check if appl is for backlog data entry, then change text 'New' to 'Old Appl'
					//added on 23-09-2021 by Amol
					$appl_type_id = $each_flow['application_type'];
					if($appl_type_id==1){
											
						foreach($appl_list_array as $key => $each){
							$cId = explode('-',(string) $each['customer_id']); #For Deprecations
							$checkIfOld = $this->Customfunctions->isOldApplication($cId[0],$appl_type_id);
							if($checkIfOld=='yes'){
								$appl_list_array[$key]['appl_type']='Old Appl';
							}
						}
					}

				}

				$appl_list_array = Hash::sort($appl_list_array, '{n}.on_date', 'desc');

			return $appl_list_array;
		}



		public function allocateApplForScrutiny(){

			$this->autoRender= false;
			$get_customer_id = explode('-',htmlentities($_POST['customer_id'], ENT_QUOTES));
			$customer_id = $get_customer_id[0];
			$appl_type = htmlentities($_POST['appl_type'], ENT_QUOTES);
			$mo_user_id = htmlentities($_POST['mo_user_id'], ENT_QUOTES);
			$current_date = date('d-m-Y H:i:s');

			//get allocation table name from flow wise tables
			$this->loadModel('DmiFlowWiseTablesLists');
			$this->loadModel('DmiApplicationTypes');
			$this->loadModel('DmiUsers');
			$this->loadModel('DmiRoOffices');
			$this->loadModel('DmiMoAllocationLogs');

			$current_level = $this->Session->read('current_level');
			$allocation_by = $this->Session->read('allocation_by');
			$username = $this->Session->read('username');

			//get allocating officer user details
			$get_user_id = $this->DmiUsers->find('all',array('fields'=>'id','conditions'=>array('email IS'=>$username)))->first();
			$user_id = $get_user_id['id'];

			$appl_type_id = $this->DmiApplicationTypes->find('all',array('conditions'=>array('LOWER(application_type) IS'=>strtolower($appl_type))))->first();

			if($appl_type=='Old Appl'){
				$appl_type_id['id'] = 1;
				//$_SESSION['application_type'] = 1;
			}

			//this temporary session varible is set for the SMS and Email - Akash [10-10-2022]
			$_SESSION['application_type_temp'] = $appl_type_id['id'];

			$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type_id['id'])))->first();
			
			$allocation_table = $flow_wise_tables['allocation'];
			$ho_allocation_table = $flow_wise_tables['ho_level_allocation'];
			$current_position_table = $flow_wise_tables['appl_current_pos'];
			
			$this->loadModel($allocation_table);
			$this->loadModel($ho_allocation_table);
			$this->loadModel($current_position_table);

			//get MO/SMO user details
			$user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$mo_user_id)))->first();
			$mo_posted_id = $user_details['posted_ro_office'];

			//get MO/SMO posted office
			$mo_office = $this->DmiRoOffices->find('all',array('conditions'=>array('id IS'=>$mo_posted_id)))->first();
			$mo_office = $mo_office['ro_office'];



			if($current_level=='level_3' && $allocation_by==null){//by RO/SO as nodal office

				$allocation_by = 'nodal';
				$allocation_type = '0';// 0 & 1 for first scrutiny allocation/reallocation
				$msg_id = 9;

				//get latest record for allocation
				$get_latest_id = $this->$allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();

				if($get_latest_id['level_1'] != null){$allocation_type = '1'; $msg_id = 10;}//to check allocation or reallocation

				$next_level = 'level_1';
				$mo_column_name = 'level_1';


			}
			elseif($current_level=='level_3' && $allocation_by=='level_4_ro'){//by RO for SO appl.

				$allocation_type = '2';// 2 & 3 for SO appl. by RO scrutiny allocation/reallocation
				$msg_id = 9;

				//get latest record for allocation
				$get_latest_id = $this->$allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();

				if($get_latest_id['level_4_mo'] != null){$allocation_type = '3'; $msg_id = 10;}//to check allocation or reallocation

				$next_level = 'level_4_mo';
				$mo_column_name = 'level_4_mo';

			}
			elseif($current_level=='level_4'){//by DY AMA from HO office

				$allocation_by = 'dy_ama';
				$allocation_type = '4';// 4 & 5 for ho mo allocation/reallocation
				$msg_id = 21;

				//get latest record for allocation
				$allocation_table = $ho_allocation_table;
				$get_latest_id = $this->$allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();

				if($get_latest_id['ho_mo_smo'] != null){$allocation_type = '5'; $msg_id = 22;}//to check allocation or reallocation

				$next_level = 'level_4';
				$mo_column_name = 'ho_mo_smo';

			}

			//updating the allocation table for level 1
			if($this->$allocation_table->updateAll(

					array($mo_column_name=>"$mo_user_id",'current_level'=>"$mo_user_id",'modified'=>"$current_date"),
					array('id'=>$get_latest_id['id'])

			)){

				//update current position table
				$this->$current_position_table->currentUserUpdate($customer_id,$mo_user_id,$next_level);

				// Common MO/SMO allocation logs, dirrentiate with allocation type nos
				$allocation_logs_entity = $this->DmiMoAllocationLogs->newEntity(array(

					'customer_id'=>$customer_id,
					'application_type'=>$appl_type_id['id'],
					'created'=>date('Y-m-d H:i:s'),
					'user_id'=>$user_id,
					'mo_office'=>$mo_office,
					'mo_email_id'=>$mo_user_id,
					'allocation_type'=>$allocation_type

				));

				if($this->DmiMoAllocationLogs->save($allocation_logs_entity)){
					
					//check if reallocating application and comments in Mo-RO comments table
					//if found entry then enter new record from RO to MO comment, to manage reallocation
					if($allocation_type=='1' || $allocation_type=='3' || $allocation_type=='5'){
						
						//for Nodal officer scrutiny
						if($allocation_type=='1'){
							$commentsTable = $flow_wise_tables['commenting_with_mo'];
							$this->loadModel($commentsTable);
							$dataArray = array(
								'customer_id'=>$customer_id,
								'comment_by'=>$username,
								'comment_to'=>$mo_user_id,
								'comment_date'=>date('Y-m-d H:i:s'),
								'comment'=>'Reallocated for Scrutiny',
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s'),
								'available_to'=>'mo'
							);
						
						//for Level4 RO scrutiny
						}else if($allocation_type=='3'){
							$commentsTable = $flow_wise_tables['ro_so_comments'];
							$this->loadModel($commentsTable);
							$dataArray = array(
								'customer_id'=>$customer_id,
								'comment_by'=>$username,
								'comment_to'=>$mo_user_id,
								'comment_date'=>date('Y-m-d H:i:s'),
								'comment'=>'Reallocated for Scrutiny',
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s'),
								'from_user'=>'ro',
								'to_user'=>'mo',
							);
						
						//for HO level scrutiny
						}else if($allocation_type=='5'){
							$commentsTable = $flow_wise_tables['ho_comment_reply'];
							$this->loadModel($commentsTable);
							$dataArray = array(
								'customer_id'=>$customer_id,
								'comment_by'=>$username,
								'comment_to'=>$mo_user_id,
								'comment_date'=>date('Y-m-d H:i:s'),
								'comment'=>'Reallocated for Scrutiny',
								'created'=>date('Y-m-d H:i:s'),
								'modified'=>date('Y-m-d H:i:s'),
								'from_user'=>'dy_ama',
								'to_user'=>'ho_mo_smo',
							);
						}
						//check last comment record	
						$checkRecord = $this->$commentsTable->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
						if(!empty($checkRecord)){
							$ro_mo_comments_entity = $this->$commentsTable->newEntity($dataArray);
							$this->$commentsTable->save($ro_mo_comments_entity);
						}
						
						
					}
					
					#SMS: Allocation
					$this->DmiSmsEmailTemplates->sendMessage($msg_id,$customer_id);
				}

			}

			echo '~'.$allocation_by.'~';
			exit;

		}



		public function allocateApplForInspection(){

			$this->autoRender= false;
			$get_customer_id = explode('-',htmlentities($_POST['customer_id'], ENT_QUOTES));
			$customer_id = $get_customer_id[0];

			$appl_type = htmlentities($_POST['appl_type'], ENT_QUOTES);
			$io_user_id = htmlentities($_POST['io_user_id'], ENT_QUOTES);
			$current_date = date('d-m-Y H:i:s');
			$ro_scheduled_date = htmlentities($_POST['ro_scheduled_date'], ENT_QUOTES);

			//get allocation table name from flow wise tables
			$this->loadModel('DmiFlowWiseTablesLists');
			$this->loadModel('DmiApplicationTypes');
			$this->loadModel('DmiUsers');
			$this->loadModel('DmiRoOffices');
			$this->loadModel('DmiIoAllocationLogs');
			$this->loadComponent('Customfunctions');

			$ro_scheduled_date = $this->Customfunctions->dateFormatCheck($ro_scheduled_date);

			$current_level = $this->Session->read('current_level');
			$username = $this->Session->read('username');

			//get allocating officer user details
			$get_user_id = $this->DmiUsers->find('all',array('fields'=>'id','conditions'=>array('email IS'=>$username)))->first();
			$user_id = $get_user_id['id'];

			$appl_type_id = $this->DmiApplicationTypes->find('all',array('conditions'=>array('LOWER(application_type) IS'=>strtolower($appl_type))))->first();

			//this temporary session varible is set for the SMS and Email - Akash [10-10-2022]
			$_SESSION['application_type_temp'] = $appl_type_id['id'];

			$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$appl_type_id['id'])))->first();
			$allocation_table = $flow_wise_tables['allocation'];
			$current_position_table = $flow_wise_tables['appl_current_pos'];

			$this->loadModel($allocation_table);
			$this->loadModel($current_position_table);

			//get IO user details
			$user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$io_user_id)))->first();
			$io_posted_id = $user_details['posted_ro_office'];

			//get IO posted office
			$io_office = $this->DmiRoOffices->find('all',array('conditions'=>array('id IS'=>$io_posted_id)))->first();
			$io_office = $io_office['ro_office'];



			if($current_level=='level_3'){//by RO/SO as nodal office

				$allocation_type = '0';// 0 & 1 for first Inspection allocation/reallocation
				$msg_id = 14;

				//get latest record for allocation
				$get_latest_id = $this->$allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();

				if($get_latest_id['level_2'] != null){$allocation_type = '1'; $msg_id = 15;}//to check allocation or reallocation

				$next_level = 'level_2';
				$mo_column_name = 'level_2';


			}


			//updating the allocation table for level 2
			if($this->$allocation_table->updateAll(

					array($mo_column_name=>"$io_user_id",
						'current_level'=>"$io_user_id",
						'modified'=>"$current_date",
						'ro_scheduled_date'=>"$ro_scheduled_date",
						'io_scheduled_date'=>"$ro_scheduled_date"),
					array('id'=>$get_latest_id['id'])

			)){

				//update current position table
				$this->$current_position_table->currentUserUpdate($customer_id,$io_user_id,$next_level);

				// Common IO allocation logs, dirrentiate with allocation type nos
				$allocation_logs_entity = $this->DmiIoAllocationLogs->newEntity(array(

					'customer_id'=>$customer_id,
					'application_type'=>$appl_type_id['id'],
					'created'=>date('Y-m-d H:i:s'),
					'user_id'=>$user_id,
					'io_office'=>$io_office,
					'io_email_id'=>$io_user_id,
					'allocation_type'=>$allocation_type

				));

				if($this->DmiIoAllocationLogs->save($allocation_logs_entity)){
					
					#SMS: Inspection
					$this->DmiSmsEmailTemplates->sendMessage($msg_id,$customer_id);
				}

			}

			exit;

		}

		// Function to combine user name and user email id and create user name(ID) value
		public function userNameList($user_email_list){

			$user_name_details = array();

			if(!empty($user_email_list)){
				$this->loadModel('DmiUsers');
				$user_name_list = $this->DmiUsers->find('all',array('conditions'=>array('email IN'=>$user_email_list),'order'=>'f_name ASC'))->toArray();

				$i = 0;
				foreach($user_name_list as $each){

					$user_name = $each['f_name'].' '.$each['l_name'];
					$user_email_id = $each['email'];
					$user_name_details[$user_email_id] = $user_name.' ('.base64_decode($user_email_id).')'; //for email encoding
					$i = $i+1;
				}
			}

			return $user_name_details;
		}

//allocations ends


	//this is an ajax function call to change inspection date by IO/RO
		public function changeInspectionDate(){

			$this->autoRender= false;
			$get_customer_id = explode('-',htmlentities($_POST['customer_id'], ENT_QUOTES));
			$customer_id = $get_customer_id[0];

			$appl_type = htmlentities($_POST['appl_type'], ENT_QUOTES);
			$io_scheduled_date = htmlentities($_POST['io_scheduled_date'], ENT_QUOTES);
			$io_sched_date_comment = htmlentities($_POST['io_sched_date_comment'], ENT_QUOTES);//added on 12-05-2021 by Amol

			//get allocation table name from flow wise tables
			$this->loadModel('DmiFlowWiseTablesLists');
			$this->loadModel('DmiApplicationTypes');

			$appl_type_id = $this->DmiApplicationTypes->find('all',array('conditions'=>array('LOWER(application_type) IS'=>strtolower($appl_type))))->first();

			$allocation_table = $this->DmiFlowWiseTablesLists->getFlowWiseTableDetails($appl_type_id['id'],'allocation');
			$this->loadModel($allocation_table);

			//Apply function to check valid date formate
			$io_scheduled_date = $this->Customfunctions->dateFormatCheck($io_scheduled_date);

			$find_id = $this->$allocation_table->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, /*'current_level'=>$this->Session->read('username')*/)))->first();

			$record_id = $find_id['id'];
												//added new field on 12-05-2021 by Amol
			$this->$allocation_table->updateAll(array('io_scheduled_date'=>"$io_scheduled_date",'io_sched_date_comment'=>"$io_sched_date_comment"),array('id'=>$record_id));

			#SMS: IO Rescheduled site inspection date
			$this->DmiSmsEmailTemplates->sendMessage(16,$customer_id);
			
			exit;

		}


   //below function is created to be call through ajax internally to get all counts and append on each tab
    /*      public function commonCountFetch(){

              $this->autoRender = false;
              $main_tab_count = array();
			  $main_tab_count['pending']= 0;
			  $main_tab_count['ref_back']= 0;
			  $main_tab_count['replied']= 0;
			  $main_tab_count['reports_filed']= 0;
			  $main_tab_count['rejected']= 0;
			  $main_tab_count['approved']=0;
			  $main_tab_count['alloc_main']=0;

			  $for_level = $this->Session->read('current_level');
			  $level_3_for = $this->Session->read('level_3_for');

			  if($level_3_for=='RO'){
				$nodal_sub_tab = array('with_applicant','scrutiny','reports','with_sub_office','with_ho_office');
			  }else{
				 $nodal_sub_tab = array('with_applicant','scrutiny','reports','with_reg_office');
			  }
			  $scrutiny_sub_tab = array('scrutiny_with_nodal_office','scrutiny_with_reg_office','scrutiny_with_ho_office');
			  $ho_sub_tab = array('for_ho_scrutiny','for_dy_ama','for_jt_ama','for_ama');
			  $for_status = array('pending','ref_back','replied','approved');
			  if($for_level=='level_2'){
				  $for_status = array('pending','reports_filed','ref_back','replied','approved');
			  
			  //added condition for level3 and level4, no need to show approved count or list
			  //as already showing grnated appl list to them with appl,report,cert pdfs
			  //added on 03-12-2021
			  }elseif($for_level=='level_3' || $for_level=='level_4'){
				  $for_status = array('pending','ref_back','replied');
			  }

			  $nodal_allocation_sub_tab = array('scrutiny_allocation_tab','scrutiny_allocation_by_level4ro_tab','inspection_allocation_tab');
			  $ho_allocation_sub_tab = array('scrutiny_allocation_tab');

			  if($for_level=='pao'){
				$i=1;
				foreach($for_status as $status){
					if($i==1){
						$main_tab_count['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
					elseif($i==2){
						$main_tab_count['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
					elseif($i==3){
						$main_tab_count['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
					elseif($i==4){
						$main_tab_count['approved']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}

				$i=$i+1;
				}

			  }
			  elseif($for_level=='level_1'){

				  foreach($scrutiny_sub_tab as $each_tab){

					  $i=1;
						foreach($for_status as $status){
							if($i==1){ //main_tab count[$each_tab][status] is multidimenstional array for sub tabs values
								$main_tab_count[$each_tab]['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
								$main_tab_count['pending'] = $main_tab_count['pending']+$main_tab_count[$each_tab]['pending'];
							}
							elseif($i==2){
								$main_tab_count[$each_tab]['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
								$main_tab_count['ref_back'] = $main_tab_count['ref_back']+$main_tab_count[$each_tab]['ref_back'];
							}
							elseif($i==3){
								$main_tab_count[$each_tab]['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
								$main_tab_count['replied'] = $main_tab_count['replied']+$main_tab_count[$each_tab]['replied'];
							}
							elseif($i==4){
								$main_tab_count[$each_tab]['approved']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
								$main_tab_count['approved'] = $main_tab_count['approved']+$main_tab_count[$each_tab]['approved'];
							}

						$i=$i+1;
						}
				  }
			  }
			  elseif($for_level=='level_2'){
				$i=1;
				foreach($for_status as $status){
					if($i==1){
						$main_tab_count['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
					elseif($i==2){
						$main_tab_count['reports_filed']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
					elseif($i==3){
						$main_tab_count['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
					elseif($i==4){
						$main_tab_count['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
					elseif($i==5){
						$main_tab_count['approved']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}

				$i=$i+1;
				}

			  }
			  elseif($for_level=='level_3'){

				  foreach($nodal_sub_tab as $each_tab){

					$i=1;
					foreach($for_status as $status){
						if($i==1){//main_tab count[$each_tab][status] is multidimenstional array for sub tabs values
							$main_tab_count[$each_tab]['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
							$main_tab_count['pending'] = $main_tab_count['pending']+$main_tab_count[$each_tab]['pending'];
						}
						elseif($i==2){
							$main_tab_count[$each_tab]['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
							$main_tab_count['ref_back'] = $main_tab_count['ref_back']+$main_tab_count[$each_tab]['ref_back'];
						}
						elseif($i==3){
							$main_tab_count[$each_tab]['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
							$main_tab_count['replied'] = $main_tab_count['replied']+$main_tab_count[$each_tab]['replied'];
						}
						
						elseif($i==4){
							$main_tab_count[$each_tab]['approved']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
							$main_tab_count['approved'] = $main_tab_count['approved']+$main_tab_count[$each_tab]['approved'];
						}
					

					$i=$i+1;
					}

				  }

				  //for Rejected Applications
					$main_tab_count['rejected']= count($this->Commonlistingfunctions->fetchRecords($for_level,'rejected'));

//print_r($main_tab_count);exit;
				  //for allocation
				 $j=1;
				 foreach($nodal_allocation_sub_tab as $alloc_tab){

					if($for_level=='level_3' || ($for_level=='level_4' && $j==1)){
						$main_tab_count[$alloc_tab]= count($this->fetchAllocationRecords($for_level,$alloc_tab));
						$main_tab_count['alloc_main'] = $main_tab_count['alloc_main'] + $main_tab_count[$alloc_tab];
					}
				 $j=$j+1;
				 }
			  }
			  elseif($for_level=='level_4'){

				  foreach($ho_sub_tab as $each_tab){

					$i=1;
					foreach($for_status as $status){
						if($i==1){//main_tab count[$each_tab][status] is multidimenstional array for sub tabs values
							$main_tab_count[$each_tab]['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
							$main_tab_count['pending'] = $main_tab_count['pending']+$main_tab_count[$each_tab]['pending'];
						}
						elseif($i==2){
							$main_tab_count[$each_tab]['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
							$main_tab_count['ref_back'] = $main_tab_count['ref_back']+$main_tab_count[$each_tab]['ref_back'];
						}
						elseif($i==3){
							$main_tab_count[$each_tab]['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
							$main_tab_count['replied'] = $main_tab_count['replied']+$main_tab_count[$each_tab]['replied'];
						}
						elseif($i==4){
							$main_tab_count[$each_tab]['approved']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
							$main_tab_count['approved'] = $main_tab_count['approved']+$main_tab_count[$each_tab]['approved'];
						}

					$i=$i+1;
					}
				  }

				 //for allocation
				 $j=1;
				 foreach($ho_allocation_sub_tab as $alloc_tab){

					$main_tab_count[$alloc_tab]= count($this->fetchAllocationRecords($for_level,$alloc_tab));
					$main_tab_count['alloc_main'] = $main_tab_count['alloc_main'] + $main_tab_count[$alloc_tab];
				 $j=$j+1;
				 }
			  }


              echo '~'.json_encode($main_tab_count).'~';
              exit;
          }
*/

	//This count function is updated on 03-06-2022, for specific count on dashboard
	public function commonCountFetch(){

              $this->autoRender = false;
              $main_tab_count = array();
			  $main_tab_count['pending']= 0;
			  $main_tab_count['ref_back']= 0;
			  $main_tab_count['replied']= 0;
			  $main_tab_count['reports_filed']= 0;
			  $main_tab_count['rejected']= 0;
			  $main_tab_count['approved']=0;
			  $main_tab_count['alloc_main']=0;

			  $for_level = $this->Session->read('current_level');
			  $level_3_for = $this->Session->read('level_3_for');
			  
			  //this change is added on 03-06-2022 by Amol
			  //to get specific status count only, to overcome the load.
			  //so the status is fetched from POST value when called from script.
			  $fetchStatus = $_POST['fetchStatus'];
			  
			  if(!empty($fetchStatus)){

				  if($level_3_for=='RO'){
					$nodal_sub_tab = array('with_applicant','scrutiny','reports','with_sub_office','with_ho_office');
				  }else{
					 $nodal_sub_tab = array('with_applicant','scrutiny','reports','with_reg_office');
				  }
				  $scrutiny_sub_tab = array('scrutiny_with_nodal_office','scrutiny_with_reg_office','scrutiny_with_ho_office');
				  $ho_sub_tab = array('for_ho_scrutiny','for_dy_ama','for_jt_ama','for_ama');
/*				  
				  $for_status = array('pending','ref_back','replied','approved');
				  if($for_level=='level_2'){
					  $for_status = array('pending','reports_filed','ref_back','replied','approved');
				  
				  //added condition for level3 and level4, no need to show approved count or list
				  //as already showing grnated appl list to them with appl,report,cert pdfs
				  //added on 03-12-2021
				  }elseif($for_level=='level_3' || $for_level=='level_4'){
					  $for_status = array('pending','ref_back','replied');
				  }
*/
				  $nodal_allocation_sub_tab = array('scrutiny_allocation_tab','scrutiny_allocation_by_level4ro_tab','inspection_allocation_tab');
				  $ho_allocation_sub_tab = array('scrutiny_allocation_tab');
				  
				  $for_status = array($fetchStatus);

				  if($for_level=='pao'){
					$i=1;
					foreach($for_status as $status){
						if($status=='pending'){
							$main_tab_count['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
						}
						elseif($status=='ref_back'){
							$main_tab_count['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
						}
						elseif($status=='replied'){
							$main_tab_count['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
						}
						elseif($status=='approved'){
							$main_tab_count['approved']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
						}

					$i=$i+1;
					}

				  }
				  elseif($for_level=='level_1'){

					  foreach($scrutiny_sub_tab as $each_tab){

						  $i=1;
							foreach($for_status as $status){
								if($status=='pending'){ //main_tab count[$each_tab][status] is multidimenstional array for sub tabs values
									$main_tab_count[$each_tab]['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['pending'] = $main_tab_count['pending']+$main_tab_count[$each_tab]['pending'];
								}
								elseif($status=='ref_back'){
									$main_tab_count[$each_tab]['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['ref_back'] = $main_tab_count['ref_back']+$main_tab_count[$each_tab]['ref_back'];
								}
								elseif($status=='replied'){
									$main_tab_count[$each_tab]['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['replied'] = $main_tab_count['replied']+$main_tab_count[$each_tab]['replied'];
								}
								elseif($status=='approved'){
									$main_tab_count[$each_tab]['approved']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['approved'] = $main_tab_count['approved']+$main_tab_count[$each_tab]['approved'];
								}

							$i=$i+1;
							}
					  }
				  }
				  elseif($for_level=='level_2'){
					$i=1;
					foreach($for_status as $status){
						if($status=='pending'){
							$main_tab_count['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
						}
						elseif($status=='reports_filed'){
							$main_tab_count['reports_filed']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
						}
						elseif($status=='ref_back'){
							$main_tab_count['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
						}
						elseif($status=='replied'){
							$main_tab_count['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
						}
						elseif($status=='approved'){
							$main_tab_count['approved']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
						}

					$i=$i+1;
					}

				  }
				  elseif($for_level=='level_3'){

					  //for Rejected Applications
					 if($for_status[0]=='rejected'){
						$main_tab_count['rejected']= count($this->Commonlistingfunctions->fetchRecords($for_level,'rejected'));
					 }

	//print_r($main_tab_count);exit;
					//for allocation
					
					if($for_status[0]=='allocation'){
						$j=1;
						foreach($nodal_allocation_sub_tab as $alloc_tab){
							if($for_level=='level_3' || ($for_level=='level_4' && $j==1)){
								$main_tab_count[$alloc_tab]= count($this->fetchAllocationRecords($for_level,$alloc_tab));
								$main_tab_count['alloc_main'] = $main_tab_count['alloc_main'] + $main_tab_count[$alloc_tab];
							}
						 $j=$j+1;
						}
					}else{
						
						foreach($nodal_sub_tab as $each_tab){

							$i=1;
							foreach($for_status as $status){//print_r($status);exit;
								if($status=='pending'){//main_tab count[$each_tab][status] is multidimenstional array for sub tabs values
									$main_tab_count[$each_tab]['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['pending'] = $main_tab_count['pending']+$main_tab_count[$each_tab]['pending'];
								}
								elseif($status=='ref_back'){
									$main_tab_count[$each_tab]['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['ref_back'] = $main_tab_count['ref_back']+$main_tab_count[$each_tab]['ref_back'];
								}
								elseif($status=='replied'){
									$main_tab_count[$each_tab]['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['replied'] = $main_tab_count['replied']+$main_tab_count[$each_tab]['replied'];
								}
								
							/*	elseif($i==4){
									$main_tab_count[$each_tab]['approved']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['approved'] = $main_tab_count['approved']+$main_tab_count[$each_tab]['approved'];
								}*/
							

							$i=$i+1;
							}

						}
						
					}
				  }
				  elseif($for_level=='level_4'){  

					 //for allocation
					 if($for_status[0]=='allocation'){
						 $j=1;
						 foreach($ho_allocation_sub_tab as $alloc_tab){

							$main_tab_count[$alloc_tab]= count($this->fetchAllocationRecords($for_level,$alloc_tab));
							$main_tab_count['alloc_main'] = $main_tab_count['alloc_main'] + $main_tab_count[$alloc_tab];
						 $j=$j+1;
						 }
					 }else{
						 
						 foreach($ho_sub_tab as $each_tab){

							$i=1;
							foreach($for_status as $status){
								if($status=='pending'){//main_tab count[$each_tab][status] is multidimenstional array for sub tabs values
									$main_tab_count[$each_tab]['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['pending'] = $main_tab_count['pending']+$main_tab_count[$each_tab]['pending'];
								}
								elseif($status=='ref_back'){
									$main_tab_count[$each_tab]['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['ref_back'] = $main_tab_count['ref_back']+$main_tab_count[$each_tab]['ref_back'];
								}
								elseif($status=='replied'){
									$main_tab_count[$each_tab]['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['replied'] = $main_tab_count['replied']+$main_tab_count[$each_tab]['replied'];
								}
							/*	elseif($i==4){
									$main_tab_count[$each_tab]['approved']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
									$main_tab_count['approved'] = $main_tab_count['approved']+$main_tab_count[$each_tab]['approved'];
								}*/

							$i=$i+1;
							}
						 }
						 
						}
					}
				  
				}


              echo '~'.json_encode($main_tab_count).'~';
              exit;
          }



	//to get over all pending work count for user on dashboard
	public function dashboardpendingWorkCount(){

	 // $this->autoRender = false;
	  $main_tab_count = array();
	  $main_tab_count['pending']= 0;
	  $main_tab_count['replied']= 0;
	  $main_tab_count['alloc_main']=0;

	  $current_level_arr = array();
	  $username = $this->Session->read('username');
	  $this->loadModel('DmiUserRoles');
	  $current_user_roles = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$username)))->first();

		if(!empty($current_user_roles)){

			if($current_user_roles['mo_smo_inspection'] == 'yes'){
				$current_level_arr[0] = 'level_1';
			}
			if($current_user_roles['io_inspection'] == 'yes'){
				$current_level_arr[1] = 'level_2';
			}

			//for level 3 users
			if($current_user_roles['ro_inspection'] == 'yes'){
				$current_level_arr[2] = 'level_3';//either this

			}elseif($current_user_roles['so_inspection'] == 'yes'){
				$current_level_arr[2] = 'level_3';//either this

			}

			//for level 4 users
			if($current_user_roles['dy_ama'] == 'yes'){
				$current_level_arr[3] = 'level_4';

			}elseif($current_user_roles['jt_ama'] == 'yes'){
				$current_level_arr[3] = 'level_4';

			}elseif($current_user_roles['ama'] == 'yes'){
				$current_level_arr[3] = 'level_4';

			}elseif($current_user_roles['ho_mo_smo'] == 'yes'){
				$current_level_arr[3] = 'level_4';

			}elseif($current_user_roles['pao'] == 'yes'){
				$current_level_arr[4] = 'pao';

			}
		}



		foreach($current_level_arr as $for_level){

			if($current_user_roles['ro_inspection'] == 'yes'){
				 $level_3_for = 'RO';
			}elseif($current_user_roles['so_inspection'] == 'yes'){
				$level_3_for = 'SO';
			}else{
				$level_3_for = '';
			}

			  if($level_3_for=='RO'){
				$nodal_sub_tab = array('with_applicant','scrutiny','reports','with_sub_office','with_ho_office');
			  }else{
				$nodal_sub_tab = array('with_applicant','scrutiny','reports','with_reg_office');
			  }

			  $scrutiny_sub_tab = array('scrutiny_with_nodal_office','scrutiny_with_reg_office','scrutiny_with_ho_office');
			  $ho_sub_tab = array('for_ho_scrutiny','for_dy_ama','for_jt_ama','for_ama');
			  $for_status = array('pending','replied');
			  if($for_level=='level_2'){
				  $for_status = array('pending','ref_back');
			  }

			  $nodal_allocation_sub_tab = array('scrutiny_allocation_tab','scrutiny_allocation_by_level4ro_tab','inspection_allocation_tab');
			  $ho_allocation_sub_tab = array('scrutiny_allocation_tab');

			  if($for_level=='pao'){
				$i=1;
				foreach($for_status as $status){
					if($i==1){
						$main_tab_count['payment']['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
					elseif($i==2){
						$main_tab_count['payment']['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
				$i=$i+1;
				}

			  }
			  elseif($for_level=='level_1'){

				  foreach($scrutiny_sub_tab as $each_tab){

					  $i=1;
						foreach($for_status as $status){
							if($i==1){ //main_tab count[$each_tab][status] is multidimenstional array for sub tabs values
								$main_tab_count[$each_tab]['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
							//	$main_tab_count['pending'] = $main_tab_count['pending']+$main_tab_count[$each_tab]['pending'];
							}
							elseif($i==2){
								$main_tab_count[$each_tab]['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
							//	$main_tab_count['replied'] = $main_tab_count['replied']+$main_tab_count[$each_tab]['replied'];
							}
						$i=$i+1;
						}
				  }
			  }
			  elseif($for_level=='level_2'){
				$i=1;
				foreach($for_status as $status){
					if($i==1){
						$main_tab_count['inspection']['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
					elseif($i==2){
						$main_tab_count['inspection']['ref_back']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status));
					}
				$i=$i+1;
				}

			  }
			  elseif($for_level=='level_3'){

				  foreach($nodal_sub_tab as $each_tab){

					$i=1;
					foreach($for_status as $status){
						if($i==1){//main_tab count[$each_tab][status] is multidimenstional array for sub tabs values
							$main_tab_count[$each_tab]['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
						//	$main_tab_count['pending'] = $main_tab_count['pending']+$main_tab_count[$each_tab]['pending'];
						}
						elseif($i==2){
							$main_tab_count[$each_tab]['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
						//	$main_tab_count['replied'] = $main_tab_count['replied']+$main_tab_count[$each_tab]['replied'];
						}

					$i=$i+1;
					}

				  }

				  //for allocation
				 $j=1;
				 foreach($nodal_allocation_sub_tab as $alloc_tab){

					if($for_level=='level_3' || ($for_level=='level_4' && $j==1)){
						$main_tab_count[$alloc_tab]= count($this->fetchAllocationRecords($for_level,$alloc_tab));
					//	$main_tab_count['alloc_main'] = $main_tab_count['alloc_main'] + $main_tab_count[$alloc_tab];
					}
				 $j=$j+1;
				 }
			  }
			  elseif($for_level=='level_4'){

				  foreach($ho_sub_tab as $each_tab){

					$i=1;
					foreach($for_status as $status){
						if($i==1){//main_tab count[$each_tab][status] is multidimenstional array for sub tabs values
							$main_tab_count[$each_tab]['pending']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
						//	$main_tab_count['pending'] = $main_tab_count['pending']+$main_tab_count[$each_tab]['pending'];
						}
						elseif($i==2){
							$main_tab_count[$each_tab]['replied']= count($this->Commonlistingfunctions->fetchRecords($for_level,$status,$each_tab));
						//	$main_tab_count['replied'] = $main_tab_count['replied']+$main_tab_count[$each_tab]['replied'];
						}

					$i=$i+1;
					}
				  }

				 //for allocation
				 $j=1;
				 foreach($ho_allocation_sub_tab as $alloc_tab){

					$main_tab_count[$alloc_tab]= count($this->fetchAllocationRecords($for_level,$alloc_tab));
				//	$main_tab_count['alloc_main'] = $main_tab_count['alloc_main'] + $main_tab_count[$alloc_tab];
				 $j=$j+1;
				 }
			  }
		}
       //print_r($main_tab_count);exit;
         return $main_tab_count;

      }




	//for Application Rejection module

		public function openRejectApplPopup(){

			$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call

			$appl_type = $_POST['appl_type'];
			$customer_id = $_POST['customer_id'];
			$this->set(compact('appl_type','customer_id'));
			$this->render('/element/common_counts_and_list_elements/common_reject_appl_popup');

		}

		public function rejectApplication(){

			$this->autoRender= false;
			$get_customer_id = explode('-',htmlentities($_POST['customer_id'], ENT_QUOTES));
			$customer_id = $get_customer_id[0];
			$appl_type = htmlentities($_POST['appl_type'], ENT_QUOTES);
			$form_type = $get_customer_id[1];
			$remark = htmlentities($_POST['remark'], ENT_QUOTES);
			$this->loadModel('DmiApplicationTypes');
			$this->loadModel('DmiRejectedApplLogs');
			
			//added on 18-07-2022 by Amol
			//for old applications
			if ($appl_type!='Old Appl') {
				$appl_type_id = $this->DmiApplicationTypes->find('all',array('conditions'=>array('LOWER(application_type) IS'=>strtolower($appl_type))))->first();
			}else{
				$appl_type_id['id']=1;
			}
			

			//insert record in reject log table
			$rejectlogEntity = $this->DmiRejectedApplLogs->NewEntity(array(

				'appl_type' => $appl_type_id['id'],
				'form_type' => $form_type,
				'customer_id' => $customer_id,
				'by_user' => $this->Session->read('username'),
				'remark' => $remark,
				'created' => date('Y-m-d H:i:s')

			));

			$this->DmiRejectedApplLogs->save($rejectlogEntity);
			echo '~'.$this->Session->read('for_sub_tab').'~';
			exit;
		}



	//added this method for the Application transfer module from one office to another.
	//on 04-02-2019 by Amol
	public function transferAppl(){

		// set variables to show popup messages from view file
		$message = '';
		$redirect_to = '';

		$this->loadModel('DmiRoOffices');		
		$this->loadModel('DmiApplicationTypes');
		

		//updated and added code to get Office table details from appl mapping Model
		/*$this->loadModel('DmiApplWithRoMappings');
		$get_ro_details = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$ro_email_id = $get_ro_details['ro_email_id'];*/

		//get Application types list
		$applTypesList = $this->DmiApplicationTypes->find('list',array('valueField'=>'application_type','order'=>'id ASC'))->toArray();
		$this->set('applTypesList',$applTypesList);

		//office list
		$office_list = $this->DmiRoOffices->find('list', array('keyField'=>'id','valueField'=>'ro_office','conditions'=>array('office_type IN'=>array('RO','SO'),'delete_status IS NULL'),'order'=>'ro_office ASC'));
		$this->set('office_list',$office_list);

		//on click of transfer btn
		if ($this->request->getData('transfer') != null){

			$appl_type = $this->request->getData('appl_type');
			$appl_type = $this->Customfunctions->dropdownSelectInputCheck('DmiApplicationTypes',$this->request->getData('appl_type'));

			//check drop down values
			$from_office = $this->Customfunctions->dropdownSelectInputCheck('DmiRoOffices',$this->request->getData('from_office'));
			$to_office = $this->Customfunctions->dropdownSelectInputCheck('DmiRoOffices',$this->request->getData('to_office'));

			$customer_id = $this->request->getData('appl_id');
			$remark = htmlentities($this->request->getData('remark'), ENT_QUOTES);//html encode
		

			//validate post data
			if(!empty($appl_type) && !empty($from_office) && !empty($customer_id) && !empty($to_office) && !empty($remark)) {

				// Make common function to Transfer application from officer to another office
				// Done by Pravin Bhakare 11-10-2021
				$transferStatus = $this->transferAppFormTo($appl_type,$customer_id,$from_office,$to_office,$remark);

				if($transferStatus == 1)
				{

					$message = 'The Selected Application is Successfully Transfered. Thankyou';
					$redirect_to = 'transfer_appl';

				}
				elseif( $transferStatus == 0 )
				{
					$message = 'Sorry... Your request to transfer this Application is failed due to some reason.';
					$redirect_to = 'transfer_appl';

				}

			}

		}

		// set variables to show popup messages from view file
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);

		if($message != null){
			$this->render('/element/message_boxes');
		}

	}
	
	//logic to transfer single application from one office to another office
	// Done by Pravin Bhakare 11-10-2021
	public function transferAppFormTo($appl_type,$customer_id,$from_office,$to_office,$remark){
	
			$this->loadModel('DmiApplWithRoMappings');
			$this->loadModel('DmiApplWithRoMappingLogs');
			$this->loadModel('DmiApplTransferLogs');
			$this->loadModel('DmiFlowWiseTablesLists');
			$this->loadModel('DmiRoOffices');	

			$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$appl_type)))->first();
			$finalSubmitTable = $flow_wise_tables['application_form'];
			$allocationTable = $flow_wise_tables['allocation'];
			$currentPosTable = $flow_wise_tables['appl_current_pos'];
			$this->loadModel($finalSubmitTable);
			$this->loadModel($allocationTable);
			$this->loadModel($currentPosTable);
			
			
			//get new office details to which application will be tranfering
			$to_office_details = $this->DmiRoOffices->find('all',array('conditions'=>array('id IS'=>$to_office)))->first();
			$to_user = $to_office_details['ro_email_id'];
			
			
				$appl_transfer_status=null;//default
				$date=date('Y-m-d H:i:s');
				
				//check if application is for renewal
				$check_final_submit = $this->$finalSubmitTable->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
				
				
				if(!empty($check_final_submit)){
				//if found entry in renewal table then no need to updated new application allocation table
				//update only renewal allocation and further flows allocations if level_3 != approved

					//check if renewal granted, if No then update allocation table also, else only update mapping table
					if(!($check_final_submit['current_level']=='level_3' && $check_final_submit['status']=='approved')){

						//check current position table, if current position is level 3 then update
						$current_position = $this->$currentPosTable->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
						if($current_position['current_level']=='level_3'){

							$this->$currentPosTable->updateAll(array('current_user_email_id' => "$to_user",'modified'=>"$date"),array('customer_id' => $customer_id));
						}

						//get previous allocation details
						$alloc_details = $this->$allocationTable->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
						$pre_level_3_user = $alloc_details['level_3'];


						//update the allocation table
						if($alloc_details['current_level']==$pre_level_3_user){//if current level == level_3

							$this->$allocationTable->updateAll(array('level_3' => "$to_user",'current_level' => "$to_user",'modified'=>"$date"),array('id'=>$alloc_details['id'],'customer_id' => $customer_id));
						}else{

							$this->$allocationTable->updateAll(array('level_3' => "$to_user",'modified'=>"$date"),array('id'=>$alloc_details['id'],'customer_id' => $customer_id));
						}

						//Insert transfer details in transfer log table
						$DmiApplTransferLogsEntity = $this->DmiApplTransferLogs->newEntity(array(
							'customer_id'=>$customer_id,
							'from_office'=>$from_office,
							'from_user'=>$pre_level_3_user,
							'to_office'=>$to_office,
							'to_user'=>$to_user,
							'by_user'=>$this->Session->read('username'),
							'appl_type'=>$appl_type,
							'remark'=>$remark,
							'created'=>date('Y-m-d H:i:s'),
							'appl_status_that_time'=>'In Progress'
						));

						$this->DmiApplTransferLogs->save($DmiApplTransferLogsEntity);

					}else{
						//not updating allocation table as application has grant
						//only office changed
						//Insert transfer details in transfer log table
						$DmiApplTransferLogsEntity = $this->DmiApplTransferLogs->newEntity(array(
							'customer_id'=>$customer_id,
							'from_office'=>$from_office,
							'from_user'=>null,
							'to_office'=>$to_office,
							'to_user'=>null,
							'by_user'=>$this->Session->read('username'),
							'appl_type'=>$appl_type,
							'remark'=>$remark,
							'created'=>date('Y-m-d H:i:s'),							
							'appl_status_that_time'=>'Granted'
						));

						$this->DmiApplTransferLogs->save($DmiApplTransferLogsEntity);
					}

					//this variable used below to update application with office mapping table
					$appl_transfer_status = 'yes';
				}
				
				
				
				if($appl_transfer_status == 'yes'){

					//update application with office mapping table
					$this->DmiApplWithRoMappings->updateAll(array('office_id' => "$to_office",'modified'=>"$date"),array('customer_id' => $customer_id));

					//insert records in mapping logs table
					$DmiApplWithRoMappingLogsEntity = $this->DmiApplWithRoMappingLogs->newEntity(array(
						'customer_id'=>$customer_id,
						'office_id'=>$to_office,
						'created'=>date('Y-m-d H:i:s')

					));

					$this->DmiApplWithRoMappingLogs->save($DmiApplWithRoMappingLogsEntity);
						
					return 1;	

				}else{
					return 0;
				}
				
	}


	//ajax function to fetch Office wise applications
	//from application transfer module
	public function getOfficeWiseAppl(){

		$this->autoRender = false;
		$this->loadModel('DmiApplWithRoMappings');
		$this->loadModel('DmiFlowWiseTablesLists');

		$office_id = $this->request->getData('from_office');
		$appl_type = $this->request->getData('appl_type');

		$finalSubmiModel = $this->DmiFlowWiseTablesLists->getFlowWiseTableDetails($appl_type,'application_form');

		//get table name from model name
		$finalSubmiTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$finalSubmiModel))));

		//using core joins due issue in cakephp 3.8 joins format
		$conn = ConnectionManager::get('default');
		$stmt = $conn->execute("select am.customer_id from dmi_appl_with_ro_mappings as am
								inner join dmi_firms as f on am.customer_id = f.customer_id AND f.delete_status IS Null
								inner join $finalSubmiTable as fs on fs.customer_id = am.customer_id AND fs.status='pending'
								where am.office_id='$office_id'");
		$appl_list = $stmt ->fetchAll('assoc');

		echo '~'.json_encode($appl_list).'~';
		exit;

	}
	
	
	//ajax function to fetch TO Office, type wise on 30-01-2023 by Amol
	//from application transfer module
	public function getToOffice(){

		$this->autoRender = false;
		$this->loadModel('DmiRoOffices');

		$office_id = $this->request->getData('from_office');
		$getOffice_type = $this->DmiRoOffices->find('all',array('fields'=>'office_type','conditions'=>array('id IS'=>$office_id)))->first();
		$office_type = $getOffice_type['office_type'];
		
		//using core joins due issue in cakephp 3.8 joins format
		$conn = ConnectionManager::get('default');
		$stmt = $conn->execute("select id,ro_office from dmi_ro_offices where office_type='$office_type' AND delete_status IS NULL");
		$appl_list = $stmt ->fetchAll('assoc');

		echo '~'.json_encode($appl_list).'~';
		exit;

	}


	//this function called through ajax to get application status details
	//to alert user before tranfering if application in process.
	public function getApplStatusDetails(){

		$this->autoRender = false;

		$customer_id = $this->request->getData('appl_id');
		$appl_type = $this->request->getData('appl_type');
		$appl_current_status = $this->Customfunctions->getApplicationCurrentStatus($customer_id,$appl_type);

		echo '~'.$appl_current_status.'~';
		exit;

	}


	//to show grant Certificates to Nodal Officer
	public function grantCertificatesList(){

		$user_email_id = $this->Session->read('username');
		$this->loadModel('DmiApplWithRoMappings');
		$this->loadModel('DmiFlowWiseTablesLists');
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiApplicationTypes');
		$this->loadComponent('Customfunctions');

		//get flow wise grant table

		$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();

		$all_grant_cert = array();
		$i=0;
		foreach($flow_wise_tables as $each_flow){

			$grantTable = $each_flow['grant_pdf'];
			$this->loadModel($grantTable);

			$fetch_all_granted_pdf = $this->$grantTable->find('all',array('fields'=>'customer_id','group'=>'customer_id','conditions'=>array('user_email_id !='=>'old_application')))->toArray();

			foreach($fetch_all_granted_pdf as $each_pdf){

				$customer_id = $each_pdf['customer_id'];
				$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
				$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
				//check current Nodal officer for specific district
				$get_ro_details = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
				$ro_email_id = $get_ro_details['ro_email_id'];

				$each_cert_detail = $this->$grantTable->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();

				//if current Nodal Officer of application district and logged in user id matched
				if($user_email_id == $ro_email_id){

					$get_appl_type = $this->DmiApplicationTypes->find('all',array('conditions'=>array('id IS'=>$each_flow['application_type'])))->first();
					$appl_type = $get_appl_type['application_type'];

					//get firm name for the customer id
					$firm_details = $this->DmiFirms->firmDetails($customer_id);
					$firm_name = $firm_details['firm_name'];

					if($split_customer_id[1] == 1){
						$cert_type = 'CA';
					}
					elseif($split_customer_id[1] == 2){
						$cert_type = 'Printing';
					}
					elseif($split_customer_id[1] == 3){
						$cert_type = 'Laboratory';
					}

					$all_grant_cert[$i]['appl_type'] = $appl_type;
					$all_grant_cert[$i]['cert_type'] = $cert_type;
					$all_grant_cert[$i]['customer_id'] = $customer_id.'-'.$form_type;
					$all_grant_cert[$i]['firm_name'] = $firm_name;
					$all_grant_cert[$i]['pdf_link'] = $each_cert_detail['pdf_file'];
					$all_grant_cert[$i]['date'] = $each_cert_detail['modified'];

				}
			}

		$i=$i+1;
		}

		$this->set('all_grant_cert',$all_grant_cert);

	}




//for Lab export JAT inspection	start

	public function jtamaJatStatusMainTab(){

		$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call
		$this->Session->write('show_list_for','jtama_jat_status');

		$this->render('/element/common_counts_and_list_elements/ho_common_elements/jtama_JAT_status_sub_tabs');
	}

	//common JTAMA JAT status sub tabs onclick calls
	public function commonJtamaJatSubTabsCall($sub_tab){

		$appl_list_array = $this->fetchJtamaJatStatusRecords($sub_tab);
		$this->set('appl_list_array',$appl_list_array);
		$this->render('/element/common_counts_and_list_elements/ho_common_elements/jtama_jat_status_list_element');

	}

	public function fetchJtamaJATStatusList(){

		$this->viewBuilder()->enableAutoLayout(false);//to stop showing blank page for ajax call

		$list_for = $_POST['list_for'];

		if($list_for == 'pending_jat'){
			$sub_tab = 'jtama_pending_jat_tab';

		}elseif($list_for == 'inprogress_jat'){
			$sub_tab = 'jtama_inprogress_jat_tab';

		}elseif($list_for == 'filed_reports_jat'){
			$sub_tab = 'jat_filed_reports_jat_tab';

		}

		$this->commonJtamaJatSubTabsCall($sub_tab);

	}



	public function fetchJtamaJatStatusRecords($sub_tab) {

		$appl_list_array = array();

		//dummy list array
		//if($creat_array==true){

			$appl_list_array[0]['appl_type'] = 'New';
			$appl_list_array[0]['customer_id'] = '101/3/MUM/014-C';
			$appl_list_array[0]['firm_name'] = 'Lab Export';
			$appl_list_array[0]['comm_with'] = 'JAT members';
			$appl_list_array[0]['forwarded_on'] = '21/10/2020';
			$appl_list_array[0]['appl_view_link'] = '';
			$appl_list_array[0]['appl_edit_link'] = '';

		//}

		return $appl_list_array;
	}


//for Lab export JAT inspection	end

	//below function is created on 26-04-2021 by Amol
	//to fetch and show state wise status of applications, on click of map on dashboard.
	public function getStateWiseDetails(){

		$this->autoRender = false;
		$state_id = $_POST['state_id'];
		$this->loadModel('DmiStates');
		$this->loadModel('DmiFlowWiseTablesLists');
		$conn = ConnectionManager::get('default');

		//flow wise tables
		$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();

		$appl_cnt = 0;
		$grant_cnt = 0;
		$rev_cnt = 0;

		$i=1;
		foreach($flow_wise_tables as $each_flow){

			$grnt_pdf_version = '1';
			if($i==2){
				$grnt_pdf_version = '2';
			}


			$finalSubmitTable = $each_flow['application_form'];
			$finalSubmitTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$finalSubmitTable))));//converted to normal table name

			$grantTable = $each_flow['grant_pdf'];
			$grantTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$grantTable))));//converted to normal table name

			$paymentTable = $each_flow['payment'];
			$paymentTable = strtolower(implode('_',array_filter(preg_split('/(?=[A-Z])/',$paymentTable))));//converted to normal table name

			//if appl type is new then apply is old condition =='no', take only fresh appl count
			if($i==1){

				//for applications count
				$stmt1 = $conn->execute("select f.customer_id,f.total_charges from dmi_firms as f
									inner join $finalSubmitTable as fs on fs.customer_id = f.customer_id AND fs.status = 'pending'
									where f.state = '$state_id' AND f.delete_status IS null AND f.is_already_granted = 'no'
									group by f.customer_id,f.total_charges");
				$appl_arr = $stmt1 ->fetchAll('assoc');
				$appl_cnt = count($appl_arr)+$appl_cnt;

				//for grant count
				$stmt2 = $conn->execute("select f.customer_id from dmi_firms as f
									inner join $grantTable as gt on gt.customer_id = f.customer_id AND gt.pdf_version = '$grnt_pdf_version'
									where f.state = '$state_id' AND f.delete_status IS null AND f.is_already_granted = 'no'
									group by f.customer_id");
				$grant_arr = $stmt2 ->fetchAll('assoc');
				$grant_cnt = count($grant_arr)+$grant_cnt;

				//for total revenue
				$stmt3 = $conn->execute("select f.customer_id,pt.amount_paid from dmi_firms as f
									inner join $paymentTable as pt on pt.customer_id = f.customer_id AND pt.payment_confirmation = 'confirmed'
									where f.state = '$state_id' AND f.delete_status IS null AND f.is_already_granted = 'no'");
				$rev_arr = $stmt3 ->fetchAll('assoc');

				foreach($rev_arr as $each){

					$rev_cnt = $rev_cnt+$each['amount_paid'];
				}

			}else{

				//for applications count
				$stmt1 = $conn->execute("select f.customer_id from dmi_firms as f
									inner join $finalSubmitTable as fs on fs.customer_id = f.customer_id AND fs.status = 'pending'
									where f.state = '$state_id' AND f.delete_status IS null");
				$appl_arr = $stmt1 ->fetchAll('assoc');
				$appl_cnt = count($appl_arr)+$appl_cnt;

				//for grant count
				$stmt2 = $conn->execute("select f.customer_id from dmi_firms as f
									inner join $grantTable as gt on gt.customer_id = f.customer_id AND gt.pdf_version = '$grnt_pdf_version'
									where f.state = '$state_id' AND f.delete_status IS null
									group by f.customer_id");
				$grant_arr = $stmt2 ->fetchAll('assoc');
				$grant_cnt = count($grant_arr)+$grant_cnt;

				//for total revenue
				$stmt3 = $conn->execute("select f.customer_id,pt.amount_paid from dmi_firms as f
									inner join $paymentTable as pt on pt.customer_id = f.customer_id AND pt.payment_confirmation = 'confirmed'
									where f.state = '$state_id' AND f.delete_status IS null");
				$rev_arr = $stmt3 ->fetchAll('assoc');

				foreach($rev_arr as $each){

					$rev_cnt = $rev_cnt+$each['amount_paid'];
				}

			}

			$i=$i+1;
		}

		$result_arr = array('appl'=>$appl_cnt,'grant'=>$grant_cnt,'revenue'=>$rev_cnt);

		echo json_encode($result_arr);
		exit;
	}


	public function afterFilter(EventInterface $event)
	{
		if ($this->getRequest()->is('ajax')) {

			//exit;
		}
	}
	
	public function allManuals(){}

	//this function is added to set session for visiting tabs and sub tabs from pending status window
	public function setSessionForStatusTabsClick(){
		$this->Session->write('listFor',$_POST['listFor']);
		$this->Session->write('listSubTab',$_POST['listSubTab']);
		exit;
	}


//phase 2 new code till above


}



?>
