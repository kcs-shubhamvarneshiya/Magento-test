define([
    'uiComponent'
], function(Component) {
    'use strict';

    return Component.extend({
        cartUrl: window.checkoutConfig.cartUrl
    });
});
