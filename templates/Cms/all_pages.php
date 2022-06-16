<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Site Pages</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item active">Site Pages</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 mb-2">
					<?php echo $this->Html->link('Add New', array('controller' => 'cms', 'action'=>'add_page'),array('class'=>'add_btn btn btn-success float-left')); ?>
					<?php echo $this->Html->link('Back', array('controller' => 'dashboard', 'action'=>'home'),array('class'=>'add_btn btn btn-secondary float-right')); ?>
				</div>						
				<div class="col-md-12">
					<div class="card card-cyan">
						<div class="card-header"><h4 class="card-title-new">All Site Pages</h4></div>
							<div class="masters_list mt-2">
								<?php echo $this->Form->create(); ?>
									<table id="pages_list_table" class="table m-0 table-stripped table-bordered table-hover">
										<thead class="tablehead">
											<tr>
												<th>SR.No</th>
												<th>Page Name</th>
												<th>Author</th>
												<th>Status</th>
												<th>Date</th>
												<th>Action</th>
											</tr>
										</thead>	
										
										<tbody>
											<?php
											if(!empty($all_pages)){
												$sr_no = 1;		
												foreach($all_pages as $single_page){ ?>
												<tr>
													<td><?php echo $sr_no;?></td>
													<td><?php echo $single_page['title'];?></td>
													<td><?php echo base64_decode($single_page['user_email_id']); //for email encoding ?></td>
													<td><?php
															$pageStatus = $single_page['status'];
																if($pageStatus == 'publish'){
																	$badge = "success";
																}elseif($pageStatus == 'draft'){
																	$badge = "warning";
																}
																
														    echo "<span class='badge badge-pill badge-".$badge."'>".$pageStatus."</span>";
														?>
													</td>
													<td><?php echo $single_page['publish_date'];?></td>
													<td><?php echo $this->Html->link('', array('controller' => 'cms', 'action'=>'pagePreview', $single_page['id']),array('target'=>'blank','class'=>'fas fa-eye','title'=>'Preview')); ?> | 
														<?php echo $this->Html->link('', array('controller' => 'cms', 'action'=>'fetch_page_id', $single_page['id']),array('class'=>'far fa-edit','title'=>'Edit')); ?> | 
														<?php echo $this->Html->link('', array('controller' => 'cms', 'action'=>'delete_page', $single_page['id']),array('class'=>'fas fa-trash-alt delete_page','title'=>'Delete')); ?>
													</td>
												</tr>
											<?php $sr_no++; } } ?>					
										</tbody>
									</table>
								</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

	<?php echo $this->Html->script('cms/all_pages');?>
