$(document).ready(function(){

    var form_section_id = $('#form_section_id').val();

    $('.edit_packer_id').click(function(e) {
        e.preventDefault();

        var edit_packer_id = $(this).attr('id');

        var form_data = $("#"+form_section_id).serializeArray();
        form_data.push({name: "edit_packer_id",value: edit_packer_id});

        $.ajax({
            type: "POST",
            url: "../AjaxFunctions/editPackerId",
            data: form_data,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success: function(response){
                $("#packer_table_detail").html(response);

            }
        });

    });


     $('.delete_packer_id').click(function(e) {
        e.preventDefault();

        var delete_packer_id = $(this).attr('id');

        var form_data = $("#"+form_section_id).serializeArray();
        form_data.push({name: "delete_packer_id",value: delete_packer_id});

        $.ajax({
            type: "POST",
            url: "../AjaxFunctions/deletePackerId",
            data: form_data,
            beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success: function(response){
                $("#packer_table_detail").html(response);

            }
        });

    });


    $('#save_packer_details').click(function(e) {
        e.preventDefault();

        var edit_packer_id ='';
        var save_packer_id = $(this).attr('id');
        var packer_name = $('#packer_name').val();
        var packer_type = $('#packer_type').val();
        var quantity_printed = $('#quantity_printed').val();

        var form_data = $("#"+form_section_id).serializeArray();
        form_data.push({name: "save_packer_id",value: save_packer_id},
                        {name: "packer_name",value: packer_name},
                        {name: "packer_type",value: packer_type},
                        {name: "quantity_printed",value: quantity_printed},
                        {name: "edit_packer_id",value: edit_packer_id});

        if(packer_table_validation() == true){

            $.ajax({
                type: "POST",
                url: "../AjaxFunctions/editPackerId",
                data: form_data,
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success: function(response){

                    $("#packer_table_detail").html(response);
                    $('#quantity_printed').val('');

                }
            });
        }
    });


    $('#add_packer_details').click(function(e) {
        e.preventDefault();


        var save_packer_id = $(this).attr('id');
        var packer_name = $('#packer_name').val();
        var packer_type = $('#packer_type').val();
        var quantity_printed = $('#quantity_printed').val();

        var form_data = $("#"+form_section_id).serializeArray();
        form_data.push(	{name: "save_packer_id",value: save_packer_id},
                        {name: "packer_name",value: packer_name},
                        {name: "packer_type",value: packer_type},
                        {name: "quantity_printed",value: quantity_printed});

        if(packer_table_validation() == true){
            $.ajax({
                type: "POST",
                url: "../AjaxFunctions/addPackerDetails",
                data: form_data,
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success: function(response){
                    $("#packer_table_detail").html(response);
                    $('#quantity_printed').val('');
                }
            });
        }
    });


});
