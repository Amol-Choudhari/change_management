<?php ?>

	<table id="const_oil_mills_table" class="table table-bordered table-striped m-0">
		<thead  class="tablehead">
			<th>Sr.No.</th>
			<th>Name of Oil</th>
			<th>Name & Address of Oil mill</th>
			<th>Quantity Procured</th>
			<th>Action</th>
		</thead>
		
		<div id="oils_each_row">
		<?php
			$i=1;
			
			foreach ($section_form_details[4] as $each_const_oil_mill) { ?>
				
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $each_const_oil_mill['oil_name']; ?></td>
					<td><?php echo $each_const_oil_mill['mill_name_address']; ?></td>
					<td><?php echo $each_const_oil_mill['quantity_procured']; ?></td>
					<td>
						<a href="#" class="edit_const_oil_mill_id glyphicon glyphicon-edit const_oil_mill_edit" id="<?php echo $each_const_oil_mill['id']; ?>" ></a> | 
						<a href="#" class="delete_const_oil_mill_id glyphicon glyphicon-remove-sign const_oil_mill_delete" id="<?php echo $each_const_oil_mill['id']; ?>" ></a>
					</td>
				</tr>
			
			<?php $i=$i+1; } ?>
			<div id="error_const_oil_mills"></div>

		<!-- for edit mill details -->
		<?php if ($this->getRequest()->getSession()->read('edit_const_oil_mill_id') != null) { ?>

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
		<?php } else { ?>
			
			<div id="add_new_row">
		
				<tr>
					<td></td>
					<td><?php echo $this->Form->control('oil_name', array('type'=>'text', 'id'=>'oil_name', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('mill_name_address', array('type'=>'text', 'id'=>'mill_name_address', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('quantity_procured', array('type'=>'text', 'id'=>'quantity_procured', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td>
					<div class="form-buttons"><a href="#" id="add_const_oil_mill_details" class='btn btn-success table_record_add_btn'>Add</a></div>
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

<?php echo $this->Html->script('element/ca_other_tables_elements/const_oil_mill_details_table_view'); ?>	
	
	
