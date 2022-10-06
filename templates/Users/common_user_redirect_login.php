<?php $_SESSION['randSalt'] = Rand(); $salt_server = $_SESSION['randSalt']; ?>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6 align-center mx-auto">
				<?php echo $this->Form->create(null,array('id'=>'common_user_redirect_login_form')); ?>
					<div class="card img-thumbnail box2shadow">
						<div class="card-body register-card-body">
							<h4 class="login-box-msg">Common User Login Redirect</h4>
							<div class="input-group mb-3">
								<label for="field3" class="col-md-3"><span> Password <span class="cRed">*</span></span></label>
								<?php echo $this->Form->control('password', array('label'=>'', 'id'=>'passwordValidation', 'placeholder'=>'Please enter your Password','class'=>'form-control')); ?>
								<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
								<span id="error_password" class="error invalid-feedback"></span>
								<?php echo $this->Form->control('salt_value', array('label'=>'', 'id'=>'hiddenSaltvalue', 'type'=>'hidden', 'value'=>$salt_server)); ?>
							</div>
						</div>
						<div class="card-footer">
							<?php echo $this->Form->control('Submit', array('type'=>'submit', 'name'=>'submit','label'=>false,'class'=>'btn btn-success submit_btn')); ?>						
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</section>

<input type="hidden" value="<?php echo $return_error_msg; ?>" id="return_error_msg"/>
<?php echo $this->Html->script('Users/common_user_redirect_login'); ?>
