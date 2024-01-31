define([
    'jquery'
], function($) {
    'use strict';

    return (function(){
        let options = {
            customBtnSearch: '.amlocator-button.-search',
            customFilterLabel: '.amlocator-input--custom label',
            customInput: '.amlocator-input--custom',
            customShowRoomWrapper: '.showroom__wrapper',
            customMapHolderBlock: '.map__holder',
            amLocatorCurrentInput: '.amlocator-current-location input',
            amLocatorSelectedOption: '.amlocator-input .amlocator-select option',
            amLocatorSearch: '.amlocator-wrapper .amlocator-search',
            amLocatorReset: '.amlocator-current-location .amlocator-reset',
            amLocatorFilter: '.amlocator-button.amlocator-filter-attribute',
            amLocatorBtnNearby: '.amlocator-button.-nearby',
            measurementLabel: '.amlocator-block .amasty_distance_number'
        }

        $.fn.showFlex = function() {
            this.css('display','flex');
        }

        $(options.customBtnSearch).click(() => {
            if(!$(options.amLocatorCurrentInput).val()) {
                return;
            }else {
                $(options.customShowRoomWrapper).hide();
                $(options.customInput).showFlex();
                $(options.customMapHolderBlock).show();
                $(options.amLocatorSearch).trigger('click');
                $('.page-title-additional').hide();
            }
        })

        $(options.amLocatorCurrentInput).keydown((e) => {
            if(e.keyCode == 13) {
                $(options.customShowRoomWrapper).hide();
                $(options.customInput).showFlex();
                $(options.customMapHolderBlock).show();
                $('.page-title-additional').hide();
            }
        })

        $(options.amLocatorReset).click(() => {
            $(options.customShowRoomWrapper).show();
            $(options.customInput).hide();
            $(options.customMapHolderBlock).hide();
            $('.page-title-additional').show();
        })

        $(options.amLocatorBtnNearby).click(() => {
            $(options.amLocatorCurrentInput).val('');
            $(options.customShowRoomWrapper).hide();
            $(options.customInput).showFlex();
            $(options.customMapHolderBlock).show();
            $('.page-title-additional').hide();
        })

        $(options.customFilterLabel).each((i,v) => {
            $(v).click((e) => {
                $(v).toggleClass('selected');
                $(options.amLocatorSelectedOption).each((i,v) => {
                    if(v.attributes.name.value == e.target.dataset.name &&
                        e.target.classList.value == 'selected') {
                        v.selected = true;
                    }else if(v.attributes.name.value == e.target.dataset.name &&
                        e.target.classList.value != 'selected') {
                        v.selected = false;
                    }
                })

                setTimeout(function(){
                    $(options.amLocatorFilter).trigger('click');
                },1000);
            })
        })
    })()
});
