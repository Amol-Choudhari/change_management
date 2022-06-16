<?php
	
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiRenewalPackerDetailsTable extends Table{
		
		var $name = "DmiRenewalPackerDetails";
		
		public $validate = array(
		
			'customer_id'=>array(
					'rule'=>array('maxLength',100),				
				),
			'customer_once_no'=>array(
					'rule'=>array('maxLength',200),				
				),
			'packer_name'=>array(
					'rule'=>array('maxLength',100),	
					'allowEmpty'=>false,	
				),
			'packer_type'=>array(
					'rule1'=>array(
							'rule'=>array('maxLength',100),
							'allowEmpty'=>false,
							'last'=>false,
						),
					'rule2'=>array(
							'rule'=>'Numeric',
						)	
				),
			'quantity_printed'=>array(
					'rule'=>array('maxLength',100),	
					'allowEmpty'=>false,	
				),
			'delete_status'=>array(
					'rule'=>array('maxLength',10),							
				),		
		
		);
		
		public function savePackerDetails($packer_name,$packer_type,$quantity_printed){
			
			$customer_id = $_SESSION['username'];
			$customer_once_no = $_SESSION['once_card_no'];
			
			$Entity = $this->newEntity(array(
			
				'customer_id'=>$customer_id,
				'customer_once_no'=>$customer_once_no,
				'packer_name'=>$packer_name,
				'packer_type'=>$packer_type,
				'quantity_printed'=>$quantity_printed,				
				'created'=>date('Y-m-d H:i:s')			
			));
			
			if($this->save($Entity)){				
				return true;				
			}
			
		}
				
		public function editPackerDetails($record_id,$packer_name,$packer_type,$quantity_printed){
			
			$Entity = $this->newEntity(array(
				'id'=>$record_id,
				'packer_name'=>$packer_name,
				'packer_type'=>$packer_type,
				'quantity_printed'=>$quantity_printed,				
				'modified'=>date('Y-m-d H:i:s')
			));
			if($this->save($Entity)){				
				return true;				
			}			
		}
				
		public function deletePackerDetails($record_id){
			
			$Entity = $this->newEntity(array(
				'id'=>$record_id,
				'delete_status'=>'yes',
				'modified'=>date('Y-m-d H:i:s')
			
			));
			if($this->save($Entity)){				
				return true;				
			}			
		}
		
		public function packerDetatils($customer_id){
				
			if(isset($_SESSION['edit_packer_id'])){ $hide_edit_id = array('id !='=>$_SESSION['edit_packer_id']); }else{ $hide_edit_id = array('id IS NOT NULL');  }
			
			$Dmi_grant_certificates_pdf = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');	
			$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
			$Dmi_packing_type = TableRegistry::getTableLocator()->get('DmiPackingTypes');
			
			$CustomersController = new CustomersController;
			
			$renewal_packaging_type = $CustomersController->Mastertablecontent->allPackingType();
			$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
			
			$packer_name = $Dmi_grant_certificates_pdf->find('all', array('fields'=>'customer_id', 'group'=>'customer_id'))->toArray();		
							
			$i=0;
			$j=0;
			$packer_renewal_name = null;
				
			foreach($packer_name as $each_packer)
			{
				 $packer_name_data = $packer_name[$i];
				 $packer_applicant_id = $packer_name_data['customer_id'];

				$split_customer_id = explode('/',$packer_applicant_id);
				// change condition by pravin 13/06/2017
				if($split_customer_id[1]==1)
				{
					$fetch_data = $Dmi_firm->find('all', array('fields'=>array('id','firm_name','customer_id'), 'conditions'=>array('customer_id IS'=>$packer_applicant_id)))->first();
					
					$packer_renewal_name[$j] = $fetch_data['firm_name'];
					$packer_renewal_id[$j] = $fetch_data['id'];
					$packer_id[$j] = $fetch_data['customer_id'];
					$packer_renewal_name[$j] =$packer_renewal_name[$j].' - '.$packer_id[$j];
				$j=$j+1;
				}
					
				$i=$i+1;
			}	
					
			//to show added packer table	
			$added_packers_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,$grantDateCondition,'delete_status IS NULL'),'order'=>'id'))->toArray();
									
			$i=1;
			$show_packer_name=null;
			$show_packer_type=null;
			foreach($added_packers_details as $each_packer)
			{
				//update below code on 26-07-2017 by Pravin
				$packer_type_value = $each_packer['packer_type'];						
				$show_packer_type[$i] = $renewal_packaging_type[$packer_type_value];
				$show_packer_name[$i] = $each_packer['packer_name'];
				$i=$i+1;	
			}					
										
			return array($added_packers_details,$packer_renewal_name,$show_packer_name,$show_packer_type,$renewal_packaging_type);
			
		}
}

?>