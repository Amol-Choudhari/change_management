<?php ?>
<?php echo $this->Form->create(null, array()); ?>
	<div class="card card-info">
			<div class="card-header">
				<?php if(!empty($from_dt) && !empty($to_dt)){ ?>
						<h3 class="card-title-new">List of <?php echo $appl_type; ?> Granted Applications from <?php echo $from_dt; ?> to <?php echo $to_dt; ?></h3>
				<?php }else{ ?>
						<h3 class="card-title-new">List of <?php echo $appl_type; ?> Granted Applications for Last 1 Month</h3>
				<?php } ?>
			</div>
		
			<div class="card-body">
				<div class="form-group row pd10_0_0">
					<div class="col-md-2">
						<?php echo $this->Form->control('from_dt',array('type'=>'text','id'=>'from_dt','placeholder'=>'From Date','label'=>false,'class'=>'form-control','readonly'=>true)); ?>
					</div>
					<div class="col-md-2">
						<?php echo $this->Form->control('to_dt',array('type'=>'text','id'=>'to_dt','placeholder'=>'To Date','label'=>false,'class'=>'form-control','readonly'=>true)); ?>
					</div>
					<div class="col-md-2">
						<?php echo $this->Form->control('Get Logs',array('type'=>'submit','id'=>'search','label'=>false,'class'=>'btn btn-success')); ?>
					</div>
					<div class="clear"></div>
				</div>
			</div>
								
			<table id="ho_granted_appl_list" class="table m-0 table-bordered table-striped table-hover">
				<thead class="tablehead">
					<tr>
						<th>Sr. No.</th>
						<th>Cert. Type</th>
						<th>Applicant Id</th>
						<th>Grant Date</th>
						<th>Cert. Pdf</th>

						<!-- added condition on 16-09-2021, as per new order -->
						<?php if ($appl_type != 'Renewal') { ?>
							<th>Appl. | Pdf</th>
							<th>Report. | Pdf</th>
						<?php } else { ?>
							<th>Action</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i=0;
						if (!empty($appl_array)) {

							foreach ($appl_array as $each) { ?>

								<tr>
									<td><?php echo $i+1;?></td>
									<td><?php echo $each['cert_type'];?></td>
									<td id="customer_id<?php echo $i; ?>"><?php echo $each['customer_id'];?></td>
									<td><?php echo $each['grant_date'];?></td>
									<td><?php $cert_pdf_path = explode("/",$each['cert_pdf']);
											  $cert_pdf_name = $cert_pdf_path[count($cert_pdf_path) - 1]; ?>
											<a id="pdf_link<?php echo $i; ?>" target="_blank" href="<?php echo $each['cert_pdf']; ?>">
												<?php echo 'Certificate'; ?>
											</a>
									</td>

									<!-- added condition on 16-09-2021, as per new order -->
									<?php if ($appl_type != 'Renewal') { ?>
									<td><?php $appl_pdf_path = explode("/",$each['appl_pdf']);
											$appl_pdf_name = $appl_pdf_path[count($appl_pdf_path) - 1]; ?>

										<a target="_blank" href="<?php echo $each['appl_form']; ?>">
											<?php echo 'View'; ?>
										</a>
										|
										<a target="_blank" href="<?php echo $each['appl_pdf']; ?>">
											<?php echo 'Pdf'; ?>
										</a>
									</td>

									<td><?php $report_pdf_path = explode("/",$each['report_pdf']);
											$report_pdf_name = $report_pdf_path[count($report_pdf_path) - 1]; ?>

										<a target="_blank" href="<?php echo $each['report_form']; ?>">
											<?php echo 'View'; ?>
										</a>
										|
										<a target="_blank" href="<?php echo $each['report_pdf']; ?>">
											<?php echo 'Pdf'; ?>
										</a>
									</td>

									<?php } elseif ($appl_type == 'Renewal') {
											if ($each['show_esign_btn']=='yes') { ?>
												<td><a href="#" id="renewal_esign_btn<?php echo $i; ?>" class="btn btn-primary">Esign Certificate</a></td>
											<?php } else { ?>
												<td><strong>Certificate Esigned</strong></td>
											<?php }
										} ?>
							</tr>
						<?php	$i=$i+1; } } ?>
					</tbody>
				</table>
			</div>
<?php echo $this->Form->end(); ?>

<?php echo $this->element('esign_views/renewal_grant_declaration_message'); ?>	
<input type="hidden" id="i-value" value="<?php echo $i; ?>">
<?php echo $this->Html->script('dashboard/granted_appl_list-js'); ?>
