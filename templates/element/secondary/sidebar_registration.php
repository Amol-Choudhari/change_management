
<?php
    if (!isset($current_menu)) {
        $current_menu = '';
    }
?>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="index3.html" class="brand-link">
            <?php echo $this->Html->image('AdminLTELogo.png', array('alt'=>'AQCMS Logo', 'class'=>'brand-image img-circle elevation-3 op8')); ?>
            <span class="brand-text font-weight-light">AQCMS</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <?php echo $this->Html->image('profile.jpg', array("alt"=>"User Image", "height"=>"255", "class"=>"img-circle elevation-2")); ?>
                </div>

                <div class="info">
                    <a href="#" class="d-block"><?php echo $_SESSION["firm_name"];?></a>
                    <span class="right badge badge-light"><?php echo $_SESSION["username"];?></span>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="<?php echo $this->request->getAttribute("webroot");?>customers/secondary-home" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p class="nav-icon-p">Dashboard</p>
                        </a>
                    </li>

                <?php $sec_id = '1'; foreach ($allSectionDetails as $eachSection) { ?>

                    <li class="nav-item">
                        <a href="<?php echo $this->getRequest()->getAttribute('webroot');?>application/section/<?php echo $eachSection['section_id']; ?>" class="nav-link <?php if (isset($_SESSION['section_id']) && $_SESSION['section_id'] == $sec_id && ($current_menu != 'menu_payment')) { echo 'active'; } ?>">
                            <i class="far fa-circle nav-icon"></i>
                            <p class="nav-icon-p"><?php echo ucwords(str_replace('_',' ',$eachSection['section_name'])); ?></p>
                        </a>
                    </li>

                <?php $sec_id++; } ?>

            <?php if($allSectionDetails[0]['payment_section'] != "" && ($oldapplication=='no' || $_SESSION['application_type'] !=1 )) { ?>

                <li class="nav-item">
                    <a href="<?php echo $this->request->getAttribute('webroot');?>application/payment" class="nav-link <?php if ($current_menu == 'menu_payment') { echo 'active'; } ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <p class="nav-icon-p">Payment</p>
                    </a>
                </li>

            <?php } ?>

            </ul>
        </nav>
    </div>
</aside>
