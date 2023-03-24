
	<div class="col-md-12"><div class="card card-success"><div class="card-header"><h3 class="card-title">
		<?php if ($firm_type==1 || $firm_type==3) { ?> Category/Commodities Details <?php } ?>
		<?php if ($firm_type==2) { ?> Packing Types Details <?php } ?>
	</div></div></div>

	<?php if ($firm_type==1 || $firm_type==3) { ?>

		<!-- fields for new change value-->
		<div class="col-md-6">
			<p><b>New Details</b></p>
			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Category <span class="cRed">*</span></label>
				<div class="col-sm-9">
					<?php echo $this->form->control('comm_category', array('type'=>'select', 'id'=>'category', 'empty'=>'Select Category', 'options'=>$section_form_details[0]['comm_category_list'], 'value'=>$section_form_details[0]['comm_category'], 'label'=>false, 'class'=>'form-control')); ?>
					<span id="error_comm_category" class="error invalid-feedback"></span>
				</div>
			</div>
			
			<div id="selected_bevo_nonbevo_msg"></div>
			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Commodities <span class="cRed">*</span></label>
				<div class="col-sm-9">
					<?php echo $this->form->control('commodity', array('type'=>'select', 'empty'=>'Select Commodity', 'id'=>'commodity','options'=>$section_form_details[0]['commodity_list'], 'label'=>false, 'class'=>'form-control')); ?>
					<span id="error_commodity" class="error invalid-feedback"></span>
				</div>
			</div>

			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Commodities </label>
				<div class="col-sm-9">
					<?php echo $this->form->control('selected_commodity', array('type'=>'select', 'id'=>'selected_commodity', 'options'=>$section_form_details[0]['commodity'], 'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control mH260')); ?>
					<span id="error_selected_commodity" class="error invalid-feedback"></span>
				</div>
			</div>
			<p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
		</div>
		
		<!-- fields for Last value-->
		<div class="col-md-6 last_details_change">
			<p><b>Last Details</b></p>
			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Category <span class="cRed">*</span></label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('category_last', array('type'=>'select', 'options'=>$category_list,'label'=>false, 'class'=>'form-control')); ?>
				</div>
			</div>

			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Commodities </label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('selected_commodity', array('type'=>'select','options'=>$selected_commodities, 'multiple'=>true, 'label'=>false, 'class'=>'form-control mH260')); ?>
				</div>
			</div>

		</div>


	<?php } elseif ($firm_type==2) { ?>

		<!-- fields for new change value-->
		<div class="col-md-6">
			<p><b>New Details</b></p>
			<div class="form-group row">
			<label for="inputEmail3" class="col-sm-3 col-form-label">Packing Types <span class="cRed">*</span></label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('packing_types', array('type'=>'select', 'id'=>'packing_types', 'empty'=>'Select', 'options'=>$packing_types, 'label'=>false, 'class'=>'form-control')); ?>
					<span id="error_packing_types" class="error invalid-feedback"></span>
				</div>
			</div>

			<div class="form-group row">
				<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Packing Types </label>
				<div class="col-sm-9">
					<?php echo $this->Form->control('selected_packing_types', array('type'=>'select', 'id'=>'selected_packing_types', 'options'=>$section_form_details[0]['packing_types'],'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control')); ?>
					<span id="error_selected_packing_types" class="error invalid-feedback"></span>
				</div>
			</div>
			<p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
		</div>
		
		<!-- fields for last value-->
		<div class="col-md-6 last_details_change">
			<p><b>Last Details</b></p>
			<div class="form-group row">
				<!--<label for="inputEmail3" class="col-sm-3 col-form-label">Packing Types <span class="cRed">*</span></label>
					<div class="col-sm-9">
						<?php //echo $this->Form->control('packing_types_last', array('type'=>'select', 'empty'=>'Select', 'options'=>$packing_types, 'label'=>false, 'class'=>'form-control')); ?>
					</div>
				</div>-->

				<div class="form-group row">
					<label for="inputEmail3" class="col-sm-3 col-form-label">Selected Packing Types </label>
					<div class="col-sm-9">
						<?php echo $this->Form->control('selected_packing_types_last', array('type'=>'select', 'options'=>$selected_packing_types,'empty'=>'--Selected--', 'multiple'=>true, 'label'=>false, 'class'=>'form-control')); ?>
					</div>
				</div>
				<p class="commodity-note-txt"><i class="fa fa-info-circle"></i> To remove from list click on the item</p>
			</div>
		</div>

	<?php } ?>



<?php echo $this->Html->script('forms/update_commodity_option'); ?>
