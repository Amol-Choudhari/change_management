var csrfToken = $('#bottom_layout_csrf_call').val();


$(document).ready(function() {
    $("#form_outer_main :input[type='reset']").hide();
});

$(function () {
  $("#example1").DataTable({
    "responsive": true,
    "autoWidth": false,
  });
  $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
  });
});
