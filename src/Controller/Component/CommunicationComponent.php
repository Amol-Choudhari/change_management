<?php
	namespace app\Controller\Component;
	use Cake\Controller\Controller;
	use Cake\Controller\Component;
	use Cake\Controller\ComponentRegistry;
	use Cake\ORM\Table;
	use Cake\ORM\TableRegistry;
	use Cake\Datasource\EntityInterface;

	class CommunicationComponent extends Component {


		public $components= array('Session','Customfunctions');
		public $controller = null;
		public $session = null;

		public function initialize(array $config): void {
			parent::initialize($config);
			$this->Controller = $this->_registry->getController();
			$this->Session = $this->getController()->getRequest()->getSession();
		}


		//compare last referred back date of final submit table and current section table
		public function lastRecordWithDeleteNull($tablename,$customer_id){

			// Get last grant date to get latest record of the application. Done by Pravin Bhakare
			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
			$tablename = TableRegistry::getTableLocator()->get($tablename);//initialize model in component

			$latest_modified_date = null;
			$latest_modified_date_id = $tablename->find('all', array('fields'=>'modified', 'conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition),'order'=>array('id desc')))->first();
			if(!empty($latest_modified_date_id)){
				$latest_modified_date = $latest_modified_date_id['modified'];
			}

			$list_record_with_delete_null = $tablename->find('list', array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,
							  'delete_mo_comment IS NULL', 'delete_ro_referred_back  IS NULL', 'ro_current_comment_to'=>'mo', 'modified IS'=>$latest_modified_date)))->toArray();


			if($list_record_with_delete_null)
			{
			  $last_record_with_delete_null = $tablename->find('all', array('conditions'=>array('id'=>max($list_record_with_delete_null))))->first();
			}
			else{

				$list_record_with_delete_null = $tablename->find('list', array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,
							  'OR'=>array('ro_reply_comment_date IS NOT NULL','mo_comment_date IS NOT NULL'),
							  'delete_mo_comment IS NULL', 'delete_ro_referred_back IS NULL')))->toArray();

				if(!empty($list_record_with_delete_null))
				{
					$last_record_with_delete_null = $tablename->find('all', array('conditions'=>array('id'=>max($list_record_with_delete_null))))->first();
				}
				else{
						$last_record_with_delete_null = null;
				}
			}

			$this->Controller->set('last_record_with_delete_null',$last_record_with_delete_null);

			return $last_record_with_delete_null;

		}


		public function editDeleteOptionForMoRoCommunication($tablename,$customer_id,$current_level,$last_record_with_delete_null){

			// Get last grant date to get latest record of the application. Done by Pravin Bhakare
			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

			$Dmi_tablename = TableRegistry::getTableLocator()->get($tablename);//initialize model in component

			$application_type = $this->Session->read('application_type');
			$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();

			$Dmi_mo_ro_comments_detail = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['commenting_with_mo']);//initialize model in component
			$username = $this->Session->read('username');


			$comment_id_list = $Dmi_mo_ro_comments_detail->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
			$last_comment_by = null;
			if(!empty($comment_id_list))
			{
				$last_comment_by = $Dmi_mo_ro_comments_detail->find('all',array('fields'=>'comment_by','conditions'=>array('id'=>max($comment_id_list))))->first();

			}
			$this->Controller->set('last_comment_by',$last_comment_by);



			//Date of last final submit comment by current user
			$current_user_comment_id_list = $Dmi_mo_ro_comments_detail->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'comment_by'=>$username)))->toArray();
			$last_submit_comment_date = null;
			if(!empty($current_user_comment_id_list))
			{
				$find_date = $Dmi_mo_ro_comments_detail->find('all',array('conditions'=>array('id'=>max($current_user_comment_id_list))))->first();
				$last_submit_comment_date = $find_date['created'];
			}
			$this->Controller->set('last_submit_comment_date',$last_submit_comment_date);



			//compare two dates to show edit/delete comments options
			$comment_reply_date = null;
			if($current_level == 'level_1' && !empty($last_record_with_delete_null))//if level 1 check MO comment Date
			{
				$comment_reply_date = $last_record_with_delete_null['mo_comment_date'];
			}
			elseif($current_level == 'level_3' && !empty($last_record_with_delete_null))//if level 3 check Ro Reply Date
			{
				$comment_reply_date = $last_record_with_delete_null['ro_reply_comment_date'];
			}


			// Updated Date comparison logic to solve issue of date comparison on month change (by Pravin 12-08-2017)
			$comment_reply_date = strtotime(str_replace('/','-',(string) $comment_reply_date));  // added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-10-2022]
			$last_submit_comment_date = strtotime(str_replace('/','-',(string) $last_submit_comment_date)); // added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-10-2022]

			//compare Sent to date and save comment/reply date
			if($comment_reply_date > $last_submit_comment_date)
			{
				$show_edit_delete_options = 'yes';
			}
			else{

				$show_edit_delete_options = 'no';
			}
			$this->Controller->set('show_edit_delete_options',$show_edit_delete_options);

		}


		public function saveEditedMoComment($tablename,$comment_by_mo,$mo_comment_ul,$redirect_location){

			$id = $this->Session->read('edit_mo_comment_id');
			$tablename = TableRegistry::getTableLocator()->get($tablename);//initialize model in component

				$tablenameEntity = $tablename->newEntity(array(
					'id'=>$id,
					'mo_comment'=>$comment_by_mo,
					'mo_comment_date'=>date('Y-m-d H:i:s'),
					'mo_comment_ul'=>$mo_comment_ul,
					'modified'=>date('Y-m-d H:i:s')
				));

				if($tablename->save($tablenameEntity)){

					$this->Session->delete('edit_mo_comment_id');
					$this->Controller->redirect($redirect_location);
				}
		}


		public function saveEditedRoReply($tablename,$ro_reply,$rr_comment_ul,$redirect_location){

			$id = $this->Session->read('edit_ro_reply_id');
			$tablename = TableRegistry::getTableLocator()->get($tablename);//initialize model in component

				$tablenameEntity = $tablename->newEntity(array(
					'id'=>$id,
					'ro_reply_comment'=>$ro_reply,
					'ro_reply_comment_date'=>date('Y-m-d H:i:s'),
					'rr_comment_ul'=>$rr_comment_ul,
					'modified'=>date('Y-m-d H:i:s'),
					'ro_current_comment_to'=>'mo'
				));

				if($tablename->save($tablenameEntity)){

					$this->Session->delete('edit_ro_reply_id');
					$this->Controller->redirect($redirect_location);
				}
		}

		public function editDeleteOptionForRoApplicantCommunication($customer_id,$reffered_back_date){

			$application_type = $this->Session->read('application_type');
			$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();

			$Dmi_final_submit = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['application_form']);//initialize model in component

			$referred_back_id_list = $Dmi_final_submit->find('list',array('conditions'=>array('customer_id IS'=>$customer_id,'status'=>'referred_back','current_level'=>'level_3')))->toArray();
				$last_submit_referred_back_date = null;
				if(!empty($referred_back_id_list))
				{
					$find_referred_back_date = $Dmi_final_submit->find('all',array('conditions'=>array('id'=>max($referred_back_id_list))))->first();
					$last_submit_referred_back_date = $find_referred_back_date['created'];
				}
				$this->Controller->set('last_submit_referred_back_date',$last_submit_referred_back_date);



				//compare last referred back date of final submit table and current section table
				$section_last_referred_back_date = $reffered_back_date;

				// Updated Date comparison logic to solve issue of date comparison on month change (by Pravin 12-08-2017)
				$section_last_referred_back_date = strtotime(str_replace('/','-',(string) $section_last_referred_back_date)); // added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-10-2022]
				$last_submit_referred_back_date = strtotime(str_replace('/','-',(string) $last_submit_referred_back_date)); // added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-10-2022]

				if($section_last_referred_back_date > $last_submit_referred_back_date)
				{
					$show_ro_edit_delete = 'yes';
				}
				else{

					$show_ro_edit_delete = 'no';
				}
				$this->Controller->set('show_ro_edit_delete',$show_ro_edit_delete);

		}

		public function saveEditedReferredBack($tablename,$referred_back,$rb_comment_ul,$redirect_location){

			$id = $this->Session->read('edit_referred_back_id');
			$tablename = TableRegistry::getTableLocator()->get($tablename);//initialize model in component

			$tablenameEntity = $tablename->newEntity(array(
				'id'=>$id,
				'reffered_back_comment'=>$referred_back,
				'reffered_back_date'=>date('Y-m-d H:i:s'),
				'rb_comment_ul'=>$rb_comment_ul,
				'ro_current_comment_to'=>'applicant',
				'modified'=>date('Y-m-d H:i:s')
			));

			if($tablename->save($tablenameEntity)){

				$this->Session->delete('edit_referred_back_id');
				$this->Controller->redirect($redirect_location);
			}

		}

		// Get Reply and Referredback Comment History for chemist flow. Done by Aakash Thakare, 30-09-2021
		public function singleWindowCommentHistory($customer_id)
		{
			$section_id = $this->Session->read('section_id');
			$username = $this->Session->read('username');
			
			$application_dashboard = $this->Session->read('application_dashboard');

			// Pravin bhakare 03-10-2021
			$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');
			
			if($application_dashboard == 'chemist'){

				$chemist_reply_history = $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'section_id IS'=>$section_id)))->toArray();
				$this->Controller->set('chemist_referredback_history',$chemist_reply_history);
				
				$referredbacksection = $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'section_id IS'=>$section_id,'is_latest'=>1)))->first();
				$this->Controller->set('referredbacksection',$referredbacksection);

			}elseif($application_dashboard == 'ro'){	

				$chemist_referredback_history = $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'section_id IS'=>$section_id,'comment_by IS'=>$username)))->toArray();
				$this->Controller->set('chemist_referredback_history',$chemist_referredback_history);

				$atleastOneComment = $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'is_latest'=>1,'comment_by IS'=>$username)))->toArray();
				$this->Controller->set('atleastOneComment',$atleastOneComment);
			}	
		}

		//Saved referredback comments in chemist flow, Done Aakash Thakare 30-09-2021
		public function singleWindowReferredback($data,$allSectionDetails){
		
			
			$comment_to = $this->Session->read('customer_id');
			$comment_by = $this->Session->read('username');
			$section_id = $this->Session->read('section_id');
			$commentid= '';	
			
			$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');

			$commentDetails = $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id' => $comment_to,'comment_by'=>$comment_by,'section_id'=>"$section_id",'is_latest'=>'1')))->first();
			
			if(!empty($commentDetails)){

				$commentid = $commentDetails['id'];
				
				if($data['reffered_back_id'] == ''){
					$comment = $commentDetails['comments'].' '.htmlentities($data['reffered_back_comment'], ENT_QUOTES);
				}else{
					$comment = htmlentities($data['reffered_back_comment'], ENT_QUOTES);
				}
				
				
				if(!empty($commentDetails['reply_comment'])&& !empty($commentDetails['reply_dt'])){			
					$commentid= '';			
					$Dmi_chemist_comment->updateAll(
						array('is_latest' => 0),
						array('customer_id' => $comment_to,'comment_by'=>$comment_by,'section_id'=>"$section_id",'is_latest'=>'1')
					);				
					$comment = htmlentities($data['reffered_back_comment'], ENT_QUOTES);
				}
				
				
				
				
			}else{
				$comment = htmlentities($data['reffered_back_comment'], ENT_QUOTES);
			}
			
			$newEntity = $Dmi_chemist_comment->newEntity(array(

				'id'=>$commentid,
				'customer_id'=>$comment_to,
				'comment_by'=>$comment_by,
				'comment_to'=>$comment_to,
				'comments'=>$comment,
				'comment_dt'=>date('Y-m-d H:i:s'),
				'section_id'=>$section_id,
				'is_latest'=>1			   
			));
			if($Dmi_chemist_comment->save($newEntity)){
				
				$section_id = $section_id - 1;
				$section_model = $allSectionDetails[$section_id]['section_model'];
				$formtable = TableRegistry::getTableLocator()->get($section_model);
				$formtable->updateAll(
					array('form_status' => "referred_back",'ro_current_comment_to'=>'applicant'),
					array('customer_id'=>$comment_to,'is_latest'=>'1')
				);
				
				return 1;
			}else{
				return 2;
			}
			
		}


		//Get all comment history between Applicant and RO/SO on particular application
		public function applicantCommentHistory($section_model,$customer_id){

			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

			$application_type = $this->Session->read('application_type');
			$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
			$Dmi_final_submit = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['application_form']);

			$model = TableRegistry::getTableLocator()->get($section_model);

			$section_form_details = $model->sectionFormDetails($customer_id);

			$fetch_comment_reply = $model->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition ,'delete_ro_referred_back IS NULL'), 'order'=>'id'))->toArray();
			$this->Controller->set('fetch_comment_reply',$fetch_comment_reply);

			$replied_id_list = $Dmi_final_submit->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition, 'status'=>'replied','current_level'=>'level_3'),'order'=>'id DESC'))->first();
			$last_submit_replied_date = null;
			if(!empty($replied_id_list))
			{
				$last_submit_replied_date = $replied_id_list['created'];
			}
			$this->Controller->set('last_submit_replied_date',$last_submit_replied_date);

			$section_last_replied_date = $section_form_details[0]['customer_reply_date'];

			//compare last replied date of final submit table and current section table


			// Updated Date comparison logic to solve issue of date comparison on month change (by Pravin 12-08-2017)
			$last_submit_replied_date = strtotime(str_replace('/','-',(string) $last_submit_replied_date));// added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-10-2022]
			$section_last_replied_date = strtotime(str_replace('/','-',(string) $section_last_replied_date));// added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-10-2022]

			if($section_last_replied_date > $last_submit_replied_date)
			{
				$show_applicant_edit_delete = 'yes';
			}
			else{

				$show_applicant_edit_delete = 'no';
			}
			$this->Controller->set('show_applicant_edit_delete',$show_applicant_edit_delete);

		}

		// Get all IO commented history details
		public function ioCommentHistory($section_model,$customer_id,$section_form_details,$application_type){

			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

			$model = TableRegistry::getTableLocator()->get($section_model);
			$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$final_submit_table = $Dmi_flow_wise_tables_list->getFlowWiseTableDetails($application_type,'inspection_report');
			$Dmi_report_final_submit_table = TableRegistry::getTableLocator()->get($final_submit_table);

			$fetch_comment_reply = $model->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition, 'delete_ro_referred_back IS NULL'), 'order'=>'id'))->toArray();
			$this->Controller->set('fetch_comment_reply',$fetch_comment_reply);

			$referred_back_max_id = null; $reply_max_id = null;
			foreach($fetch_comment_reply as $comment_reply){

				if(!empty($comment_reply['referred_back_date'])){

					$referred_back_max_id = $comment_reply['id'];

				}

				if(!empty($comment_reply['referred_back_date'])){

					$reply_max_id = $comment_reply['id'];

				}

			}

			if(!empty($section_form_details[0]['id'])){

				$reply_id_list = $Dmi_report_final_submit_table->find('list',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition, 'status'=>'replied','current_level'=>'level_3')))->toArray();

				$last_submit_reply_date = null;
				if(!empty($reply_id_list)){

					$find_reply_date = $Dmi_report_final_submit_table->find('all',array('conditions'=>array('id'=>max($reply_id_list))))->first();
					$last_submit_reply_date = $find_reply_date['created'];
				}
				$this->Controller->set('last_submit_reply_date',$last_submit_reply_date);

				//compare last referred back date of final submit table and current section table
				$section_last_replied_date = $section_form_details[0]['io_reply_date'];

				$section_last_replied_date = strtotime(str_replace('/','-',(string) $section_last_replied_date));// added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-10-2022]
				$last_submit_reply_date = strtotime(str_replace('/','-',(string) $last_submit_reply_date));// added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-10-2022]

				if($section_last_replied_date > $last_submit_reply_date)
				{
					$show_io_edit_delete = 'yes';
				}
				else{

					$show_io_edit_delete = 'no';
				}
				$this->Controller->set('show_io_edit_delete',$show_io_edit_delete);


				$referred_back_id_list = $Dmi_report_final_submit_table->find('list',array('conditions'=>array('customer_id IS'=>$customer_id, $grantDateCondition, 'status'=>'referred_back','current_level'=>'level_3')))->toArray();
				$last_submit_referred_back_date = null;
				if(!empty($referred_back_id_list))
				{
					$find_referred_back_date = $Dmi_report_final_submit_table->find('all',array('conditions'=>array('id'=>max($referred_back_id_list))))->first();
					$last_submit_referred_back_date = $find_referred_back_date['created'];
				}
				$this->Controller->set('last_submit_referred_back_date',$last_submit_referred_back_date);

				//compare last referred back date of final submit table and current section table
				$section_last_referred_back_date = $section_form_details[0]['referred_back_date'];

				$section_last_referred_back_date = strtotime(str_replace('/','-',(string) $section_last_referred_back_date));// added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-10-2022]
				$last_submit_referred_back_date = strtotime(str_replace('/','-',(string) $last_submit_referred_back_date));// added the (string) type-cast to fix the PHP8.1.4 Depractions - Akash [06-10-2022]

				if($section_last_referred_back_date > $last_submit_referred_back_date)
				{
					$show_ro_edit_delete = 'yes';
				}
				else{

					$show_ro_edit_delete = 'no';
				}
				$this->Controller->set('show_ro_edit_delete',$show_ro_edit_delete);


			}

			$show_save_btn = '';  $show_sent_to_btn = '';	 $show_referred_back_btn = '';

			if(!empty($fetch_comment_reply)){

				if(!($comment_reply['id']==$reply_max_id &&
					$section_form_details[0]['id']==$reply_max_id &&
						$show_io_edit_delete == 'yes') &&
							$section_form_details[0]['form_status'] == 'referred_back'){
								$show_save_btn = 'yes';
							}


				if($comment_reply['id']==$reply_max_id &&
					 $section_form_details[0]['id']==$reply_max_id &&
						$show_io_edit_delete == 'yes'){
							$show_sent_to_btn = 'yes';
						}

				if(!($comment_reply['id']==$referred_back_max_id &&
						$section_form_details[0]['id']==$referred_back_max_id &&
							$show_ro_edit_delete == 'yes')){
								$show_referred_back_btn = 'yes';
						}
			}
			$this->Controller->set('show_save_btn',$show_save_btn);
			$this->Controller->set('show_sent_to_btn',$show_sent_to_btn);
			$this->Controller->set('show_referred_back_btn',$show_referred_back_btn);
		}

	}

?>
