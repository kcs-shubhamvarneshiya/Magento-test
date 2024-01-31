<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsPage;

/**
 * Class VcInspirationDetailsPageV1
 */
class VcInspirationDetailsPageV1 extends AbstractCmsPage
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        $pageContent = <<<EOD
<style>#html-body [data-pb-style=AIRWGCP],#html-body [data-pb-style=F0LJD05],#html-body [data-pb-style=IJQQIRJ],#html-body [data-pb-style=JHVKHWU],#html-body [data-pb-style=KR26Y94],#html-body [data-pb-style=L6HO5S1],#html-body [data-pb-style=WGUVRDA],#html-body [data-pb-style=WOPUQAX]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=Q93VO07]{margin-top:20px}#html-body [data-pb-style=E629LTL]{text-align:left}#html-body [data-pb-style=X0VTOJT]{border-style:none}#html-body [data-pb-style=ET7W8B0],#html-body [data-pb-style=QOI69MV]{max-width:100%;height:auto}#html-body [data-pb-style=YJYA67U]{border-style:none}#html-body [data-pb-style=L4YSLRE],#html-body [data-pb-style=XFEERBT]{max-width:100%;height:auto}#html-body [data-pb-style=BCAMN3E]{border-style:none}#html-body [data-pb-style=JP5HQQQ],#html-body [data-pb-style=UVCQ1O7]{max-width:100%;height:auto}#html-body [data-pb-style=C7JK18E]{width:100%;border-width:1px;border-color:#cecece;display:inline-block}#html-body [data-pb-style=M04T6FW]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=VVK35XM]{border-style:none}#html-body [data-pb-style=L5HW4RP],#html-body [data-pb-style=RHR0AI8]{max-width:100%;height:auto}#html-body [data-pb-style=SA1IL3L]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=XN4BVPE]{border-style:none}#html-body [data-pb-style=E3HETSM],#html-body [data-pb-style=J1Q38DI]{max-width:100%;height:auto}#html-body [data-pb-style=MFTVR2Q]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=J5N6M7P]{border-style:none}#html-body [data-pb-style=BBHXI44],#html-body [data-pb-style=MGVIH66]{max-width:100%;height:auto}#html-body [data-pb-style=WCQVBMG]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=V1E9H9M]{border-style:none}#html-body [data-pb-style=DB36VLH],#html-body [data-pb-style=VDQ4PKN]{max-width:100%;height:auto}#html-body [data-pb-style=LESIXB7]{justify-content:center;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=K9HJFB7]{text-align:center}#html-body [data-pb-style=N87YB42]{justify-content:center;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}@media only screen and (max-width: 768px) { #html-body [data-pb-style=BCAMN3E],#html-body [data-pb-style=J5N6M7P],#html-body [data-pb-style=V1E9H9M],#html-body [data-pb-style=VVK35XM],#html-body [data-pb-style=X0VTOJT],#html-body [data-pb-style=XN4BVPE],#html-body [data-pb-style=YJYA67U]{border-style:none} }</style>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details page-head" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="AIRWGCP">
        <div class="highlight" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">DESIGN PROFILES</span></p>
        </div>
        <h1 data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="E629LTL">Marie Flanigan's Favorite Aspects of a Room</h1>
        <div data-content-type="text" data-appearance="default" data-element="main">
            <p>Architect-turned-decorator Marie Flanigan celebrates the launch of her new Visual Comfort collection with a peek into her latest, and possibly brightest, project to date. Photography by Julie Soefer.</p>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="WGUVRDA">
        <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="X0VTOJT"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/id-v1-hero.jpg}}" alt="" data-element="desktop_image" data-pb-style="ET7W8B0"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/id-v1-hero.jpg}}" alt="" data-element="mobile_image" data-pb-style="QOI69MV"></figure>
        <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">Devin Pendant by Sean Lavin, Rousseau Double Wall Sconce by Kelly Wearstler</span></p>
        </div>
        <div data-content-type="text" data-appearance="default" data-element="main" data-pb-style="Q93VO07">
            <p>“Lighting is perhaps the single most transformative element in any environment. Its power to illuminate and animate—literally—shapes how we experience our surroundings,” muses Marie Flanigan. To celebrate the launch of her new collection with Visual Comfort, the lighting-obsessed, Houston-based designer exclusively shared one of her most potent design projects with us. Although Flanigan’s new fixtures were still on the drawing boards when this home was built, her less is more point-of-view is fully illuminated. Paging through the photos makes one wonder how her spaces simultaneously exude strength and serenity.</p>
            <p>&nbsp;</p>
            <p>The secret is that Flanigan began her career as an architect but switched to design when she realized she couldn’t give up on the finishing details. She thinks and plans on a macro level and then hones in on finishes, furnishings, and lighting at a micro level creating holistically designed havens for clients. Melding modern and classic furnishings, polished marble with bare brick, and the functionality with the pretty excites Flanigan, but not as much as the influence lighting a house offers. “By incorporating recessed lights, pendants, and sconces in a room, you can change the whole atmosphere of the room with just a switch,” she says. Discover ten more of Flanigan’s brightest ideas for lighting and living.</p>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details no-description" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="F0LJD05">
        <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="M04T6FW">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="VVK35XM"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="desktop_image" data-pb-style="L5HW4RP"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="mobile_image" data-pb-style="RHR0AI8"></figure>
                <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                    <p><span style="font-size: 10px;">Devin Pendant by Sean Lavin</span></p>
                </div>
                <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="placeholder"&gt;&lt;/div&gt;</div>
            </div>
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="SA1IL3L">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="XN4BVPE"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="desktop_image" data-pb-style="E3HETSM"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="mobile_image" data-pb-style="J1Q38DI"></figure>
                <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                    <p><span style="font-size: 10px;">Rousseau Double Wall Sconce by Kelly Wearstler</span></p>
                </div>
                <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="placeholder"&gt;&lt;/div&gt;</div>
            </div>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="IJQQIRJ">
        <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="MFTVR2Q">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="J5N6M7P"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="desktop_image" data-pb-style="BBHXI44"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img.jpg}}" alt="" data-element="mobile_image" data-pb-style="MGVIH66"></figure>
                <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
                    <p><span style="font-size: 10px;">Shop a similar look: Gates Medium Rectangle Table Lamp by Marie Flanigan</span></p>
                </div>
                <h2 data-content-type="heading" data-appearance="default" data-element="main">Squared Away</h2>
                <div data-content-type="text" data-appearance="default" data-element="main">
                    <p>We extended the barrel vaulted ceiling into the living room for added dimension in the expansive space. Lining up the barrels with the door and windows was key to the serenity of the room. Geometric table lamps enhance the room’s symmetry and movement.</p>
                </div>
            </div>
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="WCQVBMG">
                <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="V1E9H9M"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="desktop_image" data-pb-style="VDQ4PKN"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img_1.jpg}}" alt="" data-element="mobile_image" data-pb-style="DB36VLH"></figure>
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
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="KR26Y94">
        <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="YJYA67U"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img-fullwidth.jpg}}" alt="" data-element="desktop_image" data-pb-style="L4YSLRE"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img-fullwidth.jpg}}" alt="" data-element="mobile_image" data-pb-style="XFEERBT"></figure>
        <div class="img-note" data-content-type="text" data-appearance="default" data-element="main">
            <p><span style="font-size: 10px;">Shop a similar look: Gates Medium Rectangle Table Lamp by Marie Flanigan</span></p>
        </div>
        <h2 data-content-type="heading" data-appearance="default" data-element="main">Squared Away</h2>
        <div data-content-type="text" data-appearance="default" data-element="main">
            <p>We extended the barrel vaulted ceiling into the living room for added dimension in the expansive space. Lining up the barrels with the door and windows was key to the serenity of the room. Geometric table lamps enhance the room’s symmetry and movement.</p>
        </div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="WOPUQAX">
        <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="home-block"&gt; &lt;div class="fifth-block three-items-slider"&gt; &lt;div class="elements slick-slider _init-custom-slider" data-content-type="3"&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/410x448" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;Shop a similar look: Gates Medium Rectangle Table Lamp by Marie Flanigan&lt;/span&gt; &lt;h4&gt;Squared Away&lt;/h4&gt; &lt;p&gt;We extended the barrel vaulted ceiling into the living room for added dimension in the expansive space. Lining up the barrels with the door and windows was key to the serenity of the room. Geometric table lamps enhance the room’s symmetry and movement.&lt;/p&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/410x448" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;Shop a similar look: Gates Medium Rectangle Table Lamp by Marie Flanigan&lt;/span&gt; &lt;h4&gt;New Neutral&lt;/h4&gt; &lt;p&gt;Blush is the new neutral, serving as the perfect backdrop for soft whites, natural tones, and warm gold hues. This serene color palette blends well with almost any décor and provides a sense of calm to all who walk through the space.&lt;/p&gt; &lt;/div&gt; &lt;div class="element"&gt; &lt;div class="image"&gt; &lt;a href="/link"&gt; &lt;img src="//via.placeholder.com/410x448" alt="Alt text goes here"&gt; &lt;/a&gt; &lt;/div&gt; &lt;span class="highlight"&gt;Shop a similar look: Gates Medium Rectangle Table Lamp by Marie Flanigan&lt;/span&gt; &lt;h4&gt;New Neutral&lt;/h4&gt; &lt;p&gt;Blush is the new neutral, serving as the perfect backdrop for soft whites, natural tones, and warm gold hues. This serene color palette blends well with almost any décor and provides a sense of calm to all who walk through the space.&lt;/p&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt; &lt;/div&gt;</div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details description-horizontal" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="L6HO5S1">
        <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="BCAMN3E"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/article-img-fullwidth-2.jpg}}" alt="" data-element="desktop_image" data-pb-style="UVCQ1O7"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/article-img-fullwidth-2.jpg}}" alt="" data-element="mobile_image" data-pb-style="JP5HQQQ"></figure>
        <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="LESIXB7">
                <h2 data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="K9HJFB7">Mirror, Mirror, on the Wall</h2>
            </div>
            <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="N87YB42">
                <div data-content-type="text" data-appearance="default" data-element="main">
                    <p>When installing fixtures directly onto reflective surfaces like in this powder room, I select ones with light diffusing shades to avoid overly-bright, hot spots. These alabaster sconces were a beautiful way to achieve this.</p>
                </div>
            </div>
        </div>
        <div data-content-type="divider" data-appearance="default" data-element="main"><hr data-element="line" data-pb-style="C7JK18E"></div>
    </div>
</div>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div class="inspiration-details" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="JHVKHWU">
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