<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Granted Application</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
						<li class="breadcrumb-item active">Granted Application</li>
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
						<div class="card-header"><h3 class="card-title-new">All Granted Certificates</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<table id="certificates_list" class="table m-0">
									<thead class="form-color-beige">
										<tr>
											<th>Application Type</th>
											<th>Certification Type</th>
											<th>Application Id</th>
											<th>Firm Name</th>
											<th>Certificate</th>
											<th>Date</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if(!empty($all_grant_cert)){
											foreach($all_grant_cert as $each_cert){ ?>
									
											<tr>
												<td><?php echo $each_cert['appl_type'];?></td>
												<td><?php echo $each_cert['cert_type'];?></td>
												<td><?php echo $each_cert['customer_id'];?></td>
												<td class="boldtext"><?php echo $each_cert['firm_name'];?></td>
												<td>
													<?php $split_file_path = explode("/",$each_cert['pdf_link']);
														$file_name = $split_file_path[count($split_file_path) - 1]; ?>
																	
													<a class="badge badge-success" target="blank" href="<?php echo $each_cert['pdf_link']; ?>">
														<?php echo $file_name; ?>
													</a>
												</td>
												<td><?php echo $each_cert['date'];?></td>
											</tr>
									
										<?php } } ?>
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

<?php echo $this->Html->script('dashboard/grant_certificates_list'); ?>
