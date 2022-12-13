<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

<section class="content form-middle form_outer_class" id="form_outer_main">
	<div class="container-fluid">
	  	<h5 class="mt-1 mb-2">Change Details</h5>
	    
			<div class="card col-md-12">
				<div class="row">
					
					<?php if (in_array(1,$selectedValues)) { // for firm name ?>

						<div class="col-md-12"><div class="card card-success"><div class="card-header"><h3 class="card-title">Firm Name</h3></div></div></div>
						<div class="clearfix"></div>
						<!-- fields for new change value-->
						<div class="col-md-6">
							<p><b>New Details</b></p>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name', 'escape'=>false, 'value'=>$section_form_details[0]['firm_name'], 'class'=>'form-control input-field', 'label'=>false)); ?>
									<span id="error_firm_name" class="error invalid-feedback"></span>
								</div>
							</div>
						</div>
						<!-- fields to show last value-->
						<div class="col-md-6 last_details_change">
							<p><b>Last Details</b></p>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('firm_name_last', array('type'=>'text','escape'=>false, 'value'=>$section_form_details[1]['firm_name'], 'class'=>'form-control input-field', 'label'=>false, 'disabled'=>true)); ?>
								</div>
							</div>
						</div>
					<?php } ?>

					<?php if (in_array(2,$selectedValues)) { // for firm contact details ?>
						<div class="col-md-12"><div class="card card-success"><div class="card-header"><h3 class="card-title">Contact Details</h3></div></div></div>
						<div class="clearfix"></div>
						<!-- fields for new change value-->
						<div class="col-md-6">
							<p><b>New Details</b></p>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('mobile_no', array('type'=>'text', 'id'=>'mobile_no', 'escape'=>false, 'value'=>$section_form_details[0]['mobile_no'], 'class'=>'form-control input-field', 'label'=>false)); ?>
									<span id="error_mobile_no" class="error invalid-feedback"></span>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('email_id', array('type'=>'text', 'id'=>'email_id', 'escape'=>false, 'value'=>$section_form_details[0]['email_id'], 'class'=>'form-control input-field', 'label'=>false)); ?>
									<span id="error_email_id" class="error invalid-feedback"></span>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Phone No. <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('phone_no', array('type'=>'text', 'id'=>'phone_no', 'escape'=>false, 'value'=>$section_form_details[0]['phone_no'], 'class'=>'form-control input-field', 'label'=>false)); ?>
									<span id="error_phone_no" class="error invalid-feedback"></span>
								</div>
							</div>
						</div>
						<!-- fields to show last value-->
						<div class="col-md-6 last_details_change">
							<p><b>Last Details</b></p>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('mobile_no_last', array('type'=>'text','escape'=>false, 'value'=>base64_decode($section_form_details[1]['mobile_no']), 'class'=>'form-control input-field', 'label'=>false)); ?>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('email_id_last', array('type'=>'text','escape'=>false, 'value'=>base64_decode($section_form_details[1]['email']), 'class'=>'form-control input-field', 'label'=>false)); ?>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Phone No. <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('phone_no_last', array('type'=>'text','escape'=>false, 'value'=>base64_decode($section_form_details[1]['fax_no']), 'class'=>'form-control input-field', 'label'=>false)); ?>
								</div>
							</div>
						</div>

					<?php } ?>	

					<?php if (in_array(5,$selectedValues)) { // for Premises/Location ?>
						<!-- fields for new change value-->
						<div class="col-md-12"><div class="card card-success"><div class="card-header"><h3 class="card-title">Premises/Location</h3></div></div></div>
						<div class="clearfix"></div>
						<div class="col-md-6">
							<p><b>New Details</b></p>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
									<div class="custom-file col-sm-9">
										<?php echo $this->Form->control('premise_street', array('type'=>'textarea', 'id'=>'street_address', 'escape'=>false, 'value'=>$section_form_details[0]['premise_street'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter street address')); ?>
										<span id="error_premise_street" class="error invalid-feedback"></span>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
									<div class="custom-file col-sm-9">
										<?php echo $this->Form->control('premise_state', array('type'=>'select', 'id'=>'state', 'options'=>$state_list,  'value'=>$section_form_details[0]['premise_state'],  'empty'=>'Select', 'label'=>false,'class'=>'form-control')); ?>
										<span id="error_premise_state" class="error invalid-feedback"></span>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
									<div class="custom-file col-sm-9">
										<?php echo $this->Form->control('premise_city', array('type'=>'select', 'id'=>'district', 'options'=>$section_form_details[0]['dist_list'], 'value'=>$section_form_details[0]['premise_city'], 'label'=>false, 'class'=>'form-control')); ?>
										<span id="error_premise_city" class="error invalid-feedback"></span>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
									<div class="custom-file col-sm-9">
										<?php echo $this->Form->control('premise_pin', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$section_form_details[0]['premise_pin'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter postal/zip code')); ?>
										<span id="error_premise_pin" class="error invalid-feedback"></span>
								</div>
							</div>
						</div>
						<!-- fields to show last value-->
						<div class="col-md-6 last_details_change">
							<p><b>Last Details</b></p>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
									<div class="custom-file col-sm-9">
										<?php echo $this->Form->control('street_address_last', array('type'=>'textarea', 'escape'=>false, 'value'=>$section_form_details[2]['street_address'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter street address')); ?>
								
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
									<div class="custom-file col-sm-9">
										<?php echo $this->Form->control('state_last', array('type'=>'select','options'=>$state_list,  'value'=>$section_form_details[2]['state'],  'empty'=>'Select', 'label'=>false,'class'=>'form-control')); ?>
									
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
									<div class="custom-file col-sm-9">
										<?php echo $this->Form->control('district_last', array('type'=>'select','options'=>$distict_list, 'value'=>$section_form_details[2]['district'], 'label'=>false, 'class'=>'form-control')); ?>
									
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
									<div class="custom-file col-sm-9">
										<?php echo $this->Form->control('postal_code_last', array('type'=>'text','escape'=>false, 'value'=>$section_form_details[2]['postal_code'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter postal/zip code')); ?>
									<span id="error_postal_code" class="error invalid-feedback"></span>
								</div>
							</div>
						</div>

					<?php } ?>

					<?php if (in_array(3,$selectedValues)) { // for TBL details ?>
						<!-- fields for new change value-->
						<div class="col-md-12"><div class="card card-success"><div class="card-header"><h3 class="card-title">TBL Details</h3></div></div></div>
						<div class="clearfix"></div>
						<?php echo $this->element('application_forms/change/tbl_details_table'); ?>

					<?php } ?>

					<?php if (in_array(4,$selectedValues)) { // for Directors details ?>
						<!-- fields for new change value-->
						<div class="col-md-12"><div class="card card-success"><div class="card-header"><h3 class="card-title">Director/Partner Details</h3></div></div></div>
						<div class="clearfix"></div>
						<?php echo $this->element('application_forms/change/directors_details_table_view'); ?>

					<?php } ?>

					<?php if (in_array(6,$selectedValues)) { // for Laboratory details ?>
						<!-- fields for new change value-->
						<div class="col-md-12"><div class="card card-success"><div class="card-header"><h3 class="card-title">Laboratory Details</h3></div></div></div>
						<div class="clearfix"></div>
						<div class="col-md-6">
							<p><b>New Details</b></p>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-4 col-form-label">Laboratory Name <span class="cRed">*</span></label>
								<div class="custom-file col-sm-8">
									<?php echo $this->form->control('lab_name', array('type'=>'text', 'id'=>'lab_name', 'escape'=>false, 'value'=>$section_form_details[0]['lab_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter laboratory name')); ?>
									<span id="error_lab_name" class="error invalid-feedback"></span>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-4 col-form-label">Laboratory Type <span class="cRed">*</span></label>
								<div class="custom-file col-sm-8">
									<?php echo $this->form->control('lab_type', array('type'=>'select', 'id'=>'lab_type', 'options'=>$section_form_details[5][0], 'value'=>$section_form_details[0]['lab_type'], 'label'=>false, 'class'=>'form-control')); ?>
									<span id="error_lab_type" class="error invalid-feedback"></span>
								</div>
							</div>
							
							<div id="show_chemist_details">
								<div class="d-inline-block">
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Upload Details of Approved Chemists</p>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span></label>
											<?php if(!empty($section_form_details[0]['chemist_details_docs'])){?>
												<a id="chemist_detail_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['chemist_details_docs']); ?>">Preview</a>
											<?php } ?>
										
										<div class="custom-file col-sm-9">
											<input type="file" name="chemist_details_docs" class="form-control" id="chemist_details_docs", multiple='multiple'>
											<span id="error_chemist_details_docs" class="error invalid-feedback"></span>
											<span id="error_type_chemists_detail_docs" class="error invalid-feedback"></span>
											<span id="error_size_chemists_detail_docs" class="error invalid-feedback"></span>
										</div>
									</div>
									<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
								</div>

								<div class="d-inline-block">
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Upload Details of Instruments, Details of Glass Apparatus, Details of Chemicals</p>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span></label>
											<?php if(!empty($section_form_details[0]['lab_equipped_docs'])){?>
												<a id="lab_equipped_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['lab_equipped_docs']); ?>">Preview</a>
											<?php } ?>
										
										<div class="custom-file col-sm-9">
											<input type="file" name="lab_equipped_docs" class="form-control" id="lab_equipped_docs", multiple='multiple'>
											<span id="error_lab_equipped_docs" class="error invalid-feedback"></span>
											<span id="error_type_lab_equipped_docs" class="error invalid-feedback"></span>
											<span id="error_size_lab_equipped_docs" class="error invalid-feedback"></span>
										</div>
									</div>
									<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
								</div>
							</div>

							<div id="hide_consent_letter">
								<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Consent letter of the laboratory may be enclosed, Not required in case of own laboratory.</p>
										<div class="form-group row">
										<label for="inputEmail3" class="col-sm-2 col-form-label">Attach File: <span class="cRed">*</span></label>
											<?php if(!empty($section_form_details[0]['lab_consent_docs'])){?>
												<a id="consent_letter_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['lab_consent_docs']); ?>">Preview</a>
											<?php } ?>
										
										<div class="custom-file col-sm-4">
											<input type="file" name="lab_consent_docs" class="form-control" id="lab_consent_docs", multiple='multiple'>
											<span id="error_lab_consent_docss" class="error invalid-feedback"></span>
											<span id="error_type_lab_consent_docs" class="error invalid-feedback"></span>
											<span id="error_size_lab_consent_docs" class="error invalid-feedback"></span>
										</div>
									</div>
								<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
							</div>
						</div>

						<!-- fields to show last value-->
						<div class="col-md-6 last_details_change">
							<p><b>Last Details</b></p>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-4 col-form-label">Laboratory Name <span class="cRed">*</span></label>
								<div class="custom-file col-sm-8">
									<?php echo $this->form->control('laboratory_name', array('type'=>'text','escape'=>false, 'value'=>$section_form_details[5][1]['laboratory_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter laboratory name')); ?>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-4 col-form-label">Laboratory Type <span class="cRed">*</span></label>
								<div class="custom-file col-sm-8">
									<?php echo $this->form->control('laboratory_type', array('type'=>'select','options'=>$section_form_details[5][0], 'value'=>$section_form_details[5][1]['laboratory_type'], 'label'=>false, 'class'=>'form-control')); ?>
								
								</div>
							</div>

							<?php if(!empty($section_form_details[5][1]['chemist_detail_docs'])){?>
								<div class="d-inline-block">
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Upload Details of Approved Chemists</p>
									<div class="form-group row">
									Attached File : <a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[5][1]['chemist_detail_docs']); ?>">Preview</a>
									</div>
									<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
								</div>
							<?php } ?>

							<?php if(!empty($section_form_details[5][1]['lab_equipped_docs'])){?>
								<div class="d-inline-block">
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Upload Details of Instruments, Details of Glass Apparatus, Details of Chemicals</p>
									<div class="form-group row">
									Attached File : <a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[5][1]['lab_equipped_docs']); ?>">Preview</a>	
									</div>
									<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
								</div>
							<?php } ?>

							<?php if(!empty($section_form_details[5][1]['consent_letter_docs'])){?>
								<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Consent letter of the laboratory may be enclosed, Not required in case of own laboratory.</p>
									<div class="form-group row">
										Attached File : <a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[5][1]['consent_letter_docs']); ?>">Preview</a>	
									</div>
								<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
							<?php } ?>
						</div>	
					<?php } ?>

					<?php if (in_array(7,$selectedValues)) { // for category and commodities ?>
						
						
						<div class="clearfix"></div>
						<?php echo $this->element('application_forms/change/change_update_commodity_popup'); ?>

						
					<?php } ?>
				</div>
			</div>
		</div>
</section>


	<div id="form_outer_main" class="form-style-3" class="form_outer_class">

	</div>
	
	<input type="hidden" id="selectedValues" value="<?php echo implode(',',$selectedValues); ?>">
	<input type="hidden" id="firm_type" value="<?php echo $firm_type; ?>">

<?php 

echo $this->Html->script('element/application_forms/change/change_appl_js'); ?>
