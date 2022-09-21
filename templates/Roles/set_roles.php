<?php ?>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6"><label class="badge badge-primary">User Roles</label></div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-right">
								<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
								<li class="breadcrumb-item active"> User Roles</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
				<section class="content form-middle">	
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-10">
								<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'set_roles_form')); ?>
									<div class="card card-cyan">
				           				<div class="card-header"><h3 class="card-title-new">Set User Roles</h3></div>
											<div class="card-body mb-3">
												<label>Select User & Assign : </label>
												<?php echo $this->Form->control('user_list', array('type'=>'select', 'id'=>'user_list', 'options'=>$find_available_user, 'empty'=>'Select', 'label'=>false, 'class'=>'form-control select_user')); ?>  
														<div id="user_division"></div> <!-- create div for to show selected user division on ajax call (done by pravin 16/11/2017)-->
															<div class="clearfix"></div>
																<div class="alert alert-primary" id="dmi_user_roles_list_box">
																	<label> DMI Roles List : </label>
																		<div class="row">
																			<div class="col-md-4">
																				<?php echo $this->Form->control('add_user', array('type'=>'checkbox','label'=>' Add user')); ?>
																				<?php echo $this->Form->control('page_draft', array('type'=>'checkbox','label'=>' Page (Draft only)',)); ?>
																				<?php echo $this->Form->control('page_publish', array('type'=>'checkbox','label'=>' Page Publish',)); ?>
																				<?php echo $this->Form->control('menus', array('type'=>'checkbox','label'=>' Menus')); ?>
																				<?php echo $this->Form->control('mo_smo_inspection', array('type'=>'checkbox','label'=>' Scrutiny Officer',)); ?>
																				<?php echo $this->Form->control('io_inspection', array('type'=>'checkbox', 'label'=>' Inspection Officer',)); ?>
																				<?php echo $this->Form->control('ro_inspection', array('type'=>'checkbox', 'label'=>' RO In-Charge',)); ?>
																				<?php echo $this->Form->control('allocation_mo_smo', array('type'=>'checkbox', 'label'=>' Allocate to Scrutiny',)); ?>
																				<?php echo $this->Form->control('allocation_io', array('type'=>'checkbox','label'=>' Allocate to IO',)); ?>
																				<?php echo $this->Form->control('reallocation', array('type'=>'checkbox','label'=>' Re-Allocate',)); ?>
																			</div>


																			<div class="col-md-4">

																				<?php echo $this->Form->control('file_upload', array('type'=>'checkbox','label'=>' Upload Files',)); ?>
																				<?php echo $this->Form->control('dy_ama', array('type'=>'checkbox', 'label'=>' Dy. AMA (QC)', 'id'=>'dy_ama',)); ?>
																				<?php echo $this->Form->control('ho_mo_smo', array('type'=>'checkbox', 'label'=>' HO Scrutiny Officer',)); ?>
																				<?php echo $this->Form->control('jt_ama', array('type'=>'checkbox','label'=>' Jt. AMA', 'id'=>'jt_ama',)); ?>
																				<?php echo $this->Form->control('ama', array('type'=>'checkbox', 'label'=>' AMA', 'id'=>'ama',)); ?>
																				<?php //echo $this->Form->control('allocation_dy_ama', array('type'=>'checkbox','label'=>'Forward to Dy. AMA',)); ?>
																				<?php echo $this->Form->control('allocation_ho_mo_smo', array('type'=>'checkbox', 'label'=>' Allocate to HO Scrutiny',)); ?>
																				<?php //echo $this->Form->control('allocation_jt_ama', array('type'=>'checkbox', 'label'=>'Forward to Jt. AMA',)); ?>
																				<?php //echo $this->Form->control('allocation_ama', array('type'=>'checkbox','label'=>'Forward to AMA',)); ?>
																				<?php echo $this->Form->control('masters', array('type'=>'checkbox','label'=>' Masters')); ?>
																				<?php echo $this->Form->control('super_admin', array('type'=>'checkbox','label'=>' Super Admin')); ?>
																				<?php echo $this->Form->control('renewal_verification', array('type'=>'checkbox','label'=>' Renewal Scrutiny',)); ?>
																				<?php echo $this->Form->control('renewal_allocation', array('type'=>'checkbox','label'=>' Renewal Allocation',)); ?>
																			</div>

																			<div class="col-md-4">

																				<?php echo $this->Form->control('form_verification_home', array('type'=>'checkbox', 'label'=>' Form Scrutiny Home',)); ?>
																				<?php echo $this->Form->control('allocation_home', array('type'=>'checkbox', 'label'=>' Allocation Home',)); ?>
																				<?php echo $this->Form->control('view_reports', array('type'=>'checkbox', 'label'=>' View Reports',)); ?>
																				<?php echo $this->Form->control('unlock_user', array('type'=>'checkbox', 'label'=>' Unlock User')); ?>
																				<?php echo $this->Form->control('transfer_appl', array('type'=>'checkbox', 'label'=>' Transfer Application')); ?>
																				<?php echo $this->Form->control('pao', array('type'=>'checkbox', 'label'=>' PAO/DDO',)); ?>
																				<?php echo $this->Form->control('feedbacks', array('type'=>'checkbox', 'label'=>' Feedbacks',)); ?>
																				<?php //echo $this->Form->control('once_update_permission', array('type'=>'checkbox', 'label'=>'Aadhar update Permission',)); ?>
																				<?php echo $this->Form->control('old_appln_data_entry', array('type'=>'checkbox', 'label'=>' Old Applications Data Entry',)); ?>
																				<?php echo $this->Form->control('so_inspection', array('type'=>'checkbox', 'label'=>' SO In-Charge',)); ?>
																				<?php //echo $this->Form->control('smd_inspection', array('type'=>'checkbox', 'label'=>'SMD In-Charge',)); ?>
																				<?php echo $this->Form->control('site_inspection_pp', array('type'=>'checkbox', 'label'=>' Site Inspection(P.P)',)); ?>
																				<?php echo $this->Form->control('so_grant_pp', array('type'=>'checkbox', 'label'=>' SO Grant(P.P)',)); ?>
																			</div>
																			<div class="clearfix"></div>
																		</div>

																		<div class="alert alert-success" id="both_user_roles_list_box">
																			<label> Common Roles List : </label>
																				<div class="row">
																					<div class="col-md-6">
																						<?php echo $this->Form->control('set_roles', array('type'=>'checkbox', 'label'=>' User Roles',)); ?>
																					</div>
																				</div>
																			<div class="clearfix"></div>
																		</div>

																		<!-- Start Create LMIS Roles List for user role set (done by pravin 16/11/2017) -->
																		<div class="user_role_css" id="lmis_user_roles_list_box">
																			<label> LIMS Roles List : </label>
																				<div class="row">
																					<div class="col-md-6">
																						<?php echo $this->Form->control('sample_inward', array('type'=>'checkbox', 'label'=>' Sample Inward',)); ?>
																						<?php echo $this->Form->control('sample_forward', array('type'=>'checkbox', 'label'=>' Sample Forward',)); ?>
																						<?php echo $this->Form->control('sample_allocated', array('type'=>'checkbox', 'label'=>' Sample Allocated',)); ?>
																						<?php echo $this->Form->control('sample_result_approval', array('type'=>'checkbox', 'label'=>' Sample Result Approval',)); ?>
																						<?php echo $this->Form->control('RO', array('type'=>'checkbox', 'label'=> ' RO',)); ?>
																						<?php echo $this->Form->control('reports', array('type'=>'checkbox', 'label'=>' Reports',)); ?>
																					</div>

																					<div class="col-md-6">
																						<?php echo $this->Form->control('administration', array('type'=>'checkbox', 'label'=>' Administration',)); ?>
																						<?php echo $this->Form->control('out_forward', array('type'=>'checkbox', 'label'=>' Forward',)); ?>
																						<?php echo $this->Form->control('sample_testing_progress', array('type'=>'checkbox','label'=>' Sample Testing In progress',)); ?>
																						<?php echo $this->Form->control('finalized_sample', array('type'=>'checkbox', 'label'=>' Finalize Sample',)); ?>
																						<?php echo $this->Form->control('CAL', array('type'=>'checkbox', 'label'=>' CAL',)); ?>
																						<?php echo $this->Form->control('generate_inward_letter', array('type'=>'checkbox', 'label'=>' Generate Inward Letter',)); ?>
																						<?php echo $this->Form->control('dashboard', array('type'=>'checkbox', 'label'=>' Dashboard',)); ?>
																					</div>
																				</div>
																				<h5 class="fwBold"> Office Type : </h5>
																					<?php

																						$options=array('RO'=>'RO','SO'=>'SO','RAL'=>'RAL','CAL'=>'CAL','HO'=>'HO');
																						$attributes=array('legend'=>false,  'id'=>'division','class'=>"ml17");
																						echo $this->form->radio('user_flag',$options,$attributes);
																					?>
																		</div>
																		<div class="clearfix"></div>

															<?php echo $this->Form->control('Set Roles', array('type'=>'submit', 'name'=>'set_roles_btn', 'id'=>'set_roles_btn', 'label'=>false,'class'=>'btn btn-success')); ?>

														</div>
													<?php echo $this->Form->end(); ?>
												</div>
											</div>
										</div>
									</div>
						</section>
				</div>

				<input type="hidden" id="dyama_set_role_detail" value="<?php echo $dyama_set_role_detail; ?>">
				<input type="hidden" id="jtama_set_role_detail" value="<?php echo $jtama_set_role_detail; ?>">
				<input type="hidden" id="ama_set_role_detail" value="<?php echo $ama_set_role_detail; ?>">


				<?php echo $this->Html->script('Roles/set_roles'); ?>
