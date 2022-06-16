<?php ?>
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

        <?php foreach ($allSectionDetails as $eachSection) {  ?>
          <li class="nav-item">
            <a href="<?php echo $this->request->getAttribute('webroot');?>inspections/section/<?php echo $eachSection['section_id']; ?>" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p class="nav-icon-p"><?php echo ucwords(str_replace('_',' ',$eachSection['section_name'])); ?></p>
            </a>
          </li>							
        <?php } ?>			
      </ul>
    </nav>
	</div>
</aside>
