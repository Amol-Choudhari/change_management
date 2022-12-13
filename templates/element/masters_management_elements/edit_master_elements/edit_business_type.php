<?php  ?>


<label>Edit Business Type <span class="cRed">*</span></label>

	<div class="col-md-7">
		<?php echo $this->Form->control('business_type', array('type'=>'text', 'id'=>'business_type','label'=>false, 'value'=>$record_details['business_type'],'class'=>'form-control')); ?>	
		<div id="error_business_type"></div>
	</div>	


	<div class="col-md-2 mt-2">			
		<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
	</div>
	
	<div class="clearfix"></div>
	
