$(document).ready(function () {

    //for disabling firefox resend popup message on form resubmitting.
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}

	// After downloading the report as excel format, refersh the current page on the document.click event
	// Done By pravin 14/3/2018
	/*$(document).ready(function () {
		$('#download_report').click(function(e) { 
			 
			 $(document).on("click",function() {
							 
				window.location.reload();
			});			
		});
	});*/
});
