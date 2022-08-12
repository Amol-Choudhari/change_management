<?php
//taking 'years for' value from option session variable
//conditional column name due to 3 diff. tables.
	$years_for = $_SESSION['edit_optional_param'];
	$options = array('0'=>'CA','1'=>'Printing Press','2'=>'Crushing & Refining');

	if ($years_for=='0' || $years_for=='1') {

		$business_years='business_years';

	} elseif ($years_for=='2') {

		$business_years='crushing_refining_periods';
	}
?>

	<div class="col-md-6">
		<div class="form-group">
			<label>Enter value Here <span class="cRed">*</span></label>
				<?php echo $this->Form->control('business_years', array('type'=>'text', 'id'=>'business_years','label'=>false, 'value'=>$record_details[$business_years],'class'=>'form-control')); ?>
			<div id="error_business_year"></div>
		</div>
	</div>

	<div class="col-md-6">
		<label>Select Business Years for:</label>
			<?php echo $this->Form->control('business_years_for', array('type'=>'select', 'id'=>'business_years_for','label'=>false, 'options'=>$options, 'value'=>$years_for,'class'=>'form-control','readonly'=>true)); ?>
			<div id="error_business_years_for"></div>
	</div>

	<div class="col-md-2">
			<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
	</div>

	<?php echo $this->Html->script('element/masters_management_elements/edit_business_year'); ?>
