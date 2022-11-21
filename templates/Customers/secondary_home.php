<div class="content-wrapper">
    <section id="applicanthome" class="content">
        <div class="applhome container-fluid">
        <?php 
            $customer_id = $_SESSION['username'];
            
            if ($final_submit_status == 'no_final_submit') {  ?>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                            <?php 
                                if ($is_already_granted == 'yes') {
                                    echo "To fill your old application details please click on 'Apply' button. Thankyou";
                                } else {
                                    echo "Please click on 'Apply' button to fill application details. Thankyou";
                                }
                            ?>
                        </div>
                    </div>
                </div>

            <?php } else {

                if ($is_already_granted == 'no') { ?>

                    <!-- #This Below Block is added to Show the Message if teh application is rejected -->
                    <?php if($is_appl_rejected != NULL){ ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-info alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                                    Your Application Has Been Rejected by the DMI.
                                    <p>Reason For Rejection : <?php echo $is_appl_rejected; ?></p>
                                </div>
                            </div>  
                        </div>
                    <?php } ?>
                 
                    <div class="row">
                        <section class="col-lg-12 connectedSortable">
                            <div class="card card-info">
                                <div class="card-header"><h3 class="card-title-new">Application Versions for Certificate</h3></div>
                                <div class="card-body">
                                    <table id="example1" class="table m-0 table-bordered table-hover">
                                        <thead class="tablehead">
                                            <tr>
                                                <th>Applicant Id</th>
                                                <th>Application Pdf</th>
                                                <th>Date</th>
                                                <th>Version</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($application_pdfs as $each_record) { ?>
                                                <tr>
                                                    <td class="boldtext"><?php echo $each_record['customer_id']; ?></td>
                                                    <td><?php $split_file_path = explode("/",$each_record['pdf_file']); $file_name = $split_file_path[count($split_file_path) - 1]; ?>
                                                        <a target="blank" href="<?php echo $each_record['pdf_file']; ?>"><?php echo $file_name; ?></a>
                                                    </td>
                                                    <td><?php echo substr($each_record['modified'],0,-9); ?></td>
                                                    <td><?php echo $each_record['pdf_version']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                    
                <?php } else if (!($final_submit_status == 'approved' && $final_submit_level == 'level_3')) { ?>

                    <!-- #This Below Block is added to Show the Message if teh application is rejected -->
                    <?php if($is_appl_rejected != NULL){ ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-info alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                                    Your Application Has Been Rejected by the DMI.
                                    <p>Reason For Rejection : <?php echo $is_appl_rejected; ?></p>
                                </div>
                            </div>  
                        </div>
                    <?php }else{ ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert alert-info alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                                    Your old application details are saved and finally submitted, to check application status please click on "Application Status" button. Thankyou
                                </div>
                            </div>  
                        </div>
                    <?php } ?>
                <?php }
            }

            $show_grant_table = null;
            //check if primary application approved
            if ($final_submit_status == 'approved' && $final_submit_level == 'level_3') {
                //check if old application
                if ($is_already_granted == 'yes') {
                //check if old application online renewal granted
                if ($renewal_final_submit_status == 'approved' && $renewal_final_submit_level == 'level_3') {
                    $show_grant_table = 'yes';
                } else {
                    $show_grant_table = 'no';
                }
                //if new application
                } else {
                    $show_grant_table = 'yes';
                }


                if ($show_grant_table == 'yes') { ?>

                    <div class="row">
                        <section class="col-lg-12 connectedSortable">
                            <div class="card card-info">
                                <div class="card-header"><h3 class="card-title-new">Granted Certificate Versions</h3></div>
                                <div class="card-body">
                                    <table id="example2" class="table m-0 table-bordered">
                                        <thead class="tablehead">
                                            <tr>
                                                <th>Applicant Id</th>
                                                <th>Certificate Pdf</th>
                                                <th>Grant Date</th>
                                            </tr>
                                        </thead>  
                                        <tbody>
                                            <?php foreach ($grant_certificate_pdf as $each_record) { ?>
                                                <tr>
                                                    <td class="boldtext"><?php echo $each_record['customer_id']; ?></td>
                                                    <td><?php $split_file_path = explode("/",$each_record['pdf_file']); $file_name = $split_file_path[count($split_file_path) - 1]; ?>
                                                        <a target="blank" href="<?php echo $each_record['pdf_file']; ?>"><?php echo $file_name; ?></a>
                                                    </td>
                                                    <td><?php echo substr($each_record['date'],0,-9); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>  
                        </section>
                    </div>

                <?php } ?>

                <?php if (!empty($renewal_final_submit_details)) { ?>

                    <div class="row">
                        <section class="col-lg-12 connectedSortable">
                            <div class="card card-info">
                                <div class="card-header"><h3 class="card-title-new">Certificate Renewal Application versions</h3></div>
                                <div class="card-body">
                                    <table id="example3" class="table m-0 table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Applicant Id</th>
                                                <th>Application Pdf</th>
                                                <th>Date</th>
                                                <th>Version</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($renewal_application_pdfs as $each_record) { ?>
                                                <tr>
                                                    <td class="boldtext"><?php echo $each_record['customer_id']; ?></td>
                                                    <td><?php $split_file_path = explode("/",$each_record['pdf_file']);$file_name = $split_file_path[count($split_file_path) - 1]; ?>
                                                        <a target="blank" href="<?php echo $each_record['pdf_file']; ?>"><?php echo $file_name; ?></a>
                                                    </td>
                                                    <td><?php echo substr($each_record['modified'],0,-9); ?></td>
                                                    <td><?php echo $each_record['pdf_version']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>

                <?php } ?>

            <?php } ?>

            <?php if ($is_already_granted == 'yes' && $show_grant_table == 'no' && empty($renewal_final_submit_details)) { ?>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                            <?php 
                                if ($show_renewal_btn == 'yes') {
                                    if ($show_renewal_button == 'Renewal') {
                                        echo "Please click on 'Renewal' button to proceed for renewal application. Thankyou";
                                    } elseif ($show_renewal_button == 'Renewal Status') {
                                        echo "To check your renewal application status please click on 'Renewal Status' button. Thankyou";
                                    }
                                } else {
                                    echo "Your Old Application has been successfully verified. <br />Your Certificate is valid upto ".$valid_upto_date."<br /> A 'Renewal' button option will be available to you from the date of verification or three months before valid upto date, whichever is later.<br />This option for 'Renewal' will be available till one month from date of validity, after which you won't be able to apply for renewal. Thank you";
                                } 
                            ?>
                        </div>
                    </div>
                </div>

            <?php } ?>

            <?php if (!empty($renewal_final_submit_details)) { ?>

                <?php if ($show_renewal_btn == 'yes') { ?>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-info"></i> Please Note !</h5>
                                <?php
                                if ($show_renewal_button == 'Renewal') {
                                    echo "Please click on 'Renewal' button to proceed for renewal application. Thankyou";
                                } elseif ($show_renewal_button == 'Renewal Status') {
                                    echo "To check your renewal application status please click on 'Renewal Status' button. Thankyou";
                                } ?>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            <?php }

            if ($show_applied_to_popup == 'yes') {
                echo $this->element('firm_applying_to_view/applying_to_view');
            }
        ?>
    
    
        <!-- Added to show list of pdfs for 15 digit code approval application and certificates -->
        <div class="row">
            <?php if(!empty($appl_15_digit_pdfs)) { ?>
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-info">
                        <div class="card-header"><h3 class="card-title-new">Application Versions to use 15 Digit Code</h3></div>
                        <div class="card-body">
                            <table id="example1" class="table m-0 table-bordered table-hover">
                                <thead class="tablehead">
                                <tr>
                                    <th>Applicant Id</th>
                                    <th>Application Pdf</th>
                                    <th>Date</th>
                                    <th>Version</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($appl_15_digit_pdfs as $each_record) { ?>
                                <tr>
                                    <td class="boldtext"><?php echo $each_record['customer_id']; ?></td>
                                    <td><?php $split_file_path = explode("/",$each_record['pdf_file']); $file_name = $split_file_path[count($split_file_path) - 1]; ?>
                                        <a target="blank" href="<?php echo $each_record['pdf_file']; ?>"><?php echo $file_name; ?></a>
                                    </td>
                                    <td><?php echo substr($each_record['modified'],0,-9); ?></td>
                                    <td><?php echo $each_record['pdf_version']; ?></td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            <?php } ?>

            <?php if(!empty($cert_15_digit_pdfs)) { ?>
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-info">
                        <div class="card-header"><h3 class="card-title-new">Certificate of Approval to Use 15 Digit Code</h3></div>
                        <div class="card-body">
                            <table id="example2" class="table m-0 table-bordered">
                                <thead class="tablehead">
                                    <tr>
                                        <th>Applicant Id</th>
                                        <th>Certificate Pdf</th>
                                        <th>Grant Date</th>
                                    </tr>
                                </thead>  
                                <tbody>
                                    <?php foreach ($cert_15_digit_pdfs as $each_record) { ?>
                                    <tr>
                                        <td class="boldtext"><?php echo $each_record['customer_id']; ?></td>
                                        <td><?php $split_file_path = explode("/",$each_record['pdf_file']); $file_name = $split_file_path[count($split_file_path) - 1]; ?>
                                                <a target="blank" href="<?php echo $each_record['pdf_file']; ?>"><?php echo $file_name; ?></a>
                                        </td>
                                        <td><?php echo substr($each_record['date'],0,-9); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>  
                </section>
            <?php } ?>
        </div>
    
  
        <!-- Added to show list of pdfs for E-code approval application and certificates -->
        <div class="row">
            <?php if(!empty($appl_e_code_pdfs)) { ?>
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-info">
                        <div class="card-header"><h3 class="card-title-new">Application Versions to use E-Code</h3></div>
                        <div class="card-body">
                            <table id="example1" class="table m-0 table-bordered table-hover">
                                <thead class="tablehead">
                                    <tr>
                                        <th>Applicant Id</th>
                                        <th>Application Pdf</th>
                                        <th>Date</th>
                                        <th>Version</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appl_e_code_pdfs as $each_record) { ?>
                                        <tr>
                                            <td class="boldtext"><?php echo $each_record['customer_id']; ?></td>
                                            <td><?php $split_file_path = explode("/",$each_record['pdf_file']); $file_name = $split_file_path[count($split_file_path) - 1]; ?>
                                                <a target="blank" href="<?php echo $each_record['pdf_file']; ?>"><?php echo $file_name; ?></a>
                                            </td>
                                            <td><?php echo substr($each_record['modified'],0,-9); ?></td>
                                            <td><?php echo $each_record['pdf_version']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            <?php } ?>

            <?php if(!empty($cert_e_code_pdfs)) { ?>
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-info">
                        <div class="card-header"><h3 class="card-title-new">Certificate of Approval to Use E-Code</h3></div>
                        <div class="card-body">
                            <table id="example2" class="table m-0 table-bordered">
                                <thead class="tablehead">
                                    <tr>
                                        <th>Applicant Id</th>
                                        <th>Certificate Pdf</th>
                                        <th>Grant Date</th>
                                    </tr>
                                </thead>  
                                <tbody>
                                    <?php foreach ($cert_e_code_pdfs as $each_record) { ?>
                                    <tr>
                                        <td class="boldtext"><?php echo $each_record['customer_id']; ?></td>
                                        <td><?php $split_file_path = explode("/",$each_record['pdf_file']); $file_name = $split_file_path[count($split_file_path) - 1]; ?>
                                                <a target="blank" href="<?php echo $each_record['pdf_file']; ?>"><?php echo $file_name; ?></a>
                                        </td>
                                        <td><?php echo substr($each_record['date'],0,-9); ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>  
                </section>
            <?php } ?>

        <!-- condition added by shankhpal shende on 18/11/2022  -->
        <?php  if(!empty($appl_adp_pdfs_records)) { ?>
          <section class="col-lg-12 connectedSortable">
            <div class="card card-info">
              <div class="card-header"><h3 class="card-title-new">Application Pdf for Approval of Designated persons</h3></div>
                <div class="card-body">
                  <table id="example2" class="table m-0 table-bordered">
                    <thead class="tablehead">
                      <tr>
                        <th>Applicant Id</th>
                        <th>Certificate Pdf</th>
                        <th>Grant Date</th>
                    </tr>
                    </thead>  
                    <tbody>
                    <?php foreach ($appl_adp_pdfs_records as $each_record) { ?>
                      <tr>
                        <td class="boldtext"><?php echo $each_record['customer_id']; ?></td>
                        <td><?php $split_file_path = explode("/",$each_record['pdf_file']); $file_name = $split_file_path[count($split_file_path) - 1]; ?>
                                  <a target="blank" href="<?php echo $each_record['pdf_file']; ?>"><?php echo $file_name; ?></a>
                        </td>
                        <td><?php echo substr($each_record['date'],0,-9); ?></td>
                      </tr>
                    <?php } ?>
                    </tbody>
                </table>
              </div>
            </div>  
          </section>
        <?php } ?>

         <!-- condition added by shankhpal shende on 18/11/2022  -->
         <?php  if(!empty($appl_adp_grant_pdfs)) { ?>
          <section class="col-lg-12 connectedSortable">
            <div class="card card-info">
              <div class="card-header"><h3 class="card-title-new">Grant Certificate Pdf for Approval of Designated persons</h3></div>
                <div class="card-body">
                  <table id="example2" class="table m-0 table-bordered">
                    <thead class="tablehead">
                      <tr>
                        <th>Applicant Id</th>
                        <th>Certificate Pdf</th>
                        <th>Grant Date</th>
                    </tr>
                    </thead>  
                    <tbody>
                    <?php foreach ($appl_adp_grant_pdfs as $each_record) { ?>
                      <tr>
                        <td class="boldtext"><?php echo $each_record['customer_id']; ?></td>
                        <td><?php $split_file_path = explode("/",$each_record['pdf_file']); $file_name = $split_file_path[count($split_file_path) - 1]; ?>
                                  <a target="blank" href="<?php echo $each_record['pdf_file']; ?>"><?php echo $file_name; ?></a>
                        </td>
                        <td><?php echo substr($each_record['date'],0,-9); ?></td>
                      </tr>
                    <?php } ?>
                    </tbody>
                </table>
              </div>
            </div>  
          </section>
        <?php } ?>

        </div>
    </div>
  </section>
</div>


<?php echo $this->element('line_track'); ?>