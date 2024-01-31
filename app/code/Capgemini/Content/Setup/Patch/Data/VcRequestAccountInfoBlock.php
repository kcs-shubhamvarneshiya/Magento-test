<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsBlock;

/**
 * Class VcRequestAccountInfoBlock
 */
class VcRequestAccountInfoBlock extends AbstractCmsBlock
{

    const BLOCK_IDENTIFIER = 'vc-request-account-info-block';

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->createRequestAccountInfoBlock();
    }

    private function createRequestAccountInfoBlock(): void
    {
        $blockData = [
            'title'      => 'VC - Request Account Info Right Block',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content'    => '
            <style>#html-body [data-pb-style=MF7N24E]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=PVE56UD]{border-style:none}#html-body [data-pb-style=EWRL60S],#html-body [data-pb-style=FF2ODYU]{max-width:100%;height:auto}#html-body [data-pb-style=TASWRDS]{display:inline-block}@media only screen and (max-width: 768px) { #html-body [data-pb-style=PVE56UD]{border-style:none} }</style>
            <div data-content-type="row" data-appearance="contained" data-element="main">
               <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="MF7N24E">
                  <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="PVE56UD"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/account/group_2x.png}}" alt="" data-element="desktop_image" data-pb-style="EWRL60S"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/account/group_2x.png}}" alt="" data-element="mobile_image" data-pb-style="FF2ODYU"></figure>
                  <h2 data-content-type="heading" data-appearance="default" data-element="main">Enjoy Great Benefits</h2>
                  <div data-content-type="text" data-appearance="default" data-element="main">
                     <p>Interior designers, architects, builders and other design professionals are invited to join our Trade Program Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis pulvinar, felis quis imperdiet venenatis, velit velit convallis leo, vel tincidunt dolor elit nec est. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
                  </div>
                  <div data-content-type="buttons" data-appearance="inline" data-same-width="false" data-element="main">
                     <div class="btn btn-secondary" data-content-type="button-item" data-appearance="default" data-element="main" data-pb-style="TASWRDS"><a class="pagebuilder-button-secondary" href="#" target="" data-link-type="default" data-element="link"><span data-element="link_text">Learn More</span></a></div>
                  </div>
               </div>
            </div>
            ',
            'stores'     => [$this->getVcStoreId()],
            'is_active'  => 1,
        ];
        $this->upsertBlock($blockData);
    }
}
