
<div class="container">	
	<section class="content-header pt-0">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-6"></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item active">Change Request</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'changeForm')); ?>
	<section class="content mr-4 ml-4">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div class="card-header"><h3 class="card-title">Change Request</h3></div>
							<div class="form-horizontal">
								<div class="card-body">
									<div class="row">							
							   			<label for="inputEmail3" class="col-md-2 col-form-label">Change Request For : <span class="cRed">*</span></label>
											<div class="select2-purple col-md-8">
												<?php echo $this->Form->control('changefield', array('type'=>'select', 'data-placeholder'=>'Select a Change Request','options'=>$changeFieldsList, 'value'=>$selectedValues,'escape'=>false, 'class'=>'select2', 'multiple'=>'multiple', 'data-dropdown-css-class'=>'select2-purple', 'required'=>true,'label'=>false)); ?>
												<!--<select class="select2" multiple="multiple" name="changefield[]" data-placeholder="Select a Change Request" data-dropdown-css-class="select2-purple"  style="width: 100%;">
												<?php foreach ($changeFieldsList as $key => $list) { ?>
													<option value="<?php echo $key; ?>"><?php echo $list; ?></option>									  
												<?php } ?>
												</select>-->
											</div>
							  			<?php if ($selectedValues != null) {  ?>
							  				<label class="text-danger pt-3 pl-5">Note : If you changed selected value, Then delete all old saved data and need to fill data once again</label>
							  			<?php } ?>	
									</div>
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>    		
		</section>
		<div class="row">
			<div class="col-md-12">
				<div class="card shadow-none">
					<div class="form-horizontal">
						<div class="mr-4">
							<?php echo $this->form->submit('GO', array('name'=>'submit', 'id'=>'submit', 'class'=>'btn btn-info float-right ml-2', 'label'=>false)); ?> 	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
  
 <?php echo $this->Html->script('application/change/change'); ?>
 
  <?php if($final_submit_details != null){ ?>
	<?php echo $this->Html->script('application/change/final_submit_details_null'); ?>
  <?php } ?>