<?php ?>

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6"><label class="badge badge-primary">Add Users</label></div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
								<li class="breadcrumb-item"><?php echo $this->Html->link('All Users', array('controller' => 'users', 'action'=>'all-users'));?></a></li>
								<li class="breadcrumb-item active">Add Users</li>
							</ol>
						</div>
					</div>
				</div>
			</div>

			<?php if (!empty($user_registered)) {?>
				<h4><?php echo "Congratulations... you have created new user with email id ".base64_decode($htmlencodedemail) ; //for email encoding ?><br/>
					<a href="<?php echo $this->getRequest()->getAttribute('webroot');?>users/login_user">Please click Here to login by new user</a>
				</h4>
			<?php } else { ?>

			<section class="content form-middle">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<?php echo $this->Form->create(null , array('type'=>'file', 'enctype'=>'multipart/form-data','class'=>'form-group','id'=>'add_user_form')); ?>
								<div class="card card-info">
									<div class="card-header"><h3 class="card-title">Name</h3></div>
										<div class="form-horizontal">
											<div class="card-body">
												<p class="bg-gray pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"> Name should be same as in Aadhar card.</i></p>
													<div class="row">
														<div class="col-sm-4">
															<div class="form-group">
																<label for="field3"><span>First Name <span class="cRed">*</span></span></label>
																<?php echo $this->Form->control('f_name', array('label'=>'','id'=>'f_name', 'class'=>'input-field', 'placeholder'=>'Enter First Name','class'=>'form-control')); ?>
																<span id="error_f_name" class="error invalid-feedback"></span>
															</div>
														</div>
														<div class="col-sm-4">
															<div class="form-group">
																<label for="field3"><span>Last Name <span class="cRed">*</span></span></label>
																<?php echo $this->Form->control('l_name', array('label'=>'','id'=>'l_name', 'class'=>'input-field', 'placeholder'=>'Enter Last Name','class'=>'form-control')); ?>
																<span id="error_l_name" class="error invalid-feedback"></span>
															</div>
														</div>
														<div class="col-sm-4">
															<div class="form-group">
																<div class="form-group row">
																	<label for="field3"><span>Profile Picture <span class="cRed">*</span></span></label>
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
											</div>
											<div class="card-header"><h3 class="card-title">Other Details</h3></div>
												<div class="form-horizontal">
													<div class="card-body">
														<div class="row">
															<div class="col-sm-4">
																<div class="form-group">
																	<label for="field3"><span>Email <span class="cRed">*</span></span></label>
																	<?php echo $this->Form->control('email', array('label'=>'','id'=>'email', 'class'=>'input-field', 'placeholder'=>'Enter your email','class'=>'form-control')); ?>
																	<span id="error_email" class="error invalid-feedback"></span>
																</div>
															</div>
															<div class="col-sm-4">
																<div class="form-group">
																	<label for="field3"><span>Mobile No. <span class="cRed">*</span></span></label>
																	<?php echo $this->Form->control('phone', array('label'=>'','id'=>'phone', 'placeholder'=>'Mobile No.','class'=>'form-control')); ?>
																	<span id="error_phone" class="error invalid-feedback"></span>
																</div>
															</div>
															<div class="col-sm-4">
																<div class="form-group">
																	<label for="field3"><span>Landline No. <span class="required"></span></span></label>
																	<?php echo $this->Form->control('landline', array('label'=>'','id'=>'landline_phone', 'placeholder'=>'Landline No.','class'=>'form-control')); ?>
																	<span id="error_landline_phone" class="error invalid-feedback"></sapn>
																</div>
															</div>
														</div>
													</div>
												</div>
											<div class="card-header"><h3 class="card-title">User Division</h3></div>
												<div class="form-horizontal">
													<div class="card-body">
														<div class="row">
															<div class="col-sm-4">
																<div class="form-group">
																	<div id="user_division">
																		<?php //added on 12-05-2017 by Amol(for DMI,LMIS & BOTH)
																			if($_SESSION['division'] == 'DMI')
																			{
																				$division = 'DMI';
																			}
																			elseif($_SESSION['division'] == 'LMIS'){
																				$division = 'LMIS';
																			}
																			elseif($_SESSION['division'] == 'BOTH'){
																				$division = 'BOTH';
																			}

																			$options=array('DMI'=>'DMI','LMIS'=>'LIMS','BOTH'=>'BOTH');
																			$attributes=array('value'=>$division, 'id'=>'division');
																			echo $this->form->radio('division',$options,$attributes);
																		?>
																		<p></p>
																		<div class="clearfix"></div>
																		<!-- this div added on 27-07-2018 to show option for BOTH user to belongs to which dept. -->
																		<div id="user_belongs_to_div"  class="small-box alert alert-primary b1scp5br5w4m0">
																			<span class="float-left;">User Belongs To:</span>

																				<?php $options=array('DMI'=>'DMI','LMIS'=>'LIMS');
																				$attributes=array('id'=>'user_belongs_to');
																				echo $this->form->radio('user_belongs_to',$options,$attributes); ?>

																			<div class="clearfix"></div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="offset-2 col-sm-4">
																<div class="form-group small-box colorbluealert">
																	<div id="ro_list_div">
																		<label for="field3"><span>Office Posted To <span class="cRed">*</span></span></label>
																			<?php echo $this->Form->control('office_posted', array('label'=>'', 'type'=>'select', 'options'=>$office_posted,'id'=>'office_posted','class'=>'form-control')); ?>
																	</div>
																	<div id="ral_list_div">
																		<label for="field3"><span>Office Posted To <span class="cRed">*</span></span></label>
																		<?php echo $this->Form->control('office_posted', array('label'=>'', 'type'=>'select', 'options'=>$ral_office_posted,'id'=>'ral_office_posted','class'=>'form-control')); ?>
																	</div>
																	<div id="lmis_roles_list">
																		<label for="field3"><span>Select Role</span></label>
																		<?php echo $this->Form->control('role', array('label'=>false, 'type'=>'select', 'id'=>'lmis_role', 'empty'=>'--Select--', 'options'=>$get_lmis_roles,'class'=>'form-control')); ?>
																		<div id="error_lmis_role"></div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											<div class="card-footer cardFooterBackground 	">
												<?php echo $this->Form->control('Submit', array('type'=>'submit', 'name'=>'submit', 'label'=>false, 'class'=>'btn btn-success float-left submit_btn')); ?>
												<?php echo $this->Html->link('Back', array('controller' => 'dashboard', 'action'=>'home'),array('class'=>'add_btn btn btn-secondary float-right')); ?>
											</div>
										<?php echo $this->Form->end(); ?>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>

<?php echo $this->Form->control('return_error_msg', array('type'=>'hidden', 'id'=>'return_error_msg', 'value'=>$return_error_msg)); ?>
<?php echo $this->Html->script('Users/add_user'); ?>
