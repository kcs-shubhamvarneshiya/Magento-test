define([
    "jquery"
], function($){
    "use strict";
    return function megamenu() {

        jQuery(".nav-toggle").click(function () {
            if (jQuery("html").hasClass("nav-open")) {
                jQuery("html").removeClass('nav-before-open nav-open');
            } else {
                jQuery("html").addClass('nav-before-open nav-open');
            }
        });

        $(document).on('vsmenuLoaded', function () {
            $('.vsmenu .vsmenu-list .category-image').toggleClass('category-image menu-cat-image');
            $('.vsmenu .vsmenu-list .category-description').toggleClass('category-description menu-cat-description');
            
            let windowWidth = window.innerWidth;
            let desktopSize = windowWidth > 1280;
            if (desktopSize) {
                $('.vsmenu .vsmenu-list .category-item').hover(function(){
                    $(this).find('.mega-menu.fullmenu').addClass('visible');
                    $(this).addClass('hover');
                    $('div.desc-img:first-child').show();
                },function(){
                    $(this).find('.mega-menu.fullmenu').removeClass('visible');
                    $(this).removeClass('hover');
                    $('div.desc-img').hide();
                });

                $('div.desc-img').hide();
                $('.vsmenu .vsmenu-list .category-item .submenu-section').hover(function () {
                    $('div.desc-img').eq($(this).index()).css("display", "block");
                    $('div.desc-img').eq($(this).index()).siblings().css("display", "none");
                }, function () {
                    $('div.desc-img').eq($(this).index()).siblings().css("display", "none");
                });
            } else {
                $('.vsmenu .vsmenu-list .category-item .vsmenu-click').click(function () {
                    let outerwrap = $(this).parent().find('.mega-menu');
                    let innerwrap = $(this).parent().find(".vsmenu-content");
                    if ($(this).parent().find('.mega-menu').hasClass('active')) {
                        $(this).parent().find('.mega-menu').removeClass('active');
                        $(this).removeClass('expanded');
                        outerwrap.height(0);
                    } else {
                        $(this).parent().find('.mega-menu').addClass('active');
                        $(this).addClass('expanded');
                        outerwrap.height(innerwrap.outerHeight(true));
                    }
                });
            }
        });
    }
});