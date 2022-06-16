<?php  
			
		$customer_id = $_SESSION['username'];
		
		$split_customer_id = explode('/',$customer_id);
		//added this message on 27-10-2017 by Amol
		//commented on 31-05-2018 because, getting message from DB now
		//$esign_msg = "Please preview your application pdf, if all fine click 'Ok' to E-Sign the document, if you don't want to E-Sign now please click 'Cancel', Your Renewal Application will final submitted only after E-Signing.";
		//$aadhar_auth_msg = 'I hereby state that I have no objection in authenticating myself with Aadhaar based authentication system and consent to providing my Aadhaar number, Biometric and/or One Time Pin (OTP) data for Aadhaar based authentication for the purposes of availing of eSign service/ e-KYC services / both in PAN application from DMI.';
		if ($split_customer_id[1] == 1) {		
			
			if ($ca_bevo_applicant == 'no') {
				
				$message = $aadhar_auth_msg.'<br><br>'.$esign_msg."<br><br>".$ca_renewal_msg;
				$message_wo_esign = $ca_renewal_msg;
			} else {
				
				$message = $aadhar_auth_msg.'<br><br>'.$esign_msg."<br><br>".$ca_bevo_renewal_msg;
				$message_wo_esign = $ca_bevo_renewal_msg;
			}
		} elseif ($split_customer_id[1] == 2) {
			
			$message = $aadhar_auth_msg.'<br><br>'.$esign_msg."<br><br>".$printing_renewal_msg;
			$message_wo_esign = $printing_renewal_msg;
		
		} elseif ($split_customer_id[1] == 3) {
			
			$message = $aadhar_auth_msg.'<br><br>'.$esign_msg."<br><br>".$lab_renewal_msg;
			$message_wo_esign = $lab_renewal_msg;
		}
			
			
		//taking current Controller name to apply below pdf preview anchor tag URL	//on 01-11-2017
		$controller_name = $this->request->getParam('controller');
			
?>

			<form>
		<!-- created new modal on 28-03-2018 by Amol, to show option with/without esign -->
				<div id="esign_or_not_modal" class="modal">
				  <!-- Modal content -->				  
				  <div class="modal-content">
					<span class="close"><b>&times;</b></span>
					<p><?php echo $without_esign; ?></p>
					<br>
					<?php $options=array('yes'=>'Submit with Esign','no'=>'Submit without Esign');
						$attributes=array('legend'=>false,'value'=>'yes','id'=>'esign_or_not_option', 'label'=>false );					
						echo $this->Form->radio('esign_or_not_option',$options,$attributes); ?>

					<button id="proceedbtn" class="modal-button btn btn-success float-right" ><i class="fa fa-check-circle"></i> Proceed</button>
	
				  </div>				 
				</div>
			</form>
				
				

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
					<div class="col-md-3">Application Pdf: </div>
					<div class="col-md-4"><a target="blank" href="../<?php echo $controller_name; ?>/forms_pdf" >Preview</a></div><br>
					<div class="clearfix"></div>
					
					<form action="https://esignservice.cdac.in/esign2.1/2.1/form/signdoc" method="POST">
					
						<input type="hidden" id = "eSignRequest" name="eSignRequest" value=''/>
						<input type="hidden" id = "aspTxnID" name="aspTxnID" value=""/>
						<input type="hidden" id = "Content-Type" name="Content-Type" value="application/xml"/>
						<input type="submit" name="submit" value="Esign" class="btn btn-success mt-2 float-right mr-2" id="esign_submit_btn">
					</form>
					
					<input type="checkbox" name="declaration_check_box" id="declaration_check_box" class="modal-checkbox" >
					<label for="declaration_check_box"><?php echo $message; ?></label><br>

					<p id="plz_wait" class="pleaseWait">Please Wait...</p>
					
				  </div>				 
				</div>
		
		

		<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data')); ?>
			<!-- The Modal for final submit withOut Esign-->
		<!-- Added on 04-05-2018 by Amol -->
				<div id="declarationModal_wo_esign" class="modal">
				  <!-- Modal content -->				  
				  <div class="modal-content">
					<span class="close"><b>&times;</b></span>
					<!--added this pdf preview link on 27-10-2017 by Amol -->
					<div class="col-md-3">Application Pdf: </div>
					<div class="col-md-4"><a target="blank" href="../<?php echo $controller_name; ?>/forms_pdf" >Preview</a></div><br>
					<div class="clearfix"></div>
					
					
					<?php echo $this->Form->control('declaration_check_box_wo_esign', array('type'=>'checkbox', 'id'=>'declaration_check_box_wo_esign', 'class'=>'modal-checkbox','label'=>$message_wo_esign, 'escape'=>false)); ?>

					<button id="cancelBtn" class="modal-button">Cancel</button>
					<button id="okBtn_wo_esign" class="modal-button" name="final_submit">Submit</button>	
				  </div>				 
				</div>
				
		<?php echo $this->Form->end(); ?>

	<?php //if ($final_submit_status == 'no_final_submit') { //commented is condition on 04-11-2017 by Amol ?>	
		
		
		<?php echo $this->Html->script('element/esign_views/renewal_application_declaration_message'); ?>	

				
	<?php //} ?>			