<?php echo $this->Html->css('dashboard/chart-css'); ?>
<!-- taking chart data values in hidden fields -->
<?php 
// To show month wise allocated applications
	$i=1;
	foreach($month_name as $month){

		echo $this->form->input('month_name', array('label'=>false, 'id'=>'month_name'.$i, 'type'=>'hidden', 'value'=>$month)); 

	$i=$i+1;
	}
 
// To show month wise allocated applications
	$i=1;
	foreach($month_allocated_data as $data_value){

		echo $this->form->input('month_allocated_data', array('label'=>false, 'id'=>'month_allocated_data'.$i, 'type'=>'hidden', 'value'=>$data_value)); 

	$i=$i+1;
	}

// To show month wise approved applications
	$i=1;
	foreach($month_approved_data as $data_value){

		echo $this->form->input('month_approved_data', array('label'=>false, 'id'=>'month_approved_data'.$i, 'type'=>'hidden', 'value'=>$data_value)); 
		
	$i=$i+1;
	}

?>

	<div class="card">
	  <div class="card-header alert alert-primary">
		<h3 class="card-title">
		  <i class="fas fa-chart-pie mr-1"></i>
		  Applications V/s Granted
		</h3>
	  </div><!-- /.card-header -->
	  <div class="card-body">
		<div class="tab-content p-0">
		  <!-- Morris chart - Sales -->
		  <div class="chart tab-pane active" id="revenue-chart">
			  <canvas id="revenue-chart-canvas" height="300"></canvas>          
		  </div>
		  <div class="chart tab-pane" id="sales-chart">
			<canvas id="sales-chart-canvas" height="300"></canvas>                         
		  </div>  
		</div>
	  </div><!-- /.card-body -->
	</div>
            <!-- /.card -->
