<?php ?>
<div id="declarationModal" class="modal">
  <!-- Modal content -->				  
  <div class="modal-content">	
	<ul>
		<li>All previous renewal dates are to be entered in "All Previous Renewal Details".</li> 
		<li>The renewal dates are to be entered in ascending order starting from the first renewal date and ending with the last renewal date.</li>
		<li>Use "Add More" in case of more than one renewal date.</li>
		<!-- Below li added on 30-03-2019 to show last renewal date on popup meaage-->
		<li class="text-danger">Your grant date is <span id="entered_grant_date"></span>, so your last renewal due date may be <span id="predicted_last_renewal_date"></span></li>
		<li class="text-danger">Don't enter future renewal due date here. Only enter dates till the renewal was granted, otherwise application may not process</li>
	</ul>
	<button id="okBtnrinfo" class="modal-button">OK</button>	
  </div>				 
</div>

<?php echo $this->Html->script('element/add_firm_renewal_dates_popup'); ?>