<?php  ?>
<div id="otp_popup_box" class="modal">
	<div class="modal-content">
		<span class="close"><b>&times;</b></span>
		<div id="error_aadhar_otp"></div>
		<?php echo $this->Form->control('aadhar_otp', array('type'=>'text', 'label'=>'Aadhar Authentication OTP', 'id'=>'aadhar_otp', 'escape'=>false, 'placeholder'=>'Enter OTP here')); ?>
		<!--<button id="cancelotp" class="modal-button">Cancel</button>-->
		<button id="submitotp" class="modal-button" name="submit">Submit</button>
		<a class="float-right" id="resend_otp" href="#">Resend OTP</a>
	</div>				 
</div>

<?php echo $this->Html->script('element/esign_views/aadhar_authentication_otp_popup'); ?>	
