
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
	
	
	<!-- Below new table for FORM A added on 22-08-2017 by Amol -->
	
	<table width="100%" border="1">	

			<tr>				
				<td align="center"><h4>FORM A4</h4></td>
			</tr>

	</table>
	
	
	
	
		<table width="100%" border="1">
			<tr>
			<td align="center" style="padding:5px;">
				<?php 
				if($ca_bevo_applicant == 'no'){					
					if($export_unit_status == 'yes'){?>
					<h4>Application For Renewal Of Certificate Of Authorisation To Grade And Mark for Export</h4>
					<?php }else{ ?>
					<h4>Application For Renewal Of Certificate Of Authorisation To Grade And Mark for Internal Trade</h4>
				<?php } 				
				}elseif($ca_bevo_applicant == 'yes'){ ?>
					<h4>Application For Renewal Of Certificate Of Authorisation To Grade And Mark for Blended Edible Vegetable Oils/Fat Spread</h4>
				<?php } ?>
			</td>
			</tr>
		</table>

		
		
		
		
		<table width="100%" border="1">
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
						<td><br>The Dy./Asstt.Agril. Marketing Adviser<br>
							Senior Marketing Officer<br>
							Directorate of Marketing and Inspection<br>
							<?php echo $get_office['ro_office']; ?>,<?php echo $state_value; ?></td>
					</tr>	
					

					<tr>	
					<td><br>Sub:-  Renewal of C.A.No. <?php echo $customer_id;?>  granted to grade and mark <?php 
																								$i = 1;
																								foreach($commodities as $each_commodity) { 
																								echo $each_commodity.','; 
																								$i = $i+1; } ?> under Agmark.</td><br>

					</tr>
					
					<tr>
					<td><br>Sir,</td><br>
					</tr>	
					
					
					<tr>
						<td><br>I/We  have been granted the Certificate of  Authorisation  Number <?php echo $customer_id;?> for grading and marking of
						<?php $i = 0; foreach($commodities as $each_commodity) { echo $each_commodity.','; $i = $i+1; } ?> in accordance with the rules made under the provisions of the Agricultural Produce (Grading & Marking) Act, 1937. The validity  of the C.A. expires on <?php if(!empty($certificate_valid_upto)){  echo $certificate_valid_upto; }else{ echo 'NA'; }  ?>. I/We desire to continue the grading and marking under Agmark and accordingly request you to renew our Certificate of Authorisation for further period of five years. I/We are submitting herewith the C.A. book number <?php echo $customer_id;?> alongwith the renewal fee of Rs. <?php if(!empty($total_charges)){ echo $total_charges; }else{ echo 'NA'; }  ?> through online paymentreference No.  <?php if(!empty($applicant_payment_detail['transaction_id'])){ echo $applicant_payment_detail['transaction_id']; }else{ echo 'NA'; }  ?>    dated  <?php if(!empty($applicant_payment_detail['transaction_date'])){ $payment_date = explode(' ',$applicant_payment_detail['transaction_date']); echo $payment_date[0]; }else{ echo 'NA'; } ?> .</td>
						
					</tr>
					
					
				<!--	<tr>
						<td><br>I/We are furnishing here under the particulars of grading work carried out during the last validity period (last five years).</td>
					</tr>-->
					
					
			</table>	
					
<!-- commented the code to display extra details from renewal application, as no form will be filled by applicant in renewal application
as per new order, applied on 19-10-2021 by Amol-->
		<!--	<table width="100%" border="1">
	
				<tr align="center">
					<th style="border:1px solid gray"><b>Commodity</b>.</th>
					<th style="border:1px solid gray"><b>Financial Year</b></th>
					<th style="border:1px solid gray"><b>Quantity Graded(In Qtls.)</b></th>
				</tr>
						
				<?php /*$i=0; foreach($commodities as $each_commodity){ ?>
					<tr>
						<td style="border:1px solid gray; text-align:center;">
							<?php if(!empty($each_commodity)){ echo $each_commodity; }else{ echo 'NA'; }  ?>
						</td>
						
						<td style="border:1px solid gray; text-align:center;">									
							<table border="1" >
								<?php foreach($year as $each_year){?>
									<tr style="border:1px solid gray; height:25px; text-align:center;">
										<td><?php if(!empty($each_year)){ echo $each_year; }else{ echo 'NA'; } ?></td>
									</tr>													
								<?php } ?>
							</table>									
						</td>
						
						<td style="border:1px solid gray; text-align:center;">	
							<table border="1" >																		
								<?php
								$qty_total = null;
								foreach($year as $each_year){ ?>
									<tr style="border:1px solid gray; height:25px; text-align:center;">
										<td><?php if(!empty($quantity_graded[$i])){ echo $quantity_graded[$i]; }else{ echo 'NA'; } ?></td>
									</tr>
								<?php 
								$qty_total = $quantity_graded[$i]+$qty_total;
								
								$i=$i+1; } ?>	
							</table>
							
							<p style="margin:0 !mportant;">Total:<span style="font-weight:bold; color:#000;" ><?php if(!empty($qty_total)){ echo $qty_total; }else{ echo 'NA'; } ?> Qtls.</span></p>
						</td>
					</tr>	
							
				<?php  }*/ ?>				

				
					
			</table>	-->
			

		<?php //if($show_esigned_by == 'yes'){ ?>
			<table align="right">	
					<tr><td></td></tr>
					<tr>
					<td><?php echo $firm_data['firm_name']; ?><br>
							<?php echo $firm_data['street_address'].', <br>';
								  echo $district_value['district_name'].', ';
								  echo $state_value.', ';
								  if(!empty($firm_data['postal_code'])){ echo $firm_data['postal_code']; }else{ echo 'NA'; } ?>
					</td>
					</tr>
			</table>
		<?php //} ?>
	