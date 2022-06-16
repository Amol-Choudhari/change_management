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
			<td align="center"><h4>FORM B4</h4></td>
		</tr>
	</table>
		
	<table width="100%" border="1">
		<tr>
		<td align="center" style="padding:5px;">
			<h4>Application for Renewal of Permission for Printing of Agmark Replica</h4>
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
			<td><br>The Incharge<br>
				Regional /Sub-Office,<br>
				Directorate of Marketing & Inspection,<br>
				<?php echo $get_office['ro_office']; ?>,<?php echo $state_value; ?>
			</td>
		</tr>				

		<tr>	
			<td>
				<br>Subject:  Renewal of permission for printing of Agmark replica on <?php 
				$i=1;
				foreach($renewal_packaging_type as $packaging_type) { ?><?php if(!empty($packaging_type)){ echo $packaging_type.','; }else{ echo 'NA'; }  ?><?php $i=$i+1; } ?>  reg.
			</td>
		</tr>
		
		<tr>
			<td><br>Sir,</td><br>
		</tr>				
		
		<tr>
			<td><br>I/We have been permitted to print Agmark replica on <?php 
			$i=1;
			foreach($renewal_packaging_type as $packaging_type) { ?><?php if(!empty($packaging_type)){ echo $packaging_type.','; }else{ echo 'NA'; } ?><?php $i=$i+1; } ?>. The permission expires on <?php if(!empty($validity_upto)){ echo $validity_upto; }else{ echo 'NA'; } ?>
			</td>				
		</tr>
		
		<tr>
		<!--	<td><br>I/We desire to continue the printing of replica under Agmark for a further period of <?php //$renew_upto = explode('',$check_renewal_form_value['renew_upto']); echo $renew_upto[0]; ?><br></td>-->
			<td><br>I/We desire to continue the printing of replica under Agmark for a further period of 5 years.<br></td>
		</tr>
		
	<!--	<tr>
			<td><br>I/we am/are  furnishing the particulars of printing of replica carried out during the last  validity period as along with the renewal fee of Rs. 5000 through  online payment  reference No.    <?php if(!empty($applicant_payment_detail['transaction_id'])){ echo $applicant_payment_detail['transaction_id']; }else{ echo 'NA'; }  ?>  dated  <?php if(!empty($applicant_payment_detail['transaction_date'])){ $payment_date = explode(' ',$applicant_payment_detail['transaction_date']); echo $payment_date[0]; }else{ echo 'NA'; } ?> .under:<br><br></td>
		</tr>-->
	</table>
			
		<!--	<table  width="100%">
			
				<tr>
						
						<td style="padding:10px; vertical-align:top;"><br><br>
																	 <table width="100%" border="1">
							<tr>
								<th style="padding:10px;" width="40%" cellspacing="50" align="left"><b>Name of packer who had placed order for printing</b></th>
								<th style="padding:10px;" width="30%" cellspacing="50" align="left"><b>Type</b></th>
								<th style="padding:10px;" width="30%" cellspacing="50" align="left"><b>Total numbers of packages printed</b></th>
							</tr>
							<?php /*
							$i=1;
							foreach($section_form_details[3][0] as $each_packer){?>
								<tr>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_packer['packer_name'])){ echo $each_packer['packer_name']; }else{ echo 'NA'; }  ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($section_form_details[3][3][$i])){ echo $section_form_details[3][3][$i]; }else{ echo 'NA'; } ?></td>
									<td style="padding:10px; vertical-align:top;"><?php if(!empty($each_packer['quantity_printed'])){ echo $each_packer['quantity_printed']; }else{ echo 'NA'; } ?></td>
									</tr>
							<?php $i=$i+1; }*/ ?>
							
						</table></td>
					</tr>
			
			</table>-->
					
			
		<?php //if($show_esigned_by == 'yes'){ ?>
			<table align="right">	
					<tr><td></td></tr>
					<tr>
					<td><?php echo $firm_detail['firm_name']; ?><br>
							<?php echo $firm_detail['street_address'].', <br>';
								  echo $district_value['district_name'].', ';
								  echo $state_value.', ';
								  if(!empty($firm_detail['postal_code'])){ echo $firm_detail['postal_code']; }else{ echo 'NA'; }  ?>
					</td>
					</tr>
			</table>	
		<?php //} ?>
			

		
		
		
	