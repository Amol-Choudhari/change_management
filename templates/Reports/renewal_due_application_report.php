
<?php echo $this->Html->css('Reports/renewal_due_application_report') ?>

<?php  // Change on 5/11/2018 : Assign class attribute to all search filter field and comment the value attribute   - By Pravin Bhakare ?>
<div class="content-wrapper bg-bg">
	<div class="content-header page-header" id="page-load">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h6 class="m-0 ml-3"><?php echo 'Renewal Due Application Report'; ?></h6>
				</div>
				<div class="col-sm-6 my-auto">
					<ol class="breadcrumb float-sm-right">
						<span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
						<span class="my-auto"><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge bg-light"><?php echo $this->Html->link('All Reports', array('controller' => 'reports', 'action'=>'report_types'));?></a></span></span>
						<span class=""><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge page-header">
							<?php echo 'Renewal Due Application Report'; ?></span>
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
											<?php echo $this->form->input('state', array('type'=>'select', 'options'=>$all_states, 'label'=>false, 'id'=>'state', 'empty'=>'All States', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
								  	</div>
									<div class="col-sm-2" id="office_all">
                    					<div class="form-group">
											<!-- <label>District</label> -->
											<?php echo $this->form->input('district', array('type'=>'select', 'label'=>false, 'id'=>'district',  'empty'=>'All Districts', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label>Application Type</label> -->
											<?php echo $this->form->input('application_type', array('type'=>'select', 'options'=>$all_application_type,'label'=>false, 'empty'=>'All Application Types', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label id="to_date_label">Year</label> -->
											<?php echo $this->form->input('year', array('type'=>'select', 'options'=>$dropdown_years,'label'=>false, 'escape'=>false, 'class'=>'form-control form-control-sm')); ?>
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
							if(!empty($application_type)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Application Type</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $all_application_type[$application_type]; $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($renewal_year)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Renewal Year</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $renewal_year;  $search_value = 'yes'; ?> </span>
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
			<div class="container-fluid border rounded-3 border-light bg-light p-3 shadow">
				<div class="row">
					<div class="col-md-12">

						<div class="table-responsive report-table-format">
							<table class="table table-sm rounded" id="renewal_due_application_report_table">
								<thead class="table-light">
									<tr class="rounded">
										<th><span class="table-heading">S.No</span></th>
										<th><span class="table-heading">Application ID</span></th>
										<th><span class="table-heading">Application Type</span></th>
										<th><span class="table-heading">Expiry Date</span></th>
										<th class="text-right"><span class="table-heading">State</span></th>
										<th><span class="table-heading">District</span></th>
									</tr>
								</thead>
								<tbody class="">
									<?php //Find and calculate the value for Sr.No column (Done by pravin 16-07-2018)
										if(!empty($renewal_user_details)) {
											$sr_no = 1 ; // updated by Ankur Jangid
										}
										for ($i=0; $i<sizeof($renewal_user_details); $i++) { ?>
									<tr id="table_row" class="row-hover border border-light">
										<td><span class="badge subtitle mb-1 borderless hover-border"><?php echo $sr_no; ?></span></td>
										<td><span class="badge title borderless"><?php echo $renewal_due_applications_id[$i]['customer_id']; ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo $all_application_type[$renewal_user_details[$i]['certification_type']]; ?></span></td>
										<td><span class="badge title borderless"><?php echo $application_expiry_date[$i]; ?></span></td>
										<td class="text-right"><span class="badge subtitle borderless"><?php echo $all_states[$renewal_user_details[$i]['state']]; ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo $all_district[$renewal_user_details[$i]['district']]; ?></span></td>
									</tr>
									<?php $sr_no++; }

										if(empty($renewal_user_details)) { ?>
									<tr>
										<td colspan="6" class="fs-4"><?php echo "NO Records Available"; ?></td>
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
			<h5><a href="<?php echo $this->request->getAttribute('webroot');?>reports/<?php echo $backAction; ?>" class="report-back-button btn back-btn" role="button">Back to All Reports</a>
				<?php //echo $this->Html->link('Back', array('controller' => 'reports', 'action'=>'report_types')); ?>
			</h5>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>

<?php echo $this->Html->script('Reports/renewal_due_application_report'); ?>
