<?php  ?>
	<div class="col-md-12 progress_bar_con">
		<?php
			$i=0;
			foreach ($allSectionDetails as $eachSection) {
			if (!empty($eachSection['progress_bar'])){  ?>

			<a href="<?php echo $this->request->getAttribute('webroot');?>inspections/section/<?php echo $eachSection['section_id']; ?>">
				<div id="section<?php echo $eachSection['section_id']; ?>" class="d-inline p-1 pl-3 pr-3 mr-1 bg-red wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
					<?php echo $eachSection['progress_bar']; ?>
					<span id="span<?php echo $eachSection['section_id']; ?>" class="far fa-trash-alt"></span>
					<?php echo $this->Form->control('report_section_status', array('type'=>'hidden', 'id'=>'report_section_status', 'value'=>"", 'class'=>'input-field', 'label'=>false)); ?>
				</div>
			</a>
		<?php $i++; }  } ?>
	</div>

<?php echo $this->Form->control('progbarstatus', array('type'=>'hidden', 'id'=>'progbarstatus', 'value'=>json_encode($progress_bar_status))); ?>
<?php echo $this->Form->control('3', array('type'=>'hidden', 'id'=>'pbfinalsubmit', 'value'=>$final_submit_status)); ?>
<?php echo $this->Form->control('current_level', array('type'=>'hidden', 'id'=>'current_level', 'value'=>$_SESSION['current_level'])); ?>
<?php echo $this->Html->script('element/siteinspection_forms/progress_bar/io_report_progress_bar'); ?>
