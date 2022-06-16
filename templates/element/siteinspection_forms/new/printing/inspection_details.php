<?php echo $this->Form->create(null, array('type'=>'file', 'id'=>'siteinspection_report', 'enctype'=>'multipart/form-data')); ?>
<h3 class="card-title-new">Printing Firm Site Inspection Report</h3>
	<div id="form_outer_main" class="col-md-12 form-middle">
		<div id="form_inner_main" class="card card-success">

			<div class="card-header"><h3 class="card-title">Director/Partner/Proprietor/Owner Details</h3></div>
				<div class="form-horizontal">
					<div class="card-body">
						<div class="tank_table">
							<?php echo $this->element('old_applications_elements/old_app_directors_details_table_view'); ?>
						</div>
					</div>
				</div>

				<div class="card-header"><h3 class="card-title">Assessed Purpose</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Is Assessed for the purpose of Income Tax, Sales Tax etc.?</p>
									<label for="field3">
										<?php
											$options=array('yes'=>'Yes','no'=>'No');
											$attributes=array('legend'=>false, 'id'=>'is_assessed_for', 'value'=>$section_form_details[1][0]['have_vat_cst_no'], 'label'=>true,'disabled'=>'disabled');
											echo $this->form->radio('is_assessed_for',$options,$attributes);
										?>
									</label>
								</div>
								<div class="col-md-6">
									<div id="hide_is_assessed_for">
										<div>
											<label for="field3"><span>GST NO.<span class="cRed">*</span></span>
											<?php echo $this->Form->control('assessed_for_gst_no', array('type'=>'text', 'value'=>$section_form_details[1][0]['gst_no'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
											</label>																			<!--Change variable name (by pravin 23/05/2017)-->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="card-header"><h3 class="card-title">Earlier Permitted</h3></div>
						<div class="form-horizontal">
							<div class="card-body mar10">
								<div class="row">
									<div class="col-md-6">
										<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Is the premises earlier permitted for Agmark replica?</p>
										<label for="field3">
											<?php
												$options=array('yes'=>'Yes','no'=>'No');
												$attributes=array('legend'=>false, 'id'=>'earlier_permitted', 'value'=>$section_form_details[2][0]['earlier_approved'], 'label'=>true,'disabled'=>'disabled');
												echo $this->form->radio('earlier_permitted',$options,$attributes);
											?>
										</label>
									</div>
									<div class="col-md-6" id="hide_earlier_permitted">
										<label for="field3" class="col-md-6"><p><span>Reason Of Withdrawal</span></p></label>
										<div class="col-md-8">
											<?php echo $this->Form->control('reason_of_withdrawal', array('type'=>'textarea', 'id'=>'reason_of_withdrawal', 'value'=>$section_form_details[0]['reason_of_withdrawal'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter reason of withdrawal','class'=>'form-control')); ?>
										</div>
										<div id="error_earlier_permitted"></div> <!--create div field for showing error message (by pravin 11/05/2017)-->
										<div id="error_reason_of_withdrawal"></div> <!--create div field for showing error message (by pravin 07/07/2017)-->
									</div>
								</div>
							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Are Machines Requisite?</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i>Whether the printing press is having the requisite machinery for printing of Agmark replica?</p>
											<label for="field3"><p><span></span></p>
												<?php
													$options=array('yes'=>'Yes','no'=>'No');
													$attributes=array('legend'=>false, 'id'=>'machines_requisite', 'value'=>$section_form_details[0]['machines_requisite'], 'label'=>true);
													echo $this->form->radio('machines_requisite',$options,$attributes);
												?>
											</label>
											<div id="error_machines_requisite"></div> <!--create div field for showing error message (by pravin 11/05/2017)-->
										</div>

										<!--Add By pravin 23/05/2017-->
										<div class="col-md-6">
											<label for="field3"><p><span>Give Details</span></p>
												<?php echo $this->Form->control('machines_requisite_details', array('type'=>'textarea', 'id'=>'machines_requisite_details', 'value'=>$section_form_details[0]['machines_requisite_details'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Give details with number and capacity','class'=>'form-control')); ?>
											</label>
											<div id="error_machines_requisite_details"></div> <!--create div field for showing error message (by pravin 11/05/2017)-->
										</div>

										<div class="col-sm-6" id="are_machines_requisite">
                  							<div class="form-group row">
					                   	 		<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
													<?php if(!empty($section_form_details[0]['machines_requisite_docs'])){?>
															<a target="blank" id="machines_requisite_docs_value" href="<?php echo $section_form_details[0]['machines_requisite_docs']; ?>">Preview</a>
													<?php } ?>
					                    		</label>
												<div class="custom-file col-sm-9">
													<?php  echo $this->Form->control('machines_requisite_docs', array('type'=>'file', 'id'=>'machines_requisite_docs','multiple'=>'multiple', 'label'=>false, 'class'=>'form-control'));  ?>
													<span id="error_machines_requisite_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
													<span id="error_size_machines_requisite_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
													<span id="error_type_machines_requisite_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
												</div>
                  							</div>
                  							<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
              					  		</div>
									</div>
								</div>
							</div>

							<div class="card-header"><h3 class="card-title">In House Storage Facility</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-12">
												<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether proper In house storage facilities exists?</p>
											</div>
											<div class="col-md-7">
												<label for="field3"><p><span></span></p>
													<?php
														$options=array('yes'=>'Yes','no'=>'No');
														$attributes=array('legend'=>false, 'id'=>'in_house_storage_facility', 'value'=>$section_form_details[0]['in_house_storage_facility'], 'label'=>true);
														echo $this->form->radio('in_house_storage_facility',$options,$attributes);
														?>
													</label>
												<div id="error_in_house_storage_facility"></div> <!--create div field for showing error message (by pravin 11/05/2017)-->
											</div>
										</div>
									</div>
								</div>

								<div class="card-header"><h3 class="card-title">Is Account Maintained?</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-12">
													<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether printing press maintain account for printing orders received?</p>
												</div>
												<div class="col-md-7">
													<label for="field3" class="col-md-6">
														<?php
															$options=array('yes'=>'Yes','no'=>'No');
															$attributes=array('legend'=>false, 'id'=>'account_maintained', 'value'=>$section_form_details[0]['account_maintained'], 'label'=>true);
															echo $this->form->radio('account_maintained',$options,$attributes);
														?>
													</label>
												<div id="error_account_maintained"></div> <!--create div field for showing error message (by pravin 11/05/2017)-->
												</div>
											</div>
										</div>
									</div>

								<div class="card-header"><h3 class="card-title">Fabrication Facilities(for tin containers)</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-md-6">
													<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether fabrication facilities available?</p>
													<label for="field3">
													<?php
														// add new radio button value (by pravin 31/10/2017)
														$options=array('yes'=>'Yes','no'=>'No','n/a'=>'Not Applicable');
														$attributes=array('legend'=>false, 'id'=>'fabrication_facility', 'value'=>$section_form_details[0]['fabrication_facility'], 'label'=>true);
														echo $this->form->radio('fabrication_facility',$options,$attributes);
														?>
													</label>
												</div>
												<div class="col-sm-6"  id="hide_fabrication_facility">
							                  		<div class="form-group row">
							                   	 		<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
															<?php if(!empty($section_form_details[0]['fabrication_facility_docs'])){?>
																<a target="blank" id="fabrication_facility_docs_value" href="<?php echo $section_form_details[0]['fabrication_facility_docs']; ?>">Preview</a>
															<?php } ?>
							                    		</label>
														<div class="custom-file col-sm-9">
															<?php echo $this->Form->control('fabrication_facility_docs',array('type'=>'file', 'id'=>'fabrication_facility_docs',   'multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));  ?>
															<span id="error_fabrication_facility_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
															<span id="error_size_fabrication_facility_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
															<span id="error_type_fabrication_facility_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
														</div>
							                  		</div>
                 					 			<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
                							</div>
										</div>
									</div>
								</div>

								<div class="card-header"><h3 class="card-title">Given Declaration?</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-md-6">
													<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether the press has given declaration of right quality ink and use of food grade material?</p>
														<label for="field3">
															<?php
																$options=array('yes'=>'Yes','no'=>'No');
																$attributes=array('legend'=>false, 'id'=>'declaration_given', 'value'=>$section_form_details[0]['declaration_given'], 'label'=>true);
																echo $this->form->radio('declaration_given',$options,$attributes);
															?>
														</label>
													<div id="error_declaration_given"></div> <!--create div field for showing error message (by pravin 11/05/2017)-->
												</div>
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
															<?php if(!empty($section_form_details[0]['ink_declaration_docs'])){?>
																	<a target="blank" id="ink_declaration_docs_value" href="<?php echo $section_form_details[0]['ink_declaration_docs']; ?>">Preview</a>
															<?php } ?>
														</label>
														<div class="custom-file col-sm-9">
															<?php echo $this->Form->control('ink_declaration_docs',array('type'=>'file', 'id'=>'ink_declaration_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));  ?>
															<span id="error_ink_declaration_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
															<span id="error_size_ink_declaration_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
															<span id="error_type_ink_declaration_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
														</div>
			  	                					</div>
             									 	<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
                								</div>
											</div>
										</div>
									</div>

									<div class="card-header"><h3 class="card-title">Is Press Sponsored?</h3></div>
										<div class="form-horizontal">
											<div class="card-body">
												<div class="row">
													<div class="col-md-6">
														<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether the press has sponsored by the authorized packers?</p>
														<label for="field3">
															<?php
																$options=array('yes'=>'Yes','no'=>'No');
																$attributes=array('legend'=>false, 'id'=>'is_press_sponsored', 'value'=>$section_form_details[0]['is_press_sponsored'], 'label'=>true);
																echo $this->form->radio('is_press_sponsored',$options,$attributes);
															?>
														</label>
													</div>
													<div class="col-md-6" id="hide_press_authorised">
														<p class="bg-info pl-2 p-1 rounded"><i class="fa fa-info-circle"></i> Whether the press is owned by any Authorised/ Packer’s Printing unit?</p>
														<label class="float-left">
															<?php
																$options=array('yes'=>'Yes','no'=>'No');
																$attributes=array('legend'=>false, 'id'=>'is_press_authorised', 'value'=>$section_form_details[0]['is_press_authorised'], 'label'=>true);
																echo $this->form->radio('is_press_authorised',$options,$attributes);
															?>
														</label>
													</div>
													<div class="col-sm-6" id="hide_press_sponsored">
														<div class="form-group row">
															<label for="inputEmail3" class="col-sm-3 col-form-label">Attach File :
																<?php if(!empty($section_form_details[0]['press_sponsored_docs'])){?>
																<a target="blank" id="press_sponsored_docs_value" href="<?php echo $section_form_details[0]['press_sponsored_docs']; ?>">Preview</a>
																<?php } ?>
															</label>
															<div class="custom-file col-sm-9">
																<?php echo $this->Form->control('press_sponsored_docs',array('type'=>'file', 'id'=>'press_sponsored_docs','multiple'=>'multiple', 'label'=>false, 'class'=>'form-control'));  ?>
																<span id="error_press_sponsored_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
																<span id="error_size_press_sponsored_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
																<span id="error_type_press_sponsored_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
															</div>
					                  					</div>
					                  					<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
					                				</div>
												</div>
											</div>
										</div>

										<div class="card-header"><h3 class="card-title">Remarks</h3></div>
											<div class="form-horizontal">
												<div class="card-body">
													<div class="row mb-2">
														<div class="col-md-3">
															<label for="field3"><p><span>Remarks, if any</span></p></label>
														</div>
														<div class="col-md-6">
															<?php echo $this->Form->control('any_other_point', array('type'=>'textarea', 'id'=>'any_other_point', 'value'=>$section_form_details[0]['any_other_point'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter your points here','class'=>'form-control')); ?>
																<div id="error_any_other_point"></div> <!--create div field for showing error message (by pravin 07-07-2017)-->
														</div>
													</div>
												</div>
											</div>

											<div class="card-header"><h3 class="card-title">Recommendations</h3></div>
												<div class="form-horizontal">
													<div class="card-body">
														<div class="row mb-2">
															<div class="col-md-3">
																<label for="field3"><p><span>Given recommendations</span></p></label>
															</div>
															<div class="col-md-6">
																<?php  echo $this->Form->control('recommendations', array('type'=>'textarea', 'id'=>'recommendations', 'value'=>$section_form_details[0]['recommendations'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter Recommendations','class'=>'form-control')); ?>
																<div id="error_recommendations"></div> <!--create div field for showing error message (by pravin 07-07-2017)-->
															</div>
														</div>
													</div>
												</div>
		</div>
</div>

<input type="hidden" id="final_status_id" value="<?php echo $section_status; ?>">
<?php echo $this->Html->script('element/siteinspection_forms/new/printing/inspection_details'); ?>
