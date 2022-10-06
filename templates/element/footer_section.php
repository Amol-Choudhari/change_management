	<?php
		$count = str_split($fetch_count['visitor']);
		$today_count = str_split($fetch_count['t_visitor']);
	?>

		<div class="clear"></div>
		<div  id="footer" class="mt-4 pb-4 elevation-3 rounded">
		
			<?php if($this->getRequest()->getSession()->read('username') == null){ ?>
			
				<!-- <div class="footerStrip"> -->
				<div class="footerStrip mb-3">
					<div class="mid">
						<?php echo $this->element('bottom_menu', array('bottommenus' => $bottommenus)); ?>
					</div>
				</div>

				<aside class="sidebar-dark-primary elevation-3 rounded">
					<div>
					  <nav class="">
								<ul class="nav nav-pills nav-sidebar justify-content-center font-weight-bold" data-widget="treeview" role="menu" data-accordion="false">
					<!-- <ul class="list-group list-group-horizontal mx-auto justify-content-center "> -->
						<!--<li class="first" >Page last updated on :  <span id="lblLastUpadtedon" class="myp">28/09/2016</span></li>-->

										<li class="list-group-item bg-transparent border-0 nav-item"  class="color_gray_for_home">Total Hits :

							<?php if(!empty($fetch_count)){ ?>
							<?php foreach($count as $each){ ?>

								<span id="lblTodaysCounts" class="myp" class="color_gray_for_home"><?php echo $each;?></span>

							<?php }
							}else{ ?><span id="lblTodaysCounts" class="myp" class="color_gray_for_home">00000</span><?php } ?>

						</li>
						<li class="list-group-item bg-transparent border-0 nav-item"  class="color_gray_for_home">Today's Hits :
							<?php if(!empty($fetch_count)){ ?>
							<?php foreach($today_count as $each){ ?>

								<span id="lblTodaysCounts" class="myp" class="color_gray_for_home"><?php echo $each;?></span>

							<?php }
							}else{ ?><span id="lblTodaysCounts" class="myp" class="color_gray_for_home">00000</span><?php } ?>

						</li>

					</ul>
							</nav>
						</div>
				</aside>
				<!-- </div> -->
				
			<?php } ?>

			<div class="textCenter">

				<?php  echo $footer_content; ?>

				<?php if($this->request->getAttribute('here') == $this->request->getAttribute('webroot')){ ?>
					<img class="elevation-3 rounded" class="logo_css" src="img/NIC_logo.jpg" />
				<?php }elseif(empty($this->request->getParam('pass'))){ ?>
					<img class="elevation-3 rounded" class="logo_css" src="../img/NIC_logo.jpg" />
				<?php }else{ ?>
					<img class="elevation-3 rounded" class="logo_css" src="../../img/NIC_logo.jpg" />
				<?php } ?>

			</div>
		</div>
