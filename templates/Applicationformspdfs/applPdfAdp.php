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


<table width="100%" border="1">
		<tr>
		<td align="center" style="padding:5px;">		
			<h4>Application for Approval of Designated Person</h4>
		</td>
		</tr>
</table>

<table width="100%"><br><br>	
		<tr>
			<td><br>To,</td><br>
		</tr>	
</table>	

<table  width="100%">
		<tr>
			<td>
				<br><strong>The Deputy Agriculture Marketing Director</strong><br>
				Incharge-Regional Office<br>
				Directorate of Marketing & Inspection<br>
				(Ministry of Agriculture & Farmers Welfare)<br>
				<?php echo $get_office['ro_office']; ?>,<?php echo $firm_state_name; ?>
			</td>
		</tr>
        
        <tr>
			<td><br>Subject: Application for approval of authorized signatory for CAG issuance.</td><br>
		</tr>

        <tr>
			<td><br>Dear Sir,</td><br>
		</tr>

        <tr>
			<td><br>I,<?php echo $lab_incharge_data['lab_ceo_name']; ?> of <?php echo $customer_firm_data['firm_name'].', '; echo $customer_firm_data['street_address'].','; echo $firm_district_name.', '; echo $firm_state_name.', '; echo $customer_firm_data['postal_code']; ?>  seek approval for below
            mentioned persons to issue CAG for grading and marking of agricultural commodities for <?php echo $customer_firm_data['firm_name']; ?>
			in accordance with the provision of agriculture
            produce (Grading and Marking) Act, 1937 and rules made there under.</td>
		</tr>
        <tr>
			<td><br><br>List of the designated persons to be approved:</td><br>
           
		</tr>
        <?php 
		$i=1;
		foreach($designated_person as $person_detail){?>
        <tr>
		<td><?php echo $i .")". " ". $person_detail['person_name'];?></td>
        </tr>
        <?php $i=$i+1;} ?>
</table>        

<table>					
	 <tr>
		<td  align="left"><br><br><br>
			Place: <?php echo $firm_district_name.', '; echo $firm_state_name.'.';?><br>
			Date: <?php echo $pdf_date;?>
		</td>
	</tr>
</table>
<table>	
	<tr>
		<td align="right">With Regards,<br>
            <?php echo $customer_firm_data['firm_name']; ?><br> 
			<?php echo $customer_firm_data['street_address'].', <br>';
				  echo $firm_district_name.', ';
				  echo $firm_state_name.', ';
				echo $customer_firm_data['postal_code'].'.<br>';?>
		</td>
	</tr>
</table>	
<div style="page-break-before:always"><br>
  <table>
    <tr>
		<td><br>Declaration:</td><br><br>                 
	</tr>

	<tr>
		<td><br>I,<?php echo $lab_incharge_data['lab_ceo_name']; ?> of <?php echo $customer_firm_data['firm_name'].', '; echo $customer_firm_data['street_address'].','; echo $firm_district_name.', '; echo $firm_state_name.', '; echo $customer_firm_data['postal_code']; ?>   hereby solemnly and 
             sincerely affirm and state that:
		</td>
	</tr>

	<tr>
		<td><br>List of the designated persons to be approved with designation, Laboratory name and Office name.</td><br>
	</tr>

	<tr>
		<td><br>Are designated persons for the purpose of issuing the certificate of Agmark on behalf of 
		<?php echo $customer_firm_data['firm_name'].', '; echo $customer_firm_data['street_address'].','; echo $firm_district_name.', '; echo $firm_state_name.', '; echo $customer_firm_data['postal_code']; ?></td><br>
	</tr>

	<tr>
		<td><br>I do hereby declare that they are responsible persons of the Laboratory, The laboratory will liable for the actions.</td><br>
	</tr>

	<tr>
		<td><br>I do hereby declare that as per record and belief of company they do not have criminal record.</td><br>
	</tr>

	<tr>
		<td><br>I do hereby declare that the above stated facts are true and correct and I have not surppressed any material facts relating to subject matter of declaration.</td><br>
	</tr>

	<tr>
		<td><br>Sworn and signed on behalf of <?php echo $customer_firm_data['firm_name'].', '; echo $customer_firm_data['street_address'].','; echo $firm_district_name.', '; echo $firm_state_name.', '; echo $customer_firm_data['postal_code']; ?></td><br>
	</tr>

  </table>		 

  <table>					
	 <tr>
		<td  align="left"><br><br><br>
			Place: <?php echo $firm_district_name.', '; echo $firm_state_name.'.';?><br>
			Date: <?php echo $pdf_date;?>
		</td>
	</tr>
</table>

<table>	
	<tr>
		<td align="right">Your Sincerely<br>
            <?php echo $customer_firm_data['firm_name']; ?><br> 
			<?php echo $customer_firm_data['street_address'].', <br>';
				  echo $firm_district_name.', ';
				  echo $firm_state_name.', ';
				echo $customer_firm_data['postal_code'].'.<br>';?>
		</td>
	</tr>
</table>	

</div> 

