
<?php  			
	$message = $cert_grant_msg;	
?>


<!-- Created new form to post xml through Form Based esign method 
	updated on 28-05-2021 by Amol
	with predefined 3 hidden fields for xml string, transaction id and content-type-->

	<!-- The Modal -->
	<div id="declarationModal" class="modal">
		<!-- Modal content -->				  
		<div class="modal-content">
		
		<div class="row">
			<div class="col-md-3 d-inline">Certificate Pdf: </div> 
			<div class="col-md-3 d-inline"><a id="preview_link" target="blank" href="" >Preview</a></div>
			<span class="offset-5 close"><b>&times;</b></span>
		</div>
		<div class="clearfix"></div>
		
	<!--	<form action="https://esignservice.cdac.in/esign2.1/2.1/form/signdoc" method="POST">-->
	<?php echo $this->Form->create(null,array('action'=>'https://10.153.72.52/DMI_test/esign/renGrantEsign','method'=>'POST'));?>

			<input type="hidden" id = "eSignRequest" name="eSignRequest" value=""/>
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

	<input type="hidden" id="grantPdfFunctionPath" value="">
	<?php echo $this->Html->script('element/esign_views/renewal-declaration-message-before-grant'); ?>	
