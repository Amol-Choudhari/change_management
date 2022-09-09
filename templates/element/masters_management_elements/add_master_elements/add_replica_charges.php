<?php ?>

	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6">
				<label>Category <span class="cRed">*</span></label>
				<?php echo $this->Form->control('commodity', array('type'=>'select', 'id'=>'commodity_category', 'empty'=>'Select Category', 'options'=>$commodity_categories, 'label'=>false, 'class'=>'form-control')); ?>
				<span class="error invalid-feedback" id="error_commodity_category"></span>
			</div>
			<div class="col-md-6">
				<label>Commodity Name <span class="cRed">*</span></label>
				<?php echo $this->Form->control('sub_commodity', array('type'=>'select', 'id'=>'commodity', 'label'=>false,'class'=>'form-control')); ?>
				<span class="error invalid-feedback" id="error_commodity"></span>
			</div>
		</div>
	</div>
		<div class="col-md-12 mt-3">
			<div class="row">
				<div class="col-md-6 mt-2">
					<label>Charges  <span class="cRed">*</span></label>
					<?php echo $this->Form->control('replica_charges', array('type'=>'text', 'placeholder'=>'Enter Replica Charges', 'id'=>'replica_charges', 'class'=>'form-control', 'label'=>false)); ?>
					<span class="error invalid-feedback" id="error_replica_charges"></span>
				</div>

				<div class="col-md-6 mt-2">
					<label>Minimum Quantity  <span class="cRed">*</span></label>
					<?php echo $this->Form->control('minimum_quantity', array('type'=>'text', 'placeholder'=>'Enter Minimum Qunatity', 'id'=>'min_qty', 'class'=>'form-control', 'label'=>false)); ?>
					<span class="error invalid-feedback" id="error_min_qty"></span>
				</div>
				<div class="col-md-6 mt-2">
				<label>Unit <span class="cRed">*</span></label>
					<select name="unit" id="unit" class="form-control">
						<option value="0">Select</option>
						<option value="Kg">Kg</option>
						<option value="Ltr">Ltr</option>
						<option value="Nos">Nos</option>
					</select>
					<span class="error invalid-feedback" id="error_unit"></span>
				</div>
				

				<div class="col-md-6 mt-2">
					<label class="col-from-label">Commodity Code for 15-Digit Code  <span class="cRed">*</span></label>
					<?php echo $this->Form->control('replica_code', array('type'=>'text','placeholder'=>'Enter Commodity Code for 15-Digit Code', 'id'=>'replica_code', 'class'=>'form-control fifteenDigitUpper', 'label'=>false)); ?>
					<span class="error invalid-feedback" id="error_replica_code"></span>
				</div>
			</div>
		</div>
		<div class="col-md-1 mt-3">
			<?php echo $this->element('masters_management_elements/add_submit_common_btn'); ?>
		</div>

<?php echo $this->Html->script('element/masters_management_elements/add_replica_charges'); ?>
