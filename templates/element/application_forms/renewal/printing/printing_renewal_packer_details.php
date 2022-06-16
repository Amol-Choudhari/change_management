<?php ?>
	<div id="renew_last_validity_period" class="machinery_table table-format form-middle">
		<table class="table table-stripped table-bordered m-0" id="packer_table_detail">
			<tr>
				<th class="tablehead">Sr.No</th>
				<th class="tablehead">Packer Name</th>
				<th class="tablehead">Type</th>
				<th class="tablehead">Quantity Printed(Nos)</th>
				<th class="tablehead acols">Action</th>
			</tr>

			<div id="machinery_each_row">
				<?php
				$i=1;
				foreach($section_form_details[3][0] as $each_packer){ ?>

					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $each_packer['packer_name']; ?></td>
						<td><?php echo $section_form_details[3][3][$i]; ?></td>
						<td><?php echo $each_packer['quantity_printed']; ?></td>
						<td class="acols">
							<a href="#" class="edit_packer_id far fa-edit packer_edit" id="<?php echo $each_packer['id']; ?>"></a>|
							<a href="#" class="delete_packer_id far fa-trash-alt packer_delete" id="<?php echo $each_packer['id']; ?>"></a>
						</td>
					</tr>

				<?php $i=$i+1; } ?>


			<!-- for edit machine details -->
			<?php if($this->getRequest()->getSession()->read('edit_packer_id') != null){  ?>
				<tr>
					<td></td>
					<td><input type="text" list="packer_name_list" name="packer_name" id='packer_name' class="input-field" value="<?php echo $find_packers_details['packer_name'];?>"><datalist id="packer_name_list" ><?php foreach($section_form_details[3][1] as $packer_name){ ?> <option value="<?php echo $packer_name; ?>"><?php } ?></datalist>
					<?php //echo $this->form->input('packer_name', array('type'=>'select', 'id'=>'packer_name', 'value'=>$find_packers_details['Dmi_renewal_packer_detail']['packer_name'], 'options'=>$packer_renewal_name, 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('packer_type', array('type'=>'select', 'id'=>'packer_type', 'value'=>$packer_type_value_edit, 'options'=>$section_form_details[3][4], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('quantity_printed', array('type'=>'text', 'id'=>'quantity_printed','value'=>$find_packers_details['quantity_printed'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><div class="form-buttons"><a href="#" id="save_packer_details">Save</a></div></td>

						<?php //echo $this->form->submit('Save', array('name'=>'edit_packer_details', 'id'=>'edit_packer_details', 'label'=>false)); ?>
				</tr>

			<!-- To show added and save new machine details -->
			<?php }else{?>
				<div id="add_new_row">
				<tr id="add_new_row_r">
					<td></td>
					<td><input type="text" list="packer_name_list" name="packer_name" id='packer_name' class="input-field"><datalist id="packer_name_list" ><?php foreach($section_form_details[3][1] as $packer_name){ ?> <option value="<?php echo $packer_name; ?>"><?php } ?></datalist></td>
					<td><?php echo $this->Form->control('packer_type', array('type'=>'select', 'id'=>'packer_type', 'options'=>$section_form_details[3][4], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><?php echo $this->Form->control('quantity_printed', array('type'=>'text', 'id'=>'quantity_printed', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
					<td><div class="form-buttons"><a href="#" id="add_packer_details" >Add</a></div></td>

						<?php //echo $this->form->submit('Add', array('name'=>'add_packer_details', 'id'=>'add_packer_details', 'label'=>false, 'onclick'=>'packer_table_validation();return false')); ?>
				</tr>
				</div>
				<div id="error_quantity_printed"></div>
				<div id="error_packer_name"></div>
			<?php } ?>
			</div>
		</table>
	</div>
<?php echo $this->Html->script('element/application_forms/renewal/printing/printing_renewal_packer_details'); ?>
