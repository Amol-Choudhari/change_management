
function openCity(evt, cityName) {

  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

$("#self_registered_chemist_tab").click(function(){
	
	$("#self_registered_chemist").show();
	$("#lab_registered_chemist").hide();
	$("#allocated_chemist").hide();
	
});

$("#lab_registered_chemist_tab").click(function(){
	
	$("#self_registered_chemist").hide();
	$("#lab_registered_chemist").show();
	$("#allocated_chemist").hide();
	
});

$("#allocated_chemist_tab").click(function(){
	
	$("#self_registered_chemist").hide();
	$("#lab_registered_chemist").hide();
	$("#allocated_chemist").show();
	
});

document.getElementById('self_registered_chemist').style.display = "block";
