<?php ?>
<?php echo $this->Html->css('Roles/edit_roles') ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Edit Roles</label></div>
          			<div class="col-sm-6">
           				 <ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
			              	<li class="breadcrumb-item active">Edit User Roles</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-10">
						<div class="card card-cyan">
			            	<div class="card-header"><h3 class="card-title-new">Edit User Roles</h3></div>
								<div class="card-body mb-3">
									<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'edit_roles_form')); ?>
										<div class="set_roles_page">
											<label>Select User & Assign</label>
												<div class="form-group row">
													<div class="col-md-6">
														<?php echo $this->Form->control('user_list', array('type'=>'select', 'id'=>'user_list', 'options'=>$find_available_user, 'label'=>false,'class'=>'form-control')); ?>
													</div>
													<div class="col-md-3">
														<?php echo $this->Form->control('Show Roles', array('type'=>'submit', 'id'=>'show_roles_btn', 'name'=>'show_roles', 'label'=>false,'class'=>'btn btn-info')); ?>
													</div>
												</div>
												<div class="col-md-12" id="user_division"></div>

												<div class="clearfix"></div>
												 <!-- create div for to show selected user division on ajax call (done by pravin 16/11/2017)-->

												<?php if (!empty($assigned_old_roles)) { ?>
													<?php foreach ($assigned_old_roles as $each_role) { ?>
														<div class="alert alert-success" id="dmi_user_roles_list_box" >
															<label>DMI Roles List : </label>
																<div class="row">
																	<div class="col-md-4">

																		<?php if ($each_role['add_user']=='yes') {
																			echo $this->Form->control('add_user', array('type'=>'checkbox', 'checked'=>true,'label'=>' Add User'));
																		} else {
																			echo $this->Form->control('add_user', array('type'=>'checkbox', 'checked'=>false,'label'=>' Add User'));
																		} ?>

																		<?php if ($each_role['page_draft']=='yes') {
																			echo $this->Form->control('page_draft', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Page (Draft only)',));
																		} else {
																			echo $this->Form->control('page_draft', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Page (Draft only)',));
																		} ?>

																		<?php if ($each_role['page_publish']=='yes') {
																			echo $this->Form->control('page_publish', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Page Publish',));
																		} else {
																			echo $this->Form->control('page_publish', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Page Publish',));
																		} ?>

																		<?php if ($each_role['menus']=='yes') {
																			echo $this->Form->control('menus', array('type'=>'checkbox', 'checked'=>true,'label'=>' Menus'));
																		} else {
																			echo $this->Form->control('menus', array('type'=>'checkbox', 'checked'=>false,'label'=>' Menus'));
																		} ?>

																		<?php if ($each_role['mo_smo_inspection']=='yes') {
																			echo $this->Form->control('mo_smo_inspection', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Scrutiny Officer', 'id'=>'mo_smo_inspection',));
																		} else {
																			echo $this->Form->control('mo_smo_inspection', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Scrutiny Officer', 'id'=>'mo_smo_inspection',));
																		} ?>

																		<?php if ($each_role['io_inspection']=='yes') {
																			echo $this->Form->control('io_inspection', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Inspection Officer', 'id'=>'io_inspection',));
																		} else {
																			echo $this->Form->control('io_inspection', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Inspection Officer', 'id'=>'io_inspection',));
																		} ?>

																		<?php if ($each_role['ro_inspection']=='yes') {
																			echo $this->Form->control('ro_inspection', array('type'=>'checkbox', 'checked'=>true, 'label'=>' RO In-Charge', 'id'=>'ro_inspection',));
																		} else {
																			echo $this->Form->control('ro_inspection', array('type'=>'checkbox', 'checked'=>false, 'label'=>' RO In-Charge', 'id'=>'ro_inspection',));
																		} ?>

																		<?php if ($each_role['allocation_mo_smo']=='yes') {
																			echo $this->Form->control('allocation_mo_smo', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Allocate to Scrutiny',));
																		} else {
																			echo $this->Form->control('allocation_mo_smo', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Allocate to Scrutiny',));
																		} ?>

																		<?php if ($each_role['allocation_io']=='yes') {
																			echo $this->Form->control('allocation_io', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Allocate to IO',));
																		} else {
																			echo $this->Form->control('allocation_io', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Allocate to IO',));
																		} ?>

																		<?php if ($each_role['reallocation']=='yes') {
																			echo $this->Form->control('reallocation', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Re-Allocate',));
																		} else {
																			echo $this->Form->control('reallocation', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Re-Allocate',));
																		} ?>

															</div>

															<div class="col-md-4">

																<?php if ($each_role['file_upload']=='yes') {
																	echo $this->Form->control('file_upload', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Upload Files',));
																} else {
																	echo $this->Form->control('file_upload', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Upload Files',));
																} ?>

																<?php if ($each_role['dy_ama']=='yes') {																					// Set id for dy_ama by pravin 30-08-2017
																	echo $this->Form->control('dy_ama', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Dy. AMA (QC)', 'id'=>'dy_ama',));
																} else {																					// Set id for dy_ama by pravin 30-08-2017
																	echo $this->Form->control('dy_ama', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Dy. AMA (QC)', 'id'=>'dy_ama',));
																} ?>

																<?php if ($each_role['ho_mo_smo']=='yes') {																												// Set id for ama by pravin 04-09-2017
																	echo $this->Form->control('ho_mo_smo', array('type'=>'checkbox', 'checked'=>true, 'label'=>' HO Scrutiny Officer', 'id'=>'ho_mo_smo',));
																} else {																											// Set id for ama by pravin 04-09-2017
																	echo $this->Form->control('ho_mo_smo', array('type'=>'checkbox', 'checked'=>false, 'label'=>' HO Scrutiny Officer', 'id'=>'ho_mo_smo',));
																} ?>

																<?php if ($each_role['jt_ama']=='yes') {																					// Set id for jt_ama by pravin 30-08-2017
																	echo $this->Form->control('jt_ama', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Jt. AMA', 'id'=>'jt_ama',));
																} else {																						// Set id for jt_ama by pravin 30-08-2017
																	echo $this->Form->control('jt_ama', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Jt. AMA', 'id'=>'jt_ama',));
																} ?>

																<?php if ($each_role['ama']=='yes') {																						// Set id for ama by pravin 30-08-2017
																	echo $this->Form->control('ama', array('type'=>'checkbox', 'checked'=>true, 'label'=>' AMA', 'id'=>'ama',));
																} else {																					// Set id for ama by pravin 30-08-2017
																	echo $this->Form->control('ama', array('type'=>'checkbox', 'checked'=>false, 'label'=>' AMA', 'id'=>'ama',));
																} ?>

																<?php /* if ($each_role['allocation_dy_ama']=='yes') {
																	echo $this->Form->control('allocation_dy_ama', array('type'=>'checkbox', 'checked'=>true, 'label'=>'Forward to Dy. AMA',));
																} else {
																	echo $this->Form->control('allocation_dy_ama', array('type'=>'checkbox', 'checked'=>false, 'label'=>'Forward to Dy. AMA',));
																} */ ?>

																<?php if ($each_role['allocation_ho_mo_smo']=='yes') {
																	echo $this->Form->control('allocation_ho_mo_smo', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Allocate to HO Scrutiny',));
																} else {
																	echo $this->Form->control('allocation_ho_mo_smo', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Allocate to HO Scrutiny',));
																} ?>

																<?php /* if ($each_role['allocation_jt_ama']=='yes') {
																	echo $this->Form->control('allocation_jt_ama', array('type'=>'checkbox', 'checked'=>true, 'label'=>'Forward to Jt. AMA',));
																} else {
																	echo $this->Form->control('allocation_jt_ama', array('type'=>'checkbox', 'checked'=>false, 'label'=>'Forward to Jt. AMA',));
																}*/ ?>

																<?php /* if ($each_role['allocation_ama']=='yes') {
																	echo $this->Form->control('allocation_ama', array('type'=>'checkbox', 'checked'=>true, 'label'=>'Forward to AMA',));
																} else {
																	echo $this->Form->control('allocation_ama', array('type'=>'checkbox', 'checked'=>false, 'label'=>'Forward to AMA',));
																} */ ?>

																<?php if ($each_role['masters']=='yes') {
																	echo $this->Form->control('masters', array('type'=>'checkbox', 'checked'=>true,'label'=>' Masters'));
																} else {
																	echo $this->Form->control('masters', array('type'=>'checkbox', 'checked'=>false,'label'=>' Masters'));
																} ?>

																<?php if ($each_role['super_admin']=='yes') {
																	echo $this->Form->control('super_admin', array('type'=>'checkbox', 'checked'=>true,'label'=>' Super Admin'));
																} else {
																	echo $this->Form->control('super_admin', array('type'=>'checkbox', 'checked'=>false,'label'=>' Super Admin'));
																} ?>

																<?php if ($each_role['renewal_verification']=='yes') {
																	echo $this->Form->control('renewal_verification', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Renewal Scrutiny',));
																} else {
																	echo $this->Form->control('renewal_verification', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Renewal Scrutiny',));
																} ?>

																<?php if ($each_role['renewal_allocation']=='yes') {
																	echo $this->Form->control('renewal_allocation', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Renewal Allocation', ));
																} else {
																	echo $this->Form->control('renewal_allocation', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Renewal Allocation',));
																} ?>

															</div>

															<div class="col-md-4">

																<?php if ($each_role['form_verification_home']=='yes') {
																	echo $this->Form->control('form_verification_home', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Form Scrutiny Home',));
																} else {
																	echo $this->Form->control('form_verification_home', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Form Scrutiny Home',));
																} ?>

																<?php if ($each_role['allocation_home']=='yes') {
																	echo $this->Form->control('allocation_home', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Allocation Home',));
																} else {
																	echo $this->Form->control('allocation_home', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Allocation Home',));
																} ?>

																<?php if ($each_role['view_reports']=='yes') {
																	echo $this->Form->control('view_reports', array('type'=>'checkbox', 'checked'=>true, 'label'=>' View Reports'));
																} else {
																	echo $this->Form->control('view_reports', array('type'=>'checkbox', 'checked'=>false, 'label'=>' View Reports'));
																} ?>

																<?php if ($each_role['unlock_user']=='yes') {
																	echo $this->Form->control('unlock_user', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Unlock User'));
																} else {
																	echo $this->Form->control('unlock_user', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Unlock User'));
																} ?>

																<?php if ($each_role['transfer_appl']=='yes') {
																	echo $this->Form->control('transfer_appl', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Transfer Application'));
																} else {
																	echo $this->Form->control('transfer_appl', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Transfer Application'));
																} ?>

																<?php if ($each_role['pao']=='yes') {
																	echo $this->Form->control('pao', array('type'=>'checkbox', 'checked'=>true, 'label'=>' PAO/DDO', 'id'=>'pao',));
																} else {
																	echo $this->Form->control('pao', array('type'=>'checkbox', 'checked'=>false, 'label'=>' PAO/DDO', 'id'=>'pao',));
																} ?>

																<!-- Create new role "once_update_permission" to show aadhar updation request window to admin
																	on 03-02-2018 by Amol -->
																<?php /* if ($each_role['once_update_permission']=='yes') {
																	echo $this->Form->control('once_update_permission', array('type'=>'checkbox', 'checked'=>true, 'label'=>'Aadhar update Permission','escape'=>false));
																} else {
																	echo $this->Form->control('once_update_permission', array('type'=>'checkbox', 'checked'=>false, 'label'=>'Aadhar update Permission','escape'=>false));
																} */ ?>

																<!-- created new role to show Old application data entry window to admin user
																on 07-02-2018 by Amol -->
																<?php if ($each_role['old_appln_data_entry']=='yes') {
																	echo $this->Form->control('old_appln_data_entry', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Old Data Entry'));
																} else {
																	echo $this->Form->control('old_appln_data_entry', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Old Data Entry'));
																} ?>

																<!-- created new role to show Feedbacks window to admin user
																on 12-06-2018 by Amol -->
																<?php if ($each_role['feedbacks']=='yes') {
																	echo $this->Form->control('feedbacks', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Feedbacks'));
																} else {
																	echo $this->Form->control('feedbacks', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Feedbacks'));
																} ?>

																<!-- created new role SO inspection
																on 01-03-2018 by Amol -->
																<?php  if ($each_role['so_inspection']=='yes') {
																	echo $this->Form->control('so_inspection', array('type'=>'checkbox', 'checked'=>true, 'label'=>' SO In-Charge','id'=>'so_inspection'));
																} else {
																	echo $this->Form->control('so_inspection', array('type'=>'checkbox', 'checked'=>false, 'label'=>' SO In-Charge','id'=>'so_inspection'));
																} ?>

																<!-- created new role SMD inspection
																on 01-03-2018 by Amol -->
																<?php /* if ($each_role['smd_inspection']=='yes') {
																	echo $this->Form->control('smd_inspection', array('type'=>'checkbox', 'checked'=>true, 'label'=>'SMD In-Charge'));
																} else {
																	echo $this->Form->control('smd_inspection', array('type'=>'checkbox', 'checked'=>false, 'label'=>'SMD In-Charge'));
																} */ ?>

																<?php  if ($each_role['site_inspection_pp']=='yes') {
																	echo $this->Form->control('site_inspection_pp', array('type'=>'checkbox', 'checked'=>true, 'label'=>' Site Inspection(P.P)','id'=>'site_inspection_pp'));
																} else {
																	echo $this->Form->control('site_inspection_pp', array('type'=>'checkbox', 'checked'=>false, 'label'=>' Site Inspection(P.P)','id'=>'site_inspection_pp'));
																} ?>

																<?php  if ($each_role['so_grant_pp']=='yes') {
																	echo $this->Form->control('so_grant_pp', array('type'=>'checkbox', 'checked'=>true, 'label'=>' SO Grant(P.P)','id'=>'so_grant_pp'));
																} else {
																	echo $this->Form->control('so_grant_pp', array('type'=>'checkbox', 'checked'=>false, 'label'=>' SO Grant(P.P)','id'=>'so_grant_pp'));
																} ?>

															</div>
															<div class="clearfix"></div>
														</div>
														</div>



														<div class="alert alert-info" id="both_user_roles_list_box">
															<label> Common Roles List : </label>
																<div class="row">
																	<div class="col-md-6">
																		<?php if ($each_role['set_roles']=='yes') {
																			echo $this->Form->control('set_roles', array('type'=>'checkbox', 'checked'=>true, 'label'=>' User Roles',));
																		} else {
																			echo $this->Form->control('set_roles', array('type'=>'checkbox', 'checked'=>false, 'label'=>' User Roles',));
																		} ?>

																	</div>
																</div>

															<div class="clearfix"></div>
														</div>



														<!-- Start Create LMIS Roles List for user role set (done by pravin 16/11/2017) -->

														<div class="alert alert-dark" id="lmis_user_roles_list_box">
															<label> LIMS Roles List : </label>
															<div class="row">
															<div class="col-md-6">

																<?php if ($each_role['sample_inward']=='yes') {
																	echo $this->Form->control('sample_inward', array('type'=>'checkbox', 'checked'=>true,'label'=>' Sample Inward'));
																} else {
																	echo $this->Form->control('sample_inward', array('type'=>'checkbox', 'checked'=>false,'label'=>' Sample Inward'));
																} ?>

																<?php if ($each_role['sample_forward']=='yes') {
																	echo $this->Form->control('sample_forward', array('type'=>'checkbox', 'checked'=>true,'label'=>' Sample Forward'));
																} else {
																	echo $this->Form->control('sample_forward', array('type'=>'checkbox', 'checked'=>false,'label'=>' Sample Forward'));
																} ?>

																<?php if ($each_role['sample_allocated']=='yes') {
																	echo $this->Form->control('sample_allocated', array('type'=>'checkbox', 'checked'=>true,'label'=>' Allocate Sample'));
																} else {
																	echo $this->Form->control('sample_allocated', array('type'=>'checkbox', 'checked'=>false,'label'=>' Allocate Sample'));
																} ?>

																<?php if ($each_role['sample_result_approval']=='yes') {
																	echo $this->Form->control('sample_result_approval', array('type'=>'checkbox', 'checked'=>true,'label'=>' Approve Results'));
																} else {
																	echo $this->Form->control('sample_result_approval', array('type'=>'checkbox', 'checked'=>false,'label'=>' Approve Results'));
																} ?>

																<?php if ($each_role['RO']=='yes') {
																	echo $this->Form->control('RO', array('type'=>'checkbox', 'checked'=>true,'label'=>' Regional Officer'));
																} else {
																	echo $this->Form->control('RO', array('type'=>'checkbox', 'checked'=>false,'label'=>' Regional Officer'));
																} ?>

																<?php if ($each_role['reports']=='yes') {
																	echo $this->Form->control('reports', array('type'=>'checkbox', 'checked'=>true,'label'=>' Reports'));
																} else {
																	echo $this->Form->control('reports', array('type'=>'checkbox', 'checked'=>false,'label'=>' Reports'));
																} ?>

															</div>



															<div class="col-md-6">

																<?php if ($each_role['administration']=='yes') {
																	echo $this->Form->control('administration', array('type'=>'checkbox', 'checked'=>true,'label'=>' Administration'));
																} else {
																	echo $this->Form->control('administration', array('type'=>'checkbox', 'checked'=>false,'label'=>' Administration'));
																} ?>

																<?php if ($each_role['out_forward']=='yes') {
																	echo $this->Form->control('out_forward', array('type'=>'checkbox', 'checked'=>true,'label'=>' Out Forward'));
																} else {
																	echo $this->Form->control('out_forward', array('type'=>'checkbox', 'checked'=>false,'label'=>' Out Forward'));
																} ?>

																<?php if ($each_role['sample_testing_progress']=='yes') {
																	echo $this->Form->control('sample_testing_progress', array('type'=>'checkbox', 'checked'=>true,'label'=>' Testing Process'));
																} else {
																	echo $this->Form->control('sample_testing_progress', array('type'=>'checkbox', 'checked'=>false,'label'=>' Testing Process'));
																} ?>

																<?php if ($each_role['finalized_sample']=='yes') {
																	echo $this->Form->control('finalized_sample', array('type'=>'checkbox', 'checked'=>true,'label'=>' Finalization'));
																} else {
																	echo $this->Form->control('finalized_sample', array('type'=>'checkbox', 'checked'=>false,'label'=>' Finalization'));
																} ?>

																<?php if ($each_role['cal']=='yes') {
																	echo $this->Form->control('CAL', array('type'=>'checkbox', 'checked'=>true,'label'=>' CAL'));
																} else {
																	echo $this->Form->control('CAL', array('type'=>'checkbox', 'checked'=>false,'label'=>' CAL'));
																} ?>

																<?php if ($each_role['generate_inward_letter']=='yes') {
																	echo $this->Form->control('generate_inward_letter', array('type'=>'checkbox', 'checked'=>true,'label'=>' Inward Letter'));
																} else {
																	echo $this->Form->control('generate_inward_letter', array('type'=>'checkbox', 'checked'=>false,'label'=>' Inward Letter'));
																} ?>

																<?php if ($each_role['dashboard']=='yes') {
																	echo $this->Form->control('dashboard', array('type'=>'checkbox', 'checked'=>true,'label'=>' Dashboard'));
																} else {
																	echo $this->Form->control('dashboard', array('type'=>'checkbox', 'checked'=>false,'label'=>' Dashboard'));
																} ?>

															</div>

															</div>

															<h5 class="fwBold"> Office Type : </h5>
															<?php if ($each_role['user_flag'] != null) {

																$options=array('RO'=>'RO','SO'=>'SO','RAL'=>'RAL','CAL'=>'CAL','HO'=>'HO');
																$attributes=array('legend'=>false,  'value'=>$each_role['user_flag'], 'id'=>'division','class'=>"ml17");
																echo $this->Form->radio('user_flag',$options,$attributes);

															} else {

																$options=array('RO'=>'RO','SO'=>'SO','RAL'=>'RAL','CAL'=>'CAL','HO'=>'HO');
																$attributes=array('legend'=>false,  'id'=>'division','class'=>"ml17");
																echo $this->Form->radio('user_flag',$options,$attributes);

															} ?>


															<div class="clearfix"></div>
															<!-- end -->
														</div>
														<?php } ?>
														<?php echo $this->Form->control('Update Roles', array('type'=>'submit', 'id'=>'update_roles_btn', 'name'=>'update_roles_btn', 'label'=>false,'class'=>'btn btn-success')); ?>
														</div>
														<div class="clearfix"></div>


													</div>

													<!--check user id in allocation and renewal allocation table and application grant table before remove MO/SMO role from user
													Done by pravin 02-09-2017-->
													<div id="mo_allocated_list">
														<Div><h5>Applications in which the user <?php echo $user_name.' ('. $user_id .')'; ?> has taken part for Scrutiny<h5></div>
														<table class="table b1s" id="mo_allocated_list_table">
															<thead>
																<tr>
																	<th>Sr.No</th>
																	<th>Appl. Type</th>
																	<th>Appl. ID</th>
																	<th>RO ID</th>
																	<th>RO Office</th>
																</tr>
															</thead>

															<tbody>
																<?php

																$i = 0;$j = 0;$k = 0;
																foreach($mo_allocated_running_application_list as $application_id) {
																foreach($mo_allocated_to_under_ro_id as $ro_id) {
																foreach($mo_allocation_ro_office_name_list as $ro_office) {
																foreach($mo_appl_type as $type) {?>

																<tr>
																	<td><?php echo 	$i+1; ?></td>
																	<td><?php echo 	$type; ?></td>
																	<td><?php echo 	$application_id; ?></td>
																	<td><?php echo  $ro_id;?></td>
																	<td><?php echo 	$ro_office; ?></td>
																</tr>

																<?php break;}break;}break;}$i =$i+1; $j =$j+1; $k =$k+1;} ?>
															</tbody>
														</table>

													</div>


													<!--check user id in allocation and renewal allocation table and application grant table before remove inspection office role from user
													Done by pravin 02-09-2017-->
													<div id="io_allocated_list">
														<Div><h5>Applications in which the user <?php echo $user_name.' ('. $user_id .')'; ?>has taken part for Site Inspection<h5></div>

														<table class="table" id="io_allocated_list_table">
															<thead>
																<tr>
																	<th>Sr.No</th>
																	<th>Appl. Type</th>
																	<th>Application ID</th>
																	<th>Under RO ID</th>
																	<th>Under RO Office</th>
																</tr>
															</thead>

															<tbody>
																<?php
																$i = 0;$j = 0;$k = 0;
																foreach($io_allocated_running_application_list as $application_id) {
																foreach($io_allocated_to_under_ro_id as $ro_id) {
																foreach($io_allocation_ro_office_name_list as $ro_office) {
																foreach($mo_appl_type as $type) { ?>

																<tr>
																	<td><?php echo 	$i+1; ?></td>
																	<td><?php echo 	$type; ?></td>
																	<td><?php echo 	$application_id; ?></td>
																	<td><?php echo  $ro_id;?></td>
																	<td><?php echo 	$ro_office; ?></td>
																</tr>

																<?php break;}break;}break;}$i =$i+1; $j =$j+1; $k =$k+1;} ?>
															</tbody>
														</table>

													</div>

													<!--check user id in allocation and renewal allocation table and application grant table before remove HO MO/SMO role from user
													Done by pravin 04-09-2017-->
													<div id="ho_mo_allocated_list">
														<Div><h5>Applications in which the user <?php echo $user_name.' ('. $user_id .')'; ?>has taken part for scrutiny (HO-QC)<h5></div>

														<table class="table" id="ho_mo_allocated_list_table">
															<thead>
																<tr>
																	<th>Sr.No</th>
																	<th>Appl. Type</th>
																	<th>Application ID</th>
																	<th>DY.AMA Name</th>
																	<th>DY.AMA ID</th>
																</tr>
															</thead>

															<tbody>
																<?php
																$i = 0;$j = 0;$k = 0;
																foreach($ho_mo_allocated_running_application_list as $application_id) {
																foreach($ho_mo_allocated_dy_ama_list as $dyama_name) {
																foreach($ho_mo_allocated_to_under_dy_ama as $dyama_id) {
																foreach($ho_mo_appl_type as $type) { ?>

																<tr>
																	<td><?php echo 	$i+1; ?></td>
																	<td><?php echo 	$type; ?></td>
																	<td><?php echo 	$application_id; ?></td>
																	<td><?php echo  $dyama_name;?></td>
																	<td><?php echo 	$dyama_id; ?></td>
																</tr>
																<?php break;}break;}break;}$i =$i+1; $j =$j+1; $k =$k+1;} ?>
															</tbody>
														</table>
													</div>
												<?php } ?>
											<?php echo $this->Form->end(); ?>
										</div>
									</div>
								</div>
							</div>
						</section>
				</div>

			<?php

				$dyama_set_role_detail = "";
				$jtama_set_role_detail = "";
				$ama_set_role_detail = "";
				$ro_office_details = "";
				$user_id = "";
				$ro_office = "";
				$so_office_details = "";
				$mo_allocated_running_application_list = "";
				$mo_renewal_allocated_running_application_list = "";
				$io_allocated_running_application_list = "";
				$io_renewal_allocated_running_application_list = "";
				$ho_mo_allocated_running_application_list = "";
				$user_division_type = "";
				$pao_pending_works = "";

			 ?>

			<input type="hidden" id="dyama_set_role_detail" value="<?php echo $dyama_set_role_detail; ?>">
			<input type="hidden" id="jtama_set_role_detail" value="<?php echo $jtama_set_role_detail; ?>">
			<input type="hidden" id="ama_set_role_detail" value="<?php echo $ama_set_role_detail; ?>">
			<input type="hidden" id="ro_office_details" value="<?php echo $ro_office_details; ?>">
			<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
			<input type="hidden" id="ro_office" value="<?php echo $ro_office; ?>">
			<input type="hidden" id="so_office_details" value="<?php echo $so_office_details; ?>">
			<input type="hidden" id="mo_allocated_running_application_list" value="<?php echo $mo_allocated_running_application_list; ?>">
			<input type="hidden" id="mo_renewal_allocated_running_application_list" value="<?php echo $mo_renewal_allocated_running_application_list; ?>">
			<input type="hidden" id="io_allocated_running_application_list" value="<?php echo $io_allocated_running_application_list; ?>">
			<input type="hidden" id="io_renewal_allocated_running_application_list" value="<?php echo $io_renewal_allocated_running_application_list; ?>">
			<input type="hidden" id="ho_mo_allocated_running_application_list" value="<?php echo $ho_mo_allocated_running_application_list; ?>">
			<input type="hidden" id="user_division_type" value="<?php echo $user_division_type; ?>">
			<input type="hidden" id="pao_pending_works" value="<?php echo $pao_pending_works; ?>">


<?php echo $this->Html->script('Roles/edit_roles') ?>
