<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Update Firm Details</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item"><?php echo $this->Html->link('List of Firms', array('controller' => 'othermodules', 'action'=>'firms_list_to_update'));?></a></li>
						<li class="breadcrumb-item active">Edit Firm Details</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle ">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-10">
					<?php echo $this->Form->create(null, array('id'=>'edit_firm_details')); ?>
						<div class="card card-cyan">
							<div class="card-header"><h3 class="card-title-new">Firm Details</h3></div>
								<div class="form-horizontal">
									<?php if(!empty($email_updated) || !empty($mob_updated)){ ?>
										<label class="badge">Note:The last updated field is bordered with green color.</label>
										<div class="clear"></div>
									<?php } ?>
									<div class="card-body">
										<?php if(!empty($return_message)) { ?>
											<label class="cgfs15tal"><?php echo $return_message; ?></label>
											<div class="clear"></div>
										<?php } ?>
										<div class="row">
											<div class="col-md-6">
												<label class="col-form-label">Applicant ID : <span class="cRed">*</span></label>
												<?php echo $this->Form->control('appl_id', array('type'=>'text','id'=>'appl_id','value'=>$firm_details['customer_id'], 'class'=>'form-control', 'label'=>false,'readonly'=>true)); ?>
											</div>

											<!-- hidden fields, to be used to map if user chnage the field value or not to show update button-->
											<?php if ($model == 'DmiCustomers') { $mob_field = 'mobile'; }else{ $mob_field = 'mobile_no'; } ?>
											<?php echo $this->Form->control('last_mobile_no', array('type'=>'hidden',  'id'=>'last_mobile_no','value'=>base64_decode($firm_details[$mob_field]))); ?>
											<?php echo $this->Form->control('last_email', array('type'=>'hidden',  'id'=>'last_email','value'=>base64_decode($firm_details['email']))); //for email encoding ?>

											<?php if ($model == 'DmiCustomers') { ?>

						              <div class="col-md-6">
														<label class="col-form-label">Firm Name : <span class="cRed">*</span></label>
															<?php echo $this->Form->control('firm_name', array('type'=>'text','id'=>'firm_name','value'=>$firm_details['f_name']." ".$firm_details['l_name'], 'class'=>'form-control', 'label'=>false,'readonly'=>true)); ?>
											    </div>

											<?php } else { ?>

                        <div class="col-md-6">
													<label class="col-form-label">Firm Name : <span class="cRed">*</span></label>
													<?php echo $this->Form->control('firm_name', array('type'=>'text','id'=>'firm_name','value'=>$firm_details['firm_name'], 'class'=>'form-control', 'label'=>false,'readonly'=>true)); ?>
					    					</div>

                    <?php } ?>

                    <?php if ($model == 'DmiCustomers') { ?>

                        <div class="col-md-6">
													<label class="col-form-label">Mobile : <span class="cRed">*</span></label>
                            <?php echo $this->Form->control('mobile_no', array('type'=>'text', 'placeholder'=>'Enter New Mobile', 'id'=>'mobile_no','value'=>base64_decode($firm_details['mobile']), 'class'=>'form-control', 'label'=>false)); ?>
                            <div id="error_mobile_no" class="error invalid-feedback"></div>
                        </div>

                    <?php }else{ ?>

                        <div class="col-md-6">
													<label class="col-form-label">Mobile : <span class="cRed">*</span></label>
                            <?php echo $this->Form->control('mobile_no', array('type'=>'text', 'placeholder'=>'Enter New Mobile', 'id'=>'mobile_no','value'=>base64_decode($firm_details['mobile_no']), 'class'=>'form-control', 'label'=>false)); ?>
                            <div id="error_mobile_no" class="error invalid-feedback"></div>
                        </div>

                    <?php } ?>

											<div class="col-md-6">
												<label class="col-form-label">Email : <span class="cRed">*</span></label>
	                        <?php echo $this->Form->control('email', array('type'=>'text', 'placeholder'=>'Enter New Email', 'id'=>'email','value'=>base64_decode($firm_details['email']), 'class'=>'form-control', 'label'=>false)); //for email encoding ?>
	                        <div id="error_email" class="error invalid-feedback"></div>
											</div>

									<?php if(empty($return_message)) { ?>
										<div class="col-md-6">
											<label class="col-form-label">Reason : <span class="cRed">*</span></label>
											<?php echo $this->Form->control('Reason/Remark', array('type'=>'textarea','name'=>'reason', 'placeholder'=>'Enter Reason/Remark', 'id'=>'reason', 'class'=>'form-control', 'label'=>false)); ?>
											<div id="error_reason" class="error invalid-feedback"></div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="cardFooterBackground card-footer mt-2">
							<?php if(!empty($btn_to_re_esign)) { ?>
								<label class="re-esign_check" for="field3">
									<?php echo $this->Form->control('re_esign_concent', array('type'=>'checkbox', 'id'=>'re_esign_concent', 'label'=>'I confirm the changes and proceed to re-esign','escape'=>false)); ?>
									<div id="error_re_esign_concent" class="error invalid-feedback"></div>
								</label>
							<?php }else{ ?>

								<div class="clear"></div>

								<?php if($type=='firm' && !empty($is_firm_granted) && $is_firm_granted['user_email_id'] != 'old_application'){ ?>
									<p class="badge">Note: Once updated the firm details, kindly re-esign the certificate to reflect the change on certificate</p>

								<?php } } ?>

								<div class="col-md-3">
										<?php if(!empty($btn_to_re_esign)) { ?>
												<?php echo $this->form->submit('Re-Esign Certificate', array('name'=>'proceed_btn','id'=>'proceed_btn', 'label'=>false,'class'=>'float-right btn btn-success')); ?>
														<?php } elseif(empty($return_message)) { ?>
												<?php echo $this->Form->submit('Update', array('name'=>'update_details','id'=>'update_details','label'=>false,'class'=>'float-left btn btn-success')); ?>
										<?php } ?>
								</div>

								<?php if(empty($btn_to_re_esign)) { ?>
										<a href="../othermodules/firms_list_to_update" class="btn btn-secondary float-right">Back</a>
								<?php } ?>
						</div>
						<?php echo $this->Form->end(); ?>
						<?php echo $this->element('esign_views/re_esign_pdf_popup'); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>



<input type="hidden" id="email_updated" value="<?php echo $email_updated; ?>">
<input type="hidden" id="mob_updated" value="<?php echo $mob_updated; ?>">
<?php echo $this->Html->script('othermodules/update_firm_details'); ?>
