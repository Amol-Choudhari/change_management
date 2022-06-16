<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-info">Packer Transactions</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item active">Packer Payment Transactions</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle ">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<?php echo $this->Form->create(null,array('id' => 'packer_transaction_form')); ?>
						<div class="card card-info pb-2">
							<div class="card-header info2"><h3 class="card-title-new">Packer Transactions</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-12">
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="offset-2">Select Packer Name <span class="cRed">*</span></label>
															<div class="col-sm-7 custom-file">
																<?php echo $this->Form->control('packer_list', array('type'=>'select', 'id'=>'packer_list', 'options'=>$packer_list, 'empty'=>'select', 'label'=>false,'class' => 'form-control')); ?>
															</div>
														</div>
													</div>
													<div class="col-sm-6">
														<?php echo $this->Form->control('Submit', array('type'=>'submit', 'name'=>'packer_id', 'id'=>'packer_id_btn', 'label'=>false,'class'=>'btn btn-success float-left')); ?>
													</div>
												</div>
											</div>
										<div class="col-sm-12">
											<div class="card-header bg-info"><h3 class="card-title-new">Transaction History <?php if (!empty($packer_name)) { echo 'of '.$packer_name; } else { echo $packer_name = ''; }?></h3></div>
												<table id = "user_logs_table" class="table m-0 table-bordered table-striped table-hover">
													<thead class="tablehead">
														<tr>
															<th>SR.NO</th>
															<th>Date</th>
															<th>Transaction Type</th>
															<th>Transaction Amount</th>
															<th>Balance Amount</th>
															<th>Replica Number Range</th>
														</tr>
													</thead>
												<tbody>
													<?php
													if(!empty($transactionhistory)) {	
														$i=1;
														foreach($transactionhistory as $each){ ?>

															<tr>

																<td><?php echo $i;?></td>
																<td><?php echo $each['created'];?></td>
																<td><?php echo ucfirst($each['trans_type']);?></td>
																<td><?php echo $each['trans_amount']; ?></td>
																<td><?php echo $each['balance_amount'];?></td>

															<?php if($each['replica_series_from'] != ''){ ?>

																<td><?php echo $each['replica_series_from'].' - '.$each['replica_series_to']; ?></td>

															<?php }else{ ?>

																<td><?php echo '--'; ?></td>

															<?php } ?>
															</tr>
													<?php	$i=$i+1; } } ?>
											</tbody>
										</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<?php echo $this->Html->script('advance_payment/transaction/datatable'); ?>
