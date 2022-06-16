<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
	<div id="form_outer_main" class="col-md-10 form-middle">
		<h5 class="mt-1 mb-2 tacfw700">Premises Details</h5>
			<div id="form_inner_main" class="card card-success">

				<?php if($ca_bevo_applicant == 'no'){ ?>

					<div class="card-header"><h3 class="card-title">Storage Details</h3></div>
						<div class="tank_table form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Details of storage tank/ rooms for the storage of the commodity </p>
										<?php echo $this->element('ca_other_tables_elements/storage_tank_details_table_view'); ?>
									</div>
									<div class="col-md-12 mt-2">
										<div class="row">
											<div class="custom-file col-sm-6">
												<label for="field3" ><span>Site Plan No.</span></label>

												<?php echo $this->Form->control('storage_site_plan_no', array('type'=>'text', 'id'=>'storage_site_plan_no', 'value'=>$section_form_details[0]['storage_site_plan_no'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter site plan no.','class'=>'form-control')); ?>
												<div id="error_storage_site_plan_no"></div>
											</div>

											<div class="col-sm-6">
												<label for="inputEmail3">Attach File : </label>
													<?php if (!empty($section_form_details[0]['storage_details_docs'])) { ?>
														<a id="storage_details_docs_value" target="blank" href="<?php echo $section_form_details[0]['storage_details_docs']; ?>">Preview</a>
													<?php } ?>

												<?php echo $this->Form->control('storage_details_docs',array('type'=>'file', 'id'=>'storage_details_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
												<span id="error_storage_details_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 08/05/2017)-->
												<span id="error_size_storage_details_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->
												<span id="error_type_storage_details_docs" class="error invalid-feedback"></span> <!--create div field for showing error message (by pravin 09/05/2017)-->

												<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>

											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					<div class="card-header"><h3 class="card-title">Fulfillment of conditions</h3></div>
						<div class="form-horizontal">
							<div class="card-body">

								<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Whether premises fulfills the conditions stipulated for the grading of the commodity </p>
								<div class="row">
									<div class="col-sm-6">

										<label for="field3"><span>Conditions Fulfilled?</span>	</label>
											<?php
												$options=array('yes'=>'Yes','no'=>'No');
												$attributes=array('legend'=>false, 'id'=>'conditions_fulfilled', 'value'=>$section_form_details[0]['conditions_fulfilled'], 'label'=>true);
												echo $this->form->radio('conditions_fulfilled',$options,$attributes);
											?>
											<div id="error_conditions_fulfilled"></div>
									</div>
									<div class="col-sm-6">
										<div id="hide_condition_details">
											<label for="field3"><span>Details if any</span></label>
												<?php  echo $this->Form->control('condition_details', array('type'=>'textarea', 'id'=>'condition_details', 'value'=>$section_form_details[0]['condition_details'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter condition details if any','class'=>'form-control')); ?>
												<div id="error_condition_details"></div>

										</div>
										<div id="hide_conditions_fulfilled">
											<label for="inputEmail3">Attach File : </label>
												<?php if (!empty($section_form_details[0]['condition_details_docs'])) { ?>
													<a id="condition_details_docs_value" target="blank" href="<?php echo $section_form_details[0]['condition_details_docs']; ?>">Preview</a>
												<?php } ?>

											<?php echo $this->Form->control('condition_details_docs',array('type'=>'file', 'id'=>'condition_details_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control')); ?>
												<div id="error_condition_details_docs"></div>
												<div id="error_size_condition_details_docs"></div>
												<div id="error_type_condition_details_docs"></div>
											<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
										</div>
									</div>
								</div>

							</div>

						</div>

			<?php }elseif($ca_bevo_applicant == 'yes'){ ?>

				<div class="card-header"><h3 class="card-title">Constituent Oils Tanks Details</h3></div>
					<div class="tank_table form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> Details of the storage tanks with capacity of the each for different constituent oils.</p>
									<?php echo $this->element('ca_other_tables_elements/const_oil_tank_details_table_view'); ?>
								</div>
							</div>
						</div>
					</div>
				<div class="card-header"><h3 class="card-title">BEVO Tanks Details</h3></div>
					<div class="tank_table form-horizontal">
						<div class="card-body">
							<div class="col-md-12">
								<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Details of the storage tanks meant for Blended Edible Vegetable Oils. </p>
							</div>
								<?php echo $this->element('ca_other_tables_elements/bevo_oil_tank_details_table_view'); ?>
						</div>
					</div>
				<div class="card-header"><h3 class="card-title">Mill Details(Constituent Oils)</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Name and address of the Oil Mill where constituent oils shall be manufactured. </p>
								</div>

								<div class="col-md-6">
									<label for="inputEmail3">Attach File : </label>
										<?php if(!empty($section_form_details[0]['constituent_oil_mill_docs'])){?>
											<a id="constituent_oil_mill_docs_value" target="blank" href="<?php echo $section_form_details[0]['constituent_oil_mill_docs']; ?>">Preview</a>
										<?php } ?>

									<?php echo $this->Form->control('constituent_oil_mill_docs',array('type'=>'file', 'id'=>'constituent_oil_mill_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));  ?>
										<div id="error_constituent_oil_mill_docs"></div>
										<div id="error_size_constituent_oil_mill_docs"></div>
										<div id="error_type_constituent_oil_mill_docs"></div>
									<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
								</div>
							</div>

					</div>
				</div>
				<div class="card-header"><h3 class="card-title">Separate Pipe Lines?</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
									<label for="field3"><span>Whether separate pipe lines are provided for different oils and their storage tanks?</span></label>
									<?php
										$options=array('yes'=>'Yes','no'=>'No');
										$attributes=array('legend'=>false, 'id'=>'separate_pipe_lines', 'value'=>$section_form_details[0]['separate_pipe_lines'], 'label'=>true);
										echo $this->form->radio('separate_pipe_lines',$options,$attributes);
									?>
								<div id="error_separate_pipe_lines"></div>

						</div>
					</div>
				</div>


			<?php } ?>

				<div class="card-header"><h3 class="card-title">Room Details</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> of the size of the rooms where grading and marking is to be done.</p>
								</div>

									<div class="col-sm-6">
										<label><span>Site Plan No.</span>	</label>
											<?php echo $this->Form->control('room_site_plan_no', array('type'=>'text', 'id'=>'room_site_plan_no', 'value'=>$section_form_details[0]['room_site_plan_no'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter site plan no.','class'=>'form-control')); ?>
											<div id="error_room_site_plan_no"></div>

									</div>
									<div class="col-sm-6">
										<label for="inputEmail3">Attach File : </label>
											<?php if(!empty($section_form_details[0]['room_details_docs'])){ ?>
													<a id="room_details_docs_value" target="blank" href="<?php echo $section_form_details[0]['room_details_docs']; ?>">Preview</a>
											<?php } ?>

										<?php echo $this->Form->control('room_details_docs',array('type'=>'file', 'id'=>'room_details_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));  ?>
										<div id="error_room_details_docs"></div>
										<div id="error_size_room_details_docs"></div>
										<div id="error_type_room_details_docs"></div>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
									</div>
							</div>

							</div>
						</div>

						<div class="card-header"><h3 class="card-title">Ventilation/Lighting/Hygiene Details</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-sm-12">
											<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> the premises is adequately lighted, well ventilated and hygienic </p>
										</div>
										<div class="col-sm-6">
											<label for="field3"><span>Lighted,Ventilated?</span></label>
												<?php
													$options=array('yes'=>'Yes','no'=>'No');
													$attributes=array('legend'=>false, 'id'=>'lighted_ventilated', 'value'=>$section_form_details[0]['lighted_ventilated'], 'label'=>true);
													echo $this->form->radio('lighted_ventilated',$options,$attributes);
												?>
												<div id="error_lighted_ventilated"></div>


											<div id="hide_lighted_ventilated">
												<label for="inputEmail3">Attach File : </label>
												<?php if(!empty($section_form_details[0]['ventilation_details_docs'])){?>
													<a id="ventilation_details_docs_value" target="blank" href="<?php echo $section_form_details[0]['ventilation_details_docs']; ?>">Preview</a>
												<?php } ?>

												<?php echo $this->Form->control('ventilation_details_docs',array('type'=>'file', 'id'=>'ventilation_details_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));  ?>
													<div id="error_ventilation_details_docs"></div>
													<div id="error_size_ventilation_details_docs"></div>
													<div id="error_type_ventilation_details_docs"></div>

													<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
											</div>
										</div>
										<div class="col-sm-6">
											<label for="field3"><span>Details if any</span>	</label>
												<?php echo $this->Form->control('ventilation_details', array('type'=>'textarea','class'=>'form-control', 'id'=>'ventilation_details', 'value'=>$section_form_details[0]['ventilation_details'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter ventilation details if any')); ?>
												<div id="error_ventilation_details"></div>

										</div>
									</div>
								</div>
							</div>

							<div class="card-header"><h3 class="card-title">Locking Details</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-12">
												<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i> locking arrangements are adequate for storage of commodity </p>
											</div>
											<div class="col-sm-6">
												<label for="field3"><span>Adequate or not?</span></label>
													<?php
														$options=array('yes'=>'Yes','no'=>'No');
														$attributes=array('legend'=>false, 'id'=>'locking_adequate', 'value'=>$section_form_details[0]['locking_adequate'], 'label'=>true);
														echo $this->form->radio('locking_adequate',$options,$attributes);
													?>
													<div id="error_locking_adequate"></div>

												<div id="hide_locking_adequate">
													<label for="inputEmail3">Attach File : </label>
													<?php if(!empty($section_form_details[0]['locking_details_docs'])){ ?>
														<a id="locking_details_docs_value" target="blank" href="<?php echo $section_form_details[0]['locking_details_docs']; ?>">Preview</a>
													<?php } ?>

													<?php echo $this->Form->control('locking_details_docs',array('type'=>'file', 'id'=>'locking_details_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));  ?>
														<div id="error_locking_details_docs"></div>
														<div id="error_size_locking_details_docs"></div>
														<div id="error_type_locking_details_docs"></div>

													<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
												</div>
											</div>
											<div class="col-sm-6">
												<label for="field3"><span>Details if any</span>	</label>
													<?php echo $this->Form->control('locking_details', array('type'=>'textarea','class'=>'form-control', 'id'=>'locking_details', 'value'=>$section_form_details[0]['locking_details'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter locking details if any')); ?>
													<div id="error_locking_details"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

	<input type="hidden" id="ca_bevo_applicant_id" value="<?php echo $ca_bevo_applicant; ?>">
	<input type="hidden" id="final_status_id" value="<?php echo $section_status; ?>">

	<?php echo $this->Html->script('element/siteinspection_forms/new/ca/premises_details'); ?>
