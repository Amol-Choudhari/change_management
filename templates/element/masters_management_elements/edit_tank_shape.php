<?php  ?>
	<label>Enter Tank Shape <span class="cRed">*</span></label>
		<div class="col-md-7">
			<?php echo $this->Form->control('tank_shapes', array('type'=>'text', 'id'=>'tank_shapes','label'=>false, 'value'=>$record_details['tank_shapes'],'class'=>'form-control')); ?>	
			<div id="error_tank_shape"></div>
		</div>	


	<div class="col-md-2">			
		<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
	</div>
		