<?php
//functionality to manage header and all table rows in excel sheet date:18/12/2017//

  $header = "<table style='border: 5px solid black;'>
  				<tr styel='border: 2px solid black'>
  					<th >Srno</th>
  					<th styel='border: 2px solid black'>Date</th>
  					<th styel='border: 2px solid black'>User Name(ID)</th>
  					<th styel='border: 2px solid black'>User Office</th>
  					<th styel='border: 2px solid black'>Added Roles</th>
  					<th styel='border: 2px solid black'>Removed Roles</th>
  					<th styel='border: 2px solid black'>Roles As on Date</th>
  				</tr>";
 $srno=1;
 $i = 0 ;
 foreach ($orders as $order)
 {   

 	$ax = $order['created'];
	$axx = $user_name_detail[$i];
	$axxx = $user_office[$i];
	$aiv = $add_user_roles_name_list[$i];
	$av	= $remove_user_roles_name_list[$i];
	$avi =	$user_roles_name_list[$i];

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

 $filename='User_Roles_Logs_History_Report.xls'; 
 header ( "Content-type: application/vnd.ms-excel" );
 header ( "Content-Disposition: attachment; filename=".$filename);
 echo $header;
?>