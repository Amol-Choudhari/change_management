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

<script>

	//for disabling firefox resend popup message on form resubmitting.
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}

	// After downloading the report as excel format, refersh the current page on the document.click event
	// Done By pravin 14/3/2018
	$(document).ready(function () {
		$('#download_report').click(function(e) { 
			 
			 $(document).on("click",function() {
							 
				window.location.reload();
			});			
		});
	});
</script>