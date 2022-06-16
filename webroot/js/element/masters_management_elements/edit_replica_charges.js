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