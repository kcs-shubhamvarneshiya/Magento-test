<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsBlock;

/**
 * Class VcRequestAccountInfoBlock
 */
class VcCompanyAccountBlock extends AbstractCmsBlock
{

    const BLOCK_IDENTIFIER = 'vc-company_account_cms_block';

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->createCompanyAccountBlock();
    }

    private function createCompanyAccountBlock(): void
    {
        $blockData = [
            'title'      => 'VC - Company Account Link CMS Block',
            'identifier' => self::BLOCK_IDENTIFIER,
            'content'    => <<<EOD
            <style>#html-body [data-pb-style=PBXO47A]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=WPXODIB]{display:flex;width:100%}#html-body [data-pb-style=N9MLWXY]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:100%;align-self:stretch}#html-body [data-pb-style=Q2RE2MY]{display:inline-block}#html-body [data-pb-style=N8FWINM]{text-align:center}</style>
            <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="PBXO47A">
               <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="WPXODIB">
                  <div class="pagebuilder-column block block-new-company" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="N9MLWXY">
                     <div data-content-type="text" data-appearance="default" data-element="main">
                        <h2><span style="color: #454545; font-family: goudy-old-style, serif;" role="heading" aria-level="2">Wholesale/Trade Customers</span></h2>
                     </div>
                     <div data-content-type="text" data-appearance="default" data-element="main">
                        <p><span style="color: #454545; font-family: proxima-nova, sans-serif; letter-spacing: 0.25px;">For qualified trade customers, we offer great benefits including exclusive pricing, quote generation, tear sheets, and CAD Block drawings of our products.</span></p>
                     </div>
                     <div data-content-type="buttons" data-appearance="inline" data-same-width="false" data-element="main">
                        <div data-content-type="button-item" data-appearance="default" data-element="main" data-pb-style="Q2RE2MY"><a class="pagebuilder-button-primary" href="/trade" target="" data-link-type="default" data-element="link" data-pb-style="N8FWINM"><span data-element="link_text">Request An Account</span></a></div>
                     </div>
                  </div>
               </div>
            </div>
            EOD,
            'stores'     => [$this->getVcStoreId()],
            'is_active'  => 1,
        ];
        $this->upsertBlock($blockData);
    }
}
