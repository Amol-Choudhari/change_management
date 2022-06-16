var toastTheme = $("#toastTheme").val();

if(toastTheme !=''){
	$('#toast-msg-'+toastTheme).html(toastTheme);
	$('#toast-msg-box-'+toastTheme).fadeIn('slow');
	$('#toast-msg-box-'+toastTheme).delay(3000).fadeOut('slow');
}