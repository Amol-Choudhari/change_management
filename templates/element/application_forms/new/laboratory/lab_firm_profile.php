<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

	<section class="content form-middle form_outer_class" id="form_outer_main">
		<div class="container-fluid">
			<h5 class="mt-1 mb-2">Laboratory Firm Profile</h5>
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div class="card-header"><h3 class="card-title">Firm Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body marginB10">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Laboratory Name <span class="cRed">*</span></label>
											<div class="col-sm-8">
												<?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name', 'escape'=>false, 'value'=>$firm_details['firm_name'], 'class'=>'form-control input-field', 'label'=>false, 'disabled'=>true)); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Type of laboratory <span class="cRed">*</span></label>
											<div class="col-sm-8">
												<?php echo $this->Form->control('laboratory_type', array('type'=>'select', 'id'=>'laboretory_type', 'options'=>$section_form_details[1], 'value'=>$section_form_details[0]['laboratory_type'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?>
											</div>
										</div>
									</div>
									<div id="export_unit" class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Commodities List <span class="cRed">*</span></label>
											<div class="col-sm-8">
												<?php echo $this->Form->control('types_of_sub_commodities',  array('type'=>'select', 'id'=>'types_of_sub_commodities', 'options'=>$section_form_details[2], 'values'=>'', 'multiple'=>'multiple', 'escape'=>false, 'label'=>false, 'class'=>'form-control')); ?>
											</div>
										</div>

										<!-- Element call to provide option to update/change commodities -->
										<?php echo $this->element('/application_forms/update_commodity_popup'); ?>
									</div>
								</div>
							</div>
						</div>

						<div id="address">
							<div class="card-header sub-card-header-firm"><h3 class="card-title"> Firm Address</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Address <span class="cRed">*</span></label>
												<div class="col-sm-8">
													<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'id'=>'street_address', 'escape'=>false, 'value'=>$firm_details['street_address'], 'class'=>'form-control input-field', 'label'=>false, )); ?>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Email Id <span class="cRed">*</span></label>
												<div class="col-sm-8">
													<?php echo $this->Form->control('email_id', array('type'=>'email', 'escape'=>false, 'value'=>base64_decode($firm_details['email']), 'id'=>'email', 'label'=>false, 'class'=>'form-control')); //for email encoding ?>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">State/Region <span class="cRed">*</span></label>
												<div class="col-sm-8">
													<?php echo $this->Form->control('state', array('type'=>'text', 'id'=>'state', 'value'=>$state_list[$firm_details['state']], 'label'=>false, 'class'=>'form-control')); ?>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">District <span class="cRed">*</span></label>
												<div class="col-sm-8">
													<?php echo $this->Form->control('district', array('type'=>'text', 'id'=>'district', 'value'=>$distict_list[$firm_details['district']], 'label'=>false, 'class'=>'form-control')); ?>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Pin Code <span class="cRed">*</span></label>
												<div class="col-sm-8">
													<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'class'=>'form-control input-field', 'label'=>false)); ?>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Mobile No. <span class="cRed">*</span></label>
												<div class="col-sm-8">
													<?php echo $this->Form->control('mobile_no', array('type'=>'text', 'escape'=>false, 'value'=>base64_decode($firm_details['mobile_no']), 'id'=>'mobile_no', 'label'=>false, 'class'=>'form-control')); ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Firm Status</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Business Type <span class="cRed">*</span></label>
											<div class="col-sm-8">
												<?php echo $this->Form->control('business_type', array('type'=>'select', 'id'=>'business_type', 'options'=>$business_type, 'value'=>$section_form_details[0]['business_type'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?>
											</div>
										</div>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i> Please select the Document name which is to be uploaded. Have a scanned copy of it ready?</p>
									</div>
									<div id="export_unit" class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Attach File <span class="cRed">*</span>:
												<?php if(!empty($section_form_details[0]['business_type_docs'])){ ?>
													<a target="blank" id="business_type_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['business_type_docs']); ?>">Preview</a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-8">
												<input type="file" name="business_type_docs" class="form-control" id="business_type_docs", multiple='multiple'>
												<span id="error_business_type_docs" class="error invalid-feedback"></span>
												<span id="error_size_business_type_docs" class="error invalid-feedback"></span>
												<span id="error_type_business_type_docs" class="error invalid-feedback"></span>
											</div>
										</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Date Establishment</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Date <span class="cRed">*</span></label>
											<div class="col-sm-8">
												<?php echo $this->Form->control('establishment_date', array('type'=>'text', 'value'=>chop($section_form_details[0]['establishment_date'],"00:00:00"),  'escape'=>false, 'label'=>false, 'id'=>'pickdate', 'readonly'=>true, 'placeholder'=>'Please Enter Establishment Date', 'class'=>'form-control')); ?>
												<span id="error_establishment_date" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

              <?php if ($oldapplication == 'yes') { ?>
			  
					<div class="card-header"><h3 class="card-title">Old Certification Details</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Scan and attach old certification documents</p>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Attach File <span class="cRed">*</span> :
												<?php if(!empty($section_form_details[3]['old_certificate_pdf'])){ ?>
													<a target="blank" id="old_certification_pdf_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[3]['old_certificate_pdf']); ?>" >Preview</a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-8">
												<input type="file" name="old_certification_pdf" class="form-control" id="old_certification_pdf", multiple='multiple'>
												<span id="error_old_certification_pdf" class="error invalid-feedback"></span>
												<span id="error_size_old_certification_pdf" class="error invalid-feedback"></span>
												<span id="error_type_old_certification_pdf" class="error invalid-feedback"></span>
											</div>
										</div>
									<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
								</div>
								<div class="col-sm-6">
									<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Scan and attach all old application documents as single pdf file</p>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Attach File <span class="cRed">*</span> :
												<?php if(!empty($section_form_details[3]['old_application_docs'])){ ?>
													<a target="blank" id="old_application_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[3]['old_application_docs']); ?>" >Preview</a>
												<?php } ?>
											</label>

											<div class="custom-file col-sm-8">
												<input type="file" name="old_application_docs" class="form-control" id="old_application_docs", multiple='multiple'>
												<span id="error_old_application_docs" class="error invalid-feedback"></span>
												<span id="error_size_old_application_docs" class="error invalid-feedback"></span>
												<span id="error_type_old_application_docs" class="error invalid-feedback"></span>
											</div>
										</div>
									<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
								</div>
							</div>
						</div>
					</div>

					<div class="card-header"><h3 class="card-title">Director/Partner/Proprietor/Owner Details</h3></div>
					<div class="form-horizontal">
						<div class="card-body p-0 m-4 border rounded">
							<div class="row">
								<div class="col-sm-12">
									<?php echo $this->element('old_applications_elements/old_app_directors_details_table_view'); ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>
<input type="hidden" name="oldapplication" id="oldapplication" value="<?php echo $oldapplication; ?>">
<input type="hidden" name="final_submit_status" value="<?php echo $final_submit_status; ?>">

<?php echo $this->Html->script('element/application_forms/new/laboratory/lab_firm_profile'); ?>
