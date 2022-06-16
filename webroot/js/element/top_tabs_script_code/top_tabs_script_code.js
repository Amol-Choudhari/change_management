
		$(document).ready(function(){

			$('ul.tabs li').click(function(){
				var tab_id = $(this).attr('data-tab');

				$('.allocation-page').hide();
				$('.inspection').hide();

				$('ul.tabs li').removeClass('current');
				$('.tab-content').removeClass('current');

				$(this).addClass('current');
				$("#"+tab_id).addClass('current');

				//$('.table-format td').addClass('display_none');
			})


			$('ul.tabs li').addClass('current');

		})
