define(['jquery', 'Capgemini_LightBulbs/js/model/lightbulbs'], function ($, lightbulbs) {
    'use strict';

    let lastCheckboxState = false;
    return function (config, node) {
        lightbulbs.setProperty('bulbsData', config.bulbsData);
        lightbulbs.setProperty('idx', config.idx);
        let $node = $(node),
            labelForIdInput = $node.find('label'),
            idInput = $node.find('input[name="lightbulb[' + config.idx +'][id]"]');
        lightbulbs.setProperty('elementsToUpdate', {
            qtyInput: $node.find('input[name="lightbulb[' + config.idx +'][qty]"]'),
            skuInput: $node.find('input[name="lightbulb[' + config.idx +'][sku]"]'),
            idInput: idInput,
            labelForIdInput: labelForIdInput,
            qtyDisplayElem: labelForIdInput.children('.qty'),
            priceDisplayElem: labelForIdInput.children('.price'),
        })
        lightbulbs.setProperty('showList', function () {
            if ($node.is(':visible')) {
                return;
            }
            idInput.prop('checked', lastCheckboxState);
            $node.show()
        });
        lightbulbs.setProperty('hideList', function () {
            if ($node.is(':hidden')) {
                return;
            }
            lastCheckboxState = idInput.is(':checked');
            idInput.prop('checked', false);
            $node.hide()
        });
    }
})
