<?php ?>

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6"><label class="badge badge-primary">Backlog Data</label></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
							<li class="breadcrumb-item active">Old Application Entry</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card card-Lightblue">
						
							<div class="card-header"><h4 class="card-title-new">Old Applications Registration</h4></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="social-auth-links text-center d-flex col-12 m-0 p-0">
											<div class="col-6 mb-2 p-0"><a id="primary_list" href="#" class="btn btn-info">Primary List</a></div>
											<div class="col-6 mb-2 p-0"><a id="firms_list" href="#" class="btn btn-success" >Firm List</a></div>
										</div>
									</div>
								</div>
							</div>
							
							<!--PRIMARY LISTING TABLE STARTS-->
							<div id="primary_listing_table">
								<div class="d-flex col-12"><a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/register_customer" class="btn btn-success float-left">Create New Primary</a></div>
								<div class="card-header"><h4 class="bg-blue card-title-new">List of Primary Registration</h4></div>
								<div class="table-format">
									<table class="table m-0 table-bordered">
										<thead class="tablehead">
											<tr class="filters">
												<th>Sr no.</th>
												<th>Primary ID</th>
												<th>Name</th>
												<th>Date of Registration</th>
												<th>District</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if (!empty($primary_reg_list)) {
													$i=0;
													foreach ($primary_reg_list as $each_reg) { ?>
														<tr>
															<td><?php echo $i+1; ?></td>
															<td><?php echo $each_reg['customer_id']; ?></td>
															<td><?php echo $each_reg['f_name'].' '.$each_reg['l_name']; ?></td>
															<td><?php echo $each_reg['created']; ?></td>
															<td><?php echo $district_list[$each_reg['district']]; ?></td>
															<td><button class="bg-cyan"><?php echo $this->Html->link('Edit', array('controller' => 'authprocessedoldapp', 'action'=>'fetch_primary_id', $each_reg['id'])); ?></button></td>
														</tr>

													<?php	$i=$i+1; }

												} else { ?>

													<tr>
														<td></td>
														<td><h6 class="badge">Currently there are no Primary Ids</h6></td>
													</tr>

											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
							<!--PRIMARY LISTING ENDS-->

							<!--FIRMS LISTING TABLE STARTS-->
							<div id="firms_listing_table">
								<div class="d-flex col-12"><a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/add_firm" class="btn btn-success rightto">Create New Firm</a></div>
								<div class="card-header"><h5 class="card-title-new bg-gradient-gray">List of Firm Registration</h5></div>
								<div class="table-format">
									<table class="table m-0 table-bordered table-hover table-striped">
										<thead class="tablehead">
											<tr class="filters">
												<th>Sr no.</th>
												<th>Primary ID</th>
												<th>Firm ID</th>
												<th>Firm Name</th>
												<th>Type of Certificate</th>
												<th>District</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if (!empty($firms_list)) {
													 $i=0;
													foreach ($firms_list as $each_firm) { ?>
														<tr>
															<td><?php echo $i+1; ?></td>
															<td><?php echo $each_firm['customer_primary_id']; ?></td>
															<td><?php echo $each_firm['customer_id']; ?></td>
															<td><?php echo $each_firm['firm_name']; ?></td>
															<td><?php echo $certificate_type[$each_firm['certification_type']]; ?></td>
															<td><?php echo $district_list[$each_firm['district']]; ?></td>
															<td>
															<?php if(empty($firm_final_submited[$i])){?>
																<button class="bg-info"><?php echo $this->Html->link('Edit Firm', array('controller' => 'authprocessedoldapp', 'action'=>'edit_firm_fetch_id', $each_firm['id'])); ?></button> |
																<button class="bg-DARK"><?php echo $this->Html->link('Fill Form', array('controller' => 'application', 'action'=>'fill_form_fetch_id', $each_firm['id'],1,'edit')); ?></button>
															<?php }else{ ?>
																<button class="bg-success"><?php echo $this->Html->link('View Form', array('controller' => 'application', 'action'=>'fill_form_fetch_id', $each_firm['id'],1,'view')); ?></button>
															<?php } ?>
															</td>
														</tr>
													<?php	$i=$i+1; }

												} else { ?>

													<tr>
														<td></td>
														<td><h6 class="badge">Currently there are no Firms</h6></td>
													</tr>

												<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

<?php echo $this->Html->script('authprocessedoldapp/home/home'); ?>
