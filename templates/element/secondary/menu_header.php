<?php if (isset($_SESSION['username'])) { ?>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav main-header-navbar container">
      <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>

      <li class="nav-item dnone d-sm-inline-block">
          <a href="#" class="nav-link">Last Login: <?php echo $this->element('customer_last_login'); ?> [IP: <?php echo $_SESSION["ip_address"];?>]</a>
      </li>

      <ul class="navbar-nav ml-auto">
          <li class="nav-item" title="Logout">
          <?php  $explodeValue = explode('/',$_SESSION['username']);
				if ($explodeValue[0] == 'CHM') { ?>
                    <?php echo $this->Html->link('<i class="fas fa-power-off text-lg"></i>', array('controller'=>'chemist', 'action'=>'logout'), array('class'=>'nav-link', 'role'=>'button', 'escape'=>false)); ?>
               <?php } else { ?>
                    <?php echo $this->Html->link('<i class="fas fa-power-off text-lg"></i>', array('controller'=>'customers', 'action'=>'logout'), array('class'=>'nav-link', 'role'=>'button', 'escape'=>false)); ?>
                <?php } ?>
          </li>
      </ul>
  </nav>
<?php } ?>
