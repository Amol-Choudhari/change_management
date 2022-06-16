<?php  $firm_type_array = array('1'=>'CA','2'=>'Printing Press','3'=>'Approval of Laboratory'); 
	   $application_processed_type = array('New Application','Renewal Application','Backlog Application');	
?>
<style>
@import url('//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css');
.accordion-toggle:before {
    /* symbol for "opening" panels */
    font-family:'FontAwesome';
    content:"\f146";
    float: right;
    color: inherit;
}

.collapsed .accordion-toggle:before {
    /* symbol for "collapsed" panels */
    content:"\f0fe";
}
</style>
<?php echo $this->Form->create('Dmi_user_role'); ?>

<!------ Include the above in your HEAD tag ---------->
<div class="panel panel-primary report-filterable-format">
	<div class="panel-heading">			
		<h3 class="panel-title col-md-10">Given Below is AQCMS Statistics</h3>
			<div class="clearfix"></div>
	</div>
</div>	
<div class="container">
      <div class="row">
        <div class="col-sm-6 col-md-6">
          <div class="panel-group" id="accordion">
			<div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                 <span class="glyphicon glyphicon-stop"></span>
                    <label class="cifw600">
						Primary/Corporate User : <?php echo count($total_primary_user); ?>
					</label>				 
                </h4>
              </div>              
            </div>
			<div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
				  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <span class="glyphicon accordion-toggle"></span>						
						Total Firms Registered : 
						<?php $total = 0; 
							  foreach($total_firm_register as $each_firm){ 
								$total = $total + $each_firm[0]['count'];  
							  } 
							  echo $total;
						?>
                  </a>				
                </h4>
              </div> 
			  <div id="collapseOne" class="panel-collapse collapse">
                <ul class="list-group">
					<?php foreach($total_firm_register as $each_firm){ ?>
						<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span>
							<?php 
								echo $firm_type_array[$each_firm['Dmi_firm']['certification_type']]; 
								echo ' : ';
								echo $each_firm[0]['count'];
							?>
						</li>
					<?php } ?>	
                  <li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> Deleted Firms : <?php echo count($total_delete_firms); ?></li>
                </ul>
              </div>	
            </div>
			<div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
				  <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                    <span class="glyphicon accordion-toggle"></span>						
						Application Processed : 
						<?php $totalProcessed = 0; 
							foreach($application_processed as $each_application){ 
								$totalProcessed = $totalProcessed + $each_application[0][0]+$each_application[0][1]+$each_application[0][2];  
							} 
							echo $totalProcessed;												
						?>
                  </a>				
                </h4>
              </div> 
			  <div id="collapseTwo" class="panel-collapse collapse">
                <ul class="list-group">
					<?php $i = 0;  foreach($application_processed as $each_application){ ?>
						<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span>
							<?php echo $application_processed_type[$i]; ?>
							<ul class="list-group">								
							  <li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> CA : <?php echo $each_application[0][0]; ?></li>
							   <li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> Printing Press : <?php echo $each_application[0][1]; ?></li>
							    <li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> Approval of Laboratory : <?php echo $each_application[0][2]; ?></li>
							</ul>
						</li>
					<?php $i++; } ?>	                  
                </ul>
              </div>	
            </div>
			<div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
				  <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                    <span class="glyphicon accordion-toggle"></span>						
						Application Granted :
						<?php $totalGrant = 0; 
							foreach($application_processed as $each_application){ 
								$totalGrant = $totalGrant + $each_application[1][0]+$each_application[1][1]+$each_application[1][2];  
							} 
							echo $totalGrant;
					  ?>
                  </a>				
                </h4>
              </div> 
			  <div id="collapseThree" class="panel-collapse collapse">
                <ul class="list-group">
					<?php $i = 0;  foreach($application_processed as $each_application){ ?>
						<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span>
							<?php echo $application_processed_type[$i]; ?>
							<ul class="list-group">								
							  <li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> CA : <?php echo $each_application[1][0]; ?></li>
							   <li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> Printing Press : <?php echo $each_application[1][1]; ?></li>
							    <li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> Approval of Laboratory : <?php echo $each_application[1][2]; ?></li>
							</ul>
						</li>
					<?php $i++; } ?>	                  
                </ul>
              </div>	
            </div>
			<div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
				  <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                    <span class="glyphicon accordion-toggle"></span>						
						Application Pending:
						<?php echo $pendingCountForMo+$pendingCountForIo+$pendingCountForHo+$pendingCountForRo; ?>
                  </a>				
                </h4>
              </div> 
			  <div id="collapseFour" class="panel-collapse collapse">
                <ul class="list-group">					
					<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> With MO : <?php echo $pendingCountForMo; ?></li>
					<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> With IO : <?php echo $pendingCountForIo; ?></li>
					<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> With RO : <?php echo $pendingCountForRo; ?></li>	
					<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> With HO : <?php echo $pendingCountForHo; ?></li>
				</ul>
              </div>	
            </div>
			<div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
				  <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                    <span class="glyphicon accordion-toggle"></span>						
						Documents E-signed:
						<?php echo $applicationEsigned+$inspectionReportEsigned+$certificateEsigned; ?>
                  </a>				
                </h4>
              </div> 
			  <div id="collapseFive" class="panel-collapse collapse">
                <ul class="list-group">					
					<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> Application : <?php echo $applicationEsigned; ?></li>
					<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> Inspection Report : <?php echo $inspectionReportEsigned; ?></li>
					<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> Grant Certificate : <?php echo $certificateEsigned; ?></li>					
				</ul>
              </div>	
            </div> 
			<div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
				  <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
                    <span class="glyphicon accordion-toggle"></span>						
						Total Revenue:
						<?php echo $newApplicationrevenue+$renewalApplicationrevenue; ?>
                  </a>				
                </h4>
              </div> 
			  <div id="collapseSix" class="panel-collapse collapse">
                <ul class="list-group">					
					<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> New Application Revenue : <?php echo $newApplicationrevenue; ?></li>
					<li class="list-group-item"><span class="glyphicon glyphicon-stop text-primary"></span> Renewal Application Revenue : <?php echo $renewalApplicationrevenue; ?></li>								
				</ul>
              </div>	
            </div>
			<div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                 <span class="glyphicon glyphicon-stop"></span>
                    <label style="color: inherit; font-weight: 600;">
						Total Visitors : <?php echo $totalVisitor; ?>
					</label>				 
                </h4>
              </div>              
            </div>	
          </div>
        </div>        
      </div>
    </div>
	<?php echo $this->element('download_report_excel_format/report_download_button'); ?>
	
<?php echo $this->Form->end(); ?>	