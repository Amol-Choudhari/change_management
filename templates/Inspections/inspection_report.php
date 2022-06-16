<div class="content-wrapper">
<?php echo $this->Form->control('form_section_id', array('type'=>'hidden', 'id'=>'form_section_id', 'value'=>$section,'label'=>false,)); ?>
<?php   echo $this->element($section_details['section_path']); ?>
<div class="form-style-3 pd0i">
	<?php if ($section_details['comment_section'] == 'yes' && !empty($final_submit_status) && $fromHoLevel==null) {
		  echo $this->element('siteinspection_forms/communication/communication-window');
		 }
	?>
</div>

<?php echo $this->element('action_buttons/report_buttons'); ?>
<?php echo $this->Form->end(); ?>

<!-- Call element of declaration message box out of form tag on 31-05-2021 by Amol for Form base method -->

<!-- for Report esign -->
	<?php if ($current_level == 'level_2' && ($application_type == 1 || $application_type == 5 || $application_type == 6) && $form_type != 'F' ) {
				echo $this->element('esign_views/declaration-message_boxes'); ?>
		<!-- for Certificate esign -->
	<?php } elseif ($final_granted_btn == 'yes') {
				echo $this->element('esign_views/declaration-message-before-grant');
		  }
	?>

</div>

<input type="hidden" id="current_level" value="<?php echo $_SESSION['current_level']; ?>">
<input type="hidden" id="final_submit_status_ir" value="<?php echo $final_submit_status; ?>">
<input type="hidden" id="section_form_details" value="<?php echo $section_form_details[0]['form_status']; ?>">

<?php echo $this->Html->script('inspection/inspection'); ?>
