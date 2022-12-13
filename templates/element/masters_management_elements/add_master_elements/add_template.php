<?php ?>

	<div class="col-md-12">

		<div class="row">
			<div class="col-md-6">
				<label>Short Description <span class="cRed">*</span></label>
				<?php echo $this->Form->control('description', array('type'=>'textarea', 'id'=>'description','class'=>'form-control', 'label'=>false, 'placeholder'=>'Enter Short Description Here')); ?>
                <span id="error_description" class="error invalid-feedback"></span>
			</div>

			<div class="col-md-6">
				<label>SMS Message <span class="cRed">*</span></label>
				<?php echo $this->Form->control('sms_message', array('type'=>'textarea', 'id'=>'sms_message','class'=>'form-control', 'label'=>false, 'placeholder'=>'Enter SMS Message Here')); ?>
				<span id="error_sms_message" class="error invalid-feedback"></span>
			</div>

			<div class="col-md-6 mt-2">
				<label>Subject </label>
				<?php echo $this->Form->control('email_subject', array('type'=>'text', 'id'=>'email_subject', 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Enter Subject Here')); ?>
				<span id="error_email_subject" class="error invalid-feedback"></span>
			</div>

			<div class="col-md-6 mt-2">
				<label>Email Message </label>
				<?php echo $this->Form->control('email_message', array('type'=>'textarea', 'class'=>'form-control','id'=>'email_message',  'label'=>false, 'placeholder'=>'Enter Email Message Here')); ?>
			</div>

			<div class="col-md-12">
				<label class="badge badge-info">Send To :
				<?php
					$options = array('dmi'=>'DMI','lmis'=>'LIMS');
					$attributes = array('legend'=>false, 'id'=>'template_for');
					echo $this->Form->radio('template_for',$options,$attributes,);
				?>
				</label>

				<div id="dmi_roles" class="form-horizontal">
					<div class="card-body">
						<div class="boxformenus row color1">
							<div class="col-md-3">
								<!--Applicant-->
								<?php echo $this->Form->control('applicant', array('type'=>'checkbox', 'id'=>'applicant','label'=>' Applicant', 'escape'=>false)); ?>
								<!--MO/SMO (Scrutiny Officer)-->
								<?php echo $this->Form->control('mo_smo', array('type'=>'checkbox', 'id'=>'mo_smo', 'label'=>' Scrutinizer', 'escape'=>false)); ?>
								<!--IO (Inspection Officer)-->
								<?php echo $this->Form->control('io', array('type'=>'checkbox', 'id'=>'io', 'label'=>' Inspection Officer', 'escape'=>false)); ?>
							</div>
							<div class="col-md-3">
								<!--RO/SO (Nodal Officer)-->
								<?php echo $this->Form->control('ro_so', array('type'=>'checkbox', 'id'=>'ro_so', 'label'=>' Nodal Officer', 'escape'=>false)); ?>
								<!--Dy.AMA-->
								<?php echo $this->Form->control('dy_ama', array('type'=>'checkbox', 'id'=>'dy_ama', 'label'=>' Dy.AMA', 'escape'=>false)); ?>
								<!--Chemist User-->
								<?php echo $this->Form->control('chemist_user', array('type'=>'checkbox', 'id'=>'chemist_user', 'label'=>' Chemist User', 'escape'=>false)); ?>
							</div>
							<div class="col-md-3">
								<!--Jt.AMA-->
								<?php echo $this->Form->control('jt_ama', array('type'=>'checkbox', 'id'=>'jt_ama', 'label'=>' Jt.AMA', 'escape'=>false)); ?>
								<!--HO/MO/SMO-->
								<?php echo $this->Form->control('ho_mo_smo', array('type'=>'checkbox', 'id'=>'ho_mo_smo', 'label'=>' Scrutiny(HO)', 'escape'=>false)); ?>
								<!--RO-Incharge-->
								<?php echo $this->Form->control('ro_incharge', array('type'=>'checkbox', 'id'=>'ro_incharge', 'label'=>' RO Incharge', 'escape'=>false)); ?>

							</div>
							<div class="col-md-3">
								<!--AMA-->
								<?php echo $this->Form->control('ama', array('type'=>'checkbox', 'id'=>'ama', 'label'=>' AMA', 'escape'=>false)); ?>
								<!--DDO-->
								<?php echo $this->Form->control('accounts', array('type'=>'checkbox', 'id'=>'accounts', 'label'=>' Accounts', 'escape'=>false)); ?>
							</div>
					</div>
				</div>
			</div>

			<div id="lmis_roles" class="form-horizontal">
				<div class="card-body">
					<div class="boxformenus row color1">
						<div class="col-md-3">
							<!--Inward Officer-->
							<?php echo $this->Form->control('inward_officer', array('type'=>'checkbox', 'id'=>'inward_officer', 'label'=>' Inward Officer', 'escape'=>false)); ?>
							<!--RAL/CAL-OIC-->
							<?php echo $this->Form->control('ral_cal_oic', array('type'=>'checkbox', 'id'=>'ral_cal_oic', 'label'=>' RAL/CAL-OIC', 'escape'=>false)); ?>
							<!--Chemist-->
							<?php echo $this->Form->control('chemist', array('type'=>'checkbox', 'id'=>'chemist', 'label'=>' Chemist', 'escape'=>false)); ?>
						</div>
						<div class="col-md-3">
							<!--Chief Chemist-->
							<?php echo $this->Form->control('chief_chemist', array('type'=>'checkbox', 'id'=>'chief_chemist', 'label'=>' Chief Chemist', 'escape'=>false)); ?>
							<!--Lab Incharge-->
							<?php echo $this->Form->control('lab_incharge', array('type'=>'checkbox', 'id'=>'lab_incharge', 'label'=>' Lab In-charge', 'escape'=>false)); ?>
							<!--DOL-->
							<?php echo $this->Form->control('dol', array('type'=>'checkbox', 'id'=>'dol', 'label'=>' DOL', 'escape'=>false)); ?>
						</div>
						<div class="col-md-3">
							<!--Inward Clerk-->
							<?php echo $this->Form->control('inward_clerk', array('type'=>'checkbox', 'id'=>'inward_clerk', 'label'=>' Inward Clerk', 'escape'=>false)); ?>
							<!--Outward Clerk-->
							<?php echo $this->Form->control('outward_clerk', array('type'=>'checkbox', 'id'=>'outword_clerk', 'label'=>' Outward Clerk', 'escape'=>false)); ?>
							<!--RO/SO Officer-->
							<?php echo $this->Form->control('ro_so_officer', array('type'=>'checkbox', 'id'=>'ro_so_officer', 'label'=>' RO/SO Officer', 'escape'=>false)); ?>

						</div>
						<div class="col-md-3">
							<!--RO/SO-OIC-->
							<?php echo $this->Form->control('ro_so_oic', array('type'=>'checkbox', 'id'=>'ro_so_oic', 'label'=>' RO/SO-OIC', 'escape'=>false)); ?>
							<!--Accounts-->
							<?php echo $this->Form->control('accounts', array('type'=>'checkbox', 'id'=>'accounts', 'label'=>' Accounts', 'escape'=>false)); ?>
							<!--Head Office-->
							<?php echo $this->Form->control('head_office', array('type'=>'checkbox', 'id'=>'head_office', 'label'=>' Head Office', 'escape'=>false)); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-3 mt-3">
				<?php echo $this->Form->submit('Add', array('name'=>'add_sms_template', 'id'=>'add_sms_template_btn','label'=>false,'class'=>'btn btn-success')); ?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
