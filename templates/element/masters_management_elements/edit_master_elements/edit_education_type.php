<?php  ?>
	<label>Education Type <span class="cRed">*</span></label>
		<div class="col-md-7">	
			<?php echo $this->Form->control('education_type', array('type'=>'text', 'id'=>'education_type', 'label'=>false, 'value'=>$record_details['edu_type'],'placeholder'=>'Enter Eduaction Type','class'=>'form-control')); ?>
		<div id="error_education_type"></div>
	</div>	


	<div class="col-md-2">			
		<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
	</div>
