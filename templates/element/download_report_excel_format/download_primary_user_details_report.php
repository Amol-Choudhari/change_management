<?php
//functionality to manage header and all table rows in excel sheet date:18/12/2017//
$srno=1;

$header = "<table style='border: 5px solid black;'>";
for ($i=0; $i<sizeof($primary_user_details); $i++) {
	
	//$list_status1 = array('Srno','Date','Company ID','State','District');	

	$header .= "<tr styel='border: 2px solid black'>
  					<th >Srno</th>
  					<th styel='border: 2px solid black'>Date</th>
  					<th styel='border: 2px solid black'>Company ID</th>
  					<th styel='border: 2px solid black'>State</th>
  					<th styel='border: 2px solid black'>District</th>
  				</tr>";

				  	
	$p_ax = $primary_user_details[$i]['created'];
	$p_axx = $primary_user_details[$i]['customer_id'];
	$p_axxx = $all_states[$primary_user_details[$i]['state']];
	$p_aiv = $all_district[$primary_user_details[$i]['district']];
	
	$header .= "<tr>
				  <td styel='border: 1px solid black'>$srno</td>
				  <td styel='border: 1px solid black'>$p_ax</td>
				  <td styel='border: 1px solid black'>$p_axx</td>
				  <td styel='border: 1px solid black'>$p_axxx</td>
				  <td styel='border: 1px solid black'>$p_aiv</td>
			  </tr>";

	//$line = array($srno,$p_ax,$p_axx,$p_axxx,$p_aiv);
	
	//$list_status2 = array('Srno','Date','Company ID','Application Type','Firm/Primises ID','Firm/Primises Name','State','District');	
	
	$header .= "<tr styel='border: 2px solid black'>
  					<th >Srno</th>
  					<th styel='border: 2px solid black'>Date</th>
  					<th styel='border: 2px solid black'>Company ID</th>
  					<th styel='border: 2px solid black'>Application Type</th>
  					<th styel='border: 2px solid black'>Firm/Primises ID</th>
					<th styel='border: 2px solid black'>Firm/Primises Name</th>
					<th styel='border: 2px solid black'>State</th>
					<th styel='border: 2px solid black'>District</th>
  				</tr>";
	
	if(!empty($primary_firms_details[$i])){
		
		for ($j=0; $j<sizeof($primary_firms_details[$i]); $j++) {
			
			$sr_no = $j+1;
			$ax = $primary_firms_details[$i][$j]['created'];
			$axx = $primary_firms_details[$i][$j]['customer_primary_id'];
			$axxx = $application_type_array[$certification_type[$i][$j]];
			$aiv = $primary_firms_details[$i][$j]['customer_id'];
			$av	= $primary_firms_details[$i][$j]['firm_name'];
			$avi = $all_states[$primary_firms_details[$i][$j]['state']];
			$avii = $all_district[$primary_firms_details[$i][$j]['district']];

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
			
			//$line2 = array($sr_no,$ax,$axx,$axxx,$aiv,$av,$avi,$avii);
			
		}
		
	}else{
		$header .= "<tr>
				  		<td styel='border: 1px solid black' colspan='7'>No Firm Added</td>
			  		</tr>";
		//$line2 = array('No Firm Added');		
	}  
	
	$header .= "<tr>
				  		<td styel='border: 1px solid black' colspan='7'></td>
			  		</tr>";
	//$this->CSV->addRow($line3);
	
	$srno=$srno+1;
} 

 $header .= "</table>";
 $filename='Primay_User_Details_Report.xls';
 header ( "Content-type: application/vnd.ms-excel" );
 header ( "Content-Disposition: attachment; filename=".$filename);
 echo $header;
 
?>
