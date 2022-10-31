<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Network\Session\DatabaseSession;
use App\Network\Email\Email;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\ORM\TableRegistry;
use tcpdf;
use phpqrcode;
use xmldsign;
use Cake\Utility\Xml;
use FR3D;
use Cake\View;

class ApplicationformspdfsController extends AppController{
	
	var $name = 'Applicationformspdfs';	
							
    public function initialize(): void
    {
        parent::initialize();
		   
		$this->loadComponent('Customfunctions');
		$this->loadComponent('Mastertablecontent');
		$this->viewBuilder()->setHelpers(['Form','Html']);
		$this->viewBuilder()->setLayout('pdf_layout');
    }
	
	//method to generate pdf view for CA application

	//function to move file from one folder to another
	public function moveFile($file_name,$source,$destination){
		
		// If we copied this successfully, mark it for deletion
		if (copy($source.$file_name, $destination.$file_name)) {
			$delete_path = $source.$file_name;
			unlink($delete_path);
			return true;
		}else{
			//this if condition added on 01-04-2019 by Amol
			//to try the moving of file for 2nd attempt, because many times it was not moved in 1st attempt.
			if (copy($source.$file_name, $destination.$file_name)) {
				$delete_path = $source.$file_name;
				unlink($delete_path);
				return true;
			}else{
				
				if (file_exists($source.$file_name)) {//added this new condition on 15-01-2020  
					return false;					                       
				}	
			}
		}
	}
	
	public function generateApplicationPdf($pdf_view_path){
			
		$application_type = $this->Session->read('application_type');
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		$Dmi_app_pdf_record = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['app_pdf_record']);
		$Dmi_esign_status = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['esign_status']);
		
		$Dmi_temp_esign_status = TableRegistry::getTableLocator()->get('DmiTempEsignStatuses');
		$Dmi_pao_detail = TableRegistry::getTableLocator()->get('DmiPaoDetails');
		$Dmi_ro_office = TableRegistry::getTableLocator()->get('DmiRoOffices');
		$Dmi_user = TableRegistry::getTableLocator()->get('DmiUsers');
			
		//to check if any record is present in esign temp table for current customer.
		//if present then remove that record, delete file from temp folder, and update record from main esign status table
		//added on 02-10-2018 by Amol
		$Dmi_temp_esign_status->checkTempEsignRecordExist($this->Session->read('username'),'applicant');			
				
		$all_data_pdf = $this->render($pdf_view_path);				
		
		$customer_id = $this->Session->read('username');				
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
		
		//as per distributed folder structure, get folder name as per application to store pdf
		//on 04-10-2021 by Amol
		$folderName = $this->Customfunctions->getFolderName($customer_id);
		
		$pdfPrefix = null;
		if($application_type==2){ 
			$pdfPrefix = 'R-'; 
		
		}elseif($application_type==5){
			$pdfPrefix = 'FDC-';
		
		}elseif($application_type==6){
			$pdfPrefix = 'EC-';
		}
	
		$rearranged_id = $pdfPrefix.$split_customer_id[0].'-'.$split_customer_id[1].'-'.$split_customer_id[2].'-'.$split_customer_id[3];				
	
		//check applicant last record version to increment		
		$list_id = $Dmi_app_pdf_record->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
				
		if(!empty($list_id))
		{
			$max_id = $Dmi_app_pdf_record->find('all', array('fields'=>'pdf_version', 'conditions'=>array('id'=>max($list_id))))->first();																	
			$last_pdf_version 	=	$max_id['pdf_version'];

		}
		else{	$last_pdf_version = 0;	}				

		$current_pdf_version = $last_pdf_version+1; //increment last version by 1
	
	
		//taking complete file name in session, which will be use in esign controller to esign the file.
		$this->Session->write('pdf_file_name',$rearranged_id.'('.$current_pdf_version.')'.'.pdf');
	
		//condition to check if application is old or new
		
		if($this->Customfunctions->checkApplicationOldNew($customer_id)=='new' || $application_type !=1){
			
			//creating filename and file path to save				
			$file_path = '/writereaddata/DMI/temp/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';
			
			$filename = $_SERVER["DOCUMENT_ROOT"].$file_path;
			
			//check esign status by calling function from custom model  //added on 01-11-2017 by Amol
			$current_level = 'applicant';			
			$esign_status = $Dmi_esign_status->getEsignedStatus($customer_id,$current_level);
			
			
			if($esign_status == 'yes' && $this->Session->read('with_esign')=='yes'){//added new condition of session value on 27-03-2018
				
				//move esigned file from temp folder to files folder
				$file_name = $rearranged_id.'('.$current_pdf_version.')'.'.pdf';
				$source = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/';
				$destination = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/applications/'.$folderName.'/';
				
				//calling custome function to move file
				if($this->moveFile($file_name,$source,$destination)==1){
					
					//changed file path from temp to files
					$file_path = '/writereaddata/DMI/applications/'.$folderName.'/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';
					
					$Dmi_app_pdf_record_entity = $Dmi_app_pdf_record->newEntity(array(
					
						'customer_id'=>$customer_id,
						'pdf_file'=>$file_path,
						'date'=>date('Y-m-d H:i:s'),
						'pdf_version'=>$current_pdf_version,
						'created'=>date('Y-m-d H:i:s'),
						'modified'=>date('Y-m-d H:i:s')	
					
					));
					
					$Dmi_app_pdf_record->save($Dmi_app_pdf_record_entity);
					
					$this->redirect('/customers/secondary-home');
					
				}					
				
			}
			else{  
				// when application final submitted without esign, on 27-03-2018 by Amol
				if($this->Session->read('with_esign')=='no'){ //added 1 more condition of session "with_esign" on 27-03-2018
				
					//creating filename and file path to save				
					$file_path = '/writereaddata/DMI/applications/'.$folderName.'/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';				
					$filename = $_SERVER["DOCUMENT_ROOT"].$file_path;
					$this->callTcpdf($all_data_pdf,'F',$customer_id,'new');//on 23-01-2020 with save mode
					
					$Dmi_app_pdf_record_entity = $Dmi_app_pdf_record->newEntity(array(
							
						'customer_id'=>$customer_id,
						'pdf_file'=>$file_path,
						'date'=>date('Y-m-d H:i:s'),
						'pdf_version'=>$current_pdf_version,
						'created'=>date('Y-m-d H:i:s'),
						'modified'=>date('Y-m-d H:i:s')	
					
					));
					$Dmi_app_pdf_record->save($Dmi_app_pdf_record_entity);
					
					$this->redirect('/customers/secondary-home');
					
				}else{
					
					$applicationType = $this->Mastertablecontent->applicationTypeById($application_type);
					//to preview application
					$this->callTcpdf($all_data_pdf,'I',$customer_id,$applicationType);//on 23-01-2020 with preview mode
					$this->callTcpdf($all_data_pdf,'F',$customer_id,$applicationType);//on 23-01-2020 with save mode
					//exit;
				}					
				
			}
		
		}else{
			//for old applications pdf generation
			//creating filename and file path to save				
			$file_path = '/writereaddata/DMI/applications/'.$folderName.'/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';				
			$filename = $_SERVER["DOCUMENT_ROOT"].$file_path;
			$this->callTcpdf($all_data_pdf,'F',$customer_id,'old');//on 23-01-2020 with save mode
			
			$Dmi_app_pdf_record_entity = $Dmi_app_pdf_record->newEntity(array(
					
				'customer_id'=>$customer_id,
				'pdf_file'=>$file_path,
				'date'=>date('Y-m-d H:i:s'),
				'pdf_version'=>$current_pdf_version,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s')	
			
			));
			$Dmi_app_pdf_record->save($Dmi_app_pdf_record_entity);
			
			$this->redirect('/customers/secondary-home');
		}
	

	}
	
	public function generateReportPdf($pdf_view_path){
		
		//to check if any record is present in esign temp table for current customer.
		//if present then remove that record, delete file from temp folder, and update record from main esign status table
		//added on 02-10-2018 by Amol
		$Dmi_temp_esign_status = TableRegistry::getTableLocator()->get('DmiTempEsignStatuses');
		$Dmi_temp_esign_status->checkTempEsignRecordExist($this->Session->read('customer_id'),$this->Session->read('current_level'));																															 
		
		$application_type = $this->Session->read('application_type');
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		$Dmi_app_pdf_record = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['inspection_pdf_record']);
		$Dmi_esign_status = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['esign_status']);
		
		$all_data_pdf = $this->render($pdf_view_path);
	
		$customer_id = $this->Session->read('customer_id');		
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
		
		//as per distributed folder structure, get folder name as per application to store pdf
		//on 04-10-2021 by Amol
		$folderName = $this->Customfunctions->getFolderName($customer_id);
		 
		//added on 17-11-2021 by Amol
		$pdfPrefix = ''; 		
		if($application_type==5){
			$pdfPrefix = 'FDC-';
		
		}elseif($application_type==6){
			$pdfPrefix = 'EC-';
		}
		
		$rearranged_id = 'I-'.$pdfPrefix.$split_customer_id[0].'-'.$split_customer_id[1].'-'.$split_customer_id[2].'-'.$split_customer_id[3];
			
		//check applicant last record version to increment		
		$list_id = $Dmi_app_pdf_record->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
					
		if(!empty($list_id))
		{
			$max_id = $Dmi_app_pdf_record->find('all', array('fields'=>'pdf_version', 'conditions'=>array('id'=>max($list_id))))->first();															
			$last_pdf_version 	=	$max_id['pdf_version'];

		}
		else{
					
			$last_pdf_version = 0;
		}

		$current_pdf_version = $last_pdf_version+1; //increment last version by 1
		

		//creating filename and file path to save		
		$file_path = '/writereaddata/DMI/temp/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';		
		$filename = $_SERVER["DOCUMENT_ROOT"].$file_path;
		
		
		//taking complete file name in session, which will be use in esign controller to esign the file.
		$this->Session->write('pdf_file_name',$rearranged_id.'('.$current_pdf_version.')'.'.pdf');
				
		
		//check esign status by calling function from custom model  //added on 02-11-2017 by Amol
		$current_level = 'level_2';
		$esign_status = $Dmi_esign_status->getEsignedStatus($customer_id,$current_level);
		
		if($esign_status == 'yes'){
			
			//move esigned file from temp folder to files folder
			$file_name = $rearranged_id.'('.$current_pdf_version.')'.'.pdf';
			$source = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/';
			$destination = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/inspection_reports/'.$folderName.'/';
			
			//calling custome function to move file
			if($this->moveFile($file_name,$source,$destination)==1){
				
				//changed file path from temp to files
				$file_path = '/writereaddata/DMI/inspection_reports/'.$folderName.'/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';
				
				$ReportPdfRecords = $Dmi_app_pdf_record->newEntity(array(
		
					'customer_id'=>$customer_id,
					'pdf_file'=>$file_path,
					'date'=>date('Y-m-d H:i:s'),
					'pdf_version'=>$current_pdf_version,
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')	
				
				)); 
				$Dmi_app_pdf_record->save($ReportPdfRecords);
				
			}
			
		}else{
			
			$this->callTcpdf($all_data_pdf,'I',$customer_id,'report');//on 23-01-2020 with preview mode
			$this->callTcpdf($all_data_pdf,'F',$customer_id,'report');//on 23-01-2020 with save mode
		}
	}
	
	
	public function generateGrantCerticatePdf($pdf_view_path){ 
	
		//to check if any record is present in esign temp table for current customer.
		//if present then remove that record, delete file from temp folder, and update record from main esign status table
		//added on 02-10-2018 by Amol
		$Dmi_temp_esign_status = TableRegistry::getTableLocator()->get('DmiTempEsignStatuses');
		$Dmi_temp_esign_status->checkTempEsignRecordExist($this->Session->read('customer_id'),$this->Session->read('current_level'));		
	
		$application_type = $this->Session->read('application_type');	
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		
		$Dmi_grant_pdf_record = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['grant_pdf']);
		$Dmi_esign_status = TableRegistry::getTableLocator()->get($Dmi_final_submit_tb['esign_status']);
		
		$all_data_pdf = $this->render($pdf_view_path);		
		
		$customer_id = $this->Session->read('customer_id');				
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations	
		
		//as per distributed folder structure, get folder name as per application to store pdf
		//on 04-10-2021 by Amol
		$folderName = $this->Customfunctions->getFolderName($customer_id);

		//added on 18-11-2021 by Amol
		$pdfPrefix = ''; 		
		if($application_type==5){
			$pdfPrefix = 'FDC-';
		
		}elseif($application_type==6){
			$pdfPrefix = 'EC-';
		}
		
		$rearranged_id = 'G-'.$pdfPrefix.$split_customer_id[0].'-'.$split_customer_id[1].'-'.$split_customer_id[2].'-'.$split_customer_id[3];
				
		//check applicant last record version to increment				
		$list_id = $Dmi_grant_pdf_record->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
					
		if(!empty($list_id))
		{
			$max_id = $Dmi_grant_pdf_record->find('all', array('fields'=>'pdf_version', 'conditions'=>array('id'=>max($list_id))))->first();
			$last_pdf_version 	=	$max_id['pdf_version'];
		}
		else{					
			$last_pdf_version = 0;
		}

		//applied on 14-10-2021 by Amol
		//to get the existing pdf version when RO/SO will esign the renewal certificate, approved by DDO, as per new order
		if($this->Session->read('ren_esign_process')=='yes'){
			$current_pdf_version = $last_pdf_version;
		}else{
			$current_pdf_version = $last_pdf_version+1; //increment last version by 1
		}
		
		

		$user_email_id = $this->Session->read('username');
		$user_once_no = $this->Session->read('once_card_no');
		
		//creating filename and file path to save				
		$file_path = '/writereaddata/DMI/temp/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';				
		$filename = $_SERVER["DOCUMENT_ROOT"].$file_path;
		
		
		//taking complete file name in session, which will be use in esign controller to esign the file.
		$this->Session->write('pdf_file_name',$rearranged_id.'('.$current_pdf_version.')'.'.pdf');
		
		//check esign status by calling function from custom model  //added on 01-11-2017 by Amol
		$current_level = $this->Session->read('current_level');		
		$esign_status = $Dmi_esign_status->getEsignedStatus($customer_id,$current_level);
		
		if($esign_status == 'yes'){

			//move esigned file from temp folder to files folder
			$file_name = $rearranged_id.'('.$current_pdf_version.')'.'.pdf';
			$source = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/';
			$destination = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/certificates/'.$folderName.'/';
			
			//calling custome function to move file
			if($this->moveFile($file_name,$source,$destination)==1){
				
				//changed file path from temp to files
				$file_path = '/writereaddata/DMI/certificates/'.$folderName.'/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';						
				
				$grantPdfRecords = $Dmi_grant_pdf_record->newEntity(array(		
					'customer_id'=>$customer_id,
					'user_email_id'=>$user_email_id,
					'user_once_no'=>$user_once_no,
					'pdf_file'=>$file_path,
					'date'=>date('Y-m-d H:i:s'),
					'pdf_version'=>$current_pdf_version,
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')				
				));
				$Dmi_grant_pdf_record->save($grantPdfRecords);
				
			}else{
			
				$this->customAlertPage("Sorry.. Certificate is generated but details not saved in DB because some functions not worked properly.");
				exit();
			}

		}else{

			//this condition is added to generate provisional renewal certificate without esign
			//on confirmation of payment by DDO
			//created on 16-09-2021 by Amol
			if($application_type==2 && $current_level=='pao'){

				//storing the renewal provisional ceritficate in temp folder
				//when RO/SO in-charge will esign this certificate then it will moved to main folder
				$this->callTcpdf($all_data_pdf,'F',$customer_id,'grant');

				$file_path = '/writereaddata/DMI/temp/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';
				$grantPdfRecords = $Dmi_grant_pdf_record->newEntity(array(		
					'customer_id'=>$customer_id,
					'user_email_id'=>$user_email_id,//DDO user id will be saved, and on esign RO user id will be replaced
					'user_once_no'=>$user_once_no,
					'pdf_file'=>$file_path,
					'date'=>date('Y-m-d H:i:s'),
					'pdf_version'=>$current_pdf_version,
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')				
				));
				$Dmi_grant_pdf_record->save($grantPdfRecords);

				//entry in new table to maintain the DDO/PAO renewal provisional grant logs
				//added on 18-10-2021 by Amol
				$DmiProvGrantLogs = TableRegistry::getTableLocator()->get('DmiGrantProvCertificateLogs');
				$proviGrantLog = $DmiProvGrantLogs->newEntity(array(		
					'customer_id'=>$customer_id,
					'user_email_id'=>$user_email_id,
					'pdf_file'=>$file_path,
					'date'=>date('Y-m-d H:i:s'),
					'pdf_version'=>$current_pdf_version,
					'created'=>date('Y-m-d H:i:s'),
					'modified'=>date('Y-m-d H:i:s')	
				));
				$DmiProvGrantLogs->save($proviGrantLog);


			}else{
				$this->callTcpdf($all_data_pdf,'I',$customer_id,'grant');//on 27-01-2020 with preview mode
				$this->callTcpdf($all_data_pdf,'F',$customer_id,'grant');//on 27-01-2020 with save mode

			}
				
			
		}	
	}
	
	public function generateGrantCerticateToReEsignPdf($pdf_view_path){
				
		$customer_id = $this->Session->read('customer_id');				
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations	
		
		$application_type = 1;//$this->Session->read('application_type');
		$Dmi_flow_wise_tables_list = TableRegistry::getTableLocator()->get('DmiFlowWiseTablesLists');
		$Dmi_final_submit_tb = $Dmi_flow_wise_tables_list->find('all',array('conditions'=>array('application_type IS'=>$application_type)))->first();
		$grant_pdf = $Dmi_final_submit_tb['grant_pdf'];
		$esign_status = $Dmi_final_submit_tb['esign_status'];
		$Dmi_grant_pdf_record = TableRegistry::getTableLocator()->get($grant_pdf);
		$Dmi_esign_status = TableRegistry::getTableLocator()->get($esign_status);
		
		$all_data_pdf = $this->render($pdf_view_path);		
	
		$rearranged_id = 'G-'.$split_customer_id[0].'-'.$split_customer_id[1].'-'.$split_customer_id[2].'-'.$split_customer_id[3];
		
		//updated logic as per new order on 01-04-2021, 5 years validity for PP and Laboratory
		//as the module is to reesign renewal certificate only, So now need to re-esign the first grant also, if granted with 2 years of validity
		//but not the old first grant record
		//on 24-09-2021 by Amol

		//check applicant last record version to increment				
		//$list_id = $Dmi_grant_pdf_record->find('list', array('valueField'=>'id', 'conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		//$current_pdf_version = 2; //for version 2 only

		$grant_details = $Dmi_grant_pdf_record->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'user_email_id !='=>'old_application'),'order'=>'id desc'))->first();
		$current_pdf_version = $grant_details['pdf_version'];

		$user_email_id = $this->Session->read('username');
		
		//creating filename and file path to save				
		$file_path = '/writereaddata/DMI/temp/'.$rearranged_id.'('.$current_pdf_version.')'.'.pdf';				
		$filename = $_SERVER["DOCUMENT_ROOT"].$file_path;
		
		
		//taking complete file name in session, which will be use in esign controller to esign the file.
		$this->Session->write('pdf_file_name',$rearranged_id.'('.$current_pdf_version.')'.'.pdf');
		
		//check esign status by calling function from custom model  //added on 01-11-2017 by Amol
		$current_level = $this->Session->read('current_level');

		//$this->Mpdf->Output($filename,'I');	
		//$this->Mpdf->Output($filename,'F');
		$this->callTcpdf($all_data_pdf,'I',$customer_id,'re_esign');//on 27-01-2020 with preview mode
		$this->callTcpdf($all_data_pdf,'F',$customer_id,'re_esign');//on 27-01-2020 with save mode

		
	}
	
			
	public function caFormsPdf(){ 
		
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiDistricts');
		$this->loadModel('DmiStates');
		$this->loadModel('MCommodity');
		$this->loadModel('MCommodityCategory');
		$this->loadModel('DmiCustomerFirmProfiles');
		$this->loadModel('DmiBusinessTypes');
		$this->loadModel('DmiCustomerPremisesProfiles');
		$this->loadModel('DmiCustomerMachineryProfiles');
		$this->loadModel('DmiAllMachinesDetails');
		$this->loadModel('DmiMachineTypes');
		$this->loadModel('DmiAllConstituentOilsDetails');
		$this->loadModel('DmiAllTanksDetails');
		$this->loadModel('DmiTankShapes');
		$this->loadModel('DmiCustomerLaboratoryDetails');
		$this->loadModel('DmiCustomerTblDetails');
		$this->loadModel('DmiAllTblsDetails');
		$this->loadModel('DmiCaBusinessYears');
		$this->loadModel('DmiLaboratoryTypes');
		$this->loadModel('DmiCustomerPackingDetails');
		$this->loadModel('DmiCrushingRefiningPeriods');
		
	
		//added on 27-03-2018, to set default value
		$show_esigned_by = $this->Session->read('with_esign');
		$this->set('show_esigned_by',$show_esigned_by);

		$customer_id = $this->Session->read('username');
		$this->set('customer_id',$customer_id);

		// This is added to get the form type - Akash [07-09-2022]
		$form_type = $this->Customfunctions->checkApplicantFormType($customer_id);
		$this->set('form_type',$form_type);

		//get nodal office of the applied CA
		$this->loadModel('DmiApplWithRoMappings');
		$get_office = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$this->set('get_office',$get_office);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);
		
		//check CA BEVO Applicant
		$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
		$this->set('ca_bevo_applicant',$ca_bevo_applicant);
		
		//check application have export unit
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
		$this->set('export_unit_status',$export_unit_status);
		
		// data from DMI firm Table
		$fetch_customer_firm_data = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$customer_firm_data = $fetch_customer_firm_data;
		$this->set('customer_firm_data',$customer_firm_data);
		
		// to show firm address name form id	
		$fetch_district_name = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$customer_firm_data['district'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
		$firm_district_name = $fetch_district_name['district_name'];
		$this->set('firm_district_name',$firm_district_name);
		
		$fetch_state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$customer_firm_data['state'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
		$firm_state_name = $fetch_state_name['state_name'];
		$this->set('firm_state_name',$firm_state_name);
		
		// to show commodities and there selected sub-commodities
		$sub_commodity_array = explode(',',(string) $customer_firm_data['sub_commodity']); #For Deprecations

		$i=0;
		foreach($sub_commodity_array as $sub_commodity_id)
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

		//forms data starts here

		// data from firm profile form
		$fetch_firm_last_id = $this->DmiCustomerFirmProfiles->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		$fetch_firm_profile_data = $this->DmiCustomerFirmProfiles->find('all',array('conditions'=>array('id'=>max($fetch_firm_last_id))))->first();
		$firm_data = $fetch_firm_profile_data;
		$this->set('firm_data',$firm_data);
						
		$fetch_business_type = $this->DmiBusinessTypes->find('all',array('fields'=>'business_type','conditions'=>array('id IS'=>$firm_data['business_type'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
		$business_type = $fetch_business_type['business_type'];
		$this->set('business_type',$business_type);
			
		// data from premises profile form
		$fetch_premises_last_id = $this->DmiCustomerPremisesProfiles->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		$fetch_premises_profile_data = $this->DmiCustomerPremisesProfiles->find('all',array('conditions'=>array('id'=>max($fetch_premises_last_id))))->first();
		$premises_data = $fetch_premises_profile_data;
		$this->set('premises_data',$premises_data);
						
		// data from machinery profile form
		$fetch_machinery_last_id = $this->DmiCustomerMachineryProfiles->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		$fetch_machinery_profile_data = $this->DmiCustomerMachineryProfiles->find('all',array('conditions'=>array('id'=>max($fetch_machinery_last_id))))->first();
		$machinery_data = $fetch_machinery_profile_data;
		$this->set('machinery_data',$machinery_data);
		
		//fetch details from All Machines details table
		$all_machine_details = $this->DmiAllMachinesDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->toArray();
		$this->set('all_machine_details',$all_machine_details);
		//below loop added on 24-08-2017 by Amol to get value from id
		$i=0;
		$machine_type_value=array();
		foreach($all_machine_details as $machine_type_id)
		{
			$get_machine_type_value = $this->DmiMachineTypes->find('all',array('conditions'=>array('id IS'=>$machine_type_id['machine_type'])))->first();
			$machine_type_value[$i] = $get_machine_type_value['machine_types'];
			$i=$i+1;
		}
		$this->set('machine_type_value',$machine_type_value);
		
		//fetch details from All Constituents oil mills details table
		$all_const_oil_mill_details = $this->DmiAllConstituentOilsDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 
																					'OR'=>array('delete_status IS NULL','delete_status ='=>'no'),
																					/*'customer_once_no !='=>null,*/ 'user_email_id IS NULL')))->toArray();
		$this->set('all_const_oil_mill_details',$all_const_oil_mill_details);			
		
		//fetch details from All tanks details table
		$all_tanks_details = $this->DmiAllTanksDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 
																					'OR'=>array('delete_status IS NULL','delete_status ='=>'no'),
																					/*'customer_once_no !='=>null,*/ 'user_email_id IS NULL')))->toArray();
		$this->set('all_tanks_details',$all_tanks_details);
		//below loop added on 24-08-2017 by Amol to get value from id
		$i=0;
		$tank_shape_value=array();
		foreach($all_tanks_details as $tank_shape_id)
		{
			$get_tank_shape_value = $this->DmiTankShapes->find('all',array('conditions'=>array('id IS'=>$tank_shape_id['tank_shape'])))->first();
			$tank_shape_value[$i] = $get_tank_shape_value['tank_shapes'];
			$i=$i+1;
		}
		$this->set('tank_shape_value',$tank_shape_value);
		
		
		//Below condition is applied for the CA EXPORT form F type - Akash [07-09-2022]
		// data from TBL profile form	
		if (trim($form_type) !='F') {

			// data from laboratory profile form
			$fetch_laboratory_last_id = $this->DmiCustomerLaboratoryDetails->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
			$fetch_laboratory_detail_data = $this->DmiCustomerLaboratoryDetails->find('all',array('conditions'=>array('id'=>max($fetch_laboratory_last_id))))->first();
			$laboratory_data = $fetch_laboratory_detail_data;
			$this->set('laboratory_data',$laboratory_data);
			
			// data from TBL profile form	
			$fetch_tbl_last_id = $this->DmiCustomerTblDetails->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
			$fetch_tbl_detail_data = $this->DmiCustomerTblDetails->find('all',array('conditions'=>array('id'=>max($fetch_tbl_last_id))))->first();
			$tbl_data = $fetch_tbl_detail_data;
			$this->set('tbl_data',$tbl_data);
		
		
			$all_tbls_details = $this->DmiAllTblsDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->toArray();
			$this->set('all_tbls_details',$all_tbls_details);
		}
	
		
	
		
		// for non bevo application	
		if($ca_bevo_applicant == 'no'){ 
		
			// to show premises address name form id
			$fetch_district_name = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$premises_data['district'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
			$premises_district_name = $fetch_district_name['district_name'];
			$this->set('premises_district_name',$premises_district_name);
			
			$fetch_state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$premises_data['state'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
			$premises_state_name = $fetch_state_name['state_name'];
			$this->set('premises_state_name',$premises_state_name);
			
			// Takeing business year value from ca_business_year table by pravin 11-08-2017
			//commented on 11-08-2022, as suggested after UAT phase II
			/*	$business_years = $this->DmiCaBusinessYears->find('list',array('keyField'=>'id','valueField'=>'business_years'))->toArray();	
			$business_years_value = $business_years[$firm_data['business_years']];
			$this->Set('business_years_value',$business_years_value);	*/
			
			//Below condition is applied for the CA EXPORT form F type - Akash [07-09-2022]
			// data from TBL profile form	
			if (trim($form_type) !='F') {

				//to fetch laboratory type name
				$fetch_laboratory_type = $this->DmiLaboratoryTypes->find('all',array('fields'=>'laboratory_type','conditions'=>array('id IS'=>$laboratory_data['laboratory_type'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
				$laboratory_type_name = $fetch_laboratory_type['laboratory_type'];
				$this->set('laboratory_type_name',$laboratory_type_name);
				
				// to show laboratory address name form id
				$fetch_laboratory_district_name = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$laboratory_data['district'],'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
				$laboratory_district_name = $fetch_laboratory_district_name['district_name'];
				$this->set('laboratory_district_name',$laboratory_district_name);
				
				$fetch_laboratory_state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$laboratory_data['state'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
				$laboratory_state_name = $fetch_laboratory_state_name['state_name'];
				$this->set('laboratory_state_name',$laboratory_state_name);
			}

			// data from packing profile form	
			$fetch_packing_last_id = $this->DmiCustomerPackingDetails->find('list',array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();				
			$fetch_packing_detail_data = $this->DmiCustomerPackingDetails->find('all',array('conditions'=>array('id'=>max($fetch_packing_last_id))))->first();
			$packing_data = $fetch_packing_detail_data;
			$this->set('packing_data',$packing_data);

		} elseif($ca_bevo_applicant == 'yes') {

			//query applied on 22-08-2017 by Amol
			$get_crushed_refined_period = $this->DmiCrushingRefiningPeriods->find('all',array('conditions'=>array('id IS'=>$machinery_data['mill_business_period'])))->first();
			$crushed_refined_period = $get_crushed_refined_period['crushing_refining_periods'];
			$this->set('crushed_refined_period',$crushed_refined_period);
		}
		
		
		$this->generateApplicationPdf('/Applicationformspdfs/caFormsPdf');	
		
		// $this->redirect(array('controller'=>'customers','action'=>'secondary_home'));	
		
	}
	
	
	public function printingFormsPdf(){
		
		$this->loadModel('DmiCustomers');
		$this->loadModel('DmiPrintingFirmProfiles');
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiAllMachinesDetails');
		$this->loadModel('DmiPrintingBusinessYears');
		$this->loadModel('DmiDistricts');
		$this->loadModel('DmiStates');
		$this->loadModel('DmiPackingTypes');
		$this->loadModel('DmiBusinessTypes');
		$this->loadModel('DmiPrintingPremisesProfiles');
		$this->loadModel('DmiPrintingUnitDetails');
		$this->loadModel('DmiApplicantPaymentDetails');		     
		 
		//added on 27-03-2018, to set default value
		$show_esigned_by = $this->Session->read('with_esign');
		$this->set('show_esigned_by',$show_esigned_by);	
		
		$customer_id = $this->Session->read('username');
		$this->set('customer_id',$customer_id);
		
		//get nodal office of the applied CA
		$this->loadModel('DmiApplWithRoMappings');
		$get_office = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$this->set('get_office',$get_office);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);		
		
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
		$primary_id_code = $split_customer_id[0];
		
		// data from DMI Customer Table
		$primary_customer_data = $this->DmiCustomers->find('all',array('conditions'=>array('customer_id LIKE'=>'%'.$primary_id_code.'%')))->first();
		$this->set('primary_customer_data',$primary_customer_data);		
		
		//forms data starts here
		// Change option by pravin 25/05/2017
		// data from Printing firm profile form					
		$fetch_firm_profile_data = $this->DmiPrintingFirmProfiles->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();	
		$firm_data = $fetch_firm_profile_data;				
		$this->set('firm_data',$firm_data);		
		
		// data from DMI firm Table					
		$fetch_customer_firm_data = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$customer_firm_data = $fetch_customer_firm_data;
		$this->set('customer_firm_data',$customer_firm_data);
		
		/*--Code start by pravin 18/3/2017--*/
		
		$fetch_printing_firm_profile = $this->DmiPrintingFirmProfiles->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$printing_firm_profile_data = $fetch_printing_firm_profile;
		$this->set('printing_firm_profile_data',$printing_firm_profile_data);		
		
		//fetch details from All Machines details table
		$all_machine_details = $this->DmiAllMachinesDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->toArray();
		$this->set('all_machine_details',$all_machine_details);		
		
		// Takeing business year value from printing_business_year table by pravin 11-08-2017
		$business_years = $this->DmiPrintingBusinessYears->find('list',array('keyField'=>'id','valueField'=>'business_years'))->toArray();		
		$business_years_value = $business_years[$firm_data['business_years']];
		$this->set('business_years_value',$business_years_value);
		/*--Code end by pravin 18/3/2017--*/
		
		
	// to show firm address name form id
	
		$fetch_district_name = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$customer_firm_data['district'])))->first();
		$firm_district_name = $fetch_district_name['district_name'];
		$this->set('firm_district_name',$firm_district_name);
		
		$fetch_state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$customer_firm_data['state'])))->first();
		$firm_state_name = $fetch_state_name['state_name'];
		$this->set('firm_state_name',$firm_state_name);			
		
		$packaging_materials = explode(',',(string) $customer_firm_data['packaging_materials']); #For Deprecations
		$packaging_type_list = $this->DmiPackingTypes->find('list', array('keyField'=>'id','valueField'=>'packing_type', 'conditions'=>array('id IN'=>$packaging_materials)))->toArray();			 
		$this->set('packaging_type_list',$packaging_type_list);
		
		$fetch_business_type = $this->DmiBusinessTypes->find('all',array('fields'=>'business_type','conditions'=>array('id IS'=>$firm_data['business_type'])))->first();
		$business_type = $fetch_business_type['business_type'];
		$this->set('business_type',$business_type);

		// data from Printing premises profile form					
		$fetch_premises_profile_data = $this->DmiPrintingPremisesProfiles->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
		$premises_data = $fetch_premises_profile_data;
		$this->set('premises_data',$premises_data);		
		
		// to show Printing premises address name form id
	
		$fetch_district_name = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$premises_data['district'])))->first();
		$premises_district_name = $fetch_district_name['district_name'];
		$this->set('premises_district_name',$premises_district_name);
		
		$fetch_state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$premises_data['state'])))->first();
		$premises_state_name = $fetch_state_name['state_name'];
		$this->set('premises_state_name',$premises_state_name);										
		
		// data from Printing Unit Details form	
		$fetch_printing_unit_detail = $this->DmiPrintingUnitDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
		$printing_unit_detail = $fetch_printing_unit_detail;
		$this->set('printing_unit_detail',$printing_unit_detail);
		
		/*code started By pravin 18/3/2017*/
		
		// data from Printing Unit Details form	
		$printing_all_machines_detail = $this->DmiAllMachinesDetails->find('all',array('fields'=>'id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
		$printing_machines_detail = $printing_all_machines_detail;
		$this->set('printing_machines_detail',$printing_machines_detail);
		
		// find the payment details (Done by pravin 06/02/2018)
		$applicant_payment_detail = null;
		$applicant_payment_details = $this->DmiApplicantPaymentDetails->find('all', array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
		if(!empty($applicant_payment_details)){
			$applicant_payment_detail = $applicant_payment_details;
		}
		$this->set('applicant_payment_detail',$applicant_payment_detail);
		
		$this->generateApplicationPdf('/Applicationformspdfs/printingFormsPdf');	
		
		$this->redirect(array('controller'=>'customers','action'=>'secondary_home'));	
		
	}
	
	public function laboratoryFormsPdf(){
		
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiChangeLabFirmDetails');
		$this->loadModel('DmiLaboratoryTypes');
		$this->loadModel('DmiDistricts');
		$this->loadModel('DmiStates');
		$this->loadModel('DmiBusinessTypes');
		$this->loadModel('DmiChangeLabOtherDetails');
		$this->loadModel('MCommodity');
		$this->loadModel('DmiLaboratoryChemistsDetails');
		$this->loadModel('DmiApplicantPaymentDetails');		  
		
		//added on 27-03-2018, to set default value
		$show_esigned_by = $this->Session->read('with_esign');
		$this->set('show_esigned_by',$show_esigned_by);	
		
		$customer_id = $this->Session->read('username');
		$this->set('customer_id',$customer_id);
		
		//get nodal office of the applied CA
		$this->loadModel('DmiApplWithRoMappings');
		$get_office = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$this->set('get_office',$get_office);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);
		
		//Added on 01-09-2017 check lab export unit 
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
		$this->set('export_unit_status',$export_unit_status);
		
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
		$primary_id_code = $split_customer_id[0];
		
		// data from DMI Customer Table
		$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$firm_detail = $firm_details;
		$this->set('firm_detail',$firm_detail);
		
		 
		$check_fields_result = $this->DmiChangeLabFirmDetails->sectionFormDetails($customer_id);
		$this->set('check_fields_result',$check_fields_result);
		
		// Take Laboratory Type
		// Correct spelling of laboratory_type field in laboratory_firm_profile table by pravin 19-08-2017
		$laboratory_types = $this->DmiLaboratoryTypes->find('all', array('fields'=>'laboratory_type', 'conditions'=>array('id IS'=>$check_fields_result[0]['laboratory_type'])))->first();	
		$laboratory_type = $laboratory_types['laboratory_type'];
		$this->set('laboratory_type',$laboratory_type);
		
		// Take District &  StateType
		$fetch_district_name = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$firm_detail['district'])))->first();
		$firm_district_name = $fetch_district_name['district_name'];
		$this->set('firm_district_name',$firm_district_name);
		
		// Take State Type
		$fetch_state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$firm_detail['state'])))->first();
		$firm_state_name = $fetch_state_name['state_name'];
		$this->set('firm_state_name',$firm_state_name);
	
		// Take Laboratory Type
		$laboratory_types = $this->DmiBusinessTypes->find('all', array('fields'=>'business_type', 'conditions'=>array('id IS'=>$check_fields_result[0]['business_type'])))->first();
		$business_type = $laboratory_types['business_type'];
		$this->set('business_type',$business_type);
					
		$check_laboratory_other_fields_result = $this->DmiChangeLabOtherDetails->sectionFormDetails($customer_id);		
		$this->set('check_laboratory_other_fields_result',$check_laboratory_other_fields_result);
		
		// Take sub commodities

		$sub_commodities_details = explode(',',(string) $firm_detail['sub_commodity']); #For Deprecations
		$sub_commodities_details = $this->MCommodity->find('list', array('keyField'=>'commodity_code','valueField'=>'commodity_name','conditions'=>array('commodity_code IN'=>$sub_commodities_details)))->toArray();
		$this->Set('sub_commodities_details',$sub_commodities_details);
		
		$chemist_details = $this->DmiLaboratoryChemistsDetails->find('all', array('conditions'=>array('customer_id IS'=>$customer_id, 'delete_status IS NULL', 'user_email_id IS NULL', 
																						/*'customer_once_no !='=>null,*/ 'by_renewal_form IS NULL'),'order'=>'id'))->toArray();
		//$chemist_details_values = $chemist_details['Dmi_laboratory_chemists_detail'];
		$this->set('chemist_details',$chemist_details);
		$chemist_commodity_value= array();
		
		$i=1;
		foreach($chemist_details as $chemist_detail)
		{
			$chemist_commodity_details = explode(',',(string) $chemist_detail['commodity']); #For Deprecations
			$chemist_details_values[$i] = $this->MCommodity->find('list', array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$chemist_commodity_details)))->toArray();
			$chemist_commodity_value[$i] = implode(',',$chemist_details_values[$i]);			
			$i=$i+1;	
		}
		$this->set('chemist_commodity_value',$chemist_commodity_value);	
		
		// find the payment details (Done by pravin 06/02/2018)
		$applicant_payment_detail = null;
		$list_applicant_payment_id = $this->DmiApplicantPaymentDetails->find('list', array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		
		if(!empty($list_applicant_payment_id)){
			$applicant_payment_details = $this->DmiApplicantPaymentDetails->find('all', array('conditions'=>array('id'=>max($list_applicant_payment_id))))->first();
			$applicant_payment_detail = $applicant_payment_details;
		}
		$this->set('applicant_payment_detail',$applicant_payment_detail);
		$this->generateApplicationPdf('/Applicationformspdfs/laboratoryFormsPdf');
		$this->redirect(array('controller'=>'customers','action'=>'secondary_home'));	
				
	}
	
	
	// Report Pdfs Start
	
	public function caReportPdf(){
		
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiUsers');
		$this->loadModel('MCommodity');
		$this->loadModel('MCommodityCategory');
		$this->loadModel('DmiSiteinspectionPremisesDetails');
		$this->loadModel('DmiSiteinspectionLaboratoryDetails');
		$this->loadModel('DmiCustomerLaboratoryDetails');
		$this->loadModel('DmiCustomerPremisesProfiles');
		$this->loadModel('DmiSiteinspectionOtherDetails');
		$this->loadModel('DmiAllDirectorsDetails'); 
			
		//Apply check " customer_id available status " (Done By pravin 27/10/2017)
		$customer_id = $this->Customfunctions->checkCustomerIdAvailable($this->Session->read('customer_id'));
		$this->set('customer_id',$customer_id);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);
		
		//check CA BEVO Applicant		
		$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
		$this->set('ca_bevo_applicant',$ca_bevo_applicant);
		
		$added_directors_details = $this->DmiAllDirectorsDetails->allDirectorsDetail($customer_id);	
		$this->set('added_directors_details',$added_directors_details);
			
		//check application have export unit
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
		$this->set('export_unit_status',$export_unit_status);
		
		// Fetch data from DMI firm Table					
		$customer_firm_data = $this->DmiFirms->firmDetails($customer_id);
		$this->set('customer_firm_data',$customer_firm_data);
		
		// to show firm address name form id		
		$firm_district_name = $this->Mastertablecontent->districtValueById($customer_firm_data['district']);
		$this->set('firm_district_name',$firm_district_name['district_name']);
		
		$firm_state_name = $this->Mastertablecontent->stateValueById($customer_firm_data['state']);
		$this->set('firm_state_name',$firm_state_name);
		
		//to show certificate type name
		$firm_certificate_type = $this->Mastertablecontent->CertificateTypeId($customer_firm_data['certification_type']);
		$this->set('firm_certificate_type',$firm_certificate_type);
		
		//get logged in user details//added on 31-07-2017 by Amol
		$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
		$user_full_name = $get_user_details['f_name'].' '.$get_user_details['l_name'];
		$this->set('user_full_name',$user_full_name);	
				
		// to show commodities and there selected sub-commodities
		$sub_commodity_array = explode(',',(string) $customer_firm_data['sub_commodity']); #For Deprecations
		$i=0;
		foreach($sub_commodity_array as $sub_commodity_id)
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
				
		// data from premises profile form					
		$premises_data = $this->DmiCustomerPremisesProfiles->sectionFormDetails($customer_id);
		$this->set('premises_data',$premises_data);
		
		// to show premises state & district address name form id		
		$premises_district_name = $this->Mastertablecontent->districtValueById($premises_data[0]['district']);
		$this->set('premises_district_name',$premises_district_name['district_name']);
		
		$premises_state_name = $this->Mastertablecontent->stateValueById($premises_data[0]['state']);
		$this->set('premises_state_name',$premises_state_name);
				
		// data from siteinspection premises details section					
		$premises_details = $this->DmiSiteinspectionPremisesDetails->sectionFormDetails($customer_id);	
		$this->set('premises_details',$premises_details);
		
		// data from siteinspection laboratory details section					
		$report_lab_details = $this->DmiSiteinspectionLaboratoryDetails->sectionFormDetails($customer_id);
		$this->set('report_lab_details',$report_lab_details);

		//laboratory details from application form section
		$form_laboratory_data = $this->DmiCustomerLaboratoryDetails->sectionFormDetails($customer_id);
		$this->set('form_laboratory_data',$form_laboratory_data);
				
		if($ca_bevo_applicant == 'no'){
			// to show laboratory address name form id			
			$lab_district_name = $this->Mastertablecontent->districtValueById($form_laboratory_data[0]['district']);
			$this->set('lab_district_name',$lab_district_name['district_name']);
			
			$lab_state_name = $this->Mastertablecontent->stateValueById($form_laboratory_data[0]['state']);
			$this->set('lab_state_name',$lab_state_name);
			
			//find laboratory type value
			$laboratory_type_name = $this->Mastertablecontent->laboratoryTypeById($form_laboratory_data[0]['laboratory_type']);
			$this->set('laboratory_type_name',$laboratory_type_name);	
		}
					
		// data from siteinspection other details section					
		$other_details = $this->DmiSiteinspectionOtherDetails->sectionFormDetails($customer_id);
		$this->set('other_details',$other_details);
		
		//call custome function from appcontroller to create pdf
		$this->generateReportPdf('/Applicationformspdfs/caReportPdf');				
			
		$this->redirect(array('controller'=>'dashboard','action'=>'home'));
		
	}		
		
	public function printingReportPdf(){
		
		$this->loadModel('DmiUsers');
		$this->loadModel('DmiPrintingFirmProfiles');
		$this->loadModel('DmiPrintingPremisesProfiles');
		$this->loadModel('DmiPrintingSiteinspectionReports');
		$this->loadModel('DmiAllDirectorsDetails');
		
		//Apply check " customer_id available status " (Done By pravin 27/10/2017)
		$customer_id = $this->Customfunctions->checkCustomerIdAvailable($this->Session->read('customer_id'));
		$this->set('customer_id',$customer_id);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);		
		
		//get logged in user details //added on 31-07-2017 by Amol
		$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
		$user_full_name = $get_user_details['f_name'].' '.$get_user_details['l_name'];
		$this->set('user_full_name',$user_full_name);
		
		$added_directors_details = $this->DmiAllDirectorsDetails->allDirectorsDetail($customer_id);	
		$this->set('added_directors_details',$added_directors_details);
		
		//for firm	
		$firm_profile_detail = $this->DmiPrintingFirmProfiles->sectionFormDetails($customer_id);
		$this->set('firm_profile_detail',$firm_profile_detail);
		
		$firm_state_value = $this->Mastertablecontent->stateValueById($firm_profile_detail[0]['state']);
		$this->set('firm_state_value',$firm_state_value);
		
		$firm_district_value = $this->Mastertablecontent->districtValueById($firm_profile_detail[0]['district']);
		$this->set('firm_district_value',$firm_district_value['district_name']);
		
		$business_type_value = $this->Mastertablecontent->businessTypeById($firm_profile_detail[0]['business_type']);
		$this->set('business_type_value',$business_type_value);
		
		// Takeing business year value from printing_business_year table by pravin 11-08-2017
		$business_years = $this->Mastertablecontent->printingBusinessYearById($firm_profile_detail[0]['business_years']);
		$this->set('business_years',$business_years);
		
		// for premises	
		$premises_profile_detail = $this->DmiPrintingPremisesProfiles->sectionFormDetails($customer_id);
		$this->set('premises_profile_detail',$premises_profile_detail);
		
		$premises_state_value = $this->Mastertablecontent->stateValueById($premises_profile_detail[0]['state']);
		$this->set('premises_state_value',$premises_state_value);
		
		$premises_district_value = $this->Mastertablecontent->districtValueById($premises_profile_detail[0]['district']);
		$this->set('premises_district_value',$premises_district_value['district_name']);
		
		// for printing siteinspection report	
		$printing_report_detail = $this->DmiPrintingSiteinspectionReports->sectionFormDetails($customer_id);
		$this->set('printing_report_detail',$printing_report_detail);
				
		$this->generateReportPdf('/Applicationformspdfs/printingReportPdf');
		
		$this->redirect(array('controller'=>'dashboard','action'=>'home'));		
	}
		
		
	// laboratory Siteinspection pdf by pravin 24/05/2017
	public function laboratoryReportPdf(){
			
		$this->loadModel('DmiUsers');
		$this->loadModel('DmiFirms');
		$this->loadModel('MCommodity');
		$this->loadModel('DmiLaboratoryFirmDetails');
		$this->loadModel('DmiLaboratorySiteinspectionReports');
		$this->loadModel('DmiAllDirectorsDetails');
		
		//Apply check " customer_id available status " (Done By pravin 27/10/2017)
		$customer_id = $this->Customfunctions->checkCustomerIdAvailable($this->Session->read('customer_id'));
		$this->set('customer_id',$customer_id);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);
		
		$added_directors_details = $this->DmiAllDirectorsDetails->allDirectorsDetail($customer_id);	
		$this->set('added_directors_details',$added_directors_details);
		
		//get logged in user details //added on 31-07-2017 by Amol
		$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
		$user_full_name = $get_user_details['f_name'].' '.$get_user_details['l_name'];
		$this->set('user_full_name',$user_full_name);
		
		$firm_detail = $this->DmiFirms->firmDetails($customer_id);
		$this->set('firm_detail',$firm_detail);
		
		$state_value = $this->Mastertablecontent->stateValueById($firm_detail['state']);
		$this->set('state_value',$state_value);
		
		$district_value = $this->Mastertablecontent->districtValueById($firm_detail['district']);
		$this->set('district_value',$district_value['district_name']);
		
		// Take sub commodities
		$sub_commodities_details = explode(',',(string) $firm_detail['sub_commodity']); #For Deprecations
		$sub_commodities_details = $this->MCommodity->find('list', array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$sub_commodities_details)))->toArray();
		$this->Set('sub_commodities_details',$sub_commodities_details);
		
		// Take Laboratory Type
		$laboratory_types = $this->DmiLaboratoryFirmDetails->sectionFormDetails($customer_id);
		
		$laboratory_type_value = $this->Mastertablecontent->laboratoryTypeById($laboratory_types[0]['laboratory_type']);
		$this->set('laboratory_type_value',$laboratory_type_value);
		
		$laboratory_report_detail = $this->DmiLaboratorySiteinspectionReports->sectionFormDetails($customer_id);
		$this->set('laboratory_report_detail',$laboratory_report_detail);
		
		$show_chemist_commodity_types = $this->MCommodity->find('list', array('keyField'=>'commodity_code','valueField'=>'commodity_name'))->toArray();
		$this->set('show_chemist_commodity_types',$show_chemist_commodity_types);
				
		$this->generateReportPdf('/Applicationformspdfs/laboratoryReportPdf');
		
		$this->redirect(array('controller'=>'dashboard','action'=>'home'));				
			
	}
	
	
	public function caRenewalFormPdf(){
		
		$this->loadModel('DmiFirms');
		$this->loadModel('MCommodity');
		$this->loadModel('DmiCaRenewalCommodityDetails');
		$this->loadModel('DmiGrantCertificatesPdfs');
		$this->loadModel('DmiApplicationCharges');
		$this->loadModel('DmiRenewalApplicantPaymentDetails');
		
		//added on 28-03-2018, to set default value
		$show_esigned_by = $this->Session->read('with_esign');
		$this->set('show_esigned_by',$show_esigned_by);
		
		$customer_id = $this->Session->read('username');
		$this->set('customer_id',$customer_id);

		//get nodal office of the applied CA
		$this->loadModel('DmiApplWithRoMappings');
		$get_office = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$this->set('get_office',$get_office);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);
		
		//check CA BEVO Applicant		
		$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
		$this->set('ca_bevo_applicant',$ca_bevo_applicant);
		
		//check application have export unit
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
		$this->set('export_unit_status',$export_unit_status);
		
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
		$primary_id_code = $split_customer_id[0];
		
		$firm_detail = $this->DmiFirms->firmDetails($customer_id);
		$this->set('firm_data',$firm_detail);
				
		$state_value = $this->Mastertablecontent->stateValueById($firm_detail['state']);
		$this->set('state_value',$state_value);
		
		$district_value = $this->Mastertablecontent->districtValueById($firm_detail['district']);
		$this->set('district_value',$district_value);
		
		
		//fetch commodities by id
		$selected_commodities = explode(',',(string) $firm_detail['sub_commodity']); #For Deprecations
		
																				//commented the 'display'=>'Y' condition on 22-03-2021, as conflicting the delete status flag between LIMS/DMI for listing commodities
																				//As in LIMS "Fat Spread" under "BEVO" not used and in DMI we use "Fat Spread" under "BEVO". and 'display' is 'N' for the field.
		$commodities = $this->MCommodity->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$selected_commodities,/*'display'=>'Y'*/)))->toArray();
		$this->set('commodities',$commodities);	
		

		//last records from renewal commodity details table
			$last_commodity_gradings = $this->DmiCaRenewalCommodityDetails->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,'is_latest'=>'yes')))->toArray();
			$this->set('last_commodity_gradings',$last_commodity_gradings);
			
			$year = array();
			$quantity_graded = array();
			$i=0;
			foreach($last_commodity_gradings as $each_grading)
			{
				$last_gradings_years = $this->DmiCaRenewalCommodityDetails->find('all',array('fields'=>'year','conditions'=>array('customer_id IS'=>$customer_id,'is_latest'=>'yes'),'group'=>'year', 'order'=>'year DESC'))->toArray();
				
				//print_r($last_gradings_years); exit;
				$p=1;
				foreach($last_gradings_years as $each_year)
				{
					$year[$p]= $each_year['year'];
					
				$p=$p+1;
				}
				
				$quantity_graded[$i] = $each_grading['quantity_graded'];
			
			$i=$i+1;	
			}
			
			$this->set('year',$year);
			$this->set('quantity_graded',$quantity_graded);
			
			
			
		//added on 25-07-2017 by Amol to get valid upto date
		$get_last_grant_date = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
		$last_grant_date = $get_last_grant_date['date'];

		$certificate_valid_upto = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$last_grant_date);
		$this->set('certificate_valid_upto',$certificate_valid_upto);
		
		//get renewal charges for CA. query added on 23-08-2017 by Amol
		$get_charges = $this->DmiApplicationCharges->find('all',array('conditions'=>array('certificate_type_id'=>4)))->first();
		$total_charges = $get_charges['charge'];
		$this->set('total_charges',$total_charges);
		
		
		// find the payment details (Done by pravin 06/02/2018)
		$applicant_payment_detail = null;
		$list_applicant_payment_id = $this->DmiRenewalApplicantPaymentDetails->find('list', array('fields'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		
		if(!empty($list_applicant_payment_id)){
			$applicant_payment_details = $this->DmiRenewalApplicantPaymentDetails->find('all', array('conditions'=>array('id'=>max($list_applicant_payment_id))))->first();
			$applicant_payment_detail = $applicant_payment_details;
		}
		$this->set('applicant_payment_detail',$applicant_payment_detail);
		
		$this->generateApplicationPdf('/Applicationformspdfs/caRenewalFormPdf');
		
		$this->redirect(array('controller'=>'customers','action'=>'secondary_home'));	
				
	}
	
	public function printingRenewalFormPdf(){
		
		$this->loadModel('DmiGrantCertificatesPdfs'); 
		$this->loadModel('DmiPackingTypes');
		$this->loadModel('DmiPrintingRenewalDetails');
		$this->loadModel('DmiRenewalApplicantPaymentDetails');
		$this->loadModel('DmiFirms');
		
		//added on 28-03-2018, to set default value
		$show_esigned_by = $this->Session->read('with_esign');
		$this->set('show_esigned_by',$show_esigned_by);
		
		$customer_id = $this->Session->read('username');
		$this->set('customer_id',$customer_id);

		//get nodal office of the applied CA
		$this->loadModel('DmiApplWithRoMappings');
		$get_office = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$this->set('get_office',$get_office);
				
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);
		
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
		$primary_id_code = $split_customer_id[0];		
		
		//below query added on 21-07-2017 by Amol
		$get_last_grant_date = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
		$last_grant_date = $get_last_grant_date['date'];
		 
		$validity_upto = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$last_grant_date);
		$this->set('validity_upto',$validity_upto);

		$firm_detail = $this->DmiFirms->firmDetails($customer_id);
		$this->set('firm_detail',$firm_detail);
		// Take Firm Detail
		
		$state_value = $this->Mastertablecontent->stateValueById($firm_detail['state']);
		$this->set('state_value',$state_value);
		
		$district_value = $this->Mastertablecontent->districtValueById($firm_detail['district']);
		$this->set('district_value',$district_value);	
		
		// Take Packing Material Type	
		$packaging_materials = explode(',',(string) $firm_detail['packaging_materials']); #For Deprecations
		$renewal_packaging_type = $this->DmiPackingTypes->find('list', array('valueField'=>array('packing_type'),'keyField'=>array('id'), 'conditions'=>array('id IN'=>$packaging_materials)))->toArray();	
		$this->set('renewal_packaging_type',$renewal_packaging_type);
		
		$section_form_details = $this->DmiPrintingRenewalDetails->sectionFormDetails($customer_id);		
		$this->set('section_form_details',$section_form_details);
		
		// find the payment details (Done by pravin 06/02/2018)
		$applicant_payment_detail = null;
		$list_applicant_payment_id = $this->DmiRenewalApplicantPaymentDetails->find('list', array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		
		if(!empty($list_applicant_payment_id)){
			$applicant_payment_details = $this->DmiRenewalApplicantPaymentDetails->find('all', array('conditions'=>array('id'=>max($list_applicant_payment_id))))->first();
			$applicant_payment_detail = $applicant_payment_details;
		}
		
		$this->set('applicant_payment_detail',$applicant_payment_detail);
		
		$this->generateApplicationPdf('/Applicationformspdfs/printingRenewalFormPdf');
		
		$this->redirect(array('controller'=>'customers','action'=>'secondary_home'));		
	}
	
	
	public function labRenewalFormPdf(){
		
		$this->loadModel('DmiGrantCertificatesPdfs');
		$this->loadModel('DmiApplicationCharges');
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiLaboratoryRenewalOtherDetails');
		$this->loadModel('MCommodity');
		$this->loadModel('DmiLaboratoryChemistsDetails');
		$this->loadModel('DmiRenewalApplicantPaymentDetails');
		
		//added on 28-03-2018, to set default value
		$show_esigned_by = $this->Session->read('with_esign');
		$this->set('show_esigned_by',$show_esigned_by);
		
		$customer_id = $this->Session->read('username');
		$this->set('customer_id',$customer_id);
		
		//get nodal office of the applied CA
		$this->loadModel('DmiApplWithRoMappings');
		$get_office = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$this->set('get_office',$get_office);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);
		
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
		$primary_id_code = $split_customer_id[0];
		
		
		//Added on 20-09-2017 check lab export unit 
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);	
		$this->set('export_unit_status',$export_unit_status);
		
		//added on 20-09-2017 by Amol to get valid upto date
		$get_last_grant_date = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>array('id desc')))->first();
		$last_grant_date = $get_last_grant_date['date'];
		$this->set('last_grant_date',$last_grant_date);	
		
		//get renewal charges for Lab. query added on 20-09-2017 by Amol
		$get_charges = $this->DmiApplicationCharges->find('all',array('conditions'=>array('certificate_type_id'=>6)))->first();
		$total_charges = $get_charges['charge'];
		$this->set('total_charges',$total_charges);
		
		
		// data from DMI Customer Table
		$firm_detail = $this->DmiFirms->firmDetails($customer_id);
		$this->set('firm_detail',$firm_detail);
		
		$section_form_details = $this->DmiLaboratoryRenewalOtherDetails->sectionFormDetails($customer_id);		
		$this->set('check_fields_result',$section_form_details[0]);
		
		$state_value = $this->Mastertablecontent->stateValueById($firm_detail['state']);
		$this->set('state_value',$state_value);
		
		$district_value = $this->Mastertablecontent->districtValueById($firm_detail['district']);
		$this->set('district_value',$district_value);	
		
		// Take laboratory commodity list
		$laboratory_commodity_list = explode(',',(string) $firm_detail['sub_commodity']); #For Deprecations
		$laboratory_commodity_values = $this->MCommodity->find('list', array('keyField'=>'commodity_code', 'valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$laboratory_commodity_list)))->toArray();
		$this->Set('laboratory_commodity_values',$laboratory_commodity_values);
		
		// Take chemist details		
		$chemist_details = $this->DmiLaboratoryChemistsDetails->find('all', array('conditions'=>array('customer_id IS'=>$customer_id, 'delete_status IS NULL', 
																						'user_email_id IS NULL', /*'customer_once_no'=>null,*/ 'by_renewal_form IS NOT NULL'),'order'=>'id'))->toArray();
																						
		//$chemist_details_values = $chemist_details['Dmi_laboratory_chemists_detail'];
		$this->set('chemist_details',$chemist_details);
		
		$chemist_commodity_value='';
		
		$i=1;
		foreach($chemist_details as $chemist_detail)
		{
			$chemist_commodity_details = explode(',',(string) $chemist_detail['commodity']); #For Deprecations
			$chemist_details_values[$i] = $this->MCommodity->find('list', array('keyField'=>'commodity_code', 'valueField'=>'commodity_name', 'conditions'=>array('commodity_code IN'=>$chemist_commodity_details)))->toArray();
			$chemist_commodity_value[$i] =implode(', ',$chemist_details_values[$i]);								
			
			$i=$i+1;	
		}						
		$this->set('chemist_commodity_value',$chemist_commodity_value);
		
		
		// find the payment details (Done by pravin 06/02/2018)
		$applicant_payment_detail = null;
		$list_applicant_payment_id = $this->DmiRenewalApplicantPaymentDetails->find('list', array('valueField'=>'id','conditions'=>array('customer_id IS'=>$customer_id)))->toArray();
		
		if(!empty($list_applicant_payment_id)){
			$applicant_payment_details = $this->DmiRenewalApplicantPaymentDetails->find('all', array('conditions'=>array('id'=>max($list_applicant_payment_id))))->first();
			$applicant_payment_detail = $applicant_payment_details;
		}
		$this->set('applicant_payment_detail',$applicant_payment_detail);
		
		$this->generateApplicationPdf('/Applicationformspdfs/labRenewalFormPdf');
		
		$this->redirect(array('controller'=>'customers','action'=>'secondary_home'));
			
	}
	
	public function grantCaCertificatePdf(){
				
		$this->loadModel('DmiFirms');		
		$this->loadModel('DmiUsers');
		$this->loadModel('MCommodity');
		$this->loadModel('MCommodityCategory');
		$this->loadModel('DmiCustomerFirmProfiles');
		$this->loadModel('DmiCustomerPremisesProfiles');
		$this->loadModel('DmiCustomerTblDetails');
		$this->loadModel('DmiAllDirectorsDetails');
		$this->loadModel('DmiRenewalFinalSubmits');
		$this->loadModel('DmiCustomerLaboratoryDetails');
		
		$customer_id = $this->Session->read('customer_id');
		$this->set('customer_id',$customer_id);
			
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);
		
		// Fetch grant date conditions get latest records.
		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
			
		//check CA BEVO Applicant		
		$ca_bevo_applicant = $this->Customfunctions->checkCaBevo($customer_id);
		$this->set('ca_bevo_applicant',$ca_bevo_applicant);		
		
		//check application have export unit
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
		$this->set('export_unit_status',$export_unit_status);	
			
		// Fetch data from DMI firm Table					
		$customer_firm_data = $this->DmiFirms->firmDetails($customer_id);
		$this->set('customer_firm_data',$customer_firm_data);
		
		//get logged in user details
		$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
		$user_full_name = $get_user_details['f_name'].' '.$get_user_details['l_name'];
		$this->set('user_full_name',$user_full_name);	
			
		// to show firm address name form id		
		$firm_district_name = $this->Mastertablecontent->districtValueById($customer_firm_data['district']);
		$this->set('firm_district_name',$firm_district_name['district_name']);
		
		$firm_state_name = $this->Mastertablecontent->stateValueById($customer_firm_data['state']);
		$this->set('firm_state_name',$firm_state_name);
		
		// to show commodities and there selected sub-commodities
		$sub_commodity_array = explode(',',(string) $customer_firm_data['sub_commodity']); #For Deprecations

		$i=0;
		foreach($sub_commodity_array as $sub_commodity_id)
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

		$firm_data = $this->DmiCustomerFirmProfiles->sectionFormDetails($customer_id);
		$this->set('firm_data',$firm_data);
		
		$business_type = $this->Mastertablecontent->businessTypeById($firm_data[0]['business_type']);	
		$this->set('business_type',$business_type);				
		
		$premises_data = $this->DmiCustomerPremisesProfiles->sectionFormDetails($customer_id);
		$this->set('premises_data',$premises_data);
		
		// to show premises address name form id		
		if($ca_bevo_applicant == 'no')
		{
			$premises_district_name = $this->Mastertablecontent->districtValueById($premises_data[0]['district']);
			$this->set('premises_district_name',$premises_district_name);
			
			$premises_state_name = $this->Mastertablecontent->stateValueById($premises_data[0]['state']);
			$this->set('premises_state_name',$premises_state_name);			
		}
				
		$laboratory_data = $this->DmiCustomerLaboratoryDetails->sectionFormDetails($customer_id);		
		$this->set('laboratory_data',$laboratory_data);
		
		$added_tbls_details = $this->DmiCustomerTblDetails->sectionFormDetails($customer_id);		
		$this->set('added_tbls_details',$added_tbls_details);		
		
		if($ca_bevo_applicant == 'no')
		{
			//find laboratory type value
			$laboratory_type_name = $this->Mastertablecontent->laboratoryTypeById($laboratory_data[0]['laboratory_type']);
			$this->set('laboratory_type_name',$laboratory_type_name);
			
			$laboratory_district_name = $this->Mastertablecontent->districtValueById($laboratory_data[0]['district']);
			$this->set('laboratory_district_name',$laboratory_district_name);
		
			$laboratory_state_name = $this->Mastertablecontent->stateValueById($laboratory_data[0]['state']);
			$this->set('laboratory_state_name',$laboratory_state_name);			
		}
			
		$added_directors_details = $this->DmiAllDirectorsDetails->allDirectorsDetail($customer_id);		
		$this->set('added_directors_details',$added_directors_details);

		//if called for re-esign process, make grant date condition blank, bcoz need to call all records
		//applied on 24-09-2021 by Amol
		if($this->Session->read('re_esigning')=='yes' && 
			($this->request->referer('/',true)=='/othermodules/re_esign_module' || $this->request->referer('/',true)=='/othermodules/update-firm-details')){//updated new condition on 24-12-2021 by Amol, re-esign for firm details updates
			$grantDateCondition = '';
		}
		
		//for renewal update in Printing grant certificate_type																															//this condition commented on 10-11-2017 by Amol, to show renewal details in grant preview.			
		$check_renewal_final_submit = $this->DmiRenewalFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, /*$grantDateCondition 'status'=>'approved', 'current_level'=>'level_1'*/)))->first();
		$this->set('check_renewal_final_submit',$check_renewal_final_submit);
		
		if(!empty($check_renewal_final_submit))
		{				
			//updated below code on 03-10-2020 by Amol
			$get_final_submitted_date = $this->DmiRenewalFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,/*$grantDateCondition,*/'status'=>'pending'),'order'=>'id ASC'))->toArray();
				
			$i=1;
			foreach($get_final_submitted_date as $each_date){
				
				$renewal_application_date[$i] = $each_date['created'];
				$i=$i+1;
			}
			$this->set('renewal_application_date',$renewal_application_date);
		}
			
		//get all records from grant table to manage multiple renewals
		//added on 03-10-2020 by Amol
		$this->loadModel('DmiGrantCertificatesPdfs');
		$get_grant_details = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'pdf_version ASC'))->toArray();
		
		$user_full_name = array();
		$certificate_valid_upto = array();
		$lastGrantDate = null;//added on 14-10-2021
		if(!empty($get_grant_details)){
			
			$i=0;
			foreach($get_grant_details as $each_grant){
				
				//to get application wise esigned user name
				$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$each_grant['user_email_id'])))->first();
				if(!empty($get_user_details)){
					
					$user_full_name[$i] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
				
				}else{
					
					$user_full_name[$i] = null;
				}
				$certificate_valid_upto[$i] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$each_grant['date']);
				
				$i=$i+1;
				$lastGrantDate = $each_grant['date'];//added on 14-10-2021
			}
			
			//to show current on going renewal details at last
			$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
			$user_full_name[$i] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
			
			$cert_grant_date = $pdf_date;
			$certificate_valid_upto[$i] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$cert_grant_date);
			
			
		}else{				
			//user details for first grant
			$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
			$user_full_name[0] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
			
			$cert_grant_date = $pdf_date;
			$certificate_valid_upto[0] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$cert_grant_date);
		}
		
		
		$this->set('get_grant_details',$get_grant_details);
		$this->set('user_full_name',$user_full_name);
		$this->set('certificate_valid_upto',$certificate_valid_upto);
		
		//added this condition on 08-06-2019 by Amol
		//to proceed for re esigning renewal grant if session is set and check previous URL
		if($this->Session->read('re_esigning')=='yes' && 
			($this->request->referer('/',true)=='/othermodules/re_esign_module' || $this->request->referer('/',true)=='/othermodules/update-firm-details')){//updated new condition on 24-12-2021 by Amol, re-esign for firm details updates
			
			//added below code and conditions on 08-01-2021 by Amol
			$user_full_name = array();
			$certificate_valid_upto = array();
			if(!empty($get_grant_details)){
				
				$i=0;
				foreach($get_grant_details as $each_grant){
					
					//to get application wise esigned user name
					$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$each_grant['user_email_id'])))->first();
					if(!empty($get_user_details)){
						
						$user_full_name[$i] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
					
					}else{
						
						$user_full_name[$i] = null;
					}
					$certificate_valid_upto[$i] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$each_grant['date']);
					
					$i=$i+1;
				}
			}
			
			$this->set('pdf_date',$this->Session->read('re_esign_grant_date'));
			//providing existing grant date in function to get correct validity date
			//$i-1 is used because it is for re-esign, no need to create new record, get last record
			$certificate_valid_upto[$i-1] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$this->Session->read('re_esign_grant_date'));
			
			//added lines below on 08-01-2021 by Amol
			$this->set('get_grant_details',$get_grant_details);
			$this->set('user_full_name',$user_full_name);																						   
			$this->set('certificate_valid_upto',$certificate_valid_upto);

			//This below line is added for the QR Code genration on Shankhpal [16-08-2022]	
			$firm_name_forqr = $firm_data[0]['firm_name'];	
			$data = [$customer_id,$pdf_date,$certificate_valid_upto,$firm_name_forqr];
			$result_for_qr = $this->Customfunctions->getQrCode($data);
			$this->set('result_for_qr',$result_for_qr);

			$this->generateGrantCerticateToReEsignPdf('/Applicationformspdfs/grantCaCertificatePdf'); 
			//$this->create_grant_certificate_pdf_to_re_esign();
			
		}else{

			//added on 14-10-2021
			//to change last record values as renewal certificate will be issued on DDo approval and further grant by RO/SO
			//so the last record in the grant table will be updated with new esigned file and user name (RO/SO)
			if($this->Session->read('ren_esign_process')=='yes'){

				$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
				$user_full_name[$i-1] = $get_user_details['f_name'].' '.$get_user_details['l_name'];

				$certificate_valid_upto[$i-1] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$lastGrantDate);
				
				//below array_pop() is used to remove last element from the array, 
				//to show existing renewal record only, in renewal grant history list on certificate
				array_pop($user_full_name);
				array_pop($certificate_valid_upto);

				$this->set('user_full_name',$user_full_name);
				$this->set('certificate_valid_upto',$certificate_valid_upto);
			}
			
			//This below line is added for the QR Code genration on Shankhpal [16-08-2022]	
			$firm_name_forqr = $firm_data[0]['firm_name'];	
			$data = [$customer_id,$pdf_date,$certificate_valid_upto,$firm_name_forqr];
			$result_for_qr = $this->Customfunctions->getQrCode($data);
			$this->set('result_for_qr',$result_for_qr);
			
			$this->generateGrantCerticatePdf('/Applicationformspdfs/grantCaCertificatePdf'); 
					
		}
			
		$this->redirect(array('controller'=>'hoinspections','action'=>'grantCertificatesList'));
		
	}
	
	
	public function grantPrintingCertificatePdf(){
		
		$this->loadModel('DmiFirms');		
		$this->loadModel('DmiUsers');
		$this->loadModel('DmiPackingTypes');
		$this->loadModel('DmiPrintingPremisesProfiles');
		$this->loadModel('DmiPrintingFirmProfiles');
		$this->loadModel('DmiAllDirectorsDetails');
		$this->loadModel('DmiRenewalFinalSubmits');
		
		$customer_id = $this->Session->read('customer_id');
		$this->set('customer_id',$customer_id);
			
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);
			
		// Fetch grant date conditions get latest records.
		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
		
		// Fetch data from DMI firm Table					
		$customer_firm_data = $this->DmiFirms->firmDetails($customer_id);	
		$this->set('customer_firm_data',$customer_firm_data);
		
		//get logged in user details//added on 31-07-2017 by Amol
		$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
		$user_full_name = $get_user_details['f_name'].' '.$get_user_details['l_name'];
		$this->set('user_full_name',$user_full_name);		
		
		// show firm address name form id		
		$firm_district_name = $this->Mastertablecontent->districtValueById($customer_firm_data['district']);
		$this->set('firm_district_name',$firm_district_name['district_name']);
		
		$firm_state_name = $this->Mastertablecontent->stateValueById($customer_firm_data['state']);
		$this->set('firm_state_name',$firm_state_name);
		
		// to show commodities and there selected sub-commodities	
		$packaging_materials = explode(',',(string) $customer_firm_data['packaging_materials']); #For Deprecations
		$packaging_types = $this->DmiPackingTypes->find('list', array('keyField'=>'id','valueField'=>'packing_type', 'conditions'=>array('id IN'=>$packaging_materials)))->toArray();			 
		$this->set('packaging_types',$packaging_types);
					
		// data from Printing premises profile form					
		$premises_data = $this->DmiPrintingPremisesProfiles->sectionFormDetails($customer_id);
		$this->set('premises_data',$premises_data);	
		
		// data from firm profile form	
		$firm_data = $this->DmiPrintingFirmProfiles->sectionFormDetails($customer_id);
		$this->set('firm_data',$firm_data);
		
		// Fetch Business Type
		$business_type = $this->Mastertablecontent->businessTypeById($firm_data[0]['business_type']);
		$this->set('business_type',$business_type);
			
		// to show premises address name form id
		$premises_district_name = $this->Mastertablecontent->districtValueById($premises_data[0]['district']);
		$this->set('premises_district_name',$premises_district_name['district_name']);
		
		$premises_state_name = $this->Mastertablecontent->stateValueById($premises_data[0]['state']);
		$this->set('premises_state_name',$premises_state_name);
		
		// show added directors table	
		$added_directors_details = $this->DmiAllDirectorsDetails->allDirectorsDetail($customer_id);	
		$this->set('added_directors_details',$added_directors_details);

		//if called for re-esign process, make grant date condition blank, bcoz need to call all records
		//applied on 24-09-2021 by Amol
		if($this->Session->read('re_esigning')=='yes' && 
			($this->request->referer('/',true)=='/othermodules/re_esign_module' || $this->request->referer('/',true)=='/othermodules/update-firm-details')){//updated new condition on 24-12-2021 by Amol, re-esign for firm details updates
			$grantDateCondition = '';
		}
		
		//for renewal update in Ca grant certificate_type																															//this condition commented on 10-11-2017 by Amol, to show renewal details in grant preview.			
		$check_renewal_final_submit = $this->DmiRenewalFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, /*$grantDateCondition ,'status'=>'approved', 'current_level'=>'level_1'*/)))->first();
		$this->set('check_renewal_final_submit',$check_renewal_final_submit);
		
		if(!empty($check_renewal_final_submit))
		{
			//updated below code on 03-10-2020 by Amol
			$get_final_submitted_date = $this->DmiRenewalFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,/*$grantDateCondition,*/ 'status'=>'pending'),'order'=>'id ASC'))->toArray();
			
			$i=1;
			foreach($get_final_submitted_date as $each_date){
				
				$renewal_application_date[$i] = $each_date['created'];
			
			$i=$i+1;
			}				
			$this->set('renewal_application_date',$renewal_application_date);

		}
			
		//get all records from grant table to manage multiple renewals
		//added on 0-10-2020 by Amol
		$this->loadModel('DmiGrantCertificatesPdfs');
		$get_grant_details = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'pdf_version ASC'))->toArray();
		
		$user_full_name = array();
		$certificate_valid_upto = array();
		$lastGrantDate = null;//added on 14-10-2021
		if(!empty($get_grant_details)){
			
			$i=0;
			foreach($get_grant_details as $each_grant){
				
				//to get application wise esigned user name
				$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$each_grant['user_email_id'])))->first();
				if(!empty($get_user_details)){
					
					$user_full_name[$i] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
				
				}else{
					
					$user_full_name[$i] = null;
				}
				$certificate_valid_upto[$i] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$each_grant['date']);
				
				$i=$i+1;
				$lastGrantDate = $each_grant['date'];//added on 14-10-2021
			}
			
			//to show current on going renewal details at last
			$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
			$user_full_name[$i] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
			
			$cert_grant_date = $pdf_date;
			$certificate_valid_upto[$i] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$cert_grant_date);
			
			
		}else{				
			//user details for first grant
			$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
			$user_full_name[0] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
			
			$cert_grant_date = $pdf_date;
			$certificate_valid_upto[0] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$cert_grant_date);
		}
		
		
		$this->set('get_grant_details',$get_grant_details);
		$this->set('user_full_name',$user_full_name);
																								
		$this->set('certificate_valid_upto',$certificate_valid_upto);
		
		//added this condition on 08-06-2019 by Amol
		//to proceed for re esigning renewal grant if session is set and check previous URL
		if($this->Session->read('re_esigning')=='yes' && 
			($this->request->referer('/',true)=='/othermodules/re_esign_module' || $this->request->referer('/',true)=='/othermodules/update-firm-details')){//updated new condition on 24-12-2021 by Amol, re-esign for firm details updates
		
			//added below code and conditions on 08-01-2021 by Amol
			$user_full_name = array();
			$certificate_valid_upto = array();
			if(!empty($get_grant_details)){
				
				$i=0;
				foreach($get_grant_details as $each_grant){
					
					//to get application wise esigned user name
					$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$each_grant['user_email_id'])))->first();
					if(!empty($get_user_details)){
						
						$user_full_name[$i] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
					
					}else{
						
						$user_full_name[$i] = null;
					}
					$certificate_valid_upto[$i] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$each_grant['date']);
					
					$i=$i+1;
				}
			}
			
			$this->set('pdf_date',$this->Session->read('re_esign_grant_date'));
			//providing existing grant date in function to get correct validity date
			//$i-1 is used because it is for re-esign, no need to create new record, get last record
			$certificate_valid_upto[$i-1] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$this->Session->read('re_esign_grant_date'));
			
			//added below lines on 08-01-2021 by Amol
			$this->set('get_grant_details',$get_grant_details);
			$this->set('user_full_name',$user_full_name);
			$this->set('certificate_valid_upto',$certificate_valid_upto);
			
			//This below line is added for the QR Code genration on Shankhpal [16-08-2022]	
			$firm_name_forqr = $firm_data[0]['firm_name'];	
			$data = [$customer_id,$pdf_date,$certificate_valid_upto,$firm_name_forqr];
			$result_for_qr = $this->Customfunctions->getQrCode($data);
			$this->set('result_for_qr',$result_for_qr);				

			$this->generateGrantCerticateToReEsignPdf('/Applicationformspdfs/grantPrintingCertificatePdf'); 
			//$this->create_grant_certificate_pdf_to_re_esign();
			
		}else{

			//added on 14-10-2021
			//to change last record values as renewal certificate will be issued on DDo approval and further grant by RO/SO
			//so the last record in the grant table will be updated with new esigned file and user name (RO/SO)
			if($this->Session->read('ren_esign_process')=='yes'){

				$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
				$user_full_name[$i-1] = $get_user_details['f_name'].' '.$get_user_details['l_name'];

				$certificate_valid_upto[$i-1] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$lastGrantDate);
			
				//below array_pop() is used to remove last element from the array, 
				//to show existing renewal record only, in renewal grant history list on certificate
				array_pop($user_full_name);
				array_pop($certificate_valid_upto);

				$this->set('user_full_name',$user_full_name);																						   
				$this->set('certificate_valid_upto',$certificate_valid_upto);
			}

			//This below line is added for the QR Code genration on Shankhpal [16-08-2022]	
			$firm_name_forqr = $firm_data[0]['firm_name'];	
			$data = [$customer_id,$pdf_date,$certificate_valid_upto,$firm_name_forqr];
			$result_for_qr = $this->Customfunctions->getQrCode($data);
			$this->set('result_for_qr',$result_for_qr);				
			
			$this->generateGrantCerticatePdf('/Applicationformspdfs/grantPrintingCertificatePdf'); 
						
		}
			
		$this->redirect(array('controller'=>'hoinspections','action'=>'grantCertificatesList'));
	}
	
	
	public function grantLaboratoryCertificatePdf(){
		
		$this->loadModel('DmiFirms');		
		$this->loadModel('DmiUsers');
		$this->loadModel('MCommodity');
		$this->loadModel('MCommodityCategory');
		$this->loadModel('DmiLaboratoryFirmDetails');
		$this->loadModel('DmiAllDirectorsDetails');
		$this->loadModel('DmiRenewalFinalSubmits');
				
		//Apply check " customer_id available status " (Done By pravin 27/10/2017)
		$customer_id = $this->Session->read('customer_id');
		$this->set('customer_id',$customer_id);
		
		// Fetch grant date conditions get latest records.
		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);
			
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);	
		
		//check application have export unit
		$export_unit_status = $this->Customfunctions->checkApplicantExportUnit($customer_id);
		$this->set('export_unit_status',$export_unit_status);
							
		// Fetch data from DMI firm Table					
		$customer_firm_data = $this->DmiFirms->firmDetails($customer_id);	
		$this->set('customer_firm_data',$customer_firm_data);
		
		//get logged in user details//added on 31-07-2017 by Amol
		$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
		$user_full_name = $get_user_details['f_name'].' '.$get_user_details['l_name'];	
		$this->set('user_full_name',$user_full_name);
			
		// to show firm address name form id		
		$firm_district_name = $this->Mastertablecontent->districtValueById($customer_firm_data['district']);
		$this->set('firm_district_name',$firm_district_name['district_name']);
		
		$firm_state_name = $this->Mastertablecontent->stateValueById($customer_firm_data['state']);
		$this->set('firm_state_name',$firm_state_name);
		
		// to show commodities and there selected sub-commodities
		$sub_commodity_array = explode(',',(string) $customer_firm_data['sub_commodity']); #For Deprecations

		$i=0;
		foreach($sub_commodity_array as $sub_commodity_id)
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
		
		// Fetch data from firm profile form	
		$firm_data = $this->DmiLaboratoryFirmDetails->sectionFormDetails($customer_id);
		$this->set('firm_data',$firm_data);
		
		// Fetch firm business type	
		$business_type = $this->Mastertablecontent->businessTypeById($firm_data[0]['business_type']);	
		$this->set('business_type',$business_type);
		
		// Fetch firm laboratory type	
		$laboratory_type_value = $this->Mastertablecontent->laboratoryTypeById($firm_data[0]['laboratory_type']);	
		$this->set('laboratory_type',$laboratory_type_value);
		
		//Fetch added directors table details
		$added_directors_details = $this->DmiAllDirectorsDetails->allDirectorsDetail($customer_id);	
		$this->set('added_directors_details',$added_directors_details);

		//if called for re-esign process, make grant date condition blank, bcoz need to call all records
		//applied on 24-09-2021 by Amol
		if($this->Session->read('re_esigning')=='yes' && 
			($this->request->referer('/',true)=='/othermodules/re_esign_module' || $this->request->referer('/',true)=='/othermodules/update-firm-details')){//updated new condition on 24-12-2021 by Amol, re-esign for firm details updates
			$grantDateCondition = '';
		}
		
		//for renewal update in Ca grant certificate_type																															//this condition commented on 10-11-2017 by Amol, to show renewal details in grant preview.			
		$check_renewal_final_submit = $this->DmiRenewalFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, /*$grantDateCondition, 'status'=>'approved', 'current_level'=>'level_1'*/)))->first();
		$this->set('check_renewal_final_submit',$check_renewal_final_submit);
		
		if(!empty($check_renewal_final_submit))
		{
			//updated below code on 03-10-2020 by Amol
			$get_final_submitted_date = $this->DmiRenewalFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$customer_id,/*$grantDateCondition,*/ 'status'=>'pending'),'order'=>'id ASC'))->toArray();
			
			$i=1;
			foreach($get_final_submitted_date as $each_date){
				
				$renewal_application_date[$i] = $each_date['created'];
			
			$i=$i+1;
			}				
			$this->set('renewal_application_date',$renewal_application_date);

		}
			
		//get all records from grant table to manage multiple renewals
		//added on 03-10-2020 by Amol
		$this->loadModel('DmiGrantCertificatesPdfs');
		$get_grant_details = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'pdf_version ASC'))->toArray();
		
		$user_full_name = array();
		$certificate_valid_upto = array();
		$lastGrantDate = null;//added on 14-10-2021
		if(!empty($get_grant_details)){
			
			$i=0;
			foreach($get_grant_details as $each_grant){
				
				//to get application wise esigned user name
				$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$each_grant['user_email_id'])))->first();
				if(!empty($get_user_details)){
					
					$user_full_name[$i] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
				
				}else{
					
					$user_full_name[$i] = null;
				}
				$certificate_valid_upto[$i] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$each_grant['date']);
				
				$i=$i+1;
				$lastGrantDate = $each_grant['date'];//added on 14-10-2021
			}
			
			//to show current on going renewal details at last
			$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
			$user_full_name[$i] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
			
			$cert_grant_date = $pdf_date;
			$certificate_valid_upto[$i] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$cert_grant_date);
			
			
		}else{				
			//user details for first grant
			$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
			$user_full_name[0] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
			
			$cert_grant_date = $pdf_date;
			$certificate_valid_upto[0] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$cert_grant_date);
		}
		
		
		$this->set('get_grant_details',$get_grant_details);
		$this->set('user_full_name',$user_full_name);
																								
		$this->set('certificate_valid_upto',$certificate_valid_upto);
		
		//added this condition on 08-06-2019 by Amol
		//to proceed for re esigning renewal grant if session is set and check previous URL
		if($this->Session->read('re_esigning')=='yes' && 
			($this->request->referer('/',true)=='/othermodules/re_esign_module' || $this->request->referer('/',true)=='/othermodules/update-firm-details')){//updated new condition on 24-12-2021 by Amol, re-esign for firm details updates
			
			//added below code and conditions on 08-01-2021 by Amol
			$user_full_name = array();
			$certificate_valid_upto = array();
			if(!empty($get_grant_details)){
				
				$i=0;
				foreach($get_grant_details as $each_grant){
					
					//to get application wise esigned user name
					$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$each_grant['user_email_id'])))->first();
					if(!empty($get_user_details)){
						
						$user_full_name[$i] = $get_user_details['f_name'].' '.$get_user_details['l_name'];
					
					}else{
						
						$user_full_name[$i] = null;
					}
					$certificate_valid_upto[$i] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$each_grant['date']);
					
					$i=$i+1;
				}
			}
			
			$this->set('pdf_date',$this->Session->read('re_esign_grant_date'));
			//providing existing grant date in function to get correct validity date
			//$i-1 is used because it is for re-esign, no need to create new record, get last record
			$certificate_valid_upto[$i-1] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$this->Session->read('re_esign_grant_date'));
			
			//added lines below on 08-01-2021 by Amol
			$this->set('get_grant_details',$get_grant_details);
			$this->set('user_full_name',$user_full_name);
			$this->set('certificate_valid_upto',$certificate_valid_upto);

			//This below line is added for the QR Code genration on Shankhpal [16-08-2022]	
			$firm_name_forqr = $customer_firm_data['firm_name'];
			$data = [$customer_id,$pdf_date,$certificate_valid_upto,$firm_name_forqr];
			$result_for_qr = $this->Customfunctions->getQrCode($data);
			$this->set('result_for_qr',$result_for_qr);

			$this->generateGrantCerticateToReEsignPdf('/Applicationformspdfs/grantLaboratoryCertificatePdf'); 
			
		}else{
			
			//added on 14-10-2021
			//to change last record values as renewal certificate will be issued on DDo approval and further grant by RO/SO
			//so the last record in the grant table will be updated with new esigned file and user name (RO/SO)
			if($this->Session->read('ren_esign_process')=='yes'){

				$get_user_details = $this->DmiUsers->find('all',array('conditions'=>array('email IS'=>$this->Session->read('username'))))->first();
				$user_full_name[$i-1] = $get_user_details['f_name'].' '.$get_user_details['l_name'];

				$certificate_valid_upto[$i-1] = $this->Customfunctions->getCertificateValidUptoDate($customer_id,$lastGrantDate);
			
				//below array_pop() is used to remove last element from the array, 
				//to show existing renewal record only, in renewal grant history list on certificate
				array_pop($user_full_name);
				array_pop($certificate_valid_upto);

				$this->set('user_full_name',$user_full_name);																						   
				$this->set('certificate_valid_upto',$certificate_valid_upto);
			}
			
			//This below line is added for the QR Code genration on Shankhpal [16-08-2022]	
			$firm_name_forqr = $customer_firm_data['firm_name'];			
			$data = [$customer_id,$pdf_date,$certificate_valid_upto,$firm_name_forqr];
			$result_for_qr = $this->Customfunctions->getQrCode($data);
			$this->set('result_for_qr',$result_for_qr);				
			
			$this->generateGrantCerticatePdf('/Applicationformspdfs/grantLaboratoryCertificatePdf'); 
		}
			
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations

		if($split_customer_id[1]==3 && $export_unit_status=='yes'){
			
			$this->redirect(array('controller'=>'hoinspections','action'=>'grantCertificatesList'));

		}else{
			
			$this->redirect(array('controller'=>'hoinspections','action'=>'grantCertificatesList'));
		}
		
	}
	
	
	//method to show All grant certificates list		
	public function grantCertificatesList(){
			
		//$this->layout = 'admin_dashboard';
		$this->viewBuilder()->setLayout('admin_dashboard');
		$user_email_id = $this->Session->read('username');
		$this->loadModel('DmiGrantCertificatesPdfs');
		$this->loadModel('DmiRoOffices');
		$this->loadModel('DmiFirms');
																		//changed query logic, now taking all grant certiicates except uploaded old certificates //on 06-03-2018 by Amol
		$fetch_all_granted_pdf = $this->DmiGrantCertificatesPdfs->find('all',array('fields'=>'customer_id','group'=>'customer_id','conditions'=>array('user_email_id !='=>'old_application')))->toArray();
		
		$ca=0;
		$pp=0;
		$lb=0;  // (Add by pravin 19/05/2017)
		$all_ca_grant_certificates = array();
		$all_printing_grant_certificates = array();
		$all_laboratory_grant_certificates = array(); // (Add by pravin 19/05/2017)
		$f_name = array();
		
		foreach($fetch_all_granted_pdf as $each_pdf)
		{
		
			$customer_id = $each_pdf['customer_id'];
			$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
			$district_code = $split_customer_id[2]; //added on 06-03-2018 by Amol to get application district code.
			
			//updated and added code to get Office table details from appl mapping Model
			$this->loadModel('DmiApplWithRoMappings');
			$get_ro_details = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
			$ro_email_id = $get_ro_details['ro_email_id'];
			
			//get firm name for the customer id //add on 23-11-2019 by Amol
			$get_firm_details = $this->DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('customer_id IS'=>$customer_id)))->first();
			$firm_name = $get_firm_details['firm_name'];
			
			//if current RO of application district and logged in user id matched
			if($user_email_id == $ro_email_id){ //applied condition on 06-03-2018 by Amol
				
				if($split_customer_id[1] == 1)
				{ 																																		//changed query logic on 06-03-2018 by Amol
					//$fetch_ca_max_id = $this->DmiGrantCertificatesPdfs->find('first',array('fields'=>'id','conditions'=>array('customer_id'=>$customer_id,/* 'user_email_id'=>$user_email_id*/)));
					
					$all_ca_grant_certificates_list = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
					
					$all_ca_grant_certificates[$ca] = $all_ca_grant_certificates_list;
					$f_name['ca'][$ca] = $firm_name;//add on 23-11-2019 by Amol
					
					$ca=$ca+1;
				}
				
				
				if($split_customer_id[1] == 2)
				{																																		//changed query logic on 06-03-2018 by Amol
					//$fetch_printing_max_id = $this->Dmi_grant_certificates_pdf->find('first',array('fields'=>'max(id)','conditions'=>array('customer_id'=>$customer_id,/* 'user_email_id'=>$user_email_id*/)));
					
					$all_printing_grant_certificates_list = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
					
					$all_printing_grant_certificates[$pp] = $all_printing_grant_certificates_list;
					$f_name['pp'][$pp] = $firm_name;//add on 23-11-2019 by Amol
					
					$pp=$pp+1;
				}
				
				// Find the Final Granded laboratory Application pdf list file By pravin (19/05/2017)
				if($split_customer_id[1] == 3)
				{																																			//changed query logic on 06-03-2018 by Amol
					//$fetch_laboratory_max_id = $this->Dmi_grant_certificates_pdf->find('first',array('fields'=>'max(id)','conditions'=>array('customer_id'=>$customer_id,/* 'user_email_id'=>$user_email_id*/)));
					
					$all_laboratory_grant_certificates_list = $this->DmiGrantCertificatesPdfs->find('all',array('conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id DESC'))->first();
					
					$all_laboratory_grant_certificates[$lb] = $all_laboratory_grant_certificates_list;
					$f_name['lb'][$lb] = $firm_name;//add on 23-11-2019 by Amol
					
					$lb=$lb+1;
				}
		
			}
		}

		$this->set('all_ca_grant_certificates',$all_ca_grant_certificates);
		$this->set('all_printing_grant_certificates',$all_printing_grant_certificates);
		$this->set('all_laboratory_grant_certificates',$all_laboratory_grant_certificates); // (Add by pravin 19/05/2017)
		$this->set('f_name',$f_name);//add on 23-11-2019 by Amol
	}
	
	

	
	//this function is created to generate pdf using tcpdf plugin
	//This is called at place of Mpdf output function with required parameteres.
	//on 23-01-2020 by Amol
	public function callTcpdf($html,$mode,$customer_id,$pdf_for){
	
		$with_esign = $this->Session->read('with_esign');
		$current_level = $this->Session->read('current_level');
		$file_name = $this->Session->read('pdf_file_name');

		//get application type and current level from session
		//added on 16-09-2021 by Amol
		$appl_type = $this->Session->read('application_type');
		$current_level = $this->Session->read('current_level');
		
		/* $view = new View($this, false);
		$view->layout = null; */
		
		//if application is for old or w/o esign then store pdf in files folder directly.
		if($pdf_for == 'old' || $with_esign == 'no'){
			
			//as per distributed folder structure, get folder name as per application to store pdf
			//on 04-10-2021 by Amol
			$folderName = $this->Customfunctions->getFolderName($customer_id);
		
			$file_path = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/applications/'.$folderName.'/'.$file_name;
			
		}else{
			$file_path = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/'.$file_name;
		}
		
		//generatin pdf starts here
		//create new pdf using tcpdf including signature apprearence to generate its hash below
		require_once(ROOT . DS .'vendor' . DS . 'tcpdf' . DS . 'tcpdf.php');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);	
			
			// set default monospaced font
		//	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
		//	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		//	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(5);
		//$pdf->SetFont('krutidev010', '', 10);

		//added condition if renewal certificate and user is PAO/DDO
		//then signature appearence not required
		//on 16-09-2021 by Amol
		if (!($appl_type==2 && $current_level=='pao')) {

			//only for save mode 'F' else no need in preview mode 'I'
			if($mode == 'F' && $pdf_for != 'old' && $with_esign != 'no') {
				//to set signature content block in pdf
				$info = array();
				$pdf->my_set_sign('', '', '', '', 2, $info);
			}
		}
			
			
			$pdf->AddPage();//print_r($html);exit;	
			
			$pdf->writeHTML($html, true, false, true, false, '');

			//get signer details
			//if applicant
			if(preg_match("/^[0-9]+\/[0-9]+\/[A-Z]+\/[0-9]+$/", $this->Session->read('username'),$matches)==1)
			{						
				$esigner = $this->Session->read('firm_name');
				$desg = '';
			
			//if DMI user 
			}else{
				if($current_level=='level_2'){$desg = "\n".'(Inspecting Officer)';}
				elseif($current_level=='level_3'){$desg = "\n".'(Regional Officer)';}
				
				$esigner = $this->Session->read('f_name').' '.$this->Session->read('l_name');
	
				//added condition on 13-10-2022 by AMol, to masked chemist name from replica allotment letter
				if ($pdf_for=="replica") {$esigner = 'Authorized Chemist';}
			}
			
			//start to add bg image for the 'esigned by' cell on document
			// get the current page break margin
			$bMargin = $pdf->getBreakMargin();
			// get current auto-page-break mode
			$auto_page_break = $pdf->getAutoPageBreak();		
			// restore auto-page-break status
			$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
			// set the starting point for the page content
			$pdf->setPageMark();
			//end to add bg image on cell

		//added condition if renewal certificate and user is PAO/DDO
		//then signature appearence not required
		//on 16-09-2021 by Amol
		if (!($appl_type==2 && $current_level=='pao')) {

			//only for save mode 'F' else no need in preview mode 'I'
			//to show esigned by block on pdf
			if($mode == 'F' && $pdf_for != 'old' && $with_esign != 'no') {
				// set bacground image on cell
				$img_file = 'img/checked.png';
				$pdf->Image($img_file, 165, 266, 8, 8, '', '', '', false, 300, '', false, false, 0);
				
				$pdf->SetFont('times', '', 8);
				$pdf->setCellPaddings(1, 2, 1, 1);
				$pdf->MultiCell(40, 10, 'Esigned by: '.$esigner."\n".'Date: '.$_SESSION['sign_timestamp'], 1, '', 0, 1, 150, 265, true);

				// define active area for signature appearance
				$pdf->setSignatureAppearance(150, 265, 40, 10);
			}
		}
			
			// reset pointer to the last page
			$pdf->lastPage();
			
			// Clean any content of the output buffer
			if(ob_get_length() > 0) {
				ob_end_clean();
			}

			//Close and output PDF document
			$pdf->my_output($file_path, $mode);
			//generatin pdf ends here		
	}
	
	
	//application pdf for approval to use 15 digit code
	public function applPdf15DigitCode(){ 
		
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiDistricts');
		$this->loadModel('DmiStates');
		$this->loadModel('MCommodity');
		$this->loadModel('MCommodityCategory');
		
		
		//added on 27-03-2018, to set default value
		$show_esigned_by = $this->Session->read('with_esign');
		$this->set('show_esigned_by',$show_esigned_by);		

		$customer_id = $this->Session->read('username');
		$this->set('customer_id',$customer_id);
		
		//get nodal office of the applied CA
		$this->loadModel('DmiApplWithRoMappings');
		$get_office = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$this->set('get_office',$get_office);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);					
		
		// data from DMI firm Table					
		$fetch_customer_firm_data = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$customer_firm_data = $fetch_customer_firm_data;
		$this->set('customer_firm_data',$customer_firm_data);		
		
		// to show firm address name form id	
		$fetch_district_name = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$customer_firm_data['district'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
		$firm_district_name = $fetch_district_name['district_name'];
		$this->set('firm_district_name',$firm_district_name);
		
		$fetch_state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$customer_firm_data['state'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
		$firm_state_name = $fetch_state_name['state_name'];
		$this->set('firm_state_name',$firm_state_name);		
		
		// to show commodities and there selected sub-commodities
		$sub_commodity_array = explode(',',(string) $customer_firm_data['sub_commodity']); #For Deprecations

		$i=0;
		foreach($sub_commodity_array as $sub_commodity_id)
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

		
		$this->generateApplicationPdf('/Applicationformspdfs/applPdf15DigitCode');	
		
		// $this->redirect(array('controller'=>'customers','action'=>'secondary_home'));	
		
	}
	
	//Report pdf for approval to use 15 digit code
	public function reportPdf15DigitCode(){
		
		$this->loadModel('DmiFirms');
		$this->loadModel('Dmi15DigitSiteinspectionReports');
		
		//Apply check " customer_id available status " (Done By pravin 27/10/2017)
		$customer_id = $this->Session->read('customer_id');
		$this->set('customer_id',$customer_id);
		
		$firm_detail = $this->DmiFirms->firmDetails($customer_id);
		$this->set('firm_detail',$firm_detail);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);		
		
		$firm_state_value = $this->Mastertablecontent->stateValueById($firm_detail['state']);
		$this->set('firm_state_value',$firm_state_value);
		
		$firm_district_value = $this->Mastertablecontent->districtValueById($firm_detail['district']);
		$this->set('firm_district_value',$firm_district_value['district_name']);
		
		//siteinspection report	
		$report_detail = $this->Dmi15DigitSiteinspectionReports->sectionFormDetails($customer_id);
		$this->set('report_detail',$report_detail[0]);
				
		$this->generateReportPdf('/Applicationformspdfs/reportPdf15DigitCode');
		
		$this->redirect(array('controller'=>'dashboard','action'=>'home'));		
	}


	//grant certificate for 15 digit code application
	public function grant15DigitCertificate(){
				
		$this->loadModel('DmiFirms');		
		$this->loadModel('DmiUsers');
		$this->loadModel('MCommodity');
		$this->loadModel('MCommodityCategory');
		
		$customer_id = $this->Session->read('customer_id');
		$this->set('customer_id',$customer_id);
			
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);

		// Fetch grant date conditions get latest records.
		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

		//get application submission date
		$this->loadModel('Dmi15DigitFinalSubmits');
		$get_appl_date = $this->Dmi15DigitFinalSubmits->find('all',array('fields'=>'created','conditions'=>array('status'=>'pending',$grantDateCondition)))->first();		
		$appl_date = $get_appl_date['created'];
		$this->set('appl_date',$appl_date);
			
		// Fetch data from DMI firm Table					
		$firm_details = $this->DmiFirms->firmDetails($customer_id);
		$this->set('firm_details',$firm_details);	
			
		// to show firm address name form id		
		$firm_district_name = $this->Mastertablecontent->districtValueById($firm_details['district']);
		$this->set('firm_district_name',$firm_district_name['district_name']);
		
		$firm_state_name = $this->Mastertablecontent->stateValueById($firm_details['state']);
		$this->set('firm_state_name',$firm_state_name);
		
		// to show commodities and there selected sub-commodities
		$sub_commodity_array = explode(',',(string) $firm_details['sub_commodity']); #For Deprecations

		$i=0;
		foreach($sub_commodity_array as $sub_commodity_id)
		{			
			$fetch_commodity_id = $this->MCommodity->find('all',array('conditions'=>array('commodity_code IS'=>$sub_commodity_id)))->first();
			$commodity_id[$i] = $fetch_commodity_id['category_code'];			
			$sub_commodity_data[$i] =  $fetch_commodity_id;			
			$i=$i+1;
		}

		$commodity_names = '';
		foreach($sub_commodity_data as $each){
			$commodity_names .= $each['commodity_name'].', ';
		}		
		$this->set('commodity_names',$commodity_names);
		
		//This below line is added for the QR Code genration on Shankhpal [16-08-2022]	
		$result_for_qr = $this->Customfunctions->getQrCode($customer_id,'FDC');
		$this->set('result_for_qr',$result_for_qr);
		
		$this->generateGrantCerticatePdf('/Applicationformspdfs/grant15DigitCertificate'); 

		$this->redirect(array('controller'=>'hoinspections','action'=>'grantCertificatesList'));

	}
	
	
	
	//application pdf for approval to use E-code
	public function applPdfECode(){ 
		
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiDistricts');
		$this->loadModel('DmiStates');
		$this->loadModel('MCommodity');
		$this->loadModel('MCommodityCategory');
		
		
		//added on 27-03-2018, to set default value
		$show_esigned_by = $this->Session->read('with_esign');
		$this->set('show_esigned_by',$show_esigned_by);		

		$customer_id = $this->Session->read('username');
		$this->set('customer_id',$customer_id);
		
		//get nodal office of the applied CA
		$this->loadModel('DmiApplWithRoMappings');
		$get_office = $this->DmiApplWithRoMappings->getOfficeDetails($customer_id);
		$this->set('get_office',$get_office);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);					
		
		// data from DMI firm Table					
		$fetch_customer_firm_data = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$customer_firm_data = $fetch_customer_firm_data;
		$this->set('customer_firm_data',$customer_firm_data);		
		
		// to show firm address name form id	
		$fetch_district_name = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$customer_firm_data['district'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
		$firm_district_name = $fetch_district_name['district_name'];
		$this->set('firm_district_name',$firm_district_name);
		
		$fetch_state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$customer_firm_data['state'], 'OR'=>array('delete_status IS NULL','delete_status ='=>'no'))))->first();
		$firm_state_name = $fetch_state_name['state_name'];
		$this->set('firm_state_name',$firm_state_name);		
		
		// to show commodities and there selected sub-commodities
		$sub_commodity_array = explode(',',(string) $customer_firm_data['sub_commodity']); #For Deprecations

		$i=0;
		foreach($sub_commodity_array as $sub_commodity_id)
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

		
		$this->generateApplicationPdf('/Applicationformspdfs/applPdfECode');	
		
		// $this->redirect(array('controller'=>'customers','action'=>'secondary_home'));	
		
	}
	
	//Report pdf for approval to use E-code
	public function reportPdfECode(){
		
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiECodeSiteinspectionReports');
		
		//Apply check " customer_id available status " (Done By pravin 27/10/2017)
		$customer_id = $this->Session->read('customer_id');
		$this->set('customer_id',$customer_id);
		
		$firm_detail = $this->DmiFirms->firmDetails($customer_id);
		$this->set('firm_detail',$firm_detail);
		
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);		
		
		$firm_state_value = $this->Mastertablecontent->stateValueById($firm_detail['state']);
		$this->set('firm_state_value',$firm_state_value);
		
		$firm_district_value = $this->Mastertablecontent->districtValueById($firm_detail['district']);
		$this->set('firm_district_value',$firm_district_value['district_name']);
		
		//siteinspection report	
		$report_detail = $this->DmiECodeSiteinspectionReports->sectionFormDetails($customer_id);
		$this->set('report_detail',$report_detail[0]);
				
		$this->generateReportPdf('/Applicationformspdfs/reportPdfECode');
		
		$this->redirect(array('controller'=>'dashboard','action'=>'home'));		
	}


//grant certificate for E-code application
	public function grantECodeCertificate(){
				
		$this->loadModel('DmiFirms');		
		$this->loadModel('DmiUsers');
		$this->loadModel('MCommodity');
		$this->loadModel('MCommodityCategory');
		
		$customer_id = $this->Session->read('customer_id');
		$this->set('customer_id',$customer_id);
			
		$pdf_date = date('d-m-Y');
		$this->set('pdf_date',$pdf_date);

		// Fetch grant date conditions get latest records.
		$grantDateCondition = $this->Customfunctions->returnGrantDateCondition($customer_id);

		//get application submission date
		$this->loadModel('DmiECodeFinalSubmits');
		$get_appl_date = $this->DmiECodeFinalSubmits->find('all',array('fields'=>'created','conditions'=>array('status'=>'pending',$grantDateCondition)))->first();		
		$appl_date = $get_appl_date['created'];
		$this->set('appl_date',$appl_date);
			
		// Fetch data from DMI firm Table					
		$firm_details = $this->DmiFirms->firmDetails($customer_id);
		$this->set('firm_details',$firm_details);	
			
		// to show firm address name form id		
		$firm_district_name = $this->Mastertablecontent->districtValueById($firm_details['district']);
		$this->set('firm_district_name',$firm_district_name['district_name']);
		
		$firm_state_name = $this->Mastertablecontent->stateValueById($firm_details['state']);
		$this->set('firm_state_name',$firm_state_name);
		
		// to show commodities and there selected sub-commodities
		$sub_commodity_array = explode(',',(string) $firm_details['sub_commodity']); #For Deprecations

		$i=0;
		foreach($sub_commodity_array as $sub_commodity_id)
		{			
			$fetch_commodity_id = $this->MCommodity->find('all',array('conditions'=>array('commodity_code IS'=>$sub_commodity_id)))->first();
			$commodity_id[$i] = $fetch_commodity_id['category_code'];			
			$sub_commodity_data[$i] =  $fetch_commodity_id;			
			$i=$i+1;
		}

		$commodity_names = '';
		foreach($sub_commodity_data as $each){
			$commodity_names .= $each['commodity_name'].', ';
		}		
		$this->set('commodity_names',$commodity_names);
		
		//get applicant wise E-Code from table, and if not present then generate new and enter record
		//added on 24-11-2021 by Amol
		$this->loadModel('DmiECodeForApplicants');
		$getGrantedEcode = $this->DmiECodeForApplicants->find('all',array('fields'=>'e_code','conditions'=>array('customer_id'=>$customer_id),'order'=>'id desc'))->first();
		if(!empty($getGrantedEcode)){
			$granted_e_code = $getGrantedEcode['e_code'];
			$eCode = $granted_e_code;
		}else{
			//fetch last recorded E-Code from the table, add one and generate new
			$getLastEcode = $this->DmiECodeForApplicants->find('all',array('fields'=>'e_code','order'=>'id desc'))->first();
			if(!empty($getLastEcode)){
				$lastECode = $getLastEcode['e_code'];
			}else{
				$lastECode = 'E-0';
			}
			
			
			$splitEcode = explode('-',(string) $lastECode); #For Deprecations
			$newEcode = 'E-'.$splitEcode[1]+1;
			
			//enter new eocde record in table for current applicant
			$DmiECodeForApplicantsEntity = $this->DmiECodeForApplicants->newEntity(array(
				
				'customer_id'=>$customer_id,
				'e_code'=>$newEcode,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s')
			));
			$this->DmiECodeForApplicants->save($DmiECodeForApplicantsEntity);
			
			$eCode = $newEcode;
		}
		$this->set('eCode',$eCode);
		
		//This below line is added for the QR Code genration on Shankhpal [16-08-2022]	
		$data = [$customer_id,$eCode];
		$result_for_qr = $this->Customfunctions->getQrCode($data,'ECode');
		$this->set('result_for_qr',$result_for_qr);
		
		$this->generateGrantCerticatePdf('/Applicationformspdfs/grantECodeCertificate'); 

		$this->redirect(array('controller'=>'hoinspections','action'=>'grantCertificatesList'));

	}
	
	
}	
?>