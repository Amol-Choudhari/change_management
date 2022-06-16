<?php ?>		

<style>
	h4 {
		padding: 5px;
		font-family: times;
		font-size: 13pt;
	}							 

	table{
		padding: 5px;
		font-size: 11pt;
		font-family: times;
	}
				
</style>	   
	
	
	<!-- Below new table for FORM D added on 26-08-2017 by Amol -->
	
		<table width="100%" border="1">
		
				
				<tr>	<!-- added condition on 01-09-2017 by Amol -->			
					<td align="center"><h4><?php if($export_unit_status == 'yes'){?>FORM C<?php }else{ ?>FORM D<?php }?></h4></td>
				</tr>

				
		</table>
		
		
		<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;">
			<!-- added condition on 01-09-2017 by Amol -->
			<h4><?php if($export_unit_status == 'yes'){?>
					Application for approval of commercial laboratory for matters relating to Organic Certification / grading and marking of Agricultural Commodities for EXPORT
				<?php }else{ ?>
					Application for approval of State Grading Laboratory/ Commercial Laboratory for Grading & Marking of Agricultural Commodities for DOMESTIC MARKET
				<?php } ?>
			</h4>
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
				<td><br>The Dy. Agricultural Marketing Adviser/<br>
					Asstt.Agricultural Marketing Adviser/<br>
					Senior Marketing Officer,<br>
					Directorate of Marketing & Inspection<br>
					<!--<?php //echo $get_office['ro_office']; ?>,<?php //echo $firm_state_name; ?> on 23-05-2022 as conflict in sponsored PP appl -->
					<?php echo $get_office['ro_office']; ?>
				</td><br><br>
			</tr>

			<tr><td></td><br></tr>			 
			<tr>
				<td>Sir,</td><br>
			</tr>
			<!-- added condition on 01-09-2017 by Amol -->
			<?php if($export_unit_status == 'yes'){?>
			<tr>
				<td>I/we <?php echo $firm_detail['firm_name']; ?> seek approval of our laboratory to undertake grading and marking of agricultural commodities for export and matters relating to Organic Certification in accordance with the provisions of Agricultural Produce (Grading & Marking) Act,1937 and Rules made there under.</td>
			</tr>
			<?php }else{ ?>
			<tr>
				<td>I/we <?php echo $firm_detail['firm_name']; ?> seek approval of our laboratory to undertake grading and marking of agricultural commodities for domestic market in accordance with the provisions of Agricultural Produce (Grading & Marking) Act, 1937 and Rules made there under.</td>
			</tr>
			<?php } ?>
			
			<tr>
				<td>I/we have carefully gone through the provisions of the Agricultural Produce (Grading & Marking) Act, 1937, General Grading & Marking Rules, 1988, Commodity Grading and Marking Rules, and the instructions issued on the subject and hereby agree to abide by them as well as those which may thereafter be issued in this regard.</td>
			</tr>
			<!-- added condition on 01-09-2017 by Amol -->
			<?php if($export_unit_status == 'yes'){?>
			<tr>
				<td>I/we furnish the requisite particulars in the prescribed Forms C-1, C-1(a), C-1(b) and also submit necessary documents.</td>
			</tr>
			<?php }else{ ?>
			<tr>
				<td>I/we furnish herewith the requisite particulars in the prescribed Form D-1 and also submit necessary documents.</td>
			</tr>
			<?php } ?>
			
			<tr>
				<td>I/we hereby declare that the information furnished therein is true and correct and the changes, if any, will be reported to the Directorate immediately.</td>
			</tr>
			
			<tr>
				<td>The processing fee of Rs. <?php echo $firm_detail['total_charges']; ?> through  online payment  No.    <?php if(!empty($applicant_payment_detail['transaction_id'])){ echo $applicant_payment_detail['transaction_id']; }else{ echo 'NA'; }  ?>  dated    <?php if(!empty($applicant_payment_detail['transaction_date'])){ $payment_date = explode(' ',$applicant_payment_detail['transaction_date']); echo $payment_date[0]; }else{ echo 'NA'; } ?> .</td> 
			</tr>
			
		</table>
		
		
		<table>					
				<tr><td></td><br></tr>		  
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
				<td><?php echo $firm_detail['firm_name']; ?><br> 
					<?php echo $firm_detail['street_address'].', <br>';
							echo $firm_district_name.', ';
							echo $firm_state_name.', ';
							echo $firm_detail['postal_code'].'.<br>';?>
				</td>
				</tr>
		</table>
		<?php //} ?>
	<!-- FORM D portion end here -->		
		
		
		<br pagebreak="true" />
	

	
	
	
	<!-- FORM D1 portion end here -->	
	
		<table width="100%" border="1">
	
			
				<tr>				
					<td align="center"><h4><?php if($export_unit_status == 'yes'){?>FORM C1<?php }else{ ?>FORM D1<?php }?></h4></td>
				</tr>
			
			
		</table>
	
	
	
		<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;">
			<!-- added condition on 01-09-2017 by Amol -->
				<?php if($export_unit_status == 'yes'){?>
				<h4>Application for approval of commercial laboratory for matters relating to Organic Certification/ grading and marking of Agricultural Commodities for EXPORT</h4>
				<?php }else{ ?>
				<h4>Application for approval of State Grading Laboratory/ Commercial Laboratory for Grading & Marking of Agricultural Commodities for DOMESTIC MARKET</h4>
				<?php } ?>
			</td>
			</tr>
		</table>

		
		
		
		
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

				
					<tr>
						<td style="padding:10px; vertical-align:top;">1.(a) Name of Of the laboratory :
																	  <br>(b) Type of laboretory : </td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_detail['firm_name'])){  echo $firm_detail['firm_name']; }else{ echo 'NA'; }  ?>
																	  <br><?php if(!empty($laboratory_type)){ echo $laboratory_type; }else{ echo 'NA'; } ?></td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">2. Mailing Address with contact details i.e. Phone No., Fax, e-mail etc.</td>
						<td style="padding:10px; vertical-align:top;">
							<?php echo $firm_detail['street_address'].', ';
									echo $firm_district_name.', ';
									echo $firm_state_name.', ';
									echo $firm_detail['postal_code']; ?><br>
							<?php	echo base64_decode($firm_detail['email']); //for email encoding ?><br>
							<?php	echo base64_decode($firm_detail['mobile_no']);	?><br>
							<?php	echo base64_decode($firm_detail['fax_no']); ?>
							
						</td>
					</tr>
					
				
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">3. Status of the Laboratory:<br>(Proprietory/partnership/pvt.ltd./limited. etc.)
																	  <br>Enclose a self-attested copy of the relevant document.</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($business_type)){  echo $business_type; }else{ echo 'NA'; }  ?><br /> 
						
						<?php if(!empty($check_fields_result[0]['business_type_docs'])){ $split_file_path = explode("/",$check_fields_result[0]['business_type_docs']);
						$file_name = $split_file_path[count($split_file_path) - 1];
						?>
						<a href="<?php echo $check_fields_result[0]['business_type_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">4. Date of establishment of the laboratory:</td>
						<td style="padding:10px; vertical-align:top;"><?php	if(!empty($check_fields_result[0]['establishment_date'])){ $check_fields_result[0] = explode(' ',$check_fields_result[0]['establishment_date']);
																			echo $check_fields_result[0][0]; }else{ echo 'NA'; } ?></td>
					</tr>
					
					
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">5. Whether the laboratory premises is owned? :
							<?php if($check_laboratory_other_fields_result[0]['premises_belongs_to'] == 'no'){ ?><br>Premises owner name : <?php } ?> 
																	<br>enclose a self-attested copy of the relevant document</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_laboratory_other_fields_result[0]['premises_belongs_to'])){ echo ucfirst($check_laboratory_other_fields_result[0]['premises_belongs_to']); }else{ echo 'NA'; }  ?>
						<br><?php if($check_laboratory_other_fields_result[0]['premises_belongs_to'] == 'no'){ if(!empty($check_laboratory_other_fields_result[0]['owner_name'])){ echo $check_laboratory_other_fields_result[0]['owner_name']; }else{ echo 'NA'; } } ?>
										<br><?php if(!empty($check_laboratory_other_fields_result[0]['premises_belongs_to_docs'])){ $split_file_path = explode("/",$check_laboratory_other_fields_result[0]['premises_belongs_to_docs']);
										$file_name = $split_file_path[count($split_file_path) - 1]; ?>
										<a href="<?php echo $check_laboratory_other_fields_result[0]['premises_belongs_to_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					
					<!-- added condition on 01-09-2017 by Amol -->
					<?php if($export_unit_status == 'yes'){ ?>
					<tr>
						<td style="padding:10px; vertical-align:top;">5(a). Name of Laboratory Chief Executive :</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_laboratory_other_fields_result[0]['lab_ceo_name'])){ echo $check_laboratory_other_fields_result[0]['lab_ceo_name']; }else{ echo 'NA'; } ?></td>
					</tr>
					<?php } ?>
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">6. Total covered area of the laboratory :
																	<br>enclose a self-attested copy of the sketch of the laboratory </td>																		
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_laboratory_other_fields_result[0]['total_area_covered'])){ echo $check_laboratory_other_fields_result[0]['total_area_covered'].'sq/m'; }else{ echo 'NA'; } ?>
																	 <br><?php if(!empty($check_laboratory_other_fields_result[0]['total_area_covered_docs'])){ $split_file_path = explode("/",$check_laboratory_other_fields_result[0]['total_area_covered_docs']);
																		$file_name = $split_file_path[count($split_file_path) - 1]; ?>
									<a href="<?php echo $check_laboratory_other_fields_result[0]['total_area_covered_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">7. Whether the laboratory is accredited with NABL :
						<?php if($check_laboratory_other_fields_result[0]['is_accreditated'] == 'yes'){ ?>	<br>a) Accreditation No. :<br>b) Scope of the accreditation :<br>
																										c) Accredidation Certificate of NABL : <?php } ?></td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_laboratory_other_fields_result[0]['is_accreditated'])){ echo ucfirst($check_laboratory_other_fields_result[0]['is_accreditated']); }else{ echo 'NA'; } ?>
						<?php if($check_laboratory_other_fields_result[0]['is_accreditated'] == 'yes'){ ?> <br><?php if(!empty($check_laboratory_other_fields_result[0]['accreditation_no'])){ echo $check_laboratory_other_fields_result[0]['accreditation_no']; }else{ echo 'NA'; } ?>
																	<br><?php if(!empty($check_laboratory_other_fields_result[0]['accreditation_scope'])){ echo $check_laboratory_other_fields_result[0]['accreditation_scope']; }else{ echo 'NA'; } ?><br>
															<?php if(!empty($check_laboratory_other_fields_result[0]['accreditation_docs'])){ $split_file_path = explode("/",$check_laboratory_other_fields_result[0]['accreditation_docs']);
															$file_name = $split_file_path[count($split_file_path) - 1]; ?>
						<a href="<?php echo $check_laboratory_other_fields_result[0]['accreditation_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } } ?></td>
														
					</tr>
					
					<!-- added condition on 01-09-2017 by Amol -->
					<?php if($export_unit_status == 'yes' && $check_laboratory_other_fields_result[0]['is_accreditated'] == 'yes'){ ?>
					<tr>
						<td style="padding:10px; vertical-align:top;">7(a). Recognition Certificate issued from APEDA:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_laboratory_other_fields_result[0]['apeda_docs'])){ $split_file_path = explode("/",$check_laboratory_other_fields_result[0]['apeda_docs']);
															$file_name = $split_file_path[count($split_file_path) - 1]; ?>
						<a href="<?php echo $check_laboratory_other_fields_result[0]['apeda_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?></td>
					</tr>
					<?php } ?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">8. Name of the commodities which are being analyzed :</td>						
						
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($sub_commodities_details)){ $i = 0;
						foreach($sub_commodities_details as $commodities_details){
						echo $commodities_details.', '; 
						$i=$i+1; } }else{ echo 'NA'; } ?>
						</td>
						
					</tr>
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">9. Whether the laboratory is fully equipped for testing and grading of commodity(ies) for which approval is sought? :
						<?php if($check_laboratory_other_fields_result[0]['is_laboretory_equipped'] == 'yes'){ ?><br>Complete list of equipments, Glasswares & Chemicals duly singed & stamp of applicant<?php } ?></td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_laboratory_other_fields_result[0]['is_laboretory_equipped'])){ echo ucfirst($check_laboratory_other_fields_result[0]['is_laboretory_equipped']); }else{ echo 'NA'; } ?>
						<?php if($check_laboratory_other_fields_result[0]['is_laboretory_equipped'] == 'yes'){ ?><br><?php if(!empty($check_laboratory_other_fields_result[0]['is_laboretory_equipped_docs'])){  $split_file_path = explode("/",$check_laboratory_other_fields_result[0]['is_laboretory_equipped_docs']);
																		$file_name = $split_file_path[count($split_file_path) - 1]; ?>
						<a href="<?php echo $check_laboratory_other_fields_result[0]['is_laboretory_equipped_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } } ?></td>
					</tr>
					
					
					<tr>
					<td style="padding:10px; vertical-align:top;">10. Complete chemists employed details with name of the chemists, their qualification, year of experience, commodity for which approved under Agmark :</td>
					<td style="padding:10px; vertical-align:top;">
						<table width="100%" border="1">
							<tr>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Name of Chemist</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Qualification (Highest)</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Experience<br>(In Years)</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Commodity</th>
								<th style="padding:10px;" width="20%" cellspacing="50" align="left">Upload File<br>(Individual Chemist Details)</th>
							</tr>
							<?php 
							$i=1;
							foreach($chemist_details as $chemist_detail){ ?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_detail['chemist_name'])){ echo $chemist_detail['chemist_name']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_detail['qualification'])){ echo $chemist_detail['qualification']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_detail['experience'])){ echo $chemist_detail['experience']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($chemist_commodity_value[$i])){ echo $chemist_commodity_value[$i]; }else{ echo 'NA'; } ?></td>
									<td><?php if($chemist_detail['chemists_details_docs'] != null){ ?>
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
						<td style="padding:10px; vertical-align:top;">11. Any other information relevant to the laboratory.</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($check_laboratory_other_fields_result[0]['other_information'])){ echo $check_laboratory_other_fields_result[0]['other_information']; }else{ echo 'NA'; } ?></td>
					</tr>
					
					
			</table>	
		
		
		
			<table>
				<tr><td></td><br></tr>		  
					<tr>
						<td align="left">
							<h4>I hereby declare that the above information is correct.</h4>
						</td>
					</tr>
			</table>
			
			<table>					
				<tr><td></td><br></tr>		  
					<tr>
						<td  align="left">
							Date: <?php echo $pdf_date;?>
						</td>
					</tr>
			</table>
			
		<?php //if($show_esigned_by=='yes'){ ?><!-- Condition added on 27-03-2018 by Amol -->
			<table align="right">	

					<tr>
					<td><?php echo $firm_detail['firm_name'].', <br>'; ?> <?php echo $firm_detail['street_address'].', <br>';
										echo $firm_district_name.', ';
										echo $firm_state_name.', ';
										echo $firm_detail['postal_code'].'.<br>';?>
					</td>
					</tr>
			</table>
		<?php //} ?>
	