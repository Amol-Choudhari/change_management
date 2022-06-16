
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

	<section class="content form-middle form_outer_class" id="form_outer_main">
		<div class="container-fluid">
			<h5 class="mt-1 mb-2">Packing Details</h5>
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div class="card-header"><h3 class="card-title">Proposed to re-pack</h3></div>
						<div class="form-horizontal">
							<div class="card-body p-0 m-4 rounded">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label text-sm">It is proposed to re-pack?</label>
											<div class="col-sm-3">
												<?php
													$re_pack_radio = $section_form_details[0]['proposed_to_repack'];
													if ($re_pack_radio == 'yes') {
														$re_pack_radio_yes = 'checked';
														$re_pack_radio_no = '';
													} else if ($re_pack_radio == 'no') {
														$re_pack_radio_yes = '';
														$re_pack_radio_no = 'checked';
													} else {
														$re_pack_radio_yes = '';
														$re_pack_radio_no = '';
													}
												?>
												<div class="icheck-success d-inline">
													<input type="radio" name="proposed_to_repack" id="proposed_to_repack-yes" value="yes" <?php echo $re_pack_radio_yes; ?>>
													<label for="proposed_to_repack-yes">Yes</label>
												</div>
												<div class="icheck-success d-inline">
													<input type="radio" name="proposed_to_repack" id="proposed_to_repack-no" value="no" <?php echo $re_pack_radio_no; ?>>
													<label for="proposed_to_repack-no">No</label>
												</div>
												<span id="error_proposed_to_repack" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									
									<div class="col-sm-12" id="hide_proposed_place">
										<div class="col-sm-6 d-inline-block align-top mb-5">
											<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> If yes, furnish the name of the place & address of the re-packing premises</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-4 col-form-label">Name & Address of the Place/Premises <span class="cRed">*</span></label>
												<div class="custom-file col-sm-8">
													<?php echo $this->Form->control('proposed_place', array('type'=>'textarea', 'id'=>'proposed_place', 'escape'=>false, 'value'=>$section_form_details[0]['proposed_place'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Name & Address of proposed place')); ?>
													<span id="error_proposed_place" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-5 d-inline-block">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
													<?php if(!empty($section_form_details[0]['repacking_docs'])){?>
														<a id="repacking_docs_value" target="blank" href="<?php  echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['repacking_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="repacking_docs" class="form-control" id="repacking_docs" multiple='multiple'>
													<span id="error_repacking_docs" class="error invalid-feedback"></span>
													<span id="error_type_repacking_docs" class="error invalid-feedback"></span>
													<span id="error_size_repacking_docs" class="error invalid-feedback"></span>
												</div>
											</div>
											<p class="lab_form_note float-right mt-3"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="card-header mt-2"><h3 class="card-title">Grading of Commodity</h3></div>
						<div class="form-horizontal mb-4 pb-2">
							<div class="card-body p-0 m-4 rounded">
								<div class="row">
									<div class="col-sm-6">
										<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Any other information relevant to Grading of commodity:</p>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-5 col-form-label text-sm">Have Other Information?</label>
											<div class="col-sm-3">
												<?php
													$grading_com_info_radio = $section_form_details[0]['have_grading_other_info'];
													if ($grading_com_info_radio == 'yes') {
														$grading_com_info_radio_yes = 'checked';
														$grading_com_info_radio_no = '';
													} else if ($grading_com_info_radio == 'no') {
														$grading_com_info_radio_yes = '';
														$grading_com_info_radio_no = 'checked';
													} else {
														$grading_com_info_radio_yes = '';
														$grading_com_info_radio_no = '';
													}
												?>
												<div class="icheck-success d-inline">
													<input type="radio" name="have_grading_other_info" id="have_grading_other_info-yes" value="yes" <?php echo $grading_com_info_radio_yes; ?>>
													<label for="have_grading_other_info-yes">Yes</label>
												</div>
												<div class="icheck-success d-inline">
													<input type="radio" name="have_grading_other_info" id="have_grading_other_info-no" value="no" <?php echo $grading_com_info_radio_no; ?>>
													<label for="have_grading_other_info-no">No</label>
												</div>
												<span id="error_have_grading_other_info" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6" id="hide_grading_other_info">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Other Information <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('grading_other_info', array('type'=>'textarea', 'id'=>'grading_other_info', 'escape'=>false, 'value'=>$section_form_details[0]['grading_other_info'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please Furnish other information here')); ?>
												<span id="error_grading_other_info" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
<?php echo $this->Html->script('element/application_forms/new/ca/ca_packing'); ?>
