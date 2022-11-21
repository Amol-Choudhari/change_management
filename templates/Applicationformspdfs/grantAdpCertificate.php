
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
			<h4>Certificate of Approval of Designated Person</h4>
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
       <td><?php echo $customer_firm_data['firm_name'].',<br>';
        echo $customer_firm_data['street_address'].', <br>'; 
        echo $firm_district_name.', '; 
        echo $firm_state_name.', ';?>
       <?php echo $customer_firm_data['postal_code'].'.<br>'; 
       Date:  echo $pdf_date; ?></td>
	</tr>

    <tr>
		<td><br>Subject: Grant of approval of authorized signatory for CAG issuance.</td><br>
	</tr>

    <tr>
		<td><br>Dear Applicant,</td><br>
	</tr>

    <tr>
		<td><br>We have granted approval for below
            mentioned persons to issue CAG for grading and marking of agricultural commodities for [type of the certification approved by DMI]  in accordance with the provision of agriculture
            produce (Grading and Marking) Act, 1937 and rules made there under.</td>
	</tr>
        
    <tr>
		<td><br><br>List of the designated persons approved:</td><br>
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
		<td align="right"><br><strong>The Deputy Agriculture Marketing Director</strong><br>
                           Incharge-Regional Office<br>
                           Directorate of Marketing & Inspection<br>
                           (Ministry of Agriculture & Farmers Welfare)<br>
				<?php echo $get_office['ro_office']; ?>,<?php echo $firm_state_name; ?>
				
		</td>
	</tr>
</table>	
