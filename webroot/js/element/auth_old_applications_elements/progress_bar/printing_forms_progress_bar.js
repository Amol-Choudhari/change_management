$(document).ready(function(){

        var printing_firm_form_status=$("#printing_firm_form_status").val();
        var printing_premises_form_status=$("#printing_premises_form_status").val();
        var printing_unit_form_status=$("#printing_unit_form_status").val();
        var printing_payment_status=$("#printing_payment_status").val();



        //for firm profile form
            if(printing_firm_form_status=="saved"){

                $('#firm_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#firm_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
            }

            else if(printing_firm_form_status==""){

                $('#firm_profile').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#firm_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(printing_firm_form_status=="referred_back"){

                $('#firm_profile').removeClass('progress-bar-success');
                $('#firm_profile').removeClass('progress-bar-danger');
                $('#firm_profile').addClass('progress-bar-warning');
                $('#firm_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(printing_firm_form_status=="approved"){

                $('#firm_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#firm_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }











        //for premises profile form
            if(printing_premises_form_status=="saved"){

                $('#premises_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#premises_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            else if(printing_premises_form_status==""){

                $('#premises_profile').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#premises_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(printing_premises_form_status=="referred_back"){

                $('#premises_profile').removeClass('progress-bar-success');
                $('#premises_profile').removeClass('progress-bar-danger');
                $('#premises_profile').addClass('progress-bar-warning');
                $('#premises_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(printing_premises_form_status=="approved"){

                $('#premises_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#premises_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }












        //for printing unit details form
            if(printing_unit_form_status=="saved"){

                $('#printing_unit_details').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#printing_unit_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            else if(printing_unit_form_status==""){

                $('#printing_unit_details').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#printing_unit_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(printing_unit_form_status=="referred_back"){

                $('#printing_unit_details').removeClass('progress-bar-success');
                $('#printing_unit_details').removeClass('progress-bar-danger');
                $('#printing_unit_details').addClass('progress-bar-warning');
                $('#printing_unit_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(printing_unit_form_status=="approved"){

                $('#printing_unit_details').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#printing_unit_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }















        //for payment details form
            if(printing_payment_status=="saved"){

                $('#payment').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#payment_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            else if(printing_payment_status==""){

                $('#payment').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#payment_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(printing_payment_status=="referred_back"){

                $('#payment').removeClass('progress-bar-success');
                $('#payment').removeClass('progress-bar-danger');
                $('#payment').addClass('progress-bar-warning');
                $('#payment_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(printing_payment_status=="approved"){

                $('#payment').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#payment_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }


    });
