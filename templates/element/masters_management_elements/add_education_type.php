<?php ?>
    <label class="col-md-3">Enter Education Type<span class="cRed">*</span></label>
        <div class="col-md-7">
            <?php echo $this->Form->control('education_type', array('type'=>'text', 'id'=>'education_type', 'label'=>false, 'placeholder'=>'Enter Education Type Here', 'class'=>'form-control','required'=>true)); ?>
            <span id="error_education_type" class="error invalid-feedback"></span>
        </div>

    <div class="col-md-1 float-right">
        <?php echo $this->element('masters_management_elements/add_submit_common_btn'); ?>
    </div>
