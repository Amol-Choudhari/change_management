<?php  			
		//added this message on 27-10-2017 by Amol
		$message = $io_report_msg;
		
		//taking current Controller name to apply below pdf preview anchor tag URL	//on 01-11-2017
		$controller_name = $this->request->getParam('controller');
		//taking action name to apply in condition below
		$action_name = $this->request->getParam('action');
				
		//conditionally taking forms final submit btn id & name for onclick script
		if (empty($final_submit_status)) {
			$final_report_btn_id = 'final_submit_btn';
		} elseif (!empty($final_submit_status)) {			
			$final_report_btn_id = 'sent_to';	
		}
		
		//this line added on 09-07-2018, while now esigning called through ajax, and no server side finalsubmit isset called.
		$_SESSION['current_action'] = $action_name;
		
		$controller_name = 'applicationformspdfs';
		$forms_pdf = $section_details['report_pdf'];
			
?>


<!-- Created new form to post xml through Form Based esign method 
	updated on 28-05-2021 by Amol
	with predefined 3 hidden fields for xml string, transaction id and content-type
-->

			<!-- The Modal -->
			<div id="declarationModal" class="modal">
			  <!-- Modal content -->				  
			  <div class="modal-content">
				<span class="close"><b>&times;</b></span>
				<!--added this pdf preview link on 27-10-2017 by Amol -->
				<div class="col-md-3 d-inline">Report PDF: </div> 
				<div class="col-md-4 d-inline"><a target="blank" href="../<?php echo $controller_name; ?>/<?php echo $forms_pdf; ?>" >Preview</a></div>
				<div class="clearfix"></div>
				
			<!--	<form action="https://esignservice.cdac.in/esign2.1/2.1/form/signdoc" method="POST">-->
			<?php echo $this->Form->create(null,array('action'=>'https://10.153.72.52/DMI_test/esign/requestEsign','method'=>'POST'));?>

					<input type="hidden" id = "eSignRequest" name="eSignRequest" value=''/>
					<input type="hidden" id = "aspTxnID" name="aspTxnID" value=""/>
					<input type="hidden" id = "Content-Type" name="Content-Type" value="application/xml"/>
					<input type="submit" name="submit" value="Esign" class="btn btn-success mt-2 float-right mr-2" id="esign_submit_btn">
				<!--</form>-->
		<?php echo $this->Form->end(); ?>		   
				
				<input type="checkbox" name="declaration_check_box" id="declaration_check_box" class="modal-checkbox" >
				<label for="declaration_check_box"><?php echo $aadhar_auth_msg.'<br><br>'.$message; ?></label><br>

				<p id="plz_wait" class="pleaseWait">Please Wait...</p>
				
			  </div>				 
			</div>

	<input type="hidden" id="final_report_btn_id" value="<?php echo $final_report_btn_id; ?>">				
	<input type="hidden" id="controller_name_id" value="<?php echo $controller_name; ?>">				
	<input type="hidden" id="report_pdf_function_id" value="<?php echo $forms_pdf; ?>">				

	<?php echo $this->Html->script('element/esign_views/declaration-message_boxes'); ?>	
