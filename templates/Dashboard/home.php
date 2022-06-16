<?php ?>

<?php echo $this->Form->create(null,array('class'=>'pr-10')); ?>
<?php if(!empty($check_user_role)){ 

	echo $this->element('common_counts_and_list_elements/dashboard_home_tabs');
	echo $this->element('common_counts_and_list_elements/common_dashboard_home_view');

}else{ echo "Sorry... You don't have any role assigned by admin."; } ?>

<div class="clear"></div>
<?php echo $this->Form->end(); ?>

