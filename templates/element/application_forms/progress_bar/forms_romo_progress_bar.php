<?php $application_type = $_SESSION['application_type']; ?>
<div class="col-md-12 progress_bar_con">
	<?php
			$i=0;
			foreach($allSectionDetails as $eachSection) {
				if(!empty($eachSection['progress_bar'])){

						$show = 'no';
						if($application_type == 3){
							if(in_array($eachSection['section_id'],$selectedSections)){
								$show = 'yes';
							}
						 }else{  $show = 'yes';  }

						 if( $show == 'yes'){
											?>
		<a href="<?php echo $this->getRequest()->getAttribute('webroot');?>scrutiny/section/<?php echo $eachSection['section_id']; ?>">
			<div id="section<?php echo $eachSection['section_id']; ?>" class="progress-bar wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
				<?php echo $eachSection['progress_bar']; ?>
				<span id="span<?php echo $eachSection['section_id']; ?>" class="far fa-trash-alt"></span>
				<?php echo $this->Form->control('form_section_status', array('type'=>'hidden', 'id'=>'form_section_status', 'value'=>"", 'class'=>'input-field', 'label'=>false)); ?>
			</div>
		</a>
	<?php $i++; } }  } ?>
	<?php if($_SESSION['paymentSection'] == 'available') { ?>

            <a href="<?php echo $this->getRequest()->getAttribute('webroot');?>scrutiny/payment"><div id="sectionpayment" class="progress-bar wd12" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                    Payment <span id="spanpayment" class="far fa-trash-alt"></span>
                    <?php echo $this->Form->control('payment_status', array('type'=>'hidden', 'id'=>'payment_status', 'value'=>"", 'class'=>'input-field', 'label'=>false)); ?>
            </div></a>
	<?php } ?>
</div>

<?php  echo $this->Form->control('progbarstatus', array('type'=>'hidden', 'id'=>'progbarstatus', 'value'=>json_encode($progress_bar_status), 'class'=>'input-field', 'label'=>false)); ?>
<!-- covert inline script to external, done by Pravin Bhakare 06-10-2021-->
<?php echo $this->Html->script('element/progressbar/forms_romo_progress_bar'); ?>
