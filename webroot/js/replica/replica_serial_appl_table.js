
/**
 * DYNAMIC FORM CREATION BY USING ARRAYS
 */

$(document).ready(function(){

	$(document).on('click', '#table_1 #add_more', function() {
    var tblId = $(this).closest('table').attr('id');
    var tblIdArr = tblId.split('_');
    var tblIdNum = tblIdArr[1];
    addMoreRow(tblIdNum);
	});

	$(document).on('click', '#table_1 .remove_btn_btn', function() {
		var tblId = $(this).closest('table').attr('id');
		var tblIdArr = tblId.split('_');
		var tblIdNum = tblIdArr[1];
		var trId = $(this).closest('tr').attr('id');
		remRow(trId, tblIdNum);
	});
	
	//var tableFormData = $('#tableFormData').val();
	var tableFormD = document.getElementById('tableFormData').value;
	var tableFormData = (tableFormD != '') ? JSON.parse(tableFormD) : Array();

	createFormStruct(tableFormData);

	$(".readonly").attr('readonly',true);
	$("#save").prop('disabled',true);
	get_overall_total_and_min_bal(); //to get overall total charges on edit mode, when form already saved

	//to get replica charge from db
	$('#table_1').on('change','.commodity',function(){

		var id_No = this.id.split("-");//to get dynamic id of element for each row, and split to get no.
		id_No = id_No[2];

		var commodity_id = $("#ta-commodity-"+id_No).val();

		$.ajax({
			type: "POST",
			url: "../replica/get_commodity_wise_charge",
			data: {commodity_id:commodity_id},
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){

				var response = response.match(/~([^']+)~/)[1];//getting data bitween ~..~ from response

				if(response == 'No Charge'){
					alert("No Charges available for selected commodity");
					$("#ta-label_charge-"+id_No).val('');
					$("#ta-packet_size_unit-"+id_No).html('');
					$("#ta-total_quantity-"+id_No).val('');
					return false;

				}else{

					response = JSON.parse(response);//response is JSOn encoded to parse JSON

					$("#ta-label_charge-"+id_No).val(response['charge']);

					var unit_list = response['unit_list'];

					var unit_option = "<option value=''>--Select--</option>";
					$.each(unit_list, function(index, value){

						unit_option += "<option value='"+index+"'>"+value+"</option>";
					});

					$("#ta-packet_size_unit-"+id_No).html(unit_option);
					$("#ta-total_quantity-"+id_No).val(response['min_qty']);
				}

			}
		});
		
		
	  //***************************************************************************************** */
      // This ajax used for gre grade for selected commodity 
	  // Added by shankhpal shende on 22/08/2022
		$.ajax({
			type: "POST",
			url: "../replica/get_commodity_wise_grade",
			data: {commodity_id:commodity_id},
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){
                
				var response = response.match(/~([^']+)~/)[1];//getting data bitween ~..~ from response
                
				if(response == 'No Grade'){
					
					$.alert("No Grade available for selected Commodity");
					var grade_option = "<option value=''>--Select--</option>";
					$("#ta-grade-"+id_No).val('');
					$("#ta-grade-"+id_No).html(grade_option);
					
					
					return false;

				}else{

					response = JSON.parse(response);//response is JSOn encoded to parse JSON
                   
					var grade_list = response['Grade'];
                   
					var grade_option = "<option value=''>--Select--</option>";
					
					$.each(grade_list, function(index, value){
						
						grade_option += "<option value='"+index+"'>"+value+"</option>";
						
					});

					$("#ta-grade-"+id_No).html(grade_option);
				
				}

			}
		});
		
		//if commodity changed, reset the packet size, no of packets and total label charge fields
		$("#ta-packet_size-"+id_No).val('');
		$("#ta-no_of_packets-"+id_No).val('');
		$("#ta-total_label_charges-"+id_No).val('');
		$("#save").prop('disabled',true);

	});

	//to get gross quantity and total charges
	$('#table_1').on('focusout','.no_of_packets',function(){

		var id_No = this.id.split("-");//to get dynamic id of element for each row, and split to get no.
		id_No = id_No[2];

		var packet_size = $("#ta-packet_size-"+id_No).val();
		var sub_unit_id = $("#ta-packet_size_unit-"+id_No).val();
		var no_of_packets = $("#ta-no_of_packets-"+id_No).val();
		var label_charge = $("#ta-label_charge-"+id_No).val();
		var commodity_id = $("#ta-commodity-"+id_No).val();

		$.ajax({
			type: "POST",
			url: "../replica/get_gross_quantity_and_total_charge",
			data: {packet_size:packet_size,sub_unit_id:sub_unit_id,no_of_packets:no_of_packets,label_charge:label_charge,commodity_id:commodity_id},
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){

				var response = response.match(/~([^']+)~/)[1];//getting data bitween ~..~ from response
				response = JSON.parse(response);//response is JSOn encoded to parse JSON

				$("#ta-total_quantity-"+id_No).val(response['gross_quantity']);
				$("#ta-total_label_charges-"+id_No).val(response['total_charges']);

				get_overall_total_and_min_bal();
			}
		});

	});

	//to get selected printer details
	$('#table_1').on('click','.view_printer',function(){

		var id_No = this.id.split("-");//to get dynamic id of element for each row, and split to get no.
		id_No = id_No[2];

		var printer_id = $("#ta-authorized_printer-"+id_No).val();

		if(printer_id==''){
			alert('Please select the printer from the list.');
			return false;
		}

		$.ajax({
			type: "POST",
			url: "../replica/get_printer_details",
			data: {printer_id:printer_id},
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){

				var response = response.match(/~([^']+)~/)[1];//getting data bitween ~..~ from response

				$("#printer_detail_popup").show();
				$("#printer_detail_content").html(response);

			}
		});


	});


	//below two scripts are added to recall above calculation ajax calls, if packet size or unit changed
	$('#table_1').on('change','.packet_size',function(){

		var id_No = this.id.split("-");//to get dynamic id of element for each row, and split to get no.
		id_No = id_No[2];

		$("#ta-no_of_packets-"+id_No).val('');
		//$("#ta-total_quantity-"+id_No).val('');
		$("#ta-total_label_charges-"+id_No).val('');
		$("#save").prop('disabled',true);
	});

	$('#table_1').on('change','.packet_size_unit',function(){

		var id_No = this.id.split("-");//to get dynamic id of element for each row, and split to get no.
		id_No = id_No[2];

		$("#ta-no_of_packets-"+id_No).val('');
		//$("#ta-total_quantity-"+id_No).val('');
		$("#ta-total_label_charges-"+id_No).val('');
		$("#save").prop('disabled',true);
	});



});


	//to get and check overall total charges is not greater than balance amount
	function get_overall_total_and_min_bal(){

	//	var id_No = this.id.split("-");//to get dynamic id of element for each row, and split to get no.
	//	id_No = id_No[2];

		var i = 1;
		var overall_total = 0;
		$('#table_1 > .table_body  > tr').each(function() {

			var total_label_charges = $("#ta-total_label_charges-"+i).val();

			overall_total = parseFloat(overall_total) + parseFloat(total_label_charges);

			i++;
		});



		$.ajax({
			type: "POST",
			url: "../replica/check_bal_amt",
			beforeSend: function (xhr) { // Add this line
					xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
			},
			success: function(response){

				var response = response.match(/~([^']+)~/)[1];//getting data bitween ~..~ from response
				response = JSON.parse(response);//response is JSOn encoded to parse JSON

				var bal_amt = response;

				$("#overall_total_chrg").val(overall_total);

				if(bal_amt < overall_total){

					$("#bal_amt_exceeds_msg").html('Total exceeds balance amount').css("color", "Red");
					$("#save").prop('disabled',true);
					$("#add_more").prop('disabled',true);

				}else{
					$("#bal_amt_exceeds_msg").html('');
					$("#save").prop('disabled',false);
					$("#add_more").prop('disabled',false);
				}

				if(isNaN(overall_total) || overall_total==0 || overall_total==''){

					$("#save").prop('disabled',true);
				}

			}
		});


	}


function createFormStruct(tableFormArr){

	var tableRw = JSON.parse(JSON.stringify(tableFormArr));

	var tRw = 0;
	$.each(tableRw, function(index, value){

		var tabRw = tRw + parseInt(1);
		var tableForm = $('#table_container_'+tabRw);


		var tableContainer = "";
		// var mainRowContainer = "";
		tableContainer += '<input type="hidden" name="row_count_'+tabRw+'" id="row-count-'+tabRw+'" value="1">';
		tableContainer += "<table id='table_"+tabRw+"' class='table table-bordered table_form'>";
		tableContainer += "<thead class='table-light table_head'>";

		var tableFormHead = $('.table_form .table_head');
		var tableArr = JSON.parse(JSON.stringify(tableFormArr[tRw]));

		$.each(tableArr.label, function(index, value){

			tableContainer += "<tr>";

			$.each(this, function(index, value){
				tableContainer += "<th";
				tableContainer += (this.colspan > 1) ? " colspan='" + this.colspan + "'" : "";
				tableContainer += (this.rowspan > 1) ? " rowspan='" + this.rowspan + "'>" : ">";
				tableContainer += this.col;
				tableContainer += "</th>";
			});

			tableContainer += "<th>";
			tableContainer += "</th>";
			tableContainer += "</tr>";
		});


		tableContainer += "</thead>";

		tableContainer += "<tbody class='table_body'>";

		var rowsC = 1;

		//

		$.each(tableArr.input, function(index, value){

			tableContainer += "<tr id='row_container-"+rowsC+"'>";

			var tableFormBody = $('.table_form .table_body');
			var mainRowContainer = "";

			$.each(this, function(index, value){

				console.log(this.option);

				var inputName = this.name + "[]";
				var inputId = "ta-" + this.name + "-"+rowsC;
				var inputType = this.type;
				var inputValid = this.valid;
				var inputLength = this.length;
				var inputOptions = this.option;
				var inputOptActive = this.selected;
				var inputTitle = this.title;
				// var inputMaxLenTxt = (this.maxlength == null) ? '' : '"maxlength"=>"';
				// var inputMaxLenVal = (this.maxlength == null) ? '' : this.maxlength + '",';
				var inputMaxLenVal = (this.maxlength == null) ? '' : this.maxlength;
				var inputOnchange = (this.onchange == null) ? '' : 'onchange=\''+this.onchange+'\'';
				var inputClass = (this.class == null) ? "" : this.class;
				var inputClassArr = inputClass.split(" ");
				var inputAutocomplete = "";
				var mainInputAutocomplete = "";
				if($.inArray("nameOne", inputClassArr) !== -1){
					inputAutocomplete += "<div id='ta-suggestion_box-"+rowsC+"' class='sugg-box autocomp'></div>";
					mainInputAutocomplete += "<div id='ta-suggestion_box-"+rowsC+"' class='sugg-box autocomp'></div>";
				}

				var inputValue = (this.value == null) ? "" : this.value;
				var inputMax = (this.max == null) ? "" : this.max;

				tableContainer += (inputType == 'hidden') ? "" : "<td>";
				mainRowContainer += (inputType == 'hidden') ? "" : "<td>";

				var selectContainer = '<select name="'+inputName+'" id="'+inputId+'" class="form-control input-field '+inputClass+'" '+inputOnchange+' required>';
				var mainSelectContainer = '<select name="'+inputName+'" id="'+inputId+'" class="form-control input-field '+inputClass+'" '+inputOnchange+' required>';

				if(inputOptions){
					selectContainer += "<option value='' >--Select--</option>";
					mainSelectContainer += "<option value='' >--Select--</option>";
					$.each(inputOptions, function(){



						var optionValue = this.vall;
						var optionLabel = this.label;
						var optActive = (inputOptActive == optionValue) ? "selected" : "";
						selectContainer += "<option value='"+optionValue+"' "+optActive+" >"+optionLabel+"</option>";
						mainSelectContainer += "<option value='"+optionValue+"' >"+optionLabel+"</option>";

					});

				}

				selectContainer += "</select>";
				mainSelectContainer += "</select>";

				tableContainer += 	(this.name == null) ? '<span class="serial_no">'+rowsC+'</span>' :
									(inputType == 'textarea') ? '<textarea name="' + inputName + '" id="'+inputId+'" value="'+inputValue+'" class=>"form-control input-field '+inputClass+'">' :
									(inputType == 'hidden') ? '<input name="' + inputName + '" type="'+inputType+'" id="'+inputId+'" value="'+inputValue+'" class="form-control input-field">' :
									(inputType == 'icon') ? '<i id="'+inputId+'" class="'+inputClass+'" title="'+inputTitle+'"></i>' :
									(inputType == 'select') ? selectContainer :
									'<input name="' + inputName + '" type="'+inputType+'" id="'+inputId+'" value="'+inputValue+'" class="form-control input-field '+inputClass+'" maxlength="'+inputMaxLenVal+'" max="'+inputMax+'" required>';
				tableContainer += '<div class="err_cv"></div>'+inputAutocomplete;


				mainRowContainer += (this.name == null) ? '<span class="serial_no">'+rowsC+'</span>' :
									(inputType == 'textarea') ? '<textarea name="' + inputName + '" id="'+inputId+'" class="form-control input-field '+inputClass+'">' :
									(inputType == 'hidden') ? '<input name="' + inputName + '" type="'+inputType+'" id="'+inputId+'" class="form-control input-field">' :
									(inputType == 'icon') ? '<i id="'+inputId+'" class="'+inputClass+'" title="'+inputTitle+'"></i>' :
									(inputType == 'select') ? mainSelectContainer :
									'<input name="' + inputName + '" type="'+inputType+'" id="'+inputId+'" class="form-control input-field '+inputClass+'" maxlength="'+inputMaxLenVal+'" max="'+inputMax+'" required>';
				mainRowContainer += '<div class="err_cv"></div>'+mainInputAutocomplete;

				tableContainer	+= (inputType == 'hidden') ? "" :  "</td>";
				mainRowContainer	+= (inputType == 'hidden') ? "" :  "</td>";
			});

			var rowId = "row_container-1";
			tableContainer += "<td class='remove_btn'>";
			tableContainer += "<button type='button' class='btn btn-sm btn-danger remove_btn_btn' onclick=\"remRow(\'row_container-"+rowsC+"\',"+tabRw+")\"><i class='fa fa-times'></i></button>";
			tableContainer += "</td>";
			mainRowContainer += "<td class='remove_btn'>";
			mainRowContainer += "</td>";
			tableContainer += "</tr>";
			$('#main_row').val(mainRowContainer);

			tableForm.after('<input type="hidden" id="main_row_'+tabRw+'">');
			$('#main_row_'+tabRw).val(mainRowContainer);

			rowsC++;

		});



		var colsCount = 0;
		$.each(tableArr.label[0], function(){
			colsCount += parseInt(this.colspan);
		});
		colsCount ++;

		tableContainer += "</tbody>";
		tableContainer += "<tbody>";
		tableContainer += "<tr id='addmorebtn'>";
		tableContainer += "<td colspan='"+colsCount+"'>";
		tableContainer += "<div class='form-buttons text-right'><button type='button' id='add_more' onclick='addMoreRow("+tabRw+")' class='btn btn-info btn-sm'><i class='fa fa-plus'></i> Add more</button></div>";
		tableContainer += "</td>";
		tableContainer += "</tr>";
		tableContainer += "</tbody>";
		tableContainer += "</table>";

		tableForm.append(tableContainer);

		checkDirectorDetRem(tabRw);

		tRw++;

	});

}

function addMoreRow(tabRw){

	var currentRow = $('#table_'+tabRw+' .table_body tr:last').attr('id');
	var extractId = currentRow.split('-');
	var incRow = parseInt(extractId[1]) + parseInt(1);
	var valueBlank = "";

	var rowContainer = "<tr id='row_container-"+incRow+"'>";
	// rowContainer +=$('#row_container-1').html();
	rowContainer +=$('#main_row_'+tabRw).val();
	rowContainer = rowContainer.replace(/name\=\"(.*?)\-(.*?)\-(.*?)\"/g, 'name\=\"$1\-$2\-'+incRow+'\"');
	rowContainer = rowContainer.replace(/id\=\"(.*?)\-(.*?)\-(.*?)\"/g, 'id\=\"$1\-$2\-'+incRow+'\"');
	rowContainer = rowContainer.replace(/value\=\"(.*?)\"/g, 'value\=\"'+valueBlank+'\"');
	rowContainer = rowContainer.replace(/class\=\"serial_no\"\>(.*?)\</g, 'class\=\"serial_no\"\>'+incRow+'\<');
	rowContainer = rowContainer.replace(/class\=\'remove_btn\'\>(.*?)\</g, 'class\=\"remove_btn\"\>\<button type\=\"button\" class\=\"btn btn\-sm btn\-danger remove\_btn\_btn\" onclick\=\"remRow\(\'row_container\-'+incRow+'\'\,'+tabRw+')\"\><i class\=\"fa fa\-times\"\>\<\/\i\>\<\/button\>\<');
	rowContainer += "</tr>";

	$('#table_'+tabRw).append(rowContainer);
	updateSerialNo(tabRw);
	checkDirectorDetRem(tabRw);

	$("input").attr("autocomplete","off");

	$(".readonly").attr('readonly',true);

}

function checkDirectorDetRem(tabRw){

	var totalRows = $('#table_'+tabRw+' .table_body tr').length;
	if(totalRows == 2){
		$('#table_'+tabRw+' .table_body tr:first button').removeAttr('disabled');
	} else if(totalRows == 1) {
		$('#table_'+tabRw+' .table_body tr:first button').attr('disabled','true');
	}

}

function remRow(parentId, tId){

	$('#table_'+tId+' #' + parentId).remove();
	updateSerialNo(tId);
	checkDirectorDetRem(tId);

	get_overall_total_and_min_bal();//when any row remove, re calculate the total replica charges

}

function updateSerialNo(tId){

	var rowsCount = $('#table_'+tId+' .table_body tr').length;
	var i;
	for(i = 0; i < rowsCount; i++){
		var incI = i + parseInt(1);
		var thisAtt = $('#table_'+tId+' .table_body tr td .serial_no').eq(i).text(incI);
	}
	$('#row-count-'+tId).val(rowsCount);

}


