<div class="table-format">
        <table id="tbls_table_view" class="table table-bordered table-striped">
            <thead class="tablehead">
                <th>Sr.No.</th>
                <th>TBL Name</th>
                <th>Registered?</th>
                <th>Reg. No.</th>
                <th>Upload File</th>
                <th>Action</th>
            </thead>
            <div id="machinery_each_row">
                <?php
                $i=1;
                foreach($section_form_details[3][0] as $each_tbl){  ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $each_tbl['tbl_name']; ?></td>
                    <td><?php echo $each_tbl['tbl_registered']; ?></td>
                    <td><?php echo $each_tbl['tbl_registered_no']; ?></td>
                    <td><?php if($each_tbl['tbl_registration_docs'] != null){?>
                            <a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$each_tbl['tbl_registration_docs']); ?>">Preview</a>
                        <?php }else{ echo "No File Attached";} ?></td>
                    <td><?php echo $this->Html->link('', array('controller' => 'application', 'action'=>'edit_tbl_id',$each_tbl['id']),array('class'=>'glyphicon glyphicon-edit tbl_edit', 'title'=>'Edit')); ?> |
                        <?php echo $this->Html->link('', array('controller' => 'application', 'action'=>'delete_tbl_id',$each_tbl['id']),array('class'=>'glyphicon glyphicon-remove-sign tbl_delete', 'title'=>'Delete')); ?>
                    </td>
                </tr>
                <?php  $i=$i+1; } ?>
                <div id="error_tbls"></div>

            <!-- for edit machine details -->
            <?php  if($this->request->getSession()->read('edit_tbl_id') != null){ ?>
                <tr>
                    <td></td>
                    <td><?php  echo $this->Form->control('tbl_name', array('type'=>'text', 'id'=>'tbl_name', 'value'=>$section_form_details[3][1]['tbl_name'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?></td>
                    <td>
                        <?php
                        $tbl_registered_radio = $section_form_details[3][1]['tbl_registered'];
                        if($tbl_registered_radio == 'yes'){
                            $tbl_registered_radio_yes = 'checked';
                            $tbl_registered_radio_no = '';
                        } else if($tbl_registered_radio == 'no'){
                            $tbl_registered_radio_yes = '';
                            $tbl_registered_radio_no = 'checked';
                        } else {
                            $tbl_registered_radio_yes = '';
                            $tbl_registered_radio_no = '';
                        }
                        ?>
                        <div class="icheck-success d-inline">
                        <input type="radio" name="tbl_registered" id="tbl_registered-yes" value="yes" <?php echo $tbl_registered_radio_yes; ?>>
                        <label for="tbl_registered-yes">Yes
                        </label>
                        </div>
                        <div class="icheck-success d-inline">
                        <input type="radio" name="tbl_registered" id="tbl_registered-no" value="no" <?php echo $tbl_registered_radio_no; ?>>
                        <label for="tbl_registered-no">No
                        </label>
                        </div>
                    </td>
                    <td><?php  echo $this->Form->control('tbl_registered_no', array('type'=>'text', 'id'=>'tbl_registered_no', 'value'=>$section_form_details[3][1]['tbl_registered_no'], 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?></td>
                    <td>
                        <div class="custom-file">
                            <input type="file" name="tbl_registration_docs" class="custom-file-input", multiple='multiple'>
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <?php if($section_form_details[3][1]['tbl_registration_docs'] != null){?>
                        <a target="blank" href="<?php echo str_replace("D:/xampp/htdocs","",$section_form_details[3][1]['tbl_registration_docs']); ?>">Preview</a>
                        <?php } ?>
                    </td>
                    <td><?php  echo $this->form->submit('save', array('name'=>'add_tbl_details', 'class'=>'table_record_add_btn btn btn-info btn-sm', 'id'=>'edit_tbl_details','label'=>false)); ?></td>
                </tr>

            <!-- To show added and save new machine details -->
            <?php  }else{?>

                <div id="add_new_row">
                <tr>
                    <td></td>
                    <td><?php  echo $this->Form->control('tbl_name', array('type'=>'text', 'id'=>'tbl_name', 'escape'=>false, 'class'=>'input-field', 'label'=>false, 'class'=>'form-control')); ?>
                        <span id="error_tbl_name" class="error invalid-feedback"></span>
                    </td>
                    <td>
                        <div class="icheck-success d-inline">
                        <input type="radio" name="tbl_registered" id="tbl_registered-yes" value="yes" checked="">
                        <label for="tbl_registered-yes">Yes
                        </label>
                        </div>
                        <div class="icheck-success d-inline">
                        <input type="radio" name="tbl_registered" id="tbl_registered-no" value="no">
                        <label for="tbl_registered-no">No
                        </label>
                        </div>
                        <span id="error_tbl_registered" class="error invalid-feedback"></span>
                    </td>
                    <td><?php echo $this->Form->control('tbl_registered_no', array('type'=>'text', 'id'=>'tbl_registered_no', 'escape'=>false, 'class'=>'form-control input-field', 'label'=>false)); ?>
                        <span id="error_tbl_registered_no" class="error invalid-feedback"></span>
                    </td>
                    <td>
                        <div class="custom-file">
                            <input type="file" name="tbl_registration_docs" class="custom-file-input" id="tbl_registration_file", multiple='multiple'>
                            <label class="custom-file-label" for="customFile">Choose file</label>
                            <span id="error_tbl_registration_docs" class="error invalid-feedback"></span>
                            <span id="error_type_tbl_registration_docs" class="error invalid-feedback"></span>
                            <span id="error_size_tbl_registration_docs" class="error invalid-feedback"></span>
                        </div>
                    </td>
                    <td><?php  echo $this->form->submit('Add', array('name'=>'add_tbl_details', 'id'=>'add_tbl_details','label'=>false, 'class'=>'btn btn-info btn-sm')); ?></td>
                </tr>

                </div>
            <?php  } ?>
        </div>
    </table>
</div>

<input type="hidden" id="final_submit_status_id" value="<?php echo $final_submit_status; ?>">
<?php echo $this->Html->script('element/application_forms/new/ca/ca_tbl') ?>