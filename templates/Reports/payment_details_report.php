
<?php echo $this->Html->css('Reports/payment_details_report') ?>


<?php  // Change on 5/11/2018 : Assign class attribute to all search filter field and comment the value attribute   - By Pravin Bhakare?>
<div class="content-wrapper bg-bg">
	<div class="content-header page-header" id="page-load">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h6 class="m-0 ml-3"><?php echo 'Payment Details Report'; ?></h6>
				</div>
				<div class="col-sm-6 my-auto">
					<ol class="breadcrumb float-sm-right">
						<span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
						<span class="my-auto"><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge bg-light"><?php echo $this->Html->link('All Reports', array('controller' => 'reports', 'action'=>'report_types'));?></a></span></span>
						<span class=""><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge page-header">
							<?php echo 'Payment Details Report'; ?></span>
						</span>
					</ol>
				</div>
				<div class="clearfix"></div>
			</div>
    	</div>
  	</div>

	 <!-- <section class="content form-middle"> -->
	  <div class="container-fluid">
      		<div class="row">
        		<div class="col-md-12 bg-bg">

					<div class="px-4 page-header" > <!-- style="background:#F9F1F1;" -->
						<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'newly_added_firm')); ?>
						<div class="bg-transparent">
							<div id="search_by_options" class="">
								<div class="row report-filter pt-2">
									<div class="col-sm-3">
                    					<div class="form-group">
											<!-- <label>Report For</label> -->
											<?php echo $this->form->input('report_for', array('type'=>'select', 'options'=>$report_for_array, 'label'=>false, 'id'=>'report_for', 'escape'=>false, 'class'=>'form-control form-control-sm')); ?>
										</div>
								  	</div>
									<div class="col-sm-3" id="office_all">
                    					<div class="form-group">
											<!-- <label>Application Type</label> -->
											<?php echo $this->form->input('application_type', array('type'=>'select', 'options'=>$all_application_type, 'label'=>false, 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-3">
                    					<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('office', array('type'=>'select', /*'value'=>$search_office,*/ 'options'=>$all_ro_office, 'label'=>false, 'id'=>'office', 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-3">
                    					<div class="form-group">
											<!-- <label>State</label> -->
											<?php echo $this->form->input('state', array('type'=>'select', 'options'=>$all_states, 'label'=>false, 'id'=>'state', 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
								</div>

								<div class="row report-filter ro_report-filter pt-2">
									<div class="col-sm-3">
                    					<div class="form-group">
											<!-- <label>District</label> -->
											<?php echo $this->form->input('district', array('type'=>'select', 'label'=>false, 'id'=>'district',  'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-3">
                    					<div class="form-group">
											<!-- <label>From Date</label> -->
											<?php echo $this->form->input('from_date', array('type'=>'text', /*'value'=>$search_from_date,*/'label'=>false, 'id'=>'fromdate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'From Date')); ?>
										</div>
								  	</div>
									<div class="col-sm-3">
                    					<div class="form-group">
											<!-- <label id="to_date_label">To Date</label> -->
											<?php echo $this->form->input('to_date', array('type'=>'text', /*'value'=>$search_to_date,*/'label'=>false, 'id'=>'todate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'To Date')); ?>
										</div>
								  	</div>
									<div class="col-sm-1">
										<button id="search_btn" type="submit" name="search_logs" class="btn text-light option-menu-btn" value="Search" data-bs-toggle="tooltip" data-bs-placement="top" title="Search">
											<i class="fas fa-search"></i>
										</button>
										<!-- <input style="background:#747474; color:#fff;" id="search_btn" type="submit" name="search_logs" class="form-control" value="Search" > -->
									</div>
									<div class="col-sm-1">
										<!-- Call the Downloading Report Button Element (Done by Pravin 13/3/2018) -->
										<?php // echo $this->element('download_report_excel_format/report_download_button'); ?>
									</div>
								</div>

							</div>
						</div>
					</div>

				</div>
      		</div>
    	</div>
  	<!-- </section> -->

	<div class="bg-bg">
	 	<div class="container-fluid">
      		<div class="row">
        		<div class="col-md-12">
					<div class="mx-5">
						<?php ?> <span class="badge bg-light shadow">RESULT</span><i class="fas fa-chevron-right px-2 fs80"></i> <?php
							if(!empty($application_type)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Application Type</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $all_application_type[$application_type];  $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($ro_office)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">RO Office</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $all_ro_office[$ro_office];   $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($state)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">State</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $all_states[$state];  $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($district)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">District</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $all_district[$district];  $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($search_from_date)) {
							?> 	<span class="badge rounded-pill bg-grad1 shadow">From Date</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php $explode_f_date = explode(' ',$search_from_date);
									$explode_f_date = explode('-',$explode_f_date[0]);
									echo $explode_f_date[2].'-'.$explode_f_date[1].'-'.$explode_f_date[0];  $search_value = 'yes'; ?> </span>

								<span class="badge rounded-pill bg-grad1 shadow">To Date</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 shadow"> <?php $explode_t_date = explode(' ',$search_to_date);
									$explode_t_date = explode('-',$explode_t_date[0]);
									echo $explode_t_date[2].'-'.$explode_t_date[1].'-'.$explode_t_date[0];  $search_value = 'yes'; ?> </span>
								<?php
							}
							if(empty($search_value)) {
							?>  <span class="badge bg-grad2 mr-3 shadow"> <?php echo 'ALL'; ?> </span>
								<?php
							}
						?>
					</div>
				</div>
      		</div>
    	</div>

		<section class="content form-middle">
			<div class="container-fluid border rounded-lg border-light bg-light p-3 shadow">
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive report-table-format">
							<table class="table table-sm rounded" id="payment_details_report_table">
								<thead class="table-light">
									<tr class="rounded">
										<th><span class="table-heading">S.No</span></th>
										<th><span class="table-heading">Date</span></th>
										<th><span class="table-heading">Application Type</span></th>
										<th><span class="table-heading">RO Office</span></th>
										<th class="text-right"><span class="table-heading">State</span></th>
										<th><span class="table-heading">District</span></th>
										<th><span class="table-heading">Payment Amount</span></th>
									</tr>
								</thead>
								<tbody class="">
								<?php //Find and calculate the value for Sr.No column (Done by pravin 16-07-2018)
									$i=''; if($report_for == 'both' || $report_for == 'new') {
										if(!empty($firms_details)) { ?><tr><td colspan="6" class="table-heading bg-dark">New Application Payment Details</tr><?php } ?>
										<?php for ($i=0; $i<sizeof($firms_details); $i++) { ?>
									<tr id="table_row" class="row-hover border border-light">
										<td><span class="badge title mb-1 borderless hover-border"><?php echo $i+1; ?></span></td>
										<td><span class="badge title borderless"><?php $explode_date = explode(' ',$customer_payment_details[$i]['transaction_date']);
											echo $explode_date[0]; ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo $all_application_type[$firms_details[$i]['certification_type']]; ?></span></td>
										<td><?php if(!empty($all_ro_office[$ro_id[$i]['ro_id']])) { ?> <span class="badge subtitle borderless"> <?php echo $all_ro_office[$ro_id[$i]['ro_id']]; } ?> </span></td>
										<td class="text-right"><span class="badge title borderless"><?php echo $all_states[$firms_details[$i]['state']]; ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo $all_district[$firms_details[$i]['district']]; ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo number_format($customer_payment_details[$i]['amount_paid'],2); ?></span></td>
									</tr>

									<?php
										if(!empty($ca_application_payment_total)) {
											if($i == max($ca_application_payment_total)) { ?>
												<tr id="table_row" class="row-hover border border-light">
													<td colspan="6" class="table-heading bg-dark">CA Applications Payment Total</td>
													<td><span class="badge title borderless"><?php echo number_format($printing_payment,2); ?></span></td>
												</tr>
										<?php } } ?>
									<?php
										if(!empty($laboratory_application_payment_total)) {
											if($i == max($laboratory_application_payment_total)) { ?>
												<tr id="table_row" class="row-hover border border-light">
													<td colspan="6" class="table-heading bg-dark">Printing Applications Payment Total</td>
													<td><span class="badge title borderless"><?php echo number_format($printing_payment,2); ?></span></td>
												</tr>
										<?php } } ?>
									<?php
										if(!empty($laboratory_application_payment_total)) {
											if($i == max($laboratory_application_payment_total)) { ?>
												<tr id="table_row" class="row-hover border border-light">
													<td colspan="6" class="table-heading bg-dark">Laboratory Applications Payment Total</td>
													<td><span class="badge title borderless"><?php echo  number_format($lab_payment,2); ?></span></td>
												</tr>
										<?php } } ?>
									<?php } } ?>

									<?php $j=''; if($report_for == 'both' || $report_for == 'renewal') { ?>
										<?php if(!empty($firms_details)) { ?><tr><td colspan="6" class="table-heading bg-dark">Renewal Application Payment Details</tr><?php } ?>
										<?php for ($j=0; $j<sizeof($renewal_firms_details); $j++) { ?>
											<tr id="table_row" class="row-hover border border-light">
												<td><span class="badge subtitle mb-1 borderless hover-border"><?php echo $j+1;?></span></td>
												<td><span class="badge title borderless"><?php $explode_date = explode(' ',$renewal_customer_payment_details[$j]['transaction_date']);
													echo $explode_date[0]; ?></span></td> <!-- ['Dmi_renewal_applicant_payment_detail'] -->
												<td><span class="badge title borderless"><?php echo  $all_application_type[$renewal_firms_details[$j]['certification_type']];?></span></td> <!-- ['Dmi_firm'] -->
												<td><span class="badge subtitle borderless"><?php if(!empty($all_ro_office[$renewal_ro_id[$j]['ro_id']])){ echo $all_ro_office[$renewal_ro_id[$j]['ro_id']]; } ?></span></td> <!-- ['Dmi_district'] -->
												<td><span class="badge subtitle borderless"><?php echo  $all_states[$renewal_firms_details[$j]['state']]; ?></span></td> <!-- ['Dmi_firm'] -->
												<td><span class="badge subtitle borderless"><?php echo  $all_district[$renewal_firms_details[$j]['district']]; ?></span></td> <!-- ['Dmi_firm'] -->
												<td><span class="badge title borderless"><?php echo  number_format($renewal_customer_payment_details[$j]['amount_paid'],2); ?></span></td> <!-- ['Dmi_renewal_applicant_payment_detail'] -->
											</tr>

										<?php
											if(!empty($renewal_ca_application_payment_total)) {
												if($j == max($renewal_ca_application_payment_total)) { ?>
													<tr>
														<td colspan="6" class="table-heading bg-dark">CA Renewal Applications Payment Total</td>
														<td><span class="badge title borderless"><?php echo  number_format($renewal_ca_payment,2); ?></span></td>
													</tr>
											<?php } } ?>
										<?php
											if(!empty($renewal_printing_application_payment_total)) {
												if($j == max($renewal_printing_application_payment_total)) { ?>
													<tr>
														<td colspan="6" class="table-heading bg-dark">Printing Renewal Applications Payment Total</td>
														<td><span class="badge title borderless"><?php echo  number_format($renewal_printing_payment,2); ?></span></td>
													</tr>
											<?php } } ?>
										<?php
											if(!empty($renewal_laboratory_application_payment_total)) {
												if($j == max($renewal_laboratory_application_payment_total)) { ?>
													<tr>
														<td colspan="6" class="table-heading bg-dark">Laboratory Renewal Applications Payment Total</td>
														<td><span class="badge title borderless"><?php echo  number_format($renewal_lab_payment,2); ?></span></td>
													</tr>
											<?php } } ?>
									<?php } } ?>

									<?php  if(!empty($firms_details) || !empty($renewal_firms_details)) { ?>
										<?php  if($i == sizeof($firms_details) || $j == sizeof($renewal_firms_details)) { ?>
											<tr>
												<td colspan="6" class="table-heading bg-dark">Grant Total (<?php echo ucfirst($report_for); ?>)</td>
												<td ><span class="badge title borderless"><?php  echo   number_format($ca_payment+$printing_payment+$lab_payment+$renewal_lab_payment
																					 +$renewal_ca_payment+$renewal_printing_payment,2); ?></span></td>
											</tr>
									<?php } }
									else { ?>
										<tr><td colspan="7"><?php echo "NO Records Available"; ?></td></tr>
									<?php }  ?>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
		</section>

		<div class="ml-3 mt-3">
			<h5><a href="<?php echo $this->request->getAttribute('webroot');?>reports/<?php echo $backAction; ?>" class="report-back-button btn back-btn" role="button">Back to All Reports</a>
				<?php //echo $this->Html->link('Back', array('controller' => 'reports', 'action'=>'report_types')); ?>
			</h5>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>


<?php echo $this->Html->script('Reports/payment_details_report'); ?>
