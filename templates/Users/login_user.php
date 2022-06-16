<?php 

	$_SESSION['randSalt'] = Rand();			
	$salt_server = $_SESSION['randSalt'];			
	echo $this->element('get_captcha_random_code');	
	$captchacode = $_SESSION["code"];
		
?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-12 text-center">
					<h4>Authorized User Login</h4>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-10 align-center mx-auto">
					<div class="offset-2 card img-thumbnail shadow">
						<div class="card-body register-card-body">
							<p class="login-box-msg"><b>Authorized User Login</b></p>
							<div class="row">
								<div class="col-7">
									<?php echo $this->Form->create(null, array('autocomplete'=>'off', 'id'=>'login_user_form')); ?>

									<div id="error_email" class="text-red text-sm"></div>
									<div class="input-group mb-3">
										<?php $this->Form->setTemplates(['inputContainer' => '{{content}}']); ?>
											<?php echo $this->Form->control('email', array('label'=>false, 'id'=>'email', 'class'=>'form-control input-field', 'placeholder'=>'email')); ?>
												<div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span>
											</div>
										</div>
									</div>		
									
									<div class="dnone"><?php echo $this->Form->control('',array('type'=>'text', 'name'=>'username', 'value'=>'Hello')); ?></div>
												
									<?php if (!empty($captcha_error_msg)) { ?><div class="text-red">Enter Password Again</div> <?php echo $this->Html->script('customers/password_validation_call') ;?><?php }?>
								
									<div id="error_password" class="text-red text-sm"></div>
									<div class="input-group mb-3">
										<?php echo $this->Form->control('password', array('label'=>'', 'id'=>'passwordValidation','class'=>'form-control input-field', 'placeholder'=>'Password','autocomplete'=>'new-password')); ?>
										<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
										<span id="error_password" class="error invalid_feedback"></span>	
										<p class="password_text"></p>
										<?php echo $this->Form->control('salt_value', array('label'=>'', 'id'=>'hiddenSaltvalue', 'type'=>'hidden', 'value'=>$salt_server)); ?>
									</div>
									
									<div class="dnone"><?php echo $this->Form->control('', array('label'=>null,'type'=>'password', 'name'=>'temp_pass', 'value'=>'mypassword')); ?></div>
									
									
									<div id="error_captchacode" class="text-red float-right text-sm"></div>
									<?php if(!empty($captcha_error_msg)){ ?><div class="text-red float-right text-sm"><?php echo $captcha_error_msg; ?></div><?php echo $this->Html->script('customers/password_validation_call') ;?><?php } ?>


									<label for="field3"><span>Verify <span class="cRed">*</span></span></label><br />
									<div class="input-group mb-3">
										<span id="captcha_img" class="col-4 mr-2 rounded p-0 d-flex"><?php echo $this->Html->image(array('controller'=>'users','action'=>'create_captcha'), array('class'=>'rounded')); ?></span>
										<div class="col-2 btn m-0 p-0"><img class="img-responsive img-thumbnail border-0 shadow-none" id="new_captcha" src="<?php echo $this->request->getAttribute('webroot');?>img/refresh.png"/></div>
										<?php echo $this->Form->control('captcha', array('label'=>false, 'id'=>'captchacode', 'type'=>'text', 'placeholder'=>'Please enter captcha code','class'=>'form-control col-5')); ?>
										<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
									</div>
					
									<div class="social-auth-links text-center d-flex col-12 m-0 p-0">
										<div class="col-6 p-0"><?php echo $this->Form->control('Submit', array('type'=>'submit', 'id'=>'submit','name'=>'submit', 'label'=>false, 'class'=>'btn btn-success btn-block myfunctionclick')); ?></div>
										<div class="col-6 p-0">
											<a href="<?php echo $this->getRequest()->getAttribute('webroot'); ?>users/forgot_password" class="btn btn-light btn-sm border d-block ml-1" ><i class="fas fa-key mr-2"></i>Forgot Password</a>
										</div>
									</div>
								</div>
								<?php echo $this->Form->end(); ?>
								<div class="col-5 login-tips">
									<h6><b>Trouble Logging In?</b></h6>
									<ul>
										<li>User Id is case sensitive</li>
										<li>Password is case sensitive</li>
										<li>Captcha is case sensitive</li>
										<li>Enter the details properly</li>
										<li>Refresh captcha if not visible</li>
										<li>Password related queries refer the <a target="_blank" href="/writereaddata/DMI/manuals/DMI User Manual to Reset Password.pdf">Manual</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php if($already_loggedin_msg == 'yes'){ echo $this->element('already_loggedin_msg'); } ?>
<?php echo $this->Html->script('Users/login_user'); ?>