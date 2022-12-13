<?php  ?>

	<style>
		h4 {
			padding: 5px;
			font-family: times;
			font-size: 13pt;					
		}
								 

		table{
			padding: 5px;
			font-size: 12pt;
			font-family: times;
		}
					
	</style>

	<?php
	/*$i=0;
	$sub_commodities_array = array();	
	foreach($sub_commodity_data as $sub_commodity){
		
		$sub_commodities_array[$i] = $sub_commodity['commodity_name'];
		$i=$i+1;
	} 
	
	$sub_commodities_list = implode(',',$sub_commodities_array);*/
	?>	
		
	<table width="100%" border="1">
		<tr>
		<td align="center" style="padding:5px;">
			<?php $spitId = explode('/',$customer_id); 
				if ($spitId[1]==1) {$certFor='Certificate of Authorisation';}
				elseif ($spitId[1]==2) {$certFor='Certificate of Printing Permission';}
				elseif ($spitId[1]==3) {$certFor='Certificate of Approval of Laboratory';}
			?>
			<h4>Application for Grant of Modification under <?php echo $certFor; ?></h4>
		</td>
		</tr>
	</table>


	<table width="100%">	
		<tr><td></td><br></tr>
		<tr>
			<td><br>To,</td><br>
		</tr>	
	</table>		

	<table  width="100%">

		<tr>
			<td><br>The Dy. Agri. Marketing Adviser/<br>
				Asstt. Agri. Marketing Adviser/<br>
				Senior Marketing Officer<br>
				Directorate of Marketing & Inspection<br>
				<?php echo $get_office['ro_office']; ?>
			</td>
		</tr>
		
		<tr>
			<td><br>Sir,</td><br>
		</tr>

			
		<tr>
			<td><br>I/We have carefully gone through the Agricultural Produce (Grading and Marking) Act, 1937, General Grading and Marking Rules, and the instructions issued by the Agricultural Marketing Adviser to the Government of India.<br>
				Following are the Changes/Modifications to be made under the Approved Grant Certificate.</td>
		</tr>
	
	</table>
	<br><br>
	<table width="100%" border="1">

		<tr>
			<td style="padding:10px; vertical-align:top;"><b>Section/Field to Change</b></td>
			<!--<td style="padding:10px; vertical-align:top;"><b>Last Details</b></td>-->
			<td style="padding:10px; vertical-align:top;"><b>New Details</b></td>
		</tr>
		
		<?php foreach ($getFieldName as $eachField) {?>
			<tr>
				<td style="padding:10px; vertical-align:top;"><?php echo $eachField['change_field']; ?></td>
				<!--<td style="padding:10px; vertical-align:top;"></td>-->
				<td style="padding:10px; vertical-align:top;">
					<?php if ($eachField['field_id']==1) {//if firm name changed
						echo $getChangeDetails['firm_name'];
					
					} elseif ($eachField['field_id']==2) {//if personal details changed
						echo $getChangeDetails['mobile_no'].'<br>';
						echo $getChangeDetails['email_id'].'<br>';
						echo $getChangeDetails['phone_no'];
					
					} elseif ($eachField['field_id']==3) {//if TBL details changed			
						$i=1;
						foreach ($changeTblDetails as $eachtbl) {
							echo $i.'. '.$eachtbl['tbl_name'].'<br>';
							
							$i++;
						}
						
					} elseif ($eachField['field_id']==4) {//if Directors details changed		
						$i=1;
						foreach ($changeDirectorDetails as $eachdirector) {
							echo $i.'. '.$eachdirector['d_name'].', '.$eachdirector['d_address'].'<br>';
							
							$i++;
						}
						
					} elseif ($eachField['field_id']==5) {//if premises changed				
						echo $change_premises;
						
					} elseif ($eachField['field_id']==6) {//if Grading lab details changed			
						echo 'Type: '.$change_lab_type.'<br>';
						echo 'Name: '.$getChangeDetails['lab_name'];
						
					} elseif ($eachField['field_id']==7) {//if Commodity changed					
						
						$i=1;	
						foreach($change_commodity_name_list as $commodity_name){ ?>
						
							<b><?php echo $i.'.'. $commodity_name['category_name']; ?></b>
							<ol>
								<?php 

									foreach($change_sub_commodity_data as $sub_commodity){ ?>
								
									<?php if($sub_commodity['category_code'] == $commodity_name['category_code']){?>
									
										<li><?php echo $sub_commodity['commodity_name']; ?></li>
										
									<?php } ?>
								
								<?php  } ?>
								
							</ol>
							
						<?php $i=$i+1; }
						
					} ?>
				</td>
			</tr>
		<?php } ?>
				
	</table>
	<br><br>
	<table>					
		<tr>
			<td  align="left">
				Place: <?php echo $firm_district_name.', '; echo $firm_state_name.'.';?><br>
				Date: <?php echo $pdf_date;?>
			</td>
		</tr>
	</table>
	
	<table align="right">	
			
		<tr>
			<td><?php echo $customer_firm_data['firm_name']; ?><br> 
				<?php echo $customer_firm_data['street_address'].', <br>';
						echo $firm_district_name.', ';
						echo $firm_state_name.', ';
						echo $customer_firm_data['postal_code'].'.<br>';?>
			</td>
		</tr>
	</table>

	