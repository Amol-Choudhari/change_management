<?php  ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
			    <div class="col-sm-6"><?php echo $this->Html->link('Back', array('controller' => 'masters', 'action'=>'list_master_records'),array('class'=>'add_btn btn btn-secondary')); ?></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
                        <li class="breadcrumb-item"><?php echo $this->Html->link('Masters Home', array('controller' => 'masters', 'action'=>'masters-home'));?></li>
                        <li class="breadcrumb-item active"><?php echo $masterEditTitle ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content form-middle">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <div class="card card-teal">
                        <div class="card-header"><h5 class="card-title-new"><?php echo $masterEditTitle ?></h5></div>
                        <?php echo $this->Form->create(null,array('class'=>'form-group','id'=>$form_id)); ?>
                            <div class="form-horizontal">
                                <div class="card-body">
                                    <div class="row">
                                        <?php
                                            if ($masterId=='1') {

                                                echo $this->element('masters_management_elements/edit_master_elements/edit_state');

                                            } elseif ($masterId=='2'){
                                                echo $this->element('masters_management_elements/edit_master_elements/edit_district');

                                            } elseif ($masterId=='3'){
                                                echo $this->element('masters_management_elements/edit_master_elements/edit_business_type');

                                            } elseif ($masterId=='4'){
                                                echo $this->element('masters_management_elements/edit_master_elements/edit_packing_type');

                                            } elseif ($masterId=='5'){
                                                echo $this->element('masters_management_elements/edit_master_elements/edit_laboratory_type');

                                            } elseif ($masterId=='6'){
                                                echo $this->element('masters_management_elements/edit_master_elements/edit_machine_type');

                                            } elseif ($masterId=='7'){
                                                echo $this->element('masters_management_elements/edit_master_elements/edit_tank_shape');

                                            } elseif ($masterId=='8'){
                                                echo $this->element('masters_management_elements/edit_master_elements/edit_charge');

                                            } elseif ($masterId=='9') {

                                                echo $this->element('masters_management_elements/edit_master_elements/edit_business_year');

                                            } elseif ($masterId=='10') {

                                                echo $this->element('masters_management_elements/edit_master_elements/edit_office');

                                            } elseif ($masterId=='11') {

                                                echo $this->element('masters_management_elements/edit_master_elements/edit_template');

                                            } elseif ($masterId=='12') {

                                                echo $this->element('masters_management_elements/edit_master_elements/edit_pao');

                                            } elseif ($masterId=='15') {

                                                echo $this->element('masters_management_elements/edit_master_elements/edit_feedback_type');

                                            } elseif ($masterId=='16') {

                                                echo $this->element('masters_management_elements/edit_master_elements/edit_replica_charges');

                                            } elseif  ($masterId=='17') {

                                                echo $this->element('masters_management_elements/edit_master_elements/edit_education_type');

                                            } elseif  ($masterId=='18') {

                                                echo $this->element('masters_management_elements/edit_master_elements/edit_division_type');

                                            } elseif  ($masterId=='19') {

                                                echo $this->element('masters_management_elements/edit_master_elements/edit_documents_list');
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<input type="hidden" id="masterId" value="<?php echo $masterId; ?>">
<input type="hidden" id="form_id" value="<?php echo $form_id; ?>">
<?php echo $this->Html->script('Masters/edit_master_record'); ?>
