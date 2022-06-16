<?php

echo $this->Html->css('../multiselect/jquery.multiselect');
echo $this->Html->script('../multiselect/jquery.multiselect');

?>

<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
<section class="content form-middle form_outer_class" id="form_outer_main">
	<div class="container-fluid">
	  	<h5 class="mt-1 mb-2">Laboratory Firm Renewal Details</h5>
	    	<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div id="chemist_table" class="machinery_table">
							<div class="card-header sub-card-header-firm"><h3 class="card-title">Chemist Details, Appointment/Acceptance/Resignation Letter (as applicable),etc</h3></div>
								<div class="card-body">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group row">
												<label class="fLMr">Add Old Chemist Records </label>
												<?php echo $this->Form->control('chemist_details_choice', array('type'=>'select', 'multiple'=>'checkbox', 'id'=>'chemist_details_choice', 'value'=>$section_form_details[1][5], 'options'=>array(1=>''), 'escape'=>false, 'label'=>false, 'class'=>'form-control')); ?>
												<div class="clearfix"></div>
											</div>
										</div>
									</div>
								</div>

							<div class="form-horizontal">
								<div class="card-body p-0 m-4 border rounded">
									<div class="row">
										<div class="col-sm-12">

											<div class="table-format"><!-- table-format -->
												<div id="renewal_side_table" class="table-responsive"><!-- renewal_side_table -->
													<table class="table chemisttable" id="renewal_chemist_table">

														<thead>
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
																	<td><?php if($chemist_detail['chemists_details_docs'] != null){?>
																		<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$chemist_detail['chemists_details_docs']); ?>">Preview</a>
																		<?php }else{ echo "No File Attached";} ?>
																	</td>
																	<td>
																		<div class="btn-group btn-group-sm">
																			<?php echo $this->Html->link('<i class="fas fa-edit"></i>', array('controller' => 'application', 'action'=>'edit_chemist_id',$chemist_detail['id']),array('class'=>'far fa-edit packer_edit btn btn-info', 'title'=>'Edit', 'escape'=>false)); ?> |
																			<?php echo $this->Html->link('<i class="fas fa-trash"></i>', array('controller' => 'application', 'action'=>'delete_chemist_id',$chemist_detail['id']),array('class'=>'far fa-trash-alt packer_delete btn btn-danger', 'title'=>'Delete', 'escape'=>false)); ?>
																		</div>
																	</td>
																</tr>

																<?php $i=$i+1; }  ?>
															</div>

															<!-- for edit chemist details -->
															<?php if($this->getRequest()->getSession()->read('edit_chemist_id') != null){ ?>

															<tr>
																<td></td>
																<td><?php echo $this->Form->control('chemist_name', array('type'=>'text', 'value'=>$section_form_details[1][6]['chemist_name'], 'escape'=>false,  'label'=>false, 'id'=>'chemist_name','class'=>'form-control wd100')); ?></td>
																<td><?php echo $this->Form->control('qualification', array('type'=>'text', 'value'=>$section_form_details[1][6]['qualification'], 'escape'=>false,  'label'=>false, 'id'=>'chemist_qualification','class'=>'form-control wd100')); ?></td>
																<td><?php echo $this->Form->control('experience', array('type'=>'text', 'value'=>$section_form_details[1][6]['experience'], 'escape'=>false,  'label'=>false, 'id'=>'chemist_experience','class'=>'form-control wd100')); ?></td>
																<td class="mx-height"><?php echo $this->Form->control('commodity', array('type'=>'select',  'value'=>$section_form_details[1][7], 'options'=>$section_form_details[1][2], 'multiple'=>'multiple','escape'=>false, 'id'=>'chemist_list', 'label'=>false, 'class'=>'form-control wd100')); ?></td>
																<td><?php if($section_form_details[1][6]['chemists_details_docs'] != null){?>
																	<a target="blank" id="chemists_details_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[1][6]['chemists_details_docs']); ?>">Preview</a>
																	<?php }?>
																	</span>
																	<?php echo $this->Form->control('chemists_details_docs',array('type'=>'file', 'id'=>'chemists_details_docs', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																</td>
																<td><?php echo $this->form->submit('Save', array('name'=>'add_renewal_chemist_details', 'id'=>'edit_chemist_details', 'label'=>false, 'class'=>'form-control wd100 btn btn-success')); ?></td>
															</tr>

															<!-- To show added and save new machine details -->
															<?php }else{?>

															<div id="add_new_row">
																<tr id="add_new_row_r">
																	<td></td>
																	<td><?php echo $this->Form->control('chemist_name', array('type'=>'text', 'escape'=>false,  'label'=>false, 'id'=>'chemist_name', 'class'=>'form-control wd100')); ?></td> <!--Set Id To input Field  (by pravin 12/05/2017) -->
																	<td><?php echo $this->Form->control('qualification', array('type'=>'text', 'escape'=>false,  'label'=>false, 'id'=>'chemist_qualification', 'class'=>'form-control wd100')); ?></td> <!--Set Id To input Field  (by pravin 12/05/2017) -->
																	<td><?php echo $this->Form->control('experience', array('type'=>'text', 'escape'=>false,  'label'=>false, 'id'=>'chemist_experience', 'class'=>'form-control wd100')); ?></td> <!--Set Id To input Field  (by pravin 12/05/2017) -->
																	<td><?php echo $this->Form->control('commodity', array('type'=>'select',  'options'=>$section_form_details[1][2], 'multiple'=>'multiple','escape'=>false, 'id'=>'chemist_list', 'label'=>false, 'class'=>'form-control wd100')); ?></td> <!--Set Id To input Field  (by pravin 12/05/2017) -->
																	<td><?php echo $this->Form->control('chemists_details_docs',array('type'=>'file', 'id'=>'chemists_details_docs', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?></td>
																	<td><?php echo $this->form->submit('Add', array('name'=>'add_renewal_chemist_details', 'id'=>'add_chemist_details', 'label'=>false, 'class'=>'form-control wd100')); ?></td> <!--Set Id To input Field & call validation table validation file (by pravin 12/05/2017) -->
																</tr>
															</div>
															<?php } ?>
														</tbody>
													</table>
												</div><!-- renewal_side_table -->

												<div id="application_side_chemist_table" class="table-responsive"><!-- application_side_chemist_table -->
													<div id="machinery_each_row"><!-- machinery_each_row -->

														<table class="table chemisttable" >
															<?php
															$i=1;
															foreach($section_form_details[1][3] as $chemist_detail){ ?>

															<tr>
																<td class="wd7"><?php echo $i; ?></td>
																<?php echo $this->Form->control('application_side_chemist_id'.$chemist_detail['id'], array('type'=>'hidden', 'value'=>$chemist_detail['id'], 'escape'=>false,  'label'=>false,  'class'=>'form-control wd100')); ?>
																<td class="wd10"><?php echo $this->Form->control('application_side_chemist_name'.$chemist_detail['id'], array('type'=>'text', 'value'=>$chemist_detail['chemist_name'], 'escape'=>false,  'label'=>false,  'class'=>'form-control wd100')); ?></td>
																<td class="wd12"><?php echo $this->Form->control('application_side_qualification'.$chemist_detail['id'], array('type'=>'text', 'value'=>$chemist_detail['qualification'], 'escape'=>false,  'label'=>false,  'class'=>'form-control wd100')); ?></td>
																<td class="wd10"><?php echo $this->Form->control('application_side_experience'.$chemist_detail['id'], array('type'=>'text', 'value'=>$chemist_detail['experience'], 'escape'=>false,  'label'=>false,  'class'=>'form-control wd100')); ?></td>
																<td class="wd22"><?php echo $this->Form->control('application_side_commodity'.$chemist_detail['id'], array('type'=>'select',  'value'=>$section_form_details[1][4][$i], 'options'=>$section_form_details[1][2], 'multiple'=>'multiple','escape'=>false, 'class'=>'application_side_commodity form-control wd100', 'label'=>false )); ?></td>
																<td><?php if($chemist_detail['chemists_details_docs'] != null){?>
																	<a target="blank" id="chemists_details_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$chemist_detail['chemists_details_docs']); ?>">Preview</a>
																	<?php }?>
																	</span>
																	<?php echo $this->Form->control('chemists_details_docs'.$chemist_detail['id'],array('type'=>'file', 'id'=>'chemists_details_docs', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																</td>
																<td class="wd10"><?php echo $this->form->submit('Add', array('name'=>'add_old_chemist_details', 'class'=>'old_record_class', 'id'=>'oldrecordsave-'.$chemist_detail['id'], 'label'=>false,  'class'=>'form-control wd100')); ?></td>
															</tr>
														<?php $i=$i+1; } ?>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-body">
	            			<div class="row">
		            			<div class="col-sm-12">
									<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Upload file having details like; number of chemists , total area of unit, accreditation, equipment , glassware, chemicals, etc.</p>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
												<?php if (!empty($section_form_details[0]['chemist_detail_docs'])) { ?>
													<a target="blank" id="chemist_detail_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['chemist_detail_docs']); ?>">Preview</a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-9">
						            			<input type="file" name="chemist_detail_docs" class="custom-file-input" id="chemist_detail_docs" multiple='multiple'>
					                			<label class="custom-file-label" for="customFile">Choose file</label>
				              				</div>
										</div>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										<div id="error_chemist_detail_docs"></div>
										<div id="error_size_chemist_detail_docs"></div>
										<div id="error_type_chemist_detail_docs"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-header"><h3 class="card-title">Details of Authorized Packers</h3></div>
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12">
										<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Commodity wise Nos. of authorized packers attached with the laboratory for grading and marking</p>
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
													<?php if (!empty($section_form_details[0]['authorized_packers_docs'])) { ?>
														<a target="blank" id="authorized_packers_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['authorized_packers_docs']); ?>">Preview</a>
													<?php } ?>
												</label>
											<div class="custom-file col-sm-9">
												<input type="file" name="authorized_packers_docs" class="custom-file-input" id="authorized_packers_docs"  multiple='multiple'>
												<label class="custom-file-label" for="customFile">Choose file</label>
											</div>
										</div>
								<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
								<div id="error_authorized_packers_docs"></div>
								<div id="error_size_authorized_packers_docs"></div>
								<div id="error_type_authorized_packers_docs"></div>
							</div>
						</div>
					</div>
					<div class="card-header"><h3 class="card-title">No. of lots graded</h3></div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Packer wise No. of lots graded during the last two years (year wise and commodity wise)</p>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
											<?php if (!empty($section_form_details[0]['lots_graded_docs'])) { ?>
												<a target="blank" id="lots_graded_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['lots_graded_docs']); ?>">Preview</a>
											<?php } ?>
										</label>
										<div class="custom-file col-sm-9">
											<input type="file" name="lots_graded_docs" class="custom-file-input" id="lots_graded_docs"  multiple='multiple'>
											<label class="custom-file-label" for="customFile">Choose file</label>
										</div>
									</div>
								<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
								<div id="error_lots_graded_docs"></div>
								<div id="error_size_lots_graded_docs"></div>
								<div id="error_type_lots_graded_docs"></div>
							</div>
						</div>
					</div>

					<div class="card-header"><h3 class="card-title">Quantity graded</h3></div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Packer wise quantity graded during the last two years (year wise and commodity wise)</p>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
												<?php if(!empty($section_form_details[0]['quantity_graded_docs'])){?>
													<a target="blank" id="quantity_graded_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['quantity_graded_docs']); ?>">Preview</a>
												<?php } ?>
											</label>
											<div class="custom-file col-sm-9">
												<input type="file" name="quantity_graded_docs" class="custom-file-input" id="quantity_graded_docs" multiple='multiple'>
												<label class="custom-file-label" for="customFile">Choose file</label>
											</div>
										</div>
									<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									<div id="error_quantity_graded_docs"></div>
									<div id="error_size_quantity_graded_docs"></div>
									<div id="error_type_quantity_graded_docs"></div>
								</div>
							</div>
						</div>


					<div class="card-header"><h3 class="card-title">Misgraded Check Sample</h3></div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Details of check samples misgraded during last validity period (Two years)</p>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
											<?php if(!empty($section_form_details[0]['check_Sample_docs'])){?>
												<a target="blank" id="check_Sample_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['check_Sample_docs']); ?>">Preview</a>
											<?php } ?>
										</label>
										<div class="custom-file col-sm-9">
											<input type="file" name="check_Sample_docs" class="custom-file-input" id="check_Sample_docs" multiple='multiple'>
											<label class="custom-file-label" for="customFile">Choose file</label>
										</div>
									</div>
									<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									<div id="error_check_Sample_docs"></div>
									<div id="error_size_check_Sample_docs"></div>
									<div id="error_type_check_Sample_docs"></div>
								</div>
							</div>
						</div>

					<div class="card-header"><h3 class="card-title">Any Warning Issued</h3></div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
				          			<div class="form-group row">
			              				<p class="pl-2 p-1 rounded">
				              				<label for="inputEmail3" class="col-sm-12 text-sm">Whether any warning(s) was/were issued against misgrading(s) during last validity period OR the laboratory was suspended against misgrading.</label>
				              					<div class="ml-5 pl-4 col-sm-3">
											     <?php
											           $is_warning_issued = $section_form_details[0]['is_warning_issued'];
											           if ($is_warning_issued == 'yes') {
												         $is_warning_issued_yes = 'checked';
												         $is_warning_issued_no = '';
													   } else if ($is_warning_issued == 'no') {
														         $is_warning_issued_yes = '';
														         $is_warning_issued_no = 'checked';
													   } else {
												         $is_warning_issued_yes = '';
												         $is_warning_issued_no = '';
											     	   }
											     ?>
								                 <div class="icheck-success d-inline">
								                      <input type="radio" name="is_warning_issued" id="is_warning_issued-yes" value="yes" <?php echo $is_warning_issued_yes; ?>>
								                      <label for="is_warning_issued-yes">Yes</label>
								                 </div>
								                 <div class="icheck-success d-inline">
								                      <input type="radio" name="is_warning_issued" id="is_warning_issued-no" value="no" <?php echo $is_warning_issued_no; ?>>
								                      <label for="is_warning_issued-no">No</label>
								                 </div>
							                 <span id="error_is_warning_issued" class="error invalid-feedback"></span>
							              </div>
						              </p>
			          			</div>
								 <div class="form-group row">
									 <div id="warning_details_box" class="col-sm-6">
		                     			<?php echo $this->Form->control('warning_details', array('type'=>'textarea', 'id'=>'warning_details', 'escape'=>false, 'value'=>$section_form_details[0]['warning_details'], 'label'=>false, 'placeholder'=>'Please Enter any Warning Issued', 'class'=>'form-control')); ?>
	                				</div>
								 	<span id="error_warning_details" class="error invalid-feedback"></span>
							 	 	</div>
							 	</div>
						 	</div>
					 	</div>
					</div>
				</div>
			</div>
		<?php echo $this->Form->control('old_record_id', array('type'=>'hidden', 'id'=>'old_record_id', 'escape'=>false, 'value'=>'', 'label'=>false)); ?>
	</div>
</section>
<input type="hidden" value="<?php echo $final_submit_status; ?>" id="final_renewal_submit_status_id">
<?php echo $this->Html->script('element/application_forms/renewal/laboratory/lab_renewal_details'); ?>
