<div class="row">
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
</div>