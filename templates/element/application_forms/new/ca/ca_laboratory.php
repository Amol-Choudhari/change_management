<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

	<section class="content form-middle form_outer_class" id="form_outer_main">
		<div class="container-fluid">
			<h5 class="mt-1 mb-2">Laboratory Details</h5>
				<div class="row">
					<div class="col-md-12">
						<div class="card card-success">
						<?php if ($ca_bevo_applicant == 'no') { ?>

							<div class="card-header"><h3 class="card-title">Name</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 m-4 rounded">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Laboratory Name <span class="cRed">*</span></label>
												<div class="custom-file col-sm-8">
													<?php echo $this->form->control('laboratory_name', array('type'=>'text', 'id'=>'laboratory_name', 'escape'=>false, 'value'=>$section_form_details[0]['laboratory_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter laboratory name')); ?>
													<span id="error_laboratory_name" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card-header"><h3 class="card-title">Type of Laboratory</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 m-4 rounded">
									<div class="row">
										<div class="col-sm-12">
											<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Specify type of the laboratory through which Grading & Marking is proposed to be undertaken</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-2 col-form-label">Laboratory Type <span class="cRed">*</span></label>
												<div class="custom-file col-sm-5">
													<?php echo $this->form->control('laboratory_type', array('type'=>'select', 'id'=>'laboratory_type', 'options'=>$section_form_details[1], 'value'=>$section_form_details[0]['laboratory_type'], 'label'=>false, 'class'=>'form-control')); ?>
													<span id="error_laboratory_type" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>

										<!-- Add New File Upload Option For Chemist Details By Pravin 22-07-2017 -->
										<div class="col-sm-12 row" id="show_chemist_details">
											<div class="col-sm-6 d-inline-block">
												<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Upload Details of Approved Chemists</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
														<?php if(!empty($section_form_details[0]['chemist_detail_docs'])){?>
															<a id="chemist_detail_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['chemist_detail_docs']); ?>">Preview</a>
														<?php } ?>
													</label>
													<div class="custom-file col-sm-9">
														<input type="file" name="chemist_detail_docs" class="form-control" id="chemist_detail_docs", multiple='multiple'>
														<span id="error_chemist_detail_docs" class="error invalid-feedback"></span>
														<span id="error_type_chemist_detail_docs" class="error invalid-feedback"></span>
														<span id="error_size_chemist_detail_docs" class="error invalid-feedback"></span>
													</div>
												</div>
												<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
											</div>

											<div class="col-sm-6 d-inline-block">
												<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Upload Details of Instruments, Details of Glass Apparatus, Details of Chemicals</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
														<?php if(!empty($section_form_details[0]['lab_equipped_docs'])){?>
															<a id="lab_equipped_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['lab_equipped_docs']); ?>">Preview</a>
														<?php } ?>
													</label>
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

										<div class="col-sm-12" id="hide_consent_letter">
											<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Consent letter of the laboratory may be enclosed, Not required in case of own laboratory.</p>
													<div class="form-group row">
													<label for="inputEmail3" class="col-sm-2 col-form-label">Attach File: <span class="cRed">*</span>
														<?php if(!empty($section_form_details[0]['consent_letter_docs'])){?>
															<a id="consent_letter_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['consent_letter_docs']); ?>">Preview</a>
														<?php } ?>
													</label>
													<div class="custom-file col-sm-4">
														<input type="file" name="consent_letter_docs" class="form-control" id="consent_letter_docs", multiple='multiple', value="<?php echo $section_form_details[0]['consent_letter_docs']; ?>">
														<span id="error_consent_letter_docs" class="error invalid-feedback"></span>
														<span id="error_type_consent_letter_docs" class="error invalid-feedback"></span>
														<span id="error_size_consent_letter_docs" class="error invalid-feedback"></span>
													</div>
												</div>
											<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										</div>
									</div>
								</div>
							</div>

							<div class="card-header"><h3 class="card-title">Address</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 m-4 rounded">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Address <span class="cRed">*</span></label>
												<div class="custom-file col-sm-8 h-auto">
													<?php echo $this->form->control('street_address', array('type'=>'textarea', 'id'=>'street_address', 'escape'=>false, 'value'=>$section_form_details[0]['street_address'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter street address')); ?>
													<span id="error_street_address" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">State/Region <span class="cRed">*</span></label>
												<div class="custom-file col-sm-8">
													<?php echo $this->form->control('state', array('type'=>'select', 'id'=>'state', 'options'=>$state_list, 'value'=>$section_form_details[0]['state'], 'label'=>false, 'empty'=>"Select",'class'=>'form-control getState')); ?>
													<span id="error_state" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">District <span class="cRed">*</span></label>
												<div class="custom-file col-sm-8">
													<?php echo $this->form->control('district', array('type'=>'select', 'id'=>'district', 'options'=>$section_form_details[2], 'value'=>$section_form_details[0]['district'], 'label'=>false, 'class'=>'form-control')); ?>
													<span id="error_district" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Pin Code <span class="cRed">*</span></label>
												<div class="custom-file col-sm-8">
													<?php echo $this->form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$section_form_details[0]['postal_code'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter postal/zip code')); ?>
													<span id="error_postal_code" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="form-group row marginT25">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Email Id <span class="cRed">*</span></label>
												<div class="custom-file col-sm-8">
													<?php echo $this->form->control('lab_email_id', array('type'=>'text', 'id'=>'lab_email_id', 'escape'=>false, 'value'=>base64_decode($section_form_details[0]['lab_email_id']), 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter lab Email Id')); //for email encoding ?>
													<span id="error_lab_email_id" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="form-group row marginT25">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Mobile No. <span class="cRed">*</span></label>
												<div class="custom-file col-sm-8">
													<?php echo $this->form->control('lab_mobile_no', array('type'=>'text', 'id'=>'lab_mobile_no', 'escape'=>false, 'value'=>base64_decode($section_form_details[0]['lab_mobile_no']), 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter Mobile No.')); ?>
													<span id="error_lab_mobile_no" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="form-group row marginT25">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Phone No. <span class="cRed">*</span></label>
												<div class="custom-file col-sm-8">
													<?php echo $this->form->control('lab_fax_no', array('type'=>'text', 'id'=>'lab_fax_no', 'escape'=>false, 'value'=>base64_decode($section_form_details[0]['lab_fax_no']), 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter Phone No.')); ?>
													<span id="error_lab_fax_no" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

					    <?php } elseif ($ca_bevo_applicant == 'yes') { ?>

							<div class="card-header"><h3 class="card-title">Is Laboratory Fully Equiped?</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 m-4 rounded">
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-9 col-form-label text-sm">Whether the laboratory is fully equipped for analysis of constituent oils and Blended Edible Vegetable Oils?</label>
												<div class="col-sm-3">
												<?php
													$is_lab_equipped_radio = $section_form_details[0]['is_lab_equipped'];
													if ($is_lab_equipped_radio == 'yes') {
														$is_lab_equipped_radio_yes = 'checked';
														$is_lab_equipped_radio_no = '';
													} else if ($is_lab_equipped_radio == 'no') {
														$is_lab_equipped_radio_yes = '';
														$is_lab_equipped_radio_no = 'checked';
													} else {
														$is_lab_equipped_radio_yes = '';
														$is_lab_equipped_radio_no = '';
													}
												?>
													<div class="icheck-success d-inline">
														<input type="radio" name="is_lab_equipped" id="is_lab_equipped-yes" value="yes" <?php echo $is_lab_equipped_radio_yes; ?>>
														<label for="is_lab_equipped-yes">Yes</label>
													</div>
													<div class="icheck-success d-inline">
														<input type="radio" name="is_lab_equipped" id="is_lab_equipped-no" value="no" <?php echo $is_lab_equipped_radio_no; ?>>
														<label for="is_lab_equipped-no">No</label>
													</div>
													<span id="error_is_lab_equipped" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
										
										<div class="col-sm-6" id="hide_lab_equipped">
											<p class="bg-info pl-2 p-1 rounded text-sm">Upload Details of Instruments, Details of Glass Apparatus, Details of Chemicals</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
													<?php if(!empty($section_form_details[0]['lab_equipped_docs'])){?>
														<a id="lab_equipped_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['lab_equipped_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="lab_equipped_docs" class="custom-file-input" id="lab_equipped_docs" multiple='multiple'>
													<label class="custom-file-label" for="customFile">Choose file</label>
													<span id="error_lab_equipped_docs" class="error invalid-feedback"></span>
													<span id="error_type_lab_equipped_docs" class="error invalid-feedback"></span>
													<span id="error_size_lab_equipped_docs" class="error invalid-feedback"></span>
												</div>
											</div>
											<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										</div>
										
										<!-- Add New File Upload Option For Chemist Details By Pravin 22-07-2017 -->
										<div class="col-sm-6">
											<p class="bg-info pl-2 p-1 rounded text-sm">Upload Details of Approved Chemists</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
														<?php if(!empty($section_form_details[0]['lab_equipped_docs'])){?>
															<a id="chemist_detail_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['lab_equipped_docs']); ?>">Preview</a>
														<?php } ?>
													</label>
													<div class="custom-file col-sm-9">
														<input type="file" name="chemist_detail_docs" class="custom-file-input" id="chemist_detail_docs" multiple='multiple'>
														<label class="custom-file-label" for="customFile">Choose file</label>
														<span id="error_chemist_detail_docs" class="error invalid-feedback"></span>
														<span id="error_type_chemist_detail_docs" class="error invalid-feedback"></span>
														<span id="error_size_chemist_detail_docs" class="error invalid-feedback"></span>
													</div>
												</div>
											<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
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
	
	<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
	<input type="hidden" id="ca_bevo_applicant_id" value="<?php echo $ca_bevo_applicant; ?>">
	<input type="hidden" id="form_type_id" value="<?php echo $form_type; ?>">
	
	<?php echo $this->Html->script('element/application_forms/new/ca/ca_laboratory') ?>
