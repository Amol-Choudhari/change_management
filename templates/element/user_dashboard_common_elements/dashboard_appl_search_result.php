<!-- below search result code added on 11-05-2017 by Amol -->
<?php if(!empty($get_firm_data)){ ?>

<h5>Given Details for Applicant Id: <?php echo $customer_id; ?></h5>
<table class="table table-striped table-bordered wd100">
	<thead>
		<tr>
			<th>Firm Name</th>
			<th>Certificate Type</th>
			<th>Commodity</th>
			<th>District</th>
			<th>Position</th>
			<th>Action</th>
		</tr>
	</thead>

		<tbody>
				<tr>
					<td><?php echo $get_firm_data['firm_name'];?></td>
					<td><?php echo $get_firm_data['certification_type'];?></td>
					<td><?php echo $get_firm_data['commodity'];?></td>
					<td><?php echo $get_firm_data['district'];?></td>
					<td><?php echo $current_position;?></td>
					<td><?php
						$split_customer_id = explode('/',$customer_id);

					//below conditions added on 25-11-2017 by Amol
						if(!empty($check_if_renewal_applied)){
							$action_name = 'form_scrutiny_fetch_id/'.$get_firm_data['id'].'/view/2';

						}else{

							$action_name = 'form_scrutiny_fetch_id/'.$get_firm_data['id'].'/view/1';
						}
						echo $this->Html->link('View', array('controller' => 'scrutiny', 'action'=>$action_name),array('target'=>'_blank')); ?></td>

				</tr>


		</tbody>


</table>

<?php }elseif(!empty($no_result)){ ?>

	<table class="table table-striped table-bordered wd100">
		<thead>
			<tr>
				<td><?php echo $no_result; ?></th>
			</tr>
		</thead>
	</table>

<?php } ?>
