<?php ?>

<div class="table-format">
	<table id="commodity_storage_tank_table" class="table m-0 table-bordered table-striped table-responsive">
			<tr>
				<th class="tablehead">Sr.No.</th>
				<th class="tablehead">Room No./Tank No.</th><!-- Changed on 17-08-2022 as per UAT suggestion-->
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
					<a href="#" class="edit_storage_tank_id glyphicon glyphicon-edit tank_edit" id="<?php echo $each_tank['id']; ?>" ></a> | 
						<a href="#" class="delete_storage_tank_id glyphicon glyphicon-remove-sign tank_delete" id="<?php echo $each_tank['id']; ?>" ></a>		
          </td>
				</tr>
				<?php $i=$i+1; } ?>
				<div id="error_commodity_storage_tank"></div>

			<!-- for edit machine details -->
			<?php if($this->request->getSession()->read('edit_storage_tank_id') != null){?>
				<tr>
					<td></td>
					<td><?php echo $this->Form->control('tank_no', array('type'=>'text', 'id'=>'commodity_tank_no', 'value'=>$find_tanks_details['tank_no'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('tank_shape', array('type'=>'select', 'id'=>'commodity_tank_shape', 'value'=>$tank_shape_value_edit, 'options'=>$section_form_details[1][0], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('tank_size', array('type'=>'text', 'id'=>'commodity_tank_size', 'value'=>$find_tanks_details['tank_size'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('tank_capacity', array('type'=>'text', 'id'=>'commodity_tank_capacity', 'value'=>$find_tanks_details['tank_capacity'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td>
						<div class="form-buttons"><a href="#" id="save_storage_tank_details">Save</a></div>
						<?php //echo $this->form->submit('save', array('name'=>'edit_tank_details', 'id'=>'edit_tank_details', 'label'=>false)); ?></td>
				</tr>
			
			<!-- To show added and save new machine details -->
			<?php }else{?>
				<div id="add_new_row">
				<tr>
					<td></td>
					<td><?php echo $this->Form->control('tank_no', array('type'=>'text', 'id'=>'commodity_tank_no', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('tank_shape', array('type'=>'select', 'id'=>'commodity_tank_shape', 'options'=>$section_form_details[1][0], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('tank_size', array('type'=>'text', 'id'=>'commodity_tank_size', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('tank_capacity', array('type'=>'text', 'id'=>'commodity_tank_capacity', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td>
						<div class="form-buttons"><a href="#" id="add_storage_tank_details" class='btn btn-success table_record_add_btn' >Add</a></div>
						<?php //echo $this->form->submit('Add', array('name'=>'add_tank_details', 'id'=>'add_tank_details', 'onclick'=>'validate_commodity_storage_tank();return false', 'label'=>false)); ?>
					</td>
				</tr>
					<div id="error_commodity_tank_no"></div>
					<div id="error_commodity_tank_shape"></div>
					<div id="error_commodity_tank_size"></div>
					<div id="error_commodity_tank_capacity"></div>
				</div>
			<?php } ?>
			</div>
		</table>
</div>	
	
<?php echo $this->Html->script('element/ca_other_tables_elements/storage_tank_details_table_view'); ?>	
	
	
