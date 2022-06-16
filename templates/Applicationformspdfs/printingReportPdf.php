<?php ?>

<style>
	h4 {
		padding: 5px;
		font-family: times;
		font-size: 12pt;
	}						 

	table{
		padding: 5px;
		font-size: 10pt;
		font-family: times;
	}				
</style>

	
	<table width="100%" border="1">
	
		
			<tr>				
				<td align="center"><h4>FORM B3</h4></td>
			</tr>
		
		
	</table>
	
	
	<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;"><h4>Inspection Report for Grant of Permission to Printing Press</h4></td>
			</tr>
	</table>


	<table width="100%" >
		<tr>
			<td>
				Applicant Id. <?php echo $customer_id?>
			</td>
			<td align="right">
				Date: <?php echo $pdf_date; ?>
			</td>
		</tr>
	</table>

	<table width="100%" border="1">
	
		<tr>
			<td style="padding:10px; vertical-align:top;">1. Name of printing Press :</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $firm_profile_detail[0]['firm_name']; ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">2. Full postal address. (with contact details mobile No./Fax No. E-mail etc.)<br>a. Registered Office<br>b. Printing Press Premises Address</td>
			<td style="padding:10px; vertical-align:top;"><b>Registered Office</b><br><?php echo $firm_profile_detail[0]['street_address'].','; ?><br>
														 <?php	echo $firm_state_value.',';  
																echo $firm_district_value.','; 
																echo $firm_profile_detail[0]['postal_code']; ?><br>
														<?php	if(!empty($firm_profile_detail[0]['firm_email_id'])){ echo base64_decode($firm_profile_detail[0]['firm_email_id']); }else{ echo 'NA'; } //for email encoding ?><br>
														<?php	if(!empty($firm_profile_detail[0]['firm_mobile_no'])){ echo base64_decode($firm_profile_detail[0]['firm_mobile_no']); }else{ echo 'NA'; }  ?><br>
														<?php	if(!empty($firm_profile_detail[0]['firm_fax_no'])){ echo base64_decode($firm_profile_detail[0]['firm_fax_no']);	}  ?><br>
														<b>Printing Press Premises Address</b><br><?php if(!empty($premises_profile_detail[0]['street_address'])){ echo $premises_profile_detail[0]['street_address'].','; }else{ echo 'NA'; }  ?><br>
														 <?php	if(!empty($premises_state_value)){ echo $premises_state_value.',';  }else{ echo 'NA'; }  
																if(!empty($premises_district_value)){ echo $premises_district_value.','; }else{ echo 'NA'; } 
																if(!empty($premises_profile_detail[0]['postal_code'])){ echo $premises_profile_detail[0]['postal_code']; }else{ echo 'NA'; } ?><br></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">3. Status of the printing press:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($business_type_value['business_type'])){ echo $business_type_value['business_type']; }else{ echo 'NA'; } ?></td>
		</tr>
		
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4. Details of Director/Partner/Proprietor/Owner</td>
			<td style="padding:10px; vertical-align:top;">
				<table width="100%" border="1">
					<tr>
						<th style="padding:10px;" width="25%" cellspacing="50" align="left">S.No.</th>
						<th style="padding:10px;" width="25%" cellspacing="50" align="left">Name</th>
						<th style="padding:10px;" width="25%" cellspacing="50" align="left">Address</th>
					</tr>
					<?php $i=1; foreach($added_directors_details as $each_detail){?>
						<tr>
							<td style="padding:10px; vertical-align:top;"><?php echo $i; ?></td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_name'])){ echo $each_detail['d_name']; }else{ echo 'NA'; } ?></td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_address'])){ echo $each_detail['d_address']; }else{ echo 'NA'; } ?></td>

						</tr>
					<?php $i=$i+1;} ?>
					
				</table>
			</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">5. Whether the printing press is assessed for the purpose of Income Tax. If yes give GST No.:</td>
			<td style="padding:10px; vertical-align:top;">  <?php if($printing_report_detail[0]['is_assessed_for'] == 'yes'){ ?>
															
															<b>GST NO :</b>
															<?php if(!empty($printing_premises_profile['gst_no'])){ echo $printing_premises_profile['gst_no']; }else{ echo 'NA'; } ?>
			<?php } else{ echo 'No'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">6. Period for which the press is in the printing business :</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($business_years)){ echo $business_years; }else{ echo 'NA'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">7. Whether the press has been earlier permitted to print Agmark Replica? If yes, reason for withdrawal of permission :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($printing_report_detail[0]['earlier_permitted'] == 'yes'){ ?>
																	<?php if(!empty($printing_report_detail[0]['reason_of_withdrawal'])){ echo $printing_report_detail[0]['reason_of_withdrawal']; }else{ echo 'NA'; } ?>
		<?php } else{ echo 'No'; } ?></td>
		</tr>											 
	
		<tr>
			<td style="padding:10px; vertical-align:top;">8. Whether the printing press is having the requisite machineries for the printing of Agmark replica (give details with number and capacity) :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($printing_report_detail[0]['machines_requisite'] == 'yes'){ ?>
			
																	<?php echo 'Yes';?><br>
																	<?php if(!empty($printing_report_detail[0]['machines_requisite_details'])){ echo $printing_report_detail[0]['machines_requisite_details'];  }else{ echo 'NA'; } ?><br>
																	
														<?php } else { ?>
														
																	<?php if(!empty($printing_report_detail[0]['machines_requisite_docs'])){ $split_file_path = explode("/",$printing_report_detail[0]['machines_requisite_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																	
																	<a href="<?php echo $printing_report_detail[0]['machines_requisite_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?>
														<?php } ?></td>
																
			
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">9. Whether proper in – house storage facilities exists for security and safe custody of printing and printed material :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($printing_report_detail[0]['in_house_storage_facility'] == 'yes'){ echo 'Yes'; ?>
															<?php } else { echo 'NO'; }?></td>
		</tr>								
		
		<tr>
			<td style="padding:10px; vertical-align:top;">10. Whether the printing press maintains proper account for printing orders received, executed and invoice records etc :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($printing_report_detail[0]['account_maintained'] == 'yes'){ echo 'Yes'; ?>
															<?php } else { echo 'NO'; }?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">11. In case of printing on tin containers whether fabrication facilities are available. If not, give the details of tie up arrangement :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($printing_report_detail[0]['fabrication_facility'] == 'yes'){ echo 'Yes'; ?>
															<?php } else { ?>
															<?php if(!empty($printing_report_detail[0]['fabrication_facility_docs'])){ $split_file_path = explode("/",$printing_report_detail[0]['fabrication_facility_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																	
																	<a href="<?php echo $printing_report_detail[0]['fabrication_facility_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?>
															<?php } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">12. Whether the press has given declaration about use of right quality of printing ink and use of food grade packaging material :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($printing_report_detail[0]['declaration_given'] == 'yes'){ echo 'Yes'; ?>
															<?php } else { echo 'NO'; }?><br>
															<?php if(!empty($printing_report_detail[0]['ink_declaration_docs'])){ $split_file_path = explode("/",$printing_report_detail[0]['ink_declaration_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																	
																	<a href="<?php echo $printing_report_detail[0]['ink_declaration_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?>
															</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">13. Whether the press has been sponsored by the authorized packers.   If so, mention the details of authorized  packers with CA Nos. :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($printing_report_detail[0]['is_press_sponsored'] == 'yes'){ ?>
			
																<?php if(!empty($printing_report_detail[0]['press_sponsored_docs'])){ $split_file_path = explode("/",$printing_report_detail[0]['press_sponsored_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																	<a href="<?php echo $printing_report_detail[0]['press_sponsored_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?>
																	
															<?php } elseif($printing_report_detail[0]['is_press_authorised'] == 'yes'){ ?>
																	
																	<?php if(!empty($printing_report_detail[0]['press_sponsored_docs'])){ $split_file_path = explode("/",$printing_report_detail[0]['press_sponsored_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																	<a href="<?php echo $printing_report_detail[0]['press_sponsored_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?>
																	
															<?php } else { echo 'NO';} ?></td>
		</tr>
															
		<tr>												
			<td style="padding:10px; vertical-align:top;">14. Remarks, if any :</td>
			<td style="padding:10px; vertical-align:top;"><?php	if(!empty($printing_report_detail[0]['any_other_point'])){ echo $printing_report_detail[0]['any_other_point']; }else{ echo 'NA'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">15. Recommendations :</td>
			<td style="padding:10px; vertical-align:top;"><?php	if(!empty($printing_report_detail[0]['recommendations'])){ echo $printing_report_detail[0]['recommendations']; }else{ echo 'NA'; } ?></td>
		</tr>
	</table>
	
	
	<!--Add the Digital/ E-signed by applicant (by pravin 23/05/2017)-->
			<!--<table align="right">	
					<tr>
						<td>E-signed By<td>
						
					<tr>
					<tr>
					<td><?php //echo $firm_profile_detail[0]['firm_name']; ?><br>
							<?php //echo $firm_profile_detail[0]['street_address'].','; ?>
							<?php //echo $firm_state_value['Dmi_state']['state_name'].',';  
								//echo $firm_district_value['Dmi_district']['district_name'].','; 
								//echo $firm_profile_detail[0]['postal_code'];  ?>
					</td>
					<tr>
			</table>-->
			
		
		
		<table>
					<tr>
						<td align="left">
							<h4>I hereby declare that the above information is correct.</h4>
						</td>
					</tr>
			</table>
			
			<table>					
					<tr>
						<td  align="left">
							Date: <?php echo $pdf_date;?>
						</td>
					</tr>
			</table>
			
			
			<!--<table width="35%" align="right">	
					<tr>
						<td>E-signed By<td>
						
					</tr>
					
					<tr>
						<td><?php //echo $user_full_name;?></td>
					</tr>
			</table>-->