<?php  ?>

	<div class="col-md-6">
		<label class="col-form-label">Application Type Details <span class="cRed">*</span></label>
		<?php echo $this->Form->control('application_type', array('type'=>'textarea', 'id'=>'application_type', 'value'=>$record_details['application_type'], 'readonly'=>true, 'escape'=>false,'class'=>'form-control','label'=>false)); ?>	
		<span id="error_application_type" class="error invalid-feedback"></span>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label class="col-form-label">Amount (<i class="fas fa-rupee-sign"></i>) <span class="cRed">*</span></label>
			<?php echo $this->Form->control('charge', array('type'=>'text', 'id'=>'charge', 'value'=>$record_details['charge'], 'class'=>'form-control','escape'=>false,'required'=>true,'label'=>false)); ?>	
			<span id="error_charge" class="error invalid-feedback"></span>
		</div>
		<div class="form-group">
			<label class="col-form-label">Application Type <span class="cRed">*</span></label>
			<?php echo $this->Form->control('application_type_id', array('type'=>'select', 'id'=>'application_type_id','value'=>$selectedApplicationType, 'class'=>'form-control','options'=>$applicationTypes,'class'=>'form-control','label'=>false,'empty'=>'--Select Application Type--','readonly'=>true)); ?>
			<span id="error_application_type_id" class="error invalid-feedback"></span>
		</div>
		<div class="form-group">
			<label class="col-form-label">Firm Type <span class="cRed">*</span></label>
			<?php echo $this->Form->control('firm_type', array('type'=>'select', 'id'=>'firm_type','value'=>$selectedFirmType, 'options'=>$firmType,'class'=>'form-control','label'=>false,'empty'=>'--Select Firm Type--','readonly'=>true)); ?>
			<span id="error_firm_type" class="error invalid-feedback"></span>
		</div>
	</div> 	

	<div class="col-md-2 mt-3">			
		<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
	</div>
	

<?php echo $this->Html->script('element/masters_management_elements/edit_charge'); ?>