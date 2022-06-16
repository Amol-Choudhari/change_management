<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>

<section class="content form-middle form_outer_class" id="form_outer_main">
	<div class="container-fluid">
	  	<h5 class="mt-1 mb-2">Laboratory Firm Details</h5>
	    <div class="row">
			<div class="col-md-12">
				<div class="card card-success">

				<div class="card-header">
					<h3 class="card-title">Initial Details</h3>
				</div>
				<div class="form-horizontal">
					<div class="card-body">
						<div class="row">
						<div class="col-sm-6">
							<div class="form-group row">
								<label for="inputEmail3" class="col-sm-3 col-form-label">Laboratory Name <span class="cRed">*</span></label>
								<div class="col-sm-9">
									<?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name', 'escape'=>false, 'value'=>$firm_details['firm_name'], 'class'=>'form-control input-field', 'label'=>false, 'disabled'=>true)); ?>
								</div>
							</div>
						</div>

						</div>
					</div>
				</div>

				<div id="address">
					<div class="card-header sub-card-header-firm">
						<h3 class="card-title"><i class="fa fa-address-card"></i> Firm Address</h3>
					</div><br>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Address <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('street_address', array('type'=>'textarea', 'id'=>'street_address', 'escape'=>false, 'value'=>$firm_details['street_address'], 'class'=>'form-control input-field', 'label'=>false, 'disabled'=>true )); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Email Id <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('email', array('type'=>'text', 'id'=>'email', 'class'=>'form-control', 'escape'=>false, 'value'=>base64_decode($firm_details['email']), 'label'=>false, 'disabled'=>true  )); //for email encoding ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">State/Region <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('state', array('type'=>'text', 'id'=>'state', 'value'=>$state_list[$firm_details['state']], 'label'=>false, 'class'=>'form-control',  'disabled'=>true )); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">District <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('district', array('type'=>'text', 'id'=>'district', 'value'=>$distict_list[$firm_details['district']], 'label'=>false, 'class'=>'form-control',  'disabled'=>true )); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Pin Code <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('postal_code', array('type'=>'text', 'id'=>'postal_code', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'class'=>'form-control input-field', 'label'=>false,  'disabled'=>true )); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Mobile No. <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('mobile_no', array('type'=>'text', 'escape'=>false, 'value'=>base64_decode($firm_details['mobile_no']), 'label'=>false, 'class'=>'form-control',  'disabled'=>true )); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card-header">
					<h3 class="card-title">Commodities List</h3>
				</div>
				<div class="form-horizontal">
					<div class="card-body">
						<div class="row">
							<div id="export_unit" class="col-sm-6">
								<div class="form-group row">
									<label for="inputEmail3" class="col-sm-3 col-form-label">Commodities List <span class="cRed">*</span></label>
									<div class="col-sm-9">
										<?php echo $this->Form->control('types_of_sub_commodities',  array('type'=>'select', 'id'=>'types_of_sub_commodities', 'options'=>$section_form_details[2], 'values'=>'', 'multiple'=>'multiple', 'escape'=>false, 'label'=>false, 'class'=>'form-control', 'disabled'=>true )); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</section>


	<div id="form_outer_main" class="form-style-3" class="form_outer_class">


	<!-- commented on 16-07-2021 by Amol as per new order updates -->
	<!--	<div class="form-buttons">
			<a href="<?php //echo $this->getRequest()->getAttribute('webroot');?><?php //echo $this->request->getParam('controller');?>/section/2" >Next Section</a>
		</div>-->
	</div>

<?php echo $this->Html->script('element/application_forms/renewal/laboratory/lab_firm_details'); ?>
