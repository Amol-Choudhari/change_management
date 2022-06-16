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
				<td align="center"><h4>FORM D2</h4></td>
			</tr>
		
		
	</table>
		
	
	<table width="100%" border="1">
		<tr>
		<td align="center" style="padding:5px;">
			<h4>Inspection Report for Approval of Laboratory</h4>
		</td>
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
			<td style="padding:10px; vertical-align:top;">1. Name of Laboratory :</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $firm_detail['firm_name']; ?></td>
		</tr>
		
		
		<tr>
			<td style="padding:10px; vertical-align:top;">2. Full postal address. (with contact details mobile No./Fax No. E-mail etc.) :</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $firm_detail['street_address'].','; ?><br>
														  <?php	echo $state_value.',';  
																echo $district_value.','; 
																echo $firm_detail['postal_code']; ?><br>
														<?php echo base64_decode($firm_detail['email']); //for email encoding ?><br>
														<?php echo base64_decode($firm_detail['mobile_no']); ?><br>
														<?php echo base64_decode($firm_detail['fax_no']); ?><br>
			</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">3. Type of laboretory :</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_type_value)){ echo $laboratory_type_value; }else{ echo 'NA'; }  ?></td>
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
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_type_value)){ echo $each_detail['d_name']; }else{ echo 'NA'; } ?></td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_type_value)){ echo $each_detail['d_address']; }else{ echo 'NA'; } ?></td>

						</tr>
					<?php $i=$i+1;} ?>
					
				</table>
			</td>
		</tr>
		
		
		<tr>
			<td style="padding:10px; vertical-align:top;">5. Name of commodities proposed to be graded :</td>
			<td style="padding:10px; vertical-align:top;"><?php foreach($sub_commodities_details as $sub_commodities_detail){ echo $sub_commodities_detail.',';} ?></td>
		</tr>

		<tr>
			<td style="padding:10px; vertical-align:top;">6. Date of Inspection :</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_report_detail[0]['inspection_date'])){ $inspection_date = explode(' ',$laboratory_report_detail[0]['inspection_date']); echo $inspection_date[0]; }else{ echo 'NA'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">7. Details of the size of the rooms of the laboratory :</td>
			<td style="padding:10px; vertical-align:top;">Plan No. <?php if(!empty($laboratory_report_detail[0]['laboratory_site_plan_no'])){ echo $laboratory_report_detail[0]['laboratory_site_plan_no']; }else{ echo 'NA'; } ?><br>
														  <?php if(!empty($laboratory_report_detail[0]['laboratory_site_plan_docs'])){ $split_file_path = explode("/",$laboratory_report_detail[0]['laboratory_site_plan_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>		
														  <a href="<?php echo $laboratory_report_detail[0]['laboratory_site_plan_docs']; ?>">
														  <?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">8. Whether the laboratory is having proper light/ ventilation arrangement, cemented flooring and drainage etc.? :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($laboratory_report_detail[0]['lab_surrounding_details']== 'yes'){ echo 'Yes'; }else{ echo 'No'; } ?></td>
		</tr>
	
	
		<tr>
			<td style="padding:10px; vertical-align:top;">9. Whether the laboratory exists in hygienic, pollution free and vibration free place? :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($laboratory_report_detail[0]['lab_environment_details']== 'yes'){ echo 'Yes'; }else{ echo 'No'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">10. Whether the laboratory is fully equipped for the analysis of the commodity/ies for which approval is sought? :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($laboratory_report_detail[0]['lab_environment_details']== 'yes'){ echo 'Yes'; ?><br>
														  <?php if(!empty($laboratory_report_detail[0]['is_lab_fully_equipped_doc'])){ $split_file_path = explode("/",$laboratory_report_detail[0]['is_lab_fully_equipped_doc']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>		
														  <a href="<?php echo $laboratory_report_detail[0]['is_lab_fully_equipped_doc']; ?>">
														  <?php echo substr($file_name,23); ?></a>	<?php }else{ echo 'NA'; }  ?>			
														 <?php } else{ echo 'No'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">11. Whether the arrangement exists in the laboratory for safe custody of records, Agmark Replica and  grading equipments etc? :</td>
			<td style="padding:10px; vertical-align:top;"><?php if($laboratory_report_detail[0]['laboretory_safety_records']== 'yes'){ echo 'Yes'; }else{ echo 'No'; } ?></td>
		</tr>
		</table>
		
	<table width="100%" border="1">
		<tr>
			<td style="padding:10px; vertical-align:top;">12. Nos. of the chemists working  in the laboratory (their names and qualification with experience may be specified)? :</td>
			<td style="padding:10px; vertical-align:top;">
					<table border="1">
							<tr>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Name of Chemist</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Qualification (Highest)</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Experience (In Years)</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Commodity</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Upload File<br>(Individual Chemist Details)</th>
							</tr>
							<?php 
							$i=1;
							foreach($laboratory_report_detail[1] as $chemist_detail){?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_detail['chemist_name'])){ echo $chemist_detail['chemist_name']; }else{ echo 'NA'; }  ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_detail['qualification'])){ echo $chemist_detail['qualification']; }else{ echo 'NA'; }  ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_detail['experience'])){ echo $chemist_detail['experience']; }else{ echo 'NA'; }  ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_report_detail[2][$i])){ echo $laboratory_report_detail[2][$i]; }else{ echo 'NA'; }  ?></td>
									<td><?php if($chemist_detail['chemists_details_docs'] != null){?>
										<br><?php $split_file_path = explode("/",$chemist_detail['chemists_details_docs']);
												$file_name = $split_file_path[count($split_file_path) - 1]; ?>
										<a href="<?php echo $chemist_detail['chemists_details_docs']; ?>"><?php echo substr($file_name,23); ?></a>
										<?php }else{ echo "No File Uploaded";} ?></td>
								</tr>
							<?php $i=$i+1; } ?>
							
					</table>
					<br>
					<?php if(!empty($laboratory_report_detail[0]['chemists_employed_docs'])){ $split_file_path = explode("/",$laboratory_report_detail[0]['chemists_employed_docs']);
						$file_name = $split_file_path[count($split_file_path) - 1];?>		
					<a href="<?php echo $laboratory_report_detail[0]['chemists_employed_docs']; ?>">
					<?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?>	
					
			</td>
		</tr>
		
		
		<tr>
			<td style="padding:10px; vertical-align:top;">13. Recommendations:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_report_detail[0]['recommendations'])){  echo $laboratory_report_detail[0]['recommendations']; }else{ echo 'NA'; } ?></td>
		</tr>
		
	</table>
	
	<!--Add the Digital/ E-signed by applicant (by pravin 23/05/2017)-->
			<!--<table align="right">	
					<tr>
						<td>E-signed By<td>
						
					<tr>
					<tr>
					<td><?php //echo $firm_detail['firm_name']; ?><br>
							<?php //echo $firm_detail['street_address'].','; ?>
							<?php //echo $state_value.',';  
								//echo $district_value.','; 
								//echo $firm_detail['postal_code'];  ?>
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
			
			
		<!--	<table width="35%" align="right">	
					<tr>
						<td>E-signed By<td>
						
					</tr>
					<tr>
						<td><?php //echo $user_full_name; ?></td>
					</tr>
			</table>
		-->
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	