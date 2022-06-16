
	<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

	<section class="content form-middle form_outer_class" id="form_outer_main">
		<div class="container-fluid">
			<h5 class="mt-1 mb-2">Printing Press Profile</h5>
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div class="card-header"><h3 class="card-title">Firm Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name', 'escape'=>false, 'value'=>$firm_details['firm_name'], 'class'=>'form-control input-field', 'label'=>false)); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('email', array('type'=>'email', 'id'=>'email', 'escape'=>false, 'value'=>base64_decode($firm_details['email']), 'class'=>'form-control input-field', 'label'=>false)); //for email encoding ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'id'=>'street_address', 'escape'=>false, 'value'=>$firm_details['street_address'], 'class'=>'form-control input-field', 'label'=>false)); ?>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('state', array('type'=>'text', 'id'=>'state', 'value'=>$state_list[$firm_details['state']], 'label'=>false, 'onchange'=>'get_district()', 'class'=>'form-control')); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('district', array('type'=>'text', 'id'=>'district', 'value'=>$distict_list[$firm_details['district']], 'label'=>false, 'class'=>'form-control')); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'class'=>'form-control input-field', 'label'=>false)); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('mobile', array('type'=>'tel', 'id'=>'mobile', 'escape'=>false, 'value'=>base64_decode($firm_details['mobile_no']), 'class'=>'form-control input-field', 'label'=>false )); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Phone No. <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('fax', array('type'=>'tel', 'id'=>'fax', 'escape'=>false, 'value'=>$firm_details['fax_no'], 'class'=>'form-control input-field', 'label'=>false )); ?>
											</div>
										</div>
										
										<!-- Element call to provide option to update/change commodities -->
										<?php echo $this->element('/application_forms/update_commodity_popup'); ?>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header mt-2"><h3 class="card-title">Firm Status</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Business Type <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('business_type', array('type'=>'select', 'id'=>'business_type', 'value'=>$section_form_details[0]['business_type'], 'options'=>$business_type, 'label'=>false, 'class'=>'form-control')); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Name Of Proprietor/Partners <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('owner_name', array('type'=>'text', 'id'=>'owner_name', 'value'=>$section_form_details[0]['owner_name'], 'label'=>false, 'placeholder'=>'Enter Name of Proprietor/Partners', 'class'=>'form-control')); ?>
											<span id="error_owner_name" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div id="export_unit" class="col-sm-6">
										<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Attach Copy of partnership deed/ Article of Memorandum/Etc</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File : <span class="cRed">*</span>
													<?php if (!empty($section_form_details[0]['business_type_docs'])) { ?>
													<a target="blank" id="business_type_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['business_type_docs']); ?>" id="business_type_docs_path">Preview</a>
													<?php } ?>

													<div id="fiel_privew_disable">
													<?php //echo "No Document Provided" ; ?>
													</div>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="business_type_docs" class="form-control" id="business_type_docs" multiple='multiple'>
													<span id="error_business_type_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
													<span id="error_size_business_type_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
													<span id="error_type_business_type_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
												</div>
											</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Firm In Business Since</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Period of business (Years) <span class="cRed">*</span></label>
											<div class="col-sm-5">
												<?php echo $this->Form->control('business_years', array('type'=>'select', 'id'=>'business_years', 'value'=>$section_form_details[0]['business_years'], 'options'=>$all_printing_business_year, 'empty'=>'select', 'label'=>false, 'class'=>'form-control')); ?>
												<span id="error_business_years" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-6 col-form-label">Is Declaration in prescribed proforma attached(form B2) ?</label>
											<div class="col-sm-2">
												<?php
													$proforma = $section_form_details[0]['affidavit_proforma_3_attached'];
													if ($proforma == 'yes') {
														$checked_yes = 'checked';
														$checked_no = '';
													} elseif ($proforma == 'no') {
														$checked_yes = '';
														$checked_no = 'checked';
													} else {
														$checked_yes = '';
														$checked_no = '';
													}
												?>
												
												<div class="icheck-success d-inline">
													<input type="radio" name="affidavit_proforma_3_attached" id="affidavit_proforma_3_attached-yes" value="yes" <?php echo $checked_yes; ?>>
													<label for="affidavit_proforma_3_attached-yes">Yes</label>
												</div>
												<div class="icheck-success d-inline">
													<input type="radio" name="affidavit_proforma_3_attached" id="affidavit_proforma_3_attached-no" value="no" <?php echo $checked_no; ?>>
													<label for="affidavit_proforma_3_attached-no">No</label>
												</div>
												<span id="error_affidavit_proforma_3_attached" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>

									<div class="col-sm-12" id="is_declaration_attached">
										<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Attach Declaration in prescribel Proforma</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File : <span class="cRed">*</span>
													<?php if (!empty($section_form_details[0]['affidavit_proforma_3_attached_docs'])) { ?>
														<a target="blank" id="affidavit_proforma_3_attached_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['affidavit_proforma_3_attached_docs']); ?>" >Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-5">
													<input type="file" name="affidavit_proforma_3_attached_docs" class="form-control" id="affidavit_proforma_3_attached_docs"  multiple='multiple'>
													<span id="error_affidavit_proforma_3_attached_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
													<span id="error_size_affidavit_proforma_3_attached_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
													<span id="error_type_affidavit_proforma_3_attached_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
												</div>
											</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

					<?php if ($oldapplication == 'yes') { ?>
							<!--This fields is used to taken all old certification documents from applicant Done By pravin 29-09-2017 -->
							<div class="card-header"><h3 class="card-title">Old Certification Details</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-6">
											<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Scan and attach old certification documents</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File : <span class="cRed">*</span>
														<?php if (!empty($section_form_details[2]['old_certificate_pdf'])) { ?>
															<a target="blank" id="old_certification_pdf_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[2]['old_certificate_pdf']); ?>" >Preview</a>
														<?php } ?>
													</label>
													<div class="custom-file col-sm-9">
														<input type="file" name="old_certification_pdf" class="form-control" id="old_certification_pdf" multiple='multiple'>
														<span id="error_old_certification_pdf" class="error invalid-feedback"></span>
													</div>
												</div>
											<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
										</div>
										<div class="col-sm-6">
											<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Scan and attach all old application documents as single pdf file</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File : <span class="cRed">*</span>
														<?php if (!empty($section_form_details[2]['old_application_docs'])) { ?>
															<a target="blank" id="old_application_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[2]['old_application_docs']); ?>" >Preview</a>
														<?php } ?>
													</label>

												<div class="custom-file col-sm-9">
													<input type="file" name="old_application_docs" class="form-control" id="old_application_docs" multiple='multiple'>
													<span id="error_old_application_docs" class="error invalid-feedback"></span>
													<span id="error_size_old_application_docs" class="error invalid-feedback"></span>
													<span id="error_type_old_application_docs" class="error invalid-feedback"></span>
													</div>
												</div>
											<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
										</div>
									</div>
								</div>
							</div>

							<div class="card-header"><h3 class="card-title">Director/Partner/Proprietor/Owner Details</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 m-4 border rounded">
									<div class="col-sm-12">
									<!-- call table view form element with ajax call -->
									<?php echo $this->element('old_applications_elements/old_app_directors_details_table_view'); ?>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	
<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
<?php echo $this->Html->script('element/application_forms/new/printing/printing_profile'); ?>		
