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

            <!-- Session timer countdown - Aniket G [13-10-2022] -->
            <li class="nav-item">
                <?php $maxlifetime = ini_get("session.gc_maxlifetime") * 1000; ?>
                <input type="hidden" value="<?php echo $maxlifetime; ?>" id="session_timeout_value">
                <?php echo $this->Form->create(null, array('type' => 'file', 'enctype' => 'multipart/form-data', 'class' => '')); ?>
                <div id="session_timer">
                    <div id="session_timer_text">Session time:</div>
                    <div id="session_timer_counter"><?php echo $maxlifetime/(60*1000); ?> : 00</div>
                    <?php echo $this->Form->control('session_timer_id', array('type'=>'hidden', 'id'=>'session_timer_id', 'value'=>$_SESSION['browser_session_d'])); ?>
                    <?php echo $this->Form->control('session_timer_logout_url', array('type'=>'hidden', 'id'=>'session_timer_logout_url', 'value'=>$this->Url->build(['controller'=>'common', 'action'=>'sessionExpiredLogout']))); ?>
                    <?php echo $this->Form->control('session_username', array('type'=>'hidden', 'id'=>'session_username', 'value'=>$_SESSION['username'])); ?>
                    <?php echo $this->Form->control('session_timer_status', array('type'=>'hidden', 'id'=>'session_timer_status', 'value'=>0)); ?>
                </div>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Html->css('element/session_timer'); ?>
                <?php echo $this->Html->script('element/session_timer'); ?>
            </li>

            <li class="nav-item" title="Logout">
                <?php echo $this->Html->link('<i class="fas fa-power-off text-lg"></i>', array('controller'=>'common', 'action'=>'logout'), array('class'=>'nav-link', 'role'=>'button', 'escape'=>false)); ?>
            </li>
        </ul>   
    </nav>
<?php } ?>
