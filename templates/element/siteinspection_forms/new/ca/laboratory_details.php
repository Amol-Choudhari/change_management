
	<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

		<div id="form_outer_main" class="col-md-10 form-middle">
			<h5 class="mt-1 mb-2 tacfw700">Laboratory Details</h5>
				<div id="form_inner_main" class="card card-success">

				<?php if ($ca_bevo_applicant == 'no') { ?>

				<div class="card-header"><h3 class="card-title">Laboratory Address</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-md-3">
									<label for="field3"><span>Address <span class="cRed">*</span></span>
									<?php echo $this->Form->control('street_address', array('type'=>'text', 'escape'=>false, 'value'=>$section_form_details[1][0]['street_address'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
									</label>
								</div>
								<div class="col-md-3">
									<label for="field3"><span>State/Region <span class="cRed">*</span></span>
									<?php echo $this->Form->control('state', array('type'=>'text', 'escape'=>false, 'value'=>$state_list[$section_form_details[1][0]['state']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
									</label>
								</div>
								<div class="col-md-3">
									<label for="field3"><span>District <span class="cRed">*</span></span>
									<?php echo $this->Form->control('district', array('type'=>'text', 'escape'=>false, 'value'=>$distict_list[$section_form_details[1][0]['district']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
									</label>
								</div>
								<div class="col-md-3">
									<label for="field3"><span>Pin Code <span class="cRed">*</span></span>
									<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'value'=>$section_form_details[1][0]['postal_code'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="card-header"><h3 class="card-title">Contact Info</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<label for="field3"><span>Email Id <span class="cRed">*</span></span>
										<?php echo $this->Form->control('lab_email_id', array('type'=>'text', 'escape'=>false, 'value'=>base64_decode($section_form_details[1][0]['lab_email_id']), 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); //for email encoding ?>
										</label>
									</div>
									<div class="col-md-4">
										<label for="field3"><span>Mobile No. <span class="cRed">*</span></span>
										<?php echo $this->Form->control('lab_mobile_no', array('type'=>'text', 'escape'=>false, 'value'=>base64_decode($section_form_details[1][0]['lab_mobile_no']), 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</label>
									</div>
									<div class="col-md-4">
										<label for="field3"><span>Phone No. <span class="cRed">*</span></span>
										<?php echo $this->Form->control('lab_fax_no', array('type'=>'text', 'escape'=>false, 'value'=>base64_decode($section_form_details[1][0]['lab_fax_no']), 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="card-header"><h3 class="card-title">Properly Equipped?</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>If own laboratory, whether it is properly equipped for analysis of samples of the commodity proposed to be graded. </p>

									<div class="row">
										<div class="col-sm-6">
											<label for="field3"><span>Properly Equipped?</span></label>
												<?php
													//added new option NA in radio options as per UAT suggestion
    												//on 17-08-2022 
													$options=array('yes'=>'Yes','no'=>'No','n/a'=>'NA');
													$attributes=array('legend'=>false, 'id'=>'laboratory_equipped', 'value'=>$section_form_details[0]['laboratory_equipped'], 'label'=>true);
													echo $this->form->radio('laboratory_equipped',$options,$attributes);
												?>
												<div id="error_laboratory_equipped"></div>
										</div>

										<div class="col-sm-6">
											<div id="hide_not_equipped">
												<label for="field3"><span>Provide Shortcomings</span>	</label>
													<?php echo $this->Form->control('lab_shortcomings', array('type'=>'textarea', 'id'=>'lab_shortcomings','class'=>'form-control' ,'value'=>$section_form_details[0]['lab_shortcomings'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter Shortcomings Details')); ?>
													<div id="error_lab_shortcomings"></div>

											</div>

											<div id="hide_laboratory_equipped">
												<label for="field3"><span>Document Ref. No.</span>	</label>
													<?php echo $this->Form->control('lab_doc_ref_no', array('type'=>'text', 'id'=>'lab_doc_ref_no','class'=>'form-control', 'value'=>$section_form_details[0]['lab_doc_ref_no'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter document reference no.')); ?>
													<div id="error_lab_doc_ref_no"></div>


												<label for="inputEmail3">Attach File : </label>
												<?php if (!empty($section_form_details[0]['laboratory_equipped_docs'])) { ?>
													<a id="laboratory_equipped_docs_value" target="blank" href="<?php echo $section_form_details[0]['laboratory_equipped_docs']; ?>">Preview</a>
												<?php } ?>

												<?php echo $this->Form->control('laboratory_equipped_docs',array('type'=>'file', 'id'=>'laboratory_equipped_docs','multiple'=>'multiple','class'=>'form-control', 'label'=>false));  ?>
													<div id="error_laboratory_equipped_docs"></div>
													<div id="error_size_laboratory_equipped_docs"></div>
													<div id="error_type_laboratory_equipped_docs"></div>
													<p class="lab_form_note"><i class="fa fa-info-circle"></i>File type: pdf,jpg & Max-size:2mb</p>
												</div>
											</div>
										</div>
									</div>
								</div>



			<?php } elseif ($ca_bevo_applicant == 'yes') { ?>

				<div class="card-header"><h3 class="card-title">Properly Equipped?</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Whether the laboratory is fully equipped for the analysis of constituent oils and blended edible vegetable oil/Fat Spread</p>
									<div class="row">
										<div class="col-md-6">
											<label for="field3"><span>Fully Equipped?</span></label>
											<?php
												$options=array('yes'=>'Yes','no'=>'No');
												$attributes=array('legend'=>false, 'id'=>'laboratory_equipped', 'value'=>$section_form_details[0]['laboratory_equipped'], 'label'=>true);
												echo $this->form->radio('laboratory_equipped',$options,$attributes);
											?>
											<div id="error_laboratory_equipped"></div>
										</div>

										<div class="col-sm-6">
											<div id="hide_not_equipped">
												<label for="field3"><span>Provide Shortcomings</span>	</label>
												<?php echo $this->Form->control('lab_shortcomings', array('type'=>'textarea', 'id'=>'lab_shortcomings', 'value'=>$section_form_details[0]['lab_shortcomings'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter Shortcomings Details','class'=>'form-control')); ?>
												<div id="error_lab_shortcomings"></div>
											</div>
										</div>

										<div class="col-sm-6">
											<div id="hide_laboratory_equipped">
												<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> List of chemicals and apparatus duly signed by the inspecting officer may be attached</p>
												<label for="inputEmail3">Attach File : </label>
												<?php if (!empty($section_form_details[0]['laboratory_equipped_docs'])) { ?>
													<a id="laboratory_equipped_docs_value" target="blank" href="<?php echo $section_form_details[0]['laboratory_equipped_docs']; ?>">Preview</a>
												<?php } ?>

												<?php echo $this->Form->control('laboratory_equipped_docs',array('type'=>'file', 'id'=>'laboratory_equipped_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));   ?>
												<div id="error_laboratory_equipped_docs"></div>
												<div id="error_size_laboratory_equipped_docs"></div>
												<div id="error_type_laboratory_equipped_docs"></div>
												<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: pdf,jpg & Max-size:2mb</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		
<input type="hidden" id="ca_bevo_applicant_id" value="<?php echo $ca_bevo_applicant; ?>">
<input type="hidden" id="final_status_id" value="<?php echo $section_status; ?>">

<?php echo $this->Html->script('element/siteinspection_forms/new/ca/laboratory_details'); ?>
