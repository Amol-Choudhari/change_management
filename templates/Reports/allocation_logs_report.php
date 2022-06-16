
<?php echo $this->Html->css('Reports/allocation_logs_report') ?>

<?php // Change on 2/11/2018 : Assign class attribute to all search filter field   - By Pravin Bhakare	?>

<div class="content-wrapper">
	<div class="content-header page-header" id="page-load">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h6 class="m-0 ml-3"><?php echo $report_heading; ?></h6>
				</div>
				<div class="col-sm-6 my-auto">
				<ol class="breadcrumb float-sm-right">
					<span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
					<span class="my-auto"><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge bg-light"><?php echo $this->Html->link('All Reports', array('controller' => 'reports', 'action'=>'report_types'));?></a></span></span>
					<span class=""><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge page-header"><?php echo $report_heading; ?></span></span>
				</ol>
				</div>
			</div>
    	</div>
  	</div>

	<!-- <section class="content form-middle"> -->
    	<div class="container-fluid">
      		<div class="row">
        		<div class="col-md-12 bg-bg">

					<div class="px-4 page-header" >
						<?php echo $this->Form->create(); ?>
						<?php if($user_type == 'MO' || $user_type == 'IO'){ ?>
						<div class="bg-transparent">
							<div id="search_by_options" class="">
								<div class="row report-filter pt-2">
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('office', array('type'=>'select', 'value'=>$search_office,'options'=>$ro_office, 'label'=>false, 'id'=>'office', 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label>Application ID</label> -->
											<?php echo $this->form->input('application_id', array('type'=>'text', 'value'=>$application_id,'options'=>'', 'label'=>false, 'id'=>'user_id', 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'Application ID')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label>User Name(ID)</label> -->
											<?php echo $this->form->input('user_id', array('type'=>'select', 'value'=>$search_user_id,'options'=>$user_name_list, 'label'=>false, 'id'=>'user_id', 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
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
						<?php } ?>
						<?php if($user_type == 'RO') {?>
						<div class="bg-transparent">
							<div id="search_by_options" class="">
								<div class="row report-filter pt-2">
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('office', array('type'=>'select', 'value'=>$search_office,'options'=>$ro_office, 'label'=>false, 'id'=>'office', 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label>User Name(ID)</label> -->
											<?php echo $this->form->input('user_id', array('type'=>'select', 'value'=>$search_user_id,'options'=>$user_name_list, 'label'=>false, 'id'=>'user_id', 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
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
										<button id="search_btn" type="submit" name="search_logs" class="btn text-light option-menu-btn" value="Search" data-bs-toggle="tooltip" data-bs-placement="top" title="Search"><i class="fas fa-search mr-2 mx-auto"></i></button>
										<!-- <input style="background:#747474; color:#fff;" id="search_btn" type="submit" name="search_logs" class="form-control" value="Search" > -->
														</div>
									<div class="col-sm-1">
										<!-- Call the Downloading Report Button Element (Done by Pravin 13/3/2018) -->
										<?php echo $this->element('download_report_excel_format/report_download_button'); ?>
									</div>
								</div>

							</div>
						</div>
						<?php } ?>
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
							if(!empty($application_id)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Application ID</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $application_id;  $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($search_office)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Office</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $ro_office[$search_office];  $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($search_user_id)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">User Name</span>
									<i class="fas fa-caret-right"></i>
									<span class="mr-3">
										<span class="badge bg-grad2 shadow"> <?php $explode_user_name = explode('(',$user_name_list[$search_user_id]); $search_value = 'yes';
											echo $explode_user_name[0]; ?> </span>
										<span class="badge bg-grad1 shadow mr-3"><?php $explode_user_email = explode(')',$explode_user_name[1]);
											echo $explode_user_email[0];  ?> </span>
									</span>
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
							<table class="table table-sm rounded" id="allocation_logs_report_table">
								<thead class="table-light">
									<tr class="rounded">
										<th><span class="table-heading">S.No</span></th>
										<th><span class="table-heading">User Name(ID)</span></th>
										<th><span class="table-heading">User Email(ID)</span></th>
										<th><span class="table-heading">Posted Office</span></th>
										<?php if($user_id_field != 'ro_incharge_id') { ?>
										<th class="text-right"><span class="table-heading">Application ID</span></th>
										<th class="text-center"><span class="table-heading">Application Type</span></th>
										<?php } ?>
										<th class="text-right"><span class="table-heading">Date</span></th>
										<th><span class="table-heading">Time</span></th>
									</tr>
								</thead>
								<tbody class="">
									<?php
										//Find and calculate the value for Sr.No column (Done by pravin 16-07-2018)
										if(!empty($allocation_logs_details)) {
											$sr_no = 1 ;
										}
										for ($i=0; $i<sizeof($allocation_logs_details); $i++) { ?>
									<tr id="table_row" class="row-hover border border-light">
										<td><span class="badge subtitle mb-1 borderless hover-border"><?php echo $sr_no; ?></span></td>
										<td><?php if($user_name_detail[$i] == '---') { echo $user_name_detail[$i]; }
											else { $explode_user = explode('(',$user_name_detail[$i]); ?>
											<span class="badge title borderless"><?php echo $explode_user[0]; } ?> </span></td>
										<td class=""><?php if($user_name_detail[$i] == '---') { echo $user_name_detail[$i]; }
											else { $explode_user = explode('(',$user_name_detail[$i]); ?>
											<span class="badge subtitle borderless"><?php $explode_email = explode(')',$explode_user[1]); echo $explode_email[0]; } ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo 	$allocation_logs_details[$i][$office_field]; ?></span></td>
									<?php if($user_id_field != 'ro_incharge_id') { ?>
										<td class="text-right"><span class="badge title borderless"><?php echo  $allocation_logs_details[$i]['customer_id']; ?></span></td>
										<td class="text-center"><span class="badge rounded-pill bg-grad3 px-1 subtitle subtitle-2 rounded"><span class=""><?php echo  $allocation_logs_details[$i]['application_type']; ?></span></span>
										</td> <?php } ?>
										<?php $explode_date = explode(' ',$allocation_logs_details[$i]['created']); ?>
										<td class="text-right"><?php if($allocation_logs_details[$i]['created'] == null) { echo $allocation_logs_details[$i]['created']; } else { ?>
											<span class="badge title borderless"><?php echo $explode_date[0]; } ?></span>
										<td><?php if($allocation_logs_details[$i]['created'] == null) { echo $allocation_logs_details[$i]['created']; } else { ?>
											<span class="badge subtitle subtitle-2 rounded px-1 borderless"><?php echo $explode_date[1]; } ?></span></td>
									</tr>

									<?php $sr_no++; } if(empty($allocation_logs_details)){ ?>
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
<?php echo $this->Html->script('Reports/allocation_logs_report'); ?>
