$("#add_business_year_btn").click(function (){

    var value_return = 'true';

    if($("#business_years").val() == ''){

        $("#error_business_year").show().text("Please Enter value here");
        $("#error_business_year").css({"color":"red","font-size":"14px","font-weight":"500","text-align":"right"});
        $("#business_years").click(function(){$("#error_business_year").hide().text;});
        value_return = 'false';
    }

    if(value_return == 'false')
    {
        return false;
    }
    else{
        exit();
    }

});
