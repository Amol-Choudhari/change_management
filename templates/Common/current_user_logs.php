<?php $username = $_SESSION['username']; ?>
	
	<div class="content-wrapper">
    	<div class="content-header">
      		<div class="container-fluid">
        		<div class="row mb-2">
          			<div class="col-sm-6"><label class="badge badge-primary">Log History</label></div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item">
									<?php if ($userType == 'User') { ?>
										<?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?>
								  	<?php } elseif ($userType == 'Primary') { ?>
										<?php echo $this->Html->link('Dashboard', array('controller' => 'customers', 'action'=>'primary_home'));?>
								   	<?php } elseif ($userType == 'Chemist') { ?>
										<?php echo $this->Html->link('Dashboard', array('controller' => 'chemist', 'action'=>'home'));?>
								   	<?php } elseif ($userType == 'Secondary') { ?>
										<?php echo $this->Html->link('Dashboard', array('controller' => 'customers', 'action'=>'secondary_home'));?>
									<?php }?>
								</li>
						  		<li class="breadcrumb-item active">Log History</li>
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
					  				<div class="card-header"><h3 class="card-title-new">Given Below is your log history</h3></div>
									<div class="card-body">
										<table id="log_history" class="table m-0 table-bordered table-hover table-striped">
											<thead class="boldtext tablehead">
												<tr>
													<th>Date</th>
													<th>User Id</th>
													<th>TimeIn</th>
													<th>TimeOut</th>
													<th>Duration</th>
													<th>Remark</th>
													<th>IP Address</th>
												</tr>
											</thead>
											<tbody>
												<?php if (!empty($currentLogs)) {

														$i=0;
														foreach ($currentLogs as $logs) { ?>
														
														<?php 
															$time_in = strtotime($logs['time_in']);
															$time_out = strtotime($logs['time_out']);
															$login_duration = $time_out - $time_in;
														?>
															
														<tr>
															<td><?php echo $logs['date'];?></td>
															<td><?php if ($userType == 'User') { ?> 
																	<?php echo base64_decode($logs['email_id']); //for email encoding ?>
																<?php } else { ?>
																	<?php echo $logs['customer_id']; ?>
																<?php } ?>
															</td>
															<td><?php echo $logs['time_in'];?></td>
															<td><?php //updated the logic on 20-11-2020 by Amol
																if(!empty($logs['time_out'])){
																	echo $logs['time_out'];										
																}else{ echo '---'; } ?>
															</td>
															<td>
																<?php
																//updated the logic on 20-11-2020 by Amol
																	if($i==0){											
																		echo "Current Session";
																	}else{
																		if(!empty($logs['time_out'])){												
																			echo round($login_duration/60)." min ".($login_duration%60)." sec";
																		}else{
																			echo "0 min 0 sec";												
																		}
																	}							
																?>	
															</td>
															<td><?php
																$remark = $logs['remark'];
																if($remark == 'Success')
																	$badge = "success";
																else if ($remark == 'Failed')
																	$badge = "danger";
																else
																	$badge = "info";
																echo "<span class='badge badge-".$badge."'>".$remark."</span>";
																?>
															</td>
															<td><?php echo $logs['ip_address'];?></td>
														</tr>
												<?php $i=$i+1; } }?>
											</tbody>
										</table>
									<?php echo $this->Form->end(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
		</section>
	</div>
	
	<?php echo $this->Html->script('Common/current_user_logs'); ?>
