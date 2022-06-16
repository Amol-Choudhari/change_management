<?php ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0 text-dark"></h1></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'customers', 'action'=>'secondary_home'));?></a></li>
                        <li class="breadcrumb-item active">Chemist Registration</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

	<?php if (!empty($primary_registered)) { ?>
        <div class="container">
            <h4 class="h4_tag_class">
                <div class="marTop">
                <span class="font_class">Congratulations... your details has been saved and your id is</span> <span  class="id_font"><?php echo $new_customer_id ; ?></span>.
                    <br>
                    <span class="encodedEmail"><?php
                        echo " A link has been sent to your email id ".$htmlencodedemail ;
                    ?>.</span>
                </div>
            </h4>
        </div>
	<?php } else { ?>

    <section class="content form-middle">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <?php echo $this->Form->create(null, array('type'=>'file', 'id'=>'reg_customer_form', 'enctype'=>'multipart/form-data')); ?>
                        <div class="card card-secondary" id="form_outer_main">
                            <div class="card-header"><h3 class="card-title-new">Chemist Registration</h3></div>
                                <div class="col-sm-6 offset-7 mt-2"><span class="badge badge-danger">Name should be same as in Aadhar card.</span></div>
                                    <div class="form-horizontal">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="emailsignup" class="youmail">First Name <span class="cRed">*</span></label>
                                                        <?php echo $this->Form->control('chemist_fname', array('label'=>false, 'id'=>'chemist_fname', 'escape'=>false, 'class'=>'input-field form-control', 'placeholder'=>'Please enter first name')); ?>
                                                    <span class="error invalid-feedback" id="error_f_name"></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="emailsignup" class="youmail">Last Name <span class="cRed">*</span></label>
                                                        <?php echo $this->Form->control('chemist_lname', array('label'=>false, 'id'=>'chemist_lname', 'escape'=>false, 'class'=>'input-field form-control', 'placeholder'=>'Please enter last name')); ?>
                                                    <span class="error invalid-feedback" id="error_lname"></span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="emailsignup" class="youmail">Email <span class="cRed">*</span></label>
                                                        <?php echo $this->Form->control('email', array('label'=>false, 'id'=>'email', 'escape'=>false, 'class'=>'input-field form-control', 'placeholder'=>'Please enter email id')); ?>
                                                    <span class="error invalid-feedback" id="error_email"></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="passwordsignup" class="youpasswd">Mobile No <span class="cRed">*</span></label>
                                                        <?php echo $this->Form->control('mobile', array('type'=>'tel', 'escape'=>false, 'id'=>'mobile', 'label'=>false, 'class'=>'input-field form-control', 'minlength'=>'10', 'maxlength'=>'10','placeholder'=>'Please enter mobile no.')); ?>
                                                    <span class="error invalid-feedback" id="error_mobile"></span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                  <label for="usernamesignup" class="uname">Date of Birth <span class="cRed">*</span></label>
                                                        <?php echo $this->Form->control('dob', array('type'=>'text', 'escape'=>false, 'id'=>'dob', 'label'=>false, 'class'=>'form-control input-field','placeholder'=>'Enter Date of Birth','readonly'=>true)); ?>
                                                    <span class="error invalid-feedback" id="error_dob"></span>
                                                </div>
                                              
                                            </div>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                    <div class="card-body cardFooterBackground">
                                        <?php echo $this->Form->control('Register', array('type'=>'button', 'id'=>'add_chemist', 'label'=>false, 'class'=>'btn btn-success mtminus12pb7')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php echo $this->Html->script('chemist_module_validations'); ?>

<?php } ?>
