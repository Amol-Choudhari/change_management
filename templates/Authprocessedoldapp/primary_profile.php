<?php ?>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<label class="badge badge-primary">Primary Profile</label>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Old Application Entry', array('controller' => 'authprocessedoldapp', 'action'=>'home'));?></li>
							<li class="breadcrumb-item active">Primary Profile</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'add_firm_form')); ?>
							<div class="card card-success">

								<div class="card-header"><h3 class="card-title"><i class="far fa-address-book"></i> Profile Name</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<p id="aadhar_update_msg" class="cNavyD">Note: You Can Update Aadhar & Mobile details, Please Confirm Changes before Update</p>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="firstname" class="col-form-label"><span>First Name <span class="cRed">*</span></span></label>
													<?php echo $this->Form->control('f_name', array('type'=>'text', 'escape'=>false, 'id'=>'f_name', 'class'=>'input-field form-control','value'=>$customer_data['f_name'], 'label'=>false, 'placeholder'=>'Please enter your first name')); ?>
													<span id="error_f_name" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="middlename" class="col-form-label"><span>Middle Name <span class=""></span></span></label>
													<?php echo $this->Form->control('m_name', array('type'=>'text', 'escape'=>false, 'id'=>'m_name', 'class'=>'input-field form-control','value'=>$customer_data['m_name'], 'label'=>false, 'placeholder'=>'Please enter your middle name')); ?>
													<span id="error_m_name" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="lastname" class="col-form-label"><span>Last Name <span class="cRed">*</span></span></label>
													<?php echo $this->Form->control('l_name', array('type'=>'text', 'escape'=>false, 'id'=>'l_name', 'value'=>$customer_data['l_name'],'class'=>'input-field form-control', 'label'=>false, 'placeholder'=>'Please enter your last name')); ?>
													<span id="error_l_name" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="card-header"><h3 class="card-title"><i class="far fa-address-card"></i> Address</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="address" class="col-sm-3 col-form-label"><span>Address <span class="cRed">*</span></span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'escape'=>false, 'id'=>'street_address','value'=>$customer_data['street_address'],'class'=>'input-field form-control', 'label'=>false, 'placeholder'=>'Please enter your street address')); ?>
														<span id="error_street_address" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="state" class="col-sm-4 col-form-label">State/Region <span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('state', array('type'=>'select', 'id'=>'state', 'empty'=>'Select State','value'=>$selected_states_value,'options'=>$states,'label'=>false,'class'=>'form-control onchangeGetDistrict')); ?>
														<span id="error_state" class="error invalid-feedback"></span>
													</div>
												</div>
												<div class="form-group row">
													<label for="district" class="col-sm-4 col-form-label">District <span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('district', array('type'=>'select', 'id'=>'district', 'value'=>$selected_districts_value, 'label'=>false,'class'=>'form-control')); ?>
														<span id="error_district" class="error invalid-feedback"></span>
													</div>
												</div>
												<div class="form-group row">
													<label for="pincode" class="col-sm-4 col-form-label">Pin Code <span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'id'=>'postal_code', 'class'=>'input-field form-control', 'value'=>$customer_data['postal_code'],'label'=>false, 'placeholder'=>'Please enter your postal/zip code')); ?>
														<span id="error_postal_code" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="card-header"><h3 class="card-title"><i class="fas fa-id-card-alt"></i> Contact</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-4">
												<div class="form-group">
													<label for="emailid" class="col-sm-4 col-form-label">Email Id <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('email', array('label'=>false, 'escape'=>false, 'id'=>'email', 'value'=>$customer_data['email'], 'class'=>'input-field form-control', 'placeholder'=>'Please enter your email id')); ?>
														<span id="error_email" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="mobileno" class="col-sm-5 col-form-label">Mobile No. <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('mobile', array('type'=>'tel', 'escape'=>false, 'id'=>'mobile', 'value'=>$customer_data['mobile'], 'label'=>false, 'class'=>'input-field form-control', 'readonly'=>true)); ?>
														<span id="error_mobile_no" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="landlineno" class="col-sm-5 col-form-label">Landline No.</label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('landline', array('type'=>'tel', 'escape'=>false, 'id'=>'landline', 'value'=>base64_decode($customer_data['landline']), 'label'=>false, 'class'=>'input-field form-control')); ?>
														<span id="error_landline" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="card-header"><h3 class="card-title"><i class="far fa-folder-open"></i> Documents</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="photoid" class="col-sm-6 col-form-label">Photo ID (Select any one) <span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('document', array('type'=>'select', 'id'=>'document', 'value'=>$selected_document_lists_value, 'options'=>$document_lists, 'label'=>false,'class'=>'form-control')); ?>
														<span id="error_document" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<div class="custom-file col-sm-8">
														<label class="float-left col-form-label">Attached File : <a  target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$customer_data['file']); ?>">Preview</a></label>
														<?php echo $this->Form->control('file',array('type'=>'file', 'id'=>'upload_file','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
														<div id="error_upload_file"></div>
														<div id="error_size_upload_file"></div>
														<div id="error_type_upload_file"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer">
										<?php echo $this->Form->submit('Update', array('name'=>'update', 'label'=>false,'class'=>'btn btn-success auth_call float-left')); ?>
										<?php echo $this->Html->link('Back', array('controller' => 'authprocessedoldapp', 'action'=>'home'),array('class'=>'btn btn-secondary float-right'));?>
									</div>
								</div>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</section>
	</div>

<input type="hidden" value="<?php echo $return_error_msg; ?>" id="return_error_msg"/>

<?php echo $this->Html->script('authprocessedoldapp/primary_profile/primary_profile'); ?>			
<?php if ($aadhar_change_status == 'in_progress') { ?>
	<?php echo $this->Html->script('authprocessedoldapp/primary_profile/aadhar_validation'); ?>
<?php } ?>
