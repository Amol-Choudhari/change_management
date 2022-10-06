<?php ?>

<!-- on 23-10-2017, Below noscript tag added to check if browser Scripting is working or not, if not provided steps -->
<noscript><?php echo $this->element('javascript_disable_msg_box'); ?></noscript>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>Directorate of Marketing & Inspection</title>
		<?php
			echo $this->Html->meta('icon');
			echo $this->Html->charset();

			//CSS Files
			echo $this->Html->css('../dashboard/css/datepicker3');
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
			echo $this->Html->css('toastr.min');
			echo $this->Html->css('custom-style');
			echo $this->Html->css('jquery-confirm.min');


			//JS Files
			echo $this->Html->script('jquery_main.min');
			echo $this->Html->script('sha512.min');
			echo $this->Html->script('primary_forms_validations');
			echo $this->Html->script('no_back');
			echo $this->Html->script('../dashboard/js/bootstrap-datepicker');

			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');

			// new template js start
			echo $this->Html->script('jquery-ui.min');
			echo $this->Html->script('bootstrap.bundle.min');
			echo $this->Html->script('jquery.validate.min');
			echo $this->Html->script('additional-methods.min');
			echo $this->Html->script('toastr.min');
			echo $this->Html->script('adminlte');
			echo $this->Html->script('bs-custom-file-input.min');
			echo $this->Html->script('Chart.min');
			echo $this->Html->script('sparkline');
			echo $this->Html->script('jquery.vmap.min');
			echo $this->Html->script('jquery.vmap.india');
			echo $this->Html->script('jquery.knob.min');
			echo $this->Html->script('moment.min');
			echo $this->Html->script('daterangepicker');
			echo $this->Html->script('tempusdominus-bootstrap.min');
			echo $this->Html->script('summernote-bs4.min');
			echo $this->Html->script('jquery.overlayScrollbars.min');
			echo $this->Html->script('adminlte');
			echo $this->Html->script('jquery-confirm.min');


			// datatables
			echo $this->Html->script('jquery.dataTables.min');
			echo $this->Html->script('dataTables.bootstrap.min');
			echo $this->Html->script('dataTables.responsive.min');
			echo $this->Html->script('responsive.bootstrap.min');
			// new template js ends

			// call forms validation file
			echo $this->Html->script('laboratory_forms_validations');
			echo $this->Html->script('ca_forms_validations');
			echo $this->Html->script('printing_forms_validations_new');
		?>
	</head>

	<?php echo $this->element('common_loader'); ?>

	<body class="sidebar-mini layout-boxed">
		<?php echo $this->element('main_site_header'); ?>

		<div class="wrapper">
			<!-- Main Sidebar Container -->
			<?php echo $this->element('common_side_menus/front_side_menus'); ?>

			<div class="content-wrapper form_layout_wrapper"><?php echo $this->fetch('content'); ?></div>

			<!-- form alerts -->
			<div id="toast-container" class="toast-top-right">
				<div id="toast-msg-box-error" class="toast toast-error" aria-live="assertive">
					<div class="toast-message" id="toast-msg-error"></div>
				</div>
				<div id="toast-msg-box-success" class="toast toast-success" aria-live="assertive">
					<div class="toast-message" id="toast-msg-success"></div>
				</div>
			</div>

			<!-- Control Sidebar -->
			<aside class="control-sidebar control-sidebar-dark"></aside>
		</div>

		<div class="wrapper"><?php echo $this->element('footer_section'); ?></div>

		<?php
		//added this code to fetch message boxes view commonly
			if(isset($message) && $message != null){
				echo $this->element('message_boxes');
			}
		?>
	
		<?php echo $this->Html->script('../dashboard/js/bootstrap-datepicker'); ?>
		<input type="hidden" id="bottom_layout_csrf_call" value="<?php echo json_encode($this->request->getParam('_csrfToken'))?>">
	</body>
</html>
