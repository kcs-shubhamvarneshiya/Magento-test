define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function($, customerData) {
    'use strict';

    return function(widget) {
        $.widget('mage.amScrollScript', widget, {
            groupId: undefined,
            isAmScrollEnabled: undefined,
            initialize: function() {
                let link,
                    matches;
                try {
                    link = $('#layered-filter-block')
                        .find('.block-content.filter-content .block-filters-wrapper form li a')[0].href
                    matches = link.match(/c_group=(\d+)/);
                    this.groupId = matches[1];
                } catch (e) {
                    this.groupId = null;
                }
                this._super();
            },
            _generateUrl: function (page, addScroll) {
                let url = this._super(page, addScroll);
                this.isAmScrollEnabled = this.isAmScrollEnabled === undefined ?
                    (url.indexOf('?is_scroll=1') > -1 || url.indexOf('&is_scroll=1') > -1 )
                    : this.isAmScrollEnabled;
                if (this.isAmScrollEnabled === false) {

                    return url;
                }
                if (!this.groupId) {

                    return url;
                }
                let search = 'c_group=' + this.groupId;
                if (url.indexOf(search) > -1) {
                    let split = url.split('?')
                    if (split[1] === search) {
                        url = split[0];
                    }

                } else if(url.indexOf('?p=') > -1 || url.indexOf('&p=') > -1 ) {
                    url = url.replace('?', '?' + search + '&');
                }

                return url;
            }
        });
        return $.mage.amScrollScript;
    };
})
