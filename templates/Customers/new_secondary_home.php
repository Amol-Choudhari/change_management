<div class="content-wrapper">
    <section id="applicanthome" class="content">
        <div class="applhome container-fluid">
            <div id="accordion">
                <div class="card bsc">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Applications
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <?php 
                                $customer_id = $_SESSION['username']; 

                                if ($final_submit_status == 'no_final_submit') { 
                                    echo $this->element('customer_elements/dash_messages/new_or_old');
                                } else {

                                    if ($is_already_granted == 'no') {

                                        #This Below Block is added to Show the Message when the application is rejected -= Akash [25-11-2022]
                                        if($is_appl_rejected != NULL){
                                            echo $this->element('customer_elements/dash_messages/for_rejected');
                                        }
                                        
                                        #For Displaying the Application PDF Table#
                                        echo $this->element('customer_elements/pdf_table_view/application/general_application');
                                        
                                    } else if (!($final_submit_status == 'approved' && $final_submit_level == 'level_3')) {

                                        #This Below Block is added to Show the Message when the application is rejected - Akash [25-11-2022]
                                        if($is_appl_rejected != NULL){
                                            echo $this->element('customer_elements/dash_messages/for_rejected');
                                        }else{
                                            echo $this->element('customer_elements/dash_messages/for_old_appl_saved');
                                        } 
                                    }
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

                                    } else { //if new application
                                        $show_grant_table = 'yes';
                                    }

                                    #For Displaying the Grant PDF Table#
                                    if ($show_grant_table == 'yes') {
                                        echo $this->element('customer_elements/pdf_table_view/grant/gen_grant');
                                    }

                                    #For Displaying the Renewal PDF Table#
                                    if (!empty($renewal_final_submit_details)) { 
                                        echo $this->element('customer_elements/pdf_table_view/grant/renewal');
                                    } 

                                }

                                #To Displaying Message of renewal status
                                if ($is_already_granted == 'yes' && $show_grant_table == 'no' && empty($renewal_final_submit_details)) {
                                    echo $this->element('customer_elements/dash_messages/for_renewal_stats');
                                }

                                #To Displaying Message of applied for renewal 
                                if (!empty($renewal_final_submit_details)) {
                                    if ($show_renewal_btn == 'yes') { 
                                        echo $this->element('customer_elements/dash_messages/if_renewal_applied');
                                    }
                                }
                        
                                if ($show_applied_to_popup == 'yes') {
                                    echo $this->element('firm_applying_to_view/applying_to_view');
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <?php if(!empty($appl_15_digit_pdfs) || !empty($cert_15_digit_pdfs)) { ?>

                    <div class="card bsc">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                15-Digit Code Application
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                <?php 
                                    #For 15-Digit Code Application PDF Table View - Amol [2022]
                                    if(!empty($appl_15_digit_pdfs)) {
                                        echo $this->element('customer_elements/pdf_table_view/application/fdc_application');
                                    }

                                    #For 15-Digit Code Grant PDF Table View - Amol [2022]
                                    if(!empty($cert_15_digit_pdfs)) { 
                                        echo $this->element('customer_elements/pdf_table_view/grant/fdc_grant');
                                    } 
                                ?>
                            </div>
                        </div>
                    </div>

                <?php } ?>

                <?php if(!empty($appl_e_code_pdfs) || !empty($cert_e_code_pdfs)) { ?>

                    <div class="card bsc">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                E-Code Application
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">
                                <?php 
                                    #For E-Code Application PDF Table View - Amol [2022]
                                    if(!empty($appl_e_code_pdfs)) {
                                        echo $this->element('customer_elements/pdf_table_view/application/ecode_application');
                                    }

                                    #For E-Code Grant PDF Table View - Amol [2022]
                                    if(!empty($cert_e_code_pdfs)) { 
                                        echo $this->element('customer_elements/pdf_table_view/grant/ecode_grant');
                                    } 
                                ?>                    
                            </div>
                        </div>
                    </div>

                <?php } ?>

                <?php if(!empty($appl_adp_pdfs_records) || !empty($appl_adp_grant_pdfs)) { ?>

                    <div class="card bsc">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Approval of Designated Person Application
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">
                                <?php 
                                    #For ADP Application PDF Table View - Shankhpal [18/11/2022] 
                                    if(!empty($appl_adp_pdfs_records)) { 
                                        echo $this->element('customer_elements/pdf_table_view/application/adp_application');
                                    } 

                                    #For ADP Grant PDF Table View - Shankhpal [18/11/2022]
                                    if(!empty($appl_adp_grant_pdfs)) {
                                        echo $this->element('customer_elements/pdf_table_view/grant/adp_grant');
                                    }
                                ?>  
                            </div>
                        </div>
                    </div>

                <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>


<?php echo $this->element('line_track'); ?>