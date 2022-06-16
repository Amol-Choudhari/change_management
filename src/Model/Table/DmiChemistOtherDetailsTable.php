<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use Cake\ORM\TableRegistry;
	use App\Controller\CustomersController;

class DmiChemistOtherDetailsTable extends Table{

	var $name = "DmiChemistOtherDetails";
	var $useTable = 'dmi_chemist_other_details';

	public function sectionFormDetails($chemist_id) {

		$result = array();

		$latest_id = $this->find('list',array('conditions'=>array('customer_id IS'=>$chemist_id)))->toArray();
		if($latest_id != null){
			$result = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->toArray();			
		}

	
		 if(empty($result)){
				$result = array();
				$result[0]['id'] = '';
				$result[0]['customer_id'] = '';
				$result[0]['social_work'] = '';
				$result[0]['prest_instit'] = '';
				$result[0]['academic_focus'] = '';
				$result[0]['articles_pub'] = '';
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
		 //$result['count'] = $count;

		 return array($result[0]);
	}


	public function saveFormDetails($chemist_id,$forms_data) {

		$result = false;
		$dataValidatation = $this->postDataValidation($forms_data);
		$date = date('Y-m-d H:i:s');

		if($dataValidatation == 1 ){

			$section_form_details = $this->sectionFormDetails($chemist_id);

			$id = $section_form_details[0]['id'];
			$status = 'saved';
			$created = date('Y-m-d H:i:s');

			$social_work = htmlentities($forms_data['social_work'], ENT_QUOTES);
			$prest_instit = htmlentities($forms_data['prest_instit'], ENT_QUOTES);
			$academic_focus = htmlentities($forms_data['academic_focus'], ENT_QUOTES);
			$articles_pub = htmlentities($forms_data['articles_pub'], ENT_QUOTES);

			$section_id = $_SESSION['section_id'];
			$Dmi_chemist_comment = TableRegistry::getTableLocator()->get('DmiChemistComments');

			$currCommentRecord =  $Dmi_chemist_comment->find('all',array('conditions'=>array('customer_id'=>$chemist_id,'section_id'=>$section_id,'is_latest'=>'1')))->first();
			//print_r($currCommentRecord); exit;
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


			$DmiChemistOtherDetailsEntity = $this->newEntity(array(

				'id'=>$id,
				'customer_id'=>$chemist_id,
				'social_work'=>$social_work,
				'prest_instit'=>$prest_instit,
				'academic_focus'=>$academic_focus,
				'articles_pub'=>$articles_pub,
				'form_status'=>$status,				
				'created'=>$created,
				'modified'=>date('Y-m-d H:i:s'),
				'is_latest'=>1
			));

			if ($this->save($DmiChemistOtherDetailsEntity)) {

				return true;

			} else {

				return $result;
			}

		}
	}

	// To save 	RO/SO referred back  and MO reply comment
	public function saveReferredBackComment ($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to) {

		// Import another model in this model

		$logged_in_user = $_SESSION['username'];
		$current_level = $_SESSION['current_level'];

		$DmiOldApplicationDetails = TableRegistry::getTableLocator()->get('DmiOldApplicationCertificateDetails');

		$CustomersController = new CustomersController;
		$oldapplication = $CustomersController->Customfunctions->isOldApplication($customer_id);

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

		}elseif($reffered_back_to == 'Level1ToLevel3'){

			$form_status = $forms_data['form_status'];
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
			'social_work'=>$forms_data['social_work'],
			'prest_instit'=>$forms_data['prest_instit'],
			'academic_focus'=>$forms_data['academic_focus'],
			'articles_pub'=>$forms_data['articles_pub'],
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
