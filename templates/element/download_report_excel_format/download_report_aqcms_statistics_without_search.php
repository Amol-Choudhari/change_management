<?php //$ro_id = $_SESSION['ro_id']; 
if (isset($_SESSION['ro_id'])){ $ro_id = $_SESSION['ro_id']; }else{ $ro_id = ""; }

?>
<style>
  .box-1 { background-color:#57ca85; color:white; }
  .box-2 { background-color:#F36265; color:white; }
  .box-3 { background-color:#036ED9; color:white; }
  .box-4 { background-color:#7117ea; color:white; }
  .box-5 { background-color:#A1051D; color:white; }
  .box-6 { background-color:#f76b1c; color:white; }
  .box-7 { background-color:#622774; color:white; }
  .box-8 { background-color:#f2d50f; color:white; }
.stats_title { font-size:14px; }
.stats_main_figs { font-size:16px; }
.stats_subtitle { font-size:12px; color:#6C757D; }
.stats_sub_figs { font-size:14px; color:#495057; }
.subtitle2 { font-size:10px; color:#495057;  }
</style>
<?php  $firm_type_array = array('1'=>'CA','2'=>'Printing Press','3'=>'Approval of Laboratory'); 
	   $application_processed_type = array('New Application','Renewal Application','Backlog Application');	
?>

	<div class="panel panel-primary report-filterable-format">		
            <h3 class="panel-title col-md-12">At A Glance Statistics for --> <?php if(!empty($ro_id)) { 
				echo "RO Incharge --> $ro_name_list[$ro_id]"; 
				}
				else { 
					echo "All"; 
				} ?> </h3>
		
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">1</td>
					<td width="200" class="stats_title" class="box-1">Primary/Corporate User</td>
					<td width="100" align="center" class="stats_main_figs" class="box-1"><?php echo $statistics_counts[0]['primary_user']; ?></td>
				</tr>
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">2</td>
					<td width="200" class="stats_title" class="box-2">Total Firms Registered</td>
					<td width="100" align="center" class="stats_main_figs" class="box-2"><?php echo $statistics_counts[0]['firms_registered']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">2.1</td>
					<td width="170" class="stats_subtitle">CA</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['ca_firm_reg']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">2.2</td>
					<td width="170" class="stats_subtitle">Printing Press</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['pp_firm_reg']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">2.3</td>
					<td width="170" class="stats_subtitle">Approval of Laboratory</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['lb_firm_reg']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">2.4</td>
					<td width="170" class="stats_subtitle">Deleted Firms</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['delete_firm']; ?></td>
				</tr>
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">3</td>
					<td width="200" class="stats_title" class="box-3">In-Process</td>
					<td width="100" align="center" class="stats_main_figs" class="box-3"><?php echo $statistics_counts[0]['ca_ip_app_n']+$statistics_counts[0]['pp_ip_app_n']+$statistics_counts[0]['lb_ip_app_n']+ 
																		  $statistics_counts[0]['ca_ip_app_r']+$statistics_counts[0]['pp_ip_app_r']+$statistics_counts[0]['lb_ip_app_r']+
																		  $statistics_counts[0]['ca_ip_app_bk']+$statistics_counts[0]['pp_ip_app_bk']+$statistics_counts[0]['lb_ip_app_bk'];
															  ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">3.1</td>
					<td width="170" class="stats_subtitle">New Application</td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">a</td>
					<td width="140" class="stats_subtitle">CA</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['ca_ip_app_n']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">b</td>
					<td width="140" class="stats_subtitle">Printing Press</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['pp_ip_app_n']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">c</td>
					<td width="140" class="stats_subtitle">Approval of Laboratory</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['lb_ip_app_n']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">3.2</td>
					<td width="170" class="stats_subtitle">Renewal Application</td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">a</td>
					<td width="140" class="stats_subtitle">CA</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['ca_ip_app_r']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">b</td>
					<td width="140" class="stats_subtitle">Printing Press</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['pp_ip_app_r']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">c</td>
					<td width="140" class="stats_subtitle">Approval of Laboratory</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['lb_ip_app_r']; ?></td>
				</tr>
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">4</td>
					<td width="200" class="stats_title" class="box-4">Granted</td>
					<td width="100" align="center" class="stats_main_figs" class="box-4"><?php echo $statistics_counts[0]['ca_new_grant']+$statistics_counts[0]['printing_new_grant']+$statistics_counts[0]['lab_new_grant']+ 
																	$statistics_counts[0]['ca_renew_grant']+$statistics_counts[0]['printing_renew_grant']+$statistics_counts[0]['lab_renew_grant']+
																	$statistics_counts[0]['ca_bk_grant']+$statistics_counts[0]['pp_bk_grant']+$statistics_counts[0]['lb_bk_grant'];
														?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">4.1</td>
					<td width="170" class="stats_subtitle">New Application (E-signed)</td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">a</td>
					<td width="140" class="stats_subtitle">CA</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['ca_new_grant']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">b</td>
					<td width="140" class="stats_subtitle">Printing Press</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['printing_new_grant']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">c</td>
					<td width="140" class="stats_subtitle">Approval of Laboratory</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['lab_new_grant']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">4.2</td>
					<td width="170" class="stats_subtitle">Renewal Application (E-signed)</td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">a</td>
					<td width="140" class="stats_subtitle">CA</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['ca_renew_grant']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">b</td>
					<td width="140" class="stats_subtitle">Printing Press</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['printing_renew_grant']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">c</td>
					<td width="140" class="stats_subtitle">Approval of Laboratory</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['lab_renew_grant']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">4.3</td>
					<td width="170" class="stats_subtitle">Backlog Application</td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">a</td>
					<td width="140" class="stats_subtitle">CA</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['ca_bk_grant']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">b</td>
					<td width="140" class="stats_subtitle">Printing Press</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['pp_bk_grant']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">c</td>
					<td width="140" class="stats_subtitle">Approval of Laboratory</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['lb_bk_grant']; ?></td>
				</tr>
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">5</td>
					<td width="200" class="stats_title" class="box-5">Renewal Due</td>
					<td width="100" align="center" class="stats_main_figs" class="box-5"><?php echo $statistics_counts[0]['ca_renewal_due']+$statistics_counts[0]['pp_renewal_due']+$statistics_counts[0]['lb_renewal_due']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">5.1</td>
					<td width="170" class="stats_subtitle">CA</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['ca_renewal_due']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">5.2</td>
					<td width="170" class="stats_subtitle">Printing Press</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['pp_renewal_due']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">5.3</td>
					<td width="170" class="stats_subtitle">Approval of Laboratory</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['lb_renewal_due']; ?></td>
				</tr>
			</table>
			<div></div><div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">6</td>
					<td width="200" class="stats_title" class="box-6">Pending With</td>
					<td width="100" align="center" class="stats_main_figs" class="box-6"><?php echo $statistics_counts[0]['pending_mo']+$statistics_counts[0]['pending_io']+$statistics_counts[0]['pending_ro']+$statistics_counts[0]['pending_ho']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">6.1</td>
					<td width="170" class="stats_subtitle">With Scrutinizer</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['pending_mo']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">6.2</td>
					<td width="170" class="stats_subtitle">With IO</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['pending_io']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">6.3</td>
					<td width="170" class="stats_subtitle">With RO</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['pending_ro']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">6.4</td>
					<td width="170" class="stats_subtitle">With HO</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['pending_ho']; ?></td>
				</tr>
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">7</td>
					<td width="200" class="stats_title" class="box-7">Documents E-signed</td>
					<td width="100" align="center" class="stats_main_figs" class="box-7"><?php echo $statistics_counts[0]['e_sign_app_n']+$statistics_counts[0]['e_sign_insp_n']+$statistics_counts[0]['e_sign_grantc_n']+
																			$statistics_counts[0]['e_sign_app_r']+$statistics_counts[0]['e_sign_insp_r']+$statistics_counts[0]['e_sign_grantc_r']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">7.1</td>
					<td width="170" class="stats_subtitle">New Application</td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">a</td>
					<td width="140" class="stats_subtitle">Application</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['e_sign_app_n']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">b</td>
					<td width="140" class="stats_subtitle">Inspection Report</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['e_sign_insp_n']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">c</td>
					<td width="140" class="stats_subtitle">Grant Certificate</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['e_sign_grantc_n']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">7.2</td>
					<td width="170" class="stats_subtitle">Renewal Application</td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">a</td>
					<td width="140" class="stats_subtitle">Application</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['e_sign_app_r']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">b</td>
					<td width="140" class="stats_subtitle">Inspection Report</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['e_sign_insp_r']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">c</td>
					<td width="140" class="stats_subtitle">Grant Certificate</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['e_sign_grantc_r']; ?></td>
				</tr>
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">8</td>
					<td width="200" class="stats_title" class="box-8">Total Revenue</td>
					<td width="100" align="center" class="stats_main_figs"><?php echo $statistics_counts[0]['total_revenue']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">8.1</td>
					<td width="170" class="stats_subtitle">New Application Revenue</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['reve_app_n']; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">8.2</td>
					<td width="170" class="stats_subtitle">Renewal Application Revenue</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $statistics_counts[0]['reve_app_r']; ?></td>
				</tr>
			</table>


		
	</div>	