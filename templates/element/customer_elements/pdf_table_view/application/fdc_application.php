<div class="row">
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
</div>