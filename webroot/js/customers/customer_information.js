//file added by laxmi B. on 21/11/2022
$(document).ready(function(){

    $(document).on('click', 'input[type="radio"]', function() {      
    $('input[type="radio"]').not(this).prop('checked', false); 
    document.getElementById("search_text").value = "";  
     $('#customer_data').hide();
    
   
});

$(function() {
$("#search_btn").click(function() {
    
        
       
var name = $('input[type="radio"]:checked').attr('name');
 var id = $('#search_text').val();
 console.log(name);
var save = $('#search_btn').val();
if((id == "" || id == null) && (name == null  || name == "undefined")){
     $("#error_msg").show();
    $("#error_msg").text("Please select any option and enter value");
    $('input[type="radio"]').click(function(){
         $("#error_msg").hide();

    });
     $('#search_text').click(function(){
         $("#error_msg").hide();
          
    });
  return false;  
} else if(name == "undefined" || name == null ){
     $("#error_msg").show();
   $("#error_msg").text("Please select any option");
   $('input[type="radio"]').click(function(){
         $("#error_msg").hide();
       
    });
  return false;  
}else if(name == "firm" && id == ""){
     $("#error_msg").show();
   $("#error_msg").text("Please Enter Customer Id");
   $('#search_text').click(function(){
         $("#error_msg").hide();
        
    });
  return false;
}else if(name == "replica" &&  id == ""){
     $("#error_msg").show();
  $("#error_msg").text("Please Enter Replica No.");
  $('#search_text').click(function(){
         $("#error_msg").hide();
         
    });
  return false;
  }else if(name == "code15Digit" &&  id == ""){
     $("#error_msg").show();
  $("#error_msg").text("Please Enter Code15Digit No.");
  $('#search_text').click(function(){
         $("#error_msg").hide();
        
    });
  return false;
  } else if(name == "ecode" &&  id == ""){
     $("#error_msg").show();
   $("#error_msg").text("Please Enter Ecode No.");
   $('#search_text').click(function(){
         $("#error_msg").hide();
        
    });
  return false;
} else {

 //for redirect to controller
if(name == "firm"){   
$.ajax({
  type: "POST",
  url: "../customers/customer_information", 
  data:{name: name , id : id, save : save},
 cache: false,
 beforeSend: function (xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },

 success: function (result) {

  //alert('customer record fetching...');
  
   $('#customer_data').html(result);
     $('#customer_data').show();
    return result;
},
error: function () {
    alert('Something went wrong');
}
});
} else if(name === "replica"){
   
$.ajax({
  type: "POST",
  url: "../replica/search_replica", 
  data:{ rep_ser_no : id},
 cache: false,
 beforeSend: function (xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },

 success: function (msg) {

  //alert('customer record fetching...');
  
   $('#customer_data').html(msg);
      $('#customer_data').show();
    return msg;
},
error: function () {
    alert('Something went wrong');
}
});
} else if(name === "code15Digit"){
    
$.ajax({
  type: "POST",
  url: "../code15digit/search_replica", 
  data:{ rep_ser_no : id},
 cache: false,
 beforeSend: function (xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },

 success: function (msg) {

  //alert('customer record fetching...');
  
   $('#customer_data').html(msg);
      $('#customer_data').show();
    return msg;
},
error: function () {
    alert('Something went wrong');
}
});
} else{
   
    $.ajax({
  type: "POST",
  url: "../ecode/search_replica", 
  data:{ rep_ser_no : id},
 cache: false,
 beforeSend: function (xhr){
            xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
        },

 success: function (msg) {

  //alert('customer record fetching...');
  
   $('#customer_data').html(msg);
    $('#customer_data').show();
    return msg;
},
error: function () {
    alert('Something went wrong');
}
});
}


return false;
}
});
});



});