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
        <tr><td align="center"><h4>Replica 15 Digit Code Application Form</h4></td></tr>
    </table>

    <table width="100%" border="1">
        <tr><td align="center" style="padding:5px;"><h4>Application for 15 Digit Code of CA</h4></td></tr>
    </table>

    <table width="100%" border="1">
        <tr><td>Applicant Id. : <?php echo $customer_id;?></td>
            <td align="right">Date: <?php echo $pdf_date;?></td>
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
            <td>    The Dy. Agricultural Marketing Adviser<br>
                    Incharge-Regional Office,<br>
                    Directorate of Marketing & Inspection,<br>
                    (Ministry of Agriculture & Farmers Welfare)<br>
                    Nagpur, Maharashtra
             <!--<?php echo $firm_district_name; ?>,<?php echo $firm_state_name; ?>-->
         </td>
        </tr>


        <tr>    
            <td><br>Subject: Allotment of serial number for printing AGMARK replica bearing packets request.</td>
        </tr>

          <tr>
            <td><br>Sir,</td><br>
        </tr>   



        <tr>
            <td><br>I am submitting and application for the allotment of serial number for printing of AGMARK replica bearing packets required for the commodities as under:-</td>
        </tr>

    </table>

            
    <table border="1" width="100%">
        <tbody>
            <tr>
                <td colspan="5" width="260">
                    <p>1. File number of permission for use of AGMARK Replica granted by DMI:</p>
                </td>

                <td colspan="5" width="260">

                    <p>856699 <!--<?php echo $file_number?>--></p>
                
                </td>
            </tr>

            <tr>

                <td colspan="5" width="260">
                    <p>2. Name and Address of approved printing press:</p>
                </td>

                <td colspan="5" width="260">
                    
                    <p>Name : Akash printing Press <!--<?php echo $printing_press_name; ?>--></p>

                    <p>Address : Adaesh Nagar Wadi <!--<?php echo $printing_press_address; ?>--></p>
                </td>
            </tr>

            <tr>
                <td colspan="5" width="260">
                    <p>3. Name and address of grading laboratories:</p>
                </td>
               
                <td colspan="5" width="260">
                    
                    <p>Name : Akash LABS <!--<?php echo $lab_name; ?>--></p>

                    <p>Address : SaI Nagar Wadi <!--<?php echo $lab_address; ?>--></p>
                </td>
            </tr>

            <tr>
                <td colspan="5" width="260">
                    <p>4. Name of TBL (Trade Brand Label):</p>
                </td>

                <td colspan="5" width="260">
                    <p> Kichen King <!--<?php echo $tbl_name; ?>--></p>
                </td>
            </tr>

            <tr>
                <td colspan="5" width="260">
                    <p>5. Mention the packaging material on which the Printing will be done:</p>
                </td>

                <td colspan="5" width="260">
                    <p>Rubber Matreial <!--<?php echo $packeging_material; ?>--></p>
                </td>
            </tr>

            <tr>
                <td colspan="5" width="260">
                    <p>6. Name and composition of the packing material in which the printing will be done:</p>
                </td>
                <td colspan="5" width="260">
                    <p>Compostion <!--<?php echo $composition; ?>--></p>
                </td>
            </tr>
        </tbody>
        </table>


    <table>
        
       <tr>
                <td colspan="10" border="1" width="520">
                    <p>7. Details of proposed printing material: (<strong>Name of printer and valid upto may be in an additional column against the replica number required for printing.</strong></p>
                </td>
            </tr>
  
    </table>    
    
        <br pagebreak="true" />
     
    <table border="1" >
      <tbody>     
            <tr>
                <td width="30">
                    <p>Sn.</p>
                </td>
                
                <td width="100">
                    <p>Name of commodity</p>
                </td>
                
                <td width="50">
                    <p>Grade</p>
                </td>
                
                <td width="50">
                    <p>TBL</p>
                </td>
            
                <td width="50">
                    <p>Net weight of pack size</p>
                </td>
            
                <td width="50">
                    <p>No. of pouches to be printed</p>
                </td>
            
                <td width="50">
                    <p>Total quantity in quintal</p>
                </td>
                
                <td width="70">
                    <p>Label charges (Rs.)</p>
                </td>
                
                <td width="70">
                    <p>Balance Agmark replica with serial</p>
                </td>
            </tr>

            <tr>
                <td colspan="5" width="260">
                    <p>8. Details of payment:</p>
                    <p>&nbsp;</p>
                </td>
                <td colspan="5" width="260">
                    <p>Mode of payment: Bharat Kosh</p>
                    <p>Reference No : 569966 <!--<?php echo $refrence_no; ?>--></p>
                    <p>Date : 27-07-2021 <!--<?php echo $date_of_application?>--></p>
                    <p>Amount(Rs) : 5600 <!--<?php echo $total_charge; ?>-->::(In words : Five Thousand Six Hundred <!--<?php echo $total_charge_in_words?>--> )</p>
                </td>
            </tr>
        </tbody>
    </table>




    <table width="100%" style="margin-top: 20px;">
        <tbody>
            <tr>
                <td width="284">
                    <p><strong>Verification by the grading chemist:</strong></p>
                    <p>1. Verified that balance of the replica as shown as in column 7 is correct</p>
                    <p>2. Verified that label charges deposited as per detailed given in column 8.</p>
                    <p>&nbsp;</p>
                </td>
    
                <td width="284">
                    <p style="text-align: center;"><strong>Signature of Authorized Packer</strong></p>
                </td>
            </tr>

            <tr>
                <td width="284">
                    <p>&nbsp;</p>
                </td>
                <td width="284">
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td width="284">
                    <p>Place: Nagpur <!--<?php echo $place_of_application; ?>--></p>
                    <p>Date: 27-07-2021 <!--<?php echo $date_of_application; ?>--></p>
                    <p>&nbsp;</p>
                </td>
                <td width="284">
                 <p>&nbsp;</p>
                    <p style="text-align: center;"><strong>Signature of Grading Chemist</strong></p>
                <p>&nbsp;</p>
                </td>
            </tr>
        </tbody>
    </table>
    