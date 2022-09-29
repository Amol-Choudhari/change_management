
<?php $application_type = $_SESSION['application_type']; ?>

	<?php  if ($current_level == 'level_3') {  ?>

		<div class="col-md-12" id="comment_reply_box">
			<div class="card card-dark">
				<!-- taking last id of MO comments // added on 11-04-2017 by Amol-->
				<?php 
					$referred_back_max_id = null;
					foreach ($fetch_applicant_communication as $comment_reply) {
						if (!empty($comment_reply['reffered_back_date'])) {
							$referred_back_max_id = $comment_reply['id'];
					} };
				?>

				<!-- taking last id of MO comments // added on 11-04-2017 by Amol-->
				<?php 
					$ro_reply_max_id = null;
					foreach ($fetch_comment_reply as $comment_reply) {
						if (!empty($comment_reply['mo_comment_date']) || !empty($comment_reply['ro_reply_comment_date'])) {
							$ro_reply_max_id = $comment_reply['id'];
					} }; 
				?>

				<?php if ($level3_current_comment_to =='applicant' || $level3_current_comment_to =='both') { ?>

					<div class="card-header"><h3 class="card-title-new"><i class="fa fa-comments"></i> Communications With Applicant</h3></div>
					<div class="form-horizontal">
						<div class="card-body p-0 rounded mb-3">
							<div class="com-md-12">
								<table class="table table-bordered table-striped text-sm mb-0">
									<tr>
										<th>Date</th>
										<th>Remark By You</th>
										<th>Uploaded files By You</th>
										<th>Reply By Applicant</th>
										<th>Uploaded files By Applicant</th>
										<th>Action</th>
									</tr>

									<?php if ($fetch_applicant_communication != null) {

											foreach ($fetch_applicant_communication as $comment_reply) {

												//view only rows with values.
												if (!empty($comment_reply['reffered_back_date'])) { ?>

													<!-- Below code changed and added on 14-04-2017 by Amol for edit/delete RO reply
													This will show on click of edit button which create session for edit -->

													<?php if (isset($_SESSION['edit_referred_back_id'])) {  ?>

														<tr>
															<?php if ($comment_reply['id'] == $referred_back_max_id && $current_form_data['id'] == $referred_back_max_id && $show_ro_edit_delete == 'yes') {?>

																<td><?php echo $comment_reply['reffered_back_date']; ?></td><!-- give id to edit comment box by pravin 13/07/2017-->
																<td>
																	<?php echo $this->Form->control('edited_referred_back', array('type'=>'textarea', 'id'=>'reffered_back_comment', 'value'=>$comment_reply['reffered_back_comment'], 'escape'=>false,'label'=>false,'class'=>'form-control')); ?>
																	<span id="error_referred_back" class="error invalid-feedback"></span>
																</td>
																<td>
																	<?php if ($comment_reply['rb_comment_ul'] != null) { ?>
																		<a target="blank" id="rb_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rb_comment_ul']); ?>">Preview</a>
																	<?php }?>

																	<?php echo $this->Form->control('rb_comment_ul',array('type'=>'file', 'id'=>'edit_rb_comment_ul', 'multiple'=>'multiple', 'label'=>false)); ?>
																	<label id="rb_comment_label"></label>
																</td>
																<td><?php echo $comment_reply['customer_reply']; ?></td>
																<td></td>
																<td><?php echo $this->form->submit('save', array('name'=>'save_edited_referred_back', 'id'=>'save_edited_referred_back','label'=>false)); ?></td>
															<?php } ?>
														</tr>

													<?php } else { ?>

														<tr>
															<td><?php echo $comment_reply['reffered_back_date']; ?></td>
															<td><?php echo $comment_reply['reffered_back_comment']; ?></td>
															<td><?php if ($comment_reply['rb_comment_ul'] != null) {?>
																	<a target="blank" id="rb_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rb_comment_ul']); ?>">Preview</a>
																<?php }?>
															</td>
															<td><?php echo $comment_reply['customer_reply']; ?></td>
															<td><?php if ($comment_reply['cr_comment_ul'] != null) {?>
																	<a target="blank" id="rb_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['cr_comment_ul']); ?>">Preview</a>
																<?php }?>
															</td>
															<td>
																<?php if ($comment_reply['id']==$referred_back_max_id && $current_form_data['id']==$referred_back_max_id && $show_ro_edit_delete == 'yes') { ?>

																	<?php echo $this->Html->link('', array('controller' => 'scrutiny', 'action'=>'edit_referred_back',$comment_reply['id']),array('id'=>'edit_referred_back','class'=>'far fa-edit comment_reply_edit_btn', 'title'=>'Edit')); ?> |
																	<?php echo $this->Html->link('', array('controller' => 'scrutiny', 'action'=>'delete_referred_back',$comment_reply['id']),array('id'=>'delete_referred_back','class'=>'far fa-trash-alt comment_reply_delete_btn', 'title'=>'Delete')); ?>

																<?php } ?>
															</td>
														</tr>

													<?php } ?>

									<?php } } } ?>
									</table>
									<!-- this field is hidden and send referrd back max id for edit/delete RO referred back by ajax // added on 14-04-2017 by Amol-->
									<?php echo $this->Form->control('referred_back_max_id', array('type'=>'hidden', 'value'=>$referred_back_max_id,'label'=>false,'id'=>'referred_back_max_id')); ?>
									<?php echo $this->Form->control('model_name', array('type'=>'hidden', 'value'=>$tablename,'label'=>false, 'id'=>'model_name')); ?>
							</div>
							<div class="col-md-12 mt-2">
								<div class="form-buttons">
									<!-- same above conditions for edit/delete options for referred back are applied here with NOT(opposite) operator to show comment box -->
										<?php if ($fetch_applicant_communication != null) {

												if (!($comment_reply['id']==$referred_back_max_id && $current_form_data['id']==$referred_back_max_id && $show_ro_edit_delete == 'yes')) { ?>

													<div class="row">
														<div class="col-md-6">
															<div id="ro_referred_back_box" class="remark-current">
																<?php echo $this->Form->control('reffered_back_comment', array('type'=>'textarea', 'id'=>'reffered_back_comment', 'escape'=>false, 'label'=>false, 'placeholder'=>'Write Your Referred Back Comment For Applicant Here','class'=>'form-control')); ?>
																<div id="error_referred_back"></div>

																<label>Upload File : </label>
																<?php echo $this->Form->control('rb_comment_ul',array('type'=>'file', 'id'=>'rb_comment_ul','class'=>'btn btn-primary' ,'multiple'=>'multiple', 'label'=>false)); ?>
																<p class="lab_form_note mt-1"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
															</div>

														</div>
													</div>
														<!--error filed for showing blank save reply in edit mode by pravin 13/07/2017-->

													<!-- call comment box validation function by pravin 13/07/2017-->
												<h4 id="ro_referred_back_click" class="btn bg-green float-left dnone">Click to Comment</h4>

												</div>

												<!--comment by pravin and used in below 27-06-2017 -->
												<?php echo $this->Form->submit('Save Comment', array('name'=>'ro_referred_back', 'id'=>'ro_referred_back', /*'onclick'=>'comment_reply_ro_to_applicant_box_validation();return false',*/ 'label'=>false,'class'=>'btn btn-success float-left')); ?>
											<?php } ?>

											<?php echo $this->Form->submit('Final Submit to Applicant', array('name'=>'sent_to_applicant', 'title'=>'Be Sure before click, After final submit the application will only be available to you after applicant replied', 'id'=>'sent_to_applicant', 'label'=>false,'class'=>'btn btn-success float-right mr9')); ?>

										<?php } ?>
									</div>
								</div>
							</div>


				<?php } else { ?>

					<!--Show Applicant Comment in Read only mode when communication start with MO (By pravin 07-08-2017) -->

					<div class="card-header"><h3 class="card-title-new"><i class="fa fa-comments"></i> Communications With Applicant</h3></div>
						<div class="form-horizontal">
							<div class="card-body p-0 rounded">
								<div class="remark-history">
									<table class="table table-bordered table-striped text-sm mb-0">
										<tr>
											<th>Date</th>
											<th>Remark By You</th>
											<th>Uploaded files By You</th>
											<th>Reply By Applicant</th>
											<th>Uploaded files By Applicant</th>
										</tr>

										<?php foreach ($fetch_applicant_communication as $comment_reply) {

											//view only rows with values.
											if (!empty($comment_reply['reffered_back_date'])) {?>

												<tr>
													<td><?php echo $comment_reply['reffered_back_date']; ?></td>
													<td><?php echo $comment_reply['reffered_back_comment']; ?></td>
													<td><?php if ($comment_reply['rb_comment_ul'] != null) { ?><a target="blank" id="cr_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rb_comment_ul']); ?>">Preview</a> <?php }?></td>
													<td><?php echo $comment_reply['customer_reply']; ?></td>
													<td><?php if ($comment_reply['cr_comment_ul'] != null) {?><a target="blank" id="cr_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['cr_comment_ul']); ?>">Preview</a><?php }?></td>
												</tr>

											<?php } } ?>
									</table>
								</div>
							</div>
						</div>

				<?php } ?>

				<!-- add new conditions for application type 4, Done by Aakash Thakare 30-09-2021 -->
				<?php if (empty($check_if_report_filed) && $application_type !=4 && ($oldapplication=='no' || $application_type !=1) ) { //condition applied on 09-06-2017 to hide MO comm. box ?>

					<?php if ($level3_current_comment_to =='mo' || $level3_current_comment_to =='both') { ?>

						<div class="card-header bg-dark"><h3 class="card-title-new"><i class="fa fa-comments"></i> Communications With Scrutinizer</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 rounded mb-3">
									<div class="remark-history">
										<table class="table table-bordered table-striped text-sm mb-0">
											<tr>
												<th>Date</th>
												<!--<th>Comment By</th>--><!-- commented on 16-08-2022 as per UAT suggestion -->
												<th>Scrutiny Officer Comment</th>
												<th>Comment Uploads</th>
												<th>RO/SO Comment</th>
												<th>Reply Uploads</th>
												<th>Action</th>
											</tr>
											<?php $i = 0;

												  if ($fetch_comment_reply != null) {

													foreach ($fetch_comment_reply as $comment_reply) {

														//view only rows with values.
														if (!empty($comment_reply['mo_comment_date'])|| !empty($comment_reply['ro_reply_comment_date'])) { ?>

															<!-- Below code changed and added on 13-04-2017 by Amol for edit/delete RO reply
															This will show on click of edit button which create session for edit -->

															<?php if (isset($_SESSION['edit_ro_reply_id'])) { ?>

																<tr>
																	<?php if ($comment_reply['id']==$ro_reply_max_id && $last_record_with_delete_null['id']==$ro_reply_max_id && $last_comment_by != $_SESSION['username'] && $show_edit_delete_options == 'yes') { ?>

																		<td><?php
																				if (!empty($comment_reply['mo_comment_date'])) {
																					echo $comment_reply['mo_comment_date'];
																				} else {
																					echo $comment_reply['ro_reply_comment_date'];
																				}
																			?>
																		</td>
																		<!-- commented on 16-08-2022 as per UAT suggestion -->
																		<!--<td><?php //echo base64_decode($comment_reply['user_email_id']); ?></td>-->
																		<td><?php echo $comment_reply['mo_comment']; ?></td>
																		<td><?php if ($comment_reply['mo_comment_ul'] != null) { ?><a target="blank" id="mo_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['mo_comment_ul']); ?>">Preview</a><?php }?></td>
																		<!-- give id to edit comment box by pravin 13/07/2017-->
																		<td><?php echo $this->Form->control('edited_ro_reply', array('type'=>'textarea', 'id'=>'check_save_reply',  'value'=>$comment_reply['ro_reply_comment'], 'escape'=>false,'label'=>false)); ?>					   
																			<span id="error_save_reply" class="error invalid-feedback"></span>
																		</td>
																		<td><?php if ($comment_reply['rr_comment_ul'] != null) {?>
																			<a target="blank" id="edit_rr_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rr_comment_ul']); ?>">Preview</a>
																			<?php }?>
																			</span>
																			<?php echo $this->Form->control('rr_comment_ul',array('type'=>'file', 'id'=>'edit_rr_comment_ul','multiple'=>'multiple', 'label'=>false, 'class'=>'wid83ml34')); ?>
																			<label id="rr_comment_label"></label>
																		</td>
																		<td><!-- call comment box validation function by pravin 13/07/2017-->
																			<?php echo $this->form->submit('save', array('name'=>'save_edited_ro_reply', 'id'=>'save_edited_ro_reply','label'=>false)); ?>
																		</td>
																	<?php } ?>
																</tr>


															<?php } else { ?>

																<tr>
																	<td><?php if (!empty($comment_reply['mo_comment_date'])) {
																				 echo $comment_reply['mo_comment_date'];
																			} else {
																				echo $comment_reply['ro_reply_comment_date'];
																			}
																		?>
																	</td>
																	 <!-- commented on 16-08-2022 as per UAT suggestion -->  
																	 <!--<td id="comment_by<?php //echo $i; ?>"><?php //echo base64_decode($comment_reply['user_email_id']); ?></td>-->
																	<td id="mo_commentt<?php echo $i; ?>"><?php echo $comment_reply['mo_comment']; ?></td>
																	<td><?php if ($comment_reply['mo_comment_ul'] != null) { ?>
																		<a target="blank" id="mo_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['mo_comment_ul']); ?>">Preview</a>
																		<?php } ?>
																	</td>
																	<td id="ro_reply_comment<?php echo $i; ?>"><?php echo $comment_reply['ro_reply_comment']; ?></td>
																	<td><?php if ($comment_reply['rr_comment_ul'] != null) { ?>
																		<a target="blank" id="rr_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rr_comment_ul']); ?>">Preview</a>
																		<?php } ?>
																	</td>
																	<td>
																	<?php if ($comment_reply['id']==$ro_reply_max_id &&
																			$last_record_with_delete_null['id']==$ro_reply_max_id &&
																			$last_comment_by != $_SESSION['username'] && $show_edit_delete_options == 'yes') {?>

																		<?php echo $this->Html->link('', array('controller' => 'scrutiny', 'action'=>'edit_ro_reply',$comment_reply['id']),array('id'=>'edit_ro_reply','class'=>'far fa-edit comment_reply_edit_btn', 'title'=>'Edit')); ?> |
																		<?php echo $this->Html->link('', array('controller' => 'scrutiny', 'action'=>'delete_ro_reply',$comment_reply['id']),array('id'=>'delete_ro_reply','class'=>'far fa-trash-alt comment_reply_delete_btn', 'title'=>'Delete')); ?>
																	<?php } ?>
																	</td>
																</tr>
															<?php } ?>
												<?php	$i = $i+1; } } } ?>
											</table>

											<!-- this field is hidden and send max id and model name for edit/delete RO Reply by ajax // added on 13-04-2017 by Amol-->
											<?php echo $this->Form->control('ro_reply_max_id', array('type'=>'hidden', 'value'=>$ro_reply_max_id,'label'=>false,'id'=>'ro_reply_max_id')); ?>
											<?php echo $this->Form->control('model_name', array('type'=>'hidden', 'value'=>$tablename,'label'=>false, 'id'=>'model_name')); ?>

											<div class="col-md-12 mt-2">
												<div class="form-buttons">
												<?php if ($fetch_comment_reply != null) {  ?>

													<?php
													//same above conditions for edit/delete options for RO are applied here with NOT(opposite) operator to show comment box
													if (!(isset($last_record_with_delete_null['id'])==$ro_reply_max_id && $last_comment_by != $_SESSION['username'] && $show_edit_delete_options == 'yes')) { ?>

														<div class="row">
															<div class="col-md-6">
																<div id="ro_reply_box">								<!-- give id to edit comment box by pravin 13/07/2017-->
																	<?php echo $this->Form->control('ro_reply_comment', array('type'=>'textarea', 'id'=>'check_save_reply', 'escape'=>false, 'label'=>false, 'placeholder'=>'Write Your Reply for Scrutinizer/IO Here','class'=>'form-control')); ?>
																		<div id="error_save_reply"></div>

																		<label>Upload File : </label>
																		<?php echo $this->Form->control('rr_comment_ul',array('type'=>'file', 'id'=>'rr_comment_ul', 'multiple'=>'multiple', 'label'=>false,'class'=>'btn btn-primary')); ?>
																	<p class="lab_form_note mt-1"><i class="fa fa-info-circle"></i> File type: PDF, jpg & max size upto 2 MB</p>
																</div>
															</div>
														</div>
														<!--error filed for showing blank save reply in edit mode by pravin 13/07/2017-->


														<!-- Change variable $lab_firm_profile_detail with $last_record_with_delete_null['Dmi_laboratory_firm_detail'] by pravin 02/05/2017 -->
														<?php if (!empty($last_record_with_delete_null['mo_comment'])) { ?>

														<!-- below buttons commeted on 18-05-2021 by Amol, not so useful -->
														<!--	<h4 id="forward_comment">Forward to Applicant</h4> -->
														<!--	<h4 href="#" id="edit_comment">Edit & Send to Applicant</h4> -->

														<?php } ?>

														<h4 href="#" id="ro_reply_click" class="btn btn-success">Reply To Scrutinizer</h4>										<!-- call comment box validation function by pravin 13/07/2017-->
														<?php  echo $this->form->submit('Save Comment', array('name'=>'ro_reply', 'id'=>'ro_reply','label'=>false,'class'=>'btn btn-success float-left')); ?>

													<?php }

													echo $this->Form->submit('Final Submit To Scrutinizer', array('name'=>'sent_to', 'title'=>'Be Sure before click, After final submit the application will only be available to you after Scrutinizer Scrutiny', 'id'=>'sent_to_mo', 'label'=>false,'class'=>'btn btn-success float-right'));

												?>
												<?php }  ?>
											</div>
										</div>
									</div>
								</div>
							</div>

					<?php } else { ?>

						<!--Show RO Comment in Read only mode when communication start with Applicant (By pravin 07-08-2017) -->
						<div class="card-header bg-dark"><h3 class="card-title-new"><i class="fa fa-comments"></i> Communications With Scrutinizer</h3></div>
							<div class="form-horizontal">
								<div class="card-body p-0 rounded">
									<div class="col-md-12">
										<div class="remark-history">
											<table class="table table-striped mb-0">
												<tr>
													<th>Date</th>
													<th>Comment By</th>
													<th>Comment Uploads</th>
													<th>Your Reply</th>
													<th>Reply Uploads</th>
												</tr>
													<?php
														$i = 0;

														foreach ($fetch_comment_reply as $comment_reply) {

															if (!empty($comment_reply['mo_comment_date']) || !empty($comment_reply['ro_reply_comment_date'])) {?>

																<tr>
																	<td><?php
																			if (!empty($comment_reply['mo_comment_date'])) {

																					echo $comment_reply['mo_comment_date'];
																			} else {

																				echo $comment_reply['ro_reply_comment_date'];
																			}
																		?>
																	</td>
																	<td id="comment_by<?php echo $i; ?>"><?php echo base64_decode($comment_reply['user_email_id']); ?></td>
																	<td id="mo_commentt<?php echo $i; ?>"><?php echo $comment_reply['mo_comment']; ?></td>
																	<td><?php if ($comment_reply['mo_comment_ul'] != null) {?><a target="blank" id="mo_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['mo_comment_ul']); ?>">Preview</a><?php }?></td>
																	<td id="ro_reply_comment<?php echo $i; ?>"><?php echo $comment_reply['ro_reply_comment']; ?></td>
																	<td><?php if ($comment_reply['rr_comment_ul'] != null) { ?><a target="blank" id="rr_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rr_comment_ul']); ?>">Preview</a><?php }?></td>
																</tr>
																<?php

																$i = $i+1;
															}
														}
													?>
											</table>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
	<?php } ?>

				<!-- This Window is for MO only -->
				<?php if ($current_level == 'level_1') { ?>

					<div class="card-header bg-dark"><h3 class="card-title-new"><i class="fa fa-comments"></i> Communications With <?php echo $office_type; ?></h3></div>
						<div class="form-horizontal">
							<div class="card-body p-0 rounded">
								<div class="m-0">
									<div class="remark-history">
										<table class="table table-striped table-bordered mb-0">
											<tr>
												<th>Date</th>
												<th>Comment By Scrutinizer</th>
												<th>Comment Uploads</th>
												<th>Reply By <?php echo $office_type; ?></th>
												<th>Reply Uploads</th>
												<th>Action</th>
											</tr>

											<!-- taking last id of MO comments // added on 11-04-2017 by Amol-->
											<?php
												$mo_comment_max_id = null;

												foreach ($fetch_comment_reply as $comment_reply) {

													if (!empty($comment_reply['mo_comment_date']) || !empty($comment_reply['ro_reply_comment_date']))	{

														$mo_comment_max_id = $comment_reply['id'];

													}};
												?>

												<!-- display comments list -->
												<?php foreach ($fetch_comment_reply as $comment_reply) {

													//view only rows with values.
													if (!empty($comment_reply['mo_comment_date']) || !empty($comment_reply['ro_reply_comment_date'])) { ?>

														<!-- Below code changed and added on 11-04-2017 by Amol for edit/delete mo comment
															This will show on click of edit button which create session for edit -->

														<?php if (isset($_SESSION['edit_mo_comment_id'])) { ?>

															<tr>
																<?php if ($comment_reply['id']==$mo_comment_max_id && $current_form_data['id']==$mo_comment_max_id && $last_comment_by != $_SESSION['username'] && $show_edit_delete_options == 'yes') { ?>

																	<td>
																		<?php if (!empty($comment_reply['mo_comment_date'])) {
																			echo $comment_reply['mo_comment_date'];
																		} else {
																			echo $comment_reply['ro_reply_comment_date'];
																		} ?>
																	</td>
																		<!-- give id to edit comment box by pravin 13/07/2017-->
																	<td>
																		<?php echo $this->Form->control('edited_mo_comment', array('type'=>'textarea', 'id'=>'check_save_reply', 'value'=>$comment_reply['mo_comment'], 'escape'=>false,'label'=>false)); ?>
																		<!--error filed for showing blank save reply in edit mode by pravin 13/07/2017-->
																		<div id="error_save_reply"></div>
																	</td>
																	<td>
																		<?php if ($comment_reply['mo_comment_ul'] != null) { ?>

																			<a target="blank" id="edit_mo_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['mo_comment_ul']); ?>">Preview</a>

																			<?php }?>
																			</span>
																		<?php echo $this->Form->control('mo_comment_ul',array('type'=>'file', 'id'=>'edit_mo_comment_ul', 'multiple'=>'multiple', 'label'=>false, 'class'=>'wid83ml34')); ?>
																		<label id="mo_comment_label"></label>
																	</td>
																	<td><?php echo $comment_reply['ro_reply_comment']; ?></td>
																	<td><?php if ($comment_reply['rr_comment_ul'] != null) {?>
																			<a target="blank" id="rr_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rr_comment_ul']); ?>">Preview</a>
																		<?php }?>
																	</td>
																	<td><!-- call comment box validation function by pravin 13/07/2017-->
																		<?php echo $this->form->submit('save', array('name'=>'save_edited_mo_comment', 'id'=>'save_edited_mo_comment','label'=>false)); ?>
																	</td>
																<?php } ?>
															</tr>


														<?php } else { ?>

															<!--	This will show by default if edit button not clicked -->
														<tr>
															<td>
																<?php if (!empty($comment_reply['mo_comment_date'])) {
																			echo $comment_reply['mo_comment_date'];
																	} else {
																				echo $comment_reply['ro_reply_comment_date'];
																	}
																?>
															</td>
															<td class="text-cyan text-bold"><?php echo $comment_reply['mo_comment']; ?></td>
															<td><?php if ($comment_reply['mo_comment_ul'] != null) { ?>
																<a target="blank" id="mo_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['mo_comment_ul']); ?>">Preview</a>
																<?php }?>
															</td>
															<td><?php echo $comment_reply['ro_reply_comment']; ?></td>
															<td><?php if ($comment_reply['rr_comment_ul'] != null) { ?>
																<a target="blank" id="rr_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rr_comment_ul']); ?>">Preview</a>
																<?php } ?>
															</td>
															<td>
															<?php if ($comment_reply['id']==$mo_comment_max_id && $current_form_data['id']==$mo_comment_max_id && $last_comment_by != $_SESSION['username'] && $show_edit_delete_options == 'yes') { ?>

																<?php echo $this->Html->link('', array('controller' => 'scrutiny', 'action'=>'edit_mo_comment',$comment_reply['id']),array('id'=>'edit_mo_comment','class'=>'far fa-edit comment_reply_edit_btn', 'title'=>'Edit')); ?> |
																<?php echo $this->Html->link('', array('controller' => 'scrutiny', 'action'=>'delete_mo_comment',$comment_reply['id']),array('id'=>'delete_mo_comment','class'=>'far fa-trash-alt comment_reply_delete_btn', 'title'=>'Delete')); ?>
															<?php } ?>
															</td>
														</tr>

													<?php } ?>

												<?php } } ?>
										</table>
									<!-- this field is hidden and send max id and model name for edit/delete mo comment by ajax // added on 11-04-2017 by Amol-->
									<?php echo $this->Form->control('mo_comment_max_id', array('type'=>'hidden', 'value'=>$mo_comment_max_id,'label'=>false,'id'=>'mo_comment_max_id')); ?>
									<?php echo $this->Form->control('model_name', array('type'=>'hidden', 'value'=>$tablename,'label'=>false,'id'=>'model_name')); ?>
								</div>
							</div>
						</div>
					</div>

					<?php if ($_SESSION['application_mode'] == 'edit') {

						//same above conditions for edit/delete options for MO are applied here with NOT(opposite) operator to show comment box

						if (!($current_form_data['id']==$mo_comment_max_id && $last_comment_by != $_SESSION['username'] && $show_edit_delete_options == 'yes')) {  ?>

							<div class="commentBox">
								<div class="remark-current row">
									<div class="col-md-6">
										<label class="col-sm-3 col-form-label">Current Comment</label>
										<?php echo $this->Form->control('comment_by_mo', array('type'=>'textarea', 'id'=>'check_save_reply', 'escape'=>false, 'label'=>false,'class'=>'form-control')); ?>
										<span id="error_save_reply" class="error invalid-feedback"></span>
									</div>

									<div class="col-md-6">
										<label class="col-sm-3 col-form-label">Upload File : </label>
										<?php echo $this->Form->control('mo_comment_ul',array('type'=>'file', 'id'=>'mo_comment_ul', 'multiple'=>'multiple', 'label'=>false,'class'=>'form-control btn btn-primary')); ?>
										<span id="error_mo_comment_ul" class="error invalid-feedback"></span>
										<span id="error_size_mo_comment_ul" class="error invalid-feedback"></span>
										<span id="error_type_mo_comment_ul" class="error invalid-feedback"></span>
									</div>
								</div>
							</div>

					<?php } } ?>

				<?php } ?>

				<div class="col-md-12 card-footer cardFooterBackground">
					<div class="form-buttons">

					<?php if (!empty($previousbtnid)) { ?>
								<a class="btn btn-secondary float-left" href="<?php echo $this->request->getAttribute('webroot');?>scrutiny/section/<?php echo $previousbtnid; ?>" >Previous Section</a>
					<?php } ?>

					<?php if ($current_level == 'level_3') {

						echo $this->Form->submit('Section Scrutinized', array('name'=>'mo_verified', 'id'=>'verified', 'label'=>false,'class'=>'btn btn-info float-left'));
						if (empty($allocation_deatils['level_4_ro'])&& empty($allocation_deatils['level_2'])) {
							echo $this->Form->submit('Forward to '.$forward_to_btn, array('name'=>'accepted_forward', 'id'=>'accepted_forward_btn',  'class'=>'dnone btn btn-success float-left', 'label'=>false));
						}

						if ($final_granted_btn == 'yes'&& empty($allocation_deatils['level_2'])) {
							echo $this->Form->submit('Final Granted', array('name'=>'final_granted', 'id'=>'final_granted_btn', 'class'=>'dnone btn btn-success float-left', 'label'=>false));
						}
					}
					?>

					<?php if ($current_level == 'level_1') {
						//same above conditions for edit/delete options for MO are applied here with NOT(opposite) operator to show comment box
						if (!($current_form_data['id']==$mo_comment_max_id && $last_comment_by != $_SESSION['username'] && $show_edit_delete_options == 'yes')) {
							// call comment box validation function by pravin 13/07/2017
							echo $this->form->submit('Save Comment', array('name'=>'mo_referred_back', 'id'=>'mo_referred_back', 'label'=>false,'class'=>'btn btn-success float-left'));
						}
							echo $this->form->submit('Final Submit', array('name'=>'sent_to', 'title'=>'Be Sure before click, After final submit this application will be only available if Nodal Office replied to you', 'id'=>'sent_to_ro', 'label'=>false,'class'=>'btn btn-success float-left boldtext ml4'));
					}
					?>

					<?php if (!empty($nextbtnid)) { ?>
							<a class="btn btn-primary float-right" id="previous_btn" href="<?php echo $this->request->getAttribute('webroot');?>scrutiny/section/<?php echo $nextbtnid; ?>" >Next Section</a>
					<?php } elseif ($_SESSION['paymentSection'] == 'available') { ?>
							<a class="btn btn-primary float-right" id="previous_btn" href="<?php echo $this->request->getAttribute('webroot');?>scrutiny/payment">Next Section</a>
					<?php } ?>

					</div>
				</div>
			</div>
		</div>

		<?php echo $this->Html->script('element/communications_elements/ro_mo_applicant_communication'); ?>
