/**
 * Widget CMS Block Random
 */

define([
    'jquery',
    'mage/url',
    'jquery-ui-modules/widget'
], function ($, url) {
    $.widget('capgemini.cmsBlockRandom', {
        options: {
            identityIds: [],
        },

        /**
         * Create widget
         *
         * @private
         */
        _create: function () {
            this.loadCmsBlock(this.getRandomIdentifier());
        },

        /**
         * Get random identifier from the list
         *
         * @returns {string|null}
         */
        getRandomIdentifier: function() {
            let length = this.options.identityIds.length;
            if (length === 0) {
                return null;
            }
            let pos = Math.floor(Math.random() * length);
            return this.options.identityIds[pos];
        },

        /**
         * Load CMS block by identifier
         *
         * @param {string} identifier CMS block indentifier
         */
        loadCmsBlock: function(identifier) {
            let self = this;
            let query = `query {
                cmsBlocks(identifiers: \"` + identifier + `\") {
                    items {
                        identifier
                        title
                        content
                    }
                }
            }`;

            $.ajax({
                method: "POST",
                url: url.build("graphql"),
                contentType: "application/json",
                data: JSON.stringify({
                    query: query,
                    variables: {}
                }),
                success: function (res) {
                    if (res.data.cmsBlocks.items[0]) {
                        self.setBlockContent(res.data.cmsBlocks.items[0])
                    } else {
                        self.removeIdentifierAndReload(identifier);
                    }
                },
                error: function (res) {
                    self.removeIdentifierAndReload(identifier);
                }
            });
        },

        /**
         * Remove identifier and load different block
         * @param {string} identifier CMS block indentifier
         */
        removeIdentifierAndReload(identifier)
        {
            if (this.options.identityIds.length > 1) {
                this.options.identityIds = this.options.identityIds.filter(item => item !== identifier);
                this.loadCmsBlock(this.getRandomIdentifier());
            }
        },

        /**
         * Set loaded block content
         *
         * @param {Object} data CMS block data
         */
        setBlockContent: function(data) {
            this.element.html(data.content);
        }
    });

    return $.capgemini.cmsBlockRandom;
});