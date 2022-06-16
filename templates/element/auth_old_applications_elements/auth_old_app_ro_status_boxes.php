	

		
		<div class="row">
			<div class="col-xs-6 col-md-6 col-lg-3 col-sm-6">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<svg class="glyph stroked bag"><use xlink:href="#stroked-bag"></use></svg>
						</div>
						<a id="auth_old_ro_pending_applications" oncontextmenu="return false;" href="<?php echo $this->request->getAttribute('webroot');?>roinspections/auth_old_pending_applications"><div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $auth_old_ro_application_pending_count; ?></div>
							<div class="text-muted">Pending</div>
						</div></a>
					</div>
				</div>
			</div>
			
			<div class="col-xs-6 col-md-6 col-lg-3 col-sm-6">
				<div class="panel panel-orange panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<svg class="glyph stroked empty-message"><use xlink:href="#stroked-empty-message"></use></svg>
						</div>
					<a id="auth_old_ro_approved_applications" oncontextmenu="return false;" href="<?php echo $this->request->getAttribute('webroot');?>roinspections/auth_old_approved_applications"> <div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $auth_old_ro_application_approved_count; ?></div>
							<div class="text-muted">Scrutinized</div>
						</div></a>
					</div>
				</div>
			</div>
			
		</div><!--/.row-->
		
		
		
	
		
	
		<!-- to show loading gif while ajax loading -->
		<div class="textCenter">
			<?php echo $this->Html->image('ajax-loader.gif', array('id'=>'auth_old_ro_with_applicant_ajax_loader', 'style'=>'display:none;margin-top:50px;')); ?>
		</div>
	


		<div id="auth_old_ro_with_applicant_applications_list">
		
		<!--  applications list will be shown on ajax request complete -->

		</div>
		
		
		
		
		
		
		
		
	