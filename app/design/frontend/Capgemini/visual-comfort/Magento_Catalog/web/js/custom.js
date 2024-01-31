require([
    'jquery',
    'domReady!'
    ], function ($) {
        jQuery(".amscroll-load-button").on("click", function(){
            jQuery('.product-item-info').load('.product-item-info');
        });
});
