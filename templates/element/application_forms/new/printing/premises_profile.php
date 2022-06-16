
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

	<section class="content form-middle form_outer_class" id="form_outer_main">
		<div class="container-fluid">
			<h5 class="mt-1 mb-2">Printing Premises Profile</h5>
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div class="card-header"><h3 class="card-title">Address</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'escape'=>false, 'id'=>'street_address', 'value'=>$section_form_details[0]['street_address'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter street address')); ?>
												<span id="error_street_address" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('state', array('type'=>'select', 'id'=>'state', 'options'=>$state_list, 'value'=>$section_form_details[0]['state'], 'empty'=>'select', 'label'=>false,'class'=>'form-control')); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('district', array('type'=>'select', 'id'=>'district', 'options'=>$section_form_details[1], 'value'=>$section_form_details[0]['district'], 'empty'=>'select', 'label'=>false, 'class'=>'form-control')); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$section_form_details[0]['postal_code'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter postal/zip code')); ?>
												<span id="error_postal_code" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">GST No.</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Do you Have GST Certificate ?</label>
											<div class="col-sm-2">
												<?php
													$gst_cert = $section_form_details[0]['have_vat_cst_no'];
													if ($gst_cert == 'yes') {
														$gst_cert_yes = 'checked';
														$gst_cert_no = '';
													} elseif ($gst_cert == 'no') {
														$gst_cert_yes = '';
														$gst_cert_no = 'checked';
													} else {
														$gst_cert_yes = '';
														$gst_cert_no = '';
													}
												?>
												
												<div class="icheck-success d-inline">
													<input type="radio" name="have_vat_cst_no" id="vat_cst-yes" value="yes" <?php echo $gst_cert_yes; ?>>
													<label for="vat_cst-yes">Yes</label>
												</div>
												<div class="icheck-success d-inline">
													<input type="radio" name="have_vat_cst_no" id="vat_cst-no" value="no" <?php echo $gst_cert_no; ?>>
													<label for="vat_cst-no">No</label>
												</div>
												<span id="error_vat_cst" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>

									<div class="col-sm-12" id="hide_vat_cst">
										<div class="col-sm-5 d-inline-block align-top">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Give GST NO.</label>
												<div class="col-sm-9">
													<?php echo $this->Form->control('gst_no', array('type'=>'text', 'id'=>'gst_no', 'escape'=>false, 'value'=>$section_form_details[0]['gst_no'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter GST NO'));?>
													<span id="error_gst_no" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-6 d-inline-block">
											<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Attach Copies of GST registration</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File : <span class="cRed">*</span>
														<?php if(!empty($section_form_details[0]['vat_cst_docs'])){ ?>
															<a target="blank" id="vat_cst_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['vat_cst_docs']); ?>">Preview</a>
														<?php } ?>
													</label>
													<div class="custom-file col-sm-9">
														<input type="file" name="vat_cst_docs" class="form-control" id="vat_cst_docs" multiple='multiple'>
														<span id="error_vat_cst_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
														<span id="error_size_vat_cst_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
														<span id="error_type_vat_cst_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
													</div>
												</div>
											<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Layout Plan</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-6 col-form-label">Is Layout Plan attached ?</label>
											<div class="col-sm-4">
												<?php
													$layout_lan = $section_form_details[0]['layout_plan_attached'];
													if($layout_lan == 'yes') {
														$layout_plan_yes = 'checked';
														$layout_plan_no = '';
													} elseif ($layout_lan == 'no') {
														$layout_plan_yes = '';
														$layout_plan_no = 'checked';
													} else {
														$layout_plan_yes = '';
														$layout_plan_no = '';
													}
												?>
												
												<div class="icheck-success d-inline">
													<input type="radio" name="layout_plan_attached" id="layout_plan_attached-yes" value="yes" <?php echo $layout_plan_yes; ?>>
													<label for="layout_plan_attached-yes">Yes</label>
												</div>
												<div class="icheck-success d-inline">
													<input type="radio" name="layout_plan_attached" id="layout_plan_attached-no" value="no" <?php echo $layout_plan_no; ?>>
													<label for="layout_plan_attached-no">No</label>
												</div>
												<span id="error_layout_plan_attached" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>

									<div class="col-sm-6" id="is_layout_plan_attached">
										<p class="lab_form_note bg-info pl-2 p-1 rounded">Attach Layout Plan</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File : <span class="cRed">*</span>
													<?php if(!empty($section_form_details[0]['layout_plan_docs'])){  ?>
														<a target="blank" id="layout_plan_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['layout_plan_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="layout_plan_docs" class="form-control" id="layout_plan_docs" multiple='multiple'>
													<span id="error_layout_plan_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
													<span id="error_size_layout_plan_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
													<span id="error_type_layout_plan_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
												</div>
											</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title"><i class="icon fa fa-user"></i> First Representative Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">First Name: <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('first_rep_f_name', array('type'=>'text', 'id'=>'first_rep_f_name', 'escape'=>false, 'value'=>$section_form_details[0]['first_rep_f_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter first name.')); ?>
												<span id="error_first_rep_f_name" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Middle Name:</label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('first_rep_m_name', array('type'=>'text', 'id'=>'first_rep_m_name', 'escape'=>false, 'value'=>$section_form_details[0]['first_rep_m_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter middle name')); ?>
												<span id="error_first_rep_m_name" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Last Name: <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('first_rep_l_name', array('type'=>'text', 'id'=>'first_rep_l_name', 'escape'=>false, 'value'=>$section_form_details[0]['first_rep_l_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter last name')); ?>
												<span id="error_first_rep_l_name" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No: <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('first_rep_mobile', array('type'=>'text', 'id'=>'first_rep_mobile', 'escape'=>false, 'value'=>base64_decode($section_form_details[0]['first_rep_mobile']), 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter mobile no.')); ?>
												<span id="error_first_rep_mobile" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach Signature: <span class="cRed">*</span>
												<?php  if(!empty($section_form_details[0]['first_rep_signature'])){ ?>
													<a target="blank" id="first_rep_signature_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['first_rep_signature']); ?>">Preview</a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" name="first_rep_signature" class="form-control" id="first_rep_signature" multiple='multiple'>
												<span id="error_first_rep_signature" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
												<span id="error_size_first_rep_signature" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
												<span id="error_type_first_rep_signature" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
											</div>
										</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title"><i class="icon fa fa-user"></i> Second Representative Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">First Name: <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('second_rep_f_name', array('type'=>'text', 'id'=>'second_rep_f_name', 'escape'=>false, 'value'=>$section_form_details[0]['second_rep_f_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter first name.')); ?>
												<span id="error_second_rep_f_name" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Middle Name:</label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('second_rep_m_name', array('type'=>'text', 'id'=>'second_rep_m_name',  'escape'=>false, 'value'=>$section_form_details[0]['second_rep_m_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter middle name')); ?>
												<span id="error_second_rep_m_name" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Last Name: <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('second_rep_l_name', array('type'=>'text', 'id'=>'second_rep_l_name',  'escape'=>false, 'value'=>$section_form_details[0]['second_rep_l_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter last name')); ?>
												<span id="error_second_rep_l_name" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No: <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('second_rep_mobile', array('type'=>'text', 'id'=>'second_rep_mobile',  'escape'=>false, 'value'=>base64_decode($section_form_details[0]['second_rep_mobile']), 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter mobile no.')); ?>
												<span id="error_second_rep_mobile" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach Signature: <span class="cRed">*</span>
												<?php if(!empty($section_form_details[0]['second_rep_signature'])){ ?>
													<a target="blank" id="second_rep_signature_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['second_rep_signature']); ?>">Preview</a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" name="second_rep_signature" class="form-control" id="second_rep_signature" multiple='multiple'>
												<span id="error_second_rep_signature" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
												<span id="error_size_second_rep_signature" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
												<span id="error_type_second_rep_signature" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 10/05/2017)-->
											</div>
										</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
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
<?php echo $this->Html->script('element/application_forms/new/printing/premises_profile'); ?>
