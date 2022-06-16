$("#test-2").click(function(e){

	openForm2();
		return false;
	}
});

$("#test-3").click(function(e){

	openForm3();
		return false;
	}
});

$("#test-4").click(function(e){

	openForm4();
		return false;
	}
});

$("#test-5").click(function(e){

	openForm5();
		return false;
	}
});

$("#test-6").click(function(e){

	openForm6();
		return false;
	}
});

$("#test-7").click(function(e){

	openForm7();
		return false;
	}
});

$("#test-8").click(function(e){

	openForm8();
		return false;
	}
});

function openForm2() {
  document.getElementById("myForm2").style.display = "block";
  document.getElementById("test-2").setAttribute( "onClick", "closeForm2(); return false;" );
  document.getElementById("myForm3").style.display = "none";
  document.getElementById("myForm4").style.display = "none";
  document.getElementById("myForm5").style.display = "none";
  document.getElementById("myForm6").style.display = "none";
  document.getElementById("myForm7").style.display = "none";
  document.getElementById("myForm8").style.display = "none";
}
function closeForm2() {
  document.getElementById("myForm2").style.display = "none";
  document.getElementById("test-2").setAttribute( "onClick", "openForm2(); return false;" );
}
function openForm3() {
  document.getElementById("myForm3").style.display = "block";
  document.getElementById("test-3").setAttribute( "onClick", "closeForm3(); return false;" );
  document.getElementById("myForm2").style.display = "none";
  document.getElementById("myForm4").style.display = "none";
  document.getElementById("myForm5").style.display = "none";
  document.getElementById("myForm6").style.display = "none";
  document.getElementById("myForm7").style.display = "none";
  document.getElementById("myForm8").style.display = "none";
}
function closeForm3() {
  document.getElementById("myForm3").style.display = "none";
  document.getElementById("test-3").setAttribute( "onClick", "openForm3(); return false;" );
}
function openForm4() {
  document.getElementById("myForm4").style.display = "block";
  document.getElementById("test-4").setAttribute( "onClick", "closeForm4(); return false;" );
  document.getElementById("myForm2").style.display = "none";
  document.getElementById("myForm3").style.display = "none";
  document.getElementById("myForm5").style.display = "none";
  document.getElementById("myForm6").style.display = "none";
  document.getElementById("myForm7").style.display = "none";
  document.getElementById("myForm8").style.display = "none";
}
function closeForm4() {
  document.getElementById("myForm4").style.display = "none";
  document.getElementById("test-4").setAttribute( "onClick", "openForm4(); return false;" );
}
function openForm5() {
  document.getElementById("myForm5").style.display = "block";
  document.getElementById("test-5").setAttribute( "onClick", "closeForm5(); return false;" );
  document.getElementById("myForm2").style.display = "none";
  document.getElementById("myForm3").style.display = "none";
  document.getElementById("myForm4").style.display = "none";
  document.getElementById("myForm6").style.display = "none";
  document.getElementById("myForm7").style.display = "none";
  document.getElementById("myForm8").style.display = "none";
}
function closeForm5() {
  document.getElementById("myForm5").style.display = "none";
  document.getElementById("test-5").setAttribute( "onClick", "openForm5(); return false;" );
}
function openForm6() {
  document.getElementById("myForm6").style.display = "block";
  document.getElementById("test-6").setAttribute( "onClick", "closeForm6(); return false;" );
  document.getElementById("myForm2").style.display = "none";
  document.getElementById("myForm3").style.display = "none";
  document.getElementById("myForm4").style.display = "none";
  document.getElementById("myForm5").style.display = "none";
  document.getElementById("myForm7").style.display = "none";
  document.getElementById("myForm8").style.display = "none";
}
function closeForm6() {
  document.getElementById("myForm6").style.display = "none";
  document.getElementById("test-6").setAttribute( "onClick", "openForm6(); return false;" );
}
function openForm7() {
  document.getElementById("myForm7").style.display = "block";
  document.getElementById("test-7").setAttribute( "onClick", "closeForm7(); return false;" );
  document.getElementById("myForm2").style.display = "none";
  document.getElementById("myForm3").style.display = "none";
  document.getElementById("myForm4").style.display = "none";
  document.getElementById("myForm5").style.display = "none";
  document.getElementById("myForm6").style.display = "none";
  document.getElementById("myForm8").style.display = "none";
}
function closeForm7() {
  document.getElementById("myForm7").style.display = "none";
  document.getElementById("test-7").setAttribute( "onClick", "openForm7(); return false;" );
}

function openForm8() {
  document.getElementById("myForm8").style.display = "block";
  document.getElementById("test-8").setAttribute( "onClick", "closeForm8(); return false;" );
  document.getElementById("myForm2").style.display = "none";
  document.getElementById("myForm3").style.display = "none";
  document.getElementById("myForm4").style.display = "none";
  document.getElementById("myForm5").style.display = "none";
  document.getElementById("myForm6").style.display = "none";
  document.getElementById("myForm7").style.display = "none";
}
function closeForm8() {
  document.getElementById("myForm8").style.display = "none";
  document.getElementById("test-8").setAttribute( "onClick", "openForm8(); return false;" );
}


$(document).ready(function () {

	// Change on 2/11/2018 : Clear search filter field value of click search button - By Pravin Bhakare
	$('.search_field').val('');

	$('#fromdate').datepicker({format: "dd/mm/yyyy",orientation: "left top",autoclose: true,});
	$('#todate').datepicker({ format: "dd/mm/yyyy", orientation: "left top", autoclose: true, });


	$('#search_btn').click(function(){

		var from = $("#fromdate").val().split("/");
		var fromdate = new Date(from[2], from[1] - 1, from[0]);

		var from = $("#todate").val().split("/");
		var todate = new Date(from[2], from[1] - 1, from[0]);

		if(todate < fromdate){

			alert('Invalid Date Range Selection');
			return false;
		}
	});

});
