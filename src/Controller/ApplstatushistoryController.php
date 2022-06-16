<?php
namespace App\Controller;

	class ApplstatushistoryController extends AppController{

		var $name = 'Applstatushistory';
		//var $layout = 'application_history';
		//var $components= array('Session', 'RequestHandler','Createcaptcha');
		//public $helpers = array('Form','Html','Js' => array('Jquery'));

		public function initialize(): void
    {
        parent::initialize();


				$this->loadComponent('Createcaptcha');
				$this->loadComponent('RequestHandler');

				$this->viewBuilder()->setHelpers(['Form','Html','Js'=>array('Jquery')]);

				$this->viewBuilder()->setLayout('application_history');
    }

		public function applications_list(){

			$this->viewBuilder()->setLayout('admin_dashboard');
			$this->loadModel('DmiFirms');
			$this->loadModel('DmiRoOffices');
			$this->loadModel('DmiCertificateTypes');
			$this->loadModel('Dmi_final_submit');
			$this->loadModel('Dmi_renewal_final_submit');

			$inprogress_napp_with_ro = array(); $inprogress_rapp_with_ro = array();

			$ro_office_list = $this->Dmi_ro_office->find('list',array('fields'=>array('ro_office'),'conditions'=>array('office_type IS'=>'RO','delete_status'=>null),'order'=>array('ro_office')));
			$certificate_type = $this->Dmi_certificate_type->find('list',array('fields'=>array('certificate_type'),'order'=>array('certificate_type')));
			$pending_with = array('ro_inspection-level_3'=>'RO/SO','mo_smo_inspection-level_1'=>'Scrutinizer','io_inspection-level_2'=>'IO','dy_ama-level_4'=>'DY.AMA','jt_ama-level_4'=>'JT.AMA','ama-level_4'=>'AMA','ho_mo_smo-level_4'=>'Scrutinizer(HO)','applicant'=>'Applicant');
			$result_for = array('pending'=>'Pending','granted'=>'Granted');


			$this->set('result_for',$result_for);
			$this->set('ro_office_list',$ro_office_list);
			$this->set('certificate_type',$certificate_type);
			$this->set('pending_with',$pending_with);

			$all_firms_list = $this->Dmi_firms->find('all',array('joins'=>array(array('table' => 'dmi_districts','alias' => 'dist','type' => 'INNER','conditions' => array('dist.id = Dmi_firms.district::integer')),
															  array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = dist.ro_id::integer')),
															  array('table' => 'dmi_certificate_types','alias' => 'ct','type' => 'INNER','conditions' => array('ct.id= Dmi_firms.certification_type::integer'))),
											   'fields'=>array('roo.ro_office','ct.certificate_type','Dmi_firms.firm_name','Dmi_firms.customer_id','Dmi_firms.id'),
											   'conditions'=>array('Dmi_firms.delete_status'=>null),'order'=>array('Dmi_firms.id desc')));

			if(isset($_POST['data'])){

				$conditions = array();
				$application_type = $this->request->getData('application_type');
				$office_type = $this->request->getData('office_type');
				$result_for = $this->request->getData('result_for');
				$pending_with = $this->request->getData('pending_with');

				$application_type_conditions = array(); $office_type_conditions = array(); $pending_with_conditions = array();
				$firm_delete_conditions = array('Dmi_firms.delete_status'=>null);
				if(!empty($application_type)){
					$application_type_conditions = array('Dmi_firms.certification_type'=>$application_type);
				}
				if(!empty($office_type)){
					$office_type_conditions = array('roo.id'=>$office_type);
				}
				if(!empty($pending_with)){
					if($pending_with != "applicant"){
						$explode = explode('-',$pending_with);
						$usrl = 'usrl.'.$explode[0];
						$pending_with_conditions = array($usrl=>'yes','aacp.current_level'=>$explode[1]);
					}else{
						$pending_with_conditions = array('aacp.current_level'=>'applicant');
					}

				}
				if(!empty($result_for)){

					if($result_for=='pending'){

						$pending_conditions = am($application_type_conditions,$office_type_conditions,$pending_with_conditions,$firm_delete_conditions);

						$new_application = $this->Dmi_firms->find('all',array('joins'=>array(array('table' => 'dmi_districts','alias' => 'dist','type' => 'INNER','conditions' => array('dist.id = Dmi_firms.district::integer')),
																				 array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = dist.ro_id::integer')),
																				 array('table' => 'dmi_all_applications_current_positions','alias' => 'aacp','type' => 'INNER','conditions' => array('aacp.customer_id = Dmi_firms.customer_id')),
																				 array('table' => 'dmi_users','alias' => 'usr','type' => 'INNER','conditions' => array('usr.email = aacp.current_user_email_id')),
																				 array('table' => 'dmi_user_roles','alias' => 'usrl','type' => 'INNER','conditions' => array('usrl.user_email_id = usr.email')),
																				 array('table' => 'dmi_certificate_types','alias' => 'ct','type' => 'INNER','conditions' => array('ct.id= Dmi_firms.certification_type::integer'))),
																 'conditions'=>$pending_conditions,
																 'fields'=>array('Dmi_firms.firm_name','Dmi_firms.customer_id','Dmi_firms.id','ct.certificate_type','roo.ro_office')));

						foreach($new_application as $each_record ){
							$result_status = $this->Dmi_final_submit->find('first',array('conditions'=>array('customer_id'=>$each_record['Dmi_firms']['customer_id'],'status'=>'approved','current_level'=>'level_3')));
							if(empty($result_status)){
								$inprogress_napp_with_ro[] = $each_record;
							}
						}
						$renewal_application = $this->Dmi_firms->find('all',array('joins'=>array(array('table' => 'dmi_districts','alias' => 'dist','type' => 'INNER','conditions' => array('dist.id = Dmi_firms.district::integer')),
																				 array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = dist.ro_id::integer')),
																				 array('table' => 'dmi_renewal_all_current_positions','alias' => 'aacp','type' => 'INNER','conditions' => array('aacp.customer_id = Dmi_firms.customer_id')),
																				 array('table' => 'dmi_users','alias' => 'usr','type' => 'INNER','conditions' => array('usr.email = aacp.current_user_email_id')),
																				 array('table' => 'dmi_user_roles','alias' => 'usrl','type' => 'INNER','conditions' => array('usrl.user_email_id = usr.email')),
																				 array('table' => 'dmi_certificate_types','alias' => 'ct','type' => 'INNER','conditions' => array('ct.id= Dmi_firms.certification_type::integer'))),
																 'conditions'=>$pending_conditions,
																 'fields'=>array('Dmi_firms.firm_name','Dmi_firms.customer_id','Dmi_firms.id','ct.certificate_type','roo.ro_office')));

						foreach($renewal_application as $each_record ){
							$result_status = $this->Dmi_renewal_final_submit->find('first',array('conditions'=>array('customer_id'=>$each_record['Dmi_firms']['customer_id'],'status'=>'approved','current_level'=>'level_3')));
							if(empty($result_status)){
								$inprogress_rapp_with_ro[] = $each_record;
							}
						}

						$all_firms_list = am(array_unique($inprogress_napp_with_ro, SORT_REGULAR),array_unique($inprogress_rapp_with_ro, SORT_REGULAR));

					}elseif($result_for=='granted'){

						$granted_conditions = am($application_type_conditions,$office_type_conditions,$firm_delete_conditions);

						$new_application = $this->Dmi_firms->find('all',array('joins'=>array(array('table' => 'dmi_districts','alias' => 'dist','type' => 'INNER','conditions' => array('dist.id = Dmi_firms.district::integer')),
																				 array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = dist.ro_id::integer')),
																				 array('table' => 'dmi_final_submits','alias' => 'dfs','type' => 'INNER','conditions' => array('dfs.customer_id = Dmi_firms.customer_id','dfs.current_level'=>'level_3','dfs.status'=>'approved')),
																				 array('table' => 'dmi_certificate_types','alias' => 'ct','type' => 'INNER','conditions' => array('ct.id= Dmi_firms.certification_type::integer'))),
																 'fields'=>array('Dmi_firms.firm_name','Dmi_firms.customer_id','Dmi_firms.id','ct.certificate_type','roo.ro_office'),
																 'conditions'=>$granted_conditions,
																 'order'=>array('Dmi_firms.id desc')));

						$renewal_application = $this->Dmi_firms->find('all',array('joins'=>array(array('table' => 'dmi_districts','alias' => 'dist','type' => 'INNER','conditions' => array('dist.id = Dmi_firms.district::integer')),
																				 array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = dist.ro_id::integer')),
																				 array('table' => 'dmi_renewal_final_submits','alias' => 'dfs','type' => 'INNER','conditions' => array('dfs.customer_id = Dmi_firms.customer_id','dfs.current_level'=>'level_3','dfs.status'=>'approved')),
																				 array('table' => 'dmi_certificate_types','alias' => 'ct','type' => 'INNER','conditions' => array('ct.id= Dmi_firms.certification_type::integer'))),
																 'fields'=>array('Dmi_firms.firm_name','Dmi_firms.customer_id','Dmi_firms.id','ct.certificate_type','roo.ro_office'),
																 'conditions'=>$granted_conditions,
																 'order'=>array('Dmi_firms.id desc'),
																 ));

						$all_firms_list = am(array_unique($new_application, SORT_REGULAR),array_unique($renewal_application, SORT_REGULAR));
					}

				}else{

					$firms_conditions = am($application_type_conditions,$office_type_conditions,$firm_delete_conditions);

					$all_firms_list = $this->Dmi_firms->find('all',array('joins'=>array(array('table' => 'dmi_districts','alias' => 'dist','type' => 'INNER','conditions' => array('dist.id = Dmi_firms.district::integer')),
															  array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = dist.ro_id::integer')),
															  array('table' => 'dmi_certificate_types','alias' => 'ct','type' => 'INNER','conditions' => array('ct.id= Dmi_firms.certification_type::integer'))),
											   'fields'=>array('roo.ro_office','ct.certificate_type','Dmi_firms.firm_name','Dmi_firms.customer_id','Dmi_firms.id'),
											   'conditions'=>$firms_conditions,'order'=>array('Dmi_firms.id desc')));

				}

			}

			$this->set('all_firms_list',$all_firms_list);//exit;
		}

		public function application_status_history($id){

			$this->loadModel('Dmi_applicant_payment_detail');
			$this->loadModel('Dmi_final_submit');
			$this->loadModel('Dmi_allocation');
			$this->loadModel('Dmi_mo_ro_comments_detail');
			$this->loadModel('Dmi_siteinspection_final_report');
			$this->loadModel('Dmi_ho_allocation');
			$this->loadModel('Dmi_ho_comment_reply_detail');
			$this->loadModel('Dmi_ama_approved_application');
			$this->loadModel('Dmi_all_applications_current_position');
			$this->loadModel('Dmi_firms');
			$this->loadModel('Dmi_printing_firm_profile');

			$this->loadModel('Dmi_renewal_all_current_position');
			$this->loadModel('Dmi_renewal_allocation');
			$this->loadModel('Dmi_renewal_applicant_payment_detail');
			$this->loadModel('Dmi_renewal_final_submit');
			$this->loadModel('Dmi_renewal_mo_ro_comments_detail');
			$this->loadModel('Dmi_renewal_siteinspection_final_report');


			$this->loadModel('Dmi_ro_office');
			$this->loadModel('Dmi_certificate_type');
			$this->loadModel('Dmi_district');
			$this->loadModel('Dmi_state');
			$this->loadModel('Dmi_customer');
			$this->loadModel('M_commodity_category');

			$ro_office_list = $this->Dmi_ro_office->find('list',array('fields'=>array('ro_office'),'conditions'=>array('office_type'=>'RO','delete_status'=>null),'order'=>array('ro_office')));
			$certificate_type = $this->Dmi_certificate_type->find('list',array('fields'=>array('certificate_type'),'order'=>array('certificate_type')));
			$pending_with = array('ro_inspection-level_3'=>'RO/SO','mo_smo_inspection-level_1'=>'Scrutinizer','io_inspection-level_2'=>'IO','dy_ama-level_4'=>'DY.AMA','jt_ama-level_4'=>'JT.AMA','ama-level_4'=>'AMA','ho_mo_smo-level_4'=>'Scrutinizer(HO)','applicant'=>'Applicant');
			$result_for = array('pending'=>'Pending','granted'=>'Granted');

			$this->set('result_for',$result_for);
			$this->set('ro_office_list',$ro_office_list);
			$this->set('certificate_type',$certificate_type);
			$this->set('pending_with',$pending_with);

			$current_position_level ='';
			$current_user_role = '';
			$confirmed_payment = '';
			$ho_allocation_details = '';
			$payment_confirmation_status = '';
			$ho_comment_inarray = array();
			$application_id = '';
			$printing_forward_ho = '';
 			$forward_ho_btn = '';


			$certification_type = array('A'=> 'CA Non Bevo','E'=>'CA Bevo','F'=>'CA Export','D'=>'Laboratory Domestic');

			$firm_details = $this->Dmi_firms->find('first',array('conditions'=>array('id'=>$id)));
			$application_id = $firm_details['Dmi_firms']['customer_id'];
			$form_type = $this->check_applicant_form_type($application_id);

			$application_type = $this->get_application_type($application_id);

			//Get user last action on selected application and his replied and referred back numbers.
			$use_actions = $this->get_user_last_action_details($application_id,$application_type);
			$this->set('use_actions',$use_actions);

			//Get all user details like name, office, email, phone.
			$get_userdetails = $this->get_userdetails($application_id,$application_type);
			$this->set('get_userdetails',$get_userdetails);

			$district_list = $this->Dmi_district->find('list',array('fields'=>array('district_name')));
			$state_list = $this->Dmi_state->find('list',array('fields'=>array('state_name')));
			$this->set('district_list',$district_list);
			$this->set('state_list',$state_list);

			$primary_details = $this->Dmi_customer->find('first',array('conditions'=>array('customer_id'=>$firm_details['Dmi_firms']['customer_primary_id'])));
			$this->set('primary_details',$primary_details);

			$commodity_category_details = $this->M_commodity_category->find('list',array('fields'=>array('category_code','category_name')));
			$this->set('commodity_category_details',$commodity_category_details);

			$ama_approved = $this->Dmi_ama_approved_application->find('first',array('conditions'=>array('customer_id'=>$application_id)));

			if($application_type[0] == 'renewal'){

				$Dmi_all_applications_current_position = 'Dmi_renewal_all_current_position';
				$Dmi_allocation = 'Dmi_renewal_allocation';
				$Dmi_applicant_payment_detail =  'Dmi_renewal_applicant_payment_detail';
				$Dmi_final_submit =  'Dmi_renewal_final_submit';
				$Dmi_mo_ro_comments_detail = 'Dmi_renewal_mo_ro_comments_detail';
				$Dmi_siteinspection_final_report =  'Dmi_renewal_siteinspection_final_report';
			}else{
				$Dmi_all_applications_current_position = 'Dmi_all_applications_current_position';
				$Dmi_allocation = 'Dmi_allocation';
				$Dmi_applicant_payment_detail =  'Dmi_applicant_payment_detail';
				$Dmi_final_submit =  'Dmi_final_submit';
				$Dmi_mo_ro_comments_detail = 'Dmi_mo_ro_comments_detail';
				$Dmi_siteinspection_final_report =  'Dmi_siteinspection_final_report';
			}

			//Get certification type of selected Application.
			if($form_type == 'B'){
				$printing_details = $this->Dmi_printing_firm_profile->find('first',array('joins'=>array(array('table' => 'dmi_printing_business_years','alias' => 'pby','type' => 'INNER','conditions' => array('pby.id = Dmi_printing_firm_profile.business_years::integer'))),
																	 'fields'=>array('pby.id','Dmi_printing_firm_profile.business_years'),
																	 'conditions'=>array('customer_id'=>$application_id),
																	 'order'=>array('Dmi_printing_firm_profile.id desc')));
				if(!empty($printing_details)){
					if($printing_details['Dmi_printing_firm_profile']['business_years'] == '5'){
						$certification_type_label = 'Printing Press More Than 3 Years';
					}else{
						$certification_type_label = 'Printing Press less Than 3 Years';
						$printing_forward_ho = 'B';
					}
				}else{
					$certification_type_label = 'Printing Press';
				}
			}else{
				$certification_type_label = $certification_type[$form_type];
			}

			//Here decided to Head office(HO) block showing or not.
			if(($form_type == 'E' || $form_type == 'F' || $printing_forward_ho =='B' || $form_type == 'D') && $application_type[0] !='renewal' && $application_type[0] !='old'){
				if($form_type == 'F' && $firm_details['Dmi_firms']['commodity'] == 6 ){
					$forward_ho_btn = 'yes';
				}elseif($printing_forward_ho =='B'){
					$forward_ho_btn = 'yes';
				}elseif($form_type != 'F' && $printing_forward_ho ==''){
					$forward_ho_btn = 'yes';
				}
			}

			$this->set('forward_ho_btn',$forward_ho_btn);
			$this->set('application_type',$application_type);
			$this->set('firm_details',$firm_details);
			$this->set('certification_type_label',$certification_type_label);

			//Checked payment confirmation status.
			$payment_history = $this->$Dmi_applicant_payment_detail->find('first',array('fields'=>array('payment_confirmation'),'conditions'=>array('customer_id'=>$application_id),'order'=>array('id desc')));
			if(!empty($payment_history)){
				$payment_confirmation_status = $payment_history[$Dmi_applicant_payment_detail]['payment_confirmation'];
			}

			if($payment_confirmation_status == 'confirmed'){

				$application_allocation_status = $this->$Dmi_allocation->find('first',array('conditions'=>array('customer_id'=>$application_id)));
				$ho_comment_reply_history = $this->Dmi_ho_comment_reply_detail->find('list',array('fields'=>array('from_user','to_user'),'conditions'=>array('customer_id'=>$application_id)));
				if(!empty($ho_comment_reply_history)){
					$ho_comment_inarray = array_unique(array_merge($ho_comment_reply_history,array_keys($ho_comment_reply_history)));
				}

				//Find here at which level selected application is present or pending.
				if($application_type[0] == 'renewal'){

					$current_position = $this->Dmi_renewal_all_current_position->find('first',
												array('joins'=>array(array('table' => 'dmi_user_roles','alias' => 'user_role','type' => 'INNER','conditions' => array('user_role.user_email_id = Dmi_renewal_all_current_position.current_user_email_id'))),
													  'fields'=>array('user_role.dy_ama','user_role.jt_ama','user_role.ama','user_role.ho_mo_smo','Dmi_renewal_all_current_position.current_level','Dmi_renewal_all_current_position.current_user_email_id'),
													  'conditions'=>array('Dmi_renewal_all_current_position.customer_id'=>$application_id)));
					$current_position_level = $current_position['Dmi_renewal_all_current_position']['current_level'];

				}else{

					$current_position = $this->Dmi_all_applications_current_position->find('first',
												array('joins'=>array(array('table' => 'dmi_user_roles','alias' => 'user_role','type' => 'INNER','conditions' => array('user_role.user_email_id = Dmi_all_applications_current_position.current_user_email_id'))),
													  'fields'=>array('user_role.dy_ama','user_role.jt_ama','user_role.ama','user_role.ho_mo_smo','Dmi_all_applications_current_position.current_level','Dmi_all_applications_current_position.current_user_email_id'),
													  'conditions'=>array('Dmi_all_applications_current_position.customer_id'=>$application_id)));
					$current_position_level = $current_position['Dmi_all_applications_current_position']['current_level'];

				}


				$current_user_role = $current_position['user_role'];


				//Checked HO level allocation and where the application pending at HO level.
				$ho_allocation_details = $this->Dmi_ho_allocation->find('first',array('conditions'=>array('customer_id'=>$application_id)));
				if(!empty($ho_allocation_details)){

					$ho_allocation_current_level = $ho_allocation_details['Dmi_ho_allocation']['current_level'];
					$ho_allocation_ho_mo_smo = $ho_allocation_details['Dmi_ho_allocation']['ho_mo_smo'];
					$ho_allocation_dy_ama = $ho_allocation_details['Dmi_ho_allocation']['dy_ama'];

					$ho_comment_reply_detail = $this->Dmi_ho_comment_reply_detail->find('first',
												array('fields'=>array('from_user','to_user','id'),
													  'conditions'=>array('customer_id'=>$application_id),'order'=>'id desc'));
					if(empty($ho_comment_reply_detail) && $current_position_level=='level_4'){

						if($ho_allocation_ho_mo_smo == $ho_allocation_current_level){
							$ho_comment_reply_from = 'dy_ama';
							$ho_comment_reply_to = 'ho_mo_smo';
						}
						if($ho_allocation_dy_ama == $ho_allocation_current_level){

						}
					}else{
						$ho_comment_reply_from = $ho_comment_reply_detail['Dmi_ho_comment_reply_detail']['from_user'];
						$ho_comment_reply_to = $ho_comment_reply_detail['Dmi_ho_comment_reply_detail']['to_user'];
					}

					$this->set('level_4_from',$ho_comment_reply_from);
					$this->set('level_4_to',$ho_comment_reply_to);
					$this->set('level_4_homosmo',$ho_allocation_ho_mo_smo);

				}

				$this->set('current_position',$current_position);
				$this->set('ho_comment_inarray',$ho_comment_inarray);
				$this->set('allocation_status',$application_allocation_status[$Dmi_allocation]);
			}

			if($current_position_level == 'level_3'){

				//Checked here, Which user sent the application currently to RO?
				$application_came_from = $this->application_came_from($application_id,$application_type[0]);

				$explode_value = explode("/",$application_came_from);
				if(count($explode_value)>1){
				  $explode_value[1] = $explode_value[1];
				}else{
				  $explode_value[1] = '';
				}
				$this->set('application_came_from',$explode_value[0]);
				$this->set('final_submit_status',$explode_value[1]);
			}

			//Get scrutinizer pending Details
			if($current_position_level == 'level_1'){

				$mo_ro_details = $this->$Dmi_mo_ro_comments_detail->find('first',array(
									'fields'=>array('modified'),'conditions'=>array('customer_id'=>$application_id),'order'=>'id desc'));
				$this->set('mo_pending',$mo_ro_details);

			}

			//Get Inspection officer pending Details
			if($current_position_level == 'level_2'){
				$siteinspection_final_details = $this->$Dmi_siteinspection_final_report->find('first',array(
									'fields'=>array('modified','status'),'conditions'=>array('customer_id'=>$application_id),'order'=>'id desc'));
				$this->set('io_pending',$siteinspection_final_details);
			}

			$this->set('application_id',$application_id);
			$this->set('ama_approved',$ama_approved);
			$this->set('payment_confirmation_status',$payment_confirmation_status);
			$this->set('ho_allocation_details',$ho_allocation_details);
			$this->set('current_position_level',$current_position_level);
			$this->set('current_user_role',$current_user_role);
		}


		//Checked here, Which user sent the application currently to RO?
		public function application_came_from($application_id,$application_type){

			$this->loadModel('Dmi_final_submit');
			$this->loadModel('Dmi_mo_ro_comments_detail');
			$this->loadModel('Dmi_siteinspection_final_report');
			$this->loadModel('Dmi_ho_comment_reply_detail');

			if($application_type == 'renewal'){
				$Dmi_final_submit =  'Dmi_renewal_final_submit';
				$Dmi_mo_ro_comments_detail = 'Dmi_renewal_mo_ro_comments_detail';
				$Dmi_siteinspection_final_report =  'Dmi_renewal_siteinspection_final_report';
			}else{
				$Dmi_final_submit =  'Dmi_final_submit';
				$Dmi_mo_ro_comments_detail = 'Dmi_mo_ro_comments_detail';
				$Dmi_siteinspection_final_report =  'Dmi_siteinspection_final_report';
			}

			$final_submitted_time = 0; $mo_ro_commented_time=0; $siteinspection_final_submitted_time=0; $ho_commented_time=0;
			$final_submitted_index = 'applicant'; $siteinspection_final_submitted_index='IO';

			$final_submit_details = $this->$Dmi_final_submit->find('first',array(
									'fields'=>array('modified','status'),'conditions'=>array('customer_id'=>$application_id),'order'=>'id desc'));

			$mo_ro_details = $this->$Dmi_mo_ro_comments_detail->find('first',array(
									'fields'=>array('modified'),'conditions'=>array('customer_id'=>$application_id),'order'=>'id desc'));

			$siteinspection_final_details = $this->$Dmi_siteinspection_final_report->find('first',array(
									'fields'=>array('modified','status'),'conditions'=>array('customer_id'=>$application_id),'order'=>'id desc'));

			$ho_comment_reply_detail = $this->Dmi_ho_comment_reply_detail->find('first',array(
									'fields'=>array('modified'),'conditions'=>array('customer_id'=>$application_id),'order'=>'id desc'));

			if(!empty($final_submit_details)){
				$final_submitted_index = $final_submitted_index.'/'.$final_submit_details[$Dmi_final_submit]['status'];
				$final_submitted_time = strtotime(str_replace('/','-',$final_submit_details[$Dmi_final_submit]['modified']));
			}
			if(!empty($mo_ro_details)){
				$mo_ro_commented_time = strtotime(str_replace('/','-',$mo_ro_details[$Dmi_mo_ro_comments_detail]['modified']));
			}
			if(!empty($siteinspection_final_details)){
				$siteinspection_final_submitted_index = $siteinspection_final_submitted_index.'/'.$siteinspection_final_details[$Dmi_siteinspection_final_report]['status'];
				$siteinspection_final_submitted_time = strtotime(str_replace('/','-',$siteinspection_final_details[$Dmi_siteinspection_final_report]['modified']));
			}
			if(!empty($ho_comment_reply_detail)){
				$ho_commented_time = strtotime(str_replace('/','-',$ho_comment_reply_detail['Dmi_ho_comment_reply_detail']['modified']));
			}

			$application_came_from = array($final_submitted_index => $final_submitted_time, "MO" => $mo_ro_commented_time, $siteinspection_final_submitted_index => $siteinspection_final_submitted_time,"HO"=>$ho_commented_time);
			$came_from = array_keys($application_came_from, max($application_came_from));
			return $came_from[0];
		}

		//Get application type.
		public function get_application_type($customer_id){

			$this->loadModel('Dmi_auth_firm_registration');
			$this->loadModel('Dmi_grant_certificates_pdf');
			$this->loadModel('Dmi_firms');
			$already_granted = $this->Dmi_firms->find('first',array('fields'=>array('customer_id'),'conditions'=>array('customer_id'=>$customer_id,'is_already_granted'=>'yes')));
			$granted = $this->Dmi_grant_certificates_pdf->find('first',array('fields'=>array('customer_id'),'conditions'=>array('customer_id'=>$customer_id,'pdf_version'=>'1')));
			$added_by_auth_user = $this->Dmi_auth_firm_registration->find('first',array('fields'=>array('firm_id'),'conditions'=>array('firm_id'=>$customer_id)));

			if(!empty($granted)){
				$application_type = 'renewal';
				$message = 'Renewal Application';
			}elseif(!empty($already_granted) && !empty($added_by_auth_user)){
				$application_type = 'oldByAuth';
				$message = 'Old Application Added By DMI Authenticated User';
			}elseif(!empty($already_granted) && empty($added_by_auth_user)){
				$application_type = 'old';
				$message = 'Old Application Added By Applicant';
			}else{
				$application_type = 'new';
				$message = 'New Application';
			}

			return  array($application_type,$message);
		}

		//Get all user details like name, office, email, phone.
		public function get_userdetails($customer_id,$application_type){

			$dy_ama_details =array(); $jt_ama_details =array(); $ama_details =array(); $homo_details =array();

			if($application_type[0] == 'renewal'){

				$Dmi_allocation = 'Dmi_renewal_allocation';
				$Dmi_final_submit =  'Dmi_renewal_final_submit';
				$Dmi_mo_ro_comments_detail = 'Dmi_renewal_mo_ro_comments_detail';
				$Dmi_siteinspection_final_report =  'Dmi_renewal_siteinspection_final_report';
				$Dmi_applicant_payment_detail =  'Dmi_renewal_applicant_payment_detail';

			}else{

				$Dmi_allocation = 'Dmi_allocation';
				$Dmi_final_submit =  'Dmi_final_submit';
				$Dmi_mo_ro_comments_detail = 'Dmi_mo_ro_comments_detail';
				$Dmi_siteinspection_final_report =  'Dmi_siteinspection_final_report';
				$Dmi_applicant_payment_detail =  'Dmi_applicant_payment_detail';
			}

			$this->loadModel($Dmi_allocation);
			$this->loadModel($Dmi_final_submit);
			$this->loadModel($Dmi_mo_ro_comments_detail);
			$this->loadModel($Dmi_siteinspection_final_report);
			$this->loadModel($Dmi_applicant_payment_detail);
			$this->loadModel('Dmi_ho_allocation');
			$this->loadModel('Dmi_ho_comment_reply_detail');

			$pao_details = $this->$Dmi_applicant_payment_detail->find('first',array('joins'=>array(array('table' => 'dmi_pao_details','alias' => 'pao','type' => 'INNER','conditions' => array('pao.id = '.$Dmi_applicant_payment_detail.'.pao_id::integer')),
																								   array('table' => 'dmi_users','alias' => 'user','type' => 'INNER','conditions' => array('user.id = pao.pao_user_id::integer')),
																								   array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = user.posted_ro_office::integer'))),
																				    'fields'=>array('pao.pao_alias_name','user.f_name','user.l_name','roo.ro_office','user.email','user.phone'),
																					'conditions'=>array($Dmi_applicant_payment_detail.'.customer_id'=>$customer_id)));

			$mo_details = $this->$Dmi_allocation->find('first',array('joins'=>array(array('table' => 'dmi_users','alias' => 'user','type' => 'INNER','conditions' => array('user.email = '.$Dmi_allocation.'.level_1')),
																								   array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = user.posted_ro_office::integer'))),
																				    'fields'=>array('user.f_name','user.l_name','roo.ro_office','user.email','user.phone'),
																					'conditions'=>array($Dmi_allocation.'.customer_id'=>$customer_id)));

			$io_details = $this->$Dmi_allocation->find('first',array('joins'=>array(array('table' => 'dmi_users','alias' => 'user','type' => 'INNER','conditions' => array('user.email = '.$Dmi_allocation.'.level_2')),
																								   array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = user.posted_ro_office::integer'))),
																				    'fields'=>array('user.f_name','user.l_name','roo.ro_office','user.email','user.phone'),
																					'conditions'=>array($Dmi_allocation.'.customer_id'=>$customer_id)));

			$ro_details = $this->$Dmi_allocation->find('first',array('joins'=>array(array('table' => 'dmi_users','alias' => 'user','type' => 'INNER','conditions' => array('user.email = '.$Dmi_allocation.'.level_3')),
																								   array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = user.posted_ro_office::integer'))),
																				    'fields'=>array('user.f_name','user.l_name','roo.ro_office','user.email','user.phone'),
																					'conditions'=>array($Dmi_allocation.'.customer_id'=>$customer_id)));

			if($application_type[0] != 'renewal'){

				$dy_ama_details = $this->Dmi_ho_allocation->find('first',array('joins'=>array(array('table' => 'dmi_users','alias' => 'user','type' => 'INNER','conditions' => array('user.email = Dmi_ho_allocation.dy_ama')),
																									   array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = user.posted_ro_office::integer'))),
																						'fields'=>array('user.f_name','user.l_name','roo.ro_office','user.email','user.phone'),
																						'conditions'=>array('Dmi_ho_allocation.customer_id'=>$customer_id)));

				$jt_ama_details = $this->Dmi_ho_allocation->find('first',array('joins'=>array(array('table' => 'dmi_users','alias' => 'user','type' => 'INNER','conditions' => array('user.email = Dmi_ho_allocation.jt_ama')),
																									   array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = user.posted_ro_office::integer'))),
																						'fields'=>array('user.f_name','user.l_name','roo.ro_office','user.email','user.phone'),
																						'conditions'=>array('Dmi_ho_allocation.customer_id'=>$customer_id)));

				$ama_details = $this->Dmi_ho_allocation->find('first',array('joins'=>array(array('table' => 'dmi_users','alias' => 'user','type' => 'INNER','conditions' => array('user.email = Dmi_ho_allocation.ama')),
																									   array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = user.posted_ro_office::integer'))),
																						'fields'=>array('user.f_name','user.l_name','roo.ro_office','user.email','user.phone'),
																						'conditions'=>array('Dmi_ho_allocation.customer_id'=>$customer_id)));

				$homo_details = $this->Dmi_ho_allocation->find('first',array('joins'=>array(array('table' => 'dmi_users','alias' => 'user','type' => 'INNER','conditions' => array('user.email = Dmi_ho_allocation.ho_mo_smo')),
																									   array('table' => 'dmi_ro_offices','alias' => 'roo','type' => 'INNER','conditions' => array('roo.id = user.posted_ro_office::integer'))),
																						'fields'=>array('user.f_name','user.l_name','roo.ro_office','user.email','user.phone'),
																						'conditions'=>array('Dmi_ho_allocation.customer_id'=>$customer_id)));

			}


			return array($pao_details,$mo_details,$io_details,$ro_details,$dy_ama_details,$jt_ama_details,$ama_details,$homo_details);



		}


		//Get user last action on selected application and his replied and referred back numbers.
		public function get_user_last_action_details($customer_id,$application_type){

			if($application_type == 'renewal'){
				$Dmi_allocation = 'Dmi_renewal_allocation';
				$Dmi_final_submit =  'Dmi_renewal_final_submit';
				$Dmi_mo_ro_comments_detail = 'Dmi_renewal_mo_ro_comments_detail';
				$Dmi_siteinspection_final_report =  'Dmi_renewal_siteinspection_final_report';
				$Dmi_applicant_payment_detail =  'Dmi_renewal_applicant_payment_detail';
				$Dmi_ho_comment_reply_detail = 'Dmi_ho_comment_reply_detail';
			}else{
				$Dmi_allocation = 'Dmi_allocation';
				$Dmi_final_submit =  'Dmi_final_submit';
				$Dmi_mo_ro_comments_detail = 'Dmi_mo_ro_comments_detail';
				$Dmi_siteinspection_final_report =  'Dmi_siteinspection_final_report';
				$Dmi_applicant_payment_detail =  'Dmi_applicant_payment_detail';
				$Dmi_ho_comment_reply_detail = 'Dmi_ho_comment_reply_detail';
			}


			//Defined Variables
			$applicant_last_action_date = ""; $ddo_last_action_date = ""; $mo_last_action_date = ""; $io_last_action_date = "";
			$dyama_last_action_date = ""; $jtama_last_action_date = ""; $ama_last_action_date = ""; $homo_last_action_date = "";
			$ro_last_action_date = ""; $applicant_replied_to_ro_count = ""; $applicant_replied_to_ddo_count = ""; $ro_referred_back_to_applicant_count = "";
			$ro_referred_back_to_mo_count = ""; $ro_referred_back_to_io_count = ""; $ro_referred_back_to_ho_count = ""; $dyama_replied_count = "";
			$jtama_replied_count = ""; $ama_replied_count = ""; $homo_replied_count = ""; $ddo_referred_back_count = "";
			$mo_replied_count = ""; $io_replied_count = "";


			$this->loadModel($Dmi_allocation);
			$this->loadModel($Dmi_final_submit);
			$this->loadModel($Dmi_mo_ro_comments_detail);
			$this->loadModel($Dmi_siteinspection_final_report);
			$this->loadModel($Dmi_applicant_payment_detail);

			$applicant_final_submit_date = $this->$Dmi_applicant_payment_detail->find('first',array('fields'=>array('date(modified)'),'conditions'=>array('customer_id'=>$customer_id,'payment_confirmation'=>'pending'),'order'=>'id desc'));
			$ddo_referred_back_date = $this->$Dmi_applicant_payment_detail->find('first',array('fields'=>array('date(modified)'),'conditions'=>array('customer_id'=>$customer_id,'payment_confirmation'=>'not_confirmed'),'order'=>'id desc'));
			$applicant_replied_date = $this->$Dmi_applicant_payment_detail->find('first',array('fields'=>array('date(modified)'),'conditions'=>array('customer_id'=>$customer_id,'payment_confirmation'=>'replied'),'order'=>'id desc'));
			$ddo_confirm_date = $this->$Dmi_applicant_payment_detail->find('first',array('fields'=>array('date(modified)'),'conditions'=>array('customer_id'=>$customer_id,'payment_confirmation'=>'confirmed'),'order'=>'id desc'));
			$ddo_referred_back_count = $this->$Dmi_applicant_payment_detail->find('first',array('fields'=>array('COUNT(payment_confirmation) as count'),'conditions'=>array('customer_id'=>$customer_id,'payment_confirmation'=>'not_confirmed')));
			$applicant_replied_to_ddo_count = $this->$Dmi_applicant_payment_detail->find('first',array('fields'=>array('COUNT(payment_confirmation) as count'),'conditions'=>array('customer_id'=>$customer_id,'payment_confirmation'=>'replied')));


			if(!empty($ddo_referred_back_count)){ $ddo_referred_back_count = $ddo_referred_back_count[0]['count']; }
			if(!empty($applicant_replied_to_ddo_count)){ $applicant_replied_to_ddo_count = $applicant_replied_to_ddo_count[0]['count']; }

			if(empty($applicant_replied_date)){
				$applicant_last_action_date = $applicant_final_submit_date;
			}else{
				$applicant_last_action_date = $applicant_replied_date;
			}

			if(empty($ddo_confirm_date)){
				$ddo_last_action_date = $ddo_referred_back_date;
			}else{
				$ddo_last_action_date = $ddo_confirm_date;
				$applicant_replied_to_ro_date = $this->$Dmi_final_submit->find('first',array('fields'=>array('date(modified)'),'conditions'=>array('customer_id'=>$customer_id,'status'=>'replied'),'order'=>'id desc'));
				$applicant_replied_to_ro_count = $this->$Dmi_final_submit->find('first',array('fields'=>array('COUNT(status) as count'),'conditions'=>array('customer_id'=>$customer_id,'status'=>'replied')));

				if(!empty($applicant_replied_to_ro_date)){
					$applicant_last_action_date = $applicant_replied_to_ro_date;
				}

				$mo_last_action_date = $this->$Dmi_mo_ro_comments_detail->find('first',array('fields'=>array('date(comment_date)'),'conditions'=>array('customer_id'=>$customer_id,'available_to'=>'ro'),'order'=>'id desc'));
				$io_last_action_date = $this->$Dmi_siteinspection_final_report->find('first',array('fields'=>array('date(modified)'),'conditions'=>array('customer_id'=>$customer_id,'OR'=>array('status'=>'pending','status'=>'replied')),'order'=>'id desc'));

				$mo_replied_count = $this->$Dmi_mo_ro_comments_detail->find('first',array('fields'=>array('COUNT(available_to) as count'),'conditions'=>array('customer_id'=>$customer_id,'available_to'=>'ro')));
				$io_replied_count = $this->$Dmi_siteinspection_final_report->find('first',array('fields'=>array('COUNT(status) as count'),'conditions'=>array('customer_id'=>$customer_id,'OR'=>array('status'=>'pending','status'=>'replied'))));

				$dyama_last_action_date = $this->$Dmi_ho_comment_reply_detail->find('first',array('fields'=>array('date(comment_date)'),'conditions'=>array('customer_id'=>$customer_id,'from_user'=>'dy_ama'),'order'=>'id desc'));
				$jtama_last_action_date = $this->$Dmi_ho_comment_reply_detail->find('first',array('fields'=>array('date(comment_date)'),'conditions'=>array('customer_id'=>$customer_id,'from_user'=>'jt_ama'),'order'=>'id desc'));
				$ama_last_action_date = $this->$Dmi_ho_comment_reply_detail->find('first',array('fields'=>array('date(comment_date)'),'conditions'=>array('customer_id'=>$customer_id,'from_user'=>'ama'),'order'=>'id desc'));
				$homo_last_action_date = $this->$Dmi_ho_comment_reply_detail->find('first',array('fields'=>array('date(comment_date)'),'conditions'=>array('customer_id'=>$customer_id,'from_user'=>'ho_mo_smo'),'order'=>'id desc'));

				$dyama_replied_count = $this->$Dmi_ho_comment_reply_detail->find('first',array('fields'=>array('COUNT(from_user) as count'),'conditions'=>array('customer_id'=>$customer_id,'from_user'=>'dy_ama')));
				$jtama_replied_count = $this->$Dmi_ho_comment_reply_detail->find('first',array('fields'=>array('COUNT(from_user) as count'),'conditions'=>array('customer_id'=>$customer_id,'from_user'=>'jt_ama')));
				$ama_replied_count = $this->$Dmi_ho_comment_reply_detail->find('first',array('fields'=>array('COUNT(from_user) as count'),'conditions'=>array('customer_id'=>$customer_id,'from_user'=>'ama')));
				$homo_replied_count = $this->$Dmi_ho_comment_reply_detail->find('first',array('fields'=>array('COUNT(from_user) as count'),'conditions'=>array('customer_id'=>$customer_id,'from_user'=>'ho_mo_smo')));


				$ro_referred_back_to_applicant_date = "";
				$ro_referred_back_to_mo_date = "";
				$ro_referred_back_to_io_date = "";
				$ro_referred_back_to_ho_date = "";

				$ro_referred_back_to_applicant = $this->$Dmi_final_submit->find('first',array('fields'=>array('modified'),'conditions'=>array('customer_id'=>$customer_id,'status'=>'referred_back'),'order'=>'id desc'));
				$ro_referred_back_to_mo = $this->$Dmi_mo_ro_comments_detail->find('first',array('fields'=>array('comment_date'),'conditions'=>array('customer_id'=>$customer_id,'available_to'=>'mo'),'order'=>'id desc'));
				$ro_referred_back_to_io = $this->$Dmi_siteinspection_final_report->find('first',array('fields'=>array('modified'),'conditions'=>array('customer_id'=>$customer_id,'status'=>'referred_back'),'order'=>'id desc'));
				$ro_referred_back_to_ho = $this->$Dmi_ho_comment_reply_detail->find('first',array('fields'=>array('comment_date'),'conditions'=>array('customer_id'=>$customer_id,'from_user'=>'ro'),'order'=>'id desc'));

				if(!empty($ro_referred_back_to_applicant)){ $ro_referred_back_to_applicant_date = $ro_referred_back_to_applicant['Dmi_final_submit']['modified']; }
				if(!empty($ro_referred_back_to_mo)){ $ro_referred_back_to_mo_date = $ro_referred_back_to_mo['Dmi_mo_ro_comments_detail']['comment_date']; }
				if(!empty($ro_referred_back_to_io)){ $ro_referred_back_to_io_date = $ro_referred_back_to_io['Dmi_siteinspection_final_report']['modified']; }
				if(!empty($ro_referred_back_to_ho)){ $ro_referred_back_to_ho_date = $ro_referred_back_to_ho['Dmi_ho_comment_reply_detail']['comment_date']; }

				$ro_last_action_dates = array($ro_referred_back_to_applicant_date,$ro_referred_back_to_mo_date,$ro_referred_back_to_io_date,$ro_referred_back_to_ho_date);
				$roMostRecentActionDate= 0; $ro_last_action_date = '';
				foreach($ro_last_action_dates as $date){
				  $curDate = strtotime(str_replace('/','-',$date));
				  if ($curDate > $roMostRecentActionDate) {
					 $roMostRecentActionDate = $curDate;
					 $explode = explode(" ",$date);
					 $ro_last_action_date = $explode[0];
				  }
				}


				$ro_referred_back_to_applicant_count = $this->$Dmi_final_submit->find('first',array('fields'=>array('COUNT(status) as count'),'conditions'=>array('customer_id'=>$customer_id,'status'=>'referred_back')));
				$ro_referred_back_to_mo_count = $this->$Dmi_mo_ro_comments_detail->find('first',array('fields'=>array('COUNT(available_to) as count'),'conditions'=>array('customer_id'=>$customer_id,'available_to'=>'mo')));
				$ro_referred_back_to_io_count = $this->$Dmi_siteinspection_final_report->find('first',array('fields'=>array('COUNT(status) as count'),'conditions'=>array('customer_id'=>$customer_id,'OR'=>array('status'=>'referred_back'))));
				$ro_referred_back_to_ho_count = $this->$Dmi_ho_comment_reply_detail->find('first',array('fields'=>array('COUNT(from_user) as count'),'conditions'=>array('customer_id'=>$customer_id,'from_user'=>'ro')));

				if(!empty($applicant_last_action_date)){ $applicant_last_action_date = $applicant_last_action_date[0]['date']; }
				if(!empty($ddo_last_action_date)){ $ddo_last_action_date = $ddo_last_action_date[0]['date']; }
				if(!empty($mo_last_action_date)){ $mo_last_action_date = $mo_last_action_date[0]['date']; }
				if(!empty($io_last_action_date)){ $io_last_action_date = $io_last_action_date[0]['date']; }
				if(!empty($dyama_last_action_date)){ $dyama_last_action_date = $dyama_last_action_date[0]['date']; }
				if(!empty($jtama_last_action_date)){ $jtama_last_action_date = $jtama_last_action_date[0]['date']; }
				if(!empty($ama_last_action_date)){ $ama_last_action_date = $ama_last_action_date[0]['date']; }
				if(!empty($homo_last_action_date)){ $homo_last_action_date = $homo_last_action_date[0]['date']; }


				if(!empty($ro_referred_back_to_applicant_count)){ $ro_referred_back_to_applicant_count = $ro_referred_back_to_applicant_count[0]['count']; }
				if(!empty($ro_referred_back_to_mo_count)){ $ro_referred_back_to_mo_count = $ro_referred_back_to_mo_count[0]['count']; }
				if(!empty($ro_referred_back_to_io_count)){ $ro_referred_back_to_io_count = $ro_referred_back_to_io_count[0]['count']; }
				if(!empty($ro_referred_back_to_ho_count)){ $ro_referred_back_to_ho_count = $ro_referred_back_to_ho_count[0]['count']; }
				if(!empty($mo_replied_count)){ $mo_replied_count = $mo_replied_count[0]['count']; }
				if(!empty($io_replied_count)){ $io_replied_count = $io_replied_count[0]['count']; }
				if(!empty($dyama_replied_count)){ $dyama_replied_count = $dyama_replied_count[0]['count']; }
				if(!empty($jtama_replied_count)){ $jtama_replied_count = $jtama_replied_count[0]['count']; }
				if(!empty($ama_replied_count)){ $ama_replied_count = $ama_replied_count[0]['count']; }
				if(!empty($homo_replied_count)){ $homo_replied_count = $homo_replied_count[0]['count']; }
				if(!empty($applicant_replied_to_ro_count)){ $applicant_replied_to_ro_count = $applicant_replied_to_ro_count[0]['count']; }

			}

			$lastActionDate = array("applicant"=>$applicant_last_action_date,"ddo"=>$ddo_last_action_date,
										"mo"=>$mo_last_action_date,"io"=>$io_last_action_date,
										"dyama"=>$dyama_last_action_date,"jtama"=>$jtama_last_action_date,
										"ama"=>$ama_last_action_date,"homo"=>$homo_last_action_date,"ro"=>$ro_last_action_date);


			$allActionCounts = array("applicant_to_ro"=>$applicant_replied_to_ro_count,"applicant_to_ddo"=>$applicant_replied_to_ddo_count,
										"ro_to_applicant"=>$ro_referred_back_to_applicant_count,"ro_to_mo"=>$ro_referred_back_to_mo_count,
										"ro_to_io"=>$ro_referred_back_to_io_count,"ro_to_ho"=>$ro_referred_back_to_ho_count,"mo"=>$mo_replied_count,
										"io"=>$io_replied_count,"dyama"=>$dyama_replied_count,"jtama"=>$jtama_replied_count,
										"ama"=>$ama_replied_count,"homo"=>$homo_replied_count,"ddo"=>$ddo_referred_back_count);

			return array("date"=>$lastActionDate,"count"=>$allActionCounts);
		}


	}
?>
