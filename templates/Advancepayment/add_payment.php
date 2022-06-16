<?php ?>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6"><label class="badge badge-info">Advance Payment</label></div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><?php echo $this->Html->link('Dashboard', array('controller' => 'customers', 'action'=>'secondary_home'));?></a></li>
						<li class="breadcrumb-item"><?php echo $this->Html->link('Transactions', array('controller' => 'advancepayment', 'action'=>'transactions'));?></a></li>
						<li class="breadcrumb-item active">Add Payment</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content form-middle ">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-10">
					<?php echo $this->Form->create(NULL,array('type'=>'file', 'enctype'=>'multipart/form-data', 'id'=>'payment_modes')); ?>
						<?php echo $this->element('payment_details_elements/payment_information_details'); ?>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</section>
</div>

<?php if($status == 'saved'){ ?>

	<?php echo $this->Html->script('advance_payment/add_payment/final_submit_button_display'); ?>

<?php } ?>

<?php if($status == 'pending' || $status == 'replied' || $status == 'confirmed'){ ?>

	<?php echo $this->Html->script('advance_payment/add_payment/payment_status_validations'); ?>

<?php } ?>

<?php if ($status == 'not_confirmed' || $status == 'replied') { ?>

	<?php echo $this->Html->script('advance_payment/add_payment/not_confirmed_payment_validation'); ?>

<?php } ?>
