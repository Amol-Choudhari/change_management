<?php ?>



	<label>Enter Feedback Type<span class="cRed">*</span></label>
	<div class="col-md-7">
		<?php echo $this->Form->control('title', array('type'=>'text', 'id'=>'title','label'=>false, 'placeholder'=>'Enter type Here','class'=>'form-control')); ?>
		<span id="error_title" class="error invalid-feedback"></span>
	</div>


	<div class="col-md-1">
		<?php echo $this->element('masters_management_elements/add_submit_common_btn'); ?>
	</div>
