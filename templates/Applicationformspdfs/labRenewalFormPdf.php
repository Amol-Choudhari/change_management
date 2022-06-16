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
	
	<?php if($export_unit_status == 'yes'){ ?>
		
		<table width="100%" border="1">
			<tr>
				<td align="center"><h4>FORM C3</h4></td>
			</tr>
		</table>	
	
		<table width="100%" border="1">
			<tr>
				<td align="center" style="padding:5px;">
					<h4>Application for Renewal for Approval of Laboratory for Export</h4>
				</td>
			</tr>
		</table>
		
		<table width="100%" border="1" >
			<tr>
				<td>
					Applicant Id. <?php echo $customer_id;?>
				</td>
				<td align="right">
					Date: <?php echo $pdf_date;?>
				</td>
			</tr>
		</table>
			
		<table width="100%">
			<tr><td></td></tr>
			<tr>
				<td><br>To,</td><br>
			</tr>	
		</table>
						
		<table  width="100%">
			
			<tr>
				<td><br>The Dy. Agricultural Marketing Adviser<br>
					Asstt. Agricultural Marketing Adviser,<br>
					Incharge, Regional Office,<br>
					Directorate of Marketing & Inspection,<br>
					<?php echo $get_office['ro_office']; ?>,<?php echo $state_value; ?></td>
			</tr>

			<tr>	
				<td><br>Subject:  Renewal of approval for Grading and marking of fruits and Vegetables for Exports.</td>
			</tr>
				
			<tr>
				<td><br>Sir,</td><br>
			</tr>			
				
			<tr>
				<td><br>We have been approved for the grading and marking of Fruits and Vegetables, vide letter No. <?php echo $firm_detail['customer_id'];?> dated <?php if(!empty($last_grant_date)){ echo chop($last_grant_date,'00:00:00'); }else{ echo 'NA'; }  ?></td>						
			</tr>
			
			<tr>
				<td><br>We desire to continue grading and marking of fruits and vegetables for a further period of five years.</td>						
			</tr>		
			
		<!--	<tr>
				<td><br>We are furnishing the details of grading and marking carried out during last two years in the enclosed proforma (Annexure-II).</td>
			</tr>-->
			
			<tr>
				<td><br>A Online payment made on dated  <?php if(!empty($applicant_payment_detail['transaction_date'])){ $payment_date = explode(' ',$applicant_payment_detail['transaction_date']); echo $payment_date[0]; }else{ echo 'NA'; } ?> for Rs <?php if(!empty($total_charges)){ echo $total_charges; }else{ echo 'NA'; } ?> as a renewal fee.</td>
			</tr>
			
		</table>
	
	<?php }else{ ?>
	
		<table width="100%" border="1">
			<tr>
				<td align="center"><h4>FORM D3</h4></td>
			</tr>
		</table>	
	
		<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;">
				<h4>Application for Renewal for Approval of Commercial Laboratory</h4>
			</td>
			</tr>
		</table>
		
		<table width="100%" border="1" >
			<tr>
				<td>
					Applicant Id. <?php echo $customer_id;?>
				</td>
				<td align="right">
					Date: <?php echo $pdf_date;?>
				</td>
			</tr>
		</table>
		
		<table width="100%">
			<tr><td></td></tr>
			<tr>
				<td><br>To,</td><br>
			</tr>	
		</table>			
			
		<table  width="100%">
			
			<tr>
				<td><br>The Dy. Agricultural Marketing Adviser<br>
					Asstt. Agricultural Marketing Adviser,<br>
					Senior Marketing Officer<br>
					Directorate of Marketing & Inspection,<br>
					<?php echo $district_value['district_name']; ?>,<?php echo $state_value; ?></td>
			</tr>
			
			<tr>	
				<td><br>Subject:  Renewal for Approval of Commercial Laboratory</td>
			</tr>
				
			<tr>
				<td><br>Sir,</td><br>
			</tr>				
				
			<tr>
				<td><br>I/ We have been granted approval by the Directorate for my/our commercial laboratory M/s.<?php echo $firm_detail['firm_name']; ?>. for grading and marking of <?php 
															$i = 0;
															foreach($laboratory_commodity_values as $commodity_values){ ?>
															<?php echo $commodity_values.','; ?>
															<?php $i = $i+1;
															} ?> vide letter No. <?php echo $firm_detail['customer_id'];?> dated <?php if(!empty($last_grant_date)){ echo chop($last_grant_date,'00:00:00'); }else{ echo 'NA'; }  ?>.</td>
				
			</tr>
				
			<tr>
				<td><br>I/ We desire to continue grading and marking of  <?php 
															$i = 0;
															foreach($laboratory_commodity_values as $commodity_values){ ?>
															<?php echo $commodity_values.','; ?>
															<?php $i = $i+1;
															} ?>  for a further period of five years.</td>
				
			</tr>
				
			<tr>
				<td><br>I/ We  am/ are furnishing the details of grading and marking carried out during the last validity period in the prescribed Form D-4.</td>
			</tr>
				
			<tr>
				<td><br>A Online payment made on dated  <?php if(!empty($applicant_payment_detail['transaction_date'])){ $payment_date = explode(' ',$applicant_payment_detail['transaction_date']); echo $payment_date[0]; }else{ echo 'NA'; } ?> for Rs <?php if(!empty($total_charges)){ echo $total_charges; }else{ echo 'NA'; } ?> as a renewal fee.</td>
			</tr>
			
		</table>
	
	<?php } ?>			
			
	<?php //if($show_esigned_by == 'yes'){ ?>
		<table align="right">	
			<tr><td></td></tr>
			<tr>
			<td><?php echo $firm_detail['firm_name']; ?><br>
					<?php echo $firm_detail['street_address'].', <br>';
						  echo $district_value['district_name'].', ';
						  echo $state_value.', ';
						  echo $firm_detail['postal_code']; ?>
			</td>
			</tr>
		</table>	
	<?php //} ?>

<!-- commented the code to display extra details from renewal application, as no form will be filled by applicant in renewal application
as per new order, applied on 19-10-2021 by Amol-->			
<!--	<br pagebreak="true" />
	
		<table width="100%" border="1">			
			<tr>
				<?php /* if($export_unit_status=='yes'){ ?>
					<td align="center"><h4>FORM C4</h4></td>
				<?php }else{ ?>
					<td align="center"><h4>FORM D4</h4></td>
				<?php } ?>
			</tr>
		</table>
			
		<table  width="100%" border="1">
		
			<tr>
				<td style="padding:10px; vertical-align:top;">1.(a) Name of Of the laboratory :</td>
				<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_detail['firm_name'])){ echo $firm_detail['firm_name']; }else{ echo 'NA'; }  ?></td>
			</tr>
			
			
			<tr>
				<td style="padding:10px; vertical-align:top;">2. Mailing Address with contact details i.e. Mobile No., e-mail etc.</td>
				<td style="padding:10px; vertical-align:top;">
					<?php echo $firm_detail['street_address'].', ';
							echo $district_value['district_name'].', ';
							echo $state_value.', ';
							if(!empty($firm_detail['postal_code'])){ echo $firm_detail['postal_code']; }else{ echo 'NA'; } ?><br>
					<?php	if(!empty($firm_detail['email'])){ echo $firm_detail['email']; }else{ echo 'NA'; } ?><br>
					<?php	if(!empty($firm_detail['mobile_no'])){ echo base64_decode($firm_detail['mobile_no']);	}else{ echo 'NA'; } ?><br>
				</td>
			</tr>
				
			<tr>
				<td style="padding:10px; vertical-align:top;">3. Commodities List:</td>
				<td style="padding:10px; vertical-align:top;"><?php $i = 0;
															foreach($laboratory_commodity_values as $commodity_values){ 
															 if(!empty($commodity_values)){ echo $commodity_values.','; }else{ echo 'NA'; }
															 $i = $i+1; } ?> </td>
			</tr>	
			
		</table>
		
		<table  width="100%" border="1">
			<tr>
					<td style="padding:10px; vertical-align:top;">4.(a)Chemists Details :</td>
					<td style="padding:10px; vertical-align:top;">
						<table width="100%" border="1">
							<tr>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Name of Chemist</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Qualification (Highest)</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Experience (In Years)</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Commodity</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Upload File<br>(Individual Chemist Details)</th>
								
							</tr>
							<?php 
							$i=1;
							foreach($chemist_details as $chemist_detail){?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_detail['chemist_name'])){ echo $chemist_detail['chemist_name']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_detail['qualification'])){ echo $chemist_detail['qualification']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_detail['experience'])){ echo $chemist_detail['experience']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_commodity_value[$i])){ echo $chemist_commodity_value[$i]; }else{ echo 'NA'; } ?></td>
									<td><?php if($chemist_detail['chemists_details_docs'] != null){?>
									<br><?php $split_file_path = explode("/",$chemist_detail['chemists_details_docs']);
											$file_name = $split_file_path[count($split_file_path) - 1]; ?>
									<a href="<?php echo $chemist_detail['chemists_details_docs']; ?>"><?php echo substr($file_name,23); ?></a>
									<?php }else{ echo "No File Uploaded";} ?></td>
								</tr>
							<?php $i=$i+1; } ?>
						
						</table>
						
					</td>
			</tr>
			
			
			<tr>
				<td style="padding:10px; vertical-align:top;">4.(b)Relevent Document :</td>
				<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_fields_result['chemist_detail_docs'])){ $split_file_path = explode("/",$check_fields_result['chemist_detail_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1]; ?>
								<a href="<?php echo $check_fields_result['chemist_detail_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?></td>
			</tr>
	
		</table>	

		<table  width="100%" border="1">
		
			<tr>
					<td style="padding:10px; vertical-align:top;">5. Details of Authorized Packers:</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_fields_result['authorized_packers_docs'])){ $split_file_path = explode("/",$check_fields_result['authorized_packers_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1]; ?>
								<a href="<?php echo $check_fields_result['authorized_packers_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?></td>
			</tr>	
			
			
			<tr>
					<td style="padding:10px; vertical-align:top;">6. No. of lots Graded:</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_fields_result['lots_graded_docs'])){ $split_file_path = explode("/",$check_fields_result['lots_graded_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1]; ?>
								<a href="<?php echo $check_fields_result['lots_graded_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?></td>
			</tr>	
			
			
			<tr>
					<td style="padding:10px; vertical-align:top;">7. Quantity Graded:</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_fields_result['quantity_graded_docs'])){ $split_file_path = explode("/",$check_fields_result['quantity_graded_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1]; ?>
								<a href="<?php echo $check_fields_result['quantity_graded_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?></td>
			</tr>	
			
			
			<tr>
					<td style="padding:10px; vertical-align:top;">8. Misgraded Check Sample:</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_fields_result['check_Sample_docs'])){ $split_file_path = explode("/",$check_fields_result['check_Sample_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1]; ?>
								<a href="<?php echo $check_fields_result['check_Sample_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?></td>
			</tr>	
			
			<tr>
					<td style="padding:10px; vertical-align:top;">9. Any Warning Issued:</td>
					<td style="padding:10px; vertical-align:top;"><?php if($check_fields_result['is_warning_issued'] == 'yes'){ echo $check_fields_result['warning_details']; }else{echo 'No';} ?></td>
			</tr>	
			
		</table>
		
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

		<?php //if($show_esigned_by == 'yes'){ ?>
			<table align="right">	
					<tr><td></td></tr>
					<tr>
					<td><?php echo $firm_detail['firm_name']; ?><br>
							<?php echo $firm_detail['street_address'].', <br>';
								  echo $district_value['district_name'].', ';
								  echo $state_value.', ';
								  if(!empty($firm_detail['postal_code'])){ echo $firm_detail['postal_code'];  }else{ echo 'NA'; } ?>
					</td>
					</tr>
			</table>
		<?php //}*/ ?>
	-->