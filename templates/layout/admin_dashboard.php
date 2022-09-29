
<?php ?>

<!-- on 23-10-2017, Below noscript tag added to check if browser Scripting is working or not, if not provided steps -->
<noscript><?php echo $this->element('javascript_disable_msg_box'); ?></noscript>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta name="viewport" content="width=device-width,initial-scale=1">
				<?php //ALL PLUGINS CSS				
					echo $this->Html->css('../dashboard/plugins/fontawesome-free/css/all.min');
					echo $this->Html->css('../dashboard/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min');
					echo $this->Html->css('../dashboard/plugins/icheck-bootstrap/icheck-bootstrap.min');
					echo $this->Html->css('../dashboard/plugins/jqvmap/jqvmap.min');
					echo $this->Html->css('../dashboard/dist/css/adminlte.min');
					echo $this->Html->css('../dashboard/plugins/overlayScrollbars/css/OverlayScrollbars.min');
					echo $this->Html->css('../dashboard/plugins/daterangepicker/daterangepicker');
					echo $this->Html->css('../dashboard/plugins/summernote/summernote-bs4');
				

					//Fetch Charset
					echo $this->Html->meta('icon');
					echo $this->Html->charset();

					//Fetch Meta Elements
					echo $this->fetch('meta');
					echo $this->fetch('css');
					echo $this->fetch('script');

					//Load CSS Files
					echo $this->Html->css('font-awesome.min');
					echo $this->Html->css('bootstrap.min');
					echo $this->Html->css('cwdialog');
					echo $this->Html->css('../dashboard/css/datepicker3');
					echo $this->Html->css('../dashboard/css/bootstrap-glyphicons.min');
				//	echo $this->Html->css('../dashboard/css/style');
					echo $this->Html->css('dataTables.bootstrap.min');
				//	echo $this->Html->css('toastr.min');
					echo $this->Html->css('../multiselect/jquery.multiselect');
					echo $this->Html->css('custom-style');
					echo $this->Html->css('jquery-confirm.min');

					//Load JS Files
					echo $this->Html->script('jquery_main.min');
					echo $this->Html->script('sha512.min');
					echo $this->Html->script('no_back');
					echo $this->Html->script('cwdialog');
					echo $this->Html->script('ckeditor/ckeditor', array('inline' => false));
					echo $this->Html->script('../chosen-select/chosen.jquery');
					echo $this->Html->script('../multiselect/jquery.multiselect');
					echo $this->Html->script('toastr.min');

					//Load Datatables Scripts
					echo $this->Html->script('jquery.dataTables.min');
					echo $this->Html->script('dataTables.bootstrap.min');
					echo $this->Html->script('dataTables.responsive.min');
					echo $this->Html->script('responsive.bootstrap.min');

					//Load Validations Scripts
					echo $this->Html->script('bs-custom-file-input.min');
					echo $this->Html->script('ca_forms_validations');
					echo $this->Html->script('printing_forms_validations_new');
					echo $this->Html->script('laboratory_forms_validations');
					echo $this->Html->script('admin_forms_validation');
					echo $this->Html->script('auth_old_processed_validations');
					echo $this->Html->script('table_filter');
				// 	echo $this->Html->script('validation');
				//	echo $this->Html->script('primary_forms_validations');
				// 	echo $this->Html->script('jssor.slider-21.1.6.min');
					echo $this->Html->script('jquery-confirm.min');

					
				?>

			<title>Directorate of Marketing & Inspection</title>
		</head>
		<?php echo $this->element('common_loader'); ?>
		<body class="hold-transition sidebar-mini layout-fixed">
			<?php echo $this->element('common_side_menus/dashboard_side_menus'); ?>

			<div class="wrapper main-header">
  				<?php echo $this->element('main_site_header'); ?>
  				<?php echo $this->fetch('content'); ?>
				<!-- form alerts -->
				<div id="toast-container" class="toast-top-right">
					<div id="toast-msg-box-error" class="toast toast-error" aria-live="assertive">
						<div class="toast-message" id="toast-msg-error"></div>
					</div>
					<div id="toast-msg-box-success" class="toast toast-success" aria-live="assertive">
						<div class="toast-message" id="toast-msg-success"></div>
					</div>
				</div>
			<?php echo $this->element('footer_section'); ?>
		</div>


		<?php //ALL PLUGINS SCRIPTS
			echo $this->Html->script("../dashboard/plugins/jquery-ui/jquery-ui.min.js");
			echo $this->Html->script("../dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js");
			echo $this->Html->script("../dashboard/plugins/chart.js/Chart.min.js");
			echo $this->Html->script("../dashboard/plugins/sparklines/sparkline.js");
			echo $this->Html->script("../dashboard/plugins/jqvmap/jquery.vmap.min.js");
			echo $this->Html->script("../dashboard/plugins/jqvmap/maps/jquery.vmap.india.js");
			echo $this->Html->script("../dashboard/plugins/jquery-knob/jquery.knob.min.js");
			echo $this->Html->script("../dashboard/plugins/moment/moment.min.js");
			echo $this->Html->script("../dashboard/plugins/daterangepicker/daterangepicker.js");
			echo $this->Html->script("../dashboard/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js");
			echo $this->Html->script("../dashboard/plugins/summernote/summernote-bs4.min.js");
			echo $this->Html->script("../dashboard/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js");
			echo $this->Html->script("../dashboard/dist/js/adminlte.js");
			echo $this->Html->script("../dashboard/dist/js/pages/dashboard.js");
		?>
		
		<?php echo $this->Html->script('bootstrap.min'); echo $this->Html->script('../dashboard/js/bootstrap-datepicker'); ?>


		<?php //added this code to fetch message boxes view commonly
			if(!empty($message)){
				echo $this->element('message_boxes');
			}
		?>

		<input type="hidden" id="bottom_layout_csrf_call" value="<?php echo json_encode($this->request->getParam('_csrfToken'))?>">
		<?php echo $this->Html->script('layouts/admin_dashboard/admin_dashboard.js'); ?>
	</body>
</html>
