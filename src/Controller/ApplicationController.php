<?php
	namespace App\Controller;
	use Cake\Event\EventInterface;
	use Cake\Network\Session\DatabaseSession;
	use App\Network\Email\Email;
	use App\Network\Request\Request;
	use App\Network\Response\Response;
	use Cake\ORM\TableRegistry;

	class ApplicationController extends AppController{

		var $name = 'Application';

		public function initialize(): void
		{
			parent::initialize();

			$this->loadComponent('Customfunctions');
			$this->loadComponent('Authentication');
			$this->loadComponent('Paymentdetails');
			$this->loadComponent('Mastertablecontent');
			$this->loadComponent('Communication');
			$this->loadComponent('Progressbar');
			$this->loadComponent('Romoioapplicantcommunicationactions');
			$this->loadComponent('Flowbuttons');
			$this->loadComponent('Randomfunctions');
			$this->loadModel('DmiSmsEmailTemplates');
		

			$this->viewBuilder()->setHelpers(['Form','Html','Time']);

			$this->Session = $this->getRequest()->getSession();
		}
		
		
		// BEFORE FILTER
		public function beforeFilter($event) {

			parent::beforeFilter($event);

			$this->loadModel('DmiFirms');
			$customer_last_login = $this->Customfunctions->customerLastLogin();
            $this->set('customer_last_login',$customer_last_login);

			//checking the user type by akash on 20/09/2021
			$username = $this->Session->read('username');

			if ($username == null) {

				$this->invalidActivities();

			} else {
				//this else portion added on 10-07-2017 by Amol to allow only logged in Applicant

				if (preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $this->Session->read('username'),$matches)==1) {
					//to check the application is new, not old//on 17/10/2017
                    $this->loadModel('DmiFirms');
					$check_applicant_is_new = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$this->Session->read('username')/* ,'is_already_granted'=>'no' */)))->first();

					if (!empty($check_applicant_is_new)) {
						//Give Permission
					} else {
						$this->invalidActivities();
					}
					
				} elseif (preg_match("/^[A-Z]+\/[0-9]+\/[0-9]+$/", $this->Session->read('username'),$matches)==1) {
					
					$this->loadModel('DmiChemistRegistrations');

					$check_applicant_is_new = $this->DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$this->Session->read('username')/* ,'is_already_granted'=>'no' */)))->first();

					if (!empty($check_applicant_is_new)) {
						//Give Permission
					} else {
						$this->invalidActivities();
					}
					
				} else {

					$this->loadModel('DmiUsers');
					$this->loadModel('DmiAuthFirmRegistrations');

					$check_user = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
					//$check_auth_reg = $this->DmiAuthFirmRegistrations->find('all',array('conditions'=>array('firm_id'=>$this->Session->read('customer_id'))))->first();

					if (empty($check_user)) {	$this->invalidActivities(); }
				}
			}
		}


		// CHANGE REQUEST
		public function changeRequest() {

			$this->viewBuilder()->setLayout('secondary_customer');
			$this->loadModel('DmiChangeSelectedFields');
			$this->loadModel('DmiChangeFieldLists');

			$customer_id = $this->Customfunctions->sessionCustomerID();
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
			$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
			$final_submit_details = $this->Customfunctions->finalSubmitDetails($customer_id,'application_form');
			$this->set('final_submit_details',$final_submit_details);

			$changeFieldsList = $this->DmiChangeFieldLists->find('list',array('keyField'=>'field_id','valueField'=>'change_field','conditions'=>array('form_type IS'=>$form_type),'order'=>'field_id'))->toArray();
			$this->set('changeFieldsList',$changeFieldsList);

			$selectedValues = $this->DmiChangeSelectedFields->selectedChangeFields();
			$this->set('selectedValues',$selectedValues[0]);

			if ($this->request->is('post')) {

				$fieldResult = $this->DmiChangeFieldLists->changeFieldList($this->request->getData('changefield'));

				$changeFieldString = implode(',',$this->request->getData('changefield'));
				$selectedValuesString = implode(',',$selectedValues[0]);

				if ($selectedValuesString !== null && $selectedValuesString !== $changeFieldString) {

					$this->Customfunctions->deleteChangeRequestEntry($customer_id);
				}

				$this->DmiChangeSelectedFields->saveData($changeFieldString);
				$this->Session->write('changefield',$fieldResult[0]);
				$this->Session->write('paymentforchange',$fieldResult[1]);
				$this->redirect('/application/application-for-certificate');
			}
		}
		
		
		// FILL FORM FETCH ID
		public function fillFormFetchId($id,$applicant_type,$mode,$authscrutiny=null) {


			$this->loadModel('DmiFirms');
			$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$id)))->first();

			$this->Session->write('customer_id',$firm_details['customer_id']);
			$this->Session->write('application_type',$applicant_type);
			$this->Session->write('application_mode',$mode);
			$this->Session->delete('authscrutiny');

			if (!empty($authscrutiny)) { $this->Session->write('authscrutiny',$authscrutiny); }

			$this->Session->delete('section_id');
			$this->redirect('/application/application-for-certificate');
		}
		
		
		// APPLICATION TYPE
		public function applicationType($id) {

			$this->Session->delete('section_id');
			$this->Session->delete('paymentforchange');
			$this->Session->write('application_type',$id);
			$this->Session->write('section_id',null);
			$this->Session->write('current_level','applicant');

			if ($id==3) { $this->redirect('/application/change-request'); }
			else{ $this->redirect('/application/application-for-certificate'); }
		}
		
		
		// SECTION
		public function section($id) {

			$this->Session->write('section_id',$id);
			$this->redirect('/application/application-for-certificate');
		}

		
		// APPLICATION FOR CERTIFICATE
		public function applicationForCertificate() {

			$customer_id = $this->Customfunctions->sessionCustomerID();
			$this->set('customer_id',$customer_id);
			
			$oldapplication = $this->Customfunctions->isOldApplication($customer_id);
			$this->set('oldapplication',$oldapplication);

			$authRegFirm = $this->Mastertablecontent->authFirmRegistration($customer_id);
			$this->set('authRegFirm',$authRegFirm);

			if (strpos(base64_decode($this->Session->read('username')), '@') !== false) {//for email encoding
				$this->viewBuilder()->setLayout('old_app_scrutiny_layout');
			} else {
				$this->viewBuilder()->setLayout('application_forms_layout');
			}

			$this->set('cname','Application');

			// set variable for holding message theme value like 'success', 'failed' @by Aniket Ganvir dated 15th DEC 2020
			$message = "";
			$message_theme = "";
			$redirect_to = "";

			$this->loadModel('DmiAllDirectorsDetails');
			$this->loadModel('DmiFirms');
			$this->loadModel('DmiChemistRegistrations');

			$application_type = $this->Session->read('application_type');
			$this->set('application_type',$application_type);
			
			// check current form section value
			if (isset($_SESSION['section_id'])) {
				$section_id = $this->Session->read('section_id');
			} else {
				$section_id = 1;
				$this->Session->write('section_id',$section_id);
			}

			// For Chemist added the appluicant type 4 and replaced chemist id with customer id , Done By AKASH THAKRE 30-09-2021
			if ($application_type == 4) {

				$chemistDetails = $this->DmiChemistRegistrations->find('all',array('conditions'=>array('chemist_id IS'=>$customer_id,'delete_status IS NULL')))->first();
				if (!empty($chemistDetails)) {
					$chemist_id = $customer_id;
					$customer_id = $chemistDetails['created_by'];
					$packer_id = $customer_id;
					$this->Session->write('packer_id',$packer_id);
				}

				$this->Session->write('application_dashboard','chemist');
				$this->Communication->singleWindowCommentHistory($chemist_id);
				
			}			


			$this->Customfunctions->showOldCertDetailsPopup($customer_id);


			if ($application_type == 3) {
				$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiChangeDirectorsDetails');
			} else {
				$DmiAllDirectorsDetails = TableRegistry::getTableLocator()->get('DmiAllDirectorsDetails');
			}

			$added_directors_details = $DmiAllDirectorsDetails->allDirectorsDetail($customer_id);
			$this->set('added_directors_details',$added_directors_details);
			$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
			$firm_type = $this->Customfunctions->firmType($customer_id);
			$this->set('firm_type',$firm_type);			
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
			
			$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
			$this->set('export_unit_status',$export_unit_status);
			$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
			$this->set('ca_bevo_applicant',$ca_bevo_applicant);
			$applicant_type = $this->Customfunctions->checkFatSpreadOrBevo($customer_id);//call fucntion to check bevo or fat spread
			$this->set('applicant_type',$applicant_type);

			

			if ($form_type=='F' && $ca_bevo_applicant=='yes') {
				$form_type='E';
			}

			// Predefine value
			$business_type = $this->Mastertablecontent->allBusinessType();
			$this->set('business_type',$business_type);
			$state_list = $this->Mastertablecontent->allStateValue();
			asort($state_list);
			$this->set('state_list',$state_list);
			$distict_list = $this->Mastertablecontent->allDistrictValue();
			$this->set('distict_list',$distict_list);
			$all_ca_business_year = $this->Mastertablecontent->allCaBusinessYear();
			$this->set('all_ca_business_year',$all_ca_business_year);
			$all_printing_business_year = $this->Mastertablecontent->allPrintingBusinessYear();
			$this->set('all_printing_business_year',$all_printing_business_year);
			$rushing_refining_period = $this->Mastertablecontent->allCrushingRefiningValue();
			$this->set('rushing_refining_period',$rushing_refining_period);
			$allPackingType = $this->Mastertablecontent->allPackingType();
			$this->set('allPackingType',$allPackingType);
			$firm_details = $this->DmiFirms->firmDetails($customer_id);
            $this->set('firm_details',$firm_details);
			$document_lists = $this->Mastertablecontent->allDocumentsList();
            $this->set('document_lists',$document_lists);

			//get commodity details, for option to update commodities
			//applied on 02-07-2021 by Amol
			$this->Randomfunctions->getCommodityDetails($firm_details,$firm_type);

			// get current section all details
            $this->loadModel('DmiCommonScrutinyFlowDetails');

			// pravin bhakare 28-09-2021
			if ($application_type == 4) {

				$customer_id = $chemist_id;
				$form_type='CHM';
			}
			
			$this->set('form_type',$form_type);
			
			$firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
			$final_submit_details = $this->Customfunctions->finalSubmitDetails($customer_id,'application_form');
			$this->set('final_submit_details',$final_submit_details);

			$section_details = $this->DmiCommonScrutinyFlowDetails->currentSectionDetails($application_type,$office_type,$firm_type,$form_type,$section_id);

			// get all section all details
			$allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);

			$section_model = $section_details['section_model'];
			$section = $section_details['section_name'];

			$this->loadModel($section_model);

			// get section details
			$section_form_details = $this->$section_model->sectionFormDetails($customer_id);


			// if return value 1 (all forms saved), return value 2 (all forms approved), return value 0 (all forms not saved or approved)
			$all_section_status = $this->Customfunctions->formStatusValue($allSectionDetails,$customer_id);
            $progress_bar_status = $this->Progressbar->formsProgressBarStatus($allSectionDetails,$customer_id);
            $this->set('progress_bar_status',$progress_bar_status);

			//get forward btn and grant btn display status value
			$forward_to_btn = $this->Flowbuttons->ShowNodalLevelForwardBtnAfterScru($customer_id,$application_type,$section_details,$allSectionDetails);
			$this->set('forward_to_btn',$forward_to_btn);
			$final_granted_btn = $this->Flowbuttons->ShowNodalLevelGrantBtnAfterScru($customer_id,$application_type,$section_details,$allSectionDetails);
			$this->set('final_granted_btn',$final_granted_btn);

			// get previous and next button id
			$nextPreviousBtn =	$this->Customfunctions->getNextPreSec($allSectionDetails);
			
			$this->set('section',$section);
			$this->set('tablename',$section_model);
			$this->set('current_form_data',$section_form_details[0]);
			$this->set('all_section_status',$all_section_status);
			$this->set('section_details',$section_details);
			$this->set('allSectionDetails',$allSectionDetails);
			$this->set('section_form_details',$section_form_details);
			$this->set('previousbtnid',$nextPreviousBtn[0]);
			$this->set('nextbtnid',$nextPreviousBtn[1]);

			//added by Amol on 04-06-2021, as the variables used below and not defined anyware.
			$previousbtnid = $nextPreviousBtn[0];
			$nextbtnid = $nextPreviousBtn[1];

			if (!empty($final_submit_details)) {
				$final_submit_status = $final_submit_details['status'];
			} else {
				$final_submit_status = 'no_final_submit';
			}

			$this->set('final_submit_status',$final_submit_status);


			/* For change module*/
			$fstatuses = array('pending','replied','approved');
			if ($application_type == 3 && in_array($final_submit_status,$fstatuses) == false) {
				$changefields = $this->Session->read('changefield');
			} else {
				$changefields = array();
			}
			
			$this->set('changefields',json_encode($changefields));

			$selectedSections = array();
			if ($application_type == 3) {
				$this->loadModel('DmiChangeSelectedFields');
				$selectedfields = $this->DmiChangeSelectedFields->selectedChangeFields();
				$selectedSections = $selectedfields[2];
			}
			
			$this->set('selectedSections',$selectedSections);


			// Check current forms is saved or not
			if (empty($section_form_details[0]['created'])) {
				$process_query = "Saved";
			} else { 
				$process_query = "Updated"; 
			}

			//created and commented on 12-05-2021 by Amol intensionally to view comment box
			/*	if (empty($section_form_details[0]['created'])) {
				$process_query = "Updated";
			} else { $process_query = "Saved"; }*/


			$this->set('process_query',$process_query);

			//Get comment history details
			if ($section_details['comment_section']=='yes') {
				$this->Communication->applicantCommentHistory($section_model,$customer_id);
			}

	
			if (null !== $this->request->getData('save_edited_reply')) {

				$id = $this->Session->read('edit_reply_id');
				$htmlencoded_edited_reply = $this->request->getData('edited_reply');

				if ($this->request->getData('cr_comment_ul')->getClientFilename() != null) {

					$attachment = $this->request->getData('cr_comment_ul');
					$file_name = $attachment->getClientFilename();
					$file_size = $attachment->getSize();
					$file_type = $attachment->getClientMediaType();
					$file_local_path = $attachment->getStream()->getMetadata('uri');

					$cr_comment_ul = $this->Customfunctions->fileUploadLib($file_name,$file_size,$file_type,$file_local_path); // calling file uploading function

				} else { 
					$cr_comment_ul = $section_form_details[0]['cr_comment_ul']; 
				}

				$section_modelEntity = $this->$section_model->newEntity(array(
					'id'=>$id,
					'customer_reply'=>$htmlencoded_edited_reply,
					'cr_comment_ul'=>$cr_comment_ul,
					'customer_reply_date'=>date('Y-m-d H:i:s')
				));
				
				if ($this->$section_model->save($section_modelEntity)) {
					$this->Session->delete('edit_reply_id');
					$this->redirect('/application/application-for-certificate');
				}
			}

			
			if (null !== $this->request->getData('save')) {

				$result = $this->$section_model->saveFormDetails($customer_id,$this->request->getData());

				if (is_array($result)=='') {
					
					if ($result == 1) {

						if (empty($section_form_details[0]['created'])) {

							if (empty($nextbtnid)) {

								$paymentSection = $this->Session->read('paymentSection');

								if ($paymentSection == 'available') {
									$redirect_to = '../application/payment';
								} else {
									$redirect_to = '../application/application-for-certificate';
								}

							} else {
								$this->Session->write('section_id',$nextPreviousBtn[1]);
								$redirect_to = '../application/application-for-certificate';
							}
						}

						$message = $firm_type_text.' - '.ucwords(str_replace('_',' ',$section_details['section_name'])).' section, '.$process_query.' successfully';

						//Added this call to save the user action log on 04-03-2022 by Akash
						if ($application_type == 4) {
							$this->Customfunctions->saveActionPoint('Application '."($process_query)", 'Success');
						} else {
							$this->Customfunctions->saveActionPoint('Firm '."($process_query)", 'Success');
						}
						
						$message_theme = 'success';
						$this->render('/element/message_boxes');

					} else {
						
						$this->set('save_result',$result);

						//Added this call to save the user action log on 04-03-2022 by Akash
						if ($application_type == 4) {
							$this->Customfunctions->saveActionPoint('Application '."($process_query)", 'Failed');
						} else {
							$this->Customfunctions->saveActionPoint('Firm '."($process_query)", 'Failed');
						}
						$message = "Please check and fill all required fields before proceeding.";
						$message_theme = 'failed';
						$this->render('/element/message_boxes');
					}
				}
			
			} elseif (null !== $this->request->getData('final_submit')) {
				
				if (!empty($this->request->getData('once_no'))) {
					//calling common function for esigning//applied on 01-11-2017 by Amol
					//$this->processToEsign($customer_id);
				} else {
					
					//proceed without esign
					$this->Session->write('with_esign','no');
					$final_submit_call_result =  $this->Customfunctions->applicationFinalSubmitCall($customer_id,$all_section_status);

					if ($final_submit_call_result == true) {

						$this->Customfunctions->saveActionPoint('Application Final Submit', 'Success'); #Action
						$message = $firm_type_text.' - Final submitted successfully ';
						$message_theme = 'success';

						//Added this call to save the user action log on 04-03-2022 by Akash
						if ($application_type == 4) {
							// SMS - APPLICATION SUBMITTED 
							#$this->DmiSmsEmailTemplates->sendMessage(69,$customer_id); # CHEMIST
							#$this->DmiSmsEmailTemplates->sendMessage(70,$customer_id); # RO
						} else {
							// SMS - APPLICATION SUBMITTED 
							#$this->DmiSmsEmailTemplates->sendMessage(6,$customer_id); # APPLICANT , RO , DDO
						}

						//For Chemist i.e Apllication Type 4 then redirect to Chemist Home after Final Submit -> Akash [29-09-2021].
						if ($application_type == 4) {
							$redirect_to = '../chemist/home';
						} elseif ($authRegFirm=='yes') {
							$redirect_to = '../authprocessedoldapp/home';
						} else {
							$redirect_to = '../applicationformspdfs/'.$section_details['forms_pdf'];
						}

					} else {
						
						$this->Customfunctions->saveActionPoint('Application Final Submit', 'Failed'); #Action
						$message = $firm_type_text.' - All Sections not filled, Please fill all Section and then Final Submit ';
						$message_theme = 'failed';
						$redirect_to = '../application/application-for-certificate';
					}
				
					$this->render('/element/message_boxes');
				}

			} elseif (null !== $this->request->getData('mo_verified')) {

				$result = $this->Romoioapplicantcommunicationactions->ROScrutinizedATMOLevel($customer_id,$section_model,$section_form_details[0],$allSectionDetails);

				if ($result == 1 && $oldapplication=='yes' && $application_type==1) {

					$this->Romoioapplicantcommunicationactions->ROScrutinizedOldApplication($customer_id);

					$message_theme = 'success';
					$message = $firm_type_text." - All sections scrutinized successfully";
					$redirect_to =  '../dashboard/home';
					$this->render('/element/message_boxes');

				} elseif ($result == 2) {

					$message_theme = 'success';
					$message = $firm_type_text." - ".ucwords(str_replace('_',' ',$section_details['section_name']))." Section Scrutinized successfully";
					$redirect_to = "../application/application-for-certificate";
					$this->render('/element/message_boxes');

				} elseif ($result == 3) {

					$message_theme = 'failed';
					$message = $firm_type_text." - ".ucwords(str_replace('_',' ',$section_details['section_name']))." Section already Scrutinized";
					$redirect_to = "../application/application-for-certificate";
					$this->render('/element/message_boxes');

				} elseif ($result == 4) {

					$message_theme = 'failed';
					$message = "Please verify old dates entered before scrutinizing the section, The button is given above to edit/view the old dates.'";
					$redirect_to =  '../application/application-for-certificate';
					$this->render('/element/message_boxes');
				}

			} elseif (null !== $this->request->getData('add_tbl_details')) {

				$this->loadModel('DmiAllTblsDetails');
				$save_details_result = $this->DmiAllTblsDetails->saveTblDetails($customer_id,$this->request->getData());
				
				if ($save_details_result == 1) {
					$this->Session->delete('edit_tbl_id');
					$this->Redirect('/application/application-for-certificate');
				}
				
			} elseif (null !== $this->request->getData('add_chemist_details')) {

				$this->loadModel('DmiLaboratoryChemistsDetails');
				$save_details_result = $this->DmiLaboratoryChemistsDetails->saveChemistDetails($customer_id,$this->request->getData());
				
				if ($save_details_result == 1) {
					$this->Session->delete('edit_chemist_id');
					$this->Redirect('/application/application-for-certificate');
				}
				
			} elseif (null !== $this->request->getData('add_renewal_chemist_details')) {

				$this->loadModel('DmiLaboratoryChemistsDetails');
				$save_details_result = $this->DmiLaboratoryChemistsDetails->saveRenewalChemistDetails($customer_id,$this->request->getData());
				
				if ($save_details_result == 1) {
					$this->Session->delete('edit_chemist_id');
					$this->Redirect('/application/application-for-certificate');
				}
				
			} elseif (null !== $this->request->getData('add_old_chemist_details')) {

				$this->loadModel('DmiLaboratoryChemistsDetails');
				$save_details_result = $this->DmiLaboratoryChemistsDetails->saveformschemistDetailsAtRenewal($customer_id,$this->request->getData());
				
				if ($save_details_result == 1) {
					$this->Session->delete('edit_chemist_id');
					$this->Redirect('/application/application-for-certificate');
				}
			}

			$this->set('message_theme',$message_theme);
			$this->set('message',$message);
			$this->set('redirect_to',$redirect_to);
			$this->set('application_type',$application_type);

			// PRIOR TO THE CAKEPHP 4, "$this->view" IS NOT WORKING,
			// SO ADDED "render" PROPERTY TO POP UP FORM RELATED MESSAGES
			// by Aniket Ganvir dated 29th JAN 2021
			if ($message != null) {
				$this->render('/element/message_boxes');
			}
		}

		
		// PAYMENT
		public function payment() {

			// set menu name for current active menu in sidebar
	        $this->set('current_menu', 'menu_payment');

            $this->viewBuilder()->setLayout('application_forms_layout');
            $this->loadModel('DmiFirms');
			$this->loadModel('DmiChangeFirms');

			$customer_id = $this->Customfunctions->sessionCustomerID();

			if (!empty($customer_id)) {

				// set variables to show popup messages from view file
				$message_theme = ''; // set message theme like error/success @by Aniket Ganvir dated 17th DEC 2020
				$message = '';
				$redirect_to = '';

				//check CA BEVO Applicant
				$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
				$oldapplication = $this->Customfunctions->isOldApplication($customer_id);
				$this->set('ca_bevo_applicant',$ca_bevo_applicant);
				$this->set('oldapplication',$oldapplication);
				
				$application_type = $this->Session->read('application_type');
				$this->set('application_type',$application_type);
				$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
				$firm_type = $this->Customfunctions->firmType($customer_id);
				$this->set('firm_type',$firm_type);
				
				$firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
				
				$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
				$this->set('form_type',$form_type);
				
				if ($form_type=='F' && $ca_bevo_applicant=='yes') {
					$form_type='E';
				}

				$this->loadModel('DmiCommonScrutinyFlowDetails');
				$this->loadModel('DmiFlowWiseTablesLists');

				$section_details = $this->DmiCommonScrutinyFlowDetails->currentSectionDetails($application_type,$office_type,$firm_type,$form_type,1);

				$allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);

				// get previous and next button id
				$previousBtn =	$this->Customfunctions->getNextPreSec($allSectionDetails);
				$previous_button_url = 'application/section/'.$previousBtn[2];

				// For change flow
				$selectedSections = array();
				
				if ($application_type == 3) {
					
					$this->loadModel('DmiChangeSelectedFields');
					$selectedfields = $this->DmiChangeSelectedFields->selectedChangeFields();
					$selectedSections = $selectedfields[2];
				}
				
				$this->set('selectedSections',$selectedSections);

				$payment_table = $this->DmiFlowWiseTablesLists->getFlowWiseTableDetails($application_type,'payment');
				$this->loadModel($payment_table);

				// if return value 1 (all forms saved), return value 2 (all forms approved), return value 0 (all forms not saved or approved)
				//applied on 16-07-2021 by Amol
				if ($application_type == 2) {

					$all_section_status = 1;
					$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
					$payment_status = $this->$payment_table->find('all', array('conditions'=>array('customer_id IS'=>$customer_id,$grantDateCondition,'payment_confirmation IN'=>array('saved','confirmed')),'order'=>'id DESC'))->first();
					if (empty($payment_status)) { $all_section_status = 0; }
					$progress_bar_status = array();

					//to check validity date for remark/reason box
					$this->loadModel('DmiGrantCertificatesPdfs');
					$last_grant_record =  $this->DmiGrantCertificatesPdfs->find('all',array('fields'=>'date','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
					$cert_grant_date = $last_grant_record['date'];
					$validity_date = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$cert_grant_date);
					$current_date = date('Y-m-d H:i:s');
					$this->set('current_date',$current_date);
					$this->set('validity_date',$validity_date);

					//to check if record exist in renewal submission logs table, to fill intimation checkbox and remark field on the page
					$this->loadModel('DmiRenewalSubmissionLogs');
					$checkIntimation = $this->DmiRenewalSubmissionLogs->find('all',array('fields'=>'remark','conditions'=>array('customer_id'=>$customer_id,$grantDateCondition),'order'=>'id desc'))->first();
					$intCheckBox = '';
					$intRemark = '';
					if (!empty($checkIntimation)) {
						$intCheckBox = 'checked';
						$intRemark = $checkIntimation['remark'];
					}
					$this->set('intCheckBox',$intCheckBox);
					$this->set('intRemark',$intRemark);

				} else {

					$all_section_status = $this->Customfunctions->formStatusValue($allSectionDetails,$customer_id);

					$progress_bar_status = $this->Progressbar->formsProgressBarStatus($allSectionDetails,$customer_id);
				}


				$final_submit_details = $this->Customfunctions->finalSubmitDetails($customer_id,'application_form');
				$this->set('final_submit_details',$final_submit_details);


				$this->set('progress_bar_status',$progress_bar_status);

				//intensionally called from DMiChangeFirms, it will fetch record from dmi firms by default if not found from the function
				$firm_detail = $this->DmiChangeFirms->sectionFormDetails($customer_id);
				$firm_details = $firm_detail[0];
				$this->set('firm_details',$firm_details);

				// Fetch submitted Payment Details and show // Done By pravin 13/10/2017
				$this->Paymentdetails->applicantPaymentDetails($customer_id,$firm_details['district'],$payment_table);

				$this->loadModel('DmiApplicationCharges');
				$this->loadModel('MCommodity');
				$this->loadModel('MCommodityCategory');


				$application_charge = $this->Customfunctions->applicationCharges($application_type,$firm_type);
				$this->set('application_charge',$application_charge);

				$this->loadModel($payment_table);
				$list_applicant_payment_id = $this->$payment_table->find('list', array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
				
				if (!empty($list_applicant_payment_id)) { $process_query = 'Updated'; } else { $process_query = 'Saved'; }

				$sub_commodity_array = explode(',',$firm_details['sub_commodity']);

				if (!empty($firm_details['sub_commodity'])) {
					
					$i=0;
					foreach ($sub_commodity_array as $sub_commodity_id)
					{
						$fetch_commodity_id = $this->MCommodity->find('all',array('conditions'=>array('commodity_code IS'=>$sub_commodity_id)))->first();
						$commodity_id[$i] = $fetch_commodity_id['category_code'];
						$sub_commodity_data[$i] =  $fetch_commodity_id;
						$i=$i+1;
					}

					$unique_commodity_id = array_unique($commodity_id);

					$commodity_name_list = $this->MCommodityCategory->find('all',array('conditions'=>array('category_code IN'=>$unique_commodity_id, 'display'=>'Y')))->toArray();

					$this->set('commodity_name_list',$commodity_name_list);

					$this->set('sub_commodity_data',$sub_commodity_data);
				}

				if (!empty($firm_details['packaging_materials'])) {
					
					$this->loadModel('DmiPackingTypes');
					$packaging_materials = explode(',',$firm_details['packaging_materials']);
					$packaging_type = $this->DmiPackingTypes->find('list', array('valueField'=>'packing_type', 'conditions'=>array('id IN'=>$packaging_materials)));
					$this->set('packaging_type',$packaging_type);
				}

				if (!empty($final_submit_details)) {
					$final_submit_status = $final_submit_details['status'];
				} else {
					$final_submit_status = 'no_final_submit';
				}
				
				$this->set('final_submit_status',$final_submit_status);

				// set variables to show popup messages from view file
				$this->set('previous_button_url',$previous_button_url);
				$this->set('allSectionDetails',$allSectionDetails);
				$this->set('all_section_status',$all_section_status);
				$this->set('section_details',$section_details);

				if (null !== ($this->request->getData('final_submit'))) {

					//applied this condition on 26-03-2018 by Amol, with esign or without
					if (!empty($this->request->getData('once_no'))) {
						//calling common function for esigning//applied on 01-11-2017 by Amol
						//$this->process_to_esign($customer_id);
			
					} else {
						//proceed without esign
                        $this->Session->write('with_esign','no');
                        $final_submit_call_result =  $this->Customfunctions->applicationFinalSubmitCall($customer_id,$all_section_status);

                        if ($final_submit_call_result == true) {

							//to update record in renewal submission logs table with status 'submitted', if applied for renewal
							//applied on 14-09-2021 by Amol
							if ($application_type == 2) {

								$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
								$this->loadModel('DmiRenewalSubmissionLogs');
								//get record id to update the status of renewal final submit
								$getId = $this->DmiRenewalSubmissionLogs->find('all',array('fields'=>'id','conditions'=>array('customer_id'=>$customer_id,$grantDateCondition),'order'=>'id desc'))->first();
								$renRecordId = $getId['id'];
								$renewalSubmissionLogEntity = $this->DmiRenewalSubmissionLogs->newEntity(array(

									'id'=>$renRecordId,
									'remark'=>$this->request->getData('late_remark'),
									'modified'=>date('Y-m-d H:i:s'),
									'status'=>'submitted'
								));

								$this->DmiRenewalSubmissionLogs->save($renewalSubmissionLogEntity);
							}
							
							//Added this call to save the user action log on 04-03-2022 by Akash
							$this->Customfunctions->saveActionPoint('Firm Final Submitted', 'Success');
	
							//SMS for Application Submitted on 12-08-2022 By Akash
							#$this->DmiSmsEmailTemplates->sendMessage(6,$customer_id); #To Applicant , RO , DDO

							$message_theme = 'success';
							$message = $firm_type_text.' - Final submitted successfully ';
							$redirect_to = '../applicationformspdfs/'.$section_details['forms_pdf'];
							$this->viewBuilder()->setVar('message', $message);
							$this->viewBuilder()->setVar('redirect_to', $redirect_to);

						} else {

							//Added this call to save the user action log on 04-03-2022 by Akash
							$this->Customfunctions->saveActionPoint('Firm Final Submitted', 'Failed');
							$message_theme = 'failed';
							$message = $firm_type_text.' - All Sections not filled, Please fill all Section and then Final Submit ';
							$redirect_to = '../application/application-for-certificate';
							$this->viewBuilder()->setVar('message', $message);
							$this->viewBuilder()->setVar('redirect_to', $redirect_to);
                        }

						$this->render('/element/message_boxes');
					}
				
				} elseif (null !== ($this->request->getData('save'))) {  // Save payment details by applicant

					//to save record in renewal submission logs table, if applied for renewal
					//applied on 14-09-2021 by Amol
					if ($application_type == 2) {

						$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
						$this->loadModel('DmiRenewalSubmissionLogs');
						//check if record exist with 'submitted status', then status will be 'replied'
						$getStatus = $this->DmiRenewalSubmissionLogs->find('all',array('fields'=>'id','conditions'=>array('customer_id'=>$customer_id,$grantDateCondition,'status'=>'submitted'),'order'=>'id desc'))->first();
						
						if (!empty($getStatus)) {
							$renStatus = 'replied';
						} else {
							$renStatus = 'saved';
						}
						
						$renewalSubmissionLogEntity = $this->DmiRenewalSubmissionLogs->newEntity(array(

							'customer_id'=>$customer_id,
							'form_type'=>$form_type,
							'last_validity'=>$validity_date,
							'remark'=>$this->request->getData('late_remark'),
							'created'=>date('Y-m-d H:i:s'),
							'modified'=>date('Y-m-d H:i:s'),
							'status'=>$renStatus
						));

						$this->DmiRenewalSubmissionLogs->save($renewalSubmissionLogEntity);
					}

					$get_payment_details = $this->Paymentdetails->saveApplicantPaymentDetails($this->request->getData(), $payment_table);

					if ($get_payment_details == true)
					{
						$message_theme = 'success';
						$message = $firm_type_text.' - Payment Section, '.$process_query.' successfully';
						$redirect_to = 'payment';
						$this->viewBuilder()->setVar('message', $message);
						$this->viewBuilder()->setVar('redirect_to', $redirect_to);
			
						$this->render('/element/message_boxes');
					}
				}

				$this->set('message_theme',$message_theme);
				$this->set('message',$message);
				$this->set('redirect_to',$redirect_to);
			}

		}
		
		
		
		// APPLICATION FINAL SUBMIT
		public function applicationFinalSubmit() {

			$this->viewBuilder()->setLayout('secondary_customer');

			$message = '';
			$message_theme = '';
			$redirect_to = '';
			
			$customer_id = $this->Customfunctions->sessionCustomerID();

			$application_type = $this->Session->read('application_type');
			$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
			$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
			$firm_type = $this->Customfunctions->firmType($customer_id);
			$firm_type_text = $this->Customfunctions->firmTypeText($customer_id);
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);

			if ($form_type=='F' && $ca_bevo_applicant=='yes') {
				$form_type='E';
			}

			//added on 11-11-2021 by Amol for application types forms
			if ($application_type == 4) {
				$form_type='CHM';
			} elseif ($application_type == 5) {
				$form_type='FDC';
			}
			
			$this->loadModel('DmiCommonScrutinyFlowDetails');

			$section_details = $this->DmiCommonScrutinyFlowDetails->currentSectionDetails($application_type,$office_type,$firm_type,$form_type,1);
			$allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);
			$all_section_status = $this->Customfunctions->formStatusValue($allSectionDetails,$customer_id);

			$final_submit_call_result =  $this->Customfunctions->applicationFinalSubmitCall($customer_id,$all_section_status);

			if ($final_submit_call_result == true) {
				$message = $firm_type_text.' - Final submitted successfully ';
				$message_theme = 'success';
				$redirect_to = '../applicationformspdfs/'.$section_details['forms_pdf'];
			} else {
				$message = $firm_type_text.' - All Sections not filled, Please fill all Section and then Final Submit ';
				$message_theme = 'failed';
				$redirect_to = '../application/application-for-certificate';
			}
		
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);
		}
		
		
		
		
		// Method for to Add tank details (By Amol 14/06/2017)
		
		// EDIT TBL ID
		public function editTblId($id) {

			$this->Session->write('edit_tbl_id',$id);
			$this->redirect('/application/application-for-certificate');
		}
		
		
		// DELETE TBL ID
		public function deleteTblId($id) {
			$record_id = $id;
			$this->loadModel('DmiAllTblsDetails');
			$tbl_delete_result = $this->DmiAllTblsDetails->deleteTblDetails($record_id);// call to custome function from model
			if ($tbl_delete_result == 1)
			{
				$this->redirect('/application/application-for-certificate');
			}
		}
		
		
		// EDIT CHEMIST ID
		public function editChemistId($id) {

			$this->Session->write('edit_chemist_id',$id);
			$this->redirect('/application/application-for-certificate');
		}
		
		
		// DELETE CHEMIST ID
		public function deleteChemistId($id) {
			$record_id = $id;
			$this->loadModel('DmiLaboratoryChemistsDetails');
			$tbl_delete_result = $this->DmiLaboratoryChemistsDetails->deleteChemistDetails($record_id);// call to custome function from model
			if ($tbl_delete_result == 1)
			{
				$this->redirect('/application/application-for-certificate');
			}
		}
		
		
		// EDIT REPLY
		public function editReply() {
			$this->autoRender = false;
			$id = $_POST['reply_max_id'];
			$this->Session->write('edit_reply_id',$id);
		}
		
		
		// DELETE REPLY
		public function deleteReply() {
			
			$this->autoRender = false;
			$id = $_POST['reply_max_id'];
			$model_name = $_POST['model_name'];
			$this->loadModel($model_name);
			$modelEntity = $this->$model_name->newEntity(array(
			
				'id'=>$id,
				'customer_reply'=>null,
				'customer_reply_date'=>null,
				'cr_comment_ul'=>null,
				'form_status'=>'referred_back'
			));
			
			$this->$model_name->save($modelEntity);
		}

		
		// DELETE COMMENT
		public function deleteComment($id,$section_id) {
			
			$this->autoRender = false;
			$customer_id = $this->Customfunctions->sessionCustomerID();
			$application_type = $this->Session->read('application_type');
		
			$office_type = $this->Customfunctions->getApplDistrictOffice($customer_id);
			$firm_type = $this->Customfunctions->firmType($customer_id);
			$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);

			$this->loadModel('DmiChemistComments');
			$this->loadModel('DmiCommonScrutinyFlowDetails');

			$allSectionDetails = $this->DmiCommonScrutinyFlowDetails->allSectionList($application_type,$office_type,$firm_type,$form_type);
			
			$section_id = $section_id - 1;
			$section_model = $allSectionDetails[$section_id]['section_model'];
			$this->loadModel($section_model);
			$newEntity = $this->DmiChemistComments->newEntity(array(
				'id'=>$id,
				'reply_by'=>'',
				'reply_to'=>'',
				'reply_comment'=>'',
				'reply_dt'=>''
			));
			
			$this->DmiChemistComments->save($newEntity);		
			
			$this->$section_model->updateAll(
				array('form_status' => "referred_back"),
				array('customer_id'=>$customer_id,'is_latest'=>'1')
			);
		
			$this->redirect('/application/application-for-certificate');				
		}


	}
?>
