<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Capgemini\BloomreachSearchProxy\Controller\Result;

use Capgemini\BloomreachThematic\Controller\Theme\View as ThematicView;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Session;
use Magento\CatalogSearch\Controller\Result\Index as Orig;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\UrlInterface;
use Magento\Search\Model\QueryFactory;
use Magento\Store\Model\StoreManagerInterface;
use Capgemini\BloomreachSearchProxy\Helper\Data as ModuleHelper;

/**
 * Search result.
 */
class Index extends Orig
{
    /**
     * @var Http
     */
    protected $_request;
    private CustomerSession $customerSession;
    private ModuleHelper $moduleHelper;

    public function __construct(
        Context $context,
        Session $catalogSession,
        StoreManagerInterface $storeManager,
        QueryFactory $queryFactory,
        Resolver $layerResolver,
        CustomerSession $customerSession,
        ModuleHelper $moduleHelper
    ) {
        parent::__construct(
            $context,
            $catalogSession,
            $storeManager,
            $queryFactory,
            $layerResolver
        );
        $this->customerSession = $customerSession;
        $this->moduleHelper = $moduleHelper;
    }

    public function execute()
    {
        if ($this->moduleHelper->getIsSearchProxyEnabled()) {
            $queryText = $this->_request->getParam('q');
            if ($queryText && is_string($queryText)) {
                $this->moduleHelper->setRequestType('search');
                $this->moduleHelper->setSearchType('keyword');
                $this->moduleHelper->setPageKey($queryText);
                $this->moduleHelper->setUserId($this->customerSession->getCustomerId());
                $this->_request
                    ->setRouteName('capgemini_bloomreach_search_proxy')
                    ->setAlias(UrlInterface::REWRITE_REQUEST_PATH_ALIAS, 'catalogsearch/result/');
            }
        }

        parent::execute();
    }
}
