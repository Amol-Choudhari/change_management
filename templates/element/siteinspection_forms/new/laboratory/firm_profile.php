<?php ?>
	<section id="form_outer_main" class="content form-middle">
		<div class="container-fluid">
	  		<h5 class="mt-1 mb-2">Laboratory Firm Details</h5>
	    		<div class="row">
	     			 <div class="col-md-12">
						<?php echo $this->Form->create(); ?>
            				<div class="card card-success"><div class="card-header"><h3 class="card-title">Firm Details</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
	                    							<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name', 'escape'=>false, 'value'=>$firm_details['firm_name'], 'class'=>'input-field', 'label'=>false,'class'=>'form-control')); ?>
	                    							</div>
	                  							</div>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('street_address', array('type'=>'text', 'id'=>'street_address', 'escape'=>false, 'value'=>$firm_details['street_address'], 'class'=>'input-field', 'label'=>false,'class'=>'form-control')); ?>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('state', array('type'=>'text', 'id'=>'state', 'value'=>$state_list[$firm_details['state']], 'label'=>false,'class'=>'form-control')); ?>
													</div>
												</div>
                							</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('district', array('type'=>'text', 'id'=>'district', 'value'=>$distict_list[$firm_details['district']], 'label'=>false,'class'=>'form-control')); ?>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'class'=>'input-field', 'label'=>false,'class'=>'form-control')); ?>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('email_id', array('type'=>'text', 'escape'=>false, 'value'=>base64_decode($firm_details['email']), 'label'=>false, 'class'=>'form-control')); //for email encoding ?>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('mobile_no', array('type'=>'text', 'escape'=>false, 'value'=>base64_decode($firm_details['mobile_no']), 'label'=>false,'class'=>'form-control')); ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
									<div class="card-header"><h3 class="card-title">Laboratory Type</h3></div>
										<div class="form-horizontal">
											<div class="card-body">
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group row">
															<label for="inputEmail3" class="col-sm-3 col-form-label">Type of laboretory <span class="cRed">*</span></label>
															<div class="custom-file col-sm-9">
															<?php echo $this->Form->control('laboratory_type_value', array('type'=>'text', 'id'=>'laboretory_type', 'value'=>$section_form_details[1][$section_form_details[0]['laboratory_type']], 'escape'=>false, 'class'=>'input-field', 'label'=>false,'class'=>'form-control')); ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-header"><h3 class="card-title">Commodity/ies</h3></div>
											<div class="form-horizontal pdB58">
												<div class="card-body">
													<div class="row">
														<div class="col-sm-6"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Name of commodities proposed to be graded</p></div>
														<div class="col-sm-6">
															<div class="form-group row">
																<label for="inputEmail3" class="col-sm-3 col-form-label">Commodities List <span class="cRed">*</span></label>
																<div class="custom-file col-sm-9">
																<?php echo $this->Form->control('types_of_sub_commodities', array('type'=>'select', 'options'=>$section_form_details[2], 'values'=>'', 'multiple'=>'multiple', 'escape'=>false, 'label'=>false,'class'=>'form-control')); ?>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="form-buttons mb-3">
												<a class="btn btn-success" href="<?php echo $this->request->getAttribute('webroot');?>inspections/section/2">Start Inspection</a>
											</div>
										<?php echo $this->Form->end(); ?>
									</div>
								</div>
							</div>
						</div>
					</section>

				<?php echo $this->Html->script('element/siteinspection_forms/new/laboratory/firm_profile'); ?>
