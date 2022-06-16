<?php ?>

<div class="card bg-gradient-primary">
  <div class="card-header border-0">
	<h3 class="card-title">
	  <i class="fas fa-map-marker-alt mr-1"></i>
	  All India with states hyperlinked
	</h3>
	<!-- card tools -->
	<div class="card-tools">
	  <button type="button"
			  class="btn btn-primary btn-sm daterange"
			  data-toggle="tooltip"
			  title="Date range">
		<i class="far fa-calendar-alt"></i>
	  </button>
	  <button type="button"
			  class="btn btn-primary btn-sm"
			  data-card-widget="collapse"
			  data-toggle="tooltip"
			  title="Collapse">
		<i class="fas fa-minus"></i>
	  </button>
	</div>
	<!-- /.card-tools -->
  </div>
  <div class="card-body">
	<div id="world-map" style="height: 430px; width: 100%;bottom:30px;"></div>
  </div>
  <!-- /.card-body-->
  <div class="card-footer bg-transparent">
	<div class="row">
	  <div class="col-4 text-center">
		<div id="sparkline-1"></div>
		<div class="text-white">Applications</div>
	  </div>
	  <!-- ./col -->
	  <div class="col-4 text-center">
		<div id="sparkline-2"></div>
		<div class="text-white">Granted</div>
	  </div>
	  <!-- ./col -->
	  <div class="col-4 text-center">
		<div id="sparkline-3"></div>
		<div class="text-white">Revenue</div>
	  </div>
	  <!-- ./col -->
	</div>
	<!-- /.row -->
  </div>
</div>