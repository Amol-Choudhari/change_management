<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">All Menus</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item active">All Menu</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 mb-2">
					<?php echo $this->Html->link('Add New', array('controller' => 'cms', 'action'=>'add_menu'),array('class'=>'add_btn btn btn-success float-left')); ?>
					<?php echo $this->Html->link('Back', array('controller' => 'dashboard', 'action'=>'home'),array('class'=>'add_btn btn btn-secondary float-right')); ?>
				</div>
				<div class="col-md-12">
					<div class="card card-cyan">
						<div class="card-header"><h5 class="card-title-new">Given Below is list of All Menus</h5></div>
							<div class="card-body">
								<?php echo $this->Form->create(null, array('class'=>'form-group','id'=>'all_menu')); ?>
									<table id="menus_list_table" class="table m-0 table-bordered table-hover">
										<thead class="tablehead">
											<tr>
												<th>SR.No</th>
												<th>Menu Name</th>
												<th>Position</th>
												<th>Menu Type</th>
												<th>Action</th>
											</tr>
										</thead>	
										<tbody>
											<?php
											if (!empty($all_menus)) {
												$sr_no=1;
												foreach ($all_menus as $single_menu) { ?>
												<tr>
													<td><?php echo $sr_no;?></td>
													<td><?php echo $single_menu['title'];?></td>
													<td><?php echo $single_menu['position'];?></td>
													<td><?php echo $single_menu['link_type'];?></td>
													<td>
														<?php echo $this->Html->link('', array('controller' => 'cms', 'action'=>'fetch_menu_id', $single_menu['id']),array('class'=>'far fa-edit','title'=>'Edit')); ?> | 
														<?php echo $this->Html->link('', array('controller' => 'cms', 'action'=>'delete_menu', $single_menu['id']),array('class'=>'fas fa-minus-circle delete_menu','title'=>'Delete')); ?>
													</td>		
												</tr>
											<?php $sr_no++; } } ?>
										</tbody>				
									</table>
								<?php echo $this->Form->end(); ?>
							</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>	
<?php echo $this->Html->script('cms/all_menus');?>



