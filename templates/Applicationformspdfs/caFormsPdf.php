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

	<!-- Below new table for FORM A added on 22-08-2017 by Amol -->
	
	<table width="100%" border="1">
	
		
			<tr>				
				<td align="center"><h4>
					<?php if($ca_bevo_applicant == 'no'){?>
						<?php if($export_unit_status == 'yes'){?>FORM F
						<?php }else{ ?>FORM A<?php } ?>
					<?php }elseif($ca_bevo_applicant == 'yes'){?>FORM E <?php } ?>
					</h4>
				</td>
			</tr>
		
		
	</table>
	
	
	
	<?php
	$i=0;
	$sub_commodities_array = array();	
	foreach($sub_commodity_data as $sub_commodity){
		
		$sub_commodities_array[$i] = $sub_commodity['commodity_name'];
		$i=$i+1;
	} 
	
	$sub_commodities_list = implode(',',$sub_commodities_array);
	?>
	
	
	<?php if($ca_bevo_applicant == 'no'){?>	

		<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;">
			
				<?php if($export_unit_status == 'yes'){?>
					<h4>Application for Grant of Certificate of Authorization for Grading and Marking of <?php echo $sub_commodities_list;?> for Export: </h4>
				<?php }else{ ?>
					<h4>Application for Grant of Certificate of Authorization for Grading and Marking of <?php echo $sub_commodities_list;?> for DOMESTIC MARKET</h4>
				<?php }?>
			
			</td>
			</tr>
		</table>

	<?php }elseif($ca_bevo_applicant == 'yes'){?>		
		
		<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;">
				<h4>Application for grant of Certificate of Authorisation for grading and marking of Blended Edible Vegetable Oils/Fat Spread</h4>
			</td>
			</tr>
		</table>

	<?php } ?>	
	
	
	<table width="100%">	
		<tr><td></td><br></tr>
		<tr>
			<td><br>To,</td><br>
		</tr>	
	</table>		

	<table  width="100%">

		<tr><!-- removed the Bevo condition as all CA appl now grant by RO office not HO, on 02-12-2021-->
			<td><br>The Dy. Agri. Marketing Adviser/<br>
				Asstt. Agri. Marketing Adviser/<br>
				Senior Marketing Officer<br>
				Directorate of Marketing & Inspection<br>
				<!--<?php //echo $get_office['ro_office']; ?>,<?php //echo $firm_state_name; ?> on 23-05-2022 as conflict in sponsored PP appl -->
				<?php echo $get_office['ro_office']; ?>
			</td>
		</tr>
		
		<tr>
			<td><br>Sir,</td><br>
		</tr>
		<?php if($ca_bevo_applicant == 'no'){?>
			<tr>
				<td><br>I/We <?php echo $customer_firm_data['firm_name']; ?> of M/s <?php echo $customer_firm_data['street_address'].', '; echo $firm_district_name.', '; echo $firm_state_name.', '; echo $customer_firm_data['postal_code']; ?> being desirous of marking <?php echo $sub_commodities_list;?> with a grade designation mark in accordance with the rules made under the provisions of Agricultural Produce (Grading & Marking) Act, 1937, hereby, request for grant of Certificate of Authorisation.</td>
			</tr>
			
			<tr>
				<td><br>I/We have carefully gone through the provisions of Agricultural Produce (Grading & Marking) Act, 1937, the General Grading & Marking Rules 1988, relevant Commodity Grading & Marking Rules and the instructions issued by the Agricultural Marketing Adviser to the Govt. of India or an Officer authorised by him in this regard for grading & marking of the said commodity and agree to abide by them.</td>
			</tr>
			
			<tr>
				<td><br>The requisite particulars are furnished herewith in the prescribed Form-A-1 and the requisite documents are enclosed.</td>
			</tr>
		
		<?php }elseif($ca_bevo_applicant == 'yes'){?>	
		
			<tr>
				<td><br>I/We <?php echo $customer_firm_data['firm_name']; ?> of M/s <?php echo $customer_firm_data['street_address'].', '; echo $firm_district_name.', '; echo $firm_state_name.', '; echo $customer_firm_data['postal_code']; ?> being desirous of grading and marking Blended Edible Vegetable Oils/Fat Spread with grade designation marks in accordance with the Rules made under Agricultural Produce (Grading and Marketing) Act, 1937, hereby request for grant of Certificate of Authorisation.</td>
			</tr>
			
			<tr>
				<td><br>I/We have carefully gone through the Agricultural Produce (Grading and Marking) Act, 1937, General Grading and Marking Rules, 1988 and the Blended Edible Vegetable Oils Grading and Marking Rules, 1991/Fat Spread Grading & Marking Rules 1994, and the instructions issued by the Agricultural Marketing Adviser to the Government of India, in connection with the grading and marking of Blended Edible Vegetable Oils/Fat Spread and agree to abide by them, as  well as such rules and instructions, as may be issued from time to time.</td>
			</tr>
		
		<?php } ?>
					
	</table>
	
	<table>					
			<tr>
				<td  align="left">
					Place: <?php echo $firm_district_name.', '; echo $firm_state_name.'.';?><br>
					Date: <?php echo $pdf_date;?>
				</td>
			</tr>
	</table>
	
	<?php //if($show_esigned_by=='yes'){ ?><!-- Condition added on 27-03-2018 by Amol -->
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
	<?php //}?>		
			
<!-- FORM A portion end here -->			
	
<br pagebreak="true" />	
	
<!-- FORM A1 portion Starts here -->	
	
	<table width="100%" border="1">
	
		
			<tr>
			
				<td align="center"><h4>
					<?php if($ca_bevo_applicant == 'no'){?>
						<?php if($export_unit_status == 'yes'){?>FORM F1
						<?php }else{ ?>FORM A1<?php } ?>
					<?php }elseif($ca_bevo_applicant == 'yes'){?>FORM E1<?php } ?>
				</h4></td>
				<!--	<td>
						<img src="../img/logos/emblem.png">
						</td>
						<td align="center">
							<h2>Directorate of Marketing & Inspection</h2>
							<h3>Ministry of Agriculture and Farmers Welfare <br>Government of India</h3>
						</td>
						<td>
						<img src="../img/logos/agmarklogo.png">
						</td>
				-->	
			</tr>
		
	
	</table>
	
	
	
	<?php if($ca_bevo_applicant == 'no'){?>	

		<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;">
			
				<?php if($export_unit_status == 'yes'){?>
					<h4>Application for Grant of New Certificate of Authorisation for Grading & Marking of Agricultural Commodities Under Agmark for Export: </h4>
				<?php }else{ ?>
					<h4>Application for Grant of New Certificate of Authorisation for Grading & Marking of Agricultural Commodities Under Agmark for Internal Trade: </h4>
				<?php }?>
			
			</td>
			</tr>
		</table>

	<?php }elseif($ca_bevo_applicant == 'yes'){?>		
		
		<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;">
				<h4>Application for Grant of New Certificate of Authorisation for Grading & Marking of Blended Edible Vegetable Oils/Fat Spread: </h4>
			</td>
			</tr>
		</table>
		
		
		
	<?php } ?>	
		
		
		<table width="100%" >
			<tr>
				<td>
					Applicant Id. <?php echo $customer_id;?>
				</td>
				<td align="right">
					Date: <?php echo $pdf_date;?>
				</td>
			</tr>
		</table>
		
		
		
			
		<table width="100%" border="1">
			
			<?php if($ca_bevo_applicant == 'no'){ ?>	
				
				<tr>
					<td style="padding:10px; vertical-align:top;">1.Name and Postal address of the Firm, Contact details (mobile no, Fax, e-mail etc.)</td>
					<td style="padding:10px; vertical-align:top;"><?php echo $customer_firm_data['firm_name']; ?> <br>
						<?php echo $customer_firm_data['street_address'].', ';
								echo $firm_district_name.', ';
								echo $firm_state_name.', ';
								echo $customer_firm_data['postal_code'].'.<br>';
								echo 'Email: '.base64_decode($customer_firm_data['email']).',<br>'; //for email encoding
								echo 'Phone: '.base64_decode($customer_firm_data['mobile_no']).',<br>';
								if(!empty($customer_firm_data['fax_no'])){ echo 'Landline: '. base64_decode($customer_firm_data['fax_no']); } 
								
						?>
					</td>
				</tr>


				<tr>
					<td style="padding:10px; vertical-align:top;">2. Complete address of the grading premises where grading and marking will be carried out.</td>
					<td style="padding:10px; vertical-align:top;">
						<?php echo $premises_data['street_address'].', ';
								echo $premises_district_name.', ';
								echo $premises_state_name.', ';
								echo $premises_data['postal_code'];
		
						?>
					</td>
				</tr>
					
					
				<tr>
					<td style="padding:10px; vertical-align:top;">3. Name of Commodities proposed to be Graded:</td>
					<td style="padding:10px; vertical-align:top;">
						<?php

						$i=1;	
						foreach($commodity_name_list as $commodity_name){ ?>
						
							<b><?php echo $i.'.'. $commodity_name['category_name']; ?></b>
							<ol>
								<?php 

									foreach($sub_commodity_data as $sub_commodity){ ?>
								
									<?php if($sub_commodity['category_code'] == $commodity_name['category_code']){?>
									
										<li><?php echo $sub_commodity['commodity_name']; ?></li>
										
									<?php } ?>
								
								<?php  } ?>
								
							</ol>
							
						<?php $i=$i+1; } ?>
					</td>
				</tr>
					
					
					
				<tr>
					<td style="padding:10px; vertical-align:top;">4. Status of the firm i.e. Proprietary/ Partnership/ Private Ltd.,/ Public Ltd. Registered Society/ Public undertaking etc. (copy of relevant document to be enclosed).</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($business_type)){ echo $business_type; }else{ echo 'NA'; } ?><br /> 
					
					<?php if(!empty($firm_data['business_type_docs'])){ $split_file_path = explode("/",$firm_data['business_type_docs']);
					$file_name = $split_file_path[count($split_file_path) - 1];
					?>
					Provided Docs: <a href="<?php echo $firm_data['business_type_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
				</tr>
					
				<!--commented on 11-08-2022, as suggested after UAT phase II-->
				<!--
					<tr>
						<td style="padding:10px; vertical-align:top;">5. Period for which the applicant has been in the business:</td>
						<td style="padding:10px; vertical-align:top;"><?php //if(!empty($business_years_value)){ echo $business_years_value; }else{ echo 'NA'; }  ?></td>
					</tr>
				-->
	
	
				<!-- Hide the "Have Registration/License" Radio option (Done By Pravin 02-02-2018) -->					
				<!--<tr>
					<td style="padding:10px; vertical-align:top;">6. Registration/ License No. issued under the FSSAI Act, 2006 in case of food commodities:</td>
					<td style="padding:10px; vertical-align:top;"><?php //echo $firm_data['have_reg_no']; ?></td>
				</tr>-->
					
				<?php if($firm_data['have_reg_no'] == 'yes'){?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">5(a). Registration/ License No. issued under the FSSAI Act, 2006 in case of food commodities:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_data['fssai_reg_no'])){ echo $firm_data['fssai_reg_no']; }else{ echo 'NA'; }  ?></td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">5(b). FSSAI Relevant Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_data['fssai_reg_docs'])){ $split_file_path = explode("/",$firm_data['fssai_reg_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $firm_data['fssai_reg_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					
				<?php } ?>
					

				<tr>
					<td style="padding:10px; vertical-align:top;">6. Details of the machinery/ packing machines/ storage tanks/ Cold Storage etc.  available in the plant/ premises with their capacity.</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($machinery_data['have_details'])){ echo ucfirst($machinery_data['have_details']); }else{ echo 'NA'; } ?>
					</td>
				</tr>
					

				<?php if($machinery_data['have_details'] == 'yes'){?>

					<tr>
						<td style="padding:10px; vertical-align:top;">6(a). All Machinery Details:</td>
						<td style="padding:10px; vertical-align:top;">
							<table width="100%" border="1">
								<tr>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Name</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Type</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">No.</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Capacity</th>
								</tr>
								<?php 
								$i=0;
								foreach($all_machine_details as $each_machine){?>
									<tr>
										<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_machine['machine_name'])){ echo $each_machine['machine_name']; }else{ echo 'NA'; }  ?></td>
										<td style="padding:10px; vertical-align:top;"><?php if(!empty($machine_type_value[$i])){ echo $machine_type_value[$i]; }else{ echo 'NA'; } ?></td>
										<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_machine['machine_no'])){ echo $each_machine['machine_no']; }else{ echo 'NA'; }  ?></td>
										<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_machine['machine_capacity'])){ echo $each_machine['machine_capacity']; }else{ echo 'NA'; }  ?></td>
									</tr>
								<?php 
								$i=$i+1;} ?>
								
							</table>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">6(b). Machinery Details Docs:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($machinery_data['detail_docs'])){ $split_file_path = explode("/",$machinery_data['detail_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $machinery_data['detail_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
																
					</tr>
				
				<?php } ?>
					
					
					
					
				<tr>
					<td style="padding:10px; vertical-align:top;">7. Is the manufacturing unit owned by the applicant?</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($machinery_data['owned_by_applicant'])){ echo ucfirst($machinery_data['owned_by_applicant']); }else{ echo 'NA'; } ?>
					</td>
				</tr>
					
				<?php if($machinery_data['owned_by_applicant'] == 'no'){?>
				
					<tr>
						<td style="padding:10px; vertical-align:top;">7(a). Name & Address of Approved Unit</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($machinery_data['unit_name_address'])){ echo $machinery_data['unit_name_address']; }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">7(b). Manufacturing Unit Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($machinery_data['unit_related_docs'])){ $split_file_path = explode("/",$machinery_data['unit_related_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $machinery_data['unit_related_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
				
				<?php } ?>


					
					
				<tr>
					<td style="padding:10px; vertical-align:top;">8. Whether it is proposed to re-pack the Graded product for large container to smaller packages.?</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($packing_data['proposed_to_repack'])){ echo ucfirst($packing_data['proposed_to_repack']); }else{ echo 'NA'; } ?>
					</td>
				</tr>
					
				<?php if($packing_data['proposed_to_repack'] == 'yes'){?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">8(a). Address of the re-packing premises:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($packing_data['proposed_place'])){ echo $packing_data['proposed_place']; }else{ echo 'NA'; } ?></td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">8(b). Re-Packing Related Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php  if(!empty($packing_data['repacking_docs'])){ $split_file_path = explode("/",$packing_data['repacking_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php  echo $packing_data['repacking_docs']; ?>"><?php  echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>

				<?php } ?>
					
					
				
					
				<tr>
					<td style="padding:10px; vertical-align:top;">9. Any other information relevant to Grading of commodity?</td>
					<td style="padding:10px; vertical-align:top;"><?php  if(!empty($packing_data['have_grading_other_info'])){ echo ucfirst($packing_data['have_grading_other_info']); }else{ echo 'NA'; } ?>
					</td>
				</tr>
					
				<?php if($packing_data['have_grading_other_info'] == 'yes'){?>

					<tr>
						<td style="padding:10px; vertical-align:top;">9(a). Grading Other Information:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($packing_data['grading_other_info'])){ echo $packing_data['grading_other_info']; }else{ echo 'NA'; } ?></td>
					</tr>

				<?php } ?>

			

				
				<?php if($form_type != 'F') { #This condition is applied for the changes on CA EXPORT - Akash [07-09-2022] ?>

					<tr>
						<td style="padding:10px; vertical-align:top;">10. Specify type of the Laboratory through which grading and marking is proposed to be undertaken i.e. Own laboratory/ State Grading Laboratory/ Commercial Laboratory (Consent letter of the laboratory may be enclosed, not required in case of own laboratory.)</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_type_name)){ echo $laboratory_type_name; }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
					<?php if($laboratory_type_name == 'Own Laboratory'){?>
					
						<!-- Add By Pravin 22-07-2017 -->
						<tr>
							<td style="padding:10px; vertical-align:top;">10(a). Chemist Details:</td>
							<td style="padding:10px; vertical-align:top;"><?php  if(!empty($laboratory_data['chemist_detail_docs'])){ $split_file_path = explode("/",$laboratory_data['chemist_detail_docs']);
																		$file_name = $split_file_path[count($split_file_path) - 1];?>
																	<a href="<?php  echo $laboratory_data['chemist_detail_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
						</tr>
						
						<tr>
							<td style="padding:10px; vertical-align:top;">10(b). List of equipments, Glasswares & chemicals duly signed & stamp by the applicant:</td>
							<td style="padding:10px; vertical-align:top;"><?php  if(!empty($laboratory_data['lab_equipped_docs'])){ $split_file_path = explode("/",$laboratory_data['lab_equipped_docs']);
																		$file_name = $split_file_path[count($split_file_path) - 1];?>
																	<a href="<?php  echo $laboratory_data['lab_equipped_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
						</tr>
					
					<?php }else{ ?>

						<tr>
							<td style="padding:10px; vertical-align:top;">10(a). Consent letter Document:</td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_data['consent_letter_docs'])){ $split_file_path = explode("/",$laboratory_data['consent_letter_docs']);
																		$file_name = $split_file_path[count($split_file_path) - 1];?>
																	<a href="<?php echo $laboratory_data['consent_letter_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
						</tr>

					<?php } ?>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">11. Name and address of the approved laboratory.</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_data['street_address'])){ echo $laboratory_data['street_address'].', '; }else{ echo 'NA'; } 
																			if(!empty($laboratory_district_name)){	echo $laboratory_district_name.', '; }else{ echo 'NA'; }
																			if(!empty($laboratory_state_name)){	echo $laboratory_state_name.', '; }else{ echo 'NA'; }
																			if(!empty($laboratory_data['postal_code'])){	echo $laboratory_data['postal_code'].'.<br>'; }else{ echo 'NA'; }
																			if(!empty($laboratory_data['lab_email_id'])){	echo 'Email: '.base64_decode($laboratory_data['lab_email_id']).',<br>'; }else{ echo 'NA'; } //for email encoding
																			if(!empty($laboratory_data['lab_mobile_no'])){	echo 'Phone: '.base64_decode($laboratory_data['lab_mobile_no']).',<br>'; }else{ echo 'NA'; }
																			if(!empty($laboratory_data['lab_fax_no'])){	echo 'Landline: '.base64_decode($laboratory_data['lab_fax_no']); }else{ echo 'NA'; } ?>
						</td>
					</tr>
				
				<?php } ?>
					
					
				<?php if($form_type != 'F') { #This condition is applied for the changes on CA EXPORT - Akash [07-09-2022] ?>
						
					<tr>
						<td style="padding:10px; vertical-align:top;">12. Name(s) of the Trade Brand Label (TBL)  proposed to be applied on the graded packages.</td>
						<td style="padding:10px; vertical-align:top;">
							<table width="100%" border="1">
								<tr>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Name</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Is Registered?</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Reg. No.</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Document</th>
								</tr>
						
								<?php foreach($all_tbls_details as $each_tbl){?>
									<tr>
										<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_name'])){ echo $each_tbl['tbl_name']; }else{ echo 'NA'; }  ?></td>
										<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_registered'])){ echo $each_tbl['tbl_registered']; }else{ echo 'NA'; } ?></td>
										<td style="padding:10px; vertical-align:top;">
											<?php 
												if($each_tbl['tbl_registered']=='yes'){
													echo $each_tbl['tbl_registered_no']; 
												}else{
													echo "----";
												}										
											?>
										</td>
										<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_registration_docs'])){ $split_file_path = explode("/",$each_tbl['tbl_registration_docs']);
																				$file_name = $split_file_path[count($split_file_path) - 1];?>
																			<a href="<?php echo $each_tbl['tbl_registration_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
									</tr>
								<?php } ?>
							</table>
						</td>
					</tr>
						
					<tr>
						<td style="padding:10px; vertical-align:top;">13. Whether the proposed TBLs belongs to the applicant ?</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($tbl_data['tbl_belongs_to_applicant'])){ echo ucfirst($tbl_data['tbl_belongs_to_applicant']); }else{ echo 'NA'; }  ?>
						</td>
					</tr>
						
					<?php if($tbl_data['tbl_belongs_to_applicant'] == 'yes'){?>
					
						<tr>
							<td style="padding:10px; vertical-align:top;">13(a). TBLs(Form-A2) Document:</td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($tbl_data['tbl_belongs_docs'])){ $split_file_path = explode("/",$tbl_data['tbl_belongs_docs']);
																		$file_name = $split_file_path[count($split_file_path) - 1];?>
																	<a href="<?php echo $tbl_data['tbl_belongs_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
						</tr>

					<?php }else{ ?>
					
						<tr>
							<td style="padding:10px; vertical-align:top;">13(a). Name of the firm to which the proposed TBL belongs.</td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($tbl_data['tbl_proposed_firm'])){ echo $tbl_data['tbl_proposed_firm']; }else{ echo 'NA'; } ?>
							</td>
						</tr>
					
						<tr>
							<td style="padding:10px; vertical-align:top;">13(b). TBLs Consent Letter:</td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($tbl_data['tbl_consent_letter_docs'])){ $split_file_path = explode("/",$tbl_data['tbl_consent_letter_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $tbl_data['tbl_consent_letter_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
						</tr>
					
					<?php } ?>
					
				<?php } ?>
					
					
			<?php }elseif($ca_bevo_applicant == 'yes'){ ?>	
			
				<tr>
					<td style="padding:10px; vertical-align:top;">1.Name of the Firm</td>
					<td style="padding:10px; vertical-align:top;"><?php echo $customer_firm_data['firm_name']; ?> <br>
						<?php echo $customer_firm_data['street_address'].', ';
								echo $firm_district_name.', ';
								echo $firm_state_name.', ';
								echo $customer_firm_data['postal_code'].'.<br>';
								echo 'Email: '.base64_decode($customer_firm_data['email']).',<br>'; //for email encoding
								echo 'Phone: '.base64_decode($customer_firm_data['mobile_no']).',<br>';
								if(!empty($customer_firm_data['fax_no'])){ echo 'Landline: '. base64_decode($customer_firm_data['fax_no']); }
								
						?>
					</td>
				</tr>
			
			
				<tr>
					<td style="padding:10px; vertical-align:top;">2. Status of the Firm (Proprietary/Partnership/Private Ltd./Limited/Public Sector/Govt. Undertaking, etc.).</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($business_type)){ echo $business_type; }else{ echo 'NA'; }  ?><br /> 
					
					<?php if(!empty($firm_data['business_type_docs'])){ $split_file_path = explode("/",$firm_data['business_type_docs']);
					$file_name = $split_file_path[count($split_file_path) - 1];
					?>
					Provided Docs: <a href="<?php echo $firm_data['business_type_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
				</tr>

					
					
				<tr>
					<td style="padding:10px; vertical-align:top;">3. Whether authorized to manufacture and sell Blended Edible Vegetable Oils by the Department of Civil Supplies ?</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_data['authorised_for_bevo'])){ echo ucfirst($firm_data['authorised_for_bevo']); }else{ echo 'NA'; } ?><br>
					
						<?php if($firm_data['authorised_for_bevo'] == 'yes'){?>
							Authority Document:<?php  if(!empty($firm_data['authorised_bevo_docs'])){ $split_file_path = explode("/",$firm_data['authorised_bevo_docs']);
													$file_name = $split_file_path[count($split_file_path) - 1];?>
												<a href="<?php  echo $firm_data['authorised_bevo_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
						<?php } ?>
					</td>
				</tr>
					
					
				<!--Add New Field Affidavit/Undertaking From Oil Manufacturer by Pravin 22/07/2017-->
				
				<tr>
					<td style="padding:10px; vertical-align:top;">3(a). Affidavit/Undertaking From Oil Manufacturer:</td>
					<td style="padding:10px; vertical-align:top;"><?php  if(!empty($firm_data['oil_manu_affidavit_docs'])){ $split_file_path = explode("/",$firm_data['oil_manu_affidavit_docs']);
																$file_name = $split_file_path[count($split_file_path) - 1];?>
															<a href="<?php  echo $firm_data['oil_manu_affidavit_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
				</tr>
				
				<!--Add New Field Affidavit/Undertaking From Oil Manufacturer by Pravin 22/07/2017-->
				<tr>
					<td style="padding:10px; vertical-align:top;">3(b). FSSAI Relevant Document:</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_data['fssai_reg_docs'])){ $split_file_path = explode("/",$firm_data['fssai_reg_docs']);
																$file_name = $split_file_path[count($split_file_path) - 1];?>
															<a href="<?php echo $firm_data['fssai_reg_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
				</tr>
				
				<!--Add New Field Affidavit/Undertaking From Oil Manufacturer by Pravin 22/07/2017-->
				<tr>
					<td style="padding:10px; vertical-align:top;">3(c). VOP Related Document:</td>
					<td style="padding:10px; vertical-align:top;"><?php  if(!empty($firm_data['vopa_certificate_docs'])){ $split_file_path = explode("/",$firm_data['vopa_certificate_docs']);
																$file_name = $split_file_path[count($split_file_path) - 1];?>
															<a href="<?php  echo $firm_data['vopa_certificate_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
				</tr>
					
					
					
				<tr>
					<td style="padding:10px; vertical-align:top;">4. Mailing Address with contact details (Telephone Nos., Fax No., E-mail etc</td>
					<td style="padding:10px; vertical-align:top;">
						<?php echo $customer_firm_data['street_address'].', ';
								echo $firm_district_name.', ';
								echo $firm_state_name.', ';
								echo $customer_firm_data['postal_code'].'.<br>';
								echo 'Email: '.base64_decode($customer_firm_data['email']).',<br>'; //for email encoding
								echo 'Phone: '.base64_decode($customer_firm_data['mobile_no']).',<br>';
								if(!empty($customer_firm_data['fax_no'])){ echo 'Landline: '. base64_decode($customer_firm_data['fax_no']); }
								
						?>
					</td>
				</tr>
			


				
					
					
				<tr>
					<td style="padding:10px; vertical-align:top;">5. Approximate quantity of Blended Edible Vegetable Oils proposed to be graded per month:</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_data['quantity_per_month'])){ echo $firm_data['quantity_per_month']; }else{ echo 'NA'; }?>
					</td>
				</tr>
					
					
				<tr>
					<td style="padding:10px; vertical-align:top;">6. Name and address of the mills (if any), where Blended Edible Vegetable Oil will be manufactured:</td>
					<td style="padding:10px; vertical-align:top;"><?php  if(!empty($premises_data['bevo_mills_address_docs'])){ $split_file_path = explode("/",$premises_data['bevo_mills_address_docs']);
																$file_name = $split_file_path[count($split_file_path) - 1];?>
															<a href="<?php  echo $premises_data['bevo_mills_address_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
				</tr>
					
					
					
				<tr>
					<td style="padding:10px; vertical-align:top;">7. Name and address of oil mills from where the constituent oils are proposed to be procured:</td>
					<td style="padding:10px; vertical-align:top;">
						<table width="100%" border="1">
							<tr>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">Oil Name</th>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">Name & address of Oil Mill through which oil will be procured</th>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">Quantity Proposed to be Procured in MT's/Month</th>
							</tr>
							<?php foreach($all_const_oil_mill_details as $each_mill){?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_mill['oil_name'])){ echo $each_mill['oil_name']; }else{ echo 'NA'; }  ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_mill['mill_name_address'])){ echo $each_mill['mill_name_address']; }else{ echo 'NA'; }  ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_mill['quantity_procured'])){ echo $each_mill['quantity_procured']; }else{ echo 'NA'; }  ?></td>
								</tr>
							<?php } ?>
							
						</table>
						</td>
				</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">8. Quantity of different oilseeds being crushed/oil refined annually</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($machinery_data['quantity_of_oilseeds'])){ echo $machinery_data['quantity_of_oilseeds']; }else{ echo 'NA'; }?>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">8(a). Oilseeds normally crushed/oil refined by the mill</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($machinery_data['crushed_refined_seeds'])){ echo $machinery_data['crushed_refined_seeds']; }else{ echo 'NA'; }?>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">8(b). Period for which the mill has been in crushing/refining business</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($crushed_refined_period)){ echo $crushed_refined_period; }else{ echo 'NA'; }?>
						</td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">9(a). Details of machineries available in the Oil mill with their capacity, in case of Blended Edible Vegetable Oils:</td>
						<td style="padding:10px; vertical-align:top;"><?php  if(!empty($machinery_data['bevo_machinery_details_docs'])){ $split_file_path = explode("/",$machinery_data['bevo_machinery_details_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php  echo $machinery_data['bevo_machinery_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">9(b). Details of minimum infrastructure/facilities available in the plant in case of Fat Spread:</td>
						<td style="padding:10px; vertical-align:top;"><?php  if(!empty($machinery_data['fat_spread_facility_docs'])){ $split_file_path = explode("/",$machinery_data['fat_spread_facility_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php  echo $machinery_data['fat_spread_facility_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">10. Are different oilseeds stored and crushed/oils refined separately?</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($machinery_data['stored_crushed_separately'])){ echo ucfirst($machinery_data['stored_crushed_separately']); }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
					<?php if($machinery_data['stored_crushed_separately'] == 'yes'){?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">10(a). Related Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php  if(!empty($machinery_data['stored_crushed_separately_docs'])){ $split_file_path = explode("/",$machinery_data['stored_crushed_separately_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php  echo $machinery_data['stored_crushed_separately_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					<?php } ?>
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">11. Are separate tanks used for storage of different oils ?</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($premises_data['separate_tanks_used'])){ echo ucfirst($premises_data['separate_tanks_used']); }else{ echo 'NA'; }?>
						</td>
					</tr>
					
					<?php if($premises_data['separate_tanks_used'] == 'yes'){?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">11(a). Related Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php  if(!empty($premises_data['separate_tanks_docs'])){ $split_file_path = explode("/",$premises_data['separate_tanks_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php  echo $premises_data['separate_tanks_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					<?php } ?>
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">12. What precautions taken to avoid mixing of different oil seeds and oils in the oil mill?</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($machinery_data['precautions_taken'])){ echo $machinery_data['precautions_taken']; }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
					
					
					
					
					
					<tr>
					<td style="padding:10px; vertical-align:top;">13. Number and capacity of tanks to be used for storage of the Constituent Oils and Blended Vegetable Oil proposed to be graded:</td>
					<td style="padding:10px; vertical-align:top;">
						<table width="100%" border="1">
							<tr>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank No.</th>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank Shape</th>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank Size</th>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank Capacity</th>
							</tr>
							<?php 
							$i=0;
							foreach($all_tanks_details as $each_tank){?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_no'])){ echo $each_tank['tank_no']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($tank_shape_value[$i])){ echo $tank_shape_value[$i]; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_size'])){ echo $each_tank['tank_size']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_capacity'])){ echo $each_tank['tank_capacity']; }else{ echo 'NA'; } ?></td>
								</tr>
							<?php 
							$i=$i+1;} ?>
							
						</table>
						</td>
					</tr>
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">14. Whether the locking arrangements have been provided with the storage tanks both at inlets and outlets?</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($premises_data['locking_for_storage_tanks'])){ echo ucfirst($premises_data['locking_for_storage_tanks']); }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
					

					<tr>
						<td style="padding:10px; vertical-align:top;">15. Whether the laboratory is fully equipped for analysis of constituent oils and Blended Edible Vegetable oils?</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($laboratory_data['is_lab_equipped'])){ echo ucfirst($laboratory_data['is_lab_equipped']); }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
					<!-- Add By Pravin 22-07-2017 -->
					<tr>
						<td style="padding:10px; vertical-align:top;">15(a). Chemist Details:</td>
						<td style="padding:10px; vertical-align:top;"><?php  if(!empty($laboratory_data['chemist_detail_docs'])){ $split_file_path = explode("/",$laboratory_data['chemist_detail_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php  echo $laboratory_data['chemist_detail_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					
					<!-- Added on 27-07-2017 by Amol -->
					<tr>
						<td style="padding:10px; vertical-align:top;">15(b). List of equipments, Glasswares & chemicals duly signed & stamp by the applicant:</td>
						<td style="padding:10px; vertical-align:top;"><?php  if(!empty($laboratory_data['lab_equipped_docs'])){  $split_file_path = explode("/",$laboratory_data['lab_equipped_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php  echo $laboratory_data['lab_equipped_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">16. Bank references</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_data['bank_references'])){ echo $firm_data['bank_references']; }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">17. Name(s) of the Trade Brand Label (TBL)  proposed to be applied on the graded packages.</td>
					
					
						<td style="padding:10px; vertical-align:top;">
							<table width="100%" border="1">
								<tr>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Name</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Is Registered?</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Reg. No.</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Document</th>
								</tr>
						
							<?php foreach($all_tbls_details as $each_tbl){?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_name'])){ echo $each_tbl['tbl_name']; }else{ echo 'NA'; }  ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_registered'])){ echo $each_tbl['tbl_registered']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;">
										<?php 
											if($each_tbl['tbl_registered']=='yes'){
												echo $each_tbl['tbl_registered_no']; 
											}else{
												echo "----";
											}										
										?>
									</td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_registration_docs'])){ $split_file_path = explode("/",$each_tbl['tbl_registration_docs']);
																			$file_name = $split_file_path[count($split_file_path) - 1];?>
																		<a href="<?php echo $each_tbl['tbl_registration_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
								</tr>
							<?php } ?>
							</table>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">18. Whether the proposed TBLs belongs to the applicant ?</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($tbl_data['tbl_belongs_to_applicant'])){ echo ucfirst($tbl_data['tbl_belongs_to_applicant']); }else{ echo 'NA'; }  ?>
						</td>
					</tr>
					
					<?php if($tbl_data['tbl_belongs_to_applicant'] == 'yes'){?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">19(a). TBLs(Form-A2) Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($tbl_data['tbl_belongs_docs'])){ $split_file_path = explode("/",$tbl_data['tbl_belongs_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $tbl_data['tbl_belongs_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					<?php }else{ ?>
					
						<tr>
							<td style="padding:10px; vertical-align:top;">19(a). Name of the firm to which the proposed TBL belongs.</td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($tbl_data['tbl_proposed_firm'])){ echo $tbl_data['tbl_proposed_firm']; }else{ echo 'NA'; } ?>
							</td>
						</tr>
					
						<tr>
							<td style="padding:10px; vertical-align:top;">19(b). TBLs Consent Letter:</td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($tbl_data['tbl_consent_letter_docs'])){ $split_file_path = explode("/",$tbl_data['tbl_consent_letter_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $tbl_data['tbl_consent_letter_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
						</tr>
					
					
					<?php } ?>
	
				

			<?php } ?>	
			
			<!-- This Below code is added for the CA EXPORT Form type F by Akash [20-09-2022]-->
			<?php if($form_type == 'F'){?>

				<tr>
					<td style="padding:10px; vertical-align:top;">10. Certificates of APEDA :</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_data['apeda_docs'])){ $split_file_path = explode("/",$firm_data['apeda_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $firm_data['apeda_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
					</td>
				</tr>
				<tr>
					<td style="padding:10px; vertical-align:top;">11. IEC Code :</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_data['iec_code'])){ echo $firm_data['iec_code']; }else{ echo 'NA'; } ?></td>
				</tr>
				<tr>
					<td style="padding:10px; vertical-align:top;">11(a). IEC Document :</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_data['iec_code_docs'])){ $split_file_path = explode("/",$firm_data['iec_code_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $firm_data['iec_code_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
					</td>
				</tr>

			<?php } ?>

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
		
		<?php //if($show_esigned_by=='yes'){ ?><!-- Condition added on 27-03-2018 by Amol -->	
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
		<?php //} ?>	

	
		