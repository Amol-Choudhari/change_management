<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<?php echo $this->Html->link('Back', array('controller' => 'dashboard', 'action'=>'home'),array('class'=>'add_btn btn btn-secondary float-sm-left')); ?>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item active">Applicant Deatils</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<section class="col-lg-12">
					<div class="card card-cyan">
						<div class="card-header"><h3 class="card-title-new">Appliant Details</h3></div>
							<?php echo $this->Form->create(); ?>
								<div class="form-horizontal">
									<table class="table table-hover table-striped table-bordered table-responsive">
										<thead class="tablehead">
											<tr>
												<th>Sr.No</th>
												<th>Primary ID</th>
												<th>Primary Email</th>
												<th>Applicant ID</th>
												<th>Firm Name</th>
												<th>Firm Email</th>
												<th>District</th>
											</tr>
										</thead>
										<tbody>
											<?php $sr_no=1; foreach($datalist as $eachdata){ ?>
												<?php foreach($eachdata as $data){
													//$email = AppController::get_email_masked($data['fp']['email']);
													//$femail = AppController::get_email_masked($data['Dmi_firm']['email']);
													$email = base64_decode($data['email']);//for email encoding
													$femail = base64_decode($data['email']);//for email encoding
												?>
												<tr>
													<td><?php echo 	"<span class='badge'>".$sr_no."</span>";?></td>
													<td><?php echo 	"<span class='badge'>".$data['customer_primary_id']."</span>"; ?></td>
													<td><?php echo  "<span class='badge'>".$email."</span>";?></td>
													<td><?php echo 	"<span class='badge'>".$data['customer_id']."</span>"; ?></td>
													<td><?php echo  "<span class='badge'>".$data['firm_name']."</span>"; ?></td>
													<td><?php echo  "<span class='badge'>".$femail."</span>"; ?></td>
													<td><?php echo  "<span class='badge'>".$data['district_name']."</span>"; ?></td>
												</tr>
											<?php $sr_no++; } ?>
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

	<?php echo $this->Html->script('Users/applicant_details'); ?>
