<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsBlock;

/**
 * Class VcTechLightingOlapicBottomBlock
 */
class VcTechLightingOlapicBottomBlock extends AbstractCmsBlock
{

    const BLOCK_IDENTIFIER = 'vc-tech-lighting-olapic-bottom-block';

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->createTechLightingOlapicBottomBlock();
    }

    private function createTechLightingOlapicBottomBlock(): void
    {
        $blockData = [
            'title'      => 'VC - Tech Lighting Olapic Bottom Block',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content'    => <<<EOD
            <style>#html-body [data-pb-style=OTBTX2M]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}</style>
                <div data-content-type="row" data-appearance="contained" data-element="main">
                    <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="OTBTX2M">
                    <div data-content-type="html" data-appearance="default" data-element="main">&lt;div class="olapic-bottom__wrapper"&gt; &lt;h2 class="hidden-mobile"&gt;Inspiration&lt;/h2&gt; &lt;h2 class="hidden-desktop"&gt;#visualcomfort&lt;/h2&gt; &lt;div id="olapic_specific_widget"&gt;&lt;/div&gt;&lt;script type="text/javascript" src="https://photorankstatics-a.akamaihd.net/743d2e78a76dedeb07e0745158547931/static/frontend/latest/build.min.js" data-olapic="olapic_specific_widget" data-instance="28665828234f8ae117b7c92dc5ac7a29" data-apikey="0c072425f1bdb173ba3cca355f2bf872540131b6d2929a9d8634e59e19d6aa52" async="async"&gt;&lt;/script&gt; &lt;div class="social-icons"&gt; &lt;a href="https://www.instagram.com/circalighting/"&gt;&lt;img src="{{view url='Magento_Newsletter::icons-social-instagram.svg'}}"&gt;&lt;/a&gt; &lt;a href="https://www.pinterest.com/circalighting/"&gt;&lt;img src="{{view url='Magento_Newsletter::icons-social-pinterest.svg'}}"&gt;&lt;/a&gt; &lt;a href="https://www.facebook.com/circalighting/"&gt;&lt;img src="{{view url='Magento_Newsletter::icons-social-facebook.svg'}}"&gt;&lt;/a&gt; &lt;a href="https://twitter.com/circalighting"&gt;&lt;img src="{{view url='Magento_Newsletter::icons-social-twitter.svg'}}"&gt;&lt;/a&gt; &lt;/div&gt; &lt;/div&gt;</div>
                </div>
            </div>
            EOD,
            'stores'     => [$this->getVcStoreId()],
            'is_active'  => 1,
        ];
        $this->upsertBlock($blockData);
    }
}
