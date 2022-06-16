<?php

$current_level = 'level_1';

echo $this->Form->control('current_level', array('type'=>'hidden', 'id'=>'current_level', 'value'=>$current_level, 'class'=>'input-field', 'label'=>false));

?>






<div class="progress">
    <a href="<?php echo $this->request->getAttribute('webroot');?>oldappinspections/printing_firm_profile_inspect"><div id="firm_profile" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
		Firm Profile <span id="firm_profile_span" class="far fa-trash-alt"></span>
		<?php echo $this->Form->control('printing_firm_form_status', array('type'=>'hidden', 'id'=>'printing_firm_form_status', 'value'=>$printing_firm_form_status, 'class'=>'input-field', 'label'=>false)); ?>
		<?php echo $this->Form->control('printing_firm_reply_status', array('type'=>'hidden', 'id'=>'printing_firm_reply_status', 'value'=>$printing_firm_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
		<?php echo $this->Form->control('printing_firm_current_level', array('type'=>'hidden', 'id'=>'printing_firm_current_level', 'value'=>$printing_firm_current_level, 'class'=>'input-field', 'label'=>false)); ?>
	</div></a>

    <a href="<?php echo $this->request->getAttribute('webroot');?>oldappinspections/printing_premises_profile_inspect"><div id="premises_profile" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
		Premises Profile <span id="premises_profile_span" class="far fa-trash-alt"></span>
		<?php echo $this->Form->control('printing_premises_form_status', array('type'=>'hidden', 'id'=>'printing_premises_form_status', 'value'=>$printing_premises_form_status, 'class'=>'input-field', 'label'=>false)); ?>
		<?php echo $this->Form->control('printing_premises_profile_reply_status', array('type'=>'hidden', 'id'=>'printing_premises_profile_reply_status', 'value'=>$printing_premises_profile_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
		<?php echo $this->Form->control('printing_premises_profile_current_level', array('type'=>'hidden', 'id'=>'printing_premises_profile_current_level', 'value'=>$printing_premises_profile_current_level, 'class'=>'input-field', 'label'=>false)); ?>
	</div></a>

	<a href="<?php echo $this->request->getAttribute('webroot');?>oldappinspections/printing_unit_details_inspect"><div id="printing_unit_details" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
		Unit Details <span id="printing_unit_span" class="far fa-trash-alt"></span>
		<?php echo $this->Form->control('printing_unit_form_status', array('type'=>'hidden', 'id'=>'printing_unit_form_status', 'value'=>$printing_unit_form_status, 'class'=>'input-field', 'label'=>false)); ?>
		<?php echo $this->Form->control('printing_unit_reply_status', array('type'=>'hidden', 'id'=>'printing_unit_reply_status', 'value'=>$printing_unit_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
		<?php echo $this->Form->control('printing_unit_current_level', array('type'=>'hidden', 'id'=>'printing_unit_current_level', 'value'=>$printing_unit_current_level, 'class'=>'input-field', 'label'=>false)); ?>
   </div></a>


	<!--<a href="#"><div id="payment" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
		Payment <span id="payment_span" class="far fa-trash-alt"></span>
		<?php //echo $this->Form->control('payment_status', array('type'=>'hidden', 'id'=>'payment_status', 'value'=>$printing_payment_form_status, 'class'=>'input-field', 'label'=>false)); ?>
		<?php //echo $this->Form->control('payment_reply_status', array('type'=>'hidden', 'id'=>'payment_reply_status', 'value'=>$printing_payment_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
		<?php //echo $this->Form->control('printing_payment_current_level', array('type'=>'hidden', 'id'=>'printing_payment_current_level', 'value'=>$printing_payment_current_level, 'class'=>'input-field', 'label'=>false)); ?>
	</div></a>-->

</div>

<?php echo $this->Html->script('element/old_applications_elements/progress_bar/printing_inspection_progress_bar'); ?>
