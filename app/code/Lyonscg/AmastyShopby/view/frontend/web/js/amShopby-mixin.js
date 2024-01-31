define([
    'jquery',
    'jquery/jquery.parsequery'
], function($) {
    'use strict';
    let elementToProcess;
    return function(widget) {
        $.widget('mage.amShopbyFilterAbstract', widget, {
            prepareTriggerAjax: function (element, clearUrl, clearFilter, isSorting) {
                elementToProcess = element;

                return this._super(element, clearUrl, clearFilter, isSorting);
            },
            normalizeData: function (data, isSorting, clearFilter) {
                data = this._super(data, isSorting, clearFilter);
                if (!elementToProcess) {

                    return data;
                }
                let url = $(elementToProcess).attr('href'),
                    queryMarkPos = url.indexOf('?'),
                    params = $.parseQuery({
                        query: url.substring(queryMarkPos)
                    });
                if (params.c_group !== undefined) {
                    data.unshift({
                        name: "c_group",
                        value: params.c_group
                    });
                }
                return data;
            }
        });
        return $.mage.amShopbyFilterAbstract;
    };
});
