<?php

namespace App\Controller;

use Cake\Event\Event;
use App\Network\Request\Request;
use App\Network\Response\Response;
use Cake\Datasource\ConnectionManager;

class EcodeController extends AppController {

		var $name = 'Ecode';

		public function initialize(): void
		{
			parent::initialize();

			$this->loadComponent('RequestHandler');
			$this->viewBuilder()->setHelpers(['Form','Html','Time']);
			$this->viewBuilder()->setLayout('secondary_customer');

		}

		public function beforeFilter($event) {

			parent::beforeFilter($event);	

			$customer_last_login = $this->Customfunctions->customerLastLogin();
        	$this->set('customer_last_login', $customer_last_login);

			// Checked final submit status, on 10-08-2021 by Amol
			//to show "Confirm Replica" and "Replica alloted list" conditional menu from chemist home layout
			$chemist_id = $this->Session->read('username');
			$this->loadModel('DmiChemistFinalSubmits');
			$final_submit_record = $this->DmiChemistFinalSubmits->find('all',array('conditions'=>array('customer_id IS'=>$chemist_id),'order'=>'id desc'))->first();
			
			$final_status = '';
			if(!empty($final_submit_record)){
				$final_status = $final_submit_record['status'];
			}
			$this->set('final_submit_status',$final_status);

		}

		public function replicaApplication(){
			
			$this->viewBuilder()->setLayout('replica_appl_layout');
			$customer_id = $this->Session->read('username');
			
			$this->loadModel('DmiFirms');
			$this->loadModel('MCommodity');
			$this->loadModel('DmiECodeAllotmentDetails');
			$this->loadModel('MGradeDesc');
			$this->loadModel('DmiAllTblsDetails');
			$this->loadModel('DmiPackingTypes');
			$this->loadModel('DmiReplicaUnitDetails');
			$this->loadModel('DmiCaPpLabMapings'); // added by shankhpal shende on 18/10/2022
			$message = '';
			$message_theme = '';
			$redirect_to = '';
			
			// added by Amol on 21-10-2022 as per replica controller
			$attached_lab = $this->DmiCaPpLabMapings->find('list',array('keyField'=>'id','valueField'=>'lab_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id asc'))->toList();
			//get printing list
			$attached_pp = $this->DmiCaPpLabMapings->find('list',array('keyField'=>'id','valueField'=>'pp_id','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id asc'))->toList();     
				
			//get array
			$attached_lab_data = $this->DmiCaPpLabMapings->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'lab_id IS NOT NULL'),'order'=>'id asc'))->first();
			$attached_pp_data = $this->DmiCaPpLabMapings->find('all',array('conditions'=>array('customer_id IS'=>$customer_id, 'pp_id IS NOT NULL'),'order'=>'id asc'))->first();
		
			//first check if this packer have any chemist incharge or not, elae show alert
			$this->loadModel('DmiChemistAllotments');
			$this->loadModel('CommGrade');
			
			$check_che_incharge = $this->DmiChemistAllotments->find('all',array('fields'=>'chemist_id','conditions'=>array('customer_id IS'=>$customer_id,'status'=>1,'incharge'=>'yes')))->first();
			if (empty($check_che_incharge) || empty($attached_pp_data) || empty($attached_lab_data)) {
				
				$message_var = '<b>Note: Before Replica Self Generation following must be done.</b>
								<br>1. You need to register a Chemist from "Apply For-><b>Chemist Registration</b>" then login with Chemist id, fill forms and submit for approval, Once approved set the chemist incharge.
								<br>2. You need to attach Printing Press and Laboratory from the menu "Apply For-><b>Attach Printing Press/Lab</b>".
								<br>3. You need to Apply for the Advance Payment from the menu "Apply For-><b>Advance Payment</b>".';
				
				$message = $message_var;
				$message_theme = 'info';
				$redirect_to = '../customers/secondary_home';
				
			}else{

				//get packer details
				$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();		

				//get CA granted E-Code number
				$this->loadModel('DmiECodeForApplicants');
				$get_e_code = $this->DmiECodeForApplicants->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
				$firm_details['e_code'] = $get_e_code['e_code'];
				
				$this->set('firm_details',$firm_details);
				
				//list of authorized laboratory
				
				//$lab_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/3/'.'%','delete_status IS Null'),'order'=>'firm_name asc'))->toArray();
				
				// added by shankhpal shende for display only attached lab on 18/10/2022
				$lab_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/3/'.'%','delete_status IS NULL','id IN'=>$attached_lab),'order'=>'firm_name asc'))->toArray();
				
				$this->set('lab_list',$lab_list);
			
				//get packer wise commodity list
				$commodity_ids = explode(',',(string) $firm_details['sub_commodity']); #For Deprecations
				$commodity_list = $this->MCommodity->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name','conditions'=>array('commodity_code IN'=>$commodity_ids)))->toArray();
				
				// commented by shankhpal Shende on 26/10/2022 for [On loading Set Grade for selected commodity]
				//$grade_list = $this->MGradeDesc->find('list',array('keyField'=>'grade_code','valueField'=>'grade_desc','conditions'=>array('display'=>'Y'),'order'=>'grade_code asc'))->toArray();
				
				$get_grade = $this->CommGrade->find('all',array('fields'=>'grade_code','conditions'=>array('commodity_code IN'=>$commodity_ids),'group'=>'grade_code'))->toArray();
			
				foreach($get_grade as $val)
				{
					$get_grade_desc = $this->MGradeDesc->find('all',array('fields'=>array('grade_code','grade_desc'),'conditions'=>array('grade_code IN'=>$val['grade_code']),'group'=>array('grade_code','grade_desc')))->first();
					$grade_list[$get_grade_desc['grade_code']] = $get_grade_desc['grade_desc'];
				}
				$tbl_list = $this->DmiAllTblsDetails->find('list',array('keyField'=>'id','valueField'=>'tbl_name','conditions'=>array('customer_id IS'=>$customer_id,'delete_status IS Null'),'order'=>'id asc'))->toArray();
				$packaging_material_list = $this->DmiPackingTypes->find('list',array('keyField'=>'id','valueField'=>'packing_type','conditions'=>array('delete_status IS Null'),'order'=>'id asc'))->toArray();
				
				// commented by shankhpal shende on 18/10/2022
				//$printers_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/2/'.'%','delete_status IS Null'),'order'=>'firm_name asc'))->toArray();
				
				$printers_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/2/'.'%','delete_status IS Null','id IN'=>$attached_pp),'order'=>'firm_name asc'))->toArray();

				//fetch last reocrds from table, if empty set default value
				$dataArray = $this->DmiECodeAllotmentDetails->getSectionData($customer_id);
				
				//to show selected lab in list
				if(!empty($dataArray)){
					
					$selected_lab = $dataArray[0]['grading_lab'];			
				}else{
					$selected_lab = '';
				}
				$this->set('selected_lab',$selected_lab);
				$this->set('dataArray',$dataArray);

				//create array as per the column to display in table
				$tableD = array();
				
				// common add more Table Header Array
					$tableD['label'] = array(
						'0' => array(
							'0' => array(
								'col' 		=> 'Sr.no',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'1' => array(
								'col' 		=> 'Commodity',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'2' => array(
								'col' 		=> 'Grade',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'3' => array(
								'col' 		=> 'TBL',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'4' => array(
								'col' 		=> 'Packaging Material',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'5' => array(
								'col' 		=> 'Authorized Printer',
								'colspan' 	=> '2',
								'rowspan' 	=> '2'
							),
							'6' => array(
								'col' 		=> 'Packet Size',
								'colspan' 	=> '2',
								'rowspan' 	=> '1'
							),
							'7' => array(
								'col' 		=> 'No. of Packets',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'8' => array(
								'col' 		=> 'Total Quantity Gross (Kg/Ltr)',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'9' => array(
								'col' 		=> 'Rate of Label Charge(Rs.) ',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'10' => array(
								'col' 		=> 'Total Label Charges(Rs.)',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
							'11' => array(
								'col' 		=> 'Balance Agmark Replica No.',
								'colspan' 	=> '1',
								'rowspan' 	=> '2'
							),
						),
						'1' => array(
							'0' => array(
								'col' 		=> 'Size',
								'colspan' 	=> '1',
								'rowspan' 	=> '1'
							),
							'1' => array(
								'col' 		=> 'Unit',
								'colspan' 	=> '1',
								'rowspan' 	=> '1'
							)

						)
					);
					
				//converting list array in required foramt for common add more table
				$commodity_list1 = array();
				$grade_list1 = array();	
				$tbl_list1 = array();
				$packaging_material_list1 = array();
				$printers_list1 = array();
				
				//commodity list array
				foreach($commodity_list as $key => $value){

					$commodity_list1[] = array(
						'vall' => $key,
						'label' => $value
					);
				}
				
				//grade list array
				foreach($grade_list as $key => $value){

					$grade_list1[] = array(
						'vall' => $key,
						'label' => $value
					);
				}
				
				//TBL list array
				foreach($tbl_list as $key => $value){

					$tbl_list1[] = array(
						'vall' => $key,
						'label' => $value
					);
				}
				
				//Packaging material list array
				foreach($packaging_material_list as $key => $value){

					$packaging_material_list1[] = array(
						'vall' => $key,
						'label' => $value
					);
				}
				
				//Printers list array
				foreach($printers_list as $key => $value){

					$printers_list1[] = array(
						'vall' => $key,
						'label' => $value
					);
				}


				// common add more Table Input Array
				foreach($dataArray as $row){

				//	$row = $row['Dmi_replica_allotment_detail'];
					$unit_list1 = array();
					if(!empty($row['packet_size_unit'])){
						
						$get_unit = $this->DmiReplicaUnitDetails->find('all',array('fields'=>array('unit'),'conditions'=>array('id IS'=>$row['packet_size_unit'])))->first();
						$unit = $get_unit['unit'];
						
						$unit_list = $this->DmiReplicaUnitDetails->find('list',array('keyField'=>'id','valueField'=>'sub_unit','conditions'=>array('unit IS'=>$unit),'order'=>'id asc'))->toArray();
					
						foreach($unit_list as $key => $value){

							$unit_list1[] = array(
								'vall' => $key,
								'label' => $value
							);
						}
					}else{
						$unit_list1 = array();
					}

					$tableD['input'][] = array(
						
							'0' => array(
				
								'name'		=> null,
								'type'		=> null,
								'valid'		=> null,
								'length'	=> null
							),
							'1' => array(
								'name'		=> 'commodity',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $commodity_list1,
								'selected'	=> $row['commodity'],
								'class'		=> 'form-control commodity',
								'id'		=> 'commodity',
								
							),
							'2' => array(
								'name'		=> 'grade',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $grade_list1,
								'selected'	=> $row['grade'],
								'class'		=> 'form-control grade',
								'id'		=> 'grade'
							),
							'3' => array(
								'name'		=> 'tbl',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $tbl_list1,
								'selected'	=> $row['tbl'],
								'class'		=> 'form-control tbl',
								'id'		=> 'tbl'
							),
							'4' => array(
								'name'		=> 'packaging_material',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $packaging_material_list1,
								'selected'	=> $row['packaging_material'],
								'class'		=> 'form-control packaging_material',
								'id'		=> 'packaging_material'
							),
							'5' => array(
								'name'		=> 'authorized_printer',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $printers_list1,
								'selected'	=> $row['authorized_printer'],
								'class'		=> 'form-control authorized_printer',
								'id'		=> 'authorized_printer'
							),
							'6' => array(
								'name'		=> 'view_printer',
								'type'		=> 'icon',
								'class'		=> 'mt-2 fa fa-eye view_printer',
								'id'		=> 'view_printer',
								'title'		=> 'View Selected Printer details'
							),
							'7' => array(
								'name'		=> 'packet_size',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['packet_size'],
								'length'	=> '50',
								'class'		=> 'form-control packet_size',
								'id'		=> 'packet_size'

							),
							'8' => array(
								'name'		=> 'packet_size_unit',
								'type'		=> 'select',
								'valid'		=> 'text',
								'option'	=> $unit_list1,
								'selected'	=> $row['packet_size_unit'],
								'length'	=> '50',
								'class'		=> 'form-control packet_size_unit',
								'id'		=> 'packet_size_unit'

							),
							'9' => array(
								'name'		=> 'no_of_packets',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['no_of_packets'],
								'length'	=> '50',
								'class'		=> 'form-control no_of_packets',
								'id'		=> 'no_of_packets'

							),
							'10' => array(
								'name'		=> 'total_quantity',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['total_quantity'],
								'length'	=> '50',
								'class'		=> 'form-control readonly total_quantity',
								'id'		=> 'total_quantity'

							),
							'11' => array(
								'name'		=> 'label_charge',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['label_charge'],
								'length'	=> '50',
								'class'		=> 'form-control readonly label_charge',
								'id'		=> 'label_charge'

							),
							'12' => array(
								'name'		=> 'total_label_charges',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['total_label_charges'],
								'length'	=> '50',
								'class'		=> 'form-control readonly total_label_charges',
								'id'		=> 'total_label_charges'

							),
							'13' => array(
								'name'		=> 'bal_agmark_replica',
								'type'		=> 'text',
								'valid'		=> 'text',
								'value'		=> $row['bal_agmark_replica'],
								'length'	=> '100',
								'class'		=> 'form-control bal_agmark_replica',
								'id'		=> 'bal_agmark_replica'

							),

						
					);
			
				}


				$tableForm[] = $tableD;

				$jsonTableForm = json_encode($tableForm);
				$this->set('tableForm',$jsonTableForm);
				
				
				
				//get balance amount for the applicant from transaction table
				$this->loadModel('DmiAdvPaymentTransactions');
				$bal_amt = 0;
				$get_bal = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>'balance_amount','conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();			
				if(!empty($get_bal)){
					$bal_amt = $get_bal['balance_amount'];
				}
				$this->set('bal_amt',$bal_amt);
				
				//to save post data
				if (null!==($this->request->getData('save'))){
					
					//set delete flag for last records with allotment status Null, to show only current added/updated records
					//the alloted records will be status 1, which will not touch, and to be keeped for logs
					$date = date('Y-m-d H:i:s');
					$this->DmiECodeAllotmentDetails->updateAll(array('delete_status'=>"yes",'modified'=>"$date"),array('customer_id IS'=>$customer_id,'allot_status IS Null'));

					//in E-code replica application, the E-code is already granted to packer
					//so no need to further generate any serial or series, just a single number will be printed on all packets.
					$postData = $this->request->getData();
					
					//generate packer unique id from table id
					$postData['ca_unique_no'] = $this->getCaUniqueid($firm_details['id']);

					if($this->DmiECodeAllotmentDetails->saveFormDetails($postData,$firm_details['e_code'],$firm_details['e_code'])==true){
					
						
						//get chemist in-charge id to send SMS/email
						$this->loadModel('DmiChemistAllotments');
						$chemist_incharge = $this->DmiChemistAllotments->find('all',array('fields'=>'chemist_id','conditions'=>array('customer_id IS'=>$customer_id,'status'=>1,'incharge'=>'yes')))->first();
						$chemist_id = $chemist_incharge['chemist_id'];

						#SMS: Applicant registered for E Code
						//$this->DmiSmsEmailTemplates->sendmessage(81,$customer_id); #Packer
						//$this->DmiSmsEmailTemplates->sendmessage(82,$chemist_id); #Chemist
					
						$message = 'The application for E-Code Replica is saved successfully. It is now available to Chemist for confirmation';
						$redirect_to = 'replica_application';					
						
					}
				}
				
			}
			
			
			$this->set('message',$message);
			$this->set('message_theme',$message_theme);
			$this->set('redirect_to',$redirect_to);
			if(!empty($message)){$this->render('/element/message_boxes');}
					
		}
	
	
	//to get charge as per commodity for replica serial number, when unit selected in row	
	public function getCommodityWiseCharge(){
		
		$this->autoRender = false;
		
		$commodity_id = $_POST['commodity_id'];
		
		//get charge from table
		$this->loadModel('DmiReplicaChargesDetails');
		
		$get_charge = $this->DmiReplicaChargesDetails->find('all',array('conditions'=>array('commodity_code IS'=>$commodity_id)))->first();
		
		if(!empty($get_charge)){
			
			$charge = $get_charge['charges'];
			$unit = $get_charge['unit'];
			$min_qty = $get_charge['min_qty'];
			
			//get unit list from table as per commodity list
			$this->loadModel('DmiReplicaUnitDetails');
			$unit_list = $this->DmiReplicaUnitDetails->find('list',array('keyField'=>'id','valueField'=>'sub_unit','conditions'=>array('unit IS'=>$unit),'order'=>'id asc'))->toArray();
			
			$result = array('charge'=>$charge,'unit_list'=>$unit_list,'min_qty'=>$min_qty);
			
			echo '~'.json_encode($result).'~';
		
		}else{
			echo '~No Charge~';
		}
		exit;
					
	}
    
	//to get grade as per commodity for replica serial number, when unit selected in row
	// added by shankhpal shende on 22/08/2022	
	public function getCommodityWiseGrade() {
		$this->autoRender = false;
		$commodity_id = $_POST['commodity_id'];
		$this->loadModel('CommGrade');
		$this->loadModel('MGradeDesc');
		$get_grade = $this->CommGrade->find('all',array('fields'=>'grade_code','conditions'=>array('commodity_code IS'=>$commodity_id),'group'=>'grade_code'))->toArray();

		foreach($get_grade as $val)
		{
			
			$get_grade_desc = $this->MGradeDesc->find('all',array('fields'=>array('grade_code','grade_desc'),'conditions'=>array('grade_code IN'=>$val['grade_code']),'group'=>array('grade_code','grade_desc')))->first();
	        $desc[$get_grade_desc['grade_code']] = $get_grade_desc['grade_desc'];
		
		}
		if (!empty($desc)) {
          
			$result = array('Grade'=>$desc);
		
			
			echo '~'.json_encode($result).'~';
		
		} else {
			echo '~No Grade~';
		}
		exit;
	}


	//to get gross quantity and total charges when enter no. of packets		
	public function getGrossQuantityAndTotalCharge(){
			
		$this->autoRender = false;
		$packet_size = $_POST['packet_size'];
		$sub_unit_id = $_POST['sub_unit_id'];
		$no_of_packets = $_POST['no_of_packets'];
		$label_charge = $_POST['label_charge'];
		$commodity_id = $_POST['commodity_id'];
		
		//get conversion factor as per sub unit
		$this->loadModel('DmiReplicaUnitDetails');
		$get_factor = $this->DmiReplicaUnitDetails->find('all',array('fields'=>'conversion_factor', 'conditions'=>array('id IS'=>$sub_unit_id)))->first();
		$conversion_factor = $get_factor['conversion_factor'];
		
		//get min gross quantity for selected commodity to campair
		$this->loadModel('DmiReplicaChargesDetails');
		$get_charge = $this->DmiReplicaChargesDetails->find('all',array('fields'=>'min_qty','conditions'=>array('commodity_code IS'=>$commodity_id)))->first();
		$min_grpss_qty = $get_charge['min_qty'];
		
		$gross_quantity = ($packet_size*$no_of_packets)/$conversion_factor;
		
		//if calulated gross qty is more tahn min qty, then get new total, else calculate total with min qty
		if($gross_quantity > $min_grpss_qty){				
			$total_charges = $gross_quantity*$label_charge;			
		}else{
			$total_charges = $min_grpss_qty*$label_charge;
			$gross_quantity = $min_grpss_qty;
		}
		
		$result = array('gross_quantity'=>$gross_quantity,'total_charges'=>$total_charges);
		
		echo '~'.json_encode($result).'~';
		exit;
	}
	
	
	//to get gross quantity and total charges when enter no. of packets		
	public function getPrinterDetails(){
		
		$this->autoRender = false;
		$printer_id = $_POST['printer_id'];
		
		//get conversion factor as per sub unit
		$this->loadModel('DmiFirms');
		$get_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$printer_id)))->first();
		$name = $get_details['firm_name'];
		$cert_no = $get_details['customer_id'];
		$address = $get_details['street_address'];
		$district_id = $get_details['district'];
		$state_id = $get_details['state'];
		
		//get district name
		$this->loadModel('DmiDistricts');
		$get_district = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$district_id)))->first();
		$district = $get_district['district_name'];
		
		//get state name
		$this->loadModel('DmiStates');
		$get_state = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS'=>$state_id)))->first();
		$state = $get_state['state_name'];
		
		$msg = "<b>Name:</b> ".$name."<br>";
		$msg .= "<b>Certificate No:</b> ".$cert_no."<br>";
		$msg .= "<b>Address:</b> ".$address."<br>";
		$msg .= "<b>District:</b> ".$district."<br>";
		$msg .= "<b>State:</b> ".$state;
		
		echo '~'.$msg.'~';
		exit;
	}


	//to get balance amount for ajax call_user_func
	public function checkBalAmt(){
			
		$this->autoRender = false;

		//get balance amount for the applicant from transaction table
		$this->loadModel('DmiAdvPaymentTransactions');
		$bal_amt = 0;
		$get_bal = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>'balance_amount','conditions'=>array('customer_id IS'=>$this->Session->read('username')),'order'=>'id desc'))->first();
		
		if(!empty($get_bal)){
			$bal_amt = $get_bal['balance_amount'];
		}
		
		echo '~'.$bal_amt.'~';
		exit;
	}
	
	
//below functions are for chemist dashboard for approval of applications

	public function replicaApplList(){
		
		$this->viewBuilder()->setLayout('chemist_home_layout');
		$this->loadModel('DmiECodeAllotmentDetails');
		$this->loadModel('DmiChemistAllotments');
		
		$this->Session->delete('ca_unique_no');
		
		//get packers for which the chemist alloted
		$chemist_id = $this->Session->read('username');	
		$chemist_packer_list = $this->DmiChemistAllotments->find('list',array('keyField'=>'id','valueField'=>'customer_id','conditions'=>array('chemist_id IS'=>$chemist_id,'status'=>1,'incharge'=>'yes'),'group'=>'id,customer_id'))->toArray();

		//get replica applications list for this chemist
		$replica_appl_list = array();
		if(!empty($chemist_packer_list)){
			$replica_appl_list = $this->DmiECodeAllotmentDetails->find('all',array('fields'=>array('customer_id','ca_unique_no'),'conditions'=>array('customer_id IN'=>$chemist_packer_list,'allot_status IS Null','delete_status IS Null'),'group'=>'customer_id,ca_unique_no'))->toArray();
		}
		$this->set('replica_appl_list',$replica_appl_list);
		
	}
	
	//get ca unique no and redirect to application page
	public function replicaApplListId($ca_unique_no){
		
		$this->Session->write('ca_unique_no',$ca_unique_no);
		$this->redirect('/Ecode/replicaApplicationApproval');
	}

	//method to show replica application on chemist side for verification
	public function replicaApplicationApproval(){
			
		$this->viewBuilder()->setLayout('replica_appl_approval_layout');
		
		$this->loadModel('DmiFirms');
		$this->loadModel('MCommodity');
		$this->loadModel('DmiECodeAllotmentDetails');
		$this->loadModel('MGradeDesc');
		$this->loadModel('DmiAllTblsDetails');
		$this->loadModel('DmiPackingTypes');
		$this->loadModel('DmiReplicaUnitDetails');
		
		//get firm_details from ca_unique_no
		$ca_unique_no = $this->Session->read('ca_unique_no');
		$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$ca_unique_no)))->first();
		
		$customer_id = $firm_details['customer_id'];
		
		//get CA granted E-Code number
		$this->loadModel('DmiECodeForApplicants');
		$get_e_code = $this->DmiECodeForApplicants->find('all',array('conditions'=>array('customer_id IS'=>$customer_id)))->first();
		$firm_details['e_code'] = $get_e_code['e_code'];
				
		$this->set('firm_details',$firm_details);
		
		
		$this->Session->write('replica_for','ecode');//to get controller name in esign consent & sey url in response function
		
		
		//list of authorized laboratory
		$lab_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/3/'.'%','delete_status IS Null'),'order'=>'firm_name asc'));
		$this->set('lab_list',$lab_list);
	
		//get packer wise commodity list
	//	$commodity_ids = explode(',',$firm_details['sub_commodity']);		
	//	$commodity_list = $this->M_commodity->find('list',array('keyField'=>'commodity_code','valueField'=>'commodity_name','conditions'=>array('commodity_code IN'=>$commodity_ids)))->toArray();
	//	$grade_list = $this->MGradeDesc->find('list',array('keyField'=>'grade_code','valueField'=>'grade_desc','conditions'=>array('display'=>'Y'),'order'=>'grade_code asc'))->toArray();
	//	$tbl_list = $this->Dmi_all_tbls_detail->find('list',array('keyField'=>'id','valueField'=>'tbl_name','conditions'=>array('customer_id IS'=>$customer_id,'delete_status IS Null'),'order'=>'id asc'))->toArray();
	//	$packaging_material_list = $this->Dmi_packing_type->find('list',array('keyField'=>'id','valueField'=>'packing_type','conditions'=>array('delete_status IS Null'),'order'=>'id asc'))->toArray();
	//	$printers_list = $this->DmiFirms->find('list',array('keyField'=>'id','valueField'=>'firm_name','conditions'=>array('customer_id like'=>'%'.'/2/'.'%','delete_status IS Null'),'order'=>'firm_name asc'))->toArray();
		
		
		$this->set('ca_unique_no',$ca_unique_no);
		
		
	//fetch last reocrds from table, if empty set default value
		$dataArray = $this->DmiECodeAllotmentDetails->getSectionData($customer_id);
		
		$tableRowData = array();
		$overall_charges = 0;
		$i=0;
		foreach($dataArray as $each){
			
			$eachrow = $each;
			
			//get selected lab name
			$lab_details = $this->DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('id IS'=>$eachrow['grading_lab'])))->first();
			$lab_name = $lab_details['firm_name'];				
			$tableRowData[$i]['lab_name'] = $lab_name;
			
			//get selected commodity
			$commodity_details = $this->MCommodity->find('all',array('fields'=>'commodity_name','conditions'=>array('commodity_code IS'=>$eachrow['commodity'])))->first();
			$commodity_name = $commodity_details['commodity_name'];
			$tableRowData[$i]['commodity_name'] = $commodity_name;
			
			//get selected grade
			$grade_details = $this->MGradeDesc->find('all',array('fields'=>'grade_desc','conditions'=>array('grade_code IS'=>$eachrow['grade'])))->first();
			$grade_name = $grade_details['grade_desc'];
			$tableRowData[$i]['grade_name'] = $grade_name;
			
			//get selected tbl
			$tbl_details = $this->DmiAllTblsDetails->find('all',array('fields'=>'tbl_name','conditions'=>array('id IS'=>$eachrow['tbl'])))->first();
			$tbl_name = $tbl_details['tbl_name'];			
			$tableRowData[$i]['tbl_name'] = $tbl_name;
			
			//get selected packaging material
			$packing_details = $this->DmiPackingTypes->find('all',array('fields'=>'packing_type','conditions'=>array('id IS'=>$eachrow['packaging_material'])))->first();
			$packing_type = $packing_details['packing_type'];				
			$tableRowData[$i]['packing_type'] = $packing_type;
			
			//get selected printer
			$printer_details = $this->DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('id IS'=>$eachrow['authorized_printer'])))->first();
			$printer_name = $printer_details['firm_name'];				
			$tableRowData[$i]['printer_name'] = $printer_name;
			
			//get packet size			
			$tableRowData[$i]['packet_size'] = $eachrow['packet_size'];
			
			//get selected printer
			$get_unit = $this->DmiReplicaUnitDetails->find('all',array('fields'=>array('sub_unit'),'conditions'=>array('id'=>$eachrow['packet_size_unit'])))->first();
			$packet_size_unit = $get_unit['sub_unit'];
			$tableRowData[$i]['packet_size_unit'] = $packet_size_unit;
			
			//get no of packets			
			$tableRowData[$i]['no_of_packets'] = $eachrow['no_of_packets'];
			
			//get total quantity gross		
			$tableRowData[$i]['total_quantity'] = $eachrow['total_quantity'];
			
			//get label charge		
			$tableRowData[$i]['label_charge'] = $eachrow['label_charge'];
			
			//get total label charges		
			$tableRowData[$i]['total_label_charges'] = $eachrow['total_label_charges'];
			
			//get bal replica no	
			$tableRowData[$i]['bal_agmark_replica'] = $eachrow['bal_agmark_replica'];
			
			
			//calculate over all charges
			$overall_charges = $overall_charges+$eachrow['total_label_charges'];
			
			$i=$i+1;
		}
		
		$this->set('tableRowData',$tableRowData);
		$this->set('overall_charges',$overall_charges);
	}


	//function to create view of pdf document for replica allotment letter
	public function replicaAllotmentPdfView(){

		$this->viewBuilder()->setLayout('pdf_layout');
			
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiDistricts');
		$this->loadModel('DmiStates');
		$this->loadModel('DmiECodeAllotmentDetails');
		$this->loadModel('MCommodity');
		$this->loadModel('MGradeDesc');
		$this->loadModel('DmiAllTblsDetails');
		$this->loadModel('DmiPackingTypes');
		$this->loadModel('DmiReplicaUnitDetails');

		//get firm_details from ca_unique_no
		$ca_unique_no = $this->Session->read('ca_unique_no');
		$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$ca_unique_no)))->first();

		//get CA granted E-Code number
		$this->loadModel('DmiECodeForApplicants');
		$get_e_code = $this->DmiECodeForApplicants->find('all',array('conditions'=>array('customer_id IS'=>$firm_details['customer_id'])))->first();
		$firm_details['e_code'] = $get_e_code['e_code'];
				
		$this->set('firm_details',$firm_details);
		
		//get district and state name
		$fetch_district_name = $this->DmiDistricts->find('all',array('fields'=>'district_name','conditions'=>array('id IS'=>$firm_details['district'], 'OR'=>array('delete_status IS Null','delete_status'=>'no'))))->first();
		$this->set('firm_district_name',$fetch_district_name['district_name']);
		
		$fetch_state_name = $this->DmiStates->find('all',array('fields'=>'state_name','conditions'=>array('id IS '=>$firm_details['state'], 'OR'=>array('delete_status IS Null','delete_status'=>'no'))))->first();
		$this->set('firm_state_name',$fetch_state_name['state_name']);
		
		//replica application details
		$dataArray = $this->DmiECodeAllotmentDetails->getSectionData($firm_details['customer_id']);
		$appl_date = $dataArray[0]['modified'];
		$this->set('appl_date',$appl_date);
		
		
		$tableRowData = array();
		$overall_charges = 0;
		$i=0;
		foreach($dataArray as $each){
			
			$eachrow = $each;
			
			//get selected lab name
			$lab_details = $this->DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('id IS'=>$eachrow['grading_lab'])))->first();
			$lab_name = $lab_details['firm_name'];				
			$tableRowData[$i]['lab_name'] = $lab_name;
			
			//get selected commodity
			$commodity_details = $this->MCommodity->find('all',array('fields'=>'commodity_name','conditions'=>array('commodity_code IS'=>$eachrow['commodity'])))->first();
			$commodity_name = $commodity_details['commodity_name'];				
			$tableRowData[$i]['commodity_name'] = $commodity_name;
			
			//get selected grade
			$grade_details = $this->MGradeDesc->find('all',array('fields'=>'grade_desc','conditions'=>array('grade_code IS'=>$eachrow['grade'])))->first();
			$grade_name = $grade_details['grade_desc'];				
			$tableRowData[$i]['grade_name'] = $grade_name;
			
			//get selected tbl
			$tbl_details = $this->DmiAllTblsDetails->find('all',array('fields'=>'tbl_name','conditions'=>array('id IS'=>$eachrow['tbl'])))->first();
			$tbl_name = $tbl_details['tbl_name'];				
			$tableRowData[$i]['tbl_name'] = $tbl_name;
			
			//get selected packaging material
			$packing_details = $this->DmiPackingTypes->find('all',array('fields'=>'packing_type','conditions'=>array('id IS'=>$eachrow['packaging_material'])))->first();
			$packing_type = $packing_details['packing_type'];				
			$tableRowData[$i]['packing_type'] = $packing_type;
			
			//get selected printer
			$printer_details = $this->DmiFirms->find('all',array('fields'=>'firm_name','conditions'=>array('id IS'=>$eachrow['authorized_printer'])))->first();
			$printer_name = $printer_details['firm_name'];				
			$tableRowData[$i]['printer_name'] = $printer_name;
			
			//get packet size			
			$tableRowData[$i]['packet_size'] = $eachrow['packet_size'];
			
			//get selected printer
			$get_unit = $this->DmiReplicaUnitDetails->find('all',array('fields'=>array('sub_unit'),'conditions'=>array('id IS'=>$eachrow['packet_size_unit'])))->first();
			$packet_size_unit = $get_unit['sub_unit'];
			$tableRowData[$i]['packet_size_unit'] = $packet_size_unit;
			
			//get no of packets			
			$tableRowData[$i]['no_of_packets'] = $eachrow['no_of_packets'];
			
			//get total quantity gross		
			$tableRowData[$i]['total_quantity'] = $eachrow['total_quantity'];
			
			//get label charge		
			$tableRowData[$i]['label_charge'] = $eachrow['label_charge'];
			
			//get total label charges		
			$tableRowData[$i]['total_label_charges'] = $eachrow['total_label_charges'];
			
			//get bal replica no	
			$tableRowData[$i]['bal_agmark_replica'] = $eachrow['bal_agmark_replica'];
			
			//calculate over all charges
			$overall_charges = $overall_charges+$eachrow['total_label_charges'];
			
			$i=$i+1;
		}
		
		
		//get transaction id for payment to be deducted
		$this->loadModel('DmiAdvPaymentTransactions');
		$tran_details = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>array('trans_id','created'),'conditions'=>array('customer_id IS'=>$firm_details['customer_id']),'order'=>'id desc'))->first();
		$last_trans_id = $tran_details['trans_id'];
		$trans_date = $tran_details['created'];
		
		$cur_trans_id =  substr($last_trans_id,-4)+1;
		$cur_trans_id = 'ADP/'.date('m').'/'.$cur_trans_id;
		$this->set(compact('cur_trans_id','trans_date'));
		
		//get chemist name
		$chemist_id = $this->Session->read('username');
		$this->loadModel('DmiChemistRegistrations');
		$chemistdetails = $this->DmiChemistRegistrations->find('all',array('fields'=>array('chemist_fname','chemist_lname'),'conditions'=>array('chemist_id IS'=>$chemist_id)))->first();
		$chemist_name = $chemistdetails['chemist_fname'].' '.$chemistdetails['chemist_lname'];
		
		$this->set(compact('chemist_name','tableRowData','overall_charges'));
		
		//to eb used this session after successful esigned, to updated transaction table
		$this->Session->write('overall_total_chrg',$overall_charges);
		
		//added by shankhpal shende on 14/10/2022 for implimenting QR code for replica EsignedChemist
		$data = [$chemist_name,$firm_details['firm_name']];
		$result_for_qr = $this->Customfunctions->getQrCode($data,'CHM');
		
		$this->set('result_for_qr',$result_for_qr);
		//end for QR code
		
		$this->generateReplicaAllotmentPdf();
		
	}


	//generate replica allotment letter pdf	
	public function generateReplicaAllotmentPdf(){

		$this->loadModel('DmiFirms');
		$this->loadModel('DmiEcodeAllotmentPdfs');
		
		//get firm_details from ca_unique_no
		$ca_unique_no = $this->Session->read('ca_unique_no');
		$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$ca_unique_no)))->first();			
		$customer_id = $firm_details['customer_id'];	
		
	//	$view = new View($this, false);
	//	$view->layout = null;
		$pdf_data = $this->render('/Ecode/replica_allotment_pdf_view');	

		//check applicant last record version to increment				
		$pdf_list = $this->DmiEcodeAllotmentPdfs->find('all', array('fields'=>'pdf_version', 'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
		$last_pdf_version = 0;			
		if(!empty($pdf_list))
		{										
			$last_pdf_version =	$pdf_list['pdf_version'];
		}

		$current_pdf_version = $last_pdf_version+1; //increment last version by 1
		
		//creating filename and file path to save
		$split_customer_id = explode('/',(string) $customer_id); #For Deprecations
		$rearranged_id = 'Rep-EC-'.$split_customer_id[0].'-'.$split_customer_id[1].'-'.$split_customer_id[2].'-'.$split_customer_id[3];
		$filename = $rearranged_id.'('.$current_pdf_version.')'.'.pdf';
		$file_path = '/writereaddata/DMI/temp/'.$filename;				
		
		$this->Session->write('pdf_file_name',$filename);

		$pdfinst = new ApplicationformspdfsController();
		$pdfinst->callTcpdf($pdf_data,'I',$customer_id,'replica');
		$pdfinst->callTcpdf($pdf_data,'F',$customer_id,'replica');
	}
	
	
	//this function will be called from esign controller, when document esigned successful
	public function afterReplicaAllotmentEsigned(){
		
		$this->viewBuilder()->setLayout('chemist_home_layout');
		$this->loadModel('DmiFirms');
		$this->loadModel('DmiEcodeAllotmentPdfs');
		
		$message='';
		$redirect_to='';
		
		//get firm_details from ca_unique_no
		$ca_unique_no = $this->Session->read('ca_unique_no');
		$firm_details = $this->DmiFirms->find('all',array('conditions'=>array('id IS'=>$ca_unique_no)))->first();			
		$customer_id = $firm_details['customer_id'];
		
		//add record for deduction of payment in transaction table
		
		$this->loadModel('DmiAdvPaymentTransactions');
		//fetch last transaction details, to get bal amount
		$bal_details = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>array('balance_amount'),'conditions'=>array('customer_id iS'=>$customer_id),'order'=>'id desc'))->first();
		$last_bal_amt = $bal_details['balance_amount'];
		
		//get last transaction id in the table
		$trans_id_details = $this->DmiAdvPaymentTransactions->find('all',array('fields'=>array('trans_id'),'order'=>'id desc'))->first();
		$last_trans_id = $trans_id_details['trans_id'];
		
		$cur_total_amt = $this->Session->read('overall_total_chrg');
		$cur_bal_amt = $last_bal_amt - $cur_total_amt;
		$split_trans_id =  explode('/',(string) $last_trans_id);//substr($last_trans_id,-4)+1; #For Deprecations
		$cur_trans_id = $split_trans_id[2]+1;
		$cur_trans_id = 'ADP/'.date('m').'/'.$cur_trans_id;
		
	
		//Move esigned file from temp folder to Main "Replica" folder and enter record in allotment pdf table
		
		$filename = $this->Session->read('pdf_file_name');
		$source = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/temp/';
		$destination = $_SERVER["DOCUMENT_ROOT"].'/writereaddata/DMI/replica-allotments/';
		
		//calling custome function to move file
		$pdfinst = new ApplicationformspdfsController();
		if($pdfinst->moveFile($filename,$source,$destination)==1){
			
			//changed file path from temp to "replica-allotments"
			$file_path = '/writereaddata/DMI/replica-allotments/'.$filename;

			//check applicant last record version to increment				
			$pdf_list = $this->DmiEcodeAllotmentPdfs->find('all', array('fields'=>'pdf_version', 'conditions'=>array('customer_id IS'=>$customer_id),'order'=>'id desc'))->first();
			$last_pdf_version = 0;			
			if(!empty($pdf_list))
			{										
				$last_pdf_version =	$pdf_list['pdf_version'];
			}

			$current_pdf_version = $last_pdf_version+1; //increment last version by 1
			
			$DmiEcodeAllotmentPdfsEntity = $this->DmiEcodeAllotmentPdfs->newEntity(array(
	
				'customer_id'=>$customer_id,
				'chemist_id'=>$this->Session->read('username'),
				'pdf_file'=>$file_path,
				'date'=>date('Y-m-d'),
				'pdf_version'=>$current_pdf_version,
				'created'=>date('Y-m-d H:i:s'),
				'modified'=>date('Y-m-d H:i:s')	
			
			));

			$this->DmiEcodeAllotmentPdfs->save($DmiEcodeAllotmentPdfsEntity);
			
			
			//save debit entry in transaction table
			$DmiAdvPaymentTransactionsEntity = $this->DmiAdvPaymentTransactions->newEntity(array(
			
				'customer_id'=>$customer_id,
				'payment_for'=>'1',
				'trans_type'=>'debited',
				'trans_amount'=>$cur_total_amt,
				'balance_amount'=>$cur_bal_amt,
				'trans_id'=>$cur_trans_id,
				'created'=>date('Y-m-d H:i:s')
			
			));
			$this->DmiAdvPaymentTransactions->save($DmiAdvPaymentTransactionsEntity);	
			
			//update allotment status to 1 in replica allotment table
			$this->loadModel('DmiEcodeAllotmentPdfs');
			$date = date('Y-m-d H:i:s');
			$this->DmiEcodeAllotmentPdfs->updateAll(array('allot_status'=>"1",'modified'=>"$date",'version'=>"$current_pdf_version"),array('customer_id IS'=>$customer_id,'allot_status IS Null','delete_status IS Null'));
		
			
		}
		
		//delete session variables
		$this->Session->delete('pdf_file_name');
		$this->Session->delete('overall_total_chrg');
		$this->Session->delete('replica_for');
		
		
		//get chemist in-charge id to send SMS/email
		$this->loadModel('DmiChemistAllotments');
		$chemist_incharge = $this->DmiChemistAllotments->find('all',array('fields'=>'chemist_id','conditions'=>array('customer_id IS'=>$customer_id,'status'=>1,'incharge'=>'yes')))->first();
		$chemist_id = $chemist_incharge['chemist_id'];
		
		#SMS: Approve and Allotment of  E Code
		//$this->DmiSmsEmailTemplates->sendMessage(83,$customer_id); #Packer
		//$this->DmiSmsEmailTemplates->sendmessage(84,$chemist_id); #Chemist
		//$this->DmiSmsEmailTemplates->sendmessage(85,$customer_id); #RO/SO
		
		//get lab and printer for last allotment to send SMS/email
		$get_allotments = $this->DmiECodeAllotmentDetails->find('all',array('fields'=>array('authorized_printer','grading_lab'),'conditions'=>array('version IS'=>$current_pdf_version)))->toArray();
		
		$i=0;
		foreach($get_allotments as $each){
			
			$each = $each;
			
			$lab_id = $each['grading_lab'];
			$printer_id[$i] = $each['authorized_printer'];
			
			$i=$i+1;
		}
		//get lab details
		$lab_details = $this->DmiFirms->find('all',array('fields'=>'customer_id','conditions'=>array('id IS'=>$lab_id)))->first();
		$lab_cust_id = $lab_details['customer_id'];
		
		#SMS: Approve and Allotment of  E Code
		$this->DmiSmsEmailTemplates->sendmessage(86,$lab_cust_id); #Laboratory
		
		//check if multiple printers selected, to send SMS/email
		$id='';
		foreach($printer_id as $each){
			
			if($id != $each){
				
				//get printer details
				$printer_details = $this->DmiFirms->find('all',array('fields'=>'customer_id','conditions'=>array('id IS'=>$each)))->first();
				$printer_cust_id = $printer_details['customer_id'];

				#SMS: Approve and Allotment of  E Code
				$this->DmiSmsEmailTemplates->sendmessage(87,$printer_cust_id); #Printer
			}
			
			$id = $each;
		}
		
		
		$message = 'The E-Code Number is Approved and Alloted Successfully';
		$redirect_to = '../chemist/replica_alloted_list';
		$this->render('/element/message_boxes');
		
		$message_theme = 'success';
		$this->set('message_theme',$message_theme);
		$this->set('message',$message);
		$this->set('redirect_to',$redirect_to);
	}


	//function to create view of pdf document for application for replica	
	public function replicaApplicationPdfView(){

		$customer_id = $this->Session->read('username');
		$this->set('customer_id',$customer_id);
		$this->generateReplicaApplicationPdf();
	}

	//generate application pdf
	public function generateReplicaApplicationPdf(){

		$customer_id = $this->Session->read('username');
		//$view = new View($this, false);

		//$view->layout = null;

		$pdf_data = $this->render('/Ecode/replicaApplicationPdfView');			

		$pdfinst = new ApplicationformspdfsController();
		$pdfinst->callTcpdf($pdf_data,'I',$customer_id,'replica');
	}
	
	
//function to get details from replica serial no. 
	public function getReplicaNumberDetails($rep_serial_no){
		
		$ca_unique_no = substr($rep_serial_no,0,5);
		//get CA details
		$this->loadModel('DmiFirms');
		
		if(is_numeric($ca_unique_no)==true){
			$get_details = $this->DmiFirms->find('all',array('fields'=>array('firm_name','customer_id'),'conditions'=>array('id IS'=>$ca_unique_no)))->first();
		}else{
			$get_details = array();
		}
		
		if(!empty($get_details)){
			
			$firm_name = $get_details['firm_name'];
			$customer_id = $get_details['customer_id'];
			
			//alphabetic conversion of year (currently upto 26 years)
			$year_ar = array('2021'=>'A','2022'=>'B','2023'=>'C','2024'=>'D','2025'=>'E','2026'=>'F','2027'=>'G','2028'=>'H','2029'=>'I','2030'=>'J','2031'=>'K',
							'2032'=>'L','2033'=>'M','2034'=>'N','2035'=>'O','2036'=>'P','2037'=>'Q','2038'=>'R','2039'=>'S','2040'=>'T','2041'=>'U','2042'=>'V','2043'=>'W',
							'2044'=>'X','2045'=>'Y','2046'=>'Z');
							
			//alphabetic conversion of month (A to L Jan to Dec and again M to X Jan to Dec)
			$month_ar = array('January'=>'A','February'=>'B','March'=>'C','April'=>'D','May'=>'E','June'=>'F','July'=>'G','August'=>'H','September'=>'I','October'=>'J','November'=>'K','December'=>'L');
			
			//if range exceeds (ZZZ999) for same month then start month from M to X (Jan to Dec)
			$month_ar2 = array('January'=>'M','February'=>'N','March'=>'O','April'=>'P','May'=>'Q','June'=>'R','July'=>'S','August'=>'T','September'=>'U','October'=>'V','November'=>'W','December'=>'X');
			
			//get month and year from mapping arr
			if(substr($rep_serial_no,6,1) > 'L'){//get array 2 if month alphabet is greater than L
				$month = array_search(substr($rep_serial_no,6,1),$month_ar2);
			}else{
				$month = array_search(substr($rep_serial_no,6,1),$month_ar);
			}
			
			$year = array_search(substr($rep_serial_no,5,1),$year_ar);
			
			
			//now to get numeric converion of replica serial number (last 6 digits)
			
			//serial no mapping array for thound, lakh, and crore position place (AAA000)
			$mapping_arr = array('0'=>'A','1'=>'B','2'=>'C','3'=>'D','4'=>'E','5'=>'F','6'=>'G','7'=>'H','8'=>'I','9'=>'J','10'=>'K',
								'11'=>'L','12'=>'M','13'=>'N','14'=>'O','15'=>'P','16'=>'Q','17'=>'R','18'=>'S','19'=>'T','20'=>'U','21'=>'V','22'=>'W',
								'23'=>'X','24'=>'Y','25'=>'Z');
			
			
			$hun_val = substr($rep_serial_no,10,3);
			$thNum = array_search(substr($rep_serial_no,9,1),$mapping_arr);//thousand digit
			$lkNum = array_search(substr($rep_serial_no,8,1),$mapping_arr);//lakh digit
			$crNum = array_search(substr($rep_serial_no,7,1),$mapping_arr);//crore digit
			
			$thNum = $thNum*1000;
			$lkNum = $lkNum*26000;
			$crNum = $crNum*676000;
			
			$serial_no = $hun_val;
			if($thNum != 0){				
				$serial_no = $serial_no+$thNum;
			}
			if($lkNum != 0){				
				$serial_no = $serial_no+$lkNum;
			}
			if($crNum != 0){				
				$serial_no = $serial_no+$crNum;
			}
			
			//add 1 in above total as started from 000
			$serial_no = $serial_no+1;
			
			return array('firm_name'=>$firm_name,'customer_id'=>$customer_id,'year'=>$year,'month'=>$month,'serial_no'=>$serial_no);

		}else{
			
			return array('firm_name'=>'','customer_id'=>'','year'=>'','month'=>'','serial_no'=>'');
		}
		
		
	}


	//ajax function for search replica and show in popup
	public function searchReplica(){
			
		$this->autoRender = false;
		$msg = '';
		$rep_ser_no = $_POST['rep_ser_no'];
		
		$detail_arr = $this->getReplicaNumberDetails($rep_ser_no);
		
		if(!empty($detail_arr['customer_id'])){
		
			//check if entered replica number is valid number for the CA
			$this->LoadModel('DmiECodeAllotmentDetails');
			//get first alloted number
			$get_first_rep = $this->DmiECodeAllotmentDetails->find('all',array('fields'=>'alloted_rep_from','conditions'=>array('customer_id IS'=>$detail_arr['customer_id'],'allot_status'=>1,'delete_status IS Null'),'order'=>'id asc'))->first();
			
			if(!empty($get_first_rep)){
				
				$first_rep = $get_first_rep['alloted_rep_from'];
				$rep_detail = $this->getReplicaNumberDetails($first_rep);
				$first_rep_no = $rep_detail['serial_no'];
				
				//get last alloted number
				$get_last_rep = $this->DmiECodeAllotmentDetails->find('all',array('fields'=>'alloted_rep_to','conditions'=>array('customer_id IS'=>$detail_arr['customer_id'],'allot_status'=>1,'delete_status IS Null'),'order'=>'id desc'))->first();
				$last_rep = $get_last_rep['alloted_rep_to'];
				$rep_detail = $this->getReplicaNumberDetails($last_rep);
				$last_rep_no = $rep_detail['serial_no'];
				
				//check if the searched replica number is between this range
				if($detail_arr['serial_no'] >= $first_rep_no && $detail_arr['serial_no'] <= $last_rep_no){
					
					
					$msg .= "<tr><td><b>Firm Name:</b></td><td>".$detail_arr['firm_name']."</td></tr>";
					$msg .= "<tr><td><b>Certificate No:</b></td><td>".$detail_arr['customer_id']."</td></tr>";
					$msg .= "<tr><td><b>Year:</b></td><td>".$detail_arr['year']."</td></tr>";
					$msg .= "<tr><td><b>Month:</b></td><td>".$detail_arr['month']."</td></tr>";
					$msg .= "<tr><td><b>Serial No:</b></td><td>".$detail_arr['serial_no']."</td></tr>";
					
				
				}else{
					
					$msg = "<tr><td>Sorry, The E-Code you have searched is not valid.</td></tr>";
				}
			
			}else{
				
				$msg = "<tr><td>Sorry, The E-Code you have searched is not valid.</td></tr>";
			}
		
		}else{
			
			$msg = "<tr><td>Sorry, The E-Code you have searched is not valid.</td></tr>";
		}
		
		
		
		echo '~'.$msg.'~';
		exit;
	}
	
	
	//function to generate CA unique id for replica number
	public function getCaUniqueid($table_id){
			
		//count the length of table id
		$idLength = strlen($table_id);
		if($idLength==1){
			$table_id = '0000'.$table_id;
		}elseif($idLength==2){
			$table_id = '000'.$table_id;
		}elseif($idLength==3){
			$table_id = '00'.$table_id;
		}elseif($idLength==4){
			$table_id = '0'.$table_id;
		}
		
		return $table_id;
	}


}



?>
