<?php ?>

	<thead>
		<tr>
			<th>Sr.No</th>
			<th>Office</th>
			<th>Office Incharge ID</th>
			<th>Office Type</th>

			<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<?php
		if (!empty($all_records)) {
			$sr_no=1;
			foreach ($all_records as $each_record) { ?>

				<tr>
					<td><?php echo $sr_no; ?></td>
					<td><?php echo $each_record['ro_office'];?></td>
					<td><?php echo base64_decode($each_record['ro_email_id']); //for email encoding ?></td>
					<td class="boldtext"><?php echo $each_record['office_type'];?></td>
					<td>
						<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect', $each_record['id']),array('class'=>'far fa-edit','title'=>'Edit')); ?> |
						<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'deleteMasterRecord', $each_record['id']),array('class'=>'glyphicon glyphicon-remove delete_office','title'=>'Delete')); ?>
					</td>
				</tr>

		<?php	$sr_no++; } } ?>

	</tbody>
	
<?php echo $this->Html->script('element/masters_management_elements/all_offices'); ?>
