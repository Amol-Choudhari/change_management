<?php echo $this->Html->css('dashboard/country-map-css'); ?>

<div class="card bg-gradient-primary">
  <div class="card-header border-0">
	<h3 class="card-title">
	  <i class="fas fa-map-marker-alt mr-1"></i>
	  All India with states hyperlinked
	</h3>

  </div>
  <div class="clear"></div>
  <div class="card-body">
	<div id="world-map"></div>
  </div>
  <!-- /.card-body-->
  <div class="card-footer bg-transparent">
  
	<h5 id="state_name"></h5>
	<div class="row">
	  <div class="col-4 text-center">
		<div class="text-white">Applications<br><span id="st_wise_appl"></span></div>
	  </div>
	  <!-- ./col -->
	  <div class="col-4 text-center">
		<div class="text-white">Granted<br><span id="st_wise_grant"></span></div>
	  </div>
	  <!-- ./col -->
	  <div class="col-4 text-center">
		<div class="text-white">Revenue<br><span id="st_wise_rev"></span></div>
	  </div>
	  <!-- ./col -->
	</div>
	<!-- /.row -->
  </div>
</div>

<?php echo $this->Html->script('dashboard/country-map-js'); ?>