<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Update Firm Details</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item active">All Primary Firms</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<section class="col-lg-12 connectedSortable">
					<?php echo $this->Form->create(); ?>
						<div class="card card-cyan">
							<div class="card-header"><h3 class="card-title-new">List of Registered Applicants</h3></div>
								<div class="card-body">
									<table id = "user_logs_table" class="table table-hover table-bordered table-striped">
										<thead class="tablehead">
											<tr>
												<th>Sr.No</th>
												<?php if ($for_firm == 'primary') { ?>
													<th>Primary Applicant ID</th>
													<th>Applicant Name</th>

												<?php } else { ?>
													<th>Applicant ID</th>
													<th>Firm Name</th>
													<th>District</th>
												<?php } ?>

												<th>Last Updated</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php if ($for_firm == 'primary') { ?>

													<?php  $sr_no=1;
													foreach ($primary_id as $data) { ?>
													<tr>
														<td><?php echo 	$sr_no;?></td>
														<td><?php echo 	$data['customer_id']; ?></td>
														<td><?php echo  $data['f_name']." ".$data['l_name']; ?></td>
														<td><?php echo  $data['modified']; ?></td>
														<td><?php echo $this->Html->link('Edit', array('controller' => 'othermodules', 'action'=>'fetch_firm_id', $data['id']),array('class'=>'btn btn-primary')); ?>
													</tr>
													<?php $sr_no++; } ?>

											<?php } else { ?>

													<?php $sr_no=1; foreach($datalist as $eachdata){ ?>
														<?php foreach($eachdata as $data){

															$email = $data['email'];
															$femail = $data['email'];
															?>
															<tr>
																<td><?php echo 	$sr_no;?></td>
																<td><?php echo 	$data['customer_id']; ?></td>
																<td><?php echo  $data['firm_name']; ?></td>
																<td><?php echo  $data['district_name']; ?></td>
																<td><?php echo  $data['modified']; ?></td>
																<td><?php echo $this->Html->link('Edit', array('controller' => 'othermodules', 'action'=>'fetch_firm_id', $data['id']),array('class'=>'btn btn-primary')); ?>
															</tr>
														<?php $sr_no++; } ?>
													<?php } ?>

											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</section>
					</div>
				</div>
			</section>
		</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('othermodules/firms_list_to_update'); ?>
