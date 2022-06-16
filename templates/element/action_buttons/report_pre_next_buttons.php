<?php $controller = $this->request->getParam('controller'); ?>
<div class="form-buttons form-style-3">
	<?php   if(!empty($section_details['previous_btn'])) { ?>
		<a id="previous_btn" href="<?php echo $this->request->getAttribute('webroot');?><?php echo $controller; ?>/section/<?php echo $section_details['section_id']-1; ?>" >Previous Section</a>
	<?php } ?>

	<?php if(!empty($section_details['next_btn'])) { ?>
		<a id="next_btn" href="<?php echo $this->request->getAttribute('webroot');?><?php echo $controller; ?>/section/<?php echo $section_details['section_id']+1; ?>" >Next Section</a>
	<?php } ?>
</div>
