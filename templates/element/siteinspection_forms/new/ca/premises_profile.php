<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
	<div id="form_outer_main" class="form-style-3 col-md-10 form-middle">
		<h5 class="mt-1 mb-2 tacfw700">Premises Profile</h5>
		<div id="form_inner_main" class="card card-success">

			<?php if($application_type !=3) { ?>
				<div class="card-header"><h3 class="card-title">Director/Partner/Proprietor/Owner Details</h3></div>
					<div class="tank_table form-horizontal">
						<div class="card-body mb-3">
							<?php echo $this->element('old_applications_elements/old_app_directors_details_table_view'); ?>
						</div>
					</div>
			<?php } ?>

			<div class="card-header"><h3 class="card-title">Premises Address</h3></div>
			<div class="form-horizontal">
				<div class="card-body">
					<div class="row">
						<div class="col-md-3">
							<label for="field3" class="col-form-label"><span>Address <span class="cRed">*</span></span>
								<?php echo $this->Form->control('street_address', array('type'=>'text', 'escape'=>false, 'value'=>$section_form_details[1][0]['street_address'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
							</label>
						</div>
						<div class="col-md-3">
							<label for="field3" class="col-form-label"><span>State/Region <span class="cRed">*</span></span>
								<?php echo $this->Form->control('state', array('type'=>'text', 'escape'=>false, 'value'=>$state_list[$section_form_details[1][0]['state']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
							</label>
						</div>
						<div class="col-md-3">
							<label for="field3" class="col-form-label"><span>District <span class="cRed">*</span></span>
								<?php echo $this->Form->control('district', array('type'=>'text', 'escape'=>false, 'value'=>$distict_list[$section_form_details[1][0]['district']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
							</label>
						</div>
						<div class="col-md-3">
							<label for="field3" class="col-form-label"><span>Pin Code <span class="cRed">*</span></span>
								<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'value'=>$section_form_details[1][0]['postal_code'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
							</label>
						</div>
					</div>
				</div>
			</div>

			<div class="card-header"><h3 class="card-title">Inspection Photos</h3></div>
			<div class="form-horizontal">
				<div class="card-body">
					<div class="row">
						<div class="col-md-2">
							<label for="inputEmail3" class="col-form-label">Attach File <span class="cRed">*</span> : </label>
						</div>
						<?php if (!empty($section_form_details[0]['inspection_pics'])) { ?>
							<div class="col-md-2">
								<a id="inspection_pics_value" target="blank" href="<?php echo $section_form_details[0]['inspection_pics']; ?>">Preview</a>
							</div>
						<?php } ?>

						<div class="col-md-4">
							<?php echo $this->Form->control('inspection_pics',array('type'=>'file', 'id'=>'inspection_pics','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>

							<span id="error_inspection_pics" class="error invalid-feedback"></span>
							<span id="error_size_inspection_pics" class="error invalid-feedback"></span>
							<span id="error_type_inspection_pics" class="error invalid-feedback"></span>
						</div>
					</div>
					<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
				</div>
			</div>
		</div>
	</div>

<input type="hidden" id="final_status_id" value="<?php echo $section_status; ?>">
<?php echo $this->Html->script('element/siteinspection_forms/new/ca/premises_profile'); ?>
