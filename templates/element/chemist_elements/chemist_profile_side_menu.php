<?php
if (!isset($current_menu) || $current_menu=='') {
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


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
    <?php echo $this->element('common_side_menus/common_top_left_logo'); ?>
    <!-- Sidebar -->
    <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
        <?php echo $this->element('common_side_menus/common_top_left_profile'); ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
              <li class="nav-item">
                  <?php echo $this->Html->link('<i class="nav-icon fas fa-tachometer-alt"></i><p class="nav-icon-p">Dashboard</p>', array('controller'=>'chemist', 'action'=>'home'), array('escape'=>false, 'class'=>'nav-link '.$menu_dashboard)); ?>
              </li>

              <li class="nav-item">
                  <?php echo $this->Html->link('<i class="nav-icon fas fa-user-tie"></i></i><p class="nav-icon-p">Profile</p>', array('controller'=>'chemist', 'action'=>'profile'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
              </li>

              <li class="nav-item">
                  <?php echo $this->Html->link('<i class="nav-icon fas fa-user-graduate"></i></i></i><p class="nav-icon-p">Education</p>', array('controller'=>'chemist', 'action'=>'education'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
              </li>

              <li class="nav-item">
                  <?php echo $this->Html->link('<i class="nav-icon fas fa-id-card"></i></i><p class="nav-icon-p">Experience</p>', array('controller'=>'chemist', 'action'=>'experience'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
              </li>

              <li class="nav-item">
                  <?php echo $this->Html->link('<i class="nav-icon fas fa-user"></i><p class="nav-icon-p">Other Details</p>', array('controller'=>'chemist', 'action'=>'other_details'), array('escape'=>false, 'class'=>'nav-link '.$menu_profile)); ?>
              </li>
            </ul>
        </nav>
      </div>
   </aside>
