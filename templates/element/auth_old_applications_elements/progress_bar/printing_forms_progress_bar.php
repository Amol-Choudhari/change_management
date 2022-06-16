<?php ?>
	<div class="progress">
		<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/printing_firm_profile">
			<div id="firm_profile" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
				Firm Profile <span id="firm_profile_span" class="far fa-trash-alt"></span>
				<?php  echo $this->Form->control('printing_firm_form_status', array('type'=>'hidden', 'id'=>'printing_firm_form_status', 'value'=>$printing_firm_form_status, 'class'=>'input-field', 'label'=>false)); ?>
			</div>
		</a>

		<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/printing_premises_profile">
			<div id="premises_profile" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" >
				Premises Profile <span id="premises_profile_span" class="far fa-trash-alt"></span>
				<?php echo $this->Form->control('printing_premises_form_status', array('type'=>'hidden', 'id'=>'printing_premises_form_status', 'value'=>$printing_premises_form_status, 'class'=>'input-field', 'label'=>false)); ?>
			</div>
		</a>

		<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/printing_unit_details">
			<div id="printing_unit_details" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
				Unit Details <span id="printing_unit_span" class="far fa-trash-alt"></span>
				<?php echo $this->Form->control('printing_unit_form_status', array('type'=>'hidden', 'id'=>'printing_unit_form_status', 'value'=>$printing_unit_form_status, 'class'=>'input-field', 'label'=>false)); ?>
			</div>
		</a>
	</div>

<?php echo $this->Html->script('element/auth_old_applications_elements/progress_bar/printing_forms_progress_bar'); ?>
