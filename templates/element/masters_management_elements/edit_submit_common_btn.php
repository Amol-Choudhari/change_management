<input type="hidden" id="add_master_btn_master_id" value="<?php echo $masterId; ?>">
<?php echo $this->Form->submit('Edit', array('name'=>'add_master', 'id'=>'add_master_btn','label'=>false,'class'=>'btn btn-success mtNegative3')); ?>
<?php echo $this->Html->script('element/masters_management_elements/edit_submit_common_btn'); ?>
