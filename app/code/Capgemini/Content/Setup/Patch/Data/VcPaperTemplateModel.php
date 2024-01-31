<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsBlock;

/**
 * Class VcArchitecturalTechLightingBlock
 */
class VcPaperTemplateModel extends AbstractCmsBlock
{

    const BLOCK_IDENTIFIER = 'paper_model_template';

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->createTechLightingBlock();
    }

    /**
     * @return void
     */
    private function createTechLightingBlock(): void
    {
        $blockData = [
            'title' => 'Paper Model Template',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content' => '
            <style>#html-body [data-pb-style=MYU0R86],#html-body [data-pb-style=YHYM39P]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=MYU0R86]{justify-content:flex-start;display:flex;flex-direction:column;border-style:none;border-width:1px;border-radius:0;margin:0 0 10px;padding:10px}#html-body [data-pb-style=YHYM39P]{align-self:stretch}#html-body [data-pb-style=N9N4Q5I]{display:flex;width:100%}#html-body [data-pb-style=NOR5FBV]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:41.6667%;padding:0 60px 0 0;align-self:stretch}#html-body [data-pb-style=VSESUR7]{border-style:none}#html-body [data-pb-style=U0BXCAD],#html-body [data-pb-style=UL275A3]{max-width:100%;height:auto}#html-body [data-pb-style=YF5EL13]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:58.3333%;padding-right:120px;align-self:flex-start}#html-body [data-pb-style=B0U3HMK]{margin-top:0;margin-bottom:22px;padding-top:0}#html-body [data-pb-style=UJAKC94]{margin-bottom:60px}@media only screen and (max-width: 768px) { #html-body [data-pb-style=VSESUR7]{border-style:none} }</style><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="MYU0R86"><div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="YHYM39P"><div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="N9N4Q5I"><div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="NOR5FBV"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="VSESUR7"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/2D-Image_1000x1000.gif}}" alt="" title="" data-element="desktop_image" data-pb-style="UL275A3"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/2D-Image_1000x1000.gif}}" alt="" title="" data-element="mobile_image" data-pb-style="U0BXCAD"></figure></div><div class="pagebuilder-column" data-content-type="column" data-appearance="align-top" data-background-images="{}" data-element="main" data-pb-style="YF5EL13"><h3 data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="B0U3HMK">See it in your space at scale</h3><div data-content-type="text" data-appearance="default" data-element="main" data-pb-style="UJAKC94"><p>Not sure if it’s the right fit? Order a 2D paper template with an outline of this fixture at actual size. We will print and ship the template to you in 2-3 business days for just $15 each.</p></div><div data-content-type="html" data-appearance="default" data-element="main">{{widget type="Capgemini\PaperModel\Block\Widget\PaperModelButtons"}}</div></div></div></div></div></div>
            ',
            'stores' => [$this->getVcStoreId()],
            'is_active' => 1,
        ];
        $this->upsertBlock($blockData);
    }
}
