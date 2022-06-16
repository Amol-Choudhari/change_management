<?php ?>
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

<section class="content form-middle form_outer_class" id="form_outer_main">
	<div class="container-fluid">
		<h5 class="mt-1 mb-2">Printing Firm Profile</h5>
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div class="card-header"><h3 class="card-title">Initial Details</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Applicant ID<span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('applicant_id', array('type'=>'text', 'value'=>$firm_details['customer_id'], 'class'=>'form-control input-field',  'label'=>false, 'placeholder'=>'id')); ?>
													</div>
												</div>
												<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label"> Email Id<span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('email_id', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['email'], 'label'=>false,'class'=>'form-control input-field' )); ?>
												 </div>
										  	 </div>
										 </div>
										 <div class="col-sm-6">
										  <div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Name<span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('firm_name', array('type'=>'text', 'value'=>$firm_details['firm_name'], 'label'=>false, 'placeholder'=>'Name','class'=>'form-control input-field')); ?>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Mobile No.<span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('mobile_no', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['mobile_no'], 'label'=>false,'class'=>'form-control input-field' )); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-header"><h3 class="card-title">Premises Address</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-4 col-form-label"> Address <span class="cRed">*</span></label>
														<div class="custom-file col-sm-8">
															<?php echo $this->Form->control('street_address', array('type'=>'text', 'id'=>'street_address', 'escape'=>false, 'value'=>$firm_details['street_address'], 'class'=>'form-control input-field', 'label'=>false)); ?>
															</div>
														</div>
														<div class="form-group row">
															<label for="inputEmail3" class="col-sm-4 col-form-label">State/Region<span class="cRed">*</span></label>
																<div class="custom-file col-sm-8">
																	<?php echo $this->Form->control('state', array('type'=>'text', 'id'=>'state', 'value'=>$state_list[$firm_details['state']], 'label'=>false,'class'=>'form-control input-field')); ?>
																</div>
															</div>
														</div>
														<div class="col-sm-6">
															<div class="form-group row">
																<label for="inputEmail3" class="col-sm-4 col-form-label"> District<span class="cRed">*</span></label>
																	<div class="custom-file col-sm-8">
																		<?php echo $this->Form->control('district', array('type'=>'text', 'id'=>'district', 'value'=>$distict_list[$firm_details['district']], 'label'=>false,'class'=>'form-control input-field')); ?>
																	</div>
															</div>
															<div class="form-group row">
																<label for="inputEmail3" class="col-sm-4 col-form-label">Pin Code<span class="cRed">*</span></label>
																	<div class="custom-file col-sm-8">
																		<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'class'=>'input-field form-control', 'label'=>false)); ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="card-header"><h3 class="card-title">Type of Packing</h3></div>
														<div class="form-horizontal" class="pdB44">
															<div class="card-body">
																<div class="row mb-1">
																	<div class="col-sm-6">
																		<div class="form-group row">
																			<label for="inputEmail3" class="col-sm-4 col-form-label"> Type of Packing<span class="cRed">*</span></label>
																			<div class="custom-file col-sm-8">
																			<?php echo $this->Form->control('packing_type', array('type'=>'select', 'options' =>$section_form_details[1], 'label'=>false, 'multiple'=>true, 'placeholder'=>'Type of Packing','class'=>'form-control input-field')); ?>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												<div class="card-header"><h3 class="card-title">Validty and Grant Dates</h3></div>
													<div class="form-horizontal">
														<div class="card-body">
															<div class="row">
																<div class="col-sm-6">
																	<div class="form-group">
																		<label for="inputEmail3" class="col-sm-4 col-form-label"> Date of Validity<span class="cRed">*</span></label>
																			<div class="custom-file col-sm-8">
																				<?php echo $this->Form->control('validity_upto', array('type'=>'text', 'id'=>'validity_upto', 'escape'=>false, 'value'=>$section_form_details[0]['validity_upto'], 'label'=>false, 'readonly'=>true,'class'=>'form-control')); ?>
																				</div>
																			<div id="error_validity_upto"></div>
																		</div>
																	</div>
																	<div class="col-sm-6">
																		<div class="form-group">
																			<div id='grant_date'>
																				<label for="field3" class="col-sm-4 col-form-label">Date of grant<span class="cRed">*</span></label>
																					<div class="custom-file col-sm-8">
																						<?php echo $this->Form->control('grant_date', array('type'=>'text', 'value'=>chop($section_form_details[2]['date'],'00:00:00'), 'label'=>false, 'placeholder'=>'Date of granted','class'=>'form-control')); ?>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="card-header"><h3 class="card-title">Last Validity</h3></div>
																<div class="form-horizontal">
																	<div class="card-body">
																		<div id="tbls_table">
																			<div class="col-sm-12"><p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether applicant  furnishes particulars of printing of replica carried out during the last validity period (02 Years) ?</p></div>
																				<div class="col-md-12">
																				<?php
																						$options=array('yes'=>'Yes','no'=>'No');
																						$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['is_particulars_furnished'], 'id'=>'is_particulars_furnished', 'label'=>true);
																						echo $this->form->radio('is_particulars_furnished',$options,$attributes); 	?>
																				<br><br>
																				<div id="error_is_particulars_furnished"></div> <!--create div field for showing error message (by pravin 11/05/2017)-->

																				<div id="renew_last_validity_period" class="machinery_table">
																					<?php echo $this->element('application_forms/renewal/printing/printing_renewal_packer_details');?>
																					<div id="error_packer_table_detail"></div> <!--create div field for showing error message (by pravin 11/05/2017)-->
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

<input type="hidden" id="final_renewal_submit_status_id" value="<?php echo $final_submit_status; ?>">
<?php echo $this->Html->script('element/application_forms/renewal/printing/printing_renewal'); ?>
