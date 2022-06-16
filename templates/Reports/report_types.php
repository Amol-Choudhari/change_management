
<?php ?>

<?php echo $this->Html->css('Reports/report_types') ?>

<div class="content-wrapper bg-bg">
	<div class="content-header page-header" id="page-load">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h5 class="m-0 ml-3"><?php echo 'Reports'; ?></h5>
				</div>
				<div class="col-sm-6 my-auto">
					<ol class="breadcrumb float-sm-right">
						<span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
						<span class=""><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge page-header">
							<?php echo 'All Reports'; ?></span>
						</span>
					</ol>
				</div>
				<div class="clearfix"></div>
			</div>
    	</div>
  	</div>

	<div class="bg-bg">
	 	<div class="container-fluid">
      		<div class="row text-center pt-2">
        		<div class="col-md-12">
					<div class="mx-5">
						<span class="badge bg-light shadow fs-6">Given Below is List of Reports</span>
					</div>
				</div>
      		</div>
    	</div>

		<section class="content form-middle">
			<div class="container-fluid border rounded-3 border-light bg-light p-3 shadow">
				<div class="row">
					<div class="col-md-12">

						<div class="table-responsive report-table-format">
							<table class="table table-sm table-striped rounded" id="newly_added_firm_list_report_table">
								<thead class="table-light">
									<tr class="rounded">
										<th><span class="table-heading">S.No</span></th>
										<th><span class="table-heading">Report Name</span></th>
										<th><span class="table-heading">Report Description</span></th>
									</tr>
								</thead>
								<tbody class="">
									<tr>
										<td><span class="badge subtitle">1</span></td>
										<td><a class="badge title text-info" href="aqcms_statistics">AQCMS Statistics</a></td>
										<td><span class="subtitle">Statistics of Primary/Corporate User, Total Firms Registered, Application Processed, Application Granted, Application Pending, Documents E-signed,  Report</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">2</span></td>
										<td><a class="badge title text-info" href="user_roles_logs_report">User Roles Logs History</a></td>
										<td><span class="subtitle">Report includes history of user role assignment and removal of roles.</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">3</span></td>
										<td><a class="badge title text-info" href="mo_allocation_logs_report">Scrutinizer Allocation Logs History</a></td>
										<td><span class="subtitle">Report includes  RO office history for Scrutinizer allocation and reallocation.</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">4</span></td>
										<td><a class="badge title text-info" href="io_allocation_logs_report">IO Allocation Logs History</a></td>
										<td><span class="subtitle">Report includes  RO office history for IO allocation and reallocation.</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">5</span></td>
										<td><a class="badge title text-info" href="ro_allocation_logs_report">RO incharge Allocation Logs History</a></td>
										<td><span class="subtitle">Report includes  RO office history for RO incharge allocation and reallocation.</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">6</span></td>
										<td><a class="badge title text-info" href="pending_new_applications_report">Pending New Applications</a></td>
										<td><span class="subtitle">Report includes list of pending new applications report for  RO/SO, Scrutinizer, IO, HO Scrutinizer, DY.AMA, JT.AMA, AMA.</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">7</span></td>
										<td><a class="badge title text-info" href="pending_renewal_applications_report">Pending Renewal Applications</a></td>
										<td><span class="subtitle">Report includes list of  pending renewal applications for RO/SO, Scrutinizer, IO.</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">8</span></td>
										<td><a class="badge title text-info" href="fifteen_day_pending_new_application">Pending New Applications (More than 15 Days)</a></td>
										<td><span class="subtitle">Report includes list of pending new applications for RO/SO, Scrutinizer, IO, HO Scrutinizer, DY.AMA, JT.AMA, AMA.</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">9</span></td>
										<td><a class="badge title text-info" href="fifteen_day_pending_renewal_application">Pending Renewal Applications (More than 15 Days)</a></td>
										<td><span class="subtitle">Report includes list of pending renewal applications for RO/SO, Scrutinizer, IO.</span></td>
									</tr>
									<tr class="row-hover">
										<td><span class="badge subtitle">10</span></td>
										<td><a class="badge title text-info" href="approved_new_application_type">Approved New and Old Applications</a></td>
										<td><span class="subtitle">Report includes list of Approved (final Granted) New and Old applications records</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">11</span></td>
										<td><a class="badge title text-info" href="approved_renewal_application_type">Approved Renewal Applications</a></td>
										<td><span class="subtitle">Report includes list of Approved (final Granted) Renewal applications records</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">12</span></td>
										<td><a class="badge title text-info" href="newly_added_firm_list_report">Newly Added Firms Report</a></td>
										<td><span class="subtitle">Report includes list of  newly added firms applications which are not submitted by user.</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">13</span></td>
										<td><a class="badge title text-info" href="primary_user_details_report">Primary User Details Report</a></td>
										<td><span class="subtitle borderless">Report includes list of primary user detail records.</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle mb-1 borderless hover-border">14</span></td>
										<td><a class="badge title text-info" href="renewal_due_application_report">Renewals Due application Report</a></td>
										<td><span class="subtitle">Report includes list of all renewal due firms records.</span></td>
									</tr>
									<tr>
										<td><span class="badge subtitle">15</span></td>
										<td><a class="badge title text-info" href="payment_details_report">Payment Details Report</a></td>
										<td><span class="subtitle">Report includes list of all application payment detail records.</span></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<?php echo $this->Html->script('Reports/report_types'); ?>
