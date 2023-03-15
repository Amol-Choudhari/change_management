<?php ?>
<!-- The Modal -->
		<div id="scrutiny_alloction_Modal" class="modal">
		
			<div class="modal-dialog modal-dialog-centered">
			  <!-- Modal content -->				  
			  <div class="modal-content">
				<!-- Modal Header -->
					<div class="modal-header">
						<?php if($comm_with=='Not Allocated'){ ?>
							<h4 class="modal-title">Allocation for Scrutiny</h4>
						<?php }else{ ?>
							<h4 class="modal-title">Re-allocation for Scrutiny</h4>
						<?php } ?>
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>

					 <!-- Modal body -->
					<div class="modal-body">
					
						<?php echo $this->Form->create(null,array('id'=>'common_scrutiny_allocation_form')); ?>
							<table id="scrutiny-alloc-table" class="table table-striped table-bordered wd100">
								<thead>
									<tr>
										<th>Application Type</th>
										<th>Application Id</th>
										<th>Scrutiny Officers</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									 
									<tr>
										<td><?php echo $this->Form->control('alloc_appl_type',array('type'=>'text', 'id'=>'alloc_appl_type', 'value'=>$appl_type, 'label'=>false, 'readonly'=>true)); ?></td>
										<td><?php echo $this->Form->control('alloc_customer_id',array('type'=>'text', 'id'=>'alloc_customer_id', 'value'=>$customer_id, 'label'=>false, 'readonly'=>true)); ?></td>
										<td><?php echo $this->Form->control('mo_users_list',array('type'=>'select', 'id'=>'mo_users_list', 'options'=>$mo_users_list, 'label'=>false,)); ?>
										</td>
										<td>
										
										<?php if($comm_with=='Not Allocated'){ ?>
											<a href="#" class="allocate_btn"  id="scrutiny_allocate_btn">Allocate</a>
											
										<?php }else{ ?>
											<a href="#" class="allocate_btn"  id="scrutiny_allocate_btn">Reallocate</a>
										<?php } ?>


										</td>
									</tr>
									
								</tbody>
							</table>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>	

			</div>
		</div>
		
		<!-- applied on 03-02-2023 by Amol-->
		<input type="hidden" id="comm_with" value="<?php echo $comm_with; ?>" >

<?php 
//commented script call, now all script is in common-count-js.js file as function call, changed on 21-10-2021 by Amol
//echo $this->Html->script('dashboard/scrutiny-alloc-js'); 
exit; //intensionally added exit; on 07-10-2021 by Amol, as CSRF blocking the next request after rendering the view through ajax ?>	
