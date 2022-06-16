<?php
	$customer_id = $_SESSION['customer_id'];
	$split_customer_id = explode('/',$customer_id);
	$certificate_type_value = $split_customer_id[1];
?>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<label class="badge badge-primary">Edit Firm</label>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Old Application Entry', array('controller' => 'authprocessedoldapp', 'action'=>'home'));?></li>
							<li class="breadcrumb-item active">Edit Firm</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info"><i class="icon fas fa-info"></i> Only Email id and Phone No. can be updated</div>
							<?php echo $this->Form->create(null, array('id'=>'edit_firm_form')); ?>
								<div class="card card-success">
									<?php foreach($added_firms as $added_firm) { ?>
									<div class="card-header"><h3 class="card-title"><i class="fas fa-certificate"></i> Type of Certification</h3></div>
										<?php echo $this->Form->control('certificate_type_value', array('type'=>'hidden', 'value'=>$certificate_type_value, 'id'=>'certificate_type_value', 'label'=>false)); ?>
										
											<div class="form-horizontal">
												<div class="card-body">
													<div class="row">
														<div class="col-sm-6 certification_type">
															<div class="form-group row">
																<label for="certification type" class="col-sm-3 col-form-label">Certification Type  <span class="cRed">*</span></label>
																<div class="col-sm-9">
																	<?php echo $this->Form->control('certification_type', array('type'=>'text', 'value'=>$certification_type_value['certificate_type'], 'class'=>'input-field form-control', 'label'=>'', 'disabled'=>'disabled')); ?>
																	<span id="error_certification_type" class="error invalid-feedback"></span>
																</div>
															</div>
														</div>
														
													<?php if (isset($export_unit)) {
															if ($export_unit != null || $export_unit != '') { ?>
																<div id="export_unit" class="col-sm-6">
																	<div class="form-group row">
																		<label for="export unit" class="col-sm-3 col-form-label">Export Unit ?</label>
																		<div class="col-sm-9">
																			<?php
																			$export_unit = $added_firm['export_unit'];
																			if ($export_unit == 'yes') {
																				$checked_yes = 'checked';
																				$checked_no = '';
																			} elseif ($export_unit == 'no') {
																				$checked_yes = '';
																				$checked_no = 'checked';
																			}
																			?>
																			<div class="icheck-success d-inline">
																				<input type="radio" name="export_unit" checked="" id="radioSuccess1" value="yes" <?php echo $checked_yes; ?> disabled>
																				<label for="radioSuccess1">Yes</label>
																			</div>
																			<div class="icheck-success d-inline">
																				<input type="radio" name="export_unit" id="radioSuccess2" value="no" <?php echo $checked_no; ?> disabled>
																				<label for="radioSuccess2">No</label>
																			</div>
																		</div>
																	</div>
																</div>
														<?php } } ?>
													</div>
												</div>
											</div>

											<div class="card-header"><h3 class="card-title"><i class="fas fa-clipboard-check"></i> Certificate Granted</h3></div>
											<div class="form-horizontal">
												<div class="card-body">
													<div class="row">
														<div class="col-sm-12">
															<div class="form-group row">
																<label for="already granted" class="col-sm-3 col-form-label">Is Certificate Already Granted ? : </label>
																<div class="col-sm-6">
																	<?php
																	if ($is_already_granted == 'yes') {
																		$cert_grant_yes = 'checked';
																		$cert_grant_no = '';
																	} elseif ($is_already_granted == 'no') {
																		$cert_grant_yes = '';
																		$cert_grant_no = 'checked';
																	}
																	?>
																	<div class="icheck-primary d-inline">
																		<input type="radio" name="is_already_granted" checked="" id="radioPrimary1" value="yes" <?php echo $cert_grant_yes; ?> disabled="">
																		<label for="radioPrimary1">Yes</label>
																	</div>
																	<div class="icheck-primary d-inline">
																		<input type="radio" name="is_already_granted" id="radioPrimary2" value="no" <?php echo $cert_grant_no; ?> disabled="">
																		<label for="radioPrimary2">No
																		</label>
																	</div>
																	<span id="error_export_unit" class="error invalid-feedback"></span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div id="old_granted_certificate">
												<div class="card-header sub-card-header-firm"><h3 class="card-title"><i class="fas fa-info-circle"></i> Granted Certificate Details</h3></div>
												<div class="form-horizontal">
													<div class="card-body">
														<div class="row">
															<div class="col-sm-6">
																<div class="form-group row">
																	<label for="certficate no" class="col-sm-3 col-form-label">Certificate No. <span class="cRed">*</span></label>
																	<div class="col-sm-9">
																		<?php echo $this->Form->control('old_certificate_no', array('type'=>'text', 'value'=>$certificate_no, 'id'=>'certification_no', 'class'=>'form-control input-field', 'label'=>false, 'disabled'=>'disabled')); ?>
																		<span id="error_certificate_no" class="error invalid-feedback"></span>
																	</div>
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group row">
																	<label for="date of grant" class="col-sm-3 col-form-label">Date of Grant <span class="cRed">*</span></label>
																	<div class="col-sm-9">
																		<?php echo $this->Form->control('grant_date', array('type'=>'text', 'value'=>$date_of_grant, 'readonly'=>'true', 'id'=>'grant_date', 'class'=>'form-control input-field', 'label'=>false, 'disabled'=>'disabled')); ?>
																		<span id="error_grant_date" class="error invalid-feedback"></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

										<?php if (!empty($old_app_renewal_dates)) { ?>
										
											<div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle"></i> All Renewal Details</h3></div>
											<div class="form-horizontal">
												<div class="card-body">
													<div class="row">
														<div class="col-sm-6">
															<?php foreach($old_app_renewal_dates as $renewal_date){  ?>
																<div class="form-group row input_fields_container">
																	<label for="year of renewal" class="col-sm-3 col-form-label">Year Of Renewal <span class="cRed">*</span></label>
																	<div class="col-sm-9">
																		<input type="text" name="renewal_dates[]" id="last_renewal_dates1" value="<?php echo $renewal_date['renewal_date']; ?>" class="form-control renewal_dates_input" readonly="true">
																	</div>
																</div>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										
										<?php } ?>

											<!-- End Check added firm is new or old granted firm 04-10-2017-->
											<div class="card-header"><h3 class="card-title"><i class="far fa-building"></i> Firm Details</h3></div>
											<div class="form-horizontal">
												<div class="card-body">
													<div class="row">
														<div class="col-sm-6">
															<div class="form-group row">
																<label for="firm name" class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
																<div class="col-sm-9">
																	<?php echo $this->Form->control('firm_name', array('type'=>'text', 'value'=>$added_firm['firm_name'], 'class'=>'form-control input-field', 'label'=>'', 'disabled'=>'disabled')); ?>
																</div>
															</div>
															<div class="form-group row">
																<label for="email id" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
																<div class="col-sm-9">
																	<?php echo $this->Form->control('email', array('type'=>'email', 'value'=>$added_firm['email'], 'id'=>'email', 'class'=>'form-control input-field', 'label'=>false)); //for email encoding ?>
																	<span id="error_email" class="error invalid-feedback"></span>
																</div>
															</div>
														</div>
														<div class="col-sm-6">
															<div class="form-group row">
																<label for="mobile no" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
																<div class="col-sm-9">
																	<?php echo $this->Form->control('mobile_no', array('type'=>'text', 'value'=>$added_firm['mobile_no'], 'id'=>'mobile_no', 'class'=>'form-control input-field', 'readonly'=>true, 'label'=>false)); ?>
																	<span id="error_mobile_no" class="error invalid-feedback"></span>
																</div>
															</div>
															<div class="form-group row">
																<label for="phone no" class="col-sm-3 col-form-label">Phone no.</label>
																<div class="col-sm-9">
																	<?php echo $this->Form->control('fax_no', array('type'=>'text', 'value'=>base64_decode($added_firm['fax_no']), 'id'=>'fax_no', 'class'=>'form-control input-field', 'label'=>false)); ?>
																	<span id="error_fax_no" class="error invalid-feedback"></span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div id="commodity_box">
												<div class="card-header sub-card-header-firm"><h3 class="card-title"><i class="fa fa-tree"></i> Commodities</h3></div>
												<div class="form-horizontal">
													<div class="card-body">
														<div class="row">
															<div class="col-sm-6">
																<div class="form-group row">
																	<label for="commodities" class="col-sm-3 col-form-label">Commodities <span class="cRed">*</span></label>
																	<div class="col-sm-9">
																		<?php echo $this->Form->control('sub_commodity', array('type'=>'select', 'options'=>$sub_commodity_value, 'multiple'=>'multiple', 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
																	</div>
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group row">
																	<label for="processing fees" class="col-sm-3 col-form-label">Processing Fee (<i class="fas fa-rupee-sign"></i>) : <span class="cRed">*</span></label>
																	<div class="col-sm-9">
																		<?php echo $this->Form->control('total_charge', array('type'=>'text', 'value'=>$added_firm['total_charges'], 'id'=>'total_charge', 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div id="packaging_types_box">
												<div class="card-header sub-card-header-firm"><h3 class="card-title"><i class="fas fa-industry"></i> Packaging Materials</h3></div>
												<div class="form-horizontal">
													<div class="card-body">
														<div class="row">
															<div class="col-sm-6">
																<div class="form-group row">
																	<label for="packaging material" class="col-sm-3 col-form-label">Packaging Materials Used <span class="cRed">*</span></label>
																	<div class="col-sm-9">
																		<?php echo $this->Form->control('packaging_materials', array('type'=>'select', 'options'=>$packaging_materials_value, 'multiple'=>'multiple', 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
																	</div>
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group row">
																	<label for="total charges" class="col-sm-3 col-form-label">Total Charges(RS.):</label>
																	<div class="col-sm-9">
																		<?php echo $this->Form->control('total_charge', array('type'=>'text', 'value'=>$added_firm['total_charges'], 'id'=>'total_charge', 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="card-header sub-card-header-firm"><h3 class="card-title"><i class="far fa-address-card"></i> Premises Address</h3></div>
											<div class="form-horizontal">
												<div class="card-body">
													<div class="row">
														<div class="col-sm-6">
															<div class="form-group row">
																<label for="address" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
																<div class="col-sm-9">
																	<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'value'=>$added_firm['street_address'], 'class'=>'form-control input-field', 'label'=>false, 'disabled'=>'disabled')); ?>
																</div>
															</div>
														</div>
														<div class="col-sm-6">
															<div class="form-group row">
																<label for="state" class="col-sm-4 col-form-label">State/Region <span class="cRed">*</span></label>
																<div class="col-sm-8">
																	<?php echo $this->Form->control('state', array('type'=>'text', 'value'=>$state_value['state_name'], 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
																</div>
															</div>
															<div class="form-group row">
																<label for="district" class="col-sm-4 col-form-label">District <span class="cRed">*</span></label>
																<div class="col-sm-8">
																	<?php echo $this->Form->control('district', array('type'=>'text', 'value'=>$district_value['district_name'], 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
																</div>
															</div>
															<div class="form-group row">
																<label for="pin code" class="col-sm-4 col-form-label">Pin Code <span class="cRed">*</span></label>
																<div class="col-sm-8">
																	<?php echo $this->Form->control('postal_code', array('type'=>'text', 'value'=>$added_firm['postal_code'], 'class'=>'form-control input-field', 'label'=>false, 'disabled'=>'disabled')); ?>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>				
										
									<div class="card-footer cardFooterBackground">
										<?php echo $this->Form->submit('Update', array('name'=>'update', 'label'=>false, 'id'=>'auth_add_firm_validations_call', 'class'=>'btn btn-success float-left')); ?>
										<?php echo $this->Html->link('Back', array('controller' => 'authprocessedoldapp', 'action'=>'home'),array('class'=>'btn btn-secondary float-right'));?>
									</div>
								</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</section>
	</div>

<input type="hidden" value="<?php echo $return_error_msg; ?>" id="return_error_msg"/>

<?php echo $this->Html->script('authprocessedoldapp/edit_firm/edit_firm'); ?>
