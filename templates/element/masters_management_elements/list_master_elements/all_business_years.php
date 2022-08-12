<?php ?>


<nav>
  <div class="nav nav-tabs"role="tablist">
    <a class="nav-item nav-link active" id="ca_btn" data-toggle="tab" href="#nav-home" role="tab" aria-selected="true">CA Business Years</a>
    <a class="nav-item nav-link" id="printing_btn" data-toggle="tab" href="#nav-profile" role="tab" aria-selected="false">Printing Business Years</a>
    <a class="nav-item nav-link" id="crush_ref_btn" data-toggle="tab" href="#nav-contact" role="tab"  aria-selected="false">Crushing & Refining Periods</a>
  </div>
</nav>

<thead>
	<tr>
		<th>ID</th>
		<th>Business Years</th>
		<th>Action</th>
	</tr>
</thead>

	<tbody id="ca_view">
		<?php
		if (!empty($ca_business_years)) {
			foreach ($ca_business_years as $each_year) { ?>

				<tr>
					<td><?php echo $each_year['id'];?></td>
					<td><?php echo $each_year['business_years'];?></td>
					<td><?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect', $each_year['id'],'0'),array('class'=>'far fa-edit','title'=>'Edit')); ?>
						<?php //echo $this->Html->link('', array('controller' => 'masters', 'action'=>'deleteMasterRecord', $each_year['id']),array('class'=>'glyphicon glyphicon-remove','title'=>'Delete','confirm'=>'Are You Sure to Delete this Record?')); ?></td>

				</tr>

		<?php } } ?>

	</tbody>



	<tbody id="printing_view">
		<?php
		if (!empty($pp_business_years)) {
			foreach ($pp_business_years as $each_year) { ?>

				<tr>
					<td><?php echo $each_year['id'];?></td>
					<td><?php echo $each_year['business_years'];?></td>
					<td><?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect', $each_year['id'],'1'),array('class'=>'far fa-edit','title'=>'Edit')); ?>
						<?php //echo $this->Html->link('', array('controller' => 'masters', 'action'=>'deleteMasterRecord', $each_year['id']),array('class'=>'glyphicon glyphicon-remove','title'=>'Delete','confirm'=>'Are You Sure to Delete this Record?')); ?></td>
				</tr>

		<?php	} } ?>

	</tbody>



	<tbody id="crush_ref_view">
		<?php
		if (!empty($crush_refine_years)) {
			foreach ($crush_refine_years as $each_period) { ?>

				<tr>
					<td><?php echo $each_period['id'];?></td>
					<td><?php echo $each_period['crushing_refining_periods'];?></td>
					<td><?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect', $each_period['id'],'2'),array('class'=>'far fa-edit','title'=>'Edit')); ?>
						<?php //echo $this->Html->link('', array('controller' => 'masters', 'action'=>'deleteMasterRecord', $each_period['id']),array('class'=>'glyphicon glyphicon-remove','title'=>'Delete','confirm'=>'Are You Sure to Delete this Record?')); ?></td>
				</tr>

		<?php	} } ?>

	</tbody>
    <?php echo $this->Html->script('element/masters_management_elements/all_business_types'); ?>
