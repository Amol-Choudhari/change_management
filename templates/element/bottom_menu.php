<?php
?>

<aside class="sidebar-dark-primary elevation-3 rounded">
    <div>
      <nav class="mt-1">
        <ul class="nav nav-pills nav-sidebar justify-content-center font-weight-bold" data-widget="treeview" role="menu" data-accordion="false">
      <!-- <div class="bg-warning mid d-flex"> -->
        <!-- <ul class="list-group list-group-horizontal mx-auto justify-content-center list-group-item-action"> -->
          <?php
          foreach($bottommenus as $bottommenu){ ?>
            <li class="nav-item">
            <!-- <li class="list-group-item bg-transparent border-0 "> -->
              <?php
              if(!empty($bottommenu['external_link'])){
      		        $url = 'home?'.'$type='.$bottommenu['link_type'].'&'.'$page='.$bottommenu['link_id'].'&'.'$menu='.$bottommenu['id'];
      		        echo $this->Html->link(__($bottommenu['title'], $url), array('controller' => 'pages', 'action'=>$url), array('escape'=>false, 'class'=>'nav-link'));
      	      }
              else{
      		        $url = 'home?'.'$type='.$bottommenu['link_type'].'&'.'$page='.$bottommenu['link_id'];
      		        echo $this->Html->link(__($bottommenu['title'], $url), array('controller' => 'pages', 'action'=>$url), array('escape'=>false, 'class'=>'nav-link'));
      	      }
              ?>
            </li>
          <?php } ?>
        </ul>
      <!-- </div> -->

    </nav>
  </div>
</aside>
