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
		<?php $checkUsername = explode('/',$_SESSION['username']);
		if ($checkUsername[0] == 'CHM') { ?>
			<a href="#" class="d-block"><?php echo $_SESSION["f_name"]." ".$_SESSION["l_name"];?></a>
	<?php } else { ?>
			<a href="#" class="d-block"><?php echo $_SESSION["firm_name"];?></a>
	<?php } ?>

	<span class="right badge badge-light"><?php echo $_SESSION["username"];?></span>
	</div>
</div>
