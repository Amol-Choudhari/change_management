<?php ?>
	<label class="col-md-4">Enter Machine Type <span class="cRed">*</span></label>
		<div class="col-md-6">
			<?php echo $this->Form->control('machine_types', array('type'=>'text', 'id'=>'machine_types','label'=>false, 'placeholder'=>'Enter Machine Type Here','class'=>'form-control','required'=>true)); ?>
			<span id="error_machine_type" class="error invalid-feedback"></span>
		</div>
		<div class="col-md-12 mt-3">
			<div class="row">
				<div class="col-md-6">
					<label class="badge badge-primary">Application Type :	</label>
					<?php
						$options=array('ca'=>'CA','printing'=>'Printing');
						$attributes=array('legend'=>false, 'value'=>'ca', 'id'=>'application_type');
						echo $this->form->radio('application_type',$options,$attributes);
					?>
					<span id="error_application_type" class="error invalid-feedback"></span>
				</div>

				<div class="col-md-1 offset-3 flaot-right">
					<?php echo $this->element('masters_management_elements/add_submit_common_btn'); ?>
				</div>
			</div>
