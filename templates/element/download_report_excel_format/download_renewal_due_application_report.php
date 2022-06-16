<?php
//functionality to manage header and all table rows in excel sheet date:18/12/2017//
 
$header = "<table style='border: 5px solid black;'>
			<tr styel='border: 2px solid black'>
				<th >Srno</th>
				<th styel='border: 2px solid black'>Application ID</th>
				<th styel='border: 2px solid black'>Application Type</th>
				<th styel='border: 2px solid black'>Expiry Date</th>
				<th styel='border: 2px solid black'>State</th>
				<th styel='border: 2px solid black'>District</th>
			</tr>";

 $srno=1;
 for ($i=0; $i<sizeof($renewal_user_details); $i++) {
	
	$ax = $renewal_due_applications_id[$i]['customer_id']; // ['Dmi_grant_certificates_pdf']
	$axx = $all_application_type[$renewal_user_details[$i]['certification_type']]; // ['Dmi_firm']
	$axxx = $application_expiry_date[$i];
	$aiv = $all_states[$renewal_user_details[$i]['state']]; // ['Dmi_firm']
	$av	= $all_district[$renewal_user_details[$i]['district']]; // ['Dmi_firm']
	
	$header .= "<tr>
 					<td styel='border: 1px solid black'>$srno</td>
 					<td styel='border: 1px solid black'>$ax</td>
 					<td styel='border: 1px solid black'>$axx</td>
 					<td styel='border: 1px solid black'>$axxx</td>
 					<td styel='border: 1px solid black'>$aiv</td>
 					<td styel='border: 1px solid black'>$av</td>
 				</tr>";		
	 
	  $srno=$srno+1;
 }
 $header .= "</table>";
 $filename='Renewal_Due_Application_Report.xls';
 header ( "Content-type: application/vnd.ms-excel" );
 header ( "Content-Disposition: attachment; filename=".$filename);
 echo $header;
 
?>
