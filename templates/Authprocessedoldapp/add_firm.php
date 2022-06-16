<?php ?>

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<label class="badge badge-primary">Create Firm</label>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Old Application Entry', array('controller' => 'authprocessedoldapp', 'action'=>'home'));?></li>
							<li class="breadcrumb-item active">Add Firm</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<?php echo $this->Form->create(null, array('id'=>'add_firm_form')); ?>
							<div class="card card-success">
								<div class="card-header"><h3 class="card-title"><i class="far fa-id-card"></i> Primary ID</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-4 col-form-label">Select Primary Id <span class="cRed">*</span></label>
													<div class="col-sm-8">
														<?php echo $this->Form->control('primary_id', array('type'=>'select', 'id'=>'primary_id', 'empty'=>'--Select--', 'options'=>$primary_id_list, 'label'=>false,'class'=>'form-control')); ?>
														<span id="error_primary_id" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Name <span class="cRed">*</span></label>
													<div class="col-sm-9"><div id="show_firm_name" class="form-control"></div></div>
												</div>
											</div>
										</div>
									</div>
								</div>

                                <div class="card-header"><h3 class="card-title"><i class="fas fa-certificate"></i> Type of Certification</h3></div>
                                <div class="form-horizontal">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-4 col-form-label">Choose from list <span class="cRed">*</span></label>
                                                    <div class="col-sm-8">
                                                        <?php echo $this->Form->control('certification_type', array('type'=>'select', 'id'=>'certification_type', 'options'=>$certificate_type, 'label'=>false,'class'=>'form-control')); ?>
                                                        <span id="error_certification_type" class="error invalid-feedback"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="export_unit" class="col-sm-6">
                                                <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-3 col-form-label">Export Unit ?</label>
                                                    <div class="col-sm-9">
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="export_unit" checked="" id="radioSuccess1" value="yes">
                                                            <label for="radioSuccess1">Yes</label>
                                                        </div>
                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="export_unit" id="radioSuccess2" value="no" checked>
                                                            <label for="radioSuccess2">No</label>
                                                        </div>
                                                        <span id="error_export_unit" class="error invalid-feedback"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- provision to select the sponsored CA for printing press 
                                Done by Pravin Bhakare 18-10-2020 -->
                                <div id="sponsored_press_by_ca" class="dnone card-primary">
                                    <div class="card-header bg-success"><h3 class="card-title"><i class="fas fa-certificate"></i> Sponsored Printing Press</h3></div>
                                    <div class="form-horizontal">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-6 col-form-label">Is Press Sponsored By CA<span class="cRed">*</span></label>
                                                        <div class="col-sm-6">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" name="is_sponsored_press" id="is_sponsored_pressYes" value="yes">
                                                                <label for="is_sponsored_pressYes">Yes</label>
                                                            </div>
                                                            <div class="icheck-primary d-inline">
                                                                <input type="radio" name="is_sponsored_press" id="is_sponsored_pressNo" value="no" checked>
                                                                <label for="is_sponsored_pressNo">No</label>
                                                            </div>
                                                            <span id="error_is_sponsored_press" class="error invalid-feedback"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 sponsored_cas dnone">
                                                    <div class="form-group row">
                                                        <label for="inputEmail3" class="col-sm-3 col-form-label">Sponsored CA <span class="cRed">*</span></label>
                                                        <div class="col-sm-9">
                                                            <?php echo $this->Form->control('sponsored_ca', array('type'=>'select', 'options'=>$sponsored_cas, 'empty'=>'Select Sponsored CA','id'=>'sponsored_ca', 'class'=>'form-control input-field', 'label'=>false)); ?>
                                                            <span id="error_sponsored_ca" class="error invalid-feedback"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End -->

                                <div class="card-header bg-success"><h3 class="card-title"><i class="fas fa-clipboard-check"></i> Certificate Granted</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Is Certificate Already Granted?</label>
													<div class="col-sm-6">
														<div class="icheck-primary d-inline">
															<input type="radio" name="is_already_granted" checked="" id="radioPrimary1" value="yes" checked>
															<label for="radioPrimary1">Yes</label>
														</div>
														<span id="error_export_unit" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div id="old_granted_certificate">
									<div class="card-header bg-success"><h3 class="card-title"><i class="fas fa-info-circle"></i> Granted Certificate Details</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-4 col-form-label">Certificate No. <span class="cRed">*</span></label>
														<div class="col-sm-8">
															<?php echo $this->Form->control('old_certificate_no', array('type'=>'text', 'placeholder'=>'Enter Certification Number', 'id'=>'certification_no', 'class'=>'form-control input-field', 'label'=>false)); ?>
															<span id="error_certificate_no" class="error invalid-feedback"></span>
															<span id="duplicate_certification_no_error" class="error invalid-feedback"><?php if(!empty($duplicate_certification_no_msg)){ echo $duplicate_certification_no_msg;}?></span>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-4 col-form-label">Date of Grant <span class="cRed">*</span></label>
														<div class="col-sm-8">
															<?php echo $this->Form->control('grant_date', array('type'=>'text', 'placeholder'=>'Enter Certificate Grant Date', 'readonly'=>'true', 'id'=>'grant_date', 'class'=>'form-control input-field', 'label'=>false)); ?>
															<span id="error_grant_date" class="error invalid-feedback"></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- This field added on 02-10-2017 by Pravin to add multiple old renewal dates -->
								<div id="last_renewal_details">
									<div class="card-header bg-success"><h3 class="card-title"><i class="fas fa-info-circle"></i> All Renewal Details</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group row input_fields_container">
														<label for="inputEmail3" class="col-sm-3 col-form-label">Year Of Renewal <span class="cRed">*</span></label>
														<div class="col-sm-9">
															<input type="text" class="year-of-renewal form-control" id="static_renewal_dates1" value="" readonly="true">
															<input type="text" name="renewal_dates[]" id="last_renewal_dates1" class="renewal_dates_input form-control" readonly="true">
															<button class="btn btn-sm btn-success add_more_button">Add More</button>
															<span id="error_renewal_dates1" class="error invalid-feedback"></span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

                                <!-- End Check added firm is new or old granted firm 26-09-2017-->
                                <div class="card-header bg-success"><h3 class="card-title"><i class="far fa-building"></i> Firm Details</h3></div>
                                <div class="form-horizontal">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Firm Name <span class="cRed">*</span></label>
                                                    <div class="col-sm-9">
                                                        <?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name', 'class'=>'form-control input-field', 'placeholder'=>'Enter Firm Name', 'label'=>false)); ?>
                                                        <span id="error_firm_name" class="error invalid-feedback"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
                                                    <div class="col-sm-9">
                                                        <?php echo $this->Form->control('email', array('type'=>'text', 'placeholder'=>'Enter firm email id', 'id'=>'email', 'class'=>'form-control input-field', 'label'=>false)); ?>
                                                        <span id="error_email" class="error invalid-feedback"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
                                                    <div class="col-sm-9">
                                                        <?php echo $this->Form->control('mobile_no', array('type'=>'text', 'placeholder'=>'Enter mobile no. here', 'id'=>'mobile_no', 'class'=>'form-control input-field', 'label'=>false)); ?>
                                                        <span id="error_mobile_no" class="error invalid-feedback"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="inputEmail3" class="col-sm-3 col-form-label">Phone no. <span class="cRed">*</span></label>
                                                    <div class="col-sm-9">
                                                        <?php echo $this->Form->control('fax_no', array('type'=>'text', 'placeholder'=>'Enter Phone no. here', 'id'=>'fax_no', 'class'=>'form-control input-field', 'label'=>false)); ?>
                                                        <span id="error_fax_no" class="error invalid-feedback"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="commodity_box">
                                    <div class="card-header bg-success"><h3 class="card-title"><i class="fa fa-tree"></i> Commodities</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-4 col-form-label">Category <span class="cRed">*</span></label>
														<div class="col-sm-8">
															<?php echo $this->Form->control('commodity', array('type'=>'select', 'id'=>'commodity_category', 'empty'=>'Select Category', 'options'=>$commodity_categories,'label'=>false, 'class'=>'form-control onchangegetcommodity')); ?>
															<span id="error_commodity_category" class="error invalid-feedback"></span>
														</div>
													</div>
													<div id="selected_bevo_nonbevo_msg"></div>
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-4 col-form-label">Commodities <span class="cRed">*</span></label>
														<div class="col-sm-8">
															<?php echo $this->Form->control('sub_commodity', array('type'=>'select', 'empty'=>'Select Commodity', 'id'=>'commodity','options'=>array(), 'label'=>false, 'class'=>'form-control')); ?>
															<span id="error_commodity" class="error invalid-feedback"></span>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Commodities </label>
														<div class="col-sm-9">
															<?php echo $this->Form->control('selected_commodity', array('type'=>'select', 'id'=>'selected_commodity', 'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control')); ?>
															<span id="error_selected_commodity" class="error invalid-feedback"></span>
														</div>
													</div>
													<p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div id="total_charge_box">
									<div class="card-header bg-success"><h3 class="card-title"><i class="fa fa-credit-card"></i> Processing Fee</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-5 col-form-label">Processing Fee(RS.):</label>
														<div class="col-sm-7 show_charge">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div id="packaging_type_box">
									<div class="card-header bg-success"><h3 class="card-title"><i class="fas fa-industry"></i> Packaging Materials</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-3 col-form-label">Packaging Material List <span class="cRed">*</span></label>
														<div class="col-sm-9">
															<?php echo $this->Form->control('packaging_materials_list', array('type'=>'select', 'id'=>'packaging_materials', 'empty'=>'Select', 'options'=>$packaging_materials, 'label'=>false, 'class'=>'form-control')); ?>
														</div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group row">
														<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Packaging Materials</label>
														<div class="col-sm-9">
															<?php echo $this->Form->control('packaging_materials', array('type'=>'select', 'id'=>'selected_packaging_materials', 'multiple'=>true, 'label'=>false, 'class'=>'form-control')); ?>
															<span id="error_packaging_materials" class="error invalid-feedback"></span>
														</div>
													</div>
													<div class="form-group row" id="other_packaging_details_box">
														<label for="inputEmail3" class="col-sm-3 col-form-label">Other Packaging Details</label>
														<div class="col-sm-9">
															<?php echo $this->Form->control('other_packaging_details', array('type'=>'text', 'id'=>'other_packaging_details', 'placeholder'=>'Enter Other Packaging details', 'class'=>'form-control input-field', 'label'=>false)); ?>
															<span id="error_other_packaging" class="error invalid-feedback"></span>
														</div>
													</div>
													<p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="card-header bg-success"><h3 class="card-title"><i class="far fa-address-card"></i> Premises Address</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
													<div class="col-sm-9">
														<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'id'=>'street_address', 'placeholder'=>'Enter street address', 'class'=>'form-control input-field', 'label'=>false)); ?>
														<span id="error_street_address" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-4 col-form-label">State/Region <span class="cRed">*</span></label>
														<div class="col-sm-8">
														<?php echo $this->Form->control('state', array('type'=>'select', 'id'=>'state', 'empty'=>'Select State', 'options'=>$states, 'label'=>false,'class'=>'form-control onchangegetdistrict')); ?>
														<span id="error_state" class="error invalid-feedback"></span>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-4 col-form-label">District <span class="cRed">*</span></label>
													<div class="col-sm-8">
														<?php echo $this->Form->control('district', array('type'=>'select', 'id'=>'district', 'empty'=>'Select District','options'=>array(), 'label'=>false, 'class'=>'form-control')); ?>
														<span id="error_district" class="error invalid-feedback"></span>
													</div>
												</div>
												<div class="form-group row">
													<label for="inputPassword3" class="col-sm-4 col-form-label">Pin Code <span class="cRed">*</span></label>
													<div class="col-sm-8">
														<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'placeholder'=>'Enter Postal/Zip code', 'class'=>'input-field', 'label'=>false, 'class'=>'form-control')); ?>
														<span id="error_postal_code" class="error invalid-feedback"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer d-flex">
										<?php echo $this->Form->submit('Save', array('name'=>'save', 'id'=>'save','label'=>false, 'class'=>'btn btn-success float-right onclickValidation')); ?>
										<span class="col-md-11">
											<?php echo $this->Form->control('Reset', array('type'=>'reset', 'id'=>'reset_btn', 'label'=>false, 'class'=>'btn btn-outline-dark float-left')); ?>
											<?php echo $this->Html->link('Back', array('controller' => 'authprocessedoldapp', 'action'=>'home'),array('class'=>'btn btn-secondary float-right'));?>
										</span>
									</div>
								</div>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</section>
	</div>
	
    <input type="hidden" value="<?php echo $return_error_msg; ?>" id="return_error_msg"/>

	<!-- // Show pop-up message box before enter previous renewals details history.
		 // Done by Pravin Bhakare on 31-01-2019
		 // Suggest By Navin Sir
		 // Why : show message box because user enter wrong renewals date history
	-->
	<?php  echo $this->element('old_applications_elements/inforamtion_input_previous_renewals_dates'); ?>

	<?php 
		echo $this->Html->script('../multipleselect/js/bootstrap.min.js');
		echo $this->Html->script('../multipleselect/css/bootstrap-multiselect.css');
		echo $this->Html->script('../multipleselect/js/bootstrap-multiselect.js');				
	?>
	
	<?php echo $this->Html->script('authprocessedoldapp/add_firm/add_firm'); ?>

	<div class="modal fade" id="export_unit_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<i class="fa fa-info-circle"></i> Note: If you are applying for export unit, then select 'Yes' option, else select 'No' option.
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Ok</button>
				</div>
			</div>
		</div>
	</div>

