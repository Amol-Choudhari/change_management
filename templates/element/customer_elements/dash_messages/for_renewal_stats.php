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
                    echo "Hello, Your Old Application has been successfully verified. 
                    <br />Your Certificate is valid upto <b>$valid_upto_date</b>.
                    <br /> A <b>Renewal</b> button option will be available to you from the <u>date of verification or three months before valid upto date</u>, whichever is later.
                    <br />This option for <b>Renewal</b> will be available till one month from date of validity, after which you won't be able to apply for renewal. Thank you";
                } 
            ?>
        </div>
    </div>
</div>