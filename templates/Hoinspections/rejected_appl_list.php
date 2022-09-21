<?php ?>
<?php echo $this->Form->create(null, array()); ?>
	<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title-new">List of all Rejected/Junked Applications</h3>
			</div>
								
			<table id="all_rejected_appl_list" class="table m-0 table-bordered table-striped table-hover">
				<thead class="tablehead">
					<tr>
						<th>Appl. Type</th>
						<th>Appl. Id</th>
						<th>Firm Name</th>
						<th>By USer</th>
						<th>On Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i=0;
						if (!empty($appl_array)) {

							foreach ($appl_array as $each) { ?>

								<tr>
									<td><?php echo $each['appl_type']; ?></td>
									<td><?php echo $each['customer_id']; ?></td>
									<td><?php echo $each['firm_name']; ?></td>
									<td><?php echo $each['by_user']; ?></td>
									<td><?php echo $each['on_date']; ?></td>
									<td><a href="<?php echo $each['appl_view_link']; ?>">View</a></td>
							</tr>
						<?php	$i=$i+1; } } ?>
					</tbody>
				</table>
			</div>
<?php echo $this->Form->end(); ?>

<input type="hidden" id="i-value" value="<?php echo $i; ?>">
<?php echo $this->Html->script('dashboard/rejected_appl_list_js'); ?>
