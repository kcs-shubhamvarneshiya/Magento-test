/**
 * Copyright © Visual Comfort, Inc. 2022 All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'underscore',
    'mage/template',
    'matchMedia',
    'jquery-ui-modules/widget',
    'jquery-ui-modules/core',
    'mage/translate'
], function ($, _, mageTemplate, mediaCheck) {
    'use strict';

    /**
     * Check whether the incoming string is not empty or if doesn't consist of spaces.
     *
     * @param {String} value - Value to check.
     * @returns {Boolean}
     */
    function isEmpty(value) {
        return value.length === 0 || value == null || /^\s+$/.test(value);
    }

    $.widget('mage.quickSearch', {
        options: {
            autocomplete: 'off',
            minSearchLength: 3,
            responseFieldElements: 'ul li',
            selectClass: 'selected',
            template:
                '<li class="<%- data.row_class %>" id="qs-option-<%- data.index %>" role="option">' +
                '<span class="qs-option-name">' +
                ' <%- data.title %>' +
                '</span>' +
                '<span aria-hidden="true" class="amount">' +
                '<%- data.num_results %>' +
                '</span>' +
                '</li>',
            submitBtn: 'button[type="submit"]',
            searchLabel: '[data-role=minisearch-label]',
            isExpandable: null,
            suggestionDelay: 300,
            pageHeader: '.page-header',
            blockContainer: '.block.block-search',
            closeBtn: '.button.close'
        },

        /** @inheritdoc */
        _create: function () {
            this.responseList = {
                indexList: null,
                selected: null
            };
            this.autoComplete = $(this.options.destinationSelector);
            this.searchForm = $(this.options.formSelector);
            this.submitBtn = this.searchForm.find(this.options.submitBtn)[0];
            this.searchLabel = this.searchForm.find(this.options.searchLabel);
            this.buttonClose = this.searchForm.find(this.options.closeBtn);
            this.isExpandable = this.options.isExpandable;
            this.pageHeader = $(this.options.pageHeader);
            this.blockContainer = $(this.options.blockContainer);

            _.bindAll(this, '_onKeyDown', '_onPropertyChange', '_onSubmit');

            this.submitBtn.disabled = true;

            this.element.attr('autocomplete', this.options.autocomplete);

            mediaCheck({
                media: '(max-width: 1279px)',
                entry: function () {
                    this.isExpandable = true;
                }.bind(this),
                exit: function () {
                    this.isExpandable = true;
                }.bind(this)
            });

            $('body').resize(function () {
                this.setSearchOffsets();
            })

            this.buttonClose.on('click', function (e) {
                if (this.isExpandable && this.isActive()) {
                    e.preventDefault();
                }
                $(this.blockContainer).find('input').val('');
                this.blockContainer.removeClass('active');
                this.searchForm.removeClass('active');
                this.searchLabel.removeClass('active');
                $('.page-wrapper').removeClass('search-active');
            }.bind(this));

            this.searchLabel.on('click', function (e) {
                this.setActiveState(false);
            }.bind(this));

            this.element.on('blur', $.proxy(function () {
                if (!this.searchLabel.hasClass('active')) {
                    return;
                }

                setTimeout($.proxy(function () {
                    if (this.autoComplete.is(':hidden')) {
                        this.setActiveState(false);
                    } else {
                        this.element.trigger('focus');
                    }

                    this.autoComplete.hide();
                    this._updateAriaHasPopup(false);
                }, this), 250);
            }, this));

            if (this.element.get(0) === document.activeElement) {
                this.setActiveState(true);
            }

            this.searchLabel.on('click', this.setActiveState.bind(this, true));
            this.element.on('focus', this.setActiveState.bind(this, true));
            this.element.on('keydown', this._onKeyDown);
            // Prevent spamming the server with requests by waiting till the user has stopped typing for period of time
            this.element.on('input propertychange', _.debounce(this._onPropertyChange, this.options.suggestionDelay));

            this.searchForm.on('submit', $.proxy(function (e) {
                this._onSubmit(e);
                this._updateAriaHasPopup(false);
            }, this));

            this.setSearchOffsets();

            let overlayOffset = $('#maincontent').offset().top;
            if ($('.message.global.demo').length > 0) {
                overlayOffset += $('.message.global.demo').outerHeight();
            }

            window.addEventListener('scroll', () => {
                document.documentElement.style.setProperty('--scroll-y', `${window.scrollY}px`);
            });
        },

        /**
         * Checks if search field is active.
         *
         * @returns {Boolean}
         */
        isActive: function () {
            return this.searchLabel.hasClass('active');
        },

        /**
         *
         */
        setSearchOffsets: function () {
            mediaCheck({
                media: '(min-width: 1280px)',
                entry: function () {
                    $('#nav-search-btn-container').append(this.blockContainer);
                    this.searchLabel.insertBefore($('#search_mini_form .control'));
                    $('.block-search').css('top', 0);
                }.bind(this),
                exit: function () {
                    this.searchLabel.insertBefore($('.minicart-wrapper'));
                    this.blockContainer.insertAfter($('.page-header .header.content .sections.nav-sections'));
                    $('.block-search').css('top',
                        this.pageHeader.outerHeight()
                    );
                }.bind(this)
            });
        },

        /**
         * Sets state of the search field to provided value.
         *
         * @param {Boolean} isActive
         */
        setActiveState: function (isActive) {
            var searchValue;

            this.blockContainer.toggleClass('active', isActive);
            this.searchForm.toggleClass('active', isActive);
            this.searchLabel.toggleClass('active', isActive);
            $('.page-wrapper').toggleClass('search-active', isActive);
            // Lock the page from scrolling when search is open
            if (isActive) {
                const body = document.body;
                // used to measure with of scroll bar
                let beforePageWidth = $('body').width();

                body.style.position = 'fixed';
                body.style.top = '0';
                body.style.bottom = '0';
                body.style.left = '0';
                body.style.right = '0';

                let newPageWidth = $('body').width();
                let scrollbarWidth = (newPageWidth - beforePageWidth) + 'px';
                body.style.right = scrollbarWidth;
                $('.page-header').css('padding-right', scrollbarWidth);

            } else {
                const body = document.body;
                const scrollY = body.style.top;
                body.style.position = '';
                body.style.top = '';
            }

            if (this.isExpandable) {
                this.element.attr('aria-expanded', isActive);
                searchValue = this.element.val();
                this.element.val('');
                this.element.val(searchValue);
            }
        },

        /**
         * @private
         * @return {Element} The first element in the suggestion list.
         */
        _getFirstVisibleElement: function () {
            return this.responseList.indexList ? this.responseList.indexList.first() : false;
        },

        /**
         * @private
         * @return {Element} The last element in the suggestion list.
         */
        _getLastElement: function () {
            return this.responseList.indexList ? this.responseList.indexList.last() : false;
        },

        /**
         * @private
         * @param {Boolean} show - Set attribute aria-haspopup to "true/false" for element.
         */
        _updateAriaHasPopup: function (show) {
            if (show) {
                this.element.attr('aria-haspopup', 'true');
            } else {
                this.element.attr('aria-haspopup', 'false');
            }
        },

        /**
         * Clears the item selected from the suggestion list and resets the suggestion list.
         * @private
         * @param {Boolean} all - Controls whether to clear the suggestion list.
         */
        _resetResponseList: function (all) {
            this.responseList.selected = null;

            if (all === true) {
                this.responseList.indexList = null;
            }
        },

        /**
         * Executes when the search box is submitted. Sets the search input field to the
         * value of the selected item.
         * @private
         * @param {Event} e - The submit event
         */
        _onSubmit: function (e) {
            var value = this.element.val();

            if (isEmpty(value)) {
                e.preventDefault();
            }

            if (this.responseList.selected) {
                this.element.val(this.responseList.selected.find('.qs-option-name').text());
            }
        },

        /**
         * Executes when keys are pressed in the search input field. Performs specific actions
         * depending on which keys are pressed.
         * @private
         * @param {Event} e - The key down event
         * @return {Boolean} Default return type for any unhandled keys
         */
        _onKeyDown: function (e) {
            var keyCode = e.keyCode || e.which;

            switch (keyCode) {
                case $.ui.keyCode.HOME:
                    if (this._getFirstVisibleElement()) {
                        this._getFirstVisibleElement().addClass(this.options.selectClass);
                        this.responseList.selected = this._getFirstVisibleElement();
                    }
                    break;

                case $.ui.keyCode.END:
                    if (this._getLastElement()) {
                        this._getLastElement().addClass(this.options.selectClass);
                        this.responseList.selected = this._getLastElement();
                    }
                    break;

                case $.ui.keyCode.ESCAPE:
                    this._resetResponseList(true);
                    this.autoComplete.hide();
                    break;

                case $.ui.keyCode.ENTER:
                    if (this.element.val().length >= parseInt(this.options.minSearchLength, 10)) {
                        this.searchForm.trigger('submit');
                        e.preventDefault();
                    }
                    break;

                case $.ui.keyCode.DOWN:
                    if (this.responseList.indexList) {
                        if (!this.responseList.selected) {  //eslint-disable-line max-depth
                            this._getFirstVisibleElement().addClass(this.options.selectClass);
                            this.responseList.selected = this._getFirstVisibleElement();
                        } else if (!this._getLastElement().hasClass(this.options.selectClass)) {
                            this.responseList.selected = this.responseList.selected
                                .removeClass(this.options.selectClass).next().addClass(this.options.selectClass);
                        } else {
                            this.responseList.selected.removeClass(this.options.selectClass);
                            this._getFirstVisibleElement().addClass(this.options.selectClass);
                            this.responseList.selected = this._getFirstVisibleElement();
                        }
                        this.element.val(this.responseList.selected.find('.qs-option-name').text());
                        this.element.attr('aria-activedescendant', this.responseList.selected.attr('id'));
                        this._updateAriaHasPopup(true);
                        this.autoComplete.show();
                    }
                    break;

                case $.ui.keyCode.UP:
                    if (this.responseList.indexList !== null) {
                        if (!this._getFirstVisibleElement().hasClass(this.options.selectClass)) {
                            this.responseList.selected = this.responseList.selected
                                .removeClass(this.options.selectClass).prev().addClass(this.options.selectClass);

                        } else {
                            this.responseList.selected.removeClass(this.options.selectClass);
                            this._getLastElement().addClass(this.options.selectClass);
                            this.responseList.selected = this._getLastElement();
                        }
                        this.element.val(this.responseList.selected.find('.qs-option-name').text());
                        this.element.attr('aria-activedescendant', this.responseList.selected.attr('id'));
                        this._updateAriaHasPopup(true);
                        this.autoComplete.show();
                    }
                    break;
                default:
                    return true;
            }
        },

        /**
         * Executes when the value of the search input field changes. Executes a GET request
         * to populate a suggestion list based on entered text. Handles click (select), hover,
         * and mouseout events on the populated suggestion list dropdown.
         * @private
         */
        _onPropertyChange: function () {
            var searchField = this.element,
                clonePosition = {
                    position: 'absolute',
                    // Removed to fix display issues
                    // left: searchField.offset().left,
                    // top: searchField.offset().top + searchField.outerHeight(),
                    width: searchField.outerWidth()
                },
                source = this.options.template,
                template = mageTemplate(source),
                dropdown = $('<ul role="listbox"></ul>'),
                value = this.element.val();

            this.submitBtn.disabled = true;

            if (value.length >= parseInt(this.options.minSearchLength, 10)) {
                this.submitBtn.disabled = false;

                if (this.options.url !== '') { //eslint-disable-line eqeqeq
                    $.getJSON(this.options.url, {
                        q: value
                    }, $.proxy(function (data) {
                        if (data.length) {
                            $.each(data, function (index, element) {
                                var html;

                                element.index = index;
                                html = template({
                                    data: element
                                });
                                dropdown.append(html);
                            });

                            this._resetResponseList(true);

                            this.responseList.indexList = this.autoComplete.html(dropdown)
                                .css(clonePosition)
                                .show()
                                .find(this.options.responseFieldElements + ':visible');

                            this.element.removeAttr('aria-activedescendant');

                            if (this.responseList.indexList.length) {
                                this._updateAriaHasPopup(true);
                            } else {
                                this._updateAriaHasPopup(false);
                            }

                            this.responseList.indexList
                                .on('click', function (e) {
                                    this.responseList.selected = $(e.currentTarget);
                                    this.searchForm.trigger('submit');
                                }.bind(this))
                                .on('mouseenter mouseleave', function (e) {
                                    this.responseList.indexList.removeClass(this.options.selectClass);
                                    $(e.target).addClass(this.options.selectClass);
                                    this.responseList.selected = $(e.target);
                                    this.element.attr('aria-activedescendant', $(e.target).attr('id'));
                                }.bind(this))
                                .on('mouseout', function (e) {
                                    if (!this._getLastElement() &&
                                        this._getLastElement().hasClass(this.options.selectClass)) {
                                        $(e.target).removeClass(this.options.selectClass);
                                        this._resetResponseList(false);
                                    }
                                }.bind(this));
                        } else {
                            this._resetResponseList(true);
                            this.autoComplete.hide();
                            this._updateAriaHasPopup(false);
                            this.element.removeAttr('aria-activedescendant');
                        }
                    }, this));
                }
            } else {
                this._resetResponseList(true);
                this.autoComplete.hide();
                this._updateAriaHasPopup(false);
                this.element.removeAttr('aria-activedescendant');
            }
        }
    });

    return $.mage.quickSearch;
});
