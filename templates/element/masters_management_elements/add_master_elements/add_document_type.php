<?php ?>

	<label>Enter Document Type <span class="cRed">*</span></label>
	<div class="col-md-6">
		<?php echo $this->Form->control('document_name', array('type'=>'text', 'id'=>'document_name','label'=>false, 'placeholder'=>'Enter document name Type Here','class'=>'form-control','required'=>true)); ?>
		<span id="error_document_name" class="error invalid-feedback"></span>
	</div>


	<div class="col-md-1 float-right">
		<?php echo $this->element('masters_management_elements/add_submit_common_btn'); ?>
	</div>
