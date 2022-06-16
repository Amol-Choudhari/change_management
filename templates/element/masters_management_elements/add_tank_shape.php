<?php ?>
<label>Enter Tank Shape <span class="cRed">*</span></label>

	<div class="col-md-7">
		<?php echo $this->Form->control('tank_shapes', array('type'=>'text', 'id'=>'tank_shapes','label'=>false, 'placeholder'=>'Enter Tank Shape Here','class'=>'form-control','required'=>true)); ?>
		<span id="error_tank_shape" class="error invalid-feedback"></span>
	</div>


	<div class="col-md-1 float-right">
		<?php echo $this->element('masters_management_elements/add_submit_common_btn'); ?>
	</div>

	<div class="clearfix"></div>

</div>
