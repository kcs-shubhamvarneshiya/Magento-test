<?php

namespace Capgemini\BloomreachApi\Plugin;

use Capgemini\BloomreachApi\Helper\Data as ModuleHelper;
use Magento\Framework\Exception\LocalizedException;

class ConfigSave
{
    const SPECIAL_PRICE_LABEL_PLACEHOLDER = 'special-price-label-placeholder';
    /**
     * @var ModuleHelper
     */
    private $moduleHelper;

    public function __construct(ModuleHelper $moduleHelper)
    {
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @param \Magento\Config\Model\Config $subject
     * @return array
     * @throws LocalizedException
     */
    public function beforeSave(\Magento\Config\Model\Config $subject): array
    {
        $section = $subject->getSection();
        switch ($section) {
            case 'bloomreach_search':
                $group = 'sitesearch';

                break;
            case 'bloomreach_collections':
                $group = 'general';

                break;
            default:
                return [];
        }
        $fieldPath = sprintf('groups/%s/fields/', $group);
        $loggedInTemplateConfigPath = $section . '/' . $group . '/' . 'productlist_template_text_logged_in';
        $productListTemplateData = $subject->getDataByPath($fieldPath . 'productlist_template_text');

        if (!$productListTemplate = $productListTemplateData['value'] ?? null) {
            $subject->setDataByPath($loggedInTemplateConfigPath, '');

            return [];
        }

        $priceContainerOpeningTagData = $subject->getDataByPath($fieldPath . 'price_content_tag');

        if (!$priceContainerOpeningTag = $priceContainerOpeningTagData['value'] ?? '') {
            $subject->setDataByPath($loggedInTemplateConfigPath, '');

            return [];
        }

        $classAttribute = $this->validateOpeningTagAndExtractClassAttribute($productListTemplate, $priceContainerOpeningTag);
        $loggedInTemplateContent = $this->moduleHelper->prepareLoggedInTemplate($productListTemplate, $priceContainerOpeningTag, $classAttribute);
        $subject->setDataByPath($loggedInTemplateConfigPath, $loggedInTemplateContent);

        return [];
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    private function validateOpeningTagAndExtractClassAttribute($productListTemplate, $priceContainerOpeningTag)
    {

        if (strpos($productListTemplate, $priceContainerOpeningTag) === false) {

            throw new LocalizedException(__('Price Content Tag is not present in Product List Template.'));
        }

        if (!preg_match(
            '#^<[a-z]+(\s+[a-z-0-9]+="(<%.*%>)*[a-z-_0-9\s]*(<%.*%>)*")+\s*>$#',
            $priceContainerOpeningTag
        )) {

            throw new LocalizedException(__('Price Content Tag is not a valid HTML tag.'));
        }

        if (!preg_match_all(
            '#class="(<%.*%>)*[a-z-_0-9\s]*(<%.*%>)*"#',
            $priceContainerOpeningTag,
            $matches)
        ) {

            throw new LocalizedException(__('Price Content Tag must be endowed with a valid class attribute.'));
        }

        if (count($matches[0]) > 1) {

            throw new LocalizedException(__('Price Content Tag may not contain more than one class attribute.'));
        }

        return $matches[0][0];
    }
}
