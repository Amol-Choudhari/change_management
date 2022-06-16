
<?php //echo $this->Form->create(null,array('class'=>'form-inline ml-3 search-bx')); ?>
<!-- below search box view code added on 11-05-2017 by Amol -->
<div class="input-group input-group-sm form-inline ml-3 search-bx">

	<?php echo $this->form->control('applicant_id', array('type'=>'search', 'id'=>'srch_appl_id', 'class'=>'form-control form-control-navbar', 'label'=>false, 'placeholder'=>'Search Application')); ?>
	
	<div class="input-group-append">
	
	<?php //echo $this->form->control('Submit', array('type'=>'submit', 'name'=>'search_applicant', 'class'=>'fas fa-search')); ?>
	
	<a title="Search Application" id="search_appl_btn" href="#"><span class="glyphicon glyphicon-search"></span></a>
</div>

</div>
<div class="clearfix"></div>
<?php //echo $this->Form->end(); ?>

<?php echo $this->element('common_counts_and_list_elements/applSearchInPopupElement'); ?>

<?php echo $this->Html->script('dashboard/dashboard-search-appl-js'); ?>