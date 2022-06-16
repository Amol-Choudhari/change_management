	<?php
		$_SESSION['randSalt'] = Rand();
		$salt_server = $_SESSION['randSalt'];
		echo $this->element('get_captcha_random_code');//added on 15-07-2017 by Amol
		$captchacode = $_SESSION["code"];

	?>

	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-12 text-center"><h4>Reset Password</h4></div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6 align-center mx-auto">
					<div class="card img-thumbnail shadow">
						<?php echo $this->Form->create(null, array('type'=>'file', 'id'=>'reset_password')); ?>
						<div class="card-body register-card-body">
				      		<p class="login-box-msg">Personal</p>
							<p class="login-box-msg">User Id</p>
							<div id="error_email" class="text-red text-sm"></div>
								<div class="input-group mb-3">
									<?php $this->Form->setTemplates(['inputContainer' => '{{content}}']); ?>
									<?php echo $this->Form->control('customer_id', array('type'=>'text', 'label'=>false, 'id'=>'customer_id', 'value'=>$user_id, 'class'=>'form-control input-field', 'readonly'=>true)); ?>
								</div>

								<div id="error_Newpassword" class="text-red text-sm"></div>
								<div class="input-group mb-3">
									<?php echo $this->Form->control('new_password', array('label'=>'', 'type'=>'password', 'id'=>'Newpassword', 'class'=>'form-control input-field', 'placeholder'=>'Enter New Password')); ?>
									<div class="input-group-append">
										<div class="input-group-text"><span class="fas fa-lock"></span></div>
									</div>
									<?php echo $this->Form->control('salt_value', array('label'=>'', 'id'=>'hiddenSaltvalue', 'type'=>'hidden', 'value'=>$salt_server)); ?>
								</div>


								<div id="error_confpass" class="text-red text-sm"></div>
						        <div class="input-group mb-3">
						          <?php echo $this->Form->control('confirm_password', array('label'=>'', 'type'=>'password', 'id'=>'confpass','class'=>'form-control input-field', 'placeholder'=>'Confirm New Password')); ?>
						          <div class="input-group-append">
						            <div class="input-group-text">
						              <span class="fas fa-lock"></span>
						            </div>
						          </div>
						          <p id="comfirm_pass_msg" class="text-red text-sm"><?php if(!empty($comfirm_pass_msg)){ echo $comfirm_pass_msg;}?></p>
						        </div>

							<div id="error_captchacode" class="text-red float-right text-sm"></div>
								<div class="input-group mb-3">
						          <span id="captcha_img" class="col-4 mr-2 rounded p-0 d-flex">
						          	<?php echo $this->Html->image(array('controller'=>'customers','action'=>'create_captcha'), array('class'=>'rounded')); ?>
						          </span>
						          <div class="col-2 btn m-0 p-0">
							          <img class="img-responsive img-thumbnail border-0 shadow-none" id="new_captcha" src="<?php echo $this->request->getAttribute('webroot');?>img/refresh.png" />
							      </div>

						          <?php echo $this->Form->control('captcha', array('label'=>false, 'id'=>'captchacode', 'type'=>'text', 'placeholder'=>'Enter captcha', 'class'=>'form-control col-5')); ?>
						          <div class="input-group-append">
						            <div class="input-group-text">
						              <span class="fas fa-lock"></span>
						            </div>
						          </div>
						        </div>
								<div class="row">
						          <div class="col-8">
						          </div>
						          <div class="col-4">
						            <?php echo $this->Form->control('Submit', array('type'=>'submit', 'name'=>'submit', 'label'=>false,'class'=>'btn btn-success btn-block submitButton')); ?>
						          </div>
						        </div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>


<?php echo $this->Html->script('customers/reset_password'); ?>
