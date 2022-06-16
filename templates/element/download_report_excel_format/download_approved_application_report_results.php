<?php
//functionality to manage header and all table rows in excel sheet date:18/12/2017//
 
$header = "<table style='border: 5px solid black;'>
  				<tr>
  					<th style='color: #ffffff; background-color: #1ca6d9'>Srno</th>
  					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Date</th>
					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Process</th>
  					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Application Type</th>
  					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Application ID</th>
  					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Approved Office</th>
  					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>User ID</th>
  					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Commodity</th>
					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Firm Name</th>
					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Firm Address</th>
					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Contact Details</th>
					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>TBL Details</th>
					<th style='border: 2px solid black; color: #ffffff; background-color: #1ca6d9'>Lab Details</th>
	
  				</tr>";

 $srno=1;
 $i = 0 ;
 foreach ($orders as $order)
 {   
	$date = $order;
	$process = $approved_application_type[$i];
	$applicationType = $application_type[$i];
	$applicationId = $application_id[$i];
	$approvedOffice = $user_office[$i];
	$userId	= $application_user_email_id[$i];//chnged variable on 27-04-2019
	$commodity	= $commodity_list[$i];
	$firmName	= $name_of_the_firm[$i];
	$firmAddress	= $address_of_the_firm[$i];
	$contactDetails	= $contact_details_of_the_firm[$i];

	$countTbl = count($approved_TBL_details_tbl_name[$i]);
	$tblDeatils = '';

	for($j=0;$j<$countTbl;$j++){
			
		$tblDeatils .= "<b>TBL Name : </b>".$approved_TBL_details_tbl_name[$i][$j].","." "."<b>TBL Reg No : </b> ".$approved_TBL_details_tbl_registered_no[$i][$j];
		$tblDeatils.="\n";
	}

	$laboratoryDetails	= "<b>Name : </b>".$laboratory_details_name[$i].","." "."<b>Address : </b>".$laboratory_details_address[$i];
	
	$header .= "<tr>
 					<td style='border: 1px solid black'>$srno</td>
 					<td style='border: 1px solid black'>$date</td>
					<td style='border: 1px solid black'>$process</td>
 					<td style='border: 1px solid black'>$applicationType</td>
 					<td style='border: 1px solid black'>$applicationId</td>
 					<td style='border: 1px solid black'>$approvedOffice</td>
 					<td style='border: 1px solid black'>$userId</td>
 					<td style='border: 1px solid black'>$commodity</td>
 					<td style='border: 1px solid black'>$firmName</td>
 					<td style='border: 1px solid black'>$firmAddress</td>
 					<td style='border: 1px solid black'>$contactDetails</td>
					<td style='border: 1px solid black'>$tblDeatils</td>
					<td style='border: 1px solid black'>$laboratoryDetails</td>
 				</tr>";
	$srno=$srno+1;
	$i=$i+1;
 }

 $header .= "</table>";
 $filename='Approved_applications_report.xls';
 header ( "Content-type: application/vnd.ms-excel" );
 header ( "Content-Disposition: attachment; filename=".$filename);
 echo $header;
 
?>
