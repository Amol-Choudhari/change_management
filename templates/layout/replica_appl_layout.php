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

		// new template css start
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
		echo $this->Html->css('element/line');
		// new template css end

		//echo $this->Html->script('jquery.min');

		echo $this->Html->script('jquery_main.min'); //newly added on 24-08-2020 updated js
		// echo $this->Html->script('bootstrap.min');
		//echo $this->Html->script('md5');
		// echo $this->Html->script('validation');
		echo $this->Html->script('primary_forms_validations');
		// echo $this->Html->script('jssor.slider-21.1.6.min');
		echo $this->Html->script('no_back');
		// echo $this->Html->script('cwdialog');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');

		// new template js start
		echo $this->Html->script('loader');
		echo $this->Html->script('jquery-ui.min');
		//echo $this->Html->script('jquery.min');
		echo $this->Html->script('bootstrap.bundle.min');
		echo $this->Html->script('jquery.validate.min');
		echo $this->Html->script('additional-methods.min');
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
		echo $this->Html->script('../select2/js/select2.full.min');
		echo $this->Html->script('jquery-confirm.min');

		// datatables
		echo $this->Html->script('jquery.dataTables.min');
		echo $this->Html->script('dataTables.bootstrap.min');
		echo $this->Html->script('dataTables.responsive.min');
		echo $this->Html->script('responsive.bootstrap.min');
		// new template js ends
	?>

<title>Directorate of Marketing & Inspection</title>
</head>
<?php echo $this->element('common_loader'); ?>
<body class="hold-transition sidebar-mini layout-fixed">


  <!-- Main Sidebar Container -->

  <?php echo $this->element('main_site_header'); ?>

  <?php echo $this->fetch('content'); ?>

  <?php echo $this->element('footer_section'); ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->



  <input type="hidden" id="bottom_layout_csrf_call" value="<?php echo json_encode($this->request->getParam('_csrfToken'))?>">


  </body>
  </html>

  <?php echo $this->Html->script('layouts/admin_dashboard/admin_dashboard'); ?>
