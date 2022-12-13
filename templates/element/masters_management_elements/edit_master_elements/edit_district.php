<?php ?>

	<div class="col-md-12">
		<div class="row">
			<div class="col-md-6">
				<label>State 	<span class="cRed">*</span></label>
					<?php echo $this->Form->control('state_list', array('type'=>'select', 'id'=>'state_list','value'=>$selected_state_id, 'options'=>$state_list, 'label'=>false,'class'=>'form-control')); ?>
				<div id="error_state_list"></div>
			</div>

			<div class="col-md-6">
				<label>District Name <span class="cRed">*</span></label>
					<?php echo $this->Form->control('district_name', array('type'=>'text', 'id'=>'district_name', 'label'=>false, 'value'=>$entered_district,'class'=>'form-control')); ?>
				<div id="error_district_name"></div>
			</div>

			<div class="col-md-6 mt-3">
				<label class="alert alert-info">District Office :</label>
					<?php
						$options=array('RO'=>'RO','SO'=>'SO');
						if(!empty($selected_so_office_id)){$dist_office_type='SO';}elseif(empty($selected_so_office_id)){$dist_office_type='RO';}
						$attributes=array('legend'=>false, 'value'=>$dist_office_type, 'id'=>'dist_office_type');
						echo $this->Form->radio('dist_office_type',$options,$attributes);
					?>
			</div>

		<!-- Added below radio button block on 10-08-2018 FOR optional RO/SO office (one mandatory)-->

		<div id="ro_list_div" class="col-md-6 mt-3">
			<label>RO Office</label>
				<?php echo $this->Form->control('ro_offices_list', array('type'=>'select', 'id'=>'ro_offices_list','value'=>$selected_ro_office_id, 'options'=>$ro_offices_list,'label'=>false,'class'=>'form-control')); ?>
			<div id="error_ro_offices_list"></div>
		</div>

		<!-- added on 06-03-2018 by Amol added id on 10-08-2018-->
		<div id="so_list_div" class="col-md-6 mt-3">
			<label>SO Office</label>
				<?php  echo $this->Form->control('so_offices_list', array('type'=>'select', 'id'=>'so_offices_list','value'=>$selected_so_office_id, 'options'=>$so_offices_list,'label'=>false,'class'=>'form-control')); ?>
			<div id="error_so_offices_list"></div>
		</div>

		<div class="col-md-2 mt-2">
			<?php echo $this->element('masters_management_elements/edit_submit_common_btn'); ?>
		</div>


		<?php echo $this->Html->script('element/masters_management_elements/edit_district'); ?>
