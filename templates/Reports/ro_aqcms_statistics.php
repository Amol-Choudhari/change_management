<?php echo $this->Html->css('Reports/ro_aqcms_statistics') ?>

<?php  $firm_type_array = array('1'=>'CA','2'=>'Printing Press','3'=>'Approval of Laboratory');
	   $application_processed_type = array('New Application','Renewal Application','Backlog Application');
?>

<?php echo $this->Form->create(); ?>
<div class="panel panel-primary report-filterable">
	<div class="panel-heading lh31">
		<div id="search_by_options" class="col-md-12">
			<div class="clearfix"></div>
			<div class="report-filter" class="col-md-12">
				<div class="col-md-2 mt15"><strong>Search By:</strong></div>
				<div class="col-md-10">
					<div class="col-md-3 dnone">
						<label>RO Incharge</label>
						<?php echo $this->form->input('ro_id', array('type'=>'text', 'value'=>$ro_id, 'label'=>false, 'id'=>'office', 'class'=>'dnone;')); ?>
					</div>
					<div class="col-md-3">
						<label>From Date</label>
						<?php echo $this->form->input('from_date', array('type'=>'text', 'value'=>'','label'=>false, 'id'=>'fromdate', 'empty'=>'select', 'escape'=>false, 'class'=>'search_field',)); ?>
					</div>

					<div class="col-md-3">
						<label id="to_date_label">To Date</label>
						<?php echo $this->form->input('to_date', array('type'=>'text', 'value'=>'','label'=>false, 'id'=>'todate', 'empty'=>'select', 'escape'=>false, 'class'=>'search_field')); ?>
					</div>
					<div class="col-md-2">
							<input id="search_btn" type="submit" name="search" class="form-control bcfff" value="Search">
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="clearfix"></div>
<div>
	<?php echo "Result for : ";
		  if(empty($ro_id)&&empty($from_date)&&empty($to_date)){ echo "All"; }
		  if(!empty($ro_id)){ echo "RO Incharge --> $ro_name_list[$ro_id]"; }
		  if(!empty($from_date)){ echo " From Date : $from_date"; }
		  if(!empty($to_date)){ echo " To Date : $to_date"; }
		  echo $this->element('download_report_excel_format/report_download_button');
	?>
</div>
<div>
	<div class="col-sm-6 col-md-12 circle">
		<div class="dot dotbg">
			<span class="fs30"><?php echo count($total_primary_user); ?></span>
			<br><span class="fs12_fwB">Primary User</span>
		</div>
		<div class="dot bgdot">
			<span><a id="test-2" href="#">
				<span class="fs30">
					<?php $total = 0;
			  			  foreach ($total_firm_register as $each_firm) {
							  		$total = $total + $each_firm[0]['count'];
			  			  }

			  			  echo $total;
					  ?>
				</span><br>
				<span class="fs12_fwB">Firms Registered</span></a></span>
					<div class="form-popup" id="myForm2">
						<ul class="ul">
							<?php foreach($total_firm_register as $each_firm){ ?>
								<li><span class="glyphicon glyphicon-stop text-primary"></span>
									<?php
										echo $firm_type_array[$each_firm['certification_type']];
										echo ' : ';
										echo $each_firm[0]['count'];
									?>
								</li>
							<?php } ?>
						  <li><span class="glyphicon glyphicon-stop text-primary"></span> Deleted Firms : <?php echo count($total_delete_firms); ?></li>
						</ul>
					</div>
				</div>
				<div class="dot dot2"><span><a id="test-3" href="#">
					<span class="fs30"><?php $totalProcessed = 0;
						foreach ($application_processed as $each_application) {
							$totalProcessed = $totalProcessed + $each_application[0][0]+$each_application[0][1]+$each_application[0][2];
						}
						echo $totalProcessed;
					?></span><br><span class="fs12_fwB">In-Process</span></a></span>

					<div class="form-popup" id="myForm3">
						<ul class="ul">
							<?php $i = 0;  foreach($application_processed as $each_application){ ?>
								<li><span class="glyphicon glyphicon-stop text-primary"></span>
									<?php echo $application_processed_type[$i]; ?>
									<ul>
									  <li><span class="glyphicon glyphicon-stop text-primary"></span> CA : <?php echo $each_application[0][0]; ?></li>
									   <li><span class="glyphicon glyphicon-stop text-primary"></span> Printing Press : <?php echo $each_application[0][1]; ?></li>
										<li><span class="glyphicon glyphicon-stop text-primary"></span> Approval of Laboratory : <?php echo $each_application[0][2]; ?></li>
									</ul>
								</li>
							<?php $i++; } ?>
						</ul>
					</div>
				</div>

				<div class="dot dot3"><span><a id="test-4" href="#">
					<span class="fs30"><?php $totalGrant = 0;
						foreach ($application_processed as $each_application) {
							$totalGrant = $totalGrant + $each_application[1][0]+$each_application[1][1]+$each_application[1][2];
						}
						echo $totalGrant;
				  ?></span><br><span class="fs12_fwB">Granted</span></a></span>

					<div class="form-popup" id="myForm4">
						<ul class="ul">
							<?php $i = 0;  foreach($application_processed as $each_application){ ?>
								<li><span class="glyphicon glyphicon-stop text-primary"></span>
									<?php echo $application_processed_type[$i]; ?><?php if($i==0 || $i==1){ echo ' (E-signed)';}?>
									<ul>
									  <li><span class="glyphicon glyphicon-stop text-primary"></span> CA : <?php echo $each_application[1][0]; ?></li>
									   <li><span class="glyphicon glyphicon-stop text-primary"></span> Printing Press : <?php echo $each_application[1][1]; ?></li>
										<li><span class="glyphicon glyphicon-stop text-primary"></span> Approval of Laboratory : <?php echo $each_application[1][2]; ?></li>
									</ul>
								</li>
							<?php $i++; } ?>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-sm-6 col-md-12 circle">
				<div class="dot dot4">
					<span><a id="test-8" href="#">
					<span class="fs30"><?php echo $caRenewalDue+$printingRenewalDue+$labRenewalDue; ?></span>
					<br><span class="fs12_fwB">Renewal Due</span></a></span>
					<div class="form-popup" id="myForm8">
						<ul class="ul">
							<li><span class="glyphicon glyphicon-stop text-primary"></span> CA : <?php echo $caRenewalDue; ?></li>
							<li><span class="glyphicon glyphicon-stop text-primary"></span> Printing Permission : <?php echo $printingRenewalDue; ?></li>
							<li><span class="glyphicon glyphicon-stop text-primary"></span> Laboratory approval : <?php echo $labRenewalDue; ?></li>
						</ul>
					</div>
				</div>

				<div class="dot dot5"><span><a id="test-5" href="#">
					<span class="fs30"><?php echo $pendingCountForMo+$pendingCountForIo+$pendingCountForHo+$pendingCountForRo; ?></span>
					<br><span class="fs12_fwB">Pending With</span></a></span>
					<div class="form-popup" id="myForm5">
						<ul class="ul">
							<li><span class="glyphicon glyphicon-stop text-primary"></span> With MO : <?php echo $pendingCountForMo; ?></li>
							<li><span class="glyphicon glyphicon-stop text-primary"></span> With IO : <?php echo $pendingCountForIo; ?></li>
							<li><span class="glyphicon glyphicon-stop text-primary"></span> With RO : <?php echo $pendingCountForRo; ?></li>
							<li><span class="glyphicon glyphicon-stop text-primary"></span> With HO : <?php echo $pendingCountForHo; ?></li>
						</ul>
					</div>
				</div>

				<div class="dot dot6"><span><a id="test-6" href="#">
					<span class="fs30"><?php echo $applicationEsigned+$inspectionReportEsigned+$certificateEsigned+$renewalApplicationEsigned+$renewalInspectionReportEsigned+$renewalCertificateEsigned; ?></span>
					<br><span class="fs12_fwB">E-signed</span></a></span>
					<div class="form-popup" id="myForm6">
						<ul class="ul">
							<li><span class="glyphicon glyphicon-stop text-primary"></span>
								New Application
								<ul class="ul">
									<li><span class="glyphicon glyphicon-stop text-primary"></span> Application : <?php echo $applicationEsigned; ?></li>
									<li><span class="glyphicon glyphicon-stop text-primary"></span> Inspection Report : <?php echo $inspectionReportEsigned; ?></li>
									<li><span class="glyphicon glyphicon-stop text-primary"></span> Grant Certificate : <?php echo $certificateEsigned; ?></li>
								</ul>
							</li>
							<li><span class="glyphicon glyphicon-stop text-primary"></span>
								Renewal Application
								<ul class="ul">
									<li><span class="glyphicon glyphicon-stop text-primary"></span> Application : <?php echo $renewalApplicationEsigned; ?></li>
									<li><span class="glyphicon glyphicon-stop text-primary"></span> Inspection Report : <?php echo $renewalInspectionReportEsigned; ?></li>
									<li><span class="glyphicon glyphicon-stop text-primary"></span> Grant Certificate : <?php echo $renewalCertificateEsigned; ?></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>

				<div class="dot dot7"><span><a id="test-7" href="#">
					<span class="fs30"><?php echo $newApplicationrevenue+$renewalApplicationrevenue; ?></span>
					<br><span class="fs12_fwB">Total Revenue</span></a></span>
					<div class="form-popup" id="myForm7">
						<ul class="ul">
							<li><span class="glyphicon glyphicon-stop text-primary"></span> New Application Revenue : <?php echo $newApplicationrevenue; ?></li>
							<li><span class="glyphicon glyphicon-stop text-primary"></span> Renewal Application Revenue : <?php echo $renewalApplicationrevenue; ?></li>								
						</ul>
					</div>
				</div>
				<!--<div class="dot">
					<span style="font-size: 30px;"><?php //echo $totalVisitor; ?></span>
					<br><span style="font-size: 10px;">Total Visitors</span>
				</div>-->
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
	<?php echo $this->Html->script('Reports/ro_aqcms_statistics'); ?>
