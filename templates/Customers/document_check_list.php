<div class="container">
  <!-- For demo purpose -->
   <div class="row">
    <div class="col-lg-12 mx-auto text-center">
      <h3 class="display-4">Documents Check List</h3>
       <hr/>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 mx-auto">
      <!-- Accordion -->
       <div id="accordionExample" class="accordion shadow">
          <?php 
              $i = 0;
            foreach($form_type as $each){?>
         <!-- CA Non-Bevo -->
         <div class="card">
          <div id="headingOne" class="card-header bg-white shadow-sm border-0">
            <h5 class="mb-0 font-weight-bold" ><a href="#" data-toggle="collapse" data-target="#collapseOne<?php echo $i; ?>" aria-expanded="false" aria-controls="collapseOne<?php echo $i; ?>" class="d-block position-relative text-dark text-uppercase collapsible-link py-2"><span class="dot">&#9632;</span><?php echo $each; ?></a></h5>
          </div>
          <div id="collapseOne<?php echo $i; ?>" aria-labelledby="headingOne" data-parent="#accordionExample" class="collapse">
            <div class="card-body">
            <ul class="list-group">
            <?php 
               foreach($doc_array[$i] as $eachDetail){ ?>
                 <li class="list-group-item" ><span>&#10146;</span>&nbsp;<?php echo $eachDetail['releted_document']; ?></li>
                 <?php  }  ?>
             </ul> 
            </div>
          </div>
        </div>
          <?php $i++;}  ?>
      </div>
    </div>
  </div>
</div>