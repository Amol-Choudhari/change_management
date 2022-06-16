<?php echo $this->Html->css('Reports/newly_added_firm_list_report') ?>
<?php ?>
<div class="content-wrapper bg-bg">
	<div class="content-header page-header" id="page-load">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h6 class="m-0 ml-3"><?php echo 'Newly Added Firms Report (Not Final Submitted)'; ?></h6>
				</div>
				<div class="col-sm-6 my-auto">
					<ol class="breadcrumb float-sm-right">
						<span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
						<span class="my-auto"><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge bg-light"><?php echo $this->Html->link('All Reports', array('controller' => 'reports', 'action'=>'report_types'));?></a></span></span>
						<span class=""><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge page-header">
							<?php echo 'Newly Added Firms Report (Not Final Submitted)'; ?></span>
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
									<div class="col-sm-3" id="office_all">
                    					<div class="form-group">
											<!-- <label>Company User ID</label> -->
											<?php echo $this->form->input('company_id', array('type'=>'text', 'value'=>'', 'label'=>false,  'empty'=>'All', 'id'=>'company_id',  'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'Company User ID')); ?>
										</div>
								  	</div>
									<div class="col-sm-3">
                    					<div class="form-group">
											<!-- <label>Application Type</label> -->
											<?php echo $this->form->input('application_type', array('type'=>'select', 'value'=>'', 'options'=>$application_type_array, 'label'=>false, 'multiple'=>'multiple', 'id'=>'application_type', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-3">
                    					<div class="form-group">
											<!-- <label>State</label> -->
											<?php echo $this->form->input('state', array('type'=>'select', 'options'=>$states, 'label'=>false, 'id'=>'state', 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
								</div>

								<div class="row report-filter pb-2">
									<div class="col-sm-3" id="office_all">
                    					<div class="form-group">
											<!-- <label>District</label> -->
											<?php echo $this->form->input('district', array('type'=>'select', 'label'=>false, 'id'=>'district',  'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-3">
                    					<div class="form-group">
											<!-- <label>From Date</label> -->
											<?php echo $this->form->input('from_date', array('type'=>'text', 'value'=>'','label'=>false, 'id'=>'fromdate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'From Date')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-3">
                    					<div class="form-group">
											<!-- <label id="to_date_label">To Date</label> -->
											<?php echo $this->form->input('to_date', array('type'=>'text', 'value'=>'','label'=>false, 'id'=>'todate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'To Date')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-1">
										<button id="search_btn" type="submit" name="search_logs" class="btn text-light option-menu-btn" value="Search" data-bs-toggle="tooltip" data-bs-placement="top" title="Search">
											<i class="fas fa-search"></i>
										</button>
										<!-- <input style="background:#747474; color:#fff;" id="search_btn" type="submit" name="search_logs" class="form-control" value="Search" > -->
									</div>
									<div class="col-sm-1">
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
						<?php ?> <span class="badge bg-light shadow">RESULT</span><i class="fas fa-chevron-right px-2 fs80" ></i> <?php
							if(!empty($company_id)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Company ID</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $company_id; $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($application_type)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Application Type</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php foreach($application_type as $application_type_id) {
										echo $application_type_array[$application_type_id];
										}  $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($state)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">State</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $states[$state];  $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($district)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">District</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $all_district_name[$district];  $search_value = 'yes'; ?> </span>
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
							<table class="table table-sm rounded" id="newly_added_firm_list_report_table">
								<thead class="table-light">
									<tr class="rounded">
										<th><span class="table-heading">S.No</span></th>
										<th><span class="table-heading">Company ID</span></th>
										<th class="text-right"><span class="table-heading">Firm/Primises ID</span></th>
										<th><span class="table-heading">Firm/Primises Name</span></th>
										<th class="text-right"><span class="table-heading">Application Type</span></th>
										<th><span class="table-heading">Application Form</span></th>
										<th class="text-right"><span class="table-heading">State</span></th>
										<th><span class="table-heading">District</span></th>
										<th class="text-right"><span class="table-heading">Date</span></th>
										<th><span class="table-heading">Time</span></th>
									</tr>
								</thead>
								<tbody class="">
									<?php //Find and calculate the value for Sr.No column (Done by pravin 16-07-2018)
										if(!empty($firms_data_details)) {
											$sr_no = 1 ; // updated by Ankur Jangid
										}
										for ($i=0; $i<sizeof($firms_data_details); $i++) { ?>
									<tr id="table_row" class="row-hover border border-light">
										<td><span class="badge subtitle mb-1 borderless hover-border"><?php echo $sr_no; ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo $firms_data_details[$i]['customer_primary_id']; ?></span></td>
										<td class="text-right"><span class="badge title borderless"><?php echo $firms_data_details[$i]['customer_id']; ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo $firms_data_details[$i]['firm_name']; ?></span></td>
										<td class="text-right"><?php $explode_app_type = explode('(',$application_type_name[$i]); ?>
											<span class="badge p-0 subtitle mb-1 borderless"><?php echo $explode_app_type[0]; ?> </span></td>
										<td><?php $explode_app_type = explode('(',$application_type_name[$i]); ?>
											<span class="badge rounded-pill bg-grad3 px-1 subtitle subtitle-2 rounded"><?php $explode_form = explode(')',$explode_app_type[1]);
											echo $explode_form[0]; ?></span></td>
										<td class="text-right"><span class="badge subtitle borderless"><?php echo $firms_states[$i]; ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo $firms_districts[$i]; ?></span></td>
										<?php $explode_date = explode(' ',$firms_data_details[$i]['created']); ?>
										<td class="text-right"><?php if($firms_data_details[$i]['created'] == null) { echo $firms_data_details[$i]['created']; } else { ?>
											<span class="badge title borderless"><?php echo $explode_date[0]; } ?></span>
										<td><?php if($firms_data_details[$i]['created'] == null) { echo $firms_data_details[$i]['created']; } else { ?>
											<span class="badge subtitle subtitle-2 rounded px-1 borderless"><?php echo $explode_date[1]; } ?></span></td>
									</tr>
									<?php $sr_no++; }
										if(empty($firms_data_details)) { ?>
									<tr>
										<td colspan="8" class="fs-4"><?php echo "NO Records Available"; ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
		</section>

		<div class="ml-3 mt-3">
			<h5><a href="<?php echo $this->request->getAttribute('webroot');?>reports/<?php echo $backAction; ?>" class="report-back-button btn back-btn shadow" role="button">Back to All Reports</a>
				<?php //echo $this->Html->link('Back', array('controller' => 'reports', 'action'=>'report_types')); ?>
			</h5>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>

    <?php echo $this->Html->script('Reports/newly_added_firm_list_report'); ?>
