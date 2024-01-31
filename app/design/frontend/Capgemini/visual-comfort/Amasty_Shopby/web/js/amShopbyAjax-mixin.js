define([
    'jquery'
], function($) {
    'use strict';
    return function(widget) {
        $.widget('mage.amShopbyAjax', widget, {
            initAjax: function () {
                var self = this;
                this.generateOverlayElement();

                if ($.mage.productListToolbarForm) {
                    //change page limit
                    $.mage.productListToolbarForm.prototype.changeUrl = function (paramName, paramValue, defaultValue) {
                        // Workaround to prevent double method call
                        if (self.blockToolbarProcessing) {
                            return;
                        }
                        self.blockToolbarProcessing = true;
                        setTimeout(function () {
                            self.blockToolbarProcessing = false;
                        }, 300);

                        var decode = window.decodeURIComponent,
                            urlPaths = this.options.url.split('?'),
                            urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                            paramData = {};

                        for (var i = 0; i < urlParams.length; i++) {
                            var parameters = urlParams[i].split('=');
                            paramData[decode(parameters[0])] = parameters[1] !== undefined
                                ? decode(parameters[1].replace(/\+/g, '%20'))
                                : '';
                        }
                        paramData[paramName] = paramValue;
                        if (paramValue == defaultValue) {
                            // delete paramData[paramName];
                        }
                        self.options.clearUrl = self.getNewClearUrl(paramName, paramData[paramName] ? paramData[paramName] : '');

                        //add ajax call
                        $.mage.amShopbyFilterAbstract.prototype.prepareTriggerAjax(null, null, null, true);
                    };
                }

                //change page number
                $(".toolbar .pages a").unbind('click').bind('click', function (e) {
                    var newUrl = $(this).prop('href'),
                        updatedUrl = null,
                        urlPaths = newUrl.split('?'),
                        urlParams = urlPaths[1].split('&');

                    for (var i = 0; i < urlParams.length; i++) {
                        if (urlParams[i].indexOf("p=") === 0) {
                            var pageParam = urlParams[i].split('=');
                            updatedUrl = self.getNewClearUrl(pageParam[0], pageParam[1] > 1 ? pageParam[1] : '');
                            break;
                        }
                    }

                    if (!updatedUrl) {
                        updatedUrl = this.href;
                    }
                    updatedUrl = updatedUrl.replace('amp;', '');
                    $.mage.amShopbyFilterAbstract.prototype.prepareTriggerAjax(document, updatedUrl, false, true);
                    $(document).scrollTop($(self.selectors.products_wrapper).offset().top);

                    e.stopPropagation();
                    e.preventDefault();
                });
            }
        });
        return $.mage.amShopbyAjax;
    };
});
