<?php ?>

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6"><label class="badge badge-primary">File Uploads</label></div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></li>
							<li class="breadcrumb-item active">File Uploads</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
						<div class="card card-cyan">
							<div class="card-header"><h3 class="card-title-new">Add New Files/Photos</h3></div>
							<div class="card-body">
								<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'class'=>'form-group')); ?>
									<div class="masters_list add_master">
										<div class="col-md-8 offset-3 row">
											<div class="col-md-7">
												<?php echo $this->form->control('file',array('type'=>'file', 'multiple'=>'multiple', 'id'=>'file_uploads', 'label'=>false,'class'=>'form-control')); ?>
												<p class="lab_form_note"><i class="fa fa-info-circle"></i> File type: PDF, jpg &amp; max size upto 2 MB</p>
												<span class="error invalid-feedback" id="error_size_file_uploads"></span>
												<span class="error invalid-feedback" id="error_file_uploads"></span>
												<span class="error invalid-feedback" id="error_type_file_uploads"></span>
											</div>
											<div class="col-md-2">
												<?php echo $this->Form->submit('Upload', array('name'=>'upload', 'id'=>'upload_btn', 'label'=>false,'class'=>'btn btn-success float-right')); ?>
											</div>
										</div>
									</div>
								<div class="pd10"></div>
								<div class="card card-primary">
									<div class="card-header bg-dark"><h3 class="card-title-new">Uploaded files</h3></div>
									<table id="uploaded_files" class="table m-0 table-bordered table-hover">
										<thead class="tablehead">
											<tr>
												<th>ID</th>
												<th>File Name</th>
												<th>Uploaded by</th>
												<th class="wd60">Action</th>
											</tr>
										</thead>

										<tbody>
											<?php
											if (!empty($all_files)) {
												$sr = 1;
												foreach ($all_files as $single_file) { ?>
												<tr>
													<td><?php echo $sr;?></td>
													<td><?php echo $single_file['file_name'];?></td>
													<td><?php echo base64_decode($single_file['user_email_id']); //for email encoding?></td>
													<td>
													<?php echo $this->Html->link('', array('controller' => 'cms', 'action'=>'fetch_file_id', $single_file['id']),array('target'=>'blank','class'=>'fas fa-eye','title'=>'View')); ?> |
													<?php echo $this->Html->link('', array('controller' => 'cms', 'action'=>'delete_uploaded_file', $single_file['id']),array('class'=>'fas fa-trash-alt delete_file','title'=>'Delete')); ?></td>
												</tr>
											<?php $sr = $sr+1;	} } ?>
										</tbody>		
									</table>
								</div>
							<?php echo $this->Form->end(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	<?php echo $this->Html->script('cms/file_uploads');?>
