<?php ?>
<?php echo $this->Html->css('element/show_old_certificate_details_popup'); ?>
<?php $customer_id = $_SESSION['customer_id']; ?>

<button type="button" class="btn btn-primary" id="show_old_cert_details" data-toggle="modal" data-target=".bd-example-modal-lg">Click to View/Edit Old Grant Date </button>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header"><h5 class="modal-title" id="exampleModalLabel">Old Granted Certificate Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<?php echo $this->Form->create(null, array('id'=>'old_cert_details_form')); ?>
					<div class="form-horizontal" id="old_granted_certificate">
						<div id="show_valid_upto_date" class="middle mt-2"><label class="badge badge-danger"><?php echo 'Certificate is valid upto '.$valid_upto_date; ?></label></div>	
						<div class="card-body">	
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label class="col-form-label">Certificate No. <span class="cRed">*</span></label>
										<div class="custom-file col-sm-7">
											<?php echo $this->Form->control('old_certificate_no', array('type'=>'text', 'value'=>$certificate_no, 'id'=>'certification_no', 'class'=>'input-field form-control', 'label'=>false, 'disabled'=>'disabled')); ?>
											<span id="error_certificate_no" class="error invalid-feedback"></span>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label class="col-form-label">Date of Grant <span class="cRed">*</span></label>
										<div class="custom-file col-sm-7">
											<?php echo $this->Form->control('grant_date', array('type'=>'text', 'value'=>chop($date_of_grant,'00:00:00'), 'id'=>'grant_date', 'class'=>'input-field form-control', 'label'=>false, 'readonly'=>true)); ?>
											<span id="error_grant_date" class="error invalid-feedback"></span>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<?php  if(!empty($old_app_renewal_dates)) { ?>

											<?php foreach($old_app_renewal_dates as $renewal_date){

												$last_renewal_date = chop($renewal_date['renewal_date'],'00:00:00');
												$split_date = explode('/',$last_renewal_date);
												$last_ren_day_month = $split_date[0].'/'.$split_date[1].'/';
												$last_ren_year = $split_date[2];
											} ?>

											<label class="col-form-label">Last Renewal Date <span class="cRed">*</span></label>
											<input type="text" name="last_ren_day_month" class="wd19mrminus12 form-control input-field" id="last_ren_day_month" value="<?php echo $last_ren_day_month; ?>" readonly="true" />
											<input type="text" name="last_ren_year" class="wd20 form-control input-field" id="last_ren_year" value="<?php echo $last_ren_year; ?>" class="renewal_dates_input" readonly="true" />
											<span id="error_last_ren_year" class="error invalid-feedback"></span>
											
										<?php } ?>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label class="col-form-label">Remark <span class="cRed">*</span></label>
									
											<?php echo $this->Form->control('reason_to_update', array('type'=>'textarea', 'id'=>'reason_to_update', 'class'=>'input-field form-control', 'label'=>false,)); ?>
											<span id="error_reason_to_update" class="error invalid-feedback"></span>
											<div id="show_coming_ren_status_msg"></div>
								
									</div>
								</div>
								
							</div>
						</div>
					</div>
					
				<?php echo $this->Form->end(); ?>
			</div>
			<div class="card-footer">
				<input type="submit" name="update_old_date" id="update_old_date" class="btn btn-success" value="Update/Approve"/>
			</div>
		</div>
	</div>
</div>

<input type="hidden" id="customer_flash_id" value="<?php echo $customer_id; ?>"/>

<!-- to disable grant date filed if renewal date exist else highlight -->
<?php  if (empty($old_app_renewal_dates)) { ?>
	<?php echo $this->Html->css('element/old_app_renewal_dates'); ?>
<?php } else { ?>
	<?php echo $this->Html->script('element/old_applications_elements/old_app_renewal_dates'); ?>
<?php } ?>


<?php echo $this->Html->script('element/old_applications_elements/show_old_certificate_details_popup'); ?>
