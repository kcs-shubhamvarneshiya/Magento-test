<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsPage;

/**
 * Class VcHomePage
 */
class VcHomePageV1 extends AbstractCmsPage
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        $homepageContent = <<<EOD
<style>#html-body [data-pb-style=VALP3AX]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;margin-bottom:55px}#html-body [data-pb-style=SF2QI50]{border-style:none}#html-body [data-pb-style=IUES451],#html-body [data-pb-style=N8K3EMS]{max-width:100%;height:auto}#html-body [data-pb-style=XTP5SFP]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:100%;align-self:stretch}#html-body [data-pb-style=C86M4SP]{margin-top:25px}#html-body [data-pb-style=P1YEPCF]{width:100%;border-width:1px;border-color:#ccc;display:inline-block}#html-body [data-pb-style=INP28IK]{margin-left:15px;margin-right:15px;margin-bottom:40px}#html-body [data-pb-style=INP28IK],#html-body [data-pb-style=OUCTOPA]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=MOH3D7C]{border-style:none}#html-body [data-pb-style=JDT58MD],#html-body [data-pb-style=KPX22P2]{max-width:100%;height:auto}#html-body [data-pb-style=GAYSQ2D],#html-body [data-pb-style=RSASKF9]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=GAYSQ2D]{margin-top:80px}#html-body [data-pb-style=RSASKF9]{width:calc(33.3333% - 30px);margin-left:15px;margin-right:15px;align-self:stretch}#html-body [data-pb-style=P55YJNU]{width:66.6667%;align-self:stretch}#html-body [data-pb-style=NC2PH4A],#html-body [data-pb-style=P55YJNU],#html-body [data-pb-style=VBK8SR3]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=NC2PH4A]{margin-top:40px}#html-body [data-pb-style=VBK8SR3]{width:50%;align-self:stretch}#html-body [data-pb-style=R17AOVG]{border-style:none}#html-body [data-pb-style=CRE2TK7],#html-body [data-pb-style=GJEHUIR]{max-width:100%;height:auto}#html-body [data-pb-style=FXA22QM]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=N4Y4H2Q]{border-style:none}#html-body [data-pb-style=OITO2V4],#html-body [data-pb-style=YLNYL7D]{max-width:100%;height:auto}#html-body [data-pb-style=B0YFPG8],#html-body [data-pb-style=O5PMPWR],#html-body [data-pb-style=QPXO4QY]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=O5PMPWR],#html-body [data-pb-style=QPXO4QY]{align-self:stretch}#html-body [data-pb-style=O5PMPWR]{width:calc(33.3333% - 30px);margin-left:15px;margin-right:15px}#html-body [data-pb-style=QPXO4QY]{width:66.6667%}#html-body [data-pb-style=SA9LBQK]{border-style:none}#html-body [data-pb-style=UIURY9E],#html-body [data-pb-style=YP89N59]{max-width:100%;height:auto}#html-body [data-pb-style=HY6MFKF]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;margin-bottom:35px}#html-body [data-pb-style=LWFG789]{border-style:none}#html-body [data-pb-style=GF056YC],#html-body [data-pb-style=YX9HK1X]{max-width:100%;height:auto}#html-body [data-pb-style=M9Q4AXR]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}@media only screen and (max-width: 768px) { #html-body [data-pb-style=LWFG789],#html-body [data-pb-style=MOH3D7C],#html-body [data-pb-style=N4Y4H2Q],#html-body [data-pb-style=R17AOVG],#html-body [data-pb-style=SA9LBQK],#html-body [data-pb-style=SF2QI50]{border-style:none} }</style><div class="homepage-designer-1-container" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="VALP3AX"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="SF2QI50"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/vc-homepage/vc-designer-1.png}}" alt="" title="" data-element="desktop_image" data-pb-style="IUES451"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/vc-homepage/vc-designer-1-mobile.png}}" alt="" title="" data-element="mobile_image" data-pb-style="N8K3EMS"></figure><div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main"><div class="pagebuilder-column homepage-designer-1-col" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="XTP5SFP"><h2 class="homepage-designer-1__heading" data-content-type="heading" data-appearance="default" data-element="main">Kelly Wearstler</h2><div class="homepage-designer-1__text" data-content-type="text" data-appearance="default" data-element="main"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi vehicula nibh eu purus rhoncus porta. Curabitur mattis risus non bibendum vestibulum. Morbi nisi lorem, rutrum sed scelerisque eget, rutrum nec neque.</p></div><div class="homepage-designer-1__links" data-content-type="html" data-appearance="default" data-element="main">&lt;a href="#"&gt;Shop the Collection&lt;/a&gt;
&lt;a href="#"&gt;Shop New Arrivals&lt;/a&gt;</div><div data-content-type="divider" data-appearance="default" data-element="main" data-pb-style="C86M4SP"><hr data-element="line" data-pb-style="P1YEPCF"></div></div></div></div><div data-content-type="row" data-appearance="contained" data-element="main"><div class="homepage-explore-products-container" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="INP28IK"><h2 data-content-type="heading" data-appearance="default" data-element="main">Explore Our Products</h2><div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="explore-products-grid"&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-explore-ceiling.png}}" alt="" /&gt;
        &lt;span&gt;Ceiling&lt;/span&gt;
    &lt;/a&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-explore-table.png}}" alt="" /&gt;
        &lt;span&gt;Table&lt;/span&gt;
    &lt;/a&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-explore-floor.png}}" alt="" /&gt;
        &lt;span&gt;Floor&lt;/span&gt;
    &lt;/a&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-explore-wall.png}}" alt="" /&gt;
        &lt;span&gt;Wall&lt;/span&gt;
    &lt;/a&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-explore-outdoor.png}}" alt="" /&gt;
        &lt;span&gt;Outdoor&lt;/span&gt;
    &lt;/a&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-explore-ceiling-fans.png}}" alt="" /&gt;
        &lt;span&gt;Ceiling Fans&lt;/span&gt;
    &lt;/a&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-explore-architectural.png}}" alt="" /&gt;
        &lt;span&gt;Architectural&lt;/span&gt;
    &lt;/a&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-explore-systems.png}}" alt="" /&gt;
        &lt;span&gt;Systems&lt;/span&gt;
    &lt;/a&gt;
&lt;/div&gt;</div><div data-content-type="html" data-appearance="default" data-element="main"></div></div></div><div class="homepage-designer-2-container" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="OUCTOPA"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="MOH3D7C"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/vc-homepage/homepage-designer-2.png}}" alt="" title="" data-element="desktop_image" data-pb-style="KPX22P2"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/vc-homepage/homepage-designer-2-mobile.png}}" alt="" title="" data-element="mobile_image" data-pb-style="JDT58MD"></figure><div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="homepage-designer-2-subcontainer"&gt;
    &lt;div class="homepage-designer-2-subcontainer-col1"&gt;
        &lt;h2 class="homepage-designer-2-title"&gt;Kelly Wearstler&lt;/h2&gt;
        &lt;p class="homepage-designer-2-subtitle"&gt;Styling Secrets&lt;/p&gt;
    &lt;/div&gt;
    &lt;div class="homepage-designer-2-subcontainer-col2"&gt;
        &lt;p class="homepage-designer-2-content"&gt;Designer Kelly Wearstler shares her styling secrets for neutral spaces&lt;/p&gt;
        &lt;a href="#" class="homepage-designer-2-link"&gt;Read the Interview &amp; Shop Kelly’s Product&lt;/a&gt;
    &lt;/div&gt;
&lt;/div&gt;</div></div><div data-content-type="row" data-appearance="contained" data-element="main"><div class="homepage-new-in-container" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="GAYSQ2D"><div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main"><div class="pagebuilder-column homepage-new-in-col1" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="RSASKF9"><div class="homepage-designer-2__pre-heading" data-content-type="text" data-appearance="default" data-element="main"><p>70 New Items</p></div><h2 class="homepage-designer-2__heading" data-content-type="heading" data-appearance="default" data-element="main">New In</h2><div class="homepage-designer-2__content" data-content-type="text" data-appearance="default" data-element="main"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p></div><div data-content-type="html" data-appearance="default" data-element="main">&lt;a href="#" class="btn btn-secondary homepage-designer-2__link"&gt;Shop new arrivals&lt;/a&gt;</div></div><div class="pagebuilder-column homepage-new-in-col2" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="P55YJNU"><div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="homepage-new-in-image-container"&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-new-in-kelly-wearstler.png}}" alt="" /&gt;
        &lt;p&gt;Kelly Wearstler&lt;/p&gt;
    &lt;/a&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-new-in-avroko.png}}" alt="" /&gt;
        &lt;p&gt;Avroko&lt;/p&gt;
    &lt;/a&gt;
    &lt;a href="#"&gt;
        &lt;img src="{{media url=wysiwyg/vc-homepage/homepage-new-in-julie-neill.png}}" alt="" /&gt;
        &lt;p&gt;Julie Neill&lt;/p&gt;
    &lt;/a&gt;
&lt;/div&gt;</div></div></div></div></div><div data-content-type="row" data-appearance="contained" data-element="main"><div class="homepage-content-tile-container" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="NC2PH4A"><div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main"><div class="pagebuilder-column homepage-content-tile-col1" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="VBK8SR3"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="R17AOVG"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/vc-homepage/homepage-content-tile-1.png}}" alt="" title="" data-element="desktop_image" data-pb-style="GJEHUIR"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/vc-homepage/homepage-content-tile-1-mobile.png}}" alt="" title="" data-element="mobile_image" data-pb-style="CRE2TK7"></figure><h2 class="homepage-content-tile__heading" data-content-type="heading" data-appearance="default" data-element="main">AVRO | KO</h2><div class="homepage-content-tile__content" data-content-type="text" data-appearance="default" data-element="main"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud…</p></div><div data-content-type="html" data-appearance="default" data-element="main">&lt;a href="#" class="homepage-content-tile__link"&gt;Read More&lt;/a&gt;</div></div><div class="pagebuilder-column homepage-content-tile-col2" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="FXA22QM"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="N4Y4H2Q"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/vc-homepage/homepage-content-tile-2.png}}" alt="" title="" data-element="desktop_image" data-pb-style="YLNYL7D"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/vc-homepage/homepage-content-tile-2-mobile.png}}" alt="" title="" data-element="mobile_image" data-pb-style="OITO2V4"></figure><h2 class="homepage-content-tile__heading" data-content-type="heading" data-appearance="default" data-element="main">In the Studio with Julie Neill</h2><div class="homepage-content-tile__content" data-content-type="text" data-appearance="default" data-element="main"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud…</p></div><div data-content-type="html" data-appearance="default" data-element="main">&lt;a href="#" class="homepage-content-tile__link"&gt;Read More&lt;/a&gt;</div></div></div></div></div><div data-content-type="row" data-appearance="contained" data-element="main"><div class="homepage-top-sellers-container" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="B0YFPG8"><div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main"><div class="pagebuilder-column homepage-top-sellers-col-1" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="O5PMPWR"><div class="homepage-top-sellers__pre-heading" data-content-type="text" data-appearance="default" data-element="main"><p>70 New Items</p></div><h2 class="homepage-top-sellers__heading" data-content-type="heading" data-appearance="default" data-element="main">Top Sellers</h2><div class="homepage-top-sellers__text" data-content-type="text" data-appearance="default" data-element="main"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p></div><div data-content-type="html" data-appearance="default" data-element="main">&lt;a href="#" class="btn btn-secondary homepage-top-sellers__link"&gt;Shop Top Sellers&lt;/a&gt;</div></div><div class="pagebuilder-column homepage-top-sellers-col-2" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="QPXO4QY"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="SA9LBQK"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/vc-homepage/homepage-top-sellers.png}}" alt="" title="" data-element="desktop_image" data-pb-style="YP89N59"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/vc-homepage/homepage-top-sellers-mobile.png}}" alt="" title="" data-element="mobile_image" data-pb-style="UIURY9E"></figure></div></div></div></div><div class="homepage-experience-visual-comfort-container" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="HY6MFKF"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="LWFG789"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/vc-homepage/homepage-experience-visual-comfort.png}}" alt="" title="" data-element="desktop_image" data-pb-style="YX9HK1X"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/vc-homepage/homepage-experience-visual-comfort-mobile.png}}" alt="" title="" data-element="mobile_image" data-pb-style="GF056YC"></figure><div data-content-type="html" data-appearance="default" data-element="main">&lt;h2&gt;Experience&lt;span&gt;Visual Comfort&lt;/span&gt;&lt;/h2&gt;</div></div><div data-content-type="row" data-appearance="contained" data-element="main"><div class="homepage-olapic" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="M9Q4AXR"><div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="homepage-olapic-heading-container"&gt;
    &lt;h2 class="homepage-olapic-header"&gt;#visualcomfort&lt;/h2&gt;
    &lt;a class="homepage-olapic-see-more" href="#"&gt;See More Inspiration&lt;/a&gt;
&lt;/div&gt;

&lt;div class="homepage-olapic-grid"&gt;
    &lt;img class="homepage-olapic-grid-1" src="{{media url=wysiwyg/vc-homepage/olapic1.png}}" alt="" /&gt;
    &lt;img class="homepage-olapic-grid-2" src="{{media url=wysiwyg/vc-homepage/olapic2.png}}" alt="" /&gt;
    &lt;img class="homepage-olapic-grid-3" src="{{media url=wysiwyg/vc-homepage/olapic3.png}}" alt="" /&gt;
    &lt;img class="homepage-olapic-grid-4" src="{{media url=wysiwyg/vc-homepage/olapic4.png}}" alt="" /&gt;
&lt;/div&gt;</div></div></div>
EOD;

        $cmsPages = [
            [
                'title' => 'VC Homepage',
                'page_layout' => 'cms-full-width',
                'identifier' => 'vc-homepage-slider',
                'content' => $homepageContent,
                'is_active' => 1,
                'stores' => [$this->getVcStoreId()],
                'sort_order' => 0
            ]
        ];

        /**
         * Insert default and system pages
         */
        foreach ($cmsPages as $data) {
            $this->upsertPage($data);
        }
    }
}
