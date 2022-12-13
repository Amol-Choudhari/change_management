<?php ?>

<div class="col-md-12">
	<div class="form-horizontal">
		<div class="row">
			<!-- added on 26-07-2018 to show radio btns to add RO or RAL -->
			<div class="col-md-12">
				<!--Added below variable to set the message for dupicate office or 15 digit code on 03-12-2021 by AKASH-->
				<?php if (!empty($duplicate_code_msg)) {
					echo "<div class='alert alert-danger'>".$duplicate_code_msg."</div>";
				}?>
			<label class="badge badge-info">Office Type :</label>
			<?php $options=array('RO'=>'RO','RAL'=>'RAL','SO'=>'SO');
				$attributes=array('legend'=>false, 'value'=>'RO', 'id'=>'office_type');
				echo $this->form->radio('office_type',$options,$attributes); ?>
			</div>

			<div class="col-md-6">
			<label>Office Name <span class="cRed">*</span></label>
				<?php echo $this->Form->control('ro_office', array('type'=>'text', 'id'=>'ro_office', 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Enter Office Name','required'=>true)); ?>
				<span id="error_ro_office" class="error invalid-feedback"></span>
			</div>


			<div class="col-md-6">
				<label>Address <span class="cRed">*</span></label>
					<?php echo $this->Form->control('ro_office_address', array('type'=>'text', 'id'=>'ro_office_address', 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Enter Office Address','required'=>true)); ?>
				<span id="error_ro_office_address" class="error invalid-feedback"></span>
			</div>


			<!-- added id on 26-07-2018 ---->
			<div id="ro_email_list" class="col-md-6 mt-3">
				<label>Officer Id (default id is 'dmiqc@nic.in')</label>
					<?php echo $this->Form->control('ro_email_id', array('type'=>'select', 'id'=>'ro_email_id', 'class'=>'form-control', 'label'=>false, 'options'=>$all_ro_list, 'value'=>'dmiqc@nic.in')); ?>
				<span id="error_ro_email_id" class="error invalid-feedback"></span>
			</div>

			<!-- added on 26-07-2018 to show LIMS users list when RAL radio btn selected -->
			<div id="ral_email_list" class="col-md-6 mt-3">
				<label>Officer Id (default id is 'dmiqc@nic.in')</label>
					<?php echo $this->Form->control('ral_email_id', array('type'=>'select', 'id'=>'ral_email_id', 'class'=>'form-control', 'label'=>false, 'options'=>$all_ral_list, 'value'=>'dmiqc@nic.in')); ?>
					<span id="error_ral_email_id" class="error invalid-feedback"></span>
			</div>

			<!-- added on 11-03-2019 to show SO incharge users list when SO radio btn selected -->
			<div id="so_email_list" class="col-md-6 mt-3">
				<label>Officer Id (default id is 'dmiqc@nic.in')</label>
					<?php echo $this->Form->control('so_email_id', array('type'=>'select', 'id'=>'so_email_id', 'class'=>'form-control', 'label'=>false, 'options'=>$all_so_list, 'value'=>'dmiqc@nic.in')); ?>
				<span id="error_so_email_id" class="error invalid-feedback"></span>
			</div>


			<!-- added below RO offices dropdown on 11-03-2019 for SO offices-->
			<div id="ro_office_list" class="col-md-6 mt-3">
				<label>RO Office</label>
					<?php echo $this->Form->control('ro_office_id', array('type'=>'select', 'id'=>'ro_office_id', 'class'=>'form-control', 'label'=>false, 'options'=>$ro_office_list, 'empty'=>'---Select---')); ?>
				<span id="error_ro_office_id" class="error invalid-feedback"></span>
			</div>
			<div class="col-md-6 mt-3">
				<label>Phone No. </label>
					<?php echo $this->Form->control('ro_office_phone', array('type'=>'text', 'id'=>'ro_office_phone', 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Enter Office Phone NO.')); ?>
				<span id="error_ro_office_phone" class="error invalid-feedback"></span>
			</div>


			<!-- added id on 26-07-2018 ---->
			<div id="short_code_div" class="col-md-6 mt-3">
				<label>Office District Code <span class="cRed">*</span></label>
					<?php echo $this->Form->control('short_code', array('type'=>'text', 'id'=>'short_code', 'class'=>'form-control fifteenDigitUpper', 'pattern' =>'[a-zA-Z ]*','label'=>false, 'placeholder'=>'Enter Office District Short Code')); ?>
				<div id="error_short_code" class="error invalid-feedback"><?php if(!empty($duplicate_short_code)){echo "This Short code already exist";}?></div>
			</div>
			<!--added for Office code for 15-digit code entry on 03-12-2021 by AKASH-->	
			<div class="col-md-6 mt-3" id="replica_code_div">
				<label class="col-from-label">Office Code for 15-Digit Code  <span class="cRed">*</span></label>
				<?php echo $this->Form->control('replica_code', array('type'=>'text','placeholder'=>'Enter Office Code for 15-Digit Code', 'id'=>'replica_code', 'class'=>'form-control', 'label'=>false)); ?>
				<span class="error invalid-feedback" id="error_replica_code"></span>
			</div>
		</div>		
	</div>
	<div class="col-md-1 mt-3">
		<?php echo $this->element('masters_management_elements/add_submit_common_btn'); ?>
	</div>
</div>
<?php echo $this->Html->script('element/masters_management_elements/add_office'); ?>
