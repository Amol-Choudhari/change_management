<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
<section class="content form-middle form_outer_class" id="form_outer_main">
			<!-- This condition with message was replaced here from bottom on 11-08-2017 by Amol -->
			<?php if(isset($save_result)){ $section_form_details[0]['fullfill_minimum_quantity']='no'; ?>
				<p class="fullfill_minimum_quantity"><?php echo $save_result[2]; ?>
					<span renewal_application_declaration_message="form_status_saved">
					If you want to save details anyway please click <?php if($section_form_details[0]['form_status']=='saved'){ ?>Update<?php }elseif($section_form_details[0]['form_status'] == 'referred_back'){ ?>Save<?php }else{ ?>Save<?php } ?> button again.</span>
				</p>
			<?php } ?>
			<div class="container-fluid">
				<div class="row">
			      	<div class="col-md-12">
		            	<div class="card card-success">
							<div class="card-header"><h3 class="card-title">Applicant Details</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-3 col-form-label">Applicant ID<span class="cRed">*</span></label>
														<div class="col-sm-9">
															<?php echo $this->Form->control('customer_id', array('type'=>'text', 'escape'=>false, 'value'=>$customer_id, 'class'=>'input-field form-control', 'label'=>false, 'disabled'=>true)); ?>
														</div>
													</div>
								                  <div class="form-group row">
													<label for="inputPassword3" class="col-sm-3 col-form-label">Full Address<span class="cRed">*</span></label>
													<div class="col-sm-9">
														<?php echo $this->Form->control('full_address', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['street_address'].', '.$distict_list[$firm_details['district']].', '.$state_list[$firm_details['state']].', '.$firm_details['postal_code'], 'class'=>'input-field form-control','label'=>false, 'disabled'=>true)); ?>
													</div>
												</div>
												<div class="form-group row">
												<?php if($ca_bevo_applicant == 'no'){?>
													<label for="inputPassword3" class="col-sm-3 col-form-label">Grading Labarotary<span class="cRed">*</span></label>
														<div class="col-sm-9">
															<?php echo $this->Form->control('grading_laboratory', array('type'=>'text', 'escape'=>false, 'value'=>$section_form_details[2][0]['laboratory_name'].', '.$section_form_details[2][0]['street_address'].', '.$distict_list[$section_form_details[2][0]['district']].', '.$state_list[$section_form_details[2][0]['state']].', '.$section_form_details[2][0]['postal_code'], 'class'=>'input-field form-control', 'label'=>false, 'disabled'=>true)); ?>
															<?php } ?>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputPassword3" class="col-sm-4 col-form-label">Commodities <span class="cRed">*</span></label>
															<div class="col-sm-9">
																<?php echo $this->Form->control('commodities', array('type'=>'select', 'escape'=>false, 'options'=>$section_form_details[1], 'class'=>'input-field form-control', 'multiple'=>true, 'label'=>false, 'disabled'=>true)); ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card-header"><h3 class="card-title">Renewal Details</h3></div>
										<div class="form-horizontal">
											<div class="card-body">
												<div class="col-sm-12">
													<?php if(isset($save_result)){ ?>
														<div class="table-responsive">
															<table class="table m-0 renewal_min_qty_table table-bordered">
																<tr>
																	<th class="tablehead">Your Commodity Categories</th>
																	<th class="tablehead">Minimum Quantity(in 5 years)</th>
																	<th class="tablehead">Your Total Quantity(in 5 years)</th>
																</tr>
																<?php
																$i=0;
																foreach($section_form_details[4][1] as $each_value){ ?>
																	<tr>
																		<td><?php echo $each_value['category_name']; ?></td>
																		<td><?php echo $each_value['min_quantity']; ?> qtls.</td>
																		<?php if($each_value['min_quantity']>$save_result[0][$i]){ ?>
																			<td class="cRed"><?php echo $save_result[0][$i]; ?> qtls.</td>
																		<?php }else{ ?>
																			<td class="cGreen"><?php echo $save_result[0][$i]; ?> qtls.</td>
																		<?php } ?>
																	</tr>
																<?php $i=$i+1; } ?>
															</table>
														</div>
													<?php } else { ?>
														<div class="table-responsive">
															<table class="table renewal_min_qty_table table-bordered">
																<tr>
																	<th class="tablehead">Your Commodity Categories</th>
																	<th class="tablehead">Minimum Quantity(in 5 years)</th>
																</tr>
																<?php foreach ($section_form_details[4][1] as $each_value) { ?>
																	<tr>
																		<td><?php echo $each_value['category_name']; ?></td>
																		<td><?php echo $each_value['min_quantity']; ?> qtls.</td>
																	</tr>
																<?php } ?>
															</table>
														</div>
														<?php } ?>
														<span>Whether applicant fulfill the minimum Quantity(in Quintal) during last validity period(05 Years)?</span>
														<?php
															$options=array('yes'=>'Yes','no'=>'No');
															$attributes=array('legend'=>false, 'value'=>$section_form_details[0]['fullfill_minimum_quantity'], 'id'=>'fullfill_minimum_quantity', 'label'=>true);
															echo $this->form->radio('fullfill_minimum_quantity',$options,$attributes); ?>
															<div id="error_fullfill_minimum_quantity"></div>
															<div id="hide_quantity_table" class="renewal_commodity_table">
																<div class="table-responsive">
																	<table class="table m-0 renewal_min_qty_table table-bordered">
																		<tr>
																			<th class="tablehead">Commodity</th>
																			<th class="tablehead">Financial Year</th>
																			<th class="tablehead">Quantity Graded(In Qtls.)</th>
																		</tr>

																		<?php

																		if (isset($save_result)) {
																			$quantity_graded = $save_result[3];
																		} else {
																			$quantity_graded = $section_form_details[3][1];
																		}

																		$i=0;
																		$q=0;
																		foreach ($section_form_details[1] as $each_commodity) { ?>
																			<tr>
																				<td><?php echo $this->Form->control('commodity_name', array('type'=>'text', 'name'=>'commodity_name'.$i, 'escape'=>false, 'value'=>$each_commodity, 'class'=>'input-field form-control', 'readonly'=>true, 'label'=>false)); ?></td>
																				<td>
																					<table class="table table-bordered">
																						<?php
																						$y=0;
																						foreach ($section_form_details[3][0] as $each_year) { ?>
																							<tr>
																								<td><?php echo $this->Form->control('year', array('type'=>'text', 'name'=>'year'.$i.$y, 'escape'=>false, 'value'=>$each_year, 'class'=>'input-field', 'readonly'=>true, 'label'=>false)); ?></td>
																							</tr>
																						<?php $y=$y+1; } ?>
																					</table>
																				</td>
																				<td>
																					<table id="quantity_graded_table" class="table">
																						<?php
																						$y=0;
																						$qty_total = null;
																						foreach ($section_form_details[3][0] as $each_year) {?>
																							<tr>
																								<td><?php echo $this->Form->control('quantity_graded', array('type'=>'text', 'id'=>'quantity_graded'.$i.$y, 'value'=>$quantity_graded[$q], 'name'=>'quantity_graded'.$i.$y, 'escape'=>false, 'class'=>'quantity_graded', 'label'=>false, 'onfocusout'=>'check_quantity(id);return false')); ?></td>
																							</tr>
																						<?php
																						$qty_total = $quantity_graded[$q]+$qty_total;

																						$y=$y+1; $q=$q+1;} ?>
																						<div id="error_quantity_graded"></div>
																					</table>
																					<p class="mZero">Total:<span class="spanFWB" id="qty_total<?php echo $i; ?>" ><?php echo $qty_total; ?> Qtls.</span></p>
																				</td>
																			</tr>
																			<?php ?>
																		<?php $i=$i+1; } ?>
																	</table>
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



	<!-- Call element of declaration message box before E-Sign of any application by pravin 10-08-2017 -->
	<div class="form-style-3">
		<?php  //echo $this->element('esign_views/renewal_application_declaration_message'); ?>
	</div>

<?php $i = 0; ?>

<input type="hidden" id="commodity_loop" value="<?php echo $section_form_details[1]; ?>">
<input type="hidden" id="year_loop" value="<?php echo $section_form_details[3][0]; ?>">
<input type="hidden" id="final_submit_status" value="<?php echo $final_submit_status; ?>">
<?php echo $this->Html->script('element/application_forms/renewal/ca/ca_renewal'); ?>
