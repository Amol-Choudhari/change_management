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
$menu_action_log = '';

if ($current_menu == 'menu_profile') {
  $menu_profile = 'active';
} elseif ($current_menu == 'menu_firm') {	
  $menu_firm = 'active';
} elseif ($current_menu == 'menu_password') {
  $menu_password = 'active';
} elseif ($current_menu == 'menu_log') {
  $menu_log = 'active';
} elseif ($current_menu == 'menu_action_log') { 
  $menu_action_log = 'active';
} else {
  $menu_dashboard = 'active';
}
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<?php echo $this->element('common_side_menus/common_top_left_logo'); ?>
		<div class="sidebar">
			<?php echo $this->element('common_side_menus/common_top_left_profile'); ?>
			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
					<li class="nav-item">
						<?php 
							echo $this->Html->link('<i class="nav-icon fas fa-tachometer-alt"></i><p class="nav-icon-p">Dashboard</p>', array('controller'=>'customers', 'action'=>'primary_home'), array('escape'=>false, 'class'=>'nav-link '.$menu_dashboard)); 
						?>
					</li>
					<li class="nav-item">
						<?php 
							echo $this->Html->link('<i class="nav-icon fas fa-user"></i><p class="nav-icon-p">View Profile</p>', array('controller'=>'customers', 'action'=>'customer_profile'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); 
						?>
					</li>
					<li class="nav-item">
						<?php 
							echo $this->Html->link('<i class="nav-icon fas fa-plus-square"></i><p class="nav-icon-p">Add Firm</p>', array('controller'=>'customerforms', 'action'=>'add_firm'), array('escape'=>false, 'class'=>'nav-link '.$menu_firm)); 
						?>
					</li>
					<li class="nav-item">
						<?php 
							echo $this->Html->link('<i class="nav-icon fas fa-lock"></i><p class="nav-icon-p">Change Password</p>', array('controller'=>'common', 'action'=>'change_password'), array('escape'=>false, 'class'=>'nav-link '.$menu_password)); 
						?>
					</li>
					<li class="nav-item">
						<?php 
							echo $this->Html->link('<i class="nav-icon fas fa-book"></i><p class="nav-icon-p">Log History</p>', array('controller'=>'common', 'action'=>'current_user_logs'), array('escape'=>false, 'class'=>'nav-link '.$menu_log)); 
						?>
					</li>
					<li class="nav-item">
						<?php 
							echo $this->Html->link('<i class="nav-icon fas fa-book"></i><p class="nav-icon-p">Action History</p>', array('controller'=>'common', 'action'=>'user_action_history'), array('escape'=>false, 'class'=>'nav-link '.$menu_action_log)); 
						?>
					</li>
					<li class="nav-item">
						<?php 
							echo $this->Html->link('<i class="nav-icon fas fa-power-off"></i><p class="nav-icon-p">Logout</p>', array('controller'=>'common', 'action'=>'logout'), array('escape'=>false, 'class'=>'nav-link')); 
						?>
					</li>
				</ul>
			</nav>
		</div>
</aside>
