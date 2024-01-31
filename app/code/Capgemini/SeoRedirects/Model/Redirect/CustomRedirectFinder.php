<?php
/**
 * Capgemini_SeoRedirects
 *
 * @author    Eugene Nazarov <eugene.nazarov@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */
namespace Capgemini\SeoRedirects\Model\Redirect;

use MageWorx\SeoRedirects\Api\Data\CustomRedirectInterface;
use MageWorx\SeoRedirects\Model\Redirect\CustomRedirect;

/**
 * Overrides CustomRedirectFinder to allow use query parameters
 */
class CustomRedirectFinder extends \MageWorx\SeoRedirects\Model\Redirect\CustomRedirectFinder
{
    /**
     * @param $request
     * @param int $storeId
     * @param null $requestRewrite
     */
    public function getRedirectInfo($request, $storeId, $requestRewrite = null)
    {
        $redirectTypeRewriteFragmentSource = $this->redirectTypeRewriteFragmentSource->toArray();

        $conditions = [];

        if ($requestRewrite) {
            foreach ($redirectTypeRewriteFragmentSource as $redirectType => $fragment) {
                if (strpos($requestRewrite->getTargetPath(), $fragment) !== false) {
                    $conditions[] = [
                        CustomRedirectInterface::REQUEST_ENTITY_TYPE       => $redirectType,
                        CustomRedirectInterface::REQUEST_ENTITY_IDENTIFIER => (int)str_replace(
                            $fragment,
                            '',
                            $requestRewrite->getTargetPath()
                        )
                    ];
                }
            }
        }

        $conditions[] = [
            CustomRedirectInterface::REQUEST_ENTITY_TYPE       => CustomRedirect::REDIRECT_TYPE_CUSTOM,
            CustomRedirectInterface::REQUEST_ENTITY_IDENTIFIER => ltrim($request->getRequestUri(), '/')
            //  /customer-service/  or /customer-service
        ];


        /** @var \MageWorx\SeoRedirects\Model\ResourceModel\Redirect\CustomRedirect\Collection $redirectCollection */
        $redirectCollection = $this->redirectCollectionFactory->create();

        $redirectCollection
            ->addStoreFilter($storeId)
            ->addFieldToFilter(CustomRedirectInterface::STATUS, CustomRedirect::STATUS_ENABLED)
            ->addDateRangeFilter();

        $orParts = [];
        foreach ($conditions as $andConditions) {
            $andList = [];
            foreach ($andConditions as $key => $value) {
                $andList[] = $redirectCollection->getSelect()->getConnection()->quoteInto("$key = ?", $value);
            }
            $orParts[] = "(" . implode(' AND ', $andList) . ')';
        }
        //Use TYPE_CONDITION to prevent replacing "?" character in the condition
        $redirectCollection->getSelect()->where(
            new \Zend_Db_Expr(implode(' OR ', $orParts)),
            null,
            \Magento\Framework\DB\Select::TYPE_CONDITION);

        if (count($conditions) > 1) {
            $redirectCollection->addOrder(
                CustomRedirectInterface::REQUEST_ENTITY_TYPE,
                $redirectCollection::SORT_ORDER_ASC
            );
        }

        /** @var \MageWorx\SeoRedirects\Model\Redirect\CustomRedirect $redirect */
        $redirect = $redirectCollection->getFirstItem();

        if (!$redirect->getId()) {
            return null;
        }

        if (!empty($redirectTypeRewriteFragmentSource[$redirect->getTargetEntityType()])) {
            $targetPath    = $redirectTypeRewriteFragmentSource[$redirect->getTargetEntityType(
                )] . $redirect->getTargetEntityIdentifier();
            $targetRewrite = $this->getRewriteByTargetPath($targetPath, $redirect->getStoreId());

            if (!$targetRewrite) {
                return null;
            }

            // Add trailing slash for CMS Page URLs - magento way
            if ($targetRewrite->getEntityType() == 'cms-page') {

                if ($this->helperPage->getIsHomePage(
                    $targetRewrite->getRequestPath(),
                    $targetRewrite->getEntityId(),
                    $storeId
                )) {
                    $url = '';
                } else {
                    $url = $this->helperData->addTrailingSlash($targetRewrite->getRequestPath());
                }
            } else {
                $url = $targetRewrite->getRequestPath();
            }
        } else {
            $url = $redirect->getTargetEntityIdentifier();
        }

        /**
         * Wrap object
         */
        $data = [];

        $data['code']               = $redirect->getRedirectCode();
        $data['url']                = $url;
        $data['is_custom_redirect'] = $redirect->getTargetEntityType() == CustomRedirect::REDIRECT_TYPE_CUSTOM;

        return $data;
    }
}
