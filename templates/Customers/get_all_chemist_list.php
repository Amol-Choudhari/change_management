<?php 	$split_user_name = explode('/',$_SESSION['username']); ?>
<?php echo $this->Html->css('customers/get_all_chemist_list'); ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-info">Registered Chemist</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'customers', 'action'=>'secondary_home'));?></a></li>
						<li class="breadcrumb-item active">Log History</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<body>
		<div class="tab">
			<button class="tablinks active" id="self_registered_chemist_tab" >Self - Registered Chemist</button>
			<?php if ($split_user_name[1] == 1) { ?>
			<button class="tablinks" id="lab_registered_chemist_tab" >Lab Registered Chemist</button> <?php } ?>
			<button class="tablinks" id="allocated_chemist_tab" >Allocated Chemist</button>
		</div>

		<div id="self_registered_chemist" class="tabcontent">
			<div class="card card-dark">
				<div class="card-header blu"><h3 class="card-title-new">Self-Registered Chemist</h3></div>
					<table id="applicant_logs_table" class="table m-0 table-bordered table-hover table-striped border border-primary ">
						<thead class="tablehead">
							<tr>
							<th>Sr.No</th>
							<th>Name</th>
							<th>Chemist ID</th>
							<th>Email</th>
							<th>Registered On</th>
						</tr>
					</thead>
					<tbody>
					<?php if (!empty($self_registered_chemist)) {

						$i=0; $sr_no = 1;

						foreach ($self_registered_chemist as $each) { ?>
						<tr>
							<td><?php echo $sr_no; ?></td>
							<td><?php echo $each['chemist_fname']." ".$each['chemist_lname']; ?></td>
							<td><?php echo $each['chemist_id']; ?></td>
							<td><?php echo base64_decode($each['email']); ?></td>
							<td><?php echo $each['created']; ?></td>
						</tr>
					<?php $sr_no++; $i=$i+1;	} } ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php if ($split_user_name[1] == 1) { ?>
	<div id="lab_registered_chemist" class="tabcontent">
		<div class="card-header cool_blues"><h3 class="card-title-new">Lab Registered Chemist</h3></div>
			<table id="applicant_logs_table" class="table m-0 table-bordered table-striped border border-dark">
				<thead class="tablehead">
					<tr>
						<th>Sr.No</th>
						<th>Name</th>
						<th>Chemist ID</th>
						<th>Email</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if (!empty($lab_registered_chemist)) {
						$i=0;$sr_no = 1;
						foreach($lab_registered_chemist as $each){ ?>
						<tr>
							<td><?php echo $sr_no; ?></td>
							<td><?php echo $chemist_name[$i]; ?></td>
							<td><?php echo $each['chemist_id']; ?></td>
							<td><?php echo base64_decode($chemist_email[$i]); ?></td>
							<td><?php echo $this->Html->link('Allocate', array('controller' => 'customers','action'=>'fetch_chemist_id',$each['id'])); ?> </td>
						</tr>
					<?php $sr_no++; $i=$i+1;	} } ?>
				</tbody>
			</table>
		</div>
	<?php } ?>
		<div id="allocated_chemist" class="tabcontent">
			<div class="card-header pacific_dream"><h3 class="card-title-new">Allocated Chemist</h3></div>
        		<table id="applicant_logs_table" class="table m-0 table-bordered table-striped">
					<thead class="tablehead">
						<tr>
							<th>Sr.No</th>
							<th>Name</th>
							<th>Chemist ID</th>
							<th>Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					<?php if (!empty($alloc_allocated_chemists)) {
							$i=0; $sr_no = 1;
							foreach ($alloc_allocated_chemists as $each) { ?>

								<tr>
									<td><?php echo $sr_no; ?></td>
									<td><?php echo $alloc_chemist_name[$i]; ?></td>
									<td><?php echo $each['chemist_id']; ?></td>
									<td><?php echo base64_decode($alloc_chemist_email[$i]); ?></td>
									<td><?php
										if (empty($chemist_incharge_id)) {
											echo $this->Html->link('Set Incharge', array('controller' => 'customers','action'=>'set_chemist_incharge',$each['id']));
										} else {
											if ($chemist_incharge_id == $each['id']) {
												echo $this->Html->link('Unset Incharge', array('controller' => 'customers','action'=>'unset_chemist_incharge',$each['id']));
											}
										}
										?>
									</td>
								</tr>
							<?php $sr_no++; $i=$i+1; } } ?>
						</tbody>
					</table>
				</div>
			</body>
		</div>

	<?php echo $this->Html->script('customers/get_all_chemist_list'); ?>
