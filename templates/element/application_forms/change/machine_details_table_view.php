<?php ?>

	<div class="table-format">
		<table id="machinery_table" class="table table-bordered table-striped table-responsive">
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
					foreach ($section_form_details[6][2] as $each_machine) { ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $each_machine['machine_name']; ?></td>
							<td><?php echo $section_form_details[6][0][$each_machine['machine_type']]; ?></td>
							<td><?php echo $each_machine['machine_no']; ?></td>
							<td><?php echo $each_machine['machine_capacity']; ?></td>
							<td>
								<a href="#" class="edit_machine_id glyphicon glyphicon-edit machine_edit" id="<?php echo $each_machine['id']; ?>" ></a> |
								<a href="#" class="delete_machine_id glyphicon glyphicon-remove-sign machine_delete" id="<?php echo $each_machine['id']; ?>" ></a>
							</td>
						</tr>
					<?php $i=$i+1; } ?>
				
					<div id="error_machinery" class="text-red float-right text-sm"></div>

					<!-- for edit machine details -->
					<?php if ($this->request->getSession()->read('edit_machine_id') != null) { ?>
						<tr>
							<td></td>
							<td><?php echo $this->Form->control('machine_name', array('type'=>'text', 'id'=>'machine_name', 'value'=>$find_machines_details['machine_name'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?></td>
							<td><?php echo $this->Form->control('machine_type', array('type'=>'select', 'id'=>'machine_type', 'value'=>$machine_type_value_edit, 'options'=>$section_form_details[6][0], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?></td>
							<td><?php echo $this->Form->control('machine_no', array('type'=>'text', 'id'=>'machine_no', 'value'=>$find_machines_details['machine_no'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?></td>
							<td><?php echo $this->Form->control('machine_capacity', array('type'=>'text', 'id'=>'machine_capacity', 'value'=>$find_machines_details['machine_capacity'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?></td>
							<td>
								<div class="form-buttons"><a href="#" id="save_machine_details" class="btn btn-info btn-sm">Save</a></div>
								<?php //echo $this->form->submit('save', array('name'=>'edit_machine_details', 'id'=>'edit_machine_details', 'onclick'=>'validate_machinery_details();return false', 'label'=>false)); ?>
							</td>
						</tr>

					<!-- To show added and save new machine details -->
					<?php } else { ?>
							<div id="add_new_row">
								<tr>
									<td></td>
									<td><?php echo $this->Form->control('machine_name', array('type'=>'text', 'id'=>'machine_name', 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?>
										<span id="error_machine_name" class="error invalid-feedback"></span>
									</td>
									<td><?php echo $this->Form->control('machine_type', array('type'=>'select', 'id'=>'machine_type', 'options'=>$section_form_details[6][0], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?>
										<span id="error_machine_type" class="error invalid-feedback"></span>
									</td>
									<td><?php echo $this->Form->control('machine_no', array('type'=>'text', 'id'=>'machine_no', 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?>
										<span id="error_machine_no" class="error invalid-feedback"></span>
									</td>
									<td><?php echo $this->Form->control('machine_capacity', array('type'=>'text', 'id'=>'machine_capacity', 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?>
										<span id="error_machine_capacity" class="error invalid-feedback"></span>
									</td>
									<td>
										<div class="form-buttons"><a href="#" id="add_machine_details" class='table_record_add_btn btn btn-info btn-sm'><i class="fa fa-plus"></i> Add</a></div>
										<?php //echo $this->form->submit('Add', array('name'=>'add_machine_details', 'id'=>'add_machine_details', 'onclick'=>'validate_machinery_details();return false', 'label'=>false)); ?>
									</td>
								</tr>
								<!-- <div id="error_machine_name"></div>
								<div id="error_machine_type"></div>
								<div id="error_machine_no"></div>
								<div id="error_machine_capacity"></div> -->
							</div>
				<?php } ?>
			</div>
		</table>
	</div>

<?php echo $this->Html->script('element/ca_other_tables_elements/machine_details_table_view'); ?>