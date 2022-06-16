$.ajax({
        type: "POST",
        async:true,
        url:"../AjaxFunctions/show-district-dropdown",
        data: {state:state},
        beforeSend: function (xhr) { // Add this line
                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },
        success: function (data) {
                $("#district").append(data);
        }
});
