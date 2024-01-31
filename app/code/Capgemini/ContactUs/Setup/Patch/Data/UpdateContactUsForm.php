<?php

namespace Capgemini\ContactUs\Setup\Patch\Data;

use Magento\Cms\Model\PageFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State as AppState;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Amasty\Customform\Api\FormRepositoryInterface;


class UpdateContactUsForm implements DataPatchInterface
{

    const MODULE_CODE = 'Capgemini_ContactUs';
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var AppState
     */
    private $appState;

    public function __construct(
        PageFactory $pageFactory,
        FormRepositoryInterface $formRepository,
        AppState $appState
    ) {
        $this->pageFactory = $pageFactory;
        $this->formRepository = $formRepository;
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

        $formJson ='[[{"type":"textinput","name":"firstname","entity_id":"","label":"First Name","className":"form-control","style":"","placeholder":"","required":"1","description":"","validation":"","regexp":"","errorMessage":"","value":"","maxlength":"","layout":"two","parentType":"","dependency":[]},{"type":"textinput","name":"lastname","entity_id":"","label":"Last Name","className":"form-control","style":"","placeholder":"","required":"1","description":"","validation":"","regexp":"","errorMessage":"","value":"","maxlength":"","layout":"two","parentType":"","dependency":[]},{"type":"textinput","name":"email","entity_id":"","label":"Email","className":"form-control","style":"","placeholder":"","required":"1","description":"","validation":"validate-email","regexp":"","errorMessage":"","value":"","maxlength":"","layout":"one","parentType":"","dependency":[]},{"type":"textinput","name":"phone","entity_id":"","label":"Phone Number (Optional)","className":"form-control","style":"","placeholder":"","description":"","validation":"","regexp":"","errorMessage":"","value":"","maxlength":"","layout":"one","parentType":"","dependency":[]},{"type":"dropdown","name":"topic","entity_id":"","label":"Your Topic","className":"form-control","style":"","placeholder":"","required":"1","description":"","dependencyField":"Choose an Option...","layout":"one","parentType":"options","values":[{"label":"- Select Topic","value":"select topic"},{"label":"Customer Service","value":"service","selected":"1"},{"label":"Product","value":"product"},{"label":"Service","value":"service"},{"label":"Other","value":"other"}],"dependency":[{"field":"Choose an Option...","value":"Choose an Option..."}]},{"type":"file","name":"file","entity_id":"","label":"File","className":"form-control","style":"","description":"","allowed_extension":"","max_file_size":"","layout":"one","parentType":"select","dependency":[]},{"type":"textarea","name":"comment","entity_id":"","label":"Comment","className":"form-control","style":"min-height:150px;","placeholder":"Describe your concern or question...","required":"1","description":"","rows":"","value":"","maxlength":"","layout":"one","parentType":"","dependency":[]}]]';
        $formModel = $this->formRepository->getByFormCode('contact');
        if($formModel->getFormId()){
            $formModel->setSuccessUrl('/');
            $formModel->setFormJson($formJson);

            $this->formRepository->save($formModel);
        }

        $contactUsPage = $this->pageFactory->create()->load('contact_us/','identifier');

        $content =  <<<EOD
<style>#html-body [data-pb-style=HM52NIT],#html-body [data-pb-style=QDCD0IN],#html-body [data-pb-style=QRATOFS]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=QDCD0IN]{border-style:none;border-width:1px;border-radius:0;margin:0 0 10px;padding:10px}#html-body [data-pb-style=HM52NIT],#html-body [data-pb-style=QRATOFS]{align-self:stretch}#html-body [data-pb-style=HM52NIT]{width:50%}#html-body [data-pb-style=QRATOFS]{width:calc(50% - 100px);margin-left:100px;margin-top:94px}#html-body [data-pb-style=NW4PUNL]{border-style:none}#html-body [data-pb-style=TAT08XD],#html-body [data-pb-style=YQC44F2]{max-width:100%;height:auto}@media only screen and (max-width: 768px) { #html-body [data-pb-style=NW4PUNL]{border-style:none} }</style><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="QDCD0IN"><div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main"><div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="HM52NIT"><h2 class="contact-us" data-content-type="heading" data-appearance="default" data-element="main">Contact Us</h2><div data-content-type="html" data-appearance="default" data-element="main">{{widget type="Amasty\Customform\Block\Init" form_id="5" popup="0" template="Amasty_Customform::init.phtml"}}
</div></div><div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="QRATOFS"><figure data-content-type="image" data-appearance="full-width" data-element="main" data-pb-style="NW4PUNL"><img class="pagebuilder-mobile-hidden" src="{{media url=wysiwyg/Screen_Shot_2022-07-07_at_9.00.38_AM.png}}" alt="" title="" data-element="desktop_image" data-pb-style="TAT08XD"><img class="pagebuilder-mobile-only" src="{{media url=wysiwyg/Screen_Shot_2022-07-07_at_9.00.38_AM.png}}" alt="" title="" data-element="mobile_image" data-pb-style="YQC44F2"></figure></div></div></div></div>
EOD;
        if($contactUsPage->getId()){

            $contactUsPage->setContent($content);
            $contactUsPage->save();
        }
    }
}
