<?php echo $this->Html->css('dashboard/scrutiny-common-tabs-css'); ?>
 
<div id="ro_tabs_div">
	<ul class="nav nav-tabs">
		 <?php if($current_user_roles['mo_smo_inspection'] == 'yes'){ ?>
			  <li id="with_nodal_office_tab" class="active nav-item" title="Status of Communication with Nodal office">
				<a data-toggle="tab" class="nav-link" href="#">With Nodal Office (<span id="with_nodal_office_count">0</span>)</a>
			  </li>
			  <li id="with_reg_office_tab" class="nav-item" title="Status of Communication with Regional Office">
				<a data-toggle="tab" class="nav-link" href="#">With Reg. Office (<span id="with_reg_office_count">0</span>)</a>
			  </li>
		 <?php }?>
	  
		 <?php if($current_user_roles['ho_mo_smo'] == 'yes'){ ?>
			  <li id="with_ho_office_tab" class="nav-item" title="Status of Communication with HO Office (QC)">
				<a data-toggle="tab" class="nav-link" href="#">With HO (QC) (<span id="with_ho_office_count">0</span>)</a>
			  </li>
		 <?php } ?>

	</ul>

	<div id="tab-content-bg" class="tab-content">

		<div id="level_1_common_applications_list">
			<!-- List will be loaded here -->
		</div>
		
	  
	</div>
</div>

<?php //echo $this->element('common_counts_and_list_elements/allocation_popup_models/scrutiny_allocation_popup'); ?>
	
<?php 
//commented script call, now all script is in common-count-js.js file as function call, changed on 21-10-2021 by Amol
//echo $this->Html->script('dashboard/scrutiny-common-tabs-js'); 
exit; //intensionally added exit; on 07-10-2021 by Amol, as CSRF blocking the next request after rendering the view through ajax ?>	
