<?php

	namespace app\Model\Table;
	use Cake\ORM\Table;
	use App\Model\Model;
	use App\Controller\AppController;
	use App\Controller\CustomersController;
	use Cake\ORM\TableRegistry;
	use Cake\Utility\Hash;

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


					$fetch_ama_data = $DmiUsers->find('first',array('conditions'=>array('email IS'=>$ama_email_id)))->first();
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

				print_r($sms_message); 
				print_r('</br>'); 
				print_r($destination_mob_nos_values); 
				print_r('</br>'); 
				print_r($destination_email_ids_values); 
				print_r($email_message); 
				print_r('</br>'); 
			

				//To send SMS on list of mobile nos.
				if (!empty($find_message_record['sms_message'])) {

				//code to send sms starts here
					//echo "sendsms.php";
					// Initialize the sender variable
					/*$sender=urlencode("AGMARK");
					//$uname=urlencode("aqcms.sms");
					$uname="aqcms.sms";
					//$pass=urlencode("Y&nF4b#7q");
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

					/*$Email = new CakeEmail();
					$Email->from(array('amy.cho27@gmail.com' => 'From DMI'))
						->to($destination_email_ids)
						->subject('DMI Email Templates')
						->send($email_message);
					*/

					$to = $destination_email_ids_values;
					$subject = $email_subject;
					$txt = $email_format;
					$headers = "From: dmiqc@nic.in";

					//mail($to,$subject,$txt,$headers);



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

							$message = str_replace("%%submission_date%%",$this->getReplaceDynamicValues('submission_date',$customer_id),$message);
							break;

						case "firm_name":

							$message = str_replace("%%firm_name%%",$this->getReplaceDynamicValues('firm_name',$customer_id),$message);
							break;

						case "amount":

							$message = str_replace("%%amount%%",$this->getReplaceDynamicValues('amount',$customer_id),$message);
							break;

						case "commodities":

							$message = str_replace("%%commodities%%",$this->getReplaceDynamicValues('commodities',$customer_id),$message);
							break;

						case "applicant_name":

							$message = str_replace("%%applicant_name%%",$this->getReplaceDynamicValues('applicant_name',$customer_id),$message);
							break;

						case "applicant_mobile_no":

							$message = str_replace("%%applicant_mobile_no%%",$this->getReplaceDynamicValues('applicant_mobile_no',$customer_id),$message);
							break;

						case "company_id":

							$message = str_replace("%%company_id%%",$this->getReplaceDynamicValues('company_id',$customer_id),$message);
							break;

						case "certificate_valid_upto"://added on 05-02-2018 by Amol

							$message = str_replace("%%certificate_valid_upto%%",$this->getReplaceDynamicValues('certificate_valid_upto',$customer_id),$message);
							break;

						// Add new paramerter list (done by pravin 07-03-2018)

						case "premises_id":

							$message = str_replace("%%premises_id%%",$customer_id,$message);
							break;

						case "firm_email":

							$message = str_replace("%%firm_email%%",$this->getReplaceDynamicValues('firm_email',$customer_id),$message);
							break;

						case "firm_certification_type":

							$message = str_replace("%%firm_certification_type%%",$this->getReplaceDynamicValues('firm_certification_type',$customer_id),$message);
							break;

						case "ro_name":

							$message = str_replace("%%ro_name%%",$this->getReplaceDynamicValues('ro_name',$customer_id),$message);
							break;

						case "ro_mobile_no":

							$message = str_replace("%%ro_mobile_no%%",$this->getReplaceDynamicValues('ro_mobile_no',$customer_id),$message);
							break;

						case "ro_office":

							$message = str_replace("%%ro_office%%",$this->getReplaceDynamicValues('ro_office',$customer_id),$message);
							break;

						case "ro_email_id":

							$message = str_replace("%%ro_email_id%%",$this->getReplaceDynamicValues('ro_email_id',$customer_id),$message);
							break;

						case "mo_name":

							$message = str_replace("%%mo_name%%",$this->getReplaceDynamicValues('mo_name',$customer_id),$message);
							break;

						case "mo_mobile_no":

							$message = str_replace("%%mo_mobile_no%%",$this->getReplaceDynamicValues('mo_mobile_no',$customer_id),$message);
							break;

						case "mo_office":

							$message = str_replace("%%mo_office%%",$this->getReplaceDynamicValues('mo_office',$customer_id),$message);
							break;

						case "mo_email_id":

							$message = str_replace("%%mo_email_id%%",$this->getReplaceDynamicValues('mo_email_id',$customer_id),$message);
							break;

						case "io_name":

							$message = str_replace("%%io_name%%",$this->getReplaceDynamicValues('io_name',$customer_id),$message);
							break;

						case "io_mobile_no":

							$message = str_replace("%%io_mobile_no%%",$this->getReplaceDynamicValues('io_mobile_no',$customer_id),$message);
							break;

						case "io_office":

							$message = str_replace("%%io_office%%",$this->getReplaceDynamicValues('io_office',$customer_id),$message);
							break;

						case "io_email_id":

							$message = str_replace("%%io_email_id%%",$this->getReplaceDynamicValues('io_email_id',$customer_id),$message);
							break;

						case "dyama_name":

							$message = str_replace("%%dyama_name%%",$this->getReplaceDynamicValues('dyama_name',$customer_id),$message);
							break;

						case "dyama_mobile_no":

							$message = str_replace("%%dyama_mobile_no%%",$this->getReplaceDynamicValues('dyama_mobile_no',$customer_id),$message);
							break;

						case "dyama_email_id":

							$message = str_replace("%%dyama_email_id%%",$this->getReplaceDynamicValues('dyama_email_id',$customer_id),$message);
							break;

						case "jtama_name":

							$message = str_replace("%%jtama_name%%",$this->getReplaceDynamicValues('jtama_name',$customer_id),$message);
							break;

						case "jtama_mobile_no":

							$message = str_replace("%%jtama_mobile_no%%",$this->getReplaceDynamicValues('jtama_mobile_no',$customer_id),$message);
							break;

						case "jtama_email_id":

							$message = str_replace("%%jtama_email_id%%",$this->getReplaceDynamicValues('jtama_email_id',$customer_id),$message);
							break;

						case "ama_name":

							$message = str_replace("%%ama_name%%",$this->getReplaceDynamicValues('ama_name',$customer_id),$message);
							break;

						case "ama_mobile_no":

							$message = str_replace("%%ama_mobile_no%%",$this->getReplaceDynamicValues('ama_mobile_no',$customer_id),$message);
							break;

						case "ama_email_id":

							$message = str_replace("%%ama_email_id%%",$this->getReplaceDynamicValues('ama_email_id',$customer_id),$message);
							break;

						case "io_scheduled_date":

							$message = str_replace("%%io_scheduled_date%%",$this->getReplaceDynamicValues('io_scheduled_date',$customer_id),$message);
							break;

						case "applicant_email":

							$message = str_replace("%%applicant_email%%",$this->getReplaceDynamicValues('applicant_email',$customer_id),$message);
							break;

						//	start add new parameter Done by pravin 20-07-2018
						case "pao_name":

							$message = str_replace("%%pao_name%%",$this->getReplaceDynamicValues('pao_name',$customer_id),$message);
							break;

						case "pao_email_id":

							$message = str_replace("%%pao_email_id%%",$this->getReplaceDynamicValues('pao_email_id',$customer_id),$message);
							break;

						case "pao_mobile_no":

							$message = str_replace("%%pao_mobile_no%%",$this->getReplaceDynamicValues('pao_mobile_no',$customer_id),$message);
							break;
						// end

						// Start add new parameter Done by pravin 23-07-2018
						case "ho_mo_name":

							$message = str_replace("%%ho_mo_name%%",$this->getReplaceDynamicValues('ho_mo_name',$customer_id),$message);
							break;

						case "ho_mo_mobile_no":

							$message = str_replace("%%ho_mo_mobile_no%%",$this->getReplaceDynamicValues('ho_mo_mobile_no',$customer_id),$message);
							break;

						case "ho_mo_email_id":

							$message = str_replace("%%ho_mo_email_id%%",$this->getReplaceDynamicValues('ho_mo_email_id',$customer_id),$message);
							break;
						// end

						case "home_link":

							$message = str_replace("%%home_link%%",$_SERVER['HTTP_HOST'],$message);
							break;

						//For Replica And Chemist Module
						case "chemist_name":

							$message = str_replace("%%chemist_name%%",$this->getReplaceDynamicValues('chemist_name',$customer_id),$message);
							break;

						case "chemist_id":

							$message = str_replace("%%chemist_id%%",$this->getReplaceDynamicValues('chemist_id',$customer_id),$message);
							break;

						case "replica_commodities":

							$message = str_replace("%%replica_commodities%%",$this->getReplaceDynamicValues('replica_commodities',$customer_id),$message);
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

			//for replica
			$chemist_name = null;
			$chemist_id = null;
			$replica_commodities = null;

			if (!empty($application_type)) {
				
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
			
			$CustomersController = new CustomersController;

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

				//get chemist name
				$get_chemist_name = $DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$_SESSION['chemistId'],'delete_status IS NULL'),'order'=>'id desc'))->first();
						
				if (!empty($get_chemist_name)) {

					$chemist_name = $get_chemist_name['chemist_fname']." ".$get_chemist_name['chemist_lname']; 
					$chemist_id = $get_chemist_name['chemist_id'];
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

				default:

				$message = '%%';
				break;

			}
		}


		// This function replace the value between two character  (Done By pravin 9-08-2018)
		function replaceBetween($str, $needle_start, $needle_end, $replacement) {

			$pos = strpos($str, $needle_start);
			$start = $pos === false ? 0 : $pos + strlen($needle_start);

			$pos = strpos($str, $needle_end, $start);
			$end = $start === false ? strlen($str) : $pos;

			return substr_replace($str,$replacement,$start);
		}



	}
