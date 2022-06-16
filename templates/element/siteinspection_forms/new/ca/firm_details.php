<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>$section)); ?>
	
<section class="content form-middle">
	<div class="container-fluid">
		<h5 class="mt-1 mb-2 tacfw700">Firm Details</h5>
		<div class="row">
			<div  class="col-md-12">
				<div class="card card-success" id="form_outer_main">
					<div class="card-header"><h3 class="card-title">Initial Details</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-form-label col-sm-3">Firm Name <span class="cRed">*</span></label>
											<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('firm_name', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['firm_name'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-form-label col-sm-3"><span>Firm Status <span class="cRed">*</span></span></label>
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('firm_status', array('type'=>'text', 'escape'=>false, 'value'=>$business_type[$section_form_details[0]['business_type']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="card-header"><h3 class="card-title">Names of Commodities Proposed to be Graded</h3></div>
					<div class="form-horizontal marginB10">
						<div class="card-body pb-2 mb-5">
							<div class="row">
								<label for="field3" class="col-form-label col-sm-3"><span>Commodities List  <span class="cRed">*</span></span></label>
								<div class="col-sm-6">
									<div class="form-group row">
										<div class="custom-file col-sm-9">
											<?php echo $this->Form->control('commodity_name', array('type'=>'select', 'escape'=>false, 'options'=>$section_form_details[2], 'multiple'=>true, 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="card-header"><h3 class="card-title">Firm Address</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-sm-3 col-form-label"><span>Firm Address <span class="cRed">*</span></span></label>
										<div class="custom-file col-sm-8">
											<?php echo $this->Form->control('street_address', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['street_address'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-sm-3 col-form-label"><span>State/Region <span class="cRed">*</span></span></label>
											<div class="custom-file col-sm-8">
											<?php echo $this->Form->control('state', array('type'=>'text', 'escape'=>false, 'value'=>$state_list[$firm_details['state']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-sm-3 col-form-label"><span>District  <span class="cRed">*</span></span></label>
										<div class="custom-file col-sm-8">
											<?php echo $this->Form->control('district', array('type'=>'text', 'escape'=>false, 'value'=>$distict_list[$firm_details['district']], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-sm-3 col-form-label"><span>Pin Code   <span class="cRed">*</span></span></label>
										<div class="custom-file col-sm-8">
											<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</div>																								
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-header"><h3 class="card-title">Firm Contact</h3></div>
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-sm-3 col-form-label"><span>Email Id <span class="cRed">*</span></span></label>
										<div class="custom-file col-sm-8">
											<?php echo $this->Form->control('firm_email_id', array('type'=>'text', 'escape'=>false, 'value'=>base64_decode($firm_details['email']), 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); //for email encoding ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-sm-3 col-form-label"><span>Mobile No.  <span class="cRed">*</span></span></label>
										<div class="custom-file col-sm-8">
											<?php echo $this->Form->control('firm_mobile_no', array('type'=>'text', 'escape'=>false, 'value'=>base64_decode($firm_details['mobile_no']), 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-sm-3 col-form-label"><span>Phone No.  </span></label>
										<div class="custom-file col-sm-8">
											<?php echo $this->Form->control('firm_fax_no', array('type'=>'text', 'escape'=>false, 'value'=>base64_decode($firm_details['fax_no']), 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="field3" class="col-sm-3 col-form-label"><span>Pin Code   <span class="cRed">*</span></span></label>
										<div class="custom-file col-sm-8">
											<?php echo $this->Form->control('postal_code', array('type'=>'text', 'escape'=>false, 'value'=>$firm_details['postal_code'], 'label'=>false, 'disabled'=>'disabled','class'=>'form-control')); ?>
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

		<?php echo $this->Html->script('element/siteinspection_forms/new/ca/firm_details'); ?>
