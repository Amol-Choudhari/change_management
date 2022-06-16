<?php ?>
<?php echo $this->Html->script('element/top_tabs_script_code/top_tabs_script_code'); ?>

	<!-- For Inspection window -->
	<?php if ($this->request->getParam('controller') == 'inspections') {

		if ($this->request->getParam('action') == 'pending_applications' || $this->request->getParam('action') == 'renewal_pending_applications' ||
			$this->request->getParam('action') == 'referred_back_applications' || $this->request->getParam('action') == 'renewal_referred_back_applications' ||
			$this->request->getParam('action') == 'replied_applications' || $this->request->getParam('action') == 'renewal_replied_applications' ||
			$this->request->getParam('action') == 'verified_applications' || $this->request->getParam('action') == 'renewal_verified_applications') { ?>

				<?php echo $this->Html->script('element/top_tabs_script_code/if_inspection'); ?>


			<?php }

		}  if ($this->request->getParam('controller') == 'siteinspections') { ?>

			<?php echo $this->Html->script('element/top_tabs_script_code/if_site_inspection'); ?>

		<?php } if ($this->request->getParam('controller') == 'roinspections') { ?>

			<?php echo $this->Html->script('element/top_tabs_script_code/roinspections'); ?>

		<?php } if ($this->request->getParam('controller') == 'hoinspections') {

					if ($this->request->getParam('action') == 'dyama_pending' || $this->request->getParam('action') == 'dyama_commented'||
						$this->request->getParam('action') == 'dyama_replied') { ?>

					<?php echo $this->Html->script('element/top_tabs_script_code/hoinspections'); ?>

		<?php } if ($this->request->getParam('action') == 'ho_mo_pending' ||
						$this->request->getParam('action') == 'ho_mo_commented'||
						$this->request->getParam('action') == 'ho_mo_replied') { ?>

					<?php echo $this->Html->script('element/top_tabs_script_code/ho_mo'); ?>


			<?php } if ($this->request->getParam('action') == 'ho_jtama_pending' ||
						$this->request->getParam('action') == 'ho_jtama_commented'||
						$this->request->getParam('action') == 'ho_jtama_replied') { ?>

						<?php echo $this->Html->script('element/top_tabs_script_code/ho_jtama'); ?>


			<?php } if ($this->request->getParam('action') == 'ho_ama_pending' ||
						$this->request->getParam('action') == 'ho_ama_commented'||
						$this->request->getParam('action') == 'ho_ama_replied') { ?>

						<?php echo $this->Html->script('element/top_tabs_script_code/ho_ama'); ?>

			<?php } ?>


	<?php } ?>








	<!-- For allocation window-->

	<?php if ($this->request->getParam('controller') == 'allocations') { ?>

		<?php echo $this->Html->script('element/top_tabs_script_code/allocations'); ?>

	<?php } ?>


	<?php if ($this->request->getParam('action') == 'pending_forms' ||
				$this->request->getParam('action') == 'allocated_forms' ||
				$this->request->getParam('action') == 'approved_forms' ||
				$this->request->getParam('action') == 'renewal_pending_forms' ||
				$this->request->getParam('action') == 'renewal_allocated_forms' ||
				$this->request->getParam('action') == 'renewal_approved_forms'
				//$this->request->getParam['action'] == 'home'
				){?>

			<?php echo $this->Html->script('element/top_tabs_script_code/pending_forms'); ?>


	<?php } elseif ($this->request->getParam('action') == 'pending_sites' ||
					$this->request->getParam('action') == 'allocated_sites' ||
					$this->request->getParam('action') == 'approved_sites' ||
					$this->request->getParam('action') == 'renewal_pending_sites' ||
					$this->request->getParam('action') == 'renewal_allocated_sites' ||
					$this->request->getParam('action') == 'renewal_approved_sites'){ ?>

				<?php echo $this->Html->script('element/top_tabs_script_code/pending_sites'); ?>


	<?php } elseif ($this->request->getPara(['action') == 'ho_pending' || $this->request->getParam('action') == 'ho_allocated') { ?>

		<?php echo $this->Html->script('element/top_tabs_script_code/ho_pending_or_ho_allocated'); ?>

	<?php } elseif ($this->request->getParam('action'( == 'ho_mo_pending' || $this->request->getParam('action'( == 'ho_mo_allocated') { ?>

		<?php echo $this->Html->script('element/top_tabs_script_code/ho_mo_pending_or_ho_mo_allocated'); ?>

	<?php } elseif ($this->request->getParam('action') == 'ho_jtama_pending' || $this->request->getParam('action') == 'ho_jtama_allocated') { ?>

		<?php echo $this->Html->script('element/top_tabs_script_code/ho_jtama_pending_or_ho_jtama_allocated'); ?>

	<?php } elseif ($this->request->getParam('action') == 'ho_ama_pending' || $this->request->getParam('action') == 'ho_ama_allocated') { ?>

		<?php echo $this->Html->script('element/top_tabs_script_code/ho_ama_pending_or_ho_ama_allocated'); ?>

	<?php }?>
