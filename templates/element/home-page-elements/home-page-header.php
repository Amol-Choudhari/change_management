
<div id="home-page-header">
	<div class="logo-title row">
		<div class="col-md-2 col-xs-2 header-img1">
			<img class="img-responsive" src="/writereaddata/logos/emblem.png">
		</div>
		<div class="col-md-8 col-xs-8 header-text">

			<h2><?php echo $home_page_content[2]['title']; ?><br><?php echo $home_page_content[1]['title']; ?></h2>
			<h1><?php echo $home_page_content[0]['title']; ?></h1>
			
		</div>
		<div class="col-md-2 col-xs-2 header-img2">
			<img class="img-responsive" src="/writereaddata/logos/agmarklogo.png">
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="wrapper row1">
		<header id="header" class="hoc clear">
		  
	
		  <nav id="mainav">
			<ul class="clear">
			  <?php 
				foreach($sidemenus as $sidemenu){ ?>
				  <li>
					<?php 
					if(!empty($sidemenu['external_link'])){

						$url = 'home?'.'$type='.$sidemenu['link_type'].'&'.'$page='.$sidemenu['link_id'].'&'.'$menu='.$sidemenu['id'];
						echo $this->Html->link(__("<b>".$sidemenu['title']."</b>", $url), array('controller' => 'pages', 'action'=>$url), array('escape'=>false));

					} else {

						$url = 'home?'.'$type='.$sidemenu['link_type'].'&'.'$page='.$sidemenu['link_id'];
						echo $this->Html->link(__("<b>".$sidemenu['title']."</b>", $url), array('controller' => 'pages', 'action'=>$url), array('escape'=>false));

					}

					?>
				  </li>
				<?php } ?>
			</ul>
		  </nav>
		
		</header>
	  </div>
</div>