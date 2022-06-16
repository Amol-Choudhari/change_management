<?php ?>

<h5 class="cRed;">Applicant ID: <?php echo $_SESSION['customer_id']; ?> - <?php echo $firm_name; ?></h5>
	<div class="form-style-3">
		<?php echo $this->Form->create(); ?>
			<h2>HO Level Verification</h2>
				<fieldset><legend>View Application/Download PDF</legend>
					<div class="form-buttons">
						<div class="col-md-6">

							<?php echo $this->Form->submit('View Applicant Forms', array('name'=>'view_applicant_forms', 'id'=>'view_applicant_forms', 'label'=>false, 'class'=>'wd180')); ?>
							<?php
							//this condition is added on 04-09-2017 by Amol,if report not filed
							if(!empty($report_pdf_path)){

								echo $this->Form->submit('View Inspection Reports', array('name'=>'view_inspection_reports', 'id'=>'view_inspection_reports', 'label'=>false, 'class'=>'wd180'));

							}elseif(!empty($check_jat_report_filed)){//this link added on 18-09-2017 by Amol for JAT report Pdf

								echo $this->Html->link('View JAT Inspection Report', array('controller' => 'jatinspections', 'action'=>'view_jat_final_report_pdf'));
							}
							?>

						</div>

						<div class="col-md-6">

							<a target="blank" href="<?php echo $download_application_pdf;?>" class="w238fs12" >Download Application PDF</a>

							<?php if(!empty($report_pdf_path)){ //this condition is added on 04-09-2017 by Amol,if report not filed?>
								<a target="blank" href="<?php echo $download_report_pdf;?>" class="w238mt9fs13">Download Inspection Report PDF</a>
							<?php } ?>
						</div>

						<div class="clearfix"></div>

						</div>
					</fieldset>


					<fieldset><legend>Previous Comments History</legend>
						<div class="remark-history">
						<table>

							<tr>
							<th>Date</th>
							<th>Comment By</th>
							<th>Comment To</th>
							<th>Comment</th>
							</tr>
							<?php foreach($ho_comment_details as $comment_detail){

								//view only rows with values.
								if(!empty($comment_detail['comment_date'])){?>

								<tr>
								<td><?php echo $comment_detail['comment_date']; ?></td>
								<td><?php echo $comment_detail['comment_by']; ?></td>
								<td><?php echo $comment_detail['comment_to']; ?></td>
								<td><?php echo $comment_detail['comment']; ?></td>
								</tr>

							<?php }
							}?>
						</table>
						</div>
					</fieldset>


					<div class="form-buttons">

						<?php
							if(!empty($check_valid_ro) && $_SESSION['current_level']=='level_3' && !empty($check_ama_approval)
								&& empty($check_if_granted)){

								echo $this->Form->submit('Proceed to Grant', array('name'=>'proceed_to_grant', 'id'=>'proceed_to_grant', 'label'=>false));
							}
						?>
					</div>


					<?php echo $this->Form->end(); ?>

				</div>
