
$(".close").click(function(){ $("#dasboard_main_count_popop").hide(); });

//for RO tabs
$(".Applicant_reg_offc_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','RO','with_applicant'); });
$(".Applicant_reg_offc_cnt_R").click(function(){ setSessionForStatusTabsClick('replied','RO','with_applicant'); });

$(".Srutiny_reg_offc_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','RO','with_scrutiny'); });
$(".Srutiny_reg_offc_cnt_R").click(function(){ setSessionForStatusTabsClick('replied','RO','with_scrutiny'); });

$(".Inspection_reg_offc_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','RO','with_report'); });
$(".Inspection_reg_offc_cnt_R").click(function(){ setSessionForStatusTabsClick('replied','RO','with_report'); });

$(".sub_reg_offc_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','RO','with_sub_off'); });
$(".sub_reg_offc_cnt_R").click(function(){ setSessionForStatusTabsClick('replied','RO','with_sub_off'); });

$(".ho_reg_offc_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','RO','with_ho_off'); });
$(".ho_reg_offc_cnt_R").click(function(){ setSessionForStatusTabsClick('replied','RO','with_ho_off'); });

$(".reg_offc_cnt_allc_scr").click(function(){ setSessionForStatusTabsClick('allocation','RO','for_scr'); });
$(".reg_offc_cnt_allc_ins").click(function(){ setSessionForStatusTabsClick('allocation','RO','for_ins'); });

   

//for SO tabs
$(".Applicant_sub_offc_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','SO','with_applicant'); });
$(".Applicant_sub_offc_cnt_R").click(function(){ setSessionForStatusTabsClick('replied','SO','with_applicant'); });

$(".Srutiny_sub_offc_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','SO','with_scrutiny'); });
$(".Srutiny_sub_offc_cnt_R").click(function(){ setSessionForStatusTabsClick('replied','SO','with_scrutiny'); });

$(".Inspection_sub_offc_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','SO','with_report'); });
$(".Inspection_sub_offc_cnt_R").click(function(){ setSessionForStatusTabsClick('replied','SO','with_report'); });

$(".reg_sub_offc_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','SO','with_reg_off'); });
$(".reg_sub_offc_cnt_R").click(function(){ setSessionForStatusTabsClick('replied','SO','with_reg_off'); });

$(".sub_offc_cnt_allc_scr").click(function(){ setSessionForStatusTabsClick('allocation','SO','for_scr'); });
$(".sub_offc_cnt_allc_ins").click(function(){ setSessionForStatusTabsClick('allocation','SO','for_ins'); });

//for Scrutiny tabs
$(".scr_offc_cnt_nodal_P").click(function(){ setSessionForStatusTabsClick('pending','MO','scr_with_nodal'); });
$(".scr_offc_cnt_nodal_R").click(function(){ setSessionForStatusTabsClick('replied','MO','scr_with_nodal'); });

$(".scr_offc_cnt_ro_P").click(function(){ setSessionForStatusTabsClick('pending','MO','scr_with_reg'); });
$(".scr_offc_cnt_ro_R").click(function(){ setSessionForStatusTabsClick('replied','MO','scr_with_reg'); });

$(".scr_offc_cnt_ho_P").click(function(){ setSessionForStatusTabsClick('pending','MO','scr_with_ho'); });
$(".scr_offc_cnt_ho_R").click(function(){ setSessionForStatusTabsClick('replied','MO','scr_with_ho'); });

//for inspection tab
$(".site_inspect_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','IO','none'); });
$(".site_inspect_cnt_R").click(function(){ setSessionForStatusTabsClick('ref_back','IO','none'); });

//for HO QC tab
$(".ho_offc_cnt_dyama_P").click(function(){ setSessionForStatusTabsClick('pending','HO','for_dyama'); });
$(".ho_offc_cnt_dyama_R").click(function(){ setSessionForStatusTabsClick('replied','HO','for_dyama'); });

$(".ho_offc_cnt_jtama_P").click(function(){ setSessionForStatusTabsClick('pending','HO','for_jtama'); });
$(".ho_offc_cnt_jtama_R").click(function(){ setSessionForStatusTabsClick('replied','HO','for_jtama'); });

$(".ho_offc_cnt_ama_P").click(function(){ setSessionForStatusTabsClick('pending','HO','for_ama'); });
$(".ho_offc_cnt_ama_R").click(function(){ setSessionForStatusTabsClick('replied','HO','for_ama'); });

$(".ho_offc_cnt_allc_scr").click(function(){ setSessionForStatusTabsClick('allocation','HO','for_scr'); });

//for PAO Tab
$(".pao_offc_cnt_P").click(function(){ setSessionForStatusTabsClick('pending','PAO','none'); });
$(".pao_offc_cnt_R").click(function(){ setSessionForStatusTabsClick('replied','PAO','none'); });

function setSessionForStatusTabsClick(listFor,userLevel,listSubTab){
		
	$.ajax({
		type: "POST",
		async:false,
		url:"../dashboard/setSessionForStatusTabsClick",
		data:{listFor:listFor, listSubTab:listSubTab},
		beforeSend: function (xhr) {
			$(".loader").show();$(".loadermsg").show();
			xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
		}, 
		success: function (data) {
			$(".loader").hide();$(".loadermsg").hide();
			
			if(userLevel == 'RO'){
				$("#regional_office_btn").click();
			}
			if(userLevel == 'SO'){
				$("#sub_office_btn").click();
			}
			if(userLevel == 'MO'){
				$("#scrutiny_btn").click();
			}
			if(userLevel == 'IO'){
				$("#inspection_btn").click();
			}
			if(userLevel == 'HO'){
				$("#hO_quality_control_btn").click();
			}
			if(userLevel == 'PAO'){
				$("#pao_ddo_office_btn").click();
			}
				
			$("#dasboard_main_count_popop").hide();
		}
	});
	
}
