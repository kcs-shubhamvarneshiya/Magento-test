<?php
/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */

namespace Lyonscg\RequisitionList\Controller\Requisition;

use Amasty\Orderattr\Api\CheckoutDataRepositoryInterface;
use Amasty\Orderattr\Model\Entity\EntityResolver;
use Lyonscg\SalesPad\Helper\Quote as QuoteHelper;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory as RegionCollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\RequisitionList\Api\RequisitionListManagementInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;
use Magento\RequisitionList\Model\RequisitionList;
use Magento\RequisitionList\Model\RequisitionList\ItemSelector;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Index
 */
class CreateOrder extends Action
{
    const AMASTY_FORM_CODE = 'amasty_checkout_shipping';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CartManagementInterface
     */
    protected $cartManagement;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var RequisitionListRepositoryInterface
     */
    protected $requisitionListRepository;

    /**
     * @var CartItemInterface
     */
    protected $cartItem;

    /**
     * @var CartItemRepositoryInterface
     */
    protected $itemRepository;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ItemSelector
     */
    protected $itemSelector;

    /**
     * @var RequisitionListManagementInterface
     */
    protected $listManagement;

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var RegionCollectionFactory
     */
    protected $regionCollectionFactory;

    /**
     * @var CartExtensionFactory
     */
    protected $cartExtensionFactory;

    /**
     * @var EntityResolver
     */
    protected $entityResolver;

    /**
     * @var CheckoutDataRepositoryInterface
     */
    protected $checkoutDataRepository;

    /**
     * @var QuoteHelper
     */
    protected $quoteHelper;

    /**
     * CreateOrder constructor.
     * @param Context $context
     * @param CartManagementInterface $cartManagement
     * @param CartRepositoryInterface $quoteRepository
     * @param Session $customerSession
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequisitionListRepositoryInterface $requisitionListRepository
     * @param CartItemInterface $cartItem
     * @param CartItemRepositoryInterface $itemRepository
     * @param ManagerInterface $messageManager
     * @param StoreManagerInterface $storeManager
     * @param ItemSelector $itemSelector
     * @param RequisitionListManagementInterface $listManagement
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param SerializerInterface $serializer
     * @param RegionCollectionFactory $regionCollectionFactory
     * @param CartExtensionFactory $cartExtensionFactory
     * @param EntityResolver $entityResolver
     * @param CheckoutDataRepositoryInterface $checkoutDataRepository
     * @param QuoteHelper $quoteHelper
     */
    public function __construct(
        Context $context,
        CartManagementInterface $cartManagement,
        CartRepositoryInterface $quoteRepository,
        Session $customerSession,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequisitionListRepositoryInterface $requisitionListRepository,
        CartItemInterface $cartItem,
        CartItemRepositoryInterface $itemRepository,
        ManagerInterface $messageManager,
        StoreManagerInterface $storeManager,
        ItemSelector $itemSelector,
        RequisitionListManagementInterface $listManagement,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        SerializerInterface $serializer,
        RegionCollectionFactory $regionCollectionFactory,
        CartExtensionFactory $cartExtensionFactory,
        EntityResolver $entityResolver,
        CheckoutDataRepositoryInterface $checkoutDataRepository,
        QuoteHelper $quoteHelper
    ) {
        parent::__construct($context);
        $this->cartManagement = $cartManagement;
        $this->quoteRepository = $quoteRepository;
        $this->customerSession = $customerSession;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->requisitionListRepository = $requisitionListRepository;
        $this->cartItem = $cartItem;
        $this->itemRepository = $itemRepository;
        $this->messageManager = $messageManager;
        $this->storeManager = $storeManager;
        $this->itemSelector = $itemSelector;
        $this->listManagement = $listManagement;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->serializer = $serializer;
        $this->regionCollectionFactory = $regionCollectionFactory;
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->entityResolver = $entityResolver;
        $this->checkoutDataRepository = $checkoutDataRepository;
        $this->quoteHelper = $quoteHelper;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $requisitionListId = $this->getRequest()->getParam('requisition_id');
        $requisitionList = $this->requisitionListRepository->get($requisitionListId);
        $resultRedirect = $this->resultRedirectFactory->create();
        $isReplace = false;
        if (count($requisitionList->getItems()) == 0) {
            $this->messageManager->addNoticeMessage(__('The Quote \' %1 \' has no products', $requisitionList->getName()));
            $redirectUrl = 'requisition_list/requisition/index';
            return $resultRedirect->setPath($redirectUrl);
        }
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('customer_id', $this->customerSession->getCustomerId())
            ->addFilter('is_active', 1)
            ->create();
        $activeCustomerQuotes = $this->quoteRepository->getList($searchCriteria);
        if ($activeCustomerQuotes->getTotalCount() > 0) {
            $customerCart = $this->cartManagement->getCartForCustomer(
                $this->customerSession->getCustomerId()
            );
            if ($customerCart->getItemsCount() > 0) {
                $isReplace = ($this->getRequest()->getParam('action') == 'replace') ? true : false;
            }
            $quoteId = $customerCart->getId();
        } else {
            $quoteId = $this->cartManagement->createEmptyCartForCustomer($this->customerSession->getCustomerId());
        }
        try {
            $items = $this->itemSelector->selectItemsFromRequisitionList(
                $requisitionList->getId(),
                $this->getRequisitionListItemIds($requisitionList),
                $this->storeManager->getWebsite()->getId()
            );
            $addedItems = $this->listManagement->placeItemsInCart($quoteId, $items, $isReplace);
            $this->copyExtensionAttributes($requisitionList, $quoteId);
            $this->setSalespadQuoteNumToCart($quoteId, $this->getSalespadDocNum($requisitionList));
            try {
                $requisitionList->setData('__no_sync', true);
                $this->requisitionListRepository->delete($requisitionList);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the Quote \'%1\'', $requisitionList->getName()));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong!'));
            $redirectUrl = 'requisition_list/requisition/index';
            return $resultRedirect->setPath($redirectUrl);
        }
        $this->messageManager->addSuccessMessage(__('All %1 products from Quote \' %2 \' were added to your cart', count($addedItems), $requisitionList->getName()));
        $redirectUrl = 'checkout/cart/';
        return $resultRedirect->setPath($redirectUrl);
    }

    /**
     * @param RequisitionListInterface $requisitionList
     * @return array
     */
    protected function getRequisitionListItemIds($requisitionList)
    {
        $ids = [];
        $items =  $requisitionList->getItems();
        foreach ($items as $item) {
            $ids[] = $item->getId();
        }
        return $ids;
    }

    /**
     * @param array $data
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    protected function requisitionListToCookies($data)
    {
        $region = $this->regionCollectionFactory->create()->getItemByColumnValue('region_id', '');
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setPath('/');
        $this->cookieManager
            ->setPublicCookie(
                $this->serializer->serialize($data),
                $metadata
            );
    }

    /**
     * @param RequisitionListInterface $requisitionList
     * @param $quoteId
     * @throws NoSuchEntityException
     */
    protected function copyExtensionAttributes($requisitionList, $quoteId)
    {
        $requisitionListExtensions = $requisitionList->getExtensionAttributes();
        $poNumber = '';
        if ($requisitionListExtensions) {
            $poNumber = $requisitionListExtensions->getPoNumber();
        }
        $entity = $this->entityResolver->getEntityByQuoteId($quoteId);
        //$extensions = $quote->getExtensionAttributes() ?? $this->quoteExtensionFactory->create();
        $entity->setCustomAttribute('comments', $requisitionList->getDescription());
        $entity->setCustomAttribute('po_number', $poNumber);
        $entity->setCustomAttribute('project_name', $requisitionList->getName());

        $this->checkoutDataRepository->save($quoteId, self::AMASTY_FORM_CODE, null, $entity);
    }

    protected function setSalespadQuoteNumToCart($quoteId, $salespadQuoteNum)
    {
        try {
            $quote = $this->quoteRepository->get($quoteId);
            $quote->setData('salespad_quote_num', $salespadQuoteNum);
            $extensionAttributes = $quote->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->cartExtensionFactory->create();
            $extensionAttributes->setSalespadQuoteNum($salespadQuoteNum);
            $quote->setExtensionAttributes($extensionAttributes);
            $this->quoteRepository->save($quote);
        } catch (NoSuchEntityException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getSalespadDocNum(RequisitionListInterface $requisitionList)
    {
        return $this->quoteHelper->getSalesDocNum($requisitionList);
    }
}
