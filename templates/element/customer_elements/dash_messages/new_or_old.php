<div class="row">
    <div class="col-lg-6">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info"></i> Please Note !</h5>
            <?php 
                if ($is_already_granted == 'yes') {
                    echo "To Fill Your OLD Application Details, Please click on <b>Apply For</b> button. Thankyou";
                } else {
                    echo "Please click on <b>Apply For -> <i>New Certification</i></b> button to fill application details. Thankyou";
                }
            ?>
        </div>
    </div>
</div>