<?php $i=0; ?>

<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6"><label class="badge badge-info">Work Transfer</label></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
							<li class="breadcrumb-item active">User Work Transfer</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<section class="content form-middle ">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-10">
						<?php echo $this->Form->create(); ?>
							<div class="card card-primary">
								<div class="card-header"><h3 class="card-title-new">User Work Transfer</h3></div>
									<div class="form-horizontal">
										<h5 class="middle mt-3"><span class="badge badge-success">To Be Used On Retirement OR When Need To Transfer Entire Work</span></h5>
											<div class="card-body">
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group row">
															<label for="inputEmail3" class="col-sm-3 col-form-label">Select User: <span class="cRed">*</span></label>
																<div class="custom-file col-sm-9">
																	<?php echo $this->Form->control('users_list', array('label'=>'', 'type'=>'select', 'id'=>'users_list', 'options'=>$users_list, 'empty'=>'--- Select ---', 'required'=>true, 'class'=>'form-control float-left')); ?>
																	<span id="error_loc_id" class="error invalid-feedback"></span>
																</div>
															</div>
														</div>
														<div class="col-sm-6">
															<?php echo $this->Form->control('Get Details', array('type'=>'submit', 'id'=>'get_details', 'name'=>'get_details', 'label'=>false,'class'=>'btn btn-primary'));?>
														</div>
														<div class="textCenter col-sm-12">
															<?php echo $this->Html->image('ajax-loader.gif', array('id'=>'work_transfer_loader', 'class'=>'dnmt50')); ?>
														</div>
														<?php if (!empty($inProgressWork)) { ?>

															<?php if (empty($get_ho_perm_status)) { ?>

																<div class="col-sm-10 mt-3 offset-1">
																	<p class="alert alert-primary middle"><b>Please Note:</b> <br />
																		To Transfer/Reallocate the below work, You need a permission from HO (QC).<br />
																		The user cannot be deactivated unless all the below work is transferred to someone else.<br />
																	</p>
																</div>

															<?php } ?>

														<div class="col-sm-12">
															<h6 id="list_title" class="alert alert-success"></h6>

															<?php if (empty($get_ho_perm_status)) {

																echo $this->Form->control('Click to Request Permission from HO(QC)', array('type'=>'submit', 'name'=>'get_ho_permission','class'=>'fl_fwB_cNavy_mb10', 'label'=>false));

															} elseif ($ho_perm_status == 'Requested') { ?>

																<p class="note_for_requested">Note: Your Request to Transfer Work is Pending, Please Contact HO(QC)</p>

															<?php  } elseif ($ho_perm_status == 'Permitted') { ?>

																	<p class="note_for_permitted">Note: Your Request to Transfer Work is Permitted by HO(QC), Now you can transfer/reallocate the work.</p>

															<?php } elseif ($ho_perm_status == 'Rejected') { ?>

																<p class="note_for_rejected">Note: Your Request to Transfer Work is Rejected by HO(QC).</p>

															<?php } ?>
														</div>

														<div class="col-sm-12">
															<table id = "user_work_list" class="table m-0 table-hover	 table-bordered table-striped">
																<thead class="tablehead">
																	<tr>
																		<th>Appl. Type</th>
																		<th>Appl. Id</th>
																		<th>Release From</th>
																		<?php if($ho_perm_status == 'Permitted'){ ?>
																			<th>Allocate To</th>
																		<?php } ?>
																		<th>Action</th>
																	</tr>
																</thead>
																<tbody>
																<?php $i=1;
																	foreach ($inProgressWork as $eachwork) { ?>
																		<tr>
																			<td id="appl_type<?php echo $i;?>"><?php echo $eachwork['appl_type']; ?></td>
																			<td id="appl_id<?php echo $i;?>"><?php echo $eachwork['appl_id']; ?></td>
																			<td id="rels_from<?php echo $i;?>"><?php echo $eachwork['rels_from']; ?></td>

																			<?php if ($ho_perm_status == 'Permitted') { ?>
																			<td>
																				<?php if ($eachwork['rels_from'] == 'Scrutiny Allocation' || $eachwork['rels_from'] == 'Scrutiny Allocation(HO)') {
																					echo $this->Form->control('allocate_to', array('label'=>'', 'type'=>'select', 'id'=>'allocate_to'.$i, 'empty'=>'---Select---', 'options'=>$scrutiny_officers,'class'=>'form-control'));
																				} elseif ($eachwork['rels_from'] == 'Inspection Allocation') {
																					echo $this->Form->control('allocate_to', array('label'=>'', 'type'=>'select', 'id'=>'allocate_to'.$i, 'empty'=>'---Select---', 'options'=>$inspection_officers,'class'=>'form-control'));
																				} ?>
																			</td>
																			<?php } ?>

																			<td><a id="view_status_btn<?php echo $i;?>" title="Click to View Application Status"><span class="glyphicon glyphicon-eye-open panel"></span></a> |
																				<?php if ($ho_perm_status == 'Permitted') { ?>
																						<a id="allocate_btn<?php echo $i;?>" title="Click to Transfer"><span class="glyphicon glyphicon-share-alt panel"></span></a>
																				<?php } ?>
																			</td>
																		</tr>
																	<?php $i=$i+1; } ?>
																</tbody>
															</table>
														</div>
														<div id="show_appl_status" class="modal">
															<div class="modal-content">
																<div class="card-header"><h3 class="card-title-new">Application Details</h3></div>
																<div class="float-right"><span class="close"><b>&times;</b></span></div>																
																	<table class="table table-bordered">
																		<tr><td>Application Id : </td> <td id="show_appl_id"></td></tr>
																		<tr><td>Firm Name : </td> <td id="show_firm_name"></td></tr>
																		<tr><td>Applied On : </td> <td id="show_applied_on"></td></tr>
																		<tr><td>Currently with : </td> <td id="show_currently_with"></td></tr>
																		<tr><td>Last Status : </td> <td id="show_last_status"></td></tr>
																	</table>
																</div>
															</div>
													<?php } else { ?>
															<div class=""><?php echo $workNotPendingMsg; ?></div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
								<?php echo $this->form->end(); ?>
							</div>
						</div>
					</div>
				</section>
			</div>

	<input type="hidden" id="increment_id" value="<?php echo $i;?>">	
	<?php echo $this->Html->script('othermodules/user_work_transfer'); ?>
