
<?php
// set blank if current_menu is not set for highlight left sidebar menus
// @by Aniket Ganvir dated 16th DEC 2020
if(!isset($current_menu))
  $current_menu = '';

$application_type = $_SESSION['application_type'];
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <?php echo $this->element('common_side_menus/common_top_left_logo'); ?>

  <div class="sidebar">
   <?php echo $this->element('common_side_menus/common_top_left_profile_firm'); ?>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <?php $checkUsername = explode('/',$_SESSION['username']);
    		if ($checkUsername[0] == 'CHM') { ?>
                <a href="<?php echo $this->request->getAttribute("webroot");?>chemist/home" class="nav-link">
    	<?php } else { ?>
            <a href="<?php echo $this->request->getAttribute("webroot");?>customers/secondary-home" class="nav-link">
    	<?php } ?>
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p class="nav-icon-p">Dashboard</p>
          </a>
        </li>
        <?php $sec_id = '1'; foreach($allSectionDetails as $eachSection) {

				$show = 'no';
				if($application_type == 3){
					if(in_array($eachSection['section_id'],$selectedSections)){
						$show = 'yes';
					}
				 }else{  $show = 'yes';  }

				if($show == 'yes'){
											?>
        <li class="nav-item">
          <a href="<?php echo $this->getRequest()->getAttribute('webroot');?>application/section/<?php echo $eachSection['section_id']; ?>" class="nav-link <?php if(isset($_SESSION['section_id']) && $_SESSION['section_id'] == $sec_id && ($current_menu != 'menu_payment')){ echo 'active'; } ?>">
            <i class="far fa-circle nav-icon"></i>
            <p class="nav-icon-p"><?php echo ucwords(str_replace('_',' ',$eachSection['section_name'])); ?></p>
          </a>
        </li>
		<?php $sec_id++; } } ?>
        <?php if($_SESSION['paymentSection'] == 'available') { ?>
        <li class="nav-item">
          <a id="mpayment" href="<?php echo $this->request->getAttribute('webroot');?>application/payment" class="nav-link <?php if($current_menu == 'menu_payment'){ echo 'active'; } ?>">
            <i class="far fa-circle nav-icon"></i>
            <p class="nav-icon-p">Payment</p>
          </a>
        </li>
        <?php } ?>
      </ul>
    </nav>
  </div>
</aside>
