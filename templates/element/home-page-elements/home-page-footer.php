	<?php
		$count = str_split($fetch_count['visitor']);
		$today_count = str_split($fetch_count['t_visitor']);
	?>

	<div class="clear"></div>
		
	<?php if ($this->getRequest()->getSession()->read('username') == null) { ?>
		
		<div id="home-page-header">
		<div class="wrapper row1">
			<header id="header" class="hoc clear">	  
		
			  <nav id="mainav">
				<ul class="clear">
				   <?php
					  foreach ($bottommenus as $bottommenu) { ?>
						<li>
						<!-- <li class="list-group-item bg-transparent border-0 "> -->
						  <?php
						  if (!empty($bottommenu['external_link'])) {
								$url = 'home?'.'$type='.$bottommenu['link_type'].'&'.'$page='.$bottommenu['link_id'].'&'.'$menu='.$bottommenu['id'];
								echo $this->Html->link(__($bottommenu['title'], $url), array('controller' => 'pages', 'action'=>$url), array('escape'=>false, 'class'=>'nav-link'));
						  } else {
								$url = 'home?'.'$type='.$bottommenu['link_type'].'&'.'$page='.$bottommenu['link_id'];
								echo $this->Html->link(__($bottommenu['title'], $url), array('controller' => 'pages', 'action'=>$url), array('escape'=>false, 'class'=>'nav-link'));
						  }
						  ?>
						</li>
					  <?php } ?>
				</ul>
			  </nav>
			
			</header>
		  </div>
		</div>

		<div>
		  <nav>
			<ul class="nav nav-pills nav-sidebar justify-content-center font-weight-bold" data-widget="treeview" role="menu" data-accordion="false">

				<li class="list-group-item bg-transparent border-0 nav-item cGray">Total Hits :

					<?php if (!empty($fetch_count)) { ?>
					<?php foreach($count as $each) { ?>

						<span id="lblTodaysCounts" class="myp cGray"><?php echo $each;?></span>

					<?php }
					} else { ?><span id="lblTodaysCounts" class="myp cGray">00000</span><?php } ?>

				</li>
				<li class="list-group-item bg-transparent border-0 nav-item cGray">Today's Hits :
					<?php if (!empty($fetch_count)) { ?>
					<?php foreach ($today_count as $each) { ?>

						<span id="lblTodaysCounts" class="myp cGray"><?php echo $each;?></span>

					<?php }
					} else { ?><span id="lblTodaysCounts" class="myp cGray">00000</span><?php } ?>

					</li>
				</ul>
			</nav>
		</div>
	<!-- </div> -->
		
	<?php } ?>
			
	<div class="wrapper row5">
		<div id="copyright" class="hoc clear">
			<div class="textAlignCenter">
				<?php  echo $footer_content; ?>
				<br>
				<?php if ($this->request->getAttribute('here') == $this->request->getAttribute('webroot')) { ?>
					<img class="elevation-3 rounded" class="logo_css" src="img/NIC_logo.jpg" />
				<?php } elseif (empty($this->request->getParam('pass'))) { ?>
					<img class="elevation-3 rounded" class="logo_css" src="../img/NIC_logo.jpg" />
				<?php } else { ?>
					<img class="elevation-3 rounded" class="logo_css" src="../img/NIC_logo.jpg" />
				<?php } ?>

			</div>
		</div>
	</div>
