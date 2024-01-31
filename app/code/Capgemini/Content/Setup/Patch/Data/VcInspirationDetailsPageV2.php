<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsPage;

/**
 * Class VcInspirationDetailsPageV2
 */
class VcInspirationDetailsPageV2 extends AbstractCmsPage
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        $pageContent = <<<EOD
<style>#html-body [data-pb-style=RY61KDV]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=YCJVB98]{text-align:left}#html-body [data-pb-style=JBJS9FN],#html-body [data-pb-style=OVY5G8W]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=OVY5G8W]{width:50%;align-self:stretch}#html-body [data-pb-style=I9FX5W4]{border-style:none}#html-body [data-pb-style=LNGE51K],#html-body [data-pb-style=UM8GYDW]{max-width:100%;height:auto}#html-body [data-pb-style=JUO3W5A]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=RHLLBYL]{border-style:none}#html-body [data-pb-style=BDRUEBJ],#html-body [data-pb-style=SPL8UBA]{max-width:100%;height:auto}#html-body [data-pb-style=MNV1N6P]{text-align:center}#html-body [data-pb-style=IK2QTY9],#html-body [data-pb-style=QBJLKHV]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=QBJLKHV]{width:50%;align-self:stretch}#html-body [data-pb-style=XTIOR37]{border-style:none}#html-body [data-pb-style=B3NRYU7],#html-body [data-pb-style=FF2ERBP]{max-width:100%;height:auto}#html-body [data-pb-style=D0YT0M4]{text-align:center}#html-body [data-pb-style=UL2WITE]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=O7KSW31]{border-style:none}#html-body [data-pb-style=D5DM96L],#html-body [data-pb-style=P4NS2DB]{max-width:100%;height:auto}#html-body [data-pb-style=E7TX1LF]{text-align:center}#html-body [data-pb-style=E130N3L]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=GT10GS1]{border-style:none}#html-body [data-pb-style=FYWL0KV],#html-body [data-pb-style=Q9XMXRU]{max-width:100%;height:auto}#html-body [data-pb-style=CICN6FQ]{text-align:center}#html-body [data-pb-style=BNLBUXD]{width:100%;border-width:1px;border-color:#cecece;display:inline-block}#html-body [data-pb-style=CU54I8K]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}@media only screen and (max-width: 768px) { #html-body [data-pb-style=GT10GS1],#html-body [data-pb-style=I9FX5W4],#html-body [data-pb-style=O7KSW31],#html-body [data-pb-style=RHLLBYL],#html-body [data-pb-style=XTIOR37]{border-style:none} }</style>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details page-head" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="RY61KDV">
        <div class="highlight" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">DESIGN PROFILES</span></p>
        </div>
        <h1 data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="YCJVB98">Mudroom Solutions from Kate Marker Interiors</h1>
        <div data-content-type="text" data-appearance="default" data-element="main">
            <p>Mudrooms are high-traffic, functional spaces that are often overlooked. They keep the rest of the house looking clean, with racks for shoes, hooks for jackets, and catchall storage.<br>See how designer Kate Marker transforms mudrooms into stylish and organized home hubs.</p>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details no-description" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="JBJS9FN">
        <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="OVY5G8W">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="I9FX5W4"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="desktop_image" data-pb-style="LNGE51K"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="mobile_image" data-pb-style="UM8GYDW"></figure>
            </div>
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="JUO3W5A">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="RHLLBYL"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="desktop_image" data-pb-style="SPL8UBA"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="mobile_image" data-pb-style="BDRUEBJ"></figure>
            </div>
        </div>
        <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">Gale Library Wall Light by Thomas O’Brien | Photography By: @emilykennedyphoto</span></p>
        </div>
        <div class="quote" data-content-type="text" data-appearance="default" data-element="main">
            <p style="text-align: center;">“While most lighting selections tend to be restrained in style, the finishes and forms are always able to stand on their own with Visual Comfort.”</p>
        </div>
        <div class="author" data-content-type="text" data-appearance="default" data-element="main" data-pb-style="MNV1N6P">
            <p>Kate Marker</p>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="IK2QTY9">
        <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="QBJLKHV">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="XTIOR37"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="desktop_image" data-pb-style="B3NRYU7"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="mobile_image" data-pb-style="FF2ERBP"></figure>
                <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                    <p><span style="font-size: 10px;">Morris Large Flush Mount by Suzanne Kasler | Photography: Courtesy of Kate Marker Interiors</span></p>
                </div>
                <div class="quote" data-content-type="text" data-appearance="default" data-element="main">
                    <p style="text-align: center;">“Beautiful lighting helps to elevate a mudroom from merely a drop zone to a room deserving as much (if not more!) attention to detail as the rest of the house.”</p>
                </div>
                <div class="author" data-content-type="text" data-appearance="default" data-element="main" data-pb-style="D0YT0M4">
                    <p>Kate Marker</p>
                </div>
            </div>
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="UL2WITE">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="O7KSW31"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="desktop_image" data-pb-style="P4NS2DB"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="mobile_image" data-pb-style="D5DM96L"></figure>
                <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                    <p><span style="font-size: 10px;">McCarren Small Flush Mount by Ralph Lauren | Photography By: @stofferphotographyinteriors</span></p>
                </div>
                <div class="quote" data-content-type="text" data-appearance="default" data-element="main">
                    <p style="text-align: center;">“Done well, a mudroom will be utilized far more than many other rooms in a home by all members of the family and the design, including the lighting plan, should reflect that.”</p>
                </div>
                <div class="author" data-content-type="text" data-appearance="default" data-element="main" data-pb-style="E7TX1LF">
                    <p>Kate Marker</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="E130N3L">
        <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="GT10GS1"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img-fullwidth.jpg}}" alt="" data-element="desktop_image" data-pb-style="FYWL0KV"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img-fullwidth.jpg}}" alt="" data-element="mobile_image" data-pb-style="Q9XMXRU"></figure>
        <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">Gale Library Wall Light by Thomas O’Brien | Photography By: @emilykennedyphoto</span></p>
        </div>
        <div class="quote" data-content-type="text" data-appearance="default" data-element="main">
            <p style="text-align: center;">“While most lighting selections tend to be restrained in style, the finishes and forms are always able to stand on their own with Visual Comfort.”</p>
        </div>
        <div class="author" data-content-type="text" data-appearance="default" data-element="main" data-pb-style="CICN6FQ">
            <p>Kate Marker</p>
        </div>
        <div data-content-type="divider" data-appearance="default" data-element="main"><hr data-element="line" data-pb-style="BNLBUXD"></div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details slider" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="CU54I8K">
        <h2 data-content-type="heading" data-appearance="default" data-element="main">You May Also Like</h2>
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="home-block"&gt; &lt;div class="fifth-block three-items-slider"&gt; &lt;div class="elements slick-slider _init-custom-slider product-slider" data-content-type="4"&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt;</div>
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