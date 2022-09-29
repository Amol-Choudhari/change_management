<?php ?>

<div class="table-format">
	<table id="bevo_oil_storage_tank_table" class="table table-stripped table-bordered table-responsive">
		<tr class="tablehead">
			<th>Sr.No.</th>
			<th>Tank No.</th>
			<th>Type/shape</th>
			<th>Size</th>
			<th>Capacity</th>
			<th>Action</th>
		</tr>
		
		<div id="tanks_each_row">
		
			<?php
			$i=1;
			foreach ($section_form_details[3][2] as $each_tank) { ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $each_tank['tank_no']; ?></td>
				<td><?php echo $section_form_details[3][1][$i]; ?></td>
				<td><?php echo $each_tank['tank_size']; ?></td>
				<td><?php echo $each_tank['tank_capacity']; ?></td>
				<td>
					<a href="#" class="edit_bevo_oils_tank_id glyphicon glyphicon-edit tank_edit" id="<?php echo $each_tank['id']; ?>" ></a> | 
					<a href="#" class="delete_bevo_oils_tank_id glyphicon glyphicon-remove-sign tank_delete" id="<?php echo $each_tank['id']; ?>" ></a>
				</td>
			</tr>
			<?php $i=$i+1; } ?>
			
			<div id="error_bevo_oil_storage_tank"></div>
		

			<!-- for edit machine details -->
			<?php if ($this->getRequest()->getSession()->read('edit_bevo_oils_tank_id') != null) { ?>
				<tr>
					<td></td>
					<td><?php echo $this->Form->control('bevo_tank_no', array('type'=>'text', 'id'=>'bevo_tank_no', 'value'=>$find_bevo_oils_tanks_details['tank_no'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('bevo_tank_shape', array('type'=>'select', 'id'=>'bevo_tank_shape', 'value'=>$bevo_oils_tank_shape_value_edit, 'options'=>$section_form_details[3][0], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('bevo_tank_size', array('type'=>'text', 'id'=>'bevo_tank_size', 'value'=>$find_bevo_oils_tanks_details['tank_size'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('bevo_tank_capacity', array('type'=>'text', 'id'=>'bevo_tank_capacity', 'value'=>$find_bevo_oils_tanks_details['tank_capacity'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td>
						<div class="form-buttons"><a href="#" id="save_bevo_oils_tank_details">Save</a></div>
						<?php //echo $this->form->submit('save', array('name'=>'edit_bevo_oils_tank_details', 'id'=>'edit_bevo_oils_tank_details', 'label'=>false)); ?>
					</td>
				</tr>
			
			<!-- To show added and save new machine details -->
			<?php } else { ?>

				<div id="add_new_row">
					<tr>
						<td></td>
						<td><?php echo $this->Form->control('bevo_tank_no', array('type'=>'text', 'id'=>'bevo_tank_no', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
						<td><?php echo $this->Form->control('bevo_tank_shape', array('type'=>'select', 'id'=>'bevo_tank_shape', 'options'=>$section_form_details[3][0], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
						<td><?php echo $this->Form->control('bevo_tank_size', array('type'=>'text', 'id'=>'bevo_tank_size', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
						<td><?php echo $this->Form->control('bevo_tank_capacity', array('type'=>'text', 'id'=>'bevo_tank_capacity', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
						<td>
							<div class="form-buttons"><a href="#" id="add_bevo_oils_tank_details" class='btn btn-success table_record_add_btn'>Add</a></div>
							<?php //echo $this->form->submit('Add', array('name'=>'add_bevo_oils_tank_details', 'id'=>'add_bevo_oils_tank_details', 'onclick'=>'validate_bevo_oil_tank();return false', 'label'=>false)); ?>
						</td>
					</tr>
					<div id="error_bevo_tank_no"></div>
					<div id="error_bevo_tank_shape"></div>
					<div id="error_bevo_tank_size"></div>
					<div id="error_bevo_tank_capacity"></div>
				
				</div>
			<?php } ?>
		</div>
	</table>
</div>

<?php echo $this->Html->script('element/ca_other_tables_elements/bevo_oil_tank_details_table_view'); ?>