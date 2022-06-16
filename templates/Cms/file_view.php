<?php ?>

<div class="container">
<!-- below condition and anchor tag added on 25-08-2018 to open pdf file in another window in browser -->
<?php if (substr(strrchr($view_file,'.'),1)=='pdf' || 
		substr(strrchr($view_file,'.'),1)=='PDF') { ?>
	
	<a target="blank" href="<?php echo $view_file; ?>">Please click here to open pdf file</a>
	
<?php } else {?>

	<img class="wd100" src="<?php echo $view_file; ?>" />
<?php } ?>	
</div>




