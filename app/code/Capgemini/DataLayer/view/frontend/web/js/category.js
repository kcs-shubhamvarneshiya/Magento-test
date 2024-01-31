define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function ($, customerData) {
    'use strict';

    $.widget('capgemini.datalayer_category', {
        options: {
            customer: customerData.get('customer')()
        },

        _create: function () {
            this.options.dataLayer = {
                'event': 'product click',
                'pageType': 'product_listing_page',
                'hashedEmail': this.options.customer['hashedEmail'],
                'loggedinStatus': this.options.customer['loggedinStatus'],
                'currencyCode': this.options.customer['currencyCode'],
                'tradeCustomer': this.options.customer['tradeCustomer'],
                'customerClass': this.options.customer['customerClass'],
                'ecommerce': {
                    'click': {
                        'products': []
                    }
                }
            };
            var self = this;
            $(this.options.elementIdentifier).on('click', $.proxy(function (e) {
                e.preventDefault();
                try {
                    var productElement = $(e.target).closest('li.product-item');
                    var productId = $(productElement).data('productId');
                    var product = self.options.productsData.products[productId];
                    $(productElement).find('[data-price-amount]').each(function () {
                        if ($(this).data('priceType') == 'oldPrice') {
                            product.fullPrice = $(this).data('priceAmount');
                        } else if ($(this).data('priceType') == 'finalPrice') {
                            product.price = $(this).data('priceAmount');
                        }
                    });
                    if (typeof product.fullPrice === 'undefined') {
                        product.fullPrice = product.price;
                    }
                    self.options.dataLayer.ecommerce.click.products.push(product);
                    window.dataLayer.push(self.options.dataLayer);
                } catch (e) {
                    console.log(e.message);
                }
                window.location.href = e.target.href ? e.target.href : e.currentTarget.href;
            }, self));
        }
    });
    return $.capgemini.datalayer_category;
});
