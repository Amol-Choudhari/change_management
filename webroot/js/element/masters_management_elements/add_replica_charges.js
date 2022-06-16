function get_commodity(){


    var commodity = $("#commodity_category").val();
    $.ajax({
            type: "POST",
            async:true,
            url:"../AjaxFunctions/show-commodity-dropdown",
            data: {commodity:commodity},
            beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            },
            success: function (data) {
                    $("#commodity").append(data);
            }
    });
}

$("#commodity_category").change(function(){
	
	get_commodity();
});


$(document).ready(function() {
        $('#replica_code').on('input propertychange', function() {
                charLimit(this, 1);
        });
});
    
let charLimit = (input, maxChar) => {

        let len = $(input).val().length;
        if (len > maxChar) {
                $(input).val($(input).val().substring(0, maxChar));
        }
}