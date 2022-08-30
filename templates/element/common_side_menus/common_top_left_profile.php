<?php ?>

<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			
	<?php 
		if(!empty($_SESSION['profile_pic'])){
		//	$rootdir = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
			$profile_pic = $_SESSION['profile_pic']; //added on 06-05-2021 for profile pic
			echo $this->Html->image('../../'.$profile_pic, array("alt"=>"User Image", "width"=>"200", "class"=>"img-circle")); 
			
		}else{ ?>
			<div class="image">
			  <?php echo $this->Html->image('profile.jpg', array("alt"=>"User Image", "height"=>"255", "class"=>"img-circle elevation-2")); ?>
			</div>
		<?php } ?>
	
	<div class="info">
	<a class="d-block"><?php echo $_SESSION["f_name"];?> <?php echo $_SESSION["l_name"];?></a>
	
	<?php if (filter_var(base64_decode($_SESSION["username"]), FILTER_VALIDATE_EMAIL)) { //for email encoding ?>
		<span class="right badge badge-light"><?php echo base64_decode($_SESSION["username"]);?></span>
	<?php }else{ ?>
		<span class="right badge badge-light"><?php echo $_SESSION["username"];?></span>
	<?php } ?>
	</div>
</div>