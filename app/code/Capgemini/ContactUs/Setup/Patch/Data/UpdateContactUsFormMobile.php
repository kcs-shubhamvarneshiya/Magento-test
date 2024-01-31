<?php

namespace Capgemini\ContactUs\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State as AppState;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Amasty\Customform\Api\FormRepositoryInterface;


class UpdateContactUsFormMobile implements DataPatchInterface
{

    const MODULE_CODE = 'Capgemini_ContactUs';

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var AppState
     */
    private $appState;

    public function __construct( PageFactory $pageFactory, AppState $appState
    ) {
        $this->pageFactory = $pageFactory;
        $this->appState = $appState;
    }


    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @return \Capgemini\CreateAccount\Setup\Patch\Data\AddNewCmsBlocks
     */
    public function apply(): self
    {
        $this->appState->emulateAreaCode(
            Area::AREA_ADMINHTML,
            [$this, 'applyUpdateToForms']
        );

        return $this;
    }

    public function applyUpdateToForms():void{

        $contactUsPage = $this->pageFactory->create()->load('contact_us/','identifier');

        $content =  <<<EOD
<style>#html-body [data-pb-style=GQFYECE],#html-body [data-pb-style=GY3C3WN],#html-body [data-pb-style=PBWW4MH]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=GQFYECE]{border-style:none;border-width:1px;border-radius:0;margin:0 0 10px;padding:10px}#html-body [data-pb-style=GY3C3WN],#html-body [data-pb-style=PBWW4MH]{align-self:stretch}#html-body [data-pb-style=PBWW4MH]{width:50%}#html-body [data-pb-style=GY3C3WN]{width:calc(50% - 100px);margin-left:100px;margin-top:94px}#html-body [data-pb-style=N9VJ1KN]{border-style:none}#html-body [data-pb-style=DUS4G2Y],#html-body [data-pb-style=WWFPL54]{max-width:100%;height:auto}#html-body [data-pb-style=F36E21H]{border-style:none}#html-body [data-pb-style=C7GOMJY],#html-body [data-pb-style=NUT34CT]{max-width:100%;height:auto}@media only screen and (max-width: 768px) { #html-body [data-pb-style=F36E21H],#html-body [data-pb-style=N9VJ1KN]{border-style:none} }</style><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="GQFYECE"><div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main"><div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="PBWW4MH"><h2 class="contact-us" data-content-type="heading" data-appearance="default" data-element="main">Contact Us</h2><figure class="only-show-on-mobile" data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="N9VJ1KN"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/Screen_Shot_2022-07-07_at_9.00.38_AM_2.png}}" alt="" title="" data-element="desktop_image" data-pb-style="DUS4G2Y"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/Screen_Shot_2022-07-07_at_9.00.38_AM_2.png}}" alt="" title="" data-element="mobile_image" data-pb-style="WWFPL54"></figure><div data-content-type="html" data-appearance="default" data-element="main">{{widget type="Amasty\Customform\Block\Init" form_id="5" popup="0" template="Amasty_Customform::init.phtml"}}
</div></div><div class="pagebuilder-column vc-right-contact" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="GY3C3WN"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="F36E21H"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/Screen_Shot_2022-07-07_at_9.00.38_AM_2.png}}" alt="" title="" data-element="desktop_image" data-pb-style="NUT34CT"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/Screen_Shot_2022-07-07_at_9.00.38_AM_2.png}}" alt="" title="" data-element="mobile_image" data-pb-style="C7GOMJY"></figure></div></div></div></div>
EOD;
        if($contactUsPage->getId()){

            $contactUsPage->setContent($content);
            $contactUsPage->save();
        }
    }
}
