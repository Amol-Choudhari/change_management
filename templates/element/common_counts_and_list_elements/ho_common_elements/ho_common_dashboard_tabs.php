<?php echo $this->Html->css('dashboard/ho-common-tabs-css'); ?>

 
<div id="ho_tabs_div">
	<ul class="nav nav-tabs">
		 <?php if($current_user_roles['ho_mo_smo'] == 'yes'){ ?>
			  <li id="for_ho_scrutiny_tab" class="active nav-item" title="Status of Communication for Scrutiny of Application">
				<a data-toggle="tab" class="nav-link" href="#">Applications for Scrutiny (<span id="for_ho_scrutiny_count">0</span>)</a>
			  </li>
			  
		 <?php }if($current_user_roles['dy_ama'] == 'yes'){ ?>
			  <li id="for_dyama_tab" class="nav-item" title="Status of Communication for Application with Deputy AMA">
				<a data-toggle="tab" class="nav-link" href="#">Applications for Deputy AMA (<span id="for_dyama_count">0</span>)</a>
			  </li>
			  
		 <?php }if($current_user_roles['jt_ama'] == 'yes'){ ?>
			<li id="for_jtama_tab" class="nav-item" title="Status of Communication for Application with Joint AMA">
				<a data-toggle="tab" class="nav-link" href="#">Applications for Joint AMA (<span id="for_jtama_count">0</span>)</a>
			  </li>
		 
		 <?php }if($current_user_roles['ama'] == 'yes'){ ?>
			<li id="for_ama_tab" class="nav-item" title="Status of Communication for Application with AMA">
				<a data-toggle="tab" class="nav-link" href="#">Applications for AMA (<span id="for_ama_count">0</span>)</a>
			  </li>
	  
		 <?php } ?>

	</ul>

	<div id="tab-content-bg" class="tab-content">

		<div id="ho_level_common_applications_list">
			<!-- List will be loaded here -->
		</div>
		
	  
	</div>
</div>

<?php //echo $this->element('common_counts_and_list_elements/allocation_popup_models/scrutiny_allocation_popup'); ?>
	
<?php 
//commented script call, now all script is in common-count-js.js file as function call, changed on 21-10-2021 by Amol
//echo $this->Html->script('dashboard/ho-common-tabs-js'); 
exit; //intensionally added exit; on 07-10-2021 by Amol, as CSRF blocking the next request after rendering the view through ajax ?>	
