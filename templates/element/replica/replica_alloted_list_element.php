<?php echo $this->Form->create(null);

	if ($_SESSION['alloted_list_for']=='replica') {

		$list_for = 'Replica';
		echo $this->Form->control('', array('type'=>'hidden','id'=>'alloted_list_for','value'=>$list_for)); //added for the alert message variable by AKASH on 30-12-2021

	} elseif ($_SESSION['alloted_list_for']=='15Digit') {

		$list_for = '15 Digit Code';
		echo $this->Form->control('', array('type'=>'hidden','id'=>'alloted_list_for','value'=>$list_for)); //added for the alert message variable by AKASH on 30-12-2021

	} elseif ($_SESSION['alloted_list_for']=='ECode') {

		$list_for = 'E-Code';
		echo $this->Form->control('', array('type'=>'hidden','id'=>'alloted_list_for','value'=>$list_for)); //added for the alert message variable by AKASH on 30-12-2021

	}
?>
	<div class="replica_details_mod form-group">
		<div class="row">
			<div class="col-md-12">
				<div class="form-horizontal">
					<div class="card-body content form-middle">
						<div class="row">
							<div class="col-md-4">
								<input class="form-control" id="rep_ser_no" type="text" placeholder="Enter <?php echo $list_for; ?> Number"/>
							</div>
							<div class="col-md-3">
								<button id="replica_details_btn" class="btn btn-primary">Get <?php echo $list_for; ?> Details</button>
							</div>
						</div>
						<div id="replica_detail_popup" class="modal" >
							<!-- Modal content -->
							<div class="modal-content">
								<span class="close"><b>&times;</b></span>
								<h4 class="modal-header"><?php echo $list_for; ?> Details</h4>
								<div id="replica_detail_content"><table id="append-table" class="table-bordered wd100"></table></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid form-group">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header info2"><h3 class="card-title-new">Alloted <?php echo $list_for; ?> Status</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<table id="applicant_logs_table" class="table table-bordered table-striped table-hover m-0">
									<thead class="tablehead">
										<tr>
											<th>Sr.No</th>
											<th>Customer Id</th>
											<th>CA Unique No.</th>
											<th>Commodity</th>
											<th>Date</th>
											<th>Version</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php if (!empty($replica_stats)) {
											$i=0;
											$sr_no = 1;
											foreach ($replica_stats as $each) { ?>
											<tr>
												<td><?php echo $sr_no; ?></td>
												<td><?php echo $each['customer_id']; ?></td>
												<td><?php echo $each['ca_unique_no']; ?></td>
												<td><?php echo $commodity[$i] ?></td>
												<td><?php $explodeDate = explode(' ',$each['modified']);echo $explodeDate[0]; ?></td>
												<td><?php echo $pdf_version[$i]; ?></td>
												<!-- below td updated on 25-08-2022 for letter and Excel sheet download -->
												<td><a class="view_letter_btn" target="_blank" href="<?php echo $pdf_link[$i]; ?>">Letter</a>
												
												<?php if ($_SESSION['alloted_list_for']=='replica') { //only for replica series ?>	
													| <a class="view_letter_btn" href="../replica/getAllotedReplicaExcel/<?php echo $each['id']; ?>">Sheet</a>
												<?php } ?>
												</td>
											</tr>
										<?php $sr_no++; $i=$i+1; } } ?>
									</tbody>
								</table>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>


	<?php echo $this->Html->script('element/replica/replica_alloted_list_element'); ?>
