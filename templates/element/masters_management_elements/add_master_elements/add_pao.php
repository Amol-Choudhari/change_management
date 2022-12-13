<?php
	echo $this->Html->css('../multiselect/jquery.multiselect');
	echo $this->Html->script('../multiselect/jquery.multiselect');
?>

<div class="col-md-12">
	<div class="row">
		<div class="col-md-4">
			<label>PAO/DDO Email ID</label>
			<?php echo $this->Form->control('pao_email_id', array('type'=>'select', 'id'=>'pao_email_id', 'options'=>$pao_email_id_list,'class'=>'form-control',  'label'=>false)); ?>
			<div id="error_pao_email_id"></div>
				</div>
				<div class="col-md-4">
					<label>PAO/DDO Alias Name</label>
					<?php echo $this->Form->control('pao_alias_name', array('type'=>'text', 'id'=>'pao_alias_name', 'class'=>'form-control',  'label'=>false, 'placeholder'=>'Enter PAO/DDO Alias Name Here','required'=>true)); ?>	
					<div id="error_pao_alias_name"></div>
				</div>
				<div class="clearfix"></div>
				<div class="col-md-4">
					<label>Allocate State List</label>
					<?php echo $this->Form->control('state_list', array('type'=>'select', 'id'=>'state_list', 'options'=>$all_states, 'multiple'=>'multiple',  'label'=>false)); ?>
					<div id="error_district_list"></div>
				</div>
				<div class="col-md-4" id="update_district_div">
					<label>Allocate District List</label>
					<?php echo $this->Form->control('district_list', array('type'=>'select', 'id'=>'district_list', /*'options'=>$district_name_list,*/ 'multiple'=>'multiple',  'label'=>false)); ?>
					<?php echo $this->Form->control('district_option', array('type'=>'hidden', 'id'=>'district_option', 'label'=>false,)); ?>
					<div id="error_district_list"></div>
				</div>
				

				<div class="col-md-3">
					<!--Check pao user and district name availability (Done By pravin 25/10/2017)-->
					<?php if(empty($pao_email_id_list)){ ?>

					<label class="badge badge-info"> User with role PAO/DDO are all set </label>
					<?php } ?>

					<?php if(empty($district_name_list)){ ?>

					<label class="badge badge-info"> No district remaining to set for PAO/DDO </label>

					<?php } ?>
				</div>
			</div>
		</div>
	<div class="col-md-2 mt-3">
		<?php echo $this->Form->submit('Add PAO/DDO', array('name'=>'add_pao', 'id'=>'add_pao_btn','class'=>'btn btn-success', 'label'=>false)); ?>
	</div>

<?php echo $this->Html->script('element/masters_management_elements/add_pao'); ?>
