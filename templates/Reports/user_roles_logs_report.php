
<?php echo $this->Html->css('Reports/user_roles_logs_report') ?>

<?php // Change on 1/11/2018 : Assign class attribute to all search filter field and comment the value attribute   - By Pravin Bhakare	?>

<div class="content-wrapper">
	<div class="content-header page-header" id="page-load">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h4 class="m-0 ml-3"><?php echo $report_name; ?></h4>
				</div>
				<div class="col-sm-6 my-auto">
				<ol class="breadcrumb float-sm-right">
					<span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
					<span class="my-auto"><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge bg-light"><?php echo $this->Html->link('All Reports', array('controller' => 'reports', 'action'=>'report_types'));?></a></span></span>
					<span class=""><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge page-header"><?php echo $report_name; ?></span></span>
				</ol>
				</div>
			</div>
    	</div>
  	</div>

	<!-- <section class="content form-middle"> -->
    	<div class="container-fluid">
      		<div class="row">
        		<div class="col-md-12 bg-bg">

					<div class="px-4 page-header">
						<?php echo $this->Form->create(null); ?>
						<div class="bg-transparent">
							<div id="search_by_options" class="">
								<div class="row report-filter pt-2">
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('office', array('type'=>'select', 'options'=>$ro_office, 'label'=>false, 'id'=>'office', 'empty'=>'RO Offices - All', 'escape'=>false ,'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label>User Roles</label> -->
											<?php echo $this->form->input('user_roles', array('type'=>'select', /*'value'=>$search_user_role,*/ 'options'=>$user_roles, 'label'=>false, 'id'=>'user_roles', 'empty'=>'User Roles - All', 'escape'=>false ,'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label>User Name(ID)</label> -->
											<?php echo $this->form->input('user_id', array('type'=>'select', /*'value'=>$search_user_id,*/ 'options'=>$user_name_details, 'label'=>false, 'id'=>'user_id', 'empty'=>'User Name (ID) - All', 'escape'=>false ,'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label>From Date</label> -->
											<?php echo $this->form->input('from_date', array('type'=>'text', /*'value'=>$search_from_date,*/ 'label'=>false, 'id'=>'fromdate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'From Date')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
											<!-- <label id="to_date_label">To Date</label> -->
											<?php echo $this->form->input('to_date', array('type'=>'text', /*'value'=>$search_to_date,*/ 'label'=>false, 'id'=>'todate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'To Date')); ?>
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
							if(!empty($search_user_role)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">User Roles </span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $user_roles[$search_user_role]; $search_value = 'yes'; ?> </span>
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
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $user_name_details[$search_user_id];  $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($search_from_date)) {
							?> 	<span class="badge rounded-pill bg-grad1 shadow">From Date</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $search_from_date;  $search_value = 'yes'; ?> </span>

								<span class="badge rounded-pill bg-grad1 shadow">To Date</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 shadow"> <?php echo $search_to_date;  $search_value = 'yes'; ?> </span>
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
							<table class="table table-sm rounded" id="user_roles_logs_report_table">
								<thead class="table-light">
									<tr class="rounded">
										<th><span class="table-heading">Sr.No</span></th>
										<th class="text-right"><span class="table-heading">User Name(ID)</span></th>
										<th><span class="table-heading">User Office</span></th>
										<th class="text-center"><span class="table-heading">Date</span></th>
										<th><span class="table-heading">Roles As on Date</span></th>
										<th class="text-right"><span class="table-heading">Added Roles</span></th>
										<th class="text-left"><span class="table-heading">Removed Roles</span></th>
									</tr>
								</thead>
								<tbody class="">
									<?php //Find and calculate the value for Sr.No column (Done by pravin 16-07-2018)
										if(!empty($user_role_logs_history_details)) {
											$sr_no = 1 ; // updated by Ankur Jangid
										}

										for ($i=0; $i<sizeof($user_role_logs_history_details); $i++) { ?>

											<tr id="table_row" class="row-hover border border-light">
												<td><span class="badge subtitle mb-1 borderless hover-border"><?php echo $sr_no;?></td></span>
												<td class="text-right">
													<?php if ($user_name_detail[$i] == '---') { 
														echo $user_name_detail[$i]; 
													} else { 
														$explode_user = explode('(',$user_name_detail[$i]); ?>
													<span class="badge title borderless"><?php echo $explode_user[0]; ?> </span><br><span class="badge subtitle borderless"><?php $explode_email = explode(')',$explode_user[1]); echo $explode_email[0]; } ?></span></td>
												<td class="text-left"><span class="badge subtitle borderless"><?php echo $user_office[$i]; ?></span></td>
												<td><?php $explode_date = explode(' ',$user_role_logs_history_details[$i]['created']); ?>
													<span class="badge title mb-1 borderless"><?php echo $explode_date[0]; ?> </span><br><span class="badge subtitle subtitle-2 rounded px-1 borderless"> <?php echo $explode_date[1]; ?></span></td>
												<td><?php for ($j=0; $j<sizeof($user_roles_name_view_list[$i]); $j++) {
															?> <span class="badge subtitle roles-badge borderless hover-border"> <?php echo $user_roles_name_view_list[$i][$j]; ?> </span> <?php
													}
												?></td>
												<td class="text-right"><?php for ($j=0; $j<sizeof($add_user_roles_name_view_list[$i]); $j++) {
														?> <span class="badge subtitle roles-badge borderless hover-border"> <?php echo $add_user_roles_name_view_list[$i][$j]; ?> </span> <?php
													}
												?></td>
												<td class="text-left"><?php for ($j=0; $j<sizeof($remove_user_roles_name_view_list[$i]); $j++) {
														?> <span class="badge subtitle roles-badge borderless hover-border"> <?php echo $remove_user_roles_name_view_list[$i][$j]; ?> </span> <?php
													}
												?></td>
											</tr>

										<?php $sr_no++; }
										if(empty($user_role_logs_history_details)) { ?>
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

		<div class="ml-3 mt-3">
			<h5><a href="<?php echo $this->request->getAttribute('webroot');?>reports/<?php echo $backAction; ?>" class="report-back-button btn back-btn shadow" role="button">Back to All Reports</a>
				<?php //echo $this->Html->link('Back', array('controller' => 'reports', 'action'=>'report_types')); ?>
			</h5>
		</div>

	</div>
	<?php echo $this->Form->end(); ?>
</div>

<?php echo $this->Html->script('Reports/user_roles_logs_report'); ?>
