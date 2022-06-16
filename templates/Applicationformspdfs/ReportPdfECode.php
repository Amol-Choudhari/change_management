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
			<td align="center" style="padding:5px;"><h4>Inspection Report for Grant & Issue of E-Code</h4></td>
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
			<td style="padding:10px; vertical-align:top;">1. Name of Packer :</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $firm_detail['firm_name']; ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">2. Address of the Packer</td>
			<td style="padding:10px; vertical-align:top;"><?php echo $firm_detail['street_address'].','; ?><br>
														 <?php	echo $firm_district_value.',';
																echo $firm_state_value.',';  														 
																echo $firm_detail['postal_code']; ?><br>
														<?php	if(!empty($firm_detail['email'])){ echo base64_decode($firm_detail['email']); }else{ echo 'NA'; }  //for email encoding ?><br>
														<?php	if(!empty($firm_detail['mobile_no'])){ echo base64_decode($firm_detail['mobile_no']); }else{ echo 'NA'; }  ?>												
														</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">3. Packer has inbuilt and automatic system of control and fast speed automatic packing lines:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['is_automatic_system'])){ echo $report_detail['is_automatic_system']; }else{ echo 'NA'; } ?><br>
														<?php if(!empty($report_detail['automatic_system_docs'])){ $split_file_path = explode("/",$report_detail['automatic_system_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];
														?>
														Provided Docs: <a href="<?php echo $report_detail['automatic_system_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
														</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4. Separate records has been maintained in separate sections of unit by different section in-charges:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['is_separate_records'])){ echo $report_detail['is_separate_records']; }else{ echo 'NA'; } ?><br>
														<?php if(!empty($report_detail['separate_records_docs'])){ $split_file_path = explode("/",$report_detail['separate_records_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];
														?>
														Provided Docs: <a href="<?php echo $report_detail['separate_records_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
														</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4(a). Copies of letters placing order for replica printing:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['is_copy_of_orders'])){ echo $report_detail['is_copy_of_orders']; }else{ echo 'NA'; } ?><br>
														<?php if(!empty($report_detail['copy_of_orders_docs'])){ $split_file_path = explode("/",$report_detail['copy_of_orders_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];
														?>
														Provided Docs: <a href="<?php echo $report_detail['copy_of_orders_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
														</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4(b). Copies of printing order carried out by the printing press:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['is_copy_of_printing'])){ echo $report_detail['is_copy_of_printing']; }else{ echo 'NA'; } ?><br>
														<?php if(!empty($report_detail['copy_of_printing_docs'])){ $split_file_path = explode("/",$report_detail['copy_of_printing_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];
														?>
														Provided Docs: <a href="<?php echo $report_detail['copy_of_printing_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
														</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4(c). Stock register of empty containers (packing material):</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['reg_of_empty_container'])){ echo $report_detail['reg_of_empty_container']; }else{ echo 'NA'; } ?><br>
														<?php if(!empty($report_detail['empty_container_docs'])){ $split_file_path = explode("/",$report_detail['empty_container_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];
														?>
														Provided Docs: <a href="<?php echo $report_detail['empty_container_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
														</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4(d). Issue register of empty containers size-wise and commodity-wise:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['issue_of_empty_container'])){ echo $report_detail['issue_of_empty_container']; }else{ echo 'NA'; } ?><br>
														<?php if(!empty($report_detail['issue_of_empty_container_docs'])){ $split_file_path = explode("/",$report_detail['issue_of_empty_container_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];
														?>
														Provided Docs: <a href="<?php echo $report_detail['issue_of_empty_container_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
														</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4(e). Stock register of raw material:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['reg_of_raw_materials'])){ echo $report_detail['reg_of_raw_materials']; }else{ echo 'NA'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4(f). Register Showing Daily Production:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['reg_daily_production'])){ echo $report_detail['reg_daily_production']; }else{ echo 'NA'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4(g). Registers maintained in packing section showing daily account of quantity packed size-wise:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['reg_daily_account_qty'])){ echo $report_detail['reg_daily_account_qty']; }else{ echo 'NA'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4(h). Register showing date-wise and packsize-wise damaged containers, if any (during packing):</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['reg_damaged_container'])){ echo $report_detail['reg_damaged_container']; }else{ echo 'NA'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4(i). Stock register in the store room/cold storage showing daily stock:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['reg_showing_daily_stock'])){ echo $report_detail['reg_showing_daily_stock']; }else{ echo 'NA'; } ?></td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">4(j). Sale register/sale invoice:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['reg_sale_invoice'])){ echo $report_detail['reg_sale_invoice']; }else{ echo 'NA'; } ?><br>
														<?php if(!empty($report_detail['reg_sale_invoice_docs'])){ $split_file_path = explode("/",$report_detail['reg_sale_invoice_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];
														?>
														Provided Docs: <a href="<?php echo $report_detail['reg_sale_invoice_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
														</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">5. Packer should have graded during the previous year a minimum prescribed quantity for each commodity:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['graded_min_quantity'])){ echo $report_detail['graded_min_quantity']; }else{ echo 'NA'; } ?><br>
														<?php if(!empty($report_detail['graded_min_qty_docs'])){ $split_file_path = explode("/",$report_detail['graded_min_qty_docs']);
														$file_name = $split_file_path[count($split_file_path) - 1];
														?>
														Provided Docs: <a href="<?php echo $report_detail['graded_min_qty_docs']; ?>"><?php echo substr($file_name, 23); ?></a><?php }else{ echo 'NA'; }  ?>
														</td>
		</tr>
		
		<tr>
			<td style="padding:10px; vertical-align:top;">6. Packer shall have to grade 100% of the production of the commodity:</td>
			<td style="padding:10px; vertical-align:top;"><?php if(!empty($report_detail['grade_100_per_prod'])){ echo $report_detail['grade_100_per_prod']; }else{ echo 'NA'; } ?></td>
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
