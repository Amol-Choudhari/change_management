
<?php if($process_query == 'Updated' && $final_submit_status != 'no_final_submit'){  ?>
	<div class="col-md-12 p-0" id="comment_reply_box">
		<div class="card card-dark">

	      <div class="card-header">
	        <h3 class="card-title"><i class="fa fa-comments"></i> Previous Communication</h3>
	      </div>
	      <div class="form-horizontal">
	      	<div class="card-body p-0  rounded">
	          <div class="row m-0">

	            <div class="col-sm-12 p-0">
					<div class="machinery_table">
						<!-- call table view form element with ajax call -->
						<div class="table-format">
							<table id="tbls_table_view" class="table table-bordered table-striped text-sm mb-0">
								<thead>
									<tr>
										<th>Date</th>
										<th>Remark</th>
										<th>Remark upload</th>
										<th>Reply</th>
										<th>Reply upload</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>

									<!-- taking last id of applicant reply // added on 14-04-2017 by Amol -->
									<?php
										$reply_max_id = null;
										foreach($fetch_comment_reply as $comment_reply){
										if(!empty($comment_reply['reffered_back_date'])){

											$reply_max_id = $comment_reply['id'];

										}}

									?>

									<?php foreach($fetch_comment_reply as $comment_reply){

										//view only rows with values.
										if(!empty($comment_reply['reffered_back_date'])){?>

										<!-- Below code changed and added on 14-04-2017 by Amol for edit/delete Applicant reply
										This will show on click of edit button which create session for edit -->
										<?php if($this->getRequest()->getSession()->read('edit_reply_id') != null){?>
											<tr>
												<?php if($comment_reply['id']==$reply_max_id &&
														$current_form_data['id']==$reply_max_id &&
														$show_applicant_edit_delete == 'yes'){?>

													<td><?php echo $comment_reply['reffered_back_date']; ?></td>
													<td><?php echo $comment_reply['reffered_back_comment']; ?></td>
													<td><?php if($comment_reply['rb_comment_ul'] != null){?>
														<a target="blank" id="rb_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rb_comment_ul']); ?>">Preview</a>
														<?php }?>
													</td>
																													<!-- give id to edit comment box by pravin 26/07/2017-->
													<td><?php echo $this->Form->control('edited_reply', array('type'=>'textarea', 'id'=>'check_save_reply', 'value'=>$comment_reply['customer_reply'], 'escape'=>false,'label'=>false, 'class'=>'form-control')); ?>
													<div id="error_save_reply" class="text-red float-right"></div> <!--create div field for showing error message (by pravin 26/07/2017)-->
													</td>
													<td><?php if($comment_reply['cr_comment_ul'] != null){?>
														<a target="blank" id="cr_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['cr_comment_ul']); ?>">Preview</a>
														<?php }?>
														</span>

														<div class="custom-file col-sm-9">
										                  <input type="file" name="cr_comment_ul" class="custom-file-input" id="edit_cr_comment_ul" onchange='file_browse_onclick(id);return false', multiple='multiple'>
									                      <label class="custom-file-label" for="customFile">Choose file</label>
									                    </div>

														<label id="fileLabel"></label>
													</td>
													<td>																								<!-- call comment box validation function by pravin 26/07/2017-->
														<?php echo $this->form->submit('save', array('name'=>'save_edited_reply', 'id'=>'save_edited_reply', 'onclick'=>'comment_reply_box_validation();return false', 'label'=>false, 'class'=>'form-control')); ?>
													</td>
												<?php } ?>
											</tr>


										<?php }else{ ?>

											<tr>

												<td><?php echo $comment_reply['reffered_back_date']; ?></td>
												<td><?php echo $comment_reply['reffered_back_comment']; ?></td>
												<td><?php if($comment_reply['rb_comment_ul'] != null){?>
													<a target="blank" id="rb_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['rb_comment_ul']); ?>">Preview</a>
													<?php }?>
												</td>
												<td><?php echo $comment_reply['customer_reply']; ?></td>
												<td><?php if($comment_reply['cr_comment_ul'] != null){?>
													<a target="blank" id="cr_comment_ul_value" href="<?php echo str_replace("D:/xampp/htdocs","",$comment_reply['cr_comment_ul']); ?>">Preview</a>
													<?php }?>
												</td>
												<td><?php if($comment_reply['id']==$reply_max_id &&
														$current_form_data['id']==$reply_max_id &&
														$show_applicant_edit_delete == 'yes'){?>

														<?php //echo $this->Html->link('', array(),array('id'=>'edit_reply','class'=>'far fa-edit comment_reply_edit_btn', 'title'=>'Edit')); ?> 
														<?php //echo $this->Html->link('', array(),array('id'=>'delete_reply','class'=>'far fa-trash-alt comment_reply_delete_btn', 'title'=>'Delete')); ?>

														<div class="btn-group btn-group-sm">
															<?php echo $this->Html->link('', array(),array('id'=>'edit_reply','class'=>'glyphicon glyphicon-edit mr-2 comment_reply_edit_btn btn btn-info', 'title'=>'Edit', 'escape'=>false)); ?>  
															<?php echo $this->Html->link('', array(),array('id'=>'delete_reply','class'=>'glyphicon glyphicon-remove-sign comment_reply_delete_btn btn btn-danger', 'title'=>'Delete', 'escape'=>false)); ?>
														</div>

													<?php } ?>

												</td>

											</tr>

										<?php } ?>

									<?php }
									}?>
								</tbody>
							</table>

							<!-- this field is hidden and send max id and model name for edit/delete mo comment by ajax // added on 11-04-2017 by Amol-->
							<?php echo $this->Form->control('reply_max_id', array('type'=>'hidden', 'id'=>'reply_max_id', 'value'=>$reply_max_id, 'label'=>false,)); ?>
							<?php echo $this->Form->control('model_name', array('type'=>'hidden', 'id'=>'model_name', 'value'=>$tablename, 'label'=>false,)); ?>

						</div>
					</div>
            	</div>
            </div>
          </div>
      	</div>
	</div>
      	<!--same above conditions for edit/delete options for Reply are applied here with NOT(opposite) operator to show reply box -->
		<?php
			if($final_submit_status == "referred_back"){
				if(!($comment_reply['id']==$reply_max_id &&
					$current_form_data['id']==$reply_max_id &&
					$show_applicant_edit_delete == 'yes')){?>
		<div class="card card-dark">			
          <div class="card-header">
            <h3 class="card-title"><i class="fa fa-share"></i> Current Reply</h3>
          </div>
          <div class="form-horizontal">
          	<div class="card-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Customer Reply <span class="cRed">*</span></label>
                    <div class="col-sm-9">
	                  <?php echo $this->Form->control('customer_reply', array('type'=>'textarea', 'id'=>'check_save_reply', 'escape'=>false, 'label'=>false, 'class'=>'form-control')); ?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-3 col-form-label">Upload File:
						</label>
						<div class="custom-file col-sm-9">
		                  <input type="file" name="cr_comment_ul" class="custom-file-input" id="cr_comment_ul" onchange='file_browse_onclick(id);return false', multiple='multiple'>
	                      <label class="custom-file-label" for="customFile">Choose file</label>
	                    </div>
					</div>
                </div>
              <div id="error_check_save_reply" class="error invalid-feedback"></div> <!--create div field for showing error message (by pravin 07-07-2017)-->
              </div>
          	</div>
          </div>
          <?php } } ?>
		</div>
		
	</div>
<?php }  ?>
<?php echo $this->Html->script('element/communications_elements/applicant_side_communication'); ?>	
