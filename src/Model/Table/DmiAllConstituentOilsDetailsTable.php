<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
    use Cake\Routing\Router;

	class DmiAllConstituentOilsDetailsTable extends Table{
		
		var $name = "DmiAllConstituentOilsDetails";
		
		public $validate = array(
		
			'oil_name'=>array(
					'rule'=>array('maxLength',100),	
					'allowEmpty'=>false,
				),											
			'mill_name_address'=>array(
					'rule'=>array('maxLength',200),
					'allowEmpty'=>false,
				),
			'quantity_procured'=>array(
					'rule'=>array('maxLength',100),
					'allowEmpty'=>false,
				),
				
			);
		
		
		public function constituentOilsMillDetails($record_added_for) {
			
					
            if (isset($_SESSION['edit_const_oils_id'])) { 
				$hide_edit_id = array('id !='=>$_SESSION['edit_const_oils_id']); 
			} elseif (isset($_SESSION['edit_const_oil_mill_id'])) { 
				$hide_edit_id = array('id !='=>$_SESSION['edit_const_oil_mill_id']); 
			} else { 
				$hide_edit_id = array('id IS NOT NULL');  
			}
			
			if (strpos(base64_decode($_SESSION['username']), '@') !== false) {//for email encoding
                            
				$customer_id = $_SESSION['customer_id']; 
				if ($record_added_for=='Application') {
					$conditions = array('OR'=>$hide_edit_id, 'customer_id'=>$customer_id,'user_email_id IS NULL','delete_status IS NULL');
				} else {
					$conditions = array('OR'=>$hide_edit_id, 'customer_id'=>$customer_id,'user_email_id IS NOT NULL','delete_status IS NULL');
				}
			} else { 
				$customer_id = $_SESSION['username']; 
				$conditions = array('OR'=>$hide_edit_id, 'customer_id'=>$customer_id,'user_email_id IS NULL','delete_status IS NULL');
            }
		
			$added_const_oil_mill_details = $this->find('all', array('conditions'=>$conditions,'order'=>'id'))->toArray();				
			return $added_const_oil_mill_details;
		}
	   
	   
	   
		// Methods to store details form applicant side
		public function saveConstOilsDetails($oil_name,$mill_name_address,$quantity_procured) {
			
			if (strpos(base64_decode($_SESSION['username']), '@') !== false) {//for email encoding
               $customer_id = $_SESSION['customer_id'];
            } else {
                $customer_id = $_SESSION['username'];
            }    
          
			$customer_once_no = $_SESSION['once_card_no'];
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'customer_once_no'=>$customer_once_no,
				'oil_name'=>$oil_name,
				'mill_name_address'=>$mill_name_address,
				'quantity_procured'=>$quantity_procured,
				'created'=>date('Y-m-d H:i:s')
			));
			
			if ($this->save($newEntity)) {
				
				return true;
			}
		}
		
		
		
		
		
		public function editConstOilsDetails($record_id,$oil_name,$mill_name_address,$quantity_procured) {
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'oil_name'=>$oil_name,
				'mill_name_address'=>$mill_name_address,
				'quantity_procured'=>$quantity_procured,
				'modified'=>date('Y-m-d H:i:s')
			
			));
			if ($this->save($newEntity)) {
				
				return true;
				
			}
		}
		
		
		
		
		public function deleteConstOilsDetails($record_id) {
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'delete_status'=>'yes',
				'modified'=>date('Y-m-d H:i:s')
			
			));
			
			if ($this->save($newEntity)) {
				
				return true;
				
			}
		}
		
		
		
		
		
		
		/****************************/////**********************************************///		
		
		
		
		
		
		
		
		
		// Methods to store details form IO user side
	
		public function saveUserConstOilMillDetails($customer_id,$oil_name,$mill_name_address,$quantity_procured) {
			
			$user_email_id = $_SESSION['username'];
			$user_once_no = $_SESSION['once_card_no'];
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'user_email_id'=>$user_email_id,
				'user_once_no'=>$user_once_no,
				'oil_name'=>$oil_name,
				'mill_name_address'=>$mill_name_address,
				'quantity_procured'=>$quantity_procured,
				'created'=>date('Y-m-d H:i:s')
			
			));
			
			if ($this->save($newEntity)) {
				
				return true;
				
			}
		}
		
		
		
		
		
		public function editUserConstOilMillDetails($record_id,$oil_name,$mill_name_address,$quantity_procured) {
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'oil_name'=>$oil_name,
				'mill_name_address'=>$mill_name_address,
				'quantity_procured'=>$quantity_procured,
				'modified'=>date('Y-m-d H:i:s')
			));
			
			if ($this->save($newEntity)) {
				
				return true;
			}
		}
		
		
		
		
		public function deleteUserConstOilMillDetails($record_id) {
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'delete_status'=>'yes',
				'modified'=>date('Y-m-d H:i:s')
			
			));
			if ($this->save($newEntity)) {				
				return true;
				
			}
		}
		
	
	}

?>