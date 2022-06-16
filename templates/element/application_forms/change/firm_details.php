<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
	<section class="content form-middle form_outer_class" id="form_outer_main">
		<div class="container-fluid">
			<h5 class="mt-1 mb-2">
				<?php
					if($firm_details['certification_type'] == 1) { echo 'CA Firm Details'; }
					if($firm_details['certification_type'] == 2) { echo 'Printing Press Firm Details'; }
					if($firm_details['certification_type'] == 3) { echo 'Laboratory Firm Details'; }
				?>
			</h5>
			<div class="row">
				 <div class="col-md-12">
					<div class="card card-success">
						<div class="card-header">
							<h3 class="card-title"><i class="fa fa-tree"></i> Firm Details</h3>
						</div>
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
									  <div class="form-group row">
										<label for="inputEmail3" class="col-sm-4 col-form-label">Firm Name <span class="cRed">*</span></label>
										<div class="col-sm-8">
										  <?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name', 'value'=>$section_form_details[0]['firm_name'], 'class'=>'form-control input-field', 'placeholder'=>'Firm Name', 'label'=>false)); ?>
										</div>
									  </div>
									  <div class="form-group row">
										<label for="inputEmail3" class="col-sm-4 col-form-label">Email Id <span class="cRed">*</span></label>
										<div class="col-sm-8">
										  <?php echo $this->Form->control('email', array('type'=>'text', 'placeholder'=>'firm email id', 'id'=>'email', 'value'=>base64_decode($section_form_details[0]['email']), 'class'=>'form-control input-field', 'label'=>false)); //for email encoding ?>
										</div>
									  </div>
									</div>
									<div class="col-sm-6">
									  <div class="form-group row">
										<label for="inputEmail3" class="col-sm-4 col-form-label">Mobile No. <span class="cRed">*</span></label>
										<div class="col-sm-8">
										  <?php echo $this->Form->control('mobile_no', array('type'=>'text', 'id'=>'mobile_no', 'class'=>'form-control input-field', 'value'=>$section_form_details[0]['mobile_no'], 'placeholder'=>'Mobile No', 'label'=>false)); ?>
										</div>
									  </div>
									  <div class="form-group row">
										<label for="inputEmail3" class="col-sm-4 col-form-label">Phone no.</label>
										<div class="col-sm-8">
										  <?php echo $this->Form->control('fax_no', array('type'=>'text', 'placeholder'=>'Firm Phone no', 'id'=>'fax_no', 'value'=>$section_form_details[0]['fax_no'], 'class'=>'form-control input-field', 'label'=>false)); ?>
										</div>
									  </div>
									</div>
								</div>
							</div>
						</div>
						<div id="director_details">
							<div class="card-header sub-card-header-firm">
								<h3 class="card-title"><i class="fa fa-tree"></i> Director/Partner/Proprietor/Owner Details</h3>
							  </div>
							<div class="form-horizontal">
								<div class="card-body p-0 m-4 border rounded">
								  <div class="">
									<div class="col-sm-12">
										<?php echo $this->element('old_applications_elements/old_app_directors_details_table_view'); ?>
									</div>
								  </div>
								</div>
							 </div>
						 </div>
						<div id="commodity_box">
							  <div class="card-header sub-card-header-firm">
								<h3 class="card-title"><i class="fa fa-tree"></i> Commodities</h3>
							  </div>
							  <div class="form-horizontal">
								<div class="card-body">
								  <div class="row">
									<div class="col-sm-6">
									  <div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Category <span class="cRed">*</span></label>
										<div class="col-sm-9">
										  <?php echo $this->Form->control('commodity', array('type'=>'select', 'id'=>'commodity_category', 'options'=>$section_form_details[3], 'value'=>$section_form_details[0]['commodity'],'label'=>false, 'class'=>'form-control')); ?>
										  <span id="error_commodity_category" class="error invalid-feedback"></span>
										</div>
									  </div>
									  <div id="selected_bevo_nonbevo_msg"></div>
									  <div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Commodities <span class="cRed">*</span></label>
										<div class="col-sm-9">
										  <?php echo $this->Form->control('sub_commodity', array('type'=>'select', 'empty'=>'Select Commodity', 'id'=>'commodity','options'=>$section_form_details[1], 'label'=>false, 'class'=>'form-control')); ?>
										  <span id="error_commodity" class="error invalid-feedback"></span>
										</div>
									  </div>
									</div>
									<div class="col-sm-6">
									  <div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Commodities </label>
										<div class="col-sm-9">
										  <?php echo $this->Form->control('selected_commodity', array('type'=>'select', 'id'=>'selected_commodity', 'options'=>$section_form_details[2], 'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control')); ?>
										  <span id="error_selected_commodity" class="error invalid-feedback"></span>
										</div>
									  </div>
									  <p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
									</div>
								  </div>
								</div>
							  </div>
						  </div>

						  <?php if($firm_type == 2){ ?>
							  <div id="packaging_type_box">
								  <div class="card-header sub-card-header-firm">
									<h3 class="card-title"><i class="fa fa-archive"></i> Packaging Materials</h3>
								  </div><br>
								  <div class="form-horizontal">
									<div class="card-body">
									  <div class="row">
										<div class="col-sm-6">
										  <div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Packaging Material List <span class="cRed">*</span></label>
											<div class="col-sm-9">
											  <?php echo $this->Form->control('packaging_materials_list', array('type'=>'select', 'id'=>'packaging_materials', 'empty'=>'Select', 'options'=>$allPackingType, 'label'=>false, 'class'=>'form-control')); ?>
											</div>
										  </div>
										</div>
										<div class="col-sm-6">
										  <div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Packaging Materials</label>
											<div class="col-sm-9">
											  <?php echo $this->Form->control('packaging_materials', array('type'=>'select', 'id'=>'selected_packaging_materials', 'options'=>$packaging_materials, 'multiple'=>true, 'label'=>false, 'class'=>'form-control')); ?>
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
						  <?php } ?>

						  <div class="card-header sub-card-header-firm">
							<h3 class="card-title"><i class="fa fa-address-card"></i> Premises Address</h3>
						  </div>
						  <div class="form-horizontal">
							<div class="card-body">
							  <div class="row">
								<div class="col-sm-6">
								  <div class="form-group row">
									<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
									<div class="col-sm-9">
									  <?php echo $this->Form->control('street_address', array('type'=>'textarea', 'id'=>'street_address', 'placeholder'=>'Enter street address', 'value'=>$section_form_details[0]['street_address'], 'class'=>'form-control input-field', 'label'=>false)); ?>
									  <span id="error_street_address" class="error invalid-feedback"></span>
									</div>
								  </div>
								</div>
								<div class="col-sm-6">
								  <div class="form-group row">
									<label for="inputPassword3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
									<div class="col-sm-9">
									   <?php echo $this->Form->control('state', array('type'=>'text', 'value'=>$state_list[$section_form_details[0]['state']], 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
									  <span id="error_state" class="error invalid-feedback"></span>
									</div>
								  </div>
								  <div class="form-group row">
									<label for="inputPassword3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
									<div class="col-sm-9">
									   <?php echo $this->Form->control('district', array('type'=>'text', 'value'=>$distict_list[$section_form_details[0]['district']], 'label'=>false, 'disabled'=>'disabled', 'class'=>'form-control')); ?>
									  <span id="error_district" class="error invalid-feedback"></span>
									</div>
								  </div>
								  <div class="form-group row">
									<label for="inputPassword3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
									<div class="col-sm-9">
									  <?php echo $this->Form->control('postal_code', array('type'=>'text', 'value'=>$section_form_details[0]['postal_code'], 'class'=>'form-control input-field', 'label'=>false)); ?>
									  <span id="error_postal_code" class="error invalid-feedback"></span>
									</div>
								  </div>
								</div>
							  </div>
							</div>
						</div>
				 </div>
			<div>
		</div>
	</section>
	<?php  echo $this->Html->script('element/application_forms/change/change_add_firms');	?>
