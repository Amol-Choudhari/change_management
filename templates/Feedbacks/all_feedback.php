<?php ?>
	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6"><label class="badge badge-primary">Feedbacks</label></div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
								<li class="breadcrumb-item active">All Feedbacks</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12 mb-1"><?php echo $this->Html->link('Export',array('controller'=>'Feedbacks','action'=>'download'), array('target'=>'_blank','class'=>'btn export btn btn-secondary smallButtonLikeBadge float-right')); ?></div>
						<?php echo $this->Form->create(); ?>	
							<div class="col-md-12">
								<div class="card card-Lightblue">
									<div class="card-header"><h3 class="card-title-new">All Feedbacks</h3></div>
										<div class="form-horizontal">
											<div class="card-body">
												<div class="row">
													<div class="masters_list">
														<table id="feedbacks_list_table" class="table m-0 table-bordered">
															<thead class="tablehead">
																<tr>
																	<th>Sr.</th>
																	<th>Firstname</th>
																	<th>Lastname</th>
																	<th>Feedback Type</th>
																	<th>Mobile Number</th>
																	<th>Email Id</th>
																	<th>Date & Time</th>
																	<th>Action</th>
																</tr>
															</thead>
															<tbody>
															<?php if (!empty($all_feedback)) {
																	$sr=1; $i=0;
																	foreach ($all_feedback as $each_user) { ?>
																		<tr>
																			<td class="td_left"><?php echo $sr; ?></td>
																			<td class="td_left"><?php echo $each_user['first_name'];?></td>
																			<td class="td_left"><?php echo $each_user['last_name'];?></td>
																			<td class="td_left">
																				<?php
																					if ($each_user['type'] != 'Other') {
																						echo ucfirst(str_replace('_',' ',$each_user['type']));
																					} else {
																						echo $each_user['other_type'];
																					}
																				?>
																		    </td>
																			<td class="td_left"><?php echo base64_decode($each_user['mobile_no']);?></td>
																			<td class="td_left"><?php echo base64_decode($each_user['email']); //for email encoding ?></td>
																			<td class="td_left"><?php echo $each_user['created'];?></td>
																			<td class="td_left"><?php echo $this->Html->link('', array('controller' => 'Feedbacks', 'action'=>'fetch_feedback_id', $each_user['id']),array('class'=>'glyphicon glyphicon-eye-open')); ?></td>
																		</tr>
														<?php $sr=$sr+1; $i=$i+1; } } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</section>
	</div>
	<?php echo $this->Html->script('Feedbacks/all_feedbacks'); ?>
