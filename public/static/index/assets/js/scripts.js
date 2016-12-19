
jQuery(document).ready(function() {

    $('.page-container form').submit(function(){
        var username = $(this).find('.username').val();
        var email = $(this).find('email').val();
        var password = $(this).find('.password').val();
        var verify = $(this).find('.verify').val();
        
        if(username == '') {
            $(this).find('.error').fadeOut('fast', function(){
                $(this).css('top', '27px');
            });
            $(this).find('.error').fadeIn('fast', function(){
                $(this).parent().find('.username').focus();
            });
            return false;
        }
        if(email == '') {
            $(this).find('.error').fadeOut('fast', function(){
                $(this).css('top', '96px');
            });
            $(this).find('.error').fadeIn('fast', function(){
                $(this).parent().find('.email').focus();
            });
            return false;
        }
        if(password == '') {
            $(this).find('.error').fadeOut('fast', function(){
                $(this).css('top', '165px');
            });
            $(this).find('.error').fadeIn('fast', function(){
                $(this).parent().find('.password').focus();
            });
            return false;
        }
        if(verify == '') {
            $(this).find('.error').fadeOut('fast', function(){
                $(this).css('top', '235px');
            });
            $(this).find('.error').fadeIn('fast', function(){
                $(this).parent().find('.verify').focus();
            });
            return false;
        }
    });

    $('.page-container form .username, .page-container form .email, .page-container form .password, .page-container form .verify').keyup(function(){
        $(this).parent().find('.error').fadeOut('fast');
    });

});
