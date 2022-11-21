
<?php //pr($section_form_details);die;
	echo $this->Html->css('../multiselect/jquery.multiselect');
	echo $this->Html->script('../multiselect/jquery.multiselect');
?>
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

<section class="content form-middle form_outer_class" id="form_outer_main">
	<div class="container-fluid">
		<h5 class="mt-1 mb-2">Designated Person Details</h5>
		<div class="row">
			<div class="col-md-12">
			    <div class="card card-success">
				   <div id="chemist_table" class="machinery_table">
					  <div class="cNavy m-2">Note: You can only add up to four designated people.</div>
						 <div class="form-horizontal">
							<div class="card-body p-0 m-2 rounded">
								<div class="row">
									<div class="col-sm-12 scroll">
										<div class="table-format">
											<table id="person_details_table" class="table chemisttable persontbl table-bordered">
												<thead class="tablehead">
													<tr>
														<th>S.No</th>
														<th>Name of Person</th>
														<th>Qualification (Highest)</th>
                                                        <th>Upload File<br>(Qualification Doc)</th>
														<th>Experience (In Years)</th>
                                                        <th>Upload File<br>(Experience Doc)</th>
														<th>Upload File<br>(Profile Pic)</th>
                                                        <th>Upload File<br>(Signature)</th>
														<th>He/She is responsible person for labortory</th>
														<th>He/She has any criminal record</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<div id="machinery_each_row">
														<?php 
														$i=1;
														
														foreach($section_form_details[1][0] as $person_detail){?>
														
															<tr>
																<td><?php echo $i; ?></td>
																<td><?php echo $person_detail['person_name'];?></td>
																<td><?php echo $person_detail['qualification']; ?></td>
																<td><?php if ($person_detail['qualifi_docs'] != null) {  ?>
																		<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$person_detail['qualifi_docs']); ?>">Preview</a>
																	<?php } else { echo "No File Attached"; } ?>
																</td>
																<td><?php echo $person_detail['experience']; ?></td>
                                                                <td><?php if ($person_detail['exp_docs'] != null) { ?>
																		<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$person_detail['exp_docs']); ?>">Preview</a>
																	<?php } else { echo "No File Attached"; } ?>
																</td>
																<td><?php if ($person_detail['profile_pic'] != null) { ?>
																		<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$person_detail['profile_pic']); ?>">Preview</a>
																	<?php } else { echo "No File Attached"; } ?>
																</td>
																<td><?php if ($person_detail['signature_docs'] != null) { ?>
																		<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$person_detail['signature_docs']); ?>">Preview</a>
																	<?php } else { echo "No File Attached"; } ?>
																</td>
																<td>
																<?php
																		$is_responsible = $person_detail['is_responsible'];
																		
																			if($is_responsible == 'yes'){
																				
																				$checked_yes = 'checked';
																				$checked_no = '';
																			} else {
																				$checked_yes = '';
																				$checked_no = 'checked';
																			}
																		?>
																	<div class=" d-inline">
																		<input type="radio" name="is_responsible<?php echo $i; ?>" checked="" id="is_responsible-yes<?php echo $i ?>" value="yes" <?php echo $checked_yes; ?>>
																		<label for="is_responsible-yes">Yes
																		</label>
																	</div>
																	<div class=" d-inline">
																		<input type="radio" name="is_responsible<?php echo $i; ?>" id="is_responsible-no <?php echo $i ?>" value="no" <?php  echo $checked_no; ?>>
																		<label for="is_responsible-no">No
																		</label>
																	</div>
																	
																</td>
																<td>
																<?php
																		$any_criminal_record = $person_detail['any_criminal_record'];
																		
																			if($any_criminal_record == 'yes'){
																				$checked_yes = 'checked';
																				$checked_no = '';
																			} else {

																				$checked_yes = '';
																				$checked_no = 'checked';
																			}
																		?>
																	<div class="d-inline">
																		<input type="radio" name="any_criminal_record<?php echo $i;?>" checked="" id="any_criminal_record-yes<?php echo $i; ?>" value="yes" <?php echo $checked_yes; ?>>
																		<label for="any_criminal_record-yes">Yes
																		</label>
																	</div>
																	<div class="d-inline">
																		<input type="radio" name="any_criminal_record<?php echo $i;?>" id="any_criminal_record-no<?php echo $i; ?>" value="no" <?php  echo $checked_no; ?>>
																		<label for="any_criminal_record-no">No
																		</label>
																	</div>

																	
																</td>
																<td>
																	<?php echo $this->Html->link('', array('controller' => 'application', 'action'=>'edit_person_id',$person_detail['id']),array('class'=>'glyphicon glyphicon-edit packer_edit', 'title'=>'Edit')); ?> |
																	<?php echo $this->Html->link('', array('controller' => 'application', 'action'=>'delete_person_id',$person_detail['id']),array('class'=>'glyphicon glyphicon-remove-sign packer_delete', 'title'=>'Delete')); ?>
																</td>
															</tr>

														<?php $i=$i+1;} ?>
													</div>
													<div id="error_person" class="text-red float-right text-sm"></div>
													<!-- for edit person details -->
													<?php if ($this->getRequest()->getSession()->read('edit_person_id') != null) { ?>
																<tr>
																	<td></td>
																	<td><div class =""><?php echo $this->Form->control('person_name', array('type'=>'text', 'value'=>$section_form_details[1][1]['person_name'], 'escape'=>false,  'label'=>false,'id'=>'chemist_name', 'class'=>'form-control wd100')); ?>
																		<span id="error_person_name" class="error invalid-feedback"></span>
																	</td>
																	<td><?php echo $this->Form->control('qualification', array('type'=>'text', 'value'=>$section_form_details[1][1]['qualification'], 'escape'=>false,  'label'=>false, 'id'=>'chemist_qualification', 'class'=>'form-control wd100')); ?>
																		<span id="error_qualification" class="error invalid-feedback"></span>
																	</td>
                                                                    <td><?php if($section_form_details[1][1]['qualifi_docs'] != null){?>
																		<a target="blank" id="person_qualifi_details_doc" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[1][1]['qualifi_docs']); ?>">Preview</a>
																		<?php }?>
																		<?php echo $this->Form->control('person_qualifi_details_doc',array('type'=>'file', 'id'=>'person_qualifi_details_doc', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																			<span id="error_person_qualifi_details_doc" class="error invalid-feedback"></span>
																			<span id="error_size_person_qualifi_details_doc" class="error invalid-feedback"></span>
																			<span id="error_type_person_qualifi_details_doc" class="error invalid-feedback"></span>
																	</td>
																	<td><?php echo $this->Form->control('experience', array('type'=>'text', 'value'=>$section_form_details[1][1]['experience'], 'escape'=>false,  'label'=>false, 'id'=>'person_experience', 'class'=>'form-control wd100')); ?>
																		<span id="error_experience" class="error invalid-feedback"></span>
																	</td>
																	<td><?php if($section_form_details[1][1]['exp_docs'] != null){?>
																		<a target="blank" id="person_details_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[1][1]['exp_docs']); ?>">Preview</a>
																		<?php }?>
																		<?php echo $this->Form->control('person_exp_details_doc',array('type'=>'file', 'id'=>'person_exp_details_doc', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																			<span id="error_experience_details_docs" class="error invalid-feedback"></span>
																			<span id="error_size_chemists_details_docs" class="error invalid-feedback"></span>
																			<span id="error_type_chemists_details_docs" class="error invalid-feedback"></span>
																	</td>
                                                                    <td><?php if($section_form_details[1][1]['profile_pic'] != null){?>
																		<a target="blank" id="profile_pic_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[1][1]['profile_pic']); ?>">Preview</a>
																		<?php }?>
																		<?php echo $this->Form->control('profile_pic',array('type'=>'file', 'id'=>'profile_pic', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																			<span id="error_profile_pic" class="error invalid-feedback"></span>
																			<span id="error_size_profile_pic" class="error invalid-feedback"></span>
																			<span id="error_type_profile_pic" class="error invalid-feedback"></span>
																	</td>
																	<td><?php if($section_form_details[1][1]['signature_docs'] != null){?>
																		<a target="blank" id="signature_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[1][1]['signature_docs']); ?>">Preview</a>
																		<?php }?>
																		<?php echo $this->Form->control('signature_docs',array('type'=>'file', 'id'=>'signature_docs', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																			<span id="error_signature_docs" class="error invalid-feedback"></span>
																			<span id="error_size_signature_docs" class="error invalid-feedback"></span>
																			<span id="error_type_signature_docs" class="error invalid-feedback"></span>
																	</td>
																	<td>
																	<?php
																		$is_responsible = $section_form_details[1][1]['is_responsible'];
																		
																			if($is_responsible == 'yes'){
																				
																				$checked_yes = 'checked';
																				$checked_no = '';
																			} else {
																				$checked_yes = '';
																				$checked_no = 'checked';
																			}
																		?>
																	<div class=" d-inline">
																		<input type="radio" name="is_responsible" checked="" id="is_responsible-yes" value="yes" <?php echo $checked_yes; ?>>
																		<label for="is_responsible-yes">Yes
																		</label>
																	</div>
																	<div class=" d-inline">
																		<input type="radio" name="is_responsible" id="is_responsible-no" value="no" <?php  echo $checked_no; ?>>
																		<label for="is_responsible-no">No
																		</label>
																	</div>
																	</td>
																	<td>
																	<?php
																		$any_criminal_record = $section_form_details[1][1]['any_criminal_record'];
																		
																			if($any_criminal_record == 'yes'){
																				$checked_yes = 'checked';
																				$checked_no = '';
																			} else {

																				$checked_yes = '';
																				$checked_no = 'checked';
																			}
																		?>
																	<div class="d-inline">
																		<input type="radio" name="any_criminal_record" checked="" id="any_criminal_record-yes" value="yes" <?php echo $checked_yes; ?>>
																		<label for="any_criminal_record-yes">Yes
																		</label>
																	</div>
																	<div class="d-inline">
																		<input type="radio" name="any_criminal_record" id="any_criminal_record-no" value="no" <?php  echo $checked_no; ?>>
																		<label for="any_criminal_record-no">No
																		</label>
																	</div>

																	</td>
																	<td><?php echo $this->form->submit('Save', array('name'=>'edit_person_details', 'id'=>'edit_person_details', 'label'=>false,'class'=>'form-control wd100 btn btn-success')); ?></td>
																</tr>
															
													<!-- To show added and save new machine details -->
													<?php } elseif ($this->request->getParam('controller') == 'Application') { ?>
													
															<div id="add_new_row">
																<tr id="add_new_row_r">
																	<td></td>
																	<td><?php echo $this->Form->control('person_name', array('type'=>'text', 'escape'=>false,  'label'=>false, 'id'=>'person_name', 'class'=>'form-control wd100')); ?>
																		<span id="error_person_name" class="error invalid-feedback"></span>
																	</td>
																	<td><?php echo $this->Form->control('qualification', array('type'=>'text', 'escape'=>false,  'label'=>false, 'id'=>'person_qualification', 'class'=>'form-control wd100')); ?>
																		<span id="error_qualification" class="error invalid-feedback"></span>
																	</td>
                                                                    <td><?php //echo $this->Form->control('person_qualifi_details_doc',array('type'=>'file', 'id'=>'person_qualifi_details_doc', 'onchange'=>'file_browse_onclick(id);return false', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																		<div class="custom-file col-sm-12">
																			<input type="file" name="person_qualifi_details_doc" class="form-control" id="person_qualifi_details_doc", multiple='multiple'>
																			<span id="error_person_qualifi_details_doc" class="error invalid-feedback"></span>
																			<span id="error_size_person_qualifi_details_doc" class="error invalid-feedback"></span>
																			<span id="error_type_person_qualifi_details_doc" class="error invalid-feedback"></span>
																		</div>
																	</td>
																	<td><?php echo $this->Form->control('experience', array('type'=>'text', 'escape'=>false,  'label'=>false, 'id'=>'person_experience', 'class'=>'form-control wd100')); ?>
																		<span id="error_experience" class="error invalid-feedback"></span>
																	</td>
                                                                    <td><?php //echo $this->Form->control('person_exp_details_doc',array('type'=>'file', 'id'=>'person_exp_details_doc', 'onchange'=>'file_browse_onclick(id);return false', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																		<div class="custom-file col-sm-12">
																			<input type="file" name="person_exp_details_doc" class="form-control" id="person_exp_details_doc", multiple='multiple'>
																			<span id="error_person_exp_details_doc" class="error invalid-feedback"></span>
																			<span id="error_size_person_exp_details_doc" class="error invalid-feedback"></span>
																			<span id="error_type_person_exp_details_doc" class="error invalid-feedback"></span>
																		</div>
																	</td>
																	<td><?php //echo $this->Form->control('profile_pic',array('type'=>'file', 'id'=>'profile_pic', 'onchange'=>'file_browse_onclick(id);return false', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																		<div class="custom-file col-sm-12">
																			<input type="file" name="profile_pic" class="form-control" id="profile_pic", multiple='multiple'>
																			<span id="error_profile_pic" class="error invalid-feedback"></span>
																			<span id="error_size_profile_pic" class="error invalid-feedback"></span>
																			<span id="error_type_profile_pic" class="error invalid-feedback"></span>
																		</div>
																	</td>
																	<td><?php //echo $this->Form->control('signature_doc',array('type'=>'file', 'id'=>'signature_doc', 'onchange'=>'file_browse_onclick(id);return false', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
																		<div class="custom-file col-sm-12">
																			<input type="file" name="signature_docs" class="form-control" id="signature_docs", multiple='multiple'>
																			<span id="error_signature_docs" class="error invalid-feedback"></span>
																			<span id="error_size_signature_docs" class="error invalid-feedback"></span>
																			<span id="error_type_signature_docs" class="error invalid-feedback"></span>
																		</div>
																	</td>
																	<td>
																		<div class=" d-inline">
																			<input type="radio" name="is_responsible" checked="" id="is_responsible-yes" value="yes">
																			<label for="is_responsible-yes">Yes
																			</label>
																		</div>
																		<div class=" d-inline">
																			<input type="radio" name="is_responsible" id="is_responsible-no" value="no" >
																			<label for="is_responsible-no">No
																			</label>
																		</div>
																	</td>

																	<td>
																		<div class=" d-inline">
																			<input type="radio" name="any_criminal_record" checked="" id="any_criminal_record-yes" value="yes">
																			<label for="any_criminal_record-yes">Yes
																			</label>
																		</div>
																		<div class=" d-inline">
																			<input type="radio" name="any_criminal_record" id="any_criminal_record-no" value="no" >
																			<label for="any_criminal_record-no">No
																			</label>
																		</div>
																	</td>
																	<td><?php echo $this->form->submit('Add', array('name'=>'add_person_details', 'id'=>'add_person_details', 'label'=>false,'class'=>'form-control wd100 btn btn-success')); ?></td>
																</tr>
															</div>
															
													<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<div class="card-header"><h3 class="card-title">Any Other Information ?</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Relevant Information <span class="cRed">*</span></a></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('other_information', array('type'=>'textarea', 'escape'=>false, 'id'=>'other_information', 'value'=>$section_form_details[0]['any_other_info'], 'label'=>false, 'placeholder'=>'Please Enter other relevent information', 'class'=>'form-control')); ?>
											<span id="error_other_information" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
										</div>
										
									</div>
								</div>
                                <div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Relevant Uploads <span class="cRed">*</span></a></label>
										<div class="custom-file col-sm-12">
										    <?php if($section_form_details[0]['any_other_upload'] != null){?>
										     <a target="blank" id="other_docs_value" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['any_other_upload']); ?>">Preview</a>
											 <?php }?>
											<?php echo $this->Form->control('any_other_upload',array('type'=>'file', 'id'=>'any_other_upload', 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control wd100')); ?>
												<span id="error_any_other_upload" class="error invalid-feedback"></span>
												<span id="error_size_any_other_upload" class="error invalid-feedback"></span>
												<span id="error_type_any_other_upload" class="error invalid-feedback"></span>
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
		
		<input type="hidden" id="chemist_details_value_id" value="<?php //echo json_encode($person_details); ?>">
 
	-->
<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
<input type="hidden" id="export_unit_status_id" value="<?php echo $export_unit_status; ?>">

<?php echo $this->Html->script('element/application_forms/adp/person_details'); ?>

