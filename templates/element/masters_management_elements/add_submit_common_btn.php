<input type="hidden" id="add_master_btn_master_id" value="<?php echo $masterId; ?>">
<?php echo $this->Form->submit('Add', array('name'=>'add_master', 'id'=>'add_master_btn','label'=>false,'class'=>'form-control btn btn-success')); ?>
<?php echo $this->Html->script('element/masters_management_elements/add_submit_common_btn'); ?>
