<?php ?>

    <label class="col-md-3">Enter Division Grade <span class="cRed">*</span></label>
    <div class="col-md-7">
        <?php echo $this->Form->control('division_type', array('type'=>'text', 'id'=>'division_type', 'label'=>false, 'placeholder'=>'Enter Division Type Here' ,'class'=>'form-control', 'required'=>true)); ?>
        <span id="error_division_type" class="error invalid-feedback"></span>
    </div>

    <div class="col-md-1 float-right">
        <?php echo $this->element('masters_management_elements/add_submit_common_btn'); ?>
    </div>
