<?php  ?>
	<label>Division Grade <span class="cRed">*</span></label>
		<div class="col-md-7">	
			<?php echo $this->Form->control('division_type', array('type'=>'text', 'id'=>'division_type', 'label'=>false, 'value'=>$record_details['division'],'placeholder'=>'Enter Division Grade','class'=>'form-control')); ?>
		<div id="error_education_type"></div>
	</div>	


	<div class="col-md-2">			
		<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
	</div>
