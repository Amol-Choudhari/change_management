<?php ?>
<div class="form-buttons">

	<?php
	         $current_level = $_SESSION['current_level'];

		   if (!empty($current_final_status)) {

			   if ($current_final_status !='approved') {

				   	if ($current_final_status !='pending' && $current_level != 'level_2') {
						echo $this->element('siteinspection_forms/communication/communication-window');
				   	}

					if ($current_final_status =='pending' && $current_level != 'level_2') {
						echo $this->element('siteinspection_forms/communication/communication-window');
				   	}

				   	if (($current_final_status =='referred_back' || $current_final_status =='replied') && $current_level == 'level_2') {
						echo $this->element('siteinspection_forms/communication/communication-window');
				   	}

				   	if ($current_final_status =='pending' && $current_level == 'level_2') {
					   echo $this->element('siteinspection_forms/communication/communication-window');
				   	}

			   } else {
				  echo $this->element('siteinspection_forms/communication/buttons');
			   }

		   } else {
			   echo $this->element('siteinspection_forms/communication/buttons');
		   }

	?>

</div>
