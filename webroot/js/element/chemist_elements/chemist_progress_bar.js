$(document).ready(function(){
		
        var profile_status	=	$("#profile_status").val();
        var education_status	=	$("#education_status").val();
        var experience_status	=	$("#experience_status").val();
        var training_status	=	$("#training_status").val();
        var other_details_status	=	$("#other_details_status").val();
        var application_dashboard	=	$("#application_dashboard").val();
        
        
        //for firm profile form	
            if(application_dashboard == 'ro' && profile_status=="saved"){
                
                $('#profile').removeClass('bg-success').addClass('bg-red');
                $('#profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }else if(profile_status=="saved" || profile_status=="replied" || profile_status=="approved" ){

                $('#profile').removeClass('bg-red').addClass('bg-success');
                $('#profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
            }
            
            else if(profile_status==""){

                $('#profile').removeClass('bg-success').addClass('bg-red');
                $('#profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }
            else if(profile_status=="referred_back"){

                $('#profile').removeClass('bg-success');
                $('#profile').removeClass('bg-red');
                $('#profile').addClass('bg-warning');
                $('#profile_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }
            else if(profile_status=="approved"){
                
                $('#profile').removeClass('bg-red').addClass('bg-success');
                $('#profile_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            
            
            
            
            
            
            
            
            
            
            
        //for premises profile form	
            if(application_dashboard == 'ro' && education_status=="saved"){
                
                $('#education').removeClass('bg-success').addClass('bg-red');
                $('#education_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }else if(education_status=="saved" || education_status=="replied" || education_status == "approved"){

                $('#education').removeClass('bg-red').addClass('bg-success');
                $('#education_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                
            }
            else if(education_status==""){

                $('#education').removeClass('bg-success').addClass('bg-red');
                $('#education_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }
            else if(education_status=="referred_back"){

                $('#education').removeClass('bg-success');
                $('#education').removeClass('bg-red');
                $('#education').addClass('bg-warning');
                $('#education_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }
            else if(education_status=="approved"){
                
                $('#education').removeClass('bg-red').addClass('bg-success');
                $('#education_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            
            
            
            
            
            
            
            
            
            
            
            
        //for printing unit details form	
            if(application_dashboard == 'ro' && experience_status=="saved"){
                
                $('#experience').removeClass('bg-success').addClass('bg-red');
                $('#experience_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }else if(experience_status=="saved" || experience_status=="replied" || experience_status == "approved"){

                $('#experience').removeClass('bg-red').addClass('bg-success');
                $('#experience_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                
            }
            else if(experience_status==""){

                $('#experience').removeClass('bg-success').addClass('bg-red');
                $('#experience_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }
            else if(experience_status=="referred_back"){

                $('#experience').removeClass('bg-success');
                $('#experience').removeClass('bg-red');
                $('#experience').addClass('bg-warning');
                $('#experience_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }
            else if(experience_status=="approved"){
                
                $('#experience').removeClass('bg-red').addClass('bg-success');
                $('#experience_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            
            
            
            
            
            
            
        //for payment details form	
            if(application_dashboard == 'ro' && training_status=="saved"){
                
                $('#training').removeClass('bg-success').addClass('bg-red');
                $('#training_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }else if(training_status=="saved" || training_status=="replied" || training_status == "approved"){

                $('#training').removeClass('bg-red').addClass('bg-success');
                $('#training_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                
            }
            else if(training_status==""){

                $('#training').removeClass('bg-success').addClass('bg-red');
                $('#training_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }
            else if(training_status=="referred_back"){

                $('#training').removeClass('bg-success');
                $('#training').removeClass('bg-red');
                $('#training').addClass('bg-warning');
                $('#training_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }
            else if(training_status=="approved"){
                
                $('#training').removeClass('bg-red').addClass('bg-success');
                $('#training_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            
            
            //for payment details form	
            if(application_dashboard == 'ro' && other_details_status=="saved"){
                
                $('#other_details').removeClass('bg-success').addClass('bg-red');
                $('#other_details_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }else if(other_details_status=="saved" || other_details_status=="replied" || other_details_status == "approved"){

                $('#other_details').removeClass('bg-red').addClass('bg-success');
                $('#other_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');
                
            }
            else if(other_details_status==""){

                $('#other_details').removeClass('bg-success').addClass('bg-red');
                $('#other_details_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }
            else if(other_details_status=="referred_back"){

                $('#other_details').removeClass('bg-success');
                $('#other_details').removeClass('bg-red');
                $('#other_details').addClass('bg-warning');
                $('#other_details_span').removeClass('glyphicon-ok-sign').addClass('glyphicon-remove-sign');
                
            }
            else if(other_details_status=="approved"){
                
                $('#other_details').removeClass('bg-red').addClass('bg-success');
                $('#other_details_span').removeClass('glyphicon-remove-sign').addClass('glyphicon-ok-sign');

            }
            
            
                            
});
    