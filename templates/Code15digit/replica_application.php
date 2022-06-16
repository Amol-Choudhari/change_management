<?php ?>
<?php echo $this->Html->css('Code15digit/replica_application'); ?>
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'firm_form')); ?>
	<section class="content form-middle form_outer_class" id="form_outer_main">
		<a href="../customers/secondary_home" class="btn btn-primary">Back</a>
		<div class="container-fluid form-group wd1080">
	  		<h5 class="mt-1 mb-2">Application For 15 Digit Code</h5>
			<div class="row">
				<div class="col-md-12">
					<div id="firm_details_block" class="card card-success">
						<div class="card-header"><h3 class="card-title">Firm Details</h3></div>
							<div class="form-horizontal">
								<div class="card-body">

									<div class="row">
										<div class="col-md-3">
											<?php echo $this->Form->control('firm_name', array('type'=>'text', 'id'=>'firm_name','value'=>$firm_details['firm_name'], 'class'=>'form-control', 'readonly'=>true, 'label'=>'Firm Name')); ?>
										</div>

										<div class="col-md-3">
											<?php echo $this->Form->control('customer_id', array('type'=>'text', 'id'=>'customer_id','value'=>$firm_details['customer_id'], 'class'=>'form-control', 'readonly'=>true, 'label'=>'packer ID')); ?>
										</div>

										<div class="col-md-3">
											<?php echo $this->Form->control('ca_unique_no', array('type'=>'text', 'id'=>'ca_unique_no','value'=>$firm_details['ca_unique_no'], 'class'=>'form-control', 'readonly'=>true, 'label'=>'Packer Unique No.')); ?>
										</div>

										<div class="col-md-3">
											<?php echo $this->Form->control('grading_lab', array('type'=>'select', 'id'=>'grading_lab','options'=>$lab_list, 'value'=>$selected_lab,'empty'=>'--Select Grading Laboratory--', 'class'=>'form-control', 'label'=>'Grading laboratory', 'required'=>true)); ?>
										</div>
									</div>
									<div class="clear"></div>
								</div>
							</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div id="replica_add_more_table" class="card card-success wd1080">
						<div class="card-header"><h3 class="card-title">Enter Other Details</h3></div>
						<div class="form-horizontal">
							<div class="card-body">

								<div id="table_outer">
									<div id="add_new_row"></div>
									<div id="table_container_1" ></div>
								</div>

								<div class="col-md-3 float-right">

									<?php echo $this->Form->control('available_bal', array('type'=>'number', 'id'=>'available_bal','value'=>$bal_amt, 'class'=>'form-control', 'readonly'=>true, 'label'=>'Available Balance (Rs.):')); ?>
								</div>

								<div class="col-md-3 float-right">

									<?php echo $this->Form->control('overall_total_chrg', array('type'=>'number', 'id'=>'overall_total_chrg','value'=>'0', 'class'=>'form-control', 'readonly'=>true, 'label'=>'Total Replica Charges (Rs.):', 'required'=>true)); ?>
									<span id="bal_amt_exceeds_msg"></span>
								</div>

								<div class="clear"></div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<?php echo $this->element('replica/printer_details'); ?>

		<div class="col-md-2">
			<?php if(empty($dataArray[0]['customer_id'])){ $btn_name = 'Save & Apply'; }else{ $btn_name = 'Update'; } ?>
			<?php echo $this->Form->control($btn_name, array('type'=>'submit', 'id'=>'save', 'name'=>'save', 'class'=>'btn btn-success', 'label'=>false,)); ?>
		</div>

		<div class="clear"></div>
	</section>
	<input type="hidden" id="tableFormData" value='<?php echo $tableForm; ?>'>
	<?php echo $this->Form->end(); ?>


<?php echo $this->Html->script('Code15digit/replica_serial_appl_table'); ?>
