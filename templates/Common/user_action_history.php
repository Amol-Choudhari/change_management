<?php $username = $_SESSION['username']; ?>
	<div class="content-wrapper">
    	<div class="content-header">
      		<div class="container-fluid">
        		<div class="row mb-2">
          			<div class="col-sm-6"><label class="badge badge-primary">User Action History</label></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
							<li class="breadcrumb-item active">User Action Logs</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
						<?php echo $this->Form->create(); ?>
						<div class="card card-dark">
							<div class="card-header"><h3 class="card-title-new">Given Below Is User Action History</h3></div>
							<div class="card-body">
								<table id="user_logs_table" class="table m-0 table-bordered table-hover table-striped">
									<thead class="tablehead">
										<tr>
											<!--<th>Serial Number</th>-->
											<th>User Id</th>
											<th>IP Address</th>
											<th>Date and Time</th>
											<th>Action Performed</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if (!empty($get_user_actions)) {
												$i=1;
											foreach($get_user_actions as $user_log){ ?>
											<tr>
												<!--<td><?php echo $i;?></td>-->
												<td><?php if ($userType == 'User') { ?> 
														<?php echo base64_decode($user_log['user_id']); //for email encoding ?>
													<?php } elseif ($userType == 'Primary' || $userType == 'Secondary') { ?>
														<?php echo $user_log['customer_id']; ?>
													<?php } else { ?>
														<?php echo $user_log['chemist_id']; ?>
													<?php } ?>
												</td>
												<td><?php echo $user_log['ipaddress'];?></td>
												<td><?php echo $user_log['created']; ?></td>
												<td><?php echo ucwords($user_log['action_perform']);?></td>
												<td><?php
														$remark = ucwords($user_log['status']);
														if ($remark == 'Success') {
															$badge = "success";
														} elseif ($remark == 'Failed') {
															$badge = "danger";
														} else {
															$badge = "info";
														}
														echo "<span class='badge badge-".$badge."'>".$remark."</span>";
													?>
												</td>
											</tr>
										<?php $i=$i+1; } }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</section>
	</div>
	<?php echo $this->Html->script('common_modules/user_action_history'); ?>
