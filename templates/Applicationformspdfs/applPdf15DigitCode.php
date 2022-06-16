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

	<?php
	$i=0;
	$sub_commodities_array = array();	
	foreach($sub_commodity_data as $sub_commodity){
		
		$sub_commodities_array[$i] = $sub_commodity['commodity_name'];
	$i=$i+1;
	} 
	
	$sub_commodities_list = implode(',',$sub_commodities_array);
	?>

	<table width="100%" border="1">
		<tr>
		<td align="center" style="padding:5px;">		
			<h4>Application for Grant of Approval to use 15 Digit Code</h4>
		</td>
		</tr>
	</table>

	
	
	<table width="100%">	
		<tr>
			<td><br>To,</td><br>
		</tr>	
	</table>		

	<table  width="100%">

		<tr>
		
			<td>
				<br>
				Dy Agriculture Marketing Adviser<br>
				Incharge-Regional Office<br>
				Directorate of Marketing & Inspection<br>
				(Ministry of Agriculture & Farmers Welfare)<br>
				<?php echo $get_office['ro_office']; ?>,<?php echo $firm_state_name; ?>
			</td>
		</tr>
		
		<tr>
			<td><br>Sir,</td><br>
		</tr>
		<tr>
			<td><br>I/We <?php echo $customer_firm_data['firm_name']; ?> of M/s <?php echo $customer_firm_data['street_address'].', '; echo $firm_district_name.', '; echo $firm_state_name.', '; echo $customer_firm_data['postal_code']; ?> being desirous of marking <?php echo $sub_commodities_list;?> with a grade designation mark in accordance with the rules made under the provisions of Agricultural Produce (Grading & Marking) Act, 1937, hereby, request for grant of Certificate of Approval to use 15 Digit Code.</td>
		</tr>
		
		<tr>
			<td><br>I/We have carefully gone through the provisions of Agricultural Produce (Grading & Marking) Act, 1937, the General Grading & Marking Rules 1988, relevant Commodity Grading & Marking Rules and the instructions issued by the Agricultural Marketing Adviser to the Govt. of India or an Officer authorised by him in this regard for grading & marking of the said commodity and agree to abide by them.</td>
		</tr>
		
		<tr>
			<td><br>With maintaining the guidlines as follows:</td>
		</tr>
		
		<tr>
			<td><br>
				1.	having inbuilt and automatic system of control and fast speed automatic packing lines.<br>
				2.	Separate records maintained in separate sections of unit by different section in-charges as.<br>
				&nbsp;&nbsp;	a.	Copies of letters placing order for replica printing.<br>
				&nbsp;&nbsp;	b.	Copies of printing order carried out by the printing press.<br>
				&nbsp;&nbsp;	c.	Stock register of empty containers (packing material).<br>
				&nbsp;&nbsp;	d.	Issue register of empty containers size-wise and commodity-wise.<br>
				&nbsp;&nbsp;	e.	Stock register of raw material.<br>
				&nbsp;&nbsp;	f.	Issue register of raw material.<br>
				&nbsp;&nbsp;	g.	Register showing daily production.<br>
				&nbsp;&nbsp;	h.	Register showing date-wise and packsize-wise damaged containers, if any (during packing).<br>
				&nbsp;&nbsp;	i.	Stock register in the store room/cold storage showing daily stock.<br>
				&nbsp;&nbsp;	j.	Sale register/sale invoice.<br>
				3.	Copies of all relevant documents uploaded alongwith the application.<br>
				5.	Have graded 100% of the production of the commodity.<br>
				6.	The packed graded commodity are kept in a store room exclusively meant for the products graded under Agmark.<br>
				7.	A register duly certified by the DMI officer shall be maintained in the store room indicating the stock in the store room.<br>

			</td>
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
					Place: <?php echo $firm_district_name.', '; echo $firm_state_name.'.';?><br>
					Date: <?php echo $pdf_date;?>
				</td>
			</tr>
	</table>	

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
	
		