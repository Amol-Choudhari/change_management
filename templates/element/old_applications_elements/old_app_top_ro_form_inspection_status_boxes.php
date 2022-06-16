<?php 
		
		//ajax request for pending applications
		$this->Js->get('#old_ro_pending_applications')->event(
		'click',
		$this->Js->request(
			array('controller'=>'roinspections','action' => 'old_pending_applications'), 
			array('update'=>'#old_ro_with_applicant_applications_list',
				'before' => '$("#old_ro_with_applicant_ajax_loader").show();',
				'complete' => '$("#old_ro_with_applicant_ajax_loader").hide();$("#old_ro_with_applicant_applications_list").show()')
			
		
		)
		);
		
		
		//ajax request for referred back applications
		$this->Js->get('#old_ro_referred_back_applications')->event(
		'click',
		$this->Js->request(
			array('controller'=>'roinspections','action' => 'old_referred_back_applications'), 
			array('update'=>'#old_ro_with_applicant_applications_list',
				'before' => '$("#old_ro_with_applicant_ajax_loader").show();',
				'complete' => '$("#old_ro_with_applicant_ajax_loader").hide();$("#old_ro_with_applicant_applications_list").show()')
			
		
		)
		);
		
		
		
		//ajax request for referred back applications
		$this->Js->get('#old_ro_replied_applications')->event(
		'click',
		$this->Js->request(
			array('controller'=>'roinspections','action' => 'old_replied_applications'), 
			array('update'=>'#old_ro_with_applicant_applications_list',
				'before' => '$("#old_ro_with_applicant_ajax_loader").show();',
				'complete' => '$("#old_ro_with_applicant_ajax_loader").hide();$("#old_ro_with_applicant_applications_list").show()')
			
		
		)
		);
		
		
		
		//ajax request for approved applications
		$this->Js->get('#old_ro_approved_applications')->event(
		'click',
		$this->Js->request(
			array('controller'=>'roinspections','action' => 'old_approved_applications'), 
			array('update'=>'#old_ro_with_applicant_applications_list',
				'before' => '$("#old_ro_with_applicant_ajax_loader").show();',
				'complete' => '$("#old_ro_with_applicant_ajax_loader").hide();$("#old_ro_with_applicant_applications_list").show()')
			
		
		)
		);

		 echo $this->Js->writeBuffer();
?>	

		
		<div class="row">
			<div class="col-xs-6 col-md-6 col-lg-3 col-sm-6">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<svg class="glyph stroked bag"><use xlink:href="#stroked-bag"></use></svg>
						</div>
						<a id="old_ro_pending_applications" oncontextmenu="return false;" href="<?php echo $this->request->getAttribute('webroot');?>roinspections/old_pending_applications"><div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $old_ro_application_pending_count; ?></div>
							<div class="text-muted">Pending</div>
						</div></a>
					</div>
				</div>
			</div>
			
			<div class="col-xs-6 col-md-6 col-lg-3 col-sm-6">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>
						</div>
					<a id="old_ro_referred_back_applications" oncontextmenu="return false;" href="<?php echo $this->request->getAttribute('webroot');?>roinspections/old_referred_back_applications">	<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $old_ro_application_referred_back_count; ?></div>
							<div class="text-muted">Referred Back</div>
						</div></a>
					</div>
				</div>
			</div>
			
			<div class="col-xs-6 col-md-6 col-lg-3 col-sm-6">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg>
						</div>
					<a id="old_ro_replied_applications" oncontextmenu="return false;" href="<?php echo $this->request->getAttribute('webroot');?>roinspections/old_replied_applications">	<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $old_ro_application_replied_count; ?></div>
							<div class="text-muted">Replied</div>
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
					<a id="old_ro_approved_applications" oncontextmenu="return false;" href="<?php echo $this->request->getAttribute('webroot');?>roinspections/old_approved_applications"> <div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo $old_ro_application_approved_count; ?></div>
							<div class="text-muted">Scrutinized</div>
						</div></a>
					</div>
				</div>
			</div>
			
		</div><!--/.row-->
		
		
		
	
		
	
		<!-- to show loading gif while ajax loading -->
		<div class="textCenter">
			<?php echo $this->Html->image('ajax-loader.gif', array('id'=>'old_ro_with_applicant_ajax_loader', 'style'=>'display:none;margin-top:50px;')); ?>
		</div>
	


		<div id="old_ro_with_applicant_applications_list">
		
		<!--  applications list will be shown on ajax request complete -->

		</div>
		
		
		
		
		
		
		
		
	