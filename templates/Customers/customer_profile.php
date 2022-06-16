
<?php
	$_SESSION['randSalt'] = Rand();
	$salt_server = $_SESSION['randSalt'];
	echo $this->element('get_captcha_random_code');
	$captchacode = $_SESSION['code'];
?>

<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-6"><label class="badge badge-info">Profile</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'customers', 'action'=>'primary_home'));?></a></li>
						<li class="breadcrumb-item active">Profile</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 alert alert-primary dnone" id="aadhar_update_msg">
					<i class="fa fa-info float-right"></i>Note: You Can Update Aadhar & Mobile details, Please Confirm Changes before Update
				</div>

				<div class="col-md-12 mb-1"><h5 class="middle"><span><i class="fas fa-user-alt"></i></span> Customer Profile</h5></div>
				<div class="col-md-12">
					<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'add_firm_form', 'novalidate'=>'novalidate')); ?>
						<div class="card card-primary">
							<div class="card-header"><h3 class="card-title">Name</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div role="form">
											<div class="row">
												<div class="col-sm-4">
													<div class="form-group">
														<label>First Name <span class="cRed">*</span></label>
														<?php echo $this->Form->control('f_name', array('type'=>'text', 'escape'=>false, 'id'=>'f_name', 'value'=>$customer_data['f_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter your first name', 'required'=>true)); ?>
														<span id="error_f_name" class="error invalid-feedback"></span>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label>Middle Name <span class="cRed">*</span></label>
														<?php echo $this->Form->control('m_name', array('type'=>'text', 'escape'=>false, 'id'=>'m_name', 'value'=>$customer_data['m_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter your middle name')); ?>
														<span id="error_m_name" class="error invalid-feedback"></span>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<label>Last Name <span class="cRed">*</span></label>
														<?php echo $this->Form->control('l_name', array('type'=>'text', 'escape'=>false, 'id'=>'l_name', 'value'=>$customer_data['l_name'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter your last name')); ?>
														<span id="error_l_name" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="card-header sub_card_header_prof"><h3 class="card-title"><i class="fa fa-address-card"></i> Address</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
														<div class="col-sm-9">
															<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'escape'=>false, 'value'=>$customer_data['street_address'], 'id'=>'street_address', 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter your street address')); ?>
															<span id="error_street_address" class="error invalid-feedback"></span>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputPassword3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
														<div class="col-sm-9">
															<?php echo $this->Form->control('state', array('type'=>'select', 'id'=>'state', 'options'=>$states, 'value'=>$selected_states_value, 'label'=>false,'class'=>'form-control getState')); ?>
															<span id="error_state" class="error invalid-feedback"></span>
														</div>
													</div>
													<div class="form-group row">
														<label for="inputPassword3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
														<div class="col-sm-9">
															<?php echo $this->Form->control('district', array('type'=>'select', 'id'=>'district', 'value'=>$selected_districts_value, 'label'=>false, 'class'=>'form-control')); ?>
															<span id="error_district" class="error invalid-feedback"></span>
														</div>
													</div>
													<div class="form-group row">
														<label for="inputPassword3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
														<div class="col-sm-9">
															<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'id'=>'postal_code', 'value'=>$customer_data['postal_code'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter your postal/zip code')); ?>
															<span id="error_postal_code" class="error invalid-feedback"></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="card-header sub_card_header_prof"><h3 class="card-title"><i class="fa fa-envelope"></i> Email Address</h3></div>
										<div class="form-horizontal">
											<div class="card-body">
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group row">
															<label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
															<div class="col-sm-9">
																<?php echo $this->Form->control('email', array('label'=>false, 'escape'=>false, 'id'=>'email', 'value'=>$customer_data['email'], 'class'=>'form-control input-field', 'placeholder'=>'Please enter your email id')); //for email encoding ?>
																<span id="error_email" class="error invalid-feedback"></span>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="card-header sub_card_header_prof"><h3 class="card-title"><i class="fa fa-phone"></i> Contact No.</h3></div>
											<div class="form-horizontal">
												<div class="card-body">
													<div class="row">
														<div class="col-sm-6">
															<div class="form-group row">
																<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
																<div class="col-sm-9">
																	<?php echo $this->Form->control('mobile', array('type'=>'tel', 'escape'=>false, 'id'=>'mobile', 'value'=>$customer_data['mobile'], 'label'=>false, 'class'=>'form-control input-field', 'readonly'=>true)); ?>
																	<span id="error_mobile_no" class="error invalid-feedback"></span>
																</div>
															</div>
														</div>
														<div class="col-sm-6">
															<div class="form-group row">
																<label for="inputPassword3" class="col-sm-3 col-form-label">Landline No. </label>
																<div class="col-sm-9">
																	<?php echo $this->Form->control('landline', array('type'=>'tel', 'escape'=>false, 'id'=>'landline', 'value'=>base64_decode($customer_data['landline']), 'label'=>false, 'class'=>'form-control input-field')); ?>
																	<span id="error_landline" class="error invalid-feedback"></span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="card-header sub_card_header_prof"><h3 class="card-title"><i class="fa fa-id-card"></i> Photo Id</h3></div>
												<div class="form-horizontal">
													<div class="card-body">
														<div class="row">
															<div class="col-sm-6">
																<div class="form-group row">
																	<label for="inputEmail3" class="col-sm-3 col-form-label">Select from list <span class="cRed">*</span></label>
																	<div class="col-sm-9">
																		<?php echo $this->Form->control('document', array('type'=>'select', 'id'=>'document', 'value'=>$selected_document_lists_value, 'options'=>$document_lists, 'label'=>false, 'class'=>'form-control')); ?>
																		<span id="error_document" class="error invalid-feedback"></span>
																	</div>
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group row">
																	<label for="inputPassword3" class="col-sm-3 col-form-label">Photo ID No. </label>
																	<div class="col-sm-9">
																		<?php echo $this->Form->control('photo_id_no', array('type'=>'text', 'escape'=>false, 'id'=>'photo_id_no', 'class'=>'input-field', 'value'=>base64_decode($customer_data['photo_id_no']), 'label'=>false, 'class'=>'form-control')); ?>
																		<span id="error_photo_id_no" class="error invalid-feedback"></span>
																	</div>
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group row">
																	<label for="inputEmail3" class="col-sm-3 col-form-label">Photo Id File
																	<?php if (!empty($customer_data['file'])) { ?><a  target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$customer_data['file']); ?>">: Preview</a><?php } ?>
																	</label>
																	<div class="custom-file col-sm-6">
																		<input type="file" class="custom-file-input" id="upload_file" name="file" multiple='multiple'>
																		<label class="custom-file-label" for="customFile">Choose file</label>
																		<span id="error_upload_file" class="error invalid-feedback"></span>
																		<span id="error_size_upload_file" class="error invalid-feedback"></span>
																		<span id="error_type_upload_file" class="error invalid-feedback"></span>
																	</div>
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group">
																	<label for="field3"><span>Profile Picture
																	<?php if(!empty($customer_data['profile_pic'])){ ?><a  target="_blank" href="<?php echo str_replace("D:/xampp/htdocs","",$customer_data['profile_pic']); ?>">: Preview</a><?php } ?></span>
																	</label>
																	<div class="custom-file">
																		<input type="file" class="custom-file-input" id="profile_pic" name="profile_pic" multiple='multiple'>
																		<label class="custom-file-label" for="customFile">Choose file</label>
																		<span id="error_profile_pic" class="error invalid-feedback"></span>
																		<span id="error_size_profile_pic" class="error invalid-feedback"></span>
																		<span id="error_type_profile_pic" class="error invalid-feedback"></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="card-footer cardFooterBackground">
													<?php echo $this->form->submit('Update', array('name'=>'update', 'label'=>false,'class'=>'btn btn-success float-left updateButtonProfile')); ?>
													<?php echo $this->form->submit('Back', array('name'=>'back', 'label'=>false, 'class'=>'btn btn-secondary float-right')); ?>
												</div>
											</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</section>
</div>


<?php echo $this->Form->control('return_error_msg', array('type'=>'hidden', 'id'=>'return_error_msg', 'value'=>$return_error_msg)); ?>				

	<?php if($aadhar_change_status == 'in_progress'){ ?>
		<?php echo $this->Html->script('customers/customer_profile/in_process') ?>
	<?php } ?>

	<?php echo $this->Html->script('customers/customer_profile/customer_profile_extracted'); ?>
