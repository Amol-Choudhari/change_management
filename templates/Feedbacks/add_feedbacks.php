
<?php

	echo $this->element('get_captcha_random_code');
	$captchacode = $_SESSION["code"];

?>

<section class="form-middle content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-11 ml175p19">
				<div class="card card-info">
					<div class="card-header"><h3 class="card-title-new">Feedback Form</h3></div>
						<?php echo $this->Form->create(null, array('autocomplete'=>'off','id'=>'feedback_form_id')); ?>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<label class="f_title_line"><span>First Name <span class="cRed">*</span></span> </label>
											<?php echo $this->Form->control('firstname', array('label'=>'', 'id'=>'firstname','maxlength'=>255,'type'=>'text','class'=>'form-control', 'placeholder'=>'Firstname')); ?>
											<span id="error_firstname" class="error invalid-feedback"></span>
										</div>
										<div class="col-md-6">
											<label class="f_title_line"><span>Last Name <span class="cRed">*</span></span> </label>
											<?php echo $this->Form->control('lastname', array('label'=>'', 'id'=>'lastname','maxlength'=>255,'type'=>'text','class'=>'form-control',  'placeholder'=>'Lastname')); ?>
											<span class="error invalid-feedback" id= "error_lastname"></span>
										</div>
									</div>
								</div>
							</div>

							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<label class="f_title_line"><span>Mobile No <span class="cRed">*</span></span> </label>
											<?php echo $this->Form->control('mobile', array('label'=>'', 'id'=>'mobile','maxlength'=>'10','type'=>'text','class'=>'form-control',  'placeholder'=>'Mobile no')); ?>
											<span class="error invalid-feedback"  id="error_mobile_valid"></span>
											<span class="error invalid-feedback"  id="error_mobile"></span>
											<span class="error invalid-feedback"  id="error_mobile_length"></span>
										</div>
										<div class="col-md-6">
											<label class="f_title_line"><span>Email <span class="cRed">*</span></span></label>
											<?php echo $this->Form->control('email', array('label'=>'', 'id'=>'email','maxlength'=>255,'type'=>'email','class'=>'form-control',  'placeholder'=>'Email')); ?>
											<span class="error invalid-feedback" id="error_email"></span>
											<span class="error invalid-feedback" id="error_email_valid"></span>
										</div>
									</div>
								</div>
							</div>

							<!-- if selected other option, then show textbox to enter other feedback type , Change on 30-08-2018, By Pravin Bhakare-->

							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<label class="f_title_line"><span>Type of Feedback <span class="cRed">*</label>
											<?php echo $this->Form->control('type',array('label'=>'','type'=>'select','empty'=>'--Select--','class'=>'form-control','id'=>'type','options'=>$list_of_feedback )); ?>
											<span class="error invalid-feedback" id="error_type"></span>
											<?php echo $this->Form->control('other', array('label'=>'', 'id'=>'other','maxlength'=>50,'type'=>'text','class'=>'form-control',  'placeholder'=>'other type	')); ?>
											<span class="error invalid-feedback" id="error_othertype"></span>
										</div>
									</div>
								</div>
							</div>

							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<label class="f_title_line"><span>Address <span class="cRed">*</span></span></label>
											<?php echo $this->Form->control('address', array('label'=>'','type'=>'textarea','class'=>'form-control textbox_h',  'id'=>'address', 'placeholder'=>'Address')); ?>
											<span class="error invalid-feedback" id="error_address"></span>
										</div>
										<div class="col-md-6">
											<label class="f_title_line"><span>Comment <span class="cRed">*</span></span>  </label>
											<?php echo $this->Form->control('comment', array('label'=>'','type'=>'textarea','class'=>'form-control textbox_h',  'id'=>'comment', 'placeholder'=>'Comment')); ?>
											<span class="error invalid-feedback" id="error_comment"></span>
										</div>
									</div>
								</div>
							</div>


							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Verify Captcha <span class="cRed">*</span></label>
												<div class="col-sm-8">
													<label for="inputEmail3">
														<span id="captcha_img"><?php echo $this->Html->image(array('controller'=>'users','action'=>'create_captcha'), array('class'=>'rounded')); ?></span>
														<img class="img-responsive img-thumbnail border-0 shadow-none btn" id="new_captcha" src="<?php echo $this->getRequest()->getAttribute('webroot');?>img/refresh_button1.jpg"/>
													</label>

													<?php $this->Form->setTemplates(['inputContainer' => '{{content}}']); ?>
													<?php echo $this->Form->control('captcha', array('label'=>false, 'id'=>'captchacode', 'type'=>'text','class'=>'form-control col-sm-6 d-inline float-right','placeholder'=>'Enter captcha')); ?>
													<div class="clear"></div>
													<span class="error invalid-feedback" id="error_captchacode"></span>
													<p class="password_text"><?php if (!empty($captcha_error_msg)){ echo $captcha_error_msg; } ?></p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer cardFooterBackground">
									<?php echo $this->Form->control('Send', array('type'=>'submit', 'name'=>'submit', 'label'=>false,'class'=>'feedback_button btn btn-success float-left myfunctionclick')); ?>
									<div class="clear"></div>
								</div>
							<?php echo $this->form->end(); ?>
						</div>
					</div>
				</div>
			</div>
		</section>

<?php echo $this->Form->control('return_error_msg', array('type'=>'hidden', 'id'=>'return_error_msg', 'value'=>$return_error_msg)); ?>
<?php echo $this->Html->script('Feedbacks/add_feedbacks'); ?>
