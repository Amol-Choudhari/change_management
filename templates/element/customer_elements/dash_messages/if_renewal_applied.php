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