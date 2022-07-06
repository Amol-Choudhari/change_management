<?php if (!empty($show_note_msg) && $_SESSION['level_3_for']=='ro') { ?>
		<p class="cNavy"><span class="cBrown">Note: </span>
		After Verification of required updates, If you want to send application to HO-QC, 
		please go from "Regional Office -> Pending/Replied -> With HO(QC)" option on your dashboard, 
		open this application and send comment to HO-QC again. Because this application was referred back from HO-QC.
		</p>
<?php } ?>

<?php  			
	$customer_id = $_SESSION['customer_id'];		
	$split_customer_id = explode('/',$customer_id);
	//added this message on 27-10-2017 by Amol
	$message = $cert_grant_msg;
	//taking action name to apply in condition below
	$final_report_btn_id = 'final_granted_btn';
	$final_report_btn_name = 'final_granted';
	
	//conditionally calling pdf creation function for CA/printing/Lab report
	$controller_name = 'applicationformspdfs';
	$grant_pdf = $section_details['grant_pdf'];		
?>


<!-- Created new form to post xml through Form Based esign method 
	updated on 28-05-2021 by Amol
	with predefined 3 hidden fields for xml string, transaction id and content-type-->

	<!-- The Modal -->
	<div id="declarationModal" class="modal">
		<!-- Modal content -->				  
		<div class="modal-content">
		
		<!--added this pdf preview link on 27-10-2017 by Amol -->
		<div class="row">
			<div class="col-md-3 d-inline">Certificate Pdf: </div> 
			<div class="col-md-3 d-inline"><a target="blank" href="../<?php echo $controller_name; ?>/<?php echo $grant_pdf; ?>" >Preview</a></div>
			<span class="offset-5 close"><b>&times;</b></span>
		</div>
		<div class="clearfix"></div>
		
	<!--	<form action="https://esignservice.cdac.in/esign2.1/2.1/form/signdoc" method="POST">-->
	<?php echo $this->Form->create(null,array('action'=>'https://10.153.72.52/DMI/esign/requestEsign','method'=>'POST'));?>

			<input type="hidden" id = "eSignRequest" name="eSignRequest" value=''/>
			<input type="hidden" id = "aspTxnID" name="aspTxnID" value=""/>
			<input type="hidden" id = "Content-Type" name="Content-Type" value="application/xml"/>
			<input type="submit" name="submit" value="Esign" class="btn btn-success mt-2 float-right mr-2" id="esign_submit_btn">
		<!--</form>-->
		<?php echo $this->Form->end(); ?>				   
		
		<input type="checkbox" name="declaration_check_box" id="declaration_check_box" class="modal-checkbox" >
		<label for="declaration_check_box"><?php echo $aadhar_auth_msg.'<br><br>'.$message; ?></label><br>

		<p id="plz_wait" class="pleaseWait">Please Wait...</p>
		
		<div class="row offset-7">
			
		</div>

		</div>				 
	</div>			

	<input type="hidden" id="final_report_btn_id" value="<?php echo $final_report_btn_id; ?>">
	<input type="hidden" id="controller_name_id" value="<?php echo $controller_name; ?>">
	<input type="hidden" id="grant_pdf_function_id" value="<?php echo $grant_pdf; ?>">

	<?php echo $this->Html->script('element/esign_views/declaration-message-before-grant'); ?>	
