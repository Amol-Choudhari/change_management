$("#ho_granted_appl_list").dataTable({"order": []});

var i=0;
var limit = $("#i-value").val();

for(i=0;i < limit;i++){	

    (function(p) {
        
        $('#ho_granted_appl_list').on('click', '#renewal_esign_btn'+p, function(){//applied btn id as selector to .on method and click event on table id, to apply onlick event to every record in datatables
            
            if(confirm("Are you sure to proceed for Esign the Renewal Certificate?")){
                
                var customer_id = $("#customer_id"+p).text();
                var pdf_link = $("#pdf_link"+p).attr('href');
				var split_link = pdf_link.split('/');
				var pdf_name = split_link[4];
                
                $.ajax({
                    type: "POST",
                    async:true,
                    url:"../AjaxFunctions/set_session_for_renewal_esign",
                    data:({customer_id:customer_id,pdf_name:pdf_name}),
					beforeSend: function (xhr) { // Add this line
						xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
					},
                    success: function (data) {
						$("#declarationModal").show();
						
						var split_customer_id = customer_id.split('/');
						var grantPdfFunctionPath = '';
						if(split_customer_id[1]==1){
							grantPdfFunctionPath = '../Applicationformspdfs/grantCaCertificatePdf';
						}else if(split_customer_id[1]==2){
							grantPdfFunctionPath = '../Applicationformspdfs/grantPrintingCertificatePdf';
						}else if(split_customer_id[1]==3){
							grantPdfFunctionPath = '../Applicationformspdfs/grantLaboratoryCertificatePdf';
						}
						
						//append the preview link and hidden filed value
						$("#preview_link").attr('href',grantPdfFunctionPath);
						$("#grantPdfFunctionPath").val(grantPdfFunctionPath);
					}
                });
                
            }
        });
    })(i);
}


$(document).ready(function () {
    $('#from_dt').datepicker({

        format: "dd/mm/yyyy",
        autoclose: true
    });

    $('#to_dt').datepicker({

        format: "dd/mm/yyyy",
        autoclose: true
    });

    $('#search').click(function(){

        if($('#from_dt').val()=='' || $('#to_dt').val()==''){
            $.alert('Please Select Proper Dates');
            return false;
        }
    });

});