<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsBlock;

/**
 * Class VcArchitecturalTechLightingBlock
 */
class VcArchitecturalTechLightingBlock extends AbstractCmsBlock
{

    const BLOCK_IDENTIFIER = 'vc-architectural-tech-lighting-block';

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->createTechLightingBlock();
    }

    // VcArchitecturalTechLightingBlock
    private function createTechLightingBlock(): void
    {
        $blockData = [
            'title'      => 'VC - Category: Architectural Tech Lighting Block',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content'    => '
            <style>#html-body [data-pb-style=IT7G9BQ]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=FJOY1O2],#html-body [data-pb-style=T84V0JW]{text-align:center}#html-body [data-pb-style=HO9L7LE],#html-body [data-pb-style=JU7QNEG]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=JU7QNEG]{justify-content:flex-start;display:flex;flex-direction:column}#html-body [data-pb-style=HO9L7LE]{align-self:stretch}#html-body [data-pb-style=DBSI1VE]{display:flex;width:100%}#html-body [data-pb-style=V0OGV8W]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=D98CFVC]{border-style:none}#html-body [data-pb-style=K0F2D32],#html-body [data-pb-style=MT6ATII]{max-width:100%;height:auto}#html-body [data-pb-style=AV18JIE]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=S6F82J3]{border-style:none}#html-body [data-pb-style=M8I62UI],#html-body [data-pb-style=VHBJUOD]{max-width:100%;height:auto}#html-body [data-pb-style=FP2HD0P]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=I8MNVCD]{display:flex;width:100%}#html-body [data-pb-style=PS3N4DG]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=Q6FEBEF]{border-style:none}#html-body [data-pb-style=CO6F53S],#html-body [data-pb-style=N7GAPPF]{max-width:100%;height:auto}#html-body [data-pb-style=WW0UEDU]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=JV3IPUE]{border-style:none}#html-body [data-pb-style=FETEHAY],#html-body [data-pb-style=YFAWC45]{max-width:100%;height:auto}#html-body [data-pb-style=QUP2A9Q]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=U8JLDHS]{display:flex;width:100%}#html-body [data-pb-style=PFXFLS2]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=VYGO34J]{border-style:none}#html-body [data-pb-style=BAVCNET],#html-body [data-pb-style=LQVSY3T]{max-width:100%;height:auto}#html-body [data-pb-style=JU6EF6I]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=PI6ID2B]{border-style:none}#html-body [data-pb-style=KHQ1SWN],#html-body [data-pb-style=P477YVF]{max-width:100%;height:auto}#html-body [data-pb-style=UH03355]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=PPHSEQQ]{width:100%;border-width:1px;border-color:#cecece;display:inline-block}@media only screen and (max-width: 768px) { #html-body [data-pb-style=D98CFVC],#html-body [data-pb-style=JV3IPUE],#html-body [data-pb-style=PI6ID2B],#html-body [data-pb-style=Q6FEBEF],#html-body [data-pb-style=S6F82J3],#html-body [data-pb-style=VYGO34J]{border-style:none} }</style>
            <div data-content-type="row" data-appearance="contained" data-element="main">
               <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="IT7G9BQ">
                  <h1 class="category__heading" data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="FJOY1O2">Architectural</h1>
                  <div class="category__description" data-content-type="text" data-appearance="default" data-element="main" data-pb-style="T84V0JW">
                     <p style="text-align: center;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut suscipit nunc in turpis consequat bibendum. Mauris id aliquam mauris. Ut sit amet urna arcu. Quisque a lorem in risus egestas interdum ac tincidunt nisi Donec semper tellus maximus, porta lacus et, tristique dui. Curabitur at molestie neque. Nunc est lorem, eleifend sit amet ex eu, eleifend fringilla arcu..</p>
                  </div>
               </div>
            </div>
            <div data-content-type="row" data-appearance="contained" data-element="main">
               <div class="category__row" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="JU7QNEG">
                  <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="HO9L7LE">
                     <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="DBSI1VE">
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="V0OGV8W">
                           <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="D98CFVC"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="desktop_image" data-pb-style="K0F2D32"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="mobile_image" data-pb-style="MT6ATII"></figure>
                           <h2 data-content-type="heading" data-appearance="default" data-element="main">Recessed</h2>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="AV18JIE">
                           <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="S6F82J3"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="desktop_image" data-pb-style="M8I62UI"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="mobile_image" data-pb-style="VHBJUOD"></figure>
                           <h2 data-content-type="heading" data-appearance="default" data-element="main">Channels of Light</h2>
                        </div>
                     </div>
                  </div>
                  <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="FP2HD0P">
                     <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="I8MNVCD">
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="PS3N4DG">
                           <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="Q6FEBEF"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="desktop_image" data-pb-style="CO6F53S"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="mobile_image" data-pb-style="N7GAPPF"></figure>
                           <h2 data-content-type="heading" data-appearance="default" data-element="main">Cylinders</h2>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="WW0UEDU">
                           <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="JV3IPUE"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="desktop_image" data-pb-style="YFAWC45"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="mobile_image" data-pb-style="FETEHAY"></figure>
                           <h2 data-content-type="heading" data-appearance="default" data-element="main">Monopoint Task</h2>
                        </div>
                     </div>
                  </div>
                  <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="QUP2A9Q">
                     <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="U8JLDHS">
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="PFXFLS2">
                           <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="VYGO34J"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="desktop_image" data-pb-style="LQVSY3T"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="mobile_image" data-pb-style="BAVCNET"></figure>
                           <h2 data-content-type="heading" data-appearance="default" data-element="main">Monorail</h2>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="JU6EF6I">
                           <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="PI6ID2B"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="desktop_image" data-pb-style="KHQ1SWN"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/1.png}}" alt="" data-element="mobile_image" data-pb-style="P477YVF"></figure>
                           <h2 data-content-type="heading" data-appearance="default" data-element="main">Kable Light</h2>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div data-content-type="row" data-appearance="contained" data-element="main">
               <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="UH03355">
                  <div data-content-type="divider" data-appearance="default" data-element="main">
                     <hr data-element="line" data-pb-style="PPHSEQQ">
                  </div>
                  <div data-content-type="block" data-appearance="default" data-element="main">{{widget type="Magento\Cms\Block\Widget\Block" template="widget/static_block/default.phtml" block_id="948" type_name="CMS Static Block"}}</div>
               </div>
            </div>
            ',
            'stores'     => [$this->getVcStoreId()],
            'is_active'  => 1,
        ];
        $this->upsertBlock($blockData);
    }
}
