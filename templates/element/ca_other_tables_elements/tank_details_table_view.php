<?php ?>

<div class="table-format">
	<table id="tank_table" class="table table-bordered table-striped">
		<tr>
			<th class="tablehead">Sr.No.</th>
			<th class="tablehead">Tank No.</th>
			<th class="tablehead">Type/shape</th>
			<th class="tablehead">Size</th>
			<th class="tablehead">Capacity</th>
			<th class="tablehead">Action</th>
		</tr>
		
		<div id="tanks_each_row">
		
			<?php
			$i=1;
			foreach($section_form_details[1][2] as $each_tank){ ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $each_tank['tank_no']; ?></td>
				<td><?php echo $section_form_details[1][1][$i]; ?></td>
				<td><?php echo $each_tank['tank_size']; ?></td>
				<td><?php echo $each_tank['tank_capacity']; ?></td>
				<td>
					<a href="#" class="edit_tank_id glyphicon glyphicon-edit tank_edit" id="<?php echo $each_tank['id']; ?>" ></a> | 
					<a href="#" class="delete_tank_id glyphicon glyphicon-remove-sign tank_delete" id="<?php echo $each_tank['id']; ?>" ></a>				
				</td>
			</tr>
			<?php $i=$i+1; } ?>
			<div id="error_tanks"></div>
		

		<!-- for edit machine details -->
		<?php if($this->getRequest()->getSession()->read('edit_tank_id') != null){ ?>
			<tr>
				<td></td>
				<td><?php echo $this->Form->control('tank_no', array('type'=>'text', 'id'=>'tank_no', 'value'=>$find_tanks_details['tank_no'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('tank_shape', array('type'=>'select', 'id'=>'tank_shape', 'value'=>$tank_shape_value_edit, 'options'=>$section_form_details[1][0], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('tank_size', array('type'=>'text', 'id'=>'tank_size', 'value'=>$find_tanks_details['tank_size'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('tank_capacity', array('type'=>'text', 'id'=>'tank_capacity', 'value'=>$find_tanks_details['tank_capacity'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td>
					<div class="form-buttons"><a href="#" id="save_tank_details">Save</a></div>
					<?php //echo $this->form->submit('save', array('name'=>'edit_tank_details', 'id'=>'edit_tank_details', 'label'=>false)); ?>
				</td>
			</tr>
		
		<!-- To show added and save new machine details -->
		<?php }else{?>
			<div id="add_new_row">
				<tr>
					<td></td>
					<td><?php echo $this->Form->control('tank_no', array('type'=>'text', 'id'=>'tank_no', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('tank_shape', array('type'=>'select', 'id'=>'tank_shape', 'options'=>$section_form_details[1][0], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('tank_size', array('type'=>'text', 'id'=>'tank_size', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('tank_capacity', array('type'=>'text', 'id'=>'tank_capacity', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td>
						<div class="form-buttons"><a href="#" id="add_tank_details" class='btn btn-success table_record_add_btn'>Add</a></div>
						<?php //echo $this->form->submit('Add', array('name'=>'add_tank_details', 'id'=>'add_tank_details', 'onclick'=>'validate_tanks_details();return false', 'label'=>false)); ?>
					</td>
				</tr>
				<div id="error_tank_no"></div>
				<div id="error_tank_shape"></div>
				<div id="error_tank_size"></div>
				<div id="error_tank_capacity"></div>
			</div>
		<?php } ?>
		</div>
	</table>
</div>	
<?php echo $this->Form->control('hidden', array('type'=>'hidden', 'id'=>'cname', 'value'=>$cname,'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?>		
	
	

<?php echo $this->Html->script('element/ca_other_tables_elements/tank_details_table_view'); ?>		
