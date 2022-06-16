

	<div class="row">
		<div class="col-md-6">
			<h5 class="mt-1 mb-2">Renewal Intimation</h5>
			<?php echo $this->Form->control('ren_intimation', array('type'=>'checkbox', 'id'=>'ren_intimation', 'checked'=>$intCheckBox, 'label'=>' I agree and ready to apply for renewal', 'required'=>true,)); ?>
		</div>
	
		<?php if(strtotime($validity_date) < strtotime($current_date)){ ?>
			<p></p>
			<div class="col-md-6">
				<h5 class="mt-1 mb-2">Remark/Reason</h5>		
				<?php	echo $this->Form->control('late_remark',array('type'=>'textarea', 'id'=>'late_remark', 'label'=>false, 'value'=>$intRemark, 'placeholder'=>'Enter remark for late submission', 'required'=>true, 'class'=>'form-control')); ?>
			</div>
		<?php } ?>
	</div>
	<p></p>


<?php echo $this->Html->script('element/payment/renewal_intimation'); ?>
