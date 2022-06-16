<?php
//functionality to manage header and all table rows in excel sheet date:18/12/2017//

if ($user_id_field != 'ro_incharge_id') { 

	$exceltable = "<table style='border: 5px solid black;'>
					<tr styel='border: 2px solid black'>
						<th >Srno</th>
						<th styel='border: 2px solid black'>Date</th>
						<th styel='border: 2px solid black'>User Name(ID)</th>
						<th styel='border: 2px solid black'>Posted Office</th>
						<th styel='border: 2px solid black'>Application ID</th>
						<th styel='border: 2px solid black'>Application Type</th>  					
					</tr>";
} else {

	$exceltable = "<table style='border: 5px solid black;'>
					<tr styel='border: 2px solid black'>
						<th >Srno</th>
						<th styel='border: 2px solid black'>Date</th>
						<th styel='border: 2px solid black'>User Name(ID)</th>
						<th styel='border: 2px solid black'>Posted Office</th>						 					
					</tr>";
}
 
 $srno=1;
 $i = 0 ;
 foreach ($orders as $order)
 {   
	$ax = $order['created'];
	$axx = $user_name_detail[$i];
	$axxx = $order[$office_field];
	$aiv = $order['customer_id'];
	$av	= $order['application_type'];

	if($user_id_field != 'ro_incharge_id') { 
      
		$exceltable .= "<tr>
 					<td styel='border: 1px solid black'>$srno</td>
 					<td styel='border: 1px solid black'>$ax</td>
 					<td styel='border: 1px solid black'>$axx</td>
 					<td styel='border: 1px solid black'>$axxx</td>
 					<td styel='border: 1px solid black'>$aiv</td>
 					<td styel='border: 1px solid black'>$av</td> 
 				</tr>";		
	}else{

		$exceltable .= "<tr>
 					<td styel='border: 1px solid black'>$srno</td>
 					<td styel='border: 1px solid black'>$ax</td>
 					<td styel='border: 1px solid black'>$axx</td>
 					<td styel='border: 1px solid black'>$axxx</td> 	
 				</tr>";			 
	}      
	  $srno=$srno+1;
	  $i=$i+1;
 }

 $exceltable .= "</table>";

 $filename='Allocation_Logs_Details_Report.xls';
 header ( "Content-type: application/vnd.ms-excel" );
 header ( "Content-Disposition: attachment; filename=".$filename);
 echo $exceltable;
 
?>
