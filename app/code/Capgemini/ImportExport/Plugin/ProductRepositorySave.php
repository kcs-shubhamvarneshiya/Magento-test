<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Capgemini\ImportExport\Plugin;

use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\InputException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\ConfigurableProduct\Api\Data\OptionInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LogLevel;

class ProductRepositorySave
{
    /**
     * @var ProductAttributeRepositoryInterface
     */
    private $productAttributeRepository;

    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     * @param ProductFactory $productFactory
     */
    public function __construct(
        ProductAttributeRepositoryInterface $productAttributeRepository,
        ProductFactory $productFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->productAttributeRepository = $productAttributeRepository;
        $this->productFactory = $productFactory;
        $this->logger = $logger;
    }

    /**
     * Validate product links of configurable product
     *
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $product
     * @param bool $saveOptions
     * @return array
     * @throws InputException
     * @throws NoSuchEntityException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(
        ProductRepositoryInterface $subject,
        ProductInterface $product,
        $saveOptions = false
    ): array {
        $result[] = $product;
        if ($product->getTypeId() !== Configurable::TYPE_CODE) {
            return $result;
        }

        $extensionAttributes = $product->getExtensionAttributes();
        if ($extensionAttributes === null) {
            return $result;
        }

        $configurableLinks = (array) $extensionAttributes->getConfigurableProductLinks();
        $configurableOptions = (array) $extensionAttributes->getConfigurableProductOptions();

        if (empty($configurableLinks) && empty($configurableOptions)) {
            return $result;
        }

        $attributeCodes = [];
        /** @var OptionInterface $configurableOption */
        foreach ($configurableOptions as $configurableOption) {
            $eavAttribute = $this->productAttributeRepository->get($configurableOption->getAttributeId());
            $attributeCode = $eavAttribute->getAttributeCode();
            $attributeCodes[] = $attributeCode;
        }
        $this->validateProductLinks($attributeCodes, $configurableLinks);

        return $result;
    }
    /**
     * Validate product links and reset configurable attributes to configurable product
     *
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $result
     * @param ProductInterface $product
     * @param bool $saveOptions
     * @return ProductInterface
     * @throws CouldNotSaveException
     * @throws InputException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        ProductRepositoryInterface $subject,
        ProductInterface $result,
        ProductInterface $product,
        $saveOptions = false
    ) {
        if ($product->getTypeId() !== Configurable::TYPE_CODE) {
            return $result;
        }

        $extensionAttributes = $result->getExtensionAttributes();
        if ($extensionAttributes === null) {
            return $result;
        }

        $configurableLinks = (array) $extensionAttributes->getConfigurableProductLinks();
        $configurableOptions = (array) $extensionAttributes->getConfigurableProductOptions();

        if (empty($configurableLinks) && empty($configurableOptions)) {
            return $result;
        }

        $attributeCodes = [];
        /** @var OptionInterface $configurableOption */
        foreach ($configurableOptions as $configurableOption) {
            $eavAttribute = $this->productAttributeRepository->get($configurableOption->getAttributeId());
            $attributeCode = $eavAttribute->getAttributeCode();
            $attributeCodes[] = $attributeCode;
        }
        $this->validateProductLinks($attributeCodes, $configurableLinks);
        $result->getTypeInstance()->resetConfigurableAttributes($product);

        return $result;
    }

    /**
     * @param array $attributeCodes
     * @param array $linkIds
     * @return $this
     * @throws InputException
     */
    private function validateProductLinks(array $attributeCodes, array $linkIds)
    {
        $valueMap = [];

        foreach ($linkIds as $productId) {
            $variation = $this->productFactory->create()->load($productId);
            $valueKey = '';
            foreach ($attributeCodes as $attributeCode) {
                if (!$variation->getData($attributeCode)) {
                    throw new InputException(
                        __('Product with id "%1" does not contain required attribute "%2".', $productId, $attributeCode)
                    );
                }
                $valueKey = $valueKey . $attributeCode . ':' . $variation->getData($attributeCode) . ';';
            }
            if (isset($valueMap[$valueKey])) {

                $double1 = $this->productFactory->create()->load($productId);
                $double2 = $this->productFactory->create()->load( $valueMap[$valueKey]);

                $this->logger->log(LogLevel::NOTICE, "The same " . $double1->getSku() . ' ' . $double2->getSku());
            }
            $valueMap[$valueKey] = $productId;
        }
    }
}
