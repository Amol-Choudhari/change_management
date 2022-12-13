	<?php ?>

<!-- on 23-10-2017, Below noscript tag added to check if browser Scripting is working or not, if not provided steps -->
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

        //Load CSS
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('font-awesome.min');
		echo $this->Html->css('../dashboard/css/datepicker3');
		echo $this->Html->css('cwdialog');
		echo $this->Html->css('chemist_layout');
		echo $this->Html->css('../dashboard/css/bootstrap-glyphicons.min');
		echo $this->Html->css('../dashboard/css/style');
		echo $this->Html->css('tempusdominus-bootstrap.min');
		echo $this->Html->css('icheck-bootstrap.min');
		echo $this->Html->css('jqvmap.min');
		echo $this->Html->css('adminlte.min');
		echo $this->Html->css('OverlayScrollbars.min');
		echo $this->Html->css('daterangepicker');
		echo $this->Html->css('summernote-bs4');
		echo $this->Html->css('all.min');
		echo $this->Html->css('dataTables.bootstrap.min');
		echo $this->Html->css('responsive.bootstrap.min');
		echo $this->Html->css('custom-style');
		echo $this->Html->css('../select2/css/select2.min');
		echo $this->Html->css('jquery-confirm.min');

        //Load Scripts
		echo $this->Html->script('jquery_main.min');
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->script('validation');
		echo $this->Html->script('jssor.slider-21.1.6.min');
		echo $this->Html->script('no_back');
		echo $this->Html->script('sha512.min');
		echo $this->Html->script('custom.validation.v.5');
		echo $this->Html->script('chemist_module_validations');
		echo $this->Html->script('../dashboard/js/bootstrap-datepicker');
		echo $this->Html->script('jquery-confirm.min');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

<title>Directorate of Marketing & Inspection</title>
</head>
<?php echo $this->element('common_loader'); ?>
<body  class="hold-transition sidebar-mini layout-fixed">

<?php echo $this->element('chemist_elements/chemist_profile_side_menu'); ?>

	<div class="wrapper main-header">

		<?php echo $this->element('main_site_header'); ?>

		<div class="col-md-12 mt-4">
			<?php echo $this->element('chemist_elements/chemist_progress_bar'); ?>
		</div>

		<?php echo $this->fetch('content'); ?>

		<?php echo $this->element('footer_section'); ?>

		<!-- form alerts -->
		<div id="toast-container" class="toast-top-right">
			<div id="toast-msg-box-error" class="toast toast-error" aria-live="assertive">
				<div class="toast-message" id="toast-msg-error"></div>
			</div>
			<div id="toast-msg-box-success" class="toast toast-success" aria-live="assertive">
				<div class="toast-message" id="toast-msg-success"></div>
			</div>
		</div>

	    <aside class="control-sidebar control-sidebar-dark">
	      <!-- Control sidebar content goes here -->
	    </aside>
	    <!-- /.control-sidebar -->
	  </div>

<?php echo $this->Html->script('../dashboard/js/bootstrap-datepicker'); ?>


<?php //added this code to fetch message boxes view commonly //11-02-2021
	if(!empty($message)){
		echo $this->element('message_boxes');
	}
?>

	<input type="hidden" id="process_query" value="<?php echo $process_query; ?>">
	<?php echo $this->Html->script('layouts/chemist_layout'); ?>

</body>
</html>
