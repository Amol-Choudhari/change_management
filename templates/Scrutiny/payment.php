<?php ?>
	<div class="col-md-12">
		<section class="content form-middle col-md-9">
			<div class="container-fluid">
				<div class="card card-success">
					<div class="card-header"><h3 class="card-title-new">Payment</h3></div>
						<div class="form-horizontal">
							<div class="card-body  p-0 m-2 rounded">
								<div class="row">
									<div class="form-style-3 form-middle">
										<?php echo $this->Form->create(null,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'payment_modes')); ?>
											<div class="table-format">
												<div class="total_charges_table">
													<table class="table m-0 table-bordered box2shadow">
														<thead class="tablehead">
															<tr>
																<th>Sr. No.</th>
																<?php if(!empty($firm_details['sub_commodity'])){ ?>
																	<th>Category</th>
																	<th>Commodities</th>
																<?php }elseif(!empty($firm_details['packaging_materials'])){ ?>
																	<th>Selected Packaging Material</th>
																<?php } ?>
															</tr>
													</thead>
													<tbody>
													<?php //above four line commented & below code applied on 10-09-2017 by Amol
															if(!empty($firm_details['sub_commodity'])){
															$i=1;
															foreach($commodity_name_list as $commodity_name){ ?>
															<tr>
																<td><?php echo $i; ?></td>
																<td><?php echo $commodity_name['category_name']; ?></td>
																<td>
																	<ul>
																		<?php foreach($sub_commodity_data as $sub_commodity){ ?>

																			<?php if($sub_commodity['category_code'] == $commodity_name['category_code']){?>

																			<li><?php echo $sub_commodity['commodity_name']; ?></li>

																			<?php } ?>

																		<?php  } ?>
																	</ul>
															</td>
														</tr>
													<?php $i=$i+1; } } ?>

													<?php if(!empty($firm_details['packaging_materials'])){
																	$sr_no = 1;
																	foreach($packaging_type as $each_packaging_type){ ?>

																		<tr>
																			<td><?php echo $sr_no;?></td>
																			<td><?php echo $each_packaging_type;?></td>
																		</tr>
													<?php $sr_no = $sr_no + 1;	} } ?>

													<tr>
														<?php if(!empty($firm_details['sub_commodity'])){ ?>
															<td></td>
														<?php } ?>
														<td class="cffffw">Processing Fee</td>
														<td class="c000fwb">Rs.<?php echo $application_charge; ?></td>								
													</tr>
												</tbody>
											</table>
										</div>
									</div>
							<?php echo $this->element('payment_details_elements/payment_information_details'); ?>
							<?php echo $this->Form->end(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
</div>

		<?php if($all_section_status == 1 && ($final_submit_status == 'no_final_submit' || $final_submit_status == 'referred_back')){ ?>
			<?php echo $this->Html->script('Scrutiny/all_section_status_final_submit_status_no_final__referred_back'); ?>
		<?php } ?>

		<?php  if($final_submit_status != 'no_final_submit'){ ?>
			<?php echo $this->Html->script('Scrutiny/final_submit_status_no_final_submit'); ?>
		<?php  } ?>

		<?php  if($payment_confirmation_status == 'not_confirmed'){ ?>
			<?php echo $this->Html->script('Scrutiny/payment_confirmation_status_not_confirmed'); ?>
		<?php  } ?>
