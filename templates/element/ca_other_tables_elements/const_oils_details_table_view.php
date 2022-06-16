<?php ?>

<div class="table-format">
	<table id="const_oils_table" class="table m-0 table-bordered table-striped">
		<tr>
			<th class="tablehead">Sr.No.</th>
			<th class="tablehead">Name of Oil</th>
			<th class="tablehead">Name & Address of Oil mill</th>
			<th class="tablehead">Quantity Procured(Qtl)</th>
			<th class="tablehead">Action</th>
		</tr>
		<div id="oils_each_row">
		
			<?php
			$i=1;
			foreach($section_form_details[1] as $each_const_oil){ ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $each_const_oil['oil_name']; ?></td>
				<td><?php echo $each_const_oil['mill_name_address']; ?></td>
				<td><?php echo $each_const_oil['quantity_procured']; ?></td>
				<td>
					<a href="#" class="edit_const_oils_id glyphicon glyphicon-edit const_oil_edit" id="<?php echo $each_const_oil['id']; ?>" ></a> | 
					<a href="#" class="delete_const_oils_id glyphicon glyphicon-remove-sign const_oil_delete" id="<?php echo $each_const_oil['id']; ?>" ></a>
				</td>
			</tr>							
			<?php $i=$i+1; } ?>
			<div id="error_const_oil"></div>
		

		<!-- for edit machine details -->
		<?php if($this->getRequest()->getSession()->read('edit_const_oils_id') != null){?>
			<tr>
				<td></td>
				<td><?php echo $this->Form->control('oil_name', array('type'=>'text', 'id'=>'oil_name', 'value'=>$find_const_oils_details['oil_name'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('mill_name_address', array('type'=>'text', 'id'=>'mill_name_address', 'value'=>$find_const_oils_details['mill_name_address'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('quantity_procured', array('type'=>'text', 'id'=>'quantity_procured', 'value'=>$find_const_oils_details['quantity_procured'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td>
					<div class="form-buttons"><a href="#" id="save_const_oils_details">Save</a></div>
					<?php //echo $this->form->submit('save', array('name'=>'edit_const_oils_details', 'id'=>'edit_const_oils_details', 'onclick'=>'validate_const_oil_details();return false', 'label'=>false)); ?>
				</td>
			</tr>
		
		<!-- To show added and save new machine details -->
		<?php }else{?>
			<div id="add_new_row">
			<tr>
				<td></td>
				<td><?php echo $this->Form->control('oil_name', array('type'=>'text', 'id'=>'oil_name', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('mill_name_address', array('type'=>'text', 'id'=>'mill_name_address', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><?php echo $this->Form->control('quantity_procured', array('type'=>'text', 'id'=>'quantity_procured', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td>
					<div class="form-buttons"><a href="#" id="add_const_oils_details" class='btn btn-success table_record_add_btn'>Add</a></div>
					<?php //echo $this->form->submit('Add', array('name'=>'add_const_oils_details', 'id'=>'add_const_oils_details', 'onclick'=>'validate_const_oil_details();return false', 'label'=>false)); ?>
				</td>
			</tr>
				<div id="error_oil_name_add"></div>
				<div id="error_mill_name_address_add"></div>
				<div id="error_quantity_procured_add"></div>
			</div>
		<?php } ?>
		</div>
	</table>
</div>	
<?php echo $this->Form->control('hidden', array('type'=>'hidden', 'id'=>'cname', 'value'=>$cname,'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?>	
	
<?php echo $this->Html->script('element/ca_other_tables_elements/const_oils_details_table_view'); ?>