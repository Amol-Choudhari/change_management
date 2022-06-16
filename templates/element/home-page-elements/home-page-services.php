<div id="home-page-content" class="wrapper row3">
	  <main class="hoc container clear">
		<!-- main body -->

		<div class="center">
		  <h6 class="heading underline font-x2"><?php echo $home_page_content[3]['title']; ?></h6>
			<!--<h6 class="heading"><?php //echo $home_page_content[4]['title']; ?></h6>-->
		</div>
		<ul class="nospace group row">
		<li class="service col-md-2">
			<article><a href="../DMI_test/users/login_user"><img class="img-fluid" src="img/other/login-img.jpg"></a>
			  <h6 class="heading"><a href="../DMI_test/users/login_user"><?php echo $home_page_content[9]['title']; ?></a></h6>
			</article>
		  </li>
		  <li class="service col-md-2">
			<article><a href="<?php echo $this->request->getAttribute('webroot'); ?>customers/login-customer-redirect/1"><img class="img-fluid" src="img/other/login-img2.jpg"></a>
			  <h6 class="heading"><a href="<?php echo $this->request->getAttribute('webroot'); ?>customers/login-customer-redirect/1"><?php echo $home_page_content[5]['title']; ?></a></h6>
			</article>
		  </li>
		  <li class="service col-md-2">
			<article><a href="<?php echo $this->request->getAttribute('webroot'); ?>customers/login-customer-redirect/2"><img class="img-fluid" src="img/other/login-img3.jpg"></a>
			  <h6 class="heading"><a href="<?php echo $this->request->getAttribute('webroot'); ?>customers/login-customer-redirect/2"><?php echo $home_page_content[6]['title']; ?></a></h6>
			</article>
		  </li>
		  <li class="service col-md-2">
			<article><a href="<?php echo $this->request->getAttribute('webroot'); ?>customers/login-customer-redirect/3"><img class="img-fluid" src="img/other/login-img4.jpg"></a>
			  <h6 class="heading"><a href="<?php echo $this->request->getAttribute('webroot'); ?>customers/login-customer-redirect/3"><?php echo $home_page_content[7]['title']; ?></a></h6>
			</article>
		  </li>
		  <li class="service col-md-2">
			<article><a href="../LIMS_test/users/login_user"><img class="img-fluid" src="img/other/login-img5.jpg"></a>
			  <h6 class="heading"><a href="../LIMS_test/users/login_user"><?php echo $home_page_content[8]['title']; ?></a></h6>
			</article>
		  </li>
		  
		</ul>

		<!-- / main body -->
		<div class="clear"></div>
	  </main>
	</div>