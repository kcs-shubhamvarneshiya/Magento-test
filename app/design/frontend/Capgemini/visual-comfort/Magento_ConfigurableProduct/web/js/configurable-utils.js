define([
    'jquery',
    'underscore',
    'Capgemini_LightBulbs/js/model/lightbulbs'
], function ($, _, lightbulbs) {
    'use strict';

    function getSelectedSwatch()
    {
        var $selects = $('#product-options-wrapper').find('select');
        var values = [];
        for (var i = 0; i < $selects.length; i++) {
            values.push($($selects[i]).val());
        }
        var $vars = $('.variation-item a');
        for (var i = 0; i < $vars.length; i++) {
            var options = $($vars[i]).data('options');
            var chosen = true;
            for (var j = 0; j < options.length; j++) {
                if (!_.contains(values, options[j])) {
                    chosen = false;
                    break;
                }
            }
            if (chosen) {
                // found the correct one
                return $vars[i];
            }
        }
        return false;
    }

    function updateSelection() {
        var selected = getSelectedSwatch();
        var optionLabel = $('#selected-option-label');
        if (!selected) {
            optionLabel.html('');
            return selected;
        }
        $('.product-info-main .variation-item a').removeClass('active');
        $('.product-info-main .variation-item').removeClass('active');
        $(selected).addClass('active');
        $(selected).parent().parent().addClass('active');
        optionLabel.html($(selected).attr('data-clp-finish'));
        updateSkuAndSpecifications(selected);
        updateAvailability(selected);
        updateProp65(selected);
        lightbulbs.updateBulbsData(selected);
        return selected;
    }

    function updateSkuAndSpecifications(elem) {
        var newSku = $(elem).data('product-sku');
        var $skus = $('.product.attribute.sku .value');
        if ($skus.length === 0) {
            return;
        }
        $($skus[0]).text(newSku);
        updateSpecifications($(elem).data('product-id'));
    }

    function updateSpecifications(productId) {
        if (!window.specificationsData || !window.specificationsData[productId]) {
            return;
        }
        var specs = window.specificationsData[productId];
        /*var containerIds = ['#product-specifications', '#additional-specs'];
        for (var i = 0; i < containerIds.length; i++) {
            var containerId = containerIds[i];
            $(containerId + ' td.data').each(function() {
                for (var j = 0; j < this.classList.length; j++) {
                    if (this.classList[j] === 'data' || this.classList[j] === 'criteria_1') {
                        continue;
                    }
                    if (specs.hasOwnProperty(this.classList[j])) {
                        $(this).html(specs[this.classList[j]]);
                    }
                }
            });
            var $desc = $('.additional-description .content');
            if ($desc.length && specs.hasOwnProperty('description')) {
                $desc.html(specs['description']);
            }
        }*/
        Object.keys(specs).forEach((key) => {
            let labelValuePair = specs[key],
                labelValuePairArr = typeof labelValuePair === 'string' ? labelValuePair.split(':') : [],
                // labelKey = labelValuePairArr[1] ? btoa(labelValuePairArr[0]) : btoa(key),
                labelKey =  btoa(key),
                pureValue = labelValuePairArr[1];
            $('td[data-label-key="' + labelKey + '"]').each(function () {
                let elem = $(this);
                if (elem.hasClass('pure-value')) {
                    elem.html(pureValue)
                } else {
                    window.specificationsDataTrimmed[labelKey] = labelValuePair;
                    elem.html(labelValuePair)
                }
            });
        });
        let specsWidget = $('#product-specifications').data('cpSpecs');
        if (specsWidget && specsWidget.options.metricWasAttached === true) {
            specsWidget.resetMetricData();
        }
        updateDocuments(productId, specs);
        updateLabel(specs);
    }

    function updateDocuments(productId, specs) {
        var docs = {
            'ts':  'ts_docname',
            'ig':  'ig_docname',
            'cad': 'cad_docname'
        };
        for (var doc in docs) {
            if (!docs.hasOwnProperty(doc)) {
                continue;
            }
            var $link = $('a.file-' + doc);
            if ($link.length === 0) {
                continue;
            }

            // if the selected child does not have a file, hide the link and skip it
            var specField = docs[doc];
            if (!specs.hasOwnProperty(specField) || !specs[specField]) {
                $link.hide();
                continue;
            }

            var url = $link.data('url-base') + specs[specField];
            $link.attr('href', url).show();
        }
    }

    function updateLabel(specs) {
        var $label = $('.mpproductlabel-label');
        if ($label.length === 0) {
            return;
        }
        if (specs.hasOwnProperty('new_item') && parseInt(specs['new_item'], 10) !== 0) {
            $label.show();
        } else {
            $label.hide();
        }
    }

    function updateAvailability(elem) {
        var label = $(elem).data('product-availability-label');
        var newAvailability = $(elem).data('product-availability-value');
        var $availabilityContainer = $('.product-info-stock-sku .stock span');
        if ($availabilityContainer.length === 0) {
            return;
        }
        $($availabilityContainer).html('<span>' + label + ':</span> ' + newAvailability);
    }

    function updateProp65(elem) {
        var newProp65 = $(elem).data('product-prop65');
        var $newProp65Element = $('.prop65-description .additional_block_inner');
        var newProp65Elementdiv = '';
        if ($newProp65Element.length > 1 && newProp65.length === 0) {
            return;
        }
        if(newProp65.length !== 0){
            newProp65Elementdiv = newProp65;
                $($newProp65Element).html(newProp65Elementdiv);
                $('.prop65-description').addClass("prop65-active");
        }else{
            $($newProp65Element).html(newProp65Elementdiv);
            $('.prop65-description').removeClass("prop65-active");
        }
    }

    function clickFromClp() {
        var paramsStr = window.location.search.substring(1),
            params = paramsStr.split('&');
        for (var i = 0; i < params.length; i++) {
            var parts = params[i].split('=');
            if (parts.length !== 2) {
                continue;
            }
            if (parts[0] === 'selected_product') {
                var productId = $.trim(parts[1]);
                $('a[data-product-id="' + productId + '"]').trigger('click');
                return;
            }
        }
    }

    function clickSelectedSwatch() {
        var selected = this.getSelectedSwatch();
        if (!selected) {
            return;
        }
        $(selected).trigger('click');
    }

    function requisitionEdit() {
        // only if we are editing an item from a quote
        if (window.location.href.indexOf('requisition_list/item/configure') === -1) {
            return;
        }
        this.clickSelectedSwatch();
    }

    function cartEdit() {
        // only if we are editing an item from the cart
        if (window.location.href.indexOf('checkout/cart/configure') === -1) {
            return;
        }
        this.clickSelectedSwatch();
    }

    function getOption(optionId) {
        var $opts = $('#product_addtocart_form [option-id="' + optionId + '"]');
        if ($opts.length > 0) {
            return $opts[0];
        }
        $opts = $('option[value="' + optionId + '"]');
        if ($opts.length > 0) {
            return $opts[0];
        }
        return false;
    }
    return {
        getSelectedSwatch: getSelectedSwatch,
        updateSelection: updateSelection,
        clickFromClp: clickFromClp,
        clickSelectedSwatch: clickSelectedSwatch,
        requisitionEdit: requisitionEdit,
        cartEdit: cartEdit,
        getOption: getOption,

    };
});
