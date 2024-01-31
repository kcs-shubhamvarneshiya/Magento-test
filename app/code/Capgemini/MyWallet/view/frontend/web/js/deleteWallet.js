define(
    [
        'jquery',
        'underscore',
        'Magento_Ui/js/modal/confirm',
        'domReady!',
        'loader'
    ],
    function ($, _, confirmation) {
        "use strict";

        $.widget('myWallet.delete',  {
            options: {
                saveUpdates: '#save_updates',
                cancelUpdates: '#cancel_updates',
                walletFormId: '#wallet_form',
                deleteWalletUrl: '',
                loaderUrl: ''
            },

            /**
             * Widget initialization
             *
             * @private
             */
            _create: function () {
                this._super();
                this.addListeners();
                $('#loader').loader({
                    icon: this.options.loaderUrl
                });
                $('#loader').loader("hide");
            },


            addListeners: function () {
                var self = this;

                $(".removelink").on('click', function (e) {
                    e.preventDefault();
                    var walletId = e.target.id;
                    walletId = walletId.substring(12);
                    confirmation({
                        title: 'Delete credit card',
                        content: 'Are you sure you want to remove this credit card ? ' ,
                        actions: {
                            cancel: function (){},
                            confirm: function () {
                                $('#loader').loader({
                                    icon: self.options.loaderUrl
                                });
                                $('#loader').loader("show");
                                window.location = self.options.deleteWalletUrl +'\?id=' + walletId;
                            },
                            always: function (){}
                        }
                    });


                });
            }
        });

        return $.myWallet.delete;
    }
);
