$("#request_list").dataTable();//to display list as it is in result array order

var i=1;
var limit_id = $('#id_for_tranfer').val();
var limit = limit_id;

for(i=1;i < limit;i++){

    (function(p) {

        $('#request_list').on('click', '#action_btn'+p, function(){//applied btn id as selector to .on method and click event on table id, to apply onlick event to every record in datatables


            $.confirm({
			
                icon: 'fas fa-info-circle',
                content: 'Are you sure for the action taken ?',
                columnClass: 'col-md-6 col-md-offset-3',
                buttons: {
    
                    confirm: { 
    
                        btnClass: 'btn-green',
                        action: function () {

                            var req_by_user = $("#req_by_user"+p).text();
                            var req_for_user = $("#req_for_user"+p).text();
            
                            $.ajax({
                                type: "POST",
                                async:true,
                                url:"../othermodules/ho_permitted_for_transfer",
                                data:({req_by_user:req_by_user,req_for_user:req_for_user}),
                                beforeSend: function (xhr) {
                                    $("#work_transfer_loader").show();
                                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                                },
                                success: function (data) {

                                    $("#work_transfer_loader").hide();
            
                                    data = data.match(/~([^']+)~/)[1]; //fetching value between ~~ string
                                    if(data == 'done'){

                                        $.alert({

                                            title: 'Alert!',
                                            columnClass: 'medium',
                                            content: 'You have successfully given permission to transfer work for user id "' + req_for_user + '"',
                                            buttons: {
                                                Okay: { 
                                                    btnClass: 'btn-blue',
                                                    action: function () {
                                            
                                                        window.location = '';
                                                    }
                                                },
                                            }
                                        });
    
                                    }else{
                                        $.alert('Sorry... Please try again');
                                        return false;
                                    }
                                }
                            });
                        
                        }
                    },
                    
                    cancel:{
                        
                        btnClass: 'btn-red',
                        action: function () {}
                    },
                }
            });
        });
    })(i);
}


for(i=1;i < limit;i++){

    (function(p) {

        $('#request_list').on('click', '#reject_btn'+p, function(){//applied btn id as selector to .on method and click event on table id, to apply onlick event to every record in datatables


            $.confirm({
			
                icon: 'fas fa-info-circle',
                content: 'Are you sure for the action taken ?',
                columnClass: 'col-md-6 col-md-offset-3',
                buttons: {
    
                    confirm: { 
    
                        btnClass: 'btn-green',
                        action: function () {

                            var req_by_user = $("#req_by_user"+p).text();
                            var req_for_user = $("#req_for_user"+p).text();
            
                            $.ajax({

                                type: "POST",
                                async:true,
                                url:"../othermodules/ho_rejected_for_transfer",
                                data:({req_by_user:req_by_user,req_for_user:req_for_user}),
                                beforeSend: function (xhr) {
                                    $("#work_transfer_loader").show();
                                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
            
                                },
                                success: function (data) {

                                    $("#work_transfer_loader").hide();
            
                                    data = data.match(/~([^']+)~/)[1]; //fetching value between ~~ string
                                    if(data == 'done'){

                                        $.alert({

                                            title: 'Alert!',
                                            columnClass: 'medium',
                                            content: 'You have Rejected the Request to Transfer work for user id "' + req_for_user + '"',
                                            buttons: {
                                                Okay: { 
                                                    btnClass: 'btn-blue',
                                                    action: function () {
                                            
                                                        window.location = '';
                                                    }
                                                },
                                            }
                                        });

                                    }else{
                                        $.alert('Sorry... Please try again');
                                        return false;
                                    }
                                }
                            });
              
                        }
                    },
                    
                    cancel:{
                        
                        btnClass: 'btn-red',
                        action: function () {}
                    },
                }
            });
        });
    })(i);
}
