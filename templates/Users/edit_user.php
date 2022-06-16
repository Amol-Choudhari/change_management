<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Edit User</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item"><?php echo $this->Html->link('All Users', array('controller' => 'users', 'action'=>'all-users'));?></a></li>
						<li class="breadcrumb-item active">Edit Users</li>
					</ol>
				</div>
        	</div>
      	</div>
    </div>
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data','class'=>'form-group')); ?>
							<div class="card card-info">
								<div class="card-header sub-card-header-firm"><h3 class="card-title">Name</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-4">
													<div class="form-group">
														<label>First Name <span class="cRed">*</span></label>
															<?php echo $this->Form->control('f_name', array('label'=>'', 'type'=>'text', 'id'=>'f_name', 'value'=>$user_details['f_name'], 'class'=>'input-field form-control')); ?>
															<span id="error_f_name" class="error invalid-feedback"></span>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label>Last Name <span class="cRed">*</span></label>
															<?php echo $this->Form->control('l_name', array('label'=>'', 'type'=>'text', 'id'=>'l_name', 'value'=>$user_details['l_name'],'class'=>'form-control input-field')); ?>
															<span id="error_l_name" class="error invalid-feedback"></span>
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label for="field3"><span>Profile Picture
																<?php if(!empty($user_details['profile_pic'])){ ?>
																		<a  target="_blank" href="<?php echo str_replace("D:/xampp/htdocs","",$user_details['profile_pic']); ?>">: Preview</a>
																	<?php } ?>
																<span class="cRed">*</span></span>
															</label>
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
										<div class="card-header sub-card-header-firm"><h3 class="card-title">Other Details</h3></div>
											<div class="form-horizontal">
                								<div class="card-body">
													<div class="row">
														<div class="col-sm-4">
															<div class="form-group">
																<label>Email <span class="cRed">*</span></label>
																	<?php echo $this->Form->control('email', array('label'=>'', 'id'=>'email', 'value'=>$user_details['email'], 'class'=>'input-field form-control', 'readonly'=>true)); //for email encoding ?>
																	<span id="error_email" class="error invalid-feedback"></span>
																</div>
															</div>
														<div class="col-sm-4">
															<div class="form-group">
																				<label class="uneditable" for="field3"><span>Mobile No. <span class="cRed">*</span></span>
															<?php echo $this->Form->control('phone', array('label'=>'', 'value'=>$user_details['phone'], 'readonly'=>true, 'id'=>'phone', 'class'=>'form-control')); ?>
															<p class="mobilenumbertext">Mobile number should be same as mentioned in Aadhar</p>
															<div id="error_phone"></div>
														</label>
															</div>
														</div>

														<div class="col-sm-4">
															<div class="form-group">
																<label for="field3"><span>Landline No. <span class="required"></span></span>
																	<?php echo $this->Form->control('landline', array('label'=>'', 'value'=>base64_decode($user_details['landline']), 'id'=>'landline_phone','class'=>'form-control')); ?>
																	<div id="error_landline_phone"></div>
																</label>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="card-header sub-card-header-firm"><h3 class="card-title">User Division</h3></div>
												<div class="form-horizontal">
													<div class="card-body">
														<div class="row">
															<div class="col-sm-4">
																<div class="form-group">
																	<label for="field3"><span>User Division</span></label>
																		<div id="user_division">
																			<?php //added on 12-05-2017 by Amol(for DMI,LMIS & BOTH)
																				$options=array('DMI'=>'DMI','LMIS'=>'LIMS','BOTH'=>'BOTH',);
																				$attributes=array('value'=>$user_details['division'], 'id'=>'division');
																				echo $this->form->radio('division',$options,$attributes);
																			?>
																			<div class="clearfix"></div>
																				<!-- this div added on 27-07-2018 to show option for BOTH user to belongs to which dept. -->
																					<div id="user_belongs_to_div" class="b1scp5br5w4m0">
																						<span class="float-left">User Belongs To:</span>
																							<?php $options=array('DMI'=>'DMI','LMIS'=>'LIMS');
																								if($user_office_type == 'RAL'){$user_belongs_to='LMIS';}else{$user_belongs_to='DMI';}
																									$attributes=array('value'=>$user_belongs_to, 'id'=>'user_belongs_to');
																									echo $this->form->radio('user_belongs_to',$options,$attributes); ?>
																									<div class="clearfix"></div>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="offset-2 col-sm-4">
																			<div class="form-group small-box colorbluealert">
																				<div id="ro_list_div">
																					<label for="field3"><span>Office Posted To <span class="required">*</span></span></label>
																					<?php echo $this->Form->control('office_posted', array('label'=>'', 'type'=>'select', 'options'=>$office_posted, 'value'=>$user_details['posted_ro_office'], 'id'=>'office_posted','class'=>'form-control')); ?>
																				</div>
																				<div id="ral_list_div">
																					<label for="field3"><span>Office Posted To <span class="required">*</span></span></label>
																					<?php echo $this->Form->control('office_posted', array('label'=>'', 'type'=>'select', 'options'=>$ral_office_posted, 'value'=>$user_details['posted_ro_office'], 'id'=>'ral_office_posted','class'=>'form-control')); ?>
																				</div>
																				<div id="lmis_roles_list">
																					<label for="field3"><span>Select Role</span></label>								<!-- added id on 27-07-2018 -->
																						<?php echo $this->Form->control('role', array('label'=>false, 'type'=>'select', 'id'=>'lmis_role', 'value'=>$selected_lmis_role, 'options'=>$get_lmis_roles, 'empty'=>'--Select','class'=>'form-control')); ?>
																						<div id="error_lmis_role"></div>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="card-footer color_alert_dark">
																		<?php echo $this->Form->control('Update', array('type'=>'submit', 'name'=>'submit', 'label'=>false,'class'=>'btn btn-success float-left update_btn')); ?>
																		<?php echo $this->Html->link('Back', array('controller' => 'dashboard', 'action'=>'home'),array('class'=>'btn btn-secondary float-right'));?>
																	</div>
																</div>
															<?php echo $this->Form->end(); ?>
														</div>
													</div>
												</div>
											</section>
									</div>

				
<?php echo $this->Form->control('return_error_msg', array('type'=>'hidden', 'id'=>'return_error_msg', 'value'=>$return_error_msg)); ?>				
<?php echo $this->Html->script('Users/edit_user'); ?>
