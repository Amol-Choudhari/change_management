<?php

	App::uses('Dmi_customer','Model');
	App::uses('Dmi_firm','Model');
	App::uses('Dmi_ro_office','Model');
	App::uses('Dmi_allocation','Model');
	App::uses('Dmi_user','Model');
	App::uses('Dmi_user_role','Model');
	App::uses('Dmi_ho_allocation','Model');
	App::uses('Dmi_final_submit','Model');
	App::uses('M_commodity','Model');
	App::uses('Dmi_certificate_type','Model');
	App::uses('Dmi_sent_sms_log','Model');//added on 11-10-2017 by Amol
	App::uses('Dmi_sent_email_log','Model');//added on 11-10-2017 by Amol
	App::uses('Dmi_applicant_payment_detail','Model');//added on 20-07-2017 by Pravin
	App::uses('Dmi_pao_detail','Model');//added on 20-07-2017 by Pravin
	
	//for renewal process //added on 05-12-2019 by Amol
	App::uses('Dmi_renewal_allocation','Model');
	App::uses('Dmi_renewal_ho_allocation','Model');
	App::uses('Dmi_renewal_final_submit','Model');
	App::uses('Dmi_renewal_applicant_payment_detail','Model');
	
	//for replica module added this table on 07/08/2021
	App::uses('Dmi_chemist_allocation','Model');
	App::uses('Dmi_adv_payment_detail','Model');
	App::uses('Dmi_chemist_registrations','Model');
	App::uses('Dmi_replica_allotment_detail','Model');
	
	App::uses('Dmi_grant_certificates_pdf','Model');
	App::uses('App','Controller');//added on 05-02-2018 by Amol to load AppController
 
	class Dmi_sms_email_template extends AppModel{

		var $name = "Dmi_sms_email_template";
						
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
		
		
		
		
		public function send_message($message_id, $customer_id, $flow_table=null)
		{
			//print_r($flow_table); exit;
			$Dmi_customer = new Dmi_customer();
			$Dmi_firm = new Dmi_firm();
			$Dmi_ro_office = new Dmi_ro_office();
			$Dmi_allocation = new Dmi_allocation();
			$Dmi_user = new Dmi_user();
			$Dmi_user_role = new Dmi_user_role();
			$Dmi_ho_allocation = new Dmi_ho_allocation();
			$Dmi_sent_sms_log = new Dmi_sent_sms_log();//added on 11-10-2017 by Amol
			$Dmi_sent_email_log = new Dmi_sent_email_log();//added on 11-10-2017 by Amol
			$Dmi_applicant_payment_detail = new Dmi_applicant_payment_detail();//added on 20-07-2017 by Pravin
			$Dmi_pao_detail = new Dmi_pao_detail();//added on 20-07-2017 by Pravin
			
			//for replica module added below code to manage customer id and chemist id on 07/08/2021
			$Dmi_chemist_registrations = new Dmi_chemist_registrations();
			
			$_SESSION['chemistId'] = '';

			if(preg_match("/^[CHM]+\/[0-9]+\/[0-9]+$/", $customer_id,$matches)==1){
				
				$get_packer_id = $Dmi_chemist_registrations->find('first',array('fields'=>'created_by','conditions'=>array('chemist_id'=>$customer_id)));
				$packer_id = $get_packer_id['Dmi_chemist_registrations']['created_by'];
				
				$_SESSION['chemistId'] = $customer_id;
				
				$customer_id = $packer_id;
				
				
			}

			
			$_SESSION['flow_table'] = '';
			
			//check if applied for renewal, then load renewal models, on 05-12-2019 by Amol
			$Dmi_renewal_final_submit = new Dmi_renewal_final_submit();
			
			if($flow_table == 'Dmi_chemist_final_submit'){
				
				$_SESSION['flow_table'] = $flow_table;
				$Dmi_applicant_payment_detail = new Dmi_adv_payment_detail();
				$Dmi_allocation = new Dmi_chemist_allocation();
				$allocation_model = 'Dmi_chemist_allocation';
				$payment_detail_model = 'Dmi_adv_payment_detail';
				
			}else{
				
		
				$check_for_renewal = $Dmi_renewal_final_submit->find('first',array('conditions'=>array('customer_id'=>$customer_id)));
				if(!empty($check_for_renewal)){
					//load renewal related models
					$Dmi_allocation = new Dmi_renewal_allocation();
					$Dmi_ho_allocation = new Dmi_renewal_ho_allocation();
					$Dmi_applicant_payment_detail = new Dmi_renewal_applicant_payment_detail();
					
					//set renewal model variable
					$allocation_model = 'Dmi_renewal_allocation';
					$ho_allocation_model = 'Dmi_renewal_ho_allocation';
					$payment_detail_model = 'Dmi_renewal_applicant_payment_detail';
					
				}else{//as a new application
					$allocation_model = 'Dmi_allocation';
					$ho_allocation_model = 'Dmi_ho_allocation';
					$payment_detail_model = 'Dmi_applicant_payment_detail';
				}
			}
			
			$find_message_record = $this->find('first',array('conditions'=>array('id'=>$message_id, 'status'=>'active')));//'status'condition inserted on 24-07-2018

			//added this if condition on 24-07-2018 by Amol
			if(!empty($find_message_record)){
				
				//Make an user action entry in user action log table, Done by pravin bhakare, 11-02-2021
				App::uses('App','Controller');
				$app = new AppController();
				$userActions = $find_message_record['Dmi_sms_email_template']['action'];
				if($message_id !=1 ){
					$app->userActionPerformLog($userActions,'Success');
				}
				
				$destination_values = $find_message_record['Dmi_sms_email_template']['destination'];			
				$destination_array = explode(',',$destination_values);
				
				//checking applicant id pattern ex.102/2017 if primary Applicant, then dont split
				//added on 23-08-2017 by Amol
				if(!preg_match("/^[0-9]+\/[0-9]+$/",$customer_id,$matches)==1)
				{
					$split_customer_id = split('/',$customer_id);
					$district_ro_code = $split_customer_id[2];
								
					$find_ro_email_id = $Dmi_ro_office->find('first',array('fields'=>'ro_email_id','conditions'=>array('short_code'=>$district_ro_code)));			
					$ro_email_id	=	$find_ro_email_id['Dmi_ro_office']['ro_email_id'];
				}
				
				$m=0;
				$e=0;
				$destination_mob_nos = array();
				$log_dest_mob_nos = array();
				$destination_email_ids = array();
				
				
				
				//for Applicant
				if(in_array(0,$destination_array))
				{
					//checking applicant id pattern ex.102/2017 if primary Applicant
					//added on 23-08-2017 by Amol
					if(preg_match("/^[0-9]+\/[0-9]+$/",$customer_id,$matches)==1)
					{						
						$fetch_applicant_data = $Dmi_customer->find('first',array('conditions'=>array('customer_id'=>$customer_id)));
					
						$applicant_mob_no = $fetch_applicant_data['Dmi_customer']['mobile'];
						$applicant_email_id = $fetch_applicant_data['Dmi_customer']['email'];
					}
					else{
						
						$fetch_applicant_data = $Dmi_firm->find('first',array('conditions'=>array('customer_id'=>$customer_id)));
					
						$applicant_mob_no = $fetch_applicant_data['Dmi_firm']['mobile_no'];
						$applicant_email_id = $fetch_applicant_data['Dmi_firm']['email'];
						
					}
							
					
					
					$destination_mob_nos[$m] = '91'.base64_decode($applicant_mob_no);
					$log_dest_mob_nos[$m] = '91'.$applicant_mob_no;
					$destination_email_ids[$e] = $applicant_email_id;
					
					$m=$m+1;
					$e=$e+1;
				}
				
				
				
				
				//for MO/SMO
				if(in_array(1,$destination_array))
				{								
					$find_allocated_mo = $Dmi_allocation->find('first',array('conditions'=>array('customer_id'=>$customer_id,'level_3'=>$ro_email_id),'order'=>'id desc'));
																	
					
					if(!empty($find_allocated_mo)){//added condition on 05-12-2019 by Amol
						$mo_email_id = $find_allocated_mo[$allocation_model]['level_1'];//added dynamic variable on 05-12-2019 by Amol
						
						//check if MO is allocated or not //added on 04-10-2017
						if(!empty($mo_email_id)){
							
							$fetch_mo_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$mo_email_id)));
							$mo_mob_no = $fetch_mo_data['Dmi_user']['phone'];
														
							$destination_mob_nos[$m] = '91'.base64_decode($mo_mob_no);
							$log_dest_mob_nos[$m] = '91'.$mo_mob_no;
							$destination_email_ids[$e] = $mo_email_id;
						
						}else{
							
							$destination_mob_nos[$m] = null;
							$log_dest_mob_nos[$m] = null;
							$destination_email_ids[$e] = null;
						}
					}else{
	  
						$destination_mob_nos[$m] = null;
						$log_dest_mob_nos[$m] = null;
						$destination_email_ids[$e] = null;
					}
	 

					$m=$m+1;
					$e=$e+1;
					
				}
				
				
				
				
				//for IO
				if(in_array(2,$destination_array))
				{								
					$find_allocated_io = $Dmi_allocation->find('first',array('conditions'=>array('customer_id'=>$customer_id,'level_3'=>$ro_email_id),'order'=>'id desc'));
																	
					
					if(!empty($find_allocated_io)){//added condition on 05-12-2019 by Amol
						$io_email_id = $find_allocated_io[$allocation_model]['level_2'];//added dynamic variable on 05-12-2019 by Amol
						
						//check if IO is allocated or not //added on 04-10-2017
						if(!empty($io_email_id)){
							
							$fetch_io_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$io_email_id)));
							$io_mob_no = $fetch_io_data['Dmi_user']['phone'];
														
							$destination_mob_nos[$m] = '91'.base64_decode($io_mob_no);
							$log_dest_mob_nos[$m] = '91'.$io_mob_no;
							$destination_email_ids[$e] = $io_email_id;
						
						}else{
							
							$destination_mob_nos[$m] = null;
							$log_dest_mob_nos[$m] = null;
							$destination_email_ids[$e] = null;
						}
					}else{
	  
						$destination_mob_nos[$m] = null;
						$log_dest_mob_nos[$m] = null;
						$destination_email_ids[$e] = null;
						
					}

					$m=$m+1;
					$e=$e+1;
					
				}
				
				
				
				//for RO/SO
				if(in_array(3,$destination_array))
				{								
					
					$fetch_ro_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$ro_email_id)));
					$ro_mob_no = $fetch_ro_data['Dmi_user']['phone'];
										
					$destination_mob_nos[$m] = '91'.base64_decode($ro_mob_no);
					$log_dest_mob_nos[$m] = '91'.$ro_mob_no;
					$destination_email_ids[$e] = $ro_email_id;
					
					$m=$m+1;
					$e=$e+1;
					
				}
				
				
				
				//for Dy AMA
				if(in_array(4,$destination_array))
				{								
					
					$find_dy_ama_user = $Dmi_user_role->find('first',array('fields'=>'user_email_id','conditions'=>array('dy_ama'=>'yes')));
					$dy_ama_email_id = $find_dy_ama_user['Dmi_user_role']['user_email_id'];
			
					
					$fetch_dy_ama_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$dy_ama_email_id)));
					$dy_ama_mob_no = $fetch_dy_ama_data['Dmi_user']['phone'];
										
					$destination_mob_nos[$m] = '91'.base64_decode($dy_ama_mob_no);
					$log_dest_mob_nos[$m] = '91'.$dy_ama_mob_no;
					$destination_email_ids[$e] = $dy_ama_email_id;
					
					$m=$m+1;
					$e=$e+1;
					
				}
				
				
				
				//for Jt AMA
				if(in_array(5,$destination_array))
				{								
																											
					$find_jt_ama_user = $Dmi_user_role->find('first',array('fields'=>'user_email_id','conditions'=>array('jt_ama'=>'yes')));
					$jt_ama_email_id = $find_jt_ama_user['Dmi_user_role']['user_email_id'];												
					
					
					$fetch_jt_ama_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$jt_ama_email_id)));
					$jt_ama_mob_no = $fetch_jt_ama_data['Dmi_user']['phone'];
					
					$destination_mob_nos[$m] = '91'.base64_decode($jt_ama_mob_no);
					$log_dest_mob_nos[$m] = '91'.$jt_ama_mob_no;
					$destination_email_ids[$e] = $jt_ama_email_id;
					
					$m=$m+1;
					$e=$e+1;
					
				}
				
				
				
				
				//for HO MO/SMO
				if(in_array(6,$destination_array))
				{																						
					$find_dy_ama_user = $Dmi_user_role->find('first',array('fields'=>'user_email_id','conditions'=>array('dy_ama'=>'yes')));
					$dy_ama_email_id = $find_dy_ama_user['Dmi_user_role']['user_email_id'];

					
					$find_allocated_ho_mo = $Dmi_ho_allocation->find('first',array('conditions'=>array('customer_id'=>$customer_id, 'dy_ama'=>$dy_ama_email_id),'order'=>'id desc'));
					$ho_mo_email_id = $find_allocated_ho_mo[$ho_allocation_model]['ho_mo_smo'];//added dynamic variable on 05-12-2019 by Amol
					
					
					$fetch_ho_mo_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$ho_mo_email_id)));
					$ho_mo_mob_no = $fetch_ho_mo_data['Dmi_user']['phone'];
										
					$destination_mob_nos[$m] = '91'.base64_decode($ho_mo_mob_no);
					$log_dest_mob_nos[$m] = '91'.$ho_mo_mob_no;					
					$destination_email_ids[$e] = $ho_mo_email_id;
					
					$m=$m+1;
					$e=$e+1;
					
				}
				
				
				
				//for AMA
				if(in_array(7,$destination_array))
				{																						
							
					$find_ama_user = $Dmi_user_role->find('first',array('fields'=>'user_email_id','conditions'=>array('ama'=>'yes')));
					$ama_email_id = $find_ama_user['Dmi_user_role']['user_email_id'];
					
					
					$fetch_ama_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$ama_email_id)));
					$ama_mob_no = $fetch_ama_data['Dmi_user']['phone'];
										
					$destination_mob_nos[$m] = '91'.base64_decode($ama_mob_no);
					$log_dest_mob_nos[$m] = '91'.$ama_mob_no;
					$destination_email_ids[$e] = $ama_email_id;
					
					$m=$m+1;
					$e=$e+1;
					
				}
				
				
				
				//for Accounts  (Done by pravin 20-07-2018)
				if(in_array(8,$destination_array))
				{
																						
					$find_pao_id = $Dmi_applicant_payment_detail->find('first',array('conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'));
					
					if(!empty($find_pao_id)) {
						
						$pao_id =  $find_pao_id[$payment_detail_model]['pao_id'];//added dynamic variable on 05-12-2019 by Amol
						$find_user_id =  $Dmi_pao_detail->find('first',array('conditions'=>array('id'=>$pao_id)));
						$user_id =  $find_user_id['Dmi_pao_detail']['pao_user_id'];
						
						
						$fetch_pao_data = $Dmi_user->find('first',array('conditions'=>array('id'=>$user_id)));
						$pao_mob_no = $fetch_pao_data['Dmi_user']['phone'];
						$pao_email = $fetch_pao_data['Dmi_user']['email'];
												
						$destination_mob_nos[$m] = '91'.base64_decode($pao_mob_no);
						$log_dest_mob_nos[$m] = '91'.$pao_mob_no;
						$destination_email_ids[$e] = $pao_email;
						
					}else{

						$destination_mob_nos[$m] = null;
						$log_dest_mob_nos[$m] = null;
						$destination_email_ids[$e] = null;
						
					}
					
					$m=$m+1;
					$e=$e+1;
					
				}
				
				
				//for Chemist User
				if(in_array(10,$destination_array))
				{																						
					$find_chemist_user= $Dmi_chemist_registrations->find('first',array('conditions'=>array('chemist_id'=>$_SESSION['chemistId']),'order'=>'id desc'));
			
					if(!empty($find_chemist_user)) {
						
						$chemist_id =  $find_chemist_user['Dmi_chemist_registrations']['chemist_id'];
						$chemist_mob_no = $find_chemist_user['Dmi_chemist_registrations']['mobile'];
						$chemist_email = $find_chemist_user['Dmi_chemist_registrations']['email'];
												
						$destination_mob_nos[$m] = '91'.base64_decode($chemist_mob_no);
						$log_dest_mob_nos[$m] = '91'.$chemist_mob_no;
						$destination_email_ids[$e] = $chemist_email;
							
					}else{
						$destination_mob_nos[$m] = null;
						$log_dest_mob_nos[$m] = null;
						$destination_email_ids[$e] = null;
						
					}
					
					$m=$m+1;
					$e=$e+1;
					
				}


				
				$sms_message = $find_message_record['Dmi_sms_email_template']['sms_message'];
				$destination_mob_nos_values = implode(',',$destination_mob_nos);
				$log_dest_mob_nos_values = implode(',',$log_dest_mob_nos);
				
				$email_message = $find_message_record['Dmi_sms_email_template']['email_message'];
				$destination_email_ids_values = implode(',',$destination_email_ids);
				
				$email_subject = $find_message_record['Dmi_sms_email_template']['email_subject'];

				$template_id = $find_message_record['Dmi_sms_email_template']['template_id'];//added on 02-03-2021 by Amol, new field
				
				//replacing dynamic values in the email message
				$sms_message = $this->replace_dynamic_values_from_message($customer_id,$sms_message);
				
				
				//replacing dynamic values in the email message
				$email_message = $this->replace_dynamic_values_from_message($customer_id,$email_message);


				//To send SMS on list of mobile nos.
				if(!empty($find_message_record['Dmi_sms_email_template']['sms_message']))
				{
					$URL = "";	
				//code to send sms starts here	
					// Initialize the sender variable
					$sender=urlencode("AGMARK");
					$uname="aqcms.sms";
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
					
				//code to send sms ends here	
				
				
					//query to save SMS sending logs in DB // added on 11-10-2017
					$Dmi_sent_sms_log->save(array(
					
						'message_id'=>$message_id,
						'destination_list'=>$log_dest_mob_nos_values,
						'MID'=>null,
						'sent_date'=>date('Y-m-d H:i:s'),
						'message'=>$sms_message,
						'created'=>date('Y-m-d H:i:s'),			
						'template_id'=>$template_id //added on 02-03-2021 by Amol									   
					
					));
					
				}
				
			
				//email format to send on mail with content from master
				$email_format = 'Dear Sir/Madam' . "\r\n\r\n" .$email_message. "\r\n\r\n" .
								'Thanks & Regards,' . "\r\n" .
								'Directorate of Marketing & Inspection,' . "\r\n" .
								'Ministry of Agriculture and Farmers Welfare,' . "\r\n" .
								'Government of India.';
				

				
				//To send Email on list of Email ids.
				if(!empty($find_message_record['Dmi_sms_email_template']['email_message']))
				{
					
					$to = $destination_email_ids_values;
					$subject = $email_subject;
					$txt = $email_format;
					$headers = "From: dmiqc@nic.in";

					mail($to,$subject,$txt,$headers, '-f dmiqc@nic.in'); //added new parameter '-f dmiqc@nic.in' on 08-12-2018 by Amol
					
					
					
					//query to save Email sending logs in DB // added on 11-10-2017
					$Dmi_sent_email_log->save(array(
					
						'message_id'=>$message_id,
						'destination_list'=>$destination_email_ids_values,
						'sent_date'=>date('Y-m-d H:i:s'),
						'message'=>$sms_message,
						'created'=>date('Y-m-d H:i:s'),			
						'template_id'=>$template_id //added on 02-03-2021 by Amol
					
					));
					
				}
			
			}//end of 1st if condition 24-07-2018
			
			
			$_SESSION['flow_table'] = '';
			$_SESSION['chemistId'] = '';
		}
		
		
		//this function is created on 08-07-2017 by Amol to replace dynamic values in message
		public function replace_dynamic_values_from_message($customer_id,$message)
		{
			
			//for replica
			$Dmi_chemist_registrations = new Dmi_chemist_registrations();
			
			/*$chemistId = '';
			if(preg_match("/^[CHM]+\/[0-9]+\/[0-9]+$/", $customer_id,$matches)==1){
				
				$get_packer_id = $Dmi_chemist_registrations->find('first',array('fields'=>'created_by','conditions'=>array('chemist_id'=>$customer_id)));
				$packer_id = $get_packer_id['Dmi_chemist_registrations']['created_by'];
				
				
				$chemistId = $customer_id;
				 
				$customer_id = $packer_id;
			}*/

			//getting count before execution
			$total_occurrences = substr_count($message,"%%");
	
			while($total_occurrences > 0){

				$matches = explode('%%',$message);//getting string between %% & %%

				if(!empty($matches[1]))
				{
					switch ($matches[1]) {
						
						case "submission_date":
							
							//$message = str_replace("%%submission_date%%",$this->get_submission_date($customer_id),$message);
							$message = str_replace("%%submission_date%%",$this->get_replace_dynamic_values('submission_date',$customer_id),$message);	
							break;
							
						case "firm_name":
						
							//$message = str_replace("%%firm_name%%",$this->get_firm_name($customer_id),$message);
							$message = str_replace("%%firm_name%%",$this->get_replace_dynamic_values('firm_name',$customer_id),$message);
							break;
							
						case "amount":
						
							//$message = str_replace("%%amount%%",$this->get_amount($customer_id),$message);
							$message = str_replace("%%amount%%",$this->get_replace_dynamic_values('amount',$customer_id),$message);
							break;
							
						case "commodities":
						
							//$message = str_replace("%%commodities%%",$this->get_commodities($customer_id),$message);
							$message = str_replace("%%commodities%%",$this->get_replace_dynamic_values('commodities',$customer_id),$message);
							break;
							
						case "applicant_name":
						
							$message = str_replace("%%applicant_name%%",$this->get_replace_dynamic_values('applicant_name',$customer_id),$message);
							break;
							
						case "applicant_mobile_no":
						
							$message = str_replace("%%applicant_mobile_no%%",$this->get_replace_dynamic_values('applicant_mobile_no',$customer_id),$message);
							break;	
								
						case "company_id":
						
							$message = str_replace("%%company_id%%",$this->get_replace_dynamic_values('company_id',$customer_id),$message);
							break;
							
						case "certificate_valid_upto"://added on 05-02-2018 by Amol
						
							$message = str_replace("%%certificate_valid_upto%%",$this->get_replace_dynamic_values('certificate_valid_upto',$customer_id),$message);
							break;
								
						// Add new paramerter list (done by pravin 07-03-2018)	
						
						case "premises_id":
						
							$message = str_replace("%%premises_id%%",$customer_id,$message);
							break;
						
						case "firm_email":
						
							$message = str_replace("%%firm_email%%",$this->get_replace_dynamic_values('firm_email',$customer_id),$message);
							break;
								
						case "firm_certification_type":
						
							$message = str_replace("%%firm_certification_type%%",$this->get_replace_dynamic_values('firm_certification_type',$customer_id),$message);
							break;
							
						case "ro_name":
						
							$message = str_replace("%%ro_name%%",$this->get_replace_dynamic_values('ro_name',$customer_id),$message);
							break;
							
						case "ro_mobile_no":
						
							$message = str_replace("%%ro_mobile_no%%",$this->get_replace_dynamic_values('ro_mobile_no',$customer_id),$message);
							break;
							
						case "ro_office":
						
							$message = str_replace("%%ro_office%%",$this->get_replace_dynamic_values('ro_office',$customer_id),$message);
							break;
							
						case "ro_email_id":
						
							$message = str_replace("%%ro_email_id%%",$this->get_replace_dynamic_values('ro_email_id',$customer_id),$message);
							break;
							
						case "mo_name":
						
							$message = str_replace("%%mo_name%%",$this->get_replace_dynamic_values('mo_name',$customer_id),$message);
							break;
							
						case "mo_mobile_no":
						
							$message = str_replace("%%mo_mobile_no%%",$this->get_replace_dynamic_values('mo_mobile_no',$customer_id),$message);
							break;
							
						case "mo_office":
						
							$message = str_replace("%%mo_office%%",$this->get_replace_dynamic_values('mo_office',$customer_id),$message);
							break;
							
						case "mo_email_id":
						
							$message = str_replace("%%mo_email_id%%",$this->get_replace_dynamic_values('mo_email_id',$customer_id),$message);
							break;
							
						case "io_name":
						
							$message = str_replace("%%io_name%%",$this->get_replace_dynamic_values('io_name',$customer_id),$message);
							break;
							
						case "io_mobile_no":
						
							$message = str_replace("%%io_mobile_no%%",$this->get_replace_dynamic_values('io_mobile_no',$customer_id),$message);
							break;
							
						case "io_office":
						
							$message = str_replace("%%io_office%%",$this->get_replace_dynamic_values('io_office',$customer_id),$message);
							break;
							
						case "io_email_id":
						
							$message = str_replace("%%io_email_id%%",$this->get_replace_dynamic_values('io_email_id',$customer_id),$message);
							break;
							
						case "dyama_name":
						
							$message = str_replace("%%dyama_name%%",$this->get_replace_dynamic_values('dyama_name',$customer_id),$message);
							break;
						
						case "dyama_mobile_no":
						
							$message = str_replace("%%dyama_mobile_no%%",$this->get_replace_dynamic_values('dyama_mobile_no',$customer_id),$message);
							break;

						case "dyama_email_id":
						
							$message = str_replace("%%dyama_email_id%%",$this->get_replace_dynamic_values('dyama_email_id',$customer_id),$message);
							break;
							
						case "jtama_name":
						
							$message = str_replace("%%jtama_name%%",$this->get_replace_dynamic_values('jtama_name',$customer_id),$message);
							break;

						case "jtama_mobile_no":
						
							$message = str_replace("%%jtama_mobile_no%%",$this->get_replace_dynamic_values('jtama_mobile_no',$customer_id),$message);
							break;

						case "jtama_email_id":
						
							$message = str_replace("%%jtama_email_id%%",$this->get_replace_dynamic_values('jtama_email_id',$customer_id),$message);
							break;

						case "ama_name":
						
							$message = str_replace("%%ama_name%%",$this->get_replace_dynamic_values('ama_name',$customer_id),$message);
							break;
						
						case "ama_mobile_no":
						
							$message = str_replace("%%ama_mobile_no%%",$this->get_replace_dynamic_values('ama_mobile_no',$customer_id),$message);
							break;

						case "ama_email_id":
						
							$message = str_replace("%%ama_email_id%%",$this->get_replace_dynamic_values('ama_email_id',$customer_id),$message);
							break;
							
						case "io_scheduled_date":
						
							$message = str_replace("%%io_scheduled_date%%",$this->get_replace_dynamic_values('io_scheduled_date',$customer_id),$message);
							break;

						case "applicant_email":
						
							$message = str_replace("%%applicant_email%%",$this->get_replace_dynamic_values('applicant_email',$customer_id),$message);
							break;
							
						//	start add new parameter Done by pravin 20-07-2018
						case "pao_name":
						
							$message = str_replace("%%pao_name%%",$this->get_replace_dynamic_values('pao_name',$customer_id),$message);
							break;

						case "pao_email_id":
						
							$message = str_replace("%%pao_email_id%%",$this->get_replace_dynamic_values('pao_email_id',$customer_id),$message);
							break;		
							
						case "pao_mobile_no":
						
							$message = str_replace("%%pao_mobile_no%%",$this->get_replace_dynamic_values('pao_mobile_no',$customer_id),$message);
							break;		
						// end
						
						// Start add new parameter Done by pravin 23-07-2018
						case "ho_mo_name":
						
							$message = str_replace("%%ho_mo_name%%",$this->get_replace_dynamic_values('ho_mo_name',$customer_id),$message);
							break;

						case "ho_mo_mobile_no":
						
							$message = str_replace("%%ho_mo_mobile_no%%",$this->get_replace_dynamic_values('ho_mo_mobile_no',$customer_id),$message);
							break;

						case "ho_mo_email_id":
						
							$message = str_replace("%%ho_mo_email_id%%",$this->get_replace_dynamic_values('ho_mo_email_id',$customer_id),$message);
							break;		
						// end 

						case "home_link":
						
							$message = str_replace("%%home_link%%",$_SERVER['HTTP_HOST'],$message);
							break;		
							

						//for replica 
						case "chemist_name":
						
							$message = str_replace("%%chemist_name%%",$this->get_replace_dynamic_values('chemist_name',$customer_id),$message);
							break;			

						case "chemist_id":
						
							$message = str_replace("%%chemist_id%%",$this->get_replace_dynamic_values('chemist_id',$customer_id),$message);
							break;

						case "replica_commodities":
						
							$message = str_replace("%%replica_commodities%%",$this->get_replace_dynamic_values('replica_commodities',$customer_id),$message);
							break;			

						default:						
							
							$message = $this->replace_between($message, '%%', '%%', '');	
							$default_value = 'yes';						
							break;	
					}
				}
					if(empty($default_value)){	
						$total_occurrences = substr_count($message,"%%");//getting count after execution
					}else{
						$total_occurrences = $total_occurrences - 1;
					}
			}

			return $message;
		}
		
		// commented by pravin on 24-08-2017 and created new common function below
		/*
		//this function is created on 08-07-2017 by Amol to get firm name of current applicant
		public function get_firm_name($customer_id){
			
			$Dmi_firm = new Dmi_firm();
			
			$fetch_firm_data = $Dmi_firm->find('first',array('conditions'=>array('customer_id'=>$customer_id)));
				
			$firm_name = $fetch_firm_data['Dmi_firm']['firm_name'];
			
			return $firm_name;
		}
		
		
		
		//this function is created on 08-07-2017 by Amol to get application Submission Date
		public function get_submission_date($customer_id){
			
			$Dmi_final_submit = new Dmi_final_submit();
			
			$final_submit_data = $Dmi_final_submit->find('first',array('conditions'=>array('customer_id'=>$customer_id, 'status'=>'pending')));
				
			$submission_date = $final_submit_data['Dmi_final_submit']['created'];
			
			return $submission_date;
		}
		
		
		
		//this function is created on 08-07-2017 by Amol to get application total charges
		public function get_amount($customer_id){
			
			$Dmi_firm = new Dmi_firm();
			
			$fetch_firm_data = $Dmi_firm->find('first',array('conditions'=>array('customer_id'=>$customer_id)));
				
			$amount = $fetch_firm_data['Dmi_firm']['total_charges'];
			
			return $amount;
		}
		
		
		
		//this function is created on 08-07-2017 by Amol to get applicant selected commodities
		public function get_commodities($customer_id){
			
			$Dmi_firm = new Dmi_firm();
			$M_commodity = new M_commodity();
			
			$fetch_firm_data = $Dmi_firm->find('first',array('conditions'=>array('customer_id'=>$customer_id)));

			$get_commodity_id = explode(',',$fetch_firm_data['Dmi_firm']['sub_commodity']);
					
			$get_commodity_name = $M_commodity->find('list',array('fields'=>'commodity_code,commodity_name','conditions'=>array('commodity_code'=>$get_commodity_id)));
			
			$commodities = implode(',',$get_commodity_name);
			
			return $commodities;
		}*/
		
		
		
		
		// This function find and return the value of replace variable value that are used in sms/email message templete
		// Created By Pravin on 24-08-2017
		public function get_replace_dynamic_values($replace_variable_value,$customer_id){
			
			$Dmi_customer = new Dmi_customer();
			$Dmi_firm = new Dmi_firm();
			$Dmi_ro_office = new Dmi_ro_office();
			$Dmi_allocation = new Dmi_allocation();
			$Dmi_user = new Dmi_user();
			$Dmi_user_role = new Dmi_user_role();
			$Dmi_ho_allocation = new Dmi_ho_allocation();
			$Dmi_final_submit = new Dmi_final_submit();
			$M_commodity = new M_commodity();
			$Dmi_certificate_type = new Dmi_certificate_type();
			$Dmi_grant_certificates_pdf = new Dmi_grant_certificates_pdf();
			$Dmi_applicant_payment_detail = new Dmi_applicant_payment_detail();//added on 23-07-2017 by Pravin
			$Dmi_pao_detail = new Dmi_pao_detail();//added on 23-07-2017 by Pravin
			
			$AppController = new AppController();//added on 05-02-2018 by Amol
			
			//for replicawwa21`1`w2wqa
			$Dmi_chemist_registrations = new Dmi_chemist_registrations();
			$Dmi_replica_allotment_detail = new Dmi_replica_allotment_detail();

			//for replica
			$chemist_name = null;
			$chemist_id = null;
			$replica_commodities = null;
			
			
			/*
			$chemistId = '';
			if(preg_match("/^[CHM]+\/[0-9]+\/[0-9]+$/", $customer_id,$matches)==1){
				
				$get_packer_id = $Dmi_chemist_registrations->find('first',array('conditions'=>array('chemist_id'=>$customer_id)));
				$packer_id = $get_packer_id['Dmi_chemist_registrations']['created_by'];
				
				$chemist_name = $get_packer_id['Dmi_chemist_registrations']['chemist_fname']." ".$get_packer_id['Dmi_chemist_registrations']['chemist_lname']; 
				$chemist_id = $get_packer_id['Dmi_chemist_registrations']['chemist_id'];
				
				$chemistId = $customer_id;
				$customer_id = $packer_id;
			}

			*/

			//check if applied for renewal, then load renewal models, on 05-12-2019 by Amol
			$Dmi_renewal_final_submit = new Dmi_renewal_final_submit();
 
			$check_for_renewal = $Dmi_renewal_final_submit->find('first',array('conditions'=>array('customer_id'=>$customer_id)));
			
			$flow_table = $_SESSION['flow_table'];
			if($flow_table == 'Dmi_chemist_final_submit'){
				

				$allocation_model = 'Dmi_chemist_allocation';
				$payment_detail_model = 'Dmi_adv_payment_detail';
				$Dmi_applicant_payment_detail = new Dmi_adv_payment_detail();
				$Dmi_allocation = new Dmi_chemist_allocation();
				
			}else{
				
				if(!empty($check_for_renewal)){
					//load renewal related models
					$Dmi_allocation = new Dmi_renewal_allocation();
					$Dmi_ho_allocation = new Dmi_renewal_ho_allocation();
					$Dmi_applicant_payment_detail = new Dmi_renewal_applicant_payment_detail();
					$Dmi_final_submit = $Dmi_renewal_final_submit;
					
					//set renewal model variable
					$allocation_model = 'Dmi_renewal_allocation';
					$ho_allocation_model = 'Dmi_renewal_ho_allocation';
					$payment_detail_model = 'Dmi_renewal_applicant_payment_detail';
					$final_submit_model = 'Dmi_renewal_final_submit';
					
				}else{//as a new application
					$allocation_model = 'Dmi_allocation';
					$ho_allocation_model = 'Dmi_ho_allocation';
					$payment_detail_model = 'Dmi_applicant_payment_detail';
					$final_submit_model = 'Dmi_final_submit';
				}
			
			}
			
			
			if(preg_match("/^[0-9]+\/[0-9]+$/",$customer_id,$matches)==1)
			{						
				$fetch_applicant_data = $Dmi_customer->find('first',array('conditions'=>array('customer_id'=>$customer_id)));
				$fetch_applicant_data = $fetch_applicant_data['Dmi_customer'];
					
			}
			else{
				
	
				$fetch_firm_data = $Dmi_firm->find('first',array('conditions'=>array('customer_id'=>$customer_id)));
				$firm_data = $fetch_firm_data['Dmi_firm'];
			
				$get_commodity_id = explode(',',$fetch_firm_data['Dmi_firm']['sub_commodity']);	
				$get_commodity_name = $M_commodity->find('list',array('fields'=>'commodity_code,commodity_name','conditions'=>array('commodity_code'=>$get_commodity_id)));

				$firm_certification_type_id = $firm_data['certification_type'];
				$firm_certification_type = $Dmi_certificate_type->find('first',array('conditions'=>array('id'=>$firm_certification_type_id)));
							
				$final_submit_data = $Dmi_final_submit->find('first',array('conditions'=>array('customer_id'=>$customer_id, 'status'=>'pending'),'order'=>'id desc'));
				//Check empty condition (Done by pravin 13/2/2018)
				if(!empty($final_submit_data)){
					$final_submit_data = $final_submit_data[$final_submit_model]['created'];//added dynamic variable on 05-12-2019 by Amol
				}else{
					$final_submit_data = null;
				}
				
				
				$split_customer_id = split('/',$customer_id);
				$district_ro_code = $split_customer_id[2];
							
				$find_ro_email_id = $Dmi_ro_office->find('first',array('conditions'=>array('short_code'=>$district_ro_code)));			
				$ro_email_id	=	$find_ro_email_id['Dmi_ro_office']['ro_email_id'];
				$ro_user_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$ro_email_id)));
				$ro_user_data = $ro_user_data['Dmi_user'];
				
				$find_allocated_mo = $Dmi_allocation->find('first',array('conditions'=>array('customer_id'=>$customer_id,'level_3'=>$ro_email_id),'order'=>'id desc'));
				if(!empty($find_allocated_mo)){
					
					$mo_email_id = $find_allocated_mo[$allocation_model]['level_1'];//added dynamic variable on 05-12-2019 by Amol
					$mo_user_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$mo_email_id)));
					if(!empty($mo_user_data)){
					$mo_user_data = $mo_user_data['Dmi_user'];
					}
				}
				
				
				$find_allocated_io = $Dmi_allocation->find('first',array('conditions'=>array('customer_id'=>$customer_id,'level_3'=>$ro_email_id),'order'=>'id desc'));
				if(!empty($find_allocated_io)){	
					
					$io_email_id = $find_allocated_io[$allocation_model]['level_2'];//added dynamic variable on 05-12-2019 by Amol
					$io_user_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$io_email_id)));
					if(!empty($io_user_data)){
					$io_user_data = $io_user_data['Dmi_user'];
					}
				}
				
				$find_dy_ama_user = $Dmi_user_role->find('first',array('fields'=>'user_email_id','conditions'=>array('dy_ama'=>'yes')));
				$dy_ama_email_id = $find_dy_ama_user['Dmi_user_role']['user_email_id'];
				$dy_ama_user_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$dy_ama_email_id)));
				$dy_ama_user_data = $dy_ama_user_data['Dmi_user'];
				
				$find_jt_ama_user = $Dmi_user_role->find('first',array('fields'=>'user_email_id','conditions'=>array('jt_ama'=>'yes')));
				$jt_ama_email_id = $find_jt_ama_user['Dmi_user_role']['user_email_id'];
				$jt_ama_user_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$jt_ama_email_id)));
				$jt_ama_user_data = $jt_ama_user_data['Dmi_user'];
					
				$find_ama_user = $Dmi_user_role->find('first',array('fields'=>'user_email_id','conditions'=>array('ama'=>'yes')));
				$ama_email_id = $find_ama_user['Dmi_user_role']['user_email_id'];
				$ama_user_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$ama_email_id)));
				$ama_user_data = $ama_user_data['Dmi_user'];
				
				//Get ho_mo_details (Done by pravin 23-07-2018)
				$find_allocated_ho_mo = $Dmi_ho_allocation->find('first',array('conditions'=>array('customer_id'=>$customer_id, 'dy_ama'=>$dy_ama_email_id),'order'=>'id desc'));
				if(!empty($find_allocated_ho_mo)){
					$ho_mo_email_id = $find_allocated_ho_mo[$ho_allocation_model]['ho_mo_smo'];//added dynamic variable on 05-12-2019 by Amol
					$fetch_ho_mo_data = $Dmi_user->find('first',array('conditions'=>array('email'=>$ho_mo_email_id)));
					if(!empty($fetch_ho_mo_data)){
						$ho_mo_mob_no = $fetch_ho_mo_data['Dmi_user']['phone'];					
						$ho_mo_name = $fetch_ho_mo_data['Dmi_user']['f_name']." ".$fetch_ho_mo_data['Dmi_user']['l_name'];
					}
				}
				
				$get_io_scheduled_date = $Dmi_allocation->find('first',array('conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'));
				
				if(!empty($get_io_scheduled_date)){//condition added on 11-10-2017 by Amol
					$io_scheduled_date = $get_io_scheduled_date[$allocation_model]['io_scheduled_date'];//added dynamic variable on 05-12-2019 by Amol
				}else{
					$io_scheduled_date = '---';
				}
				
				
				//get renewal valid upto date
				//added on 05-02-2018 by Amol
				$each_application_grant_list = $Dmi_grant_certificates_pdf->find('list',array('conditions'=>array('customer_id'=>$customer_id)));
				if(!empty($each_application_grant_list)){
					
					$last_grant_details = $Dmi_grant_certificates_pdf->find('first',array('conditions'=>array('id'=>max($each_application_grant_list))));
					$last_grant_date = $last_grant_details['Dmi_grant_certificates_pdf']['date'];
					
					//get certificate valid upto date
					$certificate_valid_upto = $AppController->get_certificate_valid_upto_date($customer_id,$last_grant_date);
					
				}else{
					
					$certificate_valid_upto = '';
				}
				
				//Get pao_name and pao_email (Done by pravin 20-07-2018)
				$find_pao_id = $Dmi_applicant_payment_detail->find('first',array('conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'));
				if(!empty($find_pao_id)){
					$pao_id =  $find_pao_id[$payment_detail_model]['pao_id'];//added dynamic variable on 05-12-2019 by Amol
					$find_user_id =  $Dmi_pao_detail->find('first',array('conditions'=>array('id'=>$pao_id)));
					$user_id =  $find_user_id['Dmi_pao_detail']['pao_user_id'];	
					$fetch_pao_data = $Dmi_user->find('first',array('conditions'=>array('id'=>$user_id)));
					$pao_mobile_no = $fetch_pao_data['Dmi_user']['phone'];
					$pao_email_id = $fetch_pao_data['Dmi_user']['email'];
					$pao_name = $fetch_pao_data['Dmi_user']['f_name']." ".$fetch_pao_data['Dmi_user']['l_name'];
				}
			
			
				//get chemist name
				$get_chemist_name = $Dmi_chemist_registrations->find('first',array('conditions'=>array('chemist_id'=>$_SESSION['chemistId'],'delete_status'=>NULL),'order'=>'id desc'));
						
				if (!empty($get_chemist_name)) {

					$chemist_name = $get_chemist_name['Dmi_chemist_registrations']['chemist_fname']." ".$get_chemist_name['Dmi_chemist_registrations']['chemist_lname']; 
					$chemist_id = $get_chemist_name['Dmi_chemist_registrations']['chemist_id'];
				}
				
				//get replica commodties
				$get_replica_commodities = $Dmi_replica_allotment_detail->find('list',array('fields'=>'commodity','conditions'=>array('customer_id'=>$customer_id,'delete_status'=>NULL,'allot_status'=>NULL)));
						
				if (!empty($get_replica_commodities)) {
					
					foreach ($get_replica_commodities as $commodities) {
						
						$get_commodity_name = $M_commodity->find('list',array('fields'=>'commodity_code,commodity_name','conditions'=>array('commodity_code'=>$get_replica_commodities)));
						$replica_commodities = implode(',',$get_commodity_name);
					
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
						
							$firm_email = $firm_data['email'];
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
						
							$ro_office = $find_ro_email_id['Dmi_ro_office']['ro_office'];
							return $ro_office; 
							break;
							
						case "ro_email_id":
						
							$ro_email_id = $find_ro_email_id['Dmi_ro_office']['ro_email_id'];
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
						
							$mo_office = $find_ro_email_id['Dmi_ro_office']['ro_office'];
							return $mo_office; 
							break;
							
						case "mo_email_id":
						
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
						
							$io_office = $find_ro_email_id['Dmi_ro_office']['ro_office'];
							return $io_office; 
							break;
							
						case "io_email_id":
						
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
		function replace_between($str, $needle_start, $needle_end, $replacement) {
			$pos = strpos($str, $needle_start);
			$start = $pos === false ? 0 : $pos + strlen($needle_start);

			$pos = strpos($str, $needle_end, $start);
			$end = $start === false ? strlen($str) : $pos;

			return substr_replace($str,$replacement,$start);
		}
		

} ?>