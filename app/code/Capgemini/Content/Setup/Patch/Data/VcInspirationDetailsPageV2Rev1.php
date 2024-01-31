<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsPage;

/**
 * Class VcInspirationDetailsPageV2Rev1
 */
class VcInspirationDetailsPageV2Rev1 extends AbstractCmsPage
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        $pageContent = <<<EOD
<style>#html-body [data-pb-style=OBR4WKN]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=CHSVUB1]{text-align:left}#html-body [data-pb-style=DM2HNJ1],#html-body [data-pb-style=R73ETFV]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=DM2HNJ1]{justify-content:flex-start;display:flex;flex-direction:column}#html-body [data-pb-style=R73ETFV]{align-self:stretch}#html-body [data-pb-style=IT30EOK]{display:flex;width:100%}#html-body [data-pb-style=WX3DR3M]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=W7W5JE9]{border-style:none}#html-body [data-pb-style=GHP8V8V],#html-body [data-pb-style=GYJ9XCR]{max-width:100%;height:auto}#html-body [data-pb-style=XYF12G4]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=KJMXHPT]{border-style:none}#html-body [data-pb-style=L15AXOU],#html-body [data-pb-style=RRCGH7G]{max-width:100%;height:auto}#html-body [data-pb-style=ACF5R6B]{text-align:center}#html-body [data-pb-style=TPGU4EC],#html-body [data-pb-style=VNEU8LH]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=TPGU4EC]{justify-content:flex-start;display:flex;flex-direction:column}#html-body [data-pb-style=VNEU8LH]{align-self:stretch}#html-body [data-pb-style=FWHD5KN]{display:flex;width:100%}#html-body [data-pb-style=WV3H2CV]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=QDFEL7J]{border-style:none}#html-body [data-pb-style=BW8YR9G],#html-body [data-pb-style=YWDB9G3]{max-width:100%;height:auto}#html-body [data-pb-style=ADK5VVW]{text-align:center}#html-body [data-pb-style=SCICCCM]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=OUVTK67]{border-style:none}#html-body [data-pb-style=O7ILLIK],#html-body [data-pb-style=RO61ADW]{max-width:100%;height:auto}#html-body [data-pb-style=UUQUPUI]{text-align:center}#html-body [data-pb-style=YRMV6HC]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=XC1L88V]{border-style:none}#html-body [data-pb-style=KH0CQYL],#html-body [data-pb-style=QRBSKDH]{max-width:100%;height:auto}#html-body [data-pb-style=ONK1K9Y]{text-align:center}#html-body [data-pb-style=T2R3HWA]{width:100%;border-width:1px;border-color:#cecece;display:inline-block}#html-body [data-pb-style=L4M0EYJ]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}@media only screen and (max-width: 768px) { #html-body [data-pb-style=KJMXHPT],#html-body [data-pb-style=OUVTK67],#html-body [data-pb-style=QDFEL7J],#html-body [data-pb-style=W7W5JE9],#html-body [data-pb-style=XC1L88V]{border-style:none} }</style>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details page-head block-2-1" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="OBR4WKN">
        <div class="highlight" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">DESIGN PROFILES</span></p>
        </div>
        <h1 data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="CHSVUB1">Mudroom Solutions from Kate Marker Interiors</h1>
        <div data-content-type="text" data-appearance="default" data-element="main">
            <p>Mudrooms are high-traffic, functional spaces that are often overlooked. They keep the rest of the house looking clean, with racks for shoes, hooks for jackets, and catchall storage.<br>See how designer Kate Marker transforms mudrooms into stylish and organized home hubs.</p>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details no-description block-2-2" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="DM2HNJ1">
        <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="R73ETFV">
            <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="IT30EOK">
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="WX3DR3M">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="W7W5JE9"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="desktop_image" data-pb-style="GYJ9XCR"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="mobile_image" data-pb-style="GHP8V8V"></figure>
                </div>
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="XYF12G4">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="KJMXHPT"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="desktop_image" data-pb-style="L15AXOU"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="mobile_image" data-pb-style="RRCGH7G"></figure>
                </div>
            </div>
        </div>
        <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">Gale Library Wall Light by Thomas O’Brien | Photography By: @emilykennedyphoto</span></p>
        </div>
        <div class="quote" data-content-type="text" data-appearance="default" data-element="main">
            <p style="text-align: center;">“While most lighting selections tend to be restrained in style, the finishes and forms are always able to stand on their own with Visual Comfort.”</p>
        </div>
        <div class="author" data-content-type="text" data-appearance="default" data-element="main" data-pb-style="ACF5R6B">
            <p>Kate Marker</p>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details block-2-3" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="TPGU4EC">
        <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="VNEU8LH">
            <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="FWHD5KN">
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="WV3H2CV">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="QDFEL7J"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="desktop_image" data-pb-style="YWDB9G3"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="mobile_image" data-pb-style="BW8YR9G"></figure>
                    <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                        <p><span style="font-size: 10px;">Morris Large Flush Mount by Suzanne Kasler | Photography: Courtesy of Kate Marker Interiors</span></p>
                    </div>
                    <div class="quote" data-content-type="text" data-appearance="default" data-element="main">
                        <p style="text-align: center;">“Beautiful lighting helps to elevate a mudroom from merely a drop zone to a room deserving as much (if not more!) attention to detail as the rest of the house.”</p>
                    </div>
                    <div class="author" data-content-type="text" data-appearance="default" data-element="main" data-pb-style="ADK5VVW">
                        <p>Kate Marker</p>
                    </div>
                </div>
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="SCICCCM">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="OUVTK67"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="desktop_image" data-pb-style="O7ILLIK"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="mobile_image" data-pb-style="RO61ADW"></figure>
                    <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                        <p><span style="font-size: 10px;">McCarren Small Flush Mount by Ralph Lauren | Photography By: @stofferphotographyinteriors</span></p>
                    </div>
                    <div class="quote" data-content-type="text" data-appearance="default" data-element="main">
                        <p style="text-align: center;">“Done well, a mudroom will be utilized far more than many other rooms in a home by all members of the family and the design, including the lighting plan, should reflect that.”</p>
                    </div>
                    <div class="author" data-content-type="text" data-appearance="default" data-element="main" data-pb-style="UUQUPUI">
                        <p>Kate Marker</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details block-2-4" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="YRMV6HC">
        <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="XC1L88V"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img-fullwidth.jpg}}" alt="" data-element="desktop_image" data-pb-style="QRBSKDH"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img-fullwidth.jpg}}" alt="" data-element="mobile_image" data-pb-style="KH0CQYL"></figure>
        <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">Gale Library Wall Light by Thomas O’Brien | Photography By: @emilykennedyphoto</span></p>
        </div>
        <div class="quote" data-content-type="text" data-appearance="default" data-element="main">
            <p style="text-align: center;">“While most lighting selections tend to be restrained in style, the finishes and forms are always able to stand on their own with Visual Comfort.”</p>
        </div>
        <div class="author" data-content-type="text" data-appearance="default" data-element="main" data-pb-style="ONK1K9Y">
            <p>Kate Marker</p>
        </div>
        <div data-content-type="divider" data-appearance="default" data-element="main"><hr data-element="line" data-pb-style="T2R3HWA"></div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details slider block-2-5" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="L4M0EYJ">
        <h2 data-content-type="heading" data-appearance="default" data-element="main">You May Also Like</h2>
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="home-block"&gt; &lt;div class="fifth-block three-items-slider"&gt; &lt;div class="elements slick-slider _init-custom-slider product-slider desktop-slides-4 mobile-slides-2"&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt;</div>
    </div>
</div>
EOD;

        $pageData = [
            [
                'title' => 'VC Inspiration Details Ver 2',
                'page_layout' => 'cms-full-width',
                'identifier' => 'vc_inspiration_detail_template_2',
                'content' => $pageContent,
                'is_active' => 1,
                'stores' => [$this->getVcStoreId()],
                'sort_order' => 0
            ]
        ];

        /**
         * Insert default and system pages
         */
        foreach ($pageData as $data) {
            $this->upsertPage($data);
        }
    }
}