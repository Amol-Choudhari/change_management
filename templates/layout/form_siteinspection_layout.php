<?php ?>
<noscript>
		<?php echo $this->element('javascript_disable_msg_box'); ?>
</noscript>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">

	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->charset();
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		//CSS Libraries
		echo $this->Html->css('../dashboard/plugins/fontawesome-free/css/all.min');
		echo $this->Html->css('../dashboard/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min');
		echo $this->Html->css('../dashboard/plugins/icheck-bootstrap/icheck-bootstrap.min');
		echo $this->Html->css('../dashboard/plugins/jqvmap/jqvmap.min');
		echo $this->Html->css('../dashboard/dist/css/adminlte.min');
		echo $this->Html->css('../dashboard/plugins/overlayScrollbars/css/OverlayScrollbars.min');
		echo $this->Html->css('../dashboard/plugins/summernote/summernote-bs4');

		//All Custom CSS Files 
		echo $this->Html->css('font-awesome.min');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('cwdialog');
		echo $this->Html->css('../dashboard/css/datepicker3');
		echo $this->Html->css('../dashboard/css/bootstrap-glyphicons.min');
		echo $this->Html->css('custom-style');
		echo $this->Html->css('jquery-confirm.min');

		//Custom JS Files
		echo $this->Html->script('jquery_main.min');
		echo $this->Html->script('cwdialog');
		echo $this->Html->script('validation');
		echo $this->Html->script('printing_forms_validations_new');
		echo $this->Html->script('laboratory_forms_validations');
		echo $this->Html->script('ca_forms_validations');
		echo $this->Html->script('bs-custom-file-input.min');
		echo $this->Html->script('jquery-confirm.min');

	
	?>

<title>Directorate of Marketing & Inspection</title>
</head>
<?php echo $this->element('common_loader'); ?>
<body class="hold-transition sidebar-mini layout-fixed">
	<?php echo $this->element('common_side_menus/form_siteinspection_layout_side_menus'); ?>
	<div class="wrapper main-header">
		<?php echo $this->element('main_site_header'); ?>

			<div class="row">
				<div class="col-9">
					<h6 class="applicationtextcolor ">Applicant ID: <?php echo $_SESSION['customer_id']; ?> - <?php echo $firm_details['firm_name']; ?></h6>
				</div>
				<!--<div class="col-3">
					<a href="<?php echo $download_application_pdf;?>" target="blank" style="float:right; color:#f09609; margin-right:20px;"><b>Download PDF Version</b></a>
				</div>-->		<!-- commented by akash on 09-03-2021-->
			</div>
			<div class="clearfix"></div>

			<div class="row mt-4">
				<div class="col-md-10">
					<?php echo $this->element('siteinspection_forms/progress_bar/io_report_progress_bar'); ?>
				</div>
			</div>

			<!--<div class="form-buttons">
				<?php if($back_to_inspection_level == 'level_2'){ ?>
					<a href="<?php echo $this->request->getAttribute('webroot');?>dashboard/home" >Back to Status Home</a>
				<?php }elseif($back_to_inspection_level == 'level_3' || $back_to_inspection_level == 'level_1'){ ?>
					<a href="<?php echo $this->request->getAttribute('webroot');?>dashboard/home" >Back to Status Home</a>
				<?php } ?>
			</div>-->

			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
	  		<?php echo $this->element('footer_section'); ?>

			<?php 
					//Call For JS Files For DATATABLES
			 		echo $this->Html->script('no_back');
					echo $this->Html->script('jquery.dataTables.min');
					echo $this->Html->script('dataTables.bootstrap.min');
					echo $this->Html->script('dataTables.responsive.min');
					echo $this->Html->script('responsive.bootstrap.min');

					//All Common Layout Jquey Libraries & Bootsrap Calls
					echo $this->Html->script('../dashboard/plugins/jquery-ui/jquery-ui.min');
					echo $this->Html->script('../dashboard/plugins/bootstrap/js/bootstrap.bundle.min');
					echo $this->Html->script('../dashboard/plugins/chart.js/Chart.min');
					echo $this->Html->script('../dashboard/plugins/sparklines/sparkline');
					echo $this->Html->script('../dashboard/plugins/jqvmap/jquery.vmap.min');
					echo $this->Html->script('../dashboard/plugins/jqvmap/maps/jquery.vmap.india');
					echo $this->Html->script('../dashboard/plugins/jquery-knob/jquery.knob.min');
					echo $this->Html->script('../dashboard/plugins/moment/moment.min');
					echo $this->Html->script('../dashboard/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min');
					echo $this->Html->script('../dashboard/plugins/summernote/summernote-bs4.min');
					echo $this->Html->script('../dashboard/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min');
					echo $this->Html->script('../dashboard/dist/js/adminlte');
					echo $this->Html->script('../dashboard/dist/js/pages/dashboard');
			?>



<?php
 //added this code to fetch message boxes view commonly //11-02-2021
	if(!empty($message)){
		echo $this->element('message_boxes');
	}
?>




	<?php if($this->request->getParam('controller') == 'scrutiny') { ?>

		<?php echo $this->Html->script('layouts/form_siteinspection_layout/scrutiny'); ?>

	<?php } if($this->request->getParam('controller') == 'inspections'){?>

		<?php echo $this->Html->script('layouts/form_siteinspection_layout/inspection'); ?>
		
	<?php } ?>


	<?php echo $this->Html->script('bootstrap.min');echo $this->Html->script('../dashboard/js/bootstrap-datepicker'); ?>

	<?php echo $this->Html->script('layouts/form_siteinspection_layout/form_siteinspection_layout'); ?>
</div>
</body>
</html>
