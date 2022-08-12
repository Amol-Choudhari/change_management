<?php ?>

<thead>
	<tr>
		<th>SR.No</th>
		<th>Mineral name</th>	
		<th>Grade code</th>
		<th>Grade name</th>	
		<th>Action</th>
	</tr>
</thead>

<tbody>
	<tr>
		<td>1</td>
		<td>AGATE</td>		
		<td>0</td>
		<td>Agate</td>		
		<td>
			<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect'),array('class'=>'far fa-edit','title'=>'Edit')); ?> | 
			<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'deleteMasterRecord'),array('class'=>'glyphicon glyphicon-remove','title'=>'Delete','confirm'=>'Are You Sure to Delete this Record?')); ?>				
		</td>				
	</tr>
	<tr>
		<td>2</td>
		<td>ANDALUSITE</td>		
		<td>0</td>
		<td>Andalusite</td>		
		<td>
			<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect'),array('class'=>'far fa-edit','title'=>'Edit')); ?> | 
			<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'deleteMasterRecord'),array('class'=>'glyphicon glyphicon-remove','title'=>'Delete','confirm'=>'Are You Sure to Delete this Record?')); ?>				
		</td>				
	</tr>
	<tr>
		<td>3</td>
		<td>ASBESTOS</td>		
		<td>1</td>
		<td>Chrysotile</td>	
		<td>
			<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect'),array('class'=>'far fa-edit','title'=>'Edit')); ?> | 
			<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'deleteMasterRecord'),array('class'=>'glyphicon glyphicon-remove','title'=>'Delete','confirm'=>'Are You Sure to Delete this Record?')); ?>				
		</td>				
	</tr>
	<tr>
		<td>4</td>
		<td>ASBESTOS</td>		
		<td>2</td>
		<td>Amphibole</td>	
		<td>
			<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect'),array('class'=>'far fa-edit','title'=>'Edit')); ?> | 
			<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'deleteMasterRecord'),array('class'=>'glyphicon glyphicon-remove','title'=>'Delete','confirm'=>'Are You Sure to Delete this Record?')); ?>				
		</td>				
	</tr>
	<tr>
		<td>5</td>
		<td>BALL CLAY</td>		
		<td>0</td>
		<td>Crude (Natural)</td>	
		<td>
			<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect'),array('class'=>'far fa-edit','title'=>'Edit')); ?> | 
			<?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'deleteMasterRecord'),array('class'=>'glyphicon glyphicon-remove','title'=>'Delete','confirm'=>'Are You Sure to Delete this Record?')); ?>				
		</td>				
	</tr>
</tbody>