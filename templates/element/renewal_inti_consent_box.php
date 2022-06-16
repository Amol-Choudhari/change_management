<?php

		$customer_id = $_SESSION['username'];

		$split_customer_id = explode('/',$customer_id);
		if ($split_customer_id[1] == 1) {
			if ($ca_bevo_applicant == 'no') {
				$message_wo_esign = $ca_renewal_msg;
			} else {
				$message_wo_esign = $ca_bevo_renewal_msg;
			}
		} elseif ($split_customer_id[1] == 2) {
			$message_wo_esign = $printing_renewal_msg;
		} elseif ($split_customer_id[1] == 3) {
			$message_wo_esign = $lab_renewal_msg;
		}

		//taking current Controller name to apply below pdf preview anchor tag URL	//on 01-11-2017
		$controller_name = 'applicationformspdfs';
		$forms_pdf = $section_details['forms_pdf'];
?>


	<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data')); ?>
		<!-- The Modal for final submit withOut Esign-->
		<!-- Added on 04-05-2018 by Amol -->
				<div id="declarationModal_wo_esign" class="modal">
				  <!-- Modal content -->
				  <div class="modal-content">
					 <span class="close"><b>&times;</b></span>
					<div class="col-md-3 d-inline">Renewal Consent</div>
					<div class="clearfix"></div>

					<?php echo $this->Form->control('declaration_check_box_wo_esign', array('type'=>'checkbox', 'id'=>'declaration_check_box_wo_esign', 'class'=>'modal-checkbox','label'=>$message_wo_esign, 'escape'=>false)); ?>

					<button id="cancelBtn" class="modal-button btn btn-dark mt-2 float-right"><i class="fa fa-times-circle"></i> Cancel</button>
					<button id="okBtn_wo_esign" class="modal-button btn btn-success mt-2 float-right mr-2" name="final_submit"><i class="fa fa-check-circle"></i> Submit</button>
				  </div>
				</div>
	<?php echo $this->Form->end(); ?>

	<?php echo $this->Html->script('element/renewal_inti_consent_box'); ?>
