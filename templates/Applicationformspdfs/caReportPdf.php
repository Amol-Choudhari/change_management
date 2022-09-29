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
		<td align="center"><h4>
			<?php if($ca_bevo_applicant == 'no'){?>FORM A5
			<?php }elseif($ca_bevo_applicant == 'yes'){?>FORM E2 <?php } ?>
			</h4>
		</td>
	</tr>
</table>

<?php if($ca_bevo_applicant == 'no'){?>	

	<table width="100%" border="1">
		<tr>
		<td align="center" style="padding:5px;">
		
			<?php if($export_unit_status == 'yes'){ ?>
				<h4>Inspection Report for Grant of New Certificate of Authorisation for Grading & Marking of Agricultural Commodities Under Agmark for Export </h4>
			<?php }else{ ?>
				<h4>Inspection Report for Grant of New Certificate of Authorisation for Grading & Marking of Agricultural Commodities Under Agmark for Internal Trade </h4>
			<?php }?>
		</td>
		</tr>
	</table>

<?php }elseif($ca_bevo_applicant == 'yes'){ ?>		
	
	<table width="100%" border="1">
		<tr>
		<td align="center" style="padding:5px;">
			<h4>Inspection Report for Grant of New Certificate of Authorisation for Grading & Marking of Blended Edible Vegetable Oils/Fat Spread </h4>
		</td>
		</tr>
	</table>	
<?php } ?>	

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
			<!-- for Non BEVO applications -->		
			<?php if($ca_bevo_applicant == 'no'){ ?>
			
					<tr>
					<td style="padding:10px; vertical-align:top;">1. Name of the commodity/ies proposed to be graded under Agmark</td>
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
					<td style="padding:10px; vertical-align:top;">2. Application is for Grant of C.A/Change of Grading Premises/Inclusion of Additional Premises etc.</td>
					<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_certificate_type)){ echo $firm_certificate_type; }else{ echo 'NA'; } ?></td>
				</tr>
				
				
				
				<tr>
					<td style="padding:10px; vertical-align:top;">3. Name of the Firm</td>
					<td style="padding:10px; vertical-align:top;"><?php echo $customer_firm_data['firm_name']; ?> <br>
						<?php echo $customer_firm_data['street_address'].', ';
								echo $firm_district_name.', ';
								echo $firm_state_name.', ';
								echo $customer_firm_data['postal_code'].'.<br>';
								echo 'Email: '.base64_decode($customer_firm_data['email']).',<br>'; //for email encoding
								echo 'Phone: '.base64_decode($customer_firm_data['mobile_no']).',<br>';
								if(!empty($customer_firm_data['fax_no'])){ echo 'Landline: '.base64_decode($customer_firm_data['fax_no']); }else{ echo 'NA'; } 
								
						?>
					</td>
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
							<?php $i=1; foreach($added_directors_details as $each_detail){ ?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php echo $i; ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_name'])){ echo $each_detail['d_name'];  }else{ echo 'NA'; }  ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_address'])){ echo $each_detail['d_address']; }else{ echo 'NA'; }  ?></td>

								</tr>
							<?php $i=$i+1;} ?>
							
						</table>
					</td>
				</tr>
		
				

					<tr>
						<td style="padding:10px; vertical-align:top;">5. Address of the premises where grading & marking will be undertaken</td>
						<td style="padding:10px; vertical-align:top;">
							<?php echo $premises_data[0]['street_address'].', ';
									echo $premises_district_name.', ';
									echo $premises_state_name.', ';
									echo $premises_data[0]['postal_code'];
			
							?>
						</td>
					</tr>

					<tr>
						<td style="padding:10px; vertical-align:top;">6. Details of the size of the rooms where grading and marking will be done.(Site plan duly signed by authorized person of the firm and counter-signed by the Inspecting Officer should be enclosed).</td>
						<td style="padding:10px; vertical-align:top;">Site Plan No. <?php if(!empty($premises_details[0]['room_site_plan_no'])){ echo $premises_details[0]['room_site_plan_no']; }else{ echo 'NA'; } ?> <br /> 
						
								<?php if(!empty($premises_details[0]['room_details_docs'])){ $split_file_path = explode("/",$premises_details[0]['room_details_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
								Provided Docs: <a href="<?php echo $premises_details[0]['room_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
							
						</td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">7. Details of the machinery for the processing and packing of the commodity (Name of the firm may be mentioned if processing is not through own machinery).</td>
						<td style="padding:10px; vertical-align:top;">Machineries are own: <?php if(!empty($other_details[0]['own_machinery'])){ echo ucfirst($other_details[0]['own_machinery']); }else{ echo 'NA'; } ?> <br /> 
							
							<?php if($other_details[0]['own_machinery']=='no'){ ?>
							
								Processing done by: <?php if(!empty($other_details[0]['processing_done_by'])){ echo $other_details[0]['processing_done_by']; }else{ echo 'NA'; } ?> <br /> 
							
								<?php if(!empty($other_details[0]['machinery_processing_docs'])){ $split_file_path = explode("/",$other_details[0]['machinery_processing_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
							Provided Docs: <a href="<?php echo $other_details[0]['machinery_processing_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; } } ?></td>
						
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">8. Details of storage tanks/ rooms for the storage of the commodity/ies.</td>
						<td style="padding:10px; vertical-align:top;">Storage Plan No. <?php if(!empty($premises_details[0]['storage_site_plan_no'])){ echo $premises_details[0]['storage_site_plan_no']; }else{ echo 'NA'; } ?> <br /> 
						
								<?php if(!empty($premises_details[0]['storage_details_docs'])){ $split_file_path = explode("/",$premises_details[0]['storage_details_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
								Provided Docs: <a href="<?php echo $premises_details[0]['storage_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
								<br>
								<table width="100%" border="1">
									<tr>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Room No./Tank No.</th><!-- Updated on 17-08-2022 as per UAT suggestion-->
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Type/Shape</th>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Size</th>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Capacity</th>
									</tr>
									<?php 
									$i=1; 
									foreach($premises_details[1][2] as $each_tank){?>
										<tr>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_no'])){ echo $each_tank['tank_no']; }else{ echo 'NA'; } ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($premises_details[1][1][$i])){ echo $premises_details[1][1][$i]; }else{ echo 'NA'; } ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_size'])){ echo $each_tank['tank_size']; }else{ echo 'NA'; } ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_capacity'])){ echo $each_tank['tank_capacity']; }else{ echo 'NA'; } ?></td>
										</tr>
									<?php 
									$i=$i+1;} ?>
									
								</table>
							
						</td>
					</tr>
	
										
					<tr>
						<td style="padding:10px; vertical-align:top;">9. Whether locking arrangements are adequate for storage of commodity/ies.</td>
						<td style="padding:10px; vertical-align:top;">Arrangements are adequate: <?php if(!empty($premises_details[0]['locking_adequate'])){ echo ucfirst($premises_details[0]['locking_adequate']); }else{ echo 'NA'; } ?> <br /> 
							Details if any: <?php if(!empty($premises_details[0]['locking_details_docs'])){ echo $premises_details[0]['locking_details']; }else{ echo 'NA'; } ?> <br /> 
						
							<?php if($premises_details[0]['locking_adequate']=='yes'){ ?>
							
								<?php if(!empty($premises_details[0]['locking_details_docs'])){ $split_file_path = explode("/",$premises_details[0]['locking_details_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
								Provided Docs: <a href="<?php echo $premises_details[0]['locking_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
							<?php } ?>
						</td>
					</tr>
					


					<tr>
						<td style="padding:10px; vertical-align:top;">10. Whether the grading premises is adequately lighted, well ventilated and hygienic.</td>
						<td style="padding:10px; vertical-align:top;">Lighted, Ventilated & Hygienic: <?php if(!empty($premises_details[0]['lighted_ventilated'])){ echo ucfirst($premises_details[0]['lighted_ventilated']); }else{ echo 'NA'; } ?> <br /> 
							Details if any: <?php if(!empty($premises_details[0]['ventilation_details'])){ echo $premises_details[0]['ventilation_details']; }else{ echo 'NA'; } ?> <br /> 
						
							<?php if($premises_details[0]['lighted_ventilated']=='yes'){ ?>
							
								<?php if(!empty($premises_details[0]['ventilation_details_docs'])){ $split_file_path = explode("/",$premises_details[0]['ventilation_details_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
								Provided Docs: <a href="<?php echo $premises_details[0]['ventilation_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
							
							<?php } ?>
						</td>
					</tr>

					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">11. Whether grading premises fulfills the conditions as stipulated in the relevant commodity grading and marking rules.</td>
						<td style="padding:10px; vertical-align:top;">Conditions Fullfilled: <?php if(!empty($premises_details[0]['conditions_fulfilled'])){ echo ucfirst($premises_details[0]['conditions_fulfilled']); }else{ echo 'NA'; } ?> <br /> 
							Details if any: <?php echo $premises_details[0]['condition_details']; ?> <br /> 
						
							<?php if($premises_details[0]['conditions_fulfilled']=='yes'){ ?>
							
								<?php if(!empty($premises_details[0]['condition_details_docs'])){ $split_file_path = explode("/",$premises_details[0]['condition_details_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
								Provided Docs: <a href="<?php echo $premises_details[0]['condition_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
							
							<?php } ?>
						</td>
					</tr>
					


					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">12. Quantity of the commodity/ies proposed to be graded under Agmark in a month.</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($other_details[0]['commodity_quantity'])){ echo $other_details[0]['commodity_quantity'];  }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
									
					
					<tr>
						<td style="padding:10px; vertical-align:top;">13. Arrangement made for the analysis of the proposed commodity/ies.</td>
						<td style="padding:10px; vertical-align:top;">laboratory type: <?php if(!empty($laboratory_type_name)){ echo $laboratory_type_name;  }else{ echo 'NA'; } ?> <br />
								 			
								<?php if(!empty($form_laboratory_data[0]['consent_letter_docs'])){ $split_file_path = explode("/",$form_laboratory_data[0]['consent_letter_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
								Consent Letter: <a href="<?php echo $form_laboratory_data[0]['consent_letter_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
						</td>
					</tr>
						
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">14. Name and address of the laboratory if analysis of samples is proposed to be done through  State Grading Laboratory/ Commercial Laboratory.</td>
						<td style="padding:10px; vertical-align:top;">Lab Name: <?php if(!empty($form_laboratory_data[0]['laboratory_name'])){ echo $form_laboratory_data[0]['laboratory_name']; }else{ echo 'NA'; } ?> <br />
									<?php echo $form_laboratory_data[0]['street_address'].', ';
										echo $lab_district_name.', ';
										echo $lab_state_name.', ';
										echo $form_laboratory_data[0]['postal_code'].'.<br>';
										echo 'Email: '.base64_decode($form_laboratory_data[0]['lab_email_id']).',<br>'; //for email encoding
										echo 'Phone: '.base64_decode($form_laboratory_data[0]['lab_mobile_no']).',<br>';
										if(!empty($form_laboratory_data[0]['lab_fax_no'])){ echo 'Landline: '.base64_decode($form_laboratory_data[0]['lab_fax_no']); }else{ echo 'NA'; } ?>
						</td>
					</tr>
					

					
					<tr>

						<td style="padding:10px; vertical-align:top;">15. If the firm has set up its own laboratory, state:</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($form_laboratory_data[0]['laboratory_name'])){ echo ucfirst($laboratory_type_name); }else{ echo 'NA'; } ?></td>
					</tr>
					
					
					<?php if($laboratory_type_name == 'Own Laboratory'){ ?>
						<tr>
							<td style="padding:10px; vertical-align:top;">15(a). Whether it is properly equipped for analysis of samples of the commodity/ies proposed to be graded under Agmark.</td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_lab_details[0]['laboratory_equipped'])){ echo ucfirst($report_lab_details[0]['laboratory_equipped']); }else{ echo 'NA'; } ?></td>
						</tr>
						
						
						<tr>
							<td style="padding:10px; vertical-align:top;">15(b). If not properly equipped, point out short comings</td>
							<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_lab_details[0]['lab_shortcomings'])){ echo $report_lab_details[0]['lab_shortcomings']; }else{ echo 'NA'; } ?></td>
						</tr>
						
						<tr>
							<td style="padding:10px; vertical-align:top;">15(c). Provided Documents:</td>
							<td style="padding:10px; vertical-align:top;">Document Ref. no. <?php if(!empty($report_lab_details[0]['lab_doc_ref_no'])){ echo $report_lab_details[0]['lab_doc_ref_no']; }else{ echo 'NA'; } ?><br />
							
								<?php if(!empty($report_lab_details[0]['lab_doc_ref_no'])){ $split_file_path = explode("/",$report_lab_details[0]['laboratory_equipped_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
								Provided Docs: <a href="<?php echo $report_lab_details[0]['laboratory_equipped_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
						</tr>
					<?php } ?>
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">16. Name of the TBL(s) which will be used for graded products under Agmark.</td>
					
					
						<td style="padding:10px; vertical-align:top;">
							<table width="100%" border="1">
								<tr>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Name</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Is Registered?</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Reg. No.</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Document</th>
								</tr>
						
							<?php foreach($other_details[2][0] as $each_tbl){?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_name'])){ echo $each_tbl['tbl_name']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_registered'])){ echo $each_tbl['tbl_registered']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_registered_no'])){ echo $each_tbl['tbl_registered_no']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_registration_docs'])){ $split_file_path = explode("/",$each_tbl['tbl_registration_docs']);
																			$file_name = $split_file_path[count($split_file_path) - 1];?>
																		<a href="<?php echo $each_tbl['tbl_registration_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
								</tr>
							<?php } ?>
							</table>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">17. Remarks, if any</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($other_details[0]['other_points'])){ echo $other_details[0]['other_points']; }else{ echo 'NA'; } ?>
						</td>
					</tr>
					

					<tr>
						<td style="padding:10px; vertical-align:top;">18. Recommendations</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($other_details[0]['recommendations'])){ echo $other_details[0]['recommendations']; }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
			




			<!-- for BEVO applications -->
					
			<?php }elseif($ca_bevo_applicant == 'yes'){ ?>	
			
					<tr>
						<td style="padding:10px; vertical-align:top;">1. Application is for Grant of C.A/Change of Grading Premises/Inclusion of Additional Premises etc.</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($firm_certificate_type)){ echo $firm_certificate_type; }else{ echo 'NA'; } ?></td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">2. Name of the Firm</td>
						<td style="padding:10px; vertical-align:top;"><?php echo $customer_firm_data['firm_name']; ?> <br>
							<?php echo $customer_firm_data['street_address'].', ';
									echo $firm_district_name.', ';
									echo $firm_state_name.', ';
									echo $customer_firm_data['postal_code'].'.<br>';
									echo 'Email: '.base64_decode($customer_firm_data['email']).',<br>'; //for email encoding
									echo 'Phone: '.base64_decode($customer_firm_data['mobile_no']).',<br>';
									if(!empty($customer_firm_data['fax_no'])){ echo 'Landline: '.base64_decode($customer_firm_data['fax_no']); }else{ echo 'NA'; }
									
							?>
						</td>
					</tr>
					
					<tr>
						<td style="padding:10px; vertical-align:top;">3. Details of Directors/Proprietors/Partners</td>
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
										<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_detail['d_address'])){ echo $each_detail['d_address']; }else{ echo 'NA'; }  ?></td>

									</tr>
								<?php $i=$i+1;} ?>
								
							</table>
						</td>
					</tr>
		
			
					<tr>
						<td style="padding:10px; vertical-align:top;">4. Address of the premises where grading & marking of Blended Edible Vegetable Oil will be undertaken</td>
						<td style="padding:10px; vertical-align:top;">
							<?php echo $premises_data[0]['street_address'].', ';
									echo $premises_district_name.', ';
									echo $premises_state_name.', ';
									echo $premises_data[0]['postal_code'];
			
							?>
						</td>
					</tr>
			
		
					<tr>
						<td style="padding:10px; vertical-align:top;">5. Name and address of the Oil Mill where constituent oils shall be manufactured.</td>
						<td style="padding:10px; vertical-align:top;">
						
							<?php if(!empty($premises_details[0]['constituent_oil_mill_docs'])){  $split_file_path = explode("/",$premises_details[0]['constituent_oil_mill_docs']);
							$file_name = $split_file_path[count($split_file_path) - 1];
							?>
							Provided Docs: <a href="<?php echo $premises_details[0]['constituent_oil_mill_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					
					

					<tr>
						<td style="padding:10px; vertical-align:top;">6. Whether the premises is adequately lighted, well ventilated and hygienic.</td>
						<td style="padding:10px; vertical-align:top;">Lighted, Ventilated & Hygienic: <?php if(!empty($premises_details[0]['lighted_ventilated'])){ echo ucfirst($premises_details[0]['lighted_ventilated']); }else{ echo 'NA'; } ?> <br /> 
								Details if any: <?php if(!empty($premises_details[0]['ventilation_details'])){ echo $premises_details[0]['ventilation_details']; }else{ echo 'NA'; } ?> <br /> 
							
								<?php if($premises_details[0]['lighted_ventilated']=='yes'){ ?>
								
									<?php if(!empty($premises_details[0]['ventilation_details'])){ $split_file_path = explode("/",$premises_details[0]['ventilation_details_docs']);
									$file_name = $split_file_path[count($split_file_path) - 1];
									?>
									Provided Docs: <a href="<?php echo $premises_details[0]['ventilation_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
								<?php } ?>
						</td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">7. Details of the size of the rooms where storage, grading and marking will be done.(Site plan duly signed by authorized person of the firm and counter-signed by the Inspecting Officer should be enclosed).</td>
						<td style="padding:10px; vertical-align:top;">Site Plan No. <?php if(!empty($premises_details[0]['room_site_plan_no'])){ echo $premises_details[0]['room_site_plan_no']; }else{ echo 'NA'; }  ?> <br /> 
						
								<?php if(!empty($premises_details[0]['room_details_docs'])){ $split_file_path = explode("/",$premises_details[0]['room_details_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
								Provided Docs: <a href="<?php echo $premises_details[0]['room_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
							
						</td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">8(a). Details of the machinery available in the Oil Mill in case of Blended Edible Vegetable Oil.</td>
						<td style="padding:10px; vertical-align:top;">
						
						<?php if(!empty($other_details[0]['bevo_machinery_details_docs'])){ $split_file_path = explode("/",$other_details[0]['bevo_machinery_details_docs']);
						$file_name = $split_file_path[count($split_file_path) - 1];
						?>
						Provided Docs: <a href="<?php echo $other_details[0]['bevo_machinery_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">8(b). Whether minimum infrastructure/facilities are available in the plant as required in case of Fat Spread.</td>
						<td style="padding:10px; vertical-align:top;">Facilities available : <?php if(!empty($other_details[0]['fat_spread_facilitities'])){ echo ucfirst($other_details[0]['fat_spread_facilitities']); }else{ echo 'NA'; } ?> </td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">9. Details of the storage tanks with capacity of the each for different constituent oils.</td>
						<td style="padding:10px; vertical-align:top;">
								
								<table width="100%" border="1">
									<tr>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank No.</th>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank Shape</th>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank Size</th>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank Capacity</th>
									</tr>
									<?php
									$i=1;
									foreach($premises_details[2][2] as $each_tank){?>
										<tr>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_no'])){ echo $each_tank['tank_no']; }else{ echo 'NA'; } ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($premises_details[2][1][$i])){ echo $premises_details[2][1][$i]; }else{ echo 'NA'; } ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_size'])){ echo $each_tank['tank_size']; }else{ echo 'NA'; } ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_capacity'])){ echo $each_tank['tank_capacity']; }else{ echo 'NA'; } ?></td>
										</tr>
									<?php 
									$i=$i+1;} ?>
									
								</table>
							
						</td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">10. Whether separate pipe lines are provided for different oils and their storage tanks. </td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($premises_details[0]['separate_pipe_lines'])){ echo ucfirst($premises_details[0]['separate_pipe_lines']); }else{ echo 'NA'; } ?>
						</td>
					</tr>
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">11. Number and capacity of storage tanks meant for Blended Edible Vegetable Oils.</td>
						<td style="padding:10px; vertical-align:top;">
								
								<table width="100%" border="1">
									<tr>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank No.</th>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank Shape</th>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank Size</th>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Tank Capacity</th>
									</tr>
									<?php 
									$i=1;
									foreach($premises_details[3][2] as $each_tank){?>
										<tr>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_no'])){ echo $each_tank['tank_no']; }else{ echo 'NA'; }  ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($premises_details[3][1][$i])){ echo $premises_details[3][1][$i]; }else{ echo 'NA'; } ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_size'])){ echo $each_tank['tank_size']; }else{ echo 'NA'; }  ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tank['tank_capacity'])){ echo $each_tank['tank_capacity']; }else{ echo 'NA'; }  ?></td>
										</tr>
									<?php 
									$i=$i+1;} ?>
									
								</table>
							
						</td>
					</tr>
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">12. Whether locking arrangements are provided with the storage tanks both at inlets and outlets.</td>
						<td style="padding:10px; vertical-align:top;">Arrangements are provided: <?php if(!empty($premises_details[0]['locking_adequate'])){ echo ucfirst($premises_details[0]['locking_adequate']); }else{ echo 'NA'; } ?> <br /> 
							Details if any: <?php if(!empty($premises_details[0]['locking_details'])){ echo $premises_details[0]['locking_details']; }else{ echo 'NA'; }  ?> <br /> 
						
							<?php if($premises_details[0]['locking_adequate']=='yes'){ ?>
							
								<?php if(!empty($premises_details[0]['locking_details_docs'])){ $split_file_path = explode("/",$premises_details[0]['locking_details_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
								Provided Docs: <a href="<?php echo $premises_details[0]['locking_details_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
							<?php } ?>
						</td>
					</tr>
					
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">13. Quantity of Blended Edible Vegetable Oil proposed to be graded per month in MTs.</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($other_details[0]['bevo_quantity_per_month'])){ echo $other_details[0]['bevo_quantity_per_month']; }else{ echo 'NA'; }  ?></td>
					</tr>
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">14. Name and address of the firms/oil mills from which constituent oil will be procured with approximate quantity thereof.</td>
						<td style="padding:10px; vertical-align:top;">
								
								<table width="100%" border="1">
									<tr>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Oil Name</th>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Mill Name & Address</th>
										<th style="padding:10px;" width="25%" cellspacing="50" align="left">Quantity Procured</th>
									</tr>
									<?php foreach($other_details[4] as $each_mill){?>
										<tr>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_mill['oil_name'])){ echo $each_mill['oil_name']; }else{ echo 'NA'; } ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_mill['mill_name_address'])){ echo $each_mill['mill_name_address']; }else{ echo 'NA'; } ?></td>
											<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_mill['quantity_procured'])){ echo $each_mill['quantity_procured']; }else{ echo 'NA'; } ?></td>
										</tr>
									<?php } ?>
									
								</table>
							
						</td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">14(a). Declaration from constituents oil suppliers.</td>
						<td style="padding:10px; vertical-align:top;">
								<?php if(!empty($other_details[0]['constituent_oil_suppliers_docs'])){ $split_file_path = explode("/",$other_details[0]['constituent_oil_suppliers_docs']);
								$file_name = $split_file_path[count($split_file_path) - 1];
								?>
								Provided Docs: <a href="<?php echo $other_details[0]['constituent_oil_suppliers_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
						</td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">15. Important places where graded Blended Edible Vegetable Oils is proposed to be marketed. </td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($other_details[0]['constituent_oil_suppliers_docs'])){ echo $other_details[0]['graded_bevo_marketed_places']; }else{ echo 'NA'; } ?></td>

					</tr>
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">16. Whether the laboratory is fully equipped for the analysis of constituent oils and blended edible vegetable oil/Fat Spread.</td>
						<td style="padding:10px; vertical-align:top;">
								Laboratory Equipped: <?php if(!empty($other_details[0]['constituent_oil_suppliers_docs'])){ echo ucfirst($report_lab_details[0]['laboratory_equipped']); }else{ echo 'NA'; } ?><br />
						
								<?php if($report_lab_details[0]['laboratory_equipped']=='yes'){ ?>
								
									<?php if(!empty($report_lab_details[0]['laboratory_equipped_docs'])){ $split_file_path = explode("/",$report_lab_details[0]['laboratory_equipped_docs']);
									$file_name = $split_file_path[count($split_file_path) - 1];
									?>
									Provided Docs: <a href="<?php echo $report_lab_details[0]['laboratory_equipped_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
								<?php } ?>
						</td>
					</tr>
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">17. Name of the TBL(s) which will be used for graded products under Agmark.</td>
					
					
						<td style="padding:10px; vertical-align:top;">
							<table width="100%" border="1">
								<tr>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Name</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Is Registered?</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Reg. No.</th>
									<th style="padding:10px;" width="25%" cellspacing="50" align="left">Document</th>
								</tr>
						
							<?php foreach($other_details[2][0] as $each_tbl){?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_name'])){ echo $each_tbl['tbl_name']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_registered'])){ echo $each_tbl['tbl_registered']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_registered_no'])){ echo $each_tbl['tbl_registered_no']; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_tbl['tbl_registration_docs'])){ $split_file_path = explode("/",$each_tbl['tbl_registration_docs']);
																			$file_name = $split_file_path[count($split_file_path) - 1];?>
																		<a href="<?php echo $each_tbl['tbl_registration_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?></td>
								</tr>
							<?php } ?>
							</table>
						</td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">18. Shortcomings in the premises/laboratory, if any.</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_lab_details[0]['lab_shortcomings'])){ echo $report_lab_details[0]['lab_shortcomings']; }else{ echo 'NA'; } ?></td>
					</tr>
					
					
					
					<tr>
						<td style="padding:10px; vertical-align:top;">19. Recommendations</td>
						<td style="padding:10px; vertical-align:top;"><?php if(!empty($other_details[0]['recommendations'])){ echo $other_details[0]['recommendations']; }else{ echo 'NA'; } ?></td>
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
			
			
		<!--	<table width="35%" align="right">	
				<tr>
					<td>E-signed By<td>
					
				</tr>
				<tr>
					<td><?php //echo $user_full_name; ?>
				</td>
				</tr>
			</table>-->