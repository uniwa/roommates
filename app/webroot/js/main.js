$(function(){


    $("#fileupload").customFileInput();

// fancybox
    $(".fancyImage").fancybox({
        'titleShow'    : false,
        'transitionIn'    : 'elastic',
        'transitionOut'    : 'elastic'
    });


    $(".imageactions").css("visibility", "hidden");
    $(".galleryimage").hover(
        function(){
            $(this).find('.imageactions').css("visibility", "visible");
        },
        function(){
            $(".imageactions").css("visibility", "hidden");
        });
});