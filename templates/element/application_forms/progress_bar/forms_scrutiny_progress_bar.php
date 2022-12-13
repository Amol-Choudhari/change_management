<?php $application_type = $_SESSION['application_type']; ?>
<section class="content">
  <div class="container-fluid">
    <div class="row">
          <div class="progress_bar_con">
              <?php
              $i=0;
              foreach($allSectionDetails as $eachSection) {
                if(!empty($eachSection['progress_bar'])){
                  $show = 'yes';
				  //commented below code on 13-12-2022 by Amol, no need of conditions for change flow
                   /* if($application_type == 3){
                      if(in_array($eachSection['section_id'],$selectedSections)){
                        $show = 'yes';
                      }
                    }else{  $show = 'yes';  }*/

                    if( $show == 'yes'){
                              ?>
                <a href="<?php echo $this->getRequest()->getAttribute('webroot');?>scrutiny/section/<?php echo $eachSection['section_id']; ?>">
                  <div id="section<?php echo $eachSection['section_id']; ?>" class="d-inline p-1 pl-3 pr-3 mr-1 bg-red" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">

                    <span id="span<?php echo $eachSection['section_id']; ?>" class="progress_bar fa fa-times-circle"></span>
                      <?php echo $eachSection['progress_bar']; ?>
                    <?php echo $this->Form->control('form_section_status', array('type'=>'hidden', 'id'=>'form_section_status', 'value'=>"", 'class'=>'input-field', 'label'=>false)); ?>
                  </div>
                </a>
              <?php $i++; } }  } ?>
              <?php if($_SESSION['paymentSection'] == 'available') { ?>

                        <a href="<?php echo $this->getRequest()->getAttribute('webroot');?>scrutiny/payment">
                        <div id="sectionpayment" class="d-inline p-1 pl-3 pr-3 mr-1 bg-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span id="spanpayment" class="progress_bar  fa fa-times-circle"></span>Payment
                                <?php echo $this->Form->control('payment_status', array('type'=>'hidden', 'id'=>'payment_status', 'value'=>"", 'class'=>'input-field', 'label'=>false)); ?>
                        </div></a>
              <?php } ?>
          </div>
    </div>
  </div>
</section>

<?php  echo $this->Form->control('progbarstatus', array('type'=>'hidden', 'id'=>'progbarstatus', 'value'=>json_encode($progress_bar_status), 'class'=>'input-field', 'label'=>false)); ?>
<!-- covert inline script to external, done by Pravin Bhakare 07-10-2021-->
<?php echo $this->Html->script('element/progressbar/forms_scrutiny_progress_bar'); ?>
