<?php
	echo $this->Html->css('../multiselect/jquery.multiselect');
	echo $this->Html->script('../multiselect/jquery.multiselect');
?>

	<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

	<section class="content form-middle form_outer_class" id="form_outer_main">
	  	<div class="container-fluid">
	  		<h5 class="mt-1 mb-2">Laboratory Firm Other Details</h5>
			<div class="row">
	      		<div class="col-md-12">
					<div class="card card-success">
						<div id="chemist_table" class="machinery_table">
							<div class="card-header sub-card-header-firm"><h3 class="card-title">Chemist Details, Appointment/Acceptance/Resignation Letter (as applicable),etc</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 m-2 rounded">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-format">
												<table id="directors_details_table" class="table chemisttable table-bordered">
													<thead class="tablehead">
														<tr>
															<th>S.No</th>
															<th class="wd10">Name of Chemist</th>
															<th class="wd10">Qualification (Highest)</th>
															<th class="wd10">Experience (In Years)</th>
															<th class="wd22">Commodity</th>
															<th>Upload File<br>(Individual Chemist Details)</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
														<div id="machinery_each_row">
															<?php
															$i=1;
															foreach($section_form_details[1][0] as $chemist_detail){ ?>
															
																<tr>
																	<td><?php echo $i; ?></td>
																	<td><?php echo $chemist_detail['chemist_name'];?></td>
																	<td><?php echo $chemist_detail['qualification']; ?></td>
																	<td><?php echo $chemist_detail['experience']; ?></td>
																	<td class="mx-height"><?php echo $section_form_details[1][1][$i]; ?></td>
																	<td><?php if ($chemist_detail['chemists_details_docs'] != null) { ?>
																			<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$chemist_detail['chemists_details_docs']); ?>">Preview</a>
																		<?php } else { echo "No File Attached"; } ?>
																	</td>
																	<td>
																		<?php echo $this->Html->link('', array('controller' => 'application', 'action'=>'edit_chemist_id',$chemist_detail['id']),array('class'=>'glyphicon glyphicon-edit packer_edit', 'title'=>'Edit')); ?> |
																		<?php echo $this->Html->link('', array('controller' => 'application', 'action'=>'delete_chemist_id',$chemist_detail['id']),array('class'=>'glyphicon glyphicon-remove-sign packer_delete', 'title'=>'Delete')); ?>
																	</td>
																</tr>

															<?php $i=$i+1; } ?>
														</div>
														
														<!-- for edit chemist details -->
														<?php if ($this->getRequest()->getSession()->read('edit_chemist_id') != null) { ?>
																	<tr>
																		<td></td>
																		<td><div class =""><?php echo $this->Form->control('chemist_name', array('type'=>'text', 'value'=>$section_form_details[1][2]['chemist_name'], 'escape'=>false,  'label'=>false,'id'=>'chemist_name', 'class'=>'form-control wd100')); ?>
																			<span id="error_chemist_name" class="error invalid-feedback"></span>
																		</td>
																		<td><?php echo $this->Form->control('qualification', array('type'=>'text', 'value'=>$section_form_details[1][2]['qualification'], 'escape'=>false,  'label'=>false, 'id'=>'chemist_qualification', 'class'=>'form-control wd100')); ?>
																			<span id="error_qualification" class="error invalid-feedback"></span>
																		</td>
																		<td><?php echo $this->Form->control('experience', array('type'=>'text', 'value'=>$section_form_details[1][2]['experience'], 'escape'=>false,  'label'=>false, 'id'=>'chemist_experience', 'class'=>'form-control wd100')); ?>
																			<span id="error_experience" class="error invalid-feedback"></span>
																		</td>
																		<td><?php echo $this->Form->control('commodity', array('type'=>'select',  'value'=>$section_form_details[1][4], 'options'=>$section_form_details[1][3],  'multiple'=>'multiple','escape'=>false, 'id'=>'chemist_list', 'label'=>false, 'class'=>'form-control wd100')); ?>
																			<span id="error_commodity" class="error invalid-feedback"></span>
																		</td>
																		<td><?php if($section_form_details[1][2]['chemists_details_docs'] != null){?>
																			<a target="blank" id="chemists_details_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[1][2]['chemists_details_docs']); ?>">Preview</a>
																			<?php }?>
																			<?php echo $this->Form->control('chemists_details_docs',array('type'=>'file', 'id'=>'chemists_details_docs', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																					<span id="error_chemists_details_docs" class="error invalid-feedback"></span>
																					<span id="error_size_chemists_details_docs" class="error invalid-feedback"></span>
																					<span id="error_type_chemists_details_docs" class="error invalid-feedback"></span>
																		</td>
																		<td><?php echo $this->form->submit('Save', array('name'=>'add_chemist_details', 'id'=>'edit_chemist_details', 'label'=>false,'class'=>'form-control wd100 btn btn-success')); ?></td>
																	</tr>
																
														<!-- To show added and save new machine details -->
														<?php } elseif ($this->request->getParam('controller') == 'Application') { ?>
														
																<div id="add_new_row">
																	<tr id="add_new_row_r">
																		<td></td>
																		<td><?php echo $this->Form->control('chemist_name', array('type'=>'text', 'escape'=>false,  'label'=>false, 'id'=>'chemist_name', 'class'=>'form-control wd100')); ?>
																			<span id="error_chemist_name" class="error invalid-feedback"></span>
																		</td>
																		<td><?php echo $this->Form->control('qualification', array('type'=>'text', 'escape'=>false,  'label'=>false, 'id'=>'chemist_qualification', 'class'=>'form-control wd100')); ?>
																			<span id="error_qualification" class="error invalid-feedback"></span>
																		</td>
																		<td><?php echo $this->Form->control('experience', array('type'=>'text', 'escape'=>false,  'label'=>false, 'id'=>'chemist_experience', 'class'=>'form-control wd100')); ?>
																			<span id="error_experience" class="error invalid-feedback"></span>
																		</td>
																		<td><?php echo $this->Form->control('commodity', array('type'=>'select',  'options'=>$section_form_details[1][3],  'multiple'=>'multiple','escape'=>false, 'id'=>'chemist_list', 'label'=>false, 'class'=>'form-control wd100')); ?>
																			<span id="error_commodity" class="error invalid-feedback"></span>
																		</td>
																		<td><?php //echo $this->Form->control('chemists_details_docs',array('type'=>'file', 'id'=>'chemists_details_docs', 'onchange'=>'file_browse_onclick(id);return false', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																			<div class="custom-file col-sm-12">
																				<input type="file" name="chemists_details_docs" class="form-control" id="chemists_details_docs", multiple='multiple'>
																				<span id="error_chemists_details_docs" class="error invalid-feedback"></span>
																				<span id="error_size_chemists_details_docs" class="error invalid-feedback"></span>
																				<span id="error_type_chemists_details_docs" class="error invalid-feedback"></span>
																			</div>
																		</td>
																		<td><?php echo $this->form->submit('Add', array('name'=>'add_chemist_details', 'id'=>'add_chemist_details', 'label'=>false,'class'=>'form-control wd100 btn btn-success')); ?></td>
																	</tr>
																</div>
																
														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card-body">
									<div class="row">
										<div class="col-sm-12">
											<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Upload file having details like; number of chemists , total area of unit, accreditation, equipment , glassware, chemicals, etc.</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File <span class="cRed">*</span></label>
													<?php if (!empty($section_form_details[0]['chemists_employed_docs'])) { ?>
														<a target="blank"  id="chemists_employed_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['chemists_employed_docs']); ?>">Preview</a>
													<?php } ?>
													
													<div class="custom-file col-sm-6">
														<input type="file" name="chemists_employed_docs" class="form-control" id="chemists_employed_docs", multiple='multiple'>
														<div id="null_record_in_chemist_table"></div>
														<span id="error_chemists_employed_docs" class="error invalid-feedback"></span>
														<span id="error_size_chemists_employed_docs" class="error invalid-feedback"></span>
														<span id="error_type_chemists_employed_docs" class="error invalid-feedback"></span>
													</div>
												</div>
											<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Premises belongs to applicant?</h3> </div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Is the premises owned by the applicant? <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php
													$export_unit = $section_form_details[0]['premises_belongs_to'];
													if($export_unit == 'yes'){
														$checked_yes = 'checked';
														$checked_no = '';
													} else {

														$checked_yes = '';
														$checked_no = 'checked';
													}
												?>
												
												<div class="icheck-success d-inline">
													<input type="radio" name="premises_belongs_to" checked="" id="premises_belongs_to-yes" value="yes" <?php echo $checked_yes; ?>>
													<label for="premises_belongs_to-yes">Yes
													</label>
												</div>
												<div class="icheck-success d-inline">
													<input type="radio" name="premises_belongs_to" id="premises_belongs_to-no" value="no" <?php echo $checked_no; ?>>
													<label for="premises_belongs_to-no">No
													</label>
												</div>
											</div>
										</div>
										<div id="belongs_to" class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Give Premises Owner Name <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('owner_name', array('type'=>'text', 'escape'=>false, 'value'=>$section_form_details[0]['owner_name'], 'id'=>'owner_name', 'label'=>false, 'placeholder'=>'Please Enter Premises Owner Name', 'class'=>'form-control')); ?>
												<span id="error_owner_name" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Enclose a self-attested copy of the Premises relevent Document</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
													<?php if(!empty($section_form_details[0]['premises_belongs_to_docs'])){ ?>
														<a target="blank" id="premises_belongs_to_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['premises_belongs_to_docs']); ?>">Preview</a>
													<?php } ?>
												</label>

												<div class="custom-file col-sm-9">
													<input type="file" name="premises_belongs_to_docs" class="form-control" id="premises_belongs_to_docs", multiple='multiple'>
													<span id="error_premises_belongs_to_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
													<span id="error_size_premises_belongs_to_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
													<span id="error_type_premises_belongs_to_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
												</div>
											</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Total Area Covered</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<label for="inputEmail3" class="col-sm-6 col-form-label">Area Of Laboratory (Sq. Meter) <span class="cRed">*</span></label>
										<div class="form-group">
											<div class="col-sm-9">
												<?php echo $this->Form->control('total_area_covered', array('type'=>'text', 'escape'=>false, 'value'=>$section_form_details[0]['total_area_covered'], 'id'=>'total_area_covered', 'label'=>false, 'placeholder'=>'Please Enter Lab Area here', 'class'=>'form-control')); ?>
												<span id="error_total_area_covered" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Enclose a self-attested copy of the sketch of the laboratory</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
													<?php if(!empty($section_form_details[0]['total_area_covered_docs'])){ ?>
															<a target="blank" id="total_area_covered_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['total_area_covered_docs']); ?>"> Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="total_area_covered_docs" class="form-control" id="total_area_covered_docs" multiple='multiple'>
													<span id="error_total_area_covered_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
													<span id="error_size_total_area_covered_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
													<span id="error_type_total_area_covered_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
												</div>
											</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Is Accreditated with NABL</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Is laboratory accreditated with? <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php //if lab export then NABL accreditation is mandatory applied on 27-09-2021 by Amol
													if ($export_unit_status == 'yes') {
														$nabl_checked_yes = 'checked';
													} else {
														$nabl_accred = $section_form_details[0]['is_accreditated'];
														if ($nabl_accred == 'yes') {
															$nabl_checked_yes = 'checked';
															$nabl_checked_no = '';
														} else {
															$nabl_checked_yes = '';
															$nabl_checked_no = 'checked';
														}
													}
												?>
												
												<div class="icheck-success d-inline">
													<input type="radio" name="is_accreditated" checked="" id="is_accreditated-yes" value="yes" <?php echo $nabl_checked_yes; ?>>
													<label for="is_accreditated-yes">Yes</label>
												</div>

												<?php //if lab export then NABL accreditation is mandatory applied on 27-09-2021 by Amol
													if ($export_unit_status != 'yes') { ?>
														<div class="icheck-success d-inline">
															<input type="radio" name="is_accreditated" id="is_accreditated-no" value="no" <?php echo $nabl_checked_no; ?>>
															<label for="is_accreditated-no">No</label>
														</div>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

          				<div class="form-horizontal" id="is_accreditated_attached">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Area Of Laboratory (Sq. Meter) <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('accreditation_no', array('type'=>'text', 'id'=>'accreditation_no', 'escape'=>false, 'value'=>$section_form_details[0]['accreditation_no'], 'label'=>false, 'placeholder'=>'Please Enter accreditation Number', 'class'=>'form-control')); ?>
												<span id="error_accreditation_no" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Scope <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('accreditation_scope', array('type'=>'textarea', 'id'=>'accreditation_scope', 'escape'=>false, 'value'=>$section_form_details[0]['accreditation_scope'], 'label'=>false, 'placeholder'=>'Please Enter accreditation Scope', 'class'=>'form-control')); ?>
												<span id="error_accreditation_scope" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
											</div>
										</div>

										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">NABL Accreditated upto <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('nabl_accreditated_upto', array('type'=>'text', 'id'=>'nabl_accreditated_upto', 'escape'=>false, 'value'=>$section_form_details[0]['nabl_accreditated_upto'], 'label'=>false, 'placeholder'=>'Select date upto which the NABL is accreditated', 'class'=>'form-control','readonly'=>true)); ?>
												<span id="error_nabl_accreditated_upto" class="error invalid-feedback"></span>
											</div>
										</div>
									</div>
									
									<div class="col-sm-6">
										<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>
											<?php if ($export_unit_status == 'yes') { ?>
												Upload accreditation Certificate of NABL for biological & chemical.
											<?php } else { ?>
												Upload accreditation Certificate of NABL.
											<?php } ?>
										</p>
										
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
												<?php if(!empty($section_form_details[0]['accreditation_docs'])){?>
													<a target="blank" id="accreditation_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['accreditation_docs']); ?>">Preview</a>
												<?php } ?>
											</label>

											<div class="custom-file col-sm-9">
												<input type="file" name="accreditation_docs" class="form-control" id="accreditation_docs" multiple='multiple'>
												<span id="error_accreditation_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
												<span id="error_size_accreditation_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
												<span id="error_type_accreditation_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
											</div>
										</div>
										
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>

										<?php if($export_unit_status == 'yes'){ ?>
											<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Upload the valid recognition certificate issued from APEDA.</p>
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
														<?php if(!empty($section_form_details[0]['apeda_docs'])){ ?>
															<a target="blank" id="apeda_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['apeda_docs']); ?>">Preview</a>
														<?php } ?>
													</label>
											
													<div class="custom-file col-sm-9">
														<input type="file" name="apeda_docs" class="form-control" id="apeda_docs" multiple='multiple'>
														<span id="error_apeda_docs" class="error invalid-feedback"></span>
														<span id="error_size_apeda_docs" class="error invalid-feedback"></span>
														<span id="error_type_apeda_docs" class="error invalid-feedback"></span>
													</div>
												</div>
											<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Is laboratory Equipped</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-6 col-form-label">Whether the laboratory is fully equipped for testing and grading of commodity(ies) for which approval is sought ? <span class="cRed">*</span></label>
												<div class="col-sm-6">
													<?php

														$lab_equip = $section_form_details[0]['is_laboretory_equipped'];

														if($lab_equip == 'yes'){

															$lab_equip_checked_yes = 'checked';

															$lab_equip_checked_no = '';

														} else {

															$lab_equip_checked_yes = '';

															$lab_equip_checked_no = 'checked';

														}
													?>
													
												<div class="icheck-success d-inline">
													<input type="radio" name="is_laboretory_equipped" id="is_laboretory_equipped-yes" value="yes" <?php echo $lab_equip_checked_yes; ?>>
													<label for="is_laboretory_equipped-yes">Yes
													</label>
												</div>
												<div class="icheck-success d-inline">
													<input type="radio" name="is_laboretory_equipped" id="is_laboretory_equipped-no" value="no" <?php echo $lab_equip_checked_no; ?>>
													<label for="is_laboretory_equipped-no">No
													</label>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-12" id="laboretory_equipped_attached">
										<p class="lab_form_note bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Upload complete list of equipments, Glasswares & Chemicals duly singed & stamp of applicant</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-2 col-form-label">Attach File :
													<?php if(!empty($section_form_details[0]['is_laboretory_equipped_docs'])){ ?>
														<a target="blank" id="is_laboretory_equipped_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['is_laboretory_equipped_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-5">
													<input type="file" name="is_laboretory_equipped_docs" class="form-control" id="is_laboretory_equipped_docs" multiple='multiple'>
													<span id="error_is_laboretory_equipped_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
													<span id="error_size_is_laboretory_equipped_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
													<span id="error_type_is_laboretory_equipped_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
												</div>
											</div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>
							</div>
						</div>

						<!-- added new block for CEO name if lab-export on 31-08-2017 by Amol -->
						<?php if ($export_unit_status == 'yes') { ?>

							<div class="card-header"><h3 class="card-title">Chief Executive of Laboratory</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Name of Chief Executive <span class="cRed">*</span></a></label>
												<div class="col-sm-9">
													<?php echo $this->Form->control('lab_ceo_name', array('type'=>'text', 'escape'=>false, 'id'=>'lab_ceo_name', 'value'=>$section_form_details[0]['lab_ceo_name'], 'label'=>false, 'placeholder'=>'Please Enter Name here', 'class'=>'form-control')); ?>
													<span id="error_lab_ceo_name" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							
						<?php } ?>

						<div class="card-header"><h3 class="card-title">Any Other Information ?</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Relevant Information <span class="cRed">*</span></a></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('other_information', array('type'=>'textarea', 'escape'=>false, 'id'=>'other_information', 'value'=>$section_form_details[0]['other_information'], 'label'=>false, 'placeholder'=>'Please Enter other relevent information', 'class'=>'form-control')); ?>
												<span id="error_other_information" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
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

	<!--
		
		// this variable is commented as it seems its not been used sa of now so commented on the 31-03-2022
		// if in future any problem persists check the validations 
		
		<input type="hidden" id="chemist_details_value_id" value="<?php echo json_encode($chemist_details); ?>">
 
	-->
<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
<input type="hidden" id="export_unit_status_id" value="<?php echo $export_unit_status; ?>">

<?php echo $this->Html->script('element/application_forms/new/laboratory/lab_other_details'); ?>
