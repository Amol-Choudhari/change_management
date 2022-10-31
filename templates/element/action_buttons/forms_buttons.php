
		<div class="col-md-12">
			
			<?php if (!empty($previousbtnid)) { ?>
				<a id="previous_btn" href="<?php echo $this->getRequest()->getAttribute('webroot');?>application/section/<?php echo $previousbtnid; ?>" class="btn btn-info float-left ml-2"><i class="fa fa-arrow-left"></i> Previous Section</a>
			<?php } ?>

			<?php

				if ($section_form_details[0]['form_status'] == 'saved') { $btn_label = 'Update'; } elseif ($section_form_details[0]['form_status'] == 'referred_back') { $btn_label = 'Save'; } else { $btn_label = 'Save & Next'; }

				if (empty($nextbtnid) and $section_form_details[0]['form_status'] == 'saved') { $btn_label = 'Update'; } else

				if (empty($nextbtnid) and $_SESSION['paymentSection'] != 'available') { $btn_label = 'Save'; }

				if ($btn_label == "Save & Next" || $_SESSION['application_type']==3) { 
					echo $this->Form->control('Reset', array('type'=>'reset', 'id'=>'reset_btn', 'class'=>'btn btn-secondary float-left ml-2', 'label'=>false)); 
				}

				echo $this->Form->submit($btn_label, array('name'=>'save', 'id'=>'save_btn', 'class'=>'btn btn-success float-left ml-2', 'label'=>false));

				if (isset($_SESSION['authscrutiny'])) {

					echo $this->form->submit('Section Scrutinized', array('name'=>'mo_verified', 'id'=>'verified', 'class'=>'btn btn-info float-left ml-2', 'label'=>false));
				}

				echo $this->Form->submit('Final Submit', array('name'=>'final_submit', 'id'=>'final_submit_btn', 'class'=>'dnone btn btn-success float-left ml-2', 'title'=>'Be sure all fields and details are properly filled for application before final submission', 'label'=>false));
			
				//Below Condition is added to hide these buttons on the Applicant side - Akash [17-10-2022]
				if (filter_var(base64_decode($_SESSION['username']), FILTER_VALIDATE_EMAIL)) {
									
					echo $this->Form->submit('Forward to '.$forward_to_btn, array('name'=>'accepted_forward', 'id'=>'accepted_forward_btn', 'class'=>'dnone bt btn-info float-left ml-2', 'label'=>false));

					echo $this->Form->submit('Final Granted', array('name'=>'final_granted', 'id'=>'final_granted_btn', 'class'=>'dnone btn btn-success float-left ml-2', 'label'=>false));
				}
				?>
				<?php if (!empty($nextbtnid)) { ?>
					<a id="next_btn" href="<?php echo $this->getRequest()->getAttribute('webroot');?>application/section/<?php echo $nextbtnid; ?>" class="btn btn-info float-right ml-2">Next Section <i class="fa fa-arrow-right"></i></a>
				<?php } elseif ($_SESSION['paymentSection'] == 'available') { ?>
					<a id="next_btn" href="<?php echo $this->getRequest()->getAttribute('webroot');?>application/payment" class="btn btn-info float-right ml-2">Next Section <i class="fa fa-arrow-right"></i></a>
				<?php } ?>
			
		</div>


	<input type="hidden" id="validationFunction" value="<?php echo $section_details['validation_function']; ?>">
	<input type="hidden" id="section_form_id" value="<?php echo $section; ?>">
	<input type="hidden" id="all_section_status" value="<?php echo $all_section_status; ?>">
	<input type="hidden" id="final_submit_status" value="<?php echo $final_submit_status; ?>">
	<input type="hidden" id="final_granted_btn_forms_button" value="<?php echo $final_granted_btn; ?>">
	<input type="hidden" id="current_level" value="<?php echo $_SESSION['current_level']; ?>">
	<input type="hidden" id="forward_to_btn_forms_button" value="<?php echo $forward_to_btn; ?>">
	<input type="hidden" id="authRegFirm" value="<?php echo $authRegFirm; ?>">


<?php echo $this->Html->script('element/action_buttons/forms_buttons'); ?>
