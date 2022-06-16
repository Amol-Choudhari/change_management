
<?php echo $this->Form->create(null, array('type'=>'file','enctype'=>'multipart/form-data', 'id'=>$section)); ?>
	<!--This hidden field is created on 29-06-2017 by Amol to get new csrf token from session on each ajax request
	and append that value to hidden csrf token field value on ajax complete -->
	<!--<div id="new_token_key_div">
	<input type="hidden" id="new_token_key" name="new_token_key" value="<?php //echo $_SESSION['_Token']['key']; ?>" />
	</div>	-->
	<section class="content form-middle form_outer_class" id="form_outer_main">
		<div class="container-fluid">
	  		<h5 class="mt-1 mb-2">Premises Profile</h5>
	    		<div class="row">
					<div class="col-md-12">
						<div class="card card-success">

							<?php if($ca_bevo_applicant == 'yes'){ ?>

								<div class="card-header sub-card-header-firm"><h3 class="card-title">Storage Tanks Details</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-12">
													<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Details of tanks to be used for storage of the Constituent Oils and Blended Vegetable Oil proposed to be graded.</p>
												</div>
												<div class="col-sm-12 tank_table">
													<!-- call table view form element with ajax call -->
													<?php echo $this->element('ca_other_tables_elements/tank_details_table_view'); ?>
												</div>
											</div>
										</div>
									</div>

	              					<div class="card-header"><h3 class="card-title">BEVO Mills Details(if any)</h3></div>
										<div class="form-horizontal">
											<div class="card-body p-0 m-4 rounded">
												<div class="row">
													<div class="col-sm-12">
														<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Name and address of the mills (if any), where Blended Edible Vegetable Oil will be manufactured.</p>
													</div>
													<div class="col-sm-6">
														<div class="form-group row">
															<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
																<?php if(!empty($section_form_details[0]['bevo_mills_address_docs'])){ ?>
																<a id="bevo_mills_address_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['bevo_mills_address_docs']); ?>">Preview</a>
																<?php } ?>
															</label>

															<div class="custom-file col-sm-9">
															<input type="file" name="bevo_mills_address_docs" class="custom-file-input" id="bevo_mills_address_docs" multiple='multiple'>
															<label class="custom-file-label" for="customFile">Choose file</label>
															<span id="error_bevo_mills_address_docs" class="error invalid-feedback"></span>
															<span id="error_size_bevo_mills_address_docs" class="error invalid-feedback"></span>
															<span id="error_type_bevo_mills_address_docs" class="error invalid-feedback"></span>
															</div>
														</div>
														<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
													</div>
												</div>
											</div>
										</div>

	             						<div class="card-header"><h3 class="card-title">Are separate tanks used ?</h3></div>
											<div class="form-horizontal">
												<div class="card-body p-0 m-4 rounded">
													<div class="row">
														<div class="col-sm-6">
															<div class="form-group row">
																<label for="inputEmail3" class="col-sm-9 col-form-label text-sm">Are separate tanks used for storage of different oils ?</label>
																	<div class="col-sm-3">
																		<?php
																			$sep_tank = $section_form_details[0]['separate_tanks_used'];
																			if($sep_tank == 'yes'){
																				$sep_tank_yes = 'checked';
																				$sep_tank_no = '';
																			} else if($sep_tank == 'no'){
																				$sep_tank_yes = '';
																				$sep_tank_no = 'checked';
																			} else {
																				$sep_tank_yes = '';
																				$sep_tank_no = '';
																			}
																		?>
																		<div class="icheck-success d-inline">
																			<input type="radio" name="separate_tanks_used" id="separate_tanks_used-yes" value="yes" <?php echo $sep_tank_yes; ?>>
																			<label for="separate_tanks_used-yes">Yes
																			</label>
																		</div>
																		<div class="icheck-success d-inline">
																			<input type="radio" name="separate_tanks_used" id="separate_tanks_used-no" value="no" <?php echo $sep_tank_no; ?>>
																			<label for="separate_tanks_used-no">No
																			</label>
																		</div>
																		<span id="error_separate_tanks_used" class="error invalid-feedback"></span>
																	</div>
																</div>
															</div>
															<div class="col-sm-6" id="hide_separate_tanks">
																<div class="form-group row">
																	<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
																		<?php if(!empty($section_form_details[0]['separate_tanks_docs'])){ ?>
																		<a id="separate_tanks_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['separate_tanks_docs']); ?>">Preview</a>
																		<?php } ?>
																	</label>

																	<div class="custom-file col-sm-9">
																	<input type="file" name="separate_tanks_docs" class="custom-file-input" id="separate_tanks_docs" multiple='multiple'>
																	<label class="custom-file-label" for="customFile">Choose file</label>
																	<span id="error_separate_tanks_docs" class="error invalid-feedback"></span>
																	<span id="error_size_separate_tanks_docs" class="error invalid-feedback"></span>
																	<span id="error_type_separate_tanks_docs" class="error invalid-feedback"></span>
																	</div>
																</div>
																<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
															</div>
														</div>
													</div>
												</div>

												<div class="card-header"><h3 class="card-title">Locking Arrangement</h3></div>
													<div class="form-horizontal">
														<div class="card-body p-0 m-4 rounded">
															<div class="row">
																<div class="col-sm-12">
																	<div class="form-group row">
																		<label for="inputEmail3" class="col-sm-9 col-form-label text-sm">Whether the locking arrangements have been provided with the storage tanks both at inlets and outlets ?</label>
																		<div class="col-sm-3">
																			<?php
																				$lock_tank = $section_form_details[0]['locking_for_storage_tanks'];

																				if($lock_tank == 'yes'){
																					$lock_tank_yes = 'checked';
																					$lock_tank_no = '';
																				} else if($lock_tank == 'no'){
																					$lock_tank_yes = '';
																					$lock_tank_no = 'checked';
																				} else {
																					$lock_tank_yes = '';
																					$lock_tank_no = '';
																				}
																			?>
																			<div class="icheck-success d-inline">
																				<input type="radio" name="locking_for_storage_tanks" id="locking_for_storage_tanks-yes" value="yes" <?php echo $lock_tank_yes; ?>>
																				<label for="locking_for_storage_tanks-yes">Yes
																				</label>
																			</div>
																			<div class="icheck-success d-inline">
																				<input type="radio" name="locking_for_storage_tanks" id="locking_for_storage_tanks-no" value="no" <?php echo $lock_tank_no; ?>>
																				<label for="locking_for_storage_tanks-no">No
																				</label>
																			</div>
																			<span id="error_locking_for_storage_tanks" class="error invalid-feedback"></span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>



							<?php } ?>

            			  <div class="card-header"><h3 class="card-title">Address</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'id'=>'street_address', 'escape'=>false, 'value'=>$section_form_details[0]['street_address'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter street address')); ?>
													<span id="error_street_address" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('state', array('type'=>'select', 'id'=>'state', 'options'=>$state_list,  'value'=>$section_form_details[0]['state'],  'empty'=>'Select', 'label'=>false,'class'=>'form-control')); ?>
													<span id="error_state" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('district', array('type'=>'select', 'id'=>'district', 'options'=>$section_form_details[2], 'value'=>$section_form_details[0]['district'], 'label'=>false, 'class'=>'form-control')); ?>
													<span id="error_district" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$section_form_details[0]['postal_code'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter postal/zip code')); ?>
													<span id="error_postal_code" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>
	</section>
<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
<input type="hidden" id="ca_bevo_applicant_id" value="<?php echo $ca_bevo_applicant; ?>">
<?php echo $this->Html->script('element/application_forms/new/ca/ca_premises'); ?>
	
