define([
        "jquery",
        "underscore",
        "mage/translate",
        "Magento_Customer/js/customer-data",
        "accordion",
        'matchMedia',
        "mage/mage",
        "mage/cookies",
        "sticky",
        "owlcarousel",
        "Amasty_Base/vendor/slick/slick.min",
        "domReady!"
    ],
    function ($, _, $t, customerData, accordion, mediaCheck) {
        "use strict";
        var body = $('body');
        var slickInit = false;

        /**
         * Breadcrumbs for category view page
         */
        if (body.hasClass('catalog-category-view')) {
            if ($('.tabcontent .elements .element').length) {
                $('.tabcontent .elements .element').each(function (index, value) {
                    if (index >= 2) {
                        $('.tabcontent .elements').find('.element').eq(index).addClass('hide');
                        $('.tabcontent .controls .dots').append('<div class="selected"></div>');
                        for (var i = 2; i <= index; i++) {
                            $('.tabcontent .controls .dots').append('<div class="unselected"></div>');
                        }
                    }
                });

                var numItems = $('.tabcontent .elements .element').length;

                $('.tabcontent .controls .right-arrow').on('click', function () {
                    var rightClicked = this.className;
                    if (rightClicked === 'right-arrow more') {
                        for (var i = 0; i < numItems; i++) {
                            if (!$('.tabcontent .elements').find('.element').eq(i).hasClass('hide')) {
                                $('.tabcontent .elements').find('.element').eq(i).addClass('hide');
                                $('.tabcontent .elements').find('.element').eq(i + 2).removeClass('hide');
                                $('.tabcontent .controls .left-arrow').addClass('more');
                                $('.tabcontent .controls .dots').find('.selected').addClass('unselected').removeClass('selected');
                                $('.tabcontent .controls .dots').find('.unselected').eq(i + 1).addClass('selected').removeClass('unselected');
                                if ((i + 3) === numItems) {
                                    $('.tabcontent .controls .right-arrow').removeClass('more');
                                }
                                break;
                            }
                        }
                    }
                });
                $('.tabcontent .controls .left-arrow').on('click', function () {
                    var leftClicked = this.className;
                    if (leftClicked === 'left-arrow more') {
                        for (var j = (numItems - 1); j >= 0; j--) {
                            if (!$('.tabcontent .elements').find('.element').eq(j).hasClass('hide')) {
                                $('.tabcontent .elements').find('.element').eq(j).addClass('hide');
                                $('.tabcontent .elements').find('.element').eq(j - 2).removeClass('hide');
                                $('.tabcontent .controls .right-arrow').addClass('more');
                                $('.tabcontent .controls .dots').find('.selected').addClass('unselected').removeClass('selected');
                                $('.tabcontent .controls .dots').find('.unselected').eq(j - 2).addClass('selected').removeClass('unselected');
                                if ((j - 2) === 0) {
                                    $('.tabcontent .controls .left-arrow').removeClass('more');
                                }
                                break;
                            }
                        }
                    }
                });
            }
            if ($('.fifth-block .elements .element').length) {
                $('.tabcontent .controls .dots').append('<div class="selected"></div>');
                $('.fifth-block .elements .element').each(function (index, value) {
                    if (index >= 2) {
                        $('.fifth-block .elements').find('.element').eq(index).addClass('hide');
                    }
                    if (index > 2) {
                        $('.fifth-block .controls .dots').append('<div class="unselected"></div>');
                    }
                });

                var numItems2 = $('.fifth-block .elements .element').length;

                $('.fifth-block .controls .right-arrow').on('click', function () {
                    var rightClicked = this.className;
                    if (rightClicked === 'right-arrow more') {
                        for (var m = 0; m < numItems2; m++) {
                            if (!$('.fifth-block .elements').find('.element').eq(m).hasClass('hide')) {
                                $('.fifth-block .elements').find('.element').eq(m).addClass('hide');
                                $('.fifth-block .elements').find('.element').eq(m + 2).removeClass('hide');
                                $('.fifth-block .controls .left-arrow').addClass('more');
                                $('.fifth-block .controls .dots').find('.selected').addClass('unselected').removeClass('selected');
                                $('.fifth-block .controls .dots').find('.unselected').eq(m + 1).addClass('selected').removeClass('unselected');
                                if ((m + 3) === numItems2) {
                                    $('.fifth-block .controls .right-arrow').removeClass('more');
                                }
                                break;
                            }
                        }
                    }
                });
                $('.fifth-block .controls .left-arrow').on('click', function () {
                    var leftClicked = this.className;
                    if (leftClicked === 'left-arrow more') {
                        for (var n = (numItems2 - 1); n >= 0; n--) {
                            if (!$('.fifth-block .elements').find('.element').eq(n).hasClass('hide')) {
                                $('.fifth-block .elements').find('.element').eq(n).addClass('hide');
                                $('.fifth-block .elements').find('.element').eq(n - 2).removeClass('hide');
                                $('.fifth-block .controls .right-arrow').addClass('more');
                                $('.fifth-block .controls .dots').find('.selected').addClass('unselected').removeClass('selected');
                                $('.fifth-block .controls .dots').find('.unselected').eq(n - 2).addClass('selected').removeClass('unselected');
                                if ((n - 2) === 0) {
                                    $('.fifth-block .controls .left-arrow').removeClass('more');
                                }
                                break;
                            }
                        }
                    }
                });
            }

        }

        /**
         * Custom slick init
         */
        if ($('._init-custom-slider').length > 0) {
            var myCarousel = $("._init-custom-slider.elements.slick-slider");
            myCarousel.each(function () {
                if ($(this).attr('data-content-type')) {
                    var elementsPerSlide = parseInt($(this).attr('data-content-type'));

                    $(this).slick({
                        infinite: false,
                        slidesToScroll: elementsPerSlide,
                        slidesToShow: elementsPerSlide,
                        dots: true,
                        responsive: [
                            {
                                breakpoint: 1280,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2,
                                    dots: true,
                                    arrows: false,
                                    draggable: true,
                                    autoplay: true,
                                    autoplaySpeed: 6000
                                }
                            },
                            {
                                breakpoint: 980,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    dots: true,
                                    arrows: true,
                                    draggable: true,
                                    autoplay: true,
                                    autoplaySpeed: 6000
                                }
                            }
                        ]
                    });

                } else {
                    var elementsPerSlideDesktop = 3,
                        elementsPerSlideTablet = 2,
                        elementsPerSlideMobile = 1,
                        mobileBreakpoint = 980,
                        desktopBreakpoint = 1280,
                        classString = $(this).attr('class');

                    if (classString.indexOf('desktop-slides-') > -1) {
                        var expressionDesktop = /desktop-slides-(\d+)/i,
                            elementsPerSlideDesktop = parseInt(expressionDesktop.exec(classString)[1]);
                    }

                    if (classString.indexOf('tablet-slides-') > -1) {
                        var expressionTablet = /tablet-slides-(\d+)/i,
                            elementsPerSlideTablet = parseInt(expressionTablet.exec(classString)[1]);
                    }

                    if (classString.indexOf('mobile-slides-') > -1) {
                        var expressionMobile = /mobile-slides-(\d+)/i,
                            elementsPerSlideMobile = parseInt(expressionMobile.exec(classString)[1]);
                    }

                    if (classString.indexOf('mobile-breakpoint-') > -1) {
                        var expressionMobileBreakpoint = /mobile-breakpoint-(\d+)/i,
                            mobileBreakpoint = parseInt(expressionMobileBreakpoint.exec(classString)[1]);
                    }

                    if (classString.indexOf('desktop-breakpoint-') > -1) {
                        var expressionDesktopBreakpoint = /desktop-breakpoint-(\d+)/i,
                            desktopBreakpoint = parseInt(expressionDesktopBreakpoint.exec(classString)[1]);
                    }

                    $(this).slick({
                        infinite: false,
                        slidesToScroll: 4,
                        slidesToShow: 4,
                        dots: true,
                        responsive: [
                            {
                                breakpoint: desktopBreakpoint,
                                settings: {
                                    slidesToShow: elementsPerSlideTablet,
                                    slidesToScroll: elementsPerSlideTablet,
                                    dots: true,
                                    arrows: false,
                                    draggable: true,
                                    autoplay: true,
                                    autoplaySpeed: 6000
                                }
                            },
                            {
                                breakpoint: mobileBreakpoint,
                                settings: {
                                    slidesToShow: elementsPerSlideMobile,
                                    slidesToScroll: elementsPerSlideMobile,
                                    dots: true,
                                    arrows: true,
                                    draggable: true,
                                    autoplay: true,
                                    autoplaySpeed: 6000
                                }
                            }
                        ]
                    });
                }
            });
        }
        moveOlapic();


        $('.brseo').find('.br-widget.more-results').addClass('vc-slider');
        $('.vc-slider').on('init', function(event, slick){
            slickInit = true;
        });
        mediaCheck({
            media: '(min-width: 768px)',
            entry: function () {
                $('.vc-slider').slick({
                    infinite: false,
                    slidesToScroll: 4,
                    slidesToShow: 4,
                    dots: true,
                    arrows: true,
                    draggable: true,
                    autoplay: true,
                    autoplaySpeed: 6000
                });
            }.bind(this),
            exit: function () {
                if(slickInit){
                    $('.vc-slider').slick('unslick');
                }
            }.bind(this)
        });

        let layeredNavContainer = $('#layered-filter-block'),
            mobileNavContainer = $('#amasty-shopby-product-list .toolbar-sorter');

        if (layeredNavContainer.length > 0 || mobileNavContainer.length > 0) {
            let layeredNavOffset = layeredNavContainer.length > 0 ? Math.floor(layeredNavContainer.offset().top) : 0,
                //mobileNavOffset = Math.floor(mobileNavContainer.offset().top);
                mobileNavOffset = mobileNavContainer.length > 0 ? Math.floor(mobileNavContainer.offset().top) : 0;

            function handleScroll() {
                let scroll = $(window).scrollTop();
                let isDesktop = false;
                let isTablet = false;
                if ($(window).width() <= 1024 && $(window).width() >= 748) {
                    isTablet = layeredNavContainer.outerHeight() > 53;
                } else {
                    isDesktop = layeredNavContainer.outerHeight() > 0;
                }

                if (isDesktop) {
                    let headerHeight = Math.floor($('.page-header').outerHeight());

                    if (!layeredNavContainer.hasClass(('_sticky'))) {
                        layeredNavOffset = Math.floor(layeredNavContainer.offset().top)
                    }

                    if (scroll >= (layeredNavOffset - headerHeight - 30)) {
                        layeredNavContainer.addClass('_sticky')
                            .find('.filter-content').css('top', 0);

                    } else {
                        layeredNavContainer.removeClass('_sticky');
                    }
                } else {
                    if (isTablet) {
                        let headerHeight = Math.floor($('.nav-sections').outerHeight());

                        if (!layeredNavContainer.hasClass(('_sticky'))) {
                            layeredNavOffset = Math.floor(layeredNavContainer.offset().top)
                        }

                        if (scroll >= layeredNavOffset) {
                            layeredNavContainer.addClass('_sticky')
                                .find('.filter-content').css('top', 0);
                        } else {
                            layeredNavContainer.removeClass('_sticky');
                        }
                    } else {
                        if (!mobileNavContainer.hasClass('sticky')) {
                            mobileNavOffset = Math.floor(mobileNavContainer.offset().top);
                        }

                        if (scroll >= mobileNavOffset) {
                            mobileNavContainer.addClass('sticky');
                        } else {
                            mobileNavContainer.removeClass('sticky');
                        }
                    }
                }
            }

            //$(window).scroll(handleScroll);
            //setTimeout(handleScroll, 2000);
        }
        //Move Olapic down the page
        function moveOlapic() {
            var domWidth = $(window).width();

            if ($('.catalog-product-view').length && $('#olapic_specific_widget').length) {
                var desktopContainer = $('.product.media'),
                    mobileContainer = $('.olapic-mobile-holder'),
                    olapicContainer = $('.olapic-container');

                if (domWidth > 768) {
                    olapicContainer.appendTo(desktopContainer);
                } else {
                    olapicContainer.appendTo(mobileContainer);
                }
            }
        }

        //check safari client
        if (/^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
            body.addClass('client-safari');
        }

        //check Mac OS
        if (navigator.userAgent.indexOf('Mac OS X') != -1) {
            var is_opera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
            var is_Edge = navigator.userAgent.indexOf("Edge") > -1;
            var is_chrome = !!window.chrome && !is_opera && !is_Edge;

            if (is_chrome) {
                $("body").addClass("client-mac-chrome");
            }
        }

        // Simple plugin to move elements accross the DOM
        $.fn.moveInDom = function (options) {

            var defaults = {
                parentContainer: '',
                desktopContainer: '',
                mobileContainer: '',
                mobileViewport: 768
            };

            var settings = $.extend({}, defaults, options),
                domWidth = $(window).width();

            $(this).css('opacity', 0);

            if ($(settings.desktopContainer).length && $(settings.mobileContainer).length && $(settings.parentContainer).length) {
                $(this).each(function () {
                    if (domWidth > settings.mobileViewport) {
                        $(this).appendTo($(this).closest(settings.parentContainer).find(settings.desktopContainer));
                    } else {
                        $(this).appendTo($(this).closest(settings.parentContainer).find(settings.mobileContainer));
                    }

                    $(this).css('opacity', 1);
                })
            }

        };

        $('.checkout-cart-index .cart-comments').moveInDom({
            parentContainer: '.cart.item',
            desktopContainer: '.item-info .col.item .product-item-details',
            mobileContainer: '.additional-data td',
            mobileViewport: 768
        });



        $(window).resize(function () {
            moveOlapic();

            $('.checkout-cart-index .cart-comments').moveInDom({
                parentContainer: '.cart.item',
                desktopContainer: '.item-info .col.item .product-item-details',
                mobileContainer: '.additional-data td',
                mobileViewport: 768
            });
        });

        //Show Retail price on PDP
        $('.product-info-main .old-price.no-display').removeClass('no-display');

        if (body.hasClass('checkout-index-index')) {
            waitForElementToDisplay('.order-attributes .fieldset *', 500)
        }

        /**
         * Init/destroy variations carousel on CLP while hover/unhover product item
         */
        function createVariationCarousel() {
            //hover
            var variationItem = $(this).find('.variation-item'),
                variationItemCount = variationItem.length,
                carouselContainer = $(this).find('.variation-carousel'),
                itemsToDisplay = 4,
                domWidth = $(window).width();
                carouselContainer.owlCarousel({
                    lazyLoad: true,
                    nav: true,
                    autoPlay: false,
                    mouseDrag: false,
                    dots: false,
                    onInitialized: carouselInitialized(carouselContainer),
                    responsive: {
                        // breakpoint from 0 up
                        0: {
                            items: 2,
                            margin: 15
                        },
                        // breakpoint from 768 up
                        768: {
                            items: itemsToDisplay,
                            margin: 5
                        }
                    }
                });
        }

        $('body').on('mouseenter', '.product-item-info', createVariationCarousel);

        $('body').on('mouseleave', '.product-item-info', function () {
            var carouselContainer = $(this).find('.variation-carousel');
            carouselContainer.trigger('destroy.owl.carousel');
        })

        /**
         * Actions when slider was initialized
         */
        function carouselInitialized(carouselContainer) {
            //carouselContainer.fadeIn('fast');
        }

        /**
         * Manipulate class active on hover event for variations carousel
         */
        $('.variation-carousel').on('hover', '.variation-item', function () {
            $(this).closest('.variation-carousel').find('.variation-item').removeClass('active');
            $(this).addClass('active');
        });

        /**
         * Set page scroll position for CLP pages
         */
        $(window).scroll(function () {
            if ($('.catalog-category-view').length) {
                //set scroll position in session storage
                sessionStorage.scrollPos = $(window).scrollTop();
                sessionStorage.pageLink = window.location.href;
            }
        });

        var initScrollOnload = function () {
            if ($('.catalog-category-view').length &&
                sessionStorage.backToPreviousPage &&
                window.location.href == sessionStorage.pageLink) {
                //return scroll position in session storage
                $(window).scrollTop(sessionStorage.scrollPos || 0);

                sessionStorage.scrollPos = 0;
                sessionStorage.backToPreviousPage = false;
            } else {

                sessionStorage.backToPreviousPage = false;
            }
        };

        $(window).on('popstate', function () {
            sessionStorage.backToPreviousPage = true;
        });

        window.onload = initScrollOnload;

        /**
         * Set product list height
         */
        if ($('.page-products .products-grid .product-item').length) {
            $('.products-grid .product-item').each(function () {
                var innerContainer = $(this).find('.product-item-info');
                $(this).css('height', innerContainer.height());
            });
        }

        function waitForElementToDisplay(selector, time, retries = 20) {
            if (document.querySelector(selector) !== null) {
                let projectDetails = $('.checkout-project-details'),
                    orderAttributes = $('.order-attributes'),
                    customer = customerData.get('customer')();

                if (customer && customer.loggedinStatus == 1 && customer.tradeCustomer == 1) {
                    orderAttributes.appendTo('.checkout-project-details');
                    projectDetails.show();
                } else {
                    orderAttributes.remove();
                }
            } else {
                if (retries < 0) {
                    return;
                }

                setTimeout(function () {
                    waitForElementToDisplay(selector, time, retries - 1);
                }, time);
            }
        }

        $(window).resize(_.debounce(function () {
            if (($(window).width() < 768) && body.hasClass('catalog-category-view')) {
                $('.category-view .breadcrumbs').show();
                $('.page-main .breadcrumbs').hide();

                if (body.hasClass('greaterthan2')) {

                }
            } else {
                $('.category-view .breadcrumbs').hide();
                $('.page-main .breadcrumbs').show();
            }

            /**
             * Set product list height
             */
            if ($('.products-grid .product-item').length) {
                $('.products-grid .product-item').each(function () {
                    var innerContainer = $(this).find('.product-item-info');
                    $(this).css('height', innerContainer.height());
                });
            }
        }, 500));

        // handling default loading page in mobile view
        if ($(window).width() < 1280) {
            $(".footer-links").accordion({
                "active": false,
                "collapsible": true,
                "icons": {"header": "plus", "activeHeader": "minus"},
                "multipleCollapsible": true,
                "animate": true,
            });
        }

        // footer link redirect
        $('.footer-links .link-column a').click(function (e) {
            e.preventDefault();
            var link = $(this).attr('href');
            window.location.href = link;
        });

        // Close message
        $('body').on('click', '.messages .message .close', function () {
            $(this).closest('.message').hide();
        });

        // Cart comments
        $('#shopping-cart-table .cart-comments div[data-role="trigger"]').on('click', function () {
            $(this).closest('.cart-comments').toggleClass('active');
        });

        // homepage image accordion
        $('.homepage-image-accordion .accordion-slide').hover(
            function() {
                $('.homepage-image-accordion .accordion-slide').removeClass('active')
                $(this).addClass('active')
            },
            function() {
                // $(this).removeClass('active')
            }
        )
        jQuery(document).mouseup(function(e){
            var container = jQuery(".filter-options-content");
        
            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) 
            {
                jQuery(".filter-options-item.active").removeClass("active");
                container.hide();
            }
        });

    }
);
