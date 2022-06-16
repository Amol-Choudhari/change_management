<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;

	class DmiChangeLabChemistsDetailsTable extends Table{

		var $name = "DmiChangeLabChemistsDetails";

		public $validate = array(

				'customer_id'=>array(
					'rule'=>array('maxLength',50),
				),
				'customer_once_no'=>array(
					'rule'=>array('maxLength',200),
				),
				'delete_status'=>array(
					'rule'=>array('maxLength',20),
				),
				'chemist_name'=>array(
					'rule'=>array('maxLength',50),
				),
				'qualification'=>array(
					'rule'=>array('maxLength',50),
				),
				'experience'=>array(
					'rule'=>array('maxLength',50),
				),
				'commodity'=>array(
					'rule'=>array('maxLength',100),
				),
				'user_email_id'=>array(
					'rule'=>array('maxLength',50),
				),
				'user_once_no'=>array(
					'rule'=>array('maxLength',200),
				),
				'by_renewal_form'=>array(
					'rule'=>array('maxLength',10),
				),
				'add_in_renewal'=>array(
					'rule'=>array('maxLength',10),
				),
				'chemist_details_choice'=>array(
					'rule'=>array('maxLength',10),
				),
				'chemists_details_docs'=>array(
					'rule'=>array('maxLength',200),
				),
			);



		public function laboratoryChemistDetails(){

				if(strpos(base64_decode($_SESSION['username']), '@') !== false){//for email encoding
					$customer_id = $_SESSION['customer_id'];
				}else{
					$customer_id = $_SESSION['username'];
				}

				$M_commodity = TableRegistry::getTableLocator()->get('MCommodity');
				$show_chemist_commodity_types = $M_commodity->find('list', array('valueField'=>'commodity_name','keyField'=>'commodity_code'))->toArray();


				if(isset($_SESSION['edit_chemist_id'])){
					$hide_edit_id = array('id !='=>$_SESSION['edit_chemist_id']);
					$edit_id = $_SESSION['edit_chemist_id'];
				}else{
					$hide_edit_id = array('id IS NOT NULL');
					$edit_id = '';
				}

				$added_chemist_details = $this->find('all', array('conditions'=>array('OR'=>$hide_edit_id, 'customer_id IS'=>$customer_id,'delete_status IS NULL','by_renewal_form IS NULL'),'order'=>'id'))->toArray();

				$chemist_commodity_value=array(); 	$find_chemist_details='';	$commodity_value_edit = "";
				if(!empty($added_chemist_details)){
					$chemist_details_values = $added_chemist_details[0]->toArray();

					$i=1;
					foreach($added_chemist_details as $chemist_detail)
					{
						$chemist_commodity_details = explode(',',$chemist_detail['commodity']);
                                                $chemist_details_values[$i] = $M_commodity->find('list', array('valueField'=>'commodity_name','keyField'=>'commodity_code', 'conditions'=>array('commodity_code IN'=>$chemist_commodity_details)))->toArray();

                                                $chemist_commodity_value[$i] =implode(',',$chemist_details_values[$i]);
						$i=$i+1;
					}
				}
					$find_chemist_details = $this->find('all',array('conditions'=>array('id IS'=>$edit_id)))->first();
					if(!empty($find_chemist_details)){
						$commodity_value_edit = explode(',',$find_chemist_details['commodity']);
					}else{
						$commodity_value_edit = "";
					}

				return array($added_chemist_details,$chemist_commodity_value,$find_chemist_details,$show_chemist_commodity_types,$commodity_value_edit);

		}



		public function saveChemistDetails($customer_id,$forms_data){

			$CustomersController = new CustomersController;
			$customer_once_no = $_SESSION['once_card_no'];

			if(isset($_SESSION['edit_chemist_id'])){ $hide_edit_id = $_SESSION['edit_chemist_id']; }else{ $hide_edit_id = '';  }


			$edit_chemist_row_data = $this->find('all', array('conditions'=>array('id IS'=>$hide_edit_id,'customer_id IS'=>$customer_id,'delete_status IS NULL'),'order'=>'id'))->first();

			$chemist_name = htmlentities($forms_data['chemist_name'], ENT_NOQUOTES);
			$chemist_qualification = htmlentities($forms_data['qualification'], ENT_NOQUOTES);
			$chemist_experience = htmlentities($forms_data['experience'], ENT_NOQUOTES);
			$chemist_commodity = $forms_data['commodity'];
			$chemist_commodity_value=implode(', ',$chemist_commodity);

			if(!empty($forms_data['chemists_details_docs']->getClientFilename())){

				$file_name = $forms_data['chemists_details_docs']->getClientFilename();
				$file_size = $forms_data['chemists_details_docs']->getSize();
				$file_type = $forms_data['chemists_details_docs']->getClientMediaType();
				$file_local_path = $forms_data['chemists_details_docs']->getStream()->getMetadata('uri');

				$chemists_details_docs = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

			}else{ $chemists_details_docs = $edit_chemist_row_data['chemists_details_docs']; }


			$newEntity = $this->newEntity(array(

				'id'=>$hide_edit_id,
				'customer_id'=>$customer_id,
				'customer_once_no'=>$customer_once_no,
				'chemist_name'=>$chemist_name,
				'qualification'=>$chemist_qualification,
				'experience'=>$chemist_experience,
				'commodity'=>$chemist_commodity_value,
				'chemists_details_docs'=>$chemists_details_docs,
				'created'=>date('Y-m-d H:i:s')

			));

			if($this->save($newEntity)){ return true; }


		}

		public function deleteChemistDetails($record_id){

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
