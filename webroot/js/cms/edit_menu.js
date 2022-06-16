
$('#update_btn').click(function (e) { 

    if (add_edit_menus_validation() == false) {
        e.preventDefault();
    } else {
        $('#edit_menu').submit();
    }
});

$(document).ready(function(){
		
		
		//max height to page list
		$('#link_id').click(function(){
			//$('#link_id').attr('size','5');
		
		});
	
		
		$('#external_link_field').hide();
		
		//for already checked
		
			if($('#link_type-external').is(":checked")){		
		
				$("#external_link_field").show();
				$("#pages_list_field").hide();
									
			}else if($('#link_type-page').is(":checked")){

				$("#pages_list_field").show();
				$("#external_link_field").hide();
				
			}
					
				
				
		//for on clicked
		
		$('#link_type-external').click(function(){		

			$("#external_link_field").show();
			$("#pages_list_field").hide();
												
		});
		
		$('#link_type-page').click(function(){	
		
			$("#pages_list_field").show();
			$("#external_link_field").hide();
												
		});
		
		
		
		
		
		
		$("#side_menu_list").hide();
		$("#bottom_menu_list").hide();
		
		//for already checked
		
			if($('#position-side').is(":checked")){		
		
				$("#side_menu_list").show();
				$("#bottom_menu_list").hide();
				$("#current_menu_heading").html("<div class='card-header'><label>Current Sidemenu List</label></div>");
									
			}else if($('#position-bottom').is(":checked")){

				$("#bottom_menu_list").show();
				$("#side_menu_list").hide();
				$("#current_menu_heading").html("<div class='card-header'><label>Current Bottommenu List</label></div>");
			}
					
				
				
		//for on clicked
		
		$('#position-side').click(function(){		

			$("#side_menu_list").show();
			$("#bottom_menu_list").hide(); 
			$("#current_menu_heading").html("<div class='card-header'><label>Current Sidemenu List</label></div>");
		});
		
		$('#position-bottom').click(function(){		

			$("#bottom_menu_list").show();
			$("#side_menu_list").hide();
			$("#current_menu_heading").html("<div class='card-header'><label>Current Bottommenu List</label></div>");					
		});
	
	
	});
