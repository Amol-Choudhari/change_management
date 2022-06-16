
<?php
	$username = $_SESSION['username'];
	$customer_f_name = $_SESSION["f_name"];
	$customer_l_name = $_SESSION["l_name"];
?>


	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1 class="m-0 text-dark">My AQCMS Dashboard</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item active">Dashboard</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3 col-6">
					<div class="small-box bg-success">
						<div class="inner"><h3><?php echo count($firms_details); ?></h3><p>Total Firms</p></div>
						<div class="icon"><i class="fa fa-building"></i></div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-info">
						<div class="inner">
							<h3>
								<?php
				                  	$authorisation_count = '0';
				                    $lab_firm_count = '0';
				                    $printing_firm_count = '0';

									foreach ($firms_details as $data) {
										if ($data['certification_type'] == 'Grant of Certificate of Authorisation')
                  							$authorisation_count++;

                      					if($data['certification_type'] == 'Approval of Laboratory')
                        					$lab_firm_count++;

                      					if($data['certification_type'] == 'Grant of Permission to Printing Press')
                        					$printing_firm_count++;

                  					}

                  					echo $authorisation_count; ?>
                			</h3>
						<p>Authorisation Firms</p>
              		</div>
				<div class="icon"><i class="fa fa-address-card"></i></div>
            </div>
         </div>
		<div class="col-lg-3 col-6">
			<div class="small-box bg-warning">
				<div class="inner"><h3><?php echo $lab_firm_count; ?></h3><p>Laboratory Firms</p></div>
				<div class="icon"><i class="fa fa-flask"></i></div>
			</div>
		</div>
		<div class="col-lg-3 col-6">
			<div class="small-box cardFooterBackground">
				<div class="inner"><h3><?php echo $printing_firm_count; ?></h3><p>Printing Firms</p></div>
				<div class="icon"><i class="fa fa-print"></i></div>
			</div>
		</div>
	</div>
		<div class="row">
			<section class="col-lg-12 connectedSortable">
				<div class="card-info">
					<div class="card-header"><h3 class="card-title-new">Given Below are previously added firms</h3></div>
						<div class="card-body">
							<table id="example1" class="table m-0 table-bordered table-hover">
								<thead class="tablehead">
				  	                <tr>
				  						<th>ID</th>
				  						<th>Firm Name</th>
				  						<th>Certification Type</th>
				  						<th>Commodity</th>
				  						<th>District</th>
				  						<th>Status</th>
				  						<th class="wd18">Action</th>
				  	                </tr>
		                  		</thead>
			                  	<tbody>
									<?php if ($firms_details != 'No Firm Added') {
			              					$i=0;
			          						foreach ($firms_details as $firms_detail) { ?>
												<tr>
					          						<td class="boldtext"><?php echo $firms_detail['customer_id'];?></td>
					          						<td><?php echo $firms_detail['firm_name'];?></td>
					          						<td><?php echo $firms_detail['certification_type'];?></td>
					          						<td><?php echo $firms_detail['commodity'];?></td>
					          						<td><?php echo $firms_detail['district'];?></td>
					          						<td><?php $app_status = $application_status[$i];
			          										if ($app_status == 'Not Applied yet') {
																echo "<span class='badge badge-warning'>".$app_status."</span>";
															} else {
																echo "<span class='badge badge-success'>".$app_status."</span>";
															} ?>
													</td>
			          								<td>
			                        					<div class="btn-group btn-group-sm">
			                          						<?php echo $this->Html->link('<i class="fas fa-eye"></i> View', array('controller' => 'customerforms', 'action'=>'fetch_firm_id', $firms_detail['id']), array('class'=>'btn btn-info', 'escape'=>false)); ?>
			            									<?php if(empty($final_submit_done[$i])) {
			                              						echo $this->Form->button('<i class="fas fa-trash"></i> Delete', array('class'=>'btn btn-danger firm_delete_btn', 'escapeTitle'=>false, 'value'=>'../customerforms/delete_firm_id/'.$firms_detail['id']));
			            									} ?>
			                        					</div>
			          								</td>
			  	                				</tr>
										<?php $i=$i+1; } } ?>
									</tbody>
								</table>
							</div>
						</div>
					<p> &nbsp; </p>
				</section>
			</div>
		</div>
	</section>
</div>

	<div class="modal fade show" id="modal-default" aria-modal="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header text-md bg-danger">
					<h5 class="container text-center"><i class="icon fas fa-exclamation-triangle"></i> Alert</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				</div>
				<div class="modal-body">Are you sure you want to delete this Firm ?</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="button" id="confirm-del" class="btn btn-danger"><i class="fa fa-trash"></i> Confirm</button>
				</div>
			</div>
		</div>
	</div>

	<?php echo $this->Html->script('customers/primary_home'); ?>
