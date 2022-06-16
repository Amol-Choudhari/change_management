<?php 

// SET ACTIVE MENU (HIGHLIGHT CURRENTLY SELECTED MENU) IN LEFT SIDEBAR
// By Aniket Ganvir dated 8th DEC 2020

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
    <a href="index3.html" class="brand-link">
      <?php echo $this->Html->image('AdminLTELogo.png', array('alt'=>'AQCMS Logo', 'class'=>'brand-image img-circle elevation-3 op8')); ?>
      <span class="brand-text font-weight-light">AQCMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php echo $this->Html->image('profile.jpg', array("alt"=>"User Image", "height"=>"255", "class"=>"img-circle elevation-2")); ?>
      	</div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION["f_name"];?> <?php echo $_SESSION["l_name"];?></a>
          <span class="right badge badge-light"><?php echo $_SESSION["username"];?></span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
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
          		echo $this->Html->link('<i class="nav-icon fas fa-plus"></i><p class="nav-icon-p">Add Firm</p>', array('controller'=>'customerforms', 'action'=>'add_firm'), array('escape'=>false, 'class'=>'nav-link '.$menu_firm)); 
      		?>
          </li>
          <li class="nav-item">
          	<?php 
          		echo $this->Html->link('<i class="nav-icon fas fa-lock"></i><p class="nav-icon-p">Change Password</p>', array('controller'=>'customers', 'action'=>'change_password'), array('escape'=>false, 'class'=>'nav-link '.$menu_password)); 
      		?>
          </li>
          <li class="nav-item">
          	<?php 
          		echo $this->Html->link('<i class="nav-icon fas fa-book"></i><p class="nav-icon-p">Log History</p>', array('controller'=>'customers', 'action'=>'customer_logs'), array('escape'=>false, 'class'=>'nav-link '.$menu_log)); 
      		?>
          </li>
          <li class="nav-item">
          	<?php 
          		echo $this->Html->link('<i class="nav-icon fas fa-power-off"></i><p class="nav-icon-p">Logout</p>', array('controller'=>'customers', 'action'=>'logout'), array('escape'=>false, 'class'=>'nav-link')); 
      		?>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>