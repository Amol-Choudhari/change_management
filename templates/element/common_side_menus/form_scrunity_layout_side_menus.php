<?php $application_type = $_SESSION['application_type']; ?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  
 <?php echo $this->element('common_side_menus/common_top_left_logo'); ?>

	<div class="sidebar">
	<?php echo $this->element('common_side_menus/common_top_left_profile'); ?>

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item">
              <a href="<?php echo $this->request->getAttribute("webroot");?>dashboard/home" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p class="nav-icon-p">Dashboard</p>
              </a>
            </li>

            <?php 			
				foreach($allSectionDetails as $eachSection) {  
					$show = 'yes';
					//commented below code on 13-12-2022 by Amol, no need of conditions for change flow
					/*if($application_type == 3){
						if(in_array($eachSection['section_id'],$selectedSections)){
							$show = 'yes';
						}
					 }else{  $show = 'yes';  }*/
												
					if($show == 'yes'){	
										?>
               <li class="nav-item">               
                 <a href="<?php echo $this->request->getAttribute('webroot');?>scrutiny/section/<?php echo $eachSection['section_id']; ?>" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p class="nav-icon-p"><?php echo ucwords(str_replace('_',' ',$eachSection['section_name'])); ?></p>
                </a>	
              </li>												
		<?php } } ?>

      <li class="nav-item">
              
                  <?php if($_SESSION['paymentSection'] == 'available') { ?>
                    <a id="mpayment" href="<?php echo $this->request->getAttribute('webroot');?>scrutiny/payment" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p class="nav-icon-p">Payment</p>
                  </a>
                  <?php } ?>	
      </li>

        </ul>
    </nav>
  
	</div>

	
</aside>