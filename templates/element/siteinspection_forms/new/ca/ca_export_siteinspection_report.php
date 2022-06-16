
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
	<div id="form_outer_main" class="content form-middle">
		<div id="form_inner_main" class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<!-- <h2>Certification of Authorisation(Export) Report upload</h2>	-->
						<!-- Below code added on 10-10-2017 by Amol to get/show Directors details for IO/RO -->
						<!-- this view is for IO Window -->
						<div class="card-header"><h3 class="card-title-new">Director/Partner/Proprietor/Owner Details</h3></div>
							<div class="tank_table">
								<!-- call table view form element with ajax call -->
								<?php echo $this->element('old_applications_elements/old_app_directors_details_table_view'); ?>
							</div>
							<div class="col-md-12">
								<div class="row">
									<h3 class="col-md-12 card-title-new mt30"><?php // if ($current_level == 'level_2' && $application_mode == 'edit') { echo 'Give Remark and Upload Report: '; } else { echo 'Given Remark and Uploaded Report: '; } ?></h3>
									<div class="col-md-6">

											<label for="field3"><p><span><?php if ($current_level == 'level_2' && $application_mode == 'edit' ) { echo 'Give Remark'; } else { echo 'Given Remark'; } ?></span></p></label>
											<?php echo $this->Form->control('remark_on_report', array('type'=>'textarea', 'value'=>$section_form_details[0]['remark_on_report'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter Firm Remark', 'id'=>'remark_on_report', 'class'=>'form-control')); ?>
											
											<div id="error_remark_on_report"></div>
										
									</div>
									<div class="col-md-6">
										<label for="field3"><p><span><?php if ($current_level == 'level_2' && $application_mode == 'edit') { echo 'Upload Report'; }else{ echo 'Uploaded Report'; } ?></span></p>
											<span class="float-left"><?php if ($current_level == 'level_2' && $application_mode == 'edit') { echo 'Attach File'; }else{ echo 'Attached File'; } ?> :
												<?php if (!empty($section_form_details[0]['report_docs'])) { ?>
													<a id="report_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['report_docs']); ?>">Preview</a>
												<?php }else{ echo "No Document Provided" ;} ?>
											</span>
										</label>
										<?php if ($current_level == 'level_2' && $application_mode == 'edit') { echo $this->Form->control('report_docs',array('type'=>'file', 'id'=>'report_docs', 'multiple'=>'multiple','label'=>false));  ?>
											<p class="file_limits">File type: pdf,jpg & Max-size:2mb</p>
										<?php } ?>
										
										<div id="error_type_report_docs"></div>
										<div id="error_size_report_docs"></div>
										<div id="error_report_docs"></div>
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
		<?php echo $this->Html->script('element/siteinspection_forms/new/ca/ca_export_siteinspection_report'); ?>
