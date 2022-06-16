<?php ?>
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
	
	
	
	<!-- Below new table for FORM B added on 25-08-2017 by Amol -->
	
	<table width="100%" border="1">
	
	
			<tr>				
				<td align="center"><h4>FORM B</h4></td>
			</tr>
		

	</table>
	
	
	<table width="100%" border="1">
		<tr>
		<td align="center" style="padding:5px;">
		<h4>Application for grant of permission to the Printing Press for printing of Agmark Replica/Slips.</h4>
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
			</td><br>
		</tr>
		
		<tr>
			<td>Subject: Intimation for permission to the Printing Press for printing of Agmark Replica/Slips-reg</td><br><br>
		</tr>
		
		<tr>
			<td>Sir,</td><br>
		</tr>
		
		<tr>
			<td>I/We <?php echo $firm_data['owner_name']; ?> desire to engage my/our printing press namely <?php echo $customer_firm_data['firm_name']; ?> for printing of Agmark replica/slips on <?php foreach($packaging_type_list as $each_type){ echo $each_type.','; } ?> for Agmark authorized packer(s) w.e.f________. My/our printing press is situated at <?php echo $premises_data['street_address'].', ';echo $premises_district_name.', ';echo $premises_state_name.', ';echo $premises_data['postal_code']; ?>.</td>
		</tr>
		
		<tr>
			<td>I/We have carefully gone through the guidelines for permission to the printing presses issued by Agricultural Marketing Adviser to the Government of India.</td>
		</tr>
		
		<tr>
			<td>I/We hereby agree to abide by the instructions issued or that may be issued in this regard from time to time.</td>
		</tr>
		
		<tr>
			<td>The processing fee of Rs. <?php echo $customer_firm_data['total_charges']; ?> through  online payment  No.    <?php if(!empty($applicant_payment_detail['transaction_id'])){ echo $applicant_payment_detail['transaction_id']; }else{ echo 'NA'; }  ?>  dated    <?php if(!empty($applicant_payment_detail['transaction_date'])){ $payment_date = explode(' ',$applicant_payment_detail['transaction_date']); echo $payment_date[0]; }else{ echo 'NA'; } ?> .</td>
		</tr>
		
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
	<table  align="right">	
			
			<tr>
			<td><?php echo $customer_firm_data['firm_name']; ?>,<br> 
				<?php echo $customer_firm_data['street_address'].', <br>';
						echo $firm_district_name.', ';
						echo $firm_state_name.', ';
						echo $customer_firm_data['postal_code'].'.<br>';?>
			</td>
			</tr>
	</table>
	<?php //} ?>

<!-- FORM B portion end here -->		
	
	
	<br pagebreak="true" />
	
	
	
<!-- FORM B1 portion Starts here -->
	
	<table width="100%" border="1">
	

			<tr>				
				<td align="center"><h4>FORM B1</h4></td>
			</tr>
		

	</table>
	
	
		<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;">
				<h4>Application for Grant of permission for Printing of Agmark Replica</h4>
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
				<!--
				<tr>
					<th style="padding:10px;" width="50%" cellspacing="50" align="left">Field Name</th>
					<th style="padding:10px;" width="50%" cellspacing="50" align="left">Description</th>
				</tr>
				-->
				
				
				
				
				
				
					<tr>
						<td style="padding:10px; vertical-align:top;">1. Name of printing Press :</td>
						<td style="padding:10px; vertical-align:top;"><?php echo $customer_firm_data['firm_name']; ?></td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">2. Full postal address. (with contact details mobile No./Fax No. E-mail etc.)<br>2(a). Registered Office:-  </td>
						<td style="padding:10px; vertical-align:top;">
							<?php echo $customer_firm_data['street_address'].', ';
									echo $firm_district_name.', ';
									echo $firm_state_name.', ';
									echo $customer_firm_data['postal_code']; ?><br>
							<?php	echo base64_decode($printing_firm_profile_data['firm_email_id']); //for email encoding ?><br>
							<?php	echo base64_decode($printing_firm_profile_data['firm_mobile_no']);	?><br>
							<?php	echo base64_decode($printing_firm_profile_data['firm_fax_no']); ?>
							
						</td>
					</tr>
					
				<!-- code Start By Pravin 18/3/2017-->	
					<!--
					<tr>
						<td style="padding:10px; vertical-align:top;">1(c). Email:</td>
						<td style="padding:10px; vertical-align:top;">
							<?php 
									//echo $printing_firm_profile_data['firm_email_id'];									
							?>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">1(d). Mobile_no:</td>
						<td style="padding:10px; vertical-align:top;">
							<?php 
									//echo $printing_firm_profile_data['firm_mobile_no'];									
							?>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">1(e). fax_no:</td>
						<td style="padding:10px; vertical-align:top;">
							<?php 
									//echo $printing_firm_profile_data['firm_fax_no'];									
							?>
						</td>
					</tr>
					-->
				<!-- code End By Pravin 18/3/2017-->
				
				<!-- commented by pravin 18/3/2017
				
					<tr>
						<td style="padding:10px; vertical-align:top;">1(d). Relevant Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php //$split_file_path = explode("/",$primary_customer_data['Dmi_customer']['file']);
																	//$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php //echo $primary_customer_data['Dmi_customer']['file']; ?>"><?php echo $file_name; ?></a></td>
					</tr>
					
					
					-->
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">2(b) Address of the printing unit where printing work shall be done:</td>
						<td style="padding:10px; vertical-align:top;">
							<?php echo $premises_data['street_address'].', ';
									echo $premises_district_name.', ';
									echo $premises_state_name.', ';
									echo $premises_data['postal_code'];
									
							?>
						</td>
					</tr>
	
					<!-- Commented By pravin 15/3/2017
					<tr>
						<td style="padding:10px; vertical-align:top;">2(b). Relevant Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php //$split_file_path = explode("/",$premises_data['premises_docs']);
																	//$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php //echo $premises_data['premises_docs']; ?>"><?php //echo $file_name; ?></a></td>
					</tr>
					
					
					
					
					
					
					
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">3. Type of packaging material on which printing of Agmark replica, serial numbers shall be done</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $packing_type_name;?>
						</td>
					</tr>
					
					
					
					
					
					
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">4(a). Whether the said premises belong to applicant:</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $premises_data['belongs_to_applicant'];?>
						</td>
					</tr>
					
					<?php //if($premises_data['belongs_to_applicant'] == 'no'){?>
					<tr>
						<td style="padding:10px; vertical-align:top;">4(b). Premises Owner Name:</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $premises_data['premises_owner_name'];?>
						</td>
					</tr>
					<?php // } ?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">4(c). Relevant Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php //$split_file_path = explode("/",$premises_data['premises_docs']);
																	//$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php //echo $premises_data['premises_docs']; ?>"><?php //echo $file_name; ?></a></td>
					</tr>
					
					
					
					
					
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">5. Whether the structure of printing unit is permanent in nature?</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $premises_data['structure_is_permanent'];?>
						</td>
					</tr>
					
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">6. Whether printing unit is having adequate arrangement for safe keep of printed material and records related to Agmark?</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $premises_data['adequate_arrangement'];?>
						</td>
					</tr>
					
					-->
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">3. Status of the Printing press:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($business_type)){ echo $business_type; }else{ echo 'NA'; } ?><br /> 
						
						<?php if(!empty($firm_data['business_type_docs'])){ $split_file_path = explode("/",$firm_data['business_type_docs']);
						$file_name = $split_file_path[count($split_file_path) - 1]; ?>
						<a href="<?php echo $firm_data['business_type_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?></td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">4. Name(s) of the proprietor/ partners/ Directors etc:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_data['owner_name'])){ echo $firm_data['owner_name']; }else{ echo 'NA'; }?></td>
					</tr>
					
					
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">5. Period for which the Printing Press is in the printing business:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($business_years_value)){ echo $business_years_value; }else{ echo 'NA'; } ?></td>  <!-- Change variable (by pravin 13/05/2017)-->
					</tr>
					
					
					
					
					<!-- Commented By pravin 18/3/2017
					
					<tr>
						<td style="padding:10px; vertical-align:top;">10. Whether affidavit in proforma-III is attached?</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $firm_data['affidavit_proforma_3_attached'];?>
						</td>
					</tr>
					
					-->
					
					<tr>
						<td style="padding:10px; vertical-align:top;">6. Whether the Press was earlier permitted for printing of Agmark replica on packaging material? </td>
																		
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($printing_unit_detail['earlier_approved'])){ echo ucfirst($printing_unit_detail['earlier_approved']); }else{ echo 'NA'; } ?></td>
					</tr>
					
					<?php if($printing_unit_detail['earlier_approved'] == 'yes'){?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">6(a). Date of expiry of such permission be mentioned :</td>
						<td style="padding:10px; vertical-align:top;"><?php	if(!empty($printing_unit_detail['earlier_expiry_date'])){ $earlier_expiry_date = explode(' ',$printing_unit_detail['earlier_expiry_date']);	
																			echo $earlier_expiry_date[0]; }else{ echo 'NA'; } ?></td>
					</tr>
					
					<?php } ?>
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">7. Is Assessed for the purpose of Income Tax, Sale Tax, etc? </td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($premises_data['have_vat_cst_no'])){ echo ucfirst($premises_data['have_vat_cst_no']); }else{ echo 'NA'; } ?></td>
					</tr>
					
					<?php if($premises_data['have_vat_cst_no'] == 'yes'){?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">7(a). GST No.<br>7(b). Relevant Document:
																	  
																	  <!--iii)VAT/CST/GST Relevant Document:-->
						</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $premises_data['vat_cst_no']; ?><br>
																	  <?php if(!empty($premises_data['have_vat_cst_no'])){ echo $premises_data['gst_no']; }else{ echo 'NA'; } ?><br>
																	  <?php if(!empty($premises_data['vat_cst_docs'])){ $split_file_path = explode("/",$premises_data['vat_cst_docs']);
																	  $file_name = $split_file_path[count($split_file_path) - 1];?>
																	  <a href="<?php echo $premises_data['vat_cst_docs']; ?>"><?php echo substr($file_name,23); ?></a> <?php }else{ echo 'NA'; } ?>
						</td>
					</tr>
					<?php } ?>
					<!--
					<tr>
						<td style="padding:10px; vertical-align:top;">11.(a) GST No.</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $premises_data['gst_no']; ?></td>
					</tr>
					-->
					
					
					
					<!--
					<tr>
						<td style="padding:10px; vertical-align:top;">11.(b) VAT/CST Relevant Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php //$split_file_path = explode("/",$premises_data['vat_cst_docs']);
																	//$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php //echo $premises_data['vat_cst_docs']; ?>"><?php //echo substr($file_name,23); ?></a></td>
					</tr>
					
					<?php //} ?>
					-->
					
					<tr>
						<td style="padding:10px; vertical-align:top;">8. Responsible persons who will look after the work of printing unit and correspondence with DMI.</td>
						<td style="padding:10px; vertical-align:top;">
							<b>Responsible Person 1:</b><br>
							Name: 		<?php if(!empty($premises_data['first_rep_f_name'])){ echo $premises_data['first_rep_f_name']; }else{ echo 'NA'; } ?> 
										<?php //if(!empty($premises_data['first_rep_m_name'])){ echo $premises_data['first_rep_m_name']; }else{ echo 'NA'; } ?>
										<?php if(!empty($premises_data['first_rep_l_name'])){ echo $premises_data['first_rep_l_name']; }else{ echo 'NA'; } ?><br>
							Mobile No. <?php if(!empty($premises_data['first_rep_mobile'])){ echo base64_decode($premises_data['first_rep_mobile']); }else{ echo 'NA'; } ?><br>
							Signature: <?php if(!empty($premises_data['first_rep_signature'])){ $split_file_path = explode("/",$premises_data['first_rep_signature']);
											$file_name = $split_file_path[count($split_file_path) - 1];?>
										<a href="<?php echo $premises_data['first_rep_signature']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?>
							
							<br><br>	
							
							<b>Responsible Persons 2:</b><br>
							Name: 		<?php if(!empty($premises_data['second_rep_f_name'])){ echo $premises_data['second_rep_f_name']; }else{ echo 'NA'; } ?> 
										<?php //if(!empty($premises_data['second_rep_m_name'])){ echo $premises_data['second_rep_m_name']; }else{ echo 'NA'; } ?>
										<?php if(!empty($premises_data['second_rep_l_name'])){ echo $premises_data['second_rep_l_name']; }else{ echo 'NA'; } ?><br>
							Mobile No. <?php if(!empty($premises_data['second_rep_mobile'])){ echo base64_decode($premises_data['second_rep_mobile']); }else{ echo 'NA'; } ?><br>
							Signature: <?php if(!empty($premises_data['second_rep_signature'])){ $split_file_path = explode("/",$premises_data['second_rep_signature']);
											$file_name = $split_file_path[count($split_file_path) - 1];?>
										<a href="<?php echo $premises_data['second_rep_signature']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?>
							
						</td>
					</tr>
					
					
					
					<!--
					<tr>
						<td style="padding:10px; vertical-align:top;">6. Details list of printing machines possessed by the printing press with name, numbers and capacity.</td>
						<td style="padding:10px; vertical-align:top;"><?php // echo $machinery_data['have_details'];?></td>
					</tr>
					-->
						
						
					<tr>
					<td style="padding:10px; vertical-align:top;">9. Details list of printing machines possessed by the printing press with name, numbers and capacity:</td>
					<td style="padding:10px; vertical-align:top;">
						<table width="100%" border="1">
							<tr>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">Name</th>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">Type</th>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">No.</th>
								<th style="padding:10px;" width="25%" cellspacing="50" align="left">Capacity</th>
							</tr>
							<?php foreach($all_machine_details as $each_machine){?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_machine['machine_name'])){ echo $each_machine['machine_name']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_machine['machine_type'])){ echo $each_machine['machine_type']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_machine['machine_no'])){ echo $each_machine['machine_no']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_machine['machine_capacity'])){ echo $each_machine['machine_capacity']; }else{ echo 'NA'; } ?></td>
								</tr>
							<?php } ?>
							
						</table>
						</td>
					</tr>
					
					<!--
					<tr>
																		<th>Name</th>
																		<th>Type</th>
																		<th>No.</th>
																		<th>Capacity(Nos)</th>
																	</tr>	
																<?php
																	//$i=1;
																		//foreach($added_machines_details as $each_machine){ ?>	-->
						<!--code start by Pravin 18/3/2017--><!--<tr>
																<td><?php //echo $printing_machines_detail['machine_name']; ?></td>
																<td><?php			//echo $printing_machines_detail['machine_no']; ?></td>
																<td><?php			//echo $printing_machines_detail['machine_capacity'];?></td>
																	
															</tr>	-->
															<?php // $i=$i+1; } ?>	
						<!--code end by Pravin 18/3/2017-->
						
						<?php //$split_file_path = explode("/",$printing_unit_detail['machine_details_docs']);
																	//$file_name = $split_file_path[count($split_file_path) - 1];?>
															<!--	<a href="<?php //echo $printing_unit_detail['machine_details_docs']; ?>"><?php //echo $file_name; ?></a></td>
					</tr>-->
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">10. Details of other required machineries (especially for printing on tin sheets and for fabrication of containers from the sheet installed in the printing press.</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($printing_unit_detail['other_required_machine_docs'])){ $split_file_path = explode("/",$printing_unit_detail['other_required_machine_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $printing_unit_detail['other_required_machine_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?></td>
					</tr>
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">11(a). Weather printing unit is having in house machinery for printing of Agmark replica and serial numbers?</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($printing_unit_detail['other_required_machine_docs'])){ echo ucfirst($printing_unit_detail['in_house_machinery']); }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
					
					
					
					<!-- Commented By pravin 15/3/2017
					
					<tr>
						<td style="padding:10px; vertical-align:top;">15. Is the printing unit is having facilities for security and safe keeping of printing material and printed material?</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $premises_data['facility_for_security'];?>
						</td>
					</tr>
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">16. Whether the printing unit is having proper documentation system for various printing work undertaken by the firm?</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $premises_data['proper_documentation_system'];?>
						</td>
					</tr>
					
					-->
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">Note: Any addition/alteration of the printing machinery shall be inform to all concerned offices of the directorate immediately.<br>
						
							11(b). Whether the printing unit is having proper facilities for fabrication of tin containers from the tin sheet?</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($printing_unit_detail['proper_fabrication'])){ echo ucfirst($printing_unit_detail['proper_fabrication']);  }else{ echo 'NA'; } ?><br>
																	  <?php if(!empty($printing_unit_detail['fabrication_docs'])){ $split_file_path = explode("/",$printing_unit_detail['fabrication_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $printing_unit_detail['fabrication_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?></td>
																	  
																	  
																	 					
					</tr>
					
					<?php if($printing_unit_detail['proper_fabrication'] == 'no'){?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">Note: If the facility for fabrication of tins is not available within the printing press, mention the name of fabrication unit with whome the tie-up has been arranged.(Affidavit to this effect may be submitted).<br>
						
							11(b)(i). Mention the name of fabrication unit with whom the tie-up has been arranged</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($printing_unit_detail['name_address_fabrication_unit'])){ echo $printing_unit_detail['name_address_fabrication_unit']; }else{ echo 'NA'; } ?><br>
						</td>
					</tr>
					<?php } ?>
					
					
					<!-- commented by Pravin 18/3/2017
					<tr>
						<td style="padding:10px; vertical-align:top;">18. Whether the printing unit is having proper facilities for fabrication of tin containers from the tin sheet?</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $printing_unit_detail['proper_fabrication'];?>
						</td>
					</tr>
					
					
					<?php //if($printing_unit_detail['proper_fabrication'] == 'no'){?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">18(a). Name and address of other unit were fabrication shall be done.</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $printing_unit_detail['name_address_fabrication_unit']; ?></td>
					</tr>
					
					<?php // } ?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">18(b). Relevant Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php //$split_file_path = explode("/",$printing_unit_detail['fabrication_docs']);
																	//$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php //echo $printing_unit_detail['fabrication_docs']; ?>"><?php //echo $file_name; ?></a></td>
					</tr>
					
					-->
					
					
					
					
					
					
					<!-- Code Commented By Pravin 15/3/2017
					
					<tr>
						<td style="padding:10px; vertical-align:top;">19(a). Whether containers shall be of food grade material?</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $printing_unit_detail['is_container_of_food_grade'];?>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">19(b). Relevant Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php //$split_file_path = explode("/",$printing_unit_detail['container_docs']);
																	//$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php //echo $printing_unit_detail['container_docs']; ?>"><?php //echo $file_name; ?></a>
						</td>						
					</tr>
					
					
					
					
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">20. Whether right quality of ink shall be used and the printing ink will doesn’t contaminate the products packed in the packages?</td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $printing_unit_detail['right_quality_ink'];?>
						</td>
					</tr>
					
				-->
					
					
					
					
					
				<!-- commented by pravin 18/3/2017>	
					<tr>
						<td style="padding:10px; vertical-align:top;">21. Responsible  persons of firm who will look after & attend the grading work and correspondence pertaining to Agmark.</td>
						<td style="padding:10px; vertical-align:top;">
							<b>Representative 1:</b><br>
							Name: 		<?php //echo $firm_data['first_rep_f_name']; ?> 
										<?php //echo $firm_data['first_rep_m_name']; ?>
										<?php //echo $firm_data['first_rep_l_name']; ?><br>
							Mobile No. <?php //echo $firm_data['first_rep_mobile']; ?><br>
							Signature: <?php //$split_file_path = explode("/",$firm_data['first_rep_signature']);
											//$file_name = $split_file_path[count($split_file_path) - 1];?>
										<a href="<?php //echo $firm_data['first_rep_signature']; ?>"><?php //echo $file_name; ?></a>
							

							
							<b>Representative 2:</b><br>
							Name: 		<?php //echo $firm_data['second_rep_f_name']; ?> 
										<?php //echo $firm_data['second_rep_m_name']; ?>
										<?php //echo $firm_data['second_rep_l_name']; ?><br>
							Mobile No. <?php //echo $firm_data['second_rep_mobile']; ?><br>
							Signature: <?php //$split_file_path = explode("/",$firm_data['second_rep_signature']);
											//$file_name = $split_file_path[count($split_file_path) - 1];?>
										<a href="<?php //echo $firm_data['second_rep_signature']; ?>"><?php //echo $file_name; ?></a>
							
						</td>
					</tr>
					
				-->	
					
					
					

					
					<tr>
						<td style="padding:10px; vertical-align:top;">11(b)(ii). Layout /Plan of premise of printing Unit.  With different section & dimensions is attached? </td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($premises_data['layout_plan_attached'])){ echo ucfirst($premises_data['layout_plan_attached']); }else{ echo 'NA'; } ?></td>
					</tr>
					
					<?php if($premises_data['layout_plan_attached'] == 'yes'){?>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">11(b)(iii). Layout /Plan Relevant Document:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($premises_data['layout_plan_docs'])){  $split_file_path = explode("/",$premises_data['layout_plan_docs']);
																	$file_name = $split_file_path[count($split_file_path) - 1];?>
																<a href="<?php echo $premises_data['layout_plan_docs']; ?>"><?php echo substr($file_name,23); ?></a><?php }else{ echo 'NA'; } ?></td>
					</tr>
					
					<?php } ?>
					
					
					
					
					
					
					
					
					<!-- commented by pravin 18/3/2017
					<tr>
						<td style="padding:10px; vertical-align:top;">24. Signature of Applicant/ Authorized person. </td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $firm_data['customer_signature']; ?></td>
					</tr>
					
					-->
					
					
					
					
					
					
					
					
					
					
					
					<!-- commented by pravin 18/3/2017
					<tr>
						<td style="padding:10px; vertical-align:top;">26. Name of commodities for which printed packaging material shall be used</td>
						<td style="padding:10px; vertical-align:top;">
							<?php

							//$i=1;	
						//	foreach($commodity_name_list as $commodity_name){ ?>
							
								<b><?php //echo $i.'.'. $commodity_name['Dmi_commodity']['commodity_name']; ?></b>
								<ol>
									<?php 
	
										//foreach($sub_commodity_data as $sub_commodity){ ?>
									
										<?php //if($sub_commodity['commodity_id'] == $commodity_name['Dmi_commodity']['id']){?>
										
											<li><?php //echo $sub_commodity['sub_comm_name']; ?></li>
											
										<?php // } ?>
									
									<?php //  } ?>
									
								</ol>
								
							<?php //$i=$i+1; } ?>
						</td>
					</tr>
					
					-->
					
					
					
					
					<!--commented by Pravin 18/3/2017
					<tr>
						<td style="padding:10px; vertical-align:top;">27. Whether processing fee is deposited? </td>
						<td style="padding:10px; vertical-align:top;"><?php //echo $payment_data['amount']; ?></td>
					</tr>
					
					-->
					
					<tr>
						<td style="padding:10px; vertical-align:top;">12. Date from which the Press is proposed to be engaged for printing of Agmark replicas on all types of containers/packages</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($premises_data['layout_plan_docs'])){  $press_proposed_date = explode(' ',$printing_unit_detail['press_proposed_date']);
																			echo $press_proposed_date[0]; }else{ echo 'NA'; } ?></td>																	
																 
					</tr>
	
			</table>	
			
			<br><br>
			
			<!--Add the Digital/ E-signed by applicant (by pravin 13/05/2017)-->
			<!--<table align="right">	
					<tr>
						<td>E-signed By<td>
						
					<tr>
					<tr>
					<td><?php //echo $customer_firm_data['firm_name']; ?><br>
							<?php //echo $customer_firm_data['street_address'].', ';
								  //echo $firm_district_name.', ';
								  //echo $firm_state_name.', ';
								  //echo $customer_firm_data['postal_code']; ?>
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
			
	<?php //if($show_esigned_by=='yes'){ ?><!-- Condition added on 27-03-2018 by Amol -->		
			<table align="right">	
					
					<tr>
					<td><?php echo $customer_firm_data['firm_name'].', <br>'; ?> <?php echo $customer_firm_data['street_address'].', <br>';
											echo $firm_district_name.', ';
											echo $firm_state_name.', ';
											echo $customer_firm_data['postal_code'].'.<br>';?>
					</td>
					</tr>
			</table>
	<?php //} ?>		

		
		
	