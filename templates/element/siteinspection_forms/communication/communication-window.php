<?php if ($_SESSION['current_level'] == 'level_2') { ?>

	<div id="comment_reply_box" class="col-md-12 form-middle">
		<div class="card card-dark">
			<div class="card-header bg-dark"><h3 class="card-title-new"><i class="fa fa-comments"></i> Communications With <?php echo $office_type; ?></h3></div>
				<div class="form-horizontal remark-history">
					<div class="card-body p-0 rounded">
						<div class="table-format">
							<table class="table m-0 table-bordered table-striped">
								<tr>
									<th>Date</th>
									<th>Remark</th>
									<th>Remark Uploaded</th>
									<th>Reply</th>
									<th>Reply Uploaded</th>
									<th>Action</th>
								</tr>
									<!-- taking last id of referred back date // added on 17-04-2017 by Amol-->
									<?php $reply_max_id = null;

										  foreach ($fetch_comment_reply as $comment_reply) {

											if (!empty($comment_reply['referred_back_date'])) {

												$reply_max_id = $comment_reply['id'];

											}

										  };
									?>

								<?php foreach ($fetch_comment_reply as $comment_reply) {
									  //view only rows with values.
									  if (!empty($comment_reply['referred_back_date'])) { ?>

										  <!-- Below code changed and added on 17-04-2017 by Amol for edit/delete reply
											This will show on click of edit button which create session for edit -->
											<?php if (isset($_SESSION['edit_reply_to_ro_id'])) { ?>

													<tr>
														<?php if ($comment_reply['id']==$reply_max_id && $section_form_details[0]['id']==$reply_max_id && $show_io_edit_delete == 'yes') { ?>
															<td><?php echo $comment_reply['referred_back_date']; ?></td>
															<td><?php echo $comment_reply['referred_back_comment']; ?></td>
															<td><?php if ($comment_reply['rb_comment_ul'] != null) { ?><a target="blank" id="rb_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rb_comment_ul']); ?>">Preview</a><?php } ?></td>
															<!-- give id to edit comment box by pravin 13/07/2017-->
															<td><?php echo $this->Form->control('edited_reply', array('type'=>'textarea', 'id'=>'check_save_reply', 'value'=>$comment_reply['io_reply'], 'escape'=>false,'label'=>false)); ?>
															<td><?php if ($comment_reply['ir_comment_ul'] != null) { ?><a target="blank" id="ir_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['ir_comment_ul']); ?>">Preview</a><?php } ?>
															</span>
															<?php echo $this->Form->control('ir_comment_ul',array('type'=>'file', 'id'=>'edit_ir_comment_ul', 'multiple'=>'multiple', 'label'=>false, 'class'=>'wid83ml34')); ?>
																<label id="rb_comment_label"></label>
															</td>
																<!--error filed for showing blank save reply in edit mode by pravin 13/07/2017-->
																<div id="error_save_reply"></div>
															</td>
															<td><!-- call comment box validation function by pravin 13/07/2017-->
																<?php echo $this->form->submit('save', array('name'=>'save_edited_reply', 'id'=>'save_edited_reply', 'label'=>false)); ?>
															</td>
														<?php } ?>
													</tr>

											<?php } else { ?>

													<tr>
														<td><?php echo $comment_reply['referred_back_date']; ?></td>
														<td><?php echo $comment_reply['referred_back_comment']; ?></td>
														<td><?php if ($comment_reply['rb_comment_ul'] != null) {?>
															<a target="blank" id="rb_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rb_comment_ul']); ?>">Preview</a>
															<?php }?>
														</td>
														<td><?php echo $comment_reply['io_reply']; ?></td>
														<td><?php if ($comment_reply['ir_comment_ul'] != null) {?>
															<a target="blank" id="ir_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['ir_comment_ul']); ?>">Preview</a>
															<?php }?>
														</td>
														<?php //print_r($reply_max_id); exit;?>
														<td><?php

																if ($comment_reply['id']==$reply_max_id &&
																$section_form_details[0]['id']==$reply_max_id &&
																$show_io_edit_delete == 'yes') {  ?>

																<?php echo $this->Html->link('', array('controller' => 'siteinspections', 'action'=>'edit_io_reply',$comment_reply['id']),array('id'=>'edit_io_reply','class'=>'far fa-edit comment_reply_edit_btn', 'title'=>'Edit')); ?> |
																<?php echo $this->Html->link('', array('controller' => 'siteinspections', 'action'=>'delete_io_reply',$comment_reply['id']),array('id'=>'delete_io_reply','class'=>'far fa-trash-alt comment_reply_delete_btn', 'title'=>'Delete')); ?>

															<?php } ?>
														</td>
													</tr>

											<?php } ?>

											<?php } } ?>

									</table>
										<!-- this field is hidden and send max id and model name for edit/delete RO Reply by ajax // added on 13-04-2017 by Amol-->
										<?php echo $this->Form->control('reply_max_id', array('type'=>'hidden', 'id'=>'reply_max_id', 'value'=>$reply_max_id,'label'=>false,)); ?>
										<?php echo $this->Form->control('model_name', array('type'=>'hidden', 'id'=>'model_name', 'value'=>$section_model_name,'label'=>false,)); ?>
									</div>
								</div>
							</div>

						<?php  if (!($comment_reply['id']==$reply_max_id && $section_form_details[0]['id']==$reply_max_id && $show_io_edit_delete == 'yes') && $section_form_details[0]['form_status'] == 'referred_back' && $application_mode == 'edit' && $section_form_details[0]['form_status'] != 'approved') { ?>

								<div class="row mt15p10">
									<div class="col-md-6">
										<div id="commentBoxIns">
											<label>Current Reply</label>
											<div class="remark-current">								<!-- give id to edit comment box by pravin 13/07/2017-->
												<?php echo $this->Form->control('io_reply', array('type'=>'textarea', 'id'=>'check_save_reply', 'escape'=>false, 'label'=>false, 'class'=>'form-control')); ?>
												<div id="error_save_reply"></div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<label>Upload File : </label>
										<?php echo $this->Form->control('ir_comment_ul',array('type'=>'file', 'id'=>'ir_comment_ul','multiple'=>'multiple', 'label'=>false, 'class'=>'form-control')); ?>
										<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
									</div>
								</div>

							<?php } ?>
						</div>
					</div>
				<?php } ?>

				<?php if ($_SESSION['current_level'] != 'level_2') { ?>

					<div id="comment_reply_box" class="col-md-12 form-middle">
						<div class="card card-dark">
							<div class="card-header bg-dark"><h3 class="card-title-new"><i class="fa fa-comments"></i> Communications With IO</h3></div>
								<div class="form-horizontal remark-history">
									<div class="card-body p-0 rounded">
										<div class="table-format">
											<table class="table m-0 table-bordered table-striped">
												<thead class="tablehead">
													<th>Date</th>
													<th>Remark</th>
													<th>Remark Uploaded</th>
													<th>Reply</th>
													<th>Reply Uploaded</th>
													<th>Action</th>
												</thead>
												<tbody>
										<!-- taking last id of MO comments // added on 11-04-2017 by Amol-->
										<?php $referred_back_max_id = null;

											
											  foreach ($fetch_comment_reply as $comment_reply) {

												  if (!empty($comment_reply['referred_back_date'])) {
														$referred_back_max_id = $comment_reply['id'];
												  }
											  };
										?>

										<?php foreach ($fetch_comment_reply as $comment_reply) {
												  //view only rows with values.
												  if (!empty($comment_reply['referred_back_date'])) { ?>
												  <!-- Below code changed and added on 15-04-2017 by Amol for edit/delete Referred Back This will show on click of edit button which create session for edit -->
												  <?php if (isset($_SESSION['edit_referred_back_to_io_id'])) { ?>

												  		<tr>
															<?php if ($comment_reply['id']==$referred_back_max_id && $section_form_details[0]['id']==$referred_back_max_id && $show_ro_edit_delete == 'yes') { ?>

															<td><?php echo $comment_reply['referred_back_date']; ?></td>
															<td><?php echo $this->Form->control('edited_referred_back', array('type'=>'textarea', 'id'=>'reffered_back_comment', 'value'=>$comment_reply['referred_back_comment'], 'escape'=>false,'label'=>false)); ?>
																<div id="error_referred_back"></div>
															</td>
															<td><?php if ($comment_reply['rb_comment_ul'] != null) { ?><a target="blank" id="rb_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rb_comment_ul']); ?>">Preview</a><?php }?></td>
															<td><?php echo $this->Form->control('rb_comment_ul',array('type'=>'file', 'id'=>'edit_rb_comment_ul','multiple'=>'multiple', 'label'=>false)); ?>
																<label id="rb_comment_label"></label>
															</td>
															<td><?php echo $comment_reply['io_reply']; ?></td>
															<td><!-- call comment box validation function by pravin 13/07/2017-->
																<?php echo $this->form->submit('save', array('name'=>'save_edited_referred_back', 'id'=>'save_edited_referred_back', 'label'=>false)); ?>
															</td>
														<?php } ?>
													</tr>

										      <?php } else { ?>

														<tr>
															<td><?php echo $comment_reply['referred_back_date']; ?></td>
															<td><?php echo $comment_reply['referred_back_comment']; ?></td>
															<td><?php if ($comment_reply['rb_comment_ul'] != null) { ?><a target="blank" id="rb_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rb_comment_ul']); ?>">Preview</a><?php }?></td>
															<td><?php echo $comment_reply['io_reply']; ?></td>
															<td><?php if ($comment_reply['ir_comment_ul'] != null) { ?><a target="blank" id="ir_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['ir_comment_ul']); ?>">Preview</a><?php }?></td>
															<td><?php if ($comment_reply['id']==$referred_back_max_id && $section_form_details[0]['id']==$referred_back_max_id && $show_ro_edit_delete == 'yes') { ?>
																<?php echo $this->Html->link('', array('controller' => 'roinspections', 'action'=>'edit_referred_to_io_back',$comment_reply['id']),array('id'=>'edit_referred_to_io_back','class'=>'far fa-edit comment_reply_edit_btn', 'title'=>'Edit')); ?> |
																<?php echo $this->Html->link('', array('controller' => 'roinspections', 'action'=>'delete_referred_to_io_back',$comment_reply['id']),array('id'=>'delete_referred_to_io_back','class'=>'far fa-trash-alt comment_reply_delete_btn', 'title'=>'Delete')); ?>
																<?php } ?>
															</td>
														</tr>
													<?php } ?>
												<?php } } ?>
											</tbody>
										</table>
									<!-- this field is hidden and send max id and model name for edit/delete RO Reply by ajax // added on 13-04-2017 by Amol-->
									<?php echo $this->Form->control('referred_back_max_id', array('type'=>'hidden', 'id'=>'referred_back_max_id', 'value'=>$referred_back_max_id,'label'=>false,)); ?>
									<?php echo $this->Form->control('model_name', array('type'=>'hidden', 'id'=>'model_name', 'value'=>$section_model_name,'label'=>false,)); ?>
								</div>
							</div>
						</div>


						<!-- Condition applied on 15-04-2017 by Amol to show/hide comment box in view only mode -->
						<?php if ($final_submit_status['status'] != 'referred_back' && $final_submit_status['status'] != 'approved') { ?>

						<!-- same above conditions for edit/delete options for RO are applied here with NOT(opposite) operator to show comment box -->
						<?php if (!empty($fetch_comment_reply) && !($comment_reply['id']==$referred_back_max_id && $section_form_details[0]['id']==$referred_back_max_id && $show_ro_edit_delete == 'yes') && $application_mode == 'edit' /* && $section_form_details[0]['form_status'] != 'approved' */) { ?>

								<div id="commentBoxIns">
									<div class="col-md-12 mb-3">
										<div class="row">
											<div class="col-md-6">
												<label>Current Remark</label>
													<div class="remark-current">
														<?php echo $this->Form->control('referred_back_comment', array('type'=>'textarea','class'=>'form-control' ,'id'=>'reffered_back_comment', 'escape'=>false, 'label'=>false, )); ?>
														<div id="error_referred_back"></div>
													</div>
												</div>
												<div class="col-md-6">
													<label>Upload File : </label>
													<?php echo $this->Form->control('rb_comment_ul',array('type'=>'file', 'id'=>'rb_comment_ul','multiple'=>'multiple','class'=>'btn btn-primary', 'label'=>false)); ?>
													<!--error filed for showing blank save reply in edit mode by pravin 13/07/2017-->
													<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				<?php }  ?>
			<!-- Call element of declaration message box before E-Sign of any application by Amol on 03-11-2017 -->
			<?php  //echo $this->element('esign_views/declaration-message_boxes'); ?>


		<input type="hidden" id="show_final_report_btn" value="<?php echo $show_final_report_btn; ?>">					
		<input type="hidden" id="accept_btn_comm_win" value="<?php echo $accept_btn; ?>">
		<input type="hidden" id="forward_to_btn_comm_win" value="<?php echo $forward_to_btn; ?>">
		<input type="hidden" id="final_granted_btn_comm_win" value="<?php echo $final_granted_btn; ?>">		

		<?php echo $this->Html->script('element/siteinspection_forms/communication/communication_window'); ?>
