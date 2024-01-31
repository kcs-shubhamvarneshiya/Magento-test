define([
    'jquery',
    'mage/utils/wrapper',
    'uiRegistry'
], function ($, wrapper, uiRegistry) {
    'use strict';
    return function (targetModule) {
        targetModule.prototype._getSimpleProductId =
            wrapper.wrap(targetModule.prototype._getSimpleProductId, function (original) {
                var result = original();
                if (result !== undefined) {
                    uiRegistry.get('technical_resources', function(component) {
                        component.selectedProductId(result);
                    });
                }
                return result;
            });
        return targetModule;
    };
});
