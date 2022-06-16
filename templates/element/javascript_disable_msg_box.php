<?php ?>
<?php echo $this->Html->css('javascript_disable_msg_box')?>
<div class="form-style-3" class="width14">
<fieldset><legend><span class="uiHeader">JavaScript Required</span></legend>
<span>
We're sorry, but this site doesn't work properly without JavaScript enabled. If you can't enable JavaScript please <a href="view/javascript_enable_setting">click here</a> to view settings.
</span><br><br>
<button id="buttonOK" class="fr">Reload Page</button>
</fieldset>
</div>
<?php echo $this->Html->script('element/javascript_disable_msg_box'); ?>
