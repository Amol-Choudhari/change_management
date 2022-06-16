<?php ?>

<div class="table-format">
	<table id="machinery_table" class="table table-bordered table-striped">
		<tr>
			<th class="tablehead">Sr.No.</th>
			<th class="tablehead">Name</th>
			<th class="tablehead">Type</th>
			<th class="tablehead">No.</th>
			<th class="tablehead">Capacity(Qtl/day)</th>
			<th class="tablehead">Action</th>
		</tr>

		<div id="machinery_each_row">

			<?php
			$i=1;
			foreach($added_machines_details as $each_machine){ ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $each_machine['machine_name']; ?></td>
				<td><?php echo $show_machine_type[$i]; ?></td>
				<td><?php echo $each_machine['machine_no']; ?></td>
				<td><?php echo $each_machine['machine_capacity']; ?></td>
				<td>
					<a href="#" class="edit_machine_id far fa-edit machine_edit" id="<?php echo $each_machine['id']; ?>" ></a> |
					<a href="#" class="delete_machine_id far fa-trash-alt machine_delete" id="<?php echo $each_machine['id']; ?>" ></a>

					<?php //echo $this->Html->link('', array('controller' => 'customerforms', 'action'=>'edit_machine_id',$each_machine['Dmi_all_machines_detail']['id']),array('class'=>'far fa-edit machine_edit', 'title'=>'Edit')); ?>
					<?php //echo $this->Html->link('', array('controller' => 'customerforms', 'action'=>'delete_machine_id',$each_machine['Dmi_all_machines_detail']['id']),array('class'=>'far fa-trash-alt machine_delete', 'title'=>'Delete')); ?>
				</td>
			</tr>
			<?php $i=$i+1; } ?>
			<div id="error_machinery"></div>

		<!-- for edit machine details -->
		<?php if($this->Session->read('edit_machine_id') != null){?>
			<tr>
				<td></td>
				<td><?php echo $this->Form->control('machine_name', array('type'=>'text', 'id'=>'machine_name', 'value'=>$find_machines_details['machine_name'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('machine_type', array('type'=>'select', 'id'=>'machine_type', 'value'=>$machine_type_value_edit, 'options'=>$machines_types, 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('machine_no', array('type'=>'text', 'id'=>'machine_no', 'value'=>$find_machines_details['machine_no'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('machine_capacity', array('type'=>'text', 'id'=>'machine_capacity', 'value'=>$find_machines_details['machine_capacity'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td>
					<div class="form-buttons"><a href="#" id="save_machine_details">Save</a></div>
					<?php //echo $this->form->submit('save', array('name'=>'edit_machine_details', 'id'=>'edit_machine_details', 'onclick'=>'validate_machinery_details();return false', 'label'=>false)); ?>
				</td>
			</tr>

		<!-- To show added and save new machine details -->
		<?php }else{?>
			<div id="add_new_row">
			<tr>
				<td></td>
				<td><?php echo $this->Form->control('machine_name', array('type'=>'text', 'id'=>'machine_name', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('machine_type', array('type'=>'select', 'id'=>'machine_type', 'options'=>$machines_types, 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('machine_no', array('type'=>'text', 'id'=>'machine_no', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('machine_capacity', array('type'=>'text', 'id'=>'machine_capacity', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td>
					<div class="form-buttons"><a href="#" id="add_machine_details" >Add</a></div>
					<?php //echo $this->form->submit('Add', array('name'=>'add_machine_details', 'id'=>'add_machine_details', 'onclick'=>'validate_machinery_details();return false', 'label'=>false)); ?>
				</td>
			</tr>
				<div id="error_machine_name"></div>
				<div id="error_machine_type"></div>
				<div id="error_machine_no"></div>
				<div id="error_machine_capacity"></div>
			</div>
		<?php } ?>
		</div>
	</table>

</div>

<?php echo $this->Html->script('element/auth_old_applications_elements/ca_other_tables_elements/machine_details_table_view'); ?>
