<?php ?>
	<?php echo $this->form->input('actual_payment', array('type'=>'hidden', 'id'=>'actual_payment', 'value'=>$firm_details_fields['total_charges'], 'label'=>false,)); ?>

	<div id="form_outer_main">
		<div class="card-body mt22p1">
			<div class="callout callout-danger p-4">
				<legend>How To Do Online Payment </legend>

				<label class="row fs16"><i class="glyphicon glyphicon-arrow-right paymentlabels"></i>Link To Payment Online :<a class="boldtext" href="https://bharatkosh.gov.in/" target="blank">bharatkosh.gov.in</a></label>
	
				<label class="row fs16"><i class="glyphicon glyphicon-arrow-right paymentlabels"></i><a href="#" target="blank"> FAQ on payments</a></label>
	
				<label class="row fs16"><i class="glyphicon glyphicon-arrow-right paymentlabels"></i>PAO/DDO to whom payment is to be made : <span class="badge badge-info margin5"><?php echo $pao_to_whom_payment; ?></span></label>
				
				<label class="row fs16"><i class="glyphicon glyphicon-arrow-right paymentlabels"></i> Is payment done on Bharatkosh?
				<?php
					$options=array('yes'=>'Yes','no'=>'No');
					$attributes=array('legend'=>false, 'value'=>$bharatkosh_payment_done, 'id'=>'bharatkosh_payment_done', 'label'=>true);
					echo $this->Form->radio('bharatkosh_payment_done',$options,$attributes); ?>
				</label>

				<fieldset id="payment_details"><legend>Payment Details</legend>

						<label for="field3"><span>Payment Amount<span class="required">*</span></span>
									<?php echo $this->form->input('payment_amount', array('type'=>'text', 'escape'=>false, 'value'=>$payment_amount, 'id'=>'payment_amount', 'label'=>false, 'placeholder'=>'Please Enter Payment Amount')); ?>
						</label>
						<div id="error_payment_amount"></div>

						<label for="field3"><span>Transaction ID/Receipt NO. <span class="required">*</span></span>
									<?php echo $this->form->input('payment_transaction_id', array('type'=>'text', 'escape'=>false, 'value'=>$payment_transaction_id, 'id'=>'payment_transaction_id', 'label'=>false, 'placeholder'=>'Please Enter Transaction ID/Receipt NO')); ?>
						</label>
						<div id="error_payment_transaction_id"></div>

						<!--<label for="field3"><span>PAO Name<span class="required">*</span></span>
									<?php //echo $this->form->input('pao_name', array('type'=>'select', 'escape'=>false, 'value'=>$selected_pao_alias_name, 'options'=>$pao_alias_name, 'id'=>'pao_name', 'label'=>false, 'placeholder'=>'Please Enter PAO Name')); ?>
						</label>
						-->

						<label for="field3"><span>PAO/DDO Name<span class="required">*</span></span>
								<?php echo $this->form->input('pao_name', array('type'=>'text', 'escape'=>false, 'value'=>$pao_to_whom_payment, 'id'=>'pao_name', 'label'=>false, 'readonly'=>true)); ?>
						</label>

						<label for="field3"><span>Date of Transaction<span class="required">*</span></span>
									<?php echo $this->form->input('payment_trasaction_date', array('type'=>'text', 'escape'=>false, 'value'=>$payment_trasaction_date[0], 'id'=>'payment_trasaction_date', 'label'=>false, 'readonly'=>true, 'placeholder'=>'Please Enter Date of Transaction')); ?>
						</label>
						<div id="error_payment_trasaction_date"></div>

						<p>Upload Payment Receipt</p>
						<span class="float-left">Attach File :

							<?php if(!empty($payment_receipt_docs)){ ?>
							<a target="blank" id="payment_receipt_document_value" href="<?php echo str_replace("D:/xampp/htdocs","",$payment_receipt_docs); ?>">Preview</a>
							<?php } ?>
						</span>

						<?php echo $this->form->input('payment_receipt_document',array('type'=>'file', 'id'=>'payment_receipt_document', 'onchange'=>'file_browse_onclick(id);return false', 'multiple'=>'multiple', 'label'=>false)); ?>
						<p class="file_limits">File type: pdf,jpg & Max-size:2mb</p>
						<div id="error_payment_receipt_document"></div>

				</fieldset>


	</div>

	<label>Note: Fees once paid, shall not be refunded </label>
	<div class="form-buttons">

		<?php if($ca_bevo_applicant == 'no'){?>
			<a href="<?php echo $this->request->getAttribute('webroot'); echo $previous_button_url?>" >Previous Section</a>
		<?php }elseif($ca_bevo_applicant == 'yes'){?>
			<a href="<?php echo $this->request->getAttribute('webroot'); echo $previous_button_url?>" >Previous Section</a>
		<?php }else{ ?>
		<a href="<?php echo $this->request->getAttribute('webroot'); echo $previous_button_url?>" >Previous Section</a>
		<?php } ?>

	</div>


<script>

	$( document ).ready(function() {

			$("#form_outer_main :input").prop("disabled", true);
			$("#form_outer_main :input[type='radio']").prop("disabled", true);
			$("#form_outer_main :input[type='select']").prop("disabled", true);
			$("#form_outer_main :input[type='submit']").css('display','none');
	});

</script>


	
