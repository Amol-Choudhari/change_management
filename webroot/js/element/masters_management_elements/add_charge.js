$(document).ready(function() {
    $('#charge').on('input propertychange', function() {
        
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    });

});