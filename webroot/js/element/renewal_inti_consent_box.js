$("#okBtn_wo_esign").prop("disabled", true);

var final_submit_btn = document.getElementById("final_submit_btn");
var span = document.getElementsByClassName("close")[0];

final_submit_btn.onclick = function(e) {
    e.preventDefault();
    declarationModal_wo_esign.style.display = "block";
    return false;
}

$(".close").click(function() {
    $(".modal").hide();
    return false;
});

//for final submit without esign, added on 04-05-2018 by Amol
$("#declaration_check_box_wo_esign").change(function() {

    $("#okBtn_wo_esign").prop("disabled", false);

    if ($(this).prop('checked') == false) {

        $("#okBtn_wo_esign").prop("disabled", true);
    }

});
