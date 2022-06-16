<?php ?>

<!-- on 23-10-2017, Below noscript tag added to check if browser Scripting is working or not, if not provided steps -->	
<noscript>
		<?php echo $this->element('javascript_disable_msg_box'); ?>
</noscript>

<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'DMI');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
 
<?php
		echo $this->Html->meta('icon');
		echo $this->Html->charset();
		echo $this->Html->css('forms-style');
		echo $this->Html->css('cwdialog');
		echo $this->Html->css('../dashboard/css/bootstrap.min');
		echo $this->Html->css('../dashboard/css/datepicker3');
		echo $this->Html->css('../dashboard/css/styles');
		echo $this->Html->script('jquery.min');
		
		
	?>
	
<title>Directorate of Marketing & Inspection</title>
</head>


<body>
	<div class="main_container">

	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid row">
			<div style="text-align:center; margin:0;" class="navbar-header">
			
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button><div class="clearfix"></div>
			
				<!--<span class="navbar-brand" style="float:none; font-size:20px;">Directorate of Marketing &amp; Inspection </span>-->
				
				<div id="header">
					<?php echo $this->element('main_site_header'); ?> 
				</div>
			</div>
			<div style="clear:both;"></div>					
		</div><!-- /.container-fluid -->
		
		
		
		<h4 class="dashboard-login-info-top-strip">
			Logged in User <span><?php echo $this->Session->read('f_name');?> <?php echo $this->Session->read('l_name');?></span>  
			and IP Address [ <?php echo $_SESSION["ip_address"];?> ] 
			Last login: <?php echo $this->element('user_last_login'); ?> 
			
			<ul class="nav navbar-nav navbar-right" style="margin:0 50px 0 0 !important;">
				<li id="user" class="dropdown"><a style="margin:0;padding:0;color:#fff;border:none;" href="#"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Logout<span class="caret"></span></a>

					<ul class="dropdown-menu">
						<li>					
							<li><a style="margin:0;text-align:center;" href="<?php echo $this->request->getAttribute('webroot');?>users/logout">Logout</a></li>							
								<?php if($current_user_division['Dmi_user']['division'] == 'BOTH') { ?>
								
									<li class="dropdown">
										<a style="margin:0;text-align:center;" href="../../LIMS/users/common_user_redirect_login/<?php echo $current_user_division['Dmi_user']['id']; ?>">Go To LIMS</a>
									</li>
									
								<?php } ?>							
						</li>
					</ul>
				
				</li>		

			</ul>
		</h4>

		
		
	</nav>
	
		
	<div class="main">			

	
	<!-- inspection top application status boxes start-->

	<!-- inspection top application status boxes end-->		
	
	

		

		
		
		
	<!-- fetching page data start-->	
		
		<?php echo $this->Flash->render(); ?>
		<?php echo $this->fetch('content'); ?>
		
		
	<!-- fetching page data end-->	
		
		
		
		
	</div>	<!--/.main-->
	

	<?php echo $this->Html->script('../dashboard/js/jquery.minn'); ?>
	<?php echo $this->Html->script('../dashboard/js/bootstrap.min'); ?>

	<?php echo $this->Html->script('../dashboard/js/bootstrap-datepicker'); ?>
	<?php echo $this->Html->script('../dashboard/js/jquerysession'); ?>
	
	<?php //echo $this->Html->script('no_back'); ?>
	
	<?php if($this->request->getParam('controller')=='dashboard' && $this->request->getParam('action')=='home'){ ?>
	
		<?php echo $this->Html->script('../dashboard/js/chart.min'); ?>
		<?php echo $this->Html->script('../dashboard/js/line-bar-chart-data'); ?>
		<?php echo $this->Html->script('../dashboard/js/easypiechart'); ?>
		<?php echo $this->Html->script('../dashboard/js/easypiechart-data'); ?>
	
	<?php } ?>
	
	
	
	
	
	<script>

	
	
		$('#calendar').datepicker({
			
			format: "dd/mm/yyyy"
			autoclose: true
		});

		!function ($) {
		    $(document).on("click","ul.nav li.parent > a > span.icon", function(){          
		        $(this).find('em:first').toggleClass("glyphicon-minus");      
		    }); 
		    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	</script>	
	
	
	
	<!-- for top status tabs -->
	
	
	<?php echo $this->element('top_tabs_script_code'); ?>

	<!-- Below script is added on 03-07-2017 by Amol to always enable CSRF token on every form to POST key-->
 <script>

  $( document ).ready(function() {
   $("#Token_key_id").prop("disabled", false);
  });

 </script>
	
	<?php echo $this->Html->script('no_back'); //uncommented here on 11-11-2020 by Amol ?>
	
	<script>
		//below script used to disable all mouse events for 10 sec, if any submit click.
		//to prevent user from clicking any where while submit in process.
		//created on 24-11-2017 by Amol
/*		$(":submit").click(function() {
			$('.main_container').css('pointer-events','none');				
			setTimeout(function(){ $('.main_container').css('pointer-events','visible'); },4000);
		});	*/

		
		//to disable right click of all anchor tags// on 11-11-2020 by Amol
		$(document).bind("contextmenu",function(e){
			return false;
		});
	</script>
	
</div>
	
</body>

</html>
