<?php ?>
		<label>Enter Machine Type <span class="cRed">*</span></label>

	<div class="col-md-7">
			<?php echo $this->Form->control('machine_types', array('type'=>'text', 'id'=>'machine_types','label'=>false, 'value'=>$record_details['machine_types'],'class'=>'form-control')); ?>	
		<div id="error_machine_type"></div>
	</div>
	
	<div class="col-md-12 mt-3">
		<div class="row">
		<div class="col-md-6">
			<label class="badge badge-primary">Application Type :	</label>
				<?php 					
					$options=array('ca'=>'CA','printing'=>'Printing');
					$attributes=array('legend'=>false, 'value'=>$record_details['application_type'], 'id'=>'application_type');		
					echo $this->form->radio('application_type',$options,$attributes); 
				?>
			<div id="error_application_type"></div>
		</div>

		<div class="col-md-2 offset-2 float-right">			
			<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
		</div>
	</div>
</div>