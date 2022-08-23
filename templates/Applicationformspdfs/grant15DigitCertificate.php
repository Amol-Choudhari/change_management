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
				Department of Agriculture & Farmers Welfare<br>
				Directorate of Marketing & Inspection</h4>
				
			</td>
			<td width="12%" align="center">
				<img src="img/logos/agmarklogo.png">
			</td>				
		</tr>
	</table>

    <table width="100%" border="1">
        <tr><td align="center" style="padding:5px;"><h4>Certificate of Approval to Use 15 Digit Code</h4></td></tr>
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
            <td><br>Subject: Approval of application to use 15 digit code – regarding.</td>
        </tr>
                    
        <tr>
            <td><br>Sir,</td><br>
        </tr>   

        <tr>
            <td>With reference to your application dated <?php echo $appl_date; ?> on the subject sited above, 
				it is inform, that you have been granted the Approval to use 15 digit code numbers, 
				on Agmark bearing packages/containers of <?php echo $commodity_names; ?> in your premises <?php echo $firm_details['street_address'].', '.$firm_district_name.', '.$firm_state_name.', '.$firm_details['postal_code']; ?><br>
			
				<br>
				Instructions: <br>
				1. Entire Quantity of <?php echo $commodity_names; ?> in above premises shall be graded under Agmark.<br>
				2. You will apply to this office for permission to print Agmark replica stating pack sizes and quantity along with requisite demand draft towards grading charges in advance as usual.<br>
				3. You will print the code on each package/container just below the Agmark insignia.<br>
				4. All the necessary prescribed records will have be properly maintained continously by different sections in your premises, updated on day to day basis and regularly checked by the chemist, approved by this directorate and counter checked 
					by the managing director/authorized employee of the firm and the same may be made available to inspecting officer regularly.<br>
				5. It is further subjected to complaince of instructions and order issued by the Agriculture Marketing Adviser to the Government of India or by any officer from time to time
					and is further governed by the provisions of the Agriculture Produce (Grading and Marking) Act, 1937 and the Rules framed there under as amended from time to time.<br>
				6. Replica serial numbers alloted/issued up to this date fully exhausted before starting the use of 15 digit code.<br>
				
			</td>
        </tr>
                    
        <tr>
            <td><br></td>
        </tr>
              
    </table>


	<br>
    <table align="right">	
					
		<tr>
			<td>Your’s faithfully<br> 
				Regional office In-charge<br> 
				Dy. Agril. Marketing Adviser<br>
			</td>
		</tr>
	</table>

    <!-- QR Code added by shankhpal shende on 16/08/2022 -->
	<div style="text-align: left;"> <img width="100" height="100" src="<?php echo $result_for_qr['qr_code_path']; ?>"></div>
	
	
        