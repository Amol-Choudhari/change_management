$(document).ready(function(){

        var laboratory_firm_form_status=$("#laboratory_firm_form_status").val();
        var laboratory_other_form_status=$("#laboratory_other_form_status").val();


        //for laboratory firm profile form
            if(laboratory_firm_form_status=="saved"){

                $('#laboratory_firm_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#laboratory_firm_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
            }

            else if(laboratory_firm_form_status==""){

                $('#laboratory_firm_profile').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#laboratory_firm_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(laboratory_firm_form_status=="referred_back"){

                $('#laboratory_firm_profile').removeClass('progress-bar-success');
                $('#laboratory_firm_profile').removeClass('progress-bar-danger');
                $('#laboratory_firm_profile').addClass('progress-bar-warning');
                $('#laboratory_firm_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(laboratory_firm_form_status=="approved"){

                $('#laboratory_firm_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#laboratory_firm_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }











        //for laboratory firm other detail form

            if(laboratory_other_form_status=="saved"){

                $('#laboratory_firm_other_detail').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#laboratory_firm_other_detail_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            else if(laboratory_other_form_status==""){

                $('#laboratory_firm_other_detail').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#laboratory_firm_other_detail_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(laboratory_other_form_status=="referred_back"){

                $('#laboratory_firm_other_detail').removeClass('progress-bar-success');
                $('#laboratory_firm_other_detail').removeClass('progress-bar-danger');
                $('#laboratory_firm_other_detail').addClass('progress-bar-warning');
                $('#laboratory_firm_other_detail_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(laboratory_other_form_status=="approved"){

                $('#laboratory_firm_other_detail').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#laboratory_firm_other_detail_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }


});
    
