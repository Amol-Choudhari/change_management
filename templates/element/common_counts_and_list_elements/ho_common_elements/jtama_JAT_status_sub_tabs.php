<?php ?>
 
 <style>
 #jat_status_tabs_div ul li a{background:#fff; color:#0068b7; font-weight:bold; padding:5px 10px;}
 #jat_status_tabs_div ul .active a{background:#0068b7; color:#fff; border:none;}
 .nav-tabs{cursor:pointer;}

 </style>
 
<div id="jat_status_tabs_div">
	<ul class="nav nav-tabs">
	
		 <?php if($current_user_roles['jt_ama'] == 'yes'){ ?>
		 
			<li id="pending_jat_tab" class="active nav-item" title="Applications pending for site inspection through JAT">
				<a data-toggle="tab" class="nav-link" href="#">Pending to Create JAT (<span id="for_pending_jat_count">1</span>)</a>
			  </li>
			  
			  <li id="inprogress_jat_tab" class="nav-item" title="Application for inpsection is in progress throught JAT">
				<a data-toggle="tab" class="nav-link" href="#">Inspection In Progress (<span id="for_inprogress_jat_count">0</span>)</a>
			  </li>
			  
			  <li id="filed_reports_jat_tab" class="nav-item" title="Applications for which inpsection Report filed through JAT">
				<a data-toggle="tab" class="nav-link" href="#">JAT Filed Reports (<span id="for_filed_reports_jat_count">0</span>)</a>
			  </li>
	  
		 <?php } ?>

	</ul>

	<div class="tab-content" style="background:#d0eaff;">

		<div id="JAT_status_common_applications_list">
			<!-- List will be loaded here -->
		</div>
		
	  
	</div>
</div>

<?php //echo $this->element('common_counts_and_list_elements/allocation_popup_models/scrutiny_allocation_popup'); ?>
	
	
<script>
$(".loader").hide();
$("#jat_status_tabs_div li a").click(function() {
$("#jat_status_tabs_div li").removeClass('active');
$(this).parent().addClass('active');
});

//to get and append counts in HO sub tabs
//the countarray is alreay declared in previous script as global
$("#for_pending_jat_count").text(countarray['for_pending_jat'][maintabid]);
$("#for_inprogress_jat_count").text(countarray['for_inprogress_jat'][maintabid]);
$("#for_filed_reports_jat_count").text(countarray['for_filed_reports_jat'][maintabid]);

</script>

<script>

function fetch_jtama_JAT_status_list_ajax(list_for){
	
	$.ajax({
		type: "POST",
		async:true,
		url:"../dashboard/fetch_jtama_JAT_status_list",
		data:{list_for:list_for},
		beforeSend: function (xhr) { // Add this line
				$(".loader").show();$(".loadermsg").show();
				xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
				$(".loader").hide();$(".loadermsg").hide();
				$("#JAT_status_common_applications_list").show();
				$("#JAT_status_common_applications_list").html(data);
		}
	});
	
}
//ajax to fetch listing for pending JAT
$("#pending_jat_tab").click(function(){
	$("#JAT_status_common_applications_list").hide();
	var list_for = 'pending_jat';
	
	fetch_jtama_JAT_status_list_ajax(list_for);
});

//ajax to fetch listing for inprogress JAT
$("#inprogress_jat_tab").click(function(){
	$("#JAT_status_common_applications_list").hide();
	var list_for = 'inprogress_jat';
	
	fetch_jtama_JAT_status_list_ajax(list_for);
});

//ajax to fetch listing for filed reports JAT
$("#filed_reports_jat_tab").click(function(){
	$("#JAT_status_common_applications_list").hide();
	var list_for = 'filed_reports_jat';
	
	fetch_jtama_JAT_status_list_ajax(list_for);
});

</script>
<?php ?>
