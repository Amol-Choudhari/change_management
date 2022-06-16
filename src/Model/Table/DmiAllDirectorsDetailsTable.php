<?php
	
namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;

class DmiAllDirectorsDetailsTable extends Table{
		
	var $name = "DmiAllDirectorsDetails";
		
	public $validate = array(
	
		'd_name'=>array('rule'=>array('maxLength',100),'allowEmpty'=>false,),											
		'd_address'=>array('rule'=>array('maxLength',200),'allowEmpty'=>false,),
	);
			
			
	/* Fetch particular firm directors_details (Done by pravin 11/09/2018)*/
	public function allDirectorsDetail($customer_id){
					
		if (isset($_SESSION['edit_directors_details_id'])) { 
		
			$hide_edit_id = array('id !='=>$_SESSION['edit_directors_details_id']); 
		
		} else { 
		
			$hide_edit_id = array('id IS NOT NULL');  
		}
		
		$added_directors_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->toArray();			
	
		return	$added_directors_details; 
	}
		

	//Save Directors Details
	public function saveDirectorsDetails($d_name,$d_address){
		
		$user_email_id = $_SESSION['username'];
		$user_once_no = $_SESSION['once_card_no'];
		
		// Check login user is applicant? if applicant then customer_id is session of username
		// Done By pravin 29-09-2017
		if (preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $user_email_id,$matches)==1) {

			$customer_id = $_SESSION['username'];
			$user_email_id = 'old_application';
		
		} else {
			
			$customer_id = $_SESSION['customer_id'];
			$user_email_id = $_SESSION['username'];
		}
		
		$newEntity = $this->newEntity(array(
		
			'customer_id'=>$customer_id,
			'd_name'=>$d_name,
			'd_address'=>$d_address,
			'user_email_id'=>$user_email_id,
			'user_once_no'=>$user_once_no,
			'created'=>date('Y-m-d H:i:s')
		)); 
		
		if ($this->save($newEntity)) {
			
			return true;
			
		}
	}
		

	//Edit Directors Details	
	public function editDirectorsDetails($record_id,$d_name,$d_address){
		
		$newEntity = $this->newEntity(array(

			'id'=>$record_id,
			'd_name'=>$d_name,
			'd_address'=>$d_address,
			'modified'=>date('Y-m-d H:i:s')
		)); 
		
		if ($this->save($newEntity)) {
			
			return true;
		}
	}
	
	
	//Delete Directors Details Method	
	public function deleteDirectorsDetails($record_id){
		
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