<?php ?>

	<div class="col-md-12">
		<!--Added below variable to set the message for dupicate office or 15 digit code on 03-12-2021 by AKASH-->
		<?php if (!empty($duplicate_code_msg)) {
					echo "<div class='alert alert-danger'>".$duplicate_code_msg."</div>";
				}
		?>

		<div class="row">

		<!-- added conditions on 11-05-2021 for RO/SO office type, if required to change the office type -->
		<?php if ($record_details['office_type']=='RO' || $record_details['office_type']=='SO') { ?>

			<div class="col-md-6">
				<label class="badge badge-info">Current Office Type: <span class="cRed">*</span>
					<?php echo $this->Form->control('current_type', array('type'=>'text', 'id'=>'current_type', 'value'=>$record_details['office_type'],'label'=>false,'class'=>'','readonly'=>true)); ?>
					</label>
				</div>


			<div class="col-md-6">
				<label class="badge badge-info">Change Type (if required): <span class="cRed">*</span>
					<?php $options=array('RO'=>'RO','SO'=>'SO');
						$attributes=array('legend'=>false, 'value'=>$record_details['office_type'], 'id'=>'office_type');
						echo $this->form->radio('office_type',$options,$attributes); 
					?>
					</label>
			</div>

			<!-- Else for RAL/CAL office type -->
		<?php } else { ?>

			<div class="col-md-6 mt-3">
				<label>Office Type: <span class="cRed">*</span></label>
				<?php echo $this->Form->control('office_type', array('type'=>'text', 'value'=>$record_details['office_type'],'label'=>false,'class'=>'form-control','readonly'=>true)); ?>
			</div>

		<?php } ?>

		<div class="col-md-6 mt-3">
		<label>Office Name <span class="cRed">*</span></label>
			<?php echo $this->Form->control('ro_office', array('type'=>'text', 'value'=>$record_details['ro_office'], 'id'=>'ro_office', 'label'=>false,'class'=>'form-control')); ?>
			<span id="error_ro_office" class="error invalid-feedback"></span>
		</div>


		<div class="col-md-6 mt-3">
		<label>Address <span class="cRed">*</span></label>
			<?php echo $this->Form->control('ro_office_address', array('type'=>'text', 'value'=>$record_details['ro_office_address'], 'id'=>'ro_office_address', 'label'=>false,'class'=>'form-control')); ?>
			<span id="error_ro_office_address" class="error invalid-feedback"></span>
		</div>

		<!-- to show when office type is RO -->
		<?php if ($record_details['office_type']=='RO' || $record_details['office_type']=='SO') { ?>
			<div class="col-md-6 mt-3">
				<label>Office District Code <span class="cRed">*</span></label>
				<?php echo $this->Form->control('short_code', array('type'=>'text', 'value'=>$record_details['short_code'], 'label'=>false, 'readonly'=>true,'class'=>'form-control')); ?>
				<span id="error_short_code" class="error invalid-feedback"></span>
			</div>

			<!--added for Office code for 15-digit code entry on 03-12-2021 by AKASH-->	
			<div class="col-md-6 mt-3" id="replica_code_div">
				<label class="col-from-label">Office Code for 15-Digit Code  <span class="cRed">*</span></label>
				<?php echo $this->Form->control('replica_code', array('type'=>'text','readonly'=>true,'placeholder'=>'Enter Office Code for 15-Digit Code','value'=>$record_details['replica_code'], 'id'=>'replica_code', 'class'=>'form-control', 'label'=>false)); ?>
				<span class="error invalid-feedback" id="error_replica_code"></span>
			</div>
		<?php } ?>

		<div class="col-md-6 mt-3">
		<label>Phone No.</label>

			<?php echo $this->Form->control('ro_office_phone', array('type'=>'text', 'value'=>$record_details['ro_office_phone'], 'id'=>'ro_office_phone', 'label'=>false,'class'=>'form-control')); ?>
			<span id="error_ro_office_phone" class="error invalid-feedback"></span>
		</div>

		<!-- to show when office type is RAL -->
		<?php if($record_details['office_type']=='RAL'){ ?>

			<div id="ral_email_list" class="col-md-6 mt-3">
			<label>Officer Email Id <span class="cRed">*</span></label>
				<?php echo $this->Form->control('ral_email_id', array('type'=>'select', 'id'=>'ral_email_id', 'label'=>false, 'options'=>$all_ral_list, 'value'=>$record_details['ro_email_id'],'class'=>'form-control')); ?>
				<span id="error_ral_email_id" class="error invalid-feedback"></span>
			</div>

		<?php } ?>

		<!-- added below RO offices dropdown for SO offices-->
		<?php if($record_details['office_type']=='SO' || $record_details['office_type']=='RO'){ ?>

			<div id="ro_office_list" class="col-md-6 mt-3">
				<label>RO Office <span class="cRed">*</span></label>
				<?php echo $this->Form->control('ro_office_id', array('type'=>'select', 'id'=>'ro_office_id', 'label'=>false, 'options'=>$ro_office_list, 'value'=>$record_details['ro_id_for_so'],'class'=>'form-control')); ?>
				<span id="error_ro_office_id" class="error invalid-feedback"></span>
			</div>

		<?php } ?>

		<div class="col-md-12 mt-3">
			<?php echo $this->Form->submit('Update Office', array('name'=>'edit_ro_office', 'id'=>'edit_ro_office','label'=>false,'class'=>'btn btn-success')); ?>
		</div>


		<!-- to show when office type is RO -->
		<?php if($record_details['office_type']=='RO' || $record_details['office_type']=='SO'){ ?>

			<!-- Show current ro incharge details -->
			<div class="col-md-12 mt-3">
				<h5 class="alert alert-info">Current In-Charge Details</h5>
					<div class="form-horizontal">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>In-charge Email Id <span class="cRed">*</span></label>
										<?php echo $this->Form->control('ro_email_id', array('type'=>'text', 'value'=>base64_decode($record_details['ro_email_id']), 'label'=>false, 'readonly'=>true,'class'=>'form-control')); //for email encoding ?>
									<span id="error_ro_email_id" class="error invalid-feedback"></span>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group ">
									<label>In-charge Name <span class="cRed">*</span></label>
										<?php echo $this->Form->control('incharge_name', array('type'=>'text', 'value'=>$ro_incharge_name, 'label'=>false, 'readonly'=>true,'class'=>'form-control')); ?>
									<span id="error_incharge_name" class="error invalid-feedback"></span>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group ">
									<label>In-charge Mobile No.<span class="cRed">*</span></label>
										<?php echo $this->Form->control('incharge_mobile_no', array('type'=>'text', 'value'=>base64_decode($ro_incharge_mobile_no), 'label'=>false, 'readonly'=>true,'class'=>'form-control')); ?>
									<span id="error_incharge_mobile_no" class="error invalid-feedback"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
	<!-- to show when office type is RO -->
	<?php if($record_details['office_type']=='RO' || $record_details['office_type']=='SO'){ ?>
		<!--  Reallocated the ro incharge to current ro office  (Done by pravin 01-09-2017)-->
			<div class="col-md-12 mt-3">
				<h5 class="alert alert-success">Reallocate In-charge</h5>
					<div class="form-horizontal">
						<div class="row">
							<div class="col-md-7">
								<label class="col-md-6">All In-charge List</label>
								<?php echo $this->Form->control('ro_name_list', array('type'=>'select', 'options'=>$ro_incharge_name_list, 'label'=>false,'class'=>'form-control')); ?>
								<span id="error_ro_name_list" class="error invalid-feedback"></span>
							</div>
							<div class="col-md-4">
								<?php echo $this->Form->submit('Reallocate In-charge', array('name'=>'ro_reallocate', 'id'=>'ro_reallocate_btn','label'=>false,'class'=>'btn btn-success mt33')); ?>
							</div>
						</div>
					</div>
				</div>		
	<?php } ?>

	<input type="hidden" id="master_id_for_office" value="<?php echo $masterId; ?>">

	<?php echo $this->Html->script('element/masters_management_elements/edit_office'); ?>
