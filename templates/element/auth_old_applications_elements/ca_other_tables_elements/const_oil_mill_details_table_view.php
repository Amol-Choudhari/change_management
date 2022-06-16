<?php ?>

<div class="table-format">
	<table id="const_oil_mills_table" class="table table-striped table-bordered">
		<tr>
			<th class="tablehead">Sr.No.</th>
			<th class="tablehead">Name of Oil</th>
			<th class="tablehead">Name & Address of Oil mill</th>
			<th class="tablehead">Quantity Procured</th>
			<th class="tablehead">Action</th>
		</tr>

		<div id="oils_each_row">

			<?php
			$i=1;
			foreach($added_const_oil_mill_details as $each_const_oil_mill){ ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $each_const_oil_mill['oil_name']; ?></td>
				<td><?php echo $each_const_oil_mill['mill_name_address']; ?></td>
				<td><?php echo $each_const_oil_mill['quantity_procured']; ?></td>
				<td>
					<a href="#" class="edit_const_oil_mill_id far fa-edit const_oil_mill_edit" id="<?php echo $each_const_oil_mill['id']; ?>" ></a> |
					<a href="#" class="delete_const_oil_mill_id far fa-trash-alt const_oil_mill_delete" id="<?php echo $each_const_oil_mill['id']; ?>" ></a>

					<?php //echo $this->Html->link('', array('controller' => 'customerforms', 'action'=>'edit_const_oil_mill_id',$each_const_oil_mill['Dmi_all_constituent_oils_detail']['id']),array('class'=>'far fa-edit const_oil_mill_edit', 'title'=>'Edit')); ?>
					<?php //echo $this->Html->link('', array('controller' => 'customerforms', 'action'=>'delete_const_oil_mill_id',$each_const_oil_mill['Dmi_all_constituent_oils_detail']['id']),array('class'=>'far fa-trash-alt const_oil_mill_delete', 'title'=>'Delete')); ?>
				</td>
			</tr>
			<?php $i=$i+1; } ?>
			<div id="error_const_oil_mills"></div>

		<!-- for edit mill details -->
		<?php if($this->Session->read('edit_const_oil_mill_id') != null){?>
			<tr>
				<td></td>
				<td><?php echo $this->Form->control('oil_name', array('type'=>'text', 'id'=>'oil_name', 'value'=>$find_const_oil_mill_details['oil_name'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('mill_name_address', array('type'=>'text', 'id'=>'mill_name_address', 'value'=>$find_const_oil_mill_details['mill_name_address'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('quantity_procured', array('type'=>'text', 'id'=>'quantity_procured', 'value'=>$find_const_oil_mill_details['quantity_procured'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td>
					<div class="form-buttons"><a href="#" id="save_const_oil_mill_details">Save</a></div>
					<?php //echo $this->form->submit('save', array('name'=>'edit_const_oil_mill_details', 'id'=>'edit_const_oil_mill_details', 'label'=>false)); ?>
				</td>
			</tr>

		<!-- To show added and save new mill details -->
		<?php }else{?>
			<div id="add_new_row">
			<tr>
				<td></td>
				<td><?php echo $this->Form->control('oil_name', array('type'=>'text', 'id'=>'oil_name', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('mill_name_address', array('type'=>'text', 'id'=>'mill_name_address', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('quantity_procured', array('type'=>'text', 'id'=>'quantity_procured', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td>
					<div class="form-buttons"><a href="#" id="add_const_oil_mill_details" >Add</a></div>
					<?php //echo $this->form->submit('Add', array('name'=>'add_const_oil_mill_details', 'id'=>'add_const_oil_mill_details', 'onclick'=>'validate_const_oil_mills();return false', 'label'=>false)); ?>
				</td>
			</tr>
				<div id="error_oil_name"></div>
				<div id="error_mill_name_address"></div>
				<div id="error_quantity_procured"></div>

			</div>
		<?php } ?>
		</div>
	</table>
</div>
<?php echo $this->Html->script('element/auth_old_applications_elements/ca_other_tables_elements/const_oil_mill_details_table_view'); ?>
