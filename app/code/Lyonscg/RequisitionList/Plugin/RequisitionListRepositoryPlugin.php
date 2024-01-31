<?php
/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\RequisitionList\Plugin;

use Magento\RequisitionList\Api\Data\RequisitionListExtensionFactory;
use Magento\RequisitionList\Api\Data\RequisitionListInterface;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;

/**
 * Class RequisitionListRepositoryPlugin
 * @package Lyonscg\RequisitionList\Plugin
 */
class RequisitionListRepositoryPlugin
{

    /**
     * @var RequisitionListExtensionFactory
     */
    protected $requisitionListExtensionFactory;
    /**
     * @var \Lyonscg\SalesPad\Model\CustomerLinkRepository
     */
    private $customerLinkRepository;

    /**
     * RequisitionListRepositoryPlugin constructor.
     * @param RequisitionListExtensionFactory $requisitionListExtensionFactory
     */
    public function __construct(
        RequisitionListExtensionFactory $requisitionListExtensionFactory,
        \Lyonscg\SalesPad\Model\CustomerLinkRepository $customerLinkRepository
    ) {
        $this->requisitionListExtensionFactory = $requisitionListExtensionFactory;
        $this->customerLinkRepository = $customerLinkRepository;
    }

    /**
     * @param RequisitionListRepositoryInterface $subject
     * @param RequisitionListInterface $requisitionList
     * @return RequisitionListInterface
     */
    public function afterGet(RequisitionListRepositoryInterface $subject, RequisitionListInterface $requisitionList)
    {
        try {
            if ($requisitionList->getId()) {
                $extensionAttributes = $requisitionList->getExtensionAttributes();
                if ($extensionAttributes === null) {
                    $extensionAttributes = $this->requisitionListExtensionFactory->create();
                }
                $extensionAttributes->setPoNumber($requisitionList->getPoNumber());
                $requisitionList->setExtensionAttributes($extensionAttributes);
            }
        } catch (\Exception $e) {
        }
        return $requisitionList;
    }

    /**
     * @param RequisitionListRepositoryInterface $subject
     * @param RequisitionListInterface $requisitionList
     * @param $processName
     * @return array
     */
    public function beforeSave(
        RequisitionListRepositoryInterface $subject,
        RequisitionListInterface $requisitionList,
        $processName = false
    ) {
        if (!$requisitionList->getSalesPadCustomerNum()) {
            $customerId = $requisitionList->getCustomerId();
            try {
                $spCustomerNum = $this->customerLinkRepository->get($customerId, null, null);
            } catch (\Exception $exception) {
                $spCustomerNum = null;
            }

            if ($spCustomerNum) {
                $requisitionList->setSalesPadCustomerNum($spCustomerNum);
            }
        }

        return [$requisitionList, $processName];
    }

    /**
     * @param RequisitionListRepositoryInterface $subject
     * @param RequisitionListInterface $result
     * @param RequisitionListInterface $requisitionList
     * @return RequisitionListInterface
     */
    public function afterSave(
        RequisitionListRepositoryInterface $subject,
        RequisitionListInterface $result,
        RequisitionListInterface $requisitionList
    ) {
        try {
            $result->setPoNumber($requisitionList->getExtensionAttributes()->getPoNumber())->save();
        } catch (\Exception $e) {
        }
        return $result;
    }
}
