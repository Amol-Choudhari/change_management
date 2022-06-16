<?php

	// SET ACTIVE MENU (HIGHLIGHT CURRENTLY SELECTED MENU) IN LEFT SIDEBAR
	// By Aniket Ganvir dated 8th DEC 2020

	if(!isset($current_menu) || $current_menu=='')
	{
	  $current_menu = 'dashboard';
	}

	$menu_dashboard = '';
	$menu_profile = '';
	$menu_firm = '';
	$menu_password = '';
	$menu_log = '';

	if ($current_menu == 'menu_profile')
	  $menu_profile = 'active';
	else if ($current_menu == 'menu_firm')
	  $menu_firm = 'active';
	else if ($current_menu == 'menu_password')
	  $menu_password = 'active';
	else if ($current_menu == 'menu_log')
	  $menu_log = 'active';
	else
	  $menu_dashboard = 'active';

?>

	<aside class="main-sidebar sidebar-dark-primary elevation-4">
		<?php echo $this->element('common_side_menus/common_top_left_logo'); ?>
			<div class="sidebar">
			  <?php echo $this->element('common_side_menus/common_top_left_profile'); ?>
			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
					<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->

					<li class="nav-item">
						<?php echo $this->Html->link('<i class="nav-icon fas fa-tachometer-alt"></i><p class="nav-icon-p">Dashboard</p>', array('controller'=>'chemist', 'action'=>'home'), array('escape'=>false, 'class'=>'nav-link '.$menu_dashboard)); ?>
					</li>
					<?php if (empty($final_submit_status)) { ?>
						  <li class="nav-item">
							<?php echo $this->Html->link('<i class="nav-icon fas fa-user"></i><p class="nav-icon-p">Register Application</p>', array('controller'=>'application', 'action'=>'application-type',4), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
						  </li>
					<?php } else { ?>
						<li class="nav-item">
							<?php echo $this->Html->link('<i class="nav-icon fas fa-user"></i><p class="nav-icon-p">Registration Status</p>', array('controller'=>'application', 'action'=>'application-type',4), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
						</li>
					<?php } ?>

					<li class="nav-item">
						<?php echo $this->Html->link('<i class="nav-icon fas fa-key"></i><p class="nav-icon-p">Change Password</p>', array('controller'=>'common', 'action'=>'change_password'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
					</li>

					<li class="nav-item">
						<?php echo $this->Html->link('<i class=" nav-icon far fa-dot-circle"></i></i><p class="nav-icon-p">Log History</p>', array('controller'=>'common', 'action'=>'currentUserLogs'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
					</li>

					<li class="nav-item">
						<?php echo $this->Html->link('<i class="nav-icon far fa-dot-circle"></i></i><p class="nav-icon-p">Action Logs</p>', array('controller'=>'common', 'action'=>'user_action_history'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
					</li>

					<?php if (!empty($final_submit_status) && $final_submit_status == 'approved') { ?>
						<li class="nav-item">
							<?php echo $this->Html->link('<i class="nav-icon fas fa-user"></i><p class="nav-icon-p">Confirm Replica Serial</p>', array('controller'=>'replica', 'action'=>'replica_appl_list'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
						</li>
						<li class="nav-item">
							<?php echo $this->Html->link('<i class="nav-icon fas fa-user"></i><p class="nav-icon-p">Confirm 15 Digit Code Replica</p>', array('controller'=>'code15digit', 'action'=>'replica_appl_list'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
						</li>
						<li class="nav-item">
							<?php echo $this->Html->link('<i class="nav-icon fas fa-user"></i><p class="nav-icon-p">Confirm E-Code Replica</p>', array('controller'=>'ecode', 'action'=>'replica_appl_list'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
						</li>

						<li class="nav-item">
							<?php echo $this->Html->link('<i class="nav-icon fas fa-user"></i><p class="nav-icon-p">Replica Alloted List</p>', array('controller'=>'chemist', 'action'=>'replica_alloted_list'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
						</li>
						<li class="nav-item">
							<?php echo $this->Html->link('<i class="nav-icon fas fa-user"></i><p class="nav-icon-p">15 Digit Alloted List</p>', array('controller'=>'chemist', 'action'=>'alloted15_digit_list'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
						</li>
						<li class="nav-item">
							<?php echo $this->Html->link('<i class="nav-icon fas fa-user"></i><p class="nav-icon-p">E-Code Alloted List</p>', array('controller'=>'chemist', 'action'=>'alloted_e_code_list'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
						</li>
					<?php } ?>

					<li class="nav-item">
						<?php echo $this->Html->link('<i class="nav-icon fas fa-sign-out-alt"></i><p class="nav-icon-p">Logout</p>', array('controller'=>'common', 'action'=>'logout'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
					</li>
				</ul>
			</nav>
		</div>
	</aside>
