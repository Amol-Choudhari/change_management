<?php
	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	
class DmiPrintingRenewalDetailsTable extends Table{
	
	var $name = "DmiPrintingRenewalDetails";
	
	public $validate = array(
	
		'customer_id'=>array(
					'rule'=>array('maxLength',100),				
					),	
					
		'customer_once_no'=>array(
					'rule'=>array('maxLength',200),				
					),
					
		'user_email_id'=>array(
					'rule'=>array('maxLength',200),				
					),
					
		'user_once_no'=>array(
					'rule'=>array('maxLength',200),				
					),
		'form_status'=>array(
					'rule'=>array('maxLength',50),				
					),
		'current_level'=>array(
					'rule'=>array('maxLength',100),				
					),
		'validity_upto'=>array(
					'rule'=>array('maxLength',100),				
					),
		'is_particulars_furnished'=>array(
					'rule'=>array('maxLength',10),				
					),
		'last_validity_period'=>array(
					'rule'=>array('maxLength',10),				
					),
		'ro_current_comment_to'=>array(
					'rule'=>array('maxLength',100),				
					),						
					
	);
	
	public function sectionFormDetails($customer_id){
		
		$CustomersController = new CustomersController;	
		$grantDateCondition = $CustomersController->Customfunctions->returnGrantDateCondition($customer_id);
			
		$latest_id = $this->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition)))->toArray();
					
			if($latest_id != null){
				$form_fields = $this->find('all', array('conditions'=>array('id'=>MAX($latest_id))))->first();		
				$form_fields_details = $form_fields;
				
			}else{
				$form_fields_details = Array ( 	'id'=>'','customer_id' => "", 'customer_once_no' => "", 'user_email_id' => "", 'user_once_no' => "", 'referred_back_comment' => "", 'referred_back_date' => "", 
												'customer_reply' => "",  'customer_reply_date' => "",'form_status'=>"", 'approved_date' => "", 'current_level' => "", 'validity_upto' => "", 'renew_upto' => "", 'is_particulars_furnished' => "yes",
												'mo_comment' => "", 'mo_comment_date' => "", 'ro_reply_comment' => "", 'ro_reply_comment_date' => "", 'created' => "", 'modified' => "", 'last_validity_period' => "",
												'delete_mo_comment' => "", 'delete_ro_reply' => "", 'delete_ro_referred_back' => "", 'delete_customer_reply' => "", 'ro_current_comment_to' => "",'rb_comment_ul'=>"",
												'mo_comment_ul'=>"",'rr_comment_ul'=>"",'cr_comment_ul'=>""); 
				
			}
			
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');	
		$firm_details = $DmiFirms->firmDetails($customer_id);
		
		$DmiPackingTypes = TableRegistry::getTableLocator()->get('DmiPackingTypes');
		$packaging_materials = explode(',',$firm_details['packaging_materials']);
		$packaging_type = $DmiPackingTypes->find('list', array('valueField'=>array('packing_type'),'keyField'=>array('id'), 'conditions'=>array('id IN'=>$packaging_materials)))->toArray();	
		
		$DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get('DmiGrantCertificatesPdfs');	
		$renewal_grant_detail = $DmiGrantCertificatesPdfs->find('all', array('conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
		
		$DmiRenewalPackerDetails = TableRegistry::getTableLocator()->get('DmiRenewalPackerDetails');
		$PackerDetails = $DmiRenewalPackerDetails->packerDetatils($customer_id);
	
		return array($form_fields_details,$packaging_type,$renewal_grant_detail,$PackerDetails);
	}
	// To save 	MO Referred Back comment	
	
	// save or update form data and comment reply by applicant
	public function saveFormDetails($customer_id,$forms_data){
		
		$Dmi_firm = TableRegistry::getTableLocator()->get('DmiFirms');
		
		$CustomersController = new CustomersController;	
		$section_form_details = $this->sectionFormDetails($customer_id);
		$firm_details = $Dmi_firm->firmDetails($customer_id);
				
		$htmlencoded_validity_upto = htmlentities($forms_data['validity_upto'], ENT_QUOTES);
		$htmlencoded_validity_upto = $CustomersController->Customfunctions->dateFormatCheck($htmlencoded_validity_upto);
		
		$post_input_request = $forms_data['is_particulars_furnished'];				
		$is_particulars_furnished = $CustomersController->Customfunctions->radioButtonInputCheck($post_input_request);//calling librabry function
		if($is_particulars_furnished == null){ return false;}	
		
		// If applicant have referred back on give section				
		if($section_form_details[0]['form_status'] == 'referred_back'){
		
			$max_id = $section_form_details[0]['id'];
			$htmlencoded_reply = htmlentities($forms_data['customer_reply'], ENT_QUOTES);
			$customer_reply_date = date('Y-m-d H:i:s');
			
			if(!empty($forms_data['cr_comment_ul']->getClientFilename())){				
				
				$file_name = $forms_data['cr_comment_ul']->getClientFilename();
				$file_size = $forms_data['cr_comment_ul']->getSize();
				$file_type = $forms_data['cr_comment_ul']->getClientMediaType();
				$file_local_path = $forms_data['cr_comment_ul']->getStream()->getMetadata('uri');
				
				$cr_comment_ul = $CustomersController->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function
		
			}else{ $cr_comment_ul = null; }
				
		}else{ 			
				$htmlencoded_reply = ''; 
				$max_id = ''; 
				$customer_reply_date = '';
				$cr_comment_ul = null;	
		}

		if(empty($section_form_details[0]['created'])){  $created = date('Y-m-d H:i:s'); }
		//added date function on 31-05-2021 by Amol to convert date format, as saving null
		else{ $created = $CustomersController->Customfunctions->changeDateFormat($section_form_details[0]['created']); }
		
		$newEntity = $this->newEntity(array(
		
			'id'=>$max_id,
			'customer_id'=>$customer_id,
			'customer_once_no'=>$_SESSION['once_card_no'],
			'validity_upto'=>$htmlencoded_validity_upto,
			'is_particulars_furnished'=>$is_particulars_furnished,
			
			'form_status'=>'saved',
			'customer_reply'=>$htmlencoded_reply,
			'customer_reply_date'=>$customer_reply_date,
			'cr_comment_ul'=>$cr_comment_ul,
			'created'=>$created,
			'modified'=>date('Y-m-d H:i:s'))); 
		
		if ($this->save($newEntity)){ return 1; };	
				
	}

	// To save 	RO/SO referred back  and MO reply comment
	public function saveReferredBackComment($customer_id,$forms_data,$comment,$comment_upload,$reffered_back_to)
	{			
		// Import another model in this model	
		
		$logged_in_user = $_SESSION['username'];
		$current_level = $_SESSION['current_level'];
		
		//added date function on 31-05-2021 by Amol to convert date format, as saving null
		$CustomersController = new CustomersController;
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
			
		}elseif($reffered_back_to = 'Level3ToLevel'){
			
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
			'customer_once_no'=>$_SESSION['once_card_no'],
			'validity_upto'=>$forms_data['validity_upto'],
			'is_particulars_furnished'=>$forms_data['is_particulars_furnished'],
			'created'=>$created_date,
			'modified'=>date('Y-m-d H:i:s'),
			'form_status'=>$form_status,
			'reffered_back_comment'=>$reffered_back_comment,
			'reffered_back_date'=>$reffered_back_date,
			'rb_comment_ul'=>$rb_comment_ul,
			'user_email_id'=>$_SESSION['username'],
			'user_once_no'=>$_SESSION['once_card_no'],
			'current_level'=>$current_level,
			'ro_current_comment_to'=>$ro_current_comment_to,	
			'mo_comment'=>$mo_comment,
			'mo_comment_date'=>$mo_comment_date,
			'mo_comment_ul'=>$mo_comment_ul,
			'ro_reply_comment'=>$ro_reply_comment,
			'ro_reply_comment_date'=>$ro_reply_comment_date,
			'rr_comment_ul'=>$rr_comment_ul			
		)); 
		
		if($this->save($newEntity)){ return true; }

	}															
														
}

?>