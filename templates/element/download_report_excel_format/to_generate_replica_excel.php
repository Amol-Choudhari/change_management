<?php
//functionality to manage header and all table rows in excel sheet date:18/12/2017//
 
$header = "<table style='border: 5px solid black;'>
  				<tr>
  					<th style='color: #ffffff; background-color: #1ca6d9'>Srno</th>
  					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Year</th>
					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Month</th>
  					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Replica No.</th>
  					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Series No.</th>
	
  				</tr>";

 $srno=1;
 foreach ($resultArr as $each)
 {   
	$year = $each['year'];
	$month = $each['month'];
	$replica_no = $each['replica_no'];
	$series_no = $each['series_no'];

	$header .= "<tr>
 					<td style='border: 1px solid black'>$srno</td>
 					<td style='border: 1px solid black'>$year</td>
					<td style='border: 1px solid black'>$month</td>
 					<td style='border: 1px solid black'>$replica_no</td>
					<td style='border: 1px solid black'>$series_no</td>
 				</tr>";
	$srno=$srno+1;

 }

 $header .= "</table>";
 $filename='Replica_Series_Sheet.xls';
 header ( "Content-type: application/vnd.ms-excel" );
 header ( "Content-Disposition: attachment; filename=".$filename);
 echo $header;
 
?>
