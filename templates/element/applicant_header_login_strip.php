<?php if (isset($_SESSION['username'])) { ?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>

            <li class="nav-item dnone d-sm-inline-block">
                <a href="#" class="nav-link">Last Login: <?php echo $this->element('customer_last_login'); ?> [IP: <?php echo $_SESSION["ip_address"];?>]</a>
            </li>
        </ul>

         <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- To show notifications on applicant dashboard, on 02-12-2021 -->

            <?php  if (!empty($appl_notifications)) { ?>

                <li class="nav-item dropdown" title="Notifications">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge"><?php echo count($appl_notifications); ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header"><?php echo count($appl_notifications); ?> Notifications</span>
                        <div class="dropdown-divider"></div>

                        <?php foreach ($appl_notifications as $each) { ?>
                            <a href="<?php echo $each['link']; ?>" class="dropdown-item">
                                <?php echo $each['message']; ?><br>
                                <span class="text-muted text-sm">on Date: <?php echo substr($each['on_date'],0,-9); ?></span>
                            </a>
                        <?php } ?>
                    </div>	
                </li>

            <?php  } ?>

            <li class="nav-item" title="Logout">
                <?php echo $this->Html->link('<i class="fas fa-power-off text-lg"></i>', array('controller'=>'common', 'action'=>'logout'), array('class'=>'nav-link', 'role'=>'button', 'escape'=>false)); ?>
            </li>
        </ul>   
    </nav>
<?php } ?>
