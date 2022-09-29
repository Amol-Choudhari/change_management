<?php 
	
	?>	
	
	<div class="col-md-12">
	<h3 class="report-heading">IO Allocation Logs History Report</h3>
	</div>
	<div class="clearfix"></div>
	<?php echo $this->Form->create('Dmi_user_role'); ?>
	<div class="panel panel-primary report-filterable">
			
			<div class="panel-heading">
				
				<div id="search_by_options" class="col-md-12">	

								<strong class="col-md-12 report-strong" >Search By:</strong>
							<div class="report-filter" class="col-md-12">	
								<div class="col-md-3">
									<label>Office</label>
									<?php echo $this->form->input('office', array('type'=>'select', 'value'=>$search_office,'options'=>$ro_office, 'label'=>false, 'id'=>'office', 'empty'=>'All', 'escape'=>false)); ?>
								</div>
								
								<div class="col-md-3">
									<label>Application ID</label>
									<?php echo $this->form->input('application_id', array('type'=>'text', 'value'=>$application_id,'options'=>'', 'label'=>false, 'id'=>'user_id', 'empty'=>'All', 'escape'=>false)); ?>
								</div>
								
								<div class="col-md-6">
									<label>User Name(ID)</label>
									<?php echo $this->form->input('user_id', array('type'=>'select', 'value'=>$search_user_id,'options'=>$user_name_list, 'label'=>false, 'id'=>'user_id', 'empty'=>'All', 'escape'=>false)); ?>
								</div>
								
							</div>
								
							<div class="clearfix"></div>
							
							<div class="report-filter" class="col-md-12">
								<div class="col-md-3">
									<label>From Date</label>
									<?php echo $this->form->input('from_date', array('type'=>'text', 'value'=>$search_from_date,'label'=>false, 'id'=>'fromdate', 'empty'=>'select', 'escape'=>false)); ?>
								</div>
								
								<div class="col-md-3">
									<label id="to_date_label">To Date</label>
									<?php echo $this->form->input('to_date', array('type'=>'text', 'value'=>$search_to_date,'label'=>false, 'id'=>'todate', 'empty'=>'select', 'escape'=>false)); ?>
								</div>
								
								<div class="col-md-6">
									<input id="search_btn" type="submit" name="search_logs" class="form-control b747474" value="Search" >
								</div>
								
								
							</div>	
							<div class="clearfix"></div>
				</div>
				
				<div class="clearfix"></div>
				
			</div>
			
		</div>
		<div class="clearfix"></div>
		
		<?php if(!empty($allocation_logs_details)){ ?>
			<div class="table-responsive report-table-format">
							
								<table class="table">
								<tr>
								<th>Date</th>
								<th>User Name(ID)</th>
								<th>Posted Office</th>
								<th>Application ID</th>
								<th>Application Type</th>
								</tr>
								
								<?php for ($i=0; $i<sizeof($allocation_logs_details); $i++) { ?>
								
								<tr>
								<td><?php echo 	$allocation_logs_details[$i][$table]['created']; ?></td>
								<td><?php echo  $user_name_detail[$i];?></td>
								<td><?php echo 	$allocation_logs_details[$i][$table][$office_field]; ?></td>
								<td><?php echo  $allocation_logs_details[$i][$table]['customer_id']; ?></td>
								<td><?php echo  $allocation_logs_details[$i][$table]['application_type']; ?></td>
								</tr>
								
								<?php } ?>
								</table>
									
			</div>
			<a href="#" class="col-md-12 cf09609"><b class="float-right">Download PDF Version</b></a>
			<div class="report-back-button">
			<h5>
			<?php echo $this->Html->link('Back', array('controller' => 'reports', 'action'=>'report_types')); ?>
			</h5>
			</div>
			
		<?php } ?>
		
		
		
		<?php $paginator = $this->Paginator; ?>	


			<ul class = "pagination">
			<!--<li><?php //echo $paginator->first("First"); ?></li>-->
				<li><?php if($paginator->hasPrev()){
						echo $paginator->prev("<<");
					}?>
				</li>
				<li><?php echo $paginator->numbers(); ?></li>
				<li><?php if($paginator->hasNext()){
						echo $paginator->next(">>");
					}?>
				</li>
			<!--<li><?php //echo $paginator->last("Last"); ?></li>-->
			</ul>
			
		<?php echo $this->Form->end(); ?>
		
		<script>
		$(document).ready(function () {
			
			$('#fromdate').datepicker({format: "dd/mm/yyyy",orientation: "left top",autoclose: true,});
			$('#todate').datepicker({ format: "dd/mm/yyyy", orientation: "left top", autoclose: true, });
			
			
			$('#search_btn').click(function(){	
						
				var from = $("#fromdate").val().split("/");
				var fromdate = new Date(from[2], from[1] - 1, from[0]);
 
				var from = $("#todate").val().split("/");
				var todate = new Date(from[2], from[1] - 1, from[0]);	
				
				if(todate < fromdate){
					
					alert('Invalid Date Range Selection');
					return false;
				}	
			});
		
		});
			
		</script>