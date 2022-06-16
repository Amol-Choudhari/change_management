<?php 
	echo $this->Html->script('front.statistic.jquery.min');
	echo $this->Html->script('front.statistic.bootstrap.min');
?>

<!--Carousel Wrapper-->
<div id="multi-item-example" class="carousel slide carousel-multi-item" data-ride="carousel">

  <!--Controls-->
  <!--<div class="controls-top">
    <a class="btn-floating" href="#multi-item-example" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
    <a class="btn-floating" href="#multi-item-example" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
  </div>-->
  <!--/.Controls-->

  <!--Indicators-->
  <!--<ol class="carousel-indicators">
    <li data-target="#multi-item-example" data-slide-to="0" class="active"></li>
    <li data-target="#multi-item-example" data-slide-to="1"></li>
    <li data-target="#multi-item-example" data-slide-to="2"></li>
  </ol>-->
  <!--/.Indicators-->
<div class="clearfix"></div>
<div class="backimag">
  <!--Slides-->
  <div class="carousel-inner pd30_0" role="listbox">

    <!--First slide-->
    <div class="carousel-item active">

		<div class='offset-md-2 row'>
				
			<div class='col-md-2 squaretotal squaretotalr square s14-color'>
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/users.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['primary_user'] + 
						  $frontstatisctics['firms_registered'] + 
						  $frontstatisctics['t_users'] ; ?>
				</h1>
				<span>Total Online Users</span>
			</div>
			
			<div class='col-md-2 squaretotalr square s11-color'>
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/c_users.png" alt="revenu">
				</div>						
				<h1><?php echo $frontstatisctics['primary_user']; ?></h1>
				<span>Corporates Registered</span>
			</div>
			<div class='col-md-2 squaretotalr square s12-color'>
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/firms.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['firms_registered']; ?></h1>
				<span>Firms Registered</span>
			</div>
			<div class='col-md-2 squaretotalr square s13-color'>
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/gov_users.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['t_users']; ?></h1>
				<span>Govt Officials</span>
			</div>			
		</div>

    </div>
    <!--/.First slide-->

    <!--Second slide-->
    <div class="carousel-item">

		<div class='offset-md-2 row'>
			<div class='col-md-2 squaretotal squaretotalr square s24-color'>	
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/certificate.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['ca_firm_reg'] + 
						  $frontstatisctics['pp_firm_reg'] + 
						  $frontstatisctics['lb_firm_reg'] ; ?>
				</h1>
				<span>Total Applications Received</span>
			</div>
									
			<div class='col-md-2 squaretotalr square s21-color'>
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/ca.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['ca_firm_reg']; ?></h1>
				<span>Certificate of Authorisation</span>
			</div>
			<div class='col-md-2 squaretotalr square s22-color'>
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/cpp.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['pp_firm_reg']; ?></h1>
				<span>Printing Press Permission</span>
			</div>
			<div class='col-md-2 squaretotalr square s23-color'>
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/cl.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['lb_firm_reg']; ?></h1>
				<span>Approval of Laboratory</span>
			</div>			
		</div>

    </div>
    <!--/.Second slide-->

    <!--Third slide-->
    <div class="carousel-item">

		<div class='offset-md-2 row'>
			<div class='col-md-2 squaretotal squaretotalr square s34-color'>	
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/certificate.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['ca_new_grant'] + 
						  $frontstatisctics['printing_new_grant'] + 
						  $frontstatisctics['lab_new_grant']+
						  $frontstatisctics['ca_bk_grant'] + 
						  $frontstatisctics['pp_bk_grant'] + 
						  $frontstatisctics['lb_bk_grant']; ?>
				</h1>
				<span>Total Applications Granted </span>
			</div>
									
			<div class='col-md-2 squaretotalr square s31-color'>
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/ca.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['ca_new_grant']+
							   $frontstatisctics['ca_bk_grant'] ; ?></h1>
				<span>CA Applications Granted </span>
			</div>
			<div class='col-md-2 squaretotalr square s32-color'>
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/cpp.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['printing_new_grant']+
							   $frontstatisctics['pp_bk_grant']; ?></h1>
				<span>Printing Press Applications Granted </span>
			</div>
			<div class='col-md-2 squaretotalr square s33-color'>
				<div class='circle-icon'>
					<img src="/writereaddata/home-slider/image/cl.png" alt="revenu">
				</div>
				<h1><?php echo $frontstatisctics['lab_new_grant']+
							   $frontstatisctics['lb_bk_grant']; ?></h1>
				<span>Laboratory Applications Granted</span>
			</div>			
		</div>

    </div>
	
	
	<div class="carousel-item">
		<div class='offset-md-2 row'>
				<div class='col-md-2 squaretotal squaretotalr square s44-color'>
					<div class='circle-icon'>
						<img src="/writereaddata/home-slider/image/certificate.png" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['ca_renew_grant'] + 
							  $frontstatisctics['printing_renew_grant'] + 
							  $frontstatisctics['lab_renew_grant'] ; ?>
					</h1>
					<span>Total Renewal Applications Granted</span>
				</div>
										
				<div class='col-md-2 squaretotalr square s41-color'>
					<div class='circle-icon'>
						<img src="/writereaddata/home-slider/image/ca.png" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['ca_renew_grant']; ?></h1>
					<span>CA Renewal Applications Granted </span>
				</div>
				<div class='col-md-2 squaretotalr square s42-color'>
					<div class='circle-icon'>
						<img src="/writereaddata/home-slider/image/cpp.png" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['printing_renew_grant']; ?></h1>
					<span>Printing Press Renewal Applications Granted </span>
				</div>
				<div class='col-md-2 squaretotalr square s43-color'>
					<div class='circle-icon'>
						<img src="/writereaddata/home-slider/image/cl.png" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['lab_renew_grant']; ?></h1>
					<span>Laboratory Renewal Applications Granted</span>
				</div>			
		</div>
	  </div>	
	  
	  <div class="carousel-item">
		<div class='offset-md-2 row'>
				<div class='col-md-2 squaretotal squaretotalr square s54-color'>
					<div class='circle-icon'>
						<img src="/writereaddata/home-slider/image/revenu1.png" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['total_revenue'] ; ?>
					</h1>
					<span>Total Revenue</span>
				</div>
										
				<div class='col-md-2 squaretotalr square s51-color'>
					<div class='circle-icon'>
						<img src="/writereaddata/home-slider/image/newrevenu.png" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['reve_app_n']; ?></h1>
					<span>New Applications Revenue</span>
				</div>
				<div class='col-md-2 squaretotalr square s52-color'>
					<div class='circle-icon'>
						<img src="/writereaddata/home-slider/image/renewalrevenu.png" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['reve_app_r']; ?></h1>
					<span>Renewal Applications Revenue </span>
				</div>
				<div class='col-md-2 squaretotalr square s53-color'>
					<div class='circle-icon'>
						<img src="/writereaddata/home-slider/image/mony-transtaction.png" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['t_payment_trans']; ?></h1>
					<span>Payment Transactions</span>
				</div>
						
		</div>
	  </div>
	   <div class="carousel-item">
		<div class='offset-md-2 row'>
				<div class='col-md-2 squaretotal squaretotalr square s61-color'>
					<div class='circle-icon lims-circle-icon'>
						<span class="lims-text">LIMS</span>
						<img src="/writereaddata/home-slider/image/chemist.png" class="h44" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['t_chemist'] ; ?>
					</h1>
					<span>Chemists in <?php echo $frontstatisctics['t_labs'] ; ?> Labs</span>
				</div>
										
				<div class='col-md-2 squaretotalr square s62-color'>
					<div class='circle-icon lims-circle-icon'>
						<span class="lims-text">LIMS</span>
						<img src="/writereaddata/home-slider/image/sample-tube.png" class="h44" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['t_sample_r']; ?></h1>
					<span>Samples Received</span>
				</div>
				<div class='col-md-2 squaretotalr square s63-color'>
					<div class='circle-icon lims-circle-icon'>
						<span class="lims-text">LIMS</span>
						<img src="/writereaddata/home-slider/image/focus.png" class="h44" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['t_sample_p']; ?></h1>
					<span>Results Published</span>
				</div>
				<div class='col-md-2 squaretotalr square s64-color'>
					<div class='circle-icon lims-circle-icon'>
						<span class="lims-text">LIMS</span>
						<img src="/writereaddata/home-slider/image/commodities.png" class="h44" alt="revenu">
					</div>
					<h1><?php echo $frontstatisctics['t_commodity']; ?></h1>
					<span>Commodities Tested in <?php echo $frontstatisctics['t_test']; ?> Parameter</span>
				</div>
						
		</div>
	  </div>
	
    <!--/.Third slide-->

  </div>
  <!--/.Slides-->
 </div> 


<!-- Left and right controls -->
	  <a class="carousel-control-prev" href="#multi-item-example" data-slide="prev">
		<span class="carousel-control-prev-icon"></span>
	  </a>
	  <a class="carousel-control-next" href="#multi-item-example" data-slide="next">
		<span class="carousel-control-next-icon"></span>
	  </a>

</div>