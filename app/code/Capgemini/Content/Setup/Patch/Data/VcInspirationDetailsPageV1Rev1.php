<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsPage;

/**
 * Class VcInspirationDetailsPageV1Rev1
 */
class VcInspirationDetailsPageV1Rev1 extends AbstractCmsPage
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        $pageContent = <<<EOD
<style>#html-body [data-pb-style=FM7MHPR]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=K9J5IBQ]{text-align:left}#html-body [data-pb-style=GFRE8JD]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=M70JPF6]{border-style:none}#html-body [data-pb-style=OXEMEX0],#html-body [data-pb-style=W2KTL17]{max-width:100%;height:auto}#html-body [data-pb-style=TMOTVRW]{margin-top:20px}#html-body [data-pb-style=DMIKFPQ],#html-body [data-pb-style=QWHQPN0]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=QWHQPN0]{justify-content:flex-start;display:flex;flex-direction:column}#html-body [data-pb-style=DMIKFPQ]{align-self:stretch}#html-body [data-pb-style=NNW7O3Y]{display:flex;width:100%}#html-body [data-pb-style=SSMXHES]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=L7QWL96]{border-style:none}#html-body [data-pb-style=C8DIV5A],#html-body [data-pb-style=GBXIJYN]{max-width:100%;height:auto}#html-body [data-pb-style=RU5LWQY]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=BKOGPOS]{border-style:none}#html-body [data-pb-style=D7RRP2F],#html-body [data-pb-style=P9MNW5O]{max-width:100%;height:auto}#html-body [data-pb-style=SY09TF1],#html-body [data-pb-style=UKBUP7I]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=SY09TF1]{justify-content:flex-start;display:flex;flex-direction:column}#html-body [data-pb-style=UKBUP7I]{align-self:stretch}#html-body [data-pb-style=LQCF4D9]{display:flex;width:100%}#html-body [data-pb-style=OM07NSF]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=PKBKAOG]{border-style:none}#html-body [data-pb-style=O8E2HA9],#html-body [data-pb-style=VVBT4EQ]{max-width:100%;height:auto}#html-body [data-pb-style=PS8EBF8]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=PQQCNJ3]{border-style:none}#html-body [data-pb-style=PETS2SE],#html-body [data-pb-style=QDHGB7I]{max-width:100%;height:auto}#html-body [data-pb-style=K44D9WW]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=KYSUANP]{border-style:none}#html-body [data-pb-style=M6WK6OT],#html-body [data-pb-style=WEJJB7W]{max-width:100%;height:auto}#html-body [data-pb-style=CTSRS14],#html-body [data-pb-style=HJW4LI5]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=V5NM1AE]{border-style:none}#html-body [data-pb-style=H0ORRAA],#html-body [data-pb-style=RC8FTEA]{max-width:100%;height:auto}#html-body [data-pb-style=J5O0VL8]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=F3FDU7N]{display:flex;width:100%}#html-body [data-pb-style=USR1YPD]{justify-content:center;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=NCEC2R4]{text-align:center}#html-body [data-pb-style=G3HV7QC]{justify-content:center;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=F5O0VV3]{width:100%;border-width:1px;border-color:#cecece;display:inline-block}#html-body [data-pb-style=L7NUV2U]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}@media only screen and (max-width: 768px) { #html-body [data-pb-style=BKOGPOS],#html-body [data-pb-style=KYSUANP],#html-body [data-pb-style=L7QWL96],#html-body [data-pb-style=M70JPF6],#html-body [data-pb-style=PKBKAOG],#html-body [data-pb-style=PQQCNJ3],#html-body [data-pb-style=V5NM1AE]{border-style:none} }</style>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details page-head block-1-1" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="FM7MHPR">
        <div class="highlight" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">DESIGN PROFILES</span></p>
        </div>
        <h1 data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="K9J5IBQ">Marie Flanigan's Favorite Aspects of a Room</h1>
        <div data-content-type="text" data-appearance="default" data-element="main">
            <p>Architect-turned-decorator Marie Flanigan celebrates the launch of her new Visual Comfort collection with a peek into her latest, and possibly brightest, project to date. Photography by Julie Soefer.</p>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details block-1-2" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="GFRE8JD">
        <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="M70JPF6"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/id-v1-hero.jpg}}" alt="" data-element="desktop_image" data-pb-style="OXEMEX0"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/id-v1-hero.jpg}}" alt="" data-element="mobile_image" data-pb-style="W2KTL17"></figure>
        <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">Devin Pendant by Sean Lavin, Rousseau Double Wall Sconce by Kelly Wearstler</span></p>
        </div>
        <div data-content-type="text" data-appearance="default" data-element="main" data-pb-style="TMOTVRW">
            <p>“Lighting is perhaps the single most transformative element in any environment. Its power to illuminate and animate—literally—shapes how we experience our surroundings,” muses Marie Flanigan. To celebrate the launch of her new collection with Visual Comfort, the lighting-obsessed, Houston-based designer exclusively shared one of her most potent design projects with us. Although Flanigan’s new fixtures were still on the drawing boards when this home was built, her less is more point-of-view is fully illuminated. Paging through the photos makes one wonder how her spaces simultaneously exude strength and serenity. <br><br>The secret is that Flanigan began her career as an architect but switched to design when she realized she couldn’t give up on the finishing details. She thinks and plans on a macro level and then hones in on finishes, furnishings, and lighting at a micro level creating holistically designed havens for clients. Melding modern and classic furnishings, polished marble with bare brick, and the functionality with the pretty excites Flanigan, but not as much as the influence lighting a house offers. “By incorporating recessed lights, pendants, and sconces in a room, you can change the whole atmosphere of the room with just a switch,” she says. Discover ten more of Flanigan’s brightest ideas for lighting and living.</p>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details no-description block-1-3" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="QWHQPN0">
        <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="DMIKFPQ">
            <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="NNW7O3Y">
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="SSMXHES">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="L7QWL96"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="desktop_image" data-pb-style="C8DIV5A"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="mobile_image" data-pb-style="GBXIJYN"></figure>
                    <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                        <p><span style="font-size: 10px;">Devin Pendant by Sean Lavin</span></p>
                    </div>
                    <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="placeholder"&gt;&lt;/div&gt;</div>
                </div>
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="RU5LWQY">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="BKOGPOS"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="desktop_image" data-pb-style="P9MNW5O"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="mobile_image" data-pb-style="D7RRP2F"></figure>
                    <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                        <p><span style="font-size: 10px;">Rousseau Double Wall Sconce by Kelly Wearstler</span></p>
                    </div>
                    <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="placeholder"&gt;&lt;/div&gt;</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details block-1-4" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="SY09TF1">
        <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="UKBUP7I">
            <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="LQCF4D9">
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="OM07NSF">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="PKBKAOG"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="desktop_image" data-pb-style="VVBT4EQ"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="mobile_image" data-pb-style="O8E2HA9"></figure>
                    <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                        <p><span style="font-size: 10px;">Shop a similar look: Gates Medium Rectangle Table Lamp by Marie Flanigan</span></p>
                    </div>
                    <h2 data-content-type="heading" data-appearance="default" data-element="main">Squared Away</h2>
                    <div data-content-type="text" data-appearance="default" data-element="main">
                        <p>We extended the barrel vaulted ceiling into the living room for added dimension in the expansive space. Lining up the barrels with the door and windows was key to the serenity of the room. Geometric table lamps enhance the room’s symmetry and movement.</p>
                    </div>
                </div>
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="PS8EBF8">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="PQQCNJ3"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="desktop_image" data-pb-style="QDHGB7I"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="mobile_image" data-pb-style="PETS2SE"></figure>
                    <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                        <p><span style="font-size: 10px;">Dover Floor Lamp by AERIN</span></p>
                    </div>
                    <h2 data-content-type="heading" data-appearance="default" data-element="main">Stand Tall and Proud</h2>
                    <div data-content-type="text" data-appearance="default" data-element="main">
                        <p>The long and lean, brass floor lamp draws eyes up to the tall planked ceiling and accents the fluted wood surrounding the fireplace in this sunroom.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details block-1-5" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="K44D9WW">
        <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="KYSUANP"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img-fullwidth.jpg}}" alt="" data-element="desktop_image" data-pb-style="M6WK6OT"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img-fullwidth.jpg}}" alt="" data-element="mobile_image" data-pb-style="WEJJB7W"></figure>
        <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">Metropolis Table Lamp by Ralph Lauren</span></p>
        </div>
        <h2 data-content-type="heading" data-appearance="default" data-element="main">Double the Lights for Two</h2>
        <div data-content-type="text" data-appearance="default" data-element="main">
            <p>Reading wall lamps are wonderful when one person wants to read while the other sleeps. The bedside lamps add depth and interest to the room.</p>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details block-1-6" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="CTSRS14">
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="home-block"&gt; &lt;div class="fifth-block three-items-slider"&gt; &lt;div class="elements slick-slider _init-custom-slider" data-content-type="3"&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/410x448" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;Shop a similar look: Gates Medium Rectangle Table Lamp by Marie Flanigan&lt;/span&gt; &lt;h4&gt;Squared Away&lt;/h4&gt; &lt;p&gt;We extended the barrel vaulted ceiling into the living room for added dimension in the expansive space. Lining up the barrels with the door and windows was key to the serenity of the room. Geometric table lamps enhance the room’s symmetry and movement.&lt;/p&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/410x448" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;Shop a similar look: Gates Medium Rectangle Table Lamp by Marie Flanigan&lt;/span&gt; &lt;h4&gt;New Neutral&lt;/h4&gt; &lt;p&gt;Blush is the new neutral, serving as the perfect backdrop for soft whites, natural tones, and warm gold hues. This serene color palette blends well with almost any décor and provides a sense of calm to all who walk through the space.&lt;/p&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/410x448" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;Shop a similar look: Gates Medium Rectangle Table Lamp by Marie Flanigan&lt;/span&gt; &lt;h4&gt;New Neutral&lt;/h4&gt; &lt;p&gt;Blush is the new neutral, serving as the perfect backdrop for soft whites, natural tones, and warm gold hues. This serene color palette blends well with almost any décor and provides a sense of calm to all who walk through the space.&lt;/p&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt;</div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details description-horizontal block-1-7" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="HJW4LI5">
        <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="V5NM1AE"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img-fullwidth-2.jpg}}" alt="" data-element="desktop_image" data-pb-style="H0ORRAA"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img-fullwidth-2.jpg}}" alt="" data-element="mobile_image" data-pb-style="RC8FTEA"></figure>
        <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="J5O0VL8">
            <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="F3FDU7N">
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="USR1YPD">
                    <h2 data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="NCEC2R4">Mirror, Mirror, on the Wall</h2>
                </div>
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="G3HV7QC">
                    <div data-content-type="text" data-appearance="default" data-element="main">
                        <p>When installing fixtures directly onto reflective surfaces like in this powder room, I select ones with light diffusing shades to avoid overly-bright, hot spots. These alabaster sconces were a beautiful way to achieve this.</p>
                    </div>
                </div>
            </div>
        </div>
        <div data-content-type="divider" data-appearance="default" data-element="main"><hr data-element="line" data-pb-style="F5O0VV3"></div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details block-1-8" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="L7NUV2U">
        <h2 data-content-type="heading" data-appearance="default" data-element="main">You May Also Like</h2>
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="home-block"&gt; &lt;div class="fifth-block three-items-slider"&gt; &lt;div class="elements slick-slider _init-custom-slider product-slider" data-content-type="4"&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/280" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;h4&gt;Anton Large Swing Arm Floor Lamp&lt;/h4&gt; &lt;span class="designer"&gt;Designer: Thomas O'Brien&lt;/span&gt; &lt;span class="price"&gt;$537.95&lt;/span&gt; &lt;a href="/link" class="view-more"&gt;View Additional Finishes&lt;/a&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt;</div>
    </div>
</div>
EOD;

        $pageData = [
            [
                'title' => 'VC Inspiration Details Ver 1',
                'page_layout' => 'cms-full-width',
                'identifier' => 'vc_inspiration_detail_template_1',
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