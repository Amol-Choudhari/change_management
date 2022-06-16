<?php 
//functionality to manage header and all table rows in excel sheet date:18/12/2017//
 
$header = "<table style='border: 5px solid black;'>
  				<tr styel='border: 2px solid black'>
  					<th >Srno</th>
  					<th styel='border: 2px solid black'>Date</th>
  					<th styel='border: 2px solid black'>Company ID</th>
  					<th styel='border: 2px solid black'>Application Type</th>
  					<th styel='border: 2px solid black'>Firm/Primises ID</th>
  					<th styel='border: 2px solid black'>Firm/Primises Name</th>
  					<th styel='border: 2px solid black'>State</th>
					<th styel='border: 2px solid black'>District</th>
  				</tr>";
				    
 $srno=1;
 $i = 0 ; 
 foreach ($orders as $order)
 {   
	$ax = $order['created'];
	$axx = $order['customer_primary_id'];
	$axxx = $application_type_name[$i];
	$aiv = $order['customer_id'];
	$av	= $order['firm_name'];
	$avi = $firms_states[$i];
	$avii = $firms_districts[$i];
	
	$header .= "<tr>
 					<td styel='border: 1px solid black'>$srno</td>
 					<td styel='border: 1px solid black'>$ax</td>
 					<td styel='border: 1px solid black'>$axx</td>
 					<td styel='border: 1px solid black'>$axxx</td>
 					<td styel='border: 1px solid black'>$aiv</td>
 					<td styel='border: 1px solid black'>$av</td>
 					<td styel='border: 1px solid black'>$avi</td>
					<td styel='border: 1px solid black'>$avii</td> 					
 				</tr>";	

	  $srno=$srno+1;
	  $i=$i+1;
 }

 $header .= "</table>";

 
 $filename='Newly_Added_Firms_Report.xls';
 header ( "Content-type: application/vnd.ms-excel" );
 header ( "Content-Disposition: attachment; filename=".$filename);
 echo $header;
 
?>
