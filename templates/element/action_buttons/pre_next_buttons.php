<?php $controller = $this->request->getParam('controller'); ?>
<div class="form-buttons form-style-3">

	<?php if (!empty($previousbtnid)) { ?>
		<a class="btn btn-primary" id="previous_btn" href="<?php echo $this->request->getAttribute('webroot');?><?php echo $controller; ?>/section/<?php echo $previousbtnid; ?>" >Previous Section</a>
	<?php } ?>
	<?php if (!empty($nextbtnid)) { ?>
		<a class="btn btn-secondary" id="next_btn" href="<?php echo $this->request->getAttribute('webroot');?><?php echo $controller; ?>/section/<?php echo $nextbtnid; ?>" >Next Section</a>
	<?php } elseif ($_SESSION['paymentSection'] == 'available') { ?>
		<a id="next_btn" href="<?php echo $this->request->getAttribute('webroot');?><?php echo $controller; ?>/payment">Next Section</a>
	<?php } ?>


</div>
