<?php ?>

	<label>Enter Business Type <span class="cRed">*</span></label>
	<div class="col-md-6">
		<?php echo $this->Form->control('business_type', array('type'=>'text', 'id'=>'business_type','label'=>false, 'placeholder'=>'Enter Business Type Here','class'=>'form-control','required'=>true)); ?>
		<span id="error_business_type" class="error invalid-feedback"></span>
	</div>


	<div class="col-md-1 float-right">
		<?php echo $this->element('masters_management_elements/add_submit_common_btn'); ?>
	</div>
