/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    $(document).ready(function() {
        $('.absolute-div').mouseover(function(){
            $(this).children('div').css('position', 'absolute');
            $(this).children('div').css('background', '#00529C');
            $(this).children('div').css('padding-right', '10px');
            $(this).children('div').css('color', 'snow');
            $(this).children('div').css('border-radius', '0 10px 10px 0');
            $(this).children('div').css('box-shadow', '5px 5px 7px #111');
            $(this).children('div').css('-moz-box-shadow', '5px 5px 7px #111');
            $(this).children('div').css('-webkit-box-shadow', '5px 5px 7px #111');
            $(this).children('div').css('-webkit-border-bottom-right-radius', '15px');
            $(this).children('div').animate({width:"350px"}, 'fast', 'linear', function(){
            });
        });
        $('.absolute-div').mouseleave(function(){
            $(this).children('div').animate({width:"88px"}, 'fast', 'linear', function(){
                $(this).css('position', '');
                $(this).css('background', '');
                $(this).css('padding-right', '');
                $(this).css('color', '#000');
                $(this).css('box-shadow', '');
                $(this).css('-moz-box-shadow', '');
                $(this).css('-webkit-box-shadow', '');
                $(this).css('-webkit-border-bottom-right-radius', '');
            });
        });
    });

