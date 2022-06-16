<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'education','class'=>'form_name')); ?>
<div id="form_outer_main" class="card card-success form_outer_class">
	<div class="card-header"><h3 class="card-title-new">Education</h3></div>
		<div class="form-horizontal">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12 row">
						<div id="add_new_row"></div>
						<div id="table_container_1" ></div>
					</div>
					<?php echo $this->Form->control('application_dashboard', array('type'=>'hidden', 'id'=>'application_dashboard', 'value'=>$_SESSION['application_dashboard'])); ?>
				</div>
			</div>
		</div>
		<input type="hidden" id="tableData" value='<?php echo $section_form_details[1]; ?>'>
</div>
<?php echo $this->Html->script('element/application_forms/chemist/tableData'); ?>
<?php echo $this->Html->script('add_more_row'); ?>

