$(function(){
    $('.form-collapse').click(function(e){
        if($(this).hasClass('expand')){
            $(this).next('.collapsible').slideDown('normal',toggleExpand($(this), false));
        }else{
            $(this).next('.collapsible').slideUp('normal',toggleExpand($(this), true));
        }
    });
    
    function toggleExpand($elem, tf){
        if(tf){
            $elem.css('background-position', '98% 0px');
            $elem.addClass('expand');
        }else{
            $elem.css('background-position', '98% -16px');
            $elem.removeClass('expand');
        }
    }
})
