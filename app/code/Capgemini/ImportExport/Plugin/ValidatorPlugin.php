<?php
namespace Capgemini\ImportExport\Plugin;

use Magento\Catalog\Model\Product;
use Psr\Log\LogLevel;

/**
 * Class ValidatorPlugin
 * @package Capgemini\ImportExport\Plugin
 */
class ValidatorPlugin
{
    /**
     * @var \Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory
     */
    protected $optionLabelFactory;

    /**
     * @var \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory
     */
    protected $optionFactory;

    /**
     * @var \Magento\Eav\Model\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var \Magento\Eav\Api\AttributeOptionManagementInterface
     */
    protected $optionManager;

    /**
     * @var
     */
    protected $eavConfig;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var Product\Attribute\Repository
     */
    protected $attributeRepo;

    /**
     * @var \Capgemini\ImportExport\Helper\Info
     */
    protected $importHelper;

    /**
     * ValidatorPlugin constructor.
     * @param \Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory $optionLabelFactory
     * @param \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory $optionFactory
     * @param \Magento\Eav\Api\AttributeOptionManagementInterface $optionManager
     * @param \Magento\Eav\Model\AttributeRepository $attributeRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param Product\Attribute\Repository $attributeRepo
     */
    public function __construct(
        \Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory $optionLabelFactory,
        \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory $optionFactory,
        \Magento\Eav\Api\AttributeOptionManagementInterface $optionManager,
        \Magento\Eav\Model\AttributeRepository $attributeRepository,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Model\Product\Attribute\Repository $attributeRepo,
        \Capgemini\ImportExport\Helper\Info $importHelper
    )
    {
        $this->optionLabelFactory = $optionLabelFactory;
        $this->optionFactory = $optionFactory;
        $this->optionManager = $optionManager;
        $this->attributeRepository = $attributeRepository;
        $this->logger = $logger;
        $this->attributeRepo = $attributeRepo;
        $this->importHelper = $importHelper;
    }

    /**
     * @param $subject
     * @param $attrCode
     * @param array $attrParams
     * @param array $rowData
     * @return array
     */
    public function beforeIsAttributeValid($subject, $attrCode, array $attrParams, array $rowData)
    {
        $rowData[$attrCode] = !empty($rowData[$attrCode]) ? trim($rowData[$attrCode]) : '';

        return [$attrCode, $attrParams, $rowData];
    }


    /**
     * Is attribute valid
     *
     * @param string $attrCode
     * @param array $attrParams
     * @param array $rowData
     * @return bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function afterIsAttributeValid($subject, $isValid, $attrCode, $attribute, $rowData)
    {
        $data = $this->importHelper->getRequest()->getPostValue();

        if ($data && isset($data['entity']) && $data['entity'] == $this->importHelper::DEFAULT_IMPORT_PRODUCT_ENTITY_CODE) {
            return ;
        }
        if (!$isValid) {
            $invalidValue = $rowData[$attrCode];
            if ($invalidValue) {
                $optionsNew = $this->attributeRepo->get($attrCode)->getOptions();
                $loadedOptions = [];
                foreach ($optionsNew as $option)
                {
                    $loadedOptions[] = $option->getLabel();
                }

                if (!in_array($invalidValue, $loadedOptions)) {
                    $this->logger->log(LogLevel::NOTICE,'attr code '. $attrCode . ' val ' . $invalidValue);
                    $this->createAbsentOption($attrCode, $invalidValue);
                }
            }
        }
    }

    /**
     * @param $attrCode
     * @param $optionValue
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    protected function createAbsentOption($attrCode, $optionValue)
    {
        $optionLabelAdmin = $this->optionLabelFactory->create();
        $optionLabelAdmin->setStoreId(0);
        $optionLabelAdmin->setLabel($optionValue);

        $optionLabel = $this->optionLabelFactory->create();
        $optionLabel->setStoreId(1);
        $optionLabel->setLabel($optionValue);

        $option = $this->optionFactory->create();
        $option->setLabel($optionValue);
        $option->setStoreLabels([$optionLabelAdmin, $optionLabel]);
        $option->setSortOrder(0);
        $option->setIsDefault(false);
        $attribute = $this->attributeRepository->get(Product::ENTITY, $attrCode);
        $this->optionManager->add(
            Product::ENTITY,
            $attribute->getId(),
            $option
        );
    }


    public function createAbsentOptionPub($attrCode, $optionValue)
    {
        $optionLabelAdmin = $this->optionLabelFactory->create();
        $optionLabelAdmin->setStoreId(0);
        $optionLabelAdmin->setLabel($optionValue);

        $optionLabel = $this->optionLabelFactory->create();
        $optionLabel->setStoreId(1);
        $optionLabel->setLabel($optionValue);

        $option = $this->optionFactory->create();
        $option->setLabel($optionValue);
        $option->setStoreLabels([$optionLabelAdmin, $optionLabel]);
        $option->setSortOrder(0);
        $option->setIsDefault(false);
        $attribute = $this->attributeRepository->get(Product::ENTITY, $attrCode);
        return $this->optionManager->add(
            Product::ENTITY,
            $attribute->getId(),
            $option
        );
    }
}
