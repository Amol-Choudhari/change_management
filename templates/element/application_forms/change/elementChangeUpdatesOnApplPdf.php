<?php foreach ($getFieldName as $eachField) {?>
			<tr>
				<td style="padding:10px; vertical-align:top;"><?php echo $eachField['change_field']; ?></td>
				<!--<td style="padding:10px; vertical-align:top;"></td>-->
				<td style="padding:10px; vertical-align:top;">
					<?php if ($eachField['field_id']==1) {//if firm name changed
						echo $getChangeDetails['firm_name'];
					
					} elseif ($eachField['field_id']==2) {//if personal details changed
						echo $getChangeDetails['mobile_no'].'<br>';
						echo $getChangeDetails['email_id'].'<br>';
						echo $getChangeDetails['phone_no'];
					
					} elseif ($eachField['field_id']==3) {//if TBL details changed			
						$i=1;
						foreach ($changeTblDetails as $eachtbl) {
							echo $i.'. '.$eachtbl['tbl_name'].'<br>';
							
							$i++;
						}
						
					} elseif ($eachField['field_id']==4) {//if Directors details changed		
						$i=1;
						foreach ($changeDirectorDetails as $eachdirector) {
							echo $i.'. '.$eachdirector['d_name'].', '.$eachdirector['d_address'].'<br>';
							
							$i++;
						}
						
					} elseif ($eachField['field_id']==5) {//if premises changed				
						echo $change_premises;
						
					} elseif ($eachField['field_id']==6) {//if Grading lab details changed			
						echo 'Type: '.$change_lab_type.'<br>';
						echo 'Name: '.$getChangeDetails['lab_name'];
						
					} elseif ($eachField['field_id']==7) {//if Commodity changed					
						
						$i=1;	
						foreach($change_commodity_name_list as $commodity_name){ ?>
						
							<b><?php echo $i.'.'. $commodity_name['category_name']; ?></b>
							<ol>
								<?php 

									foreach($change_sub_commodity_data as $sub_commodity){ ?>
								
									<?php if($sub_commodity['category_code'] == $commodity_name['category_code']){?>
									
										<li><?php echo $sub_commodity['commodity_name']; ?></li>
										
									<?php } ?>
								
								<?php  } ?>
								
							</ol>
							
						<?php $i=$i+1; }
						
					} elseif ($eachField['field_id']==8) {//if Machine Details	
						$i=1;
						foreach ($changeMachineDetails as $eachmachine) {
							echo $i.'. '.$eachmachine['machine_name'].'<br>';
							
							$i++;
						}
						
					} elseif ($eachField['field_id']==9) {//if premises changed				
						echo $change_business_type;
						
					} ?>
				</td>
			</tr>
		<?php } ?>