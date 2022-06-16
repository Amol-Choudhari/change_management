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
					
					<td width="12%" align="center">
						<img width="35" src="img/logos/emblem.png">
					</td>
					<td width="76%" align="center">
						<h4>Government of India <br> Ministry of Agriculture and Farmers Welfare<br>
						Department of Agriculture Co-Operation & Farmers Welfare<br>
						Directorate of Marketing & Inspection</h4>
						
					</td>
					<td width="12%" align="center">
						<img src="img/logos/agmarklogo.png">
					</td>
					
			</tr>

	</table>

    <table width="100%" border="1">
        <tr><td align="center" style="padding:5px;"><h4>Approval/Allotement letter from DMI for AGMARK Replica 15 Digit Code</h4></td></tr>
    </table>

    <table width="100%" border="1">
        <tr><td>Applicant Id: <?php echo $firm_details['customer_id']; ?></td>
            <td align="right">Date: <?php echo date('d/m/Y'); ?></td>
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
            <td>    <?php echo $firm_details['firm_name']; ?>,<br>
                    <?php echo $firm_details['street_address']; ?>,<br>
                    <?php echo $firm_district_name; ?>, <?php echo $firm_details['postal_code']; ?><br>
                    <?php echo $firm_state_name; ?> <br>
            </td>
        </tr>

        <tr>    
            <td><br>Subject: Printing AGMARK replica- allotement of 15 Digit Code – regarding.</td>
        </tr>
                    
        <tr>
            <td><br>Sir,</td><br>
        </tr>   
                    
        <tr>
            <td><br>With reference to your letter on <?php echo $appl_date; ?>, on the subject cited above the following 15 Digit Codes are allotted for the printing of Agmark replica.<!-- in lieu of Agmark labels on ……….. for packing………… 
                    for the following pack sizes as desired by you. TBL………--></td>
        </tr>
              
    </table>




    <table width="100%" border="1">
	

			<tr>
				<th>Sr.No</th>
				<th>Commodity</th>
				<th>Grade</th>
				<th>TBL</th>
				<th>Packing Type</th>
				<th>Printer</th>
				<th>Pack</th>
				<th>Unit</th>
				<th>No of Packets</th>
				<th>Alloted Code</th>
				<th>Total Charge Rs. (Kg/Ltr)</th>
			</tr>
			
			<?php
				if(!empty($tableRowData)){
			
					$i=0;
					$sr_no = 1;
					foreach($tableRowData as $each){ ?>
					
					<tr>
						<td><?php echo $sr_no; ?></td>
						<td><?php echo $each['commodity_name']; ?></td>
						<td><?php echo $each['grade_name']; ?></td>
						<td><?php echo $each['tbl_name']; ?></td>
						<td><?php echo $each['packing_type']; ?></td>
						<td><?php echo $each['printer_name']; ?></td>
						<td><?php echo $each['packet_size']; ?></td>
						<td><?php echo $each['packet_size_unit']; ?></td>
						<td><?php echo $each['no_of_packets']; ?></td>
						<td><?php 
								$splitLastRange = substr($each['alloted_rep_to'],-5);																	
								echo $each['alloted_rep_from'].' - '.$splitLastRange; 																	
							?>
						</td>
						<td><?php echo $each['total_label_charges']; ?></td>
					</tr>

			<?php $sr_no++; $i=$i+1;	} } ?>


	</table>
	
	<table align="left">
        <tr style="margin-top: 50px;">
            <td>Tran. No. <b><?php echo $cur_trans_id; ?></b> Dt. <?php echo $trans_date; ?> For Rs. <b><?php echo $overall_charges; ?></b> is acknowledged.</td>
        </tr>
    </table>

	<table align="left">
        <tr style="margin-top: 50px;">
            <td><b>15 Digit Code Generation Rule: </b><br>
			1. First Digit is for RO/SO office code.<br>
			2. Second Digit is Commodity Category code.<br>
			3. Third & Fourth Digit is for month<br>
			4. Fifth & Sixth Digit is for Year<br>
			5. Then the series counter will start from 'A' like 'A001T', and change to 'B001T' and more as per requiremnt.<br>
			6. T represents thousand.<br>
			7. Users can generate 15 digit codes with packets in multiples of 1000 only.<br>
			</td>
        </tr>
    </table>



    <table align="left" style="margin-top: 15px">
        <tr>
            <td>
                As soon as the replica bearing ……… are received from the manufacturer/printing presses, they will be handed over to chemist In-charge who will be responsible for maintaining the account of replica bearing …….. <br>
             </td>
         </tr>
         <tr>
             <td>   
                It will be overall responsiblility of the certificate of authorization holder to keep the replica bearing ……. in safe custody and the account for the same. <br>
            </td>
        </tr>

        <tr>
             <td>
                The concerned approved printing presses may also be advised to send a copy of this invoice against your order to this office for record. The order placed for the printing AGMARK replica ……… in lieu of Agmark lables may be endorsed to this office.
            </td>
        </tr>
    
    </table>
 
	<table style="margin-top: 50px;">
        <tr>
            <td>1.The Agril. Officer (Chemistry) SAGL I/II… ……..</td>
        </tr>

        <tr>
            <td> The account of the containers carrying AGMARK replica shall be maintained in the prescribed performa and can be submitted to this office along with monthly grading returns by the 10th every month. </td>

        </tr>
        
        <tr>
            <td>2.……………………………………….(Though Packer) </td>

        </tr>

        <tr>   

           <td><br>They shall invariably endorse a copy of thrie invoice/advisory note to this office as soon as the consignment of AGMARK replica containers/packages are dispatched to the concerned authorized packers. 
            </td>
        </tr>
    </table>

	<br><br><p></p>
    <table align="right" style="margin-top: 20px">
        <tr>
            <td>
				<strong>Esigned By: <?php echo $chemist_name; ?></strong><br>
				(Chemist In-charge)<br> <?php echo $firm_details['firm_name']; ?><br>
			</td>
        </tr>
    </table>

        