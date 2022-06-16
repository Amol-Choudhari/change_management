<?php ?>
<?php echo $this->Html->css('element/show_old_certificate_details_popup'); ?>
<?php $customer_id = $_SESSION['customer_id']; ?>


	<!-- Button to click and open the popup -->
	<a href="#" id="show_old_cert_details" class="badge badge-info">Click to View/Edit Old Grant Date</a>
	<!-- All the popup view is under this div -->
	<div id="declarationModal" class="modal">
		<div class="modal-content card card-info">
			<div class="card-header"><h3 class="card-title-new">Old Granted Certificate Details</h3></div>
			<span class="close bg-red"><b>&times;</b></span>

			<?php echo $this->Form->create(null, array('id'=>'old_cert_details_form')); ?>
			<div class="form-horizontal">
				<div id="show_valid_upto_date" class="middle mt-2"><label class="badge badge-danger"><?php echo 'Certificate is valid upto '.$valid_upto_date; ?></label></div>
					<div class="uneditable" id="old_granted_certificate">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-sm-5">Certificate No. <span class="cRed">*</span></label>
										<div class="custom-file col-sm-7">
											<?php echo $this->Form->control('old_certificate_no', array('type'=>'text', 'value'=>$certificate_no, 'id'=>'certification_no', 'class'=>'input-field form-control', 'label'=>false, 'disabled'=>'disabled')); ?>
											<span id="error_certificate_no" class="error invalid-feedback"></span>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-sm-5">Date of Grant <span class="cRed">*</span></label>
										<div class="custom-file col-sm-7">
											<?php echo $this->Form->control('grant_date', array('type'=>'text', 'value'=>chop($date_of_grant,'00:00:00'), 'id'=>'grant_date', 'class'=>'input-field form-control', 'label'=>false, 'readonly'=>true)); ?>
											<span id="error_grant_date" class="error invalid-feedback"></span>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<?php  if(!empty($old_app_renewal_dates)) { ?>

										<?php foreach($old_app_renewal_dates as $renewal_date){

											$last_renewal_date = chop($renewal_date['renewal_date'],'00:00:00');
											$split_date = explode('/',$last_renewal_date);
											$last_ren_day_month = $split_date[0].'/'.$split_date[1].'/';
											$last_ren_year = $split_date[2];
										} ?>

										<label for="field3">Last Renewal Date <span class="cRed">*</span>
											<input type="text" name="last_ren_day_month" class="wd14mrminus5" id="last_ren_day_month" value="<?php echo $last_ren_day_month; ?>">
											<input type="text" name="last_ren_year" class="wd20" id="last_ren_year" value="<?php echo $last_ren_year; ?>" class="renewal_dates_input" readonly="true" />
											<span id="error_last_ren_year" class="error invalid-feedback"></span>
										</label>
									<?php } ?>
								</div>
								<div class="col-sm-6">
									<label for="field3">Remark <span class="cRed">*</span></label>
									<?php echo $this->Form->control('reason_to_update', array('type'=>'textarea', 'id'=>'reason_to_update', 'class'=>'form-control', 'label'=>false,)); ?>
									<span id="error_reason_to_update" class="error invalid-feedback"></span>
									<div id="show_coming_ren_status_msg"></div>
								</div>
								<div class="col-sm-3">
									<input type="submit" name="update_old_date" id="update_old_date" class="btn btn-success" value="Update/Approve"/>
								</div>
							</div>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>

<!-- to disable grant date filed if renewal date exist else highlight -->
	<?php  if (empty($old_app_renewal_dates)) { ?>
		<?php echo $this->Html->css('element/old_app_renewal_dates'); ?>
	<?php } else { ?>
		<?php echo $this->Html->script('element/old_applications_elements/old_app_renewal_dates'); ?>
	<?php } ?>


<?php echo $this->Html->script('element/old_applications_elements/show_old_certificate_details_popup'); ?>
