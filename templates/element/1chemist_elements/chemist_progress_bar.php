<?php ?>

<input type="hidden" id="application_dashboard" value="<?php echo $_SESSION['application_dashboard']; ?>">
<div class="progress_bar_con">

    <a href="<?php echo $this->request->getAttribute('webroot');?>chemist/profile">
	<div id="profile" class="d-inline p-1 pl-3 pr-3 mr-1 bg-red wd14" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
		Profile <span id="profile_span" class="glyphicon glyphicon-remove-sign"></span>
		<?php echo $this->Form->control('profile', array('type'=>'hidden', 'id'=>'profile_status', 'value'=>$profile_status, 'class'=>'input-field', 'label'=>false)); ?>
    </div></a>
	
    <a href="<?php echo $this->request->getAttribute('webroot');?>chemist/education"><div id="education" class="d-inline p-1 pl-3 pr-3 mr-1 bg-red" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" >
		Education <span id="education_span" class="glyphicon glyphicon-remove-sign wd14"></span>
		<?php echo $this->Form->control('education', array('type'=>'hidden', 'id'=>'education_status', 'value'=>$education_status, 'class'=>'input-field', 'label'=>false)); ?>
    </div></a>
	
	<a href="<?php echo $this->request->getAttribute('webroot');?>chemist/experience"><div id="experience" class="d-inline p-1 pl-3 pr-3 mr-1 bg-red" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
		Experience <span id="experience_span" class="glyphicon glyphicon-remove-sign wd14"></span>
		<?php echo $this->Form->control('experience', array('type'=>'hidden', 'id'=>'experience_status', 'value'=>$experience_status, 'class'=>'input-field', 'label'=>false)); ?>
    </div></a>
	
	
	<a href="<?php echo $this->request->getAttribute('webroot');?>chemist/training"><div id="training" class="d-inline p-1 pl-3 pr-3 mr-1 bg-red" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
		Training <span id="training_span" class="glyphicon glyphicon-remove-sign wd14"></span>
		<?php echo $this->Form->control('training', array('type'=>'hidden', 'id'=>'training_status', 'value'=>$training_status, 'class'=>'input-field', 'label'=>false)); ?>
	</div></a>

	<a href="<?php echo $this->request->getAttribute('webroot');?>chemist/other_details"><div id="other_details" class="d-inline p-1 pl-3 pr-3 mr-1 bg-red" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" >
		Other Details <span id="other_details_span" class="glyphicon glyphicon-remove-sign wd14"></span>
		<?php echo $this->Form->control('other_details', array('type'=>'hidden', 'id'=>'other_details_status', 'value'=>$other_details_status, 'class'=>'input-field', 'label'=>false)); ?>
	</div></a>

</div>

<?php echo $this->Html->script('element/chemist_elements/chemist_progress_bar'); ?>	

