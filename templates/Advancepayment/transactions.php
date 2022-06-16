<?php  ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-info">Advance Payment</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'customers', 'action'=>'secondary_home'));?></a></li>
						<li class="breadcrumb-item active">Advance Payment Module</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<section class="content form-middle ">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-info pb-2">
						<div class="card-header info2"><h3 class="card-title-new"></h3>Initial Details</div>
							<div id='initial_details' class="form-horizontal">
								<div class="col-lg-2 float-right">
									<a href="../advancepayment/add_payment" class="btn btn-info float-right mt-2">
									<?php if($unconfirmedBalanceAmount == 0){ echo 'Add Payment'; }else{ echo 'Payment Status'; } ?></a>
								</div>

					 		<div class="card-body">
					 			<div class="row">
									 <div class="middle">
									 <label  class="badge"for="field3"><span class="badge badge-success">Available Balance<span class="ml-1">:</span></span>
										<label class="badge"><i class="fas fa-rupee-sign"></i>  <?php echo $currentBalance; ?></label>
									</label>

									<label for="field3" class="badge"><span class="badge badge-warning">Unconfirmed Balance<span class="ml-1">:</span></span>
										<label class="badge"><i class="fas fa-rupee-sign"></i>  <?php echo $unconfirmedBalanceAmount; ?></label>
									</label>
									 </div>

								</div>
							</div>
						</div>
					</div>
					<div class="card-header bg-success"><legend class="card-title-new">Transactions</legend></div>
						<div class="form-horizontal">
							<div class="card-body">
								<table id = "user_logs_table" class="table m-0 table-striped table-hover table-bordered">
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
									<?php if (!empty($transactionsHistory)) {	
											
										$i=1;
											
										foreach ($transactionsHistory as $each) { ?>
												<tr>
													<td><?php echo $i;?></td>
													<td><?php echo $each['created'];?></td>
													<td><?php echo ucfirst($each['trans_type']);?></td>
													<td><?php echo $each['trans_amount']; ?></td>
													<td><?php echo $each['balance_amount'];?></td>

												<?php if ($each['replica_series_from'] != '') { ?>

													<td><?php echo $each['replica_series_from'].' - '.$each['replica_series_to']; ?></td>

												<?php } else { ?>

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
		</section>
	</div>

	<?php echo $this->Html->script('advance_payment/transaction/datatable'); ?>
