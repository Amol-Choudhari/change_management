<?php $i=null; ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-primary">Work Transfer Request</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'dashboard', 'action'=>'home'));?></a></li>
						<li class="breadcrumb-item active">Work Transfer</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
        <section class="content form-middle ">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<?php echo $this->Form->create(); ?>
							<div class="card card-cyan">
								<div class="card-header"><h3 class="card-title-new">User Work Transfer</h3></div>
									<div class="form-horizontal">
										<h5 class="middle mt-3"><span class="badge badge-success">To Be Used On Retirement OR When Need To Transfer Entire Work</span></h5>
											<div class="card-body">
												<div class="col-sm-12">
                                                    <div class="textCenter"><?php echo $this->Html->image('ajax-loader.gif', array('id'=>'work_transfer_loader', 'class'=>'dnmt50')); ?></div>
                                                        <table id = "request_list" class="table m-0 table-bordered table-striped table-hover">
                                                            <thead class="tablehead">
                                                                <tr>
                                                                    <th>S. No</th>
                                                                    <th>By Office</th>
                                                                    <th>By User (RO/SO)</th>
                                                                    <th>For User</th>
                                                                    <th>Requested On</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                                if (!empty($allRequests)) {
                                                                $i=1;
                                                                foreach ($allRequests as $eachRequest) { ?>

                                                                <tr>
                                                                    <td><?php echo $i; ?></td>
                                                                    <td id="req_by_office<?php echo $i;?>"><?php echo $office_name[$i]; ?></td>
                                                                    <td id="req_by_user<?php echo $i;?>"><?php echo base64_decode($eachRequest['req_by_user']); //for email encoding ?></td>
                                                                    <td id="req_for_user<?php echo $i;?>"><?php echo base64_decode($eachRequest['req_for_user']); //for email encoding ?></td>
                                                                    <td><?php echo $eachRequest['created']; ?></td>

                                                                    <td>
                                                                        <?php if ($eachRequest['status'] == 'Requested') { ?>

                                                                            <a id="action_btn<?php echo $i;?>" title="Click to Permit"><span class="glyphicon glyphicon-ok cpcg"></span></a> |
                                                                            <a id="reject_btn<?php echo $i;?>" title="Click to Reject"><span class="glyphicon glyphicon-remove cpcr"></span></a>

                                                                        <?php } elseif ($eachRequest['status'] == 'Permitted') { ?>

                                                                            <span class="fwB_cG">Permitted</span>

                                                                        <?php } elseif ($eachRequest['status'] == 'Rejected') { ?>

                                                                            <span class="fwB_cR">Rejected</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
															<?php $i=$i+1; } } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    <?php echo $this->form->end(); ?>
                </div>
            </div>
        </div>
    </section>
</div>
<input type="hidden" id="id_for_tranfer" value="<?php echo $i; ?>">
<?php echo $this->Html->script('othermodules/work_transfer_requests'); ?>
