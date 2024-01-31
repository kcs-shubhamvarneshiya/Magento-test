define([
    'jquery',
    'underscore',
    'imagerotator'
], function ($) {
    'use strict';

    var mixin = {

        initialize: function(config, element) {
            this._super(config, element);
        },

        handleFotorama: function(e, fotorama) {
            var spinFrame = fotorama.activeFrame.$stageFrame;
            if (spinFrame.hasClass('webrotate360'))
                return;

            var spinWrap = spinFrame.find('.wr360wrap');
            if (spinWrap.length !== 1)
                return;
                const threesixty =  __WR360Config.threesixtyvalue;
                var threesixtyhtml =  `<model-viewer id='mv-360' alt='360 Viewer' src='/media/docs/360/${threesixty}'  ar='' ar-modes='webxr scene-viewer quick-look' environment-image='' poster='' seamless-poster='' shadow-intensity='1' camera-controls='' data-js-focus-visible='' ar-status='not-presenting'></model-viewer>`;

            spinFrame.attr('tabIndex', -1);
            spinFrame.addClass('webrotate360');
            spinWrap.html("<div id='wr360PlayerId' style='height:100%;'> " + threesixtyhtml + "</div>");


            spinWrap.on('pointerdown touchstart mousedown click mousemove touchmove mouseup', function(e) {
                e.stopPropagation();
            });

            var cfg = __WR360Config;
            var ir = WR360.ImageRotator.Create('wr360PlayerId');
            ir.settings.graphicsPath = cfg.graphicsPath;
            ir.settings.configFileURL = this.selectedSimpleConfig ? this.selectedSimpleConfig.confFileURL : cfg.confFileURL;
            ir.settings.rootPath = this.selectedSimpleConfig ? this.selectedSimpleConfig.rootPath : cfg.rootPath;
            ir.settings.googleEventTracking  = cfg.useAnalytics;
            $('#mv-360').attr("src",this.selectedSimpleConfig ? this.selectedSimpleConfig.confFileURL : cfg.confFileURL);

            if (cfg.licensePath) {
                if (cfg.licensePath.indexOf('.lic') > 0)
                    ir.licenseFileURL = cfg.licensePath;
                else
                    ir.licenseCode = cfg.licensePath;
            }

            ir.settings.apiReadyCallback = function(api, isFullscreen) {
                if (cfg.apiCallback.length > 0) {
                    var fn = window[cfg.apiCallback];
                    if (typeof fn === 'function')
                        fn(api, isFullscreen);
                }

                if (isFullscreen)
                    return;

                $(e.target).on('fotorama:fullscreenenter fotorama:fullscreenexit fotorama:showend', function() {
                    api.updateDimensions();
                });

                function onFullScreenChange() {
                    var fsElement = document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement;
                    if (!fsElement && fotorama.fullScreen) {
                        setTimeout(function() {
                            api.updateDimensions()
                        }, 100);
                    }
                }
                document.addEventListener('fullscreenchange', onFullScreenChange, false);
            };

            ir.runImageRotator();
        },

        initApi: function() {
            this._super();
            if (typeof __WR360Config === 'undefined'){
                return;
            }

            var self = this;
            var updateDataOld = this.settings.api.updateData;

            this.settings.api.updateData = function(data) {
                self.selectedSimpleConfig = null;
                var productIndex = __WR360Config.swatches ? window['__wr360Swatches'] : null;
                if (productIndex != null) {

                    var selectedSimpleProductId = self.getSelectedSimpleProduct(productIndex);
                    if (selectedSimpleProductId) {
                        self.selectedSimpleConfig = __WR360Config.swatches[selectedSimpleProductId];
                    }
                }

                var fsViewer = $('#wr360PlayerId_fs');
                if (fsViewer.length > 0)
                    fsViewer.remove();

                updateDataOld(data);
                self.initViewerFrame();
            };
            this.settings.$element.data('gallery', this.settings.api);
            self.initViewerFrame();
            this.settings.fotoramaApi.setOptions({ arrows: 1 });
        },

        initViewerFrame: function() {
            var slide = {
                html: "<div class='wr360wrap' style='height:100%;'></div>",
                thumb: __WR360Config.thumbPath,
                type: 'webrotate360'
            };

            if (__WR360Config.endPlacement)
                this.settings.fotoramaApi.push(slide);
            else
                this.settings.fotoramaApi.unshift(slide);

            this.settings.$elementF.on('fotorama:ready fotorama:showend', $.proxy(this.handleFotorama, this));
        },

        getSelectedSimpleProduct: function(productIndex) {
            var selectedOptions = null;

            jQuery('.variation-item a.active').each(function() {
                var attribute = $(this).attr('data-product-id');

                if (attribute && productIndex.hasOwnProperty(attribute))
                    selectedOptions = attribute;
            });
            return selectedOptions;
        },

        selectedSimpleConfig: null
    };

    return function (target) {
        return target.extend(mixin);
    };
});
