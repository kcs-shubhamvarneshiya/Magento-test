define([
    'jquery',
    'uiComponent',
    'ko'],
    function ($, Component, ko) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Capgemini_ProductDimensions/dimensions'
            },
            dimensions: null,
            specifications: null,
            hasSpecifications: false,
            hasDimensions: false,
            specCollapseElem: false,
            dimCollapseElem: false,
            elementSelector: "#product-dimensions-comp",
            addToCartSelector: "#product_addtocart_form",
            simpleProductElement: "selected_configurable_option",
            initialize: function () {
                this._super();
                this.initListeners();
                if (this.getCurrentProduct()) {
                    this.initData(this.getCurrentProduct());
                }
            },
            getCurrentProduct: function () {
                let productId = $(this.addToCartSelector).find('input[name='+this.simpleProductElement+']').val();
                if (productId) {
                    return productId;
                }

                return this.currentProduct;
            },
            initData: function (productId) {
                this.setDimensions(this.dimensionsConfig.dimensions[productId]);
                this.setSpecifications(this.dimensionsConfig.specifications[productId]);
            },
            initObservable: function () {
                this._super();
                this.observe([
                    'dimensions',
                    'specifications',
                    'hasSpecifications',
                    'hasDimensions',
                    'specCollapseElem',
                    'dimCollapseElem'
                ]);
                return this;
            },
            initListeners: function () {
                let that = this;
                $(document).on("dimensions:updateProductId", function(event, data) {
                    that.initData(data.product_id);
                });
            },
            processExpand: function() {
                let buttonElement = $(this.elementSelector).find('button.table-expand');
                $(this.elementSelector).find('.collapsible').toggle();
                let display = $(this.elementSelector).find('.collapsible').css('display');
                let text = 'Show Less' ;
                if(display =='none' || display=='')
                    text = 'Show More';
                buttonElement.text(text);
            },
            getDimensions: function () {
                return this.dimensions;
            },
            getSpecifications: function () {
                return this.specifications;
            },
            getDimCollapseElem: function () {
               return this.dimCollapseElem;
            },
            setDimensions: function (dimensions) {
                this.hasDimensions(false);
                this.dimCollapseElem(false);
                if (dimensions.length > 0) {
                    this.hasDimensions(true);
                }
                if (dimensions.length > 4) {
                    this.dimCollapseElem(true);
                }
                this.dimensions(dimensions);
            },
            setSpecifications(specifications) {
                this.hasSpecifications(false);
                this.specCollapseElem(false);
                if (specifications.length > 0) {
                    this.hasSpecifications(true);
                }
                if (specifications.length > 4) {
                    this.specCollapseElem(true);
                }
                this.specifications(specifications);
            }
        });
    }
);
