<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsPage;

/**
 * Class VcInspirationLandingPage
 */
class VcInspirationLandingPage extends AbstractCmsPage
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        $pageContent = <<<EOD
<style>#html-body [data-pb-style=I9FT892],#html-body [data-pb-style=NP79P8S],#html-body [data-pb-style=O3V0B3T],#html-body [data-pb-style=W0LR9VT]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=AAUQ770]{text-align:center}#html-body [data-pb-style=D56Y4SI],#html-body [data-pb-style=DW7B0Q0],#html-body [data-pb-style=ENUHB97],#html-body [data-pb-style=RC9BL8P],#html-body [data-pb-style=T4S3ACK]{width:100%;border-width:1px;border-color:#cecece;display:inline-block}#html-body [data-pb-style=TPI43LO]{border-style:none}#html-body [data-pb-style=B644DNG],#html-body [data-pb-style=BMQXJ6Y]{max-width:100%;height:auto}#html-body [data-pb-style=D4WWUAG],#html-body [data-pb-style=QGR7T6I],#html-body [data-pb-style=QUXCEQB]{text-align:center}#html-body [data-pb-style=CBYDLOE]{display:inline-block}#html-body [data-pb-style=GGW4AB9]{text-align:center}#html-body [data-pb-style=DD12USU]{display:inline-block}#html-body [data-pb-style=LRWV8V0]{text-align:center}#html-body [data-pb-style=L608KIG]{display:inline-block}#html-body [data-pb-style=G5K629P]{text-align:center}#html-body [data-pb-style=FEFOFCY]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=WPPD0YH]{border-style:none}#html-body [data-pb-style=FH442K1],#html-body [data-pb-style=U0DISM0]{max-width:100%;height:auto}#html-body [data-pb-style=UMK2J39]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=E2OUQLL]{border-style:none}#html-body [data-pb-style=I4IDT3P],#html-body [data-pb-style=POLPLSM]{max-width:100%;height:auto}#html-body [data-pb-style=YIAYQHK]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=W1LW10D]{border-style:none}#html-body [data-pb-style=KK8R68M],#html-body [data-pb-style=VFVOINC]{max-width:100%;height:auto}#html-body [data-pb-style=DUCL58C]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=AV037FH]{border-style:none}#html-body [data-pb-style=QGQ142B],#html-body [data-pb-style=SNNI6GQ]{max-width:100%;height:auto}@media only screen and (max-width: 768px) { #html-body [data-pb-style=AV037FH],#html-body [data-pb-style=E2OUQLL],#html-body [data-pb-style=TPI43LO],#html-body [data-pb-style=W1LW10D],#html-body [data-pb-style=WPPD0YH]{border-style:none} }</style>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-landing anchor-menu" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="I9FT892">
        <h1 data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="AAUQ770">Inspiration</h1>
        <div data-content-type="divider" data-appearance="default" data-element="main"><hr data-element="line" data-pb-style="ENUHB97"></div>
        <div data-content-type="text" data-appearance="default" data-element="main">
            <p style="text-align: center;"><a tabindex="0" href="#spotlight">Spotlight</a> <a tabindex="0" href="#resources">Resources</a> <a tabindex="0" href="#tips-tricks">Tips &amp; Tricks</a></p>
        </div>
        <div data-content-type="divider" data-appearance="default" data-element="main"><hr data-element="line" data-pb-style="T4S3ACK"></div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-landing" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="W0LR9VT">
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div id="spotlight"&gt;&lt;/div&gt;</div>
        <h2 data-content-type="heading" data-appearance="default" data-element="main">Spotlight</h2>
        <figure class="hero-img" data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="TPI43LO"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/spotlight_hero.jpg}}" alt="" data-element="desktop_image" data-pb-style="B644DNG"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/spotlight_hero-mobile.png}}" alt="" data-element="mobile_image" data-pb-style="BMQXJ6Y"></figure>
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="home-block"&gt; &lt;div class="fifth-block three-items-slider"&gt; &lt;div class="elements slick-slider _init-custom-slider" data-content-type="3"&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="{{media url=wysiwyg/spotlight-slider-1.png}}" alt="Marie Flanigan's Favorite" /&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;Marie Flanigan's Favorite&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum. Mauris id aliquam mauris. Ut sit amet urna arcu.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="{{media url=wysiwyg/spotlight-slider-2.png}}" alt="Talking Shop with Stoffer Home" /&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;Talking Shop with Stoffer Home&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum. Mauris id aliquam mauris. Ut sit amet urna arcu.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="{{media url=wysiwyg/spotlight-slider-3.png}}" alt="The Francesco Series" /&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;The Francesco Series&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum. Mauris id aliquam mauris. Ut sit amet urna arcu.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt;</div>
        <div data-content-type="buttons" data-appearance="inline" data-same-width="false" data-element="main" data-pb-style="QGR7T6I">
            <div data-content-type="button-item" data-appearance="default" data-element="main" data-pb-style="CBYDLOE"><a class="pagebuilder-button-secondary" href="/spotlight" target="" data-link-type="default" data-element="link" data-pb-style="GGW4AB9"><span data-element="link_text">View more</span></a></div>
        </div>
        <div data-content-type="divider" data-appearance="default" data-element="main"><hr data-element="line" data-pb-style="DW7B0Q0"></div>
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div id="resources"&gt;&lt;/div&gt;</div>
        <h2 data-content-type="heading" data-appearance="default" data-element="main">Resources</h2>
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="home-block"&gt; &lt;div class="fifth-block four-items-slider"&gt; &lt;div class="elements slick-slider _init-custom-slider" data-content-type="4"&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/300x365" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;Headline Goes Here&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/300x365" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;Headline Goes Here&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/300x365" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;Headline Goes Here&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/300x365" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;Headline Goes Here&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt;</div>
        <div data-content-type="buttons" data-appearance="inline" data-same-width="false" data-element="main" data-pb-style="QUXCEQB">
            <div data-content-type="button-item" data-appearance="default" data-element="main" data-pb-style="DD12USU"><a class="pagebuilder-button-secondary" href="/spotlight" target="" data-link-type="default" data-element="link" data-pb-style="LRWV8V0"><span data-element="link_text">View more</span></a></div>
        </div>
        <div data-content-type="divider" data-appearance="default" data-element="main"><hr data-element="line" data-pb-style="D56Y4SI"></div>
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div id="tips-tricks"&gt;&lt;/div&gt;</div>
        <h2 data-content-type="heading" data-appearance="default" data-element="main">Tips &amp; Tricks</h2>
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="home-block"&gt; &lt;div class="fifth-block four-items-slider"&gt; &lt;div class="elements slick-slider _init-custom-slider" data-content-type="4"&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/300x365" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;Headline Goes Here&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/300x365" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;Headline Goes Here&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/300x365" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;Headline Goes Here&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/300x365" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;DESIGN PROFILES&lt;/span&gt; &lt;h4&gt;Headline Goes Here&lt;/h4&gt; &lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum.&lt;/p&gt; &lt;a href="/link"&gt;Read More&lt;/a&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt;</div>
        <div data-content-type="buttons" data-appearance="inline" data-same-width="false" data-element="main" data-pb-style="D4WWUAG">
            <div data-content-type="button-item" data-appearance="default" data-element="main" data-pb-style="L608KIG"><a class="pagebuilder-button-secondary" href="/spotlight" target="" data-link-type="default" data-element="link" data-pb-style="G5K629P"><span data-element="link_text">View more</span></a></div>
        </div>
        <div data-content-type="divider" data-appearance="default" data-element="main"><hr data-element="line" data-pb-style="RC9BL8P"></div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-landing grid" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="O3V0B3T">
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div id="inspiration"&gt;&lt;/div&gt;</div>
        <h2 data-content-type="heading" data-appearance="default" data-element="main">Inspiration</h2>
        <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="FEFOFCY">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="WPPD0YH"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/gallery-300.png}}" alt="" data-element="desktop_image" data-pb-style="FH442K1"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/gallery-300.png}}" alt="" data-element="mobile_image" data-pb-style="U0DISM0"></figure>
            </div>
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="UMK2J39">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="E2OUQLL"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/gallery-300_1.png}}" alt="" data-element="desktop_image" data-pb-style="POLPLSM"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/gallery-300_1.png}}" alt="" data-element="mobile_image" data-pb-style="I4IDT3P"></figure>
            </div>
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="YIAYQHK">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="W1LW10D"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/gallery-300_2.png}}" alt="" data-element="desktop_image" data-pb-style="KK8R68M"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/gallery-300_2.png}}" alt="" data-element="mobile_image" data-pb-style="VFVOINC"></figure>
            </div>
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="DUCL58C">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="AV037FH"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/gallery-300_3.png}}" alt="" data-element="desktop_image" data-pb-style="QGQ142B"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/gallery-300_3.png}}" alt="" data-element="mobile_image" data-pb-style="SNNI6GQ"></figure>
            </div>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-landing" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="NP79P8S">
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="social-icons"&gt; &lt;a href="https://www.facebook.com/circalighting/" class="icon-facebook"&gt;&lt;/a&gt; &lt;a href="https://www.pinterest.com/circalighting/" class="icon-pinterest"&gt;&lt;/a&gt; &lt;a href="https://twitter.com/circalighting" class="icon-twitter"&gt;&lt;/a&gt; &lt;a href="https://www.instagram.com/circalighting/" class="icon-ig"&gt;&lt;/a&gt; &lt;a href="mailto:visualcomfort@test.com/" class="icon-email"&gt;&lt;/a&gt; &lt;/div&gt;</div>
    </div>
</div>
EOD;

        $pageData = [
            [
                'title' => 'VC Inspiration Landing',
                'page_layout' => 'cms-full-width',
                'identifier' => 'vc_inspiration_landing',
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