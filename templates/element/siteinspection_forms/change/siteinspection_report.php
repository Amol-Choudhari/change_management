
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
	<div id="form_outer_main" class="content form-middle">
		<div id="form_inner_main" class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">

							<div class="col-md-12">
								<div class="row">
									<h3 class="col-md-12 card-title-new mt30"><?php // if ($current_level == 'level_2' && $application_mode == 'edit') { echo 'Give Remark and Upload Report: '; } else { echo 'Given Remark and Uploaded Report: '; } ?></h3>
									<div class="col-md-6">

											<label for="field3"><p><span><?php if ($current_level == 'level_2' && $application_mode == 'edit' ) { echo 'Give Remark'; } else { echo 'Given Remark'; } ?></span></p></label>
											<?php echo $this->Form->control('remark_on_report', array('type'=>'textarea', 'value'=>$section_form_details[0]['remark_on_report'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter Firm Remark', 'id'=>'remark_on_report', 'class'=>'form-control')); ?>
											
											<div id="error_remark_on_report"></div>
										
									</div>
									
									<div class="col-sm-6 d-inline-block">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">
												<p><span><?php //if ($current_level == 'level_2' && $application_mode == 'edit') { echo 'Upload Report'; }else{ echo 'Uploaded Report'; } ?></span></p>
												<span class="float-left"><?php if ($current_level == 'level_2' && $application_mode == 'edit' && empty($section_form_details[0]['report_docs'])) { echo 'Attach Report'; }else{ echo 'Attached Report'; } ?> :
													<?php if (!empty($section_form_details[0]['report_docs'])) { ?>
														<a id="report_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['report_docs']); ?>">Preview</a>
													<?php }else{ echo "No Document Provided" ;} ?>
												</span>
											</label>
											<div class="custom-file col-sm-9">
												<?php if ($current_level == 'level_2' && $application_mode == 'edit') { ?>
													<input type="file" name="report_docs" class="form-control" id="report_docs", multiple='multiple'>
													<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
												<?php } ?>
												
												<span id="error_type_report_docs"      class="error invalid-feedback"></span>
												<span id="error_size_report_docs" class="error invalid-feedback"></span>
												<span id="error_report_docs" class="error invalid-feedback"></span>
											</div>
										</div>
										
									</div>
								</div>
						</div>
						<div class="form-buttons">
							<?php //echo $this->element('siteinspection/communication/buttons'); ?>
						</div>
							<?php //echo $this->Form->end(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php echo $this->Html->script('element/siteinspection_forms/change/siteinspection_report'); ?>
