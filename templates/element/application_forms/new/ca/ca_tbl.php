
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
<section class="content form-middle form_outer_class" id="form_outer_main">
	<div class="container-fluid">
		<h5 class="mt-1 mb-2">Trade Brand Label Details</h5>
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div id="tbls_table">
							<div class="card-header sub-card-header-firm"><h3 class="card-title">TBL Details</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 m-4 rounded">
									<div class="row">
										<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Details of the TBLs proposed to be applied on the Graded Packages</p></div>
	                    				<div class="col-sm-12">
											<div class="machinery_table">
											<!-- call table view form element with ajax call -->
											<?php //echo $this->element('ca_other_tables_elements/tbl_details_table_view'); ?>
											<div class="table-format">
												<table id="tbls_table_view" class="table table-bordered table-striped">
													<thead class="tablehead">
														<th>Sr.No.</th>
														<th>TBL Name</th>
														<th>Registered?</th>
														<th>Reg. No.</th>
														<th>Upload File</th>
														<th>Action</th>
													</thead>
													<div id="machinery_each_row">
														<?php
														$i=1;
														foreach($section_form_details[1][0] as $each_tbl){ ?>
														<tr>
															<td><?php echo $i; ?></td>
															<td><?php echo $each_tbl['tbl_name']; ?></td>
															<td><?php echo $each_tbl['tbl_registered']; ?></td>
															<td><?php echo $each_tbl['tbl_registered_no']; ?></td>
															<td><?php if($each_tbl['tbl_registration_docs'] != null){?>
																	<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$each_tbl['tbl_registration_docs']); ?>">Preview</a>
																<?php }else{ echo "No File Attached";} ?></td>
															<td><?php echo $this->Html->link('', array('controller' => 'application', 'action'=>'edit_tbl_id',$each_tbl['id']),array('class'=>'glyphicon glyphicon-edit tbl_edit', 'title'=>'Edit')); ?> |
																<?php echo $this->Html->link('', array('controller' => 'application', 'action'=>'delete_tbl_id',$each_tbl['id']),array('class'=>'glyphicon glyphicon-remove-sign tbl_delete', 'title'=>'Delete')); ?>
															</td>
														</tr>
														<?php  $i=$i+1; } ?>
														<div id="error_tbls"></div>

													<!-- for edit machine details -->
													<?php  if($this->request->getSession()->read('edit_tbl_id') != null){?>
														<tr>
															<td></td>
															<td><?php  echo $this->Form->control('tbl_name', array('type'=>'text', 'id'=>'tbl_name', 'value'=>$section_form_details[1][1]['tbl_name'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?></td>
															<td>
																<?php
																$tbl_registered_radio = $section_form_details[1][1]['tbl_registered'];
																if($tbl_registered_radio == 'yes'){
																	$tbl_registered_radio_yes = 'checked';
																	$tbl_registered_radio_no = '';
																} else if($tbl_registered_radio == 'no'){
																	$tbl_registered_radio_yes = '';
																	$tbl_registered_radio_no = 'checked';
																} else {
																	$tbl_registered_radio_yes = '';
																	$tbl_registered_radio_no = '';
																}
																?>
										                      <div class="icheck-success d-inline">
										                        <input type="radio" name="tbl_registered" id="tbl_registered-yes" value="yes" <?php echo $tbl_registered_radio_yes; ?>>
										                        <label for="tbl_registered-yes">Yes
										                        </label>
										                      </div>
										                      <div class="icheck-success d-inline">
										                        <input type="radio" name="tbl_registered" id="tbl_registered-no" value="no" <?php echo $tbl_registered_radio_no; ?>>
										                        <label for="tbl_registered-no">No
										                        </label>
										                      </div>
															</td>
															<td><?php  echo $this->Form->control('tbl_registered_no', array('type'=>'text', 'id'=>'tbl_registered_no', 'value'=>$section_form_details[1][1]['tbl_registered_no'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?></td>
															<td>
																<div class="custom-file">
												                  <input type="file" name="tbl_registration_docs" class="custom-file-input", multiple='multiple'>
											                      <label class="custom-file-label" for="customFile">Choose file</label>
											                    </div>
											                    <?php if($section_form_details[1][1]['tbl_registration_docs'] != null){?>
																<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[1][1]['tbl_registration_docs']); ?>">Preview</a>
																<?php } ?>
															</td>
															<td><?php  echo $this->form->submit('save', array('name'=>'add_tbl_details', 'class'=>'table_record_add_btn btn btn-info btn-sm', 'id'=>'edit_tbl_details','label'=>false)); ?></td>
														</tr>

													<!-- To show added and save new machine details -->
													<?php  }else{?>

														<div id="add_new_row">
														<tr>
															<td></td>
															<td><?php  echo $this->Form->control('tbl_name', array('type'=>'text', 'id'=>'tbl_name', 'escape'=>false, 'class'=>'input-field', 'label'=>false, 'class'=>'form-control')); ?>
																<span id="error_tbl_name" class="error invalid-feedback"></span>
															</td>
															<td>
										                      <div class="icheck-success d-inline">
										                        <input type="radio" name="tbl_registered" id="tbl_registered-yes" value="yes" checked="">
										                        <label for="tbl_registered-yes">Yes
										                        </label>
										                      </div>
										                      <div class="icheck-success d-inline">
										                        <input type="radio" name="tbl_registered" id="tbl_registered-no" value="no">
										                        <label for="tbl_registered-no">No
										                        </label>
										                      </div>
						                      				  <span id="error_tbl_registered" class="error invalid-feedback"></span>
															</td>
															<td><?php echo $this->Form->control('tbl_registered_no', array('type'=>'text', 'id'=>'tbl_registered_no', 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?>
																<span id="error_tbl_registered_no" class="error invalid-feedback"></span>
															</td>
															<td>
																<div class="custom-file">
												                  <input type="file" name="tbl_registration_docs" class="custom-file-input" id="tbl_registration_file", multiple='multiple'>
											                      <label class="custom-file-label" for="customFile">Choose file</label>
											                  	  <span id="error_tbl_registration_docs" class="error invalid-feedback"></span>
											                  	  <span id="error_type_tbl_registration_docs" class="error invalid-feedback"></span>
											                  	  <span id="error_size_tbl_registration_docs" class="error invalid-feedback"></span>
											                    </div>
															</td>
															<td><?php  echo $this->form->submit('Add', array('name'=>'add_tbl_details', 'id'=>'add_tbl_details','label'=>false, 'class'=>'btn btn-info btn-sm')); ?></td>
														</tr>

														</div>
													<?php  } ?>
												</div>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

	            <div class="card-header"><h3 class="card-title">Trade Brand Label belongs to</h3></div>
	            <div class="form-horizontal">
	              	<div class="card-body p-0 m-4 rounded">
	                  <div class="row">
	                    <div class="col-sm-12">
		                  <div class="form-group row">
		                    <label for="inputEmail3" class="col-sm-4 col-form-label text-sm">Is TBLs belongs to the you?</label>
		                    <div class="col-sm-3">
							<?php
							$tbl_belongs_to_applicant_radio = $section_form_details[0]['tbl_belongs_to_applicant'];
							if($tbl_belongs_to_applicant_radio == 'yes'){
								$tbl_belongs_to_applicant_radio_yes = 'checked';
								$tbl_belongs_to_applicant_radio_no = '';
							} else if($tbl_belongs_to_applicant_radio == 'no'){
								$tbl_belongs_to_applicant_radio_yes = '';
								$tbl_belongs_to_applicant_radio_no = 'checked';
							} else {
								$tbl_belongs_to_applicant_radio_yes = '';
								$tbl_belongs_to_applicant_radio_no = '';
							}
							?>
		                      <div class="icheck-success d-inline">
		                        <input type="radio" name="tbl_belongs_to_applicant" id="tbl_belongs_to_applicant-yes" value="yes" <?php echo $tbl_belongs_to_applicant_radio_yes; ?>>
		                        <label for="tbl_belongs_to_applicant-yes">Yes
		                        </label>
		                      </div>
		                      <div class="icheck-success d-inline">
		                        <input type="radio" name="tbl_belongs_to_applicant" id="tbl_belongs_to_applicant-no" value="no" <?php echo $tbl_belongs_to_applicant_radio_no; ?>>
		                        <label for="tbl_belongs_to_applicant-no">No
		                        </label>
		                      </div>
		                      <span id="error_tbl_belongs_to_applicant" class="error invalid-feedback"></span>
		                    </div>
		                  </div>
		                </div>
	                    <div class="col-sm-6" id="hide_tbl_belongs">
              		  		<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Attached Form A-2 regarding declaration of ownership of TBLs</p>
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
									<?php if(!empty($section_form_details[0]['tbl_belongs_docs'])){ ?>
										<a id="tbl_belongs_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['tbl_belongs_docs']); ?>">Preview</a>
									<?php } ?>
								</label>
								<div class="custom-file col-sm-9">
				                  <input type="file" name="tbl_belongs_docs" class="custom-file-input" id="tbl_belongs_docs", multiple='multiple'>
			                      <label class="custom-file-label" for="customFile">Choose file</label>
			                  	  <span id="error_tbl_belongs_docs" class="error invalid-feedback"></span>
			                  	  <span id="error_type_tbl_belongs_docs" class="error invalid-feedback"></span>
			                  	  <span id="error_size_tbl_belongs_docs" class="error invalid-feedback"></span>
			                    </div>
							</div>
							<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
	                    </div>
		                <div class="col-sm-12" id="hide_tbl_consent_letter_docs">
		                    <div class="col-sm-6 d-inline-block align-top">
              		  		  <p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Name of the firm to which the proposed TBL belongs</p>
			                  <div class="form-group row">
			                    <label for="inputEmail3" class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
			                    <div class="custom-file col-sm-9">
				                  <?php echo $this->Form->control('tbl_proposed_firm', array('type'=>'text', 'id'=>'tbl_proposed_firm', 'escape'=>false, 'value'=>$section_form_details[0]['tbl_proposed_firm'], 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'Please enter name of Firm')); ?>
				                  <span id="error_tbl_proposed_firm" class="error invalid-feedback position-absolute"></span>
			                    </div>
			                  </div>
		                    </div>
		                    <div class="col-sm-5 d-inline-block">
	              		  		<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Attach consent letter of the TBL owner</p>
								<div class="form-group row">
									<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File: <span class="cRed">*</span>
										<?php if(!empty($section_form_details[0]['tbl_consent_letter_docs'])){?>
											<a id="tbl_consent_letter_docs_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['tbl_consent_letter_docs']); ?>">Preview</a>
										<?php } ?>
									</label>
									<div class="custom-file col-sm-9">
					                  <input type="file" name="tbl_consent_letter_docs" class="custom-file-input" id="tbl_consent_letter_docs", multiple='multiple'>
				                      <label class="custom-file-label" for="customFile">Choose file</label>
				                  	  <span id="error_tbl_consent_letter_docs" class="error invalid-feedback"></span>
				                  	  <span id="error_type_tbl_consent_letter_docs" class="error invalid-feedback"></span>
				                  	  <span id="error_size_tbl_consent_letter_docs" class="error invalid-feedback"></span>
				                    </div>
								</div>
								<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
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
<?php echo $this->Html->script('element/application_forms/new/ca/ca_tbl') ?>
