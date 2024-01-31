define([
        "jquery",
        "Magento_Ui/js/modal/modal",
        'mage/validation'
    ], function ($, modal) {
        $.widget('capgemini.wishlistPdfButton', {
            options: {
                modalSelector: '#wishlist-pdf-modal-wrap',
                errorMessageSelector: '#wishlist-error',
                markupPercentSelector: '#markup-percent',
                withMarkupType: '4',
                printUrl: ''
            },

            _create: function () {
                this._initElement();
            },

            _initElement: function () {
                var self = this;
                var options = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    modalClass: 'pdf-modal-container',
                    buttons: [{
                        text: $.mage.__('Print PDF'),
                        class: 'print-pdf-button primary',
                        click: function () {
                            self.print();
                        }
                    },
                    {
                        text: $.mage.__('Cancel'),
                        class: 'cancel-button secondary',
                        click: function (event) {
                            this.closeModal(event);
                        }
                    }]
                };
                modal(options, $(this.options.modalSelector));

                $(this.element).on('click', function () {
                    $(self.options.modalSelector).modal("openModal");
                });
            },

            print: function () {
                let selectedPricing = $(this.options.modalSelector).find('input:checked');
                let errorMessage = $(this.options.errorMessageSelector);
                if (selectedPricing.length === 0 ) {
                    errorMessage.text($.mage.__('Please select a pricing option.')).show();
                    return;
                }
                let url = this.options.printUrl + "pricing_type/" + selectedPricing.val() + '/';
                if (selectedPricing.val() === this.options.withMarkupType) {
                    let percent = $('#markup-percent');
                    if (!percent.val()) {
                        errorMessage.text($.mage.__('Please specify the markup %.')).show();
                        return;
                    }
                    if (!$.validator.validateElement(percent)) {
                        errorMessage.text($.mage.__('Please enter a valid number in the markup %.')).show();
                        return;
                    }
                    url += 'percent/' + percent.val() + '/';
                }
                errorMessage.hide();
                window.open(url);
            }
        });
        return $.capgemini.wishlistPdfButton;
    }
);
