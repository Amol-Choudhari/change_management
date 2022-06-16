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
								<li class="breadcrumb-item">Extend Dates</li>
							</ol>
						</div>
					</div>
				</div>
			</div>
			<section class="content form-middle">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-10">
							<?php echo $this->Form->create(null,array('class'=>'form-group')); ?>
								<div class="card card-info">
									<div class="card-header"><h3 class="card-title-new">Extend Renewal Due Date</h3></div>
										<div class="add_master form-horizontal">
											<div class="card-body">
												<div class="row">
													<div class="col-md-6">
														<?php echo $this->Form->control('cert_type', array('type'=>'select', 'id'=>'cert_type', 'options'=>$cert_type_list, 'empty'=>'---Select---','label'=>'Certificate Type','required'=>true, 'class'=>'form-control')); ?>
													</div>
													<div class="col-md-5">
														<?php echo $this->Form->control('ren_ext_dt', array('type'=>'text', 'id'=>'ren_ext_dt','label'=>'Ext. Date (Day/Month)','required'=>true,'readonly'=>true, 'class'=>'form-control')); ?>
													</div>
													<div class="col-md-6 mt-2">
														<?php echo $this->Form->control('remark', array('type'=>'textarea', 'id'=>'remark','label'=>'Remark','required'=>true, 'class'=>'form-control')); ?>
													</div>
												</div>
											</div>
										</div>
										<div class="card-footer cardFooterBackground mt-2">
											<?php echo $this->Form->submit('Update', array('name'=>'update', 'label'=>false,'class'=>'btn btn-success')); ?>
                                        </div>
                                    </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </section>
    </div>

	<?php echo $this->Html->script('Masters/extend_dates'); ?>
