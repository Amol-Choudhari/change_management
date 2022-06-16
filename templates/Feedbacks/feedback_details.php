<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Feedback Details</label></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
							<li class="breadcrumb-item"><?php echo $this->Html->link('Feedbacks', array('controller' => 'feedbacks', 'action'=>'all-feedback'));?></li>
							<li class="breadcrumb-item active">Feedback Details</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card card-cyan">
							<div class="card-header"><h3 class="card-title-new">Feedback</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<table class="table m-0 table-bordered">
												<tr>
													<th class="tablehead">Fields</th>
													<th class="tablehead">Description</th>
												</tr>
												<tr>
													<td>Type of Feedback</td>
													<td>
														<?php if ($feedback_details['type'] != 'Other') {
																echo ucfirst(str_replace('_',' ',$feedback_details['type']));
															  } else {
																echo ucfirst(str_replace('_',' ',$feedback_details['other_type']));
															  }
													      ?>
													</td>
												</tr>
												<tr>
													<td>Name</td>
													<td><?php echo $feedback_details['first_name'] ." ". $feedback_details['last_name']; ?></td>
												</tr>
												<tr>
													<td>Mobile No</td>
													<td><?php echo base64_decode($feedback_details['mobile_no']); ?></td>
												</tr>
												<tr>
													<td>Email Id</td>
													<td><?php echo base64_decode($feedback_details['email']); //for email encoding ?></td>
												</tr>
												<tr>
													<td>Address</td>
													<td><?php echo $feedback_details['address']; ?></td>
												</tr>
												<tr>
													<td>Comment</td>
													<td><?php echo $feedback_details['comments']; ?></td>
												</tr>
											</table>
										</div>
									</div>
									<div class="card-footer cardFooterBackground">
										<?php echo $this->Html->link('Back', array('controller' => 'feedbacks', 'action'=>'all-feedback'),array('class'=>'add_btn btn btn-secondary float-right')); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
