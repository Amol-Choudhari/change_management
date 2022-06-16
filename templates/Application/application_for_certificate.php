

<?php if($authRegFirm == 'yes'){ ?>
	
<?php } ?>

<?php echo $this->Form->control('form_section_id', array('type'=>'hidden', 'id'=>'form_section_id', 'value'=>$section,'label'=>false,)); ?>
<?php echo $this->Form->control('changefields', array('type'=>'hidden', 'id'=>'changefields', 'value'=>$changefields,'label'=>false,)); ?>

	<?php echo $this->element($section_details['section_path']); ?>

	<?php if ($authRegFirm == 'no' || $application_type != 1 ) { ?>
		<div class="form-style-3 form-middle">
			<?php
				// set new communication window for chemist flow, Done by Akash Thakre, 30-09-2021
				if ($application_type == 4) {

					echo $this->element('communications_elements/ro_chemist_communication');

				} elseif ($section_details['comment_section'] == 'yes') {

					echo $this->element('communications_elements/applicant_side_communication');
				}
			?>
		</div>
	<?php } ?>

	<div class='test'>
		<?php echo $this->element('action_buttons/forms_buttons'); ?>
	</div>


<?php echo $this->Form->end(); ?>

	<!-- Call element of declaration message box out of Form tag on 31-05-2021 by Amol for Form base esign method -->
	<?php if (($authRegFirm == 'no' || $application_type != 1) && $oldapplication == 'no' && $application_type != 4){ echo $this->element('declaration-message_boxes'); } ?>

	<?php echo $this->Html->script('application/application_for_certificate/edit_and_delete_reply'); ?>

	<?php if (!isset($_SESSION['authscrutiny'])) {  ?>

		<?php if ($final_submit_status != 'referred_back' && $final_submit_status != 'no_final_submit') {  ?>
			<?php echo $this->Html->script('application/application_for_certificate/authscrutiny_referred_back_no_final_submit'); ?>
		<?php } ?>

		<?php if ($final_submit_status == 'referred_back' && $current_form_data['reffered_back_comment'] =='') { ?>
			<?php echo $this->Html->script('application/application_for_certificate/referred_back_validation'); ?>
		<?php } ?>

		<?php if ($final_submit_status == 'referred_back' && $current_form_data['customer_reply'] !='') { ?>
			<?php echo $this->Html->script('application/application_for_certificate/customer_reply_validation'); ?>
		<?php } ?>

		<?php if ($_SESSION['application_type']==3) { ?>
			<?php echo $this->Html->script('application/application_for_certificate/application_type'); ?>
		<?php } ?>

	<?php }

	$section_form_details = $section_form_details[0];

	if ($section_form_details['form_status'] == 'approved') { ?>
		<?php echo $this->Html->script('application/application_for_certificate/form_status_approved'); ?>
	<?php } ?>


	<?php echo $this->Form->control('changefields', array('type'=>'hidden', 'id'=>'changefields', 'value'=>$changefields,'label'=>false,)); ?>

	<input type="hidden" id="oldapplication_call" value="<?php echo $oldapplication; ?>">

	<?php echo $this->Html->script('application/application_for_certificate/application_for_certificate'); ?>
