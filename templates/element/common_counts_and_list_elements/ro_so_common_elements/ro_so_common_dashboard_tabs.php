<?php echo $this->Html->css('dashboard/ro-so-common-tabs-css'); ?>

<div id="ro_tabs_div">
	<ul class="nav nav-tabs">
	  <li id="with_applicant_tab" class="active nav-item" title="Status of Communication with Applicant">
		<a data-toggle="tab" class="nav-link" href="#home" >With Applicant (<span id="with_applicant_tab_count">0</span>)</a>
	  </li>
	  <li id="scrutiny_tab" class="nav-item" title="Status of Communication for Scrutiny with MO/SMO">
		<a data-toggle="tab" class="nav-link" href="#home1" >For Scrutiny (<span id="scrutiny_tab_count">0</span>)</a>
	  </li>
	  <li id="reports_tab" class="nav-item" title="Status of Communication for Siteinspection Reports submitted by IO">
		<a data-toggle="tab" class="nav-link" href="#menu1" >IO Reports (<span id="reports_tab_count">0</span>)</a>
	  </li>
	  
	  <?php if($_SESSION['current_level']=='level_3' && $_SESSION['level_3_for']=='RO'){?>
		<li id="with_sub_offs_tab" class="nav-item" title="Status of Communication for applications forwarded by Sub. Office">
			<a data-toggle="tab" class="nav-link" href="#menu2" >With Sub Office (<span id="with_sub_offs_tab_count">0</span>)</a>
		</li>
	  <?php }elseif($_SESSION['current_level']=='level_3' && $_SESSION['level_3_for']=='SO'){ ?>
		<li id="with_reg_offs_tab" class="nav-item" title="Status of Communication for applications forwarded to Reg. Office">
			<a data-toggle="tab" class="nav-link" href="#menu3" >With Reg. Office (<span id="with_reg_offs_count">0</span>)</a>
		</li>
	  <?php } ?>
	  <?php if($_SESSION['current_level']=='level_3' && $_SESSION['level_3_for']=='RO'){?>
		<li id="with_ho_offs_tab" class="nav-item" title="Status of Communication for applications forwarded to Head Office">
			<a data-toggle="tab" class="nav-link" href="#menu4" >With HO(QC) (<span id="with_ho_offs_tab_count">0</span>)</a>
		</li>
	<?php } ?>
	</ul>

	<div id="tab-content-bg" class="tab-content">

		<div id="level_3_common_applications_list">
		
			<!--<div id="show_all"><?php //echo $this->element('common_counts_and_list_elements/all_app_list_element'); ?></div>
				
			<div id="show_common"><?php //echo $this->element('common_counts_and_list_elements/common_app_list_element'); ?></div>-->
		</div>
		
	  
	</div>
</div>

<?php //echo $this->element('common_counts_and_list_elements/allocation_popup_models/scrutiny_allocation_popup'); ?>
	
<input type="hidden" id="ro_so_session_level" value="<?php echo $_SESSION['current_level']; ?>">
<input type="hidden" id="ro_so_level_3_for" value="<?php echo $_SESSION['level_3_for']; ?>">

<?php 
//commented script call, now all script is in common-count-js.js file as function call, changed on 21-10-2021 by Amol
//echo $this->Html->script('dashboard/ro-so-common-tabs-js'); 
exit; //intensionally added exit; on 07-10-2021 by Amol, as CSRF blocking the next request after rendering the view through ajax ?>	
