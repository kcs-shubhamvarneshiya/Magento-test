<?php
/**
 * Capgemini_OrderSplit
 *
 * @category  Capgemini
 * @author    Oleksander Tolkach <oleksander.tolkach@capgemini.com>
 * @copyright 2022 Capgemini, Inc (www.capgemini.com)
 */

declare(strict_types=1);

namespace Capgemini\OrderSplit\Model;

use Capgemini\CompanyType\Model\Config;
use Exception;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Customer\Model\Session;
use Psr\Log\LoggerInterface;

class CustomConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Config
     */
    protected Config $companyTypeConfig;

    /**
     * @var Session
     */
    protected Session $customerSession;

    /**
     * @var CheckoutSession
     */
    protected CheckoutSession $checkoutSession;

    /**
     * @var Image
     */
    protected Image $imageHelper;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var Configurable
     */
    protected Configurable $catalogProductTypeConfigurable;

    /**
     * @var ProductRepository
     */
    protected ProductRepository $productRepository;

    /**
     * @param Config $companyTypeConfig
     * @param Session $customerSession
     * @param CheckoutSession $checkoutSession
     * @param Image $imageHelper
     * @param LoggerInterface $logger
     * @param Configurable $catalogProductTypeConfigurable
     * @param ProductRepository $productRepository
     */
    public function __construct(
        Config            $companyTypeConfig,
        Session           $customerSession,
        CheckoutSession   $checkoutSession,
        Image             $imageHelper,
        LoggerInterface   $logger,
        Configurable      $catalogProductTypeConfigurable,
        ProductRepository $productRepository
    ) {
        $this->companyTypeConfig = $companyTypeConfig;
        $this->customerSession = $customerSession;
        $this->checkoutSession = $checkoutSession;
        $this->imageHelper = $imageHelper;
        $this->logger = $logger;
        $this->catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
        $this->productRepository = $productRepository;
    }

    /**
     * @return array[]|null[]
     */
    public function getConfig(): array
    {
        $itemsData = null;
        try {
            $customer = $this->customerSession->getCustomer();
            $customerCompanyType = $this->companyTypeConfig->getCustomerCompanyType($customer);

            $businessBackendAttributeCode = 'division';
            $businessCollectionAttributeCode = 'reporting_brand';

            if ($customerCompanyType == Config::WHOLESALE) {
                $items = $this->checkoutSession->getQuote()->getAllItems();
                $displayedProducts = [];

                if ($items) {
                    foreach ($items as $item) {
                        $businessValueId = $item->getProduct()->getData($businessBackendAttributeCode);
                        $collectionValueId =
                            $item->getProduct()->getData($businessCollectionAttributeCode)
                            ?? 0;

                        if ($businessValueId) {
                            $product = $item->getProduct();
                            $parentProductId = $this->getParentProductId($item);
                            if ($product->getTypeId() == 'simple' && $parentProductId) {
                                $product = $this->productRepository->getById($parentProductId);
                            }

                            if (!in_array($product->getId(), $displayedProducts)) {
                                $itemsData[$businessValueId]['division'] =
                                    $product->getAttributeText($businessBackendAttributeCode);

                                $itemsData[$businessValueId]['items'][$collectionValueId] = [
                                    'title' => $product->getAttributeText($businessCollectionAttributeCode),
                                    'items_data' => array_merge(
                                        $itemsData[$businessValueId]['items'][$collectionValueId]['items_data']
                                        ?? [],
                                        [$this->getItemData($product)]
                                    )
                                ];
                            }

                            $displayedProducts[] = $product->getId();
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        if (!empty($itemsData)) {
            array_walk($itemsData, function (&$value) {
                $value['items'] = array_values($value['items']);
            });
        }

        return [
            'items_collection_config' =>
                is_array($itemsData) ?
                    array_values(
                        $itemsData
                    ) : $itemsData
        ];
    }

    /**
     * @param $item
     * @return string|null
     */
    private function getParentProductId($item): ?string
    {
        $parentByChild = $this->catalogProductTypeConfigurable
            ->getParentIdsByChild($item->getProduct()->getId());

        return $parentByChild[0] ?? null;
    }

    /**
     * @param Product $product
     * @return array
     */
    private function getItemData(Product $product): array
    {
        return [
            'id' => $product->getId(),
            'image_url' => $this->imageHelper->init($product, 'product_page_image_small')->getUrl(),
            'name' => $product->getName()
        ];
    }
}
