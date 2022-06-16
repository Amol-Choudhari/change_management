<?php ?>
<?php echo $this->Html->css('re_esign_module'); ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-info">Re-Esign</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item active">Re-Esign Module</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle ">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-10">
					<?php echo $this->Form->create(); ?>
						<div class="card card-teal">
							<div class="card-header"><h3 class="card-title-new">Re-Esign Module</h3></div>
								<div class="form-horizontal" id="form_outer_main">
										<div class="card-body">
											<div class="row">
											<p class="alert alert-info">
												Note: <br>1. This Re-Esign is available only for the Granted New/Renewal Certificates, to correct wrong validity date.<br>
												2. This will not change any other details on the certificate, only the date of validity will be updated, also only if earlier printed wrong.<br>
												3. The date of validity will be calculated automatically by the system, and shown for confirmation if proper or not.
											</p>
											<div class="col-sm-6">
												<div class="form-group">
													<label for="field3">Application Id <span class="cRed">*</span></label>
													<?php echo $this->Form->control('appl_id', array('type'=>'select', 'id'=>'appl_id', 'escape'=>false, 'options'=>$appl_list, 'empty'=>'---Select---', 'label'=>false,'class'=>'form-control')); ?>
													<div id="error_appl_id"></div>
													<div id="view_certificate_link"></div>
													<div id="view_valid_upto"></div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label for="field3"><span>Reason to Re-Esign</span></label>
													<?php echo $this->form->control('reason_to_re_esign', array('type'=>'textarea', 'id'=>'reason_to_re_esign', 'escape'=>false, 'label'=>false,'class'=>'form-control')); ?>
													<div id="error_reason_to_re_esign"></div>
												</div>
											</div>
											<div class="col-sm-6">
												<label class="re-esign_check" for="field3">
													<?php echo $this->form->control('re_esign_concent', array('type'=>'checkbox', 'id'=>'re_esign_concent', 'label'=>' Please check this concent before proceeding to re-esign','escape'=>false)); ?>
													<div id="error_re_esign_concent"></div>
												</label>
											</div>

											<div class="col-sm-6 mb-2">
												<div class="appl_re_esigned">
													<label for="field3"><span>Re-Esigned Applications list</span>
														<?php echo $this->form->control('appl_re_esigned', array('type'=>'select', 'multiple'=>'multiple','options'=>$appl_re_esigned, 'label'=>false,'class'=>'form-control')); ?>
													</label>
												</div>
											</div>
										<!-- moved out of form tag on 28-05-2021 for Form Based Esign method -->
										<?php echo $this->element('esign_views/re_esign_pdf_popup'); ?>
									</div>
								</div>
							</div>
							<div class="card-footer cardFooterBackground">
								<label><?php echo $this->form->submit('Proceed', array('id'=>'proceed_btn', 'label'=>false,'class'=>'btn btn-success')); ?></label>
							</div>
						</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</section>
</div>

<?php echo $this->Html->script('othermodules/re_esign_module'); ?>
