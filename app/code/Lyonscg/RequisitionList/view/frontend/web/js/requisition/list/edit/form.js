/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

define([
    'jquery',
    'uiComponent',
    'mage/calendar',
    'mpDevbridgeAutocomplete'
], function ($,UiComponent, calendar, mpDevbridgeAutocomplete) {
    'use strict';

    return UiComponent.extend({
        defaults: {
            value: {}
        },

        /**
         * Init observable properties
         *
         * @returns {exports}
         */
        initObservable: function () {
            this._super()
                .observe('value');

            return this;
        },

        _init: function () {
            $.calendar.init();
            $.devbridgeAutocomplete.init();
        },

        /**
         * Set values
         *
         * @param {Object} data
         * @return void
         */
        setValues: function (data) {
            this.value(data);
        },

        /**
         * Get values
         *
         * @returns {Object}
         */
        getValues: function () {
            return this.value();
        },

        applyCalendar: function() {
            $('.calendar').calendar({
                showTime: false,
                dateFormat: 'yy-mm-dd',
                minDate: new Date()
            });
        },

        applyDateRangeToEHDates: function() {
            this.applyCalendar();
            $('.date__field-dates').dateRange({
                from: {
                    id: 'requisition-list-open-date'
                },
                to: {
                    id: 'requisition-list-end-date'
                },
                minDate: new Date()
            });
        },

        applyDateRangeToFurniture: function() {
            this.applyCalendar();
            $('.date__field-furniture').dateRange({
                from: {
                    id: 'requisition-list-delivery-date'
                },
                to: {
                    id: 'requisition-list-pickup-date'
                },
                minDate: new Date()
            });
        },

        applyDateRangeToEventDates: function() {
            this.applyCalendar();
            $('.event-dates').dateRange({
                from: {
                    id: 'requisition-list-event-start-date'
                },
                to: {
                    id: 'requisition-list-event-end-date'
                },
                minDate: new Date()
            });
        },

        suggest: function () {
            var self = this;
            // var venue = $("[name='venue']");
            var venue = $("#requisition-list-venue-name");
            venue.devbridgeAutocomplete({
                serviceUrl: window.serviceUrl,
                type: 'GET',
                dataType: 'json',
                paramName: 'searchCriteria[filter_groups][0][filters][0][value]',
                params: {
                    'searchCriteria[filter_groups][0][filters][1][field]': 'name',
                    'searchCriteria[filter_groups][0][filters][1][condition_type]': 'eq',
                    'searchCriteria[filter_groups][0][filters][1][value]': 'TBD',
                    'searchCriteria[filter_groups][0][filters][0][field]': 'name',
                    'searchCriteria[filter_groups][0][filters][0][condition_type]': 'like'
                },
                transformResult: function (response) {
                    return {
                        suggestions: $.map(response.items, function (dataItem) {
                            return {value: dataItem.name, data: dataItem.id};
                        })
                    };
                },
                onSelect: function (suggestion) {
                    var venue = $("#requisition-list-venue-id");
                    venue.val(suggestion.data);
                    venue.trigger('change');
                }
            });

            this.scrollModal();
        },

        focusout: function(element) {
            var self = this;
            var venue = $("#requisition-list-venue-name");
            var suggestion = $(".autocomplete-suggestions .autocomplete-suggestion.autocomplete-selected");
            if (suggestion.length == 0) {
                venue.val('TBD');
                venue.trigger('change');
            }
        },

        scrollModal: function(element) {
            $(".requisition-popup").scroll(function(){
                var autocompleteElem = $('.autocomplete-suggestions'),
                    fieldElem = $("#requisition-list-venue-name"),
                    borderOffset = 2;

                autocompleteElem.css('top', fieldElem.offset().top + fieldElem.height() + borderOffset);
            });
        }
    });
});
