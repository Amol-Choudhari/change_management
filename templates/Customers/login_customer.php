
<?php
	$_SESSION['randSalt'] = Rand();
	$salt_server = $_SESSION['randSalt'];
	echo $this->element('get_captcha_random_code');//added on 15-07-2017 by Amol
	$captchacode = $_SESSION["code"];
?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-12 text-center">
		<?php if($_SESSION['login_to']=='ca'){?>
			<h4>Applicant Login for Certificate of Authorisation</h4>
		<?php }elseif($_SESSION['login_to']=='printing'){?>
			<h4>Applicant Login for Certificate of Printing Permission</h4>
		<?php }elseif($_SESSION['login_to']=='lab'){?>
			<h4>Applicant Login for Certificate of Approval of Laboratory</h4>
		<?php } ?>
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
							<p class="login-box-msg"><b>Sign In</b></p>
								<div class="row">
									<div class="col-7">
										<?php echo $this->Form->create(null, array('autocomplete'=>'off', 'id'=>'login_customer_form')); ?>
											<div id="error_customer_id" class="text-red text-sm"></div>
											<div id="userid_indication" class="text-info"></div>

												<div class="input-group mb-3">
												  <?php $this->Form->setTemplates(['inputContainer' => '{{content}}']); ?>
												  <?php echo $this->Form->control('customer_id', array('label'=>false, 'type'=>'text', 'id'=>'customer_id', 'class'=>'form-control input-field', 'placeholder'=>'Company Id/Premises Id')); ?>
												  <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>
												</div>

												<div class="dnone">
													<?php echo $this->Form->control('', array('label'=>'','type'=>'text', 'name'=>'username', 'value'=>'Hello')); ?>
												</div>

												<?php if(!empty($captcha_error_msg)){ ?>
													<div class="text-red">Enter Password Again</div>
													<?php echo $this->Html->script('customers/password_validation_call') ;?>
												<?php } ?>

												<div id="error_password" class="text-red text-sm"></div>
												<div class="input-group mb-3">
												  <?php echo $this->Form->control('password', array('label'=>'', 'id'=>'passwordValidation', 'class'=>'form-control input-field', 'placeholder'=>'Password','autocomplete'=>'new-password')); ?>
												  <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
												  <?php echo $this->Form->control('salt_value', array('label'=>'', 'id'=>'hiddenSaltvalue', 'type'=>'hidden', 'value'=>$salt_server)); ?>
												</div>

												<div class="dnone">
													<?php echo $this->Form->control('', array('label'=>'','type'=>'password', 'name'=>'temp_pass', 'value'=>'mypassword')); ?>
												</div>

												<div id="error_captchacode" class="text-red float-right text-sm"></div>

												<?php if(!empty($captcha_error_msg)){ ?>
													<div class="text-red float-right text-sm"><?php echo $captcha_error_msg; ?></div>
													<?php echo $this->Html->script('customers/password_validation_call') ;?>
												<?php } ?>

												<div class="input-group mb-3">
												  <span id="captcha_img" class="col-4 mr-2 rounded p-0 d-flex">
													<?php echo $this->Html->image(array('controller'=>'customers','action'=>'create_captcha'), array('class'=>'rounded')); ?>
												  </span>

												  <div class="col-2 btn m-0 p-0">
													  <img class="img-responsive img-thumbnail border-0 shadow-none" id="new_captcha" src="<?php echo $this->request->getAttribute('webroot');?>img/refresh.png"/>
												  </div>

												  <?php echo $this->Form->control('captcha', array('label'=>false, 'id'=>'captchacode', 'type'=>'text', 'placeholder'=>'Enter captcha', 'class'=>'form-control col-5')); ?>
												  <div class="input-group-append">
													<div class="input-group-text">
													  <span class="fas fa-lock"></span>
													</div>
												  </div>
												</div>

												<div class="row">
												  <div class="col-8"></div>
												  <div class="col-4">
														<?php echo $this->Form->control('Submit', array('type'=>'submit', 'name'=>'submit', 'label'=>false, 'id'=>'login_customer_validation_call', 'class'=>'btn btn-success btn-block')); ?>
												  </div>
												</div>
				  						<?php echo $this->Form->end(); ?>

										  <div class="social-auth-links text-center">
											<p>- OR -</p>
										  </div>
										  <div class="social-auth-links text-center d-flex col-12 m-0 p-0">
												<div class="col-6 p-0">
													<a href="<?php echo $this->request->getAttribute('webroot'); ?>customers/register-customer" class="btn btn-light btn-sm border d-block">
													  <i class="fas fa-user-plus mr-2"></i> Sign Up
													</a>
												</div>
												<div class="col-6 p-0">
													<a href="<?php echo $this->request->getAttribute('webroot'); ?>customers/forgot-password" class="btn btn-light btn-sm border d-block ml-1">
													  <i class="fas fa-key mr-2"></i> Forgot Password
													</a>
												</div>
										  </div>
									 </div>

			  				<div class="col-5 login-tips">
								<h6><b>Trouble Logging In?</b></h6>
								<ul>
									<li>User Id is case sensitive</li>
									<li>Password is case sensitive</li>
									<li>Captcha is case sensitive</li>
									<li>Enter the details properly</li>
									<li>Refresh captcha if not visible</li>
									<li>Password related queries refer the <a target="_blank" href="/writereaddata/DMI/manuals/Applicant Manual to Reset Password.pdf">Manual</a></li>
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
<?php echo $this->Html->script('customers/login_customers') ;?>








						
