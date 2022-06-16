
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
	<section class="content form-middle form_outer_class" id="form_outer_main">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
            		<div class="card card-success">
						<div class="card-header"><h3 class="card-title">Firm Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name', 'escape'=>false, 'value'=>$firm_details['firm_name'], 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('firm_email_id', array('type'=>'text', 'id'=>'firm_email_id', 'escape'=>false, 'value'=>base64_decode($firm_details['email']), 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); //for email encoding ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'id'=>'street_address', 'escape'=>false, 'value'=>$firm_details['street_address'], 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Commodities List <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
											  <?php echo $this->Form->control('types_of_sub_commodities',  array('type'=>'select', 'id'=>'types_of_sub_commodities', 'options'=>$section_form_details[1], 'multiple'=>'multiple', 'escape'=>false,'disabled'=>true, 'label'=>false, 'class'=>'form-control input-field')); ?>
											</div>
										  </div>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('state', array('type'=>'text', 'id'=>'state', 'value'=>$state_list[$firm_details['state']], 'disabled'=>true, 'label'=>false,'class'=>'form-control')); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('district', array('type'=>'text', 'id'=>'district', 'value'=>$distict_list[$firm_details['district']], 'disabled'=>true, 'label'=>false, 'class'=>'form-control')); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
												<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('firm_mobile_no', array('type'=>'text', 'id'=>'firm_mobile_no', 'escape'=>false, 'value'=>base64_decode($firm_details['mobile_no']), 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Phone No.</label>
												<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('firm_fax_no', array('type'=>'text', 'id'=>'firm_fax_no', 'escape'=>false, 'value'=>base64_decode($firm_details['fax_no']), 'class'=>'form-control input-field', 'disabled'=>true, 'label'=>false)); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-header"><h3 class="card-title">Other Details</h3></div>
						
						<div class="form-horizontal">
							<div class="card-body">
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Is the packer already issued the E-Code?</p>

										<?php 
											$options=array('yes'=>'Yes','no'=>'No');
											$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['already_granted'], 'id'=>'already_granted', 'label'=>true );
											echo $this->Form->radio('already_granted',$options,$attributes); ?>
										<div id="error_already_granted"></div>
									</div>
							</div>
						</div>
							
						<div class="form-horizontal">
							<div class="card-body">
									
								<div id="new_details_section">
									
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Is Packer have inbuilt and automatic system of control and fast speed automatic packing lines?</p>

										<?php 
											$options=array('yes'=>'Yes','no'=>'No');
											$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['auto_packing_lines'], 'id'=>'auto_packing_lines', 'label'=>true );
											echo $this->Form->radio('auto_packing_lines',$options,$attributes); ?>
										<div id="error_auto_packing_lines"></div>
									</div>
										
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Separate records should be maintained in separate sections of unit by different section in-charges as.</p>
										
										<div class="row">
											<div class="col-sm-6 d-inline-block">
												<ul>
													<li>Copies of letters placing order for replica printing</li>
													<li>Copies of printing order carried out by the printing press</li>
													<li>Stock register of empty containers (packing material)</li>
													<li>Issue register of empty containers size-wise and commodity-wise</li>
													<li>Stock register of raw material</li>
												</ul>
											</div>
											<div class="col-sm-6 d-inline-block">
												<ul>
													<li>Issue register of raw material</li>
													<li>Register showing daily production</li>
													<li>Register showing date-wise and packsize-wise damaged containers, if any (during packing)</li>
													<li>Stock register in the store room/cold storage showing daily stock</li>
													<li>Sale register/sale invoice</li>
												</ul>
											</div>

											<div class="col-sm-6 d-inline-block">
												<?php 
													$options=array('yes'=>'Yes','no'=>'No');
													$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['separate_sections_unit'], 'id'=>'separate_sections_unit', 'label'=>true );
													echo $this->Form->radio('separate_sections_unit',$options,$attributes); ?>
												<div id="error_separate_sections_unit"></div>
											</div>
										</div>
									</div>
									
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Packer shall have to grade 100% of the commodity packed in the unit.</p>
										<div class="row">
											<div class="col-sm-6 d-inline-block">
												<?php 
													$options=array('yes'=>'Yes','no'=>'No');
													$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['is_all_commo_graded'], 'id'=>'is_all_commo_graded', 'label'=>true );
													echo $this->Form->radio('is_all_commo_graded',$options,$attributes); ?>
												<div id="error_is_all_commo_graded"></div>
											</div>
										
											<div class="col-sm-6 d-inline-block">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Undertaking doc: <span class="cRed">*</span>
														<?php if(!empty($section_form_details[0]['all_commo_graded_doc'])){?>
															<a id="all_commo_graded_doc_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['all_commo_graded_doc']); ?>">Preview</a>
														<?php } ?>
													</label>
													<div class="custom-file col-sm-9">
														<input type="file" name="all_commo_graded_doc" class="custom-file-input" id="all_commo_graded_doc", multiple='multiple'>
														<label class="custom-file-label" for="customFile">Choose file</label>
														<span id="error_all_commo_graded_doc" class="error invalid-feedback"></span>
														<span id="error_size_all_commo_graded_doc" class="error invalid-feedback"></span>
														<span id="error_type_all_commo_graded_doc" class="error invalid-feedback"></span>
													</div>
												</div>
												<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
											</div>
										</div>
									</div>
									
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Is the packed graded commodity shall be kept in a store room exclusively meant for the products graded under AGMARK?</p>

										<?php 
											$options=array('yes'=>'Yes','no'=>'No');
											$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['is_commo_stored_in_room'], 'id'=>'is_commo_stored_in_room', 'label'=>true );
											echo $this->Form->radio('is_commo_stored_in_room',$options,$attributes); ?>
										<div id="error_is_commo_stored_in_room"></div>
									</div>
									
									<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Is the register duly certified by the DMI officer shall be maintained in the store room indicating the stock in the store room?</p>
									
										<div class="row">
											<div class="col-sm-6 d-inline-block">
												<?php 
													$options=array('yes'=>'Yes','no'=>'No');
													$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['is_reg_stored_in_room'], 'id'=>'is_reg_stored_in_room', 'label'=>true );
													echo $this->Form->radio('is_reg_stored_in_room',$options,$attributes); ?>
												<div id="error_is_reg_stored_in_room"></div>
											</div>
											<div class="col-sm-6 d-inline-block">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Relevant Doc: <span class="cRed">*</span>
														<?php if(!empty($section_form_details[0]['relevant_doc'])){?>
															<a id="relevant_doc_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['relevant_doc']); ?>">Preview</a>
														<?php } ?>
													</label>
													<div class="custom-file col-sm-9">
														<input type="file" name="relevant_doc" class="custom-file-input" id="relevant_doc", multiple='multiple'>
														<label class="custom-file-label" for="customFile">Choose file</label>
														<span id="error_relevant_doc" class="error invalid-feedback"></span>
														<span id="error_size_relevant_doc" class="error invalid-feedback"></span>
														<span id="error_type_relevant_doc" class="error invalid-feedback"></span>
													</div>
												</div>
												<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
											</div>
										</div>
									
									
										
									</div>
								</div>
								
							<div id="old_details_section">
							
								<div class="col-sm-12"><p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Old Certificate Details</p>							
									<div class="row">
										<div class="col-sm-6 form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Certificate No. <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('old_cert_no', array('type'=>'text', 'id'=>'old_cert_no', 'escape'=>false, 'value'=>$section_form_details[0]['old_cert_no'], 'class'=>'form-control input-field', 'label'=>false)); ?>
											</div>
											<div id="error_old_cert_no"></div>
										</div>
										<div class="col-sm-6 d-inline-block">
											<div class="form-group row">
												<label for="inputEmail3" class="col-sm-3 col-form-label">Old Certificate Doc: <span class="cRed">*</span>
													<?php if(!empty($section_form_details[0]['old_cert_doc'])){?>
														<a id="old_cert_doc_value" target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[0]['old_cert_doc']); ?>">Preview</a>
													<?php } ?>
												</label>
												<div class="custom-file col-sm-9">
													<input type="file" name="old_cert_doc" class="custom-file-input" id="old_cert_doc", multiple='multiple'>
													<label class="custom-file-label" for="customFile">Choose file</label>
													<span id="error_old_cert_doc" class="error invalid-feedback"></span>
													<span id="error_size_old_cert_doc" class="error invalid-feedback"></span>
													<span id="error_type_old_cert_doc" class="error invalid-feedback"></span>
												</div>
											</div>
											<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										</div>
									</div>
								</div>
								
								<div class="col-sm-12">
								
									<div class="row">
										<div class="col-sm-6 form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Granted E-Code <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('granted_e_code', array('type'=>'text', 'id'=>'granted_e_code', 'escape'=>false, 'value'=>$section_form_details[0]['granted_e_code'], 'class'=>'form-control input-field', 'label'=>false)); ?>
											</div>
											<div id="error_granted_e_code"></div>
										</div>
										
										<div class="col-sm-6 form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Granted On Date <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
												<?php echo $this->Form->control('granted_on', array('type'=>'text', 'id'=>'granted_on', 'escape'=>false, 'value'=>$section_form_details[0]['granted_on'], 'class'=>'form-control input-field', 'label'=>false)); ?>
											</div>
											<div id="error_granted_on"></div>
										</div>
										
									</div>
								</div>

								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-6 form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Remark <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('remark', array('type'=>'textarea', 'id'=>'remark', 'escape'=>false, 'value'=>$section_form_details[0]['remark'], 'class'=>'form-control input-field', 'label'=>false)); ?>
											</div>
											<div id="error_remark"></div>
											
										</div>
										
										
										
									</div>
								</div>
							
							<div class="clear"></div>	
							</div>
								
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</section>
<?php echo $this->Html->script('element/application_forms/e_code/application_form_js'); ?>
