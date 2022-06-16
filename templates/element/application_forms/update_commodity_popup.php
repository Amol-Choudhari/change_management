<?php ?>

	<?php if ($firm_type==1 || $firm_type==3) { ?>
		<button id="comm_open_popup_btn" class="btn btn-primary float-right">Update Commodity</button>
	<?php } elseif ($firm_type==2) { ?>
		<button id="comm_open_popup_btn" class="btn btn-primary float-right">Update Packing Type</button>
	<?php } ?>

	<div id="update_commodity_Modal" class="modal">
		<div class="modal-dialog modal-dialog-centered" class="mwd56">
			<div class="modal-content">
				<?php if ($firm_type==1 || $firm_type==3) { ?>
					<div class="modal-header"<h4 class="modal-title">Change/Update Commodity</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Category <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('category', array('type'=>'select', 'id'=>'category', 'empty'=>'Select Category', 'options'=>$category_list,'label'=>false, 'class'=>'form-control')); ?>
											</div>
										</div>
										
										<div id="selected_bevo_nonbevo_msg"></div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Commodities <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('commodity', array('type'=>'select', 'empty'=>'Select Commodity', 'id'=>'commodity','options'=>array(), 'label'=>false, 'class'=>'form-control')); ?>
											</div>
										</div>
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Remark <span class="cRed">*</span></label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('updt_comm_remark', array('type'=>'textarea', 'id'=>'updt_comm_remark','label'=>false, 'class'=>'form-control')); ?>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group row">
											<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Commodities </label>
											<div class="col-sm-9">
												<?php echo $this->Form->control('selected_commodity', array('type'=>'select', 'id'=>'selected_commodity','options'=>$selected_commodities, 'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control mH260')); ?>
											</div>
										</div>
										<p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
									</div>
								</div>
								<button id="comm_update_btn" class="btn btn-success float-left">Update</button>
							</div>
						</div>
					</div>

			<?php } elseif ($firm_type==2) { ?>

				<div class="modal-header"><h4 class="modal-title">Change/Update Packing Type</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
				<div class="modal-body">
					<div class="form-horizontal">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group row">
									<label for="inputEmail3" class="col-sm-3 col-form-label">Packing Types <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('packing_types', array('type'=>'select', 'id'=>'packing_types', 'empty'=>'Select', 'options'=>$packing_types, 'label'=>false, 'class'=>'form-control')); ?>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Remark <span class="cRed">*</span></label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('updt_packtype_remark', array('type'=>'textarea', 'id'=>'updt_packtype_remark','label'=>false, 'class'=>'form-control')); ?>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Packing Types </label>
										<div class="col-sm-9">
											<?php echo $this->Form->control('selected_packing_types', array('type'=>'select', 'id'=>'selected_packing_types', 'options'=>$selected_packing_types,'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control')); ?>
										</div>
									</div>
									<p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
								</div>
							</div>
							<button id="packtype_update_btn" class="btn btn-success float-left">Update</button>
						</div>
					</div>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>


<?php echo $this->Html->script('forms/update_commodity_option'); ?>
