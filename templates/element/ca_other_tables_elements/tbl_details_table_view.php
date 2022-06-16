<?php ?>

<div class="table-format">
	<table id="tbls_table_view" class="table table-striped table-bordered table-responsive">
		<tr>
			<th class="tablehead">Sr.No.</th>
			<th class="tablehead">TBL Name</th>
			<th class="tablehead">Registered?</th>
			<th class="tablehead">Reg. No.</th>
			<th class="tablehead">Upload File</th>
			<th class="tablehead">Action</th>
		</tr>
		
		<div id="machinery_each_row">
		
			<?php
			$i=1;
			foreach($added_tbls_details as $each_tbl){ ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $each_tbl['tbl_name']; ?></td>
				<td><?php echo $each_tbl['tbl_registered']; ?></td>
				<td><?php echo $each_tbl['tbl_registered_no']; ?></td>
				<td><?php if($each_tbl['tbl_registration_docs'] != null){?>
					<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$each_tbl['tbl_registration_docs']); ?>">Preview</a>
					<?php }else{ echo "No File Attached";} ?></td>
				<td>
					<a href="#" class="edit_tbl_id glyphicon-edit tbl_edit" id="<?php echo $each_tbl['id']; ?>" ></a> | 
					<a href="#" class="delete_tbl_id glyphicon-remove-sign tbl_delete" id="<?php echo $each_tbl['id']; ?>" ></a>
				</td>
			</tr>
			<?php  $i=$i+1; } ?>
			<div id="error_tbls"></div>

		<!-- for edit machine details -->
		<?php  if($this->Session->read('edit_tbl_id') != null){?>
			<tr>
				<td></td>
				<td><?php  echo $this->Form->control('tbl_name', array('type'=>'text', 'id'=>'tbl_name', 'value'=>$find_tbls_details['tbl_name'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				
				<td><?php $options=array('yes'=>'Yes','no'=>'No');
							$attributes=array('legend'=>false, 'id'=>'tbl_registered', 'value'=>$find_tbls_details['tbl_registered'], 'label'=>true);					
							echo $this->Form->radio('tbl_registered',$options,$attributes); ?></td>
				
				<td><?php  echo $this->Form->control('tbl_registered_no', array('type'=>'text', 'id'=>'tbl_registered_no', 'value'=>$find_tbls_details['tbl_registered_no'], 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				
				<td><?php if($find_tbls_details['tbl_registration_docs'] != null){?>
					<a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$find_tbls_details['tbl_registration_docs']); ?>">Preview</a>
					<?php }?>
					</span>
					<?php echo $this->Form->control('tbl_registration_docs',array('type'=>'file', 'id'=>'tbl_registration_docs','multiple'=>'multiple', 'label'=>false)); ?>	
				</td>
				
				<td>
					<div class="form-buttons"><a href="#" id="save_tbl_details">Save</a></div>
					<?php  //echo $this->form->submit('save', array('name'=>'edit_tbl_details', 'id'=>'edit_tbl_details', 'onclick'=>'validate_tbl_details();return false', 'label'=>false)); ?>
				</td>
			</tr>
		
		<!-- To show added and save new machine details -->
		<?php  }else{?>	

			<div id="add_new_row">
			<tr>
				<td></td>
				<td><?php  echo $this->Form->control('tbl_name', array('type'=>'text', 'id'=>'tbl_name', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				
				<td><?php $options=array('yes'=>'Yes','no'=>'No');
							$attributes=array('legend'=>false, 'id'=>'tbl_registered', 'value'=>'yes', 'label'=>true);					
							echo $this->Form->radio('tbl_registered',$options,$attributes); ?></td>
				
				<td><?php  echo $this->Form->control('tbl_registered_no', array('type'=>'text', 'id'=>'tbl_registered_no', 'escape'=>false, 'class'=>'input-field', 'label'=>false)); ?></td>
				
				<td><?php  echo $this->Form->control('tbl_registration_docs',array('type'=>'file', 'id'=>'tbl_registration_docs', 'multiple'=>'multiple', 'label'=>false)); ?></td>
				
				<td>
					<div class="form-buttons"><a href="#" id="add_tbl_details" class='btn btn-success table_record_add_btn' >Add</a></div>
					<?php  //echo $this->form->submit('Add', array('name'=>'add_tbl_details', 'id'=>'add_tbl_details', 'onclick'=>'validate_tbl_details();return false', 'label'=>false)); ?>
				</td>
			</tr> 
				<div id="error_tbl_name"></div>
				<div id="error_tbl_registered"></div>
				<div id="error_tbl_registered_no"></div>
				<div id="error_tbl_registration_docs"></div>
				<div id="error_type_tbl_registration_docs"></div>
				<div id="error_size_tbl_registration_docs"></div>
			</div>
		<?php  } ?>
		</div>
	</table>
</div>	
	
<?php echo $this->Html->script('element/ca_other_tables_elements/tbl_details_table_view'); ?>	
