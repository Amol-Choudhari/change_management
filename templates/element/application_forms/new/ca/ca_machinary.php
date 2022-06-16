<?php echo $this->Form->create(null, array('type'=>'file', 'id'=>$section, 'enctype'=>'multipart/form-data')); ?>

	<section class="content form-middle form_outer_class" id="form_outer_main">
		<div class="container-fluid">
		    <h5 class="mt-1 mb-2">Machinery Profile</h5>
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
					<?php if ($ca_bevo_applicant == 'no') { ?>
						<div id="tbls_table">
							<div class="card-header sub-card-header-firm"><h3 class="card-title">Details/Documents</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 m-4 rounded">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-6 col-form-label text-sm">Do you have Machinery details ?</label>
												<div class="col-sm-3">
													<?php
														$mac_det = $section_form_details[0]['have_details'];
														if($mac_det == 'yes'){
															$mac_det_yes = 'checked';
															$mac_det_no = '';
														} else if($mac_det == 'no'){
															$mac_det_yes = '';
															$mac_det_no = 'checked';
														} else {
															$mac_det_yes = '';
															$mac_det_no = '';
														}
													?>
													
													<div class="icheck-success d-inline">
														<input type="radio" name="have_details" id="have_details-yes" value="yes" <?php echo $mac_det_yes; ?>>
														<label for="have_details-yes">Yes</label>
													</div>
													
													<div class="icheck-success d-inline">
														<input type="radio" name="have_details" id="have_details-no" value="no" <?php echo $mac_det_no; ?>>
														<label for="have_details-no">No</label>
													</div>
													<span id="error_have_details" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-12" id="hide_machinery_details">
											<div class="col-sm-12 border">
												<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Detail of machinery/ packing machine/ storage tank/ cold storage etc available in the plant/ premises with their capacity (in Quintal).</p>
												<!-- call table view form element with ajax call -->
												<?php echo $this->element('ca_other_tables_elements/machine_details_table_view'); ?>
											</div>
											<div class="col-sm-6 mt-4">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
														<?php if(!empty($section_form_details[0]['detail_docs'])){ ?>
															<a id="detail_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['detail_docs']); ?>">Preview</a>
														<?php } ?>
													</label>
													<div class="custom-file col-sm-9">
													  <input type="file" name="detail_docs" class="form-control" id="detail_docs", multiple='multiple'>
													  <span id="error_detail_docs" class="error invalid-feedback"></span>
													  <span id="error_type_detail_docs" class="error invalid-feedback"></span>
													  <span id="error_size_detail_docs" class="error invalid-feedback"></span>
													</div>
												</div>
												<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Manufacturing unit details</h3></div>
						<div class="form-horizontal">
							<div class="card-body p-0 m-4 rounded">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label text-sm">Is the Manufacturing unit owned by you ?</label>
												<div class="col-sm-3">
													<?php
														$own_app = $section_form_details[0]['owned_by_applicant'];
														if($own_app == 'yes'){
														$own_app_yes = 'checked';
														$own_app_no = '';
														} else if($own_app == 'no'){
														$own_app_yes = '';
														$own_app_no = 'checked';
														} else {
														$own_app_yes = '';
														$own_app_no = '';
														}
													?>
													<div class="icheck-success d-inline">
														<input type="radio" name="owned_by_applicant" id="manufacturing_unit-yes" value="yes" <?php echo $own_app_yes; ?>>
														<label for="manufacturing_unit-yes">Yes</label>
													</div>
													<div class="icheck-success d-inline">
														<input type="radio" name="owned_by_applicant" id="manufacturing_unit-no" value="no" <?php echo $own_app_no; ?>>
														<label for="manufacturing_unit-no">No</label>
													</div>
												<span id="error_owned_by_applicant" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									
									<div class="col-sm-12" id="no_owned_unit">
										<div class="col-sm-6 d-inline-block align-top">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Name & Address of Approved Unit <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
													<?php echo $this->Form->control('unit_name_address', array('type'=>'textarea', 'id'=>'unit_name_address', 'escape'=>false, 'value'=>$section_form_details[0]['unit_name_address'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Provide Approved Unit Name & Address')); ?>
													<span id="error_unit_name_address" class="error invalid-feedback position-absolute"></span>
												</div>
											</div>
										</div>
										<div class="col-sm-5 d-inline-block">
											<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Copy of the consent letter to be enclosed</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
													<?php if(!empty($section_form_details[0]['unit_related_docs'])){  ?>
														<a id="unit_related_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['unit_related_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="unit_related_docs" class="custom-file-input" id="unit_related_docs", multiple='multiple'>
													<label class="custom-file-label" for="customFile">Choose file</label>
													<span id="error_unit_related_docs" class="error invalid-feedback"></span>
													<span id="error_type_unit_related_docs" class="error invalid-feedback"></span>
													<span id="error_size_unit_related_docs" class="error invalid-feedback"></span>
												</div>
											</div>
											<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										</div>
									</div>
								</div>
							</div>
						</div>
                                                        
                    <?php } elseif ($ca_bevo_applicant == 'yes') { ?>

						<div class="card-header"><h3 class="card-title">Crushing/Refining Mill Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body mb-4">
								<div class="row">
									<div class="col-sm-12">
										<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Oil seeds normally crushed/oils refined by the mill and the period for which the mill has been in crushing/refining business.</p>
									</div>
									<div class="col-sm-6 mb-5">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-4 col-form-label">Seeds Crushed/Oils Refined</label>
											<div class="custom-file col-sm-8">
												<?php echo $this->Form->control('crushed_refined_seeds', array('type'=>'textarea', 'id'=>'crushed_refined_seeds', 'escape'=>false, 'value'=>$section_form_details[0]['crushed_refined_seeds'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter Seeds and Oils details')); ?>
												<span id="error_crushed_refined_seeds" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Period</label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('mill_business_period', array('type'=>'select', 'options'=>$rushing_refining_period, 'id'=>'mill_business_period', 'escape'=>false, 'selected'=>$section_form_details[0]['mill_business_period'], 'class'=>'form-control input-field', 'label'=>false)); ?>
												<span id="error_mill_business_period" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Quantity of Different Oilseeds</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Quantity of different oilseeds being crushed/refined annually.</p>
									</div>
									<div class="col-sm-12">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-2 col-form-label">Total Quantity(MT)</label>
											<div class="custom-file col-sm-4">
												<?php echo $this->Form->control('quantity_of_oilseeds', array('type'=>'text', 'id'=>'quantity_of_oilseeds', 'escape'=>false, 'value'=>$section_form_details[0]['quantity_of_oilseeds'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter quantity here')); ?>
												<span id="error_quantity_of_oilseeds" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

		                <?php if ($applicant_type == 'bevo') { ?>

                            <div class="card-header"><h3 class="card-title">Machinery Details</h3></div>
                            <div class="form-horizontal">
                                <div class="card-body p-0 m-4 rounded">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Details of machineries available in the Oil mill with their capacity, in case of Blended Edible Vegetable Oils.</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
                                                    <?php if(!empty($section_form_details[0]['bevo_machinery_details_docs'])){ ?>
                                                        <a id="bevo_machinery_details_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['bevo_machinery_details_docs']); ?>">Preview</a>
                                                    <?php } ?>
                                                </label>
                                                <div class="custom-file col-sm-9">
                                                    <input type="file" name="bevo_machinery_details_docs" class="custom-file-input" id="bevo_machinery_details_docs", multiple='multiple'>
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                    <span id="error_bevo_machinery_details_docs" class="error invalid-feedback"></span>
                                                    <span id="error_type_bevo_machinery_details_docs" class="error invalid-feedback"></span>
                                                    <span id="error_size_bevo_machinery_details_docs" class="error invalid-feedback"></span>
                                                </div>
                                            </div>
                                            <p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
						

			            <?php } elseif ($applicant_type == 'fat_spread') { ?>

                            <div class="card-header"><h3 class="card-title">Minimum Infrastructure/Facilities</h3></div>
                            <div class="form-horizontal">
                                <div class="card-body p-0 m-4 rounded">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Details of minimum infrastructure/facilities available in the plant in case of Fat Spread.</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
                                                    <?php if(!empty($section_form_details[0]['fat_spread_facility_docs'])){ ?>
                                                        <a id="fat_spread_facility_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['fat_spread_facility_docs']); ?>">Preview</a>
                                                    <?php } ?>
                                                </label>
                                                <div class="custom-file col-sm-9">
                                                    <input type="file" name="fat_spread_facility_docs" class="custom-file-input" id="fat_spread_facility_docs", multiple='multiple'>
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                    <span id="error_fat_spread_facility_docs" class="error invalid-feedback"></span>
                                                    <span id="error_type_fat_spread_facility_docs" class="error invalid-feedback"></span>
                                                    <span id="error_size_fat_spread_facility_docs" class="error invalid-feedback"></span>
                                                </div>
                                            </div>
                                            <p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

			            <?php } ?>

						<div class="card-header"><h3 class="card-title">Separately Stored & Crushed</h3></div>
						<div class="form-horizontal">
							<div class="card-body p-0 m-4 rounded">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-9 col-form-label text-sm">Are different oilseeds stored and crushed/oils refined separately ?</label>
											<div class="col-sm-3">
												<?php
													$sep_store_crush = $section_form_details[0]['stored_crushed_separately'];
													if($sep_store_crush == 'yes'){
														$sep_store_crush_yes = 'checked';
														$sep_store_crush_no = '';
													} else if($sep_store_crush == 'no'){
														$sep_store_crush_yes = '';
														$sep_store_crush_no = 'checked';
													} else {
														$sep_store_crush_yes = '';
														$sep_store_crush_no = '';
													}
												?>
												<div class="icheck-success d-inline">
													<input type="radio" name="stored_crushed_separately" id="stored_crushed_separately-yes" value="yes" <?php echo $sep_store_crush_yes; ?>>
													<label for="stored_crushed_separately-yes">Yes</label>
												</div>
												<div class="icheck-success d-inline">
													<input type="radio" name="stored_crushed_separately" id="stored_crushed_separately-no" value="no" <?php echo $sep_store_crush_no; ?>>
													<label for="stored_crushed_separately-no">No</label>
												</div>
												<span id="error_stored_crushed_separately" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6" id="hide_separate_crushed">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File:
												<?php if(!empty($section_form_details[0]['stored_crushed_separately_docs'])){ ?>
													<a id="stored_crushed_separately_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['stored_crushed_separately_docs']); ?>">Preview</a>
												<?php } ?>
											</label>

											<div class="custom-file col-sm-9">
												<input type="file" name="stored_crushed_separately_docs" class="custom-file-input" id="stored_crushed_separately_docs", multiple='multiple'>
												<label class="custom-file-label" for="customFile">Choose file</label>
												<span id="error_stored_crushed_separately_docs" 	 class="error invalid-feedback"></span>
												<span id="error_type_stored_crushed_separately_docs" class="error invalid-feedback"></span>
												<span id="error_size_stored_crushed_separately_docs" class="error invalid-feedback"></span>
											</div>
										</div>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Precautions Taken</h3></div>
						<div class="form-horizontal">
							<div class="card-body mb-3">
								<div class="row">
									<div class="col-sm-12">
										<p class="bg-info pl-2 p-1 rounded text-sm">What precautions taken to avoid mixing of different oil seeds and oils in the oil mill?</p>
									</div>
									<div class="col-sm-6 mb-5 pb-4">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Details</label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('precautions_taken', array('type'=>'textarea', 'id'=>'precautions_taken', 'escape'=>false, 'value'=>$section_form_details[0]['precautions_taken'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter Details here')); ?>
												<span id="error_precautions_taken" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
			        <?php } ?>
					</div>
				</div>
			</div>
		</div>
    </section>

	<input type="hidden" id="ca_bevo_applicant_id" value="<?php echo $ca_bevo_applicant; ?>">
	<input type="hidden" id="applicant_type_id" value="<?php echo $applicant_type; ?>">
	<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
	<?php echo $this->Html->script('element/application_forms/new/ca/ca_machinary'); ?>
