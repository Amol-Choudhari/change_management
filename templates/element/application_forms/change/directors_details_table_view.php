<?php ?>

	<div class="table-format">
		<div id="test"></div>
		<table id="directors_details_table" class="table table-bordered m-0">
			<tr class="tablehead">
				<th>Sr.No.</th>
				<th>Name</th>
				<th>Address</th>
				<th>Action</th>
			</tr>
		
			<div id="directors_each_row">
				<?php
					$i=1;
					foreach ($section_form_details[4] as $each_director) { ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $each_director['d_name']; ?></td>
							<td><?php echo $each_director['d_address']; ?></td>
							<td>
								<a href="#" class="edit_directors_details_id glyphicon glyphicon-edit director_edit" id="<?php echo $each_director['id']; ?>" ></a> |
								<a href="#" class="delete_directors_details_id glyphicon glyphicon-remove-sign director_delete" id="<?php echo $each_director['id']; ?>" ></a>
							</td>
						</tr>
				<?php $i=$i+1; } ?>
				
				<div id="error_directors_details" class="text-red float-right text-sm"></div>

				<!-- for edit machine details -->
				<?php if ($this->request->getSession()->read('edit_directors_details_id') != null) { ?>
					<tr>
						<td></td>
						<td><?php echo $this->Form->control('d_name', array('type'=>'text', 'id'=>'d_name', 'value'=>$find_directors_details['d_name'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?></td>
						<td><?php echo $this->Form->control('d_address', array('type'=>'text', 'id'=>'d_address', 'value'=>$find_directors_details['d_address'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?></td>
						<td>
							<div class="form-buttons"><a href="#" id="save_directors_details" class="btn btn-info btn-sm">Save</a></div>
							<?php //echo $this->form->submit('save', array('name'=>'edit_directors_details', 'id'=>'edit_directors_details', 'label'=>false)); ?>
						</td>
					</tr>

				<!-- To show added and save new machine details -->
				<?php } else { ?>
				
					<div id="add_new_row">
						<tr id="add_new_row_r">
							<td></td>
							<td><?php echo $this->Form->control('d_name', array('type'=>'text', 'id'=>'d_name', 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'max. 50 characters allowed')); ?>
								<span id="error_directors_details_name" class="error invalid-feedback"></span>
							</td>
							<td><?php echo $this->Form->control('d_address', array('type'=>'text', 'id'=>'d_address', 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false, 'placeholder'=>'max. 180 characters allowed')); ?>
								<span id="error_directors_details_address" class="error invalid-feedback"></span>
							</td>
							<td>
								<div class="form-buttons"><a href="#" id="add_directors_details" class="btn btn-info btn-sm"><i class="fa fa-plus"></i> Add</a></div>
								<?php //echo $this->form->submit('Add', array('name'=>'add_directors_details', 'id'=>'add_directors_details', 'onclick'=>'validate_directors_details();return false', 'label'=>false)); ?>
							</td>
						</tr>
					</div>
				<?php } ?>
			</div>
		</table>
	</div>

<?php echo $this->Html->script('element/old_applications_elements/old_app_directors_details_table_view'); ?>
