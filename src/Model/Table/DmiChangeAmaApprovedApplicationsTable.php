<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\CustomersController;

	class DmiChangeAmaApprovedApplicationsTable extends Table{
	
		var $name = "DmiChangeAmaApprovedApplications";
	
	
		public function saveAmaApproved()
		{
			$Dmi_ho_allocation = TableRegistry::getTableLocator()->get('DmiChangeHoAllocations');
			$Dmi_ho_comment_reply_detail = TableRegistry::getTableLocator()->get('DmiChangeHoComments');
			$Dmi_all_applications_current_position = TableRegistry::getTableLocator()->get('DmiChangeAllCurrentPositions');
		
		
			$customer_id = $_SESSION['customer_id'];
			$user_email_id = $_SESSION['username'];
			$user_once_no = $_SESSION['once_card_no'];
			
			//this query and condition added on 04-08-2017 by Amol to save "approved" comment on approved button
			$find_ho_allocation = $Dmi_ho_allocation->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();	
			
			//below code , function and condition added on 16-09-2019 by Amol
			//to check if application is CA BEVO then approved by JTAMA and send to DYAMA. not by AMA. as suggested
				$CustomersController = new CustomersController;
				//check CA BEVO Applicant		
				$ca_bevo_applicant = $CustomersController->Customfunctions->checkCaBevo($customer_id);
				
				if($ca_bevo_applicant == 'yes'){
					
					$from_user = 'jt_ama';
					$to_user = 'dy_ama';
					$comment_to_email_id = $find_ho_allocation['dy_ama'];
				}else{
					
					$from_user = 'ama';
					$to_user = 'jt_ama';
					$comment_to_email_id = $find_ho_allocation['jt_ama'];
				}
			//till here, and above variables used below			
			$comment = 'approved';
			
			
			if(!empty($comment_to_email_id))//Condition added on 04-08-2017 by Amol	
			{		
				
				$Dmi_ho_comment_reply_entity = $Dmi_ho_comment_reply_detail->newEntity(array(
				
						'customer_id'=>$customer_id,
						'comment_by'=>$_SESSION['username'],
						'comment_to'=>$comment_to_email_id,
						'comment_date'=>date('Y-m-d H:i:s'),
						'comment'=>$comment,
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
								
					
					$all_current_pos_entity = $this->newEntity(array(
			
						'customer_id'=>$customer_id,
						'user_email_id'=>$user_email_id,
						'user_once_no'=>$user_once_no,
						'status'=>'approved',
						'created'=>date('Y-m-d H:i:s'),
						'modified'=>date('Y-m-d H:i:s')
						
					));
					
					if($this->save($all_current_pos_entity)){						
						
						return true;
					}
				}
			}
	
		}
	
	}

?>