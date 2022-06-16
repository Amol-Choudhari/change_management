
<div class="progress">
    <a href="<?php echo $this->request->getAttribute('webroot');?>oldapplaboratoryforms/laboratory_firm_profile"><div id="laboratory_firm_profile" class="progress-bar progress-bar-danger wd18" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
		Firm Profile <span id="laboratory_firm_profile_span" class="far fa-trash-alt"></span>
		<?php echo $this->Form->control('laboratory_firm_form_status', array('type'=>'hidden', 'id'=>'laboratory_firm_form_status', 'value'=>$laboratory_firm_form_status, 'class'=>'input-field', 'label'=>false)); ?>
    </div></a>

    <a href="<?php echo $this->request->getAttribute('webroot');?>oldapplaboratoryforms/laboratory_firm_other_detail"><div id="laboratory_firm_other_detail" class="progress-bar progress-bar-danger wd20" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
		Other Details <span id="laboratory_firm_other_detail_span" class="far fa-trash-alt"></span>
		<?php echo $this->Form->control('laboratory_other_form_status', array('type'=>'hidden', 'id'=>'laboratory_other_form_status', 'value'=>$laboratory_other_form_status, 'class'=>'input-field', 'label'=>false)); ?>
    </div></a>

	<!--<a href="<?php// echo $this->request->getAttribute('webroot');?>oldlaboratoryforms/payment_modes"><div id="laboratory_payment" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
		Payment <span id="laboratory_payment_span" class="far fa-trash-alt"></span>
		<?php //echo $this->Form->control('laboratory_payment_status', array('type'=>'hidden', 'id'=>'laboratory_payment_status',  'class'=>'input-field', 'label'=>false)); ?>
    </div></a>-->

</div>
<?php echo $this->Html->script('element/old_applications_elements/progress_bar/laboratory_forms_progress_bar'); ?>
