<?php


$current_action = $this->request->getParam('action');

// To Display the proper name in list instead of showing the varilable name (by pravin 05/05/2017)
$current_action_split_value = explode('_',$current_action);
$current_action_name = ucwords(implode(" ",$current_action_split_value));

//calling jquery for table filter here as ajax applied and set layout is turned off
//on 08-06-2017 by Amol
echo $this->Html->script('table_filter');


	if ($current_action == 'pending_chemist_verification') {
		$action = 'Scrutinize';
	} elseif ($current_action  == 'referred_back_chemist_verification') {
		$action = 'View';
	} elseif ($current_action == 'replied_chemist_details') {
		$action = 'Scrutinize';
	} elseif ($current_action=='chemist_confirmed') {
		$action = 'View';
	}

?>


<div class="table-format">
<div class="inspection">
<div class="panel panel-primary filterable">
	<div class="panel-heading">
		<div class="admin-main-page">
			<h5>Given Below is list of <?php echo $current_action_name; ?></h5>
			<div class="pull-right">
				<button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span>Filter Table</button>
			</div>
		</div>
	</div>
<table class="table">
	<thead>
		<tr class="filters">
			<th><input type="text" class="form-control" placeholder="Id" disabled></th>
			<th><input type="text" class="form-control" placeholder="Chemist Name" disabled></th>
			<th><input type="text" class="form-control" placeholder="Registered By" disabled></th>
			<th><input type="text" class="form-control" placeholder="Action" disabled></th>
		</tr>
	</thead>
	<tbody>
		<?php

		if(!empty($chemist_list)){
			$i=1;
			foreach($chemist_list as $chemist_detail){ ?>

				<tr>
					<td><?php echo $chemist_detail['chemist_id']; ?></td>
					<td><?php echo $chemist_detail['chemist_fname'].' '.
							  $chemist_detail['chemist_lname'] ?></td>
					<td><?php echo $chemist_detail['created_by']; ?></td>
					<td><?php  echo $this->Html->link($action, array('controller' => 'chemist', 'action'=>'inspect_chemist_details', $chemist_detail['id'])); ?></td>

				</tr>

		<?php $i=$i+1;	} }else{ ?>
				<tr>

					<td></td>
					<td>Currently there are no <?php echo $current_action_name; ?></td>

				</tr>
		<?php	} ?>
	</tbody>
</table>
</div>
</div>
</div>
