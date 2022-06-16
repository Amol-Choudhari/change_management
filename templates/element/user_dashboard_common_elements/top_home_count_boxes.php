 <!-- Main content -->
 <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
         
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><span class="percent"><?php echo round($ca_percentage); ?>%</h3>

                <p>CA Applications</p>
              </div>
              <div class="icon">
                <i class="fa fa-file-text"></i>
              </div>
              <div href="#" class="small-box-footer"><?php echo $ca_applications_count; ?> Out of <?php echo $total_allocated_applications; ?> <!--<i class="fas fa-arrow-circle-right"></i>--></div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><span class="percent"><?php echo round($printing_percentage); ?>%</h3>

                <p>Printing Applications</p>
              </div>
              <div class="icon">
                <i class="fa fa-print"></i>
              </div>
              <div href="#" class="small-box-footer"><?php echo $printing_applications_count; ?> Out of <?php echo $total_allocated_applications; ?> <!--<i class="fas fa-arrow-circle-right"></i>--></div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><span class="percent"><?php echo round($lab_percentage); ?>%</h3>

                <p>Laboratory Applications</p>
              </div>
              <div class="icon">
                <i class="fa fa-flask"></i>
              </div>
              <div class="small-box-footer"><?php echo $lab_applications_count; ?> Out of <?php echo $total_allocated_applications; ?> <!--<i class="fas fa-arrow-circle-right"></i>--></div>
            </div>
          </div>
          <!-- ./col -->
		   <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><span class="percent"><?php echo round($siteinspection_percentage); ?>%</span></h3>

                <p>Inspection Completed</p>
              </div>
              <div class="icon">
                <i class="fa fa-edit"></i>
              </div>
              <div  class="small-box-footer"><?php echo $site_inspection_count; ?> Out of <?php echo $total_allocated_applications; ?> <!--<i class="fas fa-arrow-circle-right"></i>--></div>
            </div>
          </div>
          <!-- ./col -->
        </div>
		
	</div>
	</section>
</div>