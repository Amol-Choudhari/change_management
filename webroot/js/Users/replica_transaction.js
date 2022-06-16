$('#packer_list_btn').click(function (e) { 

    if (masters_validation() == false) {
        e.preventDefault();
    }
    
});