	<?php ?>
	<h3 class="card-title-new"> Printing Firm Profile</h3>
		<div id="form_outer_main" class="col-md-12 form-middle">
			<?php echo $this->Form->create(); ?>
				<div class="card card-success">
					<div class="card-header"><h3 class="card-title">Initial Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<label for="field3"><span>Firm Name <span class="cRed">*</span></span>
										<?php echo $this->Form->control('firm_name', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['firm_name'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</label>
									</div>
									<div class="col-md-4">
										<label for="field3"><span>Firm Status <span class="cRed">*</span></span>
										<?php echo $this->Form->control('firm_status', array('type'=>'text', 'escape'=>false, 'value'=>$business_type[$section_form_details[0]['business_type']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</label>
									</div>
									<div class="col-md-4">
										<label for="field3"><span>Firm in Business <span class="cRed">*</span></span>
										<?php echo $this->Form->control('firm_status', array('type'=>'text', 'escape'=>false, 'value'=>$all_printing_business_year[$section_form_details[0]['business_years']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</label>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Firm Address</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-md-3">
											<label for="field3"><span>Address <span class="cRed">*</span></span>
											<?php echo $this->Form->control('street_address', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['street_address'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
											</label>
										</div>
										<div class="col-md-3">
											<label for="field3"><span>State/Region <span class="cRed">*</span></span>
											<?php echo $this->Form->control('state', array('type'=>'text', 'escape'=>false, 'value'=>$state_list[$firm_details['state']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
											</label>
										</div>
										<div class="col-md-3">
											<label for="field3"><span>District <span class="cRed">*</span></span>
											<?php echo $this->Form->control('district', array('type'=>'text', 'escape'=>false, 'value'=>$distict_list[$firm_details['district']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
											</label>
										</div>
										<div class="col-md-3">
											<label for="field3"><span>Pin Code <span class="cRed">*</span></span>
											<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="card-header"><h3 class="card-title">Premises Address</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-md-3">
												<label for="field3"><span>Address <span class="cRed">*</span></span>
												<?php echo $this->Form->control('street_address', array('type'=>'text', 'escape'=>false, 'value'=>$section_form_details[1][0]['street_address'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
												</label>
											</div>
											<div class="col-md-3">
												<label for="field3"><span>State/Region <span class="cRed">*</span></span>
												<?php echo $this->Form->control('state', array('type'=>'text', 'escape'=>false, 'value'=>$state_list[$section_form_details[1][0]['state']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
												</label>
											</div>
											<div class="col-md-3">
												<label for="field3"><span>District <span class="cRed">*</span></span>
												<?php echo $this->Form->control('district', array('type'=>'text', 'escape'=>false, 'value'=>$distict_list[$section_form_details[1][0]['district']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
												</label>
											</div>
											<div class="col-md-3">
												<label for="field3"><span>Pin Code <span class="cRed">*</span></span>
												<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'value'=>$section_form_details[1][0]['postal_code'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer cardFooterBackground">
									<div class="form-buttons">
										<a  class="btn bg-cyan" href="<?php echo $this->request->getAttribute('webroot')	;?>inspections/section/2" >Start Inspection</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

<?php echo $this->Html->script('element/siteinspection_forms/new/printing/firm_profile'); ?>
