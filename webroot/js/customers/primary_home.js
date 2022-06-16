$('.firm_delete_btn').click(function() {
  var link = $(this).val();
  $('#modal-default').modal('show');

  $('#confirm-del').click(function() {
    location.href = link;
  })
});

$("#example1").DataTable();
