define([
    'jquery',
    'mage/utils/wrapper',
], function ($, wrapper) {
    'use strict';
    return function (targetModule) {
        targetModule.prototype._getSimpleProductId =
            wrapper.wrap(targetModule.prototype._getSimpleProductId, function (original) {
                var result = original();
                if (result !== undefined) {
                    $(document).trigger(
                        'dimensions:updateProductId',
                        {'product_id':result}
                    );
                }
                return result;
            });
        return targetModule;
    };
});

