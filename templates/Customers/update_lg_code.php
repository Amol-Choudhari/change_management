<div class="content-wrapper">
<section class="content form-middle">
<div class="container-fluid">
<div class="row form-group">


<div class="card-header sub_card_header_prof"><h3 class="card-title"><i class="fa fa-id-card"></i> Photo Id</h3></div>
												<div class="form-horizontal">
													<div class="card-body">
														<div class="row">
														<div class="offset-4 col-md-6">
<?php echo $this->Form->create(null, array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'add_firm_form')); ?>
<div class="custom-file">
<input type="file" class="custom-file-input" id="uploadexcel" name="uploadexcel" multiple='multiple'>
<label class="custom-file-label" for="customFile">Choose file</label>
</div>
<?php 
echo $this->form->submit('upload', array('name'=>'upload')); 
echo $this->Form->end(); 
?>
</div>
</div>
</div>
</div>

</div>
</div>
</section>
</div>