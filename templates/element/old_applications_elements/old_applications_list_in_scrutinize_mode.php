<?php 

$current_level = $_SESSION['current_level'];
$current_action = $this->request->getParam('action');

// To Display the proper name in list instead of showing the varilable name (by pravin 05/05/2017)
$current_action_split_value = explode('_',$current_action);
$current_action_name = ucwords(implode(" ",$current_action_split_value));

//calling jquery for table filter here as ajax applied and set layout is turned off
//on 08-06-2017 by Amol
echo $this->Html->script('table_filter');

?>

<!-- table for listing of CA applications starts -->
<div class="table-format">
<div class="inspection">
<div class="panel panel-primary filterable">
	<div class="panel-heading">
		<div class="admin-main-page">
			<h5>Given Below is list of <?php echo $current_action_name; ?> for CA</h5>
			<div class="pull-right">
				<button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span>Filter Table</button>
			</div>
		</div>
	</div>
<table class="table">
	<thead>
		<tr class="filters">
			<th><input type="text" class="form-control" placeholder="Id" disabled></th>
			<th><input type="text" class="form-control" placeholder="Firm Name" disabled></th>
		<!--	<th>Certification Type</th>-->
		
			<?php if(($current_level=='level_1' || $current_level=='level_3') && $current_action=='old_pending_applications'){?>			
				
				<th><input type="text" class="form-control" placeholder="Submission Date" disabled></th>
				
			<?php }elseif($current_level=='level_3' && $current_action=='old_pending_verifications'){?>
			
				<th><input type="text" class="form-control" placeholder="Allocation Date" disabled></th>
			
			<?php }elseif(($current_level=='level_1' || $current_level=='level_3') && 
						($current_action=='old_referred_back_applications' || $current_action=='old_referred_back_to_ro')){?>
			
				<th><input type="text" class="form-control" placeholder="Referred Back Date" disabled></th>
				
			<?php }elseif(($current_level=='level_1' || $current_level=='level_3') && 
						($current_action=='old_replied_applications' || $current_action=='old_replied_by_ro')){?>
			
				<th><input type="text" class="form-control" placeholder="Replied Date" disabled></th>
				
			<?php }elseif(($current_level=='level_1' && $current_action=='old_verified_applications') ||
						($current_level=='level_3' && $current_action=='old_approved_applications')){?>
			
				<th><input type="text" class="form-control" placeholder="Scrutinized Date" disabled></th>
				
			<?php } ?>
			
			<th><input type="text" class="form-control" placeholder="Commodity" disabled></th>
			<th><input type="text" class="form-control" placeholder="State" disabled></th>
			<th><input type="text" class="form-control" placeholder="District" disabled></th>
			<th><input type="text" class="form-control" placeholder="Action" disabled></th>
		</tr>
	</thead>		
	<tbody>	
		<?php
		if(!empty($old_available_ca_customer_id)){
			$i=0;
			foreach($old_available_ca_customer_id as $firm_detail){ ?>
				
				<tr>
					<td><?php echo ['customer_id'];?>-<?php echo $form_type_ca_old[$i]; ?></td>
					<td><?php echo ['firm_name'];?></td>

					<td><?php echo $display_date_ca_old[$i]; ?></td>
	
				<!--	<td><?php //echo $firm_detail['Dmi_firm']['certification_type'];?></td>-->
					<td><?php echo ['commodity'];?></td>
					<td><?php echo ['state'];?></td>
					<td><?php echo ['district'];?></td>
					<!--<td><?php // echo $this->Html->link('Inspect Forms', array('controller' => 'roinspections', 'action'=>'inspect_report_fetch_id', $firm_detail['Dmi_firm']['id'])); ?></td>-->
					<td><?php  
								// Apply this condition to check wether the application present on RO side or HO side or IO side By pravin 12-07-2017 
								if($current_action=='old_replied_applications' && $current_level =='level_3'){
																		// Apply ho allocated check	by pravin 25/07/2017
									if($old_ca_get_current_position[$i] != 'level_3' || $old_ca_check_if_report_filed[$i] == 'old_ho_allocated'){
										
											$Scrutinize = 'View Only';
											
									}elseif($old_ca_get_current_position[$i] == 'level_3' && $old_ca_check_if_report_filed[$i] != null){
										
											$Scrutinize = 'View & Comment';
									}else{
										
										$Scrutinize = 'Scrutinize';
									 }
								}else{
										$Scrutinize = 'Scrutinize';
									 }
					
								echo $this->Html->link($Scrutinize, array('controller' => 'oldappinspections', 'action'=>'edit_fetch_id', $firm_detail['id'])); ?>
								| <?php echo $this->Html->link('PDF', array('controller' => 'oldappinspections', 'action'=>'pdf_fetch_id', $firm_detail['id'])); ?>
					</td>
								
								</td>
	
				</tr>
			
		<?php $i=$i+1;	} }else{ ?>		
				<tr>
				
					<td></td>
					<td>Currently there are no <?php echo $current_action_name; ?> for CA</td>
					    
				</tr>
		<?php	} ?>		
	</tbody>		
</table>
</div>	
</div>	
</div>

<!-- table for listing of CA applications ends -->






<!-- table for listing of Printing applications starts -->

<div class="table-format">
<div class="inspection">
<div class="panel panel-primary filterable">
	<div class="panel-heading">
		<div class="admin-main-page">
			<h5>Given Below is list of <?php echo $current_action_name; ?> for Printing Press</h5>
			<div class="pull-right">
				<button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span>Filter Table</button>
			</div>
		</div>
	</div>
<table class="table">
	<thead>
		<tr class="filters">
			<th><input type="text" class="form-control" placeholder="Id" disabled></th>
			<th><input type="text" class="form-control" placeholder="Firm Name" disabled></th>
		<!--	<th>Certification Type</th>-->
		
			<?php if(($current_level=='level_1' || $current_level=='level_3') && $current_action=='old_pending_applications'){?>			
				
				<th><input type="text" class="form-control" placeholder="Submission Date" disabled></th>
				
			<?php }elseif($current_level=='level_3' && $current_action=='old_pending_verifications'){?>
			
				<th><input type="text" class="form-control" placeholder="Allocation Date" disabled></th>
			
			<?php }elseif(($current_level=='level_1' || $current_level=='level_3') && 
						($current_action=='old_referred_back_applications' || $current_action=='old_referred_back_to_ro')){?>
			
				<th><input type="text" class="form-control" placeholder="Referred Back Date" disabled></th>
				
			<?php }elseif(($current_level=='level_1' || $current_level=='level_3') && 
						($current_action=='old_replied_applications' || $current_action=='old_replied_by_ro')){?>
			
				<th><input type="text" class="form-control" placeholder="Replied Date" disabled></th>
				
			<?php }elseif(($current_level=='level_1' && $current_action=='old_verified_applications') ||
						($current_level=='level_3' && $current_action=='old_approved_applications')){?>
			
				<th><input type="text" class="form-control" placeholder="Scrutinized Date" disabled></th>
				
			<?php } ?>
			
			<th><input type="text" class="form-control" placeholder="Commodity" disabled></th>
			<th><input type="text" class="form-control" placeholder="State" disabled></th>
			<th><input type="text" class="form-control" placeholder="District" disabled></th>
			<th><input type="text" class="form-control" placeholder="Action" disabled></th>
		</tr>
	</thead>		
	<tbody>	
		<?php
		if(!empty($old_available_printing_customer_id)){
			$i=0;
			foreach($old_available_printing_customer_id as $firm_detail){ ?>
				
				<tr>
					<td><?php echo $firm_detail['customer_id'];?>-<?php echo $form_type_printing_old[$i]; ?></td>
					<td><?php echo $firm_detail['firm_name'];?></td>

					<td><?php echo $display_date_printing_old[$i]; ?></td>
					
				<!--	<td><?php //echo $firm_detail['Dmi_firm']['certification_type'];?></td>-->
					<td><?php echo $firm_detail['commodity'];?></td>
					<td><?php echo $firm_detail['state'];?></td>
					<td><?php echo $firm_detail['district'];?></td>
					<td><?php 
								// Apply this condition to check wether the application present on RO side or HO side or IO side By pravin 12-07-2017 
									if($current_action=='old_replied_applications' && $current_level =='level_3'){
																						// Apply ho allocated check	by pravin 25/07/2017		
										if($old_printing_get_current_position[$i] != 'level_3' || $old_printing_check_if_report_filed[$i] == 'old_ho_allocated'){
											
											$Scrutinize = 'View Only';
										
										}elseif($old_printing_get_current_position[$i] == 'level_3' && $old_printing_check_if_report_filed[$i] != null){
										
											$Scrutinize = 'View & Comment';
										}else{
										
											$Scrutinize = 'Scrutinize';
										}
								}else{
										$Scrutinize = 'Scrutinize';
									 }
					
							echo $this->Html->link($Scrutinize, array('controller' => 'oldappinspections', 'action'=>'printing_inspect_fetch_id', $firm_detail['id'])); ?>
							| <?php echo $this->Html->link('PDF', array('controller' => 'oldappinspections', 'action'=>'pdf_fetch_id', $firm_detail['id'])); ?>
					</td>
							</td>
					
				</tr>
		
		<?php $i=$i+1;	} }else{ ?>		
				<tr>				
					<td></td>
					<td>Currently there are no <?php echo $current_action_name; ?> for Printing Press</td>
					    
				</tr>
		<?php	} ?>
		
	</tbody>					
</table>	
</div>		
</div>	
</div>

<!-- table for listing of Printing applications ends -->





<!-- table for listing of laboratory applications starts -->

<div class="table-format">
<div class="inspection">
<div class="panel panel-primary filterable">
	<div class="panel-heading">
		<div class="admin-main-page">
			<h5>Given Below is list of <?php echo $current_action_name; ?> for Laboratory</h5>
			<div class="pull-right">
				<button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span>Filter Table</button>
			</div>
		</div>
	</div>
<table class="table">
	<thead>
		<tr class="filters">
			<th><input type="text" class="form-control" placeholder="Id" disabled></th>
			<th><input type="text" class="form-control" placeholder="Firm Name" disabled></th>
		<!--	<th>Certification Type</th>-->
		
			<?php if(($current_level=='level_1' || $current_level=='level_3') && $current_action=='old_pending_applications'){?>			
				
				<th><input type="text" class="form-control" placeholder="Submission Date" disabled></th>
				
			<?php }elseif($current_level=='level_3' && $current_action=='old_pending_verifications'){?>
			
				<th><input type="text" class="form-control" placeholder="Allocation Date" disabled></th>
			
			<?php }elseif(($current_level=='level_1' || $current_level=='level_3') && 
						($current_action=='old_referred_back_applications' || $current_action=='old_referred_back_to_ro')){?>
			
				<th><input type="text" class="form-control" placeholder="Referred Back Date" disabled></th>
				
			<?php }elseif(($current_level=='level_1' || $current_level=='level_3') && 
						($current_action=='old_replied_applications' || $current_action=='old_replied_by_ro')){?>
			
				<th><input type="text" class="form-control" placeholder="Replied Date" disabled></th>
				
			<?php }elseif(($current_level=='level_1' && $current_action=='old_verified_applications') ||
						($current_level=='level_3' && $current_action=='old_approved_applications')){?>
			
				<th><input type="text" class="form-control" placeholder="Scrutinized Date" disabled></th>
				
			<?php } ?>
			
			<th><input type="text" class="form-control" placeholder="Commodity" disabled></th>
			<th><input type="text" class="form-control" placeholder="State" disabled></th>
			<th><input type="text" class="form-control" placeholder="District" disabled></th>
			<th><input type="text" class="form-control" placeholder="Action" disabled></th>
		</tr>
	</thead>		
	<tbody>	
		<?php
		if(!empty($old_available_laboratory_customer_id)){
			$i=0;
			foreach($old_available_laboratory_customer_id as $firm_detail){ ?>
				
				<tr>
					<td><?php echo $firm_detail['customer_id'];?>-<?php echo $form_type_laboratory_old[$i]; ?></td>
					<td><?php echo $firm_detail['firm_name'];?></td>
					
					<td><?php echo $display_date_laboratory_old[$i]; ?></td>
					
				<!--	<td><?php //echo $firm_detail['Dmi_firm']['certification_type'];?></td>-->
					<td><?php echo $firm_detail['commodity'];?></td>
					<td><?php echo $firm_detail['state'];?></td>
					<td><?php echo $firm_detail['district'];?></td>
					<!--<td><?php // echo $this->Html->link('Scrutinize', array('controller' => 'inspections', 'action'=>'printing_inspect_fetch_id', $firm_detail['Dmi_firm']['id'])); ?></td>-->
					<td><?php  
							// Apply this condition to check wether the application present on RO side or HO side or IO side By pravin 12-07-2017 
							if($current_action=='old_replied_applications' && $current_level =='level_3'){
																					// Apply ho allocated check	by pravin 25/07/2017
								if($old_laboratory_get_current_position[$i] != 'level_3' || $old_laboratory_check_if_report_filed[$i] == 'old_ho_allocated'){
									
									$Scrutinize = 'View Only';
								
								}elseif($old_laboratory_get_current_position[$i] == 'level_3' && $old_laboratory_check_if_report_filed[$i] != null ){
										
									$Scrutinize = 'View & Comment';
								}else{
									
									$Scrutinize = 'Scrutinize';
								 }
							}else{	
										$Scrutinize = 'Scrutinize';
									 }	 
					
					echo $this->Html->link($Scrutinize, array('controller' => 'oldappinspections', 'action'=>'laboratory_inspect_fetch_id', $firm_detail['id'])); ?>
					| <?php echo $this->Html->link('PDF', array('controller' => 'oldappinspections', 'action'=>'pdf_fetch_id', $firm_detail['id'])); ?>
					</td>
					</td>    
				</tr>
	
		<?php	$i=$i+1; } }else{ ?>		
				<tr>				
					<td></td>
					<td>Currently there are no <?php echo $current_action_name; ?> for Laboratory</td>
					    
				</tr>
		<?php	} ?>
		
	</tbody>					
</table>
</div>	
</div>
</div>

<!-- table for listing of laboratory applications ends -->