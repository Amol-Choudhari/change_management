<?php 
	$action = $this->request->getParam('action'); 
	if($action == 'aqcmsStatistics'){ $buttonName = 'Download Report As PDF'; }else{ $buttonName = 'Download Report As Excel'; }
?>


<!-- below if-else added by Ankur -->
<button id="download_report" type="submit" name="download_report" value="<?php echo $buttonName; ?>" class="btn text-light option-menu-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $buttonName; ?>">
<?php if($buttonName == 'Download Report As PDF') { ?>
		<i class="fas fa-file-pdf"></i>
	<?php }
	else { ?>
		<i class="fas fa-file-excel"></i>
	<?php } ?>
</button>		
	
<!-- <input style="background:#666; color:#f2d60b; float: right; margin-right: 16px; text-align: center;" id="download_report" type="submit" name="download_report" class="col-md-3" value="<?php echo $buttonName; ?>" > -->

<?php echo $this->Html->script('Reports/download_excel_element'); ?>