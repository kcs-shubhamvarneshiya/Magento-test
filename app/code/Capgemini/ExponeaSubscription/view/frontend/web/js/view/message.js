define([
    'jquery',
    'mage/cookies'
], function($) {
    'use strict';
    return {
        messageWrapElem: '.subscribe-msg',
        messageContainerElem: '.message div',
        process: function (data) {
            if (data.message) {
                $(this.messageWrapElem).find(this.messageContainerElem).html(data.message);
            }
            $(this.messageWrapElem).find(this.messageContainerElem).addClass(data.status);
            $(this.messageWrapElem).show();
        },
    };
});
