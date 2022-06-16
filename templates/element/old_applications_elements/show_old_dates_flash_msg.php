<?php ?>
	<?php echo $this->Html->css('element/show_old_dates_flash_msg'); ?>
	<?php if(!empty($old_app_renewal_dates)) { ?>
		<div class="validity_msg blink">As per entered last Renewal date "<?php echo $last_ren_date; ?>" this Certificate is valid up to "<?php echo $valid_upto_date; ?>", Update the date if not proper.</div>
	<?php }else{ ?>
		<div class="validity_msg blink">As per entered Grant date "<?php echo chop($date_of_grant,'00:00:00'); ?>", this Certificate is valid up to "<?php echo $valid_upto_date; ?>", Update the date if not proper.</div>
	<?php } ?>
