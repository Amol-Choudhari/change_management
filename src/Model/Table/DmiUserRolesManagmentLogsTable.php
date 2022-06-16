<?php

namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use Cake\Core\Configure;
	
/*	App::uses('Dmi_firm','Model');
	App::uses('Dmi_ro_office','Model');
	App::uses('Dmi_allocation','Model');
	App::uses('Dmi_user','Model');
	App::uses('Dmi_user_role','Model');
	App::uses('Dmi_ho_allocation','Model');
	App::uses('Dmi_final_submit','Model');
	App::uses('M_commodity','Model');
*/	
 
	class DmiUserRolesManagmentLogsTable extends Table {

		var $name = "DmiUserRolesManagmentLogs";
		
		public function showUserRolesHistory($user_role_logs_details) {
			
			if(!empty($user_role_logs_details)) {
				$i = 0;
				
				foreach($user_role_logs_details as $user_roles) {
					$user_roles_name_list[$i] = explode(',',$user_roles['user_roles']);
					$add_user_roles_name_list[$i] = explode(',',$user_roles['add_roles']);
					$remove_user_roles_name_list[$i] = explode(',',$user_roles['remove_roles']);
					$i =$i+1;
				} 
				
				$user_roles_name_list = $this->userRolesName($user_roles_name_list);					
				$add_user_roles_name_list = $this->userRolesName($add_user_roles_name_list);					
				$remove_user_roles_name_list = $this->userRolesName($remove_user_roles_name_list);					
				return array($user_roles_name_list,$remove_user_roles_name_list,$add_user_roles_name_list);
				
			}
					
		}
		
		
		
		public function updateUserRoles($user_roles_details,$user_email_id){

			$user_roles_logs_id = $this->find('list',array('valuefield'=>'id','conditions'=>array('to_user IS'=>$user_email_id)))->toList();
					
				if(!empty($user_roles_logs_id)){
					
					$user_roles_logs_detail = $this->find('all',array('conditions'=>array('id'=>max($user_roles_logs_id))))->first();
					$user_roles_logs_detail = $user_roles_logs_detail['user_roles'];
					$old_user_roles_details = explode(',',$user_roles_logs_detail);
					
					$add_user_roles = array_diff($user_roles_details,$old_user_roles_details);
					$remove_user_roles = array_diff($old_user_roles_details,$user_roles_details,$add_user_roles);
						
					
					if(!empty($add_user_roles) && !empty($remove_user_roles)){
						
						$add_user_roles =implode(',',$add_user_roles);
						$remove_user_roles =implode(',',$remove_user_roles);
						$action = "both";
						
						return array($add_user_roles,$remove_user_roles,$action);
						
					}elseif(!empty($add_user_roles) && empty($remove_user_roles)){
						
						$add_user_roles =implode(',',$add_user_roles);
						$remove_user_roles =null;
						$action = "add";
						
						return array($add_user_roles,$remove_user_roles,$action);
						
					}elseif(empty($add_user_roles) && !empty($remove_user_roles)){
						
						$add_user_roles =null;
						$remove_user_roles = implode(',',$remove_user_roles);
						$action = "remove";
						
						return array($add_user_roles,$remove_user_roles,$action);
					}
					
				}else{
					
					$add_user_roles = implode(',',$user_roles_details);
					$remove_user_roles =null;
					$action = "add";
					
					return array($add_user_roles,$remove_user_roles,$action);
					
				}
					
		}
		
		
		
		public function userRolesName($user_roles_name_list) {
		
			$user_roles_name_value = array('add_user'=>'Add User','page_draft'=>'Page (Draft only)','page_publish'=>'Page Publish','menus'=>'Menus','mo_smo_inspection'=>'MO/SMO','io_inspection'=>'Inspection Officer','ro_inspection'=>'RO In-Charge','allocation_mo_smo'=>'Allocate to MO/SMO','allocation_io'=>'Allocate to IO','reallocation'=>'Re-Allocate',
										'form_verification_home'=>'Form Scrutiny Home','allocation_home'=>'Allocation Home','set_roles'=>'Set User Roles','file_upload'=>'Upload Files','dy_ama'=>'Dy. AMA','ho_mo_smo'=>'HO MO/SMO','jt_ama'=>'Jt. AMA','ama'=>'AMA','allocation_dy_ama'=>'Forward to Dy. AMA','allocation_ho_mo_smo'=>'Allocate to HO MO/SMO',
										'allocation_jt_ama'=>'Forward to Jt. AMA','allocation_ama'=>'Forward to AMA','masters'=>'Masters','super_admin'=>'Super Admin','renewal_verification'=>'Renewal Scrutiny','renewal_allocation'=>'Renewal Allocation','view_reports'=>'View Reports','pao'=>'PAO/DDO','once_update_permission'=>'Aadhar Update Permission',
										'old_appln_data_entry'=>'Old Applications Data Entry','so_inspection'=>'SO In-Charge','smd_inspection'=>'SMD In-Charge','feedbacks'=>'Feedbacks','unlock_user'=>'Unlock User','transfer_appl'=>'Transfer Application','sample_inward'=>'Sample Inward','sample_forward'=>'Sample Forward',
										'generate_inward_letter'=>'Generate Inward Letter','reports'=>'Reports','dashboard'=>'Dashboard','out_forward'=>'Out Forward','user_flag'=>'User Flag','so'=>'SO','sample_allocated'=>'Sample Allocated','sample_testing_progress'=>'Sample Testing Progress','sample_result_approval'=>'Sample Result Approval',
										'finalized_sample'=>'Finalized Sample','administration'=>'Administration','ho'=>'HO','cal'=>'CAL','ral'=>'RAL','ro'=>'RO','once_update_permission'=>'Once Update Permission','aadhar_update_permission'=>'Aadhar Update Permission','inspection_pp'=>'Inspection of PP','so_grant_pp'=>'SO Grant PP','re_esign'=>'Re Esign Module');

				$j = 0;
				foreach($user_roles_name_list as $user_name) {

					$i = 0;
					foreach($user_name as $user_name) {
						if(!empty($user_name)) {
							$user_name_text[$j][$i] = $user_roles_name_value[trim(strtolower($user_name))];//added strtolower() on 06-01-2022 by Amol
						} else {
							$user_name_text[$j][$i] = '--N/A--';
						}
						$i =$i+1;
					}
					$j =$j+1;
				}				
				
				$j = 0;
				foreach($user_name_text as $user_name) {
				
					if(!empty($user_name)) {
						$user_name_text[$j] = implode(', ',$user_name);
						$user_name_text_for_view[$j] = $user_name; // added by Ankur
						
					} else {
						$user_name_text[$j] = '';
					}
					$j =$j+1;
				}
			
			return array($user_name_text,$user_name_text_for_view); // added by Ankur
						
		}


} ?>