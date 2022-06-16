<?php ?>
<div class="table-format">
	<table id="directors_details_table" class="table table-bordered table-striped">
		<tr>
			<th class="tablehead">Sr.No.</th>
			<th class="tablehead">Name</th>
			<th class="tablehead">Address</th>
			<th class="tablehead">Action</th>
		</tr>

		<div id="directors_each_row">

			<?php
			$i=1;
			foreach($added_directors_details as $each_director){ ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $each_director['d_name']; ?></td>
				<td><?php echo $each_director['d_address']; ?></td>
				<td>
					<a href="#" class="edit_directors_details_id far fa-edit director_edit" id="<?php echo $each_director['id']; ?>" ></a> |
					<a href="#" class="delete_directors_details_id far fa-trash-alt director_delete" id="<?php echo $each_director['id']; ?>" ></a>

					<?php //echo $this->Html->link('', array('controller' => 'siteinspections', 'action'=>'edit_directors_details_id',$each_director['Dmi_all_directors_detail']['id']),array('class'=>'far fa-edit directors_details_edit', 'title'=>'Edit')); ?>
					<?php //echo $this->Html->link('', array('controller' => 'siteinspections', 'action'=>'delete_directors_details_id',$each_director['Dmi_all_directors_detail']['id']),array('class'=>'far fa-trash-alt directors_details_delete', 'title'=>'Delete')); ?>
				</td>
			</tr>
			<?php $i=$i+1; } ?>
			<div id="error_directors_details"></div>


		<!-- for edit machine details -->
		<?php if($this->Session->read('edit_directors_details_id') != null){ ?>
			<tr>
				<td></td>
				<td><?php echo $this->form->input('d_name', array('type'=>'text', 'id'=>'d_name', 'value'=>$find_directors_details['d_name'], 'escape'=>false, 'class'=>'input-field mwd100', 'label'=>false)); ?></td>
				<td><?php echo $this->form->input('d_address', array('type'=>'text', 'id'=>'d_address', 'value'=>$find_directors_details['d_address'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				<td><div class="form-buttons"><a href="#" id="save_directors_details">Save</a></div>
					<?php //echo $this->form->submit('save', array('name'=>'edit_directors_details', 'id'=>'edit_directors_details', 'label'=>false)); ?>
				</td>
			</tr>

		<!-- To show added and save new machine details -->
		<?php }else{ ?>
			<div id="add_new_row">
			<tr>
				<td></td>
				<td><?php echo $this->form->input('d_name', array('type'=>'text', 'id'=>'d_name', 'escape'=>false, 'class'=>'input-field mwd100', 'label'=>false, 'placeholder'=>'max. 50 characters allowed')); ?></td>
				<td><?php echo $this->form->input('d_address', array('type'=>'text', 'id'=>'d_address', 'escape'=>false, 'class'=>'input-field', 'label'=>false, 'placeholder'=>'max. 180 characters allowed')); ?></td>
				<td>
					<div class="form-buttons"><a href="#" id="add_directors_details" class='table_record_add_btn'>Add</a></div>
					<?php //echo $this->form->submit('Add', array('name'=>'add_directors_details', 'id'=>'add_directors_details', 'onclick'=>'validate_directors_details();return false', 'label'=>false)); ?>
				</td>
			</tr>
			<div id="error_directors_details_name"></div>
			<div id="error_directors_details_address"></div>

			</div>
		<?php } ?>
		</div>
	</table>

</div>
<?php echo $this->Html->script('element/siteinspection_forms/directors_details_table_view'); ?>
