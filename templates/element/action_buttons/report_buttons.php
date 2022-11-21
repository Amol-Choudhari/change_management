<?php ?>
<div class="form-buttons form-style-3 row">
	<?php if (!empty($section_details['previous_btn'])) { ?>
		<a id="previous_btn" class="btn btn-secondary float-left" href="<?php echo $this->request->getAttribute('webroot');?>inspections/section/<?php echo $section_details['section_id']-1; ?>" >Previous Section</a>
	<?php } ?>

	<?php if ($application_mode == 'edit') {

			$reset_btn = '';
			if ($section_form_details[0]['form_status'] == 'saved') { $btn_label = 'Update'; }
			elseif ($section_form_details[0]['form_status'] == 'referred_back') {  $btn_label = 'Save'; }
			elseif (!empty($section_details['next_btn'])) { $btn_label = 'Save & Next'; $reset_btn = 'Y'; }
			else { $btn_label = 'Save'; $reset_btn = 'Y'; }

			if (!empty($section_details['save_btn']) && empty($final_submit_status)) {
				echo $this->Form->submit($btn_label, array('name'=>'save', 'id'=>'save_btn', 'class'=>'btn btn-success', 'label'=>false));
			}
			if (!empty($section_details['final_submit_btn']) && empty($final_submit_status) ) {
				echo $this->Form->submit('Final Submit', array('name'=>'final_submit', 'id'=>'final_submit_btn', 'class'=>'btn btn-success dnone','title'=>'Be sure all fields and details are properly filled for application before final submission', 'label'=>false));
			}
				/*if(!empty($section_details['reset_btn'])) {
					if(($reset_btn == "Y" || $_SESSION['application_type']==3) && empty($final_submit_status)){ echo $this->Form->control('Reset', array('type'=>'reset', 'id'=>'reset_btn', 'label'=>false,'class'=>'btn btn-secondary')); }
				}*/

				if (!empty($final_submit_status)) {

					if ($section_details['comment_section']=='yes') {

						if (!empty($show_save_btn) && $_SESSION['current_level'] == 'level_2') {
							echo $this->Form->submit('Save', array('name'=>'save', 'id'=>'save_btn', 'class'=>'btn btn-success', 'label'=>false));
						}

						if (!empty($show_referred_back_btn) && $_SESSION['current_level'] != 'level_2' && $final_submit_status['status'] != 'referred_back' && $final_submit_status['status'] != 'approved') {
							echo $this->Form->submit('Save Comment', array('name'=>'referred_back', 'id'=>'referred_back', 'label'=>false,'class'=>'btn btn-success'));
						}

						if ($_SESSION['current_level'] != 'level_2' && $report_referred_back_status != '') {
							echo $this->Form->submit('Submit to IO', array('name'=>'send_to_io', 'id'=>'send_to_io', 'label'=>false,'class'=>'btn btn-success'));
						}

						if (!empty($show_sent_to_btn) && $_SESSION['current_level'] == 'level_2' && $show_final_report_btn == 'yes') {
							echo $this->Form->submit('Final Submit to '.$office_type, array('name'=>'sent_to', 'id'=>'sent_to', 'label'=>false,'class'=>'btn btn-success'));
						}

					}
					if (!empty($section_details['accept_btn'])) {
						echo $this->Form->submit('Accepted', array('name'=>'accepted', 'id'=>'accepted','label'=>false,'class'=>'btn btn-success dnone'));
					}
					if (!empty($section_details['forward_btn'])) {

						echo $this->Form->submit('Forward to '.$forward_to, array('name'=>'accepted_forward', 'id'=>'accepted_forward_btn','label'=>false,'class'=>'btn btn-success dnone'));
					}
					if (!empty($section_details['final_grant_btn'])) {
						echo $this->Form->submit('Final Granted', array('name'=>'final_granted', 'id'=>'final_granted_btn','label'=>false,'class'=>'btn btn-success float-right dnone'));
					}

					if ($_SESSION['current_level'] != 'level_2') {
						echo $this->Form->submit('View Application', array('name'=>'view_application', 'id'=>'view_application', 'label'=>false,'class'=>'btn btn-primary'));
					}

				}

				if (!empty($section_details['reject_btn'])) {
					echo $this->Form->submit('Reject', array('name'=>'reject_btn', 'id'=>'reject_btn', 'label'=>false,'class'=>'btn btn-danger'));
				}

			}

	?>

	<?php if (!empty($section_details['next_btn'])) { ?>
		<a id="next_btn" class="btn btn-primary float-right" href="<?php echo $this->request->getAttribute('webroot');?>inspections/section/<?php echo $section_details['section_id']+1; ?>" >Next Section</a>
	<?php } ?>


</div>

<input type="hidden" id="validationFunction" value="<?php echo $section_details['validation_function']; ?>">
<input type="hidden" id="section_form_id" value="<?php echo $section; ?>">

<input type="hidden" id="show_final_report_btn_report_buttons" value="<?php echo $show_final_report_btn; ?>">
<input type="hidden" id="final_granted_btn_report_buttons" value="<?php echo $final_granted_btn; ?>">
<input type="hidden" id="current_level" value="<?php echo $_SESSION['current_level'] ?>">
<input type="hidden" id="accept_btn_report_buttons" value="<?php echo $accept_btn; ?>">
<input type="hidden" id="forward_to_btn_report_buttons" value="<?php echo $forward_to_btn; ?>">
<input type="hidden" id="application_mode" value="<?php echo $application_mode; ?>">
<input type="hidden" id="section_form_status" value="<?php echo $section_form_details[0]['form_status']; ?>"><!-- added on 03-11-2022 to show/hide Accepted btn -->


<?php echo $this->Html->script('element/action_buttons/report_buttons'); ?>