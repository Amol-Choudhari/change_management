<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
		
	class DmiAllMachinesDetailsTable extends Table{
		
		var $name = "DmiAllMachinesDetails";
		
		public $validate = array(
		
			'machine_name'=>array(
					'rule'=>array('maxLength',200),	
					'allowEmpty'=>false,
				),											
			'machine_type'=>array(
					'rule1'=>array(
						'rule'=>array('maxLength',50),
						'allowEmpty'=>false,
						'last'=>false),
					'rule2'=>array(
						'rule'=>'Numeric')
				),
			'machine_no'=>array(
					'rule'=>array('maxLength',100),
					'allowEmpty'=>false,
				),
			'machine_capacity'=>array(
					'rule'=>array('maxLength',50),
					'allowEmpty'=>false,
				),
				
			);
		
		public function machineDetails($application){
			
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
			
			// Update condition to get rigt machine type. Done by Pravin Bhakare 3/06/2020
			$explode = explode('/',$customer_id);
			if($explode[1] == 1){
				$application_type = 'ca';
			}elseif($explode[1] == 2){
				$application_type = 'printing';
			}else{
				$application_type = 'laboratory';
			}
			
			$Dmi_machine_type = TableRegistry::getTableLocator()->get('DmiMachineTypes');	
			
			$machines_types = $Dmi_machine_type->find('list',array('valueField'=>'machine_types','conditions'=>array('application_type IS'=>$application_type,'delete_status IS NULL')))->toArray();	
											// change the condition delete_status IS NOT NULL from delete_status IS NULL by akash thakre
			//to show added machinery table	
			
			if(isset($_SESSION['edit_machine_id'])){ $hide_edit_id = array('id !='=>$_SESSION['edit_machine_id']); }else{ $hide_edit_id = array('id IS NOT NULL');  }
			$added_machines_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->toArray();
		
			$i=1;
		
			$show_machine_type = null;
			
			foreach($added_machines_details as $each_machine)
			{
				
				$machine_type_value = $each_machine['machine_type'];
				$show_machine_type[$i] = $machines_types[$machine_type_value];
				//print_r($smachine_types); exit;
				$i=$i+1;	
			}
			//print_r($show_machine_type); exit;

			return array($machines_types,$show_machine_type,$added_machines_details);
		}
		
		public function saveMachineDetails($machine_name,$machine_type,$machine_no,$machine_capacity){
			
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}
                        
			$customer_once_no = $_SESSION['once_card_no'];
			
			$newEntity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'customer_once_no'=>$customer_once_no,
				'machine_name'=>$machine_name,
				'machine_type'=>$machine_type,
				'machine_no'=>$machine_no,
				'machine_capacity'=>$machine_capacity,
				'created'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){
				
				return true;
				
			}
			
			
		}
		
		
		
		
		
		public function editMachineDetails($record_id,$machine_name,$machine_type,$machine_no,$machine_capacity){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'machine_name'=>$machine_name,
				'machine_type'=>$machine_type,
				'machine_no'=>$machine_no,
				'machine_capacity'=>$machine_capacity,
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){				
				return true;				
			}			
		}
		
		
		public function deleteMachineDetails($record_id){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'delete_status'=>'yes',
				'modified'=>date('Y-m-d H:i:s')
			
			));
			
			if($this->save($newEntity)){
				
				return true;
				
			}
			
			
		}
				
		
}

?>