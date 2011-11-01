$(function(){


    $("textarea").autoGrow();

    $("#fileupload").customFileInput();

// fancybox
    $(".fancyImage").fancybox({
        'titleShow'    : false,
        'transitionIn'    : 'elastic',
        'transitionOut'    : 'elastic'
    });


    // image actions for image list
    $(".imageactions").css("visibility", "hidden");
    $(".liimage").hover(
        function(){
            $(this).find('.imageactions').css("visibility", "visible");
        },
        function(){
            $(".imageactions").css("visibility", "hidden");
        });
    // image actions for default image
    $(".default-image").hover(
        function(){
            $(this).find('.imageactions').css("visibility", "visible");
        },
        function(){
            $(".imageactions").css("visibility", "hidden");
        });

});
