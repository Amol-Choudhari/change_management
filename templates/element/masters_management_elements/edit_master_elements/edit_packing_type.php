<?php  ?>
	<label>Packing Type <span class="cRed">*</span></label>
		<div class="col-md-7">
			<?php echo $this->Form->control('packing_type', array('type'=>'text', 'id'=>'packing_type','label'=>false, 'value'=>$record_details['packing_type'],'class'=>'form-control')); ?>	
		<div id="error_packing_type"></div>
	</div>	


	<div class="col-md-2">			
		<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
	</div>
		
