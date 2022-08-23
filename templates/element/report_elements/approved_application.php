<?php // Change on 3/11/2018 : Assign class attribute to all search filter field and comment the value attribute   - By Pravin Bhakare ?>
<?php echo $this->Html->css('Reports/approved_applications_report'); ?>

	<div class="content-wrapper bg-bg">
		<div class="content-header page-header" id="page-load">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h6 class="m-0 ml-3"><?php if($approved_application_type == 'renewal') { echo 'Approved Renewal Applications Report'; } else { echo 'Approved New and Old Applications Report';	} ?></h6>
					</div>
					<div class="col-sm-6 my-auto">
						<ol class="breadcrumb float-sm-right">
							<span class="badge bg-light my-auto"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></span>
							<span class="my-auto"><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge bg-light"><?php echo $this->Html->link('All Reports', array('controller' => 'reports', 'action'=>'report_types'));?></a></span></span>
							<span class=""><i class="fas fa-chevron-right px-2 fs80"></i><span class="badge page-header">
								<?php if($approved_application_type == 'renewal') { echo 'Approved Renewal Applications Report'; } else { echo 'Approved New and Old Applications Report';	} ?></span>
							</span>
						</ol>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>

	  	<div class="container-fluid">
      		<div class="row">
        		<div class="col-md-12 bg-bg">
					<div class="px-4 page-header" > <!-- style="background:#F9F1F1;" -->
						<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'approved_application_report')); ?>
						    <div class="bg-transparent">
							    <div id="search_by_options" class="">
								    <div class="row report-filter pt-2">
									    <div class="col-sm-3">
                    					    <div class="form-group">
											<?php echo $this->form->input('application_type', array('type'=>'select', 'value'=>$search_application_type_id, 'options'=>$application_type_xy, 'label'=>false, 'multiple'=>'multiple', 'id'=>'application_type', 'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
										</div>
								  	</div>
									<div class="col-sm-2" id="office_all">
                    					<div class="form-group">
											<?php echo $this->form->input('office', array('type'=>'select', 'value'=>$application_approved_office, 'options'=>$ro_office, 'label'=>false,  'multiple'=>'multiple', 'id'=>'office',  'escape'=>false, 'class'=>'form-control form-control-sm search_field')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
											<?php echo $this->form->input('from_date', array('type'=>'text', 'value'=>$search_from_date,'label'=>false, 'id'=>'fromdate', 'empty'=>'select', 'escape'=>false, 'class'=>'form-control form-control-sm search_field', 'placeholder'=>'From Date')); ?>
									  	</div>
								  	</div>
									<div class="col-sm-2">
                    					<div class="form-group">
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

		<div class="bg-bg">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="mx-5">
							<?php ?> <span class="badge bg-light shadow">RESULT</span><i class="fas fa-chevron-right px-2 fs80"></i> <?php
								if(!empty($search_application_type_id)) {
								?>  <span class="badge rounded-pill bg-grad1 shadow">Application Type</span>
										<i class="fas fa-caret-right"></i>
										<span class="badge bg-grad2 mr-3 shadow"> <?php foreach($search_application_type_id as $application_type_id) {
											echo $application_type_xy[$application_type_id];
										} $search_value = 'yes'; ?> </span>
									<?php
								}
								if(!empty($application_approved_office)) {
								?>  <span class="badge rounded-pill bg-grad1 shadow">RO Office</span>
										<i class="fas fa-caret-right"></i>
										<span class="badge bg-grad2 mr-3 shadow"> <?php foreach($application_approved_office as $approved_office_id) {
											echo $ro_office[$approved_office_id];
										}  $search_value = 'yes'; ?> </span>
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
								<table class="table table-sm rounded table-bordered" id="approved_applications_report_table">
									<thead class="table-light">
										<tr class="rounded">
											<th><span class="table-heading">S.No</span></th>
											<th><span class="table-heading">Process</span></th>
											<th><span class="table-heading">Application ID</span></th>
											<th class="text-right"><span class="table-heading">Application Type</th>
											<th><span class="table-heading">Application Form</th>
											<th class="text-right"><span class="table-heading">Approved Office</span></th>
											<th><span class="table-heading">User ID</span></th>
											<th><span class="table-heading">Commodity</span></th>
											<th class="text-right"><span class="table-heading">Date</span></th>
											<th><span class="table-heading">Time</span></th>
											<th><span class="table-heading">Firm Details</span></th>
											<th><span class="table-heading">TBL Details</span></th>
											<th><span class="table-heading">Laboratory Details</span></th>
										</tr>
									</thead>
									<tbody class="">
										<?php //Find and calculate the value for Sr.No column (Done by pravin 16-07-2018)
											if(!empty($date)) {
												$sr_no = 1 ; // updated by Ankur Jangid
											}
											for ($i=0; $i<sizeof($date); $i++) { ?>

												<tr id="table_row" class="row-hover border border-light">
													<td><span class="badge subtitle mb-1 borderless hover-border"><?php echo $sr_no; ?></span></td>
													<td><span class="badge subtitle borderless badge-pill bg-green"><?php echo $approved_application_type[$i];?></span></td>
													<td><span class="badge subtitle borderless"><?php echo $application_customer_id[$i]; ?></span></td>
													<td class="text-right"><?php $explode_app_type = explode('(',$application_type[$i]); ?>
														<span class="badge title borderless"><?php echo $explode_app_type[0]; ?> </span>
													</td>
													<td><?php $explode_app_type = explode('(',$application_type[$i]); ?>
														<span class="badge sybtitle borderless"><?php $explode_form = explode(')',$explode_app_type[1]);
														echo $explode_form[0]; ?></span>
													</td>
													<td class="text-right"><span class="badge subtitle borderless"><?php echo $user_office[$i]; ?></span></td>
													<td><span class="badge title borderless"><?php echo $application_user_email_id[$i]; //for email encoding ?></span></td>
													<td><?php if($commodity_list[$i] == null) { echo $commodity_list[$i]; }	else { $explode_commodity_list = explode(',',$commodity_list[$i]);
															for($j=0; $j<sizeof($explode_commodity_list); $j++) { ?>
																<span class="badge subtitle borderless hover-border"><?php echo $explode_commodity_list[$j]; ?></span>
														<?php } } ?>
													</td>
													<?php $explode_date = explode(' ',$date[$i]); ?>
													<td class="text-right"><?php if($date[$i] == null) { echo $date[$i]; } else { ?>
														<span class="badge title mb-1 borderless"><?php echo $explode_date[0]; } ?></span>
													<td><?php if($date[$i] == null) { echo $date[$i]; } else { ?>
														<span class="badge subtitle subtitle-2 rounded px-1 borderless"><?php echo $explode_date[1]; } ?></span>
													</td>

													<td><span class="badge borderless">Name : <?php echo $name_of_the_firm[$i];?></span> </br>
														<span class="badge borderless">Address : <?php echo $address_of_the_firm[$i];?></span></br>
														<span class="badge borderless">Contact : <?php echo $contact_details_of_the_firm[$i];?></span>
													</td>
													
													<?php if ($approved_TBL_details_tbl_name[$i][0] == '--') { ?>
														<td>--</td>
													<?php }else{ ?>
														<?php $countTbl = count($approved_TBL_details_tbl_name[$i]); ?>

														<td>
															<?php for($j=0;$j<$countTbl;$j++){  ?>
																<span class="badge">TBL Name: <?php echo $approved_TBL_details_tbl_name[$i][$j];?></span>
																</br>
																<span class="badge">TBL Reg No: <?php echo $approved_TBL_details_tbl_registered_no[$i][$j];?></span>
																</br></br>
															<?php } ?>
														</td>

													<?php } ?>

													<?php if ($laboratory_details_name[$i] == '--') { ?>
														<td>--</td>
													<?php }else{ ?>
														<td><span class="badge">Name : <?php echo $laboratory_details_name[$i];?></span>
															</br>
															<span class="badge">Address : <?php echo $laboratory_details_address[$i];?></span>
														</td>
													<?php } ?>
												</tr>
											<?php $sr_no++; }

											if(empty($date)) { ?>
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
				<h5><a href="<?php echo $this->request->getAttribute('webroot');?>reports/<?php echo $backAction; ?>" class="report-back-button btn back-btn" role="button">Back to All Reports</a>
					<?php //echo $this->Html->link('Back', array('controller' => 'reports', 'action'=>'report_types')); ?>
				</h5>
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
</div>

<?php echo $this->Html->script('Reports/approved_applications_report'); ?>
