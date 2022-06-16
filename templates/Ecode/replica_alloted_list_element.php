<?php echo $this->Html->css('Ecode/replica_alloted_list_element') ?>
<?php echo $this->Form->create(); ?>
    <div class="replica_details_mod form-group">
        <div class="col-md-4">
            <input class="form-control" id="rep_ser_no" type="text" placeholder="Enter Replica Number"/>
        </div>

        <button id="replica_details_btn" class="btn btn-primary">Get E-Code Details</button>
            <div id="replica_detail_popup" class="modal">
                <div class="modal-content wd30">
                <span class="close"><b>&times;</b></span>
                <h4 class="modal-header">Replica Details</h4>
                <div id="replica_detail_content"><table id="append-table" class="table-bordered"></table></div>
            </div>
        </div>
    </div>

    <div class="card card-info">
        <div class="card-header"><h3 class="card-title-new">Alloted E-Code Status</h3></div>
            <div class="table-format">
                <table id="applicant_logs_table" class="table table-bordered table-striped table-hover">
                    <thead class="tablehead">
                        <tr>
                            <th>Sr.No</th>
                            <th>Customer Id</th>
                            <th>CA Unique No.</th>
                            <th>Commodity</th>
                            <th>Date</th>
                            <th>Version</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($replica_stats)) {

                            $i=0;$sr_no = 1;

                            foreach ($replica_stats as $each) { ?>

                            <tr>
                                <td><?php echo $sr_no; ?></td>
                                <td><?php echo $each['customer_id']; ?></td>
                                <td><?php echo $each['ca_unique_no']; ?></td>
                                <td><?php echo $commodity[$i] ?></td>
                                <td><?php $explodeDate = explode(' ',$each['modified']); echo $explodeDate[0]; ?></td>
                                <td><?php echo $pdf_version[$i]; ?></td>
                                <td><a class="view_letter_btn" target="_blank" href="<?php echo $pdf_link[$i]; ?>">View Letter</a></td>
                            </tr>
                            <?php $sr_no++; $i=$i+1;	} } ?>
                        </tbody>
                    </table>
                </div>
            </div>
<?php echo $this->Form->end(); ?>


<?php echo $this->Html->script('Ecode/replica_alloted_list_element'); ?>
