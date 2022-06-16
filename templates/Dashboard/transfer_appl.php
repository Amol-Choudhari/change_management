<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge info2 float-left">Transfer Application</label></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
							<li class="breadcrumb-item active">Transfer Applications</li>
						</ol>
				</div>
			</div>
		</div>
	</div>

		   <?php if (!empty($returnFalseMessage)) {
    			echo "<div class='alert alert-danger'>$returnFalseMessage</div>";
    		} ?>
		<?php echo $this->Form->create(null,array('id'=>'transfer_appl_form','class'=>'form-group')); ?>
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card card-Lightblue">
							<div class="card-header"><h3 class="card-title-new">Transfer Application</h3></div>
								<div class="form-horizontal">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-3">
	                  							<div class="form-group">
													<label>Application type <span class="cRed">*</span></label>
													<?php echo $this->Form->control('appl_type', array('type'=>'select', 'id'=>'appl_type', 'label'=>false, 'options'=>$applTypesList, 'empty'=>'--Select--','class'=>'form-control')); ?>
													<span id="error_appl_type" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label>From Office <span class="cRed">*</span></label>
													<?php echo $this->Form->control('from_office', array('type'=>'select', 'id'=>'from_office', 'label'=>false, 'options'=>$office_list, 'empty'=>'--Select--','class'=>'form-control')); ?>
													<span id="error_from_office" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label>Application Id <span class="cRed">*</span></label>
													<?php echo $this->Form->control('appl_id', array('type'=>'select', 'id'=>'appl_id', 'label'=>false, 'empty'=>'--Select--','class'=>'form-control')); ?>
													<span id="error_appl_id" class="error invalid-feedback"></span>
												</div>
											</div>
											<div class="col-sm-3">
	                  							<div class="form-group">
													<label>To Office <span class="cRed">*</span></label>
													<?php echo $this->Form->control('to_office', array('type'=>'select', 'id'=>'to_office', 'label'=>false, 'options'=>$office_list, 'empty'=>'--Select--','class'=>'form-control')); ?>
													<span id="error_to_office" class="error invalid-feedback"></span>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer cardFooterBackground">
									<div class="col-md-6">
	                  					<div class="form-group row">
											<label>Remark/Reason <span class="cRed">*</span></label>
												<?php echo $this->Form->textarea('remark', array('id'=>'remark','label'=>false,'class'=>'form-control')); ?>
												<span id="error_remark" class="error invalid-feedback"></span>
											</div>
										</div>
										<div class="col-md-5" id="appl_status">
											<!-- Values here will be loaded through ajax -->
										</div>
										<div class="col-md-3 d-flex">
											<?php echo $this->Form->submit('Transfer', array('name'=>'transfer', 'id'=>'transfer_btn', 'label'=>false,'class'=>'mtminus2 btn btn-success')); ?>
										<div class="col-md-3">
											<?php echo $this->Html->link('Back', array('controller' => 'dashboard', 'action'=>'home'),array('class'=>'add_btn btn btn-secondary')); ?>
										</div>
									</div>
								</div>
							<?php echo $this->Form->end(); ?>
						</div>
					</div>
				</div>
			</div>
		</section>
</div>

<?php echo $this->Html->script('dashboard/transfer-appl-module-js');  ?>
