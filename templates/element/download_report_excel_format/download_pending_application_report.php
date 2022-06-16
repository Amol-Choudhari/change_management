<?php
//functionality to manage header and all table rows in excel sheet date:18/12/2017//
 
$list_status = array('Srno','Date','Application Type','Application ID','Pending With','Posted Office','User ID');	

$header = "<table style='border: 5px solid black;'>
			<tr styel='border: 2px solid black'>
				<th >Srno</th>
				<th styel='border: 2px solid black'>Date</th>
				<th styel='border: 2px solid black'>Application Type</th>
				<th styel='border: 2px solid black'>Application ID</th>
				<th styel='border: 2px solid black'>Pending With</th>
				<th styel='border: 2px solid black'>Posted Office</th>
				<th styel='border: 2px solid black'>User ID</th>
			</tr>";
 
 $srno=1;
 $i = 0 ;
 foreach ($orders as $order)
 {   
	$ax = $order;
	$axx = $application_type[$i];
	$axxx = $application_id[$i];
	$aiv = $user_roles[$i];
	$av	= $user_office[$i];
	$avi	= $user_email_id[$i];
	
	$header .= "<tr>
 					<td styel='border: 1px solid black'>$srno</td>
 					<td styel='border: 1px solid black'>$ax</td>
 					<td styel='border: 1px solid black'>$axx</td>
 					<td styel='border: 1px solid black'>$axxx</td>
 					<td styel='border: 1px solid black'>$aiv</td>
 					<td styel='border: 1px solid black'>$av</td>
 					<td styel='border: 1px solid black'>$avi</td> 					
 				</tr>";	

	  $srno=$srno+1;
	  $i=$i+1;
 }

 $header .= "</table>";
 $filename='Pending_Application_Report.xls';
 header ( "Content-type: application/vnd.ms-excel" );
 header ( "Content-Disposition: attachment; filename=".$filename);
 echo $header;
 
?>
