<?php
namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	
class Dmi15DigitAllotmentDetailsTable extends Table{
	
	var $name = "Dmi15DigitAllotmentDetails";
	var $useTable = 'dmi15_digit_allotment_details';

	public function getSectionData($customer_id) {
	        
		$result = $this->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'allot_status IS Null','delete_status IS Null'),'order'=>'id asc'))->toArray();

		 $count = count($result);

		 if($count == 0){
				$result = array();
				$result[0]['customer_id'] = '';
				$result[0]['ca_unique_no'] = '';
				$result[0]['grading_lab'] = '';
				$result[0]['commodity'] = '';
				$result[0]['grade'] = '';
				$result[0]['tbl'] = '';
				$result[0]['packaging_material'] = '';
				$result[0]['authorized_printer'] = '';
				$result[0]['packet_size'] = '';
				$result[0]['packet_size_unit'] = '';
				$result[0]['no_of_packets'] = '';
				$result[0]['total_quantity'] = '';
				$result[0]['label_charge'] = '';
				$result[0]['total_label_charges'] = '';
				$result[0]['bal_agmark_replica'] = '';
				$result[0]['grading_lab'] = '';
		}
		 //$result['count'] = $count;

		 return $result;
	}

	public function saveFormDetails($forms_data,$cur_rep_no_from,$cur_rep_no_upto) {
		
		$result = false;		
		$dataValidatation = $this->postDataValidation($forms_data);
		$date = date('Y-m-d H:i:s');

		if($dataValidatation == 1 ){

			$customer_id = $_SESSION['username'];

			$i=0;
			$dataValues = array();
			foreach($forms_data['commodity'] as $key=>$val){
				
				$dataValues[] = array(
				
					'customer_id'=>$customer_id,
					'ca_unique_no'=>htmlentities($forms_data['ca_unique_no'], ENT_QUOTES),
					'grading_lab'=>htmlentities($forms_data['grading_lab'], ENT_QUOTES),
					'commodity'=>htmlentities($forms_data['commodity'][$key], ENT_QUOTES),
					'grade'=>htmlentities($forms_data['grade'][$key], ENT_QUOTES),
					'tbl'=>htmlentities($forms_data['tbl'][$key], ENT_QUOTES),
					'packaging_material'=>htmlentities($forms_data['packaging_material'][$key], ENT_QUOTES),
					'authorized_printer'=>htmlentities($forms_data['authorized_printer'][$key], ENT_QUOTES),
					'packet_size'=>htmlentities($forms_data['packet_size'][$key], ENT_QUOTES),
					'packet_size_unit'=>htmlentities($forms_data['packet_size_unit'][$key], ENT_QUOTES),
					'no_of_packets'=>htmlentities($forms_data['no_of_packets'][$key], ENT_QUOTES),
					'total_quantity'=>htmlentities($forms_data['total_quantity'][$key], ENT_QUOTES),
					'label_charge'=>htmlentities($forms_data['label_charge'][$key], ENT_QUOTES),
					'total_label_charges'=>htmlentities($forms_data['total_label_charges'][$key], ENT_QUOTES),
					'bal_agmark_replica'=>htmlentities($forms_data['bal_agmark_replica'][$key], ENT_QUOTES),
					'alloted_rep_from'=>$cur_rep_no_from[$i],
					'alloted_rep_to'=>$cur_rep_no_upto[$i],
					'created'=>$date,
					'modified'=>$date
				
				);
				 
				$i=$i+1;
				
				$result = true;
			}

			//creating entities for array
			$ModelEntity = $this->newEntities($dataValues);

			//saving data in loop
			foreach($ModelEntity as $each){
				$this->save($each);
			}

			return $result;

		}

		

	}


	public function postDataValidation($forms_data){
		
			return true;

	}

} ?>