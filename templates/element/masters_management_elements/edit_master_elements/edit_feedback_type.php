<?php  ?>
	<label>Enter Feedback Type <span class="cRed">*</span></label>
	<div class="col-md-7">
		<?php echo $this->Form->control('title', array('type'=>'text', 'id'=>'title','label'=>false, 'value'=>$record_details['title'],'class'=>'form-control', 'required'=>true)); ?>
		<div id="error_title"></div>
	</div>


	<div class="col-md-2">
		<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
	</div>
