	<?php
	
	$data = $this->Js->get('#newly_added_firm')->serializeForm(array('isForm' => true, 'inline' => true));
		  
		  
		  
			  $this->Js->get('#state')->event(
				'change',
				$this->Js->request(
				  array('controller'=>'reports','action' => 'show_district_dropdown'),
				  array(
				   'update' => '#district',
					'data' => $data,
					'async' => true,    
					'dataExpression'=>true,
					'method' => 'POST'
						
				 )
				)
			  );
		  
		  
		  
	echo $this->Js->writeBuffer();
	                                        
?>	
	<div class="col-md-12">
	<h3 class="report-heading">Sent Email Status Report</h3>
	</div>
	<div class="clearfix"></div>
	<?php echo $this->Form->create('Dmi_firm',array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'sent_email_status')); ?>
	
	<div class="panel panel-primary report-filterable">
			
			<div class="panel-heading">
				
				<div id="search_by_options" class="col-md-12">	
							<div id="test"></div>
							<strong class="col-md-12 report-strong" >Search By:</strong>
							<div class="report-filter ro_report-filter " class="col-md-12">	
								
																
								<div class="col-md-3" >
									<label>Application Type</label>
									<?php echo $this->form->input('application_type', array('type'=>'select', 'value'=>'', 'options'=>$all_application_type, 'label'=>false, 'empty'=>'All', 'id'=>'application_type', 'escape'=>false)); ?>
								</div>
								
								<div class="col-md-2">
								<label>Office</label>
								<?php echo $this->form->input('office', array('type'=>'select', /*'value'=>$search_office,*/ 'options'=>$all_ro_office, 'label'=>false, 'id'=>'office', 'empty'=>'All', 'escape'=>false)); ?>
								</div>
								
								<div class="col-md-2">
									<label>From Date</label>
									<?php echo $this->form->input('from_date', array('type'=>'text', 'value'=>'','label'=>false, 'id'=>'fromdate', 'empty'=>'select', 'escape'=>false)); ?>
								</div>
								
								<div class="col-md-2">
									<label id="to_date_label">To Date</label>
									<?php echo $this->form->input('to_date', array('type'=>'text', 'value'=>'','label'=>false, 'id'=>'todate', 'empty'=>'select', 'escape'=>false)); ?>
								</div>
								
								<div class="col-md-3">
									<input id="search_btn" type="submit" name="search_logs" class="form-control b747474cfffmt16" value="Search" >
								</div>
								
							</div>	
							<div class="clearfix"></div>
							
							
				</div>
				
				<div class="clearfix"></div>
				
			</div>
			
		</div>
		<div class="clearfix"></div>
		
		<?php //if(!empty($current_users_details)){ ?>
			<div class="table-responsive report-table-format">
							
								<table class="table">
								<tr>
								<th>Sr.No</th>
								<th>Date</th>
								<th>Application Type</th>
								<th>Message</th>
								<th>Message Destination</th>
								</tr>
								
								<?php for ($i=0; $i<sizeof($sent_email_details); $i++) { ?>
								
								<tr>
								<td><?php echo 	$i+1;?></td>
								<td><?php echo 	$sent_email_details[$i]['Dmi_sent_email_log']['sent_date']?></td>
								<td><?php ?></td>
								<td><?php echo  $sent_email_details[$i]['Dmi_sent_email_log']['message']; ?></td>
								<td><?php echo  $email_destination_list[$i]; ?></td>
								</tr>
								
								<?php } if(empty($sent_email_details)){ ?>
								<tr>
								<td><?php echo "NO Records Available"; ?></td>
								</tr>
								<?php } ?>
								
								</table>
									
			</div>
			<a href="#" class="col-md-12 cf09609" target="blank"><b class="float-right;">Download PDF Version</b></a>
			
			
			<?php //echo $this->Html->link('Back', array('controller' => 'reports', 'action'=>'report_types', 'class'=>'btn btn-info')); ?>
			<div >
			<h5>
			<a href="<?php echo $this->request->getAttribute('webroot');?>reports/report_types" class="report-back-button btn btn-info" role="button">Back</a>
			<?php //echo $this->Html->link('Back', array('controller' => 'reports', 'action'=>'report_types', 'class'=>'btn-primary')); ?>
			</h5>
			</div>
			
		<?php //} ?>
		
		
		
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
		
		
		// for multiselect dropdown option	
		
		/*$('#application_type').multiselect({
            includeSelectAllOption: true,
			nonSelectedText :'Select Application Type',
			buttonWidth: '100%',
            maxHeight: 400,
		});*/
		
		
		
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