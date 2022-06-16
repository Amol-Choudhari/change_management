var i=0;
var commodity_loop = $("#commodity_loop").val();
var year_loop = $("#year_loop").val();

$.each(commodity_loop , function(index, val) {

  var qty_total = 0;
  var j=0;

  $.each(year_loop , function(index1, val1) {

		$(".renewal_min_qty_table table td").focusout(function(){

			qty_total = parseInt($("#quantity_graded".concat(i).concat(j)).val())+ parseInt(qty_total);

			$("#qty_total".concat(i)).text(qty_total);

		});

		j++;
  });

  i++;
});

var final_submit_status = $("#final_submit_status").val();



// Extraxted below Script on 16-03-2022 BY Akash
$(document).ready(function () {

		$('#renewed_upto_date').datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			startDate: new Date()
		});
});
