													 
<div class="container">
  
  <!-- For demo purpose -->
   <div class="row">
    <div class="col-lg-12 mx-auto text-left">
      <h2>COMMODITIES</h2>
      <p><strong>A. AGMARK certification is compulsory&nbsp;for following eight food products</strong> as manadated by relevants&nbsp;Food Safety and Standards (Prohibition and Restrictions on sales) Regulations and Food Safety and Standards (Packaging and labelling) Regulations made <strong>under&nbsp;Food Safety and Standards Act, 2006</strong>, namely,</p>
      <p>1.Blended Edible Vegetable Oils (BEVO)</p>
      <p>2.Fats Spread</p>
      <p>3.Carbia Callosa</p>
      <p>4.Honey dew</p>
      <p>5.Ghee having less RM value and a different standard for BR than that specified for the area in which it is imported for sale or storage</p>
      <p>6.Til Oil produced in Tripura, Assam and West Bengal&nbsp;having different standards than those specified for til oil</p>
      <p>7.Kangra Tea</p>
      <p>8.Light Black Pepper.</p>
      <p><strong>B. COMMODITIES NOTIFIED IN RELEVANT RULES UNDER THE AGRICULTURAL PRODUCE (GRADING AND &nbsp;&nbsp;MARKING) ACT, 1937 AS AMENDED</strong></p>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 mx-auto">
      <!-- Accordion -->
       <div id="accordionExample" class="accordion shadow">
          <?php 
              $i = 0;
            foreach($commodity_cat as $each){
              if(!empty($each['category_name'])){
              ?>
         <!-- CA Non-Bevo -->
         <div class="card">
          <div id="headingOne" class="card-header bg-white shadow-sm border-0">
            <h5 class="mb-0 font-weight-bold" ><a href="#" data-toggle="collapse" data-target="#collapseOne<?php echo $i; ?>" aria-expanded="false" aria-controls="collapseOne<?php echo $i; ?>" class="d-block position-relative text-dark text-uppercase collapsible-link py-2"><span class="dot">&#9632;</span><?php echo $each['category_name']; ?></a></h5>
          </div>
          <div id="collapseOne<?php echo $i; ?>" aria-labelledby="headingOne" data-parent="#accordionExample" class="collapse">
            <div class="card-body">
            <ul class="list-group">
            <?php 
               foreach($comm_array[$i] as $eachDetail){ 
                ?>
                 <li class="list-group-item" ><span>&#10146;</span>&nbsp;<?php echo $eachDetail['commodity_name']; ?></li>
                 <?php  }  ?>
             </ul> 
            </div>
          </div>
        </div>
          <?php $i++;} } ?>
      </div>
    </div>
  </div>
  <br>
  <h5><strong>C. SUMMARY</strong></h5>
  <table class="table table-bordered">
    <thead>
      
      <tr>
        <th>Sr.no</th>
        <th>Group</th>
        <th> No. of commodities notified</th>
      </tr>
    </thead>
    <tbody>
    <?php  
       $i= 0;
       $sum = 0;
				$sr_no = 1;
					foreach($commodity_cat as $each){
            if(!empty($each['category_name'])){
            ?>
            <tr>
              <td><?php echo $sr_no; ?></td>
							<td><?php echo $each['category_name']; ?></td>
							<td><?php echo count($comm_array[$i]); ?></td>
               <?php $sum += count($comm_array[$i]) ?>
						</tr>
            
            <?php } ?>
            
         <?php $sr_no++;$i++;}  ?>
        
          <tr>
              <td></td>
              <td><strong>Total No. of Commodities : </strong></td>
              <td><?php echo "<strong>$sum</strong>"; ?></td></td>
          </tr>
    </tbody>
  </table>
</div>



