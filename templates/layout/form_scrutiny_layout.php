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
			echo $this->Html->css('../dashboard/css/datepicker3');
			echo $this->Html->css('../dashboard/css/bootstrap-glyphicons.min');
			echo $this->Html->css('dataTables.bootstrap.min');
			echo $this->Html->css('jquery-confirm.min');
			#echo $this->Html->css('../dashboard/css/styles');
			echo $this->Html->css('custom-style');
			
			//FETCH
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');

			//LOAD SCRIPTS
			echo $this->Html->script('jquery_main.min');
			#echo $this->Html->script('../dashboard/js/lumino.glyphs');
			echo $this->Html->script('no_back');	 
			echo $this->Html->script('cwdialog');
			#echo $this->Html->script('validation');
			#echo $this->Html->script('ckeditor/ckeditor', array('inline' => false));
			echo $this->Html->script('../chosen-select/chosen.jquery');
			echo $this->Html->script('../multiselect/jquery.multiselect');
			echo $this->Html->script('ca_forms_validations');
			echo $this->Html->script('printing_forms_validations_new');
			echo $this->Html->script('laboratory_forms_validations');
			echo $this->Html->script('admin_forms_validation'); 
			echo $this->Html->script('auth_old_processed_validations');
			echo $this->Html->script('chemist_module_validations');
			echo $this->Html->script('add_more_row');
			echo $this->Html->script('custom.validation.v.5');
			echo $this->Html->script('table_filter');
			echo $this->Html->script('jquery-confirm.min');

			//DATATABLES SCRIPTS
			echo $this->Html->script('jquery.dataTables.min');
			echo $this->Html->script('dataTables.bootstrap.min');
			echo $this->Html->script('dataTables.responsive.min');
			echo $this->Html->script('responsive.bootstrap.min');
			
		?>
	
		<title>Directorate of Marketing & Inspection</title>
	</head>
	
	<?php echo $this->element('common_loader'); ?>

	<body class="hold-transition sidebar-mini layout-fixed">
		<?php echo $this->element('common_side_menus/form_scrunity_layout_side_menus'); ?>
		<div class="wrapper main-header">
			<?php echo $this->element('main_site_header'); ?> 
			<!-- Added this header on 15-11-2021 by Amol -->
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
			<div class="row">
				<div class="col-8">
						<h6 class="text-primary ml-3">Applicant ID: <?php echo $_SESSION['customer_id']; ?> - <?php echo $firm_details['firm_name']; ?></h6>
				</div>
				<div class="col-4">	
					<!--Option for to transfer the application
						if only one officer present in SO office
						Done by Pravin Bhakare 12-10-2021
					-->
					<?php if($firm_type == 2 && $officer_present_in_off == 1){ ?>
						<a href="#" class="float-right mr-3 tras-to-ro" title="This is a single officer posted office, so you have option to transfer the application to ro office">Transfer<sup class="fa fa-share"></sup></a>					
					<?php } ?>

					<a href="<?php echo $download_application_pdf;?>" target="blank" class="float-right fa fa-download mr-3 text-danger" title="Download Pdf Version Form"><span class="fa fa-file-powerpoint-o text-danger"></span></a>
				</div>
			</div>

			<div class="clearfix"></div>
			
			<div class="row float-right mr-3"></div>
			
			<div class="clearfix"></div>

			<div class="row mt-4">
				<div class="ml-3 col-md-12">
					<?php 
						if(!isset($_SESSION['ro_with'])){ $_SESSION['ro_with']= 'applicant'; }
						if($level3_current_comment_to=='applicant' || $_SESSION['ro_with']=='applicant'){ echo $this->element('application_forms/progress_bar/forms_scrutiny_progress_bar'); }
						elseif($level3_current_comment_to=='mo' || $_SESSION['ro_with']=='mo'){echo $this->element('application_forms/progress_bar/forms_romo_progress_bar'); }
						elseif($current_level=='level_1'){echo $this->element('application_forms/progress_bar/forms_romo_progress_bar'); }
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
			echo $this->Html->script("../dashboard/plugins/jquery-ui/jquery-ui.min");
			echo $this->Html->script("../dashboard/plugins/bootstrap/js/bootstrap.bundle.min");
			echo $this->Html->script("../dashboard/plugins/chart.js/Chart.min");
			echo $this->Html->script("../dashboard/plugins/sparklines/sparkline");
			echo $this->Html->script("../dashboard/plugins/jqvmap/jquery.vmap.min");
			echo $this->Html->script("../dashboard/plugins/jqvmap/maps/jquery.vmap.india");
			echo $this->Html->script("../dashboard/plugins/jquery-knob/jquery.knob.min");
			echo $this->Html->script("../dashboard/plugins/moment/moment.min");
			echo $this->Html->script("../dashboard/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min");
			echo $this->Html->script("../dashboard/plugins/summernote/summernote-bs4.min");
			echo $this->Html->script("../dashboard/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min");
			echo $this->Html->script("../dashboard/dist/js/adminlte");
			echo $this->Html->script("../dashboard/dist/js/pages/dashboard");

		?>

		<?php 
			//added this code to fetch message boxes view commonly 11-02-2021
			if (!empty($message)) {
				echo $this->element('message_boxes');
			}
		?>

		<!--Move the inline css and js to external file, Done by Pravin Bhakare 12-10-2021 -->
		<input type="hidden" id="currcontroller" value="<?php echo $this->request->getParam('controller'); ?>">
		<input type="hidden" id="bottom_layout_csrf_call" value="<?php echo json_encode($this->request->getParam('_csrfToken'))?>">
		<!--This -> Layouts inline JS Call -->
		<?php echo $this->Html->script('layouts/form_scrutiny_layout.js'); ?>
		<?php echo $this->Html->script('layouts/admin_dashboard/admin_dashboard.js'); ?>	
	</body>
</html>