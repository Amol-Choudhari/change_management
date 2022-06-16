
    // Change on 5/11/2018 : Clear search filter field value of click search button - By Pravin Bhakare
    $('.search_field').val('');

    // for multiselect dropdown option

    $('#application_type').multiselect({
        includeSelectAllOption: true,
        nonSelectedText :'Select Application Type',
        buttonWidth: '100%',
        maxHeight: 400,
    });

    //create the dynamic path for ajax url (Done by pravin 03/11/2017) - added by Ankur Jangid 20/05/2021
    var host = location.hostname;
    var paths = window.location.pathname;
    var split_paths = paths.split("/");
    var path = "/"+split_paths[1]+"/"+split_paths[2];

    $('#state').change(function(e){

        var state = $('#state').val();

        var form_data = $("#newly_added_firm").serializeArray();
        form_data.push({name: "state", value: state});

        $.ajax({
            type: "POST",
            url: path+"/showDistrictDropdown",
            data: form_data,
            success: function(response){
                $("#district").html(response);
            }
        });
    });

    $(document).ready(function () {

        $('#fromdate').datepicker({format: "dd/mm/yyyy",orientation: "left top",autoclose: true,});
        $('#todate').datepicker({ format: "dd/mm/yyyy", orientation: "left top", autoclose: true, });
        var table = $('#primary_user_details_report_table').DataTable();

        $('#primary_user_details_report_table tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();

            var form_data = $("#newly_added_firm").serializeArray();
            form_data.push({name: "data", value: data[1]});
            $("#customer_id").html(data[1]);

            $.ajax({
                type: "POST",
                url: path+"/primaryFirmDetailsReport",
                data: form_data,
                success: function(response){
                        deta = JSON.parse(response);
                        $("#firm_details_table tbody tr").remove();

                        if(deta.length !== 0) {
                            for(i=0; i<deta[0].length; i++) {
                                var firm_id = deta[0][i].customer_id;
                                var firm_name = deta[0][i].firm_name;
                                var firm_app_type = deta[0][i].certification_type;
                                var firm_state = deta[0][i].state;
                                var firm_district = deta[0][i].district;
                                var firm_date = deta[0][i].created.split(' ');

                                var tr_str = "<tr id='table_row' class='row-hover border border-light'>" +
                                        "<td class='text-right'><span class='badge title borderless'>" + firm_id + "</span></td>" +
                                        "<td><span class='badge title borderless'>" + firm_name + "</span></td>" +
                                        "<td><span class='badge subtitle borderless'>" + firm_app_type + "</span></td>" +
                                        "<td class='text-right'><span class='badge subtitle borderless'>" + firm_state + "</span></td>" +
                                        "<td><span class='badge subtitle borderless'>" + firm_district + "</span></td>" +
                                        "<td class='text-right'><span class='badge title borderless'>" + firm_date[0] + "</span></td>" +
                                        "<td><span class='badge subtitle borderless'>" + firm_date[1] + "</span></td>" +
                                    "</tr>";

                                $("#firm_details_table tbody").append(tr_str);
                            }
                        }
                        else {
                            var tr_str = "<tr id='table_row' class='row-hover border border-light'>" +
                                        "<td colspan='5' class='text-center'><span class='badge title borderless fs20'>" + 'No Firm Added' + "</span></td>" +
                                    "</tr>";

                            $("#firm_details_table tbody").append(tr_str);
                        }
                }
            });
        } );

        $('#search_btn').click(function(){

            var from = $("#fromdate").val().split("/");
            var fromdate = new Date(from[2], from[1] - 1, from[0]);

            var from = $("#todate").val().split("/");
            var todate = new Date(from[2], from[1] - 1, from[0]);

            if(todate < fromdate){

                alert('Invalid Date Range Selection');
                return false;
            }
        });

        $('html, body').animate({
            scrollTop: $('#page-load').offset().top
        }, 'slow');

    });
