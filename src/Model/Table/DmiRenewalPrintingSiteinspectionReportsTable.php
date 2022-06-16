<?php

class DmiRenewalPrintingSiteinspectionReportsTable extends Table{
	
	var $name = "DmiRenewalPrintingSiteinspectionReports";
	
	public $validate = array(
					
					'customer_id'=>array(
						'rule'=>array('maxLength',20),				
					),
					
					'status'=>array(
						'rule'=>array('maxLength',20),				
					),
					'current_level'=>array(
						'rule'=>array('maxLength',20),							
					),				
					
					'firm_renewal_docs'=>array(
						'rule'=>array('maxLength',200),
					),
				);
}

?>