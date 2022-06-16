<?php ?>
<?php echo $this->Html->css('userprofile') ?>

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6"><label class="badge badge-primary">User Profile</label></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
							<li class="breadcrumb-item active">User Profile</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		
		<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data','id'=>'user_profile')); ?>
		
		<div class="form-style-3 content form-middle">
			<div class="card card-cyan">
				<?php foreach($user_data as $user_data_value) { ?>
					<div class="card-header"><h3 class="card-title">Name</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="field3"><span>First Name <span class="cRed">*</span></span></label>
											<?php echo $this->Form->control('f_name', array('label'=>'', 'escape'=>false, 'id'=>'f_name', 'value'=>$user_data_value['f_name'], 'class'=>'input-field form-control')); ?>
											<span id="error_f_name" class="error invalid-feedback"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="field3"><span>Last Name <span class="cRed">*</span></span></label>
											<?php echo $this->Form->control('l_name', array('label'=>'', 'escape'=>false, 'id'=>'l_name', 'value'=>$user_data_value['l_name'], 'class'=>'input-field form-control')); ?>
											<span id="error_l_name" class="error invalid-feedback"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="uneditable" for="field3"><span>Email <span class="cRed">*</span></span></label>
											<?php echo $this->Form->control('email', array('label'=>'', 'escape'=>false, 'id'=>'email', 'value'=>$user_data_value['email'], 'class'=>'input-field form-control', 'readonly'=>true)); //for email encoding ?>
											<span id="error_email" class="error invalid-feedback"></span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Other Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<label class="uneditable" for="field3"><span>Mobile No. <span class="cRed">*</span></span></label>
										<?php echo $this->Form->control('phone', array('label'=>'', 'escape'=>false, 'id'=>'phone', 'class'=>'form-control','value'=>$user_data_value['phone'], 'readonly'=>true)); ?>
										<span id="error_phone" class="error invalid-feedback"></span>
									</div>
									<div class="col-md-4">
										<label for="field3"><span>Landline No. <span class="required"></span></span></label>
										<?php echo $this->Form->control('landline', array('label'=>'', 'value'=>base64_decode($user_data_value['landline']),'class'=>'form-control', 'escape'=>false, 'id'=>'landline_phone')); ?>
										<span id="error_landline_phone" class="error invalid-feedback"></span>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<div class="form-group row">
												<label for="field3"><span>Profile Picture
													<?php if (!empty($user_data_value['profile_pic'])) { ?>
													<a  target="_blank" href="<?php echo str_replace("D:/xampp/htdocs","",$user_data_value['profile_pic']); ?>">: Preview</a>
												<?php } ?>
												<span class="cRed">*</span></span>
												</label>
												<div class="custom-file">
												  <input type="file" class="custom-file-input" id="profile_pic" name="profile_pic" onchange='file_browse_onclick(id);return false', multiple='multiple'>
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
						
						<div class="card-header"><h3 class="card-title">Assigned Roles</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<?php foreach($assigned_old_roles as $each_role) { ?>
										<div class=" master_home col-md-4">
											<?php if ($each_role['add_user']=='yes') { ?>
													<label>Add User</label>
											<?php } ?>

											<?php if ($each_role['page_draft']=='yes') { ?>
												<label>Page (Draft only)</label>
											<?php } ?>

											<?php if ($each_role['page_publish']=='yes') { ?>
												<label>Page Publish</label>
											<?php } ?>

											<?php if ($each_role['menus']=='yes') { ?>
												<label>Menus</label>
											<?php } ?>

											<?php if ($each_role['mo_smo_inspection']=='yes') { ?>
												<label>MO/SMO</label>
											<?php } ?>

											<?php if ($each_role['io_inspection']=='yes') { ?>
												<label>Inspection Officer</label>
											<?php } ?>

											<?php if ($each_role['ro_inspection']=='yes') { ?>
												<label>RO In-Charge</label>
											<?php } ?>

											<?php if ($each_role['allocation_mo_smo']=='yes') { ?>
												<label>Allocate to MO/SMO</label>
											<?php } ?>

											<?php if ($each_role['allocation_io']=='yes') {  ?>
												<label>Allocate to IO</label>
											<?php } ?>

											<?php if ($each_role['reallocation']=='yes') {  ?>
												<label>Re-Allocate</label>
											<?php } ?>
										</div>

										<div class=" master_home col-md-4">
											<?php if ($each_role['form_verification_home']=='yes') { ?>
												<label>Form Scrutiny Home</label>
											<?php } ?>

											<?php if ($each_role['allocation_home']=='yes') { ?>
												<label>Allocation Home</label>
											<?php } ?>

											<?php if ($each_role['view_reports']=='yes') {  ?>
												<label>View Reports</label>
											<?php } ?>

											<?php if ($each_role['file_upload']=='yes') {  ?>
													<label>Upload Files</label>
											<?php } ?>

											<?php if ($each_role['dy_ama']=='yes') {  ?>
												<label>Dy. AMA (QC)</label>
											<?php } ?>

											<?php if ($each_role['ho_mo_smo']=='yes') {  ?>
												<label>HO MO/SMO</label>
											<?php } ?>

											<?php if ($each_role['jt_ama']=='yes') {  ?>
												<label>Jt. AMA</label>
											<?php } ?>

											<?php if ($each_role['ama']=='yes') {  ?>
												<label>AMA</label>
											<?php } ?>

											<?php if ($each_role['allocation_dy_ama']=='yes') {  ?>
												<label>Forward to Dy. AMA</label>
											<?php } ?>

											<?php if ($each_role['allocation_ho_mo_smo']=='yes') {  ?>
												<label>Allocate to HO MO/SMO</label>
											<?php } ?>
										</div>

										<div class=" master_home col-md-4">
											<?php if ($each_role['allocation_jt_ama']=='yes') {   ?>
												<label>Forward to Jt. AMA</label>
											<?php } ?>

											<?php if ($each_role['allocation_ama']=='yes') {  ?>
												<label>Forward to AMA</label>
											<?php } ?>

											<?php if ($each_role['masters']=='yes') {  ?>
												<label>Masters</label>
											<?php } ?>

											<?php if ($each_role['super_admin']=='yes') {  ?>
												<label>Super Admin</label>
											<?php } ?>

											<?php if ($each_role['renewal_verification']=='yes') {  ?>
												<label>Renewal Scrutiny</label>
											<?php } ?>

											<?php if ($each_role['renewal_allocation']=='yes') {  ?>
												<label>Renewal Allocation</label>
											<?php } ?>

											<?php if ($each_role['pao']=='yes') {  ?>
												<label>PAO/DDO</label>
											<?php } ?>

											<?php if ($each_role['once_update_permission']=='yes') {  ?>
												<label>Aadhar update Permission</label>
											<?php } ?>

											<?php if ($each_role['old_appln_data_entry']=='yes') {  ?>
												<label>Old Applications Data Entry</label>
											<?php } ?>

											<?php if ($each_role['so_inspection']=='yes') {  ?>
												<label>SO In-Charge</label>
											<?php } ?>

											<?php if ($each_role['smd_inspection']=='yes') {  ?>
												<label>SMD In-Charge</label>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="card-footer cardFooterBackground">
							<?php echo $this->Form->control('Update', array('type'=>'submit', 'name'=>'update', 'label'=>false,'class'=>'btn btn-success submit_btn float-left')); ?>
							<?php echo $this->Form->control('Back', array('type'=>'submit', 'name'=>'ok', 'label'=>false,'class'=>'btn btn-secondary float-right')); ?>
						</div>
					<?php } ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
	
<?php echo $this->Form->control('return_error_msg', array('type'=>'hidden', 'id'=>'return_error_msg', 'value'=>$return_error_msg)); ?>				
<?php echo $this->Html->script('Users/user_profile'); ?>
