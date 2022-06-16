<?php
    $_SESSION['randSalt'] = Rand();
    $salt_server = $_SESSION['randSalt'];
    echo $this->element('get_captcha_random_code');
    $captchacode = $_SESSION['code'];
?>

<?php
	if(empty($captcha_error_msg)){$captcha_error_msg = '';}
	if(empty($used_email_error_msg)){$used_email_error_msg = '';}
	if(empty($primary_registered)){$primary_registered = '';}
?>
	
	<section class="content" id="form_outer_main">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-9 registrationform">
					<h5 class="mt-1 mb-2 pt-4 pb-2 pl-4"><i class="fa fa-user-plus"></i> New Applicant Registration</h5>
					<div class="">
                        <?php if (!empty($primary_registered)) { ?>
                            <div class="alert alert-success lh-36p">
                                <h5><i class="icon fas fa-check"></i> Congratulations !</h5>
                                1. Your details have been saved and your id is <b><?php echo $new_customer_id; ?></b><br>
                                2. You will receive two emails on your email id <b><?php echo base64_decode($htmlencodedemail); //for email encoding ?></b><br>
                                3. First email will contain a welcome message and second email will contain a link to set your password for login. <br>
                                4. This link will be active only for 24 hours. If expired, then try to set your password from Reset Password option. Thankyou.
                            </div>
                        <?php } else { ?>
                    </div>
                    <?php echo $this->Form->create(null, array('type'=>'file', 'id'=>'reg_customer_form', 'enctype'=>'multipart/form-data')); ?>

                    <div class="card card-info">
                        <div class="card-header sub-card-header-firm"><h3 class="card-title">Name</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div role="form">
									<div class="row">
										<div class="col-sm-4">
											<div class="form-group">
												<label>First Name <span class="cRed">*</span></label>
												<?php echo $this->Form->control('f_name', array('type'=>'text', 'escape'=>false, 'id'=>'f_name', 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter your first name')); ?>
												<span id="error_f_name" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label>Middle Name</label>
												<?php echo $this->Form->control('m_name', array('type'=>'text', 'escape'=>false, 'id'=>'m_name', 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter your middle name')); ?>
												<span id="error_m_name" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<label>Last Name <span class="cRed">*</span></label>
												<?php echo $this->Form->control('l_name', array('type'=>'text', 'escape'=>false, 'id'=>'l_name', 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter your last name')); ?>
												<span id="error_l_name" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Registered Office Address</h3></div>
						<div class="form-horizontal marginB10">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'escape'=>false, 'id'=>'street_address', 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter your street address')); ?>
											<span id="error_street_address" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>

									<div class="col-sm-6">
										<div class="form-group row marginB10">
											<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('state', array('type'=>'select', 'id'=>'state', 'empty'=>'Select State', 'options'=>$states, 'label'=>false,'class'=>'form-control')); ?>
												<span id="error_state" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="form-group row marginT25">
											<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('district', array('type'=>'select', 'id'=>'district', 'empty'=>'Select District', 'options'=>array(), 'label'=>false, 'class'=>'form-control')); ?>
												<span id="error_district" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="form-group row marginT25">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'id'=>'postal_code', 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter your postal/zip code')); ?>
												<span id="error_postal_code" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Email Address</h3></div>
						<div class="form-horizontal marginB10">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('email', array('label'=>false, 'id'=>'email', 'escape'=>false, 'class'=>'form-control input-field', 'placeholder'=>'Please enter your email id')); ?>
												<span id="error_email" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Confirm Email <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('confirm_email', array('label'=>false, 'id'=>'confirm_email', 'class'=>'form-control input-field', 'placeholder'=>'Please confirm your email id')); ?>
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

						<div class="card-header"><h3 class="card-title">Contact No.</h3></div>
						<div class="form-horizontal marginB10">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('mobile', array('type'=>'tel', 'escape'=>false, 'id'=>'mobile', 'label'=>false, 'class'=>'form-control input-field', 'placeholder'=>'Please enter your mobile no.')); ?>
											<span id="error_mobile_no" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Landline No.</label>
												<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('landline', array('type'=>'tel', 'escape'=>false, 'id'=>'landline', 'label'=>false, 'class'=>'form-control input-field', 'placeholder'=>'Please enter your landline no.')); ?>
												<span id="error_landline" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Photo Id</h3></div>
						<div class="form-horizontal marginB10">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-8">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Photo ID (Select any one) <span class="cRed">*</span></label>
											<div class="custom-file col-sm-8">
												<?php echo $this->Form->control('document', array('type'=>'select', 'id'=>'document', 'options'=>$document_lists, 'empty'=>'Select Document Type', 'label'=>false, 'class'=>'form-control')); ?>
												<span id="error_document" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="form-group row marginT25">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Photo ID No. <span class="cRed">*</span></label>
											<div class="custom-file col-sm-8">
												<?php echo $this->Form->control('photo_id_no', array('type'=>'text', 'escape'=>false, 'id'=>'photo_id_no', 'class'=>'form-control input-field', 'placeholder'=>'Enter your Photo ID No.', 'label'=>false)); ?>
												<span id="error_photo_id_no" class="error invalid-feedback"></span>
											</div>
										</div>
										<!--<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Aadhaar No.(optional)</label>
											<div class="custom-file col-sm-8">
											<?php //echo $this->Form->control('once_card_no', array('type'=>'text', 'escape'=>false, 'id'=>'once_card_no', 'class'=>'form-control input-field', 'placeholder'=>'Enter your aadhar no.(optional)', 'label'=>false)); ?>
											<span id="error_aadhar_card_no" class="error invalid-feedback"></span>
											</div>

											<label class="aadhar_check" for="field3">
											<?php //echo $this->Form->control('aadhar_auth_check', array('type'=>'checkbox', 'id'=>'aadhar_auth_check', 'label'=>$aadhar_auth_msg, 'escape'=>false)); ?>
											<div id="error_aadhar_auth_check"></div>
											</label>
										</div>-->

										<div class="form-group row marginT25">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Verify Captcha <span class="cRed">*</span></label>
											<div class="col-sm-8">
												<label for="inputEmail3" class="col-sm-4 col-form-label d-inline p-0">
												<span id="captcha_img"><?php echo $this->Html->image(array('controller'=>'customers','action'=>'create_captcha'), array('class'=>'rounded')); ?></span>
												<img class="img-responsive img-thumbnail border-0 shadow-none btn" id="new_captcha" src="<?php echo $this->request->getAttribute('webroot');?>img/refresh.png"/>
												</label>

												<?php // $this->Form->templates(['inputContainer' => '{{content}}']); // remove input field extra wrapping ?>
												<?php $this->Form->setTemplates(['inputContainer' => '{{content}}']); ?>

												<?php echo $this->Form->control('captcha', array('label'=>false, 'id'=>'captchacode', 'type'=>'text', 'placeholder'=>'Enter captcha code', 'class'=>'form-control col-sm-6 d-inline float-right')); ?>
												<span id="error_captchacode" class="error invalid-feedback"></span>

												<?php if(!empty($captcha_error_msg)){ ?>
												<span class="text-red text-sm float-right"><?php echo $captcha_error_msg; ?></span>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer cardFooterBackground">
							<?php echo $this->Form->control('Register', array('type'=>'submit', 'id'=>'register_btn','name'=>'submit', 'label'=>false, 'class'=>'btn btn-success float-left')); ?>
							<?php echo $this->Form->control('Reset', array('type'=>'reset', 'id'=>'reset_btn', 'label'=>false, 'class'=>'btn btn-default ml-2 float-right')); ?>
						</div>
						<!-- Call element of declaration message box before E-Sign of any application by pravin 10-08-2017 -->
						<?php echo $this->element('esign_views/aadhar_authentication_otp_popup'); ?>
					</div>
				<?php echo $this->Form->end(); ?>
				
					<input type="hidden" id="return_error_msg" value="<?php echo $return_error_msg; ?>">
					<?php echo $this->Html->script('customers/register_customer/register_customer') ?>
					<input type="hidden" id="captcha_error_msg" value="<?php echo $captcha_error_msg; ?>">
					<input type="hidden" id="used_email_error_msg" value="<?php echo $used_email_error_msg; ?>">
					<input type="hidden" id="primary_registered" value="<?php echo $primary_registered; ?>">
					<?php echo $this->Html->script('customers/register_customer/toastRegister') ?>

				<?php } ?>

                </div>
	        </div>
	    </div>
    </section>
