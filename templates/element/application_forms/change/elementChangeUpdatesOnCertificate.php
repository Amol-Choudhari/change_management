<?php $applCnt=0;
foreach($getNoOfAppl as $each){ ?>

<tr>
	<td style="padding:10px; vertical-align:top;">Date: <?php echo $applSubmissionDate[$applCnt]; ?></td>
	<td style="padding:10px; vertical-align:top;">
	<table width="100%" border="1">
		<tr>
			<td style="padding:10px; vertical-align:top;"><b>Section/Field</b></td>
			<td style="padding:10px; vertical-align:top;"><b>Changed Detail</b></td>
		</tr>

		<?php foreach ($getFieldName[$applCnt] as $eachField) { ?>
			<tr>
				<td style="padding:10px; vertical-align:top;"><?php echo $eachField['change_field']; ?></td>
				<!--<td style="padding:10px; vertical-align:top;"></td>-->
				<td style="padding:10px; vertical-align:top;">
					<?php if ($eachField['field_id']==1) {//if firm name changed
						echo $getChangeDetails[$applCnt]['firm_name'];
					
					} elseif ($eachField['field_id']==2) {//if personal details changed
						
						if(!empty($getChangeDetails[$applCnt]['mobile_no'])){ echo base64_decode($getChangeDetails[$applCnt]['mobile_no']).', '; } //for email encoding
						if(!empty($getChangeDetails[$applCnt]['email_id'])){ echo base64_decode($getChangeDetails[$applCnt]['email_id']).', '; }
						if(!empty($getChangeDetails[$applCnt]['phone_no'])){ echo base64_decode($getChangeDetails[$applCnt]['phone_no']); }
					
					} elseif ($eachField['field_id']==3) {//if TBL details changed			
						$i=1;
						foreach ($changeTblDetails[$applCnt] as $eachtbl) {
							echo $i.'. '.$eachtbl['tbl_name'].'<br>';
							
							$i++;
						}
						
					} elseif ($eachField['field_id']==4) {//if Directors details changed		
						$i=1;
						foreach ($changeDirectorDetails[$applCnt] as $eachdirector) {
							echo $i.'. '.$eachdirector['d_name'].', '.$eachdirector['d_address'].'<br>';
							
							$i++;
						}
						
					} elseif ($eachField['field_id']==5) {//if premises changed				
						echo $change_premises[$applCnt];
						
					} elseif ($eachField['field_id']==6) {//if Grading lab details changed			
						echo 'Type: '.$change_lab_type.'<br>';
						echo 'Name: '.$getChangeDetails[$applCnt]['lab_name'];
						
					} elseif ($eachField['field_id']==7) {//if Commodity changed					
						
						$splitApplId = explode('/',$customer_id);

						//for PP
						if ($splitApplId[1]==2) { ?>
							
							<ul>
								<?php 

									foreach($packaging_types as $eachType){ ?>
									
										<li><?php echo $eachType; ?></li>
								
								<?php  } ?>
								
							</ul>

						<?php }else{
							
							$i=1;	
							foreach($change_commodity_name_list[$applCnt] as $commodity_name){ ?>
							
								<b><?php echo $i.'.'. $commodity_name['category_name']; ?></b>
								<ol>
									<?php 

										foreach($change_sub_commodity_data[$applCnt] as $sub_commodity){ ?>
									
										<?php if($sub_commodity['category_code'] == $commodity_name['category_code']){?>
										
											<li><?php echo $sub_commodity['commodity_name']; ?></li>
											
										<?php } ?>
									
									<?php  } ?>
									
								</ol>
								
							<?php $i=$i+1; }
						}
						
					} elseif ($eachField['field_id']==8) {//if Machine details changed			
						$i=1;
						foreach ($changeMachineDetails[$applCnt] as $eachMachine) {
							echo $i.'. '.$eachMachine['machine_name'].'<br>';
							
							$i++;
						}
						
					} elseif ($eachField['field_id']==9) {//if Business type details changed			
						echo $getChangeDetails[$applCnt]['business_type'];
						
					} ?>
				</td>
			</tr>
		<?php } ?>

	</table>
					
	</td>
	<td style="padding:10px; vertical-align:top;">E-Signed By:<?php if(!empty($certEsignedBy[$applCnt])){ echo $certEsignedBy[$applCnt]; }else{ echo 'NA'; } ?></td>
</tr>

<?php $applCnt++; } ?>