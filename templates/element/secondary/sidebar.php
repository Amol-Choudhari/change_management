<?php

// SET ACTIVE MENU (HIGHLIGHT CURRENTLY SELECTED MENU) IN LEFT SIDEBAR
// By Aniket Ganvir dated 8th DEC 2020

if(!isset($current_menu) || $current_menu=='')
{
  $current_menu = 'dashboard';
}

$menu_dashboard = '';
$menu_apply = '';
$menu_apply_open = '';
$menu_register = '';
$menu_renewal = '';
$menu_susp = '';
$menu_mod = '';
$menu_password = '';
$menu_log = '';

if ($current_menu == 'menu_register'){
  $menu_register = 'active';
  $menu_apply = 'active';
  $menu_apply_open = 'menu-open';
}
else if ($current_menu == 'menu_renewal'){
  $menu_renewal = 'active';
  $menu_apply = 'active';
  $menu_apply_open = 'menu-open';
}
else if ($current_menu == 'menu_susp'){
  $menu_susp = 'active';
  $menu_apply = 'active';
  $menu_apply_open = 'menu-open';
}
else if ($current_menu == 'menu_mod'){
  $menu_mod = 'active';
  $menu_apply = 'active';
  $menu_apply_open = 'menu-open';
}
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
          <a href="#" class="d-block"><?php echo $_SESSION["firm_name"];?></a>
          <span class="right badge badge-light"><?php echo $_SESSION["username"];?></span>
        </div>
      </div>



      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?php echo $this->request->getAttribute("webroot");?>customers/secondary-home" class="nav-link <?php echo $menu_dashboard; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p class="nav-icon-p">Dashboard</p>
            </a>
          </li>

          <li class="nav-item has-treeview <?php echo $menu_apply_open; ?>">
            <a href="#" class="nav-link <?php echo $menu_apply; ?>">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Apply For
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <?php if($show_button == 'apply' || $show_button == 'application_status'){ ?>
            <ul class="nav nav-treeview dnone">
              <li class="nav-item">
                <a href="<?php echo $this->request->getAttribute("webroot");?>application/application-type/1" class="nav-link <?php echo $menu_register; ?>">
                  <i class="far fa-plus-square nav-icon"></i>
                  <p>
                    <?php
                    if($show_button == 'apply'){
                      echo "New Registration";
                    }
                    else {
                      echo "Application Status";
                    }
                    ?>
                  </p>
                </a>
              </li>
            <?php } ?>
            <?php if($show_renewal_button == 'renewal' || $show_button == 'renewal_status'){ ?>
              <li class="nav-item">
                <a href="<?php echo $this->request->getAttribute("webroot");?>application/application-type/2" class="nav-link <?php echo $menu_renewal; ?>">
                  <i class="far fa-calendar-alt nav-icon"></i>
                  <p>
                    <?php
                    if($show_button == 'apply'){
                      echo "Renewal";
                    }
                    else {
                      echo "Renewal Status";
                    }
                    ?>
                  </p>
                </a>
              </li>
            <?php } ?>
              <li class="nav-item">
                <a href="#" class="nav-link <?php echo $menu_susp; ?>">
                  <i class="far fa-clock nav-icon"></i>
                  <p>Suspension</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link <?php echo $menu_mod; ?>">
                  <i class="fa fa-wrench nav-icon"></i>
                  <p>Modification</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="<?php echo $this->request->getAttribute("webroot");?>customers/change_password" class="nav-link <?php echo $menu_password; ?>">
              <i class="nav-icon fas fa-lock"></i>
              <p class="nav-icon-p">Change Password</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $this->request->getAttribute("webroot");?>customers/customer_logs" class="nav-link <?php echo $menu_log; ?>">
              <i class="nav-icon fas fa-book"></i>
              <p class="nav-icon-p">Log History</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $this->request->getAttribute("webroot");?>customers/logout" class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>
              <p class="nav-icon-p">Logout</p>
            </a>
          </li>

        </ul>
      </nav>
    </div>
  </aside>
