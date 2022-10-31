<?php
//session_start();
namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Controller\Component;
use Cake\Utility\Hash;
use Cake\ORM\Entity;
use Cake\View\ViewBuilder;

	class FrontStatisticsController extends AppController{
		
		
		var $name = 'FrontStatistics';
		var $uses = array();
		var $layout = 'admin_dashboard';
			
		public function initialize(): void
		{
			parent::initialize();
			
				// $this->loadComponent('Session'); // commented by Ankur Jangid
				$this->loadComponent('Createcaptcha');
				$this->loadComponent('Reportstatistics');
				$this->loadComponent('Customfunctions');

				$this->loadModel('DmiAllApplicationsCurrentPositions');
				$this->loadModel('DmiRenewalAllCurrentPositions');

				$this->viewBuilder()->setHelpers(['Form','Html','Time']);
			$this->Session = $this->getRequest()->getSession();
			
		}
			
		
		public function aqcmsStatistics(){
			
			$this->autoRender=false;
			
			$this->loadModel('DmiCustomers');
			$this->loadModel('DmiFirms');						
			$this->loadModel('DmiUsers');
			
			$searchConditions = array();

			$total_primary_user = $this->DmiCustomers->find('list',array('valueField'=>'id','conditions'=>$searchConditions))->toList();

			$total_users = $this->DmiUsers->find('list',array('valueField'=>'id','conditions'=>array('status'=>'active')))->toList();
			
			$query_tfr = $this->DmiFirms->find('all');
			$total_firm_register = $query_tfr->select(['certification_type', 'count' => $query_tfr->func()->count('certification_type')])
																					 ->where($searchConditions)																					 
																					 ->group(['certification_type'])
																					 ->order(['certification_type' => 'ASC'])
																					 ->toArray(); 

			$total_delete_firms = $this->DmiFirms->find('all')->where($searchConditions)->where(['delete_status IS NOT' => NULL])->toArray(); 	
																				 
			//$total_firm_register = $this->DmiFirms->find('all',array('fields'=>array('certification_type',count('certification_type')),'group'=>array('certification_type'),'order'=>'certification_type','conditions'=>$searchConditions))->toArray();
			//$total_delete_firms = $this->DmiFirms->find('all',array('conditions'=>am($searchConditions,array('delete_status !='=>NULL))))->toArray();
			//exit;

			$caRenewalDue = 0; 	$printingRenewalDue = 0; $labRenewalDue = 0;
			
			$list4RenewalDueCheck = $this->DmiFirms->find('all')->where($searchConditions)->combine('id', 'customer_id')->toArray();

			//$list4RenewalDueCheck = $this->DmiFirms->find('list',array('valueField'=>'customer_id','conditions'=>$searchConditions))->toList();		
			
			foreach($list4RenewalDueCheck as $each_application){

				$renewalDue = $this->Customfunctions->checkApplicantValidForRenewal($each_application);

				if($renewalDue == 'yes'){

					$split_customer_id = explode('/',(string) $each_application); #For Deprecations

					if($split_customer_id[1] == 1){

						$caRenewalDue = $caRenewalDue+1; 

					}elseif($split_customer_id[1] == 2){

						$printingRenewalDue = $printingRenewalDue+1; 

					}elseif($split_customer_id[1] == 3){

						$labRenewalDue = $labRenewalDue+1;
					}
				}
			}
			
			$application_processed_type = array('new_app_processed','renewal_app_processed','backlog_app_processed');
			
			foreach($application_processed_type as $each){	

				$application_processed[] = $this->Reportstatistics->$each($searchConditions);
			}
			
			$applications_current_positions_tables =  array('DmiFinalSubmits'=>'DmiAllApplicationsCurrentPositions',
															'DmiRenewalFinalSubmits'=>'DmiRenewalAllCurrentPositions');
			
			$pendingCountForMo = 0; $pendingCountForIo = 0; $pendingCountForHo = 0;
			$inprogress_app_with_ro = array();
			
			$searchPendingConditions = array();
			
			foreach($applications_current_positions_tables as $each_table){
				
				//$Dmi_each_table = ClassRegistry::init($each_table);
				
				
				$key = array_search ($each_table, $applications_current_positions_tables);
				//$Dmi_key = ClassRegistry::init($key);

				//For Progress with MO
				$inprogress_with_mo = $this->$each_table->find('all')->select(['id', 'customer_id'])
														->where($searchPendingConditions)->where(['current_level' => 'level_1'])
														->combine('id', 'customer_id')->toArray(); // updated by Ankur
				$pendingCountForMo = $pendingCountForMo + count($inprogress_with_mo);

				//For Progress with IO
				$inprogress_with_io = $this->$each_table->find('all')->select(['id', 'customer_id'])
														->where($searchPendingConditions)->where(['current_level' => 'level_2'])
														->combine('id', 'customer_id')->toArray(); // updated by Ankur
				$pendingCountForIo = $pendingCountForIo + count($inprogress_with_io);


				//For Progress with HO
				$inprogress_with_ho = $this->$each_table->find('all')->select(['id', 'customer_id'])
														->where($searchPendingConditions)->where(['current_level' => 'level_4'])
														->combine('id', 'customer_id')->toArray(); // updated by Ankur
				$pendingCountForHo = $pendingCountForHo + count($inprogress_with_ho);


				//For Progress with RO
				$inprogress_with_ro = $this->$each_table->find('all')->select(['id', 'customer_id'])
														->where($searchPendingConditions)->where(['current_level' => 'level_3'])
														->combine('id', 'customer_id')->toArray(); // updated by Ankur

				foreach($inprogress_with_ro as $each_record ){
					$result_status = $this->$key->find('all')->where(['customer_id' => $each_record, 'status' => 'approved', 'current_level' => 'level_3'])->toArray();
					
					if(empty($result_status)){
						$inprogress_app_with_ro[] = $each_record;
					}
				}			
			}
			
			$this->loadModel('DmiApplicationEsignedStatuses');
						
			$applicationEsigned = $this->DmiApplicationEsignedStatuses->find('all')->where($searchPendingConditions)->where(['application_esigned' => 'yes'])->toArray(); 
			$inspectionReportEsigned = $this->DmiApplicationEsignedStatuses->find('all')->where($searchPendingConditions)->where(['report_esigned' => 'yes'])->toArray(); 
			$certificateEsigned = $this->DmiApplicationEsignedStatuses->find('all')->where($searchPendingConditions)->where(['certificate_esigned' => 'yes'])->toArray();


			$this->loadModel('DmiRenewalEsignedStatuses');			
			
			$renewalApplicationEsigned = $this->DmiRenewalEsignedStatuses->find('all')->where($searchPendingConditions)->where(['application_esigned' => 'yes'])->toArray();
			$renewalInspectionReportEsigned = $this->DmiRenewalEsignedStatuses->find('all')->where($searchPendingConditions)->where(['report_esigned' => 'yes'])->toArray();
			$renewalCertificateEsigned = $this->DmiRenewalEsignedStatuses->find('all')->where($searchPendingConditions)->where(['certificate_esigned' => 'yes'])->toArray();



			$this->loadModel('DmiApplicantPaymentDetails');
			$this->loadModel('DmiRenewalApplicantPaymentDetails');
			$this->loadModel('DmiVisitorCounts');
			
			//$newApplicationrevenue = $this->DmiApplicantPaymentDetails->find('all',array('fields' =>array('sum(amount_paid::integer)'),'conditions'=>am($searchPendingConditions,array('payment_confirmation'=>'confirmed'))))->toArray();
			//$renewalApplicationrevenue =$this->DmiRenewalApplicantPaymentDetails->find('all',array('fields' =>array('sum(amount_paid::integer)'),'conditions'=>am($searchPendingConditions,array('payment_confirmation'=>'confirmed'))))->toArray();
			
			$newApplicationrevenue_Query = $this->DmiApplicantPaymentDetails->find('all')->where($searchPendingConditions)
						->where(['payment_confirmation' => 'confirmed'])->sumOf('amount_paid'); // updated by Ankur
			$newApplicationrevenue = ['sum' => $newApplicationrevenue_Query];
			
			$renewalApplicationrevenue_Query =$this->DmiRenewalApplicantPaymentDetails->find('all')->where($searchPendingConditions)
						->where(['payment_confirmation' => 'confirmed'])->sumOf('amount_paid'); 
			$renewalApplicationrevenue = ['sum' => $renewalApplicationrevenue_Query];			

			//$newApplicationrevenueTrans = $this->DmiApplicantPaymentDetails->find('list',array('valueField'=>'id','conditions'=>am($searchPendingConditions,array('payment_confirmation'=>'confirmed'))))->toList();
			$newApplicationrevenueTrans = $this->DmiApplicantPaymentDetails->find('list')->select(['id', 'id'])->where($searchPendingConditions)->where(['payment_confirmation' => 'confirmed'])->combine('id', 'id')->toArray();
			
			//$renewalApplicationrevenueTrans =$this->DmiRenewalApplicantPaymentDetails->find('list',array('valueField'=>'id','conditions'=>am($searchPendingConditions,array('payment_confirmation'=>'confirmed'))))->toList();
			$renewalApplicationrevenueTrans = $this->DmiRenewalApplicantPaymentDetails->find('list')->select(['id', 'id'])->where($searchPendingConditions)->where(['payment_confirmation' => 'confirmed'])->combine('id', 'id')->toArray();
			
			$total_payment_transaction = count($newApplicationrevenueTrans) + count($renewalApplicationrevenueTrans);
		
			$totalVisitor =$this->DmiVisitorCounts->find('all')->select(['visitor'])->order(['id' => 'DESC'])->first();
			//$totalVisitor =$this->DmiVisitorCounts->find('all',array('fields' =>array('visitor'),'order' => array('id' =>'desc')))->first();
			
			
			$total_firm = 0; 
			
			
			  foreach($total_firm_register as $each_firm){
				  
				$total_firm = $total_firm + $each_firm['count'];
				
				if($each_firm['certification_type'] == 1 ){
					$ca_applictions = $each_firm['count']; 
				}elseif($each_firm['certification_type'] == 2 ){
					$printing_applictions = $each_firm['count']; 
				}elseif($each_firm['certification_type'] == 3 ){
					$lab_applictions = $each_firm['count'];
				}
			  } 
			

			$totalProcessed = 0; 
			foreach($application_processed as $each_application){ 
				$totalProcessed = $totalProcessed + $each_application[0][0]+$each_application[0][1]+$each_application[0][2];  
			}
			
			$totalGrant = 0; 			
			foreach($application_processed as $each_application){ 
				$totalGrant = $totalGrant + $each_application[1][0]+$each_application[1][1]+$each_application[1][2];  
			} 
			//print_r(count($newApplicationrevenueTrans));exit;
			
			$total_renewal=$caRenewalDue+$printingRenewalDue+$labRenewalDue;
			$total_pending=$pendingCountForMo+$pendingCountForIo+$pendingCountForHo+count($inprogress_app_with_ro);
			$total_esigned=count($applicationEsigned)+count($inspectionReportEsigned)+count($certificateEsigned)+
							 count($renewalApplicationEsigned)+count($renewalInspectionReportEsigned)+count($renewalCertificateEsigned);
			

					 
			$total_revenue = $this->thousandsCurrencyFormat($newApplicationrevenue['sum'] + $renewalApplicationrevenue['sum']);				 
			
			$new_appl_revenue = $this->thousandsCurrencyFormat($newApplicationrevenue['sum']);
			$renewal_appl_revenue = $this->thousandsCurrencyFormat($renewalApplicationrevenue['sum']); 
			
			$this->loadModel('DmiFrontStatistics');
			
			/* LIMS STATISTIC*/
			
			$this->loadModel('DmiUsers');
			$this->loadModel('DmiRoOffices');
			$this->loadModel('MReport');
			$this->loadModel('SampleInward');
			
			$query_tfr = $this->SampleInward->find('all');
			$sample_details = $query_tfr->select(['count' => $query_tfr->func()->count('inward_id')])->toArray();		
			$sampleRecived = $sample_details[0]['count'];
				

			$grantedSamples = $query_tfr->select(['count' => $query_tfr->func()->count('inward_id')])->where(['status_flag'=>'F'])->toArray();
			$resultPublished = $grantedSamples[0]['count'];
			
			$samplesCommodity = $query_tfr->select(['count' => $query_tfr->func()->count('commodity_code')])->group(['commodity_code'])->toArray();
			$commodityTested = count($samplesCommodity);		
			
			$query_dmiuser = $this->DmiUsers->find('all');
			$chemists = $query_dmiuser->select(['count' => $query_tfr->func()->count('id')])->where(['role IN'=>array('Sr Chemist','Jr Chemist'),'status'=>'active'])->toArray();
			//$this->DmiUsers->find('all',array('fields'=>'count(id)','conditions'=>array('role'=>array('Sr Chemist','Jr Chemist'),'status'=>'active')))->first();
			$chemistsCount = $chemists[0]['count'];
			
			$query_dmirooffice = $this->DmiRoOffices->find('all');
			$labs = $query_dmirooffice->select(['count' => $query_tfr->func()->count('id')])->where(['office_type IN'=>array('RAL','CAL'),'delete_status IS NULL'])->toArray();
			//$this->DmiRoOffices->find('all',array('fields'=>'count(id)','conditions'=>array('office_type'=>array('RAL','CAL'),'delete_status'=>NULL)))->first();
			$labsCount = $labs[0]['count'];
			
			$query_mreport = $this->MReport->find('all');
			$tests = $query_mreport->select(['count' => $query_tfr->func()->count('report_code')])->where(['display'=>'Y'])->toArray();
			//$this->MReport->find('all',array('fields'=>'count(report_code)','conditions'=>array('display'=>'Y')))->first();
			$testsCount = $tests[0]['count'];
			

			$newEntity = $this->DmiFrontStatistics->newEntity(array(
			    'id'=>1,
				'primary_user' => count($total_primary_user),
				'firms_registered' =>$total_firm,
				't_users'=>count($total_users),
				'ca_new_grant'=>$application_processed[0][1][0],
				'printing_new_grant'=>$application_processed[0][1][1],
				'lab_new_grant'=>$application_processed[0][1][2],
				'ca_renew_grant'=>$application_processed[1][1][0],
				'printing_renew_grant'=>$application_processed[1][1][1],
				'lab_renew_grant'=>$application_processed[1][1][2],
				'ca_bk_grant'=>$application_processed[2][1][0],
				'pp_bk_grant'=>$application_processed[2][1][1],
				'lb_bk_grant'=>$application_processed[2][1][2],
				'ca_firm_reg'=>$ca_applictions,
				'pp_firm_reg'=>$printing_applictions,
				'lb_firm_reg'=>$lab_applictions,
				'delete_firm'=>count($total_delete_firms),
				'ca_ip_app_n'=>$application_processed[0][0][0],
				'pp_ip_app_n'=>$application_processed[0][0][1],
				'lb_ip_app_n'=>$application_processed[0][0][2],
				'ca_ip_app_r'=>$application_processed[1][0][0],
				'pp_ip_app_r'=>$application_processed[1][0][1],
				'lb_ip_app_r'=>$application_processed[1][0][2],
				'ca_ip_app_bk'=>$application_processed[2][0][0],
				'pp_ip_app_bk'=>$application_processed[2][0][1],
				'lb_ip_app_bk'=>$application_processed[2][0][2],
				'ca_renewal_due'=>$caRenewalDue,
				'pp_renewal_due'=>$printingRenewalDue,
				'lb_renewal_due'=>$labRenewalDue,
				'pending_mo'=>$pendingCountForMo,
				'pending_io'=>$pendingCountForIo,
				'pending_ro'=>count($inprogress_app_with_ro),
				'pending_ho'=>$pendingCountForHo,
				'e_sign_app_n'=>count($applicationEsigned),
				'e_sign_insp_n'=>count($inspectionReportEsigned),
				'e_sign_grantc_n'=>count($certificateEsigned),
				'e_sign_app_r'=>count($renewalApplicationEsigned),
				'e_sign_insp_r'=>count($renewalInspectionReportEsigned),
				'e_sign_grantc_r'=>count($renewalCertificateEsigned),
				'reve_app_n'=>$new_appl_revenue,
				'reve_app_r'=>$renewal_appl_revenue,
				'total_revenue' => $total_revenue,
				'new_appl_revenue'=>$new_appl_revenue,
				'renewal_appl_revenue'=>$renewal_appl_revenue,
				't_payment_trans'=>$total_payment_transaction,
				't_chemist'=>$chemistsCount,
				't_test'=>$testsCount,
				't_commodity'=>$commodityTested,
				't_sample_r'=>$sampleRecived,
				't_sample_p'=>$resultPublished,
				't_labs'=>$labsCount,
				'modified'=>date('Y-m-d H:i:s')
			));

			$this->DmiFrontStatistics->save($newEntity);
		
		
		}
		
		public function thousandsCurrencyFormat($number) {

			if ($number >= 1000) {
				
				$x_array = explode('.',number_format(($number / 1000), 1));
				
				if($x_array[1] == 0 ){
					$number_value = $x_array[0]. 'K';
				}else{
					$number_value = number_format(($number / 1000), 1) . 'k';
				}				
				return $number_value;
				
			} else {
				return $number;
			}			
			
		}
			
		
	}	
?>