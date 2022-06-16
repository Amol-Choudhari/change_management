<?php ?>
<div>
	<h5 class="cNavy">Applicant Id: <?php echo $customer_id; ?> - <?php echo $firm_details['firm_name']; ?>

	<a href="<?php echo $download_siteinspection_report_pdf;?>" target="blank" class="download_application_pdf"><b>Download PDF Version</b></a>
	<div class="clearfix"></div>
	</h5>
</div>

<div class="form-buttons">

	<?php if($_SESSION['current_level'] == 'level_3' || $_SESSION['current_level'] == 'level_2'){ ?>
		
		<a href="<?php echo $this->request->getAttribute('webroot');?>inspections/home" >Back to Status Window</a>

	<?php }elseif($_SESSION['current_level'] == 'level_4'){  ?>

		<a href="<?php echo $this->request->getAttribute('webroot');?>hoinspections/ho_inspection" >Back to Comments Window</a>

	<?php }?>

</div>
