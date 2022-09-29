<?php echo $this->Html->css('Reports/primary_user_details_report') ?>

<?php  // Change on 5/11/2018 : Assign class attribute to all search filter field and comment the value attribute   - By Pravin Bhakare ?>

<div class="content-wrapper bg-bg">
	<div class="content-header page-header" id="page-load">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h6 class="m-0 ml-3"><?php echo 'Primay User Details Report'; ?></h6>
				</div>
				<div class="col-sm-6 my-auto">
					<ol class="breadcrumb float-sm-right">
						<span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
						<span class="my-auto"><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge bg-light"><?php echo $this->Html->link('All Reports', array('controller' => 'reports', 'action'=>'report_types'));?></a></span></span>
						<span class=""><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge page-header">
							<?php echo 'Primay User Details Report'; ?></span>
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
				<div class="px-4 page-header" >
					<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'newly_added_firm')); ?>
					<div class="bg-transparent">
						<div id="search_by_options" class="">
							<div class="row report-filter ro_report-filter pt-2">
								<div class="col-sm-2">
									<div class="form-group">
										<!-- <label>State</label> -->
										<?php echo $this->form->input('state', array('type'=>'select', 'options'=>$all_states, 'label'=>false, 'id'=>'state', 'empty'=>'States List - All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<!-- <label>District</label> -->
										<?php echo $this->form->input('district', array('type'=>'select', 'label'=>false, 'id'=>'district',  'empty'=>'Districts List - All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<!-- <label>From Date</label> -->
										<?php echo $this->form->input('from_date', array('type'=>'text', 'value'=>$search_from_date,'label'=>false, 'id'=>'fromdate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'From Date')); ?>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<!-- <label id="to_date_label">To Date</label> -->
										<?php echo $this->form->input('to_date', array('type'=>'text', 'value'=>$search_to_date,'label'=>false, 'id'=>'todate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'To Date')); ?>
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
									<?php echo $this->element('download_report_excel_format/report_download_button'); ?>
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
							if(!empty($state)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">State</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $all_states[$state];  $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($district)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">District</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $all_district[$district];   $search_value = 'yes'; ?> </span>
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
						<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
								<div class="modal-content">
									<div class="modal-header px-3 py-1 page-header">
										<h5 class="modal-title" id="exampleModalLabel">
											<span>Primary User</span><i class="fas fa-caret-right px-2"></i>
											<span class="bg-light rounded px-1 fwb" id="customer_id"></span><i class="fas fa-chevron-right px-2 fs80"></i>
											<span>Added Firms Details</span>
										</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body py-1" id="model-body">
										<div class="table-responsive report-table-format">
											<table class="table table-sm rounded mt-2" id="firm_details_table">
												<thead class="table-light">
													<tr class="rounded">
														<th class="text-right"><span class="table-heading">Firm/Primises ID</span></th>
														<th><span class="table-heading">Firm/Primises Name</span></th>
														<th><span class="table-heading">Application Type</span></th>
														<th class="text-right"><span class="table-heading">State</span></th>
														<th><span class="table-heading">District</span></th>
														<th class="text-right"><span class="table-heading">Date</span></th>
														<th><span class="table-heading">Time</span></th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="table-responsive report-table-format">
							<table class="table table-sm rounded" id="primary_user_details_report_table">
								<thead class="table-light">
									<tr class="rounded">
										<th><span class="table-heading">S.No</span></th>
										<th><span class="table-heading">Company ID</span></th>
										<th class="text-right"><span class="table-heading">State</span></th>
										<th><span class="table-heading">District</span></th>
										<th class="text-right"><span class="table-heading">Date</span></th>
										<th><span class="table-heading">Time</span></th>
										<th class="text-center"><span class="table-heading">+</span></th>
									</tr>
								</thead>
								<tbody class="">
									<?php
										//Find and calculate the value for Sr.No column (Done by pravin 16-07-2018)
										if(!empty($primary_user_details)) {
											$sr_no = 1 ;
										}
										for ($i=0; $i<sizeof($primary_user_details); $i++) { ?>
										<tr id="table_row" class="row-hover border border-light" data-toggle="modal" data-target="#exampleModal">
											<td><span class="badge subtitle mb-1 borderless hover-border"><?php echo $sr_no; ?></span></td>
											<td class="title borderless fs75fwb"><?php echo  $primary_user_details[$i]['customer_id']; ?></td>
											<td class="text-right"><span class="badge subtitle borderless"><?php echo  $all_states[$primary_user_details[$i]['state']]; ?></span></td>
											<td><span class="badge subtitle borderless"><?php echo  $all_district[$primary_user_details[$i]['district']]; ?></span></td>
											<?php $explode_date = explode(' ',$primary_user_details[$i]['created']); ?>
											<td class="text-right"><?php if($primary_user_details[$i]['created'] == null) { echo $primary_user_details[$i]['created']; } else { ?>
												<span class="badge title borderless"><?php echo $explode_date[0]; } ?></span>
											<td><?php if($primary_user_details[$i]['created'] == null) { echo $primary_user_details[$i]['created']; } else { ?> 
												<span class="badge subtitle subtitle-2 rounded px-1 borderless"><?php echo $explode_date[1]; } ?></span></td>
											<td class="text-center"><span class="badge title mb-1 borderless hover-border"><?php echo '+ FIRM DETAILS'; ?></span></td>
										</tr>

										<?php $sr_no++; } if(empty($primary_user_details)) { ?>
										<tr>
											<td colspan="7" class="fs-4"><?php echo "NO Records Available"; ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	<div class="ml-3 mt-3">
		<h5><a href="<?php echo $this->request->getAttribute('webroot');?>reports/<?php echo $backAction; ?>" class="report-back-button btn back-btn shadow" role="button">Back to All Reports</a>
			<?php //echo $this->Html->link('Back', array('controller' => 'reports', 'action'=>'report_types')); ?>
		</h5>
	</div>
	<?php echo $this->Form->end(); ?>
</div>

<?php echo $this->Html->script('Reports/primary_user_details_report'); ?>
