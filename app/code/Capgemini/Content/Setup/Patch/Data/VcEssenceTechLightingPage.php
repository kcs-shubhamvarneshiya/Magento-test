<?php
/**
 * Capgemini_Content
 */

namespace Capgemini\Content\Setup\Patch\Data;

use Capgemini\Content\Setup\Patch\Data\Cms\AbstractCmsPage;

/**
 * Class VcEssenceTechLightingPage
 */
class VcEssenceTechLightingPage extends AbstractCmsPage
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply()
    {
        $pageContent = <<<EOD
        <style>#html-body [data-pb-style=D05AK7I],#html-body [data-pb-style=I75K7YB]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=I75K7YB]{justify-content:flex-start;display:flex;flex-direction:column;background-color:#000}#html-body [data-pb-style=D05AK7I]{align-self:stretch}#html-body [data-pb-style=KXQMUJ1]{display:flex;width:100%}#html-body [data-pb-style=W5VB1UE]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:100%;align-self:stretch}#html-body [data-pb-style=FRDNKAF],#html-body [data-pb-style=QWUDA10]{text-align:center}#html-body [data-pb-style=TBP6GOQ]{padding-top:0;padding-bottom:0}#html-body [data-pb-style=KRVQNV2]{border-style:none}#html-body [data-pb-style=B3RO44G],#html-body [data-pb-style=SWPA67G]{max-width:100%;height:auto}#html-body [data-pb-style=S4EH96N]{justify-content:flex-start;display:flex;flex-direction:column;background-color:#000;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=JX6TE62]{text-align:center;margin-bottom:10px}#html-body [data-pb-style=JMJ0QTA]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=NGJBAP0]{display:flex;width:100%}#html-body [data-pb-style=U6EKGIK]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=H275NFR]{border-style:none}#html-body [data-pb-style=A9HCPQU],#html-body [data-pb-style=UCS1DE2]{max-width:100%;height:auto}#html-body [data-pb-style=JEA6CE8]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=U860XIW]{border-style:none}#html-body [data-pb-style=FGHTG7D],#html-body [data-pb-style=GAIY3KH]{max-width:100%;height:auto}#html-body [data-pb-style=DC55S7N]{border-style:none}#html-body [data-pb-style=ISRTEXF],#html-body [data-pb-style=SSC95ET]{max-width:100%;height:auto}#html-body [data-pb-style=CY1YSB5]{justify-content:flex-start;display:flex;flex-direction:column;background-color:#000;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=YIPUB2D]{text-align:center;margin-bottom:2px}#html-body [data-pb-style=X4L2KKM]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=OXKKFMY]{display:flex;width:100%}#html-body [data-pb-style=U6HG4E0]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:100%;align-self:stretch}#html-body [data-pb-style=GOEC1L7]{text-align:center;border-style:none}#html-body [data-pb-style=CNTUY4R],#html-body [data-pb-style=U40N7W1]{max-width:100%;height:auto}#html-body [data-pb-style=GPAMNL2]{justify-content:flex-start;display:flex;flex-direction:column;background-color:#000;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=WNC95D4]{text-align:center;margin-bottom:13px}#html-body [data-pb-style=HNG86PX]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=D070IEO]{display:flex;width:100%}#html-body [data-pb-style=R4MJ4L3]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=D2A2AHA]{border-style:none}#html-body [data-pb-style=RQ4YNY1],#html-body [data-pb-style=WPWFGI2]{max-width:100%;height:auto}#html-body [data-pb-style=F095B9V]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=O5QTNCP]{border-style:none}#html-body [data-pb-style=F2I6PPB],#html-body [data-pb-style=HEJFHCV]{max-width:100%;height:auto}#html-body [data-pb-style=UALTAG6]{justify-content:flex-start;display:flex;flex-direction:column;background-color:#000;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=DDVIWII]{text-align:center;margin-bottom:13px}#html-body [data-pb-style=TQRWNRH]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=QEE5KI3]{display:flex;width:100%}#html-body [data-pb-style=CQF9GOL]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=AGGF1CM]{border-style:none}#html-body [data-pb-style=M03BBAV],#html-body [data-pb-style=YVS4M4Y]{max-width:100%;height:auto}#html-body [data-pb-style=AOGAQ00]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:50%;align-self:stretch}#html-body [data-pb-style=D8RCQH2]{border-style:none}#html-body [data-pb-style=DCWKVH4],#html-body [data-pb-style=FCT9GLX]{max-width:100%;height:auto}#html-body [data-pb-style=SRKAIB4]{justify-content:flex-start;display:flex;flex-direction:column;background-color:#000;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=LC7YVWD]{text-align:center;margin-bottom:8px}#html-body [data-pb-style=L7D8H4W]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=GJCFN8A]{display:flex;width:100%}#html-body [data-pb-style=PPXYUCU]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=QAKLEHG]{border-style:none}#html-body [data-pb-style=G542V4S],#html-body [data-pb-style=W9V0OHH]{max-width:100%;height:auto}#html-body [data-pb-style=SBQUDVY]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=PIMCJ0W]{border-style:none}#html-body [data-pb-style=JRLQ6ES],#html-body [data-pb-style=S3XIO3Y]{max-width:100%;height:auto}#html-body [data-pb-style=MW1EA8C]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=UGWT74O]{border-style:none}#html-body [data-pb-style=KC4NBXS],#html-body [data-pb-style=NNQTPQP]{max-width:100%;height:auto}#html-body [data-pb-style=DQ4GODX]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=NQFSQM4]{border-style:none}#html-body [data-pb-style=RX5GS7E],#html-body [data-pb-style=VDLWVIO]{max-width:100%;height:auto}#html-body [data-pb-style=CE22FGL]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=KIOA7T8]{display:flex;width:100%}#html-body [data-pb-style=GX6GMB6]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=DY1KVQ1]{border-style:none}#html-body [data-pb-style=AIKT3X6],#html-body [data-pb-style=Q5BYN15]{max-width:100%;height:auto}#html-body [data-pb-style=M3IBTB9]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=BKYFW4L]{border-style:none}#html-body [data-pb-style=CXO6N0J],#html-body [data-pb-style=G8JC9SC]{max-width:100%;height:auto}#html-body [data-pb-style=WWSNIFI]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=NMHLY83]{border-style:none}#html-body [data-pb-style=G4SRB1T],#html-body [data-pb-style=KKIQMKA]{max-width:100%;height:auto}#html-body [data-pb-style=N4ILP6Q]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:25%;align-self:stretch}#html-body [data-pb-style=UCVVO4U]{border-style:none}#html-body [data-pb-style=VS841XE],#html-body [data-pb-style=YT5WRYO]{max-width:100%;height:auto}#html-body [data-pb-style=VY5JNMK]{justify-content:flex-start;display:flex;flex-direction:column;background-color:#000;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=OHOBH8C]{text-align:center;margin-bottom:8px}#html-body [data-pb-style=X52M5R6]{background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;align-self:stretch}#html-body [data-pb-style=GS7496A]{display:flex;width:100%}#html-body [data-pb-style=FHK3IHG]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll;width:100%;align-self:stretch}#html-body [data-pb-style=GRNT1JS]{text-align:center;border-style:none}#html-body [data-pb-style=DEU3610],#html-body [data-pb-style=WEODKNL]{max-width:100%;height:auto}#html-body [data-pb-style=HETUAJU]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=FWR4SEM]{width:100%;border-width:1px;border-color:#cecece;display:inline-block}@media only screen and (max-width: 768px) { #html-body [data-pb-style=AGGF1CM],#html-body [data-pb-style=BKYFW4L],#html-body [data-pb-style=D2A2AHA],#html-body [data-pb-style=D8RCQH2],#html-body [data-pb-style=DC55S7N],#html-body [data-pb-style=DY1KVQ1],#html-body [data-pb-style=GOEC1L7],#html-body [data-pb-style=GRNT1JS],#html-body [data-pb-style=H275NFR],#html-body [data-pb-style=KRVQNV2],#html-body [data-pb-style=NMHLY83],#html-body [data-pb-style=NQFSQM4],#html-body [data-pb-style=O5QTNCP],#html-body [data-pb-style=PIMCJ0W],#html-body [data-pb-style=QAKLEHG],#html-body [data-pb-style=U860XIW],#html-body [data-pb-style=UCVVO4U],#html-body [data-pb-style=UGWT74O]{border-style:none} }</style>
        <div class="category-mask__row" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="I75K7YB">
           <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="D05AK7I">
              <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="KXQMUJ1">
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="W5VB1UE">
                    <h1 class="cms-heading__h1" data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="FRDNKAF">essence</h1>
                    <h2 class="cms-heading__h2" data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="QWUDA10">Elevate your space with ESSENCE Linear Lighting</h2>
                    <div class="cms-heading__text" data-content-type="text" data-appearance="default" data-element="main" data-pb-style="TBP6GOQ">
                       <p style="text-align: center;">Where light is placed has a significant impact on visibility, visual comfort, aesthetics, task performance, safety, mood, satisfaction, and social interaction. ESSENCE provides premier quality made-to-order architectural light channels and premium LED tape.</p>
                    </div>
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="KRVQNV2"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-2.png}}" alt="" data-element="desktop_image" data-pb-style="SWPA67G"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/essf-0604-wh-bk-b-bk-lens-on.png}}" alt="" data-element="mobile_image" data-pb-style="B3RO44G"></figure>
                 </div>
              </div>
           </div>
        </div>
        <div class="category-mask__row" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="S4EH96N">
           <h2 class="cms-heading__h2" data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="JX6TE62">Unmatched color quality and consistency</h2>
           <div class="cms-heading__text" data-content-type="text" data-appearance="default" data-element="main">
              <p style="text-align: center;">The quality of light is critical to ensure other design elements in the space are presented in the most natural way possible. ESSENCE utilizes a proprietary phosphor mix and hand selects from over 1 billion LEDs each year to achieve unmatched color quality and consistency.</p>
           </div>
           <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="JMJ0QTA">
              <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="NGJBAP0">
                 <div class="pagebuilder-column category-mask__col--1" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="U6EKGIK">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="H275NFR"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-3.png}}" alt="" data-element="desktop_image" data-pb-style="UCS1DE2"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/essence-under-cabinet-lighting-shallow-surface-mount-essf-0604-detail-3-jk.png}}" alt="" data-element="mobile_image" data-pb-style="A9HCPQU"></figure>
                 </div>
                 <div class="pagebuilder-column category-mask__col--2" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="JEA6CE8">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="U860XIW"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-4.png}}" alt="" data-element="desktop_image" data-pb-style="GAIY3KH"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/essence-74-inch-shallow-surface-black-detail-1.png}}" alt="" data-element="mobile_image" data-pb-style="FGHTG7D"></figure>
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="DC55S7N"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-5.png}}" alt="" data-element="desktop_image" data-pb-style="ISRTEXF"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/essence-shallowsurfacemount-satinnickel-36-inch-main-copy.png}}" alt="" data-element="mobile_image" data-pb-style="SSC95ET"></figure>
                 </div>
              </div>
           </div>
        </div>
        <div class="category-mask__row" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="CY1YSB5">
           <h2 class="cms-heading__h2" data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="YIPUB2D">Superior LED Tape</h2>
           <div class="cms-heading__text" data-content-type="text" data-appearance="default" data-element="main">
              <p style="text-align: center;">Engineered to provide beautiful light with maximum flexibility, consistent brightness, minimal shadowing and long life. Choose between 1-and 2-step binning, static white and warm dim.</p>
           </div>
           <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="X4L2KKM">
              <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="OXKKFMY">
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="U6HG4E0">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="GOEC1L7"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-6.png}}" alt="" data-element="desktop_image" data-pb-style="CNTUY4R"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/essence-under-cabinet-lighting-shallow-surface-mount-essf-0604-detail-3-jk_1.png}}" alt="" data-element="mobile_image" data-pb-style="U40N7W1"></figure>
                 </div>
              </div>
           </div>
        </div>
        <div class="category-mask__row" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="GPAMNL2">
           <h2 class="cms-heading__h2" data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="WNC95D4">Premium Channel Aesthetics</h2>
           <div class="cms-heading__text" data-content-type="text" data-appearance="default" data-element="main">
              <p style="text-align: center;">Essence Aluminum channel extrusions are manufactured to provide a tailored, quality aesthetic. Available in Anodized Black, Satin Aluminum, and White (field-paintable) finishes.</p>
           </div>
           <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="HNG86PX">
              <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="D070IEO">
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="R4MJ4L3">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="D2A2AHA"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-4.png}}" alt="" data-element="desktop_image" data-pb-style="RQ4YNY1"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-4.png}}" alt="" data-element="mobile_image" data-pb-style="WPWFGI2"></figure>
                 </div>
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="F095B9V">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="O5QTNCP"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-5.png}}" alt="" data-element="desktop_image" data-pb-style="HEJFHCV"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-5.png}}" alt="" data-element="mobile_image" data-pb-style="F2I6PPB"></figure>
                 </div>
              </div>
           </div>
        </div>
        <div class="category-mask__row" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="UALTAG6">
           <h2 class="cms-heading__h2" data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="DDVIWII">Superior Lenses &amp; Louvers</h2>
           <div class="cms-heading__text" data-content-type="text" data-appearance="default" data-element="main">
              <p style="text-align: center;">Our acrylic lenses optimize light output while minimizing glare and LED pixaltion. We offer White, Frosted, Black and Louver lenses. Learn more to understand what product is best for your project.</p>
           </div>
           <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="TQRWNRH">
              <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="QEE5KI3">
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="CQF9GOL">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="AGGF1CM"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/essence-under-cabinet-lighting-shallow-surface-mount-essf-0604-detail-4-copy-2.png}}" alt="" data-element="desktop_image" data-pb-style="YVS4M4Y"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/essence-under-cabinet-lighting-shallow-surface-mount-essf-0604-detail-3-jk_2.png}}" alt="" data-element="mobile_image" data-pb-style="M03BBAV"></figure>
                 </div>
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="AOGAQ00">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="D8RCQH2"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/essence-under-cabinet-lighting-shallow-surface-mount-essf-0604-detail-4-copy-3.png}}" alt="" data-element="desktop_image" data-pb-style="DCWKVH4"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/essence-under-cabinet-lighting-shallow-surface-mount-essf-0604-detail-3-jk_3.png}}" alt="" data-element="mobile_image" data-pb-style="FCT9GLX"></figure>
                 </div>
              </div>
           </div>
        </div>
        <div class="category-mask__row --grid" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="SRKAIB4">
           <h2 class="cms-heading__h2" data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="LC7YVWD">Mounting &amp; Connectors</h2>
           <div class="cms-heading__text" data-content-type="text" data-appearance="default" data-element="main">
              <p style="text-align: center;">We have a range of mounting options and connectors that allows you to have a simple and quick install process</p>
           </div>
           <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="L7D8H4W">
              <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="GJCFN8A">
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="PPXYUCU">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="QAKLEHG"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="desktop_image" data-pb-style="G542V4S"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="mobile_image" data-pb-style="W9V0OHH"></figure>
                 </div>
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="SBQUDVY">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="PIMCJ0W"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="desktop_image" data-pb-style="S3XIO3Y"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="mobile_image" data-pb-style="JRLQ6ES"></figure>
                 </div>
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="MW1EA8C">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="UGWT74O"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="desktop_image" data-pb-style="NNQTPQP"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="mobile_image" data-pb-style="KC4NBXS"></figure>
                 </div>
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="DQ4GODX">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="NQFSQM4"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="desktop_image" data-pb-style="VDLWVIO"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="mobile_image" data-pb-style="RX5GS7E"></figure>
                 </div>
              </div>
           </div>
           <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="CE22FGL">
              <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="KIOA7T8">
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="GX6GMB6">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="DY1KVQ1"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="desktop_image" data-pb-style="AIKT3X6"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="mobile_image" data-pb-style="Q5BYN15"></figure>
                 </div>
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="M3IBTB9">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="BKYFW4L"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="desktop_image" data-pb-style="G8JC9SC"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="mobile_image" data-pb-style="CXO6N0J"></figure>
                 </div>
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="WWSNIFI">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="NMHLY83"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="desktop_image" data-pb-style="KKIQMKA"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="mobile_image" data-pb-style="G4SRB1T"></figure>
                 </div>
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="N4ILP6Q">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="UCVVO4U"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="desktop_image" data-pb-style="YT5WRYO"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-7.png}}" alt="" data-element="mobile_image" data-pb-style="VS841XE"></figure>
                 </div>
              </div>
           </div>
        </div>
        <div class="category-mask__row" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="main" data-pb-style="VY5JNMK">
           <h2 class="cms-heading__h2" data-content-type="heading" data-appearance="default" data-element="main" data-pb-style="OHOBH8C">Installation Process</h2>
           <div class="cms-heading__text" data-content-type="text" data-appearance="default" data-element="main">
              <p style="text-align: center;">We have a range of mounting options and connectors that allows you to have a simple and quick install process</p>
           </div>
           <div class="pagebuilder-column-group" data-background-images="{}" data-content-type="column-group" data-appearance="default" data-grid-size="12" data-element="main" data-pb-style="X52M5R6">
              <div class="pagebuilder-column-line" data-content-type="column-line" data-element="main" data-pb-style="GS7496A">
                 <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="FHK3IHG">
                    <figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="GRNT1JS"><img class="pagebuilder-mobile-hidden" title="" src="{{media url=wysiwyg/tech-lighting/tech-8.png}}" alt="" data-element="desktop_image" data-pb-style="WEODKNL"><img class="pagebuilder-mobile-only" title="" src="{{media url=wysiwyg/tech-lighting/tech-8.png}}" alt="" data-element="mobile_image" data-pb-style="DEU3610"></figure>
                 </div>
              </div>
           </div>
        </div>
        <div data-content-type="row" data-appearance="contained" data-element="main">
           <div class="category-mask__row--olapic" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="HETUAJU">
              <div data-content-type="divider" data-appearance="default" data-element="main">
                 <hr data-element="line" data-pb-style="FWR4SEM">
              </div>
              <div data-content-type="block" data-appearance="default" data-element="main">{{widget type="Magento\Cms\Block\Widget\Block" template="widget/static_block/default.phtml" block_id="1219" type_name="CMS Static Block"}}</div>
           </div>
        </div>
        EOD;

        $pageData = [
            [
                'title' => 'VC Essence Page',
                'page_layout' => '1column',
                'identifier' => 'vc-essence-page',
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
