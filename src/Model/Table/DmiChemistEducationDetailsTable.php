<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\CustomersController;

class DmiChemistEducationDetailsTable extends Table{

	var $name = "DmiChemistEducationDetails";
	var $useTable = 'dmi_chemist_education_details';

	public function sectionFormDetails($chemist_id) {

		$result = array();

		$result = $this->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id,'is_latest'=>1),'order'=>'id asc'))->toArray();
		//to get last record for status, as above query get order in ASC. on 28-04-2022 by Amol
		if(!empty($result)){
			$getLastStatus = $this->find('all',array('fields'=>'form_status','conditions'=>array('customer_id IS'=>$chemist_id,'is_latest'=>1),'order'=>'id desc'))->first();
			$result[0]['form_status'] = $getLastStatus['form_status'];
		}
		
				
		// if($latest_id != null){
		// 	$result = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->toArray();			
		// }
		
		 if(empty($result)){
			$result = array();
			$result[0]['id'] = '';
			$result[0]['customer_id'] = '';
			$result[0]['education'] = '';
			$result[0]['stream'] = '';
			$result[0]['university'] = '';
			$result[0]['year'] = '';
			$result[0]['division'] = '';
			$result[0]['marks'] = '';
			$result[0]['form_status'] = '';
			$result[0]['created'] = '';
			$result[0]['modified'] = '';
			$result[0]['is_latest'] = '';
			$result[0]['customer_reply'] = '';
			$result[0]['current_level'] = '';
			$result[0]['mo_comment'] = '';
			$result[0]['ro_reply_comment'] = '';
			$result[0]['delete_mo_comment'] = '';
			$result[0]['customer_reply_date'] = '';

		}else{

			$section_id = $_SESSION['section_id'];
			$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');
			$commentDetails = $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id,'section_id IS'=>$section_id,'is_latest'=>1)))->first();
			
			if(!empty($commentDetails)){
				$reffered_back_comment = $commentDetails['comments'];
				$reffered_back_date = $commentDetails['comment_dt'];
			}else{
				$reffered_back_comment = '';
				$reffered_back_date = '';
			}
			
			$result[0]['reffered_back_comment'] = $reffered_back_comment;
			$result[0]['reffered_back_date'] = $reffered_back_date;

		}

		$DmiEducationTypes = TableRegistry::getTableLocator()->get('DmiEducationTypes');
		$DmiDivisionGrades = TableRegistry::getTableLocator()->get('DmiDivisionGrades');

		$edu_type_list = $DmiEducationTypes->find('list', array('valueField'=>'edu_type','conditions'=>array('OR'=>array('delete_status'=>'no')),'order'=>array('edu_type')))->toArray();
		$division_list = $DmiDivisionGrades->find('list', array('valueField'=>'division','conditions'=>array('OR'=>array('delete_status'=>'no')),'order'=>array('division')))->toArray();

		$edu_type = array();
        $division = array();

        foreach ($edu_type_list as $key => $value) {

			$edu_type[] = array(
				'vall' => $key,
				'label' => $value
			);
		}


		foreach ($division_list as $key => $value) {

			$division[] = array(
				'vall' => $key,
				'label' => $value
			);
		}

		for ($year = 1950 ; $year < date('Y'); $year++) {

			$year_list[] = array(
				'vall' => $year,
				'label' => $year
			);
		}


		// common add more Table Header Array
		$tableD['label'] = array(
			'0' => array(
				'0' => array(
					'col' 		=> 'Sr.no',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				),
				'1' => array(
					'col' 		=> 'Education Type',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				),
				'2' => array(
					'col' 		=> 'Stream',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				),
				'3' => array(
					'col' 		=> 'University/Institute',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				),
				'4' => array(
					'col' 		=> 'Year',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				),
				'5' => array(
					'col' 		=> 'Division/Grade',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				),
				'6' => array(
					'col' 		=> '% Marks',
					'colspan' 	=> '1',
					'rowspan' 	=> '1'
				)
			)
		);


		$loopC = "0";
		foreach($result as $row){

			$row = $row;


			$tableD['input'][$loopC] = array(

				'0' => array(
					'name'		=> null,
					'type'		=> null,
					'valid'		=> null,
					'length'	=> null
				),
				'1' => array(
					'name'		=> 'education',
					'type'		=> 'select',
					'valid'		=> 'text',
					'option'	=> $edu_type,
					'selected'	=> $row['education'],
					'class'		=> 'cvOn cvReq',
					'id'		=> 'education'
				),
				'2' => array(
					'name'		=> 'stream',
					'type'		=> 'text',
					'valid'		=> 'text',
					'maxlength'	=> '100',
					'value'		=> $row['stream'],
					'class'		=> 'cvOn cvReq cvAlphaNum cvMaxLen',
					'id'		=> 'stream'
				),
				'3' => array(
					'name'		=> 'university',
					'type'		=> 'text',
					'valid'		=> 'text',
					'maxlength'	=> '100',
					'value'		=> $row['university'],
					'class'		=> 'cvOn cvReq cvAlphaNum cvMaxLen',
					'id'		=> 'university'
				),
				'4' => array(
					'name'		=> 'year',
					'type'		=> 'select',
					'valid'		=> 'text',
					'option'	=> $year_list,
					'selected'	=> $row['year'],
					'class'		=> 'cvOn cvReq',
					'id'		=> 'year'
				),
				'5' => array(
					'name'		=> 'division',
					'type'		=> 'select',
					'valid'		=> 'text',
					'option'	=> $division,
					'selected'	=> $row['division'],
					'class'		=> 'cvOn cvReq',
					'id'		=> 'division'
				),
				'6' => array(
					'name'		=> 'marks',
					'type'		=> 'text',
					'valid'		=> 'text',
					'maxlength'	=> '6',
					'cvfloat'	=> '99.99',
					'value'		=> $row['marks'],
					'class'		=> 'cvOn cvReq cvFloat cvMaxLen',
					'id'		=> 'marks'
				)
			);
			$loopC++;

		}



		$tableForm[] = $tableD;
		$jsonTableForm = json_encode($tableForm);
		$resultIndex = count($result);
		 return array($result[$resultIndex-$resultIndex],$jsonTableForm,$result);
	}

	public function saveFormDetails($chemist_id,$forms_data) {

		$result = false;
		$return = "false";
		$dataValidatation = $this->postDataValidation($forms_data);
		$date = date('Y-m-d H:i:s');
		if($dataValidatation == 1 ){

			$section_form_details = $this->sectionFormDetails($chemist_id);

			//$id = $section_form_details[0]['id'];
			$status = 'saved';
			$created = date('Y-m-d H:i:s');
			$CustomersController = new CustomersController;
			$row_count = count($forms_data['education']);


			$section_id = $_SESSION['section_id'];
			$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');

			$currCommentRecord =  $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id'=>$chemist_id,'section_id'=>$section_id,'is_latest'=>'1')))->first();
			if(!empty($currCommentRecord)){
				$commentid = $currCommentRecord['id'];
				$reply_to = $currCommentRecord['comment_by'];
			}else{
				$commentid ='';
				$reply_to = '';
			}
			

			if(!empty($reply_to))
			{
				$comment = htmlentities($forms_data['reffered_back_comment'], ENT_QUOTES);
				
				$newEntity = $Dmi_chemist_comment->newEntity(array(
					'id'=>$commentid,
					'reply_by'=>$chemist_id,
					'reply_to'=>$reply_to,
					'reply_comment'=>$comment,
					'reply_dt'=>date('Y-m-d H:i:s')			
				));
				$Dmi_chemist_comment->save($newEntity);
				
			}	

			$this->deleteAll(array('customer_id'=>$chemist_id,'is_latest'=>1));

			for ($i=0;$i<$row_count;$i++) {

				$table = 'DmiEducationTypes';
				$post_input_request = $forms_data['education'][$i];
				$education = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function

				$table = 'DmiDivisionGrades';
				$post_input_request = $forms_data['division'][$i];
				$division = $CustomersController->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function

				$stream = htmlentities($forms_data['stream'][$i], ENT_QUOTES);
				$university = htmlentities($forms_data['university'][$i], ENT_QUOTES);
				$year = htmlentities($forms_data['year'][$i], ENT_QUOTES);
				$marks = htmlentities($forms_data['marks'][$i], ENT_QUOTES);

				$DmiChemistEducationDetailsEntity =	$this->newEntity(array(

					'customer_id'=>$chemist_id,
					'education'=>$education,
					'stream'=>$stream,
					'university'=>$university,
					'year'=>$year,
					'division'=>$division,
					'marks'=>$marks,
					'form_status'=>$status,					
					'created'=>$created,
					'modified'=>date('Y-m-d H:i:s'),
					'is_latest'=>1
				));

				if($this->save($DmiChemistEducationDetailsEntity)){

					$return = "true";
				}
			}
		} else {

			$return = "false";
		}

		if($return = "true"){

			return true;

		}else{
			return false;
		}

	}


	// To save 	RO/SO referred back  and MO reply comment
	public function saveReferredBackComment ($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to) {
//print_r($forms_data); exit;
		// Import another model in this model

		$logged_in_user = $_SESSION['username'];
		$current_level = $_SESSION['current_level'];

		$DmiOldApplicationDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');

		$CustomersController = new CustomersController;
		$oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);

		$secData =  $this->sectionFormDetails($customer_id);  

		//added date function on 31-05-2021 by Amol to convert date format, as saving null
		$created_date = $CustomersController->Customfunctions->changeDateFormat($forms_data['created']);

		if($reffered_back_to == 'Level3ToApplicant'){

			$form_status = 'referred_back';
			$reffered_back_comment = $comment;
			$reffered_back_date = date('Y-m-d H:i:s');
			$rb_comment_ul = $comment_upload;
			$ro_current_comment_to = 'applicant';
			$mo_comment = null;
			$mo_comment_date = null;
			$mo_comment_ul = null;
			$ro_reply_comment = null;
			$ro_reply_comment_date = null;
			$rr_comment_ul = null;
			$form_status = $forms_data['form_status'];

		}elseif($reffered_back_to == 'Level1ToLevel3'){

			$reffered_back_comment = null;
			$reffered_back_date = null;
			$rb_comment_ul = null;
			$ro_current_comment_to = null;
			$mo_comment = $comment;
			$mo_comment_date = date('Y-m-d H:i:s');
			$mo_comment_ul = $comment_upload;
			$ro_reply_comment = null;
			$ro_reply_comment_date = null;
			$rr_comment_ul = null;

		}elseif($reffered_back_to == 'Level3ToLevel'){

			$form_status = $forms_data['form_status'];
			$reffered_back_comment = $forms_data['reffered_back_comment'];
			$reffered_back_date = $forms_data['reffered_back_date'];
			$rb_comment_ul = $forms_data['rb_comment_ul'];
			$ro_current_comment_to = 'mo';
			$mo_comment = null;
			$mo_comment_date = null;
			$mo_comment_ul = null;
			$ro_reply_comment = $comment;
			$ro_reply_comment_date = date('Y-m-d H:i:s');
			$rr_comment_ul = $comment_upload;

		}

		$newEntity = $this->newEntity(array(

			'customer_id'=>$customer_id,
			'education'=>$forms_data['education'],
			'stream'=>$forms_data['stream'],
			'university'=>$forms_data['university'],
			'year'=>$forms_data['year'],
			'division'=>$forms_data['division'],
			'marks'=>$forms_data['marks'],
			'form_status'=>$forms_data['form_status'],
			'is_latest'=>$forms_data['is_latest'],
			'created'=>$created_date,
			'modified'=>date('Y-m-d H:i:s'),
			'form_status'=>$form_status,
			'reffered_back_comment'=>$reffered_back_comment,
			'reffered_back_date'=>$reffered_back_date,
			'rb_comment_ul'=>$rb_comment_ul,
			'user_email_id'=>$_SESSION['username'],			
			'current_level'=>$current_level,
			'ro_current_comment_to'=>$ro_current_comment_to,	
			'mo_comment'=>$mo_comment,
			'mo_comment_date'=>$mo_comment_date,
			'mo_comment_ul'=>$mo_comment_ul,
			'ro_reply_comment'=>$ro_reply_comment,
			'ro_reply_comment_date'=>$ro_reply_comment_date,
			'rr_comment_ul'=>$rr_comment_ul

		));

		if($this->save($newEntity)){

			if($oldapplication == 'yes'){

					$old_certificate_details = $DmiOldApplicationDetails->oldApplicationCertificationDetails($customer_id);

				$DmiOldApplicationDetailsEntity = $DmiOldApplicationDetails->newEntity(array(
										'id'=>$old_certificate_details['id'],
										'old_certificate_pdf'=>$old_certificate_details['old_certificate_pdf'],
										'old_application_docs'=>$old_certificate_details['old_application_docs'],
				));

				if($DmiOldApplicationDetails->save($DmiOldApplicationDetailsEntity)){ return true;  }

			}else{ return true; }
		}

	}

	public function postDataValidation($forms_data){

			return true;

	}

} ?>
