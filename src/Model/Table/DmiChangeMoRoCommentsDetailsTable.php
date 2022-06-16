<?php 
		namespace app\Model\Table;
		use Cake\ORM\Table;
		use App\Model\Model;
		use App\Controller\AppController;
		use App\Controller\CustomersController;
		use Cake\ORM\TableRegistry;

		class DmiChangeMoRoCommentsDetailsTable extends Table{
	
			var $name = "DmiChangeMoRoCommentsDetails";			
			

			public function commentsDetails($form_code){
				
				$logged_in_user = $_SESSION['username'];
				$customer_id = $_SESSION['customer_id'];
				
				$comments_details = $this->find('all',array('conditions'=> array('OR'=>array(
																'comment_by'=>$logged_in_user,
																'comment_to'=>$logged_in_user),
																'customer_id'=>$customer_id,
																'form_code'=>$form_code)))->toArray();	
				return $comments_details;
				
			}	

			
			//updating mo ro comments table
			public function saveCommentsDetails($customer_id,$comment_by,$comment_to){
				
				//below condition is added on 05-03-2018 by Amol to get application available to
				$current_level = $_SESSION['current_level'];
				if($current_level == 'level_1'){
					$available_to = 'ro';
				}elseif($current_level == 'level_3'){
					$available_to = 'mo';
				}
				
				$newEntity = $this->newEntity(array(					
					'customer_id'=>$customer_id,
					'comment_by'=>$comment_by,
					'comment_to'=>$comment_to,
					'comment_date'=>date('Y-m-d H:i:s'),
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s'),
					'available_to'=>$available_to //added on 05-03-2018 by Amol				
				));
				
				if($this->save($newEntity)){	return true; }else{ return false; }

			}

} ?>