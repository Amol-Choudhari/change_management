<?php echo $this->Html->css('dashboard/allocation-common-tabs-css'); ?>

 
<div id="ro_tabs_div">
	<ul class="nav nav-tabs">
		 <?php if($_SESSION['current_level'] == 'level_3' || 
			($_SESSION['current_level'] == 'level_4' && $current_user_roles['dy_ama'] == 'yes')){ 		 
		 ?>
			  <li id="for_scrutiny_allocation_tab" class="active nav-item" title="Status of Allocation/Reallocations for Scrutiny">
				<a data-toggle="tab" class="nav-link" href="#">For Scrutiny (<span id="for_scrutiny_allocation_count">0</span>)</a>
			  </li>
		 <?php } 
		 
		  if($_SESSION['current_level'] == 'level_3' && $_SESSION['level_3_for'] == 'RO'){
			?>
			  <li id="for_scrutiny_of_so_appl_tab" class="nav-item" title="Status of Allocation/Reallocations for Scrutiny of SO Applications.">
				<a data-toggle="tab" class="nav-link" href="#">For Scrutiny of SO Appl. (<span id="for_scrutiny_of_so_appl_count">0</span>)</a>
			  </li>
		 <?php }
		 
		 if($_SESSION['current_level'] == 'level_3'){
			?>
			  <li id="for_inspection_allocation_tab" class="nav-item" title="Status of Allocation/Reallocations for Site Inspection">
				<a data-toggle="tab" class="nav-link" href="#">For Inspection (<span id="for_inspection_allocation_count">0</span>)</a>
			  </li>
		 <?php }?>


	</ul>

	<div id="tab-content-bg" class="tab-content">

		<div id="allocation_common_applications_list">
			<!-- List will be loaded here -->
		</div>
		
	  
	</div>
</div>

<?php //echo $this->element('common_counts_and_list_elements/allocation_popup_models/scrutiny_allocation_popup'); ?>
	
<?php 
//commented script call, now all script is in common-count-js.js file as function call, changed on 21-10-2021 by Amol
//echo $this->Html->script('dashboard/allocation-common-tabs-js'); 
exit; //intensionally added exit; on 07-10-2021 by Amol, as CSRF blocking the next request after rendering the view through ajax ?>	
