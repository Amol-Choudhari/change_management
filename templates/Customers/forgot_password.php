<?php
	$_SESSION['randSalt'] = Rand();
	$salt_server = $_SESSION['randSalt'];
	echo $this->element('get_captcha_random_code');
	$captchacode = $_SESSION["code"];
?>

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12 text-center"><h4>Forgot Password </h4></div>
		</div>
	</div>
</section>

	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6 align-center mx-auto">
					<div class="card img-thumbnail shadow">
						<?php echo $this->Form->create(null ,array('type'=>'file', 'id'=>'forgot_password_form', 'enctype'=>'multipart/form-data')); ?>
							<div class="card-body register-card-body">
							<p class="login-box-msg">Authorized Email Id</p>
							<p class="login-box-msg"><i class="fa fa-info-circle mr-1"></i>Link will be send on this email to reset password</p>
							<span id="error_customer_id" class="error invalid-feedback"></span>

							<div id="userid_indication" class="text-info"></div>
					        <div class="input-group mb-3">
					          	<?php $this->Form->setTemplates(['inputContainer' => '{{content}}']); ?>
							  	<?php echo $this->Form->control('customer_id', array('type'=>'text', 'label'=>false, 'id'=>'customer_id', 'class'=>'form-control input-field', 'placeholder'=>'Please enter your Applicant id')); ?>
								<div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>
					        </div>

							<span id="error_email" class="error invalid-feedback"></span>
					        <div class="input-group mb-3">
								<?php echo $this->Form->control('email', array('label'=>false, 'id'=>'email', 'class'=>'form-control input-field', 'placeholder'=>'Please enter registered email id')); ?>
								<div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>
					        </div>

							<span id="error_captchacode" class="error invalid-feedback"></span>
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
					          <div class="col-4">
				           		<?php echo $this->Form->control('Submit', array('type'=>'submit', 'name'=>'submit', 'label'=>false,'class'=>'btn btn-success btn-block submitForgotPass')); ?>
					          </div>
				        	</div>
						</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php echo $this->Html->script('customers/forgot_password_customer'); ?>
