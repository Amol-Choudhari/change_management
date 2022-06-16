<?php ?>
<style>
.glyphicon{margin-right:15px;}
#jtama_jat_status_list_div input[type="text"]{max-width:126px;border:none;background:#f9f9f9;}
</style>

<?php
$current_action = $this->request->getParams('action'); 	
?>

<!-- for common applications listing-->
<div id="jtama_jat_status_list_div">

<table id="jtama_jat_status_list_table" class="table table-striped table-bordered" style="width:100%">
	<thead class="tablehead">
		<tr>
			<th>Application Type</th>
			<th>Application Id</th>
			<th>Firm Name</th>
			<th>Forwarded On</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$i=1;
			foreach($appl_list_array as $each){ //print_r($each); ?>
			<tr>
				<td data-search="<?php echo ucwords($each['appl_type']); ?>" data-order="<?php echo ucwords($each['appl_type']); ?>"><?php echo $this->Form->control('appl_type',array('type'=>'text', 'id'=>'appl_type'.$i, 'value'=>ucwords($each['appl_type']), 'label'=>false, 'readonly'=>true)); ?></td>
				<td data-search="<?php echo $each['customer_id']; ?>" data-order="<?php echo $each['customer_id']; ?>"><?php echo $this->Form->control('customer_id',array('type'=>'text', 'id'=>'customer_id'.$i, 'value'=>$each['customer_id'], 'label'=>false, 'readonly'=>true)); ?></td>
				<td><?php echo $each['firm_name'];?></td>
				<td><?php echo $each['forwarded_on'];?></td>
				
				<td>
					<a title="View Application" href="<?php echo $each['appl_view_link'];?>"><span class="glyphicon glyphicon-eye-open"></span></a>

					<a title="Create JAT" href="<?php echo $each['appl_edit_link'];?>"><span class="glyphicon far fa-edit"></span></a>
				</td>
			</tr>
		<?php $i=$i+1; } ?>
		
		
	</tbody>
</table>

</div>


<script>
$(document).ready(function() {
    $('#jtama_jat_status_list_table').DataTable({"ordering": false});

});

</script>

