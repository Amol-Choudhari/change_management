
	<?php if ($customer_last_login == 'First login') {

			echo "No last log";
		} else{ ?>

		<?php	echo $customer_last_login['date'];?> <?php echo $customer_last_login['time_in']; ?>

	<?php } ?>
