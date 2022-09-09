<?php
//session_start();
//App::uses('EsignController','Controller');
//App::uses('AppController', 'Controller');

namespace App\Controller;

use Cake\Event\Event;
use App\Network\Email\Email;
use Cake\ORM\Entity;
use Symfony\Component\VarDumper\Exception\ThrowingCasterException;

class RolesController extends AppController{

	var $name = 'Roles';


	public function beforeFilter($event) {
		parent::beforeFilter($event);

		$this->viewBuilder()->setHelpers(['Form','Html','Time']);
		$this->viewBuilder()->setLayout('admin_dashboard');

		$this->loadComponent('Createcaptcha');
		$this->loadComponent('Customfunctions');
		$this->loadComponent('Randomfunctions');
		$this->loadComponent('Mastertablecontent');

	}

	//authenticate for Users management windows
	public function authenicateUser() {

		//check user role for access
		$this->loadModel('DmiUserRoles');
		$user_access = $this->DmiUserRoles->find('all',array('conditions'=>array('set_roles'=>'yes','user_email_id IS'=>$this->Session->read('username'))))->first();
		if (!empty($user_access)) {
			//proceed
		} else {
			echo "Sorry.. You don't have permission to view this page";
			exit();
		}

	}

	//set user roles start
	public function setRoles() {

		//authenitcate user
		$this->authenicateUser();
		//loadModels
		$this->loadModel('DmiUsers');
		$this->loadModel('DmiUserRoles');

		// find login user division type to make condition as per division and find user list as per user division type
		$login_user = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
		$user_division = $login_user['division'];

		if ($user_division == 'DMI') {
			 $conditions = array('division'=>'DMI');
		} elseif ($user_division == 'LMIS') {
			 $conditions = array('division'=>'LMIS');
		} else { 
			$conditions = array(); 
		}

		$user_list = $this->DmiUsers->find('list',array('valueField'=>'email','conditions'=>$conditions))->toArray();

		// Check entry of dy_ama, jt_ama, ama into user_roles tabels for duplicate set roles for dy_ama, jt_ama, ama into user_roles
		$dyama_jtama_ama_set_roles_details = $this->Customfunctions->alreadySetDyamaJtamaAma();

		$dyama_set_role_detail = $dyama_jtama_ama_set_roles_details[0];
		$jtama_set_role_detail = $dyama_jtama_ama_set_roles_details[1];
		$ama_set_role_detail = $dyama_jtama_ama_set_roles_details[2];

		$user_email_list = array();

		if (!empty($user_list)) {   // check user_list empty or not
			$i=1;
			foreach ($user_list as $user_email) {

				$find_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('user_email_id IS'=>$user_email)))->first();

				if (empty($find_user)) {

					$user_email_list[$i] = $user_email;
					$i=$i+1;
				}
			}
		}

		$find_available_user =array();
		
		if (!empty($user_email_list)) {
			$find_available_user = $this->DmiUsers->find('list',array('keyfield'=>'id','valueField'=>'email','conditions'=>array('email IN'=>$user_email_list, 'status' => 'active')))->toArray();
		}

		asort($find_available_user);//to sort array alphabeticaly

		//added below loop //for email encoding
		$i=1;
		$newArray = array();
		foreach ($find_available_user as $key => $emailId) {

			$newArray[$key] = base64_decode($emailId);
			$i=$i+1;
		}
		$find_available_user = $newArray;
		//till here

		$this->set(compact('dyama_set_role_detail','jtama_set_role_detail','ama_set_role_detail','find_available_user'));

		// set variables to show popup messages from view file
		$message = '';
		$redirect_to = '';

		if (null!==($this->request->getData('set_roles_btn'))) {

			$postData = $this->request->getData();

			//take user id from input and find email_id
			$fetch_user_emai_id = $this->DmiUsers->find('all',array('conditions'=>array('id IS'=>$postData['user_list'])))->first();
			$user_email_id = $fetch_user_emai_id['email']; //for email encoding
			$selected_user_division = $fetch_user_emai_id['division'];

			// Create array of users roles set that set by admin user for particule user
			$user_roles_details = array();
			$i=0;

			if ($postData['add_user'] == 1) {
				$add_user = 'yes';
				$user_roles_details[$i] = 'add_user';
				$i= $i + 1;
			} else { $add_user = 'no'; }

			if ($postData['page_draft'] == 1) {
				$page_draft = 'yes';
				$user_roles_details[$i] = 'page_draft';
				$i= $i + 1;
			} else { $page_draft = 'no'; }

			if ($postData['page_publish'] == 1) {
				$page_publish = 'yes';
				$user_roles_details[$i] = 'page_publish';
				$i= $i + 1;
			} else { $page_publish = 'no'; }

			if ($postData['menus'] == 1) {
				$menus = 'yes';
				$user_roles_details[$i] = 'menus';
				$i= $i + 1;
			} else { $menus = 'no'; }

			if ($postData['mo_smo_inspection'] == 1) {
				$mo_smo_inspection = 'yes';
				$user_roles_details[$i] = 'mo_smo_inspection';
				$i= $i + 1;
			} else { $mo_smo_inspection = 'no'; }

			if ($postData['io_inspection'] == 1) {
				$io_inspection = 'yes';
				$user_roles_details[$i] = 'io_inspection';
				$i= $i + 1;
			} else { $io_inspection = 'no'; }

			if ($postData['ro_inspection'] == 1) {
				$ro_inspection = 'yes';
				$user_roles_details[$i] = 'ro_inspection';
				$i= $i + 1;
			} else { $ro_inspection = 'no'; }

			if ($postData['allocation_mo_smo'] == 1) {
				$allocation_mo_smo = 'yes';
				$user_roles_details[$i] = 'allocation_mo_smo';
				$i= $i + 1;
			} else { $allocation_mo_smo = 'no'; }

			if ($postData['allocation_io'] == 1) {
				$allocation_io = 'yes';
				$user_roles_details[$i] = 'allocation_io';
				$i= $i + 1;
			} else { $allocation_io = 'no'; }

			if ($postData['reallocation'] == 1) {
				$reallocation = 'yes';
				$user_roles_details[$i] = 'reallocation';
				$i= $i + 1;
			} else { $reallocation = 'no'; }

			if ($postData['form_verification_home'] == 1) {
				$form_verification_home = 'yes';
				$user_roles_details[$i] = 'form_verification_home';
				$i= $i + 1;
			} else { $form_verification_home = 'no'; }

			if ($postData['allocation_home'] == 1) {
				$allocation_home = 'yes';
				$user_roles_details[$i] = 'allocation_home';
				$i= $i + 1;
			} else { $allocation_home = 'no'; }

			if ($postData['set_roles'] == 1) {
				$set_roles = 'yes';
				$user_roles_details[$i] = 'set_roles';
				$i= $i + 1;
			} else { $set_roles = 'no'; }

			if ($postData['file_upload'] == 1) {
				$file_upload = 'yes';
				$user_roles_details[$i] = 'file_upload';
				$i= $i + 1;
			} else { $file_upload = 'no'; }

			if ($postData['dy_ama'] == 1) {
				$dy_ama = 'yes';
				$user_roles_details[$i] = 'dy_ama';
				$i= $i + 1;
			} else { $dy_ama = 'no'; }

			if ($postData['ho_mo_smo'] == 1) {
				$ho_mo_smo = 'yes';
				$user_roles_details[$i] = 'ho_mo_smo';
				$i= $i + 1;
			} else { $ho_mo_smo = 'no'; }

			if ($postData['jt_ama'] == 1) {
				$jt_ama = 'yes';
				$user_roles_details[$i] = 'jt_ama';
				$i= $i + 1;
			} else { $jt_ama = 'no'; }

			if ($postData['ama'] == 1) {
				$ama = 'yes';
				$user_roles_details[$i] = 'ama';
				$i= $i + 1;
			} else { $ama = 'no'; }

			if ($postData['allocation_ho_mo_smo'] == 1) {
				$allocation_ho_mo_smo = 'yes';
				$user_roles_details[$i] = 'allocation_ho_mo_smo';
				$i= $i + 1;
			} else { $allocation_ho_mo_smo = 'no'; }

			if ($postData['masters'] == 1) {
				$masters = 'yes';
				$user_roles_details[$i] = 'masters';
				$i= $i + 1;
			} else { $masters = 'no'; }

			if ($postData['super_admin'] == 1) {
				$super_admin = 'yes';
				$user_roles_details[$i] = 'super_admin';
				$i= $i + 1;
			} else { $super_admin = 'no'; }

			if ($postData['renewal_verification'] == 1) {
				$renewal_verification = 'yes';
				$user_roles_details[$i] = 'renewal_verification';
				$i= $i + 1;
			} else { $renewal_verification = 'no'; }

			if ($postData['renewal_allocation'] == 1) {
				$renewal_allocation = 'yes';
				$user_roles_details[$i] = 'renewal_allocation';
				$i= $i + 1;
			} else { $renewal_allocation = 'no'; }

			//Create new roles view_reports to view all reports (Done by pravin 15-09-2017)
			if ($postData['view_reports'] == 1) {
				$view_reports = 'yes';
				$user_roles_details[$i] = 'view_reports';
				$i= $i + 1;
			} else { $view_reports = 'no'; }

			//Create new role "PAO" for certificate payment verified (Done by pravin 05-10-2017)
			if ($postData['pao'] == 1) {
				$pao = 'yes';
				$user_roles_details[$i] = 'pao';
				$i= $i + 1;
			} else { $pao = 'no'; }

			//Create new role "once_update_permission" to show aadhar updation request window to admin
			// on 03-02-2018 by Amol
			/*	if ($postData['once_update_permission'] == 1) {
				$once_update_permission = 'yes';
				$user_roles_details[$i] = 'once_update_permission';
				$i= $i + 1;
			} else { $once_update_permission = 'no'; }
			*/
			//created new role to show Old application data entry window to admin user
			// on 07-02-2018 by Amol
			if ($postData['old_appln_data_entry'] == 1) {
				$old_appln_data_entry = 'yes';
				$user_roles_details[$i] = 'old_appln_data_entry';
				$i= $i + 1;
			} else { $old_appln_data_entry = 'no'; }

			//new role for feedback menu, added on 12-06-2018 by Amol
			if ($postData['feedbacks'] == 1) {
				$feedbacks = 'yes';
				$user_roles_details[$i] = 'feedbacks';
				$i= $i + 1;
			} else { $feedbacks = 'no'; }

			//created new role to for SO inspection
			if ($postData['so_inspection'] == 1) {
				$so_inspection = 'yes';
				$user_roles_details[$i] = 'so_inspection';
				$i= $i + 1;
			} else { $so_inspection = 'no'; }

			/*	//created new role to for SMD inspection
			// on 01-03-2018 by Amol
			if ($postData['smd_inspection'] == 1) {
				$smd_inspection = 'yes';
				$user_roles_details[$i] = 'smd_inspection';
				$i= $i + 1;
			} else { $smd_inspection = 'no'; }
			*/


			/* Add new role 'unlock_user' for unlock user functionality, Change on 06-12-2018 - By Pravin Bhakare - Suggested by Navin Sir
			   Reason : In current system their is no role for any authorised user, to assign the unlock user activities  */
			if ($postData['unlock_user'] == 1) {
				$unlock_user = 'yes';
				$user_roles_details[$i] = 'unlock_user';
				$i= $i + 1;
			} else { $unlock_user = 'no'; }

			//created this new role for transfer of application to another RO
			if ($postData['transfer_appl'] == 1) {
				$transfer_appl = 'yes';
				$user_roles_details[$i] = 'transfer_appl';
				$i= $i + 1;
			} else { $transfer_appl = 'no'; }

			//special role added for site inspection of Printing Press
			if ($postData['site_inspection_pp'] == 1) {
				$site_inspection_pp = 'yes';
				$user_roles_details[$i] = 'site_inspection_pp';
				$i= $i + 1;
			} else { $site_inspection_pp = 'no'; }
			//special role added for SO in-charge to grant Printing press application
			if ($postData['so_grant_pp'] == 1) {
				$so_grant_pp = 'yes';
				$user_roles_details[$i] = 'so_grant_pp';
				$i= $i + 1;
			} else { $so_grant_pp = 'no'; }


			// Start LMIS Role List

			if ($postData['sample_inward'] == 1) {
				$sample_inward = 'yes';
				$user_roles_details[$i] = 'sample_inward';
				$i= $i + 1;
			} else { $sample_inward = 'no'; }

			if ($postData['sample_forward'] == 1) {
				$sample_forward = 'yes';
				$user_roles_details[$i] = 'sample_forward';
				$i= $i + 1;
			} else { $sample_forward = 'no'; }

			if ($postData['sample_allocated'] == 1) {
				$sample_allocated = 'yes';
				$user_roles_details[$i] = 'sample_allocated';
				$i= $i + 1;
			} else { $sample_allocated = 'no'; }

			if ($postData['generate_inward_letter'] == 1) {
				$generate_inward_letter = 'yes';
				$user_roles_details[$i] = 'generate_inward_letter';
				$i= $i + 1;
			} else { $generate_inward_letter = 'no'; }

			if ($postData['sample_testing_progress'] == 1) {
				$sample_testing_progress = 'yes';
				$user_roles_details[$i] = 'sample_testing_progress';
				$i= $i + 1;
			} else { $sample_testing_progress = 'no'; }

			if ($postData['sample_result_approval'] == 1) {
				$sample_result_approval = 'yes';
				$user_roles_details[$i] = 'sample_result_approval';
				$i= $i + 1;
			} else { $sample_result_approval = 'no'; }

			if ($postData['finalized_sample'] == 1) {
				$finalized_sample = 'yes';
				$user_roles_details[$i] = 'finalized_sample';
				$i= $i + 1;
			} else { $finalized_sample = 'no'; }

			if ($postData['administration'] == 1) {
				$administration = 'yes';
				$user_roles_details[$i] = 'administration';
				$i= $i + 1;
			} else { $administration = 'no'; }

			if ($postData['reports'] == 1) {
				$reports = 'yes';
				$user_roles_details[$i] = 'reports';
				$i= $i + 1;
			} else { $reports = 'no'; }

			if ($postData['dashboard'] == 1) {
				$dashboard = 'yes';
				$user_roles_details[$i] = 'dashboard';
				$i= $i + 1;
			} else { $dashboard = 'no'; }

			if ($postData['out_forward'] == 1) {
				$out_forward = 'yes';
				$user_roles_details[$i] = 'dashboard';
				$i= $i + 1;
			} else { $out_forward = 'no'; }

			if ($postData['user_flag'] == "RO") {
				$RO = 'yes';
				$user_roles_details[$i] = 'user_flag';
				$i= $i + 1;
			} else { $RO = 'no'; }

			if ($postData['user_flag'] == "SO") {
				$SO = 'yes';
				$user_roles_details[$i] = 'user_flag';
				$i= $i + 1;
			} else { $SO = 'no'; }

			if ($postData['user_flag'] == "RAL") {
				$RAL = 'yes';
				$user_roles_details[$i] = 'user_flag';
				$i= $i + 1;
			} else { $RAL = 'no'; }

			if ($postData['user_flag'] == "CAL") {
				$CAL = 'yes';
				$user_roles_details[$i] = 'user_flag';
				$i= $i + 1;
			} else { $CAL = 'no'; }

			if ($postData['user_flag'] == "HO") {
				$HO = 'yes';
				$user_roles_details[$i] = 'user_flag';
				$i= $i + 1;
			} else { $HO = 'no'; }

			if (!empty($postData['user_flag'])) {
				$post_input_request = $postData['user_flag'];
				$user_flag = $this->Customfunctions->radioButtonInputCheck($post_input_request);
				$user_roles_details[$i] = $postData['user_flag'];
				$i= $i + 1;
				if ($user_flag == null) { return false;}
			} else {
				$user_flag=null;
			}

			// End LMIS Role List

			//check some validation if HO roles checked to assigned // and update HO allocation table
			$this->Customfunctions->ifHoLevelRoleAssigned($user_email_id,$dy_ama,$jt_ama,$ama);

			//create string of set user roles
			$user_roles_details =implode(', ',$user_roles_details);

			$DmiUserRolesEntity = $this->DmiUserRoles->newEntity(array(
				'user_email_id'=>$user_email_id,
				'add_user'=>$add_user,
				'page_draft'=>$page_draft,
				'page_publish'=>$page_publish,
				'menus'=>$menus,
				'file_upload'=>$file_upload,
				'mo_smo_inspection'=>$mo_smo_inspection,
				'io_inspection'=>$io_inspection,
				'allocation_mo_smo'=>$allocation_mo_smo,
				'allocation_io'=>$allocation_io,
				'reallocation'=>$reallocation,
				'form_verification_home'=>$form_verification_home,
				'allocation_home'=>$allocation_home,
				'set_roles'=>$set_roles,
				'ro_inspection'=>$ro_inspection,
				'dy_ama'=>$dy_ama,
				'ho_mo_smo'=>$ho_mo_smo,
				'jt_ama'=>$jt_ama,
				'ama'=>$ama,
				//'allocation_dy_ama'=>$allocation_dy_ama,
				'allocation_ho_mo_smo'=>$allocation_ho_mo_smo,
				//'allocation_jt_ama'=>$allocation_jt_ama,
				//'allocation_ama'=>$allocation_ama,
				'masters'=>$masters,
			//	'super_admin'=>$super_admin, //intensionally commented as there should be only default single super admins on 06-06-2022
				'renewal_verification'=>$renewal_verification,
				'renewal_allocation'=>$renewal_allocation,
				'view_reports'=>$view_reports,
				'pao'=>$pao,
				'old_appln_data_entry'=>$old_appln_data_entry,
				'so_inspection'=>$so_inspection,
				//'smd_inspection'=>$smd_inspection,
				'feedbacks'=>$feedbacks,
				'unlock_user'=>$unlock_user,
				'transfer_appl'=>$transfer_appl,
				'inspection_pp'=>$site_inspection_pp, //new
				'so_grant_pp'=>$so_grant_pp, //new


				// Start LMIS Role List
				'sample_inward'=>$sample_inward,
				'sample_forward'=>$sample_forward,
				'generate_inward_letter'=>$generate_inward_letter,
				'sample_allocated'=>$sample_allocated,
				'sample_testing_progress'=>$sample_testing_progress,
				'sample_result_approval'=>$sample_result_approval,
				'finalized_sample'=>$finalized_sample,
				'administration'=>$administration,
				'reports'=>$reports,
				'dashboard'=>$dashboard,
				'out_forward'=>$out_forward,
				'RO'=>$RO,
				'SO'=>$SO,
				'RAL'=>$RAL,
				'CAL'=>$CAL,
				'HO'=>$HO,
				'user_flag'=>$user_flag,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s')

			));

			 if ($this->DmiUserRoles->save($DmiUserRolesEntity)) {

				// create entry set roles to user in user_roles_managment_log tables
				$this->loadmodel('DmiUserRolesManagmentLogs');
				$RolesManagmentLogsEntity = $this->DmiUserRolesManagmentLogs->newEntity(array(
					'user_roles'=>$user_roles_details,
					'by_user'=>$this->Session->read('username'),
					'to_user'=>$user_email_id,
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s'),
					'action'=>'add',
					'add_roles'=>$user_roles_details,
					'user_division'=>$selected_user_division//new field
				));

				if ($this->DmiUserRolesManagmentLogs->save($RolesManagmentLogsEntity)) {

					//get the user name
					$userFullName = $this->DmiUsers->find()->select(['f_name','l_name'])->where(['email' => $user_email_id])->first();
					$name = $userFullName['f_name']." ".$userFullName['l_name'];

					//Added this call to save the user action log on 22-02-2022
					$this->Customfunctions->saveActionPoint('Set Role','Success');
					$message = 'Roles are set successfully for the user '.$name.'('.base64_decode($user_email_id).')';
					$redirect_to = 'set-roles';
				}
			}
		}

		// set variables to show popup messages from view file
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);


	}
	//set user roles end


	//this function is called through ajax from set/edit roles to compare user office type and send response
	//to alert user before selecting user flag
	public function checkOfficeType() {

		$this->autoRender = false;
		$this->loadModel('DmiUsers');
		$this->loadModel('DmiUserRoles');
		$user_id = $_POST['user_id'];
		$office_type_val = $_POST['office_type_val'];

		//get posted to office
		$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('id IS'=>$user_id)))->first();
		$posted_office = $get_user_details['posted_ro_office'];

		//find another user with this posted office //added status condition on 28-07-2022 by Amol
		$get_other_user = $this->DmiUsers->find('all',array('conditions'=>array('posted_ro_office IS'=>$posted_office, 'id IS NOT'=>$user_id, 'status'=>'active')))->toArray();

		$user_flag = null;
		foreach ($get_other_user as $each) {

			//then find user_flag in role table of this user
			$get_user_flag = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$each['email'])))->first();

			if (!empty($get_user_flag)) {
				$user_flag = $get_user_flag['user_flag'];
				break;//break loop
			}
		}
		echo '"'.$user_flag.'"';
		exit;
	}



	// find user division type// called through ajax
	public function userDivisionType() {
		$this->autoRender = false;

		$user_id = $_POST['user_id'];
		$user_division_value = $this->DmiUsers->find('all',array('fields'=>'division','conditions'=>array('id IS'=>$user_id)))->first();
		?><label style="float:right; font-weight:bold;margin-top: 8px;margin-right: 50px;" id="user_type_text">User Type : <?php if ($user_division_value['division']=='LMIS') {echo 'LIMS';} else {echo $user_division_value['division'];} ?></label><?php
		exit;
	}



	//Edit user roles start


	public function editRoles() {

		//authenitcate user
		$this->authenicateUser();

		//load Models
		$this->loadModel('DmiUsers');
		$this->loadModel('DmiUserRoles');
		$this->loadModel('DmiRoOffices');
		
		//default variables used in view file to manage script
		//cut from view file and added on 28-07-2022 by Amol
		$dyama_set_role_detail = "";
		$jtama_set_role_detail = "";
		$ama_set_role_detail = "";
		$ro_office_details = "";
		$user_id = "";
		$ro_office = "";
		$so_office_details = "";
		$so_office = "";
		$mo_allocated_running_application_list = "";
		$mo_renewal_allocated_running_application_list = "";
		$io_allocated_running_application_list = "";
		$io_renewal_allocated_running_application_list = "";
		$ho_mo_allocated_running_application_list = "";
		$user_division_type = "";
		$pao_pending_works = "";
		$this->set(compact('dyama_set_role_detail','jtama_set_role_detail','ama_set_role_detail','ro_office_details','user_id','ro_office',
							'so_office_details','so_office','mo_allocated_running_application_list','mo_renewal_allocated_running_application_list',
							'io_allocated_running_application_list','io_renewal_allocated_running_application_list','ho_mo_allocated_running_application_list',
							'user_division_type','pao_pending_works'));

		// find login user division type to make condition as per division and find user list as per user division type
		$login_user = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
		$user_division = $login_user['division'];
		if ($user_division == 'DMI') {

		$conditions = array('division'=>'DMI');
		} elseif ($user_division == 'LMIS') {
			$conditions = array('division'=>'LMIS');
		} else {
			$conditions = array();
		}
		$user_id_list = $this->DmiUsers->find('list',array('valueField'=>'email','conditions'=>$conditions))->toArray();

		//$user_list = $this->Dmi_user->find('list',array('fields'=>'email'));


		// Check entry of dy_ama, jt_ama, ama into user_roles tabels for duplicate set roles for dy_ama, jt_ama, ama into user_roles
		// Done by pravin 30-08-2017
		$dyama_jtama_ama_set_roles_details = $this->Customfunctions->alreadySetDyamaJtamaAma();

		$dyama_set_role_detail = $dyama_jtama_ama_set_roles_details[0];
		$jtama_set_role_detail = $dyama_jtama_ama_set_roles_details[1];
		$ama_set_role_detail = $dyama_jtama_ama_set_roles_details[2];

		$user_email_list = array();
		if (!empty($user_id_list)) {   // check user_id_list empty or not (done by pravin 21/11/2017)

			$i=1;
			foreach ($user_id_list as $user_email) {

				$find_user = $this->DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('user_email_id IS'=>$user_email)))->first();
				if (!empty($find_user)) {
					$user_email_list[$i] = $user_email;
					$i=$i+1;
				}
			}
		}

		$find_available_user = $this->DmiUsers->find('list',array('valueField'=>'email','conditions'=>array('email IN'=>$user_email_list,'status' => 'active')))->toArray();
		asort($find_available_user);//to sort array alphabeticaly

		//added below loop //for email encoding
		$i=1;
		$newArray = array();
		foreach ($find_available_user as $key => $emailId) {

			$newArray[$key] = base64_decode($emailId);
			$i=$i+1;
		}
		$find_available_user = $newArray;
		//till here

		//temp. showing blank
		$assigned_old_roles = '';
		$user_division_type = null;
		$this->set(compact('user_division_type','assigned_old_roles','find_available_user','ama_set_role_detail','jtama_set_role_detail','dyama_set_role_detail'));

		if (null!==($this->request->getData('show_roles'))) {

			//Check pao pending works before remove pao role from any user, Change on 14-12-2018 , By Pravin Bhakare
			$this->set('pao_pending_works',$this->Randomfunctions->checkPaoPendingWorks($this->request->getData('user_list')));

			$user_list = $this->DmiUsers->find('all',array('conditions'=>array('id IS'=>$this->request->getData('user_list'))))->first();

			$assigned_old_roles = $this->DmiUserRoles->find('all',array('conditions'=>array('user_email_id IS'=>$user_list['email'])))->toArray();
			$this->set('assigned_old_roles',$assigned_old_roles);

			$user_id = $user_list['email'];
			$user_name = $user_list['f_name'].' '.$user_list['l_name'];

			// find user division type (done by pravin 21/11/2017)
			$user_division_type = $user_list['division'];

			$this->set('user_id',$user_id);
			$this->set('user_name',$user_name);
			$this->set('user_division_type',$user_division_type);


			//Check user id in SO office from office table before remove RO in-chanrge role from user
			// BY pravin 01/9/2017
			$ro_office_details = $this->DmiRoOffices->find('all',array('conditions'=>array('ro_email_id IS'=>$user_list['email'],'delete_status IS NULL','office_type'=>'RO')))->toArray();

			if (!empty($ro_office_details)) {
				$i=0;
				foreach ($ro_office_details as $ro_office) {

					$ro_office_name[$i] = $ro_office['ro_office'];
					$i = $i+1;
				}

				$ro_office = implode(', ',$ro_office_name);
				$this->set('ro_office',$ro_office);
				$this->set('ro_office_details',$ro_office_details);

			}

			//Check user id in SO office from office table before remove SO in-chanrge role from user
			//Amol on 11-05-2021
			$so_office_details = $this->DmiRoOffices->find('all',array('conditions'=>array('ro_email_id IS'=>$user_list['email'],'delete_status IS NULL','office_type'=>'SO')))->toArray();

			if (!empty($so_office_details)) {
				$i=0;
				foreach ($so_office_details as $so_office) {

					$so_office_name[$i] = $so_office['ro_office'];
					$i = $i+1;
				}

				$so_office = implode(', ',$so_office_name);
				$this->set('so_office',$so_office);
				$this->set('so_office_details',$so_office_details);

			}


			//check user id in allocation and renewal allocation table and application grant table before remove MO/SMO role from user

			//get flow wise tables
			$this->loadModel('DmiFlowWiseTablesLists');
			$flow_wise_tables = $this->DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IN'=>$this->Session->read('applTypeArray')),'order'=>'id ASC'))->toArray();

			//for MO work
			$this->Randomfunctions->checkPendingWorkForMoIo($flow_wise_tables,'MO',$user_id);
			//for IO work
			$this->Randomfunctions->checkPendingWorkForMoIo($flow_wise_tables,'IO',$user_id);
			//for HO MO
			$this->Randomfunctions->checkPendingWorkForHoMo($flow_wise_tables,$user_list);

		}

		// set variables to show popup messages from view file
		$message = '';
		$message_theme = '';
		$redirect_to = '';

		if (null!==($this->request->getData('update_roles_btn'))) {

			//take user id from input and find email_id

			$fetch_user_emai_id = $this->DmiUsers->find('all',array('fields'=>array('division','email'), 'conditions'=>array('id IS'=>$this->request->getData('user_list'))))->first();
			$user_email_id = $fetch_user_emai_id['email'];
			$selected_user_division = $fetch_user_emai_id['division'];

			// Create array of users roles set that set by admin user for particule user
			$user_roles_details = array();
			$i=0;

			if ($this->request->getData('add_user') == 1) {
				$add_user = 'yes';
				$user_roles_details[$i] = 'add_user';
				$i= $i + 1;
			} else { $add_user = 'no'; }

			if ($this->request->getData('page_draft') == 1) {
				$page_draft = 'yes';
				$user_roles_details[$i] = 'page_draft';
				$i= $i + 1;
			} else { $page_draft = 'no'; }

			if ($this->request->getData('page_publish') == 1) {
				$page_publish = 'yes';
				$user_roles_details[$i] = 'page_publish';
				$i= $i + 1;
			} else { $page_publish = 'no'; }

			if ($this->request->getData('menus') == 1) {
				$menus = 'yes';
				$user_roles_details[$i] = 'menus';
				$i= $i + 1;
			} else { $menus = 'no'; }

			if ($this->request->getData('mo_smo_inspection') == 1) {
				$mo_smo_inspection = 'yes';
				$user_roles_details[$i] = 'mo_smo_inspection';
				$i= $i + 1;
			} else { $mo_smo_inspection = 'no'; }

			if ($this->request->getData('io_inspection') == 1) {
				$io_inspection = 'yes';
				$user_roles_details[$i] = 'io_inspection';
				$i= $i + 1;
			} else { $io_inspection = 'no'; }

			if ($this->request->getData('ro_inspection') == 1) {
				$ro_inspection = 'yes';
				$user_roles_details[$i] = 'ro_inspection';
				$i= $i + 1;
			} else { $ro_inspection = 'no'; }

			if ($this->request->getData('allocation_mo_smo') == 1) {
				$allocation_mo_smo = 'yes';
				$user_roles_details[$i] = 'allocation_mo_smo';
				$i= $i + 1;
			} else { $allocation_mo_smo = 'no'; }

			if ($this->request->getData('allocation_io') == 1) {
				$allocation_io = 'yes';
				$user_roles_details[$i] = 'allocation_io';
				$i= $i + 1;
			} else { $allocation_io = 'no'; }

			if ($this->request->getData('reallocation') == 1) {
				$reallocation = 'yes';
				$user_roles_details[$i] = 'reallocation';
				$i= $i + 1;
			} else { $reallocation = 'no'; }

			if ($this->request->getData('form_verification_home') == 1) {
				$form_verification_home = 'yes';
				$user_roles_details[$i] = 'form_verification_home';
				$i= $i + 1;
			} else { $form_verification_home = 'no'; }

			if ($this->request->getData('allocation_home') == 1) {
				$allocation_home = 'yes';
				$user_roles_details[$i] = 'allocation_home';
				$i= $i + 1;
			} else { $allocation_home = 'no'; }

			if ($this->request->getData('set_roles') == 1) {
				$set_roles = 'yes';
				$user_roles_details[$i] = 'set_roles';
				$i= $i + 1;
			} else { $set_roles = 'no'; }

			if ($this->request->getData('file_upload') == 1) {
				$file_upload = 'yes';
				$user_roles_details[$i] = 'file_upload';
				$i= $i + 1;
			} else { $file_upload = 'no'; }

			if ($this->request->getData('dy_ama') == 1) {
				$dy_ama = 'yes';
				$user_roles_details[$i] = 'dy_ama';
				$i= $i + 1;
			} else { $dy_ama = 'no'; }

			if ($this->request->getData('ho_mo_smo') == 1) {
				$ho_mo_smo = 'yes';
				$user_roles_details[$i] = 'ho_mo_smo';
				$i= $i + 1;
			} else { $ho_mo_smo = 'no'; }

			if ($this->request->getData('jt_ama') == 1) {
				$jt_ama = 'yes';
				$user_roles_details[$i] = 'jt_ama';
				$i= $i + 1;
			} else { $jt_ama = 'no'; }

			if ($this->request->getData('ama') == 1) {
				$ama = 'yes';
				$user_roles_details[$i] = 'ama';
				$i= $i + 1;
			} else { $ama = 'no'; }


			/*	if ($this->request->getData('allocation_dy_ama') == 1) {
				$allocation_dy_ama = 'yes';
				$user_roles_details[$i] = 'allocation_dy_ama';
				$i= $i + 1;
			} else { $allocation_dy_ama = 'no'; }
			*/
			if ($this->request->getData('allocation_ho_mo_smo') == 1) {
				$allocation_ho_mo_smo = 'yes';
				$user_roles_details[$i] = 'allocation_ho_mo_smo';
				$i= $i + 1;
			} else { $allocation_ho_mo_smo = 'no'; }

			/*	if ($this->request->getData('allocation_jt_ama') == 1) {
				$allocation_jt_ama = 'yes';
				$user_roles_details[$i] = 'allocation_jt_ama';
				$i= $i + 1;
			} else { $allocation_jt_ama = 'no'; }
			*/
			/*	if ($this->request->getData('allocation_ama') == 1) {
				$allocation_ama = 'yes';
				$user_roles_details[$i] = 'allocation_ama';
				$i= $i + 1;
			} else { $allocation_ama = 'no'; }
			*/
			if ($this->request->getData('masters') == 1) {
				$masters = 'yes';
				$user_roles_details[$i] = 'masters';
				$i= $i + 1;
			} else { $masters = 'no'; }

			if ($this->request->getData('super_admin') == 1) {
				$super_admin = 'yes';
				$user_roles_details[$i] = 'super_admin';
				$i= $i + 1;
			} else { $super_admin = 'no'; }

			if ($this->request->getData('renewal_verification') == 1) {
				$renewal_verification = 'yes';
				$user_roles_details[$i] = 'renewal_verification';
				$i= $i + 1;
			} else { $renewal_verification = 'no'; }

			if ($this->request->getData('renewal_allocation') == 1) {
				$renewal_allocation = 'yes';
				$user_roles_details[$i] = 'renewal_allocation';
				$i= $i + 1;
			} else { $renewal_allocation = 'no'; }

			//Create new roles view_reports to view all reports (Done by pravin 15-09-2017)
			if ($this->request->getData('view_reports') == 1) {
				$view_reports = 'yes';
				$user_roles_details[$i] = 'view_reports';
				$i= $i + 1;
			} else { $view_reports = 'no'; }

			//Create new roles view_reports to view all reports (Done by pravin 15-09-2017)
			if ($this->request->getData('pao') == 1) {
				$pao = 'yes';
				$user_roles_details[$i] = 'pao';
				$i= $i + 1;
			} else { $pao = 'no'; }

			//Create new role "once_update_permission" to show aadhar updation request window to admin
			// on 03-02-2018 by Amol
			/*	if ($this->request->getData('once_update_permission') == 1) {
				$once_update_permission = 'yes';
				$user_roles_details[$i] = 'once_update_permission';
				$i= $i + 1;
			} else { $once_update_permission = 'no'; }
			*/

			//created new role to show Old application data entry window to admin user
			// on 07-02-2018 by Amol
			if ($this->request->getData('old_appln_data_entry') == 1) {
				$old_appln_data_entry = 'yes';
				$user_roles_details[$i] = 'old_appln_data_entry';
				$i= $i + 1;
			} else { $old_appln_data_entry = 'no'; }

			//for new role Feedbacks in menus, added on 12-06-2018 by Amol
			if ($this->request->getData('feedbacks') == 1) {
				$feedbacks = 'yes';
				$user_roles_details[$i] = 'feedbacks';
				$i= $i + 1;
			} else { $feedbacks = 'no'; }

			//created new role for SO inspection
			// on 01-03-2018 by Amol
			//commented on 12-03-2018
			if ($this->request->getData('so_inspection') == 1) {
				$so_inspection = 'yes';
				$user_roles_details[$i] = 'so_inspection';
				$i= $i + 1;
			} else { $so_inspection = 'no'; }

			/* Add new role 'unlock_user' for unlock user functionality, Change on 06-12-2018 - By Pravin Bhakare - Suggested by Navin Sir
			   Reason : In current system their is no role for any authorised user, to assign the unlock user activities  */
			if ($this->request->getData('unlock_user') == 1) {
				$unlock_user = 'yes';
				$user_roles_details[$i] = 'unlock_user';
				$i= $i + 1;
			} else { $unlock_user = 'no'; }

			//created this new role for transfer of application to another RO
			if ($this->request->getData('transfer_appl') == 1) {
				$transfer_appl = 'yes';
				$user_roles_details[$i] = 'transfer_appl';
				$i= $i + 1;
			} else { $transfer_appl = 'no'; }

			//special role added for site inspection of Printing Press
			if ($this->request->getData('site_inspection_pp') == 1) {
				$site_inspection_pp = 'yes';
				$user_roles_details[$i] = 'site_inspection_pp';
				$i= $i + 1;
			} else { $site_inspection_pp = 'no'; }
			//special role added for SO in-charge to grant Printing press application
			if ($this->request->getData('so_grant_pp') == 1) {
				$so_grant_pp = 'yes';
				$user_roles_details[$i] = 'so_grant_pp';
				$i= $i + 1;
			} else { $so_grant_pp = 'no'; }

			//created new role for SMD inspection
			// on 01-03-2018 by Amol
			/*	if ($this->request->getData('smd_inspection') == 1) {
				$smd_inspection = 'yes';
				$user_roles_details[$i] = 'smd_inspection';
				$i= $i + 1;
			} else { $smd_inspection = 'no'; }
			*/

			// Start LMIS Role List  (Done By pravin 16/11/2017)
			//this condition added on 28-11-2017 by Amol


			if ($this->request->getData('sample_inward') == 1) {
				$sample_inward = 'yes';
				$user_roles_details[$i] = 'sample_inward';
				$i= $i + 1;
			} else { $sample_inward = 'no'; }

			if ($this->request->getData('sample_forward') == 1) {
				$sample_forward = 'yes';
				$user_roles_details[$i] = 'sample_forward';
				$i= $i + 1;
			} else { $sample_forward = 'no'; }

			if ($this->request->getData('sample_allocated') == 1) {
				$sample_allocated = 'yes';
				$user_roles_details[$i] = 'sample_allocated';
				$i= $i + 1;
			} else { $sample_allocated = 'no'; }

			if ($this->request->getData('generate_inward_letter') == 1) {
				$generate_inward_letter = 'yes';
				$user_roles_details[$i] = 'generate_inward_letter';
				$i= $i + 1;
			} else { $generate_inward_letter = 'no'; }

			if ($this->request->getData('sample_testing_progress') == 1) {
				$sample_testing_progress = 'yes';
				$user_roles_details[$i] = 'sample_testing_progress';
				$i= $i + 1;
			} else { $sample_testing_progress = 'no'; }

			if ($this->request->getData('sample_result_approval') == 1) {
				$sample_result_approval = 'yes';
				$user_roles_details[$i] = 'sample_result_approval';
				$i= $i + 1;
			} else { $sample_result_approval = 'no'; }

			if ($this->request->getData('finalized_sample') == 1) {
				$finalized_sample = 'yes';
				$user_roles_details[$i] = 'finalized_sample';
				$i= $i + 1;
			} else { $finalized_sample = 'no'; }

			if ($this->request->getData('administration') == 1) {
				$administration = 'yes';
				$user_roles_details[$i] = 'administration';
				$i= $i + 1;
			} else { $administration = 'no'; }

			if ($this->request->getData('reports') == 1) {
				$reports = 'yes';
				$user_roles_details[$i] = 'reports';
				$i= $i + 1;
			} else { $reports = 'no'; }

			if ($this->request->getData('dashboard') == 1) {
				$dashboard = 'yes';
				$user_roles_details[$i] = 'dashboard';
				$i= $i + 1;
			} else { $dashboard = 'no'; }

			if ($this->request->getData('out_forward') == 1) {
				$out_forward = 'yes';
				$user_roles_details[$i] = 'out_forward';
				$i= $i + 1;
			} else { $out_forward = 'no'; }

			if ($this->request->getData('user_flag') == "RO") {
				$RO = 'yes';
				$user_roles_details[$i] = 'user_flag';
				$i= $i + 1;
			} else { $RO = 'no'; }

			if ($this->request->getData('user_flag') == "SO") {
				$SO = 'yes';
				$user_roles_details[$i] = 'user_flag';
				$i= $i + 1;
			} else { $SO = 'no'; }

			if ($this->request->getData('user_flag') == "RAL") {
				$RAL = 'yes';
				$user_roles_details[$i] = 'user_flag';
				$i= $i + 1;
			} else { $RAL = 'no'; }

			if ($this->request->getData('user_flag') == "CAL") {
				$CAL = 'yes';
				$user_roles_details[$i] = 'user_flag';
				$i= $i + 1;
			} else { $CAL = 'no'; }

			if ($this->request->getData('user_flag') == "HO") {
				$HO = 'yes';
				$user_roles_details[$i] = 'user_flag';
				$i= $i + 1;
			} else { $HO = 'no'; }

			if (!empty($this->request->getData('user_flag'))) {

				$post_input_request = $this->request->getData('user_flag');
				$user_flag = $this->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry functions
				$user_roles_details[$i] = $this->request->getData('user_flag');
				$i= $i + 1;
				if ($user_flag == null) { return false;}

			} else {
				$user_flag=null;
			}

			$this->set('user_flag',$user_flag);

			// End LMIS Role List


			//added on 13-03-2018 by Amol
			//check some validation if HO roles checked to assigned
			if ($this->Customfunctions->ifHoLevelRoleAssigned($user_email_id,$dy_ama,$jt_ama,$ama) == 0) {

				//Added this call to save the user action log on 22-02-2022
				$this->Customfunctions->saveActionPoint('Edit Role (HO Level)','Failed');
				
				$message = 'Sorry...The Selected User already assigned HO level Role(DY.AMA/ JT.AMA/ AMA). One User can have only one HO level role.';
				$redirect_to = '../roles/edit_roles';
		
			} else {

				// Update set user role logs history
				$this->loadModel('DmiUserRolesManagmentLogs');
				$update_user_roles_details = $this->DmiUserRolesManagmentLogs->updateUserRoles($user_roles_details,$user_email_id);

				$add_user_roles = $update_user_roles_details[0];
				$remove_user_roles = $update_user_roles_details[1];
				$action = $update_user_roles_details[2];

				$user_roles_details =implode(',',$user_roles_details);

				$fetch_roles_row_id = $this->DmiUserRoles->find('all',array('fields'=>'id','conditions'=>array('user_email_id IS'=>$user_email_id)))->first();
				$roles_row_id = $fetch_roles_row_id['id'];

				$DmiUserRolesEntity = $this->DmiUserRoles->newEntity(array(

					'id'=>$roles_row_id,
					'user_email_id'=>$user_email_id,
					'add_user'=>$add_user,
					'page_draft'=>$page_draft,
					'page_publish'=>$page_publish,
					'menus'=>$menus,
					'file_upload'=>$file_upload,
					'mo_smo_inspection'=>$mo_smo_inspection,
					'io_inspection'=>$io_inspection,
					'allocation_mo_smo'=>$allocation_mo_smo,
					'allocation_io'=>$allocation_io,
					'reallocation'=>$reallocation,
					'form_verification_home'=>$form_verification_home,
					'allocation_home'=>$allocation_home,
					'set_roles'=>$set_roles,
					'ro_inspection'=>$ro_inspection,
					'dy_ama'=>$dy_ama,
					'ho_mo_smo'=>$ho_mo_smo,
					'jt_ama'=>$jt_ama,
					'ama'=>$ama,
					//'allocation_dy_ama'=>$allocation_dy_ama,
					'allocation_ho_mo_smo'=>$allocation_ho_mo_smo,
					//'allocation_jt_ama'=>$allocation_jt_ama,
					//'allocation_ama'=>$allocation_ama,
					'masters'=>$masters,
				//	'super_admin'=>$super_admin, //intensionally commented as there should be only default single super admins on 06-06-2022
					'renewal_verification'=>$renewal_verification,
					'renewal_allocation'=>$renewal_allocation,
					'view_reports'=>$view_reports,
					'pao'=>$pao,
					//'once_update_permission'=>$once_update_permission,
					'old_appln_data_entry'=>$old_appln_data_entry,
					'so_inspection'=>$so_inspection,
					//'smd_inspection'=>$smd_inspection,
					'feedbacks'=>$feedbacks,
					'unlock_user'=>$unlock_user,
					'transfer_appl'=>$transfer_appl,
					'inspection_pp'=>$site_inspection_pp, //new
					'so_grant_pp'=>$so_grant_pp, //new


					// Start LMIS Role List
					'sample_inward'=>$sample_inward,
					'sample_forward'=>$sample_forward,
					'generate_inward_letter'=>$generate_inward_letter,
					'sample_allocated'=>$sample_allocated,
					'sample_testing_progress'=>$sample_testing_progress,
					'sample_result_approval'=>$sample_result_approval,
					'finalized_sample'=>$finalized_sample,
					'administration'=>$administration,
					'reports'=>$reports,
					'dashboard'=>$dashboard,
					'out_forward'=>$out_forward,
					'RO'=>$RO,
					'SO'=>$SO,
					'RAL'=>$RAL,
					'CAL'=>$CAL,
					'HO'=>$HO,
					'user_flag'=>$user_flag,
					'modified'=>date('Y-m-d H:i:s')


				));

				if ($this->DmiUserRoles->save($DmiUserRolesEntity)) {

					// create entry set roles to user in user_roles_managment_log tables
					$RolesManagmentLogsEntity = $this->DmiUserRolesManagmentLogs->newEntity(array(

					'user_roles'=>$user_roles_details,
					'by_user'=>$this->Session->read('username'),
					'to_user'=>$user_email_id,
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s'),
					'action'=>$action,
					'add_roles'=>$add_user_roles,
					'remove_roles'=>$remove_user_roles,
					'user_division'=>$selected_user_division//new field
					));

					//get the user name
					$userFullName = $this->DmiUsers->find()->select(['f_name','l_name'])->where(['email' => $user_email_id])->first();
					$name = $userFullName['f_name']." ".$userFullName['l_name'];
					if ($this->DmiUserRolesManagmentLogs->save($RolesManagmentLogsEntity)) {

						//Added this call to save the user action log on 22-02-2022
						$this->Customfunctions->saveActionPoint('Edit Role ('.$action.')','Success');
						$message = 'Roles are updated successfully for the user '.$name.'('.base64_decode($user_email_id).')';
						$message_theme = 'success';
						$redirect_to = '../roles/edit_roles';
					}
				}
			}
		}

		// set variables to show popup messages from view file
		$this->set('message',$message);
		$this->set('message_theme',$message_theme);
		$this->set('redirect_to',$redirect_to);

	}
	//Edit user roles end



}
?>
