
<?php echo $this->Form->control('form_section_id', array('type'=>'hidden', 'id'=>'form_section_id', 'value'=>$section,'label'=>false,)); ?>

<!-- //commented below hidden input on 13-12-2022 by Amol, no need of conditions for change flow -->
<?php //echo $this->Form->control('changefields', array('type'=>'hidden', 'id'=>'changefields', 'value'=>$changefields,'label'=>false,)); ?>
<?php   echo $this->element($section_details['section_path']); ?>

	<div class="form-style-3 form-middle" id="actiob_buttons_x">
		<?php

			// set new communication window for chemist flow, Done by Aakash Thakare, 30-09-2021
			if($application_type == 4)
			{
				echo $this->element('communications_elements/ro_chemist_communication');
			}
			elseif ($section_details['comment_section'] == 'yes' && $fromHoLevel==null) {

				echo $this->element('communications_elements/ro_mo_applicant_communication');

			}
			else
			{

				echo $this->element('action_buttons/pre_next_buttons');
			}
		?>
	</div>
	<?php echo $this->Form->end(); ?>
		
		<?php
			 $i = 0;
		
			if (!empty($fetch_comment_reply)) {			

			foreach ($fetch_comment_reply as $comment_reply) {

				if (!empty($comment_reply['mo_comment_date'])||!empty($comment_reply['ro_reply_comment_date'])) {

					$i = $i+1;

				}

			}

		}   ?>

		<?php

			if ($final_granted_btn == 'yes') {

				echo $this->element('esign_views/declaration-message-before-grant');
			}
		?>


		<input type="hidden" id="i_id" value="<?php echo $i-1; ?>">

		<?php echo $this->Html->script('Scrutiny/form_scrutiny'); ?>


<?php if($application_mode == 'view' || $section_form_details[0]['form_status'] == 'approved'){  ?>
	<?php echo $this->Html->script('Scrutiny/application_mode_edit_check_if_report_filed'); ?>

<?php } ?>


<?php if($application_mode == 'edit' && $section_form_details[0]['form_status'] == 'approved' && !empty($check_if_report_filed)){  ?>
	<?php echo $this->Html->script('Scrutiny/application_mode_approved_check_if_report_filed'); ?>
<?php } ?>

<?php if(!empty($forward_to_btn) && $current_level == 'level_3'){ ?>
	<?php echo $this->Html->script('Scrutiny/forward_to_btn_and_current_level_3'); ?>
<?php } ?>

<?php if($final_granted_btn == 'yes' && $current_level == 'level_3'){ ?>
	<?php echo $this->Html->script('Scrutiny/final_granted_btn_yes_current_level_3'); ?>
<?php } ?>
