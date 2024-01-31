/**
 * Lyonscg_Easyzoom
 *
 * @category  Lyons
 * @package   Lyonscg_Easyzoom
 * @author    Tanya Mamchik <tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2021 Lyons Consulting Group (www.lyonscg.com)
 */
define([
    'jquery',
    'easyzoom',
    'Magento_ConfigurableProduct/js/configurable-utils',
    'domReady'
], function ($, easyzoom, configurableUtils) {
    "use strict";

    $.widget('lyonscg.easyzoom', {
        options: {
            toggler: '.gallery-placeholder .eazyzoom_mag',
            wrapper: '.gallery-placeholder',
            disableClass: 'zoom_disabled',
            swatch: '.variation-item a',
            easyzoom: '.gallery-placeholder .easyzoom'
        },

        _create: function() {
            var self = this;
            this.$wrapper = $(this.options.wrapper);
            this.$easyzoom = $(this.options.easyzoom).easyZoom();
            this.api = this.$easyzoom.data('easyZoom');
            configurableUtils.clickFromClp();
            configurableUtils.requisitionEdit();
            configurableUtils.cartEdit();
            $(document).on('click', this.options.toggler, $.proxy(this._toggle, this));
            $(document).on('click', this.options.swatch, $.proxy(function(event){
                configurableUtils.updateSelection(self.api);
            }));
        },

        _toggle: function() {
            if (this.$wrapper.hasClass(this.options.disableClass)) {
                this.$wrapper.removeClass(this.options.disableClass);
                this.api.show();
            } else {
                this.$wrapper.addClass(this.options.disableClass);
                this.api.hide();
            }
        },
    });

    return $.lyonscg.easyzoom;
});
