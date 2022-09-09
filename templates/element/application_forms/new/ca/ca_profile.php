
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
	
	<section class="content form-middle form_outer_class" id="form_outer_main">
		<div class="container-fluid">
	  		<h5 class="mt-1 mb-2">Firm Profile</h5>
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div class="card-header"><h3 class="card-title">Firm Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body marginB10">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name', 'escape'=>false, 'value'=>$firm_details['firm_name'], 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('firm_email_id', array('type'=>'text', 'id'=>'firm_email_id', 'escape'=>false, 'value'=>base64_decode($firm_details['email']), 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); //for email encoding ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'id'=>'street_address', 'escape'=>false, 'value'=>$firm_details['street_address'], 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('state', array('type'=>'text', 'id'=>'state', 'value'=>$state_list[$firm_details['state']], 'disabled'=>true, 'label'=>false,'class'=>'form-control')); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('district', array('type'=>'text', 'id'=>'district', 'value'=>$distict_list[$firm_details['district']], 'disabled'=>true, 'label'=>false, 'class'=>'form-control')); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('firm_mobile_no', array('type'=>'text', 'id'=>'firm_mobile_no', 'escape'=>false, 'value'=>base64_decode($firm_details['mobile_no']), 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Phone No.</label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('firm_fax_no', array('type'=>'text', 'id'=>'firm_fax_no', 'escape'=>false, 'value'=>base64_decode($firm_details['fax_no']), 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
											</div>
										</div>

										<!-- Element call to provide option to update/change commodities -->
										<?php echo $this->element('/application_forms/update_commodity_popup'); ?>
									</div>
								</div>
							</div>
						</div>

					<?php if ($ca_bevo_applicant == 'no') { ?>

						<div class="card-header"><h3 class="card-title">Registration/ License No.</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Registration/ License No. issued under the FSSAI Act, 2006 in case of food commodities</p>
										<?php /* Remove the label of "yes" radio button option and Remove the "no" radio buttion option Done By pravin 02-02-2018 */
											$options=array('yes'=>''/*,'no'=>'No'*/);
											$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['have_reg_no'], 'id'=>'reg_lic', 'label'=>true );
											echo $this->Form->radio('have_reg_no',$options,$attributes); ?>
										<span id="error_reg_lic" class="error invalid-feedback" ></span>
									</div>

									<div class="col-sm-12 d-inline-flex" id="hide_reg_lic">
										<div class="col-sm-6 d-inline-block">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Give registration license No. <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('fssai_reg_no', array('type'=>'text', 'escape'=>false, 'id'=>'fssai_reg_no', 'value'=>$section_form_details[0]['fssai_reg_no'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter reg. licence no.')); ?>
													<span id="error_fssai_reg_no" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-6 d-inline-block">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
													<?php if(!empty($section_form_details[0]['fssai_reg_docs'])){?>
														<a id="fssai_reg_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['fssai_reg_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="fssai_reg_docs" class="form-control" id="fssai_reg_docs", multiple='multiple'>
													<span id="error_fssai_reg_docs"      class="error invalid-feedback"></span>
													<span id="error_size_fssai_reg_docs" class="error invalid-feedback"></span>
													<span id="error_type_fssai_reg_docs" class="error invalid-feedback"></span>
												</div>
											</div>
											<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										</div>
									</div>
								</div>
							</div>
						</div>

					<?php } elseif ($ca_bevo_applicant == 'yes') { ?>

						<div class="card-header sub-card-header-firm"><h3 class="card-title">Oil Mills Details(Constituent Oils)</h3></div>
						<div class="form-horizontal">
							<div class="card-body mb-2">
								<div class="row">
									<div class="col-sm-12">
										<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Name and address of oil mills from where the constituent oils are proposed to be procured.</p>
									</div>
									<div class="col-sm-12">
										<!-- call table view form element with ajax call -->
										<?php echo $this->element('ca_other_tables_elements/const_oils_details_table_view'); ?>
									</div>
								</div>
							</div>
						</div>
						
						<div class="card-header"><h3 class="card-title">BEVO Authorisation</h3></div>
						<div class="form-horizontal">
							<div class="card-body p-0 m-4 rounded">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-9 col-form-label">Whether authorized to manufacture and sell Blended Edible Vegetable Oils by the Department of Civil Supplies ?</label>
											<div class="col-sm-2">
												<?php
													$bevo_auth = $section_form_details[0]['authorised_for_bevo'];
													if ($bevo_auth == 'yes') {
														$bevo_auth_yes = 'checked';
														$bevo_auth_no = '';
													} else if ($bevo_auth == 'no') {
														$bevo_auth_yes = '';
														$bevo_auth_no = 'checked';
													} else {
														$bevo_auth_yes = '';
														$bevo_auth_no = '';
													}
												?>
												<div class="icheck-success d-inline">
													<input type="radio" name="authorised_for_bevo" id="authorised_for_bevo-yes" value="yes" <?php echo $bevo_auth_yes; ?>>
													<label for="authorised_for_bevo-yes">Yes</label>
												</div>
												<div class="icheck-success d-inline ml-3">
													<input type="radio" name="authorised_for_bevo" id="authorised_for_bevo-no" value="no" <?php echo $bevo_auth_no; ?>>
													<label for="authorised_for_bevo-no">No</label>
												</div>
												<span id="error_is_particulars_furnished" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 11/05/2017)-->
											</div>
										</div>
									</div>
									<div class="col-sm-6" id="hide_bevo_authorised">
										<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> A copy of the authority be enclosed</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
													<?php if (!empty($section_form_details[0]['authorised_bevo_docs'])) { ?>

														<a id="authorised_bevo_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['authorised_bevo_docs']); ?>">Preview</a>

													<?php } ?>
												</label>

												<div class="custom-file col-sm-9">
													<input type="file" name="authorised_bevo_docs" class="form-control" id="authorised_bevo_docs", multiple='multiple'>
													<span id="error_authorised_bevo_docs" 	   class="error invalid-feedback"></span>
													<span id="error_size_authorised_bevo_docs" class="error invalid-feedback"></span>
													<span id="error_type_authorised_bevo_docs" class="error invalid-feedback"></span>
												</div>
											</div>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<!-- Add new Upload Field for Affidavit/Undertaking From Oil Manufacturer by Pravin 22/07/2017 -->

						<div class="card-header"><h3 class="card-title">Affidavit/Undertaking From Oil Manufacturer</h3></div>
						<div class="form-horizontal">
							<div class="card-body p-0 m-4 rounded">
								<div class="row">
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Upload the affidavit/undertaking from oil manufacturers to supply constituent oils</p></div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
												<?php if (!empty($section_form_details[0]['oil_manu_affidavit_docs'])) { ?>
													<a id="oil_manu_affidavit_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['oil_manu_affidavit_docs']); ?>">Preview</a>
												<?php } ?>
											</label>

											<div class="custom-file col-sm-9">
												<input type="file" name="oil_manu_affidavit_docs" class="form-control" id="oil_manu_affidavit_docs", multiple='multiple'>
												<span id="error_oil_manu_affidavit_docs"      class="error invalid-feedback"></span>
												<span id="error_size_oil_manu_affidavit_docs" class="error invalid-feedback"></span>
												<span id="error_type_oil_manu_affidavit_docs" class="error invalid-feedback"></span>
											</div>
										</div>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<!-- Add new Upload Field for FSSAI Registration Details by Pravin 22/07/2017 -->
						<div class="card-header"><h3 class="card-title">FSSAI Registration Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body p-0 m-4 rounded">
								<div class="row">
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Upload Registration/License document issued under the FSSAI Act, 2006</p></div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
												<?php if (!empty($section_form_details[0]['fssai_reg_docs'])) { ?>
													<a id="fssai_reg_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['fssai_reg_docs']); ?>">Preview</a>
												<?php } ?>
											</label>

											<div class="custom-file col-sm-9">
												<input type="file" name="fssai_reg_docs" class="form-control" id="fssai_reg_docs", multiple='multiple'>
												<span id="error_fssai_reg_docs"      class="error invalid-feedback"></span>
												<span id="error_size_fssai_reg_docs" class="error invalid-feedback"></span>
												<span id="error_type_fssai_reg_docs" class="error invalid-feedback"></span>
											</div>
										</div>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<!-- Add new Upload Field for VOP Registration Details by Pravin 22/07/2017 -->
						<div class="card-header"><h3 class="card-title">VOP Registration Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body p-0 m-4 rounded">
								<div class="row">
									<div class="col-sm-12">
										<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Upload Registration/License document issued under the VOP Act, 2006</p>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
												<?php if (!empty($section_form_details[0]['vopa_certificate_docs'])) { ?>
													<a id="vopa_certificate_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['vopa_certificate_docs']); ?>">Preview</a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" name="vopa_certificate_docs" class="form-control" id="vopa_certificate_docs", multiple='multiple'>
												<span id="error_vopa_certificate_docs" class="error invalid-feedback"></span>
												<span id="error_size_vopa_certificate_docs" class="error invalid-feedback"></span>
												<span id="error_type_vopa_certificate_docs" class="error invalid-feedback"></span>
											</div>
										</div>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Quantity Per Month</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded text-sm">Approximate quantity of Blended Edible Vegetable Oils proposed to be graded per month.</p></div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Quantity (in MT)</label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('quantity_per_month', array('type'=>'text', 'escape'=>false, 'id'=>'quantity_per_month', 'value'=>$section_form_details[0]['quantity_per_month'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter Quantity here')); ?>
												<span id="error_quantity_per_month" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Bank References</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Give Details</label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('bank_references', array('type'=>'text', 'id'=>'bank_references', 'escape'=>false, 'value'=>$section_form_details[0]['bank_references'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Bank References Details')); ?>
												<span id="error_bank_references" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<p class="bg-info pl-2 p-1 rounded text-sm">Upload document related to bank references</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
													<?php if (!empty($section_form_details[0]['bank_references_docs'])) { ?>
														<a id="bank_references_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['bank_references_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="bank_references_docs" class="form-control" id="bank_references_docs", multiple='multiple'>
													<span id="error_bank_references_docs" class="error invalid-feedback"></span>
													<span id="error_size_bank_references_docs" class="error invalid-feedback"></span>
													<span id="error_type_bank_references_docs" class="error invalid-feedback"></span>
												</div>
											</div>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

						<div class="card-header"><h3 class="card-title">Firm Status</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Please select the document name which is to be uploaded. Have a scanned copy of it ready.</p></div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Business Type <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('business_type', array('type'=>'select', 'id'=>'business_type', 'value'=>$section_form_details[0]['business_type'], 'options'=>$business_type, 'label'=>false, 'class'=>'form-control')); ?>
												<span id="error_business_type" class="error invalid-feedback"></span>
											</div>
										</div>

										<?php if ($ca_bevo_applicant == 'no') { ?>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Period for which firm has been in business (Years) <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('business_years', array('type'=>'select', 'id'=>'business_years', 'value'=>$section_form_details[0]['business_years'], 'options'=>$all_ca_business_year, 'label'=>false, 'class'=>'form-control')); ?>
												<span id="error_business_years" class="error invalid-feedback"></span>
												</div>
											</div>
										<?php } ?>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
												<?php if (!empty($section_form_details[0]['business_type_docs'])) { ?>
													<a id="business_type_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['business_type_docs']); ?>">Preview</a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" name="business_type_docs" class="form-control" id="business_type_docs", multiple='multiple'>
												<span id="error_business_type_docs" class="error invalid-feedback"></span>
												<span id="error_size_business_type_docs" class="error invalid-feedback"></span>
												<span id="error_type_business_type_docs" class="error invalid-feedback"></span>
											</div>
										</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
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
										<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Scan and attach old certification documents</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
													<?php if (!empty($section_form_details[3]['old_certificate_pdf'])) { ?>
														<a target="blank" id="old_certification_pdf_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[3]['old_certificate_pdf']); ?>" >Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="old_certification_pdf" class="form-control" id="old_certification_pdf", multiple='multiple'>
													<span id="error_old_certification_pdf" class="error invalid-feedback"></span>
													<span id="error_size_old_certification_pdf" class="error invalid-feedback"></span>
													<span id="error_type_old_certification_pdf" class="error invalid-feedback"></span>
												</div>
											</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
									<div class="col-sm-6">
										<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Scan and attach all old application documents as single pdf file</p>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
												<?php if (!empty($section_form_details[3]['old_application_docs'])) { ?>
													<a target="blank" id="old_application_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[3]['old_application_docs']); ?>" >Preview</a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" name="old_application_docs" class="form-control" id="old_application_docs", multiple='multiple'>
												<span id="error_old_application_docs" class="error invalid-feedback"></span>
												<span id="error_size_old_application_docs" class="error invalid-feedback"></span>
												<span id="error_type_old_application_docs" class="error invalid-feedback"></span>
											</div>
										</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>
						<div class="card-header"><h3 class="card-title">Director/Partner/Proprietor/Owner Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
							<div class="">
								<div class="col-sm-12 border tank_table">
									<!-- call table view form element with ajax call -->
									<?php echo $this->element('old_applications_elements/old_app_directors_details_table_view'); ?>
								</div>
							</div>
							</div>
						</div>
					<?php } ?>


					<!-- Added new Upload Field for APEDA and IEC documents  by AKASH 01/09/2022 -->

					<?php if ($form_type == 'F') { ?>
						<div class="card-header"><h3 class="card-title">Other Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Please select the document name which is to be uploaded. Have a scanned copy of it ready.</p></div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label class="col-sm-3 col-form-label">Certificates of APEDA
												<?php if (!empty($customer_data['apeda_docs_value'])) { ?><a  target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['apeda_docs']); ?>">: Preview</a><?php } ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" class="custom-file-input" id="apeda_docs" name="apeda_docs" multiple='multiple'>
												<label class="custom-file-label">Choose file</label>
												<span id="error_apeda_docs" class="error invalid-feedback"></span>
												<span id="error_size_apeda_docs" class="error invalid-feedback"></span>
												<span id="error_type_apeda_docs" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Please provide the Importer-Exporter Code (IEC) that is required for every import/export business owner in India.</p></div>

									<div class="col-sm-12 d-inline-flex">
										<div class="col-sm-6 d-inline-block">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">IEC Code <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('iec_code', array('type'=>'text', 'escape'=>false, 'id'=>'iec_code', 'value'=>$section_form_details[0]['iec_code'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter IEC code')); ?>
													<span id="error_iec_code" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-6 d-inline-block">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
													<?php if(!empty($section_form_details[0]['iec_code_docs'])){?>
														<a id="iec_code_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['iec_code_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" class="custom-file-input" id="iec_code_docs" name="iec_code_docs" multiple='multiple'>
													<label class="custom-file-label">Choose file</label>
													<span id="error_iec_code_docs"      class="error invalid-feedback"></span>
													<span id="error_size_iec_code_docs" class="error invalid-feedback"></span>
													<span id="error_type_iec_code_docs" class="error invalid-feedback"></span>
												</div>
											</div>
											<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					<?php }?>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	
	<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
	<input type="hidden" id="ca_bevo_applicant_id" 	 value="<?php echo $ca_bevo_applicant; ?>">
	<input type="hidden" id="isOldApplication" 		 value="<?php echo $_SESSION['oldapplication']; ?>">
	<input type="hidden" id="form_type_id" 			 value="<?php echo $form_type; ?>">

	<?php echo $this->Html->script('element/application_forms/new/ca/ca_profile'); ?>
