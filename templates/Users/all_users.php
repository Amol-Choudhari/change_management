<?php ?>

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6"><label class="badge badge-primary">All Users</label></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
							<li class="breadcrumb-item active">All Users</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<label><?php echo $this->Html->link('Add New', array('controller' => 'users', 'action'=>'add_user'),array('class'=>'add_btn btn btn-success')); ?></label>
							<div class="card card-info">
								<div class="card-header"><h3 class="card-title-new">Given Below is list of All Users</h3></div>
									<div class="card-body">
										<div class="masters_list">
											<?php echo $this->Form->create(); ?>
												<div class="panel panel-primary filterable">
													<table id="all_users_list" class="table m-0 table-striped table-bordered">
														<thead class="tablehead">
																	<tr>
																		<th>Sr.No.</th>
																		<th>Name</th>
																		<th>Email Id</th>
																		<th>Division</th><!-- added on 27-07-2018 by Amol -->
																		<th>Posted Office</th>
																		<th>Action</th>
																	</tr>
														</thead>
															<tbody>
													<?php if (!empty($all_users)) {
															$i=0;
															$sr_no=1;
															foreach ($all_users as $each_user) { ?>
															<?php  if ($each_user['status'] == 'active') { ?>
														
															<tr>
																<td><?php echo $sr_no; ?></td>
																<td><?php echo $each_user['f_name'].' '; echo $each_user['l_name'];?></td>
																<td><?php echo base64_decode($each_user['email']); //for email encoding ?></td>
																<td><?php echo $each_user['division']; ?></td>
																<td><?php if (!empty($posted_ro_office[$i])) { echo $posted_ro_office[$i];} ?></td>
																<td><?php echo $this->Html->link('', array('controller' => 'users', 'action'=>'fetch_user_id', $each_user['id']),array('class'=>'far fa-edit','title'=>'Edit')); ?> |
																	<?php if ($each_user['status'] == 'active') {
																		      echo $this->Html->link('', array('controller' => 'users', 'action'=>'change_status_user_id', $each_user['id']),array('class'=>'fas fa-user-times deactivate_button','title'=>'Deactivate'));
																	      } else {
																		      echo $this->Html->link('', array('controller' => 'users', 'action'=>'change_status_user_id', $each_user['id']),array('class'=>'fas fa-check activate_button','title'=>'Activate'));
																	      }
																    ?>
																</td>
															</tr>
														<?php } else { ?>

															<tr class="cRed">
																<td class="borred"><?php echo $sr_no; ?></td>
																<td><?php echo $each_user['f_name'].' '; echo $each_user['l_name'];?></td>
																<td><?php echo base64_decode($each_user['email']); //for email encoding ?></td>
																<td><?php echo $each_user['division'];?></td>
																<td><?php if (!empty($posted_ro_office[$i])) { echo $posted_ro_office[$i];} ?></td>
																<td><?php echo $this->Html->link('', array('controller' => 'users', 'action'=>'fetch_user_id', $each_user['id']),array('class'=>'cRed far fa-edit','title'=>'Edit')); ?> |
																	<?php if ($each_user['status'] == 'active') {
																		      echo $this->Html->link('', array('controller' => 'users', 'action'=>'change_status_user_id', $each_user['id']),array('class'=>'fas fa-user-times deactivate_button cRed','title'=>'Deactivate'));
																	      } else {
																		      echo $this->Html->link('', array('controller' => 'users', 'action'=>'change_status_user_id', $each_user['id']),array('class'=>'fas fa-check activate_button cRed','title'=>'Activate'));
																	      }
																    ?>
																</td>
															</tr>
														<?php } ?>
														<?php $sr_no++; $i=$i+1;} } ?>
													</tbody>
												</table>
											</div>
										<?php echo $this->Form->end(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>

<?php echo $this->Html->script('Users/all_users'); ?>
