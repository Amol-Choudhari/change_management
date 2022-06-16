//FOR CHECKING THE REPLCIA CODE FOR 15-DIGIT CODE ALREADY EXITS AJAX AND VALIDATION IS ADDED BY AKASH ON 02-12-2021
$('#state_name').focusout(function(){

    var state_name = $("#state_name").val();

    $.ajax({
        type : 'POST',
        url : '../AjaxFunctions/check_if_state_already_exist',
        async : true,
        data : {state_name:state_name},
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success : function(response){

            if($.trim(response)=='yes'){

                $.alert({
                    title: "State Name Error!",
                    content: 'The State Name <b>'+ state_name  +'</b> is already used. Please verify and enter again.',
                    type: 'red',
                     columnClass: 'medium',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Try again',
                            btnClass: 'btn-red',
                            action: function(){
                                $("#state_name").val('');
                            }
                        },
                    }
                });
            }
        }
    });
});


