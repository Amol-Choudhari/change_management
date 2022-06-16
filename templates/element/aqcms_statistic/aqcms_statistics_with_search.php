<?php  $firm_type_array = array('1'=>'CA','2'=>'Printing Press','3'=>'Approval of Laboratory');
	   $application_processed_type = array('New Application','Renewal Application','Backlog Application');
?>
<div>
	<div class="col-sm-6 col-md-12 circle mb-4">
		<div class="stats-card mr-4">
			<div class="fixed-stats-card-inner box-1">
				<div class="fixed-stats-card-front">
					<a href="<?php echo $this->request->getAttribute('webroot');?>reports/primary_user_details_report">
						<span class="title-number"><?php echo count($total_primary_user); ?></span><br>
						<span class="subtitle">Primary User</span>
					</a>
				</div>
			</div>
		</div>
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-2">
				<div class="stats-card-front"><a id="test-2" href="#" onclick="openForm2(); return false;">
					<span class="title-number">
						<?php $total = 0; foreach($total_firm_register as $each_firm){ $total = $total + $each_firm['count']; } echo $total; ?>
					</span><br><span class="subtitle">Firms Registered</span></a>
				</div>
				<div class="stats-card-back" id="myForm2">
					<ul class="ul">
						<?php foreach($total_firm_register as $each_firm){ ?>
							<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/newly_added_firm_list_report">
								<span class="text-primary"></span><?php echo $firm_type_array[$each_firm['certification_type']]; echo ' : '; echo $each_firm['count']; ?>
							</a></li><br>
						<?php } ?>
						<li class="badge bg-light"><span class="text-primary"></span> Deleted Firms : <?php echo count($total_delete_firms); ?></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-3">
				<div class="stats-card-front"><a id="test-3" href="#" onclick="openForm3(); return false;">
					<span class="title-number"><?php $totalProcessed = 0; foreach($application_processed as $each_application) {
											$totalProcessed = $totalProcessed + $each_application[0][0]+$each_application[0][1]+$each_application[0][2]; }
										echo $totalProcessed;?>
					</span><br><span class="subtitle">In-Process</span></a>
				</div>
				<div class="stats-card-back" id="myForm3">
					<ul class="ul ul-margin">
						<?php $i = 0;  foreach($application_processed as $each_application){ ?>
							<li class="mb-1"><span class="text-primary"></span>
								<span class="badge bg-dark"><?php echo $application_processed_type[$i]; ?></span>
								<ul>
									<li class="ul badge bg-light"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_new_applications_report"><span class="text-primary"></span> CA : <?php echo $each_application[0][0]; ?></a></li>
									<li class="badge bg-light"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_new_applications_report"><span class="text-primary"></span> Printing Press : <?php echo $each_application[0][1]; ?></a></li>
									<li class="ul badge bg-light"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_new_applications_report"><span class="text-primary"></span> Approval of Laboratory : <?php echo $each_application[0][2]; ?></a></li>
								</ul>
							</li>
						<?php $i++; } ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-4">
				<div class="stats-card-front"><a id="test-4" href="#" onclick="openForm4(); return false;">
					<span class="title-number"><?php $totalGrant = 0; foreach($application_processed as $each_application){
											$totalGrant = $totalGrant + $each_application[1][0]+$each_application[1][1]+$each_application[1][2]; } echo $totalGrant; ?>
					</span><br><span class="subtitle">Granted</span></a>
				</div>
				<div class="stats-card-back" id="myForm4">
					<ul class="ul ul-margin">
						<?php $i = 0;  foreach($application_processed as $each_application){ ?>
							<li class="mb-1"><span class="text-primary"></span>
								<span class="badge bg-dark"><?php echo $application_processed_type[$i]; ?><?php if($i==0 || $i==1){ echo ' ( E-signed )'; } ?></span>
								<ul>
									<li class="ul badge bg-light"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/approved_new_application_type"><span class="text-primary"></span> CA : <?php echo $each_application[1][0]; ?></a></li>
									<li class="badge bg-light"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/approved_new_application_type"><span class="text-primary"></span> Printing Press : <?php echo $each_application[1][1]; ?></a></li>
									<li class="ul badge bg-light"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/approved_new_application_type"><span class="text-primary"></span> Approval of Laboratory : <?php echo $each_application[1][2]; ?></a></li>
								</ul>
							</li>
						<?php $i++; } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-12 circle">
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-5">
				<div class="stats-card-front"><a id="test-8" href="#" onclick="openForm8(); return false;">
					<span class="title-number"><?php echo $caRenewalDue+$printingRenewalDue+$labRenewalDue; ?></span>
					<br><span class="subtitle">Renewal Due</span></a>
				</div>
				<div class="stats-card-back" id="myForm8">
					<ul class="ul">
						<li class="badge bg-light mb-3"><span class="text-primary"></span> CA : <?php echo $caRenewalDue; ?></li><br>
						<li class="badge bg-light mb-3"><span class="text-primary"></span> Printing Permission : <?php echo $printingRenewalDue; ?></li><br>
						<li class="badge bg-light"><span class="text-primary"></span> Laboratory approval : <?php echo $labRenewalDue; ?></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-6">
				<div class="stats-card-front"><a id="test-5" href="#" onclick="openForm5(); return false;">
					<span class="title-number"><?php echo $pendingCountForMo+$pendingCountForIo+$pendingCountForHo+$pendingCountForRo; ?></span>
					<br><span class="subtitle">Pending With</span></a>
				</div>
				<div class="stats-card-back" id="myForm5">
					<ul class="ul">
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_new_applications_report"><span class="text-primary"></span> With Scrutinizer : <?php echo $pendingCountForMo; ?></a></li><br>
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_new_applications_report"><span class="text-primary"></span> With IO : <?php echo $pendingCountForIo; ?></a></li><br>
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_new_applications_report"><span class="text-primary"></span> With RO : <?php echo $pendingCountForRo; ?></a></li><br>
						<li class="badge bg-light mb-1"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/pending_new_applications_report"><span class="text-primary"></span> With HO : <?php echo $pendingCountForHo; ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-7">
				<div class="stats-card-front"><a id="test-6" href="#" onclick="openForm6(); return false;">
					<span class="title-number"><?php echo $applicationEsigned+$inspectionReportEsigned+$certificateEsigned+
																				$renewalApplicationEsigned+$renewalInspectionReportEsigned+$renewalCertificateEsigned; ?></span>
					<br><span class="subtitle">E-signed</span></a>
				</div>
				<div class="stats-card-back zoom" id="myForm6">
					<ul class="ul">
						<li class="mb-1"><span class="text-primary"></span>
							<span class="badge bg-dark mb-1">New Application</span>
							<ul class="ul">
								<li class="badge bg-light mb-1"><span class="text-primary"></span> Application : <?php echo $applicationEsigned; ?></li>
								<li class="badge bg-light mb-1"><span class="text-primary"></span> Inspection Report : <?php echo $inspectionReportEsigned; ?></li>
								<li class="badge bg-light mb-1"><span class="text-primary"></span> Grant Certificate : <?php echo $certificateEsigned; ?></li>
							</ul>
						</li>
						<li><span class="text-primary"></span>
							<span class="badge bg-dark mb-1">Renewal Application</span>
							<ul class="ul">
								<li class="badge bg-light mb-1"><span class="text-primary"></span> Application : <?php echo $renewalApplicationEsigned; ?></li>
								<li class="badge bg-light mb-1"><span class="text-primary"></span> Inspection Report : <?php echo $renewalInspectionReportEsigned; ?></li>
								<li class="badge bg-light mb-1"><span class="text-primary"></span> Grant Certificate : <?php echo $renewalCertificateEsigned; ?></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="stats-card mr-4">
			<div class="stats-card-inner box-8">
				<div class="stats-card-front"><a id="test-7" href="#" onclick="openForm7(); return false;">
					<span class="title-number"><?php echo $totalrevenue; ?></span>
					<br><span class="subtitle">Total Revenue</span></a>
				</div>
				<div class="stats-card-back" id="myForm7">
					<ul class="ul">
						<li class="badge bg-light mb-3"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/payment_details_report"><span class="text-primary"></span> New Application Revenue : <?php echo $newApplicationrevenue; ?></a></li>
						<li class="badge bg-light mb-1"><a href="<?php echo $this->request->getAttribute('webroot');?>reports/payment_details_report"><span class="text-primary"></span> Renewal Application Revenue : <?php echo $renewalApplicationrevenue; ?></a></li>
					</ul>
				</div>
			</div>
		</div>
		<!--<div class="dot">
			<span style="font-size: 30px;"><?php //echo $totalVisitor; ?></span>
			<br><span style="font-size: 10px;">Total Visitors</span>
		</div>-->
	</div>
</div>
