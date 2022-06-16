<?php ?>
<?php echo $this->Html->css('Replica/replica_application_approval'); ?>
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'firm_form')); ?>
	<section class="content form-middle form_outer_class" id="form_outer_main">
		<a href="../replica/replica_appl_list" class="btn btn-primary">Back</a>
			<div class="container-fluid form-group wd1080">
	  			<h5 class="mt-1 mb-2">Replica Serial Number Application</h5>
					<div class="row">
						<div class="col-md-12">
							<div id="firm_details_block" class="card card-success">
								<div class="card-header"><h3 class="card-title">Firm Details</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-md-3">
											<?php echo $this->form->control('firm_name', array('type'=>'text', 'id'=>'firm_name','value'=>$firm_details['firm_name'], 'class'=>'form-control', 'readonly'=>true, 'label'=>'Firm Name')); ?>
										</div>

										<div class="col-md-3">
											<?php echo $this->form->control('customer_id', array('type'=>'text', 'id'=>'customer_id','value'=>$firm_details['customer_id'], 'class'=>'form-control', 'readonly'=>true, 'label'=>'packer ID')); ?>
										</div>

										<div class="col-md-3">
											<?php echo $this->form->control('ca_unique_no', array('type'=>'text', 'id'=>'ca_unique_no','value'=>$ca_unique_no, 'class'=>'form-control', 'readonly'=>true, 'label'=>'Packer Unique No.')); ?>
										</div>

										<div class="col-md-3">
											<?php echo $this->form->control('grading_lab', array('type'=>'text', 'id'=>'grading_lab', 'value'=>$tableRowData[0]['lab_name'], 'class'=>'form-control', 'label'=>'Grading laboratory', 'readonly'=>true,)); ?>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<div id="replica_add_more_table" class="card card-success wd1080">
							<div class="card-header"><h3 class="card-title">Other Details</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
								<div id="table_outer">
									<div id="table_container" >

										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Sr.No</th>
													<th>Commodity</th>
													<th>Grade</th>
													<th>TBL</th>
													<th>Packaging Material</th>
													<th>Authorized Printer</th>
													<th>Packet Size</th>
													<th>Unit</th>
													<th>No of Packets</th>
													<th>Total Quantity (Kg/Ltr)</th>
													<th>Label Charge</th>
													<th>Total Label Charges (Kg/Ltr)</th>
													<th>Balance Replica No.</th>
													<th>Alloted From (Ser. No.)</th>
													<th>Alloted To (Ser. No.)</th>
												</tr>
											</thead>
											<tbody>

												<?php
												if(!empty($tableRowData)){

													$i=0;
													$sr_no = 1;
													foreach($tableRowData as $each){ ?>

														<tr>
															<td><?php echo $sr_no; ?></td>
															<td><?php echo $each['commodity_name']; ?></td>
															<td><?php echo $each['grade_name']; ?></td>
															<td><?php echo $each['tbl_name']; ?></td>
															<td><?php echo $each['packing_type']; ?></td>
															<td><?php echo $each['printer_name']; ?></td>
															<td><?php echo $each['packet_size']; ?></td>
															<td><?php echo $each['packet_size_unit']; ?></td>
															<td><?php echo $each['no_of_packets']; ?></td>
															<td><?php echo $each['total_quantity']; ?></td>
															<td><?php echo $each['label_charge']; ?></td>
															<td><?php echo $each['total_label_charges']; ?></td>
															<td><?php echo $each['bal_agmark_replica']; ?></td>
															<td><?php echo $each['alloted_rep_from']; ?> (<?php echo $each['rep_from_numeric']; ?>)</td>
															<td><?php echo $each['alloted_rep_to']; ?> (<?php echo $each['rep_to_numeric']; ?>)</td>
														</tr>

												<?php $sr_no++; $i=$i+1;	} } ?>

											</tbody>
										</table>

									</div>
								</div>

								<div class="col-md-3 float-right">

									<?php echo $this->form->control('overall_total_chrg', array('type'=>'number', 'id'=>'overall_total_chrg','value'=>$overall_charges, 'class'=>'form-control', 'readonly'=>true, 'label'=>'Total Replica Charges:', 'required'=>true)); ?>
									<span id="bal_amt_exceeds_msg"></span>
								</div>

								<div class="clear"></div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-2">
				<?php echo $this->form->control('Approve', array('type'=>'submit', 'id'=>'che_approve', 'name'=>'che_approve', 'class'=>'btn btn-success', 'label'=>false,)); ?>
			</div>
		</section>
		<div class="clear"></div>
	<!--	<input type="hidden" id="tableFormData" value="<?php //echo $tableForm; ?>"> -->
	<?php echo $this->Form->end(); ?>

	<?php echo $this->element('replica/replica_approval_esign_consent'); ?>
	<?php echo $this->Html->script('replica/replica_serial_approval'); ?>
