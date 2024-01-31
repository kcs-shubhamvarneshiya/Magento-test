<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsBlock;

/**
 * Class TechLightingCategoryBrowsePageBottom
 */
class TechLightingCategoryBrowsePageBottom extends AbstractCmsBlock
{

    const BLOCK_IDENTIFIER = 'tech-lighting-category-browse-page-bottom';

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->createTechLightingBlock();
    }

    private function createTechLightingBlock(): void
    {
        $blockData = [
            'title' => 'Tech Lighting - Category Browse Page Bottom',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content' => '
            <style>#html-body [data-pb-style=QTE51WG]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;margin:0}#html-body [data-pb-style=JJP24LF]{border-style:none}#html-body [data-pb-style=GUTN3FY],#html-body [data-pb-style=S70J9UQ]{max-width:100%;height:auto}@media only screen and (max-width: 768px) { #html-body [data-pb-style=JJP24LF]{border-style:none} }</style><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="QTE51WG"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="JJP24LF"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/tech-category-browse-footer.png}}" alt="" title="" data-element="desktop_image" data-pb-style="GUTN3FY"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/tech-category-browse-footer.png}}" alt="" title="" data-element="mobile_image" data-pb-style="S70J9UQ"></figure></div></div>
            ',
            'stores' => [$this->getVcStoreId()],
            'is_active' => 1,
        ];
        $this->upsertBlock($blockData);
    }
}
