<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsBlock;

/**
 * Class VcDesignersLanding
 */
class VcDesignersLanding extends AbstractCmsBlock
{

    const ID_OUR_DESIGNERS = 'vc-category-our-designers';
    const ID_OUR_DESIGNERS_HEADER_1 = 'vc-designer-header-1';
    const ID_OUR_DESIGNERS_HEADER_2 = 'vc-designer-header-2';

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->createVcDesignersLanding();
        $this->createVcDesignersHeaderOne();
        $this->createVcDesignersHeaderTwo();
    }

    // Our Designers Block
    private function createVcDesignersLanding()
    {
        $content = <<<CONTENT
            <style>#html-body [data-pb-style=ANV1IBV],#html-body [data-pb-style=DM8F71D]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=DM8F71D]{width:25%;align-self:stretch}#html-body [data-pb-style=IS6OBYR]{border-style:none}#html-body [data-pb-style=LCNE42W],#html-body [data-pb-style=VQC4S7I]{max-width:100%;height:auto}#html-body [data-pb-style=J3205QI]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=F0KM2X6]{border-style:none}#html-body [data-pb-style=FTCKSVR],#html-body [data-pb-style=ULNXKAC]{max-width:100%;height:auto}#html-body [data-pb-style=UKXMS37]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=EBK9JYQ]{border-style:none}#html-body [data-pb-style=JFR0U67],#html-body [data-pb-style=NPFK63K]{max-width:100%;height:auto}#html-body [data-pb-style=ONSVMUQ]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=LNYYCTL]{border-style:none}#html-body [data-pb-style=PSPG204],#html-body [data-pb-style=WLEEW92]{max-width:100%;height:auto}#html-body [data-pb-style=Y4EY2Y5]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:16.6667%;align-self:stretch}#html-body [data-pb-style=RCP6WFY]{border-style:none}#html-body [data-pb-style=Y3FHO4X],#html-body [data-pb-style=YROWEO2]{max-width:100%;height:auto}#html-body [data-pb-style=LXRKY8L]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:16.6667%;align-self:stretch}#html-body [data-pb-style=RM6U15U]{border-style:none}#html-body [data-pb-style=N1FIYN3],#html-body [data-pb-style=TU7MY2O]{max-width:100%;height:auto}#html-body [data-pb-style=CG9KOK7]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:33.33333333%;align-self:stretch}#html-body [data-pb-style=VTGQAOH]{border-style:none}#html-body [data-pb-style=HXPKHXB],#html-body [data-pb-style=YSMVNSL]{max-width:100%;height:auto}#html-body [data-pb-style=T2S26F0]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:16.66666667%;align-self:stretch}#html-body [data-pb-style=VDS5GWS]{border-style:none}#html-body [data-pb-style=CR1FTNP],#html-body [data-pb-style=SSX6IRK]{max-width:100%;height:auto}#html-body [data-pb-style=D6YURQW]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:16.6667%;align-self:stretch}#html-body [data-pb-style=UIG2RKN]{border-style:none}#html-body [data-pb-style=B1N50IC],#html-body [data-pb-style=XVKMIO1]{max-width:100%;height:auto}#html-body [data-pb-style=N5ORV7Y]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=AJGMLTS]{border-style:none}#html-body [data-pb-style=MLGY95U],#html-body [data-pb-style=X2OWDM5]{max-width:100%;height:auto}#html-body [data-pb-style=T8WL9GA]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=B6TOY8P]{border-style:none}#html-body [data-pb-style=MP68OSJ],#html-body [data-pb-style=Y3YAXA4]{max-width:100%;height:auto}#html-body [data-pb-style=T5GT66W]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=NYI2O8K]{border-style:none}#html-body [data-pb-style=LIHBRX2],#html-body [data-pb-style=W2G1KSJ]{max-width:100%;height:auto}#html-body [data-pb-style=VN5RW2T]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=KIYTVUU]{border-style:none}#html-body [data-pb-style=BW8GDNQ],#html-body [data-pb-style=Q7NRWJ0]{max-width:100%;height:auto}@media only screen and (max-width: 768px) { #html-body [data-pb-style=AJGMLTS],#html-body [data-pb-style=B6TOY8P],#html-body [data-pb-style=EBK9JYQ],#html-body [data-pb-style=F0KM2X6],#html-body [data-pb-style=IS6OBYR],#html-body [data-pb-style=KIYTVUU],#html-body [data-pb-style=LNYYCTL],#html-body [data-pb-style=NYI2O8K],#html-body [data-pb-style=RCP6WFY],#html-body [data-pb-style=RM6U15U],#html-body [data-pb-style=UIG2RKN],#html-body [data-pb-style=VDS5GWS],#html-body [data-pb-style=VTGQAOH]{border-style:none} }</style>
            <div class="random-header" data-content-type="html" data-appearance="default" data-element="main">{{widget type="Capgemini\CmsBlockRandom\Block\Widget\CmsBlockRandom" identity_ids="vc-designer-header-1, vc-designer-header-2"}}</div>
            <div data-content-type="row" data-appearance="contained" data-element="main">
                <div class="designer-grid" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="ANV1IBV">
                    <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="DM8F71D">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="IS6OBYR"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_12.png}}" alt="" data-element="desktop_image" data-pb-style="VQC4S7I"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_12.png}}" alt="" data-element="mobile_image" data-pb-style="LCNE42W"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="J3205QI">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="F0KM2X6"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_13.png}}" alt="" data-element="desktop_image" data-pb-style="FTCKSVR"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_13.png}}" alt="" data-element="mobile_image" data-pb-style="ULNXKAC"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a tabindex="0" href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="UKXMS37">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="EBK9JYQ"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_14.png}}" alt="" data-element="desktop_image" data-pb-style="NPFK63K"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_14.png}}" alt="" data-element="mobile_image" data-pb-style="JFR0U67"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="ONSVMUQ">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="LNYYCTL"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_15.png}}" alt="" data-element="desktop_image" data-pb-style="PSPG204"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_15.png}}" alt="" data-element="mobile_image" data-pb-style="WLEEW92"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a tabindex="0" href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="Y4EY2Y5">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="RCP6WFY"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_16.png}}" alt="" data-element="desktop_image" data-pb-style="Y3FHO4X"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_16.png}}" alt="" data-element="mobile_image" data-pb-style="YROWEO2"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a tabindex="0" href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="LXRKY8L">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="RM6U15U"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_17.png}}" alt="" data-element="desktop_image" data-pb-style="N1FIYN3"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_17.png}}" alt="" data-element="mobile_image" data-pb-style="TU7MY2O"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a tabindex="0" href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                        <div class="pagebuilder-column banner-2x2" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="CG9KOK7">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="VTGQAOH"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/designer2x2_3.png}}" alt="" data-element="desktop_image" data-pb-style="YSMVNSL"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/designer2x2_3.png}}" alt="" data-element="mobile_image" data-pb-style="HXPKHXB"></figure>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="T2S26F0">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="VDS5GWS"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_24.png}}" alt="" data-element="desktop_image" data-pb-style="SSX6IRK"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_24.png}}" alt="" data-element="mobile_image" data-pb-style="CR1FTNP"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a tabindex="0" href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="D6YURQW">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="UIG2RKN"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_25.png}}" alt="" data-element="desktop_image" data-pb-style="XVKMIO1"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_25.png}}" alt="" data-element="mobile_image" data-pb-style="B1N50IC"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a tabindex="0" href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="N5ORV7Y">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="AJGMLTS"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_20.png}}" alt="" data-element="desktop_image" data-pb-style="MLGY95U"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_20.png}}" alt="" data-element="mobile_image" data-pb-style="X2OWDM5"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a tabindex="0" href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="T8WL9GA">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="B6TOY8P"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_21.png}}" alt="" data-element="desktop_image" data-pb-style="Y3YAXA4"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_21.png}}" alt="" data-element="mobile_image" data-pb-style="MP68OSJ"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a tabindex="0" href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="T5GT66W">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="NYI2O8K"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_22.png}}" alt="" data-element="desktop_image" data-pb-style="W2G1KSJ"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_22.png}}" alt="" data-element="mobile_image" data-pb-style="LIHBRX2"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a tabindex="0" href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="VN5RW2T">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="KIYTVUU"><a title="" href="/designer-link" target="" data-link-type="default" data-element="link"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/kelly-wearstler_23.png}}" alt="" data-element="desktop_image" data-pb-style="Q7NRWJ0"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/kelly-wearstler_23.png}}" alt="" data-element="mobile_image" data-pb-style="BW8GDNQ"></a></figure>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p style="text-align: center;"><a tabindex="-1" href="/designer-link">Designer Name</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        CONTENT;
        $designersLandingBlock = [
            'title'      => 'VC - Category: Our Designers',
            'identifier' => self::ID_OUR_DESIGNERS,
            'content'    => $content,
            'stores'     => [$this->getVcStoreId()],
            'is_active'  => 1,
        ];
        $this->upsertBlock($designersLandingBlock);
    }

    // Our Designers Header 1 Block
    private function createVcDesignersHeaderOne()
    {
        $content = <<<CONTENT
            <style>#html-body [data-pb-style=J3MVXRI],#html-body [data-pb-style=NQ62TIW],#html-body [data-pb-style=XW415FX]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=J3MVXRI]{background-color:#f8f8f8;padding-top:0;padding-bottom:30px}#html-body [data-pb-style=NQ62TIW],#html-body [data-pb-style=XW415FX]{width:50%;align-self:stretch}#html-body [data-pb-style=NQ62TIW]{justify-content:center}#html-body [data-pb-style=RIOPOIR]{border-style:none}#html-body [data-pb-style=IHMICK7],#html-body [data-pb-style=JHE2THN]{max-width:100%;height:auto}#html-body [data-pb-style=Y4HKJWO]{display:inline-block}#html-body [data-pb-style=PEHIQ3M]{text-align:center}@media only screen and (max-width: 768px) { #html-body [data-pb-style=RIOPOIR]{border-style:none} }</style>
            <div data-content-type="row" data-appearance="full-width" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="J3MVXRI">
                <div class="row-full-width-inner" data-element="inner">
                    <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="XW415FX">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="RIOPOIR"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/designer-header.png}}" alt="" data-element="desktop_image" data-pb-style="IHMICK7"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/designer-header-mobile.png}}" alt="" data-element="mobile_image" data-pb-style="JHE2THN"></figure>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="NQ62TIW">
                            <h2 data-content-type="heading" data-appearance="default" data-element="main">Jessie Carrier &amp; Mara Miller</h2>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p>Jesse Carrier and Mara Miller, the principals of Carrier and Company Interiors, are a husband-and-wife inerior design duo who create rooms that offer a confident mix of timeless and contemporary design-both familar and fresh at once. Through their unique vision, the Carriers introduce a collection that reflects their confidence in current trends while offering elegance with livability.</p>
                            </div>
                            <div data-content-type="buttons" data-appearance="inline" data-same-width="false" data-element="main">
                                <div data-content-type="button-item" data-appearance="default" data-element="main" data-pb-style="Y4HKJWO"><a class="pagebuilder-button-secondary" href="/designer-link" target="" data-link-type="default" data-element="link" data-pb-style="PEHIQ3M"><span data-element="link_text">Shop Products From This Designer</span></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        CONTENT;
        $designersHeaderOneBlock = [
            'title'      => 'VC - Category: Our Designers Header 1',
            'identifier' => self::ID_OUR_DESIGNERS_HEADER_1,
            'content'    => $content,
            'stores'     => [$this->getVcStoreId()],
            'is_active'  => 1,
        ];
        $this->upsertBlock($designersHeaderOneBlock);
    }


    // Our Designers Header 2 Block
    private function createVcDesignersHeaderTwo()
    {
        $content = <<<CONTENT
            <style>#html-body [data-pb-style=J3MVXRI],#html-body [data-pb-style=NQ62TIW],#html-body [data-pb-style=XW415FX]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=J3MVXRI]{background-color:#f8f8f8;padding-top:0;padding-bottom:30px}#html-body [data-pb-style=NQ62TIW],#html-body [data-pb-style=XW415FX]{width:50%;align-self:stretch}#html-body [data-pb-style=NQ62TIW]{justify-content:center}#html-body [data-pb-style=RIOPOIR]{border-style:none}#html-body [data-pb-style=IHMICK7],#html-body [data-pb-style=JHE2THN]{max-width:100%;height:auto}#html-body [data-pb-style=Y4HKJWO]{display:inline-block}#html-body [data-pb-style=PEHIQ3M]{text-align:center}@media only screen and (max-width: 768px) { #html-body [data-pb-style=RIOPOIR]{border-style:none} }</style>
            <div data-content-type="row" data-appearance="full-width" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="J3MVXRI">
                <div class="row-full-width-inner" data-element="inner">
                    <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="XW415FX">
                            <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="RIOPOIR"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/designer-header.png}}" alt="" data-element="desktop_image" data-pb-style="IHMICK7"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/designer-header-mobile.png}}" alt="" data-element="mobile_image" data-pb-style="JHE2THN"></figure>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="NQ62TIW">
                            <h2 data-content-type="heading" data-appearance="default" data-element="main">Designer Name 2</h2>
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p>Content version 2. Jesse Carrier and Mara Miller, the principals of Carrier and Company Interiors, are a husband-and-wife inerior design duo who create rooms that offer a confident mix of timeless and contemporary design-both familar and fresh at once. Through their unique vision, the Carriers introduce a collection that reflects their confidence in current trends while offering elegance with livability.</p>
                            </div>
                            <div data-content-type="buttons" data-appearance="inline" data-same-width="false" data-element="main">
                                <div data-content-type="button-item" data-appearance="default" data-element="main" data-pb-style="Y4HKJWO"><a class="pagebuilder-button-secondary" href="/designer-link" target="" data-link-type="default" data-element="link" data-pb-style="PEHIQ3M"><span data-element="link_text">Shop Products From This Designer</span></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        CONTENT;
        $designersHeaderTwoBlock = [
            'title'      => 'VC - Category: Our Designers Header 2',
            'identifier' => self::ID_OUR_DESIGNERS_HEADER_2,
            'content'    => $content,
            'stores'     => [$this->getVcStoreId()],
            'is_active'  => 1,
        ];
        $this->upsertBlock($designersHeaderTwoBlock);
    }
}
