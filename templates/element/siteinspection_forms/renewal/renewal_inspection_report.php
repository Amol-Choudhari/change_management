	<?php ?>
		<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

			<div id="form_outer_main" class="content form-middle">
				<div id='form_inner_main' class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="card card-success">
								<div class="card-header"><h3 class="card-title"><i class="fa fa-tree"></i> Renewal Site Inspection Report</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
											<div class="col-md-12">
												<label>
														<?php
															if ($current_level == 'level_2' && $application_mode == 'edit') {

																echo 'Give Remark and Upload Report';

															} else {

																echo 'Given Remark and Uploaded Report';
															} ?>
													</label>
											</div>
											<div class="col-md-6">
												<label for="field3">
														<p>
															<span>
																<?php
																	if ($current_level == 'level_2' && $application_mode == 'edit' ) {

																		echo 'Give Remark';

																	} else {

																		echo 'Given Remark';

																	} ?>
																</span>
															</p>

														<?php echo $this->Form->control('firm_renewal_remark', array('type'=>'textarea', 'value'=>$section_form_details[0]['firm_renewal_remark'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter Firm Remark', 'id'=>'firm_renewal_remark','class'=>'form-control')); ?>
													</label>

													<div id="error_firm_renewal_remark"></div> <!-- create error field by pravin 26-07-2017-->

											</div>


													<div class="col-md-6">
														<label for="field3"><p><span><?php if($current_level == 'level_2' && $application_mode == 'edit'){ echo 'Upload Report'; }else{ echo 'Uploaded Report'; } ?></span></p>
															<span class="float-left"><?php if($current_level == 'level_2' && $application_mode == 'edit'){ echo 'Attach File'; }else{ echo 'Attached File'; } ?> :
																<?php if(!empty($section_form_details[0]['firm_renewal_docs'])){ ?>
																	<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['firm_renewal_docs']); ?>" id='firm_renewal_docs_value'>Preview</a>
																<?php }else{ echo "No Document Provided" ;} ?>

																<?php if($current_level == 'level_2' && $application_mode == 'edit'){ echo $this->form->input('firm_renewal_docs',array('type'=>'file', 'id'=>'firm_renewal_docs', 'multiple'=>'multiple','label'=>false));  ?>
																	<p class="file_limits">File type: pdf,jpg & Max-size:2mb</p>
																<?php } ?>
															</span>

															</label>
															<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>

															</div>
															<div id="error_type_firm_renewal_docs"></div> <!-- create error field by pravin 26-07-2017-->
															<div id="error_size_firm_renewal_docs"></div> <!-- create error field by pravin 26-07-2017-->
															<div id="error_firm_renewal_docs"></div> <!-- create error field by pravin 26-07-2017-->

														</div>
													</div>

												</div>

												<div class="form-buttons">
													<?php //echo $this->element('siteinspection/communication/buttons'); ?>
												</div>

											<?php echo $this->Form->end(); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


<?php echo $this->Html->script('element/siteinspection_forms/renewal/renewal_inspection_report'); ?>
