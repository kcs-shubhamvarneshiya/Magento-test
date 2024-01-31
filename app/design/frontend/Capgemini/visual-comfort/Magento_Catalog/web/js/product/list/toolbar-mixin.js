define([
    'jquery'
], function($) {
    'use strict';
    return function(widget) {
        $.widget('mage.productListToolbarForm', widget, {

            options: {
                query: 'q'
            },
            /**
             * @param {String} paramName
             * @param {*} paramValue
             * @param {*} defaultValue
             */
            changeUrl: function (paramName, paramValue, defaultValue) {
                var decode = window.decodeURIComponent,
                    urlPaths = this.options.url.split('?'),
                    baseUrl = urlPaths[0],
                    urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                    paramData = {},
                    parameters, i, form, params, key, input, formKey;

                for (i = 0; i < urlParams.length; i++) {
                    parameters = urlParams[i].split('=');
                    paramData[decode(parameters[0])] = parameters[1] !== undefined ?
                        decode(parameters[1].replace(/\+/g, '%20')) :
                        '';
                }
                paramData[paramName] = paramValue;

                if (this.options.post) {
                    form = document.createElement('form');
                    params = [this.options.query, this.options.mode, this.options.direction, this.options.order, this.options.limit];

                    for (key in paramData) {
                        if (params.indexOf(key) !== -1) { //eslint-disable-line max-depth
                            input = document.createElement('input');
                            input.name = key;
                            input.value = paramData[key];
                            form.appendChild(input);
                            // delete paramData[key];
                        }
                    }
                    //Removed form_key

                    // formKey = document.createElement('input');
                    // formKey.name = 'form_key';
                    // formKey.value = this.options.formKey;
                    // form.appendChild(formKey);

                    paramData = $.param(paramData);
                    baseUrl += paramData.length ? '?' + paramData : '';

                    form.action = baseUrl;
                    //Changed POST method to GET
                    form.method = 'GET';
                    document.body.appendChild(form);
                    //form.submit();
                    location.href = baseUrl;
                } else {
                    if (paramValue == defaultValue) { //eslint-disable-line eqeqeq
                        delete paramData[paramName];
                    }
                    paramData = $.param(paramData);
                    location.href = baseUrl + (paramData.length ? '?' + paramData : '');
                }
            }
        });
        return $.mage.productListToolbarForm;
    };
});
