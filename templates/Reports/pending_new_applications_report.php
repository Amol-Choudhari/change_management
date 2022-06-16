
<?php echo $this->Html->css('Reports/pending_new_applications_report') ?>

<?php // Change on 2/11/2018 : Assign class attribute to all search filter field and comment the value attribute   - By Pravin Bhakare ?>
<div class="content-wrapper">
	<div class="content-header page-header" id="page-load">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
  					<?php if($report_name == 'Pending New Applications Report ( More than 15 Days)') { ?>
							<h4 class="m-0 ml-3"><?php echo $report_name; ?></h4>
						<?php  } else { ?>
							<h4 class="m-0 ml-3"><?php echo $report_name; ?></h4>
						<?php } ?>
				</div>
				<div class="col-sm-6 my-auto">
					<ol class="breadcrumb float-sm-right">
						<span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
						<span class="my-auto"><i class="fas fa-chevron-right px-2 fs80" ></i><span class="badge bg-light"><?php echo $this->Html->link('All Reports', array('controller' => 'reports', 'action'=>'report_types'));?></a></span></span>
						<span class=""><i class="fas fa-chevron-right px-2 fs80" ></i><span class="badge page-header"><?php echo $report_name; ?></span></span>
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

					<div class="px-4 page-header">
						<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'pending_application_report')); ?>
						<div class="bg-transparent">
							<div id="search_by_options" class="">
								<div class="row report-filter ro_report-filter pt-2">
									<div class="col-sm-3">
                          				<div class="form-group">
										  	<!-- <label>Application Type</label> -->
											<?php echo $this->form->input('application_type', array('type'=>'select', 'value'=>$search_application_type_id, 'options'=>$application_type_xy, 'label'=>false, 'multiple'=>'multiple', 'id'=>'application_type', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>

									<div class="col-sm-3">
										<div class="form-group">
											<!-- <label>User Role</label> -->
											<?php echo $this->form->input('user_role', array('type'=>'select', 'value'=>$search_user_role,'options'=>$user_roles_xy, 'label'=>false, 'id'=>'user_role', 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>

									<div class="col-sm-3" id="office_all">
										<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('office', array('type'=>'select', 'value'=>'', 'label'=>false,  'empty'=>'All', 'id'=>'office',  'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>
									<div class="col-sm-3" id="office_ro">
										<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('ro_office', array('type'=>'select', 'value'=>$ro_office_id, 'options'=>$ro_office, 'label'=>false, 'multiple'=>'multiple', 'id'=>'office_ro_input',  'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>
									<div class="col-sm-3" id="office_mo">
										<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('mo_office', array('type'=>'select', 'value'=>$mo_office_id, 'options'=>$ro_office, 'label'=>false, 'multiple'=>'multiple', 'id'=>'office_mo_input',  'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>
									<div class="col-sm-3" id="office_io">
										<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('io_office', array('type'=>'select', 'value'=>$io_office_id, 'options'=>$ro_office, 'label'=>false, 'multiple'=>'multiple', 'id'=>'office_io_input',  'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>
									<div class="col-sm-3" id="office_ho_mo">
										<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('ho_mo_office', array('type'=>'text', 'value'=>'HO MO/SMO', 'disabled'=>true, 'label'=>false, 'id'=>'office_ho_mo_input',  'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>

									<div class="col-sm-3" id="office_dyama">
										<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('dyama_office', array('type'=>'text', 'value'=>'DY.AMA','label'=>false,  'disabled'=>true, 'id'=>'office_dyama_input',  'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>
									<div class="col-sm-3" id="office_jtama">
										<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('jtama_office', array('type'=>'text', 'value'=>'JT.AMA', 'label'=>false,  'disabled'=>true, 'id'=>'office_jtama_input',  'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>
									<div class="col-sm-3" id="office_ama">
										<div class="form-group">
											<!-- <label>Office</label> -->
											<?php echo $this->form->input('ama_office', array('type'=>'text', 'value'=>'AMA', 'label'=>false, 'disabled'=>true, 'id'=>'office_ama_input',  'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>

								<div class="row report-filter pending_report-filter">
									<div class="col-sm-3" id="office_ama">
										<div class="form-group">
											<!-- <label>User Name(ID)</label> -->
											<?php echo $this->form->input('user_id', array('type'=>'select', 'value'=>'','label'=>false, 'id'=>'user_id', 'empty'=>'All', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
									</div>
									<div class="col-sm-3" id="office_ama">
										<div class="form-group">
											<!-- <label>From Date</label> -->
											<?php echo $this->form->input('from_date', array('type'=>'text', 'value'=>'','label'=>false, 'id'=>'fromdate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'From Date')); ?>
										</div>
									</div>
									<div class="col-sm-3" id="office_ama">
										<div class="form-group">
											<!-- <label id="to_date_label">To Date</label> -->
											<?php echo $this->form->input('to_date', array('type'=>'text', 'value'=>'','label'=>false, 'id'=>'todate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'To Date')); ?>
										</div>
									</div>
									<div class="col-sm-1">
										<div class="">
											<button id="search_btn" type="submit" name="search_logs" class="btn text-light option-menu-btn" value="Search" data-bs-toggle="tooltip" data-bs-placement="top" title="Search">
												<i class="fas fa-search"></i>
											</button>
											<!-- <input style="background:#747474; color:#fff;" id="search_btn" type="submit" name="search_logs" class="form-control" value="Search" > -->
										</div>
									</div>
									<div class="col-sm-1">
										<!-- Call the Downloading Report Button Element (Done by Pravin 13/3/2018) -->
										<?php echo $this->element('download_report_excel_format/report_download_button'); ?>
									</div>
								</div>
								<div class="clearfix"></div>

							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="clearfix"></div>

				</div>
      		</div>
    	</div>
  	<!-- </section> -->

	<div class="bg-bg">
	 	<div class="container-fluid">
      		<div class="row">
        		<div class="col-md-12">
					<div class="mx-5">
						<?php ?> <span class="badge bg-light shadow">RESULT</span><i class="fas fa-chevron-right px-2 fs" ></i> <?php
							if(!empty($search_application_type_id)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Application Type</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow">
										<?php foreach($search_application_type_id as $application_type_id) {
												echo $application_type_xy[$application_type_id];
											}   $search_value = 'yes'; ?>
									</span>
								<?php
							}
							if(!empty($search_user_role)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">User Role</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow"> <?php echo $user_roles_xy[$search_user_role];   $search_value = 'yes'; ?> </span>
								<?php
							}
							if(!empty($ro_office_id)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Office</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow">
										<?php foreach($ro_office_id as $office) {
											echo $ro_office[$office];
										}   $search_value = 'yes'; ?>
									</span>
								<?php
							}
							elseif(!empty($mo_office_id)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Office</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow">
										<?php foreach($mo_office_id as $office) {
											echo $ro_office[$office];
											}   $search_value = 'yes'; ?>
									</span>
								<?php
							}
							elseif(!empty($io_office_id)) {
							?>  <span class="badge rounded-pill bg-grad1 shadow">Office</span>
									<i class="fas fa-caret-right"></i>
									<span class="badge bg-grad2 mr-3 shadow">
										<?php foreach($io_office_id as $office) {
											echo $ro_office[$office];
											}  $search_value = 'yes'; ?>
									</span>
								<?php
							}
							?>
							<?php 	use App\Model\Table\DmiUserRolesTable; // added by Ankur Jangid
									$Dmi_user_role = New DmiUserRolesTable(); // Ankur Jangid updated

								if(!empty($search_user_email_id)) {
									$user_email_id_details = $Dmi_user_role->find('all')->select(['user_email_id'])->where(['id'=>$search_user_email_id])->first();
									$search_user_email = $user_email_id_details['user_email_id'];
								?>  <span class="badge rounded-pill bg-grad1 shadow">User ID</span>
										<i class="fas fa-caret-right"></i>
										<span class="badge bg-grad2 mr-3 shadow">
											<?php echo base64_decode($search_user_email);   $search_value = 'yes'; //for email encoding ?>
									</span>
								<?php
								}
							?> <?php
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
				<div class="row pt-2">
					<div class="col-md-12">

						<div class="table-responsive report-table-format">
							<table class="table table-sm rounded" id="pending-new-applications-report-data-table">
								<thead class="table-light">
									<tr class="rounded">
										<th><span class="table-heading">Sr.No</span></th>
										<th><span class="table-heading">Application ID</span></th>
										<th class="text-right"><span class="table-heading">Application Type</span></th>
										<th><span class="table-heading">Application Form</span></th>
										<th class="text-right"><span class="table-heading">Pending With</span></th>
										<th><span class="table-heading">Posted Office</span></th>
										<th><span class="table-heading">User ID</span></th>
										<th class="text-right"><span class="table-heading">Date</span></th>
										<th><span class="table-heading">Time</span></th>
									</tr>
								</thead>
								<tbody class="">
									<?php //Find and calculate the value for Sr.No column (Done by pravin 16-07-2018)
										if(!empty($date)) {
											$sr_no = 1 ; // updated by Ankur Jangid
										}
										for ($i=0; $i<count($date); $i++) { ?>
									<tr id="table_row" class="row-hover border border-light">
										<td><span class="badge title mb-1 borderless hover-border"><?php echo $sr_no; ?></span></td>
										<td><span class="badge title borderless"><?php echo $application_id[$i]; ?></span></td>
										<td class="text-right"><?php $explode_app_type = explode('(',$application_type[$i]); ?>
											<span class="badge title borderless"><?php echo $explode_app_type[0]; ?> </span></td>
										<td><?php $explode_app_type = explode('(',$application_type[$i]); ?>
											<span class="badge subtitle borderless">(<?php echo $explode_app_type[1]; ?></span></td>
										<td class="text-right"><span class="badge subtitle borderless"><?php echo $user_roles[$i]; ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo $user_office[$i]; ?></span></td>
										<td><span class="badge subtitle borderless"><?php echo base64_decode($user_email_id[$i]); //for email encoding ?></span></td>
										<?php $explode_date = explode(' ',$date[$i]); ?>
										<td class="text-right"><?php if($date[$i] == null) { echo $date[$i]; } else { ?>
											<span class="badge title borderless"><?php echo $explode_date[0]; } ?></span>
										<td><?php if($date[$i] == null) { echo $date[$i]; } else { ?>
											<span class="badge subtitle subtitle-2 rounded px-1 borderless"><?php echo $explode_date[1]; } ?></span></td>
									</tr>
									<?php $sr_no++; }

										if(empty($date)) { ?>
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

<?php echo $this->Html->script('Reports/pending_new_applications_report'); ?>
