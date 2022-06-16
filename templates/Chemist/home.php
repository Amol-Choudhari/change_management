
	<?php if (in_array($final_submit_status,array('pending','replied','referred_back'))) { ?>

		 	<div class="col-lg-8">
				<div class="alert alert-info alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h5><i class="icon fas fa-info"></i> Please Note !</h5>
					<p>Your application for registration has been saved and finally submitted, to check status please click on "Registration Status" button. Thankyou</p>
				</div>
			</div>

	<?php } elseif ($final_submit_status == 'approved') { ?>

			<div class="col-lg-8">
				<div class="alert alert-info alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h5><i class="icon fas fa-info"></i> Please Note !</h5>
					<p>Your application for registration has been successfully verified. Thankyou</p>
				</div>
			</div>

	<?php } elseif ($final_submit_status == '') { ?>

		<div class="col-lg-8">
			<div class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h5><i class="icon fas fa-info"></i> Please Note !</h5>
				<p class="">You need to register your application as a chemist on online system, so please click "Register Application" button to fill your details and apply. Thankyou</p>
			</div>
		</div>

	<?php } ?>
