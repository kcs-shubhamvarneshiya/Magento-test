define([
    'jquery',
    'prototype'
], function ($) {
    "use strict";
    $.widget('lyonscg.showFilter', {

        _create: function () {
            this.element.on('click', function() {
                var filterButton = $('#layered-filter-block .filter-title strong');
                if (filterButton.length !== undefined && filterButton.length) {
                    filterButton.click();
                }
            });
        }
    });

    return $.lyonscg.showFilter;
});
