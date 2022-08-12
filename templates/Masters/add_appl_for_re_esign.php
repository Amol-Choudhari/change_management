<?php ?>
<div class="content-wrapper">
	<div class="content-header">
   		<div class="container-fluid">
    		<div class="row mb-2">
      			<div class="col-sm-6"><?php echo $this->Html->link('Back', array('controller' => 'masters', 'action'=>'masters_home'),array('class'=>'add_btn btn btn-secondary float-left')); ?></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
						<li class="breadcrumb-item"><?php echo $this->Html->link('Masters Home', array('controller' => 'masters', 'action'=>'masters-home'));?></li>
						<li class="breadcrumb-item">Application for Re-Esign</li>
					</ol>
				</div>
			</div>
		</div>
	</div>

	<section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<?php echo $this->Form->create(null,array('id'=>'add_reesign_form','url'=>'/Masters/add_appl_for_re_esign','method'=>'POST','class'=>'form-group')); ?>
						<div class="card card-info">
							<div class="card-header"><h2 class="card-title-new">To Re-esign the Renewal Certificate to correct validity date</h2></div>
							<div class="add_master form-horizontal">
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-md-6">Enter Application Id</label>
												<?php echo $this->Form->control('customer_id', array('type'=>'text', 'id'=>'customer_id', 'class'=>'form-control', 'label'=>false, 'placeholder'=>'Enter Application Id', 'required'=>true)); ?>
												<div id="error_machine_type"></div>
											</div>
										</div>
										<div class="col-md-2">
											<?php echo $this->Form->submit('Add', array('name'=>'add_appl', 'id'=>'add_appl', 'class'=>'btn btn-success', 'label'=>false)); ?>
										</div>
									</div>
								</div>
							</div>

							<div class="card-header bg-gray"><h3 class="card-title-new">List of Added Applications</h3></div>
							<div class="table-responsive">
								<table id="added_appl_list" class="table table-striped table-bordered color1 table-hover">
									<thead class="tablehead">
										<tr>
											<th>S.No.</th>
											<th>Application Id</th>
											<th>Re-Esign Status</th>
											<th>Added On</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$sr_no = 1;

											foreach($added_appl as $each){ ?>
											<tr>
												<td><?php echo $sr_no; ?></td>
												<td><?php echo $each['customer_id'];?></td>
												<td><?php echo $each['re_esign_status'];?></td>
												<td><?php echo $each['created'];?></td>
												<td><?php 
														if($each['re_esign_status']=='Pending'){
															if($each['action_status']=='active'){
																echo $this->Html->link('Deactivate', array('controller' => 'masters', 'action'=>'deactvt_appl_re_esign_id', $each['id']));
															} else { ?>
															Deactivated
													<?php } } ?>
												</td>
											</tr>
										<?php $sr_no++; } ?>
									</tbody>
								</table>
							</div>
						</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</section>
</div>
		<?php echo $this->Html->script('Masters/add_appl_for_re_esign'); ?>
