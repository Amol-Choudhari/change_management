<?php echo $this->Html->css('dashboard/common-appl-list-css'); ?>

<?php
$current_action = $this->request->getParam('action'); 	
?>

<!-- for common applications listing-->
<div id="common_app_list_div">
<?php //echo $this->Form->create('DmiFirms',array('id'=>'common_list_form')); ?>
<table id="common_app_list_table" class="table table-striped table-bordered">
	<thead class="tablehead">
		<tr>
			<th>Application Type</th>
			<th>Application Id</th>
			<th>Firm Name</th>
			<?php if($_SESSION['current_level']=='level_2' && $current_action=='pendingApplications'){ //for IO user only for pending Reports ?>
				
					<th>Scheduled date</th>
			
			<?php }else{ ?>
			
					<th><?php if(empty($alloc_window)){ echo "Communication With"; }else{ echo "Allocated To"; } ?></th>
			
			<?php } ?>
			<?php if(empty($alloc_window)){ ?>
				<th>On Date</th>
			<?php } ?>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$i=1;
			foreach($appl_list_array as $each){ //print_r($each); ?>
			<tr>
				<td data-search="<?php echo $each['appl_type']; ?>" data-order="<?php echo $each['appl_type']; ?>"><?php echo $this->Form->control('appl_type',array('type'=>'text', 'id'=>'appl_type'.$i, 'value'=>$each['appl_type'], 'label'=>false, 'readonly'=>true)); ?></td>
				<td data-search="<?php echo $each['customer_id']; ?>" data-order="<?php echo $each['customer_id']; ?>"><?php echo $this->Form->control('customer_id',array('type'=>'text', 'id'=>'customer_id'.$i, 'value'=>$each['customer_id'], 'label'=>false, 'readonly'=>true)); ?></td>
				<td><?php echo "<span class='badge'>".$each['firm_name']."</span>";?></td>
				
				<?php if($_SESSION['current_level']=='level_2' && $current_action == 'pendingApplications'){ //for IO user only for Reports ?>
					
					<td>
						<?php echo $this->Form->control('io_scheduled_date',array('type'=>'text', 'id'=>'io_scheduled_date'.$i, 'value'=>date("d/m/Y",strtotime($each['io_scheduled_date'])), 'class'=>'io_scheduled_date', 'readonly'=>true, 'required'=>true, 'label'=>false)); ?>
						<?php echo $this->Form->control('io_sched_date_comment',array('type'=>'textarea', 'id'=>'io_sched_date_comment'.$i, 'value'=>$each['io_sched_date_comment'], 'class'=>'io_sched_date_comment',  'required'=>true, 'label'=>false, 'placeholder'=>'Reason/Remark','rows'=>'2','cols'=>'9')); //added on 12-05-2021 by Amol ?>
						<a href="#" class="change_date btn-success" title='Change Date' id="<?php echo 'change_date'.$i; ?>">Change</a>
					</td>
					
				<?php }else{ ?>
				
						<td id="<?php echo 'comm_with'.$i; ?>"><?php echo $each['comm_with'];?></td>
					
				<?php } ?>
				
				<?php if(empty($alloc_window)){ ?>
					<td><?php  echo date("d/m/Y",$each['on_date']) ;  ?></td>
				<?php } ?>
				
				<td>
					<!-- Conditional links flow and appl. type wise-->
					<?php if($_SESSION['current_level']=='pao'){ 
							if($current_action == 'approvedApplications' || $current_action == 'refBackApplications') { //for IO user only for Reports ?>
								<a title="View Payment" href="<?php echo $each['appl_view_link'];?>"><span class="glyphicon glyphicon-eye-open"></span></a>
							<?php }else{ ?>
								<a title="Verify Payment" href="<?php echo $each['appl_view_link'];?>"><span class="glyphicon glyphicon-edit"></span></a>
								<a id="reject_appln<?php echo $i;?>" title="To Reject the Application" class="reject"><span class="glyphicon glyphicon-remove"></span></a>																												  
					<?php } }else{ ?>
						
						<a title="View Application" href="<?php echo $each['appl_view_link'];?>"><span class="glyphicon glyphicon-eye-open"></span></a>
					
					<?php } ?>
					
					<?php if($_SESSION['current_level']=='level_1' || $_SESSION['current_level']=='level_3' || $_SESSION['current_level']=='level_4'){ //for MO/Level 3 user only for Scrutiny 
						
						if(!empty($each['appl_edit_link'])){ ?>
						
						<a title="Scrutiny" href="<?php echo $each['appl_edit_link'];?>"><span class="glyphicon glyphicon glyphicon-edit"></span></a>
						
						
						<!--For Nodal officer to view the grant pdf, pravin bhakare 03-07-2020 -->
						<?php if($_SESSION['current_level']=='level_3'){  if(!empty($each['grant_certificate'])) { ?>
						
							<a id="grant_pdf<?php echo $i;?>" title="View Certificate PDF" href="<?php echo $each['grant_certificate'];?>" target="_blank"><span class="glyphicon glyphicon-file"></span></a>
						
						<?php } } ?>
						
							<!--For Nodal officer to Reject Application -->
						<?php if($_SESSION['current_level']=='level_3' ){ ?>
						
							<a id="reject_appln<?php echo $i;?>" title="To Reject the Application" class="reject"><span class="glyphicon glyphicon-remove"></span></a>
						
						<?php } ?>						
					<?php }} ?>
					
					<?php if($_SESSION['current_level']=='level_2'){ //for IO user only for Reports 
							if($current_action == 'pendingApplications' || $current_action == 'refBackApplications') { ?>
								<a title="Report" href="<?php echo $each['report_link'];?>"><span class="glyphicon glyphicon glyphicon-edit"></span></a>
							<?php }else{ ?>
								<a title="View Report" href="<?php echo $each['report_link'];?>"><span class="glyphicon glyphicon glyphicon-eye-open"></span></a>
					<?php } } ?>
					
			
			<!-- Below button are for allocation/reallocations -->		
					
					<!--for level 3 and Dyama user only for scrutiny allocation -->
					<?php if(($_SESSION['current_level']=='level_3'  || 
							($_SESSION['current_level'] == 'level_4' && $current_user_roles['dy_ama'] == 'yes')) &&
							(!empty($each['alloc_sub_tab']) && 
							($each['alloc_sub_tab']=='scrutiny_allocation_tab' || $each['alloc_sub_tab']=='scrutiny_allocation_by_level4ro_tab'))){ 
					
						if($each['comm_with']=='Not Allocated'){ ?>
							
							<a id="allocate-scrutiny<?php echo $i;?>" title="Allocate for Scrutiny" class="allocate"><span class="glyphicon glyphicon-share-alt"></span></a>
						
						<?php }else{ ?>
							
							<a id="allocate-scrutiny<?php echo $i;?>" title="Reallocate for Scrutiny" class="reallocate"><span class="glyphicon glyphicon-share-alt"></span></a>
						
						<?php }
					
					} ?>
					
					
					<!--for level 3 and Dyama user only for Site Inspection allocation -->
					<?php if($_SESSION['current_level']=='level_3'  && (!empty($each['alloc_sub_tab']) && $each['alloc_sub_tab']=='inspection_allocation_tab')){ 
					
						if($each['comm_with']=='Not Allocated'){ ?>
							
							<a id="allocate-inspection<?php echo $i;?>" title="Allocate for Site Inspection" class="allocate"><span class="glyphicon glyphicon-share-alt"></span></a>
						
						<?php }else{ ?>
							
							<a id="allocate-inspection<?php echo $i;?>" title="Reallocate for Site Inspection" class="reallocate"><span class="glyphicon glyphicon-share-alt"></span></a>
						
						<?php }
					
					} ?>
					
					
					
				</td>
			</tr>
		<?php $i=$i+1; } ?>
		
		
	</tbody>
</table>
<?php //echo $this->Form->end(); ?>
</div>

<div id="allocation_popup_box">

</div>

<input type="hidden" id="session-current-level" value="<?php echo $_SESSION['current_level']; ?>">
<input type="hidden" id="i-value" value="<?php echo $i; ?>">
<?php 
//commented script call, now all script is in common-count-js.js file as function call, changed on 21-10-2021 by Amol
//echo $this->Html->script('dashboard/common-appl-list-js'); 
exit; //intensionally added exit; on 07-10-2021 by Amol, as CSRF blocking the next request after rendering the view throough ajax ?>

