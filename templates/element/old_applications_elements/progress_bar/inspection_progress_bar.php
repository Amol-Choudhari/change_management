<?php
	//if($_SESSION['current_level'])
	//{
	//	$current_level = $_SESSION['current_level'];
	//}
	//else{

		$current_level = 'level_1';
	//}

	echo $this->Form->control('current_level', array('type'=>'hidden', 'id'=>'current_level', 'value'=>$current_level, 'class'=>'input-field', 'label'=>false));

?>




	<div class="progress">
		<a href="<?php echo $this->request->getAttribute('webroot');?>oldappinspections/edit_firm_profile"><div id="firm_profile" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
			Firm Profile <span id="firm_profile_span" class="far fa-trash-alt"></span>
			<?php echo $this->Form->control('firm_profile_status', array('type'=>'hidden', 'id'=>'firm_profile_status', 'value'=>$firm_form_status, 'class'=>'input-field', 'label'=>false)); ?>
			<?php echo $this->Form->control('firm_reply_status', array('type'=>'hidden', 'id'=>'firm_reply_status', 'value'=>$firm_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
			<?php echo $this->Form->control('firm_form_current_level', array('type'=>'hidden', 'id'=>'firm_form_current_level', 'value'=>$firm_form_current_level, 'class'=>'input-field', 'label'=>false)); ?>
		</div></a>

		<a href="<?php echo $this->request->getAttribute('webroot');?>oldappinspections/edit_premises_profile"><div id="premises_profile" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
			Premises Profile <span id="premises_profile_span" class="far fa-trash-alt"></span>
			<?php echo $this->Form->control('premises_profile_status', array('type'=>'hidden', 'id'=>'premises_profile_status', 'value'=>$premises_form_status, 'class'=>'input-field', 'label'=>false)); ?>
			<?php echo $this->Form->control('premises_reply_status', array('type'=>'hidden', 'id'=>'premises_reply_status', 'value'=>$premises_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
			<?php echo $this->Form->control('premises_form_current_level', array('type'=>'hidden', 'id'=>'premises_form_current_level', 'value'=>$premises_form_current_level, 'class'=>'input-field', 'label'=>false)); ?>
		</div></a>

		<a href="<?php echo $this->request->getAttribute('webroot');?>oldappinspections/edit_machinery_profile"><div id="machinery_profile" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
			Machinery Profile <span id="machinery_profile_span" class="far fa-trash-alt"></span>
			<?php echo $this->Form->control('machinery_profile_status', array('type'=>'hidden', 'id'=>'machinery_profile_status', 'value'=>$machinery_form_status, 'class'=>'input-field', 'label'=>false)); ?>
			<?php echo $this->Form->control('machinery_reply_status', array('type'=>'hidden', 'id'=>'machinery_reply_status', 'value'=>$machinery_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
			<?php echo $this->Form->control('machinery_form_current_level', array('type'=>'hidden', 'id'=>'machinery_form_current_level', 'value'=>$machinery_form_current_level, 'class'=>'input-field', 'label'=>false)); ?>
		</div></a>

		<?php if($ca_bevo_applicant == 'no'){?>

			<a href="<?php echo $this->request->getAttribute('webroot');?>oldappinspections/edit_packing_details"><div id="packing_details" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
				Packing Details <span id="packing_details_span" class="far fa-trash-alt"></span>
				<?php echo $this->Form->control('packing_details_status', array('type'=>'hidden', 'id'=>'packing_details_status', 'value'=>$packing_form_status, 'class'=>'input-field', 'label'=>false)); ?>
				<?php echo $this->Form->control('packing_reply_status', array('type'=>'hidden', 'id'=>'packing_reply_status', 'value'=>$packing_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
				<?php echo $this->Form->control('packing_form_current_level', array('type'=>'hidden', 'id'=>'packing_form_current_level', 'value'=>$packing_form_current_level, 'class'=>'input-field', 'label'=>false)); ?>
			</div></a>

		<?php } ?>

		<a href="<?php echo $this->request->getAttribute('webroot');?>oldappinspections/edit_laboratory_details"><div id="laboratory_details" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
			Laboratory Details <span id="laboratory_details_span" class="far fa-trash-alt"></span>
			<?php echo $this->Form->control('laboratory_details_status', array('type'=>'hidden', 'id'=>'laboratory_details_status', 'value'=>$laboratory_form_status, 'class'=>'input-field', 'label'=>false)); ?>
			<?php echo $this->Form->control('laboratory_reply_status', array('type'=>'hidden', 'id'=>'laboratory_reply_status', 'value'=>$laboratory_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
			<?php echo $this->Form->control('laboratory_form_current_level', array('type'=>'hidden', 'id'=>'laboratory_form_current_level', 'value'=>$laboratory_form_current_level, 'class'=>'input-field', 'label'=>false)); ?>
		</div></a>

		<!-- Comment the the condition for TBL section, Now TBL Section available in CA BEVO and NON BEVO (Done By Pravin 28/02/2018)-->
		<?php //if($ca_bevo_applicant == 'no'){?>

			<a href="<?php echo $this->request->getAttribute('webroot');?>oldappinspections/edit_tbl_details"><div id="tbl_details" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
				TBL Details <span id="tbl_details_span" class="far fa-trash-alt"></span>
				<?php echo $this->Form->control('tbl_details_status', array('type'=>'hidden', 'id'=>'tbl_details_status', 'value'=>$tbl_form_status, 'class'=>'input-field', 'label'=>false)); ?>
				<?php echo $this->Form->control('tbl_reply_status', array('type'=>'hidden', 'id'=>'tbl_reply_status', 'value'=>$tbl_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
				<?php echo $this->Form->control('tbl_form_current_level', array('type'=>'hidden', 'id'=>'tbl_form_current_level', 'value'=>$tbl_form_current_level, 'class'=>'input-field', 'label'=>false)); ?>
			</div></a>

		<?php //} ?>
		<!--
			<a href="#"><div id="payment" class="progress-bar progress-bar-danger wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
				Payment <span id="payment_span" class="far fa-trash-alt"></span>
				<?php //echo $this->Form->control('payment_status', array('type'=>'hidden', 'id'=>'payment_status', 'value'=>$payment_form_status, 'class'=>'input-field', 'label'=>false)); ?>
				<?php //echo $this->Form->control('payment_reply_status', array('type'=>'hidden', 'id'=>'payment_reply_status', 'value'=>$payment_reply_status, 'class'=>'input-field', 'label'=>false)); ?>
				<?php //echo $this->Form->control('payment_form_current_level', array('type'=>'hidden', 'id'=>'payment_form_current_level', 'value'=>$payment_form_current_level, 'class'=>'input-field', 'label'=>false)); ?>
			</div></a>
		-->
	</div>


<?php echo $this->Html->script('element/old_applications_elements/progress_bar/inspection_progress_bar'); ?>
