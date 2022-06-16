<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'siteinspection_report')); ?>
	<section id="form_outer_main" class="content form-middle">
		<div id='form_inner_main'>
			<div class="container-fluid">
	  			<h5 class="mt-1 mb-2">Laboratory Firm Site Inspection Report</h5>
				<div class="row">
					<div class="col-md-12">
						<?php echo $this->Form->create(); ?>
							<div class="card card-success">
								<div class="card-header"><h3 class="card-title">Director/Partner/Proprietor/Owner Details</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="tank_table">
											<div class="form-group">
												<?php echo $this->element('old_applications_elements/old_app_directors_details_table_view'); ?>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card-header"><h3 class="card-title">Inspection Date</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-4 col-form-label">Date of Inspection <span class="cRed">*</span></label>
													<div class="custom-file col-sm-8">
														<?php echo $this->Form->control('inspection_date', array('type'=>'text', 'id'=>'inspection_date', 'value'=>$section_form_details[0]['inspection_date'], 'escape'=>false, 'label'=>false, 'class'=>'pickdate', 'readonly'=>true,'placeholder'=>'Please Enter Siteinspection Date','class'=>'form-control'));  ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card-header"><h3 class="card-title">Room Details</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Details of the size of the rooms where grading & marking is to be done</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Site Plan No. <span class="cRed">*</span></label>
													<div class="col-sm-9">
														<?php echo $this->Form->control('laboratory_site_plan_no', array('type'=>'text', 'id'=>'laboratory_site_plan_no', 'value'=>$section_form_details[0]['laboratory_site_plan_no'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Please Enter Site plan No.','class'=>'form-control'));  ?>
														<span id="error_laboratory_site_plan_no" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Please upload the Site plan duly signed by authorized person of the laboratory</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
														<?php if (!empty($section_form_details[0]['laboratory_site_plan_docs'])) { ?>
															<a target="blank" id="laboratory_site_plan_docs_value" href="<?php echo $section_form_details[0]['laboratory_site_plan_docs']; ?>">Preview</a>
														<?php } else { echo "No Document Provided" ;} ?>
													</label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('laboratory_site_plan_docs',array('type'=>'file',  'id'=>'laboratory_site_plan_docs','value'=>'', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control')); ?>
														<span id="error_laboratory_site_plan_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
														<span id="error_size_laboratory_site_plan_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
														<span id="error_type_laboratory_site_plan_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
													</div>
												</div>
												<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card-header"><h3 class="card-title">Surrounding Details</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-12">
												<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether the laboratory is having proper light/ ventilation arrangement, cemented flooring and drainage etc.?</p>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="field3" class="col-md-6">
														<?php
															$options=array('yes'=>'Yes','no'=>'No');
															$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['lab_surrounding_details'], 'id'=>'lab_surrounding_details', 'label'=>true);
															echo $this->form->radio('lab_surrounding_details',$options,$attributes);
														?>
													</label>
													<span id="error_lab_surrounding_details" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card-header"><h3 class="card-title">Environment Details</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-12">
												<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether the laboratory exists in hygienic, pollution free and vibration free place?</p>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="field3" class="col-md-6">
														<?php
															$options=array('yes'=>'Yes','no'=>'No');
															$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['lab_environment_details'], 'id'=>'lab_environment_details', 'label'=>true);
															echo $this->form->radio('lab_environment_details',$options,$attributes); ?>
													</label>
													<div id="error_lab_environment_details"></div> <!--create div field for showing error message (by pravin 12/05/2017)-->
												</div>
											</div>
										</div>
									</div>
								</div>
															
								<div class="card-header"><h3 class="card-title">Is Fully Equipped?</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether the laboratory  is fully equipped for the analysis of the commodity/ies for which approval is sought?</p>
												<div class="form-group row">
													<label for="field3" class="col-md-6">
														<?php
															$options=array('yes'=>'Yes','no'=>'No');
															$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['is_lab_fully_equipped'], 'id'=>'is_lab_fully_equipped', 'label'=>true);
															echo $this->form->radio('is_lab_fully_equipped',$options,$attributes); ?>
													</label>
													<div id="error_is_lab_fully_equipped"></div>
												</div>
											</div>
											<div class="col-sm-6"  id="is_lab_equipped">
												<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Attach a list of chemicals, apparatus, equipment etc. duly verified by the Inspecting officer</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
														<?php if (!empty($section_form_details[0]['is_lab_fully_equipped_doc'])) { ?>
															<a target="blank" id="is_lab_fully_equipped_doc_value" href="<?php echo $section_form_details[0]['is_lab_fully_equipped_doc']; ?>">Preview</a>
														<?php } ?>
													</label>
													<div class="custom-file col-sm-9">
														<?php echo $this->Form->control('is_lab_fully_equipped_doc',array('type'=>'file',  'id'=>'is_lab_fully_equipped_doc','value'=>'', 'multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
														<span id="error_is_lab_fully_equipped_doc" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
														<span id="error_size_is_lab_fully_equipped_doc" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
														<span id="error_type_is_lab_fully_equipped_doc" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
													</div>
												</div>
												<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card-header"><h3 class="card-title">Safety Of Records</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-12">
												<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether the arrangement exists in the laboratory for safe custody of records, Agmark Replica and  grading equipments etc?</p>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="field3" class="col-md-6">
														<?php
															$options=array('yes'=>'Yes','no'=>'No');
															$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['laboretory_safety_records'], 'id'=>'laboretory_safety_records', 'label'=>true);
															echo $this->form->radio('laboretory_safety_records',$options,$attributes);
														?>
													</label>
													<div id="error_laboretory_safety_records"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card-header"><h3 class="card-title">Chemist Details, Appointment/Acceptance/Resignation Letter (as applicable),etc</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="machinery_table table-responsive">
												<table class="table chemisttable m-0 table-bordered table-striped">
													<tr>
														<td class="tablehead">S.No</td>
														<td class="tablehead">Name of Chemist</td>
														<td class="tablehead">Qualification (Highest)</td>
														<td class="tablehead">Experience (In Years)</td>
														<td class="tablehead">Commodity</td>
														<td class="tablehead">Upload File<br>(Individual Chemist Details)</td>
													</tr>
													<div id="machinery_each_row">
														<?php
															$i=1;
															foreach($section_form_details[1] as $chemist_detail) { ?>
																<tr>
																	<td><?php echo $i; ?></td>
																	<td><?php echo $chemist_detail['chemist_name'];?></td>
																	<td><?php echo $chemist_detail['qualification']; ?></td>
																	<td><?php echo $chemist_detail['experience']; ?></td>
																	<td><?php echo $section_form_details[2][$i]; ?></td>
																	<td><?php if ($chemist_detail['chemists_details_docs'] != null) {?>
																	<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$chemist_detail['chemists_details_docs']); ?>">Preview</a>
																		<?php }else{ echo "No File Attached";} ?></td>
																</tr>

														<?php $i=$i+1; }  ?>
													</div>
												</table>
											</div>
											<div class="col-sm-12 pd16">
												<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Upload file having details like; number of chemists , total area of unit, accreditation, equipment , glassware, chemicals, etc</p>
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
															<?php if (!empty($section_form_details[0]['chemists_employed_docs'])) { ?>
															<a target="blank" id="chemists_employed_docs_value" href="<?php echo $section_form_details[0]['chemists_employed_docs']; ?>">Preview</a>
															<?php } ?>
														</label>
													<div class="custom-file col-sm-3">
														<?php echo $this->Form->control('chemists_employed_docs',array('type'=>'file',  'id'=>'chemists_employed_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
														<span id="error_chemists_employed_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
														<span id="error_size_chemists_employed_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
														<span id="error_type_chemists_employed_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
													</div>
												</div>
												<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card-header"><h3 class="card-title">Recommendations</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="field3" class="col-md-5"><span>Given recommendations<span class="required"></span></span></label>
													<?php echo $this->Form->control('recommendations', array('type'=>'textarea', 'id'=>'recommendations', 'escape'=>false, 'value'=>$section_form_details[0]['recommendations'], 'label'=>false, 'placeholder'=>'Please Enter other relevent information','class'=>'form-control')); ?>
													<span id="error_recommendations" class="error invalid-feedback"></span>
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


<input type="hidden" id="final_status_id" value="<?php echo $section_status; ?>">

<?php echo $this->Html->script('element/siteinspection_forms/new/laboratory/inspection_details'); ?>

<?php if ($report_edit_mode == 'No') { ?>
	<?php echo $this->Html->script('element/siteinspection_forms/new/laboratory/report_edit_mode_no'); ?>
<?php } ?>
