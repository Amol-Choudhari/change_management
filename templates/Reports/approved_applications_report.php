<?php //changed on the 14-06-2022 by Akash  ?>
<?php if ($_SESSION['approved_application_type'] == 'all_reports') { ?>
	<?php echo $this->element('report_elements/all_approved'); ?>
<?php } else { ?>
	<?php echo $this->element('report_elements/approved_application'); ?>
<?php } ?>
