<?php $firm_sub_commodity = explode(',',$firm_details['sub_commodity']); ?>

	<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
		<div id="form_outer_main" class="col-md-10 form-middle">
			<h5 class="mt-1 mb-2 tacfw700">Other Details</h5>
	 			<div id="form_inner_main" class="card card-success">
	 				<div class="card-header"><h3 class="card-title">Trade Brand Label Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body mb-3">
								<div class="tank_table">
									<table class="table m-0 table-bordered table-striped">
										<thead class="tablehead">
											<th>TBL Name</th>
											<th>Registered?</th>
											<th>Reg. No.</th>
											<th>Uploaded File</th>
										</thead>
										<tbody>
											<?php
												$i=1;

												foreach ($section_form_details[2][0] as $each_tbl) { ?>
													<tr>
														<td><?php  echo $each_tbl['tbl_name']; ?></td>
														<td><?php  echo $each_tbl['tbl_registered']; ?></td>
														<td><?php
																if ($each_tbl['tbl_registered']=='yes') {

																		echo $each_tbl['tbl_registered_no'];
																} else {
																	echo "---";
																}
															?>
														</td>
														<td><?php if ($each_tbl['tbl_registration_docs'] != null) { ?>
																	<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$each_tbl['tbl_registration_docs']); ?>">Preview</a>
															  <?php } else { echo "No File Attached"; }  ?></td>
													</tr>
												<?php  $i=$i+1; } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>

					<?php if ($ca_bevo_applicant == 'no') { ?>

						<div class="card-header"><h3 class="card-title">Other Details</h3></div>
							<div class="form-horizontal">
								<div class="card-body mb-2">
									<div class="row">
										<div class="col-md-12"><p class="bg-info pl-2 p-1 mt-2 rounded text-sm"><i class="fa fa-info-circle"></i>Quantity of the commodity proposed to be graded under Agmark per month(in Quintal)</p></div>
										<label for="field3" class="col-md-3"><span>Total Quantity </span></label>
										<?php echo $this->Form->control('commodity_quantity', array('type'=>'text', 'id'=>'commodity_quantity', 'value'=>$section_form_details[0]['commodity_quantity'], 'escape'=>false,'class'=>'form-control' ,'label'=>false, 'placeholder'=>'Enter commodity quantity')); ?>
										<div id="error_commodity_quantity"></div>

										<div class="col-md-12 mt-3"><p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Arrangement made for the analysis of the proposed commodity/ies</p></div>
										<label for="field3" class="col-md-3"><span>Laboratory Type </span></label>
										<?php echo $this->Form->control('laboratory_type', array('type'=>'text', 'id'=>'laboratory_type', 'value'=>$section_form_details[3][$section_form_details[1][0]['laboratory_type']], 'escape'=>false,'class'=>'form-control', 'label'=>false, 'disabled'=>true)); ?>

										<div class="col-md-12">
											<label for="inputEmail3">Attached Consent Letter: </label>
													<?php if (!empty($section_form_details[1][0]['consent_letter_docs'])) { ?>
															<a id="consent_letter_docs_value" target="blank" href="<?php echo $section_form_details[1][0]['consent_letter_docs']; ?>">Preview</a>
													<?php }else{ echo "No Document Provided" ;} ?>
										</div>
									</div>
								</div>
								</div>

								<div class="card-header"><h3 class="card-title">Machinery Details</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-md-12"><p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Details of the machinery for the processing of the commodity. Processing done through own machinery. </p>
													<div class="row">
														<div class="col-sm-6">
															<label for="field3"><span>Own Machinery?</span></label>
															<?php
																$options=array('yes'=>'Yes','no'=>'No');
																$attributes=array('legend'=>false, 'id'=>'own_machinery', 'value'=>$section_form_details[0]['own_machinery'], 'label'=>true);
																echo $this->form->radio('own_machinery',$options,$attributes);
															?>
															<div id="error_own_machinery"></div>
														</div>

														<div class="col-sm-6">
															<div id="hide_own_machinery">
																<label for="field3"><span>Processing Done By </span></label>
																	<?php echo $this->Form->control('processing_done_by', array('type'=>'text','class'=>'form-control', 'id'=>'processing_done_by', 'value'=>$section_form_details[0]['processing_done_by'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter by whome processing is done')); ?>
																	<div id="error_processing_done_by"></div>

															</div>

															<label for="inputEmail3">Attach File : </label>
															<?php if (!empty($section_form_details[0]['machinery_processing_docs'])) { ?>
																	<a id="machinery_processing_docs_value" target="blank" href="<?php echo $section_form_details[0]['machinery_processing_docs']; ?>">Preview</a>
															<?php } ?>

															<?php echo $this->Form->control('machinery_processing_docs',array('type'=>'file', 'class'=>'form-control', 'id'=>'machinery_processing_docs','multiple'=>'multiple','class'=>'form-control', 'label'=>false));  ?>
															<div id="error_machinery_processing_docs"></div>
															<div id="error_size_machinery_processing_docs"></div>
															<div id="error_type_machinery_processing_docs"></div>
															<p class="lab_form_note"><i class="fa fa-info-circle"></i>File type: pdf,jpg & Max-size:2mb</p>
														</div>
													</div>
													</div>
												</div>
											</div>
										</div>

					<?php }elseif ($ca_bevo_applicant == 'yes') { ?>

						<div class="card-header"><h3 class="card-title">Firm/Oil Mills Details(Constituent Oil)</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Name and address of the firms/oil mills from which constituent oil will be procured with approximate quantity thereof.</p>
										<div class="const_oils_table">
											<!-- call table view form element with ajax call -->
											<?php echo $this->element('ca_other_tables_elements/const_oil_mill_details_table_view'); ?>
										</div>
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Declaration from constituents oil suppliers need to be uploaded</p>

									<div class="col-sm-6">
										<label for="inputEmail3">Attach File : </label>
											<?php if (!empty($section_form_details[0]['constituent_oil_suppliers_docs'])) { ?>
													<a id="constituent_oil_suppliers_docs_value" target="blank" href="<?php echo $section_form_details[0]['constituent_oil_suppliers_docs']; ?>">Preview</a>
											<?php } ?>

									<?php echo $this->Form->control('constituent_oil_suppliers_docs',array('type'=>'file', 'id'=>'constituent_oil_suppliers_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));  ?>
										<div id="error_constituent_oil_suppliers_docs"></div>
										<div id="error_size_constituent_oil_suppliers_docs"></div>
										<div id="error_type_constituent_oil_suppliers_docs"></div>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i>File type: pdf,jpg & Max-size:2mb</p>
									</div>
								</div>
							</div>


				<!-- Hide and show the "Machinery details" and "Minimum Infrastructure/Facilities" box on selected sub commodity wise
				    Done by pravin 10-01-2018 -->
				<?php if (in_array('172',$firm_sub_commodity)) { ?>

					<div class="card-header"><h3 class="card-title">Machinery Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Details of the machinery available in the Oil Mill in case of Blended Edible Vegetable Oil.</p>
									<div class="col-sm-6">
										<label for="inputEmail3">Attach File : </label>
											<?php if (!empty($section_form_details[0]['bevo_machinery_details_docs'])) { ?>
													<a id="bevo_machinery_details_docs_value" target="blank" href="<?php echo $section_form_details[0]['bevo_machinery_details_docs']; ?>">Preview</a>
											<?php } ?>

										<?php echo $this->Form->control('bevo_machinery_details_docs',array('type'=>'file', 'id'=>'bevo_machinery_details_docs','multiple'=>'multiple', 'label'=>false,'class'=>'form-control'));  ?>
											<div id="error_bevo_machinery_details_docs"></div>
											<div id="error_size_bevo_machinery_details_docs"></div>
											<div id="error_type_bevo_machinery_details_docs"></div>
										<p class="lab_form_note float-right"><i class="fa fa-info-circle"></i>File type: pdf,jpg & Max-size:2mb</p>
									</div>
								</div>
							</div>
				<?php } ?>


				<?php if (in_array('173',$firm_sub_commodity)) { ?>

					<div class="card-header"><h3 class="card-title">Minimum Infrastructure/Facilities</h3></div>
						<div class="form-horizontal">
							<div class="card-body">
								<label for="field3"><span>Whether minimum infrastructure/facilities are available in the plant as required in case of Fat Spread.?</span></label>
								<div>
								<?php
										$options=array('yes'=>'Yes','no'=>'No');
										$attributes=array('legend'=>false, 'id'=>'fat_spread_facilitities', 'value'=>$section_form_details[0]['fat_spread_facilitities'], 'label'=>true);
										echo $this->form->radio('fat_spread_facilitities',$options,$attributes);
									?>
									<div id="error_fat_spread_facilitities"></div>
								</div>

							</div>
						</div>

				<?php } ?>


				<div class="card-header"><h3 class="card-title">Quantity Per Month</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group ">
										<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Quantity of Blended Edible Vegetable Oil proposed to be graded per month in MTs.</p>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group ">
										<label for="field3"><span>Quantity (in MTs)</span></label>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group 	">
										<?php echo $this->Form->control('bevo_quantity_per_month', array('type'=>'text', 'id'=>'bevo_quantity_per_month', 'value'=>$section_form_details[0]['bevo_quantity_per_month'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Please Enter Quantity here','class'=>'form-control')); ?>
									<div id="error_bevo_quantity_per_month"></div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card-header"><h3 class="card-title">Marketed Places</h3></div>
					<div class="form-horizontal">
						<div class="card-body mb-3">
							<div class="row">
								<div class="col-md-12">
									<p class="bg-info pl-2 p-1 rounded text-sm"><i class="fa fa-info-circle"></i>Important places where graded Blended Edible Vegetable Oils is proposed to be marketed.</p>
								</div>
								<div class="col-md-6">
									<label for="field3"><span>Details</span></label>

									<?php  echo $this->Form->control('graded_bevo_marketed_places', array('type'=>'textarea', 'id'=>'graded_bevo_marketed_places', 'value'=>$section_form_details[0]['graded_bevo_marketed_places'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Please Enter places details','class'=>'form-control')); ?>
									<div id="error_graded_bevo_marketed_places"></div>
								</div>
							</div>
						</div>
					</div>

			<?php } ?>

			<div class="card-header"><h3 class="card-title">Remarks and Recommendations</h3></div>
				<div class="form-horizontal">
					<div class="card-body mb-3">
						<div class="row">
							<div class="col-md-6">
								<label for="field3"><span>Remarks, if any</span></label>
								<?php echo $this->Form->control('other_points', array('type'=>'textarea', 'id'=>'other_points', 'value'=>$section_form_details[0]['other_points'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter Details','class'=>'form-control')); ?>
									<div id="error_other_points"></div>
							</div>

							<div class="col-md-6">
								<label for="field3"><span>Give Recommendations </span></label>

								<?php echo $this->Form->control('recommendations', array('type'=>'textarea', 'id'=>'recommendations', 'value'=>$section_form_details[0]['recommendations'], 'escape'=>false, 'label'=>false, 'placeholder'=>'Enter Details','class'=>'form-control')); ?>
								<div id="error_recommendations"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<input type="hidden" id="ca_bevo_applicant_id" value="<?php echo $ca_bevo_applicant; ?>">
<input type="hidden" id="final_status_id" value="<?php echo $section_status; ?>">
<input type="hidden" id="firm_sub_commodity_id" value="<?php echo json_encode($firm_sub_commodity); ?>">

<?php echo $this->Html->script('element/siteinspection_forms/new/ca/other_details'); ?>
