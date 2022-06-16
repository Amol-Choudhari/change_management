<?php ?>

	<div class="form-style-3">
		<!-- created new modal on 26-03-2018 by Amol, to show option with/without esign -->
			<div id="approved_check_modal" class="modal">
				<!-- Modal content -->				  
				<div class="modal-content">
				<p><b>Note:</b> We need approval for the communication done on <?php echo $approval_on; ?>, before proceeding to HO office. If there is no further discrepancies on the Form level and Report Level, please press "<b>Approve</b>" button. If you have discrepancies then press "<b>Cancel</b>" button and go to respected section first. Unless and until it is approved, it won't be sent to HO office. Thank you.</p>
				<br>
			
				<?php echo $this->Form->create();

					echo $this->Form->control('check_for_proceed', array('type'=>'checkbox', 'id'=>'check_for_proceed', 'label'=>' Please confirm that all the referred back comments by you on Forms/Reports were replied.','escape'=>false));
					?><div class="clear"></div><?php
				
					echo $this->Form->submit('Approve', array('name'=>'approve_check', 'id'=>'approve_btn','label'=>false, 'class'=>'btn btn-primary' ));
					
					echo $this->Form->submit('Cancel', array('name'=>'cancel_check', 'id'=>'cancel_btn','label'=>false, 'class'=>'btn btn-primary' ));
					
				
				echo $this->Form->end(); ?>
				
				<div class="clear"></div>
				</div>				 
			</div>
		</div>
		
		<?php echo $this->Html->script('element/ho_inspection_elements/forms_and_reports_approved_check'); ?>	
