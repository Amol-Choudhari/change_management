<?php ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><label class="badge badge-primary">Master Records</label></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action' => 'home')); ?></li>
                        <li class="breadcrumb-item"><?php echo $this->Html->link('Masters Home', array('controller' => 'masters', 'action' => 'masters-home')); ?></li>
                        <li class="breadcrumb-item active"><?php echo $masterListHeader; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content form-middle">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <?php echo $this->Html->link('Add New', array('controller' => 'masters', 'action' => 'add_master_record'), array('class' => 'add_btn btn btn-success')); ?>
                    <?php echo $this->Html->link('Back', array('controller' => 'masters', 'action' => 'masters_home'), array('class' => 'add_btn btn btn-secondary float-right')); ?>
                </div>

                <div class="col-md-12">
                    <?php echo $this->Form->create(); ?>
                        <div class="card card-Lightblue">
                            <div class="card-header"><h4 class="card-title-new"><?php echo $masterListTitle; ?></h4></div>
                            <div class="panel panel-primary filterable">
                                <table id="list_master_table" class="table m-0 table-stripped table-hover table-bordered">
                                    <?php
                                        if ($masterId == '1') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_states');

                                        } elseif ($masterId == '2') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_districts');

                                        } elseif ($masterId == '3') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_business_types');

                                        } elseif ($masterId == '4') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_packing_types');

                                        } elseif ($masterId == '5') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_laboratory_types');

                                        } elseif ($masterId == '6') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_machine_types');

                                        } elseif ($masterId == '7') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_tank_shapes');

                                        } elseif ($masterId == '8') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_charges');

                                        } elseif ($masterId == '9') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_business_years');

                                        } elseif ($masterId == '10') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_offices');

                                        } elseif ($masterId == '11') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_msg_templates');

                                        } elseif ($masterId == '12') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_pao');

                                        } elseif ($masterId == '15') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_feedback_types');

                                        } elseif ($masterId == '100') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_test');

                                        } elseif ($masterId == '16') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_replica_charges');

                                        } elseif ($masterId == '17') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_education_type');

                                        } elseif ($masterId == '18') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_division_type');

                                        } elseif ($masterId == '19') {

                                            echo $this->element('masters_management_elements/list_master_elements/all_documents_list');

                                        }
                                    ?>
                                </table>
                            </div>
                        </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php echo $this->Html->script('Masters/list_master_records'); ?>
