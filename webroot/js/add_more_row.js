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
		
		if(typeof tableFormData !== 'undefined'){
			createFormStruct(tableFormData);
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
				tableContainer += "<thead class='tablehead'>";
				
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

						//console.log(this.option);
						
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
						var inputcvfloat = (this.cvfloat == null) ? '' : this.cvfloat;
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
						
						var selectContainer = '<div><select name="'+inputName+'" id="'+inputId+'" class="form-control input-field '+inputClass+'" '+inputOnchange+' >';
						var mainSelectContainer = '<div><select name="'+inputName+'" id="'+inputId+'" class="form-control input-field '+inputClass+'" '+inputOnchange+' >';
						
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

						selectContainer += "</select></div>";
						mainSelectContainer += "</select></div>";
						
						tableContainer += 	(this.name == null) ? '<span class="serial_no">'+rowsC+'</span>' : 
											(inputType == 'textarea') ? '<div><textarea name="' + inputName + '" id="'+inputId+'" class="form-control input-field '+inputClass+'">'+inputValue+'</textarea></div>' :
											(inputType == 'hidden') ? '<input name="' + inputName + '" type="'+inputType+'" id="'+inputId+'" value="'+inputValue+'" class="form-control input-field">' :
											(inputType == 'icon') ? '<i id="'+inputId+'" class="'+inputClass+'" title="'+inputTitle+'"></i>' :
											(inputType == 'select') ? selectContainer : 
											'<div><input name="' + inputName + '" type="'+inputType+'" id="'+inputId+'" value="'+inputValue+'" class="form-control input-field '+inputClass+'" maxlength="'+inputMaxLenVal+'" max="'+inputMax+'" cvfloat="'+inputcvfloat+'" ></div>';
						tableContainer += '<div class="err_cv"></div>'+inputAutocomplete;


						mainRowContainer += (this.name == null) ? '<span class="serial_no">'+rowsC+'</span>' : 
											(inputType == 'textarea') ? '<div><textarea name="' + inputName + '" id="'+inputId+'" class="form-control input-field '+inputClass+'">'+inputValue+'</textarea></div>' :
											(inputType == 'hidden') ? '<input name="' + inputName + '" type="'+inputType+'" id="'+inputId+'" class="form-control input-field">' :
											(inputType == 'icon') ? '<i id="'+inputId+'" class="'+inputClass+'" title="'+inputTitle+'"></i>' :
											(inputType == 'select') ? mainSelectContainer : 
											'<div><input name="' + inputName + '" type="'+inputType+'" id="'+inputId+'" class="form-control input-field '+inputClass+'" maxlength="'+inputMaxLenVal+'" max="'+inputMax+'" cvfloat="'+inputcvfloat+'" ></div>';
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
		
	});
	
	
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
	
	