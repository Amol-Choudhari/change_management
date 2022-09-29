<?php ?>
<noscript><?php echo $this->element('javascript_disable_msg_box'); ?></noscript>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta name="viewport" content="width=device-width,initial-scale=1">
				<?php //ALL PLUGINS CSS
					echo $this->Html->css("../dashboard/plugins/fontawesome-free/css/all.min");
					echo $this->Html->css("../dashboard/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min");
					echo $this->Html->css("../dashboard/plugins/icheck-bootstrap/icheck-bootstrap.min");
					echo $this->Html->css("../dashboard/plugins/jqvmap/jqvmap.min");
					echo $this->Html->css("../dashboard/dist/css/adminlte.min");
					echo $this->Html->css("../dashboard/plugins/overlayScrollbars/css/OverlayScrollbars.min");
					echo $this->Html->css("../dashboard/plugins/summernote/summernote-bs4");
				
					echo $this->Html->meta('icon');
					echo $this->Html->charset();
					echo $this->Html->css('font-awesome.min');
					echo $this->Html->css('bootstrap.min');
					echo $this->Html->css('cwdialog');
				 // echo $this->Html->css('dataTables.bootstrap.min');
					echo $this->Html->css('../dashboard/css/datepicker3');
					echo $this->Html->css('../dashboard/css/bootstrap-glyphicons.min');
					echo $this->Html->css('dataTables.bootstrap.min');
					echo $this->Html->css('jquery-confirm.min');
				 // echo $this->Html->css('../dashboard/css/styles');
					echo $this->Html->css('custom-style');
				 // echo $this->Html->css('forms-style');
					echo $this->fetch('meta');
					echo $this->fetch('css');
					echo $this->fetch('script');

				// SCRIPTS		
				   echo $this->Html->script('jquery_main.min');
				// echo $this->Html->script('../dashboard/js/lumino.glyphs');
				// echo $this->Html->script('md5');
				   echo $this->Html->script('no_back');
				   echo $this->Html->script('cwdialog');
				// echo $this->Html->script('validation');
				// echo $this->Html->script('ckeditor/ckeditor', array('inline' => false));
				   echo $this->Html->script('../chosen-select/chosen.jquery');
				   echo $this->Html->script('../multiselect/jquery.multiselect');

				// DATATABLES SCRIPTS
				   echo $this->Html->script('jquery.dataTables.min');
				   echo $this->Html->script('dataTables.bootstrap.min');
				   echo $this->Html->script('dataTables.responsive.min');
				   echo $this->Html->script('responsive.bootstrap.min');

				// VALIDATIONS SCRIPTS
				   echo $this->Html->script('ca_forms_validations');
				   echo $this->Html->script('printing_forms_validations_new');
				   echo $this->Html->script('laboratory_forms_validations');
				   echo $this->Html->script('admin_forms_validation');
				   echo $this->Html->script('auth_old_processed_validations');
				   echo $this->Html->script('table_filter');
				   echo $this->Html->script('jquery-confirm.min');
				
			?>

			<title>Directorate of Marketing & Inspection</title>
		</head>
		<?php echo $this->element('common_loader'); ?>
		<body class="hold-transition sidebar-mini layout-fixed">
			<?php echo $this->element('common_side_menus/form_scrunity_layout_side_menus'); ?>
				<div class="wrapper main-header">
					<?php echo $this->element('main_site_header'); ?>
						<div class="row">
							<div class="col-9"><h6 class="applicationtextcolor">Applicant ID: <?php echo $_SESSION['customer_id']; ?> - <?php echo $firm_details['firm_name']; ?></h6></div>
						</div>
						<div class="clearfix"></div>

						<div class="container-fluid">
							<div class="col-md-12">
								<div class="row">
									<?php $action = $this->request->getParam('action'); ?>
									<?php if ($_SESSION['application_mode'] == 'edit' && !empty($_SESSION['fromauth']) != 'yes') { ?>
										<div class="col-md-9">
											<?php	echo $this->element('old_applications_elements/show_old_dates_flash_msg'); ?>
										</div>
										<div class="col-md-3 float-right">
											<?php echo $this->element('old_applications_elements/show_old_certificate_details_popup'); ?>
										</div>
									<?php }  ?>
								</div>
							</div>
						</div>
						
						<div class="row mt-5 ml-2">
							<div class="col-md-12">
								<?php
									if ($action !='applicationForCertificate') {
										echo $this->element('application_forms/progress_bar/forms_scrutiny_progress_bar');
									} else {
										echo $this->element('application_forms/progress_bar/forms_application_progress_bar');
									}
								?>
							</div>
						</div>

					<?php echo $this->Flash->render(); ?>
					<?php echo $this->fetch('content'); ?>
					<?php echo $this->element('footer_section'); ?>

				</div>


				<?php //ALL PLUGINS SCRIPTS
					
					// echo $this->Html->script('../dashboard/js/jquery-1.11.1.min');
					// echo $this->Html->script('../dashboard/js/bootstrap.min');
					// echo $this->Html->script('../dashboard/js/chart.min');
					// echo $this->Html->script('../dashboard/js/chart-data');
					// echo $this->Html->script('../dashboard/js/easypiechart');
					// echo $this->Html->script('../dashboard/js/easypiechart-data');
					// echo $this->Html->script('../dashboard/js/bootstrap-datepicker');
					   echo $this->Html->script('no_back');
					   echo $this->Html->script("../dashboard/plugins/jquery-ui/jquery-ui.min.js");
					   echo $this->Html->script("../dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js");
					   echo $this->Html->script("../dashboard/plugins/chart.js/Chart.min.js");
					   echo $this->Html->script("../dashboard/plugins/sparklines/sparkline.js");
					   echo $this->Html->script("../dashboard/plugins/jqvmap/jquery.vmap.min.js");
					   echo $this->Html->script("../dashboard/plugins/jqvmap/maps/jquery.vmap.india.js");
					   echo $this->Html->script("../dashboard/plugins/jquery-knob/jquery.knob.min.js");
					   echo $this->Html->script("../dashboard/plugins/moment/moment.min.js");
					   echo $this->Html->script("../dashboard/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js");
					   echo $this->Html->script("../dashboard/plugins/summernote/summernote-bs4.min.js");
					   echo $this->Html->script("../dashboard/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js");
					   echo $this->Html->script("../dashboard/dist/js/adminlte.js");
					   echo $this->Html->script("../dashboard/dist/js/pages/dashboard.js");
					   echo $this->Html->script('bootstrap.min'); 
					   echo $this->Html->script('../dashboard/js/bootstrap-datepicker'); 
				?>

			<?php if($this->request->getParam('controller') == 'scrutiny') { ?>

			<?php } if($this->request->getParam('controller') == 'inspections'){?>

			<?php } ?>

			 <?php
				if(!empty($message)){
					echo $this->element('message_boxes');
				}
			?>

			<input type="hidden" id="currcontroller" value="<?php echo $this->request->getParam('controller'); ?>">
			<input type="hidden" id="bottom_layout_csrf_call" value="<?php echo json_encode($this->request->getParam('_csrfToken'))?>">
			<?php echo $this->Html->script('layouts/old_app_scrutiny_layout'); ?> 
		</body>
	</html>
			