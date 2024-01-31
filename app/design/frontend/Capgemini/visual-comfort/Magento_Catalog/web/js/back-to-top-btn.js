require([
    'jquery',
    'domReady!'
    ], function ($) {

    'use strict';

    var $btn = $('.back-to-top__btn');

    function backToTop() {
        if(window.matchMedia("(max-width: 767px)").matches){
            if($(this).scrollTop() > 250) {
                $btn.fadeIn();
            } else {
                $btn.fadeOut();
            }
        }
    }

    backToTop();

    $(window).scroll(backToTop);

    $btn.on('click touch', function() {
        $('body,html').animate({
            scrollTop:0
        }, 500);
        return false;
    })
});
