<?php ?>
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6">
				<label>Category <span class="cRed">*</span></label>
				<?php echo $this->Form->control('commodity', array('type'=>'select', 'id'=>'commodity_category','options'=>$commodity_categories, 'label'=>false,'disabled','class'=>'form-control'));?>
				<span class="error invalid-feedback" id="error_commodity_category"></span>
			</div>
			<div class="col-md-6">
				<label>Commodity Name  <span class="cRed">*</span></label>
				<?php echo $this->Form->control('sub_commodity', array('type'=>'text','id'=>'commodity','escape'=>false,'value'=>$entered_commodity, 'label'=>false,'disabled','class'=>'form-control')); ?>
				<span class="error invalid-feedback" id="error_commodity"></span>
			</div>
		</div>
	</div>
	<div class="col-md-12 mt-3">
		<div class="row">
			<div class="col-md-6">
				<label>Charges <span class="cRed">*</span></label>
				<?php echo $this->Form->control('replica_charges', array('type'=>'text', 'placeholder'=>'Enter Replica Charges', 'id'=>'replica_charges','value'=>$entered_charge, 'class'=>'form-control', 'label'=>false)); ?>
				<span class="error invalid-feedback" id="error_replica_charges"></span>
			</div>
			<div class="col-md-6">
				<label>Minimum Qty <span class="cRed">*</span></label>
				<?php echo $this->Form->control('minimum_quantity', array('type'=>'text', 'placeholder'=>'Enter Minimum Qty', 'id'=>'min_qty','value'=>$entered_qty, 'class'=>'form-control', 'label'=>false)); ?>
				<span class="error invalid-feedback" id="error_min_qty"></span>
			</div>
			<div class="col-md-6 mt-2">
				<label>Unit <span class="cRed">*</span></label>
					<select name="unit" id="unit" disabled class="form-control">
						<option value="Kg"<?php if ($selected_unit == 'Kg') echo ' selected="selected"'; ?>>Kg</option>
						<option value="Ltr"<?php if ($selected_unit == 'Ltr') echo ' selected="selected"'; ?>>Ltr</option>
						<option value="Nos"<?php if ($selected_unit == 'Nos') echo ' selected="selected"'; ?>>Nos</option>
					</select>
					<span class="error invalid-feedback" id="error_unit"></span>
			</div>
			<div class="col-md-6 mt-2">
					<label class="col-from-label">Commodity Code for 15-Digit Code  <span class="cRed">*</span></label>
						<?php echo $this->form->control('replica_code', array('type'=>'text','value' => $replica_code, 'placeholder'=>'Enter Commodity Code for 15-Digit Code', 'id'=>'replica_code', 'class'=>'form-control fifteenDigitUpper', 'label'=>false,'required'=>true)); ?>
						<span class="error invalid-feedback" id="error_replica_code"></span>
				</div>
		</div>
	</div>
	<div class="col-md-3 mt-3">
		<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
	</div>

	<?php echo $this->Html->script('element/masters_management_elements/edit_replica_charges'); ?>