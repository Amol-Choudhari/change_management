<?php ?>
<aside class="main-sidebar sidebar-dark-primary elevation-4 rounded">
	<?php if (isset($_SESSION['userloggedin'])) { ?>
		<div class="sidebar h245">
	<?php  } else { ?>
   		 <div class="sidebar h391">
	<?php } ?>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        	<?php 
			foreach($sidemenus as $sidemenu){ ?>
	          <li class="nav-item">
	          	<?php 
				if(!empty($sidemenu['external_link'])){

		          	$url = 'home?'.'$type='.$sidemenu['link_type'].'&'.'$page='.$sidemenu['link_id'].'&'.'$menu='.$sidemenu['id'];
		          	echo $this->Html->link(__("<b>".$sidemenu['title']."</b>", $url), array('controller' => 'pages', 'action'=>$url), array('escape'=>false, 'class'=>'nav-link'));

		        } else {

					$url = 'home?'.'$type='.$sidemenu['link_type'].'&'.'$page='.$sidemenu['link_id'];
		          	echo $this->Html->link(__("<b>".$sidemenu['title']."</b>", $url), array('controller' => 'pages', 'action'=>$url), array('escape'=>false, 'class'=>'nav-link'));

		        }

		        ?>
	          </li>
	        <?php } ?>
        </ul>
      </nav>
    </div>
</aside>

