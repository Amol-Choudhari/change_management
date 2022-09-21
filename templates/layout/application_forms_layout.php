
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
		echo $this->Html->css('../dashboard/css/datepicker3');

		// new template css start
		echo $this->Html->css('../dashboard/css/bootstrap-glyphicons.min');
		echo $this->Html->css('font-awesome.min');
		//	echo $this->Html->css('bootstrap.min');
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

		// new template css end


		echo $this->Html->script('jquery_main.min');
		echo $this->Html->script('primary_forms_validations');
		echo $this->Html->script('cwdialog');

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
		// datatables
		echo $this->Html->script('jquery.dataTables.min');
		echo $this->Html->script('dataTables.bootstrap.min');
		echo $this->Html->script('dataTables.responsive.min');
		echo $this->Html->script('responsive.bootstrap.min');
		// new template js ends

		// call forms validation file
		
	
	
		echo $this->Html->script('chemist_module_validations');
		echo $this->Html->script('add_more_row');
		echo $this->Html->script('jquery-confirm.min');
		echo $this->Html->script('custom.validation.v.5');
		echo $this->Html->script('no_back');
		
	?>
	
	<?php if ($firm_type == 1) { 
			echo $this->Html->script('ca_forms_validations');
		 } elseif ($firm_type == 2) {
			echo $this->Html->script('printing_forms_validations_new');
		} elseif ($firm_type == 3) {
			echo $this->Html->script('laboratory_forms_validations');
		}
	?>

<title>Directorate of Marketing & Inspection</title>
</head>

<?php echo $this->element('common_loader'); ?>
<body class="hold-transition sidebar-mini layout-fixed">

<?php echo $this->element('common_side_menus/applications_forms_layout_side_menus'); ?>

<div class="wrapper main-header">
	<?php echo $this->element('main_site_header'); ?>
		<div class="content-wrapper" class="mH1254">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-8">
							<h5 class="text-gray">
				            	<?php
									if ($form_type=='F' && $ca_bevo_applicant=='yes') {
										echo str_replace('E1','F1',$section_details['section_heading']);
									} else {
										echo $section_details['section_heading'];
									}
								?>
							</h5>
						</div>

					<?php if ($application_type != 2) { ?>
							<div class="col-sm-4">
								<div class="alert bg-white p-2 pl-3 m-0">
									<h5 class="text-sm m-0"><i class="icon fas fa-info"></i> Progress Bar Status</h5>
									<div class="progress_bar_status m-0">
										<span class="grd_box bg-red"></span> Pending
										<span class="grd_box bg-green ml-2"></span> Saved
										<span class="grd_box bg-yellow ml-2"></span> Referred Back
									</div>
								</div>
							</div>
			  		<?php } ?>
	        	</div>
	      	</div>
    	</section>

		<?php if ($application_type != 2) { echo $this->element('application_forms/progress_bar/forms_application_progress_bar'); } ?>
		<?php echo $this->fetch('content'); ?>

	</div>

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

		<aside class="control-sidebar control-sidebar-dark"></aside>

	</div>
	
	<?php echo $this->Html->script('bootstrap.min'); echo $this->Html->script('../dashboard/js/bootstrap-datepicker'); ?>
	
	<input type="hidden" id="bottom_layout_csrf_call" value="<?php echo json_encode($this->request->getParam('_csrfToken'))?>">
	<?php echo $this->Html->script('layouts/application_forms_layout'); ?>
	</body>
</html>
