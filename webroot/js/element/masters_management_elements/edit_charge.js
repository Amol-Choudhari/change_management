$(document).ready(function() {
    $('#charge').on('input propertychange', function() {
        
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    });

    $('#application_type_id').attr("style", "pointer-events: none;");
    $('#firm_type').attr("style", "pointer-events: none;");


});