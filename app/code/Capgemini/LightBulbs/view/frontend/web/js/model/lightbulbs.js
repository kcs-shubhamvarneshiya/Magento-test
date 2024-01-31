define(['jquery', "mage/translate"], function ($, $t) {
    'use strict';

    function setElementsValues(elementsToUpdate, bulbsData) {
        if (typeof bulbsData === 'undefined') {
            var newValForIdInputElem = '',
                qty   = '',
                sku   = '',
                id    = '',
                price = '';
        } else {
            newValForIdInputElem = 'lightbulb-' + bulbsData.id;
            qty   = bulbsData.qty;
            sku   = bulbsData.sku;
            id    = bulbsData.id;
            price = bulbsData.price;
        }
        elementsToUpdate.qtyInput.val(qty);
        elementsToUpdate.skuInput.val(sku);
        elementsToUpdate.idInput.val(id);
        elementsToUpdate.idInput.attr('id', newValForIdInputElem);
        elementsToUpdate.labelForIdInput.attr('for', newValForIdInputElem);
        elementsToUpdate.qtyDisplayElem.text($t('Qty') + ': ' + qty);
        elementsToUpdate.priceDisplayElem.text('+ ' + price);
    }
    return {
        bulbsData: undefined,
        idx: undefined,
        elementsToUpdate: undefined,
        showList: undefined,
        hideList: undefined,
        setProperty: function (key, value) {
            this[key] = value;
        },
        updateBulbsData: function (elem) {
            if (!this.bulbsData) {
                return;
            }

            let simpleId = $(elem).data('product-id');

            if (!simpleId || !this.bulbsData.hasOwnProperty(simpleId)) {
                setElementsValues(this.elementsToUpdate)
                this.hideList();
                return;
            }

            setElementsValues(this.elementsToUpdate, this.bulbsData[simpleId][this.idx])
            this.showList();
        }
    }
})
