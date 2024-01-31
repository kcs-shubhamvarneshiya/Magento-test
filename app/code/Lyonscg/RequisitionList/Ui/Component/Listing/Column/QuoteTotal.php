<?php
/**
 * Lyonscg_RequisitionList
 *
 * @category  Lyons
 * @package   Lyonscg_RequisitionList
 * @author    Tetiana Mamchik<tanya.mamchik@capgemini.com>
 * @copyright Copyright (c) 2020 Lyons Consulting Group (www.lyonscg.com)
 */
namespace Lyonscg\RequisitionList\Ui\Component\Listing\Column;

use Lyonscg\RequisitionList\ViewModel\RequisitionListTotalFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\RequisitionList\Api\RequisitionListRepositoryInterface;

/**
 * Class CreatedBy
 * @package Lyonscg\RequisitionList\Ui\Component\Listing\Column
 */
class QuoteTotal extends Column
{
    /**
     * @var RequisitionListRepositoryInterface
     */
    protected $requisitionListRepository;

    /**
     * @var RequisitionListTotalFactory
     */
    protected $requesitionListTotalFactory;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * QuoteTotal constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param RequisitionListRepositoryInterface $requisitionListRepository
     * @param RequisitionListTotalFactory $requisitionListTotalFactory
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        RequisitionListRepositoryInterface $requisitionListRepository,
        RequisitionListTotalFactory $requisitionListTotalFactory,
        PriceCurrencyInterface $priceCurrency,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->requisitionListRepository = $requisitionListRepository;
        $this->requesitionListTotalFactory = $requisitionListTotalFactory;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $requisitionList = $this->requisitionListRepository->get($item['entity_id']);
                $taxRate = 7; //Need to get actual tax rate

                /** @var \Lyonscg\RequisitionList\ViewModel\RequisitionListTotal $requisitionTotalViewModel */
                $requisitionTotalViewModel = $this->requesitionListTotalFactory->create();
                if (!empty($requisitionTotalViewModel)) {
                    $requisitionTotalViewModel->setRequisitionList($requisitionList)->setTaxRate($taxRate);
                }
                $requisitionListSubtotal = $requisitionTotalViewModel->getRequisitionListSubtotal(true);
                $item['quote_total'] = \strip_tags($requisitionListSubtotal) ?? 0;
            }
        }
        
        return $dataSource;
    }

    public function getTaxRate()
    {
        // TODO - get actual tax rate
        return 1.5;
    }
}
