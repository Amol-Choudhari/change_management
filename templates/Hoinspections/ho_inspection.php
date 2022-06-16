<?php ?>
<?php echo $this->Html->css('ho_inspection'); ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-info">HO Level Verification</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
						<li class="breadcrumb-item active">Inspection Report</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-11">
					<?php echo $this->Form->create(); ?>
						<div class="card card-success">
							<div class="card-header"><h3 class="card-title-new">View Application/Download PDF</h3></div>
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
											<div class="col-md-5">
												<label>View Application/Report</label>
													<div class="form-buttons">
														<div class="form-group row">
															<?php echo $this->Form->submit('View Applicant Form', array('name'=>'view_applicant_forms', 'id'=>'view_applicant_forms', 'label'=>false,'class'=>'btn btn-primary')); ?>
															<?php
																//this condition is added on 04-09-2017 by Amol,if report not filed
																//applied bevo & export condition on 24-11-2017
																if(!empty($report_pdf_path) || ($ca_bevo_applicant == 'yes' && $export_unit_status == 'yes')){

																	echo $this->Form->submit('View Inspection Report', array('name'=>'view_inspection_reports', 'id'=>'view_inspection_reports', 'label'=>false,'class'=>'btn btn-secondary'));

																}elseif(!empty($check_jat_report_filed)){//this link added on 18-09-2017 by Amol for JAT report Pdf

																	echo $this->Html->link('View JAT Inspection Report', array('controller' => 'jatinspections', 'action'=>'view_jat_final_report_pdf'));
																}
																?>
														</div>
													</div>
												</div>
												<div class="col-sm-7">
													<label>Download PDF</label>
														<div class="form-buttons">
															<div class="form-group">
																<a class="btn btn-primary mr-2" target="blank" href="<?php echo $download_application_pdf;?>">Download Application PDF</a>
																<?php if(!empty($report_pdf_path)){ //this condition is added on 04-09-2017 by Amol,if report not filed?>
																	<a class="btn btn-secondary" target="blank" href="<?php echo $download_report_pdf;?>">Download Inspection Report PDF</a>
																<?php }?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="card-header bg-dark"><h3 class="card-title">Previous Comments History</h3></div>
											<div class="form-horizontal">
												<div class="card-body">
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
															<?php
																$last_comment_by = null;
																$from_user = null; //added on 19-01-2022
																$to_user = null; //added on 19-01-2022
																foreach($ho_comment_details as $comment_detail){

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
																//below lines added on 19-01-2022 by Amol, and used below
																$from_user = $comment_detail['from_user'];
																$to_user = $comment_detail['to_user'];
															}?>
														</table>
													</div>
												</div>
											</div>

					<?php  
					//added this condition to hide comment box & btns if comment_by current user & current allocated level is login user. 
					//updated on 16-12-2017
					//again cond. updated if current user is Ro and DyAMA both
					if((($last_comment_by != $_SESSION['username'] || 
						($check_user_role['dy_ama'] == 'yes' && $check_user_role['ro_inspection'] == 'yes')) 
						&& $current_allocated_to == $_SESSION['username']) || 
						
						//below condition added on 19-01-2022 by Amol, to manage scenario when user commented last and swapped positions/transfer roles
						//like position/transfer roles RO to Dy ama or Dy ama to RO etc
						($last_comment_by == $_SESSION['username'] && 
						((($from_user=='dy_ama' || $from_user=='jt_ama' || $from_user=='ama') && $_SESSION['current_level']=='level_3')||
						($from_user=='ro' && $_SESSION['current_level']=='level_4')))			
						){ ?> 

						<?php if($check_user_role['ama'] == 'yes' || ($check_user_role['jt_ama'] == 'yes' && $ca_bevo_applicant == 'yes' && $_SESSION['application_type']==1)){ //added cond. on 22-11-2021 for appl. type = 1 ?>

							<div id="actionbox">
								<div class="col-md-6 mt-2">
									<div class="form-group">
										<label class="col-sm-3">Actions <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php $options = array('0'=>'Approved','1'=>'Not Approved','2'=>'Query');

											echo $this->Form->control('action', array('type'=>'select', 'id'=>'action', 'empty'=>'---Select---','options'=>$options, 'escape'=>false, 'label'=>false,'class'=>'form-control')); ?>
										</div>
									</div>
								</div>
							</div>

						<?php } ?>

						<!-- changed on 04-08-2017 by Amol -->
						<div id="comment_box_with_btn" class="card-body row">
							<div class="col-sm-6">
								<label>Current Comment <span class="cRed">*</span></label>
								<div class="remark-current">
									<?php echo $this->Form->control('comment', array('type'=>'textarea', 'id'=>'check_save_reply', 'escape'=>false, 'label'=>false,'class'=>'form-control' )); ?>
								</div>
								<div id="error_save_reply"></div>
							</div>

							<div id="comment_to" class="col-sm-6">
								<label>Comment To <span class="cRed">*</span> :</label>	
								<div class="form-group">

								<?php

									if ($check_user_role['ro_inspection'] == 'yes' && $_SESSION['current_level'] == 'level_3') {
										
										if ($office_type == 'RO') {

											$options=array('dy_ama'=>' HO Quality Control');
										
										} elseif ($office_type == 'SO') {
											
											$options=array('dy_ama'=>' HO Quality Control','so'=>' Sub office');
										}
										
										$attributes=array('legend'=>false, 'id'=>'comment_to', 'value'=>'dy_ama', 'label'=>true);
										echo $this->Form->radio('comment_to',$options,$attributes);

									}

									//this condition added to hide RO radio btn for DYAMA (role) if its current level is 4
									// on 28-10-2021 by Akash
									if ($check_user_role['dy_ama'] == 'yes' && $_SESSION['current_level'] == 'level_4') {
										//this condition added to hide RO radio btn for DYAMA if JAT report filed for lab export only
										// on 19-09-2017 by Amol
										if ($export_unit_status == 'yes' && $ca_bevo_applicant != 'yes') {//applied bevo condition on 24-11-2017

											//commented JAT condition for lab export as per new orders, NO JAT exists now for lab export
											//on 28-09-2021 by Amol
										//	if(empty($check_jat_report_filed)){

												$options=array('ro'=>'RO','ho_mo_smo'=>'MO/SMO','jt_ama'=>'JT AMA');

										//	}else{
										//		$options=array('jt_ama'=>'JT AMA');
										//	}

										} else {

											$options=array('ro'=>' RO','ho_mo_smo'=>' MO/SMO','jt_ama'=>' JT AMA');
										}

										$attributes=array('legend'=>false, 'id'=>'comment_to', 'label'=>true);
										echo $this->Form->radio('comment_to',$options,$attributes);


									//this condition added to hide RO radio btn for DYAMA (role) if its current level is 4
									// on 28-10-2021 by Akash
									} elseif ($check_user_role['jt_ama'] == 'yes' && $_SESSION['current_level'] == 'level_4') {

										//added below new condition on 16-09-2019 for CA BEVO appln approved by Jtama only
										if (($check_user_role['jt_ama'] == 'yes' && $ca_bevo_applicant == 'yes' && $_SESSION['application_type']==1)) {//added cond. on 22-11-2021 for appl. type = 1

											$options=array('dy_ama'=>'HO Quality Control');

										} else {

											//this condition added to hide AMA radio btn for JTAMA if JAT report not filed for lab export only
											// on 19-09-2017 by Amol
											if($export_unit_status == 'yes' && $ca_bevo_applicant != 'yes'){//applied bevo condition on 24-11-2017

												//commented JAT condition for lab export as per new orders, NO JAT exists now for lab export
												//on 28-09-2021 by Amol
											//	if(!empty($check_jat_report_filed)){

													$options=array('dy_ama'=>'HO Quality Control','ama'=>'AMA');

											//	}else{
											//		$options=array('dy_ama'=>'HO Quality Control');
											//	}

											} else {

												$options=array('dy_ama'=>' HO Quality Control','ama'=>' AMA');
											}
										}

										$attributes=array('legend'=>false, 'id'=>'comment_to', 'label'=>true);
										echo $this->Form->radio('comment_to',$options,$attributes);

								

									//this condition added to hide RO radio btn for DYAMA (role) if its current level is 4
									// on 28-10-2021 by Akash
									} elseif ($check_user_role['ho_mo_smo'] == 'yes') {

										$options=array('dy_ama'=>' HO Quality Control');
										$attributes=array('legend'=>false, 'value'=>'dy_ama', 'id'=>'comment_to', 'label'=>true);
										echo $this->Form->radio('comment_to',$options,$attributes);

										//echo $this->Form->control('comment_to', array('type'=>'hidden', 'value'=>'dy_ama', 'label'=>false));


									//this condition added to hide RO radio btn for DYAMA (role) if its current level is 4
									// on 28-10-2021 by Akash

									} elseif ($check_user_role['ama'] == 'yes' && $_SESSION['current_level'] == 'level_4') {

										$options=array('jt_ama'=>' JT AMA');
										$attributes=array('legend'=>false, 'value'=>'jt_ama', 'id'=>'comment_to', 'label'=>true);
										echo $this->Form->radio('comment_to',$options,$attributes);

										//echo $this->Form->control('comment_to', array('type'=>'hidden', 'value'=>'jt_ama', 'label'=>false));

									}



								 ?>
								</div>
							</div>

							<div class="mt-2 row ml-1">

								<?php echo $this->Form->submit('Send Comment', array('name'=>'send_comment', 'id'=>'send_comment_btn', 'label'=>false,'class'=>'btn btn-success')); ?>

								<?php
									if(!empty($check_valid_ro) && $_SESSION['current_level']=='level_3' && empty($check_ama_approval)){

									echo $this->Form->submit('Comment to Applicant', array('name'=>'comment_to_applicant', 'id'=>'comment_to_applicant', 'label'=>false,'class'=>'btn btn-success'));
									echo $this->Form->submit('Comment to IO', array('name'=>'comment_to_io', 'id'=>'comment_to_io', 'label'=>false,'class'=>'btn btn-success'));
									}
								?>
							</div>

						</div>

						<!-- changed on 04-08-2017 by Amol -->
						<div id="approved_btn" class="card-body">

							<?php

								if($check_user_role['ama'] == 'yes'||
									//added below new condition on 16-09-2019 for CA BEVO appln approved by Jtama only
									($check_user_role['jt_ama'] == 'yes' && $ca_bevo_applicant == 'yes' && $_SESSION['application_type']==1)){ //added cond. on 22-11-2021 for appl. type = 1
									?>
									<!-- added this comment box for approval comment only on 05-05-2021 by Amol -->
									<div class="col-sm-6">
										<label>Current Comment <span class="cRed">*</span></label>
										<div class="remark-current">
										<?php echo $this->Form->control('approval_comment', array('type'=>'textarea', 'id'=>'approval_comment', 'escape'=>false, 'label'=>false, 'class'=>'form-control','required'=>true)); ?>
										</div>
										<div id="error_approval_comment"></div>
									</div>
									<?php

									//added on click script function call on 05-05-2020 by Amol
									echo $this->Form->submit('Approved', array('name'=>'approved_by_ama', 'id'=>'approved_by_ama','label'=>false,'class'=>'btn btn-success mt-2'));
								}
							?>

						</div>
						<!-- Added new buttn for DYAMA grant for lab export appln on 18-09-2017 -->
						<!-- Added NABL conditions, if lab is nabl accreditated then after HO approval show Grant button to RO, not proceed to grant btn, on 30-09-2021 by Amol-->
						<?php
							if((($export_unit_status == 'yes' && $check_user_role['dy_ama'] == 'yes') ||
								($export_unit_status != 'yes' && $NablDate != null && !empty($check_valid_ro)))//line added on 30-09-2021
								&& !empty($check_ama_approval) && $ca_bevo_applicant != 'yes'){//applied bevo condition on 24-11-2017

								echo $this->Form->submit('Final Grant', array('name'=>'final_granted', 'id'=>'final_granted_btn', 'label'=>false,'class'=>'btn btn-success m-2 ml-3'));

							//added export unit cond. on 28-09-2021 by Amol, if lab export than grant by Dyama
							}elseif($export_unit_status != 'yes' && !empty($check_valid_ro)
								&& $_SESSION['current_level']=='level_3' && !empty($check_ama_approval)){//commented to ro cond added on 04-07-2019

								echo $this->form->submit('Proceed to Grant', array('name'=>'proceed_to_grant', 'id'=>'proceed_to_grant', 'label'=>false,'class'=>'btn btn-success'));
							}?>


							<?php echo $this->Form->end(); ?>

							<!-- Call element of declaration message box out of Form tag on 31-05-2021 by Amol for Form base esign method -->
							<?php echo $this->element('esign_views/declaration-message-before-grant'); ?>

							<?php
								//this element is called to get approval on froms/reports once approved and again referred back.
								//on 20/05/2021 by Amol
								if($show_check_msg_popup=='yes'){
									echo $this->element('ho_inspection_elements/forms_and_reports_approved_check');
								}
							?>
							
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<input type="hidden" id="check_user_role" value='<?php echo json_encode($check_user_role); ?>'>
<input type="hidden" id="ca_bevo_applicant" value="<?php echo $ca_bevo_applicant; ?>">
<input type="hidden" id="application_mode" value="<?php echo $_SESSION['application_mode']; ?>">
<input type="hidden" id="export_unit_status" value="<?php echo $export_unit_status; ?>">
<input type="hidden" id="check_valid_ro" value="<?php echo $check_valid_ro; ?>">
<input type="hidden" id="check_ama_approval" value="<?php echo $check_ama_approval; ?>">
<input type="hidden" id="current_level" value="<?php echo $_SESSION['current_level']; ?>">
<input type="hidden" id="application_type_id" value="<?php echo $_SESSION['application_type']; ?>">

<?php echo $this->Html->script('hoinspection/ho_inspection'); ?>
