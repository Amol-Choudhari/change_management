<?php ?>

	<div class="progress">
		<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/firm_profile">
			<div id="firm_profile" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
				Firm Profile <span id="firm_profile_span" class="far fa-trash-alt"></span>
				<?php echo $this->Form->control('firm_profile_status', array('type'=>'hidden', 'id'=>'firm_profile_status', 'value'=>$firm_form_status, 'class'=>'input-field wd14', 'label'=>false)); ?>
			</div>
		</a>

		<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/premises_profile">
			<div id="premises_profile" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" >
				Premises Profile <span id="premises_profile_span" class="far fa-trash-alt"></span>
				<?php echo $this->Form->control('premises_profile_status', array('type'=>'hidden', 'id'=>'premises_profile_status', 'value'=>$premises_form_status, 'class'=>'input-field wd14', 'label'=>false)); ?>
			</div>
		</a>

		<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/machinery_profile">
			<div id="machinery_profile" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
				Machinery Profile <span id="machinery_profile_span" class="far fa-trash-alt"></span>
				<?php echo $this->Form->control('machinery_profile_status', array('type'=>'hidden', 'id'=>'machinery_profile_status', 'value'=>$machinery_form_status, 'class'=>'input-field wd14', 'label'=>false)); ?>
			</div>
		</a>
		
		<?php if ($ca_bevo_applicant == 'no') { ?>
			<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/packing_details">
				<div id="packing_details" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" >
					Packing Details <span id="packing_details_span" class="far fa-trash-alt"></span>
					<?php echo $this->Form->control('packing_details_status', array('type'=>'hidden', 'id'=>'packing_details_status', 'value'=>$packing_form_status, 'class'=>'input-field wd14', 'label'=>false)); ?>
				</div>
			</a>
		<?php } ?>
	
		<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/laboratory_details">
			<div id="laboratory_details" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
				Laboratory Details <span id="laboratory_details_span" class="far fa-trash-alt"></span>
				<?php echo $this->Form->control('laboratory_details_status', array('type'=>'hidden', 'id'=>'laboratory_details_status', 'value'=>$laboratory_form_status, 'class'=>'input-field wd14', 'label'=>false)); ?>
			</div>
		</a>

		<!-- Comment the the condition for TBL section, Now TBL Section available in CA BEVO and NON BEVO	( Done by Pravin 28-02-2018 ) -->
		<?php //if($ca_bevo_applicant == 'no'){ ?>
			<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/tbl_details">
				<div id="tbl_details" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" >
					TBL Details <span id="tbl_details_span" class="far fa-trash-alt"></span>
					<?php echo $this->Form->control('tbl_details_status', array('type'=>'hidden', 'id'=>'tbl_details_status', 'value'=>$tbl_form_status, 'class'=>'input-field wd14', 'label'=>false)); ?>
				</div>
			</a>
		<?php //} ?>
		
		<?php /*if($switch_payment_btn == 'no'){ ?>
			<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/payment_modes"><div id="payment" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
				Payment <span id="payment_span" class="far fa-trash-alt"></span>
				<?php echo $this->Form->control('payment_status', array('type'=>'hidden', 'id'=>'payment_status', 'value'=>$payment_form_status, 'class'=>'input-field wd14', 'label'=>false)); ?>
			</div></a>
		<?php }else{ ?>
			<a href="<?php echo $this->request->getAttribute('webroot');?>authprocessedoldapp/payment_modes"><div id="payment" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
			Payment <span id="payment_span" class="far fa-trash-alt"></span>
			<?php echo $this->Form->control('payment_status', array('type'=>'hidden', 'id'=>'payment_status', 'value'=>$payment_form_status, 'class'=>'input-field wd14', 'label'=>false)); ?>
			</div></a>

		<?php } */ ?>

	</div>

<?php echo $this->Html->script('element/auth_old_applications_elements/progress_bar/progress_bar'); ?>
