<?php ?>
	<?php echo $this->Form->control('actual_payment', array('type'=>'hidden', 'id'=>'actual_payment', 'value'=>$application_charge, 'label'=>false,)); ?>
	<?php echo $this->Form->control('advancepayment', array('type'=>'hidden', 'id'=>'advancepayment', 'value'=>$_SESSION['advancepayment'], 'label'=>false)); ?>

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
			</div>
		</div>
		
			<div id="payment_details">
				<div class="card card-cyan col-md-12 p-0">
					<div class="card-header"><h3 class="card-title-new"><?php if(!empty($_SESSION['advancepayment']) && $_SESSION['advancepayment']=='no'){ echo 'Payment Details'; }else{ echo 'Advance Payment Details'; } ?></h3></div>
						<div class="card-footer p-0">
							<ul class="nav flex-column">
								<li class="nav-item row pt-2">
									<label for="field3" class="col-md-5 "><span class="col-form-label">Payment Amount<span class="cRed">*</span></span></label>
										<div class="col-sm-6">
											<?php echo $this->Form->control('payment_amount', array('type'=>'text', 'escape'=>false, 'value'=>$payment_amount, 'id'=>'payment_amount', 'label'=>false, 'placeholder'=>'Please Enter Payment Amount','class'=>'form-control')); ?>
										</div>
									<div id="error_payment_amount"></div>
								</li>
            					<li class="nav-item row pt-2">
									<label for="field3" class="col-md-5"><span class="col-form-label">Transaction ID/Receipt NO. <span class="cRed">*</span></span></label>
										<div class="col-sm-6">
											<?php echo $this->Form->control('payment_transaction_id', array('type'=>'text', 'escape'=>false, 'value'=>$payment_transaction_id, 'id'=>'payment_transaction_id', 'label'=>false, 'placeholder'=>'Please Enter Transaction ID/Receipt NO','class'=>'form-control')); ?>
										</div>
									<div id="error_payment_transaction_id"></div>
                				</li>
                				<li class="nav-item row pt-2">
									<label for="field3" class="col-md-5"><span class="col-form-label">PAO/DDO Name <span class="cRed">*</span></span></label>
										<div class="col-sm-6">
											<?php echo $this->Form->control('pao_name', array('type'=>'text', 'escape'=>false, 'value'=>$pao_to_whom_payment, 'id'=>'pao_name', 'label'=>false, 'readonly'=>true,'class'=>'form-control')); ?>
										</div>
									<div id="error_payment_amount"></div>
                				</li>
                				<li class="nav-item row pt-2">
									<label for="field3" class="col-md-5"><span class="col-form-label">Date of Transaction<span class="cRed">*</span></span></label>
										<div class="col-sm-6">
											<?php echo $this->Form->control('payment_trasaction_date', array('type'=>'text', 'escape'=>false, 'value'=>$payment_trasaction_date[0], 'id'=>'payment_trasaction_date', 'label'=>false, 'readonly'=>true, 'placeholder'=>'Please Enter Date of Transaction','class'=>'form-control')); ?>
										</div>
									<div id="error_payment_trasaction_date"></div>
                				</li>

								<?php if(!empty($_SESSION['advancepayment']) && $_SESSION['advancepayment']=='yes') { ?>
									<li class="nav-item row pt-2">
										<label for="field3" class="col-md-5"><span class="col-form-label">Advance Payment For<span class="cRed">*</span></span></label>
										<div class="col-sm-6">
											<?php echo $this->Form->control('payment_for', array('type'=>'select', 'escape'=>false, 'value'=>'1', 'options'=>array('1'=>'Advance Replica Payment'), 'id'=>'payment_for', 'label'=>false, 'readonly'=>true)); ?>
											<div id="error_payment_trasaction_date"></div>
										</div>
									</li>
									<?php } ?>

								<li class="nav-item row pt-2">
									<label for="field3" class="col-md-5"><span class="col-form-label">Upload Payment Receipt<span class="cRed">*</span></span></label>
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Attach File :
													<?php if(!empty($payment_receipt_docs)){ ?>
														<a target="blank" id="payment_receipt_document_value" href="<?php echo str_replace("D:/xampp/htdocs","",$payment_receipt_docs); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-8">
												  <?php echo $this->Form->control('payment_receipt_document',array('type'=>'file', 'id'=>'payment_receipt_document', 'multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>

												  <span id="error_payment_receipt_document" class="error invalid-feedback"></span> <!-- create div field for showing error message ( by pravin 06/05/2017)-->
												  <span id="error_size_payment_receipt_document" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
												  <span id="error_type_payment_receipt_document" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
												  <p class="lab_form_note_pay mt-1"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
												</div>
											  </div>
										</div>
									</li>
								</ul>
							</div>
						</div>
						<p><i class="fas fa-info-circle middle"></i> <b><u>Note: Fees once paid, shall not be refunded </b></u></p>
					</div>



					<!--<fieldset id="not_confirmed_reason"><legend>Referred Back Reason</legend>
									<label for="field3">Reason
									<?php
										//$options = array('0'=>'Payment amount does not match','1'=>'Transaction ID Invalid','2'=>'PAO Name Invalid', '3'=>'Transaction Date Invalid', '4'=>'Payment Receipt Invalid');

										//echo $this->Form->control('reasone_list_comment', array('type'=>'select', 'id'=>'reasone_list_comment', 'value'=>$reason_list_comment, 'options'=>$options, 'escape'=>false, 'label'=>false, 'readonly'=>true)); ?>
									</label >

									<label for="field3">Comment
									<?php //echo $this->Form->control('reasone_comment', array('type'=>'textarea', 'escape'=>false, 'value'=>$reason_comment, 'id'=>'reasone_comment', 'label'=>false, 'readonly'=>true)); ?>
									</label>
					</fieldset>-->

				<div id="not_confirmed_reason">
					<div class="card-header bg-dark"><h3 class="card-title-new">Referred Back History</h3></div>
					<div class="remark-history">
						<table class="table table-bordered">
							<tr class="boxformenus">
							<th class="tablehead">Date</th>
							<th class="tablehead">Reason</th>
							<th class="tablehead">Comment</th>
							</tr>
							<!-- change variable fetch_comment_reply to fetch_applicant_communication(by pravin 03/05/2017)-->
							<?php $options = array('0'=>'Payment amount does not match','1'=>'Transaction ID Invalid','2'=>'PAO/DDO Name Invalid', '3'=>'Transaction Date Invalid', '4'=>'Payment Receipt Invalid');

							foreach($fetch_pao_referred_back as $comment_reply){ ?>

								<tr>
								<td><?php echo $comment_reply['modified']; ?></td>
								<td><?php echo $options[$comment_reply['reason_option_comment']]; ?></td>
								<td><?php echo $comment_reply['reason_comment']; ?></td>
								</tr>

							<?php }?>
						</table>
					</div>
				</div>
			</div>

		<div class="form-buttons">
			<!-- Comment the condition for to show which previous section on payment section (Done By pravin 27/02/2018) -->
			<?php /*if($ca_bevo_applicant == 'no'){?>
				<a href="<?php echo $this->request->getAttribute('webroot'); echo $previous_button_url?>" >Previous Section</a>
			<?php //}elseif($ca_bevo_applicant == 'yes'){?>
				<a href="<?php echo $this->request->getAttribute('webroot'); echo $previous_button_url?>" >Previous Section</a>
			<?php //}else{*/ ?>

			<?php if($_SESSION['advancepayment'] == 'no'){ ?>
				<a class="btn btn-primary float-left" href="<?php echo $this->request->getAttribute('webroot'); echo $previous_button_url?>" >Previous Section</a>
			<?php } ?>
			<?php //} ?>
			<?php echo $this->form->submit('Final Submit', array('name'=>'final_submit', 'id'=>'final_submit_btn', 'label'=>false, 'class'=>'btn btn bg-teal float-left dnone')); ?>
			<?php echo $this->form->submit('Save', array('name'=>'save', 'id'=>'submit_payment_detail', 'label'=>false,'class'=>'btn btn-success float-right')); ?>
		</div>

		<input type="hidden" id="payment_confirmation_status" value="<?php echo $payment_confirmation_status; ?>" >

		<?php echo $this->Html->script('element/payment/payment_information_details'); ?>
