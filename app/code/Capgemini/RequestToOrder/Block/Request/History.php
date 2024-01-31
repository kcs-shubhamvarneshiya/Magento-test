<?php
/**
 * Capgemini_RequestToOrder
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\RequestToOrder\Block\Request;

use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\Collection;
use Capgemini\RequestToOrder\Model\ResourceModel\OrderRequest\CollectionFactory as OrderRequestCollectionFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Pager;

class History extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Capgemini_RequestToOrder::request/history.phtml';

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var OrderRequestCollectionFactory
     */
    private $orderRequestCollectionFactory;

    protected $requests;

    /**
     * @param Context $context
     * @param CustomerSession $customerSession
     * @param OrderRequestCollectionFactory $orderRequestCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context                       $context,
        CustomerSession               $customerSession,
        OrderRequestCollectionFactory $orderRequestCollectionFactory,
        array                         $data = []
    )
    {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->orderRequestCollectionFactory = $orderRequestCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Request To Order'));
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        if ($this->getRequests()) {
            $pager = $this->getLayout()->createBlock(
                Pager::class,
                'orequest.request.history.pager'
            )->setCollection(
                $this->getRequests()
            );
            $this->setChild('pager', $pager);
            $this->getRequests()->load();
        }
    }

    /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return Collection
     */
    public function getCollectionFactory()
    {
        return $this->orderRequestCollectionFactory->create();
    }

    /**
     * @return string
     */
    public function getViewUrl($request)
    {
        return $this->getUrl('orequest/request', ['id' => $request->getId()]);
    }

    /**
     * @return Collection|false
     */
    public function getRequests()
    {
        if (!($customerId = $this->customerSession->getCustomerId())) {
            return false;
        }

        if (!$this->requests) {

            $this->requests = $this->getCollectionFactory()
                ->addFieldToSelect('*')
                ->addFieldToFilter('customer_id', $customerId)
                ->setOrder(
                    'created_at',
                    'desc'
                );
        }

        return $this->requests;
    }
}
