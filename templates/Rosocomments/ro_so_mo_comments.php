
<?php echo $this->Html->css('ro_so_mo_comments') ?>

<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Communication</a></li>
						<li class="breadcrumb-item active">Dashboard</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<?php echo $this->Form->create(); ?>
						<div class="card card-Lightblue">
							<div class="card-header"><h3 class="card-title">Communication between Regional Office and Sub Office</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="alert alert-success form-middle col-md-6">
											<label>Applicant ID: <?php echo $this->getRequest()->getSession()->read('customer_id'); ?> - <?php echo $firm_name; ?></label>
										</div>
									</div>
								</div>
							</div>

							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-6">
											<label>View Application/Report</label>
											<div class="form-buttons">
												<div class="form-group row">
													<?php echo $this->Form->submit('Applicant Form', array('name'=>'view_applicant_forms', 'id'=>'view_applicant_forms', 'label'=>false, 'class'=>'btn btn-primary')); ?>
													<?php
														//applied bevo & export condition
														if (!empty($report_pdf_path) && $this->getRequest()->getSession()->read('current_level')!='level_1') {
															echo $this->Form->submit('Inspection Report', array('name'=>'view_inspection_reports', 'id'=>'view_inspection_reports', 'label'=>false, 'class'=>'btn btn-primary'));
														}
													?>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<label>Download PDF</label>
											<div class="form-buttons">
												<div class="form-group row">
													<a target="blank" href="<?php echo $download_application_pdf;?>" class="btn btn-primary" >Application PDF</a>
													<?php if (!empty($report_pdf_path) && $this->getRequest()->getSession()->read('current_level')!='level_1') { ?>
														<a target="blank" href="<?php echo $download_report_pdf;?>" class="btn btn-primary" >Inspection Report PDF</a>
													<?php }?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-md-12"><label>Previous Comments History</label>
											<div class="remark-history">
												<table class="table m-0 table-bordered table-hover table-striped">
													<thead class="tablehead">
														<tr>
															<th>Date</th>
															<th>Comment By</th>
															<th>Comment To</th>
															<th>Comment</th>
														</tr>
													</thead>
													<tbody>
														<?php
														if(!empty($ro_so_mo_comments)){
															$last_comment_by = null;
															foreach($ro_so_mo_comments as $comment_detail){

																//view only rows with values.
																if(!empty($comment_detail['comment_date'])){?>

																<tr>
																<td><?php echo $comment_detail['comment_date']; ?></td>
																<td><?php echo base64_decode($comment_detail['comment_by']); ?></td>
																<td><?php echo base64_decode($comment_detail['comment_to']); ?></td>
																<td><?php echo $comment_detail['comment']; ?></td>
																</tr>

														<?php }
															//added on 25-09-2017 to get last comment by to apply in condition below
															$last_comment_by = $comment_detail['comment_by'];
															$last_comment_to = $comment_detail['comment_to'];
															}
														}else{ ?>
															<tr><td>Currently there is no comments regarding this application.</td></tr>

														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-horizontal">
								<div class="card-body">
									<div class="col-sm-12">
										<div id="comment_box_with_btn">
											<div id="commentBox">
												<div class="row">
													<div class="col-sm-6">
														<div class="remark-current">
															<label>Current Comment : </label>
															<?php echo $this->Form->control('comment', array('type'=>'textarea', 'id'=>'check_save_reply', 'class'=>'form-control','escape'=>false, 'label'=>false, )); ?>
															<div id="error_save_reply"></div>
														</div>
													</div>
													<div class="col-sm-6">
														<div id="comment_to">
															<label>Comment To : </label>
															<?php
																if($commentWindow=='ro'){

																	if($HoInspectionExist == 'yes' && !empty($ho_allocation_details)){
																		$options=array('so'=>' Sub office','mo'=>' Scrutiny Officer','dy_ama'=>' HO Quality Control');
																	}else{
																		$options=array('so'=>' Sub office','mo'=>' Scrutiny Officer');
																	}

																	$attributes=array('legend'=>false, 'id'=>'comment_to', 'value'=>'so', 'label'=>true,);
																	echo $this->Form->radio('comment_to',$options,$attributes);

																}else{

																	$options=array('ro'=>' Regional officer');
																	$attributes=array('legend'=>false, 'id'=>'comment_to', 'value'=>'ro', 'label'=>true,);
																	echo $this->Form->radio('comment_to',$options,$attributes);
																}
															?>
															<div id="error_mo_allocation"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="form-horizontal">
								<div class="card-body">
									<div id="action-btns" class="form-buttons">
										<div class="row">
											<?php echo $this->Form->submit('Send Comment', array('name'=>'send_comment', 'id'=>'send_comment_btn', 'label'=>false,'class'=>'btn btn-success')); ?>
											<?php if ($commentWindow=='so') {
														
													//SO can grant only CA non Bevo appl, and with RO approval, on 23-09-2021 by Amol
													// Show final grant butto to SO user, if he have power to grant the application
													// For printing and CA Non Bevo application
													// Done by Pravin Bhakare 14-10-2021
													if($so_power_to_grant_appl == 'yes' && !empty($roApproved)){
														echo $this->Form->submit('Final Grant', array('name'=>'final_grant', 'id'=>'final_granted_btn', 'label'=>false,'class'=>'btn btn-success'));
													}else{
														echo $this->Form->submit('Comment to Applicant', array('name'=>'comment_to_applicant', 'id'=>'comment_to_applicant', 'label'=>false,'class'=>'btn btn-success'));

														if (!empty($allocation_deatils['level_2'])) {
															echo $this->Form->submit('Comment to IO', array('name'=>'comment_to_io', 'id'=>'comment_to_io', 'label'=>false,'class'=>'btn btn-success'));
														}
													}
												}

												if ($commentWindow=='ro') {

													if ($HoInspectionExist == 'yes') {

														if (!empty($amaapproved)) {

															if($so_power_to_grant_appl == 'yes'){
																echo $this->Form->submit('Approve', array('name'=>'ro_approve', 'id'=>'ro_approve_btn', 'label'=>false,'class'=>'btn btn-success'));
															}else{
																echo $this->Form->submit('Final Grant', array('name'=>'final_grant', 'id'=>'final_granted_btn', 'label'=>false,'class'=>'btn btn-success'));
															}

														}elseif (empty($ho_allocation_details)) {

															echo $this->Form->submit('Forward To HO', array('name'=>'forward_to_ho', 'id'=>'forward_to_ho', 'label'=>false,'class'=>'btn btn-success'));
														}

														//If appl sent from SO office and its CA non Bevo, then RO will have the option to approve and send back to SO for Grant
														//on 23-09-2021 by Amol
														//}elseif(($form_type=='A' || $form_type=='F') && $office_type=='SO'){

													}elseif ($so_power_to_grant_appl == 'yes')	{
														//if not approved by RO yet
														if(empty($roApproved)){
															echo $this->Form->submit('Approve', array('name'=>'ro_approve', 'id'=>'ro_approve_btn', 'label'=>false,'class'=>'btn btn-success'));
														}

													} else {

														echo $this->Form->submit('Final Grant', array('name'=>'final_grant', 'id'=>'final_granted_btn', 'label'=>false,'class'=>'btn btn-success'));
													}
												}
											?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php echo $this->Form->control('mo_allocation', array('type'=>'hidden', 'id'=>'mo_allocation', 'value'=>$allocation_deatils['level_4_mo'],'label'=>false,)); ?>
					<?php echo $this->Form->end(); ?>
					<!-- Call element of declaration message box out of Form tag on 31-05-2021 by Amol for Form base esign method -->
					<?php echo $this->element('esign_views/declaration-message-before-grant');  ?>
					<?php
						//this element is called to get approval on froms/reports once approved and again referred back.
						//on 25/05/2021 by Amol
						if($show_check_msg_popup=='yes'){
							echo $this->element('ho_inspection_elements/forms_and_reports_approved_check');
						}
					?>
				</div>
			</div>
		</div>
	</section>
</div>

<input type="hidden" id="application_mode" value="<?php echo $application_mode; ?>">
<input type="hidden" id="amaapproved" value="<?php echo $amaapproved; ?>">
<input type="hidden" id="so_power_to_grant_appl" value="<?php echo $so_power_to_grant_appl; ?>">

<?php echo $this->Html->script('rosocomments/ro_so_comments'); ?>
