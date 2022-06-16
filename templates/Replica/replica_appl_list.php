<?php echo $this->Form->create(null); ?>
	<div class="container-fluid form-group">
	  		<h5 class="mt-1 mb-2">Applications For Replica Serial Number</h5>
			<div class="row">
				<div class="col-md-12">
					<div class="form-horizontal">
						<div class="card-body">
							<div id="replica_appl_list_div">
								<table id="replica_appl_list_table" class="table table-bordered table-hover table-striped">
									<thead class="tablehead">
										<tr>
											<th>Sr.No</th>
											<th>Packer Id</th>
											<th>Unique No.</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

										<?php
										if(!empty($replica_appl_list)){

											$i=0;
											$sr_no = 1;
											foreach($replica_appl_list as $each){ ?>

												<tr>
													<td><?php echo $sr_no; ?></td>
													<td><?php echo $each['customer_id']; ?></td>
													<td><?php echo $each['ca_unique_no']; ?></td>
													<td><?php echo $this->Html->link('View Details', array('controller' => 'replica', 'action'=>'replica_appl_list_id', $each['ca_unique_no'])); ?></td>
												</tr>

										<?php $sr_no++; $i=$i+1;	} } ?>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script('replica/replica_appl_list'); ?>
