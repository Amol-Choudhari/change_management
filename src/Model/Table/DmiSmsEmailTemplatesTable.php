<?php

namespace app\Model\Table;
use Cake\ORM\Table;
use App\Model\Model;
use App\Controller\AppController;
use App\Controller\CustomersController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Filesystem\File;

class DmiSmsEmailTemplatesTable extends Table{

	var $name = "DmiSmsEmailTemplates";

	public $validate = array(

		'sms_message'=>array(

					'rule' => 'notBlank',
				),
		'email_message'=>array(

					'rule' => 'notBlank',
				),
		'description'=>array(

				'rule' => 'notBlank',
			),
		'template_for'=>array(
				'rule'=>array('maxLength',20),
				'allowEmpty'=>false,
			),
		'email_subject'=>array(
				'rule'=>array('maxLength',200),
				'allowEmpty'=>false,
			),

	);


	public function sendMessage($message_id, $customer_id) {

		if (!isset($_SESSION['application_type'])){ $_SESSION['application_type']=null; }

		$application_type = $_SESSION['application_type'];
		
		//This Session ID is Applied for the temporary Application Type is not present.
		if ($application_type == null) {
			$application_type = isset($_SESSION['application_type_temp']);
		}

		//Load Models
		$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$DmiFinalSubmitTable = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		
		$DmiCustomers = TableRegistry::getTableLocator()->get('DmiCustomers');
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiUserRoles = TableRegistry::getTableLocator()->get('DmiUserRoles');
		$DmiSentSmsLogs = TableRegistry::getTableLocator()->get('DmiSentSmsLogs');
		$DmiSentEmailLogs = TableRegistry::getTableLocator()->get('DmiSentEmailLogs');
		$DmiPaoDetails = TableRegistry::getTableLocator()->get('DmiPaoDetails');
		$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');

		$find_message_record = $this->find('all',array('conditions'=>array('id IS'=>$message_id, 'status'=>'active')))->first();//'status'condition inserted on 24-07-2018

		//Replica and Chemist Module
		$_SESSION['chemistId'] = '';

		if (preg_match("/^[CHM]+\/[0-9]+\/[0-9]+$/", $customer_id,$matches)==1) {

			$get_packer_id = $DmiChemistRegistrations->find('all',array('fields'=>'created_by','conditions'=>array('chemist_id IS'=>$customer_id)))->first();
			$packer_id = $get_packer_id['created_by'];
			$_SESSION['chemistId'] = $customer_id;
			$customer_id = $packer_id;
		}

		$_SESSION['flow_table'] = '';
		//added this if condition on 24-07-2018 by Amol
		if (!empty($find_message_record)) {

			$destination_values = $find_message_record['destination'];
			$destination_array = explode(',',$destination_values);

			//checking applicant id pattern ex.102/2017 if primary Applicant, then dont split
			//added on 23-08-2017 by Amol
			if (!preg_match("/^[0-9]+\/[0-9]+$/",$customer_id,$matches)==1) {

				$split_customer_id = explode('/',$customer_id);
				$district_ro_code = $split_customer_id[2];
				//updated and added code to get Office table details from appl mapping Model
				$DmiApplWithRoMappings = TableRegistry::getTableLocator()->get('DmiApplWithRoMappings');
				$find_ro_email_id = $DmiApplWithRoMappings->getOfficeDetails($customer_id);
				$ro_email_id = $find_ro_email_id['ro_email_id'];
			}

			$m=0;
			$e=0;
			$destination_mob_nos = array();
			$log_dest_mob_nos = array();
			$destination_email_ids = array();



			//Applicant
			if (in_array(0,$destination_array)) {
				//checking applicant id pattern ex.102/2017 if primary Applicant added on 23-08-2017 by Amol
				if (preg_match("/^[0-9]+\/[0-9]+$/",$customer_id,$matches)==1) {

					$fetch_applicant_data = $DmiCustomers->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
					$applicant_mob_no = $fetch_applicant_data['mobile'];
					$applicant_email_id = $fetch_applicant_data['email'];

				} else {

					$fetch_applicant_data = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
					$applicant_mob_no = $fetch_applicant_data['mobile_no'];
					$applicant_email_id = $fetch_applicant_data['email'];

				}

				$destination_mob_nos[$m] = '91'.base64_decode($applicant_mob_no); //This is addded on 27-04-2021 for base64decoding by AKASH
				$log_dest_mob_nos[$m] = '91'.$applicant_mob_no;
				$destination_email_ids[$e] = base64_decode($applicant_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

				$m=$m+1;
				$e=$e+1;
			}




			//for MO/SMO (Nodal Officer)
			if (in_array(1,$destination_array)) {

				$DmiAllocations = TableRegistry::getTableLocator()->get($DmiFinalSubmitTable['allocation']);
				$find_allocated_mo = $DmiAllocations->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'level_3 IS'=>$ro_email_id),'order' => array('id' => 'desc')))->first();
				$mo_email_id = $find_allocated_mo['level_1'];

				//check if MO is allocated or not //added on 04-10-2017
				if (!empty($mo_email_id)) {

					$fetch_mo_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$mo_email_id)))->first();
					$mo_mob_no = $fetch_mo_data['phone'];

					$destination_mob_nos[$m] = '91'.base64_decode($mo_mob_no); //This is addded on 27-04-2021 for base64decoding by AKASH
					$log_dest_mob_nos[$m] = '91'.$mo_mob_no;
					$destination_email_ids[$e] = base64_decode($mo_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

				} else {

					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;
					$destination_email_ids[$e] = null;
				}


				$m=$m+1;
				$e=$e+1;

			}




			//for IO
			if (in_array(2,$destination_array)) {

				$DmiAllocations = TableRegistry::getTableLocator()->get($DmiFinalSubmitTable['allocation']);
				$find_allocated_io = $DmiAllocations->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'level_3 IS'=>$ro_email_id),'order' => array('id' => 'desc')))->first();
				$io_email_id = $find_allocated_io['level_2'];

				//check if IO is allocated or not //added on 04-10-2017
				if (!empty($io_email_id)) {

					$fetch_io_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$io_email_id)))->first();
					$io_mob_no = $fetch_io_data['phone'];

					$destination_mob_nos[$m] = '91'.base64_decode($io_mob_no);//This is addded on 27-04-2021 for base64decoding by AKASH
					$log_dest_mob_nos[$m] = '91'.$io_mob_no;
					$destination_email_ids[$e] = base64_decode($io_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

				} else {

					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;
					$destination_email_ids[$e] = null;
				}

				$m=$m+1;
				$e=$e+1;

			}



			//RO/SO
			if (in_array(3,$destination_array)) {

				$fetch_ro_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$ro_email_id)))->first();
				$ro_mob_no = $fetch_ro_data['phone'];

				$destination_mob_nos[$m] = '91'.base64_decode($ro_mob_no);//This is addded on 27-04-2021 for base64decoding by AKASH
				$log_dest_mob_nos[$m] = '91'.$ro_mob_no;
				$destination_email_ids[$e] = base64_decode($ro_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

				$m=$m+1;
				$e=$e+1;

			}



			//Dy.AMA
			if (in_array(4,$destination_array)) {

				$find_dy_ama_user = $DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('dy_ama'=>'yes')))->first();
				$dy_ama_email_id = $find_dy_ama_user['user_email_id'];

				$fetch_dy_ama_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$dy_ama_email_id)))->first();
				$dy_ama_mob_no = $fetch_dy_ama_data['phone'];

				$destination_mob_nos[$m] = '91'.base64_decode($dy_ama_mob_no);//This is addded on 27-04-2021 for base64decoding by AKASH
				$log_dest_mob_nos[$m] = '91'.$dy_ama_mob_no;
				$destination_email_ids[$e] = base64_decode($dy_ama_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

				$m=$m+1;
				$e=$e+1;

			}



			//Jt.AMA
			if (in_array(5,$destination_array)) {

				$find_jt_ama_user = $DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('jt_ama'=>'yes')))->first();
				$jt_ama_email_id = $find_jt_ama_user['user_email_id'];

				$fetch_jt_ama_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$jt_ama_email_id)))->first();
				$jt_ama_mob_no = $fetch_jt_ama_data['phone'];

				$destination_mob_nos[$m] = '91'.base64_decode($jt_ama_mob_no);//This is addded on 27-04-2021 for base64decoding by AKASH
				$log_dest_mob_nos[$m] = '91'.$jt_ama_mob_no;
				$destination_email_ids[$e] = base64_decode($jt_ama_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

				$m=$m+1;
				$e=$e+1;

			}

			//for HO MO/SMO
			if (in_array(6,$destination_array)) {

				$find_dy_ama_user = $DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('dy_ama'=>'yes')))->first();
				$dy_ama_email_id = $find_dy_ama_user['user_email_id'];

				$DmiHoAllocations = TableRegistry::getTableLocator()->get($DmiFinalSubmitTable['ho_level_allocation']);
				$find_allocated_ho_mo = $DmiHoAllocations->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'dy_ama IS'=>$dy_ama_email_id),'order' => array('id' => 'desc')))->first();
				$ho_mo_email_id = $find_allocated_ho_mo['ho_mo_smo'];

				$fetch_ho_mo_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$ho_mo_email_id)))->first();
				$ho_mo_mob_no = $fetch_ho_mo_data['phone'];

				$destination_mob_nos[$m] = '91'.base64_decode($ho_mo_mob_no);//This is addded on 27-04-2021 for base64decoding by AKASH
				$log_dest_mob_nos[$m] = '91'.$ho_mo_mob_no;
				$destination_email_ids[$e] = base64_decode($ho_mo_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

				$m=$m+1;
				$e=$e+1;

			}



			//for AMA
			if (in_array(7,$destination_array)) {

				$find_ama_user = $DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('ama'=>'yes')))->first();
				$ama_email_id = $find_ama_user['user_email_id'];


				$fetch_ama_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$ama_email_id)))->first();
				$ama_mob_no = $fetch_ama_data['phone'];

				$destination_mob_nos[$m] = '91'.base64_decode($ama_mob_no);//This is addded on 27-04-2021 for base64decoding by AKASH
				$log_dest_mob_nos[$m] = '91'.$ama_mob_no;
				$destination_email_ids[$e] = base64_decode($ama_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

				$m=$m+1;
				$e=$e+1;

			}



			//for Accounts  (Done by pravin 20-07-2018)
			if (in_array(8,$destination_array)) {

				$DmiApplicantPaymentDetails = TableRegistry::getTableLocator()->get($DmiFinalSubmitTable['payment']);//added on 20-07-2017 by Pravin
				$find_pao_id = $DmiApplicantPaymentDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order' => array('id' => 'desc')))->first();

				$pao_id =  $find_pao_id['pao_id'];
				$find_user_id =  $DmiPaoDetails->find('all',array('conditions'=>array('id IS'=>$pao_id)))->first();
				$user_id =  $find_user_id['pao_user_id'];


				$fetch_pao_data = $DmiUsers->find('all',array('conditions'=>array('id IS'=>$user_id)))->first();
				$pao_mob_no = $fetch_pao_data['phone'];
				$pao_email = $fetch_pao_data['email'];

				$destination_mob_nos[$m] = '91'.base64_decode($pao_mob_no);//This is addded on 27-04-2021 for base64decoding by AKASH
				$log_dest_mob_nos[$m] = '91'.$pao_mob_no;
				$destination_email_ids[$e] = base64_decode($pao_email);//This is addded on 01-03-2022 for base64decoding by AKASH

				$m=$m+1;
				$e=$e+1;

			}


			//RO Incharge
			if (in_array(9,$destination_array)) {

				$fetch_ro_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$ro_email_id)))->first();
				$ro_mob_no = $fetch_ro_data['phone'];

				$destination_mob_nos[$m] = '91'.base64_decode($ro_mob_no);//This is addded on 27-04-2021 for base64decoding by AKASH
				$log_dest_mob_nos[$m] = '91'.$ro_mob_no;
				$destination_email_ids[$e] = base64_decode($ro_email_id);//This is addded on 01-03-2022 for base64decoding by AKASH

				$m=$m+1;
				$e=$e+1;

			}

			//for Chemist User
			if (in_array(10,$destination_array)) {

				$find_chemist_user= $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$_SESSION['chemistId']),'order'=>'id desc'))->first();

				if (!empty($find_chemist_user)) {

					$chemist_id =  $find_chemist_user['chemist_id'];
					$chemist_mob_no = $find_chemist_user['mobile'];
					$chemist_email = $find_chemist_user['email'];

					$destination_mob_nos[$m] = '91'.base64_decode($chemist_mob_no);
					$log_dest_mob_nos[$m] = '91'.$chemist_mob_no;
					$destination_email_ids[$e] = base64_decode($chemist_email);

				} else {

					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;
					$destination_email_ids[$e] = null;
				}

				$m=$m+1;
				$e=$e+1;
			}

			
			
			$sms_message = $find_message_record['sms_message'];
			$destination_mob_nos_values = implode(',',$destination_mob_nos);
			$log_dest_mob_nos_values = implode(',',$log_dest_mob_nos);

			$email_message = $find_message_record['email_message'];
			$destination_email_ids_values = implode(',',$destination_email_ids);

			$email_subject = $find_message_record['email_subject'];

			$template_id = $find_message_record['template_id'];//added on 12-05-2021 by Amol, new field

			//replacing dynamic values in the email message
			$sms_message = $this->replaceDynamicValuesFromMessage($customer_id,$sms_message);

			//replacing dynamic values in the email message
			$email_message = $this->replaceDynamicValuesFromMessage($customer_id,$email_message);
			/*
			$b = array (
				'SMS' => $sms_message, 
				'MOBILE' => $destination_mob_nos_values,
				'EmailMessage' => $email_message,
				'Email'=> $destination_email_ids_values
			);
				
			$fp = fopen('D:/tesq.txt',"a");
			fwrite($fp, print_r($b, true));
			fclose($fp);
			*/

			//To send SMS on list of mobile nos.
			if (!empty($find_message_record['sms_message'])) {


				$sender=urlencode("AGMARK");
				
				//$uname=urlencode("aqcms.sms");
				$uname="aqcms.sms";
				
				//$pass=urlencode("Y&nF4b#7q");
				$pass="Y%26nF4b%237q";
				
				$send=urlencode("AGMARK");
				
				$dest='9860641844';
				
				$msg=urlencode($sms_message);

				// Initialize the URL variable
				//$URL="https://smsgw.sms.gov.in/failsafe/HttpLink";
				$URL="https://smsgw.sms.gov.in/failsafe/MLink";

				// Create and initialize a new cURL resource
				$ch = curl_init();
				// Set URL to URL variable
				curl_setopt($ch, CURLOPT_URL,$URL);
				// Set URL HTTPS post to 1
				curl_setopt($ch, CURLOPT_POST, true);
				// Set URL HTTPS post field values
				
				// Set URL HTTPS post field values
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				$entity_id = '1101424110000041576'; //updated on 18-11-2020

				// if message lenght is greater than 160 character then add one more parameter "concat=1" (Done by pravin 07-03-2018)
				if(strlen($msg) <= 160 ){

					curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&dlt_entity_id=$entity_id&dlt_template_id=$template_id");

				}else{

					curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&concat=1&dlt_entity_id=$entity_id&dlt_template_id=$template_id");
				}

				// Set URL return value to True to return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// The URL session is executed and passed to the browser
				$curl_output =curl_exec($ch);
				//echo $curl_output;
			
				//code to send sms ends here


				//query to save SMS sending logs in DB // added on 11-10-2017
				$DmiSentSmsLogsEntity = $DmiSentSmsLogs->newEntity(array(
					'message_id'=>$message_id,
					'destination_list'=>$log_dest_mob_nos_values,
					'mid'=>null,
					'sent_date'=>date('Y-m-d H:i:s'),
					'message'=>$sms_message,
					'created'=>date('Y-m-d H:i:s'),
					'template_id'=>$template_id //added on 12-05-2021 by Amol
				));

				$DmiSentSmsLogs->save($DmiSentSmsLogsEntity);
			}


			//email format to send on mail with content from master
			$email_format = 'Dear Sir/Madam' . "\r\n\r\n" .$email_message. "\r\n\r\n" .
							'Thanks & Regards,' . "\r\n" .
							'Directorate of Marketing & Inspection,' . "\r\n" .
							'Ministry of Agriculture and Farmers Welfare,' . "\r\n" .
							'Government of India.';



			//To send Email on list of Email ids.
			if (!empty($find_message_record['email_message'])) {

				$to = "akashthakreer@gmail.com";
				$subject = $email_subject;
				$txt = $email_format;
				$headers = "From: dmiqc@nic.in";

				mail($to,$subject,$txt,$headers);

				//query to save Email sending logs in DB // added on 11-10-2017
				$DmiSentEmailLogsEntity = $DmiSentEmailLogs->newEntity(array(

					'message_id'=>$message_id,
					'destination_list'=>$destination_email_ids_values,
					'sent_date'=>date('Y-m-d H:i:s'),
					'message'=>$sms_message,
					'created'=>date('Y-m-d H:i:s'),
					'template_id'=>$template_id //added on 12-05-2021 by Amol

				));

				$DmiSentEmailLogs->save($DmiSentEmailLogsEntity);

			}

		}//end of 1st if condition 24-07-2018

	}


	//this function is created on 08-07-2017 by Amol to replace dynamic values in message
	public function replaceDynamicValuesFromMessage($customer_id,$message) {

		//getting count before execution
		$total_occurrences = substr_count($message,"%%");

		while($total_occurrences > 0){

			$matches = explode('%%',$message);//getting string between %% & %%

			if (!empty($matches[1])) {

				switch ($matches[1]) {

					case "submission_date":

						$message = str_replace("%%submission_date%%",(string) $this->getReplaceDynamicValues('submission_date',$customer_id),$message);
						break;

					case "firm_name":

						$message = str_replace("%%firm_name%%",(string) $this->getReplaceDynamicValues('firm_name',$customer_id),$message);
						break;

					case "amount":

						$message = str_replace("%%amount%%",(string) $this->getReplaceDynamicValues('amount',$customer_id),$message);
						break;

					case "commodities":

						$message = str_replace("%%commodities%%",(string) $this->getReplaceDynamicValues('commodities',$customer_id),$message);
						break;

					case "applicant_name":

						$message = str_replace("%%applicant_name%%",(string) $this->getReplaceDynamicValues('applicant_name',$customer_id),$message);
						break;

					case "applicant_mobile_no":

						$message = str_replace("%%applicant_mobile_no%%",(string) $this->getReplaceDynamicValues('applicant_mobile_no',$customer_id),$message);
						break;

					case "company_id":

						$message = str_replace("%%company_id%%",(string) $this->getReplaceDynamicValues('company_id',$customer_id),$message);
						break;

					case "certificate_valid_upto":

						$message = str_replace("%%certificate_valid_upto%%",(string) $this->getReplaceDynamicValues('certificate_valid_upto',$customer_id),$message);
						break;

					case "premises_id":

						$message = str_replace("%%premises_id%%",(string) $customer_id,$message);
						break;

					case "firm_email":

						$message = str_replace("%%firm_email%%",(string) $this->getReplaceDynamicValues('firm_email',$customer_id),$message);
						break;

					case "firm_certification_type":

						$message = str_replace("%%firm_certification_type%%",(string) $this->getReplaceDynamicValues('firm_certification_type',$customer_id),$message);
						break;

					case "ro_name":

						$message = str_replace("%%ro_name%%",(string) $this->getReplaceDynamicValues('ro_name',$customer_id),$message);
						break;

					case "ro_mobile_no":

						$message = str_replace("%%ro_mobile_no%%",(string) $this->getReplaceDynamicValues('ro_mobile_no',$customer_id),$message); 
						break;

					case "ro_office":

						$message = str_replace("%%ro_office%%",(string) $this->getReplaceDynamicValues('ro_office',$customer_id),$message);
						break;

					case "ro_email_id":

						$message = str_replace("%%ro_email_id%%",(string) $this->getReplaceDynamicValues('ro_email_id',$customer_id),$message);
						break;

					case "mo_name":

						$message = str_replace("%%mo_name%%",(string) $this->getReplaceDynamicValues('mo_name',$customer_id),$message);
						break;

					case "mo_mobile_no":

						$message = str_replace("%%mo_mobile_no%%",(string) $this->getReplaceDynamicValues('mo_mobile_no',$customer_id),$message);
						break;

					case "mo_office":

						$message = str_replace("%%mo_office%%",(string) $this->getReplaceDynamicValues('mo_office',$customer_id),$message);
						break;

					case "mo_email_id":

						$message = str_replace("%%mo_email_id%%",(string) $this->getReplaceDynamicValues('mo_email_id',$customer_id),$message);
						break;

					case "io_name":

						$message = str_replace("%%io_name%%",(string) $this->getReplaceDynamicValues('io_name',$customer_id),$message);
						break;

					case "io_mobile_no":

						$message = str_replace("%%io_mobile_no%%",(string) $this->getReplaceDynamicValues('io_mobile_no',$customer_id),$message);
						break;

					case "io_office":

						$message = str_replace("%%io_office%%",(string) $this->getReplaceDynamicValues('io_office',$customer_id),$message);
						break;

					case "io_email_id":

						$message = str_replace("%%io_email_id%%",(string) $this->getReplaceDynamicValues('io_email_id',$customer_id),$message);
						break;

					case "dyama_name":

						$message = str_replace("%%dyama_name%%",(string) $this->getReplaceDynamicValues('dyama_name',$customer_id),$message);
						break;

					case "dyama_mobile_no":

						$message = str_replace("%%dyama_mobile_no%%",(string) $this->getReplaceDynamicValues('dyama_mobile_no',$customer_id),$message);
						break;

					case "dyama_email_id":

						$message = str_replace("%%dyama_email_id%%",(string) $this->getReplaceDynamicValues('dyama_email_id',$customer_id),$message);
						break;

					case "jtama_name":

						$message = str_replace("%%jtama_name%%",(string) $this->getReplaceDynamicValues('jtama_name',$customer_id),$message);
						break;

					case "jtama_mobile_no":

						$message = str_replace("%%jtama_mobile_no%%",(string) $this->getReplaceDynamicValues('jtama_mobile_no',$customer_id),$message);
						break;

					case "jtama_email_id":

						$message = str_replace("%%jtama_email_id%%",(string) $this->getReplaceDynamicValues('jtama_email_id',$customer_id),$message);
						break;

					case "ama_name":

						$message = str_replace("%%ama_name%%",(string) $this->getReplaceDynamicValues('ama_name',$customer_id),$message);
						break;

					case "ama_mobile_no":

						$message = str_replace("%%ama_mobile_no%%",(string) $this->getReplaceDynamicValues('ama_mobile_no',$customer_id),$message);
						break;

					case "ama_email_id":

						$message = str_replace("%%ama_email_id%%",(string) $this->getReplaceDynamicValues('ama_email_id',$customer_id),$message);
						break;

					case "io_scheduled_date":

						$message = str_replace("%%io_scheduled_date%%",(string) $this->getReplaceDynamicValues('io_scheduled_date',$customer_id),$message);
						break;

					case "applicant_email":

						$message = str_replace("%%applicant_email%%",(string) $this->getReplaceDynamicValues('applicant_email',$customer_id),$message);
						break;

					case "pao_name":

						$message = str_replace("%%pao_name%%",(string) $this->getReplaceDynamicValues('pao_name',$customer_id),$message);
						break;

					case "pao_email_id":

						$message = str_replace("%%pao_email_id%%",(string) $this->getReplaceDynamicValues('pao_email_id',$customer_id),$message);
						break;

					case "pao_mobile_no":

						$message = str_replace("%%pao_mobile_no%%",(string) $this->getReplaceDynamicValues('pao_mobile_no',$customer_id),$message);
						break;

					case "ho_mo_name":

						$message = str_replace("%%ho_mo_name%%",(string) $this->getReplaceDynamicValues('ho_mo_name',$customer_id),$message);
						break;

					case "ho_mo_mobile_no":

						$message = str_replace("%%ho_mo_mobile_no%%",(string) $this->getReplaceDynamicValues('ho_mo_mobile_no',$customer_id),$message);
						break;

					case "ho_mo_email_id":

						$message = str_replace("%%ho_mo_email_id%%", (string) $this->getReplaceDynamicValues('ho_mo_email_id',$customer_id),$message);
						break;

					case "home_link":

						$message = str_replace("%%home_link%%",(string) $_SERVER['HTTP_HOST'],$message);
						break;

					//For Replica And Chemist Module
					case "chemist_name":

						$message = str_replace("%%chemist_name%%",(string) $this->getReplaceDynamicValues('chemist_name',$customer_id),$message);
						break;

					case "chemist_id":

						$message = str_replace("%%chemist_id%%",(string) $this->getReplaceDynamicValues('chemist_id',$customer_id),$message);
						break;

					case "replica_commodities":

						$message = str_replace("%%replica_commodities%%",(string) $this->getReplaceDynamicValues('replica_commodities',$customer_id),$message);
						break;

					case "application_type":

						$message = str_replace("%%application_type%%",(string) $this->getReplaceDynamicValues('application_type',$customer_id),$message);
						break;
					
					case "packer_name":

						$message = str_replace("%%packer_name%%",(string) $this->getReplaceDynamicValues('packer_name',$customer_id),$message);
						break;
						
					case "lab_name":

						$message = str_replace("%%lab_name%%",(string) $this->getReplaceDynamicValues('lab_name',$customer_id),$message);
						break;
						
					case "printerName":

						$message = str_replace("%%printerName%%",(string) $this->getReplaceDynamicValues('printerName',$customer_id),$message);
						break;

					default:

						$message = $this->replaceBetween($message, '%%', '%%', '');
						$default_value = 'yes';
						break;
				}

			}

			if (empty($default_value)) {
				$total_occurrences = substr_count($message,"%%");//getting count after execution
			} else {
				$total_occurrences = $total_occurrences - 1;
			}

		}

		return $message;
	}

	


	// This function find and return the value of replace variable value that are used in sms/email message templete
	// Created By Pravin on 24-08-2017
	public function getReplaceDynamicValues($replace_variable_value,$customer_id){

		if (!isset($_SESSION['application_type'])) { $_SESSION['application_type']=null; }

		$application_type = $_SESSION['application_type'];

		//This Session ID is Applied for the temporary Application Type is not present.
		if ($application_type == null) {
			$application_type = isset($_SESSION['application_type_temp']);
		}

		//Get Application Type Text
		$DmiApplicationTypes = TableRegistry::getTableLocator()->get('DmiApplicationTypes');
		
		if ($application_type != null) {

			$get_application_type = $DmiApplicationTypes->find('all')->select(['application_type'])->where(['id IS'=>$application_type,'delete_status IS NULL'])->first();
			$application_type_text = $get_application_type['application_type'];
		}else{
			$application_type_text = '';
		}


		//for replica
		$chemist_name = null;
		$chemist_id = null;
		$replica_commodities = null;
		
		$CustomersController = new CustomersController;
		
		//Below Application Type = 7 condtion is added to by pass if the SMS is for Advance Payment -  AKASH [31-10-2022]
		if (!empty($application_type) && $application_type != 7) {
			
			$DmiFlowWiseTablesLists = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
			$DmiFinalSubmitTable = $DmiFlowWiseTablesLists->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
			
			$DmiAllocations = TableRegistry::getTableLocator()->get($DmiFinalSubmitTable['allocation']);
			$DmiHoAllocations = TableRegistry::getTableLocator()->get($DmiFinalSubmitTable['ho_level_allocation']);
			$DmiFinalSubmits = TableRegistry::getTableLocator()->get($DmiFinalSubmitTable['application_form']);
			$DmiGrantCertificatesPdfs = TableRegistry::getTableLocator()->get($DmiFinalSubmitTable['grant_pdf']);
			$DmiApplicantPaymentDetails = TableRegistry::getTableLocator()->get($DmiFinalSubmitTable['payment']);//added on 20-07-2017 by Pravin
		}

		$DmiCustomers = TableRegistry::getTableLocator()->get('DmiCustomers');
		$DmiFirms = TableRegistry::getTableLocator()->get('DmiFirms');
		$DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiUserRoles = TableRegistry::getTableLocator()->get('DmiUserRoles');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
		$DmiCertificateTypes = TableRegistry::getTableLocator()->get('DmiCertificateTypes');
		$DmiPaoDetails = TableRegistry::getTableLocator()->get('DmiPaoDetails');//added on 20-07-2017 by Pravin
		$DmiChemistRegistrations = TableRegistry::getTableLocator()->get('DmiChemistRegistrations');
		$dmi_adv_payment_details = TableRegistry::getTableLocator()->get('DmiAdvPaymentDetails');
		$dmi_ca_pp_lab_mapings = TableRegistry::getTableLocator()->get('DmiCaPpLabMapings');
		$dmi_replica_allotment_details = TableRegistry::getTableLocator()->get('DmiReplicaAllotmentDetails');
		$dmi_replica_allotment_pdfs = TableRegistry::getTableLocator()->get('DmiReplicaAllotmentPdfs');


		if (preg_match("/^[0-9]+\/[0-9]+$/",$customer_id,$matches)==1) {

			$fetch_applicant_data = $DmiCustomers->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
			$fetch_applicant_data = $fetch_applicant_data;

		} else {

			$fetch_firm_data = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
			$firm_data = $fetch_firm_data;

			$get_commodity_id = explode(',',$fetch_firm_data['sub_commodity']);
			$get_commodity_name = $MCommodity->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name','conditions'=>array('commodity_code IN'=>$get_commodity_id)))->toArray();

			$firm_certification_type_id = $firm_data['certification_type'];
			$firm_certification_type = $DmiCertificateTypes->find('all',array('conditions'=>array('id IS'=>$firm_certification_type_id)))->first();


			$split_customer_id = explode('/',$customer_id);
			$district_ro_code = $split_customer_id[2];

			//updated and added code to get Office table details from appl mapping Model
			$DmiApplWithRoMappings = TableRegistry::getTableLocator()->get('DmiApplWithRoMappings');
			$find_ro_email_id = $DmiApplWithRoMappings->getOfficeDetails($customer_id);
			$ro_email_id = $find_ro_email_id['ro_email_id'];

			$ro_user_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$ro_email_id)))->first();
			$ro_user_data = $ro_user_data;

			$find_dy_ama_user = $DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('dy_ama'=>'yes')))->first();
			$dy_ama_email_id = $find_dy_ama_user['user_email_id'];

			$dy_ama_user_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$dy_ama_email_id)))->first();
			$dy_ama_user_data = $dy_ama_user_data;

			$find_jt_ama_user = $DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('jt_ama'=>'yes')))->first();
			$jt_ama_email_id = $find_jt_ama_user['user_email_id'];

			$jt_ama_user_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$jt_ama_email_id)))->first();
			$jt_ama_user_data = $jt_ama_user_data;

			$find_ama_user = $DmiUserRoles->find('all',array('fields'=>'user_email_id','conditions'=>array('ama'=>'yes')))->first();
			$ama_email_id = $find_ama_user['user_email_id'];

			$ama_user_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$ama_email_id)))->first();
			$ama_user_data = $ama_user_data;

			if (!empty($DmiFinalSubmitTable)) {

				$final_submit_data = $DmiFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'status'=>'pending'),'order' => array('id' => 'desc')))->first();
				//Check empty condition (Done by pravin 13/2/2018)

				if (!empty($final_submit_data)) {
					$final_submit_data = $final_submit_data['created'];
				} else {
					$final_submit_data = null;
				}

				$find_allocated_mo = $DmiAllocations->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'level_3 IS'=>$ro_email_id),'order' => array('id' => 'desc')))->first();

				if (!empty($find_allocated_mo)) {

					$mo_email_id = $find_allocated_mo['level_1'];
					$mo_user_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$mo_email_id)))->first();

					if (!empty($mo_user_data)) {

						$mo_user_data = $mo_user_data;

					}
				}


				$find_allocated_io = $DmiAllocations->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'level_3 IS'=>$ro_email_id),'order' => array('id' => 'desc')))->first();

				if (!empty($find_allocated_io)) {

					$io_email_id = $find_allocated_io['level_2'];

					$io_user_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$io_email_id)))->first();

					if (!empty($io_user_data)) {

						$io_user_data = $io_user_data;

					}

				}

				//Get ho_mo_details (Done by pravin 23-07-2018)
				$find_allocated_ho_mo = $DmiHoAllocations->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'dy_ama IS'=>$dy_ama_email_id),'order' => array('id' => 'desc')))->first();

				if (!empty($find_allocated_ho_mo)) {

					$ho_mo_email_id = $find_allocated_ho_mo['ho_mo_smo'];

					$fetch_ho_mo_data = $DmiUsers->find('all',array('conditions'=>array('email IS'=>$ho_mo_email_id)))->first();

					if (!empty($fetch_ho_mo_data)) {

						$ho_mo_mob_no = $fetch_ho_mo_data['phone'];

						$ho_mo_name = $fetch_ho_mo_data['f_name']." ".$fetch_ho_mo_data['l_name'];

					}

				}


				$get_io_scheduled_date = $DmiAllocations->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order' => array('id' => 'desc')))->first();

					if (!empty($get_io_scheduled_date)) {//condition added on 11-10-2017 by Amol

						$io_scheduled_date = $get_io_scheduled_date['io_scheduled_date'];

					} else {

						$io_scheduled_date = '---';
					}


				//get renewal valid upto date
				//added on 05-02-2018 by Amol
				$each_application_grant_list = $DmiGrantCertificatesPdfs->find('list',array('conditions'=>array('customer_id IS'=>$customer_id)))->toArray();

					if (!empty($each_application_grant_list)) {


						$last_grant_details = $DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('id'=>max($each_application_grant_list))))->first();

						$last_grant_date = $last_grant_details['date'];

						//get certificate valid upto date

						$certificate_valid_upto = $CustomersController->Customfunctions->getCertificateValidUptoDate($customer_id,$last_grant_date);

					} else {

						$certificate_valid_upto = '';
					}

				//Get pao_name and pao_email (Done by pravin 20-07-2018)
				$find_pao_id = $DmiApplicantPaymentDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order' => array('id' => 'desc')))->first();

					if (!empty($find_pao_id)) {

						$pao_id =  $find_pao_id['pao_id'];

						$find_user_id =  $DmiPaoDetails->find('all',array('conditions'=>array('id IS'=>$pao_id)))->first();

						$user_id =  $find_user_id['pao_user_id'];

						$fetch_pao_data = $DmiUsers->find('all',array('conditions'=>array('id IS'=>$user_id)))->first();

						$pao_mobile_no = $fetch_pao_data['phone'];

						$pao_email_id = $fetch_pao_data['email'];

						$pao_name = $fetch_pao_data['f_name']." ".$fetch_pao_data['l_name'];

					}

			}
			
			
			//////////////[ For Replica ]//////////////
			
			#CHEMIST
			$get_chemist_name = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$_SESSION['chemistId'],'delete_status IS NULL'),'order'=>'id desc'))->first();
					
			if (!empty($get_chemist_name)) {
				
				$get_chemist_name = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$_SESSION['chemistId'],'delete_status IS NULL'),'order'=>'id desc'))->first();
				$chemist_name = $get_chemist_name['chemist_fname']." ".$get_chemist_name['chemist_lname']; 
				$chemist_id = $get_chemist_name['chemist_id'];
			
			}else{
				
				$get_chemist_id = $dmi_replica_allotment_pdfs->find('all')->select(['chemist_id'])->where(['customer_id IS' => $customer_id])->first();
				
				if(!empty($get_chemist_id)){
					$get_chemist_name = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$get_chemist_id['chemist_id'],'delete_status IS NULL'),'order'=>'id desc'))->first();
					$chemist_name = $get_chemist_name['chemist_fname']." ".$get_chemist_name['chemist_lname']; 
					$chemist_id = $get_chemist_name['chemist_id'];
				}
			}
			
			#ADVANCE PAYMENT
			if (!empty($application_type) && $application_type == 7) {
				
				$find_pao_id = $dmi_adv_payment_details->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order' => array('id' => 'desc')))->first();
			
				if (!empty($find_pao_id)) {

					$pao_id =  $find_pao_id['pao_id'];
					$find_user_id =  $DmiPaoDetails->find('all',array('conditions'=>array('id IS'=>$pao_id)))->first();
					$user_id =  $find_user_id['pao_user_id'];
					$fetch_pao_data = $DmiUsers->find('all',array('conditions'=>array('id IS'=>$user_id)))->first();
					$pao_mobile_no = $fetch_pao_data['phone'];
					$pao_email_id = $fetch_pao_data['email'];
					$pao_name = $fetch_pao_data['f_name']." ".$fetch_pao_data['l_name'];
				}
			}
			
			#COMMODITIES
			$getReplicaCommodity = $dmi_replica_allotment_details->find('all')->select(['commodity'])->where(['customer_id IS' => $customer_id])->group('commodity')->toArray();
			
			if(empty($getReplicaCommodity)){
				
				$getTableID = $DmiFirms->find('all')->select(['id'])->where(['customer_id'=>$customer_id])->first();
				
				$get_packer_customer_id = $dmi_ca_pp_lab_mapings->find()->select(['customer_id'])->where(['OR' => [['pp_id' => $getTableID['id']], ['lab_id' => $getTableID['id']]]])->first();
	
				$getReplicaCommodity = $dmi_replica_allotment_details->find('all')->select(['commodity'])->where(['customer_id IS' => $get_packer_customer_id['customer_id']])->group('commodity')->toArray();
			}
			
			if(!empty($getReplicaCommodity)){
				
				$i=0;
				$replica_commodities=array();
			
				foreach($getReplicaCommodity as $each){
					$getCommodity = $MCommodity->find('all',array('conditions'=>array('commodity_code IS'=>$each['commodity'])))->first();
					$replicacommodities[$i] = $getCommodity['commodity_name']; 
					$i++;
				}
				
				$replica_commodities = implode(", ", $replicacommodities);
			}else{
				$replica_commodities=null;
			}
			
				
			$get_allotments = $dmi_ca_pp_lab_mapings->find('all')->where(['customer_id IS' => $customer_id])->toArray();
			
			if(empty($get_allotments)){
				
				$getTableID = $DmiFirms->find('all')->select(['id'])->where(['customer_id'=>$customer_id])->first();
				$get_packer_customer_id = $dmi_ca_pp_lab_mapings->find()->select(['customer_id'])->where(['OR' => [['pp_id' => $getTableID['id']], ['lab_id' => $getTableID['id']]]])->first();
				$get_allotments = $dmi_ca_pp_lab_mapings->find('all')->where(['customer_id IS' => $get_packer_customer_id['customer_id']])->toArray();
			}
			
			if(!empty($get_allotments)){
				
				foreach($get_allotments as $each){
				
					#PRINTER
					if($each['map_type'] == 'pp'){
						$getPrinterName = $DmiFirms->find('all',array('conditions'=>array('id IS'=>$each['pp_id'])))->first();
						$printerName = $getPrinterName['firm_name'];
						
					}
					
					#LAB
					if($each['map_type'] == 'lab'){
						$getLabName = $DmiFirms->find('all',array('conditions'=>array('id IS'=>$each['lab_id'])))->first();
						$lab_name = $getLabName['firm_name'];
					}
					
					#PACKER
					$get_packer_name = $DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$each['customer_id'])))->first();
					$packer_name = $get_packer_name['firm_name'];
			    }
				
			}
		}

		switch ($replace_variable_value) {

			case "applicant_name":

				$applicant_name = $fetch_applicant_data['f_name']." ".$fetch_applicant_data['l_name'];
				return $applicant_name;
				break;

			case "applicant_mobile_no":

				$applicant_mobile_no = $fetch_applicant_data['mobile'];
				return $applicant_mobile_no;
				break;

			case "company_id":

				$company_id = $fetch_applicant_data['customer_id'];
				return $company_id;
				break;

			case "premises_id":

				$premises_id = $firm_data['customer_id'];
				return $premises_id;
				break;

			case "firm_name":

				$firm_name = $firm_data['firm_name'];
				return $firm_name;
				break;

			case "firm_certification_type":

				return $firm_certification_type;
				break;

			case "firm_email":

				$firm_email = base64_decode($firm_data['email']);
				return $firm_email;
				break;

			case "submission_date":

				$submission_date = $final_submit_data;
				return $submission_date;
				break;

			case "commodities":

				return $get_commodity_name;
				break;

			case "amount":

				$amount = $firm_data['total_charges'];
				return $amount;
				break;

			case "ro_name":

				$ro_name = $ro_user_data['f_name'];
				return $ro_name;
				break;

			case "ro_mobile_no":

				$ro_mobile_no = $ro_user_data['phone'];
				return $ro_mobile_no;
				break;

			case "ro_office":

				$ro_office = $find_ro_email_id['ro_office'];
				return $ro_office;
				break;

			case "ro_email_id":

				$ro_email_id = base64_decode($find_ro_email_id['ro_email_id']);
				return $ro_email_id;
				break;

			case "mo_name":

				$mo_name = $mo_user_data['f_name'];
				return $mo_name;
				break;

			case "mo_mobile_no":

				$mo_mobile_no = $mo_user_data['phone'];
				return $mo_mobile_no;
				break;

			case "mo_office":

				$mo_office = $find_ro_email_id['ro_office'];
				return $mo_office;
				break;

			case "mo_email_id":
				
				$mo_email_id = base64_decode($mo_email_id);
				return $mo_email_id;
				break;

			case "io_name":

				$io_name = $io_user_data['f_name'];
				return $io_name;
				break;

			case "io_mobile_no":

				$io_mobile_no = $io_user_data['phone'];
				return $io_mobile_no;
				break;

			case "io_office":

				$io_office = $find_ro_email_id['ro_office'];
				return $io_office;
				break;

			case "io_email_id":
				
				$io_email_id = base64_decode($io_email_id);
				return $io_email_id;
				break;

			case "dyama_name":

				$dyama_name = $dy_ama_user_data['f_name'];
				return $dyama_name;
				break;

			case "dyama_mobile_no":

				$dyama_mobile_no = $dy_ama_user_data['phone'];
				return $dyama_mobile_no;
				break;

			case "dyama_email_id":
					$dy_ama_email_id;
				return $dy_ama_email_id;
				break;

			case "jtama_name":

				$jtama_name = $jt_ama_user_data['f_name'];
				return $jtama_name;
				break;

			case "jtama_mobile_no":

				$jtama_mobile_no = $jt_ama_user_data['phone'];
				return $jtama_mobile_no;
				break;

			case "jtama_email_id":

				return $jt_ama_email_id;
				break;

			case "ama_name":

				$ama_name = $ama_user_data['f_name'];
				return $ama_name;
				break;

			case "ama_mobile_no":

				$ama_mobile_no = $ama_user_data['phone'];
				return $ama_mobile_no;
				break;

			case "ama_email_id":

				return $ama_email_id;
				break;

			case "io_scheduled_date":

				return $io_scheduled_date;
				break;

			case "certificate_valid_upto"://added on 05-02-2018 by Amol

				return $certificate_valid_upto;
				break;

			case "applicant_email":  // Add new paramerter list (done by pravin 07-03-2018)

				$applicant_email = $fetch_applicant_data['email'];
				return $applicant_email;
				break;

			case "pao_name":  // Add new paramerter list (done by pravin 20-07-2018)

				return $pao_name;
				break;

			case "pao_email_id":  // Add new paramerter list (done by pravin 20-07-2018)

				return $pao_email_id;
				break;

			case "pao_mobile_no":  // Add new paramerter list (done by pravin 20-07-2018)

				return $pao_mobile_no;
				break;

			case "ho_mo_email_id":  // Add new paramerter list (done by pravin 23-07-2018)

				return $ho_mo_email_id;
				break;

			case "ho_mo_mob_no":  // Add new paramerter list (done by pravin 23-07-2018)

				return $ho_mo_mob_no;
				break;

			case "ho_mo_name":  // Add new paramerter list (done by pravin 23-07-2018)

				return $ho_mo_name;
				break;

			//for replica
			case "chemist_name":

				return $chemist_name;
				break;

			case "chemist_id":

				return $chemist_id;
				break;

			case "replica_commodities":

				return $replica_commodities;
				break;

			case "application_type":

				return $application_type_text;
				break;
			
			case "packer_name":

				return $packer_name;
				break;
				
			case "printerName":
	
				return $printerName;
				break;
				
			case "lab_name":

				return $lab_name;
				break;
				
			default:

			$message = '%%';
			break;

		}

		//Destroy the Application Type Session
		$_SESSION['application_type']=null;


	}


	// This function replace the value between two character  (Done By pravin 9-08-2018)
	function replaceBetween($str, $needle_start, $needle_end, $replacement) {

		$pos = strpos($str, $needle_start);
		$start = $pos === false ? 0 : $pos + strlen($needle_start);

		$pos = strpos($str, $needle_end, $start);
		$end = $start === false ? strlen($str) : $pos;

		return substr_replace($str,$replacement,$start);
	}



	public function sendMessageLims($message_id,$userCode,$sample_code) {

		
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiSentSmsLogs = TableRegistry::getTableLocator()->get('DmiSentSmsLogs');
		$DmiSentEmailLogs = TableRegistry::getTableLocator()->get('DmiSentEmailLogs');
		$Workflow = TableRegistry::getTableLocator()->get('Workflow');
		

		$find_message_record = $this->find('all',array('conditions'=>array('id IS'=>$message_id, 'status'=>'active')))->first();

		if (!empty($find_message_record)) {

			$getUserId = $this->getUserDet($userCode,$find_message_record['destination']);

			$m=0;
			$e=0;
			$destination_mob_nos = array();
			$log_dest_mob_nos = array();
			$destination_email_ids = array();

			

			// Inward Officer
			if ($getUserId == 101) { 

				$inward_data = $DmiUsers->getUserDetailsById($userCode);

				if (!empty($inward_data)) {

					if (trim($inward_data['role']) == 'Inward Officer') {

						$email_id = base64_decode($inward_data['email']); //for email encoding
						$mobile_no = base64_decode($inward_data['phone']); //for mobile encoding

						//This is addded on 27-04-2021 for base64decoding by AKASH
						$destination_mob_nos[$m] = '91'.$mobile_no; 
						$log_dest_mob_nos[$m] = '91'.$mobile_no;
						$destination_email_ids[$e] = $email_id;
					
					}

				} else {
					
					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;
					$destination_email_ids[$e] = null;
				}

				$m=$m+1;
				$e=$e+1;
			}

			
			// RAL/CAL OIC
			if ($getUserId == 102) { 

				$ral_cal_oic_data = $DmiUsers->getUserDetailsById($userCode);
	
				if (!empty($ral_cal_oic_data)) {

					if (trim($ral_cal_oic_data['role']) == 'RAL/CAL OIC') {

						$email_id = base64_decode($ral_cal_oic_data['email']); //for email encoding
						$mobile_no = base64_decode($ral_cal_oic_data['phone']); //for mobile encoding

						//This is addded on 27-04-2021 for base64decoding by AKASH
						$destination_mob_nos[$m] = '91'.$mobile_no; 
						$log_dest_mob_nos[$m] = '91'.$mobile_no;
						$destination_email_ids[$e] = $email_id;
					}

				} else {
					
					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;		   
					$destination_email_ids[$e] = null;
				}

				$m=$m+1;
				$e=$e+1;
				
			}

			// RO Officer
			if ($getUserId == 109) { 

				$ro_data = $DmiUsers->getUserDetailsById($userCode);
				
				if (!empty($ro_data)) {
					
					$email_id = base64_decode($ro_data['email']); //for email encoding
					$mobile_no = base64_decode($ro_data['phone']); //for mobile encoding

					//This is addded on 27-04-2021 for base64decoding by AKASH
					$destination_mob_nos[$m] = '91'.$mobile_no; 
					$log_dest_mob_nos[$m] = '91'.$mobile_no;
					$destination_email_ids[$e] = $email_id;

				} else {
					
					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;
					$destination_email_ids[$e] = null;
				}

				$m=$m+1;
				$e=$e+1;
				
			}

			// RO/SO-OIC
			if ($getUserId == 110) { 

				$ro_so_oic_data = $DmiUsers->getUserDetailsById($userCode);

				if (!empty($ro_so_oic_data)) {
					
					if (trim($ro_so_oic_data['role']) == 'RO/SO-OIC') {

						$email_id = base64_decode($ro_so_oic_data['email']); //for email encoding
						$mobile_no = base64_decode($ro_so_oic_data['phone']); //for mobile encoding

						//This is addded on 27-04-2021 for base64decoding by AKASH
						$destination_mob_nos[$m] = '91'.$mobile_no; 
						$log_dest_mob_nos[$m] = '91'.$mobile_no;
						$destination_email_ids[$e] = $email_id;
					}

				} else {
					
					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;
					$destination_email_ids[$e] = null;
				}

				$m=$m+1;
				$e=$e+1;
				
			}

			// PAO/DDO
			if ($getUserId == 111) { 

				$ddo_data = $DmiUsers->getUserDetailsById($userCode);
		
				if (!empty($ddo_data)) {

					$email_id = base64_decode($ddo_data['email']); //for email encoding
					$mobile_no = base64_decode($ddo_data['phone']); //for mobile encoding

					//This is addded on 27-04-2021 for base64decoding by AKASH
					$destination_mob_nos[$m] = '91'.$mobile_no; 
					$log_dest_mob_nos[$m] = '91'.$mobile_no;
					$destination_email_ids[$e] = $email_id;

				} else {
					
					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;
					$destination_email_ids[$e] = null;
				}

				$m=$m+1;
				$e=$e+1;
				
			}

			// HO
			if ($getUserId == 112) { 

				$ho_data = $DmiUsers->getUserDetailsById($userCode);

				if (!empty($ho_data)) {
					
					if (trim($ho_data['role']) == 'Head Office') {

						$email_id = base64_decode($ho_data['email']); //for email encoding
						$mobile_no = base64_decode($ho_data['phone']); //for mobile encoding

						//This is addded on 27-04-2021 for base64decoding by AKASH
						$destination_mob_nos[$m] = '91'.$mobile_no; 
						$log_dest_mob_nos[$m] = '91'.$mobile_no;
						$destination_email_ids[$e] = $email_id;
					}

				} else {
					
					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;
					$destination_email_ids[$e] = null;
				}

				$m=$m+1;
				$e=$e+1;
				
			}
		

			// SO Officer
			if ($getUserId == 113) { 

				$so_data = $DmiUsers->getUserDetailsById($userCode);

				if (!empty($so_data)) {
					
					if (trim($so_data['role']) == 'Head Office') {

						$email_id = base64_decode($so_data['email']); //for email encoding
						$mobile_no = base64_decode($so_data['phone']); //for mobile encoding

						//This is addded on 27-04-2021 for base64decoding by AKASH
						$destination_mob_nos[$m] = '91'.$mobile_no; 
						$log_dest_mob_nos[$m] = '91'.$mobile_no;
						$destination_email_ids[$e] = $email_id;
					}

				} else {
					
					$destination_mob_nos[$m] = null;
					$log_dest_mob_nos[$m] = null;
					$destination_email_ids[$e] = null;
				}

				$m=$m+1;
				$e=$e+1;
				
			}
			
		
			$sms_message = $find_message_record['sms_message']; 
			$destination_mob_nos_values = implode(',',$destination_mob_nos);
			$log_dest_mob_nos_values = implode(',',$log_dest_mob_nos);
			$email_message = $find_message_record['email_message'];
			$destination_email_ids_values = implode(',',$destination_email_ids);
			$email_subject = $find_message_record['email_subject'];
			$template_id = $find_message_record['template_id'];//added on 12-05-2021 by Amol, new field
			
			//replacing dynamic values in the email message
			$sms_message = $this->replaceDynamicValuesFromMessage($sms_message,$userCode,$sample_code);

			//replacing dynamic values in the email message
			$email_message = $this->replaceDynamicValuesFromMessage($email_message,$userCode,$sample_code);
			
			$b = array (
				'SMS' => $sms_message, 
				'MOBILE' => $destination_mob_nos_values,
				'Message' => $email_message,
				'Email'=> $destination_email_ids_values
			);
				
			$fp = fopen('D:/lims.txt',"a");
			fwrite($fp, print_r($b, true));
			fclose($fp);

			//To send SMS on list of mobile nos.
			if (!empty($find_message_record['sms_message'])) {

				//code to send sms starts here	
				//echo "sendsms.php";
				// Initialize the sender variable
			/*	$uname="aqcms.sms";
				$pass="Y%26nF4b%237q";
				$send=urlencode("AGMARK");
				$dest=$destination_mob_nos_values;
				$msg=urlencode($sms_message);
			
				// Initialize the URL variable
				$URL="http://smsgw.sms.gov.in/failsafe/HttpLink";
				// Create and initialize a new cURL resource
				$ch = curl_init();
				// Set URL to URL variable
				curl_setopt($ch, CURLOPT_URL,$URL);
				// Set URL HTTPS post to 1
				curl_setopt($ch, CURLOPT_POST, true);
				// Set URL HTTPS post field values
				
				$entity_id = '1101424110000041576'; //updated on 18-11-2020
				
				// if message lenght is greater than 160 character then add one more parameter "concat=1" (Done by pravin 07-03-2018)
				if (strlen($msg) <= 160 ) {
					
					curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&dlt_entity_id=$entity_id&dlt_template_id=$template_id");
				
				} else {  
					
					curl_setopt($ch, CURLOPT_POSTFIELDS,"username=$uname&pin=$pass&signature=$send&mnumber=$dest&message=$msg&concat=1&dlt_entity_id=$entity_id&dlt_template_id=$template_id");
				}
				
				// Set URL return value to True to return the transfer as a string
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// The URL session is executed and passed to the browser
				$curl_output =curl_exec($ch);
				//echo $curl_output;
			*/	
				//code to send sms ends here	
			
				
				//query to save SMS sending logs in DB // added on 11-10-2017
				$DmiSentSmsLogsEntity = $DmiSentSmsLogs->newEntity(array(
					'message_id'=>$message_id,
					'destination_list'=>$log_dest_mob_nos_values,
					'mid'=>null,
					'sent_date'=>date('Y-m-d H:i:s'),
					'message'=>$sms_message,
					'created'=>date('Y-m-d H:i:s'),
					'template_id'=>$template_id //added on 12-05-2021 by Amol
				)); 

				$DmiSentSmsLogs->save($DmiSentSmsLogsEntity);
				
			}
			
		
			//email format to send on mail with content from master
			$email_format = 'Dear Sir/Madam' . "\r\n\r\n" .$email_message. "\r\n\r\n" .
							'Thanks & Regards,' . "\r\n" .
							'Directorate of Marketing & Inspection,' . "\r\n" .
							'Ministry of Agriculture and Farmers Welfare,' . "\r\n" .
							'Government of India.';
			

			
			//To send Email on list of Email ids.
			if (!empty($find_message_record['email_message'])) {
				
				$to = $destination_email_ids_values;
				$subject = $email_subject;
				$txt = $email_format;
				$headers = "From: dmiqc@nic.in";

			//	mail($to,$subject,$txt,$headers);
				
				
				
				//query to save Email sending logs in DB // added on 11-10-2017
				$DmiSentEmailLogsEntity = $DmiSentEmailLogs->newEntity(array(
				
					'message_id'=>$message_id,
					'destination_list'=>$destination_email_ids_values,
					'sent_date'=>date('Y-m-d H:i:s'),
					'message'=>$sms_message,
					'created'=>date('Y-m-d H:i:s'),
					'template_id'=>$template_id //added on 12-05-2021 by Amol
				
				));
				$DmiSentEmailLogs->save($DmiSentEmailLogsEntity);
				
			}
		
		}//end of 1st if condition 24-07-2018
		
	}
		


	//REPLACE DYNAMIC VALUES IN MESSAGE STRING
	public function replaceDynamicValuesFromMessageLims($message,$userCode,$sample_code) {
		
		//Getting Count Before Execution
		$total_occurrences = substr_count($message,"%%");

		while($total_occurrences > 0){

			$matches = explode('%%',$message);//getting string between %% & %%

			if (!empty($matches[1])) {

				switch ($matches[1]) {

					case "sample_code":
						$message = str_replace("%%sample_code%%",$this->getReplaceDynamicValues('sample_code',$userCode,$sample_code),$message);
					break;

					case "commodities":
						$message = str_replace("%%commodities%%",$this->getReplaceDynamicValues('commodities',$userCode,$sample_code),$message);
					break;

					case "src_user":
						$message = str_replace("%%src_user%%",$this->getReplaceDynamicValues('src_user',$userCode,$sample_code),$message);
					break;
			
					case "src_usr_role":
						$message = str_replace("%%src_usr_role%%",$this->getReplaceDynamicValues('src_usr_role',$userCode,$sample_code),$message);
					break;

					case "src_office":
						$message = str_replace("%%src_office%%",$this->getReplaceDynamicValues('src_office',$userCode,$sample_code),$message);
					break;

					case "dst_user":
						$message = str_replace("%%dst_user%%",$this->getReplaceDynamicValues('dst_user',$userCode,$sample_code),$message);
					break;

					case "dst_usr_role":
						$message = str_replace("%%dst_usr_role%%",$this->getReplaceDynamicValues('dst_usr_role',$userCode,$sample_code),$message);
					break;

					case "dst_office":
						$message = str_replace("%%dst_office%%",$this->getReplaceDynamicValues('dst_office',$userCode,$sample_code),$message);
					break;

					case "sample_flow":
						$message = str_replace("%%sample_flow%%",$this->getReplaceDynamicValues('sample_flow',$userCode,$sample_code),$message);
					break;

					case "category":
						$message = str_replace("%%category%%",$this->getReplaceDynamicValues('category',$userCode,$sample_code),$message);
					break;
		
					case "oic":
						$message = str_replace("%%oic%%",$this->getReplaceDynamicValues('oic',$userCode,$sample_code),$message);
					break;

					case "pao_name":
						$message = str_replace("%%pao_name%%",$this->getReplaceDynamicValues('pao_name',$userCode,$sample_code),$message);
					break;

					case "ro_name":
						$message = str_replace("%%ro_name%%",$this->getReplaceDynamicValues('ro_name',$userCode,$sample_code),$message);
					break;

					default:
						$message = $this->replaceBetween($message, '%%', '%%', '');	
						$default_value = 'yes';
					break;
				}
			}
				
			if (empty($default_value)) {	
			
				$total_occurrences = substr_count($message,"%%");//getting count after execution
				
			} else {
			
				$total_occurrences = $total_occurrences - 1;
			}
		}
		
		return $message;
	
	}
		
	
	//GET REPLACE DYNAMIC VALUES IN MESSAGE STRING
	public function getReplaceDynamicValuesLims($replace_variable_value,$userCode,$sample_code) {
		
		$Workflow = TableRegistry::getTableLocator()->get('Workflow');
		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');
		$DmiRoOffices = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$MCommodityCategory = TableRegistry::getTableLocator()->get('MCommodityCategory');
		$MCommodity = TableRegistry::getTableLocator()->get('MCommodity');
		$SampleInward = TableRegistry::getTableLocator()->get('SampleInward');
		$MSampleType = TableRegistry::getTableLocator()->get('MSampleType');
		//Get the Source User and their role from sample 

		$workflowData = $Workflow->find('all')->where(['stage_smpl_cd' => $sample_code])->order('id desc')->first();
		
		if(trim($workflowData['stage_smpl_flag']) == 'AS'){
			$workflowData = $Workflow->find('all')->where(['stage_smpl_cd' => $sample_code, 'stage_smpl_flag' => 'OF'])->first();
		}

		$sampleDetails = $SampleInward->getSampleDetailsByCode($workflowData['org_sample_code']);
		
		$sample_type = $MSampleType->getSampleType($sampleDetails['sample_type_code']);

		$commodities = $MCommodity->getCommodity($sampleDetails['commodity_code']);

		$get_category = $MCommodityCategory->getCategory($sampleDetails['category_code']);
		
		//Get User Name
		$src_user_name =  $DmiUsers->getUserDetailsById($workflowData['src_usr_cd']);
		$dst_user_name =  $DmiUsers->getUserDetailsById($workflowData['dst_usr_cd']);

		$month_name = $this->getMonthName($sampleDetails['expiry_month']);
		
		//User Role
		$src_usr_role =  $DmiUsers->getUserDetailsById($workflowData['src_usr_cd']);
		$dst_usr_role =  $DmiUsers->getUserDetailsById($workflowData['dst_usr_cd']);

		//Offices
		$src_office = $DmiRoOffices->getOfficeDetailsById($workflowData['src_loc_id']);
		$dst_office = $DmiRoOffices->getOfficeDetailsById($workflowData['dst_loc_id']);
		
		//OIC
		$getoicdetails = $DmiUsers->getUserDetailsById($userCode);

		//PAO
		$getPao = $DmiUsers->getUserDetailsById($userCode);

		//PAO
		$getRo = $DmiUsers->getUserDetailsById($userCode);

		switch ($replace_variable_value) {
				
			case "sample_code":
				return $sample_code;
			break;

			case "commodities":
				return $commodities;
			break;

			case "src_user":
				$src_user = $src_user_name['f_name']." ".$src_user_name['l_name']; 
				return $src_user; 
			break;

			case "src_usr_role":
				return $src_usr_role['role'];
			break;	
			
			case "src_office":
				return $src_office[0]; 
			break;

			case "dst_user":
				$dst_user = $dst_user_name['f_name']." ".$dst_user_name['l_name'];
				return $dst_user; 
			break;
			
			case "dst_usr_role":
				return $dst_usr_role['role'];
			break;

			case "dst_office":
				return $dst_office[0]; 
			break;

			case "sample_flow":
				$sample_flow = $sample_type;
				return $sample_flow; 
			break;

			case "category":
				$category = $get_category; 
				return $category; 
			break;

			case "oic":
				$oic = $getoicdetails['f_name']." ".$getoicdetails['l_name']; 
				return $oic; 
			break;

			case "pao_name":
				$pao = $getPao['f_name']." ".$getPao['l_name']; 
				return $pao; 
			break;

			case "ro_name":
				$ro_so = $getRo['f_name']." ".$getRo['l_name']; 
				return $ro_so; 
			break;

			default:	
				$message = '%%';
			break;
		}
	}

	// This function replace the value between two character  (Done By pravin 9-08-2018)
	function getUserDet($userCode,$destination_values) {

		$DmiUsers = TableRegistry::getTableLocator()->get('DmiUsers');

		$details = $DmiUsers->getUserDetailsById($userCode);
		$getID = $this->smsUserId(trim($details['role']));
		
		$destination_array = explode(',',$destination_values);
		
		if (in_array(111,$destination_array)) {
			
			$getID = 111;
			return $getID;
		}

		if (in_array(109,$destination_array)) {
	
			$getID = 109;
			return $getID;
		}

		if (in_array(113,$destination_array)) {
			$getID = 113;
			return $getID;
		}


		$lookUp = in_array($getID,$destination_array);
		if ($lookUp==1) {
			return $getID;
		}
		
		
	}


	public function smsUserId($role){
		
		//Current selected values from edit page for LMIS
		
		if ($role == 'Inward Officer') { $dest_id = 101; }

		if ($role == 'RAL/CAL OIC') { $dest_id = 102; }

		if ($role == 'Jr Chemist') { $dest_id = 103; }

		if ($role == 'Sr Chemist') { $dest_id = 104; }

		if ($role == 'Lab Incharge') { $dest_id = 105; }

		if ($role == 'DOL') { $dest_id = 106; }

		if ($role == 'inward_clerk') { $dest_id = 107; }

		if ($role == 'outward_clerk') { $dest_id = 108; }

		if ($role == 'RO Officer') { $dest_id = 109; }

		if ($role == 'RO/SO OIC') { $dest_id = 110; }

		if ($role == 'Head Office') { $dest_id = 112; }

		if ($role == 'SO Officer') { $dest_id = 113; }

		return $dest_id;
	}
	
}
?>
