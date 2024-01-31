define([
    'jquery',
    'mage/cookies'
], function($) {
    'use strict';
    $.widget('circa.newsletterSubscription', {
        options: {
            response: 'newsletter_response',
            response_type: 'newsletter_response_type',
            message_wrapper: '.message',
            message_container: '.message div',
            message: '',
            css_class: ''
        },

        _create: function() {
            this.options.message =  $.mage.cookies.get(this.options.response);
            this.options.css_class =  $.mage.cookies.get(this.options.response_type);
            this._bind();
        },

        _bind: function() {
            var elem = $(this.element);
            elem.hide();
            if (this.options.message) {
                elem.find(this.options.message_container).html(this.options.message.replaceAll('+', ' '));
                elem.find(this.options.message_wrapper).addClass(this.options.css_class);
                elem.show();
            }
        }
    });
    return $.circa.newsletterSubscription;
});
