define([
    'Magento_Ui/js/grid/columns/column',
    'underscore',
    'jquery'
], function (Column, _, $) {
    'use strict';

    return Column.extend({
        toggleRowExpand: function(viewModel, event) {
            try {
                var $row = $(event.currentTarget.parentNode);
                if ($row.hasClass('expanded-row')) {
                    $row.removeClass('expanded-row');
                } else {
                    $row.addClass('expanded-row');
                }
            } catch (e) {}
        },
        /**
         * Get field handler per row.
         *
         * @param {Object} row
         * @returns {Function}
         */
        getFieldHandler: function (row) {
            return this.toggleRowExpand.bind(this);
        }
    });
});
