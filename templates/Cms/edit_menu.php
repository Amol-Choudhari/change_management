<?php ?>

<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Edit Menus</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item"><?php echo $this->Html->link('All Site Menus', array('controller' => 'cms', 'action'=>'all-menus'));?></a></li>
						<li class="breadcrumb-item active">Edit Menu</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
		<section class="content form-middle">
      		<div class="container-fluid">
				<div class="row">
					<div class="col-md-10">
						<?php echo $this->Form->create(null,array('class'=>'form-group','id'=>'edit_menu')); ?>
            				<div class="card card-cyan">
								<div class="card-header"><h3 class="card-title-new">Edit Menu Details</h3></div>
									<div class="form-horizontal">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label>Menu Name <span class="cRed">*</span></label>
														<?php echo $this->Form->control('title', array('type'=>'text', 'label'=>false, 'value'=>$menu_details['title'], 'placeholder'=>'Enter Menu Title','class'=>'form-control')); ?>
														<div id="error_title"></div>
													</div>
													<div class="form-group">
														<label>Menu Type <span class="cRed">*</span></label>
														<?php
															$options=array('page'=>'Page','external'=>'External');
															$attributes=array('legend'=>false, 'value'=>$menu_details['link_type'], 'id'=>'link_type');
															echo $this->form->radio('link_type',$options,$attributes); ?>
															<div id="error_link_type"></div>
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<div id="external_link_field">
															<p class="badge badge-success">If External, Paste Link with http:// or https://</p>
																<?php echo $this->Form->control('external_link', array('type'=>'text', 'id'=>'external_link', 'label'=>false, 'value'=>$menu_details['external_link'], 'placeholder'=>'Eg: https://www.google.com','class'=>'form-control')); ?>
																<div id="error_external_link"></div>
														</div>
														<div id="pages_list_field">
															<label>Select Page</label>
															<?php echo $this->Form->control('link_id', array('type'=>'select', 'options'=>$list_pages, 'value'=>$menu_details['link_id'], 'empty'=>'---Select---', 'id'=>'link_id', 'label'=>false,'class'=>'form-control')); ?>
															<div id="error_link_id"></div>
														</div>
													</div>

													<div class="form-group row">
														<label class="col-md-4">Menu Position : </label>
														<?php
															$options=array('side'=>'Side','bottom'=>'Bottom');
															$attributes=array('legend'=>false, 'value'=>$menu_details['position'], 'id'=>'position');
															echo $this->form->radio('position',$options,$attributes); ?>
															<div id="error_position"></div>
													</div>

													<div class="form-group row">
														<label class="col-md-4">Menu Order : </label>
														<div class="col-sm-6">
															<?php echo $this->Form->control('order_id', array('type'=>'text', 'id'=>'order_id', 'value'=>$menu_details['order_id'], 'label'=>false, 'placeholder'=>'Enter Order No.','class'=>'form-control')); ?>
															<div id="error_order"></div>
														</div>
													</div>
												</div>
												<div class="col-md-10 offset-1">
													<div id="current_menu_heading"></div>
														<div id="side_menu_list">
															<table class="table table-bordered table-info">
																<thead class="tablehead">
																	<tr>
																		<th>Menu Name</th>
																		<th>Order No.</th>
																	</tr>
																</thead>
																<tbody>
																	<?php foreach($side_menu_list as $side_menu){ ?>
																		<tr>
																			<td><?php echo $side_menu['title'];?></td>
																			<td><?php echo $side_menu['order_id'];?></td>
																		</tr>
																	<?php } ?>
																</tbody>
															</table>
														</div>
														<div id="bottom_menu_list">
															<table class="table table-bordered table-info">
																<thead class="tablehead">
																	<tr>
																		<th>Menu Name</th>
																		<th>Order No.</th>
																	</tr>
																</thead>
																<tbody>
																	<?php foreach($bottom_menu_list as $bottom_menu){ ?>
																		<tr>
																			<td><?php echo $bottom_menu['title'];?></td>
																			<td><?php echo $bottom_menu['order_id'];?></td>
																		</tr>
																	<?php } ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									<div class="card-footer cardFooterBackground">
									<?php echo $this->Form->submit('Update', array('name'=>'update', 'id'=>'update_btn','label'=>false,'class'=>'btn btn-success float-left')); ?>
									<?php echo $this->Html->link('Back', array('controller' => 'cms', 'action'=>'all_menus'),array('class'=>'btn btn-secondary float-right')); ?>
								</div>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			  </div>
		</section>
	</div>

	<?php echo $this->Html->script('cms/edit_menu');?>
