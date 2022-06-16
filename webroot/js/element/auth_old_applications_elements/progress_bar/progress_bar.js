$(document).ready(function(){

        var firm_profile_status=$("#firm_profile_status").val();
        var premises_profile_status=$("#premises_profile_status").val();
        var machinery_profile_status=$("#machinery_profile_status").val();
        var packing_details_status=$("#packing_details_status").val();
        var laboratory_details_status=$("#laboratory_details_status").val();
        var tbl_details_status=$("#tbl_details_status").val();
        var payment_status=$("#payment_status").val();



        //for firm profile form
            if(firm_profile_status=="saved"){

                $('#firm_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#firm_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
            }

            else if(firm_profile_status==""){

                $('#firm_profile').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#firm_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(firm_profile_status=="referred_back"){

                $('#firm_profile').removeClass('progress-bar-success');
                $('#firm_profile').removeClass('progress-bar-danger');
                $('#firm_profile').addClass('progress-bar-warning');
                $('#firm_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(firm_profile_status=="approved"){

                $('#firm_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#firm_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

                /*$('#firm_profile').removeClass('progress-bar-success');
                $('#firm_profile').removeClass('progress-bar-danger');
                $('#firm_profile').removeClass('progress-bar-warning');
                $('#firm_profile').addClass('progress-bar-info');
                $('#firm_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                */
            }



        //for premises profile form
            if(premises_profile_status=="saved"){

                $('#premises_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#premises_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            else if(premises_profile_status==""){

                $('#premises_profile').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#premises_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(premises_profile_status=="referred_back"){

                $('#premises_profile').removeClass('progress-bar-success');
                $('#premises_profile').removeClass('progress-bar-danger');
                $('#premises_profile').addClass('progress-bar-warning');
                $('#premises_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(premises_profile_status=="approved"){

                $('#premises_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#premises_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

                /*$('#premises_profile').removeClass('progress-bar-success');
                $('#premises_profile').removeClass('progress-bar-danger');
                $('#premises_profile').removeClass('progress-bar-warning');
                $('#premises_profile').addClass('progress-bar-info');
                $('#premises_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                */
            }



        //for machinery profile form
            if(machinery_profile_status=="saved"){

                $('#machinery_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#machinery_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            else if(machinery_profile_status==""){

                $('#machinery_profile').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#machinery_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(machinery_profile_status=="referred_back"){

                $('#machinery_profile').removeClass('progress-bar-success');
                $('#machinery_profile').removeClass('progress-bar-danger');
                $('#machinery_profile').addClass('progress-bar-warning');
                $('#machinery_profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(machinery_profile_status=="approved"){

                $('#machinery_profile').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#machinery_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

                /*$('#machinery_profile').removeClass('progress-bar-success');
                $('#machinery_profile').removeClass('progress-bar-danger');
                $('#machinery_profile').removeClass('progress-bar-warning');
                $('#machinery_profile').addClass('progress-bar-info');
                $('#machinery_profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                */
            }






        //for packing details form
            if(packing_details_status=="saved"){

                $('#packing_details').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#packing_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            else if(packing_details_status==""){

                $('#packing_details').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#packing_details_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(packing_details_status=="referred_back"){

                $('#packing_details').removeClass('progress-bar-success');
                $('#packing_details').removeClass('progress-bar-danger');
                $('#packing_details').addClass('progress-bar-warning');
                $('#packing_details_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(packing_details_status=="approved"){

                $('#packing_details').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#packing_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

                /*$('#packing_details').removeClass('progress-bar-success');
                $('#packing_details').removeClass('progress-bar-danger');
                $('#packing_details').removeClass('progress-bar-warning');
                $('#packing_details').addClass('progress-bar-info');
                $('#packing_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                */
            }






        //for laboratory details form
            if(laboratory_details_status=="saved"){

                $('#laboratory_details').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#laboratory_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            else if(laboratory_details_status==""){

                $('#laboratory_details').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#laboratory_details_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(laboratory_details_status=="referred_back"){

                $('#laboratory_details').removeClass('progress-bar-success');
                $('#laboratory_details').removeClass('progress-bar-danger');
                $('#laboratory_details').addClass('progress-bar-warning');
                $('#laboratory_details_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(laboratory_details_status=="approved"){

                $('#laboratory_details').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#laboratory_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

                /*$('#laboratory_details').removeClass('progress-bar-success');
                $('#laboratory_details').removeClass('progress-bar-danger');
                $('#laboratory_details').removeClass('progress-bar-warning');
                $('#laboratory_details').addClass('progress-bar-info');
                $('#laboratory_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                */
            }






        //for TBL details form
            if(tbl_details_status=="saved"){

                $('#tbl_details').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#tbl_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            else if(tbl_details_status==""){

                $('#tbl_details').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#tbl_details_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(tbl_details_status=="referred_back"){

                $('#tbl_details').removeClass('progress-bar-success');
                $('#tbl_details').removeClass('progress-bar-danger');
                $('#tbl_details').addClass('progress-bar-warning');
                $('#tbl_details_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(tbl_details_status=="approved"){

                $('#tbl_details').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#tbl_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

                /*$('#tbl_details').removeClass('progress-bar-success');
                $('#tbl_details').removeClass('progress-bar-danger');
                $('#tbl_details').removeClass('progress-bar-warning');
                $('#tbl_details').addClass('progress-bar-info');
                $('#tbl_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                */
            }






        //for payment details form
            if(payment_status=="saved"){

                $('#payment').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#payment_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            else if(payment_status==""){

                $('#payment').removeClass('progress-bar-success').addClass('progress-bar-danger');
                $('#payment_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(payment_status=="referred_back"){

                $('#payment').removeClass('progress-bar-success');
                $('#payment').removeClass('progress-bar-danger');
                $('#payment').addClass('progress-bar-warning');
                $('#payment_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');

            }
            else if(payment_status=="approved"){

                $('#payment').removeClass('progress-bar-danger').addClass('progress-bar-success');
                $('#payment_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

                /*$('#payment').removeClass('progress-bar-success');
                $('#payment').removeClass('progress-bar-danger');
                $('#payment').removeClass('progress-bar-warning');
                $('#payment').addClass('progress-bar-info');
                $('#payment_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                */
            }


    });
