/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/dataPost',
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/modal/modal',
    'jquery-ui-modules/widget',
    'jquery/validate',
    'mage/translate',
    'mage/mage'
], function ($, dataPost, confirm) {
    'use strict';

    let needCheckForChanges,
        bulbsCalculationData,
        availableBulbsStates,
        bulbsState,
        bulbStateToButtonInscription;

    $.widget('mage.requisitionActions', {
        options: {
            form: '#form-requisition-list',
            button: {
                removeList: '[data-action="remove-list"]',
                removeItem: '[data-action="remove-item"]',
                removeSelected: '[data-action="remove-selected"]',
                update: '[data-action="update-list"]',
                updateItem: '[data-action="edit-item"]',
                toggleBulbs: '[data-action="toggle-bulbs"]',
                addAllToCart: '.action.add-to-cart-button',
                addSelectedToCart: '.action.secondary.add-cart'
            },
            input: {
                selectAll: '[data-role="select-all"]',
                select: '[data-role="select-item"]',
                qty: '[data-role="requisition-item-qty"]',
                remove: '[data-action="requisition-item-check"]:checked',
                selectionSelector: '[data-action="requisition-item-check"]'
            },
            confirmMessage: {
                removeList: '',
                removeSelected: $.mage.__(
                    'Are you sure you would like to remove selected items from the quote?'
                )
            },
            titleNames: {
                removeList: $.mage.__(
                    'Delete Requisition List?'
                ),
                removeItem: $.mage.__ (
                    'Remove from Quote?'
                )
            },
            bulbElements: 'tr[data-is-bulb-product="true"]',
            nonBulbElements: 'tr[data-is-bulb-product="false"]',
            inputSelector: '.input-text',
            bulbsCalculationData: null,
            availableBulbsStates: null,
            bulbsState: null,
            bulbStateToButtonInscription: null,
            saveErrorModalContent: '',
            inputCollection: null,
            inputElements: []
        },

        /**
         * @private
         */
        _create: function () {
            this._bind();
            this._initGlobals();
            this._adjustBulbElementsQty();
            this._initInputElements()
        },

        /**
         * @private
         */
        _bind: function () {
            var self = this,
                events = {};

            /**
             * @param {jQuery.Event} event
             */
            events['click ' + this.options.button.removeList] =  function (event) {
                event.stopPropagation();

                confirm({
                    title: self.options.titleNames.removeList,
                    modalClass: 'requisition-popup modal-slide',
                    content: $.mage.__(
                        'Are you sure you want to delete "%1" list? This action cannot be undone.'
                    ).replace('%1', $(event.currentTarget).data('delete-list').listName),
                    buttons: [{
                        text: $.mage.__('Delete'),
                        'class': 'action primary confirm',

                        /**
                         * @param {jQuery.Event} e
                         */
                        click: function (e) {
                            this.closeModal(e, true);
                        }
                    }, {
                        text: $.mage.__('Cancel'),
                        'class': 'action secondary cancel',

                        /**
                         * @param {juery.Event} e
                         */
                        click: function (e) {
                            this.closeModal(e);
                        }
                    }],
                    actions: {
                        /** Confirm */
                        confirm: function () {
                            var url = $(event.currentTarget).data('delete-list').deleteUrl;

                            self._request(url);
                        },

                        /** Always */
                        always: function (e) {
                            e.stopImmediatePropagation();
                        }
                    }
                });
            };

            /**
             * @param {jQuery.Event} event
             */
            events['click ' + this.options.button.update] =  function (event) {
                this._updateRequesitionList()
            };

            /**
             * @param {jQuery.Event} event
             */
            events['click ' + this.options.button.removeItem] =  function (event) {
                event.stopPropagation();

                confirm({
                    modalClass: 'requisition-popup modal-slide',
                    title: self.options.titleNames.removeItem,
                    content: self.options.confirmMessage.removeSelected,
                    buttons: [{
                        text: $.mage.__('Delete'),
                        'class': 'action primary confirm',

                        /**
                         * @param {jQuery.Event} e
                         */
                        click: function (e) {
                            this.closeModal(e, true);
                        }
                    }, {
                        text: $.mage.__('Cancel'),
                        'class': 'action secondary cancel',

                        /**
                         * @param {jQuery.Event} e
                         */
                        click: function (e) {
                            this.closeModal(e);
                        }
                    }],
                    actions: {
                        /** Confirm */
                        confirm: function () {
                            let rootItemDiv = $(event.currentTarget).closest('tr');
                            rootItemDiv = $(rootItemDiv);
                            let isBulb = rootItemDiv.data('is-bulb-product'),
                                newBulbsState;
                            if (isBulb === true) {
                                rootItemDiv.hide();
                            } else if (isBulb === false) {
                                rootItemDiv.remove();
                            } else {
                                return;

                            }
                            self._adjustBulbElementsQty();
                            newBulbsState = self._calculateCurrantBulbsState();
                            self._performBulbsStateUpdate(newBulbsState);
                            self._setDisabledPropOnAddToCartButtons(true)
                            needCheckForChanges = true;
                        },

                        /**
                         * @param {jQuery.Event} e
                         */
                        always: function (e) {
                            e.stopImmediatePropagation();
                        }
                    }
                });
            };

            /**
             * @param {jQuery.Event} event
             */
            events['click ' + this.options.button.updateItem] =  function (event) {
                var url;

                event.stopPropagation();
                url = $(event.currentTarget).data('update-item').editItemUrl;
                self._request(url);
            };

            /**
             * @param {jQuery.Event} event
             */
            events['click ' + this.options.button.toggleBulbs] =  function (event) {
                event.stopPropagation();
                let newBulbsState = availableBulbsStates.bulbsStateHasNoAndCanNotHave;
                if (bulbsState === availableBulbsStates.bulbsStateHas) {
                    $(self.options.bulbElements).hide();
                    self._adjustBulbElementsQty();
                    newBulbsState = self._calculateCurrantBulbsState();
                } else if (bulbsState === availableBulbsStates.bulbsStateHasNoButCanHave) {
                    $(self.options.bulbElements).show();
                    newBulbsState = availableBulbsStates.bulbsStateHas;
                }
                self._performBulbsStateUpdate(newBulbsState);
            };

            /**
             * @param {jQuery.Event} event
             */
            events['change ' + this.options.input.selectAll] =  function (event) {
                var selectAll = $(event.currentTarget);

                $(self.options.input.select).filter(':enabled')
                    .prop('checked', selectAll.prop('checked'))
                    .trigger('change');
            };

            /**
             * Check discrete select event trigger selectAll check/uncheck
             */
            events['change ' + this.options.input.select] =  function () {
                var isAllItemsChecked = $(self.options.input.selectionSelector).filter(':unchecked').length === 0;

                $(self.options.input.selectAll).prop('checked', isAllItemsChecked);
            };

            this._on(this.element, events);
            self.options.inputCollection = $(this.options.form + ' ' + this.options.inputSelector);
            self.options.inputCollection.one('input', () => {
                self._setDisabledPropOnAddToCartButtons(true);
                needCheckForChanges = true
            });
            window.onbeforeunload = (event) => {
                //event.preventDefault();
                self.options.inputCollection.blur();
                if (needCheckForChanges && self._hasChanged()) {
                    return event.returnValue = "Changes you made may not be saved. Are you sure you want to exit?";
                } else {
                    return undefined;
                }
            };

        },

        _initGlobals: function () {
            needCheckForChanges = false;
            if (this.options.bulbsCalculationData !== null) {
                bulbsCalculationData = this.options.bulbsCalculationData;
                this.options.bulbsCalculationData = null;
            }
            if (this.options.availableBulbsStates !== null) {
                availableBulbsStates = this.options.availableBulbsStates;
                this.options.availableBulbsStates = null;
            }
            if (this.options.bulbsState !== null) {
                bulbsState = this.options.bulbsState;
                this.options.bulbsState = null;
            }
            if (this.options.bulbStateToButtonInscription !== null) {
                bulbStateToButtonInscription = this.options.bulbStateToButtonInscription;
                this.options.bulbStateToButtonInscription = null;
            }
        },

        _adjustBulbElementsQty: function () {
            let self = this;
            $(self.options.bulbElements).each(function () {
                if ($(this).is(':hidden')) {
                    let sku = $(this).find('.product-item-sku span').text(),
                        id = $(this).data('product-id'),
                        totalQty = 0,
                        data = bulbsCalculationData[sku];
                    for (let nonBulbId in data) {
                        let parent = $(self.options.nonBulbElements).filter('[data-product-id="' + nonBulbId + '"]');
                        if (parent.length !== 1) {

                            continue;
                        }
                        let qty = $(parent[0]).find('#item-' + nonBulbId + '-qty').val();
                        totalQty = totalQty + parseInt(qty) * parseInt(data[nonBulbId]);
                    }
                    let element = $(this);
                    if (totalQty === 0) {
                        element.remove()
                    } else {
                        element.find('#item-' + id + '-qty').val(totalQty);
                    }
                }
            })
        },

        _initInputElements: function () {
            let self = this;
            self.options.inputCollection.each(function(index){
                let element = $(this);
                self.options.inputElements[index] = {
                    element: element,
                    value: element.val(),
                    isVisible: element.is(':visible')
                }
            })
        },

        /**
         * @private
         */
        _request: function (action, data) {
            dataPost().postData({
                action: action,
                data: data || {}
            });
        },

        //Copied and refactored from Lyonscg_CircaLighting::docupdate/quote.phtml
        /**
         * @private
         */
        _updateRequesitionList: function (){
            let self = this;
            $(self.options.bulbElements).each(function () {
                if ($(this).is(':hidden')) {
                    this.remove();
                }
            });
            var rlData = $(self.options.form).serializeArray(),
                errorModalSettings = {
                    modalClass: 'requisition-popup modal-slide',
                    content: self.options.saveErrorModalContent,
                    buttons: [
                        {
                            text: $.mage.__('Try again'),
                            class: 'action primary confirm',
                            click: function (e) {
                                this.closeModal(e, true);
                                self._updateRequesitionList()
                            }
                        },
                        {
                            text: $.mage.__('Cancel'),
                            class: 'action secondary cancel',
                            click: function (e) {
                                this.closeModal(e, true);
                            }
                        }
                    ]
                };
            rlData.push({
                'name': 'form_key',
                'value': $('[name="form_key"]').val()
            });
            rlData.push({
                'name': 'requisition_id',
                'value': self.options.requisitionId
            });
            $('body').trigger('processStart');
            $.post(
                self.options.quoteUrl,
                $.param(rlData),
                function(data) {
                    if (data.success) {
                        document.querySelector('.requisition-content').remove();
                        $('.column.main').prepend(data.html);
                        let requisitionContent = $('.requisition-content');
                        requisitionContent
                            .find('div[data-bind="scope: \'rename_requisition_list_popup\'"]')
                            .remove();
                        requisitionContent
                            .trigger('contentUpdated')
                            .applyBindings();
                        self._setDisabledPropOnAddToCartButtons(false)
                        needCheckForChanges = false;
                        $(window).scrollTop(0);
                    } else {
                        confirm(errorModalSettings)
                    }
                }
            ).fail(function() {
                confirm(errorModalSettings)
            }).always(function() {
                $('body').trigger('processStop');
            });
        },

        _hasChanged: function () {
            return !this.options.inputElements.every(
                (obj) => obj.value === obj.element.val() && obj.isVisible === obj.element.is(':visible')
            )
        },

        _calculateCurrantBulbsState: function () {
            let bulbs = $(this.options.bulbElements);
            if (bulbs.length === 0) {

                return availableBulbsStates.bulbsStateHasNoAndCanNotHave;
            }
            let isAllBulbsHidden = bulbs.toArray().every((element) => element.style.display === 'none');

            return isAllBulbsHidden
                ? availableBulbsStates.bulbsStateHasNoButCanHave
                : availableBulbsStates.bulbsStateHas;
        },

        _performBulbsStateUpdate: function (newBulbsState) {
            if (newBulbsState !== bulbsState) {
                let button = $(this.options.button.toggleBulbs),
                    inscription = bulbStateToButtonInscription[newBulbsState];
                $(button).children('span').text(inscription)
                if (newBulbsState === availableBulbsStates.bulbsStateHasNoAndCanNotHave) {
                    button.hide();
                }
                bulbsState = newBulbsState;
                this._setDisabledPropOnAddToCartButtons(true);
                needCheckForChanges = true;
            }
        },

        _setDisabledPropOnAddToCartButtons: function (bool) {
            $(this.options.button.addAllToCart).prop('disabled', bool);
            $(this.options.button.addSelectedToCart).prop('disabled', bool);
        }
    });

    return $.mage.requisitionActions;
});
