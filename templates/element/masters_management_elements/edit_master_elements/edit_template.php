<?php ?>
	<div class="col-md-12">
	 	<div class="row">
			<div class="col-md-6">
				<label>Short Description <span class="cRed">*</span></label>
					<?php echo $this->Form->control('description', array('type'=>'textarea', 'id'=>'description', 'value'=>$record_details['description'], 'label'=>false,'class'=>'form-control', 'placeholder'=>'Enter Short Description Here')); ?>
				<span id="error_description" class="error invalid-feedback"></span>
			</div>
			<div class="col-md-6">
				<label>SMS Message <span class="cRed">*</span></label>
					<?php echo $this->Form->control('sms_message', array('type'=>'textarea', 'id'=>'sms_message','class'=>'form-control', 'value'=>$record_details['sms_message'], 'label'=>false)); ?>
				<span id="error_sms_message" class="error invalid-feedback"></span>
			</div>
			<div class="col-md-6 mt-3">
				<label>Subject <span class="cRed">*</span></label>
					<?php echo $this->Form->control('email_subject', array('type'=>'text', 'id'=>'email_subject','class'=>'form-control', 'value'=>$record_details['email_subject'], 'label'=>false)); ?>
				<span id="error_email_subject" class="error invalid-feedback"></span>
			</div>
			<div class="col-md-6 mt-3">
				<label>Email Message <span class="cRed">*</span></label>
				<?php echo $this->Form->control('email_message', array('type'=>'textarea', 'id'=>'email_message','class'=>'form-control', 'value'=>$record_details['email_message'], 'label'=>false)); ?>
                <span id="error_email_message" class="error invalid-feedback"></span>
            </div>

			<div class="col-md-12">
				<label class="badge badge-success">Send To :
					<?php
						$options=array('dmi'=>'DMI','lmis'=>'LMIS');
						$attributes=array('legend'=>false, 'value'=>$record_details['template_for'], 'id'=>'template_for');
						echo $this->form->radio('template_for',$options,$attributes);
					?>
				</label>
                <span id="error_send_to" class="error invalid-feedback"></span>

                <div id="dmi_roles" class="form-horizontal">
					<div class="card-body">
						<div class="boxformenus row color1">
							<div class="col-md-3">
								<?php //Applicant
									if (in_array(0,$existed_destination_array)) {
										echo $this->Form->control('applicant', array('type'=>'checkbox', 'checked'=>true, 'id'=>'applicant', 'label'=>' Applicant'));
									} else {
										echo $this->Form->control('applicant', array('type'=>'checkbox', 'checked'=>false, 'id'=>'applicant', 'label'=>' Applicant'));
									}
								?>

								<?php //MO/SMO (Scrutinizer)
									if (in_array(1,$existed_destination_array)) {
										echo $this->Form->control('mo_smo', array('type'=>'checkbox', 'checked'=>true, 'id'=>'mo_smo', 'label'=>' Scrutinizer'));
									} else {
										echo $this->Form->control('mo_smo', array('type'=>'checkbox', 'checked'=>false, 'id'=>'mo_smo', 'label'=>' Scrutinizer'));
									}
								?>


								<?php //Inspection Officer
									if (in_array(2,$existed_destination_array)) {
										echo $this->Form->control('io', array('type'=>'checkbox', 'checked'=>true, 'id'=>'io', 'label'=>' Inspection Officer'));
									} else {
										echo $this->Form->control('io', array('type'=>'checkbox', 'checked'=>false, 'id'=>'io', 'label'=>' Inspection Officer'));
									}
								?>

							</div>


							<div class="col-md-3">
								<?php //RO/SO (Nodal Officer)
									if (in_array(3,$existed_destination_array)) {
										echo $this->Form->control('ro_so', array('type'=>'checkbox', 'checked'=>true, 'id'=>'ro_so', 'label'=>' Nodal Officer'));
									} else {
										echo $this->Form->control('ro_so', array('type'=>'checkbox', 'checked'=>false, 'id'=>'ro_so', 'label'=>' Nodal Officer'));
									}
								?>
								<?php // Dy.AMA
									if (in_array(4,$existed_destination_array)){
										echo $this->Form->control('dy_ama', array('type'=>'checkbox', 'checked'=>true, 'id'=>'dy_ama', 'label'=>' Dy AMA'));
									} else {
										echo $this->Form->control('dy_ama', array('type'=>'checkbox', 'checked'=>false, 'id'=>'dy_ama', 'label'=>' Dy AMA'));
									}
								?>

								<?php //Jt.AMA
									if (in_array(5,$existed_destination_array)) {
										echo $this->Form->control('jt_ama', array('type'=>'checkbox', 'checked'=>true, 'id'=>'jt_ama', 'label'=>' Jt AMA'));
									}else{
										echo $this->Form->control('jt_ama', array('type'=>'checkbox', 'checked'=>false, 'id'=>'jt_ama', 'label'=>' Jt AMA'));
									}
								?>

							</div>


							<div class="col-md-3">

								<?php //HO/MO/SMO
									if (in_array(6,$existed_destination_array)) {
										echo $this->Form->control('ho_mo_smo', array('type'=>'checkbox', 'checked'=>true, 'id'=>'ho_mo_smo', 'label'=>' Scrutiny(HO)'));
									} else {
										echo $this->Form->control('ho_mo_smo', array('type'=>'checkbox', 'checked'=>false, 'id'=>'ho_mo_smo', 'label'=>' Scrutiny(HO)'));
									}
								?>
								<?php //AMA
									if (in_array(7,$existed_destination_array)) {
										echo $this->Form->control('ama', array('type'=>'checkbox', 'checked'=>true, 'id'=>'ama', 'label'=>' AMA'));
									}else{
										echo $this->Form->control('ama', array('type'=>'checkbox', 'checked'=>false, 'id'=>'ama', 'label'=>' AMA'));
									}
								?>

								<?php //DDO
									if (in_array(8,$existed_destination_array)) {
										echo $this->Form->control('accounts', array('type'=>'checkbox', 'checked'=>true, 'id'=>'accounts', 'label'=>' Accounts'));
									}else{
										echo $this->Form->control('accounts', array('type'=>'checkbox', 'checked'=>false, 'id'=>'accounts', 'label'=>' Accounts'));
									}
								?>
							</div>


							<div class="col-md-3">

								<?php //Chemist User
									if (in_array(10,$existed_destination_array)) {
										echo $this->Form->control('chemist_user', array('type'=>'checkbox', 'checked'=>true, 'id'=>'chemist_user', 'label'=>' Chemist User'));
									}else{
										echo $this->Form->control('chemist_user', array('type'=>'checkbox', 'checked'=>false, 'id'=>'chemist_user', 'label'=>' Chemist User'));
									}
								?>


								<?php //RO Incharge
									if (in_array(9,$existed_destination_array)) {
										echo $this->Form->control('ro_incharge', array('type'=>'checkbox', 'checked'=>true, 'id'=>'ro_incharge', 'label'=>' RO Incharge'));
									}else{
										echo $this->Form->control('ro_incharge', array('type'=>'checkbox', 'checked'=>false, 'id'=>'ro_incharge', 'label'=>' RO Incharge'));
									}
								?>
							</div>
						</div>
					</div>
				</div>

				<div id="lmis_roles" class="form-horizontal">
					<div class="card-body">
						<div class="boxformenus row color1">
							<div class="col-md-3">
								<?php //Inward Officer
									if (in_array(101,$existed_destination_array)) {
										echo $this->Form->control('inward_officer', array('type'=>'checkbox', 'checked'=>true, 'id'=>'inward_officer', 'label'=>' Inward Officer'));
									} else {
										echo $this->Form->control('inward_officer', array('type'=>'checkbox', 'checked'=>false, 'id'=>'inward_officer', 'label'=>' Inward Officer'));
									}
								?>
								<?php //RAL/CAL-OIC
									if (in_array(102,$existed_destination_array)) {
										echo $this->Form->control('ral_cal_oic', array('type'=>'checkbox', 'checked'=>true, 'id'=>'ral_cal_oic', 'label'=>' RAL/CAL-OIC'));
									} else {
										echo $this->Form->control('ral_cal_oic', array('type'=>'checkbox', 'checked'=>false, 'id'=>'ral_cal_oic', 'label'=>' RAL/CAL-OIC'));
									}
								?>
								<?php //Chemist
									if (in_array(103,$existed_destination_array)) {
										echo $this->Form->control('chemist', array('type'=>'checkbox', 'checked'=>true, 'id'=>'chemist', 'label'=>' Chemist'));
									} else {
										echo $this->Form->control('chemist', array('type'=>'checkbox', 'checked'=>false, 'id'=>'chemist', 'label'=>' Chemist'));
									}
								?>
							</div>

							<div class="col-md-3">
								<?php //Cheif Chemist
									if (in_array(104,$existed_destination_array)) {
										echo $this->Form->control('chief_chemist', array('type'=>'checkbox', 'checked'=>true, 'id'=>'chief_chemist', 'label'=>' Cheif Chemist'));
									} else {
										echo $this->Form->control('chief_chemist', array('type'=>'checkbox', 'checked'=>false, 'id'=>'chief_chemist', 'label'=>' Cheif Chemist'));
									}
								?>
								<?php //Lab Incharge
									if (in_array(105,$existed_destination_array)) {
										echo $this->Form->control('lab_incharge', array('type'=>'checkbox', 'checked'=>true, 'id'=>'lab_incharge', 'label'=>' Lab Incharge'));
									} else {
										echo $this->Form->control('lab_incharge', array('type'=>'checkbox', 'checked'=>false, 'id'=>'lab_incharge', 'label'=>' Lab Incharge'));
									}
								?>

								<?php //DOL
									if (in_array(106,$existed_destination_array)) {
										echo $this->Form->control('dol', array('type'=>'checkbox', 'checked'=>true, 'id'=>'dol', 'label'=>' DOL'));
									} else {
										echo $this->Form->control('dol', array('type'=>'checkbox', 'checked'=>false, 'id'=>'dol', 'label'=>' DOL'));
									}
								?>
							</div>

							<div class="col-md-3">
								<?php //Inward Clerk
									if (in_array(107,$existed_destination_array)) {
										echo $this->Form->control('inward_clerk', array('type'=>'checkbox', 'checked'=>true, 'id'=>'inward_clerk', 'label'=>' Inward Clerk'));
									} else {
										echo $this->Form->control('inward_clerk', array('type'=>'checkbox', 'checked'=>false, 'id'=>'inward_clerk', 'label'=>' Inward Clerk'));
									}
								?>

								<?php //Outward Clerk
									if (in_array(108,$existed_destination_array)) {
										echo $this->Form->control('outward_clerk', array('type'=>'checkbox', 'checked'=>true, 'id'=>'outward_clerk', 'label'=>' Outward Clerk'));
									} else {
										echo $this->Form->control('outward_clerk', array('type'=>'checkbox', 'checked'=>false, 'id'=>'outward_clerk', 'label'=>' Outward Clerk'));
									}
								?>

								<?php //RO/SO Officer
									if (in_array(109,$existed_destination_array)) {
										echo $this->Form->control('ro_so_officer', array('type'=>'checkbox', 'checked'=>true, 'id'=>'ro_so_officer', 'label'=>' RO/SO Officer'));
									} else {
										echo $this->Form->control('ro_so_officer', array('type'=>'checkbox', 'checked'=>false, 'id'=>'ro_so_officer', 'label'=>' RO/SO Officer'));
									}
								?>
							</div>

							<div class="col-md-3">
								<?php //RO/SO OIC
									if (in_array(110,$existed_destination_array)) {
										echo $this->Form->control('ro_so_oic', array('type'=>'checkbox', 'checked'=>true, 'id'=>'ro_so_oic', 'label'=>' RO/SO-OIC'));
									} else {
										echo $this->Form->control('ro_so_oic', array('type'=>'checkbox', 'checked'=>false, 'id'=>'ro_so_oic', 'label'=>' RO/SO-OIC'));
									}
								?>

								<?php //Accounts
									if (in_array(111,$existed_destination_array)) {
										echo $this->Form->control('accounts', array('type'=>'checkbox', 'checked'=>true, 'id'=>'accounts', 'label'=>' Accounts'));
									} else {
										echo $this->Form->control('accounts', array('type'=>'checkbox', 'checked'=>false, 'id'=>'accounts', 'label'=>' Accounts'));
									}
								?>

								<?php //Haed Officer
									if (in_array(112,$existed_destination_array)) {
										echo $this->Form->control('head_office', array('type'=>'checkbox', 'checked'=>true, 'id'=>'head_office', 'label'=>' Head Office'));
									} else {
										echo $this->Form->control('head_office', array('type'=>'checkbox', 'checked'=>false, 'id'=>'head_office', 'label'=>' Head Office'));
									}
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3 mt-3">
				<?php echo $this->Form->control('Edit', array('name'=>'edit_sms_template','type'=>'submit', 'id'=>'edit_sms_template_btn','class'=>'btn btn-success', 'label'=>false)); ?>
			</div>
		</div>
	</div>
</div>
