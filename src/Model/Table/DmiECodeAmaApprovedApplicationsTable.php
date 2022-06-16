<?php 
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\CustomersController;
	
	class DmiECodeAmaApprovedApplicationsTable extends Table{
	
	var $name = "DmiECodeAmaApprovedApplications";
	
	
	public function saveAmaApproved($approval_comment,$application_type)//new arguments on 05-05-2021 by Amol
	{
		//get flow wise applications tables
		$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$flow_wise_table = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		
		$Dmi_ho_allocation = TableRegistry::getTableLocator()->get($flow_wise_table['ho_level_allocation']);
		$Dmi_ho_comment_reply_detail = TableRegistry::getTableLocator()->get($flow_wise_table['ho_comment_reply']);
		$Dmi_all_applications_current_position = TableRegistry::getTableLocator()->get($flow_wise_table['appl_current_pos']);
	
	
		$customer_id = $_SESSION['customer_id'];
		$user_email_id = $_SESSION['username'];
		$user_once_no = $_SESSION['once_card_no'];
		
		//this query and condition added on 04-08-2017 by Amol to save "approved" comment on approved button
		$find_ho_allocation = $Dmi_ho_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();	
		
		$from_user = 'ama';
		$to_user = 'jt_ama';
		$comment_to_email_id = $find_ho_allocation['jt_ama'];
		
		
		if(!empty($comment_to_email_id))//Condition added on 04-08-2017 by Amol	
		{		
			
			$Dmi_ho_comment_reply_entity = $Dmi_ho_comment_reply_detail->newEntity(array(
			
					'customer_id'=>$customer_id,
					'comment_by'=>$_SESSION['username'],
					'comment_to'=>$comment_to_email_id,
					'comment_date'=>date('Y-m-d H:i:s'),
					'comment'=>$approval_comment,//updated variable on 05-05-2021 by Amol
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s'),
					'from_user'=>$from_user,
					'to_user'=>$to_user
			
			));
			
			if($Dmi_ho_comment_reply_detail->save($Dmi_ho_comment_reply_entity)){
				
					//update ho_allocation current level
					/* If ama approved application then application send to jt_ama. Dispaly application in "replied to me" window.  
					   But comment window and "send_to" option not available when application open. So we update the entery in "Dmi_ho_allocation"
					   to show the comment window and "send_to option"  (Done By pravin 10-01-2018)
					*/
					$Dmi_ho_allocation->updateAll(array('current_level' => "$comment_to_email_id"),array('customer_id' => $customer_id));

					$current_level = 'level_4';								
					$Dmi_all_applications_current_position->currentUserUpdate($customer_id,$comment_to_email_id,$current_level);//call to custom function from model		
							
				//entry in AMA approval table
				$ama_approval_entity = $this->newEntity(array(
		
					'customer_id'=>$customer_id,
					'user_email_id'=>$user_email_id,
					'user_once_no'=>$user_once_no,
					'status'=>'approved',
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')
					
				));
				
				if($this->save($ama_approval_entity)){						
					
					return true;
				}
			}
		}

	}
		
}

?>