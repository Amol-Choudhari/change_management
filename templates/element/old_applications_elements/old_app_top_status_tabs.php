
<?php ?>


<div class="row">
			
			<div class="container">

				<ul class="tabs">

				<?php if($current_user_roles[0]['ro_inspection'] == 'yes'){?>
				
					<li id="tab-3" class="tab-link" data-tab="tab-3-content">Old Applications by Applicant</li>
					
					<li id="tab-4" class="tab-link" data-tab="tab-4-content">Old Applications by Authority</li>
				<?php } ?>

				</ul>

 	
				
				<div id="tab-3-content" class="tab-content"> 
					<div class="panel-group" id="accordion">
					  <div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">																									<!--define by pravin 13/06/2017-->
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Status of Communication with Applicants (<?php echo $old_ro_application_replied_count+$old_ro_application_pending_count; ?>/
																																		<?php echo $old_ro_application_pending_count+$old_ro_application_replied_count+$old_ro_application_referred_back_count+$old_ro_application_approved_count; ?>)</a>
						  </h4>
						</div>
						<div id="collapse1" class="panel-collapse collapse">
						  <div class="panel-body"><?php echo $this->element('old_applications_elements/old_app_top_ro_form_inspection_status_boxes'); ?></div>
						</div>
					  </div>
					</div> 	 
				</div>
				
				
				<div id="tab-4-content" class="tab-content"> 
					<div class="panel-group" id="accordion">
					  <div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">																		
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Status of Old Applications (<?php echo $auth_old_ro_application_pending_count; ?>/<?php echo $auth_old_ro_application_pending_count + $auth_old_ro_application_approved_count; ?>)</a>
						  </h4>
						</div>
						<div id="collapse2" class="panel-collapse collapse">
						  <div class="panel-body"><?php echo $this->element('auth_old_applications_elements/auth_old_app_ro_status_boxes'); ?></div>
						</div>
					  </div>
					</div> 	 
				</div>

			</div><!-- container -->
			
			
			
			
		</div>






<?php
	
	// Ajax Call to set current level values
	  
			  
			   $this->Js->get('#tab-3')->event(
				'click',
				$this->Js->request(
				  array('action' => 'set_current_level_3')
				)
			  );
		  
		  
		  echo $this->Js->writeBuffer();
                                                
?>





