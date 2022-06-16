<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	use Cake\Routing\Router;
	
	class DmiAllTanksDetailsTable extends Table{
		
		var $name = "DmiAllTanksDetails";
		
		public $validate = array(
		
			'tank_no'=>array(
					'rule'=>array('maxLength',100),	
					'allowEmpty'=>false,
				),											
			'street_address'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',10),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
			'tank_size'=>array(
					'rule'=>array('maxLength',50),	
					'allowEmpty'=>false,
				),
			'tank_capacity'=>array(
					'rule'=>array('maxLength',20),	
					'allowEmpty'=>false,
				),
				
		);
		
		public function tanksDetails($user_email_id,$oil_type,$record_added_for){
                                    
            if(empty($oil_type)){ $oil_type = array('oil_type IS NULL'); }
			else{ $oil_type = array('oil_type'=>$oil_type); }
			
			if(isset($_SESSION['edit_tank_id'])){ $hide_edit_id = array('id !='=>$_SESSION['edit_tank_id']); }
			elseif(isset($_SESSION['edit_storage_tank_id'])){ $hide_edit_id = array('id !='=>$_SESSION['edit_storage_tank_id']); $oil_type = array('oil_type IS NULL'); }
			elseif(isset($_SESSION['edit_const_oils_tank_id'])){ $hide_edit_id = array('id !='=>$_SESSION['edit_const_oils_tank_id']); }
			elseif(isset($_SESSION['edit_bevo_oils_tank_id'])){ $hide_edit_id = array('id !='=>$_SESSION['edit_bevo_oils_tank_id']); }else{ $hide_edit_id = array('id IS NOT NULL');  }
			
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
				if($record_added_for=='Application'){	
					$conditions = array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,'oil_type IS NULL','user_email_id IS NULL','delete_status IS NULL');
				}else{
					$conditions = array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,$oil_type,'user_email_id IS NOT NULL','delete_status IS NULL');
				}
			}else{				
				$customer_id = $_SESSION['username'];                               
				$conditions = array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,'oil_type IS NULL','user_email_id IS NULL','delete_status IS NULL');
			                     
            }
			
			$Dmi_tank_shape = TableRegistry::getTableLocator()->get('DmiTankShapes');
			$tank_shapes = $Dmi_tank_shape->find('list',array('valueField'=>'tank_shapes','conditions'=>array('delete_status IS NULL')))->toArray();	
				
			$added_tanks_details = $this->find('all', array('conditions'=>$conditions,'order'=>'id'))->toArray();
			//print_r($added_tanks_details); exit;
			$i=1;
			$show_tank_shape = null;
			foreach($added_tanks_details as $each_tank)
			{
				$tank_shape_value = $each_tank['tank_shape'];						
				$show_tank_shape[$i] = $tank_shapes[$tank_shape_value];
				$i=$i+1;	
			}
			
			return array($tank_shapes,$show_tank_shape,$added_tanks_details);
		}
		
			
	//methods for IO user to save CA non bevo storage tanks details
	
		public function saveUserTankDetails($customer_id,$tank_no,$tank_shape,$tank_size,$tank_capacity,$oil_type){
			
			$user_email_id = $_SESSION['username'];
			$user_once_no = $_SESSION['once_card_no'];
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'user_once_no'=>$user_once_no,
				'user_email_id'=>$user_email_id,
				'tank_no'=>$tank_no,
				'tank_shape'=>$tank_shape,
				'tank_size'=>$tank_size,
				'tank_capacity'=>$tank_capacity,
				'oil_type'=>$oil_type,
				'created'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){  return true; }			
		}
				
		
		public function editUserTankDetails($record_id,$tank_no,$tank_shape,$tank_size,$tank_capacity,$oil_type){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'tank_no'=>$tank_no,
				'tank_shape'=>$tank_shape,
				'tank_size'=>$tank_size,
				'tank_capacity'=>$tank_capacity,
				'modified'=>date('Y-m-d H:i:s')
				
			));
			if($this->save($newEntity)){ return true;  }
		}
				
		public function deleteUserTankDetails($record_id){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'delete_status'=>'yes',
				'modified'=>date('Y-m-d H:i:s')			
			));
			
			if($this->save($newEntity)){  return true; }			
		}
		
		
/***************************************////*************************************************/	
		
	//Methods for Applicant to save tank details

		public function saveCustomerTankDetails($customer_id,$tank_no,$tank_shape,$tank_size,$tank_capacity){
			
			$user_email_id = $_SESSION['username'];
			$customer_once_no = $_SESSION['once_card_no'];
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'customer_once_no'=>$customer_once_no,
				'tank_no'=>$tank_no,
				'tank_shape'=>$tank_shape,
				'tank_size'=>$tank_size,
				'tank_capacity'=>$tank_capacity,
				'created'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){  return true; }			
		}
				
		public function editCustomerTankDetails($record_id,$tank_no,$tank_shape,$tank_size,$tank_capacity){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'tank_no'=>$tank_no,
				'tank_shape'=>$tank_shape,
				'tank_size'=>$tank_size,
				'tank_capacity'=>$tank_capacity,
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){  return true; }	
			
		}
		
		
		public function deleteCustomerTankDetails($record_id){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'delete_status'=>'yes',
				'modified'=>date('Y-m-d H:i:s')
			
			));
			
			if($this->save($newEntity)){  return true; }			
		}
		
		
}

?>