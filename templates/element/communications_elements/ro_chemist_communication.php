
<?php echo $this->Form->control('final_submit_status', array('type'=>'hidden', 'id'=>'final_submit_status', 'value'=>$final_submit_status)); ?>
<?php echo $this->Form->control('form_status', array('type'=>'hidden', 'id'=>'form_status', 'value'=>$section_form_details[0]['form_status'])); ?>
<?php //echo $this->Form->control('final_submt_btn', array('type'=>'hidden', 'id'=>'final_submt_btn', 'value'=>$final_submt_btn)); ?>

<?php if ($_SESSION['application_dashboard'] == 'ro') { ?>

	<div class="comment_bx_container">
		<div class="form-horizontal">
			<div class="card-header bg-dark"><h3 class="comment_bx_title card-title-new">Communications With Chemist</h3></div>
				<div class="remark-history remark_bx">
					<table id="referredbackcommenttable" class="remark_tbl table table m-0 table-striped table-hover table-bordered">
						<thead class="remark_tbl_thead tablehead">
							<tr>
								<th>Comment Date</th>
								<th>Comment By You</th>
								<th>Reply Date</th>
								<th>Reply By Chemist</th>
								<th class="action_coloum">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($chemist_referredback_history)) { ?>
								<?php foreach ($chemist_referredback_history as $value) {  ?>
									<tr>
										<td><?php echo $value['comment_dt']; ?></td>
										<td><?php echo $value['comments']; ?></td>
										<td><?php echo $value['reply_dt']; ?></td>
										<td><?php echo $value['reply_comment']; ?></td>
										<?php if ($value['is_latest'] == 1) { ?>
										<td class="remark_action">
											<button class="che-referred-back-comment-bx btn btn-sm btn-info remark_edit_btn" id="<?php echo $value['id']; ?>"><i class="fa fa-edit"></i></button>
											<?php echo $this->Html->link('<i class="fa fa-times"></i>', array('controller' => 'scrutiny', 'action'=>'delete_comment',$value['id'],$_SESSION['section_id']),array('id'=>'che_delete_referred_back','class'=>'comment_reply_delete_btn btn btn-danger remark_delete btn-sm', 'title'=>'Delete', 'escapeTitle'=>false)); ?>
										</td>
										<?php } ?>
									</tr>
								<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="card-header">
				<div class="col-sm-6">
					<?php echo $this->Form->control('reffered_back_id', array('type'=>'hidden', 'id'=>'reffered_back_id', 'escape'=>false, 'label'=>false));?>
					<div id="che_ro_referred_back_box" class="remark-current comment_bx_body">
						<?php echo $this->Form->control('reffered_back_comment', array('type'=>'textarea', 'id'=>'reffered_back_comment_bx', 'escape'=>false, 'class'=>'cvOn cvReq form-control comment_bx', 'label'=>false, 'placeholder'=>'Write Your Referred Back Comment For Applicant Here')); ?>
						<div class="err_cv"></div>
					</div>
				</div>
			</div>
		</div>

<?php } elseif ($_SESSION['application_dashboard'] == 'chemist' && $final_submit_status != 'no_final_submit') { ?>

	<div class="comment_bx_container">
		<div class="form-horizontal">
			<div class="card-header bg-dark"><h3 class="comment_bx_title card-title-new">Comments History</h3></div>
			<div class="remark-history remark_bx">
				<table id="referredbackcommenttable" class="remark_tbl table table m-0 table-striped table-hover table-bordered">
				<thead class="remark_tbl_thead tablehead">
					<tr>
						<th>Comment Date</th>
						<th>Comment By RO</th>
						<th>Reply Date</th>
						<th>Reply By You</th>
						<th class="action_coloum">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($chemist_referredback_history)) { ?>
						<?php foreach ($chemist_referredback_history as $value) {  ?>
							<tr>
								<td><?php echo $value['comment_dt']; ?></td>
								<td><?php echo $value['comments']; ?></td>
								<td><?php echo $value['reply_dt']; ?></td>
								<td><?php echo $value['reply_comment']; ?></td>
								<td class="remark_action">
									<?php if ($value['is_latest'] == 1 && $value['reply_comment'] != '') { ?>
										<button class="che-referred-back-comment-bx btn btn-sm btn-info remark_edit_btn" id="<?php echo $value['id']; ?>"><i class="fa fa-edit"></i></button>
										<?php echo $this->Html->link('<i class="fa fa-times"></i>', array('controller' => 'application', 'action'=>'delete_comment',$value['id'],$_SESSION['section_id']),array('id'=>'delete_referred_back','class'=>'comment_reply_delete_btn btn btn-danger remark_delete btn-sm', 'title'=>'Delete', 'escapeTitle'=>false)); ?>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
			</table>
		</div>
		</div>
		<div class="card-header">
			<div class="col-sm-6">
				<?php echo $this->Form->control('re_id', array('type'=>'hidden', 'id'=>'reffered_back_id', 'escape'=>false, 'label'=>false));?>
				<div id="ro_referred_back_box" class="remark-current comment_bx_body">
					<?php echo $this->Form->control('reffered_back_comment', array('type'=>'textarea', 'id'=>'reffered_back_comment_bx', 'escape'=>false, 'class'=>'cvOn cvReq form-control comment_bx', 'label'=>false, 'placeholder'=>'Write Your Reply for RO')); ?>
					<div class="err_cv"></div>
				</div>
			</div>
		</div>	
	</div> 

<?php } ?>

<?php $controller = $this->request->getParam('controller'); ?>									
	<div class="col-md-12">
		<?php if ($_SESSION['application_dashboard'] == 'ro') { ?>

			<?php   if (!empty($previousbtnid)) { ?>
			
				<a class="btn btn-primary float-left" id="previous_btn" href="<?php echo $this->request->getAttribute('webroot');?><?php echo $controller; ?>/section/<?php echo $previousbtnid; ?>">Previous Section</a>
			
			<?php } ?>

			<?php //if ($_SESSION['application_dashboard'] == 'chemist') { ?>

				<?php //echo $this->Form->submit('Save & Next', array('name'=>'save', 'id'=>'save_btn', 'class'=>'btn btn-success mr-5px btn_w_auto', 'style'=>'float:left; margin-right:10px;', 'disabled'=>'disabled','label'=>false, )); ?>
				<?php //if($all_section_status == 1 && ($final_submit_status == 'no_final_submit' || $final_submit_status == 'referred_back')){ 
					//echo $this->Form->submit('Final Submit', array('name'=>'final_submit', 'id'=>'final_submit', 'class'=>'btn btn-info mr-5px btn_final_submit', 'style'=>'float:left; margin-right:10px', 'label'=>false)); 
				//} ?>

			
				<?php echo $this->Form->control('Save Comment', array('name'=>'che_ro_referred_back','type'=>'submit','id'=>'che_ro_referred_back', 'class'=>'btn btn-success mr-5px btn_w_auto btn_save_remark float-left ml-2','label'=>false)); ?>
			

			
				<?php if (!empty($atleastOneComment)) { 

					echo $this->Form->control('Final Submit Referred Back', array('name'=>'sent_to_applicant', 'type'=>'submit', 'id'=>'final_submit_referred_back', 'class'=>'btn btn-success mr-5px btn_final_submit float-left','label'=>false)); 
				
				} ?>
	
				<?php echo $this->Form->control('Approve Section', array('name'=>'mo_verified', 'id'=>'approved', 'type'=>'submit', 'class'=>'btn btn-info float-right','label'=>false)); ?>
		
		
				<?php 	if(!empty($nextbtnid)){  ?>

					<a class="btn btn-secondary float-right mr-2" id="next_btn" href="<?php echo $this->request->getAttribute('webroot');?><?php echo $controller; ?>/section/<?php echo $nextbtnid; ?>" >Next Section</a>
				
				<?php } ?>
		
		<?php } ?>
	</div>  
