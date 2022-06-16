<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;

	class DmiECodeLevel4RoApprovedApplsTable extends Table{
	
		var $name = "DmiECodeLevel4RoApprovedAppls";	
	
		public function saveRoApproved($approval_comment,$application_type)
		{
			$customer_id = $_SESSION['customer_id'];
			$user_email_id = $_SESSION['username'];
			
			//get flow wise applications tables
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$flow_wise_table = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
			
			$ro_so_comments_table = TableRegistry::getTableLocator()->get($flow_wise_table['ro_so_comments']);
			$Dmi_appl_current_position = TableRegistry::getTableLocator()->get($flow_wise_table['appl_current_pos']);
			$allocationTable = TableRegistry::getTableLocator()->get($flow_wise_table['allocation']);
			
			//get level 3 officer from allocation table for this application
			$get_allocation = $allocationTable->find('all',array('fields'=>'level_3','conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
			$comment_to = $get_allocation['level_3'];

			$ro_so_comments_table_entity = $ro_so_comments_table->newEntity(array(
			
				'customer_id'=>$customer_id,
				'comment_by'=>$user_email_id,
				'comment_to'=>$comment_to,
				'comment_date'=>date('Y-m-d H:i:s'),
				'comment'=>$approval_comment,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s'),
				'from_user'=>'ro',
				'to_user'=>'so'
			
			));
				
			if($ro_so_comments_table->save($ro_so_comments_table_entity)){

				$next_level = 'level_3';								
				$Dmi_appl_current_position->currentUserUpdate($customer_id,$comment_to,$next_level);
								
				//entry in RO approval table
				$ro_approval_entity = $this->newEntity(array(
		
					'customer_id'=>$customer_id,
					'user_email_id'=>$user_email_id,
					'status'=>'approved',
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')
					
				));
				
				if($this->save($ro_approval_entity)){						
					
					return true;
				}
			}
	
		}
	
	}

?>