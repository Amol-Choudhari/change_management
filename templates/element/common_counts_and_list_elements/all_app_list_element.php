<?php ?>

<?php echo $this->Html->css('elements/all_app_list_element'); ?>
<!-- for pending applications listing-->
<div id="all_app_list_div">
	<h4 id="list_heading_text" class="cOrange">Pending List</h4>
		<table id="all_pending_app" class="table table-striped table-bordered w100">
			<thead>
				<tr>
					<th>App. Type</th>
					<th>Form Type</th>
					<th>App. Id</th>
					<th>Firm Name</th>
					<?php if($_SESSION['current_level']=='level_2'){ //for IO user only for Reports ?>
						<th>Scheduled date</th>
					<?php }else{ ?>
						<th>Comm. with</th>
					<?php } ?>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($pending_list as $each){ ?>
					<tr>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['form_type'];?></td>
						<td><?php echo $each['customer_id'];?></td>
						<td><?php echo $each['firm_name'];?></td>
						<?php if($_SESSION['current_level']=='level_2'){ //for IO user only for Reports ?>
						<td><?php echo $this->form->input('io_scheduled_date',array('type'=>'text', 'id'=>'io_scheduled_date', /*'value'=>$io_scheduled_date_ca[$i],*/ 'class'=>'io_scheduled_date flw80', 'readonly'=>true, 'label'=>false)); ?>
							<?php echo $this->form->submit('', array('name'=>'change_date', 'id'=>'change_date', 'class'=>'change_date', 'title'=>'Change', 'label'=>false)); ?>
						</td>
						<?php }else{ ?>
							<td><?php echo $each['comm_with'];?></td>
						<?php } ?>
						<td><a title="View" href="#"><span class="glyphicon glyphicon-eye-open"></span></a>
							<a title="Scrutiny" href="#"><span class="glyphicon far fa-edit"></span></a>
							<a title="Allocate" href="#"><span class="glyphicon glyphicon-share-alt"></span></a>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

		<?php if($_SESSION['current_level']=='level_2'){ ?>
		<h4 id="list_heading_text" class="cOrange">Reports Filed List</h4>
		<table id="all_reports_filed" class="table table-striped table-bordered w100">
			<thead>
				<tr>
					<th>App. Type</th>
					<th>Form Type</th>
					<th>App. Id</th>
					<th>Firm Name</th>
					<th>Comm. with</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php /*foreach($pending_list as $each){ ?>
					<tr>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['customer_id'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><a title="View" href="#"><span class="glyphicon glyphicon-eye-open"></span></a>
							<a title="Scrutiny" href="#"><span class="glyphicon far fa-edit"></span></a>
							<a title="Allocate" href="#"><span class="glyphicon glyphicon-share-alt"></span></a>
						</td>
					</tr>
				<?php }*/ ?>
			</tbody>
		</table>
		<?php } ?>


		<h4 id="list_heading_text">Referred Back List</h4>
		<table id="all_ref_back_app" class="table table-striped table-bordered w100">
			<thead>
				<tr>
					<th>App. Type</th>
					<th>Form Type</th>
					<th>App. Id</th>
					<th>Firm Name</th>
					<th>Comm. with</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php /*foreach($pending_list as $each){ ?>
					<tr>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['customer_id'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><a title="View" href="#"><span class="glyphicon glyphicon-eye-open"></span></a>
							<a title="Scrutiny" href="#"><span class="glyphicon far fa-edit"></span></a>
							<a title="Allocate" href="#"><span class="glyphicon glyphicon-share-alt"></span></a>
						</td>
					</tr>
				<?php }*/ ?>
			</tbody>
		</table>


		<h4 id="list_heading_text" class="cOrange">Replied List</h4>
		<table id="all_replied_app" class="table table-striped table-bordered w100">
			<thead>
				<tr>
					<th>App. Type</th>
					<th>Form Type</th>
					<th>App. Id</th>
					<th>Firm Name</th>
					<th>Comm. with</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php /*foreach($pending_list as $each){ ?>
					<tr>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['customer_id'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><a title="View" href="#"><span class="glyphicon glyphicon-eye-open"></span></a>
							<a title="Scrutiny" href="#"><span class="glyphicon far fa-edit"></span></a>
							<a title="Allocate" href="#"><span class="glyphicon glyphicon-share-alt"></span></a>
						</td>
					</tr>
				<?php }*/ ?>
			</tbody>
		</table>



		<h4 id="list_heading_text" class="cOrange">Approved List</h4>
		<table id="all_approved_app" class="table table-striped table-bordered w100">
			<thead>
				<tr>
					<th>App. Type</th>
					<th>Form Type</th>
					<th>App. Id</th>
					<th>Firm Name</th>
					<th>Comm. with</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php /*foreach($pending_list as $each){ ?>
					<tr>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['customer_id'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><a title="View" href="#"><span class="glyphicon glyphicon-eye-open"></span></a>
							<a title="Scrutiny" href="#"><span class="glyphicon far fa-edit"></span></a>
							<a title="Allocate" href="#"><span class="glyphicon glyphicon-share-alt"></span></a>
						</td>
					</tr>
				<?php }*/ ?>
			</tbody>
		</table>


		<?php if($_SESSION['current_level']=='level_3'){ ?>
		<h4 id="list_heading_text" class="cOrange">Rejected List</h4>
		<table id="all_rejected_app" class="table table-striped table-bordered w100">
			<thead>
				<tr>
					<th>App. Type</th>
					<th>Form Type</th>
					<th>App. Id</th>
					<th>Firm Name</th>
					<th>Comm. with</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php /*foreach($pending_list as $each){ ?>
					<tr>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['customer_id'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><?php echo $each['appl_type'];?></td>
						<td><a title="View" href="#"><span class="glyphicon glyphicon-eye-open"></span></a>
							<a title="Scrutiny" href="#"><span class="glyphicon far fa-edit"></span></a>
							<a title="Allocate" href="#"><span class="glyphicon glyphicon-share-alt"></span></a>
						</td>
					</tr>
				<?php }*/ ?>
			</tbody>
		</table>
		<?php } ?>

</div>

<?php echo $this->Html->script('element/common_counts_and_list_elements/all_app_list_element'); ?>