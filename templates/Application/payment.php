<?php ?>

	<div class="col-md-10">
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="card card-success">
					<div class="card-header"><h3 class="card-title-new">Payment</h3></div>
					<div class="form-horizontal">
						<div class="card-body  p-0 m-2 rounded">
							<?php if ($application_type != 2) { ?>
								<div class="row">
									<table class="table table-striped table-hover table-bordered table-primary">
										<thead class="tablehead">
											<tr>
												<th>Sr. No.</th>
												<?php if (!empty($firm_details['sub_commodity'])) { ?>
													<th>Category</th>
													<th>Commodities</th>
												<?php } elseif (!empty($firm_details['packaging_materials'])) { ?>
													<th>Selected Packaging Material</th>
												<?php } ?>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($firm_details['sub_commodity'])) {
													$i=1;
													foreach ($commodity_name_list as $commodity_name) { ?>
														<tr>
															<td><?php echo $i; ?></td>
															<td><?php echo $commodity_name['category_name']; ?></td>
															<td><ul><?php foreach ($sub_commodity_data as $sub_commodity) { ?>
																		<?php if ($sub_commodity['category_code'] == $commodity_name['category_code']) { ?>
																			<li><?php echo $sub_commodity['commodity_name']; ?></li>
																		<?php } ?>
																	<?php  } ?>
																</ul>
															</td>
														</tr>
											<?php $i=$i+1; } } ?>

											<?php if (!empty($firm_details['packaging_materials'])) {
													$sr_no = 1;
													foreach ($packaging_type as $each_packaging_type) { ?>

														<tr>
															<td><?php echo $sr_no;?></td>
															<td><?php echo $each_packaging_type;?></td>
														</tr>
											<?php $sr_no = $sr_no + 1;	} } ?>

											<tr>
												<?php if(!empty($firm_details['sub_commodity'])){ ?>
													<td></td>
												<?php } ?>
												<td class="boldtext">Processing Fee</td>
												<td class="boldtext">Rs.<?php echo $application_charge; ?></td>
											</tr>
										</tbody>
									</table>
								</div>
							<?php } ?>

							<!-- this row will not appear when printing -->
							<div class="row no-print">
								<div class="col-12">
									<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'payment_modes')); ?>
										
									<?php //condition added on 14-09-2021 by Amol, to show intimation box for renewal application
										if ($application_type == 2) { ?>
											<?php echo $this->element('payment_details_elements/renewal_intimation'); ?>
										<?php } ?>
											
										<h5 class="mt-1 mb-2">Payment</h5>
										<div class="table-format">
											<div class="total_charges_table">
												<table class="table"></table>
											</div>
										</div>
											
										<?php echo $this->element('payment_details_elements/payment_information_details'); ?>
											
										<?php echo $this->Form->end(); ?>
											
											<!-- Call element of declaration message box out of Form tag on 31-05-2021 by Amol for Form base esign method -->
											<?php  
											if ($application_type == 2) {
												echo $this->element('renewal_inti_consent_box');
											} else {
												echo $this->element('declaration-message_boxes');
											}
										?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

                


	<?php if ($all_section_status == 1 && ($final_submit_status == 'no_final_submit' || $final_submit_status == 'referred_back')) { ?>
			<?php echo $this->Html->script('application/payment/final_submit_display') ;?>
	<?php } ?>

	<?php if ($final_submit_status != 'no_final_submit') { ?>
		<?php echo $this->Html->script('application/payment/no_final_submit') ;?>
	<?php  } ?>

	<?php if ($payment_confirmation_status == 'not_confirmed') { ?>
		<?php echo $this->Html->script('application/payment/not_confirmed') ;?>
	<?php  } ?>
