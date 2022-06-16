	
	<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
		
		<section class="content form-middle form_outer_class" id="form_outer_main">
			<div class="container-fluid">
			<h5 class="mt-1 mb-2">Printing Unit Details</h5>
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div id="tbls_table">
							<div class="card-header sub-card-header-firm"><h3 class="card-title">Printing Machines Details</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 m-4 border rounded">
									<div class="row">
										<div class="col-sm-12">
											<?php echo $this->element('ca_other_tables_elements/machine_details_table_view'); ?>
											<span id="error_machine_table" class="error invalid-feedback"></span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Other Required Machines Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6 d-inline-block">
										<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Attach Details of machinery not covered above, if any</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File : <span class="cRed">*</span>
													<?php if(!empty($section_form_details[0]['other_required_machine_docs'])){ ?>
														<a target="blank" id="other_required_machine_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['other_required_machine_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="other_required_machine_docs" class="form-control" id="other_machin_docs" multiple='multiple'>
													<span id="error_other_machin_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 11/05/2017)-->
													<span id="error_size_other_machin_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 11/05/2017)-->
													<span id="error_type_other_machin_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 11/05/2017)-->
												</div>
											</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Is Earlier Approved ?</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-9 col-form-label">Whether the press is earlier approved for Agmark work ?</label>
											<div class="col-sm-3">
												<?php
													$agmark_work = $section_form_details[0]['earlier_approved'];
													if($agmark_work == 'yes'){
														$agmark_work_yes = 'checked';
														$agmark_work_no = '';
													} else if($agmark_work == 'no'){
														$agmark_work_yes = '';
														$agmark_work_no = 'checked';
													} else {
														$agmark_work_yes = '';
														$agmark_work_no = '';
													}
												?>
												
												<div class="icheck-success d-inline">
													<input type="radio" name="earlier_approved" id="earlier_approved-yes" value="yes" <?php echo $agmark_work_yes; ?>>
													<label for="earlier_approved-yes">Yes</label>
												</div>
												<div class="icheck-success d-inline">
													<input type="radio" name="earlier_approved" id="earlier_approved-no" value="no" <?php echo $agmark_work_no; ?>>
													<label for="earlier_approved-no">No</label>
												</div>
												<span id="error_is_earlier_approved" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 11/05/2017)-->
											</div>
										</div>
									</div>
									<div class="col-sm-6" id="hide_expiry_date">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Expiry Date <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('earlier_expiry_date', array('type'=>'text',  'id'=>'earlier_expiry_date', 'escape'=>false, 'value'=>chop($section_form_details[0]['earlier_expiry_date'],"00:00:00"), 'readonly'=>'true', 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Earlier expiry date')); ?>
												<span id="error_earlier_expiry_date" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Inhouse Machinery</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-9 col-form-label">Whether firm has in house machinery for Agmark work ?</label>
											<div class="col-sm-3">
												<?php
													$in_house_mac = $section_form_details[0]['in_house_machinery'];
													if ($in_house_mac == 'yes') {
														$in_house_mac_yes = 'checked';
														$in_house_mac_no = '';
													} elseif ($in_house_mac == 'no'){
														$in_house_mac_yes = '';
														$in_house_mac_no = 'checked';
													} else {
														$in_house_mac_yes = '';
														$in_house_mac_no = '';
													}
												?>
												
												<div class="icheck-success d-inline">
													<input type="radio" name="in_house_machinery" id="in_house_machinery-yes" value="yes" <?php echo $in_house_mac_yes; ?>>
													<label for="in_house_machinery-yes">Yes</label>
												</div>
												<div class="icheck-success d-inline">
													<input type="radio" name="in_house_machinery" id="in_house_machinery-no" value="no" <?php echo $in_house_mac_no; ?>>
													<label for="in_house_machinery-no">No</label>
												</div>
												<span id="error_in_house_machinery" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Facilities for Fabrication</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-8 col-form-label">Whether firm has proper facilities for fabrication of tin containers from the tin sheet ?</label>
											<div class="col-sm-4">
												<?php
													$proper_fab = $section_form_details[0]['proper_fabrication'];
													if ($proper_fab == 'yes') {
														$proper_fab_yes = 'checked';
														$proper_fab_no = '';
														$proper_fab_na = '';
													} elseif ($proper_fab == 'no') {
														$proper_fab_yes = '';
														$proper_fab_no = 'checked';
														$proper_fab_na = '';
													} elseif ($proper_fab == 'n/a') {
														$proper_fab_yes = '';
														$proper_fab_no = '';
														$proper_fab_na = 'checked';
													} else {
														$proper_fab_yes = '';
														$proper_fab_no = '';
														$proper_fab_na = '';
													}
												?>
												
												<div class="icheck-success d-inline">
													<input type="radio" name="proper_fabrication" id="proper_fabrication-yes" value="yes" <?php echo $proper_fab_yes; ?>>
													<label for="proper_fabrication-yes">Yes</label>
												</div>
												<div class="icheck-success d-inline ml-3">
													<input type="radio" name="proper_fabrication" id="proper_fabrication-no" value="no" <?php echo $proper_fab_no; ?>>
													<label for="proper_fabrication-no">No</label>
												</div>
												<div class="icheck-success d-inline ml-3">
													<input type="radio" name="proper_fabrication" id="proper_fabrication-n-a" value="n/a" <?php echo $proper_fab_na; ?>>
													<label for="proper_fabrication-n-a">Not Applicable</label>
												</div>
												<span id="error_proper_fabrication" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 11/05/2017)-->
											</div>
										</div>
									</div>
									
									<div class="col-sm-6" id="hide_name_address">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Name & Address of Other Unit <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('name_address_fabrication_unit', array('type'=>'textarea', 'id'=>'address_fabrication_unit','escape'=>false, 'value'=>$section_form_details[0]['name_address_fabrication_unit'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Name and Address of fabrication unit')); ?>
												<span id="error_address_fabrication_unit" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>

									<div class="col-sm-6" id="fabrication_box">
										<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Attach Details of machinery not covered above, if any</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
													<?php if(!empty($section_form_details[0]['fabrication_docs'])){ ?>
														<a target="blank" id="fabrication_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['fabrication_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="fabrication_docs" class="form-control" id="fabrication_docs"  multiple='multiple'>
													<span id="error_fabrication_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 11/05/2017)-->
													<span id="error_size_fabrication_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 11/05/2017)-->
													<span id="error_type_fabrication_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 11/05/2017)-->
												</div>
											</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Proposed Start Date</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12" id="hide_expiry_date">
										<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Date from which the Press is proposed to be engaged for printing of Agmark replica on all types of containers/packages.</p>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-2 col-form-label">Proposed date <span class="cRed">*</span></label>
											<div class="custom-file col-sm-4">
												<div id="hide_expiry_date">
													<?php echo $this->Form->control('press_proposed_date', array('type'=>'text', 'id'=>'proposed_date', 'escape'=>false, 'value'=>chop($section_form_details[0]['press_proposed_date'],"00:00:00"), 'readonly'=>'true', 'class'=>'form-control input-field pickdate', 'label'=>false, 'placeholder'=>'Enter proposed date')); ?>
												</div>
												<span id="error_proposed_date" class="error invalid-feedback"></span>
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

<!-- Move internal css and js script into external file, Done by Pravin Bhakare 07-10-2021 -->
<?php echo $this->Html->script('element/ca_other_tables_elements/machine_details_table_view'); ?>
<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
<?php echo $this->Html->script('element/application_forms/new/printing/unit_details'); ?>
