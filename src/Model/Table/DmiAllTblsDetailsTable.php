<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
	class DmiAllTblsDetailsTable extends Table{
		
		var $name = "DmiAllTblsDetails";
		
		public $validate = array(
		
			'tbl_name'=>array(
					'rule'=>array('maxLength',200),	
					'allowEmpty'=>false,
				),	
			'tbl_registered'=>array(
					'rule'=>array('maxLength',10),	
					'allowEmpty'=>false,
				),
			'tbl_registered_no'=>array(
					'rule'=>array('maxLength',100),
				),
			'tbl_registration_docs'=>array(
					'rule'=>array('maxLength',200),
				),
		);
		
		
		public function tblsDetails(){
			
			// Takeing business year value from tank shapes table by pravin 11-08-2017
			if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
				$customer_id = $_SESSION['customer_id'];
			}else{
				$customer_id = $_SESSION['username'];
			}	
		
			
			if(isset($_SESSION['edit_tbl_id'])){ 
                            $hide_edit_id = array('id !='=>$_SESSION['edit_tbl_id']); 
                            $edit_id = $_SESSION['edit_tbl_id'];
                        
                        }else{ 
                            $hide_edit_id = array('id IS NOT NULL'); 
                            $edit_id = '';
                        }
			$added_tbls_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->toArray();
			
			$find_tbls_details = $this->find('all',array('conditions'=>array('id IS'=>$edit_id)))->first();
			
			return array($added_tbls_details,$find_tbls_details);
		
            }
		
            public function saveTblDetails($customer_id,$forms_data){

                    $CustomersController = new CustomersController;
                    $customer_once_no = $_SESSION['once_card_no'];

                    if(isset($_SESSION['edit_tbl_id'])){ $hide_edit_id = $_SESSION['edit_tbl_id']; }else{ $hide_edit_id = '';  }

                    $edit_tbl_row_data = $this->find('all', array('conditions'=>array('id IS'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->first();


                    $tbl_name = htmlentities($forms_data['tbl_name'], ENT_QUOTES);

                    //check radio btn value
                    $post_input_request = $forms_data['tbl_registered'];				
                    $tbl_registered = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
                    if($tbl_registered == null){ return false;}	

                    $tbl_registered_no = htmlentities($forms_data['tbl_registered_no'], ENT_QUOTES);

                    //file uploading
                    if(!empty($forms_data['tbl_registration_docs']->getClientFilename())){					

                            $file_name = $forms_data['tbl_registration_docs']->getClientFilename();
                            $file_size = $forms_data['tbl_registration_docs']->getSize();
                            $file_type = $forms_data['tbl_registration_docs']->getClientMediaType();
                            $file_local_path = $forms_data['tbl_registration_docs']->getStream()->getMetadata('uri');

                            $tbl_registration_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

                    }else{ $tbl_registration_docs = $edit_tbl_row_data['tbl_registration_docs']; }

                    $newEntity = $this->newEntity(array(
                            'id'=>$hide_edit_id,
                            'customer_id'=>$customer_id,
                            'customer_once_no'=>$customer_once_no,
                            'tbl_name'=>$tbl_name,
                            'tbl_registered'=>$tbl_registered,
                            'tbl_registered_no'=>$tbl_registered_no,
                            'tbl_registration_docs'=>$tbl_registration_docs,
                            'created'=>date('Y-m-d H:i:s')
                    )); 

                    if($this->save($newEntity)){
                        return true;
                    }


            }		
		
		
		public function editTblDetails($record_id,$tbl_name,$tbl_registered,$tbl_registered_no,$tbl_registration_docs){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'tbl_name'=>$tbl_name,
				'tbl_registered'=>$tbl_registered,
				'tbl_registered_no'=>$tbl_registered_no,
				'tbl_registration_docs'=>$tbl_registration_docs,
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){ return true; }
			
		}
		
		public function deleteTblDetails($record_id){
			
			$newEntity = $this->newEntity(array(
				'id'=>$record_id,
				'delete_status'=>'yes',
				'modified'=>date('Y-m-d H:i:s')
			
			)); 
			
			if($this->save($newEntity)){ return true; }		
			
		}
		
}

?>