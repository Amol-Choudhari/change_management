<?php ?>

    <table id="all_replica_charges_table" class="table m-0 table-striped table-bordered table-hover">
        <thead class="tablehead">
            <tr>
                <th>SR.No</th>
                <th>Commodity</th>
                <th>Category</th>
                <th>Min. Qty</th>
                <th>Charge</th>
                <th>Unit</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($all_replica_charges)) {
                $i=0;
                $sr_no =1;
                foreach ($all_replica_charges as $each) {

                    if (!empty($commodity[$i]) && !empty($category[$i])) { ?>

                        <tr>
                            <td><?php echo $sr_no; ?></td>
                            <td><?php echo $commodity[$i]; ?></td>
                            <td><?php echo $category[$i]; ?></td>
                            <td><?php echo $each['min_qty'] ?></td>
                            <td><?php echo $each['charges'] ?></td>
                            <td><?php echo $each['unit'] ?></td>
                            <td><?php echo $this->Html->link('', array('controller' => 'masters', 'action'=>'editfetchAndRedirect', $each['id']),array('class'=>'far fa-edit','title'=>'Edit')); ?>
                        </tr>

                        <?php $sr_no++; } $i=$i+1;	 } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php echo $this->Html->script('element/masters_management_elements/all_replica_charges'); ?>
