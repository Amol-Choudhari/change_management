
<?php if ($process_query == 'update' && $final_submit_status != 'no_final_submit') { ?>	
	
	<div id="comment_reply_box" ><!-- changed on 14-04-2017 by Amol(removed inline display none) -->
	
	<fieldset><legend>Previous Communication</legend>	
		<div class="remark-history">
		<table>

			<tr>
			<th>Date</th>
			<th>Remark</th>
			<th>Reply</th>
			<th class="action_coloum">Action</th>
			</tr>
			
			<!-- taking last id of applicant reply // added on 14-04-2017 by Amol -->
			<?php
				$reply_max_id = null;
				foreach ($fetch_comment_reply as $comment_reply) {
				if (!empty($comment_reply['reffered_back_date'])) {
				
					$reply_max_id = $comment_reply['id'];
				
				}}
			?>
			
			
			<?php foreach ($fetch_comment_reply as $comment_reply) {
				
				//view only rows with values.
				if (!empty($comment_reply['reffered_back_date'])) {?>
				
				<!-- Below code changed and added on 14-04-2017 by Amol for edit/delete Applicant reply
				This will show on click of edit button which create session for edit -->	
				<?php if ($this->Session->read('edit_reply_id') != null) {?>
					<tr>
						<?php if ($comment_reply['id']==$reply_max_id &&
								$check_last_record['id']==$reply_max_id &&
								$show_applicant_edit_delete == 'yes') {?>
								
							<td><?php echo $comment_reply['reffered_back_date']; ?></td>
							<td><?php echo $comment_reply['reffered_back_comment']; ?></td>
																							<!-- give id to edit comment box by pravin 26/07/2017-->
							<td><?php echo $this->Form->control('edited_reply', array('type'=>'textarea', 'id'=>'check_save_reply', 'value'=>$comment_reply['customer_reply'], 'escape'=>false,'label'=>false)); ?>
							<div id="error_save_reply"></div> <!--create div field for showing error message (by pravin 26/07/2017)-->
							</td>		
							<td>																								<!-- call comment box validation function by pravin 26/07/2017-->	
								<?php echo $this->form->submit('save', array('name'=>'save_edited_reply', 'id'=>'save_edited_reply', 'onclick'=>'comment_reply_box_validation();return false', 'label'=>false)); ?>
							</td>
						<?php } ?>
					</tr>
			
				
				<?php } else { ?>
				
					<tr>
					
						<td><?php echo $comment_reply['reffered_back_date']; ?></td>
						<td><?php echo $comment_reply['reffered_back_comment']; ?></td>
						<td><?php echo $comment_reply['customer_reply']; ?></td>
						<td><?php if ($comment_reply['id']==$reply_max_id &&
								$check_last_record['id']==$reply_max_id &&
								$show_applicant_edit_delete == 'yes') {?>
									
								<?php echo $this->Html->link('', array('controller' => 'customerforms', 'action'=>'edit_reply',$comment_reply['id']),array('id'=>'edit_reply','class'=>'glyphicon glyphicon-edit comment_reply_edit_btn', 'title'=>'Edit')); ?> | 
								<?php echo $this->Html->link('', array('controller' => 'customerforms', 'action'=>'delete_reply',$comment_reply['id']),array('id'=>'delete_reply','class'=>'glyphicon glyphicon-remove-sign comment_reply_delete_btn', 'title'=>'Delete')); ?>
							
							<?php } ?>
						</td>
					
					</tr>
				
				<?php } ?>
				
				
				
			<?php }
			}?>
			
		</table>
		
			<!-- this field is hidden and send max id and model name for edit/delete mo comment by ajax // added on 11-04-2017 by Amol-->
			<?php echo $this->Form->control('reply_max_id', array('type'=>'hidden', 'id'=>'reply_max_id', 'value'=>$reply_max_id, 'label'=>false,)); ?>
			<?php echo $this->Form->control('model_name', array('type'=>'hidden', 'id'=>'model_name', 'value'=>$current_model_name, 'label'=>false,)); ?>
		
		
		</div>
	</fieldset>
	
	<!--same above conditions for edit/delete options for Reply are applied here with NOT(opposite) operator to show reply box -->
	<?php if (!($comment_reply['id']==$reply_max_id &&
								$check_last_record['id']==$reply_max_id &&
								$show_applicant_edit_delete == 'yes')) {?>
								
		<fieldset><legend>Current Reply</legend>
			<div class="remark-current">								<!-- Give ID by pravin 07-07-2017-->
			<?php echo $this->Form->control('customer_reply', array('type'=>'textarea', 'id'=>'check_save_reply', 'escape'=>false, 'label'=>false, )); ?>
			</div> 
			<div id="error_check_save_reply"></div> <!--create div field for showing error message (by pravin 07-07-2017)-->
		</fieldset>
	
	<?php } ?>
	
	</div>
	
<?php } ?>