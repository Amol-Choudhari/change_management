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
        <h3 class="panel-title col-md-10">At A Glance Statistics for --> <?php if(!empty($ro_id)) { 
			echo "RO Incharge --> $ro_name_list[$ro_id]"; 
			} 
			else { echo "All"; } ?> </h3>

<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">1</td>
					<td width="200" class="stats_title" class="box-1">Primary/Corporate User</td>
					<td width="100" align="center" class="stats_main_figs" class="box-1"><?php echo count($total_primary_user); ?></td>
				</tr>
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">2</td>
					<td width="200" class="stats_title" class="box-2">Total Firms Registered</td>
					<td width="100" align="center" class="stats_main_figs" class="box-2"><?php $total = 0; 
													foreach($total_firm_register as $each_firm) { $total = $total + $each_firm[0]['count'];  } 
													echo $total;
											  ?></td>
				</tr>
				<?php $i=1; foreach($total_firm_register as $each_firm){ ?>
					<tr>
						<td width="30" align="center"></td>
						<td width="30" align="right" class="subtitle2">2.<?php echo $i; ?></td>
						<td width="170" class="stats_subtitle"><?php echo $firm_type_array[$each_firm['certification_type']]; ?></td>
						<td width="100" align="center" class="stats_sub_figs"><?php echo echo $each_firm[0]['count']; ?></td>
					</tr>							
				<?php } ?>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">2.4</td>
					<td width="170" class="stats_subtitle">Deleted Firms</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo count($total_delete_firms); ?></td>
				</tr>
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">3</td>
					<td width="200" class="stats_title" class="box-3">In-Process</td>
					<td width="100" align="center" class="stats_main_figs" class="box-3"><?php $totalProcessed = 0; 
													foreach($application_processed as $each_application){ 
														$totalProcessed = $totalProcessed + $each_application[0][0]+$each_application[0][1]+$each_application[0][2];  
													} 
													echo $totalProcessed;												
											  ?></td>
				</tr>
				<?php $i=0; foreach($application_processed as $each_application){ ?>
					<tr>
						<td width="30" align="center"></td>
						<td width="30" align="right" class="subtitle2">3.<?php echo $i+1; ?></td>
						<td width="170" class="stats_subtitle"><?php echo $application_processed_type[$i]; ?></td>
					</tr>
					<tr>
						<td width="30" align="center"></td>
						<td width="30" align="right" class="subtitle2">a</td>
						<td width="170" class="stats_subtitle">CA</td>
						<td width="100" align="center" class="stats_sub_figs"><?php echo $each_application[0][0]; ?></td>
					</tr>
					<tr>
						<td width="30" align="center"></td>
						<td width="30" align="right" class="subtitle2">b</td>
						<td width="170" class="stats_subtitle">Printing Press</td>
						<td width="100" align="center" class="stats_sub_figs"><?php echo $each_application[0][1]; ?></td>
					</tr>
					<tr>
						<td width="30" align="center"></td>
						<td width="30" align="right" class="subtitle2">c</td>
						<td width="170" class="stats_subtitle">Approval of Laboratory</td>
						<td width="100" align="center" class="stats_sub_figs"><?php echo $each_application[0][2]; ?></td>
					</tr>
				<?php $i++; } ?>				
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">4</td>
					<td width="200" class="stats_title" class="box-4">Granted</td>
					<td width="100" align="center" class="stats_main_figs" class="box-4"><?php $totalGrant = 0; 
													foreach($application_processed as $each_application){ 
														$totalGrant = $totalGrant + $each_application[1][0]+$each_application[1][1]+$each_application[1][2];  
													} 
													echo $totalGrant;
											  ?></td>
				</tr>
				<?php $i=0; foreach($application_processed as $each_application){ ?>
					<tr>
						<td width="30" align="center"></td>
						<td width="30" align="right" class="subtitle2">4.<?php echo $i+1; ?></td>
						<td width="170" class="stats_subtitle"><?php echo $application_processed_type[$i]; ?></td>
					</tr>
					<tr>
						<td width="30" align="center"></td>
						<td width="30" align="right" class="subtitle2">a</td>
						<td width="170" class="stats_subtitle">CA</td>
						<td width="100" align="center" class="stats_sub_figs"><?php echo $each_application[1][0]; ?></td>
					</tr>
					<tr>
						<td width="30" align="center"></td>
						<td width="30" align="right" class="subtitle2">b</td>
						<td width="170" class="stats_subtitle">Printing Press</td>
						<td width="100" align="center" class="stats_sub_figs"><?php echo $each_application[1][1]; ?></td>
					</tr>
					<tr>
						<td width="30" align="center"></td>
						<td width="30" align="right" class="subtitle2">c</td>
						<td width="170" class="stats_subtitle">Approval of Laboratory</td>
						<td width="100" align="center" class="stats_sub_figs"><?php echo $each_application[1][2]; ?></td>
					</tr>
				<?php $i++; } ?>				
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">5</td>
					<td width="200" class="stats_title" class="box-5">Renewal Due</td>
					<td width="100" align="center" class="stats_main_figs" class="box-5"><?php echo $caRenewalDue+$printingRenewalDue+$labRenewalDue; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">5.1</td>
					<td width="170" class="stats_subtitle">CA</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $caRenewalDue; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">5.2</td>
					<td width="170" class="stats_subtitle">Printing Press</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $printingRenewalDue; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">5.3</td>
					<td width="170" class="stats_subtitle">Approval of Laboratory</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $labRenewalDue; ?></td>
				</tr>
			</table>
			<div></div><div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">6</td>
					<td width="200" class="stats_title" class="box-6">Pending With</td>
					<td width="100" align="center" class="stats_main_figs" class="box-6"><?php echo $pendingCountForMo+$pendingCountForIo
													+$pendingCountForHo+$pendingCountForRo; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">6.1</td>
					<td width="170" class="stats_subtitle">With Scrutinizer</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $pendingCountForMo; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">6.2</td>
					<td width="170" class="stats_subtitle">With IO</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $pendingCountForIo; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">6.3</td>
					<td width="170" class="stats_subtitle">With RO</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $pendingCountForHo; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">6.4</td>
					<td width="170" class="stats_subtitle">With HO</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $pendingCountForRo; ?></td>
				</tr>
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">7</td>
					<td width="200" class="stats_title" class="box-7">Documents E-signed</td>
					<td width="100" align="center" class="stats_main_figs" class="box-7"><?php echo $applicationEsigned+$inspectionReportEsigned+$certificateEsigned+
																			$renewalApplicationEsigned+$renewalInspectionReportEsigned+$renewalCertificateEsigned; ?></td>
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
					<td width="100" align="center" class="stats_sub_figs"><?php echo $applicationEsigned; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">b</td>
					<td width="140" class="stats_subtitle">Inspection Report</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $inspectionReportEsigned; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">c</td>
					<td width="140" class="stats_subtitle">Grant Certificate</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $certificateEsigned; ?></td>
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
					<td width="100" align="center" class="stats_sub_figs"><?php echo $renewalApplicationEsigned; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">b</td>
					<td width="140" class="stats_subtitle">Inspection Report</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $renewalInspectionReportEsigned; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="center"></td>
					<td width="30" align="center" class="subtitle2">c</td>
					<td width="140" class="stats_subtitle">Grant Certificate</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $renewalCertificateEsigned; ?></td>
				</tr>
			</table>
			<div></div>
			<table cellspacing="0" cellpadding="1" border="1">
				<tr>
					<td width="30" align="center" class="subtitle2">8</td>
					<td width="200" class="stats_title" class="box-8">Total Revenue</td>
					<td width="100" align="center" class="stats_main_figs"><?php echo $totalrevenue; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">8.1</td>
					<td width="170" class="stats_subtitle">New Application Revenue</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $newApplicationrevenue; ?></td>
				</tr>
				<tr>
					<td width="30" align="center"></td>
					<td width="30" align="right" class="subtitle2">8.2</td>
					<td width="170" class="stats_subtitle">Renewal Application Revenue</td>
					<td width="100" align="center" class="stats_sub_figs"><?php echo $renewalApplicationrevenue; ?></td>
				</tr>
			</table>

</div>