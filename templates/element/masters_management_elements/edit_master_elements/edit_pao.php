<?php
	echo $this->Html->css('../multiselect/jquery.multiselect');
	echo $this->Html->script('../multiselect/jquery.multiselect');
?>


<div class="col-md-12">
	<div class="row">
		<div class="col-md-6">
			<label>PAO/DDO Email ID</label>
			<?php echo $this->Form->control('pao_email_id', array('type'=>'text', 'id'=>'pao_email_id', 'value'=>base64_decode($selected_pao_email_id['email']), 'label'=>false, 'readonly'=>true, 'class'=>'form-control')); //for email encoding ?>
			<div id="error_pao_email_id"></div>
		</div>
		<div class="col-md-6">
			<label>PAO/DDO Alias Name</label>
			<?php echo $this->Form->control('pao_alias_name', array('type'=>'text', 'id'=>'pao_alias_name', 'value'=>$pao_alias_name, 'label'=>false, 'class'=>'form-control'/*'readonly'=>true*/)); ?>
			<div id="error_pao_alias_name"></div>
		</div>
		<div class="clearfix"></div>

		<div class="col-md-6">
			<label>Allocate State List</label>
			<?php echo $this->Form->control('state_list', array('type'=>'select', 'id'=>'state_list', 'value'=>$selected_state_list, 'options'=>$all_states, 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control')); ?>
			<div id="error_district_list"></div>
		</div>
		<div class="col-md-6" id="update_district_div">
			<label>Allocate District List</label>
			<?php echo $this->Form->control('district_list', array('type'=>'select', 'id'=>'district_list', 'value'=>$selected_district_list, 'options'=>$district_name_list, 'multiple'=>'multiple', 'label'=>false, 'class'=>'form-control')); ?>
			<?php echo $this->Form->control('district_option', array('type'=>'hidden', 'id'=>'district_option', 'label'=>false,)); ?>
			<div id="error_district_list"></div>
		</div>
		<div class="col-md-2 mt-2">
			<?php echo $this->Form->submit('Edit PAO/DDO', array('name'=>'edit_pao', 'id'=>'add_pao_btn', 'label'=>false, 'class'=>'btn btn-success')); ?>
		</div>
	</div>
</div>

<?php echo $this->Html->script('element/masters_management_elements/edit_pao'); ?>
