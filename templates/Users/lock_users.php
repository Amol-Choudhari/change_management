<?php ?>
<?php echo $this->Html->css('lock_users'); ?>

<?php

$lockListFor = $_SESSION['lockListFor'];
if($lockListFor=='primary'){
	$logsTablename = 'DmiCustomerLogs';
	$listFor = 'Primary Applicants';

}elseif($lockListFor=='secondary'){
	$logsTablename = 'DmiCustomerLogs';
	$listFor = 'Secondary Applicants';

}elseif($lockListFor=='dmiUsers'){
	$logsTablename = 'DmiUserLogs';
	$listFor = 'DMI/LIMS Users';
}

?>

	<div class="content-wrapper">
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h6 class="badge badge-primary">All locked Accounts of <?php echo $listFor; ?></h6>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
							<li class="breadcrumb-item active">Unlock Users</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<section class="content form-middle">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card card-cyan">
							<div class="card-header"><h3 class="card-title-new">Given Below is list of All Users</h3></div>
								<div class="card-body">
									<?php echo $this->Form->create(); ?>
										<div class="panel panel-primary">
											<table id="locked_user_list" class="table m-0 table-bordered table-hover">
												<thead class="tablehead">
													<tr>
														<th>Sr.No.</th>
														<th>Name</th>
														<th>User ID</th>
														<th><?php if(!empty($all_users)){ echo $this->Form->control('Unlock All', array('type'=>'submit', 'name'=>'unlockbtn', 'id'=>'unlockbtn', 'class'=>'unlockbtn' ,'label'=>false)); } ?></th>
													</tr>
												</thead>
												<tbody>
													<?php
													if(!empty($all_users)){

														$sr_no =1;
														$i=0;
														foreach($all_users as $each_user){ ?>

															<tr class="checkboxinput">
																<td><?php echo $sr_no; ?></td>

																<?php if($lockListFor=='primary'){ ?>
																	<td><?php echo $each_user['f_name'].' '.$each_user['l_name']; ?></td>
																	<td><?php echo $each_user['customer_id'];?></td>

																<?php }elseif($lockListFor=='secondary'){ ?>
																	<td><?php echo $each_user['firm_name']; ?></td>
																	<td><?php echo $each_user['customer_id'];?></td>

																<?php }elseif($lockListFor=='dmiUsers'){ ?>
																	<td><?php echo $each_user['f_name'].' '.$each_user['l_name']; ?></td>
																	<td><?php echo base64_decode($each_user['email']); //for email encoding ?></td>
																<?php } ?>


																<td><?php echo $this->Form->control('Unlock', array('type'=>'submit', 'name'=>$each_user['id'], 'id'=>'unlockbtn', 'class'=>'unlockbtn' ,'label'=>false)); ?>
																</td>
															</tr>

													<?php $sr_no++;
														$i=$i+1;} } ?>
												</tbody>
											</table>
										</div>
									<?php echo $this->Form->end(); ?>
								</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

		
<?php echo $this->Html->script('Users/lock_users'); ?>
