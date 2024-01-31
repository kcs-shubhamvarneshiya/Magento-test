define([
    'jquery',
    'jquery/ui'
], function ($, _) {
    'use strict';

    $.widget('cp.specs',{
        options: {
            specificationData: null,
            inchWasAttached: false,
            metricWasAttached: false
        },
        _getSpecificationsData: function () {
            return Object.values(window.specificationsDataTrimmed);
        },

        _create: function () {
            let self = this;
            self._initEvents();
            self._initDefaultState();
        },

        _initDefaultState: function () {
            this._appendMetricData();
            $('#spec-inch-tab-switch').parent().addClass('active').show();
            $('#spec-cm-tab-switch').parent().show();
        },

        _initEvents: function () {
            let self = this;
            $('#spec-inch-tab-switch').on('click', function(e) {
                e.preventDefault();
                $('#spec-cm-tab-switch').parent().removeClass('active');
                $('#spec-inch-tab-switch').parent().addClass('active');
                $('#spec-inch-tab').show();
                $('#spec-cm-tab').hide();
            });
            $('#spec-cm-tab-switch').on('click', function(e) {
                e.preventDefault();
                $('#spec-inch-tab-switch').parent().removeClass('active');
                $('#spec-cm-tab-switch').parent().addClass('active');
                $('#spec-cm-tab').show();
                if (self.options.metricWasAttached === false) {
                    self._appendMetricData();
                }
                $('#spec-inch-tab').hide();
            });
        },

        _appendMetricData: function () {
            let html = '';
            this._getConvertedSpecificationsData().forEach(function (value, index) {
                html += '<tr><td>'+ value.replace(';','') +'</td></tr>';
            });
            $('#spec-cm-tab').find('tbody').append(html);
            this.options.metricWasAttached = true;
        },

        _getConvertedSpecificationsData: function () {
            let attributesValues = [];
            for (let i = 0; i < this._getSpecificationsData().length; i++) {
                attributesValues[i] = this._getSpecificationsData()[i];
            }
            for (let i = 0; i < attributesValues.length; i++) {
                if(attributesValues[i] == null){
                    attributesValues[i] = '';
                }
                if(attributesValues[i]){
                attributesValues[i] = attributesValues[i].replace(/([\d.]+)("|in|&quot)/ig,
                    function(match, value, ...values) {
                        return (Math.floor(parseFloat(value)) * 2.54).toFixed(2) + 'cm';
                    });
                attributesValues[i] = attributesValues[i].replace(/([\d.]+)('|ft)/ig,
                    function(match, value, ...values) {
                        return (Math.floor(value) * 0.3048).toFixed(2) + 'm';
                    });
                attributesValues[i] = attributesValues[i].replace(/([\d.]+)('|lbs)/ig,
                    function(match, value, ...values) {
                        return (Math.floor(value) * 0.453592).toFixed(2) + 'kg';
                    });
                }
            }
            return attributesValues;
        },

        resetMetricData: function () {
            $('#spec-cm-tab').find('tbody').children().remove();
            this._appendMetricData();
        }
    });

    return $.cp.specs;
});
