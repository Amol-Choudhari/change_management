<?php
	echo $this->Html->script('jquery.dataTables.min');
	echo $this->Html->css('jquery.dataTables.min');
?>
<style>
	.filterable { margin-top: 0px;  }
	.panel-heading { height: auto; margin-bottom: 10px; }
	.pages-table-format td { font-size: 14px !important; }
	.ro_report-filter input[type="submit"] { margin-top: 66%; width: 74px; }
	
</style>

<div class="pages-table-format">
	<?php echo $this->Form->create('Dmi_firm',array('type'=>'file','enctype'=>'multipart/form-data', 'id'=>'application_journey')); ?>
	<div class="panel panel-primary filterable">				
		<div class="panel-heading">		
			<div id="search_by_options" class="col-md-12">	
				<div class="report-filter ro_report-filter " class="col-md-12">	
					<div class="col-md-3">
						<label>Application Type</label>
						<?php echo $this->form->input('application_type', array('type'=>'select', 'options'=>$certificate_type, 'label'=>false, 'id'=>'application_type', 'empty'=>'All', 'escape'=>false, 'class'=>'search_field')); ?>
					</div>					
					<div class="col-md-3">
						<label>RO/SO Office</label>
						<?php echo $this->form->input('office_type', array('type'=>'select', 'options'=>$ro_office_list, 'label'=>false, 'id'=>'office_type', 'empty'=>'All', 'escape'=>false, 'class'=>'search_field')); ?>
					</div>
					
					<div class="col-md-2" id="office_all">
						<label>Status</label>
						<?php echo $this->form->input('result_for', array('type'=>'select', 'options'=>$result_for, 'label'=>false, 'id'=>'result_for',  'empty'=>'All', 'escape'=>false, 'class'=>'search_field')); ?>
					</div>
					<div class="col-md-3" id="pending_div">
						<label>Pending With</label>
						<?php echo $this->form->input('pending_with', array('type'=>'select', 'options'=>$pending_with, 'label'=>false, 'id'=>'pending_with',  'empty'=>'All', 'escape'=>false, 'class'=>'search_field')); ?>
					</div>
					<div class="col-md-1">
						<input style="background:#747474; color:#fff;" id="search_btn" type="submit" name="search_logs" class="form-control" value="Search" >
					</div>
				</div>	
				<div class="clearfix"></div>								
			</div>		
			<div class="clearfix"></div>		
		</div>
		<div class="clearfix"></div>	
		<table id="firms_list">
			<thead>					
					<th>Sr.No.</th>
					<th>Firm Name</th>
					<th>Firm Id</th>
					<th>Firm Type</th><!-- added on 27-07-2018 by Amol -->
					<th>RO/SO Office</th>
					<th>Action</th>
			</thead>
			<tbody>
			<?php  if(!empty($all_firms_list)){
					$i=1;
					foreach($all_firms_list as $each_firm){ ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $each_firm['Dmi_firms']['firm_name']; ?></td>
					<td><?php echo $each_firm['Dmi_firms']['customer_id']; ?></td>
					<td><?php echo $each_firm['ct']['certificate_type']; ?></td>
					<td><?php echo $each_firm['roo']['ro_office']; ?></td>
					<td><?php echo $this->Html->link('View Status', array('controller' => 'applicationstatushistory', 'action'=>'application_status_history', $each_firm['Dmi_firms']['id'])); ?></td>
				</tr>
			<?php $i=$i+1; } } ?>
			</tbody>
		</table>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">	
	$('#firms_list').DataTable({
		lengthMenu: [10, 20, 50]
	});
	$("#pending_div").hide();
	
	$("#result_for").change(function(){
		var result_for = $("#result_for").val();
	  if(result_for == 'pending'){
		  $("#pending_div").show();
	  }else{
		  $("#pending_with").val('');
		  $("#pending_div").hide();
	  }
	}); 
	
</script>