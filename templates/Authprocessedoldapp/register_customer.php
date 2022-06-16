	
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<label class="badge badge-primary">Backlog Data</label>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Old Application Entry', array('controller' => 'authprocessedoldapp', 'action'=>'home'));?></li>
							<li class="breadcrumb-item active">Primary Registration</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<?php echo $this->Form->create(null, array('type'=>'file', 'id'=>'reg_customer_form', 'enctype'=>'multipart/form-data')); ?>
							<div class="card-header"><h5 class="middle"><i class="fa fa-user-plus"></i> Primary Registration</h5></div>
							<div class="card card-info">
								<div class="card-header"><h5 class="card-title"><i class="far fa-address-book"></i> Name</h5></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-4">
												<div class="form-group">
													<label for="firstname" class="col-form-label"><span>First Name <span class="cRed">*</span></span></label>
													<?php echo $this->Form->control('f_name', array('type'=>'text', 'escape'=>false, 'id'=>'f_name', 'class'=>'input-field form-control', 'label'=>false, 'placeholder'=>'Please enter your first name')); ?>
													<span id="error_f_name" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="middlename" class="col-form-label"><span>Middle Name </span></label>
													<?php echo $this->Form->control('m_name', array('type'=>'text', 'escape'=>false, 'id'=>'m_name', 'class'=>'input-field form-control', 'label'=>false, 'placeholder'=>'Please enter your middle name')); ?>
													<span id="error_m_name" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="col-sm-4">
												<div class="form-group">
													<label for="lastname" class="col-form-label"><span>Last Name <span class="cRed">*</span></span></label>
													<?php echo $this->Form->control('l_name', array('type'=>'text', 'escape'=>false, 'id'=>'l_name', 'class'=>'input-field form-control', 'label'=>false, 'placeholder'=>'Please enter your last name')); ?>
													<span id="error_l_name" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
					
								<div class="card-header"><h5 class="card-title"><i class="far fa-address-card"></i> Address</h5></div>
								<div class="form-horizontal">
									<div class="card-body marginB16">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row marginT25">
													<label for="address" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'escape'=>false, 'id'=>'street_address', 'class'=>'input-field form-control', 'label'=>false, 'placeholder'=>'Please enter your street address')); ?>
														<span id="error_street_address" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row marginT25">
													<label for="state" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('state', array('type'=>'select', 'id'=>'state', 'empty'=>'Select State', 'options'=>$states,'label'=>false,'class'=>'form-control getState')); ?>
														<span id="error_state" class="error invalid-feedback"></span>
													</div>
												</div>
												<div class="form-group row marginT25">
													<label for="district" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('district', array('type'=>'select', 'id'=>'district', 'empty'=>'Select District', 'options'=>array(), 'label'=>false,'class'=>'form-control')); ?>
														<span id="error_district" class="error invalid-feedback"></span>
													</div>
												</div>
												<div class="form-group row marginT25">
													<label for="pincode" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'id'=>'postal_code', 'class'=>'input-field form-control', 'label'=>false, 'placeholder'=>'Please enter your postal/zip code')); ?>
														<span id="error_postal_code" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card-header"><h5 class="card-title"><i class="far fa-envelope"></i> Email</h5></div>
								<div class="form-horizontal">
									<div class="card-body marginB16">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row marginT25">
												<label for="emailid" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('email', array('label'=>false, 'id'=>'email', 'escape'=>false, 'class'=>'input-field form-control', 'placeholder'=>'Please enter your email id')); ?>
														<span id="error_email" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row marginT25">
												<label for="confirm email" class="col-sm-3 col-form-label">Confirm Email <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('confirm_email', array('label'=>false, 'id'=>'confirm_email', 'class'=>'input-field form-control', 'placeholder'=>'Please confirm your email id')); ?>
														<span id="error_confirm_email" class="error invalid-feedback"></span>
														<?php if(!empty($used_email_error_msg)){ ?>
														<span class="text-red text-sm float-right"><?php echo $used_email_error_msg; ?></span>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
									
								<div class="card-header"><h5 class="card-title"><i class="fas fa-mobile-alt"></i> Contact</h5></div>
								<div class="form-horizontal">
									<div class="card-body marginB16">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row marginT25">
													<label for="mobile no" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('mobile', array('type'=>'tel', 'escape'=>false, 'id'=>'mobile', 'label'=>false, 'class'=>'input-field form-control', 'placeholder'=>'Please enter your mobile no.')); ?>
														<span id="error_mobile_no" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row marginT25">
													<label for="landline" class="col-sm-3 col-form-label">Landline No.</label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('landline', array('type'=>'tel', 'escape'=>false, 'id'=>'landline', 'label'=>false, 'class'=>'input-field form-control', 'placeholder'=>'Please enter your landline no.')); ?>
														<span id="error_landline" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
									
								<div class="card-header"><h5 class="card-title"><i class="far fa-folder-open"></i> Documents</h5></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row marginT25">
													<label for="photoid" class="col-sm-4 col-form-label">Photo ID (Select any one) <span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('document', array('type'=>'select', 'id'=>'document', 'options'=>$document_lists, 'empty'=>'Select Document Type', 'label'=>false,'class'=>'form-control')); ?>
														<span id="error_document" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-6 marginB55">
												<div class="form-group row marginT25">
													<label for="file attach" class="col-sm-3 col-form-label">Attach File <span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('file',array('type'=>'file', 'id'=>'upload_file','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
														<p class="badge">File Type : .pdf or .jpg & Maximum Size : 2MB </p>
														<span id="error_upload_file" class="error invalid-feedback"></span>
														<span id="error_size_upload_file" class="error invalid-feedback"></span>
														<span id="error_type_upload_file" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer cardFooterBackground">
									<?php echo $this->Form->control('Create Primary', array('type'=>'submit', 'id'=>'register_btn','name'=>'submit', 'label'=>false,'class'=>'btn btn-success float-left')); ?>
									<?php echo $this->Html->link('Back', array('controller' => 'authprocessedoldapp', 'action'=>'home'),array('class'=>'btn btn-secondary float-right'));?>
								</div>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</section>
	</div>

<input type="hidden" value="<?php echo $return_error_msg; ?>" id="return_error_msg"/>
<?php echo $this->Html->script('authprocessedoldapp/register_customer/register_customer'); ?>