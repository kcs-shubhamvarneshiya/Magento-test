<?php

declare(strict_types=1);

namespace Capgemini\CompanyType\Model\Adminhtml\System\Config\Source\Customer;

use Magento\CustomerSegment\Model\ResourceModel\Segment\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;

class Segment implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $segmentCollectionFactory;
    private StoreManagerInterface $storeManager;
    private RequestInterface $request;
    private $websiteId;

    /**
     * @param CollectionFactory $segmentCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param RequestInterface $request
     * @param int|null $websiteId
     */
    public function __construct(
        CollectionFactory $segmentCollectionFactory,
        StoreManagerInterface $storeManager,
        RequestInterface $request,
        ?int $websiteId = null
    ) {
        $this->segmentCollectionFactory = $segmentCollectionFactory;
        $this->storeManager = $storeManager;
        $this->request = $request;
        $this->websiteId = $websiteId ?? $this->getWebsiteId();
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $segmentCollection = $this->segmentCollectionFactory
            ->create()
            ->addFieldToFilter('apply_to', \Magento\CustomerSegment\Model\Segment::APPLY_TO_REGISTERED);

        if ($this->websiteId) {
            $segmentCollection->addWebsiteFilter($this->websiteId);
        }

        $optionArray = $segmentCollection->loadData()->toOptionArray() ?? [];
        array_unshift($optionArray, [
            'value' => '',
            'label' => __('--Please select--')
        ]);

        return $optionArray;
    }

    public function setWebsiteId(?int $websiteId = null): static
    {
        $this->websiteId = $websiteId;

        return $this;
    }

    private function getWebsiteId()
    {
        try {
            if (!$websiteId = $this->request->getParam('website')) {
                $storeId = $this->request->getParam('store');
                $websiteId = $storeId
                    ? $this->storeManager->getStore((int) $storeId)->getWebsiteId()
                    : null;
            }
        } catch (\Exception $exception) {
            $websiteId = null;
        }

        return $websiteId ?: null;
    }
}
