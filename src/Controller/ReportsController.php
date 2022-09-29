<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Datasource\ConnectionManager;
use phpDocumentor\Reflection\Types\This;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use App\Controller\ApplicationformspdfsController;
use Cake\Core\Configure;



class ReportsController extends AppController {

	var $name = 'Reports';

	public function initialize(): void {

		parent::initialize();
		$this->loadComponent('Customfunctions');
		$this->loadComponent('Mastertablecontent');
		$this->loadComponent('Progressbar');
		$this->loadComponent('Createcaptcha');
		$this->loadComponent('Reportstatistics');
		$this->viewBuilder()->setLayout('admin_dashboard');
		$this->viewBuilder()->setHelpers(['Form','Html','Csv']);

		//LOAD ALL MODELS
		$this->loadModel('DmiUsers');
		$this->loadModel('UserRole');
		$this->loadModel('DmiUserRolesManagmentLogs');
		$this->loadModel('DmiUserRoles');
		$this->loadModel('DmiMoAllocationLogs');
		$this->loadModel('DmiGrantCertificatesPdfs');
		$this->loadModel('DmiIoAllocationLogs');
		$this->loadModel('DmiRoAllocationLogs');
		$this->loadModel('DmiStates');
		$this->loadModel('DmiDistricts');
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiRoOffices');
		$this->loadModel('DmiCustomers');
		$this->loadModel('DmiCertificateTypes');
		$this->loadModel('DmiApplicantPaymentDetails');
		$this->loadModel('DmiRenewalApplicantPaymentDetails');
		$this->loadModel('DmiSentEmailLogs');
		$this->loadModel('DmiApplicationEsignedStatuses');
		$this->loadModel('DmiVisitorCounts');
		$this->loadModel('DmiRenewalEsignedStatuses');
		$this->loadModel('DmiFrontStatistics');
		$this->loadModel('DmiAllTblsDetails');
		$this->loadModel('DmiCustomerLaboratoryDetails');
		// Added by Ankur Jangid
		$this->loadModel('DmiAllApplicationsCurrentPositions');
		$this->loadModel('DmiRenewalAllCurrentPositions');

		$this->Session = $this->getRequest()->getSession();
	}



	//Before Filter
	public function beforeFilter($event) {

		parent::beforeFilter($event);

		if ($this->Session->read('username') == null) {

			echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot');?>">Please Login</a><?php
			exit();
		}

		$user_role = $this->DmiUserRoles->find('all')->select(['view_reports'])->where(['user_email_id IS' => $this->Session->read('username')])->first(); 

		if ($this->Session->read('username') == null && $user_role['view_reports'] == 'yes') {

			echo "Sorry You are not authorized to view this page.."; ?><a href="<?php echo $this->request->getAttribute('webroot');?>">Please Login</a><?php
			exit();
		}

		/* set last action url for back button on reports, Done By Pravin Bhakare 06-03-2018  */
		$currentAction = $this->request->getParam('action');

		if ($this->Session->read('backAction') == null && $currentAction == "reportTypes") {

			$this->Session->write('backAction',$currentAction);

		} elseif ($this->Session->read('backAction') != null && $currentAction == "aqcmsStatistics") {

			$this->Session->write('backAction',$currentAction);

		} elseif ($this->Session->read('backAction') != null && $currentAction == "reportTypes") {

			$this->Session->write('backAction',$currentAction);
		}

		$this->set('backAction',$this->Session->read('backAction'));

	}




	// Report Types
	// Description : Manin Window of Reports Where Shows Reports Types Table
	// @Author : Pravin Bhakare
	// #Contributer : Ankur (M)
	// Date : ------

	public function reportTypes() {

		$this->Session->delete('search_office');
		$this->Session->delete('search_user_role');
		$this->Session->delete('search_user_id');
		$this->Session->delete('search_from_date');
		$this->Session->delete('search_to_date');
		$this->Session->delete('search_application_type_id');
		$this->Session->delete('application_approved_office');
		$this->Session->delete('ro_office_id');
		$this->Session->delete('mo_office_id');
		$this->Session->delete('io_office_id');
		$this->Session->delete('search_user_email_id');
		$this->Session->delete('pending_days');
		$this->Session->delete('state');
		$this->Session->delete('district');
		$this->Session->delete('application_type');
		$this->Session->delete('renewal_year');
		$this->Session->delete('application_id');
		$this->Session->delete('company_id');
		$this->Session->delete('ro_id');
	}





	// AQCMS Statistics
	// Description : Manin Window of Reports Where Shows Reports Types Table
	// @Author : Amol Choudhari / Pravin Bhakare
	// #Contributer : Ankur (M)
	// Date : -----

	public function aqcmsStatistics() {

		$report_name = 'AQCMS Statistics';
		$this->set('report_name',$report_name);

		$user_role = $this->DmiUserRoles->find('all')->where(['user_email_id IS' => $this->Session->read('username'), 'ro_inspection'=>'yes', 'view_reports IS NOT'=>'yes'])->first(); 
	
		$ro_office_list = $this->DmiRoOffices->find('all')->select(['id', 'ro_office'])->where(['delete_status IS NULL'])->combine('id', 'ro_office')->toArray(); 

		$statistics_counts = $this->DmiFrontStatistics->find('all')->where(['id IS' => 1])->toArray(); 

		$this->set('statistics_counts',$statistics_counts);

		$username = $this->Session->read('username');

		if (!empty($user_role)) {

			
			$rolist = $this->DmiUsers->find('all')->select(['id', 'f_name', 'l_name', 'email', 'created', 'role', 'posted_ro_office', 'status'])
													->join(['DmiUserRoles' => ['table' => 'dmi_user_roles', 'type' => 'INNER',
															'conditions' => ['DmiUserRoles.user_email_id = DmiUsers.email',
															'DmiUserRoles.ro_inspection' => 'yes', 'DmiUserRoles.user_email_id' => $username]],
															'DmiRoOffices' => ['table' => 'dmi_ro_offices', 'type' => 'INNER',
															'conditions' => ['DmiRoOffices.ro_email_id = DmiUsers.email', 'DmiUsers.status' => 'active']]])->toArray();
		} else {

				
			$rolist = $this->DmiUsers->find('all')->select(['id', 'f_name', 'l_name', 'email', 'created', 'role', 'posted_ro_office', 'status'])
													->join(['DmiUserRoles' => ['table' => 'dmi_user_roles', 'type' => 'INNER',
															'conditions' => ['DmiUserRoles.user_email_id = DmiUsers.email', 'DmiUserRoles.ro_inspection' => 'yes']],
															'DmiRoOffices' => ['table' => 'dmi_ro_offices', 'type' => 'INNER',
															'conditions' => ['DmiRoOffices.ro_email_id = DmiUsers.email', 'DmiUsers.status' => 'active']]])->toArray();
		}

		$current_ro_id = '';

		foreach ($rolist as $each_user) {

			if (!empty($user_role)) {

				$current_ro_id = $each_user['id'];
			}

			$ro_name_list[$each_user['id']] = $each_user['f_name']." ".$each_user['l_name']."  (".base64_decode($each_user['email']).") - (".$ro_office_list[$each_user['posted_ro_office']].")"; //for email encoding
		}

		asort($ro_name_list);
		$this->set('ro_name_list',$ro_name_list);

		$aqcms_statistics_report = null;

		if (null !== ($this->request->getData('search'))) {

			$aqcms_statistics_report = 1;

			$this->Session->delete('ro_id');
			$ro_id = $this->request->getData('ro_id');
			$from_date = $this->request->getData('from_date');
			$to_date = $this->request->getData('to_date');
			$this->Session->write('ro_id',$ro_id);

			if (!empty($ro_id)) {

				
				$rolist = $this->DmiRoOffices->find('all')->select(['id'])->join(['table' => 'dmi_users', 'alias' => 'users', 'type' => 'INNER','conditions' => ['users.email = dmirooffices.ro_email_id', 'users.id' => $ro_id]])->combine('id', 'id')->toArray();

				$district_list = $this->DmiDistricts->find('all')->select(['id'])->where(['ro_id IN' => $rolist])->combine('id', 'id')->toArray(); 

				$roOfficeShortCode = $this->DmiRoOffices->find('all')->select(['id', 'short_code'])->where(['id IN' => $rolist])->combine('id', 'short_code')->toArray(); 

				foreach ($roOfficeShortCode as $eachCode) {

					$OfficeShortCode[] = ['customer_id LIKE' => '%'.$eachCode.'%'];
				}
			}

			//Check Between Dates
			if (!empty($from_date) && !empty($to_date) && !empty($ro_id)) {

				$searchConditions = ['district IN' => $district_list, 'date(created) BETWEEN :start AND :end'];

			} elseif (empty($from_date) && empty($to_date) && !empty($ro_id)) {

				$searchConditions = ['district IN' => $district_list];

			} elseif (!empty($from_date) && !empty($to_date) && empty($ro_id)) {

				$searchConditions = ['date(created) BETWEEN :start AND :end'];

			} elseif (empty($from_date) && empty($to_date) && empty($ro_id)) {

				$searchConditions = array();
			}


			//Check between dates
			if (!empty($from_date) && !empty($to_date)) {

				$total_primary_user = $this->DmiCustomers->find('list')->where($searchConditions)->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')->toArray(); 
				$query_tfr = $this->DmiFirms->find('all');

				$total_firm_register = $query_tfr->select(['certification_type', 'count' => $query_tfr->func()->count('certification_type')])
																					->where($searchConditions)
																					->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')
																					->group(['certification_type'])
																					->order(['certification_type' => 'ASC'])
																					->toArray(); 

				$total_delete_firms = $this->DmiFirms->find('all')->where($searchConditions)->where(['delete_status IS NOT' => NULL])
																	->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')
																	->toArray(); 	 

				$list4RenewalDueCheck = $this->DmiFirms->find('all')->where($searchConditions)
														->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')
														->combine('id', 'customer_id')
														->toArray();	 
			} else {

				$total_primary_user = $this->DmiCustomers->find('list')->where($searchConditions)->toArray(); 
				$query_tfr = $this->DmiFirms->find('all');

				$total_firm_register = $query_tfr->select(['certification_type', 'count' => $query_tfr->func()->count('certification_type')])
													->where($searchConditions)
													->group(['certification_type'])
													->order(['certification_type' => 'ASC'])
													->toArray(); 

				$total_delete_firms = $this->DmiFirms->find('all')->where($searchConditions)->where(['delete_status IS NOT' => NULL])->toArray();  

				$list4RenewalDueCheck = $this->DmiFirms->find('all')->where($searchConditions)->combine('id', 'customer_id')->toArray(); 
			}

			$caRenewalDue = 0; 	$printingRenewalDue = 0; $labRenewalDue = 0;

			foreach ($list4RenewalDueCheck as $each_application) {

				$renewalDue = $this->Customfunctions->checkApplicantValidForRenewal($each_application);

				if ($renewalDue == 'yes') {
					$split_customer_id = explode('/',$each_application);

					if ($split_customer_id[1] == 1) {

						$caRenewalDue = $caRenewalDue+1;

					} elseif ($split_customer_id[1] == 2) {

						$printingRenewalDue = $printingRenewalDue+1;

					} elseif ($split_customer_id[1] == 3) {

						$labRenewalDue = $labRenewalDue+1;
					}
				}
			}


			$applications_current_positions_tables =  ['DmiFinalSubmits' => 'DmiAllApplicationsCurrentPositions', 'DmiRenewalFinalSubmits' => 'DmiRenewalAllCurrentPositions'];

			$pendingCountForMo = 0;
			$pendingCountForIo = 0;
			$pendingCountForHo = 0;
			$inprogress_app_with_ro = array();

			if (!empty($from_date) && !empty($to_date) && !empty($ro_id)) {

				$searchPendingConditions = ['OR' => $OfficeShortCode, 'date(modified) BETWEEN :start AND :end'];

			} elseif (empty($from_date) && empty($to_date) && !empty($ro_id)) {

				$searchPendingConditions = ['OR'=>$OfficeShortCode];

			} elseif (!empty($from_date) && !empty($to_date) && empty($ro_id)) {

				$searchPendingConditions = ['date(modified) BETWEEN :start AND :end'];

			} elseif (empty($from_date) && empty($to_date) && empty($ro_id)) {

				$searchPendingConditions = array();
			}


			$application_processed_type = ['new_app_processed','renewal_app_processed','backlog_app_processed'];


			foreach ($application_processed_type as $each) {

				$application_processed[] = $this->Reportstatistics->$each($searchPendingConditions);
			}


			if (!empty($from_date) && !empty($to_date)) {

				foreach ($applications_current_positions_tables as $each_table) {

					$key = array_search ($each_table, $applications_current_positions_tables);

					//For Progress with MO
					$inprogress_with_mo = $this->$each_table->find('all')->select(['id', 'customer_id'])
															->where($searchPendingConditions)->where(['current_level' => 'level_1'])
															->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')
															->combine('id', 'customer_id')->toArray(); 
					$pendingCountForMo = $pendingCountForMo + count($inprogress_with_mo);

					//For Progress with IO
					$inprogress_with_io = $this->$each_table->find('all')->select(['id', 'customer_id'])
															->where($searchPendingConditions)->where(['current_level' => 'level_2'])
															->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')
															->combine('id', 'customer_id')->toArray(); 
					$pendingCountForIo = $pendingCountForIo + count($inprogress_with_io);


					//For Progress with HO
					$inprogress_with_ho = $this->$each_table->find('all')->select(['id', 'customer_id'])
															->where($searchPendingConditions)->where(['current_level' => 'level_4'])
															->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')
															->combine('id', 'customer_id')->toArray(); 
					$pendingCountForHo = $pendingCountForHo + count($inprogress_with_ho);


					//For Progress with RO
					$inprogress_with_ro = $this->$each_table->find('all')->select(['id', 'customer_id'])
															->where($searchPendingConditions)->where(['current_level' => 'level_3'])
															->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')
															->combine('id', 'customer_id')->toArray(); 

					foreach ($inprogress_with_ro as $each_record ) {

						$result_status = $this->$key->find('all')->where(['customer_id' => $each_record, 'status' => 'approved', 'current_level' => 'level_3'])->toArray(); 


						if (empty($result_status)) {

							$inprogress_app_with_ro[] = $each_record;
						}
					}
				}


				$applicationEsigned = $this->DmiApplicationEsignedStatuses->find('all')->where($searchPendingConditions)->where(['application_esigned' => 'yes'])
					->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')->toArray(); 

				$inspectionReportEsigned = $this->DmiApplicationEsignedStatuses->find('all')->where($searchPendingConditions)->where(['report_esigned' => 'yes'])
					->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')->toArray(); 

				$certificateEsigned = $this->DmiApplicationEsignedStatuses->find('all')->where($searchPendingConditions)->where(['certificate_esigned' => 'yes'])
					->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')->toArray(); 

				$renewalApplicationEsigned = $this->DmiRenewalEsignedStatuses->find('all')->where($searchPendingConditions)->where(['application_esigned' => 'yes'])
					->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')->toArray(); 

				$renewalInspectionReportEsigned = $this->DmiRenewalEsignedStatuses->find('all')->where($searchPendingConditions)->where(['report_esigned' => 'yes'])
					->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')->toArray(); 

				$renewalCertificateEsigned = $this->DmiRenewalEsignedStatuses->find('all')->where($searchPendingConditions)->where(['certificate_esigned' => 'yes'])
					->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')->toArray(); 

				$newApplicationrevenue_Query = $this->DmiApplicantPaymentDetails->find('all')->where($searchPendingConditions)
					->where(['payment_confirmation' => 'confirmed'])->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')->sumOf('amount_paid'); 
				$newApplicationrevenue = ['sum' => $newApplicationrevenue_Query];

				$renewalApplicationrevenue_Query =$this->DmiRenewalApplicantPaymentDetails->find('all')->where($searchPendingConditions)
					->where(['payment_confirmation' => 'confirmed'])->bind(':start', $from_date, 'date')->bind(':end', $to_date, 'date')->sumOf('amount_paid');  
					$renewalApplicationrevenue = ['sum' => $renewalApplicationrevenue_Query];

			} else {

				foreach ($applications_current_positions_tables as $each_table) {

					$key = array_search ($each_table, $applications_current_positions_tables);

					$inprogress_with_mo = $this->$each_table->find('all')->select(['id', 'customer_id'])->where($searchPendingConditions)->where(['current_level' => 'level_1'])
						->combine('id', 'customer_id')->toArray();	 
					$pendingCountForMo = $pendingCountForMo + count($inprogress_with_mo);

					$inprogress_with_io = $this->$each_table->find('all')->select(['id', 'customer_id'])->where($searchPendingConditions)->where(['current_level' => 'level_2'])
						->combine('id', 'customer_id')->toArray(); 
					$pendingCountForIo = $pendingCountForIo + count($inprogress_with_io);

					$inprogress_with_ho = $this->$each_table->find('all')->select(['id', 'customer_id'])->where($searchPendingConditions)->where(['current_level' => 'level_4'])
						->combine('id', 'customer_id')->toArray(); 
					$pendingCountForHo = $pendingCountForHo + count($inprogress_with_ho);

					$inprogress_with_ro = $this->$each_table->find('all')->select(['id', 'customer_id'])->where($searchPendingConditions)->where(['current_level' => 'level_3'])
						->combine('id', 'customer_id')->toArray(); 

					foreach ($inprogress_with_ro as $each_record ) {
						$result_status = $this->$key->find('all')->where(['customer_id' => $each_record, 'status' => 'approved', 'current_level' => 'level_3'])->toArray(); 

						if (empty($result_status)) {
							$inprogress_app_with_ro[] = $each_record;
						}
					}
				}

				$applicationEsigned = $this->DmiApplicationEsignedStatuses->find('all')->where($searchPendingConditions)->where(['application_esigned' => 'yes'])->toArray(); 

				$inspectionReportEsigned = $this->DmiApplicationEsignedStatuses->find('all')->where($searchPendingConditions)->where(['report_esigned' => 'yes'])->toArray(); 

				$certificateEsigned = $this->DmiApplicationEsignedStatuses->find('all')->where($searchPendingConditions)->where(['certificate_esigned' => 'yes'])->toArray(); 

				$renewalApplicationEsigned = $this->DmiRenewalEsignedStatuses->find('all')->where($searchPendingConditions)->where(['application_esigned' => 'yes'])->toArray(); 

				$renewalInspectionReportEsigned = $this->DmiRenewalEsignedStatuses->find('all')->where($searchPendingConditions)->where(['report_esigned' => 'yes'])->toArray(); 

				$renewalCertificateEsigned = $this->DmiRenewalEsignedStatuses->find('all')->where($searchPendingConditions)->where(['certificate_esigned' => 'yes'])->toArray(); 

				$newApplicationrevenue_Query = $this->DmiApplicantPaymentDetails->find('all')->where($searchPendingConditions)
					->where(['payment_confirmation' => 'confirmed'])->sumOf('amount_paid'); $newApplicationrevenue = ['sum' => $newApplicationrevenue_Query]; 

				$renewalApplicationrevenue_Query =$this->DmiRenewalApplicantPaymentDetails->find('all')->where($searchPendingConditions)
					->where(['payment_confirmation' => 'confirmed'])->sumOf('amount_paid'); $renewalApplicationrevenue = ['sum' => $renewalApplicationrevenue_Query]; 
			}



			$totalVisitor =$this->DmiVisitorCounts->find('all')->select(['visitor'])->order(['id' => 'DESC'])->first(); 

			$FrontStatisticsController = new FrontstatisticsController();
			$newrevenue = $FrontStatisticsController->thousandsCurrencyFormat($newApplicationrevenue['sum']);
			$renewalrevenue = $FrontStatisticsController->thousandsCurrencyFormat($renewalApplicationrevenue['sum']);
			$totalrevenue = $FrontStatisticsController->thousandsCurrencyFormat($newApplicationrevenue['sum']+$renewalApplicationrevenue['sum']);

			$this->Session->write('total_primary_user',$total_primary_user);
			$this->Session->write('total_firm_register',$total_firm_register);
			$this->Session->write('total_delete_firms',$total_delete_firms);
			$this->Session->write('application_processed',$application_processed);
			$this->Session->write('pendingCountForMo',$pendingCountForMo);
			$this->Session->write('pendingCountForIo',$pendingCountForIo);
			$this->Session->write('pendingCountForHo',$pendingCountForHo);
			$this->Session->write('pendingCountForRo',count($inprogress_app_with_ro));
			$this->Session->write('applicationEsigned',count($applicationEsigned));
			$this->Session->write('inspectionReportEsigned',count($inspectionReportEsigned));
			$this->Session->write('certificateEsigned',count($certificateEsigned));
			$this->Session->write('renewalApplicationEsigned',count($renewalApplicationEsigned));
			$this->Session->write('renewalInspectionReportEsigned',count($renewalInspectionReportEsigned));
			$this->Session->write('renewalCertificateEsigned',count($renewalCertificateEsigned));
			$this->Session->write('newApplicationrevenue',$newrevenue);
			$this->Session->write('renewalApplicationrevenue',$renewalrevenue);
			$this->Session->write('totalrevenue',$totalrevenue);
			$this->Session->write('totalVisitor',$totalVisitor['visitor']);
			$this->Session->write('caRenewalDue',$caRenewalDue);
			$this->Session->write('printingRenewalDue',$printingRenewalDue);
			$this->Session->write('labRenewalDue',$labRenewalDue);

			$this->set('total_primary_user',$total_primary_user);
			$this->set('total_firm_register',$total_firm_register);
			$this->set('total_delete_firms',$total_delete_firms);
			$this->set('application_processed',$application_processed);
			$this->set('pendingCountForMo',$pendingCountForMo);
			$this->set('pendingCountForIo',$pendingCountForIo);
			$this->set('pendingCountForHo',$pendingCountForHo);
			$this->set('pendingCountForRo',count($inprogress_app_with_ro));
			$this->set('applicationEsigned',count($applicationEsigned));
			$this->set('inspectionReportEsigned',count($inspectionReportEsigned));
			$this->set('certificateEsigned',count($certificateEsigned));
			$this->set('renewalApplicationEsigned',count($renewalApplicationEsigned));
			$this->set('renewalInspectionReportEsigned',count($renewalInspectionReportEsigned));
			$this->set('renewalCertificateEsigned',count($renewalCertificateEsigned));
			$this->set('newApplicationrevenue',$newrevenue);
			$this->set('renewalApplicationrevenue',$renewalrevenue);
			$this->set('totalrevenue',$totalrevenue);
			$this->set('totalVisitor',$totalVisitor['visitor']);
			$this->set('caRenewalDue',$caRenewalDue);
			$this->set('printingRenewalDue',$printingRenewalDue);
			$this->set('labRenewalDue',$labRenewalDue);

		} else {

			if (!empty($user_role)) {
				$ro_id = $current_ro_id;
				$this->Session->write('ro_id', $ro_id);
			} else {
				$ro_id = null;
			}

			$from_date = null;
			$to_date = null;
		}

		$this->set('ro_id',$ro_id);
		$this->set('aqcms_statistics_report',$aqcms_statistics_report);
		$this->set('from_date',$from_date);
		$this->set('to_date',$to_date);

		if (null !== $this->request->getData('download_report')) {

			$this->viewBuilder()->setLayout('pdf_layout');

			$this->set('total_primary_user',$this->Session->read('total_primary_user'));
			$this->set('total_firm_register',$this->Session->read('total_firm_register'));
			$this->set('total_delete_firms',$this->Session->read('total_delete_firms'));
			$this->set('application_processed',$this->Session->read('application_processed'));
			$this->set('pendingCountForMo',$this->Session->read('pendingCountForMo'));
			$this->set('pendingCountForIo',$this->Session->read('pendingCountForIo'));
			$this->set('pendingCountForHo',$this->Session->read('pendingCountForHo'));
			$this->set('pendingCountForRo',$this->Session->read('pendingCountForRo'));
			$this->set('applicationEsigned',$this->Session->read('applicationEsigned'));
			$this->set('inspectionReportEsigned',$this->Session->read('inspectionReportEsigned'));
			$this->set('certificateEsigned',$this->Session->read('certificateEsigned'));
			$this->set('renewalApplicationEsigned',$this->Session->read('renewalApplicationEsigned'));
			$this->set('renewalInspectionReportEsigned',$this->Session->read('renewalInspectionReportEsigned'));
			$this->set('renewalCertificateEsigned',$this->Session->read('renewalCertificateEsigned'));
			$this->set('newApplicationrevenue',$this->Session->read('newApplicationrevenue'));
			$this->set('renewalApplicationrevenue',$this->Session->read('renewalApplicationrevenue'));
			$this->set('totalrevenue',$this->Session->read('totalrevenue'));
			$this->set('totalVisitor',$this->Session->read('totalVisitor'));
			$this->set('caRenewalDue',$this->Session->read('caRenewalDue'));
			$this->set('printingRenewalDue',$this->Session->read('printingRenewalDue'));
			$this->set('labRenewalDue',$this->Session->read('labRenewalDue'));


			$this->autoRender = false;

			if (empty($aqcms_statistics_report)) {
				$pdfHtml = $this->render('/element/download_report_excel_format/download_report_aqcms_statistics_without_search');
			} else {
				$pdfHtml = $this->render('/element/download_report_excel_format/download_report_aqcms_statistics_with_search');
			}

			$pdf = new ApplicationformspdfsController();
			// $pdfName = 'aqcms_statistics.pdf'; //name of the pdf file
			// $this->Mpdf->init();

			// $stylesheet = file_get_contents('css/forms-style.css');
			// $this->Mpdf->ob_clean();

			// $this->Mpdf->SetDisplayMode('fullpage');
			// $this->Mpdf->watermark_font = 'DejaVuSansCondensed';
			$pdf->callTcpdf($pdfHtml, 'D', '', '');
			// $pdf->WriteHTML($pdfHtml);

			// $pdf->Output($pdfName, 'D');
		}

		if (!empty($user_role)) {
			$this->view = 'ro_aqcms_statistics';
		} else {
			$this->view = 'aqcms_statistics';
		}
	
	}




	// User Roles Logs Report
	// Description : Start User Roles Logs History Reports Section
	// @Author : Pravin Bhakare
	// #Contributer : Ankur (M)
	// Date : ------

	public function userRolesLogsReport() {

		$report_name = 'User Roles Logs History Report';
		$this->set('report_name',$report_name);

		$user_name_details = $this->userNameList();
		$this->set('user_name_details',$user_name_details);

		// Change on 1/11/2018 : Add new user roles such as MO/SMO(SO), Inspection Officer(SO), MO/SMO(SMD), Inspection Officer(SMD) in user_roles array - By Pravin Bhakare
		$user_roles = ['add_user'=>'Add User','page_draft'=>'Page (Draft only)','page_publish'=>'Page Publish','menus'=>'Menus','file_upload'=>'Upload Files',
								'mo_smo_inspection'=>'MO/SMO','io_inspection'=>'Inspection Officer','allocation_mo_smo'=>'Allocate to MO/SMO',
								'allocation_io'=>'Allocate to IO','reallocation'=>'Re-Allocate','form_verification_home'=>'Form Scrutiny Home',
								'allocation_home'=>'Allocation Home','ro_inspection'=>'RO/SO In-Charge','set_roles'=>'Set User Roles','allocation_dy_ama'=>'Forward to Dy. AMA',
								'allocation_ho_mo_smo'=>'Allocate to HO MO/SMO','allocation_jt_ama'=>'Forward to Jt. AMA','allocation_ama'=>'Forward to AMA',
								'dy_ama'=>'Dy. AMA','ho_mo_smo'=>'HO MO/SMO','jt_ama'=>'Jt. AMA','ama'=>'AMA','masters'=>'Masters','super_admin'=>'Super Admin',
								'renewal_verification'=>'Renewal Scrutiny','renewal_allocation'=>'Renewal Allocation','view_reports'=>'View Reports','pao'=>'PAO/DDO',
								'once_update_permission'=>'Aadhar Update Permission','old_appln_data_entry'=>'Old Applications Data Entry','so_inspection'=>'SO In-Charge',
								'smd_inspection'=>'SMD In-Charge','feedbacks'=>'Feedbacks','unlock_user'=>'Unlock User'];

		asort($user_roles); // Change on 9/11/2018, Sorting array by ascending order - By Pravin Bhakare
		$this->set('user_roles',$user_roles);

		//added 'office_type'=>'RO' condition on 27-07-2018
		$ro_office = $this->DmiRoOffices->find('all')->select(['id', 'ro_office'])->where(['office_type' => 'RO','delete_status IS NULL'])->order(['ro_office ASC'])->combine('id', 'ro_office')->toArray(); 
		$this->set('ro_office',$ro_office);

		$search_office = $this->Session->read('search_office');
		$search_user_role = $this->Session->read('search_user_role');
		$search_user_id = $this->Session->read('search_user_id');
		$search_from_date = $this->Session->read('search_from_date');
		$search_to_date = $this->Session->read('search_to_date');
		$this->set('search_office',$search_office);
		$this->set('search_user_role',$search_user_role);
		$this->set('search_user_id',$search_user_id);
		$this->set('search_from_date',$search_from_date);
		$this->set('search_to_date',$search_to_date);

		$download_report = 'no'; // Set default value for "Download Report as Excel" click event (Done by Pravin 13/3/2018)

		//Pass the entry for "Search" or "Download Report as Excel" button click event (Done by Pravin 13/3/2018)
		if (null !== ($this->request->getData('search_logs')) || null !== ($this->request->getData('download_report'))) {

			$table = 'DmiRooffices';
			$post_input_request = $this->request->getData('office');

			if (!empty($post_input_request)) {
				$search_office = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request); //calling library function
			} else {
				$search_office = '';
			}

			$search_user_role = $this->request->getData('user_roles');
			$search_user_id = $this->request->getData('user_id');
			$search_from_date = $this->request->getData('from_date');
			$search_from_date = $this->Customfunctions->dateFormatCheck($search_from_date);
			$search_to_date = $this->request->getData('to_date');
			$search_to_date = $this->Customfunctions->dateFormatCheck($search_to_date);
			$this->date_comparison($search_from_date,$search_to_date);

			// Change on 1/11/2018 : For download excel report, Take search filter field value from session variables instend of POST variable - By Pravin Bhakare
			if (!empty($this->request->getData('download_report'))) {

				$table = 'DmiRoOffices';
				$post_input_request = $this->Session->read('search_office');
				if (!empty($post_input_request)) {
					$search_office = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request); // calling library function
				} else {
					$search_office = '';
				}

				$search_user_role = $this->Session->read('search_user_role');
				$search_user_id = $this->Session->read('search_user_id');
				$search_from_date = $this->Session->read('search_from_date');
				$search_from_date = $this->Customfunctions->dateFormatCheck($search_from_date);
				$search_to_date = $this->Session->read('search_to_date');
				$search_to_date = $this->Customfunctions->dateFormatCheck($search_to_date);
				$this->date_comparison($search_from_date,$search_to_date);
			}

			$this->Session->delete('search_office');
			$this->Session->delete('search_user_role');
			$this->Session->delete('search_user_id');
			$this->Session->delete('search_from_date');
			$this->Session->delete('search_to_date');
			$this->Session->delete('user_roles_email');

			//store the value of fields in session variable and used this value in else condition of search_logs button
			$this->Session->write('search_office',$search_office);
			$this->Session->write('search_user_role',$search_user_role);
			$this->Session->write('search_user_id',$search_user_id);
			$this->Session->write('search_from_date',$search_from_date);
			$this->Session->write('search_to_date',$search_to_date);

			$this->set('search_office',$search_office);
			$this->set('search_user_role',$search_user_role);
			$this->set('search_user_id',$search_user_id);
			$this->set('search_from_date',$search_from_date);
			$this->set('search_to_date',$search_to_date);

			if ($search_office == '' || $search_user_role =='' || $search_user_id =='' || $search_from_date =='' || $search_to_date =='') {

				$user_role_logs_details = null;
				if (!empty($search_user_id)) {
					$search_user_id = $this->search_user_id($user_name_details, $search_user_id);
				}

				if (!empty($search_office)) {

					$user_role_details = $this->DmiUsers->find('all')->where(['posted_ro_office' => $search_office])->toArray(); 
					$user_roles_email = $this->DmiUsers->find('all')->select(['email'])->where(['posted_ro_office' => $search_office])->extract('email')->toArray(); 
					// $i=0;
					// foreach ($user_role_details as $user_user) {
					// 	$user_roles_email[$i] = $user_user['email'];
					// 	$i = $i+1;
					// }

					if (!empty($user_roles_email)) {
						$this->Session->write('user_roles_email',$user_roles_email);
						$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where(['to_user IN' => $user_roles_email])->order(['id' => 'DESC'])->toArray(); 
					} else {
						$user_role_logs_history_details = null;
					}

				} elseif ($search_office == null && $search_user_role == null && $search_user_id != null && $search_from_date == null && $search_to_date == null) {

					$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where(['to_user' => $search_user_id])->order(['id' => 'DESC'])->toArray(); 
				
				} elseif ($search_office ==null && $search_user_role==null && $search_user_id==null && $search_from_date!=null && $search_to_date!=null) {

					$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where(['date(created) BETWEEN :start AND :end'])
						->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->order(['id' => 'DESC'])->toArray(); 
				} elseif ($search_office ==null && $search_user_role==null && $search_user_id!=null && $search_from_date!=null && $search_to_date!=null) {

					$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where(['to_user' => $search_user_id,'date(created) BETWEEN :start AND :end'])
						->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->order(['id' => 'DESC'])->toArray(); 
				} else {

					$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->order(['id' => 'DESC'])->toArray(); 
				}

				$user_role_logs_details = $this->userLogsSearchCondition($user_role_logs_history_details,$search_office,$search_user_role,$search_user_id,$search_from_date,$search_to_date);
				$this->userLogsSearchResult($user_role_logs_details,$download_report);
			
			} else {

				$user_role_logs_details = null;

				if (!empty($search_user_id)) {
					$search_user_id = $this->search_user_id($user_name_details,$search_user_id);
				}

				$user_role_details = $this->DmiUsers->find('all')->where(['posted_ro_office'=>$search_office])->toArray(); 
				$user_roles_email = $this->DmiUsers->find('all')->select(['email'])->where(['posted_ro_office'=>$search_office])->extract('email')->toArray(); 
				// $i=0;
				// foreach ($user_role_details as $user_user) {
				// 	$user_roles_email[$i] = $user_user['email'];
				// 	$i = $i+1;
				// }

				$this->Session->write('user_roles_email',$user_roles_email);

				$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where(['to_user IN'=>$user_roles_email])->order(['id' => 'DESC'])->toArray(); 
				$user_role_logs_details = $this->userLogsSearchCondition($user_role_logs_history_details,$search_office,$search_user_role,$search_user_id,$search_from_date,$search_to_date);
				$this->userLogsSearchResult($user_role_logs_details,$download_report);
			}

			// Check not empty "Download Report as Excel" button Request, if condition TRUE then set value "yes" for "Download Report as Excel" click event (Done By pravin 14/3/2018)
			// and pass this value to "user_logs_search_result" function
			if (!empty($this->request->getData('download_report'))) {
				$download_report = 'yes';
				$this->userLogsSearchResult($user_role_logs_details,$download_report);
			}
			
		} else {

			$user_roles_email = $this->Session->read('user_roles_email');
			$user_role_logs_details = null;

			if (!empty($search_user_id)) {
				$search_user_id = $this->search_user_id($user_name_details,$search_user_id);
			}

			if (!empty($search_office)) {

				$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where(['to_user IN' => $user_roles_email])->order(['id' => 'DESC'])->toArray(); 

			} elseif ($search_office ==null && $search_user_role==null && $search_user_id!=null && $search_from_date==null && $search_to_date==null) {

				$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where(['to_user' => $search_user_id])->order(['id' => 'DESC'])->toArray(); 

			} elseif ($search_office ==null && $search_user_role==null && $search_user_id==null && $search_from_date!=null && $search_to_date!=null) {

				$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where(['date(created) BETWEEN :start AND :end'])
					->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->order(['id' => 'DESC'])->toArray(); 

			} elseif ($search_office ==null && $search_user_role==null && $search_user_id!=null && $search_from_date!=null && $search_to_date!=null) {

				$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where(['to_user' => $search_user_id])
					->where(['date(created) BETWEEN :start AND :end'])->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')
					->order(['id' => 'DESC'])->toArray(); 
					
			} else {
				// limit set to 100 will show only latest 100 results
				$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->order(['id' => 'DESC'])->limit(['100'])->toArray(); 
			}

			$user_role_logs_details = $this->userLogsSearchCondition($user_role_logs_history_details,$search_office,$search_user_role,$search_user_id,$search_from_date,$search_to_date);
			$this->userLogsSearchResult($user_role_logs_details,$download_report);
		}
	
	}



	// User Logs Search Result
	// Description : Function For user roles logs history reports result
	// @Author : Pravin Bhakare
	// #Contributer : Ankur (M)
	// Date : ------

	public function userLogsSearchResult($user_role_logs_details,$download_report) {

		if (!empty($user_role_logs_details)) {

			$i=0;

			foreach ($user_role_logs_details as $user_name) {
				$user_role_logs_ids[$i] = $user_name['id'];
				$i = $i+1;
			}

			if (!empty($user_role_logs_ids)) {
				$download_condition = ['id IN' => $user_role_logs_ids];
			} else {
				$download_condition = ['id IS' => ''];
			}

			// Fetch the all data that required for creating the downloading report as execel (Done By pravin 13/3/2018)
			if ($download_report == 'yes') {
				$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where($download_condition)->order(['id' => 'DESC'])->toArray(); 
				$this->downloadReportUserRolesLogsHistory($user_role_logs_history_details);
				// $this->csv($user_role_logs_history_details); // excel added by ankur
			}

			$user_role_logs_history_details = $this->DmiUserRolesManagmentLogs->find('all')->where(['id IN' => $user_role_logs_ids])->order(['id' => 'DESC'])->toArray(); 

			$i=0;
			foreach ($user_role_logs_history_details as $user_name) {

				$user_email_id = $user_name['to_user']; 
				$user_details = $this->DmiUsers->find('all')->select(['f_name', 'l_name', 'email', 'posted_ro_office'])->where(['email' => $user_email_id])->first(); 

				if (!empty($user_details)) {
					$user_office_details = $this->DmiRoOffices->find('all')->select(['ro_office'])->where(['id IS' => $user_details['posted_ro_office']])->first(); 
				}

				if (!empty($user_office_details)) {
					$user_office[$i] = $user_office_details['ro_office']; 
				}
				else {
					$user_office[$i] = '---';
				}

				if (!empty($user_details)) {
					$user_full_name = $user_details['f_name'].' '.$user_details['l_name'];
					$user_name_detail[$i] = $user_full_name.' ('.base64_decode($user_details['email']).')';//for email encoding
				}
				else {
					$user_name_detail[$i] = '---';
				}
				$i = $i+1;
			}

			$user_roles_history_data = $this->DmiUserRolesManagmentLogs->showUserRolesHistory($user_role_logs_history_details);
			$user_roles_name_list = $user_roles_history_data[0][0];
			$user_roles_name_view_list = $user_roles_history_data[0][1]; // added by Ankur
			$remove_user_roles_name_list = $user_roles_history_data[1][0];
			$remove_user_roles_name_view_list = $user_roles_history_data[1][1]; // added by Ankur
			$add_user_roles_name_list = $user_roles_history_data[2][0];
			$add_user_roles_name_view_list = $user_roles_history_data[2][1]; // added by Ankur

			$this->set('user_roles_name_list',$user_roles_name_list);
			$this->set('user_roles_name_view_list',$user_roles_name_view_list); // added by Ankur
			$this->set('remove_user_roles_name_list',$remove_user_roles_name_list);
			$this->set('remove_user_roles_name_view_list',$remove_user_roles_name_view_list); // added by Ankur
			$this->set('add_user_roles_name_list',$add_user_roles_name_list);
			$this->set('add_user_roles_name_view_list',$add_user_roles_name_view_list); // added by Ankur
			$this->set('user_office',$user_office);
			$this->set('user_name_detail',$user_name_detail);
		} else {
			$user_role_logs_history_details = array();
		}
		$this->set('user_role_logs_history_details',$user_role_logs_history_details);
	}




	// Download Report User Roles Logs History
	// Description : This function create excel format file for downloading the report for user roles logs history
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 13/3/2018

	public function downloadReportUserRolesLogsHistory($user_role_logs_history_details) {

		$this->viewBuilder()->setLayout('downloadpdf');

		$i=0;
		foreach ($user_role_logs_history_details as $user_name) {
			$user_email_id = $user_name['to_user']; 
			$user_details = $this->DmiUsers->find('all')->where(['email'=>$user_email_id])->first(); 

			if (!empty($user_details)) {
				$user_office_details = $this->DmiRoOffices->find('all')->select(['ro_office'])->where(['id IS'=>$user_details['posted_ro_office']])->first(); 
			}

			if (!empty($user_office_details)) {
				$user_office[$i] = $user_office_details['ro_office']; 
			}
			else {
				$user_office[$i] = '---';
			}

			if (!empty($user_details)) {
				$user_full_name = $user_details['f_name'].' '.$user_details['l_name'];
				$user_name_detail[$i] = $user_full_name.' ('.base64_decode($user_details['email']).')';//for email encoding
			}
			else {
				$user_name_detail[$i] = '---';
			}
			$i = $i+1;
		}

		$user_roles_history_data = $this->DmiUserRolesManagmentLogs->showUserRolesHistory($user_role_logs_history_details);
		$user_roles_name_list = $user_roles_history_data[0][0];
		$remove_user_roles_name_list = $user_roles_history_data[1][0];
		$add_user_roles_name_list = $user_roles_history_data[2][0];

		$this->set('orders',$user_role_logs_history_details);
		$this->set('user_roles_name_list',$user_roles_name_list);
		$this->set('remove_user_roles_name_list',$remove_user_roles_name_list);
		$this->set('add_user_roles_name_list',$add_user_roles_name_list);
		$this->set('user_office',$user_office);
		$this->set('user_name_detail',$user_name_detail);

		$this->layout = null;
		$this->autoLayout = false;
		Configure::write('debug', '0');
		$this -> render('/element/download_report_excel_format/download_report_user_roles_logs_history');
	}


	

	// User Logs Search Condition
	// Description : Function For user roles logs history reports search conditions
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 11-09-2017

	public function userLogsSearchCondition($user_role_logs_history_details,$search_office,$search_user_role,$search_user_id,$search_from_date,$search_to_date) {

		if (!empty($user_role_logs_history_details)) {

			if ($search_user_role != null && $search_user_id != null && $search_from_date != null && $search_to_date != null) {
				$user_role_logs_details = null;

				$j=0;
				foreach ($user_role_logs_history_details as $user_assign_roles) {

					$user_role_assign_details = $user_assign_roles['user_roles'];
					$assign_user_roles = explode(',',$user_role_assign_details);

					if (in_array($search_user_role,$assign_user_roles, TRUE)) {

						if ($user_assign_roles['to_user']==$search_user_id) {

							$date = explode(' ',$user_assign_roles['created']);
							$created_value_xy = strtotime(str_replace('/','-',$date[0]));
							$search_from_date_xy = strtotime(str_replace('/','-',$search_from_date));
							$search_to_date_xy  = strtotime(str_replace('/','-',$search_to_date));

							if (($created_value_xy > $search_from_date_xy) && ($created_value_xy < $search_to_date_xy)) {
								$user_role_logs_details[$j] = $user_assign_roles;
								$j = $j+1;
							}
						}
					}
				}

				return $user_role_logs_details;
			
			} elseif ($search_user_role != null && $search_user_id != null) {

				$user_role_logs_details = null;
				$j=0;

				foreach ($user_role_logs_history_details as $user_assign_roles) {

					$user_role_assign_details = $user_assign_roles['user_roles'];
					$assign_user_roles = explode(',',$user_role_assign_details);

					if (in_array($search_user_role,$assign_user_roles, TRUE)) {

						if ($user_assign_roles['to_user']==$search_user_id) {
							$user_role_logs_details[$j] = $user_assign_roles;
							$j = $j+1;
						}
					}
				}

				return $user_role_logs_details;
			
			} elseif ($search_user_role != null && $search_from_date != null && $search_to_date != null) {

				$user_role_logs_details = null;

				$j=0;
				foreach ($user_role_logs_history_details as $user_user) {

					$user_role_assign_details = $user_user['user_roles'];
					$assign_user_roles = explode(',',$user_role_assign_details);

					if (in_array($search_user_role,$assign_user_roles, TRUE)) {

						$date = explode(' ',$user_user['created']);
						$created_value_xy = strtotime(str_replace('/','-',$date[0]));
						$search_from_date_xy = strtotime(str_replace('/','-',$search_from_date));
						$search_to_date_xy  = strtotime(str_replace('/','-',$search_to_date));
						if (($created_value_xy > $search_from_date_xy) && ($created_value_xy < $search_to_date_xy)) {
							$user_role_logs_details[$j] = $user_user; //change on 08-04-2022
							$j = $j+1;
						}
					}
				}

				return $user_role_logs_details;
			
			} elseif ($search_from_date != null && $search_to_date != null) {

				$user_role_logs_details = null;

				$j=0;
				foreach ($user_role_logs_history_details as $user_assign_roles) {

					$date = explode(' ',$user_assign_roles['created']);
					$created_value_xy = strtotime(str_replace('/','-',$date[0]));
					$search_from_date_xy = strtotime(str_replace('/','-',$search_from_date));
					$search_to_date_xy  = strtotime(str_replace('/','-',$search_to_date));

					if (($created_value_xy >= $search_from_date_xy) && ($created_value_xy <= $search_to_date_xy)) {
						$user_role_logs_details[$j] = $user_assign_roles;
						$j = $j+1;
					}
				}

				return $user_role_logs_details;
			
			} elseif ($search_user_id != null) {

				$user_role_logs_details = null;

				$j=0;
				foreach ($user_role_logs_history_details as $user_assign_roles) {

					if ($user_assign_roles['to_user']==$search_user_id) {
						$user_role_logs_details[$j] = $user_assign_roles;
						$j = $j+1;
					}
				}

				return $user_role_logs_details;
			
			} elseif ($search_user_role != null) {

				$user_role_logs_details = null;

				$j=0;
				foreach ($user_role_logs_history_details as $user_assign_roles) {

					$user_role_assign_details = $user_assign_roles['user_roles'];
					$assign_user_roles = explode(',',$user_role_assign_details);

					if (in_array($search_user_role,$assign_user_roles, TRUE)) {
						$user_role_logs_details[$j] = $user_assign_roles;
						$j = $j+1;
					}
				}

				return $user_role_logs_details;

			} else {
				$user_role_logs_details = $user_role_logs_history_details;
				return $user_role_logs_details;
			}

		} else {
			$user_role_logs_details = $user_role_logs_history_details;
			return $user_role_logs_details;
		}
	
	}




	// Mo Allocation Logs Report / Io Allocation Logs Report / Ro Allocation Logs Report
	// Description :  Start Mo , IO, RO allocation Logs History Report Section
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 11-09-2017

	public function moAllocationLogsReport()
	{
		$this->Session->write('allocation_logs_user_type','MO');
		$this->redirect('/reports/allocation_logs_report');
	}

	public function ioAllocationLogsReport()
	{
		$this->Session->write('allocation_logs_user_type','IO');
		$this->redirect('/reports/allocation_logs_report');
	}

	public function roAllocationLogsReport()
	{
		$this->Session->write('allocation_logs_user_type','RO');
		$this->redirect('/reports/allocation_logs_report'); 
	}





	// Allocation Logs Report
	// Description :  ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function allocationLogsReport() {

		$user_type = $this->Session->read('allocation_logs_user_type');

		if (!empty($user_type)) {
			$user_type = $user_type;
		} else {
			$user_type ='MO';
		}

		$this->set('user_type',$user_type);

		if ($user_type == 'MO') {
			$report_heading = 'MO/SMO Allocation Logs History Report';
		} elseif ($user_type == 'IO') {
			$report_heading = 'IO Allocation Logs History Report';
		} elseif ($user_type == 'RO') {
			$report_heading = 'RO Incharge Allocation Logs History Report';
		}

		$this->set('report_heading',$report_heading);

		$user_name_list = $this->userNameList();
		$this->set('user_name_list',$user_name_list);

		//added 'office_type'=>'RO' condition on 27-07-2018
		$ro_office = $this->DmiRoOffices->find('all')->where(['office_type' => 'RO','delete_status IS NULL'])->order(['ro_office' => 'ASC'])->combine('id', 'ro_office')->toArray(); 
		$this->set('ro_office',$ro_office);

		$search_office = $this->Session->read('search_office');
		$application_id = $this->Session->read('application_id');
		$search_user_id = $this->Session->read('search_user_id');
		$search_from_date = $this->Session->read('search_from_date');
		$search_to_date = $this->Session->read('search_to_date');
		$this->set('search_office',$search_office);
		$this->set('application_id',$application_id);
		$this->set('search_user_id',$search_user_id);
		$this->set('search_from_date',$search_from_date);
		$this->set('search_to_date',$search_to_date);

		// Set default value for download report click event (Done by pravin 13-03-2018)
		$download_report = 'no';

		//Check and Pass the entry for "Search" or "Download Report as Excel" button click event (Done by pravin 13-03-2018)
		if (null != ($this->request->getData('search_logs')) || null != ($this->request->getData('download_report'))) {

			//Check not empty "Download Report as Excel" button Request, if condition TRUE then set value "yes" for "Download Report as Excel" click event
			//and pass this value to "mo_io_ro_allocation_serach_conditions" function (Done by pravin 13-03-2018)
			if (!empty($this->request->getData('download_report'))) {
				$download_report = 'yes';
			}

			//checking dropdown input
			$table = 'DmiRoOffices';
			$post_input_request = $this->request->getData('office');
			if (!empty($post_input_request)) {
				$search_office = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request); // calling library function
			} else {
				$search_office = '';
			}

			if ($user_type == 'RO') {
				$application_id = null;
			} else {
				$application_id =  htmlentities($this->request->getData('application_id'), ENT_QUOTES);
			}

			$search_user_id = $this->request->getData('user_id');
			$search_from_date = $this->request->getData('from_date');
			$search_from_date = $this->Customfunctions->dateFormatCheck($search_from_date);
			$search_to_date = $this->request->getData('to_date');
			$search_to_date = $this->Customfunctions->dateFormatCheck($search_to_date);
			$this->date_comparison($search_from_date,$search_to_date);

			// Change on 2/11/2018 : For download excel report, Take search filter field value from session variables instend of POST variable - By Pravin Bhakare
			if ($download_report == 'yes') {

				$search_office = $this->Session->read('search_office');
				$application_id = $this->Session->read('application_id');
				$search_user_id = $this->Session->read('search_user_id');
				$search_from_date = $this->Session->read('search_from_date');
				$search_to_date = $this->Session->read('search_to_date');
			}

			$this->Session->delete('search_office');
			$this->Session->delete('application_id');
			$this->Session->delete('search_user_id');
			$this->Session->delete('search_from_date');
			$this->Session->delete('search_to_date');

			$this->Session->write('search_office',$search_office);
			$this->Session->write('application_id',$application_id);
			$this->Session->write('search_user_id',$search_user_id);
			$this->Session->write('search_from_date',$search_from_date);
			$this->Session->write('search_to_date',$search_to_date);


			$this->set('search_office',$search_office);
			$this->set('application_id',$application_id);
			$this->set('search_user_id',$search_user_id);
			$this->set('search_from_date',$search_from_date);
			$this->set('search_to_date',$search_to_date);

			$this->moIoRoAllocationSerachConditions($user_type, $search_office, $application_id, $search_user_id, $search_from_date, $user_name_list, $search_to_date, $ro_office, $download_report);
		} else {
			$this->moIoRoAllocationSerachConditions($user_type, $search_office, $application_id, $search_user_id, $search_from_date, $user_name_list, $search_to_date, $ro_office, $download_report);
		}
	
	}



	// Mo Io Ro Allocation Serach Conditions
	// Description : This function is set of search conditions that are used for to create MO, IO, RO allocation logs report_types
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function moIoRoAllocationSerachConditions($user_type, $search_office, $application_id, $search_user_id, $search_from_date, $user_name_list, $search_to_date, $ro_office, $download_report) {

		if ($user_type == 'IO') {
			$table= 'DmiIoAllocationLogs';
			$office_field = 'io_office';
			$user_id_field = 'io_email_id';
		} elseif ($user_type == 'MO') {
			$table= 'DmiMoAllocationLogs';
			$office_field = 'mo_office';
			$user_id_field = 'mo_email_id';
		} elseif ($user_type == 'RO') {
			$table= 'DmiRoAllocationLogs';
			$office_field = 'ro_office';
			$user_id_field = 'ro_incharge_id';
		}

		$office_not_empty = null;
		$application_id_not_empty = null;
		$user_id_not_empty = null;
		$date_not_empty = null;

		if ($search_office != null) {
			$search_office = $ro_office[$search_office];
			$office_not_empty = array($office_field => $search_office);
		}

		if ($application_id != null) {
			$update_application_id = $application_id; // updated strtolower($application_id) to $application_id by Ankur
			$application_id_not_empty = ['customer_id LIKE' => '%'.$update_application_id.'%']; // updated ['LOWER(customer_id) LIKE' => to ['customer_id LIKE' => by Ankur
		}

		if ($search_user_id != null) {
			$search_user_id = $this->search_user_id($user_name_list, $search_user_id);
			$user_id_not_empty = [$user_id_field => $search_user_id];
		}

		if ($search_from_date != null && $search_to_date != null) {
			$date_not_empty = ['date(created) BETWEEN :start AND :end'];
		}

		if ($search_office == '' && $application_id == '' && $search_user_id == '' && $search_from_date == '' && $search_to_date == '') {
			$this->set('search_result_for', 'all');

			if (null != ($this->request->getData('search_logs')) || null != ($this->request->getData('download_report'))) {
				$allocation_logs_details = $this->$table->find('all')->order(['id' => 'DESC'])->toArray(); 
			} else { // default query without search with limit top 100
				$allocation_logs_details = $this->$table->find('all')->order(['id' => 'DESC'])->limit(['100'])->toArray(); 
			}
			//Fetch the all data that required for creating the downloading report as execel (Done by pravin 13-03-2018)
			if ($download_report == 'yes') {
				$download_allocation_logs_details = $this->$table->find('all')->order(['id' => 'DESC'])->toArray(); 
				$this->downloadAllocationLogsDetailsReport($download_allocation_logs_details, $table, $office_field, $user_id_field);
			}

		} else {

			if ($search_from_date != null && $search_to_date != null) {
				$allocation_logs_details = $this->$table->find('all')->where($office_not_empty)->where($application_id_not_empty)->where($user_id_not_empty)
					->where($date_not_empty)->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->order(['id' => 'desc'])->toArray(); 
			} else {
				$allocation_logs_details = $this->$table->find('all')->where($office_not_empty)->where($application_id_not_empty)->where($user_id_not_empty)
					->order(['id' => 'desc'])->toArray(); 
			}

			//Fetch the all data that required for creating the downloading report as execel (Done by pravin 13-03-2018)
			if ($download_report == 'yes') {

				if ($search_from_date != null && $search_to_date != null) {
					$download_allocation_logs_details = $this->$table->find('all')->where($office_not_empty)->where($application_id_not_empty)->where($user_id_not_empty)
						->where($date_not_empty)->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->order(['id' => 'DESC'])->toArray(); 
				} else {
					$download_allocation_logs_details = $this->$table->find('all')->where($office_not_empty)->where($application_id_not_empty)->where($user_id_not_empty)
						->order(['id' => 'DESC'])->toArray(); 
				}
				$this->downloadAllocationLogsDetailsReport($download_allocation_logs_details, $table, $office_field, $user_id_field);
			}
		}

		if (!empty($allocation_logs_details)) {

			$i=0;
			foreach ($allocation_logs_details as $user_name) {
				$user_id = $user_name['user_id'];
				$user_details = $this->DmiUsers->find('all')->where(['id IS' => intval($user_id)])->first(); 

				if (!empty($user_details)) {
					$user_full_name = $user_details['f_name'].' '.$user_details['l_name'];
					$user_name_detail[$i] = $user_full_name.' ('.base64_decode($user_details['email']).')';//for email encoding
				} else {
					$user_name_detail[$i] = '---';
				}

				$i = $i+1;
			}

			$this->set('user_name_detail', $user_name_detail);

		} else {
			$allocation_logs_details = array();
		}

		$this->set('allocation_logs_details', $allocation_logs_details);
		$this->set('table', $table);
		$this->set('office_field', $office_field);
		$this->set('user_id_field', $user_id_field);
	}

	



	// Download Allocation Logs Details Report
	// Description : This function create excel format file for downloading the report for "allocation logs details report"
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 13-03-2018

	public function downloadAllocationLogsDetailsReport($download_allocation_logs_details, $table, $office_field, $user_id_field) {
			
		$this->viewBuilder()->setLayout('downloadpdf');

		if (!empty($download_allocation_logs_details)) {
			$i=0;
			foreach ($download_allocation_logs_details as $user_name) {
				$user_id = $user_name['user_id'];
				$user_details = $this->DmiUsers->find('all')->where(['id IS' => intval($user_id)])->first(); 

				if (!empty($user_details)) {
					$user_full_name = $user_details['f_name'].' '.$user_details['l_name'];
					$user_name_detail[$i] = $user_full_name.' ('.base64_decode($user_details['email']).')';//for email encoding
				}
				else {
					$user_name_detail[$i] = '---';
				}
			$i = $i+1;
			}
			$this->set('user_name_detail', $user_name_detail);
			$this->set('orders', $download_allocation_logs_details);
			$this->set('table', $table);
			$this->set('office_field', $office_field);
			$this->set('user_id_field', $user_id_field);

			$this->layout = null;
			$this->autoLayout = false;
			Configure::write('debug', '0');
			$this -> render('/element/download_report_excel_format/download_allocation_logs_details_report');
		}
	}





	// Fifteen Day Pending New Application / Fifteen Day Pending Renewal Application
	// Description : Start to create Pending new application report
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 16-09-2017

	public function fifteenDayPendingNewApplication()
	{
		$this->Session->write('pending_days','more_than_fifteen');
		$this->redirect('/reports/pending_new_applications_report');
	}

	public function fifteenDayPendingRenewalApplication()
	{
		$this->Session->write('pending_days','more_than_fifteen');
		$this->redirect('/reports/pending_renewal_applications_report');
	}





	// Pending New Applications Report
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function pendingNewApplicationsReport() {

		$application_pending_days = $this->Session->read('pending_days');

		if (!empty($application_pending_days)) {
			$report_name = 'Pending New Applications Report ( More than 15 Days)';
		} else {
			$report_name ='Pending New Applications Report';
		}

		$this->set('report_name',$report_name);

		$table = 'DmiAllApplicationsCurrentPositions';
		$pending_application_type = 'new';

		$application_type_xy = array('A'=>'CA (Form-A)', 'C'=>'Laboratory (Form-C)', 'E'=>'CA (Form-E)', 'B'=>'Printing Press (Form-B)', 'D'=>'Laboratory (Form-D)', 'F'=>'CA (Form-F)');

		//Change on 9/11/2018, Sorting array by ascending order - By Pravin Bhakare
		asort($application_type_xy);
		$this->set('application_type_xy',$application_type_xy);

		$user_roles_xy = array('RO/SO'=>'RO/SO','MO/SMO'=>'MO/SMO','IO'=>'IO','HO MO/SMO'=>'HO MO/SMO','DY.AMA'=>'DY.AMA','JT.AMA'=>'JT.AMA','AMA'=>'AMA');

		//Change on 9/11/2018, Sorting array by ascending order - By Pravin Bhakare
		asort($user_roles_xy);
		$this->set('user_roles_xy',$user_roles_xy);

		$ro_office = $this->DmiRoOffices->find('all')->where(['office_type' => 'RO','delete_status IS NULL'])->order(['ro_office' => 'ASC'])->combine('id', 'ro_office')->toArray(); 
		$this->set('ro_office',$ro_office);

		$search_application_type_id = $this->Session->read('search_application_type_id');
		$search_user_role = $this->Session->read('search_user_role');
		$ro_office_id = $this->Session->read('ro_office_id');
		$mo_office_id = $this->Session->read('mo_office_id');
		$io_office_id = $this->Session->read('io_office_id');
		$search_from_date = $this->Session->read('search_from_date');
		$search_to_date = $this->Session->read('search_to_date');
		$search_user_email_id = $this->Session->read('search_user_email_id');

		$download_search_application_type_id = $this->Session->read('search_application_type_id');
		$download_search_user_role = $this->Session->read('search_user_role');
		$download_ro_office_id = $this->Session->read('ro_office_id');
		$download_mo_office_id = $this->Session->read('mo_office_id');
		$download_io_office_id = $this->Session->read('io_office_id');
		$download_search_from_date = $this->Session->read('search_from_date');
		$download_search_to_date = $this->Session->read('search_to_date');
		$download_search_user_email_id = $this->Session->read('search_user_email_id');

		$this->set('search_application_type_id',$search_application_type_id);
		$this->set('search_user_role',$search_user_role);
		$this->set('ro_office_id',$ro_office_id);
		$this->set('mo_office_id',$mo_office_id);
		$this->set('io_office_id',$io_office_id);
		$this->set('search_from_date',$search_from_date);
		$this->set('search_to_date',$search_to_date);
		$this->set('search_user_email_id',$search_user_email_id);

		// Set default value for download report click event (Done by pravin 14-03-2018)
		$download_report = 'no';

		//Check and Pass the entry for "Search" or "Download Report as Excel" button click event (Done by pravin 14-03-2018)
		if (null != ($this->request->getData('search_logs')) || null != ($this->request->getData('download_report'))) {
			//Check not empty "Download Report as Excel" button Request, if condition TRUE then set value "yes" for "Download Report as Excel" click event
			//and pass this value to "mo_io_ro_allocation_serach_conditions" function (Done by pravin 14-03-2018)
			if (!empty($this->request->getData('download_report'))) {
				$download_report = 'yes';
			}

			$search_application_type_id = $this->request->getData('application_type');
			$search_user_role =  $this->request->getData('user_role');
			$ro_office_id =  $this->request->getData('ro_office');
			$mo_office_id =  $this->request->getData('mo_office');
			$io_office_id =  $this->request->getData('io_office');

			$search_user_email_id =  $this->request->getData('user_id');
			$search_from_date =  $this->request->getData('from_date');
			$search_from_date = $this->Customfunctions->dateFormatCheck($search_from_date);
			$search_to_date =  $this->request->getData('to_date');
			$search_to_date = $this->Customfunctions->dateFormatCheck($search_to_date);
			$this->date_comparison($search_from_date,$search_to_date);

			// Change on 2/11/2018 - For download excel report, Take search filter field value from session variables instend of POST variable - By Pravin
			if ($download_report == 'yes') {

				$search_application_type_id = $this->Session->read('search_application_type_id');
				$search_user_role = $this->Session->read('search_user_role');
				$ro_office_id = $this->Session->read('ro_office_id');
				$mo_office_id = $this->Session->read('mo_office_id');
				$io_office_id = $this->Session->read('io_office_id');
				$search_from_date = $this->Session->read('search_from_date');
				$search_to_date = $this->Session->read('search_to_date');
				$search_user_email_id = $this->Session->read('search_user_email_id');
			}

			$download_application_customer_id_list = $this->pendingApplicationSearchConditions($download_search_application_type_id,$download_search_user_role,$download_ro_office_id,$download_mo_office_id,$download_io_office_id,$download_search_from_date,$download_search_to_date,$download_search_user_email_id,$table,$pending_application_type,$application_pending_days);

			$this->Session->delete('search_application_type_id');
			$this->Session->delete('search_user_role');
			$this->Session->delete('ro_office_id');
			$this->Session->delete('mo_office_id');
			$this->Session->delete('io_office_id');
			$this->Session->delete('search_from_date');
			$this->Session->delete('search_to_date');
			$this->Session->delete('search_user_email_id');

			$this->Session->write('search_application_type_id',$search_application_type_id);
			$this->Session->write('search_user_role',$search_user_role);
			$this->Session->write('ro_office_id',$ro_office_id);
			$this->Session->write('mo_office_id',$mo_office_id);
			$this->Session->write('io_office_id',$io_office_id);
			$this->Session->write('search_from_date',$search_from_date);
			$this->Session->write('search_to_date',$search_to_date);
			$this->Session->write('search_user_email_id',$search_user_email_id);

			$this->set('search_application_type_id',$search_application_type_id);
			$this->set('search_user_role',$search_user_role);
			$this->set('ro_office_id',$ro_office_id);
			$this->set('mo_office_id',$mo_office_id);
			$this->set('io_office_id',$io_office_id);
			$this->set('search_from_date',$search_from_date);
			$this->set('search_to_date',$search_to_date);
			$this->set('search_user_email_id',$search_user_email_id);


			$application_customer_id_list = $this->pendingApplicationSearchConditions($search_application_type_id,$search_user_role,$ro_office_id,$mo_office_id,$io_office_id,$search_from_date,$search_to_date,$search_user_email_id,$table,$pending_application_type,$application_pending_days);

			if (!empty($application_customer_id_list)) {

				$current_users_details = $this->$table->find('all')->where(['customer_id IN'=>$application_customer_id_list])->order(['id' => 'DESC'])->toArray(); 

				//Fetch the all data that required for creating the downloading report as execel (Done by pravin 14-03-2018)
				if ($download_report == 'yes') {

					if (!empty($download_application_customer_id_list)) {
						$download_condition = ['customer_id IN' => $download_application_customer_id_list];
					} else {
						$download_condition = ['customer_id IS' => ''];
					}

					$download_pending_application = $this->$table->find('all')->where($download_condition)->order(['id' => 'DESC'])->toArray(); 
					$this->downloadPendingApplicationReport($download_pending_application,$pending_application_type,$table);
				}
				
			} else {
				$current_users_details = null;
			}

			$this->pendingApplicationReportResults($current_users_details,$pending_application_type,$table);

		} else {

			$application_customer_id_list = $this->pendingApplicationSearchConditions($search_application_type_id,$search_user_role,$ro_office_id,$mo_office_id,$io_office_id,$search_from_date,$search_to_date,$search_user_email_id,$table,$pending_application_type,$application_pending_days);

			if (!empty($application_customer_id_list)) {

				$current_users_details = $this->$table->find('all')->where(['customer_id IN' => $application_customer_id_list])->order(['id' => 'DESC'])->limit(['100'])->toArray(); 
				$this->set('current_users_details',$current_users_details);

				//Fetch the all data that required for creating the downloading report as execel (Done by pravin 14-03-2018)
				if ($download_report == 'yes') {
					$download_pending_application = $this->$table->find('all')->where(['customer_id' => $application_customer_id_list])->order(['id' => 'DESC'])->toArray(); 
					$this->downloadPendingApplicationReport($download_pending_application,$pending_application_type,$table);
				}

			} else {
				$current_users_details = null;
			}

			$this->pendingApplicationReportResults($current_users_details,$pending_application_type,$table);
		}
	
	}





	// Pending Renewal Applications Report
	// Description : Start to create Pending renewal application report 
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 20-09-2017

	public function pendingRenewalApplicationsReport() {

		$application_pending_days = $this->Session->read('pending_days');

		if (!empty($application_pending_days)) {
			$report_name = 'Pending Renewal Applications Report (More than 15 Days)';
		} else {
			$report_name ='Pending Renewal Applications Report';
		}

		$this->set('report_name',$report_name);

		$table = 'DmiRenewalAllCurrentPositions';
		$pending_application_type = 'renewal';

		$application_type_xy = array('A'=>'CA (Form-A)','C'=>'Laboratory (Form-C)','E'=>'CA (Form-E)','B'=>'Printing Press (Form-B)','D'=>'Laboratory (Form-D)','F'=>'CA (Form-F)');

		//Change on 9/11/2018, Sorting array by ascending order - By Pravin Bhakare
		asort($application_type_xy);
		$this->set('application_type_xy',$application_type_xy);

		$user_roles_xy = array('RO/SO'=>'RO/SO','MO/SMO'=>'MO/SMO','IO'=>'IO');
		//Change on 9/11/2018, Sorting array by ascending order - By Pravin Bhakare
		asort($user_roles_xy);
		$this->set('user_roles_xy',$user_roles_xy);

		$ro_office = $this->DmiRoOffices->find('all')->select(['id', 'ro_office'])->where(['office_type' => 'RO','delete_status IS NULL'])->order(['ro_office' => 'ASC'])
			->combine('id', 'ro_office')->toArray(); 
		$this->set('ro_office',$ro_office);

		$search_application_type_id = $this->Session->read('search_application_type_id');

		$search_user_role = $this->Session->read('search_user_role');
		$ro_office_id = $this->Session->read('ro_office_id');
		$mo_office_id = $this->Session->read('mo_office_id');
		$io_office_id = $this->Session->read('io_office_id');
		$search_from_date = $this->Session->read('search_from_date');
		$search_to_date = $this->Session->read('search_to_date');
		$search_user_email_id = $this->Session->read('search_user_email_id');

		$download_search_application_type_id = $this->Session->read('search_application_type_id');
		$download_search_user_role = $this->Session->read('search_user_role');
		$download_ro_office_id = $this->Session->read('ro_office_id');
		$download_mo_office_id = $this->Session->read('mo_office_id');
		$download_io_office_id = $this->Session->read('io_office_id');
		$download_search_from_date = $this->Session->read('search_from_date');
		$download_search_to_date = $this->Session->read('search_to_date');
		$download_search_user_email_id = $this->Session->read('search_user_email_id');

		$this->set('search_application_type_id',$search_application_type_id);
		$this->set('search_user_role',$search_user_role);
		$this->set('ro_office_id',$ro_office_id);
		$this->set('mo_office_id',$mo_office_id);
		$this->set('io_office_id',$io_office_id);
		$this->set('search_from_date',$search_from_date);
		$this->set('search_to_date',$search_to_date);
		$this->set('search_user_email_id',$search_user_email_id);

		// Set default value for download report click event (Done by pravin 14-03-2018)
		$download_report = 'no';

		//Check and Pass the entry for "Search" or "Download Report as Excel" button click event (Done by pravin 14-03-2018)
		if (null != ($this->request->getData('search_logs')) || null != ($this->request->getData('download_report'))) {

			//Check not empty "Download Report as Excel" button Request, if condition TRUE then set value "yes" for "Download Report as Excel" click event
			//and pass this value to "mo_io_ro_allocation_serach_conditions" function (Done by pravin 14-03-2018)
			if (!empty($this->request->getData('download_report'))) {
				$download_report = 'yes';
			}

			$search_application_type_id = $this->request->getData('application_type');
			$search_user_role =  $this->request->getData('user_role');
			$ro_office_id =  $this->request->getData('ro_office');
			$mo_office_id =  $this->request->getData('mo_office');
			$io_office_id =  $this->request->getData('io_office');

			$search_user_email_id =  $this->request->getData('user_id');
			$search_from_date =  $this->request->getData('from_date');
			$search_from_date = $this->Customfunctions->dateFormatCheck($search_from_date);
			$search_to_date =  $this->request->getData('to_date');
			$search_to_date = $this->Customfunctions->dateFormatCheck($search_to_date);
			$this->date_comparison($search_from_date,$search_to_date);

			// Change on 2/11/2018 - For download excel report, Take search filter field value from session variables instend of POST variable - By Pravin
			if ($download_report == 'yes') {

				$search_user_role = $this->Session->read('search_user_role');
				$ro_office_id = $this->Session->read('ro_office_id');
				$mo_office_id = $this->Session->read('mo_office_id');
				$io_office_id = $this->Session->read('io_office_id');
				$search_from_date = $this->Session->read('search_from_date');
				$search_to_date = $this->Session->read('search_to_date');
				$search_user_email_id = $this->Session->read('search_user_email_id');
			}

			$download_application_customer_id_list = $this->pendingApplicationSearchConditions($download_search_application_type_id,$download_search_user_role,$download_ro_office_id,$download_mo_office_id,$download_io_office_id,$download_search_from_date,$download_search_to_date,$download_search_user_email_id,$table,$pending_application_type,$application_pending_days);

			$this->Session->delete('search_application_type_id');
			$this->Session->delete('search_user_role');
			$this->Session->delete('ro_office_id');
			$this->Session->delete('mo_office_id');
			$this->Session->delete('io_office_id');
			$this->Session->delete('search_from_date');
			$this->Session->delete('search_to_date');
			$this->Session->delete('search_user_email_id');

			$this->Session->write('search_application_type_id',$search_application_type_id);
			$this->Session->write('search_user_role',$search_user_role);
			$this->Session->write('ro_office_id',$ro_office_id);
			$this->Session->write('mo_office_id',$mo_office_id);
			$this->Session->write('io_office_id',$io_office_id);
			$this->Session->write('search_from_date',$search_from_date);
			$this->Session->write('search_to_date',$search_to_date);
			$this->Session->write('search_user_email_id',$search_user_email_id);

			$this->set('search_application_type_id',$search_application_type_id);
			$this->set('search_user_role',$search_user_role);
			$this->set('ro_office_id',$ro_office_id);
			$this->set('mo_office_id',$mo_office_id);
			$this->set('io_office_id',$io_office_id);
			$this->set('search_from_date',$search_from_date);
			$this->set('search_to_date',$search_to_date);
			$this->set('search_user_email_id',$search_user_email_id);

			$application_customer_id_list = $this->pendingApplicationSearchConditions($search_application_type_id,$search_user_role,$ro_office_id,$mo_office_id,$io_office_id,$search_from_date,$search_to_date,$search_user_email_id,$table,$pending_application_type,$application_pending_days);

			if (!empty($application_customer_id_list)) {

				$current_users_details = $this->$table->find('all')->where(['customer_id IN'=>$application_customer_id_list])->order(['id' => 'DESC'])->toArray(); 

				//Fetch the all data that required for creating the downloading report as execel (Done by pravin 14-03-2018)
				if ($download_report == 'yes') {
					$download_pending_application = $this->$table->find('all')->where(['customer_id IN' => $download_application_customer_id_list])->order(['id' => 'DESC'])->toArray(); 
					$this->downloadPendingApplicationReport($download_pending_application,$pending_application_type,$table);
				}

			} else {
				$current_users_details = null;
			}

			$this->pendingApplicationReportResults($current_users_details,$pending_application_type,$table);
		
		} else {

			$application_customer_id_list = $this->pendingApplicationSearchConditions($search_application_type_id,$search_user_role,$ro_office_id,$mo_office_id,$io_office_id,$search_from_date,$search_to_date,$search_user_email_id,$table,$pending_application_type,$application_pending_days);

			if (!empty($application_customer_id_list)) {

				$current_users_details = $this->$table->find('all')->where(['customer_id IN' => $application_customer_id_list])->order(['id' => 'DESC'])->toArray(); 

				//Fetch the all data that required for creating the downloading report as execel (Done by pravin 14-03-2018)
				if ($download_report == 'yes') {
					$download_pending_application = $this->$table->find('all')->where(['customer_id IN' => $application_customer_id_list])
						->order(['id' => 'DESC'])->toArray(); 
					$this->downloadPendingApplicationReport($download_pending_application,$pending_application_type,$table);
				}

			} else {
				$current_users_details = null;
			}

			$this->pendingApplicationReportResults($current_users_details,$pending_application_type,$table);
		}
	
	}




	// Pending Application Search Conditions
	// Description : function used to find search conditions for pending new and renewal application report
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 18-09-2017

	public function pendingApplicationSearchConditions($search_application_type_id,$search_user_role,$ro_office_id,$mo_office_id,$io_office_id,$search_from_date,$search_to_date,$search_user_email_id,$table,$pending_application_type,$application_pending_days)
	{
		$current_date = new \DateTime(date("d-m-Y")); // Ankur updated new DateTime to new \DateTime as Class "App\Controller\DateTime" not found
		$modify_date_obj = $current_date->modify('-15 day');
		$modify_date = $modify_date_obj->format('d-m-Y H:i:s');

		if (!empty($application_pending_days)) {

			$conditions = ['DATE(modified) <' => $modify_date]; 
			// $date_conditions = array('conditions'=>array('date(modified) BETWEEN ? AND ?' => array($search_from_date,$search_to_date),'DATE(created) <'=>$modify_date));
			$date_conditions = ['date(modified) BETWEEN :start AND :end', 'DATE(created) <' => $modify_date]; 
			// $date_conditions_1 = array('fields'=>'customer_id','conditions'=>array('date(modified) BETWEEN ? AND ?' => array($search_from_date,$search_to_date),'DATE(created) <'=>$modify_date));
			$date_conditions_1 = ['date(modified) BETWEEN :start AND :end', 'DATE(created) <' => $modify_date]; 
		
		} else {

			$conditions = [];
			// $date_conditions = array('conditions'=>array('date(modified) BETWEEN ? AND ?' => array($search_from_date,$search_to_date)));
			$date_conditions = ['date(modified) BETWEEN :start AND :end']; 
			// $date_conditions_1 = array('fields'=>'customer_id','conditions'=>array('date(modified) BETWEEN ? AND ?' => array($search_from_date,$search_to_date),'DATE(created) <'=>$modify_date));
			$date_conditions_1 = ['date(modified) BETWEEN :start AND :end', 'DATE(created) <' => $modify_date];  
		}

		$final_customer_id_list = null;

		if ($ro_office_id != '' && $search_user_role == 'RO/SO') {
			$level_1_2_3_office = $ro_office_id;
		} elseif ($mo_office_id != '' && $search_user_role == 'MO/SMO') {
			$level_1_2_3_office = $mo_office_id;
		} elseif ($io_office_id != '' && $search_user_role == 'IO') {
			$level_1_2_3_office = $io_office_id;
		} else {
			$level_1_2_3_office = '';
		}


		if ($search_application_type_id != '' && $search_user_role == '' && $search_from_date == '' && $search_to_date == '') {

			$application_customer_id = $this->$table->find('all')->where($conditions)->toArray(); 

			$i=0;
			foreach ($application_customer_id as $each_customer_id) {

				if (!empty($each_customer_id['customer_id'])) {
					$application_customer_type = $this->Customfunctions->checkApplicantFormType($each_customer_id['customer_id']);

					if (in_array($application_customer_type, $search_application_type_id, TRUE)) {
						$application_customer_id_list[$i] = $each_customer_id['customer_id'];
						$i=$i+1;
					}
				}
			}

		} elseif (($search_application_type_id == '' || $search_application_type_id != '') && $search_user_role != '' && $level_1_2_3_office == '' && $search_from_date == '' && $search_to_date == '') {

			if ($search_application_type_id != '') {

				$application_customer_id = $this->$table->find('all')->where($conditions)->toArray(); 

				$i=0;
				foreach ($application_customer_id as $each_customer_id) {
					if (!empty($each_customer_id['customer_id'])) {
						$application_customer_type = $this->Customfunctions->checkApplicantFormType($each_customer_id['customer_id']);

						if (in_array($application_customer_type,$search_application_type_id)) {
							$seach_application_customer_id_list[$i] = $each_customer_id['customer_id'];
							$i=$i+1;
						}
					}
				}

				$application_current_levels = $this->$table->find('all')->where(['customer_id IN' => $seach_application_customer_id_list])->toArray(); 
			} else {
				
				$application_current_levels = $this->$table->find('all')->where($conditions)->toArray();  
			}
			
			$i=0;
			foreach ($application_current_levels as $each_current_levels) {

				$application_current_level_user_role = $this->checkUserRoleFromCurrentLevel($each_current_levels['current_level'], $each_current_levels['current_user_email_id']);

				if ($application_current_level_user_role == $search_user_role) {
					$application_customer_id_list[$i] = $each_current_levels['customer_id'];
					$i=$i+1;
				}
			}
		
		} elseif (($search_application_type_id == '' || $search_application_type_id != '') && $search_user_role != '' && $level_1_2_3_office != '' && $search_from_date == '' && $search_to_date == '' && $search_user_email_id =='') {
			
			if ($search_application_type_id != '') {

				$application_customer_id = $this->$table->find('all')->where($conditions)->toArray(); 

				$i=0;
				foreach ($application_customer_id as $each_customer_id) {
					if (!empty($each_customer_id['customer_id'])) {
						$application_customer_type = $this->Customfunctions->checkApplicantFormType($each_customer_id['customer_id']);

						if (in_array($application_customer_type,$search_application_type_id)) {
							$seach_application_customer_id_list[$i] = $each_customer_id['customer_id'];
							$i=$i+1;
						}
					}
				}
				$application_current_levels = $this->$table->find('all')->where(['customer_id IN' => $seach_application_customer_id_list])->toArray(); 
			
			} else {
			
				$application_current_levels = $this->$table->find('all')->where($conditions); 
			}

			$i=0;
			foreach ($application_current_levels as $each_current_levels) {

				$application_current_level_user_role = $this->checkUserRoleFromCurrentLevel($each_current_levels['current_level'], $each_current_levels['current_user_email_id']);

				if ($application_current_level_user_role == $search_user_role) {

					$user_posted_office_id_xy = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email IS' => $each_current_levels['current_user_email_id']])->first(); 

					if (!empty($user_posted_office_id_xy)) {
						$user_posted_office_id_xyx = $user_posted_office_id_xy['posted_ro_office'];
					} else {
						$user_posted_office_id_xyx = '';
					}

					if (in_array($user_posted_office_id_xyx,$level_1_2_3_office)) {
						$application_customer_id_list[$i] = $each_current_levels['customer_id'];
						$i=$i+1;
					}
				}
			}
		
		} elseif (($search_application_type_id == '' || $search_application_type_id != '') && $search_user_role == '' && $level_1_2_3_office == '' && $search_from_date != '' && $search_to_date != '') {
			
			if ($search_application_type_id != '') {

				$application_customer_id = $this->$table->find('all')->where($date_conditions)->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->toArray(); 

				$i=0;
				foreach ($application_customer_id as $each_customer_id) {
					if (!empty($each_customer_id['customer_id'])) {
						$application_customer_type = $this->Customfunctions->checkApplicantFormType($each_customer_id['customer_id']);

						if (in_array($application_customer_type,$search_application_type_id)) {
							$application_customer_id_list[$i] = $each_customer_id['customer_id'];
							$i=$i+1;
						}
					}
				}
			
			} else {

				$application_customer_id_list = $this->$table->find('all')->select(['customer_id'])->where($date_conditions_1)->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->combine('id', 'customer_id')->toArray();  
			}

		} elseif (($search_application_type_id == '' || $search_application_type_id != '') && $search_user_role != '' && $level_1_2_3_office != '' && $search_from_date == '' && $search_to_date == '' && $search_user_email_id !='') {
			
			if ($search_application_type_id != '') {

				$application_customer_id = $this->$table->find('all')->where($conditions)->toArray(); 

				$i=0;
				foreach ($application_customer_id as $each_customer_id) {

					if (!empty($each_customer_id['customer_id'])) {

						$application_customer_type = $this->Customfunctions->checkApplicantFormType($each_customer_id['customer_id']);

						if (in_array($application_customer_type, $search_application_type_id)) {
							$seach_application_customer_id_list[$i] = $each_customer_id['customer_id'];
							$i=$i+1;
						}
					}
				}

				$application_current_levels = $this->$table->find('all')->where(['customer_id IN' => $seach_application_customer_id_list])->toArray(); 
			
			} else {
			
				$application_current_levels = $this->$table->find('all')->where($conditions)->toArray();	 
			}

			$i=0;
			foreach ($application_current_levels as $each_current_levels) {

				$application_current_level_user_role = $this->checkUserRoleFromCurrentLevel($each_current_levels['current_level'], $each_current_levels['current_user_email_id']);

				if ($application_current_level_user_role == $search_user_role) {

					$user_posted_office_id_xy = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email IS' => $each_current_levels['current_user_email_id']])->first(); 

					if (!empty($user_posted_office_id_xy)) {
						$user_posted_office_id_xyx = $user_posted_office_id_xy['posted_ro_office'];
					} else {
						$user_posted_office_id_xyx = '';
					}

					if (in_array($user_posted_office_id_xyx,$level_1_2_3_office)) {

						$search_user_email = $this->DmiUserRoles->find('all')->select(['user_email_id'])->where(['id IS' => $search_user_email_id])->first(); 

						if ($each_current_levels['current_user_email_id'] == $search_user_email['user_email_id']) {
							$application_customer_id_list[$i] = $each_current_levels['customer_id'];
							$i=$i+1;
						}
					}
				}
			}

		} elseif (($search_application_type_id == '' || $search_application_type_id != '') && $search_user_role != '' && $search_from_date != '' && $search_to_date != '' && $level_1_2_3_office !='' && ($search_user_email_id != '' || $search_user_email_id == '')) {
			
			if ($search_application_type_id != '') {

				$application_customer_id = $this->$table->find('all')->where($conditions)->toArray(); 

				$i=0;
				foreach ($application_customer_id as $each_customer_id) {

					if (!empty($each_customer_id['customer_id'])) {
						$application_customer_type = $this->Customfunctions->checkApplicantFormType($each_customer_id['customer_id']);

						if (in_array($application_customer_type,$search_application_type_id)) {
							$seach_application_customer_id_list[$i] = $each_customer_id['customer_id'];
							$i=$i+1;
						}
					}
				}

				$application_current_levels = $this->$table->find('all')->where(['customer_id IN' => $seach_application_customer_id_list])
					->where(['date(modified) BETWEEN :start AND :end'])->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->toArray(); 
			} else {

				$application_current_levels = $this->$table->find('all')->where($date_conditions)->bind(':start', $search_from_date, 'date')
				->bind(':end', $search_to_date, 'date')->toArray();   
			}

			$i=0;
			foreach ($application_current_levels as $each_current_levels) {

				$application_current_level_user_role = $this->checkUserRoleFromCurrentLevel($each_current_levels['current_level'],$each_current_levels['current_user_email_id']);

				if ($application_current_level_user_role == $search_user_role) {

					if ($level_1_2_3_office == '') {
						$level_1_2_3_office = [];
					}

					$user_posted_office_id_xy = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email IS' => $each_current_levels['current_user_email_id']])->first(); 

					if (!empty($user_posted_office_id_xy)) {
						$user_posted_office_id_xyx = $user_posted_office_id_xy['posted_ro_office'];
					} else {
						$user_posted_office_id_xyx = '';
					}

					if (in_array($user_posted_office_id_xyx,$level_1_2_3_office)) {

						$search_user_email = $this->DmiUserRoles->find('all')->select(['user_email_id'])->where(['id IS' => $search_user_email_id])->first(); 

						if ($each_current_levels['current_user_email_id'] == $search_user_email['user_email_id']) {
							$application_customer_id_list[$i] = $each_current_levels['customer_id'];
							$i=$i+1;
						}

					} else {
						$application_customer_id_list[$i] = $each_current_levels['customer_id'];
						$i=$i+1;
					}
				}
			}

		} else {
			$application_customer_id = $this->$table->find('all')->select(['customer_id'])->where($conditions)->extract('customer_id')->toArray();  

			// $i=0;
			// foreach ($application_customer_id as $each_customer_id)
			// {
			// 		$application_customer_id_list[$i] = $each_customer_id['customer_id'];
			// 		$i=$i+1;
			// }
			// replaced foreach with query by Ankur
			$application_customer_id_list = $application_customer_id;
		}

		if (!empty($application_customer_id_list)) {

			$i=0;
			if ($pending_application_type == 'new') {
				foreach ($application_customer_id_list as $customer_id) {
					$customer_id_list = $this->DmiGrantCertificatesPdfs->find('all')->where(['customer_id IS' => $customer_id])->first(); 

					if (empty($customer_id_list)) {
						$final_customer_id_list[$i] = $customer_id;
						$i=$i+1;
					}
				}

			} elseif ($pending_application_type == 'renewal') {

				foreach ($application_customer_id_list as $customer_id ) {

					$customer_id_list = $this->DmiRenewalFinalSubmits->find('all')->where(['customer_id IS' => $customer_id, 'status' => 'approved'])->first(); 

					if (empty($customer_id_list)) {
						$final_customer_id_list[$i] = $customer_id;
						$i=$i+1;
					}
				}

				$final_table = 'DmiRenewalFinalSubmits';
			}
		}

		return $final_customer_id_list;
	}



	// Pending Application Report Results
	// Description : function to used to create out result of pending new and renewal application report
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 18-09-2017

	public function pendingApplicationReportResults($current_users_details,$pending_application_type,$table) {

		$date = [];
		$user_list = null;
		$application_type = null;
		$user_roles = null;
		$user_office = null;
		$user_email_id =null;
		$application_id = null;

		if (!empty($current_users_details)) {

			$i=0;
			foreach ($current_users_details as $each_user) {

				$customer_id = $each_user['customer_id'];
				$each_user_detail = $each_user;
				$current_level = $each_user_detail['current_level'];
				$application_form_type = $this->Customfunctions->checkApplicantFormType($each_user_detail['customer_id']);

				if ($application_form_type == 'A') {
					$application_type[$i]='CA (Form-A)';
				} elseif ($application_form_type == 'B') {
					$application_type[$i]='Printing Press (Form-B)';
				} elseif ($application_form_type == 'C') {
					$application_type[$i]='Laboratory (Form-C)';
				} elseif ($application_form_type == 'D') {
					$application_type[$i]='Laboratory (Form-D)';
				} elseif ($application_form_type == 'E') {
					$application_type[$i]='CA (Form-E)';
				} elseif ($application_form_type == 'F') {
					$application_type[$i]='CA (Form-F)';
				}

				$date[$i] = $each_user_detail['modified'];
				$user_email_id[$i] = $each_user_detail['current_user_email_id'];
				$application_id[$i] = $each_user_detail['customer_id'];

				$user_posted_office_id=array();
				if (!empty($user_email_id[$i])) {
					$user_posted_office_id = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email IN' => $user_email_id[$i]])->first(); 
				}
				if (!empty($user_posted_office_id)) {
					$user_office[$i] = $this->DmiRoOffices->find('all')->select(['ro_office'])->where(['id' => $user_posted_office_id['posted_ro_office']])->first(); 
				}

				if (!empty($user_office[$i])) {
					$user_office[$i] = $user_office[$i]['ro_office'];
				} else {
					$user_office[$i] = '--';
				}

				$check_roles=array();
				if (!empty($user_email_id[$i])) {
					$check_roles = $this->DmiUserRoles->find('all')->where(['user_email_id IN' => $user_email_id[$i]])->first(); 
				}

				if (!empty($check_roles)) {
					$user_list[$i] = $check_roles;
				} else {
					$user_list[$i] = '---';
				}

				$user_roles[$i] = $this->checkUserRoleFromCurrentLevel($each_user_detail['current_level'],$each_user_detail['current_user_email_id']);
				$i=$i+1;
			}
		}

		$this->set('date',$date);
		$this->set('user_list',$user_list);
		$this->set('application_type',$application_type);
		$this->set('user_roles',$user_roles);
		$this->set('user_office',$user_office);
		$this->set('user_email_id',$user_email_id);
		$this->set('application_id',$application_id);
	
	}




	// Download Pending Application Report
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function downloadPendingApplicationReport($download_pending_application,$pending_application_type,$table) {

		$this->viewBuilder()->setLayout('downloadpdf');

		$date = [];
		$user_list = null;
		$application_type =null;
		$user_roles = null;
		$user_office = null;
		$user_email_id =null;
		$application_id = null;

		if (!empty($download_pending_application)) {

			$i=0;
			foreach ($download_pending_application as $each_user) {

				$customer_id = $each_user['customer_id'];
				$each_user_detail = $each_user;
				$current_level = $each_user_detail['current_level'];
				$application_form_type = $this->Customfunctions->checkApplicantFormType($each_user_detail['customer_id']);

				if ($application_form_type == 'A') {
					$application_type[$i]='CA (Form-A)';
				} elseif ($application_form_type == 'B') {
					$application_type[$i]='Printing Press (Form-B)';
				} elseif ($application_form_type == 'C') {
					$application_type[$i]='Laboratory (Form-C)';
				} elseif ($application_form_type == 'D') {
					$application_type[$i]='Laboratory (Form-D)';
				} elseif ($application_form_type == 'E') {
					$application_type[$i]='CA (Form-E)';
				} elseif ($application_form_type == 'F') {
					$application_type[$i]='CA (Form-F)';
				}

				$date[$i] = $each_user_detail['modified'];
				$user_email_id[$i] = $each_user_detail['current_user_email_id'];
				$application_id[$i] = $each_user_detail['customer_id'];

				$user_posted_office_id = array();
				if(!empty($user_email_id[$i])){
					$user_posted_office_id = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email IN' => $user_email_id[$i]])->first(); 
				}

				if (!empty($user_posted_office_id)) {
					$user_office[$i] = $this->DmiRoOffices->find('all')->select(['ro_office'])->where(['id' => $user_posted_office_id['posted_ro_office']])->first(); 
				}

				if (!empty($user_office[$i])) {
					$user_office[$i] = $user_office[$i]['ro_office'];
				} else {
					$user_office[$i] = '--';
				}

				if(!empty($user_email_id[$i])){
					$check_roles = $this->DmiUserRoles->find('all')->where(['user_email_id IS' => $user_email_id[$i]])->first();  
				}

				$user_email_id[$i] = base64_decode($user_email_id[$i]);//for email encoding

				if (!empty($check_roles)) {
					$user_list[$i] = $check_roles;
				} else {
					$user_list[$i] = '---';
				}

				$user_roles[$i] = $this->checkUserRoleFromCurrentLevel($each_user_detail['current_level'],$each_user_detail['current_user_email_id']);
				$i=$i+1;
			}
		}

		$this->set('orders',$date);
		$this->set('user_list',$user_list);
		$this->set('application_type',$application_type);
		$this->set('user_roles',$user_roles);
		$this->set('user_office',$user_office);
		$this->set('user_email_id',$user_email_id);
		$this->set('application_id',$application_id);

		$this->layout = null;
		$this->autoLayout = false;
		Configure::write('debug', '0');
		$this->render('/element/download_report_excel_format/download_pending_application_report');
	
	
	}





	// Approved New Application Type / Approved Renewal Application Type
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function approvedNewApplicationType()
	{
		$this->Session->write('approved_application_type','new');
		$this->redirect('/reports/approved_applications_report');
	}

	public function approvedRenewalApplicationType()
	{
		$this->Session->write('approved_application_type','renewal');
		$this->redirect('/reports/approved_applications_report');
	}

	// This function is added to show all the approved application on 16-06-2022
	public function approvedAllApplicationType()
	{
		$this->Session->write('approved_application_type','all_reports');
		$this->redirect('/reports/approved_applications_report');
	}



	// Approved Applications Report
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function approvedApplicationsReport() {

		$approved_application_type = $this->Session->read('approved_application_type');

		if ($approved_application_type == 'new' || $approved_application_type =='') {

			$table = 'DmiFinalSubmits';
			$report_heading = 'Approved New Applications Report';
		
		} elseif ($approved_application_type == 'renewal') {
			
			$table = 'DmiRenewalFinalSubmits';
			$report_heading = 'Approved Renewal Applications Report';

		}elseif ($approved_application_type == 'all_reports') {
			
			$table = 'DmiGrantCertificatesPdfs';
			$report_heading = 'All Approved Report';
			
			// this below code is added to show the deafult office by Akash on 16-06-2022
			$posted_ro_office = $this->DmiUsers->find('all',array('fields'=>'posted_ro_office', 'conditions'=>array('email IS'=>$_SESSION['username'])))->first();
			$default_ro_office = $this->DmiRoOffices->find('all',array('conditions'=>array('id IS'=>$posted_ro_office['posted_ro_office'])))->first();
			$this->set('default_ro_office',$default_ro_office['ro_office']);
		}

		$this->set('table', $table);	// set table value ( Done by pravin 16-07-2018)
		$this->set('report_heading', $report_heading);

		$application_type_xy = array('A'=>'CA (Form-A)','C'=>'Laboratory (Form-C)','E'=>'CA (Form-E)','B'=>'Printing Press (Form-B)','D'=>'Laboratory (Form-D)','F'=>'CA (Form-F)');

		//Change on 9/11/2018, Sorting array by ascending order - By Pravin Bhakare
		asort($application_type_xy);
		$this->set('application_type_xy', $application_type_xy);

		//added 'office_type'=>'RO' condition on 27-07-2018   // Change on 3/11/2018 -  add order by condition - by Pravin Bhakare
		$ro_office = $this->DmiRoOffices->find('all')->select(['id', 'ro_office'])->where(['office_type' => 'RO','delete_status IS NULL'])->order(['ro_office' => 'ASC'])->combine('id', 'ro_office')->toArray(); 
		$this->set('ro_office',$ro_office);

		$search_application_type_id = $this->Session->read('search_application_type_id');
		$application_approved_office = $this->Session->read('application_approved_office');
		$search_from_date = $this->Session->read('search_from_date');
		$search_to_date = $this->Session->read('search_to_date');

		$this->set('search_application_type_id',$search_application_type_id);
		$this->set('application_approved_office',$application_approved_office);
		$this->set('search_from_date',$search_from_date);
		$this->set('search_to_date',$search_to_date);

		$search_flag = 'off'; // added by Ankur
		// Set default value for download report click event (Done by pravin 13-03-2018)
		$download_report = 'no';

		if (null != ($this->request->getData('search_logs')) || null != ($this->request->getData('download_report'))) {
			$search_flag = 'on'; // added by Ankur
			//Check not empty "Download Report as Excel" button Request, if condition TRUE then set value "yes" for "Download Report as Excel" click event
			//and pass this value to "approved_application_search_conditions" function (Done by pravin 13-03-2018)
			if (!empty($this->request->getData('download_report'))) {
				$download_report = 'yes';
			}

			$search_application_type_id = $this->request->getData('application_type');
			$application_approved_office = $this->request->getData('office');
			$search_from_date =  $this->request->getData('from_date');
			$search_from_date = $this->Customfunctions->dateFormatCheck($search_from_date);
			$search_to_date =  $this->request->getData('to_date');
			$search_to_date = $this->Customfunctions->dateFormatCheck($search_to_date);
			$this->date_comparison($search_from_date, $search_to_date);

			// Change on 3/11/2018 - For download excel report, Take search filter field value from session variables instend of POST variable - By Pravin
			if ($download_report == 'yes') {
				
				$search_application_type_id = $this->Session->read('search_application_type_id');
				$application_approved_office = $this->Session->read('application_approved_office');
				$search_from_date = $this->Session->read('search_from_date');
				$search_to_date = $this->Session->read('search_to_date');
			}

			$this->Session->delete('search_application_type_id');
			$this->Session->delete('application_approved_office');
			$this->Session->delete('search_from_date');
			$this->Session->delete('search_to_date');

			$this->Session->write('search_application_type_id', $search_application_type_id);
			$this->Session->write('application_approved_office', $application_approved_office);
			$this->Session->write('search_from_date', $search_from_date);
			$this->Session->write('search_to_date', $search_to_date);

			$this->set('search_application_type_id', $search_application_type_id);
			$this->set('application_approved_office', $application_approved_office);
			$this->set('search_from_date', $search_from_date);
			$this->set('search_to_date', $search_to_date);

			$approved_application_lists = $this->approvedApplicationSearchConditions($search_application_type_id, $application_approved_office, $search_from_date, $search_to_date, $table, $search_flag);
			$approved_application_list = $approved_application_lists[0];
			$download_approved_application_list = $approved_application_lists[1];

			$i=0;
			foreach ($approved_application_list as $each) {

				$approved_application_list[$i] = $each['customer_id'];
				$i=$i+1;
			}

			$j=0;
			foreach ($download_approved_application_list as $each) {

				$download_approved_application_list[$j] = $each['customer_id'];
				$j=$j+1;
			}

			//Fetch the all data that required for creating the downloading report as execel (Done by pravin 14-03-2018)
			if ($download_report == 'yes') {

				$this->downloadApprovedApplicationReportResults($download_approved_application_list, $approved_application_type);
			}

			$this->approvedApplicationReportResults($approved_application_list, $approved_application_type);
		
		} else {
			
			$approved_application_lists = $this->approvedApplicationSearchConditions($search_application_type_id, $application_approved_office, $search_from_date, $search_to_date, $table, $search_flag);
			
			$approved_application_list = $approved_application_lists[0];

			$i=0;
			foreach ($approved_application_list as $each) {
				$approved_application_list[$i] = $each['customer_id'];
				$i=$i+1;
			}

			$this->approvedApplicationReportResults($approved_application_list,$approved_application_type);
		}
	
	}




	// Approved Application Search Conditions
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function approvedApplicationSearchConditions ($search_application_type_id, $application_approved_office, $search_from_date, $search_to_date, $table, $search_flag) {

		$approved_application_list = [];

		if ($search_application_type_id != '' && $application_approved_office == '' && $search_from_date =='' && $search_to_date == '') {

			if ($table == 'DmiFinalSubmits') {
				$approved_application_customer_id = $this->DmiGrantCertificatesPdfs->find()->select(['customer_id'])->group(['customer_id HAVING COUNT(customer_id) < 2'])->toArray();
			} elseif ($table == 'DmiGrantCertificatesPdfs') {
				$approved_application_customer_id = $this->DmiGrantCertificatesPdfs->find('all',array('fields'=>'customer_id','group'=>'customer_id having count(customer_id) >= 1','having'=>array('count(customer_id) >= 1')))->toArray();
			} else {
				$approved_application_customer_id = $this->DmiGrantCertificatesPdfs->find('all',array('fields'=>'customer_id','group'=>'customer_id having count(customer_id) > 1','having'=>array('count(customer_id) > 1')))->toArray();
			}

			$i=0;
			foreach ($approved_application_customer_id as $each_customer_id)	{
				$application_customer_type = $this->Customfunctions->checkApplicantFormType($each_customer_id['customer_id']);

				if (in_array($application_customer_type, $search_application_type_id)) {
					$approved_application_list[$i] = $each_customer_id['customer_id'];
					$i=$i+1;
				}
			}

			if (!empty($approved_application_list)) {
				$conditions = ['customer_id IN' => $approved_application_list, 'status' => 'approved', 'current_level' => 'level_3']; 
			} else {
				$conditions = ['customer_id IS' => '', 'status' => 'approved', 'current_level' => 'level_3']; 
			}

			$approved_application_list = $this->$table->find('all')->where($conditions)->order(['id' => 'DESC'])->toArray();

			$download_approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 
		
		} elseif ($search_application_type_id != '' && $application_approved_office != '' && $search_from_date =='' && $search_to_date == '' ) {

			$approved_application_customer_id = $this->$table->find('all')->select(['customer_id'])->where(['status' => 'approved', 'current_level' => 'level_3'])->toArray(); 

			$i=0;
			foreach ($approved_application_customer_id as $each_customer_id) {

				$application_customer_type = $this->Customfunctions->checkApplicantFormType($each_customer_id['customer_id']);

				if (in_array($application_customer_type,$search_application_type_id)) {

					$approved_application_details_list = $this->DmiGrantCertificatesPdfs->find('all')->select(['id', 'id'])->where(['customer_id' => $each_customer_id['customer_id']])->combine('id', 'id')->toArray(); 

					if (!empty($approved_application_details_list)) {

						$approved_application_details = $this->DmiGrantCertificatesPdfs->find('all')->where(['id' => max($approved_application_details_list)])->first();  

						$user_posted_office_id = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email' => $approved_application_details['user_email_id']])->first();  

						if (!empty($user_posted_office_id)) {

							if (in_array($user_posted_office_id['posted_ro_office'],$application_approved_office)) {

								$approved_application_list[$i] = $each_customer_id['customer_id'];
								$i=$i+1;
							}
						}
					}
				}
			}

			// below if-else code added by Ankur Jangid
			if (!empty($approved_application_list)) {
				$conditions = ['customer_id IN' => $approved_application_list, 'status' => 'approved', 'current_level' => 'level_3'];
			} else {
				$conditions = ['customer_id IS' => '', 'status' => 'approved', 'current_level' => 'level_3'];
			}

			$approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 

			$download_approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 
		
		
		} elseif ($search_application_type_id != '' && $application_approved_office == '' && $search_from_date !='' && $search_to_date != '') {

			$approved_application_customer_id = $this->$table->find('all')->select(['customer_id'])->where(['date(modified) BETWEEN :start AND :end'])
			->where(['status' => 'approved', 'current_level' => 'level_3'])->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->toArray(); 

			$i=0;
			foreach ($approved_application_customer_id as $each_customer_id) {
				$application_customer_type = $this->Customfunctions->checkApplicantFormType($each_customer_id['customer_id']); 

				if (in_array($application_customer_type, $search_application_type_id)) {
					$approved_application_list[$i] = $each_customer_id['customer_id']; 
					$i=$i+1;
				}
			}

			// below if-else code added by Ankur Jangid
			if (!empty($approved_application_list)) {
				$conditions = ['customer_id IN' => $approved_application_list, 'status' => 'approved', 'current_level' => 'level_3'];
			} else {
				$conditions = ['customer_id IS' => '', 'status' => 'approved', 'current_level' => 'level_3'];
			}

			$approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 

			$download_approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 
		
		} elseif ($search_application_type_id != '' && $application_approved_office != '' && $search_from_date !='' && $search_to_date != '') {

			$approved_application_customer_id = $this->$table->find('all')->select(['customer_id'])->where(['date(modified) BETWEEN :start AND :end'])
				->where(['status' => 'approved', 'current_level' => 'level_3'])->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->toArray(); 

			$i=0;
			foreach ($approved_application_customer_id as $each_customer_id) {

				$application_customer_type = $this->Customfunctions->checkApplicantFormType($each_customer_id['customer_id']);

				if (in_array($application_customer_type,$search_application_type_id)) {

					$approved_application_details = $this->DmiGrantCertificatesPdfs->find('all')->where(['customer_id' => $each_customer_id['customer_id']])->first(); 

					$user_posted_office_id = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email IS' => $approved_application_details['user_email_id']])->first(); 

					if (!empty($user_posted_office_id)) {

						if (in_array($user_posted_office_id['posted_ro_office'], $application_approved_office)) {
							$approved_application_list[$i] = $each_customer_id['customer_id'];
							$i=$i+1;
						}
					}
				}
			}

			// below if-else code added by Ankur Jangid
			if (!empty($approved_application_list)) {
				$conditions = ['customer_id IN' => $approved_application_list, 'status' => 'approved', 'current_level' => 'level_3'];
			} else {
				$conditions = ['customer_id IS' => '', 'status' => 'approved', 'current_level' => 'level_3'];
			}

			$approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 

			$download_approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 
		
		} elseif ($search_application_type_id == '' && $application_approved_office != '' && $search_from_date =='' && $search_to_date == '') {

			$approved_application_customer_id = $this->$table->find('all')->select(['customer_id'])->where(['status' => 'approved', 'current_level' => 'level_3'])->toArray(); 

			$i=0;
			foreach ($approved_application_customer_id as $each_customer_id) {

				$approved_application_details_list = $this->DmiGrantCertificatesPdfs->find('all')->select(['id', 'id'])->where(['customer_id IS' => $each_customer_id['customer_id']])->combine('id', 'id')->toArray(); 

				if (!empty($approved_application_details_list)) {

					$approved_application_details = $this->DmiGrantCertificatesPdfs->find('all')->where(['id' => max($approved_application_details_list)])->first(); 

					$user_posted_office_id = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email' => $approved_application_details['user_email_id']])->first(); 

					if (!empty($user_posted_office_id)) {

						if (in_array($user_posted_office_id['posted_ro_office'],$application_approved_office)) {
							$approved_application_list[$i] = $each_customer_id['customer_id'];
							$i=$i+1;
						}
					}
				}
			}

			// below if-else code added by Ankur Jangid
			if (!empty($approved_application_list)) {
				$conditions = ['customer_id IN' => $approved_application_list, 'status' => 'approved', 'current_level' => 'level_3'];
			} else {
				$conditions = ['customer_id IS' => '', 'status' => 'approved', 'current_level' => 'level_3'];
			}

			$approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 

			$download_approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 
		
		} elseif ($search_application_type_id == '' && $application_approved_office != '' && $search_from_date !='' && $search_to_date != '') {

			$approved_application_customer_id = $this->$table->find('all')->select(['customer_id'])->where(['date(modified) BETWEEN :start AND :end', 'status' => 'approved',
				'current_level' => 'level_3'])->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->toArray(); 

			$i=0;
			foreach ($approved_application_customer_id as $each_customer_id) {

				$approved_application_details_list = $this->DmiGrantCertificatesPdfs->find('all')->select(['id', 'id'])->where(['customer_id' => $each_customer_id['customer_id']])->combine('id', 'id')->toArray(); 

				if (!empty($approved_application_details_list)) {

					$approved_application_details = $this->DmiGrantCertificatesPdfs->find('all')->where(['id' => max($approved_application_details_list)])->first(); 

					$user_posted_office_id = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email' => $approved_application_details['user_email_id']])->first(); 

					if (!empty($user_posted_office_id)) {

						if (in_array($user_posted_office_id['posted_ro_office'], $application_approved_office)) {
							$approved_application_list[$i] = $each_customer_id['customer_id'];
							$i=$i+1;
						}
					}
				}
			}

			// below if-else code added by Ankur Jangid
			if (!empty($approved_application_list)) {
				$conditions = ['customer_id IN' => $approved_application_list, 'status' => 'approved', 'current_level' => 'level_3'];
			} else {
				$conditions = ['customer_id IS' => '', 'status' => 'approved', 'current_level' => 'level_3'];
			}

			$approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 

			$download_approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 

		} elseif ($search_application_type_id == '' && $application_approved_office == '' && $search_from_date !='' && $search_to_date != '') {

			$approved_application_customer_id = $this->$table->find('all')->select(['customer_id'])->where(['date(modified) BETWEEN :start AND :end', 'status' => 'approved',
				'current_level' => 'level_3'])->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')->toArray(); 

			$i=0;
			foreach ($approved_application_customer_id as $each_customer_id) {
				$approved_application_list[$i] = $each_customer_id['customer_id'];
				$i=$i+1;
			}

			// below if-else code added by Ankur Jangid
			if (!empty($approved_application_list)) {
				$conditions = ['customer_id IN' => $approved_application_list, 'status' => 'approved', 'current_level' => 'level_3'];
			} else {
				$conditions = ['customer_id IS' => '', 'status' => 'approved', 'current_level' => 'level_3'];
			}

			$approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 

			$download_approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 
		
		} else {

			if ($search_flag == 'on') {
				$approved_application_customer_id = $this->$table->find('all')->select(['customer_id'])->where(['status' => 'approved', 'current_level' => 'level_3'])
				->order(['created'=>'DESC'])->extract('customer_id')->toArray(0); 
			} else {

				// THIS BELOW CONDITION IS ADDED FOR THE ALL REPORTS BY AKASH ON 16-06-2022
				if ($table == 'DmiGrantCertificatesPdfs') {

					$posted_ro_office = $this->DmiUsers->find('all',array('fields'=>'posted_ro_office', 'conditions'=>array('email IS'=>$_SESSION['username'])))->first();
					$get_short_code = $this->DmiRoOffices->find('all',array('fields'=>'short_code', 'conditions'=>array('id IS'=>$posted_ro_office['posted_ro_office'])))->first();
					$short_code = $get_short_code['short_code'];

					if ($_SESSION['role'] == 'Head Office') {
						//$approved_application_customer_id = $this->DmiGrantCertificatesPdfs->find('all',array('fields'=>array('customer_id'),'group'=>array('customer_id having count(customer_id) >= 1'),'having'=>array('count(customer_id) >= 1')))->toArray();
						$approved_application_customer_id = $this->DmiGrantCertificatesPdfs->find('all')->select(['customer_id'])->group(['customer_id HAVING COUNT(customer_id) >= 1'])->limit(['2'])->extract('customer_id')->toArray(0);
					} else {
						$approved_application_customer_id = $this->DmiGrantCertificatesPdfs->find('all',array('fields'=>'customer_id','conditions'=>array('customer_id like'=>'%/'.$short_code.'/%'),'group'=>'customer_id having count(customer_id) >= 1','having'=>array('count(customer_id) >= 1')))->toArray();
					}

				} else {
					$approved_application_customer_id = $this->$table->find('all')->select(['customer_id'])->where(['status' => 'approved', 'current_level' => 'level_3'])
					->order(['created'=>'DESC'])->limit(['100'])->extract('customer_id')->toArray(0); 
				}

				
			}

			$approved_application_list = $approved_application_customer_id;

			// below if-else code added by Ankur Jangid
			if (!empty($approved_application_list)) {

				// THIS BELOW CONDITION IS ADDED FOR THE ALL REPORTS BY AKASH ON 16-06-2022
				if ($table == 'DmiGrantCertificatesPdfs') {
					$conditions = array('customer_id IN'=>$approved_application_list); 
				} else {
					$conditions = ['customer_id IN' => $approved_application_list, 'status' => 'approved', 'current_level' => 'level_3'];
				}

			} else {
				$conditions = ['customer_id IS' => '', 'status' => 'approved', 'current_level' => 'level_3'];
			}


			$approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 
	
			$download_approved_application_list = $this->$table->find('all')->select(['customer_id'])->where($conditions)->order(['id' => 'DESC'])->toArray(); 
		}

		return array($approved_application_list,$download_approved_application_list);
	
	}





	// Approved Application Report Results
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function approvedApplicationReportResults($approved_application_list,$approved_application_type) {

		$date=array();
		$application_type=array();
		$application_user_email_id=array();
		$user_office=array();
		$application_customer_id=array();
		$name_of_the_firm=array();
		$address_of_the_firm=array();
		$contact_details_of_the_firm=array();
		$approved_TBL_details_tbl_name=array();
		$approved_TBL_details_tbl_registered_no=array();
		$laboratory_details_name=array();
		$laboratory_details_address=array();

		if (!empty($approved_application_list)) {

			$i=0;
			//applied array_unique function on 18-07-2019
			foreach (array_unique($approved_application_list) as $approved_application) {

				$approved_application_details = array(); //this line added on 18-07-2019

				// THIS BELOW CONDITION IS ADDED FOR THE ALL REPORTS BY AKASH ON 16-06-2022
				if ($approved_application_type == 'all_reports') {
					$approved_application_details = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$approved_application),'order' => array('id' => 'desc')))->first();
				} elseif ($approved_application_type == 'new' || $approved_application_type =='') {
					$approved_application_details = $this->DmiGrantCertificatesPdfs->find('all')->where(['customer_id' => $approved_application])->first(); 
				} elseif ($approved_application_type == 'renewal') {
					$approved_application_detail = $this->DmiGrantCertificatesPdfs->find('all')->select(['id'])->where(['customer_id IS'=>$approved_application])->combine('id','id')->toArray(); 
					//applied this condition on 27-04-2019
					if (!empty($approved_application_detail)) {
						$approved_application_details = $this->DmiGrantCertificatesPdfs->find('all')->where(['id IN'=> $approved_application_detail])->order(['id' => 'DESC'])->first(); 
					}
				}

				//this condition added on 18-07-2019
				if (!empty($approved_application_details)) {

					$approved_application_result = $approved_application_details;

					//to check if the application is old or not to print on the excel and for viewing part dont by Akash 07-04-2022

					// THIS BELOW CONDITION IS ADDED FOR THE ALL REPORTS BY AKASH ON 16-06-2022
					if ($approved_application_type == 'all_reports') {
			
						if ($approved_application_result['pdf_version'] > '1') {
							$approved_application_type_text[$i] = "<b>RENEWAL</b>";
						} elseif ($approved_application_result['user_email_id'] == 'old_application') {
							$approved_application_type_text[$i] = "<i>OLD</i>";
						} else {
							$approved_application_type_text[$i] = "NEW";
						}

					} else {

						if ($approved_application_type == 'renewal') {
							$approved_application_type_text[$i] = "<b>RENEWAL</b>";
						} elseif ($approved_application_result['user_email_id'] == 'old_application') {
							$approved_application_type_text[$i] = "<i>OLD</i>";
						} else {
							$approved_application_type_text[$i] = "NEW";
						} 

					}

				
					if ($approved_application_result['user_email_id'] == 'old_application') {
							$old_app_approved_by = $this->Customfunctions->old_app_approved_by($approved_application_result['customer_id']);
							$approved_application_result['user_email_id'] = $old_app_approved_by;
					}

					$explode = explode("/",$approved_application_result['customer_id']);

					$approved_office_id = $this->DmiRoOffices->find('all')->select(['ro_email_id'])->where(['short_code' => $explode[2]])->first();  

					$user_posted_office_id = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email'=>$approved_office_id['ro_email_id']])->first(); 

					if (!empty($user_posted_office_id)) {

						$user_office_details = $this->DmiRoOffices->find('all')->select(['ro_office'])->where(['id'=>$user_posted_office_id['posted_ro_office']])->first(); 

						if (!empty($user_office_details)) {
							$user_office[$i] = $user_office_details['ro_office'];
						} else {
							$user_office[$i] = 'N/A';
						}
					} else {
						$user_office[$i] = 'N/A';
					}

					$application_form_type = $this->Customfunctions->checkApplicantFormType($approved_application_result['customer_id']);

					if ($application_form_type == 'A') {
						$application_type[$i]='CA (Form-A)';
					} elseif ($application_form_type == 'B') {
						$application_type[$i]='Printing Press (Form-B)';
					} elseif ($application_form_type == 'C') {
						$application_type[$i]='Laboratory (Form-C)';
					} elseif ($application_form_type == 'D') {
						$application_type[$i]='Laboratory (Form-D)';
					} elseif ($application_form_type == 'E') {
						$application_type[$i]='CA (Form-E)';
					} elseif ($application_form_type == 'F') {
						$application_type[$i]='CA (Form-F)';
					}

					$date[$i] = $approved_application_result['created'];
					$application_customer_id[$i] = $approved_application_result['customer_id'];

					//added by the akash on 13-11-2021
					$firmDetails = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$approved_application_result['customer_id'])))->first();

					$name_of_the_firm[$i] = $firmDetails['firm_name'];
					$address_of_the_firm[$i] = $firmDetails['street_address'];
					$contact_details_of_the_firm[$i] = base64_decode($firmDetails['email']);
					$phoneno[$i] = $firmDetails['mobile_no'];

					//tbl details
					$tbl_details = $this->DmiAllTblsDetails->find('all',array('conditions'=>array('customer_id IS'=>$approved_application_result['customer_id'],'OR' => array('delete_status IS NULL', 'delete_status' => 'no'))))->toArray();

					if (!empty($tbl_details)) {
						$j=0;
						foreach ($tbl_details as $each) {

							$approved_TBL_details_tbl_name[$i][$j] = $each['tbl_name'];
							$approved_TBL_details_tbl_registered_no[$i][$j] = $each['tbl_registered_no'];
							$j++;
						}

					} else {
						$approved_TBL_details_tbl_name[$i][0] = 'N/A';
						$approved_TBL_details_tbl_registered_no[$i][0] = 'N/A';
					}



					//lab details
					$lab_details = $this->DmiCustomerLaboratoryDetails->find('all',array('conditions'=>array('customer_id IS'=>$approved_application_result['customer_id'])))->toArray();
					if (!empty($lab_details)) {
						$laboratory_details_name[$i] = $lab_details[0]['laboratory_name'];
						$laboratory_details_address[$i] = $lab_details[0]['street_address'];
					} else {
						$laboratory_details_name[$i] = 'N/A';
						$laboratory_details_address[$i] = 'N/A';
					}

					$commodity_value = $this->DmiFirms->find('all')->select(['sub_commodity'])->where(['customer_id'=>$approved_application_result['customer_id']])->first(); 

					$commodity_list[$i] = $this->Customfunctions->showCommdityInApplList($commodity_value['sub_commodity']);


					//Added this Code on the 08-04-2022 as the base encoding was not on some emails so to dispaly the email , checking if the encoding is needed or not.
					$checkEmailForEncoding[$i] = $approved_application_result['user_email_id'];

					if($this->isBase64Encoded($checkEmailForEncoding[$i]) == true){
						$application_user_email_id[$i] = base64_decode($approved_application_result['user_email_id']);
					} else {
						$application_user_email_id[$i] = $approved_application_result['user_email_id'];
					}
					
					//check the expiry dateand print to the reports  added by Akash on 24-05-2022 
					$grant_date = chop($approved_application_details['date'],"00:00:00");
					$valid_upto[$i] = $this->Customfunctions->getCertificateValidUptoDate($approved_application_result['customer_id'],$grant_date);

					//check the state name added by akash on 14-06-2022
					$state_name[$i] = $this->getStateName($approved_application_result['customer_id']);
					
					//Certificate Issued on
					$issued_on[$i] = chop($approved_application_result['date'],"00:00:00");

					$i=$i+1;
				}
			}

		} else {
			$commodity_list = '';
		}

		$this->set('date',$date);
		$this->set('application_customer_id',$application_customer_id);
		$this->set('application_user_email_id',$application_user_email_id);
		$this->set('application_type',$application_type);
		$this->set('user_office',$user_office);
		$this->set('approved_application_list',$approved_application_list);
		$this->set('commodity_list',$commodity_list);
		$this->set('approved_application_type',$approved_application_type_text);
		$this->set('name_of_the_firm',$name_of_the_firm);
		$this->set('address_of_the_firm',$address_of_the_firm);
		$this->set('contact_details_of_the_firm',$contact_details_of_the_firm);
		$this->set('approved_TBL_details_tbl_name',$approved_TBL_details_tbl_name);
		$this->set('approved_TBL_details_tbl_registered_no',$approved_TBL_details_tbl_registered_no);
		$this->set('laboratory_details_name',$laboratory_details_name);
		$this->set('laboratory_details_address',$laboratory_details_address);
		$this->set('valid_upto',$valid_upto);
		$this->set('state_name',$state_name);
		$this->set('phoneno',$phoneno);
		$this->set('issued_on',$issued_on);
	
	
	}





	// Download Approved Application Report Results
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function downloadApprovedApplicationReportResults($approved_application_list,$approved_application_type) {

		$this->viewBuilder()->setLayout('downloadpdf');

		$date=array();
		$application_type=array();
		$application_user_email_id=array();
		$user_office=array();
		$application_customer_id=array();

		if (!empty($approved_application_list)) {

			$i=0;
			foreach (array_unique($approved_application_list) as $approved_application)//applied array_unique function on 18-07-2019
			{
				$approved_application_details = array(); //this line added on 18-07-2019

				if ($approved_application_type == 'new' || $approved_application_type =='') {
					$approved_application_details = $this->DmiGrantCertificatesPdfs->find('all')->where(['customer_id' => $approved_application])->first(); 
				} elseif ($approved_application_type == 'renewal') {

					$approved_application_detail = $this->DmiGrantCertificatesPdfs->find('all')->select(['id'])->where(['customer_id' => $approved_application])->combine('id', 'id')->toArray(); 

					if (!empty($approved_application_detail)) {//applied this condition on 27-04-2019
						$approved_application_details = $this->DmiGrantCertificatesPdfs->find('all')->where(['id' => max($approved_application_detail)])->first(); 
					}
				}

				if (!empty($approved_application_details)) { //this condition added on 18-07-2019

					$approved_application_result = $approved_application_details;
					
					//to check if the application is old or not to print on the excel and for viewing part dont by Akash 07-04-2022
					if ($approved_application_type == 'renewal') {

						$approved_application_type_text[$i] = "Renewal";

					} elseif ($approved_application_result['user_email_id'] == 'old_application') {
						$approved_application_type_text[$i] = "Old";
					} else {
						$approved_application_type_text[$i] = "New";
					} 


					if ($approved_application_result['user_email_id'] == 'old_application') {
						$old_app_approved_by = $this->Customfunctions->old_app_approved_by($approved_application_result['customer_id']);
						$approved_application_result['user_email_id'] = $old_app_approved_by;
					}

					$explode = explode("/",$approved_application_result['customer_id']);

					$approved_office_id = $this->DmiRoOffices->find('all')->select(['ro_email_id'])->where(['short_code' => $explode[2]])->first(); 

					$user_posted_office_id = $this->DmiUsers->find('all')->select(['posted_ro_office'])->where(['email' => $approved_office_id['ro_email_id']])->first(); 

					if (!empty($user_posted_office_id)) {
						$user_office_details = $this->DmiRoOffices->find('all')->select(['ro_office'])->where(['id' => $user_posted_office_id['posted_ro_office']])->first(); 

						if (!empty($user_office_details)) {
							$user_office[$i] = $user_office_details['ro_office'];
						} else {
							$user_office[$i] = '--';
						}
					} else {
						$user_office[$i] = '--';
					}

					$application_form_type = $this->Customfunctions->checkApplicantFormType($approved_application_result['customer_id']);

					if ($application_form_type == 'A') {$application_type[$i]='CA (Form-A)';}
					elseif ($application_form_type == 'B') {$application_type[$i]='Printing Press (Form-B)';}
					elseif ($application_form_type == 'C') {$application_type[$i]='Laboratory (Form-C)';}
					elseif ($application_form_type == 'D') {$application_type[$i]='Laboratory (Form-D)';}
					elseif ($application_form_type == 'E') {$application_type[$i]='CA (Form-E)';}
					elseif ($application_form_type == 'F') {$application_type[$i]='CA (Form-F)';}

					$date[$i] = $approved_application_result['created'];
					$application_customer_id[$i] = $approved_application_result['customer_id'];

					//To get the Firm details to show in the excel this queries are added By Akash Thakre on 08-04-2022
					$firmDetails = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$approved_application_result['customer_id'])))->first();

					$name_of_the_firm[$i] = $firmDetails['firm_name'];
					$address_of_the_firm[$i] = $firmDetails['street_address'];
					$contact_details_of_the_firm[$i] = base64_decode($firmDetails['email']);

					//To get the TBL details like approved tbl name and registred no to print the Excel file added this below code by Akash Thakre on 08-04-2022
					$tbl_details = $this->DmiAllTblsDetails->find('all',array('conditions'=>array('customer_id IS'=>$approved_application_result['customer_id'],'delete_status IS NULL')))->toArray();

					if (!empty($tbl_details)) {
						$j=0;
						foreach ($tbl_details as $each) {

							$approved_TBL_details_tbl_name[$i][$j] = $each['tbl_name'];
							$approved_TBL_details_tbl_registered_no[$i][$j] = $each['tbl_registered_no'];
							$j++;
						}

					} else {
						$approved_TBL_details_tbl_name[$i][0] = '--';
						$approved_TBL_details_tbl_registered_no[$i][0] = '--';
					}


					//lab details
					$lab_details = $this->DmiCustomerLaboratoryDetails->find('all',array('conditions'=>array('customer_id IS'=>$approved_application_result['customer_id'])))->toArray();
					if (!empty($lab_details)) {
						$laboratory_details_name[$i] = $lab_details[0]['laboratory_name'];
						$laboratory_details_address[$i] = $lab_details[0]['street_address'];
					} else {
						$laboratory_details_name[$i] = '--';
						$laboratory_details_address[$i] = '--';
					}


					$commodity_value = $this->DmiFirms->find('all')->select(['sub_commodity'])->where(['customer_id' => $approved_application_result['customer_id']])->first(); 

					$commodity_list[$i] = $this->Customfunctions->showCommdityInApplList($commodity_value['sub_commodity']);


					//Added this Code on the 08-04-2022 as the base encoding was not on some emails so to dispaly the email , checking if the encoding is needed or not.
					$checkEmailForEncoding[$i] = $approved_application_result['user_email_id'];

					if($this->isBase64Encoded($checkEmailForEncoding[$i]) == true){
						$application_user_email_id[$i] = base64_decode($approved_application_result['user_email_id']);
					} else {
						$application_user_email_id[$i] = $approved_application_result['user_email_id'];
					}

					$i=$i+1;
				}
			}
		}

		$this->set('orders',$date);
		$this->set('application_id',$application_customer_id);
		$this->set('application_user_email_id',$application_user_email_id);
		$this->set('application_type',$application_type);
		$this->set('user_office',$user_office);
		$this->set('approved_application_list',$approved_application_list);
		$this->set('commodity_list',$commodity_list);
		$this->set('approved_application_type',$approved_application_type_text);
		$this->set('name_of_the_firm',$name_of_the_firm);
		$this->set('address_of_the_firm',$address_of_the_firm);
		$this->set('contact_details_of_the_firm',$contact_details_of_the_firm);
		$this->set('approved_TBL_details_tbl_name',$approved_TBL_details_tbl_name);
		$this->set('approved_TBL_details_tbl_registered_no',$approved_TBL_details_tbl_registered_no);
		$this->set('laboratory_details_name',$laboratory_details_name);
		$this->set('laboratory_details_address',$laboratory_details_address);

		$this->layout = null;
		$this->autoLayout = false;
		Configure::write('debug', '0');
		$this -> render('/element/download_report_excel_format/download_approved_application_report_results');
	
	
	}





	
	// Search User Id
	// Description : Function to find user email id from user name(ID)
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 12-09-2017


	public function search_user_id($user_name_details,$search_user_id) {

		if (!empty($user_name_details[$search_user_id])) {
			$search_user_id = $user_name_details[$search_user_id];
			$search_user_id = explode('(',$search_user_id);
			$search_user_id = rtrim($search_user_id[1],')');
			return $search_user_id;
		} else {
			$this->redirect('/');
			$this->Session->destroy();
		}
	}





	// User Name List
	// Description : Function to combine user name and user email id and create user name(ID) value
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration) / Amol Choudhari (Modification)
	// Date : 12-09-2017 (C) | 06-01-2022 (M)

	public function userNameList() {
		
		//updated query on 06-01-2022 by Amol
		$users_name_list = $this->DmiUsers->find('all',array('fields'=>array('id','f_name', 'l_name', 'email'),'conditions'=>array('status'=>'active','OR'=>array('division IN'=>array('BOTH','DMI'))),'order'=>'f_name ASC'))->toArray();
		$user_name_details = array();
		foreach ($users_name_list as $value) {

			$user_name_details[$value['id']] = $value['f_name'].' '.$value['l_name'].' ('.base64_decode($value['email']) . ')';
		}

		return $user_name_details;
	}



		
	// Date Comparison
	// Description : Function to check comparison between from date and To date
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration) / Amol Choudhari (Modification)
	// Date : 13-09-2017

	public function date_comparison($from_date,$to_date) {

		$from_date = strtotime(str_replace('/','-',$from_date));
		$to_date  = strtotime(str_replace('/','-',$to_date));

		// set variables to show popup messages from view file
		$message = '';
		$redirect_to = '';

		if ($from_date <= $to_date) {
			return true;
		} else {
			$message = 'Invalid Date Range Selection';
			$redirect_to = 'report_types';
			$this->view = '/element/message_boxes';
		}

		// set variables to show popup messages from view file
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);
	}





	// Show District Dropdown
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : ----
	// Date : ----

	public function showDistrictDropdown() {
		$this->autoRender = false;
		$state_id = $this->request->getData('state');

		// Change on 3/11/2018 -  add order by condition - by Pravin Bhakare
		$districts = $this->DmiDistricts->find('all')->select(['id','district_name'])->where(['state_id' => $state_id])->order(['district_name' => 'ASC'])->toArray(); 

		?><option value="">All</option><?php
		foreach ($districts as $district) { ?>
			<option value="<?php echo $district['id']?>"><?php echo $district['district_name']?></option>
		<?php	}
	}





	// Pending Application Report User Id
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function pendingApplicationReportUserId() {

		$this->autoRender = false;
		$table = 'DmiRoOffices';

		$user_role = $_POST['user_role'];
		$ro_offices = $_POST['ro_offices'];
		$mo_offices = $_POST['mo_offices'];
		$io_offices = $_POST['io_offices'];

		if ($ro_offices != null) {
			$ro_offices = explode(',', $ro_offices);
			?><option value="<?php echo ''; ?>"><?php echo 'All'; ?></option><?php

			foreach ($ro_offices as $office_id) {

				$user_email_details = $this->DmiUsers->find('all')->select(['email'])->where(['posted_ro_office' => $office_id])->order(['email' => 'ASC'])
					->extract('email')->toList(); 

				$i=0;
				foreach ($user_email_details as $user_email_id) {
					$user_email_details = $this->DmiUserRoles->find('all')->select(['id', 'user_email_id'])->where(['user_email_id' => $user_email_id, 'ro_inspection' => 'yes'])->first(); 
					if (!empty($user_email_details)) {
						$user_email_details_xy[$i] = $user_email_details;
						?><option value="<?php echo $user_email_details_xy[$i]['id']; ?>"><?php echo base64_decode($user_email_details_xy[$i]['user_email_id']); ?></option><?php
						$i=$i+1;
					}
				}
			}

		} elseif ($mo_offices != null) {

			$mo_offices = explode(',', $mo_offices);
			?><option value="<?php echo ''; ?>"><?php echo 'All'; ?></option><?php

			foreach ($mo_offices as $office_id) {

				$user_email_details = $this->DmiUsers->find('all')->select(['email'])->where(['posted_ro_office' => $office_id])->order(['email' => 'ASC'])
					->extract('email')->toList(); 

				$i=0;
				foreach ($user_email_details as $user_email_id) {

					$user_email_details = $this->DmiUserRoles->find('all')->select(['id', 'user_email_id'])->where(['user_email_id' => $user_email_id, 'mo_smo_inspection' => 'yes'])->first(); 
					if (!empty($user_email_details)) {
						$user_email_details_xy[$i] = $user_email_details;
						?><option value="<?php echo $user_email_details_xy[$i]['id']; ?>"><?php echo base64_decode($user_email_details_xy[$i]['user_email_id']); ?></option><?php
						$i=$i+1;
					}
				}
			}
			
		} elseif ( $io_offices != null) {

			$io_offices = explode(',',$io_offices);
			?><option value="<?php echo ''; ?>"><?php echo 'All'; ?></option><?php

			foreach ($io_offices as $office_id) {

				$user_email_details = $this->DmiUsers->find('all')->select(['email'])->where(['posted_ro_office' => $office_id])->order(['email' => 'ASC'])
					->extract('email')->toList(); 

				$i=0;
				foreach ($user_email_details as $user_email_id) {

					$user_email_details = $this->DmiUserRoles->find('all')->select(['id', 'user_email_id'])->where(['user_email_id' => $user_email_id, 'io_inspection' => 'yes'])->first(); 

					if (!empty($user_email_details)) {
						$user_email_details_xy[$i] = $user_email_details;
						?><option value="<?php echo $user_email_details_xy[$i]['id']; ?>"><?php echo base64_decode($user_email_details_xy[$i]['user_email_id']); ?></option><?php
						$i=$i+1;
					}
				}
			}

		} elseif ($user_role == 'HO MO/SMO' || $user_role == 'DY.AMA' || $user_role == 'JT.AMA' || $user_role == 'AMA') {

			if ($user_role == 'HO MO/SMO') {
				$office_id='ho_mo_smo';
			} elseif ($user_role == 'DY.AMA') {
				$office_id='dy_ama';
			} elseif ($user_role == 'JT.AMA') {
				$office_id='jt_ama';
			} else {
				$office_id='ama';
			}

			$user_email_id = $this->DmiUserRoles->find('all')->select(['id', 'user_email_id'])->where([$office_id => 'yes'])->toArray(); 
			//added this for loop to show list of users on 05-07-2019 by Amol
			foreach ($user_email_id as $each) {
				?><option value="<?php echo $each['id']; ?>"><?php echo base64_decode($each['user_email_id']); ?></option><?php
			}
		} elseif ($user_role == 'RO/SO' || $user_role == 'MO/SMO' || $user_role == 'IO' || $user_role == 'null_value') {
			?><option value="<?php echo ''; ?>"><?php echo 'All'; ?></option><?php
		} else{
			?><option value="<?php echo ''; ?>"><?php echo 'All'; ?></option><?php
		}
		exit;
	
	}





	// Check User Role From Current Level
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function checkUserRoleFromCurrentLevel($current_level,$user_email_id) {

		$user_roles = '--';
		if ($current_level == 'level_3') {
			$user_roles = 'RO/SO';
		} elseif ($current_level == 'level_1') {
			$user_roles ='MO/SMO';
		} elseif ($current_level == 'level_2') {
			$user_roles ='IO';
		} elseif ($current_level == 'applicant') {
			$user_roles ='Applicant';
		} elseif ($current_level == 'pao') {
			$user_roles ='PAO/DDO';
		} elseif ($current_level == 'level_4') {
			
			$check_roles = $this->DmiUserRoles->find('all')->where(['user_email_id IS' => $user_email_id])->first();
		
			if (!empty($check_roles)) {
				$user_list = $check_roles;
				
				if ($user_list['dy_ama'] == 'yes') {
					$user_roles ='DY.AMA';
				} elseif ($user_list['jt_ama'] == 'yes') {
					$user_roles ='JT.AMA';
				} elseif ($user_list['ama'] == 'yes') {
					$user_roles ='AMA';
				} elseif ($user_list['ho_mo_smo'] == 'yes') {
					$user_roles='HO MO/SMO';
				} else {
					$user_roles='--';
				}
			}
		}

		return $user_roles;
	}





	// Newly Added Firm List Report
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function newlyAddedFirmListReport() {

		$application_type_array = array('A'=>'CA (Form-A)','C'=>'Laboratory (Form-C)','E'=>'CA (Form-E)','B'=>'Printing Press (Form-B)','D'=>'Laboratory (Form-D)','F'=>'CA (Form-F)');

		//Change on 9/11/2018, Sorting array by ascending order - By Pravin Bhakare
		asort($application_type_array);
		$this->set('application_type_array',$application_type_array);

		// Apply "Order by" clause to get state list by order wise (Done By Pravin 10-01-2018)
		$states = $this->DmiStates->find('all')->select(['id', 'state_name'])->where(['OR' => [['delete_status IS' => null], ['delete_status IS' => 'no']]])
			->order(['state_name' => 'ASC'])->combine('id', 'state_name')->toArray(); 
		$this->set('states',$states);

		// Change on 05/11/2018, Get list of all district - By Pravin Bhakare
		$all_district_name = $this->DmiDistricts->find('all')->select(['id', 'district_name'])->where(['OR' => [['delete_status IS' => null], ['delete_status ='=>'no']]])
			->order(['district_name' => 'ASC'])->combine('id', 'district_name')->toArray(); 
		$this->set('all_district_name', $all_district_name);

		$company_id = null;
		$application_type = null;
		$state = null;
		$district = null;
		$search_from_date = null;
		$search_to_date = null;

		$application_type = $this->Session->read('application_type');
		$company_id = $this->Session->read('company_id');
		$state = $this->Session->read('state');
		$district = $this->Session->read('district');
		$search_from_date = $this->Session->read('search_from_date');
		$search_to_date = $this->Session->read('search_to_date');

		$download_application_type = $this->Session->read('application_type');
		$download_company_id = $this->Session->read('company_id');
		$download_state = $this->Session->read('state');
		$download_district = $this->Session->read('district');
		$download_search_from_date = $this->Session->read('search_from_date');
		$download_search_to_date = $this->Session->read('search_to_date');

		$search_flag = 'off'; // added by Ankur
		$download_report = 'no';

		if (null != ($this->request->getData('search_logs')) || null != ($this->request->getData('download_report'))) {
			$search_flag = 'on'; // added by Ankur

			//Check not empty "Download Report as Excel" button Request, if condition TRUE then set value "yes" for "Download Report as Excel" click event
			//(Done by pravin 14-03-2018)
			if (!empty($this->request->getData('download_report'))) {
				$download_report = 'yes';
			}

			$company_id = htmlentities($this->request->getData('company_id'), ENT_QUOTES);
			$application_type = $this->request->getData('application_type');

			$table = 'DmiStates';
			$post_input_request = $this->request->getData('state');
			if (!empty($post_input_request)) {
				$state = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request); // calling library function
			} else {
				$state = '';
			}

			$table = 'DmiDistricts';
			$post_input_request = $this->request->getData('district');
			if (!empty($post_input_request)) {
				$district = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request); // calling library function
			} else {
				$district = '';
			}

			$download_firms_data_details_result = $this->DmiFirms->newlyAddedFirmListReportConditions($download_application_type,$download_company_id,$download_state,$download_district,$download_search_from_date,$download_search_to_date,$search_flag);

			$search_from_date =  $this->request->getData('from_date');
			$search_from_date = $this->Customfunctions->dateFormatCheck($search_from_date);
			$search_to_date =  $this->request->getData('to_date');
			$search_to_date = $this->Customfunctions->dateFormatCheck($search_to_date);
			$this->date_comparison($search_from_date,$search_to_date);

			$this->Session->delete('application_type');	$this->Session->delete('company_id');
			$this->Session->delete('state'); $this->Session->delete('district');
			$this->Session->delete('search_from_date'); $this->Session->delete('search_to_date');

			$this->Session->write('application_type',$application_type); $this->Session->write('company_id',$company_id);
			$this->Session->write('search_from_date',$search_from_date); $this->Session->write('search_to_date',$search_to_date);
			$this->Session->write('state',$state); $this->Session->write('district',$district);

			$firms_data_details_result = $this->DmiFirms->newlyAddedFirmListReportConditions($application_type,$company_id,$state,$district,$search_from_date,$search_to_date,$search_flag);
			$this->newlyAddedFirmReportResult($firms_data_details_result,$application_type_array);

			if ($download_report == 'yes') {
				$this->downloadNewlyAddedFirmReportResult($download_firms_data_details_result,$application_type_array);
			}
		
		} else {
		
			$firms_data_details_result = $this->DmiFirms->newlyAddedFirmListReportConditions($application_type,$company_id,$state,$district,$search_from_date,$search_to_date,$search_flag);
			$this->newlyAddedFirmReportResult($firms_data_details_result,$application_type_array);
		}

		$this->set('application_type',$application_type);
		$this->set('state',$state); $this->set('company_id',$company_id);
		$this->set('district',$district); $this->set('search_from_date',$search_from_date);	 $this->set('search_to_date',$search_to_date);
	}



	// Newly Added Firm Report Result
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function newlyAddedFirmReportResult($final_customer_id_list,$application_type_array) {

		$firms_data_details = array();
		$application_type_name = array();
		$districts = array();
		$states = array();

		if (!empty($final_customer_id_list)) {

			$final_result_firm_data = $this->DmiFirms->find('all')->select(['id', 'customer_primary_id', 'firm_name', 'certification_type', 'state', 'district',
				'created', 'customer_id'])->where(['customer_id IN'=>$final_customer_id_list])->order(['created' => 'DESC'])->group(['id', 'customer_primary_id'])
				->toArray(); 

			$i = 0;

			foreach ($final_result_firm_data as $firms_data) {

				$firms_data_details[$i] = $firms_data;
				$application_form_type = $this->Customfunctions->checkApplicantFormType($firms_data['customer_id']);
				$application_type_name[$i] = $application_type_array[$application_form_type];
				$districts[$i] = $this->DmiDistricts->find('all')->select(['district_name'])->where(['id' => $firms_data['district']])->extract('district_name')->first(); 
				$states[$i] = $this->DmiStates->find('all')->select(['state_name'])->where(['id' => $firms_data['state']])->extract('state_name')->first(); 
				$i=$i+1;
			}
		}

		$this->set('firms_data_details',$firms_data_details);
		$this->set('application_type_name',$application_type_name);
		$this->set('firms_districts',$districts);
		$this->set('firms_states',$states);
	
	}




	// Download Newly Added Firm Report Result
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function downloadNewlyAddedFirmReportResult($final_customer_id_list,$application_type_array) {

		$this->viewBuilder()->setLayout('downloadpdf');

			$firms_data_details = array();
			$application_type_name = array();
			$districts = array();
			$states = array();

			if (!empty($final_customer_id_list)) {
				$conditions = ['customer_id IN' => $final_customer_id_list];
			} else {
				$conditions = ['customer_id IS' => ''];
			}

			if (!empty($final_customer_id_list)) {

				$final_result_firm_data = $this->DmiFirms->find('all')->select(['id', 'customer_primary_id', 'firm_name', 'certification_type', 'state', 'district',
				'created', 'customer_id'])->where($conditions)->group(['id', 'customer_primary_id'])->order(['created' => 'DESC'])->toArray();

				$i = 0;
				foreach ($final_result_firm_data as $firms_data) {

					$firms_data_details[$i] = $firms_data;
					$application_form_type = $this->Customfunctions->checkApplicantFormType($firms_data['customer_id']);
					$application_type_name[$i] = $application_type_array[$application_form_type];
					$districts[$i] = $this->DmiDistricts->find('all')->select(['district_name'])->where(['id' => $firms_data['district']])->extract('district_name')->first(); 
					$states[$i] = $this->DmiStates->find('all')->select(['state_name'])->where(['id' => $firms_data['state']])->extract('state_name')->first(); 
					$i=$i+1;
				}
			}

			$this->set('orders',$firms_data_details);
			$this->set('application_type_name',$application_type_name);
			$this->set('firms_districts',$districts);
			$this->set('firms_states',$states);

			$this->layout = null;
			$this->autoLayout = false;
			Configure::write('debug', '0');
			$this -> render('/element/download_report_excel_format/download_newly_added_firm_report_result');
	}




	// Primary User Details Report
	// Description : Find primary user details with his added firms details
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function primaryUserDetailsReport() {

		$application_type_array = array('A'=>'CA (Form-A)','C'=>'Laboratory (Form-C)','E'=>'CA (Form-E)','B'=>'Printing Press (Form-B)','D'=>'Laboratory (Form-D)','F'=>'CA (Form-F)');

		// Change on 9/11/2018, Add order by conditions , By Pravin Bhakare
		$all_states = $this->DmiStates->find('all')->select(['id', 'state_name'])->where(['OR'=> [['delete_status IS' => null], ['delete_status ='=>'no']]])
			->order(['state_name' => 'ASC'])->combine('id', 'state_name')->toArray(); 

		$all_district = $this->DmiDistricts->find('all')->select(['id', 'district_name'])->where(['OR'=> [['delete_status IS' => null], ['delete_status ='=>'no']]])
			->combine('id', 'district_name')->toArray(); 

		$this->set('all_states', $all_states);
		$this->set('all_district', $all_district);

		$state = null;
		$district = null;
		$search_from_date = null;
		$search_to_date = null;
		$primary_firms_details = array();
		$certification_type = array();
		$primary_user_details = array();

		$state = $this->Session->read('state');
		$district = $this->Session->read('district');
		$search_from_date = $this->Session->read('search_from_date');
		$search_to_date = $this->Session->read('search_to_date');

		$download_state = $this->Session->read('state');
		$download_district = $this->Session->read('district');
		$download_search_from_date = $this->Session->read('search_from_date');
		$download_search_to_date = $this->Session->read('search_to_date');

		$search_flag = 'off'; // added by Ankur
		$download_report = 'no';

		if (null != ($this->request->getData('search_logs')) || null != ($this->request->getData('download_report'))) {

			$search_flag = 'on'; // added by Ankur
			//Check not empty "Download Report as Excel" button Request, if condition TRUE then set value "yes" for "Download Report as Excel" click event
			//(Done by pravin 14-03-2018)

			if (!empty($this->request->getData('download_report'))) {
				$download_report = 'yes';
			}

			$table = 'DmiStates';
			$post_input_request = $this->request->getData('state');

			if (!empty($post_input_request)) {
				$state = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request); // calling library function
			} else {
				$state = '';
			}

			$table = 'DmiDistricts';
			$post_input_request = $this->request->getData('district');
			if (!empty($post_input_request)) {
				$district = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request);//calling library function
			} else {
				$district = '';
			}

			$search_from_date =  $this->request->getData('from_date');
			$search_from_date = $this->Customfunctions->dateFormatCheck($search_from_date);
			$search_to_date =  $this->request->getData('to_date');
			$search_to_date = $this->Customfunctions->dateFormatCheck($search_to_date);
			$this->date_comparison($search_from_date, $search_to_date);

			$download_primary_user_ids = $this->DmiCustomers->primaryUserDetailsReportConditions($download_state,$download_district,$download_search_from_date,$download_search_to_date,$search_flag);

			if ($download_report == 'yes') {
				$this->downloadPrimaryUserDetailsReport($download_primary_user_ids, $all_states, $all_district, $application_type_array);
			}

			$this->Session->delete('state');
			$this->Session->delete('district');
			$this->Session->delete('search_from_date');
			$this->Session->delete('search_to_date');

			$this->Session->write('search_from_date', $search_from_date);
			$this->Session->write('search_to_date',$search_to_date);
			$this->Session->write('state',$state);
			$this->Session->write('district',$district);

			$primary_user_ids = $this->DmiCustomers->primaryUserDetailsReportConditions($state,$district,$search_from_date,$search_to_date,$search_flag);
		} else {
			$primary_user_ids = $this->DmiCustomers->primaryUserDetailsReportConditions($state,$district,$search_from_date,$search_to_date,$search_flag);
		}

		if (!empty($primary_user_ids)) {

			$user_id_list = $primary_user_ids;

			if (!empty($user_id_list)) {
				$primary_user_details = $this->DmiCustomers->find('all')->select(['id', 'district', 'state', 'created', 'customer_id'])
					->where(['customer_id IN' => $user_id_list])->order(['id' => 'DESC'])->toArray(); 
			} else {
				$primary_user_details = $this->DmiCustomers->find('all')->select(['id', 'district', 'state', 'created', 'customer_id'])
					->where(['customer_id IS' => ''])->order(['id' => 'DESC'])->toArray(); 
			}

		}

		
		$this->set('primary_user_details', $primary_user_details);
		$this->set('state', $state);
		$this->set('search_from_date', $search_from_date);
		$this->set('search_to_date', $search_to_date);	  // Change on 5/11/2018 , Set search_to_date value , By Pravin Bhakare
		$this->set('district', $district);
	
	}




	// Primary Firm Details Report
	// Description :  to fetch firm details by ajax call
	// @Author : Ankur Jangid
	// #Contributer :  ----
	// Date : ----

	public function primaryFirmDetailsReport() {

		$this->autoRender = false;

		$application_type_array = array('A'=>'CA (Form-A)','C'=>'Laboratory (Form-C)','E'=>'CA (Form-E)','B'=>'Printing Press (Form-B)','D'=>'Laboratory (Form-D)','F'=>'CA (Form-F)');
		//Change on 9/11/2018, Sorting array by ascending order - By Pravin Bhakare
		asort($application_type_array);

		$all_states = $this->DmiStates->find('all')->select(['id', 'state_name'])->where(['OR'=> [['delete_status IS' => null], ['delete_status ='=>'no']]])
			->order(['state_name' => 'ASC'])->combine('id', 'state_name')->toArray(); 

		$all_district = $this->DmiDistricts->find('all')->select(['id', 'district_name'])->where(['OR'=> [['delete_status IS' => null], ['delete_status ='=>'no']]])
			->combine('id', 'district_name')->toArray(); 

		$customer_id = $this->request->getData('data');

		$primary_firm_details = $this->DmiFirms->find('all')->select(['id', 'customer_primary_id', 'firm_name', 'certification_type', 'state', 'district',
				'created', 'customer_id'])->where(['customer_primary_id IS' => $customer_id])->order(['created'=>'DESC'])->toArray(); 

		if (!empty($primary_firm_details)) {
			$primary_firms_details = $primary_firm_details;

			$j = 0;
			foreach ($primary_firm_details as $certification_id) {
				$certification_type[$j] = $this->Customfunctions->checkApplicantFormType($certification_id['customer_id']);
				$j = $j+1;
			}

			for($i = 0; $i<sizeof($primary_firm_details); $i++) {

				$firm_id[$i] = $primary_firm_details[$i]['customer_id'];
				$firm_name[$i] = $primary_firm_details[$i]['firm_name'];
				$application_type[$i] = $application_type_array[$certification_type[$i]];
				$firm_state[$i] = $all_states[$primary_firm_details[$i]['state']];
				$firm_district[$i] = $all_district[$primary_firm_details[$i]['district']];
				$firm_time[$i] = $primary_firm_details[$i]['created'];
				$firms_details[$i] = ['customer_id'=>$firm_id[$i] ,'firm_name'=>$firm_name[$i] ,'certification_type'=>$application_type[$i] ,
										'state'=>$firm_state[$i] ,'district'=>$firm_district[$i] ,'created'=>$firm_time[$i]];
			}

			$response_data = [$firms_details];
		} else {
			$primary_firms_details = [];
			$response_data = $primary_firms_details;
		}

		echo json_encode($response_data);
		exit;
	}




	// Download Primary User Details Report
	// Description :  ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function downloadPrimaryUserDetailsReport($primary_user_ids, $all_states, $all_district, $application_type_array) {

		$this->viewBuilder()->setLayout('downloadpdf');

		$primary_firms_details = array();
		$certification_type = array();
		$primary_user_details = array();

		if (!empty($primary_user_ids)) {

			$i = 0;
			foreach ( $primary_user_ids as $user_id ) {
				$user_id_list[$i] = $user_id;
				$i=$i+1;
			}

			if(!empty($user_id_list)){
				$primary_user_details = $this->DmiCustomers->find('all')->where(['customer_id IN' => $user_id_list])->order(['id' => 'DESC'])->toArray(); 
			}

			$i = 0;

			foreach ($primary_user_details as $user_details) {

				$primary_firm_details = $this->DmiFirms->find('all')->where(['customer_primary_id' => $user_details['customer_id']])->toArray(); 

				if (!empty($primary_firm_details)) {

					$primary_firms_details[$i] = $primary_firm_details;
					$j = 0;

					foreach ($primary_firm_details as $certification_id) {

						$certification_type[$i][$j] = $this->Customfunctions->checkApplicantFormType($certification_id['customer_id']);
						$j = $j+1;
					}
				} else {
					$primary_firms_details[$i] = array();
				}

				$i=$i+1;
			}
		}

		$this->set('certification_type', $certification_type);
		$this->set('primary_firms_details', $primary_firms_details);
		$this->set('primary_user_details', $primary_user_details);
		$this->set('all_states', $all_states);
		$this->set('all_district', $all_district);
		$this->set('application_type_array', $application_type_array);

		$this->layout = null;
		$this->autoLayout = false;
		Configure::write('debug', '0');
		$this -> render('/element/download_report_excel_format/download_primary_user_details_report');
	
	}


	

	// Renewal Due Application Report
	// Description :  Find renewal due applications
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : ----

	public function renewalDueApplicationReport() {

		// Change on 9/11/2018, Add order by conditions , By Pravin Bhakare
		$all_states = $this->DmiStates->find('all')->select(['id', 'state_name'])->where(['OR' => [['delete_status IS' => null], ['delete_status ='=>'no']]])
			->order(['state_name' => 'ASC'])->combine('id', 'state_name')->toArray(); 

		$all_district = $this->DmiDistricts->find('all')->select(['id', 'district_name'])->where(['OR' => [['delete_status IS' => null], ['delete_status ='=>'no']]])
			->combine('id', 'district_name')->toArray(); 

		$all_application_type = $this->DmiCertificateTypes->find('all')->select(['id', 'certificate_type'])->combine('id', 'certificate_type')->toArray(); 

		$this->set('all_states',$all_states);
		$this->set('all_district',$all_district);
		$this->set('all_application_type',$all_application_type);

		$renewal_due_applications_id = array();
		$application_expiry_date = array();
		$state = '';
		$district = '';
		$renewal_user_details = array();
		$application_type = '';

		$dropdown_year_list = date('Y');

		for($i=0; $i<=6; $i++) {
			$dropdown_years[$i] = $dropdown_year_list;
			$dropdown_year_list = $dropdown_year_list+1;
		}
			
		$this->set('dropdown_years',$dropdown_years);

		$renewal_year = $this->Session->read('renewal_year');

		if (empty($renewal_year)) {
			$renewal_year = date('Y');
		}

		$state = $this->Session->read('state');
		$district = $this->Session->read('district');
		$application_type = $this->Session->read('application_type');

		$download_renewal_year = $renewal_year;
		$download_state = $this->Session->read('state');
		$download_district = $this->Session->read('district');
		$download_application_type = $this->Session->read('application_type');

		$search_flag = 'off'; // added by Ankur
		$download_report = 'no';

		if (null != ($this->request->getData('search_logs')) || null != ($this->request->getData('download_report'))) {

			$search_flag = 'on';
			//Check not empty "Download Report as Excel" button Request, if condition TRUE then set value "yes" for "Download Report as Excel" click event
			//(Done by pravin 14-03-2018)
			if (!empty($this->request->getData('download_report'))) {
				$download_report = 'yes';
			}

			$renewal_year = $dropdown_years[$this->request->getData('year')];
			$table = 'DmiStates';
			$post_input_request = $this->request->getData('state');

			if (!empty($post_input_request)) {
				$state = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request); // calling library function
			} else {
				$state = '';
			}

			$table = 'DmiDistricts';
			$post_input_request = $this->request->getData('district');

			if (!empty($post_input_request)) {
				$district = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
			} else {
				$district = '';
			}

			$table = 'DmiCertificateTypes';
			$post_input_request = $this->request->getData('application_type');

			if (!empty($post_input_request)) {
				$application_type = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
			} else {
				$application_type = '';
			}

			$download_final_customer_id = $this->DmiGrantCertificatesPdfs->renewalDueReportConditions($download_renewal_year,$download_state,$download_district,$download_application_type);

			if ($download_report == 'yes') {
				$this->downloadRenewalDueApplicationReport($download_final_customer_id,$all_states,$all_district,$all_application_type);
			}

			$this->Session->delete('state');
			$this->Session->delete('district');
			$this->Session->delete('renewal_year');
			$this->Session->delete('application_type');

			$this->Session->write('renewal_year',$renewal_year);
			$this->Session->write('application_type',$application_type);
			$this->Session->write('state',$state);
			$this->Session->write('district',$district);

			$final_customer_id = $this->DmiGrantCertificatesPdfs->renewalDueReportConditions($renewal_year,$state,$district,$application_type);
		
		} else {
			$final_customer_id = $this->DmiGrantCertificatesPdfs->renewalDueReportConditions($renewal_year,$state,$district,$application_type);
		}

		if (!empty($final_customer_id)) {

			if (null != ($this->request->getData('search_logs')) || null != ($this->request->getData('download_report'))) {
				$renewal_user_details = $this->DmiFirms->find('all')->where(['customer_id IN' => $final_customer_id])->order(['id', 'certification_type'])->toArray(); 
			} else {
				$renewal_user_details = $this->DmiFirms->find('all')->where(['customer_id IN' => $final_customer_id])->order(['id', 'certification_type'])->limit(['100'])->toArray(); 
			}


			$i=0;
			foreach ($renewal_user_details as $application_id) {

				$granted_application_id = $this->DmiGrantCertificatesPdfs->find('all')->select(['id'])->where(['customer_id' => $application_id['customer_id']])->combine('id', 'id')->toArray(); 

				$granted_application_details = $this->DmiGrantCertificatesPdfs->find('all')->select(['customer_id', 'date'])->where(['id' => max($granted_application_id)])->first(); 

				$application_renewal_date = $this->Customfunctions->getCertificateValidUptoDate($granted_application_details['customer_id'],$granted_application_details['date']);

				$application_expiry_date[$i] = $application_renewal_date;
				$renewal_due_applications_id[$i] = $granted_application_details;
				$i=$i+1;
			}
		}

		$this->set('renewal_user_details',$renewal_user_details);
		$this->set('renewal_due_applications_id',$renewal_due_applications_id);
		$this->set('application_expiry_date',$application_expiry_date);

		$this->set('renewal_year',$renewal_year);
		$this->set('state',$state);
		$this->set('district',$district);
		$this->set('application_type',$application_type);

	}



	// Download Renewal Due Application Report
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 5/11/2018

	public function downloadRenewalDueApplicationReport($download_final_customer_id,$all_states,$all_district,$all_application_type) {

		$this->viewBuilder()->setLayout('downloadpdf');
		
		// Change on 5/11/2018, Set values for download report , By Pravin Bhakare 5/11/2018
		$renewal_user_details = '';
		$renewal_due_applications_id = [];
		$application_expiry_date = [];

		if (!empty($download_final_customer_id)) {

			if (!empty($download_final_customer_id)) {
				$condition = ['customer_id IN'=>$download_final_customer_id];
			} else {
				$condition = ['customer_id IS'=>''];
			}

			$renewal_user_details = $this->DmiFirms->find('all')->select(['id','customer_id', 'certification_type', 'state', 'district'])->where($condition)->order(['id', 'certification_type'])->toArray(); 

			$i=0;
			foreach ($renewal_user_details as $application_id) {

				$granted_application_id = $this->DmiGrantCertificatesPdfs->find('all')->select(['id'])->where(['customer_id'=>$application_id['customer_id']])->combine('id', 'id')->toArray(); 

				// $granted_application_details = $this->Dmi_grant_certificates_pdf->find('first',array('conditions'=>array('id'=>max($granted_application_id))));
				$granted_application_details = $this->DmiGrantCertificatesPdfs->find('all')->select(['customer_id', 'date'])->where(['id'=>max($granted_application_id)])->first();

				$application_renewal_date = $this->Customfunctions->getCertificateValidUptoDate($granted_application_details['customer_id'],$granted_application_details['date']);

				$application_expiry_date[$i] = $application_renewal_date;
				$renewal_due_applications_id[$i] = $granted_application_details;
				$i=$i+1;
			}
		}

		$this->set('renewal_user_details',$renewal_user_details);
		$this->set('renewal_due_applications_id',$renewal_due_applications_id);
		$this->set('application_expiry_date',$application_expiry_date);
		$this->set('all_states',$all_states);
		$this->set('all_district',$all_district);
		$this->set('all_application_type',$all_application_type);

		$this->layout = null;
		$this->autoLayout = false;
		Configure::write('debug', '0');
		$this -> render('/element/download_report_excel_format/download_renewal_due_application_report');
	
	}





	// Payment Details Report
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 27-07-2018  

	public function paymentDetailsReport() {

		$connection = ConnectionManager::get('default');

		$report_for_array = array('both'=>'BOTH (New,Renewal)','new'=>'New','renewal'=>'Renewal');

		$all_states = $this->DmiStates->find('all')->select(['id', 'state_name'])->where(['OR' => [['delete_status IS NULL'] ,['delete_status ='=>'no']]])
			->order(['state_name'])->combine('id', 'state_name')->toArray(); 

		$all_district = $this->DmiDistricts->find('all')->select(['id', 'district_name'])->where(['OR' => [['delete_status IS NULL'], ['delete_status ='=>'no']]])
			->combine('id', 'district_name')->toArray(); 

		$all_application_type = $this->DmiCertificateTypes->find('all')->select(['id', 'certificate_type'])->combine('id', 'certificate_type')->toArray();  

		//added 'office_type'=>'RO' condition on 27-07-2018          // Change on 5/11/2018, Add order by conditions , By Pravin Bhakare
		// Change on 9/11/2018, Add order by conditions , By Pravin Bhakare

		$all_ro_office = $this->DmiRoOffices->find('all')->select(['id', 'ro_office'])->where([['delete_status IS NULL'],'OR' => [['office_type' => 'RO'], ['office_type' => 'SO']]])
			->order(['ro_office'])->combine('id', 'ro_office')->toArray(); 

		$this->set('report_for_array',$report_for_array);
		$this->set('all_ro_office',$all_ro_office);
		$this->set('all_states',$all_states);
		$this->set('all_district',$all_district);
		$this->set('all_application_type',$all_application_type);

		// Change on 5/11/2018, set search_to_date value, By Pravin 5/11/2018
		$application_type = '';
		$ro_office='';
		$state='';
		$district='';
		$search_from_date='';
		$search_to_date='';

		$firm_customer_id_list ='';
		$grant_total =array();
		$total_payment_details =array();
		$ro_id =array();
		$firms_details =array();
		$customer_payment_details =array();
		$payment_max_id =array();
		$ca_application_payment_total =array();
		$printing_application_payment_total =array();
		$laboratory_application_payment_total =array();
		$ca_payment = null; // $ca_payment = ''; commented by Ankur
		$printing_payment = null; // $printing_payment = '';
		$lab_payment = null; // $lab_payment = '';

		$renewal_total_payment_details =array();
		$renewal_ro_id =array();
		$renewal_firms_details =array();
		$renewal_customer_payment_details =array();
		$renewal_payment_max_id =array();
		$renewal_ca_application_payment_total =array();
		$renewal_printing_application_payment_total =array();
		$renewal_laboratory_application_payment_total =array();
		$renewal_ca_payment = null; // $renewal_ca_payment = '';
		$renewal_printing_payment = null; // $renewal_printing_payment = '';
		$renewal_lab_payment = null; // $renewal_lab_payment = '';

		$report_for = 'both';

		if (null!==($this->request->getData('search_logs'))) {

			$report_for = $this->request->getData('report_for');

			$table = 'DmiStates';
			$post_input_request = $this->request->getData('state');

			if (!empty($post_input_request)) {
				$state = $this->Customfunctions->dropdownSelectInputCheck($table, $post_input_request); //calling library function
			} else {
				$state = '';
			}

			$table = 'DmiDistricts';
			$post_input_request = $this->request->getData('district');

			if (!empty($post_input_request)) {
				$district = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request); //calling library function
			} else {
				$district = '';
			}

			$table = 'DmiCertificateTypes';
			$post_input_request = $this->request->getData('application_type');

			if (!empty($post_input_request)) {
				$application_type = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request); //calling library function
			} else {
				$application_type = '';
			}


			$table = 'DmiRoOffices';
			$post_input_request = $this->request->getData('office');

			if (!empty($post_input_request)) {
				$ro_office = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request); //calling library function
			} else {
				$ro_office = '';
			}

			$search_from_date =  $this->request->getData('from_date');
			$search_from_date = $this->Customfunctions->dateFormatCheck($search_from_date);
			$search_to_date =  $this->request->getData('to_date');
			$search_to_date = $this->Customfunctions->dateFormatCheck($search_to_date);
			$this->date_comparison($search_from_date,$search_to_date);

			$application_type_not_empty = array(); $ro_office_not_empty = array(); $state_not_empty = array(); $district_not_empty = array();

			$date_not_empty = ['DmiApplicantPaymentDetails.customer_id = DmiFirms.customer_id', 'payment_confirmation'=>'confirmed'];

			$renewal_date_not_empty = ['DmiRenewalApplicantPaymentDetails.customer_id = Dmi_firm.customer_id','payment_confirmation'=>'confirmed'];


			if ($application_type != '') {
				$application_type_not_empty = ['certification_type IS' => $application_type];
			}

			if ($ro_office != '') {
				$ro_office_not_empty = ['DmiDistricts.ro_id IS' => $ro_office];
			}

			if ($state != '') {
				$state_not_empty = ['state IS' => $state];
			}

			if ($district != '') {
				$district_not_empty = ['district IS' => $district];
			}

			if ($search_from_date != '' && $search_to_date != '') {
				$date_not_empty = ['date(transaction_date) BETWEEN :start AND :end'];

				$renewal_date_not_empty = ['date(transaction_date) BETWEEN :start AND :end'];
			}

			if ($application_type != '' || $ro_office != '' || $state != '' || $district != '' || $search_from_date != '' || $search_to_date != '') {

				if ($search_from_date != '' && $search_to_date != '') {

					$firm_customer_id_list = $this->DmiFirms->find('all')
															->select(['id', 'customer_id'])
															->join(['DmiDistricts' => ['table' => 'dmi_districts', 'type' => 'INNER',
																	'conditions' => ['DmiDistricts.id = DmiFirms.district::integer', $ro_office_not_empty]],
																	'DmiApplicantPaymentDetails' => ['table' => 'dmi_applicant_payment_details', 'type' => 'INNER',
																	'conditions' => ['DmiApplicantPaymentDetails.customer_id = DmiFirms.customer_id', $date_not_empty,'payment_confirmation' => 'confirmed']]])
															->select(['id','customer_id'])
															->where(array_merge($application_type_not_empty, $state_not_empty, $district_not_empty))
															->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')
															->combine('id', 'customer_id')
															->toArray();

					$renewal_firm_customer_id_list = $this->DmiFirms->find('all')
																	->select(['id', 'customer_id'])
																	->join(['DmiDistricts' => ['table' => 'dmi_districts', 'type' => 'INNER',
																			'conditions' => ['DmiDistricts.id = DmiFirms.district::integer', $ro_office_not_empty]],
																			'DmiRenewalApplicantPaymentDetails' => ['table' => 'dmi_renewal_applicant_payment_details', 'type' => 'INNER',
																			'conditions' => ['DmiRenewalApplicantPaymentDetails.customer_id = DmiFirms.customer_id',$renewal_date_not_empty, 'payment_confirmation' => 'confirmed']]])
																	->select(['id','customer_id'])
																	->where(array_merge($application_type_not_empty, $state_not_empty, $district_not_empty))
																	->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')
																	->combine('id', 'customer_id')
																	->toArray();
				} else {

					$firm_customer_id_list = $this->DmiFirms->find('all')
															->select(['id', 'customer_id'])
															->join(['DmiDistricts' => ['table' => 'dmi_districts', 'type' => 'INNER',
																	'conditions' => ['DmiDistricts.id = DmiFirms.district::integer', $ro_office_not_empty]],
																	'DmiApplicantPaymentDetails' => ['table' => 'dmi_applicant_payment_details', 'type' => 'INNER',
																	'conditions' => ['DmiApplicantPaymentDetails.customer_id = DmiFirms.customer_id','payment_confirmation' => 'confirmed']]])
															->select(['id','customer_id'])
															->where(array_merge($application_type_not_empty, $state_not_empty, $district_not_empty))
															->combine('id', 'customer_id')->toArray();

					$renewal_firm_customer_id_list = $this->DmiFirms->find('all')
																	->select(['id', 'customer_id'])
																	->join(['DmiDistricts' => ['table' => 'dmi_districts', 'type' => 'INNER',
																			'conditions' => ['DmiDistricts.id = DmiFirms.district::integer', $ro_office_not_empty]],
																			'DmiRenewalApplicantPaymentDetails' => ['table' => 'dmi_renewal_applicant_payment_details', 'type' => 'INNER',
																			'conditions' => ['DmiRenewalApplicantPaymentDetails.customer_id = DmiFirms.customer_id','payment_confirmation' => 'confirmed']]])
																	->select(['id','customer_id'])
																	->where(array_merge($application_type_not_empty, $state_not_empty, $district_not_empty))
																	->combine('id', 'customer_id')
																	->toArray();
				}

				if ($firm_customer_id_list != null) {
					$firm_customer_id_condition = ['customer_id IN' => $firm_customer_id_list];
				} else {
					$firm_customer_id_condition = ['customer_id IS' => ''];
				}

				if ($renewal_firm_customer_id_list != null) {
					$renewal_firm_customer_id_list = ['customer_id IN' => $renewal_firm_customer_id_list];
				} else {
					$renewal_firm_customer_id_list = ['customer_id IS' => ''];
				}

			} else {
				$firm_customer_id_condition = array();
				$renewal_firm_customer_id_list = array();
			}

		} else {
			$firm_customer_id_condition = array();
			$renewal_firm_customer_id_list = array();
		}

		$query_cil = $this->DmiApplicantPaymentDetails->find('all');

		$customer_id_list = $query_cil->select(['customer_id', 'max' => $query_cil->func()->max('certificate_type')])
										->distinct()->where($firm_customer_id_condition)
										->group(['customer_id'])->order(['MAX(certificate_type)'])->toArray();


		$query_rcil = $this->DmiRenewalApplicantPaymentDetails->find('all');
		
		$renewal_customer_id_list = $query_rcil->find('all')
												->select(['customer_id', 'max' => $query_rcil->func()->max('certificate_type')])
												->distinct()->where($renewal_firm_customer_id_list)
												->group(['customer_id'])->order(['MAX(certificate_type)'])->toArray();


		if ($report_for == 'both' || $report_for == 'new') {

			$i=0;
			foreach ($customer_id_list as $customer_id) {

				$customer_payment_id_list = $this->DmiApplicantPaymentDetails->find('all')->select(['id'])->where(['customer_id' => $customer_id['customer_id'], 'payment_confirmation' => 'confirmed'])->toArray();

				if (!empty($customer_payment_id_list)) {

					$split_customer_id = explode('/',$customer_id['customer_id']);
					if ($split_customer_id[1] == 1) { $ca_application_payment_total[$i] = $i;}
					elseif ($split_customer_id[1] == 2) { $printing_application_payment_total[$i] = $i;}
					elseif ($split_customer_id[1] == 3) { $laboratory_application_payment_total[$i] = $i;}

					$payment_max_id[$i] = $customer_payment_id_list[0]['id'];

					$customer_payment_details[$i] =  $this->DmiApplicantPaymentDetails->find('all')->where(['id IN' => $customer_payment_id_list[0]['id']])->first();

					$firms_details[$i] = $this->DmiFirms->find('all')->where(['customer_id' => $customer_id['customer_id']])->first();

					$ro_id[$i] = $this->DmiDistricts->find('all')->select(['ro_id'])->where(['id' => $firms_details[$i]['district']])->first();
					$i=$i+1;
				}
			}
			// below if-else check added by Ankur Jangid for empty IN query error check
			if (!empty($customer_id_list)) {
				$payment_max_id_condition = ['id IN' => $payment_max_id];
			} else {
				$payment_max_id_condition = ['id IS' => ''];
			}


			$query_tpd = $this->DmiApplicantPaymentDetails->find('all');
			$total_payment_details = $query_tpd->select(['sum' => $query_tpd->func()->sum('amount_paid::integer'), 'certificate_type'])->where($payment_max_id_condition)->group(['certificate_type'])->order(['certificate_type'])->toArray();

			foreach ($total_payment_details as $total_payment) {

				if ($total_payment['certificate_type'] == 1) {$ca_payment = $total_payment['sum'];}
				elseif ($total_payment['certificate_type'] == 2) {$printing_payment = $total_payment['sum'];}
				elseif ($total_payment['certificate_type'] == 3) {$lab_payment = $total_payment['sum'];}
			}
		}

		$this->set('ca_application_payment_total',$ca_application_payment_total);
		$this->set('printing_application_payment_total',$printing_application_payment_total);
		$this->set('laboratory_application_payment_total',$laboratory_application_payment_total);
		$this->set('ca_payment',$ca_payment);
		$this->set('printing_payment',$printing_payment);
		$this->set('lab_payment',$lab_payment);

		$this->set('ro_id',$ro_id);
		$this->set('firms_details',$firms_details);
		$this->set('customer_payment_details',$customer_payment_details);


		if ($report_for == 'both' || $report_for == 'renewal') {

			$i=0;
			foreach ($renewal_customer_id_list as $renewal_customer_id) {

				$renewal_customer_payment_id_list = $this->DmiRenewalApplicantPaymentDetails->find('all')->select(['id'])
					->where(['customer_id' => $renewal_customer_id['customer_id'], 'payment_confirmation' => 'confirmed'])->first();

				if (!empty($renewal_customer_payment_id_list)) {

					$split_customer_id = explode('/',$renewal_customer_id['customer_id']);
					if ($split_customer_id[1] == 1) { $renewal_ca_application_payment_total[$i] = $i;}
					elseif ($split_customer_id[1] == 2) { $renewal_printing_application_payment_total[$i] = $i;}
					elseif ($split_customer_id[1] == 3) { $renewal_laboratory_application_payment_total[$i] = $i;}

					$renewal_payment_max_id[$i] = $renewal_customer_payment_id_list['id'];

					$renewal_customer_payment_details[$i] =  $this->DmiRenewalApplicantPaymentDetails->find('all')
						->where(['id' => $renewal_customer_payment_id_list['id']])->first();

					$renewal_firms_details[$i] = $this->DmiFirms->find('all')->where(['customer_id' => $renewal_customer_id['customer_id']])->first();

					$renewal_ro_id[$i] = $this->DmiDistricts->find('all')->select(['ro_id'])->where(['id' => $renewal_firms_details[$i]['district']])->first();
					$i=$i+1;
				}
			}
			// below if-else check added by Ankur Jangid for empty IN query error check
			if (!empty($renewal_customer_id_list)) {
				$renewal_payment_max_id_condition = ['id IN' => $renewal_payment_max_id];
			} else {
				$renewal_payment_max_id_condition = ['id IS' => ''];
			}

			$query_tpd = $this->DmiRenewalApplicantPaymentDetails->find('all');
			$renewal_total_payment_details = $query_tpd->select(['sum' => $query_tpd->func()->sum('amount_paid::integer'), 'certificate_type'])
				->where($renewal_payment_max_id_condition)->group(['certificate_type'])->order(['certificate_type'])->toArray();

			foreach ($renewal_total_payment_details as $renewal_total_payment) {
				if ($renewal_total_payment['certificate_type'] == 1) {$renewal_ca_payment = $renewal_total_payment['sum'];}
				elseif ($renewal_total_payment['certificate_type'] == 2) {$renewal_printing_payment = $renewal_total_payment['sum'];}
				elseif ($renewal_total_payment['certificate_type'] == 3) {$renewal_lab_payment = $renewal_total_payment['sum'];}
			}
		}

		$this->set('renewal_ca_application_payment_total',$renewal_ca_application_payment_total);
		$this->set('renewal_printing_application_payment_total',$renewal_printing_application_payment_total);
		$this->set('renewal_laboratory_application_payment_total',$renewal_laboratory_application_payment_total);
		$this->set('renewal_ca_payment',$renewal_ca_payment);
		$this->set('renewal_printing_payment',$renewal_printing_payment);
		$this->set('renewal_lab_payment',$renewal_lab_payment);

		$this->set('renewal_ro_id',$renewal_ro_id);
		$this->set('renewal_firms_details',$renewal_firms_details);
		$this->set('renewal_customer_payment_details',$renewal_customer_payment_details);

		$this->set('report_for',$report_for);
		$this->set('application_type',$application_type);
		$this->set('ro_office',$ro_office); $this->set('state',$state);
		$this->set('district',$district); $this->set('search_from_date',$search_from_date);
		$this->set('search_to_date',$search_to_date);   // Change on 5/11/2018, set search_to_date value, By Pravin 5/11/2018

	
	}




	// Sent Email Report
	// Description : ----
	// @Author : Pravin Bhakare
	// #Contributer : Ankur Jangid (Migration)
	// Date : 27-07-2018  

	public function sentEmailReport() {

		// $all_application_type = $this->DmiCertificateTypes->find('list', array('fields'=>'certificate_type'));
		$all_application_type = $this->DmiCertificateTypes->find('all')->select(['id', 'certificate_type'])->combine('id', 'certificate_type')->toArray();

		//added 'office_type'=>'RO' condition on 27-07-2018
		// $all_ro_office = $this->DmiRoOffices->find('list',array('fields'=>'ro_office','conditions'=>array('office_type'=>'RO')));
		$all_ro_office = $this->DmiRoOffices->find('all')->select(['id', 'ro_office'])->where(['office_type'=>'RO','delete_status IS NULL'])->combine('id', 'ro_office')->toArray();

		$this->set('all_application_type',$all_application_type);
		$this->set('all_ro_office',$all_ro_office);

		if (null != ($this->request->getData('search_logs'))) {

			$table = 'DmiCertificateTypes';
			$post_input_request = $this->request->getData('application_type');
			if (!empty($post_input_request)) {
				$application_type = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
			}
			else {
				$application_type = '';
			}

			$table = 'DmiRoOffices';
			$post_input_request = $this->request->getData('office');
			if (!empty($post_input_request)) {
				$ro_office = $this->Customfunctions->dropdownSelectInputCheck($table,$post_input_request);//calling library function
			}
			else {
				$ro_office = '';
			}

			$search_from_date =  $this->request->getData('from_date');
			$search_from_date = $this->Customfunctions->dateFormatCheck($search_from_date);
			$search_to_date =  $this->request->getData('to_date');
			$search_to_date = $this->Customfunctions->dateFormatCheck($search_to_date);
			$this->date_comparison($search_from_date,$search_to_date);

			// $this->paginate = array('conditions'=>array('date(sent_date) BETWEEN ? AND ?' => array($search_from_date,$search_to_date)),'limit' => 5,'order'=>'id desc');
			$condition = ['date(sent_date) BETWEEN :start AND :end'];
			$sent_email_details = $this->DmiSentEmailLogs->find('all')->where($condition)->bind(':start', $search_from_date, 'date')->bind(':end', $search_to_date, 'date')
				->order(['id'=>'DESC'])->toArray();
		}
		else {
			// $this->paginate = array('limit' => 5,'order'=>'id desc');
			$sent_email_details = $this->DmiSentEmailLogs->find('all')->order(['id'=>'DESC'])->toArray();
		}

		if (!empty($sent_email_details)) {
			$i=0;
			foreach ($sent_email_details as $destination_list) {
				$split_value = explode(',',$destination_list['destination_list']);
				$email_destination_list[$i] = implode(', ',$split_value);
				$i=$i+1;
			}
		}

		$this->set('sent_email_details',$sent_email_details);
		$this->set('email_destination_list',$email_destination_list);
	}



	// for checking the encoded or not
	public function isBase64Encoded($data){
		if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}



	public function getStateName($customer_id) {

		$state_id = $this->DmiFirms->find('all',array('fields'=>'state','conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$state_id['state'])))->first();
		return $state_name['state_name'];
	}	


}
?>
