<?php ?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  
  <?php echo $this->element('common_side_menus/common_top_left_logo'); ?>

  <div class="sidebar">
     <?php echo $this->element('common_side_menus/common_top_left_profile'); ?>
	
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
	
		<ul class="nav menu">
			<li class="active"><a href="<?php echo $this->request->webroot;?>dashboard/home">Home</a></li>
			<?php foreach($allSectionDetails as $eachSection) {  ?>
				<li><a href="<?php echo $this->request->webroot;?>scrutiny/section/<?php echo $eachSection['section_id']; ?>"><?php echo ucwords(str_replace('_',' ',$eachSection['section_name'])); ?></a></li>										
			<?php } ?>			
		</ul>

	</div>
	</div>
</aside>