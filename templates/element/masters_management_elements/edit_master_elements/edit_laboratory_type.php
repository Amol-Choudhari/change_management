<?php  ?>
	<label>Laboratory Type <span class="cRed">*</span></label>
		<div class="col-md-7">
			<?php echo $this->Form->control('laboratory_type', array('type'=>'text', 'id'=>'laboratory_type','label'=>false, 'value'=>$record_details['laboratory_type'],'class'=>'form-control')); ?>	
		<div id="error_laboratory_type"></div>
	</div>	


	<div class="col-md-2">			
		<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
	</div>
	
	