<?php echo $this->Html->css('dashboard/common-rej-appl-popup-css'); ?>

<!-- The Modal -->
		<div id="common_reject_Modal" class="modal">
			<div class="modal-dialog modal-dialog-centered">
			  <!-- Modal content -->				  
			  <div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header">
					<h4>Rejection of Application</h4>
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
					 <!-- Modal body -->
				<div class="modal-body">
			
					<?php echo $this->Form->create(null); ?>
					<table id="rej-appl-table" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Application Type</th>
								<th>Application Id</th>
								<th>Remark/Reason</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							 
							<tr>
								<td><?php echo $this->Form->control('rej_appl_type',array('type'=>'text', 'id'=>'rej_appl_type', 'value'=>$appl_type, 'label'=>false, 'readonly'=>true)); ?></td>
								<td><?php echo $this->Form->control('rej_customer_id',array('type'=>'text', 'id'=>'rej_customer_id', 'value'=>$customer_id, 'label'=>false, 'readonly'=>true)); ?></td>
								<td><?php echo $this->Form->control('rej_remark',array('type'=>'textarea', 'id'=>'rej_remark', 'label'=>false, 'required'=>true)); ?>
								</td>
								<td>
								
								<a href="#" class="reject_btn"  id="reject_appl_btn">Reject</a>

								</td>
							</tr>
							
						</tbody>
					</table>
					<?php echo $this->Form->end(); ?>
			
				</div>				 
			</div>
			</div>
		</div>

<?php 
//commented script call, now all script is in common-count-js.js file as function call, changed on 21-10-2021 by Amol
//echo $this->Html->script('dashboard/common-rej-appl-popup-js'); 
exit; ?>