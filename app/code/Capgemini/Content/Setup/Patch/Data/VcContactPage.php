<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsPage;

/**
 * Class VcContactPage
 */
class VcContactPage extends AbstractCmsPage
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        $pageContent = <<<EOD
<style>#html-body [data-pb-style=O0LSSAY],#html-body [data-pb-style=XEUQCB6]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=XEUQCB6]{justify-content:flex-start;display:flex;flex-direction:column;border-style:none;border-width:1px;border-radius:0}#html-body [data-pb-style=O0LSSAY]{align-self:stretch}#html-body [data-pb-style=WRFB944]{display:flex;width:100%}#html-body [data-pb-style=D3J7FQ2]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:58.3333%;align-self:stretch}#html-body [data-pb-style=MPGTVBK]{border-style:none}#html-body [data-pb-style=BGG5H5X],#html-body [data-pb-style=XS4M74B]{max-width:100%;height:auto}#html-body [data-pb-style=R5GNFG9]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;text-align:right;width:41.6667%;align-self:stretch}#html-body [data-pb-style=BXXGP1W]{border-style:none}#html-body [data-pb-style=PWQTNI3],#html-body [data-pb-style=V33YBWF]{max-width:100%;height:auto}@media only screen and (max-width: 768px) { #html-body [data-pb-style=BXXGP1W],#html-body [data-pb-style=MPGTVBK]{border-style:none} }</style>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="XEUQCB6">
        <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="O0LSSAY">
            <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="WRFB944">
                <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="D3J7FQ2">
                    <h2 class="contact-us" data-content-type="heading" data-appearance="default" data-element="main">Contact Us</h2>
                    <figure class="only-show-on-mobile" data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="MPGTVBK"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/contact-us-mobile_1.jpg}}" alt="" data-element="desktop_image" data-pb-style="BGG5H5X"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/contact-us-mobile_1.jpg}}" alt="" data-element="mobile_image" data-pb-style="XS4M74B"></figure>
                    <div data-content-type="html" data-appearance="default" data-element="main">{{widget type="Amasty\Customform\Block\Init" form_id="5" popup="0" template="Amasty_Customform::init.phtml"}}</div>
                </div>
                <div class="pagebuilder-column vc-right-contact" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="R5GNFG9">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="BXXGP1W"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/contact-us_1.jpg}}" alt="" data-element="desktop_image" data-pb-style="PWQTNI3"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/contact-us_1.jpg}}" alt="" data-element="mobile_image" data-pb-style="V33YBWF"></figure>
                </div>
            </div>
        </div>
    </div>
</div>
EOD;

        $pageData = [
            [
                'title' => 'VC Contact Us',
                'page_layout' => 'cms-full-width',
                'identifier' => 'vc-contact',
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
