<?php ?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

	<a href="" class="brand-link">
		<?php echo $this->Html->image('AdminLTELogo.png', array('alt'=>'AQCMS Logo', 'class'=>'brand-image img-circle elevation-3 op8')); ?>
		<span class="brand-text font-weight-light">AQCMS</span>
	</a>

	<div class="sidebar">

		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
			<?php echo $this->Html->image('profile.jpg', array("alt"=>"User Image", "height"=>"255", "class"=>"img-circle elevation-2")); ?>
			</div>
			<div class="info">
			<a href="#" class="d-block"><?php echo $_SESSION["f_name"];?> <?php echo $_SESSION["l_name"];?></a>
			<span class="right badge badge-light"><?php echo $_SESSION["username"];?></span>
			</div>
		</div>

		<?php echo $this->element('common_side_menus/dashboard_side_menus'); ?>

		<?php echo $this->element('common_side_menus/front_side_menus'); ?>

		<?php echo $this->element('common_side_menus/corporate_side_menus'); ?>

		<?php echo $this->element('common_side_menus/secondary_side_menus'); ?>

		<?php echo $this->element('common_side_menus/applications_forms_layout_side_menus'); ?>

		<?php echo $this->element('common_side_menus/form_scrunity_layout_side_menus'); ?>

		<?php echo $this->element('common_side_menus/form_siteinspection_layout_side_menus'); ?>

		<?php echo $this->element('common_side_menus/old_app_scrutiny_layout_side_menus'); ?>


	</div>

</aside>
