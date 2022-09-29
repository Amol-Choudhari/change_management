
<?php   if (!empty($section_details['previous_btn'])) { ?>
	<a id="previous_btn" href="<?php echo $this->getAttribute('webroot');?>inspections/section/<?php echo $_SESSION['section_id']-1; ?>" >Previous Section</a>
<?php } ?>
<div id="form_inner_main">
<?php
	if ($section_form_details[0]['form_status'] == 'saved') {$save_btn_lable = 'Update' ; } else { $save_btn_lable = 'Save' ; }
		// call comment box validation function by pravin 13/07/2017
	echo $this->Form->submit($save_btn_lable, array('name'=>'save', 'id'=>'view_save_btn', 'label'=>false));

	echo $this->Form->submit('Submit Final Report', array('name'=>'final_report', 'id'=>'final_report_btn', 'label'=>false));

	echo $this->Form->submit('Accepted', array('name'=>'accepted', 'id'=>'accepted', 'label'=>false));

	echo $this->Form->submit('Forward to HO', array('name'=>'accepted_forward', 'id'=>'accepted_forward_btn', 'label'=>false));

	echo $this->Form->submit('Final Granted', array('name'=>'final_granted', 'id'=>'final_granted_btn', 'label'=>false));

?>
</div>


<input type="hidden" id="validation_function" value="<?php echo $section_details['validation_function']; ?>">
<input type="hidden" id="section_form_id" value="<?php echo $section; ?>">
<input type="hidden" id="show_final_report_btn" value="<?php echo $show_final_report_btn; ?>">
<input type="hidden" id="show_final_granted_btn" value="<?php echo $show_final_granted_btn; ?>">
<input type="hidden" id="show_accept_btn" value="<?php echo $show_accept_btn; ?>">
<input type="hidden" id="show_forward_to_ho_btn" value="<?php echo $show_forward_to_ho_btn; ?>">
<input type="hidden" id="report_edit_mode" value="<?php echo $report_edit_mode; ?>">
<input type="hidden" id="current_level" value="<?php echo $_SESSION['current_level']; ?>">
<input type="hidden" id="report_edit_mode" value="<?php echo $report_edit_mode; ?>">

<?php echo $this->Html->script('element/siteinspection_forms/communication/buttons'); ?>