var progbarstatus = document.getElementById('progbarstatus').value;
var pbfinalsubmit = document.getElementById('pbfinalsubmit').value;
var current_level = document.getElementById('current_level').value;

if(pbfinalsubmit == '' || pbfinalsubmit == null){ pbfinalsubmit = null; }

var progbarvalue = (progbarstatus != '') ? JSON.parse(progbarstatus) : Array();

var tableRw = JSON.parse(JSON.stringify(progbarvalue));


    $.each(tableRw, function(index, value) {

        var section_id = value[0];
        var sectionvalue = value[1];
        var final_submit;

		$('#section'+section_id).removeClass();
		$('#span'+section_id).removeClass();

       if (current_level== 'level_2') {

                if (pbfinalsubmit != null) {
                      final_submit = pbfinalsubmit['status'];
                 }

		if (sectionvalue=='') {

		  $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-red');
		  $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');

        } else if (sectionvalue=='saved') {

		  $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
		  $('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');

		} else if (sectionvalue=='referred_back' && final_submit == 'referred_back') {

			$('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');

        } else if (sectionvalue=='referred_back' && final_submit != 'referred_back') {

			$('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');

       } else if (sectionvalue=='approved') {

			$('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');
		 }
	 }

     //Current Level 2
	 if (current_level!= 'level_2') {

		 if (sectionvalue=='saved' && pbfinalsubmit['status'] == 'pending') {

			$('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-red');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');

         } else if (sectionvalue=='saved' && sectionvalue[3]!='') {

            $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');

         } else if (sectionvalue=='referred_back' && pbfinalsubmit['status'] == 'pending') {

            $('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');

         } else if (sectionvalue=='referred_back' && pbfinalsubmit['status'] == 'referred_back') {

			$('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');

		 } else if (sectionvalue=='referred_back' && pbfinalsubmit['status'] == 'replied') {

			$('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');

		 } else if (sectionvalue=='replied' && pbfinalsubmit['status'] != 'referred_back') {

			$('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');

		 } else if (sectionvalue=='replied' && pbfinalsubmit['status'] == 'referred_back') {

			$('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-warning');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');

		 } else if (sectionvalue=='approved') {

			$('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-success');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-ok-sign');

		 } else if (sectionvalue=='saved' && sectionvalue[3]=='') {

			$('#section'+section_id).addClass('progress_bar d-inline p-1 pl-3 pr-3 mr-1').addClass('bg-red');
			$('#span'+section_id).addClass('glyphicon').addClass('glyphicon-remove-sign');
		 }
	 }
 });
