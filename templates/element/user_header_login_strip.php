<?php if (isset($_SESSION['username'])) { ?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
        <li class="nav-item dnone d-sm-inline-block"><a href="#" class="nav-link">Last Login: <?php echo $this->element('user_last_login'); ?> [IP: <?php echo $_SESSION["ip_address"];?>]</a></li>
    </ul>

    <!-- SEARCH FORM -->
    <?php echo $this->element('common_counts_and_list_elements/common_app_search_element'); ?>

    <div class="input-group input-group-sm form-inline ml-3 search-bx">
        <?php 
            if($this->getRequest()->getParam('controller')=='Dashboard' && $this->getRequest()->getParam('action')=='home'){

                    echo $this->Form->create(null,array('id'=>'pending_work_btn'));
                    echo $this->Form->Submit('Overall Pending Work Status',array('name'=>'get_pending_work','class'=>'btn btn-warning form-control','label'=>false));
                    echo $this->Form->End(); 
                }
        ?>
    </div>
    
    <!-- Session timer countdown - Aniket G [13-10-2022] -->
    <ul class="navbar-nav mb-n3 mr-2">
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
    </ul>

        <!-- Right navbar links -->
        <ul class="nav navbar-nav navbar-right">
            <li id="user" class="dropdown"><a href="#"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile<span class="caret"></span></a>
                <ul class="dropdown-menu" class="login_header_search_form">
                <li>
                    <li><a class="nav-link" href="<?php echo $this->getRequest()->getAttribute('webroot');?>users/user_profile"><i class="fas fa-address-book"></i> <span class="badge">View Profile</span></a></li>
                    <li><a class="nav-link" href="<?php echo $this->getRequest()->getAttribute('webroot');?>common/change_password"><i class="fas fa-key"></i> <span class="badge">Change Password</span></a></li>
                    <li><a class="nav-link" href="<?php echo $this->getRequest()->getAttribute('webroot');?>common/current_user_logs"><i class="fas fa-clock"></i> <span class="badge">Log History</span></a></li>
					<li><a class="nav-link" href="<?php echo $this->getRequest()->getAttribute('webroot');?>dashboard/all_manuals"><i class="fas fa-address-book"></i> <span class="badge">User Manuals</span></a></li>
                    <?php if ($current_user_division['role'] == 'Admin' || $current_user_division['role'] == 'Head Office') { ?>
                        <li><a class="nav-link" href="<?php echo $this->getRequest()->getAttribute('webroot');?>users/admin_logs"><i class="fas fa-clock"></i> <span class="badge">Admin Logs</span></a></li>
                    <?php } ?>
                    <?php if ($current_user_division['role'] == 'Admin' || $current_user_division['role'] == 'Head Office' || $current_user_division['role'] == 'RO/SO OIC') { ?>
                        <li><a class="nav-link" href="<?php echo $this->getRequest()->getAttribute('webroot');?>users/all_users_logs"><i class="fas fa-clock"></i> <span class="badge">All Users Logs</span></a></li>
                    <?php } ?>
                        <li><a class="nav-link" href="<?php echo $this->getRequest()->getAttribute('webroot');?>common/user_action_history"><i class="fas fa-clock"></i> <span class="badge">Action History</span></a></li>
                    <?php if ($current_user_division['division'] == 'BOTH') { ?>
                        <li class="dropdown"><a class="nav-link" href="../../LIMS/users/common_user_redirect_login/<?php echo $current_user_division['id']; ?>"><i class="fas fa-arrow-circle-right"></i><span class="badge">Go To LIMS</span></a></li>
                    <?php } ?>
                        <li><a class="nav-link" href="<?php echo $this->getRequest()->getAttribute('webroot');?>common/logout"><i class="fas fa-power-off"></i><span class="badge">Logout</span></a></li>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
<?php } ?>
