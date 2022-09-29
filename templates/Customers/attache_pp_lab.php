
<?php echo $this->Html->css('Replica/attach_pp_lab'); ?>
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'firm_form')); ?>

<section class="content form-middle form_outer_class" id="form_outer_main">
	<a href="../customers/secondary_home" class="btn btn-primary">Back</a>
	<div class="container-fluid form-group wd1080">
		<h5 class="mt-1 mb-2">Application For Attach Printing Press / Laboratory</h5>
		<div class="row">
			<div class="col-md-12">
				<div id="firm_details_block" class="card card-success">
					<div class="card-header"><h3 class="card-title">Attach Printing Press / Laboratory</h3></div>
					<div class="form-horizontal">
						<p class="note"><strong>Note:</strong></p>
							<ol>
								<li>This module is useful to attach Packer with Printing Press or Laboratory</li>
								<li>Once attachment added can not be change without office approval</li>
								<li>Only one laboratory can be attached with one packer, Printing Press can be multiple</li>
								<li>This is mandatory to attach Printing Press and Laboratory to apply for Replica allotment.</li>
							</ol>
							<hr>

						<div class="card-body">
							<div class="row">
								<div class="col-md-4"></div>
								<div class="col-md-4">
								<div class="form-group row ">
									<?php
										$options=array('pp'=>'Printing Press','lab'=>'Laborotory');
										$attributes=array('legend'=>false, 'value'=>'', 'id'=>'pp');
										echo $this->form->radio('maptype',$options,$attributes); ?>
								</div>
								<div class="pp box">
									<?php echo $this->Form->control('pp_id', array('type'=>'select', 'id'=>'pp','options'=>$printers_list, 'value'=>$selected_PP,'empty'=>'--Select Authorised Printers--', 'class'=>'form-control', 'label'=>'Authorised Printers', 'required'=>true)); ?>
								</div>
								<div class="lab box">
									<?php echo $this->Form->control('lab_id', array('type'=>'select', 'id'=>'lab','options'=>$lab_list, 'value'=>$selected_lab,'empty'=>'--Select Authorised Laboratory--', 'class'=>'form-control', 'label'=>'Authorised Laboratory', 'required'=>true)); ?>
								</div>
							</div>
								<div class="col-md-4"></div>
							</div>

							<div class="row">
								<div class="column">
									<table class="table table-bordered">
										
									<?php  if(!empty($resultArr)) { ?>
									<tr>
										<th>Attached Printing Press</th>
									</tr>
									<?php } ?>
									<?php 
										foreach($result as $each){
										?>
										<?php if($each['type']=='pp') { ?>
										<tr>
											<td><?php echo $each['p_name']; ?></td>
										</tr>
										<?php } } ?>
									</table>
								</div>
								<div class="column">
									<table class="table table-bordered">
									<?php 
										foreach($result as $each){
										?>
									<?php if($each['type']=='lab') { ?>
									<tr>
										<th>Attached Laboratory</th>
									</tr>
									<?php } } ?>
									<?php 
										foreach($result as $each){
										?>
										<?php if($each['type']=='lab') { ?>
										<tr>
											<td><?php echo $each['l_name']; ?></td>
										</tr>
										<?php } } ?>
									</table>
								</div>
							</div>
							
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>

		<?php //echo $this->element('replica/printer_details'); ?>

        <div class="col-md-2">
			<?php if(empty($dataArray[0]['customer_id'])){ $btn_name = 'Save & Apply'; }else{ $btn_name = 'Attach'; } ?>
			<?php echo $this->Form->control($btn_name, array('type'=>'submit', 'id'=>'save', 'name'=>'save', 'class'=>'btn btn-success', 'label'=>false,)); ?>
		</div>
		<div class="clear"></div>
	</section>
	
	<?php echo $this->Form->end(); ?>


<?php echo $this->Html->script('replica/attach_pp_lab'); ?>
